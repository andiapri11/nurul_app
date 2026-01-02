@extends('layouts.app')

@section('title', 'Catat Pelanggaran')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title mb-0 h5"><i class="bi bi-file-earmark-diff me-2"></i> Form Pelanggaran Siswa</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('student-affairs.violations.create') }}" method="GET" id="filterForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit Sekolah</label>
                                <select name="unit_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">-- Pilih Unit --</option>
                                    @foreach($allowedUnits as $unit)
                                        <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelas (Opsional)</label>
                                <select name="class_id" class="form-select" onchange="document.getElementById('filterForm').submit()" {{ $classes->isEmpty() ? 'disabled' : '' }}>
                                    <option value="">-- Semua Kelas --</option>
                                    @foreach($classes as $cls)
                                        <option value="{{ $cls->id }}" {{ $selectedClassId == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    <form action="{{ route('student-affairs.violations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Siswa (Bisa Pilih Banyak) <span class="text-danger">*</span></label>
                            <select name="student_ids[]" id="student_select" class="form-select select2" multiple="multiple" required>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" 
                                        {{ (is_array(old('student_ids', $editStudentIds ?? [])) && in_array($student->id, old('student_ids', $editStudentIds ?? []))) ? 'selected' : '' }}>
                                        {{ $student->nama_lengkap }} ({{ $student->schoolClass->first()->name ?? 'Tanpa Kelas' }}) - {{ $student->nis }}
                                    </option>
                                @endforeach
                            </select>
                            <!-- Selected Students Preview -->
                            <div id="selected-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                        </div>

<style>
    /* Hide default Select2 selection tags to avoid duplication */
    .select2-container .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
        display: none !important;
    }
    .select2-container .select2-search--inline .select2-search__field {
        margin-top: 5px;
        color: #000 !important; /* Force black text in input */
    }
    
    /* Force black text for dropdown options and search box */
    .select2-container--bootstrap-5 .select2-dropdown .select2-results__option {
        color: #000;
    }
    .select2-search__field {
        color: #000 !important;
    }
</style>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Pelanggaran <span class="text-danger">*</span></label>
                                <select name="violation_type" class="form-select" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Ringan" {{ old('violation_type') == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                                    <option value="Sedang" {{ old('violation_type') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                    <option value="Berat" {{ old('violation_type') == 'Berat' ? 'selected' : '' }}>Berat</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Pelanggaran <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bukti Pelanggaran (Opsional)</label>
                                <input type="file" name="proof" class="form-control" accept="image/*">
                                <small class="text-muted">Maksimal 2MB. Format Gambar.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Poin Pelanggaran</label>
                                <input type="number" name="points" class="form-control" value="{{ old('points', 0) }}" min="0">
                                <small class="text-muted">Isi poin jika berlaku.</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="needFollowUp" name="need_follow_up" {{ old('need_follow_up') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="needFollowUp">Perlu Tindak Lanjut?</label>
                            </div>
                            <small class="text-muted">Centang jika pelanggaran ini memerlukan penanganan lebih lanjut (misal: pemanggilan orang tua, skorsing).</small>
                        </div>

                        <!-- Follow Up Fields -->
                        <div id="follow-up-fields" style="display: none;" class="border p-3 rounded bg-light mb-3 border-danger">
                            <h6 class="text-danger fw-bold"><i class="bi bi-exclamation-circle-fill"></i> Detail Tindak Lanjut</h6>
                            <div class="mb-3">
                                <label class="form-label">Aksi Tindak Lanjut</label>
                                <input type="text" name="follow_up" class="form-control" value="{{ old('follow_up') }}" placeholder="Contoh: Pemanggilan Orang Tua, Skorsing">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-danger fw-bold small">Hasil Tindak Lanjut (Opsional)</label>
                                <textarea name="follow_up_result" class="form-control" rows="2" placeholder="Detail hasil penanganan..."></textarea>
                                <small class="text-muted">Isi jika penanganan sudah dilakukan.</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('student-affairs.violations.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-danger"><i class="bi bi-save me-1"></i> Simpan Pelanggaran</button>
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
        if (typeof $.fn.select2 !== 'undefined') {
            var $select = $('#student_select');
            
            $select.select2({
                theme: 'bootstrap-5',
                placeholder: '-- Cari dan Pilih Siswa --',
                allowClear: true,
                width: '100%',
                closeOnSelect: false
            });

            // Event listener for changes
            $select.on('change', function() {
                updateSelectedList();
            });

            // Initial update (for old data)
            updateSelectedList();

            function updateSelectedList() {
                var data = $select.select2('data');
                var container = $('#selected-preview');
                container.empty();

                if (data.length > 0) {
                     data.forEach(function(item) {
                        var el = `
                            <div class="badge bg-light text-dark border p-2 d-flex align-items-center">
                                <span class="me-2">${item.text}</span>
                                <i class="bi bi-x-circle-fill text-danger" style="cursor:pointer;" onclick="removeSelection('${item.id}')" title="Hapus"></i>
                            </div>
                        `;
                        container.append(el);
                    });
                }
            }

            // Expose function globally
            window.removeSelection = function(id) {
                var current = $select.val() || [];
                // Filter out the id (ensure type consistency, though usually strings)
                var newVal = current.filter(function(value) {
                    return value != id;
                });
                $select.val(newVal).trigger('change');
            };

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
        } else {
            console.warn('Select2 not loaded');
        }
    });
</script>
@endpush
