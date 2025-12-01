<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\KegiatanController;


Route::post('/login', [ApiAuthController::class, 'login']);

// Route yang mewajibkan Login
Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('/kegiatan', [KegiatanController::class, 'index']);
});
