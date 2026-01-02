@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-gray-800 mb-1">Dashboard Sarana Prasarana</h1>
            <p class="text-muted mb-0">Ringkasan kondisi aset dan aktivitas laporan terkini.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-light text-dark border shadow-sm px-3 py-2 rounded-pill">
                <i class="bi bi-calendar-event me-2"></i> {{ now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-3 d-flex gap-3 align-items-center flex-wrap">
                    <span class="fw-bold text-uppercase text-xs text-muted me-2">Akses Cepat:</span>
                    <a href="{{ route('sarpras.scan') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="bi bi-qr-code-scan me-1"></i> Scan Aset
                    </a>
                    <a href="{{ route('sarpras.inventory.store') }}" class="btn btn-sm btn-outline-success rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#createInventoryModal">
                        <i class="bi bi-box-seam me-1"></i> Input Barang
                    </a>
                    <a href="{{ route('sarpras.reports.index') }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                        <i class="bi bi-exclamation-octagon me-1"></i> Laporan Kerusakan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Barang -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body position-relative">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="bi bi-box-seam display-4 text-primary"></i>
                    </div>
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Aset</div>
                    <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $stats['total_items'] }}</div>
                    <div class="mt-2 mb-0 text-muted text-xs">
                        <span class="text-success fw-bold"><i class="bi bi-arrow-up"></i> Terdata</span>
                        <span class="ms-1">di sistem</span>
                    </div>
                </div>
                <div class="card-footer bg-primary bg-opacity-10 border-0 py-2">
                    <a href="{{ route('sarpras.inventory.index') }}" class="text-decoration-none text-primary text-xs fw-bold">
                        Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Ruangan -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body position-relative">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="bi bi-door-open display-4 text-info"></i>
                    </div>
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Ruangan</div>
                    <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $stats['total_rooms'] }}</div>
                    <div class="mt-2 mb-0 text-muted text-xs">
                        <span>Unit & Kelas</span>
                    </div>
                </div>
                <div class="card-footer bg-info bg-opacity-10 border-0 py-2">
                    <a href="{{ route('sarpras.rooms.index') }}" class="text-decoration-none text-info text-xs fw-bold">
                        Kelola Ruangan <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Barang Rusak -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body position-relative">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="bi bi-tools display-4 text-danger"></i>
                    </div>
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Kondisi Rusak</div>
                    <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $stats['broken_items'] }}</div>
                    <div class="mt-2 mb-0 text-muted text-xs">
                        @php
                            $brokenPercent = $stats['total_items'] > 0 ? round(($stats['broken_items'] / $stats['total_items']) * 100, 1) : 0;
                        @endphp
                        <span class="text-danger fw-bold">{{ $brokenPercent }}%</span>
                        <span class="ms-1">dari total aset</span>
                    </div>
                </div>
                <div class="card-footer bg-danger bg-opacity-10 border-0 py-2">
                    <a href="{{ route('sarpras.inventory.index', ['condition' => 'Broken']) }}" class="text-decoration-none text-danger text-xs fw-bold">
                        Cek Daftar <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Stok Menipis (Consumables) -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body position-relative">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="bi bi-cart-x display-4 text-warning"></i>
                    </div>
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Stok Menipis</div>
                    <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $stats['low_stock'] }}</div>
                    <div class="mt-2 mb-0 text-muted text-xs">
                        <span>Barang Habis Pakai</span>
                    </div>
                </div>
                <div class="card-footer bg-warning bg-opacity-10 border-0 py-2">
                    <a href="{{ route('sarpras.consumables.index') }}" class="text-decoration-none text-warning text-xs fw-bold">
                        Restock <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-4">
        <!-- Recent Reports -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="m-0 fw-bold text-dark">Laporan Kerusakan Terbaru</h6>
                        <span class="text-xs text-muted">5 Laporan terakhir yang masuk sistem</span>
                    </div>
                    <a href="{{ route('sarpras.reports.index') }}" class="btn btn-sm btn-light text-primary fw-bold rounded-pill px-3">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted text-uppercase text-xs fw-bold">
                                <tr>
                                    <th class="ps-4 py-3">Barang/Aset</th>
                                    <th class="py-3">Pelapor</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="pe-4 py-3 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentReports as $report)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-3 text-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-box text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $report->inventory->name }}</div>
                                                <div class="text-xs text-muted">{{ $report->created_at->format('d M Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <i class="bi bi-person-circle text-secondary"></i>
                                            </div>
                                            <span class="text-sm">{{ $report->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $badges = [
                                                'Pending' => 'bg-secondary',
                                                'Processed' => 'bg-info',
                                                'Fixed' => 'bg-success',
                                                'Rejected' => 'bg-danger'
                                            ];
                                            $labels = [
                                                'Pending' => 'Menunggu',
                                                'Processed' => 'Proses',
                                                'Fixed' => 'Selesai',
                                                'Rejected' => 'Ditolak'
                                            ];
                                        @endphp
                                        <span class="badge {{ $badges[$report->status] ?? 'bg-secondary' }} rounded-pill px-3 fw-normal">
                                            {{ $labels[$report->status] ?? $report->status }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('sarpras.reports.index') }}" class="btn btn-sm btn-light text-secondary rounded-circle" title="Detail">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-clipboard-x display-6 d-block mb-3 opacity-25"></i>
                                        Belum ada laporan kerusakan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Condition Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="m-0 fw-bold text-dark">Ringkasan Kondisi Aset</h6>
                    <span class="text-xs text-muted">Distribusi kondisi inventaris keseluruhan</span>
                </div>
                <div class="card-body pt-2">
                    @php
                        $conditions = [
                            'Good' => ['label' => 'Baik', 'color' => 'bg-success', 'msg' => 'Aset dalam kondisi prima'],
                            'Repairing' => ['label' => 'Perbaikan', 'color' => 'bg-info', 'msg' => 'Sedang dalam penanganan'],
                            'Damaged' => ['label' => 'Rusak Ringan', 'color' => 'bg-warning', 'msg' => 'Butuh perbaikan minor'],
                            'Broken' => ['label' => 'Rusak Berat', 'color' => 'bg-danger', 'msg' => 'Tidak dapat digunakan'],
                        ];
                    @endphp

                    @foreach($conditions as $key => $meta)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-end mb-1">
                                <div>
                                    <span class="fw-bold text-dark">{{ $meta['label'] }}</span>
                                    <div class="text-xs text-muted">{{ $meta['msg'] }}</div>
                                </div>
                                <span class="fw-bold h5 mb-0 text-dark">{{ $conditionStats[$key] ?? 0 }}</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 6px;">
                                @php
                                    $percentage = $stats['total_items'] > 0 ? ( ($conditionStats[$key] ?? 0) / $stats['total_items'] * 100 ) : 0;
                                @endphp
                                <div class="progress-bar {{ $meta['color'] }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-4 p-3 bg-light rounded-3 text-center">
                        <div class="text-xs text-muted text-uppercase fw-bold mb-2">Total Laporan Pending</div>
                        <div class="h3 fw-bold text-danger mb-0">{{ $stats['pending_reports'] }}</div>
                        <div class="text-xs text-muted">Perlu Tindakan Segera</div>
                        <a href="{{ route('sarpras.reports.index') }}" class="btn btn-sm btn-danger w-100 mt-2 rounded-pill">Lihat Laporan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
