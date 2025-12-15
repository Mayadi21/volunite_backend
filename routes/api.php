<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\DetailUserController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\Admin\KategoriController;

Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/kegiatan', [KegiatanController::class, 'index']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/auth/google', [GoogleAuthController::class, 'handle']);
Route::post('/register', [ApiAuthController::class, 'register']);



// Route yang mewajibkan Login
Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
    
    Route::prefix('volunteer')->group(base_path('routes/api_volunteer.php'));

    Route::prefix('organizer')->group(base_path('routes/api_organizer.php'));

    Route::prefix('admin')->group(base_path('routes/api_admin.php'));

    // jan diganngu ya (mayyy)
    Route::post('/user/detail', [DetailUserController::class, 'storeOrUpdate']);
    
    Route::get('/notifikasi', [NotifikasiController::class, 'index']);
    Route::post('/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});



Route::post('/auth/google', [GoogleAuthController::class, 'handle']);
