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
        // 1. Validasi Input
        $request->validate([
            // Asumsi user_id dikirimkan di request body, atau Anda bisa mengambilnya dari Auth::id()
            'user_id' => 'required|exists:users,id',
            'nomor_telepon' => 'required|string|max:15',
            'domisili' => 'required|string|max:255',
            'komitmen' => 'required|string|max:500',
            'keterampilan' => 'required|string|max:255',
        ]);
        
        // Cek apakah kegiatan tersebut ada
        $kegiatan = Kegiatan::find($kegiatanId);
        if (!$kegiatan) {
            return response()->json(['message' => 'Kegiatan tidak ditemukan.'], 404);
        }

        $userId = $request->user_id;

        // 2. Cek Duplikasi Pendaftaran
        $sudahDaftar = Pendaftaran::where('user_id', $userId)
                                  ->where('kegiatan_id', $kegiatanId)
                                  ->exists();

        if ($sudahDaftar) {
            return response()->json(['message' => 'Anda sudah terdaftar dalam kegiatan ini.'], 409);
        }

        // 3. Proses Penyimpanan Data (Menggunakan Transaction)
        try {
            DB::beginTransaction();

            // A. Simpan ke tabel pendaftaran
            $pendaftaran = Pendaftaran::create([
                'user_id' => $userId,
                'kegiatan_id' => $kegiatanId,
                // Nilai default: Status 'Diterima' untuk langsung diterima, atau 'Pending' jika ada review
                'status' => 'Diterima', 
                'status_kehadiran' => 'Belum Dicek',
                // tanggal_kehadiran null secara default
            ]);

            // B. Simpan ke tabel detail_pendaftaran
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
            // Log the error
            // \Log::error('Pendaftaran Gagal: ' . $e->getMessage());
            return response()->json(['message' => 'Pendaftaran gagal disimpan.', 'error' => $e->getMessage()], 500);
        }
    }
}