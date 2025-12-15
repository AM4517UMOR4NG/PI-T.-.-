<?php

namespace App\Console\Commands;

use App\Models\Rental;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelExpiredRentals extends Command
{
    protected $signature = 'rentals:cancel-expired';
    protected $description = 'Cancel rentals that have not been paid within 1 hour';

    public function handle()
    {
        $expiredRentals = Rental::where('status', 'pending')
            ->where('created_at', '<', now()->subHour())
            ->get();

        $count = 0;
        foreach ($expiredRentals as $rental) {
            $rental->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancel_reason' => 'Otomatis dibatalkan karena tidak dibayar dalam 1 jam',
            ]);

            // Restore stock for each item
            foreach ($rental->items as $item) {
                if ($item->rentable) {
                    $stockField = 'stok';
                    if ($item->rentable_type === 'App\Models\UnitPS') {
                        $stockField = 'stock';
                    }
                    $item->rentable->increment($stockField, $item->quantity);
                }
            }

            Log::info('Rental auto-cancelled due to non-payment', [
                'rental_id' => $rental->id,
                'customer_id' => $rental->customer_id,
                'created_at' => $rental->created_at,
            ]);

            $count++;
        }

        $this->info("Cancelled {$count} expired rentals.");
        return Command::SUCCESS;
    }
}
