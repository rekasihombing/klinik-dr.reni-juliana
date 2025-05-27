<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\OnlinePatientController;
use App\Http\Controllers\ProfileController;

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

Route::post('/contact', [FaqController::class, 'store']);   

Route::get('/kontak', function () {
    return Inertia::render('Kontak');
})->name('kontak');

Route::post('/kontak', [FaqController::class, 'store']);   

//Staff
Route::get('/dashboardstaff', function () {
    return Inertia::render('staff/DashboardStaff');
})->name('dashboardstaff');

Route::get('/pendaftaran', function () {
    return Inertia::render('staff/PendaftaranPasienStaff');
})->name('pendaftaran');

Route::get('/KonfirmasiPasien', function () {
    return Inertia::render('staff/KonfiirmasiPasienStaff');
})->name('KonfirmasiPasien');

Route::get('/Pembayaran', function () {
    return Inertia::render('staff/pembayaran');
})->name('Pembayaran');

Route::get('/invoice', function () {
    return Inertia::render('staff/invoice');
})->name('Invoice');

Route::get('/JadwalKlinik', function () {
    return Inertia::render('staff/Jadwal');
})->name('JadwalKlinik');
//Pasien
Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::middleware(['auth'])->get('/janjitemu', function () {
    return Inertia::render('pasien/JanjiTemu');
})->name('janjitemu');

Route::middleware(['auth'])->get('/datapasien', function () {
    return Inertia::render('pasien/datapasien');
})->name('datapasien');

Route::middleware(['auth'])->get('/datapasien', [OnlinePatientController::class, 'create'])->name('datapasien');
Route::post('/pasien/daftar', [OnlinePatientController::class, 'store'])->name('simpanpasienonline');


Route::middleware(['auth'])->get('/konfirmasijanjitemu', function () {
    return Inertia::render('pasien/KonfirmasiJanjiTemu');
})->name('konfirmasijanjitemu');

Route::middleware(['auth'])->get('/janjitemu', [AppointmentController::class, 'create'])->name('janjitemu'); 
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');   

Route::post('/contact', [FaqController::class, 'store']);   

Route::middleware(['auth'])->group(function () {
    Route::get('/patient/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patient', [PatientController::class, 'update'])->name('patients.update');
});

Route::middleware(['auth'])->get('/riwayatjanjitemu', function () {
    return Inertia::render('pasien/RiwayatJanjiTemu');
})->name('riwayatjanjitemu');


Route::middleware(['auth'])->get('/profilpasien', [ProfileController::class, 'show'])->name('profilpasien');

Route::get('/dashboarddokter', function () {
    return Inertia::render('dokter/DashboardDokter');
})->name('dashboarddokter');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
