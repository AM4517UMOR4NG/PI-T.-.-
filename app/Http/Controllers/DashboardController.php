<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\UnitPS;
use App\Models\Game;
use App\Models\Accessory;
use App\Models\RentalItem;
use App\Models\Rental;
use App\Models\Payment;
use App\Models\DamageReport;
use App\Models\User;

class DashboardController extends Controller
{
    public function admin()
    {
        Gate::authorize('access-admin');
        
        // Gunakan eager loading dan aggregasi untuk meningkatkan performa
        $unitPSData = UnitPS::selectRaw('*, COALESCE(stock, 0) as total_stok')->get();
        $gameData = Game::selectRaw('*, COALESCE(stok, 0) as total_stok')->get();
        $accessoryData = Accessory::selectRaw('*, COALESCE(stok, 0) as total_stok')->get();
        
        $unitAvailable = $unitPSData->sum('total_stok');
        $unitRented = RentalItem::whereHas('rental', function ($q) { $q->whereIn('status', ['sedang_disewa', 'menunggu_konfirmasi']); })
            ->where('rentable_type', UnitPS::class)
            ->sum('quantity');
        
        // Calculate damaged units from multiple sources:
        // 1. Items with kondisi='rusak' in the model
        // 2. RentalItems marked as 'rusak' (unique items)
        
        $unitDamagedFromModel = UnitPS::where('kondisi', 'rusak')->orWhere('status', 'maintenance')->count();
        $unitDamagedFromRentals = RentalItem::where('rentable_type', UnitPS::class)
            ->where('condition', 'rusak')
            ->count();
        $unitDamaged = $unitDamagedFromModel + $unitDamagedFromRentals;
        $unitTotal = $unitAvailable + $unitRented + $unitDamaged;

        $gameAvailable = $gameData->sum('total_stok');
        $gameRented = RentalItem::whereHas('rental', function ($q) { $q->whereIn('status', ['sedang_disewa', 'menunggu_konfirmasi']); })
            ->where('rentable_type', Game::class)
            ->sum('quantity');
        $gameDamagedFromModel = Game::where('kondisi', 'rusak')->count();
        $gameDamagedFromRentals = RentalItem::where('rentable_type', Game::class)
            ->where('condition', 'rusak')
            ->count();
        $gameDamaged = $gameDamagedFromModel + $gameDamagedFromRentals;
        $gameTotal = $gameAvailable + $gameRented;

        $accAvailable = $accessoryData->sum('total_stok');
        $accRented = RentalItem::whereHas('rental', function ($q) { $q->whereIn('status', ['sedang_disewa', 'menunggu_konfirmasi']); })
            ->where('rentable_type', Accessory::class)
            ->sum('quantity');
        $accDamagedFromModel = Accessory::where('kondisi', 'rusak')->count();
        $accDamagedFromRentals = RentalItem::where('rentable_type', Accessory::class)
            ->where('condition', 'rusak')
            ->count();
        $accDamaged = $accDamagedFromModel + $accDamagedFromRentals;
        $accTotal = $accAvailable + $accRented;

        // Ambil rental yang aktif untuk menghitung jumlah sewa per item
        $activeRentalItems = RentalItem::whereHas('rental', function ($q) { $q->whereIn('status', ['sedang_disewa', 'menunggu_konfirmasi']); })
            ->whereIn('rentable_type', [UnitPS::class, Game::class, Accessory::class])
            ->selectRaw('rentable_type, rentable_id, SUM(quantity) as total_rented')
            ->groupBy('rentable_type', 'rentable_id')
            ->get()
            ->keyBy(function($item) {
                return $item->rentable_type . '_' . $item->rentable_id;
            });

        // Optimasi data detail UnitPS
        $unitps = $unitPSData->map(function($unit) use ($activeRentalItems) {
            $key = UnitPS::class . '_' . $unit->id;
            $rentedCount = $activeRentalItems->has($key) ? $activeRentalItems->get($key)->total_rented : 0;
            
            return [
                'nama' => $unit->name,
                'model' => $unit->model,
                'merek' => $unit->brand,
                'stok' => $unit->total_stok,
                'kondisi_baik' => $unit->total_stok,
                'kondisi_buruk' => $unit->status === 'maintenance' ? 1 : 0, // Simplified logic
                'disewa' => $rentedCount,
                'tersedia' => $unit->total_stok - $rentedCount,
                'nomor_seri' => $unit->serial_number ?? '-'
            ];
        });
        
        // Optimasi data detail Games
        $games = $gameData->map(function($game) use ($activeRentalItems) {
            $key = Game::class . '_' . $game->id;
            $rentedCount = $activeRentalItems->has($key) ? $activeRentalItems->get($key)->total_rented : 0;
            
            return [
                'judul' => $game->judul,
                'platform' => $game->platform,
                'genre' => $game->genre,
                'stok' => $game->total_stok,
                'kondisi_baik' => $game->total_stok, 
                'kondisi_buruk' => 0, 
                'disewa' => $rentedCount,
                'tersedia' => $game->total_stok - $rentedCount
            ];
        });
        
        // Optimasi data detail Aksesoris
        $accessories = $accessoryData->map(function($acc) use ($activeRentalItems) {
            $key = Accessory::class . '_' . $acc->id;
            $rentedCount = $activeRentalItems->has($key) ? $activeRentalItems->get($key)->total_rented : 0;
            
            return [
                'nama' => $acc->nama,
                'jenis' => $acc->jenis,
                'stok' => $acc->total_stok,
                'kondisi_baik' => $acc->total_stok, 
                'kondisi_buruk' => 0, 
                'disewa' => $rentedCount,
                'tersedia' => $acc->total_stok - $rentedCount
            ];
        });
        
        $stats = [
            ['name' => 'Unit PS', 'total' => $unitTotal, 'available' => $unitAvailable, 'rented' => $unitRented, 'damaged' => $unitDamaged],
            ['name' => 'Game', 'total' => $gameTotal, 'available' => $gameAvailable, 'rented' => $gameRented, 'damaged' => $gameDamaged],
            ['name' => 'Aksesoris', 'total' => $accTotal, 'available' => $accAvailable, 'rented' => $accRented, 'damaged' => $accDamaged],
        ];

        // New: User Statistics
        $userStats = [
            'pelanggan' => User::where('role', 'pelanggan')->count(),
            'kasir' => User::where('role', 'kasir')->count(),
            'pemilik' => User::where('role', 'pemilik')->count(),
            'admin' => User::where('role', 'admin')->count(),
        ];

        // New: Recent Transactions
        $recentRentals = Rental::with(['customer', 'items.rentable'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Damaged Items - Get rental items with condition 'rusak' that are pending confirmation
        $damagedItems = RentalItem::with(['rental.customer', 'rentable', 'damageReport'])
            ->where('condition', 'rusak')
            ->whereHas('rental', function($q) {
                $q->whereIn('status', ['menunggu_konfirmasi', 'selesai']);
            })
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        // Damage Statistics by Category
        $damageStats = [
            'total' => DamageReport::count(),
            'pending' => DamageReport::where('status', 'pending')->count(),
            'reviewed' => DamageReport::where('status', 'reviewed')->count(),
            'resolved' => DamageReport::where('status', 'resolved')->count(),
        ];

        // Damaged items count by type
        $damagedByType = [
            'unitps' => RentalItem::where('condition', 'rusak')
                ->where('rentable_type', UnitPS::class)
                ->count(),
            'games' => RentalItem::where('condition', 'rusak')
                ->where('rentable_type', Game::class)
                ->count(),
            'accessories' => RentalItem::where('condition', 'rusak')
                ->where('rentable_type', Accessory::class)
                ->count(),
        ];

        // Pending damage reports for review
        $pendingDamageReports = DamageReport::with(['rentalItem.rental.customer', 'rentalItem.rentable', 'reporter'])
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('dashboards.admin', compact(
            'stats', 'unitps', 'games', 'accessories', 'userStats', 
            'recentRentals', 'damagedItems', 'damageStats', 'damagedByType', 'pendingDamageReports'
        ));
    }

    public function kasir()
    {
        Gate::authorize('access-kasir');
        
        // Redirect to new kasir dashboard
        return redirect()->route('kasir.dashboard');
    }

    public function pemilik()
    {
        Gate::authorize('access-pemilik');
        
        // KPI Cards Data
        $availableUnits = UnitPS::count();
        $availableGames = Game::count();
        $availableAccessories = Accessory::count();
        $todaysTransactions = Rental::whereDate('created_at', now()->toDateString())->count();

        // Revenue 7 Days (Simple Calculation for KPI Card)
        $start = now()->copy()->subDays(6)->startOfDay();
        $end = now()->endOfDay();
        $revTotal7 = Payment::whereBetween('paid_at', [$start, $end])->sum('amount');

        // Recent Transactions (Limit 5 for Dashboard)
        $recentTransactions = Rental::with(['customer', 'items.rentable'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboards.pemilik', compact(
            'availableUnits', 
            'availableGames',
            'availableAccessories',
            'todaysTransactions', 
            'revTotal7', 
            'recentTransactions'
        ));
    }

    public function pelanggan()
    {
        Gate::authorize('access-pelanggan');
        
        // Get latest available items with stock > 0 for display on landing page
        $unitps = UnitPS::where('stock', '>', 0)
            ->orderByDesc('id')
            ->limit(8)
            ->get();
            
        $games = Game::where('stok', '>', 0)
            ->orderByDesc('id')
            ->limit(8)
            ->get();
            
        $accessories = Accessory::where('stok', '>', 0)
            ->orderByDesc('id')
            ->limit(8)
            ->get();
            
        return view('dashboards.pelanggan', compact('unitps', 'games', 'accessories'));
    }
    
    public function galleryShowcase()
    {
        $units = UnitPS::where('stock', '>', 0)->inRandomOrder()->limit(12)->get();
        $games = Game::where('stok', '>', 0)->inRandomOrder()->limit(12)->get();
        $accessories = Accessory::where('stok', '>', 0)->inRandomOrder()->limit(12)->get();
        
        return view('pages.showcase.gallery', compact('units', 'games', 'accessories'));
    }
    
    // Legacy routes redirect to new gallery
    public function unitpsLanding()
    {
        return redirect()->route('showcase.gallery');
    }
    
    public function gameLanding()
    {
        return redirect()->route('showcase.gallery');
    }
    
    public function accessoryLanding()
    {
        return redirect()->route('showcase.gallery');
    }

    public function adminReport()
    {
        Gate::authorize('access-admin');
        // Ringkasan pendapatan
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();
        $revenueTotal = Payment::sum('amount');
        $revenueToday = Payment::where('paid_at', '>=', $today)->sum('amount');
        $revenueMonth = Payment::where('paid_at', '>=', $monthStart)->sum('amount');

        // Ringkasan transaksi
        $rentalsTotal = Rental::count();
        $rentalsActive = Rental::whereIn('status', ['sedang_disewa', 'menunggu_konfirmasi'])->count();
        $rentalsReturned = Rental::where('status', 'selesai')->count();

        // Pembayaran terakhir - gunakan eager loading terbatas
        $latestPayments = Payment::with(['rental' => function($query) {
                $query->with('customer');
            }])
            ->orderByDesc('paid_at')
            ->limit(10)
            ->get();

        return view('admin.laporan.index', compact(
            'revenueTotal', 'revenueToday', 'revenueMonth',
            'rentalsTotal', 'rentalsActive', 'rentalsReturned',
            'latestPayments'
        ));
    }
}


