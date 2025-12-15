<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use App\Models\RentalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Accessory::where('stok', '>', 0);

        // Filter by jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', 'like', '%' . $request->jenis . '%');
        }

        // Search by name or keywords (Semantic Search)
        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->q . '%')
                  ->orWhere('keywords', 'like', '%' . $request->q . '%');
            });
        }

        $accessories = $query->latest()->paginate(12);
        
        // Get top selling accessory IDs (only the #1 best seller with at least 1 rental)
        $topSellingIds = RentalItem::where('rentable_type', Accessory::class)
            ->select('rentable_id', DB::raw('COUNT(*) as rental_count'))
            ->groupBy('rentable_id')
            ->having('rental_count', '>=', 1)
            ->orderByDesc('rental_count')
            ->limit(1)
            ->pluck('rentable_id')
            ->toArray();

        return view('pelanggan.accessories.index', compact('accessories', 'topSellingIds'));
    }
}
