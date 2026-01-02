@extends('layouts.app')

@section('title', 'Pilih Kelas - Administrator')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-grid-3x3-gap-fill me-2"></i> Pilih Kelas untuk Input Absensi</h5>
        </div>
        <div class="card-body">
            @foreach($units as $unit)
                <h5 class="mt-3 border-bottom pb-2">{{ $unit->name }}</h5>
                <div class="row g-3">
                    @forelse($unit->classes as $cls)
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('wali-kelas.index', ['class_id' => $cls->id]) }}" class="text-decoration-none">
                                <div class="card h-100 border hover-shadow">
                                    <div class="card-body text-center p-3">
                                        <h4 class="mb-1 text-primary">{{ $cls->name }}</h4>
                                        <small class="text-muted">{{ $cls->academicYear ? $cls->academicYear->name : '-' }}</small>
                                        <div class="mt-2">
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-person me-1"></i> {{ $cls->teacher ? $cls->teacher->name : 'No Wali Kelas' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-light p-2 text-center text-primary fw-bold" style="font-size: 0.8rem;">
                                        Kelola Absensi <i class="bi bi-arrow-right"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-muted small">Belum ada kelas di unit ini.</div>
                    @endforelse
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
.hover-shadow:hover {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    transform: translateY(-2px);
    transition: all .2s;
}
</style>
@endsection
