@extends('layouts.app')

@section('title', 'Riwayat Check-in Kelas')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Riwayat Check-in Kelas</h1>
            <p class="text-muted small mb-0">Pantau kehadiran guru dan pelaksanaan kegiatan belajar mengajar.</p>
        </div>
        <div class="d-flex gap-2">
            @if(in_array(auth()->user()->role, ['administrator', 'staff']))
                <a href="{{ route('class-checkins.export-pdf', request()->all()) }}" class="btn btn-white shadow-sm border-0 px-3 py-2 rounded-pill" target="_blank">
                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i><strong>Export PDF</strong>
                </a>
            @endif
            <a href="{{ route('class-checkins.create') }}" class="btn btn-primary shadow-sm px-4 py-2 rounded-pill">
                <i class="bi bi-qr-code-scan me-2"></i><strong>Check-in Sekarang</strong>
            </a>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-white border-0 shadow-sm h-100 p-4 rounded-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Hari Ini</div>
                        <div class="h3 fw-bold mb-0 text-primary">{{ $totalTodayCount }}</div>
                    </div>
                    <div class="stats-icon bg-soft-primary p-3 rounded-3">
                        <i class="bi bi-calendar-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Note: We don't have exact stats from controller, but we can visually represent the active filter count -->
    </div>

    <!-- Filters Section -->
    <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold"><i class="bi bi-filter-left me-2 text-primary"></i>Filter Data</h6>
        </div>
        <div class="card-body bg-light-soft p-4">
            <form action="{{ route('class-checkins.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Tahun Pelajaran</label>
                    <select name="academic_year_id" class="form-select border-0 shadow-sm rounded-3" onchange="this.form.submit()">
                        <option value="">-- Tahun Aktif --</option>
                        <option value="all" {{ request('academic_year_id') == 'all' ? 'selected' : '' }}>-- Semua Riwayat --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" 
                                {{ (request('academic_year_id') == $year->id || (!request('academic_year_id') && $year->id == $activeYearId)) ? 'selected' : '' }}>
                                {{ $year->start_year }}/{{ $year->end_year }} ({{ ucfirst($year->status) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Unit Sekolah</label>
                    <select name="unit_id" id="unitFilter" class="form-select border-0 shadow-sm rounded-3">
                        <option value="">-- Semua Unit --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Kelas</label>
                    <select name="class_id" id="classFilter" class="form-select border-0 shadow-sm rounded-3">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}" data-unit="{{ $cls->unit_id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
                                {{ $cls->name }} {{ $cls->unit ? '('.$cls->unit->name.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Tanggal</label>
                    <input type="date" name="date" class="form-control border-0 shadow-sm rounded-3" value="{{ request('date', (auth()->user()->role != 'administrator' && auth()->user()->role != 'staff' && !auth()->user()->isKurikulum()) ? now()->toDateString() : '') }}">
                </div>
                <div class="col-md-1 d-flex gap-2">
                    <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center flex-grow-1 py-2 shadow-sm rounded-3">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('class-checkins.index') }}" class="btn btn-secondary d-flex align-items-center justify-content-center px-3 py-2 shadow-sm rounded-3">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Data Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover custom-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Waktu Check-in</th>
                            @if(in_array(auth()->user()->role, ['administrator', 'staff']))
                                <th>Guru Pengampu</th>
                            @endif
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Status Kehadiran</th>
                            <th>Detail / Foto</th>
                            @if(in_array(auth()->user()->role, ['administrator', 'staff']))
                                <th class="pe-4 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($checkins as $checkin)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="time-box bg-light p-2 rounded-3 text-center me-3" style="min-width: 60px;">
                                            <div class="small fw-bold text-primary">{{ $checkin->checkin_time->format('H:i') }}</div>
                                            <div class="tiny text-muted">{{ $checkin->checkin_time->format('M d') }}</div>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $checkin->checkin_time->translatedFormat('l') }}</div>
                                            <div class="small text-muted">{{ $checkin->checkin_time->format('Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                @if(in_array(auth()->user()->role, ['administrator', 'staff']))
                                    <td>
                                        <div class="fw-bold">{{ $checkin->user->name ?? '-' }}</div>
                                        <div class="small text-muted">{{ $checkin->user->email ?? '-' }}</div>
                                    </td>
                                @endif
                                <td>
                                    <span class="badge bg-soft-primary px-3 py-2 text-primary rounded-pill">
                                        <i class="bi bi-people-fill me-1"></i> {{ $checkin->schedule->schoolClass->name ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $checkin->schedule->subject->name ?? '-' }}</div>
                                </td>
                                <td>
                                    @php
                                        $statusClass = '';
                                        $statusLabel = '';
                                        switch($checkin->status) {
                                            case 'ontime': $statusClass = 'bg-soft-success text-success'; $statusLabel = 'Tepat Waktu'; break;
                                            case 'late': $statusClass = 'bg-soft-warning text-warning'; $statusLabel = 'Terlambat'; break;
                                            case 'substitute': $statusClass = 'bg-soft-purple text-purple'; $statusLabel = 'Badal / Invaler'; break;
                                            case 'absent': $statusClass = 'bg-soft-danger text-danger'; $statusLabel = 'Tidak Masuk'; break;
                                            default: $statusClass = 'bg-soft-secondary text-secondary'; $statusLabel = ucfirst($checkin->status);
                                        }
                                    @endphp
                                    <span class="badge {{ $statusClass }} border px-3 py-2 rounded-3">
                                        <i class="bi bi-dot fs-5"></i> {{ $statusLabel }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($checkin->photo)
                                            <button type="button" class="btn btn-sm btn-icon-round shadow-sm" data-bs-toggle="modal" data-bs-target="#photoModal{{ $checkin->id }}" title="Lihat Foto Bukti">
                                                <i class="bi bi-camera-fill text-primary"></i>
                                            </button>

                                            <!-- Modal Preview -->
                                            <div class="modal fade" id="photoModal{{ $checkin->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                                        <div class="modal-header border-0 bg-dark text-white p-3">
                                                            <h6 class="modal-title fs-6"><i class="bi bi-image me-2"></i>Bukti Foto Check-in</h6>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body p-0 bg-dark">
                                                            <img src="{{ asset('storage/' . $checkin->photo) }}" class="img-fluid w-100" style="max-height: 80vh; object-fit: contain;">
                                                        </div>
                                                        @if($checkin->notes)
                                                        <div class="modal-footer border-0 p-3 bg-white">
                                                            <div class="small text-muted w-100">
                                                                <i class="bi bi-info-circle me-1"></i><strong>Catatan:</strong> {{ $checkin->notes }}
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="small text-truncate text-muted" style="max-width: 150px;" title="{{ $checkin->notes }}">
                                            {{ $checkin->notes ?? '-' }}
                                        </div>
                                    </div>
                                </td>
                                @if(in_array(auth()->user()->role, ['administrator', 'staff']))
                                    <td class="pe-4 text-center">
                                        <form action="{{ route('class-checkins.destroy', $checkin->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data checkin ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-danger rounded-circle p-2" title="Hapus Data">
                                                <i class="bi bi-trash fs-6"></i>
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="bi bi-calendar-x fs-1 text-muted opacity-25"></i>
                                    </div>
                                    <h5 class="text-muted fw-bold">Belum Ada Riwayat Check-in</h5>
                                    <p class="text-muted small">Lakukan check-in sekarang untuk mulai mencatat kehadiran.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $checkins->links() }}
    </div>
</div>

@push('styles')
<style>
    .bg-light-soft { background-color: #f9fbff; }
    .bg-soft-primary { background-color: #eef2ff; color: #4e73df; }
    .bg-soft-success { background-color: #ecfdf5; color: #10b981; border-color: #d1fae5; }
    .bg-soft-warning { background-color: #fffbeb; color: #f59e0b; border-color: #fef3c7; }
    .bg-soft-danger { background-color: #fef2f2; color: #ef4444; border-color: #fee2e2; }
    .bg-soft-purple { background-color: #f5f3ff; color: #8b5cf6; border-color: #ede9fe; }
    .bg-soft-secondary { background-color: #f3f4f6; color: #6b7280; border-color: #e5e7eb; }

    .btn-white { background: white; color: #333; }
    .btn-white:hover { background: #f8f9fa; }

    .btn-icon-round {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid #eef2ff;
    }
    .btn-icon-round:hover {
        background: #eef2ff;
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }

    .btn-soft-danger {
        background-color: #fff2f2;
        color: #e74a3b;
        border: none;
    }
    .btn-soft-danger:hover {
        background-color: #e74a3b;
        color: white;
    }

    .stats-card {
        transition: transform 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .stats-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-table thead th {
        background-color: #f8f9fc;
        border-top: none;
        border-bottom: 2px solid #e3e6f0;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        color: #4e73df;
        padding: 15px;
    }
    .custom-table tbody td {
        padding: 1rem 15px;
        border-bottom: 1px solid #f1f1f1;
    }
    
    .time-box .tiny { font-size: 0.65rem; }
    .text-purple { color: #8b5cf6; }

    .rounded-4 { border-radius: 1rem !important; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const unitFilter = document.getElementById('unitFilter');
        const classFilter = document.getElementById('classFilter');
        
        if(unitFilter && classFilter) {
            const classOptions = Array.from(classFilter.options);
            
            function filterClasses() {
                const selectedUnit = unitFilter.value;
                const currentClass = classFilter.value;
                
                classOptions.forEach(option => {
                    if (option.value === "") return;
                    const unitId = option.getAttribute('data-unit');
                    if (!selectedUnit || unitId == selectedUnit) {
                        option.style.display = '';
                    } else {
                        option.style.display = 'none';
                    }
                });
                
                const selectedOption = classFilter.querySelector(`option[value="${currentClass}"]`);
                if(currentClass && selectedOption && selectedOption.style.display === 'none'){
                    classFilter.value = "";
                }
            }
            
            unitFilter.addEventListener('change', filterClasses);
            filterClasses();
        }
    });
</script>
@endpush
@endsection
