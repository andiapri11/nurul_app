@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container-xl px-4 mt-5 mb-5">
    <div class="mb-4">
        <a href="{{ route('siswa.payments.requests.index') }}" class="btn btn-light border rounded-pill shadow-sm">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white py-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-0 text-uppercase ls-1">Kode Pembayaran</h4>
                        <small class="opacity-75">{{ $paymentRequest->reference_code }}</small>
                    </div>
                    <div class="text-end">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-none d-md-block">
                                <div class="fw-bold">{{ $paymentRequest->created_at->format('d M Y') }}</div>
                                <small class="opacity-75">{{ $paymentRequest->created_at->format('H:i') }} WIB</small>
                            </div>
                            <a href="{{ route('siswa.payments.requests.print', $paymentRequest->id) }}" target="_blank" class="btn btn-white-glass btn-sm shadow-sm fw-bold px-3 py-2">
                                <i class="bi bi-download me-1"></i> <span class="d-none d-sm-inline">DOWNLOAD</span><span class="d-sm-none">DOWNLOAD</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-5">
                    
                    <div class="row mb-5 align-items-center">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted fw-bold ls-1 mb-2">Penerima</h6>
                            <h5 class="fw-bold text-dark">{{ $paymentRequest->bankAccount->bank_name ?? '-' }}</h5>
                            <p class="mb-0 text-muted">{{ $paymentRequest->bankAccount->account_number ?? '-' }}</p>
                            <p class="mb-0 text-muted">a.n {{ $paymentRequest->bankAccount->account_holder ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 text-md-end mt-4 mt-md-0">
                            <h6 class="text-uppercase text-muted fw-bold ls-1 mb-2">Status</h6>
                            @php
                                $badges = [
                                    'waiting_proof' => 'bg-warning text-dark',
                                    'pending' => 'bg-info text-white',
                                    'verified' => 'bg-success text-white',
                                    'rejected' => 'bg-danger text-white'
                                ];
                                $labels = [
                                    'waiting_proof' => 'PENDING',
                                    'pending' => 'PROSES',
                                    'verified' => 'DITERIMA & DIVERIFIKASI',
                                    'rejected' => 'DITOLAK'
                                ];
                                $icons = [
                                    'waiting_proof' => 'bi-hourglass',
                                    'pending' => 'bi-gear-wide-connected',
                                    'verified' => 'bi-check-all',
                                    'rejected' => 'bi-x-circle'
                                ];
                            @endphp
                            <span class="badge rounded-pill {{ $badges[$paymentRequest->status] }} px-4 py-2 fs-6">
                                <i class="bi {{ $icons[$paymentRequest->status] }} me-2"></i> {{ $labels[$paymentRequest->status] }}
                            </span>
                            @if($paymentRequest->status == 'verified')
                                <div class="mt-2 text-success small">
                                    <i class="bi bi-shield-check me-1"></i> Terverifikasi pada {{ $paymentRequest->verified_at ? $paymentRequest->verified_at->format('d/m/Y H:i') : '-' }}
                                </div>
                                @if($paymentRequest->transaction)
                                    <div class="mt-3">
                                        <a href="{{ route('finance.payments.receipt', $paymentRequest->transaction->id) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill fw-bold px-3">
                                            <i class="bi bi-printer-fill me-1"></i> CETAK KWITANSI RESMI
                                        </a>
                                    </div>
                                @endif
                            @elseif($paymentRequest->status == 'rejected')
                                <div class="mt-2 text-danger small">
                                    <i class="bi bi-info-circle me-1"></i> Alasan: {{ $paymentRequest->rejection_reason }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-borderless">
                            <thead class="border-bottom">
                                <tr class="text-uppercase text-muted small fw-bold">
                                    <th class="py-3 ps-0">Deskripsi</th>
                                    <th class="py-3 text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentRequest->items as $item)
                                    <tr>
                                        <td class="py-3 ps-0">
                                            <div class="fw-bold text-dark">{{ $item->studentBill->paymentType->name }}</div>
                                            <div class="small text-muted">
                                                @php
                                                    $bill = $item->studentBill;
                                                    $yearDisplay = $bill->year;
                                                    if (!$yearDisplay && $bill->academicYear) {
                                                        $yearDisplay = ($bill->month >= 7) ? $bill->academicYear->start_year : ($bill->academicYear->start_year + 1);
                                                    }
                                                @endphp
                                                {{ $bill->month ? \Carbon\Carbon::create()->month((int)$bill->month)->translatedFormat('F') : '' }} 
                                                {{ $yearDisplay }} 
                                                <span class="badge bg-light text-dark border ms-1">{{ $bill->academicYear->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 text-end fw-bold">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td class="pt-4 ps-0 fw-bold fs-5 text-dark">TOTAL PEMBAYARAN</td>
                                    <td class="pt-4 text-end fw-bold fs-4 text-primary">Rp {{ number_format($paymentRequest->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            @if($paymentRequest->notes)
                                <div class="alert alert-light border rounded-3 p-3 h-100">
                                    <small class="fw-bold text-muted d-block mb-1 text-uppercase ls-1">Catatan Anda</small>
                                    <p class="mb-0">{{ $paymentRequest->notes }}</p>
                                </div>
                            @endif
                            
                            <div class="alert alert-warning border-0 rounded-4 p-3 mt-3 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-shield-lock-fill fs-4 me-3"></i>
                                    <div>
                                        <small class="fw-bold d-block mb-1 text-uppercase ls-1">Himbauan Keamanan</small>
                                        <p class="small mb-0 fw-bold" style="line-height: 1.6;">Seluruh transaksi pembayaran wajib ditujukan ke nomor rekening resmi LPT Nurul Ilmi yang tertera pada instruksi ini. Pihak lembaga tidak bertanggung jawab atas kesalahan pengiriman dana atau kerugian yang timbul akibat transaksi ke rekening di luar instruksi resmi.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border bg-light rounded-4 shadow-none">
                                <div class="card-body p-4 text-center">
                                    <h6 class="fw-bold text-dark text-uppercase ls-1 mb-3">Bukti Pembayaran</h6>
                                    
                                    @if($paymentRequest->proof_image)
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#proofModal" class="d-block position-relative">
                                            <img src="{{ asset('uploads/payment_proofs/' . $paymentRequest->proof_image) }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 200px;" alt="Bukti">
                                            <div class="position-absolute bottom-0 start-50 translate-middle-x mb-2">
                                                <span class="badge bg-dark bg-opacity-75 rounded-pill px-3 py-2">Klik untuk memperbesar</span>
                                            </div>
                                        </a>
                                        @if($paymentRequest->status == 'pending')
                                            <div class="mt-3">
                                                <button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="document.getElementById('reuploadForm').classList.toggle('d-none')">
                                                    <i class="bi bi-arrow-repeat me-1"></i> Upload Ulang
                                                </button>
                                                <div id="reuploadForm" class="d-none mt-3">
                                                    <form action="{{ route('siswa.payments.requests.update-proof', $paymentRequest->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateImage(this)">
                                                        @csrf
                                                        <div class="alert alert-warning py-1 px-2 small mb-2" style="font-size: 10px;">
                                                            <i class="bi bi-exclamation-triangle-fill"></i> Format: <strong>JPG, JPEG, PNG</strong> (Maks 2MB)
                                                        </div>
                                                        <input type="file" name="proof_image" class="form-control mb-2 border-0 py-1 shadow-sm small" accept=".jpg,.jpeg,.png" title="Upload Bukti Pembayaran (Maks 2MB, Format Gambar)" required>
                                                        <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill fw-bold">UPDATE BUKTI</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    @elseif($paymentRequest->status == 'waiting_proof' || $paymentRequest->status == 'pending')
                                        <div class="py-3">
                                            <i class="bi bi-cloud-upload fs-1 text-muted d-block mb-3"></i>
                                            <p class="small text-muted mb-4 px-3">Belum ada bukti yang diunggah. Silakan upload bukti transfer Anda agar diproses Admin.</p>
                                            
                                            <form action="{{ route('siswa.payments.requests.update-proof', $paymentRequest->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateImage(this)">
                                                 @csrf
                                                 <div class="alert alert-info py-2 px-3 small mb-3 text-start">
                                                     <i class="bi bi-info-circle-fill me-1"></i> <strong>INFO:</strong> Menerima format <strong>JPG, JPEG, PNG</strong> (Maks 2MB).
                                                 </div>
                                                 <input type="file" name="proof_image" class="form-control mb-3 border-0 py-2 shadow-sm" accept=".jpg,.jpeg,.png" title="Upload Bukti Pembayaran (Maks 2MB, Format Gambar)" required>
                                                 <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                                                     <i class="bi bi-send-fill me-2"></i> UPLOAD SEKARANG
                                                 </button>
                                             </form>
                                        </div>
                                    @else
                                        <div class="py-3 text-muted">
                                            <i class="bi bi-slash-circle fs-2 d-block mb-2"></i>
                                            <p class="small mb-0">Bukti tidak ditemukan atau tidak tersedia.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!$paymentRequest->proof_image)
                    <div class="mt-5 pt-4 border-top">
                        <h6 class="fw-bold text-dark text-uppercase ls-1 mb-4 text-center">Instruksi Pembayaran</h6>
                        <div class="row g-4 text-center">
                            <div class="col-md-4">
                                <div class="step-card">
                                    <div class="step-num shadow-sm">1</div>
                                    <p class="small text-muted mb-0">Transfer tepat <strong>Rp {{ number_format($paymentRequest->total_amount, 0, ',', '.') }}</strong> ke rekening di atas.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="step-card">
                                    <div class="step-num shadow-sm">2</div>
                                    <p class="small text-muted mb-0">Simpan bukti transfer dalam format <strong>Gambar (JPG/PNG)</strong> (Maks 2MB).</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="step-card">
                                    <div class="step-num shadow-sm">3</div>
                                    <p class="small text-muted mb-0">Upload bukti tersebut melalui <strong>tombol di atas</strong>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <style>
                        .step-num {
                            width: 32px;
                            height: 32px;
                            background: var(--bs-primary);
                            color: white;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-weight: bold;
                            margin: 0 auto 15px;
                        }
                        .btn-white-glass {
                            background: rgba(255, 255, 255, 0.2);
                            backdrop-filter: blur(10px);
                            color: white;
                            border: 1px solid rgba(255, 255, 255, 0.3);
                            transition: all 0.3s ease;
                        }
                        .btn-white-glass:hover {
                            background: white;
                            color: var(--bs-primary);
                            transform: translateY(-2px);
                        }
                    </style>
                    @endif

                </div>
                <div class="card-footer bg-light border-top p-4 text-center text-muted small">
                    Terima kasih telah melakukan pembayaran tepat waktu. <br>
                    LPT Nurul Ilmi
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bukti -->
<div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-body p-0 bg-dark rounded-4 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <img src="{{ asset('uploads/payment_proofs/' . $paymentRequest->proof_image) }}" class="img-fluid rounded-4 w-100" alt="Bukti Transfer">
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function validateImage(form) {
        const fileInput = form.querySelector('input[type="file"]');
        const filePath = fileInput.value;
        const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
        
        if (!allowedExtensions.exec(filePath)) {
            Swal.fire({
                icon: 'error',
                title: 'Format File Salah',
                text: 'Harap gunakan file dengan format gambar (JPG, JPEG, atau PNG)!',
                confirmButtonColor: '#0d6efd'
            });
            fileInput.value = '';
            return false;
        }

        // Check file size (2MB = 2048 * 1024 bytes)
        const fileSize = fileInput.files[0].size;
        const maxSize = 2 * 1024 * 1024;
        if (fileSize > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran file maksimal adalah 2MB!',
                confirmButtonColor: '#0d6efd'
            });
            fileInput.value = '';
            return false;
        }

        return true;
    }
</script>
@endsection
@endsection
