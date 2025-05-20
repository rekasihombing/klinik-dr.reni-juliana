<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/login', function () {
    return Inertia::render('auth/Login');
});

Route::get('/register', function () {
    return Inertia::render('auth/Register');
});

Route::post('/register', [RegisteredUserController::class, 'store']);

Route::middleware(['auth'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth'])->get('/dashboardstaf', function () {
    return Inertia::render('DashboardStaff');
})->name('dashboardstaf');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
