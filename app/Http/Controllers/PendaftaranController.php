<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class PendaftaranController extends Controller
{
    /**
     * Menangani pendaftaran pengguna untuk suatu kegiatan, termasuk validasi kuota
     * dan penentuan status berdasarkan metode penerimaan.
     */
    public function store(Request $request, $kegiatanId)
    {
        // Ambil user dari token
        $userId = $request->user()->id;

        // Validasi input lain
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

        // Ambil data kunci
        $metodePenerimaan = strtolower($kegiatan->metode_penerimaan ?? 'manual');
        $kuota = $kegiatan->kuota;

        try {
            // 🔥 Mulai Transaksi untuk mencegah race condition
            DB::beginTransaction();
            
            // 1. Cek apakah user sudah pernah daftar
            $sudahDaftar = Pendaftaran::where('user_id', $userId)
                ->where('kegiatan_id', $kegiatanId)
                ->exists();

            if ($sudahDaftar) {
                DB::rollBack();
                return response()->json(['message' => 'Anda sudah terdaftar dalam kegiatan ini.'], 409);
            }

            // 2. 🔥 Tentukan Status Awal
            // Jika metode otomatis, status awal langsung 'Diterima'
            // Jika metode manual, status awal adalah 'Mengajukan'
            $statusAwal = ($metodePenerimaan == 'otomatis') ? 'Diterima' : 'Mengajukan';
            
            // 3. Pengecekan Kuota (Hanya untuk pendaftar yang statusnya akan DITERIMA)
            if ($kuota > 0 && $statusAwal == 'Diterima') {
                // Hitung jumlah pendaftar yang sudah DITERIMA (status final yang mengurangi kuota)
                $pendaftarDiterima = Pendaftaran::where('kegiatan_id', $kegiatanId)
                    ->where('status', 'Diterima')
                    ->count(); 
                
                // Jika pendaftar yang sudah Diterima + pendaftar baru (yang statusnya Diterima) melebihi kuota
                if ($pendaftarDiterima >= $kuota) {
                     DB::rollBack();
                     return response()->json(['message' => 'Maaf, kuota kegiatan ini sudah penuh. Pendaftaran ditolak.'], 403);
                }
            }
            // Catatan: Jika status awal 'Mengajukan', dia tidak akan mengurangi kuota yang sudah 'Diterima' saat ini, 
            // sehingga pengecekan ini dilewati.

            
            // Simpan data pendaftaran
            $pendaftaran = Pendaftaran::create([
                'user_id' => $userId,
                'kegiatan_id' => $kegiatanId,
                'status' => $statusAwal, // 🔥 Status Diterima jika Otomatis
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
            
        } catch (Throwable $e) {
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
            // Mengembalikan status 404 jika kegiatan tidak ditemukan
            return response()->json(['status_pendaftaran' => 'Kegiatan Tidak Ditemukan'], 404); 
        }

        // 1. Cari data pendaftaran user saat ini untuk kegiatan ini
        $pendaftaran = Pendaftaran::where('user_id', $userId)
            ->where('kegiatan_id', $kegiatanId)
            ->first();

        // 2. 🔥 LOGIC: Jika pendaftaran DITEMUKAN, langsung kembalikan statusnya
        if ($pendaftaran) {
            return response()->json([
                'status_pendaftaran' => $pendaftaran->status // Mengajukan, Diterima, Ditolak
            ], 200);
        }

        // 3. 🔥 LOGIC: Jika pendaftaran TIDAK DITEMUKAN, baru cek Kuota Penuh
        $kuota = $kegiatan->kuota;
        if ($kuota > 0) {
            $pendaftarDiterima = Pendaftaran::where('kegiatan_id', $kegiatanId)
                ->where('status', 'Diterima')
                ->count(); 
                
            if ($pendaftarDiterima >= $kuota) {
                // Kuota penuh, dan user belum terdaftar -> Blokir dengan status Kuota Penuh
                return response()->json([
                    'status_pendaftaran' => 'Kuota Penuh'
                ], 200);
            }
        }
        
        // 4. Jika pendaftaran tidak ditemukan dan kuota belum penuh
        return response()->json([
            'status_pendaftaran' => 'Belum Mendaftar'
        ], 200);
    }
}