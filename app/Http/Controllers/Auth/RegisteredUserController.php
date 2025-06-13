<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return Inertia::render('auth/Register');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            \Log::info('Registration started for: ' . $request->email);

            // Step 1: Validasi
            \Log::info('Step 1: Starting validation');
            $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            \Log::info('Step 1 PASSED: Validation successful');

            // Step 2: Database Transaction
            \Log::info('Step 2: Starting database transaction');
            DB::beginTransaction();

            // Step 3: Create User
            \Log::info('Step 3: Creating user');
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pasien',
            ]);
            \Log::info('Step 3 PASSED: User created with ID: ' . $user->id);

            // Step 4: Create Patient
            \Log::info('Step 4: Creating patient record');
            Patient::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->name,
                'nik' => '',
                'tanggal_lahir' => now(),
                'jenis_kelamin' => 'L',
                'email' => $request->email,
                'no_hp' => '',
                'alamat' => '',
            ]);
            \Log::info('Step 4 PASSED: Patient record created');

            // Step 5: Commit Transaction
            \Log::info('Step 5: Committing database transaction');
            DB::commit();
            \Log::info('Step 5 PASSED: Database transaction committed');

            // Step 6: Login User
            \Log::info('Step 6: Logging in user');
            Auth::login($user);
            \Log::info('Step 6 PASSED: User logged in - Auth check: ' . (Auth::check() ? 'true' : 'false'));

            // Step 7: Trigger Registered Event
            \Log::info('Step 7: Triggering Registered event');
            event(new Registered($user));
            \Log::info('Step 7 PASSED: Registered event triggered');

            // Step 8: Check Route Exists
            \Log::info('Step 8: Checking if verification.notice route exists');
            $routeExists = Route::has('verification.notice');
            \Log::info('Step 8 RESULT: Route verification.notice exists: ' . ($routeExists ? 'YES' : 'NO'));
            
            if (!$routeExists) {
                \Log::error('Route verification.notice does not exist!');
                // Coba redirect ke route lain yang pasti ada
                return redirect('/dashboard')->with('error', 'Verifikasi email diperlukan');
            }

            // Step 9: Get Route URL
            \Log::info('Step 9: Getting route URL');
            $routeUrl = route('verification.notice');
            \Log::info('Step 9 RESULT: Route URL is: ' . $routeUrl);

            // Step 10: Redirect
            \Log::info('Step 10: About to redirect');
            return redirect()->route('verification.notice')
                           ->with('status', 'verification-link-sent');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('VALIDATION ERROR: ' . json_encode($e->errors()));
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('GENERAL ERROR: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->withErrors(['error' => 'Registrasi gagal: ' . $e->getMessage()])
                        ->withInput($request->except('password', 'password_confirmation'));
        }
    }
}