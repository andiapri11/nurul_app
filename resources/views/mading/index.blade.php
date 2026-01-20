<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appSettings['app_name'] ?? 'Mading Online' }} - LPT Nurul Ilmi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            /* Premium Palette */
            --bg-dark: #0f172a;
            --bg-card: rgba(30, 41, 59, 0.7);
            --bg-card-hover: rgba(51, 65, 85, 0.8);
            --primary: #6366f1;
            --secondary: #ec4899;
            --accent: #14b8a6;
            --text-main: #f8fafc;
            --text-sub: #94a3b8;
            --border-glass: rgba(255, 255, 255, 0.08);
            --shadow-card: 0 8px 32px rgba(0, 0, 0, 0.2);
            --gradient-1: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --gradient-2: linear-gradient(135deg, #3b82f6 0%, #14b8a6 100%);
            --font-main: 'Plus Jakarta Sans', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-dark);
            color: var(--text-main);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            /* Dynamic Animated Background */
            background: radial-gradient(circle at top left, #1e1b4b, transparent 40%),
                        radial-gradient(circle at bottom right, #312e81, transparent 40%),
                        #0f172a;
        }

        /* Ambient Glow */
        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 80%);
            z-index: -1;
            pointer-events: none;
        }

        header {
            padding: 1rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(25px);
            border-bottom: 1px solid var(--border-glass);
            z-index: 10;
        }

        .brand {
            display: flex;
            flex-direction: column;
        }

        .brand h1 {
            font-size: 2rem;
            font-weight: 800;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.03em;
            margin-bottom: 0.2rem;
            text-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .brand p {
            color: var(--text-sub);
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .header-utils {
            display: flex;
            align-items: center;
            gap: 2.5rem;
        }

        .time-widget {
            text-align: right;
        }

        .clock {
            font-size: 2.75rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: -0.02em;
            font-variant-numeric: tabular-nums;
            background: linear-gradient(180deg, #fff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .date {
            font-size: 1rem;
            color: var(--text-sub);
            font-weight: 500;
            margin-top: 0.25rem;
        }

        .logout-btn {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .logout-btn:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* Pulse Button Animation for Urgent Actions */
        .pulse-button {
            animation: pulse-animation 2s infinite;
        }

        @keyframes pulse-animation {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        /* MAIN LAYOUT */
        main {
            flex: 1;
            padding: 1.5rem 2rem;
            display: grid;
            grid-template-columns: 2.5fr 1fr;
            gap: 1.5rem;
            overflow: hidden;
            padding-bottom: 4.5rem; /* Footer space */
        }

        /* SECTION STYLING */
        .glass-panel {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: 32px;
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: border-color 0.5s ease;
        }

        .glass-panel:hover {
            border-color: rgba(99, 102, 241, 0.3);
        }

        .panel-header {
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.02);
            border-bottom: 1px solid var(--border-glass);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .panel-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .panel-header i {
            color: var(--primary);
            font-size: 1.25rem;
        }

        /* SCHEDULE GRID */
        .schedule-grid {
            padding: 0 1rem;
            overflow-y: auto;
            flex: 1;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: min-content;
            gap: 1.25rem;
            padding-bottom: 2rem;
            align-content: start;
        }
        .schedule-grid::-webkit-scrollbar { display: none; }

        .class-card {
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: auto;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .class-header {
            background: rgba(255, 255, 255, 0.05);
            padding: 0.5rem 0.85rem;
            font-weight: 700;
            font-size: 0.9rem;
            border-bottom: 1px solid var(--border-glass);
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .class-schedule-items {
            display: flex;
            flex-direction: column;
        }

        .schedule-row {
            padding: 0.5rem 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .schedule-row:last-child { border-bottom: none; }

        .row-time {
            color: var(--text-sub);
            font-size: 0.7rem;
            font-family: monospace;
            display: flex;
            gap: 3px;
            white-space: nowrap;
            margin-top: 2px;
            opacity: 0.8;
        }
        
        .time-sep { opacity: 0.5; }

        .row-subject {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* For text-truncate */
        }

        .subject-name {
            font-weight: 600;
            color: var(--text-main);
            font-size: 0.85rem;
        }

        .teacher-row {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-sub);
            margin-top: 4px;
            padding: 0;
            transition: all 0.3s ease;
        }

        .status-label {
            font-size: 0.55rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            padding: 1px 6px;
            border-radius: 4px;
            color: white !important;
        }

        .status-red { background: #ef4444; }
        .status-green { background: #22c55e; }
        .status-purple { background: #a855f7; }
        .status-gray { background: rgba(255, 255, 255, 0.1); color: var(--text-sub) !important; border: 1px solid rgba(255,255,255,0.1); }

        /* LIVE Indicator */
        .live-indicator {
            position: absolute;
            right: 0.5rem;
            top: 0.6rem;
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(220, 38, 38, 0.2);
            color: #f87171;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.6rem;
            font-weight: 800;
            border: 1px solid rgba(220, 38, 38, 0.3);
        }

        .blob {
            width: 6px;
            height: 6px;
            background: #f87171;
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(248, 113, 113, 1);
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(248, 113, 113, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(248, 113, 113, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(248, 113, 113, 0); }
        }

        /* Past State */
        .schedule-row.past-subject {
            opacity: 0.5;
            filter: grayscale(0.5);
        }

        /* Break Row */
        .break-row {
            justify-content: center;
            background: rgba(255, 255, 255, 0.015);
            padding: 0.5rem;
        }
        
        .break-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .break-badge {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            padding: 2px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }
        
        .break-time {
            font-size: 0.8rem;
            color: var(--text-sub);
            font-family: monospace;
        }

        /* ANNOUNCEMENTS */
        .announcement-container {
            padding: 1.5rem;
            overflow-y: auto;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .announcement-container::-webkit-scrollbar { display: none; }

        .news-card {
            background: linear-gradient(180deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .news-card:hover { transform: translateY(-3px); border-color: rgba(255,255,255,0.2); }

        .news-image {
            width: 100%;
            max-height: 400px;
            object-fit: contain;
            background: #000;
            border-bottom: 1px solid var(--border-glass);
        }

        .news-content { padding: 1.25rem; }
        .news-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; line-height: 1.4; }
        .news-excerpt { font-size: 0.9rem; color: var(--text-sub); line-height: 1.6; }
        .news-meta { margin-top: 1rem; font-size: 0.75rem; color: var(--accent); font-weight: 600; text-transform: uppercase; }

        /* RUNNING TEXT */
        .marquee-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3.5rem;
            background: var(--gradient-1);
            color: white;
            display: flex;
            align-items: center;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.3);
            z-index: 20;
        }

        .marquee-content {
            display: flex;
            white-space: nowrap;
            animation: marquee 30s linear infinite;
        }
        
        .marquee-item {
            margin-right: 4rem;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        @keyframes marquee {
            0% { transform: translateX(100vw); }
            100% { transform: translateX(-100%); }
        }

        /* EMPTY STATE */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--text-sub);
            opacity: 0.6;
        }
    </style>
</head>
<body>

    <header>
        <div class="brand">
            <h1>{{ $appSettings['app_name'] ?? 'Mading Online' }}</h1>
            <p>{{ $selectedUnit ? $selectedUnit->name : 'LPT Nurul Ilmi' }}</p>
        </div>
        
        <div class="header-utils">
            <div class="time-widget">
                <div class="clock" id="clock">00:00</div>
                <div class="date">{{ $now->translatedFormat('l, d F Y') }}</div>
            </div>

            @auth
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn" title="Logout" onclick="return confirm('Logout dari mode display?')">
                    <i class="bi bi-power" style="font-size: 1.2rem;"></i>
                </button>
            </form>
            @endauth
        </div>
    </header>

    <main>
        <!-- Unit Filters (Only show if not locked) -->
        @if(!auth()->check() || auth()->user()->role !== 'mading' || !auth()->user()->unit_id)
        <div style="position: absolute; top: 100px; right: 40px; z-index: 5;">
            <div style="background: var(--bg-card); padding: 5px; border-radius: 12px; border: 1px solid var(--border-glass); display: flex; gap: 5px;">
                <a href="{{ route('mading.index') }}" style="color: white; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-size: 0.9rem; {{ !$selectedUnit ? 'background: var(--primary);' : '' }}">Semua</a>
                @foreach($units as $unit)
                    <a href="{{ route('mading.index', ['unit_id' => $unit->id]) }}" style="color: white; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-size: 0.9rem; {{ ($selectedUnit && $selectedUnit->id == $unit->id) ? 'background: var(--primary);' : '' }}">
                        {{ $unit->name }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- LEFT COLUMN WRAPPER -->
        <div style="display: flex; flex-direction: column; gap: 2rem; overflow: hidden;">
            
            <!-- SCHEDULE PANEL -->
            <div class="glass-panel" style="flex: 1; max-height: 60%;">
                <div class="panel-header">
                    <i class="bi bi-clock-history"></i>
                    <h2>Jadwal Pelajaran Hari Ini</h2>
                </div>
                
                <div class="schedule-grid auto-scroll-list">
                    @if(isset($dayStatus) && $dayStatus === 'holiday')
                        <div class="empty-state" style="grid-column: 1 / -1; color: #f87171;">
                            <i class="bi bi-emoji-smile" style="font-size: 5rem; margin-bottom: 1rem;"></i>
                            <h2 class="fw-bold mb-2">HARI INI LIBUR</h2>
                            <p class="fs-5">{{ $dayDescription }}</p>
                        </div>
                    @elseif(isset($dayStatus) && $dayStatus === 'activity')
                        <div class="empty-state" style="grid-column: 1 / -1; color: #6366f1;">
                            <i class="bi bi-flag-fill" style="font-size: 5rem; margin-bottom: 1rem;"></i>
                            <h2 class="fw-bold mb-2">KEGIATAN SEKOLAH</h2>
                            <p class="fs-4 fw-bold text-white bg-primary bg-opacity-25 px-4 py-2 rounded-pill border border-primary border-opacity-25">{{ $dayDescription }}</p>
                        </div>
                    @else
                        @php
                            // Group schedules by Class Name
                            // We need to handle null class names just in case
                            $groupedSchedules = $schedules->groupBy(function($item) {
                                return $item->schoolClass ? $item->schoolClass->name : 'Lainnya';
                            })->sortKeys();
                        @endphp
        
        @forelse($groupedSchedules as $className => $classSchedules)
                            <div class="class-card">
                                <div class="class-header">
                                    <i class="bi bi-people-fill"></i> {{ $className }}
                                </div>
                                <div class="class-schedule-items">
                                    @php
                                        $nw = \Carbon\Carbon::now();
                                        $activeIndex = -1;

                                        // 1. Find the current active schedule
                                        foreach($classSchedules as $i => $sch) {
                                            $st = \Carbon\Carbon::parse($sch->start_time);
                                            $en = \Carbon\Carbon::parse($sch->end_time);
                                            if ($nw->between($st, $en)) {
                                                $activeIndex = $i;
                                                break;
                                            }
                                        }

                                        // 2. If no active, find the next one
                                        if ($activeIndex === -1) {
                                            foreach($classSchedules as $i => $sch) {
                                                $st = \Carbon\Carbon::parse($sch->start_time);
                                                if ($nw->lt($st)) {
                                                    $activeIndex = $i;
                                                    break;
                                                }
                                            }
                                        }

                                        // 3. Fallback to last if the day is over
                                        if ($activeIndex === -1 && count($classSchedules) > 0) {
                                            $activeIndex = count($classSchedules) - 1;
                                        }

                                        // Define window
                                        $showIndices = [$activeIndex - 1, $activeIndex, $activeIndex + 1];
                                    @endphp

                                    @foreach($classSchedules as $index => $schedule)
                                        @if(in_array($index, $showIndices))
                                            @php
                                                $st = \Carbon\Carbon::parse($schedule->start_time);
                                                $en = \Carbon\Carbon::parse($schedule->end_time);
                                                $isActive = ($index === $activeIndex && $nw->between($st, $en));
                                                $isPast = ($index < $activeIndex || ($index === $activeIndex && $nw->gt($en) && $activeIndex !== -1));
                                                
                                                if ($activeIndex === -1) $isPast = false; // reset for holiday logic above

                                                $statusClass = '';
                                                if ($isActive) $statusClass = 'active-subject';
                                                elseif ($isPast) $statusClass = 'past-subject';
                                            @endphp
                                            <div class="schedule-row {{ $statusClass }} {{ $schedule->is_break ? 'break-row' : '' }}">
                                                @if($schedule->is_break)
                                                    <div class="break-content">
                                                        <span class="break-badge"><i class="bi bi-cup-hot-fill"></i> {{ $schedule->break_name ?? 'ISTIRAHAT' }}</span>
                                                        <span class="break-time">{{ $st->format('H:i') }} - {{ $en->format('H:i') }}</span>
                                                    </div>
                                                @else
                                                    <div class="row-subject w-100">
                                                        <span class="subject-name text-truncate d-block" title="{{ $schedule->subject->name ?? '-' }}">{{ $schedule->subject->name ?? '-' }}</span>
                                                        <div class="teacher-row">
                                                             <i class="bi bi-person-badge"></i> 
                                                             <span class="teacher-name-tiny text-truncate">{{ $schedule->teacher->name ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row-time">
                                                        <span>{{ $st->format('H:i') }}</span>
                                                        <span class="time-sep">-</span>
                                                        <span>{{ $en->format('H:i') }}</span>
                                                    </div>
                                                    @if($isActive)
                                                        <div class="live-indicator">
                                                            <div class="blob"></div>
                                                            <span>LIVE</span>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="empty-state" style="grid-column: 1 / -1;">
                                <i class="bi bi-calendar-x" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                                <p>Tidak ada jadwal pelajaran hari ini.</p>
                            </div>
                        @endforelse
                    @endif
                </div>
            </div>


        
        </div>

        <!-- RIGHT COLUMN WRAPPER -->
        <div style="display: flex; flex-direction: column; gap: 2rem; overflow: hidden;">
            
            <!-- NEWS PANEL -->
            <div class="glass-panel" style="flex: 1;">
                <div class="panel-header">
                    <i class="bi bi-megaphone"></i>
                    <h2>Informasi & Berita</h2>
                </div>
    
                <div class="announcement-container auto-scroll-news">
                    @forelse($news as $item)
                    <div class="news-card">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="news-image" alt="Poster">
                        @endif
                        <div class="news-content">
                            <div class="news-title">{{ $item->title }}</div>
                            <div class="news-excerpt">{{ $item->content }}</div>
                            <div class="news-meta">
                                <i class="bi bi-calendar-check"></i> {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="bi bi-inbox" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <p>Belum ada pengumuman terbaru.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- ABSENCE PANEL (LIST VIEW) -->
            <div class="glass-panel" style="flex: 1; max-height: 45%;">
                 <div class="panel-header">
                    <i class="bi bi-person-x"></i>
                    <h2>Siswa Tidak Hadir</h2>
                </div>
                <div class="auto-scroll-list" style="padding: 0 1.5rem 1.5rem 1.5rem; overflow-y: auto;">
                    @if(isset($absences) && $absences->isNotEmpty())
                        <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 1rem;">
                        @foreach($absences as $className => $students)
                            @foreach($students as $att)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px;">
                                <div style="display: flex; flex-direction: column; gap: 2px; overflow: hidden; margin-right: 1rem;">
                                    <span class="fw-bold text-truncate" style="color: var(--text-main); font-size: 0.95rem;">{{ $att->student->nama_lengkap }}</span>
                                    <span class="text-truncate" style="font-size: 0.8rem; color: #a5b4fc;"><i class="bi bi-people-fill me-1"></i> {{ $className }}</span>
                                </div>
                                @php
                                    $badge = ''; 
                                    $text = '';
                                    switch($att->status) {
                                        case 'sick': $text='SAKIT'; $style='background: rgba(14, 165, 233, 0.2); color: #7dd3fc; border: 1px solid rgba(14, 165, 233, 0.3);'; break;
                                        case 'permission': $text='IZIN'; $style='background: rgba(245, 158, 11, 0.2); color: #fcd34d; border: 1px solid rgba(245, 158, 11, 0.3);'; break;
                                        case 'alpha': $text='ALPA'; $style='background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3);'; break;
                                    }
                                @endphp
                                <span class="badge rounded-pill" style="font-size: 0.75rem; padding: 4px 10px; {{ $style }}">{{ $text }}</span>
                            </div>
                            @endforeach
                        @endforeach
                        </div>
                    @else
                        <div class="empty-state" style="padding: 2rem;">
                            <i class="bi bi-check-circle-fill" style="font-size: 3rem; margin-bottom: 1rem; color: #14b8a6; text-shadow: 0 0 20px rgba(20, 184, 166, 0.5);"></i>
                            <p class="fs-5 fw-bold text-white">NIHIL</p>
                            <p class="small">Semua siswa hadir hari ini.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>

    @if($runningText->count() > 0)
    <div class="marquee-footer">
        <div class="marquee-content">
            @foreach($runningText as $text)
                <div class="marquee-item">
                    <i class="bi bi-broadcast"></i> {{ $text->content }}
                </div>
            @endforeach
            <!-- Repeat for seamless effect -->
             @foreach($runningText as $text)
                <div class="marquee-item">
                    <i class="bi bi-broadcast"></i> {{ $text->content }}
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <script>
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit', 
                hour12: false 
            });
            // Blinking colon effect
            const displayTime = timeString.replace(':', '<span style="opacity: ' + (now.getSeconds() % 2 == 0 ? 1 : 0.5) + '">:</span>');
            document.getElementById('clock').innerHTML = displayTime;
        }

        setInterval(updateClock, 1000);
        updateClock();

        // Auto Scroll Lists logic
        function autoScroll(elementClass, speed = 40) {
            const containers = document.querySelectorAll(elementClass);
            if (containers.length === 0) return;
            
            containers.forEach(container => {
                let direction = 1;
                let scrollAmount = 0;
                const scrollStep = 0.5;
                
                setInterval(() => {
                    const maxScroll = container.scrollHeight - container.clientHeight;
                    if(maxScroll <= 0) return;

                    scrollAmount += (scrollStep * direction);
                    container.scrollTop = scrollAmount;

                    if (scrollAmount >= maxScroll) {
                        setTimeout(() => direction = -1, 3000); // Longer pause at bottom
                        scrollAmount = maxScroll;
                    } else if (scrollAmount <= 0) {
                        setTimeout(() => direction = 1, 3000); // Longer pause at top
                        scrollAmount = 0;
                    }
                }, speed);
            });
        }

        // Initialize scrolls
        document.addEventListener('DOMContentLoaded', () => {
            autoScroll('.auto-scroll-list');
            autoScroll('.auto-scroll-news');
            
            // Auto Refresh logic (every 5 minutes)
            setTimeout(() => {
                window.location.reload(); 
            }, 300000);
        });

        // Fullscreen Logic
        function enterFullscreen() {
            const elem = document.documentElement;
            if (elem.requestFullscreen) {
                elem.requestFullscreen().catch(err => {
                    console.log(`Error attempting to enable fullscreen: ${err.message}`);
                });
            } else if (elem.webkitRequestFullscreen) { /* Safari */
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) { /* IE11 */
                elem.msRequestFullscreen();
            }
        }

        // On Load Attempt
        document.addEventListener('DOMContentLoaded', () => {
             // Check if already fullscreen
             if (!document.fullscreenElement) {
                 enterFullscreen();
             }
        });

        // Double click body to toggle
        document.body.addEventListener('dblclick', () => {
             if (!document.fullscreenElement) {
                enterFullscreen();
             } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
             }
        });
    </script>
</body>
</html>
