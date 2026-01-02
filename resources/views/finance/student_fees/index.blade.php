@extends('layouts.app')

@section('title', 'Atur Pembayaran Siswa')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h3><i class="bi bi-person-lines-fill"></i> Atur Pembayaran Siswa</h3>
            <p class="text-muted">Tetapkan besaran tagihan (SPP/Gedung) untuk spesifik kelas atau siswa tertentu.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light rounded-3">
            <form action="{{ route('finance.student-fees.index') }}" method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                <input type="hidden" name="payment_type_id" value="{{ $selectedTypeId }}">
                <input type="hidden" name="group" value="{{ request('group') }}">
                
                <div class="col-md-6">
                    <label class="fw-bold small text-muted">1. Tahun Pelajaran</label>
                    <select name="academic_year_id" class="form-select border-0 shadow-sm" onchange="document.querySelector('[name=class_id]').value=''; document.querySelector('[name=payment_type_id]').value=''; this.form.submit()">
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ $selectedYearId == $ay->id ? 'selected' : '' }} class="{{ $ay->status == 'active' ? 'fw-bold text-primary' : 'text-muted' }}">
                                {{ $ay->start_year }}/{{ $ay->end_year }} {{ $ay->status == 'active' ? '(Aktif)' : '(Tidak Aktif)' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold small text-muted">2. Unit Sekolah</label>
                    <select name="unit_id" class="form-select border-0 shadow-sm" onchange="document.querySelector('[name=class_id]').value=''; document.querySelector('[name=payment_type_id]').value=''; document.querySelector('[name=group]').value=''; this.form.submit()">
                        <option value="all" {{ $selectedUnitId == 'all' ? 'selected' : '' }}>-- Semua Unit --</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ $selectedUnitId == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if($students->isNotEmpty() && $selectedTypeId)
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm" onclick="goBackToClasses()">
                <i class="bi bi-arrow-left me-2"></i> KEMBALI KE KELAS
            </button>
            <div class="text-end">
                <h4 class="fw-bold mb-0 text-dark">{{ $selectedPaymentTypeObj->name }}</h4>
                <p class="text-muted small mb-0">
                    @if(request('group') == 'boarding')
                        Seluruh Anak Asrama
                    @else
                        {{ $classes->firstWhere('id', $selectedClassId)->name ?? 'Kelas' }}
                    @endif
                    - {{ $academicYears->firstWhere('id', $selectedYearId)->start_year }}/{{ $academicYears->firstWhere('id', $selectedYearId)->end_year }}
                </p>
            </div>
        </div>

        <script>
            function goBackToClasses() {
                const url = new URL(window.location.href);
                url.searchParams.set('class_id', '');
                url.searchParams.set('payment_type_id', '');
                url.searchParams.set('group', '');
                window.location.href = url.toString();
            }
        </script>

        <form action="{{ route('finance.payments.settings.store_class_fees') }}" method="POST" id="feeForm">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
            <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
            <input type="hidden" name="payment_type_id" value="{{ $selectedTypeId }}">
            <input type="hidden" name="group" value="{{ request('group') }}">
            <input type="hidden" name="unit_id" value="{{ $selectedUnitId }}">
            
            <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap me-2"></i> Pengaturan Tarif Detail</h6>
                             <div class="small text-muted mt-1">
                                 <span class="badge bg-light text-dark border me-1">{{ $units->firstWhere('id', $selectedUnitId)->name ?? 'Semua Unit' }}</span>
                                <span class="badge bg-light text-dark border me-1">{{ $academicYears->firstWhere('id', $selectedYearId)->start_year }}/{{ $academicYears->firstWhere('id', $selectedYearId)->end_year }}</span>
                                <span class="badge bg-light text-dark border me-1">
                                    @if(request('group') == 'boarding')
                                        Grup: Anak Asrama
                                    @else
                                        Kelas {{ $classes->firstWhere('id', $selectedClassId)->name ?? '' }}
                                    @endif
                                </span>
                                <span class="badge bg-primary bg-opacity-10 text-primary border-primary border-opacity-25">{{ $selectedPaymentTypeObj->name }}</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-success px-4 fw-bold rounded-pill shadow-sm me-2" onclick="exportToExcel()">
                                <i class="bi bi-file-earmark-excel me-2"></i> EXPORT EXCEL
                            </button>
                            <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm">
                                <i class="bi bi-cloud-arrow-up me-2"></i> SIMPAN SEMUA
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bulk Action Tool -->
                <div class="card-body bg-light border-bottom py-3 px-4">
                    <div class="row align-items-center g-3">
                        <div class="col-auto">
                            <span class="fw-bold small text-muted text-uppercase ls-1 italic">Bulk Fill :</span>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group input-group-sm bg-white shadow-sm rounded">
                                <span class="input-group-text bg-transparent border-0 pe-0">Rp</span>
                                <input type="text" id="bulkValueMask" class="form-control border-0 currency-input" placeholder="0" onkeyup="formatInput(this)">
                                <input type="hidden" id="bulkValue">
                                <button type="button" class="btn btn-dark fw-bold border-0" onclick="applyToAll()">
                                    Terapkan Ke Semua
                                </button>
                            </div>
                        </div>
                        <div class="col text-end">
                            <div class="text-muted small">
                                <i class="bi bi-info-circle me-1"></i> Klik <i class="bi bi-arrow-down-square-fill text-primary"></i> (isi kolom) atau <i class="bi bi-arrow-right-square-fill text-success"></i> (isi baris). 
                                <span class="badge bg-info ms-2">FREE</span> = Bebas Biaya (Lunas otomatis)
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0 align-middle text-center table-fee" style="font-size: 0.85rem;">
                            <thead class="table-light align-middle text-uppercase small ls-1">
                                <tr>
                                    <th width="40px" rowspan="2" class="bg-white border-bottom-0">#</th>
                                    <th rowspan="2" class="text-start ps-4 bg-white border-bottom-0" style="min-width: 200px;">Nama Siswa</th>
                                    @if($selectedPaymentTypeObj->type == 'monthly')
                                        @php
                                            $ay = $academicYears->firstWhere('id', $selectedYearId);
                                            $startY = $ay->start_year ?? '';
                                            $endY = $ay->end_year ?? '';
                                            $monthsOrder = [7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6];
                                            $monthsName = [7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des', 1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun'];
                                        @endphp
                                        @foreach($monthsOrder as $mNum)
                                            <th class="py-2 border-bottom-0">
                                                {{ $monthsName[$mNum] }}<br>
                                                <small class="text-muted opacity-75" style="letter-spacing: 0;">{{ $mNum >= 7 ? $startY : $endY }}</small>
                                            </th>
                                        @endforeach
                                        <th rowspan="2" class="bg-light border-bottom-0 fw-bold text-dark" style="min-width: 120px;">TOTAL</th>
                                        <th rowspan="2" class="bg-white border-bottom-0" width="40px">Action</th>
                                    @else
                                        <th class="bg-white border-bottom-0">Jadwal & Nominal Tagihan</th>
                                        <th rowspan="2" class="bg-white border-bottom-0">Status Free</th>
                                    @endif
                                </tr>
                                @if($selectedPaymentTypeObj->type != 'monthly')
                                <tr>
                                    <th class="bg-white border-top-0">
                                        <div class="d-flex justify-content-center gap-3">
                                            <div class="input-group input-group-sm" style="width: auto;">
                                                <span class="input-group-text bg-white border-0 text-muted small fw-bold">Penagihan:</span>
                                                <select name="billing_month" class="form-select border-0 bg-light shadow-none rounded-start" style="width: 100px;">
                                                    @foreach([7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6] as $m)
                                                        <option value="{{ $m }}" {{ (isset($students[0]) && ($currentSettings->get($students[0]->id, collect())->first()->month ?? 7) == $m) ? 'selected' : '' }}>
                                                            {{ [7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember', 1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni'][$m] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group input-group-sm" style="width: auto;">
                                                <span class="input-group-text bg-white border-0 text-muted small fw-bold">Batas Akhir:</span>
                                                <select name="due_month" class="form-select border-0 bg-light shadow-none rounded-start" style="width: 100px;">
                                                    @foreach([7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6] as $m)
                                                        <option value="{{ $m }}" {{ (isset($students[0]) && ($currentSettings->get($students[0]->id, collect())->first()->due_month ?? 7) == $m) ? 'selected' : '' }}>
                                                            {{ [7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember', 1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni'][$m] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                @endif
                                @if($selectedPaymentTypeObj->type == 'monthly')
                                <tr>
                                    @foreach($monthsOrder as $mNum)
                                        <th class="p-1 bg-white border-top-0">
                                            <button type="button" class="btn btn-link btn-sm p-0 text-primary opacity-75 hover-opacity-100" title="Isi kolom ini kebawah" onclick="applyToColumn({{ $mNum }})">
                                                <i class="bi bi-arrow-down-square-fill fs-5"></i>
                                            </button>
                                        </th>
                                    @endforeach
                                </tr>
                                @endif
                            </thead>
                            <tbody>
                                @foreach($students as $index => $s)
                                    @php
                                        $sSettings = $currentSettings->get($s->id, collect());
                                    @endphp
                                    <tr data-student-id="{{ $s->id }}">
                                        <td class="text-muted small">{{ $index + 1 }}</td>
                                        <td class="text-start ps-4">
                                            <div class="fw-bold text-dark mb-0">{{ $s->nama_lengkap }}</div>
                                            <span class="text-muted tiny-label">{{ $s->nis }}</span>
                                        </td>
                                        
                                        @if($selectedPaymentTypeObj->type == 'monthly')
                                            @foreach($monthsOrder as $mNum)
                                                @php
                                                    $setting = $sSettings->where('month', $mNum)->first();
                                                    $val = $setting->amount ?? 0;
                                                    $isFree = $setting->is_free ?? false;
                                                @endphp
                                                <td class="p-0 border-end {{ $isFree ? 'bg-info bg-opacity-10' : '' }} cell-fee" data-student="{{ $s->id }}" data-month="{{ $mNum }}">
                                                    <input type="text" 
                                                           name="fees_mask[{{ $s->id }}][{{ $mNum }}]" 
                                                           data-month="{{ $mNum }}"
                                                           class="form-control form-control-sm fee-input text-center rounded-0 border-0 pt-2 pb-0 currency-input fw-bold" 
                                                           value="{{ number_format($val, 0, ',', '.') }}" 
                                                           onkeyup="formatInput(this); calculateRow({{ $s->id }})"
                                                           onfocus="this.select()"
                                                           {{ $isFree ? 'readonly' : '' }}>
                                                    <input type="hidden" name="fees[{{ $s->id }}][{{ $mNum }}]" value="{{ (int)$val }}">
                                                    
                                                    <div class="py-1">
                                                        <div class="form-check form-check-inline m-0">
                                                            <input class="form-check-input free-check" type="checkbox" name="frees[{{ $s->id }}][{{ $mNum }}]" value="1" {{ $isFree ? 'checked' : '' }} onchange="toggleFree(this, {{ $s->id }}, {{ $mNum }})">
                                                            <label class="form-check-label x-small text-muted" style="font-size: 0.65rem;">FREE</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endforeach
                                            <td class="bg-light fw-bold text-end pe-3 total-cell" id="total_{{ $s->id }}">
                                                Rp 0
                                            </td>
                                            <td class="p-0 bg-white">
                                                <div class="d-flex flex-column gap-1 p-1">
                                                    <button type="button" class="btn btn-link btn-sm p-0 text-success opacity-75 hover-opacity-100" title="Samakan nominal bulan lainnya (copy dari Juli)" onclick="applyToRow({{ $s->id }})">
                                                        <i class="bi bi-arrow-right-square-fill fs-5"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-link btn-sm p-0 text-info opacity-75 hover-opacity-100" title="Bebaskan Semua Bulan Sswaini" onclick="freeAllMonths({{ $s->id }})">
                                                        <i class="bi bi-check-square-fill fs-5"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        @else
                                            @php
                                                $setting = $sSettings->first();
                                                $val = $setting->amount ?? 0;
                                                $isFree = $setting->is_free ?? false;
                                            @endphp
                                            <td class="p-3 {{ $isFree ? 'bg-info bg-opacity-10' : '' }}">
                                                <div class="input-group input-group-sm mx-auto shadow-none" style="max-width: 250px;">
                                                    <span class="input-group-text border-0 bg-white">Rp</span>
                                                    <input type="text" 
                                                           name="fees_mask[{{ $s->id }}]" 
                                                           class="form-control fw-bold border-0 bg-light rounded-end py-2 px-3 shadow-none text-end fee-input currency-input" 
                                                           value="{{ number_format($val, 0, ',', '.') }}"
                                                           onkeyup="formatInput(this); calculateTotals();"
                                                            {{ $isFree ? 'readonly' : '' }}>
                                                    <input type="hidden" name="fees[{{ $s->id }}]" value="{{ (int)$val }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input free-check" type="checkbox" name="frees[{{ $s->id }}]" value="1" {{ $isFree ? 'checked' : '' }} onchange="toggleFree(this, {{ $s->id }}, 0)">
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light fw-bold border-top-2">
                                <tr>
                                    <td colspan="2" class="text-end py-3 ps-4">TOTAL PER BULAN</td>
                                    @if($selectedPaymentTypeObj->type == 'monthly')
                                        @foreach($monthsOrder as $mNum)
                                            <td class="text-center text-primary" id="col_total_{{ $mNum }}">Rp 0</td>
                                        @endforeach
                                        <td class="text-end pe-3 text-success fs-6" id="grand_total_all">Rp 0</td>
                                        <td class="bg-white"></td>
                                    @else
                                        <td class="text-end pe-3 text-success fs-5" id="one_time_total">Rp 0</td>
                                        <td class="bg-white"></td>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                     <button type="submit" class="btn btn-primary fw-bold w-100 py-3 shadow-sm rounded-3">
                        <i class="bi bi-save me-2"></i> SIMPAN SEMUA PERUBAHAN
                    </button>
                </div>
            </div>
        </form>

        <style>
            .table-fee .fee-input:focus {
                background-color: #f8faff;
                box-shadow: inset 0 0 0 2px #0d6efd !important;
                z-index: 2;
                position: relative;
            }
            .ls-1 { letter-spacing: 1px; }
            .italic { font-style: italic; }
            .tiny-label { font-size: 0.7rem; font-weight: 500; }
            .hover-opacity-100:hover { opacity: 1 !important; transform: scale(1.1); transition: 0.2s; }
            .table-fee thead th { border-top: none; }
            .ps-4 { padding-left: 1.5rem !important; }
            .rounded-4 { border-radius: 1rem !important; }
            .fee-input { font-weight: 600; color: #0d6efd; }
            .disc-input { font-weight: 600; color: #dc3545; }
            .currency-input { outline: none; }
            .total-cell { color: #198754; font-size: 0.9rem; }
            tfoot td { border-top: 2px solid #dee2e6 !important; }
            .x-small { font-size: 0.75rem; }
            .bg-info-subtle { background-color: #e0f2fe !important; }
        </style>

        <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
        <script>
            function formatNumber(n) {
                return n.toString().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function formatInput(id) {
                let val = id.value.replace(/\D/g, "");
                id.value = formatNumber(val);
                
                // Sync with hidden input
                const hiddenInput = id.nextElementSibling;
                if (hiddenInput && hiddenInput.type === 'hidden') {
                    hiddenInput.value = val;
                }
            }

            function syncHidden(input) {
                const hiddenInput = input.nextElementSibling;
                if (hiddenInput && hiddenInput.type === 'hidden') {
                    hiddenInput.value = input.value.replace(/\D/g, "");
                }
            }

            function calculateRow(studentId) {
                const row = document.querySelector(`tr[data-student-id="${studentId}"]`);
                if (!row) return;

                let grandTotal = 0;
                // Sum only the hidden inputs that are part of the monthly fees for this row
                row.querySelectorAll('input[name^="fees[' + studentId + ']["][type="hidden"]').forEach(hidden => {
                    grandTotal += parseInt(hidden.value || 0);
                });

                const totalDisplay = document.getElementById(`total_${studentId}`);
                if (totalDisplay) {
                    totalDisplay.innerText = 'Rp ' + formatNumber(grandTotal);
                }
            }

            function calculateTotals() {
                // Determine mode
                const isMonthly = !!document.getElementById('col_total_7');
                let grandTotalAll = 0;

                if (isMonthly) {
                    const months = [7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6];
                    months.forEach(m => {
                        let colSum = 0;
                        document.querySelectorAll(`input[name$="[${m}]"][type="hidden"]`).forEach(input => {
                            colSum += parseInt(input.value || 0);
                        });
                        const colTotalElement = document.getElementById(`col_total_${m}`);
                        if (colTotalElement) {
                            colTotalElement.innerText = 'Rp ' + formatNumber(colSum);
                        }
                        grandTotalAll += colSum;
                    });
                    const grandTotalAllElement = document.getElementById('grand_total_all');
                    if (grandTotalAllElement) {
                        grandTotalAllElement.innerText = 'Rp ' + formatNumber(grandTotalAll);
                    }
                } else {
                    document.querySelectorAll('input[name^="fees["][type="hidden"]').forEach(input => {
                        grandTotalAll += parseInt(input.value || 0);
                    });
                    const oneTimeTotal = document.getElementById('one_time_total');
                    if (oneTimeTotal) oneTimeTotal.innerText = 'Rp ' + formatNumber(grandTotalAll);
                }

                // Calculate Each Row (already handled by keyup, but needed for initial/bulk)
                document.querySelectorAll('tr[data-student-id]').forEach(row => {
                    calculateRow(row.getAttribute('data-student-id'));
                });
            }

            function applyToAll() {
                const bulkMask = document.getElementById('bulkValueMask');
                const val = bulkMask.value.replace(/\D/g, "");
                if (val === '') return alert('Masukkan nominal terlebih dahulu');
                
                const formatted = formatNumber(val);
                document.querySelectorAll('.fee-input').forEach(input => {
                    // Skip if free
                    const parent = input.closest('td');
                    if (parent && parent.querySelector('.free-check:checked')) return;

                    input.value = formatted;
                    syncHidden(input);
                });
                calculateTotals();
            }

            function applyToColumn(month) {
                const bulkMask = document.getElementById('bulkValueMask');
                const val = bulkMask.value.replace(/\D/g, "");
                if (val === '') return alert('Masukkan nominal di Bulk Fill dahulu');
                
                const formatted = formatNumber(val);
                document.querySelectorAll(`.fee-input[data-month="${month}"]`).forEach(input => {
                    // Skip if free
                    const parent = input.closest('td');
                    if (parent && parent.querySelector('.free-check:checked')) return;

                    input.value = formatted;
                    syncHidden(input);
                });
                calculateTotals();
            }

            function applyToRow(studentId) {
                const row = document.querySelector(`tr[data-student-id="${studentId}"]`);
                const firstMonthInput = row.querySelector('.fee-input[data-month="7"]');
                if (!firstMonthInput) return; // Should not happen for monthly type

                const formatted = firstMonthInput.value;
                
                row.querySelectorAll('.fee-input').forEach(input => {
                    // Skip if free
                    const parent = input.closest('td');
                    if (parent && parent.querySelector('.free-check:checked')) return;

                    input.value = formatted;
                    syncHidden(input);
                });
                calculateTotals();
            }

            function toggleFree(checkbox, studentId, month) {
                const isMonthly = !!document.getElementById('col_total_7');
                const row = document.querySelector(`tr[data-student-id="${studentId}"]`);
                let inputMask, inputHidden, cell;

                if (isMonthly) {
                    inputMask = row.querySelector(`input[name="fees_mask[${studentId}][${month}]"]`);
                    inputHidden = row.querySelector(`input[name="fees[${studentId}][${month}]"]`);
                    cell = inputMask.closest('td');
                } else {
                    inputMask = row.querySelector(`input[name="fees_mask[${studentId}]"]`);
                    inputHidden = row.querySelector(`input[name="fees[${studentId}]"]`);
                    cell = inputMask.closest('td');
                }

                if (checkbox.checked) {
                    inputMask.value = "0";
                    inputHidden.value = "0";
                    inputMask.readOnly = true;
                    cell.classList.add('bg-info', 'bg-opacity-10');
                } else {
                    inputMask.readOnly = false;
                    cell.classList.remove('bg-info', 'bg-opacity-10');
                }
                calculateTotals();
            }

            function freeAllMonths(studentId) {
                const row = document.querySelector(`tr[data-student-id="${studentId}"]`);
                row.querySelectorAll('.free-check').forEach(cb => {
                    cb.checked = true;
                    cb.dispatchEvent(new Event('change'));
                });
            }

            function exportToExcel() {
                const isMonthly = !!document.getElementById('col_total_7');
                const data = [];
                const rows = document.querySelectorAll('tr[data-student-id]');
                
                // 1. Headers
                const headers = ["#", "Nama Siswa", "NIS"];
                if (isMonthly) {
                    const months = ["Jul", "Agu", "Sep", "Okt", "Nov", "Des", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun"];
                    headers.push(...months, "TOTAL");
                } else {
                    headers.push("Bln Penagihan", "Bln Jatuh Tempo", "Nominal Tagihan", "Status FREE");
                }
                data.push(headers);

                // 2. Student Rows
                rows.forEach((row, index) => {
                    const name = row.querySelector('.fw-bold.text-dark').innerText;
                    const nis = row.querySelector('.tiny-label').innerText;
                    const studentData = [index + 1, name, nis];

                    if (isMonthly) {
                        const months = [7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6];
                        let rowSum = 0;
                        months.forEach(m => {
                            const isFree = row.querySelector(`input[name="frees[${row.dataset.studentId}][${m}]"]`).checked;
                            const val = isFree ? "FREE" : parseInt(row.querySelector(`input[name="fees[${row.dataset.studentId}][${m}]"]`).value || 0);
                            studentData.push(val);
                            if (!isFree) rowSum += parseInt(val || 0);
                        });
                        studentData.push(rowSum);
                    } else {
                        const billingMonth = document.querySelector('select[name="billing_month"]').options[document.querySelector('select[name="billing_month"]').selectedIndex].text;
                        const dueMonth = document.querySelector('select[name="due_month"]').options[document.querySelector('select[name="due_month"]').selectedIndex].text;
                        const val = parseInt(row.querySelector('input[type="hidden"]').value || 0);
                        const isFree = row.querySelector('.free-check').checked ? "FREE" : "NORMAL";
                        studentData.push(billingMonth, dueMonth, val, isFree);
                    }
                    data.push(studentData);
                });

                // 3. Create Workbook
                const ws = XLSX.utils.aoa_to_sheet(data);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "Tarif Siswa");

                // 4. Filename
                const className = "{{ $classes->firstWhere('id', $selectedClassId)->name ?? 'Kelas' }}";
                const typeName = "{{ $selectedPaymentTypeObj->name ?? 'Tarif' }}";
                XLSX.writeFile(wb, `Tarif_${typeName}_${className}.xlsx`);
            }

            // Initial calculation
            document.addEventListener('DOMContentLoaded', function() {
                // Pre-fill total labels
                document.querySelectorAll('tr[data-student-id]').forEach(row => {
                    const studentId = row.getAttribute('data-student-id');
                    let rowSum = 0;
                    row.querySelectorAll('input[type="hidden"]').forEach(input => {
                        rowSum += parseInt(input.value || 0);
                    });
                    const totalDisplay = document.getElementById(`total_${studentId}`);
                    if (totalDisplay) totalDisplay.innerText = 'Rp ' + formatNumber(rowSum);
                });
                calculateTotals();
            });
        </script>
    @elseif((request('group') == 'boarding' || $selectedClassId) && !$selectedTypeId)
        <div class="mb-4">
            <button type="button" class="btn btn-link text-decoration-none text-muted p-0" onclick="goBackToClasses()">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Kelas
            </button>
        </div>

        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark mb-2">
                Pilih Jenis Pembayaran 
                @if(request('group') == 'boarding')
                    (Untuk Anak Asrama)
                @endif
            </h2>
            <p class="text-muted">Silakan tentukan jenis tagihan yang ingin diatur untuk <b>{{ request('group') == 'boarding' ? 'Anak Asrama' : ($classes->firstWhere('id', $selectedClassId)->name ?? '') }}</b></p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($paymentTypes as $pt)
                @php $isSet = isset($setPaymentTypeIds) && $setPaymentTypeIds->contains($pt->id); @endphp
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm border-0 payment-type-card {{ $isSet ? 'border-success-subtle' : '' }}" 
                         onclick="selectPaymentType({{ $pt->id }})" 
                         style="cursor: pointer; transition: 0.3s; border-radius: 20px;">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 70px; height: 70px;">
                                    <i class="bi bi-wallet2 fs-2"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-1">{{ $pt->name }}</h5>
                            <p class="text-muted small mb-3">{{ $pt->code ?? 'Tanpa Kode' }}</p>
                            
                            @if($isSet)
                                <div class="badge bg-success-subtle text-success border border-success border-opacity-25 rounded-pill px-3 py-2">
                                    <i class="bi bi-check-circle-fill me-1"></i> Sudah Diatur
                                </div>
                            @else
                                <div class="badge bg-light text-muted border rounded-pill px-3 py-2">
                                    Belum Diatur
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <style>
            .payment-type-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 1.5rem 4rem rgba(0,0,0,.15) !important;
                background-color: #f0f7ff;
            }
            .payment-type-card:hover h5 { color: #0d6efd; }
            .payment-type-card:hover .bg-primary { background-color: #0d6efd !important; color: white !important; }
        </style>

        <script>
            function selectPaymentType(id) {
                const inputPT = document.querySelector('input[name="payment_type_id"]');
                inputPT.value = id;
                // Ensure group parameter is also in the submitted form if it exists in URL
                inputPT.closest('form').submit();
            }
            function goBackToClasses() {
                const url = new URL(window.location.href);
                url.searchParams.set('class_id', '');
                url.searchParams.set('payment_type_id', '');
                window.location.href = url.toString();
            }
        </script>
    @elseif((request('group') == 'boarding' || $selectedClassId) && $students->isEmpty())
        <div class="alert alert-warning text-center rounded-4 py-5 shadow-sm">
            <i class="bi bi-exclamation-triangle fs-1 d-block mb-3 opacity-25"></i>
            <h5 class="fw-bold">Tidak Ada Siswa</h5>
            <p class="text-muted">
                @if(request('group') == 'boarding')
                    Tidak ditemukan data siswa yang berstatus <b>Mukim (Asrama)</b> untuk unit dan tahun pelajaran ini.
                @else
                    Tidak ditemukan data siswa aktif di kelas ini untuk tahun ajaran yang dipilih.
                @endif
            </p>
        </div>
    @elseif($selectedYearId && !$selectedClassId && request('group') != 'boarding')
        <div class="row g-4">
            <div class="col-12">
                <div class="d-flex align-items-center mb-3">
                    <hr class="flex-grow-1">
                    <span class="mx-3 fw-bold text-muted text-uppercase small ls-1">GRUP KHUSUS (CROSS-CLASS)</span>
                    <hr class="flex-grow-1">
                </div>
            </div>

            <!-- Group Boarding Card -->
            <div class="col-md-3">
                <div class="card h-100 shadow-sm border-0 group-card overflow-hidden" onclick="selectGroup('boarding')" style="cursor: pointer; transition: 0.3s; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);">
                    <div class="card-body text-center p-4">
                        <div class="group-icon mb-3">
                            <div class="bg-info bg-opacity-10 text-info rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 70px; height: 70px;">
                                <i class="bi bi-house-door-fill fs-2"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-1">Anak Asrama</h5>
                        <p class="text-muted small mb-0">Kelola Tagihan Khusus Asrama Secara Global</p>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4">
                <div class="d-flex align-items-center mb-3">
                    <hr class="flex-grow-1">
                    <span class="mx-3 fw-bold text-muted text-uppercase small ls-1">BERDASARKAN KELAS</span>
                    <hr class="flex-grow-1">
                </div>
            </div>
            @forelse($classes as $c)
                @php 
                    $configs = $classPaymentConfigs->get($c->id, collect()); 
                    $unitTotal = $unitPaymentTypeCounts[$c->unit_id] ?? 0;
                    $isFullyConfigured = $configs->count() >= $unitTotal && $unitTotal > 0;
                @endphp
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm border-0 class-card overflow-hidden" onclick="selectClass({{ $c->id }})" style="cursor: pointer; transition: 0.3s; position: relative;">
                        @if($isFullyConfigured)
                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge bg-success shadow-sm rounded-circle p-1"><i class="bi bi-check-lg"></i></span>
                            </div>
                        @endif
                        <div class="card-body text-center p-4">
                            <div class="class-icon mb-3">
                                <i class="bi bi-door-open fs-1 text-primary opacity-50"></i>
                            </div>
                            <h5 class="fw-bold mb-1 text-truncate">{{ $c->name }}</h5>
                            <p class="text-muted small mb-3"><i class="bi bi-people me-1"></i> {{ $c->students_count ?? 0 }} Siswa</p>
                            
                            <div class="border-top pt-3">
                                <div class="small text-muted mb-2 text-uppercase fw-bold ls-1" style="font-size: 0.65rem;">Konfigurasi Tarif:</div>
                                @if($configs->isNotEmpty())
                                    <div class="d-flex flex-wrap justify-content-center gap-1">
                                        @foreach($configs->take(3) as $conf)
                                            @php $pName = $paymentTypes->firstWhere('id', $conf->payment_type_id)->name ?? 'Unknown'; @endphp
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1" style="font-size: 0.6rem;">
                                                {{ $pName }}
                                            </span>
                                        @endforeach
                                        @if($configs->count() > 3)
                                            <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 0.6rem;">+{{ $configs->count() - 3 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted italic small opacity-50" style="font-size: 0.7rem;">Belum ada tarif diatur</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
@empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-folder2-open fs-1 text-muted opacity-25"></i>
                    <p class="text-muted mt-2">Tidak ada kelas yang ditemukan untuk unit dan tahun pelajaran ini.</p>
                </div>
            @endforelse
        </div>

        <style>
            .class-card:hover { 
                transform: translateY(-8px); 
                box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important; 
                background-color: #f8fbff;
            }
            .class-card:hover h5 { color: #0d6efd; }
            .class-card:hover .class-icon i { opacity: 1; transform: scale(1.1); transition: 0.3s; }
        </style>

        <script>
            function selectClass(id) {
                const input = document.querySelector('input[name="class_id"]');
                input.value = id;
                document.querySelector('input[name="group"]').value = '';
                input.closest('form').submit();
            }
            function selectGroup(group) {
                const inputG = document.querySelector('input[name="group"]');
                inputG.value = group;
                document.querySelector('input[name="class_id"]').value = '';
                inputG.closest('form').submit();
            }
        </script>
    @else
        <div class="alert alert-light border shadow-sm text-center py-5 rounded-4">
            <div class="mb-3"><i class="bi bi-calendar-check fs-1 text-muted opacity-25"></i></div>
            <p class="text-muted mb-0">Silakan pilih <b>Tahun Pelajaran</b> di atas <br> untuk mulai menampilkan daftar kelas.</p>
        </div>
    @endif
</div>
@endsection
