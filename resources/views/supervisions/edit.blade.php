@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0">Detail & Progress Supervisi</h5>
                </div>
                <div class="card-body">
                    
                    <!-- Info Section -->
                    <div class="alert alert-light border shadow-sm mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted d-block uppercase tracking-wide">Guru</small>
                                <span class="fw-bold text-dark">{{ $supervision->teacher->name }}</span>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block uppercase tracking-wide">Jadwal</small>
                                <span class="fw-bold text-dark">{{ $supervision->date->translatedFormat('l, d F Y') }}</span>
                                <span class="text-muted small">({{ $supervision->time ? $supervision->time->format('H:i') : 'Waktu belum diatur' }})</span>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block uppercase tracking-wide">Status Saat Ini</small>
                                @php
                                    $statusClass = match($supervision->status) {
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'warning'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($supervision->status) }}</span>
                            </div>
                        </div>
                        </div>
                        <div class="row mt-3 pt-3 border-top">
                             <div class="col-md-6">
                                <small class="text-muted d-block uppercase tracking-wide">Mata Pelajaran</small>
                                <span class="fw-bold text-dark">{{ $supervision->subject?->name . ($supervision->subject?->code ? ' ('.$supervision->subject->code.')' : '') ?? '-' }}</span>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block uppercase tracking-wide">Kelas</small>
                                <span class="fw-bold text-dark">{{ $supervision->schoolClass?->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    @php
                        $updateRoute = request()->routeIs('teacher-docs.*')
                             ? route('teacher-docs.supervisions.update', $supervision->id)
                             : route('principal.supervisions.update', $supervision->id);
                        $cancelRoute = request()->routeIs('teacher-docs.*')
                             ? route('teacher-docs.supervisions.index')
                             : route('principal.supervisions.index');
                    @endphp
                    <form action="{{ $updateRoute }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Section 1: Teacher Document (RPP/Modul) -->
                        <div class="card mb-4 border {{ Auth::user()->role === 'guru' ? 'border-primary' : '' }}">
                            <div class="card-header {{ Auth::user()->role === 'guru' ? 'bg-primary text-white' : 'bg-light' }}">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-journal-text me-2"></i> 1. Upload Dokumen Ajar (Oleh Guru)</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Dokumen RPP / Modul Ajar</label>
                                    @if($supervision->teacher_document_path)
                                        <div class="d-flex align-items-center mb-2 p-2 bg-light rounded border">
                                           <i class="bi bi-file-earmark-check text-success fs-4 me-2"></i>
                                           <div class="flex-grow-1">
                                                <a href="{{ Storage::url($supervision->teacher_document_path) }}" target="_blank" class="fw-bold text-decoration-none">Lihat Dokumen Terupload</a>
                                                <div class="small text-muted">Uploaded: {{ $supervision->updated_at->diffForHumans() }}</div>
                                           </div>
                                           <!-- Approval Status Badge -->
                                            @php
                                                $docStatusClass = match($supervision->document_status) {
                                                    'approved' => 'bg-success',
                                                    'rejected' => 'bg-danger',
                                                    default => 'bg-warning'
                                                };
                                            @endphp
                                           <span class="badge {{ $docStatusClass }} ms-2">{{ ucfirst($supervision->document_status) }}</span>
                                        </div>
                                    @else
                                        <div class="alert alert-warning py-2 small"><i class="bi bi-exclamation-circle"></i> Guru belum mengupload dokumen.</div>
                                    @endif

                                    <!-- Teacher Upload Input -->
                                    @if(Auth::user()->role === 'guru')
                                        @if($supervision->document_status === 'approved')
                                            <div class="alert alert-success mt-2 mb-0"><i class="bi bi-check-circle"></i> Dokumen telah disetujui. Tidak dapat diubah.</div>
                                        @elseif($supervision->teacher_document_path && $supervision->document_status !== 'rejected')
                                            <div class="alert alert-info mt-2 mb-0"><i class="bi bi-clock-history"></i> Dokumen telah diupload dan menunggu persetujuan supervisor.</div>
                                        @else
                                            <input type="file" name="teacher_document" class="form-control" accept=".pdf,.doc,.docx" required>
                                            <small class="text-muted">Upload dokumen sebelum supervisi dimulai.</small>
                                            <button type="submit" class="btn btn-primary btn-sm mt-2">Upload Dokumen</button>
                                        @endif
                                    @endif
                                </div>

                                <!-- Debug Approval Visibility -->
                                <!-- Principal Approval Actions for Teacher Doc -->
                                @if((Auth::id() == $supervision->supervisor_id) || Auth::user()->role === 'administrator')
                                    @if($supervision->teacher_document_path)
                                        @if($supervision->document_status !== 'approved')
                                            <div class="border-top pt-3 mt-3">
                                                <label class="form-label d-block text-muted small fw-bold">TINDAKAN SUPERVISOR (Status: {{ $supervision->document_status ?: 'Kosong' }})</label>
                                                <button type="submit" name="document_status" value="approved" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i> Setujui Dokumen</button>
                                                <button type="submit" name="document_status" value="rejected" class="btn btn-danger btn-sm"><i class="bi bi-x-lg"></i> Tolak</button>
                                            </div>
                                        @else
                                            <div class="border-top pt-3 mt-3">
                                                <label class="form-label d-block text-muted small fw-bold">TINDAKAN SUPERVISOR</label>
                                                <div class="alert alert-success py-2 mb-2"><i class="bi bi-check-circle-fill"></i> Dokumen telah disetujui.</div>
                                                <button type="submit" name="document_status" value="pending" class="btn btn-warning btn-sm" onclick="return confirm('Batalkan persetujuan dokumen ini?')"><i class="bi bi-arrow-counterclockwise"></i> Batalkan Setujui (Reset ke Pending)</button>
                                            </div>
                                        @endif
                                    @else
                                        <div class="border-top pt-3 mt-3">
                                            <div class="alert alert-warning py-2 small mb-0">
                                                <i class="bi bi-exclamation-circle"></i> Menunggu Guru upload dokumen untuk persetujuan.
                                            </div>
                                        </div>
                                    @endif
                                @endif
                             </div>
                        </div>

                        <!-- Section 2: Supervision Execution (Supervisor) -->
                        <div class="card mb-4 {{ (Auth::id() == $supervision->supervisor_id || Auth::user()->role === 'administrator') ? 'border-primary' : '' }}">
                            <div class="card-header {{ (Auth::id() == $supervision->supervisor_id || Auth::user()->role === 'administrator') ? 'bg-primary text-white' : 'bg-light' }}">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-clipboard-check me-2"></i> 2. Hasil Supervisi (Oleh Supervisor)</h6>
                            </div>
                            <div class="card-body">
                                
                                <div class="mb-3">
                                    <label class="form-label">Upload Hasil Supervisi (Instrumen/Nilai)</label>
                                    @if($supervision->supervisor_document_path)
                                        <div class="mb-2">
                                            <a href="{{ Storage::url($supervision->supervisor_document_path) }}" target="_blank" class="btn btn-outline-info btn-sm"><i class="bi bi-download"></i> Download Hasil Supervisi</a>
                                        </div>
                                    @endif

                                    @if((Auth::id() == $supervision->supervisor_id) || Auth::user()->role === 'administrator')
                                        <input type="file" name="supervisor_document" class="form-control">
                                    @else
                                        @if(!$supervision->supervisor_document_path)
                                            <span class="text-muted d-block fst-italic">Hasil belum tersedia.</span>
                                        @endif
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Catatan / Evaluasi</label>
                                     @if(Auth::user()->role !== 'guru')
                                        <textarea name="notes" class="form-control" rows="4">{{ $supervision->notes }}</textarea>
                                    @else
                                        <div class="p-3 bg-light rounded border">
                                            {!! nl2br(e($supervision->notes)) ?: '<span class="text-muted fst-italic">Belum ada catatan.</span>' !!}
                                        </div>
                                    @endif
                                </div>
                                
                                @if(Auth::user()->role !== 'guru')
                                    <div class="mb-3">
                                         <label class="form-label">Update Status Utama</label>
                                         <select name="status" class="form-select w-auto">
                                            <option value="scheduled" {{ $supervision->status == 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                                            <option value="completed" {{ $supervision->status == 'completed' ? 'selected' : '' }}>Selesai / Completed</option>
                                            <option value="cancelled" {{ $supervision->status == 'cancelled' ? 'selected' : '' }}>Batalkan</option>
                                         </select>
                                    </div>

                                    <hr>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary px-4">Simpan Hasil & Status</button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="d-grid">
                             <a href="{{ $cancelRoute }}" class="btn btn-secondary">Kembali ke Daftar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
