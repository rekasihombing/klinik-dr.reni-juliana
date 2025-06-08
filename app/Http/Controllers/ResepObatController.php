<?php

namespace App\Http\Controllers;

use App\Models\ResepObat;
use App\Models\RekamMedis;
use App\Models\Obat;
use App\Models\TagihanObat;
use App\Models\Tagihan;
use App\Services\TagihanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ResepObatController extends Controller
{
    /**
     * Display the prescription form
     */
    public function create(Request $request)
    {
        try {
            // Get rekam medis ID from request
            $rekamMedisId = $request->get('rekam_medis_id');
            
            if (!$rekamMedisId) {
                return redirect()->back()->with('error', 'ID Rekam Medis tidak ditemukan');
            }

            // Get patient data from rekam medis
            $rekamMedis = RekamMedis::with('pasien')->findOrFail($rekamMedisId);
            
            // Get available medicines
            $availableObat = Obat::select('id', 'nama_obat')
                ->orderBy('nama_obat', 'asc')
                ->get();

            $patientData = [
                'nama' => $rekamMedis->pasien->nama ?? '',
                'umur' => $rekamMedis->pasien->umur ?? '',
                'jenisKelamin' => $rekamMedis->pasien->jenis_kelamin ?? ''
            ];

            return Inertia::render('Doctor/ResepObat', [
                'patientData' => $patientData,
                'patientName' => $rekamMedis->pasien->nama ?? '',
                'clinicName' => config('app.clinic_name', 'Klinik Praktek Dr. Reni Juliana Manurung'),
                'rekamMedisId' => $rekamMedisId,
                'availableObat' => $availableObat
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading prescription form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat form resep');
        }
    }



    /**
     * Store prescription data
     */
public function store(Request $request)
{
    Log::info('=== DEBUG RESEP OBAT ===');
    Log::info('Raw request data:', $request->all());
    
    // Debug: Cek apakah ada prescription_items
    if (!$request->has('prescription_items')) {
        Log::error('prescription_items tidak ada dalam request');
        return redirect()->back()->with('error', 'Data resep tidak ditemukan dalam request');
    }
    
    $prescriptionItems = $request->get('prescription_items', []);
    Log::info('Prescription items count: ' . count($prescriptionItems));
    Log::info('Prescription items data:', $prescriptionItems);
    
    // Cek apakah array kosong
    if (empty($prescriptionItems)) {
        Log::error('prescription_items kosong');
        return redirect()->back()->with('error', 'Tidak ada item resep yang dikirim');
    }
    
    // Validasi dengan debug
    try {
        $validated = $request->validate([
            'rekam_medis_id' => 'required|exists:rekam_medis,id',
            'prescription_items' => 'required|array|min:1',
            'prescription_items.*.obat_id' => 'nullable|exists:obat,id',
            'prescription_items.*.nama_obat' => 'required|string|max:100',
            'prescription_items.*.dosis' => 'required|string|max:100',
            'prescription_items.*.jumlah' => 'required|integer|min:1|max:1000',
            'prescription_items.*.tanggal_mulai' => 'required|date',
            'prescription_items.*.tanggal_terakhir' => 'required|date|after_or_equal:prescription_items.*.tanggal_mulai',
            'prescription_items.*.catatan' => 'nullable|string|max:500',
            'prescription_items.*.dari_klinik' => 'boolean'
        ], [
            'prescription_items.*.nama_obat.required' => 'Nama obat wajib diisi',
            'prescription_items.*.dosis.required' => 'Dosis obat wajib diisi',
            'prescription_items.*.jumlah.required' => 'Jumlah obat wajib diisi',
            'prescription_items.*.jumlah.integer' => 'Jumlah obat harus berupa angka',
            'prescription_items.*.jumlah.min' => 'Jumlah obat minimal 1',
            'prescription_items.*.tanggal_mulai.required' => 'Tanggal mulai wajib diisi',
            'prescription_items.*.tanggal_terakhir.required' => 'Tanggal berakhir wajib diisi',
            'prescription_items.*.tanggal_terakhir.after_or_equal' => 'Tanggal berakhir harus sama atau setelah tanggal mulai',
        ]);
        
        Log::info('Validation passed:', $validated);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed:', $e->errors());
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
    }

    DB::beginTransaction();

    try {
        // Check if rekam medis exists
        $rekamMedis = RekamMedis::findOrFail($validated['rekam_medis_id']);
        Log::info('Rekam medis found:', $rekamMedis->toArray());
        
        // Cari atau buat tagihan menggunakan service
        $tagihan = TagihanService::findOrCreateTagihan($validated['rekam_medis_id']);
        Log::info('Tagihan created/found:', $tagihan->toArray());

        $savedPrescriptions = [];
        $totalTagihanObat = 0;

        foreach ($validated['prescription_items'] as $index => $item) {
            Log::info("Processing item {$index}:", $item);
            
            $hargaSatuan = 0;

            if (($item['dari_klinik'] ?? true) && !empty($item['obat_id'])) {
                Log::info("Processing obat dari klinik dengan ID: " . $item['obat_id']);
                
                $obat = Obat::find($item['obat_id']);

                if (!$obat) {
                    throw new \Exception("Obat tidak ditemukan pada item resep ke-" . ($index + 1));
                }

                if ($obat->stok < $item['jumlah']) {
                    throw new \Exception("Stok tidak cukup untuk obat '{$obat->nama_obat}' pada item ke-" . ($index + 1) . ". Stok tersedia: {$obat->stok}, dibutuhkan: {$item['jumlah']}");
                }

                // Set harga satuan dari data obat
                $hargaSatuan = $obat->harga ?? 0;
                Log::info("Harga satuan: {$hargaSatuan}");

                // Proses pengurangan stok (kode yang sama seperti sebelumnya)
                $sisaJumlah = $item['jumlah'];

                $stokBatch = DB::table('stok_obat')
                    ->where('obat_id', $item['obat_id'])
                    ->where('jumlah', '>', 0)
                    ->orderBy('tanggal_kadaluarsa', 'asc')
                    ->get();

                foreach ($stokBatch as $stok) {
                    if ($sisaJumlah <= 0) break;

                    $kurangi = min($stok->jumlah, $sisaJumlah);

                    DB::table('stok_obat')
                        ->where('id', $stok->id)
                        ->update([
                            'jumlah' => DB::raw("jumlah - {$kurangi}"),
                            'updated_at' => now()
                        ]);

                    $sisaJumlah -= $kurangi;
                }

                if ($sisaJumlah > 0) {
                    throw new \Exception("Stok tidak mencukupi meski telah dicoba kurangi dari batch kadaluarsa.");
                }

                // Kurangi stok total di tabel obat (agregat)
                $obat->stok -= $item['jumlah'];
                $obat->save();
            }

            // Validasi tambahan
            if (empty($item['obat_id']) && empty(trim($item['nama_obat']))) {
                throw new \Exception("Item resep ke-" . ($index + 1) . " harus memiliki nama obat");
            }

            // Data untuk disimpan ke ResepObat
            $resepData = [
                'rekam_medis_id' => $validated['rekam_medis_id'],
                'obat_id' => $item['obat_id'] ?? null,
                'nama_obat' => trim($item['nama_obat']),
                'dosis' => trim($item['dosis']),
                'jumlah' => (int)$item['jumlah'],
                'catatan' => !empty($item['catatan']) ? trim($item['catatan']) : null,
                'tanggal_mulai' => $item['tanggal_mulai'],
                'tanggal_terakhir' => $item['tanggal_terakhir'],
                'dari_klinik' => $item['dari_klinik'] ?? true
            ];
            
            Log::info("Saving ResepObat with data:", $resepData);

            // Simpan resep
            $prescription = ResepObat::create($resepData);
            Log::info("ResepObat saved with ID: " . $prescription->id);

            $savedPrescriptions[] = $prescription;

            // Hitung subtotal untuk tagihan
            $subtotal = $hargaSatuan * (int)$item['jumlah'];
            $totalTagihanObat += $subtotal;

            // Simpan ke tagihan_obat jika obat dari klinik dan ada harga
            if (($item['dari_klinik'] ?? true) && $hargaSatuan > 0) {
                $tagihanObatData = [
                    'tagihan_id' => $tagihan->id,
                    'resep_obat_id' => $prescription->id,
                    'nama_obat' => trim($item['nama_obat']),
                    'dosis' => trim($item['dosis']),
                    'jumlah' => (int)$item['jumlah'],
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                    'catatan' => !empty($item['catatan']) ? trim($item['catatan']) : null,
                ];
                
                Log::info("Saving TagihanObat with data:", $tagihanObatData);
                
                TagihanObat::create($tagihanObatData);
            }
        }

        // Update total biaya tagihan jika ada tagihan obat
        if ($totalTagihanObat > 0) {
            Log::info("Updating total tagihan dengan jumlah: {$totalTagihanObat}");
            TagihanService::updateTotalBiaya($tagihan->id, $totalTagihanObat);
        }

        DB::commit();
        Log::info("Transaction committed successfully");

        $message = 'Resep obat berhasil disimpan';
        if ($totalTagihanObat > 0) {
            $message .= ' dan tagihan obat senilai Rp ' . number_format($totalTagihanObat, 0, ',', '.') . ' telah ditambahkan';
        }

        return redirect()->back()->with('success', $message);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollback();
        Log::error('Validation error:', $e->errors());
        
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
            
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Error saving prescription: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan saat menyimpan resep: ' . $e->getMessage())
            ->withInput();
    }
}
}