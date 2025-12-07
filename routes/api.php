<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Admin\KategoriController;

Route::post('/login', [ApiAuthController::class, 'login']);

Route::get('/kategori', [KategoriController::class, 'index']);

// Route yang mewajibkan Login
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::prefix('volunteer')->group(base_path('routes/api_volunteer.php'));

    Route::prefix('organizer')->group(base_path('routes/api_organizer.php'));
  
    Route::prefix('admin')->group(base_path('routes/api_admin.php'));

});