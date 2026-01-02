@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Capaian Ekstrakurikuler - Kelas {{ $myClass->name ?? 'Tidak Ada' }}</h1>
        <a href="{{ route('wali-kelas.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    @if(!$myClass)
        <div class="alert alert-warning">Anda tidak terdaftar sebagai Wali Kelas aktif.</div>
    @else

        <!-- Filters -->
        @if(Auth::user()->role === 'administrator' || count($availableClasses) > 1)
        <div class="card mb-4 shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('wali-kelas.extracurriculars') }}" class="row g-3 align-items-center">
                    @if(Auth::user()->role === 'administrator')
                    <div class="col-auto">
                        <label class="small fw-bold text-muted d-block">Unit</label>
                        <select name="unit_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-auto">
                        <label class="small fw-bold text-muted d-block">Tahun Pelajaran</label>
                         <select name="academic_year_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ (request('academic_year_id') == $year->id || $myClass->academic_year_id == $year->id) ? 'selected' : '' }}>
                                    TP {{ $year->name }} ({{ $year->status }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="small fw-bold text-muted d-block">Pilih Kelas</label>
                        <select name="class_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach($availableClasses as $class)
                                <option value="{{ $class->id }}" {{ $myClass->id == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header bg-primary text-white py-3">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-trophy me-2"></i> Predikat & Capaian Ekstrakurikuler Siswa</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th rowspan="2" class="text-center" width="50">No</th>
                                <th rowspan="2">Nama Siswa</th>
                                <th rowspan="2">Ekstrakurikuler</th>
                                <th colspan="2" class="text-center border-bottom bg-info text-white">Semester Ganjil</th>
                                <th colspan="2" class="text-center border-bottom bg-success text-white">Semester Genap</th>
                            </tr>
                            <tr class="bg-light">
                                <th width="80" class="text-center">Nilai</th>
                                <th>Deskripsi Capaian</th>
                                <th width="80" class="text-center">Nilai</th>
                                <th>Deskripsi Capaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse($students as $student)
                                @if($student->extracurriculars->count() > 0)
                                    @foreach($student->extracurriculars as $index => $extraMember)
                                    <tr>
                                        @if($index === 0)
                                            <td class="text-center" rowspan="{{ $student->extracurriculars->count() }}">{{ $no++ }}</td>
                                            <td rowspan="{{ $student->extracurriculars->count() }}">
                                                <div class="fw-bold">{{ $student->nama_lengkap }}</div>
                                                <div class="small text-muted">NIS: {{ $student->nis }}</div>
                                            </td>
                                        @endif
                                        <td class="fw-bold text-primary">{{ $extraMember->extracurricular->name }}</td>
                                        <!-- GANJIL -->
                                        <td class="text-center">
                                            @if($extraMember->grade_ganjil)
                                                <span class="badge bg-primary fs-6">{{ $extraMember->grade_ganjil }}</span>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="small text-wrap" style="max-width: 250px;">
                                                {{ $extraMember->description_ganjil ?: '-' }}
                                            </div>
                                        </td>
                                        <!-- GENAP -->
                                        <td class="text-center">
                                            @if($extraMember->grade_genap)
                                                <span class="badge bg-success fs-6">{{ $extraMember->grade_genap }}</span>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="small text-wrap" style="max-width: 250px;">
                                                {{ $extraMember->description_genap ?: '-' }}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $student->nama_lengkap }}</div>
                                            <div class="small text-muted">NIS: {{ $student->nis }}</div>
                                        </td>
                                        <td colspan="5" class="text-center text-muted small py-3">
                                            Tidak mengikuti ekstrakurikuler
                                        </td>
                                    </tr>
                                @endif
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-users fa-3x mb-3 d-block"></i>
                                    Tidak ada data siswa dalam kelas ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .bg-orange { background-color: #fd7e14; }
    .table th { font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .table td { font-size: 0.9rem; }
</style>
@endsection
