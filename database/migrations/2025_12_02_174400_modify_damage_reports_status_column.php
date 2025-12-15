<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change status column from enum to string to support more statuses
        Schema::table('damage_reports', function (Blueprint $table) {
            // For MySQL, we need to modify the column type
            DB::statement("ALTER TABLE damage_reports MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('damage_reports', function (Blueprint $table) {
            DB::statement("ALTER TABLE damage_reports MODIFY COLUMN status ENUM('pending', 'reviewed', 'resolved') DEFAULT 'pending'");
        });
    }
};
