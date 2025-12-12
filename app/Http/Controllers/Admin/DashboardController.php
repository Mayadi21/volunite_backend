<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kegiatan;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        // Total volunteer: misal role 'volunteer'
        $totalVolunteer = User::where('role', 'volunteer')->count();

        // Organisasi: misal role 'Organizer' (sesuaikan bila beda)
        $organisasi = User::where('role', 'Organizer')->count();

        // Event aktif: kita anggap status 'scheduled' atau 'on progress' berarti aktif
        $eventAktif = Kegiatan::whereIn('status', ['scheduled', 'on progress'])->count();

        // Event menunggu persetujuan: status 'Waiting'
        $eventMenunggu = Kegiatan::where('status', 'Waiting')->count();

        return response()->json([
            'total_volunteer' => $totalVolunteer,
            'organisasi' => $organisasi,
            'event_aktif' => $eventAktif,
            'event_menunggu_persetujuan' => $eventMenunggu,
        ]);
    }
}
