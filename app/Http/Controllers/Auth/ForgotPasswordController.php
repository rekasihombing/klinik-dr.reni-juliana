<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use App\Models\User;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form
     */
    public function create()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    /**
     * Handle forgot password request
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
        ]);

        // Check if user exists and get user data
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Email tidak ditemukan dalam database.',
            ]);
        }

        // Generate reset token
        $token = Str::random(64);

        // Delete existing password reset tokens for this email
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Insert new password reset token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Send password reset email
        $this->sendResetEmail($user, $token);

        return back()->with([
            'success' => 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.',
        ]);
    }

    /**
     * Send password reset email
     */
    private function sendResetEmail($user, $token)
    {
        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($user->email));
        
        // Simple email content - you can customize this or use a Mailable class
        $subject = 'Reset Password - ' . config('app.name');
        $message = "
            <h2>Reset Password</h2>
            <p>Halo,</p>
            <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
            <p><a href='{$resetUrl}' style='background-color: #4F46E5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Reset Password</a></p>
            <p>Link ini akan kedaluwarsa dalam 60 menit.</p>
            <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
            <p>Terima kasih,<br>" . config('app.name') . "</p>
        ";

        // Send email using Mail facade
        Mail::send([], [], function ($mail) use ($user, $subject, $message) {
            $mail->to($user->email)
                 ->subject($subject)
                 ->html($message);
        });
    }

    /**
     * Show reset password form
     */
    public function showResetForm(Request $request, $token)
    {
        return Inertia::render('Auth/ResetPassword', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle password reset
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Find the password reset record
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            throw ValidationException::withMessages([
                'email' => 'Token reset password tidak valid.',
            ]);
        }

        // Check if token is valid
        if (!Hash::check($request->token, $passwordReset->token)) {
            throw ValidationException::withMessages([
                'token' => 'Token reset password tidak valid.',
            ]);
        }

        // Check if token is not expired (60 minutes)
        if (Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            // Delete expired token
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();
                
            throw ValidationException::withMessages([
                'token' => 'Token reset password sudah kedaluwarsa.',
            ]);
        }

        // Update user password
        User::where('email', $request->email)
            ->update([
                'password' => Hash::make($request->password),
            ]);

        // Delete the password reset token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect()->route('login')->with([
            'success' => 'Password berhasil direset. Silakan login dengan password baru Anda.',
        ]);
    }
}