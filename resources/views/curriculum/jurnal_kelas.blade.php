@extends('layouts.app')

@section('title', 'Jurnal Kelas - Rekap Check-in Guru')

@push('styles')
<style>
    .filter-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border: none;
    }
    .jurnal-card {
        border-radius: 15px;
        overflow: hidden;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    .table-jurnal thead {
        background: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        font-weight: 700;
    }
    .checkin-row {
        transition: all 0.2s;
    }
    .checkin-row:hover {
        background-color: rgba(67, 97, 238, 0.03);
    }
    .status-badge {
        padding: 5px 12px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        white-space: nowrap;
    }
    .teacher-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
    }

    /* Aggressive Print Logic - Fixed for Dashboard Templates */
    @media print {
        /* Hide everything by default */
        body > *:not(#print-area-wrapper), 
        .app-header, .app-sidebar, .app-footer, .filter-card, .btn-group, 
        .pagination, .navbar, .btn, .breadcrumb, .d-print-none, .card-header .badge,
        .cute-loader, .loading-overlay {
            display: none !important;
        }

        /* Show the area we want to print */
        #print-area-wrapper {
            display: block !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            visibility: visible !important;
        }

        #print-area-wrapper * {
            visibility: visible !important;
        }

        /* Essential Reset for layout */
        html, body {
            height: auto !important;
            overflow: visible !important;
            background: #fff !important;
        }

        .jurnal-card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
            display: block !important;
            width: 100% !important;
        }

        .table-responsive {
            display: block !important;
            overflow: visible !important;
        }

        .table-jurnal {
            display: table !important;
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 0 !important;
        }

        .table-jurnal thead {
            display: table-header-group !important;
        }

        .table-jurnal tr {
            display: table-row !important;
            page-break-inside: avoid !important;
        }

        .table-jurnal td, .table-jurnal th {
            display: table-cell !important;
            padding: 8px 5px !important;
            border: 1px solid #000 !important;
            font-size: 8pt !important;
            color: #000 !important;
            vertical-align: middle !important;
        }

        .print-only {
            display: block !important;
        }

        .status-badge {
            border: 1px solid #000 !important;
            padding: 2px 4px !important;
            font-size: 7pt !important;
            background: none !important;
            color: #000 !important;
        }

        /* Forced color resets */
        .text-primary, .text-muted, .text-dark, .fw-bold {
            color: #000 !important;
        }
    }
    .print-only {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="app-content pt-4">
    <div class="container-fluid">

        {{-- Screen Title Section --}}
        <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
            <div>
                <h4 class="fw-bold text-dark mb-0"><i class="bi bi-journal-check text-primary me-2"></i> Jurnal Kelas</h4>
                <p class="text-muted small mb-0">Rekapitulasi kehadiran guru dalam kegiatan belajar mengajar</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('curriculum.jurnal-kelas.print', ['unit_id' => $unitId, 'academic_year_id' => $academicYearId, 'class_id' => $classId, 'date' => $date]) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-printer me-1"></i> Cetak Jurnal
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card filter-card mb-4">
            <div class="card-body">
                <form action="{{ route('curriculum.jurnal-kelas') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Unit Sekolah</label>
                        <select name="unit_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Unit</option>
                            @foreach($allowedUnits as $unit)
                                <option value="{{ $unit->id }}" {{ $unitId == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Tahun Pelajaran</label>
                        <select name="academic_year_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $academicYearId == $ay->id ? 'selected' : '' }}>
                                    {{ $ay->name }} {{ ucfirst($ay->semester) }} {{ $ay->status == 'active' ? 'AKTIF' : 'TIDAK AKTIF' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Kelas</label>
                        <select name="class_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Tanggal</label>
                        <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}" onchange="this.form.submit()">
                    </div>
                </form>
            </div>
        </div>

        {{-- Jurnal Table --}}
        <div class="card jurnal-card">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-table me-2"></i> Laporan Jurnal Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                    </h6>
                    <span class="badge bg-primary rounded-pill">{{ $checkins->count() }} Data Ditemukan</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-jurnal mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">No.</th>
                                <th>Jam Jadwal</th>
                                <th>Mata Pelajaran</th>
                                <th>Check-in</th>
                                <th>Kelas</th>
                                <th>Guru Pengajar</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Bukti</th>
                                <th class="pe-4">Keterangan/Materi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checkins as $index => $c)
                                @if($c->status == 'holiday')
                                    <tr class="bg-light">
                                        <td colspan="9" class="text-center py-5">
                                            <div class="display-6 text-danger fw-bold mb-2"><i class="bi bi-calendar-x"></i> HARI LIBUR</div>
                                            <h5 class="text-muted">{{ $c->holiday_name }}</h5>
                                        </td>
                                    </tr>
                                    @break
                                @endif
                                <tr class="checkin-row @if($c->status == 'absent') bg-light @elseif($c->status == 'break') bg-warning-subtle @endif">
                                    <td class="ps-4 text-muted small">{{ $index + 1 }}</td>
                                    {{-- Kolom 2: Jam Jadwal --}}
                                    <td>
                                        <div class="fw-bold text-dark">
                                            {{ $c->schedule ? substr($c->schedule->start_time, 0, 5) . ' - ' . substr($c->schedule->end_time, 0, 5) : '-' }}
                                        </div>
                                    </td>
                                    {{-- Kolom 3: Mata Pelajaran --}}
                                    <td>
                                        @if($c->status == 'break')
                                            <div class="fw-bold text-info"><i class="bi bi-clock me-1"></i> {{ $c->notes }}</div>
                                        @else
                                            <div class="fw-bold text-primary">{{ $c->schedule?->subject?->name ?? 'Mata Pelajaran Tidak Ditemukan' }}</div>
                                            <div class="small text-muted">{{ $c->schedule?->unit?->name ?? '-' }}</div>
                                        @endif
                                    </td>
                                    {{-- Kolom 4: Waktu Check-in --}}
                                    <td class="@if($c->status == 'break') text-center @endif">
                                        @if($c->checkin_time)
                                            <div class="fw-bold">{{ $c->checkin_time->format('H:i:s') }}</div>
                                            <div class="text-muted small" style="font-size: 0.70rem;">{{ $c->checkin_time->translatedFormat('d M Y') }}</div>
                                        @elseif($c->status == 'break')
                                            <span class="text-muted small">-</span>
                                        @elseif($c->status == 'future')
                                            <span class="text-muted fw-bold" style="font-size: 0.65rem;"><i class="bi bi-hourglass-split"></i> Belum Mulai</span>
                                        @else
                                            <span class="text-danger fw-bold" style="font-size: 0.65rem;"><i class="bi bi-x-circle"></i> Belum Check-in</span>
                                        @endif
                                    </td>
                                    {{-- Kolom 5: Kelas --}}
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $c->schedule?->schoolClass?->name ?? '-' }}</span>
                                    </td>
                                    {{-- Kolom 6: Guru Pengajar --}}
                                    <td>
                                        @if($c->status != 'break')
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $userPhoto = $c->user?->photo ?? $c->schedule?->teacher?->photo;
                                                    $userName = $c->user?->name ?? $c->schedule?->teacher?->name;
                                                    $userNip = $c->user?->nip ?? $c->schedule?->teacher?->nip;
                                                @endphp
                                                <img src="{{ $userPhoto ? asset('photos/' . $userPhoto) : 'https://ui-avatars.com/api/?name=' . urlencode($userName ?? 'Guru') }}" 
                                                     class="teacher-avatar">
                                                <div>
                                                    <div class="fw-bold small">{{ $userName ?? 'Guru Tidak Ditemukan' }}</div>
                                                    <div class="text-muted" style="font-size: 0.7rem;">NIP: {{ $userNip ?? '-' }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    {{-- Kolom 7: Status --}}
                                    <td class="text-center">
                                        @if($c->status == 'present' || $c->status == 'ontime')
                                            <span class="status-badge bg-success-subtle text-success">
                                                <i class="bi bi-check2-circle"></i> HADIR
                                            </span>
                                        @elseif($c->status == 'late')
                                            <span class="status-badge bg-danger-subtle text-danger">
                                                <i class="bi bi-clock-history"></i> TERLAMBAT
                                            </span>
                                        @elseif($c->status == 'absent')
                                            <span class="status-badge bg-danger text-white">
                                                <i class="bi bi-exclamation-triangle"></i> TIDAK HADIR
                                            </span>
                                        @elseif($c->status == 'future')
                                            <span class="status-badge bg-light text-muted border">
                                                <i class="bi bi-hourglass"></i> BELUM MULAI
                                            </span>
                                        @elseif($c->status == 'break')
                                            <span class="status-badge bg-info-subtle text-info">
                                                <i class="bi bi-cup-hot"></i> ISTIRAHAT
                                            </span>
                                        @else
                                            <span class="status-badge bg-warning-subtle text-warning">
                                                <i class="bi bi-info-circle"></i> {{ strtoupper($c->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    {{-- Kolom 8: Bukti --}}
                                    <td class="text-center">
                                        @if($c->photo)
                                            {{-- Screen View (Modal Trigger) --}}
                                            <a href="javascript:void(0)" 
                                               onclick="showPhoto('{{ asset('storage/' . $c->photo) }}')" 
                                               class="d-print-none">
                                                <img src="{{ asset('storage/' . $c->photo) }}" 
                                                     class="rounded shadow-sm" 
                                                     style="width: 45px; height: 45px; object-fit: cover;"
                                                     alt="Bukti Mengajar">
                                            </a>
                                            {{-- Print View (Plain Image) --}}
                                            <img src="{{ asset('storage/' . $c->photo) }}" 
                                                 class="d-none d-print-inline rounded" 
                                                 style="width: 45px; height: 45px; object-fit: cover;"
                                                 alt="Bukti Mengajar">
                                        @else
                                            <span class="text-muted small italic">-</span>
                                        @endif
                                    </td>
                                    {{-- Kolom 9: Keterangan --}}
                                    <td class="pe-4">
                                        <div class="text-dark" style="max-width: 250px; white-space: normal; font-size: 0.85rem; line-height: 1.4;">
                                            @if($c->status == 'break')
                                                <span class="text-info fw-bold small text-uppercase">{{ $c->notes }}</span>
                                            @elseif(trim($c->notes))
                                                {{ $c->notes }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="bi bi-inbox display-1 text-muted opacity-25"></i>
                                        <p class="text-muted mt-3">Tidak ada data jadwal atau jurnal pada filter ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                     </table>
                </div>
                
                {{-- Pagination Links --}}
                <div class="card-footer bg-white border-top-0 py-3 d-flex justify-content-center">
                    {{ $checkins->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Photo Modal --}}
<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0">
                <img id="modalPhoto" src="" class="img-fluid rounded shadow" style="max-height: 80vh;" alt="Bukti Mengajar">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showPhoto(url) {
        const modal = new bootstrap.Modal(document.getElementById('photoModal'));
        document.getElementById('modalPhoto').src = url;
        modal.show();
    }
</script>
@endpush
@endsection
