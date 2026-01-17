<div class="card border-0 shadow-sm rounded-4 overflow-hidden" id="monitoring-kelas-widget" style="background: #f8fafc; border: 1px solid #e2e8f0 !important;">
    <div class="card-header border-0 py-4 d-flex align-items-center justify-content-between px-4" style="background: white; border-bottom: 1px solid #e2e8f0 !important;">
        <div class="d-flex align-items-center">
            <div class="bg-primary p-3 rounded-4 me-3 shadow-sm">
                <i class="bi bi-display text-white fs-3"></i>
            </div>
            <div>
                <h4 class="fw-extrabold mb-0 text-slate-800 ls-1 text-uppercase" style="color: #1e293b;">
                    Laporan Real-time
                    @if(isset($units) && isset($selectedUnitId) && $selectedUnitId !== 'all')
                        @php $selUnit = $units->firstWhere('id', $selectedUnitId); @endphp
                        <span class="text-primary opacity-75"> • {{ $selUnit->name ?? '' }}</span>
                    @endif
                </h4>
                <div class="d-flex align-items-center mt-1">
                    <span class="badge bg-primary bg-opacity-10 text-primary small me-2">{{ $monitoringData['currentDayName'] }}</span>
                    <small class="text-muted fw-bold">{{ $monitoringData['now']->translatedFormat('d M Y') }}</small>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center bg-white rounded-pill shadow-sm border border-primary border-opacity-10 ps-4 pe-1 py-1 gap-2">
                <div id="realtime-clock" class="text-primary font-monospace fw-bold" style="font-size: 1.25rem; letter-spacing: 2px; min-width: 110px;">
                    00:00:00
                </div>
                <div class="vr text-primary opacity-25 my-2"></div>
                <button class="btn btn-light text-primary rounded-circle shadow-none d-flex align-items-center justify-content-center" onclick="location.reload()" title="Muat Ulang Data" style="width: 38px; height: 38px;">
                    <i class="bi bi-arrow-clockwise fs-5"></i>
                </button>
            </div>
    </div>
    <div class="card-body p-0" style="background: #f1f5f9;">
        <div class="auto-scroll-container" style="max-height: 600px; overflow-y: auto; padding: 2rem;">
            @if($monitoringData['dayStatus'] === 'holiday')
                <div class="text-center py-5">
                    <i class="bi bi-emoji-smile text-warning" style="font-size: 5rem;"></i>
                    <h2 class="fw-extrabold mt-3 text-dark">HARI LIBUR</h2>
                    <p class="text-muted fs-5">{{ $monitoringData['dayDescription'] }}</p>
                </div>
            @elseif($monitoringData['dayStatus'] === 'activity')
                <div class="text-center py-5">
                    <i class="bi bi-flag-fill text-primary" style="font-size: 5rem;"></i>
                    <h2 class="fw-extrabold mt-3 text-dark">KEGIATAN SEKOLAH</h2>
                    <p class="badge bg-primary px-4 py-2 fs-5 rounded-pill">{{ $monitoringData['dayDescription'] }}</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                    @forelse($monitoringData['groupedSchedules'] as $className => $classSchedules)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="background: white; border: 1px solid #e2e8f0 !important;">
                                <div class="card-header py-3 px-4 border-0 d-flex align-items-center justify-content-between" style="background: #f8fafc; border-bottom: 1px solid #f1f5f9 !important;">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-door-closed-fill text-primary me-2 fs-5"></i>
                                        <span class="fw-extrabold text-dark text-uppercase small" style="letter-spacing: 0.5px; color: #1e293b;">{{ $className }}</span>
                                    </div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 small px-2 py-1" style="font-size: 0.6rem;">LIVE</span>
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
                                            <div class="list-group-item px-4 py-3 bg-transparent border-0 {{ $isActive ? 'active-schedule' : ($isPast ? 'opacity-40' : '') }}" style="border-left: 4px solid {{ $isActive ? '#0d6efd' : 'transparent' }} !important; margin-bottom: 1px; background: {{ $isActive ? '#eff6ff' : 'transparent' }};">
                                                @if($schedule->is_break)
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;"><i class="bi bi-cup-hot-fill me-1"></i> {{ $schedule->break_name ?? 'ISTIRAHAT' }}</span>
                                                        <small class="text-muted font-monospace fw-bold">{{ $st->format('H:i') }} - {{ $en->format('H:i') }}</small>
                                                    </div>
                                                @else
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <span class="fw-bold text-dark d-block text-truncate pe-2" style="font-size: 1rem; color: #1e293b;" title="{{ $schedule->subject->name ?? '-' }}">
                                                            {{ $schedule->subject->name ?? '-' }}
                                                        </span>
                                                        <span class="badge bg-light text-muted font-monospace border border-light">{{ $st->format('H:i') }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center overflow-hidden">
                                                            <div class="avatar-mini me-2 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center rounded-circle" style="width: 24px; height: 24px;">
                                                                <i class="bi bi-person-fill" style="font-size: 0.7rem;"></i>
                                                            </div>
                                                            <span class="text-secondary text-truncate small fw-bold" style="color: #475569;">{{ $schedule->teacher->name ?? '-' }}</span>
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
                                                            <span class="badge {{ $s['bg'] }} text-white rounded-pill fw-bold" style="font-size: 0.6rem;">{{ $s['label'] }}</span>
                                                        @elseif($isActive)
                                                            <div class="d-flex align-items-center">
                                                                <span class="text-muted small me-2" style="font-size: 0.6rem; letter-spacing: 0.5px;">MENUNGGU</span>
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
        background: #f1f5f9;
    }
    .auto-scroll-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .auto-scroll-container:hover::-webkit-scrollbar-thumb {
        background: #94a3b8;
    }
    .active-schedule {
        background: #eff6ff !important;
        box-shadow: inset 0 0 15px rgba(13, 110, 253, 0.05);
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
