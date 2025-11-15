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
        Schema::create('report_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')
                  ->constrained('kegiatan') 
                  ->onDelete('cascade'); 
            $table->foreignId('user_id')
                  ->constrained('users') 
                  ->onDelete('cascade');
            $table->enum('keluhan', ['Ilegal/Penipuan', 'Informasi Palsu', 'Tidak Relevan', 'Pelanggaran S&K', 'Diskriminasi/Pelanggaran Etika', 'Kegiatan Fiktif', 'lainnya']);  
            $table->text('detail_keluhan')->nullable();
            $table->enum('status', ['Diproses', 'Dibaca', 'Ditindak']); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_kegiatan');
    }
};
