@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-primary fw-bold"><i class="bi bi-people-fill me-2"></i> {{ $title ?? 'User Siswa' }}</h3>
                </div>

                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    @if(!$isActiveYear)
                        <div class="alert alert-warning py-2 mb-3">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Mode Lihat:</strong> Anda sedang melihat data tahun ajaran non-aktif. Penambahan atau perubahan status masal hanya dapat dilakukan pada <strong>Tahun Ajaran Aktif</strong>.
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-3 align-items-end flex-wrap gap-3">
                         <div class="d-flex align-items-center flex-wrap gap-2">
                            <form action="{{ request()->url() }}" method="GET" class="d-flex align-items-center flex-wrap gap-2">
                                <div class="input-group input-group-sm" style="width: auto;">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text bg-light text-muted border-right-0">
                                             <i class="bi bi-calendar3"></i>
                                         </span>
                                     </div>
                                     <select name="academic_year_id" class="form-control" onchange="this.form.submit()">
                                         <option value="">Thn</option>
                                         @foreach($academicYears as $year)
                                             <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                                 {{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '(Non-Aktif)' }}
                                             </option>
                                         @endforeach
                                     </select>
                                 </div>

                                 <div class="input-group input-group-sm" style="width: auto;">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text bg-light text-muted border-right-0">
                                              <i class="bi bi-building"></i>
                                         </span>
                                     </div>
                                     <select name="unit_id" class="form-control" onchange="this.form.submit()">
                                         <option value="">Unit</option>
                                         @foreach($units as $unit)
                                             <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                         @endforeach
                                     </select>
                                 </div>

                                 <div class="input-group input-group-sm" style="width: auto;">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text bg-light text-muted border-right-0">
                                             <i class="bi bi-info-circle"></i>
                                         </span>
                                     </div>
                                     <select name="status" class="form-control" onchange="this.form.submit()">
                                         <option value="">Status</option>
                                         <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                         <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Alumni</option>
                                         <option value="keluar" {{ request('status') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                                         <option value="pindah" {{ request('status') == 'pindah' ? 'selected' : '' }}>Pindah</option>
                                     </select>
                                 </div>

                                 @if($classes->isNotEmpty())
                                 <div class="input-group input-group-sm" style="width: auto;">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text bg-light text-muted border-right-0">
                                             <i class="bi bi-door-open"></i>
                                         </span>
                                     </div>
                                     <select name="class_id" class="form-control" onchange="this.form.submit()">
                                         <option value="">Kls</option>
                                         @foreach($classes as $class)
                                             <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                                 @endif

                                <div class="input-group input-group-sm" style="width: auto;">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-top-left-radius: 20px; border-bottom-left-radius: 20px;">
                                            <i class="bi bi-list-ol"></i>
                                        </span>
                                    </div>
                                    <select name="per_page" class="form-control border-left-0" style="border-radius: 0;" onchange="this.form.submit()">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>

                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0 border-left-0">
                                            <i class="bi bi-funnel"></i>
                                        </span>
                                    </div>
                                    <select name="sort" class="form-control border-left-0" style="border-radius: 0; max-width: 100px;" onchange="this.form.submit()">
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>New</option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>A-Z</option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Z-A</option>
                                    </select>
                                    <input type="text" name="search" class="form-control border-right-0" placeholder="Cari..." value="{{ request('search') }}" style="border-radius: 0;">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default border-left-0 bg-white" style="border-top-right-radius: 20px; border-bottom-right-radius: 20px;">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            @if($isActiveYear)
                            <div class="btn-group ml-md-2">
                                <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear-fill me-1"></i> Aksi Massal
                                </button>
                                <div class="dropdown-menu">
                                    {{-- <a class="dropdown-item bulk-action" href="#" data-action="aktif"><i class="bi bi-person-check me-2"></i> Set sebagai AKTIF</a> --}}
                                    {{-- <a class="dropdown-item bulk-action" href="#" data-action="lulus"><i class="bi bi-mortarboard me-2"></i> Set sebagai ALUMNI</a>
                                    <a class="dropdown-item bulk-action" href="#" data-action="pindah"><i class="bi bi-person-gear me-2"></i> Set sebagai PINDAH</a>
                                    <a class="dropdown-item bulk-action" href="#" data-action="keluar"><i class="bi bi-person-x me-2"></i> Set sebagai KELUAR</a> --}}
                                    {{-- <div class="dropdown-divider"></div> --}}
                                    <a class="dropdown-item bulk-action text-danger" href="#" data-action="hapus"><i class="bi bi-trash me-2"></i> HAPUS</a>
                                </div>
                            </div>
                            @endif
                         </div>
                          </div>
                     </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    @if($isActiveYear)
                                    <th width="10px">
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    @endif
                                    <th width="10px">No.</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Akun</th>
                                    <th class="text-center">Reset Login</th>
                                    <th class="text-center">Status/Aksi</th>
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
                                        <td>{{ $student->nis }}</td>
                                        <td>{{ $student->nama_lengkap }}</td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                @php
                                                    $history = $student->classes->filter(function($c) use ($selectedYearId) {
                                                        return !$selectedYearId || $c->academic_year_id == $selectedYearId;
                                                    });
                                                @endphp
                                                @forelse($history as $c)
                                                    <span class="badge text-bg-light border small">{{ $c->name }}</span>
                                                @empty
                                                    <span class="text-muted small">-</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td>{{ $student->user?->username ?? '-' }}</td>
                                        <td>
                                            @if($student->user?->plain_password)
                                                {{ $student->user->plain_password }}
                                            @else
                                                <span class="text-muted small">Encrypted</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($student->user && $student->user->locked_at)
                                                <span class="badge" style="background-color: white; color: red; border: 1px solid red;">Terkunci ({{ $student->user->login_attempts }}x)</span>
                                            @else
                                                <span class="badge badge-success" style="color: black;">Aman</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($student->user && $student->user->locked_at)
                                                 <form action="{{ route('admin-students.reset-password', $student->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Akun Terkunci! Klik untuk membuka." onclick="return confirm('Buka kunci akun ini?')">
                                                        <i class="bi bi-shield-lock-fill"></i> Buka Kunci
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="Akun Normal / Tidak Terkunci">
                                                    <i class="bi bi-shield-check"></i> Aman
                                                </button>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                {{-- 1. Status Siswa --}}
                                                @php
                                                    $statusColor = match($student->status) {
                                                        'aktif' => 'success',
                                                        'lulus' => 'primary',
                                                        'keluar' => 'warning',
                                                        'pindah' => 'info',
                                                        default => 'secondary'
                                                    };
                                                    $statusLabel = $student->status == 'lulus' ? 'Alumni' : ucfirst($student->status);
                                                @endphp
                                                <span class="badge text-bg-{{ $statusColor }} mb-2">{{ $statusLabel }}</span>
                                                
                                                {{-- 2. User Login Status Toggle --}}
                                                @if($student->user)
                                                    <form action="{{ route('admin-students.toggle-status', $student->id) }}" method="POST">
                                                        @csrf
                                                        @if(($student->user->status ?? 'aktif') === 'aktif')
                                                            <button type="submit" class="btn btn-outline-success btn-sm w-100" onclick="return confirm('Nonaktifkan login user ini?')" title="User Saat Ini: Aktif. Klik untuk Nonaktifkan.">
                                                                <i class="bi bi-person-check-fill me-1"></i> User Aktif
                                                            </button>
                                                        @else
                                                            <button type="submit" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Aktifkan login user ini?')" title="User Saat Ini: Non-Aktif. Klik untuk Aktifkan.">
                                                                <i class="bi bi-person-x-fill me-1"></i> Non-Aktif
                                                            </button>
                                                        @endif
                                                    </form>
                                                @else
                                                    <span class="badge bg-secondary">No User</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Global Hidden Bulk Action Form --}}
                    <form action="{{ route('admin-students.bulk-action') }}" method="POST" id="bulkActionGlobalForm" style="display:none;">
                        @csrf
                        <input type="hidden" name="action" id="bulkActionTypeInput">
                        <div id="bulkSelectedIds"></div>
                    </form>
                    
                    <div class="d-flex justify-content-end mt-3">
                        {!! $students->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
    const bulkForm = document.getElementById('bulkActionGlobalForm');
    const bulkTypeInput = document.getElementById('bulkActionTypeInput');
    const bulkIdsContainer = document.getElementById('bulkSelectedIds');
    
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
            
            let actionText = action.toUpperCase();
            if(action === 'lulus') actionText = 'ALUMNI';
            
            let confirmMessage = 'Apakah Anda yakin ingin melakukan aksi massal ini (' + actionText + ')?';
            if(action === 'hapus') {
                confirmMessage = 'PERINGATAN: Data siswa yang dipilih akan DIHAPUS PERMANEN. Tindakan ini tidak dapat dibatalkan. Apakah Anda yakin ingin melanjutkan?';
            }
            
            if(confirm(confirmMessage)) {
                // Clear and Fill IDs
                bulkIdsContainer.innerHTML = '';
                selected.forEach(input => {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'ids[]';
                    hidden.value = input.value;
                    bulkIdsContainer.appendChild(hidden);
                });
                
                bulkTypeInput.value = action;
                bulkForm.submit();
            }
        });
    });
});
</script>

@endsection
