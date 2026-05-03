<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Kost - KostFinder</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

  <style>
    :root {
      --sidebar-w: 250px;
      --sidebar-col: 78px;
      --primary: #e8401c;
      --dark: #1e2d3d;
      --bg: #f4f7fb;
      --card: #ffffff;
      --line: #e8edf4;
      --muted: #8fa3b8;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    body {
      background: var(--bg);
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* ================= SIDEBAR ================= */
    .sidebar {
      width: var(--sidebar-w);
      min-height: 100vh;
      background: var(--dark);
      position: fixed;
      top: 0;
      left: 0;
      display: flex;
      flex-direction: column;
      z-index: 200;
      transition: all .3s ease;
      overflow: hidden;
    }

    .sidebar.collapsed {
      width: var(--sidebar-col);
    }

    .sidebar-brand {
      padding: 1.2rem .9rem;
      border-bottom: 1px solid rgba(255,255,255,.08);
      display: flex;
      align-items: center;
      gap: .6rem;
      min-height: 70px;
      white-space: nowrap;
    }

    .brand-icon {
      width: 38px;
      height: 38px;
      flex-shrink: 0;
      background: var(--primary);
      border-radius: .7rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      color: #fff;
    }

    .brand-text { overflow: hidden; transition: .2s; }
    .brand-text .name { font-size: 1.2rem; font-weight: 800; color: #fff; line-height: 1; }
    .brand-text .name span { color: #ff7a45; }
    .brand-text .sub { font-size: .72rem; color: #8aa0b7; margin-top: .25rem; }

    .sidebar.collapsed .brand-text,
    .sidebar.collapsed .menu-label,
    .sidebar.collapsed .menu-item span,
    .sidebar.collapsed .user-info {
      opacity: 0;
      width: 0;
      overflow: hidden;
    }

    .sidebar-menu { padding: .8rem .6rem; flex: 1; }

    .menu-label {
      font-size: .68rem;
      font-weight: 700;
      color: #7f96ad;
      padding: .45rem .55rem;
      letter-spacing: .08em;
    }

    .menu-item {
      display: flex;
      align-items: center;
      gap: .7rem;
      padding: .72rem .8rem;
      border-radius: .75rem;
      color: #adc0cf;
      text-decoration: none;
      font-size: .88rem;
      font-weight: 600;
      margin-bottom: .2rem;
      transition: .2s;
      white-space: nowrap;
      border: none;
      background: transparent;
      width: 100%;
      text-align: left;
    }

    .menu-item i { font-size: 1rem; width: 20px; flex-shrink: 0; }
    .menu-item:hover { background: rgba(255,255,255,.08); color: #fff; }
    .menu-item.active {
      background: linear-gradient(135deg, #e8401c, #ff7043);
      color: #fff;
      box-shadow: 0 8px 18px rgba(232,64,28,.25);
    }
    .menu-item.logout { color: #ff8d8d; }
    .menu-item.logout:hover { background: rgba(255,95,95,.12); color: #fff; }

    .sidebar-user {
      padding: .9rem;
      border-top: 1px solid rgba(255,255,255,.08);
      display: flex;
      align-items: center;
      gap: .7rem;
      background: rgba(255,255,255,.03);
    }

    .user-avatar {
      width: 40px; height: 40px;
      border-radius: 50%;
      background: var(--primary);
      color: #fff;
      font-weight: 700;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      overflow: hidden;
    }

    .user-name { color: #fff; font-size: .84rem; font-weight: 700; }
    .user-role { color: #8aa0b7; font-size: .72rem; }

    /* ================= MAIN ================= */
    .main {
      margin-left: var(--sidebar-w);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      transition: .3s ease;
    }

    .main.collapsed { margin-left: var(--sidebar-col); }

    /* ================= TOPBAR ================= */
    .topbar {
      height: 72px;
      background: #fff;
      border-bottom: 1px solid var(--line);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1.5rem;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .topbar-left { display: flex; align-items: center; gap: 1rem; }

    .toggle-btn {
      width: 42px; height: 42px;
      border-radius: .8rem;
      border: 1px solid var(--line);
      background: #fff;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer;
      color: #5b6b7b;
      font-size: 1.15rem;
      transition: .2s;
    }

    .toggle-btn:hover { background: #f7f9fc; }

    .topbar-left h5 { margin: 0; font-size: 1.05rem; font-weight: 800; color: var(--dark); }
    .topbar-left p { margin: 0; font-size: .78rem; color: var(--muted); }

    .topbar-right { display: flex; align-items: center; gap: .65rem; }

    .search-box {
      display: flex; align-items: center; gap: .55rem;
      background: #f7f9fc;
      border: 1px solid var(--line);
      border-radius: .8rem;
      padding: .55rem .85rem;
      width: 250px;
    }

    .search-box input { border: none; background: transparent; outline: none; width: 100%; font-size: .85rem; }

    .icon-btn {
      width: 40px; height: 40px;
      border-radius: .8rem;
      border: 1px solid var(--line);
      background: #fff;
      display: flex; align-items: center; justify-content: center;
      color: #667789;
      text-decoration: none;
      font-size: 1rem;
    }

    .icon-btn:hover { background: #f7f9fc; color: #333; }

    /* ================= BREADCRUMB ================= */
    .breadcrumb-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: .75rem;
      margin-bottom: 1.5rem;
    }

    .btn-back {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      background: linear-gradient(135deg,#e8401c,#ff7043);
      color: #fff;
      font-size: .85rem;
      font-weight: 700;
      padding: .65rem 1.25rem;
      border-radius: .8rem;
      text-decoration: none;
      box-shadow: 0 8px 18px rgba(232,64,28,.22);
      transition: .2s;
    }

    .btn-back:hover { background: linear-gradient(135deg,#cb3518,#e8401c); color: #fff; transform: translateY(-1px); }

    .breadcrumb-text { font-size: .82rem; color: var(--muted); }
    .breadcrumb-text span { color: var(--dark); font-weight: 700; }

    /* ================= CONTENT ================= */
    .content { padding: 1.5rem; flex: 1; }

    .section-card {
      background: var(--card);
      border-radius: 1rem;
      border: 1px solid var(--line);
      box-shadow: 0 10px 28px rgba(31,45,61,.04);
      overflow: hidden;
    }

    .section-head {
      padding: 1rem 1.2rem;
      border-bottom: 1px solid #f1f4f8;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .section-head h6 { font-weight: 800; color: var(--dark); margin: 0; font-size: .95rem; }

    .detail-label {
      font-size: .72rem;
      font-weight: 800;
      color: var(--muted);
      text-transform: uppercase;
      margin-bottom: .35rem;
      letter-spacing: .04em;
    }

    .detail-value { font-size: .9rem; color: var(--dark); font-weight: 500; line-height: 1.7; }

    .main-photo {
      width: 100%;
      height: 250px;
      object-fit: cover;
      border-radius: 1rem;
      border: 1px solid var(--line);
    }

    .thumb-photo {
      width: 100%;
      height: 95px;
      object-fit: cover;
      border-radius: .9rem;
      border: 1px solid var(--line);
      cursor: pointer;
      transition: .2s;
    }

    .thumb-photo:hover { transform: scale(1.02); }

    .foto-placeholder {
      width: 100%;
      height: 120px;
      background: #f8fafd;
      border: 2px dashed #dfe7f0;
      border-radius: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      color: #8fa3b8;
      font-size: .82rem;
      text-align: center;
      padding: 1rem;
    }

    .badge-soft-orange {
      background: #fff5f2;
      color: var(--primary);
      border: 1px solid #ffd0c0;
      font-weight: 700;
      font-size: .75rem;
      padding: .45rem .8rem;
      border-radius: 999px;
    }

    .badge-soft-blue {
      background: #eef6ff;
      color: #2563eb;
      border: 1px solid #bfdbfe;
      font-size: .7rem;
      font-weight: 700;
      padding: .38rem .65rem;
      border-radius: 999px;
    }

    .mini-note { font-size: .78rem; color: var(--muted); }

    table thead th {
      font-size: .7rem;
      font-weight: 800;
      color: var(--muted);
      letter-spacing: .05em;
      border: 0;
      padding: .9rem 1rem;
      background: #f8fafd;
      white-space: nowrap;
    }

    table tbody td {
      font-size: .84rem;
      color: #333;
      padding: .95rem 1rem;
      border-color: #f0f3f8;
      vertical-align: top;
    }

    .link-p { color: var(--primary); font-size: .82rem; font-weight: 700; text-decoration: none; }
    .link-p:hover { color: #cb3518; }

    #map {
      height: 320px;
      border-radius: .8rem;
      border: 1px solid var(--line);
      margin-top: .8rem;
    }

    .owner-footer {
      background: #fff;
      border-top: 1px solid var(--line);
      padding: .9rem 1.5rem;
      text-align: center;
      color: var(--muted);
      font-size: .76rem;
    }

    /* notif dot dari navbar */
    .notif-dot {
      position: absolute;
      top: 6px; right: 6px;
      width: 8px; height: 8px;
      background: #e8401c;
      border-radius: 50%;
      border: 2px solid #fff;
    }

    @media (max-width: 991px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.show { transform: translateX(0); }
      .main { margin-left: 0 !important; }
      .search-box { width: 160px; }
    }
  </style>
</head>
<body>

  @include('owner._sidebar')

  <div class="main" id="mainContent">

    {{-- TOPBAR via include supaya konsisten --}}
    @include('owner._navbar')

    <div class="content">

      {{-- BREADCRUMB --}}
      <div class="breadcrumb-bar">
        <a href="{{ route('owner.kost.index') }}" class="btn-back">
          <i class="bi bi-arrow-left-circle-fill"></i>
          Kembali ke Data Kost
        </a>
        <div class="breadcrumb-text">
          <i class="bi bi-house me-1"></i>
          Data Kost &rsaquo;
          <span>{{ $kost->nama_kost }}</span>
        </div>
      </div>

      <div class="row g-4">

        {{-- KOLOM KIRI --}}
        <div class="col-12 col-lg-4">
          <div class="section-card p-3">

            {{-- FOTO UTAMA + GALERI --}}
            <div class="mb-3">
              <div class="detail-label mb-2" style="font-size:.85rem;font-weight:800;color:var(--dark);text-transform:none;letter-spacing:0;display:flex;align-items:center;gap:.4rem;">
                <i class="bi bi-images" style="color:var(--primary);"></i> Foto Kost
              </div>
              @if($kost->images->isNotEmpty())
                {{-- Foto Utama --}}
                <div style="position:relative;border-radius:1rem;overflow:hidden;margin-bottom:.6rem;">
                  <a href="{{ asset('storage/' . $kost->images->first()->image_path) }}" class="glightbox" data-gallery="kost-gallery" id="mainLightboxLink">
                    <img id="mainPreview"
                         src="{{ asset('storage/' . $kost->images->first()->image_path) }}"
                         class="main-photo"
                         style="height:240px;">
                  </a>
                  @if($kost->images->first()->kategori)
                    <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,.65),transparent);padding:.7rem .9rem;pointer-events:none;">
                      <span style="color:#fff;font-size:.78rem;font-weight:700;"><i class="bi bi-tag-fill me-1"></i>{{ $kost->images->first()->kategori }}</span>
                    </div>
                  @endif
                </div>
                {{-- Thumbnail Galeri --}}
                <div class="row g-2">
                  @foreach($kost->images as $img)
                    <div class="col-4">
                      <div style="position:relative;border-radius:.75rem;overflow:hidden;cursor:pointer;">
                        <a href="{{ asset('storage/'.$img->image_path) }}" class="glightbox" data-gallery="kost-gallery" 
                           onclick="changeMainPhoto('{{ asset('storage/'.$img->image_path) }}', this.parentElement); event.preventDefault();">
                          <img src="{{ asset('storage/'.$img->image_path) }}"
                               class="thumb-photo"
                               style="height:80px;">
                        </a>
                        @if($img->kategori)
                          <div style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.6);padding:.18rem .4rem;text-align:center;pointer-events:none;">
                            <span style="color:#fff;font-size:.64rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">{{ $img->kategori }}</span>
                          </div>
                        @endif
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="foto-placeholder mb-3" style="height:200px;">
                  <i class="bi bi-image fs-1 mb-2 opacity-50"></i>
                  <span>Belum ada foto kost</span>
                </div>
              @endif
            </div>

            {{-- INFO SINGKAT --}}
            <div class="mb-3">
              <div class="detail-label">Nama Kost</div>
              <div class="detail-value fw-bold" style="font-size:1.05rem;">{{ $kost->nama_kost }}</div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-6">
                <div class="detail-label">Tipe Kost</div>
                <div class="detail-value">{{ $kost->tipe_kost ?? '—' }}</div>
              </div>
              <div class="col-6">
                <div class="detail-label">Status</div>
                <span class="badge bg-{{ $kost->status == 'aktif' ? 'success' : 'secondary' }}">
                  {{ ucfirst($kost->status) }}
                </span>
              </div>
            </div>

            <div class="mb-3">
  {{-- Harga Bulanan --}}
  <div class="detail-label">Harga Per Bulan</div>
  <div class="fw-bold" style="color:var(--primary);font-size:1.15rem;">
    @if($kost->harga_mulai)
      Rp {{ number_format($kost->harga_mulai,0,',','.') }}
      @if($kost->harga_sampai)
        – Rp {{ number_format($kost->harga_sampai,0,',','.') }}
      @endif
    @else
      —
    @endif
  </div>
  <small class="text-muted">Harga dapat berbeda tiap kamar</small>

  {{-- Harga Harian --}}
  @if($kost->ada_harian && $kost->harga_harian_mulai)
    <div class="detail-label mt-3">Harga Per Hari</div>
    <div class="fw-bold" style="color:#0369a1;font-size:1.15rem;">
      Rp {{ number_format($kost->harga_harian_mulai,0,',','.') }}
      @if($kost->harga_harian_sampai)
        – Rp {{ number_format($kost->harga_harian_sampai,0,',','.') }}
      @endif
    </div>
    <small class="text-muted">Harga harian dapat berbeda tiap kamar</small>
  @endif
</div>

            <div class="d-flex gap-2 pt-3 border-top">
              <a href="{{ route('owner.kost.edit', $kost->id_kost) }}"
                 class="btn btn-warning btn-sm flex-fill rounded-3">
                <i class="bi bi-pencil"></i> Edit
              </a>
              <form action="{{ route('owner.kost.destroy', $kost->id_kost) }}"
                    method="POST"
                    class="flex-fill js-confirm-delete"
                    data-delete-message="Yakin ingin menghapus kost &quot;{{ $kost->nama_kost }}&quot;? Semua kamar terkait juga akan dihapus.">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm w-100 rounded-3" type="submit">
                  <i class="bi bi-trash"></i> Hapus
                </button>
              </form>
            </div>

          </div>
        </div>

        {{-- KOLOM KANAN --}}
        <div class="col-12 col-lg-8">

          {{-- LOKASI --}}
          <div class="section-card mb-4">
            <div class="section-head">
              <h6><i class="bi bi-geo-alt me-1" style="color:var(--primary)"></i> Lokasi Kost</h6>
            </div>
            <div class="p-3">
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="detail-label">Kota / Kabupaten</div>
                  <div class="detail-value">{{ $kost->kota ?? '—' }}</div>
                </div>
                <div class="col-12">
                  <div class="detail-label">Alamat Lengkap</div>
                  <div class="detail-value">{{ $kost->alamat ?? '—' }}</div>
                </div>
                @if($kost->latitude && $kost->longitude)
                  <div class="col-md-6">
                    <div class="detail-label">Latitude</div>
                    <div class="detail-value">{{ $kost->latitude }}</div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-label">Longitude</div>
                    <div class="detail-value">{{ $kost->longitude }}</div>
                  </div>
                  <div class="col-12">
                    <div id="map"></div>
                  </div>
                @endif
              </div>
            </div>
          </div>

          {{-- INFORMASI KOST --}}
          <div class="section-card mb-4">
            <div class="section-head">
              <h6><i class="bi bi-card-text me-1" style="color:var(--primary)"></i> Informasi Kost</h6>
            </div>
            <div class="p-3">

              <div class="mb-4">
                <div class="detail-label">Deskripsi Kost</div>
                <div class="detail-value">{{ $kost->deskripsi ?? '—' }}</div>
              </div>

              <div class="mb-4">
                <div class="detail-label">Fasilitas Umum Kost</div>
                @php
                  $fasilitasKost = [];
                  if (!empty($kost->fasilitas)) {
                    if (is_array($kost->fasilitas)) {
                      $fasilitasKost = $kost->fasilitas;
                    } else {
                      $decoded = json_decode($kost->fasilitas, true);
                      $fasilitasKost = is_array($decoded) ? $decoded : explode(',', $kost->fasilitas);
                    }
                  }
                @endphp
                <div class="d-flex flex-wrap gap-2 mt-2">
                  @forelse($fasilitasKost as $f)
                    <span class="badge-soft-orange">{{ trim($f) }}</span>
                  @empty
                    <span class="text-muted" style="font-size:.82rem;">Belum ada fasilitas umum</span>
                  @endforelse
                </div>
                <small class="text-muted d-block mt-2">Fasilitas digunakan bersama seluruh penghuni kost.</small>
              </div>

              <div>
                <div class="detail-label">Aturan Kost</div>
                <div class="detail-value">{{ $kost->aturan ?? '—' }}</div>
              </div>

              {{-- FOTO FASILITAS UMUM --}}
              @if($kost->generalFacilities->isNotEmpty())
              <div class="mt-4 pt-3 border-top">
                <div class="detail-label mb-2" style="font-size:.82rem;font-weight:800;color:var(--dark);text-transform:none;letter-spacing:0;display:flex;align-items:center;gap:.4rem;">
                  <i class="bi bi-camera" style="color:var(--primary);"></i> Foto Fasilitas Umum
                </div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:.65rem;">
                  @foreach($kost->generalFacilities as $fac)
                    <div style="position:relative;border-radius:.75rem;overflow:hidden;border:1.5px solid var(--line);background:#f8fafc;box-shadow:0 2px 10px rgba(0,0,0,.05);transition:.2s;" id="fac-item-{{ $fac->id }}"
                         onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,.1)'"
                         onmouseout="this.style.transform='none';this.style.boxShadow='0 2px 10px rgba(0,0,0,.05)'">
                      <a href="{{ asset('storage/'.$fac->foto) }}" class="glightbox" data-gallery="fasilitas-gallery" data-glightbox="title: {{ $fac->nama }}">
                        <img src="{{ asset('storage/'.$fac->foto) }}" alt="{{ $fac->nama }}"
                             style="width:100%;height:90px;object-fit:cover;display:block;">
                      </a>
                      <div style="padding:.45rem .55rem;border-top:1px solid #f0f3f8;">
                        <div style="font-size:.72rem;font-weight:700;color:var(--dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                          {{ $fac->nama }}
                        </div>
                      </div>
                      <button type="button"
                        onclick="hapusFasilitas({{ $fac->id }}, {{ $kost->id_kost }}, this)"
                        style="position:absolute;top:5px;right:5px;width:26px;height:26px;border-radius:50%;background:rgba(220,38,38,.85);border:none;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.72rem;transition:.2s;opacity:0;"
                        class="fac-del-btn"
                        title="Hapus foto fasilitas ini">
                        <i class="bi bi-x-lg"></i>
                      </button>
                    </div>
                  @endforeach
                </div>
                <small class="text-muted d-block mt-2" style="font-size:.7rem;">Hover foto untuk melihat tombol hapus. Untuk menambah foto baru, gunakan tombol Edit di atas.</small>
              </div>
              @endif

            </div>
          </div>

          {{-- DAFTAR KAMAR --}}
          <div class="section-card">
            <div class="section-head">
              <h6>
                <i class="bi bi-door-open me-1" style="color:var(--primary)"></i>
                Daftar Kamar ({{ $kost->rooms->count() }})
              </h6>
              <a href="{{ route('owner.kamar.index') }}" class="link-p">Kelola Kamar</a>
            </div>

            <div class="px-3 pt-3 pb-0">
              <small class="mini-note">Setiap kamar dapat memiliki harga, ukuran, dan fasilitas berbeda.</small>
            </div>

            @if($kost->rooms->isEmpty())
              <div class="text-center py-5 text-muted" style="font-size:.82rem;">
                <i class="bi bi-door-open fs-2 d-block mb-2 opacity-25"></i>
                Belum ada kamar ditambahkan
              </div>
            @else
              <div class="table-responsive">
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th>NO KAMAR</th>
                      <th>TIPE</th>
                      <th>UKURAN</th>
                      <th>HARGA</th>
                      <th>FASILITAS KAMAR</th>
                      <th>STATUS</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($kost->rooms as $room)
                      <tr>
                        <td class="fw-semibold">{{ $room->nomor_kamar ?? '—' }}</td>
                        <td>{{ $room->tipe_kamar ?? '—' }}</td>
                        <td>{{ $room->ukuran ? $room->ukuran.' m²' : '—' }}</td>
                        <td style="min-width:170px;">
                          <div class="small">
                            @if($room->aktif_bulanan && $room->harga_per_bulan)
                              <div class="mb-1"><strong>Bulanan:</strong><br>Rp {{ number_format($room->harga_per_bulan,0,',','.') }}</div>
                            @endif
                            @if($room->aktif_harian && $room->harga_harian)
                              <div><strong>Harian:</strong><br>Rp {{ number_format($room->harga_harian,0,',','.') }}</div>
                            @endif
                            @if((!$room->aktif_bulanan || !$room->harga_per_bulan) && (!$room->aktif_harian || !$room->harga_harian))
                              <span class="text-muted">—</span>
                            @endif
                          </div>
                        </td>
                        <td style="min-width:220px;">
                          @php
                            $fasilitasKamar = [];
                            if (!empty($room->fasilitas)) {
                              if (is_array($room->fasilitas)) {
                                $fasilitasKamar = $room->fasilitas;
                              } else {
                                $decoded = json_decode($room->fasilitas, true);
                                $fasilitasKamar = is_array($decoded) ? $decoded : explode(',', $room->fasilitas);
                              }
                            }
                          @endphp
                          @if(count($fasilitasKamar) > 0)
                            <div class="d-flex flex-wrap gap-1">
                              @foreach($fasilitasKamar as $f)
                                <span class="badge-soft-blue">{{ trim($f) }}</span>
                              @endforeach
                            </div>
                          @else
                            <span class="text-muted small">Belum ada fasilitas</span>
                          @endif
                        </td>
                        <td>
                          <span class="badge bg-{{ $room->status_kamar == 'tersedia' ? 'success' : 'danger' }}">
                            {{ ucfirst($room->status_kamar ?? '—') }}
                          </span>
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

    <footer class="owner-footer">
      © {{ date('Y') }} KostFinder — Panel Pemilik Kost. All rights reserved.
    </footer>
  </div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

  <script>
    // ── SIDEBAR TOGGLE ──
    function toggleSidebar() {
      const s = document.getElementById('sidebar');
      const m = document.getElementById('mainContent');
      if (window.innerWidth <= 991) {
        s?.classList.toggle('show');
      } else {
        s?.classList.toggle('collapsed');
        m?.classList.toggle('collapsed');
      }
    }

    // ── GLIGHTBOX INITIALIZATION ──
    const lightbox = GLightbox({
      selector: '.glightbox',
      touchNavigation: true,
      loop: true,
      zoomable: true
    });

    // ── GANTI FOTO UTAMA ──
    function changeMainPhoto(src, thumbEl) {
      const el = document.getElementById('mainPreview');
      if (el) el.src = src;
      
      const link = document.getElementById('mainLightboxLink');
      if (link) link.href = src;

      // Ambil label dari thumbnail yang diklik
      const mainWrap = el ? el.closest('div[style*="border-radius:1rem"]') || el.parentElement : null;
      if (mainWrap) {
        // Hapus overlay lama kalau ada
        const oldOverlay = mainWrap.querySelector('.foto-label-overlay');
        if (oldOverlay) oldOverlay.remove();
        // Tambahkan overlay baru dari thumbnail
        if (thumbEl) {
          const thumbLabel = thumbEl.querySelector('span[style*="font-size:.64rem"]');
          if (thumbLabel && thumbLabel.textContent.trim()) {
            const overlay = document.createElement('div');
            overlay.className = 'foto-label-overlay';
            overlay.style.cssText = 'position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,.65),transparent);padding:.7rem .9rem;';
            overlay.innerHTML = `<span style="color:#fff;font-size:.78rem;font-weight:700;"><i class="bi bi-tag-fill me-1"></i>${thumbLabel.textContent.trim()}</span>`;
            mainWrap.style.position = 'relative';
            mainWrap.appendChild(overlay);
          }
        }
      }
    }

    // ── LEAFLET MAP ──
    @if($kost->latitude && $kost->longitude)
      const map = L.map('map').setView([{{ $kost->latitude }}, {{ $kost->longitude }}], 16);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);
      L.marker([{{ $kost->latitude }}, {{ $kost->longitude }}])
        .addTo(map)
        .bindPopup("{{ $kost->nama_kost }}")
        .openPopup();
    @endif

    // ── HOVER TAMPILKAN TOMBOL HAPUS FASILITAS ──
    document.querySelectorAll('[id^="fac-item-"]').forEach(function(card) {
      const btn = card.querySelector('.fac-del-btn');
      if (!btn) return;
      card.addEventListener('mouseenter', () => { btn.style.opacity = '1'; });
      card.addEventListener('mouseleave', () => { btn.style.opacity = '0'; });
    });

    // ── HAPUS FASILITAS INDIVIDUAL via AJAX ──
    async function hapusFasilitas(facId, kostId, btn) {
      if (!confirm('Yakin hapus foto fasilitas ini?')) return;
      btn.disabled = true;
      btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';

      try {
        const response = await fetch(`/owner/kost/${kostId}/facility/${facId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          }
        });
        const data = await response.json();
        if (data.success) {
          const card = document.getElementById('fac-item-' + facId);
          if (card) {
            card.style.transition = 'all .3s ease';
            card.style.transform  = 'scale(.8)';
            card.style.opacity    = '0';
            setTimeout(() => card.remove(), 300);
          }
          showFacToast('Foto fasilitas berhasil dihapus!', 'success');
        } else {
          btn.disabled = false;
          btn.innerHTML = '<i class="bi bi-x-lg"></i>';
          showFacToast('Gagal menghapus.', 'error');
        }
      } catch(e) {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-x-lg"></i>';
        showFacToast('Terjadi kesalahan.', 'error');
      }
    }

    function showFacToast(msg, type) {
      const existing = document.getElementById('facToast');
      if (existing) existing.remove();
      const t = document.createElement('div');
      t.id = 'facToast';
      t.style.cssText = `position:fixed;bottom:24px;right:24px;z-index:9999;background:${type==='success'?'#16a34a':'#dc2626'};color:#fff;padding:.75rem 1.2rem;border-radius:.75rem;font-size:.82rem;font-weight:700;box-shadow:0 8px 24px rgba(0,0,0,.2);display:flex;align-items:center;gap:.5rem;`;
      t.innerHTML = `<i class="bi bi-${type==='success'?'check-circle-fill':'x-circle-fill'}"></i> ${msg}`;
      document.body.appendChild(t);
      setTimeout(() => { t.style.opacity='0'; t.style.transition='opacity .3s'; setTimeout(()=>t.remove(),300); }, 2800);
    }
  </script>

</body>
</html>