<?php

namespace Database\Seeders;

use App\Models\Pencapaian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PencapaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $Pencapaians = [
            ['nama' => 'Relawan Pemula', 'deskripsi' => '', 'thumbnail' => '', 'required_kategori' => null, 'required_count_kategori' => null, 'required_exp' => 10],
            ['nama' => 'Juara Komunitas', 'deskripsi' => '', 'thumbnail' => '', 'required_kategori' => 2, 'required_count_kategori' => 15, 'required_exp' => null],
            ['nama' => 'Penjaga Hijau', 'deskripsi' => '', 'thumbnail' => '', 'required_kategori' => 3, 'required_count_kategori' => 10, 'required_exp' => null],
            ['nama' => 'Relawan Aktif', 'deskripsi' => '', 'thumbnail' => '', 'required_kategori' => null, 'required_count_kategori' => null, 'required_exp' => 100],
            ['nama' => 'Master Relawan', 'deskripsi' => '', 'thumbnail' => '', 'required_kategori' => null, 'required_count_kategori' => null, 'required_exp' => 1000],
            ['nama' => 'Pahlawan Lingkungan', 'deskripsi' => '', 'thumbnail' => '', 'required_kategori' => 3, 'required_count_kategori' => 50, 'required_exp' => null],
            ['nama' => 'Kontributor Setia', 'deskripsi' => '', 'thumbnail' => '', 'required_kategori' => null, 'required_count_kategori' => null, 'required_exp' => 250],
            ['nama' => 'Sahabat Pendidikan', 'deskripsi' => '', 'thumbnail' => '', 'required_kategori' => 1, 'required_count_kategori' => 10, 'required_exp' => null],
            ['nama' => 'Penolong Bencana', 'deskripsi' => '', 'thumbnail' => '', 'required_kategori' => 3, 'required_count_kategori' => 3, 'required_exp' => null],
            ['nama' => 'Penyelamat Satwa', 'deskripsi' => '', 'thumbnail' => '', 'required_kategori' => 7, 'required_count_kategori' => 10, 'required_exp' => null]
            
        ];

        foreach($Pencapaians as $pencapaian){
            Pencapaian::create($pencapaian);
        }
    }
}
