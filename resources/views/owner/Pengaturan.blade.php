<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengaturan - KostFinder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root { --sidebar-w:200px; --sidebar-col:60px; --primary:#e8401c; --dark:#1e2d3d; --bg:#f0f4f8; }
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Plus Jakarta Sans',sans-serif; background:var(--bg); display:flex; min-height:100vh; align-items:stretch; }
    .sidebar { width:var(--sidebar-w); min-height:100vh; background:var(--dark); position:fixed; top:0; left:0; display:flex; flex-direction:column; z-index:200; transition:width .3s ease; overflow:hidden; }
    .sidebar.collapsed { width:var(--sidebar-col); }
    .sidebar-brand { padding:1.2rem .9rem; border-bottom:1px solid rgba(255,255,255,.08); display:flex; align-items:center; gap:.6rem; min-height:60px; white-space:nowrap; }
    .brand-icon { width:32px; height:32px; flex-shrink:0; background:var(--primary); border-radius:.5rem; display:flex; align-items:center; justify-content:center; font-size:1rem; }
    .brand-text { overflow:hidden; transition:opacity .2s; }
    .brand-text .name { font-size:1rem; font-weight:800; color:#fff; }
    .brand-text .name span { color:var(--primary); }
    .brand-text .sub { font-size:.65rem; color:#7a92aa; }
    .sidebar.collapsed .brand-text { opacity:0; width:0; }
    .sidebar-menu { padding:.7rem .5rem; flex:1; }
    .menu-label { font-size:.6rem; font-weight:700; letter-spacing:.1em; color:#7a92aa; padding:.5rem .5rem .2rem; white-space:nowrap; transition:opacity .2s; }
    .sidebar.collapsed .menu-label { opacity:0; }
    .menu-item { display:flex; align-items:center; gap:.65rem; padding:.58rem .65rem; border-radius:.55rem; color:#a0b4c4; text-decoration:none; font-size:.82rem; font-weight:500; margin-bottom:.1rem; transition:all .2s; white-space:nowrap; cursor:pointer; border:0; background:none; width:100%; text-align:left; }
    .menu-item i { font-size:.95rem; width:20px; flex-shrink:0; }
    .menu-item span { overflow:hidden; transition:opacity .2s; }
    .sidebar.collapsed .menu-item span { opacity:0; width:0; }
    .menu-item:hover { background:rgba(255,255,255,.07); color:#fff; }
    .menu-item.active { background:var(--primary); color:#fff; }
    .menu-item.logout { color:#f87171; }
    .menu-item.logout:hover { background:rgba(248,113,113,.1); }
    .sidebar-user { padding:.85rem .9rem; border-top:1px solid rgba(255,255,255,.08); display:flex; align-items:center; gap:.6rem; white-space:nowrap; }
    .user-avatar { width:32px; height:32px; border-radius:50%; background:var(--primary); color:#fff; font-weight:700; font-size:.8rem; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
    .user-info { overflow:hidden; transition:opacity .2s; }
    .sidebar.collapsed .user-info { opacity:0; width:0; }
    .user-name { color:#fff; font-size:.8rem; font-weight:600; }
    .user-role { color:#7a92aa; font-size:.68rem; }
    .main { margin-left:var(--sidebar-w); flex:1; transition:margin-left .3s ease; display:flex; flex-direction:column; min-height:100vh; }
    .main.collapsed { margin-left:var(--sidebar-col); }
    .topbar { background:#fff; height:60px; padding:0 1.5rem; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e4e9f0; position:sticky; top:0; z-index:100; }
    .topbar-left { display:flex; align-items:center; gap:.8rem; }
    .topbar-left h5 { font-size:.98rem; font-weight:800; color:var(--dark); margin:0; }
    .topbar-left p { font-size:.72rem; color:#8fa3b8; margin:0; }
    .topbar-right { display:flex; align-items:center; gap:.6rem; }
    .icon-btn { width:34px; height:34px; border-radius:.5rem; background:#f0f4f8; border:1px solid #dde3ed; display:flex; align-items:center; justify-content:center; color:#555; font-size:.9rem; cursor:pointer; text-decoration:none; position:relative; }
    .icon-btn:hover { background:#e4e9f0; color:#333; }
    .content { padding:1.4rem; flex:1; }
    .layout-2col { display:flex; gap:1rem; align-items:flex-start; }
    .col-kiri { flex:0 0 65%; min-width:0; }
    .col-kanan { flex:1; min-width:0; position:sticky; top:70px; }
    @media(max-width:991px){
      .layout-2col { flex-direction:column; }
      .col-kiri, .col-kanan { flex:0 0 100%; position:static; }
    }
    .form-card { background:#fff; border-radius:.85rem; border:1px solid #e4e9f0; box-shadow:0 2px 6px rgba(0,0,0,.04); padding:1.5rem; margin-bottom:1rem; }
    .form-card h6 { font-weight:700; color:var(--dark); font-size:.9rem; margin-bottom:1rem; padding-bottom:.6rem; border-bottom:1px solid #f0f3f8; display:flex; align-items:center; gap:.4rem; }
    .form-label { font-size:.8rem; font-weight:600; color:#444; margin-bottom:.3rem; }
    .form-control { font-size:.85rem; border-color:#e4e9f0; border-radius:.55rem; padding:.5rem .8rem; }
    .form-control:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(232,64,28,.1); }
    .btn-save { background:var(--primary); color:#fff; font-weight:700; border:0; border-radius:.55rem; padding:.5rem 1.4rem; font-size:.85rem; cursor:pointer; }
    .btn-save:hover { background:#cb3518; }
    .avatar-wrap { position:relative; display:inline-block; }
    .avatar-big { width:90px; height:90px; border-radius:50%; background:var(--primary); color:#fff; font-size:2rem; font-weight:800; display:flex; align-items:center; justify-content:center; border:3px solid #fff; box-shadow:0 2px 10px rgba(0,0,0,.1); }
    .avatar-big img { width:90px; height:90px; border-radius:50%; object-fit:cover; }
    .avatar-edit { position:absolute; bottom:0; right:0; width:26px; height:26px; background:var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; border:2px solid #fff; }
    .avatar-edit i { color:#fff; font-size:.7rem; }
    .notif-item { display:flex; justify-content:space-between; align-items:center; padding:.75rem 0; border-bottom:1px solid #f0f3f8; }
    .notif-item:last-child { border:0; padding-bottom:0; }
    .notif-label { font-size:.83rem; font-weight:600; color:var(--dark); }
    .notif-sub { font-size:.73rem; color:#8fa3b8; }
    .form-switch .form-check-input { width:2.5em; height:1.3em; cursor:pointer; }
    .form-switch .form-check-input:checked { background-color:var(--primary); border-color:var(--primary); }
    .owner-footer { background:#fff; border-top:1px solid #e4e9f0; padding:.8rem 1.5rem; text-align:center; color:#8fa3b8; font-size:.72rem; margin-top:auto; }
    .danger-zone { border:1px solid #fee2e2; border-radius:.85rem; padding:1.2rem 1.5rem; background:#fff; margin-top:1rem; }
    .upload-box { border:2px dashed #e4e9f0; border-radius:.75rem; padding:1.5rem; text-align:center; cursor:pointer; transition:all .2s; background:#f8fafd; display:block; }
    .upload-box:hover { border-color:var(--primary); background:#fff5f2; }
    .upload-box i { font-size:1.8rem; color:#c0ccd8; margin-bottom:.4rem; display:block; }
    .preview-img { width:100%; height:140px; object-fit:cover; border-radius:.55rem; margin-top:.5rem; }

    /* ===== VERIFIKASI STYLES BARU ===== */
    .verif-banner { border-radius:.85rem; padding:1rem 1.2rem; margin-bottom:1.2rem; display:flex; align-items:flex-start; gap:.9rem; }
    .verif-banner .icon-wrap { width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1.1rem; }
    .verif-banner .vb-title { font-weight:700; font-size:.87rem; margin-bottom:.2rem; }
    .verif-banner .vb-sub { font-size:.76rem; line-height:1.55; }
    .verif-banner.done  { background:#f0fdf4; border:1.5px solid #86efac; }
    .verif-banner.done .icon-wrap { background:#dcfce7; }
    .verif-banner.done .vb-title { color:#15803d; }
    .verif-banner.done .vb-sub { color:#4b7a5a; }
    .verif-banner.pending { background:#fffbeb; border:1.5px solid #fde68a; }
    .verif-banner.pending .icon-wrap { background:#fef9c3; }
    .verif-banner.pending .vb-title { color:#b45309; }
    .verif-banner.pending .vb-sub { color:#78532a; }
    .verif-banner.ditolak { background:#fef2f2; border:1.5px solid #fca5a5; }
    .verif-banner.ditolak .icon-wrap { background:#fee2e2; }
    .verif-banner.ditolak .vb-title { color:#b91c1c; }
    .verif-banner.ditolak .vb-sub { color:#7f1d1d; }
    .verif-banner.belum { background:#fff5f2; border:1.5px solid #ffd0c0; }
    .verif-banner.belum .icon-wrap { background:#ffe4dc; }
    .verif-banner.belum .vb-title { color:#c2410c; }
    .verif-banner.belum .vb-sub { color:#7c2d12; }

    /* catatan admin */
    .catatan-admin { background:#fef2f2; border:1.5px solid #fca5a5; border-radius:.75rem; padding:1rem 1.1rem; margin-bottom:1.2rem; }
    .catatan-admin .ca-header { display:flex; align-items:center; gap:.5rem; margin-bottom:.4rem; }
    .catatan-admin .ca-header i { color:#dc2626; font-size:.95rem; }
    .catatan-admin .ca-header span { font-size:.82rem; font-weight:700; color:#b91c1c; }
    .catatan-admin .ca-body { font-size:.81rem; color:#7f1d1d; line-height:1.6; padding-left:1.5rem; }

    /* doc preview card */
    .doc-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap:.75rem; margin-bottom:1rem; }
    .doc-card { border:1px solid #e4e9f0; border-radius:.7rem; overflow:hidden; background:#f8fafd; }
    .doc-card .doc-thumb { width:100%; height:110px; object-fit:cover; display:block; cursor:pointer; transition:opacity .2s; }
    .doc-card .doc-thumb:hover { opacity:.85; }
    .doc-card .doc-label { font-size:.72rem; font-weight:600; color:#555; padding:.45rem .7rem; border-top:1px solid #f0f3f8; display:flex; align-items:center; gap:.35rem; }
    .doc-card .doc-label i { color:#8fa3b8; font-size:.8rem; }
    .doc-card.doc-file { display:flex; align-items:center; justify-content:center; flex-direction:column; gap:.4rem; padding:1rem; cursor:pointer; text-decoration:none; }
    .doc-card.doc-file i { font-size:2rem; color:#e8401c; }
    .doc-card.doc-file span { font-size:.73rem; font-weight:600; color:#555; text-align:center; }

    /* step indicator */
    .verif-steps { display:flex; gap:0; margin-bottom:1.3rem; }
    .step { flex:1; text-align:center; position:relative; }
    .step::after { content:''; position:absolute; top:14px; left:50%; width:100%; height:2px; background:#e4e9f0; z-index:0; }
    .step:last-child::after { display:none; }
    .step-dot { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto .35rem; position:relative; z-index:1; font-size:.72rem; font-weight:700; border:2px solid #e4e9f0; background:#fff; color:#94a3b8; }
    .step.active .step-dot { background:var(--primary); border-color:var(--primary); color:#fff; }
    .step.done .step-dot { background:#16a34a; border-color:#16a34a; color:#fff; }
    .step.done::after { background:#16a34a; }
    .step-label { font-size:.68rem; font-weight:600; color:#94a3b8; }
    .step.active .step-label { color:var(--primary); }
    .step.done .step-label { color:#16a34a; }

    /* lightbox */
    #lightbox { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.85); z-index:99999; align-items:center; justify-content:center; }
    #lightbox.show { display:flex; }
    #lightbox img { max-width:90vw; max-height:85vh; border-radius:.75rem; box-shadow:0 20px 60px rgba(0,0,0,.5); }
    #lightbox .lb-close { position:absolute; top:1rem; right:1.2rem; color:#fff; font-size:1.8rem; cursor:pointer; line-height:1; }
  </style>
</head>
<body>

  @include('owner._sidebar')

  <div class="main" id="mainContent">
    @include('owner._navbar')

    <div class="content">

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" style="font-size:.83rem;border-radius:.7rem;">
          <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" style="font-size:.83rem;border-radius:.7rem;">
          <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" style="font-size:.83rem;border-radius:.7rem;">
          <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <div class="layout-2col">

        {{-- ==================== KOLOM KIRI ==================== --}}
        <div class="col-kiri">

          {{-- PROFIL --}}
          <div class="form-card">
            <h6><i class="bi bi-person-circle" style="color:var(--primary)"></i> Profil Saya</h6>
            <div class="d-flex align-items-center gap-3 mb-4">
              <div class="avatar-wrap">
                @if(auth()->user()->foto_profil)
                  <div class="avatar-big"><img src="{{ asset('storage/'.auth()->user()->foto_profil) }}" alt="Foto Profil"></div>
                @else
                  <div class="avatar-big">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                @endif
                <label for="fotoInput" class="avatar-edit" title="Ganti foto"><i class="bi bi-camera"></i></label>
              </div>
              <div>
                <div style="font-weight:700;font-size:1rem;color:var(--dark);">{{ auth()->user()->name }}</div>
                <div style="font-size:.8rem;color:#8fa3b8;">{{ auth()->user()->email }}</div>
                <div style="font-size:.75rem;font-weight:600;margin-top:.3rem;">
                  @php $sv = auth()->user()->status_verifikasi_identitas; @endphp
                  @if($sv === 'disetujui')
                    <span style="color:#16a34a;display:inline-flex;align-items:center;gap:.3rem;">
                      <i class="bi bi-patch-check-fill"></i> Identitas Terverifikasi
                    </span>
                  @elseif($sv === 'pending')
                    <span style="color:#b45309;display:inline-flex;align-items:center;gap:.3rem;">
                      <i class="bi bi-hourglass-split"></i> Menunggu Verifikasi Admin
                    </span>
                  @elseif($sv === 'ditolak')
                    <span style="color:#dc2626;display:inline-flex;align-items:center;gap:.3rem;">
                      <i class="bi bi-x-circle-fill"></i> Verifikasi Ditolak — Upload Ulang
                    </span>
                  @else
                    <span style="color:#e8401c;display:inline-flex;align-items:center;gap:.3rem;">
                      <i class="bi bi-exclamation-triangle-fill"></i> Identitas Belum Diverifikasi
                    </span>
                  @endif
                </div>
              </div>
            </div>
            <form action="{{ route('owner.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
              @csrf @method('PATCH')
              <input type="file" name="foto_profil" id="fotoInput" class="d-none" accept="image/*" onchange="this.form.submit()">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Nama Lengkap</label>
                  <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">No. Telepon</label>
                  <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('no_hp', auth()->user()->no_hp ?? '') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Kota Domisili</label>
                  <input type="text" name="kota" class="form-control" placeholder="Surabaya" value="{{ old('kota', auth()->user()->kota ?? '') }}">
                </div>
              </div>
              <div class="mt-3">
                <button type="submit" class="btn-save"><i class="bi bi-check-lg me-1"></i> Simpan Profil</button>
              </div>
            </form>
          </div>

          {{-- ===== VERIFIKASI IDENTITAS ===== --}}
          <div class="form-card">
            <h6><i class="bi bi-shield-check" style="color:var(--primary)"></i> Verifikasi Pemilik Kost</h6>

            {{-- Info box --}}
            <div class="p-3 rounded-3 mb-3" style="background:#f0f9ff;border:1px solid #bae6fd;">
              <div style="font-size:.8rem;font-weight:700;color:#0369a1;margin-bottom:.4rem;display:flex;align-items:center;gap:.4rem;">
                <i class="bi bi-info-circle-fill"></i> Mengapa Verifikasi Diperlukan?
              </div>
              <div style="font-size:.76rem;color:#0c4a6e;line-height:1.6;">
                Verifikasi memastikan kamu adalah pemilik kost yang sah. Kost hanya akan tampil ke pencari setelah identitas & kepemilikan disetujui admin.
              </div>
            </div>

            @php $sv = auth()->user()->status_verifikasi_identitas; @endphp

            {{-- ======= STATUS: DISETUJUI ======= --}}
            @if($sv === 'disetujui')

              {{-- Banner sukses --}}
              <div class="verif-banner done">
                <div class="icon-wrap"><i class="bi bi-patch-check-fill" style="color:#16a34a;font-size:1.2rem;"></i></div>
                <div>
                  <div class="vb-title">Verifikasi Selesai!</div>
                  <div class="vb-sub">Semua dokumen kamu sudah disetujui oleh Admin KostFinder. Kost kamu sudah bisa tampil ke calon penyewa.</div>
                </div>
              </div>

              {{-- Step indicator --}}
              <div class="verif-steps">
                <div class="step done">
                  <div class="step-dot"><i class="bi bi-check"></i></div>
                  <div class="step-label">Upload Dokumen</div>
                </div>
                <div class="step done">
                  <div class="step-dot"><i class="bi bi-check"></i></div>
                  <div class="step-label">Review Admin</div>
                </div>
                <div class="step done">
                  <div class="step-dot"><i class="bi bi-check"></i></div>
                  <div class="step-label">Disetujui</div>
                </div>
              </div>

              {{-- Dokumen yang sudah diupload --}}
              <div style="font-size:.8rem;font-weight:700;color:#555;margin-bottom:.6rem;">📁 Dokumen Tersimpan</div>
              <div class="doc-grid">
                @if(auth()->user()->foto_ktp)
                <div class="doc-card">
                  <img src="{{ asset('storage/'.auth()->user()->foto_ktp) }}" class="doc-thumb" alt="KTP" onclick="bukaLightbox(this.src)">
                  <div class="doc-label"><i class="bi bi-card-text"></i> Foto KTP</div>
                </div>
                @endif
                @if(auth()->user()->foto_selfie)
                <div class="doc-card">
                  <img src="{{ asset('storage/'.auth()->user()->foto_selfie) }}" class="doc-thumb" alt="Selfie" onclick="bukaLightbox(this.src)">
                  <div class="doc-label"><i class="bi bi-camera"></i> Selfie + KTP</div>
                </div>
                @endif
                @if(auth()->user()->foto_kepemilikan)
                  @php
                    $ext = pathinfo(auth()->user()->foto_kepemilikan, PATHINFO_EXTENSION);
                    $isPdf = strtolower($ext) === 'pdf';
                  @endphp
                  @if($isPdf)
                    <a href="{{ asset('storage/'.auth()->user()->foto_kepemilikan) }}" target="_blank" class="doc-card doc-file">
                      <i class="bi bi-file-earmark-pdf-fill"></i>
                      <span>Bukti Kepemilikan<br>(PDF)</span>
                    </a>
                  @else
                    <div class="doc-card">
                      <img src="{{ asset('storage/'.auth()->user()->foto_kepemilikan) }}" class="doc-thumb" alt="Kepemilikan" onclick="bukaLightbox(this.src)">
                      <div class="doc-label"><i class="bi bi-house-check"></i> Bukti Kepemilikan</div>
                    </div>
                  @endif
                @endif
              </div>

              {{-- Link update dokumen --}}
              <div style="font-size:.75rem;color:#888;margin-top:.5rem;">
                Ingin update dokumen?
                <a href="#" onclick="document.getElementById('formVerifUpdate').classList.toggle('d-none');return false;"
                  style="color:var(--primary);font-weight:600;">Klik di sini</a>
              </div>
              <div id="formVerifUpdate" class="d-none mt-3">
                <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:.65rem;padding:.8rem 1rem;font-size:.76rem;color:#78532a;margin-bottom:1rem;">
                  <i class="bi bi-exclamation-triangle me-1"></i>
                  Update dokumen akan mereset status verifikasi ke <strong>Pending</strong> dan perlu disetujui ulang oleh admin.
                </div>
                @include('owner._form_verifikasi', ['formId' => 'formUpdateDokumen'])
              </div>

            {{-- ======= STATUS: PENDING ======= --}}
            @elseif($sv === 'pending')

              <div class="verif-banner pending">
                <div class="icon-wrap"><i class="bi bi-hourglass-split" style="color:#d97706;font-size:1.1rem;"></i></div>
                <div>
                  <div class="vb-title">Sedang Direview Admin</div>
                  <div class="vb-sub">Dokumen kamu sudah kami terima dan sedang dalam proses review. Estimasi waktu: <strong>1×24 jam</strong>. Kamu akan mendapat notifikasi setelah selesai.</div>
                </div>
              </div>

              {{-- Step indicator --}}
              <div class="verif-steps">
                <div class="step done">
                  <div class="step-dot"><i class="bi bi-check"></i></div>
                  <div class="step-label">Upload Dokumen</div>
                </div>
                <div class="step active">
                  <div class="step-dot">2</div>
                  <div class="step-label">Review Admin</div>
                </div>
                <div class="step">
                  <div class="step-dot">3</div>
                  <div class="step-label">Disetujui</div>
                </div>
              </div>

              {{-- Preview dokumen yang sudah diupload --}}
              @if(auth()->user()->foto_ktp || auth()->user()->foto_selfie || auth()->user()->foto_kepemilikan)
              <div style="font-size:.8rem;font-weight:700;color:#555;margin-bottom:.6rem;">📁 Dokumen yang Dikirim</div>
              <div class="doc-grid">
                @if(auth()->user()->foto_ktp)
                <div class="doc-card">
                  <img src="{{ asset('storage/'.auth()->user()->foto_ktp) }}" class="doc-thumb" alt="KTP" onclick="bukaLightbox(this.src)">
                  <div class="doc-label"><i class="bi bi-card-text"></i> Foto KTP</div>
                </div>
                @endif
                @if(auth()->user()->foto_selfie)
                <div class="doc-card">
                  <img src="{{ asset('storage/'.auth()->user()->foto_selfie) }}" class="doc-thumb" alt="Selfie" onclick="bukaLightbox(this.src)">
                  <div class="doc-label"><i class="bi bi-camera"></i> Selfie + KTP</div>
                </div>
                @endif
                @if(auth()->user()->foto_kepemilikan)
                  @php $ext2 = pathinfo(auth()->user()->foto_kepemilikan, PATHINFO_EXTENSION); @endphp
                  @if(strtolower($ext2) === 'pdf')
                    <a href="{{ asset('storage/'.auth()->user()->foto_kepemilikan) }}" target="_blank" class="doc-card doc-file">
                      <i class="bi bi-file-earmark-pdf-fill"></i>
                      <span>Bukti Kepemilikan<br>(PDF)</span>
                    </a>
                  @else
                    <div class="doc-card">
                      <img src="{{ asset('storage/'.auth()->user()->foto_kepemilikan) }}" class="doc-thumb" alt="Kepemilikan" onclick="bukaLightbox(this.src)">
                      <div class="doc-label"><i class="bi bi-house-check"></i> Bukti Kepemilikan</div>
                    </div>
                  @endif
                @endif
              </div>
              @endif

            {{-- ======= STATUS: DITOLAK ======= --}}
            @elseif($sv === 'ditolak')

              <div class="verif-banner ditolak">
                <div class="icon-wrap"><i class="bi bi-x-circle-fill" style="color:#dc2626;font-size:1.2rem;"></i></div>
                <div>
                  <div class="vb-title">Verifikasi Ditolak</div>
                  <div class="vb-sub">Maaf, dokumen kamu tidak memenuhi syarat. Silakan baca catatan admin di bawah dan upload ulang dokumen yang benar.</div>
                </div>
              </div>

              {{-- Catatan Admin --}}
              @if(auth()->user()->catatan_verifikasi)
              <div class="catatan-admin">
                <div class="ca-header">
                  <i class="bi bi-chat-square-text-fill"></i>
                  <span>Catatan dari Admin KostFinder</span>
                </div>
                <div class="ca-body">{{ auth()->user()->catatan_verifikasi }}</div>
              </div>
              @endif

              {{-- Step indicator --}}
              <div class="verif-steps">
                <div class="step done">
                  <div class="step-dot"><i class="bi bi-check"></i></div>
                  <div class="step-label">Upload Dokumen</div>
                </div>
                <div class="step" style="--dot-bg:#dc2626;">
                  <div class="step-dot" style="background:#fee2e2;border-color:#fca5a5;color:#dc2626;"><i class="bi bi-x"></i></div>
                  <div class="step-label" style="color:#dc2626;">Ditolak</div>
                </div>
                <div class="step">
                  <div class="step-dot">3</div>
                  <div class="step-label">Disetujui</div>
                </div>
              </div>

              <div style="font-size:.82rem;font-weight:700;color:#444;margin-bottom:.8rem;">
                <i class="bi bi-arrow-repeat me-1" style="color:var(--primary);"></i> Upload Ulang Dokumen
              </div>
              @include('owner._form_verifikasi', ['formId' => 'formVerifDitolak'])

            {{-- ======= STATUS: BELUM ======= --}}
            @else

              <div class="verif-banner belum">
                <div class="icon-wrap"><i class="bi bi-exclamation-triangle-fill" style="color:#c2410c;font-size:1.1rem;"></i></div>
                <div>
                  <div class="vb-title">Identitas Belum Diverifikasi</div>
                  <div class="vb-sub">Upload identitas kamu sekarang agar kost dapat ditampilkan kepada calon penyewa.</div>
                </div>
              </div>

              {{-- Step indicator --}}
              <div class="verif-steps">
                <div class="step active">
                  <div class="step-dot">1</div>
                  <div class="step-label">Upload Dokumen</div>
                </div>
                <div class="step">
                  <div class="step-dot">2</div>
                  <div class="step-label">Review Admin</div>
                </div>
                <div class="step">
                  <div class="step-dot">3</div>
                  <div class="step-label">Disetujui</div>
                </div>
              </div>

              @include('owner._form_verifikasi', ['formId' => 'formVerifBaru'])

            @endif
          </div>

{{-- ALAMAT PROPERTI --}}
<div class="form-card">
  <h6><i class="bi bi-geo-alt-fill" style="color:var(--primary)"></i> Alamat Properti</h6>
  <form action="{{ route('owner.pengaturan.update') }}" method="POST">
    @csrf @method('PATCH')
    <div class="row g-3">
      {{-- ALAMAT LENGKAP --}}
      <div class="col-12">
        <label class="form-label">Alamat Lengkap</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
          <input type="text" name="alamat" class="form-control" placeholder="Nama jalan, nomor, RT/RW..." value="{{ old('alamat', auth()->user()->alamat ?? '') }}">
        </div>
      </div>

      {{-- PROVINSI - readonly --}}
      <div class="col-12">
        <label class="form-label">Provinsi</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-map"></i></span>
          <input type="text" class="form-control" value="Jawa Timur" readonly
            style="background:#f3f4f6; color:#6b7280; cursor:not-allowed;">
        </div>
        <input type="hidden" name="provinsi" value="Jawa Timur">
      </div>

      {{-- KECAMATAN (KOTA/KABUPATEN) --}}
      <div class="col-md-6">
        <label class="form-label">Kecamatan</label>
        <select id="sel-kota" class="form-select form-control" onchange="onPilihKota()">
          <option value="">-- Pilih Kota --</option>
          @php $currentKota = old('kota', auth()->user()->kota ?? ''); @endphp
          <option value="Surabaya"   {{ $currentKota==='Surabaya'   ? 'selected':'' }}>Surabaya</option>
          <option value="Malang"     {{ $currentKota==='Malang'     ? 'selected':'' }}>Malang</option>
          <option value="Sidoarjo"   {{ $currentKota==='Sidoarjo'   ? 'selected':'' }}>Sidoarjo</option>
          <option value="Jember"     {{ $currentKota==='Jember'     ? 'selected':'' }}>Jember</option>
          <option value="Gresik"     {{ $currentKota==='Gresik'     ? 'selected':'' }}>Gresik</option>
          <option value="Kediri"     {{ $currentKota==='Kediri'     ? 'selected':'' }}>Kediri</option>
          <option value="Banyuwangi" {{ $currentKota==='Banyuwangi' ? 'selected':'' }}>Banyuwangi</option>
          <option value="Mojokerto"  {{ $currentKota==='Mojokerto'  ? 'selected':'' }}>Mojokerto</option>
          <option value="Pasuruan"   {{ $currentKota==='Pasuruan'   ? 'selected':'' }}>Pasuruan</option>
        </select>
        <input type="hidden" name="kota" id="hidden-kota" value="{{ $currentKota }}">
      </div>

      {{-- KELURAHAN / DESA (KECAMATAN ASLI) --}}
      <div class="col-md-6">
        <label class="form-label">Kelurahan / Desa</label>
        <select id="sel-kec" class="form-select form-control" onchange="onPilihKec()">
          <option value="">-- Pilih Kecamatan dulu --</option>
        </select>
        <input type="text" id="inp-kec" name="kecamatan" class="form-control mt-2"
          placeholder="Tulis kelurahan / desa kamu..." style="display:none;" value="{{ old('kecamatan', auth()->user()->kecamatan ?? '') }}">
        <div class="tulis-sendiri-hint" id="hint-kec" style="display:none;font-size:.75rem;color:#6b7280;margin-top:5px;">✏️ Ketik kelurahan / desa kamu di atas</div>
      </div>

      {{-- KODE POS --}}
      <div class="col-md-6">
        <label class="form-label">Kode Pos</label>
        <input type="text" name="kode_pos" id="kode_pos" class="form-control" placeholder="Contoh: 61234" value="{{ old('kode_pos', auth()->user()->kode_pos ?? '') }}">
      </div>

      <input type="hidden" name="kelurahan" id="hidden-kelurahan" value="{{ old('kelurahan', auth()->user()->kelurahan ?? '') }}">
      
    </div>
    <div class="mt-3">
      <button type="submit" class="btn-save"><i class="bi bi-check-lg me-1"></i> Simpan Alamat</button>
    </div>
  </form>
</div>

{{-- REKENING BANK --}}
<div class="form-card">
  <h6><i class="bi bi-bank" style="color:var(--primary)"></i> Daftar Rekening Bank (Maks. 5)</h6>
  
  <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.7rem;padding:.9rem;margin-bottom:1.2rem;">
    <div style="font-size:.8rem;font-weight:700;color:#15803d;margin-bottom:.3rem;display:flex;align-items:center;gap:.4rem;">
      <i class="bi bi-info-circle-fill"></i> Info Pencairan Dana
    </div>
    <div style="font-size:.76rem;color:#166534;line-height:1.55;">
      Kamu bisa mendaftarkan hingga 5 rekening berbeda. Admin akan mengirimkan hasil sewa ke salah satu rekening yang kamu pilih saat mengajukan penarikan dana.
    </div>
  </div>

  @if($banks->count() > 0)
  <div class="table-responsive mb-4">
    <table class="table table-sm" style="font-size:.85rem;">
      <thead class="table-light">
        <tr>
          <th>Bank</th>
          <th>Nomor Rekening</th>
          <th>Atas Nama</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($banks as $b)
        <tr>
          <td class="align-middle">
            <span class="fw-bold">{{ $b->nama_bank }}</span>
            @if($b->is_primary) <span class="badge bg-success" style="font-size:.65rem;">Utama</span> @endif
          </td>
          <td class="align-middle">{{ $b->nomor_rekening }}</td>
          <td class="align-middle">{{ $b->nama_pemilik }}</td>
          <td class="text-center">
            <form action="{{ route('owner.pengaturan.bank.delete', $b->id) }}" method="POST" onsubmit="return confirm('Hapus rekening ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger" style="border:none;">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @else
  <div class="text-center py-4" style="background:#f8fafc; border-radius:.8rem; border:1px dashed #cbd5e1; margin-bottom:1.5rem;">
    <i class="bi bi-credit-card-2-front" style="font-size:2rem; color:#94a3b8;"></i>
    <p class="mt-2 mb-0" style="font-size:.8rem; color:#64748b;">Belum ada rekening yang didaftarkan.</p>
  </div>
  @endif

  @if($banks->count() < 5)
  <div style="background:#f8fafc; padding:1.2rem; border-radius:.8rem; border:1px solid #e2e8f0;">
    <h7 class="fw-bold d-block mb-3" style="font-size:.85rem;">+ Tambah Rekening Baru</h7>
    <form action="{{ route('owner.pengaturan.bank.store') }}" method="POST">
      @csrf
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nama Bank / E-Wallet</label>
          <select name="nama_bank" class="form-select form-control" required>
            <option value="">-- Pilih Bank --</option>
            @foreach([
              'BRI', 'Mandiri', 'BNI', 'BTN', 'BCA', 'Bank Syariah Indonesia (BSI)', 'Seabank',
              'GoPay', 'OVO', 'DANA', 'ShopeePay', 'LinkAja'
            ] as $b)
            <option value="{{ $b }}">{{ $b }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Nomor Rekening / HP</label>
          <input type="text" name="nomor_rekening" class="form-control" placeholder="Contoh: 1234567890" required>
        </div>
        <div class="col-12">
          <label class="form-label">Nama Pemilik Rekening</label>
          <input type="text" name="nama_pemilik" class="form-control" placeholder="Sesuai buku tabungan" required>
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn-save" style="background:var(--dark);"><i class="bi bi-plus-lg me-1"></i> Tambahkan Rekening</button>
      </div>
    </form>
  </div>
  @else
  <div class="alert alert-warning" style="font-size:.8rem; border-radius:.7rem;">
    <i class="bi bi-exclamation-triangle-fill me-1"></i> Kamu sudah mencapai batas maksimal 5 rekening. Hapus salah satu jika ingin menambah yang baru.
  </div>
  @endif
</div>

          {{-- GANTI PASSWORD --}}
          <div class="form-card">
            <h6><i class="bi bi-lock" style="color:var(--primary)"></i> Ganti Password</h6>
            <form action="{{ route('owner.pengaturan.password') }}" method="POST">
              @csrf @method('PATCH')
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label">Password Lama</label>
                  <div class="input-group">
                    <input type="password" name="current_password" class="form-control" placeholder="Masukkan password lama" id="pwLama" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePw('pwLama')"><i class="bi bi-eye"></i></button>
                  </div>
                  @error('current_password') <div class="text-danger mt-1" style="font-size:.7rem;">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">Password Baru</label>
                  <div class="input-group">
                    <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" id="pwBaru" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePw('pwBaru')"><i class="bi bi-eye"></i></button>
                  </div>
                  @error('password') <div class="text-danger mt-1" style="font-size:.7rem;">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">Konfirmasi Password Baru</label>
                  <div class="input-group">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" id="pwKonfirm" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePw('pwKonfirm')"><i class="bi bi-eye"></i></button>
                  </div>
                  @error('password_confirmation') <div class="text-danger mt-1" style="font-size:.7rem;">{{ $message }}</div> @enderror
                </div>
              </div>
              <div class="mt-3">
                <button type="submit" class="btn-save"><i class="bi bi-lock me-1"></i> Update Password</button>
              </div>
            </form>
          </div>

        </div>
        {{-- END KOLOM KIRI --}}

        {{-- ==================== KOLOM KANAN ==================== --}}
        <div class="col-kanan">

          <div class="form-card">
            <h6><i class="bi bi-patch-check" style="color:var(--primary)"></i> Status Akun</h6>
            <div class="d-flex flex-column gap-2">
              <div class="d-flex justify-content-between align-items-center p-2 rounded-2" style="background:#f8fafd;">
                <span style="font-size:.8rem;color:#555;display:flex;align-items:center;gap:.4rem;"><i class="bi bi-envelope-fill" style="color:#8fa3b8;"></i> Email</span>
                <span style="font-size:.75rem;font-weight:700;color:#16a34a;">✅ Terdaftar</span>
              </div>
              <div class="d-flex justify-content-between align-items-center p-2 rounded-2" style="background:#f8fafd;">
                <span style="font-size:.8rem;color:#555;display:flex;align-items:center;gap:.4rem;"><i class="bi bi-card-text" style="color:#8fa3b8;"></i> Identitas</span>
                @php $sv = auth()->user()->status_verifikasi_identitas; @endphp
                @if($sv === 'disetujui')
                  <span style="font-size:.75rem;font-weight:700;color:#16a34a;">✅ Terverifikasi</span>
                @elseif($sv === 'pending')
                  <span style="font-size:.75rem;font-weight:700;color:#b45309;">⏳ Menunggu</span>
                @elseif($sv === 'ditolak')
                  <span style="font-size:.75rem;font-weight:700;color:#dc2626;">❌ Ditolak</span>
                @else
                  <span style="font-size:.75rem;font-weight:700;color:#dc2626;">❌ Belum</span>
                @endif
              </div>
              <div class="d-flex justify-content-between align-items-center p-2 rounded-2" style="background:#f8fafd;">
                <span style="font-size:.8rem;color:#555;display:flex;align-items:center;gap:.4rem;"><i class="bi bi-house-check" style="color:#8fa3b8;"></i> Kepemilikan</span>
                @if(auth()->user()->foto_kepemilikan && $sv === 'disetujui')
                  <span style="font-size:.75rem;font-weight:700;color:#16a34a;">✅ Terverifikasi</span>
                @elseif(auth()->user()->foto_kepemilikan && $sv === 'pending')
                  <span style="font-size:.75rem;font-weight:700;color:#b45309;">⏳ Menunggu</span>
                @elseif(!auth()->user()->foto_kepemilikan)
                  <span style="font-size:.75rem;font-weight:700;color:#dc2626;">❌ Belum Upload</span>
                @else
                  <span style="font-size:.75rem;font-weight:700;color:#dc2626;">❌ Ditolak</span>
                @endif
              </div>
              <div class="d-flex justify-content-between align-items-center p-2 rounded-2" style="background:#f8fafd;">
                <span style="font-size:.8rem;color:#555;display:flex;align-items:center;gap:.4rem;"><i class="bi bi-house-fill" style="color:#8fa3b8;"></i> Kost Aktif</span>
                <span style="font-size:.75rem;font-weight:700;color:#1d4ed8;">
                  {{ \App\Models\Kost::where('owner_id', auth()->id())->where('status','aktif')->count() }} kost
                </span>
              </div>
            </div>
          </div>

          <div class="form-card">
            <h6><i class="bi bi-bell" style="color:var(--primary)"></i> Notifikasi</h6>
            <form action="{{ route('owner.pengaturan.notifikasi') }}" method="POST">
              @csrf @method('PATCH')
              <div class="notif-item">
                <div>
                  <div class="notif-label">Booking Masuk</div>
                  <div class="notif-sub">Notifikasi saat ada penyewa baru booking</div>
                </div>
                <div class="form-check form-switch mb-0">
                  <input class="form-check-input" type="checkbox" name="notif_booking" value="1" {{ auth()->user()->notif_booking ? 'checked' : '' }} onchange="this.form.submit()">
                </div>
              </div>
              <div class="notif-item">
                <div>
                  <div class="notif-label">Booking Dibatalkan</div>
                  <div class="notif-sub">Notifikasi saat penyewa membatalkan booking</div>
                </div>
                <div class="form-check form-switch mb-0">
                  <input class="form-check-input" type="checkbox" name="notif_cancel" value="1" {{ auth()->user()->notif_cancel ? 'checked' : '' }} onchange="this.form.submit()">
                </div>
              </div>
              <div class="notif-item">
                <div>
                  <div class="notif-label">Pengingat Pembayaran</div>
                  <div class="notif-sub">Notifikasi tagihan yang akan jatuh tempo</div>
                </div>
                <div class="form-check form-switch mb-0">
                  <input class="form-check-input" type="checkbox" name="notif_pembayaran" value="1" {{ auth()->user()->notif_pembayaran ? 'checked' : '' }} onchange="this.form.submit()">
                </div>
              </div>
              <div class="notif-item">
                <div>
                  <div class="notif-label">Promo & Informasi</div>
                  <div class="notif-sub">Update fitur dan promo dari KostFinder</div>
                </div>
                <div class="form-check form-switch mb-0">
                  <input class="form-check-input" type="checkbox" name="notif_promo" value="1" {{ auth()->user()->notif_promo ? 'checked' : '' }} onchange="this.form.submit()">
                </div>
              </div>
            </form>
          </div>

          <div class="danger-zone">
            <h6 style="font-weight:700;color:#dc2626;font-size:.87rem;margin-bottom:.5rem;">
              <i class="bi bi-exclamation-triangle me-1"></i> Zona Berbahaya
            </h6>
            <p style="font-size:.78rem;color:#8fa3b8;margin-bottom:.8rem;">
              Hapus akun akan menghapus semua data kost, kamar, dan booking secara permanen.
            </p>
            <button class="btn btn-sm btn-outline-danger w-100" onclick="showModalHapus()" style="border-radius:.5rem;font-size:.8rem;font-weight:600;">
              <i class="bi bi-trash me-1"></i> Hapus Akun Saya
            </button>
          </div>

        </div>
        {{-- END KOLOM KANAN --}}

      </div>
    </div>

    <footer class="owner-footer">
      © {{ date('Y') }} KostFinder — Panel Pemilik Kost. All rights reserved.
    </footer>
  </div>

  {{-- MODAL HAPUS AKUN --}}
  <div id="modalHapusAkun" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.6);z-index:99999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:1.2rem;padding:2rem;width:100%;max-width:380px;margin:1rem;box-shadow:0 20px 60px rgba(0,0,0,.25);">
      <div style="text-align:center;margin-bottom:1.2rem;">
        <div style="width:60px;height:60px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .9rem;">
          <i class="bi bi-trash" style="font-size:1.5rem;color:#dc2626;"></i>
        </div>
        <div style="font-weight:800;font-size:1rem;color:#1e2d3d;margin-bottom:.4rem;">Hapus Akun?</div>
        <div style="font-size:.82rem;color:#8fa3b8;line-height:1.6;">Yakin hapus akun? <strong style="color:#dc2626;">Tindakan ini tidak bisa dibatalkan!</strong> Semua data kost, kamar, dan booking akan terhapus permanen.</div>
      </div>
      <div style="display:flex;gap:.6rem;">
        <button onclick="hideModalHapus()" style="flex:1;padding:.65rem;border-radius:.6rem;border:1.5px solid #e4e9f0;background:#fff;font-size:.85rem;font-weight:600;color:#555;cursor:pointer;">
          Batal
        </button>
        <form action="{{ route('owner.akun.hapus') }}" method="POST" style="flex:1;">
          @csrf @method('DELETE')
          <button type="submit" style="width:100%;padding:.65rem;border-radius:.6rem;border:0;background:#dc2626;color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;">
            <i class="bi bi-trash me-1"></i> Ya, Hapus!
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- LIGHTBOX --}}
  <div id="lightbox" onclick="tutupLightbox()">
    <span class="lb-close" onclick="tutupLightbox()">&times;</span>
    <img id="lbImg" src="" alt="">
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function showModalHapus() { document.getElementById('modalHapusAkun').style.display = 'flex'; }
    function hideModalHapus() { document.getElementById('modalHapusAkun').style.display = 'none'; }
    document.getElementById('modalHapusAkun').addEventListener('click', function(e) {
      if (e.target === this) hideModalHapus();
    });
    function togglePw(id) {
      const el = document.getElementById(id);
      el.type = el.type === 'password' ? 'text' : 'password';
    }
    function previewUpload(input, boxId) {
      const box = document.getElementById(boxId);
      if (input.files && input.files[0]) {
        const file = input.files[0];
        const isPdf = file.type === 'application/pdf';
        if (isPdf) {
          box.style.borderColor = '#22c55e';
          box.style.background = '#f0fdf4';
          box.querySelectorAll('i').forEach(el => { el.className = 'bi bi-file-earmark-pdf-fill'; el.style.color = '#22c55e'; });
          box.querySelectorAll('div').forEach(el => el.textContent = file.name);
          return;
        }
        const reader = new FileReader();
        reader.onload = e => {
          box.style.padding = '0';
          box.style.border = '2px solid #22c55e';
          box.style.background = '#f0fdf4';
          box.style.overflow = 'hidden';
          box.style.height = '160px';
          box.querySelectorAll('i, div').forEach(el => el.style.display = 'none');
          let img = box.querySelector('img.inner-preview');
          if (!img) {
            img = document.createElement('img');
            img.className = 'inner-preview';
            img.style.cssText = 'width:100%;height:100%;object-fit:cover;border-radius:.65rem;display:block;';
            box.appendChild(img);
          }
          img.src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    }
    function bukaLightbox(src) {
      document.getElementById('lbImg').src = src;
      document.getElementById('lightbox').classList.add('show');
    }
    function tutupLightbox() {
      document.getElementById('lightbox').classList.remove('show');
    }
    document.addEventListener('keydown', e => { if(e.key === 'Escape') tutupLightbox(); });
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('collapsed');
      document.getElementById('mainContent').classList.toggle('collapsed');
    }

    // =============================================
    // DATA WILAYAH (SAMA DENGAN REGISTER)
    // =============================================
    const WILAYAH = {
      "Surabaya": {
        "Asemrowo":      {"Asemrowo":"60182","Genting Kalianak":"60182","Tambak Sarioso":"60181"},
        "Benowo":        {"Kandangan":"60199","Romokalisari":"60192","Sememi":"60198","Tambak Osowilangun":"60191"},
        "Bubutan":       {"Alun-Alun Contong":"60174","Bubutan":"60174","Gundih":"60172","Jepara":"60171","Tembok Dukuh":"60173"},
        "Bulak":         {"Bulak":"60124","Kenjeran":"60122","Kedung Cowek":"60123","Sukolilo Baru":"60125"},
        "Dukuh Pakis":   {"Dukuh Kupang":"60225","Dukuh Pakis":"60224","Gunung Sari":"60226","pradah Kali Kendal":"60227"},
        "Gayungan":      {"Dukuh Menanggal":"60234","Gayungan":"60235","Ketintang":"60231","Menanggal":"60232"},
        "Gebang Putih":  {"Gebang Putih":"60117","Keputih":"60111","Klampis Ngasem":"60117","Medokan Semampir":"60119","Menur Pumpungan":"60118","Semolowaru":"60119"},
        "Genteng":       {"Embong Kaliasin":"60271","Genteng":"60275","Kapasari":"60274","Ketabang":"60272","Peneleh":"60273"},
        "Gubeng":        {"Airlangga":"60286","Baratajaya":"60284","Gubeng":"60281","Kertajaya":"60282","Mojo":"60285","Pucangsewu":"60282"},
        "Gununganyar":   {"Gununganyar":"60294","Gununganyar Tambak":"60295","Rungkut Menanggal":"60293","Rungkut Tengah":"60293"},
        "Jambangan":     {"Jambangan":"60242","Karah":"60244","Kebonsari":"60233","Pagesangan":"60233"},
        "Karangpilang":  {"Gedangasin":"60222","Karangpilang":"60221","Kedurus":"60221","Wiyung":"60228"},
        "Kenjeran":      {"Bulak Banteng":"60127","Sidotopo Wetan":"60129","Tambak Wedi":"60125","Tanah Kali Kedinding":"60128"},
        "Krembangan":    {"Dupak":"60181","Kemayoran":"60182","Krembangan Selatan":"60177","Morokrembangan":"60178","Perak Barat":"60177"},
        "Lakarsantri":   {"Bangkingan":"60213","Jeruk":"60213","Lakarsantri":"60212","Lidah Kulon":"60213","Lidah Wetan":"60213","Sumur Welut":"60216"},
        "Mulyorejo":     {"Dukuh Sutorejo":"60113","Kalijudan":"60114","Kalisari":"60112","Kejawan Putih Tambak":"60112","Mulyorejo":"60115","Sutorejo":"60113"},
        "Pabean Cantian":{"Bongkaran":"60161","Kroman":"60111","Nyamplungan":"60162","Perak Timur":"60165","Perak Utara":"60164"},
        "Pakal":         {"Babat Jerawat":"60193","Benowo":"60198","Pakal":"60197","Sumber Rejo":"60198"},
        "Rungkut":       {"Kali Rungkut":"60292","Kedung Baruk":"60298","Medokan Ayu":"60295","Penjaringan Sari":"60291","Rungkut Kidul":"60293","Wonorejo":"60296"},
        "Sambikerep":    {"Bringin":"60217","Made":"60215","Sambikerep":"60216","Lontar":"60196"},
        "Sawahan":       {"Banyu Urip":"60251","Dukuh Kupang":"60225","Kupang Krajan":"60252","Pakis":"60256","Putat Gede":"60253","Sawahan":"60254"},
        "Semampir":      {"Ampel":"60151","Pegirian":"60152","Sidotopo":"60153","Ujung":"60155","Wonokusumo":"60154"},
        "Simokerto":     {"Kapasan":"60141","Sidodadi":"60141","Simokerto":"60141","Simolawang":"60144","Tambakrejo":"60142"},
        "Sukolilo":      {"Gebang Putih":"60117","Keputih":"60111","Klampis Ngasem":"60117","Medokan Semampir":"60119","Menur Pumpungan":"60118","Semolowaru":"60119"},
        "Sukomanunggal": {"Putat Gede":"60253","Simomulyo":"60281","Simomulyo Baru":"60281","Sono Kwijenan":"60184","Sukomanunggal":"60188","Tanjungsari":"60187"},
        "Tambaksari":    {"Dukuh Setro":"60134","Gading":"60134","Kapas Madya":"60137","Pacar Keling":"60131","Pacar Kembang":"60132","Ploso":"60133","Rangkah":"60135","Tambaksari":"60136"},
        "Tandes":        {"Balongsari":"60186","Banjar Sugihan":"60185","Karang Poh":"60186","Manukan Kulon":"60185","Manukan Wetan":"60185","Tandes":"60187"},
        "Tegalsari":     {"Dr. Sutomo":"60264","Kedungdoro":"60261","Keputran":"60265","Tegalsari":"60262","Wonorejo":"60263"},
        "Tenggilis Mejoyo":{"Kendangsari":"60292","Kutir":"60291","Panjang Jiwo":"60299","Tenggilis Mejoyo":"60292"},
        "Wiyung":        {"Babatan":"60227","Balas Klumprik":"60222","Jajar Tunggal":"60229","Wiyung":"60228"},
        "Wonocolo":      {"Bendul Merisi":"60239","Jemur Wonosari":"60237","Margorejo":"60238","Sidosermo":"60239","Siwalankerto":"60236"},
        "Wonokromo":     {"Darmo":"60241","Jagir":"60244","Ngagel":"60246","Ngagelrejo":"60245","Sawunggaling":"60242","Wonokromo":"60243"}
      },
      "Malang": {
        "Blimbing":      {"Arjosari":"65126","Balearjosari":"65126","Blimbing":"65125","Bunulrejo":"65123","Jodipan":"65127","Kesatrian":"65121","Pandanwangi":"65124","Polowijen":"65126","Purwantoro":"65122","Purwodadi":"65125","Polehan":"65121"},
        "Kedungkandang": {"Arjowinangun":"65132","Bumiayu":"65135","Buring":"65136","Cemorokandang":"65138","Kedungkandang":"65137","Kotalama":"65136","Lesanpuro":"65138","Madyopuro":"65138","Mergosono":"65134","Sawojajar":"65139","Tlogowaru":"65133","Wonokoyo":"65131"},
        "Klojen":        {"Bareng":"65116","Gadingasri":"65115","Kasin":"65117","Kauman":"65119","Kiduldalem":"65119","Klojen":"65111","Oro-Oro Dowo":"65112","Penanggungan":"65113","Rampal Celaket":"65111","Samaan":"65112","Sukoharjo":"65118"},
        "Lowokwaru":     {"Dinoyo":"65144","Jatimulyo":"65141","Ketawanggede":"65145","Lowokwaru":"65141","Merjosari":"65144","Mojolangu":"65142","Sumbersari":"65145","Tasikmadu":"65143","Tlogomas":"65144","Tunggulwulung":"65143","Tunjungsekar":"65142"},
        "Sukun":         {"Bakalankrajan":"65148","Bandulan":"65146","Bandungrejosari":"65148","Ciptomulyo":"65148","Gadang":"65149","Karangbesuki":"65146","Kebonsari":"65149","Mulyorejo":"65147","Pisangcandi":"65146","Sukun":"65147","Tanjungrejo":"65147"}
      },
      "Sidoarjo": {
        "Balong Bendo":  {"Balong Bendo":"61274","Bakung Temenggungan":"61274","Bakung Pringgodani":"61274","Bogem Pinggir":"61274","Gabung":"61274","Jiyu":"61274","Kedung Cangkring":"61274","Masangan Kulon":"61274","Masangan Wetan":"61274","Pulosari":"61274","Wonokasian":"61274"},
        "Buduran":       {"Banjarkemantren":"61252","Buduran":"61252","Dukuhtengah":"61252","Entalsewu":"61252","Pagerwojo":"61252","Prasung":"61252","Sawohan":"61252","Sidokerto":"61252","Sidomulyo":"61252","Sukorejo":"61252","Wadungasih":"61252"},
        "Candi":         {"Balongdowo":"61271","Bligo":"61271","Bulu Sidokare":"61213","Candi":"61271","Gelam":"61271","Jambangan":"61271","Kalipecabean":"61271","Klurak":"61271","Larangan":"61271","Sugihwaras":"61271","Sepande":"61271","Sidodadi":"61271","Sumokali":"61271","Tenggulunan":"61271","Wedoroklurak":"61271"},
        "Gedangan":      {"Ganting":"61254","Gedangan":"61254","Gemurung":"61254","Karangbong":"61254","Ketajen":"61254","Kragan":"61254","Keboansikep":"61254","Sruni":"61254","Sawotratap":"61254","Tebel":"61254","Wedi":"61254"},
        "Jabon":         {"Dukuhsari":"61276","Garongan":"61276","Jabon":"61276","Keboguyang":"61276","Kedungrejo":"61276","Kupang":"61276","Lajuk":"61276","Mliriprowo":"61276","Permisan":"61276","Semambung":"61276","Tambak Kalisogo":"61276"},
        "Krembung":      {"Cangkring":"61275","Jenggot":"61275","Kandangan":"61275","Keper":"61275","Krembung":"61275","Lemujut":"61275","Mojoruntut":"61275","Ploso":"61275","Tambakrejo":"61275","Tanjeg Wagir":"61275","Waung":"61275"},
        "Krian":         {"Barengkrajan":"61262","Katerungan":"61262","Keboharan":"61262","Kemiri":"61262","Kraton":"61262","Krian":"61262","Nidomulyo":"61262","Ponokawan":"61262","Tempel":"61262","Terung Kulon":"61262","Terung Wetan":"61262","Tropodo":"61257","Watugolong":"61262"},
        "Porong":        {"Gedang":"61274","Juwetkenongo":"61274","Kebonagung":"61274","Kesambi":"61274","Lajuk":"61274","Mindi":"61274","Pamotan":"61274","Plumbon":"61274","Porong":"61274","Renokenongo":"61274","Siring":"61274","Wunut":"61274"},
        "Prambon":       {"Bulang":"61264","Candi Negoro":"61264","Gedangrowo":"61264","Gampang":"61264","Jedongcangkring":"61264","Jiworukem":"61264","Kajartengguli":"61264","Kedungwonokerto":"61264","Kemantren":"61264","Ngampelsari":"61264","Prambon":"61264","Simogirang":"61264","Temu":"61264","Watugolong":"61264"},
        "Sedati":        {"Betro":"61253","Buncitan":"61253","Cemandi":"61253","Gisik Cemandi":"61253","Kalanganyar":"61253","Pabean":"61253","Pepe":"61253","Pranti":"61253","Pulungan":"61253","Sedati Agung":"61253","Sedati Gede":"61253"},
        "Sidoarjo":      {"Bulusidokare":"61213","Celep":"61214","Gebang":"61215","Lemahputro":"61214","Magersari":"61213","Pekauman":"61215","Pucang":"61213","Sekardangan":"61214","Sidoarjo":"61213","Urangagung":"61215"},
        "Sukodono":      {"Anggaswangi":"61258","Bangsri":"61258","Kepatihan":"61258","Masangankulon":"61258","Pekarungan":"61258","Plumbungan":"61258","Sambungrejo":"61258","Suko":"61258","Sukodono":"61258","Suruh":"61258","Wilayut":"61258"},
        "Tarik":         {"Balongmacekan":"61265","Banjarwungu":"61265","Brand":"61265","Gampingrowo":"61265","Kemuning":"61265","Kendal":"61265","Klantingsari":"61265","Kramat Temenggung":"61265","Mergobener":"61265","Mindugading":"61265","Sebani":"61265","Segodobancang":"61265","Tarik":"61265","Tanjekwagir":"61265","Tramang Kliwon":"61265"},
        "Tanggulangin":  {"Ganggangpanjang":"61272","Kalitengah":"61272","Kalisampurno":"61272","Kedungbanteng":"61272","Kedungsolo":"61272","Ketapang":"61272","Ngaban":"61272","Putat":"61272","Randegan":"61272","Tanggulangin":"61272","Ketegan":"61272"},
        "Taman":         {"Bohar":"61257","Geluran":"61257","Jemundo":"61257","Kalijaten":"61257","Kedungturi":"61257","Kramat jegu":"61257","Ngelom":"61257","Pertapanmaduretno":"61257","Sepanjang":"61257","Taman":"61257","Trosobo":"61257","Wage":"61257"},
        "Tulangan":      {"Baharan":"61273","Bangunsupt":"61273","Grabagan":"61273","Janti":"61273","Kepadangan":"61273","Kepuharum":"61273","Medalem":"61273","Modong":"61273","Pangkemiri":"61273","Ploso":"61275","Sudimoro":"61273","Tulangan":"61273","Urangagung":"61273","Wonokarang":"61273"},
        "Waru":          {"Berbek":"61254","Bungurasih":"61257","Kepuh Kiriman":"61256","Kureksari":"61256","Medaeng":"61257","Ngingas":"61257","Pepelegi":"61257","Tambak Sawah":"61256","Tambak Sumur":"61256","Tropodo":"61257","Waru":"61256","Wedoro":"61255"},
        "Wonoayu":       {"Candinegoro":"61261","Cerme":"61261","Durak":"61261","Karangpuri":"61261","Ketimang":"61261","Lambangan Kulon":"61261","Lambangan Wetan":"61261","Mojorangagung":"61261","Mulyodadi":"61261","Pagerngumbuk":"61261","Plaosan":"61261","Semambung":"61261","Simoketawang":"61261","Tanggul":"61261","Wonoayu":"61261"}
      },
      "Jember": {
        "Ajung":{"Ajung":"68175","Arjasa":"68175","Biting":"68175","Gambiran":"68175","Klungkung":"68175","Mangaran":"68175","Pancakarya":"68175","Sukamakmur":"68175"},
        "Ambulu":{"Ambulu":"68172","Andongsari":"68172","Pontang":"68172","Sabrang":"68172","Sumberrejo":"68172","Tegalsari":"68172","Wirowongso":"68172"},
        "Arjasa":{"Arjasa":"68191","Candijati":"68191","Kamal":"68191","Kemuning Lor":"68191","Kotakan":"68191","Langkap":"68191","Darsono":"68191"},
        "Balung":{"Balung Kidul":"68161","Balung Lor":"68161","Gumelar":"68161","Karangsemanding":"68161","Taman Sari":"68161","Curah Kalong":"68161"},
        "Bangsalsari":{"Bangsalsari":"68154","Banjarsari":"68154","Gambirono":"68154","Gunungsari":"68154","Karangbayat":"68154","Langkap":"68191","Sukorejo":"68154","Tisnogambar":"68154"},
        "Kaliwates":{"Gebang":"68137","Jember Kidul":"68131","Kaliwates":"68133","Kebon Agung":"68136","Mangli":"68136","Sempusari":"68135","Tegalbesar":"68134"},
        "Patrang":{"Baratan":"68111","Bintoro":"68111","Gebang":"68117","Jember":"68111","Jumerto":"68117","Patrang":"68111","Slawu":"68116"},
        "Sumbersari":{"Antirogo":"68123","Kebonsari":"68122","Kranjingan":"68124","Sumbersari":"68121","Tegal Gede":"68121","Wirolegi":"68125"},
        "Tanggul":{"Klatakan":"68155","Patemon":"68155","Tanggul Kulon":"68155","Tanggul Wetan":"68155","Wonoasri":"68155"},
        "Wuluhan":{"Dukuhdempok":"68168","Glundengan":"68162","Kesilir":"68168","Lojejer":"68162","Tanjungrejo":"68162","Wuluhan":"68162"}
      },
      "Gresik": {
        "Bungah":{"Bedanten":"61152","Bungah":"61152","Indrodelik":"61152","Kemangi":"61152","Kisik":"61152","Kramat":"61152","Masangan":"61152","Sukorejo":"61152","Sungonlegowo":"61152","Tanjungwidoro":"61152"},
        "Cerme":{"Banjarsari":"61171","Cerme Kidul":"61171","Cerme Lor":"61171","Dadap Kuning":"61171","Dampaan":"61171","Dooro":"61171","Dungus":"61171","Gedangkulut":"61171","Guranganyar":"61171","Wedani":"61171"},
        "Driyorejo":{"Cangkir":"61177","Driyorejo":"61177","Karangandong":"61177","Krikilan":"61177","Mojosarirejo":"61177","Mulung":"61177","Petiken":"61177","Randegansari":"61177","Sumput":"61177","Tanjung":"61177","Terate":"61177"},
        "Gresik":{"Bedilan":"61116","Gresik":"61118","Kebungson":"61117","Kemuteran":"61114","Kroman":"61111","Lumpur":"61115","Ngipik":"61116","Pekauman":"61116","Pekelingan":"61116","Sidokumpul":"61119","Tlogopojok":"61112"},
        "Kebomas":{"Gending":"61124","Gulomantung":"61122","Industri":"61123","Indro":"61122","Kembangan":"61121","Kebomas":"61123","Ngargosari":"61123","Prambangan":"61124","Sidomoro":"61123","Randuagung":"61124"},
        "Manyar":{"Banyuwangi":"61151","Betoyoguci":"61151","Betoyokauman":"61151","Gumeno":"61151","Leran":"61151","Manyar Sidomukti":"61151","Manyar Sidorukun":"61151","Roomo":"61151","Sembung":"61151","Suci":"61151","Sukorejo":"61151"},
        "Menganti":{"Boboh":"61174","Boteng":"61174","Bringkang":"61174","Domas":"61174","Drancang":"61174","Gadingkulon":"61174","Laban":"61174","Menganti":"61174","Mojotengah":"61174","Putatlor":"61174","Sidojangkung":"61174","Sidorukun":"61174","Setro":"61174"},
        "Panceng":{"Banyutengah":"61156","Campurejo":"61156","Dalegan":"61156","Doudo":"61156","Ketanen":"61156","Ngemboh":"61156","Panceng":"61156","Petung":"61156","Serah":"61156","Siwalan":"61156"},
        "Sidayu":{"Asempapak":"61153","Bunderan":"61153","Golokan":"61153","Kauman":"61153","Lasem":"61153","Mojoasem":"61153","Pengulu":"61153","Purwodadi":"61153","Raci Kulon":"61153","Raci Wetan":"61153","Randuboto":"61153","Sidayu":"61153","Srowo":"61153","Sukorejo":"61153"},
        "Wringinanom":{"Bambe":"61176","Dunggede":"61176","Lebaniwaras":"61176","Mondoluku":"61176","Soko":"61176","Sumengko":"61176","Watesari":"61176","Wringinanom":"61176"}
      },
      "Kediri": {
        "Badas":{"Badas":"64215","Blaru":"64215","Cengkok":"64215","Gampeng":"64215","Kawedusan":"64215","Krecek":"64215","Nanggungan":"64215","Pelas":"64215","Purwodadi":"64215","Semen":"64215"},
        "Gampengrejo":{"Gampengrejo":"64182","Pondok":"64182","Sukorame":"64182","Turus":"64182"},
        "Grogol":{"Cerme":"64183","Gambyok":"64183","Gogorante":"64183","Grogol":"64183","Janti":"64183","Kalipang":"64183","Manggis":"64183","Sumberduren":"64183","Wonocatur":"64183"},
        "Gurah":{"Bogem":"64181","Brumbung":"64181","Bulupasar":"64181","Gayam":"64181","Gedangsewu":"64181","Gurah":"64181","Kerkep":"64181","Nambaan":"64181","Puhkidul":"64181","Tiron":"64181"},
        "Kepung":{"Besowo":"64292","Damarwulan":"64292","Gadungan":"64292","Kepung":"64292","Krenceng":"64292","Puncu":"64292","Sumberagung":"64292"},
        "Kota Kediri - Klojen":{"Balowerti":"64121","Dandangan":"64125","Kemasan":"64126","Ngadirejo":"64127","Pocanan":"64121","Ringinanom":"64124","Setonopande":"64128","Tinalan":"64129"},
        "Kota Kediri - Mojoroto":{"Banaran":"64115","Banjaran":"64117","Betet":"64118","Campurejo":"64118","Dermo":"64119","Gayam":"64112","Lirboyo":"64114","Mojoroto":"64114","Ngampel":"64115","Pojok":"64112","Semampir":"64116"},
        "Kota Kediri - Pesantren":{"Bangsal":"64134","Bawang":"64136","Blabak":"64135","Burengan":"64133","Ketami":"64138","Pesantren":"64133","Singonegaran":"64139","Tosaren":"64139"},
        "Mojo":{"Brenggolo":"64292","Duwet":"64292","Jugo":"64292","Kraton":"64292","Miri":"64292","Mojo":"64292","Petok":"64292","Semen":"64292"},
        "Pare":{"Gedangsewu":"64211","Bendo":"64211","Pare":"64211","Pelem":"64211","Sidorejo":"64211","Sumberbendo":"64211","Tulungrejo":"64211"},
        "Ringinrejo":{"Bogo":"64217","Karangtengah":"64217","Purwodadi":"64217","Ringinrejo":"64217","Wonodadi":"64217"},
        "Tarokan":{"Cengkok":"64215","Sendang":"64218","Tarokan":"64218"}
      },
      "Banyuwangi": {
        "Bangorejo":{"Bangorejo":"68487","Kebondalem":"68487","Ringintelu":"68487","Sambirejo":"68487","Sukorejo":"68487","Temurejo":"68487"},
        "Banyuwangi":{"Kampung Melayu":"68411","Kepatihan":"68411","Kertosari":"68416","Pakis":"68414","Penganjuran":"68416","Singonegaran":"68414","Sobo":"68416","Tamanbaru":"68416","Temenggungan":"68416","Tukangkayu":"68417"},
        "Genteng":{"Genteng Kulon":"68465","Genteng Wetan":"68465","Karangbendo":"68465","Kembiritan":"68465","Setail":"68465"},
        "Giri":{"Boyolangu":"68422","Giri":"68422","Jambesari":"68422","Penataban":"68422"},
        "Glagah":{"Bakungan":"68421","Glagah":"68421","Kemiren":"68421","Olehsari":"68421","Paspan":"68421","Rejosari":"68421","Taman Suruh":"68421"},
        "Kabat":{"Badean":"68461","Benelan Kidul":"68461","Bunder":"68461","Gombolirang":"68461","Kabat":"68461","Kalirejo":"68461","Macan Putih":"68461","Pakistaji":"68461","Tambong":"68461"},
        "Kalibaru":{"Banyuanyar":"68468","Kalibaru Kulon":"68468","Kalibaru Manis":"68468","Kebonrejo":"68468"},
        "Kalipuro":{"Bulusan":"68413","Gombengsari":"68413","Kelir":"68413","Ketapang":"68452","Pesucen":"68413","Telemung":"68413"},
        "Muncar":{"Blambangan":"68472","Kedungrejo":"68472","Kumendung":"68472","Muncar":"68472","Sumberberas":"68472","Tambakrejo":"68472","Tembokrejo":"68472"},
        "Purwoharjo":{"Glagahagung":"68483","Karetan":"68483","Kradenan":"68483","Purwoharjo":"68483","Sidorejo":"68483","Sumberasri":"68483","Wringinpitu":"68483"},
        "Rogojampi":{"Karangbendo":"68462","Lemahbang Kulon":"68462","Patoman":"68462","Rogojampi":"68462"},
        "Sempu":{"Jambewangi":"68467","Sempu":"68467","Tegalharjo":"68467","Temuasri":"68467"},
        "Singojuruh":{"Lemah Bang":"68463","Singojuruh":"68463","Singolatren":"68463","Wongsorejo":"68463"},
        "Srono":{"Bagorejo":"68471","Kebaman":"68471","Parijatah Kulon":"68471","Parijatah Wetan":"68471","Rejoagung":"68471","Sambimulyo":"68471","Sumbersewu":"68471","Srono":"68471"},
        "Tegaldlimo":{"Kendalrejo":"68484","Kedunggebang":"68484","Purwoagung":"68484","Tegaldlimo":"68484","Wringinputih":"68484"},
        "Wongsorejo":{"Alasmalang":"68453","Bajulmati":"68453","Bimorejo":"68453","Sidodadi":"68453","Sidowangi":"68453","Sumberanyar":"68453","Wongsorejo":"68453"}
      },
      "Mojokerto": {
        "Bangsal":{"Bangsal":"61382","Brangkal":"61382","Gedangan":"61382","Jatidukuh":"61382","Japanan":"61382","Karangdiyeng":"61382","Kedungmaling":"61382","Puloniti":"61382","Sumbertebu":"61382"},
        "Gedeg":{"Domas":"61351","Gedeg":"61351","Gembongan":"61351","Gewang":"61351","Gunungan":"61351","Karangpoh":"61351","Kemantren":"61351","Mojoroto":"61351","Nguwok":"61351","Pagerluyung":"61351","Perning":"61351","Sidorejo":"61351","Sumberwono":"61351"},
        "Gondang":{"Centong":"61372","Dilem":"61372","Gondang":"61372","Jrambe":"61372","Kalikatir":"61372","Ketapanrame":"61372","Klitih":"61372","Pohjejer":"61372","Pulorejo":"61321","Segunung":"61372"},
        "Jetis":{"Banjar Asri":"61352","Banjar Kemantren":"61352","Jatipasar":"61352","Jetis":"61352","Karang Kuten":"61352","Lakardowo":"61352","Mojogebang":"61352","Ngabar":"61352","Penanggungan":"61352","Sukodono":"61352"},
        "Kemlagi":{"Kemlagi":"61353","Kemlagilor":"61353","Kedungsumur":"61353","Leminggir":"61353","Mojodadi":"61353","Mojokumpul":"61353","Pekukuhan":"61353","Warugunung":"61353"},
        "Magersari":{"Balongsari":"61313","Gedongan":"61314","Gunung Gedangan":"61315","Jagalan":"61315","Kauman":"61312","Magersari":"61314","Miji":"61312","Sentanan":"61314"},
        "Mojosari":{"Awang Awang":"61382","Beloh":"61382","Menanggal":"61382","Modopuro":"61381","Mojosari":"61381","Ngimbangan":"61381","Sumber Taman":"61381","Sumbertanggul":"61381"},
        "Pacet":{"Candiwatu":"61374","Claket":"61374","Kemiri":"61374","Mojokembang":"61374","Pacet":"61374","Padusan":"61374","Sajen":"61374","Seloliman":"61374","Tanjungkenongo":"61374"},
        "Prajurit Kulon":{"Blooto":"61321","Jagalan":"61315","Kedundung":"61323","Kranggan":"61322","Mentikan":"61321","Meri":"61322","Prajurit Kulon":"61321","Pulorejo":"61321","Surodinawan":"61322"},
        "Pungging":{"Jabontegal":"61384","Mulyoagung":"61384","Ngrame":"61384","Pungging":"61384","Randuharjo":"61384","Tunggalpager":"61384","Watukenongo":"61384"},
        "Sooko":{"Gemekan":"61361","Jambuwok":"61361","Karangkedawang":"61361","Sambiroto":"61361","Sooko":"61361"},
        "Trawas":{"Belik":"61375","Duyung":"61375","Gumeng":"61375","Ketapanrame":"61375","Ngembat":"61375","Penanggungan":"61375","Seloliman":"61374","Trawas":"61375"},
        "Trowulan":{"Bejijong":"61362","Bicak":"61362","Brangkal":"61382","Domas":"61351","Kejagan":"61362","Klinterejo":"61362","Kumitir":"61362","Ngarjo":"61362","Panggih":"61362","Sentonorejo":"61362","Trowulan":"61362","Watesumpak":"61362"}
      },
      "Pasuruan": {
        "Bangil":{"Masangan":"67153","Dermo":"67153","Kauman":"67153","Kalianyar":"67153","Kolursari":"67153","Manaruwi":"67153","Pogar":"67153","Raci":"67153","Latek":"67153","Bendomungal":"67153","Tambaan":"67153","Gempeng":"67153"},
        "Beji":{"Beji":"67154","Carat":"67154","Cangkringmalang":"67154","Glagahsari":"67154","Kedungringin":"67154","Ngempit":"67154","Pagak":"67154","Pleret":"67154","Tambakrejo":"67154"},
        "Bugul Kidul":{"Blandongan":"67122","Bugul Kidul":"67121","Kepel":"67122","Krampyangan":"67121","Tembokrejo":"67122"},
        "Gondang Wetan":{"Duren":"67162","Gondangwetan":"67162","Kalianyar":"67162","Kepulungan":"67162","Kluwut":"67162","Lemahbang":"67162","Pandean":"67162","Sumberrejo":"67162","Wonoasri":"67162"},
        "Grati":{"Grati Tunon":"67184","Kalipang":"67184","Kedawung Kulon":"67184","Kedawung Wetan":"67184","Klaseman":"67184","Raci":"67184","Rebalas":"67184","Segoropuro":"67184","Sumberdawesari":"67184"},
        "Kejayan":{"Gerongan":"67171","Kalianyar":"67171","Kejayan":"67171","Klepu":"67171","Pulokerto":"67171","Sumberrejo":"67171","Wonorejo":"67171"},
        "Kraton":{"Kraton":"67151","Ngempit":"67154","Ranggeh":"67151","Rejoso":"67151","Semare":"67151","Tambaagung":"67151"},
        "Nguling":{"Kapasan":"67182","Kedawung Kulon":"67182","Kedawung Wetan":"67182","Nguling":"67182","Penunggul":"67182","Randuati":"67182","Sumberanyar":"67182"},
        "Pandaan":{"Durensewu":"67156","Jogosari":"67156","Karangjati":"67156","Petungasri":"67156","Pandaan":"67156","Pecalukan":"67156","Tawangrejo":"67156","Wedoro":"67156"},
        "Pohjentrek":{"Karangketug":"67116","Pohjentrek":"67118","Sekargadung":"67117","Tambaan":"67153"},
        "Prigen":{"Gambiran":"67157","Jatiarjo":"67157","Ledug":"67157","Lumbangrejo":"67157","Nogosari":"67157","Prigen":"67157","Sukorejo":"67157","Wonodadi":"67157"},
        "Purwodadi":{"Gerbo":"67172","Pohgading":"67172","Purwodadi":"67172","Sengonagung":"67172","Tambaksari":"67172"},
        "Purworejo":{"Bangilan":"67119","Bugul Lor":"67117","Karangketug":"67116","Pohjentrek":"67118","Purworejo":"67118","Sekargadung":"67117"},
        "Rembang":{"Curahdami":"67175","Kedawung":"67175","Klakah Rejo":"67175","Rembang":"67175","Sumber Bendo":"67175","Tunggul Wulung":"67175"},
        "Sukorejo":{"Gunung Gangsir":"67155","Lemah ireng":"67155","Petungasri":"67155","Sukorejo":"67155","Wonorejo":"67155"},
        "Tosari":{"Baledono":"67177","Kandangan":"67177","Keduwung":"67177","Mororejo":"67177","Podokoyo":"67177","Tosari":"67177","Wonokitri":"67177"},
        "Tutur":{"Andonosari":"67176","Kalipang":"67176","Nongkojajar":"67176","Tutur":"67176","Wonosari":"67176"},
        "Winongan":{"Bandaran":"67161","Banyubang":"67161","Winongan Kidul":"67161","Winongan Lor":"67161"}
      }
    };

    function onPilihKota() {
      const kota   = document.getElementById('sel-kota').value;
      const selKec = document.getElementById('sel-kec');
      const inpKec = document.getElementById('inp-kec');
      const hintKec= document.getElementById('hint-kec');

      document.getElementById('hidden-kota').value = kota;

      selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
      inpKec.style.display = 'none'; inpKec.value = '';
      hintKec.style.display = 'none';
      document.getElementById('kode_pos').value = '';
      document.getElementById('hidden-kelurahan').value = '';

      if (!kota || !WILAYAH[kota]) return;

      Object.keys(WILAYAH[kota]).sort().forEach(kec => {
        const o = document.createElement('option');
        o.value = kec; o.textContent = kec;
        selKec.appendChild(o);
      });
      const oLain = document.createElement('option');
      oLain.value = '__lainnya__';
      oLain.textContent = '✏️ Tidak ada? Tulis sendiri...';
      selKec.appendChild(oLain);
    }

    function onPilihKec() {
      const kota   = document.getElementById('sel-kota').value;
      const selKec = document.getElementById('sel-kec');
      const inpKec = document.getElementById('inp-kec');
      const hintKec= document.getElementById('hint-kec');

      document.getElementById('kode_pos').value = '';
      document.getElementById('hidden-kelurahan').value = '';

      if (selKec.value === '__lainnya__') {
        inpKec.style.display  = 'block';
        hintKec.style.display = 'block';
        inpKec.focus();
        return;
      }

      inpKec.style.display  = 'none';
      hintKec.style.display = 'none';

      const kec = selKec.value;
      if (!kota || !kec || !WILAYAH[kota] || !WILAYAH[kota][kec]) return;

      const entries = Object.entries(WILAYAH[kota][kec]);
      if (entries.length > 0) {
        document.getElementById('kode_pos').value = entries[0][1];
        document.getElementById('hidden-kelurahan').value = entries[0][0];
      }
    }

    // Initialize dropdowns on page load based on existing data
    window.addEventListener('DOMContentLoaded', () => {
      const kotaVal = document.getElementById('hidden-kota').value;
      const kecVal = "{{ old('kecamatan', auth()->user()->kecamatan ?? '') }}";
      const initialKodePos = "{{ old('kode_pos', auth()->user()->kode_pos ?? '') }}";
      
      if (kotaVal) {
        document.getElementById('sel-kota').value = kotaVal;
        onPilihKota();
        
        const selKec = document.getElementById('sel-kec');
        let foundKec = false;
        
        for (let i = 0; i < selKec.options.length; i++) {
          if (selKec.options[i].value === kecVal) {
            selKec.selectedIndex = i;
            foundKec = true;
            break;
          }
        }
        
        if (kecVal && !foundKec) {
          // If the custom value exists, dynamically add it as an option so it looks natural
          const newOpt = document.createElement('option');
          newOpt.value = kecVal;
          newOpt.textContent = kecVal;
          // Insert right before the '__lainnya__' option
          selKec.insertBefore(newOpt, selKec.lastElementChild);
          selKec.value = kecVal;
        } else if (kecVal) {
          // just to ensure hidden kelurahan and kode pos are populated if they were somehow empty
          if (!initialKodePos) {
            onPilihKec();
          }
        }

        // Kembalikan kode pos ke nilai awal dari database atau input user sebelumnya
        if (initialKodePos) {
          document.getElementById('kode_pos').value = initialKodePos;
        }
      }
      
      // Before submit, ensure the text input is synced
      const form = document.querySelector('form[action="{{ route('owner.pengaturan.update') }}"]');
      if (form) {
        form.addEventListener('submit', function(e) {
          const selKec = document.getElementById('sel-kec');
          const inpKec = document.getElementById('inp-kec');
          const hidKel = document.getElementById('hidden-kelurahan');
          
          let kecFinal = '';
          if (selKec.value === '__lainnya__') {
            kecFinal = inpKec.value.trim();
          } else {
            kecFinal = selKec.value;
            inpKec.value = kecFinal;
            inpKec.style.display = 'block'; // Ensure it's submitted
          }
          
          if (!hidKel.value) hidKel.value = kecFinal;
        });
      }
    });

  </script>
</body>
</html>