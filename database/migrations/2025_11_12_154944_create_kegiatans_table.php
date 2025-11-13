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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 100);
            $table->string('thumbnail', 255)->nullable(); 
            $table->string('deskripsi', 150)->nullable(); 
            $table->string('lokasi', 150)->nullable(); 
            $table->text('syarat_ketentuan')->nullable(); 
            $table->integer('kuota')->nullable(); 
            $table->dateTime('tanggal_mulai')->nullable(); 
            $table->dateTime('tanggal_berakhir')->nullable(); 
            $table->enum('status', ['Waiting', 'Rejected', 'scheduled', 'on progress', 'finished', 'cancelled'])
                  ->default('Waiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};