<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pendaftaran;
use App\Models\DetailPendaftaran;
use App\Models\Kegiatan;
use App\Models\User;
use Carbon\Carbon;

class PendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        // ===============================
        // KONFIGURASI
        // ===============================
        $specialVolunteerIds = [2, 3]; // mayadi & alfi
        $maxJoinPerUser = 3; // batas supaya tidak ada user daftar ke semua kegiatan

        // Semua volunteer
        $volunteers = User::where('role', 'Volunteer')->pluck('id')->toArray();

        // Kegiatan yang bisa didaftari
        $kegiatans = Kegiatan::whereIn('status', ['scheduled', 'finished'])->get();

        if ($kegiatans->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada kegiatan scheduled / finished.');
            return;
        }

        // ===============================
        // 1️⃣ KHUSUS: MAYADI & ALFI
        // ===============================
        $finishedKegiatans = $kegiatans->where('status', 'finished');

        foreach ($finishedKegiatans as $kegiatan) {
            foreach ($specialVolunteerIds as $volunteerId) {

                $pendaftaran = Pendaftaran::create([
                    'user_id' => $volunteerId,
                    'kegiatan_id' => $kegiatan->id,
                    'status' => 'Diterima',
                    'status_kehadiran' => 'Hadir',
                    'tanggal_kehadiran' => Carbon::parse($kegiatan->tanggal_mulai),
                ]);

                DetailPendaftaran::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'nomor_telepon' => '081234567890',
                    'domisili' => 'Medan',
                    'komitmen' => 'Berkomitmen penuh mengikuti kegiatan.',
                    'keterampilan' => 'Kerja tim, disiplin, komunikasi.',
                ]);
            }
        }

        // ===============================
        // 2️⃣ VOLUNTEER LAIN (ACAK & TERBATAS)
        // ===============================
        foreach ($volunteers as $volunteerId) {

            if (in_array($volunteerId, $specialVolunteerIds)) {
                continue;
            }

            $joinCount = rand(1, min($maxJoinPerUser, $kegiatans->count()));
            $randomKegiatans = $kegiatans->random($joinCount);

            foreach ($randomKegiatans as $kegiatan) {

                if (Pendaftaran::where('user_id', $volunteerId)
                    ->where('kegiatan_id', $kegiatan->id)
                    ->exists()
                ) {
                    continue;
                }

                // ===============================
                // LOGIC BARU DI SINI 👇
                // ===============================
                $status = 'Mengajukan';
                $statusKehadiran = 'Belum Dicek';
                $tanggalKehadiran = null;

                if ($kegiatan->status === 'finished') {
                    // 40% volunteer diterima & hadir
                    if (rand(1, 100) <= 40) {
                        $status = 'Diterima';
                        $statusKehadiran = 'Hadir';
                        $tanggalKehadiran = Carbon::parse($kegiatan->tanggal_mulai);
                    }
                }

                $pendaftaran = Pendaftaran::create([
                    'user_id' => $volunteerId,
                    'kegiatan_id' => $kegiatan->id,
                    'status' => $status,
                    'status_kehadiran' => $statusKehadiran,
                    'tanggal_kehadiran' => $tanggalKehadiran,
                ]);

                DetailPendaftaran::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'nomor_telepon' => '08' . rand(1000000000, 9999999999),
                    'domisili' => 'Medan',
                    'komitmen' => 'Siap mengikuti aturan kegiatan.',
                    'keterampilan' => 'Tanggung jawab dan kerja tim.',
                ]);
            }
        }


        $this->command->info('✅ PendaftaranSeeder berhasil dijalankan dengan aturan final.');
    }
}
