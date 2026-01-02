@extends('layouts.app')

@section('title', 'Administrasi Guru')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-file-earmark-text"></i> Tugas Administrasi & Dokumen</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Judul Dokumen</th>
                            <th>Tahun Ajaran</th>
                            <th>Deadline</th>
                            <th>Status Pengumpulan</th>
                            <th>Upload / File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                            @php
                                $submission = $req->submissions->where('user_id', Auth::id())->first();
                                $status = $submission ? $submission->status : 'missing';
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $req->title }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $req->description }}</small>
                                </td>
                                <td>{{ $req->academicYear ? $req->academicYear->name : '-' }}</td>
                                    {{ $req->due_date ? $req->due_date->format('d M Y') : '-' }}
                                    @if($req->due_date)
                                        @php
                                            $daysLeft = now()->startOfDay()->diffInDays($req->due_date, false);
                                        @endphp
                                        
                                        @if($daysLeft > 0)
                                            <div class="small fw-bold text-primary mt-1">
                                                <i class="bi bi-hourglass-split"></i> Sisa {{ $daysLeft }} hari
                                            </div>
                                        @elseif($daysLeft == 0)
                                            <div class="small fw-bold text-warning text-dark mt-1">
                                                <i class="bi bi-exclamation-circle"></i> Hari ini terakhir!
                                            </div>
                                        @else
                                            <div class="small fw-bold text-danger mt-1">
                                                <i class="bi bi-exclamation-triangle"></i> Terlambat {{ abs($daysLeft) }} hari
                                            </div>
                                        @endif
                                    @endif
                                <td>
                                    @if($status == 'missing')
                                        <span class="badge bg-secondary">Belum Dikumpul</span>
                                    @elseif($status == 'pending')
                                        <span class="badge bg-warning text-dark">Menunggu Validasi</span>
                                    @elseif($status == 'validated')
                                        <span class="badge bg-info text-dark">Tervalidasi (Proses Approval)</span>
                                    @elseif($status == 'approved')
                                        <span class="badge bg-success">Diterima</span>
                                    @elseif($status == 'rejected')
                                        <span class="badge bg-danger">Ditolak / Revisi</span>
                                        @if($submission->feedback)
                                            <div class="small text-danger mt-1">Note: {{ $submission->feedback }}</div>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($status == 'approved' || $status == 'validated')
                                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-check-circle"></i> Lihat File
                                        </a>
                                        @if($status == 'validated')
                                            <div class="small text-muted mt-1">Dokumen sedang menunggu persetujuan Kepala Sekolah.</div>
                                        @endif
                                    @else
                                        @if($submission)
                                            <div class="mb-2">
                                                <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="small text-decoration-none">
                                                    Lihat File Terakhir ({{ $submission->submitted_at->format('d/m H:i') }})
                                                </a>
                                            </div>
                                        @endif
                                        
                                        @php
                                            // Strict check: if Due Date is BEFORE today (00:00:00), it is closed.
                                            // allow submission ON the due date.
                                            $isClosed = $req->due_date && $req->due_date < now()->startOfDay();
                                        @endphp

                                        @if($isClosed) 
                                            <div class="alert alert-secondary p-2 small mb-0 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-lock-fill me-2"></i> 
                                                <span>Batas Waktu Berakhir</span>
                                            </div>
                                        @else
                                            <form action="{{ route('teacher-docs.upload', $req->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="input-group input-group-sm">
                                                    <input type="file" name="file" class="form-control" required accept=".pdf,.doc,.docx,.xls,.xlsx,.zip">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="bi bi-upload"></i> {{ $submission ? 'Re-Upload' : 'Upload' }}
                                                    </button>
                                                </div>
                                                <small class="text-muted" style="font-size: 0.75rem;">Max 10MB (PDF, Office, ZIP)</small>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada tugas administrasi yang aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
