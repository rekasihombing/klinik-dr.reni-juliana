<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class ObatController extends Controller
{
    public function index()
    {
        $obat = Obat::orderBy('nama_obat')
            ->paginate(10);

        return Inertia::render('staff/Obat', [
            'obat' => $obat
        ]);
    }

    public function create()
    {
        return Inertia::render('staff/TambahObat');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255|unique:obat,nama_obat',
            'jenis_obat' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'nullable|numeric|min:0',
            'satuan' => 'required|string|max:50'
        ], [
            'nama_obat.required' => 'Nama obat harus diisi',
            'nama_obat.unique' => 'Nama obat sudah ada dalam database',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh negatif',
            'satuan.required' => 'Satuan harus diisi'
        ]);

        Obat::create($request->all());

        return Redirect::route('obat.index')
            ->with('success', 'Data obat berhasil ditambahkan');
    }

    public function show(Obat $obat)
    {
        // Load relasi stok obat
        $obat->load(['stokObat' => function($query) {
            $query->orderBy('tanggal_kadaluarsa', 'asc');
        }]);

        return Inertia::render('Obat/Show', [
            'obat' => $obat
        ]);
    }

    public function edit(Obat $obat)
    {
        return Inertia::render('Obat/Edit', [
            'obat' => $obat
        ]);
    }

    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255|unique:obat,nama_obat,' . $obat->id,
            'jenis_obat' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'nullable|numeric|min:0',
            'satuan' => 'required|string|max:50'
        ], [
            'nama_obat.required' => 'Nama obat harus diisi',
            'nama_obat.unique' => 'Nama obat sudah ada dalam database',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh negatif',
            'satuan.required' => 'Satuan harus diisi'
        ]);

        $obat->update($request->all());

        return Redirect::route('obat.index')
            ->with('success', 'Data obat berhasil diperbarui');
    }

    public function destroy(Obat $obat)
    {
        // Cek apakah obat masih memiliki stok
        if ($obat->stokObat()->count() > 0) {
            return Redirect::back()
                ->with('error', 'Tidak dapat menghapus obat yang masih memiliki stok');
        }

        $obat->delete();

        return Redirect::route('obat.index')
            ->with('success', 'Data obat berhasil dihapus');
    }
}