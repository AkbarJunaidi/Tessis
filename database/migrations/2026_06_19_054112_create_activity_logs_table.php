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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users (siapa yang beraktivitas)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Menyimpan string deskripsi log aktivitas
            $table->string('activity');
            // Menampung class target (loggable_type) dan ID target (loggable_id) secara polimorfik
            $table->nullableMorphs('loggable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
