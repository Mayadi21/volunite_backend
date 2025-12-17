<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organizer\StoreKegiatanRequest;
use App\Http\Requests\Organizer\UpdateKegiatanRequest;
use App\Models\Kegiatan;
use App\Models\Notifikasi;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ManageKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $kegiatan = Kegiatan::with('kategori')
            ->withCount(['pendaftaran' => function ($query) {
                $query->where('status', 'Diterima');
            }])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $kegiatan,
        ]);
    }

    public function store(StoreKegiatanRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $path = null;
            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('images/Kegiatan', 'public');
            }

            $kegiatan = Kegiatan::create([
                'user_id' => $request->user()->id,
                'judul' => $request->judul,
                'thumbnail' => $path,
                'deskripsi' => $request->deskripsi,
                'link_grup' => $request->link_grup,
                'lokasi' => $request->lokasi,
                'syarat_ketentuan' => $request->syarat_ketentuan,
                'kuota' => $request->kuota,
                'metode_penerimaan' => $request->metode_penerimaan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_berakhir' => $request->tanggal_berakhir,
                'status' => 'Waiting',
            ]);

            if ($request->has('kategori_ids')) {
                $kegiatan->kategori()->attach($request->kategori_ids);
            }

            return response()->json([
                'success' => true,
                'message' => 'Kegiatan berhasil dibuat',
                'data' => $kegiatan->load('kategori'),
            ], 201);
        });
    }

    public function show(Request $request, $id)
    {
        $kegiatan = Kegiatan::with(['kategori', 'pendaftaran'])
            ->withCount(['pendaftaran' => function ($query) {
                $query->where('status', 'Diterima');
            }])
            ->where('user_id', $request->user()->id)
            ->find($id);

        if (! $kegiatan) {
            return response()->json(['success' => false, 'message' => 'Not Found'], 404);
        }

        return response()->json(['success' => true, 'data' => $kegiatan]);
    }

    public function update(UpdateKegiatanRequest $request, $id)
    {
        $kegiatan = Kegiatan::where('user_id', $request->user()->id)->find($id);

        if (! $kegiatan) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if ($request->hasFile('thumbnail')) {
            if ($kegiatan->getRawOriginal('thumbnail')) {
                Storage::disk('public')->delete($kegiatan->getRawOriginal('thumbnail'));
            }
            $path = $request->file('thumbnail')->store('images/Kegiatan', 'public');
            $kegiatan->thumbnail = $path;
        }

        $kegiatan->update($request->except(['thumbnail', 'kategori_ids']));

        if ($request->has('kategori_ids')) {
            $kegiatan->kategori()->sync($request->kategori_ids);
        }

        return response()->json(['success' => true, 'data' => $kegiatan->load('kategori')]);
    }

    public function destroy(Request $request, $id)
    {
        $kegiatan = Kegiatan::where('user_id', $request->user()->id)->find($id);
        if (! $kegiatan) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if ($kegiatan->getRawOriginal('thumbnail')) {
            Storage::disk('public')->delete($kegiatan->getRawOriginal('thumbnail'));
        }
        $kegiatan->delete();

        return response()->json(['success' => true, 'message' => 'Deleted']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:finished,cancelled' 
        ]);

        $newStatus = $request->status;

        $kegiatan = Kegiatan::where('id', $id)
                            ->where('user_id', $request->user()->id)
                            ->first();

        if (!$kegiatan) {
            return response()->json(['success' => false, 'message' => 'Kegiatan tidak ditemukan'], 404);
        }

        if (in_array(strtolower($kegiatan->status), ['finished', 'cancelled', 'rejected'])) {
            return response()->json([
                'success' => false, 
                'message' => 'Status kegiatan sudah final dan tidak dapat diubah.'
            ], 400);
        }

        $kegiatan->status = $newStatus;
        $kegiatan->save();

        $msg = ($newStatus == 'finished') ? 'Kegiatan berhasil diselesaikan.' : 'Kegiatan berhasil dibatalkan.';

        return response()->json([
            'success' => true, 
            'message' => $msg,
            'data' => $kegiatan
        ]);
    }

    public function getPendaftar($kegiatanId)
    {
        $kegiatan = Kegiatan::where('user_id', request()->user()->id)->find($kegiatanId);

        if (! $kegiatan) {
            return response()->json(['message' => 'Kegiatan tidak ditemukan / Unauthorized'], 403);
        }

        $pendaftar = Pendaftaran::with(['user', 'detailPendaftaran'])
            ->where('kegiatan_id', $kegiatanId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pendaftar,
        ]);
    }


    public function updateKehadiran(Request $request, $pendaftaranId)
    {
        $request->validate([
            'status_kehadiran' => 'required|in:Hadir,Tidak Hadir,Belum Dicek',
        ]);

        $pendaftaran = Pendaftaran::with('kegiatan')->find($pendaftaranId);

        if (! $pendaftaran) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $pendaftaran->status_kehadiran = $request->status_kehadiran;

        if ($request->status_kehadiran == 'Hadir') {
            $pendaftaran->tanggal_kehadiran = now();
        } else {
            $pendaftaran->tanggal_kehadiran = null;
        }

        $pendaftaran->save();

        if ($request->status_kehadiran != 'Belum Dicek') {

            $judulKegiatan = $pendaftaran->kegiatan->judul;

            Notifikasi::create([
                'user_id' => $pendaftaran->user_id,
                'judul' => 'Laporan Kehadiran',
                'subjudul' => 'Status kehadiran Anda di "'.$judulKegiatan.'" telah diperbarui menjadi: '.$request->status_kehadiran,
                'read' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil diperbarui',
        ]);
    }

    public function updateStatusPendaftaran(Request $request, $pendaftaranId)
    {
        $request->validate([
            'status' => 'required|in:Diterima,Ditolak'
        ]);

        $pendaftaran = Pendaftaran::find($pendaftaranId);
        if (!$pendaftaran) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $kegiatan = Kegiatan::where('id', $pendaftaran->kegiatan_id)
                    ->where('user_id', $request->user()->id)
                    ->first();

        if (!$kegiatan) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $statusKegiatan = strtolower($kegiatan->status);
        if (in_array($statusKegiatan, ['finished', 'cancelled', 'rejected'])) {
            return response()->json([
                'success' => false, 
                'message' => 'Kegiatan sudah berakhir/batal. Tidak dapat mengubah status pelamar.'
            ], 400);
        }

        if ($request->status == 'Diterima') {
            
            $totalDiterima = Pendaftaran::where('kegiatan_id', $kegiatan->id)
                                        ->where('status', 'Diterima')
                                        ->count();

            if ($totalDiterima >= $kegiatan->kuota) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal menerima! Kuota kegiatan sudah penuh.'
                ], 400);
            }
        }

        $pendaftaran->status = $request->status;
        $pendaftaran->save();

        $judulNotif = ($request->status == 'Diterima') ? 'Selamat! Pendaftaran Diterima 🎉' : 'Update Status Pendaftaran';
        $pesanNotif = ($request->status == 'Diterima') 
            ? "Anda diterima di kegiatan \"{$kegiatan->judul}\". Silakan cek detail kegiatan." 
            : "Maaf, pendaftaran Anda untuk \"{$kegiatan->judul}\" belum dapat diterima.";

        if ($request->status == 'Diterima' && $kegiatan->link_grup) {
            $pesanNotif .= "\nLink Grup: " . $kegiatan->link_grup;
        }

        Notifikasi::create([
            'user_id'  => $pendaftaran->user_id,
            'judul'    => $judulNotif,
            'subjudul' => $pesanNotif,
            'read'     => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah menjadi ' . $request->status
        ]);
    }
}
