@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="m-0 fw-bold text-primary text-uppercase">
                            <i class="bi bi-person-video3 me-2"></i> {{ $title ?? 'Data Siswa' }}
                        </h5>
                        <small class="text-muted">{{ $isActiveYear ? 'Tahun Ajaran Aktif' : 'Tahun Ajaran Non-Aktif' }}</small>
                    </div>
                     <div class="d-flex gap-2">
                        @php $isAktifView = str_contains(request()->url(), 'alumni') === false && str_contains(request()->url(), 'withdrawn') === false; @endphp
                        @if(Auth::user()->role === 'administrator' && $isActiveYear && $isAktifView)
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle shadow-sm px-3" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical me-1"></i> Opsi
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('students.download-template') }}">
                                        <i class="bi bi-file-earmark-spreadsheet text-success me-2"></i> Download Template CSV
                                    </a>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item py-2" data-bs-toggle="modal" data-bs-target="#importModal">
                                        <i class="bi bi-file-earmark-arrow-up text-primary me-2"></i> Import Data CSV
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('students.export', request()->all()) }}">
                                        <i class="bi bi-cloud-download text-info me-2"></i> Export ke Excel
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Siswa
                        </a>
                        @elseif(Auth::user()->role === 'administrator' && !$isAktifView)
                            {{-- Show only Export for non-active views if needed, keeping UI clean --}}
                            <a class="btn btn-outline-info btn-sm px-3 shadow-sm" href="{{ route('students.export', request()->all()) }}">
                                <i class="bi bi-cloud-download me-1"></i> Export Excel
                            </a>
                        @endif
                    </div>
                </div>


                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {!! session('error') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(!$isActiveYear)
                        <div class="alert alert-warning py-2 mb-3">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Mode Lihat:</strong> Anda sedang melihat data tahun ajaran non-aktif. Penambahan atau perubahan status hanya dapat dilakukan pada <strong>Tahun Ajaran Aktif</strong>.
                        </div>
                    @endif

                    <div class="row mb-4 align-items-center">
                         <div class="col-lg-3">
                            @if(Auth::user()->role === 'administrator' && $isActiveYear)
                            <div class="dropdown">
                                <button type="button" class="btn btn-danger dropdown-toggle btn-sm shadow-sm px-3" data-bs-toggle="dropdown">
                                    <i class="bi bi-lightning-charge-fill me-1"></i> Aksi Massal
                                </button>
                                <div class="dropdown-menu shadow border-0">
                                    <a class="dropdown-item py-2 bulk-action" href="#" data-action="aktif"><i class="bi bi-person-check text-success me-2"></i> Aktifkan Kembali</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item py-2 bulk-action" href="#" data-action="lulus"><i class="bi bi-mortarboard text-primary me-2"></i> Set sebagai ALUMNI</a>
                                    <a class="dropdown-item bulk-action" href="#" data-action="pindah"><i class="bi bi-person-gear text-info me-2"></i> Set sebagai PINDAH</a>
                                    <a class="dropdown-item py-2 bulk-action" href="#" data-action="keluar"><i class="bi bi-person-x text-warning me-2"></i> Set sebagai KELUAR</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item py-2 bulk-action text-danger" href="#" data-action="hapus"><i class="bi bi-trash me-2"></i> HAPUS PERMANEN</a>
                                </div>
                            </div>
                            @endif
                         </div>
                         <div class="col-lg-9">
                               <form action="{{ request()->url() }}" method="GET" class="d-flex align-items-center flex-wrap gap-2 justify-content-end">
                                   {{-- Academic Year Filter --}}
                                   <div class="input-group input-group-sm modern-input-group" style="width: auto; min-width: 180px;">
                                       <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                       <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
                                           <option value="">Semua Tahun</option>
                                           @foreach ($academicYears as $year)
                                               <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                                   {{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '(Non-Aktif)' }}
                                               </option>
                                           @endforeach
                                       </select>
                                   </div>

                                   {{-- Unit Filter --}}
                                   <div class="input-group input-group-sm modern-input-group" style="width: auto; min-width: 140px;">
                                       <span class="input-group-text"><i class="bi bi-building"></i></span>
                                       <select name="unit_id" class="form-select" onchange="this.form.submit()">
                                           <option value="">Semua Unit</option>
                                           @foreach ($units as $unit)
                                               <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>
                                                   {{ $unit->name }}
                                               </option>
                                           @endforeach
                                       </select>
                                   </div>

                                   {{-- Class Filter --}}
                                   @if($classes->isNotEmpty())
                                   <div class="input-group input-group-sm modern-input-group" style="width: auto; min-width: 120px;">
                                       <span class="input-group-text"><i class="bi bi-door-open"></i></span>
                                       <select name="class_id" class="form-select" onchange="this.form.submit()">
                                           <option value="">Semua Kelas</option>
                                           @foreach ($classes as $class)
                                               <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                                   {{ $class->name }}
                                               </option>
                                           @endforeach
                                       </select>
                                   </div>
                                   @endif

                                   {{-- Per Page Filter --}}
                                   <div class="input-group input-group-sm modern-input-group" style="width: auto;">
                                       <span class="input-group-text"><i class="bi bi-list-ol"></i></span>
                                       <select name="per_page" class="form-select" onchange="this.form.submit()">
                                           <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                           <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                           <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                       </select>
                                   </div>

                                   {{-- Search --}}
                                   <div class="input-group input-group-sm modern-input-group" style="width: auto; min-width: 200px;">
                                       <span class="input-group-text"><i class="bi bi-search"></i></span>
                                       <input type="text" name="search" class="form-control" placeholder="Nama/NIS/NISN..." value="{{ request('search') }}">
                                       @if(request('search'))
                                            <a href="{{ request()->url() }}" class="btn btn-outline-secondary d-flex align-items-center">
                                                <i class="bi bi-x"></i>
                                            </a>
                                       @endif
                                       <button class="btn btn-primary" type="submit">Cari</button>
                                   </div>
                               </form>
                         </div>
                    </div>

                    <form action="{{ route('students.bulk-action') }}" method="POST" id="bulkActionForm">
                        @csrf
                        <input type="hidden" name="action" id="bulkActionInput">
                        
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    @if($isActiveYear)
                                    <th width="10px">
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    @endif
                                    <th width="10px">No.</th>
                                    <th>NAMA</th>
                                    <th>KELAS</th>
                                    <th>UNIT</th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    @if(str_contains(request()->url(), 'alumni') || str_contains(request()->url(), 'withdrawn'))
                                    <th>DOKUMEN</th>
                                    @endif
                                    @if($isActiveYear)
                                    <th class="text-center" width="100px">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $key => $student)
                                    <tr>
                                        @if($isActiveYear)
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $student->id }}" class="check-item">
                                        </td>
                                        @endif
                                        <td>{{ $students->firstItem() + $key }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($student->user->photo)
                                                    <img src="{{ file_exists(public_path('photos/thumb/' . $student->user->photo)) ? asset('photos/thumb/' . $student->user->photo) : asset('photos/' . $student->user->photo) }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; margin-right: 1rem;">
                                                @else
                                                     <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary" style="width: 40px; height: 40px; margin-right: 1rem;">
                                                         <i class="bi bi-person-fill text-white" style="font-size: 1.2rem;"></i>
                                                     </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold" style="font-size: 0.9rem; color: #333;">{{ $student->nama_lengkap }}</div>
                                                    <div>
                                                        <span class="badge {{ $student->jenis_kelamin == 'L' ? 'text-bg-primary' : 'text-bg-danger' }} small" style="font-size: 0.7rem;">{{ $student->jenis_kelamin }}</span>
                                                        @if($student->status == 'aktif')
                                                            <span class="badge text-bg-success small" style="font-size: 0.7rem;">Aktif</span>
                                                        @elseif($student->status == 'lulus')
                                                            <span class="badge text-bg-primary small" style="font-size: 0.7rem;">Alumni</span>
                                                        @elseif($student->status == 'pindah')
                                                            <span class="badge text-bg-info small" style="color: white; font-size: 0.7rem;">Pindah</span>
                                                        @elseif($student->status == 'keluar')
                                                            <span class="badge text-bg-warning small" style="font-size: 0.7rem;">Keluar</span>
                                                        @else
                                                            <span class="badge text-bg-secondary small" style="font-size: 0.7rem;">{{ ucfirst($student->status) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                @php
                                                    $history = $student->classes->sortByDesc(function($c) {
                                                        return $c->academicYear ? $c->academicYear->start_year : 0;
                                                    });
                                                @endphp

                                                @if(str_contains(request()->url(), 'alumni') || str_contains(request()->url(), 'withdrawn'))
                                                    {{-- Full History for Alumni/Withdrawn --}}
                                                    @forelse($history as $histClass)
                                                        <div class="small text-muted mb-1 pb-1 border-bottom d-flex justify-content-between align-items-center">
                                                            <span class="fw-bold">{{ $histClass->name }}</span>
                                                            <span class="badge text-bg-light border" style="font-size: 0.7rem;">{{ $histClass->academicYear ? $histClass->academicYear->name : '-' }}</span>
                                                        </div>
                                                    @empty
                                                        <span class="text-muted small">- Tidak ada histori -</span>
                                                    @endforelse
                                                @else
                                                    {{-- Current/Selected Year Class for Active Students --}}
                                                    @php
                                                        $displayClass = $student->classes->first();
                                                    @endphp
                                                    <span class="badge bg-light text-dark border small">{{ $displayClass ? $displayClass->name : '-' }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $student->unit ? $student->unit->name : '-' }}</td>
                                        <td>{{ $student->nis }}</td>
                                        <td>{{ $student->nisn }}</td>
                                        @if(str_contains(request()->url(), 'alumni') || str_contains(request()->url(), 'withdrawn'))
                                        <td>
                                            @if($student->withdrawal_proof)
                                                <a href="{{ asset('withdrawal_documents/' . $student->withdrawal_proof) }}" target="_blank" class="btn btn-xs btn-outline-info">
                                                    <i class="bi bi-file-earmark-text"></i> Lihat Dokumen
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        @endif
                                        @if($isActiveYear)
                                        <td class="text-center align-middle">
                                            <div class="d-flex flex-wrap justify-content-center gap-1" style="min-width: 100px;">
                                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                
                                                @if(Auth::user()->role === 'administrator')
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteStudent({{ $student->id }})" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    
                                                    @if($student->status == 'aktif')
                                                        <button type="button" class="btn btn-primary btn-sm" title="Luluskan" onclick="setStudentStatus({{ $student->id }}, 'lulus')">
                                                            <i class="bi bi-mortarboard"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-secondary btn-sm" title="Keluarkan" onclick="openWithdrawalModal({{ $student->id }}, '{{ $student->nama_lengkap }}')">
                                                            <i class="bi bi-person-x"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-success btn-sm" title="Aktifkan Kembali" onclick="setStudentStatus({{ $student->id }}, 'aktif')">
                                                            <i class="bi bi-person-check"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>

                    {{-- Form for Individual Delete --}}
                    <form id="deleteForm" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    {{-- Form for Individual Status Change --}}
                    <form id="statusForm" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="status" id="statusFormInput">
                    </form>

                    <script>
                        function setStudentStatus(id, status) {
                            if (status === 'keluar') {
                                // Handled by openWithdrawalModal
                                return;
                            }

                            let msg = 'Apakah Anda yakin ingin mengubah status siswa ini?';
                            if (status === 'lulus') msg = 'Luluskan siswa ini?';
                            if (status === 'aktif') msg = 'Aktifkan kembali siswa ini?';

                            if(confirm(msg)) {
                                var form = document.getElementById('statusForm');
                                form.action = "{{ url('students') }}/" + id + "/change-status";
                                document.getElementById('statusFormInput').value = status;
                                form.submit();
                            }
                        }

                        function openWithdrawalModal(id, name) {
                            document.getElementById('wd_student_name').innerText = name;
                            document.getElementById('wd_student_id').value = id;
                            var form = document.getElementById('withdrawalForm');
                            form.action = "{{ url('students') }}/" + id + "/change-status";
                            
                            var modal = new bootstrap.Modal(document.getElementById('withdrawalModal'));
                            modal.show();
                        }

                        function deleteStudent(id) {
                            if(confirm('Apakah Anda yakin ingin menghapus data siswa ini?')) {
                                var form = document.getElementById('deleteForm');
                                form.action = "{{ url('students') }}/" + id;
                                form.submit();
                            }
                        }

                        document.addEventListener('DOMContentLoaded', function() {
                            // Check All
                            const checkAll = document.getElementById('checkAll');
                            if(checkAll) {
                                checkAll.addEventListener('change', function() {
                                    const helpCheckboxes = document.querySelectorAll('.check-item');
                                    helpCheckboxes.forEach(cb => cb.checked = this.checked);
                                });
                            }
                            
                            // Bulk Action Links
                            const bulkActions = document.querySelectorAll('.bulk-action');
                            const bulkForm = document.getElementById('bulkActionForm');
                            const bulkInput = document.getElementById('bulkActionInput');
                            
                            bulkActions.forEach(link => {
                                link.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    const action = this.dataset.action;
                                    
                                    // Check if any selected
                                    const selected = document.querySelectorAll('.check-item:checked');
                                    if(selected.length === 0) {
                                        alert('Pilih siswa terlebih dahulu!');
                                        return;
                                    }
                                    
                                    let confirmMessage = 'Apakah Anda yakin ingin melakukan aksi massal ini (' + action.toUpperCase() + ')?';
                                    if(action === 'hapus') {
                                        confirmMessage = 'PERINGATAN: Data siswa yang dipilih akan DIHAPUS PERMANEN. Tindakan ini tidak dapat dibatalkan. Apakah Anda yakin ingin melanjutkan?';
                                    }
                                    
                                    if(confirm(confirmMessage)) {
                                        bulkInput.value = action;
                                        bulkForm.submit();
                                    }
                                });
                            });
                        });
                    </script>
                    
                    <div class="d-flex justify-content-end mt-3">
                        {!! $students->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if(Auth::user()->role === 'administrator')
<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Siswa (CSV)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-3" style="font-size: 0.85rem;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Petunjuk:</strong> Jika menggunakan Excel, pilih <strong>Save As</strong> lalu format <strong>CSV (Comma delimited) (*.csv)</strong>.
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label fw-bold">Pilih File CSV</label>
                        <input type="file" name="file" class="form-control" required accept=".csv">
                        <div class="form-text mt-2">
                            Kolom Wajib: <strong>nis, nisn, nama, unit_id, username, password</strong><br>
                            <small class="text-danger">* Gunakan ID Unit di bawah ini untuk kolom <code>unit_id</code>.</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Referensi Kode Unit:</label>
                        <table class="table table-sm table-bordered mt-1">
                            <thead>
                                <tr class="table-light">
                                    <th>ID</th>
                                    <th>Nama Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $u)
                                <tr>
                                    <td class="text-center"><strong>{{ $u->id }}</strong></td>
                                    <td>{{ $u->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Withdrawal Modal -->
<div class="modal fade" id="withdrawalModal" tabindex="-1" aria-labelledby="withdrawalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="withdrawalForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="status" value="keluar">
                <input type="hidden" id="wd_student_id">
                
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="withdrawalModalLabel"><i class="bi bi-person-x"></i> Form Pengeluaran Siswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan mengeluarkan siswa: <strong id="wd_student_name"></strong></p>
                    
                    <div class="mb-3">
                        <label for="withdrawal_proof" class="form-label fw-bold">Unggah Bukti Dokumen (Opsional)</label>
                        <input type="file" name="withdrawal_proof" class="form-control" id="withdrawal_proof" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text mt-2">
                            Format yang didukung: PDF, JPG, PNG (Maksimal 5MB).<br>
                            Dokumen ini akan menjadi bukti resmi pengeluaran siswa.
                        </div>
                    </div>
                    
                    <div class="alert alert-warning small">
                        <i class="bi bi-exclamation-triangle-fill"></i> Tindakan ini akan memindahkan siswa ke daftar <strong>Siswa Keluar</strong>.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-secondary">Keluarkan Siswa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
