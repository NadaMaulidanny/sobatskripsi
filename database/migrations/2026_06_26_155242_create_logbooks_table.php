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
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete(); // Dosen yang membimbing saat itu
            $table->string('bab'); // Misal: Bab 1, Bab 2, atau Umum
            $table->text('kegiatan'); // Progres yang dilaporkan mahasiswa
            $table->string('file_bab'); // Progres yang dilaporkan mahasiswa
            $table->text('catatan_dosen')->nullable(); // Revisi dari dosen
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'revisi', 'acc'])->default('pending');
            $table->date('tanggal_bimbingan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};
