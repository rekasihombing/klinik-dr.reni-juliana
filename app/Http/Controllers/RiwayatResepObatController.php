<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResepObat;
use App\Models\RekamMedis;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RiwayatResepObatController extends Controller
{
    /**
     * Helper function untuk parsing obat luar
     */
    private function parseObatLuar($obatLuarData)
    {
        if (empty($obatLuarData)) {
            return [];
        }

        // Jika string, coba decode JSON dulu
        if (is_string($obatLuarData)) {
            // Cek apakah ini JSON
            $decoded = json_decode($obatLuarData, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
            
            // Jika bukan JSON, parse sebagai text multi-line
            return $this->parseObatLuarFromText($obatLuarData);
        }

        // Jika sudah array
        if (is_array($obatLuarData)) {
            return $obatLuarData;
        }

        return [];
    }

    /**
     * Parse obat luar dari format text
     */
    private function parseObatLuarFromText($textData)
    {
        $obatList = [];
        
        // Split berdasarkan newline
        $lines = array_filter(array_map('trim', explode("\n", $textData)));
        
        foreach ($lines as $index => $line) {
            if (empty($line)) continue;
            
            // Parse setiap baris obat
            // Format contoh: "amoxilin 2x1 hari" atau "paracetamol 500mg 3x sehari"
            $obatInfo = $this->parseObatLine($line);
            $obatInfo['id'] = $index + 1;
            
            $obatList[] = $obatInfo;
        }
        
        return $obatList;
    }

    /**
     * Parse satu baris obat dari text
     */
    private function parseObatLine($line)
    {
        // Patterns untuk extract informasi
        $patterns = [
            // Pattern: "nama_obat dosis frekuensi"
            // Contoh: "amoxilin 500mg 2x1 hari"
            '/^(.+?)\s+(\d+(?:mg|ml|g)?)\s+(.+)$/i',
            // Pattern: "nama_obat frekuensi" 
            // Contoh: "amoxilin 2x1 hari"
            '/^(.+?)\s+(\d+x\d+.*)$/i',
            // Pattern: "nama_obat sisanya"
            '/^(\S+)\s+(.+)$/i'
        ];

        $namaObat = trim($line);
        $dosis = '-';
        $aturanPakai = '-';

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $line, $matches)) {
                $namaObat = trim($matches[1]);
                
                if (count($matches) >= 3) {
                    // Cek apakah match kedua adalah dosis (mengandung mg, ml, g)
                    if (preg_match('/\d+(?:mg|ml|g|tablet|kapsul)/i', $matches[2])) {
                        $dosis = trim($matches[2]);
                        $aturanPakai = isset($matches[3]) ? trim($matches[3]) : '-';
                    } else {
                        // Match kedua adalah aturan pakai
                        $aturanPakai = trim($matches[2]);
                        if (isset($matches[3])) {
                            $aturanPakai .= ' ' . trim($matches[3]);
                        }
                    }
                }
                break;
            }
        }

        return [
            'nama' => $namaObat,
            'dosis' => $dosis,
            'aturan_pakai' => $aturanPakai,
            'catatan' => $aturanPakai
        ];
    }

    /**
     * Menampilkan daftar resep obat untuk pasien yang sedang login
     */
    public function index()
    {
        $user = Auth::user();
        $pasien = $user->patient;

        // Ambil semua resep obat berdasarkan rekam medis pasien
        $resepObat = ResepObat::with(['rekamMedis.appointment', 'obat'])
            ->whereHas('rekamMedis.appointment', function($query) use ($pasien) {
                $query->where('patient_id', $pasien->id);
            })
            ->orderBy('tanggal_mulai', 'desc')
            ->get()
            ->groupBy('rekam_medis_id')
            ->map(function ($resepGroup) {
                $firstResep = $resepGroup->first();
                $rekamMedis = $firstResep->rekamMedis;
                
                // Parse obat luar dengan fungsi helper
                $obatLuar = $this->parseObatLuar($rekamMedis->obat_luar);
                
                // Log untuk debugging
                Log::info('Obat Luar Data:', [
                    'rekam_medis_id' => $rekamMedis->id,
                    'raw_data' => $rekamMedis->obat_luar,
                    'parsed_data' => $obatLuar,
                    'count' => count($obatLuar)
                ]);
                
                return [
                    'rekam_medis_id' => $rekamMedis->id,
                    'no_rekam_medis' => $rekamMedis->no_rekam_medis,
                    'tanggal_kunjungan' => $rekamMedis->tanggal_kunjungan ? 
                                         Carbon::parse($rekamMedis->tanggal_kunjungan)->format('d F Y') : 
                                         Carbon::parse($rekamMedis->created_at)->format('d F Y'),
                    'jam_kunjungan' => $rekamMedis->appointment ? 
                                     Carbon::parse($rekamMedis->appointment->jam_konsultasi)->format('H:i') : 
                                     Carbon::parse($rekamMedis->created_at)->format('H:i'),
                    'diagnosa' => $rekamMedis->diagnosa,
                    'total_obat' => $resepGroup->count(),
                    'total_obat_luar' => count($obatLuar),
                    'total_semua_obat' => $resepGroup->count() + count($obatLuar),
                    'has_obat_luar' => count($obatLuar) > 0,
                    'obat_luar_preview' => $obatLuar, // Tambahkan untuk debugging
                    'status_aktif' => $resepGroup->filter(function($resep) {
                        return Carbon::now()->between(
                            Carbon::parse($resep->tanggal_mulai),
                            Carbon::parse($resep->tanggal_terakhir)
                        );
                    })->count() > 0,
                    'tanggal_terakhir' => $resepGroup->max('tanggal_terakhir'),
                ];
            })
            ->values();

        return Inertia::render('pasien/RiwayatResepObat', [
            'resepObat' => $resepObat,
            'patientName' => $pasien->nama_lengkap ?? $pasien->name,
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung'
        ]);
    }

    /**
     * Menampilkan detail resep obat berdasarkan rekam medis
     */
    public function show($rekamMedisId)
    {
        $user = Auth::user();
        $pasien = $user->patient;

        // Validasi bahwa rekam medis milik pasien yang sedang login
        $rekamMedis = RekamMedis::with(['appointment'])
            ->whereHas('appointment', function($query) use ($pasien) {
                $query->where('patient_id', $pasien->id);
            })
            ->where('id', $rekamMedisId)
            ->firstOrFail();

        // Ambil semua resep obat untuk rekam medis ini
        $resepObat = ResepObat::with(['obat'])
            ->where('rekam_medis_id', $rekamMedisId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($resep) {
                return [
                    'id' => $resep->id,
                    'nama_obat' => $resep->nama_obat,
                    'dosis' => $resep->dosis,
                    'jumlah' => $resep->jumlah,
                    'catatan' => $resep->catatan,
                    'tanggal_mulai' => Carbon::parse($resep->tanggal_mulai)->format('d F Y'),
                    'tanggal_terakhir' => Carbon::parse($resep->tanggal_terakhir)->format('d F Y'),
                    'durasi_hari' => Carbon::parse($resep->tanggal_mulai)->diffInDays(Carbon::parse($resep->tanggal_terakhir)) + 1,
                    'dari_klinik' => $resep->dari_klinik,
                    'status_aktif' => Carbon::now()->between(
                        Carbon::parse($resep->tanggal_mulai),
                        Carbon::parse($resep->tanggal_terakhir)
                    ),
                    'sisa_hari' => Carbon::now()->diffInDays(Carbon::parse($resep->tanggal_terakhir), false),
                    'tipe' => 'resep_dokter'
                ];
            });

        // Parse obat luar dengan fungsi helper
        $obatLuarData = $this->parseObatLuar($rekamMedis->obat_luar);
        $obatLuar = [];

        // Log untuk debugging detail
        Log::info('Detail Obat Luar:', [
            'rekam_medis_id' => $rekamMedis->id,
            'raw_obat_luar' => $rekamMedis->obat_luar,
            'parsed_obat_luar' => $obatLuarData
        ]);

        // Format obat luar untuk ditampilkan
        foreach ($obatLuarData as $index => $obat) {
            // Handle berbagai format data obat luar
            $namaObat = $obat['nama'] ?? 
                       $obat['nama_obat'] ?? 
                       $obat['name'] ?? 
                       'Nama obat tidak tersedia';
            
            $dosis = $obat['dosis'] ?? 
                    $obat['dosage'] ?? 
                    $obat['dose'] ?? '-';
                    
            $jumlah = $obat['jumlah'] ?? 
                     $obat['quantity'] ?? 
                     $obat['qty'] ?? '-';
                     
            $catatan = $obat['catatan'] ?? 
                      $obat['aturan_pakai'] ??
                      $obat['keterangan'] ?? 
                      $obat['notes'] ?? 
                      $obat['note'] ?? '-';

            $obatLuar[] = [
                'id' => 'obat_luar_' . ($obat['id'] ?? $index),
                'nama_obat' => $namaObat,
                'dosis' => $dosis,
                'jumlah' => $jumlah,
                'catatan' => $catatan,
                'tanggal_mulai' => $rekamMedis->tanggal_kunjungan ? 
                                 Carbon::parse($rekamMedis->tanggal_kunjungan)->format('d F Y') : 
                                 Carbon::parse($rekamMedis->created_at)->format('d F Y'),
                'tanggal_terakhir' => '-',
                'durasi_hari' => '-',
                'dari_klinik' => false,
                'status_aktif' => false,
                'sisa_hari' => null,
                'tipe' => 'obat_luar'
            ];
        }

        return Inertia::render('pasien/DetailResepObat', [
            'resepObat' => $resepObat,
            'obatLuar' => $obatLuar,
            'rekamMedis' => [
                'id' => $rekamMedis->id,
                'no_rekam_medis' => $rekamMedis->no_rekam_medis,
                'tanggal_kunjungan' => $rekamMedis->tanggal_kunjungan ? 
                                     Carbon::parse($rekamMedis->tanggal_kunjungan)->format('d F Y') : 
                                     Carbon::parse($rekamMedis->created_at)->format('d F Y'),
                'jam_kunjungan' => $rekamMedis->appointment ? 
                                 Carbon::parse($rekamMedis->appointment->jam_konsultasi)->format('H:i') : 
                                 Carbon::parse($rekamMedis->created_at)->format('H:i'),
                'diagnosa' => $rekamMedis->diagnosa,
                'keluhan' => $rekamMedis->keluhan,
                'catatan_dokter' => $rekamMedis->catatan_dokter,
                'obat_luar_raw' => $rekamMedis->obat_luar, // Untuk debugging
            ],
            'patientName' => $pasien->nama_lengkap ?? $pasien->name,
            'clinicName' => 'Klinik Praktek Dr. Reni Juliana Manurung',
            'doctorName' => 'Dr. Reni Juliana Manurung'
        ]);
    }

    /**
     * Method untuk debugging - cek format data obat_luar
     */
    public function debugObatLuar($rekamMedisId)
    {
        $rekamMedis = RekamMedis::findOrFail($rekamMedisId);
        
        return response()->json([
            'rekam_medis_id' => $rekamMedis->id,
            'obat_luar_raw' => $rekamMedis->obat_luar,
            'obat_luar_type' => gettype($rekamMedis->obat_luar),
            'is_empty' => empty($rekamMedis->obat_luar),
            'is_null' => is_null($rekamMedis->obat_luar),
            'json_decode_result' => json_decode($rekamMedis->obat_luar, true),
            'json_last_error' => json_last_error_msg()
        ]);
    }
}