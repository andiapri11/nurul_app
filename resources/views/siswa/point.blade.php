@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
    }

    .hero-section {
        background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
        border-radius: 2rem;
        padding: 3rem 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        top: -20%;
        left: -5%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        border-radius: 1.5rem;
    }

    .point-badge {
        width: 60px;
        height: 60px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.25rem;
    }

    .achievement-card {
        border-left: 5px solid #10b981;
        transition: transform 0.3s ease;
    }

    .achievement-card:hover { transform: scale(1.02); }

    .violation-card {
        border-left: 5px solid #ef4444;
        transition: transform 0.3s ease;
    }

    .violation-card:hover { transform: scale(1.02); }

    .nav-pills-custom .nav-link {
        color: #64748b;
        font-weight: 700;
        padding: 0.8rem 1.2rem;
        border-radius: 0.8rem;
        transition: all 0.3s ease;
        background: white;
        margin-right: 0.8rem;
        border: 1px solid #e2e8f0;
    }

    .nav-pills-custom .nav-link.active {
        background: #4338ca;
        color: white;
        border-color: #4338ca;
        box-shadow: 0 10px 15px -3px rgba(67, 56, 202, 0.3);
    }

    .ls-1 { letter-spacing: 1px; }

    .timeline-year {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 2rem;
    }

    .timeline-year::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }

    .timeline-year::after {
        content: '';
        position: absolute;
        left: -4px;
        top: 10px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #4338ca;
    }
</style>
@endpush

@section('content')
<div class="app-content pt-4">
    <div class="container-xl">
        {{-- Hero Section --}}
        <div class="hero-section shadow-lg">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h6 class="text-uppercase fw-bold ls-1 opacity-75 mb-2">Poin & Prestasi Siswa</h6>
                    <h1 class="display-5 fw-extrabold mb-0">Track Record <br> Kedisiplinan & Prestasi.</h1>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <div class="d-flex flex-wrap justify-content-md-end gap-3">
                        <div class="glass-card p-3 text-center" style="min-width: 120px;">
                            <div class="h3 fw-black text-white mb-0">{{ $currentViolationPoints }}</div>
                            <div class="small fw-bold text-uppercase ls-1 opacity-75" style="font-size: 0.6rem;">Poin Tahun Ini</div>
                        </div>
                        <div class="glass-card p-3 text-center" style="min-width: 120px;">
                            <div class="h3 fw-black text-white mb-0">{{ $totalViolationPoints }}</div>
                            <div class="small fw-bold text-uppercase ls-1 opacity-75" style="font-size: 0.6rem;">Total Poin</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="row g-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <ul class="nav nav-pills nav-pills-custom" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-current-tab" data-bs-toggle="pill" data-bs-target="#pills-current" type="button" role="tab">
                                <i class="bi bi-star-fill me-2"></i> Data Terkini ({{ ($activeYear->name ?? 'Aktif') }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-history-tab" data-bs-toggle="pill" data-bs-target="#pills-history" type="button" role="tab">
                                <i class="bi bi-clock-history me-2"></i> Track Record (Riwayat)
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="pills-tabContent">
                    {{-- Current Data Tab --}}
                    <div class="tab-pane fade show active" id="pills-current" role="tabpanel">
                        <div class="row">
                            {{-- Current Achievements --}}
                            <div class="col-lg-6 mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success me-2">
                                        <i class="bi bi-award-fill"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Prestasi Tahun Ini</h5>
                                </div>
                                <div class="row g-3">
                                    @forelse($currentAchievements as $achievement)
                                        <div class="col-12">
                                            <div class="card glass-card achievement-card border-0">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h6 class="fw-bold text-dark mb-0">{{ $achievement->achievement_name }}</h6>
                                                        <span class="badge bg-success rounded-pill small">{{ $achievement->level }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-end">
                                                        <div class="small text-muted">
                                                            <i class="bi bi-calendar-event me-1"></i> {{ $achievement->date->translatedFormat('d F Y') }}<br>
                                                            <strong class="text-dark">Peringkat: {{ $achievement->rank ?: '-' }}</strong>
                                                        </div>
                                                        @if($achievement->proof)
                                                            <a href="{{ asset('storage/' . $achievement->proof) }}" target="_blank" class="btn btn-sm btn-light rounded-pill"><i class="bi bi-eye"></i> Bukti</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="card glass-card border-0 py-4 text-center">
                                                <p class="text-muted mb-0 small">Belum ada prestasi di tahun pelajaran ini.</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Current Violations --}}
                            <div class="col-lg-6 mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-danger bg-opacity-10 p-2 rounded-3 text-danger me-2">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Pelanggaran Tahun Ini</h5>
                                </div>
                                <div class="row g-3">
                                    @forelse($currentViolations as $violation)
                                        <div class="col-12">
                                            <div class="card glass-card violation-card border-0">
                                                <div class="card-body p-3">
                                                    <div class="row align-items-center">
                                                        <div class="col-2 text-center">
                                                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2 fw-bold small">
                                                                {{ $violation->points }}
                                                            </div>
                                                        </div>
                                                        <div class="col-10">
                                                            <div class="d-flex justify-content-between">
                                                                <h6 class="fw-bold text-dark mb-1">{{ $violation->description }}</h6>
                                                                <span class="badge {{ $violation->violation_type == 'Berat' ? 'bg-danger' : ($violation->violation_type == 'Sedang' ? 'bg-warning text-dark' : 'bg-secondary') }} small">
                                                                    {{ $violation->violation_type }}
                                                                </span>
                                                            </div>
                                                            <div class="d-flex justify-content-between align-items-end">
                                                                <p class="small text-muted mb-0"><i class="bi bi-calendar-x me-1"></i> {{ $violation->date->translatedFormat('d M Y') }}</p>
                                                                <span class="small badge bg-light text-muted border">{{ str_replace('_', ' ', $violation->follow_up_status ?: 'pending') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="card glass-card border-0 py-4 text-center">
                                                <p class="text-success mb-0 small"><i class="bi bi-shield-check me-1"></i> Luar biasa! Anda belum memiliki pelanggaran tahun ini.</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- History / Track Record Tab --}}
                    <div class="tab-pane fade" id="pills-history" role="tabpanel">
                        <div class="row">
                            <div class="col-12">
                                @php
                                    $allYears = collect(array_keys($groupedViolations->toArray()))
                                                ->merge(array_keys($groupedAchievements->toArray()))
                                                ->unique()
                                                ->sortDesc();
                                @endphp

                                @forelse($allYears as $yearName)
                                    <div class="timeline-year">
                                        <h4 class="fw-bold text-primary mb-3">Tahun Pelajaran {{ $yearName }}</h4>
                                        <div class="row g-4">
                                            {{-- Grouped Achievements for this year --}}
                                            <div class="col-md-6">
                                                <h6 class="fw-bold text-uppercase text-muted small ls-1 mb-2">Prestasi</h6>
                                                @if(isset($groupedAchievements[$yearName]))
                                                    @foreach($groupedAchievements[$yearName] as $ach)
                                                        <div class="card glass-card border-0 mb-2 p-3 small achievement-card">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <div>
                                                                    <strong class="d-block text-dark">{{ $ach->achievement_name }}</strong>
                                                                    <span class="text-muted">{{ $ach->date->format('d/m/Y') }} • {{ $ach->level }}</span>
                                                                </div>
                                                                <span class="text-success fw-bold">{{ $ach->rank ?: 'Partisipasi' }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <p class="text-muted small italic">Tidak ada catatan prestasi.</p>
                                                @endif
                                            </div>

                                            {{-- Grouped Violations for this year --}}
                                            <div class="col-md-6">
                                                <h6 class="fw-bold text-uppercase text-muted small ls-1 mb-2">Pelanggaran</h6>
                                                @if(isset($groupedViolations[$yearName]))
                                                    @foreach($groupedViolations[$yearName] as $viol)
                                                        <div class="card glass-card border-0 mb-2 p-3 small violation-card">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <strong class="d-block text-dark">{{ $viol->description }}</strong>
                                                                    <span class="text-muted">{{ $viol->date->format('d/m/Y') }} • </span>
                                                                    <span class="badge {{ $viol->violation_type == 'Berat' ? 'bg-danger' : ($viol->violation_type == 'Sedang' ? 'bg-warning text-dark' : 'bg-secondary') }} px-1" style="font-size: 0.65rem;">{{ $viol->violation_type }}</span>
                                                                </div>
                                                                <div class="text-danger fw-black h5 mb-0">-{{ $viol->points }}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <p class="text-success small italic"><i class="bi bi-check-circle"></i> Bersih (Tidak ada pelanggaran).</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <i class="bi bi-folder-x display-1 text-muted opacity-25"></i>
                                        <h5 class="text-muted mt-3">Belum ada riwayat data diarsipkan.</h5>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
