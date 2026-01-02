@extends('layouts.app')

@section('title', 'Laporan Kas Umum')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold mb-0 text-dark">
                <i class="bi bi-journal-text text-primary me-2"></i> LAPORAN KAS UMUM
            </h4>
            <p class="text-muted small">Rekap pemasukan umum dan pengeluaran kas operasional.</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group shadow-sm rounded-pill overflow-hidden border">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-white px-4">
                    <i class="bi bi-file-earmark-excel text-success me-1"></i> Excel
                </a>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-white px-4">
                    <i class="bi bi-file-earmark-pdf text-danger me-1"></i> PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <div class="card-body p-4 bg-light bg-opacity-50">
            <form action="{{ route('finance.reports.general-ledger') }}" method="GET" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label x-small fw-bold text-muted text-uppercase">Tipe Transaksi</label>
                    <select name="type" class="form-select border-0 shadow-sm rounded-3">
                        <option value="">Semua (Pemasukan & Pengeluaran)</option>
                        <option value="income" {{ $type == 'income' ? 'selected' : '' }}>Pemasukan Umum</option>
                        <option value="expense" {{ $type == 'expense' ? 'selected' : '' }}>Pengeluaran Kas</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label x-small fw-bold text-muted text-uppercase">Unit</label>
                    <select name="unit_id" class="form-select border-0 shadow-sm rounded-3">
                        <option value="">Seluruh Unit/Pusat</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ $unit_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label x-small fw-bold text-muted text-uppercase">Kategori</label>
                    <select name="category" class="form-select border-0 shadow-sm rounded-3">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label x-small fw-bold text-muted text-uppercase">Periode</label>
                    <select name="period" id="filter_period" class="form-select border-0 shadow-sm rounded-3">
                        <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>

                <div class="col-md-3" id="wrapper_date">
                    <label class="form-label x-small fw-bold text-muted text-uppercase" id="label_date">Pilih Tanggal</label>
                    <input type="date" name="date" class="form-control border-0 shadow-sm rounded-3" value="{{ $date }}">
                </div>

                <div class="col-md-2 d-none" id="wrapper_month">
                    <label class="form-label x-small fw-bold text-muted text-uppercase">Bulan</label>
                    <select name="month" class="form-select border-0 shadow-sm rounded-3">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(2025, $m, 1)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2 d-none" id="wrapper_year">
                    <label class="form-label x-small fw-bold text-muted text-uppercase">Tahun</label>
                    <input type="number" name="year" class="form-control border-0 shadow-sm rounded-3" value="{{ $year }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 shadow-sm py-2">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        @php 
            $incTotal = $records->where('type', 'income')->sum('amount');
            $expTotal = $records->where('type', 'expense')->sum('amount');
        @endphp
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white p-3" style="border-radius: 15px;">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                    <div>
                        <p class="mb-0 x-small opacity-75 fw-bold text-uppercase ls-1">Total Masuk</p>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($incTotal, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-danger text-white p-3" style="border-radius: 15px;">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="bi bi-graph-down-arrow fs-4"></i>
                    </div>
                    <div>
                        <p class="mb-0 x-small opacity-75 fw-bold text-uppercase ls-1">Total Keluar</p>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($expTotal, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white p-3" style="border-radius: 15px;">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                    <div>
                        <p class="mb-0 x-small opacity-75 fw-bold text-uppercase ls-1">Saldo Bersih (Net)</p>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($incTotal - $expTotal, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-muted x-small text-uppercase">
                        <tr>
                            <th class="ps-4">Tanggal / Tipe</th>
                            <th>Kategori / Keterangan</th>
                            <th>Pihak Kedua (Sumber/Penerima)</th>
                            <th>Unit / Metode</th>
                            <th>Admin / Pencatat</th>
                            <th class="text-end pe-4">Jumlah (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $row)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $row->transaction_date->translatedFormat('d F Y') }}</div>
                                <span class="badge {{ $row->type == 'income' ? 'bg-success' : 'bg-danger' }} bg-opacity-10 {{ $row->type == 'income' ? 'text-success' : 'text-danger' }} rounded-pill px-2" style="font-size: 0.65rem;">{{ strtoupper($row->type) }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $row->category }}</div>
                                <div class="x-small text-muted">{{ $row->description ?: '-' }}</div>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $row->payer_name ?: '-' }}</div>
                            </td>
                            <td>
                                <div class="x-small fw-bold">{{ $row->unit->name ?? 'Internal/Umum' }}</div>
                                <div class="x-small text-muted text-uppercase">{{ $row->payment_method }}</div>
                            </td>
                            <td>
                                <div class="x-small fw-bold text-dark">{{ $row->user->name ?? '-' }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="fw-bold {{ $row->type == 'income' ? 'text-success' : 'text-danger' }}">
                                    {{ $row->type == 'income' ? '+' : '-' }} Rp {{ number_format($row->amount, 0, ',', '.') }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted italic">Tidak ada data transaksi Kas Umum dalam periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#filter_period').change(function() {
            const val = $(this).val();
            if (val === 'daily' || val === 'weekly') {
                $('#wrapper_date').removeClass('d-none');
                $('#wrapper_month, #wrapper_year').addClass('d-none');
                $('#label_date').text(val === 'daily' ? 'Pilih Tanggal' : 'Pilih Tanggal dlm Minggu');
            } else {
                $('#wrapper_date').addClass('d-none');
                $('#wrapper_month, #wrapper_year').removeClass('d-none');
            }
        }).trigger('change');
    });
</script>
@endpush

<style>
    .x-small { font-size: 0.75rem; }
    .ls-1 { letter-spacing: 0.5px; }
    .btn-white { background: #fff; border: 1px solid #dee2e6; color: #444; }
    .btn-white:hover { background: #f8f9fa; }
</style>
@endsection
