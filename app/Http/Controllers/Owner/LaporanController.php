<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Payment;
use App\Models\DamageReport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RentalExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('access-pemilik');
        $query = Rental::with(['customer', 'items', 'payments'])->orderByDesc('start_at');
        
        if ($request->filled('dari')) {
            $query->whereDate('start_at', '>=', $request->input('dari'));
        }
        if ($request->filled('sampai')) {
            $query->whereDate('start_at', '<=', $request->input('sampai'));
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        $rentals = $query->get();
        return view('owner.laporan', compact('rentals'));
    }

    public function pendapatan(Request $request)
    {
        Gate::authorize('access-pemilik');
        
        // Get date range from request or use defaults (last 7 days)
        $dari = $request->input('dari', now()->subDays(6)->format('Y-m-d'));
        $sampai = $request->input('sampai', now()->format('Y-m-d'));
        
        // Validate and parse dates
        $start = \Carbon\Carbon::parse($dari)->startOfDay();
        $end = \Carbon\Carbon::parse($sampai)->endOfDay();
        
        // Chart: Revenue for selected date range (Rental payments)
        $paymentData = Payment::whereBetween('paid_at', [$start, $end])
            ->where('transaction_status', 'settlement')
            ->selectRaw('DATE(paid_at) as payment_date, SUM(amount) as total_amount')
            ->groupBy('payment_date')
            ->pluck('total_amount', 'payment_date');

        // Chart: Fine payments for selected date range (from completed rentals with fines)
        $fineData = Rental::whereBetween('updated_at', [$start, $end])
            ->where('status', 'selesai')
            ->where('fine', '>', 0)
            ->selectRaw('DATE(updated_at) as payment_date, SUM(fine) as total_amount')
            ->groupBy('payment_date')
            ->pluck('total_amount', 'payment_date');

        $revLabels = [];
        $revData = [];
        $fineRevData = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $key = $d->format('Y-m-d');
            $revLabels[] = $d->format('d M');
            $revData[] = (int) ($paymentData[$key] ?? 0);
            $fineRevData[] = (int) ($fineData[$key] ?? 0);
        }

        // Stats Summary
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        // Rental payments (confirmed/settlement only)
        $revenueTotal = Payment::where('transaction_status', 'settlement')->sum('amount');
        $revenueToday = Payment::where('transaction_status', 'settlement')
            ->where('paid_at', '>=', $today)->sum('amount');
        $revenueMonth = Payment::where('transaction_status', 'settlement')
            ->where('paid_at', '>=', $monthStart)->sum('amount');
        
        // Fine payments (from completed rentals with fines)
        $fineTotal = Rental::where('status', 'selesai')->where('fine', '>', 0)->sum('fine');
        $fineToday = Rental::where('status', 'selesai')->where('fine', '>', 0)
            ->whereDate('updated_at', today())->sum('fine');
        $fineMonth = Rental::where('status', 'selesai')->where('fine', '>', 0)
            ->where('updated_at', '>=', $monthStart)->sum('fine');
        
        // Revenue for selected period
        $revenueFiltered = array_sum($revData);
        $fineFiltered = array_sum($fineRevData);

        $revenueStats = [
            'total' => $revenueTotal,
            'today' => $revenueToday,
            'month' => $revenueMonth,
            'filtered' => $revenueFiltered,
            'fine_total' => $fineTotal,
            'fine_today' => $fineToday,
            'fine_month' => $fineMonth,
            'fine_filtered' => $fineFiltered,
            'grand_total' => $revenueTotal + $fineTotal,
            'grand_today' => $revenueToday + $fineToday,
            'grand_month' => $revenueMonth + $fineMonth,
            'grand_filtered' => $revenueFiltered + $fineFiltered,
        ];

        // Detailed Payments List (filtered by date range)
        $revenueList = Payment::with(['rental.customer'])
            ->where('transaction_status', 'settlement')
            ->whereBetween('paid_at', [$start, $end])
            ->orderByDesc('paid_at')
            ->paginate(20);

        // Fine payments list (from completed rentals with fines)
        $fineList = Rental::with(['customer', 'items.rentable'])
            ->where('status', 'selesai')
            ->where('fine', '>', 0)
            ->whereBetween('updated_at', [$start, $end])
            ->orderByDesc('updated_at')
            ->get();

        // Calculate period label
        $periodDays = round($start->diffInDays($end)) + 1;
        $periodLabel = $periodDays . ' Hari';
        if ($periodDays == 1) {
            $periodLabel = $start->format('d M Y');
        } elseif ($periodDays == 7) {
            $periodLabel = '7 Hari Terakhir';
        } elseif ($periodDays == 30) {
            $periodLabel = '30 Hari Terakhir';
        }

        return view('owner.laporan_pendapatan', compact(
            'revLabels', 
            'revData',
            'fineRevData',
            'revenueStats', 
            'revenueList',
            'fineList',
            'dari',
            'sampai',
            'periodLabel'
        ));
    }

    public function export(Request $request)
    {
        Gate::authorize('access-pemilik');
        $format = $request->get('format', 'xlsx');
        $dari = $request->input('dari');
        $sampai = $request->input('sampai');
        return Excel::download(new \App\Exports\RentalExport($dari, $sampai), 'laporan_transaksi.' . $format);
    }

    public function damageReports(Request $request)
    {
        Gate::authorize('access-pemilik');

        // Get all damaged items from rentals
        $damagedItems = \App\Models\RentalItem::with(['rental.customer', 'rentable'])
            ->where('condition', 'rusak')
            ->orderByDesc('updated_at')
            ->get();

        // Statistics
        $stats = [
            'total_damaged' => $damagedItems->count(),
            'unitps_damaged' => $damagedItems->where('rentable_type', \App\Models\UnitPS::class)->count(),
            'games_damaged' => $damagedItems->where('rentable_type', \App\Models\Game::class)->count(),
            'accessories_damaged' => $damagedItems->where('rentable_type', \App\Models\Accessory::class)->count(),
            'total_fine' => Rental::where('status', 'selesai')->where('fine', '>', 0)->sum('fine'),
            'pending_fine' => Rental::whereIn('status', ['menunggu_konfirmasi', 'sedang_disewa'])->where('fine', '>', 0)->sum('fine'),
        ];

        // Rentals with fines
        $rentalsWithFines = Rental::with(['customer', 'items.rentable'])
            ->where('fine', '>', 0)
            ->orderByDesc('updated_at')
            ->get();

        return view('owner.damage_reports', compact('damagedItems', 'stats', 'rentalsWithFines'));
    }
}
