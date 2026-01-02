@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')
<div class="container-fluid pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <i class="bi bi-exclamation-circle display-1 text-warning mb-4"></i>
            <h2 class="fw-bold text-dark">Tidak Ada Kelas Aktif</h2>
            <p class="lead text-muted">{{ $message ?? 'Anda tidak terdaftar sebagai Wali Kelas untuk kelas aktif saat ini.' }}</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">Kembali ke Dashboard</a>
        </div>
    </div>
</div>
@endsection
