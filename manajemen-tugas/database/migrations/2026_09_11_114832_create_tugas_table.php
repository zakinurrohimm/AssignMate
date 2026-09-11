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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel mata kuliah (menggantikan dropdown Excel)
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->onDelete('cascade');
            $table->string('nama_tugas');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_diberikan')->nullable();
            $table->dateTime('deadline');
            $table->enum('prioritas', ['Tinggi', 'Sedang', 'Rendah'])->default('Sedang');
            $table->enum('status', ['Belum Dikerjakan', 'Sedang Dikerjakan', 'Selesai'])->default('Belum Dikerjakan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
