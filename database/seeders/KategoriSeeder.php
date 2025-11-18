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
            ['nama_kategori' => 'Pendidikan', 'deskripsi' => '', 'thumbnail' => 'images/kategori/pendidikan.png'],
            ['nama_kategori' => 'Sosial', 'deskripsi' => '', 'thumbnail' => 'images/kategori/sosial.png'],
            ['nama_kategori' => 'Lingkungan', 'deskripsi' => '', 'thumbnail' => 'images/kategori/lingkungan.png'],
            ['nama_kategori' => 'Kesehatan', 'deskripsi' => '', 'thumbnail' => 'images/kategori/kesehatan.png'],
            ['nama_kategori' => 'Olahraga', 'deskripsi' => '', 'thumbnail' => 'images/kategori/olahraga.png'],
            ['nama_kategori' => 'Seni', 'deskripsi' => '', 'thumbnail' => 'images/kategori/seni.png'],
            ['nama_kategori' => 'Hewan', 'deskripsi' => '', 'thumbnail' => 'images/kategori/hewan.png']
        ];

        foreach($Kategoris as $kategori){
            Kategori::create($kategori);
        }
    }
}
