<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeExpense;
use App\Models\Unit;
use App\Models\IncomeCategory;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;

class FinanceIncomeController extends Controller
{
    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $activeAY = AcademicYear::where('status', 'active')->first() ?? $academicYears->first();
        $academic_year_id = $request->get('academic_year_id', $activeAY ? $activeAY->id : null);
        $selectedAY = AcademicYear::find($academic_year_id);

        $query = IncomeExpense::where('type', 'income')->with('unit', 'user')->latest('transaction_date');
        
        // Academic Year Filter (if no specific date range is provided, default to current AY)
        if (!$request->date_start && !$request->date_end && $selectedAY) {
            $query->where(function($q) use ($selectedAY) {
                $q->where(function($sq) use ($selectedAY) {
                    $sq->whereMonth('transaction_date', '>=', 7)->whereYear('transaction_date', $selectedAY->start_year);
                })->orWhere(function($sq) use ($selectedAY) {
                    $sq->whereMonth('transaction_date', '<=', 6)->whereYear('transaction_date', $selectedAY->end_year);
                });
            });
        }

        // Filters
        if ($request->unit_id && $request->unit_id != 'all') {
            $query->where('unit_id', $request->unit_id);
        }
        
        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->date_start) {
            $query->whereDate('transaction_date', '>=', $request->date_start);
        }
        
        if ($request->date_end) {
            $query->whereDate('transaction_date', '<=', $request->date_end);
        }
        
        // Stats
        $stats = [
            'total' => (clone $query)->sum('amount'),
            'this_month' => IncomeExpense::where('type', 'income')->whereMonth('transaction_date', date('m'))->whereYear('transaction_date', date('Y'))->sum('amount'),
            'today' => IncomeExpense::where('type', 'income')->whereDate('transaction_date', date('Y-m-d'))->sum('amount'),
        ];
        
        $incomes = $query->paginate(10)->appends($request->all());
        $units = Unit::all();
        $categories = IncomeCategory::all(); 
        $bankAccounts = \App\Models\BankAccount::where('is_active', true)->get();
        
        return view('finance.income.index', compact('incomes', 'units', 'categories', 'stats', 'bankAccounts', 'academicYears', 'academic_year_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'nullable|exists:units,id',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:tunai,transfer',
            'bank_account_id' => 'required_if:payment_method,transfer|nullable|exists:bank_accounts,id',
            'payer_name' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
            'security_pin' => 'required|digits:6'
        ]);

        // Validate PIN
        if (!\Illuminate\Support\Facades\Hash::check($request->security_pin, auth()->user()->security_pin)) {
            return redirect()->back()->withInput()->with('error', 'Gagal: PIN Keamanan Salah.');
        }

        $income = IncomeExpense::create([
            'unit_id' => $request->unit_id,
            'type' => 'income',
            'category' => $request->category,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'bank_account_id' => $request->bank_account_id,
            'payer_name' => $request->payer_name,
            'transaction_date' => $request->transaction_date,
            'description' => $request->description,
            'user_id' => auth()->id() ?? 1
        ]);

        return back()->with('success', 'Pemasukan berhasil dicatat.');
    }
    
    public function destroy(Request $request, IncomeExpense $income)
    {
        // 1. Check Role
        if (!in_array(auth()->user()->role, ['administrator', 'kepala_keuangan'])) {
            return back()->with('error', 'Gagal: Anda tidak memiliki akses untuk menghapus data ini.');
        }

        // 2. Validate PIN
        $request->validate([
            'security_pin' => 'required|digits:6'
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->security_pin, auth()->user()->security_pin)) {
            return back()->with('error', 'Gagal: PIN Keamanan Salah.');
        }

        $income->delete();
        return back()->with('success', 'Data pemasukan berhasil dihapus.');
    }

    // Category Management
    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:income_categories,name', 'description' => 'nullable|string']);
        IncomeCategory::create($request->all());
        return back()->with('success', 'Kategori penerimaan berhasil ditambahkan.');
    }

    public function destroyCategory(IncomeCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Kategori dihapus.');
    }

    public function print(IncomeExpense $income)
    {
        if ($income->type !== 'income') abort(404);
        return view('finance.income.print', compact('income'));
    }
}
