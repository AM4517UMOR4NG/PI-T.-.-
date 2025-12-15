<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->decimal('fine', 10, 2)->default(0)->after('total');
        });

        Schema::table('rental_items', function (Blueprint $table) {
            $table->enum('condition', ['baik', 'rusak'])->default('baik')->after('total');
            $table->decimal('fine', 10, 2)->default(0)->after('condition');
            $table->text('fine_description')->nullable()->after('fine');
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('fine');
        });

        Schema::table('rental_items', function (Blueprint $table) {
            $table->dropColumn(['condition', 'fine', 'fine_description']);
        });
    }
};
