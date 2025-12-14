<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Pencapaian;
use App\Models\Notifikasi;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // 1. DATA DASAR: XP GLOBAL & JUMLAH KEGIATAN GLOBAL
        $hadirCountGlobal = Pendaftaran::where('user_id', $user->id)
                        ->where('status_kehadiran', 'Hadir')
                        ->count();
        
        $totalXP = $hadirCountGlobal * 1000;

        // Data Leveling (Untuk UI)
        $levelCap = 5000; 
        $currentLevelXP = $totalXP % $levelCap;
        $nextLevelTarget = $levelCap;

        // 2. LOGIKA UNLOCK ACHIEVEMENT (DIPERBAIKI: Logic AND)
        $userAchievementIds = $user->pencapaian()->pluck('pencapaian.id')->toArray();
        $lockedAchievements = Pencapaian::whereNotIn('id', $userAchievementIds)->get();

        foreach ($lockedAchievements as $ach) {
            
            // Default TRUE. Jika kolom di database NULL, dianggap tidak ada syarat (Lolos).
            $syaratXPTerpenuhi = true;
            $syaratCountTerpenuhi = true;

            // --- CEK SYARAT 1: XP ---
            // Hanya cek jika kolom required_exp TIDAK NULL
            if (!is_null($ach->required_exp)) {
                $syaratXPTerpenuhi = ($totalXP >= $ach->required_exp);
            }

            // --- CEK SYARAT 2: JUMLAH KEGIATAN ---
            // Hanya cek jika kolom required_count_kategori TIDAK NULL
            if (!is_null($ach->required_count_kategori)) {
                
                $currentCount = 0;

                if ($ach->required_kategori) {
                    // A. Jika butuh Kategori Spesifik (misal: 5 Kegiatan Lingkungan)
                    $currentCount = Pendaftaran::where('user_id', $user->id)
                        ->where('status_kehadiran', 'Hadir')
                        ->whereHas('kegiatan', function($query) use ($ach) {
                            $query->whereHas('kategori', function($q) use ($ach) {
                                $q->where('kategori.id', $ach->required_kategori);
                            });
                        })
                        ->count();
                } else {
                    // B. Jika Kategori NULL (Kegiatan Umum/Apapun)
                    $currentCount = $hadirCountGlobal;
                }

                $syaratCountTerpenuhi = ($currentCount >= $ach->required_count_kategori);
            }

            // --- EKSEKUSI UNLOCK ---
            // Achievement diberikan HANYA JIKA kedua validasi di atas bernilai TRUE
            if ($syaratXPTerpenuhi && $syaratCountTerpenuhi) {
                
                $user->pencapaian()->attach($ach->id);

                Notifikasi::create([
                    'user_id' => $user->id,
                    'judul' => 'Pencapaian Baru Diraih! 🏆',
                    'subjudul' => "Selamat! Anda membuka pencapaian: " . $ach->nama,
                    'read' => false
                ]);

                // Update array lokal agar langsung muncul di response JSON
                $userAchievementIds[] = $ach->id;
            }
        }

        // 3. RESPONSE DATA
        $allAchievements = Pencapaian::all();
        $achievements = $allAchievements->map(function($item) use ($userAchievementIds) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'deskripsi' => $item->deskripsi,
                'thumbnail' => $item->thumbnail,
                'is_unlocked' => in_array($item->id, $userAchievementIds),
            ];
        });

        $sortedAchievements = $achievements->sortByDesc('is_unlocked')->values();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'nama' => $user->nama,
                    'path_profil' => $user->path_profil,
                    'email' => $user->email,
                ],
                'xp_info' => [
                    'total_xp' => $totalXP,
                    'current_level_xp' => $currentLevelXP,
                    'next_level_target' => $nextLevelTarget,
                    'activity_count' => $hadirCountGlobal,
                ],
                'achievements' => $sortedAchievements
            ]
        ]);
    }
}