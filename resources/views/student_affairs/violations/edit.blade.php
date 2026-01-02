@extends('layouts.app')

@section('title', 'Edit Pelanggaran')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h3 class="card-title mb-0 h5"><i class="bi bi-pencil-square me-2"></i> Edit Data Pelanggaran</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('student-affairs.violations.update', $violation->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Siswa <span class="text-danger">*</span></label>
                            <select name="student_id" class="form-select" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id', $violation->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->nama_lengkap }} ({{ $student->schoolClass->first()->name ?? 'Tanpa Kelas' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control" value="{{ old('date', $violation->date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Pelanggaran <span class="text-danger">*</span></label>
                                <select name="violation_type" class="form-select" required>
                                    <option value="Ringan" {{ old('violation_type', $violation->violation_type) == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                                    <option value="Sedang" {{ old('violation_type', $violation->violation_type) == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                    <option value="Berat" {{ old('violation_type', $violation->violation_type) == 'Berat' ? 'selected' : '' }}>Berat</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Pelanggaran <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="3" required>{{ old('description', $violation->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bukti Pelanggaran (Opsional)</label>
                                <input type="file" name="proof" class="form-control" accept="image/*">
                                @if($violation->proof)
                                    <small class="d-block mt-1">File saat ini: <a href="{{ asset('storage/' . $violation->proof) }}" target="_blank">Lihat</a></small>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Poin Pelanggaran</label>
                                <input type="number" name="points" class="form-control" value="{{ old('points', $violation->points) }}" min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="needFollowUp" name="need_follow_up" {{ (old('need_follow_up') || $violation->follow_up) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="needFollowUp">Perlu Tindak Lanjut?</label>
                            </div>
                        </div>

                        <!-- Follow Up Fields -->
                        <div id="follow-up-fields" style="display: none;" class="border p-3 rounded bg-light mb-3 border-danger">
                            <h6 class="text-danger fw-bold"><i class="bi bi-exclamation-circle-fill"></i> Detail Tindak Lanjut</h6>
                            <div class="mb-3">
                                <label class="form-label">Aksi Tindak Lanjut</label>
                                <input type="text" name="follow_up" class="form-control" value="{{ old('follow_up', $violation->follow_up) }}" placeholder="Contoh: Pemanggilan Orang Tua, Skorsing">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hasil Tindak Lanjut</label>
                                <textarea name="follow_up_result" class="form-control" rows="2" placeholder="Contoh: Orang tua hadir, siswa membuat surat pernyataan.">{{ old('follow_up_result', $violation->follow_up_result) }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('student-affairs.violations.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i> Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        // Follow Up Toggle
        const toggleWrapper = $('#follow-up-fields');
        const toggleCheckbox = $('#needFollowUp');

        function toggleFollowUp() {
            if (toggleCheckbox.is(':checked')) {
                toggleWrapper.slideDown();
            } else {
                toggleWrapper.slideUp();
            }
        }

        toggleCheckbox.on('change', toggleFollowUp);
        toggleFollowUp(); // Run on load
    });
</script>
@endpush
