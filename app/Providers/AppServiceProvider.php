<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Daftarkan event listener untuk email verification
        Event::listen(Registered::class, function ($event) {
            \Log::info('Registered event listener triggered for user: ' . $event->user->email);
            
            // Pastikan user implements MustVerifyEmail dan belum terverifikasi
            if ($event->user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && 
                !$event->user->hasVerifiedEmail()) {
                
                \Log::info('Sending email verification to: ' . $event->user->email);
                $event->user->sendEmailVerificationNotification();
                \Log::info('Email verification sent successfully');
            }
        });
    }
}