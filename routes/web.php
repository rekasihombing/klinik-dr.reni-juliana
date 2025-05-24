<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AppointmentController;

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

Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');


Route::get('/dashboardstaff', function () {
    return Inertia::render('DashboardStaff');
})->name('dashboardstaff');

Route::get('/pendaftaran', function () {
    return Inertia::render('PendaftaranPasienStaff');
})->name('pendaftaran');

Route::get('/KonfirmasiPasien', function () {
    return Inertia::render('KonfiirmasiPasienStaff');
})->name('KonfirmasiPasien');

Route::get('/kontak', function () {
    return Inertia::render('Kontak');
})->name('kontak');

Route::middleware(['auth'])->get('/janjitemu', function () {
    return Inertia::render('JanjiTemu');
})->name('janjitemu');

Route::middleware(['auth'])->get('/datapasien', function () {
    return Inertia::render('DataPasien');
})->name('datapasien');

Route::middleware(['auth'])->get('/konfirmasijanjitemu', function () {
    return Inertia::render('KonfirmasiJanjiTemu');
})->name('konfirmasijanjitemu');

Route::middleware(['auth'])->get('/riwayatjanjitemu', function () {
    return Inertia::render('RiwayatJanjiTemu');
})->name('riwayatjanjitemu');

Route::post('/kontak', [FaqController::class, 'store']);   

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
