<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\OnlinePatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\PatientDashboardController;

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

Route::middleware(['auth'])->get('/riwayatrekammedis', function () {
    return Inertia::render('pasien/RiwayatRekamMedis');
})->name('riwayatrekammedis');

Route::middleware(['auth'])->get('/jadwalkonsultasi', function () {
    return Inertia::render('pasien/JadwalKonsultasi');
})->name('jadwalkonsultasi');

Route::middleware(['auth'])->get('/profilpasien', [ProfileController::class, 'show'])->name('profilpasien');

Route::get('/dashboarddokter', function () {
    return Inertia::render('dokter/DashboardDokter');
})->name('dashboarddokter');

Route::get('/tambahrekammedis', function () {
    return Inertia::render('Doctor/TambahRekamMedis');
})->name('tambahrekammedis');

// Routes untuk dokter (gunakan middleware auth dan role dokter jika ada)
Route::middleware(['auth'])->group(function () {
    // Dashboard dokter
    Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])
        ->name('doctor.dashboard');
    
Route::middleware(['auth'])->group(function () {
    Route::get('/appointment/{id}/detail', [DoctorDashboardController::class, 'showAppointmentDetail'])->name('appointment.detail');
});
    
    // Update status appointment
    Route::patch('/appointment/{id}/status', [DoctorDashboardController::class, 'updateStatus'])
        ->name('appointment.update.status');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard'); // pasien
    Route::get('/dashboardstaff', [StaffDashboardController::class, 'index'])->name('dashboardstaff'); // staff
    Route::get('/dashboarddokter', [DoctorDashboardController::class, 'index'])->name('dashboarddokter'); // dokter
});

Route::post('/appointment/cancel', [AppointmentController::class, 'cancelAppointment'])
    ->middleware(['auth'])
    ->name('appointment.cancel');

Route::post('/checkin', [AppointmentController::class, 'checkIn']);


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
