<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') - KostFinder</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,800&display=swap" rel="stylesheet">

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f7f3f0; }

    /* ══ LAYOUT UTAMA ══ */
    .uf-body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* ══ MOBILE TOPBAR (hanya mobile) ══ */
    .uf-mobile-bar {
      display: none;
      align-items: center;
      gap: .75rem;
      background: #fff;
      padding: .6rem 1rem;
      border-bottom: 1px solid #edf0f7;
      position: sticky;
      top: 0;
      z-index: 600;
      box-shadow: 0 2px 8px rgba(0,0,0,.06);
    }
    .uf-mobile-bar-title {
      font-weight: 800;
      font-size: .9rem;
      color: #1e2d3d;
      flex: 1;
    }
    .uf-hamburger {
      background: #D0783B;
      color: #fff;
      border: 0;
      width: 36px; height: 36px;
      border-radius: .5rem;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.1rem;
      cursor: pointer;
      flex-shrink: 0;
      transition: background .18s;
    }
    .uf-hamburger:hover { background: #b5622e; }

    /* ══ WRAP SIDEBAR + KONTEN ══ */
    .uf-wrap {
      display: flex;
      flex: 1;
      align-items: flex-start;
      padding: 1rem;
      gap: 1rem;
    }

    /* ══ SIDEBAR WRAP (desktop) ══ */
    .uf-sidebar-wrap {
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      gap: .5rem;
      transition: all .3s ease;
    }

    /* Tombol MENU (desktop only) */
    .uf-menu-btn {
      background: #D0783B;
      color: #fff;
      border: 0;
      padding: .45rem 1rem;
      border-radius: .6rem;
      font-weight: 700;
      font-size: .82rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: .4rem;
      transition: background .18s;
      width: fit-content;
    }
    .uf-menu-btn:hover { background: #b5622e; }
    .uf-menu-btn i { font-size: .85rem; transition: transform .3s; }

    /* ══ SIDEBAR ══ */
    .uf-sidebar {
      width: 235px;
      background: linear-gradient(160deg, #e08a4a 0%, #D0783B 45%, #b5622e 100%);
      border-radius: 1.2rem;
      display: flex;
      flex-direction: column;
      position: relative;
      overflow: hidden;
      transition: all .3s ease;
      box-shadow: 0 8px 24px rgba(176,90,30,.25);
    }
    .uf-sidebar.collapsed {
      width: 0;
      opacity: 0;
      pointer-events: none;
      overflow: hidden;
    }
    .uf-sidebar::before {
      content: '';
      position: absolute; top: -40px; right: -40px;
      width: 160px; height: 160px; border-radius: 50%;
      background: rgba(255,255,255,.07); pointer-events: none;
    }
    .uf-sidebar-inner {
      width: 235px;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    /* User card */
    .uf-sb-user {
      margin: 1rem .85rem .6rem;
      background: rgba(255,255,255,.18);
      border-radius: .75rem; padding: .75rem .85rem;
      display: flex; align-items: center; gap: .65rem;
    }
    .uf-sb-avatar {
      width: 36px; height: 36px; border-radius: 50%;
      background: rgba(255,255,255,.3);
      border: 2px solid rgba(255,255,255,.5);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-weight: 800; font-size: .82rem;
      flex-shrink: 0; overflow: hidden;
    }
    .uf-sb-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .uf-sb-name { font-weight: 700; font-size: .82rem; color: #fff; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .uf-sb-role { font-size: .66rem; font-weight: 600; color: rgba(255,255,255,.75); margin-top: 1px; }

    .uf-sb-div { height: 1px; background: rgba(255,255,255,.18); margin: .35rem .85rem; }

    .uf-sb-section {
      font-size: .61rem; font-weight: 800;
      text-transform: uppercase; letter-spacing: .12em;
      color: rgba(255,255,255,.5);
      padding: .45rem 1.1rem .2rem;
      white-space: nowrap;
    }

    .uf-sb-item {
      display: flex; align-items: center; gap: .6rem;
      padding: .52rem .9rem; margin: .04rem .6rem;
      border-radius: .58rem; text-decoration: none;
      color: rgba(255,255,255,.88); font-size: .82rem; font-weight: 600;
      transition: all .18s; white-space: nowrap;
    }
    .uf-sb-item i { font-size: .88rem; width: 16px; text-align: center; flex-shrink: 0; }
    .uf-sb-item:hover { background: rgba(255,255,255,.18); color: #fff; }
    .uf-sb-item.active {
      background: #fff; color: #D0783B; font-weight: 700;
      box-shadow: 0 3px 10px rgba(0,0,0,.12);
    }
    .uf-sb-item.active i { color: #D0783B; }

    .uf-sb-logout {
      display: flex; align-items: center; gap: .6rem;
      padding: .52rem .9rem; margin: .04rem .6rem;
      border-radius: .58rem; color: rgba(255,255,255,.75);
      font-size: .82rem; font-weight: 600;
      cursor: pointer; border: none; background: transparent;
      width: calc(100% - 1.2rem); text-align: left;
      transition: all .18s; white-space: nowrap;
      margin-bottom: .85rem;
    }
    .uf-sb-logout:hover { background: rgba(255,255,255,.14); color: #fff; }
    .uf-sb-logout i { font-size: .88rem; width: 16px; text-align: center; }

    /* ══ MAIN AREA ══ */
    .uf-main-area {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
    }

    .uf-subbar {
      background: #fff; border-radius: .85rem;
      border: 1px solid #edf0f7;
      padding: .55rem 1.1rem;
      display: flex; align-items: center; gap: .75rem;
      margin-bottom: 1rem;
      box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }
    .uf-subbar-title { font-weight: 700; font-size: .88rem; color: #0f1923; }
    .uf-subbar-breadcrumb { font-size: .76rem; color: #8fa3b8; margin-left: auto; }
    .uf-subbar-breadcrumb a { color: #8fa3b8; text-decoration: none; }
    .uf-subbar-breadcrumb a:hover { color: #D0783B; }

    .uf-content { flex: 1; }

    .uf-footer-area { width: 100%; margin-top: auto; }

    /* ══ DRAWER MOBILE ══ */
    .uf-drawer-overlay {
      display: none;
      position: fixed; inset: 0;
      background: rgba(0,0,0,.45);
      z-index: 700;
      backdrop-filter: blur(2px);
    }
    .uf-drawer-overlay.open { display: block; }

    .uf-drawer {
      position: fixed;
      top: 0; left: -270px;
      width: 260px;
      height: 100vh;
      background: linear-gradient(160deg, #e08a4a 0%, #D0783B 45%, #b5622e 100%);
      z-index: 800;
      transition: left .28s cubic-bezier(.4,0,.2,1);
      display: flex; flex-direction: column;
      overflow-y: auto;
      box-shadow: 4px 0 24px rgba(0,0,0,.2);
    }
    .uf-drawer.open { left: 0; }

    .uf-drawer-header {
      display: flex; align-items: center; justify-content: space-between;
      padding: 1rem .9rem .5rem;
    }
    .uf-drawer-logo {
      font-weight: 800; font-size: .95rem; color: #fff;
      letter-spacing: -.01em;
    }
    .uf-drawer-close {
      background: rgba(255,255,255,.2);
      border: 0; color: #fff;
      width: 32px; height: 32px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; font-size: 1rem;
      transition: background .18s;
    }
    .uf-drawer-close:hover { background: rgba(255,255,255,.35); }

    /* ══ RESPONSIVE ══ */
    @media (max-width: 991px) {
      /* Sembunyikan sidebar desktop & tombol menu desktop */
      .uf-sidebar-wrap { display: none; }
      /* Tampilkan mobile topbar */
      .uf-mobile-bar { display: flex; }
      /* Kurangi padding wrap */
      .uf-wrap { padding: .75rem; }
      /* Sembunyikan subbar breadcrumb di mobile */
      .uf-subbar-breadcrumb { display: none; }
    }

    @media (min-width: 992px) {
      /* Sembunyikan drawer & overlay di desktop */
      .uf-drawer, .uf-drawer-overlay { display: none !important; }
      .uf-mobile-bar { display: none !important; }
    }
  </style>

  @yield('styles')
</head>
<body>

<div class="uf-body">

  {{-- ══ NAVBAR ══ --}}
  @include('layouts.navigation')

  {{-- ══ MOBILE TOPBAR ══ --}}
  <div class="uf-mobile-bar">
    <button class="uf-hamburger" id="drawerBtn" onclick="openDrawer()">
      <i class="bi bi-list"></i>
    </button>
    <div class="uf-mobile-bar-title">@yield('title', 'Dashboard')</div>
    @auth
    <div style="width:32px;height:32px;border-radius:50%;background:#D0783B;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.8rem;flex-shrink:0;overflow:hidden;">
      @if(auth()->user()->foto_profil)
        <img src="{{ asset('storage/'.auth()->user()->foto_profil) }}" style="width:100%;height:100%;object-fit:cover;">
      @else
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
      @endif
    </div>
    @endauth
  </div>

  {{-- ══ DRAWER MOBILE ══ --}}
  <div class="uf-drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
  <div class="uf-drawer" id="ufDrawer">
    <div class="uf-drawer-header">
      <div class="uf-drawer-logo">🏠 KostFinder</div>
      <button class="uf-drawer-close" onclick="closeDrawer()"><i class="bi bi-x"></i></button>
    </div>


    <div class="uf-sb-div"></div>
    <div class="uf-sb-section">Menu Utama</div>
    <a href="{{ route('user.dashboard') }}" class="uf-sb-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
      <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <a href="{{ route('user.booking.index') }}" class="uf-sb-item {{ request()->routeIs('user.booking*') ? 'active' : '' }}">
      <i class="bi bi-calendar-check-fill"></i> Pesananku
    </a>
    <a href="{{ route('user.favorit') }}" class="uf-sb-item {{ request()->routeIs('user.favorit') ? 'active' : '' }}">
      <i class="bi bi-heart-fill"></i> Kos Favoritku
    </a>

    <div class="uf-sb-div"></div>
    <div class="uf-sb-section">Aktivitas</div>
    <a href="{{ route('user.ulasan.index') }}" class="uf-sb-item {{ request()->routeIs('user.ulasan*') ? 'active' : '' }}">
      <i class="bi bi-star-fill"></i> Ulasanku
    </a>
    <a href="{{ route('keluhan.index') }}" class="uf-sb-item {{ request()->routeIs('keluhan*') ? 'active' : '' }}">
      <i class="bi bi-chat-left-text-fill"></i> Keluhanku
    </a>

    <div class="uf-sb-div"></div>
    <div class="uf-sb-section">Lainnya</div>
    <a href="{{ route('kost.cari') }}" class="uf-sb-item">
      <i class="bi bi-search"></i> Cari Kos
    </a>
    <a href="{{ route('user.profil') }}" class="uf-sb-item {{ request()->routeIs('user.profil*') ? 'active' : '' }}">
      <i class="bi bi-person-circle"></i> Data Diri
    </a>
    <a href="{{ route('user.verifikasi.index') }}" class="uf-sb-item {{ request()->routeIs('user.verifikasi*') ? 'active' : '' }}">
      <i class="bi bi-patch-check-fill"></i> Verifikasi Akun
    </a>
    <a href="{{ route('user.pengaturan.index') }}" class="uf-sb-item {{ request()->routeIs('user.pengaturan*') ? 'active' : '' }}">
      <i class="bi bi-gear-fill"></i> Pengaturan
    </a>

    <div class="uf-sb-div" style="margin-top:.5rem;"></div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="uf-sb-logout">
        <i class="bi bi-box-arrow-left"></i> Keluar dari Akun
      </button>
    </form>
  </div>

  {{-- ══ TENGAH: Sidebar Desktop + Konten ══ --}}
  <div class="uf-wrap">

    {{-- SIDEBAR DESKTOP --}}
    <div class="uf-sidebar-wrap" id="ufSidebarWrap">
      <button class="uf-menu-btn" id="menuBtn" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
        MENU
        <i class="bi bi-chevron-down" id="menuChevron" style="font-size:.75rem;transition:transform .3s;"></i>
      </button>

      <aside class="uf-sidebar" id="ufSidebar">
        <div class="uf-sidebar-inner">

          <div class="uf-sb-div"></div>
          <div class="uf-sb-section">Menu Utama</div>
          <a href="{{ route('user.dashboard') }}" class="uf-sb-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
          </a>
          <a href="{{ route('user.booking.index') }}" class="uf-sb-item {{ request()->routeIs('user.booking*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check-fill"></i> Pesananku
          </a>
          <a href="{{ route('user.favorit') }}" class="uf-sb-item {{ request()->routeIs('user.favorit') ? 'active' : '' }}">
            <i class="bi bi-heart-fill"></i> Kos Favoritku
          </a>

          <div class="uf-sb-div"></div>
          <div class="uf-sb-section">Aktivitas</div>
          <a href="{{ route('user.ulasan.index') }}" class="uf-sb-item {{ request()->routeIs('user.ulasan*') ? 'active' : '' }}">
            <i class="bi bi-star-fill"></i> Ulasanku
          </a>
          <a href="{{ route('keluhan.index') }}" class="uf-sb-item {{ request()->routeIs('keluhan*') ? 'active' : '' }}">
            <i class="bi bi-chat-left-text-fill"></i> Keluhanku
          </a>

          <div class="uf-sb-div"></div>
          <div class="uf-sb-section">Lainnya</div>
          <a href="{{ route('kost.cari') }}" class="uf-sb-item">
            <i class="bi bi-search"></i> Cari Kos
          </a>
          <div class="uf-sb-item-wrap">
            <a href="#" onclick="toggleProfilSubmenu(event)" class="uf-sb-item {{ request()->routeIs('user.profil*','user.verifikasi*','user.pengaturan*') ? 'active' : '' }}">
              <i class="bi bi-person-circle"></i> Profil Saya
              <i class="bi bi-chevron-down ms-auto" id="profilChevron" style="font-size:.7rem;transition:transform .3s;{{ request()->routeIs('user.profil*','user.verifikasi*','user.pengaturan*') ? 'transform:rotate(-180deg);' : '' }}"></i>
            </a>
            <div id="profilSubmenu" style="display:{{ request()->routeIs('user.profil*','user.verifikasi*','user.pengaturan*') ? 'block' : 'none' }};padding-left:1.5rem;margin-top:.2rem;">
              <a href="{{ route('user.profil') }}" class="uf-sb-item {{ request()->routeIs('user.profil*') ? 'active' : '' }}" style="font-size:.8rem;padding:.4rem .9rem;">Data Diri</a>
              <a href="{{ route('user.verifikasi.index') }}" class="uf-sb-item {{ request()->routeIs('user.verifikasi*') ? 'active' : '' }}" style="font-size:.8rem;padding:.4rem .9rem;">Verifikasi Akun</a>
              <a href="{{ route('user.pengaturan.index') }}" class="uf-sb-item {{ request()->routeIs('user.pengaturan*') ? 'active' : '' }}" style="font-size:.8rem;padding:.4rem .9rem;">Pengaturan</a>
            </div>
          </div>

          <div class="uf-sb-div" style="margin-top:.5rem;"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="uf-sb-logout">
              <i class="bi bi-box-arrow-left"></i> Keluar dari Akun
            </button>
          </form>
        </div>
      </aside>
    </div>

    {{-- MAIN AREA --}}
    <div class="uf-main-area">
      <div class="uf-subbar">
        <span class="uf-subbar-title">@yield('title', 'Dashboard')</span>
        <span class="uf-subbar-breadcrumb">
          <a href="{{ route('home') }}">Beranda</a> / @yield('title', 'Dashboard')
        </span>
      </div>
      <div class="uf-content">
        @yield('content')
      </div>
    </div>

  </div>

  @include('components.owner-cta-banner')

  <div class="uf-footer-area">
    @include('layouts.footer')
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  /* ══ DESKTOP SIDEBAR TOGGLE ══ */
  const sidebar  = document.getElementById('ufSidebar');
  const menuChevron = document.getElementById('menuChevron');
  let isOpen = true;

  function toggleSidebar() {
    isOpen = !isOpen;
    sidebar.classList.toggle('collapsed', !isOpen);
    menuChevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(-90deg)';
  }

  /* ══ MOBILE DRAWER ══ */
  const drawer  = document.getElementById('ufDrawer');
  const overlay = document.getElementById('drawerOverlay');

  function openDrawer() {
    drawer.classList.add('open');
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeDrawer() {
    drawer.classList.remove('open');
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  /* Tutup drawer saat resize ke desktop */
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 992) closeDrawer();
  });

  /* ══ PROFIL SUBMENU (desktop) ══ */
  function toggleProfilSubmenu(e) {
    e.preventDefault();
    const sub  = document.getElementById('profilSubmenu');
    const chev = document.getElementById('profilChevron');
    const open = sub.style.display !== 'none';
    sub.style.display  = open ? 'none' : 'block';
    chev.style.transform = open ? 'rotate(0deg)' : 'rotate(-180deg)';
  }
</script>

@if(session('success'))
<script>
  Swal.fire({ icon:'success', title:'Berhasil!', text:'{{ session('success') }}', showConfirmButton:false, timer:2000 });
</script>
@endif
@if(session('error'))
<script>
  Swal.fire({ icon:'error', title:'Oops...', text:'{{ session('error') }}', confirmButtonColor:'#E8401C' });
</script>
@endif
@if($errors->any())
<script>
  Swal.fire({ icon:'error', title:'Periksa Kembali', text:'{{ $errors->first() }}', confirmButtonColor:'#E8401C' });
</script>
@endif

@yield('scripts')
</body>
</html>