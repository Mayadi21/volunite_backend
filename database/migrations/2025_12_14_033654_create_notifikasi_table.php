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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel users
            // onDelete('cascade') berarti jika user dihapus, notifikasinya juga hilang
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('judul');
            
            // Saya buat nullable jaga-jaga kalau tidak selalu ada subjudul
            // Hapus ->nullable() jika subjudul wajib diisi
            $table->string('subjudul'); 
            
            // Boolean dengan default false (0) artinya belum dibaca
            $table->boolean('read')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};