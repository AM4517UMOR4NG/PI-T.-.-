<?php

namespace App\Services;

use App\Models\Rental;
use Carbon\Carbon;

class LateFeeService
{
    /**
     * Calculate late fee based on hours overdue
     * 
     * Rules:
     * - 1-3 hours: 20% of rental price
     * - 3-8 hours: 40% of rental price
     * - 8-12 hours: 60% of rental price
     * - >12 hours: 100% of rental price (pay double)
     */
    public static function calculateLateFee(Rental $rental): array
    {
        $endTime = Carbon::parse($rental->due_at);
        $now = now();

        // If due_at is null or not overdue, no late fee
        if (!$rental->due_at || $now->lte($endTime)) {
            return [
                'is_late' => false,
                'hours_late' => 0,
                'late_fee' => 0,
                'percentage' => 0,
                'description' => 'Tepat waktu',
            ];
        }

        // Calculate hours late (absolute difference, since we already checked now > endTime)
        $hoursLate = $endTime->diffInHours($now);
        $minutesLate = $endTime->diffInMinutes($now) % 60;
        
        // Round up if there are extra minutes
        if ($minutesLate > 0) {
            $hoursLate++;
        }
        
        // Ensure at least 1 hour if late
        if ($hoursLate < 1) {
            $hoursLate = 1;
        }

        // Get rental total price (without existing fines)
        $rentalPrice = $rental->total - ($rental->fine ?? 0);

        // Calculate percentage based on hours late
        $percentage = self::getLateFeePercentage($hoursLate);
        $lateFee = $rentalPrice * ($percentage / 100);

        // Get description
        $description = self::getLateFeeDescription($hoursLate, $percentage);

        return [
            'is_late' => true,
            'hours_late' => $hoursLate,
            'minutes_late' => $minutesLate,
            'late_fee' => round($lateFee),
            'percentage' => $percentage,
            'rental_price' => $rentalPrice,
            'description' => $description,
        ];
    }

    /**
     * Get late fee percentage based on hours
     */
    public static function getLateFeePercentage(int $hours): int
    {
        if ($hours <= 0) {
            return 0;
        } elseif ($hours <= 3) {
            return 20;
        } elseif ($hours <= 8) {
            return 40;
        } elseif ($hours <= 12) {
            return 60;
        } else {
            return 100;
        }
    }

    /**
     * Get description for late fee
     */
    public static function getLateFeeDescription(int $hours, int $percentage): string
    {
        if ($hours <= 0) {
            return 'Tepat waktu';
        } elseif ($hours <= 3) {
            return "Terlambat {$hours} jam - Denda {$percentage}% dari harga sewa";
        } elseif ($hours <= 8) {
            return "Terlambat {$hours} jam - Denda {$percentage}% dari harga sewa";
        } elseif ($hours <= 12) {
            return "Terlambat {$hours} jam - Denda {$percentage}% dari harga sewa";
        } else {
            return "Terlambat {$hours} jam - Denda {$percentage}% (bayar dua kali lipat)";
        }
    }

    /**
     * Apply late fee to rental
     */
    public static function applyLateFee(Rental $rental): Rental
    {
        $lateFeeData = self::calculateLateFee($rental);

        if ($lateFeeData['is_late'] && $lateFeeData['late_fee'] > 0) {
            // Add late fee to existing fine (damage fine)
            $existingFine = $rental->fine ?? 0;
            $newFine = $existingFine + $lateFeeData['late_fee'];

            $rental->update([
                'fine' => $newFine,
                'late_fee' => $lateFeeData['late_fee'],
                'late_hours' => $lateFeeData['hours_late'],
                'late_fee_description' => $lateFeeData['description'],
            ]);
        }

        return $rental->fresh();
    }

    /**
     * Get late fee rules for display
     */
    public static function getLateFeeRules(): array
    {
        return [
            ['range' => '1-3 jam', 'percentage' => '20%', 'description' => 'Denda ringan'],
            ['range' => '3-8 jam', 'percentage' => '40%', 'description' => 'Denda sedang'],
            ['range' => '8-12 jam', 'percentage' => '60%', 'description' => 'Denda berat'],
            ['range' => '> 12 jam', 'percentage' => '100%', 'description' => 'Bayar dua kali lipat'],
        ];
    }
}
