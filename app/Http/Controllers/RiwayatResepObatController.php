<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResepObat;
use App\Models\RekamMedis;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class RiwayatResepObatController extends Controller
{
    /**
     * Menampilkan daftar resep obat untuk pasien yang sedang login
     */
    public function index()
    {
        $user = Auth::user();
        $pasien = $user->patient;

        // Ambil semua resep obat berdasarkan rekam medis pasien
        $resepObat = ResepObat::with(['rekamMedis.appointment', 'obat'])
            ->whereHas('rekamMedis.appointment', function($query) use ($pasien) {
                $query->where('patient_id', $pasien->id);
            })
            ->orderBy('tanggal_mulai', 'desc')
            ->get()
            ->groupBy('rekam_medis_id')
            ->map(function ($resepGroup) {
                $firstResep = $resepGroup->first();
                $rekamMedis = $firstResep->rekamMedis;
                
                return [
                    'rekam_medis_id' => $rekamMedis->id,
                    'no_rekam_medis' => $rekamMedis->no_rekam_medis,
                    'tanggal_kunjungan' => $rekamMedis->tanggal_kunjungan ? 
                                         Carbon::parse($rekamMedis->tanggal_kunjungan)->format('d F Y') : 
                                         Carbon::parse($rekamMedis->created_at)->format('d F Y'),
                    'jam_kunjungan' => $rekamMedis->appointment ? 
                                     Carbon::parse($rekamMedis->appointment->jam_konsultasi)->format('H:i') : 
                                     Carbon::parse($rekamMedis->created_at)->format('H:i'),
                    'diagnosa' => $rekamMedis->diagnosa,
                    'total_obat' => $resepGroup->count(),
                    'status_aktif' => $resepGroup->filter(function($resep) {
                        return Carbon::now()->between(
                            Carbon::parse($resep->tanggal_mulai),
                            Carbon::parse($resep->tanggal_terakhir)
                        );
                    })->count() > 0,
                    'tanggal_terakhir' => $resepGroup->max('tanggal_terakhir'),
                ];
            })
            ->values();

        return Inertia::render('pasien/RiwayatResepObat', [
            'resepObat' => $resepObat,
            'patientName' => $pasien->nama_lengkap ?? $pasien->name,
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung'
        ]);
    }

    /**
     * Menampilkan detail resep obat berdasarkan rekam medis
     */
    public function show($rekamMedisId)
    {
        $user = Auth::user();
        $pasien = $user->patient;

        // Validasi bahwa rekam medis milik pasien yang sedang login
        $rekamMedis = RekamMedis::with(['appointment'])
            ->whereHas('appointment', function($query) use ($pasien) {
                $query->where('patient_id', $pasien->id);
            })
            ->where('id', $rekamMedisId)
            ->firstOrFail();

        // Ambil semua resep obat untuk rekam medis ini
        $resepObat = ResepObat::with(['obat'])
            ->where('rekam_medis_id', $rekamMedisId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($resep) {
                return [
                    'id' => $resep->id,
                    'nama_obat' => $resep->nama_obat,
                    'dosis' => $resep->dosis,
                    'jumlah' => $resep->jumlah,
                    'catatan' => $resep->catatan,
                    'tanggal_mulai' => Carbon::parse($resep->tanggal_mulai)->format('d F Y'),
                    'tanggal_terakhir' => Carbon::parse($resep->tanggal_terakhir)->format('d F Y'),
                    'durasi_hari' => Carbon::parse($resep->tanggal_mulai)->diffInDays(Carbon::parse($resep->tanggal_terakhir)) + 1,
                    'dari_klinik' => $resep->dari_klinik,
                    'status_aktif' => Carbon::now()->between(
                        Carbon::parse($resep->tanggal_mulai),
                        Carbon::parse($resep->tanggal_terakhir)
                    ),
                    'sisa_hari' => Carbon::now()->diffInDays(Carbon::parse($resep->tanggal_terakhir), false)
                ];
            });

        return Inertia::render('pasien/DetailResepObat', [
            'resepObat' => $resepObat,
            'rekamMedis' => [
                'id' => $rekamMedis->id,
                'no_rekam_medis' => $rekamMedis->no_rekam_medis,
                'tanggal_kunjungan' => $rekamMedis->tanggal_kunjungan ? 
                                     Carbon::parse($rekamMedis->tanggal_kunjungan)->format('d F Y') : 
                                     Carbon::parse($rekamMedis->created_at)->format('d F Y'),
                'jam_kunjungan' => $rekamMedis->appointment ? 
                                 Carbon::parse($rekamMedis->appointment->jam_konsultasi)->format('H:i') : 
                                 Carbon::parse($rekamMedis->created_at)->format('H:i'),
                'diagnosa' => $rekamMedis->diagnosa,
                'keluhan' => $rekamMedis->keluhan,
                'catatan_dokter' => $rekamMedis->catatan_dokter,
            ],
            'patientName' => $pasien->nama_lengkap ?? $pasien->name,
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung',
            'doctorName' => 'Dr. Reni Juliana Manurung'
        ]);
    }
}