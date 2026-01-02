@extends('layouts.app')

@section('title', 'Riwayat Pengajuan Pembayaran')

@section('content')
<div class="container-fluid px-lg-4 mt-3">
    <!-- Modern Header Banner -->
    <div class="welcome-banner mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="welcome-icon shadow-sm me-3">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h2 class="fw-extrabold mb-1">Status Pembayaran</h2>
                        <p class="mb-0 text-white-50">Pantau status verifikasi pembayaran transfer Anda secara real-time.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex justify-content-md-end mt-4 mt-md-0">
                <div class="d-flex gap-2">
                    <a href="{{ route('siswa.payments.requests.create') }}" class="btn btn-white-glass shadow-sm fw-bold px-4">
                        <i class="bi bi-plus-lg me-2"></i> Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-lg-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 h5 mb-0"></i>
                <div class="fw-bold">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-premium align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">TANGGAL & WAKTU</th>
                            <th>REKENING TUJUAN</th>
                            <th>URAIAN</th>
                            <th class="text-end">TOTAL BAYAR</th>
                            <th class="text-center">STATUS</th>
                            <th class="pe-4 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $req->created_at->translatedFormat('d M Y') }}</div>
                                    <div class="small font-monospace text-primary fw-bold" style="font-size: 0.75rem;">{{ $req->reference_code }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $req->bankAccount->bank_name ?? '-' }}</div>
                                    <div class="small text-muted opacity-75 text-uppercase ls-1" style="font-size: 0.7rem;">{{ $req->bankAccount->account_number ?? '' }}</div>
                                </td>
                                <td>
                                    @foreach($req->items as $item)
                                        <div class="small fw-bold text-dark mb-1">
                                            <i class="bi bi-dot text-primary"></i> {{ $item->studentBill->paymentType->name ?? '-' }}
                                            <span class="text-muted fw-normal" style="font-size: 0.65rem;">
                                                ({{ $item->studentBill->month ? \Carbon\Carbon::create()->month((int)$item->studentBill->month)->translatedFormat('F') : '' }} {{ $item->studentBill->year ?? ($item->studentBill->academicYear->start_year ?? '') }})
                                            </span>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="text-end">
                                    <div class="fw-extrabold text-primary">Rp {{ number_format($req->total_amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $badges = [
                                            'waiting_proof' => 'badge-soft-warning',
                                            'pending' => 'badge-soft-info',
                                            'verified' => 'badge-soft-success',
                                            'rejected' => 'badge-soft-danger'
                                        ];
                                        $labels = [
                                            'waiting_proof' => 'PENDING',
                                            'pending' => 'PROSES',
                                            'verified' => 'DITERIMA & DIVERIFIKASI',
                                            'rejected' => 'DITOLAK'
                                        ];
                                    @endphp
                                    <span class="badge {{ $badges[$req->status] }} rounded-pill px-3 py-2" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                        {{ $labels[$req->status] }}
                                    </span>
                                    @if($req->status == 'verified' && $req->transaction)
                                        <div class="mt-2 small text-muted fw-bold" style="font-size: 0.65rem;">
                                            <i class="bi bi-receipt me-1"></i> {{ $req->transaction->invoice_number }}
                                        </div>
                                    @endif
                                </td>
                                <td class="pe-4 text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        @if($req->proof_image)
                                            <button type="button" class="btn btn-light-premium btn-sm rounded-pill shadow-sm" 
                                                onclick="showProof('{{ asset('uploads/payment_proofs/' . $req->proof_image) }}')"
                                                data-bs-toggle="tooltip" title="Lihat Bukti Foto">
                                                <i class="bi bi-image me-1"></i> Bukti
                                            </button>
                                        @else
                                            <a href="{{ route('siswa.payments.requests.show', $req->id) }}" class="btn btn-warning btn-sm rounded-pill shadow-sm fw-bold">
                                                <i class="bi bi-upload me-1"></i> Upload Bukti
                                            </a>
                                        @endif
                                        <a href="{{ route('siswa.payments.requests.show', $req->id) }}" class="btn btn-primary-premium btn-sm rounded-pill shadow-sm" data-bs-toggle="tooltip" title="Detail Invoice">
                                            <i class="bi bi-file-earmark-text me-1"></i> Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-receipt fs-1 d-block mb-3 text-muted opacity-25"></i>
                                        <p class="text-muted fw-bold">Belum ada riwayat pengajuan pembayaran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($requests->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    .welcome-banner {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-radius: 24px;
        padding: 2.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::before {
        content: "";
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    .welcome-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .btn-white-glass {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 14px;
        transition: all 0.3s ease;
    }
    .btn-white-glass:hover {
        background: white;
        color: #d97706;
        transform: translateY(-2px);
    }
    .fw-extrabold { font-weight: 800; }
    .ls-1 { letter-spacing: 0.5px; }

    /* Premium Table Styles */
    .table-premium thead th {
        background-color: #f8fafc;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #64748b;
        border: none;
        padding: 1.25rem 1rem;
    }
    .table-premium tbody td {
        padding: 1.25rem 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .badge-soft-success { background: #dcfce7; color: #16a34a; font-weight: 700; }
    .badge-soft-warning { background: #fef3c7; color: #d97706; font-weight: 700; }
    .badge-soft-info { background: #e0f2fe; color: #0369a1; font-weight: 700; }
    .badge-soft-danger { background: #fee2e2; color: #dc2626; font-weight: 700; }

    .btn-light-premium {
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        border: none;
        padding: 0.5rem 1.25rem;
        transition: all 0.2s;
    }
    .btn-light-premium:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    .btn-primary-premium {
        background: #f59e0b;
        color: white;
        font-weight: 600;
        border: none;
        padding: 0.5rem 1.25rem;
        transition: all 0.2s;
    }
    .btn-primary-premium:hover {
        background: #d97706;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
</style>

<!-- Modal Bukti (Tetap) -->
<div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg bg-transparent">
            <div class="modal-body p-0 position-relative text-center">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <img id="proofImage" src="" class="img-fluid rounded-4 shadow-lg" alt="Bukti Transfer" style="max-height: 85vh;">
            </div>
        </div>
    </div>
</div>

<script>
    function showProof(url) {
        document.getElementById('proofImage').src = url;
        var myModal = new bootstrap.Modal(document.getElementById('proofModal'));
        myModal.show();
    }
</script>
@endsection
