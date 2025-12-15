<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->loadMissing('detailUser');

        return response()->json([
            'user' => [
                'id'          => $user->id,
                'nama'        => $user->nama,
                'email'       => $user->email,
                'role'        => $user->role,
                'path_profil' => $user->path_profil, // sudah URL
            ],
            'detail' => [
                'tanggal_lahir' => optional($user->detailUser)->tanggal_lahir,
                'jenis_kelamin' => optional($user->detailUser)->jenis_kelamin,
                'no_telepon'    => optional($user->detailUser)->no_telepon,
                'domisili'      => optional($user->detailUser)->domisili,
            ]
        ]);
    }

    public function update(Request $request)
{
    /** @var User $user */
    $user = Auth::user();

    // 1. Lakukan Validasi
    $validated = $request->validate([
        'nama'          => 'required|string|max:255',
        'jenis_kelamin' => 'nullable|in:Laki-Laki,Perempuan,Tidak Ingin Memberi Tahu',
        'tanggal_lahir' => 'nullable|date_format:Y-m-d',
        'no_telepon'    => 'nullable|string|max:20',
        'domisili'      => 'nullable|string|max:255',
        'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // 2. Data untuk DetailUser (Hanya field yang dimiliki DetailUser)
    $detailData = array_intersect_key($validated, [
        'jenis_kelamin' => null,
        'tanggal_lahir' => null,
        'no_telepon'    => null,
        'domisili'      => null,
    ]);

    // 3. Update Tabel Users (Hanya Nama)
    $user->update([
        'nama' => $validated['nama'],
    ]);

    // 4. Update atau Create DetailUser
    if (!empty($detailData)) { // Hanya proses jika ada data detail yang dikirim/berhasil divalidasi
        if ($user->detailUser) {
            $user->detailUser->update($detailData); // Jika sudah ada, update
        } else {
            // Jika belum ada, buat record baru dengan data detail yang tersedia.
            // Pastikan user_id akan terisi otomatis melalui relasi.
            $user->detailUser()->create($detailData); 
        }
    }
    // Jika $detailData kosong (hanya update nama), maka blok ini dilewati, dan tidak ada create yang gagal.

    // 5. Penanganan Foto (Sudah benar)
    if ($request->hasFile('foto')) {
        $oldPath = $user->attributes['path_profil'] ?? null;
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
        $path = $request->file('foto')->store('profiles', 'public');
        $user->update(['path_profil' => $path]);
    }

    return response()->json(['message' => 'Profile berhasil diperbarui']);
}
}
