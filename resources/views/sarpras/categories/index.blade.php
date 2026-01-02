@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Kategori Inventaris</h1>
    </div>
    <div class="card mb-4 border-left-primary">
        <div class="card-body">
            <form action="" method="GET" class="row align-items-end g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Unit Pendidikan</label>
                    <select name="unit_id" class="form-select" onchange="this.form.submit()">
                        @if(Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur')
                            <option value="">Semua Unit</option>
                        @endif
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ (isset($unit_id) && $unit_id == $unit->id) ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tahun Pelajaran</label>
                    <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ isset($academic_year_id) && $academic_year_id == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '(Tidak Aktif)' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-secondary w-100" onclick="window.location.href='{{ route('sarpras.categories.index') }}'">
                        Reset Filter
                    </button>
                </div>
            </form>
        </div>
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
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Kategori</h6>
                    @if(empty($academic_year_id) || ($activeAcademicYear && $academic_year_id == $activeAcademicYear->id))
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="bi bi-plus-lg"></i> Tambah Kategori
                        </button>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>Nama Kategori</th>
                                    @if(empty($unit_id))
                                        <th>Unit</th>
                                    @endif
                                    <th class="text-center">Tipe</th>
                                    <th class="text-center">Total Barang</th>
                                    <th width="100" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $cat)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="fw-bold">{{ $cat->name }}</td>
                                    @if(empty($unit_id))
                                        <td>
                                            <span class="badge bg-secondary">{{ $cat->unit->name ?? 'Semua' }}</span>
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        @if($cat->is_consumable)
                                            <span class="badge bg-warning text-dark"><i class="bi bi-basket"></i> Habis Pakai</span>
                                        @else
                                            <span class="badge bg-primary"><i class="bi bi-box-seam"></i> Inventaris (Tetap)</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $cat->inventories_count + $cat->consumables_count }} Items</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('sarpras.categories.destroy', $cat->id) }}')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">Belum ada kategori.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Tipe Ruangan</h6>
                    @if(empty($academic_year_id) || ($activeAcademicYear && $academic_year_id == $activeAcademicYear->id))
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomTypeModal">
                            <i class="bi bi-plus-lg"></i> Tambah Tipe
                        </button>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>Nama Tipe (Sistem)</th>
                                    @if(empty($unit_id))
                                        <th>Unit</th>
                                    @endif
                                    <th>Label (Tampilan)</th>
                                    <th class="text-center">Total Ruangan</th>
                                    <th width="100" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roomTypes as $type)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="fw-bold">{{ $type->name }}</td>
                                    @if(empty($unit_id))
                                        <td>
                                            <span class="badge bg-secondary">{{ $type->unit->name ?? 'Semua' }}</span>
                                        </td>
                                    @endif
                                    <td>
                                        <span class="badge bg-{{ $type->color }}">{{ $type->label }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $type->rooms_count }} Ruangan</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('sarpras.room-types.destroy', $type->id) }}')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">Belum ada tipe ruangan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Room Type Modal -->
                    <div class="modal fade" id="addRoomTypeModal" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('sarpras.room-types.store') }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah Tipe Ruangan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Unit Pendidikan</label>
                                            <select name="unit_id" class="form-select" required>
                                                @if(empty($unit_id))
                                                    <option value="">Pilih Unit...</option>
                                                @endif
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit->id }}" {{ isset($unit_id) && $unit_id == $unit->id ? 'selected' : '' }}>
                                                        {{ $unit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Nama Tipe (Sistem)</label>
                                            <input type="text" name="name" class="form-control" placeholder="Contoh: Classroom, Lab, Hall (Tanpa Spasi)" required>
                                            <small class="text-muted">Gunakan Bahasa Inggris atau kode unik tanpa spasi.</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Label (Tampilan)</label>
                                            <input type="text" name="label" class="form-control" placeholder="Contoh: Ruang Kelas, Laboratorium" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Warna Badge</label>
                                            <select name="color" class="form-select">
                                                <option value="primary">Primary (Biru)</option>
                                                <option value="secondary">Secondary (Abu-abu)</option>
                                                <option value="success">Success (Hijau)</option>
                                                <option value="danger">Danger (Merah)</option>
                                                <option value="warning">Warning (Kuning)</option>
                                                <option value="info">Info (Biru Muda)</option>
                                                <option value="dark">Dark (Hitam)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">Simpan Tipe</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('sarpras.categories.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Unit Pendidikan</label>
                        <select name="unit_id" class="form-select">
                            @if(Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur')
                                <option value="">Global (Semua Unit)</option>
                            @endif
                            @if(empty($unit_id) && Auth::user()->role !== 'administrator' && Auth::user()->role !== 'direktur')
                                <option value="">Pilih Unit...</option>
                            @endif
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ isset($unit_id) && $unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Elektronik, ATK, Mebel..." required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch p-0 ps-5 mt-2">
                             <input class="form-check-input ms-n5" type="checkbox" name="is_consumable" value="1" id="isConsumableCheck">
                             <label class="form-check-label fw-bold" for="isConsumableCheck">
                                 <i class="bi bi-basket me-1"></i> Barang Habis Pakai (Consumables)
                             </label>
                        </div>
                        <small class="text-muted d-block mt-1">Gunakan untuk kategori seperti ATK, Sabun, Kertas, dsb yang datanya tidak perlu dilacak per-item fisik (barcode).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(url) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan dihapus permanen jika tidak ada data terkait!",
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
@endsection
