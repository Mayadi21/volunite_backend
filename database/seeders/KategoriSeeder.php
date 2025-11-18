<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $Kategoris = [
            ['nama_kategori' => 'Lingkungan', 'deskripsi' => 'Aksi bersih-bersih, reforestasi, konservasi alam, dan edukasi pengelolaan sampah.', 'thumbnail' => 'images/kategori/lingkungan.png'],
            ['nama_kategori' => 'Pendidikan', 'deskripsi' => 'Mengajar, mentoring, membantu literasi, atau memberikan pelatihan keterampilan.', 'thumbnail' => 'images/kategori/pendidikan.png'],
            ['nama_kategori' => 'Kesehatan', 'deskripsi' => 'Bantuan medis dasar, pendampingan pasien, promosi hidup sehat, dan donor darah.', 'thumbnail' => 'images/kategori/kesehatan.png'],
            ['nama_kategori' => 'Sosial', 'deskripsi' => 'Bantuan kemanusiaan, pendampingan kelompok rentan (lansia, anak, disabilitas), dan fundraising.', 'thumbnail' => 'images/kategori/sosial.png'],
            ['nama_kategori' => 'Seni', 'deskripsi' => 'Membantu pagelaran seni/budaya, pelestarian warisan, atau mengajarkan keterampilan artistik.', 'thumbnail' => 'images/kategori/seni.png'],
            ['nama_kategori' => 'Olahraga', 'deskripsi' => 'Melatih tim komunitas, mengorganisir acara/turnamen olahraga, dan promosi aktivitas fisik.', 'thumbnail' => 'images/kategori/olahraga.png'],
            ['nama_kategori' => 'Hewan', 'deskripsi' => 'Konservasi, penyelamatan, rehabilitasi, dan perawatan satwa liar atau hewan peliharaan.', 'thumbnail' => 'images/kategori/hewan.png']
        ];

        foreach($Kategoris as $kategori){
            Kategori::create($kategori);
        }
    }
}
