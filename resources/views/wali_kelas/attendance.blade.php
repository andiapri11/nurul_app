@extends('layouts.app')

@section('title', 'Input Absensi Siswa')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0 h5"><i class="bi bi-calendar-check me-2"></i> Input Absensi: {{ $myClass ? $myClass->name : 'Pilih Kelas' }}</h3>
            <div class="d-flex align-items-center gap-3">
                @if(isset($attendances) && $attendances->count() > 0)
                    <span class="badge bg-success border border-light shadow-sm px-3 py-2 rounded-pill">
                        <i class="bi bi-check-all me-1"></i> SUDAH TERISI ({{ $attendances->count() }}/{{ $students->count() ?? 0 }})
                    </span>
                @else
                    <span class="badge bg-light text-dark shadow-sm px-3 py-2 rounded-pill">
                        <i class="bi bi-clock-history me-1"></i> BELUM TERISI
                    </span>
                @endif
                <div class="text-white-50 small d-none d-md-block">{{ $myClass && $myClass->unit ? $myClass->unit->name : '' }}</div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success"><i class="bi bi-check-circle me-1"></i> {{ session('success') }}</div>
            @endif

            <form action="{{ route('wali-kelas.attendance') }}" method="GET" class="mb-4 p-3 bg-light rounded shadow-sm">
                <div class="row g-2 align-items-end">
                    {{-- Filter Tahun Pelajaran --}}
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

                    {{-- Filter Unit (Admin Only) --}}
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

                    {{-- Filter Kelas (Admin Only) --}}
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

                    <div class="col-md-3">
                         <label class="form-label fw-bold small">Pilih Tanggal</label>
                         <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}" onchange="this.form.submit()">
                    </div>

                    <div class="col-md-3 ms-auto text-end">
                        <div class="btn-group mt-3" role="group">
                             <button type="button" class="btn btn-outline-success btn-sm" onclick="setAll('present')" title="Set Semua Hadir"><i class="bi bi-check-lg"></i> H</button>
                             <button type="button" class="btn btn-outline-primary btn-sm" onclick="setAll('school_activity')" title="Set Semua Kegiatan"><i class="bi bi-flag"></i> K</button>
                             <button type="button" class="btn btn-outline-info btn-sm" onclick="setAll('sick')" title="Set Semua Sakit">S</button>
                             <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAll('empty')" title="Reset"><i class="bi bi-x-lg"></i></button>
                        </div>
                        <a href="{{ route('wali-kelas.index') }}" class="btn btn-secondary btn-sm mt-3 ms-1"><i class="bi bi-arrow-left"></i></a>
                    </div>
                </div>
            </form>

            @if(isset($myClass) && $myClass)
                @if(isset($isHoliday) && $isHoliday)
                    <div class="text-center py-5">
                        @if(isset($isOutsideAcademicYear) && $isOutsideAcademicYear)
                            <i class="bi bi-calendar-x fs-1 text-secondary d-block mb-3 opacity-50"></i>
                            <h4 class="text-secondary fw-bold">DILUAR TAHUN PELAJARAN</h4>
                            <p class="fs-5 text-muted">{{ $calendarDescription }}</p>
                        @else
                            <i class="bi bi-emoji-sunglasses fs-1 text-danger d-block mb-3"></i>
                            <h4 class="text-danger fw-bold">ABSENSI NON-AKTIF (HARI LIBUR)</h4>
                            <p class="fs-5 text-muted">{{ $calendarDescription ?? 'Libur' }}</p>
                        @endif
                        
                        <div class="alert {{ (isset($isOutsideAcademicYear) && $isOutsideAcademicYear) ? 'alert-secondary' : 'alert-warning' }} d-inline-block mt-3 bg-light text-dark">
                            <i class="bi bi-info-circle-fill me-1"></i> Tidak perlu mengisi absensi siswa pada tanggal ini.
                        </div>
                    </div>
                @else
                    @if(isset($effectiveDayDescription) && $effectiveDayDescription)
                        <div class="alert alert-info border-info shadow-sm mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar-event-fill fs-4 me-3 text-info"></i>
                                <div>
                                    <h6 class="fw-bold mb-0 text-info">Agenda Kalender Akademik</h6>
                                    <div>{{ $effectiveDayDescription }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($attendances) && $attendances->count() > 0)
                        <div class="alert alert-success border-success shadow-sm mb-4 d-flex align-items-center justify-content-between" id="lock-alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-lock-fill fs-4 me-3 text-success"></i>
                                <div>
                                    <h6 class="fw-bold mb-0 text-success">Absensi Terkunci</h6>
                                    <small class="text-muted">Data absensi hari ini sudah tersimpan. Klik tombol <strong>Buka Kunci</strong> jika ingin mengubah data.</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-warning btn-sm fw-bold px-3 shadow-sm" id="btn-unlock" onclick="unlockForm()">
                                <i class="bi bi-pencil-square me-1"></i> Buka Kunci (Edit)
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('wali-kelas.store-attendance') }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                @if(Auth::user()->role === 'administrator' && isset($myClass))
                     <input type="hidden" name="class_id" value="{{ $myClass->id }}">
                @endif
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light text-center align-middle">
                            <tr>
                                <th width="40" rowspan="2">No</th>
                                <th rowspan="2">Nama Siswa</th>
                                <th colspan="6">Status Absensi</th>
                                <th rowspan="2">Catatan</th>
                            </tr>
                            <tr class="small">
                                <th width="60" class="text-success" title="Hadir">H</th>
                                <th width="60" class="text-primary" title="Kegiatan">K</th>
                                <th width="60" class="text-info" title="Sakit">S</th>
                                <th width="60" class="text-warning" title="Izin">I</th>
                                <th width="60" class="text-danger" title="Alpa">A</th>
                                <th width="60" class="text-secondary" title="Terlambat">T</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                @php
                                    $att = $attendances[$student->id] ?? null;
                                    $status = $att ? $att->status : 'present';
                                    $notes = $att ? $att->notes : '';
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $student->nama_lengkap }}</div>
                                        <div class="small text-muted" style="font-size: 0.75rem;">{{ $student->nis }}</div>
                                    </td>
                                    
                                    {{-- Custom Radio Group style --}}
                                    <td class="text-center p-1 bg-success-subtle">
                                        <input class="form-check-input attendance-input" type="radio" name="attendances[{{ $student->id }}][status]" value="present" {{ $status == 'present' ? 'checked' : '' }} {{ $attendances->count() > 0 ? 'disabled' : '' }}>
                                    </td>
                                    <td class="text-center p-1 bg-primary-subtle">
                                        <input class="form-check-input attendance-input" type="radio" name="attendances[{{ $student->id }}][status]" value="school_activity" {{ $status == 'school_activity' ? 'checked' : '' }} {{ $attendances->count() > 0 ? 'disabled' : '' }}>
                                    </td>
                                    <td class="text-center p-1 bg-info-subtle">
                                        <input class="form-check-input attendance-input" type="radio" name="attendances[{{ $student->id }}][status]" value="sick" {{ $status == 'sick' ? 'checked' : '' }} {{ $attendances->count() > 0 ? 'disabled' : '' }}>
                                    </td>
                                    <td class="text-center p-1 bg-warning-subtle">
                                        <input class="form-check-input attendance-input" type="radio" name="attendances[{{ $student->id }}][status]" value="permission" {{ $status == 'permission' ? 'checked' : '' }} {{ $attendances->count() > 0 ? 'disabled' : '' }}>
                                    </td>
                                    <td class="text-center p-1 bg-danger-subtle">
                                        <input class="form-check-input attendance-input" type="radio" name="attendances[{{ $student->id }}][status]" value="alpha" {{ $status == 'alpha' ? 'checked' : '' }} {{ $attendances->count() > 0 ? 'disabled' : '' }}>
                                    </td>
                                    <td class="text-center p-1 bg-secondary-subtle">
                                        <input class="form-check-input attendance-input" type="radio" name="attendances[{{ $student->id }}][status]" value="late" {{ $status == 'late' ? 'checked' : '' }} {{ $attendances->count() > 0 ? 'disabled' : '' }}>
                                    </td>

                                    <td class="p-1">
                                        <input type="text" name="attendances[{{ $student->id }}][notes]" class="form-control form-control-sm border-0 bg-transparent attendance-input" placeholder="Ket..." value="{{ $notes }}" {{ $attendances->count() > 0 ? 'disabled' : '' }}>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="bi bi-info-circle me-2"></i> Tidak ada siswa aktif di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                     @if(Auth::user()->role === 'administrator')
                        <button type="button" class="btn btn-outline-danger px-3 py-2" onclick="confirmDelete()">
                            <i class="bi bi-trash"></i> Hapus Absensi
                        </button>
                    @else
                        <div></div> {{-- Spacer --}}
                    @endif

                    <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm fw-bold" id="btn-save" {{ $attendances->count() > 0 ? 'style=display:none' : '' }}>
                        <i class="bi bi-save me-1"></i> SIMPAN ABSENSI
                    </button>
                    <div id="edit-mode-indicator" style="display: none;" class="text-primary fw-bold">
                        <i class="bi bi-pencil-fill me-1"></i> Mode Edit Aktif
                    </div>
                </div>
            </form>
            
            @if(Auth::user()->role === 'administrator')
            <form id="delete-form" action="{{ route('wali-kelas.destroy-attendance') }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
                <input type="hidden" name="date" value="{{ $date }}">
                @if(isset($myClass))
                     <input type="hidden" name="class_id" value="{{ $myClass->id }}">
                @endif
            </form>
            <script>
                function confirmDelete() {
                    if(confirm('Apakah anda yakin ingin menghapus SELURUH data absensi pada tanggal ini? Tindakan ini tidak dapat dibatalkan.')) {
                        document.getElementById('delete-form').submit();
                    }
                }
            </script>
            @endif
                @endif
        @else
            <div class="alert alert-info py-5 text-center my-4">
                 <i class="bi bi-info-circle fs-1 d-block mb-3 text-info"></i>
                 <h4 class="fw-bold">Tidak ada kelas yang dipilih</h4>
                 <p class="mb-0">Silakan pilih Tahun Pelajaran atau Kelas pada filter di atas untuk menampilkan data.</p>
            </div>
        @endif
        </div>
    </div>
</div>

<script>
    function setAll(status) {
        // Only allow if not locked or currently in edit mode
        if (document.getElementById('btn-save').style.display === 'none') {
            alert('Silakan klik tombol Buka Kunci (Edit) terlebih dahulu untuk mengubah data.');
            return;
        }

        if(status === 'empty') {
            document.querySelectorAll('input[type="radio"]').forEach(el => el.checked = false);
            // Default back to present usually? Or strict reset?
            // Let's set to present if reset
             document.querySelectorAll(`input[value="present"]`).forEach(radio => radio.checked = true);
        } else {
            document.querySelectorAll(`input[value="${status}"]`).forEach(radio => {
                radio.checked = true;
            });
        }
    }

    function unlockForm() {
        if(confirm('Buka kunci untuk mengedit absensi?')) {
            // Enable all inputs
            document.querySelectorAll('.attendance-input').forEach(el => {
                el.disabled = false;
            });

            // Toggle Buttons
            document.getElementById('btn-unlock').style.display = 'none';
            document.getElementById('btn-save').style.display = 'block';
            document.getElementById('edit-mode-indicator').style.display = 'block';
            document.getElementById('lock-alert').classList.remove('alert-success');
            document.getElementById('lock-alert').classList.add('alert-warning');
            document.getElementById('lock-alert').querySelector('h6').innerText = 'Mode Edit: Kunci Terbuka';
        }
    }
</script>
@endsection
