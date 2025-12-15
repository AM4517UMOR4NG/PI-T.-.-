<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\RentalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::where('stok', '>', 0);

        // Filter by platform
        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        // Filter by genre
        if ($request->filled('genre')) {
            $query->where('genre', 'like', '%' . $request->genre . '%');
        }

        // Search by title or keywords (Semantic Search)
        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->q . '%')
                  ->orWhere('keywords', 'like', '%' . $request->q . '%');
            });
        }

        $games = $query->latest()->paginate(12);
        
        // Get top selling game IDs (only the #1 best seller with at least 1 rental)
        $topSellingIds = RentalItem::where('rentable_type', Game::class)
            ->select('rentable_id', DB::raw('COUNT(*) as rental_count'))
            ->groupBy('rentable_id')
            ->having('rental_count', '>=', 1)
            ->orderByDesc('rental_count')
            ->limit(1)
            ->pluck('rentable_id')
            ->toArray();

        return view('pelanggan.games.index', compact('games', 'topSellingIds'));
    }
}
