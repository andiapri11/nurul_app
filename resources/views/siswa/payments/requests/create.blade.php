@extends('layouts.app')

@section('title', 'Buat Pembayaran Baru')

@section('content')
<div class="container-xl px-4 mt-5 mb-5 align-items-start">
    <div class="row">
        <div class="col-md-8">
            <h4 class="fw-bold mb-4">Pilih Tagihan yang Akan Dibayar</h4>
            
            <form action="{{ route('siswa.payments.requests.store') }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                @csrf
                
                @if($bills->isEmpty())
                    <div class="alert alert-success rounded-4 p-4 text-center">
                        <i class="bi bi-check-circle-fill fs-1 text-success d-block mb-3"></i>
                        <h5 class="fw-bold">Tidak Ada Tagihan</h5>
                        <p class="mb-0">Semua tagihan Anda sudah lunas. Terima kasih!</p>
                        <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-primary rounded-pill mt-3 px-4">Kembali ke Dashboard</a>
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-light py-3 border-bottom-0">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label fw-bold small text-uppercase ls-1 ms-2" for="checkAll">Pilih Semua Tagihan</label>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-white border-bottom">
                                        <tr>
                                            <th class="ps-4 py-3" width="40"></th>
                                            <th class="py-3 text-uppercase text-muted small fw-bold">Keterangan Tagihan</th>
                                            <th class="py-3 text-uppercase text-muted small fw-bold text-end">Sisa Tagihan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bills as $bill)
                                            @php
                                                $remaining = $bill->amount - $bill->paid_amount;
                                            @endphp
                                            <tr class="payment-row" style="cursor: pointer;">
                                                <td class="ps-4 py-3">
                                                    <div class="form-check" onclick="event.stopPropagation()">
                                                        <input class="form-check-input bill-check" type="checkbox" name="bill_ids[]" value="{{ $bill->id }}" id="check_{{ $bill->id }}" data-amount="{{ $remaining }}" onchange="calculateTotal()">
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="fw-bold text-dark">{{ $bill->paymentType->name }}</div>
                                                    <div class="small text-muted">
                                                        @php
                                                            $yearDisplay = $bill->year;
                                                            if (!$yearDisplay && $bill->academicYear) {
                                                                $yearDisplay = ($bill->month >= 7) ? $bill->academicYear->start_year : ($bill->academicYear->start_year + 1);
                                                            }
                                                        @endphp
                                                        {{ $bill->month ? \Carbon\Carbon::create()->month((int)$bill->month)->translatedFormat('F') : '' }} {{ $yearDisplay }}
                                                        ({{ $bill->academicYear->name }})
                                                    </div>
                                                    @if($bill->status == 'partial')
                                                        <span class="badge bg-warning text-dark rounded-pill mt-1" style="font-size: 0.65rem;">DICICIL</span>
                                                    @endif

                                                    @php
                                                        $currentYear = date('Y');
                                                        $currentMonth = date('n');
                                                        $isArrears = false;
                                                        if ($bill->month) {
                                                            if ($yearDisplay < $currentYear) {
                                                                $isArrears = true;
                                                            } elseif ($yearDisplay == $currentYear && $bill->month < $currentMonth) {
                                                                $isArrears = true;
                                                            }
                                                        }
                                                    @endphp

                                                    @if($isArrears)
                                                        <span class="badge bg-danger rounded-pill mt-1 ms-1" style="font-size: 0.65rem;">TUNGGAKAN</span>
                                                    @else
                                                        <span class="badge bg-success rounded-pill mt-1 ms-1" style="font-size: 0.65rem;">AKAN DATANG</span>
                                                    @endif
                                                </td>
                                                <td class="pe-4 py-3 text-end fw-bold text-primary">
                                                    Rp {{ number_format($remaining, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
        </div>

        <div class="col-md-4">
            <h4 class="fw-bold mb-4">Rincian Pembayaran</h4>
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 2rem; z-index: 100;">
                <div class="card-body p-4">
                    <div class="mb-4 text-center">
                        <small class="text-uppercase text-muted fw-bold ls-1 d-block mb-1">Total Yang Harus Dibayar</small>
                        <h2 class="fw-bold text-primary mb-0" id="totalAmount">Rp 0</h2>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Rekening Tujuan</label>
                        <select name="bank_account_id" class="form-select border-0 bg-light shadow-sm py-2 rounded-3" required>
                            <option value="">-- Pilih Rekening --</option>
                            @foreach($bankAccounts as $bank)
                                <option value="{{ $bank->id }}" data-desc="{{ $bank->account_number }} a.n {{ $bank->account_holder }}">
                                    {{ $bank->bank_name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text mt-2 small text-info d-none" id="bankInfo">
                            <i class="bi bi-info-circle me-1"></i> Transfer ke: <strong id="bankNumber"></strong>
                        </div>
                        <div class="mt-2">
                            <small class="text-danger fw-bold italic" style="font-size: 0.7rem;">
                                <i class="bi bi-exclamation-octagon-fill me-1"></i> PASTIKAN HANYA TRANSFER KE REKENING RESMI DI ATAS.
                            </small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Catatan (Opsional)</label>
                        <textarea name="notes" class="form-control border-0 bg-light shadow-sm" rows="2" placeholder="Contoh: Pembayaran SPP Juli & LKS"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm" id="btnSubmit">
                        <i class="bi bi-check-circle-fill me-2"></i> BUAT KODE PEMBAYARAN
                    </button>
                    <div class="mt-3 text-center">
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Bukti transfer akan diupload setelah kode pembayaran dibuat.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

@push('scripts')
<script>
    // Make it global so it can be called from anywhere
    window.calculateTotal = function() {
        const totalDisplay = document.getElementById('totalAmount');
        const checks = document.querySelectorAll('.bill-check');
        let total = 0;
        checks.forEach(c => {
            if(c.checked) {
                total += parseFloat(c.getAttribute('data-amount'));
            }
        });
        totalDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checks = document.querySelectorAll('.bill-check');
        const checkAll = document.getElementById('checkAll');
        const btnSubmit = document.getElementById('btnSubmit');
        const bankSelect = document.querySelector('select[name="bank_account_id"]');
        const bankInfo = document.getElementById('bankInfo');
        const bankNumber = document.getElementById('bankNumber');
        const rows = document.querySelectorAll('.payment-row');

        // Handle Row Click
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                // Ignore if clicking the checkbox directly (to avoid double toggle)
                if (e.target.classList.contains('form-check-input')) return;
                
                const checkbox = this.querySelector('.bill-check');
                checkbox.checked = !checkbox.checked;
                window.calculateTotal();
            });
        });

        btnSubmit.addEventListener('click', function(e) {
            e.preventDefault();
            
            let checkedCount = 0;
            checks.forEach(c => { if(c.checked) checkedCount++; });

            if (checkedCount === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Silakan pilih minimal satu tagihan yang akan dibayar.',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            if (!bankSelect.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal...',
                    text: 'Silakan pilih rekening tujuan transfer terlebih dahulu.',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            // Final Confirmation
            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Anda akan membuat kode pembayaran sebesar " + document.getElementById('totalAmount').textContent + ". Lanjutkan?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Buat Kode',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('paymentForm').submit();
                }
            });
        });

        if(checkAll) {
            checkAll.addEventListener('change', function() {
                checks.forEach(c => c.checked = checkAll.checked);
                window.calculateTotal();
            });
        }

        checks.forEach(c => {
            c.addEventListener('change', window.calculateTotal);
        });
        
        bankSelect.addEventListener('change', function() {
            if(this.value) {
                const opt = this.options[this.selectedIndex];
                bankNumber.textContent = opt.getAttribute('data-desc');
                bankInfo.classList.remove('d-none');
            } else {
                bankInfo.classList.add('d-none');
            }
            window.calculateTotal();
        });

        // Security Warning Popup
        Swal.fire({
            title: '<h4 class="fw-bold mb-0">Peringatan Keamanan</h4>',
            html: `
                <div class="text-center p-2">
                    <p class="mb-3 text-muted small">Demi keamanan, transfer <strong>HANYA</strong> dilakukan melalui rekening resmi berikut:</p>
                    <div class="alert alert-warning border-0 shadow-sm rounded-4 text-start small mb-3">
                        @foreach($bankAccounts as $bank)
                            <div class="mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="fw-bold text-dark"><i class="bi bi-bank me-2"></i>{{ $bank->bank_name }}</div>
                                <div class="font-monospace text-primary fw-bold fs-6">No. Rek: {{ $bank->account_number }}</div>
                                <div class="text-muted small">a.n {{ $bank->account_holder }}</div>
                            </div>
                        @endforeach
                    </div>
                    <p class="small text-danger fw-bold mb-0 italic" style="font-size: 0.65rem;">
                        *LPT Nurul Ilmi tidak bertanggung jawab atas pembayaran ke nomor rekening di luar daftar di atas.*
                    </p>
                </div>
            `,
            icon: 'warning',
            iconColor: '#f59e0b',
            confirmButtonText: 'SAYA MENGERTI & SETUJU',
            confirmButtonColor: '#d97706',
            buttonsStyling: true,
            customClass: {
                confirmButton: 'btn btn-primary rounded-pill px-5 py-2 fw-bold',
                popup: 'rounded-5 border-0 shadow-lg'
            },
            allowOutsideClick: false,
            allowEscapeKey: false
        });

        // Init
        window.calculateTotal();
    });
</script>
@endpush
@endsection
