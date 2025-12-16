<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            // Delivery tracking fields
            $table->timestamp('delivered_at')->nullable()->after('due_at');
            $table->timestamp('delivery_confirmed_at')->nullable()->after('delivered_at');
            $table->foreignId('delivered_by')->nullable()->after('delivery_confirmed_at');
            $table->text('delivery_notes')->nullable()->after('delivered_by');
            $table->text('delivery_address')->nullable()->after('delivery_notes');
            
            // Add foreign key constraint
            $table->foreign('delivered_by')->references('id')->on('users')->nullOnDelete();
        });

        // Update status enum to include 'menunggu_pengantaran'
        // First convert to VARCHAR to safely update
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");
        
        // Then set the new enum values
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending', 'menunggu_pengantaran', 'sedang_disewa', 'menunggu_konfirmasi', 'selesai', 'cancelled') DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign(['delivered_by']);
            $table->dropColumn([
                'delivered_at',
                'delivery_confirmed_at',
                'delivered_by',
                'delivery_notes',
                'delivery_address',
            ]);
        });

        // Revert status enum (remove 'menunggu_pengantaran')
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending', 'sedang_disewa', 'menunggu_konfirmasi', 'selesai', 'cancelled') DEFAULT 'pending'");
    }
};
