<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Organizer\ManageKegiatanController;
use App\Http\Controllers\Organizer\DashboardController;
use App\Http\Controllers\Organizer\ProfileController;

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/profile-stats', [ProfileController::class, 'index']);

Route::get('/kegiatan', [ManageKegiatanController::class, 'index']);
Route::get('/kegiatan/{id}', [ManageKegiatanController::class, 'show']);
Route::post('/kegiatan', [ManageKegiatanController::class, 'store']);
Route::post('/kegiatan/{id}', [ManageKegiatanController::class, 'update']);
Route::delete('/kegiatan/{id}', [ManageKegiatanController::class, 'destroy']);
Route::put('/kegiatan/{id}/status', [ManageKegiatanController::class, 'updateStatus']);

Route::get('/kegiatan/{id}/pendaftar', [ManageKegiatanController::class, 'getPendaftar']);

Route::post('/pendaftar/{id}/update-status', [ManageKegiatanController::class, 'updateStatusPendaftaran']);
Route::post('/pendaftar/{id}/update-kehadiran', [ManageKegiatanController::class, 'updateKehadiran']);