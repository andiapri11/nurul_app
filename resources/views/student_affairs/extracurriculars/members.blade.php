@extends('layouts.app')

@section('title', 'Anggota Ekstrakurikuler')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-3">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info border-0 shadow-sm mb-3">
            <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning border-0 shadow-sm mb-3">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('warning') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0 h6"><i class="bi bi-info-circle me-1"></i> Detail Ekskul</h3>
                    <a href="{{ route('student-affairs.extracurriculars.achievements', $extracurricular->id) }}" class="btn btn-light btn-sm fw-bold" title="Capaian & Laporan">
                        <i class="bi bi-trophy-fill"></i>
                    </a>
                </div>
                <div class="card-body">
                    <h5 class="fw-bold text-primary">{{ $extracurricular->name }}</h5>
                    <p class="mb-1"><strong>Unit:</strong> {{ $extracurricular->unit->name }}</p>
                    <p class="mb-1"><strong>Pembina:</strong> {{ $extracurricular->coach_name ?? '-' }}</p>
                    <p class="mb-0 text-muted small">{{ $extracurricular->description }}</p>
                </div>
            </div>

            @if($isViewingActiveYear)
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0 h6"><i class="bi bi-person-plus me-1"></i> Tambah Anggota</h3>
                </div>
                <div class="card-body">
                    <form id="student_enrollment_form" action="{{ route('student-affairs.extracurriculars.add-member', $extracurricular->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="unit_id" value="{{ $selectedUnitId }}">
                        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Tahun Pelajaran <span class="text-danger">*</span></label>
                            <input type="hidden" name="academic_year_id" value="{{ $academicYearId }}">
                            <select class="form-select form-select-sm" disabled>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ $academicYearId == $ay->id ? 'selected' : '' }}>
                                        {{ $ay->name }} {{ ucfirst($ay->semester) }} (Aktif)
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Anggota baru otomatis masuk ke TP Aktif.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Unit Pendidikan</label>
                            <select id="unit_filter" class="form-select form-select-sm" onchange="window.location.href='{{ route('student-affairs.extracurriculars.members', $extracurricular->id) }}?academic_year_id={{ $academicYearId }}&unit_id=' + this.value">
                                @foreach($allowedUnits as $unit)
                                    <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Filter Kelas</label>
                            <select id="class_filter" class="form-select form-select-sm" onchange="window.location.href='{{ route('student-affairs.extracurriculars.members', $extracurricular->id) }}?academic_year_id={{ $academicYearId }}&unit_id={{ $selectedUnitId }}&class_id=' + this.value">
                                <option value="">-- Semua Kelas --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih kelas untuk mempermudah mencari siswa.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Pilih Siswa <span class="text-danger">*</span></label>
                            <select name="student_ids[]" id="student_select" class="form-select select2" required>
                                <option value=""></option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->nama_lengkap }} ({{ optional($student->schoolClass->first())->name ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-success small mt-1 d-block"><i class="bi bi-info-circle"></i> Pilih siswa untuk langsung menambahkan.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Jabatan/Peran</label>
                            <input type="text" name="role" id="role_input" class="form-control form-control-sm" value="Anggota" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" id="submit_btn" class="btn btn-success btn-sm d-none">
                                <i class="bi bi-plus-circle"></i> Tambahkan
                            </button>

                            @if(count($students) > 0)
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addAllStudents()">
                                <i class="bi bi-person-plus-fill"></i> Masukan Semua Siswa ({{ count($students) }})
                            </button>
                            <input type="hidden" name="add_all" id="add_all_field" value="0">
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            @else
            <div class="alert alert-warning border-0 shadow-sm shadow-sm">
                <i class="bi bi-info-circle-fill me-2"></i>
                Anda sedang melihat data <strong>Arsip</strong>. Penambahan atau penghapusan anggota hanya dapat dilakukan pada Tahun Pelajaran Aktif.
            </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0 h6 fw-bold">Daftar Anggota</h3>
                    <form action="{{ route('student-affairs.extracurriculars.members', $extracurricular->id) }}" method="GET" class="d-flex gap-2">
                        <select name="academic_year_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $academicYearId == $ay->id ? 'selected' : '' }}>
                                    TP {{ $ay->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jabatan</th>
                                    <th width="100" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $member)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $member->student->nama_lengkap }}</div>
                                        <div class="text-muted small">NIS: {{ $member->student->nis }}</div>
                                    </td>
                                    <td>{{ optional($member->student->schoolClass->first())->name ?? '-' }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $member->role }}</span></td>
                                    <td class="text-center">
                                        @if($isViewingActiveYear)
                                        <form action="{{ route('student-affairs.extracurriculars.remove-member', $member->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus siswa dari ekskul ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm border-0" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @else
                                        <span class="badge bg-light text-muted"><i class="bi bi-lock-fill"></i> Terarsip</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-people fs-1 d-block mb-3"></i>
                                        Belum ada anggota untuk tahun pelajaran ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('student-affairs.extracurriculars.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* No custom styles needed for single select */
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof $.fn.select2 !== 'undefined') {
            $('#student_select').select2({
                theme: 'bootstrap-5',
                placeholder: '-- Pilih Siswa --',
                allowClear: true,
                width: '100%'
            });

            // Re-enable auto-submit when student is selected
            $('#student_select').on('select2:select', function(e) {
                const btn = $('#submit_btn');
                btn.html('<span class="spinner-border spinner-border-sm"></span> Menambahkan...');
                btn.removeClass('d-none').prop('disabled', true);
                $(this).closest('form').submit();
            });
        }
    });

    function addAllStudents() {
        const count = "{{ count($students) }}";
        if (confirm('Apakah Anda yakin ingin memasukkan semua siswa (' + count + ') yang ada di filter ini?')) {
            $('#add_all_field').val('1');
            $('#student_select').prop('required', false);
            
            // Show loading state on the actual "Add All" button
            const btn = event.target.closest('button');
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menambahkan...';
            btn.disabled = true;

            $('#student_enrollment_form').submit();
        }
    }
</script>
@endpush
@endsection
