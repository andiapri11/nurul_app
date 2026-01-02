@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center container-fluid gap-3">
                    <h3 class="card-title mb-0">Jadwal Supervisi Akademik</h3>
                    
                    <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
                        @if(!request()->routeIs('teacher-docs.*'))
                            <form action="{{ route('principal.supervisions.index') }}" method="GET" class="d-flex gap-2">
                                <select name="academic_year_id" class="form-select form-select-sm" style="min-width: 150px;" onchange="this.form.submit()">
                                    <option value="">-- Pilih Tahun --</option>
                                    @if($academicYears->isNotEmpty())
                                        @foreach($academicYears as $ay)
                                            <option value="{{ $ay->id }}" {{ $filterYearId == $ay->id ? 'selected' : '' }}>
                                                {{ $ay->name }} {{ ucfirst($ay->semester) }} ({{ $ay->status == 'active' ? 'Aktif' : 'Tidak Aktif' }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                
                                @if($managedUnits->isNotEmpty())
                                    <select name="unit_id" class="form-select form-select-sm" style="min-width: 150px;" onchange="this.form.submit()">
                                        @if($managedUnits->count() > 1 || Auth::user()->role === 'administrator')
                                            <option value="">-- Semua Unit --</option>
                                        @endif
                                        @foreach($managedUnits as $unit)
                                            <option value="{{ $unit->id }}" {{ $filterUnitId == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </form>
                        @endif

                        @if((Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur' || Auth::user()->isKepalaSekolah()) && $isActiveYearSelected && !request()->routeIs('teacher-docs.*'))
                        <a href="{{ route('principal.supervisions.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg"></i> Buat Jadwal
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Guru</th>
                                    <th>Unit</th>
                                    <th>Status</th>
                                    <th>Dokumen</th>
                                    <th>Supervisor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supervisions as $supervision)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ strip_tags($supervision->date->translatedFormat('l, d F Y')) }}
                                        <br>
                                        <small>{{ $supervision->time ? $supervision->time->format('H:i') : '-' }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $supervision->teacher?->name ?? 'Deleted User' }}</strong>
                                        <div class="small text-muted mt-1">
                                            @if($supervision->subject)
                                                <i class="bi bi-book"></i> {{ $supervision->subject->name }} <br>
                                            @endif
                                            @if($supervision->schoolClass)
                                                <i class="bi bi-people"></i> {{ $supervision->schoolClass->name }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $supervision->unit?->name ?? 'Deleted Unit' }}</td>
                                    <td>
                                        <span class="badge {{ $supervision->status == 'completed' ? 'bg-success' : ($supervision->status == 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                                            {{ ucfirst($supervision->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <!-- Teacher Document -->
                                        @if($supervision->teacher_document_path)
                                            <div class="mb-1">
                                                <a href="{{ Storage::url($supervision->teacher_document_path) }}" target="_blank" class="btn btn-outline-primary btn-xs" title="Dokumen Ajar Guru">
                                                    <i class="bi bi-file-earmark-person"></i> RPP/Modul
                                                </a>
                                                
                                                @php
                                                    $docStatusClass = match($supervision->document_status) {
                                                        'approved' => 'bg-success',
                                                        'rejected' => 'bg-danger',
                                                        default => 'bg-warning text-dark'
                                                    };
                                                @endphp
                                                <span class="badge {{ $docStatusClass }} align-middle">{{ ucfirst($supervision->document_status) }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted small d-block">Guru belum upload</span>
                                        @endif

                                        <!-- Supervisor Document -->
                                        @if($supervision->supervisor_document_path)
                                             <div class="mt-1">
                                                <a href="{{ Storage::url($supervision->supervisor_document_path) }}" target="_blank" class="btn btn-outline-success btn-xs" title="Hasil Supervisi">
                                                    <i class="bi bi-file-earmark-check"></i> Hasil
                                                </a>
                                             </div>
                                        @endif
                                    </td>
                                    <td>{{ $supervision->supervisor?->name ?? 'Deleted User' }}</td>
                                    <td>
                                        @php
                                            $editRoute = request()->routeIs('teacher-docs.*') 
                                                ? route('teacher-docs.supervisions.edit', $supervision->id) 
                                                : route('principal.supervisions.edit', $supervision->id);
                                        @endphp

                                        @if(Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur' || Auth::user()->isKepalaSekolah() || Auth::user()->role === 'guru')
                                        <a href="{{ $editRoute }}" class="btn btn-warning btn-sm" title="Edit / Upload Hasil">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @endif
                                        
                                        @if(!request()->routeIs('teacher-docs.*') && (Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur' || Auth::user()->isKepalaSekolah()))
                                        <form action="{{ route('principal.supervisions.destroy', $supervision->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada jadwal supervisi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $supervisions->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
