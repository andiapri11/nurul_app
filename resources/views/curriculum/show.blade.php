@extends('layouts.app')

@section('title', 'Detail Permintaan Dokumen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Permintaan</h3>
                </div>
                <div class="card-body">
                    <h5>{{ $documentRequest->title }}</h5>
                    <p class="text-muted">{{ $documentRequest->description ?? 'Tidak ada deskripsi' }}</p>
                    <hr>
                    <ul class="list-unstyled">
                        <li><strong>Tahun Ajaran:</strong> {{ $documentRequest->academicYear->name ?? '-' }} ({{ ucfirst($documentRequest->semester) }})</li>
                        <li><strong>Deadline:</strong> {{ $documentRequest->due_date ? $documentRequest->due_date->format('d M Y') : '-' }}</li>
                        
                        <li class="mt-2"><strong>Target Unit:</strong>
                            @if(!empty($documentRequest->target_units))
                                @foreach($documentRequest->target_units as $uid)
                                    @php $u = \App\Models\Unit::find($uid); @endphp
                                    <span class="badge bg-primary">{{ $u ? $u->name : $uid }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary">Semua / Global</span>
                            @endif
                        </li>

                        <li class="mt-2"><strong>Target Mapel:</strong>
                            @if(!empty($documentRequest->target_subjects))
                                @foreach($documentRequest->target_subjects as $sid)
                                    @php $s = \App\Models\Subject::find($sid); @endphp
                                    <span class="badge bg-info text-dark mb-1">{{ $s ? $s->name : $sid }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary">Semua</span>
                            @endif
                        </li>

                        <li class="mt-2"><strong>Target Grade:</strong>
                            @if(!empty($documentRequest->target_grades))
                                @foreach($documentRequest->target_grades as $g)
                                    <span class="badge bg-secondary">{{ $g }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary">Semua</span>
                            @endif
                        </li>
                        
                        <li class="mt-2"><strong>Status:</strong> 
                            <span class="badge {{ $documentRequest->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $documentRequest->is_active ? 'Aktif' : 'Arsip' }}
                            </span>
                        </li>
                    </ul>
                    <a href="{{ route('curriculum.edit', $documentRequest->id) }}" class="btn btn-warning btn-block w-100">Edit Permintaan</a>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Statistik Pengumpulan</h3>
                </div>
                <div class="card-body">
                    @php
                        $total = $teachers->count();
                        // Exclude rejected submissions from the count
                        $submitted = $documentRequest->submissions->where('status', '!=', 'rejected')->count();
                        $percent = $total > 0 ? ($submitted / $total) * 100 : 0;
                    @endphp
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100.0">
                            {{ round($percent) }}%
                        </div>
                    </div>
                    <p class="text-center">{{ $submitted }} dari {{ $total }} Guru sudah mengumpulkan.</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Pengumpulan Guru</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table id="teachersTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Guru</th>
                                    <th>Status</th>
                                    <th>Waktu Upload</th>
                                    <th>File</th>
                                    <th>Feedback / Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teachers as $teacher)
                                    @php
                                        $submission = $documentRequest->submissions->where('user_id', $teacher->id)->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $teacher->name }}</td>
                                        <td>
                                            @if($submission)
                                                @if($submission->status == 'approved')
                                                    <span class="badge bg-success">Disetujui Kepsek</span>
                                                @elseif($submission->status == 'validated')
                                                    <span class="badge bg-info text-dark">Tervalidasi (Menunggu Kepsek)</span>
                                                @elseif($submission->status == 'rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Menunggu Validasi</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Belum Mengumpulkan</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $submission ? $submission->submitted_at->format('d M H:i') : '-' }}
                                        </td>
                                        <td>
                                            @if($submission && $submission->file_path)
                                                <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-file-earmark-text"></i> Lihat
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($submission)
                                                @if($submission->status == 'approved')
                                                    <button class="btn btn-sm btn-success" disabled>
                                                        <i class="bi bi-check-all"></i> Disetujui
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $submission->id }}">
                                                        Validasi
                                                    </button>
                                                @endif

                                                <!-- Modal Review -->
                                                <div class="modal fade" id="reviewModal{{ $submission->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form action="{{ route('curriculum.submission.update-status', $submission->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Validasi Dokumen : {{ $teacher->name }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>File: <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank">Download/View</a></p>
                                                                    <div class="mb-3">
                                                                        <label>Status</label>
                                                                        <select name="status" class="form-control">
                                                                            <option value="validated" {{ $submission->status == 'validated' ? 'selected' : '' }}>Validasi (Teruskan ke Kepala Sekolah)</option>
                                                                            <option value="rejected" {{ $submission->status == 'rejected' ? 'selected' : '' }}>Tolak (Perbaiki)</option>
                                                                        </select>
                                                                        <small class="text-muted">Jika divalidasi, dokumen akan masuk ke dashboard Kepala Sekolah untuk persetujuan akhir.</small>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Feedback / Catatan (Opsional)</label>
                                                                        <textarea name="feedback" class="form-control" rows="3">{{ $submission->feedback }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#teachersTable').DataTable();
    });
</script>
@endpush
