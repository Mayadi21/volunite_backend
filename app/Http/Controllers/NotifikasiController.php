<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $notifikasi = Notifikasi::where('user_id', $request->user()->id)
                        ->orderBy('created_at', 'desc') 
                        ->get();

        return response()->json([
            'success' => true,
            'data'    => $notifikasi
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notif = Notifikasi::where('user_id', $request->user()->id)->find($id);

        if ($notif) {
            $notif->update(['read' => true]);
            return response()->json(['success' => true, 'message' => 'Marked as read']);
        }

        return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan'], 404);
    }
}