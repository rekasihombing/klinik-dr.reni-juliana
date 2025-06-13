<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $userId = DB::table('users')->insertGetId([
            'email' => 'staff@gmail.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('staff')->insert([
            'user_id' => $userId,
            'nama_lengkap' => 'Staff Satu',
        ]);
    }
}
