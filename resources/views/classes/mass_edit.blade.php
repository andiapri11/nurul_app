@extends('layouts.app')

@section('title', 'Update Massal Kelas & Siswa')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-1"><i class="bi bi-pencil-square me-2"></i>Update Massal Kelas & Siswa</h3>
            <p class="text-muted mb-0">Update nama kelas, wali kelas, dan daftar siswa secara sekaligus untuk Tahun Ajaran <strong>{{ $activeYear->name }}</strong>.</p>
        </div>
        <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-octagon-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <form action="{{ route('classes.mass-update') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-uppercase small fw-bold text-secondary" style="width: 10%;">Unit</th>
                                <th class="py-3 text-uppercase small fw-bold text-secondary" style="width: 20%;">Nama Kelas</th>
                                <th class="py-3 text-uppercase small fw-bold text-secondary" style="width: 25%;">Wali Kelas</th>
                                <th class="py-3 text-uppercase small fw-bold text-secondary" style="width: 45%;">Daftar Siswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $class)
                                @php
                                    $currentStudentIds = $class->studentHistory->pluck('id')->toArray();
                                @endphp
                                <tr>
                                    <td class="px-4">
                                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill small">
                                            {{ $class->unit->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="text" name="classes[{{ $class->id }}][name]" value="{{ $class->name }}" class="form-control border-2 shadow-sm" required>
                                    </td>
                                    <td>
                                        <select name="classes[{{ $class->id }}][teacher_id]" class="form-select border-2 shadow-sm">
                                            <option value="">-- Pilih Wali Kelas --</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ $class->teacher_id == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="classes[{{ $class->id }}][student_ids][]" class="form-select select2-students border-2 shadow-sm" multiple data-placeholder="Pilih siswa...">
                                            {{-- Siswa yang sudah ada di kelas ini --}}
                                            @foreach($class->studentHistory as $student)
                                                <option value="{{ $student->id }}" selected>
                                                    {{ $student->nama_lengkap }} ({{ $student->nisn ?? 'No NISN' }})
                                                </option>
                                            @endforeach
                                            {{-- Siswa yang belum masuk kelas manapun --}}
                                            @foreach($availableStudents as $student)
                                                <option value="{{ $student->id }}">
                                                    {{ $student->nama_lengkap }} ({{ $student->nisn ?? 'No NISN' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="mt-1 small text-muted">
                                            <i class="bi bi-info-circle me-1"></i> Total: <strong>{{ count($currentStudentIds) }}</strong> siswa
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-3 opacity-25"></i>
                                        Tidak ada kelas yang ditemukan di tahun ajaran aktif.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($classes->count() > 0)
                    <div class="p-4 bg-light border-top">
                        <div class="d-flex justify-content-end align-items-center gap-3">
                            <span class="text-muted small"><i class="bi bi-info-circle me-1"></i> Perubahan pada daftar siswa akan langsung memperbarui status kelas siswa tersebut.</span>
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill" onclick="return confirm('Simpan semua perubahan?')">
                                <i class="bi bi-save me-2"></i>Simpan Semua Perubahan
                            </button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
    .select2-container--bootstrap-5 .select2-selection {
        border-width: 2px !important;
        border-radius: 8px !important;
        min-height: 45px !important;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
        background-color: #e7f1ff !important;
        border: 1px solid #0d6efd !important;
        color: #0d6efd !important;
        border-radius: 4px !important;
        margin-top: 6px !important;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2-students').select2({
            theme: 'bootstrap-5',
            closeOnSelect: false,
            placeholder: $(this).data('placeholder'),
            allowClear: true
        });
    });
</script>
@endpush
@endsection
