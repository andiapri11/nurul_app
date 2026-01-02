@extends('layouts.app')

@section('title', 'Tambah Jabatan')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="m-0 fw-bold"><i class="bi bi-briefcase me-2"></i>Tambah Jabatan Master</h5>
                </div>
                
                @if ($errors->any())
                    <div class="alert alert-danger mx-3 mt-3 shadow-sm border-0">
                        <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-2"></i> Periksa kembali input Anda:</div>
                        <ul class="mb-0 small ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('jabatans.store') }}" method="POST">
                    @csrf
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label small fw-bold text-muted mb-1" for="unit_id"><i class="bi bi-building me-1"></i>Unit (Opsional)</label>
                                    <select name="unit_id" class="form-select shadow-sm">
                                        <option value="">-- Umum / Semua Unit --</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted extra-small d-block mt-1">Pilih jika jabatan unit-spesifik (cth: Wakasek Kurikulum SD).</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small fw-bold text-muted mb-1" for="kode_jabatan"><i class="bi bi-hash me-1"></i>Kode Jabatan</label>
                                    <select name="kode_jabatan" class="form-select shadow-sm" id="kode_jabatan" required onchange="updateNamaJabatan()">
                                        <option value="" selected disabled>-- Pilih Kode Jabatan --</option>
                                        <optgroup label="Pimpinan">
                                            <option value="kepala_sekolah" data-nama="Kepala Sekolah">kepala_sekolah</option>
                                            <option value="wakil_kurikulum" data-nama="Wakil Kurikulum">wakil_kurikulum</option>
                                            <option value="wakil_kesiswaan" data-nama="Wakil Kesiswaan">wakil_kesiswaan</option>
                                            <option value="wakil_sarana_prasarana" data-nama="Wakil Sarana Prasarana">wakil_sarana_prasarana</option>
                                            <option value="wakil_humas" data-nama="Wakil Humas">wakil_humas</option>
                                        </optgroup>
                                        <optgroup label="Guru">
                                            <option value="guru" data-nama="Guru Mapel">guru</option>
                                            <option value="guru_bk" data-nama="Guru BK">guru_bk</option>
                                            <option value="wali_kelas" data-nama="Wali Kelas">wali_kelas</option>
                                            <option value="pembina_osis" data-nama="Pembina OSIS">pembina_osis</option>
                                            <option value="koordinator_ekstrakurikuler" data-nama="Koordinator Ekstrakurikuler">koordinator_ekstrakurikuler</option>
                                        </optgroup>
                                        <optgroup label="Staff & Lainnya">
                                            <option value="kepala_tu" data-nama="Kepala TU">kepala_tu</option>
                                            <option value="staff_tu" data-nama="Staff TU">staff_tu</option>
                                            <option value="kepala_keuangan" data-nama="Kepala Keuangan">kepala_keuangan</option>
                                            <option value="admin_keuangan" data-nama="Admin Keuangan">admin_keuangan</option>
                                            <option value="bendahara" data-nama="Bendahara">bendahara</option>
                                            <option value="pustakawan" data-nama="Pustakawan">pustakawan</option>
                                            <option value="security" data-nama="Security">security</option>
                                            <option value="cleaning_service" data-nama="Cleaning Service">cleaning_service</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small fw-bold text-muted mb-1" for="nama_jabatan"><i class="bi bi-type me-1"></i>Nama Jabatan</label>
                                    <input type="text" name="nama_jabatan" class="form-control shadow-sm" id="nama_jabatan" placeholder="Cth: Kepala Sekolah SD" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small fw-bold text-muted mb-1" for="kategori"><i class="bi bi-tag me-1"></i>Kategori</label>
                                    <div class="d-flex gap-2">
                                        <input type="radio" class="btn-check" name="kategori" id="kat_guru" value="guru" checked autocomplete="off">
                                        <label class="btn btn-outline-success btn-sm flex-fill" for="kat_guru">Guru (Pendidik)</label>

                                        <input type="radio" class="btn-check" name="kategori" id="kat_staff" value="staff" autocomplete="off">
                                        <label class="btn btn-outline-secondary btn-sm flex-fill" for="kat_staff">Staff (Tendik)</label>

                                        <input type="radio" class="btn-check" name="kategori" id="kat_tambahan" value="tambahan" autocomplete="off">
                                        <label class="btn btn-outline-info btn-sm flex-fill" for="kat_tambahan">Tambahan</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small fw-bold text-muted mb-1" for="tipe"><i class="bi bi-diagram-3 me-1"></i>Tipe Jabatan</label>
                                    <select name="tipe" class="form-select shadow-sm" required>
                                        <option value="fungsional">Fungsional (Massal, cth: Guru Mapel)</option>
                                        <option value="struktural">Struktural (Tunggal, cth: Kepala Sekolah)</option>
                                        <option value="tambahan">Tugas Tambahan (cth: Wali Kelas)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light p-3 text-end">
                        <a href="{{ route('jabatans.index') }}" class="btn btn-link text-muted text-decoration-none me-3">Batal</a>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> Simpan Jabatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateNamaJabatan() {
        const select = document.getElementById('kode_jabatan');
        const namaInput = document.getElementById('nama_jabatan');
        const selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption.dataset.nama) {
            namaInput.value = selectedOption.dataset.nama;
        }
    }
</script>

<style>
    .extra-small { font-size: 0.75rem; }
    .form-label i { opacity: 0.7; }
</style>
@endpush
