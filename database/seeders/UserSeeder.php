<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'username' => 'Admin',
            'password' => Hash::make('master111'),
            'role_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'username' => 'Guru',
            'password' => Hash::make('master111'),
            'role_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'id' => 3,
            'username' => 'Siswa',
            'password' => Hash::make('master111'),
            'role_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('guru_profiles')->insert([
            'user_id' => 2,
            'full_name' => 'Guru',
            'nip' => '123456',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('kelas_guru')->insert([
            'kelas_id' => 1,
            'guru_id' => 2,
            'is_wali' => rand(0, 1),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('siswa_profiles')->insert([
            'user_id' => 3,
            'full_name' => 'Siswa',
            'nis' => '123456',
            'point' => 0,
            'level' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('kelas_siswa')->insert([
            'kelas_id' => 1,
            'siswa_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $client = new \GuzzleHttp\Client(['verify' => false]);
        $response = $client->get('https://randomuser.me/api/?results=500&nat=id');
        $users = json_decode($response->getBody(), true)['results'];

        $guruCount = DB::table('users')->where('role_id', 1)->count();

        foreach ($users as $index => $user) {
            $roleId = rand(0, 1) ? 1 : 3;

            if ($roleId == 1 && $guruCount >= 21) {
                $roleId = 3;
            }

            $userId = $index + 4;
            $username = $user['name']['first'];
            $originalUsername = $username;
            $counter = 1;

            while (DB::table('users')->where('username', $username)->exists()) {
                $username = $originalUsername . $counter;
                $counter++;
            }

            DB::table('users')->insert([
                'id' => $userId,
                'username' => $username,
                'password' => Hash::make('password123'),
                'role_id' => $roleId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if ($roleId == 1) {
                if ($guruCount <= 21) {
                    DB::table('guru_profiles')->insert([
                        'user_id' => $userId,
                        'full_name' => $user['name']['first'] . ' ' . $user['name']['last'],
                        'nip' => str_pad($userId, 6, '0', STR_PAD_LEFT),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    DB::table('kelas_guru')->insert([
                        'kelas_id' => rand(1, 4),
                        'guru_id' => $userId,
                        'is_wali' => rand(0, 1),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    $guruCount++;
                }
            } elseif ($roleId == 3) {
                $siswaprofileId = DB::table('siswa_profiles')->insertGetId([
                    'user_id' => $userId,
                    'full_name' => $user['name']['first'] . ' ' . $user['name']['last'],
                    'nis' => str_pad($userId, 6, '0', STR_PAD_LEFT),
                    'point' => 0,
                    'level' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                DB::table('kelas_siswa')->insert([
                    'kelas_id' => rand(1, 4),
                    'siswa_id' => $userId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
