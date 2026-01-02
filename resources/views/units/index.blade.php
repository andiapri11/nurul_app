@extends('layouts.app')

@section('title', 'Manajemen Unit Sekolah')

@push('styles')
<style>
    :root {
        --unit-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        --premium-shadow: 0 10px 30px rgba(99, 102, 241, 0.15);
    }

    .welcome-banner {
        background: var(--unit-gradient);
        border-radius: 24px;
        padding: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: var(--premium-shadow);
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        filter: blur(50px);
        pointer-events: none;
    }
    .welcome-icon {
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        backdrop-filter: blur(10px);
        margin-right: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .table-premium {
        background: white;
        border-radius: 20px;
        overflow: hidden;
    }
    .table-premium thead th {
        background-color: #f8fafc;
        padding: 18px 20px;
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1px;
        color: #64748b;
        border: none;
    }
    .table-premium tbody td {
        padding: 18px 20px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        font-size: 0.95rem;
    }
    
    .btn-premium {
        border-radius: 50px;
        padding: 10px 24px;
        font-weight: 700;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }
    .btn-premium-primary {
        background: var(--unit-gradient);
        border: none;
        color: white;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }
    .btn-premium-primary:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%);
        transform: scale(1.02);
        color: white;
    }

    .unit-card {
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
    }
    .unit-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.05);
        border-color: #6366f1;
    }

    .modal-content {
        border-radius: 24px;
        border: none;
    }
    .form-control-premium {
        border-radius: 12px;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-lg-4 mt-3">
    <!-- Premium Header -->
    <div class="welcome-banner">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="d-flex align-items-center">
                    <div class="welcome-icon shadow-sm">
                        <i class="bi bi-building text-white"></i>
                    </div>
                    <div>
                        <h2 class="fw-extrabold mb-1">Unit Sekolah</h2>
                        <p class="mb-0 text-white-50">Kelola daftar jenjang pendidikan dan unit operasional Yayasan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 d-flex justify-content-md-end mt-4 mt-md-0">
                <button type="button" class="btn btn-premium btn-premium-primary" data-bs-toggle="modal" data-bs-target="#addUnitModal">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Unit Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Units Table -->
    <div class="table-premium shadow-sm mb-5">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" width="80px">No</th>
                        <th>Nama Unit Sekolah</th>
                        <th class="text-center" width="150px">Kode</th>
                        <th class="pe-4 text-end" width="200px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($units as $key => $unit)
                        <tr class="unit-row">
                            <td class="ps-4 fw-bold text-muted">{{ $key + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-mortarboard-fill"></i>
                                    </div>
                                    <div class="fw-bold">{{ $unit->name }}</div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark px-3 py-2 rounded-pill border">{{ $unit->code ?? '-' }}</span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-light rounded-pill px-3 shadow-none border btn-edit" 
                                            data-id="{{ $unit->id }}" 
                                            data-name="{{ $unit->name }}"
                                            data-code="{{ $unit->code }}">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </button>
                                    <form action="{{ route('units.destroy', $unit->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-light rounded-pill px-3 shadow-none border btn-delete">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Belum ada data unit sekolah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add Unit -->
<div class="modal fade" id="addUnitModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-extrabold">Tambah Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('units.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Unit Sekolah</label>
                        <input type="text" name="name" class="form-control form-control-premium" placeholder="Contoh: SMA Nurul Ilmi" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn fw-bold text-muted me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-premium btn-premium-primary px-4">Simpan Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Unit -->
<div class="modal fade" id="editUnitModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-extrabold">Edit Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Unit Sekolah</label>
                        <input type="text" name="name" id="edit_name" class="form-control form-control-premium" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn fw-bold text-muted me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-premium btn-premium-primary px-4">Update Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle Edit Button
        $('.btn-edit').click(function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            
            $('#edit_name').val(name);
            $('#editForm').attr('action', '/units/' + id);
            $('#editUnitModal').modal('show');
        });

        // Delete Confirmation
        $('.btn-delete').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Unit?',
                text: "Menghapus unit dapat berdampak pada data siswa dan pegawai yang terkait.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger px-4 mx-2 rounded-pill',
                    cancelButton: 'btn btn-light px-4 mx-2 rounded-pill border'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
