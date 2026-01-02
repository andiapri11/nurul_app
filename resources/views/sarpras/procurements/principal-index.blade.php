@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Validasi Pengadaan Barang (Kepala Sekolah)</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <!-- Information & Filter -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tahun Pelajaran Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeAcademicYear ? $activeAcademicYear->name : 'N/A' }}</div>
                            <div class="text-xs text-muted mt-1 small">Validasi difokuskan pada pengajuan di tahun aktif ini.</div>
                        </div>
                        <div class="col-auto"><i class="bi bi-calendar-event fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow h-100">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('sarpras.procurements.index-principal') }}" class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Unit Pendidikan</label>
                            <select name="unit_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Tahun Pelajaran</label>
                            <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Tahun</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ ($academic_year_id ?? '') == $ay->id ? 'selected' : '' }}>{{ $ay->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('sarpras.procurements.index-principal') }}" class="btn btn-outline-secondary w-100" title="Reset">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Tunggu Validasi</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>Tgl Pengajuan</th>
                            <th>Unit</th>
                            <th>Item / Kategori</th>
                            <th class="text-center">Jumlah</th>
                            <th>Pemohon</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-dark">
                        @forelse($procurements as $item)
                        <tr>
                            <td class="small">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>{{ $item->unit->name }}</td>
                            <td>
                                <div class="fw-bold">{{ $item->item_name }}</div>
                                <small class="text-muted">{{ $item->category->name }}</small>
                                @if($item->type === 'Asset')
                                    <span class="badge bg-primary-subtle text-primary border border-primary small">ASET</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning small">HABIS PAKAI</span>
                                @endif
                            </td>
                            <td class="text-center fw-bold">{{ $item->quantity }} {{ $item->unit_name }}</td>
                            <td class="small">{{ $item->user->name }}</td>
                            <td class="text-center">
                                @if($item->principal_status === 'Pending')
                                    <span class="badge bg-warning text-dark">Menunggu Validasi</span>
                                @elseif($item->principal_status === 'Validated')
                                    <span class="badge bg-success">Tervalidasi</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-end px-3">
                                @if($item->principal_status === 'Pending')
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#validateModal{{ $item->id }}">
                                    Periksa
                                </button>
                                @else
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#validateModal{{ $item->id }}">
                                    Detail
                                </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Validation Modal -->
                        <div class="modal fade" id="validateModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <form action="{{ route('sarpras.procurements.validate-principal', $item->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-content text-start text-dark">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Periksa Pengajuan: {{ $item->item_name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6 border-end">
                                                    <h6 class="fw-bold text-primary">Informasi Pengajuan</h6>
                                                    <table class="table table-sm table-borderless">
                                                        <tr><td width="120">Unit</td><td>: {{ $item->unit->name }}</td></tr>
                                                        <tr><td>Pemohon</td><td>: {{ $item->user->name }}</td></tr>
                                                        <tr><td>Tgl</td><td>: {{ $item->created_at->format('d/m/Y H:i') }}</td></tr>
                                                        <tr><td>Jumlah</td><td>: {{ $item->quantity }} {{ $item->unit_name }}</td></tr>
                                                        <tr><td>Estimasi</td><td>: Rp {{ number_format($item->estimated_price, 0, ',', '.') }}</td></tr>
                                                    </table>
                                                    <div class="mt-2">
                                                        <label class="fw-bold d-block small text-muted">Keterangan Pemohon:</label>
                                                        <div class="p-2 bg-light rounded border small">{{ $item->description ?: '-' }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    @if($item->photo)
                                                    <h6 class="fw-bold text-primary">Foto Referensi</h6>
                                                    <img src="{{ asset('storage/' . $item->photo) }}" class="img-fluid rounded border shadow-sm" alt="Foto">
                                                    @else
                                                    <div class="h-100 d-flex align-items-center justify-content-center bg-light border rounded">
                                                        <p class="text-muted small">Tidak ada foto referensi</p>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($item->principal_status === 'Pending')
                                            <hr>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Keputusan Validasi</label>
                                                <div class="d-flex gap-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="status" id="valid{{ $item->id }}" value="Validated" checked>
                                                        <label class="form-check-label text-success fw-bold" for="valid{{ $item->id }}">Setujui & Teruskan (Validasi)</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="status" id="reject{{ $item->id }}" value="Rejected">
                                                        <label class="form-check-label text-danger fw-bold" for="reject{{ $item->id }}">Tolak Pengajuan</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-0">
                                                <label class="form-label fw-bold">Catatan Kepala Sekolah (Opsional)</label>
                                                <textarea name="note" class="form-control" rows="2" placeholder="Berikan catatan jika diperlukan..."></textarea>
                                            </div>
                                            @else
                                            <hr>
                                            <div class="p-3 bg-{{ $item->principal_status === 'Validated' ? 'success' : 'danger' }}-subtle border border-{{ $item->principal_status === 'Validated' ? 'success' : 'danger' }} rounded">
                                                <div class="fw-bold">Keputusan: {{ $item->principal_status === 'Validated' ? 'TERVALIDASI' : 'DITOLAK' }}</div>
                                                <div class="small mt-1 italic">Catatan: {{ $item->principal_note ?: '-' }}</div>
                                                <div class="small mt-1 text-muted">Divalidasi pada: {{ $item->validated_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            @if($item->principal_status === 'Pending')
                                            <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada pengajuan untuk divalidasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-light px-3 py-2">
                {{ $procurements->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
