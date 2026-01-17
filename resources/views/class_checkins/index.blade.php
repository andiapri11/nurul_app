@extends('layouts.app')

@section('title', 'Riwayat Check-in Kelas')

@section('content')


<div class="container-fluid px-4 py-5" style="max-width: 1400px;">
    <!-- Modern Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <div class="d-flex align-items-center mb-1">
                <span class="badge bg-indigo-100 text-indigo-700 px-3 py-1 rounded-pill small fw-bold me-2" style="background-color: #e0e7ff; color: #4338ca;">Monitoring v2.0</span>
                <span class="text-muted small">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="page-header h1 mb-1">Riwayat Kehadiran</h1>
            <p class="text-muted mb-0">Pantau aktivitas KBM secara real-time dan akurat.</p>
        </div>
        
        <div class="d-none d-md-flex gap-2">
            @if(in_array(auth()->user()->role, ['administrator', 'staff']))
                <a href="{{ route('class-checkins.export-pdf', request()->all()) }}" class="btn-custom-secondary text-decoration-none d-flex align-items-center">
                    <i class="bi bi-download me-2"></i> Export Data
                </a>
            @endif
            <a href="{{ route('class-checkins.create') }}" class="btn-custom-primary text-decoration-none d-flex align-items-center">
                <i class="bi bi-plus-lg me-2"></i> Input Check-in
            </a>
        </div>
    </div>
    
    <!-- Mobile Action Buttons (Visible only on Mobile) -->
    <div class="d-md-none d-flex flex-column gap-2 mb-4">
        <a href="{{ route('class-checkins.create') }}" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm d-flex justify-content-center align-items-center">
            <i class="bi bi-plus-lg me-2"></i> Input Check-in Baru
        </a>
        @if(in_array(auth()->user()->role, ['administrator', 'staff']))
            <a href="{{ route('class-checkins.export-pdf', request()->all()) }}" class="btn btn-outline-secondary w-100 py-2 rounded-pill d-flex justify-content-center align-items-center">
                <i class="bi bi-download me-2"></i> Export Data (PDF)
            </a>
        @endif
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="bg-white p-3 rounded-4 border d-flex align-items-center justify-content-between shadow-sm">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Total Hari Ini</h6>
                    <h2 class="h3 fw-bold text-dark mb-0">{{ $totalTodayCount }}</h2>
                </div>
                <div class="bg-blue-50 p-3 rounded-3" style="background-color: #eff6ff;">
                    <i class="bi bi-calendar-check fs-4 text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Refined Filters -->
    <div class="filter-card shadow-sm">
        <form action="{{ route('class-checkins.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-6 col-md-2">
                    <label class="form-label-custom">Tahun Ajaran</label>
                    <select name="academic_year_id" class="form-select-custom w-100" onchange="this.form.submit()">
                        <option value="">Semua (Aktif)</option>
                         @foreach($academicYears as $year)
                             <option value="{{ $year->id }}" 
                                 {{ (request('academic_year_id') == $year->id || (!request('academic_year_id') && $year->id == $activeYearId)) ? 'selected' : '' }}>
                                 {{ $year->start_year }}/{{ $year->end_year }}
                             </option>
                         @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label-custom">Unit</label>
                    <select name="unit_id" id="unitFilter" class="form-select-custom w-100">
                        <option value="">Semua Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Break to new row on mobile for better spacing -->
                <div class="col-12 col-md-4">
                    <label class="form-label-custom">Kelas</label>
                    <select name="class_id" id="classFilter" class="form-select-custom w-100">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}" data-unit="{{ $cls->unit_id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
                                {{ $cls->name }} {{ $cls->unit ? '('.$cls->unit->name.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label-custom">Tanggal</label>
                    <input type="date" name="date" class="form-control-custom w-100" value="{{ request('date', now()->toDateString()) }}">
                </div>
                <div class="col-12 col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark w-100 rounded-3 py-2" title="Cari Data">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Desktop View: Elegant Table -->
    <div class="desktop-table-container mb-4">
        <table class="table custom-table mb-0 w-100">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 15%;">Waktu</th>
                    @if(in_array(auth()->user()->role, ['administrator', 'staff']))
                        <th style="width: 20%;">Pengajar</th>
                    @endif
                    <th style="width: 15%;">Kelas</th>
                    <th style="width: 20%;">Mata Pelajaran</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 10%;">Bukti</th>
                    @if(in_array(auth()->user()->role, ['administrator', 'staff']))
                        <th class="text-end pe-4" style="width: 5%;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($checkins as $checkin)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-dark">{{ $checkin->checkin_time->format('H:i') }}</div>
                        <div class="small text-muted">{{ $checkin->checkin_time->format('d M Y') }}</div>
                    </td>
                    @if(in_array(auth()->user()->role, ['administrator', 'staff']))
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="user-avatar-sm me-2">
                                {{ substr($checkin->user->name ?? '?', 0, 1) }}
                            </div>
                            <span class="fw-medium">{{ $checkin->user->name ?? '-' }}</span>
                        </div>
                    </td>
                    @endif
                    <td>
                        <span class="badge bg-light text-dark border fw-normal">
                            {{ $checkin->schedule->schoolClass->name ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-medium text-dark">{{ $checkin->schedule->subject->name ?? '-' }}</div>
                    </td>
                    <td>
                        @php
                            $s = $checkin->status;
                            $cls = 'status-ontime';
                            if($s === 'late') $cls = 'status-late';
                            if($s === 'absent') $cls = 'status-absent';
                            if($s === 'substitute') $cls = 'status-substitute';
                        @endphp
                        <span class="status-badge {{ $cls }}">
                            {{ ucfirst($checkin->status === 'ontime' ? 'Hadir' : ($checkin->status === 'late' ? 'Telat' : ucfirst($checkin->status))) }}
                        </span>
                    </td>
                    <td>
                        @if($checkin->photo)
                            <button class="btn btn-sm btn-light border rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modal{{ $checkin->id }}">
                                <i class="bi bi-image"></i>
                            </button>
                             <!-- Modal Desktop -->
                             <div class="modal fade" id="modal{{ $checkin->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content overflow-hidden rounded-4 border-0">
                                        <div class="position-relative">
                                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3 bg-white p-2 shadow-sm opacity-100" data-bs-dismiss="modal"></button>
                                            <img src="{{ asset('storage/' . $checkin->photo) }}" class="w-100">
                                        </div>
                                        @if($checkin->notes)
                                            <div class="p-3 bg-light border-top text-muted small">
                                                <strong>Note:</strong> {{ $checkin->notes }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    @if(in_array(auth()->user()->role, ['administrator', 'staff']))
                    <td class="text-end pe-4">
                        <form action="{{ route('class-checkins.destroy', $checkin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus entri ini?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm text-danger hover-bg-red rounded-circle" style="width: 32px; height: 32px;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="empty-state">
                            <i class="bi bi-inbox empty-icon"></i>
                            <h6 class="text-muted fw-bold">Belum ada data check-in</h6>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile View: Modern Cards -->
    <div class="mobile-list-container">
        @forelse ($checkins as $checkin)
            <div class="checkin-card shadow-sm">
                <div class="checkin-card-header">
                    <div class="d-flex align-items-center">
                        <span class="time-badge me-2">{{ $checkin->checkin_time->format('H:i') }}</span>
                        <span class="text-muted small">{{ $checkin->checkin_time->format('d/m') }}</span>
                    </div>
                    
                    @php
                        $s = $checkin->status;
                        $cls = 'status-ontime';
                        if($s === 'late') $cls = 'status-late';
                        if($s === 'absent') $cls = 'status-absent';
                        if($s === 'substitute') $cls = 'status-substitute';
                    @endphp
                    <span class="status-badge {{ $cls }}">
                        {{ ucfirst($checkin->status === 'ontime' ? 'Hadir' : ($checkin->status === 'late' ? 'Telat' : ucfirst($checkin->status))) }}
                    </span>
                </div>
                
                <h6 class="card-main-title">{{ $checkin->schedule->subject->name ?? 'Mata Pelajaran Tidak Dikenal' }}</h6>
                
                <div class="card-info-row">
                    <i class="bi bi-people-fill"></i>
                    <span>Kelas {{ $checkin->schedule->schoolClass->name ?? '-' }}</span>
                </div>
                
                @if(in_array(auth()->user()->role, ['administrator', 'staff']))
                <div class="card-info-row">
                    <i class="bi bi-person-fill"></i>
                    <span>{{ $checkin->user->name ?? 'Guru Tidak Dikenal' }}</span>
                </div>
                @endif
                
                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                    @if($checkin->photo)
                        <button class="btn btn-sm btn-light border rounded-pill px-3 fw-bold small" data-bs-toggle="modal" data-bs-target="#mobileModal{{ $checkin->id }}">
                            <i class="bi bi-eye me-1"></i> Bukti Foto
                        </button>

                         <!-- Modal Mobile -->
                         <div class="modal fade" id="mobileModal{{ $checkin->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content overflow-hidden rounded-4 border-0">
                                    <div class="position-relative">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-3 bg-white p-2 shadow-sm opacity-100" data-bs-dismiss="modal"></button>
                                        <img src="{{ asset('storage/' . $checkin->photo) }}" class="w-100">
                                    </div>
                                    @if($checkin->notes)
                                        <div class="p-3 bg-light border-top text-muted small">
                                            {{ $checkin->notes }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <span class="text-muted small fst-italic">Tanpa Foto</span>
                    @endif

                    @if(in_array(auth()->user()->role, ['administrator', 'staff']))
                        <form action="{{ route('class-checkins.destroy', $checkin->id) }}" method="POST" onsubmit="return confirm('Hapus entri ini?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm text-danger fw-bold small">HAPUS</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bi bi-journal-x empty-icon"></i>
                <h6 class="text-muted">Tidak ada riwayat.</h6>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $checkins->links('pagination::bootstrap-4') }}
    </div>
    

</div>


@endsection

@push('styles')
<style>
    /* Custom refined CSS for a SaaS-like feel */
    :root {
        --primary-soft: #eef2ff;
        --primary-dark: #4338ca;
        --text-main: #1f2937;
        --text-muted: #6b7280;
        --border-color: #e5e7eb;
    }

    .page-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.025em;
    }

    .btn-custom-primary {
        background-color: var(--primary-dark);
        color: white;
        border-radius: 9999px;
        padding: 0.625rem 1.25rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(67, 56, 202, 0.1), 0 2px 4px -1px rgba(67, 56, 202, 0.06);
    }

    .btn-custom-primary:hover {
        background-color: #3730a3;
        transform: translateY(-1px);
        box-shadow: 10px 15px -3px rgba(67, 56, 202, 0.1), 0 4px 6px -2px rgba(67, 56, 202, 0.05);
    }
    
    .btn-custom-secondary {
        background-color: white;
        color: var(--text-main);
        border: 1px solid var(--border-color);
        border-radius: 9999px;
        padding: 0.625rem 1.25rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    
    .btn-custom-secondary:hover {
        border-color: #d1d5db;
        background-color: #f9fafb;
    }

    /* Filters specific */
    .filter-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-label-custom {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .form-select-custom, .form-control-custom {
        background-color: #f9fafb;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        color: var(--text-main);
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-select-custom:focus, .form-control-custom:focus {
        border-color: var(--primary-dark);
        background-color: white;
        box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.1);
        outline: 0;
    }

    /* Desktop Table */
    .desktop-table-container {
        display: none;
    }
    
    @media (min-width: 768px) {
        .desktop-table-container {
            display: block;
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .custom-table th {
            background-color: #f9fafb;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .custom-table td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            color: var(--text-main);
            border-bottom: 1px solid var(--border-color);
        }
        
        .custom-table tr:last-child td {
            border-bottom: none;
        }
    }

    /* Mobile Cards */
    .mobile-list-container {
        display: block;
    }
    
    @media (min-width: 768px) {
        .mobile-list-container {
            display: none;
        }
    }

    .checkin-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        padding: 1.25rem;
        margin-bottom: 1rem;
        position: relative;
    }
    
    .checkin-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }
    
    .time-badge {
        font-family: 'Monaco', 'Consolas', monospace;
        font-weight: 600;
        font-size: 0.85rem;
        background-color: var(--primary-soft);
        color: var(--primary-dark);
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
    }
    
    .status-badge {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
    }

    .status-ontime { background-color: #ecfdf5; color: #059669; }
    .status-late { background-color: #fffbeb; color: #d97706; }
    .status-absent { background-color: #fef2f2; color: #dc2626; }
    .status-substitute { background-color: #f5f3ff; color: #7c3aed; }

    .card-info-row {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        color: var(--text-muted);
        font-size: 0.875rem;
    }
    
    .card-info-row i {
        width: 20px;
        margin-right: 0.5rem;
        color: #9ca3af;
    }
    
    .card-main-title {
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 0.25rem;
    }
    
    .user-avatar-sm {
        width: 24px;
        height: 24px;
        border: 1px solid white;
        border-radius: 50%;
        background: #e0e7ff;
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: bold;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
    }
    
    .empty-icon {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }


</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple JS for Interactivity specific to this page
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
