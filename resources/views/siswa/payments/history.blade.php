@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; }
    .ls-1 { letter-spacing: 0.5px; }
    .ls-2 { letter-spacing: 2px; }
    .glass-header { 
        background: linear-gradient(135deg, #0A66C2 0%, #1e3a8a 100%);
        border-radius: 0 0 40px 40px;
        padding-bottom: 80px;
    }
    .card-overlap { margin-top: -60px; }
    .table-responsive { border-radius: 20px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .badge-soft-success { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .badge-soft-info { background: rgba(14, 165, 233, 0.1); color: #0ea5e9; border: 1px solid rgba(14, 165, 233, 0.2); }
    .transaction-card { transition: all 0.3s ease; border: none; }
    .transaction-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px -10px rgba(0,0,0,0.1) !important; }
</style>
@endpush

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
                        <h2 class="fw-extrabold mb-1">Riwayat Pembayaran</h2>
                        <p class="mb-0 text-white-50">Lihat semua transaksi pembayaran yang telah Anda lakukan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex justify-content-md-end mt-4 mt-md-0">
                <div class="date-pill shadow-sm">
                    <div class="icon-box">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div class="small fw-bold text-white-50 text-uppercase ls-1">Status Akun</div>
                        <div class="fw-extrabold lh-1" style="font-size: 1.1rem;">TERVERIFIKASI</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-lg-4">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-premium align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">TANGGAL & NO. INVOICE</th>
                            <th>URAIAN PEMBAYARAN</th>
                            <th class="text-end">NOMINAL</th>
                            <th class="text-center">METODE</th>
                            <th class="text-center">STATUS</th>
                            <th class="pe-4 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $t)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $t->transaction_date->translatedFormat('d M Y') }}</div>
                                    <div class="small text-muted font-monospace opacity-75">{{ $t->invoice_number }}</div>
                                </td>
                                <td>
                                    @foreach($t->items as $item)
                                        <div class="small mb-1">
                                            <span class="fw-bold text-primary">{{ $item->paymentType->name ?? '-' }}</span>
                                            @if($item->month_paid)
                                                <span class="text-muted" style="font-size: 0.75rem;">({{ \Carbon\Carbon::create()->month((int)$item->month_paid)->translatedFormat('F') }} {{ $item->year_paid }})</span>
                                            @endif
                                        </div>
                                    @endforeach
                                    <div class="mt-1" style="font-size: 0.65rem; color: #64748b; font-weight: 600;">
                                        <i class="bi bi-person-check me-1"></i> KASIR: {{ strtoupper($t->user->name ?? 'System') }}
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="fw-extrabold text-dark" style="font-size: 1.1rem;">Rp {{ number_format($t->amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-soft-info rounded-pill px-3 py-2 text-uppercase ls-1" style="font-size: 0.6rem;">
                                        {{ $t->payment_method }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-soft-success rounded-pill px-3 py-2 fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                        <i class="bi bi-check-circle-fill me-1"></i> LUNAS
                                    </span>
                                </td>
                                <td class="pe-4 text-center">
                                    <a href="{{ route('finance.payments.receipt', $t->id) }}" target="_blank" class="btn btn-primary-premium btn-sm rounded-pill shadow-sm">
                                        <i class="bi bi-printer me-1"></i> Kuitansi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 text-muted opacity-25"></i>
                                        <p class="text-muted fw-bold">Belum ada riwayat transaksi pembayaran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($transactions->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $transactions->links() }}
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
    .date-pill {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 10px 20px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .date-pill .icon-box {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
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
        font-size: 0.9rem;
    }
    .badge-soft-success { background: #dcfce7; color: #16a34a; font-weight: 700; }
    .badge-soft-info { background: #e0f2fe; color: #0369a1; font-weight: 700; }
    .badge-soft-warning { background: #fef3c7; color: #d97706; font-weight: 700; }

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
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        transform: translateY(-1px);
    }

    /* Force horizontal scroll on mobile */
    @media (max-width: 768px) {
        .table-premium {
            min-width: 800px;
        }
    }
</style>
@endsection
