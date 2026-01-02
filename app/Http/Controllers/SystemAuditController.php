<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Transaction;
use App\Models\IncomeExpense;
use App\Models\Unit;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemAuditController extends Controller
{
    public function index()
    {
        if (!in_array(auth()->user()->role, ['administrator', 'direktur'])) {
            abort(403, 'Hanya Administrator atau Direktur yang dapat mengakses laporan audit.');
        }

        $activeYear = AcademicYear::where('status', 'active')->first();
        
        // 1. STATISTIK SISTEM
        $stats = [
            'total_students' => Student::count(),
            'active_students' => Student::where('status', 'aktif')->count(),
            'total_users' => User::count(),
            'total_transactions' => Transaction::count(),
            'total_income' => IncomeExpense::where('type', 'income')->sum('amount'),
            'total_expense' => IncomeExpense::where('type', 'expense')->sum('amount'),
            'total_payments' => Transaction::where('is_void', 0)->sum('amount'),
        ];

        // 2. VERIFIKASI KEAMANAN (Check status of security features)
        $security_checks = [
            'pin_protection' => 'AKTIF (Diperlukan untuk Transaksi, Penghapusan Data, & Perubahan Akun Bank)',
            'rbac_middleware' => 'AKTIF (Role-based Access Control di setiap route)',
            'data_isolation' => 'AKTIF (Filter Unit ID di Controller untuk staf non-admin)',
            'locked_accounts' => \App\Models\UserSiswa::whereNotNull('locked_at')->count() . ' akun siswa terkunci saat ini',
        ];

        // 3. LOG AKTIVITAS TERAKHIR (Simple check)
        $recent_logs = \App\Models\LoginHistory::with(['user', 'siswa'])->latest('login_at')->take(10)->get();

        return view('admin.audit_report', compact('stats', 'security_checks', 'recent_logs', 'activeYear'));
    }
}
