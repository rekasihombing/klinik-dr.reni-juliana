<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return Inertia::render('auth/Register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();

        try {
            // Simpan user
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pasien',
            ]);

            // Simpan pasien
            Patient::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->name,
                'nik' => '',
                'tanggal_lahir' =>'',
                'jenis_kelamin' => '',
                'email' => $request->email,
                'no_hp' => '',
                'alamat' => '',
            ]);

            DB::commit();

            auth()->login($user);

            return redirect()->route('patients.create'); // atau redirect sesuai tujuanmu
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Registrasi gagal: ' . $e->getMessage()]);
        }
    }
}