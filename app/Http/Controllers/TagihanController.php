<?php

namespace App\Http\Controllers;

use App\Models\RekamMedis;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TagihanController extends Controller
{
    public function create(Request $request)
    {
        try {
            $rekamMedisId = $request->get('rekam_medis_id');
            Log::info('TagihanController::create called', [
                'rekam_medis_id' => $rekamMedisId,
                'request_params' => $request->all()
            ]);

            if (!$rekamMedisId) {
                Log::error('rekam_medis_id is missing');
                return back()->with('error', 'ID Rekam Medis tidak ditemukan');
            }

            // Fetch RekamMedis with related pasien data
            $rekamMedis = RekamMedis::with('pasien')->find($rekamMedisId);
            if (!$rekamMedis) {
                Log::error('RekamMedis not found for ID: ' . $rekamMedisId);
                return back()->with('error', 'Rekam Medis tidak ditemukan');
            }

            // Fetch or check Tagihan
            $tagihan = Tagihan::where('rekam_medis_id', $rekamMedisId)->first();
            $hasExistingTagihan = $tagihan !== null;
            $tagihanId = $tagihan ? $tagihan->id : null;
            $billNumber = $tagihan ? $tagihan->nomor_tagihan : null;

            // Prepare patient data with better error handling
            $patientData = [
                'nama' => $rekamMedis->pasien->nama ?? $rekamMedis->pasien->nama_lengkap ?? 'Nama tidak tersedia',
                'nama_lengkap' => $rekamMedis->pasien->nama_lengkap ?? $rekamMedis->pasien->nama ?? 'Nama tidak tersedia',
                'umur' => $rekamMedis->pasien->umur ?? null,
                'jenisKelamin' => $rekamMedis->pasien->jenis_kelamin ?? null,
                'noTelp' => $rekamMedis->pasien->no_telp ?? null,
                'alamat' => $rekamMedis->pasien->alamat ?? null
            ];

            // Debug log for patient data
            Log::info('Patient data prepared', [
                'patient_data' => $patientData,
                'rekam_medis_id' => $rekamMedisId,
                'pasien_id' => $rekamMedis->pasien->id ?? 'null'
            ]);

            // Fetch medicine and treatment items
            $medicineItems = $this->prepareMedicineItems($tagihan);
            $treatmentItems = $this->prepareTreatmentItems($tagihan);

            // Calculate totals
            $medicineTotal = array_sum(array_column($medicineItems, 'subtotal'));
            $treatmentTotal = array_sum(array_column($treatmentItems, 'subtotal'));
            $totalAmount = $medicineTotal + $treatmentTotal;

            $responseData = [
                'patientData' => $patientData,
                'rekamMedisId' => $rekamMedisId,
                'tagihanId' => $tagihanId,
                'medicineItems' => $medicineItems,
                'treatmentItems' => $treatmentItems,
                'medicineTotal' => $medicineTotal,
                'treatmentTotal' => $treatmentTotal,
                'totalAmount' => $totalAmount,
                'clinicInfo' => [
                    'name' => config('app.clinic_name', 'Klinik Praktek Dr. Reni Juliana Manurung'),
                    'address' => config('app.clinic_address', 'Jl. Kesehatan No. 123, Medan'),
                    'phone' => config('app.clinic_phone', '0822-7484-9745')
                ],
                'hasExistingTagihan' => $hasExistingTagihan,
                'billNumber' => $billNumber
            ];

            Log::info('Tagihan create response data', $responseData);

            return Inertia::render('staff/Tagihan', $responseData);

        } catch (\Exception $e) {
            Log::error('Error in TagihanController->create', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function prepareMedicineItems($tagihan)
    {
        $medicineItems = [];
        if ($tagihan && $tagihan->tagihanObat()->exists()) {
            foreach ($tagihan->tagihanObat as $tagihanObat) {
                // Get satuan from related obat or resep_obat, fallback to default
                $satuan = 'pcs'; // default value
                
                // Try to get satuan from resep_obat relationship if exists
                if ($tagihanObat->resepObat && $tagihanObat->resepObat->obat) {
                    $satuan = $tagihanObat->resepObat->obat->satuan ?? 'pcs';
                }
                
                // Override with direct satuan if available in tagihan_obat table
                if (isset($tagihanObat->satuan) && !empty($tagihanObat->satuan)) {
                    $satuan = $tagihanObat->satuan;
                }

                $medicineItems[] = [
                    'id' => $tagihanObat->id,
                    'name' => $tagihanObat->nama_obat,
                    'quantity' => $tagihanObat->jumlah,
                    'price' => $tagihanObat->harga_satuan,
                    'unit' => $satuan,
                    'subtotal' => $tagihanObat->subtotal,
                    'dosis' => $tagihanObat->dosis ?? '-',
                    'aturan_pakai' => $tagihanObat->catatan ?? ''
                ];
            }
        }
        Log::info('Prepared medicine items', ['items' => $medicineItems]);
        return $medicineItems;
    }

    private function prepareTreatmentItems($tagihan)
    {
        $treatmentItems = [];
        if ($tagihan && $tagihan->tagihanTindakan()->exists()) {
            foreach ($tagihan->tagihanTindakan as $tagihanTindakan) {
                $treatmentItems[] = [
                    'id' => $tagihanTindakan->id,
                    'name' => $tagihanTindakan->nama_tindakan,
                    'price' => $tagihanTindakan->harga_satuan,
                    'quantity' => $tagihanTindakan->jumlah,
                    'subtotal' => $tagihanTindakan->subtotal,
                    'keterangan' => $tagihanTindakan->catatan ?? ''
                ];
            }
        }
        Log::info('Prepared treatment items', ['items' => $treatmentItems]);
        return $treatmentItems;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rekam_medis_id' => 'required|exists:rekam_medis,id',
            'tagihan_id' => 'required|exists:tagihan,id',
            'patient_name' => 'required|string|max:100',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,transfer,card,insurance',
            'notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            $tagihan = Tagihan::findOrFail($validated['tagihan_id']);
            if ($tagihan->status === 'lunas') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tagihan sudah lunas'
                ], 400);
            }

            $rekamMedis = RekamMedis::findOrFail($validated['rekam_medis_id']);

            $tagihan->update([
                'status' => 'lunas',
                'tanggal_bayar' => now(),
                'metode_pembayaran' => $validated['payment_method'],
                'catatan' => $validated['notes'] ?? 'Pembayaran berhasil diproses',
                'subtotal' => $validated['total_amount']
            ]);

            $rekamMedis->update(['status' => 'completed']);

            DB::commit();

            Log::info('Payment processed successfully', [
                'tagihan_id' => $tagihan->id,
                'patient_name' => $validated['patient_name'],
                'total_amount' => $validated['total_amount']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses',
                'invoice_number' => $tagihan->nomor_tagihan,
                'tagihan_id' => $tagihan->id,
                'redirect_url' => route('tagihan.invoice', $tagihan->id)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error processing payment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function invoice($tagihanId)
    {
        try {
            $tagihan = Tagihan::with([
                'rekamMedis.pasien:id,nama,nama_lengkap,umur,jenis_kelamin,no_telp,alamat',
                'tagihanObat:id,tagihan_id,nama_obat,jumlah,harga_satuan,subtotal,dosis,catatan,satuan',
                'tagihanTindakan:id,tagihan_id,nama_tindakan,jumlah,harga_satuan,subtotal,catatan'
            ])->findOrFail($tagihanId);

            return Inertia::render('staff/Invoice', [
                'tagihan' => $tagihan,
                'clinicInfo' => [
                    'name' => config('app.clinic_name', 'Klinik Praktek Dr. Reni Juliana Manurung'),
                    'address' => config('app.clinic_address', 'Jl. Kesehatan No. 123, Medan'),
                    'phone' => config('app.clinic_phone', '0822-7484-9745')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading invoice: ' . $e->getMessage());
            return back()->with('error', 'Invoice tidak ditemukan');
        }
    }

    public function index(Request $request)
    {
        try {
            $query = Tagihan::with(['rekamMedis.pasien:id,nama,nama_lengkap'])
                ->orderBy('created_at', 'desc');

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('rekamMedis.pasien', function ($subQ) use ($search) {
                        $subQ->where('nama', 'like', "%{$search}%")
                             ->orWhere('nama_lengkap', 'like', "%{$search}%");
                    })->orWhere('nomor_tagihan', 'like', "%{$search}%");
                });
            }

            $tagihan = $query->paginate(20)->withQueryString();

            return Inertia::render('staff/Pembayaran', [
                'tagihan' => $tagihan,
                'filters' => $request->only(['start_date', 'end_date', 'status', 'search'])
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading billing history: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat riwayat tagihan');
        }
    }

    public function show($tagihanId)
    {
        try {
            $tagihan = Tagihan::with([
                'rekamMedis.pasien:id,nama,nama_lengkap,umur,jenis_kelamin,no_telp,alamat',
                'tagihanObat:id,tagihan_id,nama_obat,jumlah,harga_satuan,subtotal,dosis,catatan,satuan',
                'tagihanTindakan:id,tagihan_id,nama_tindakan,jumlah,harga_satuan,subtotal,catatan'
            ])->findOrFail($tagihanId);

            return Inertia::render('staff/TagihanDetail', [
                'tagihan' => $tagihan,
                'clinicInfo' => [
                    'name' => config('app.clinic_name', 'Klinik Praktek Dr. Reni Juliana Manurung'),
                    'address' => config('app.clinic_address', 'Jl. Kesehatan No. 123, Medan'),
                    'phone' => config('app.clinic_phone', '0822-7484-9745')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading billing detail: ' . $e->getMessage());
            return back()->with('error', 'Detail tagihan tidak ditemukan');
        }
    }
}