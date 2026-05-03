<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Kost - KostFinder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

  <style>
    :root {
      --sidebar-w: 250px;
      --sidebar-col: 78px;
      --primary: #e8401c;
      --primary-light: #fff5f2;
      --primary-mid: #ffd0c0;
      --dark: #1e2d3d;
      --bg: #f4f7fb;
      --line: #e8edf4;
      --muted: #8fa3b8;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
    body { background: var(--bg); min-height: 100vh; overflow-x: hidden; }

    /* ── SIDEBAR ── */
    .sidebar { width: var(--sidebar-w); min-height: 100vh; background: var(--dark); position: fixed; top: 0; left: 0; display: flex; flex-direction: column; z-index: 200; transition: all .3s ease; overflow: hidden; }
    .sidebar.collapsed { width: var(--sidebar-col); }
    .sidebar-brand { padding: 1.2rem .9rem; border-bottom: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: .6rem; min-height: 70px; white-space: nowrap; }
    .brand-icon { width: 38px; height: 38px; flex-shrink: 0; background: linear-gradient(135deg,#e8401c,#ff7043); border-radius: .7rem; display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #fff; box-shadow: 0 4px 12px rgba(232,64,28,.4); }
    .brand-text { overflow: hidden; transition: .2s; }
    .brand-text .name { font-size: 1.2rem; font-weight: 800; color: #fff; line-height: 1; }
    .brand-text .name span { color: #ff7a45; }
    .brand-text .sub { font-size: .72rem; color: #8aa0b7; margin-top: .25rem; }
    .sidebar.collapsed .brand-text, .sidebar.collapsed .menu-label, .sidebar.collapsed .menu-item span, .sidebar.collapsed .user-info { opacity: 0; width: 0; overflow: hidden; }
    .sidebar-menu { padding: .8rem .6rem; flex: 1; }
    .menu-label { font-size: .68rem; font-weight: 700; color: #7f96ad; padding: .45rem .55rem; letter-spacing: .08em; }
    .menu-item { display: flex; align-items: center; gap: .7rem; padding: .72rem .8rem; border-radius: .75rem; color: #adc0cf; text-decoration: none; font-size: .88rem; font-weight: 600; margin-bottom: .2rem; transition: .2s; white-space: nowrap; border: none; background: transparent; width: 100%; text-align: left; cursor: pointer; }
    .menu-item i { font-size: 1rem; width: 20px; flex-shrink: 0; }
    .menu-item:hover { background: rgba(255,255,255,.08); color: #fff; }
    .menu-item.active { background: linear-gradient(135deg,#e8401c,#ff7043); color: #fff; box-shadow: 0 8px 18px rgba(232,64,28,.25); }
    .menu-item.logout { color: #ff8d8d; }
    .menu-item.logout:hover { background: rgba(255,95,95,.12); color: #fff; }
    .sidebar-user { padding: .9rem; border-top: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: .7rem; background: rgba(255,255,255,.03); }
    .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: #fff; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
    .user-name { color: #fff; font-size: .84rem; font-weight: 700; }
    .user-role { color: #8aa0b7; font-size: .72rem; }

    /* ── MAIN ── */
    .main { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; transition: .3s ease; }
    .main.collapsed { margin-left: var(--sidebar-col); }

    /* ── TOPBAR ── */
    .topbar { height: 72px; background: #fff; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; position: sticky; top: 0; z-index: 100; }
    .topbar-left { display: flex; align-items: center; gap: 1rem; }
    .topbar-left h5 { margin: 0; font-size: 1.05rem; font-weight: 800; color: var(--dark); }
    .topbar-left p { margin: 0; font-size: .78rem; color: var(--muted); }
    .topbar-right { display: flex; align-items: center; gap: .65rem; }
    .search-box { display: flex; align-items: center; gap: .55rem; background: #f7f9fc; border: 1px solid var(--line); border-radius: .8rem; padding: .55rem .85rem; width: 250px; }
    .search-box input { border: none; background: transparent; outline: none; width: 100%; font-size: .85rem; }
    .icon-btn { width: 40px; height: 40px; border-radius: .8rem; border: 1px solid var(--line); background: #fff; display: flex; align-items: center; justify-content: center; color: #667789; text-decoration: none; font-size: 1rem; }
    .icon-btn:hover { background: #f7f9fc; color: #333; }
    .notif-dot { position: absolute; top: 6px; right: 6px; width: 8px; height: 8px; background: #e8401c; border-radius: 50%; border: 2px solid #fff; }

    /* ── CONTENT ── */
    .content { padding: 1.5rem; flex: 1; }

    /* ── FORM CARD ── */
    .form-card { background: #fff; border-radius: 1rem; border: 1px solid var(--line); box-shadow: 0 6px 20px rgba(0,0,0,.04); padding: 1.5rem; margin-bottom: 1rem; }
    .hover-opacity-100:hover { opacity: 1 !important; transition: .2s; }
    .form-card h6 { font-weight: 800; color: var(--dark); font-size: .95rem; margin-bottom: 1rem; padding-bottom: .8rem; border-bottom: 1px solid #f0f3f8; display: flex; align-items: center; gap: .4rem; }
    .form-label { font-size: .82rem; font-weight: 700; color: #344054; margin-bottom: .35rem; }
    .form-control, .form-select { font-size: .85rem; border-color: var(--line); border-radius: .75rem; padding: .65rem .9rem; min-height: 46px; }
    textarea.form-control { min-height: auto; }
    .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(232,64,28,.1); }
    .form-check { background: #f8fafc; border: 1px solid #edf2f7; border-radius: .75rem; padding: .7rem .8rem; height: 100%; transition: .2s; }
    .form-check:hover { border-color: #ffd2c7; background: #fff7f5; }
    .form-check-input:checked { background-color: var(--primary); border-color: var(--primary); }
    .form-check-label { font-size: .82rem; font-weight: 600; color: #344054; cursor: pointer; }

    /* ── MAP ── */
    #map { height: 300px; border-radius: .8rem; border: 1px solid var(--line); overflow: hidden; }

    /* Map status bar */
    .map-status-bar {
      display: flex; align-items: center; gap: .5rem;
      padding: .55rem .85rem;
      border-radius: .65rem;
      font-size: .78rem; font-weight: 600;
      margin-bottom: .6rem;
      transition: all .3s;
    }
    .map-status-bar.detecting { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .map-status-bar.success   { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .map-status-bar.warning   { background: #fff7ed; color: #9a3412; border: 1px solid #fed7aa; }
    .map-status-bar.info      { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }

    /* Koordinat display */
    .koordinat-box {
      display: flex; gap: .5rem; margin-top: .6rem;
    }
    .koordinat-item {
      flex: 1; background: #f8fafc; border: 1px solid var(--line);
      border-radius: .65rem; padding: .5rem .75rem;
    }
    .koordinat-item label { font-size: .68rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .04em; display: block; margin-bottom: 2px; }
    .koordinat-item span { font-size: .8rem; font-weight: 700; color: var(--dark); font-family: monospace; }

    /* Tombol refresh lokasi */
    .btn-refresh-map {
      display: inline-flex; align-items: center; gap: .4rem;
      background: var(--primary-light); color: var(--primary);
      border: 1px solid var(--primary-mid);
      border-radius: .65rem; padding: .45rem .9rem;
      font-size: .78rem; font-weight: 700;
      cursor: pointer; transition: .2s; margin-top: .6rem;
    }
    .btn-refresh-map:hover { background: var(--primary-mid); }

    .btn-submit { background: linear-gradient(135deg,#e8401c,#ff7043); color: #fff; font-weight: 700; border: 0; border-radius: .75rem; padding: .7rem 1.5rem; font-size: .88rem; cursor: pointer; box-shadow: 0 6px 16px rgba(232,64,28,.22); transition: .2s; display: inline-flex; align-items: center; gap: .4rem; }
    .btn-submit:hover { background: linear-gradient(135deg,#cb3518,#e8401c); transform: translateY(-1px); }

    .owner-footer { background: #fff; border-top: 1px solid var(--line); padding: .9rem 1.5rem; text-align: center; color: var(--muted); font-size: .76rem; }

    /* ── UPLOAD FOTO ── */
    .upload-section { position: relative; }
    .drop-zone { border: 2px dashed #d8e2ef; border-radius: 1.1rem; background: #fafcff; padding: 2.2rem 1.5rem; text-align: center; cursor: pointer; transition: all .25s ease; position: relative; overflow: hidden; }
    .drop-zone:hover, .drop-zone.drag-over { border-color: var(--primary); background: #fff8f6; transform: scale(1.005); }
    .drop-zone.drag-over { border-style: solid; box-shadow: 0 0 0 4px rgba(232,64,28,.1); }
    .drop-zone-icon { width: 68px; height: 68px; margin: 0 auto 1rem; background: linear-gradient(135deg, #fff5f2, #ffe8e0); border-radius: 1rem; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: var(--primary); box-shadow: 0 8px 20px rgba(232,64,28,.12); transition: .25s; }
    .drop-zone:hover .drop-zone-icon { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(232,64,28,.2); }
    .drop-zone-title { font-size: 1rem; font-weight: 800; color: var(--dark); margin-bottom: .35rem; }
    .drop-zone-sub { font-size: .8rem; color: var(--muted); margin-bottom: 1.2rem; line-height: 1.6; }
    .btn-pilih-foto { display: inline-flex; align-items: center; gap: .5rem; background: linear-gradient(135deg,#e8401c,#ff7043); color: #fff; font-weight: 700; font-size: .82rem; padding: .6rem 1.3rem; border-radius: .75rem; border: none; box-shadow: 0 6px 16px rgba(232,64,28,.22); cursor: pointer; transition: .2s; }
    .btn-pilih-foto:hover { background: linear-gradient(135deg,#cb3518,#e8401c); transform: translateY(-1px); }
    .drop-zone-hint { font-size: .72rem; color: #b0bfcc; margin-top: .85rem; }
    #fotoInput { display: none; }
    .foto-info-bar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .5rem; margin-top: 1rem; padding: .65rem .9rem; background: var(--primary-light); border: 1px solid var(--primary-mid); border-radius: .75rem; }
    .foto-info-left { display: flex; align-items: center; gap: .5rem; font-size: .78rem; color: #9a3412; font-weight: 600; }
    .foto-counter { display: flex; align-items: center; gap: .4rem; font-size: .78rem; font-weight: 700; color: #9a3412; }
    .counter-dot { width: 8px; height: 8px; border-radius: 50%; background: #d8cbc8; transition: .2s; }
    .counter-dot.filled { background: var(--primary); }
    .preview-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 1.2rem; }
    .preview-card { position: relative; border-radius: 1rem; overflow: hidden; background: #f8fafc; border: 1.5px solid var(--line); box-shadow: 0 4px 16px rgba(15,23,42,.06); transition: .25s ease; animation: popIn .3s cubic-bezier(.34,1.56,.64,1) both; }
    @keyframes popIn { from { opacity: 0; transform: scale(.88); } to { opacity: 1; transform: scale(1); } }
    .preview-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(15,23,42,.1); border-color: #cdd6e4; }
    .preview-card.is-cover { border-color: var(--primary); box-shadow: 0 4px 16px rgba(232,64,28,.15); }
    .preview-img-wrap { position: relative; height: 180px; overflow: hidden; background: #edf2f7; }
    .preview-img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .35s ease; }
    .preview-card:hover .preview-img-wrap img { transform: scale(1.06); }
    .preview-img-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(17,24,39,.55) 0%, transparent 55%); opacity: 0; transition: .25s; }
    .preview-card:hover .preview-img-overlay { opacity: 1; }
    .badge-cover { position: absolute; top: 10px; left: 10px; background: linear-gradient(135deg,#e8401c,#ff7043); color: #fff; font-size: .65rem; font-weight: 800; padding: .3rem .7rem; border-radius: 999px; box-shadow: 0 4px 12px rgba(232,64,28,.3); display: flex; align-items: center; gap: .3rem; letter-spacing: .02em; }
    .badge-num { position: absolute; top: 10px; right: 44px; background: rgba(17,24,39,.65); color: #fff; font-size: .65rem; font-weight: 700; padding: .28rem .55rem; border-radius: 999px; backdrop-filter: blur(6px); }
    .btn-remove { position: absolute; top: 8px; right: 8px; width: 32px; height: 32px; border: none; border-radius: 50%; background: rgba(17,24,39,.65); color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: .85rem; backdrop-filter: blur(6px); transition: .2s; }
    .btn-remove:hover { background: rgba(220,38,38,.9); transform: scale(1.1); }
    .btn-set-cover { position: absolute; bottom: 10px; right: 10px; background: rgba(255,255,255,.9); color: #555; font-size: .68rem; font-weight: 700; padding: .28rem .65rem; border-radius: 999px; border: none; cursor: pointer; display: flex; align-items: center; gap: .3rem; backdrop-filter: blur(6px); transition: .2s; opacity: 0; }
    .preview-card:hover .btn-set-cover { opacity: 1; }
    .btn-set-cover:hover { background: #fff; color: var(--primary); }
    .preview-card.is-cover .btn-set-cover { display: none; }
    .preview-info { padding: .75rem .9rem; display: flex; align-items: center; justify-content: space-between; gap: .5rem; }
    .preview-name { font-size: .8rem; font-weight: 700; color: var(--dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; }
    .preview-size { font-size: .7rem; color: var(--muted); flex-shrink: 0; }
    .tips-box { margin-top: 1rem; background: #f0f9ff; border: 1px solid #bae6fd; border-left: 3px solid #0ea5e9; border-radius: .75rem; padding: .8rem 1rem; }
    .tips-box .tips-title { font-size: .78rem; font-weight: 800; color: #0369a1; margin-bottom: .35rem; display: flex; align-items: center; gap: .35rem; }
    .tips-box ul { margin: 0; padding-left: 1.1rem; }
    .tips-box ul li { font-size: .75rem; color: #0c4a6e; line-height: 1.8; }

    /* ── FASILITAS FOTO ROWS ── */
    .facility-row { display:grid; grid-template-columns:120px 1fr auto; gap:.75rem; align-items:start; background:#f8fafc; border:1px solid var(--line); border-radius:.85rem; padding:.75rem; margin-bottom:.65rem; animation: popIn .3s cubic-bezier(.34,1.56,.64,1) both; }
    .facility-row:hover { border-color:#dce5f0; background:#fff; }
    .facility-img-preview { width:120px; height:85px; border-radius:.6rem; overflow:hidden; background:linear-gradient(135deg,#f0f4f8,#e8edf4); border:1.5px dashed #d0d8e4; display:flex; align-items:center; justify-content:center; flex-direction:column; gap:.35rem; cursor:pointer; position:relative; transition:.2s; }
    .facility-img-preview:hover { border-color:var(--primary); background:#fff8f6; }
    .facility-img-preview img { width:100%; height:100%; object-fit:cover; position:absolute; inset:0; }
    .facility-img-preview .upload-icon { font-size:1.3rem; color:#b0bfcc; transition:.2s; }
    .facility-img-preview .upload-hint { font-size:.62rem; color:#b0bfcc; font-weight:600; }
    .facility-img-preview:hover .upload-icon { color:var(--primary); }
    .facility-right { display:flex; flex-direction:column; gap:.5rem; }
    .facility-label-input { font-size:.82rem; border:1px solid var(--line); border-radius:.65rem; padding:.55rem .8rem; width:100%; outline:none; transition:.2s; color:var(--dark); font-weight:600; }
    .facility-label-input:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(232,64,28,.1); }
    .facility-remove-btn { width:34px; height:34px; border:none; border-radius:.6rem; background:#fef2f2; color:#dc2626; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:.85rem; transition:.2s; flex-shrink:0; margin-top:0; }
    .facility-remove-btn:hover { background:#dc2626; color:#fff; }
    @media (max-width: 600px) { .facility-row { grid-template-columns:90px 1fr auto; } .facility-img-preview { width:90px; height:70px; } }

    @media (max-width: 991px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.show { transform: translateX(0); }
      .main { margin-left: 0 !important; }
      .search-box { width: 160px; }
      .preview-grid { grid-template-columns: repeat(2, 1fr); }
    }
  </style>
</head>
<body>

  @include('owner._sidebar')

  <div class="main" id="mainContent">
    @include('owner._navbar')

    <div class="content">

      {{-- BREADCRUMB --}}
      <div class="d-flex align-items-center gap-2 mb-3" style="font-size:.82rem;color:var(--muted);">
        <a href="{{ route('owner.kost.index') }}" style="color:var(--muted);text-decoration:none;">Data Kost Saya</a>
        <i class="bi bi-chevron-right" style="font-size:.7rem;"></i>
        <span style="color:var(--dark);font-weight:700;">Tambah Kost</span>
      </div>

      @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:.83rem;border-radius:.75rem;">
          <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('owner.kost.store') }}" method="POST" enctype="multipart/form-data" id="kostForm">
        @csrf

        <div class="row g-3">

          {{-- ══════════════════════════════
               KOLOM KIRI
          ══════════════════════════════ --}}
          <div class="col-12 col-lg-8">

            {{-- Informasi Dasar --}}
            <div class="form-card">
              <h6><i class="bi bi-info-circle" style="color:var(--primary)"></i> Informasi Dasar</h6>

              <div class="mb-3">
                <label class="form-label">Nama Kost <span class="text-danger">*</span></label>
                <input type="text" name="nama_kost" class="form-control"
                       placeholder="Contoh: Kost Melati Indah"
                       value="{{ old('nama_kost') }}" required>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                  <select name="kota" class="form-select" required>
                    <option value="">-- Pilih Kota --</option>
                    @foreach(['Surabaya','Malang','Sidoarjo','Gresik','Kediri','Jember','Banyuwangi','Mojokerto','Madiun','Pasuruan','Blitar','Probolinggo','Tulungagung','Lumajang','Jombang','Nganjuk','Lamongan','Bojonegoro','Tuban','Magetan','Ngawi','Ponorogo','Pacitan','Trenggalek','Bondowoso','Situbondo','Pamekasan','Sampang','Bangkalan','Sumenep'] as $kota)
                      <option value="{{ $kota }}" {{ old('kota', auth()->user()->kota) == $kota ? 'selected' : '' }}>{{ $kota }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Tipe Kost <span class="text-danger">*</span></label>
                  <select name="tipe_kost" class="form-select" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="Putra"  {{ old('tipe_kost') == 'Putra'  ? 'selected' : '' }}>Putra</option>
                    <option value="Putri"  {{ old('tipe_kost') == 'Putri'  ? 'selected' : '' }}>Putri</option>
                    <option value="Campur" {{ old('tipe_kost') == 'Campur' ? 'selected' : '' }}>Campur</option>
                  </select>
                </div>
              </div>

              <div class="mt-3">
                <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                <textarea name="alamat" id="alamat" class="form-control" rows="2"
                          placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan" required>{{ old('alamat', auth()->user()->alamat) }}</textarea>
              </div>

              <div class="mt-3">
                <label class="form-label">Deskripsi Kost</label>
                <textarea name="deskripsi" class="form-control" rows="3"
                          placeholder="Ceritakan tentang kost kamu...">{{ old('deskripsi') }}</textarea>
              </div>
            </div>

            {{-- Fasilitas --}}
            <div class="form-card">
              <h6><i class="bi bi-check2-square" style="color:var(--primary)"></i> Fasilitas Umum Kost</h6>
              <p style="font-size:.76rem;color:var(--muted);margin-top:-.5rem;margin-bottom:.85rem;">Pilih fasilitas yang digunakan bersama seluruh penghuni.</p>
              <div class="row g-2" id="facilityList">
                @foreach(['WiFi/Internet','Parkir Motor','Parkir Mobil','Air Minum','Dapur','Laundry','CCTV','Mushola','Ruang Tamu','Jemuran','Ruang Santai','Keamanan 24 Jam'] as $f)
                  <div class="col-6 col-md-4" id="fac-item-{{ $loop->index }}">
                    <div class="form-check d-flex align-items-center justify-content-between pe-2">
                      <div class="d-flex align-items-center">
                        <input class="form-check-input" type="checkbox" name="fasilitas[]"
                               value="{{ $f }}" id="f_{{ $loop->index }}"
                               {{ is_array(old('fasilitas')) && in_array($f, old('fasilitas')) ? 'checked' : '' }}>
                        <label class="form-check-label ms-2" for="f_{{ $loop->index }}">{{ $f }}</label>
                      </div>
                      <button type="button" class="btn btn-link text-danger p-0 ms-2 opacity-50 hover-opacity-100" onclick="document.getElementById('fac-item-{{ $loop->index }}').remove()" title="Hapus dari daftar">
                        <i class="bi bi-x-circle"></i>
                      </button>
                    </div>
                  </div>
                @endforeach
              </div>

              {{-- Section untuk input fasilitas kustom --}}
              <div class="mt-4 pt-3 border-top">
                <label class="form-label" style="font-size:.78rem;color:var(--muted);">Gak nemu fasilitasmu? Tambah sendiri di sini:</label>
                <div class="input-group input-group-sm">
                  <input type="text" id="customFacInput" class="form-control" placeholder="Contoh: Kolam Renang, Smart TV..." style="border-radius:.6rem 0 0 .6rem;">
                  <button type="button" class="btn btn-primary px-3" onclick="addCustomFacility()" style="border-radius:0 .6rem .6rem 0;background:var(--primary);border:none;">
                    <i class="bi bi-plus-lg"></i> Tambah
                  </button>
                </div>
                <div id="customFacContainer" class="row g-2 mt-2">
                  {{-- Fasilitas kustom akan ditambahkan di sini --}}
                </div>
              </div>
            </div>


            {{-- Aturan --}}
            <div class="form-card">
              <h6><i class="bi bi-clipboard-check" style="color:var(--primary)"></i> Aturan Kost</h6>
              <textarea name="aturan" class="form-control" rows="3"
                        placeholder="Contoh: Tidak boleh membawa tamu menginap, jam malam 22.00...">{{ old('aturan') }}</textarea>
            </div>

            {{-- Galeri Foto --}}
            <div class="form-card">
              <h6><i class="bi bi-images" style="color:var(--primary)"></i> Galeri Foto (Properti & Fasilitas)</h6>
              <div class="upload-section">
                <div class="drop-zone" id="dropZone">
                  <div class="drop-zone-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                  <div class="drop-zone-title">Upload Foto Properti</div>
                  <div class="drop-zone-sub">Seret & lepas foto di sini, atau klik tombol di bawah<br>untuk memilih dari galeri perangkat kamu</div>
                  <button type="button" class="btn-pilih-foto" onclick="document.getElementById('fotoInput').click()">
                    <i class="bi bi-folder2-open"></i> Pilih Foto
                  </button>
                  <div class="drop-zone-hint"><i class="bi bi-info-circle me-1"></i>Maks. <strong>6 foto</strong> &bull; Format: JPG, PNG, WEBP &bull; Ukuran maks. <strong>2 MB</strong> per foto</div>
                </div>
                <input type="file" name="foto_kost[]" id="fotoInput" accept="image/jpeg,image/png,image/webp" multiple>
                <div id="namaFotoInputs"></div>
                <div class="foto-info-bar" id="fotoInfoBar" style="display:none;">
                  <div class="foto-info-left"><i class="bi bi-images"></i><span id="fotoInfoText">0 dari 6 foto dipilih</span></div>
                  <div class="foto-counter" id="fotoDots">
                    <div class="counter-dot" id="dot1"></div><div class="counter-dot" id="dot2"></div>
                    <div class="counter-dot" id="dot3"></div><div class="counter-dot" id="dot4"></div>
                    <div class="counter-dot" id="dot5"></div><div class="counter-dot" id="dot6"></div>
                  </div>
                </div>
                <div class="preview-grid" id="previewGrid"></div>
                <div class="tips-box">
                  <div class="tips-title"><i class="bi bi-lightbulb-fill" style="color:#f59e0b"></i> Tips foto profesional ala Mamikos</div>
                  <ul>
                    <li>Foto <strong>tampak depan bangunan</strong> sebagai foto utama/cover</li>
                    <li>Pastikan pencahayaan <strong>terang & natural</strong>, hindari foto blur</li>
                    <li>Ambil dari sudut yang memperlihatkan <strong>luas ruangan</strong></li>
                    <li>Foto kedua bisa area <strong>kamar, dapur, atau fasilitas</strong> unggulan</li>
                  </ul>
                </div>
              </div>
            </div>

          </div>
          {{-- END KOLOM KIRI --}}

          {{-- ══════════════════════════════
               KOLOM KANAN
          ══════════════════════════════ --}}
          <div class="col-12 col-lg-4">

            {{-- ✅ HARGA — harga_sampai tidak wajib --}}
            <div class="form-card">
              <h6><i class="bi bi-cash" style="color:var(--primary)"></i> Harga</h6>

              <label class="form-label">Harga Per Bulan <span class="text-danger">*</span></label>
              <div class="row g-2 mb-1">
                <div class="col-12">
                  <div class="input-group">
                    <span class="input-group-text" style="font-size:.78rem;background:#f8fafd;border-color:var(--line);">Rp</span>
                    <input type="number" name="harga_mulai" class="form-control"
                           placeholder="Contoh: 500000"
                           value="{{ old('harga_mulai') }}" required min="0">
                  </div>
                  <div class="form-text" style="font-size:.7rem;color:var(--muted);">Harga mulai dari (wajib diisi)</div>
                </div>
              </div>

              {{-- Toggle harga sampai --}}
              <div class="d-flex align-items-center justify-content-between mt-2 mb-1">
                <label class="form-label mb-0" style="font-size:.78rem;">
                  Tambah rentang harga?
                  <span style="font-size:.68rem;color:var(--muted);font-weight:500;">(opsional)</span>
                </label>
                <div class="form-check form-switch mb-0">
                  <input class="form-check-input" type="checkbox" id="toggleHargaSampai"
                         {{ old('harga_sampai') ? 'checked' : '' }}
                         onchange="document.getElementById('sectionHargaSampai').style.display = this.checked ? 'block' : 'none'">
                </div>
              </div>
              <div id="sectionHargaSampai" style="display:{{ old('harga_sampai') ? 'block' : 'none' }};">
                <div class="input-group mb-1">
                  <span class="input-group-text" style="font-size:.78rem;background:#f8fafd;border-color:var(--line);">Rp</span>
                  <input type="number" name="harga_sampai" class="form-control"
                         placeholder="Contoh: 1200000"
                         value="{{ old('harga_sampai') }}" min="0">
                </div>
                <div class="form-text" style="font-size:.7rem;color:var(--muted);">Harga tertinggi (untuk kost dengan variasi kamar)</div>
              </div>

              <div class="form-text mt-1 mb-3" style="font-size:.72rem;color:var(--muted);">
                <i class="bi bi-info-circle me-1"></i>Harga dapat berbeda tiap kamar
              </div>

              <hr style="border-color:#f0f3f8;margin:.5rem 0 1rem;">



              {{-- Harga harian --}}
              <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="form-label mb-0">Harga Per Hari <span style="font-size:.7rem;color:var(--muted);font-weight:500;">(opsional)</span></label>
                <div class="form-check form-switch mb-0">
                  <input class="form-check-input" type="checkbox" id="toggleHarian"
                         name="ada_harian" value="1"
                         {{ old('ada_harian') ? 'checked' : '' }}
                         onchange="document.getElementById('sectionHarian').style.display = this.checked ? 'block' : 'none'">
                </div>
              </div>
              <div id="sectionHarian" style="display:{{ old('ada_harian') ? 'block' : 'none' }};">
                <div class="row g-2 mb-1">
                  <div class="col-6">
                    <div class="input-group">
                      <span class="input-group-text" style="font-size:.78rem;background:#f8fafd;border-color:var(--line);">Rp</span>
                      <input type="number" name="harga_harian_mulai" class="form-control"
                             placeholder="75000" value="{{ old('harga_harian_mulai') }}">
                    </div>
                    <div class="form-text" style="font-size:.7rem;">Mulai dari</div>
                  </div>
                  <div class="col-6">
                    <div class="input-group">
                      <span class="input-group-text" style="font-size:.78rem;background:#f8fafd;border-color:var(--line);">Rp</span>
                      <input type="number" name="harga_harian_sampai" class="form-control"
                             placeholder="150000" value="{{ old('harga_harian_sampai') }}">
                    </div>
                    <div class="form-text" style="font-size:.7rem;">Sampai (opsional)</div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Status --}}
            <div class="form-card">
              <h6><i class="bi bi-toggle-on" style="color:var(--primary)"></i> Status</h6>
              <select name="status" class="form-select">
                <option value="aktif"    {{ old('status') == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
              </select>
            </div>

            {{-- ✅ PETA — auto detect dari data owner --}}
            <div class="form-card">
              <h6><i class="bi bi-geo-alt" style="color:var(--primary)"></i> Lokasi di Peta</h6>
              <p style="font-size:.75rem;color:var(--muted);margin-bottom:.7rem;">
                Lokasi otomatis dari alamat yang kamu daftarkan. Klik peta atau geser pin untuk mengubah titik lokasi.
              </p>

              {{-- Status bar geocoding --}}
              <div class="map-status-bar detecting" id="mapStatusBar">
                <i class="bi bi-arrow-repeat" id="mapStatusIcon" style="animation:spin .8s linear infinite;"></i>
                <span id="mapStatusText">Mendeteksi lokasi dari data akun kamu...</span>
              </div>

              {{-- Peta --}}
              <div id="map"></div>

              {{-- Koordinat display --}}
              <div class="koordinat-box">
                <div class="koordinat-item">
                  <label>Latitude</label>
                  <span id="latDisplay">—</span>
                </div>
                <div class="koordinat-item">
                  <label>Longitude</label>
                  <span id="lngDisplay">—</span>
                </div>
              </div>

              {{-- Tombol refresh lokasi dari alamat --}}
              <div class="d-flex flex-wrap gap-2 mt-2">
                <button type="button" class="btn-refresh-map" onclick="getLocationGPS()" style="background: #e0f2fe; color: #0369a1; border-color: #bae6fd;">
                  <i class="bi bi-geo-fill"></i> Gunakan GPS Saya
                </button>
                <button type="button" class="btn-refresh-map" onclick="geocodeFromOwnerAddress()">
                  <i class="bi bi-arrow-clockwise"></i> Dari Alamat
                </button>
              </div>

              {{-- Hidden inputs untuk form submit --}}
              <input type="hidden" name="latitude"  id="latitude"  value="{{ old('latitude',  auth()->user()->latitude  ?? '') }}">
              <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', auth()->user()->longitude ?? '') }}">
            </div>

          </div>
          {{-- END KOLOM KANAN --}}

        </div>

        <div class="d-flex gap-2 mt-2 mb-4">
          <button type="submit" class="btn-submit">
            <i class="bi bi-check-lg"></i> Simpan Kost
          </button>
          <a href="{{ route('owner.kost.index') }}"
             class="btn btn-outline-secondary"
             style="border-radius:.75rem;padding:.7rem 1.2rem;font-size:.85rem;">
            Batal
          </a>
        </div>

      </form>
    </div>

    <footer class="owner-footer">
      © {{ date('Y') }} KostFinder — Panel Pemilik Kost. All rights reserved.
    </footer>
  </div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
  </style>

  <script>
    // ══════════════════════════════════════════════════════════════
    //  SIDEBAR
    // ══════════════════════════════════════════════════════════════
    function toggleSidebar() {
      const s = document.getElementById('sidebar');
      const m = document.getElementById('mainContent');
      if (window.innerWidth <= 991) { s?.classList.toggle('show'); }
      else { s?.classList.toggle('collapsed'); m?.classList.toggle('collapsed'); }
    }

    // ══════════════════════════════════════════════════════════════
    //  DATA OWNER — dari Laravel (diambil saat halaman render)
    // ══════════════════════════════════════════════════════════════
    const OWNER = {
      alamat    : @json(auth()->user()->alamat    ?? ''),
      kecamatan : @json(auth()->user()->kecamatan ?? ''),
      kelurahan : @json(auth()->user()->kelurahan ?? ''),
      kota      : @json(auth()->user()->kota      ?? ''),
      provinsi  : @json(auth()->user()->provinsi  ?? 'Jawa Timur'),
      kode_pos  : @json(auth()->user()->kode_pos  ?? ''),
      latitude  : {{ is_numeric(auth()->user()->latitude)  && auth()->user()->latitude  != 0 ? auth()->user()->latitude  : 'null' }},
      longitude : {{ is_numeric(auth()->user()->longitude) && auth()->user()->longitude != 0 ? auth()->user()->longitude : 'null' }},
    };

    // ══════════════════════════════════════════════════════════════
    //  MAP SETUP — Leaflet
    // ══════════════════════════════════════════════════════════════

    // Koordinat awal: pakai old() kalau ada (setelah error validasi),
    // kalau tidak pakai koordinat owner, kalau tidak ada pakai default Jawa Timur
    const OLD_LAT = {{ old('latitude') ? (float) old('latitude') : 'null' }};
    const OLD_LNG = {{ old('longitude') ? (float) old('longitude') : 'null' }};

    const DEFAULT_LAT = -7.4478;
    const DEFAULT_LNG = 112.7183;

    // Pakai old() dulu, lalu koordinat owner, lalu default
    let startLat = OLD_LAT || OWNER.latitude  || DEFAULT_LAT;
    let startLng = OLD_LNG || OWNER.longitude || DEFAULT_LNG;
    let startZoom = (OLD_LAT || OWNER.latitude) ? 16 : 10;

    const map = L.map('map').setView([startLat, startLng], startZoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    const latInput  = document.getElementById('latitude');
    const lngInput  = document.getElementById('longitude');
    const latDisplay = document.getElementById('latDisplay');
    const lngDisplay = document.getElementById('lngDisplay');
    let marker = null;

    // ── Set marker di peta ──
    function setMarker(lat, lng) {
      latInput.value  = lat.toFixed(7);
      lngInput.value  = lng.toFixed(7);
      latDisplay.textContent = lat.toFixed(5);
      lngDisplay.textContent = lng.toFixed(5);

      if (marker) {
        marker.setLatLng([lat, lng]);
      } else {
        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        marker.on('dragend', function () {
          const pos = marker.getLatLng();
          latInput.value  = pos.lat.toFixed(7);
          lngInput.value  = pos.lng.toFixed(7);
          latDisplay.textContent = pos.lat.toFixed(5);
          lngDisplay.textContent = pos.lng.toFixed(5);
          setMapStatus('success', 'bi-check-circle', 'Koordinat diperbarui dari pin', false);
        });
      }
      marker.bindPopup('📍 Lokasi kost kamu').openPopup();
    }

    // ── Status bar helper ──
    function setMapStatus(type, icon, text, spinning) {
      const bar  = document.getElementById('mapStatusBar');
      const ico  = document.getElementById('mapStatusIcon');
      const txt  = document.getElementById('mapStatusText');
      bar.className = 'map-status-bar ' + type;
      ico.className = 'bi ' + icon;
      ico.style.animation = spinning ? 'spin .8s linear infinite' : 'none';
      txt.textContent = text;
    }

    // ── Klik peta → set lokasi ──
    map.on('click', function (e) {
      setMarker(e.latlng.lat, e.latlng.lng);
      map.setView([e.latlng.lat, e.latlng.lng], 17);
      setMapStatus('success', 'bi-geo-alt-fill', 'Lokasi dipilih manual dari peta', false);
    });

    setTimeout(() => map.invalidateSize(), 300);

    // ══════════════════════════════════════════════════════════════
    //  ✅ GEOCODING — Nominatim
    //  Gabungkan alamat owner jadi 1 query yang bersih
    // ══════════════════════════════════════════════════════════════
    async function geocodeQuery(query) {
      // Bersihkan query dari noise umum
      let clean = query
        .replace(/rt[\s\.]*\d+/gi, '').replace(/rw[\s\.]*\d+/gi, '')
        .replace(/no[\s\.]*\d+/gi, '').replace(/blok[\s\.]*[a-z0-9]+/gi, '')
        .replace(/\b(kecamatan|kec\.?|kabupaten|kab\.?|provinsi|prov\.?|kelurahan|kel\.?|desa)\b/gi, ' ')
        .replace(/\s+/g, ' ').trim();

      if (clean.length < 4) return null;

      try {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(clean)}&countrycodes=id&limit=1&addressdetails=1`;
        const res = await fetch(url, { headers: { 'Accept-Language': 'id' } });
        const data = await res.json();
        return data.length > 0 ? data[0] : null;
      } catch (e) {
        return null;
      }
    }

    // ══════════════════════════════════════════════════════════════
    //  ✅ AUTO DETECT DARI DATA OWNER SAAT HALAMAN LOAD
    // ══════════════════════════════════════════════════════════════
    async function geocodeFromOwnerAddress() {
      setMapStatus('detecting', 'bi-arrow-repeat', 'Mendeteksi lokasi dari alamat kamu...', true);

      // ── Strategi 1: Pakai latitude/longitude yang sudah tersimpan ──
      if (OWNER.latitude && OWNER.longitude) {
        setMarker(OWNER.latitude, OWNER.longitude);
        map.setView([OWNER.latitude, OWNER.longitude], 17);
        setMapStatus('success', 'bi-check-circle-fill', 'Lokasi dari data akun kamu ✓', false);
        return;
      }

      // ── Strategi 2: Geocode dari alamat lengkap ──
      // Susun query dari yang paling spesifik ke paling umum
      const queries = [];

      // Query 1: Alamat + kelurahan + kecamatan + kota
      if (OWNER.alamat && OWNER.kota) {
        let q = [OWNER.alamat, OWNER.kelurahan, OWNER.kecamatan, OWNER.kota, 'Jawa Timur'].filter(Boolean).join(', ');
        queries.push(q);
      }

      // Query 2: Kecamatan + kota (lebih general)
      if (OWNER.kecamatan && OWNER.kota) {
        queries.push(`${OWNER.kecamatan}, ${OWNER.kota}, Jawa Timur`);
      }

      // Query 3: Hanya kota
      if (OWNER.kota) {
        queries.push(`${OWNER.kota}, Jawa Timur`);
      }

      for (const q of queries) {
        const result = await geocodeQuery(q);
        if (result) {
          const lat = parseFloat(result.lat);
          const lng = parseFloat(result.lon);
          setMarker(lat, lng);
          map.setView([lat, lng], OWNER.kecamatan ? 15 : 12);
          setMapStatus('success', 'bi-check-circle-fill',
            `Lokasi ditemukan: ${OWNER.kecamatan || OWNER.kota} ✓`, false);
          return;
        }
      }

      // ── Strategi 3: Tidak ketemu → pakai default + pesan ──
      setMarker(DEFAULT_LAT, DEFAULT_LNG);
      map.setView([DEFAULT_LAT, DEFAULT_LNG], 10);
      setMapStatus('warning', 'bi-exclamation-triangle',
        'Lokasi tidak terdeteksi otomatis. Gunakan tombol GPS atau klik peta.', false);
    }

    // ══════════════════════════════════════════════════════════════
    //  ✅ LOKASI GPS PERANGKAT
    // ══════════════════════════════════════════════════════════════
    function getLocationGPS() {
      if (!navigator.geolocation) {
        return setMapStatus('warning', 'bi-exclamation-triangle', 'Browser tidak mendukung GPS', false);
      }

      setMapStatus('detecting', 'bi-arrow-repeat', 'Mendeteksi titik koordinat GPS kamu...', true);

      navigator.geolocation.getCurrentPosition(
        (position) => {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          setMarker(lat, lng);
          map.setView([lat, lng], 17);
          setMapStatus('success', 'bi-check-circle-fill', 'Lokasi GPS berhasil dideteksi ✓', false);
        },
        (error) => {
          let msg = 'Gagal mendeteksi lokasi';
          if (error.code === 1) msg = 'Izin lokasi ditolak browser';
          else if (error.code === 2) msg = 'Lokasi tidak tersedia/sinyal lemah';
          else if (error.code === 3) msg = 'Waktu deteksi habis (timeout)';
          setMapStatus('warning', 'bi-exclamation-triangle', msg, false);
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
      );
    }

    // ══════════════════════════════════════════════════════════════
    //  ✅ LIVE GEOCODE SAAT ALAMAT DIUBAH DI FORM
    // ══════════════════════════════════════════════════════════════
    let alamatTimer = null;
    document.getElementById('alamat').addEventListener('input', function () {
      clearTimeout(alamatTimer);
      const val = this.value.trim();
      if (val.length < 5) return;

      setMapStatus('detecting', 'bi-arrow-repeat', 'Mencari lokasi dari alamat...', true);
      alamatTimer = setTimeout(async () => {
        // Ambil kota dari dropdown
        const kotaVal = document.querySelector('select[name="kota"]').value || OWNER.kota || '';
        const query = [val, kotaVal, 'Jawa Timur'].filter(Boolean).join(', ');
        const result = await geocodeQuery(query);
        if (result) {
          const lat = parseFloat(result.lat);
          const lng = parseFloat(result.lon);
          setMarker(lat, lng);
          map.setView([lat, lng], 17);
          setMapStatus('success', 'bi-check-circle', 'Lokasi terdeteksi dari alamat ✓', false);
        } else {
          setMapStatus('warning', 'bi-exclamation-triangle', 'Alamat tidak ditemukan. Klik peta untuk pilih manual.', false);
        }
      }, 900);
    });

    // ══════════════════════════════════════════════════════════════
    //  INIT — Jalankan saat halaman siap
    // ══════════════════════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', function () {
      if (OLD_LAT && OLD_LNG) {
        // Setelah validasi error → pakai old()
        setMarker(OLD_LAT, OLD_LNG);
        map.setView([OLD_LAT, OLD_LNG], 16);
        setMapStatus('info', 'bi-info-circle', 'Koordinat dari input sebelumnya', false);
      } else {
        // Pertama kali load → geocode dari data owner
        geocodeFromOwnerAddress();
      }
    });

    // ══════════════════════════════════════════════════════════════
    //  UPLOAD FOTO
    // ══════════════════════════════════════════════════════════════
    const MAX_FILES   = 6, MAX_MB = 2;
    const dropZone    = document.getElementById('dropZone');
    const fotoInput   = document.getElementById('fotoInput');
    const previewGrid = document.getElementById('previewGrid');
    const infoBar     = document.getElementById('fotoInfoBar');
    const infoText    = document.getElementById('fotoInfoText');
    let selectedFiles = [];

    ['dragenter','dragover','dragleave','drop'].forEach(evt =>
      dropZone.addEventListener(evt, e => { e.preventDefault(); e.stopPropagation(); }, false));
    dropZone.addEventListener('dragover',  () => dropZone.classList.add('drag-over'));
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', e => { dropZone.classList.remove('drag-over'); handleFiles(Array.from(e.dataTransfer.files)); });
    fotoInput.addEventListener('change', () => handleFiles(Array.from(fotoInput.files)));
    dropZone.addEventListener('click', e => { if (!e.target.closest('.btn-pilih-foto')) fotoInput.click(); });

    function handleFiles(newFiles) {
      const images = newFiles.filter(f => f.type.startsWith('image/'));
      if (images.length !== newFiles.length) return alert('Hanya gambar yang diperbolehkan!');
      if (images.some(f => f.size > MAX_MB * 1024 * 1024)) return alert('Maks 2MB per foto!');
      selectedFiles = [...selectedFiles, ...images].slice(0, MAX_FILES);
      updateInput(); renderPreviews();
    }

    function updateInput() {
      try {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        fotoInput.files = dt.files;
      } catch(e) { console.warn(e); }
    }

    function renderPreviews() {
      previewGrid.innerHTML = '';
      const namaContainer = document.getElementById('namaFotoInputs');
      namaContainer.innerHTML = '';
      infoBar.style.display = selectedFiles.length ? 'flex' : 'none';
      infoText.textContent  = selectedFiles.length + ' dari ' + MAX_FILES + ' foto';
      for (let i = 1; i <= MAX_FILES; i++) {
        const dot = document.getElementById('dot' + i);
        if (dot) dot.classList.toggle('filled', i <= selectedFiles.length);
      }
      selectedFiles.forEach((file, i) => {
        // ── hidden input nama foto ──
        const namaHidden = document.createElement('input');
        namaHidden.type = 'hidden';
        namaHidden.name = 'foto_kost_nama[]';
        namaHidden.id   = 'namaFoto_' + i;
        namaHidden.value = window._namaFotoValues ? (window._namaFotoValues[i] || '') : '';
        namaContainer.appendChild(namaHidden);

        const reader = new FileReader();
        reader.onload = e => {
          const div = document.createElement('div');
          div.className = 'preview-card' + (i === 0 ? ' is-cover' : '');
          div.innerHTML = `
            <div class="preview-img-wrap">
              <img src="${e.target.result}">
              <div class="preview-img-overlay"></div>
              ${i === 0 ? '<div class="badge-cover"><i class="bi bi-star-fill"></i> Cover</div>' : ''}
              <span class="badge-num">Foto ${i+1}</span>
              <button type="button" class="btn-remove" onclick="removeFile(${i})"><i class="bi bi-x-lg"></i></button>
              ${i !== 0 ? `<button type="button" class="btn-set-cover" onclick="setCover(${i})"><i class="bi bi-star"></i> Cover</button>` : ''}
            </div>
            <div class="preview-info">
              <div class="preview-name" title="${file.name}">${file.name}</div>
              <div class="preview-size">${(file.size/1024/1024).toFixed(2)} MB</div>
            </div>
            <div style="padding:.3rem .9rem .75rem;border-top:1px solid #f0f3f8;">
              <input type="text"
                     id="namaFotoInput_${i}"
                     placeholder="Nama foto, contoh: Ruang Parkir..."
                     value="${window._namaFotoValues ? (window._namaFotoValues[i] || '') : ''}"
                     style="width:100%;font-size:.76rem;border:1px solid #dde3ed;border-radius:.55rem;padding:.38rem .65rem;outline:none;"
                     oninput="syncNamaFoto(${i}, this.value)">
            </div>`;
          previewGrid.appendChild(div);
        };
        reader.readAsDataURL(file);
      });
    }

    // Simpan nama ke array global saat user mengetik
    window._namaFotoValues = [];
    window.syncNamaFoto = function(idx, val) {
      window._namaFotoValues[idx] = val;
      const hidden = document.getElementById('namaFoto_' + idx);
      if (hidden) hidden.value = val;
    };

    window.removeFile = function(idx) {
      window._namaFotoValues.splice(idx, 1);
      selectedFiles.splice(idx, 1);
      updateInput();
      renderPreviews();
    };
    window.setCover = function(idx) {
      const [m] = selectedFiles.splice(idx, 1);
      selectedFiles.unshift(m);
      if (window._namaFotoValues) {
        const [n] = window._namaFotoValues.splice(idx, 1);
        window._namaFotoValues.unshift(n);
      }
      updateInput();
      renderPreviews();
    };

    // ══════════════════════════════════════════════════════════════
    //  FOTO FASILITAS UMUM — Dynamic rows
    // ══════════════════════════════════════════════════════════════
    let facilityCount = 0;
    const facilityContainer = document.getElementById('facilityRowsContainer');

    document.getElementById('btnAddFacility').addEventListener('click', function() {
      addFacilityRow();
    });

    function addFacilityRow() {
      facilityCount++;
      const idx = facilityCount;
      const row = document.createElement('div');
      row.className = 'facility-row';
      row.id = 'fac-row-' + idx;
      row.innerHTML = `
        <div class="facility-img-preview" id="fac-preview-${idx}" onclick="document.getElementById('fac-file-${idx}').click()">
          <i class="bi bi-camera-fill upload-icon"></i>
          <span class="upload-hint">Klik upload foto</span>
          <input type="file" name="facility_photo[]" id="fac-file-${idx}" accept="image/jpeg,image/png,image/webp" style="display:none;" onchange="previewFacilityPhoto(${idx}, this)" required>
        </div>
        <div class="facility-right">
          <input type="text" name="facility_name[]" class="facility-label-input"
            placeholder="Nama Fasilitas (contoh: Dapur Bersama, Area Parkir...)" required
            style=""
          >
          <div style="font-size:.7rem;color:var(--muted);display:flex;align-items:center;gap:.35rem;">
            <i class="bi bi-info-circle"></i> Nama ini akan muncul sebagai label di halaman detail kost
          </div>
        </div>
        <button type="button" class="facility-remove-btn" onclick="removeFacilityRow(${idx})" title="Hapus">
          <i class="bi bi-trash-fill"></i>
        </button>
      `;
      facilityContainer.appendChild(row);
      // Focus on label input
      setTimeout(() => row.querySelector('.facility-label-input').focus(), 150);
    }

    function previewFacilityPhoto(idx, input) {
      if (!input.files || !input.files[0]) return;
      const file = input.files[0];
      if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran foto maks 2MB!');
        input.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = function(e) {
        const preview = document.getElementById('fac-preview-' + idx);
        if (!preview) return;
        // Hapus icon upload
        preview.querySelector('.upload-icon')?.remove();
        preview.querySelector('.upload-hint')?.remove();
        // Tambahkan img preview (atau update yang sudah ada)
        let img = preview.querySelector('img');
        if (!img) {
          img = document.createElement('img');
          preview.insertBefore(img, preview.querySelector('input'));
        }
        img.src = e.target.result;
        // Tambahkan overlay edit
        let overlay = preview.querySelector('.fac-overlay');
        if (!overlay) {
          overlay = document.createElement('div');
          overlay.className = 'fac-overlay';
          overlay.style.cssText = 'position:absolute;inset:0;background:rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;opacity:0;transition:.2s;border-radius:.6rem;';
          overlay.innerHTML = '<i class="bi bi-pencil-fill" style="color:#fff;font-size:1rem;"></i>';
          preview.appendChild(overlay);
          preview.addEventListener('mouseenter', () => overlay.style.opacity = '1');
          preview.addEventListener('mouseleave', () => overlay.style.opacity = '0');
        }
      };
      reader.readAsDataURL(file);
    }

    function removeFacilityRow(idx) {
      const row = document.getElementById('fac-row-' + idx);
      if (row) {
        row.style.transform = 'scale(.9)';
        row.style.opacity = '0';
        row.style.transition = 'all .25s ease';
        setTimeout(() => row.remove(), 250);
      }
    }

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
        <div class="form-check d-flex align-items-center justify-content-between pe-2" style="background:#fff7f5; border-color:#ffd2c7;">
          <div class="d-flex align-items-center">
            <input class="form-check-input" type="checkbox" name="fasilitas[]" value="${val}" id="cf_${idx}" checked>
            <label class="form-check-label ms-2" for="cf_${idx}">${val}</label>
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
</body>
</html>