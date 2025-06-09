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
            
            // Get available medicines WITH STOCK INFORMATION
            $availableObat = Obat::select('id', 'nama_obat', 'stok')
                ->where('stok', '>', 0) // Only show medicines with stock > 0
                ->orderBy('nama_obat', 'asc')
                ->get();

            $patientData = [
                'nama' => $rekamMedis->pasien->nama_lengkap ?? '',
                'umur' => $rekamMedis->pasien->umur ?? '',
                'jenisKelamin' => $rekamMedis->pasien->jenis_kelamin ?? ''
            ];

            return Inertia::render('Doctor/ResepObat', [
                'patientData' => $patientData,
                'patientName' => $rekamMedis->pasien->nama_lengkap ?? '',
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
     * Check stock availability for medicines
     */
    public function checkStock(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array',
                'items.*.obat_id' => 'required|exists:obat,id',
                'items.*.jumlah' => 'required|integer|min:1'
            ]);

            $stockStatus = [];
            $hasInsufficientStock = false;
            $insufficientItems = [];

            foreach ($request->items as $item) {
                $obat = Obat::find($item['obat_id']);
                
                if (!$obat) {
                    $stockStatus[] = [
                        'obat_id' => $item['obat_id'],
                        'available' => false,
                        'stock' => 0,
                        'requested' => $item['jumlah'],
                        'message' => 'Obat tidak ditemukan'
                    ];
                    continue;
                }

                $isAvailable = $obat->stok >= $item['jumlah'];
                
                if (!$isAvailable) {
                    $hasInsufficientStock = true;
                    $insufficientItems[] = [
                        'nama_obat' => $obat->nama_obat,
                        'available_stock' => $obat->stok,
                        'requested' => $item['jumlah']
                    ];
                }

                $stockStatus[] = [
                    'obat_id' => $item['obat_id'],
                    'available' => $isAvailable,
                    'stock' => $obat->stok,
                    'requested' => $item['jumlah'],
                    'nama_obat' => $obat->nama_obat,
                    'message' => $isAvailable ? 'Stok tersedia' : 'Stok tidak mencukupi'
                ];
            }

            return response()->json([
                'success' => true,
                'stock_status' => $stockStatus,
                'has_insufficient_stock' => $hasInsufficientStock,
                'insufficient_items' => $insufficientItems
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking stock: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengecek stok'
            ], 500);
        }
    }

    /**
     * Store prescription data
     */
    public function store(Request $request)
    {
        Log::info('=== DEBUG RESEP OBAT ===');
        Log::info('Raw request data:', $request->all());
        
        // Cek tipe resep yang dikirim
        $type = $request->get('type', 'klinik');
        Log::info('Prescription type: ' . $type);

        DB::beginTransaction();
        
        try {
            // Validasi dasar
            $request->validate([
                'rekam_medis_id' => 'required|exists:rekam_medis,id',
                'type' => 'required|in:klinik,luar,both'
            ]);

            // Check if rekam medis exists
            $rekamMedis = RekamMedis::findOrFail($request->rekam_medis_id);
            Log::info('Rekam medis found:', $rekamMedis->toArray());

            if ($type === 'luar') {
                // Handle obat dari luar klinik saja
                return $this->storeObatLuar($request, $rekamMedis);
            } else if ($type === 'klinik') {
                // Handle obat dari klinik saja (existing logic)
                return $this->storeObatKlinik($request, $rekamMedis);
            } else if ($type === 'both') {
                // Handle keduanya sekaligus
                return $this->storeBothObat($request, $rekamMedis);
            }

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

    /**
     * Store prescription for obat dari luar klinik
     */
    private function storeObatLuar(Request $request, RekamMedis $rekamMedis)
    {
        // Validasi khusus untuk obat luar
        $validated = $request->validate([
            'obat_luar' => 'required|string|max:2000'
        ], [
            'obat_luar.required' => 'Daftar obat dari luar klinik wajib diisi',
            'obat_luar.max' => 'Daftar obat terlalu panjang (maksimal 2000 karakter)'
        ]);

        try {
            // Update langsung ke kolom obat_luar menggunakan raw query
            $updateResult = DB::table('rekam_medis')
                ->where('id', $rekamMedis->id)
                ->update([
                    'obat_luar' => trim($validated['obat_luar']),
                    'updated_at' => now()
                ]);

            Log::info('Raw query update result: ' . $updateResult);
            
            // Verify update worked with fresh query
            $freshData = DB::table('rekam_medis')
                ->select('obat_luar')
                ->where('id', $rekamMedis->id)
                ->first();
            
            Log::info('Fresh data - obat_luar: ' . $freshData->obat_luar);

            Log::info('Obat luar saved to rekam_medis.obat_luar:', [
                'rekam_medis_id' => $rekamMedis->id,
                'obat_luar_length' => strlen(trim($validated['obat_luar']))
            ]);

            DB::commit();
            
            return redirect()->back()->with('success', 'Resep obat dari luar klinik berhasil disimpan');

        } catch (\Exception $e) {
            Log::error('Error saving obat luar: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Store prescription for obat dari klinik (existing logic)
     */
    private function storeObatKlinik(Request $request, RekamMedis $rekamMedis)
    {
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
        $validated = $request->validate([
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

        // PRE-VALIDATION: Check stock before starting transaction
        $stockErrors = [];
        foreach ($validated['prescription_items'] as $index => $item) {
            if (($item['dari_klinik'] ?? true) && !empty($item['obat_id'])) {
                $obat = Obat::find($item['obat_id']);
                
                if (!$obat) {
                    $stockErrors[] = "Obat tidak ditemukan pada item resep ke-" . ($index + 1);
                    continue;
                }

                if ($obat->stok < $item['jumlah']) {
                    $stockErrors[] = "Stok tidak cukup untuk obat '{$obat->nama_obat}' pada item ke-" . ($index + 1) . ". Stok tersedia: {$obat->stok}, dibutuhkan: {$item['jumlah']}";
                }
            }
        }

        // If there are stock errors, return them immediately
        if (!empty($stockErrors)) {
            return back()->withErrors([
                'stock_error' => $stockErrors
            ])->with('error', 'Terdapat masalah stok pada beberapa obat. Silakan periksa kembali.');
        }
        
        // Cari atau buat tagihan menggunakan service
        $tagihan = TagihanService::findOrCreateTagihan($request->rekam_medis_id);
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

                // Proses pengurangan stok
                $sisaJumlah = $item['jumlah'];

                $stokBatch = DB::table('stok_obat')
                    ->where('obat_id', $item['obat_id'])
                    ->where('jumlah', '>', 0)
                    ->orderBy('tanggal_kadaluarsa', 'asc')
                    ->lockForUpdate()
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

                // Reduce total stock in obat table
                $obat->stok -= $item['jumlah'];
                $obat->save();

                // Log stock reduction
                Log::info("Stock reduced for obat_id {$item['obat_id']}: -{$item['jumlah']}, remaining: {$obat->stok}");
            }

            // Validasi tambahan
            if (empty($item['obat_id']) && empty(trim($item['nama_obat']))) {
                throw new \Exception("Item resep ke-" . ($index + 1) . " harus memiliki nama obat");
            }

            // Data untuk disimpan ke ResepObat
            $resepData = [
                'rekam_medis_id' => $request->rekam_medis_id,
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
    }

    /**
     * Store both obat klinik and obat luar
     */
    private function storeBothObat(Request $request, RekamMedis $rekamMedis)
    {
        try {
            $messages = [];
            
            // 1. Handle obat luar dulu (lebih sederhana)
            if ($request->has('obat_luar') && !empty($request->obat_luar)) {
                $request->validate([
                    'obat_luar' => 'required|string|max:2000'
                ], [
                    'obat_luar.required' => 'Daftar obat dari luar klinik wajib diisi',
                    'obat_luar.max' => 'Daftar obat terlalu panjang (maksimal 2000 karakter)'
                ]);

                // Update obat luar
                DB::table('rekam_medis')
                    ->where('id', $rekamMedis->id)
                    ->update([
                        'obat_luar' => trim($request->obat_luar),
                        'updated_at' => now()
                    ]);

                $messages[] = 'Resep obat dari luar klinik berhasil disimpan';
                Log::info('Obat luar saved successfully');
            }

            // 2. Handle obat klinik
            if ($request->has('prescription_items') && !empty($request->prescription_items)) {
                // Gunakan method yang sudah ada
                $fakeRequest = new Request();
                $fakeRequest->merge([
                    'rekam_medis_id' => $request->rekam_medis_id,
                    'prescription_items' => $request->prescription_items
                ]);
                
                // Call existing method (tapi jangan commit/rollback di situ)
                $this->storeObatKlinikOnly($fakeRequest, $rekamMedis);
                $messages[] = 'Resep obat dari klinik berhasil disimpan';
                Log::info('Obat klinik saved successfully');
            }

            DB::commit();
            
            $finalMessage = !empty($messages) ? implode(' dan ', $messages) : 'Resep obat berhasil disimpan';
            return redirect()->back()->with('success', $finalMessage);

        } catch (\Exception $e) {
            Log::error('Error saving both obat: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Store obat klinik only (without transaction control)
     */
    private function storeObatKlinikOnly(Request $request, RekamMedis $rekamMedis)
    {
        $prescriptionItems = $request->get('prescription_items', []);
        
        if (empty($prescriptionItems)) {
            return; // Skip jika kosong
        }
        
        // Validasi
        $validated = $request->validate([
            'prescription_items' => 'required|array|min:1',
            'prescription_items.*.obat_id' => 'nullable|exists:obat,id',
            'prescription_items.*.nama_obat' => 'required|string|max:100',
            'prescription_items.*.dosis' => 'required|string|max:100',
            'prescription_items.*.jumlah' => 'required|integer|min:1|max:1000',
            'prescription_items.*.tanggal_mulai' => 'required|date',
            'prescription_items.*.tanggal_terakhir' => 'required|date|after_or_equal:prescription_items.*.tanggal_mulai',
            'prescription_items.*.catatan' => 'nullable|string|max:500',
            'prescription_items.*.dari_klinik' => 'boolean'
        ]);

        // Stock validation
        $stockErrors = [];
        foreach ($validated['prescription_items'] as $index => $item) {
            if (($item['dari_klinik'] ?? true) && !empty($item['obat_id'])) {
                $obat = Obat::find($item['obat_id']);
                
                if (!$obat) {
                    $stockErrors[] = "Obat tidak ditemukan pada item resep ke-" . ($index + 1);
                    continue;
                }

                if ($obat->stok < $item['jumlah']) {
                    $stockErrors[] = "Stok tidak cukup untuk obat '{$obat->nama_obat}' pada item ke-" . ($index + 1);
                }
            }
        }

        if (!empty($stockErrors)) {
            throw new \Exception(implode(', ', $stockErrors));
        }
        
        // Cari atau buat tagihan
        $tagihan = TagihanService::findOrCreateTagihan($request->rekam_medis_id);
        $totalTagihanObat = 0;

        foreach ($validated['prescription_items'] as $index => $item) {
            $hargaSatuan = 0;

            if (($item['dari_klinik'] ?? true) && !empty($item['obat_id'])) {
                $obat = Obat::find($item['obat_id']);

                if (!$obat || $obat->stok < $item['jumlah']) {
                    throw new \Exception("Stok tidak mencukupi untuk obat pada item ke-" . ($index + 1));
                }

                $hargaSatuan = $obat->harga ?? 0;

                // Reduce stock
                $sisaJumlah = $item['jumlah'];
                $stokBatch = DB::table('stok_obat')
                    ->where('obat_id', $item['obat_id'])
                    ->where('jumlah', '>', 0)
                    ->orderBy('tanggal_kadaluarsa', 'asc')
                    ->lockForUpdate()
                    ->get();

                foreach ($stokBatch as $stok) {
                    if ($sisaJumlah <= 0) break;
                    $kurangi = min($stok->jumlah, $sisaJumlah);
                    DB::table('stok_obat')->where('id', $stok->id)->update(['jumlah' => DB::raw("jumlah - {$kurangi}")]);
                    $sisaJumlah -= $kurangi;
                }

                $obat->stok -= $item['jumlah'];
                $obat->save();
            }

            // Save prescription
            $resepData = [
                'rekam_medis_id' => $request->rekam_medis_id,
                'obat_id' => $item['obat_id'] ?? null,
                'nama_obat' => trim($item['nama_obat']),
                'dosis' => trim($item['dosis']),
                'jumlah' => (int)$item['jumlah'],
                'catatan' => !empty($item['catatan']) ? trim($item['catatan']) : null,
                'tanggal_mulai' => $item['tanggal_mulai'],
                'tanggal_terakhir' => $item['tanggal_terakhir'],
                'dari_klinik' => $item['dari_klinik'] ?? true
            ];
            
            $prescription = ResepObat::create($resepData);

            // Create billing if applicable
            $subtotal = $hargaSatuan * (int)$item['jumlah'];
            $totalTagihanObat += $subtotal;

            if (($item['dari_klinik'] ?? true) && $hargaSatuan > 0) {
                TagihanObat::create([
                    'tagihan_id' => $tagihan->id,
                    'resep_obat_id' => $prescription->id,
                    'nama_obat' => trim($item['nama_obat']),
                    'dosis' => trim($item['dosis']),
                    'jumlah' => (int)$item['jumlah'],
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                    'catatan' => !empty($item['catatan']) ? trim($item['catatan']) : null,
                ]);
            }
        }

        // Update total billing
        if ($totalTagihanObat > 0) {
            TagihanService::updateTotalBiaya($tagihan->id, $totalTagihanObat);
        }
    }
    public function getStock($obatId)
    {
        try {
            $obat = Obat::find($obatId);
            
            if (!$obat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Obat tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'stock' => $obat->stok,
                'nama_obat' => $obat->nama_obat
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting stock: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data stok'
            ], 500);
        }
    }
}