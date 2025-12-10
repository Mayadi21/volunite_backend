<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    /**
     * Menangani pendaftaran pengguna untuk suatu kegiatan.
     */
    public function store(Request $request, $kegiatanId)
    {
        // Ambil user dari token
        $userId = $request->user()->id;

        // Validasi input lain (tanpa user_id)
        $request->validate([
            'nomor_telepon' => 'required|string|max:15',
            'domisili' => 'required|string|max:255',
            'komitmen' => 'required|string|max:500',
            'keterampilan' => 'required|string|max:255',
        ]);

        // Pastikan kegiatan ada
        $kegiatan = Kegiatan::find($kegiatanId);
        if (!$kegiatan) {
            return response()->json(['message' => 'Kegiatan tidak ditemukan.'], 404);
        }

        // Cek apakah user sudah pernah daftar
        $sudahDaftar = Pendaftaran::where('user_id', $userId)
            ->where('kegiatan_id', $kegiatanId)
            ->exists();

        if ($sudahDaftar) {
            return response()->json(['message' => 'Anda sudah terdaftar dalam kegiatan ini.'], 409);
        }

        // Simpan data
        try {
            DB::beginTransaction();

            $pendaftaran = Pendaftaran::create([
                'user_id' => $userId,
                'kegiatan_id' => $kegiatanId,
                'status' => 'Diterima',
                'status_kehadiran' => 'Belum Dicek',
            ]);

            $pendaftaran->detailPendaftaran()->create([
                'nomor_telepon' => $request->nomor_telepon,
                'domisili' => $request->domisili,
                'komitmen' => $request->komitmen,
                'keterampilan' => $request->keterampilan,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Pendaftaran kegiatan berhasil!',
                'data' => $pendaftaran->load('detailPendaftaran')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Pendaftaran gagal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function status(Request $request, $kegiatanId)
    {
        $userId = $request->user()->id;

        // Cek apakah kegiatan ada
        $kegiatan = Kegiatan::find($kegiatanId);
        if (!$kegiatan) {
            return response()->json(['message' => 'Kegiatan tidak ditemukan.'], 404);
        }

        $sudahDaftar = Pendaftaran::where('user_id', $userId)
            ->where('kegiatan_id', $kegiatanId)
            ->exists();

        return response()->json([
            'is_registered' => $sudahDaftar
        ], 200);
    }
}
