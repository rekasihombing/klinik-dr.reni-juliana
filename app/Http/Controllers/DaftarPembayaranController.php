<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;


class DaftarPembayaranController extends Controller
{
    public function index(Request $request)
    {
        try {
            Log::info('=== PEMBAYARAN CONTROLLER CALLED ===');

            $query = Tagihan::with(['rekamMedis.pasien'])
                ->orderBy('created_at', 'desc');

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->whereHas('rekamMedis.pasien', function ($subQ) use ($search) {
                        $subQ->where('nama_lengkap', 'like', "%{$search}%");
                    })->orWhere('nomor_tagihan', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_tagihan', [
                    $request->input('start_date') . ' 00:00:00',
                    $request->input('end_date') . ' 23:59:59'
                ]);
            } elseif ($request->filled('start_date')) {
                $query->whereDate('tanggal_tagihan', $request->input('start_date'));
            }

            $tagihan = $query->paginate(10)->withQueryString();

            Log::info('=== TAGIHAN QUERY RESULT ===', [
                'total_records' => $tagihan->total(),
                'current_page' => $tagihan->currentPage(),
                'per_page' => $tagihan->perPage(),
                'has_data' => $tagihan->count() > 0,
            ]);

            $responseData = [
                'tagihan' => $tagihan,
                'filters' => [
                    'search' => $request->input('search', ''),
                    'status' => $request->input('status', ''),
                    'start_date' => $request->input('start_date', ''),
                    'end_date' => $request->input('end_date', ''),
                ],
            ];

            return Inertia::render('staff/pembayaran', $responseData);

        } catch (\Exception $e) {
            Log::error('=== ERROR IN PEMBAYARAN CONTROLLER ===', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return Inertia::render('staff/pembayaran', [
                'tagihan' => null,
                'filters' => [
                    'search' => '',
                    'status' => '',
                    'start_date' => '',
                    'end_date' => '',
                ],
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

public function show(Request $request, $id)
{
    try {
        Log::info('=== PEMBAYARAN SHOW CONTROLLER CALLED ===', [
            'tagihan_id' => $id,
        ]);

        $tagihan = Tagihan::with([
            'rekamMedis.pasien',
            'tagihanObat',
            'tagihanTindakan'
        ])->findOrFail($id);

        Log::info('=== TAGIHAN DETAIL LOADED ===', [
            'id' => $tagihan->id,
            'nomor_tagihan' => $tagihan->nomor_tagihan,
            'tagihan_obat_count' => $tagihan->tagihanObat->count(),
            'tagihan_tindakan_count' => $tagihan->tagihanTindakan->count(),
        ]);

        // Check if the request expects JSON (API call)
        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'tagihan' => $tagihan,
            ]);
        }

        // Fallback to Inertia rendering for page requests
        return Inertia::render('staff/pembayaran', [
            'tagihan' => $tagihan
        ]);

    } catch (\Exception $e) {
        Log::error('=== ERROR IN PEMBAYARAN SHOW CONTROLLER ===', [
            'message' => $e->getMessage(),
            'tagihan_id' => $id,
        ]);

        // Return JSON error for API requests
        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat detail tagihan: ' . $e->getMessage(),
            ], 404);
        }

        // Fallback to Inertia error handling
        return back()->with('error', 'Terjadi kesalahan saat memuat detail tagihan: ' . $e->getMessage());
    }
}

    public function store(Request $request, $id)
    {
        try {
            Log::info('=== PEMBAYARAN STORE CALLED ===', ['tagihan_id' => $id]);
            
            $tagihan = Tagihan::findOrFail($id);
            $tagihan->update(['status' => 'sudah_dibayar']);
            
            return back()->with('success', 'Tagihan berhasil ditandai sebagai sudah dibayar');
            
        } catch (\Exception $e) {
            Log::error('=== ERROR IN PEMBAYARAN STORE ===', [
                'message' => $e->getMessage(),
                'tagihan_id' => $id,
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran');
        }
    }
    
public function invoice($id)
{
    Log::info('Redirecting pembayaran/invoice to tagihan/invoice', ['tagihan_id' => $id]);
    return redirect()->route('tagihan.invoice', ['id' => $id]);
}
}