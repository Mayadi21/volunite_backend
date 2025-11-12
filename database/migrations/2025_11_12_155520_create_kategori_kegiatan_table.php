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
        Schema::create('kategori_kegiatan', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('kegiatan_id')
                  ->constrained('kegiatan') 
                  ->onDelete('cascade'); 
            $table->foreignId('kategori_id')
                  ->constrained('kategori') 
                  ->onDelete('cascade'); 
            $table->unique(['kegiatan_id', 'kategori_id']);
            
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_kegiatan');
    }
};