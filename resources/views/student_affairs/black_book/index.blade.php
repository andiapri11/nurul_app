@extends('layouts.app')

@section('title', 'Buku Hitam Siswa')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-danger">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0 h5">
                <i class="bi bi-journal-x me-2 text-danger"></i> 
                Buku Hitam Siswa
                @if(isset($selectedUnit))
                    <span class="fs-6 opacity-75 ms-2">(Unit: {{ $selectedUnit->name }} - Poin > {{ $selectedUnit->black_book_points }})</span>
                @else
                    <span class="fs-6 opacity-75 ms-2">(Tampil Berdasarkan Batas Poin Masing-masing Unit)</span>
                @endif
            </h3>
        </div>
        <div class="card-body">
            
            <!-- Settings Section -->
            <div class="mb-4">
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#settingsCollapse" aria-expanded="false">
                    <i class="bi bi-gear-fill me-1"></i> Atur Batas Poin Buku Hitam
                </button>
                <div class="collapse mt-3" id="settingsCollapse">
                    <div class="card card-body bg-light border-0">
                        <h6 class="fw-bold mb-3">Pengaturan Batas Poin Per Unit</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless md-0">
                                <thead class="text-muted">
                                    <tr>
                                        <th>Unit Sekolah</th>
                                        <th style="width: 200px;">Batas Poin (Minimum)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allowedUnits as $unit)
                                    <tr>
                                        <form action="{{ route('student-affairs.black-book.update-threshold') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                                            <td class="align-middle fw-semibold">{{ $unit->name }}</td>
                                            <td>
                                                <input type="number" name="points" class="form-control form-control-sm" value="{{ $unit->black_book_points }}" min="1" required>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-save"></i> Simpan
                                                </button>
                                            </td>
                                        </form>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted mt-2">
                            * Siswa dengan total poin <strong>di atas</strong> batas ini akan muncul di daftar Buku Hitam unit tersebut.
                        </small>
                    </div>
                </div>
            </div>

            <form action="{{ route('student-affairs.black-book') }}" method="GET" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-3">
                        <select name="unit_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Semua Unit --</option>
                            @foreach($allowedUnits as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Tahun Pelajaran --</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                    {{ $year->start_year }}/{{ $year->end_year }} ({{ ucfirst($year->semester) }}) ({{ $year->status == 'active' ? 'Aktif' : 'Tidak Aktif' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="class_id" class="form-select" onchange="this.form.submit()" {{ $classes->isEmpty() ? 'disabled' : '' }}>
                            <option value="">-- Semua Kelas --</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
                                    {{ $cls->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari Nama/NIS..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Cari</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th width="50">No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Total Poin</th>
                            <th>Batas Unit</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td class="text-center">{{ $loop->iteration + $students->firstItem() - 1 }}</td>
                            <td class="text-center">{{ $student->nis }}</td>
                            <td>{{ $student->nama_lengkap }}</td>
                            <td class="text-center">{{ $student->schoolClass->first()->name ?? '-' }} ({{ $student->schoolClass->first()->unit->name ?? '-' }})</td>
                            <td class="text-center">
                                <span class="badge bg-danger fs-6">{{ $student->violations_sum_points }}</span>
                            </td>
                            <td class="text-center text-muted small">
                                > {{ optional(optional($student->schoolClass->first())->unit)->black_book_points ?? 10 }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('student-affairs.violations.index', ['search' => $student->nis]) }}" class="btn btn-sm btn-outline-dark" title="Lihat Riwayat">
                                    <i class="bi bi-clock-history"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-emoji-smile fs-1 d-block mb-2"></i>
                                @if(isset($selectedUnit))
                                    Tidak ada siswa {{ $selectedUnit->name }} yang masuk daftar hitam (Poin > {{ $selectedUnit->black_book_points }}).
                                @else
                                    Tidak ada siswa yang masuk daftar hitam.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $students->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
