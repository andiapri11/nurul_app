@extends('layouts.app')

@section('title', 'Persetujuan Dokumen Guru')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Persetujuan Dokumen Guru</h3>
                <div class="text-end d-flex align-items-center gap-2">
                    <form action="{{ route('principal.documents') }}" method="GET" class="d-inline-block">
                        <select name="academic_year_id" class="form-select form-select-sm fw-bold border-primary text-primary" onchange="this.form.submit()" style="width: auto; min-width: 150px;">
                            <option value="">-- Tahun Ajaran --</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <span class="badge bg-info text-dark fs-6 py-2">
                        <i class="bi bi-building"></i> Unit: 
                        @foreach($units as $u)
                             {{ $u->name }}@if(!$loop->last), @endif
                        @endforeach
                    </span>
                </div>
            </div>
            
            <div class="alert alert-info">
                Menampilkan dokumen yang sudah divalidasi oleh Wakil Kurikulum dan menunggu persetujuan Anda.
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            
            <ul class="nav nav-tabs mb-3" id="docTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Overview & Progress</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                        Menunggu Persetujuan 
                        @if($submissions->count() > 0)
                            <span class="badge bg-danger ms-1">{{ $submissions->count() }}</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">Riwayat Persetujuan</button>
                </li>
            </ul>

            <div class="tab-content" id="docTabsContent">
                
                <!-- Tab 1: Overview -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="card">
                         <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">Daftar Permintaan Dokumen & Progress Unit</h3>
                            <a href="{{ route('principal.documents.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg"></i> Buat Permintaan Baru
                            </a>
                        </div>
                        <div class="card-body">
                            @if($requests->isEmpty())
                                <p class="text-center text-muted m-4">Belum ada permintaan dokumen yang aktif.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Judul / Deskripsi</th>
                                                <th>Deadline</th>
                                                <th>Statistik Pengumpulan (Unit Ini)</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($requests as $req)
                                                <tr>
                                                    <td>
                                                        <span class="fw-bold">{{ $req->title }}</span>
                                                        <br><small class="text-muted">{{ $req->academicYear->name ?? '-' }} ({{ ucfirst($req->semester) }})</small>
                                                        @if($req->target_subjects)
                                                            <br><span class="badge bg-light text-dark">Spesifik Mapel</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $req->due_date ? $req->due_date->format('d M Y') : '-' }}
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <small>Diterima / Proses: {{ $req->submission_count }}</small>
                                                            <small class="fw-bold text-success">Approved: {{ $req->approved_count }}</small>
                                                        </div>
                                                        <div class="progress" style="height: 6px;">
                                                             <div class="progress-bar bg-success" style="width: {{ $req->submission_count > 0 ? 100 : 0 }}%"></div>
                                                        </div>
                                                        <small class="text-muted" style="font-size: 0.75rem;">Waiting Review: {{ $req->validated_count - $req->approved_count }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $req->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $req->is_active ? 'Aktif' : 'Closed' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('principal.documents.show-request', $req->id) }}" class="btn btn-sm btn-info text-white me-1" title="Lihat Detail & Pengumpulan">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        @if($req->created_by == Auth::id())
                                                            <a href="{{ route('principal.documents.edit-request', $req->id) }}" class="btn btn-sm btn-warning me-1" title="Edit Permintaan">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <form action="{{ route('principal.documents.destroy-request', $req->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permintaan dokumen ini? Semua data pengumpulan terkait akan ikut terhapus (jika ada).');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Permintaan">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Pending Approval -->
                <div class="tab-pane fade" id="pending" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Dokumen Menunggu Persetujuan</h3>
                        </div>
                        <div class="card-body">
                            @if($submissions->isEmpty())
                                <p class="text-center text-muted my-5">Tidak ada dokumen yang perlu disetujui saat ini.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tanggal Submit</th>
                                                <th>Guru</th>
                                                <th>Judul Dokumen</th>
                                                <th>Divalidasi Oleh</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($submissions as $sub)
                                                <tr>
                                                    <td>{{ $sub->submitted_at->format('d M Y H:i') }}</td>
                                                    <td>
                                                        <div class="fw-bold">{{ $sub->user->name }}</div>
                                                        <small class="text-muted">{{ $sub->request->academicYear->name ?? '-' }} - {{ ucfirst($sub->request->semester) }}</small>
                                                    </td>
                                                    <td>
                                                        {{ $sub->request->title }}
                                                        @if($sub->request->due_date)
                                                            <br><small class="text-danger">Deadline: {{ $sub->request->due_date->format('d M') }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $sub->validator->name ?? 'Wakasek Kurikulum' }}
                                                        <br><small class="text-muted">{{ $sub->validated_at ? $sub->validated_at->format('d M H:i') : '-' }}</small>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('principal.documents.review', $sub->id) }}" class="btn btn-primary btn-sm">
                                                            <i class="bi bi-search"></i> Review & Setujui
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Tab 3: History & Cancellation -->
                <div class="tab-pane fade" id="history" role="tabpanel">
                    <div class="card">
                         <div class="card-header">
                            <h3 class="card-title">Riwayat Persetujuan (Terakhir 50)</h3>
                        </div>
                        <div class="card-body">
                             @if($historySubmissions->isEmpty())
                                <p class="text-center text-muted my-5">Belum ada riwayat persetujuan.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tanggal Proses</th>
                                                <th>Guru</th>
                                                <th>Judul Dokumen</th>
                                                <th>Status Akhir</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($historySubmissions as $sub)
                                                <tr>
                                                    <td>{{ $sub->approved_at ? $sub->approved_at->format('d M Y H:i') : '-' }}</td>
                                                    <td>
                                                        <div class="fw-bold">{{ $sub->user->name }}</div>
                                                    </td>
                                                    <td>
                                                        {{ $sub->request->title }}
                                                        <br>
                                                        @if($sub->file_path)
                                                            <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="small text-decoration-none">
                                                                <i class="bi bi-file-earmark-text"></i> Lihat File
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($sub->status == 'approved')
                                                            <span class="badge bg-success">Disetujui</span>
                                                        @else
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($sub->status == 'approved')
                                                            <form action="{{ route('principal.documents.review', $sub->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan approval ini? Status akan kembali menjadi Validated.');" style="display:inline-block;">
                                                                @csrf
                                                                <input type="hidden" name="status" value="validated">
                                                                <input type="hidden" name="feedback" value="Approval dibatalkan oleh Kepala Sekolah">
                                                                <button type="submit" class="btn btn-warning btn-sm">
                                                                    <i class="bi bi-arrow-counterclockwise"></i> Batalkan
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button class="btn btn-secondary btn-sm" disabled>Info</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</div>
@endsection
