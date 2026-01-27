<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\GachaLog;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GachaController extends Controller
{
    public function index()
    {
        $hasSpunToday = GachaLog::where('user_id', auth()->id())
            ->whereDate('created_at', Carbon::today())
            ->exists();

        return view('pages.gacha', compact('hasSpunToday'));
    }

    public function spin()
    {
        $user = auth()->user();

        // 1. Check Daily Limit
        $hasSpunToday = GachaLog::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($hasSpunToday) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already used your daily spin! Come back tomorrow.'
            ], 403);
        }

        // 2. RNG Logic
        $rand = rand(1, 100);
        $rewardType = 'common';
        $coupon = null;
        $description = 'Better luck next time!';
        $isWin = false;

        if ($rand <= 60) {
            // Common (60%) - Mostly Zonk or Tiny Discount
            $rewardType = 'common';
            if (rand(1, 100) <= 20) { // 20% chance inside Common to get small prize
                $isWin = true;
                $coupon = $this->createCoupon($user->id, 'percent', 5); // 5%
                $description = '5% Discount Voucher';
            } else {
                $description = 'ZONK! Just an empty box.';
            }
        } elseif ($rand <= 90) {
            // Rare (30%)
            $rewardType = 'rare';
            $isWin = true;
            $coupon = $this->createCoupon($user->id, 'percent', 10); // 10%
            $description = '10% Discount Voucher';
        } elseif ($rand <= 99) {
            // Epic (9%)
            $rewardType = 'epic';
            $isWin = true;
            $coupon = $this->createCoupon($user->id, 'fixed', 50000); // 50k OFF
            $description = 'Rp 50.000 Discount Voucher';
        } else {
            // Legendary (1%)
            $rewardType = 'legendary';
            $isWin = true;
            $coupon = $this->createCoupon($user->id, 'percent', 100); // FREE
            $description = 'FREE RENTAL VOUCHER (100% OFF)!';
        }

        // 3. Log Transaction
        GachaLog::create([
            'user_id' => $user->id,
            'reward_type' => $rewardType,
            'description' => $description
        ]);

        return response()->json([
            'status' => 'success',
            'reward_type' => $rewardType,
            'description' => $description,
            'is_win' => $isWin,
            'coupon_code' => $coupon ? $coupon->code : null
        ]);
    }

    private function createCoupon($userId, $type, $value)
    {
        return Coupon::create([
            'user_id' => $userId,
            'code' => 'GCH-' . strtoupper(Str::random(8)),
            'type' => $type,
            'value' => $value,
            'status' => 'active',
            'expires_at' => Carbon::now()->addDays(7) // Valid for 7 days
        ]);
    }
}
