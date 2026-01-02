@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-qr-code-scan me-2"></i> Check-in Kelas Hari Ini ({{ $today }})</h5>
                </div>
                <div class="card-body">
                    {{-- 1. GLOBAL HOLIDAY (ALL BLOCKED) --}}
                    @if(isset($isHoliday) && $isHoliday)
                        <div class="text-center py-5">
                            <i class="bi bi-emoji-sunglasses fs-1 text-danger d-block mb-3"></i>
                            <h3 class="text-danger fw-bold">HARI LIBUR</h3>
                            <p class="fs-5 text-muted">{{ $calendarDescription ?? 'Libur' }}</p>
                            <div class="alert alert-warning d-inline-block mt-3">
                                <i class="bi bi-info-circle me-1"></i> Tidak perlu melakukan absensi hari ini.
                            </div>
                            
                            @if(isset($unitStatuses) && count($unitStatuses) > 1)
                                <div class="mt-4 text-start d-inline-block bg-light p-3 rounded">
                                    <h6 class="small fw-bold text-muted mb-2">Status Unit:</h6>
                                    @foreach($unitStatuses as $status)
                                        <div class="badge {{ $status['status'] == 'holiday' ? 'bg-secondary' : 'bg-success' }} mb-1 me-1">
                                            {{ $status['unit'] }}: {{ $status['description'] }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    
                    @else
                        {{-- 2. MIXED / EFFECTIVE --}}
                        
                        {{-- Unit Status Banners (for Mixed Holidays/Activities) --}}
                         @if(isset($unitStatuses) && count($unitStatuses) > 0)
                            <div class="row g-2 mb-3">
                                @foreach($unitStatuses as $status)
                                    @if($status['status'] !== 'effective')
                                         <div class="col-md-12">
                                             <div class="alert {{ $status['status'] == 'holiday' ? 'alert-warning text-warning-emphasis' : 'alert-info text-info-emphasis' }} d-flex align-items-center py-2 px-3 mb-0 shadow-sm border-0">
                                                 <i class="bi {{ $status['status'] == 'holiday' ? 'bi-emoji-sunglasses' : 'bi-flag-fill' }} fs-4 me-3"></i>
                                                 <div>
                                                     <strong class="d-block small">{{ $status['unit'] }}</strong>
                                                     <span class="small lh-1">{{ $status['description'] }} (Absensi Non-Aktif)</span>
                                                 </div>
                                             </div>
                                         </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        {{-- 3. GLOBAL ACTIVITY (If All are Activity) --}}
                        @if(isset($isActivity) && $isActivity)
                            <div class="alert alert-info border-info mb-4 shadow-sm">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi bi-flag-fill fs-2 text-info"></i>
                                    <div>
                                        <h5 class="alert-heading fw-bold mb-1">KEGIATAN SEKOLAH</h5>
                                        <p class="mb-0">{{ $calendarDescription }}</p>
                                        <small class="text-muted fst-italic">Jurnal Mengajar dinonaktifkan untuk kegiatan sekolah.</small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 4. SCHEDULE LIST (Effective Only) --}}
                        @if(count($schedules) > 0)
                            <div class="list-group list-group-flush">
                                @foreach($schedules as $schedule)
                                    <div class="list-group-item p-3 d-flex justify-content-between align-items-center {{ $schedule->hasCheckedIn ? 'bg-light' : '' }}">
                                        <div>
                                            <h5 class="mb-1 text-primary">{{ $schedule->subject->name ?? 'Mapel' }}</h5>
                                            <div class="d-flex align-items-center gap-3 text-muted">
                                                <span class="badge bg-secondary"><i class="bi bi-door-open me-1"></i> {{ $schedule->schoolClass->name ?? 'Kelas' }}</span>
                                                <small><i class="bi bi-clock me-1"></i> {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</small>
                                            </div>
                                        </div>
                                        <div>
                                            @if($schedule->hasCheckedIn)
                                                <button class="btn btn-success disabled" disabled>
                                                    <i class="bi bi-check-circle-fill me-1"></i> Sudah Check-in
                                                </button>
                                            @else
                                                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#checkinModal" 
                                                    data-schedule-id="{{ $schedule->id }}" 
                                                    data-subject="{{ $schedule->subject->name }}"
                                                    data-class="{{ $schedule->schoolClass->name }}">
                                                    <i class="bi bi-geo-alt-fill me-1"></i> Check-in
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif(isset($isTooEarly) && $isTooEarly)
                            {{-- Too Early State --}}
                            <div class="text-center py-5">
                                <i class="bi bi-clock-history fs-1 text-warning d-block mb-3"></i>
                                <h4 class="fw-bold text-dark">BELUM WAKTUNYA</h4>
                                <p class="text-muted">Jadwal mengajar Anda hari ini baru dimulai pada pukul <span class="badge bg-warning text-dark fs-6">{{ $nextScheduleTime }}</span>.</p>
                                <div class="mt-4">
                                    <a href="{{ route('dashboard') }}" class="btn btn-primary px-4 rounded-pill shadow-sm">
                                        <i class="bi bi-house-door me-1"></i> Dashboard
                                    </a>
                                </div>
                            </div>
                        @elseif(isset($isFinishedToday) && $isFinishedToday)
                            {{-- Finished State --}}
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-check fs-1 text-success d-block mb-3"></i>
                                <h4 class="fw-bold text-dark">JADWAL SELESAI</h4>
                                <p class="text-muted">Semua jadwal mengajar Anda untuk hari ini telah berakhir.</p>
                                <div class="mt-4">
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary px-4 rounded-pill shadow-sm">
                                        <i class="bi bi-house-door me-1"></i> Kembali ke Dashboard
                                    </a>
                                </div>
                            </div>
                        @elseif(!isset($isActivity) || !$isActivity)
                            {{-- Empty State (Not Activity, Not Holiday) - Just no schedules at all --}}
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x fs-1 text-muted d-block mb-3"></i>
                                <h5 class="text-secondary">Tidak ada jadwal mengajar hari ini.</h5>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mt-2">Kembali ke Dashboard</a>
                            </div>
                        @endif
                        
                    @endif
                </div>
            </div>

            {{-- 5. TODAY'S SUMMARY (HISTORY & UPCOMING) --}}
            @if(!$isHoliday && !$isActivity && $activeSchedules->isNotEmpty())
                <div class="card shadow-sm border-0 mt-4 overflow-hidden">
                    <div class="card-header bg-light border-bottom-0 py-3">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-journal-text me-2"></i> Ringkasan Agenda Hari Ini</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="small text-uppercase ls-1">
                                        <th class="px-4">Waktu</th>
                                        <th>Mapel & Kelas</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeSchedules as $s)
                                        @php
                                            $checkin = $todayCheckins->where('schedule_id', $s->id)->first();
                                            $isPast = now()->format('H:i:s') > $s->end_time;
                                            $isOngoing = now()->format('H:i:s') >= $s->start_time && now()->format('H:i:s') <= $s->end_time;
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="fw-bold text-dark">{{ substr($s->start_time, 0, 5) }}</div>
                                                <div class="small text-muted">{{ substr($s->end_time, 0, 5) }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-primary">{{ $s->subject->name }}</div>
                                                <div class="small text-muted">{{ $s->schoolClass->name }}</div>
                                            </td>
                                            <td class="text-center">
                                                @if($checkin)
                                                    <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Terabsen
                                                    </span>
                                                @elseif($isOngoing)
                                                    <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill animate__animated animate__pulse animate__infinite">
                                                         sedang Berjalan
                                                    </span>
                                                @elseif($isPast)
                                                    <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">
                                                        <i class="bi bi-x-circle-fill me-1"></i> Tidak Absen
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">
                                                        Belum Mulai
                                                    </span>
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
        </div>
    </div>
</div>

<!-- Modal Checkin -->
<div class="modal fade" id="checkinModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('class-checkins.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Check-in</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="schedule_id" id="modalScheduleId">
                    <input type="hidden" name="latitude" id="modalLat">
                    <input type="hidden" name="longitude" id="modalLng">

                    <div class="alert alert-info">
                        <strong>Mengajar:</strong> <span id="modalSubject"></span><br>
                        <strong>Kelas:</strong> <span id="modalClass"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Bukti</label>
                        
                        <!-- Camera Preview -->
                        <div id="camera_container" class="mb-2 text-center" style="display:none;">
                            <video id="camera_preview" width="100%" style="border-radius: 8px;" autoplay playsinline></video>
                            <button type="button" class="btn btn-warning mt-2 w-100" id="take_photo_btn">
                                <i class="bi bi-camera-fill"></i> Ambil Foto
                            </button>
                        </div>

                        <!-- Captured Result -->
                        <div id="result_container" class="mb-2 text-center" style="display:none;">
                            <img id="saved_photo" src="" class="img-fluid rounded mb-2">
                            <button type="button" class="btn btn-secondary btn-sm" id="retake_photo_btn">
                                <i class="bi bi-arrow-counterclockwise"></i> Foto Ulang
                            </button>
                        </div>

                        <!-- Hidden Input for Base64 -->
                        <input type="hidden" name="photo_base64" id="photo_base64">

                        <!-- Fallback / Initial Button -->
                        <button type="button" class="btn btn-outline-primary w-100" id="start_camera_btn">
                            <i class="bi bi-camera"></i> Buka Kamera
                        </button>
                        
                        <div class="form-text">Ambil foto suasana kelas secara langsung.</div>
                    </div>

                        <div class="mb-3">
                            <label class="form-label">Kondisi Check-in</label>
                            <select name="checkin_type" class="form-select" id="checkinTypeSelect">
                                <option value="main">Hadir (Saya Sendiri)</option>
                                <option value="substitute">Badal / Invaler (Menggantikan Guru Lain)</option>
                                <option value="absent">Tidak Masuk (Izin/Sakit)</option>
                            </select>
                        </div>

                        <div id="substituteFields" style="display: none;">
                            <div class="alert alert-warning">
                                <small>Anda akan melakukan check-in untuk jadwal ini sebagai guru pengganti (Invaler).</small>
                            </div>
                        </div>

                        <div id="absentFields" style="display: none;">
                            <div class="alert alert-danger">
                                <small>Anda menyatakan tidak masuk untuk jadwal ini. Mohon sertakan alasan/bukti.</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan / Jurnal Kelas</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Materi yang disampaikan hari ini..."></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success d-flex align-items-center gap-2">
                        <i class="bi bi-send-fill"></i> Submit Check-in
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var checkinModal = document.getElementById('checkinModal');
        
        // Define Elements outside event listener to prevent re-declarations issues in memory
        let stream = null;
        const video = document.getElementById('camera_preview');
        const startBtn = document.getElementById('start_camera_btn');
        const takeBtn = document.getElementById('take_photo_btn');
        const retakeBtn = document.getElementById('retake_photo_btn');
        const cameraContainer = document.getElementById('camera_container');
        const resultContainer = document.getElementById('result_container');
        const savedPhoto = document.getElementById('saved_photo');
        const photoInput = document.getElementById('photo_base64');

        // Functions defined once
        async function startCamera() {
            try {
                // Constraints to prefer back camera if available
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: { ideal: "environment" } 
                    } 
                });
                video.srcObject = stream;
                
                // Play ensuring promise handling
                await video.play();

                startBtn.style.display = 'none';
                cameraContainer.style.display = 'block';
                resultContainer.style.display = 'none';
            } catch (err) {
                console.error(err);
                
                // Fallback UI
                startBtn.style.display = 'none';
                cameraContainer.style.display = 'none';
                
                var errorMsg = '';
                if(location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                     errorMsg = 'Kamera wajib menggunakan HTTPS. ';
                } else if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                     errorMsg = 'Browser tidak mendukung akses kamera langsung. ';
                } else {
                     errorMsg = 'Gagal akses kamera: ' + err.message + '. ';
                }
                
                // Show fallback file input
                var fallbackHtml = `
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> ${errorMsg}
                        <br>Silakan upload foto manual di bawah ini.
                    </div>
                    <input type="file" name="photo" class="form-control" accept="image/*" capture="environment">
                `;
                
                // Replace camera button/container area with fallback
                var parent = startBtn.parentNode;
                var div = document.createElement('div');
                div.innerHTML = fallbackHtml;
                parent.insertBefore(div, startBtn);
            }
        }

        function takePhoto() {
            if (!stream) return;
            
            const canvas = document.createElement('canvas');
            // Match the video dimensions
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Convert to Base64
            const dataUrl = canvas.toDataURL('image/jpeg', 0.8); // 80% quality
            
            // Show Result
            savedPhoto.src = dataUrl;
            photoInput.value = dataUrl;
            
            // UI Toggle
            cameraContainer.style.display = 'none';
            resultContainer.style.display = 'block';
        }

        function retakePhoto() {
            photoInput.value = '';
            resultContainer.style.display = 'none';
            cameraContainer.style.display = 'block';
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            startBtn.style.display = 'block';
            cameraContainer.style.display = 'none';
            resultContainer.style.display = 'none';
            photoInput.value = '';
            video.srcObject = null;
        }

        // Attach static listeners ONLY ONCE
        startBtn.addEventListener('click', startCamera);
        takeBtn.addEventListener('click', takePhoto);
        retakeBtn.addEventListener('click', retakePhoto);

        // Modal Events
        checkinModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var scheduleId = button.getAttribute('data-schedule-id');
            var subject = button.getAttribute('data-subject');
            var cls = button.getAttribute('data-class');

            document.getElementById('modalScheduleId').value = scheduleId;
            document.getElementById('modalSubject').textContent = subject;
            document.getElementById('modalClass').textContent = cls;

            // Geolocation
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('modalLat').value = position.coords.latitude;
                    document.getElementById('modalLng').value = position.coords.longitude;
                }, function(error) {
                    console.log("Geolocation error: " + error.message);
                });
            }
        });

        checkinModal.addEventListener('hidden.bs.modal', function () {
            stopCamera();
            document.getElementById('checkinTypeSelect').value = 'main';
            document.getElementById('substituteFields').style.display = 'none';
            document.getElementById('absentFields').style.display = 'none';
        });

        // Toggle Fields
        const typeSelect = document.getElementById('checkinTypeSelect');
        const subFields = document.getElementById('substituteFields');
        const absentFields = document.getElementById('absentFields');
        typeSelect.addEventListener('change', function() {
            if (this.value === 'substitute') {
                subFields.style.display = 'block';
                absentFields.style.display = 'none';
            } else if (this.value === 'absent') {
                subFields.style.display = 'none';
                absentFields.style.display = 'block';
            } else {
                subFields.style.display = 'none';
                absentFields.style.display = 'none';
            }
        });
    });
</script>
@endsection
