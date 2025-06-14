<?php

namespace App\Mail;

use App\Models\JadwalKontrol;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PengingatKontrol extends Mailable
{
    use Queueable, SerializesModels;

    public $jadwalKontrol;

    public function __construct(JadwalKontrol $jadwalKontrol)
    {
        $this->jadwalKontrol = $jadwalKontrol;
    }

    public function build()
    {
        $namaPasien = $this->jadwalKontrol->rekamMedis && $this->jadwalKontrol->rekamMedis->pasien
            ? $this->jadwalKontrol->rekamMedis->pasien->nama_lengkap
            : 'Pasien Tidak Dikenal';

        $data = [
            'nama_pasien' => $namaPasien,
            'tanggal_kontrol' => $this->jadwalKontrol->tanggal_kontrol_formatted,
            'catatan' => $this->jadwalKontrol->catatan ?? 'Tidak ada catatan tambahan',
            'clinic_name' => config('app.clinic_name', 'Klinik Dr. Reni Juliana'),
        ];

        Log::info('Data untuk email pengingat:', [
            'jadwal_id' => $this->jadwalKontrol->id,
            'rekam_medis_id' => $this->jadwalKontrol->rekam_medis_id,
            'data' => $data,
        ]);

        return $this->view('emails.pengingat-kontrol')
                    ->subject('Pengingat Jadwal Kontrol - ' . $data['clinic_name'])
                    ->with($data);
    }
}