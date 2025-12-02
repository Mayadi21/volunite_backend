<?php

namespace App\Http\Controllers\Organizer; 

use App\Http\Controllers\Controller;
use App\Http\Requests\Organizer\StoreKegiatanRequest;
use App\Http\Requests\Organizer\UpdateKegiatanRequest;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ManageKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $kegiatan = Kegiatan::with('kategori')
                    ->where('user_id', $request->user()->id)
                    ->latest()
                    ->get();

        return response()->json([
            'success' => true,
            'data'    => $kegiatan
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
                'user_id'          => $request->user()->id,
                'judul'            => $request->judul,
                'thumbnail'        => $path,
                'deskripsi'        => $request->deskripsi,
                'lokasi'           => $request->lokasi,
                'syarat_ketentuan' => $request->syarat_ketentuan,
                'kuota'            => $request->kuota,
                'tanggal_mulai'    => $request->tanggal_mulai,
                'tanggal_berakhir' => $request->tanggal_berakhir,
                'status'           => 'Waiting',
            ]);

            $kegiatan->kategori()->attach($request->kategori_ids);

            return response()->json([
                'success' => true,
                'message' => 'Kegiatan berhasil dibuat',
                'data'    => $kegiatan->load('kategori')
            ], 201);
        });
    }

    public function show(Request $request, $id)
    {
        $kegiatan = Kegiatan::with(['kategori', 'pendaftaran'])
                    ->where('user_id', $request->user()->id)
                    ->find($id);

        if (!$kegiatan) {
            return response()->json(['success' => false, 'message' => 'Not Found'], 404);
        }

        return response()->json(['success' => true, 'data' => $kegiatan]);
    }
    public function update(UpdateKegiatanRequest $request, $id)
    {
        $kegiatan = Kegiatan::where('user_id', $request->user()->id)->find($id);

        if (!$kegiatan) {
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

        return response()->json([
            'success' => true,
            'message' => 'Kegiatan updated',
            'data'    => $kegiatan->load('kategori')
        ]);
    }
    public function destroy(Request $request, $id)
    {
        $kegiatan = Kegiatan::where('user_id', $request->user()->id)->find($id);

        if (!$kegiatan) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if ($kegiatan->getRawOriginal('thumbnail')) {
            Storage::disk('public')->delete($kegiatan->getRawOriginal('thumbnail'));
        }

        $kegiatan->delete();

        return response()->json(['success' => true, 'message' => 'Deleted']);
    }
}