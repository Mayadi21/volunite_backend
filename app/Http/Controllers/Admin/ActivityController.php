<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

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

public function index(Request $request)
{
    $query = Kegiatan::with('user'); // <- selalu eager load user

    if ($status = $request->query('status')) {
        $query->where('status', $status);
    }

    $perPage = intval($request->query('per_page', 15));
    $paginator = $query->orderBy('created_at', 'desc')->paginate($perPage);

    // Serialisasi model ke array agar relasi user muncul
    $items = array_map(function($item) {
        // toArray() memastikan relasi yang dimuat ikut ter-serialize
        return $item->toArray();
    }, $paginator->items());

    return response()->json([
        'data' => $items,
        'meta' => [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
        ],
        'links' => [
            'first' => $paginator->url(1),
            'last' => $paginator->url($paginator->lastPage()),
            'prev' => $paginator->previousPageUrl(),
            'next' => $paginator->nextPageUrl(),
        ],
    ], 200);
}


    /**
     * GET /kegiatan/{kegiatan}
     * Return single kegiatan (with user relation if loaded)
     */
    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load('user');
        return response()->json(['data' => $kegiatan], 200);
    }

    /**
     * PUT /kegiatan/{kegiatan}/status
     * Hanya admin boleh mengubah status.
     * Request body: { "status": "Waiting" | "Rejected" | "scheduled" | "on progress" | "finished" | "cancelled" }
     */
    public function updateStatus(Request $request, Kegiatan $kegiatan)
    {
        $user = $request->user();

        // Ganti pengecekan role sesuai implementasi Anda.
        // Jika Anda punya method hasRole, gunakan itu. Jika tidak, ganti sesuai kolom role di users.
        $isAdmin = false;
        if (method_exists($user, 'hasRole')) {
            $isAdmin = $user->hasRole('Admin');
        } elseif (isset($user->role)) {
            // contoh: $user->role == 'Admin'
            $isAdmin = strtolower($user->role) === 'admin';
        }

        if (!$isAdmin) {
            return response()->json(['message' => 'Unauthorized. Hanya admin yang boleh mengubah status.'], 403);
        }

        // Validasi inline (tanpa FormRequest)
        $allowed = ['Waiting', 'Rejected', 'scheduled', 'on progress', 'finished', 'cancelled'];
        $validated = $request->validate([
            'status' => ['required', Rule::in($allowed)],
        ]);

        $newStatus = $validated['status'];

        // Optional: tambahkan aturan transisi status jika diperlukan
        // Contoh sederhana langsung set:
        $kegiatan->status = $newStatus;
        $kegiatan->save();

        // Kembalikan object terbaru (sertakan relasi user)
        $kegiatan->load('user');

        return response()->json(['data' => $kegiatan], 200);
    }
}
