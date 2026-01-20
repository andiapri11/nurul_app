@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Approval Laporan Sarpras (Pimpinan)</h1>
        
        <div class="d-flex gap-2">
            <div class="badge bg-success p-2 px-3 rounded-pill shadow-sm">
                <i class="bi bi-shield-check me-1"></i> Keputusan Final
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <form action="{{ route('sarpras.reports.director-index') }}" method="GET" class="d-flex flex-wrap gap-2">
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

                        <select name="director_status" class="form-select form-select-sm text-dark fw-bold w-auto">
                            <option value="">Semua Status Approval</option>
                            <option value="Pending" {{ request('director_status') == 'Pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                            <option value="Approved" {{ request('director_status') == 'Approved' ? 'selected' : '' }}>✅ Disetujui</option>
                            <option value="Rejected" {{ request('director_status') == 'Rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                        </select>

                        <div class="btn-group">
                            <button type="submit" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('sarpras.reports.director-index') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                            <a href="{{ route('sarpras.reports.print', request()->query()) }}" target="_blank" class="btn btn-sm btn-success" title="Cetak Laporan">
                                <i class="bi bi-printer"></i>
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
                            <th>Usulan Sarpras</th>
                            <th class="text-center">Validasi KS</th>
                            <th class="text-center">Status Final</th>
                            <th width="80" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="fw-bold">{{ $report->inventory->name ?? 'Barang Telah Dihapus' }}</div>
                                <div class="small text-muted">Kode: {{ $report->inventory->code ?? '-' }}</div>
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
                                <div class="fw-bold text-primary small">
                                    @if($report->follow_up_action == 'Repair') Perbaikan 
                                    @elseif($report->follow_up_action == 'Replacement') Ganti Baru 
                                    @elseif($report->follow_up_action == 'Disposal') Penghapusan 
                                    @else {{ $report->follow_up_action }} @endif
                                </div>
                                <div class="text-muted small italic">{{ Str::limit($report->follow_up_description, 50) }}</div>
                            </td>
                            <td class="text-center">
                                <div class="badge bg-info bg-opacity-10 text-info border border-info">
                                    <i class="bi bi-check-circle-fill me-1"></i> VALID
                                </div>
                                <div class="text-xs text-muted mt-1 small">{{ $report->principal->name ?? '-' }}</div>
                            </td>
                            <td class="text-center">
                                @if($report->director_status === 'Approved')
                                    <span class="badge bg-success px-3">DISETUJUI</span>
                                @elseif($report->director_status === 'Rejected')
                                    <span class="badge bg-danger px-3">DITOLAK</span>
                                @else
                                    <span class="badge bg-secondary px-3">MENUNGGU</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modal-director-{{ $report->id }}" title="Approval">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @if($report->director_status !== 'Pending')
                                    <form action="{{ route('sarpras.reports.reset-director', $report) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan keputusan ini? Status laporan dan kondisi barang akan dikembalikan ke kondisi sebelum approval.')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan Keputusan">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if($report->director_status === 'Approved' && ($report->follow_up_action === 'Disposal' || $report->follow_up_action === 'Write-off'))
                                    <a href="{{ route('sarpras.inventory.disposal-proof', $report->inventory_id) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Cetak Bukti Penghapusan">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-clipboard-x fs-1 text-muted opacity-25 d-block mb-3"></i>
                                <span class="text-muted">Tidak ada data laporan yang menunggu persetujuan akhir.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Approval Modals (Placed outside table to fix layout) -->
            @foreach($reports as $report)
            <div class="modal fade" id="modal-director-{{ $report->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-success text-white p-4">
                            <h5 class="modal-title fw-bold">
                                <i class="bi bi-clipboard-check-fill me-2"></i> Review & Keputusan Final Pimpinan
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('sarpras.reports.approve-director', $report) }}" method="POST">
                            @csrf
                            <div class="modal-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="bg-light p-3 rounded-4 h-100 border">
                                            <h6 class="fw-bold border-bottom pb-2 mb-3">Informasi Laporan</h6>
                                            @if($report->photo)
                                                <img src="{{ asset('storage/' . $report->photo) }}" class="img-fluid rounded-4 shadow-sm mb-3" style="width: 100%; height: 180px; object-fit: cover;">
                                            @endif
                                            <div class="small mb-2"><span class="text-muted">Item:</span> <span class="fw-bold">{{ $report->inventory->name }}</span></div>
                                            <div class="small mb-2"><span class="text-muted">Kode:</span> <code class="fw-bold">{{ $report->inventory->code }}</code></div>
                                            <div class="small mb-2"><span class="text-muted">Pelapor:</span> <span>{{ $report->user->name }}</span></div>
                                            <hr>
                                            <div class="small"><span class="text-muted d-block">Masalah:</span> <span class="italic">"{{ $report->description }}"</span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="bg-primary bg-opacity-5 p-3 rounded-4 h-100 border border-primary border-opacity-25">
                                            <h6 class="fw-bold text-primary border-bottom border-primary border-opacity-25 pb-2 mb-3">Rekomendasi Internal</h6>
                                            
                                            <div class="mb-4">
                                                <div class="small text-muted fw-bold text-uppercase mb-1">Usul Sarpras:</div>
                                                <div class="fw-bold text-dark h5">
                                                    @if($report->follow_up_action == 'Repair') Perbaikan 
                                                    @elseif($report->follow_up_action == 'Replacement') Ganti Baru 
                                                    @elseif($report->follow_up_action == 'Disposal') Penghapusan 
                                                    @else {{ $report->follow_up_action }} @endif
                                                </div>
                                                <p class="small text-muted mb-0">"{{ $report->follow_up_description }}"</p>
                                            </div>

                                            <div class="mb-2">
                                                <div class="small text-muted fw-bold text-uppercase mb-1">Validasi Kepala Sekolah:</div>
                                                <div class="badge bg-info mb-2"><i class="bi bi-check-circle-fill me-1"></i> VALID</div>
                                                <p class="small text-muted italic mb-0">"{{ $report->principal_note ?? 'Telah divalidasi oleh Kepala Sekolah.' }}"</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3">
                                            <h6 class="fw-bold text-success border-bottom pb-2 mb-3">Keputusan Anda</h6>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Status Persetujuan</label>
                                                <select name="director_status" class="form-select border-2 border-success border-opacity-25" required>
                                                    <option value="Approved" {{ $report->director_status === 'Approved' ? 'selected' : '' }}>✅ SETUJUI USULAN</option>
                                                    <option value="Rejected" {{ $report->director_status === 'Rejected' ? 'selected' : '' }}>❌ TOLAK USULAN</option>
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label small fw-bold">Catatan Pimpinan (Opsional)</label>
                                                <textarea name="director_note" class="form-control" rows="5" placeholder="Contoh: Silakan diproses sesuai anggaran yang tersedia...">{{ $report->director_note }}</textarea>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-success w-100 fw-bold py-3 shadow">
                                                <i class="bi bi-check-all me-1"></i> Simpan Keputusan Final
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
            
            @if($reports->hasPages())
            <div class="mt-4">
                {{ $reports->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .badge { font-weight: 600; font-size: 0.75rem; }
    .italic { font-style: italic; }
    .text-xs { font-size: 0.7rem; }
</style>
@endsection
