<div class="card border-0 shadow-sm rounded-4 overflow-hidden" id="monitoring-kelas-widget">
    <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                <i class="bi bi-display text-primary fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0">
                    Monitoring Kelas Real-time
                    @if(isset($units) && isset($selectedUnitId) && $selectedUnitId !== 'all')
                        @php $selUnit = $units->firstWhere('id', $selectedUnitId); @endphp
                        <span class="text-primary"> - {{ $selUnit->name ?? '' }}</span>
                    @endif
                </h5>
                <small class="text-muted">{{ $monitoringData['currentDayName'] }}, {{ $monitoringData['now']->translatedFormat('d F Y') }}</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <div id="realtime-clock" class="badge bg-dark text-white px-3 py-2 rounded-pill font-monospace" style="font-size: 0.9rem;">
                00:00:00
            </div>
            <button class="btn btn-light btn-sm rounded-circle" onclick="location.reload()" title="Refresh">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
    </div>
    <div class="card-body bg-light p-0">
        <div class="auto-scroll-container" style="max-height: 500px; overflow-y: auto; padding: 1.5rem;">
            @if($monitoringData['dayStatus'] === 'holiday')
                <div class="text-center py-5">
                    <i class="bi bi-emoji-smile text-warning" style="font-size: 4rem;"></i>
                    <h4 class="fw-bold mt-3">HARI LIBUR</h4>
                    <p class="text-muted">{{ $monitoringData['dayDescription'] }}</p>
                </div>
            @elseif($monitoringData['dayStatus'] === 'activity')
                <div class="text-center py-5">
                    <i class="bi bi-flag-fill text-primary" style="font-size: 4rem;"></i>
                    <h4 class="fw-bold mt-3">KEGIATAN SEKOLAH</h4>
                    <p class="badge bg-primary fs-6">{{ $monitoringData['dayDescription'] }}</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
                    @forelse($monitoringData['groupedSchedules'] as $className => $classSchedules)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                <div class="card-header bg-dark text-white py-2 px-3 border-0 d-flex align-items-center">
                                    <i class="bi bi-people-fill me-2 small"></i>
                                    <span class="fw-bold small">{{ $className }}</span>
                                </div>
                                <div class="list-group list-group-flush">
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
                                            <div class="list-group-item px-3 py-2 border-light {{ $isActive ? 'bg-primary bg-opacity-10' : ($isPast ? 'opacity-50' : '') }}" style="border-left: 3px solid {{ $isActive ? '#0d6efd' : 'transparent' }}">
                                                @if($schedule->is_break)
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <span class="badge bg-warning text-dark small"><i class="bi bi-cup-hot-fill me-1"></i> {{ $schedule->break_name ?? 'ISTIRAHAT' }}</span>
                                                        <small class="text-muted font-monospace">{{ $st->format('H:i') }}-{{ $en->format('H:i') }}</small>
                                                    </div>
                                                @else
                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                        <span class="fw-bold text-dark d-block text-truncate pe-2" style="font-size: 0.85rem;" title="{{ $schedule->subject->name ?? '-' }}">
                                                            {{ $schedule->subject->name ?? '-' }}
                                                        </span>
                                                        <small class="text-muted font-monospace" style="font-size: 0.7rem;">{{ $st->format('H:i') }}</small>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center overflow-hidden">
                                                            <i class="bi bi-person-badge text-muted me-1 small"></i>
                                                            <span class="text-muted text-truncate small" style="font-size: 0.75rem;">{{ $schedule->teacher->name ?? '-' }}</span>
                                                        </div>
                                                        
                                                        @if($schedule->todayCheckin)
                                                            @php
                                                                $statusMap = [
                                                                    'ontime' => ['bg' => 'bg-success', 'label' => 'HADIR'],
                                                                    'late' => ['bg' => 'bg-danger', 'label' => 'TELAT'],
                                                                    'substitute' => ['bg' => 'bg-purple', 'label' => 'BADAL'],
                                                                    'absent' => ['bg' => 'bg-danger', 'label' => 'ABSEN'],
                                                                ];
                                                                $s = $statusMap[$schedule->todayCheckin->status] ?? ['bg' => 'bg-secondary', 'label' => strtoupper($schedule->todayCheckin->status)];
                                                            @endphp
                                                            <span class="badge {{ $s['bg'] }} rounded-pill" style="font-size: 0.6rem;">{{ $s['label'] }}</span>
                                                        @elseif($isActive)
                                                            <div class="d-flex align-items-center">
                                                                <span class="badge bg-secondary rounded-pill me-1" style="font-size: 0.6rem;">BELUM</span>
                                                                <div class="spinner-grow spinner-grow-sm text-danger" role="status" style="width: 8px; height: 8px;"></div>
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
        width: 4px;
    }
    .auto-scroll-container::-webkit-scrollbar-track {
        background: transparent;
    }
    .auto-scroll-container::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 10px;
    }
    .auto-scroll-container:hover::-webkit-scrollbar-thumb {
        background: #adb5bd;
    }
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
