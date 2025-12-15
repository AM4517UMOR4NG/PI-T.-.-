<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add fields to damage_reports for complete flow
        Schema::table('damage_reports', function (Blueprint $table) {
            // User confirmation after admin review
            $table->boolean('user_confirmed')->default(false)->after('status');
            $table->timestamp('user_confirmed_at')->nullable()->after('user_confirmed');
            
            // Fine payment tracking
            $table->boolean('fine_paid')->default(false)->after('fine_amount');
            $table->timestamp('fine_paid_at')->nullable()->after('fine_paid');
            $table->string('fine_payment_method')->nullable()->after('fine_paid_at');
            $table->string('fine_transaction_id')->nullable()->after('fine_payment_method');
            
            // Kasir confirmation after payment
            $table->foreignId('confirmed_by_kasir')->nullable()->after('fine_transaction_id')->constrained('users')->onDelete('set null');
            $table->timestamp('kasir_confirmed_at')->nullable()->after('confirmed_by_kasir');
            
            // Admin close case
            $table->foreignId('closed_by')->nullable()->after('kasir_confirmed_at')->constrained('users')->onDelete('set null');
            $table->timestamp('closed_at')->nullable()->after('closed_by');
        });

        // Add payment tracking to rentals
        Schema::table('rentals', function (Blueprint $table) {
            if (!Schema::hasColumn('rentals', 'fine_paid')) {
                $table->decimal('fine_paid', 12, 2)->default(0)->after('fine');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('damage_reports', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by_kasir']);
            $table->dropForeign(['closed_by']);
            $table->dropColumn([
                'user_confirmed', 'user_confirmed_at',
                'fine_paid', 'fine_paid_at', 'fine_payment_method', 'fine_transaction_id',
                'confirmed_by_kasir', 'kasir_confirmed_at',
                'closed_by', 'closed_at'
            ]);
        });

        Schema::table('rentals', function (Blueprint $table) {
            if (Schema::hasColumn('rentals', 'fine_paid')) {
                $table->dropColumn('fine_paid');
            }
        });
    }
};
