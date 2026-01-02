@extends('layouts.app')

@section('title', 'POS Pembayaran Siswa - ' . $student->nama_lengkap)

@section('content')
<div class="container-fluid py-3">
    <!-- POS Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold mb-0 text-dark">
                <a href="{{ route('finance.payments.index') }}" class="btn btn-light btn-sm rounded-pill me-3 shadow-sm border">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <i class="bi bi-calculator text-primary me-2"></i> POS PEMBAYARAN SISWA
            </h4>
        </div>
        <div class="col-md-6 text-end">
            <!-- Space for global actions -->
        </div>
    </div>

    @if(session('success') || session('error') || $errors->any())
        <div class="row justify-content-center mb-4">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 h5 mb-0"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2 h5 mb-0"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-warning border-0 shadow-sm rounded-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-info-circle-fill me-2 h5 mb-0"></i>
                            <strong class="small">Periksa Input!</strong>
                        </div>
                        <ul class="mb-0 small ps-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <form action="{{ route('finance.payments.store', $student->id) }}" method="POST" id="paymentForm">
        @csrf
        
        @php
            $monthlyBillsGrouped = $paymentBills->where('paymentType.type', 'monthly')->groupBy('payment_type_id');
            $annualBills = $paymentBills->where('paymentType.type', 'one_time');
            $monthsName = [7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember', 1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 13 => 'Lainnya'];
            $monthsOrder = [7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6];

            // WhatsApp Message Template Logic
            $waPhone = preg_replace('/[^0-9]/', '', $student->no_hp_wali);
            if (!empty($waPhone) && $waPhone[0] === '0') {
                $waPhone = '62' . substr($waPhone, 1);
            }
            
            $totalRemaining = $stats['total_arrears'] + $stats['total_current_bills'];
            
            $currentClass = $student->classes->where('pivot.academic_year_id', $academicYear->id)->first();
            $className = $currentClass ? $currentClass->name : '-';

            // Itemize current year bills for the formal template
            $itemized = [
                'PONDOK' => 0,
                'KEGIATAN' => 0,
                'PERALATAN' => 0,
                'BUKU' => 0,
                'LAINNYA' => 0,
            ];

            foreach ($paymentBills as $bill) {
                $name = strtoupper($bill->paymentType->name);
                $remaining = $bill->amount - $bill->paid_amount;
                if ($remaining <= 0) continue;

                if (str_contains($name, 'PONDOK') || str_contains($name, 'SPP') || str_contains($name, 'BULANAN')) {
                    $itemized['PONDOK'] += $remaining;
                } elseif (str_contains($name, 'KEGIATAN')) {
                    $itemized['KEGIATAN'] += $remaining;
                } elseif (str_contains($name, 'PERALATAN') || str_contains($name, 'SERAGAM')) {
                    $itemized['PERALATAN'] += $remaining;
                } elseif (str_contains($name, 'BUKU') || str_contains($name, 'KITAB')) {
                    $itemized['BUKU'] += $remaining;
                } else {
                    $itemized['LAINNYA'] += $remaining;
                }
            }

            // Include Arrears in PONDOK or separate if needed? 
            // The template seems specific to current stuff, but let's add arrears if any.
            $totalPondok = $itemized['PONDOK'];
            if ($stats['total_arrears'] > 0) {
                $totalPondok += $stats['total_arrears'];
            }

            $waMessage = "Assalamu'alaikum Wr. Wb.\n\n" .
                         "Ayah/Bunda orang tua dari ananda *" . strtoupper($student->nama_lengkap) . "* yang Insya Allah selalu dalam keadaan sehat\n\n" .
                         "Mohon izin mengonfirmasi rincian administrasi pendidikan ananda Tahun Pelajaran " . ($academicYear->name ?? '-') . " [" . $className . "] sebagai berikut :\n\n";
            
            $idx = 1;
            if ($totalPondok > 0) {
                $waMessage .= ($idx++) . ". PONDOK : Rp " . number_format($totalPondok, 0, ',', '.') . ($stats['total_arrears'] > 0 ? "*" : "") . "\n";
            }
            if ($itemized['KEGIATAN'] > 0) {
                $waMessage .= ($idx++) . ". KEGIATAN: Rp " . number_format($itemized['KEGIATAN'], 0, ',', '.') . "\n";
            }
            if ($itemized['PERALATAN'] > 0) {
                $waMessage .= ($idx++) . ". PERALATAN: Rp " . number_format($itemized['PERALATAN'], 0, ',', '.') . "\n";
            }
            if ($itemized['BUKU'] > 0) {
                $waMessage .= ($idx++) . ". BUKU : Rp " . number_format($itemized['BUKU'], 0, ',', '.') . "\n";
            }
            if ($itemized['LAINNYA'] > 0) {
                $waMessage .= ($idx++) . ". LAINNYA: Rp " . number_format($itemized['LAINNYA'], 0, ',', '.') . "\n";
            }

            $waMessage .= "\n*Total yang harus dibayarkan : Rp " . number_format($totalRemaining, 0, ',', '.') . "*\n\n" .
                         "Semoga apa yang Ayah/Bunda bayarkan untuk pendidikan ananda menjadi amal jariyah dan membawa keberkahan bagi kita semua. Aamiin.\n\n" .
                         "Pembayaran juga bisa dengan aplikasi Via Transfer dengan No Rekening\n";
            
            foreach ($bankAccounts as $bank) {
                $waMessage .= $bank->bank_name . ": *" . $bank->account_number . "*\n" .
                             "A.n " . $bank->account_holder . "\n";
            }

            $waMessage .= "\nDemikian pemberitahuan ini disampaikan\n" .
                          "Atas ketepatan waktu pembayaran, kami pihak sekolah mengucapkan terima kasih\n\n" .
                          "Wassalamu'alaikum Wr. Wb.";
                         
            $waUrl = "https://wa.me/" . $waPhone . "?text=" . urlencode($waMessage);
        @endphp

        <!-- Row 1: Horizontal Student Profile & Financial Dashboard -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
                    <div class="row g-0">
                        <!-- SECTION 1: Profile & Identity -->
                        <div class="col-md-4 bg-primary p-4 text-white position-relative" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                            <div class="d-flex align-items-center mb-3">
                                @if($student->user && $student->user->photo)
                                    <div class="avatar-squircle bg-white shadow-sm me-3 d-flex align-items-center justify-content-center overflow-hidden position-relative" 
                                         style="width: 80px; height: 80px; border-radius: 24px; border: 4px solid rgba(255,255,255,0.2);">
                                         <img src="{{ asset('photos/' . $student->user->photo) }}" 
                                              class="position-absolute w-100 h-100" 
                                              style="object-fit: cover; top: 0; left: 0; z-index: 2;"
                                              onerror="this.style.display='none'">
                                         <i class="bi bi-person-fill text-primary display-6 mb-0 position-absolute" style="z-index: 1;"></i>
                                    </div>
                                @else
                                    <div class="avatar-squircle bg-white text-primary d-flex align-items-center justify-content-center me-3 shadow-sm" 
                                         style="width: 800px; height: 80px; border-radius: 24px;">
                                        <i class="bi bi-person-fill display-5 mb-0"></i>
                                    </div>
                                @endif
                                <div class="overflow-hidden">
                                    <h4 class="fw-bold mb-0 text-truncate text-uppercase lh-1" style="letter-spacing: 0.5px;">{{ $student->nama_lengkap }}</h4>
                                    <div class="mt-2 d-flex gap-2 flex-wrap">
                                        <span class="badge bg-white text-primary x-small px-2 py-1 rounded-pill fw-bold shadow-sm">{{ $student->nis }}</span>
                                        @if($student->status == 'aktif')
                                            <span class="badge bg-success text-white x-small px-2 py-1 rounded-pill fw-bold shadow-sm">AKTIF</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="border-top border-white border-opacity-25 pt-3">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <p class="x-small mb-1 opacity-75 fw-bold text-uppercase ls-1">Informasi Siswa</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="x-small mb-0 opacity-90"><i class="bi bi-fingerprint me-1"></i> NISN: {{ $student->nisn ?? '-' }}</p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <p class="x-small mb-0 opacity-90">
                                            <i class="bi bi-gender-{{ $student->jenis_kelamin == 'L' ? 'male' : 'female' }} me-1"></i>
                                            {{ $student->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <p class="x-small mb-0 opacity-90 text-truncate"><i class="bi bi-building-fill me-1"></i> {{ $student->unit->name ?? '-' }} ({{ $student->is_boarding ? 'Boarding' : 'Reguler' }})</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Financial Summary Stats -->
                        <div class="col-md-4 bg-white p-4 border-end">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-dark mb-0 x-small text-uppercase ls-1"><i class="bi bi-wallet2 text-primary me-2"></i> RINGKASAN PEMBAYARAN</h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="p-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-4 d-flex align-items-center">
                                        <div class="flex-shrink-0 bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                        </div>
                                        <div>
                                            <p class="x-small text-muted mb-0 fw-bold">TOTAL TUNGGAKAN</p>
                                            <p class="h5 fw-bold text-danger mb-0">Rp {{ number_format($stats['total_arrears'], 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-4 d-flex align-items-center">
                                        <div class="flex-shrink-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-receipt"></i>
                                        </div>
                                        <div>
                                            <p class="x-small text-muted mb-0 fw-bold">TAGIHAN TAHUN INI (SISA)</p>
                                            <p class="h5 fw-bold text-primary mb-0">Rp {{ number_format($stats['total_current_bills'], 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: Class History -->
                        <div class="col-md-4 bg-white p-4">
                            <h6 class="fw-bold text-dark mb-3 x-small text-uppercase ls-1"><i class="bi bi-collection-fill text-primary me-2"></i> RIWAYAT KELAS & WALI</h6>
                            <div class="mb-3">
                                <div class="d-flex gap-2 overflow-auto pb-2 custom-scrollbar">
                                    @php
                                        $sortedClasses = $student->classes->sortByDesc(function($class) use ($academicYear) {
                                            return ($class->pivot->academic_year_id == $academicYear->id ? '9999' : '') . ($class->academicYear->name ?? '');
                                        });
                                    @endphp
                                    @foreach($sortedClasses as $hist)
                                        @php $isCurrent = $hist->pivot->academic_year_id == $academicYear->id; @endphp
                                        <div class="border rounded-4 px-3 py-2 d-flex flex-column align-items-center justify-content-center flex-shrink-0 {{ $isCurrent ? 'bg-primary text-white border-primary shadow-sm' : 'bg-light text-dark' }}" 
                                             style="min-width: 100px;">
                                            <span class="fw-bold" style="font-size: 0.8rem;">{{ $hist->name }}</span>
                                            <span class="x-small {{ $isCurrent ? 'opacity-75' : 'text-muted' }}" style="font-size: 0.6rem;">{{ $hist->academicYear->name ?? '-' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="p-3 bg-light rounded-4">
                                <p class="x-small text-muted mb-1 fw-bold text-uppercase ls-1">Wali Murid / Orang Tua</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold text-dark d-block" style="font-size: 1rem;">{{ $student->nama_wali ?? '-' }}</span>
                                        <p class="x-small text-muted mb-0 fw-bold">{{ $student->no_hp_wali ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NEW Row 2: Secondary Metadata Tiles -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 d-flex flex-row align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                        <i class="bi bi-calendar2-check h4 mb-0"></i>
                    </div>
                    <div>
                        <p class="x-small text-muted mb-0 fw-bold">Update Terakhir</p>
                        <p class="fw-bold text-dark mb-0 italic" style="font-size: 0.85rem;">
                            {{ $stats['last_transaction'] ? $stats['last_transaction']->format('d M Y') : 'Belum Ada Transaksi' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 d-flex flex-row align-items-center">
                    <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle me-3">
                        <i class="bi bi-person-lines-fill h4 mb-0"></i>
                    </div>
                    <div>
                        <p class="x-small text-muted mb-0 fw-bold">Tipe Registrasi</p>
                        <p class="fw-bold text-dark mb-0" style="font-size: 0.85rem;">{{ $student->is_boarding ? 'Asrama (Boarding)' : 'Reguler (Non-Asrama)' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 d-flex align-items-center justify-content-between flex-row bg-white">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle me-3">
                            <i class="bi bi-info-circle-fill h4 mb-0"></i>
                        </div>
                        <div>
                            <p class="x-small text-muted mb-0 fw-bold">Status Keuangan</p>
                            <p class="fw-bold text-dark mb-0" style="font-size: 0.85rem;">
                                @if($stats['total_arrears'] > 0)
                                    Siswa memiliki tunggakan dari tahun sebelumnya.
                                @elseif($stats['total_current_bills'] > 0)
                                    Tagihan tahun aktif belum lunas sepenuhnya.
                                @else
                                    Seluruh tagihan telah lunas.
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="text-end">
                         <span class="badge {{ $stats['total_arrears'] > 0 ? 'bg-danger' : 'bg-success' }} rounded-pill px-3">
                             {{ $stats['total_arrears'] > 0 ? 'PERLU TINDAKAN' : 'STATUS AMAN' }}
                         </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Full Width Content Area: Bill Selection (The "Catalog") -->
            <div class="col-12">
                <!-- Arrears Section (Tunggakan Tahun Sebelumnya) -->
                @if($arrears->isNotEmpty())
                    <div class="card border-0 shadow-sm mb-4 border-start border-4 border-danger" style="border-radius: 15px;">
                        <div class="card-header bg-danger bg-opacity-10 border-0 pt-4 px-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold text-danger mb-0 text-uppercase ls-1">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i> TUNGGAKAN TAHUN SEBELUMNYA
                                </h6>
                                <span class="badge bg-danger text-white rounded-pill px-3">{{ $arrears->count() }} Item Terdeteksi</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-light text-muted x-small text-uppercase">
                                        <tr>
                                            <th class="ps-4" width="50">PILIH</th>
                                            <th>URAIAN TAGIHAN</th>
                                            <th class="text-end">NOMINAL</th>
                                            <th class="text-center" width="150">BAYAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($arrears as $b)
                                            @php 
                                                $remaining = $b->amount - $b->paid_amount;
                                                $monthsName = [7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember', 1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 13 => 'Lainnya'];
                                            @endphp
                                            <tr id="row-{{ $b->id }}" onclick="toggleRow({{ $b->id }})" style="cursor: pointer;">
                                                <td class="ps-4">
                                                    <input type="checkbox" name="bill_ids[]" value="{{ $b->id }}" 
                                                           class="form-check-input bill-checkbox" 
                                                           id="check-{{ $b->id }}"
                                                           data-amount="{{ $remaining }}" 
                                                           data-label="{{ $b->paymentType->name }} (Bulan {{ $monthsName[$b->month] ?? $b->month }})"
                                                           data-category="Tunggakan TA {{ $b->academicYear->name ?? '-' }}"
                                                           onchange="updateRowUI({{ $b->id }}); calculateTotal();"
                                                           onclick="event.stopPropagation()">
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark small">{{ $b->paymentType->name }}</div>
                                                    <div class="x-small text-muted">
                                                        @if($b->month)
                                                            Bulan {{ $monthsName[$b->month] ?? $b->month }}
                                                        @endif
                                                        - TA {{ $b->academicYear->name ?? '-' }}
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <span class="fw-bold text-danger small">Rp{{ number_format($remaining, 0, ',', '.') }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" step="0.01" 
                                                           name="pay_amounts[{{ $b->id }}]" 
                                                           class="form-control form-control-sm text-center pay-input border-0 bg-light rounded-pill fw-bold mx-auto" 
                                                           id="input-{{ $b->id }}"
                                                           value="{{ $remaining }}" 
                                                           oninput="calculateTotal()" 
                                                           onclick="event.stopPropagation()"
                                                           style="width: 100px; font-size: 0.75rem;"
                                                           disabled>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Monthly Bills Grid -->
                @if($monthlyBillsGrouped->isNotEmpty())
                    @foreach($monthlyBillsGrouped as $typeId => $bills)
                        @php $typeName = $bills->first()->paymentType->name; @endphp
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                            <div class="card-header bg-primary bg-opacity-10 border-0 pt-4 px-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold text-primary mb-0 text-uppercase ls-1">
                                        <i class="bi bi-calendar-range me-2"></i> TAGIHAN BULANAN: {{ $typeName }}
                                    </h6>
                                    <button type="button" class="btn btn-xs btn-primary rounded-pill px-3 shadow-sm" style="font-size: 0.65rem;" onclick="selectAllMonthly('{{ $typeId }}')">
                                        <i class="bi bi-check-all"></i> PILIH SEMUA BULAN
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-2 monthly-grid-{{ $typeId }} row-cols-2 row-cols-md-3 row-cols-lg-6">
                                    @foreach($monthsOrder as $m)
                                        @php 
                                            $b = $bills->where('month', $m)->first();
                                            $currentYear = ($m >= 7) ? $academicYear->start_year : ($academicYear->start_year + 1);
                                        @endphp
                                        @if($b)
                                            @php 
                                                $remaining = $b->amount - $b->paid_amount;
                                                $isPaid = $b->status == 'paid' || $remaining <= 0;
                                            @endphp
                                            <div class="col">
                                                <div class="card h-100 transition-all border-2 month-card {{ $isPaid ? 'bg-light border-light opacity-50' : 'border-outline-primary shadow-sm hover-pos' }}" 
                                                     id="card-{{ $b->id }}"
                                                     style="border-radius: 14px; cursor: {{ $isPaid ? 'default' : 'pointer' }};"
                                                     onclick="{{ $isPaid ? '' : 'toggleCard(' . $b->id . ')' }}">
                                                    
                                                    <div class="card-header border-0 bg-transparent pt-3 pb-0 d-flex justify-content-between align-items-center px-2">
                                                        <span class="fw-bold {{ $isPaid ? 'text-muted' : 'text-primary' }}" style="font-size: 0.85rem;">{{ $monthsName[$m] }} {{ $currentYear }}</span>
                                                        @if(!$isPaid)
                                                            <input type="checkbox" name="bill_ids[]" value="{{ $b->id }}" 
                                                                   class="form-check-input bill-checkbox m-0" 
                                                                   id="check-{{ $b->id }}"
                                                                   data-amount="{{ $remaining }}" 
                                                                   data-label="{{ $typeName }} ({{ $monthsName[$m] }})"
                                                                   data-category="Tagihan Reguler"
                                                                   onchange="updateCardUI({{ $b->id }}); calculateTotal();"
                                                                   onclick="event.stopPropagation()"
                                                                   style="width: 1.1rem; height: 1.1rem; border-color: #0d6efd;">
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="card-body pt-1 pb-3 px-2 text-center">
                                                        @if($isPaid)
                                                            @if($b->is_free)
                                                                <div class="text-info fw-bold py-2" style="font-size: 0.75rem;"><i class="bi bi-gift-fill me-1"></i> FREE</div>
                                                            @else
                                                                <div class="text-success fw-bold py-2" style="font-size: 0.75rem;"><i class="bi bi-check-circle-fill me-1"></i> LUNAS</div>
                                                            @endif
                                                        @else
                                                            <div class="fw-bold text-dark mb-1" style="font-size: 1.05rem; letter-spacing: -0.5px;">Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                                                            @if($b->paid_amount > 0)
                                                                <div class="badge bg-warning text-dark mb-2 px-2" style="font-size: 0.65rem; border-radius: 4px;">
                                                                    <i class="bi bi-clock-history me-1"></i> DICICIL: Rp {{ number_format($b->paid_amount, 0, ',', '.') }}
                                                                </div>
                                                            @endif
                                                            <div class="mt-1">
                                                                <input type="number" step="0.01" 
                                                                       name="pay_amounts[{{ $b->id }}]" 
                                                                       class="form-control text-center pay-input border-0 bg-light rounded-3 fw-bold mx-auto" 
                                                                       id="input-{{ $b->id }}"
                                                                       value="{{ $remaining }}" 
                                                                       oninput="calculateTotal()" 
                                                                       onclick="event.stopPropagation()"
                                                                       style="font-size: 0.85rem; height: 28px; max-width: 100%;"
                                                                       disabled>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- Annual Bills Table -->
                @if($annualBills->isNotEmpty())
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h6 class="fw-bold text-dark mb-0 text-uppercase ls-1">
                                <i class="bi bi-tag-fill text-warning me-2"></i> TAGIHAN NON-BULANAN
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-light text-muted x-small text-uppercase">
                                        <tr>
                                            <th class="ps-4 py-3" width="60">PILIH</th>
                                            <th>JENIS TAGIHAN</th>
                                            <th class="text-end">SISA (RP)</th>
                                            <th width="150" class="text-center">BAYAR (RP)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($annualBills as $b)
                                            @php 
                                                $remaining = $b->amount - $b->paid_amount;
                                                $isPaid = $b->status == 'paid' || $remaining <= 0;
                                            @endphp
                                            <tr id="row-{{ $b->id }}" onclick="{{ $isPaid ? '' : 'toggleRow(' . $b->id . ')' }}" style="cursor: {{ $isPaid ? 'default' : 'pointer' }}">
                                                <td class="ps-4">
                                                    @if(!$isPaid)
                                                        <input type="checkbox" name="bill_ids[]" value="{{ $b->id }}" 
                                                               class="form-check-input bill-checkbox" 
                                                               id="check-{{ $b->id }}"
                                                               data-amount="{{ $remaining }}" 
                                                               data-label="{{ $b->paymentType->name }}"
                                                               data-category="Tagihan Lainnya"
                                                               onchange="updateRowUI({{ $b->id }}); calculateTotal();"
                                                               onclick="event.stopPropagation()">
                                                    @else
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark small">{{ $b->paymentType->name }}</div>
                                                    <div class="x-small text-muted">Tagihan Awal: Rp{{ number_format($b->amount, 0, ',', '.') }}</div>
                                                </td>
                                                <td class="text-end">
                                                    @if($isPaid)
                                                        @if($b->is_free)
                                                            <span class="badge bg-info-soft text-info px-2 py-1 rounded-pill">FREE</span>
                                                        @else
                                                            <span class="badge bg-success-soft text-success px-2 py-1 rounded-pill">Lunas</span>
                                                        @endif
                                                    @else
                                                        <div class="fw-bold text-danger small">Rp{{ number_format($remaining, 0, ',', '.') }}</div>
                                                        @if($b->paid_amount > 0)
                                                            <div class="x-small text-warning fw-bold mt-1" style="font-size: 0.65rem;">
                                                                <i class="bi bi-clock-history"></i> DICICIL (Masuk: Rp{{ number_format($b->paid_amount, 0, ',', '.') }})
                                                            </div>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if(!$isPaid)
                                                        <input type="number" step="0.01" 
                                                               name="pay_amounts[{{ $b->id }}]" 
                                                               class="form-control form-control-sm text-center pay-input border-0 bg-light rounded-pill fw-bold mx-auto" 
                                                               id="input-{{ $b->id }}"
                                                               value="{{ $remaining }}" 
                                                               oninput="calculateTotal()" 
                                                               onclick="event.stopPropagation()"
                                                               style="width: 100px; font-size: 0.75rem;"
                                                               disabled>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Payment Summary & Checkout Section -->
                <div class="row g-4 mt-5">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 bg-light h-100">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i> CATATAN PEMBAYARAN</h6>
                                <ul class="text-muted mb-0 ps-3" style="font-size: 0.95rem;">
                                    <li class="mb-2">Pastikan nominal yang diinput sudah sesuai dengan uang yang diterima.</li>
                                    <li class="mb-2">Untuk pembayaran <b>Transfer</b>, harap pastikan bukti transfer sudah divalidasi.</li>
                                    <li class="mb-2">Kuitansi digital akan otomatis tersedia di riwayat setelah proses berhasil.</li>
                                    <li>Tagihan yang lunas otomatis berpindah ke status <b>PAID</b>.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-lg overflow-hidden h-100" style="border-radius: 20px;">
                            <div class="card-body p-4 bg-white">
                                <div class="mb-4 text-center">
                                    <label class="fw-bold text-muted x-small d-block mb-3 text-uppercase ls-1">Metode Pembayaran</label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="payment_method" id="methodCash" value="cash" checked>
                                        <label class="btn btn-outline-primary fw-bold py-2 px-3 border-2" for="methodCash">
                                            <i class="bi bi-cash-stack me-1"></i> TUNAI
                                        </label>

                                        <input type="radio" class="btn-check" name="payment_method" id="methodTransfer" value="transfer">
                                        <label class="btn btn-outline-primary fw-bold py-2 px-3 border-2" for="methodTransfer">
                                            <i class="bi bi-bank me-1"></i> TRANSFER
                                        </label>
                                    </div>
                                </div>

                                <div id="bank_account_selection" class="mb-4 d-none p-3 bg-light rounded-3 animate__animated animate__fadeIn">
                                    <label class="form-label x-small fw-bold text-muted text-uppercase mb-2">REKENING TUJUAN</label>
                                    <select name="bank_account_id" class="form-select border-0 shadow-sm">
                                        <option value="">-- Pilih Bank --</option>
                                        @foreach($bankAccounts as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->bank_name }} - {{ $bank->account_number }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="border-top pt-3 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted small fw-bold">TOTAL PEMBAYARAN</span>
                                        <span class="h2 fw-bold text-primary mb-0" id="totalDisplay">Rp 0</span>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-8">
                                        <button type="button" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow checkout-btn" id="submitBtn" disabled onclick="showPaymentConfirmation()">
                                            <i class="bi bi-shield-check me-2"></i> KONFIRMASI & BAYAR
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-success w-100 py-3 fw-bold rounded-pill shadow-sm" id="waBtn" disabled onclick="sendWASelection()">
                                            <i class="bi bi-whatsapp"></i>
                                        </button>
                                    </div>
                                </div>

                                <p class="text-center text-muted x-small mt-3 mb-0">
                                    <i class="bi bi-lock-fill me-1"></i> Sistem akan mencatat kode pembayaran secara real-time.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- History Tab Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-clock-history text-primary me-2"></i> RIWAYAT PEMBAYARAN TERKINI
                        </h6>
                        <span class="badge bg-light text-muted border rounded-pill">{{ $transactions->count() }} Transaksi</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-muted x-small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3">KODE PEMBAYARAN & TANGGAL</th>
                                    <th>URAIAN PEMBAYARAN</th>
                                    <th class="text-end">NOMINAL</th>
                                    <th>ADM</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $t)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark small">{{ $t->invoice_number }}</div>
                                            <div class="x-small text-muted">{{ $t->created_at->translatedFormat('d/m/Y H:i') }}</div>
                                            <div class="mt-1">
                                                <span class="badge {{ $t->payment_method == 'transfer' ? 'bg-info-soft text-info' : 'bg-secondary-soft text-secondary' }} x-small px-2 border">
                                                    {{ strtoupper($t->payment_method) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            @foreach($t->items as $item)
                                                <div class="x-small text-muted mb-1 d-flex justify-content-between align-items-center me-3">
                                                    <div>
                                                        <span class="fw-bold text-primary">{{ $item->paymentType->name ?? '-' }}</span>
                                                        @if($item->month_paid)
                                                            <span class="text-muted ms-1">({{ \Carbon\Carbon::create()->month((int)$item->month_paid)->translatedFormat('F') }} {{ $item->year_paid }})</span>
                                                        @endif
                                                    </div>
                                                    <span class="text-dark">Rp{{ number_format($item->amount, 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td class="text-end fw-bold text-dark">
                                            Rp{{ number_format($t->amount, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <div class="x-small fw-bold text-dark text-truncate" style="max-width: 80px;">{{ $t->user->name ?? '-' }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button type="button" class="btn btn-light border btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalDetailTrx{{ $t->id }}" title="Lihat Detail">
                                                    <i class="bi bi-eye text-info"></i>
                                                </button>
                                                <a href="{{ route('finance.payments.receipt', $t->id) }}" target="_blank" class="btn btn-light border btn-sm rounded-pill px-3 shadow-sm" title="Cetak Kwitansi">
                                                    <i class="bi bi-printer text-primary"></i>
                                                </a>
                                            </div>

                                            <!-- Modal Detail Transaksi -->
                                            <div class="modal fade" id="modalDetailTrx{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                                        <div class="modal-header bg-light border-0 py-3">
                                                            <h6 class="modal-title fw-bold text-dark">
                                                                <i class="bi bi-receipt text-primary me-2"></i> Detail Transaksi: {{ $t->invoice_number }}
                                                            </h6>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <div class="row g-3 mb-4">
                                                                <div class="col-6">
                                                                    <label class="x-small text-muted text-uppercase fw-bold ls-1 mb-1 d-block">Tanggal Transaksi</label>
                                                                    <div class="text-dark small fw-bold">{{ $t->created_at->translatedFormat('d F Y') }}</div>
                                                                    <div class="x-small text-muted">{{ $t->created_at->format('H:i') }} WIB</div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="x-small text-muted text-uppercase fw-bold ls-1 mb-1 d-block">Metode</label>
                                                                    <span class="badge {{ $t->payment_method == 'transfer' ? 'bg-info-soft text-info' : 'bg-secondary-soft text-secondary' }} x-small px-2 border">
                                                                        {{ strtoupper($t->payment_method) }}
                                                                    </span>
                                                                    @if($t->bankAccount)
                                                                        <div class="x-small text-muted mt-1">{{ $t->bankAccount->bank_name }}</div>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="bg-light rounded-3 p-3 mb-4">
                                                                <label class="x-small text-muted text-uppercase fw-bold ls-1 mb-2 d-block">Rincian Pembayaran</label>
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-borderless mb-0 align-middle">
                                                                        <thead>
                                                                            <tr class="x-small text-muted border-bottom">
                                                                                <th class="ps-0">Item</th>
                                                                                <th class="text-end pe-0">Nominal</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($t->items as $item)
                                                                                <tr>
                                                                                    <td class="ps-0 py-2">
                                                                                        <div class="fw-bold text-dark small">{{ $item->paymentType->name ?? '-' }}</div>
                                                                                        @if($item->month_paid)
                                                                                            <div class="x-small text-muted">{{ \Carbon\Carbon::create()->month((int)$item->month_paid)->translatedFormat('F') }} {{ $item->year_paid }}</div>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="text-end pe-0 fw-bold text-dark">
                                                                                        Rp{{ number_format($item->amount, 0, ',', '.') }}
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr class="border-top">
                                                                                <th class="ps-0 pt-2 text-dark">TOTAL</th>
                                                                                <th class="text-end pe-0 pt-2 text-primary fw-bold fs-6">
                                                                                    Rp{{ number_format($t->amount, 0, ',', '.') }}
                                                                                </th>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                            @if($t->notes)
                                                                <div class="mb-4">
                                                                    <label class="x-small text-muted text-uppercase fw-bold ls-1 mb-1 d-block">Catatan / Keterangan</label>
                                                                    <div class="p-2 border rounded small italic text-muted bg-white">
                                                                        "{{ $t->notes }}"
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <div class="d-flex align-items-center bg-primary bg-opacity-10 p-2 rounded-3">
                                                                <div class="avatar-squircle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; border-radius: 8px;">
                                                                    <i class="bi bi-person-fill x-small"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="x-small text-muted" style="font-size: 0.65rem;">PETUGAS KASIR</div>
                                                                    <div class="x-small fw-bold text-dark">{{ $t->user->name ?? 'System' }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 bg-light p-3">
                                                            <button type="button" class="btn btn-secondary btn-sm px-4 rounded-pill" data-bs-dismiss="modal">Tutup</button>
                                                            <a href="{{ route('finance.payments.receipt', $t->id) }}" target="_blank" class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm">
                                                                <i class="bi bi-printer me-1"></i> Cetak Kwitansi
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-5 text-muted italic small">Belum ada riwayat transaksi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div style="text-align: center; margin-top: 30px; margin-bottom: 50px; font-size: 11px; color: #94a3b8;">
        &copy; {{ date('Y') }} LPT NURUL ILMI. Digital Receipt Generated System.
    </div>

    <!-- Receipt Print Modal (Iframe Popup) -->
    <div class="modal fade" id="printReceiptModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; height: 90vh;">
                <div class="modal-header border-bottom bg-light">
                    <h6 class="modal-title fw-bold text-dark">
                        <i class="bi bi-printer-fill text-primary me-2"></i> KWITANSI PEMBAYARAN DIGITAL
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 overflow-hidden">
                    <iframe id="receiptFrame" src="" style="width: 100%; height: 100%; border: none;" allow="print"></iframe>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup & Lanjut</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4" onclick="document.getElementById('receiptFrame').contentWindow.print()">
                        <i class="bi bi-printer me-1"></i> Cetak Ulang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-body p-5 text-center">
                    <div class="mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-question-circle fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark">Konfirmasi Pembayaran</h4>
                        <p class="text-muted">Apakah Anda yakin ingin memproses pembayaran ini? Pastikan nominal uang sudah sesuai.</p>
                    </div>

                    <div class="bg-light rounded-4 p-4 mb-4 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Siswa:</span>
                            <span class="fw-bold text-dark small text-uppercase">{{ $student->nama_lengkap }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Metode:</span>
                            <span class="fw-bold text-dark small" id="summaryMethod">-</span>
                        </div>
                        <hr class="my-2 opacity-10">
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="fw-bold text-dark">TOTAL BAYAR:</span>
                            <span class="h3 fw-bold text-primary mb-0" id="summaryTotal">Rp 0</span>
                        </div>
                    </div>

                    <div class="mb-4 text-center">
                        <label class="form-label x-small fw-bold text-muted text-uppercase mb-2 d-block">Masukkan PIN Keamanan (6 Digit)</label>
                        <input type="tel" name="security_pin" id="modal_security_pin" 
                               class="form-control form-control-lg text-center fw-bold rounded-pill border-2 pin-field" 
                               maxlength="6" placeholder="••••••" 
                               autocomplete="one-time-code"
                               style="letter-spacing: 15px; -webkit-text-security: disc;">
                        <div class="invalid-feedback text-center" id="pin-feedback">PIN Keamanan wajib diisi.</div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <button type="button" class="btn btn-light w-100 py-3 rounded-pill fw-bold" data-bs-dismiss="modal">BATAL</button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm" onclick="processFinalPayment()">
                                <i class="bi bi-check-circle me-1"></i> YA, PROSES
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .x-small { font-size: 0.8rem; }
    .month-card { transition: all 0.2s ease; border-color: transparent; border-width: 2px !important; }
    .month-card:hover { border-color: #dee2e6 !important; transform: translateY(-2px); }
    .month-card.active { border-color: #0d6efd !important; background-color: rgba(13, 110, 253, 0.03); }
    .hover-pos:active { transform: scale(0.95); }
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .bg-info-soft { background-color: rgba(13, 202, 240, 0.1); }
    .bg-secondary-soft { background-color: rgba(108, 117, 125, 0.1); }
    .checkout-btn { transition: all 0.3s ease; }
    .checkout-btn:hover:not(:disabled) { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(13, 110, 253, 0.2) !important; }
    tr.active { background-color: rgba(13, 110, 253, 0.02); }
    .table td { padding: 1rem 0.75rem !important; }
</style>

<script>
let paymentModal;
document.addEventListener('DOMContentLoaded', function() {
    paymentModal = new bootstrap.Modal(document.getElementById('confirmPaymentModal'));

    @if(session('print_receipt_id'))
        // Show receipt in modal popup instead of redirecting or new tab
        const receiptUrl = "{{ route('finance.payments.receipt', session('print_receipt_id')) }}";
        const frame = document.getElementById('receiptFrame');
        if(frame) {
            frame.src = receiptUrl;
            const printReceiptModal = new bootstrap.Modal(document.getElementById('printReceiptModal'));
            printReceiptModal.show();
        }
    @endif
});

function showPaymentConfirmation() {
    const total = document.getElementById('totalDisplay').textContent;
    const method = document.querySelector('input[name="payment_method"]:checked').value;
    
    document.getElementById('summaryTotal').textContent = total;
    document.getElementById('summaryMethod').textContent = method.toUpperCase();
    
    paymentModal.show();
}

function processFinalPayment() {
    const pinInput = document.getElementById('modal_security_pin');
    const feedback = document.getElementById('pin-feedback');
    
    if (!pinInput.value || pinInput.value.length < 6) {
        pinInput.classList.add('is-invalid');
        feedback.style.display = 'block';
        return;
    }

    const btn = event.target.closest('button');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> MEMPROSES...';
    
    // Create hidden input in form for PIN
    const form = document.getElementById('paymentForm');
    const hiddenPin = document.createElement('input');
    hiddenPin.type = 'hidden';
    hiddenPin.name = 'security_pin';
    hiddenPin.value = pinInput.value;
    form.appendChild(hiddenPin);
    
    form.submit();
}

function calculateTotal() {
    let total = 0;
    const checkboxes = document.querySelectorAll('.bill-checkbox');
    let anyChecked = false;

    checkboxes.forEach(cb => {
        const id = cb.value;
        const input = document.getElementById('input-' + id);
        let container = cb.closest('.month-card') || cb.closest('tr');
        
        if (cb.checked) {
            anyChecked = true;
            if(input) {
                input.disabled = false;
                if(!input.value) input.value = cb.dataset.amount;
                total += parseFloat(input.value) || 0;
            }
            if(container) container.classList.add('active');
        } else {
            if(input) {
                input.disabled = true;
                input.value = cb.dataset.amount;
            }
            if(container) container.classList.remove('active');
        }
    });

    document.getElementById('totalDisplay').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    const submitBtn = document.getElementById('submitBtn');
    const waBtn = document.getElementById('waBtn');
    if (submitBtn) submitBtn.disabled = !anyChecked;
    if (waBtn) waBtn.disabled = !anyChecked;
}

function sendWASelection() {
    const checkboxes = document.querySelectorAll('.bill-checkbox:checked');
    if (checkboxes.length === 0) return;

    let itemized = {
        'PONDOK': 0,
        'KEGIATAN': 0,
        'PERALATAN': 0,
        'BUKU': 0,
        'LAINNYA': 0
    };
    let selectedTotal = 0;
    let hasArrears = false;

    checkboxes.forEach(cb => {
        const id = cb.value;
        const input = document.getElementById('input-' + id);
        const amount = parseFloat(input.value) || 0;
        const label = cb.dataset.label.toUpperCase();
        
        selectedTotal += amount;
        
        // Categorize
        if (label.includes('PONDOK') || label.includes('SPP') || label.includes('BULANAN')) {
            itemized['PONDOK'] += amount;
        } else if (label.includes('KEGIATAN')) {
            itemized['KEGIATAN'] += amount;
        } else if (label.includes('PERALATAN') || label.includes('SERAGAM')) {
            itemized['PERALATAN'] += amount;
        } else if (label.includes('BUKU') || label.includes('KITAB')) {
            itemized['BUKU'] += amount;
        } else {
            itemized['LAINNYA'] += amount;
        }

        if (cb.dataset.category.includes('Tunggakan')) {
            hasArrears = true;
        }
    });

    const studentName = "{{ strtoupper($student->nama_lengkap) }}";
    const waPhone = "{{ $waPhone }}";
    const currentYear = "{{ $academicYear->name ?? '-' }}";
    @php
        $currentClass = $student->classes->where('pivot.academic_year_id', $academicYear->id)->first();
        $className = $currentClass ? $currentClass->name : '-';
    @endphp
    const className = "{{ $className }}";
    
    let message = "Assalamu'alaikum Wr. Wb.\n\n" +
                  "Ayah/Bunda orang tua dari ananda *" + studentName + "* yang Insya Allah selalu dalam keadaan sehat\n\n" +
                  "Mohon izin mengonfirmasi rincian administrasi pendidikan ananda Tahun Pelajaran " + currentYear + " [" + className + "] sebagai berikut :\n\n";

    let idx = 1;
    if (itemized['PONDOK'] > 0) {
        message += (idx++) + ". PONDOK : Rp " + new Intl.NumberFormat('id-ID').format(itemized['PONDOK']) + (hasArrears ? "*" : "") + "\n";
    }
    if (itemized['KEGIATAN'] > 0) {
        message += (idx++) + ". KEGIATAN: Rp " + new Intl.NumberFormat('id-ID').format(itemized['KEGIATAN']) + "\n";
    }
    if (itemized['PERALATAN'] > 0) {
        message += (idx++) + ". PERALATAN: Rp " + new Intl.NumberFormat('id-ID').format(itemized['PERALATAN']) + "\n";
    }
    if (itemized['BUKU'] > 0) {
        message += (idx++) + ". BUKU : Rp " + new Intl.NumberFormat('id-ID').format(itemized['BUKU']) + "\n";
    }
    if (itemized['LAINNYA'] > 0) {
        message += (idx++) + ". LAINNYA: Rp " + new Intl.NumberFormat('id-ID').format(itemized['LAINNYA']) + "\n";
    }

    message += "\n*Total yang harus dibayarkan : Rp " + new Intl.NumberFormat('id-ID').format(selectedTotal) + "*\n\n" +
                  "Semoga apa yang Ayah/Bunda bayarkan untuk pendidikan ananda menjadi amal jariyah dan membawa keberkahan bagi kita semua. Aamiin.\n\n" +
                  "Pembayaran juga bisa dengan aplikasi Via Transfer dengan No Rekening\n";

    @foreach($bankAccounts as $bank)
        message += "{{ $bank->bank_name }}: *{{ $bank->account_number }}*\n" +
                   "A.n {{ $bank->account_holder }}\n";
    @endforeach

    message += "\nDemikian pemberitahuan ini disampaikan\n" +
               "Atas ketepatan waktu pembayaran, kami pihak sekolah mengucapkan terima kasih\n\n" +
               "Wassalamu'alaikum Wr. Wb.";
                  
    const url = "https://wa.me/" + waPhone + "?text=" + encodeURIComponent(message);
    window.open(url, '_blank');
}

function toggleCard(id) {
    const checkbox = document.getElementById('check-' + id);
    if (checkbox) {
        checkbox.checked = !checkbox.checked;
        updateCardUI(id);
        calculateTotal();
    }
}

function updateCardUI(id) {
    const checkbox = document.getElementById('check-' + id);
    const card = document.getElementById('card-' + id);
    const input = document.getElementById('input-' + id);
    if (checkbox.checked) {
        if(card) card.classList.add('active');
        if(input) input.disabled = false;
    } else {
        if(card) card.classList.remove('active');
        if(input) input.disabled = true;
    }
}

function selectAllMonthly(typeId) {
    const grid = document.querySelector('.monthly-grid-' + typeId);
    if (grid) {
        const checkboxes = grid.querySelectorAll('.bill-checkbox');
        checkboxes.forEach(cb => {
            if (!cb.checked) {
                cb.checked = true;
                updateCardUI(cb.value);
            }
        });
        calculateTotal();
    }
}

function toggleRow(id) {
    const checkbox = document.getElementById('check-' + id);
    if (checkbox) {
        checkbox.checked = !checkbox.checked;
        updateRowUI(id);
        calculateTotal();
    }
}

function updateRowUI(id) {
    const checkbox = document.getElementById('check-' + id);
    const row = document.getElementById('row-' + id);
    const input = document.getElementById('input-' + id);
    if (checkbox.checked) {
        if(row) row.classList.add('active');
        if(input) input.disabled = false;
    } else {
        if(row) row.classList.remove('active');
        if(input) input.disabled = true;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const methodRadios = document.getElementsByName('payment_method');
    const bankSelection = document.getElementById('bank_account_selection');

    function toggleBankSelection() {
        let isTransfer = false;
        for (const radio of methodRadios) {
            if (radio.checked && radio.value === 'transfer') isTransfer = true;
        }
        if (isTransfer) bankSelection.classList.remove('d-none');
        else bankSelection.classList.add('d-none');
    }
    
    for (const radio of methodRadios) {
        radio.addEventListener('change', toggleBankSelection);
    }
});
</script>
@endsection
