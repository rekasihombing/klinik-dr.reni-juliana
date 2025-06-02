<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRekamMedisRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->hasRole('dokter');
    }

    public function rules()
    {
        return [
            'patient_id' => 'required|exists:patient,id',
            'appointment_id' => 'required|exists:appointments,id',
            'no_rekam_medis' => 'required|string|max:50|unique:rekam_medis,no_rekam_medis',
            'tanggal_kunjungan' => 'required|date',
            
            // Anamnesis - optional but with max length
            'keluhan' => 'nullable|string|max:1000',
            'rps' => 'nullable|string|max:2000',
            'rpd' => 'nullable|string|max:2000',
            'alergi' => 'nullable|string|max:500',
            'riwayat_obat' => 'nullable|string|max:1000',
            
            // Pemeriksaan Fisik
            'tekanan_darah' => 'nullable|string|max:20',
            'suhu_tubuh' => 'nullable|string|max:10',
            'nadi' => 'nullable|string|max:20',
            'pernapasan' => 'nullable|string|max:20',
            'berat_badan' => 'nullable|string|max:10',
            'status_gizi' => 'nullable|string|max:50',
            
            // Diagnosa dan Tindakan
            'diagnosa' => 'nullable|string|max:2000',
            'tindakan' => 'nullable|string|max:2000',
            'catatan_dokter' => 'nullable|string|max:2000',
        ];
    }

    public function messages()
    {
        return [
            'patient_id.required' => 'Data pasien harus dipilih.',
            'patient_id.exists' => 'Data pasien tidak valid.',
            'appointment_id.required' => 'Data appointment harus ada.',
            'appointment_id.exists' => 'Data appointment tidak valid.',
            'no_rekam_medis.required' => 'Nomor rekam medis harus diisi.',
            'no_rekam_medis.unique' => 'Nomor rekam medis sudah digunakan.',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan harus diisi.',
            'tanggal_kunjungan.date' => 'Format tanggal kunjungan tidak valid.',
            
            // Custom messages for max length
            'keluhan.max' => 'Keluhan utama maksimal 1000 karakter.',
            'rps.max' => 'RPS maksimal 2000 karakter.',
            'rpd.max' => 'RPD maksimal 2000 karakter.',
            'alergi.max' => 'Riwayat alergi maksimal 500 karakter.',
            'riwayat_obat.max' => 'Riwayat obat maksimal 1000 karakter.',
            'diagnosa.max' => 'Diagnosa maksimal 2000 karakter.',
            'tindakan.max' => 'Tindakan maksimal 2000 karakter.',
            'catatan_dokter.max' => 'Catatan dokter maksimal 2000 karakter.',
        ];
    }

    public function prepareForValidation()
    {
        // Set default values atau clean data sebelum validasi
        $this->merge([
            'tanggal_kunjungan' => $this->tanggal_kunjungan ?: now()->format('Y-m-d'),
            'dokter_id' => auth()->id(),
        ]);
    }
}