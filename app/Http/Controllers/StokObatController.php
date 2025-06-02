<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\StokObat;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class StokObatController extends Controller
{
    public function index()
    {
        $stokObat = StokObat::with('obat')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return Inertia::render('staff/DaftarStokObat', [
            'stokObat' => $stokObat
        ]);
    }

    public function create()
    {
        $obat = Obat::select('id', 'nama_obat')
            ->orderBy('nama_obat')
            ->get();

        return Inertia::render('staff/TambahStokObat', [
            'obat' => $obat
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_kadaluarsa' => 'required|date|after:today'
        ], [
            'obat_id.required' => 'Pilih obat terlebih dahulu',
            'obat_id.exists' => 'Obat yang dipilih tidak valid',
            'jumlah.required' => 'Jumlah stok harus diisi',
            'jumlah.integer' => 'Jumlah stok harus berupa angka',
            'jumlah.min' => 'Jumlah stok minimal 1',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa harus diisi',
            'tanggal_kadaluarsa.date' => 'Format tanggal tidak valid',
            'tanggal_kadaluarsa.after' => 'Tanggal kadaluarsa harus setelah hari ini'
        ]);

        // Cek apakah sudah ada stok dengan obat_id dan tanggal_kadaluarsa yang sama
        $existingStok = StokObat::where('obat_id', $request->obat_id)
            ->where('tanggal_kadaluarsa', $request->tanggal_kadaluarsa)
            ->first();

        if ($existingStok) {
            // Jika sudah ada, update jumlahnya
            $existingStok->jumlah += $request->jumlah;
            $existingStok->save();
        } else {
            // Jika belum ada, buat baru
            StokObat::create([
                'obat_id' => $request->obat_id,
                'jumlah' => $request->jumlah,
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa
            ]);
        }

        return Redirect::route('dashboardstaff')
            ->with('success', 'Stok obat berhasil ditambahkan');
    }

    public function show(StokObat $stokObat)
    {
        $stokObat->load('obat');
        
        return Inertia::render('StokObat/Show', [
            'stokObat' => $stokObat
        ]);
    }

    public function edit(StokObat $stokObat)
    {
        $obat = Obat::select('id', 'nama_obat')
            ->orderBy('nama_obat')
            ->get();

        return Inertia::render('staff/EditStokObat', [
            'stokObat' => $stokObat->load('obat'),
            'obat' => $obat
        ]);
    }

    public function update(Request $request, StokObat $stokObat)
    {
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'jumlah' => 'required|integer|min:0',
            'tanggal_kadaluarsa' => 'required|date'
        ], [
            'obat_id.required' => 'Pilih obat terlebih dahulu',
            'obat_id.exists' => 'Obat yang dipilih tidak valid',
            'jumlah.required' => 'Jumlah stok harus diisi',
            'jumlah.integer' => 'Jumlah stok harus berupa angka',
            'jumlah.min' => 'Jumlah stok tidak boleh negatif',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa harus diisi',
            'tanggal_kadaluarsa.date' => 'Format tanggal tidak valid'
        ]);

        $stokObat->update([
            'obat_id' => $request->obat_id,
            'jumlah' => $request->jumlah,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa
        ]);

        return Redirect::route('stok-obat.index')
            ->with('success', 'Stok obat berhasil diperbarui');
    }

    public function destroy(StokObat $stokObat)
    {
        $stokObat->delete();

        return Redirect::route('stok-obat.index')
            ->with('success', 'Stok obat berhasil dihapus');
    }
}