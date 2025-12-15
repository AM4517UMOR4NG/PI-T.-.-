<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\UnitPS;
use App\Models\RentalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitPSController extends Controller
{
    public function index(Request $request)
    {
        $query = UnitPS::where('stock', '>', 0);

        // Filter by model
        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Search by name or keywords (Semantic Search)
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('model', 'like', '%' . $request->q . '%')
                    ->orWhere('keywords', 'like', '%' . $request->q . '%');
            });
        }

        $units = $query->latest()->paginate(12);
        
        // Get top selling unit IDs (only the #1 best seller with at least 1 rental)
        $topSellingIds = RentalItem::where('rentable_type', UnitPS::class)
            ->select('rentable_id', DB::raw('COUNT(*) as rental_count'))
            ->groupBy('rentable_id')
            ->having('rental_count', '>=', 1)
            ->orderByDesc('rental_count')
            ->limit(1)
            ->pluck('rentable_id')
            ->toArray();

        return view('pelanggan.unitps.index', compact('units', 'topSellingIds'));
    }
}
