@extends('layouts.app')

@section('title', 'Rekap Laporan Per Tahun Pelajaran')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold mb-0 text-dark">
                <i class="bi bi-file-earmark-bar-graph text-primary me-2"></i> REKAP PER TAHUN PELAJARAN
            </h4>
            <p class="text-muted small">Menganalisis performa keuangan berdasarkan kurikulum Tahun Pelajaran {{ $selectedAY->name }}.</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group shadow-sm rounded-pill overflow-hidden border">
                <a href="{{ route('finance.reports.index', ['mode' => 'daily', 'academic_year_id' => $academic_year_id, 'unit_id' => $unit_id]) }}" class="btn {{ $mode == 'daily' ? 'btn-primary' : 'btn-white' }} px-4 fw-bold">HARIAN</a>
                <a href="{{ route('finance.reports.index', ['mode' => 'monthly', 'academic_year_id' => $academic_year_id, 'unit_id' => $unit_id]) }}" class="btn {{ $mode == 'monthly' ? 'btn-primary' : 'btn-white' }} px-4 fw-bold">BULANAN</a>
                <a href="{{ route('finance.reports.index', ['mode' => 'annual', 'academic_year_id' => $academic_year_id, 'unit_id' => $unit_id]) }}" class="btn {{ $mode == 'annual' ? 'btn-primary' : 'btn-white' }} px-4 fw-bold">TAHUNAN (FULL)</a>
            </div>
        </div>
    </div>

    <!-- Primary Filter: Academic Year & Unit -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <div class="card-body p-4 bg-light bg-opacity-50">
            <form action="{{ route('finance.reports.index') }}" method="GET" class="row g-3">
                <input type="hidden" name="mode" value="{{ $mode }}">
                
                <div class="col-md-3">
                    <label class="form-label x-small fw-bold text-muted text-uppercase ls-1">Tahun Pelajaran</label>
                    <select name="academic_year_id" class="form-select border-0 shadow-sm rounded-3" onchange="this.form.submit()">
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ $academic_year_id == $ay->id ? 'selected' : '' }}>TP {{ $ay->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label x-small fw-bold text-muted text-uppercase ls-1">Unit Sekolah</label>
                    <select name="unit_id" class="form-select border-0 shadow-sm rounded-3">
                        <option value="">Seluruh Unit</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ $unit_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                @if($mode == 'daily')
                    <div class="col-md-3">
                        <label class="form-label x-small fw-bold text-muted text-uppercase ls-1">Tanggal Transaksi</label>
                        <input type="date" name="date" class="form-control border-0 shadow-sm rounded-3" value="{{ $date }}">
                    </div>
                @endif

                @if($mode == 'monthly')
                    <div class="col-md-3">
                        <label class="form-label x-small fw-bold text-muted text-uppercase ls-1">Pilih Bulan</label>
                        <select name="month" class="form-select border-0 shadow-sm rounded-3">
                            @php 
                                $monthsOrder = [7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6];
                                $monthsName = [7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember', 1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni'];
                            @endphp
                            @foreach($monthsOrder as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $monthsName[$m] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 shadow-sm py-2">
                        <i class="bi bi-filter me-1"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="mb-4">
        <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill border">
            <i class="bi bi-info-circle me-1"></i> Menampilkan data: 
            <strong>{{ strtoupper($mode) }}</strong> - 
            <strong>TP {{ $selectedAY->name }}</strong> 
            @if($mode == 'monthly') - <strong>{{ $monthsName[$month] }}</strong> @endif
            @if($mode == 'daily') - <strong>{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</strong> @endif
        </span>
    </div>

    <!-- Summary Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white overflow-hidden" style="border-radius: 18px;">
                <div class="card-body p-4 py-5 position-relative">
                    <i class="bi bi-cash-coin display-5 position-absolute opacity-25" style="top: 10px; right: 10px;"></i>
                    <p class="x-small text-uppercase fw-bold ls-1 mb-1 opacity-75">Realisasi (Diterima)</p>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($realizedIncome, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-dark text-white overflow-hidden" style="border-radius: 18px;">
                <div class="card-body p-4 py-5 position-relative">
                    <i class="bi bi-journal-text display-5 position-absolute opacity-25" style="top: 10px; right: 10px;"></i>
                    <p class="x-small text-uppercase fw-bold ls-1 mb-1 opacity-75">Target Tagihan</p>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalBillAmount, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-success text-white overflow-hidden" style="border-radius: 18px;">
                <div class="card-body p-4 py-5 position-relative">
                    <i class="bi bi-check-circle-fill display-5 position-absolute opacity-25" style="top: 10px; right: 10px;"></i>
                    <p class="x-small text-uppercase fw-bold ls-1 mb-1 opacity-75">Tagihan Terbayar</p>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($paidBillAmount, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
             <div class="card border-0 shadow-sm h-100 bg-danger text-white overflow-hidden" style="border-radius: 18px;">
                <div class="card-body p-4 py-5 position-relative">
                    <i class="bi bi-clock-history display-5 position-absolute opacity-25" style="top: 10px; right: 10px;"></i>
                    <p class="x-small text-uppercase fw-bold ls-1 mb-1 opacity-75">Tunggakan (Sisa)</p>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($remainingBillAmount, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 p-4">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-list-check text-primary me-2"></i> RINCIAN REALISASI PER JENIS PEMBAYARAN</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-muted x-small text-uppercase ls-1">
                                <tr>
                                    <th class="ps-4 py-3">JENIS PEMBAYARAN</th>
                                    <th class="text-end pe-4">TOTAL TERKUMPUL (RP)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incomeByType as $item)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-bold text-dark">{{ $item->name }}</div>
                                            <div class="x-small text-muted">TP {{ $selectedAY->name }}</div>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-primary">
                                            Rp {{ number_format($item->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-5 text-muted italic">
                                            Tidak ada realisasi pembayaran pada periode/TP ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($incomeByType->isNotEmpty())
                                <tfoot class="bg-light fw-bold">
                                    <tr>
                                        <td class="ps-4">TOTAL KESELURUHAN</td>
                                        <td class="text-end pe-4">Rp {{ number_format($realizedIncome, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                 <div class="card-header bg-white border-0 p-4">
                    <h6 class="fw-bold text-dark mb-0">EFISIENSI PENAGIHAN TP {{ $selectedAY->name }}</h6>
                </div>
                <div class="card-body p-4 text-center">
                    @php 
                        $percentage = $totalBillAmount > 0 ? ($paidBillAmount / $totalBillAmount * 100) : 0;
                        $color = 'primary';
                        if($percentage < 50) $color = 'danger';
                        elseif($percentage < 80) $color = 'warning';
                        else $color = 'success';
                    @endphp
                    <div class="mb-4">
                        <div class="position-relative d-inline-block">
                             <svg width="180" height="180" viewBox="0 0 200 200">
                                <circle cx="100" cy="100" r="90" fill="transparent" stroke="#f1f1f1" stroke-width="15"></circle>
                                <circle cx="100" cy="100" r="90" fill="transparent" stroke="{{ $color == 'primary' ? '#0d6efd' : ($color == 'danger' ? '#dc3545' : ($color == 'warning' ? '#ffc107' : '#198754')) }}" 
                                        stroke-width="15" stroke-dasharray="{{ ($percentage / 100) * 565 }} 565" 
                                        stroke-dashoffset="0" stroke-linecap="round" transform="rotate(-90 100 100)"></circle>
                             </svg>
                             <div class="position-absolute top-50 start-50 translate-middle">
                                <h2 class="fw-bold mb-0">{{ round($percentage, 1) }}%</h2>
                                <p class="x-small text-muted mb-0">Tertagih</p>
                             </div>
                        </div>
                    </div>
                    <div class="text-start bg-light rounded-4 p-3 border">
                        <p class="x-small text-muted mb-2 fw-bold text-uppercase ls-1">ANALISIS TP</p>
                        <p class="small mb-0 opacity-75">
                            Efisiensi penagihan didasarkan pada total tagihan yang dibuat untuk siswa selama Tahun Pelajaran {{ $selectedAY->name }} dibandingkan dengan dana yang sudah berhasil didepositkan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .x-small { font-size: 0.8rem; }
    .btn-white { background-color: #fff; color: #555; }
    .btn-white:hover { background-color: #f8f9fa; }
    .bg-primary-soft { background-color: rgba(13, 110, 253, 0.05); }
    svg circle { transition: stroke-dasharray 1s ease-in-out; }
</style>
@endsection
