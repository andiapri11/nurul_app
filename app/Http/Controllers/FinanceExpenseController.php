<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeExpense;
use App\Models\Unit;
use App\Models\ExpenseCategory;
use App\Models\AcademicYear;

class FinanceExpenseController extends Controller
{
    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $activeAY = AcademicYear::where('status', 'active')->first() ?? $academicYears->first();
        $academic_year_id = $request->get('academic_year_id', $activeAY ? $activeAY->id : null);
        $selectedAY = AcademicYear::find($academic_year_id);

        $query = IncomeExpense::where('type', 'expense')->with('unit', 'user')->latest('transaction_date');
        
        // Academic Year Filter
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
            'this_month' => IncomeExpense::where('type', 'expense')->whereMonth('transaction_date', date('m'))->whereYear('transaction_date', date('Y'))->sum('amount'),
            'today' => IncomeExpense::where('type', 'expense')->whereDate('transaction_date', date('Y-m-d'))->sum('amount'),
        ];
        
        $expenses = $query->paginate(10)->appends($request->all());
        $units = Unit::all();
        $categories = ExpenseCategory::all(); 

        // 1. Pending Disbursements (Validated by Principal AND Approved by Director)
        $recordedCodes = IncomeExpense::whereNotNull('procurement_request_code')->pluck('procurement_request_code')->toArray();
        $pendingDisbursements = \App\Models\ProcurementRequest::where('principal_status', 'Validated')
            ->where('director_status', 'Approved')
            ->where('report_status', 'Pending') 
            ->whereNotIn('request_code', $recordedCodes)
            ->with(['unit', 'user', 'category'])
            ->get()
            ->groupBy('request_code')
            ->map(function($items) {
                $first = $items->first();
                $total = $items->sum(function($item) {
                    $p = $item->approved_price ?: $item->estimated_price;
                    $q = $item->approved_quantity ?: $item->quantity;
                    return (float)$p * (float)$q;
                });
                return (object)[
                    'request_code' => $first->request_code,
                    'activity_name' => $first->activity_name,
                    'unit_name' => $first->unit->name,
                    'unit_id' => $first->unit_id,
                    'user' => $first->user,
                    'total_amount' => $total,
                    'items_count' => $items->count(),
                    'report_status' => $first->report_status,
                    'items' => $items->map(function($i) {
                        return [
                            'item_name' => $i->item_name,
                            'quantity' => $i->approved_quantity ?: $i->quantity,
                            'unit_name' => $i->unit_name,
                            'price' => $i->approved_price ?: $i->estimated_price,
                            'category_name' => $i->category ? $i->category->name : 'N/A'
                        ];
                    })
                ];
            });

        // 2. Pending Report Verifications
        $pendingReports = \App\Models\ProcurementRequest::where('report_status', 'Reported')
            ->with(['unit', 'user', 'category'])
            ->get()
            ->groupBy('request_code')
            ->map(function($items) {
                $first = $items->first();
                $total = $items->sum(function($item) {
                    $p = $item->approved_price ?: $item->estimated_price;
                    $q = $item->approved_quantity ?: $item->quantity;
                    return (float)$p * (float)$q;
                });
                return (object)[
                    'request_code' => $first->request_code,
                    'activity_name' => $first->activity_name,
                    'unit_name' => $first->unit->name,
                    'unit_id' => $first->unit_id,
                    'total_amount' => $total,
                    'items_count' => $items->count(),
                    'report_status' => $first->report_status,
                    'report_nota' => $first->report_nota,
                    'report_photo' => $first->report_photo,
                    'report_note' => $first->report_note,
                    'report_at' => $first->report_at,
                    'items' => $items->map(function($i) {
                        return [
                            'item_name' => $i->item_name,
                            'quantity' => $i->approved_quantity ?: $i->quantity,
                            'unit_name' => $i->unit_name,
                            'price' => $i->approved_price ?: $i->estimated_price,
                            'category_name' => $i->category ? $i->category->name : 'N/A'
                        ];
                    })
                ];
            });
        
        $bankAccounts = \App\Models\BankAccount::where('is_active', true)->get();
        
        return view('finance.expense.index', compact('expenses', 'units', 'categories', 'stats', 'pendingDisbursements', 'pendingReports', 'bankAccounts', 'academicYears', 'academic_year_id'));
    }

    public function storeProcurementExpense(Request $request)
    {
        $request->validate([
            'request_code' => 'required|string',
            'category' => 'required|string',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|in:tunai,transfer',
            'bank_account_id' => 'required_if:payment_method,transfer|nullable|exists:bank_accounts,id',
            'payer_name' => 'nullable|string|max:255', // Handled as recipient here
            'amount' => 'required|numeric',
            'security_pin' => 'required|digits:6'
        ]);

        // Validate PIN
        if (!\Illuminate\Support\Facades\Hash::check($request->security_pin, auth()->user()->security_pin)) {
            return redirect()->back()->withInput()->with('error', 'Gagal: PIN Keamanan Salah.');
        }

        $batch = \App\Models\ProcurementRequest::where('request_code', $request->request_code)
            ->where('director_status', 'Approved')
            ->get();

        if ($batch->isEmpty()) {
            return back()->with('error', 'Data pengajuan tidak ditemukan atau belum disetujui.');
        }

        $first = $batch->first();

        $expense = IncomeExpense::create([
            'unit_id' => $first->unit_id,
            'type' => 'expense',
            'category' => $request->category,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'bank_account_id' => $request->bank_account_id,
            'payer_name' => $request->payer_name ?: $request->recipient_display,
            'transaction_date' => $request->transaction_date,
            'description' => "Sarpras: {$first->activity_name} (#{$request->request_code})",
            'procurement_request_code' => $request->request_code,
            'user_id' => auth()->id()
        ]);

        // Save detailed items to expense_items table for itemized printing
        foreach ($batch as $procItem) {
            $qty = $procItem->approved_quantity ?? $procItem->quantity;
            $price = $procItem->approved_price ?? $procItem->estimated_price;
            
            \App\Models\ExpenseItem::create([
                'income_expense_id' => $expense->id,
                'item_name' => $procItem->item_name,
                'quantity' => $qty,
                'unit_name' => $procItem->unit_name,
                'price' => $price,
                'total_price' => $qty * $price
            ]);
        }

        // Update procurement status to Recorded (Awaiting Sarpras Report)
        \App\Models\ProcurementRequest::where('request_code', $request->request_code)->update([
            'report_status' => 'Recorded'
        ]);

        return back()->with('success', 'Pengeluaran sarpras berhasil dicatat. Menunggu Sarpras upload bukti nota.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'nullable|exists:units,id',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:tunai,transfer',
            'bank_account_id' => 'required_if:payment_method,transfer|nullable|exists:bank_accounts,id',
            'payer_name' => 'nullable|string|max:255', // Recipient
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
            'security_pin' => 'required|digits:6'
        ]);

        // Validate PIN
        if (!\Illuminate\Support\Facades\Hash::check($request->security_pin, auth()->user()->security_pin)) {
            return redirect()->back()->withInput()->with('error', 'Gagal: PIN Keamanan Salah.');
        }
        
        $request->validate([
            'nota_file' => 'nullable|image|max:2048'
        ]);

        $expense = IncomeExpense::create([
            'unit_id' => $request->unit_id,
            'type' => 'expense',
            'category' => $request->category,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'bank_account_id' => $request->bank_account_id,
            'payer_name' => $request->payer_name,
            'transaction_date' => $request->transaction_date,
            'description' => $request->description,
            'user_id' => auth()->id() ?? 1,
            'is_proof_needed' => $request->has('is_proof_needed') || $request->hasFile('nota_file'),
            'proof_status' => $request->hasFile('nota_file') ? 'Verified' : ($request->has('is_proof_needed') ? 'Pending' : 'Pending'),
            'proof_code' => ($request->has('is_proof_needed') || $request->hasFile('nota_file')) ? 'EXP-PRF-' . date('YmdHis') . '-' . rand(100, 999) : null
        ]);

        if ($request->hasFile('nota_file')) {
            $path = $request->file('nota_file')->store('expenses/proofs', 'public');
            $expense->update(['nota' => $path]);
        }

        // Save items if any
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $item) {
                if (!empty($item['name']) && !empty($item['qty']) && !empty($item['price'])) {
                    \App\Models\ExpenseItem::create([
                        'income_expense_id' => $expense->id,
                        'item_name' => $item['name'],
                        'quantity' => $item['qty'],
                        'unit_name' => $item['unit'] ?? null,
                        'price' => $item['price'],
                        'total_price' => (float)$item['qty'] * (float)$item['price']
                    ]);
                }
            }
        }

        return back()->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function verifyProcurementReport(Request $request)
    {
        $request->validate([
            'request_code' => 'required|string',
            'status' => 'required|in:Verified,Rejected',
            'finance_note' => 'nullable|string'
        ]);

        $batch = \App\Models\ProcurementRequest::where('request_code', $request->request_code)->get();
        if ($batch->isEmpty()) return back()->with('error', 'Data tidak ditemukan.');

        $first = $batch->first();

        // If verified, update the IncomeExpense entry with the proofs
        if ($request->status === 'Verified') {
            IncomeExpense::where('procurement_request_code', $request->request_code)->update([
                'nota' => $first->report_nota,
                'photo' => $first->report_photo
            ]);
        }

        foreach ($batch as $item) {
            $item->update([
                'report_status' => $request->status,
                'finance_approved_at' => now(),
                'finance_note' => $request->finance_note
            ]);
        }

        return back()->with('success', 'Laporan pengadaan berhasil ' . ($request->status === 'Verified' ? 'diverifikasi' : 'ditolak') . '.');
    }

    public function destroy(Request $request, IncomeExpense $expense)
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

        // Save code for message or further processing before deletion
        $requestCode = $expense->procurement_request_code;

        // If this is a Sarpras procurement expense, revert the procurement status to Belum Cair (Pending)
        if ($requestCode) {
            \App\Models\ProcurementRequest::where('request_code', $requestCode)->update([
                'report_status' => 'Pending',
                'report_nota' => null,
                'report_photo' => null,
                'report_at' => null,
                'report_note' => null,
                'finance_approved_at' => null,
                'finance_note' => null
            ]);
        }

        // Delete associated items first to be safe (if not already handled by cascade)
        $expense->items()->delete();
        
        $expense->delete();

        $message = 'Data pengeluaran berhasil dihapus.';
        if ($requestCode) {
            $message .= ' Status pengajuan sarpras #' . $requestCode . ' dikembalikan ke Belum Cair.';
        }

        return back()->with('success', $message);
    }

    // Category Management
    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:expense_categories,name', 'description' => 'nullable|string']);
        ExpenseCategory::create($request->all());
        return back()->with('success', 'Kategori pengeluaran berhasil ditambahkan.');
    }

    public function destroyCategory(ExpenseCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Kategori dihapus.');
    }

    public function print(IncomeExpense $expense)
    {
        if ($expense->type !== 'expense') abort(404);

        // Fallback for itemized view if expense_items table is empty but linked to procurement
        if ($expense->items->isEmpty() && $expense->procurement_request_code) {
            $procItems = \App\Models\ProcurementRequest::where('request_code', $expense->procurement_request_code)->get();
            
            $virtualItems = $procItems->map(function($pi) {
                return (object)[
                    'item_name' => $pi->item_name,
                    'quantity' => $pi->approved_quantity ?? $pi->quantity,
                    'unit_name' => $pi->unit_name,
                    'price' => $pi->approved_price ?? $pi->estimated_price,
                    'total_price' => ($pi->approved_quantity ?? $pi->quantity) * ($pi->approved_price ?? $pi->estimated_price)
                ];
            });

            // Temporarily set items for the view
            $expense->setRelation('items', $virtualItems);
        }

        return view('finance.expense.print', compact('expense'));
    }

    public function realization(Request $request)
    {
        $activeAcademicYear = \App\Models\AcademicYear::where('status', 'active')->first();
        $unitId = $request->unit_id;
        $academicYearId = $request->get('academic_year_id', ($activeAcademicYear ? $activeAcademicYear->id : null));

        // Get recorded codes once to determine "Cair" status
        $recordedCodes = IncomeExpense::whereNotNull('procurement_request_code')->pluck('procurement_request_code')->toArray();

        // 1. Pending Report (Belum Lapor & Menunggu Verifikasi)
        // Include 'Recorded' (Disbursed) and 'Reported' (Waiting Verification)
        $pendingQuery = \App\Models\ProcurementRequest::where('director_status', 'Approved')
            ->whereIn('report_status', ['Pending', 'Recorded', 'Reported']);
        
        if ($unitId) $pendingQuery->where('unit_id', $unitId);
        if ($academicYearId) $pendingQuery->where('academic_year_id', $academicYearId);

        $pendingDisbursements = $pendingQuery->with(['unit', 'user'])
            ->get()
            ->groupBy('request_code')
            ->map(function($items) use ($recordedCodes) {
                $first = $items->first();
                $total = $items->sum(function($item) {
                    $p = $item->approved_price ?: $item->estimated_price;
                    $q = $item->approved_quantity ?: $item->quantity;
                    return (float)$p * (float)$q;
                });
                return (object)[
                    'request_code' => $first->request_code,
                    'activity_name' => $first->activity_name,
                    'unit_name' => $first->unit->name,
                    'total_amount' => $total,
                    'items_count' => $items->count(),
                    'is_cair' => in_array($first->request_code, $recordedCodes),
                    'report_status' => $first->report_status, // Add status to distinguish UI
                    'report_nota' => $first->report_nota,
                    'report_photo' => $first->report_photo,
                    'report_note' => $first->report_note,
                    'items' => $items->values() // Pass the items collection
                ];
            });

        // 2. Verified (Sudah Lapor & Terverifikasi)
        $reportedQuery = \App\Models\ProcurementRequest::where('director_status', 'Approved')->where('report_status', 'Verified');
        if ($unitId) $reportedQuery->where('unit_id', $unitId);
        if ($academicYearId) $reportedQuery->where('academic_year_id', $academicYearId);

        $pendingReports = $reportedQuery->with(['unit', 'user'])
            ->get()
            ->groupBy('request_code')
            ->map(function($items) {
                $first = $items->first();
                $total = $items->sum(function($item) {
                    $p = $item->approved_price ?: $item->estimated_price;
                    $q = $item->approved_quantity ?: $item->quantity;
                    return (float)$p * (float)$q;
                });
                return (object)[
                    'request_code' => $first->request_code,
                    'activity_name' => $first->activity_name,
                    'unit_name' => $first->unit->name,
                    'total_amount' => $total,
                    'report_status' => $first->report_status,
                    'report_at' => $first->report_at,
                    'report_nota' => $first->report_nota,
                    'report_photo' => $first->report_photo,
                    'report_note' => $first->report_note,
                    'items' => $items->values(),
                    'expense_id' => \App\Models\IncomeExpense::where('procurement_request_code', $first->request_code)->value('id')
                ];
            });

    // 3. General Expenses with Proof Needed
    $generalExpenses = IncomeExpense::where('type', 'expense')
        ->where('is_proof_needed', true)
        ->with(['unit', 'items']) // Eager load items
        ->get();

    if ($unitId) $generalExpenses = $generalExpenses->where('unit_id', $unitId);

    // Map General Pending (Belum Lapor)
    $generalPending = $generalExpenses->where('proof_status', 'Pending')->map(function($item) {
        $itemsList = $item->items->isNotEmpty() ? $item->items->map(function($sub) {
            return [
                'item_name' => $sub->name,
                'qty' => $sub->quantity,
                'price' => $sub->price,
                'unit' => '',
            ];
        }) : collect([[
            'item_name' => $item->description ?: 'Pengeluaran Umum',
            'qty' => 1,
            'price' => $item->amount,
            'unit' => '',
        ]]);

        return (object)[
            'request_code' => $item->proof_code,
            'activity_name' => $item->description ?: 'Pengeluaran Umum',
            'unit_name' => $item->unit->name ?? 'Umum/Lainnya',
            'total_amount' => $item->amount,
            'items_count' => $item->items->count() ?: 1,
            'is_cair' => true,
            'report_status' => 'Recorded', // Treat as Recorded (Disbursed)
            'is_general_expense' => true,
            'id' => $item->id,
            'items' => $itemsList
        ];
    });

    // Map General Reported (Sudah Lapor)
    $generalReported = $generalExpenses->whereIn('proof_status', ['Reported', 'Verified'])->map(function($item) {
        $itemsList = $item->items->isNotEmpty() ? $item->items->map(function($sub) {
            return [
                'item_name' => $sub->name,
                'qty' => $sub->quantity,
                'price' => $sub->price,
                'unit' => '',
            ];
        }) : collect([[
            'item_name' => $item->description ?: 'Pengeluaran Umum',
            'qty' => 1,
            'price' => $item->amount,
            'unit' => '',
        ]]);

        return (object)[
             'request_code' => $item->proof_code,
             'activity_name' => $item->description ?: 'Pengeluaran Umum',
             'unit_name' => $item->unit->name ?? 'Umum/Lainnya',
             'total_amount' => $item->amount,
             'report_status' => $item->proof_status,
             'report_at' => $item->updated_at,
             'report_nota' => $item->nota,
             'report_photo' => $item->photo,
             'report_note' => $item->description,
             'is_general_expense' => true,
             'id' => $item->id,
             'items' => $itemsList
        ];
    });

    $pendingDisbursements = $pendingDisbursements->toBase()->merge($generalPending);
    $pendingReports = $pendingReports->toBase()->merge($generalReported);

        $categories = ExpenseCategory::all();
        $bankAccounts = \App\Models\BankAccount::where('is_active', true)->get();
        $units = Unit::all();
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();

        return view('finance.realization.index', compact(
            'pendingDisbursements', 'pendingReports', 'categories', 
            'bankAccounts', 'units', 'academicYears', 'activeAcademicYear', 'academicYearId'
        ));
    }
    public function uploadGeneralProof(Request $request, $id)
    {
        $request->validate([
            'nota' => 'required|image|max:2048',
            'photo' => 'nullable|image|max:2048',
        ]);

        $expense = IncomeExpense::findOrFail($id);
        
        $notaPath = $request->file('nota')->store('expenses/proofs', 'public');
        $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('expenses/photos', 'public') : null;
        
        $expense->update([
            'nota' => $notaPath,
            'photo' => $photoPath,
            'proof_status' => 'Verified'
        ]);
        
        return back()->with('success', 'Bukti transaksi berhasil diupload. Laporan Anda kini telah terverifikasi.');
    }

    public function cancelVerification(Request $request)
    {
        if (!in_array(auth()->user()->role, ['administrator', 'direktur', 'admin_keuangan'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'id' => 'nullable|exists:income_expenses,id',
            'request_code' => 'nullable|string',
        ]);

        // Case 1: General Expense Cancellation
        if ($request->filled('id')) {
            $expense = IncomeExpense::findOrFail($request->id);
            $expense->update([
                'proof_status' => 'Pending',
                'nota' => null // Opt-out: remove current note file reference if cancelling
            ]);
            return back()->with('success', 'Verifikasi pengeluaran umum berhasil dibatalkan.');
        }

        // Case 2: Sarpras Procurement Cancellation (Already existing logic)
        if ($request->filled('request_code')) {
            $procurements = \App\Models\ProcurementRequest::where('request_code', $request->request_code)->get();

            if ($procurements->isEmpty()) {
                return back()->with('error', 'Data pengajuan tidak ditemukan.');
            }

            foreach ($procurements as $proc) {
                $proc->update([
                    'report_status' => 'Reported',
                    'finance_approved_at' => null,
                ]);
            }
            return back()->with('success', 'Verifikasi pengadaan berhasil dibatalkan. Status dikembalikan ke Menunggu Verifikasi.');
        }

        return back()->with('error', 'Data tidak valid.');
    }
}
