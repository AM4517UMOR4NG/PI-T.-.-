<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DamageReport;
use App\Models\RentalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class DamageReportController extends Controller
{
    /**
     * Display a listing of damage reports
     */
    public function index(Request $request)
    {
        Gate::authorize('access-admin');

        $query = DamageReport::with(['rentalItem.rental.customer', 'rentalItem.rentable', 'reporter', 'reviewer']);

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

        $reports = $query->orderByDesc('created_at')->paginate(15);

        // Statistics
        $stats = [
            'total' => DamageReport::count(),
            'pending' => DamageReport::where('status', 'pending')->count(),
            'reviewed' => DamageReport::where('status', 'reviewed')->count(),
            'resolved' => DamageReport::where('status', 'resolved')->count(),
        ];

        return view('admin.damage-reports.index', compact('reports', 'stats'));
    }

    /**
     * Display the specified damage report
     */
    public function show(DamageReport $damageReport)
    {
        Gate::authorize('access-admin');

        $damageReport->load(['rentalItem.rental.customer', 'rentalItem.rentable', 'reporter', 'reviewer']);

        return view('admin.damage-reports.show', compact('damageReport'));
    }

    /**
     * Submit review for damage report
     */
    public function review(Request $request, DamageReport $damageReport)
    {
        Gate::authorize('access-admin');

        $validated = $request->validate([
            'admin_feedback' => 'required|string|max:1000',
            'fine_amount' => 'required|numeric|min:0',
            'status' => 'required|in:reviewed,resolved',
        ]);

        DB::transaction(function () use ($damageReport, $validated) {
            // Update damage report
            $damageReport->update([
                'admin_feedback' => $validated['admin_feedback'],
                'fine_amount' => $validated['fine_amount'],
                'status' => $validated['status'],
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            // Update rental item fine
            if ($damageReport->rentalItem) {
                $damageReport->rentalItem->update([
                    'fine' => $validated['fine_amount'],
                    'fine_description' => $damageReport->rentalItem->fine_description . ' | Admin: ' . $validated['admin_feedback'],
                ]);

                // Update rental total if fine is applied
                $rental = $damageReport->rentalItem->rental;
                if ($rental) {
                    $totalFine = $rental->items()->sum('fine');
                    $rental->update([
                        'fine' => $totalFine,
                    ]);
                }
            }

            \Log::info('Damage report reviewed', [
                'report_id' => $damageReport->id,
                'reviewed_by' => auth()->id(),
                'fine_amount' => $validated['fine_amount'],
            ]);
        });

        // Send email if no fine imposed
        if ($validated['fine_amount'] == 0 && $damageReport->reporter && $damageReport->reporter->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($damageReport->reporter->email)->send(new \App\Mail\DamageReportResult($damageReport));
                \Log::info('Damage report result email sent to: ' . $damageReport->reporter->email);
            } catch (\Exception $e) {
                \Log::error('Failed to send damage report result email: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.damage-reports.index')
            ->with('success', 'Laporan kerusakan berhasil direview. Denda sebesar Rp ' . number_format($validated['fine_amount'], 0, ',', '.') . ' telah ditetapkan.');
    }

    /**
     * Mark damage report as resolved (close case)
     */
    public function resolve(DamageReport $damageReport)
    {
        Gate::authorize('access-admin');

        // Can only close if kasir has confirmed
        if (!$damageReport->kasir_confirmed_at) {
            return redirect()->back()->with('error', 'Kasir belum mengkonfirmasi pembayaran denda.');
        }

        $damageReport->update([
            'status' => 'resolved',
            'closed_by' => auth()->id(),
            'closed_at' => now(),
        ]);

        // Restore stock for damaged item
        if ($damageReport->rentalItem && $damageReport->rentalItem->rentable) {
            $rentable = $damageReport->rentalItem->rentable;
            
            // Mark as maintenance or reduce stock permanently based on severity
            // For now, we'll just mark the case as closed
            // Stock restoration should be handled separately by admin
        }

        \Log::info('Damage report closed by admin', [
            'report_id' => $damageReport->id,
            'admin_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Case kerusakan berhasil ditutup.');
    }

    /**
     * Close case without payment (for cases with no fine)
     */
    public function closeWithoutPayment(DamageReport $damageReport)
    {
        Gate::authorize('access-admin');

        // Only for cases with no fine or zero fine
        if ($damageReport->fine_amount > 0 && !$damageReport->fine_paid) {
            return redirect()->back()->with('error', 'Denda belum dibayar. Tidak dapat menutup case.');
        }

        $damageReport->update([
            'status' => 'resolved',
            'closed_by' => auth()->id(),
            'closed_at' => now(),
        ]);

        \Log::info('Damage report closed without payment by admin', [
            'report_id' => $damageReport->id,
            'admin_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Case kerusakan berhasil ditutup.');
    }
}
