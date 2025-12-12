<?php

namespace App\Http\Controllers;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::with('kategori', 'user')->get(); 
        return response()->json($kegiatan);
    }
}
