<?php  

namespace App\Http\Controllers;  

use Illuminate\Http\Request; 
use App\Models\Appointment; 
use App\Models\User; 
use App\Models\Patient; 
use Illuminate\Support\Facades\Auth; 
use Inertia\Inertia;  
use Carbon\Carbon;


class AppointmentController extends Controller 
{ 
    public function create() 
    {      
        // dd(Auth::user());     
        $user = Auth::user();     
        $patient = $user->patient;     
        
        return Inertia::render('pasien/JanjiTemu', [     
            'patientName' => $patient ? $patient->nama_lengkap : 'Nama Tidak Ditemukan',     
            'patientId' => $patient ? $patient->id : null, 
        ]); 
    }   
    
public function store(Request $request) 
{
    $validated = $request->validate([
        'tanggal' => 'required|date',
        'jam_konsultasi' => 'required|date_format:H:i',
        'keluhan' => 'required|string|max:1000',
    ]);

    $user = Auth::user();
    $patient = $user->patient;

    if (!$patient) {
        return redirect()->back()->withErrors(['pasien_id' => 'Data pasien tidak ditemukan untuk user ini.']);
    }

    // Cek jika sudah ada janji temu aktif
    $existingAppointment = Appointment::where('pasien_id', $patient->id)
        ->whereIn('status', ['menunggu', 'dikonfirmasi'])
        ->first();

    if ($existingAppointment) {
        return redirect()->back()->withErrors(['error' => 'Anda sudah memiliki janji temu yang masih aktif.']);
    }

    $validated['pasien_id'] = $patient->id;
    $validated['dibuat_oleh'] = 'pasien';
    $validated['dokter_id'] = 1;

    $appointment = Appointment::create($validated);

    return redirect()->route('dashboard');
}


    /**
     * Cancel appointment yang sudah lewat
     */
    public function cancelAppointment(Request $request)
    {
        try {
            $request->validate([
                'appointment_id' => 'required|integer|exists:appointments,id'
            ]);

            $appointmentId = $request->appointment_id;
            $user = Auth::user();
            $patient = $user->patient;

            if (!$patient) {
                return back()->withErrors(['error' => 'Data pasien tidak ditemukan']);
            }

            // Cari appointment berdasarkan ID dan pasien_id
            $appointment = Appointment::where('id', $appointmentId)
                                    ->where('pasien_id', $patient->id)
                                    ->first();

            if (!$appointment) {
                return back()->withErrors(['error' => 'Janji temu tidak ditemukan']);
            }

            // Cek apakah appointment memang sudah lewat
            $appointmentDateTime = \Carbon\Carbon::parse($appointment->tanggal . ' ' . $appointment->jam_konsultasi);
            $now = \Carbon\Carbon::now();

            if ($appointmentDateTime->greaterThan($now)) {
                return back()->withErrors(['error' => 'Tidak dapat membatalkan janji temu yang belum lewat']);
            }

            // Update status menjadi 'dibatalkan' (sesuai enum yang sudah ada)
            $appointment->update([
                'status' => 'dibatalkan'
            ]);

            return back()->with('success', 'Janji temu berhasil dibatalkan');

        } catch (\Exception $e) {
            \Log::error('Error cancelling appointment: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membatalkan janji temu']);
        }
    }

   public function checkIn(Request $request) 
{
    try {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
        ]);

        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            return back()->withErrors(['error' => 'Data pasien tidak ditemukan']);
        }

        // Cari appointment berdasarkan ID dan pastikan milik pasien yang login
        $appointment = Appointment::where('id', $request->appointment_id)
                                ->where('pasien_id', $patient->id)
                                ->first();

        if (!$appointment) {
            return back()->withErrors(['error' => 'Janji temu tidak ditemukan']);
        }

        // Cek apakah sudah check-in sebelumnya
        if ($appointment->checked_in_at) {
            return back()->withErrors(['error' => 'Anda sudah melakukan check-in sebelumnya']);
        }

        // Simpan jam check-in sekarang
        $appointment->checked_in_at = Carbon::now();
        $appointment->save();

        return redirect()->back()->with('success', 'Check-in berhasil!');

    } catch (\Exception $e) {
        \Log::error('Error during check-in: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Terjadi kesalahan saat check-in']);
    }
}
}