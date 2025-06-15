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
        return Inertia::render('staff/EditObat', [
            'obat' => $obat
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Obat $obat)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255|unique:obat,nama_obat,' . $obat->id,
            'jenis_obat' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'nullable|numeric|min:0',
            'satuan' => 'required|string|max:50'
        ], [
            'nama_obat.required' => 'Nama obat harus diisi',
            'nama_obat.unique' => 'Nama obat sudah ada dalam database',
            'nama_obat.max' => 'Nama obat maksimal 255 karakter',
            'jenis_obat.max' => 'Jenis obat maksimal 255 karakter',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh negatif',
            'satuan.required' => 'Satuan harus diisi',
            'satuan.max' => 'Satuan maksimal 50 karakter'
        ]);

        try {
            $obat->update($validated);

            return Redirect::route('obat.index')
                ->with('success', 'Data obat berhasil diperbarui');
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Gagal memperbarui data obat: ' . $e->getMessage()])
                ->withInput();
        }
    }
}