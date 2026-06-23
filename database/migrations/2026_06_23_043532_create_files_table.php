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
        Schema::create('files', function (Blueprint $table) {
            $table->id();

            // folder_id dibuat NULLABLE untuk mendukung berkas pribadi di 'My Files'
            $table->foreignId('folder_id')
                ->nullable()
                ->constrained('folders')
                ->onDelete('cascade'); // Jika folder management dihapus, record file di dalamnya otomatis terhapus

            // Relasi kepemilikan berkas ke user login
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('file_name'); // Nama asli file (contoh: laporan_keuangan.pdf)
            $table->string('file_path'); // Path acuan acak/aman di local storage private (UUID/Hash name)
            $table->bigInteger('file_size'); // Ukuran berkas dalam hitungan Bytes
            $table->string('file_type'); // Ekstensi/Mime-type berkas (pdf, docx, png, dll.)

            $table->timestamps();
            $table->softDeletes(); // Sesuai standarisasi spesifikasi Database Design.md

            // Indexing gabungan untuk optimalisasi kueri isolasi My Files dan Folder Management
            $table->index(['folder_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
