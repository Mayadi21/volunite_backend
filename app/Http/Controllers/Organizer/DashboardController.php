<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Pendaftaran;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $stats = [
            'aktif'    => Kegiatan::where('user_id', $userId)->whereIn('status', ['scheduled', 'on progress'])->count(),
            'waiting'  => Kegiatan::where('user_id', $userId)->where('status', 'Waiting')->count(),
            'selesai'  => Kegiatan::where('user_id', $userId)->where('status', 'finished')->count(),
            'pendaftar'=> Pendaftaran::whereHas('kegiatan', function($q) use ($userId) {
                            $q->where('user_id', $userId);
                          })->count(),
        ];

        $kegiatan = Kegiatan::where('user_id', $userId)
                    ->whereIn('status', ['scheduled', 'on progress', 'Waiting'])
                    ->withCount('pendaftaran')
                    ->latest('tanggal_mulai')
                    ->take(5)
                    ->get();

        $pelamar = Pendaftaran::with(['user', 'kegiatan'])
                    ->where('status', 'Mengajukan')
                    ->whereHas('kegiatan', function($q) use ($userId) {
                        $q->where('user_id', $userId);
                    })
                    ->latest()
                    ->take(5)
                    ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $request->user(), 
                'stats' => $stats,
                'kegiatan' => $kegiatan,
                'pelamar_pending' => $pelamar
            ]
        ]);
    }
}