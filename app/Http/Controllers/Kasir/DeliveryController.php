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
        
        // Rental yang sudah dibayar, menunggu diantar
        $pendingDeliveries = Rental::where('status', 'menunggu_pengantaran')
            ->whereNull('delivered_at')
            ->with(['customer', 'items.rentable'])
            ->latest()
            ->paginate(10, ['*'], 'pending_page');
            
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
                ->whereNull('delivered_at')
                ->count(),
            'awaiting_confirmation' => Rental::where('status', 'menunggu_pengantaran')
                ->whereNotNull('delivered_at')
                ->whereNull('delivery_confirmed_at')
                ->count(),
            'delivered_today' => Rental::whereDate('delivered_at', today())->count(),
            'confirmed_today' => Rental::whereDate('delivery_confirmed_at', today())->count(),
        ];
            
        return view('kasir.deliveries.index', compact('pendingDeliveries', 'awaitingConfirmation', 'stats'));
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
        
        return back()->with('success', 'Pengantaran berhasil dikonfirmasi. Menunggu konfirmasi penerimaan dari pelanggan.');
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
