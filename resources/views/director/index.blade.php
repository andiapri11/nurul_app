@extends('layouts.app')

@section('title', 'Dashboard Direktur')

@section('content')
<div class="container-fluid p-4">
    <!-- Welcome Banner with Glassmorphism feel -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px; background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%);">
                <div class="card-body p-4 p-md-5 text-white position-relative">
                    <div class="position-relative z-1">
                        <h2 class="fw-bold mb-2">Selamat Datang, {{ Auth::user()->name }}</h2>
                        <p class="mb-0 text-white-50 fs-5">Berikut adalah ringkasan eksekutif perkembangan sekolah hari ini.</p>
                    </div>
                    <!-- Decorative Icon -->
                    <div class="position-absolute end-0 top-50 translate-middle-y me-5 d-none d-md-block opacity-25">
                        <i class="bi bi-pie-chart-fill" style="font-size: 8rem; color: rgba(255,255,255,0.2);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview Cards -->
    <div class="row g-4 mb-4">
        <!-- Students -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 card-hover-lift" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="icon-square bg-primary bg-opacity-10 text-primary rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <span class="badge bg-light text-muted border rounded-pill">Aktif</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark">{{ number_format($stats['total_students']) }}</h3>
                    <p class="text-secondary small mb-3">Total Siswa Terdaftar</p>
                    
                    <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-light text-primary border-primary border-opacity-25 w-100 rounded-pill fw-medium hover-primary">
                        View Details <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Teachers -->
        <div class="col-md-6 col-lg-3">
             <div class="card border-0 shadow-sm h-100 card-hover-lift" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                         <div class="icon-square bg-success bg-opacity-10 text-success rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-person-badge-fill fs-4"></i>
                        </div>
                        <span class="badge bg-light text-muted border rounded-pill">Guru</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark">{{ number_format($stats['total_teachers']) }}</h3>
                     <p class="text-secondary small mb-3">Total Tenaga Pengajar</p>
                    
                    <a href="{{ route('director.employees') }}" class="btn btn-sm btn-outline-light text-success border-success border-opacity-25 w-100 rounded-pill fw-medium hover-success">
                        View Directory <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Units -->
        <div class="col-md-6 col-lg-3">
             <div class="card border-0 shadow-sm h-100 card-hover-lift" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                         <div class="icon-square bg-warning bg-opacity-10 text-warning rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-building-fill fs-4"></i>
                        </div>
                         <span class="badge bg-light text-muted border rounded-pill">Unit</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark">{{ number_format($stats['total_units']) }}</h3>
                     <p class="text-secondary small mb-3">Unit Sekolah</p>
                     <!-- Placeholder button for alignment -->
                     <button class="btn btn-sm btn-light w-100 rounded-pill fw-medium disabled opacity-50">Global Stats</button>
                </div>
            </div>
        </div>

        <!-- Classes -->
        <div class="col-md-6 col-lg-3">
             <div class="card border-0 shadow-sm h-100 card-hover-lift" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                         <div class="icon-square bg-info bg-opacity-10 text-info rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-bounding-box-circles fs-4"></i>
                        </div>
                        <span class="badge bg-light text-muted border rounded-pill">Kelas</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark">{{ number_format($stats['total_classes']) }}</h3>
                     <p class="text-secondary small mb-3">Rombongal Belajar</p>
                      <button class="btn btn-sm btn-light w-100 rounded-pill fw-medium disabled opacity-50">Global Stats</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content: Unit Details Table -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; overflow: hidden;">
                 <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Statistik Per Unit</h5>
                        <small class="text-muted">Breakdown data per unit sekolah</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle p-2" type="button">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="bg-light text-secondary small text-uppercase fw-bold" style="letter-spacing: 0.5px;">
                            <tr>
                                <th class="px-4 py-3 border-0">Unit Sekolah</th>
                                <th class="py-3 text-center border-0">Siswa</th>
                                <th class="py-3 text-center border-0">Kelas</th>
                                <th class="py-3 text-center border-0">Guru</th>
                                <th class="px-4 py-3 text-end border-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($units as $unit)
                            <tr class="border-bottom border-light">
                                <td class="px-4 py-3 border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px;">
                                            <span class="fw-bold">{{ substr($unit->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <span class="d-block fw-bold text-dark">{{ $unit->name }}</span>
                                            <small class="text-muted" style="font-size: 0.75rem;">Active</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center py-3 border-0">
                                    <span class="fw-bold text-dark">{{ $unit->students_count }}</span>
                                </td>
                                <td class="text-center py-3 border-0">
                                    <span class="text-secondary">{{ $unit->classes_count }}</span>
                                </td>
                                <td class="text-center py-3 border-0">
                                     <span class="text-secondary">{{ $unit->teacher_count }}</span>
                                </td>
                                <td class="text-end px-4 py-3 border-0">
                                    <a href="{{ route('students.index', ['unit_id' => $unit->id]) }}" class="btn btn-icon btn-sm btn-light rounded-circle text-primary" data-bs-toggle="tooltip" title="Lihat Siswa Unit Ini">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar: Recent Activity -->
        <div class="col-lg-4">
             <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Aktivitas Dokumen</h5>
                    <small class="text-muted">Update permintaan dokumen terbaru</small>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="list-group list-group-flush border-0">
                        @forelse($recentSubmissions as $sub)
                            <div class="list-group-item border-0 px-4 py-3 d-flex align-items-start card-hover-bg transition-base">
                                <div class="me-3 mt-1 position-relative">
                                    @if($sub->user->photo)
                                        <img src="{{ asset('photos/' . $sub->user->photo) }}" class="rounded-circle shadow-sm border border-2 border-white" width="45" height="45" style="object-fit:cover;">
                                    @else
                                        <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-sm border border-2 border-white" style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            {{ substr($sub->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <!-- Status Indicator Dot -->
                                    @php
                                        $dotClass = match($sub->status) {
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            'validated' => 'bg-info',
                                            default => 'bg-warning'
                                        };
                                    @endphp
                                    <span class="position-absolute bottom-0 end-0 translate-middle p-1 border border-light rounded-circle {{ $dotClass }}">
                                        <span class="visually-hidden">New alerts</span>
                                    </span>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex justify-content-between align-items-baseline mb-1">
                                        <span class="fw-bold text-dark text-truncate d-block" style="max-width: 130px;" title="{{ $sub->user->name }}">{{ $sub->user->name }}</span>
                                        <small class="text-muted ms-2 text-end flex-shrink-0" style="font-size: 0.7rem;">{{ $sub->updated_at->diffForHumans(null, true) }}</small>
                                    </div>
                                    <p class="mb-1 text-secondary small text-truncate fw-medium">
                                        {{ $sub->request->title ?? 'Untitled Document' }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between mt-2">
                                        <span class="badge border rounded-pill fw-normal text-muted bg-light px-2 py-1" style="font-size: 0.65rem;">
                                            {{ $sub->user->jabatanUnits->first()->unit->name ?? 'Unknown Unit' }}
                                        </span>
                                        <span class="badge {{ $dotClass }} rounded-pill" style="font-size: 0.65rem;">{{ ucfirst($sub->status) }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 px-4">
                                <i class="bi bi-clipboard-x text-muted opacity-25" style="font-size: 3rem;"></i>
                                <h6 class="text-muted mt-3 fw-medium">Belum ada aktivitas</h6>
                                <p class="text-muted small">Update dokumen akan muncul di sini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
             </div>
        </div>
    </div>
</div>

<style>
    .card-hover-lift {
        transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.2s ease;
    }
    .card-hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
    }
    .card-hover-bg:hover {
        background-color: #f9fafb;
        cursor: default;
    }
    .hover-primary:hover {
        background-color: var(--bs-primary) !important;
        color: white !important;
    }
    .hover-success:hover {
        background-color: var(--bs-success) !important;
        color: white !important;
    }
    .transition-base {
        transition: all 0.2s ease-in-out;
    }
    .bg-gradient-primary {
         background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endsection
