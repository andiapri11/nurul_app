<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Attempt Admin Login (Web Guard)
        if (Auth::guard('web')->attempt([$loginType => $request->login, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // Check for locked out student
        $studentUser = \App\Models\UserSiswa::where($loginType, $request->login)->first();
        if ($studentUser && $studentUser->locked_at) {
            $lockoutDuration = 30; // minutes
            $lockedTime = \Carbon\Carbon::parse($studentUser->locked_at);
            if ($lockedTime->addMinutes($lockoutDuration)->isFuture()) {
                 return back()->withErrors([
                    'login' => 'Akun Anda terkunci karena 5x salah password. Hubungi Admin untuk reset atau tunggu 30 menit.',
                ])->onlyInput('login');
            } else {
                // Unlock automatically if time passed
                $studentUser->update(['locked_at' => null, 'login_attempts' => 0]);
            }
        }

        // Attempt Student Login (Student Guard)
        if (Auth::guard('student')->attempt([$loginType => $request->login, 'password' => $request->password])) {
            $user = Auth::guard('student')->user();
            
            // RESET ATTEMPTS ON SUCCESS
            if ($user->login_attempts > 0 || $user->locked_at) {
                $user->update(['login_attempts' => 0, 'locked_at' => null]);
            }

            // Check student status using the relationship from UserSiswa
            // Since we moved status to students table, and UserSiswa hasOne Student
            // We assume UserSiswa->student->status
            // AND check UserSiswa status itself
            if (($user->student && $user->student->status === 'non-aktif') || ($user->status && $user->status === 'non-aktif')) {
                Auth::guard('student')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'login' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator untuk login kembali.',
                ])->onlyInput('login');
            }

            $request->session()->regenerate();
            // Redirect to student dashboard if different, or same dashboard
            return redirect()->intended(route('siswa.dashboard'));
        }

        // FAILED ATTEMPT LOGIC
        if ($studentUser) {
            $studentUser->increment('login_attempts');
            if ($studentUser->login_attempts >= 5) {
                $studentUser->update(['locked_at' => now()]);
                return back()->withErrors([
                    'login' => 'Akun Anda telah dikunci karena 5x salah password. Hubungi Admin.',
                ])->onlyInput('login');
            }
        }

        return back()->withErrors([
            'login' => 'Username/Email atau password yang Anda masukkan salah.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function dashboard(Request $request)
    {
        if (Auth::user()->role === 'mading') {
            return redirect()->route('mading.index');
        }

        if (Auth::user()->role === 'guru') {
            return app(\App\Http\Controllers\GuruDashboardController::class)->index();
        }

        if (in_array(Auth::user()->role, ['admin', 'administrator', 'direktur'])) {
            return app(\App\Http\Controllers\AdminDashboardController::class)->index($request);
        }

        if (Auth::user()->isKepalaSekolah()) {
            return app(\App\Http\Controllers\PrincipalController::class)->index($request);
        }

        if (in_array(Auth::user()->role, ['admin_keuangan', 'kepala_keuangan'])) {
            return app(\App\Http\Controllers\FinanceDashboardController::class)->index();
        }

        if (Auth::user()->getKesiswaanUnits()->count() > 0) {
            return app(\App\Http\Controllers\KesiswaanDashboardController::class)->index();
        }

        return view('dashboard');
    }
}
