@extends('layouts.app')

@section('content')
@push('styles')
<style>
    /* Professional Enterprise Design */
    :root {
        --bs-font-sans-serif: 'Inter', system-ui, -apple-system, sans-serif;
        --app-bg: #f8f9fa;
        --app-card-bg: #ffffff;
        --app-border: #e9ecef;
        --app-text-main: #343a40;
        --app-text-sub: #6c757d;
        --app-primary: #0d6efd;
    }

    body {
        background-color: var(--app-bg);
        color: var(--app-text-main);
    }

    .page-title-section {
        padding: 1.5rem 0;
        margin-bottom: 1.5rem;
        background: #fff;
        border-bottom: 1px solid var(--app-border);
    }

    .main-card {
        background: var(--app-card-bg);
        border: 1px solid var(--app-border);
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .schedule-item {
        padding: 1.25rem;
        border-bottom: 1px solid var(--app-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background-color 0.15s ease-in-out;
    }

    .schedule-item:last-child {
        border-bottom: none;
    }

    .schedule-item:hover {
        background-color: #f8f9fa;
    }

    .schedule-time {
        font-weight: 600;
        font-size: 1rem;
        color: var(--app-text-main);
        min-width: 80px;
        display: block;
    }

    .schedule-time-end {
        font-size: 0.8rem;
        color: var(--app-text-sub);
        font-weight: 400;
    }

    .schedule-details h5 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: #212529;
    }

    .schedule-meta {
        font-size: 0.85rem;
        color: var(--app-text-sub);
    }

    .btn-checkin-action {
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        border-radius: 6px;
        font-size: 0.875rem;
        white-space: nowrap;
    }

    /* Status Badges */
    .status-indicator {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active { background: #e8f5e9; color: #198754; }
    .status-pending { background: #e9ecef; color: #495057; }
    .status-done { background: #e7f1ff; color: #0d6efd; }
    .status-missed { background: #fbe9e7; color: #d63384; }

    /* Modal Styling */
    .modal-content {
        border-radius: 8px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .camera-viewport {
        background: #000;
        width: 100%;
        max-width: 400px;
        aspect-ratio: 1/1;
        margin: 0 auto;
        position: relative;
        border-radius: 8px;
        overflow: hidden;
    }

    .camera-controls {
        display: flex;
        justify-content: center;
        padding-top: 1rem;
    }

    .capture-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #fff;
        border: 4px solid #ced4da;
        padding: 0;
        cursor: pointer;
        transition: transform 0.1s;
    }

    .capture-btn:active {
        transform: scale(0.95);
        border-color: var(--app-primary);
    }
    
    .empty-state-card {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        text-align: center;
        padding: 3rem 1rem;
    }
</style>
@endpush

<div class="page-title-section">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold text-dark">Agenda Mengajar</h4>
                <p class="text-muted mb-0 small">
                    <i class="bi bi-calendar-event me-1"></i> {{ $today }}, {{ now()->format('d F Y') }}
                </p>
            </div>
            <div class="text-end">
                <a href="{{ route('class-checkins.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4 pb-5">
    
    {{-- ALERTS --}}
    @if(isset($isHoliday) && $isHoliday)
        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center" role="alert">
            <i class="bi bi-pause-circle-fill fs-4 me-3"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-0">Hari Libur / Tidak Efektif</h6>
                <div class="small">{{ $calendarDescription }}</div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mx-auto">
            
            @if($schedules->isEmpty() && $activeSchedules->isEmpty())
                <div class="empty-state-card">
                    <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="fw-bold text-secondary">Tidak Ada Jadwal</h5>
                    <p class="text-muted small">Anda tidak memiliki jadwal mengajar aktif untuk hari ini.</p>
                </div>
            @else
                <div class="card bg-white border shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="card-title mb-0 fw-bold">Daftar Jadwal Hari Ini</h6>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        @foreach($schedules as $schedule)
                            @php
                                $currentTime = now()->format('H:i:s');
                                $isNow = $currentTime >= $schedule->start_time && $currentTime <= $schedule->end_time;
                                $isPast = $currentTime > $schedule->end_time;
                                $statusLabel = 'Menunggu';
                                $statusClass = 'status-pending';

                                if($schedule->hasCheckedIn) {
                                    $statusLabel = 'Selesai';
                                    $statusClass = 'status-done';
                                } elseif($isNow) {
                                    $statusLabel = 'Berlangsung';
                                    $statusClass = 'status-active';
                                } elseif($isPast) {
                                    $statusLabel = 'Terlewat';
                                    $statusClass = 'status-missed';
                                }
                            @endphp

                            <div class="list-group-item p-3 schedule-item {{ $isNow ? 'bg-light' : '' }}">
                                <div class="d-flex align-items-start w-100">
                                    <div class="me-3 text-center pt-1" style="min-width: 70px;">
                                        <div class="schedule-time">{{ substr($schedule->start_time, 0, 5) }}</div>
                                        <div class="schedule-time-end">{{ substr($schedule->end_time, 0, 5) }}</div>
                                    </div>
                                    
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold text-dark">{{ $schedule->subject->name }}</h6>
                                            @if($schedule->hasCheckedIn)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2">
                                                    <i class="bi bi-check-lg"></i> Terkirim
                                                </span>
                                            @endif
                                        </div>
                                        <div class="schedule-meta mb-2">
                                            <i class="bi bi-door-closed me-1"></i> Kelas {{ $schedule->schoolClass->name }}
                                        </div>
                                        
                                        @if(isset($schedule->calendar_activity))
                                            <div class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 mb-2 fw-normal" style="font-size: 0.75rem;">
                                                <i class="bi bi-flag-fill me-1"></i> Agenda: {{ $schedule->calendar_activity }}
                                            </div>
                                        @endif
                                        
                                        @if(!$schedule->hasCheckedIn)
                                            @if($isNow || $isPast)
                                                <button class="btn btn-primary btn-sm btn-checkin-action" 
                                                    onclick="initCheckin('{{ $schedule->id }}', '{{ addslashes($schedule->subject->name) }}', '{{ addslashes($schedule->schoolClass->name) }}', '{{ addslashes($schedule->calendar_activity ?? '') }}')">
                                                    <i class="bi bi-camera-fill me-2"></i> Mulai Absensi
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm btn-checkin-action disabled opacity-75" disabled>
                                                    <i class="bi bi-clock-history me-1"></i> Belum Mulai
                                                </button>
                                            @endif
                                        @else
                                            <button class="btn btn-outline-secondary btn-sm btn-checkin-action btn-sm py-1" disabled>
                                                Sudah Absen
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Absensi -->
<div class="modal fade" id="modalAbsensi" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('class-checkins.store') }}" method="POST" enctype="multipart/form-data" class="w-100">
            @csrf
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Konfirmasi Kehadiran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="schedule_id" id="inpScheduleId">
                    <input type="hidden" name="latitude" id="inpLat">
                    <input type="hidden" name="longitude" id="inpLng">
                    <input type="hidden" name="photo_base64" id="inpPhotoBase64">

                    <!-- Info Ringkas -->
                    <div class="bg-light p-3 rounded mb-3 border">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">MATA PELAJARAN</small>
                                <strong class="text-dark" id="txtSubject">-</strong>
                            </div>
                            <div class="col-6 border-start ps-3">
                                <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">KELAS</small>
                                <strong class="text-dark" id="txtClass">-</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Area Kamera -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small">1. Foto Bukti Kelas</label>
                        
                        <div id="sectionCamera">
                            <div class="camera-viewport">
                                <video id="vidPreview" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                            </div>
                            <div class="camera-controls">
                                <button type="button" class="capture-btn" id="btnCapture" title="Ambil Foto"></button>
                            </div>
                            <div class="text-center mt-2">
                                <button type="button" id="btnStartCamera" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-camera me-1"></i> Aktifkan Kamera
                                </button>
                            </div>
                        </div>

                        <div id="sectionResult" style="display: none;">
                            <div class="text-center">
                                <img id="imgResult" src="" class="img-fluid rounded border mb-2" style="max-height: 250px;">
                                <br>
                                <button type="button" class="btn btn-sm btn-light border" id="btnRetake">
                                    <i class="bi bi-arrow-repeat me-1"></i> Foto Ulang
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Input Data -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small">2. Status Kehadiran</label>
                        <select name="checkin_type" class="form-select" id="selType">
                            <option value="main">Hadir (Sesuai Jadwal)</option>
                            <option value="substitute">Guru Pengganti (Invaler)</option>
                            <option value="absent">Berhalangan Hadir</option>
                        </select>
                    </div>

                    <div id="boxSubstitute" class="alert alert-info py-2 small mb-3" style="display:none;">
                        <i class="bi bi-info-circle me-1"></i> Anda akan tercatat sebagai guru pengganti.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">3. Catatan Pembelajaran / Jurnal</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Tuliskan materi atau catatan penting..." required></textarea>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-link text-decoration-none text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Absensi</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let modalAbsensi;
    let stream = null;

    document.addEventListener('DOMContentLoaded', function() {
        modalAbsensi = new bootstrap.Modal(document.getElementById('modalAbsensi'));
        const vidPreview = document.getElementById('vidPreview');

        // Location
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                p => {
                    document.getElementById('inpLat').value = p.coords.latitude;
                    document.getElementById('inpLng').value = p.coords.longitude;
                },
                e => console.warn(e)
            );
        }

        // Camera Logic
        document.getElementById('btnStartCamera').addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                vidPreview.srcObject = stream;
                document.getElementById('btnStartCamera').style.display = 'none';
            } catch(e) {
                alert('Gagal akses kamera: ' + e.message);
            }
        });

        document.getElementById('btnCapture').addEventListener('click', () => {
            if(!stream) return;
            const canvas = document.createElement('canvas');
            const size = Math.min(vidPreview.videoWidth, vidPreview.videoHeight);
            canvas.width = 400; canvas.height = 400;
            const ctx = canvas.getContext('2d');
            
            // Center crop
            const sx = (vidPreview.videoWidth - size)/2;
            const sy = (vidPreview.videoHeight - size)/2;
            
            ctx.drawImage(vidPreview, sx, sy, size, size, 0, 0, 400, 400);
            const dataUrl = canvas.toDataURL('image/jpeg', 0.8);

            document.getElementById('inpPhotoBase64').value = dataUrl;
            document.getElementById('imgResult').src = dataUrl;
            
            document.getElementById('sectionCamera').style.display = 'none';
            document.getElementById('sectionResult').style.display = 'block';
        });

        document.getElementById('btnRetake').addEventListener('click', () => {
             document.getElementById('sectionResult').style.display = 'none';
             document.getElementById('sectionCamera').style.display = 'block';
             document.getElementById('inpPhotoBase64').value = '';
        });

        // Toggle Fields
        document.getElementById('selType').addEventListener('change', function() {
            document.getElementById('boxSubstitute').style.display = (this.value === 'substitute') ? 'block' : 'none';
        });

        // Cleanup on close
        document.getElementById('modalAbsensi').addEventListener('hidden.bs.modal', () => {
            if(stream) stream.getTracks().forEach(t => t.stop());
            stream = null;
            document.getElementById('btnStartCamera').style.display = 'inline-block';
            vidPreview.srcObject = null;
            
            // Reset views
            document.getElementById('sectionCamera').style.display = 'block';
            document.getElementById('sectionResult').style.display = 'none';
            document.getElementById('selType').value = 'main';
        });
    });

    function initCheckin(id, subject, cls, activity = '') {
        document.getElementById('inpScheduleId').value = id;
        document.getElementById('txtSubject').innerText = subject;
        document.getElementById('txtClass').innerText = cls;

        const notesEl = document.querySelector('textarea[name="notes"]');
        if (activity && notesEl) {
            notesEl.value = 'KEGIATAN: ' + activity;
        } else if (notesEl) {
            notesEl.value = '';
        }
        
        // Auto start camera for convenience
        document.getElementById('btnStartCamera').click();
        
        modalAbsensi.show();
    }
</script>
@endpush
@endsection
