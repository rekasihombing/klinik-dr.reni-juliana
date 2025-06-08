<?php

namespace App\Http\Controllers;

use App\Models\ResepObat;
use App\Models\RekamMedis;
use App\Models\Obat;
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
        Log::info('Data request:', $request->all());
        
        // Enhanced validation with custom rules
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

        DB::beginTransaction();

        try {
            // Check if rekam medis exists
            $rekamMedis = RekamMedis::findOrFail($validated['rekam_medis_id']);
            $savedPrescriptions = [];

            foreach ($validated['prescription_items'] as $index => $item) {
                // Double-check stock again inside transaction (for race condition protection)
                if (($item['dari_klinik'] ?? true) && !empty($item['obat_id'])) {
                    $obat = Obat::lockForUpdate()->find($item['obat_id']);

                    if (!$obat) {
                        throw new \Exception("Obat tidak ditemukan pada item resep ke-" . ($index + 1));
                    }

                    // Recheck stock after lock
                    if ($obat->stok < $item['jumlah']) {
                        throw new \Exception("Stok tidak cukup untuk obat '{$obat->nama_obat}' pada item ke-" . ($index + 1) . ". Stok tersedia: {$obat->stok}, dibutuhkan: {$item['jumlah']}");
                    }

                    // Reduce stock from stok_obat table (FIFO)
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
                        throw new \Exception("Stok tidak mencukupi meski telah dicoba kurangi dari batch kadaluarsa untuk obat '{$obat->nama_obat}'");
                    }

                    // Reduce total stock in obat table
                    $obat->stok -= $item['jumlah'];
                    $obat->save();

                    // Log stock reduction
                    Log::info("Stock reduced for obat_id {$item['obat_id']}: -{$item['jumlah']}, remaining: {$obat->stok}");
                }

                // Additional validation
                if (empty($item['obat_id']) && empty(trim($item['nama_obat']))) {
                    throw new \Exception("Item resep ke-" . ($index + 1) . " harus memiliki nama obat");
                }

                // Save prescription
                $prescription = ResepObat::create([
                    'rekam_medis_id' => $validated['rekam_medis_id'],
                    'obat_id' => $item['obat_id'] ?? null,
                    'nama_obat' => trim($item['nama_obat']),
                    'dosis' => trim($item['dosis']),
                    'jumlah' => (int)$item['jumlah'],
                    'catatan' => !empty($item['catatan']) ? trim($item['catatan']) : null,
                    'tanggal_mulai' => $item['tanggal_mulai'],
                    'tanggal_terakhir' => $item['tanggal_terakhir'],
                    'dari_klinik' => $item['dari_klinik'] ?? true
                ]);

                $savedPrescriptions[] = $prescription;
            }

            DB::commit();

            return back()->with('success', 'Resep obat berhasil disimpan')->with('message', 'Resep obat telah berhasil disimpan dan stok obat telah diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error('Validation error:', $e->errors());
            
            return back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving prescription: ' . $e->getMessage());
            
            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get updated stock for specific medicine
     */
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