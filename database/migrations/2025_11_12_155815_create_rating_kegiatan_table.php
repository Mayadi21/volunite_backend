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
        Schema::create('rating_kegiatan', function (Blueprint $table) {
            
            $table->id(); 
            $table->foreignId('kegiatan_id')
                  ->constrained('kegiatan') 
                  ->onDelete('cascade'); 
            $table->foreignId('user_id')
                  ->constrained('users') 
                  ->onDelete('cascade'); 
            $table->integer('rate')->unsigned(); 
            $table->unique(['kegiatan_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_kegiatan');
    }
};