<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class ApiAuthController extends Controller
{
    /**
     * Login API
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah.',
            ], 401);
        }

        // Kalau user punya role Banned, blokir login
        if ($user->role === 'Banned') {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun Anda diblokir.',
            ], 403);
        }

        // Hapus token lama (opsional, biar satu device saja)
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Logout API
     */
    public function logout(Request $request): JsonResponse
    {
        // Hapus token yang dipakai saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * Get current user (profil dari token)
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
