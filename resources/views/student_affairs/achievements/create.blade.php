@extends('layouts.app')

@section('title', 'Catat Prestasi')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0 h5"><i class="bi bi-award me-2"></i> Form Prestasi Siswa</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('student-affairs.achievements.create') }}" method="GET" id="filterForm">
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

                    <form action="{{ route('student-affairs.achievements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Siswa (Bisa Pilih Banyak) <span class="text-danger">*</span></label>
                            <select name="student_ids[]" id="student_select" class="form-select select2" multiple="multiple" required>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" 
                                        {{ (is_array(old('student_ids')) && in_array($student->id, old('student_ids'))) ? 'selected' : '' }}>
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
                                <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                                <select name="level" class="form-select" required>
                                    <option value="">-- Pilih Tingkat --</option>
                                    <option value="Sekolah" {{ old('level') == 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                                    <option value="Kecamatan" {{ old('level') == 'Kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                                    <option value="Kabupaten/Kota" {{ old('level') == 'Kabupaten/Kota' ? 'selected' : '' }}>Kabupaten/Kota</option>
                                    <option value="Provinsi" {{ old('level') == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                                    <option value="Nasional" {{ old('level') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                    <option value="Internasional" {{ old('level') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Kegiatan / Lomba <span class="text-danger">*</span></label>
                            <input type="text" name="achievement_name" class="form-control" value="{{ old('achievement_name') }}" required placeholder="Contoh: Lomba Matematika Tingkat Kabupaten">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peringkat / Juara</label>
                                <input type="text" name="rank" class="form-control" value="{{ old('rank') }}" placeholder="Contoh: Juara 1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bukti (Foto/Sertifikat)</label>
                                <input type="file" name="proof" class="form-control" accept="image/*,.pdf">
                                <small class="text-muted">Max: 2MB. Format: Image/PDF</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan Tambahan/Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('student-affairs.achievements.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan Prestasi</button>
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
        } else {
            console.warn('Select2 not loaded');
        }
    });
</script>
@endpush
