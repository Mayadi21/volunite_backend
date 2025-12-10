<?php
// FILE: app/Http/Controllers/Auth/GoogleAuthController.php
// Fungsi: Menangani proses login/registrasi user menggunakan akun Google.
// Kegunaan: Dipanggil oleh route /api/auth/google ketika Flutter mengirimkan id_token.
// Cara manggil (HTTP): POST /api/auth/google dengan body { "id_token": "..." }.
// Cara manggil (Flutter): AuthService().loginWithGoogle() yang mengirim id_token ke endpoint ini.
// Catatan: Jika email belum ada di tabel users → user baru akan diregistrasi otomatis.

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\User;

class GoogleAuthController extends Controller
{
    public function handle(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
            'role'     => 'nullable|string|in:Volunteer,Organizer', // <- tambah ini
        ]);


        $idToken = $request->id_token;

        // role yang dikirim dari Flutter (Volunteer / Organizer)
        // kalau null / tidak valid -> default Volunteer
        $role = $request->input('role', 'Volunteer');
        if (!in_array($role, ['Volunteer', 'Organizer'])) {
            $role = 'Volunteer';
        }


        // 1. Verifikasi token ke Google
        $googleResponse = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $idToken,
        ]);

        if ($googleResponse->failed()) {
            return response()->json([
                'message' => 'Token Google tidak valid',
            ], 401);
        }

        $payload = $googleResponse->json();

        // OPSIONAL: cek aud (client_id) kalau mau lebih aman
        // if (($payload['aud'] ?? null) !== config('services.google.client_id')) {
        //     return response()->json(['message' => 'Aplikasi tidak dikenali'], 403);
        // }

        $email          = $payload['email'] ?? null;
        $name           = $payload['name'] ?? null;
        $emailVerified  = ($payload['email_verified'] ?? 'false') === 'true';

        if (!$email) {
            return response()->json([
                'message' => 'Email tidak ditemukan pada token Google',
            ], 400);
        }

        // 2. Cari user berdasarkan email
        $user = User::where('email', $email)->first();

        // 3. Jika belum ada → REGISTRASI user baru
        if (!$user) {
            $user = User::create([
                'nama'              => $name ?? $email,
                'email'             => $email,
                'password'          => bcrypt(Str::random(32)),
                'role'              => $role, // <- pakai role dari request
                'email_verified_at' => $emailVerified ? now() : null,
            ]);
        }


        // 4. Buat token API (contoh: Sanctum)
        $token = $user->createToken('mobile')->plainTextToken;

        // 5. Bentuk respons sesuai model User di Flutter (User.fromJson)
        return response()->json([
            'user' => [
                'id'                => $user->id,
                'nama'              => $user->nama,
                'email'             => $user->email,
                'role'              => $user->role,
                'email_verified_at' => $user->email_verified_at,
                'created_at'        => $user->created_at,
                'updated_at'        => $user->updated_at,
            ],
            'token' => $token,
        ]);
    }
}
