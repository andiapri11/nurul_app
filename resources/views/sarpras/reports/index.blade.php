@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Laporan Kerusakan & Perbaikan</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <form action="{{ route('sarpras.reports.index') }}" method="GET" class="d-flex flex-wrap gap-2">
                        <select name="unit_id" class="form-select form-select-sm text-dark fw-bold w-auto" onchange="this.form.submit()">
                            @if($units->count() > 1)
                                <option value="">Semua Unit</option>
                            @endif
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        
                        <select name="academic_year_id" class="form-select form-select-sm text-dark fw-bold w-auto" onchange="this.form.submit()">
                            <option value="">Semua Tahun</option>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ request('academic_year_id') == $ay->id ? 'selected' : '' }}>
                                    {{ $ay->name }} {{ $ay->status == 'active' ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>

                        <select name="status" class="form-select form-select-sm text-dark fw-bold w-auto">
                            <option value="">Semua Status Laporan</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Processed" {{ request('status') == 'Processed' ? 'selected' : '' }}>Proses</option>
                            <option value="Fixed" {{ request('status') == 'Fixed' ? 'selected' : '' }}>Selesai</option>
                        </select>

                        <div class="btn-group">
                            <button type="submit" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('sarpras.reports.index') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4 mt-3 mt-lg-0">
                    <div class="d-flex justify-content-lg-end gap-2 flex-nowrap">
                        <button type="button" class="btn btn-sm btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createReportManual">
                            <i class="bi bi-plus-circle me-1"></i> <span>Tambah Laporan</span>
                        </button>
                        <a href="{{ route('sarpras.reports.print', request()->query()) }}" target="_blank" class="btn btn-sm btn-success d-flex align-items-center">
                            <i class="bi bi-printer me-1"></i> <span>Cetak Laporan</span>
                        </a>
                    </div>
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
                            <th>Tipe</th>
                            <th>Pelapor</th>
                            <th>Masalah</th>
                            <th>Bukti</th>
                            <th>Status/Approval</th>
                            <th width="100" class="text-center">Aksi</th>
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
                            <td>
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
                            <td>
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
                            <td>
                                @php
                                    $statuses = [
                                        'Pending' => 'secondary text-white',
                                        'Processed' => 'info text-white',
                                        'Fixed' => 'success text-white',
                                        'Rejected' => 'danger text-white',
                                    ];
                                    $labels = [
                                        'Pending' => 'Menunggu',
                                        'Processed' => 'Diproses',
                                        'Fixed' => 'Selesai',
                                        'Rejected' => 'Ditolak',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statuses[$report->status] ?? 'secondary' }}">
                                    {{ $labels[$report->status] ?? $report->status }}
                                </span>
                                
                                <div class="mt-2 small">
                                    <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                        <span>Val. KS:</span>
                                        @if($report->principal_approval_status === 'Approved')
                                            <span class="text-success fw-bold"><i class="bi bi-check-circle"></i> VALID</span>
                                        @elseif($report->principal_approval_status === 'Rejected')
                                            <span class="text-danger fw-bold"><i class="bi bi-x-circle"></i> NO</span>
                                        @else
                                            <span class="text-warning">WAIT</span>
                                        @endif
                                    </div>
                                    @if($report->principal_note)
                                        <div class="text-muted fst-italic mb-2" style="font-size: 0.75rem; border-left: 2px solid #ccc; padding-left: 4px;">
                                            Note KS: "{{ $report->principal_note }}"
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between">
                                        <span>Appr. Dir:</span>
                                        @if($report->director_status === 'Approved')
                                            <span class="text-success fw-bold"><i class="bi bi-check-circle"></i> OK</span>
                                        @elseif($report->director_status === 'Rejected')
                                            <span class="text-danger fw-bold"><i class="bi bi-x-circle"></i> NO</span>
                                        @else
                                            <span class="text-warning">WAIT</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateReport{{ $report->id }}" title="Edit / Update">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    
                                    @if(Auth::user()->role === 'administrator')
                                        <form action="{{ route('sarpras.reports.destroy', $report->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus laporan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Laporan">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Update Modal -->
                        <div class="modal fade" id="updateReport{{ $report->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <form action="{{ route('sarpras.reports.update-status', $report->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-content text-start text-dark">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Edit & Update Laporan #{{ $report->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 border-end">
                                                    <h6><i class="bi bi-pencil-square"></i> Data Laporan</h6>
                                                    <div class="mb-2">
                                                        <label class="form-label small">Tipe Laporan</label>
                                                        <select name="type" class="form-select form-select-sm" {{ $report->principal_approval_status === 'Approved' ? 'disabled' : '' }}>
                                                            <option value="Damaged" {{ $report->type == 'Damaged' ? 'selected' : '' }}>Barang Rusak</option>
                                                            <option value="Lost" {{ $report->type == 'Lost' ? 'selected' : '' }}>Barang Hilang</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label small">Deskripsi Masalah</label>
                                                        <textarea name="description" class="form-control form-control-sm" rows="2" {{ $report->principal_approval_status === 'Approved' || $report->status === 'Fixed' ? 'disabled' : '' }}>{{ $report->description }}</textarea>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label small">Saran Tindak Lanjut</label>
                                                        <select name="follow_up_action" class="form-select form-select-sm" {{ $report->principal_approval_status === 'Approved' || $report->status === 'Fixed' ? 'disabled' : '' }}>
                                                            <option value="Repair" {{ $report->follow_up_action == 'Repair' ? 'selected' : '' }}>Perbaikan</option>
                                                            <option value="Replacement" {{ $report->follow_up_action == 'Replacement' ? 'selected' : '' }}>Pengajuan Pembelian Baru</option>
                                                            <option value="Disposal" {{ $report->follow_up_action == 'Disposal' ? 'selected' : '' }}>Pemusnahan (Hapus dari Daftar)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label small">Alasan Saran</label>
                                                        <textarea name="follow_up_description" class="form-control form-control-sm" rows="2" {{ $report->principal_approval_status === 'Approved' || $report->status === 'Fixed' ? 'disabled' : '' }}>{{ $report->follow_up_description }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6><i class="bi bi-gear"></i> Status & Proses</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Update Status</label>
                                                        <select name="status" class="form-select" required {{ $report->status === 'Fixed' ? 'disabled' : '' }} id="statusSelect{{ $report->id }}">
                                                            <option value="Pending" {{ $report->status == 'Pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                                                            <option value="Processed" {{ $report->status == 'Processed' ? 'selected' : '' }} {{ $report->director_status !== 'Approved' ? 'disabled' : '' }}>Diproses (Sedang Diperbaiki)</option>
                                                            <option value="Fixed" {{ $report->status == 'Fixed' ? 'selected' : '' }} {{ $report->director_status !== 'Approved' ? 'disabled' : '' }}>Selesai (Sudah Diperbaiki)</option>
                                                        </select>
                                                    </div>

                                                    @if($report->status !== 'Fixed' && $report->director_status === 'Approved')
                                                    <div class="mb-3 p-3 bg-light border rounded shadow-sm">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="checkDone{{ $report->id }}" 
                                                                onchange="document.getElementById('statusSelect{{ $report->id }}').value = this.checked ? 'Fixed' : '{{ $report->status }}'">
                                                            <label class="form-check-label fw-bold text-success" for="checkDone{{ $report->id }}">
                                                                <i class="bi bi-check-circle-fill"></i> Tandai Telah Selesai
                                                            </label>
                                                        </div>
                                                        <small class="text-muted d-block mt-1">Mencentang ini akan mengubah kondisi barang kembali menjadi <strong>Baik</strong>.</small>
                                                    </div>
                                                    @endif

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Catatan Admin / Lokasi Sekarang</label>
                                                        <textarea name="admin_note" class="form-control" rows="4" placeholder="Contoh: Barang sedang di tukang servis..." {{ $report->status === 'Fixed' ? 'disabled' : '' }}>{{ $report->admin_note }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            @if($report->status !== 'Fixed')
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            @else
                                                <div class="text-success fw-bold"><i class="bi bi-lock-fill"></i> Laporan ini sudah selesai dan terkunci.</div>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">Belum ada laporan kerusakan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $reports->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Laporan Manual -->
<div class="modal fade" id="createReportManual" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold"><i class="bi bi-megaphone me-2"></i>Buat Laporan / Usulan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="manualReportForm">
                @csrf
                <div class="modal-body text-start mt-2">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Barang</label>
                        <input list="inventoryList" name="code" id="item_input" class="form-control" placeholder="Ketik nama atau kode barang..." required>
                        <datalist id="inventoryList">
                            @foreach($inventories as $item)
                                <option value="{{ $item->code }}">{{ $item->name }} ({{ $item->code }})</option>
                            @endforeach
                        </datalist>
                        <div id="item_preview" class="mt-2 p-2 bg-light rounded border d-none">
                            <small class="text-muted d-block">Barang Terpilih:</small>
                            <span id="preview_name" class="fw-bold text-primary"></span>
                        </div>
                    </div>

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
            </form>
        </div>
    </div>
</div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Handle manual report submission
    document.getElementById('manualReportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Kirim Laporan?',
            text: "Laporan akan diteruskan ke Kepala Sekolah.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                const formData = new FormData(this);

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
    });

    // Optional: Item selection preview
    document.getElementById('item_input').addEventListener('change', function() {
        const val = this.value;
        const opts = document.getElementById('inventoryList').childNodes;
        let found = false;
        for (let i = 0; i < opts.length; i++) {
            if (opts[i].value === val) {
                document.getElementById('preview_name').innerText = opts[i].innerText;
                document.getElementById('item_preview').classList.remove('d-none');
                found = true;
                break;
            }
        }
        if (!found) document.getElementById('item_preview').classList.add('d-none');
    });
</script>
@endpush
@endsection
