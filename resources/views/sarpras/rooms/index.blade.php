@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Manajemen Ruangan</h1>
        @php
            $isArchived = request('academic_year_id') && request('academic_year_id') != $activeYear->id;
        @endphp
        
        @if(!$isArchived)
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
            <i class="bi bi-plus-lg"></i> Tambah Ruangan
        </button>
        @else
        <span class="badge bg-secondary p-2"><i class="bi bi-archive-fill"></i> Tampilan Arsip (Read-Only)</span>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filters ... (keeping filters) ... -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('sarpras.rooms.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Unit Sekolah</label>
                    <select name="unit_id" class="form-select" onchange="this.form.submit()">
                        @if(Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur')
                            <option value="">Semua Unit</option>
                        @endif
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ (isset($unitId) && $unitId == $unit->id) ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Tahun Pelajaran</label>
                    <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('sarpras.rooms.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Ruangan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50" class="text-center">No</th>
                            <th>Nama Ruangan</th>
                            <th>Unit</th>
                            <th>Tahun Pelajaran</th>
                            <th>Tipe</th>
                            <th class="text-center">Total Barang</th>
                            <th>Keterangan</th>
                            @if(!$isArchived)
                            <th width="120" class="text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                        <tr>
                            <td class="text-center">{{ ($rooms->currentPage() - 1) * $rooms->perPage() + $loop->iteration }}</td>
                             <td>
                                 <div class="fw-bold text-primary">{{ $room->name }}</div>
                                 @if($room->person_in_charge)
                                     <div class="small text-muted"><i class="bi bi-person-badge"></i> PJ: {{ $room->person_in_charge }}</div>
                                 @endif
                             </td>
                            <td>{{ $room->unit->name }}</td>
                            <td>
                                <span class="badge {{ $room->academicYear->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $room->academicYear->name ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $typeObj = $roomTypes[$room->type] ?? null;
                                @endphp
                                <span class="badge bg-{{ $typeObj ? $typeObj->color : 'secondary' }}">
                                    {{ $typeObj ? $typeObj->label : $room->type }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-dark">{{ $room->inventories->count() }}</span>
                            </td>
                            <td>{{ $room->description ?: '-' }}</td>
                            @if(!$isArchived)
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editRoomModal{{ $room->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('sarpras.rooms.destroy', $room->id) }}')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>

                        @if(!$isArchived)
                        <!-- Edit Room Modal -->
                        <div class="modal fade" id="editRoomModal{{ $room->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('sarpras.rooms.update', $room->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Ruangan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Unit Sekolah</label>
                                                <select name="unit_id" class="form-select" required>
                                                    @foreach($units as $unit)
                                                        <option value="{{ $unit->id }}" {{ $room->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama Ruangan</label>
                                                <input type="text" name="name" class="form-control" value="{{ $room->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tipe Ruangan</label>
                                                <select name="type" class="form-select" required>
                                                    @foreach($roomTypes as $type)
                                                        <option value="{{ $type->name }}" {{ $room->type == $type->name ? 'selected' : '' }}>{{ $type->label }}</option>
                                                    @endforeach
                                                </select>
                                             </div>
                                             <div class="mb-3">
                                                 <label class="form-label">Penanggung Jawab</label>
                                                 <input type="text" name="person_in_charge" class="form-control" value="{{ $room->person_in_charge }}" placeholder="Nama PJ Ruangan...">
                                             </div>
                                            <div class="mb-3">
                                                <label class="form-label">Keterangan (Opsional)</label>
                                                <textarea name="description" class="form-control" rows="3">{{ $room->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada data ruangan di periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $rooms->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Scripts for Delete -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(url) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ruangan akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = url;
                form.method = 'POST';
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        })
    }


</script>
@endpush


<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('sarpras.rooms.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoomModalLabel">Tambah Ruangan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Unit Sekolah</label>
                            <select name="unit_id" id="modal_room_unit_select" class="form-select" required>
                                <option value="">Pilih Unit...</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Ruangan</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Kelas 7A, Lab Komputer" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Ruangan</label>
                        <select name="type" id="modal_room_type_select" class="form-select" required>
                            <option value="">Pilih Tipe...</option>
                            @foreach($allRoomTypes as $type)
                                <option value="{{ $type->name }}" data-unit-id="{{ $type->unit_id ?? 'general' }}" data-unit-name="{{ $type->unit->name ?? 'UMUM' }}">
                                    {{ $type->label }} {{ $type->unit_id ? '('.$type->unit->name.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text small" id="type_help_text">Pilih unit terlebih dahulu untuk melihat tipe yang tersedia.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penanggung Jawab</label>
                        <input type="text" name="person_in_charge" class="form-control" placeholder="Nama PJ Ruangan (Guru/Staff)...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan (Opsional)</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Ruangan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const unitSelect = document.getElementById('modal_room_unit_select');
    const typeSelect = document.getElementById('modal_room_type_select');
    const typeHelp = document.getElementById('type_help_text');

    if (unitSelect && typeSelect) {
        function filterTypes() {
            const selectedUnitId = unitSelect.value;
            let hasVisible = false;
            let firstVisible = null;

            Array.from(typeSelect.options).forEach(option => {
                if (option.value === "") return; // Skip default placeholder

                const typeUnitId = option.getAttribute('data-unit-id');
                
                // Show if type belongs to selected unit OR is 'general' (null in DB)
                const isVisible = (typeUnitId === 'general') || (typeUnitId === selectedUnitId);
                
                option.style.display = isVisible ? 'block' : 'none';
                
                if (isVisible) {
                    hasVisible = true;
                    if (!firstVisible) firstVisible = option;
                }
            });

            // Feedback
            if(selectedUnitId) {
                typeSelect.disabled = false;
                typeHelp.style.display = 'none';
                typeSelect.value = ""; // Reset value to force user selection or pick first reasonable default? Better force select.
            } else {
                typeSelect.disabled = true;
                typeHelp.style.display = 'block';
                typeHelp.textContent = 'Pilih unit terlebih dahulu.';
            }
        }

        unitSelect.addEventListener('change', filterTypes);
        // Initial call if something is pre-selected (e.g. if simple request)
        if(unitSelect.value) filterTypes();
        else typeSelect.disabled = true;
    }
});
</script>
@endsection
