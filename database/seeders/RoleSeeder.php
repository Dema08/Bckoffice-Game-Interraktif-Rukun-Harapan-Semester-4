<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    public function run()
    {
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

       
        DB::table('roles')->truncate();

        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        
        DB::table('roles')->insert([
            [
                'id'         => 1,
                'name'       => 'guru',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 2,
                'name'       => 'admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 3,
                'name'       => 'siswa',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
