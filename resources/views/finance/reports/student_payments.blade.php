@extends('layouts.app')

@section('title', 'Laporan Pembayaran Siswa')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold mb-0 text-dark">
                <i class="bi bi-person-check-fill text-success me-2"></i> LAPORAN PEMBAYARAN SISWA
            </h4>
            <p class="text-muted small">Rekap detail transaksi pembayaran siswa berdasarkan filter yang dipilih.</p>
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
            <form action="{{ route('finance.reports.student-payments') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label x-small fw-bold text-muted text-uppercase">Tahun Pelajaran</label>
                    <select name="academic_year_id" class="form-select border-0 shadow-sm rounded-3">
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ $academic_year_id == $ay->id ? 'selected' : '' }}>TP {{ $ay->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label x-small fw-bold text-muted text-uppercase">Unit Sekolah</label>
                    <select name="unit_id" id="filter_unit_id" class="form-select border-0 shadow-sm rounded-3">
                        <option value="">Seluruh Unit</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ $unit_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label x-small fw-bold text-muted text-uppercase">Kelas</label>
                    <select name="class_id" id="filter_class_id" class="form-select border-0 shadow-sm rounded-3">
                        <option value="">Seluruh Kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $class_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
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

                <!-- Contextual Filters -->
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
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results -->
    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-muted x-small text-uppercase">
                        <tr>
                            <th class="ps-4">Invoice / Tanggal</th>
                            <th>NIS / Nama Siswa</th>
                            <th>Unit / Kelas</th>
                            <th>Rincian Pembayaran</th>
                            <th>Admin / Penerima</th>
                            <th class="text-end pe-4">Total (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalAmount = 0; @endphp
                        @forelse($transactions as $trx)
                            @php $totalAmount += $trx->amount; @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $trx->invoice_number }}</div>
                                    <div class="x-small text-muted">{{ $trx->transaction_date->translatedFormat('d F Y H:i') }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $trx->student->nama_lengkap ?? '-' }}</div>
                                    <div class="x-small text-muted">{{ $trx->student->nis ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 rounded-pill px-3">{{ $trx->student->unit->name ?? '-' }}</span>
                                    <div class="x-small text-muted mt-1">{{ $trx->student->current_class->name ?? '-' }}</div>
                                </td>
                                <td>
                                    @foreach($trx->items as $item)
                                        <div class="x-small text-dark fw-medium">• {{ $item->paymentType->name }} {{ $item->month_paid ? '('.$item->month_paid.'/'.$item->year_paid.')' : '' }}</div>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="x-small fw-bold text-dark">{{ $trx->user->name ?? '-' }}</div>
                                    <div class="x-small text-muted text-uppercase">{{ $trx->payment_method }}</div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="fw-bold text-primary">Rp {{ number_format($trx->amount, 0, ',', '.') }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <img src="{{ asset('template/dist/assets/img/empty.png') }}" alt="Empty" style="width: 100px; opacity: 0.3;">
                                    <p class="text-muted mt-3 italic">Tidak ada transaksi ditemukan untuk filter ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($transactions->isNotEmpty())
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="5" class="text-end py-3">GRAND TOTAL</td>
                            <td class="text-end pe-4 text-primary fs-5">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
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

        $('#filter_unit_id').change(function() {
            const unit_id = $(this).val();
            const ay_id = $('select[name="academic_year_id"]').val();
            if (unit_id) {
                $.ajax({
                    url: '/get-classes/' + unit_id,
                    data: { academic_year_id: ay_id },
                    success: function(res) {
                        let html = '<option value="">Seluruh Kelas</option>';
                        res.forEach(c => {
                            html += `<option value="${c.id}">${c.name}</option>`;
                        });
                        $('#filter_class_id').html(html);
                    }
                });
            } else {
                $('#filter_class_id').html('<option value="">Seluruh Kelas</option>');
            }
        });
    });
</script>
@endpush

<style>
    .x-small { font-size: 0.75rem; }
    .btn-white { background: #fff; border: 1px solid #dee2e6; color: #444; }
    .btn-white:hover { background: #f8f9fa; }
</style>
@endsection
