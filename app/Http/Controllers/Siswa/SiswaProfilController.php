<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student; 
        
        // Eager load unit, current class, and class history
        if($student) {
             $student->load(['unit', 'schoolClass.academicYear', 'classes.academicYear']);
        }

        return view('siswa.profil', compact('user', 'student'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
        ]);

        $user = Auth::user();

        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Kata sandi saat ini salah.');
        }

        $user->update([
            'password' => \Hash::make($request->password),
            'plain_password' => $request->password
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
