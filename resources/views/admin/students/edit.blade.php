@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
             @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- We assume standard Laravel resource route: update uses PUT -->
            <form action="{{ route('admin-students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- User Account Info -->
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">User Account</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="name">Username</label>
                                    <input type="text" name="name" value="{{ $student->user->name }}" class="form-control" id="name" placeholder="Enter username" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="email">Email address</label>
                                    <input type="email" name="email" value="{{ $student->user->email }}" class="form-control" id="email" placeholder="Enter email" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password">Password (Leave blank to keep current)</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm Password">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="photo">Photo Profile</label>
                                    <input type="file" name="photo" class="form-control" id="photo">
                                    @if($student->user->photo)
                                        <div class="mt-2">
                                            <img src="{{ asset('photos/' . $student->user->photo) }}" width="100px" class="img-thumbnail">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Student Data Info -->
                    <div class="col-md-6">
                         <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Data Siswa</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label for="nis">NIS</label>
                                        <input type="text" name="nis" value="{{ $student->nis }}" class="form-control" id="nis" placeholder="NIS" required>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="nisn">NISN</label>
                                        <input type="text" name="nisn" value="{{ $student->nisn }}" class="form-control" id="nisn" placeholder="NISN" required>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="nama_lengkap">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" value="{{ $student->nama_lengkap }}" class="form-control" id="nama_lengkap" placeholder="Nama Lengkap" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label for="unit_id">Unit</label>
                                        <select name="unit_id" class="form-control" required>
                                            <option value="">Select Unit</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ $student->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="class_id">Kelas</label>
                                        <select name="class_id" class="form-control" required>
                                            <option value="">Select Class</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" {{ $student->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                     <div class="col-6 mb-3">
                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="form-control" required>
                                            <option value="L" {{ $student->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ $student->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                     </div>
                                     <div class="col-6 mb-3">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control" required>
                                            <option value="aktif" {{ $student->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="lulus" {{ $student->status == 'lulus' ? 'selected' : '' }}>Lulus</option>
                                            <option value="keluar" {{ $student->status == 'keluar' ? 'selected' : '' }}>Keluar</option>
                                            <option value="pindah" {{ $student->status == 'pindah' ? 'selected' : '' }}>Pindah</option>
                                        </select>
                                     </div>
                                </div>
                                
                                 <div class="row">
                                    <div class="col-6 mb-3">
                                        <label for="tempat_lahir">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" value="{{ $student->tempat_lahir }}" class="form-control" id="tempat_lahir" placeholder="Tempat Lahir" required>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" value="{{ $student->tanggal_lahir }}" class="form-control" id="tanggal_lahir" required>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="alamat">Alamat</label>
                                    <textarea name="alamat" class="form-control" id="alamat" rows="3" required>{{ $student->alamat }}</textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label for="nama_wali">Nama Wali</label>
                                        <input type="text" name="nama_wali" value="{{ $student->nama_wali }}" class="form-control" id="nama_wali" placeholder="Nama Wali" required>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="no_hp_wali">No HP Wali</label>
                                        <input type="text" name="no_hp_wali" value="{{ $student->no_hp_wali }}" class="form-control" id="no_hp_wali" placeholder="No HP" required>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-5">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Update Data</button>
                        <a href="{{ route('admin-students.index') }}" class="btn btn-secondary btn-lg btn-block">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const unitSelect = document.querySelector('select[name="unit_id"]');
        const classSelect = document.querySelector('select[name="class_id"]');
        const currentClassId = "{{ $student->activeClass->first()?->id }}";

        function loadClasses(unitId, selectedClassId = null) {
            classSelect.innerHTML = '<option value="">Select Class</option>';
            if (unitId) {
                fetch(`/get-classes/${unitId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(cls => {
                            const option = document.createElement('option');
                            option.value = cls.id;
                            option.textContent = cls.name;
                            if (selectedClassId && cls.id == selectedClassId) {
                                option.selected = true;
                            }
                            classSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching classes:', error));
            }
        }

        unitSelect.addEventListener('change', function() {
            loadClasses(this.value);
        });

        // Initial load
        if (unitSelect.value) {
            // We re-fetch to ensure the list is filtered correctly, 
            // but we need to make sure we don't clear the already selected valid option immediately before the fetch returns? 
            // Actually, for edit, we might want to just keep the original list rendered by server OR fetch and re-select.
            // Fetching and re-selecting ensures integrity if units changed.
            loadClasses(unitSelect.value, currentClassId);
        }
    });
</script>
@endsection
