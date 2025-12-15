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
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_item_id')->constrained('rental_items')->onDelete('cascade');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable();
            
            // 6 photos from different angles
            $table->string('photo_top')->nullable();      // Foto dari atas
            $table->string('photo_bottom')->nullable();   // Foto dari bawah
            $table->string('photo_front')->nullable();    // Foto dari depan
            $table->string('photo_back')->nullable();     // Foto dari belakang
            $table->string('photo_left')->nullable();     // Foto dari kiri
            $table->string('photo_right')->nullable();    // Foto dari kanan
            
            // Admin review
            $table->enum('status', ['pending', 'reviewed', 'resolved'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('admin_feedback')->nullable();
            $table->decimal('fine_amount', 12, 2)->nullable();
            $table->timestamp('reviewed_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
};
