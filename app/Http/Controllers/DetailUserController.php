<?php
// FILE: app/Http/Controllers/DetailUserController.php
// Fungsi: Menangani pembuatan atau pembaruan data detail_users milik user yang sedang login.
// Kegunaan: Dipanggil setelah user login (termasuk login via Google) untuk melengkapi profil.
// Cara manggil (HTTP): POST /api/user/detail dengan body: tanggal_lahir, jenis_kelamin, no_telepon, domisili.
// Cara pakai (Flutter): DetailUserService().saveDetailUser(...).
// Catatan: Menggunakan token (Sanctum/JWT) untuk mengetahui user yang sedang login.

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailUser;

class DetailUserController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        $user = $request->user(); // user dari token Sanctum

        // 1. Validasi
        $validated = $request->validate([
            'tanggal_lahir' => 'required|date', // nanti kita atur formatnya
            'jenis_kelamin' => 'nullable|in:Laki-Laki,Perempuan,Tidak Ingin Memberi Tahu',
            'no_telepon'    => 'nullable|string|max:20',
            'domisili'      => 'nullable|string|max:100',
        ]);

        // 2. Kalau tanggal_lahir kamu kirim "dd-mm-YYYY" dari Flutter,
        //    ubah ke format "YYYY-mm-dd" sebelum simpan:
        if ($request->filled('tanggal_lahir')) {
            try {
                $tanggal = \Carbon\Carbon::parse($request->tanggal_lahir);
            } catch (\Exception $e) {
                // fallback kalau ternyata dari Flutter kirim "dd-mm-YYYY"
                $tanggal = \Carbon\Carbon::createFromFormat('d-m-Y', $request->tanggal_lahir);
            }

            $validated['tanggal_lahir'] = $tanggal->format('Y-m-d');
        }

        // 3. Cek apakah detail user sudah ada (karena user_id unik)
        $detail = DetailUser::where('user_id', $user->id)->first();

        if ($detail) {
            // update
            $detail->update($validated);
        } else {
            // create
            $detail = DetailUser::create(array_merge($validated, [
                'user_id' => $user->id,
            ]));
        }

        return response()->json([
            'message' => 'Detail user berhasil disimpan',
            'data'    => $detail,
        ], 200);
    }
}
