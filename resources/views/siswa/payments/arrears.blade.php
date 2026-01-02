@extends('layouts.app')

@section('title', 'Daftar Tunggakan')

@push('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        border-radius: 24px;
        padding: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.2);
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        filter: blur(50px);
    }
    .welcome-icon {
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        backdrop-filter: blur(10px);
        margin-right: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .date-pill {
        background: rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(15px);
        border-radius: 50px;
        padding: 12px 25px;
        display: flex;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .icon-box {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        color: #f59e0b;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .arrears-card-group {
        border: none;
        border-radius: 24px;
        transition: transform 0.3s ease;
    }
    .arrears-card-group:hover {
        transform: translateY(-5px);
    }
    .table-premium thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 1px;
        color: #64748b;
        border: none;
    }
    .badge-soft-danger { background: #ef4444; color: #ffffff; font-weight: 700; }
    .badge-soft-warning { background: #fef3c7; color: #f59e0b; font-weight: 700; }
    .badge-soft-info { background: #e0f2fe; color: #0369a1; font-weight: 700; }
    .card-overlap { margin-top: -50px; }
    
    .month-card {
        flex: 1 1 180px;
        max-width: 250px;
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .month-card:hover {
        border-color: #f59e0b;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .month-card.overdue {
        border-left: 4px solid #ef4444;
    }
    .month-card.upcoming {
        border-left: 4px solid #3b82f6;
        background: #f0f7ff;
    }
    .month-card.upcoming:hover {
        border-color: #3b82f6;
        background: #e0efff;
    }
    .month-card .month-name {
        font-size: 0.9rem;
        font-weight: 800;
        color: #1e293b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: normal;
        line-height: 1.2;
        padding-right: 0; /* Removed padding right since status moved */
        margin-bottom: 5px;
    }
    .month-card .year-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 600;
    }
    .month-card .amount {
        font-size: 1.1rem;
        font-weight: 800;
        color: #ef4444;
        margin-top: 10px;
    }
    .month-card .status-indicator {
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-lg-4 mt-3">
    <!-- Modern Header Banner -->
    <div class="welcome-banner">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="welcome-icon shadow-sm">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <h2 class="fw-extrabold mb-1">Rincian Tunggakan</h2>
                        <p class="mb-0 text-white-50">Daftar tagihan yang belum dilunasi hingga saat ini.</p>
                    </div>
                </div>
            </div>
            @php
                // Flatten all bills from grouped collection to calculate total upcoming
                $allBillsCollection = collect($groupedBills)->flatten();
                $totalUpcoming = $allBillsCollection->where('is_overdue', false)->sum(function($b) { return $b->amount - $b->paid_amount; });
            @endphp
            <div class="col-md-4 d-flex flex-column align-items-md-end mt-4 mt-md-0 gap-2">
                <div class="date-pill shadow-sm">
                    <div class="icon-box">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <div class="small fw-bold text-white-50 text-uppercase ls-1">Total Tunggakan</div>
                        <div class="fw-extrabold lh-1" style="font-size: 1.25rem;">Rp {{ number_format($totalArrears, 0, ',', '.') }}</div>
                    </div>
                </div>
                @if($totalUpcoming > 0)
                <div class="date-pill shadow-sm py-2" style="background: rgba(255,255,255,0.1);">
                    <div class="icon-box" style="color: #3b82f6; width: 30px; height: 30px;">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div>
                        <div class="small fw-bold text-white-50 text-uppercase ls-1" style="font-size: 0.55rem;">Tagihan Mendatang</div>
                        <div class="fw-bold lh-1" style="font-size: 0.9rem;">Rp {{ number_format($totalUpcoming, 0, ',', '.') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-lg-4 mb-5">
    <div class="card-overlap">
        <!-- DEBUG: VERSION 2.1 - MULTI-STATUS BILLS -->
    @if($groupedBills->isNotEmpty())
        @foreach($groupedBills as $typeName => $bills)
            @php
                $subtotalRemaining = $bills->sum(function($b) { return $b->amount - $b->paid_amount; });
            @endphp
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 arrears-card-group">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">{{ $typeName }}</h5>
                        <p class="text-muted small mb-0">{{ $bills->count() }} Tagihan Belum Lunas</p>
                    </div>
                    <div class="text-end">
                        <small class="text-uppercase text-muted fw-bold ls-1" style="font-size: 0.65rem;">Total Group</small>
                        <div class="fw-bold text-danger">Rp {{ number_format($subtotalRemaining, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($bills->first()->paymentType->type == 'monthly')
                        <div class="d-flex flex-wrap gap-3">
                            @foreach($bills as $bill)
                                @php
                                    $remaining = $bill->amount - $bill->paid_amount;
                                @endphp
                                <div class="month-card {{ $bill->is_overdue ? 'overdue' : 'upcoming' }} shadow-sm">
                                    <div class="status-indicator">
                                        @if($bill->status == 'partial')
                                            <span class="badge badge-soft-warning rounded-pill px-2 py-1" style="font-size: 0.6rem;">DICICIL</span>
                                        @elseif($bill->is_overdue)
                                            <span class="badge badge-soft-danger rounded-pill px-2 py-1" style="font-size: 0.6rem;">TUNGGAKAN</span>
                                        @else
                                            <span class="badge badge-soft-info rounded-pill px-2 py-1" style="font-size: 0.6rem;">AKAN DATANG</span>
                                        @endif
                                    </div>

                                    <div class="month-name">
                                        @php
                                            $yearDisplay = $bill->year;
                                            if (!$yearDisplay && $bill->academicYear) {
                                                $yearDisplay = ($bill->month >= 7) ? $bill->academicYear->start_year : ($bill->academicYear->start_year + 1);
                                            }
                                        @endphp
                                        {{ $bill->month ? \Carbon\Carbon::create()->month((int)$bill->month)->translatedFormat('F') : 'Bulan -' }} 
                                        <span class="text-muted" style="font-weight: 600;">{{ $yearDisplay ?? '-' }}</span>
                                    </div>
                                    <div class="amount {{ $bill->is_overdue ? 'text-danger' : 'text-primary' }}">Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                                    
                                    @if($bill->paid_amount > 0)
                                        <div class="mt-2 small text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Terbayar: <span class="text-success fw-bold">Rp {{ number_format($bill->paid_amount, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-3">
                                        <small class="text-muted d-block opacity-75" style="font-size: 0.65rem;">TA: {{ $bill->academicYear->name ?? '-' }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-premium align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 40%;">Periode / Keterangan</th>
                                        <th class="py-3 text-end">Tagihan</th>
                                        <th class="py-3 text-end">Terbayar</th>
                                        <th class="py-3 text-end">Sisa</th>
                                        <th class="pe-4 py-3 text-center" style="width: 15%;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bills as $bill)
                                        @php
                                            $remaining = $bill->amount - $bill->paid_amount;
                                        @endphp
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                                        <i class="bi bi-file-earmark-text-fill" style="font-size: 0.8rem;"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark">{{ $bill->paymentType->name }}</div>
                                                        <div class="small text-muted" style="font-size: 0.75rem;">{{ $bill->academicYear->name ?? '-' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 text-end">
                                                <div class="text-secondary fw-semibold">Rp {{ number_format($bill->amount, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="py-3 text-end">
                                                @if($bill->paid_amount > 0)
                                                    <div class="text-success fw-bold small">Rp {{ number_format($bill->paid_amount, 0, ',', '.') }}</div>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-end">
                                                <div class="text-danger fw-bold">Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="pe-4 py-3 text-center">
                                                @if($bill->status == 'partial')
                                                    <span class="badge badge-soft-warning rounded-pill px-2 py-1" style="font-size: 0.65rem;">DICICIL</span>
                                                @elseif($bill->is_overdue)
                                                    <span class="badge badge-soft-danger rounded-pill px-2 py-1" style="font-size: 0.65rem;">BELUM BAYAR</span>
                                                @else
                                                    <span class="badge badge-soft-info rounded-pill px-2 py-1" style="font-size: 0.65rem;">AKAN DATANG</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
        
        <div class="card border-0 bg-transparent text-center mt-5 mb-5">
             <p class="mb-0 text-muted opacity-75">
                <i class="bi bi-info-circle me-1"></i> 
                Untuk pembayaran, silakan hubungi bagian administrasi keuangan sekolah.
            </p>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-5 p-5 text-center">
            <div class="py-5">
                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 100px; height: 100px;">
                    <i class="bi bi-check-circle-fill display-4 text-success"></i>
                </div>
                <h3 class="fw-bold text-dark mb-2">Alhamdulillah, Tidak Ada Tunggakan!</h3>
                <p class="text-muted mb-4 px-md-5">Seluruh tagihan Anda telah lunas. Terima kasih atas ketaatan Anda dalam melakukan pembayaran tepat waktu.</p>
                <a href="{{ route('siswa.payments.history') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm">
                    <i class="bi bi-clock-history me-2"></i> LIHAT RIWAYAT PEMBAYARAN
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
