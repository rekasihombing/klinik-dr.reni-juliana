<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    use HasFactory;
    
    protected $table = 'rekam_medis';
    
    protected $fillable = [
        'patient_id',      // Make sure this matches your database column name
        'dokter_id',
        'appointment_id',
        'no_rekam_medis',
        'tanggal_kunjungan',
        'status',          // Add status field
        
        // Anamnesis
        'keluhan_utama',
        'rps',
        'rpd',
        'riwayat_alergi',
        'riwayat_obat',
        
        // Pemeriksaan Fisik
        'tekanan_darah',
        'suhu_tubuh',
        'nadi',
        'pernapasan',
        'berat_badan',
        'status_gizi',
        
        // Diagnosa dan Tindakan
        'diagnosa',
        'tindakan',
        'catatan_dokter',
        
        // Obat Luar - TAMBAHAN UNTUK FITUR BARU
        'obat_luar'
    ];
    
    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];
    
    // IMPORTANT: Fix the relationship to match your database structure
    // If your foreign key column is 'patient_id', use this:
    public function pasien()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    
    // Relasi dengan Dokter
    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }
    
    // Relasi dengan Appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    
    // Accessor untuk format tanggal Indonesia
    public function getTanggalKunjunganFormattedAttribute()
    {
        return $this->tanggal_kunjungan->format('d-m-Y');
    }
    
    // Scope untuk filter berdasarkan dokter
    public function scopeByDokter($query, $dokterId)
    {
        return $query->where('dokter_id', $dokterId);
    }
    
    // Scope untuk filter berdasarkan pasien
    public function scopeByPasien($query, $pasienId)
    {
        return $query->where('patient_id', $pasienId);
    }
    
    public function tindakanMedis()
    {
        return $this->belongsToMany(TindakanMedis::class, 'tindakan_pasien', 'rekam_medis_id', 'tindakan_id')
                    ->withTimestamps();
    }
    
    public function resepObat()
    {
        return $this->hasMany(ResepObat::class, 'rekam_medis_id');
    }
    
    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'rekam_medis_id');
    }
    
    public function tagihanObat()
    {
        return $this->hasMany(TagihanObat::class, 'rekam_medis_id');
    }
    
    public function tagihanTindakan()
    {
        return $this->hasMany(TagihanTindakan::class, 'rekam_medis_id');
    }

    // ===== TAMBAHAN METHODS UNTUK OBAT LUAR (tanpa mengubah struktur existing) =====
    
    /**
     * Relationship dengan resep obat dari klinik saja
     */
    public function resepObatKlinik()
    {
        return $this->hasMany(ResepObat::class, 'rekam_medis_id')
                    ->where('dari_klinik', true);
    }

    /**
     * Check if obat_luar field has content
     */
    public function hasObatLuarInRiwayat()
    {
        return !empty($this->obat_luar);
    }

    /**
     * Get obat luar data from obat_luar field
     */
    public function getObatLuarFromRiwayat()
    {
        if (empty($this->obat_luar)) {
            return null;
        }

        return [
            'obat_luar' => $this->obat_luar,
            'catatan' => null // Tidak ada catatan lagi
        ];
    }

    /**
     * Accessor untuk cek apakah ada resep obat
     */
    public function getHasResepObatAttribute()
    {
        return $this->resepObat()->exists() || $this->hasObatLuarInRiwayat();
    }

    /**
     * Accessor untuk cek apakah ada resep obat dari klinik
     */
    public function getHasResepObatKlinikAttribute()
    {
        return $this->resepObatKlinik()->exists();
    }

    /**
     * Accessor untuk cek apakah ada resep obat dari luar
     */
    public function getHasResepObatLuarAttribute()
    {
        return $this->hasObatLuarInRiwayat();
    }

    /**
     * Method untuk mendapatkan ringkasan resep obat
     */
    public function getRingkasanResepObat()
    {
        $result = [
            'obat_klinik' => [],
            'obat_luar' => null,
            'catatan_obat_luar' => null,
            'total_item_klinik' => 0
        ];

        // Ambil resep obat dari klinik
        $obatKlinik = $this->resepObatKlinik()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($resep) {
                return [
                    'id' => $resep->id,
                    'nama_obat' => $resep->nama_obat,
                    'dosis' => $resep->dosis,
                    'jumlah' => $resep->jumlah,
                    'tanggal_mulai' => $resep->tanggal_mulai,
                    'tanggal_terakhir' => $resep->tanggal_terakhir,
                    'catatan' => $resep->catatan,
                    'dari_klinik' => $resep->dari_klinik
                ];
            });

        $result['obat_klinik'] = $obatKlinik;
        $result['total_item_klinik'] = $obatKlinik->count();

        // Ambil resep obat dari luar dari field obat_luar
        if (!empty($this->obat_luar)) {
            $result['obat_luar'] = $this->obat_luar;
            $result['catatan_obat_luar'] = null; // Tidak ada catatan
        }

        return $result;
    }

    /**
     * Method untuk format obat luar menjadi array lines
     */
    public function getObatLuarLinesAttribute()
    {
        if (empty($this->obat_luar)) {
            return [];
        }

        return array_filter(
            array_map('trim', explode("\n", $this->obat_luar)),
            function($line) {
                return !empty($line);
            }
        );
    }
}