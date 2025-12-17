<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DeliveryController extends Controller
{
    /**
     * Daftar rental yang menunggu pengantaran dan konfirmasi
     */
    public function index()
    {
        Gate::authorize('access-kasir');
        
        // Rental yang sudah dibayar, menunggu diantar (delivery method = delivery atau null untuk rental lama)
        $pendingDeliveries = Rental::where('status', 'menunggu_pengantaran')
            ->where(function($q) {
                $q->where('delivery_method', 'delivery')
                  ->orWhereNull('delivery_method');
            })
            ->whereNull('delivered_at')
            ->with(['customer', 'items.rentable'])
            ->latest()
            ->paginate(10, ['*'], 'pending_page');
        
        // Rental yang menunggu diambil di toko (delivery method = pickup)
        $pendingPickups = Rental::where('status', 'menunggu_pengantaran')
            ->where('delivery_method', 'pickup')
            ->with(['customer', 'items.rentable'])
            ->latest()
            ->paginate(10, ['*'], 'pickup_page');
            
        // Rental yang sudah diantar, menunggu konfirmasi user
        $awaitingConfirmation = Rental::where('status', 'menunggu_pengantaran')
            ->whereNotNull('delivered_at')
            ->whereNull('delivery_confirmed_at')
            ->with(['customer', 'items.rentable', 'deliverer'])
            ->latest()
            ->paginate(10, ['*'], 'awaiting_page');
        
        // Statistik
        $stats = [
            'pending_delivery' => Rental::where('status', 'menunggu_pengantaran')
                ->where(function($q) {
                    $q->where('delivery_method', 'delivery')
                      ->orWhereNull('delivery_method');
                })
                ->whereNull('delivered_at')
                ->count(),
            'pending_pickup' => Rental::where('status', 'menunggu_pengantaran')
                ->where('delivery_method', 'pickup')
                ->count(),
            'awaiting_confirmation' => Rental::where('status', 'menunggu_pengantaran')
                ->where(function($q) {
                    $q->where('delivery_method', 'delivery')
                      ->orWhereNull('delivery_method');
                })
                ->whereNotNull('delivered_at')
                ->whereNull('delivery_confirmed_at')
                ->count(),
            'delivered_today' => Rental::whereDate('delivered_at', today())->count(),
            'confirmed_today' => Rental::whereDate('delivery_confirmed_at', today())->count(),
        ];
            
        return view('kasir.deliveries.index', compact('pendingDeliveries', 'pendingPickups', 'awaitingConfirmation', 'stats'));
    }

    /**
     * Detail rental untuk pengantaran
     */
    public function show(Rental $rental)
    {
        Gate::authorize('access-kasir');
        
        $rental->load(['customer', 'items.rentable', 'deliverer']);
        
        return view('kasir.deliveries.show', compact('rental'));
    }
    
    /**
     * Admin/Kasir konfirmasi barang sudah diantarkan oleh kurir
     */
    public function confirmDelivery(Request $request, Rental $rental)
    {
        Gate::authorize('access-kasir');
        
        if ($rental->status !== 'menunggu_pengantaran') {
            return back()->with('error', 'Rental tidak dalam status menunggu pengantaran.');
        }
        
        if ($rental->delivered_at !== null) {
            return back()->with('error', 'Barang sudah dikonfirmasi diantar sebelumnya.');
        }
        
        $validated = $request->validate([
            'delivery_notes' => 'nullable|string|max:500',
        ]);
        
        $rental->update([
            'delivered_at' => now(),
            'delivered_by' => auth()->id(),
            'delivery_notes' => $validated['delivery_notes'] ?? null,
        ]);
        
        \Log::info('Delivery confirmed by kasir/admin', [
            'rental_id' => $rental->id,
            'rental_kode' => $rental->kode,
            'delivered_by' => auth()->id(),
            'customer' => $rental->customer->name ?? 'Unknown',
        ]);
        
        // Kirim notifikasi email ke pelanggan (Sesuai UC017)
        if ($rental->customer && $rental->customer->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($rental->customer)->send(new \App\Mail\DeliveryOnTheWay($rental));
                \Log::info('Delivery email sent to ' . $rental->customer->email);
            } catch (\Exception $e) {
                \Log::error('Failed to send delivery email: ' . $e->getMessage());
            }
        }
        
        return back()->with('success', 'Pengantaran berhasil dikonfirmasi. Menunggu konfirmasi penerimaan dari pelanggan.');
    }

    /**
     * Kasir konfirmasi barang sudah diambil oleh pelanggan di toko (pickup)
     */
    public function confirmPickup(Request $request, Rental $rental)
    {
        Gate::authorize('access-kasir');
        
        if ($rental->status !== 'menunggu_pengantaran') {
            return back()->with('error', 'Rental tidak dalam status menunggu pengantaran.');
        }
        
        if ($rental->delivery_method !== 'pickup') {
            return back()->with('error', 'Rental ini bukan metode ambil di toko.');
        }
        
        $validated = $request->validate([
            'pickup_notes' => 'nullable|string|max:500',
        ]);
        
        // Langsung update status ke sedang_disewa karena pelanggan sudah mengambil barang
        $rental->update([
            'delivered_at' => now(),
            'delivery_confirmed_at' => now(),
            'delivered_by' => auth()->id(),
            'delivery_notes' => $validated['pickup_notes'] ?? 'Diambil di toko',
            'status' => 'sedang_disewa',
            'start_at' => now(), // Waktu sewa mulai sekarang
        ]);
        
        // Kurangi stok
        foreach ($rental->items as $item) {
            if ($item->rentable) {
                $stockField = $item->rentable_type === 'App\Models\UnitPS' ? 'stock' : 'stok';
                $item->rentable->decrement($stockField, $item->quantity);
            }
        }
        
        \Log::info('Pickup confirmed by kasir', [
            'rental_id' => $rental->id,
            'rental_kode' => $rental->kode,
            'confirmed_by' => auth()->id(),
            'customer' => $rental->customer->name ?? 'Unknown',
        ]);
        
        return back()->with('success', 'Pengambilan barang berhasil dikonfirmasi. Status rental: Sedang Disewa.');
    }

    /**
     * Batalkan pengantaran (jika ada masalah)
     */
    public function cancelDelivery(Request $request, Rental $rental)
    {
        Gate::authorize('access-kasir');
        
        if ($rental->status !== 'menunggu_pengantaran') {
            return back()->with('error', 'Rental tidak dalam status menunggu pengantaran.');
        }
        
        // Hanya bisa batalkan jika sudah mark delivered tapi belum dikonfirmasi user
        if ($rental->delivered_at === null) {
            return back()->with('error', 'Pengantaran belum dikonfirmasi.');
        }
        
        if ($rental->delivery_confirmed_at !== null) {
            return back()->with('error', 'User sudah mengkonfirmasi penerimaan, tidak bisa dibatalkan.');
        }
        
        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:500',
        ]);
        
        // Reset delivery status
        $rental->update([
            'delivered_at' => null,
            'delivered_by' => null,
            'delivery_notes' => 'Dibatalkan: ' . $validated['cancel_reason'],
        ]);
        
        \Log::info('Delivery cancelled by kasir/admin', [
            'rental_id' => $rental->id,
            'cancelled_by' => auth()->id(),
            'reason' => $validated['cancel_reason'],
        ]);
        
        return back()->with('success', 'Status pengantaran berhasil direset. Silakan konfirmasi ulang setelah barang diantar.');
    }
}
