<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentBill;
use App\Models\BankAccount;
use App\Models\PaymentRequest;
use App\Models\PaymentRequestItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaPaymentRequestController extends Controller
{
    public function index()
    {
        $userSiswa = auth()->guard('student')->user();
        if (!$userSiswa || !$userSiswa->student) {
            abort(403);
        }
        
        $requests = PaymentRequest::where('student_id', $userSiswa->student->id)
            ->with(['bankAccount'])
            ->latest()
            ->paginate(10);
            
        return view('siswa.payments.requests.index', compact('requests'));
    }

    public function create()
    {
        $userSiswa = auth()->guard('student')->user();
        if (!$userSiswa || !$userSiswa->student) {
            abort(403);
        }

        // Fetch unpaid bills
        $bills = StudentBill::where('student_id', $userSiswa->student->id)
            ->whereIn('status', ['unpaid', 'partial'])
            ->where('is_free', false)
            ->with(['paymentType', 'academicYear'])
            ->orderBy('academic_year_id', 'asc')
            ->orderByRaw('CAST(month AS UNSIGNED) >= 7 DESC, CAST(month AS UNSIGNED) ASC')
            ->get();
            
        $bankAccounts = BankAccount::where('is_active', true)->get();
        
        return view('siswa.payments.requests.create', compact('bills', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bill_ids' => 'required|array|min:1',
            'bill_ids.*' => 'exists:student_bills,id',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'notes' => 'nullable|string|max:255',
        ]);

        $userSiswa = auth()->guard('student')->user();
        $studentId = $userSiswa->student->id;

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $itemsData = [];

            // Calculate total and prepare items
            foreach($request->bill_ids as $billId) {
                $bill = StudentBill::where('id', $billId)->where('student_id', $studentId)->firstOrFail();
                $remaining = $bill->amount - $bill->paid_amount;
                
                if($remaining <= 0) continue; // Skip if already paid (though validation checked existence, state might change)

                $totalAmount += $remaining; // Assuming full payment of remaining balance
                $itemsData[] = [
                    'student_bill_id' => $billId,
                    'amount' => $remaining
                ];
            }

            if(count($itemsData) == 0) {
                return redirect()->back()->with('error', 'Tagihan yang dipilih sudah lunas atau tidak valid.');
            }

            // Generate Reference Code
            $refCode = 'REQ-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5));

            // Create Request
            $paymentRequest = PaymentRequest::create([
                'student_id' => $studentId,
                'total_amount' => $totalAmount,
                'reference_code' => $refCode,
                'bank_account_id' => $request->bank_account_id,
                'notes' => $request->notes,
                'status' => 'waiting_proof',
            ]);

            // Create Items
            foreach($itemsData as $item) {
                PaymentRequestItem::create([
                    'payment_request_id' => $paymentRequest->id,
                    'student_bill_id' => $item['student_bill_id'],
                    'amount' => $item['amount'],
                ]);
            }

            DB::commit();

            return redirect()->route('siswa.payments.requests.show', $paymentRequest->id)
                ->with('success', 'Pengajuan pembayaran berhasil dibuat. Silakan upload bukti transfer Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        $userSiswa = auth()->guard('student')->user();
        if (!$userSiswa || !$userSiswa->student) {
            abort(403);
        }

        $paymentRequest = PaymentRequest::where('id', $id)
            ->where('student_id', $userSiswa->student->id)
            ->with(['bankAccount', 'items.studentBill.paymentType', 'items.studentBill.academicYear'])
            ->firstOrFail();

        return view('siswa.payments.requests.show', compact('paymentRequest'));
    }

    public function print($id)
    {
        $userSiswa = auth()->guard('student')->user();
        if (!$userSiswa || !$userSiswa->student) {
            abort(403);
        }

        $paymentRequest = PaymentRequest::where('id', $id)
            ->where('student_id', $userSiswa->student->id)
            ->with(['bankAccount', 'items.studentBill.paymentType', 'items.studentBill.academicYear', 'student.unit', 'student.classes'])
            ->firstOrFail();

        $pdf = Pdf::loadView('siswa.payments.requests.print', compact('paymentRequest'))
            ->setPaper([0, 0, 684, 396]); // 9.5in x 5.5in exactly
            
        return $pdf->download('Tagihan_' . $paymentRequest->reference_code . '.pdf');
    }

    public function updateProof(Request $request, $id)
    {
        $request->validate([
            'proof_image' => 'required|image|mimes:jpg|max:1024',
        ], [
            'proof_image.mimes' => 'Format file bukti harus JPG (Bukan JPEG/PNG/PDF).',
            'proof_image.max' => 'Ukuran file bukti maksimal adalah 1MB (1024 KB).',
            'proof_image.image' => 'File yang diunggah harus berupa gambar.',
        ]);

        $userSiswa = auth()->guard('student')->user();
        $paymentRequest = PaymentRequest::where('id', $id)
            ->where('student_id', $userSiswa->student->id)
            ->firstOrFail();

        if ($paymentRequest->status != 'waiting_proof' && $paymentRequest->status != 'pending') {
            return redirect()->back()->with('error', 'Status pembayaran tidak mengizinkan upload bukti baru.');
        }

        // Upload Proof
        $imageName = time() . '_' . $userSiswa->student->id . '.' . $request->proof_image->extension();
        $request->proof_image->move(public_path('uploads/payment_proofs'), $imageName);

        // Delete old proof if exists
        if ($paymentRequest->proof_image && file_exists(public_path('uploads/payment_proofs/' . $paymentRequest->proof_image))) {
            @unlink(public_path('uploads/payment_proofs/' . $paymentRequest->proof_image));
        }

        $paymentRequest->update([
            'proof_image' => $imageName,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Bukti transfer berhasil diunggah. Mohon tunggu verifikasi admin.');
    }
}
