<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obat;
use Illuminate\Support\Facades\DB;

class ObatSeeder extends Seeder
{
    public function run()
    {
        $obatData = [
            [
                'nama_obat' => 'Paracetamol 500mg',
                'jenis_obat' => 'Tablet',
                'deskripsi' => 'Obat penurun panas dan pereda nyeri',
                'harga' => 500,
                'satuan' => 'strip'
            ],
            [
                'nama_obat' => 'Amoxicillin 500mg',
                'jenis_obat' => 'Kapsul',
                'deskripsi' => 'Antibiotik untuk infeksi bakteri',
                'harga' => 8000,
                'satuan' => 'strip'
            ],
            [
                'nama_obat' => 'OBH Combi',
                'jenis_obat' => 'Sirup',
                'deskripsi' => 'Obat batuk kombinasi',
                'harga' => 12000,
                'satuan' => 'botol'
            ],
            [
                'nama_obat' => 'Betadine Solution',
                'jenis_obat' => 'Larutan',
                'deskripsi' => 'Antiseptik untuk luka luar',
                'harga' => 15000,
                'satuan' => 'botol'
            ],
            [
                'nama_obat' => 'Voltaren Gel',
                'jenis_obat' => 'Salep',
                'deskripsi' => 'Obat oles untuk nyeri otot dan sendi',
                'harga' => 25000,
                'satuan' => 'tube'
            ],
            [
                'nama_obat' => 'Vitamin C 1000mg',
                'jenis_obat' => 'Tablet',
                'deskripsi' => 'Suplemen vitamin C',
                'harga' => 30000,
                'satuan' => 'strip'
            ],
            [
                'nama_obat' => 'Antangin JRG',
                'jenis_obat' => 'Sirup',
                'deskripsi' => 'Obat masuk angin',
                'harga' => 8000,
                'satuan' => 'botol'
            ],
            [
                'nama_obat' => 'Promag Tablet',
                'jenis_obat' => 'Tablet',
                'deskripsi' => 'Obat maag dan asam lambung',
                'harga' => 4000,
                'satuan' => 'strip'
            ],
            [
                'nama_obat' => 'Mylanta Liquid',
                'jenis_obat' => 'Sirup',
                'deskripsi' => 'Obat maag cair',
                'harga' => 18000,
                'satuan' => 'botol'
            ],
            [
                'nama_obat' => 'Ibuprofen 400mg',
                'jenis_obat' => 'Tablet',
                'deskripsi' => 'Anti inflamasi dan pereda nyeri',
                'harga' => 6000,
                'satuan' => 'strip'
            ]
        ];

        foreach ($obatData as $data) {
            Obat::create($data);
        }
    }
}

// Jangan lupa daftarkan di DatabaseSeeder.php:
// $this->call(ObatSeeder::class);