<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua organizer
        $organizers = User::where('role', 'Organizer')->pluck('id')->toArray();

        if (count($organizers) === 0) {
            $this->command->error('❌ Tidak ada user dengan role Organizer. Jalankan UserSeeder dulu.');
            return;
        }

        // Helper untuk ambil organizer secara acak
        $getOrganizer = function () use ($organizers) {
            return $organizers[array_rand($organizers)];
        };

        $now = Carbon::now();

        $kegiatans = [

            // =========================
            // ====== SCHEDULED ========
            // =========================
            [
                'data' => [
                    'user_id' => $getOrganizer(),
                    'judul' => 'Penanaman 1000 Pohon Bakau',
                    'thumbnail' => 'images/Kegiatan/post1.png',
                    'deskripsi' => 'Aksi nyata menyelamatkan pesisir dari abrasi dengan menanam bakau.',
                    'lokasi' => 'Pesisir Pantai Indah Kapuk',
                    'syarat_ketentuan' => 'Membawa topi dan lotion anti nyamuk.',
                    'kuota' => 100,
                    'tanggal_mulai' => $now->copy()->addDays(30)->setHour(8),
                    'tanggal_berakhir' => $now->copy()->addDays(30)->setHour(12),
                    'status' => 'scheduled',
                ],
                'kategori_ids' => [1, 7],
            ],
            [
                'data' => [
                    'user_id' => $getOrganizer(),
                    'judul' => 'Festival Literasi Anak',
                    'thumbnail' => 'images/Kegiatan/post2.png',
                    'deskripsi' => 'Membacakan dongeng dan membagikan buku gratis.',
                    'lokasi' => 'Panti Asuhan Kasih Bunda',
                    'syarat_ketentuan' => 'Menyukai anak-anak.',
                    'kuota' => 20,
                    'tanggal_mulai' => $now->copy()->addDays(25)->setHour(14),
                    'tanggal_berakhir' => $now->copy()->addDays(25)->setHour(17),
                    'status' => 'scheduled',
                ],
                'kategori_ids' => [2, 4],
            ],

            // =========================
            // ====== WAITING ==========
            // =========================
            [
                'data' => [
                    'user_id' => $getOrganizer(),
                    'judul' => 'Seminar Zero Waste',
                    'thumbnail' => 'images/Kegiatan/post3.png',
                    'deskripsi' => 'Seminar online pengelolaan sampah rumah tangga.',
                    'lokasi' => 'Zoom Meeting',
                    'syarat_ketentuan' => 'Koneksi internet stabil.',
                    'kuota' => 200,
                    'tanggal_mulai' => $now->copy()->addDays(15),
                    'tanggal_berakhir' => $now->copy()->addDays(15)->addHours(2),
                    'status' => 'waiting',
                ],
                'kategori_ids' => [1],
            ],

            // =========================
            // ====== REJECTED =========
            // =========================
            [
                'data' => [
                    'user_id' => $getOrganizer(),
                    'judul' => 'Balap Motor Amal',
                    'thumbnail' => 'images/Kegiatan/post4.jpg',
                    'deskripsi' => 'Penggalangan dana melalui balap motor jalanan.',
                    'lokasi' => 'Jalan Protokol',
                    'syarat_ketentuan' => 'Motor modifikasi.',
                    'kuota' => 50,
                    'tanggal_mulai' => $now->copy()->addDays(10),
                    'tanggal_berakhir' => $now->copy()->addDays(10)->addHours(4),
                    'status' => 'rejected',
                ],
                'kategori_ids' => [6],
            ],

            // =========================
            // ====== CANCELLED ========
            // =========================
            [
                'data' => [
                    'user_id' => $getOrganizer(),
                    'judul' => 'Hiking Gunung Gede',
                    'thumbnail' => 'images/Kegiatan/post1.png',
                    'deskripsi' => 'Pendakian santai sambil bersih jalur.',
                    'lokasi' => 'TNGP',
                    'syarat_ketentuan' => 'Fisik prima.',
                    'kuota' => 15,
                    'tanggal_mulai' => $now->copy()->addDays(7),
                    'tanggal_berakhir' => $now->copy()->addDays(8),
                    'status' => 'cancelled',
                ],
                'kategori_ids' => [1, 6],
            ],

            // =========================
            // ====== FINISHED =========
            // =========================
            [
                'data' => [
                    'user_id' => $getOrganizer(),
                    'judul' => 'Bakti Sosial Ramadhan',
                    'thumbnail' => 'images/Kegiatan/post2.png',
                    'deskripsi' => 'Pembagian sembako untuk warga kurang mampu.',
                    'lokasi' => 'Kelurahan Tembung',
                    'syarat_ketentuan' => 'Berpakaian sopan.',
                    'kuota' => 50,
                    'tanggal_mulai' => $now->copy()->subDays(25)->setHour(9),
                    'tanggal_berakhir' => $now->copy()->subDays(25)->setHour(13),
                    'status' => 'finished',
                ],
                'kategori_ids' => [4],
            ],
            [
                'data' => [
                    'user_id' => $getOrganizer(),
                    'judul' => 'Kelas Coding Dasar',
                    'thumbnail' => 'images/Kegiatan/post3.png',
                    'deskripsi' => 'Pelatihan pemrograman dasar.',
                    'lokasi' => 'SMK Negeri 1 Medan',
                    'syarat_ketentuan' => 'Membawa laptop.',
                    'kuota' => 30,
                    'tanggal_mulai' => $now->copy()->subDays(20),
                    'tanggal_berakhir' => $now->copy()->subDays(20)->addHours(4),
                    'status' => 'finished',
                ],
                'kategori_ids' => [2],
            ],
            [
                'data' => [
                    'user_id' => $getOrganizer(),
                    'judul' => 'Aksi Bersih Sungai Deli',
                    'thumbnail' => 'images/Kegiatan/post4.jpg',
                    'deskripsi' => 'Membersihkan bantaran sungai.',
                    'lokasi' => 'Sungai Deli',
                    'syarat_ketentuan' => 'Siap kerja lapangan.',
                    'kuota' => 40,
                    'tanggal_mulai' => $now->copy()->subDays(18),
                    'tanggal_berakhir' => $now->copy()->subDays(18)->addHours(5),
                    'status' => 'finished',
                ],
                'kategori_ids' => [1],
            ],
            [
                'data' => [
                    'user_id' => $getOrganizer(),
                    'judul' => 'Pemeriksaan Kesehatan Gratis',
                    'thumbnail' => 'images/Kegiatan/post1.png',
                    'deskripsi' => 'Cek kesehatan gratis.',
                    'lokasi' => 'Puskesmas Medan Kota',
                    'syarat_ketentuan' => 'Membawa KTP.',
                    'kuota' => 100,
                    'tanggal_mulai' => $now->copy()->subDays(14),
                    'tanggal_berakhir' => $now->copy()->subDays(14)->addHours(6),
                    'status' => 'finished',
                ],
                'kategori_ids' => [3],
            ],
            [
                'data' => [
                    'user_id' => $getOrganizer(),
                    'judul' => 'Pelatihan Tari Tradisional',
                    'thumbnail' => 'images/Kegiatan/post2.png',
                    'deskripsi' => 'Pelestarian seni budaya lokal.',
                    'lokasi' => 'Sanggar Budaya Medan',
                    'syarat_ketentuan' => 'Komitmen dan disiplin.',
                    'kuota' => 25,
                    'tanggal_mulai' => $now->copy()->subDays(12),
                    'tanggal_berakhir' => $now->copy()->subDays(12)->addHours(4),
                    'status' => 'finished',
                ],
                'kategori_ids' => [5],
            ],
        ];

        // Simpan ke database
        foreach ($kegiatans as $item) {
            $kegiatan = Kegiatan::create($item['data']);
            $kegiatan->kategori()->attach($item['kategori_ids']);
        }

        $this->command->info('✅ KegiatanSeeder berhasil dijalankan.');
    }
}
