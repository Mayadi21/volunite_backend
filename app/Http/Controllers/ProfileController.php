<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\DetailUser; 

class ProfileController extends Controller
{
    // 1. GET DATA PROFILE
    public function show(Request $request)
    {
        $user = $request->user();
        
        // Ambil atau buat data detail user jika belum ada
        $detail = DetailUser::firstOrCreate(['user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'detail' => $detail
            ]
        ]);
    }

    // 2. UPDATE DATA PROFILE (Multipart Form Data)
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jenis_kelamin' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'no_telepon' => 'nullable|string|max:15',
            'domisili' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // A. Update User (Nama & Foto)
            $user->nama = $request->nama;

            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($user->path_profil && Storage::exists('public/' . $user->path_profil)) {
                    Storage::delete('public/' . $user->path_profil);
                }
                
                // Simpan foto baru
                $path = $request->file('foto')->store('images/profiles', 'public');
                $user->path_profil = $path;
            }
            $user->save();

            // B. Update Detail User
            $detail = DetailUser::firstOrCreate(['user_id' => $user->id]);
            $detail->update([
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_telepon' => $request->no_telepon,
                'domisili' => $request->domisili,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'data' => [
                    'user' => $user->refresh(),
                    'detail' => $detail
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // 3. GANTI PASSWORD
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Kata sandi lama salah.',
                'errors' => ['current_password' => ['Kata sandi lama tidak sesuai.']]
            ], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['success' => true, 'message' => 'Kata sandi berhasil diubah.']);
    }
}