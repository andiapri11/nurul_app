@extends('layouts.app')

@section('title', 'Tambah Agenda Akademik')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Agenda Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('academic-calendars.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="unit_id" class="form-label">Unit Sekolah <span class="text-danger">*</span></label>
                            <select class="form-select @error('unit_id') is-invalid @enderror" id="unit_id" name="unit_id" required>
                                <option value="">Pilih Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_start" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date_start') is-invalid @enderror" id="date_start" name="date_start" value="{{ old('date_start') }}" required>
                                @error('date_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_end" class="form-label">Tanggal Selesai <small class="text-muted">(Opsional, untuk rentang waktu)</small></label>
                                <input type="date" class="form-control @error('date_end') is-invalid @enderror" id="date_end" name="date_end" value="{{ old('date_end') }}">
                                @error('date_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Keterangan / Nama Agenda <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required placeholder="Contoh: Libur Hari Raya, Class Meeting, Pembagian Raport">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="hidden" name="is_holiday" value="0">
                            <input type="checkbox" class="form-check-input" id="is_holiday" name="is_holiday" value="1" {{ old('is_holiday', 1) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_holiday">Apakah ini Hari Libur?</label>
                            <div class="form-text">Jika dicentang, tanggal ini TIDAK akan dihitung sebagai hari efektif belajar.</div>
                        </div>

                        <div class="mb-3" id="target_class_container">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="affected_classes" class="form-label fw-bold mb-0">Target Kelas <small class="text-muted">(Opsional)</small></label>
                                <div class="form-check form-check-inline mb-0">
                                    <input class="form-check-input" type="checkbox" id="selectAllClasses" style="cursor: pointer;">
                                    <label class="form-check-label small fw-bold text-primary" for="selectAllClasses" style="cursor: pointer;">Pilih Semua Kelas</label>
                                </div>
                            </div>
                            <select class="form-select select2" id="affected_classes" name="affected_classes[]" multiple data-placeholder="Cari dan pilih kelas...">
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ is_array(old('affected_classes')) && in_array($class->id, old('affected_classes')) ? 'selected' : '' }}>
                                        {{ $class->name }} ({{ $class->unit->name }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-primary">
                                <i class="bi bi-info-circle"></i> Biarkan kosong jika agenda berlaku untuk <strong>SELURUH UNIT</strong>. 
                                <br>Jika dipilih, maka kelas yang TIDAK dipilih tetap masuk/efektif seperti biasa.
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const selectAll = document.getElementById('selectAllClasses');
                                const selectEl = $('#affected_classes');

                                selectAll.addEventListener('change', function() {
                                    if (this.checked) {
                                        selectEl.find('option').prop('selected', true);
                                    } else {
                                        selectEl.find('option').prop('selected', false);
                                    }
                                    selectEl.trigger('change');
                                });

                                // Sync checkbox if user manually deselects something
                                selectEl.on('change', function() {
                                    const total = selectEl.find('option').length;
                                    const selected = selectEl.find('option:selected').length;
                                    selectAll.checked = (total === selected && total > 0);
                                });
                            });
                        </script>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('academic-calendars.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan Agenda</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
