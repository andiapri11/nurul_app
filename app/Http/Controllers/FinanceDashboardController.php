<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\IncomeExpense;
use Illuminate\Support\Facades\DB;

class FinanceDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. Pemasukan Hari Ini (Student Payments + Other Income)
        $incomeToday = Transaction::whereDate('transaction_date', $today)->sum('amount') 
                       + IncomeExpense::where('type', 'income')->whereDate('transaction_date', $today)->sum('amount');

        // 2. Pengeluaran Hari Ini
        $expenseToday = IncomeExpense::where('type', 'expense')->whereDate('transaction_date', $today)->sum('amount');

        // 3. Jumlah Transaksi Hari Ini
        $trxCountToday = Transaction::whereDate('transaction_date', $today)->count()
                        + IncomeExpense::whereDate('transaction_date', $today)->count();

        // 4. Pemasukan Bulan Ini
        $studentIncomeMonth = Transaction::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $otherIncomeMonth = IncomeExpense::where('type', 'income')->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $totalIncomeMonth = $studentIncomeMonth + $otherIncomeMonth;

        // 5. Pengeluaran Bulan Ini
        $expenseMonth = IncomeExpense::where('type', 'expense')->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->sum('amount');

        // 6. Saldo Kas (Simple Calculation: Total All In - Total All Out)
        // Note: Ideally this should come from a proper ledger, but for now sum all.
        $allIn = Transaction::sum('amount') + IncomeExpense::where('type', 'income')->sum('amount');
        $allOut = IncomeExpense::where('type', 'expense')->sum('amount');
        $currentBalance = $allIn - $allOut;

        // 7. Action Required counts
        $pendingVerifications = \App\Models\PaymentRequest::where('status', 'pending')->count();
        $pendingRealizations = \App\Models\IncomeExpense::where('type', 'expense')
            ->where('proof_status', 'pending')
            ->whereNotNull('procurement_request_code')
            ->count();

        // Stats Array
        $stats = [
            'pemasukan_hari_ini' => $incomeToday,
            'pengeluaran_hari_ini' => $expenseToday,
            'jumlah_transaksi' => $trxCountToday,
            'pemasukan_bulan_ini' => $totalIncomeMonth,
            'pengeluaran_bulan_ini' => $expenseMonth,
            'saldo_akhir' => $currentBalance,
            'pending_verifications' => $pendingVerifications,
            'pending_realizations' => $pendingRealizations
        ];

        // Recent Transactions (Combine Student & Other)
        // We can't easily union different tables with Eloquent but we can fetch separately and merge.
        $recentStudentParams = Transaction::with('student.schoolClass', 'paymentType')->latest('transaction_date')->take(5)->get();
        // Map to common structure
        $recentPayments = $recentStudentParams->map(function($trx) {
            return (object)[
                'type_label' => 'Pembayaran Siswa',
                'description' => $trx->student->nama_lengkap . ' (' . ($trx->paymentType->name ?? '-') . ')',
                'amount' => $trx->amount,
                'is_income' => true,
                'date' => $trx->transaction_date
            ];
        });
        
        // Add recent expenses/other income if needed, but for "Recent Payments" usually implies Revenue.
        // Let's call it "Recent Activity" in the view and include expenses?
        // For now, let's keep it strictly 'Recent Incoming' for the table, or separate.
        // I'll stick to Recent Payments for the view table as per typical request, 
        // but maybe add other income too. 
        
        // Let's just pass recent student payments as 'recentPayments' for now.

        // Chart Data (Last 12 Months)
        $labels = [];
        $incomeData = [];
        $expenseData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');
            
            $mStart = $month->copy()->startOfMonth();
            $mEnd = $month->copy()->endOfMonth();
            
            $inc = Transaction::whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount') 
                   + IncomeExpense::where('type', 'income')->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');
            $exp = IncomeExpense::where('type', 'expense')->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');
            
            $incomeData[] = $inc;
            $expenseData[] = $exp;
        }

        $chartData = [
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData
        ];

        return view('finance.dashboard', compact('stats', 'chartData', 'recentPayments'));
    }
}
