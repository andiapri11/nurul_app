@extends('layouts.app')

@section('content')
<style>
    .teacher-card {
        border: none;
        border-top: 3px solid #007bff; 
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
        border-radius: 6px;
        background: #fff;
        height: 100%;
        transition: transform 0.2s;
    }
    .teacher-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .teacher-photo-container {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #e9ecef;
    }
    .teacher-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .teacher-info {
        padding-left: 12px;
    }
    .teacher-name {
        font-weight: 600;
        font-size: 1.05rem;
        color: #333;
        margin-bottom: 0;
    }
    .teacher-nip {
        font-size: 0.8rem;
        color: #888;
    }
    .section-title {
        font-size: 0.85rem;
        color: #555;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 15px;
        margin-bottom: 8px;
        display: block;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 4px;
    }
    .assignment-item {
        font-size: 0.85rem;
        padding: 4px 0;
        border-bottom: 1px dashed #f0f0f0;
        display: flex;
        justify-content: space-between;
    }
    .assignment-item:last-child {
        border-bottom: none;
    }
    .tag-badge {
        font-size: 0.75rem;
        padding: 2px 8px;
        border-radius: 12px;
        background: #eef2f7;
        color: #475569;
        font-weight: 600;
    }
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 fw-bold"><i class="bi bi-person-video3 me-2 text-primary"></i> Distribusi Tugas Mengajar</h1>
            </div>
            <div class="col-sm-6 text-sm-end">
                <div class="btn-group shadow-sm">
                    @foreach($academicYears as $ay)
                        @if($ay->status == 'active')
                            <span class="btn btn-sm btn-outline-primary active">Tahun Aktif: {{ $ay->start_year }}/{{ $ay->end_year }}</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        {{-- Filters --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body p-3">
                <form action="{{ route('curriculum.teaching-assignments.index') }}" method="GET" class="row g-2">
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Cari Nama / NIP..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="unit_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Unit</option>
                            @foreach($allowedUnits as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                    </div>
                    @if(request()->anyFilled(['search', 'unit_id']))
                        <div class="col-md-1">
                            <a href="{{ route('curriculum.teaching-assignments.index') }}" class="btn btn-sm btn-light border w-100">Reset</a>
                        </div>
                    @endif
                    <div class="col-md-2">
                        <a href="{{ route('curriculum.teaching-assignments.export', request()->query()) }}" target="_blank" class="btn btn-sm btn-outline-danger w-100 shadow-sm">
                            <i class="bi bi-file-earmark-pdf"></i> Export Rekap
                        </a>
                    </div>
                    <div class="col-md-3 ms-auto text-end">
                        @if(request('global'))
                            <a href="{{ route('curriculum.teaching-assignments.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-filter"></i> Kembali ke Unit Saya
                            </a>
                        @else
                            <a href="{{ route('curriculum.teaching-assignments.index', ['global' => 1]) }}" class="btn btn-sm btn-outline-info shadow-sm">
                                <i class="bi bi-globe"></i> Cari Guru Lintas Unit (Discovery)
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if(request('global'))
            <div class="alert alert-info border-0 shadow-sm mb-4">
                <i class="bi bi-info-circle-fill me-2"></i> <strong>Mode Discovery Aktif:</strong> Bapak bisa mencari guru dari unit mana saja di sekolah untuk kemudian diberikan tugas mengajar di unit Bapak.
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            @forelse($teachers as $teacher)
                <div class="col-md-4 mb-4">
                    <div class="teacher-card p-3 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="teacher-photo-container shadow-sm">
                                <img src="{{ $teacher->photo ? asset('photos/' . $teacher->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($teacher->name) . '&background=random&size=100' }}" class="teacher-photo">
                            </div>
                            <div class="teacher-info">
                                <div class="teacher-name text-truncate" style="max-width: 200px;" title="{{ $teacher->name }}">{{ $teacher->name }}</div>
                                <div class="teacher-nip">{{ $teacher->nip ?? '-' }}</div>
                                <span class="badge bg-light text-primary border" style="font-size: 0.7rem;">{{ $teacher->unit->name ?? 'Lintas Unit' }}</span>
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <span class="section-title">Mata Pelajaran Diampu</span>
                            @php
                                $assignments = $teacher->teachingAssignments;
                            @endphp
                            
                            @if($assignments->count() > 0)
                                <div class="assignments-list">
                                    @foreach($assignments->take(5) as $assign)
                                        <div class="assignment-item">
                                            <span class="text-dark fw-500">{{ $assign->subject->name ?? '-' }}</span>
                                            <span class="tag-badge">{{ $assign->schoolClass->name ?? '-' }}</span>
                                        </div>
                                    @endforeach
                                    @if($assignments->count() > 5)
                                        <div class="text-center mt-2">
                                            <small class="text-muted">+ {{ $assignments->count() - 5 }} lainnya</small>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p class="text-muted small italic py-2 text-center">Belum ada tugas mengajar.</p>
                            @endif
                        </div>

                        <div class="mt-3 pt-3 border-top">
                            <a href="{{ route('curriculum.teaching-assignments.edit', $teacher->id) }}" class="btn btn-sm btn-primary w-100 shadow-sm">
                                <i class="bi bi-pencil-square me-1"></i> Edit Tugas Mengajar
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card shadow-sm border-0 py-5 text-center">
                        <i class="bi bi-people display-1 text-light mb-3"></i>
                        <h4 class="text-muted">Tidak ada data guru ditemukan.</h4>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $teachers->links() }}
        </div>
    </div>
</div>
@endsection
