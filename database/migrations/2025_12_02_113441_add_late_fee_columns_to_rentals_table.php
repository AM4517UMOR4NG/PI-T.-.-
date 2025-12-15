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
        Schema::table('rentals', function (Blueprint $table) {
            $table->decimal('late_fee', 12, 2)->default(0)->after('fine');
            $table->integer('late_hours')->default(0)->after('late_fee');
            $table->string('late_fee_description')->nullable()->after('late_hours');
            $table->timestamp('cancelled_at')->nullable()->after('late_fee_description');
            $table->string('cancel_reason')->nullable()->after('cancelled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn(['late_fee', 'late_hours', 'late_fee_description', 'cancelled_at', 'cancel_reason']);
        });
    }
};
