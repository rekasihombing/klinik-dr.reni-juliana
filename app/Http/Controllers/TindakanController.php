<?php

namespace App\Http\Controllers;

use App\Models\RekamMedis;
use App\Models\TindakanMedis;
use App\Models\TindakanPasien;
use App\Models\TagihanTindakan;
use App\Models\Tagihan;
use App\Models\Appointment;
use App\Services\TagihanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TindakanController extends Controller
{
    // Tampilkan form tambah tindakan dengan data master tindakan
public function create(RekamMedis $rekamMedis)
    {
        // Pastikan relasi pasien di-load
        $rekamMedis->load('pasien');

        // Validasi bahwa pasien ada
        if (!$rekamMedis->pasien) {
            Log::error('Pasien tidak ditemukan untuk rekam medis ID: ' . $rekamMedis->id);
            return redirect()->route('dashboarddokter')->withErrors(['error' => 'Data pasien tidak ditemukan']);
        }

        // Ambil semua master tindakan medis yang tersedia
        $tindakanOptions = TindakanMedis::select('tindakan_id', 'nama_tindakan', 'tarif')
            ->orderBy('nama_tindakan')
            ->get()
            ->map(function($item) {
                return [
                    'tindakan_id' => $item->tindakan_id,
                    'nama_tindakan' => $item->nama_tindakan,
                    'tarif' => $item->tarif,
                    'jumlah' => 1,
                    'selected' => false,
                    'catatan' => null,
                ];
            });

        // Ambil informasi appointment (jika ada)
        $appointment = $rekamMedis->appointment_id ? Appointment::find($rekamMedis->appointment_id) : null;

        // Ambil umur dari kolom umur atau hitung dari tanggal_lahir
        $umur = $rekamMedis->pasien->umur ?? ($rekamMedis->pasien->tanggal_lahir ? Carbon::parse($rekamMedis->pasien->tanggal_lahir)->age : null);

        // Log untuk debugging
        Log::info('Tindakan Create Data', [
            'rekam_medis_id' => $rekamMedis->id,
            'patient_data' => [
                'id' => $rekamMedis->pasien->id,
                'nama' => $rekamMedis->pasien->nama_lengkap ?? 'Tidak Diketahui',
                'umur' => $umur ?? 'Tidak Diketahui',
                'umur_from_db' => $rekamMedis->pasien->umur, // Log nilai asli dari DB
                'tanggal_lahir' => $rekamMedis->pasien->tanggal_lahir ?? 'Tidak Diketahui',
                'jenis_kelamin' => $rekamMedis->pasien->jenis_kelamin ?? 'Tidak Diketahui',
            ],
            'appointment_id' => $rekamMedis->appointment_id,
        ]);

        return inertia('Doctor/Tindakan', [
            'rekamMedis' => $rekamMedis,
            'patientData' => [
                'nama' => $rekamMedis->pasien->nama_lengkap ?? 'Pasien Tidak Dikenal',
                'umur' => $umur, // Gunakan umur yang dihitung atau dari DB
                'jenisKelamin' => $rekamMedis->pasien->jenis_kelamin ?? '-',
            ],
            'tindakanOptions' => $tindakanOptions,
            'clinicName' => config('app.clinic_name', 'Klinik Dr. Reni Juliana'),
            'patientName' => $rekamMedis->pasien->nama_lengkap ?? 'Pasien Tidak Dikenal',
            'isStaffCreated' => $appointment ? $appointment->dibuat_oleh === 'staff' : false,
        ]);
    }

    // Simpan tindakan yang dipilih untuk pasien
public function store(Request $request, RekamMedis $rekamMedis)
{
    $validated = $request->validate([
        'tindakan' => 'required|array|min:1',
        'tindakan.*.tindakan_id' => 'required|exists:tindakan_medis,tindakan_id',
        'tindakan.*.jumlah' => 'required|integer|min:1|max:99',
        'tindakan.*.catatan' => 'nullable|string|max:500',
        'perlu_kontrol' => 'boolean',
    ], [
        'tindakan.required' => 'Pilih minimal satu tindakan',
        'tindakan.min' => 'Pilih minimal satu tindakan',
        'tindakan.*.tindakan_id.required' => 'ID tindakan diperlukan',
        'tindakan.*.tindakan_id.exists' => 'Tindakan tidak valid',
        'tindakan.*.jumlah.required' => 'Jumlah tindakan diperlukan',
        'tindakan.*.jumlah.min' => 'Jumlah minimal 1',
        'tindakan.*.jumlah.max' => 'Jumlah maksimal 99',
        'tindakan.*.catatan.max' => 'Catatan maksimal 500 karakter',
        'perlu_kontrol.boolean' => 'Pilihan kontrol ulang tidak valid',
    ]);

    DB::beginTransaction();
    
    try {
        Log::info("=== TINDAKAN STORE START ===");
        Log::info("Rekam Medis ID: {$rekamMedis->id}");
        Log::info("Tindakan Data: ", $validated['tindakan']);
        Log::info("Perlu Kontrol: " . ($validated['perlu_kontrol'] ? 'Ya' : 'Tidak'));
        
        // Validasi rekam medis masih aktif
        if ($rekamMedis->status === 'selesai') {
            throw new \Exception("Rekam medis sudah dalam status selesai, tidak dapat menambah tindakan");
        }
        
        // Cari atau buat tagihan menggunakan service
        $tagihan = TagihanService::findOrCreateTagihan($rekamMedis->id);
        Log::info("Tagihan ID: {$tagihan->id}");
        
        $totalTagihanTindakan = 0;
        $tindakanDisimpan = [];

        foreach ($validated['tindakan'] as $item) {
            // Ambil tarif dari master tindakan
            $tindakanMedis = TindakanMedis::find($item['tindakan_id']);
            
            if (!$tindakanMedis) {
                throw new \Exception("Tindakan medis dengan ID {$item['tindakan_id']} tidak ditemukan");
            }

            $subtotal = $tindakanMedis->tarif * $item['jumlah'];
            $totalTagihanTindakan += $subtotal;

            // Simpan ke tindakan_pasien
            $tindakanPasien = TindakanPasien::create([
                'rekam_medis_id' => $rekamMedis->id,
                'tindakan_id' => $item['tindakan_id'],
                'jumlah' => $item['jumlah'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Simpan ke tagihan_tindakan
            TagihanTindakan::create([
                'tagihan_id' => $tagihan->id,
                'tindakan_pasien_id' => $tindakanPasien->id,
                'nama_tindakan' => $tindakanMedis->nama_tindakan,
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $tindakanMedis->tarif,
                'subtotal' => $subtotal,
                'catatan' => !empty($item['catatan']) ? trim($item['catatan']) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $tindakanDisimpan[] = $tindakanMedis->nama_tindakan . ' (x' . $item['jumlah'] . ')';
            
            Log::info("Tindakan disimpan: {$tindakanMedis->nama_tindakan} x{$item['jumlah']} = Rp{$subtotal}");
        }

        // Update total biaya tagihan
        TagihanService::updateTotalBiaya($tagihan->id, $totalTagihanTindakan);
        Log::info("Total tagihan tindakan: Rp{$totalTagihanTindakan}");

        // Update status appointment menjadi selesai jika ada
        $appointmentMessage = '';
        if (!empty($rekamMedis->appointment_id)) {
            $appointmentUpdated = Appointment::where('id', $rekamMedis->appointment_id)
                ->where('status', '!=', 'selesai')
                ->update([
                    'status' => 'selesai',
                    'updated_at' => now()
                ]);
            
            if ($appointmentUpdated) {
                $appointmentMessage = ' Status appointment telah diubah menjadi selesai.';
                Log::info("Appointment {$rekamMedis->appointment_id} status updated to selesai");
            }
        }

        DB::commit();
        Log::info("=== TINDAKAN STORE SUCCESS ===");
        
        // Buat pesan sukses
        $message = 'Berhasil menyimpan ' . count($tindakanDisimpan) . ' tindakan: ' . 
                   implode(', ', $tindakanDisimpan);
        
        if ($totalTagihanTindakan > 0) {
            $message .= '. Total biaya tindakan: Rp ' . number_format($totalTagihanTindakan, 0, ',', '.');
        }
        
        $message .= $appointmentMessage;

        // LOGIC BARU: Cek apakah appointment dibuat oleh staff
        $appointment = $rekamMedis->appointment_id ? Appointment::find($rekamMedis->appointment_id) : null;
        $isStaffCreated = $appointment && $appointment->dibuat_oleh === 'staff';

        if ($validated['perlu_kontrol'] && !$isStaffCreated) {
            // Jika centang kontrol dan BUKAN dibuat oleh staff -> ke halaman jadwal kontrol
            Log::info("Redirecting to jadwal kontrol for rekam_medis_id: {$rekamMedis->id}");
            
            return redirect()->route('jadwal-kontrol.create', $rekamMedis->id)
                ->with('success', $message . ' Silakan atur jadwal kontrol ulang.');
        } else {
            // Jika tidak centang atau appointment dibuat oleh staff -> ke dashboard
            Log::info("Redirecting to dashboarddokter for rekam_medis_id: {$rekamMedis->id}");
            return redirect()->route('dashboarddokter')
                ->with('success', $message . ' Tindakan selesai.');
        }

    } catch (\Exception $e) {
        DB::rollback();
        Log::error("Error saving tindakan: {$e->getMessage()}");
        
        return redirect()->back()
            ->withErrors(['error' => 'Gagal menyimpan tindakan: ' . $e->getMessage()])
            ->withInput();
    }
}

    // Method untuk mendapatkan data tindakan via AJAX (opsional)
    public function getTindakanOptions()
    {
        try {
            $tindakan = TindakanMedis::select('tindakan_id', 'nama_tindakan', 'tarif')
                ->orderBy('nama_tindakan')
                ->get();
                
            return response()->json($tindakan);
        } catch (\Exception $e) {
            Log::error('Error getting tindakan options: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat data tindakan'], 500);
        }
    }
}