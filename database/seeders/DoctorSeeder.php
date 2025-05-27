<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    public function run()
    {
        // Buat user dokter
        $user = User::create([
            'email' => 'dokter@example.com',
            'password' => 'password123',  // Laravel otomatis hash password jika ada di model
            'role' => 'dokter',           // sesuaikan role
        ]);

        // Buat data dokter dengan user_id yang terkait
        Doctor::create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Dr. John Doe',
        ]);
    }
}
