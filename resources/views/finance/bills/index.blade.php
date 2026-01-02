@extends('layouts.app')

@section('title', 'Rekap Tagihan Siswa')

@push('styles')
<style>
    .group:hover .group-hover-opacity-100 {
        opacity: 1 !important;
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
    .x-small { font-size: 0.75rem; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h3 class="fw-bold"><i class="bi bi-card-checklist me-2 text-primary"></i> Rekap Tagihan Siswa</h3>
            <p class="text-muted mb-0">Pantau status pembayaran (Lunas/Hutang) seluruh siswa secara detail.</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="dropdown">
                <button class="btn btn-white border shadow-sm dropdown-toggle fw-bold px-4 rounded-pill" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-2 text-primary"></i> EXPORT DATA
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('finance.bills.export.excel', request()->query()) }}">
                            <i class="bi bi-file-earmark-spreadsheet text-success me-2"></i> Export ke Excel (CSV)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('finance.bills.export.pdf', request()->query()) }}" target="_blank">
                            <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Export ke PDF (Cetak)
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body bg-light">
            <form action="{{ route('finance.bills.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">Tahun Pelajaran</label>
                    <select name="academic_year_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ $selectedYearId == $ay->id ? 'selected' : '' }}>
                                {{ $ay->start_year }}/{{ $ay->end_year }} 
                                {{ $ay->status == 'active' ? '(Aktif)' : '(Tidak Aktif)' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted">Unit</label>
                    <select name="unit_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Unit --</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ $selectedUnitId == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted">Kelas</label>
                    <select name="class_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">Jenis Biaya</label>
                    <select name="payment_type_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Biaya --</option>
                        @foreach($paymentTypes as $pt)
                            <option value="{{ $pt->id }}" {{ $selectedTypeId == $pt->id ? 'selected' : '' }}>{{ $pt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted">Status</label>
                    <div class="d-flex gap-2">
                        <select name="status" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                            <option value="">-- Semua --</option>
                            <option value="unpaid" {{ $status == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="partial" {{ $status == 'partial' ? 'selected' : '' }}>Mencicil</option>
                            <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>Lunas</option>
                        </select>
                        <button type="submit" class="btn btn-primary border-0 shadow-sm px-3">
                            <i class="bi bi-filter"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($isMatrix)
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-uppercase"><i class="bi bi-grid-3x3 me-2"></i> Matriks Pembayaran: {{ $paymentTypes->firstWhere('id', $selectedTypeId)->name ?? '' }}</h6>
                    <span class="badge bg-light text-dark border">{{ $classes->firstWhere('id', $selectedClassId)->name ?? '' }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0 text-center" style="font-size: 0.85rem;">
                        <thead class="bg-light text-muted small text-uppercase">
                            @php
                                $ay = $academicYears->firstWhere('id', $selectedYearId);
                                $startYear = $ay ? $ay->start_year : date('Y');
                                $endYear = $ay ? $ay->end_year : (date('Y') + 1);
                                
                                $semester1 = [7, 8, 9, 10, 11, 12];
                                $semester2 = [1, 2, 3, 4, 5, 6];
                                $monthsName = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'];
                            @endphp
                            <tr>
                                <th rowspan="3" class="ps-3 bg-white border-bottom-0 align-middle" width="40px">#</th>
                                <th rowspan="3" class="text-start ps-3 bg-white border-bottom-0 align-middle" style="min-width: 250px;">Identitas Siswa</th>
                                <th colspan="6" class="bg-primary bg-opacity-10 text-primary py-2 fw-bold" style="font-size: 0.75rem;">SEMESTER 1 (GANJIL)</th>
                                <th colspan="6" class="bg-info bg-opacity-10 text-info py-2 fw-bold" style="font-size: 0.75rem;">SEMESTER 2 (GENAP)</th>
                                <th rowspan="3" class="bg-light border-bottom-0 align-middle" width="120px">TOTAL TAGIHAN</th>
                                <th rowspan="3" class="bg-primary text-white border-bottom-0 align-middle" width="120px">TOTAL BAYAR</th>
                            </tr>
                            <tr>
                                @foreach($semester1 as $m)
                                    <th class="py-1 bg-white border-bottom-0 small fw-bold">{{ $monthsName[$m] }}</th>
                                @endforeach
                                @foreach($semester2 as $m)
                                    <th class="py-1 bg-white border-bottom-0 small fw-bold">{{ $monthsName[$m] }}</th>
                                @endforeach
                            </tr>
                            <tr class="text-muted" style="font-size: 0.65rem;">
                                @foreach($semester1 as $m)
                                    <th class="py-1 border-top-0">{{ $startYear }}</th>
                                @endforeach
                                @foreach($semester2 as $m)
                                    <th class="py-1 border-top-0">{{ $endYear }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $allMonths = array_merge($semester1, $semester2);
                            @endphp
                            @foreach($students as $idx => $s)
                                @php $sBills = $matrixBills->get($s->id, collect()); @endphp
                                <tr>
                                    <td class="ps-3 text-muted align-middle">{{ $idx + 1 }}</td>
                                    <td class="text-start ps-3 py-2">
                                        <div class="fw-bold text-dark text-uppercase" style="font-size: 0.9rem;">{{ $s->nama_lengkap }}</div>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="text-muted small"><i class="bi bi-person-badge me-1"></i>{{ $s->nis }}</span>
                                            <span class="badge bg-primary bg-opacity-10 text-primary border-0 x-small px-2" style="font-size: 0.7rem;">
                                                <i class="bi bi-door-open me-1"></i>{{ $s->classes->first()->name ?? '-' }}
                                            </span>
                                        </div>
                                        <div class="mt-2">
                                            <a href="{{ route('finance.payments.show', ['student' => $s->id, 'academic_year_id' => $selectedYearId]) }}" class="btn btn-link p-0 text-decoration-none small fw-bold" style="font-size: 0.7rem;">
                                                <i class="bi bi-clock-history me-1"></i> RIWAYAT INVOICE
                                            </a>
                                        </div>
                                    </td>
                                    @php 
                                        $rowPaidTotal = 0; 
                                        $rowBillTotal = 0;
                                    @endphp
                                    @foreach($allMonths as $m)
                                        @php 
                                            $bill = $sBills->where('month', $m)->first(); 
                                            if($bill) {
                                                $rowPaidTotal += $bill->paid_amount;
                                                $rowBillTotal += $bill->amount;
                                            }
                                            $status = $bill->status ?? 'none';
                                        @endphp
                                        <td class="p-0 border-start align-middle position-relative group" style="min-width: 85px;">
                                            @if($bill)
                                                @php 
                                                    $bgClass = '';
                                                    $textClass = '';
                                                    if($bill->status == 'paid') { $bgClass = 'bg-success'; $textClass = 'text-success'; }
                                                    elseif($bill->status == 'partial') { $bgClass = 'bg-warning'; $textClass = 'text-warning'; }
                                                    else { $bgClass = 'bg-danger'; $textClass = 'text-danger'; }

                                                    $trxKey = $s->id . '-' . $m;
                                                    $latestTrx = $matrixTransactions->get($trxKey)?->first();
                                                @endphp
                                                @php
                                                    $cellBorder = '';
                                                    if($bill->status == 'paid') $cellBorder = 'border-start border-4 border-success';
                                                    elseif($bill->status == 'partial') $cellBorder = 'border-start border-4 border-warning';
                                                    else $cellBorder = 'border-start border-4 border-danger';
                                                @endphp
                                                <div class="py-2 {{ $bgClass }} bg-opacity-25 {{ $cellBorder }} d-flex flex-column justify-content-center position-relative" style="min-height: 60px;">
                                                    @if($bill->is_free)
                                                        <span class="badge bg-info text-white mx-2 mb-1" style="font-size: 0.6rem;">FREE</span>
                                                    @else
                                                        <span class="fw-bold d-block text-dark" style="font-size: 0.95rem; line-height: 1.1;">
                                                            {{ number_format($bill->paid_amount, 0, ',', '.') }}
                                                        </span>
                                                        <div style="border-top: 1px solid rgba(0,0,0,0.15); margin: 4px 8px; height: 1px;"></div>
                                                        <span class="text-dark opacity-75 fw-bold" style="font-size: 0.75rem; line-height: 1;">
                                                            {{ number_format($bill->amount, 0, ',', '.') }}
                                                        </span>
                                                    @endif

                                                    @if($latestTrx)
                                                    <form action="{{ route('finance.payments.transactions.destroy', $latestTrx->id) }}" method="POST" 
                                                          class="position-absolute top-0 end-0 mt-1 me-1 opacity-0 group-hover-opacity-100 transition-all"
                                                          onsubmit="return confirm('VOID (Batalkan) pembayaran terakhir di bulan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger p-0 border-0" title="VOID Pembayaran Terakhir">
                                                            <i class="bi bi-x-circle-fill shadow-sm rounded-circle bg-white" style="font-size: 0.8rem;"></i>
                                                        </button>
                                                    </form>
                                                    @endif

                                                    {{-- DELETE BILL BUTTON --}}
                                                    <button type="button" class="btn btn-link text-danger p-0 border-0 position-absolute top-0 start-0 mt-1 ms-1 opacity-0 group-hover-opacity-100 transition-all btn-delete-bill" 
                                                            data-url="{{ route('finance.bills.destroy', $bill->id) }}"
                                                            title="Hapus Tagihan">
                                                        <i class="bi bi-trash-fill shadow-sm rounded-circle bg-white" style="font-size: 0.8rem;"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <div class="py-3 text-light small">-</div>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="fw-bold text-end pe-3 bg-light text-muted align-middle">
                                        Rp {{ number_format($rowBillTotal, 0, ',', '.') }}
                                    </td>
                                    <td class="fw-bold text-end pe-3 bg-primary text-white align-middle shadow-sm">
                                        Rp {{ number_format($rowPaidTotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3 border-0">
                <div class="d-flex gap-4 small justify-content-center">
                    <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Lunas</span>
                    <span class="text-warning"><i class="bi bi-dash-circle-fill me-1"></i> Mencicil</span>
                    <span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i> Belum Bayar</span>
                    <span class="text-muted"><i class="bi bi-dash me-1"></i> Tidak Ada Tagihan</span>
                </div>
            </div>
        </div>
    @else
        <form action="{{ route('finance.bills.bulk-destroy') }}" method="POST" id="bulkDeleteForm">
            @csrf
            @method('DELETE')
            <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
            <input type="hidden" name="payment_type_id" value="{{ $selectedTypeId }}">
            <input type="hidden" name="security_pin" id="bulk_security_pin">

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                @if(!$selectedTypeId || !$selectedClassId)
                    <div class="bg-primary bg-opacity-10 p-4 text-center border-bottom">
                        <div class="text-primary fw-bold mb-2"><i class="bi bi-info-circle-fill me-1"></i> TIPS NAVIGASI</div>
                        <p class="mb-0 text-dark">Pilih <b>Kelas</b> dan <b>Jenis Biaya Bulanan (seperti SPP)</b> untuk mengaktifkan tampilan Matriks (Rekap per Bulan).</p>
                    </div>
                @endif
                
                <div class="card-body p-0">
                    {{-- Bulk Actions Toolbar --}}
                    <div class="px-4 py-3 border-bottom d-none bg-light" id="bulkActions">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-square-fill text-primary me-2"></i>
                            <span class="fw-bold me-3 text-dark"><span id="selectedCount">0</span> Siswa Dipilih</span>
                            <button type="button" class="btn btn-danger btn-sm rounded-pill px-3" id="btnBulkDelete">
                                <i class="bi bi-trash-fill me-1"></i> Hapus Semua Tagihan Terpilih
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-primary text-white small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3" width="40">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll">
                                        </div>
                                    </th>
                                    <th class="py-3">No</th>
                                    <th class="py-3">Siswa</th>
                                    <th class="py-3">Kelas</th>
                                    <th class="py-3 text-end">Total Tagihan</th>
                                    <th class="py-3 text-end">Total Terbayar</th>
                                    <th class="py-3 text-end">Sisa Hutang</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="pe-4 py-3 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $idx => $student)
                                    @php
                                        $totalBill = $student->bills_sum_amount ?? 0;
                                        $totalPaid = $student->bills_sum_paid_amount ?? 0;
                                        $debt = $totalBill - $totalPaid;
                                        
                                        $statusLabel = 'LUNAS';
                                        $badgeClass = 'bg-success';
                                        
                                        if ($debt > 0 && $totalPaid > 0) {
                                            $statusLabel = 'MENCICIL';
                                            $badgeClass = 'bg-warning text-dark';
                                        } elseif ($debt > 0 && $totalPaid == 0) {
                                            $statusLabel = 'BELUM BAYAR';
                                            $badgeClass = 'bg-danger';
                                        } elseif ($totalBill == 0) {
                                            $statusLabel = '-';
                                            $badgeClass = 'bg-light text-muted border';
                                        }
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="form-check">
                                                <input class="form-check-input row-check" type="checkbox" name="student_ids[]" value="{{ $student->id }}">
                                            </div>
                                        </td>
                                        <td class="text-muted">{{ $students->firstItem() + $idx }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $student->nama_lengkap }}</div>
                                            <div class="small text-muted">{{ $student->nis }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $student->classes->first()->name ?? '-' }}</span>
                                        </td>
                                        <td class="text-end fw-bold">Rp {{ number_format($totalBill, 0, ',', '.') }}</td>
                                        <td class="text-end text-success">Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
                                        <td class="text-end text-danger fw-bold">
                                            Rp {{ number_format($debt, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            @php
                                                // Check if student has ANY free bills in this period
                                                $hasFree = \App\Models\StudentBill::where('student_id', $student->id)
                                                    ->where('academic_year_id', $selectedYearId)
                                                    ->where('is_free', true)
                                                    ->exists();
                                            @endphp
                                            <span class="badge rounded-pill {{ $badgeClass }} px-3 py-2">
                                                {{ $statusLabel }}
                                                @if($hasFree) <br><small class="opacity-75">(Inc. Free)</small> @endif
                                            </span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="{{ route('finance.payments.show', ['student' => $student->id, 'academic_year_id' => $selectedYearId]) }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">
                                                <i class="bi bi-eye me-1"></i> DETAIL / BAYAR
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted italic">
                                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                            Tidak ada data siswa / tagihan yang ditemukan untuk filter ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-3 border-0">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            </form>

            {{-- Security PIN Modal --}}
            <div class="modal fade" id="pinModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-danger">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center pt-2 px-4 pb-4">
                            <div class="mb-3">
                                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-shield-lock-fill fs-2"></i>
                                </div>
                            </div>
                            <p class="small text-muted mb-3">Tindakan ini permanen. Masukkan <strong>PIN Keamanan</strong> Anda untuk melanjutkan.</p>
                            
                            <form id="pinForm" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="mb-3">
                                    <input type="password" name="security_pin" id="inputPin" class="form-control text-center fw-bold fs-4 ls-2" maxlength="6" placeholder="******" required inputmode="numeric" autocomplete="off" style="letter-spacing: 5px;">
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-danger rounded-pill fw-bold">KONFIRMASI HAPUS</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const pinModalEl = document.getElementById('pinModal');
                    const pinModal = new bootstrap.Modal(pinModalEl);
                    const pinForm = document.getElementById('pinForm');
                    const inputPin = document.getElementById('inputPin');
                    
                    // Bulk Delete Logic
                    const checkAll = document.getElementById('checkAll');
                    const rowChecks = document.querySelectorAll('.row-check');
                    const bulkActions = document.getElementById('bulkActions');
                    const selectedCount = document.getElementById('selectedCount');
                    const btnBulkDelete = document.getElementById('btnBulkDelete');
                    const bulkDeleteForm = document.getElementById('bulkDeleteForm');
                    const bulkSecurityPin = document.getElementById('bulk_security_pin');

                    // Individual Delete Logic
                    document.querySelectorAll('.btn-delete-bill').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const url = this.getAttribute('data-url');
                            pinForm.action = url; // Set action to individual delete route
                            inputPin.value = '';
                            
                            // Restore default submit behavior (remove any override)
                            pinForm.onsubmit = null; 
                            
                            pinModal.show();
                        });
                    });
                    
                    pinModalEl.addEventListener('shown.bs.modal', function () {
                        inputPin.focus();
                    });

                    // Update Bulk UI
                    function updateBulkUI() {
                        const checkedCount = document.querySelectorAll('.row-check:checked').length;
                        if(selectedCount) selectedCount.textContent = checkedCount;
                        if(bulkActions) {
                            if (checkedCount > 0) {
                                bulkActions.classList.remove('d-none');
                            } else {
                                bulkActions.classList.add('d-none');
                            }
                        }
                    }

                    if(checkAll) {
                        checkAll.addEventListener('change', function() {
                            rowChecks.forEach(cb => cb.checked = checkAll.checked);
                            updateBulkUI();
                        });
                    }

                    if(rowChecks) {
                        rowChecks.forEach(cb => {
                            cb.addEventListener('change', updateBulkUI);
                        });
                    }

                    if(btnBulkDelete) {
                        btnBulkDelete.addEventListener('click', function() {
                            // Show PIN Modal but override submit behavior
                            inputPin.value = '';
                            pinForm.action = ''; // No direct action needed, we handle via JS
                            
                            // Override submit
                            pinForm.onsubmit = function(e) {
                                e.preventDefault();
                                if(!inputPin.value) return;
                                
                                // Copy PIN to bulk form
                                bulkSecurityPin.value = inputPin.value;
                                
                                // Submit bulk form
                                bulkDeleteForm.submit();
                            };
                            
                            pinModal.show();
                        });
                    }
                });
            </script>
    @endif
</div>
@endsection
