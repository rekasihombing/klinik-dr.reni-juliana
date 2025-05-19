<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
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
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pasien',
            ]);

            DB::commit();

            auth()->login($user);

            // Redirect ke form tambah pasien (lengkapi data pasien)
            return redirect()->route('patients.create');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Registrasi gagal: ' . $e->getMessage()]);
        }
    }
}
