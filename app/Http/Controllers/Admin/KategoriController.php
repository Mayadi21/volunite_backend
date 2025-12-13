<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KategoriController extends Controller
{
    // GET /admin/kategori
    public function index()
    {
        $kategori = \App\Models\Kategori::orderBy('nama_kategori')->get()->map(function ($k) {
            return [
                'id' => $k->id,
                'nama_kategori' => $k->nama_kategori,
                'deskripsi' => $k->deskripsi,
                'thumbnail' => $k->thumbnail
                    ? asset('storage/' . $k->thumbnail)
                    : null,
            ];
        });

        return response()->json([
            'data' => $kategori
        ]);
    }



    // POST /admin/kategori
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')
                ->store('images/kategori', 'public');
            $validated['thumbnail'] = $path;
        }

        $kategori = Kategori::create($validated);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori
        ], 201);
    }




    // PUT /admin/kategori/{id}
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($kategori->thumbnail && Storage::disk('public')->exists($kategori->thumbnail)) {
                Storage::disk('public')->delete($kategori->thumbnail);
            }

            $path = $request->file('thumbnail')
                ->store('images/kategori', 'public');

            $validated['thumbnail'] = $path;
        }


        $kategori->update($validated);

        return response()->json([
            'message' => 'Kategori berhasil diperbarui',
            'data' => $kategori
        ]);
    }



    // DELETE /admin/kategori/{id}
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}
