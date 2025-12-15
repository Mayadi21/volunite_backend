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
            'status' => 'required|in:Diterima,Ditolak',
        ]);

        $pendaftaran = Pendaftaran::find($pendaftaranId);
        if (! $pendaftaran) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $kegiatan = Kegiatan::where('id', $pendaftaran->kegiatan_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $kegiatan) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($request->status == 'Diterima') {
            $acceptedCount = Pendaftaran::where('kegiatan_id', $kegiatan->id)
                ->where('status', 'Diterima')->count();
            if ($acceptedCount >= $kegiatan->kuota) {
                return response()->json(['message' => 'Kuota sudah penuh!'], 400);
            }
        }

        $pendaftaran->status = $request->status;
        $pendaftaran->save();

        $judulNotif = '';
        $pesanNotif = '';


        if ($request->status == 'Diterima') {
            $judulNotif = 'Selamat! Pendaftaran Diterima 🎉';

            
            $link_WA = $kegiatan->link_grup;
            $pesanNotif = "Anda diterima di kegiatan \"{$kegiatan->judul}\".\n" .
                      "Silakan segera bergabung ke Grup WhatsApp untuk koordinasi:\n$link_WA";
        } else {
            $judulNotif = 'Update Status Pendaftaran';
            $pesanNotif = 'Maaf, pendaftaran Anda untuk "'.$kegiatan->judul.'" belum dapat diterima.';
        }

        Notifikasi::create([
            'user_id' => $pendaftaran->user_id,
            'judul' => $judulNotif,
            'subjudul' => $pesanNotif,
            'read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah menjadi '.$request->status,
        ]);
    }
}
