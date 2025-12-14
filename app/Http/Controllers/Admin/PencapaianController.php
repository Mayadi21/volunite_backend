<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Pencapaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PencapaianController extends Controller
{
    // Helper function biar tidak nulis ulang logika URL berulang kali
    private function formatPencapaian($pencapaian)
    {
        // Kita inject property 'thumbnail_url' secara manual ke object ini
        $pencapaian->thumbnail_url = $pencapaian->thumbnail 
            ? asset('storage/' . $pencapaian->thumbnail) 
            : null;
        
        return $pencapaian;
    }

public function index()
    {
        // Ubah get() menjadi paginate(10) artinya ambil 10 data per request
        $paginator = Pencapaian::orderBy('created_at', 'desc')->paginate(5);

        // Transform collection di dalam paginator untuk inject URL gambar
        $paginator->getCollection()->transform(function ($item) {
            return $this->formatPencapaian($item);
        });

        return response()->json($paginator);
    }

    public function store(Request $request)
    {
        // ... (Validasi sama seperti sebelumnya) ...
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:50|unique:pencapaian',
            'deskripsi' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $path = null;
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('pencapaian', 'public');
        }

        $pencapaian = Pencapaian::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'thumbnail' => $path, // Simpan path saja di DB
            'required_kategori' => $request->required_kategori,
            'required_exp' => $request->required_exp,
        ]);

        // Format dulu sebelum dikirim balik ke Flutter
        return response()->json([
            'message' => 'Berhasil dibuat', 
            'data' => $this->formatPencapaian($pencapaian)
        ], 201);
    }

public function update(Request $request, $id)
    {
        $pencapaian = Pencapaian::find($id);
        if (!$pencapaian) return response()->json(['message' => 'Not found'], 404);

        $validator = Validator::make($request->all(), [
            // PERBAIKAN: Ignore ID saat ini agar tidak error "Nama sudah ada"
            'nama' => [
                'required', 
                'string', 
                'max:50', 
                Rule::unique('pencapaian')->ignore($pencapaian->id)
            ],
            'deskripsi' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048', 
        ]);

        if ($validator->fails()) {
            // Debugging: Kembalikan error validasi agar terlihat di log Flutter
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = $pencapaian->thumbnail;
        if ($request->hasFile('thumbnail')) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('thumbnail')->store('pencapaian', 'public');
        }

        $pencapaian->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'thumbnail' => $path,
            // Gunakan null coalescing operator (??) jika data tidak dikirim
            'required_exp' => $request->required_exp ?? $pencapaian->required_exp,
        ]);

        return response()->json([
            'message' => 'Berhasil diupdate', 
            // Pastikan formatPencapaian ada atau pakai manual seperti ini:
            'data' => $pencapaian 
        ]);
    }

    public function destroy($id)
    {
        $pencapaian = Pencapaian::find($id);
        if (!$pencapaian) return response()->json(['message' => 'Not found'], 404);

        // Hapus file gambar dulu agar bersih
        if ($pencapaian->thumbnail && Storage::disk('public')->exists($pencapaian->thumbnail)) {
            Storage::disk('public')->delete($pencapaian->thumbnail);
        }

        // PERHATIAN: Jika ada error SQL Integrity Constraint (karena tabel relasi),
        // Kita harus hapus relasinya dulu atau gunakan try-catch.
        try {
            $pencapaian->delete();
            return response()->json(['message' => 'Berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal hapus: Data sedang digunakan user', 'error' => $e->getMessage()], 500);
        }
    }

    // ... function destroy sama saja ...
}