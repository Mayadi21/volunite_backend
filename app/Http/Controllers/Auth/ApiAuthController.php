<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

use function Symfony\Component\Clock\now;

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

    public function register(Request $request)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'nama'                  => 'required|string|max:50',
            'email'                 => 'required|string|email|max:120|unique:users,email',
            'password'              => 'required|string|min:8|confirmed', // butuh field password_confirmation
            'role'                  => 'required|in:Volunteer,Organizer', // enum di migration
        ]);

        // 2. Buat user baru
        $user = User::create([
            'nama'        => $validated['nama'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'role'        => $validated['role'],
            'path_profil' => null,
            'email_verified_at' => now(), // biar gak susah ya we
        ]);

        // 3. Buat token Sanctum
        $token = $user->createToken('mobile')->plainTextToken;

        // 4. Kembalikan response sesuai model Flutter (AuthResponse)
        return response()->json([
            'user' => [
                'id'                => $user->id,
                'nama'              => $user->nama,
                'email'             => $user->email,
                'role'              => $user->role,
                'path_profil'       => $user->path_profil,
                'email_verified_at' => $user->email_verified_at,
                'created_at'        => $user->created_at,
                'updated_at'        => $user->updated_at,
            ],
            'token' => $token,
        ], 201);
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
