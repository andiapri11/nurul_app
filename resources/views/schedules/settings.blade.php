@extends('layouts.app')

@section('title', 'Setting Jadwal Pelajaran')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="bi bi-gear-fill me-2"></i>Setting Jadwal Pelajaran</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Setting Jadwal</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">Atur Slot Waktu per Unit</h3>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Unit Selector -->
                <form method="GET" action="{{ route('schedules.settings') }}" class="mb-4">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Pilih Unit Sekolah</label>
                            <select name="unit_id" class="form-select" onchange="this.form.submit()">
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ isset($selectedUnitId) && $selectedUnitId == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <hr>

                @if($selectedUnitId)
                    <div class="row">
                        <!-- List Slot Rules -->
                        <div class="col-md-7">
                            <h5 class="fw-bold mb-3">Daftar Slot Waktu</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Slot</th>
                                            <th>Mulai</th>
                                            <th>Selesai</th>
                                            <th>Istirahat?</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($timeSlots as $slot)
                                            <tr class="{{ $slot->is_break ? 'table-warning' : '' }}">
                                                <td>{{ $slot->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</td>
                                                <td>
                                                    @if($slot->is_break)
                                                        <span class="badge bg-warning text-dark">Istirahat</span>
                                                    @else
                                                        <span class="badge bg-secondary">KBM</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editSlotModal{{ $slot->id }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <form action="{{ route('schedules.destroyTimeSlot', $slot->id) }}" method="POST" onsubmit="return confirm('Hapus slot ini?')" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                                    </form>

                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="editSlotModal{{ $slot->id }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <form action="{{ route('schedules.updateTimeSlot', $slot->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Edit Slot: {{ $slot->name }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Nama Slot</label>
                                                                            <input type="text" name="name" class="form-control" value="{{ $slot->name }}" required>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-6 mb-3">
                                                                                <label class="form-label">Jam Mulai</label>
                                                                                <input type="time" name="start_time" class="form-control" value="{{ $slot->start_time }}" required>
                                                                            </div>
                                                                            <div class="col-6 mb-3">
                                                                                <label class="form-label">Jam Selesai</label>
                                                                                <input type="time" name="end_time" class="form-control" value="{{ $slot->end_time }}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mb-3 form-check">
                                                                            <input type="checkbox" class="form-check-input" name="is_break" value="1" {{ $slot->is_break ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Set sebagai Jam Istirahat</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Belum ada slot waktu diatur untuk unit ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Add New Slot Form -->
                        <div class="col-md-5">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-3">Tambah Slot Baru</h5>
                                    <form action="{{ route('schedules.storeTimeSlot') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="unit_id" value="{{ $selectedUnitId }}">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Nama Slot</label>
                                            <input type="text" name="name" class="form-control" placeholder="Contoh: Jam Ke-1, Istirahat" required>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <label class="form-label">Jam Mulai</label>
                                                <input type="time" name="start_time" class="form-control" required>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <label class="form-label">Jam Selesai</label>
                                                <input type="time" name="end_time" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="is_break" name="is_break" value="1">
                                            <label class="form-check-label" for="is_break">Set sebagai Jam Istirahat</label>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-plus-circle me-1"></i> Tambah Slot
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">Pilih Unit terlebih dahulu untuk mengatur jadwal.</div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Force clock icon in time inputs to be black */
    input[type="time"]::-webkit-calendar-picker-indicator {
        filter: brightness(0) !important; 
        cursor: pointer;
    }
</style>
@endpush
