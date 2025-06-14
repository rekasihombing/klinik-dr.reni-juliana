<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\RekamMedis;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class RekamMedisController extends Controller
{
public function create($appointmentId) 
    {
        $appointment = Appointment::with('pasien')->findOrFail($appointmentId);
        
        // Log untuk debugging
        Log::info('Appointment Data', [
            'id' => $appointment->id,
            'tanggal' => $appointment->tanggal,
            'pasien_nama' => $appointment->pasien->nama_lengkap
        ]);

        return Inertia::render('Doctor/TambahRekamMedis', [
            'patientData' => [
                'id' => $appointment->pasien->id,
                'nama' => $appointment->pasien->nama_lengkap,
                'umur' => $appointment->pasien->umur,
                'tanggalLahir' => $appointment->pasien->tanggal_lahir,
                'jenisKelamin' => $appointment->pasien->jenis_kelamin,
                'golonganDarah' => $appointment->pasien->golongan_darah,
                'noRekamMedis' => $appointment->pasien->no_rekam_medis,
            ],
            'appointment' => [
                'id' => $appointment->id,
                'patient_id' => $appointment->pasien_id,
                'tanggal' => $appointment->tanggal, // Pastikan tanggal dikirim
                'jam_konsultasi' => $appointment->jam_konsultasi,
                'keluhan' => $appointment->keluhan,
                'status' => $appointment->status,
            ],
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung'
        ]);
    }

    public function store(Request $request)
    { 
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'no_rekam_medis' => 'required|string|max:255',
            'tanggal_kunjungan' => 'required|date',
            'keluhan' => 'required|string',
            'rps' => 'nullable|string',
            'rpd' => 'nullable|string',
            'alergi' => 'nullable|string',
            'riwayat_obat' => 'nullable|string',
            'tekanan_darah' => 'nullable|string|max:50',
            'suhu_tubuh' => 'nullable|string|max:50',
            'nadi' => 'nullable|string|max:50',
            'pernapasan' => 'nullable|string|max:50',
            'berat_badan' => 'nullable|string|max:50',
            'status_gizi' => 'nullable|string|max:100',
            'diagnosa' => 'required|string',
            'catatan_dokter' => 'nullable|string'
        ], [
            'patient_id.required' => 'Data pasien harus ada',
            'patient_id.exists' => 'Pasien tidak ditemukan',
            'no_rekam_medis.required' => 'Nomor rekam medis harus diisi',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan harus diisi',
            'keluhan.required' => 'Keluhan utama harus diisi',
            'diagnosa.required' => 'Diagnosa harus diisi',
        ]);

        try {
            $rekamMedis = RekamMedis::create($validated);
            Log::info('Using Inertia location redirect');
            return Inertia::location(route('resep-obat.create', ['rekam_medis_id' => $rekamMedis->id]));
        } catch (\Exception $e) {
            Log::error('Error saving rekam medis: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }

    public function index()
    {
        $rekamMedis = RekamMedis::with(['pasien', 'dokter'])
            ->orderBy('tanggal_kunjungan', 'desc')
            ->paginate(10);

        return Inertia::render('Doctor/DaftarRekamMedis', [
            'rekamMedis' => $rekamMedis
        ]);
    }
}