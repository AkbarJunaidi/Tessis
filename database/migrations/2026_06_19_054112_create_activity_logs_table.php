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
        Schema::create('activity_logs', function (Blueprint $class) {
            $class->id();

            // Foreign key relasi ke tabel users dengan penanganan jika user dihapus
            $class->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Menyimpan nama kluster modul utama sistem
            $class->string('module');

            // Menyimpan jenis tindakan spesifik yang dilakukan user
            $class->string('action');

            // Timestamps otomatis (created_at berfungsi sebagai kolom Date Time)
            $class->timestamps();

            // Indexing dasar untuk optimasi performa filtering dan pencarian data
            $class->index(['module', 'action']);
            $class->index('created_at');
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
