<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Organizer\ManageKegiatanController;

Route::get('/dashboard', [ManageKegiatanController::class, 'index']);
Route::get('/kegiatan/{id}', [ManageKegiatanController::class, 'show']);
Route::post('/kegiatan', [ManageKegiatanController::class, 'store']);
Route::post('/kegiatan/{id}', [ManageKegiatanController::class, 'update']);
Route::delete('/kegiatan/{id}', [ManageKegiatanController::class, 'destroy']);