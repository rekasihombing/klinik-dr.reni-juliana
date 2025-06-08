<?php

namespace App\Http\Controllers;

use App\Models\RekamMedis;
use App\Models\TindakanMedis;
use App\Models\TindakanPasien;
use App\Models\TagihanTindakan;
use App\Models\Tagihan;
use App\Services\TagihanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TindakanController extends Controller
{
    // Tampilkan form tambah tindakan dengan data master tindakan
    public function create(RekamMedis $rekamMedis)
    {
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
                    'selected' => false
                ];
            });

        return inertia('Doctor/Tindakan', [
            'rekamMedis' => $rekamMedis,
            'patientData' => [
                'nama' => $rekamMedis->pasien->nama ?? null,
                'jenisKelamin' => $rekamMedis->pasien->jenis_kelamin ?? null,
                'umur' => $rekamMedis->pasien->umur ?? null,
            ],
            'tindakanOptions' => $tindakanOptions,
            'clinicName' => config('app.clinic_name', 'Klinik'),
            'patientName' => $rekamMedis->pasien->nama ?? null,
        ]);
    }



    // Simpan tindakan yang dipilih untuk pasien
    public function store(Request $request, RekamMedis $rekamMedis)
    {
        $validated = $request->validate([
            'tindakan' => 'required|array|min:1',
            'tindakan.*.tindakan_id' => 'required|exists:tindakan_medis,tindakan_id',
            'tindakan.*.jumlah' => 'required|integer|min:1',
            'tindakan.*.catatan' => 'nullable|string|max:500'
        ], [
            'tindakan.required' => 'Pilih minimal satu tindakan',
            'tindakan.min' => 'Pilih minimal satu tindakan',
            'tindakan.*.tindakan_id.required' => 'ID tindakan diperlukan',
            'tindakan.*.tindakan_id.exists' => 'Tindakan tidak valid',
            'tindakan.*.jumlah.required' => 'Jumlah tindakan diperlukan',
            'tindakan.*.jumlah.min' => 'Jumlah minimal 1'
        ]);

        DB::beginTransaction();
        
        try {


                  Log::info('=== DEBUG REKAM MEDIS ===');
        Log::info('Rekam Medis ID: ' . $rekamMedis->id);
        Log::info('Rekam Medis Data: ', $rekamMedis->toArray());
        
        // DEBUG: Cek apakah ada pasien_id di rekam_medis
        $rekamMedisFromDB = RekamMedis::find($rekamMedis->id);
        Log::info('Rekam Medis from DB: ', $rekamMedisFromDB->toArray());
        
        // DEBUG: Cek relasi pasien
        try {
            $pasienData = $rekamMedis->pasien;
            Log::info('Pasien Data: ', $pasienData ? $pasienData->toArray() : ['null']);
        } catch (\Exception $e) {
            Log::error('Error loading pasien: ' . $e->getMessage());
        }
            // Cari atau buat tagihan menggunakan service
            $tagihan = TagihanService::findOrCreateTagihan($rekamMedis->id);
            

            $totalTagihanTindakan = 0;

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
                ]);
            }

            // Update total biaya tagihan
            TagihanService::updateTotalBiaya($tagihan->id, $totalTagihanTindakan);

            DB::commit();
            
            $message = 'Tindakan berhasil disimpan';
            if ($totalTagihanTindakan > 0) {
                $message .= ' dan tagihan tindakan senilai Rp ' . number_format($totalTagihanTindakan, 0, ',', '.') . ' telah ditambahkan';
            }

            return redirect()->route('dashboarddokter', $rekamMedis->id)
                ->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error('Validation error in TindakanController:', $e->errors());
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving tindakan: ' . $e->getMessage());
            
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