<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            // Perbesar kolom kode dari 6 ke 10 karakter untuk format RNT-XXX
            $table->string('kode', 10)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->string('kode', 6)->nullable()->change();
        });
    }
};
