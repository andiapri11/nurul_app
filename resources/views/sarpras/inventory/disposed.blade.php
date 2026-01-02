@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800">Arsip Penghapusan / Pemusnahan Barang</h1>
            <p class="text-muted">Data barang yang telah dikeluarkan dari daftar aktif.</p>
        </div>
        <a href="{{ route('sarpras.inventory.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Inventaris
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('sarpras.inventory.disposed') }}" class="row g-3 align-items-end mb-4">
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
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ request('academic_year_id') == $ay->id ? 'selected' : '' }}>
                                {{ $ay->name }} {{ $ay->status === 'active' ? '(Aktif)' : '(Tidak Aktif)' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Cari Barang / Alasan</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Nama, kode, atau alasan..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('sarpras.inventory.disposed') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50" class="text-center">No</th>
                            <th>Nama Barang</th>
                            <th>Kode</th>
                            <th>Tahun Pelajaran</th>
                            <th>Unit / Ruangan Terakhir</th>
                            <th>Tanggal Hapus</th>
                            <th>Alasan & Bukti</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventories as $index => $item)
                        <tr>
                            <td class="text-center">{{ $inventories->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($item->photo)
                                        <div class="flex-shrink-0">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#viewPhotoModal{{ $item->id }}">
                                                <img src="{{ asset('storage/' . $item->photo) }}" 
                                                     class="rounded shadow-sm border" 
                                                     style="width: 45px; height: 45px; object-fit: cover;"
                                                     alt="{{ $item->name }}">
                                            </a>
                                        </div>
                                        <!-- Photo Preview Modal -->
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
                                    @else
                                        <div class="flex-shrink-0">
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center border" 
                                                 style="width: 45px; height: 45px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-dark">{{ $item->name }}</div>
                                        <small class="text-muted">{{ $item->category->name ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><code>{{ $item->code }}</code></td>
                            <td>
                                {{ $item->room->academicYear->name ?? '-' }}
                                @if(isset($item->room->academicYear))
                                    <br>
                                    <small class="badge {{ $item->room->academicYear->status === 'active' ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.65rem;">
                                        {{ $item->room->academicYear->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                {{ $item->room->unit->name ?? 'N/A' }} / {{ $item->room->name ?? 'N/A' }}
                            </td>
                            <td>
                                <span class="text-danger fw-bold">{{ $item->deleted_at->format('d/m/Y') }}</span>
                                <div class="small text-muted">{{ $item->deleted_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div class="mb-1"><em class="text-muted small">{{ $item->disposal_reason ?: 'Dihapus manual' }}</em></div>
                                @if($item->disposal_photo)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#viewDisposalPhoto{{ $item->id }}" class="badge bg-success text-decoration-none">
                                        <i class="bi bi-image me-1"></i> Bukti Terlampir
                                    </a>
                                    <!-- View Disposal Photo Modal -->
                                    <div class="modal fade" id="viewDisposalPhoto{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content bg-transparent border-0">
                                                <div class="modal-body p-0 text-center">
                                                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    <img src="{{ asset('storage/' . $item->disposal_photo) }}" class="img-fluid rounded shadow-lg">
                                                    <div class="mt-2 text-white fw-bold">Bukti Pemusnahan: {{ $item->name }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle me-1"></i> Menunggu Bukti</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    @if($item->disposal_photo)
                                        <div class="btn-group">
                                            <a href="{{ route('sarpras.inventory.disposal-proof', $item->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Cetak Berita Acara (PDF)">
                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#uploadProof{{ $item->id }}" title="Upload Ulang Bukti">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#uploadProof{{ $item->id }}" title="Upload Bukti Pemusnahan">
                                            <i class="bi bi-upload"></i>
                                        </button>
                                    @endif

                                    @if(Auth::user()->role === 'administrator')
                                    <form action="{{ route('sarpras.inventory.restore', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Kembalikan barang ini ke daftar aktif?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Pulihkan">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('sarpras.inventory.force-delete', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('HAPUS PERMANEN? Data tidak dapat dikembalikan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Permanen">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>

                                <!-- Upload Proof Modal -->
                                <div class="modal fade" id="uploadProof{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('sarpras.inventory.upload-disposal-photo', $item->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning text-dark">
                                                    <h5 class="modal-title fw-bold"><i class="bi bi-upload me-2"></i>Upload Bukti Pemusnahan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <p class="small text-muted">Silakan unggah foto sebagai bukti bahwa barang <strong>{{ $item->name }}</strong> telah dimusnahkan/dibuang secara fisik.</p>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Pilih Foto Bukti</label>
                                                        <input type="file" name="disposal_photo" class="form-control" accept="image/*" required>
                                                        <div class="form-text mt-1 text-xs text-danger">* Wajib diunggah sebelum dapat mencetak PDF Berita Acara.</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning fw-bold">Simpan Bukti</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-archive text-muted display-4"></i>
                                <p class="mt-2 text-muted">Belum ada data barang yang dihapus/dimusnahkan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $inventories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
