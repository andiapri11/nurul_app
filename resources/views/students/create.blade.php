@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Tambah Siswa</h3>
        <a href="{{ route('students.index') }}" class="btn btn-danger">
            <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" id="studentForm">
        @csrf
        
        <div class="row">
            <!-- Left Column: Profile Card -->
            <div class="col-md-3">
                <div class="card card-outline card-info">
                    <div class="card-header bg-info text-white text-center">
                        <h5 class="card-title m-0">Foto Profil</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="my-3">
                            <div id="profilePlaceholder" class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto elevation-2" style="width: 150px; height: 150px;">
                                <i class="bi bi-person-fill" style="font-size: 5rem; color: white;"></i>
                            </div>
                            <img id="profilePreview" style="display:none; width: 150px; height: 150px; object-fit: cover;" class="img-circle elevation-2 mx-auto rounded-circle">
                        </div>
                        
                        <div class="mt-4">
                            <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">
                            <button type="button" class="btn btn-primary btn-block btn-sm" onclick="document.getElementById('photoInput').click()">
                                <i class="bi bi-image"></i> Pilih Foto
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Vertical Form -->
            <div class="col-md-9">
                <div class="card card-primary">
                    <div class="card-header bg-white">
                        <h3 class="card-title font-weight-bold text-dark">Data Lengkap Siswa</h3>
                    </div>
                    <div class="card-body">
                        
                        <!-- SECTION 0: DATA AKUN LOGIN -->
                        <h5 class="text-primary border-bottom pb-2 mb-4"><i class="bi bi-shield-lock mr-2"></i> Data Akun Login</h5>
                        
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 45px; justify-content: center;"><i class="bi bi-person-badge"></i></span>
                                    </div>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Username Login (Optional)">
                                </div>
                                <small class="text-muted">Jika kosong, akan otomatis digenerate.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Email</label>
                             <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 45px; justify-content: center;"><i class="bi bi-envelope"></i></span>
                                    </div>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="Email Login">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Password</label>
                             <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 45px; justify-content: center;"><i class="bi bi-key"></i></span>
                                    </div>
                                    <input type="password" name="password" class="form-control" required placeholder="Password">
                                </div>
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Konfirmasi Password</label>
                             <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 45px; justify-content: center;"><i class="bi bi-key-fill"></i></span>
                                    </div>
                                    <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi Password">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 1: DATA AKADEMIK -->
                        <h5 class="text-primary border-bottom pb-2 mb-4"><i class="bi bi-person-circle mr-2"></i> Data Akademik</h5>
                        
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 45px; justify-content: center;"><i class="bi bi-person"></i></span>
                                    </div>
                                    <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" required placeholder="Nama Lengkap Siswa">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">NIS</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 45px; justify-content: center;"><i class="bi bi-card-text"></i></span>
                                    </div>
                                    <input type="text" name="nis" class="form-control" value="{{ old('nis') }}" required minlength="5" maxlength="5" placeholder="5 Digit NIS">
                                </div>
                                <small class="text-muted">Harus 5 digit angka.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">NISN</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 45px; justify-content: center;"><i class="bi bi-card-list"></i></span>
                                    </div>
                                    <input type="text" name="nisn" class="form-control" value="{{ old('nisn') }}" required minlength="10" maxlength="10" placeholder="10 Digit NISN">
                                </div>
                                <small class="text-muted">Harus 10 digit angka.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Jenis Kelamin</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 45px; justify-content: center;"><i class="bi bi-gender-ambiguous"></i></span>
                                    </div>
                                    <select name="jenis_kelamin" class="form-control" required>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status Mukim</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 45px; justify-content: center;"><i class="bi bi-house-door"></i></span>
                                    </div>
                                    <select name="is_boarding" class="form-control" required>
                                        <option value="0" {{ old('is_boarding') == '0' ? 'selected' : '' }}>Non-Mukim</option>
                                        <option value="1" {{ old('is_boarding') == '1' ? 'selected' : '' }}>Mukim</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Unit</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 45px; justify-content: center;"><i class="bi bi-building"></i></span>
                                    </div>
                                    <select name="unit_id" class="form-control" required>
                                        <option value="">Select Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 45px; justify-content: center;"><i class="bi bi-check-circle"></i></span>
                                    </div>
                                    <select name="status" class="form-control" required>
                                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>AKTIF</option>
                                        <option value="lulus" {{ old('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                                        <option value="keluar" {{ old('status') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                                        <option value="pindah" {{ old('status') == 'pindah' ? 'selected' : '' }}>Pindah</option>
                                        <option value="non-aktif" {{ old('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: DETAIL ALAMAT & PRIBADI -->
                        <h5 class="text-primary border-bottom pb-2 mb-4 mt-5"><i class="bi bi-geo-alt mr-2"></i> Detail Alamat & Pribadi</h5>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tempat Lahir</label>
                                <div class="col-sm-9">
                                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}" placeholder="Tempat Lahir">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Lahir</label>
                                <div class="col-sm-9">
                                <input type="text" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" placeholder="dd/mm/yyyy" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Agama</label>
                            <div class="col-sm-9">
                                <select name="agama" class="form-control">
                                    <option value="">Pilih Agama</option>
                                    @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'] as $agama)
                                        <option value="{{ $agama }}" {{ old('agama') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Alamat</label>
                                <div class="col-sm-9">
                                <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}" placeholder="Alamat">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Rt</label>
                                <div class="col-sm-9">
                                <input type="text" name="alamat_rt" class="form-control" value="{{ old('alamat_rt') }}" placeholder="Rt">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Rw</label>
                                <div class="col-sm-9">
                                <input type="text" name="alamat_rw" class="form-control" value="{{ old('alamat_rw') }}" placeholder="Rw">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Kelurahan/Desa</label>
                                <div class="col-sm-9">
                                <input type="text" name="desa" class="form-control" value="{{ old('desa') }}" placeholder="Kelurahan/Desa">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Kecamatan</label>
                                <div class="col-sm-9">
                                <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan') }}" placeholder="Kecamatan">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Kabupaten/Kota</label>
                                <div class="col-sm-9">
                                <input type="text" name="kota" class="form-control" value="{{ old('kota') }}" placeholder="Kabupaten/Kota">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Kode Pos</label>
                                <div class="col-sm-9">
                                <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos') }}" placeholder="Kode Pos">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Hp</label>
                                <div class="col-sm-9">
                                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="No Handphone">
                            </div>
                        </div>

                        <!-- SECTION 3: WALI -->
                        <h5 class="text-primary border-bottom pb-2 mb-4 mt-5"><i class="bi bi-people mr-2"></i> Data Wali</h5>
                        
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nama Wali</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 45px; justify-content: center;"><i class="bi bi-person-badge"></i></span>
                                    </div>
                                    <input type="text" name="nama_wali" class="form-control" value="{{ old('nama_wali') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">No HP Wali</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 45px; justify-content: center;"><i class="bi bi-telephone"></i></span>
                                    </div>
                                    <input type="text" name="no_hp_wali" class="form-control" value="{{ old('no_hp_wali') }}">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-right bg-white">
                        <button type="reset" class="btn btn-warning text-white"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    /* Modern UI Customizations */
    .card {
        border: none !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05) !important;
        border-radius: 20px !important;
        overflow: hidden;
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #f0f0f0;
        padding: 20px;
    }

    /* Profile Card specific */
    .card-info .card-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        color: white;
    }

    /* Form Controls */
    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #eaeaea;
        border-right: none;
        border-radius: 10px 0 0 10px !important;
        color: #4facfe;
    }

    .form-control {
        border: 1px solid #eaeaea;
        border-left: none;
        border-radius: 0 10px 10px 0 !important;
        padding: 10px 15px;
        height: auto;
        font-size: 0.95rem;
        background-color: #fcfcfc;
    }

    .form-control:focus {
        box-shadow: none;
        background-color: #fff;
        border-color: #eaeaea;
    }

    .form-group label {
        color: #555;
        font-weight: 600;
        margin-bottom: 8px;
    }

    /* Buttons */
    .btn {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #2af598 0%, #009efd 100%);
        border: none;
        color: white;
    }

    .btn-warning {
        background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        border: none;
        color: #fff;
    }
    
    .btn-danger {
         background: linear-gradient(135deg, #ff9966 0%, #ff5e62 100%);
         border: none;
         color: white;
    }
    
    /* Profile Image */
    .img-circle {
        border: 5px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-radius: 50%;
    }
</style>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Flatpickr
        flatpickr("#tanggal_lahir", {
            dateFormat: "d/m/Y",
            allowInput: true
        });

        // Photo Preview
        const photoInput = document.getElementById('photoInput');
        const profilePreview = document.getElementById('profilePreview');
        const placeholderIcon = document.getElementById('profilePlaceholder');
        
        if (photoInput && profilePreview) {
            photoInput.addEventListener('change', function(e) { 
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePreview.src = e.target.result;
                        profilePreview.style.display = 'block'; 
                        
                        // Apply classes
                        profilePreview.className = 'img-circle elevation-2 d-block mx-auto rounded-circle';
                        
                        // Hide Placeholder
                        if (placeholderIcon) {
                            placeholderIcon.style.display = 'none';
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush
@endsection
