<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\RekamMedis;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DetailPasienController extends Controller
{
    public function show(Request $request, $id)
    {
        // Cek apakah patient ada
        $patient = Patient::find($id);

        if (!$patient) {
            abort(404, 'Pasien tidak ditemukan');
        }

        // Ambil janji temu pasien yang aktif atau sesuai kebutuhan
        $appointments = Appointment::where('pasien_id', $id)->get();

        // Ambil rekam medis pasien dengan relasi yang diperlukan
        $rekamMedis = RekamMedis::with([
            'dokter:id,name', 
            'appointment:id,tanggal,jam_konsultasi',
            'resepObat:id,rekam_medis_id,nama_obat,dosis,jumlah,catatan,tanggal_mulai,tanggal_terakhir,dari_klinik',
            'resepObat.obat:id,nama_obat,kategori'
        ])
        ->byPasien($id)
        ->orderBy('tanggal_kunjungan', 'desc')
        ->get();

        // Format data rekam medis untuk frontend
        $rekamMedisData = $rekamMedis->map(function ($rm) {
            // Ambil ringkasan resep obat menggunakan method dari model
            $ringkasanResep = $rm->getRingkasanResepObat();
            
            return [
                'id' => $rm->id,
                'no_rekam_medis' => $rm->no_rekam_medis,
                'tanggal_kunjungan' => $rm->tanggal_kunjungan_formatted,
                'dokter_nama' => $rm->dokter->name ?? 'Tidak diketahui',
                'keluhan' => $rm->keluhan,
                'diagnosa' => $rm->diagnosa,
                'tindakan' => $rm->tindakan,
                'catatan_dokter' => $rm->catatan_dokter,
                'status' => $rm->status,
                
                // Data Anamnesis
                'anamnesis' => [
                    'rps' => $rm->rps,
                    'rpd' => $rm->rpd,
                    'riwayat_alergi' => $rm->riwayat_alergi,
                    'riwayat_obat' => $rm->riwayat_obat,
                ],
                
                // Data Pemeriksaan Fisik
                'pemeriksaan_fisik' => [
                    'tekanan_darah' => $rm->tekanan_darah,
                    'suhu_tubuh' => $rm->suhu_tubuh,
                    'nadi' => $rm->nadi,
                    'pernapasan' => $rm->pernapasan,
                    'berat_badan' => $rm->berat_badan,
                    'status_gizi' => $rm->status_gizi,
                ],
                
                // Data Resep Obat
                'resep_obat' => [
                    'has_resep' => $rm->has_resep_obat,
                    'has_resep_klinik' => $rm->has_resep_obat_klinik,
                    'has_resep_luar' => $rm->has_resep_obat_luar,
                    'obat_klinik' => $ringkasanResep['obat_klinik'],
                    'obat_luar' => $ringkasanResep['obat_luar'],
                    'obat_luar_lines' => $rm->obat_luar_lines,
                    'total_item_klinik' => $ringkasanResep['total_item_klinik'],
                ],
                
                // Data appointment terkait
                'appointment' => $rm->appointment ? [
                    'tanggal' => $rm->appointment->tanggal,
                    'jam' => $rm->appointment->jam_konsultasi,
                ] : null,
            ];
        });

        // Statistik rekam medis
        $statistikRekamMedis = [
            'total_kunjungan' => $rekamMedis->count(),
            'kunjungan_bulan_ini' => $rekamMedis->filter(function ($rm) {
                return $rm->tanggal_kunjungan->isCurrentMonth();
            })->count(),
            'terakhir_kunjungan' => $rekamMedis->first()?->tanggal_kunjungan_formatted,
            'total_resep_aktif' => $rekamMedis->sum(function ($rm) {
                return $rm->resepObat->filter(function ($resep) {
                    return $resep->status_aktif;
                })->count();
            }),
        ];

        // Data pasien yang akan dikirim
        $patientData = [
            ['label' => 'No Registrasi', 'value' => $patient->id],
            ['label' => 'Nama', 'value' => $patient->nama_lengkap],
            ['label' => 'NIK', 'value' => $patient->nik],
            ['label' => 'Tanggal Lahir', 'value' => $patient->tanggal_lahir],
            ['label' => 'Jenis Kelamin', 'value' => $patient->jenis_kelamin],
            ['label' => 'Golongan Darah', 'value' => $patient->golongan_darah],
            ['label' => 'Email', 'value' => $patient->email],
            ['label' => 'No HP', 'value' => $patient->no_hp],
            ['label' => 'Alamat', 'value' => $patient->alamat],
        ];

        $appointmentData = $appointments->map(function ($a) {
            return [
                'id' => $a->id,
                'queue_number' => $a->antrian ?? 'Belum tersedia',
                'date' => $a->tanggal,
                'time' => $a->jam_konsultasi,
                'complaint' => $a->keluhan,
                'status' => $a->status,
                'notes' => $a->catatan ?? 'Tidak ada catatan',
            ];
        });

        // Render berdasarkan role (dokter atau staff)
        if ($request->is('dokter.Pasien/*') || $request->routeIs('dokter.pasien.show')) {
            return Inertia::render('Doctor/DetailPasien', [
                'patientData' => $patientData,
                'appointmentData' => $appointmentData,
                'rekamMedisData' => $rekamMedisData,
                'statistikRekamMedis' => $statistikRekamMedis,
            ]);
        }

        if ($request->is('staff.Pasien/*') || $request->routeIs('staff.pasien.show')) {
            return Inertia::render('staff/DetailPasien', [
                'patientData' => $patientData,
                'appointmentData' => $appointmentData,
                'rekamMedisData' => $rekamMedisData,
                'statistikRekamMedis' => $statistikRekamMedis,
            ]);
        }

        // Fallback jika tidak ada route yang cocok
        abort(404, 'Rute tidak ditemukan');
    }
}