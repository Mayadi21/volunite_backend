<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PendaftaranController;


Route::post('/login', [ApiAuthController::class, 'login']);

  Route::get('/kegiatan', [KegiatanController::class, 'index']);
  Route::post('/kegiatan/{kegiatanId}/pendaftaran', [PendaftaranController::class, 'store']);
  Route::get('/kegiatan/{kegiatanId}/pendaftaran/status', [PendaftaranController::class, 'status']);
