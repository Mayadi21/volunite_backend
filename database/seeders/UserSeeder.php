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
                'user' => ['name' => 'Mas Gib Ran', 'email' => 'masgib@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('password123'), 'path_profil' => 'images/profile/default_profile.jpg', 'role' => 'Admin', 'remember_token' => Str::random(10)],
                'detail' => ['tanggal_lahir' => '1987-10-01', 'jenis_kelamin' => 'Tidak Ingin Memberi Tahu', 'no_telepon' => '081117042204', 'domisili' => 'Solo']
            ],
            [
                'user' => ['name' => 'mayadiG', 'email' => 'mayadiG@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('password123'), 'path_profil' => 'images/profile/default_profile.jpg', 'role' => 'Volunteer', 'remember_token' => Str::random(10)],
                'detail' => ['tanggal_lahir' => '2005-08-21', 'jenis_kelamin' => 'Laki-Laki', 'no_telepon' => '08123456890', 'domisili' => 'Siantar']
            ],
            [
                'user' => ['name' => 'Alfi', 'email' => 'alfi@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('password123'), 'path_profil' => 'images/profile/default_profile.jpg', 'role' => 'Volunteer', 'remember_token' => Str::random(10)],
                'detail' => ['tanggal_lahir' => '2004-10-24', 'jenis_kelamin' => 'Laki-Laki', 'no_telepon' => '08765432109', 'domisili' => 'Binjai']
            ],
            [
                'user' => ['name' => 'FerdyanG', 'email' => 'ferdyG@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('password123'), 'path_profil' => 'images/profile/default_profile.jpg', 'role' => 'Volunteer', 'remember_token' => Str::random(10)],
                'detail' => ['tanggal_lahir' => '2005-09-22', 'jenis_kelamin' => 'Laki-Laki', 'no_telepon' => '08642013579', 'domisili' => 'Medan']
            ],
            [
                'user' => ['name' => 'Naurah', 'email' => 'naurah@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('password123'), 'path_profil' => 'images/profile/default_profile.jpg', 'role' => 'Volunteer', 'remember_token' => Str::random(10)],
                'detail' => ['tanggal_lahir' => '2005-10-05', 'jenis_kelamin' => 'Perempuan', 'no_telepon' => '08246809753', 'domisili' => 'Medan']
            ],
            [
                'user' => ['name' => 'paskal', 'email' => 'paskal@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('password123'), 'path_profil' => 'images/profile/default_profile.jpg', 'role' => 'Organizer', 'remember_token' => Str::random(10)],
                'detail' => ['tanggal_lahir' => '2005-03-23', 'jenis_kelamin' => 'Laki-Laki', 'no_telepon' => '08135790864', 'domisili' => 'Medan']
            ],
            [
                'user' => ['name' => 'patra', 'email' => 'patra@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('password123'), 'path_profil' => 'images/profile/default_profile.jpg', 'role' => 'Organizer', 'remember_token' => Str::random(10)],
                'detail' => ['tanggal_lahir' => '2005-04-30', 'jenis_kelamin' => 'Laki-Laki', 'no_telepon' => '08975312468', 'domisili' => 'Dolok Masihul']
            ]
        ];

        foreach($UsersData as $data){
            $user = User::create($$data['user']);

            $user->detail()->create($$data['detail']);
        }
    }
}
