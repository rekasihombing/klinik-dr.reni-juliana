<?php

use Illuminate\Http\Request;
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
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\RiwayatRekamMedisController;
use App\Http\Controllers\OfflineBookingController;
use App\Http\Controllers\KonfirmasiPasienStaffController;
use App\Http\Controllers\ClinicScheduleController;
use App\Http\Controllers\ScheduleExceptionController;
use App\Http\Controllers\StokObatController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\JanjiTemuController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DetailPasienController;
use App\Http\Controllers\DaftarJanjiTemu;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\LaporanOperasionalController;
use App\Http\Controllers\ResepObatController;
use App\Http\Controllers\TindakanController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DaftarPembayaranController;
use App\Http\Controllers\RiwayatResepObatController;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\JadwalKontrolController;
use App\Http\Controllers\StaffPengingatController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/login', function () {
    return Inertia::render('auth/Login');
});

Route::get('/register', function () {
    return Inertia::render('auth/Register');
});

// PERBAIKAN: Route register dengan redirect ke verifikasi
Route::post('/register', [RegisteredUserController::class, 'store']);

// VERIFIKASI EMAIL ROUTES - PERBAIKAN
// Pastikan route ini ada
Route::get('/email/verify', function () {
    return Inertia::render('Auth/VerifyEmail', [
        'status' => session('status')
    ]);
})->middleware('auth')->name('verification.notice');

// 2. Handle link verifikasi email - PERBAIKAN
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    
    // Trigger verified event
    event(new Verified($request->user()));
    
    // Redirect ke dashboard dengan pesan sukses
    return redirect()->intended(route('dashboard'))->with('verified', true);
})->middleware(['auth', 'signed'])->name('verification.verify');

// 3. Resend verification email - PERBAIKAN
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// DASHBOARD - dengan middleware verified
Route::middleware(['auth', 'verified'])->get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

// ... sisa routes Anda tetap sama ...

Route::get('/tagihan', function () {
    return Inertia::render('staff/Tagihan');
})->name('tagihan');

Route::post('/contact', [FaqController::class, 'store']);   

Route::get('/kontak', function () {
    return Inertia::render('Kontak');
})->name('kontak');

Route::post('/kontak', [FaqController::class, 'store']);   

//Staff
Route::get('/dashboardstaff', [StaffDashboardController::class, 'index'])->name('dashboardstaff');
Route::patch('/appointment/{id}/status', [StaffDashboardController::class, 'updateStatus'])->name('appointment.status');
Route::post('/appointment/{id}/queue', [StaffDashboardController::class, 'generateQueueNumber'])->name('appointment.queue');

// ... (sisa routes Anda)
Route::get('/pendaftaran', [OfflineBookingController::class, 'create'])->name('pendaftaran.form');
Route::post('/simpanpendaftar', [OfflineBookingController::class, 'store'])->name('pendaftaran.store');
Route::get('/check-nik/{nik}', [OfflineBookingController::class, 'checkNik'])->name('check-nik');
Route::middleware(['auth'])->group(function () {
    Route::get('/konfirmasi-pasien', [KonfirmasiPasienStaffController::class, 'index'])
        ->name('konfirmasi.pasien');
    
    Route::post('/konfirmasi-pasien/{id}', [KonfirmasiPasienStaffController::class, 'konfirmasi']);
});

Route::get('/Pembayaran', function () {
    return Inertia::render('staff/pembayaran');
})->name('Pembayaran');

Route::get('/invoice', function () {
    return Inertia::render('staff/invoice');
})->name('Invoice');

Route::get('/staff.Pasien', [PatientController::class, 'index'])->name('staff.pasien');
Route::get('/dokter.Pasien', [PatientController::class, 'index'])->name('dokter.pasien');

Route::get('/dokter.Pasien/{id}', [DetailPasienController::class, 'show'])->name('dokter.pasien.show');
Route::get('/staff.Pasien/{id}', [DetailPasienController::class, 'show'])->name('staff.pasien.show');


Route::get('/clinic-schedules', [ClinicScheduleController::class, 'index'])->name('clinic.schedules.index');
Route::put('/clinic-schedules/bulk-update', [ClinicScheduleController::class, 'bulkUpdate'])->name('clinic.schedules.bulk-update');
Route::get('/schedule-exceptions', [ScheduleExceptionController::class, 'index'])->name('schedule.exceptions.index');


   Route::get('/daftar-janji-temu', [DaftarJanjiTemu::class, 'index'])->name('appointments.index');
    Route::patch('/daftar-janji-temu/{id}/confirm', [DaftarJanjiTemu::class, 'confirm'])->name('appointments.confirm');
    Route::patch('/daftar-janji-temu/{id}/complete', [DaftarJanjiTemu::class, 'complete'])->name('appointments.complete');
    
    // Reset antrian harian (untuk cron job)
    Route::post('/queue/reset', [DaftarJanjiTemu::class, 'resetDailyQueue'])->name('queue.reset');


//Pasien
// Menampilkan view minta verifikasi
Route::get('/email/verify', function () {
    return Inertia::render('Auth/VerifyEmail');
})->middleware('auth')->name('verification.notice');

// Proses link verifikasi email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // Menandai email sebagai verified
    return inertia::render('/dashboard'); // Arahkan ke halaman setelah verifikasi
})->middleware(['auth', 'signed'])->name('verification.verify');

// Kirim ulang email verifikasi

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified'])->get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');


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

Route::get('/resepobat', function () {
    return Inertia::render('Doctor/ResepObat');
})->name('resepobat');

Route::get('/pendaftaranpegawai', function () {
    return Inertia::render('Doctor/PendaftaranPegawai');
})->name('pendaftaranpegawai');

Route::get('/tambahstaff', function () {
    return Inertia::render('Doctor/TambahStaff');
})->name('tambahstaff');

// Route::get('/tambahrekammedis', function () {
//     return Inertia::render('Doctor/TambahRekamMedis');
// })->name('tambahrekammedis');

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
    Route::get('/dashboarddokter', [DoctorDashboardController::class, 'index'])->name('dashboarddokter'); // dokter
});

Route::post('/appointment/cancel', [AppointmentController::class, 'cancelAppointment'])
    ->middleware(['auth'])
    ->name('appointment.cancel');

Route::post('/checkin', [AppointmentController::class, 'checkIn']);

Route::get('/tambahrekammedis/{id}', [RekamMedisController::class, 'create'])->name('rekammedis.create');

Route::middleware(['auth'])->group(function () {
    // Routes untuk Rekam Medis
    Route::get('/rekam-medis/create/{appointmentId}', [RekamMedisController::class, 'create'])
        ->name('rekam-medis.create');
    
    // Route::post('/rekam-medis/store', [RekamMedisController::class, 'store'])
    //     ->name('rekam-medis.store');
    
    Route::get('/Dashboard', [RekamMedisController::class, 'show'])
        ->name('rekam-medis.show');
    
    Route::get('/rekam-medis', [RekamMedisController::class, 'index'])
        ->name('rekam-medis.index');
    
    // Route untuk backward compatibility dengan nama lama
    Route::get('/tambahrekammedis/{appointmentId}', [RekamMedisController::class, 'create'])
        ->name('tambahrekammedis');
});

// Route untuk menyimpan rekam medis (sesuai dengan yang ada di Vue component)
Route::post('/rekam-medis', [RekamMedisController::class, 'store'])->middleware('auth');


Route::middleware(['auth'])->group(function () {
Route::get('/riwayat-janji-temu', [AppointmentController::class, 'history'])->name('appointments.history');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/riwayat-rekam-medis', [RiwayatRekamMedisController::class, 'index'])
        ->name('patient.medical-history');
});

Route::middleware(['auth'])->group(function () {
    
    // Routes untuk Obat
    Route::resource('obat', ObatController::class);
    
    // Routes untuk Stok Obat
    Route::resource('stok-obat', StokObatController::class);
    
    // Atau jika ingin lebih spesifik:
    /*
    Route::prefix('obat')->name('obat.')->group(function () {
        Route::get('/', [ObatController::class, 'index'])->name('index');
        Route::get('/create', [ObatController::class, 'create'])->name('create');
        Route::post('/', [ObatController::class, 'store'])->name('store');
        Route::get('/{obat}', [ObatController::class, 'show'])->name('show');
        Route::get('/{obat}/edit', [ObatController::class, 'edit'])->name('edit');
        Route::put('/{obat}', [ObatController::class, 'update'])->name('update');
        Route::delete('/{obat}', [ObatController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('stok-obat')->name('stok-obat.')->group(function () {
        Route::get('/', [StokObatController::class, 'index'])->name('index');
        Route::get('/create', [StokObatController::class, 'create'])->name('create');
        Route::post('/', [StokObatController::class, 'store'])->name('store');
        Route::get('/{stokObat}', [StokObatController::class, 'show'])->name('show');
        Route::get('/{stokObat}/edit', [StokObatController::class, 'edit'])->name('edit');
        Route::put('/{stokObat}', [StokObatController::class, 'update'])->name('update');
        Route::delete('/{stokObat}', [StokObatController::class, 'destroy'])->name('destroy');
    });
    */
});

Route::middleware(['auth'])->group(function () {
    // Route untuk halaman laporan keuangan
    Route::get('/laporan-keuangan', [LaporanKeuanganController::class, 'index'])
        ->name('laporan-keuangan.index');
    
    // Route untuk download PDF
    Route::post('/laporan-keuangan/download-pdf', [LaporanKeuanganController::class, 'downloadPDF'])
        ->name('laporan-keuangan.download-pdf');
    
    // Route untuk mendapatkan detail transaksi (opsional untuk AJAX)
    Route::get('/laporan-keuangan/detail-transaksi', [LaporanKeuanganController::class, 'getDetailTransaksi'])
        ->name('laporan-keuangan.detail-transaksi');

        Route::get('/janji-temu', [JanjiTemuController::class, 'index']);
        Route::get('/dokter.Laporan-operasional', [LaporanOperasionalController::class, 'index'])->name('dokter.laporan-operasional');
    Route::get('/staff.Laporan-operasional', [LaporanOperasionalController::class, 'index'])->name('staff.laporan-operasional');
});
Route::get('/janji-temu', [JanjiTemuController::class, 'index']);
Route::put('/appointment/{id}/mulai-konsultasi', [AppointmentController::class, 'mulaiKonsultasi'])
    ->name('appointment.mulai-konsultasi');

// Form untuk membuat resep obat (GET)
Route::get('/resep-obat/create', [ResepObatController::class, 'create'])->name('resep-obat.create');

// Menyimpan data resep obat (POST)
Route::post('/resep-obat/store', [ResepObatController::class, 'store'])->name('resep-obat.store');






    // Display billing form with auto-populated items
    Route::get('/tagihan/create', [TagihanController::class, 'create'])->name('tagihan.create');
    
    // Process billing/payment
    Route::post('/tagihan/store', [TagihanController::class, 'store'])->name('tagihan.store');
    
    // Show invoice
    Route::get('/tagihan/{tagihan}/invoice', [TagihanController::class, 'invoice'])->name('tagihan.invoice');
    
    // Billing history
    Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
    
    // Additional routes you might need:
    
    // Show specific billing details
    Route::get('/tagihan/{tagihan}', [TagihanController::class, 'show'])->name('tagihan.show');
    
    // Edit billing (if needed)
    Route::get('/tagihan/{tagihan}/edit', [TagihanController::class, 'edit'])->name('tagihan.edit');
    Route::put('/tagihan/{tagihan}', [TagihanController::class, 'update'])->name('tagihan.update');
    
    // Delete billing (if needed)
    Route::delete('/tagihan/{tagihan}', [TagihanController::class, 'destroy'])->name('tagihan.destroy');

Route::get('/tagihan/invoice/{id}', [TagihanController::class, 'invoice'])->name('tagihan.invoice');
    Route::resource('tagihan', TagihanController::class)->except(['create']);
    
 Route::post('/resep-obat/check-stock', [ResepObatController::class, 'checkStock'])->name('resep-obat.check-stock');
    Route::get('/resep-obat/get-stock/{obatId}', [ResepObatController::class, 'getStock'])->name('resep-obat.get-stock');

    Route::get('/tindakan/create/{rekamMedis}', [TindakanController::class, 'create'])->name('tindakan.create');
    Route::get('/rekam-medis/{rekamMedis}/tindakan/create', [TindakanController::class, 'create'])->name('tindakan.create');
    Route::post('/rekam-medis/{rekamMedis}/tindakan', [TindakanController::class, 'store'])->name('tindakan.store');


Route::middleware(['auth'])->group(function () {
    Route::resource('staff', StaffController::class)->only(['index', 'store', 'update', 'destroy']);
});

Route::get('/pembayaran', [DaftarPembayaranController::class, 'index'])->name('pembayaran');
Route::get('/pembayaran/{id}', [DaftarPembayaranController::class, 'show'])->name('pembayaran.show');
Route::post('/pembayaran/{id}/store', [DaftarPembayaranController::class, 'store'])->name('pembayaran.store');
Route::get('/pembayaran/invoice/{id}', [DaftarPembayaranController::class, 'invoice'])->name('pembayaran.invoice');

// Tambahkan routes ini ke file routes/web.php yang sudah ada

// Routes untuk Resep Obat (Pasien)
Route::middleware(['auth'])->group(function () {
    
    // Route untuk daftar resep obat pasien
    Route::get('/riwayat-resep-obat', [RiwayatResepObatController::class, 'index'])
        ->name('riwayat-resep-obat.index');
    
    // Route untuk detail resep obat berdasarkan rekam medis
    Route::get('/riwayat-resep-obat/{rekamMedisId}', [RiwayatResepObatController::class, 'show'])
        ->name('riwayat-resep-obat.show')
        ->where('rekamMedisId', '[0-9]+');
    
    // API Routes untuk fitur tambahan (opsional)
    Route::prefix('api/riwayat-resep-obat')->group(function () {
        
        // Get resep obat yang sedang aktif
        Route::get('/aktif', [RiwayatResepObatController::class, 'getAktif'])
            ->name('api.riwayat-resep-obat.aktif');
        
        // Set reminder untuk obat (jika implementasi reminder)
        Route::post('/reminder', [RiwayatResepObatController::class, 'setReminder'])
            ->name('api.riwayat-resep-obat.reminder');
        
        // Export resep obat ke PDF
        Route::get('/export/{rekamMedisId}', [RiwayatResepObatController::class, 'exportPDF'])
            ->name('api.riwayat-resep-obat.export')
            ->where('rekamMedisId', '[0-9]+');
    });
});


// Forgot Password Routes
// Route::middleware('guest')->group(function () {
//     // Show forgot password form
//     Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
//         ->name('password.request');
    
//     // Handle forgot password form submission
//     Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
//         ->name('password.email');
    
//     // Show reset password form
//     Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
//         ->name('password.reset');
    
//     // Handle reset password form submission
//     Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])
//         ->name('password.update');
// });

// // Example of other auth routes that you might need
// Route::middleware('guest')->group(function () {
//     Route::get('/login', function () {
//         return Inertia::render('Auth/Login');
//     })->name('login');
    
//     Route::get('/register', function () {
//         return Inertia::render('Auth/Register');
//     })->name('register');
// });


// use App\Http\Controllers\StaffPengingatController;



    Route::get('/jadwal-kontrol/{rekamMedis}', [JadwalKontrolController::class, 'create'])->name('jadwal-kontrol.create');
    Route::post('/jadwal-kontrol/{rekamMedis}', [JadwalKontrolController::class, 'store'])->name('jadwal-kontrol.store');


// Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/pengingat-kontrol', [StaffPengingatController::class, 'index'])->name('pengingat-kontrol.index');
     Route::post('/pengingat-kontrol/{jadwalKontrol}', [StaffPengingatController::class, 'kirimPengingat'])->name('pengingat-kontrol.kirim');
// });

    Route::put('/jadwal-kontrol/{jadwalKontrol}/batalkan', [JadwalKontrolController::class, 'batalkan'])->name('jadwal-kontrol.batalkan');

Route::middleware('auth')->group(function () {
    // Settings routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('password', [PasswordController::class, 'edit'])
            ->name('password.edit');
        
        Route::put('password', [PasswordController::class, 'update'])
            ->name('password.update');
    });
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

    // Menyimpan tindakan baru untuk rekam medis tertentu
    Route::post('/rekam-medis/{rekamMedis}/tindakan', [TindakanController::class, 'store'])->name('tindakan.store');

    // Routes untuk Dokter
Route::prefix('dokter')->name('dokter.')->group(function () {
    Route::get('/laporan-operasional', [LaporanOperasionalController::class, 'index'])->name('laporan-operasional');
    Route::get('/laporan-operasional/download-pdf', [LaporanOperasionalController::class, 'downloadPdf'])->name('laporan-operasional.download-pdf');
    Route::get('/laporan-operasional/print', [LaporanOperasionalController::class, 'printReport'])->name('laporan-operasional.print');
});

// Add this route for PDF download
Route::get('/laporan-operasional/download-pdf', [LaporanOperasionalController::class, 'downloadPdf'])
    ->name('laporan-operasional.download-pdf');

// Also add the print route if you want to use it
Route::get('/laporan-operasional/print', [LaporanOperasionalController::class, 'printReport'])
    ->name('laporan-operasional.print');

    // routes/web.php (Laravel)
Route::get('/obat/{id}/edit', [ObatController::class, 'edit'])->name('obat.edit');
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';