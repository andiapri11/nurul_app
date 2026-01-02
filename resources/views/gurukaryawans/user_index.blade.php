@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h6 class="m-0 font-weight-bold text-primary">Data User Guru & Karyawan</h6>
                </div>
                <div class="col-md-8">
                    <form action="{{ route('gurukaryawans.user-index') }}" method="GET" class="row g-2 justify-content-end">
                        <div class="col-auto">
                            <select name="academic_year_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua Tahun Ajaran</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ request('academic_year_id') == $ay->id ? 'selected' : '' }}>
                                        {{ $ay->name }} ({{ ucfirst($ay->status) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <select name="unit_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <div class="input-group input-group-sm">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama/nip/user..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                                @if(request()->anyFilled(['search', 'unit_id', 'academic_year_id']))
                                    <a href="{{ route('gurukaryawans.user-index') }}" class="btn btn-outline-secondary" title="Clear Filters">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Guru</th>
                            <th>NIP</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Home Base</th>
                            <th width="10%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gurukaryawans as $index => $user)
                        <tr>
                            <td>{{ $gurukaryawans->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($user->photo)
                                        <img src="{{ asset('photos/' . $user->photo) }}" class="rounded-circle me-2" width="30" height="30" style="object-fit:cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="fw-bold">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $user->nip ?? '-' }}</td>
                            <td>{{ $user->username ?? '-' }}</td>
                            <td>
                                {{-- Menampilkan plain_password jika ada (untuk admin view saja), jika tidak tampilkan encrypted status --}}
                                @if(!empty($user->plain_password))
                                    <span class="text-danger font-monospace">{{ $user->plain_password }}</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-lock-fill"></i> Encrypted</span>
                                @endif
                            </td>
                            <td>
                                @if($user->unit)
                                    <span class="badge bg-info text-dark">{{ $user->unit->name }}</span>
                                @else
                                    <span class="text-muted fst-italic">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $user->status == 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Data tidak ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $gurukaryawans->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
