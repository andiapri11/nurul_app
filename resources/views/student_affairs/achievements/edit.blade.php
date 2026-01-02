@extends('layouts.app')

@section('title', 'Edit Prestasi')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h3 class="card-title mb-0 h5"><i class="bi bi-pencil-square me-2"></i> Edit Data Prestasi</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('student-affairs.achievements.update', $achievement->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Siswa <span class="text-danger">*</span></label>
                            <select name="student_id" class="form-select" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id', $achievement->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->nama_lengkap }} ({{ $student->schoolClass->first()->name ?? 'Tanpa Kelas' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control" value="{{ old('date', $achievement->date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                                <select name="level" class="form-select" required>
                                    <option value="Sekolah" {{ old('level', $achievement->level) == 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                                    <option value="Kecamatan" {{ old('level', $achievement->level) == 'Kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                                    <option value="Kabupaten/Kota" {{ old('level', $achievement->level) == 'Kabupaten/Kota' ? 'selected' : '' }}>Kabupaten/Kota</option>
                                    <option value="Provinsi" {{ old('level', $achievement->level) == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                                    <option value="Nasional" {{ old('level', $achievement->level) == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                    <option value="Internasional" {{ old('level', $achievement->level) == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Kegiatan / Lomba <span class="text-danger">*</span></label>
                            <input type="text" name="achievement_name" class="form-control" value="{{ old('achievement_name', $achievement->achievement_name) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peringkat / Juara</label>
                                <input type="text" name="rank" class="form-control" value="{{ old('rank', $achievement->rank) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bukti (Ganti Foto/Sertifikat)</label>
                                <input type="file" name="proof" class="form-control" accept="image/*,.pdf">
                                @if($achievement->proof)
                                    <small class="d-block mt-1">File saat ini: <a href="{{ asset('storage/' . $achievement->proof) }}" target="_blank">Lihat</a></small>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan Tambahan</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $achievement->description) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('student-affairs.achievements.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i> Update Prestasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
