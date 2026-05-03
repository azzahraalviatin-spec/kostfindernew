<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Edit Kost - KostFinder</title>
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
    .form-card { background:#fff; border-radius:1rem; border:1px solid var(--line); box-shadow:0 6px 20px rgba(0,0,0,.04); padding:1.5rem; margin-bottom:1.5rem; }
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

    /* ── FOTO EXISTING ── */
    .foto-existing-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: .75rem; margin-bottom: .75rem; }
    .foto-existing-item { position: relative; border-radius: .9rem; overflow: hidden; border: 1.5px solid var(--line); }
    .foto-existing-item img { width: 100%; height: 120px; object-fit: cover; display: block; }
    .foto-existing-badge { position: absolute; top: 8px; left: 8px; background: linear-gradient(135deg,#e8401c,#ff7043); color: #fff; font-size: .6rem; font-weight: 800; padding: .25rem .6rem; border-radius: 999px; }
    .foto-existing-num { position: absolute; top: 8px; left: 8px; background: rgba(17,24,39,.65); color: #fff; font-size: .6rem; font-weight: 700; padding: .25rem .6rem; border-radius: 999px; }
    .foto-warning { background: #fff8f2; border: 1px solid #ffd0b0; border-left: 3px solid var(--primary); border-radius: .75rem; padding: .65rem .9rem; font-size: .75rem; color: #9a3412; }

    /* ── UPLOAD FOTO ── */
    .upload-section { position: relative; }
    .drop-zone { border: 2px dashed #d8e2ef; border-radius: 1.1rem; background: #fafcff; padding: 2.2rem 1.5rem; text-align: center; cursor: pointer; transition: all .25s ease; position: relative; overflow: hidden; }
    .drop-zone:hover, .drop-zone.drag-over { border-color: var(--primary); background: #fff8f6; transform: scale(1.005); }
    .drop-zone.drag-over { border-style: solid; box-shadow: 0 0 0 4px rgba(232,64,28,.1); }
    .drop-zone-icon { width: 68px; height: 68px; margin: 0 auto 1rem; background: linear-gradient(135deg, #fff5f2, #ffe8e0); border-radius: 1rem; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: var(--primary); box-shadow: 0 8px 20px rgba(232,64,28,.12); transition: .25s; }
    .drop-zone:hover .drop-zone-icon { transform: translateY(-4px); }
    .drop-zone-title { font-size: 1rem; font-weight: 800; color: var(--dark); margin-bottom: .35rem; }
    .drop-zone-sub { font-size: .8rem; color: var(--muted); margin-bottom: 1.2rem; line-height: 1.6; }
    .btn-pilih-foto { display: inline-flex; align-items: center; gap: .5rem; background: linear-gradient(135deg,#e8401c,#ff7043); color: #fff; font-weight: 700; font-size: .82rem; padding: .6rem 1.3rem; border-radius: .75rem; border: none; box-shadow: 0 6px 16px rgba(232,64,28,.22); cursor: pointer; transition: .2s; }
    .btn-pilih-foto:hover { background: linear-gradient(135deg,#cb3518,#e8401c); transform: translateY(-1px); }
    .drop-zone-hint { font-size: .72rem; color: #b0bfcc; margin-top: .85rem; }
    #fotoInput { display: none; }

    .foto-info-bar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .5rem; margin-top: 1rem; padding: .65rem .9rem; background: var(--primary-light); border: 1px solid var(--primary-mid); border-radius: .75rem; }
    .foto-info-left { display: flex; align-items: center; gap: .5rem; font-size: .78rem; color: #9a3412; font-weight: 600; }
    .foto-counter { display: flex; align-items: center; gap: .4rem; }
    .counter-dot { width: 8px; height: 8px; border-radius: 50%; background: #d8cbc8; transition: .2s; }
    .counter-dot.filled { background: var(--primary); }

    .preview-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 1.2rem; }
    .preview-card { position: relative; border-radius: 1rem; overflow: hidden; background: #f8fafc; border: 1.5px solid var(--line); box-shadow: 0 4px 16px rgba(15,23,42,.06); transition: .25s ease; animation: popIn .3s cubic-bezier(.34,1.56,.64,1) both; }
    @keyframes popIn { from { opacity: 0; transform: scale(.88); } to { opacity: 1; transform: scale(1); } }
    .preview-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(15,23,42,.1); }
    .preview-card.is-cover { border-color: var(--primary); box-shadow: 0 4px 16px rgba(232,64,28,.15); }
    .preview-img-wrap { position: relative; height: 120px; overflow: hidden; background: #edf2f7; }
    .preview-img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .35s ease; }
    .preview-card:hover .preview-img-wrap img { transform: scale(1.06); }
    .badge-cover { position: absolute; top: 8px; left: 8px; background: linear-gradient(135deg,#e8401c,#ff7043); color: #fff; font-size: .6rem; font-weight: 800; padding: .25rem .6rem; border-radius: 999px; display: flex; align-items: center; gap: .3rem; }
    .badge-num { position: absolute; top: 8px; right: 36px; background: rgba(17,24,39,.65); color: #fff; font-size: .6rem; font-weight: 700; padding: .25rem .5rem; border-radius: 999px; }
    .btn-remove { position: absolute; top: 6px; right: 6px; width: 28px; height: 28px; border: none; border-radius: 50%; background: rgba(17,24,39,.65); color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: .78rem; transition: .2s; }
    .btn-remove:hover { background: rgba(220,38,38,.9); }
    .btn-set-cover { position: absolute; bottom: 8px; right: 8px; background: rgba(255,255,255,.9); color: #555; font-size: .62rem; font-weight: 700; padding: .22rem .55rem; border-radius: 999px; border: none; cursor: pointer; display: flex; align-items: center; gap: .3rem; opacity: 0; transition: .2s; }
    .preview-card:hover .btn-set-cover { opacity: 1; }
    .btn-set-cover:hover { background: #fff; color: var(--primary); }
    .preview-card.is-cover .btn-set-cover { display: none; }
    .preview-info { padding: .6rem .75rem; display: flex; align-items: center; justify-content: space-between; gap: .5rem; }
    .preview-name { font-size: .75rem; font-weight: 700; color: var(--dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; }
    .preview-size { font-size: .65rem; color: var(--muted); flex-shrink: 0; }

    .tips-box { margin-top: 1rem; background: #f0f9ff; border: 1px solid #bae6fd; border-left: 3px solid #0ea5e9; border-radius: .75rem; padding: .8rem 1rem; }
    .tips-box .tips-title { font-size: .78rem; font-weight: 800; color: #0369a1; margin-bottom: .35rem; display: flex; align-items: center; gap: .35rem; }
    .tips-box ul { margin: 0; padding-left: 1.1rem; }
    .tips-box ul li { font-size: .75rem; color: #0c4a6e; line-height: 1.8; }

    @media (max-width: 991px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.show { transform: translateX(0); }
      .main { margin-left: 0 !important; }
      .search-box { width: 160px; }
      .preview-grid { grid-template-columns: repeat(2, 1fr); }
      .foto-existing-grid { grid-template-columns: repeat(2, 1fr); }
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
        <span style="color:var(--dark);font-weight:700;">Edit Kost</span>
      </div>

      @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:.83rem;border-radius:.75rem;">
          <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('owner.kost.update', $kost->id_kost) }}" method="POST" enctype="multipart/form-data" id="kostForm">
        @csrf
        @method('PUT')

        <div class="row g-3">

          {{-- ── KOLOM KIRI ── --}}
          <div class="col-12 col-lg-8">

            {{-- Informasi Dasar --}}
            <div class="form-card">
              <h6><i class="bi bi-info-circle" style="color:var(--primary)"></i> Informasi Dasar</h6>

              <div class="mb-3">
                <label class="form-label">Nama Kost <span class="text-danger">*</span></label>
                <input type="text" name="nama_kost" class="form-control"
                       placeholder="Contoh: Kost Melati Indah"
                       value="{{ old('nama_kost', $kost->nama_kost) }}" required>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                  <select name="kota" class="form-select" required>
                    <option value="">-- Pilih Kota --</option>
                    @foreach(['Surabaya','Malang','Sidoarjo','Gresik','Kediri','Jember','Banyuwangi','Mojokerto','Madiun','Pasuruan','Blitar','Probolinggo','Tulungagung','Lumajang','Jombang','Nganjuk','Lamongan','Bojonegoro','Tuban','Magetan','Ngawi','Ponorogo','Pacitan','Trenggalek','Bondowoso','Situbondo','Pamekasan','Sampang','Bangkalan','Sumenep'] as $kota)
                      <option value="{{ $kota }}" {{ old('kota', $kost->kota) == $kota ? 'selected' : '' }}>{{ $kota }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Tipe Kost <span class="text-danger">*</span></label>
                  <select name="tipe_kost" class="form-select" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="Putra"  {{ old('tipe_kost', $kost->tipe_kost) == 'Putra'  ? 'selected' : '' }}>Putra</option>
                    <option value="Putri"  {{ old('tipe_kost', $kost->tipe_kost) == 'Putri'  ? 'selected' : '' }}>Putri</option>
                    <option value="Campur" {{ old('tipe_kost', $kost->tipe_kost) == 'Campur' ? 'selected' : '' }}>Campur</option>
                  </select>
                </div>
              </div>

              <div class="mt-3">
                <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                <textarea name="alamat" class="form-control" rows="2"
                          placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan" required>{{ old('alamat', $kost->alamat) }}</textarea>
              </div>

              <div class="mt-3">
                <label class="form-label">Deskripsi Kost</label>
                <textarea name="deskripsi" class="form-control" rows="3"
                          placeholder="Ceritakan tentang kost kamu...">{{ old('deskripsi', $kost->deskripsi) }}</textarea>
              </div>
            </div>

            {{-- Fasilitas --}}
            <div class="form-card">
              <h6><i class="bi bi-check2-square" style="color:var(--primary)"></i> Fasilitas Umum Kost</h6>
              <p style="font-size:.76rem;color:var(--muted);margin-top:-.5rem;margin-bottom:.85rem;">Pilih fasilitas yang digunakan bersama seluruh penghuni.</p>
              @php
                $fasilitasLama = [];
                if (!empty($kost->fasilitas)) {
                  if (is_array($kost->fasilitas)) {
                    $fasilitasLama = $kost->fasilitas;
                  } else {
                    $decoded = json_decode($kost->fasilitas, true);
                    $fasilitasLama = is_array($decoded) ? $decoded : array_map('trim', explode(',', $kost->fasilitas));
                  }
                }
              @endphp
              @php
                $standardFac = ['WiFi/Internet','Parkir Motor','Parkir Mobil','Air Minum','Dapur','Laundry','CCTV','Mushola','Ruang Tamu','Jemuran','Ruang Santai','Keamanan 24 Jam'];
                $fasilitasKustom = array_diff($fasilitasLama, $standardFac);
              @endphp
              <div class="row g-2" id="facilityList">
                @foreach($standardFac as $f)
                  <div class="col-6 col-md-4" id="fac-item-{{ $loop->index }}">
                    <div class="form-check d-flex align-items-center justify-content-between pe-2">
                      <div class="d-flex align-items-center">
                        <input class="form-check-input" type="checkbox" name="fasilitas[]"
                               value="{{ $f }}" id="f_{{ $loop->index }}"
                               {{ (is_array(old('fasilitas')) ? in_array($f, old('fasilitas')) : in_array($f, $fasilitasLama)) ? 'checked' : '' }}>
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
                <div class="input-group input-group-sm mb-3">
                  <input type="text" id="customFacInput" class="form-control" placeholder="Contoh: Kolam Renang, Smart TV..." style="border-radius:.6rem 0 0 .6rem;">
                  <button type="button" class="btn btn-primary px-3" onclick="addCustomFacility()" style="border-radius:0 .6rem .6rem 0;background:var(--primary);border:none;">
                    <i class="bi bi-plus-lg"></i> Tambah
                  </button>
                </div>
                
                <div id="customFacContainer" class="row g-2 mt-2">
                  @foreach($fasilitasKustom as $fk)
                    <div class="col-6 col-md-4" id="custom-fac-old-{{ $loop->index }}">
                      <div class="form-check d-flex align-items-center justify-content-between pe-2" style="background:#fff7f5; border-color:#ffd2c7;">
                        <div class="d-flex align-items-center">
                          <input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fk }}" id="cf_old_{{ $loop->index }}" checked>
                          <label class="form-check-label ms-2" for="cf_old_{{ $loop->index }}">{{ $fk }}</label>
                        </div>
                        <button type="button" class="btn btn-link text-danger p-0 ms-2" onclick="document.getElementById('custom-fac-old-{{ $loop->index }}').remove()">
                          <i class="bi bi-x-circle-fill"></i>
                        </button>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
            

            {{-- Aturan --}}
            <div class="form-card">
              <h6><i class="bi bi-clipboard-check" style="color:var(--primary)"></i> Aturan Kost</h6>
              <textarea name="aturan" class="form-control" rows="3"
                        placeholder="Contoh: Tidak boleh membawa tamu menginap, jam malam 22.00...">{{ old('aturan', $kost->aturan) }}</textarea>
            </div>

            {{-- Foto Galeri (Properti & Fasilitas) --}}
            <div class="form-card">
              <h6><i class="bi bi-images" style="color:var(--primary)"></i> Galeri Foto (Properti & Fasilitas)</h6>
 
  <div class="upload-section">
 
    {{-- ✅ FOTO YANG SUDAH ADA + TOMBOL HAPUS INDIVIDUAL --}}
    @if($kost->images && $kost->images->count() > 0)
      <div class="mb-3">
        <div style="font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.6rem;">
          Foto Saat Ini ({{ $kost->images->count() }} foto) — Berikan label/judul untuk setiap foto
        </div>
 
        {{-- Grid foto existing --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.65rem;" id="existingFotoGrid">
          @foreach($kost->images as $i => $img)
            <div class="foto-existing-item" id="foto-item-{{ $img->id }}" style="position:relative;border-radius:.9rem;overflow:hidden;border:1.5px solid var(--line);">
 
              {{-- Badge cover --}}
              @if($i === 0)
                <span style="position:absolute;top:8px;left:8px;background:linear-gradient(135deg,#e8401c,#ff7043);color:#fff;font-size:.6rem;font-weight:800;padding:.25rem .6rem;border-radius:999px;z-index:2;">
                  ⭐ Cover
                </span>
              @else
                <span style="position:absolute;top:8px;left:8px;background:rgba(17,24,39,.65);color:#fff;font-size:.6rem;font-weight:700;padding:.25rem .6rem;border-radius:999px;z-index:2;">
                  Foto {{ $i + 1 }}
                </span>
              @endif
 
              {{-- ✅ TOMBOL HAPUS INDIVIDUAL --}}
              <button type="button"
                onclick="hapusFotoKost({{ $img->id }}, {{ $kost->id_kost }}, this)"
                style="position:absolute;top:6px;right:6px;width:30px;height:30px;border-radius:50%;background:rgba(220,38,38,.85);border:none;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:3;font-size:.8rem;transition:.2s;"
                title="Hapus foto ini"
                onmouseover="this.style.background='rgba(185,28,28,1)'"
                onmouseout="this.style.background='rgba(220,38,38,.85)'">
                <i class="bi bi-trash-fill"></i>
              </button>
 
              <img src="{{ asset('storage/'.$img->image_path) }}"
                   alt="Foto {{ $i + 1 }}"
                   style="width:100%;height:120px;object-fit:cover;display:block;">
              
              <div class="p-2 bg-white">
                <input type="text" name="existing_foto_nama[{{ $img->id }}]" 
                       class="form-control form-control-sm" 
                       placeholder="Label (Contoh: Dapur)" 
                       style="font-size:.7rem; height:30px; border-radius:.4rem;"
                       value="{{ $img->kategori }}">
              </div>
            </div>
          @endforeach
        </div>
 
        {{-- Info --}}
        <div style="margin-top:.65rem;background:#fff8f2;border:1px solid #ffd0c0;border-left:3px solid var(--primary);border-radius:.65rem;padding:.6rem .9rem;font-size:.75rem;color:#9a3412;">
          <i class="bi bi-info-circle me-1"></i>
          Berikan <strong>label/judul</strong> pada setiap foto agar penyewa tahu itu foto area apa (misal: "Dapur", "Tampak Depan").
        </div>
      </div>
    @else
      <div style="background:#f8fafc;border:1.5px dashed var(--line);border-radius:.9rem;padding:1.2rem;text-align:center;margin-bottom:1rem;color:var(--muted);font-size:.8rem;">
        <i class="bi bi-image" style="font-size:1.8rem;display:block;margin-bottom:.4rem;opacity:.3;"></i>
        Belum ada foto. Upload foto baru di bawah.
      </div>
    @endif
 
    {{-- Drop Zone upload foto baru --}}
    <div class="drop-zone" id="dropZone">
      <div class="drop-zone-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
      <div class="drop-zone-title">Upload Foto Baru</div>
      <div class="drop-zone-sub">
        Seret & lepas foto di sini, atau klik tombol di bawah
      </div>
      <button type="button" class="btn-pilih-foto" onclick="document.getElementById('fotoInput').click()">
        <i class="bi bi-folder2-open"></i> Pilih Foto
      </button>
      <div class="drop-zone-hint">
        <i class="bi bi-info-circle me-1"></i>
        Maks. <strong>6 foto</strong> &bull; Format: JPG, PNG, WEBP &bull; Maks. <strong>2 MB</strong> per foto
      </div>
    </div>
 
    <input type="file" name="foto_kost[]" id="fotoInput" accept="image/jpeg,image/png,image/webp" multiple>
 
    {{-- Info bar & preview --}}
    <div class="foto-info-bar" id="fotoInfoBar" style="display:none;">
      <div class="foto-info-left"><i class="bi bi-images"></i><span id="fotoInfoText">0 foto dipilih</span></div>
      <div class="foto-counter" id="fotoDots">
        <div class="counter-dot" id="dot1"></div><div class="counter-dot" id="dot2"></div>
        <div class="counter-dot" id="dot3"></div><div class="counter-dot" id="dot4"></div>
        <div class="counter-dot" id="dot5"></div><div class="counter-dot" id="dot6"></div>
      </div>
    </div>
    <div class="preview-grid" id="previewGrid"></div>
 
    {{-- Tips --}}
    <div class="tips-box">
      <div class="tips-title"><i class="bi bi-lightbulb-fill" style="color:#f59e0b"></i> Tips foto profesional</div>
      <ul>
        <li>Foto <strong>tampak depan bangunan</strong> sebagai foto utama/cover</li>
        <li>Pastikan pencahayaan <strong>terang & natural</strong>, hindari foto blur</li>
        <li>Ambil dari sudut yang memperlihatkan <strong>luas ruangan</strong></li>
      </ul>
    </div>
 
  </div>
</div>

          </div>
          {{-- END KOLOM KIRI --}}

          {{-- ── KOLOM KANAN ── --}}
          <div class="col-12 col-lg-4">

            {{-- Harga --}}
            <div class="form-card">
              <h6><i class="bi bi-cash" style="color:var(--primary)"></i> Harga</h6>

              {{-- Harga Per Bulan --}}
              <label class="form-label">Harga Per Bulan</label>
              <div class="row g-2 mb-1">
                <div class="col-6">
                  <div class="input-group">
                    <span class="input-group-text" style="font-size:.78rem;background:#f8fafd;border-color:var(--line);">Rp</span>
                    <input type="number" name="harga_mulai" class="form-control"
                           placeholder="500000" value="{{ old('harga_mulai', $kost->harga_mulai) }}">
                  </div>
                  <div class="form-text" style="font-size:.7rem;">Mulai dari</div>
                </div>
                <div class="col-6">
                  <div class="input-group">
                    <span class="input-group-text" style="font-size:.78rem;background:#f8fafd;border-color:var(--line);">Rp</span>
                    <input type="number" name="harga_sampai" class="form-control"
                           placeholder="1200000" value="{{ old('harga_sampai', $kost->harga_sampai) }}">
                  </div>
                  <div class="form-text" style="font-size:.7rem;">Sampai</div>
                </div>
              </div>
              <div class="form-text mb-3" style="font-size:.72rem;color:var(--muted);">
                <i class="bi bi-info-circle me-1"></i>Harga dapat berbeda tiap kamar
              </div>

              <hr style="border-color:#f0f3f8;margin:.5rem 0 1rem;">

              {{-- Harga Per Hari --}}
              <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="form-label mb-0">Harga Per Hari <span style="font-size:.7rem;color:var(--muted);font-weight:500;">(opsional)</span></label>
                <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="toggleHarian"
       name="ada_harian" value="1"
       {{ old('ada_harian', $kost->ada_harian) ? 'checked' : '' }}
       onchange="document.getElementById('sectionHarian').style.display = this.checked ? 'block' : 'none'">
      </div>
              </div>
              <div id="sectionHarian" style="display:{{ old('ada_harian', $kost->ada_harian) ? 'block' : 'none' }};">
                <div class="row g-2 mb-1">
                  <div class="col-6">
                    <div class="input-group">
                      <span class="input-group-text" style="font-size:.78rem;background:#f8fafd;border-color:var(--line);">Rp</span>
                      <input type="number" name="harga_harian_mulai" class="form-control"
                             placeholder="75000" value="{{ old('harga_harian_mulai', $kost->harga_harian_mulai) }}">
                    </div>
                    <div class="form-text" style="font-size:.7rem;">Mulai dari</div>
                  </div>
                  <div class="col-6">
                    <div class="input-group">
                      <span class="input-group-text" style="font-size:.78rem;background:#f8fafd;border-color:var(--line);">Rp</span>
                      <input type="number" name="harga_harian_sampai" class="form-control"
                             placeholder="150000" value="{{ old('harga_harian_sampai', $kost->harga_harian_sampai) }}">
                    </div>
                    <div class="form-text" style="font-size:.7rem;">Sampai</div>
                  </div>
                </div>
                <div class="form-text" style="font-size:.72rem;color:var(--muted);">
                  <i class="bi bi-info-circle me-1"></i>Harga harian dapat berbeda tiap kamar
                </div>
              </div>
            </div>

            {{-- Status --}}
            <div class="form-card">
              <h6><i class="bi bi-toggle-on" style="color:var(--primary)"></i> Status</h6>
              <select name="status" class="form-select">
                <option value="aktif"    {{ old('status', $kost->status) == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status', $kost->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
              </select>
            </div>

            {{-- Peta --}}
            <div class="form-card">
              <h6><i class="bi bi-geo-alt" style="color:var(--primary)"></i> Lokasi di Peta</h6>
              <p style="font-size:.75rem;color:var(--muted);margin-bottom:.7rem;">Klik peta atau geser pin untuk mengubah lokasi kost</p>
              
              {{-- Status bar geocoding --}}
              <div class="map-status-bar info" id="mapStatusBar">
                <i class="bi bi-info-circle" id="mapStatusIcon"></i>
                <span id="mapStatusText">Lokasi kost saat ini</span>
              </div>

              <div id="map"></div>

              {{-- Koordinat display --}}
              <div class="koordinat-box">
                <div class="koordinat-item">
                  <label>Latitude</label>
                  <span id="latDisplay">{{ $kost->latitude ?? '—' }}</span>
                </div>
                <div class="koordinat-item">
                  <label>Longitude</label>
                  <span id="lngDisplay">{{ $kost->longitude ?? '—' }}</span>
                </div>
              </div>

              {{-- Tombol Deteksi GPS --}}
              <div class="d-flex flex-wrap gap-2 mt-2">
                <button type="button" class="btn-refresh-map" onclick="getLocationGPS()" style="background: #e0f2fe; color: #0369a1; border-color: #bae6fd;">
                  <i class="bi bi-geo-fill"></i> Gunakan GPS Saya
                </button>
              </div>

              <input type="hidden" name="latitude"  id="latitude"  value="{{ old('latitude',  $kost->latitude  ?? '') }}">
              <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $kost->longitude ?? '') }}">
            </div>

          </div>
          {{-- END KOLOM KANAN --}}

        </div>

        <div class="d-flex gap-2 mt-2 mb-4">
          <button type="submit" class="btn-submit">
            <i class="bi bi-check-lg"></i> Update Kost
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
  <script>
    // ── SIDEBAR ──
    function toggleSidebar() {
      const s = document.getElementById('sidebar');
      const m = document.getElementById('mainContent');
      if (window.innerWidth <= 991) { s?.classList.toggle('show'); }
      else { s?.classList.toggle('collapsed'); m?.classList.toggle('collapsed'); }
    }

    // ── MAP ──
    const initLat = Number('{{ old('latitude', $kost->latitude ?? -7.2575) }}') || -7.2575;
    const initLng = Number('{{ old('longitude', $kost->longitude ?? 112.7521) }}') || 112.7521;
    const map = L.map('map').setView([initLat, initLng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const latDisplay = document.getElementById('latDisplay');
    const lngDisplay = document.getElementById('lngDisplay');
    let marker = null;

    function setMarker(lat, lng) {
      const la = Number(lat).toFixed(7);
      const ln = Number(lng).toFixed(7);
      latInput.value = la;
      lngInput.value = ln;
      if (latDisplay) latDisplay.textContent = Number(lat).toFixed(5);
      if (lngDisplay) lngDisplay.textContent = Number(lng).toFixed(5);
      
      if (marker) marker.setLatLng([lat, lng]);
      else marker = L.marker([lat, lng], { draggable: true }).addTo(map).bindPopup('Lokasi kost kamu');
      
      marker.on('dragend', function() {
        const pos = marker.getLatLng();
        setMarker(pos.lat, pos.lng);
        setMapStatus('success', 'bi-check-circle', 'Koordinat diperbarui dari pin', false);
      });
      
      marker.openPopup();
    }

    function setMapStatus(type, icon, text, spinning) {
      const bar  = document.getElementById('mapStatusBar');
      const ico  = document.getElementById('mapStatusIcon');
      const txt  = document.getElementById('mapStatusText');
      if(!bar) return;
      bar.className = 'map-status-bar ' + type;
      ico.className = 'bi ' + icon;
      ico.style.animation = spinning ? 'spin .8s linear infinite' : 'none';
      txt.textContent = text;
    }

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

    setMarker(initLat, initLng);
    map.on('click', e => { 
      setMarker(e.latlng.lat, e.latlng.lng); 
      map.setView([e.latlng.lat, e.latlng.lng], 15); 
      setMapStatus('success', 'bi-geo-alt-fill', 'Lokasi dipilih manual dari peta', false);
    });
    setTimeout(() => map.invalidateSize(), 300);

    // ── UPLOAD FOTO ──
    const MAX_FILES   = 6;
    const MAX_MB      = 2;
    const dropZone    = document.getElementById('dropZone');
    const fotoInput   = document.getElementById('fotoInput');
    const previewGrid = document.getElementById('previewGrid');
    const infoBar     = document.getElementById('fotoInfoBar');
    const infoText    = document.getElementById('fotoInfoText');
    let selectedFiles = [];

    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', e => { e.preventDefault(); dropZone.classList.remove('drag-over'); handleFiles(Array.from(e.dataTransfer.files)); });
    dropZone.addEventListener('click', function(e) { if (e.target.closest('.btn-pilih-foto')) return; fotoInput.click(); });
    fotoInput.addEventListener('change', function() { handleFiles(Array.from(this.files)); });

    function handleFiles(newFiles) {
      const imageFiles = newFiles.filter(f => f.type.startsWith('image/'));
      if (newFiles.length !== imageFiles.length) { showAlert('Hanya file gambar (JPG, PNG, WEBP) yang diperbolehkan.', 'danger'); return; }
      const tooBig = imageFiles.filter(f => f.size > MAX_MB * 1024 * 1024);
      if (tooBig.length > 0) { showAlert(`File "${tooBig[0].name}" melebihi batas ${MAX_MB} MB.`, 'danger'); return; }
      const combined = [...selectedFiles, ...imageFiles].slice(0, MAX_FILES);
      if (selectedFiles.length + imageFiles.length > MAX_FILES) showAlert(`Maksimal ${MAX_FILES} foto. Foto berlebih diabaikan.`, 'warning');
      selectedFiles = combined;
      syncInputFiles();
      renderPreviews();
    }

    function syncInputFiles() {
  try {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    fotoInput.files = dt.files;
  } catch(e) {
    console.warn('DataTransfer tidak didukung:', e);
  }
}

    function renderPreviews() {
      previewGrid.innerHTML = '';
      if (selectedFiles.length === 0) { infoBar.style.display = 'none'; return; }
      infoBar.style.display = 'flex';
      infoText.textContent = `${selectedFiles.length} dari ${MAX_FILES} foto dipilih`;
      for (let i = 1; i <= MAX_FILES; i++) {
        const dot = document.getElementById(`dot${i}`);
        if (dot) dot.classList.toggle('filled', selectedFiles.length >= i);
      }
      selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
          const card = document.createElement('div');
          card.className = 'preview-card' + (index === 0 ? ' is-cover' : '');
          card.innerHTML = `
            <div class="preview-img-wrap">
              <img src="${e.target.result}" alt="${file.name}">
              ${index === 0 ? '<div class="badge-cover"><i class="bi bi-star-fill" style="font-size:.55rem"></i> Cover</div>' : ''}
              <span class="badge-num">Foto ${index + 1}</span>
              <button type="button" class="btn-remove" data-idx="${index}"><i class="bi bi-x-lg"></i></button>
              ${index !== 0 ? `<button type="button" class="btn-set-cover" data-idx="${index}"><i class="bi bi-star"></i> Cover</button>` : ''}
            </div>
            </div>
            <div class="preview-info" style="flex-direction:column; align-items:stretch; gap:4px;">
              <input type="text" name="foto_kost_nama[]" class="form-control form-control-sm" 
                     placeholder="Beri label (Dapur, dll)" style="font-size:.72rem; border-radius:.4rem; height:28px;">
              <div class="d-flex justify-content-between">
                <div class="preview-name" title="${file.name}">${file.name}</div>
                <div class="preview-size">${(file.size/1024/1024).toFixed(2)} MB</div>
              </div>
            </div>`;
          previewGrid.appendChild(card);
          card.querySelector('.btn-remove').addEventListener('click', function() {
            selectedFiles.splice(Number(this.dataset.idx), 1);
            syncInputFiles(); renderPreviews();
          });
          const btnCover = card.querySelector('.btn-set-cover');
          if (btnCover) btnCover.addEventListener('click', function() {
            const [moved] = selectedFiles.splice(Number(this.dataset.idx), 1);
            selectedFiles.unshift(moved);
            syncInputFiles(); renderPreviews();
          });
        };
        reader.readAsDataURL(file);
      });
    }
// Debug: cek file sebelum submit
document.getElementById('kostForm').addEventListener('submit', function() {
  const input = document.getElementById('fotoInput');
  console.log('Jumlah file di input:', input.files.length);
  console.log('selectedFiles:', selectedFiles.length);
});
    function showAlert(msg, type = 'danger') {
      const existing = document.getElementById('uploadAlert');
      if (existing) existing.remove();
      const div = document.createElement('div');
      div.id = 'uploadAlert';
      div.className = `alert alert-${type} alert-dismissible mt-2`;
      div.style.cssText = 'font-size:.82rem;border-radius:.75rem;';
      div.innerHTML = `<i class="bi bi-exclamation-triangle me-1"></i>${msg}<button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>`;
      dropZone.after(div);
      setTimeout(() => div.remove(), 4000);
    }
    function addNewFacilityRow() {
      const container = document.getElementById('newFacilityContainer');
      const div = document.createElement('div');
      div.className = 'row g-2 mb-2 align-items-center bg-light p-2 rounded-3 border-dashed';
      div.style.border = '1px dashed #d1d5db';
      div.innerHTML = `
        <div class="col-md-5">
          <input type="file" name="new_facility_photo[]" class="form-control form-control-sm" accept="image/*" required>
        </div>
        <div class="col-md-5">
          <input type="text" name="new_facility_name[]" class="form-control form-control-sm" placeholder="Nama Fasilitas (Dapur, Parkir, dll)" required>
        </div>
        <div class="col-md-2 text-end">
          <button type="button" class="btn btn-sm btn-danger py-1" onclick="this.closest('.row').remove()"><i class="bi bi-trash"></i></button>
        </div>
      `;
      container.appendChild(div);
    }
    // ✅ HAPUS FOTO INDIVIDUAL via AJAX
async function hapusFotoKost(imageId, kostId, btn) {
  if (!confirm('Yakin hapus foto ini?')) return;
 
  // Disable tombol saat proses
  btn.disabled = true;
  btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
 
  try {
    const response = await fetch(`/owner/kost/${kostId}/image/${imageId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      }
    });
 
    const data = await response.json();
 
    if (data.success) {
      // Animasi hapus card foto
      const card = document.getElementById('foto-item-' + imageId);
      if (card) {
        card.style.transition = 'all .3s ease';
        card.style.transform  = 'scale(.8)';
        card.style.opacity    = '0';
        setTimeout(() => {
          card.remove();
          // Update badge nomor foto yang tersisa
          refreshFotoBadges();
        }, 300);
      }
 
      // Tampilkan notif sukses
      showToast('Foto berhasil dihapus!', 'success');
 
      // Kalau semua foto habis, tampilkan placeholder
      if (data.remaining === 0) {
        const grid = document.getElementById('existingFotoGrid');
        if (grid) {
          grid.closest('.mb-3').innerHTML = `
            <div style="background:#f8fafc;border:1.5px dashed var(--line);border-radius:.9rem;padding:1.2rem;text-align:center;margin-bottom:1rem;color:var(--muted);font-size:.8rem;">
              <i class="bi bi-image" style="font-size:1.8rem;display:block;margin-bottom:.4rem;opacity:.3;"></i>
              Belum ada foto. Upload foto baru di bawah.
            </div>`;
        }
      }
    } else {
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-trash-fill"></i>';
      showToast('Gagal menghapus foto.', 'error');
    }
  } catch (e) {
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-trash-fill"></i>';
    showToast('Terjadi kesalahan. Coba lagi.', 'error');
  }
}
 
// Update badge nomor setelah hapus
function refreshFotoBadges() {
  const items = document.querySelectorAll('#existingFotoGrid .foto-existing-item');
  items.forEach((item, i) => {
    const badge = item.querySelector('span');
    if (!badge) return;
    if (i === 0) {
      badge.className = '';
      badge.style.cssText = 'position:absolute;top:8px;left:8px;background:linear-gradient(135deg,#e8401c,#ff7043);color:#fff;font-size:.6rem;font-weight:800;padding:.25rem .6rem;border-radius:999px;z-index:2;';
      badge.innerHTML = '⭐ Cover';
    } else {
      badge.style.cssText = 'position:absolute;top:8px;left:8px;background:rgba(17,24,39,.65);color:#fff;font-size:.6rem;font-weight:700;padding:.25rem .6rem;border-radius:999px;z-index:2;';
      badge.innerHTML = 'Foto ' + (i + 1);
    }
  });
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
 
// Toast notifikasi kecil
function showToast(msg, type) {
  const existing = document.getElementById('fotoToast');
  if (existing) existing.remove();
 
  const toast = document.createElement('div');
  toast.id = 'fotoToast';
  toast.style.cssText = `
    position:fixed;bottom:24px;right:24px;z-index:9999;
    background:${type === 'success' ? '#16a34a' : '#dc2626'};
    color:#fff;padding:.75rem 1.2rem;border-radius:.75rem;
    font-size:.82rem;font-weight:700;
    box-shadow:0 8px 24px rgba(0,0,0,.2);
    display:flex;align-items:center;gap:.5rem;
    animation:slideInToast .3s ease;
  `;
  toast.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'x-circle-fill'}"></i> ${msg}`;
  document.body.appendChild(toast);
 
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transition = 'opacity .3s';
    setTimeout(() => toast.remove(), 300);
  }, 2800);
}
</script>
 
<style>
@keyframes slideInToast {
  from { transform: translateY(20px); opacity: 0; }
  to   { transform: translateY(0);    opacity: 1; }
}
  </script>
</body>
</html>