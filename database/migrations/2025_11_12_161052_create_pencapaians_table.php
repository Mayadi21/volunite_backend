<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pencapaian', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->unique();
            $table->string('deskripsi', 255)->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->foreignId('required_kategori')->nullable()->constrained('kategori'); 
            $table->integer('required_count_kategori')->nullable();
            $table->integer('required_exp')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pencapaian');
    }
};