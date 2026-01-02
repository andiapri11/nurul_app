@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-11"> {{-- Widen slightly --}}
            <form action="{{ route('gurukaryawans.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- Left Column: Personal Data --}}
                    <div class="col-md-7">
                        <div class="card card-outline card-primary shadow-sm">
                            <div class="card-header bg-white border-bottom-0">
                                <h3 class="card-title text-primary fw-bold"><i class="bi bi-person-circle me-2"></i>Data Pribadi</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nama Lengkap beserta gelar" required>
                                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">NIP / NUPTK</label>
                                        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai">
                                        @error('nip') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@sekolah.id" required>
                                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">No. Telepon / WA</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="0812...">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
                                    </div>
                                </div>

                                <div class="div mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" value="L" id="genderL" {{ old('gender') == 'L' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="genderL">Laki-laki</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" value="P" id="genderP" {{ old('gender') == 'P' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="genderP">Perempuan</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <textarea name="address" class="form-control" rows="2" placeholder="Alamat domisili saat ini">{{ old('address') }}</textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Foto Profil</label>
                                    <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG. Maks 2MB.</small>
                                    @error('photo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Account & Employment --}}
                    <div class="col-md-5">
                        <div class="card card-outline card-success shadow-sm">
                            <div class="card-header bg-white border-bottom-0">
                                <h3 class="card-title text-success fw-bold"><i class="bi bi-shield-lock me-2"></i>Akun & Kepegawaian</h3>
                            </div>
                            <div class="card-body">
                                
                                <div class="alert alert-info py-2 px-3 mb-3">
                                    <small><i class="bi bi-info-circle me-1"></i> Data login digunakan untuk akses ke sistem.</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Username unik (tanpa spasi)" required>
                                    @error('username') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
                                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                                    </div>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <label class="form-label">Role System <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                        <option value="">- Pilih Role -</option>
                                        <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                                        <option value="karyawan" {{ old('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                    </select>
                                    @error('role') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Unit Homebase / Sekolah <span class="text-danger">*</span></label>
                                    <select name="unit_id" class="form-select @error('unit_id') is-invalid @enderror" required>
                                        <option value="">- Pilih Unit -</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Unit utama penugasan.</small>
                                    @error('unit_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jabatan (Tahan Ctrl untuk pilih banyak)</label>
                                    <select name="jabatan_ids[]" class="form-select @error('jabatan_ids') is-invalid @enderror" multiple style="height: 150px;">
                                        @foreach($jabatans as $jabatan)
                                            <option value="{{ $jabatan->id }}" {{ (collect(old('jabatan_ids'))->contains($jabatan->id)) ? 'selected' : '' }}>
                                                {{ $jabatan->nama_jabatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Jabatan yang dipilih akan dikaitkan dengan Unit Homebase saat pembuatan.</small>
                                    @error('jabatan_ids') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                            </div>
                            <div class="card-footer bg-white border-top-0 d-flex justify-content-end gap-2 mb-2">
                                <a href="{{ route('gurukaryawans.index') }}" class="btn btn-light border">Batal</a>
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan Data</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
