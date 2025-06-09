<?php

namespace App\Http\Controllers;

use App\Models\RekamMedis;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $tagihanStatus = $tagihan ? $tagihan->status : 'menunggu_pembayaran';

        // Prepare patient data
        $patientData = [
            'nama' => $rekamMedis->pasien->nama ?? $rekamMedis->pasien->nama_lengkap ?? 'Nama tidak tersedia',
            'nama_lengkap' => $rekamMedis->pasien->nama_lengkap ?? $rekamMedis->pasien->nama ?? 'Nama tidak tersedia',
            'umur' => $rekamMedis->pasien->umur ?? null,
            'jenisKelamin' => $rekamMedis->pasien->jenis_kelamin ?? null,
            'noTelp' => $rekamMedis->pasien->no_telp ?? null,
            'alamat' => $rekamMedis->pasien->alamat ?? null
        ];

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
            'billNumber' => $billNumber,
            'tagihanStatus' => $tagihanStatus, // Added status
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

    public function invoice($tagihanId)
    {
        try {
            Log::info('Invoice processing started', ['tagihan_id' => $tagihanId]);

            // Validasi ID tagihan
            if (!is_numeric($tagihanId) || $tagihanId <= 0) {
                Log::error('Invalid tagihan ID', ['tagihan_id' => $tagihanId]);
                abort(400, 'ID Tagihan tidak valid');
            }

            $tagihan = Tagihan::with([
                'rekamMedis.pasien',
                'tagihanObat',
                'tagihanTindakan'
            ])->find($tagihanId);

            if (!$tagihan) {
                Log::error('Tagihan not found', ['tagihan_id' => $tagihanId]);
                abort(404, 'Tagihan tidak ditemukan');
            }

            // Pastikan tagihan sudah dibayar
            if ($tagihan->status !== 'sudah_dibayar') {
                Log::warning('Tagihan belum dibayar', ['tagihan_id' => $tagihanId, 'status' => $tagihan->status]);
                return back()->with('error', 'Tagihan belum dibayar');
            }

            Log::info('Tagihan data retrieved successfully', [
                'tagihan_id' => $tagihanId, 
                'nomor_tagihan' => $tagihan->nomor_tagihan,
                'patient_name' => $tagihan->rekamMedis->pasien->nama ?? 'Unknown'
            ]);

            // Persiapkan data untuk PDF
            $medicineItems = [];
            if ($tagihan->tagihanObat) {
                foreach ($tagihan->tagihanObat as $obat) {
                    $medicineItems[] = [
                        'name' => $obat->nama_obat,
                        'quantity' => $obat->jumlah,
                        'unit' => $obat->satuan ?? 'pcs',
                        'price' => $obat->harga_satuan,
                        'subtotal' => $obat->subtotal,
                        'dosis' => $obat->dosis ?? '-',
                        'catatan' => $obat->catatan ?? ''
                    ];
                }
            }

            $treatmentItems = [];
            if ($tagihan->tagihanTindakan) {
                foreach ($tagihan->tagihanTindakan as $tindakan) {
                    $treatmentItems[] = [
                        'name' => $tindakan->nama_tindakan,
                        'quantity' => $tindakan->jumlah,
                        'price' => $tindakan->harga_satuan,
                        'subtotal' => $tindakan->subtotal,
                        'catatan' => $tindakan->catatan ?? ''
                    ];
                }
            }

            $medicineTotal = array_sum(array_column($medicineItems, 'subtotal'));
            $treatmentTotal = array_sum(array_column($treatmentItems, 'subtotal'));

            $pdfData = [
                'tagihan' => $tagihan,
                'patient' => $tagihan->rekamMedis->pasien,
                'medicineItems' => $medicineItems,
                'treatmentItems' => $treatmentItems,
                'medicineTotal' => $medicineTotal,
                'treatmentTotal' => $treatmentTotal,
                'grandTotal' => $medicineTotal + $treatmentTotal,
                'clinicInfo' => [
                    'name' => config('app.clinic_name', 'Klinik Praktek Dr. Reni Juliana Manurung'),
                    'address' => config('app.clinic_address', 'Jl. Kesehatan No. 123, Medan'),
                    'phone' => config('app.clinic_phone', '0822-7484-9745')
                ]
            ];

            // Buat PDF dengan error handling yang lebih baik
            $pdf = Pdf::loadView('pdf.invoice', $pdfData)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'sans-serif',
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true
                ]);

            Log::info('PDF generated successfully', ['tagihan_id' => $tagihanId]);

            $filename = 'invoice-' . $tagihan->nomor_tagihan . '.pdf';
            return $pdf->stream($filename);

        } catch (\Exception $e) {
            Log::error('Error generating invoice', [
                'tagihan_id' => $tagihanId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return user-friendly error response
            return response()->json([
                'error' => 'Gagal menghasilkan invoice',
                'message' => $e->getMessage()
            ], 500);
        }
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
            if ($tagihan->status === 'sudah_dibayar') {
                return back()->with([
                    'flash' => [
                        'success' => false,
                        'message' => 'Tagihan sudah dibayar'
                    ]
                ]);
            }

            $tagihan->update([
                'status' => 'sudah_dibayar',
                'tanggal_bayar' => now(),
                'metode_pembayaran' => $validated['payment_method'],
                'catatan' => $validated['notes'] ?? 'Pembayaran berhasil diproses',
                'subtotal' => $validated['total_amount']
            ]);

            DB::commit();

            Log::info('Payment processed successfully', [
                'tagihan_id' => $tagihan->id,
                'patient_name' => $validated['patient_name'],
                'total_amount' => $validated['total_amount'],
                'rekam_medis_id' => $validated['rekam_medis_id']
            ]);

            // Redirect back dengan flash message dan tagihan_id yang benar
            return back()->with([
                'flash' => [
                    'success' => true,
                    'message' => 'Pembayaran berhasil diproses',
                    'invoice_number' => $tagihan->nomor_tagihan,
                    'tagihan_id' => $tagihan->id // Pastikan tagihan_id tersedia
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error processing payment: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with([
                'flash' => [
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage()
                ]
            ]);
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