<?php

namespace App\Http\Controllers;

use App\Models\JadwalKontrol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PengingatKontrol;

class StaffPengingatController extends Controller
{
    public function index()
    {
        $jadwalKontrol = JadwalKontrol::perluPengingat()
            ->with(['rekamMedis.pasien'])
            ->get()
            ->map(function ($jadwal) {
                $namaPasien = $jadwal->rekamMedis && $jadwal->rekamMedis->pasien
                    ? $jadwal->rekamMedis->pasien->nama_lengkap
                    : 'Nama Tidak Ditemukan';
                $emailPasien = $jadwal->rekamMedis && $jadwal->rekamMedis->pasien
                    ? $jadwal->rekamMedis->pasien->email
                    : '-';

                Log::debug('Memetakan jadwal kontrol:', [
                    'jadwal_id' => $jadwal->id,
                    'rekam_medis_id' => $jadwal->rekam_medis_id,
                    'nama_pasien' => $namaPasien,
                    'email_pasien' => $emailPasien,
                ]);

                return [
                    'id' => $jadwal->id,
                    'nama_pasien' => $namaPasien,
                    'email_pasien' => $emailPasien,
                    'tanggal_kontrol' => $jadwal->tanggal_kontrol->format('d-m-Y'), // Format tanggal saja
                    'catatan' => $jadwal->catatan,
                    'status' => $jadwal->status,
                    'is_reminded' => $jadwal->is_reminded,
                ];
            });

        Log::info('Jadwal kontrol untuk pengingat:', ['count' => $jadwalKontrol->count(), 'data' => $jadwalKontrol->toArray()]);

        return inertia('staff/PengingatKontrol', [
            'jadwalKontrol' => $jadwalKontrol,
        ]);
    }

    public function kirimPengingat(Request $request, JadwalKontrol $jadwalKontrol)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email pasien wajib diisi',
            'email.email' => 'Format email tidak valid',
        ]);

        try {
            if ($jadwalKontrol->status !== 'terjadwal') {
                Log::warning('Jadwal tidak dapat diingatkan:', [
                    'jadwal_id' => $jadwalKontrol->id,
                    'status' => $jadwalKontrol->status,
                ]);
                return redirect()->back()->withErrors(['error' => 'Jadwal tidak dapat diingatkan karena sudah dibatalkan']);
            }

            if ($jadwalKontrol->is_reminded) {
                Log::warning('Pengingat sudah dikirim sebelumnya:', [
                    'jadwal_id' => $jadwalKontrol->id,
                    'email' => $request->email,
                ]);
                return redirect()->back()->withErrors(['error' => 'Pengingat sudah dikirim untuk jadwal ini']);
            }

            if (!$jadwalKontrol->rekamMedis || !$jadwalKontrol->rekamMedis->pasien) {
                Log::error('Relasi rekam medis atau pasien tidak ditemukan:', [
                    'jadwal_id' => $jadwalKontrol->id,
                    'rekam_medis_id' => $jadwalKontrol->rekam_medis_id,
                ]);
                return redirect()->back()->withErrors(['error' => 'Data pasien tidak ditemukan']);
            }

            Log::info('Mengirim pengingat email:', [
                'jadwal_id' => $jadwalKontrol->id,
                'email' => $request->email,
                'nama_pasien' => $jadwalKontrol->rekamMedis->pasien->nama ?? '-',
            ]);

            // Kirim email
            Mail::to($request->email)->send(new PengingatKontrol($jadwalKontrol));

            // Tandai sebagai sudah dikirim
            $jadwalKontrol->update(['is_reminded' => true]);

            Log::info('Pengingat email berhasil dikirim:', [
                'jadwal_id' => $jadwalKontrol->id,
                'email' => $request->email,
            ]);

            return redirect()->back()->with('success', 'Pengingat email berhasil dikirim ke ' . $request->email);

        } catch (\Exception $e) {
            Log::error('Gagal mengirim pengingat email:', [
                'error' => $e->getMessage(),
                'jadwal_id' => $jadwalKontrol->id,
                'email' => $request->email,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withErrors(['error' => 'Gagal mengirim pengingat: ' . $e->getMessage()]);
        }
    }
}