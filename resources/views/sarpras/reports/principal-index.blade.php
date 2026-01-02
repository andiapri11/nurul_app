@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Validasi Laporan Sarpras (Kepala Sekolah)</h1>
        
        <div class="d-flex gap-2">
            <div class="badge bg-primary p-2 px-3 rounded-pill shadow-sm">
                <i class="bi bi-hourglass-split me-1"></i> {{ $stats['pending'] }} Pending
            </div>
            <div class="badge bg-success p-2 px-3 rounded-pill shadow-sm">
                <i class="bi bi-check-circle me-1"></i> {{ $stats['approved'] }} Valid
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <form action="{{ route('sarpras.reports.principal-index') }}" method="GET" class="d-flex flex-wrap gap-2">
                        <select name="unit_id" class="form-select form-select-sm text-dark fw-bold w-auto">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        
                        <select name="academic_year_id" class="form-select form-select-sm text-dark fw-bold w-auto">
                            <option value="">Semua Tahun</option>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ request('academic_year_id') == $ay->id ? 'selected' : '' }}>
                                    {{ $ay->name }} {{ $ay->status == 'active' ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>

                        <select name="approval_status" class="form-select form-select-sm text-dark fw-bold w-auto">
                            <option value="">Semua Status Validasi</option>
                            <option value="Pending" {{ request('approval_status') == 'Pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                            <option value="Approved" {{ request('approval_status') == 'Approved' ? 'selected' : '' }}>✅ Valid/Disetujui</option>
                            <option value="Rejected" {{ request('approval_status') == 'Rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                        </select>

                        <div class="btn-group">
                            <button type="submit" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('sarpras.reports.principal-index') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle" width="100%" cellspacing="0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th class="text-center">Tipe</th>
                            <th>Pelapor</th>
                            <th>Masalah/Deskripsi</th>
                            <th class="text-center">Foto Bukti</th>
                            <th class="text-center">Status Validasi</th>
                            <th width="80" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="fw-bold">{{ $report->inventory->name }}</div>
                                <div class="small text-muted">Kode: {{ $report->inventory->code }}</div>
                            </td>
                            <td class="text-center">
                                @if($report->type === 'Lost')
                                    <span class="badge bg-danger">HILANG</span>
                                @else
                                    <span class="badge bg-warning text-dark">RUSAK</span>
                                @endif
                            </td>
                            <td>{{ $report->user->name }}</td>
                            <td>
                                <div class="text-wrap" style="max-width: 250px;">{{ $report->description }}</div>
                            </td>
                            <td class="text-center">
                                @if($report->photo)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#viewProof{{ $report->id }}">
                                        <img src="{{ asset('storage/' . $report->photo) }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    </a>

                                    <!-- Proof Photo Modal -->
                                    <div class="modal fade" id="viewProof{{ $report->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fs-6">Bukti Laporan #{{ $report->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center p-0">
                                                    <img src="{{ asset('storage/' . $report->photo) }}" class="img-fluid" style="max-height: 80vh;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">No Photo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($report->principal_approval_status === 'Approved')
                                    <span class="badge bg-success px-3">VALID</span>
                                @elseif($report->principal_approval_status === 'Rejected')
                                    <span class="badge bg-danger px-3">DITOLAK</span>
                                @else
                                    <span class="badge bg-primary px-3 shadow-none pulsing-badge">PENDING</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-approval-{{ $report->id }}" title="Validasi">
                                    <i class="bi bi-shield-check"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Validation (Simpler Version matching index.blade.php style) -->
                        <div class="modal fade" id="modal-approval-{{ $report->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header bg-primary text-white p-4">
                                        <h5 class="modal-title fw-bold">
                                            <i class="bi bi-shield-check me-2"></i> Validasi Laporan Kerusakan
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('sarpras.reports.approve-principal', $report) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <div class="row g-4">
                                                <div class="col-md-5">
                                                    @if($report->photo)
                                                        <img src="{{ asset('storage/' . $report->photo) }}" class="img-fluid rounded-4 shadow-sm border" style="width: 100%; height: 250px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded-4 d-flex align-items-center justify-content-center border border-dashed" style="height: 250px;">
                                                            <div class="text-center text-muted">
                                                                <i class="bi bi-image fs-1 opacity-25"></i>
                                                                <p class="small mb-0">Tanpa Foto</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="mb-3 bg-light p-3 rounded-4">
                                                        <div class="fw-bold text-primary mb-2">Saran Tindakan (Sarpras):</div>
                                                        <div class="badge bg-info mb-2">
                                                            @if($report->follow_up_action == 'Repair') Perbaikan 
                                                            @elseif($report->follow_up_action == 'Replacement') Ganti Baru 
                                                            @elseif($report->follow_up_action == 'Disposal') Penghapusan 
                                                            @else {{ $report->follow_up_action }} @endif
                                                        </div>
                                                        <p class="small mb-0 text-muted italic">"{{ $report->follow_up_description }}"</p>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Status Validasi</label>
                                                        <select name="principal_approval_status" class="form-select" required>
                                                            <option value="Approved" {{ $report->principal_approval_status === 'Approved' ? 'selected' : '' }}>✅ SETUJUI / VALID</option>
                                                            <option value="Rejected" {{ $report->principal_approval_status === 'Rejected' ? 'selected' : '' }}>❌ TOLAK</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="form-label small fw-bold">Catatan Kepala Sekolah (Opsional)</label>
                                                        <textarea name="principal_note" class="form-control" rows="3" placeholder="Masukkan catatan jika ada...">{{ $report->principal_note }}</textarea>
                                                    </div>
                                                    
                                                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                                                        <i class="bi bi-save me-1"></i> Simpan Status Validasi
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted opacity-25 d-block mb-3"></i>
                                <span class="text-muted">Tidak ada data laporan yang membutuhkan validasi.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($reports->hasPages())
            <div class="mt-4">
                {{ $reports->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .pulsing-badge {
        animation: pulse-animation 2s infinite;
    }
    @keyframes pulse-animation {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
    }
    .badge { font-weight: 600; font-size: 0.75rem; }
    .italic { font-style: italic; }
</style>
@endsection
