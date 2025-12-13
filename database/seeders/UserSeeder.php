<?php

namespace Database\Seeders;

use App\Models\DetailUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $UsersData = [
            [
                'user' => ['nama' => 'Mas Wowok', 'email' => 'masgib@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('password123'), 'path_profil' => 'images/profile/default_profile.jpg', 'role' => 'Admin', 'remember_token' => Str::random(10)],
                'detail' => ['tanggal_lahir' => '1987-10-01', 'jenis_kelamin' => 'Tidak Ingin Memberi Tahu', 'no_telepon' => '081117042204', 'domisili' => 'Solo']
            ],
            [
                'user' => ['nama' => 'mayadi', 'email' => 'mayadi@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('password123'), 'path_profil' => 'images/profile/default_profile.jpg', 'role' => 'Volunteer', 'remember_token' => Str::random(10)],
                'detail' => ['tanggal_lahir' => '2005-08-21', 'jenis_kelamin' => 'Laki-Laki', 'no_telepon' => '08123456890', 'domisili' => 'Siantar']
            ],
            [
                'user' => ['nama' => 'Alfi', 'email' => 'alfi@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('password123'), 'path_profil' => 'images/profile/default_profile.jpg', 'role' => 'Volunteer', 'remember_token' => Str::random(10)],
                'detail' => ['tanggal_lahir' => '2004-10-24', 'jenis_kelamin' => 'Laki-Laki', 'no_telepon' => '08765432109', 'domisili' => 'Binjai']
            ],
            [
                'user' => ['nama' => 'Ferdyan', 'email' => 'ferdy@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('password123'), 'path_profil' => 'images/profile/default_profile.jpg', 'role' => 'Volunteer', 'remember_token' => Str::random(10)],
                'detail' => ['tanggal_lahir' => '2005-09-22', 'jenis_kelamin' => 'Laki-Laki', 'no_telepon' => '08642013579', 'domisili' => 'Medan']
            ],
            [
                'user' => ['nama' => 'Naurah', 'email' => 'naurah@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('password123'), 'path_profil' => 'images/profile/default_profile.jpg', 'role' => 'Volunteer', 'remember_token' => Str::random(10)],
                'detail' => ['tanggal_lahir' => '2005-10-05', 'jenis_kelamin' => 'Perempuan', 'no_telepon' => '08246809753', 'domisili' => 'Medan']
            ],
            [
                'user' => ['nama' => 'paskal', 'email' => 'paskal@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('password123'), 'path_profil' => 'images/profile/default_profile.jpg', 'role' => 'Organizer', 'remember_token' => Str::random(10)],
                'detail' => ['tanggal_lahir' => '2005-03-23', 'jenis_kelamin' => 'Laki-Laki', 'no_telepon' => '08135790864', 'domisili' => 'Medan']
            ],
            [
                'user' => ['nama' => 'patra', 'email' => 'patra@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('password123'), 'path_profil' => 'images/profile/default_profile.jpg', 'role' => 'Organizer', 'remember_token' => Str::random(10)],
                'detail' => ['tanggal_lahir' => '2005-04-30', 'jenis_kelamin' => 'Laki-Laki', 'no_telepon' => '08975312468', 'domisili' => 'Dolok Masihul']
            ],

            // === TAMBAHAN VOLUNTEER ===
            [
                'user' => [
                    'nama' => 'Rizky Pratama',
                    'email' => 'rizky.volunteer@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'path_profil' => 'images/profile/default_profile.jpg',
                    'role' => 'Volunteer',
                    'remember_token' => Str::random(10)
                ],
                'detail' => [
                    'tanggal_lahir' => '2004-01-15',
                    'jenis_kelamin' => 'Laki-Laki',
                    'no_telepon' => '081234000001',
                    'domisili' => 'Medan'
                ]
            ],
            [
                'user' => [
                    'nama' => 'Alya Putri',
                    'email' => 'alya.volunteer@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'path_profil' => 'images/profile/default_profile.jpg',
                    'role' => 'Volunteer',
                    'remember_token' => Str::random(10)
                ],
                'detail' => [
                    'tanggal_lahir' => '2005-02-20',
                    'jenis_kelamin' => 'Perempuan',
                    'no_telepon' => '081234000002',
                    'domisili' => 'Binjai'
                ]
            ],
            [
                'user' => [
                    'nama' => 'Dimas Saputra',
                    'email' => 'dimas.volunteer@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'path_profil' => 'images/profile/default_profile.jpg',
                    'role' => 'Volunteer',
                    'remember_token' => Str::random(10)
                ],
                'detail' => [
                    'tanggal_lahir' => '2003-11-12',
                    'jenis_kelamin' => 'Laki-Laki',
                    'no_telepon' => '081234000003',
                    'domisili' => 'Lubuk Pakam'
                ]
            ],
            [
                'user' => [
                    'nama' => 'Nabila Syifa',
                    'email' => 'nabila.volunteer@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'path_profil' => 'images/profile/default_profile.jpg',
                    'role' => 'Volunteer',
                    'remember_token' => Str::random(10)
                ],
                'detail' => [
                    'tanggal_lahir' => '2004-06-18',
                    'jenis_kelamin' => 'Perempuan',
                    'no_telepon' => '081234000004',
                    'domisili' => 'Medan'
                ]
            ],
            [
                'user' => [
                    'nama' => 'Fajar Ramadhan',
                    'email' => 'fajar.volunteer@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'path_profil' => 'images/profile/default_profile.jpg',
                    'role' => 'Volunteer',
                    'remember_token' => Str::random(10)
                ],
                'detail' => [
                    'tanggal_lahir' => '2002-09-09',
                    'jenis_kelamin' => 'Laki-Laki',
                    'no_telepon' => '081234000005',
                    'domisili' => 'Tebing Tinggi'
                ]
            ],
            [
                'user' => [
                    'nama' => 'Salsa Maharani',
                    'email' => 'salsa.volunteer@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'path_profil' => 'images/profile/default_profile.jpg',
                    'role' => 'Volunteer',
                    'remember_token' => Str::random(10)
                ],
                'detail' => [
                    'tanggal_lahir' => '2005-12-01',
                    'jenis_kelamin' => 'Perempuan',
                    'no_telepon' => '081234000006',
                    'domisili' => 'Medan'
                ]
            ],


            // === TAMBAHAN ORGANIZER (NAMA ORGANISASI KEREN) ===
            [
                'user' => [
                    'nama' => 'GreenFuture Indonesia',
                    'email' => 'greenfuture@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'path_profil' => 'images/profile/default_profile.jpg',
                    'role' => 'Organizer',
                    'remember_token' => Str::random(10)
                ],
                'detail' => [
                    'tanggal_lahir' => '2015-01-01',
                    'jenis_kelamin' => 'Tidak Ingin Memberi Tahu',
                    'no_telepon' => '081399900001',
                    'domisili' => 'Medan'
                ]
            ],
            [
                'user' => [
                    'nama' => 'EduCare Foundation',
                    'email' => 'educare@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'path_profil' => 'images/profile/default_profile.jpg',
                    'role' => 'Organizer',
                    'remember_token' => Str::random(10)
                ],
                'detail' => [
                    'tanggal_lahir' => '2012-01-01',
                    'jenis_kelamin' => 'Tidak Ingin Memberi Tahu',
                    'no_telepon' => '081399900002',
                    'domisili' => 'Jakarta'
                ]
            ],
            [
                'user' => [
                    'nama' => 'HealthBridge Community',
                    'email' => 'healthbridge@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'path_profil' => 'images/profile/default_profile.jpg',
                    'role' => 'Organizer',
                    'remember_token' => Str::random(10)
                ],
                'detail' => [
                    'tanggal_lahir' => '2010-01-01',
                    'jenis_kelamin' => 'Tidak Ingin Memberi Tahu',
                    'no_telepon' => '081399900003',
                    'domisili' => 'Bandung'
                ]
            ],
            [
                'user' => [
                    'nama' => 'Solidarity Action Network',
                    'email' => 'solidarity@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'path_profil' => 'images/profile/default_profile.jpg',
                    'role' => 'Organizer',
                    'remember_token' => Str::random(10)
                ],
                'detail' => [
                    'tanggal_lahir' => '2018-01-01',
                    'jenis_kelamin' => 'Tidak Ingin Memberi Tahu',
                    'no_telepon' => '081399900004',
                    'domisili' => 'Surabaya'
                ]
            ]

        ];

        foreach ($UsersData as $data) {
            $user = User::create($data['user']);

            $user->detailUser()->create($data['detail']);
        }
    }
}
