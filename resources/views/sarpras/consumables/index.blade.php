@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Barang Habis Pakai (Consumables)</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('sarpras.consumables.history') }}" class="btn btn-outline-info">
                <i class="bi bi-clock-history"></i> Histori Mutasi
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addConsumableModal">
                <i class="bi bi-plus-lg"></i> Tambah Barang Baru
            </button>
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

    <!-- Search & Filter -->
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-body">
            <form method="GET" action="{{ route('sarpras.consumables.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Unit Pendidikan</label>
                    <select name="unit_id" class="form-select" onchange="this.form.submit()">
                        @if(Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur')
                            <option value="">Semua Unit</option>
                        @endif
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ $unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Tahun Pelajaran</label>
                    <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $academic_year_id == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Kategori</label>
                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Cari Nama Barang</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Ketik nama...">
                        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('sarpras.consumables.index') }}" class="btn btn-outline-secondary w-100" title="Reset Filter"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Consumables Table -->
    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50" class="text-center">No</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th class="text-center">Stok Saat Ini</th>
                            <th class="text-center">Satuan</th>
                            <th>Status Stok</th>
                            <th width="150" class="text-center">Aksi Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($consumables as $item)
                        <tr>
                            <td class="text-center text-muted">{{ ($consumables->currentPage() - 1) * $consumables->perPage() + $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold text-primary">{{ $item->name }}</div>
                                <div class="small text-muted">Min. Stok: {{ $item->min_stock }} {{ $item->unit_name }}</div>
                            </td>
                            <td>{{ $item->category->name }}</td>
                            <td class="text-center">
                                <span class="h5 mb-0 fw-bold">{{ $item->stock }}</span>
                            </td>
                            <td class="text-center">{{ $item->unit_name }}</td>
                            <td>
                                @if($item->stock <= 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif($item->stock <= $item->min_stock)
                                    <span class="badge bg-warning text-dark">Hampir Habis</span>
                                @else
                                    <span class="badge bg-success">Tersedia</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#transactModal{{ $item->id }}" title="Mutasi Stok">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" title="Edit Barang">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="{{ route('sarpras.consumables.history', ['search' => $item->name]) }}" class="btn btn-sm btn-outline-secondary" title="Lihat Histori">
                                        <i class="bi bi-clock-history"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('sarpras.consumables.destroy', $item->id) }}')" title="Hapus Barang">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Transaction Modal -->
                        <div class="modal fade" id="transactModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('sarpras.consumables.transact', $item->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-content text-start text-dark">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Mutasi Stok: {{ $item->name }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center mb-4 p-3 bg-light rounded border">
                                                <small class="text-muted d-block">Stok Sekarang:</small>
                                                <span class="h2 fw-bold">{{ $item->stock }}</span> <small>{{ $item->unit_name }}</small>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Jenis Mutasi</label>
                                                <div class="d-flex gap-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="type" id="typeIn{{ $item->id }}" value="in" checked>
                                                        <label class="form-check-label text-success fw-bold" for="typeIn{{ $item->id }}">Barang Masuk (+)</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="type" id="typeOut{{ $item->id }}" value="out">
                                                        <label class="form-check-label text-danger fw-bold" for="typeOut{{ $item->id }}">Barang Keluar (-)</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Jumlah ({{ $item->unit_name }})</label>
                                                <input type="number" name="quantity" class="form-control form-control-lg" min="1" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Keterangan / Keperluan</label>
                                                <textarea name="note" class="form-control" rows="2" placeholder="Contoh: Pembelian bulanan, Diambil untuk kantor..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Mutasi</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('sarpras.consumables.update', $item->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-content text-start text-dark">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title">Edit Barang: {{ $item->name }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Unit Pendidikan</label>
                                                <select name="unit_id" class="form-select" required>
                                                    @foreach($units as $unit)
                                                        <option value="{{ $unit->id }}" {{ $item->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Tahun Pelajaran</label>
                                                <select name="academic_year_id" class="form-select" required>
                                                    @foreach($academicYears as $year)
                                                        <option value="{{ $year->id }}" {{ $item->academic_year_id == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Kategori</label>
                                                <select name="inventory_category_id" class="form-select" required>
                                                    @foreach($categories as $cat)
                                                        <option value="{{ $cat->id }}" {{ $item->inventory_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Nama Barang</label>
                                                <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Satuan</label>
                                                    <input type="text" name="unit_name" class="form-control" value="{{ $item->unit_name }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Minimal Stok</label>
                                                    <input type="number" name="min_stock" class="form-control" value="{{ $item->min_stock }}" min="0">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-info text-white">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada data barang habis pakai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-light border-top">
                {{ $consumables->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Consumable Modal -->
<div class="modal fade" id="addConsumableModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('sarpras.consumables.store') }}" method="POST">
            @csrf
            <div class="modal-content text-start text-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang Habis Pakai Baru</h5>
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
                                <option value="{{ $unit->id }}" {{ $unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="inventory_category_id" class="form-select" required>
                            <option value="">Pilih Kategori...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Barang</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Kertas A4 80gr, Tinta Printer HP 680" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Satuan</label>
                            <input type="text" name="unit_name" class="form-control" placeholder="Rim, Box, Pcs, Liter..." required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Stok Awal</label>
                            <input type="number" name="stock" class="form-control" value="0" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Minimal Stok (Peringatan)</label>
                        <input type="number" name="min_stock" class="form-control" value="5" min="0">
                        <small class="text-muted">Sistem akan memberi peringatan jika stok di bawah angka ini.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Barang</button>
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
            text: "Data barang dan riwayat mutasi akan dihapus secara permanen!",
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
