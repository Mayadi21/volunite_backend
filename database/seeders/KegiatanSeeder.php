<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil User Organizer (Untuk mengisi kolom user_id)
        // Kita ambil user pertama yg role-nya Organizer (misal: paskal)
        $organizer = User::where('role', 'Organizer')->first();

        // Cek dulu biar tidak error kalau seeder user belum jalan
        if (!$organizer) {
            $this->command->error('Error: Tidak ada user dengan role Organizer. Jalankan UserSeeder terlebih dahulu!');
            return;
        }
        
        $now = Carbon::now();

        $kegiatans = [
            // --- 4 KEGIATAN SCHEDULED ---
            [
                'data' => [
                    'user_id' => $organizer->id, // Sudah aktif
                    'judul' => 'Penanaman 1000 Pohon Bakau',
                    'thumbnail' => 'images/Kegiatan/post1.png',
                    'deskripsi' => 'Aksi nyata menyelamatkan pesisir dari abrasi dengan menanam bakau.',
                    'lokasi' => 'Pesisir Pantai Indah Kapuk',
                    'syarat_ketentuan' => 'Membawa topi dan lotion anti nyamuk. Peralatan disediakan.',
                    'kuota' => 100,
                    'tanggal_mulai' => $now->copy()->addDays(30)->setHour(8),
                    'tanggal_berakhir' => $now->copy()->addDays(30)->setHour(12),
                    'status' => 'scheduled',
                ],
                'kategori_ids' => [1, 7], // Lingkungan, Hewan
            ],
            [
                'data' => [
                    'user_id' => $organizer->id,
                    'judul' => 'Festival Literasi Anak',
                    'thumbnail' => 'images/Kegiatan/post2.png',
                    'deskripsi' => 'Membacakan dongeng dan membagikan buku gratis untuk anak panti asuhan.',
                    'lokasi' => 'Panti Asuhan Kasih Bunda',
                    'syarat_ketentuan' => 'Menyukai anak-anak dan bisa bercerita.',
                    'kuota' => 20,
                    'tanggal_mulai' => $now->copy()->addDays(25)->setHour(14),
                    'tanggal_berakhir' => $now->copy()->addDays(25)->setHour(17),
                    'status' => 'scheduled',
                ],
                'kategori_ids' => [2, 4], // Pendidikan, Sosial
            ],
            [
                'data' => [
                    'user_id' => $organizer->id,
                    'judul' => 'Senam Sehat Lansia',
                    'thumbnail' => 'images/Kegiatan/post3.png',
                    'deskripsi' => 'Menjadi instruktur pendamping untuk senam pagi bersama para lansia.',
                    'lokasi' => 'Taman Kota Tebet',
                    'syarat_ketentuan' => 'Berpakaian olahraga sopan.',
                    'kuota' => 15,
                    'tanggal_mulai' => $now->copy()->addDays(22)->setHour(6),
                    'tanggal_berakhir' => $now->copy()->addDays(22)->setHour(9),
                    'status' => 'scheduled',
                ],
                'kategori_ids' => [3, 6], // Kesehatan, Olahraga
            ],
            [
                'data' => [
                    'user_id' => $organizer->id,
                    'judul' => 'Workshop Melukis Tote Bag',
                    'thumbnail' => 'images/Kegiatan/post4.jpg',
                    'deskripsi' => 'Mengajarkan seni lukis kain kepada remaja putus sekolah.',
                    'lokasi' => 'Balai Warga RW 05',
                    'syarat_ketentuan' => 'Membawa kuas sendiri (cat disediakan).',
                    'kuota' => 10,
                    'tanggal_mulai' => $now->copy()->addDays(24)->setHour(10),
                    'tanggal_berakhir' => $now->copy()->addDays(24)->setHour(15),
                    'status' => 'scheduled',
                ],
                'kategori_ids' => [5, 2], // Seni, Pendidikan
            ],

            // --- 1 KEGIATAN REJECTED ---
            [
                'data' => [
                    'user_id' => $organizer->id,
                    'judul' => 'Balap Motor Amal',
                    'thumbnail' => 'images/Kegiatan/post1.png',
                    'deskripsi' => 'Penggalangan dana melalui aksi balap motor jalanan.',
                    'lokasi' => 'Jalan Protokol Malam Hari',
                    'syarat_ketentuan' => 'Punya motor modifikasi.',
                    'kuota' => 50,
                    'tanggal_mulai' => $now->copy()->addDays(30),
                    'tanggal_berakhir' => $now->copy()->addDays(30)->addHours(4),
                    'status' => 'Rejected',
                ],
                'kategori_ids' => [6], 
            ],

            // --- 1 KEGIATAN WAITING ---
            [
                'data' => [
                    'user_id' => $organizer->id,
                    'judul' => 'Seminar Zero Waste',
                    'thumbnail' => 'images/Kegiatan/post2.png',
                    'deskripsi' => 'Seminar online tentang cara mengurangi sampah rumah tangga.',
                    'lokasi' => 'Zoom Meeting',
                    'syarat_ketentuan' => 'Memiliki koneksi internet stabil.',
                    'kuota' => 200,
                    'tanggal_mulai' => $now->copy()->addDays(30),
                    'tanggal_berakhir' => $now->copy()->addDays(30)->addHours(2),
                    'status' => 'Waiting',
                ],
                'kategori_ids' => [1],
            ],

            // --- 1 KEGIATAN FINISHED ---
            [
                'data' => [
                    'user_id' => $organizer->id,
                    'judul' => 'Donor Darah Akbar',
                    'thumbnail' => 'images/Kegiatan/post3.png',
                    'deskripsi' => 'Kegiatan rutin donor darah bekerjasama dengan PMI.',
                    'lokasi' => 'Mall Grand Indonesia',
                    'syarat_ketentuan' => 'Sehat jasmani dan rohani.',
                    'kuota' => 150,
                    'tanggal_mulai' => $now->copy()->subDays(7)->setHour(10),
                    'tanggal_berakhir' => $now->copy()->subDays(7)->setHour(16),
                    'status' => 'finished',
                ],
                'kategori_ids' => [3, 4],
            ],

            // --- 1 KEGIATAN CANCELLED ---
            [
                'data' => [
                    'user_id' => $organizer->id,
                    'judul' => 'Hiking Gunung Gede',
                    'thumbnail' => 'images/Kegiatan/post4.jpg',
                    'deskripsi' => 'Pendakian santai sambil membersihkan jalur pendakian.',
                    'lokasi' => 'Taman Nasional Gede Pangrango',
                    'syarat_ketentuan' => 'Fisik prima.',
                    'kuota' => 15,
                    'tanggal_mulai' => $now->copy()->addDays(23),
                    'tanggal_berakhir' => $now->copy()->addDays(24),
                    'status' => 'cancelled',
                ],
                'kategori_ids' => [1, 6],
            ],
        ];

        foreach ($kegiatans as $item) {
            $kegiatan = Kegiatan::create($item['data']);
            $kegiatan->kategori()->attach($item['kategori_ids']);
        }
    }
}