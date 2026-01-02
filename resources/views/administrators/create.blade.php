@extends('layouts.app')

@section('title', 'Tambah Administrator')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add New Administrator</h3>
                </div>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('administrators.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="photo">Photo Profile</label>
                            <input type="file" name="photo" class="form-control" id="photo">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="first_name">First Name</label>
                                    <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter first name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter last name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" id="username" placeholder="Enter username">
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email address</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Retype password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="security_pin">Security PIN (6 Digits)</label>
                            <input type="password" name="security_pin" class="form-control" id="security_pin" maxlength="6" pattern="\d{6}" placeholder="Enter 6-digit PIN" required>
                            <small class="text-muted">Digunakan untuk akses otentikasi tingkat tinggi (seperti hapus permanen).</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('administrators.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
