<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\ReportKegiatanController;
use App\Http\Controllers\Volunteer\ProfileController;

  Route::get('/profile', [ProfileController::class, 'index']);

  Route::get('/kegiatan', [KegiatanController::class, 'index']);
  Route::post('/kegiatan/{kegiatanId}/report', [ReportKegiatanController::class, 'store']);
  Route::post('/kegiatan/{kegiatanId}/pendaftaran', [PendaftaranController::class, 'store']);
  Route::get('/kegiatan/{kegiatanId}/pendaftaran/status', [PendaftaranController::class, 'status']);
