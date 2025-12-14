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
            ['nama' => 'Penjaga Hijau', 'deskripsi' => 'Diberikan kepada relawan yang berkontribusi dalam kegiatan sukarelawan dengan kategori Lingkungan.', 'thumbnail' => 'images/pencapaian/penjaga_hijau.png', 'required_kategori' => 1, 'required_count_kategori' => 5, 'required_exp' => null],
            ['nama' => 'Pahlawan Lingkungan', 'deskripsi' => 'Diberikan kepada relawan dengan dedikasi level Pahlawan yang telah berkontribusi secara signifikan dalam kegiatan kategori Lingkungan.', 'thumbnail' => 'images/pencapaian/pahlawan_lingkungan.png', 'required_kategori' => 1, 'required_count_kategori' => 25, 'required_exp' => null],
            ['nama' => 'Pengajar Inspiratif', 'deskripsi' => 'Diberikan kepada relawan yang mendedikasikan waktu dalam kegiatan sukarelawan kategori Pendidikan.', 'thumbnail' => 'images/pencapaian/pengajar_inspiratif.png', 'required_kategori' => 2, 'required_count_kategori' => 5, 'required_exp' => null],
            ['nama' => 'Mentor Komunitas', 'deskripsi' => 'Diberikan kepada relawan yang menjadi panutan dan pembimbing dalam kegiatan kategori Pendidikan.', 'thumbnail' => 'images/pencapaian/mentor_komunitas.png', 'required_kategori' => 2, 'required_count_kategori' => 25, 'required_exp' => null],
            ['nama' => 'Penyemangat Sehat', 'deskripsi' => 'Diberikan kepada relawan yang mendukung kegiatan sukarelawan di bidang Kesehatan.', 'thumbnail' => 'images/pencapaian/penyemangat_sehat.png', 'required_kategori' => 3, 'required_count_kategori' => 5, 'required_exp' => null],
            ['nama' => 'Pelopor Kesejahteraan', 'deskripsi' => 'Diberikan kepada relawan yang menjadi garda terdepan dalam meningkatkan kesehatan dan kesejahteraan masyarakat.', 'thumbnail' => 'images/pencapaian/pelopor_kesejahteraan.png', 'required_kategori' => 3, 'required_count_kategori' => 25, 'required_exp' => null],
            ['nama' => 'Jantung Sosial', 'deskripsi' => 'Diberikan kepada relawan yang aktif berinteraksi dan berkontribusi dalam kegiatan kategori Sosial.', 'thumbnail' => 'images/pencapaian/jantung_sosial.png', 'required_kategori' => 4, 'required_count_kategori' => 5, 'required_exp' => null],
            ['nama' => 'Arsitek Komunitas', 'deskripsi' => 'Diberikan kepada relawan yang berperan penting dalam membangun dan memperkuat ikatan komunitas melalui kegiatan Sosial.', 'thumbnail' => 'images/pencapaian/arsitek_komunitas.png', 'required_kategori' => 4, 'required_count_kategori' => 25, 'required_exp' => null],
            ['nama' => 'Penjelajah Kreatif', 'deskripsi' => 'Diberikan kepada relawan yang mendukung dan berpartisipasi dalam kegiatan sukarelawan kategori Seni.', 'thumbnail' => 'images/pencapaian/penjelajah_kreatif.png', 'required_kategori' => 5, 'required_count_kategori' => 5, 'required_exp' => null],
            ['nama' => 'Maestro Karya', 'deskripsi' => 'Diberikan kepada relawan yang telah memberikan kontribusi signifikan dalam memajukan seni dan kreativitas di masyarakat.', 'thumbnail' => 'images/pencapaian/maesto_karya.png', 'required_kategori' => 5, 'required_count_kategori' => 25, 'required_exp' => null],
            ['nama' => 'Penggerak Aktif', 'deskripsi' => 'Diberikan kepada relawan yang mempromosikan gaya hidup aktif dan berpartisipasi dalam kegiatan kategori Olahraga.', 'thumbnail' => 'images/pencapaian/penggerak_aktif.png', 'required_kategori' => 6, 'required_count_kategori' => 5, 'required_exp' => null],
            ['nama' => 'Bintang Lapangan', 'deskripsi' => 'Diberikan kepada relawan yang menjadi inspirasi dan pemimpin dalam kegiatan Olahraga komunitas.', 'thumbnail' => 'images/pencapaian/bintang_lapangan.png', 'required_kategori' => 6, 'required_count_kategori' => 25, 'required_exp' => null],
            ['nama' => 'Pecinta Satwa', 'deskripsi' => 'Diberikan kepada relawan yang menunjukkan kasih sayang dan kepedulian dalam kegiatan sukarelawan kategori Hewan.', 'thumbnail' => 'images/pencapaian/pecinta_satwa.png', 'required_kategori' => 7, 'required_count_kategori' => 5, 'required_exp' => null],
            ['nama' => 'Pelindung Margasatwa', 'deskripsi' => 'Diberikan kepada relawan yang secara aktif melindungi dan menyelamatkan satwa serta habitatnya.', 'thumbnail' => 'images/pencapaian/pelindung_margasatwa.png', 'required_kategori' => 7, 'required_count_kategori' => 25, 'required_exp' => null],
            ['nama' => 'Relawan Pemula', 'deskripsi' => 'Diberikan kepada relawan yang telah menyelesaikan kegiatan sukarelawan pertamanya bersama Volunite.', 'thumbnail' => 'images/pencapaian/relawan_pemula.png', 'required_kategori' => null, 'required_count_kategori' => null, 'required_exp' => 3000],
            ['nama' => 'Relawan Aktif', 'deskripsi' => 'Diberikan kepada relawan yang telah menunjukkan keaktifan dan menyelesaikan 10 kegiatan sukarelawan melalui Volunite.', 'thumbnail' => 'images/pencapaian/relawan_aktif.png', 'required_kategori' => null, 'required_count_kategori' => null, 'required_exp' => 10000],
            ['nama' => 'Kontributor Setia', 'deskripsi' => 'Diberikan kepada relawan yang secara konsisten berkontribusi dan telah berpartisipasi dalam 25 kegiatan sukarelawan.', 'thumbnail' => 'images/pencapaian/kontibutor_setia.png', 'required_kategori' => null, 'required_count_kategori' => null, 'required_exp' => 15000],
            ['nama' => 'Master Relawan', 'deskripsi' => 'Diberikan kepada relawan master yang telah mendedikasikan diri secara luar biasa dan menyelesaikan 50 kegiatan pengabdian.', 'thumbnail' => 'images/pencapaian/master_relawan.png', 'required_kategori' => null, 'required_count_kategori' => null, 'required_exp' => 20000,] ,
                [
                    'nama' => 'Legenda Relawan',
                    'deskripsi' => 'Diberikan kepada relawan legendaris yang telah menyelesaikan lebih dari 75 kegiatan sukarelawan dan menjadi inspirasi bagi komunitas.',
                    'thumbnail' => 'images/pencapaian/legenda_relawan.png',
                    'required_kategori' => null,
                    'required_count_kategori' => null,
                    'required_exp' => 25000
                ],
            [
                'nama' => 'Ikon Pengabdian',
                'deskripsi' => 'Diberikan kepada relawan luar biasa yang menunjukkan dedikasi jangka panjang dan konsistensi tinggi dalam kegiatan sosial.',
                'thumbnail' => 'images/pencapaian/ikon_pengabdian.png',
                'required_kategori' => null,
                'required_count_kategori' => null,
                'required_exp' => 32000
            ],
            [
                'nama' => 'Grandmaster Relawan',
                'deskripsi' => 'Pencapaian tertinggi bagi relawan elit yang telah mencapai tingkat pengabdian luar biasa dan dampak sosial yang luas.',
                'thumbnail' => 'images/pencapaian/grandmaster_relawan.png',
                'required_kategori' => null,
                'required_count_kategori' => null,
                'required_exp' => 50000
            ],


        ];

        foreach ($Pencapaians as $pencapaian) {
            Pencapaian::create($pencapaian);
        }
    }
}
