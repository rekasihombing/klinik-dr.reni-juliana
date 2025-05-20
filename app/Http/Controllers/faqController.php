<?php
namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Inertia\Inertia;

class faqController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'nomor_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'keluhan' => 'required|string',
        ]);

        // Simpan data ke database
        Faq::create([
            'nama_lengkap' => $request->nama_lengkap,
            'nomor_telepon' => $request->nomor_telepon,
            'email' => $request->email,
            'keluhan' => $request->keluhan,
        ]);

        // Kirimkan respons sukses menggunakan Inertia
        return Inertia::render('Kontak', [
            'message' => 'Keluhan berhasil dikirim!',
        ]);
    }
}
