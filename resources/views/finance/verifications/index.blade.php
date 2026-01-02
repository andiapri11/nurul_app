@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold"><i class="bi bi-patch-check-fill me-2 text-primary"></i> Verifikasi Pembayaran</h3>
            <p class="text-muted mb-0">Kelola dan verifikasi bukti transfer pembayaran siswa.</p>
        </div>
        <div class="btn-group shadow-sm" role="group">
            <a href="{{ route('finance.verifications.index', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'btn-primary' : 'btn-light border' }}">Perlu Verifikasi (PROSES)</a>
            <a href="{{ route('finance.verifications.index', ['status' => 'waiting_proof']) }}" class="btn {{ request('status') == 'waiting_proof' ? 'btn-primary' : 'btn-light border' }}">Belum Upload (PENDING)</a>
            <a href="{{ route('finance.verifications.index', ['status' => 'verified']) }}" class="btn {{ request('status') == 'verified' ? 'btn-primary' : 'btn-light border' }}">Diterima</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase text-muted small fw-bold">
                        <tr>
                            <th class="ps-4 py-3">Tanggal</th>
                            <th class="py-3">Siswa</th>
                            <th class="py-3">Bank Tujuan</th>
                            <th class="py-3 text-end">Total</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="pe-4 py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold">{{ $req->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $req->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $req->student->nama_lengkap ?? 'Unknown' }}</div>
                                    <span class="badge bg-light text-dark border">{{ $req->student->unit->name ?? '-' }}</span>
                                </td>
                                <td>
                                    {{ $req->bankAccount->bank_name ?? '-' }}
                                    <div class="small text-muted">{{ $req->bankAccount->account_number ?? '' }}</div>
                                </td>
                                <td class="text-end fw-bold">Rp {{ number_format($req->total_amount, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($req->status == 'waiting_proof')
                                        <span class="badge bg-warning text-dark rounded-pill">PENDING</span>
                                    @elseif($req->status == 'pending')
                                        <span class="badge bg-info rounded-pill">PROSES</span>
                                    @elseif($req->status == 'verified')
                                        <span class="badge bg-success rounded-pill">DITERIMA</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill">DITOLAK</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        @if($req->proof_image)
                                            <button type="button" class="btn btn-sm btn-outline-info rounded-circle shadow-sm" 
                                                onclick="showQuickProof('{{ asset('uploads/payment_proofs/' . $req->proof_image) }}')" 
                                                title="Lihat Bukti Tercepat">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('finance.verifications.show', $req->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                            Periksa <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                        @if(auth()->user()->role == 'administrator')
                                            <button type="button" class="btn btn-sm btn-danger rounded-circle shadow-sm" 
                                                onclick="confirmDelete('{{ $req->id }}')" 
                                                title="Hapus Permanen">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                    Tidak ada data permintaan verifikasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $requests->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@if(auth()->user()->role == 'administrator')
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-danger">Hapus Pengajuan Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center py-4">
                    <p class="mb-4">Apakah Anda yakin ingin menghapus data pengajuan ini secara permanen?<br>Tindakan ini tidak dapat dibatalkan.</p>
                    
                    <div class="form-group text-start px-4">
                        <label class="form-label fw-bold small text-muted">PIN Keamanan Admin</label>
                        <input type="password" name="security_pin" class="form-control form-control-lg text-center letter-spacing-2" placeholder="Masukkan PIN" required maxlength="6">
                    </div>
                </div>
                <div class="modal-footer border-top-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-5 fw-bold">Hapus Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endif

{{-- Quick Proof Modal --}}
<div class="modal fade" id="quickProofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg bg-dark">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2 text-center">
                <img id="quickProofImg" src="" class="img-fluid rounded-3 shadow" alt="Bukti Transfer">
            </div>
        </div>
    </div>
</div>

<script>
    function showQuickProof(imgUrl) {
        document.getElementById('quickProofImg').src = imgUrl;
        var myModal = new bootstrap.Modal(document.getElementById('quickProofModal'));
        myModal.show();
    }
    
    function confirmDelete(id) {
        var form = document.getElementById('deleteForm');
        form.action = '/finance/verifications/' + id;
        var myModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        myModal.show();
    }
</script>
@endsection
