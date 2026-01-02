@extends('layouts.app')

@section('title', 'Financial Analytics Pro')

@section('content')
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
        --accent-primary: #4361ee;
        --accent-secondary: #7209b7;
        --accent-success: #10b981;
        --accent-danger: #ef4444;
        --dark-surface: #0f172a;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f8fafc;
    }

    .dashboard-container {
        padding: 2rem;
        background: radial-gradient(circle at top right, rgba(67, 97, 238, 0.05), transparent),
                    radial-gradient(circle at bottom left, rgba(114, 9, 183, 0.05), transparent);
    }

    /* Premium Header Area */
    .premium-header {
        position: relative;
        padding-bottom: 2rem;
    }

    .greeting-text {
        font-weight: 800;
        letter-spacing: -0.02em;
        background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 2.25rem;
    }

    .role-badge-modern {
        background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
        color: white;
        padding: 6px 16px;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    /* Glass Cards */
    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glass-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.12);
        border-color: rgba(67, 97, 238, 0.2);
    }

    /* Stat Cards Specifics */
    .stat-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .icon-income { background: rgba(16, 185, 129, 0.1); color: var(--accent-success); }
    .icon-month { background: rgba(67, 97, 238, 0.1); color: var(--accent-primary); }
    .icon-expense { background: rgba(239, 68, 68, 0.1); color: var(--accent-danger); }
    .icon-balance { background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%); color: white; }

    .stat-label {
        font-family: 'Outfit', sans-serif;
        color: #64748b;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0.5rem 0;
    }

    /* Quick Action Pill */
    .action-pill {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 100px;
        padding: 12px 24px;
        transition: all 0.3s;
        text-decoration: none;
        color: #1e293b;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 12px;
    }

    .action-pill:hover {
        background: var(--dark-surface);
        color: white;
        border-color: var(--dark-surface);
        transform: translateY(-2px);
    }

    /* Alert Banner */
    .alert-banner-glass {
        background: rgba(245, 158, 11, 0.08);
        border-left: 6px solid #f59e0b;
        border-radius: 16px;
        padding: 1.5rem;
        backdrop-filter: blur(8px);
    }

    /* Chart Card */
    .chart-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    /* Activity List */
    .activity-item {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.03);
        transition: background 0.2s;
        cursor: pointer;
    }

    .activity-item:hover {
        background: rgba(67, 97, 238, 0.02);
    }

    .activity-avatar {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: var(--accent-primary);
    }

    /* Quick Link Buttons */
    .grid-link {
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        background: white;
        border: 1px solid #f1f5f9;
        transition: all 0.3s;
        height: 100%;
    }

    .grid-link:hover {
        background: white;
        border-color: var(--accent-primary);
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.05);
        transform: translateY(-5px);
    }

    .grid-link i {
        font-size: 2rem;
        margin-bottom: 1rem;
        display: block;
    }
</style>

<div class="dashboard-container">
    <!-- Header Area -->
    <div class="premium-header d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-4 mb-5">
        <div>
            <div class="d-flex align-items-center gap-3 mb-2">
                <span class="role-badge-modern">{{ Auth::user()->role == 'kepala_keuangan' ? 'Kepala Keuangan' : 'Admin Keuangan' }}</span>
                <span class="text-muted small fw-600"><i class="bi bi-clock-fill me-1"></i> {{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="greeting-text mb-0">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
            <p class="text-muted mb-0">Inilah performa keuangan sekolah sejauh ini.</p>
        </div>
        <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('finance.payments.index') }}" class="action-pill shadow-sm">
                <div class="bg-primary text-white p-2 rounded-circle d-flex" style="width: 32px; height: 32px; justify-content: center; align-items: center;">
                    <i class="bi bi-plus-lg"></i>
                </div>
                <span>Penerimaan Siswa</span>
            </a>
            <a href="{{ route('finance.expense.index') }}" class="action-pill shadow-sm">
                <div class="bg-danger text-white p-2 rounded-circle d-flex" style="width: 32px; height: 32px; justify-content: center; align-items: center;">
                    <i class="bi bi-dash-lg"></i>
                </div>
                <span>Kas Keluar</span>
            </a>
        </div>
    </div>

    <!-- Smart Alerts -->
    @if($stats['pending_verifications'] > 0 || $stats['pending_realizations'] > 0)
    <div class="alert-banner-glass mb-5 d-flex flex-column flex-md-row justify-content-between align-items-center gap-4 border-0">
        <div class="d-flex align-items-center">
            <div class="bg-warning text-white p-3 rounded-4 me-3 shadow-sm">
                <i class="bi bi-lightning-charge-fill fs-4"></i>
            </div>
            <div>
                <h5 class="fw-800 mb-1" style="color: #92400e;">Prioritas Verifikasi!</h5>
                <p class="mb-0 text-muted small fw-600">Ada <strong>{{ $stats['pending_verifications'] }}</strong> transaksi siswa dan <strong>{{ $stats['pending_realizations'] }}</strong> laporan pelunasan yang butuh persetujuan Anda.</p>
            </div>
        </div>
        <a href="{{ route('finance.verifications.index') }}" class="btn btn-dark px-4 py-2 rounded-pill fw-bold">Tinjau Data Sekarang</a>
    </div>
    @endif

    <!-- Daily Stats Row -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="glass-card p-4 h-100">
                <div class="stat-icon-box icon-income">
                    <i class="bi bi-arrow-down-left-circle"></i>
                </div>
                <div class="stat-label">Pemasukan Hari Ini</div>
                <div class="stat-value">Rp {{ number_format($stats['pemasukan_hari_ini'], 0, ',', '.') }}</div>
                <div class="text-success small fw-700 mt-2">
                    <i class="bi bi-clock-history me-1"></i> Terakhir: {{ now()->format('H:i') }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4 h-100">
                <div class="stat-icon-box icon-expense">
                    <i class="bi bi-arrow-up-right-circle"></i>
                </div>
                <div class="stat-label">Pengeluaran Hari Ini</div>
                <div class="stat-value">Rp {{ number_format($stats['pengeluaran_hari_ini'], 0, ',', '.') }}</div>
                <div class="text-danger small fw-700 mt-2">
                    <i class="bi bi-receipt me-1"></i> Operasional Harian
                </div>
            </div>
        </div>
        <div class="col-md-3">
            @php $netToday = $stats['pemasukan_hari_ini'] - $stats['pengeluaran_hari_ini']; @endphp
            <div class="glass-card p-4 h-100">
                <div class="stat-icon-box {{ $netToday >= 0 ? 'icon-income' : 'icon-expense' }}" style="background: {{ $netToday >= 0 ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}">
                    <i class="bi bi-calculator"></i>
                </div>
                <div class="stat-label">Net (Surplus/Defisit)</div>
                <div class="stat-value {{ $netToday < 0 ? 'text-danger' : 'text-success' }}">
                    {{ $netToday < 0 ? '-' : '' }}Rp {{ number_format(abs($netToday), 0, ',', '.') }}
                </div>
                <div class="small fw-700 mt-2 {{ $netToday < 0 ? 'text-danger' : 'text-success' }}">
                    <i class="bi {{ $netToday >= 0 ? 'bi-caret-up-fill' : 'bi-caret-down-fill' }} me-1"></i> Saldo Harian
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4 h-100">
                <div class="stat-icon-box icon-month">
                    <i class="bi bi-layers"></i>
                </div>
                <div class="stat-label">Volume Transaksi</div>
                <div class="stat-value">{{ $stats['jumlah_transaksi'] }} <small class="text-muted" style="font-size: 1rem">Item</small></div>
                <div class="text-primary small fw-700 mt-2">
                    <i class="bi bi-check2-all me-1"></i> Data Hari Ini
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Activity -->
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="glass-card h-100 overflow-hidden">
                <div class="chart-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-800 mb-0">Arus Kas Analytics</h5>
                        <p class="text-muted small mb-0">Komparasi internal 12 bulan terakhir</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-3 fw-bold" type="button">
                            Laporan Detail <i class="bi bi-chevron-right ms-2 mt-1"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="modernFinanceChart" style="min-height: 400px;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="row g-4 mb-4">
                <div class="col-6">
                    <a href="{{ route('finance.reports.index') }}" class="grid-link text-decoration-none d-block">
                        <i class="bi bi-files text-primary"></i>
                        <span class="fw-800 d-block">Reports</span>
                        <small class="text-muted">Laporan PDF</small>
                    </a>
                </div>
                @if(in_array(Auth::user()->role, ['administrator', 'kepala_keuangan', 'direktur']))
                <div class="col-6">
                    <a href="{{ route('finance.bank-accounts.index') }}" class="grid-link text-decoration-none d-block">
                        <i class="bi bi-bank text-info"></i>
                        <span class="fw-800 d-block">Banks</span>
                        <small class="text-muted">Rekening</small>
                    </a>
                </div>
                @endif
            </div>

            <div class="glass-card">
                <div class="p-4 border-bottom border-light">
                    <h6 class="fw-800 mb-0">Transaksi Terbaru</h6>
                </div>
                <div class="activity-list border-0">
                    @forelse($recentPayments as $trx)
                    <div class="activity-item d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="activity-avatar me-3">
                                {{ strtoupper(substr($trx->description, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-800 text-dark small">{{ Str::limit($trx->description, 22) }}</div>
                                <div class="text-muted" style="font-size: 0.65rem;">
                                    <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($trx->date)->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-800 text-success small">+{{ number_format($trx->amount, 0, ',', '.') }}</div>
                            <div class="badge bg-soft-success text-success p-0" style="font-size: 0.6rem">VERIFIED</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="bi bi-inbox-fill display-4 text-muted opacity-20"></i>
                        <p class="text-muted mt-3">Belum ada aktivitas.</p>
                    </div>
                    @endforelse
                </div>
                <div class="p-4 text-center">
                    <a href="{{ route('finance.transactions.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-bold w-100">
                        Lihat Semua History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var options = {
            series: [{
                name: 'Penerimaan',
                data: @json($chartData['income'])
            }, {
                name: 'Pengeluaran',
                data: @json($chartData['expense'])
            }],
            chart: {
                type: 'area',
                height: 400,
                toolbar: { show: false },
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                stacked: false,
                sparkline: { enabled: false }
            },
            colors: ['#4361ee', '#ef4444'],
            dataLabels: { enabled: false },
            stroke: { 
                curve: 'smooth', 
                width: 4,
                lineCap: 'round'
            },
            xaxis: {
                categories: @json($chartData['labels']),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#64748b', fontWeight: 600 }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#64748b', fontWeight: 600 },
                    formatter: function (value) {
                        return (value / 1000000).toFixed(0) + " Jt";
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.02,
                    stops: [0, 90, 100],
                    colorStops: []
                }
            },
            tooltip: {
                theme: 'light',
                shared: true,
                x: { show: true },
                y: {
                    formatter: function (val) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val);
                    }
                }
            },
            grid: {
                borderColor: 'rgba(226, 232, 240, 0.5)',
                strokeDashArray: 5,
                xaxis: { lines: { show: true } },
                padding: { top: 0, right: 0, bottom: 0, left: 10 }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                offsetY: -10,
                fontWeight: 700,
                markers: { radius: 12 }
            }
        };

        var chart = new ApexCharts(document.querySelector("#modernFinanceChart"), options);
        chart.render();
    });
</script>
@endpush
