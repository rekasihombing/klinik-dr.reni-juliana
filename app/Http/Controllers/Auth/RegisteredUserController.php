<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;


class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
       $request->validate([
    'name' => 'required|string|max:100',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|string|min:6|confirmed',
]);

        try {
            // Simpan user
            DB::beginTransaction();
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pasien', // default role pasien
            ]);

            // Simpan pasien
            Patient::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->name,
                'nik' => '', // kosong dulu jika belum ada di form
                'tanggal_lahir' => now(), // sementara default
                'jenis_kelamin' => 'L', // default
                'email' => $request->email,
                'no_hp' => '',
                'alamat' => '',
            ]);

            DB::commit();

            // Login langsung (jika mau)
            auth()->login($user);

            return redirect('/dashboard'); // halaman setelah login
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Registrasi gagal: ' . $e->getMessage()]);
        }
    }
}
