<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ClinicScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Insert jadwal default
        DB::table('clinic_schedules')->insert([
            ['day_of_week' => 'Senin', 'is_open' => true, 'open_time' => '15:00:00', 'close_time' => '23:00:00'],
            ['day_of_week' => 'Selasa', 'is_open' => true, 'open_time' => '15:00:00', 'close_time' => '23:00:00'],
            ['day_of_week' => 'Rabu', 'is_open' => true, 'open_time' => '15:00:00', 'close_time' => '23:00:00'],
            ['day_of_week' => 'Kamis', 'is_open' => true, 'open_time' => '15:00:00', 'close_time' => '23:00:00'],
            ['day_of_week' => 'Jumat', 'is_open' => true, 'open_time' => '15:00:00', 'close_time' => '23:00:00'],
            ['day_of_week' => 'Sabtu', 'is_open' => false, 'open_time' => null, 'close_time' => null],
            ['day_of_week' => 'Minggu', 'is_open' => false, 'open_time' => null, 'close_time' => null],
        ]);

        // Insert pengecualian jadwal
        DB::table('schedule_exceptions')->insert([
            ['exception_date' => '2025-08-17', 'is_open' => false, 'reason' => 'Hari Kemerdekaan Indonesia'],
            ['exception_date' => '2025-12-25', 'is_open' => false, 'reason' => 'Hari Natal'],
            ['exception_date' => '2025-01-01', 'is_open' => false, 'reason' => 'Tahun Baru'],
        ]);
    }
}
