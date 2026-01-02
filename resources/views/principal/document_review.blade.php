@extends('layouts.app')

@section('title', 'Review Dokumen')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Review Dokumen</h3>
                    <a href="{{ route('principal.documents') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    <div class="mb-4 p-3 bg-light rounded">
                        <h5>Info Dokumen</h5>
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <td width="150" class="fw-bold">Guru</td>
                                <td>: {{ $submission->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Judul Permintaan</td>
                                <td>: {{ $submission->request->title }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Deskripsi</td>
                                <td>: {{ $submission->request->description ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">File</td>
                                <td>: 
                                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download"></i> Download / Lihat File
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <form action="{{ route('principal.documents.review', $submission->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Keputusan Anda</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="approve" value="approved" checked>
                                    <label class="form-check-label text-success fw-bold" for="approve">
                                        <i class="bi bi-check-circle-fill"></i> Setujui (Approve)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="reject" value="rejected">
                                    <label class="form-check-label text-danger fw-bold" for="reject">
                                        <i class="bi bi-x-circle-fill"></i> Tolak (Reject)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="feedback" class="form-label">Catatan / Feedback (Opsional)</label>
                            <textarea name="feedback" id="feedback" rows="3" class="form-control" placeholder="Tuliskan catatan revisi jika ditolak..."></textarea>
                            <small class="text-muted">Catatan sebelumnya (dari Validasi): {{ $submission->feedback ?? '-' }}</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Kirim Keputusan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
