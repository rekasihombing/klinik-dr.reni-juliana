<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\RekamMedis;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class RekamMedisController extends Controller
{
    public function create($appointmentId) 
    {
        $appointment = Appointment::with('pasien')->findOrFail($appointmentId);
        
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
            'appointment' => $appointment,
            'clinicName' => 'Klinik Praktek Dr. Rena Juliana Manurung'
        ]);
    }

    public function store(Request $request)
    { 

        // Validasi input
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'no_rekam_medis' => 'required|string|max:255',
            'tanggal_kunjungan' => 'required|date',
            
            // Anamnesis
            'keluhan' => 'required|string',
            'rps' => 'nullable|string',
            'rpd' => 'nullable|string',
            'alergi' => 'nullable|string',
            'riwayat_obat' => 'nullable|string',
            
            // Pemeriksaan Fisik
            'tekanan_darah' => 'nullable|string|max:50',
            'suhu_tubuh' => 'nullable|string|max:50',
            'nadi' => 'nullable|string|max:50',
            'pernapasan' => 'nullable|string|max:50',
            'berat_badan' => 'nullable|string|max:50',
            'status_gizi' => 'nullable|string|max:100',
            
            // Diagnosa dan Tindakan
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
            // Simpan data rekam medis
            $rekamMedis = RekamMedis::create([
                'patient_id' => $validated['patient_id'],
                'appointment_id' => $validated['appointment_id'],
                'no_rekam_medis' => $validated['no_rekam_medis'],
                'tanggal_kunjungan' => $validated['tanggal_kunjungan'],
                
                // Anamnesis
                'keluhan' => $validated['keluhan'],
                'rps' => $validated['rps'],
                'rpd' => $validated['rpd'],
                'alergi' => $validated['alergi'],
                'riwayat_obat' => $validated['riwayat_obat'],
                
                // Pemeriksaan Fisik
                'tekanan_darah' => $validated['tekanan_darah'],
                'suhu_tubuh' => $validated['suhu_tubuh'],
                'nadi' => $validated['nadi'],
                'pernapasan' => $validated['pernapasan'],
                'berat_badan' => $validated['berat_badan'],
                'status_gizi' => $validated['status_gizi'],
                
                // Diagnosa dan Tindakan
                'diagnosa' => $validated['diagnosa'],
                'catatan_dokter' => $validated['catatan_dokter']
            ]);

            // SOLUSI YANG BENAR: Gunakan Inertia::location untuk external redirect
            Log::info('Using Inertia location redirect');
            return Inertia::location(route('resep-obat.create', ['rekam_medis_id' => $rekamMedis->id]));

        } catch (\Exception $e) {
            Log::error('Error saving rekam medis: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()  
            ])->withInput();
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