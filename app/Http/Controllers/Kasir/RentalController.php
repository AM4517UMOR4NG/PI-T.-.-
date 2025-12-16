<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\UnitPS;
use App\Models\Game;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Carbon;

class RentalController extends Controller
{
    public function index()
    {
        Gate::authorize('access-kasir');
        $rentals = Rental::with(['customer', 'items.rentable'])->latest()->paginate(10);
        return view('kasir.rentals.index', compact('rentals'));
    }

    public function create()
    {
        Gate::authorize('access-kasir');
        $units = UnitPS::where('stock', '>', 0)->orderBy('name')->get();
        $games = Game::where('stok', '>', 0)->orderBy('judul')->get();
        $accessories = Accessory::where('stok', '>', 0)->orderBy('nama')->get();
        $customers = \App\Models\User::where('role', 'pelanggan')->orderBy('name')->get();
        return view('kasir.rentals.create', compact('units', 'games', 'accessories', 'customers'));
    }

    public function store(Request $request)
    {
        Gate::authorize('access-kasir');
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'start_at' => ['required', 'date'],
            'due_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.type' => ['required', 'in:unit_ps,game,accessory'],
            'items.*.id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'paid' => ['nullable', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($validated) {
            $rental = Rental::create([
                'user_id' => $validated['user_id'],
                'handled_by' => Auth::id(),
                'start_at' => $validated['start_at'],
                'due_at' => $validated['due_at'] ?? null,
                'status' => 'sedang_disewa',
                'subtotal' => 0,
                'discount' => $validated['discount'] ?? 0,
                'total' => 0,
                'paid' => $validated['paid'] ?? 0,
            ]);

            $subtotal = 0;

            foreach ($validated['items'] as $item) {
                [$rentableType, $model] = match ($item['type']) {
                    'unit_ps' => [UnitPS::class, new UnitPS()],
                    'game' => [Game::class, new Game()],
                    'accessory' => [Accessory::class, new Accessory()],
                };

                $rentable = $model->newQuery()->lockForUpdate()->findOrFail($item['id']);

                if ($item['type'] === 'unit_ps') {
                    if ($rentable->stock < $item['quantity']) {
                        abort(422, 'Stok Unit PS tidak cukup');
                    }
                    $rentable->stock -= $item['quantity'];
                    // if ($rentable->stock === 0) {
                    //     $rentable->status = 'rented';
                    // }
                    $rentable->save();
                } else {
                    if ($rentable->stok < $item['quantity']) {
                        abort(422, 'Stok tidak cukup');
                    }
                    $rentable->decrement('stok', $item['quantity']);
                }

                $lineTotal = $item['quantity'] * $item['price'];
                $subtotal += $lineTotal;

                RentalItem::create([
                    'rental_id' => $rental->id,
                    'rentable_type' => $rentableType,
                    'rentable_id' => $rentable->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $lineTotal,
                ]);
            }

            $rental->subtotal = $subtotal;
            $rental->total = max(0, $subtotal - ($rental->discount ?? 0));
            $rental->save();

            return redirect()->route('kasir.rentals.show', $rental)->with('status', 'Rental dibuat');
        });
    }

    public function show(Rental $rental)
    {
        Gate::authorize('access-kasir');
        $rental->load(['customer', 'items', 'payments']);
        return view('kasir.rentals.show', compact('rental'));
    }

    public function returnForm(Rental $rental)
    {
        Gate::authorize('access-kasir');
        if (!in_array($rental->status, ['sedang_disewa', 'menunggu_konfirmasi'])) {
            return back()->with('status', 'Rental tidak dalam status aktif');
        }
        $rental->load(['customer', 'items.rentable']);
        return view('kasir.rentals.return', compact('rental'));
    }

    public function processReturn(Request $request, Rental $rental)
    {
        Gate::authorize('access-kasir');
        if (!in_array($rental->status, ['sedang_disewa', 'menunggu_konfirmasi'])) {
            return back()->with('status', 'Rental tidak dalam status aktif');
        }

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.condition' => 'required|in:baik,rusak',
            'items.*.fine' => 'nullable|numeric|min:0',
            'items.*.fine_description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($rental, $validated) {
            $totalFine = 0;
            $rental->load('items');

            foreach ($rental->items as $item) {
                $data = $validated['items'][$item->id] ?? null;
                if (!$data) continue;

                // Update Rental Item
                $item->condition = $data['condition'];
                $item->fine = $data['fine'] ?? 0;
                $item->fine_description = $data['fine_description'] ?? null;
                $item->save();

                $totalFine += $item->fine;

                // Update Stock / Status
                if ($item->rentable_type === UnitPS::class) {
                    $unit = UnitPS::lockForUpdate()->find($item->rentable_id);
                    if ($unit) {
                        if ($item->condition === 'baik') {
                            $unit->stock += $item->quantity;
                            $unit->status = 'available'; // Ensure status is available
                        } else {
                            // Rusak
                            $unit->status = 'maintenance';
                            // Stock stays as is (0 if rented), effectively unavailable
                        }
                        $unit->save();
                    }
                } elseif ($item->rentable_type === Game::class) {
                    if ($item->condition === 'baik') {
                        Game::where('id', $item->rentable_id)->lockForUpdate()->increment('stok', $item->quantity);
                    }
                    // If rusak, stock is lost (not incremented)
                } elseif ($item->rentable_type === Accessory::class) {
                    if ($item->condition === 'baik') {
                        Accessory::where('id', $item->rentable_id)->lockForUpdate()->increment('stok', $item->quantity);
                    }
                    // If rusak, stock is lost
                }
            }

            $rental->fine = $totalFine;
            $rental->total += $totalFine;
            $rental->returned_at = now();
            $rental->status = 'selesai';
            $rental->save();
        });

        return redirect()->route('kasir.rentals.show', $rental)->with('status', 'Pengembalian berhasil diproses. Total Denda: Rp ' . number_format($rental->fine ?? 0, 0, ',', '.'));
    }

    /**
     * Kasir mengkonfirmasi pengembalian dari user
     */
    public function confirmReturn(Request $request, Rental $rental)
    {
        Gate::authorize('access-kasir');
        
        // Hanya bisa konfirmasi jika status menunggu_konfirmasi
        if ($rental->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Penyewaan ini tidak dalam status menunggu konfirmasi.');
        }

        $itemsData = $request->input('items', []);
        $totalDamageFine = 0;
        $lateFeeData = null;

        DB::transaction(function () use ($rental, $itemsData, &$totalDamageFine, &$lateFeeData) {
            // Calculate late fee first
            $lateFeeData = \App\Services\LateFeeService::calculateLateFee($rental);
            
            // Restore stock untuk semua item dan update kondisi
            $rental->load('items');
            foreach ($rental->items as $item) {
                // Update item condition and fine from kasir verification
                if (isset($itemsData[$item->id])) {
                    $itemData = $itemsData[$item->id];
                    $condition = $itemData['condition'] ?? 'baik';
                    $fine = (int) ($itemData['fine'] ?? 0);
                    $fineDescription = $itemData['fine_description'] ?? '';
                    
                    $item->update([
                        'condition' => $condition,
                        'fine' => $fine,
                        'fine_description' => $fineDescription,
                    ]);
                    
                    $totalDamageFine += $fine;
                }
                
                if ($item->rentable) {
                    // Check if it's UnitPS (uses 'stock') or other models (use 'stok')
                    $isUnitPS = $item->rentable instanceof \App\Models\UnitPS;
                    
                    if ($isUnitPS) {
                        $item->rentable->stock += $item->quantity;
                    } else {
                        $item->rentable->stok += $item->quantity;
                    }
                    
                    $item->rentable->save();
                    
                    \Log::info('Stock restored by cashier confirmation', [
                        'item_type' => get_class($item->rentable),
                        'item_id' => $item->rentable->id,
                        'quantity_restored' => $item->quantity,
                    ]);
                }
            }

            // Calculate total fine (damage + late fee)
            $lateFee = $lateFeeData['late_fee'] ?? 0;
            $totalFine = $totalDamageFine + $lateFee;

            // Update rental status menjadi selesai dan tambahkan denda
            $rental->update([
                'status' => 'selesai',
                'fine' => $totalFine,
                'late_fee' => $lateFee,
                'late_hours' => $lateFeeData['hours_late'] ?? 0,
                'late_fee_description' => $lateFeeData['description'] ?? null,
                'handled_by' => auth()->id(),
            ]);
        });

        $message = 'Pengembalian berhasil dikonfirmasi. Stok telah dikembalikan.';
        
        if ($lateFeeData && $lateFeeData['is_late']) {
            $message .= ' Denda keterlambatan (' . $lateFeeData['hours_late'] . ' jam): Rp ' . number_format($lateFeeData['late_fee'], 0, ',', '.');
        }
        
        if ($totalDamageFine > 0) {
            $message .= ' Denda kerusakan: Rp ' . number_format($totalDamageFine, 0, ',', '.');
        }

        return redirect()->route('kasir.rentals.show', $rental)
            ->with('status', $message);
    }
}
