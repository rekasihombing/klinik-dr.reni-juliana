<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PatientController;

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
    return Inertia::render('kontak');
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

Route::middleware(['auth'])->get('/janjitemu', [AppointmentController::class, 'create'])->name('janjitemu'); 
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');   

Route::post('/contact', [FaqController::class, 'store']);   

Route::middleware(['auth'])->group(function () {
    Route::get('/patient/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patient', [PatientController::class, 'update'])->name('patients.update');
});

Route::middleware(['auth'])->get('/riwayatjanjitemu', function () {
    return Inertia::render('RiwayatJanjiTemu');
})->name('riwayatjanjitemu');

Route::get('/dashboarddokter', function () {
    return Inertia::render('DashboardDokter');
})->name('dashboarddokter');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
