@extends('layouts.app')

@section('title', 'Scan Barcode Inventaris')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    :root {
        --scan-color: #007bff;
        --scan-bg: rgba(255, 255, 255, 0.95);
        --premium-indigo: #4361ee;
    }

    .scan-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .glass-card {
        background: var(--scan-bg);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .scanner-box {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        background: #000;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }

    /* Professional Scanning Frame */
    .scanner-frame {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 10;
        pointer-events: none;
        border: 2px solid rgba(255,255,255,0.1);
    }
    
    .scanner-frame::before {
        content: '';
        position: absolute;
        top: 10%; left: 10%; right: 10%; bottom: 10%;
        border: 2px solid var(--premium-indigo);
        border-radius: 15px;
        box-shadow: 0 0 15px var(--premium-indigo);
        animation: pulse-frame 2s infinite;
    }

    @keyframes pulse-frame {
        0% { opacity: 0.5; transform: scale(0.98); }
        50% { opacity: 1; transform: scale(1); }
        100% { opacity: 0.5; transform: scale(0.98); }
    }

    /* Laser Animation */
    .laser-line {
        position: absolute;
        top: 15%;
        left: 10%;
        right: 10%;
        height: 3px;
        background: linear-gradient(to right, transparent, #ff0000, transparent);
        box-shadow: 0 0 8px #ff0000;
        z-index: 11;
        animation: scan-move 2.5s infinite linear;
        display: none;
    }

    @keyframes scan-move {
        0% { top: 15%; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: 85%; opacity: 0; }
    }

    .btn-premium {
        background: var(--premium-indigo);
        color: white;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
    }

    .btn-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
        color: white;
    }

    .item-detail-card {
        border-radius: 20px;
        background: #f8f9fa;
        border-left: 5px solid var(--premium-indigo);
    }

    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #6c757d;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .info-value {
        font-weight: 600;
        color: #2b2d42;
        font-size: 1rem;
    }

    .card-photo-wrapper {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .manual-input-pill {
        border-radius: 50px !important;
        padding-left: 20px;
        border: 2px solid #e9ecef;
    }
    
    .manual-btn-pill {
        border-radius: 0 50px 50px 0 !important;
        padding-right: 25px;
        padding-left: 20px;
    }

    .scanner-active .laser-line {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="scan-container">
        {{-- Header Section --}}
        <div class="d-flex align-items-center justify-content-between mb-4 px-2">
            <div>
                <h3 class="fw-bold mb-1 text-dark">Barcode Scanner</h3>
                <p class="text-muted small mb-0">Pindai inventaris dengan cepat & mudah</p>
            </div>
            <a href="{{ route('sarpras.inventory.index') }}" class="btn btn-light rounded-circle shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>

        <div class="glass-card shadow-lg p-3 p-md-4">
            {{-- Default State: Start Button --}}
            <div id="start-scanner-box" class="text-center py-5">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle rounded-circle" style="width: 100px; height: 100px;">
                        <i class="bi bi-qr-code-scan fs-1 text-primary"></i>
                    </div>
                </div>
                <h4 class="fw-bold">Mulai Memindai</h4>
                <p class="text-muted px-4 mb-4">Arahkan kamera ke barcode/QR Code yang tertempel pada aset sekolah.</p>
                <button type="button" class="btn btn-premium btn-lg w-100" onclick="startScanner()">
                    <i class="bi bi-camera-fill me-2"></i> Buka Kamera
                </button>
            </div>

            {{-- Scanning State: The Reader --}}
            <div id="scanner-container" class="text-center" style="display: none;">
                <div class="scanner-box mx-auto" id="box-wrapper">
                    <div class="scanner-frame"></div>
                    <div class="laser-line"></div>
                    <div id="reader" style="width: 100%; border: none;"></div>
                </div>
                <div class="mt-4 d-flex align-items-center justify-content-center gap-2">
                    <div class="spinner-grow spinner-grow-sm text-primary" role="status"></div>
                    <span class="fw-bold text-primary">Sedang Memindai...</span>
                </div>
                <button type="button" class="btn btn-link text-danger fw-bold mt-2 text-decoration-none" onclick="stopScanner()">
                    <i class="bi bi-x-circle me-1"></i> Tutup Kamera
                </button>
            </div>

            {{-- Manual Search --}}
            <div class="manual-input-section mt-4 pt-3 border-top" id="manual-input-box">
                <label class="small fw-bold text-muted mb-2 px-2 text-uppercase">Input Kode Manual</label>
                <div class="input-group mb-2">
                    <input type="text" id="manual-code" class="form-control manual-input-pill" placeholder="Masukkan Kode Item...">
                    <button class="btn btn-premium manual-btn-pill" type="button" onclick="handleManualSearch()">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </div>

            {{-- Result Section --}}
            <div id="result-section" style="display: none;" class="mt-3 animate__animated animate__fadeIn">
                <div class="alert alert-info py-2 px-3 border-0 rounded-4 d-flex align-items-center mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <span class="small fw-bold">Aset Ditemukan</span>
                </div>

                <div class="item-detail-card p-3 mb-4">
                    <div class="row g-3 align-items-start">
                        <div class="col-4 col-md-4">
                            <div class="card-photo-wrapper ratio ratio-1x1 bg-white border">
                                <img id="item-photo" src="" class="img-fluid object-fit-cover d-none">
                                <div id="item-no-photo" class="d-flex align-items-center justify-content-center w-100 h-100 text-muted-50">
                                    <i class="bi bi-image" style="font-size: 2.5rem;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-8 col-md-8">
                            <div class="info-label">KODE ASET</div>
                            <div class="info-value mb-2"><code id="item-code" class="text-primary fs-5"></code></div>
                            
                            <div class="info-label">NAMA BARANG</div>
                            <div class="info-value" id="item-name">-</div>
                        </div>
                    </div>

                    <div class="row row-cols-2 mt-4 g-3 border-top pt-3">
                        <div class="col">
                            <div class="info-label"><i class="bi bi-tag me-1"></i> Kategori</div>
                            <div class="info-value" id="item-category">-</div>
                        </div>
                        <div class="col">
                            <div class="info-label"><i class="bi bi-geo-alt me-1"></i> Lokasi</div>
                            <div class="info-value" id="item-room">-</div>
                        </div>
                        <div class="col">
                            <div class="info-label"><i class="bi bi-person me-1"></i> PJ</div>
                            <div class="info-value" id="item-pj">-</div>
                        </div>
                        <div class="col">
                            <div class="info-label"><i class="bi bi-shield-check me-1"></i> Kondisi</div>
                            <span id="item-condition" class="badge rounded-pill mt-1"></span>
                        </div>
                    </div>
                    
                    <div class="row row-cols-2 mt-2 g-3 collapse" id="extraInfo">
                        <div class="col">
                            <div class="info-label">Harga Beli</div>
                            <div class="info-value">Rp <span id="item-price">-</span></div>
                        </div>
                        <div class="col">
                            <div class="info-label">Sumber</div>
                            <div class="info-value">
                                <span id="item-source">-</span>
                                <span id="item-grant-badge" class="badge bg-warning text-dark ms-1" style="display:none;">Bantuan</span>
                            </div>
                        </div>
                        <div class="col col-12">
                            <div class="info-label">Tanggal Beli</div>
                            <div class="info-value" id="item-purchase-date">-</div>
                        </div>
                    </div>

                    <div class="text-center mt-3 pt-2">
                        <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#extraInfo">
                            Lihat Detail Selengkapnya <i class="bi bi-chevron-down"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <button class="btn btn-warning btn-lg rounded-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#reportDamageModal">
                        <i class="bi bi-exclamation-triangle me-2"></i> LAPORKAN KERUSAKAN
                    </button>
                    <button class="btn btn-outline-secondary btn-lg rounded-4 border-2" onclick="resetScanner()">
                        <i class="bi bi-qr-code me-2"></i> PINDAI LAINNYA
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Damage Modal: Clean & Targeted -->
<div class="modal fade" id="reportDamageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-warning border-0 py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-shield-exclamation me-2"></i>Laporan Masalah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="damageReportForm">
                <input type="hidden" id="report-item-code">
                <div class="modal-body p-4">
                    @if(Auth::user()->isSarpras() || Auth::user()->role === 'administrator')
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Jenis Laporan</label>
                        <select name="type" class="form-select rounded-3" required>
                            <option value="Damaged">Barang Rusak</option>
                            <option value="Lost">Barang Hilang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Deskripsi Masalah</label>
                        <textarea name="description" class="form-control rounded-3" rows="3" placeholder="Ceritakan detail kerusakan atau alasan kehilangan..." required></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Saran Solusi</label>
                            <select name="follow_up_action" class="form-select rounded-3" required>
                                <option value="Repair">Diperbaiki</option>
                                <option value="Replacement">Ganti Baru</option>
                                <option value="Disposal">Penghapusan</option>
                                <option value="Write-off">Pemutihan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Prioritas</label>
                            <select name="priority" class="form-select rounded-3">
                                <option value="Low">Biasa</option>
                                <option value="Medium" selected>Sedang</option>
                                <option value="High">Tinggi</option>
                                <option value="Urgent">Darurat</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Alasan Saran / Detail Tindak Lanjut</label>
                        <textarea name="follow_up_description" class="form-control rounded-3" rows="2" placeholder="Mengapa memberikan saran solusi di atas?" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Unggah Foto Bukti (Opsional)</label>
                        <input type="file" name="photo" class="form-control rounded-3" accept="image/*">
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-lock-fill text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-3 text-muted">Hanya <strong>Wakil Sarana</strong> yang berwenang membuat laporan kerusakan resmi.</p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Batal</button>
                    @if(Auth::user()->isSarpras() || Auth::user()->role === 'administrator')
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">Kirim Laporan</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let html5QrCode;
    const config = { fps: 15, qrbox: { width: 250, height: 250 } };

    function startScanner() {
        if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
            Swal.fire({
                title: 'Info Keamanan',
                text: 'Akses kamera membutuhkan HTTPS. Silakan gunakan alamat localhost atau pastikan SSL aktif.',
                icon: 'info'
            });
        }

        document.getElementById('start-scanner-box').style.display = 'none';
        document.getElementById('manual-input-box').style.display = 'none';
        document.getElementById('scanner-container').style.display = 'block';
        document.getElementById('box-wrapper').classList.add('scanner-active');

        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader", { 
                formatsToSupport: [ 
                    Html5QrcodeSupportedFormats.QR_CODE, 
                    Html5QrcodeSupportedFormats.CODE_128,
                    Html5QrcodeSupportedFormats.CODE_39,
                    Html5QrcodeSupportedFormats.EAN_13
                ],
                verbose: false
            });
        }

        // Re-optimized for QR Codes (much faster and more reliable)
        const qrConfig = { 
            fps: 20,
            qrbox: (viewfinderWidth, viewfinderHeight) => {
                const minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                const qrboxSize = Math.floor(minEdgeSize * 0.75);
                return {
                    width: qrboxSize,
                    height: qrboxSize
                };
            },
            aspectRatio: 1.0
        };

        html5QrCode.start(
            { facingMode: "environment" }, 
            qrConfig,
            (decodedText) => {
                console.log("Scan success:", decodedText);
                if (navigator.vibrate) navigator.vibrate(100);
                handleScanSuccess(decodedText);
            },
            (errorMessage) => {
                // Background scanning processes...
            }
        ).catch((err) => {
            console.error("Camera access error:", err);
            Swal.fire('Gagal', 'Kamera tidak dapat diakses. Pastikan izin kamera telah diberikan.', 'error');
            stopScanner();
        });
    }

    function stopScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                document.getElementById('scanner-container').style.display = 'none';
                document.getElementById('start-scanner-box').style.display = 'block';
                document.getElementById('manual-input-box').style.display = 'block';
                document.getElementById('box-wrapper').classList.remove('scanner-active');
            }).catch(err => {
                // Fallback for failed stop
                document.getElementById('scanner-container').style.display = 'none';
                document.getElementById('start-scanner-box').style.display = 'block';
                document.getElementById('manual-input-box').style.display = 'block';
            });
        }
    }

    function playSuccessSound() {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(880, audioCtx.currentTime); // A5 note
            gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
            gainNode.gain.linearRampToValueAtTime(0.2, audioCtx.currentTime + 0.05);
            gainNode.gain.linearRampToValueAtTime(0, audioCtx.currentTime + 0.15);

            oscillator.start(audioCtx.currentTime);
            oscillator.stop(audioCtx.currentTime + 0.15);
        } catch (e) {
            console.warn("Audio feedback failed:", e);
        }
    }

    function handleScanSuccess(code) {
        if (html5QrCode && html5QrCode.getState() === 2) {
            // Visual Flash Effect
            const wrapper = document.getElementById('box-wrapper');
            wrapper.style.transition = 'none';
            wrapper.style.border = '5px solid #28a745';
            wrapper.style.boxShadow = '0 0 30px #28a745';
            
            playSuccessSound();

            html5QrCode.stop().then(() => {
                setTimeout(() => {
                    document.getElementById('scanner-container').style.display = 'none';
                    document.getElementById('box-wrapper').classList.remove('scanner-active');
                    wrapper.style.border = 'none'; // reset
                    wrapper.style.boxShadow = 'none'; // reset
                    fetchItemDetails(code.trim());
                }, 300); // Small delay to show the "hit"
            });
        }
    }

    function handleManualSearch() {
        const code = document.getElementById('manual-code').value.trim();
        if (!code) {
            Swal.fire('Info', 'Masukkan kode item.', 'info');
            return;
        }
        
        if (html5QrCode && html5QrCode.getState() === 2) { 
            html5QrCode.stop().then(() => {
                document.getElementById('scanner-container').style.display = 'none';
                document.getElementById('box-wrapper').classList.remove('scanner-active');
                fetchItemDetails(code);
            });
        } else {
            document.getElementById('start-scanner-box').style.display = 'none';
            fetchItemDetails(code);
        }
    }

    function fetchItemDetails(code) {
        Swal.fire({
            title: 'Mencari...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch(`{{ route('sarpras.inventory.find-by-code') }}?code=${encodeURIComponent(code)}`)
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    const item = data.item;
                    document.getElementById('result-section').style.display = 'block';
                    document.getElementById('manual-input-box').style.display = 'none';
                    
                    document.getElementById('item-code').innerText = item.code;
                    document.getElementById('report-item-code').value = item.code;
                    document.getElementById('item-name').innerText = item.name;
                    document.getElementById('item-category').innerText = item.category;
                    document.getElementById('item-room').innerText = item.room;
                    document.getElementById('item-condition').innerText = item.condition;
                    document.getElementById('item-condition').className = `badge rounded-pill bg-${item.condition_color} shadow-sm`;
                    document.getElementById('item-price').innerText = item.price;
                    document.getElementById('item-source').innerText = item.source || '-';
                    document.getElementById('item-pj').innerText = item.person_in_charge || '-';
                    document.getElementById('item-purchase-date').innerText = item.purchase_date;
                    
                    document.getElementById('item-grant-badge').style.display = item.is_grant ? 'inline-block' : 'none';

                    const photoImg = document.getElementById('item-photo');
                    const noPhotoDiv = document.getElementById('item-no-photo');
                    
                    if (item.photo) {
                        photoImg.src = item.photo;
                        photoImg.classList.remove('d-none');
                        noPhotoDiv.classList.add('d-none');
                    } else {
                        photoImg.classList.add('d-none');
                        noPhotoDiv.classList.remove('d-none');
                    }
                } else {
                    Swal.fire('Tidak Ditemukan', data.message, 'warning').then(() => resetScanner());
                }
            })
            .catch(() => {
                Swal.fire('Error', 'Gagal memuat data.', 'error').then(() => resetScanner());
            });
    }

    function resetScanner() {
        document.getElementById('result-section').style.display = 'none';
        document.getElementById('start-scanner-box').style.display = 'block';
        document.getElementById('manual-input-box').style.display = 'block';
        document.getElementById('manual-code').value = '';
    }

    // Damage Report Submission
    document.getElementById('damageReportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Kirim Laporan?',
            text: "Laporan akan diteruskan ke Kepala Sekolah.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                const formData = new FormData(this);
                formData.append('code', document.getElementById('report-item-code').value);
                formData.append('_token', '{{ csrf_token() }}');

                fetch(`{{ route('sarpras.inventory.report-damage-by-code') }}`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('reportDamageModal')).hide();
                        Swal.fire('Berhasil', 'Laporan telah dikirim.', 'success');
                        resetScanner();
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Kesalahan sistem.', 'error'));
            }
        });
    });
</script>
@endpush
