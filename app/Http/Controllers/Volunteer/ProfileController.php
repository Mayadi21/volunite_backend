<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Pencapaian;
use App\Models\Notifikasi;
use App\Models\User;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $hadirCountGlobal = Pendaftaran::where('user_id', $user->id)
                        ->where('status_kehadiran', 'Hadir')
                        ->count();
        
        $totalXP = $hadirCountGlobal * 1000;

        $levelCap = 5000; 
        $currentLevelXP = $totalXP % $levelCap;
        $nextLevelTarget = $levelCap;

        $userAchievementIds = $user->pencapaian()->pluck('pencapaian.id')->toArray();
        $lockedAchievements = Pencapaian::whereNotIn('id', $userAchievementIds)->get();

        foreach ($lockedAchievements as $ach) {
            $syaratXPTerpenuhi = true;
            $syaratCountTerpenuhi = true;

            if (!is_null($ach->required_exp)) {
                $syaratXPTerpenuhi = ($totalXP >= $ach->required_exp);
            }

            if (!is_null($ach->required_count_kategori)) {
                $currentCount = 0;
                if ($ach->required_kategori) {
                    $currentCount = Pendaftaran::where('user_id', $user->id)
                        ->where('status_kehadiran', 'Hadir')
                        ->whereHas('kegiatan', function($query) use ($ach) {
                            $query->whereHas('kategori', function($q) use ($ach) {
                                $q->where('kategori.id', $ach->required_kategori);
                            });
                        })->count();
                } else {
                    $currentCount = $hadirCountGlobal;
                }
                $syaratCountTerpenuhi = ($currentCount >= $ach->required_count_kategori);
            }

            if ($syaratXPTerpenuhi && $syaratCountTerpenuhi) {
                $user->pencapaian()->attach($ach->id);
                Notifikasi::create([
                    'user_id' => $user->id,
                    'judul' => 'Pencapaian Baru Diraih! 🏆',
                    'subjudul' => "Selamat! Anda membuka pencapaian: " . $ach->nama,
                    'read' => false
                ]);
                $userAchievementIds[] = $ach->id;
            }
        }

        $allAchievements = Pencapaian::all();
        $achievements = $allAchievements->map(function($item) use ($userAchievementIds) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'deskripsi' => $item->deskripsi,
                'thumbnail' => $item->thumbnail,
                'is_unlocked' => in_array($item->id, $userAchievementIds),
            ];
        })->sortByDesc('is_unlocked')->values();

        $usersAboveMe = User::where('role', 'volunteer')
            ->withCount(['pendaftaran as jumlah_hadir' => function ($q) {
                $q->where('status_kehadiran', 'Hadir');
            }])
            ->having('jumlah_hadir', '>', $hadirCountGlobal)
            ->count();
        
        $globalRank = $usersAboveMe + 1;

        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $user->nama,
                'path_profil' => $user->path_profil,
                'email' => $user->email,
                
                'total_xp' => $totalXP,
                'current_level_xp' => $currentLevelXP,
                'next_level_target' => $nextLevelTarget,
                'activity_count' => $hadirCountGlobal,
                
                'global_rank' => $globalRank,
                'achievements' => $achievements,
            ]
        ]);
    }
}