<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            // Setiap task terikat wajib ke sebuah project
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            // Menyimpan status dengan default nilai 'Todo'
            $table->string('status')->default('Todo');
            // Menyimpan priority dengan default nilai 'Medium'
            $table->string('priority')->default('Medium');
            $table->date('deadline');
            // User pelaksana tugas (bisa dikosongkan/nullable)
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
