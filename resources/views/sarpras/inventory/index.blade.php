@extends('layouts.app')

@section('content')
<style>
    .modal-vw-95 {
        max-width: 95vw !important;
        margin-left: auto;
        margin-right: auto;
    }
    #inventoryTable {
        min-width: 1300px;
    }
</style>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Daftar Inventaris</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('sarpras.inventory.disposed') }}" class="btn btn-outline-danger">
                <i class="bi bi-trash-fill"></i> Arsip Penghapusan
            </a>
            <a href="{{ route('sarpras.inventory.print', request()->query()) }}" target="_blank" class="btn btn-outline-secondary">
                <i class="bi bi-printer"></i> Cetak Data
            </a>
            <button type="button" class="btn btn-outline-dark" id="btnPrintBarcodes">
                <i class="bi bi-qr-code"></i> Cetak Barcode Terpilih
            </button>
            @if(empty(request('academic_year_id')) || ($activeYear && request('academic_year_id') == $activeYear->id))
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInventoryModal">
                <i class="bi bi-plus-lg"></i> Tambah Barang
            </button>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('sarpras.inventory.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Unit Pendidikan</label>
                    <select name="unit_id" class="form-select" onchange="this.form.submit()">
                        @if($units->count() > 1 && (Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur'))
                            <option value="">Semua Unit</option>
                        @endif
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Tahun Pelajaran</label>
                    <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($academicYears as $year)
                           <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Kategori</label>
                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Ruangan</label>
                    <select name="room_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Ruangan</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }} ({{ $room->unit->name }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Kondisi</label>
                    <select name="condition" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kondisi</option>
                        <option value="Good" {{ request('condition') == 'Good' ? 'selected' : '' }}>Baik</option>
                        <option value="Repairing" {{ request('condition') == 'Repairing' ? 'selected' : '' }}>Perbaikan</option>
                        <option value="Damaged" {{ request('condition') == 'Damaged' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Broken" {{ request('condition') == 'Broken' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <label class="form-label small fw-bold">Cari Barang / Kode</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari nama barang atau kode inventaris...">
                        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('sarpras.inventory.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terjadi Kesalahan:</div>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th width="30" class="text-center">
                                <input type="checkbox" id="checkAll" class="form-check-input">
                            </th>
                            <th width="50" class="text-center">No</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Kondisi</th>
                            <th class="text-end">Harga / Sumber</th>
                            <th>Kode Item</th>
                            <th width="100" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventories as $item)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input item-checkbox" value="{{ $item->id }}">
                            </td>
                            <td class="text-center text-muted small">{{ ($inventories->currentPage() - 1) * $inventories->perPage() + $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($item->photo)
                                        <div class="flex-shrink-0">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#viewPhotoModal{{ $item->id }}">
                                                <img src="{{ asset('storage/' . $item->photo) }}" 
                                                     class="rounded shadow-sm border" 
                                                     style="width: 45px; height: 45px; object-fit: cover;"
                                                     alt="{{ $item->name }}">
                                            </a>
                                        </div>
                                    @else
                                        <div class="flex-shrink-0">
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center border" 
                                                 style="width: 45px; height: 45px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $item->name }}</div>
                                        <div class="small text-muted">Beli: {{ $item->purchase_date ? $item->purchase_date->format('d/m/Y') : '-' }}</div>
                                    </div>
                                </div>

                                <!-- Photo Preview Modal -->
                                @if($item->photo)
                                <div class="modal fade" id="viewPhotoModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content bg-transparent border-0">
                                            <div class="modal-body p-0 text-center">
                                                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                                                <img src="{{ asset('storage/' . $item->photo) }}" class="img-fluid rounded shadow-lg">
                                                <div class="mt-2 text-white fw-bold">{{ $item->name }} ({{ $item->code }})</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                            <td>{{ $item->category->name }}</td>
                             <td>
                                 <div class="fw-bold">{{ $item->room->name ?? '-' }}</div>
                                 <div class="small text-muted text-uppercase">{{ $item->room->unit->name ?? 'N/A' }}</div>
                                 @if($item->person_in_charge)
                                     <div class="mt-1 small"><i class="bi bi-person-badge"></i> PJ: <span class="fw-bold text-dark">{{ $item->person_in_charge }}</span></div>
                                 @endif
                             </td>
                            <td>
                                @php
                                    $conditions = [
                                        'Good' => ['label' => 'Baik', 'color' => 'success'],
                                        'Repairing' => ['label' => 'Perbaikan', 'color' => 'info'],
                                        'Damaged' => ['label' => 'Rusak Ringan', 'color' => 'warning'],
                                        'Broken' => ['label' => 'Rusak Berat', 'color' => 'danger'],
                                    ];
                                    $cond = $conditions[$item->condition] ?? ['label' => $item->condition, 'color' => 'secondary'];
                                @endphp
                                <span class="badge bg-{{ $cond['color'] }}">{{ $cond['label'] }}</span>
                            </td>
                            <td class="text-end">
                                <div class="fw-bold text-dark">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                @if($item->is_grant)
                                    <span class="badge bg-warning text-dark small"><i class="bi bi-gift"></i> Bantuan</span>
                                @endif
                                @if($item->source)
                                    <div class="small text-muted">Ket: {{ $item->source }}</div>
                                @endif
                            </td>
                            <td><code class="text-primary fw-bold">{{ $item->code }}</code></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="showHistory('{{ $item->id }}')" title="Histori">
                                        <i class="bi bi-clock-history"></i>
                                    </button>
                                    @if(Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur' || (empty(request('academic_year_id')) || ($activeYear && request('academic_year_id') == $activeYear->id)))
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editInventory{{ $item->id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reportItem{{ $item->id }}" title="Laporkan Kerusakan / Ajukan Penghapusan">
                                        <i class="bi bi-megaphone-fill"></i>
                                    </button>
                                    @if(Auth::user()->role === 'administrator')
                                    <button type="button" class="btn btn-sm btn-outline-dark" onclick="confirmDelete('{{ route('sarpras.inventory.destroy', $item->id) }}')" title="Hapus Manual (Admin Only)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Report/Disposal Proposal Modal -->
                        <div class="modal fade" id="reportItem{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form onsubmit="submitDamageReport(event, '{{ $item->code }}')">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title font-weight-bold"><i class="bi bi-megaphone me-2"></i>Buat Laporan / Usulan</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start mt-2">
                                            <div class="p-2 mb-3 bg-light border rounded">
                                                <strong>{{ $item->name }}</strong><br>
                                                <code class="small text-primary">{{ $item->code }}</code>
                                            </div>

                                            <input type="hidden" name="code" value="{{ $item->code }}">
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Jenis Kejadian</label>
                                                <select name="type" class="form-select" required>
                                                    <option value="Damaged">Kerusakan</option>
                                                    <option value="Lost">Kehilangan</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Detail Kerusakan / Masalah</label>
                                                <textarea name="description" class="form-control" rows="2" placeholder="Jelaskan kondisi barang saat ini..." required></textarea>
                                            </div>

                                            <hr>
                                            <div class="p-2 mb-3 border-start border-4 border-info bg-light">
                                                <label class="form-label fw-bold text-info"><i class="bi bi-lightbulb me-1"></i>Saran Tindak Lanjut (Sarpras)</label>
                                                <select name="follow_up_action" class="form-select mb-2" required>
                                                    <option value="Repair">Perbaikan</option>
                                                    <option value="Replacement">Pengajuan Pembelian Baru</option>
                                                    <option value="Disposal">Pemusnahan (Hapus dari Daftar)</option>
                                                </select>
                                                <textarea name="follow_up_description" class="form-control" rows="2" placeholder="Alasan saran ini diberikan..." required></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Skala Prioritas</label>
                                                <select name="priority" class="form-select" required>
                                                    <option value="Low">Rendah</option>
                                                    <option value="Medium" selected>Sedang</option>
                                                    <option value="High">Tinggi</option>
                                                    <option value="Urgent">Mendesak</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Foto Bukti (Opsional)</label>
                                                <input type="file" name="photo" class="form-control" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger fw-bold">Kirim Laporan & Usulan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editInventory{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('sarpras.inventory.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Barang: {{ $item->code }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <div class="mb-3">
                                                <label class="form-label font-weight-bold">Nama Barang</label>
                                                <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label font-weight-bold">Foto Barang</label>
                                                @if($item->photo)
                                                    <div class="mb-2">
                                                        <img src="{{ asset('storage/' . $item->photo) }}" class="rounded shadow-sm" style="height: 100px; object-fit: cover;">
                                                        <div class="small text-muted">Foto saat ini</div>
                                                    </div>
                                                @endif
                                                <input type="file" name="photo" class="form-control" accept="image/*">
                                                <small class="text-muted text-xs">Biarkan kosong jika tidak ingin mengubah foto.</small>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Harga Beli (Rp)</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" name="price" class="form-control currency-input" value="{{ number_format($item->price, 0, ',', '.') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Sumber / Keterangan</label>
                                                    <input type="text" name="source" class="form-control form-control-sm" value="{{ $item->source }}" placeholder="Contoh: BOS, Yayasan, Bantuan Pemerintah">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="is_grant" value="1" id="is_grant_edit{{ $item->id }}" {{ $item->is_grant ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold" for="is_grant_edit{{ $item->id }}">
                                                        Barang Bantuan / Hibah
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                 <div class="col-md-6 mb-3">
                                                     <label class="form-label">Tanggal Beli</label>
                                                     <input type="date" name="purchase_date" class="form-control" value="{{ $item->purchase_date ? $item->purchase_date->format('Y-m-d') : '' }}">
                                                 </div>
                                                 <div class="col-md-6 mb-3">
                                                     <label class="form-label font-weight-bold">Penanggung Jawab</label>
                                                     <input type="text" name="person_in_charge" class="form-control form-control-sm" value="{{ $item->person_in_charge }}" placeholder="Nama Guru/Staff...">
                                                 </div>
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

                        @if($item->photo)
                        <!-- Photo Modal -->
                        <div class="modal fade" id="viewPhotoModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-dark text-white border-0 py-2">
                                        <h6 class="modal-title fs-6">{{ $item->name }} ({{ $item->code }})</h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-0 bg-dark text-center">
                                        <img src="{{ asset('storage/' . $item->photo) }}" class="img-fluid" alt="{{ $item->name }}" style="max-height: 85vh;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">Barang tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-light border-top">
                {{ $inventories->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Multiple Inventory Modal -->
<div class="modal fade" id="addInventoryModal" tabindex="-1">
    <div class="modal-dialog modal-vw-95">
        <form action="{{ route('sarpras.inventory.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content text-start text-dark">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-box-seam"></i> Tambah Inventaris (Input Banyak)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="inventoryTable">
                            <thead class="bg-light">
                                <tr>
                                    <th width="150">Kategori</th>
                                    <th width="220">Nama Barang</th>
                                    <th width="200">Lokasi / Ruangan</th>
                                    <th width="130">Kondisi</th>
                                    <th width="140">Harga (Rp)</th>
                                    <th width="200">Sumber / Bantuan</th>
                                    <th width="180">Penanggung Jawab</th>
                                    <th width="140">Tanggal Beli</th>
                                    <th width="140">Foto</th>
                                    <th width="180">Kode Item</th>
                                    <th width="50" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="inventoryBody">
                                <tr class="inventory-row">
                                    <td>
                                        <select name="items[0][inventory_category_id]" class="form-select form-select-sm" required>
                                            <option value="">Pilih...</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="items[0][name]" class="form-control form-control-sm" placeholder="Nama..." required>
                                    </td>
                                    <td>
                                        <select name="items[0][room_id]" class="form-select form-select-sm room-select">
                                            <option value="" data-unit="GUDANG" data-pj="">Pilih Lokasi...</option>
                                            @foreach($activeRooms as $room)
                                                <option value="{{ $room->id }}" data-unit="{{ $room->unit->name }}" data-pj="{{ $room->person_in_charge }}">
                                                    {{ $room->name }} ({{ $room->unit->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="items[0][condition]" class="form-select form-select-sm">
                                            <option value="Good">Baik</option>
                                            <option value="Damaged">Rusak Ringan</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" name="items[0][price]" class="form-control currency-input" placeholder="0">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="items[0][source]" class="form-control form-control-sm mb-1" placeholder="Sumber...">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="items[0][is_grant]" value="1" id="is_grant_0">
                                            <label class="form-check-label small" for="is_grant_0">Bantuan</label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="items[0][person_in_charge]" class="form-control form-control-sm" placeholder="Nama PJ...">
                                    </td>
                                    <td>
                                        <input type="date" name="items[0][purchase_date]" class="form-control form-control-sm date-input" value="{{ date('Y-m-d') }}">
                                    </td>
                                    <td>
                                        <input type="file" name="items[0][photo]" class="form-control form-control-sm" accept="image/*">
                                    </td>
                                    <td>
                                        <input type="text" name="items[0][code]" class="form-control form-control-sm inventory-code" value="GUDANG/IVN-{{ $nextCode }}" placeholder="Kode..." required>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-row" disabled><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-success" id="addRow">
                            <i class="bi bi-plus-circle"></i> Tambah Baris
                        </button>
                        <p class="small text-muted mt-2 mb-0"><em>* Semua field bertanda bintang wajib diisi. Kode barang harus unik.</em></p>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow">Simpan Semua Barang</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Format Rupiah function
    function formatInputRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    // Attach listener for price inputs
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('currency-input')) {
            e.target.value = formatInputRupiah(e.target.value);
        }
    });

    let rowCount = 1;
    let baseNextNum = parseInt("{{ $nextCode }}") || 1;
    
    function updateRowCode(row, index) {
        const roomSelect = row.querySelector('.room-select');
        const codeInput = row.querySelector('.inventory-code');
        
        if (!roomSelect || !codeInput) return;
        
        let unit = roomSelect.options[roomSelect.selectedIndex]?.dataset.unit || 'GUDANG';
        // Remove all spaces for unit
        unit = unit.replace(/\s+/g, '').toUpperCase();
        
        const itemNumber = String(baseNextNum + index).padStart(5, '0');
        const newCode = `${unit}/IVN-${itemNumber}`;
        codeInput.value = newCode;
    }

    // Listener for dynamic updates
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('room-select') || e.target.classList.contains('date-input')) {
            const row = e.target.closest('tr');
            if (row) {
                // Determine index
                let index = 0;
                const rows = Array.from(document.querySelectorAll('.inventory-row'));
                index = rows.indexOf(row);
                updateRowCode(row, index);

                // Auto-fill Penanggung Jawab from Room
                if (e.target.classList.contains('room-select')) {
                    const pj = e.target.options[e.target.selectedIndex]?.dataset.pj || '';
                    const pjInput = row.querySelector('input[name*="[person_in_charge]"]');
                    if (pjInput) pjInput.value = pj;
                }
            }
        }
    });

    const inventoryBody = document.getElementById('inventoryBody');
    const addRowBtn = document.getElementById('addRow');

    if (addRowBtn && inventoryBody) {
        addRowBtn.addEventListener('click', function() {
            const firstRow = document.querySelector('.inventory-row');
            if (!firstRow) return;

            const newRow = firstRow.cloneNode(true);
            
            // Update input names and values
            newRow.querySelectorAll('[name]').forEach(input => {
                const name = input.getAttribute('name');
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${rowCount}]`));
                
                if (input.classList.contains('inventory-code')) {
                    // Will be updated by updateRowCode
                } else if (input.tagName === 'SELECT') {
                    if (input.name.includes('condition')) {
                        input.value = firstRow.querySelector('[name*="condition"]').value || 'Good';
                    } else if (input.name.includes('inventory_category_id')) {
                         input.value = firstRow.querySelector('[name*="inventory_category_id"]').value;
                    } else if (input.name.includes('room_id')) {
                         input.value = firstRow.querySelector('[name*="room_id"]').value;
                    }
                } else if (input.name.includes('[name]')) {
                    input.value = firstRow.querySelector('[name*="[name]"]').value;
                } else if (input.name.includes('[price]')) {
                    input.value = firstRow.querySelector('[name*="[price]"]').value;
                } else if (input.name.includes('[source]')) {
                    input.value = firstRow.querySelector('[name*="[source]"]').value;
                } else if (input.name.includes('[person_in_charge]')) {
                    input.value = firstRow.querySelector('[name*="[person_in_charge]"]').value;
                } else if (input.name.includes('[is_grant]')) {
                    input.checked = firstRow.querySelector('[name*="[is_grant]"]').checked;
                    // Update ID and For to keep checkbox working
                    const newId = `is_grant_${rowCount}`;
                    input.id = newId;
                    const label = input.nextElementSibling;
                    if (label && label.tagName === 'LABEL') label.setAttribute('for', newId);
                } else if (input.name.includes('[purchase_date]')) {
                     input.value = firstRow.querySelector('[name*="[purchase_date]"]').value;
                } else if (input.type === 'file') {
                    input.value = ''; 
                } else {
                    input.value = ''; 
                }
            });

            // Update the code for the new row
            updateRowCode(newRow, rowCount);

            // Enable delete button for new rows
            const removeBtn = newRow.querySelector('.remove-row');
            if (removeBtn) {
                removeBtn.disabled = false;
                removeBtn.addEventListener('click', function() {
                    newRow.remove();
                    // Optional: re-sequence other rows? 
                    // Usually better to leave them as is to avoid confusion
                });
            }

            inventoryBody.appendChild(newRow);
            rowCount++;
        });
    }

    // Handle delete for existing dynamically added rows (just in case)
    document.querySelectorAll('.remove-row').forEach(btn => {
        if (!btn.disabled) {
            btn.addEventListener('click', function() {
                const row = btn.closest('tr');
                if (row) row.remove();
            });
        }
    });

    // Barcode Selection Logic
    const checkAll = document.getElementById('checkAll');
    const btnPrintBarcodes = document.getElementById('btnPrintBarcodes');

    checkAll.addEventListener('change', function() {
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.checked = checkAll.checked;
        });
    });

    btnPrintBarcodes.addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) {
            Swal.fire('Info', 'Pilih minimal satu barang untuk mencetak barcode.', 'info');
            return;
        }
        
        const url = "{{ route('sarpras.inventory.print-barcodes') }}?ids=" + selected.join(',');
        window.open(url, '_blank');
    });

    function confirmDelete(url) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data barang ini akan dihapus secara permanen!",
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
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        })
    }

    function showHistory(id) {
        const modal = new bootstrap.Modal(document.getElementById('historyModal'));
        const body = document.getElementById('historyContent');
        body.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2">Memuat histori...</p></div>';
        modal.show();

        fetch(`/sarpras/inventory/${id}/history`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) throw new Error(data.message);
                
                document.getElementById('historyTitle').innerText = `Histori: ${data.inventory.name}`;
                
                let photoHtml = data.inventory.photo ? 
                    `<img src="/storage/${data.inventory.photo}" class="rounded shadow-sm border mb-3" style="width: 100%; max-height: 200px; object-fit: cover;">` : 
                    `<div class="bg-white rounded border mb-3 p-4 text-center text-muted"><i class="bi bi-image display-4"></i><br>Tidak ada foto</div>`;

                let html = `
                <div class="item-info-card p-3 bg-white rounded shadow-sm border mb-4">
                    ${photoHtml}
                    <div class="row small g-2">
                        <div class="col-6"><strong>Kode:</strong><br>${data.inventory.code}</div>
                        <div class="col-6"><strong>Kondisi:</strong><br>${data.inventory.condition}</div>
                        <div class="col-12 mt-2"><strong>Penanggung Jawab:</strong><br>${data.inventory.person_in_charge ?? '-'}</div>
                    </div>
                </div>
                <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-1"></i> Linimasa Aktivitas</h6>
                <div class="timeline-v2">`;
                
                if (data.history.length === 0) {
                    html += '<div class="alert alert-light text-center">Belum ada catatan histori untuk barang ini.</div>';
                } else {
                    data.history.forEach(item => {
                        const dateStr = new Date(item.date).toLocaleString('id-ID', {
                            day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
                        });
                        const isReport = item.type === 'report';
                        const badgeClass = isReport ? 'text-bg-danger' : 'text-bg-primary';
                        
                        html += `
                        <div class="timeline-item mb-3 pb-3 border-bottom position-relative ps-3">
                            <div class="timeline-dot position-absolute start-0 top-0 mt-1 rounded-circle ${isReport ? 'bg-danger' : 'bg-primary'}" style="width: 8px; height: 8px;"></div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="badge ${badgeClass} border-0">${item.action}</span>
                                <small class="text-muted" style="font-size: 10px;">${dateStr}</small>
                            </div>
                            <div class="text-dark small fw-medium">${item.details}</div>
                            <div class="mt-1 text-muted" style="font-size: 10px;"><i class="bi bi-person"></i> Oleh: ${item.user}</div>
                        </div>`;
                    });
                }
                html += '</div>';
                body.innerHTML = html;
            })
            .catch(err => {
                body.innerHTML = `<div class="alert alert-danger">Gagal memuat histori: ${err.message}</div>`;
            });
    }
    function submitDamageReport(event, code) {
        event.preventDefault();
        const form = event.target;
        
        Swal.fire({
            title: 'Kirim Laporan?',
            text: "Laporan akan diteruskan ke Kepala Sekolah untuk validasi.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                const formData = new FormData(form);

                fetch(`{{ route('sarpras.inventory.report-damage-by-code') }}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil', data.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
                });
            }
        });
    }
</script>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content overflow-hidden">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fs-6" id="historyTitle">Histori Barang</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light" id="historyContent" style="max-height: 450px; overflow-y: auto;">
                <!-- Content via JS -->
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endpush
@endsection
