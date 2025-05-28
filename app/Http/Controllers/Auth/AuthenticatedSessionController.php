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
    // Validasi input
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $user = \App\Models\User::where('email', $credentials['email'])->first();

    // Jika email tidak ditemukan
    if (! $user) {
        throw ValidationException::withMessages([
            'email' => 'Email tidak terdaftar.',
        ]);
    }

    // Jika password salah
    if (! \Hash::check($credentials['password'], $user->password)) {
        throw ValidationException::withMessages([
            'password' => 'Password salah.',
        ]);
    }

    // Login user
    Auth::login($user, $request->boolean('remember'));
    $request->session()->regenerate();

    // Redirect sesuai role
    if ($user->role === 'pasien') {
        return redirect()->route('dashboard');
    } elseif ($user->role === 'staff') {
        return redirect()->route('dashboardstaff');
    } elseif ($user->role === 'dokter') {
        return redirect()->route('dashboarddokter');
    }

    // Fallback
    return redirect()->intended('/dashboard');
}


    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
