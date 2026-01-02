@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ $request->title }}</h4>
                        <span class="text-muted small">Dibuat oleh: {{ $request->creator->name ?? 'System' }} | {{ $request->created_at->format('d M Y') }}</span>
                    </div>
                    <div>
                        <a href="{{ route('principal.documents') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
                        @if($request->created_by == Auth::id())
                        <a href="{{ route('principal.documents.edit-request', $request->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h5 class="text-primary">Deskripsi</h5>
                            <div class="p-3 bg-light rounded border">
                                {!! nl2br(e($request->description)) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h5 class="text-primary">Detail</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Tahun Ajaran:</span>
                                    <strong>{{ $request->academicYear->name ?? '-' }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Semester:</span>
                                    <strong>{{ ucfirst($request->semester) }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Deadline:</span>
                                    <strong class="text-danger">{{ $request->due_date ? $request->due_date->format('d M Y') : '-' }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Status:</span>
                                    <span class="badge {{ $request->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $request->is_active ? 'Aktif' : 'Closed' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <h5 class="mt-4">Daftar Pengumpulan Dokumen</h5>
                    @if($submissions->isEmpty())
                        <div class="alert alert-warning">Belum ada guru yang mengumpulkan dokumen ini (di unit yang Anda kelola).</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Guru</th>
                                        <th>Unit</th>
                                        <th>Tanggal Submit</th>
                                        <th>File</th>
                                        <th>Status</th>
                                        <th>Validator</th>
                                        <th>Approver</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions as $sub)
                                        <tr>
                                            <td>{{ $sub->user->name }}</td>
                                            <td>
                                                @foreach($sub->user->jabatanUnits as $ju)
                                                    <span class="badge bg-light text-dark border">{{ $ju->unit->name ?? '-' }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $sub->submitted_at->format('d M Y H:i') }}</td>
                                            <td>
                                                @if($sub->file_path)
                                                    <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-file-earmark-text"></i> Unduh
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($sub->status == 'pending') <span class="badge bg-warning">Pending</span>
                                                @elseif($sub->status == 'validated') <span class="badge bg-info">Validated</span>
                                                @elseif($sub->status == 'approved') <span class="badge bg-success">Approved</span>
                                                @elseif($sub->status == 'rejected') <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $sub->validator->name ?? '-' }}
                                                @if($sub->validated_at) <br><small class="text-muted">{{ $sub->validated_at->format('d/m/y H:i') }}</small> @endif
                                            </td>
                                            <td>
                                                {{ $sub->approver->name ?? '-' }}
                                                @if($sub->approved_at) <br><small class="text-muted">{{ $sub->approved_at->format('d/m/y H:i') }}</small> @endif
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
@endsection
