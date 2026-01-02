@extends('layouts.app')

@section('title', 'Detail Verifikasi Pembaayaran')

@section('content')
<div class="container-fluid">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('finance.verifications.index') }}" class="btn btn-light border rounded-pill shadow-sm">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 fw-bold">Bukti Transfer</div>
                <div class="card-body p-0 text-center bg-light cursor-pointer" data-bs-toggle="modal" data-bs-target="#proofModal">
                    <img src="{{ asset('uploads/payment_proofs/' . $paymentRequest->proof_image) }}" class="img-fluid" style="max-height: 500px; cursor: zoom-in;" alt="Bukti Transfer">
                </div>
                <div class="card-footer bg-white border-top p-3">
                    <button type="button" class="btn btn-outline-primary w-100 rounded-pill" data-bs-toggle="modal" data-bs-target="#proofModal">
                        <i class="bi bi-zoom-in me-2"></i> Lihat Ukuran Penuh
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Rincian Pembayaran</h5>
                    @php
                        $labels = [
                            'waiting_proof' => 'PENDING',
                            'pending' => 'PROSES (SIAP VERIFIKASI)',
                            'verified' => 'DITERIMA & DIVERIFIKASI',
                            'rejected' => 'DITOLAK'
                        ];
                    @endphp
                    <span class="badge bg-white text-primary rounded-pill px-3">
                        {{ $labels[$paymentRequest->status] ?? strtoupper($paymentRequest->status) }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-6">
                            <small class="text-uppercase text-muted fw-bold ls-1">Siswa</small>
                            <div class="fw-bold fs-5">{{ $paymentRequest->student->nama_lengkap }}</div>
                            <div class="text-muted">{{ $paymentRequest->student->nis }} - {{ $paymentRequest->student->unit->name }}</div>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-uppercase text-muted fw-bold ls-1">Bank Tujuan</small>
                            <div class="fw-bold">{{ $paymentRequest->bankAccount->bank_name }}</div>
                            <div class="text-muted">{{ $paymentRequest->bankAccount->account_number }}</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold border-bottom pb-2 mb-3">Item Pembayaran</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                @php $totalCheck = 0; @endphp
                                @foreach($paymentRequest->items as $item)
                                    @php $totalCheck += $item->amount; @endphp
                                    <tr>
                                        <td>
                                            {{ $item->studentBill->paymentType->name }} <br>
                                            <small class="text-muted">
                                                @php
                                                    $bill = $item->studentBill;
                                                    $yearDisplay = $bill->year;
                                                    if (!$yearDisplay && $bill->academicYear) {
                                                        $yearDisplay = ($bill->month >= 7) ? $bill->academicYear->start_year : ($bill->academicYear->start_year + 1);
                                                    }
                                                @endphp
                                                {{ $bill->month ? \Carbon\Carbon::create()->month((int)$bill->month)->translatedFormat('F') : '' }} 
                                                {{ $yearDisplay }} ({{ $bill->academicYear->name }})
                                            </small>
                                        </td>
                                        <td class="text-end fw-bold">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="border-top">
                                    <td class="pt-3 fw-bold fs-5">TOTAL</td>
                                    <td class="pt-3 fw-bold fs-5 text-end text-primary">Rp {{ number_format($totalCheck, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($paymentRequest->notes)
                        <div class="alert alert-info border-0 bg-info bg-opacity-10 text-dark mb-4">
                            <i class="bi bi-chat-left-text-fill me-2"></i> <strong>Catatan Siswa:</strong> <br>
                            {{ $paymentRequest->notes }}
                        </div>
                    @endif

                    @if($paymentRequest->status == 'pending')
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5 pt-3 border-top">
                        <button type="button" class="btn btn-outline-danger btn-lg rounded-pill px-5 fw-bold" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle me-2"></i> TOLAK
                        </button>
                        <button type="button" class="btn btn-success btn-lg rounded-pill px-5 fw-bold shadow" data-bs-toggle="modal" data-bs-target="#verifyModal">
                            <i class="bi bi-check-circle-fill me-2"></i> VERIFIKASI & TERIMA
                        </button>
                    </div>
                    @endif

                    @if($paymentRequest->status == 'rejected')
                        <div class="alert alert-danger mt-3">
                            <strong>Alasan Penolakan:</strong> {{ $paymentRequest->rejection_reason }}
                            <div class="small mt-1 text-muted">Ditolak oleh {{ $paymentRequest->verifiedByUser->name ?? '-' }} pada {{ $paymentRequest->verified_at->format('d M Y H:i') }}</div>
                        </div>
                    @elseif($paymentRequest->status == 'verified')
                        <div class="alert alert-success mt-3 border-0 shadow-sm rounded-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-patch-check-fill fs-3 me-3"></i>
                                <div>
                                    <div class="fw-bold fs-5">Pembayaran Sudah Diterima & Diverifikasi</div>
                                    <div class="small text-muted">Diverifikasi oleh {{ $paymentRequest->verifiedByUser->name ?? '-' }} pada {{ $paymentRequest->verified_at->format('d M Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Verify PIN Modal --}}
<div class="modal fade" id="verifyModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-5 border-0 shadow-lg overflow-hidden">
            <div class="modal-header bg-success text-white border-0 py-4 px-4 position-relative">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3 shadow-sm">
                        <i class="bi bi-shield-check text-white fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Konfirmasi Verifikasi</h5>
                        <p class="x-small mb-0 text-white-50">Otentikasi Keamanan PIN</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('finance.verifications.verify', $paymentRequest->id) }}" method="POST" class="pin-form">
                @csrf
                <div class="modal-body p-4 p-md-5 text-center">
                    <div class="alert alert-success border-0 rounded-4 x-small mb-4 shadow-sm py-3 px-4">
                        <p class="mb-0 opacity-75 fw-bold">Anda akan menyetujui pembayaran sebesar:</p>
                        <h4 class="fw-bold mb-0 mt-1">Rp {{ number_format($paymentRequest->total_amount, 0, ',', '.') }}</h4>
                    </div>

                    <h6 class="fw-bold text-dark mb-4 ls-1">MASUKKAN PIN KEAMANAN</h6>
                    
                    <div class="d-flex justify-content-center gap-2 mb-4 pin-container">
                        <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 50px; height: 60px;">
                        <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 50px; height: 60px;">
                        <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 50px; height: 60px;">
                        <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 50px; height: 60px;">
                        <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 50px; height: 60px;">
                        <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 50px; height: 60px;">
                    </div>
                    <input type="hidden" name="security_pin">
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold shadow-sm py-3">
                        <i class="bi bi-patch-check-fill me-2"></i> KONFIRMASI & TERIMA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Reject Modal with PIN --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-5 border-0 shadow-lg overflow-hidden">
            <div class="modal-header bg-danger text-white border-0 py-4 px-4 position-relative">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3 shadow-sm">
                        <i class="bi bi-shield-exclamation text-white fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Penolakan Pembayaran</h5>
                        <p class="x-small mb-0 text-white-50">Otentikasi Keamanan PIN</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('finance.verifications.reject', $paymentRequest->id) }}" method="POST" class="pin-form">
                @csrf
                <div class="modal-body p-4 p-md-5">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small text-uppercase ls-1">Alasan Penolakan</label>
                        <textarea name="rejection_reason" class="form-control bg-light border-0 shadow-none rounded-3" rows="3" required placeholder="Contoh: Bukti transfer buram, nominal tidak sesuai..."></textarea>
                    </div>

                    <div class="text-center">
                        <h6 class="fw-bold text-dark mb-3 ls-1">MASUKKAN PIN KEAMANAN</h6>
                        <div class="d-flex justify-content-center gap-2 mb-4 pin-container">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 55px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 55px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 55px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 55px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 55px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 55px;">
                        </div>
                        <input type="hidden" name="security_pin">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill fw-bold shadow-sm py-3">
                        <i class="bi bi-x-circle-fill me-2"></i> TOLAK PEMBAYARAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .pin-input {
        border: 2px solid #e9ecef;
        background-color: #f8f9fa;
        transition: all 0.2s ease;
    }
    .pin-input:focus {
        border-color: #0d6efd;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(13, 110, 253, 0.2);
        transform: translateY(-2px);
    }
    .modal.fade .modal-dialog {
        transition: transform .3s ease-out;
    }
    .ls-1 { letter-spacing: 1px; }
    .x-small { font-size: 0.75rem; }
    .rounded-5 { border-radius: 1.5rem !important; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pinContainers = document.querySelectorAll('.pin-container');
    
    pinContainers.forEach(container => {
        const inputs = container.querySelectorAll('.pin-input');
        const form = container.closest('form');
        const hiddenInput = form.querySelector('input[name="security_pin"]');

        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length > 1) {
                    e.target.value = e.target.value.slice(0, 1);
                }
                if (e.target.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateHiddenInput();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        function updateHiddenInput() {
            let pin = "";
            inputs.forEach(input => pin += input.value);
            hiddenInput.value = pin;
        }

        form.addEventListener('submit', function(e) {
            updateHiddenInput();
            if (hiddenInput.value.length < 6) {
                e.preventDefault();
                alert('Silakan masukkan 6 digit PIN Keamanan.');
            }
        });
    });

    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            const firstInput = modal.querySelector('.pin-input');
            if (firstInput) firstInput.focus();
        });
    });
});
</script>
@endpush
@endsection

{{-- Proof View Modal --}}
<div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-4 border-0 shadow-lg bg-dark">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2 text-center">
                <img src="{{ asset('uploads/payment_proofs/' . $paymentRequest->proof_image) }}" class="img-fluid rounded-3 shadow" alt="Bukti Transfer Full">
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center pb-3">
                <a href="{{ asset('uploads/payment_proofs/' . $paymentRequest->proof_image) }}" download class="btn btn-light rounded-pill px-4 btn-sm">
                    <i class="bi bi-download me-2"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>
