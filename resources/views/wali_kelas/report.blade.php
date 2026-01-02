@extends('layouts.app')

@section('title', 'Laporan Absensi Siswa')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-journal-text me-2"></i> {{ $title }}
            </h5>
            <div class="d-flex gap-2">
                @php
                    // Build query params for export
                    $queryParams = request()->all();
                @endphp
                <a href="{{ route('wali-kelas.export-report', $queryParams) }}" target="_blank" class="btn btn-danger btn-sm">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>
                <a href="{{ route('wali-kelas.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>
        <div class="card-body">
            
            {{-- Filter Section --}}
            <form action="{{ route('wali-kelas.report') }}" method="GET" class="row g-2 mb-4 align-items-end p-3 bg-light rounded">
                <input type="hidden" name="type" id="filterType" value="{{ $type }}">
                @if(Auth::user()->role === 'administrator' && session()->has('wali_kelas_class_id'))
                     <input type="hidden" name="class_id" value="{{ session('wali_kelas_class_id') }}">
                @endif

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Jenis Laporan</label>
                    <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="daily" {{ $type == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ $type == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ $type == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        <option value="semester" {{ $type == 'semester' ? 'selected' : '' }}>Semester</option>
                    </select>
                </div>

                {{-- Filters duplicate from attendance --}}
                @if(isset($academicYears) && $academicYears->count() > 0)
                <div class="col-md-2">
                     <label class="form-label fw-bold small">Tahun Pelajaran</label>
                     <select name="academic_year_id" class="form-select form-select-sm" onchange="this.form.submit()">
                         <option value="">-- Pilih --</option>
                         @foreach($academicYears as $ay)
                             <option value="{{ $ay->id }}" {{ request('academic_year_id', $myClass->academic_year_id ?? '') == $ay->id ? 'selected' : '' }}>
                                 {{ $ay->name }} {{ ucfirst($ay->semester) }} {{ $ay->status == 'active' ? '(Aktif)' : '' }}
                             </option>
                         @endforeach
                     </select>
                </div>
                @endif
                
                @if(isset($units) && $units->count() > 0)
                <div class="col-md-2">
                     <label class="form-label fw-bold small">Unit</label>
                     <select name="unit_id" class="form-select form-select-sm" onchange="this.form.submit()">
                         <option value="">-- Semua --</option>
                         @foreach($units as $u)
                             <option value="{{ $u->id }}" {{ request('unit_id', $myClass->unit_id ?? '') == $u->id ? 'selected' : '' }}>
                                 {{ $u->name }}
                             </option>
                         @endforeach
                     </select>
                </div>
                @endif

                @if(isset($availableClasses) && $availableClasses->count() > 0)
                <div class="col-md-2">
                     <label class="form-label fw-bold small">Kelas</label>
                     <select name="class_id" class="form-select form-select-sm" onchange="this.form.submit()">
                         <option value="">-- Pilih --</option>
                         @foreach($availableClasses as $c)
                             <option value="{{ $c->id }}" {{ request('class_id', $myClass->id ?? '') == $c->id ? 'selected' : '' }}>
                                 {{ $c->name }}
                             </option>
                         @endforeach
                     </select>
                </div>
                @endif

                @if($type === 'daily' || $type === 'weekly')
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Pilih Tanggal</label>
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}">
                </div>
                @endif

                @if($type === 'monthly')
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Bulan</label>
                    <select name="month" class="form-select form-select-sm">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Tahun</label>
                    <select name="year" class="form-select form-select-sm">
                        @foreach(range(date('Y')-1, date('Y')+1) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                @if($type === 'semester')
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Semester</label>
                    <select name="semester_type" class="form-select form-select-sm">
                        <option value="ganjil" {{ request('semester_type') == 'ganjil' ? 'selected' : '' }}>Semester Ganjil (Juli - Des)</option>
                        <option value="genap" {{ request('semester_type') == 'genap' ? 'selected' : '' }}>Semester Genap (Jan - Juni)</option>
                    </select>
                </div>
                @endif

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-filter"></i> Tampilkan</button>
                </div>
            </form>

            {{-- Results Section --}}
            <div class="table-responsive">
                @if($type === 'daily')
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50" class="text-center">No</th>
                                <th>Nama Siswa</th>
                                <th class="text-center">Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                @php
                                    $att = $data['attendances'][$student->id] ?? null;
                                    $status = $att ? $att->status : 'missing';
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $student->nama_lengkap }}</td>
                                    <td class="text-center">
                                        @if($status == 'present') <span class="badge bg-success">Hadir</span>
                                        @elseif($status == 'school_activity') <span class="badge bg-primary">Kegiatan</span>
                                        @elseif($status == 'sick') <span class="badge bg-info">Sakit</span>
                                        @elseif($status == 'permission') <span class="badge bg-warning">Izin</span>
                                        @elseif($status == 'alpha') <span class="badge bg-danger">Alpa</span>
                                        @elseif($status == 'late') <span class="badge bg-secondary">Terlambat</span>
                                        @else <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $att->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">Tidak ada siswa.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                
                @elseif($type === 'weekly')
                    <table class="table table-bordered table-sm table-hover align-middle" style="font-size: 0.9rem;">
                        <thead class="table-light text-center">
                            <tr>
                                <th rowspan="2" class="align-middle">No</th>
                                <th rowspan="2" class="align-middle">Nama Siswa</th>
                                <th colspan="{{ count($data['dates_in_week']) }}">Tanggal ({{ $data['start_date']->format('d M') }} - {{ $data['end_date']->format('d M') }})</th>
                            </tr>
                            <tr>
                                @foreach($data['dates_in_week'] as $d)
                                    @php
                                        $dStr = $d->format('Y-m-d');
                                        $isHoliday = isset($data['week_holidays'][$dStr]) && $data['week_holidays'][$dStr]->is_holiday;
                                        $holidayDesc = $isHoliday ? $data['week_holidays'][$dStr]->description : '';
                                        $headerClass = $isHoliday ? 'bg-danger text-white' : '';
                                    @endphp
                                    <th class="small {{ $headerClass }}" title="{{ $holidayDesc }}">{{ $d->translatedFormat('D, d') }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $student->nama_lengkap }}</td>
                                    @foreach($data['dates_in_week'] as $d)
                                        @php
                                            $dateStr = $d->format('Y-m-d');
                                            $isHoliday = isset($data['week_holidays'][$dateStr]) && $data['week_holidays'][$dateStr]->is_holiday;
                                            
                                            $colAtts = $data['attendances'][$student->id] ?? collect();
                                            $rec = $colAtts->firstWhere('date', $d);
                                            $s = $rec ? $rec->status : '';
                                            $badgeClass = '';
                                            $code = '';
                                            
                                            if ($isHoliday) {
                                                $code = '-';
                                                $badgeClass = 'bg-danger-subtle text-muted';
                                            } else {
                                                switch($s){
                                                    case 'present': $code = 'H'; $badgeClass='text-success fw-bold'; break;
                                                    case 'school_activity': $code = 'K'; $badgeClass='text-primary fw-bold'; break;
                                                    case 'sick': $code = 'S'; $badgeClass='text-info fw-bold'; break;
                                                    case 'permission': $code = 'I'; $badgeClass='text-warning fw-bold'; break;
                                                    case 'alpha': $code = 'A'; $badgeClass='text-danger fw-bold'; break;
                                                    case 'late': $code = 'T'; $badgeClass='text-secondary fw-bold'; break;
                                                    default: $code = '-'; $badgeClass='text-muted'; break;
                                                }
                                            }
                                        @endphp
                                        <td class="text-center {{ $badgeClass }}" title="{{ $isHoliday ? 'Libur: ' . $data['week_holidays'][$dateStr]->description : $s }}">{{ $code }}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">Tidak ada siswa.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="small text-muted mt-2">
                        Legenda: 
                        <span class="fw-bold text-success me-2">H: Hadir</span>
                        <span class="fw-bold text-primary me-2">K: Kegiatan</span>
                        <span class="fw-bold text-info me-2">S: Sakit</span>
                        <span class="fw-bold text-warning me-2">I: Izin</span>
                        <span class="fw-bold text-danger me-2">A: Alpa</span>
                        <span class="fw-bold text-secondary me-2">T: Terlambat</span>
                    </div>

                @elseif($type === 'monthly' || $type === 'semester')
                    @if(isset($data['total_effective_days']))
                    <div class="alert alert-info py-2 mb-3">
                        <i class="bi bi-calendar-check me-2"></i>
                        Total Hari Efektif Belajar: <span class="fw-bold">{{ $data['total_effective_days'] }} Hari</span>
                        <div class="small text-muted mt-1">
                            (Dihitung berdasarkan Kalender Akademik Unit)
                        </div>
                    </div>
                    @endif
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th rowspan="2" class="align-middle">No</th>
                                <th rowspan="2" class="align-middle">Nama Siswa</th>
                                <th colspan="6">Rekapitulasi Kehadiran {{ $type === 'monthly' ? '(' . \Carbon\Carbon::create()->month($month)->translatedFormat('F') . ')' : '' }}</th>
                                <th rowspan="2" class="align-middle">Persentase Kehadiran</th>
                            </tr>
                            <tr>
                                <th class="text-success">Hadir</th>
                                <th class="text-primary">Kegiatan</th>
                                <th class="text-info">Sakit</th>
                                <th class="text-warning">Izin</th>
                                <th class="text-danger">Alpa</th>
                                <th class="text-secondary">Terlambat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                @php
                                    $stats = $data['summary'][$student->id];
                                    $totalDays = array_sum($stats); 
                                    
                                    // Percentage calculation (Present + Late + Activity) / Total Recorded
                                    // Activity counts as present usually
                                    $presentCount = $stats['present'] + $stats['late'] + $stats['school_activity'];
                                    $percentage = $totalDays > 0 ? round(($presentCount / $totalDays) * 100) : 0;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $student->nama_lengkap }}</td>
                                    <td class="text-center">{{ $stats['present'] }}</td>
                                    <td class="text-center fw-bold text-primary">{{ $stats['school_activity'] }}</td>
                                    <td class="text-center">{{ $stats['sick'] }}</td>
                                    <td class="text-center">{{ $stats['permission'] }}</td>
                                    <td class="text-center">{{ $stats['alpha'] }}</td>
                                    <td class="text-center">{{ $stats['late'] }}</td>
                                    <td class="text-center fw-bold">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $percentage >= 80 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $percentage }}%;" 
                                                 aria-valuenow="{{ $percentage }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                {{ $percentage }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">Tidak ada siswa.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
