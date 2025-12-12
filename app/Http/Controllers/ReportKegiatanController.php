<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\ReportKegiatan;
use Illuminate\Http\Request;

class ReportKegiatanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'keluhan' => 'required|string',
            'detail_keluhan' => 'required|string',
        ]);

        $report = ReportKegiatan::create([
            'kegiatan_id' => $request->kegiatan_id,
            'user_id'     => $request->user()->id,
            'keluhan'     => $request->keluhan,
            'detail_keluhan' => $request->detail_keluhan,
            'status' => 'Diproses',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim',
            'data' => $report
        ], 201);
    }
}
