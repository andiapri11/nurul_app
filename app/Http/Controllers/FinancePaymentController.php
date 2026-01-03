<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\PaymentType;
use App\Models\SchoolClass;
use App\Models\StudentPaymentSetting;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FinancePaymentController extends Controller
{
    public function index(Request $request)
    {
        $units = Unit::all();
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $classes = SchoolClass::orderBy('name')->get();
        
        $selectedUnitId = $request->get('unit_id');
        $selectedYearId = $request->get('academic_year_id') ?? AcademicYear::active()->first()?->id;
        $selectedClassId = $request->get('class_id');
        $search = $request->get('search');
        
        $students = \App\Models\Student::with(['unit', 'user', 'classes' => function($q) {
            $q->orderBy('class_student.academic_year_id', 'desc');
        }])->where('status', '!=', 'keluar');

        if ($selectedUnitId) {
            $students->where('unit_id', $selectedUnitId);
        }

        if ($selectedClassId) {
            $students->whereHas('classes', function($q) use ($selectedClassId, $selectedYearId) {
                $q->where('classes.id', $selectedClassId);
                if ($selectedYearId) {
                    $q->where('class_student.academic_year_id', $selectedYearId);
                }
            });
        }

        if ($search) {
            $students->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('nis', 'like', "%$search%");
            });
        }

        $students = $students->paginate(24)->appends($request->all());
        
        return view('finance.payments.index', compact(
            'units', 'academicYears', 'classes', 'students', 
            'selectedUnitId', 'selectedYearId', 'selectedClassId', 'search'
        ));
    }

    public function manageTypes(Request $request)
    {
        if (!in_array(auth()->user()->role, ['administrator', 'kepala_keuangan', 'direktur'])) {
            abort(403);
        }
        $units = Unit::all();
        $selectedUnitId = $request->get('unit_id');
        
        $query = PaymentType::query();
        
        if ($selectedUnitId) {
            $query->where(function($q) use ($selectedUnitId) {
                $q->where('unit_id', $selectedUnitId)
                  ->orWhereNull('unit_id');
            });
        }
        
        $paymentTypes = $query->with('unit')->get();
        
        return view('finance.payment_types.index', compact('units', 'paymentTypes', 'selectedUnitId'));
    }

    public function editType(PaymentType $type)
    {
        if (!in_array(auth()->user()->role, ['administrator', 'kepala_keuangan', 'direktur'])) {
            abort(403);
        }
        $units = Unit::all();
        return view('finance.payment_types.edit', compact('units', 'type'));
    }

    public function updateType(Request $request, PaymentType $type)
    {
        if (!in_array(auth()->user()->role, ['administrator', 'kepala_keuangan', 'direktur'])) {
            abort(403);
        }
        $request->validate([
            'name' => 'required|string',
            'code' => 'nullable|string|max:50',
            'unit_id' => 'nullable|exists:units,id',
            'type' => 'required|in:monthly,one_time',
        ]);
        
        $type->update($request->all());
        return redirect()->route('finance.payment-types.index')->with('success', 'Jenis Pembayaran diperbarui');
    }

    public function destroyType(PaymentType $type)
    {
        if (!in_array(auth()->user()->role, ['administrator', 'kepala_keuangan', 'direktur'])) {
            abort(403);
        }
        $type->delete();
        return redirect()->route('finance.payment-types.index')->with('success', 'Jenis Pembayaran dihapus');
    }

    public function show(Request $request, $id)
    {
        $student = \App\Models\Student::with(['unit', 'user', 'classes.academicYear'])->findOrFail($id);
        
        $selectedYearId = $request->get('academic_year_id');
        if ($selectedYearId) {
            $academicYear = \App\Models\AcademicYear::find($selectedYearId);
        } else {
            $academicYear = \App\Models\AcademicYear::where('status', 'active')->first();
        }

        if (!$academicYear) return redirect()->back()->with('error', 'Tahun Akademik tidak ditemukan');

        // Fetch specific payment bills for this student for the selected year
        $paymentBills = \App\Models\StudentBill::with('paymentType')
                        ->where('student_id', $student->id)
                        ->where('academic_year_id', $academicYear->id)
                        ->get();

        // Fetch unpaid bills from ALL OTHER years (arrears/tunggakan)
        $arrears = \App\Models\StudentBill::with(['paymentType', 'academicYear'])
                        ->where('student_id', $student->id)
                        ->where('academic_year_id', '!=', $academicYear->id)
                        ->where('status', '!=', 'paid')
                        ->orderBy('year', 'asc')
                        ->orderBy('month', 'asc')
                        ->get();
        
        // Fetch transaction history
        $transactions = \App\Models\Transaction::with(['items.paymentType', 'user'])
                        ->where('student_id', $student->id)
                        ->orderBy('transaction_date', 'desc')
                        ->get();

        $bankAccounts = \App\Models\BankAccount::where('is_active', true)->get();

        // Financial Stats for Header
        $stats = [
            'total_arrears' => $arrears->sum(fn($b) => $b->amount - $b->paid_amount),
            'total_current_bills' => $paymentBills->sum(fn($b) => $b->amount - $b->paid_amount),
            'last_transaction' => $transactions->first()?->transaction_date,
        ];

        return view('finance.payments.show', compact('student', 'academicYear', 'paymentBills', 'arrears', 'transactions', 'bankAccounts', 'stats'));
    }

    public function transactionsHistory(Request $request)
    {
        $query = \App\Models\Transaction::with(['student.unit', 'items.paymentType', 'user', 'bankAccount'])
                    ->orderBy('transaction_date', 'desc')
                    ->orderBy('created_at', 'desc');

        if ($request->date) {
            $query->whereDate('transaction_date', $request->date);
        }

        if ($request->search) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%'.$request->search.'%')
                  ->orWhere('nis', 'like', '%'.$request->search.'%');
            })->orWhere('invoice_number', 'like', '%'.$request->search.'%');
        }

        $transactions = $query->paginate(50);

        return view('finance.transactions.index', compact('transactions'));
    }

    public function store(Request $request, \App\Models\Student $student)
    {
        $request->validate([
            'bill_ids' => 'required|array',
            'bill_ids.*' => 'exists:student_bills,id',
            'pay_amounts' => 'required|array',
            'security_pin' => 'required|digits:6',
        ]);

        // Validate PIN
        if (!\Illuminate\Support\Facades\Hash::check($request->security_pin, auth()->user()->security_pin)) {
            return redirect()->back()->withInput()->with('error', 'Gagal: PIN Keamanan Salah.');
        }

        if ($request->payment_method == 'transfer') {
             $request->validate(['bank_account_id' => 'required|exists:bank_accounts,id']);
        }

        $invoiceNumber = 'INV-' . date('YmdHis') . '-' . rand(100, 999);
        $totalPaid = 0;
        
        DB::beginTransaction();

        try {
            // 1. Create Transaction Header
            $transaction = \App\Models\Transaction::create([
                'unit_id' => $student->unit_id,
                'student_id' => $student->id,
                'user_id' => auth()->id() ?? 1,
                'amount' => 0, // Will update later
                'transaction_date' => now(),
                'payment_method' => $request->payment_method ?? 'cash',
                'bank_account_id' => $request->payment_method == 'transfer' ? $request->bank_account_id : null,
                'invoice_number' => $invoiceNumber,
                'notes' => $request->notes,
            ]);

            $transactionItems = [];
            foreach ($request->bill_ids as $billId) {
                $payAmount = (float) ($request->input("pay_amounts.$billId") ?? 0);
                if ($payAmount <= 0) continue;

                $bill = \App\Models\StudentBill::find($billId);
                if (!$bill) continue;

                // Update Bill
                $bill->paid_amount += $payAmount;
                if ($bill->paid_amount >= $bill->amount) {
                    $bill->paid_amount = $bill->amount; 
                    $bill->status = 'paid';
                } else {
                    $bill->status = 'partial';
                }
                $bill->save();

                // Create Transaction Item
                \App\Models\TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'payment_type_id' => $bill->payment_type_id,
                    'amount' => $payAmount,
                    'month_paid' => $bill->month,
                    'year_paid' => $bill->year ?: (optional($bill->academicYear)->start_year ? (($bill->month >= 7) ? $bill->academicYear->start_year : ($bill->academicYear->start_year + 1)) : null),
                ]);

                $totalPaid += $payAmount;
                $transactionItems[] = ($bill->paymentType->name ?? 'Tagihan') . ($bill->month ? " (Bulan $bill->month)" : "");
            }

            if ($totalPaid > 0) {
                // Update Total Amount in Header
                $transaction->update(['amount' => $totalPaid]);

                // Update Bank Balance
                if ($request->payment_method == 'transfer' && $request->bank_account_id) {
                    $bank = \App\Models\BankAccount::find($request->bank_account_id);
                    if ($bank) { $bank->balance += $totalPaid; $bank->save(); }
                }

                // Create Receipt (Income)
                \App\Models\Receipt::create([
                    'unit_id' => $student->unit_id,
                    'category' => 'Pembayaran Siswa',
                    'amount' => $totalPaid,
                    'transaction_date' => now(),
                    'payment_method' => $request->payment_method,
                    'description' => "Pembayaran: " . implode(', ', array_unique($transactionItems)) . " oleh " . $student->nama_lengkap,
                    'reference_number' => $invoiceNumber,
                    'user_id' => auth()->id() ?? 1,
                ]);

                DB::commit();
                session()->flash('print_receipt_id', $transaction->id);
                return redirect()->back()->with('success', 'Pembayaran Total Rp ' . number_format($totalPaid, 0, ',', '.') . ' berhasil dicatat.');
            }

            DB::rollBack();
            return redirect()->back()->with('error', 'Tidak ada pembayaran yang diproses.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroyTransaction(Request $request, \App\Models\Transaction $transaction)
    {
        $request->validate([
            'void_reason' => 'required|string|max:255',
            'security_pin' => 'required|digits:6'
        ]);

        // Validate PIN
        if (!\Illuminate\Support\Facades\Hash::check($request->security_pin, auth()->user()->security_pin)) {
            return redirect()->back()->with('error', 'Gagal: PIN Keamanan Salah.');
        }
        
        DB::beginTransaction();
        try {
            // Revert each item's impact on StudentBill
            foreach ($transaction->items as $item) {
                // Find matching bill to revert status/paid_amount
                $bill = \App\Models\StudentBill::where([
                    'student_id' => $transaction->student_id,
                    'payment_type_id' => $item->payment_type_id,
                    'month' => $item->month_paid,
                ])->whereHas('academicYear', function($q) use ($item) {
                    $q->where('start_year', $item->year_paid);
                })->first();

                if ($bill) {
                    $bill->paid_amount -= $item->amount;
                    if ($bill->paid_amount <= 0) {
                        $bill->paid_amount = 0;
                        $bill->status = 'unpaid';
                    } elseif ($bill->paid_amount < $bill->amount) {
                        $bill->status = 'partial';
                    } else {
                        $bill->status = 'paid';
                    }
                    $bill->save();
                }
            }

            // Reduce Bank Balance if it was a transfer
            if ($transaction->payment_method == 'transfer' && $transaction->bank_account_id) {
                $bank = \App\Models\BankAccount::find($transaction->bank_account_id);
                if ($bank) {
                    $bank->balance -= $transaction->amount;
                    $bank->save();
                }
            }

            // Mark matching Receipt as cancelled instead of deleting? 
            // For now, simple delete if it exists, or we could add a status there too.
            \App\Models\Receipt::where('reference_number', $transaction->invoice_number)->delete();

            // Perform VOID instead of DELETE
            $transaction->update([
                'is_void' => 1,
                'void_reason' => $request->void_reason,
                'amount' => 0, // Reset amount so it doesn't show in total income reports
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Transaksi berhasil di-VOID (dibatalkan). Data tetap tersimpan dalam riwayat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }

    public function unvoidTransaction(\App\Models\Transaction $transaction)
    {
        DB::beginTransaction();
        try {
            // Recalculate total amount from items
            $originalAmount = $transaction->items->sum('amount');

            // Re-apply each item's impact on StudentBill
            foreach ($transaction->items as $item) {
                // Find matching bill to re-apply paid_amount
                $bill = \App\Models\StudentBill::where([
                    'student_id' => $transaction->student_id,
                    'payment_type_id' => $item->payment_type_id,
                    'month' => $item->month_paid,
                ])->whereHas('academicYear', function($q) use ($item) {
                    $q->where('start_year', $item->year_paid);
                })->first();

                if ($bill) {
                    $bill->paid_amount += $item->amount;
                    if ($bill->paid_amount >= $bill->amount) {
                        $bill->status = 'paid';
                    } else {
                        $bill->status = 'partial';
                    }
                    $bill->save();
                }
            }

            // Increase Bank Balance if it was a transfer
            if ($transaction->payment_method == 'transfer' && $transaction->bank_account_id) {
                $bank = \App\Models\BankAccount::find($transaction->bank_account_id);
                if ($bank) {
                    $bank->balance += $originalAmount;
                    $bank->save();
                }
            }

            // Perform UNVOID
            $transaction->update([
                'is_void' => 0,
                'void_reason' => null,
                'amount' => $originalAmount,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Status VOID berhasil dicabut. Transaksi kini kembali aktif.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan VOID: ' . $e->getMessage());
        }
    }

    public function printReceipt(\App\Models\Transaction $transaction)
    {
        // Simple authorization for students: only allow if it's their own transaction
        if (auth()->guard('student')->check()) {
            $userSiswa = auth()->guard('student')->user();
            if (!$userSiswa->student || $transaction->student_id !== $userSiswa->student->id) {
                abort(403, 'Unauthorized access to this receipt.');
            }
        }

        $transaction->load(['student.unit', 'student.classes', 'items.paymentType', 'user']);
        return view('finance.payments.receipt', compact('transaction'));
    }

    public function destroyBill(Request $request, \App\Models\StudentBill $bill)
    {
        $request->validate([
            'security_pin' => 'required',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->security_pin, auth()->user()->security_pin)) {
            return redirect()->back()->with('error', 'Penghapusan Gagal: PIN Keamanan Salah.');
        }

        $bill->delete();
        return redirect()->back()->with('success', 'Tagihan berhasil dihapus.');
    }

    public function bulkDestroyBills(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'payment_type_id' => 'nullable|exists:payment_types,id',
            'security_pin' => 'required',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->security_pin, auth()->user()->security_pin)) {
            return redirect()->back()->with('error', 'Penghapusan Gagal: PIN Keamanan Salah.');
        }

        $query = \App\Models\StudentBill::whereIn('student_id', $request->student_ids)
                    ->where('academic_year_id', $request->academic_year_id);

        if ($request->payment_type_id) {
            $query->where('payment_type_id', $request->payment_type_id);
        }

        $count = $query->count();
        $query->delete();

        return redirect()->back()->with('success', "$count tagihan untuk " . count($request->student_ids) . " siswa berhasil dihapus.");
    }

    public function billStatus(Request $request)
    {
        $units = \App\Models\Unit::all();
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        
        $selectedUnitId = $request->get('unit_id');
        $selectedClassId = $request->get('class_id');
        $selectedYearId = $request->get('academic_year_id', \App\Models\AcademicYear::where('status', 'active')->value('id'));
        $selectedTypeId = $request->get('payment_type_id');
        $status = $request->get('status');

        $paymentTypes = \App\Models\PaymentType::query();
        if ($selectedUnitId) {
            $paymentTypes->where(function($q) use ($selectedUnitId) {
                $q->where('unit_id', $selectedUnitId)->orWhereNull('unit_id');
            });
        }
        $paymentTypes = $paymentTypes->get();

        $classes = $selectedUnitId ? \App\Models\SchoolClass::where('unit_id', $selectedUnitId)->where('academic_year_id', $selectedYearId)->get() : collect();
        
        // Matrix Mode Logic (Pivoted by Month)
        $isMatrix = false;
        $students = collect();
        $matrixBills = collect();
        $matrixTransactions = collect();
        
        if ($selectedTypeId) {
            $type = \App\Models\PaymentType::find($selectedTypeId);
            if ($type && $type->type == 'monthly') {
                $isMatrix = true;
            }
        }

        if ($isMatrix && $selectedClassId) {
            $students = \App\Models\Student::with(['classes' => function($q) use ($selectedYearId) {
                                    $q->where('class_student.academic_year_id', $selectedYearId);
                                }])
                        ->whereHas('classes', function($q) use ($selectedClassId, $selectedYearId) {
                            $q->where('classes.id', $selectedClassId)
                              ->where('class_student.academic_year_id', $selectedYearId);
                        })
                        ->orderBy('nama_lengkap')
                        ->get();
            
            // Get all bills for these students for this specific type and year
            $matrixBills = \App\Models\StudentBill::whereIn('student_id', $students->pluck('id'))
                        ->where('academic_year_id', $selectedYearId)
                        ->where('payment_type_id', $selectedTypeId)
                        ->get()
                        ->groupBy('student_id');

            // Get transactions to allow direct VOID from matrix
            $matrixTransactions = \App\Models\Transaction::whereIn('student_id', $students->pluck('id'))
                        ->where('payment_type_id', $selectedTypeId)
                        ->latest()
                        ->get()
                        ->groupBy(function($item) {
                            return $item->student_id . '-' . $item->month_paid;
                        });

            $annualBills = \App\Models\StudentBill::with('paymentType')
                        ->whereIn('student_id', $students->pluck('id'))
                        ->where('academic_year_id', $selectedYearId)
                        ->whereHas('paymentType', function($q) {
                            $q->where('type', 'one_time');
                        })
                        ->get()
                        ->groupBy('student_id');
        }

        // Fallback or List Query
        $bills = collect();
        $annualBills = $annualBills ?? collect();
        if (!$isMatrix) {
            // Grouped By Student View
            $query = \App\Models\Student::with(['classes' => function($q) use ($selectedYearId) {
                        // Filter classes to only show history for this academic year
                        $q->where('class_student.academic_year_id', $selectedYearId);
                     }, 'unit'])
                     ->whereHas('classes', function($q) use ($selectedYearId) {
                         // Must have a class in this academic year
                         $q->where('class_student.academic_year_id', $selectedYearId);
                     });

            // Filter by Unit
            if ($selectedUnitId) {
                $query->where('unit_id', $selectedUnitId);
            }

            // Filter by Class
            if ($selectedClassId) {
                $query->whereHas('classes', function($q) use ($selectedClassId, $selectedYearId) {
                    $q->where('classes.id', $selectedClassId)
                      ->where('class_student.academic_year_id', $selectedYearId);
                });
            }
            
            // Sub-query filters for sums
            $billFilter = function($q) use ($selectedYearId, $selectedTypeId, $status) {
                $q->where('academic_year_id', $selectedYearId);
                if ($selectedTypeId) $q->where('payment_type_id', $selectedTypeId);
                if ($status) $q->where('status', $status);
            };

            // Calculate Totals using withSum
            $query->withSum(['bills' => $billFilter], 'amount');
            $query->withSum(['bills' => $billFilter], 'paid_amount');

            // Filter candidates based on status presence if needed
            // If user selects "Belum Bayar", we only show students that have at least one bill matching criteria?
            // Or only students whose total is not paid?
            // To mimic previous logic which filtered bills, we should filter students who have matching bills.
            if ($status || $selectedTypeId) {
                $query->whereHas('bills', $billFilter);
            }
            
            // Search functionality (optional but good to have if we merged search here, but current form lacks search input)
            // Default sort
            $query->orderBy('nama_lengkap');

            $students = $query->paginate(50);
            
            // We use $students for both Matrix and List mode now. 
            // $bills is no longer primary for List mode, but we keep it empty to key view logic off it? 
            // Better to change view logic.
        }

        return view('finance.bills.index', compact(
            'units', 'academicYears', 'paymentTypes', 'classes', 
            'bills', 'students', 'matrixBills', 'matrixTransactions', 'isMatrix', 'annualBills',
            'selectedUnitId', 'selectedClassId', 'selectedYearId', 'selectedTypeId', 'status'
        ));
    }

    public function manageFees(Request $request)
    {
        if (!in_array(auth()->user()->role, ['administrator', 'kepala_keuangan', 'direktur'])) {
            abort(403);
        }
        // Basic Data
        $units = Unit::all();
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        
        $selectedUnitId = $request->get('unit_id');
        $selectedClassId = $request->get('class_id');
        $selectedYearId = $request->get('academic_year_id', AcademicYear::where('status', 'active')->value('id')); 
        $selectedTypeId = $request->get('payment_type_id');

        $classes = collect();
        $students = collect();
        $paymentTypes = collect(); 
        
        $currentSettings = collect();
        $selectedPaymentTypeObj = null;
        $setPaymentTypeIds = collect();
        $classPaymentConfigs = collect();
        $unitPaymentTypeCounts = collect();

        if ($selectedYearId) {
            $classesQuery = SchoolClass::where('academic_year_id', $selectedYearId) 
                         ->withCount('students')
                         ->orderBy('name');
            
            if ($selectedUnitId && $selectedUnitId !== 'all') {
                $classesQuery->where('unit_id', $selectedUnitId);
            }
            
            $classes = $classesQuery->get();

            if ($request->get('group') == 'boarding') {
                if ($selectedUnitId && $selectedUnitId !== 'all') {
                    $paymentTypes = PaymentType::where(function($q) use ($selectedUnitId) {
                        $q->where('unit_id', $selectedUnitId)->orWhereNull('unit_id');
                    })->get();
                } else {
                    $paymentTypes = PaymentType::all();
                }
            } elseif ($selectedClassId) {
                $focusedClass = SchoolClass::find($selectedClassId);
                if ($focusedClass) {
                    $paymentTypes = PaymentType::where(function($q) use ($focusedClass) {
                        $q->where('unit_id', $focusedClass->unit_id)->orWhereNull('unit_id');
                    })->get();
                }
            } elseif ($selectedUnitId && $selectedUnitId !== 'all') {
                $paymentTypes = PaymentType::where(function($q) use ($selectedUnitId) {
                    $q->where('unit_id', $selectedUnitId)->orWhereNull('unit_id');
                })->get();
            } else {
                $paymentTypes = PaymentType::all();
            }

            // Map of total payment types per unit for progress tracking
            $unitPaymentTypeCounts = PaymentType::select('unit_id', DB::raw('count(*) as count'))
                                     ->groupBy('unit_id')
                                     ->pluck('count', 'unit_id');

            // Get configured payment types for all classes in this view using Enrollment History
            $classPaymentConfigs = StudentPaymentSetting::where('student_payment_settings.academic_year_id', $selectedYearId)
                                 ->join('class_student', 'student_payment_settings.student_id', '=', 'class_student.student_id')
                                 ->where('class_student.academic_year_id', $selectedYearId)
                                 ->whereIn('class_student.class_id', $classes->pluck('id'))
                                 ->select('class_student.class_id', 'student_payment_settings.payment_type_id')
                                 ->distinct()
                                 ->get()
                                 ->groupBy('class_id');

            if ($selectedClassId) {
                $setPaymentTypeIds = $classPaymentConfigs->get($selectedClassId, collect())->pluck('payment_type_id');
            }
        }

        if ($request->get('group') == 'boarding' && $selectedYearId && $selectedTypeId) {
             $selectedPaymentTypeObj = PaymentType::find($selectedTypeId);
             $studentsQuery = \App\Models\Student::where('is_boarding', 1)
                            ->whereHas('classes', function($q) use ($selectedYearId) {
                                $q->where('class_student.academic_year_id', $selectedYearId);
                            })
                            ->orderBy('nama_lengkap');
             
             if ($selectedUnitId && $selectedUnitId !== 'all') {
                 $studentsQuery->where('unit_id', $selectedUnitId);
             }

             $students = $studentsQuery->get();
             
             if ($students->isNotEmpty()) {
                 $currentSettings = StudentPaymentSetting::whereIn('student_id', $students->pluck('id'))
                                     ->where('academic_year_id', $selectedYearId)
                                     ->where('payment_type_id', $selectedTypeId)
                                     ->get()
                                     ->groupBy('student_id');
             }
        } elseif ($selectedClassId && $selectedYearId && $selectedTypeId) {
             $selectedPaymentTypeObj = PaymentType::find($selectedTypeId);
             $class = SchoolClass::with(['students' => function($q) use ($selectedYearId) {
                 $q->where('class_student.academic_year_id', $selectedYearId);
             }])->find($selectedClassId);
             if ($class) {
                 $students = $class->students()->where('class_student.academic_year_id', $selectedYearId)->orderBy('nama_lengkap')->get();
                 $currentSettings = StudentPaymentSetting::whereIn('student_id', $students->pluck('id'))
                                     ->where('academic_year_id', $selectedYearId)
                                     ->where('payment_type_id', $selectedTypeId)
                                     ->get()
                                     ->groupBy('student_id');
             }
        } elseif ($selectedClassId) {
              $class = SchoolClass::with(['students' => function($q) use ($selectedYearId) {
                  $q->where('class_student.academic_year_id', $selectedYearId);
              }])->find($selectedClassId);
              if ($class) {
                  $students = $class->students()->where('class_student.academic_year_id', $selectedYearId)->orderBy('nama_lengkap')->get();
              }
        }

        return view('finance.student_fees.index', compact(
            'units', 'academicYears', 'paymentTypes', 
            'selectedUnitId', 'selectedClassId', 'selectedYearId', 'selectedTypeId',
            'classes', 'students', 'currentSettings', 'selectedPaymentTypeObj', 'setPaymentTypeIds', 'classPaymentConfigs', 'unitPaymentTypeCounts'
        ));
    }

    public function storeType(Request $request)
    {
        if (!in_array(auth()->user()->role, ['administrator', 'kepala_keuangan', 'direktur'])) {
            abort(403);
        }
        $request->validate([
             'name' => 'required|string',
             'code' => 'nullable|string|max:50',
             'unit_id' => 'nullable|exists:units,id',
             'type' => 'required|in:monthly,one_time',
        ]);
        
        PaymentType::create(array_merge($request->all(), ['nominal' => 0]));
        return redirect()->route('finance.payment-types.index')->with('success', 'Jenis Pembayaran ditambahkan');
    }

    public function storeClassFees(Request $request)
    {
        if (!in_array(auth()->user()->role, ['administrator', 'kepala_keuangan', 'direktur'])) {
            abort(403);
        }
        $request->validate([
            'class_id' => 'nullable|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'payment_type_id' => 'required|exists:payment_types,id',
            'fees' => 'required|array',
            'billing_month' => 'nullable|integer|between:1,12',
            'due_month' => 'nullable|integer|between:1,12',
        ]);

        $type = PaymentType::findOrFail($request->payment_type_id);
        $ay = AcademicYear::findOrFail($request->academic_year_id);
        $count = 0;
        $hasDueMonth = Schema::hasColumn('student_payment_settings', 'due_month');

        foreach ($request->fees as $studentId => $data) {
            if ($type->type == 'monthly') {
                if (is_array($data)) {
                    foreach ($data as $month => $amount) {
                        $val = is_numeric($amount) ? $amount : 0;
                        $discount = $request->discounts[$studentId][$month] ?? 0;
                        $isFree = isset($request->frees[$studentId][$month]) && $request->frees[$studentId][$month] == 1;

                        $billYear = ($month >= 7) ? $ay->start_year : ($ay->end_year ?: $ay->start_year + 1);

                        $updateData = [
                            'amount' => $val,
                            'discount_amount' => $discount,
                            'is_free' => $isFree,
                            'year' => $billYear
                        ];
                        if ($hasDueMonth) {
                            $updateData['due_month'] = $month;
                        }

                        // 1. Maintain the "Setting"
                        \App\Models\StudentPaymentSetting::updateOrCreate(
                            [
                                'student_id' => $studentId,
                                'payment_type_id' => $request->payment_type_id,
                                'academic_year_id' => $request->academic_year_id,
                                'month' => $month
                            ],
                            $updateData
                        );

                        // 2. Maintain the "Bill"
                        $billData = [
                            'amount' => $val,
                            'discount_amount' => $discount,
                            'is_free' => $isFree,
                            'year' => $billYear
                        ];
                        if ($isFree) {
                            $billData['status'] = 'paid';
                        }

                        \App\Models\StudentBill::updateOrCreate(
                            [
                                'student_id' => $studentId,
                                'payment_type_id' => $request->payment_type_id,
                                'academic_year_id' => $request->academic_year_id,
                                'month' => $month
                            ],
                            $billData
                        );
                    }
                }
            } else {
                $val = is_numeric($data) ? $data : 0;
                $discount = $request->discounts[$studentId] ?? 0;
                $isFree = isset($request->frees[$studentId]) && $request->frees[$studentId] == 1;
                
                // 1. Setting
                \App\Models\StudentPaymentSetting::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'payment_type_id' => $request->payment_type_id,
                        'academic_year_id' => $request->academic_year_id,
                    ],
                    [
                        'amount' => $val,
                        'discount_amount' => $discount,
                        'is_free' => $isFree,
                        'month' => $request->billing_month,
                        'year' => ($request->billing_month && $request->billing_month >= 7) ? $ay->start_year : ($ay->end_year ?: $ay->start_year + 1),
                        'due_month' => $hasDueMonth ? $request->due_month : null
                    ]
                );

                // 2. Bill
                $billData = [
                    'amount' => $val,
                    'discount_amount' => $discount,
                    'is_free' => $isFree,
                    'month' => $request->billing_month,
                    'year' => ($request->billing_month && $request->billing_month >= 7) ? $ay->start_year : ($ay->end_year ?: $ay->start_year + 1),
                ];
                if ($isFree) {
                    $billData['status'] = 'paid';
                }

                \App\Models\StudentBill::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'payment_type_id' => $request->payment_type_id,
                        'academic_year_id' => $request->academic_year_id,
                        'month' => $request->billing_month,
                    ],
                    $billData
                );
            }
            $count++;
        }

        $class = $request->class_id ? SchoolClass::find($request->class_id) : null;
        
        return redirect()->route('finance.student-fees.index', [
            'unit_id' => $request->unit_id ?? ($class ? $class->unit_id : ''),
            'academic_year_id' => $request->academic_year_id,
            'class_id' => $request->class_id ?? '',
            'payment_type_id' => $request->payment_type_id,
            'group' => $request->group ?? ''
        ])->with('success', "Tarif berhasil diperbarui untuk $count siswa.");
    }
    public function exportExcel(Request $request)
    {
        $query = \App\Models\Transaction::with(['student.unit', 'items.paymentType', 'user', 'bankAccount'])
                    ->orderBy('transaction_date', 'desc');

        if ($request->date) {
            $query->whereDate('transaction_date', $request->date);
        }

        if ($request->search) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%'.$request->search.'%')
                  ->orWhere('nis', 'like', '%'.$request->search.'%');
            })->orWhere('invoice_number', 'like', '%'.$request->search.'%');
        }

        $transactions = $query->get();

        $fileName = 'Laporan_Transaksi_' . date('Ymd_His') . '.csv';
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Kode Pembayaran', 'Tanggal', 'Siswa', 'Unit', 'Uraian', 'Nominal', 'Metode', 'Penerima', 'Status');

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $t) {
                $items = [];
                foreach($t->items as $item) {
                   $items[] = ($item->paymentType->name ?? '-') . ($item->month_paid ? " (".\Carbon\Carbon::create()->month((int)$item->month_paid)->translatedFormat('F').")" : "");
                }
                
                fputcsv($file, array(
                    $t->invoice_number,
                    $t->transaction_date->format('d/m/Y'),
                    $t->student->nama_lengkap,
                    $t->student->unit->name ?? '-',
                    implode('; ', $items),
                    $t->amount,
                    strtoupper($t->payment_method),
                    $t->user->name ?? 'System',
                    $t->is_void ? 'VOID' : 'AKTIF'
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $query = \App\Models\Transaction::with(['student.unit', 'items.paymentType', 'user', 'bankAccount'])
                    ->orderBy('transaction_date', 'desc');

        if ($request->date) {
            $query->whereDate('transaction_date', $request->date);
        }

        if ($request->search) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%'.$request->search.'%')
                  ->orWhere('nis', 'like', '%'.$request->search.'%');
            })->orWhere('invoice_number', 'like', '%'.$request->search.'%');
        }

        $transactions = $query->get();
        $date = $request->date ? \Carbon\Carbon::parse($request->date)->translatedFormat('d F Y') : 'Semua Waktu';

        return view('finance.transactions.pdf', compact('transactions', 'date'));
    }
    public function exportBillsExcel(Request $request)
    {
        $selectedUnitId = $request->get('unit_id');
        $selectedClassId = $request->get('class_id');
        $selectedYearId = $request->get('academic_year_id', \App\Models\AcademicYear::where('status', 'active')->value('id'));
        $selectedTypeId = $request->get('payment_type_id');
        $status = $request->get('status');

        $query = \App\Models\Student::with(['classes' => function($q) use ($selectedYearId) {
                    $q->where('class_student.academic_year_id', $selectedYearId);
                 }, 'unit'])
                 ->whereHas('classes', function($q) use ($selectedYearId) {
                     $q->where('class_student.academic_year_id', $selectedYearId);
                 });

        if ($selectedUnitId) $query->where('unit_id', $selectedUnitId);
        if ($selectedClassId) {
            $query->whereHas('classes', function($q) use ($selectedClassId, $selectedYearId) {
                $q->where('classes.id', $selectedClassId)->where('class_student.academic_year_id', $selectedYearId);
            });
        }
        
        $billFilter = function($q) use ($selectedYearId, $selectedTypeId, $status) {
            $q->where('academic_year_id', $selectedYearId);
            if ($selectedTypeId) $q->where('payment_type_id', $selectedTypeId);
            if ($status) $q->where('status', $status);
        };

        $query->withSum(['bills' => $billFilter], 'amount');
        $query->withSum(['bills' => $billFilter], 'paid_amount');

        if ($status || $selectedTypeId) {
            $query->whereHas('bills', $billFilter);
        }
        
        $students = $query->orderBy('nama_lengkap')->get();

        $fileName = 'Rekap_Tagihan_' . date('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Siswa', 'NIS', 'Kelas', 'Unit', 'Total Tagihan', 'Total Terbayar', 'Sisa Hutang', 'Status'];

        $callback = function() use($students, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($students as $s) {
                $bill = $s->bills_sum_amount ?? 0;
                $paid = $s->bills_sum_paid_amount ?? 0;
                $debt = $bill - $paid;
                
                $st = 'LUNAS';
                if ($debt > 0 && $paid > 0) $st = 'MENCICIL';
                elseif ($debt > 0 && $paid == 0) $st = 'BELUM BAYAR';
                elseif ($bill == 0) $st = '-';

                fputcsv($file, [
                    $s->nama_lengkap,
                    $s->nis,
                    $s->classes->first()->name ?? '-',
                    $s->unit->name ?? '-',
                    $bill,
                    $paid,
                    $debt,
                    $st
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportBillsPdf(Request $request)
    {
        $selectedUnitId = $request->get('unit_id');
        $selectedClassId = $request->get('class_id');
        $selectedYearId = $request->get('academic_year_id', \App\Models\AcademicYear::where('status', 'active')->value('id'));
        $selectedTypeId = $request->get('payment_type_id');
        $status = $request->get('status');

        $query = \App\Models\Student::with(['classes' => function($q) use ($selectedYearId) {
                    $q->where('class_student.academic_year_id', $selectedYearId);
                 }, 'unit'])
                 ->whereHas('classes', function($q) use ($selectedYearId) {
                     $q->where('class_student.academic_year_id', $selectedYearId);
                 });

        if ($selectedUnitId) $query->where('unit_id', $selectedUnitId);
        if ($selectedClassId) {
            $query->whereHas('classes', function($q) use ($selectedClassId, $selectedYearId) {
                $q->where('classes.id', $selectedClassId)->where('class_student.academic_year_id', $selectedYearId);
            });
        }
        
        $billFilter = function($q) use ($selectedYearId, $selectedTypeId, $status) {
            $q->where('academic_year_id', $selectedYearId);
            if ($selectedTypeId) $q->where('payment_type_id', $selectedTypeId);
            if ($status) $q->where('status', $status);
        };

        $query->withSum(['bills' => $billFilter], 'amount');
        $query->withSum(['bills' => $billFilter], 'paid_amount');

        if ($status || $selectedTypeId) {
            $query->whereHas('bills', $billFilter);
        }
        
        $students = $query->orderBy('nama_lengkap')->get();
        $ay = \App\Models\AcademicYear::find($selectedYearId);
        $tp = $ay ? $ay->name : '-';
        $unit = $selectedUnitId ? \App\Models\Unit::find($selectedUnitId)->name : 'Semua Unit';
        $class = $selectedClassId ? \App\Models\SchoolClass::find($selectedClassId)->name : 'Semua Kelas';

        return view('finance.bills.pdf', compact('students', 'tp', 'unit', 'class'));
    }

    public function forceDeleteTransaction(Request $request, \App\Models\Transaction $transaction)
    {
        if (auth()->user()->role !== 'administrator') {
            return redirect()->back()->with('error', 'Hanya administrator yang bisa menghapus permanen.');
        }

        // Verify PIN
        $user = auth()->user();
        if (!$user->security_pin) {
            return redirect()->back()->with('error', 'Anda belum mengatur PIN Keamanan. Silakan atur di Manajemen Administrator.');
        }

        if (!\Illuminate\Support\Facades\Hash::check($request->security_pin, $user->security_pin)) {
            return redirect()->back()
                ->with('error', 'PIN Keamanan tidak valid. Silakan coba lagi.')
                ->with('open_pin_modal', $transaction->id);
        }
        
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Revert Bills & Bank Balance
            if (!$transaction->is_void) {
                foreach ($transaction->items as $item) {
                    $bill = null;
                    // Prefer direct ID reference
                    if ($item->student_bill_id) {
                        $bill = \App\Models\StudentBill::find($item->student_bill_id);
                    }
                    
                    // Fallback to fuzzy search if ID missing (older records)
                    if (!$bill) {
                        $bill = \App\Models\StudentBill::where([
                            'student_id' => $transaction->student_id,
                            'payment_type_id' => $item->payment_type_id,
                            'month' => $item->month_paid,
                        ])->whereHas('academicYear', function($q) use ($item) {
                            $q->where('start_year', $item->year_paid);
                        })->first();
                    }

                    if ($bill) {
                        $bill->paid_amount -= $item->amount;
                        if ($bill->paid_amount <= 0) {
                            $bill->paid_amount = 0;
                            $bill->status = 'unpaid';
                        } elseif ($bill->paid_amount < $bill->amount) {
                            $bill->status = 'partial';
                        } else {
                            $bill->status = 'paid';
                        }
                        $bill->save();
                    }
                }

                // Revert Bank Balance
                if ($transaction->payment_method == 'transfer' && $transaction->bank_account_id) {
                    $bank = \App\Models\BankAccount::find($transaction->bank_account_id);
                    if ($bank) {
                        $bank->balance -= $transaction->items->sum('amount');
                        $bank->save();
                    }
                }
            }

            // 2. Delete linked PaymentRequest if exists
            if (str_contains($transaction->notes, 'Verifikasi Pembayaran Online #')) {
                $parts = explode('#', $transaction->notes);
                $requestId = isset($parts[1]) ? trim($parts[1]) : null;
                
                if ($requestId) {
                    $paymentRequest = \App\Models\PaymentRequest::find($requestId);
                    if ($paymentRequest) {
                        $paymentRequest->items()->delete();
                        $paymentRequest->delete();
                    }
                }
            }
            
            $invoice = $transaction->invoice_number;
            $transaction->delete(); // This will cascade to items

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->back()->with('success', 'Transaksi #'.$invoice.' & data pengajuan online terkait telah dihapus permanen.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
