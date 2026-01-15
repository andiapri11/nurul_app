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

    @php
        $globalCategories = $categories->where('unit_id', null);
    @endphp

    {{-- Global Categories Section --}}
    @if($globalCategories->count() > 0 || (Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur'))
    <div class="card shadow-sm border-0 rounded-4 mb-5 border-start border-primary border-5">
        <div class="card-header bg-white py-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-globe me-2"></i>KATEGORI GLOBAL (SEMUA UNIT)</h5>
                    <p class="text-muted small mb-0">Berlaku untuk seluruh unit sekolah.</p>
                </div>
                @if(empty($academic_year_id) || ($activeAcademicYear && $academic_year_id == $activeAcademicYear->id))
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addCategoryModal" onclick="document.getElementById('category_unit_id').value=''">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Global
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th width="50" class="text-center">No</th>
                            <th>Nama Kategori</th>
                            <th class="text-center">Tipe Barang</th>
                            <th class="text-center">Total Item</th>
                            <th width="80" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($globalCategories as $cat)
                        <tr>
                            <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold">{{ $cat->name }}</div>
                            </td>
                            <td class="text-center">
                                @if($cat->is_consumable)
                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3"><i class="bi bi-basket me-1"></i> Habis Pakai</span>
                                @else
                                    <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis border border-primary-subtle px-3"><i class="bi bi-box-seam me-1"></i> Inventaris Tetap</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-dark">{{ $cat->inventories_count + $cat->consumables_count }}</span> <span class="text-muted small">Items</span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-icon btn-sm btn-light-danger rounded-circle" onclick="confirmDelete('{{ route('sarpras.categories.destroy', $cat->id) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada kategori global.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Unit-specific Sections --}}
    @foreach($units as $unit)
    @php
        $unitCategories = $categories->where('unit_id', $unit->id);
        $unitRoomTypes = $roomTypes->where('unit_id', $unit->id);
    @endphp
    
    <div class="card shadow-sm border-0 rounded-4 mb-5 border-top border-primary border-4">
        <div class="card-header bg-light py-3 border-0 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-building me-2 text-primary"></i>UNIT: {{ $unit->name }}</h5>
            </div>
            <div class="d-flex gap-2">
                @if(empty($academic_year_id) || ($activeAcademicYear && $academic_year_id == $activeAcademicYear->id))
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addCategoryModal" onclick="document.getElementById('category_unit_id').value='{{ $unit->id }}'">
                        <i class="bi bi-plus-lg me-1"></i> Kategori
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addRoomTypeModal" onclick="document.getElementById('room_type_unit_id').value='{{ $unit->id }}'">
                        <i class="bi bi-plus-lg me-1"></i> Tipe Ruang
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            <div class="row g-0">
                {{-- Categories Column --}}
                <div class="col-lg-7 border-end">
                    <div class="p-3 bg-white border-bottom">
                        <small class="fw-bold text-primary text-uppercase letter-spacing-1"><i class="bi bi-tags me-1"></i> Kategori Barang</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-sm">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th class="ps-3 py-2 small fw-bold">No</th>
                                    <th class="py-2 small fw-bold">Nama Kategori</th>
                                    <th class="py-2 small fw-bold text-center">Tipe</th>
                                    <th class="py-2 small fw-bold text-center">Qty</th>
                                    <th class="py-2 small fw-bold text-center pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unitCategories as $cat)
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $loop->iteration }}</td>
                                    <td><span class="fw-semibold text-dark">{{ $cat->name }}</span></td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill {{ $cat->is_consumable ? 'bg-warning-subtle text-warning-emphasis' : 'bg-primary-subtle text-primary-emphasis' }}" style="font-size: 10px;">
                                            {{ $cat->is_consumable ? 'Habis Pakai' : 'Tetap' }}
                                        </span>
                                    </td>
                                    <td class="text-center small fw-bold">{{ $cat->inventories_count + $cat->consumables_count }}</td>
                                    <td class="text-center pe-3">
                                        <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="confirmDelete('{{ route('sarpras.categories.destroy', $cat->id) }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted small italic">Belum ada kategori untuk unit ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Room Types Column --}}
                <div class="col-lg-5">
                    <div class="p-3 bg-white border-bottom">
                        <small class="fw-bold text-success text-uppercase letter-spacing-1"><i class="bi bi-door-open me-1"></i> Tipe Ruangan (Zonasi)</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-sm">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th class="ps-3 py-2 small fw-bold">No</th>
                                    <th class="py-2 small fw-bold">Nama Tipe / Label</th>
                                    <th class="py-2 small fw-bold text-center">Ruang</th>
                                    <th class="py-2 small fw-bold text-center pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unitRoomTypes as $type)
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="small fw-bold text-dark">{{ $type->name }}</div>
                                        <span class="badge bg-{{ $type->color }} rounded-pill" style="font-size: 8px;">{{ $type->label }}</span>
                                    </td>
                                    <td class="text-center small fw-bold">{{ $type->rooms_count }}</td>
                                    <td class="text-center pe-3 text-nowrap">
                                        <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="confirmDelete('{{ route('sarpras.room-types.destroy', $type->id) }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small italic">Belum ada tipe ruangan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    {{-- Modals sections --}}
    <!-- Add Room Type Modal -->
    <div class="modal fade" id="addRoomTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('sarpras.room-types.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Tipe Ruangan Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Unit Pendidikan</label>
                            <select name="unit_id" id="room_type_unit_id" class="form-select" required>
                                <option value="">Pilih Unit...</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Tipe (Sistem)</label>
                            <input type="text" name="name" class="form-control" placeholder="Contool: Classroom, Lab, Hall" required>
                            <div class="form-text small">Gunakan nama singkat tanpa spasi.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Label (Tampilan)</label>
                            <input type="text" name="label" class="form-control" placeholder="Contoh: Ruang Kelas Utama" required>
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
                        <button type="submit" class="btn btn-success fw-bold px-4">Simpan Tipe</button>
                    </div>
                </div>
            </form>
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
                        <select name="unit_id" id="category_unit_id" class="form-select">
                            @if(Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur')
                                <option value="">Global (Semua Unit)</option>
                            @endif
                            @foreach($units as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
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
