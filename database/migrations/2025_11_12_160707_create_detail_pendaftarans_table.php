<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->unique()->constrained('pendaftaran')->onDelete('cascade');
            $table->string('nomor_telepon', 15)->nullable();
            $table->string('domisili', 100)->nullable();
            $table->text('komitmen')->nullable();
            $table->text('keterampilan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pendaftaran');
    }
};