@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')
<div class="container-fluid text-center mt-5">
    <div class="card col-md-6 mx-auto shadow-sm">
        <div class="card-body py-5">
            <i class="bi bi-exclamation-triangle text-warning display-1"></i>
            <h3 class="mt-3">Akses Dibatasi</h3>
            <p class="text-muted lead">
                Anda tidak terdaftar sebagai Wali Kelas untuk kelas manapun saat ini.
                <br>
                Silahkan hubungi Administrator Sekolah.
            </p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">Kembali ke Dashboard</a>
        </div>
    </div>
</div>
@endsection
