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
        Schema::create('pembimbing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained()->cascadeOnDelete();
            
            // Ubah enum-nya agar ada status 'request_1' dan 'request_2'
            $table->enum('status', [
                'request1', 
                'request2', 
                'pembimbing1', 
                'pembimbing2'
            ])->default('request1');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembimbing');
    }
};
