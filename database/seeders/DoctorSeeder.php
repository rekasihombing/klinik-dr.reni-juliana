<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run()
    {
        $userId = DB::table('users')->insertGetId([
            'email' => 'dokter@gmail.com',
            'password' => Hash::make('dokter123'),
            'role' => 'dokter',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('doctors')->insert([
            'user_id' => $userId,
            'nama_lengkap' => 'dokter',
        ]);
    }
}
