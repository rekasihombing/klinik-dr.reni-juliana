<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $staff = Staff::query()
            ->when($search, fn($query) => $query->search($search))
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Doctor/StaffManagement', [
            'staff' => $staff,
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'telepon' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();

        try {
            // Simpan ke tabel users
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'staff', // Role otomatis jadi staff
            ]);

            // Simpan ke tabel staff
            Staff::create([
                'user_id' => $user->id,
                'nama_lengkap' => $validated['nama_lengkap'],
                'telepon' => $validated['telepon'] ?? '',
                'email' => $validated['email'],
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Staff berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menambahkan staff: ' . $e->getMessage()]);
        }
    }

 public function edit(Request $request)
{
    $staff = [
        'id' => $request->input('staff_id'),
        'nama_lengkap' => $request->input('nama_lengkap'),
        'email' => $request->input('email'),
        'telepon' => $request->input('telepon'),
        'user_id' => $request->input('user_id'),
    ];

    return Inertia::render('Doctor/EditStaff', [
        'staff' => $staff
    ]);
}

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($staff->user_id)],
            'telepon' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();

        try {
            // Update tabel users
            $updateUserData = [
                'email' => $validated['email'],
            ];

            // Jika password diisi, maka update password
            if (!empty($validated['password'])) {
                $updateUserData['password'] = Hash::make($validated['password']);
            }

            $staff->user->update($updateUserData);

            // Update tabel staff
            $staff->update([
                'nama_lengkap' => $validated['nama_lengkap'],
                'telepon' => $validated['telepon'] ?? '',
                'email' => $validated['email'],
            ]);

            DB::commit();

            return redirect()->route('staff.index')
                         ->with('success', 'Staff berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui staff: ' . $e->getMessage()]);
        }
    }

    public function destroy(Staff $staff)
    {
        DB::beginTransaction();

        try {
            // Hapus data staff
            $staff->delete();
            
            // Hapus data user yang terkait
            $staff->user->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Staff berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus staff: ' . $e->getMessage()]);
        }
    }
}