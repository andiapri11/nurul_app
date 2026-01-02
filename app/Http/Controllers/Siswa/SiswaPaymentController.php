<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\StudentBill;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SiswaPaymentController extends Controller
{
    public function history(Request $request)
    {
        $userSiswa = auth()->guard('student')->user();
        if (!$userSiswa || !$userSiswa->student) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $student = $userSiswa->student;
        $transactions = Transaction::where('student_id', $student->id)
            ->where('is_void', false)
            ->with(['items.paymentType', 'user'])
            ->latest()
            ->paginate(10);

        return view('siswa.payments.history', compact('transactions'));
    }

    public function arrears(Request $request)
    {
        $userSiswa = auth()->guard('student')->user();
        if (!$userSiswa || !$userSiswa->student) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $student = $userSiswa->student;
        $now = now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // Fetch all unpaid/partial bills
        $bills = StudentBill::where('student_id', $student->id)
            ->whereIn('status', ['unpaid', 'partial'])
            ->where('is_free', false)
            ->with(['paymentType', 'academicYear'])
            ->orderBy('year', 'asc')
            ->orderByRaw('CAST(month AS UNSIGNED) ASC')
            ->get();

        // Process each bill to determine if it's overdue or upcoming
        $bills->each(function($bill) use ($currentMonth, $currentYear) {
            $isOverdue = true;

            if ($bill->paymentType->type === 'monthly') {
                $billYear = $bill->year;
                // Derive year from academic year if null
                if (!$billYear && $bill->academicYear) {
                    $billYear = ($bill->month >= 7) ? $bill->academicYear->start_year : ($bill->academicYear->start_year + 1);
                }

                if ($billYear > $currentYear) {
                    $isOverdue = false;
                } elseif ($billYear == $currentYear && $bill->month > $currentMonth) {
                    $isOverdue = false;
                }
            } else {
                // For non-monthly: check due_date if exists, else it's immediate
                if ($bill->due_date && \Carbon\Carbon::parse($bill->due_date)->isFuture()) {
                    $isOverdue = false;
                }
            }
            
            $bill->is_overdue = $isOverdue;
        });

        // Total arrears only counts overdue items
        $totalArrears = $bills->where('is_overdue', true)->sum(function($b) {
            return $b->amount - $b->paid_amount;
        });

        // Group by Payment Type Name
        $groupedBills = $bills->groupBy(function($bill) {
            return $bill->paymentType->name ?? 'Lainnya';
        });

        return view('siswa.payments.arrears', compact('groupedBills', 'totalArrears'));
    }
}
