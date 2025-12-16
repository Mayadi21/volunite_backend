<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class LeaderboardController extends Controller
{
    public function index()
    {
        $leaders = User::where('role', 'volunteer')
            ->withCount(['pendaftaran as jumlah_hadir' => function ($query) {
                $query->where('status_kehadiran', 'Hadir');
            }])
            ->orderByDesc('jumlah_hadir') 
            ->take(20)
            ->get();

        $formattedLeaders = $leaders->map(function ($user) {
            return [
                'id' => $user->id,
                'nama' => $user->nama,
                'path_profil' => $user->path_profil,
                'total_xp' => $user->jumlah_hadir * 1000, 
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedLeaders
        ]);
    }
}