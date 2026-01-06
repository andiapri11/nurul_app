<div class="card border-0 shadow-lg rounded-4 overflow-hidden" id="monitoring-kelas-widget" style="background: #0f172a; border: 1px solid rgba(255,255,255,0.1) !important;">
    <div class="card-header border-0 py-4 d-flex align-items-center justify-content-between px-4" style="background: rgba(255,255,255,0.03); border-bottom: 1px solid rgba(255,255,255,0.05) !important;">
        <div class="d-flex align-items-center">
            <div class="bg-primary p-3 rounded-4 me-3 shadow-sm">
                <i class="bi bi-display text-white fs-3"></i>
            </div>
            <div>
                <h4 class="fw-extrabold mb-0 text-white ls-1 text-uppercase">
                    Laporan Real-time
                    @if(isset($units) && isset($selectedUnitId) && $selectedUnitId !== 'all')
                        @php $selUnit = $units->firstWhere('id', $selectedUnitId); @endphp
                        <span class="text-primary opacity-75"> • {{ $selUnit->name ?? '' }}</span>
                    @endif
                </h4>
                <div class="d-flex align-items-center mt-1">
                    <span class="badge bg-primary bg-opacity-25 text-primary small me-2">{{ $monitoringData['currentDayName'] }}</span>
                    <small class="text-white-50 fw-bold">{{ $monitoringData['now']->translatedFormat('d M Y') }}</small>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div id="realtime-clock" class="badge bg-white bg-opacity-10 text-white px-4 py-2 rounded-pill font-monospace shadow-sm border border-white border-opacity-10" style="font-size: 1.2rem; letter-spacing: 2px;">
                00:00:00
            </div>
            <button class="btn btn-outline-light btn-sm rounded-circle p-2 border-opacity-25" onclick="location.reload()" title="Refresh">
                <i class="bi bi-arrow-clockwise fs-5"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0" style="background: #0f172a;">
        <div class="auto-scroll-container" style="max-height: 600px; overflow-y: auto; padding: 2rem;">
            @if($monitoringData['dayStatus'] === 'holiday')
                <div class="text-center py-5">
                    <i class="bi bi-emoji-smile text-warning" style="font-size: 5rem;"></i>
                    <h2 class="fw-extrabold mt-3 text-white">HARI LIBUR</h2>
                    <p class="text-white-50 fs-5">{{ $monitoringData['dayDescription'] }}</p>
                </div>
            @elseif($monitoringData['dayStatus'] === 'activity')
                <div class="text-center py-5">
                    <i class="bi bi-flag-fill text-primary" style="font-size: 5rem;"></i>
                    <h2 class="fw-extrabold mt-3 text-white">KEGIATAN SEKOLAH</h2>
                    <p class="badge bg-primary px-4 py-2 fs-5 rounded-pill">{{ $monitoringData['dayDescription'] }}</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                    @forelse($monitoringData['groupedSchedules'] as $className => $classSchedules)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(255,255,255,0.05) !important;">
                                <div class="card-header py-3 px-4 border-0 d-flex align-items-center justify-content-between" style="background: rgba(255,255,255,0.03); border-bottom: 1px solid rgba(255,255,255,0.03) !important;">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-door-closed-fill text-primary me-2 fs-5"></i>
                                        <span class="fw-extrabold text-white text-uppercase small" style="letter-spacing: 0.5px;">{{ $className }}</span>
                                    </div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 small px-2 py-1" style="font-size: 0.6rem;">LIVE</span>
                                </div>
                                <div class="list-group list-group-flush bg-transparent">
                                    @php
                                        $nw = $monitoringData['now'];
                                        $activeIndex = -1;
                                        foreach($classSchedules as $i => $sch) {
                                            $st = \Carbon\Carbon::parse($sch->start_time);
                                            $en = \Carbon\Carbon::parse($sch->end_time);
                                            if ($nw->between($st, $en)) { $activeIndex = $i; break; }
                                        }
                                        if ($activeIndex === -1) {
                                            foreach($classSchedules as $i => $sch) {
                                                $st = \Carbon\Carbon::parse($sch->start_time);
                                                if ($nw->lt($st)) { $activeIndex = $i; break; }
                                            }
                                        }
                                        if ($activeIndex === -1 && count($classSchedules) > 0) { $activeIndex = count($classSchedules) - 1; }
                                        $showIndices = [$activeIndex - 1, $activeIndex, $activeIndex + 1];
                                    @endphp

                                    @foreach($classSchedules as $index => $schedule)
                                        @if(in_array($index, $showIndices))
                                            @php
                                                $st = \Carbon\Carbon::parse($schedule->start_time);
                                                $en = \Carbon\Carbon::parse($schedule->end_time);
                                                $isActive = ($index === $activeIndex && $nw->between($st, $en));
                                                $isPast = ($index < $activeIndex || ($index === $activeIndex && $nw->gt($en)));
                                            @endphp
                                            <div class="list-group-item px-4 py-3 bg-transparent border-0 {{ $isActive ? 'active-schedule' : ($isPast ? 'opacity-25' : 'opacity-75') }}" style="border-left: 4px solid {{ $isActive ? '#0d6efd' : 'transparent' }} !important; margin-bottom: 1px; background: {{ $isActive ? 'rgba(13, 110, 253, 0.1)' : 'transparent' }};">
                                                @if($schedule->is_break)
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;"><i class="bi bi-cup-hot-fill me-1"></i> {{ $schedule->break_name ?? 'ISTIRAHAT' }}</span>
                                                        <small class="text-white font-monospace fw-bold opacity-50">{{ $st->format('H:i') }} - {{ $en->format('H:i') }}</small>
                                                    </div>
                                                @else
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <span class="fw-bold text-white d-block text-truncate pe-2" style="font-size: 1rem;" title="{{ $schedule->subject->name ?? '-' }}">
                                                            {{ $schedule->subject->name ?? '-' }}
                                                        </span>
                                                        <span class="badge bg-white bg-opacity-10 text-white-50 font-monospace border border-white border-opacity-10">{{ $st->format('H:i') }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center overflow-hidden">
                                                            <div class="avatar-mini me-2 bg-primary bg-opacity-20 text-primary d-flex align-items-center justify-content-center rounded-circle" style="width: 24px; height: 24px;">
                                                                <i class="bi bi-person-fill" style="font-size: 0.7rem;"></i>
                                                            </div>
                                                            <span class="text-white-50 text-truncate small fw-bold">{{ $schedule->teacher->name ?? '-' }}</span>
                                                        </div>
                                                        
                                                        @if($schedule->todayCheckin)
                                                            @php
                                                                $statusMap = [
                                                                    'ontime' => ['bg' => 'bg-success', 'label' => 'HADIR'],
                                                                    'late' => ['bg' => 'bg-warning', 'label' => 'TELAT'],
                                                                    'substitute' => ['bg' => 'bg-info', 'label' => 'BADAL'],
                                                                    'absent' => ['bg' => 'bg-danger', 'label' => 'ABSEN'],
                                                                ];
                                                                $s = $statusMap[$schedule->todayCheckin->status] ?? ['bg' => 'bg-secondary', 'label' => strtoupper($schedule->todayCheckin->status)];
                                                            @endphp
                                                            <span class="badge {{ $s['bg'] }} text-dark rounded-pill fw-bold" style="font-size: 0.6rem;">{{ $s['label'] }}</span>
                                                        @elseif($isActive)
                                                            <div class="d-flex align-items-center">
                                                                <span class="text-white-50 small me-2" style="font-size: 0.6rem; letter-spacing: 0.5px;">MENUNGGU</span>
                                                                <span class="pulse-red"></span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Tidak ada jadwal pelajaran hari ini.</p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .bg-purple { background-color: #6f42c1; color: white; }
    .auto-scroll-container::-webkit-scrollbar {
        width: 6px;
    }
    .auto-scroll-container::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.05);
    }
    .auto-scroll-container::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
    }
    .auto-scroll-container:hover::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.3);
    }
    .active-schedule {
        background: rgba(13, 110, 253, 0.15) !important;
        box-shadow: inset 0 0 20px rgba(13, 110, 253, 0.1);
    }
    .border-white .opacity-05 {
        border-color: rgba(255,255,255,0.05) !important;
    }
    .pulse-red {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #ef4444;
        box-shadow: 0 0 0 rgba(239, 68, 68, 0.4);
        animation: pulse-red 2s infinite;
    }
    @keyframes pulse-red {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    .fw-extrabold { font-weight: 800; }
</style>

<script>
    function updateDashboardClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        const clockEl = document.getElementById('realtime-clock');
        if (clockEl) {
            clockEl.textContent = `${h}:${m}:${s}`;
        }
    }
    setInterval(updateDashboardClock, 1000);
    updateDashboardClock();

    // Auto refresh logic every 5 minutes
    setTimeout(() => {
        // Only refresh if the widget is visible to avoid unnecessary reloads
        if (document.getElementById('monitoring-kelas-widget')) {
            // Instead of full reload, we could do AJAX, but for simplicity let's stay with reload or just let the user manual refresh
            // window.location.reload(); 
        }
    }, 300000);
</script>
