<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        // Render halaman login via Inertia
        return Inertia::render('auth/Login');
    }

public function store(Request $request)
{
    // Validasi input login
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Coba login user
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        // Ambil user yang login
        $user = Auth::user();

        // Redirect sesuai role / tipe user
            if ($user->role === 'pasien') {
                return redirect()->route('dashboard');
            } elseif ($user->role === 'staff') {
                return redirect()->route('dashboardstaff');
            } elseif ($user->role === 'dokter') {
                return redirect()->route('dashboarddokter');
            }


        // Default redirect jika role tidak dikenal
        return redirect()->intended('/dashboard');
    }

    // Jika gagal, lempar error validasi
    throw ValidationException::withMessages([
        'email' => __('Email atau password salah.'),
    ]);
}


    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
