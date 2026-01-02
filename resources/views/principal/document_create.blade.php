@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Buat Permintaan Dokumen Baru</h4>
                    <a href="{{ route('principal.documents') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('principal.documents.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: RPP Semester 1, Laporan Wali Kelas..." required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi & Instruksi <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                            <div class="form-text">Jelaskan detail dokumen yang diminta, format file, dan instruksi lainnya.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tahun Pelajaran <span class="text-danger">*</span></label>
                                <select name="academic_year_id" class="form-select" required>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ $year->status == 'active' ? 'selected' : '' }}>{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Semester <span class="text-danger">*</span></label>
                                <select name="semester" class="form-select" required>
                                    <option value="ganjil" {{ (old('semester') ?? 'ganjil') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="genap" {{ old('semester') == 'genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Batas Waktu (Deadline) <span class="text-danger">*</span></label>
                                <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}" required>
                            </div>
                        </div>

                        <hr>
                        <h5>Target Guru</h5>
                        <p class="text-muted small">Pilih Unit dan (opsional) Guru Spesifik. Jika Guru tidak dipilih, permintaan akan dikirim ke <strong>SEMUA</strong> guru di unit yang dipilih.</p>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Unit (Wajib Pilih Minimal 1)</label>
                            <div class="d-flex gap-3 flex-wrap">
                                @foreach($units as $unit)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="target_units[]" value="{{ $unit->id }}" id="unit_{{ $unit->id }}" checked>
                                        <label class="form-check-label" for="unit_{{ $unit->id }}">
                                            {{ $unit->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('target_units') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Guru Spesifik (Opsional)</label>
                            <select name="target_users[]" class="form-select select2" multiple data-placeholder="Pilih Guru Tertentu (Kosongkan untuk Semua Guru di Unit)">
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ in_array($teacher->id, old('target_users', [])) ? 'selected' : '' }}>
                                        {{ $teacher->name }} 
                                        ({{ $teacher->jabatanUnits->pluck('unit.name')->unique()->join(', ') }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Jika Anda memilih guru tertentu, permintaan HANYA akan muncul untuk mereka (mengabaikan filter unit di atas, namun Unit tetap wajib dipilih untuk pengelompokan).
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                             <a href="{{ route('principal.documents') }}" class="btn btn-light me-2">Batal</a>
                             <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Buat Permintaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: false,
        });
    });
</script>
@endsection
