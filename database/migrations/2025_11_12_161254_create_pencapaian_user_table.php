<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pencapaian_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pencapaian_id')->constrained('pencapaian')->onDelete('cascade'); 
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->unique(['pencapaian_id', 'user_id']);
            
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pencapaian_user');
    }
};