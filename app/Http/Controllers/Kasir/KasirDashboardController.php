<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Payment;
use App\Models\DamageReport;
use App\Models\RentalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KasirDashboardController extends Controller
{
    /**
     * Main kasir dashboard with all transaction overview
     */
    public function index()
    {
        Gate::authorize('access-kasir');

        // Statistics
        $stats = [
            'pending_payment' => Rental::where('status', 'pending')->count(),
            'active_rentals' => Rental::where('status', 'sedang_disewa')->count(),
            'waiting_confirmation' => Rental::where('status', 'menunggu_konfirmasi')->count(),
            'completed_today' => Rental::where('status', 'selesai')
                ->whereDate('returned_at', today())
                ->count(),
            'pending_fine_confirmation' => DamageReport::where('fine_paid', true)
                ->whereNull('kasir_confirmed_at')
                ->count(),
        ];

        // Today's income
        $todayIncome = Payment::whereDate('created_at', today())
            ->where('transaction_status', 'settlement')
            ->sum('amount');

        // Recent transactions
        $recentRentals = Rental::with(['customer', 'items.rentable'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Pending fine confirmations
        $pendingFineConfirmations = DamageReport::with(['rentalItem.rental.customer', 'rentalItem.rentable'])
            ->where('fine_paid', true)
            ->whereNull('kasir_confirmed_at')
            ->orderByDesc('fine_paid_at')
            ->limit(5)
            ->get();

        // Waiting return confirmation
        $waitingReturnConfirmation = Rental::with(['customer', 'items.rentable'])
            ->where('status', 'menunggu_konfirmasi')
            ->orderByDesc('returned_at')
            ->limit(5)
            ->get();

        return view('kasir.dashboard', compact(
            'stats', 'todayIncome', 'recentRentals', 
            'pendingFineConfirmations', 'waitingReturnConfirmation'
        ));
    }

    /**
     * All transactions page
     */
    public function allTransactions(Request $request)
    {
        Gate::authorize('access-kasir');

        $query = Rental::with(['customer', 'items.rentable', 'payments']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search by kode or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $rentals = $query->orderByDesc('created_at')->paginate(15);

        // Statistics
        $stats = [
            'total' => Rental::count(),
            'pending' => Rental::where('status', 'pending')->count(),
            'active' => Rental::where('status', 'sedang_disewa')->count(),
            'waiting' => Rental::where('status', 'menunggu_konfirmasi')->count(),
            'completed' => Rental::where('status', 'selesai')->count(),
            'cancelled' => Rental::where('status', 'cancelled')->count(),
        ];

        return view('kasir.transactions.index', compact('rentals', 'stats'));
    }

    /**
     * Payments management page
     */
    public function payments(Request $request)
    {
        Gate::authorize('access-kasir');

        $query = Payment::with(['rental.customer']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('transaction_status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $payments = $query->orderByDesc('created_at')->paginate(15);

        // Statistics
        $stats = [
            'total_income' => Payment::where('transaction_status', 'settlement')->sum('amount'),
            'today_income' => Payment::where('transaction_status', 'settlement')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'pending_payments' => Payment::where('transaction_status', 'pending')->count(),
            'successful_payments' => Payment::where('transaction_status', 'settlement')->count(),
        ];

        return view('kasir.payments.index', compact('payments', 'stats'));
    }

    /**
     * Fine payments management
     */
    public function finePayments(Request $request)
    {
        Gate::authorize('access-kasir');

        $query = DamageReport::with(['rentalItem.rental.customer', 'rentalItem.rentable', 'reporter']);

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'pending_confirmation') {
                $query->where('fine_paid', true)->whereNull('kasir_confirmed_at');
            } elseif ($request->status === 'confirmed') {
                $query->whereNotNull('kasir_confirmed_at');
            } elseif ($request->status === 'unpaid') {
                $query->where('fine_paid', false)->where('user_confirmed', true);
            }
        }

        $damageReports = $query->whereNotNull('fine_amount')
            ->where('fine_amount', '>', 0)
            ->orderByDesc('created_at')
            ->paginate(15);

        // Statistics
        $stats = [
            'total_fines' => DamageReport::whereNotNull('fine_amount')->sum('fine_amount'),
            'paid_fines' => DamageReport::where('fine_paid', true)->sum('fine_amount'),
            'pending_confirmation' => DamageReport::where('fine_paid', true)
                ->whereNull('kasir_confirmed_at')
                ->count(),
            'unpaid_fines' => DamageReport::where('fine_paid', false)
                ->where('user_confirmed', true)
                ->whereNotNull('fine_amount')
                ->count(),
        ];

        return view('kasir.fines.index', compact('damageReports', 'stats'));
    }

    /**
     * Confirm fine payment
     */
    public function confirmFinePayment(DamageReport $damageReport)
    {
        Gate::authorize('access-kasir');

        if (!$damageReport->fine_paid) {
            return back()->with('error', 'Denda belum dibayar oleh pelanggan.');
        }

        if ($damageReport->kasir_confirmed_at) {
            return back()->with('error', 'Pembayaran denda sudah dikonfirmasi sebelumnya.');
        }

        DB::transaction(function () use ($damageReport) {
            $damageReport->update([
                'confirmed_by_kasir' => auth()->id(),
                'kasir_confirmed_at' => now(),
                'status' => 'kasir_confirmed',
            ]);

            // Update rental fine_paid
            if ($damageReport->rentalItem && $damageReport->rentalItem->rental) {
                $rental = $damageReport->rentalItem->rental;
                $rental->fine_paid = ($rental->fine_paid ?? 0) + $damageReport->fine_amount;
                $rental->save();
            }

            \Log::info('Fine payment confirmed by kasir', [
                'damage_report_id' => $damageReport->id,
                'kasir_id' => auth()->id(),
                'amount' => $damageReport->fine_amount,
            ]);
        });

        return back()->with('success', 'Pembayaran denda berhasil dikonfirmasi.');
    }

    /**
     * Confirm rental return
     */
    public function confirmReturn(Rental $rental)
    {
        Gate::authorize('access-kasir');

        if ($rental->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Rental tidak dalam status menunggu konfirmasi.');
        }

        DB::transaction(function () use ($rental) {
            $rental->load('items.rentable');
            
            $hasDamage = false;
            
            foreach ($rental->items as $item) {
                // Check if item has damage report that needs processing
                if ($item->condition === 'rusak' && $item->damageReport) {
                    $hasDamage = true;
                    continue; // Don't restore stock for damaged items yet
                }
                
                // Restore stock for good condition items
                if ($item->condition === 'baik' && $item->rentable) {
                    if ($item->rentable instanceof \App\Models\UnitPS) {
                        $item->rentable->stock += $item->quantity;
                        $item->rentable->status = 'available';
                    } else {
                        $item->rentable->stok += $item->quantity;
                    }
                    $item->rentable->save();
                }
            }

            // Update rental status
            $rental->update([
                'status' => $hasDamage ? 'menunggu_konfirmasi' : 'selesai',
                'handled_by' => auth()->id(),
            ]);

            // If no damage, mark as complete
            if (!$hasDamage) {
                $rental->update(['status' => 'selesai']);
            }

            \Log::info('Return confirmed by kasir', [
                'rental_id' => $rental->id,
                'kasir_id' => auth()->id(),
                'has_damage' => $hasDamage,
            ]);
        });

        return back()->with('success', 'Pengembalian berhasil dikonfirmasi.');
    }

    /**
     * Daily report
     */
    public function dailyReport(Request $request)
    {
        Gate::authorize('access-kasir');

        $date = $request->filled('date') ? Carbon::parse($request->date) : today();

        // Rentals created today
        $rentalsCreated = Rental::with(['customer'])
            ->whereDate('created_at', $date)
            ->get();

        // Rentals completed today
        $rentalsCompleted = Rental::with(['customer'])
            ->whereDate('returned_at', $date)
            ->where('status', 'selesai')
            ->get();

        // Payments received today
        $payments = Payment::with(['rental.customer'])
            ->whereDate('created_at', $date)
            ->where('transaction_status', 'settlement')
            ->get();

        // Fines confirmed today
        $finesConfirmed = DamageReport::with(['rentalItem.rental.customer'])
            ->whereDate('kasir_confirmed_at', $date)
            ->get();

        // Summary
        $summary = [
            'total_rentals_created' => $rentalsCreated->count(),
            'total_rentals_completed' => $rentalsCompleted->count(),
            'total_rental_income' => $payments->sum('amount'),
            'total_fine_income' => $finesConfirmed->sum('fine_amount'),
            'total_income' => $payments->sum('amount') + $finesConfirmed->sum('fine_amount'),
        ];

        return view('kasir.reports.daily', compact(
            'date', 'rentalsCreated', 'rentalsCompleted', 
            'payments', 'finesConfirmed', 'summary'
        ));
    }
}
