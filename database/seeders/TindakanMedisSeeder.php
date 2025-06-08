<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TindakanMedis;

class TindakanMedisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_tindakan' => 'Pemeriksaan Fisik', 'tarif' => 40000.00],
            ['nama_tindakan' => 'Pengukuran Tekanan Darah', 'tarif' => 15000.00],
            ['nama_tindakan' => 'Pengukuran Gula Darah', 'tarif' => 30000.00],
            ['nama_tindakan' => 'Pemberian Suntikan', 'tarif' => 50000.00],
            ['nama_tindakan' => 'Konsultasi Dokter', 'tarif' => 60000.00],
            ['nama_tindakan' => 'Pemeriksaan Laboratorium Darah', 'tarif' => 70000.00],
            ['nama_tindakan' => 'Pengobatan Luka Ringan', 'tarif' => 45000.00],
            ['nama_tindakan' => 'Pembersihan Luka', 'tarif' => 40000.00],
            ['nama_tindakan' => 'Pemeriksaan Telinga', 'tarif' => 30000.00],
            ['nama_tindakan' => 'Pemeriksaan Mata', 'tarif' => 35000.00],
        ];

        foreach ($data as $item) {
            TindakanMedis::create($item);
        }
    }
}
