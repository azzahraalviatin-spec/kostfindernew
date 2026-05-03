@extends('layouts.owner')

@section('title', 'Tambah Kamar')

@push('styles')
<style>
    .form-card { background:#fff; border-radius:1rem; border:1px solid var(--line); box-shadow:0 6px 20px rgba(0,0,0,.04); padding:1.5rem; margin-bottom:1.5rem; }
    .form-card h6 { font-weight:800; color:var(--dark); font-size:.95rem; margin-bottom:1.2rem; padding-bottom:.8rem; border-bottom:1px solid #f0f3f8; display:flex; align-items:center; gap:.5rem; }
    .form-label { font-size:.82rem; font-weight:700; color:#344054; margin-bottom:.4rem; }
    .form-control, .form-select { font-size:.85rem; border-color:var(--line); border-radius:.75rem; padding:.65rem .9rem; min-height:46px; }
    .form-control:focus, .form-select:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(232,64,28,.1); }
    .hover-opacity-100:hover { opacity: 1 !important; transition: .2s; }

    /* ── UPLOAD DROP ZONE ── */
    .drop-zone { border:2px dashed #d8e2ef; border-radius:1.1rem; background:#fafcff; padding:2rem 1.5rem; text-align:center; cursor:pointer; transition:all .25s ease; position:relative; overflow:hidden; }
    .drop-zone:hover, .drop-zone.drag-over { border-color:var(--primary); background:#fff8f6; }
    .drop-zone.drag-over { border-style:solid; box-shadow:0 0 0 4px rgba(232,64,28,.1); }
    .dz-icon { width:60px; height:60px; margin:0 auto .8rem; background:linear-gradient(135deg,#fff5f2,#ffe8e0); border-radius:1rem; display:flex; align-items:center; justify-content:center; font-size:1.6rem; color:var(--primary); box-shadow:0 8px 20px rgba(232,64,28,.12); transition:.25s; }
    .drop-zone:hover .dz-icon { transform:translateY(-4px); box-shadow:0 12px 28px rgba(232,64,28,.2); }
    .dz-title { font-size:.95rem; font-weight:800; color:var(--dark); margin-bottom:.25rem; }
    .dz-sub { font-size:.78rem; color:var(--muted); margin-bottom:1rem; }
    .btn-pilih-foto { display:inline-flex; align-items:center; gap:.4rem; background:linear-gradient(135deg,#e8401c,#ff7043); color:#fff; font-weight:700; font-size:.8rem; padding:.55rem 1.2rem; border-radius:.75rem; border:none; box-shadow:0 6px 16px rgba(232,64,28,.22); cursor:pointer; transition:.2s; }
    .btn-pilih-foto:hover { background:linear-gradient(135deg,#cb3518,#e8401c); transform:translateY(-1px); }
    .dz-hint { font-size:.7rem; color:#b0bfcc; margin-top:.75rem; }

    /* ── INFO BAR ── */
    .foto-info-bar { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.5rem; margin-top:1rem; padding:.6rem .9rem; background:#fff5f2; border:1px solid #ffd0c0; border-radius:.75rem; }
    .foto-info-left { display:flex; align-items:center; gap:.5rem; font-size:.78rem; color:#9a3412; font-weight:600; }
    .foto-counter { display:flex; align-items:center; gap:.35rem; }
    .counter-dot { width:9px; height:9px; border-radius:50%; background:#e2c9c4; transition:.2s; }
    .counter-dot.filled { background:var(--primary); }

    /* ── PREVIEW GRID ── */
    .preview-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:.8rem; margin-top:1.1rem; }
    .preview-card { position:relative; border-radius:.85rem; overflow:hidden; background:#f8fafc; border:1.5px solid var(--line); box-shadow:0 4px 14px rgba(15,23,42,.06); transition:.25s; animation:popIn .3s cubic-bezier(.34,1.56,.64,1) both; }
    @keyframes popIn { from{opacity:0;transform:scale(.88)} to{opacity:1;transform:scale(1)} }
    .preview-card:hover { transform:translateY(-3px); box-shadow:0 10px 24px rgba(15,23,42,.1); border-color:#cdd6e4; }
    .preview-card.is-cover { border-color:var(--primary); box-shadow:0 4px 16px rgba(232,64,28,.18); }
    .preview-img-wrap { position:relative; height:130px; overflow:hidden; background:#edf2f7; }
    .preview-img-wrap img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .3s; }
    .preview-card:hover .preview-img-wrap img { transform:scale(1.06); }
    .badge-cover { position:absolute; top:7px; left:7px; background:linear-gradient(135deg,#e8401c,#ff7043); color:#fff; font-size:.6rem; font-weight:800; padding:.25rem .6rem; border-radius:999px; display:flex; align-items:center; gap:.3rem; }
    .badge-num { position:absolute; top:7px; right:38px; background:rgba(17,24,39,.6); color:#fff; font-size:.6rem; font-weight:700; padding:.22rem .5rem; border-radius:999px; backdrop-filter:blur(4px); }
    .btn-remove { position:absolute; top:6px; right:6px; width:28px; height:28px; border:none; border-radius:50%; background:rgba(17,24,39,.6); color:#fff; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:.75rem; backdrop-filter:blur(4px); transition:.2s; }
    .btn-remove:hover { background:rgba(220,38,38,.9); transform:scale(1.1); }
    .preview-info { padding:.55rem .75rem; font-size:.75rem; font-weight:700; color:var(--dark); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .preview-size { font-size:.68rem; color:var(--muted); font-weight:500; }

    .btn-submit { background:linear-gradient(135deg,#e8401c,#ff7043); color:#fff; font-weight:700; border:0; border-radius:.75rem; padding:.8rem 2rem; font-size:.9rem; cursor:pointer; box-shadow:0 6px 16px rgba(232,64,28,.2); transition:.2s; display:inline-flex; align-items:center; gap:.5rem; }
    .btn-submit:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(232,64,28,.3); }

    @media(max-width:600px) { .preview-grid { grid-template-columns:repeat(2,1fr); } }
</style>
@endpush

@section('content')
    <div class="d-flex align-items-center gap-2 mb-3" style="font-size:.82rem; color:var(--muted);">
        <a href="{{ route('owner.kamar.index') }}" style="color:var(--muted); text-decoration:none;">Kelola Kamar</a>
        <i class="bi bi-chevron-right" style="font-size:.7rem;"></i>
        <span style="color:var(--dark); font-weight:700;">Tambah Kamar Baru</span>
    </div>

    {{-- ✅ FORM DIMULAI DI SINI — semua input ada di dalam form --}}
    <form action="{{ route('owner.kamar.store') }}" method="POST" enctype="multipart/form-data" id="formKamar">
        @csrf
        <input type="hidden" name="mode_massal" id="hiddenModeMassal" value="0">

        {{-- BANNER MODE MASSAL (sekarang di DALAM form) --}}
        <div style="background:linear-gradient(135deg,#fff7ed,#fff3e0);border:1.5px solid #fed7aa;border-radius:1rem;padding:1rem 1.2rem;margin-bottom:1.2rem;display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
            <div style="flex:1;min-width:200px;">
                <div style="font-weight:800;color:#9a3412;font-size:.9rem;"><i class="bi bi-lightning-charge-fill me-1"></i> Mode Buat Massal</div>
                <div style="font-size:.78rem;color:#c2410c;margin-top:.2rem;">Aktifkan untuk membuat banyak kamar sekaligus dengan harga & fasilitas yang sama.</div>
            </div>
            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="toggleMassal" onchange="toggleModemassal(this)" style="width:2.5rem;height:1.3rem;">
                <label class="form-check-label fw-bold" for="toggleMassal" style="font-size:.85rem;color:#9a3412;">Aktifkan</label>
            </div>
        </div>

        {{-- PANEL MASSAL (sekarang di DALAM form — ini fix utamanya!) --}}
        <div id="panelMassal" style="display:none;background:#fff;border:1.5px solid #fed7aa;border-radius:1rem;padding:1.2rem;margin-bottom:1.2rem;">
            <h6 style="font-weight:800;color:#9a3412;font-size:.88rem;margin-bottom:.9rem;">
                <i class="bi bi-grid-3x3-gap-fill me-1"></i> Pengaturan Kamar Massal
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Awalan Nomor Kamar <span style="font-size:.72rem;color:#9a3412;">(prefix)</span></label>
                    {{-- ✅ name tetap nomor_kamar_prefix, sekarang di dalam form --}}
                    <input type="text" id="prefixKamar" name="nomor_kamar_prefix" class="form-control"
                           placeholder="Misal: A-, Kamar-, Lt1-" value="{{ old('nomor_kamar_prefix') }}">
                    <div style="font-size:.7rem;color:#b45309;margin-top:.3rem;">
                        <i class="bi bi-info-circle me-1"></i>Contoh prefix "A-" → A-1, A-2, A-3, ...
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jumlah Kamar yang Dibuat</label>
                    {{-- ✅ name tetap jumlah_kamar, sekarang di dalam form --}}
                    <input type="number" id="jumlahKamar" name="jumlah_kamar" class="form-control"
                           placeholder="Misal: 10" min="1" max="50"
                           value="{{ old('jumlah_kamar', 1) }}" oninput="updatePreviewNomor()">
                    <div style="font-size:.7rem;color:#b45309;margin-top:.3rem;">
                        <i class="bi bi-info-circle me-1"></i>Maks 50 kamar sekaligus
                    </div>
                </div>
                <div class="col-12">
                    <div id="previewNomor" style="background:#fff7ed;border:1px solid #fed7aa;border-radius:.65rem;padding:.6rem .9rem;font-size:.78rem;color:#9a3412;display:none;">
                        <strong>Preview nomor kamar:</strong> <span id="previewNomorText"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">

                {{-- PILIH KOST --}}
                <div class="form-card">
                    <h6><i class="bi bi-house" style="color:var(--primary)"></i> Pilih Properti Kost</h6>
                    <div class="mb-3">
                        <label class="form-label">Kost Mana yang Ditambah Kamarnya? <span class="text-danger">*</span></label>
                        <select name="kost_id" class="form-select @error('kost_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kost --</option>
                            @foreach($kosts as $k)
                                <option value="{{ $k->id_kost }}" {{ old('kost_id') == $k->id_kost ? 'selected' : '' }}>
                                    {{ $k->nama_kost }}
                                </option>
                            @endforeach
                        </select>
                        @error('kost_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- INFORMASI DASAR --}}
                <div class="form-card">
                    <h6><i class="bi bi-info-circle" style="color:var(--primary)"></i> Informasi Kamar</h6>
                    <div class="row g-3">
                        <div class="col-md-6" id="wrapNomorKamar">
                            <label class="form-label">Nomor/Nama Kamar <span class="text-danger" id="starNomor">*</span></label>
                            <input type="text" name="nomor_kamar" id="inputNomorKamar" class="form-control"
                                   placeholder="Misal: A1, Kamar 10, dll" value="{{ old('nomor_kamar') }}">
                            <div id="infoNomorMassal" style="display:none;font-size:.72rem;color:#9a3412;margin-top:.3rem;">
                                <i class="bi bi-info-circle me-1"></i>Di mode massal, nomor otomatis dari prefix + urutan.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipe Kamar</label>
                            <select name="tipe_kamar" class="form-select">
                                <option value="">-- Pilih Tipe Kamar --</option>
                                <option value="Standard"  {{ old('tipe_kamar') == 'Standard'  ? 'selected' : '' }}>🏠 Standard — Paling dasar, harga terjangkau</option>
                                <option value="Menengah"  {{ old('tipe_kamar') == 'Menengah'  ? 'selected' : '' }}>🏡 Menengah — Fasilitas lebih lengkap</option>
                                <option value="Superior"  {{ old('tipe_kamar') == 'Superior'  ? 'selected' : '' }}>✨ Superior — Nyaman & premium</option>
                                <option value="Deluxe"    {{ old('tipe_kamar') == 'Deluxe'    ? 'selected' : '' }}>💎 Deluxe — Mewah & luas</option>
                                <option value="VIP"       {{ old('tipe_kamar') == 'VIP'       ? 'selected' : '' }}>👑 VIP — Paling eksklusif</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ukuran Kamar (PxL)</label>
                            <input type="text" name="ukuran" class="form-control" placeholder="Misal: 3x4 meter" value="{{ old('ukuran') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Info Listrik</label>
                            <select name="listrik" class="form-select">
                                <option value="Termasuk">Termasuk Biaya Sewa</option>
                                <option value="Token Sendiri">Token Sendiri (Bayar Terpisah)</option>
                                <option value="Tagihan Bulanan">Tagihan Bulanan Terpisah</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Deskripsi Kamar</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan kondisi kamar kamu...">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="mt-3">
                        <label class="form-label"><i class="bi bi-clipboard-check me-1" style="color:var(--primary)"></i>Peraturan Kamar</label>
                        <textarea name="aturan_kamar" class="form-control" rows="3" placeholder="Contoh: Tidak boleh merokok, jam malam 22.00, tamu dilarang menginap...">{{ old('aturan_kamar') }}</textarea>
                        <div style="font-size:.72rem;color:var(--muted);margin-top:.3rem;">
                            <i class="bi bi-info-circle me-1"></i>Aturan khusus yang berlaku di kamar ini
                        </div>
                    </div>
                </div>

                {{-- FASILITAS KAMAR --}}
                <div class="form-card">
                    <h6><i class="bi bi-check2-square" style="color:var(--primary)"></i> Fasilitas Kamar</h6>
                    <div class="row g-2" id="roomFacilityList">
                        @foreach([
                            'Kasur/Springbed','Bantal','Guling',
                            'AC','Kipas Angin',
                            'Kamar Mandi Dalam','Water Heater',
                            'Lemari Baju','Meja & Kursi Belajar',
                            'TV','WiFi Kamar','Stop Kontak',
                            'Jendela','Cermin','Gantungan Baju',
                            'Sapu & Pel','Tempat Sampah',
                            'Rak Sepatu','Lampu Belajar',
                        ] as $f)
                            <div class="col-6 col-md-4" id="rf-item-{{ $loop->index }}">
                                <div class="form-check p-2 border rounded-3 d-flex align-items-center justify-content-between pe-2" style="font-size:.8rem; background:#f8fafc;">
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input ms-0 me-2" type="checkbox"
                                               name="fasilitas[]" value="{{ $f }}" id="f_{{ $loop->index }}"
                                               {{ is_array(old('fasilitas')) && in_array($f, old('fasilitas')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="f_{{ $loop->index }}">{{ $f }}</label>
                                    </div>
                                    <button type="button" class="btn btn-link text-danger p-0 ms-2 opacity-50 hover-opacity-100" onclick="document.getElementById('rf-item-{{ $loop->index }}').remove()" title="Hapus dari daftar">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Section untuk input fasilitas kustom --}}
                    <div class="mt-4 pt-3 border-top">
                        <label class="form-label" style="font-size:.78rem;color:var(--muted);">Gak nemu fasilitas kamar? Tambah di sini:</label>
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" id="customFacInput" class="form-control" placeholder="Contoh: Kulkas Mini, Balkon..." style="border-radius:.6rem 0 0 .6rem;">
                            <button type="button" class="btn btn-primary px-3" onclick="addCustomFacility()" style="border-radius:0 .6rem .6rem 0;background:var(--primary);border:none;">
                                <i class="bi bi-plus-lg"></i> Tambah
                            </button>
                        </div>
                        <div id="customFacContainer" class="row g-2 mt-2">
                            {{-- Fasilitas kustom akan ditambahkan di sini --}}
                        </div>
                    </div>
                </div>

                {{-- FOTO KAMAR --}}
                <div class="form-card">
                    <h6><i class="bi bi-camera" style="color:var(--primary)"></i> Foto Kamar
                        <span style="font-size:.72rem;font-weight:500;color:var(--muted);">(Maks. 6 foto)</span>
                    </h6>
                    <div class="drop-zone" id="dropZoneKamar">
                        <div class="dz-icon"><i class="bi bi-camera-fill"></i></div>
                        <div class="dz-title">Upload Foto Kamar</div>
                        <div class="dz-sub">Seret & lepas foto di sini, atau klik tombol di bawah</div>
                        <button type="button" class="btn-pilih-foto" onclick="document.getElementById('fotoKamarInput').click()">
                            <i class="bi bi-folder2-open"></i> Pilih Foto
                        </button>
                        <div class="dz-hint"><i class="bi bi-info-circle me-1"></i>Maks. <strong>6 foto</strong> &bull; JPG, PNG, WEBP &bull; Maks. <strong>5 MB</strong>/foto</div>
                    </div>
                    <input type="file" name="foto_kamar[]" id="fotoKamarInput" accept="image/jpeg,image/png,image/webp" multiple style="display:none;">
                    <div id="fotoKamarJudulContainer"></div>

                    <div class="foto-info-bar" id="kamarInfoBar" style="display:none;">
                        <div class="foto-info-left"><i class="bi bi-images"></i><span id="kamarInfoText">0 dari 6 foto</span></div>
                        <div class="foto-counter" id="kamarDots">
                            <div class="counter-dot" id="kdot1"></div><div class="counter-dot" id="kdot2"></div>
                            <div class="counter-dot" id="kdot3"></div><div class="counter-dot" id="kdot4"></div>
                            <div class="counter-dot" id="kdot5"></div><div class="counter-dot" id="kdot6"></div>
                        </div>
                    </div>
                    <div class="preview-grid" id="kamarPreviewGrid"></div>
                </div>

            </div>{{-- /col-lg-8 --}}

            <div class="col-lg-4">

                {{-- HARGA --}}
                <div class="form-card">
                    <h6><i class="bi bi-cash-stack" style="color:var(--primary)"></i> Harga Sewa</h6>
                    <div class="mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="aktif_bulanan" id="aktif_bulanan" checked>
                            <label class="form-check-label fw-bold" for="aktif_bulanan">Bisa Sewa Bulanan</label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_per_bulan" class="form-control" placeholder="Contoh: 800000" value="{{ old('harga_per_bulan') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="aktif_harian" id="aktif_harian">
                            <label class="form-check-label fw-bold" for="aktif_harian">Bisa Sewa Harian</label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_harian" class="form-control" placeholder="Contoh: 100000" value="{{ old('harga_harian') }}">
                        </div>
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="form-card">
                    <h6><i class="bi bi-toggle-on" style="color:var(--primary)"></i> Status Kamar</h6>
                    <select name="status_kamar" class="form-select">
                        <option value="tersedia" selected>Tersedia (Kosong)</option>
                        <option value="terisi">Terisi (Penuh)</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit w-100 mb-4" id="btnSubmitKamar">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span id="labelBtnSubmit">Tambah Kamar Baru</span>
                </button>

            </div>{{-- /col-lg-4 --}}
        </div>{{-- /row --}}

    </form>{{-- ✅ form ditutup di sini --}}

@endsection

@push('scripts')
<script>
    // ── MODE MASSAL ──────────────────────────────────────────────
    function toggleModemassal(checkbox) {
        const isMassal = checkbox.checked;
        document.getElementById('panelMassal').style.display    = isMassal ? 'block' : 'none';
        document.getElementById('hiddenModeMassal').value        = isMassal ? '1' : '0';
        const inputNomor = document.getElementById('inputNomorKamar');
        const infoMassal = document.getElementById('infoNomorMassal');
        inputNomor.required = !isMassal;
        inputNomor.disabled = isMassal;
        inputNomor.style.opacity = isMassal ? '.4' : '1';
        infoMassal.style.display = isMassal ? 'block' : 'none';
        document.getElementById('labelBtnSubmit').textContent = isMassal
            ? 'Buat Semua Kamar Sekaligus' : 'Tambah Kamar Baru';
        updatePreviewNomor();
    }

    function updatePreviewNomor() {
        const prefix     = (document.getElementById('prefixKamar')?.value || '').trim();
        const jumlah     = parseInt(document.getElementById('jumlahKamar')?.value) || 1;
        const previewBox = document.getElementById('previewNomor');
        const previewTxt = document.getElementById('previewNomorText');
        if (!document.getElementById('toggleMassal').checked) return;
        if (jumlah < 1) { previewBox.style.display = 'none'; return; }
        const list   = [];
        const tampil = Math.min(jumlah, 5);
        for (let i = 1; i <= tampil; i++) list.push(prefix + i);
        if (jumlah > 5) list.push('...' + prefix + jumlah);
        previewTxt.textContent    = list.join(', ');
        previewBox.style.display  = 'block';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const prefixInput = document.getElementById('prefixKamar');
        if (prefixInput) prefixInput.addEventListener('input', updatePreviewNomor);
    });

    // ── FOTO KAMAR UPLOAD ─────────────────────────────────────────
    const MAX_FILES   = 6;
    const MAX_MB      = 5;
    const dropZone    = document.getElementById('dropZoneKamar');
    const fotoInput   = document.getElementById('fotoKamarInput');
    const previewGrid = document.getElementById('kamarPreviewGrid');
    const infoBar     = document.getElementById('kamarInfoBar');
    const infoText    = document.getElementById('kamarInfoText');
    let selectedFiles = [];

    ['dragenter','dragover','dragleave','drop'].forEach(evt =>
        dropZone.addEventListener(evt, e => { e.preventDefault(); e.stopPropagation(); }, false));
    dropZone.addEventListener('dragover',  () => dropZone.classList.add('drag-over'));
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', e => {
        dropZone.classList.remove('drag-over');
        handleFiles(Array.from(e.dataTransfer.files));
    });
    fotoInput.addEventListener('change', () => handleFiles(Array.from(fotoInput.files)));
    dropZone.addEventListener('click', e => { if (!e.target.closest('.btn-pilih-foto')) fotoInput.click(); });

    function handleFiles(newFiles) {
        const images  = newFiles.filter(f => f.type.startsWith('image/'));
        if (images.length !== newFiles.length) { alert('Hanya file gambar yang diperbolehkan!'); return; }
        const oversize = images.filter(f => f.size > MAX_MB * 1024 * 1024);
        if (oversize.length) { alert('Ukuran tiap foto maks ' + MAX_MB + ' MB!'); return; }
        const sisa = MAX_FILES - selectedFiles.length;
        if (sisa <= 0) { alert('Maksimal ' + MAX_FILES + ' foto!'); return; }
        selectedFiles = [...selectedFiles, ...images].slice(0, MAX_FILES);
        syncInput();
        renderPreviews();
    }

    function syncInput() {
        try {
            const dt = new DataTransfer();
            selectedFiles.forEach(f => dt.items.add(f));
            fotoInput.files = dt.files;
        } catch(e) { console.warn(e); }
    }

    function renderPreviews() {
        previewGrid.innerHTML  = '';
        infoBar.style.display  = selectedFiles.length ? 'flex' : 'none';
        infoText.textContent   = selectedFiles.length + ' dari ' + MAX_FILES + ' foto';
        for (let i = 1; i <= MAX_FILES; i++) {
            const dot = document.getElementById('kdot' + i);
            if (dot) dot.classList.toggle('filled', i <= selectedFiles.length);
        }
        selectedFiles.forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = ev => {
                const card = document.createElement('div');
                card.className = 'preview-card' + (i === 0 ? ' is-cover' : '');
                card.innerHTML = `
                    <div class="preview-img-wrap">
                        <img src="${ev.target.result}">
                        ${i === 0 ? '<div class="badge-cover"><i class="bi bi-star-fill"></i> Cover</div>' : ''}
                        <span class="badge-num">Foto ${i + 1}</span>
                        <button type="button" class="btn-remove" onclick="removeFile(${i})" title="Hapus">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="preview-info" style="flex-direction:column; align-items:stretch; gap:4px;">
                        <input type="text" placeholder="Nama foto, contoh: Ruang Kamar" 
                               class="form-control form-control-sm" 
                               style="font-size:.72rem; border-radius:.4rem; height:32px;"
                               value="${file.judul || ''}"
                               oninput="syncJudulFoto(${i}, this.value)">
                        <div class="d-flex justify-content-between mt-1">
                            <div class="preview-name text-truncate" style="max-width:80px;" title="${file.name}">${file.name}</div>
                            <div class="preview-size">${(file.size/1024/1024).toFixed(2)} MB</div>
                        </div>
                    </div>`;
                previewGrid.appendChild(card);
            };
            reader.readAsDataURL(file);
        });
    }

    window.syncJudulFoto = function(idx, val) {
        selectedFiles[idx].judul = val;
        renderHiddenJuduls();
    };

    function renderHiddenJuduls() {
        const container = document.getElementById('fotoKamarJudulContainer');
        container.innerHTML = '';
        selectedFiles.forEach((file, i) => {
            const hidden = document.createElement('input');
            hidden.type  = 'hidden';
            hidden.name  = 'foto_kamar_judul[]';
            hidden.value = file.judul || '';
            container.appendChild(hidden);
        });
    }

    window.removeFile = function(idx) {
        selectedFiles.splice(idx, 1);
        syncInput();
        renderPreviews();
        renderHiddenJuduls();
    };

    function addCustomFacility() {
        const input = document.getElementById('customFacInput');
        const val = input.value.trim();
        if (!val) return;

        // Cek apakah sudah ada di list checkbox standar
        const existingLabels = Array.from(document.querySelectorAll('.form-check-label')).map(l => l.textContent.trim().toLowerCase());
        if (existingLabels.includes(val.toLowerCase())) {
            alert('Fasilitas ini sudah ada di daftar!');
            input.value = '';
            return;
        }

        const container = document.getElementById('customFacContainer');
        const idx = Date.now();

        const col = document.createElement('div');
        col.className = 'col-6 col-md-4';
        col.id = 'custom-fac-' + idx;
        col.innerHTML = `
            <div class="form-check p-2 border rounded-3 d-flex align-items-center justify-content-between pe-2" style="font-size:.8rem; background:#fff7f5; border-color:#ffd2c7;">
                <div class="d-flex align-items-center">
                    <input class="form-check-input ms-0 me-2" type="checkbox" name="fasilitas[]" value="${val}" id="cf_${idx}" checked>
                    <label class="form-check-label" for="cf_${idx}">${val}</label>
                </div>
                <button type="button" class="btn btn-link text-danger p-0 ms-2" onclick="document.getElementById('custom-fac-${idx}').remove()">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>
        `;
        container.appendChild(col);
        input.value = '';
        input.focus();
    }
</script>
@endpush