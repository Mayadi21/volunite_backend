<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function recent(Request $request)
    {
        $limit = intval($request->query('limit', 3));

        // Ambil kegiatan terbaru yang dibuat user dengan role 'Organizer'
        $activities = Kegiatan::whereHas('user', function ($q) {
            $q->where('role', 'Organizer');
        })
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get(['id', 'judul', 'created_at', 'status']);

        // Pastikan Carbon locale bila ingin 'diffForHumans' dalam bahasa Indonesia
        Carbon::setLocale('id');

        $payload = $activities->map(function ($a) {
            return [
                'id' => $a->id,
                'user_id' => $a->user_id,
                'judul' => $a->judul,
                'thumbnail' => $a->thumbnail ? url('storage/' . $a->thumbnail) : null,
                'deskripsi' => $a->deskripsi,
                'link_grup' => $a->link_grup,
                'lokasi' => $a->lokasi,
                'syarat_ketentuan' => $a->syarat_ketentuan,
                'kuota' => $a->kuota,
                'tanggal_mulai' => optional($a->tanggal_mulai)->toDateTimeString(),
                'tanggal_berakhir' => optional($a->tanggal_berakhir)->toDateTimeString(),
                'status' => $a->status,
                'created_at' => $a->created_at->toDateTimeString(),
                'creator_name' => $a->creator->name ?? '',
            ];
        });


        return response()->json($payload);
    }
}
