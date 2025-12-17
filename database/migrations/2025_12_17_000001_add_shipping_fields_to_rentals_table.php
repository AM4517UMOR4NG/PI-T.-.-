<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            // Shipping/delivery method fields untuk UC006
            $table->string('delivery_method')->nullable()->after('delivery_address'); // 'pickup' atau 'delivery'
            $table->decimal('shipping_cost', 12, 2)->default(0)->after('delivery_method'); // Ongkos kirim
            $table->decimal('subtotal', 12, 2)->default(0)->change(); // Pastikan subtotal ada
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn(['delivery_method', 'shipping_cost']);
        });
    }
};
