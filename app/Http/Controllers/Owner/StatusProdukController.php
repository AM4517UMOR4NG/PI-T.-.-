<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\UnitPS;
use App\Models\Game;
use App\Models\Accessory;
use App\Models\RentalItem;
use App\Models\Rental;
use Illuminate\Support\Facades\DB;

class StatusProdukController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('access-pemilik');
        
        // Get active rental IDs
        $activeRentalIds = Rental::whereIn('status', ['sedang_disewa', 'menunggu_konfirmasi'])->pluck('id');
        
        // Get rented quantities grouped by item
        $rentedUnits = RentalItem::where('rentable_type', UnitPS::class)
            ->whereIn('rental_id', $activeRentalIds)
            ->select('rentable_id', DB::raw('SUM(quantity) as total_rented'))
            ->groupBy('rentable_id')
            ->pluck('total_rented', 'rentable_id')
            ->toArray();
            
        $rentedGames = RentalItem::where('rentable_type', Game::class)
            ->whereIn('rental_id', $activeRentalIds)
            ->select('rentable_id', DB::raw('SUM(quantity) as total_rented'))
            ->groupBy('rentable_id')
            ->pluck('total_rented', 'rentable_id')
            ->toArray();
            
        $rentedAccessories = RentalItem::where('rentable_type', Accessory::class)
            ->whereIn('rental_id', $activeRentalIds)
            ->select('rentable_id', DB::raw('SUM(quantity) as total_rented'))
            ->groupBy('rentable_id')
            ->pluck('total_rented', 'rentable_id')
            ->toArray();
        
        // Search functionality
        $search = $request->input('search');
        
        // Get items with search filter
        $unitpsQuery = UnitPS::query();
        $gamesQuery = Game::query();
        $accessoriesQuery = Accessory::query();
        
        if ($search) {
            $unitpsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
            
            $gamesQuery->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('platform', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%");
            });
            
            $accessoriesQuery->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('jenis', 'like', "%{$search}%");
            });
        }
        
        $unitps = $unitpsQuery->orderBy('name')->get();
        $games = $gamesQuery->orderBy('judul')->get();
        $accessories = $accessoriesQuery->orderBy('nama')->get();
        
        // Get damaged items count
        $damagedUnits = RentalItem::where('rentable_type', UnitPS::class)
            ->where('condition', 'rusak')
            ->distinct('rentable_id')
            ->count('rentable_id');
        $damagedGames = RentalItem::where('rentable_type', Game::class)
            ->where('condition', 'rusak')
            ->distinct('rentable_id')
            ->count('rentable_id');
        $damagedAccessories = RentalItem::where('rentable_type', Accessory::class)
            ->where('condition', 'rusak')
            ->distinct('rentable_id')
            ->count('rentable_id');
        
        // Calculate statistics
        $unitTotal = $unitps->count();
        $gameTotal = $games->count();
        $accTotal = $accessories->count();
        
        $stats = [
            'units' => [
                'total' => $unitTotal,
                'available' => $unitTotal - array_sum($rentedUnits) - $damagedUnits,
                'rented' => array_sum($rentedUnits),
                'damaged' => $damagedUnits,
                'health' => $unitTotal > 0 ? round((($unitTotal - $damagedUnits) / $unitTotal) * 100) : 100,
            ],
            'games' => [
                'total' => $gameTotal,
                'available' => $gameTotal - array_sum($rentedGames) - $damagedGames,
                'rented' => array_sum($rentedGames),
                'damaged' => $damagedGames,
                'health' => $gameTotal > 0 ? round((($gameTotal - $damagedGames) / $gameTotal) * 100) : 100,
            ],
            'accessories' => [
                'total' => $accTotal,
                'available' => $accTotal - array_sum($rentedAccessories) - $damagedAccessories,
                'rented' => array_sum($rentedAccessories),
                'damaged' => $damagedAccessories,
                'health' => $accTotal > 0 ? round((($accTotal - $damagedAccessories) / $accTotal) * 100) : 100,
            ],
        ];
        
        // Overall health
        $totalItems = $unitTotal + $gameTotal + $accTotal;
        $totalDamaged = $damagedUnits + $damagedGames + $damagedAccessories;
        $overallHealth = $totalItems > 0 ? round((($totalItems - $totalDamaged) / $totalItems) * 100) : 100;
        
        return view('owner.status_produk', compact(
            'unitps', 'games', 'accessories', 
            'rentedUnits', 'rentedGames', 'rentedAccessories',
            'stats', 'search', 'overallHealth', 'totalItems', 'totalDamaged'
        ));
    }
}
