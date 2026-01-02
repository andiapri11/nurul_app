@extends('layouts.app')

@section('title', 'Riwayat Transaksi Pembayaran')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold text-dark mb-0">
                <i class="bi bi-clock-history text-primary me-2"></i> RIWAYAT TRANSAKSI PEMBAYARAN
            </h4>
            <p class="text-muted small">Daftar seluruh penerimaan pembayaran siswa secara real-time.</p>
        </div>
        <div class="col-md-6 text-end">
             <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm">
                Total Transaksi: {{ $transactions->total() }}
             </span>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form action="{{ route('finance.transactions.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label x-small fw-bold text-muted text-uppercase ls-1">Cari Siswa / Kode Pembayaran</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0 shadow-none px-3" 
                               placeholder="Nama, NIS, atau Kode Pembayaran..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label x-small fw-bold text-muted text-uppercase ls-1">Filter Tanggal</label>
                    <input type="date" name="date" class="form-control bg-light border-0 shadow-none px-3" 
                           value="{{ request('date') }}">
                </div>
                <div class="col-md-5 d-flex align-items-end gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary px-3 rounded-pill shadow-sm">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('finance.transactions.index') }}" class="btn btn-light px-3 rounded-pill border shadow-sm">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                    
                    <div class="ms-auto d-flex gap-2">
                        <a href="{{ route('finance.transactions.export.excel', request()->all()) }}" class="btn btn-success px-3 rounded-pill shadow-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i> Excel
                        </a>
                        <a href="{{ route('finance.transactions.export.pdf', request()->all()) }}" target="_blank" class="btn btn-danger px-3 rounded-pill shadow-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-muted x-small text-uppercase ls-1">
                        <tr>
                            <th class="ps-4 py-3">KODE PEMBAYARAN & TANGGAL</th>
                            <th>SISWA</th>
                            <th>URAIAN PEMBAYARAN</th>
                            <th class="text-end">NOMINAL</th>
                            <th>PENERIMA</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $t)
                            @php $isVoid = $t->is_void; @endphp
                            <tr class="animate__animated animate__fadeIn {{ $isVoid ? 'opacity-50 grayscale bg-light' : '' }}">
                                <td class="ps-4">
                                    <div class="fw-bold {{ $isVoid ? 'text-decoration-line-through text-muted' : 'text-dark' }} mb-1" style="font-size: 1rem;">
                                        {{ $t->invoice_number }}
                                        @if($isVoid) <span class="badge bg-danger ms-1" style="font-size: 0.6rem;">VOID</span> @endif
                                    </div>
                                    <div class="x-small text-muted">{{ $t->transaction_date->format('d/m/Y') }} <span class="mx-1">•</span> {{ $t->created_at->format('H:i') }}</div>
                                    <div class="mt-1">
                                        <span class="badge {{ $t->payment_method == 'transfer' ? 'bg-info-soft text-info' : 'bg-secondary-soft text-secondary' }} x-small px-2 border">
                                            {{ strtoupper($t->payment_method) }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-bold {{ $isVoid ? 'text-muted' : 'text-dark' }}" style="font-size: 1rem;">{{ $t->student->nama_lengkap }}</div>
                                            <div class="x-small text-muted">{{ $t->student->nis }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($isVoid)
                                        <div class="alert alert-danger py-1 px-2 m-0 x-small border-0 shadow-none">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                            <strong>VOID:</strong> {{ $t->void_reason }}
                                        </div>
                                    @else
                                        @foreach($t->items as $item)
                                            <div class="x-small text-muted mb-1 d-flex justify-content-between align-items-center me-3">
                                                <div>
                                                    <span class="fw-bold text-primary">{{ $item->paymentType->name ?? '-' }}</span>
                                                    @if($item->month_paid)
                                                        <span class="text-muted ms-1">({{ \Carbon\Carbon::create()->month((int)$item->month_paid)->translatedFormat('F') }} {{ $item->year_paid }})</span>
                                                    @endif
                                                </div>
                                                <span class="text-dark font-monospace">Rp{{ number_format($item->amount, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold {{ $isVoid ? 'text-muted text-decoration-line-through' : 'text-dark' }} h5 mb-0">
                                        Rp{{ number_format($t->getOriginal('amount') ?? $t->amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="x-small fw-bold text-dark">{{ $t->user->name ?? 'System' }}</div>
                                    <div class="x-small text-muted">Admin Finance</div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center align-items-center">
                                        @if(!$isVoid)
                                            <a href="{{ route('finance.payments.receipt', $t->id) }}" target="_blank" class="btn-action btn-action-receipt" title="Cetak Kuitansi">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            <a href="{{ route('finance.payments.show', $t->student_id) }}" class="btn-action btn-action-view" title="Lihat Profil POS">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <!-- Tombol Trigger Modal VOID -->
                                            <button type="button" class="btn-action btn-action-void" data-bs-toggle="modal" data-bs-target="#voidModal{{ $t->id }}" title="VOID Transaksi">
                                                <i class="bi bi-trash3"></i>
                                            </button>

                                            <!-- Modal VOID -->
                                            <div class="modal fade" id="voidModal{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                                        <form action="{{ route('finance.payments.transactions.destroy', $t->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <div class="modal-header bg-danger text-white border-0 py-3">
                                                                <h6 class="modal-title fw-bold"><i class="bi bi-exclamation-octagon me-2"></i> VOID TRANSAKSI: {{ $t->invoice_number }}</h6>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body p-4 text-start">
                                                                <div class="alert alert-warning x-small mb-4 border-0 shadow-none">
                                                                    <i class="bi bi-info-circle-fill me-2"></i>
                                                                    Tindakan VOID akan membatalkan seluruh item dalam kode pembayaran ini dan mengembalikan status tagihan siswa menjadi BELUM BAYAR. Record transaksi tidak akan terhapus secara permanen.
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold text-dark small">Alasan Pembatalan / VOID</label>
                                                                    <textarea name="void_reason" class="form-control bg-light border-0 shadow-none" rows="2" placeholder="Contoh: Salah input nominal, Siswa salah bayar, dll..." required></textarea>
                                                                </div>

                                                                <div class="border-top pt-3 text-center">
                                                                    <label class="form-label fw-bold text-dark small mb-3 text-uppercase ls-1">Masukkan PIN Keamanan</label>
                                                                    <div class="d-flex justify-content-center gap-2 mb-3 pin-container">
                                                                        <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                                                                        <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                                                                        <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                                                                        <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                                                                        <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                                                                        <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                                                                    </div>
                                                                    <input type="hidden" name="security_pin">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-0 p-3 pt-0">
                                                                <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-danger px-4 rounded-pill shadow-sm">
                                                                    <i class="bi bi-shield-lock me-1"></i> Konfirmasi VOID
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-muted italic x-small">Dibatalkan (VOID)</span>
                                                @if(auth()->user()->role == 'administrator')
                                                    <!-- Tombol Batalkan VOID -->
                                                    <form action="{{ route('finance.payments.transactions.unvoid', $t->id) }}" method="POST" onsubmit="return confirm('Kembalikan status transaksi ini menjadi AKTIF?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 24px; height: 24px;" title="Batalkan VOID (Aktifkan Kembali)">
                                                            <i class="bi bi-arrow-counterclockwise" style="font-size: 0.9rem;"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- KHUSUS ADMINISTRATOR BISA HAPUS PERMANEN KAPANPUN DENGAN PIN --}}
                                        @if(auth()->user()->role == 'administrator')
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn-action btn-action-delete" data-bs-toggle="modal" data-bs-target="#forceDeleteModal{{ $t->id }}" title="HAPUS PERMANEN (TOTAL)">
                                                <i class="bi bi-shield-lock-fill"></i>
                                            </button>

                                            <!-- Modal PIN Modern -->
                                            <div class="modal fade" id="forceDeleteModal{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow-lg rounded-5 overflow-hidden">
                                                        <form action="{{ route('finance.payments.transactions.force-delete', $t->id) }}" method="POST" class="pin-form">
                                                            @csrf @method('DELETE')
                                                            <div class="modal-header bg-dark text-white border-0 py-4 px-4 position-relative">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="bg-danger rounded-circle p-2 me-3 shadow-sm">
                                                                        <i class="bi bi-shield-lock-fill text-white fs-4"></i>
                                                                    </div>
                                                                    <div>
                                                                        <h5 class="modal-title fw-bold mb-0">Otentikasi Keamanan</h5>
                                                                        <p class="x-small mb-0 text-white-50">Konfirmasi Penghapusan Permanen</p>
                                                                    </div>
                                                                </div>
                                                                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body p-4 p-md-5 text-center">
                                                                <div class="alert alert-warning border-0 rounded-4 x-small mb-5 shadow-sm py-3 px-4">
                                                                    <div class="d-flex align-items-center justify-content-center text-dark fw-bold mb-1">
                                                                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> TINDAKAN IRREVERSIBLE!
                                                                    </div>
                                                                    <p class="mb-0 opacity-75">Data transaksi <strong>{{ $t->invoice_number }}</strong> akan dihapus selamanya dari database dan tidak dapat dipulihkan.</p>
                                                                </div>

                                                                <h6 class="fw-bold text-dark mb-4 ls-1">MASUKKAN PIN KEAMANAN</h6>
                                                                
                                                                <div class="d-flex justify-content-center gap-2 mb-4 pin-container">
                                                                    <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required>
                                                                    <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required>
                                                                    <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required>
                                                                    <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required>
                                                                    <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required>
                                                                    <input type="password" class="form-control pin-input text-center fw-bold fs-3 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required>
                                                                </div>
                                                                
                                                                <!-- Hidden field to store full PIN -->
                                                                <input type="hidden" name="security_pin">

                                                                <p class="x-small text-muted mt-2 mb-0">
                                                                    <i class="bi bi-info-circle me-1"></i> Lupa PIN? Hubungi Administrator Utama.
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer border-0 p-4 pt-0">
                                                                <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill fw-bold shadow-sm py-3">
                                                                    <i class="bi bi-trash3-fill me-2"></i> YA, HAPUS PERMANEN
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-3 opacity-25"></i>
                                        <p class="mb-0">Tidak ada riwayat transaksi yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    /* ACTION BUTTONS TIDY UP */
    .btn-action {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e9ecef;
        background: #fff;
        color: #64748b;
        cursor: pointer;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background: #f8fafc;
    }

    .btn-action i { font-size: 1.1rem; }

    .btn-action-receipt:hover { color: #0d6efd; border-color: #0d6efd; background-color: rgba(13, 110, 253, 0.05); }
    .btn-action-view:hover { color: #0dcaf0; border-color: #0dcaf0; background-color: rgba(13, 202, 240, 0.05); }
    .btn-action-void:hover { color: #dc3545; border-color: #dc3545; background-color: rgba(220, 53, 69, 0.05); }
    .btn-action-delete:hover { color: #1e293b; border-color: #1e293b; background-color: rgba(30, 41, 59, 0.1); }
    .x-small { font-size: 0.82rem; }
    .bg-info-soft { background-color: rgba(13, 202, 240, 0.1); }
    .bg-secondary-soft { background-color: rgba(108, 117, 125, 0.1); }
    .lh-1 { line-height: 1.2; }
    .table td { padding: 1.25rem 0.75rem !important; }
    
    /* PIN MODAL STYLES */
    .pin-input {
        width: 50px;
        height: 60px;
        border: 2px solid #e9ecef;
        background-color: #f8f9fa;
        transition: all 0.2s ease;
    }
    .pin-input:focus {
        border-color: #dc3545;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(220, 53, 69, 0.2);
        transform: translateY(-2px);
    }
    .rounded-5 { border-radius: 2rem !important; }
    .ls-2 { letter-spacing: 2px; }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-8px); }
        50% { transform: translateX(8px); }
        75% { transform: translateX(-8px); }
    }
    .shake { animation: shake 0.4s ease-in-out; }
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pinContainers = document.querySelectorAll('.pin-container');
    
    pinContainers.forEach(container => {
        const inputs = container.querySelectorAll('.pin-input');
        const hiddenInput = container.closest('form').querySelector('input[name="security_pin"]');
        const form = container.closest('form');

        inputs.forEach((input, index) => {
            // Handle typing
            input.addEventListener('input', (e) => {
                if (e.target.value.length > 1) {
                    e.target.value = e.target.value.slice(0, 1);
                }
                
                if (e.target.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                
                updateHiddenInput();
            });

            // Handle backspace
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            // Paste handling
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').slice(0, 6).split('');
                pasteData.forEach((char, i) => {
                    if (inputs[index + i]) {
                        inputs[index + i].value = char;
                    }
                });
                const nextFocus = index + pasteData.length;
                if (inputs[nextFocus]) {
                    inputs[nextFocus].focus();
                } else if (inputs[5]) {
                    inputs[5].focus();
                }
                updateHiddenInput();
            });
        });

        function updateHiddenInput() {
            let pin = "";
            inputs.forEach(input => pin += input.value);
            hiddenInput.value = pin;
        }

        form.addEventListener('submit', function(e) {
            updateHiddenInput();
            if (hiddenInput.value.length < 6) {
                e.preventDefault();
                container.classList.add('shake');
                setTimeout(() => container.classList.remove('shake'), 500);
                
                Swal.fire({
                    icon: 'warning',
                    title: 'PIN Belum Lengkap',
                    text: 'Silakan masukkan 6 digit PIN Anda.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });

    // Auto-focus first input when modal opens
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            const firstInput = modal.querySelector('.pin-input');
            if (firstInput) firstInput.focus();
        });
    });

    // RE-OPEN MODAL ON PIN ERROR
    @if(session('open_pin_modal'))
        const targetModalId = '#forceDeleteModal{{ session('open_pin_modal') }}';
        const targetModal = new bootstrap.Modal(document.querySelector(targetModalId));
        targetModal.show();
        
        // Add shake to the container
        setTimeout(() => {
            const container = document.querySelector(targetModalId + ' .pin-container');
            if(container) {
                container.classList.add('shake');
                setTimeout(() => container.classList.remove('shake'), 500);
            }
        }, 500);
    @endif
});
</script>
@endpush
