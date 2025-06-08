<?php

namespace App\Services;

use App\Models\RekamMedis;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TagihanService
{
    /**
     * Generate nomor tagihan yang unik
     */
    public static function generateNomorTagihan()
    {
        $prefix = 'TG';
        $date = now()->format('Ymd');
        
        // Use database lock to prevent race condition
        return DB::transaction(function() use ($prefix, $date) {
            // Cari nomor tagihan terakhir hari ini dengan lock
            $lastTagihan = Tagihan::where('nomor_tagihan', 'like', $prefix . $date . '%')
                ->orderBy('nomor_tagihan', 'desc')
                ->lockForUpdate()
                ->first();
            
            if ($lastTagihan) {
                // Ambil 4 digit terakhir dan tambah 1
                $lastNumber = intval(substr($lastTagihan->nomor_tagihan, -4));
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }
            
            return $prefix . $date . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Cari atau buat tagihan untuk rekam medis
     * Ini memastikan satu rekam medis hanya punya satu tagihan
     */
    public static function findOrCreateTagihan($rekamMedisId)
    {
        $rekamMedis = RekamMedis::find($rekamMedisId);
        
        if (!$rekamMedis) {
            throw new \Exception("Rekam medis tidak ditemukan");
        }

        // Debug
        Log::info('=== DEBUG TAGIHAN SERVICE ===');
        Log::info('Rekam Medis: ', $rekamMedis->toArray());
        
        // Ambil pasien_id dengan berbagai cara
        $pasienId = null;
        
        // Cara 1: Dari kolom pasien_id langsung
        if ($rekamMedis->pasien_id) {
            $pasienId = $rekamMedis->pasien_id;
            Log::info('Pasien ID dari kolom langsung: ' . $pasienId);
        }
        
        // Cara 2: Dari relasi pasien
        if (!$pasienId && $rekamMedis->pasien) {
            $pasienId = $rekamMedis->pasien->id;
            Log::info('Pasien ID dari relasi: ' . $pasienId);
        }
        
        // Cara 3: Jika ada kolom dengan nama lain
        if (!$pasienId && isset($rekamMedis->patient_id)) {
            $pasienId = $rekamMedis->patient_id;
            Log::info('Pasien ID dari patient_id: ' . $pasienId);
        }
        
        if (!$pasienId) {
            throw new \Exception("Pasien ID tidak ditemukan untuk rekam medis ID: " . $rekamMedisId);
        }

        // Cari tagihan yang sudah ada dengan status yang masih aktif (belum lunas)
        $tagihan = Tagihan::where('rekam_medis_id', $rekamMedisId)
                         ->whereIn('status', ['belum_bayar', 'menunggu_pembayaran']) // Cari dengan kedua status
                         ->first();

        if (!$tagihan) {
            Log::info('Membuat tagihan baru dengan pasien_id: ' . $pasienId);
            
            $tagihan = Tagihan::create([
                'nomor_tagihan' => static::generateNomorTagihan(),
                'rekam_medis_id' => $rekamMedisId,
                'pasien_id' => $pasienId,
                'status' => 'menunggu_pembayaran', // Konsisten dengan pencarian di atas
                'tanggal_tagihan' => now(),
                'subtotal' => 0
            ]);
        } else {
            Log::info('Menggunakan tagihan yang sudah ada: ' . $tagihan->nomor_tagihan);
        }

        return $tagihan;
    }

    /**
     * Update total biaya tagihan
     */
    public static function updateTotalBiaya($tagihanId, $additionalAmount)
    {
        $tagihan = Tagihan::findOrFail($tagihanId);
        $tagihan->increment('subtotal', $additionalAmount);
        return $tagihan->fresh();
    }

    /**
     * Get active tagihan for rekam medis
     */
    public static function getActiveTagihan($rekamMedisId)
    {
        return Tagihan::where('rekam_medis_id', $rekamMedisId)
                     ->whereIn('status', ['belum_bayar', 'menunggu_pembayaran'])
                     ->first();
    }

    /**
     * Check if rekam medis has active tagihan
     */
    public static function hasActiveTagihan($rekamMedisId)
    {
        return static::getActiveTagihan($rekamMedisId) !== null;
    }
}