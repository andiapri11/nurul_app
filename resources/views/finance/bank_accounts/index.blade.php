@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1">Manajemen Akun Bank</h2>
            <p class="text-muted mb-0">Kelola rekening bank lembaga dan pantau saldo kas secara real-time.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button type="button" class="btn btn-primary btn-lg rounded-pill shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle-fill me-2"></i>Tambah Akun Baru
            </button>
        </div>
    </div>

    <!-- Quick Stats Section -->
    <div class="row g-3 mb-4">

        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 text-primary me-3">
                            <i class="bi bi-bank fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1 fw-medium">Akun Bank Aktif</h6>
                            <h3 class="text-dark mb-0 fw-bold">{{ $accounts->where('is_active', true)->count() }} <span class="fs-6 text-muted fw-normal">Rekening</span></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3 text-warning me-3">
                            <i class="bi bi-exclamation-octagon fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1 fw-medium">Akun Non-Aktif</h6>
                            <h3 class="text-dark mb-0 fw-bold">{{ $accounts->where('is_active', false)->count() }} <span class="fs-6 text-muted fw-normal">Rekening</span></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-bold text-dark">Daftar Rekening</h5>
                <div class="input-group" style="max-width: 250px;">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="tableSearch" class="form-control bg-light border-0" placeholder="Cari akun...">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if(session('success'))
                <div class="px-4 pt-3">
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="bankAccountsTable">
                    <thead class="bg-light text-muted text-uppercase small">
                        <tr>
                            <th class="py-3 ps-4 border-0">Bank & Pemilik</th>
                            <th class="py-3 border-0">Nomor Rekening</th>

                            <th class="py-3 border-0 text-center">Status</th>
                            <th class="py-3 pe-4 border-0 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $account)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-info me-3" style="width: 45px; height: 45px;">
                                            <i class="bi bi-bank2 fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $account->bank_name }}</div>
                                            <div class="text-muted small">{{ $account->account_holder }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <code class="text-secondary fs-6 me-2">{{ $account->account_number }}</code>
                                        <button class="btn btn-sm btn-link text-muted p-0" onclick="copyToClipboard('{{ $account->account_number }}')" title="Salin No. Rekening">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </td>

                                <td class="py-3 text-center">
                                    @if($account->is_active)
                                        <span class="badge bg-success-subtle text-success border border-success border-opacity-25 rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary border-opacity-25 rounded-pill px-3 py-2">
                                            <i class="bi bi-pause-circle-fill me-1"></i> Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 pe-4 text-center">
                                    <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                        <button class="btn btn-white btn-sm px-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $account->id }}" title="Edit">
                                            <i class="bi bi-pencil-square text-warning"></i>
                                        </button>
                                        <button type="button" class="btn btn-white btn-sm px-3 border-start delete-btn" 
                                                data-url="{{ route('finance.bank-accounts.destroy', $account->id) }}" 
                                                title="Hapus">
                                            <i class="bi bi-trash3-fill text-danger"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $account->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <form action="{{ route('finance.bank-accounts.update', $account->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold">Edit Akun Bank</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label class="form-label fw-medium">Nama Bank <span class="text-danger">*</span></label>
                                                        <input type="text" name="bank_name" class="form-control rounded-3" value="{{ $account->bank_name }}" required placeholder="Contoh: Bank BRI">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-medium text-muted small mb-1">DATA REKENING</label>
                                                        <div class="p-3 bg-light rounded-3">
                                                            <div class="mb-3">
                                                                <label class="form-label small">Nomor Rekening</label>
                                                                <input type="number" name="account_number" class="form-control rounded-3 border-0 bg-white" value="{{ $account->account_number }}" required>
                                                            </div>
                                                            <div class="mb-0">
                                                                <label class="form-label small">Atas Nama (Pemilik)</label>
                                                                <input type="text" name="account_holder" class="form-control rounded-3 border-0 bg-white" value="{{ $account->account_holder }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
<input type="hidden" name="balance" value="{{ $account->balance }}">
                                                    <div class="col-12">
                                                        <label class="form-label fw-medium">Keterangan / Catatan</label>
                                                        <textarea name="description" class="form-control rounded-3" rows="2">{{ $account->description }}</textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-check form-switch p-3 border rounded-3 bg-light mb-3">
                                                            <input class="form-check-input ms-0 me-3" type="checkbox" name="is_active" value="1" id="isActive{{ $account->id }}" {{ $account->is_active ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-medium" for="isActive{{ $account->id }}">Status Akun Aktif</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold text-primary"><i class="bi bi-shield-lock me-1"></i>PIN Keamanan <span class="text-danger">*</span></label>
                                                        <input type="password" name="security_pin" class="form-control rounded-3 border-primary border-opacity-25" placeholder="Masukkan PIN Anda untuk konfirmasi" required maxlength="6">
                                                        <div class="form-text small text-muted">Diperlukan untuk memvalidasi perubahan data sensitif.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0 p-4">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                            <i class="bi bi-bank fs-1 text-muted opacity-50"></i>
                                        </div>
                                        <h5 class="text-dark fw-bold">Belum Ada Akun Bank</h5>
                                        <p class="text-muted">Mulai kelola keuangan dengan menambahkan rekening bank pertama Anda.</p>
                                        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createModal">
                                            Tambah Sekarang
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('finance.bank-accounts.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Tambah Akun Bank Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-medium">Nama Bank <span class="text-danger">*</span></label>
                            <input type="text" name="bank_name" class="form-control rounded-3" placeholder="Misal: BRI, Bank Mandiri, BSI" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Nomor Rekening <span class="text-danger">*</span></label>
                            <input type="number" name="account_number" class="form-control rounded-3" placeholder="Masukkan nomor rekening" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Atas Nama (Pemilik Buku Rekening) <span class="text-danger">*</span></label>
                            <input type="text" name="account_holder" class="form-control rounded-3" placeholder="Nama pemilik rekening sesuai sistem bank" required>
                        </div>
<input type="hidden" name="balance" value="0">
                        <div class="col-12 border-top pt-3 mt-2">
                            <label class="form-label fw-bold text-primary"><i class="bi bi-shield-lock me-1"></i>PIN Keamanan <span class="text-danger">*</span></label>
                            <input type="password" name="security_pin" class="form-control rounded-3 border-primary border-opacity-25" placeholder="Masukkan PIN Anda" required maxlength="6">
                            <div class="form-text small text-muted">PIN Keamanan wajib diisi untuk menambah akun baru.</div>
                        </div>
                        <input type="hidden" name="is_active" value="1">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 p-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Akun Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1) !important; }
    .bg-secondary-subtle { background-color: rgba(108, 117, 125, 0.1) !important; }
    .avatar-sm { flex-shrink: 0; }
    .btn-white { background: #fff; border: 1px solid #dee2e6; }
    .btn-white:hover { background: #f8f9fa; }
    
    #bankAccountsTable thead th {
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    
    #bankAccountsTable tr {
        transition: all 0.2s;
    }
    
    #bankAccountsTable tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.02) !important;
    }

    .form-control:focus, .input-group-text:focus {
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        border-color: #4361ee;
    }

    code {
        background: #f1f3f9;
        padding: 2px 6px;
        border-radius: 4px;
        color: #4b5a7b !important;
        font-family: 'Source Code Pro', monospace;
    }
</style>
@endpush

@push('scripts')
<script>
    // Search Functionality
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#bankAccountsTable tbody tr');
        
        rows.forEach(row => {
            if (row.textContent.toLowerCase().indexOf(value) > -1) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Copy to Clipboard Function
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                title: 'Tersalin!',
                text: 'Nomor rekening ' + text + ' telah disalin.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    }
    // Simplified Delete with PIN
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            
            Swal.fire({
                title: 'Konfirmasi Penghapusan',
                text: "Silakan masukkan PIN Keamanan Anda untuk menghapus akun bank ini.",
                icon: 'warning',
                input: 'password',
                inputAttributes: {
                    autocapitalize: 'off',
                    autocorrect: 'off',
                    placeholder: 'Masukkan PIN'
                },
                showCancelButton: true,
                confirmButtonText: 'Hapus Sekarang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33',
                showLoaderOnConfirm: true,
                preConfirm: (pin) => {
                    if (!pin) {
                        Swal.showValidationMessage('PIN wajib diisi');
                        return false;
                    }
                    
                    // Create dynamic form to submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);
                    
                    const pinField = document.createElement('input');
                    pinField.type = 'hidden';
                    pinField.name = 'security_pin';
                    pinField.value = pin;
                    form.appendChild(pinField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
