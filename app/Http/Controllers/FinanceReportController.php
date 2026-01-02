<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentBill;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\AcademicYear;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceReportController extends Controller
{
    public function index(Request $request)
    {
        $mode = $request->get('mode', 'annual'); // daily, monthly, annual
        $unit_id = $request->get('unit_id');
        $units = Unit::all();
        
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $activeAY = AcademicYear::where('status', 'active')->first() ?? $academicYears->first();
        $academic_year_id = $request->get('academic_year_id', $activeAY->id);
        $selectedAY = AcademicYear::find($academic_year_id);

        if (!$selectedAY) {
            $selectedAY = $activeAY;
            $academic_year_id = $selectedAY->id;
        }

        // Filters for Daily/Monthly
        $date = $request->get('date', date('Y-m-d'));
        $month = $request->get('month', date('n')); // This is 1-12
        
        // Logical year for month inside academic year
        // If month is 7-12, it's start_year. If 1-6, it's end_year.
        $actualYear = ($month >= 7) ? $selectedAY->start_year : $selectedAY->end_year;

        // 1. Base Query for Transactions (Incomes)
        $incomeQuery = Transaction::where('is_void', 0);
        if ($unit_id) $incomeQuery->where('unit_id', $unit_id);
        
        // 2. Base Query for Bills (Expectations)
        $billQuery = StudentBill::where('academic_year_id', $academic_year_id);
        if ($unit_id) {
            $billQuery->whereHas('student', function($q) use ($unit_id) {
                $q->where('unit_id', $unit_id);
            });
        }

        if ($mode == 'daily') {
            $incomeQuery->whereDate('transaction_date', $date);
            // Bill stats are usually not daily, but we can show stats for that specific day if needed.
            // For daily, we focus on the cash flow (realized income).
        } elseif ($mode == 'monthly') {
            $incomeQuery->whereMonth('transaction_date', $month)->whereYear('transaction_date', $actualYear);
            $billQuery->where('month', $month)->where('year', $actualYear);
        } elseif ($mode == 'annual') {
            // Scope income to the selected academic year range
            $incomeQuery->where(function($q) use ($selectedAY) {
                $q->where(function($sq) use ($selectedAY) {
                    $sq->whereMonth('transaction_date', '>=', 7)->whereYear('transaction_date', $selectedAY->start_year);
                })->orWhere(function($sq) use ($selectedAY) {
                    $sq->whereMonth('transaction_date', '<=', 6)->whereYear('transaction_date', $selectedAY->end_year);
                });
            });
            // BillQuery already scoped to academic_year_id
        }

        $realizedIncome = $incomeQuery->sum('amount');
        $totalBillAmount = $billQuery->sum('amount');
        $paidBillAmount = $billQuery->sum('paid_amount');
        $remainingBillAmount = $totalBillAmount - $paidBillAmount;

        // Breakdown by payment type
        $incomeByType = Transaction::where('is_void', 0)
            ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('payment_types', 'transaction_items.payment_type_id', '=', 'payment_types.id')
            ->select('payment_types.name', DB::raw('SUM(transaction_items.amount) as total'))
            ->groupBy('payment_types.name');

        if ($unit_id) $incomeByType->where('transactions.unit_id', $unit_id);

        if ($mode == 'daily') {
            $incomeByType->whereDate('transactions.transaction_date', $date);
        } elseif ($mode == 'monthly') {
            $incomeByType->whereMonth('transactions.transaction_date', $month)->whereYear('transactions.transaction_date', $actualYear);
        } elseif ($mode == 'annual') {
            $incomeByType->where(function($q) use ($selectedAY) {
                $q->where(function($sq) use ($selectedAY) {
                    $sq->whereMonth('transactions.transaction_date', '>=', 7)->whereYear('transactions.transaction_date', $selectedAY->start_year);
                })->orWhere(function($sq) use ($selectedAY) {
                    $sq->whereMonth('transactions.transaction_date', '<=', 6)->whereYear('transactions.transaction_date', $selectedAY->end_year);
                });
            });
        }
        
        $incomeByType = $incomeByType->get();

        return view('finance.reports.index', compact(
            'mode', 'units', 'unit_id', 'date', 'month', 'academic_year_id', 'academicYears', 'selectedAY',
            'realizedIncome', 'totalBillAmount', 'paidBillAmount', 'remainingBillAmount', 'incomeByType'
        ));
    }

    public function arrears(Request $request)
    {
        $units = Unit::all();
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $activeAY = AcademicYear::where('status', 'active')->first() ?? $academicYears->first();
        
        $selectedUnitId = $request->get('unit_id');
        $selectedClassId = $request->get('class_id');
        $selectedYearId = $request->get('academic_year_id', $activeAY->id);
        
        $classes = $selectedUnitId ? \App\Models\SchoolClass::where('unit_id', $selectedUnitId)->where('academic_year_id', $selectedYearId)->get() : collect();

        if ($selectedUnitId || $selectedClassId) {
            // Query ALL students in the selected class/unit for this academic year
            $query = \App\Models\Student::with(['classes' => function($q) use ($selectedYearId) {
                            $q->where('class_student.academic_year_id', $selectedYearId);
                          }, 'unit'])
                          ->whereHas('classes', function($q) use ($selectedYearId, $selectedClassId) {
                            $q->where('class_student.academic_year_id', $selectedYearId);
                            if ($selectedClassId) $q->where('classes.id', $selectedClassId);
                          });

            if ($selectedUnitId) $query->where('unit_id', $selectedUnitId);

            $students = $query->orderBy('nama_lengkap')->get();

            // Get ALL bills for these students for the current year to build a complete map
            $allBills = \App\Models\StudentBill::with('paymentType')
                ->whereIn('student_id', $students->pluck('id'))
                ->where('academic_year_id', $selectedYearId)
                ->get()
                ->groupBy('student_id');
        } else {
            $students = collect();
            $allBills = collect();
        }

        return view('finance.reports.arrears', compact(
            'units', 'academicYears', 'classes', 'students', 'allBills',
            'selectedUnitId', 'selectedClassId', 'selectedYearId'
        ));
    }
    public function studentPayments(Request $request)
    {
        $units = Unit::all();
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $activeAY = AcademicYear::where('status', 'active')->first() ?? $academicYears->first();
        
        $unit_id = $request->get('unit_id');
        $class_id = $request->get('class_id');
        $academic_year_id = $request->get('academic_year_id', $activeAY->id);
        $period = $request->get('period', 'daily'); // daily, weekly, monthly
        $date = $request->get('date', date('Y-m-d'));
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));

        $classes = $unit_id ? \App\Models\SchoolClass::where('unit_id', $unit_id)->where('academic_year_id', $academic_year_id)->get() : collect();

        $query = Transaction::where('is_void', 0)->with(['student.unit', 'items.paymentType', 'user']);

        if ($unit_id) $query->where('unit_id', $unit_id);
        if ($class_id) {
            $query->whereHas('student.classes', function($q) use ($class_id, $academic_year_id) {
                $q->where('classes.id', $class_id)->where('class_student.academic_year_id', $academic_year_id);
            });
        }

        if ($period == 'daily') {
            $query->whereDate('transaction_date', $date);
        } elseif ($period == 'weekly') {
            $startOfWeek = Carbon::parse($date)->startOfWeek();
            $endOfWeek = Carbon::parse($date)->endOfWeek();
            $query->whereBetween('transaction_date', [$startOfWeek, $endOfWeek]);
        } elseif ($period == 'monthly') {
            $query->whereMonth('transaction_date', $month)->whereYear('transaction_date', $year);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        if ($request->get('export') == 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StudentPaymentsExport($transactions, $period, $date, $month, $year), 'Laporan_Pembayaran_Siswa_' . now()->format('Ymd') . '.xlsx');
        }

        if ($request->get('export') == 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance.reports.pdf.student_payments', compact('transactions', 'period', 'date', 'month', 'year', 'unit_id', 'class_id', 'academic_year_id'))->setPaper('a4', 'landscape');
            return $pdf->download('Laporan_Pembayaran_Siswa_' . now()->format('Ymd') . '.pdf');
        }

        return view('finance.reports.student_payments', compact(
            'units', 'academicYears', 'classes', 'transactions',
            'unit_id', 'class_id', 'academic_year_id', 'period', 'date', 'month', 'year'
        ));
    }
    public function generalLedger(Request $request)
    {
        $units = Unit::all();
        $type = $request->get('type'); // null (all), income, expense
        $unit_id = $request->get('unit_id');
        $category = $request->get('category');
        $period = $request->get('period', 'daily');
        $date = $request->get('date', date('Y-m-d'));
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));

        // Fetch unique categories for filter dropdown
        $categoriesQuery = \App\Models\IncomeExpense::query();
        if ($type) $categoriesQuery->where('type', $type);
        $categories = $categoriesQuery->distinct()->pluck('category')->filter()->values();

        $query = \App\Models\IncomeExpense::with(['unit', 'user']);

        if ($type) $query->where('type', $type);
        if ($unit_id) $query->where('unit_id', $unit_id);
        if ($category) $query->where('category', $category);

        if ($period == 'daily') {
            $query->whereDate('transaction_date', $date);
        } elseif ($period == 'weekly') {
            $startOfWeek = Carbon::parse($date)->startOfWeek();
            $endOfWeek = Carbon::parse($date)->endOfWeek();
            $query->whereBetween('transaction_date', [$startOfWeek, $endOfWeek]);
        } elseif ($period == 'monthly') {
            $query->whereMonth('transaction_date', $month)->whereYear('transaction_date', $year);
        }

        $records = $query->orderBy('transaction_date', 'desc')->get();

        if ($request->get('export') == 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\GeneralLedgerExport($records, $period, $date, $month, $year), 'Laporan_Kas_Umum_' . now()->format('Ymd') . '.xlsx');
        }

        if ($request->get('export') == 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance.reports.pdf.general_ledger', compact('records', 'period', 'date', 'month', 'year', 'unit_id', 'type', 'category'))->setPaper('a4', 'landscape');
            return $pdf->download('Laporan_Kas_Umum_' . now()->format('Ymd') . '.pdf');
        }

        return view('finance.reports.general_ledger', compact(
            'units', 'records', 'unit_id', 'type', 'period', 'date', 'month', 'year', 'categories', 'category'
        ));
    }

}
