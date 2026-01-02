@extends('layouts.app')

@section('title', 'Edit Jabatan')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning py-3">
                    <h5 class="m-0 fw-bold text-dark"><i class="bi bi-pencil-square me-2"></i>Edit Jabatan Master</h5>
                </div>
                
                @if ($errors->any())
                    <div class="alert alert-danger mx-3 mt-3 shadow-sm border-0">
                        <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-2"></i> Periksa kembali input Anda:</div>
                        <ul class="mb-0 small ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('jabatans.update', $jabatan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label small fw-bold text-muted mb-1" for="unit_id"><i class="bi bi-building me-1"></i>Unit (Opsional)</label>
                                    <select name="unit_id" class="form-select shadow-sm">
                                        <option value="">-- Umum / Semua Unit --</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ $jabatan->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small fw-bold text-muted mb-1" for="kode_jabatan"><i class="bi bi-hash me-1"></i>Kode Jabatan</label>
                                    <input type="text" name="kode_jabatan" value="{{ $jabatan->kode_jabatan }}" class="form-control shadow-sm bg-light" id="kode_jabatan" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small fw-bold text-muted mb-1" for="nama_jabatan"><i class="bi bi-type me-1"></i>Nama Jabatan</label>
                                    <input type="text" name="nama_jabatan" value="{{ $jabatan->nama_jabatan }}" class="form-control shadow-sm" id="nama_jabatan" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small fw-bold text-muted mb-1" for="kategori"><i class="bi bi-tag me-1"></i>Kategori</label>
                                    <div class="d-flex gap-2">
                                        <input type="radio" class="btn-check" name="kategori" id="kat_guru" value="guru" {{ $jabatan->kategori == 'guru' ? 'checked' : '' }} autocomplete="off">
                                        <label class="btn btn-outline-success btn-sm flex-fill" for="kat_guru">Guru (Pendidik)</label>

                                        <input type="radio" class="btn-check" name="kategori" id="kat_staff" value="staff" {{ $jabatan->kategori == 'staff' ? 'checked' : '' }} autocomplete="off">
                                        <label class="btn btn-outline-secondary btn-sm flex-fill" for="kat_staff">Staff (Tendik)</label>

                                        <input type="radio" class="btn-check" name="kategori" id="kat_tambahan" value="tambahan" {{ $jabatan->kategori == 'tambahan' ? 'checked' : '' }} autocomplete="off">
                                        <label class="btn btn-outline-info btn-sm flex-fill" for="kat_tambahan">Tambahan</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small fw-bold text-muted mb-1" for="tipe"><i class="bi bi-diagram-3 me-1"></i>Tipe Jabatan</label>
                                    <select name="tipe" class="form-select shadow-sm" required>
                                        <option value="fungsional" {{ $jabatan->tipe == 'fungsional' ? 'selected' : '' }}>Fungsional (Massal, cth: Guru Mapel)</option>
                                        <option value="struktural" {{ $jabatan->tipe == 'struktural' ? 'selected' : '' }}>Struktural (Tunggal, cth: Kepala Sekolah)</option>
                                        <option value="tambahan" {{ $jabatan->tipe == 'tambahan' ? 'selected' : '' }}>Tugas Tambahan (cth: Wali Kelas)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light p-3 text-end">
                        <a href="{{ route('jabatans.index') }}" class="btn btn-link text-muted text-decoration-none me-3">Batal</a>
                        <button type="submit" class="btn btn-warning px-5 shadow-sm fw-bold text-dark">
                            <i class="bi bi-check-lg me-1"></i> Perbarui Jabatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
