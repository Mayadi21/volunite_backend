<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->onDelete('cascade');
            $table->enum('status', ['Mengajukan', 'Diterima', 'Ditolak'])->default('Mengajukan');
            $table->enum('status_kehadiran', ['Belum Dicek', 'Hadir', 'Tidak Hadir'])->default('Belum Dicek');
            $table->dateTime('tanggal_kehadiran')->nullable();
            $table->unique(['user_id', 'kegiatan_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};