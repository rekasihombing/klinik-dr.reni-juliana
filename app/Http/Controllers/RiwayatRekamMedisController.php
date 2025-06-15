<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\RekamMedis;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth; 

class RiwayatRekamMedisController extends Controller
{
    /**
     * Menampilkan riwayat rekam medis untuk pasien yang sedang login
     */
    public function index()
    {
        $user = Auth::user();     
        $pasien = $user->patient;

if (!$pasien) {
    return redirect()->route('dashboard')->with('alert', 'Silakan lengkapi data pasien terlebih dahulu sebelum melihat rekam medis.');
}
 
        
        // Karena struktur tabel menggunakan patient_id unique, 
        // kita perlu mengambil semua appointment pasien yang memiliki rekam medis
        $riwayatRekamMedis = RekamMedis::with(['appointment' => function($query) {
                $query->with('pasien');
            }])
            ->whereHas('appointment', function($query) use ($pasien) {
                $query->where('patient_id', $pasien->id);
            })
            ->orderBy('tanggal_kunjungan', 'desc')
            ->get()
            ->map(function ($rekamMedis) {
                return [
                    'id' => $rekamMedis->id,
                    'date' => $rekamMedis->tanggal_kunjungan ? 
                             Carbon::parse($rekamMedis->tanggal_kunjungan)->format('d-m-Y') : 
                             Carbon::parse($rekamMedis->created_at)->format('d-m-Y'),
                    'time' => $rekamMedis->appointment ? 
                             Carbon::parse($rekamMedis->appointment->jam_konsultasi)->format('H.i') : 
                             Carbon::parse($rekamMedis->created_at)->format('H.i'),
                    'queueNumber' => $rekamMedis->appointment ? $rekamMedis->appointment->no_antrian : '-',
                    'keluhan' => $rekamMedis->keluhan,
                    'diagnosa' => $rekamMedis->diagnosa,
                    'catatan_dokter' => $rekamMedis->catatan_dokter,
                    'no_rekam_medis' => $rekamMedis->no_rekam_medis,
                    'tanggal_kunjungan' => $rekamMedis->tanggal_kunjungan,
                    // Tambahan data untuk detail
                    'rps' => $rekamMedis->rps,
                    'rpd' => $rekamMedis->rpd,
                    'alergi' => $rekamMedis->alergi,
                    'riwayat_obat' => $rekamMedis->riwayat_obat,
                    'tekanan_darah' => $rekamMedis->tekanan_darah,
                    'suhu_tubuh' => $rekamMedis->suhu_tubuh,
                    'nadi' => $rekamMedis->nadi,
                    'pernapasan' => $rekamMedis->pernapasan,
                    'berat_badan' => $rekamMedis->berat_badan,
                    'status_gizi' => $rekamMedis->status_gizi,
                ];
            });

        return Inertia::render('pasien/RiwayatRekamMedis', [
            'appointments' => $riwayatRekamMedis,
            'patientName' => $pasien->nama_lengkap ?? $pasien->name,
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung'
        ]);
    }

    /**
     * Menampilkan detail rekam medis spesifik
     */
    public function show($id)
    {
        $pasien = $user->patient;

if (!$pasien) {
    return redirect()->route('dashboard')->with('alert', 'Silakan lengkapi data pasien terlebih dahulu sebelum melihat rekam medis.');
}

        
        // Ambil rekam medis dengan memastikan pasien hanya bisa melihat rekam medis miliknya
        $rekamMedis = RekamMedis::with(['appointment'])
            ->whereHas('appointment', function($query) use ($pasien) {
                $query->where('patient_id', $pasien->id);
            })
            ->where('id', $id)
            ->firstOrFail();

        return Inertia::render('Patient/DetailRekamMedis', [
            'rekamMedis' => [
                'id' => $rekamMedis->id,
                'no_rekam_medis' => $rekamMedis->no_rekam_medis,
                'tanggal_kunjungan' => $rekamMedis->tanggal_kunjungan ? 
                                     Carbon::parse($rekamMedis->tanggal_kunjungan)->format('d F Y') : 
                                     Carbon::parse($rekamMedis->created_at)->format('d F Y'),
                'jam_kunjungan' => $rekamMedis->appointment ? 
                                 Carbon::parse($rekamMedis->appointment->jam_konsultasi)->format('H:i') : 
                                 Carbon::parse($rekamMedis->created_at)->format('H:i'),
                'no_antrian' => $rekamMedis->appointment ? $rekamMedis->appointment->no_antrian : '-',
                'dokter' => 'Dr. Reni Juliana Manurung', // Sesuai dengan nama klinik
                
                // Anamnesis
                'keluhan' => $rekamMedis->keluhan,
                'rps' => $rekamMedis->rps,
                'rpd' => $rekamMedis->rpd,
                'alergi' => $rekamMedis->alergi,
                'riwayat_obat' => $rekamMedis->riwayat_obat,
                
                // Pemeriksaan Fisik
                'tekanan_darah' => $rekamMedis->tekanan_darah,
                'suhu_tubuh' => $rekamMedis->suhu_tubuh,
                'nadi' => $rekamMedis->nadi,
                'pernapasan' => $rekamMedis->pernapasan,
                'berat_badan' => $rekamMedis->berat_badan,
                'status_gizi' => $rekamMedis->status_gizi,
                
                // Diagnosa dan Catatan
                'diagnosa' => $rekamMedis->diagnosa,
                'catatan_dokter' => $rekamMedis->catatan_dokter,
            ],
            'patientName' => $pasien->nama_lengkap ?? $pasien->name,
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung'
        ]);
    }
}