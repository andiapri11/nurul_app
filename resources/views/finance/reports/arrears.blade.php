@extends('layouts.app')

@section('title', 'Laporan Tunggakan Siswa')

@push('styles')
<style>
    .month-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 2px;
    }
    .bg-unpaid { background-color: #ef4444; } /* red-500 */
    .bg-partial { background-color: #f59e0b; } /* amber-500 */
    .bg-free { background-color: #0dcaf0; } /* info/cyan */
    .bg-paid { background-color: #dee2e6; border: 1px solid #ced4da; } /* grey */
    
    .type-row { background-color: #fcfcfc; }
    .student-group-header { background-color: #f8f9fa; border-top: 2px solid #dee2e6; }
    
    @media print {
        .card-header, .filter-section, .btn-print, .no-print { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .table { font-size: 9pt !important; }
        body { background: white !important; }
        .student-group-header { background-color: #eee !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4 no-print">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Laporan Rekap Tunggakan</h3>
            <p class="text-muted">Daftar siswa dan rincian tunggakan per jenis pembayaran.</p>
        </div>
        <div class="col-md-6 text-end">
            <button onclick="window.print()" class="btn btn-dark rounded-pill px-4 shadow-sm btn-print">
                <i class="bi bi-printer me-2"></i> Cetak Laporan
            </button>
            <a href="{{ route('finance.reports.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm ms-2">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm border-0 mb-4 rounded-4 filter-section no-print">
        <div class="card-body bg-light">
            <form action="{{ route('finance.reports.arrears') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">Tahun Pelajaran</label>
                    <select name="academic_year_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ $selectedYearId == $ay->id ? 'selected' : '' }}>
                                TP {{ $ay->start_year }}/{{ $ay->end_year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">Unit</label>
                    <select name="unit_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Unit --</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ $selectedUnitId == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">Kelas</label>
                    <select name="class_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm border-0">
                        <i class="bi bi-funnel me-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    @php
        $months = [
            7 => 'Juli', 8 => 'Agust', 9 => 'Sept', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Juni'
        ];
    @endphp

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">TABEL RINCIAN TUNGGAKAN</h5>
            <span class="badge bg-primary px-3 py-2 rounded-pill">{{ $students->count() }} Siswa Terdaftar</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Identitas Siswa & Jenis Pembayaran</th>
                            <th class="text-center">Status / Matriks (Bulan)</th>
                            <th class="text-end pe-4" width="200">Total Tunggakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $idx => $s)
                            @php
                                $sBills = $allBills->get($s->id, collect());
                                $studentTypes = $sBills->groupBy('payment_type_id');
                                $totalStudentDebt = $sBills->sum(function($b) { return $b->amount - $b->paid_amount; });
                            @endphp
                            
                            <!-- Student Header -->
                            <tr class="student-group-header">
                                <td class="ps-4 fw-bold text-muted">{{ $idx + 1 }}</td>
                                <td colspan="2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-bold text-dark text-uppercase fs-6">{{ $s->nama_lengkap }}</span>
                                            <span class="ms-2 badge bg-white text-muted border fw-normal">{{ $s->nis }}</span>
                                            <span class="ms-2 small text-muted">| {{ $s->unit->name ?? '-' }} - {{ $s->classes->first()->name ?? '-' }}</span>
                                        </div>
                                        <div class="no-print">
                                            <a href="{{ route('finance.payments.show', ['student' => $s->id, 'academic_year_id' => $selectedYearId]) }}" class="btn btn-link btn-sm p-0 text-decoration-none x-small fw-bold">
                                                Detail POS <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    @if($totalStudentDebt > 0)
                                        <div class="fw-bold text-danger">Total: Rp {{ number_format($totalStudentDebt, 0, ',', '.') }}</div>
                                    @else
                                        <div class="badge bg-success text-white rounded-pill px-3 py-2 shadow-sm" style="font-size: 0.75rem;">
                                            <i class="bi bi-patch-check-fill me-1"></i> LUNAS KESELURUHAN
                                        </div>
                                    @endif
                                </td>
                            </tr>

                            <!-- Payment Types Rows -->
                            @foreach($studentTypes as $typeId => $bills)
                                @php 
                                    $pType = $bills->first()->paymentType;
                                    $typeDebt = $bills->sum(function($b) { return $b->amount - $b->paid_amount; });
                                    // Skip if no debt and not for monthly display consistency? 
                                    // Actually user wants all types set for them.
                                @endphp
                                <tr class="type-row">
                                    <td></td>
                                    <td class="ps-4">
                                        <div class="small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Jenis Pembayaran</div>
                                        <div class="fw-bold text-dark">{{ $pType->name }}</div>
                                        @if($pType->type == 'one_time')
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary x-small fw-normal">Sekali Bayar</span>
                                        @else
                                            <span class="badge bg-primary bg-opacity-10 text-primary x-small fw-normal">Bulanan</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($pType->type == 'monthly')
                                            <div class="d-flex justify-content-center gap-1">
                                                @foreach($months as $mNum => $mName)
                                                    @php 
                                                      $bill = $bills->firstWhere('month', $mNum);
                                                      $dotClass = 'bg-paid'; // Default: Paid or No Bill
                                                      $title = $mName . ': Lunas';
                                                      
                                                      if($bill) {
                                                          if($bill->is_free) {
                                                              $dotClass = 'bg-free';
                                                              $title = $mName . ': FREE';
                                                          } elseif($bill->status == 'unpaid') {
                                                              $dotClass = 'bg-unpaid';
                                                              $title = $mName . ': Belum Bayar';
                                                          } elseif($bill->status == 'partial') {
                                                              $dotClass = 'bg-partial';
                                                              $title = $mName . ': Mencicil';
                                                          }
                                                      } else {
                                                          $title = $mName . ': Tidak Ada Tagihan';
                                                      }
                                                    @endphp
                                                    <div class="text-center" style="width: 35px;">
                                                        <div class="month-dot {{ $dotClass }}" title="{{ $title }}"></div>
                                                        <div class="x-small text-muted mt-1" style="font-size: 0.6rem;">{{ substr($mName, 0, 3) }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            @php $onlyBill = $bills->first(); @endphp
                                            @if($onlyBill)
                                                @if($onlyBill->is_free)
                                                    <span class="badge bg-info text-white rounded-pill px-3">FREE (BEBAS)</span>
                                                @elseif($onlyBill->status == 'paid')
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Lunas</span>
                                                @elseif($onlyBill->status == 'partial')
                                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">Cicilan: Rp {{ number_format($onlyBill->paid_amount, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Belum Bayar</span>
                                                @endif
                                            @else
                                                <span class="text-muted small italic">-</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($typeDebt > 0)
                                            <span class="fw-bold text-danger">Rp {{ number_format($typeDebt, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-success small fw-bold"><i class="bi bi-check-circle-fill me-1"></i> LUNAS</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="bi bi-funnel text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5 class="fw-bold text-muted">Silakan Pilih Unit atau Kelas</h5>
                                    <p class="text-muted">Gunakan filter di atas untuk menampilkan data rekap tunggakan siswa.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3 border-0 d-flex justify-content-center flex-wrap gap-4 no-print">
            <span class="small"><span class="month-dot bg-unpaid"></span> Belum Bayar</span>
            <span class="small"><span class="month-dot bg-partial"></span> Mencicil</span>
            <span class="small"><span class="month-dot bg-free"></span> <b>FREE</b> (Bebas Biaya)</span>
            <span class="small"><span class="month-dot bg-paid"></span> Lunas / Sesuai</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush
@endsection
