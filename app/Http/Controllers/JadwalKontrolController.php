<?php

namespace App\Http\Controllers;

use App\Models\JadwalKontrol;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JadwalKontrolController extends Controller
{
    public function create(RekamMedis $rekamMedis)
    {
        // Pastikan relasi pasien di-load
        $rekamMedis->load('pasien');

        // Validasi bahwa pasien ada
        if (!$rekamMedis->pasien) {
            Log::error('Pasien tidak ditemukan untuk rekam medis ID: ' . $rekamMedis->id);
            return redirect()->route('dashboarddokter')->withErrors(['error' => 'Data pasien tidak ditemukan']);
        }

        // Hitung umur jika umur kosong
        $umur = $rekamMedis->pasien->umur ?? ($rekamMedis->pasien->tanggal_lahir ? Carbon::parse($rekamMedis->pasien->tanggal_lahir)->age : null);

        // Log untuk debugging
        Log::info('Jadwal Kontrol Create Data', [
            'rekam_medis_id' => $rekamMedis->id,
            'patient_data' => [
                'id' => $rekamMedis->pasien->id,
                'nama_lengkap' => $rekamMedis->pasien->nama_lengkap ?? 'Tidak Diketahui',
                'umur' => $umur ?? 'Tidak Diketahui',
                'umur_from_db' => $rekamMedis->pasien->umur,
                'tanggal_lahir' => $rekamMedis->pasien->tanggal_lahir ?? 'Tidak Diketahui',
                'jenis_kelamin' => $rekamMedis->pasien->jenis_kelamin ?? 'Tidak Diketahui',
            ],
        ]);

        return inertia('Doctor/JadwalKontrol', [
            'rekamMedis' => $rekamMedis,
            'patientData' => [
                'nama' => $rekamMedis->pasien->nama_lengkap ?? null,
                'jenisKelamin' => $rekamMedis->pasien->jenis_kelamin ?? null,
                'umur' => $umur,
                'tanggalLahir' => $rekamMedis->pasien->tanggal_lahir ?? null,
            ],
            'clinicName' => config('app.clinic_name', 'Klinik'),
            'patientName' => $rekamMedis->pasien->nama_lengkap ?? null,
        ]);
    }

    public function store(Request $request, RekamMedis $rekamMedis)
    {
        $validated = $request->validate([
            'tanggal_kontrol' => 'required|date|after:now',
            'catatan' => 'nullable|string|max:500',
        ], [
            'tanggal_kontrol.required' => 'Tanggal kontrol wajib diisi',
            'tanggal_kontrol.date' => 'Format tanggal tidak valid',
            'tanggal_kontrol.after' => 'Tanggal kontrol harus setelah tanggal saat ini',
            'catatan.max' => 'Catatan maksimal 500 karakter',
        ]);

        DB::beginTransaction();

        try {
            JadwalKontrol::create([
                'rekam_medis_id' => $rekamMedis->id,
                'tanggal_kontrol' => $validated['tanggal_kontrol'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('dashboarddokter')
                ->with('success', 'Jadwal kontrol berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving jadwal kontrol: ' . $e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Gagal menyimpan jadwal kontrol: ' . $e->getMessage()])
                ->withInput();
        }
    }
}