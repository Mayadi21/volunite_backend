<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Pendaftaran;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;

        $kegiatanQuery = Kegiatan::where('user_id', $userId);
        
        $totalKegiatan = (clone $kegiatanQuery)->count();
        $aktifKegiatan = (clone $kegiatanQuery)->whereIn('status', ['Waiting', 'scheduled', 'on progress'])->count();
        $selesaiKegiatan = (clone $kegiatanQuery)->where('status', 'finished')->count();

        $totalRelawan = Pendaftaran::where('status', 'Diterima')
                        ->whereHas('kegiatan', function($q) use ($userId) {
                            $q->where('user_id', $userId);
                        })->count();

        $totalHadir = Pendaftaran::where('status_kehadiran', 'Hadir')
                        ->whereHas('kegiatan', function($q) use ($userId) {
                            $q->where('user_id', $userId);
                        })->count();

        $attendanceRate = 0;
        if ($totalRelawan > 0) {
            $attendanceRate = round(($totalHadir / $totalRelawan) * 100);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'path_profil' => $user->path_profil,
                ],
                'stats' => [
                    'total_relawan' => $totalRelawan,
                    'attendance_rate' => $attendanceRate, 
                    'kegiatan_total' => $totalKegiatan,
                    'kegiatan_aktif' => $aktifKegiatan,
                    'kegiatan_selesai' => $selesaiKegiatan,
                ]
            ]
        ]);
    }
}