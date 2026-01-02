@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Data Pimpinan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('leadership-users.update', $leadership_user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $leadership_user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $leadership_user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username (Opsional)</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $leadership_user->username) }}">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password (Biarkan kosong jika tidak ingin mengubah)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="mb-3">
                            <label for="security_pin" class="form-label">Security PIN (6 Digit)</label>
                            <input type="text" maxlength="6" class="form-control @error('security_pin') is-invalid @enderror" id="security_pin" name="security_pin" value="{{ old('security_pin', $leadership_user->security_pin) }}" placeholder="Contoh: 123456">
                            @error('security_pin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Gunakan 6 digit angka untuk PIN keamanan transaksi/konfirmasi.</small>
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Foto (Opsional)</label>
                            @if($leadership_user->photo)
                                <div class="mb-2">
                                    <img src="{{ asset('photos/' . $leadership_user->photo) }}" alt="Foto" width="100" class="img-thumbnail">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('leadership-users.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
