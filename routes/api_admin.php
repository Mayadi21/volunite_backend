<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;

Route::get('/dashboard-stats', [DashboardController::class, 'stats']);

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::get('/activities/recent', [ActivityController::class, 'recent']); // untuk ditampilkan di dashboard admin

Route::get('/kegiatan', [ActivityController::class, 'index']);
Route::get('/kegiatan/{kegiatan}', [ActivityController::class, 'show']);
// admin hanya bisa mengubah status kegiatan
Route::put('/kegiatan/{kegiatan}/status', [ActivityController::class, 'updateStatus']);


Route::get('/kategori', [KategoriController::class, 'index']);
Route::post('/kategori', [KategoriController::class, 'store']);
Route::put('/kategori/{id}', [KategoriController::class, 'update']);
Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);
