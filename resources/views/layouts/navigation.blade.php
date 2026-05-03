{{-- ============================================================
     KostFinder – Navbar Blade Component
     File: resources/views/layouts/navbar.blade.php
     @include('layouts.navbar')  di layout utama kamu
     ============================================================ --}}

     <style>
  @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,800&display=swap');

  /* ── BASE ── */
  .kf-navbar {
    background: rgba(255,255,255,.97);
    border-bottom: 1px solid #edf0f7;
    transition: box-shadow .3s;
    z-index: 1030;
  }
  .kf-navbar.scrolled {
    box-shadow: 0 4px 20px rgba(10,20,50,.09);
  }
  .kf-navbar .container {
    height: 62px;
    display: flex;
    align-items: center;
    gap: 2rem;
  }

  /* ── LOGO ── */
  .kf-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    flex-shrink: 0;
  }
  .kf-brand-text { line-height: 1; }
  .kf-brand-name {
    font-family: 'Fraunces', Georgia, serif;
    font-weight: 800;
    font-size: 1.15rem;
    letter-spacing: -.025em;
    color: #0f1923;
  }
  .kf-brand-name span { color: #E8401C; }
  .kf-brand-sub {
    font-size: .5rem;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: #9aa3b5;
    margin-top: 1px;
    display: block;
  }
  .kf-brand-name {
  font-family: 'Fraunces', Georgia, serif;
  font-weight: 800;
  font-size: 1.15rem;
  letter-spacing: -.025em;
  color: #0f1923;

  display: flex;              /* FIX biar nggak collapse */
  align-items: center;
}

.kf-brand-name .kf-kost {
  color: #0f1923;
}

.kf-brand-name .kf-finder {
  color: #E8401C;
  margin-left: 2px;
}
  /* ── NAV LINKS ── */
  .kf-nav-links {
    display: flex;
    align-items: center;
    gap: 1.8rem;
    margin: 0;
    list-style: none;
    padding: 0;
    margin-bottom: 0;
  }
  .kf-nav-links .nav-link {
    font-size: .86rem;
    font-weight: 600;
    color: #374151;
    text-decoration: none;
    padding: 0 !important;
    position: relative;
    transition: color .2s;
    white-space: nowrap;
  }
  .kf-nav-links .nav-link::after {
    content: '';
    position: absolute;
    bottom: -4px; left: 0; right: 0;
    height: 2px;
    background: #E8401C;
    border-radius: 99px;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .22s ease;
  }
  .kf-nav-links .nav-link:hover,
  .kf-nav-links .nav-link.active { color: #E8401C; }
  .kf-nav-links .nav-link:hover::after,
  .kf-nav-links .nav-link.active::after { transform: scaleX(1); }

  /* ── SEARCH BAR ── */
  #navbarSearch {
    max-width: 440px;
    margin: 0 auto;
  }
  .kf-search-wrap {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid #eef2f7;
    border-radius: 1.2rem;
    padding: .42rem .46rem .42rem .82rem;
    gap: .45rem;
    box-shadow: 0 10px 30px rgba(15,25,35,.08);
    transition: border-color .2s, box-shadow .2s;
  }
  .kf-search-wrap:focus-within {
    border-color: #ffd0c0;
    box-shadow: 0 14px 34px rgba(228,64,28,.12);
    background: #fff;
  }
  .kf-search-wrap input {
    border: 0;
    background: transparent;
    font-size: .88rem;
    color: #0f1923;
    flex: 1;
    min-width: 0;
    outline: none;
    height: 34px;
  }
  .kf-search-wrap input::placeholder { color: #a0aabf; }
  .kf-search-wrap .btn-search-nav {
    height: 38px;
    padding: 0 1.15rem;
    border-radius: .95rem;
    border: 0;
    background: linear-gradient(135deg, #ffcfba 0%, #ff946c 100%);
    color: #22150f;
    font-weight: 800;
    font-size: .83rem;
    cursor: pointer;
    white-space: nowrap;
    box-shadow: 0 10px 22px rgba(240,100,50,.18);
    transition: all .18s;
  }
  .kf-search-wrap .btn-search-nav:hover {
    background: linear-gradient(135deg, #ffc0a7 0%, #ff8258 100%);
    color: #fff;
    box-shadow: 0 12px 26px rgba(240,100,50,.24);
  }
  .kf-search-wrap .btn-filter-icon {
    width: 36px; height: 36px;
    border-radius: .9rem;
    background: linear-gradient(135deg, #fff7f2 0%, #ffe8de 100%);
    border: 0;
    color: #f06432;
    display: flex; align-items: center; justify-content: center;
    font-size: .82rem; cursor: pointer; flex-shrink: 0;
    transition: background .18s;
  }
  .kf-search-wrap .btn-filter-icon:hover { background: linear-gradient(135deg, #ffefe8 0%, #ffdccc 100%); }

  /* ── TOMBOL KANAN ── */
  .kf-btns { display: flex; align-items: center; gap: .6rem; flex-shrink: 0; }
  .btn-kf-masuk {
    height: 34px; padding: 0 1rem; border-radius: .55rem;
    border: 1.5px solid #E8401C; background: transparent;
    color: #E8401C; font-weight: 700; font-size: .82rem;
    cursor: pointer; transition: all .18s; white-space: nowrap;
    text-decoration: none; display: inline-flex; align-items: center;
  }
  .btn-kf-masuk:hover { background: #E8401C; color: #fff; }
  .btn-kf-daftar {
    height: 34px; padding: 0 1rem; border-radius: .55rem;
    border: 0; background: #E8401C; color: #fff;
    font-weight: 700; font-size: .82rem; cursor: pointer;
    box-shadow: 0 3px 10px rgba(232,64,28,.35);
    transition: all .18s; white-space: nowrap;
  }
  .btn-kf-daftar:hover { background: #c03414; transform: translateY(-1px); box-shadow: 0 5px 14px rgba(232,64,28,.4); }

  /* ── USER PILL ── */
  .kf-user-pill {
    display: flex; align-items: center; gap: .4rem;
    padding: .25rem .6rem .25rem .25rem; border-radius: 999px;
    border: 1.5px solid #e2e8f4; background: #fff;
    text-decoration: none; transition: border-color .2s;
  }
  .kf-user-pill:hover { border-color: #E8401C; }
  .kf-avatar {
    width: 26px; height: 26px; border-radius: 50%;
    background: linear-gradient(135deg,#E8401C,#ff7a52);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 800; font-size: .7rem;
  }
  .kf-user-pill span {
    font-size: .82rem; font-weight: 700; color: #0f1923;
    max-width: 80px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
  }

  /* ── TOGGLER ── */
  .kf-toggler {
    border: 1.5px solid #e2e8f4; border-radius: .5rem;
    padding: .3rem .45rem; background: #fff; color: #0f1923;
  }
  .kf-toggler:focus { box-shadow: none; }

  /* ── SEARCH DROPDOWN ── */
  #navSearchDropdown {
    position: absolute;
    top: calc(100% + 8px); left: 0; right: 0;
    z-index: 9999;
    background: #fff;
    border: 1px solid #eef2f7;
    border-radius: 1.25rem;
    box-shadow: 0 22px 54px rgba(10,20,50,.12);
    padding: 1rem 1rem .95rem;
    animation: ddIn .16s ease;
  }
  .nav-search-head {
    display: flex; align-items: flex-start;
    gap: .75rem; margin-bottom: .9rem;
  }
  .nav-search-icon {
    width: 34px; height: 34px; border-radius: 50%;
    border: 1px solid #eef2f7; background: #fff;
    display: flex; align-items: center; justify-content: center;
    color: #f06432; flex-shrink: 0;
    box-shadow: 0 6px 14px rgba(15,25,35,.05);
  }
  .nav-chip {
    border-radius: 999px; border: 1px solid #dde5ef; background: #fff;
    color: #25313d; cursor: pointer; font-size: .76rem; font-weight: 600;
    padding: .48rem .95rem; transition: all .18s ease;
  }
  .nav-chip:hover { border-color: #ffbeaa; color: #f06432; background: #fff8f4; }
  @keyframes ddIn { from{opacity:0;transform:translateY(-5px)} to{opacity:1;transform:none} }

  /* ── FILTER MODAL ── */
  .filter-chip-btn {
    padding: .3rem .82rem; border-radius: .5rem;
    border: 1.5px solid #e2e8f4; background: #fff;
    color: #4b5a6e; font-size: .82rem; font-weight: 600;
    cursor: pointer; transition: all .16s;
  }
  .filter-chip-btn:hover, .filter-chip-btn.active-filter {
    background: #E8401C !important; color: #fff !important; border-color: #E8401C !important;
  }
  .filter-sec-title {
    font-size: .74rem; font-weight: 800; color: #374151;
    text-transform: uppercase; letter-spacing: .08em; margin-bottom: .55rem;
  }

  /* ── RESPONSIVE ── */
  @media (max-width: 991px) {
    .kf-navbar .container {
      height: auto; flex-wrap: wrap;
      padding-top: .6rem; padding-bottom: .6rem; gap: .5rem;
    }
    #navbarSearch { max-width: 100%; margin: 0; order: 3; width: 100%; }
    .kf-btns { margin-top: .3rem; }
    .kf-nav-links {
      flex-direction: column; align-items: flex-start;
      gap: 0; width: 100%; margin-top: .5rem;
    }
    .kf-nav-links .nav-link {
      padding: .6rem 0 !important;
      border-bottom: 1px solid #f0f3fa;
      width: 100%; display: block;
    }
    .kf-nav-links .nav-link::after { display: none; }
  }
  /* SCROLL HORIZONTAL */
.kf-scroll-x {
  display: flex;
  gap: 12px;
  overflow-x: auto;
  overflow-y: hidden;
  padding-bottom: 6px;
  margin-bottom: 12px;

  scroll-behavior: smooth;
  -webkit-overflow-scrolling: touch;
}

/* biar item gak turun ke bawah */
.kf-scroll-x > div {
  flex: 0 0 auto;
}

/* hilangkan scrollbar */
.kf-scroll-x::-webkit-scrollbar {
  display: none;
}
.kf-scroll-x {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>

<nav class="kf-navbar navbar navbar-expand-lg sticky-top" id="kfNavbar">
  <div class="container">

    {{-- ── LOGO ── --}}
    <a class="kf-brand" href="{{ route('home') }}">
      <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect width="34" height="34" rx="8" fill="#E8401C"/>
        <path d="M17 6.5L7 14.8V27H13.5V21H20.5V27H27V14.8L17 6.5Z" fill="white" opacity=".95"/>
        <rect x="14.5" y="21" width="5" height="6" rx="1" fill="#E8401C"/>
        <rect x="19.5" y="12.5" width="4" height="3.5" rx=".5" fill="#E8401C"/>
        <circle cx="25.5" cy="25.5" r="4.5" fill="#0f1923"/>
        <circle cx="24.9" cy="24.9" r="2.2" stroke="white" stroke-width="1.2" fill="none"/>
        <line x1="26.4" y1="26.4" x2="28" y2="28" stroke="white" stroke-width="1.3" stroke-linecap="round"/>
      </svg>
      <div class="kf-brand-text">
      <div class="kf-brand-name">
  <span class="kf-kost">Kost</span><span class="kf-finder">Finder</span>
</div>
        <span class="kf-brand-sub">Jawa Timur</span>
      </div>
    </a>

    {{-- ── TOGGLER MOBILE ── --}}
    <button class="kf-toggler navbar-toggler ms-auto me-2 d-lg-none" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMenu">
      <i class="bi bi-list" style="font-size:1.2rem;"></i>
    </button>

    <div class="navbar-collapse" id="navMenu">
      {{-- ── SEARCH BAR (tampil di HP, sembunyi di Laptop sesuai permintaan) ── --}}
      <div id="navbarSearch" class="d-none d-lg-none position-relative">
        <div class="kf-search-wrap">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
               stroke="#a0aabf" stroke-width="2.5" stroke-linecap="round" style="flex-shrink:0;">
            <circle cx="11" cy="11" r="7"/>
            <line x1="16.5" y1="16.5" x2="21" y2="21"/>
          </svg>
          <input type="text" id="navSearchInput"
                 placeholder="Cari lokasi, kampus, jalan..."
                 autocomplete="off"
                 onfocus="showNavDropdown()">
          <button class="btn-filter-icon" onclick="showFilterModal()" title="Filter">
            <i class="bi bi-sliders"></i>
          </button>
          <button class="btn-search-nav" onclick="doNavSearch()">Cari</button>
        </div>

        {{-- Dropdown Suggestions --}}
        <div id="navSearchDropdown" class="d-none">
          <div class="nav-search-head">
            <div class="nav-search-icon">
              <i class="bi bi-house-door"></i>
            </div>
            <div>
              <div style="font-size:.82rem;font-weight:800;color:#111827;">Lokasi</div>
              <div style="font-size:.8rem;color:#6b7280;line-height:1.5;">Cari nama properti / alamat / daerah / kota</div>
            </div>
          </div>

          <div class="kf-scroll-x">
            @foreach([
                ['nama' => 'Surabaya',   'file' => 'surabaya.jpg'],
                ['nama' => 'Malang',     'file' => 'malang.jpg'],
                ['nama' => 'Sidoarjo',   'file' => 'sidoarjo.jpg'],
                ['nama' => 'Gresik',     'file' => 'gresik.jpg'],
                ['nama' => 'Jember',     'file' => 'jember.jpg'],
                ['nama' => 'Kediri',     'file' => 'kediri.jpg'],
                ['nama' => 'Banyuwangi', 'file' => 'banyuwangi.jpg'],
                ['nama' => 'Mojokerto',  'file' => 'Mojokerto.jpg'],
                ['nama' => 'Pasuruan', 'file' => 'pasuruan.jpg'],
            ] as $c)
            <div class="text-center flex-shrink-0" style="cursor:pointer;" onclick="setNavSearch('{{ $c['nama'] }}')">
                <img src="{{ asset('images/kota/' . $c['file']) }}"
                     class="rounded-3 mb-1"
                     style="width:96px;height:58px;object-fit:cover;box-shadow:0 8px 16px rgba(15,25,35,.08);"
                     loading="lazy">
                <div style="font-size:.72rem;font-weight:600;color:#374151;">{{ $c['nama'] }}</div>
            </div>
            @endforeach
          </div>

          <div class="d-flex gap-3 border-bottom mb-2">
            <button class="btn btn-link p-0 pb-2 fw-bold text-decoration-none"
                    id="tabBtnDaerah"
                    style="color:#E8401C;border-bottom:2px solid #E8401C;font-size:.82rem;border-radius:0;"
                    onclick="switchNavTab('daerah')">Daerah</button>
            <button class="btn btn-link p-0 pb-2 text-decoration-none"
                    id="tabBtnKampus"
                    style="color:#888;font-size:.82rem;border-radius:0;"
                    onclick="switchNavTab('kampus')">Kampus</button>
          </div>

          <div id="navTabDaerah" class="d-flex flex-wrap gap-2">
            @foreach(['Surabaya','Malang','Sidoarjo','Gresik','Jember','Kediri','Banyuwangi','Mojokerto','Pasuruan'] as $d)
              <span class="nav-chip" onclick="setNavSearch('{{ $d }}')">{{ $d }}</span>
            @endforeach
          </div>

          <div id="navTabKampus" class="d-none d-flex flex-wrap gap-2">
            @foreach(['ITS Surabaya','UNAIR','UB Malang','UIN Malang','UNEJ Jember','UNESA'] as $k)
              <span class="nav-chip" onclick="setNavSearch('{{ $k }}')">{{ $k }}</span>
            @endforeach
          </div>
        </div>
      </div>

      {{-- ── NAV LINKS (Pindah ke kanan sebelum tombol masuk) ── --}}
      <ul class="kf-nav-links ms-auto me-lg-4" id="navMenuList">
        <li>
          <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
             href="{{ route('home') }}">
            <i class="bi bi-house-door me-1 d-lg-none"></i>Beranda
          </a>
        </li>
        <li>
          <a class="nav-link {{ request()->is('cari-kost*') ? 'active' : '' }}"
             href="{{ route('kost.cari') }}">
            <i class="bi bi-search me-1 d-lg-none"></i>Cari Kost
          </a>
        </li>
        <li>
          <a class="nav-link {{ request()->is('panduan*') ? 'active' : '' }}"
             href="/panduan">
            <i class="bi bi-info-circle me-1 d-lg-none"></i>Panduan
          </a>
        </li>
        <li>
          <a class="nav-link {{ request()->is('hubungi-kami*') ? 'active' : '' }}"
             href="{{ route('hubungi.kami') }}">
            <i class="bi bi-envelope me-1 d-lg-none"></i>Hubungi
          </a>
        </li>
      </ul>

      
      {{-- ── TOMBOL KANAN ── --}}
      <div class="kf-btns ms-0">
        @guest
          <a href="{{ route('login') }}" class="btn-kf-masuk">Masuk</a>
          <button class="btn-kf-daftar" data-bs-toggle="modal" data-bs-target="#modalRole">Daftar</button>
        @endguest

        @auth
          @if(auth()->user()->role === 'user')
            <div class="dropdown">
              <a class="kf-user-pill dropdown-toggle" href="#" data-bs-toggle="dropdown" style="text-decoration:none;">
                <div class="kf-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <span>{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down" style="font-size:.6rem;color:#9aa3b5;"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" style="min-width:170px;">
                <li>
                  <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('user.profil') }}">
                    <i class="bi bi-person" style="color:#E8401C;width:14px;"></i> Profil Saya
                  </a>
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('user.favorit') }}">
                    <i class="bi bi-heart" style="color:#E8401C;width:14px;"></i> Favorit
                  </a>
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('user.booking.index') }}">
                    <i class="bi bi-calendar-check" style="color:#E8401C;width:14px;"></i> Pesanan
                  </a>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn-logout" style="background:none; border:none; color:inherit; cursor:pointer;">
        Logout
    </button>
</form>
                </li>
              </ul>
            </div>

          @else
            {{-- Admin / Pemilik --}}
            <span style="font-size:.84rem;font-weight:600;color:#0f1923;">
              Halo, {{ auth()->user()->name }}!
            </span>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-outline-danger btn-sm" style="border-radius:.5rem;">
                Logout
              </button>
            </form>
          @endif
        @endauth
      </div>

    </div>{{-- end collapse --}}
  </div>{{-- end container --}}
</nav>

{{-- ============================================================
     MODAL FILTER
     ============================================================ --}}
<div class="modal fade" id="modalFilter" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content rounded-4 border-0" style="box-shadow:0 20px 60px rgba(10,20,50,.18);">
      <div class="modal-header" style="border-bottom:1px solid #f0f3fa;">
        <div class="d-flex align-items-center gap-2">
          <i class="bi bi-sliders" style="color:#E8401C;"></i>
          <h6 class="modal-title mb-0 fw-bold">Filter Kost</h6>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding:1.1rem 1.4rem;">

        <div class="mb-4">
          <div class="filter-sec-title">Jenis Sewa</div>
          <div class="d-flex gap-2">
            <button class="btn filter-chip-btn active-filter" onclick="toggleFilter(this)">Bulanan</button>
            <button class="btn filter-chip-btn" onclick="toggleFilter(this)">Harian</button>
          </div>
        </div>
        <hr class="my-3">

        <div class="mb-4">
          <div class="filter-sec-title">Tipe Penghuni</div>
          <div class="d-flex gap-2 flex-wrap">
            <button class="btn filter-chip-btn active-filter" onclick="toggleFilter(this)">Semua</button>
            <button class="btn filter-chip-btn" onclick="toggleFilter(this)">Campur</button>
            <button class="btn filter-chip-btn" onclick="toggleFilter(this)">Putra</button>
            <button class="btn filter-chip-btn" onclick="toggleFilter(this)">Putri</button>
          </div>
        </div>
        <hr class="my-3">

        <div class="mb-4">
          <div class="filter-sec-title">Urutkan Berdasarkan</div>
          <div class="d-flex flex-column gap-2">
            <label style="cursor:pointer;font-size:.87rem;display:flex;align-items:center;gap:.5rem;">
              <input type="radio" name="urut" checked> Paling Rekomendasi
            </label>
            <label style="cursor:pointer;font-size:.87rem;display:flex;align-items:center;gap:.5rem;">
              <input type="radio" name="urut"> Harga Termurah
            </label>
            <label style="cursor:pointer;font-size:.87rem;display:flex;align-items:center;gap:.5rem;">
              <input type="radio" name="urut"> Harga Tertinggi
            </label>
            <label style="cursor:pointer;font-size:.87rem;display:flex;align-items:center;gap:.5rem;">
              <input type="radio" name="urut"> Update Terbaru
            </label>
          </div>
        </div>
        <hr class="my-3">

        
        <hr class="my-3">

        <div class="mb-4">
          <div class="filter-sec-title">Lokasi</div>
          <div class="row g-2">
            @foreach(['Surabaya','Malang','Sidoarjo','Gresik','Kediri','Jember','Banyuwangi','Mojokerto','Madiun','Pasuruan'] as $l)
            <div class="col-6">
              <label style="cursor:pointer;font-size:.84rem;display:flex;align-items:center;gap:.5rem;">
                <input type="checkbox"> {{ $l }}
              </label>
            </div>
            @endforeach
          </div>
        </div>
        <hr class="my-3">

        <div class="mb-2">
          <div class="filter-sec-title">Rentang Harga</div>
          <div class="d-flex gap-2 align-items-center">
            <div class="flex-fill">
              <label style="font-size:.74rem;color:#9aa3b5;">Minimum</label>
              <input type="text" class="form-control form-control-sm rounded-3" placeholder="Rp 0">
            </div>
            <span class="mt-3 text-muted">–</span>
            <div class="flex-fill">
              <label style="font-size:.74rem;color:#9aa3b5;">Maksimum</label>
              <input type="text" class="form-control form-control-sm rounded-3" placeholder="Rp 15.000.000">
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer border-0" style="padding:.8rem 1.4rem 1.2rem;">
        <button type="button" class="btn w-100 fw-bold py-2 rounded-3"
                style="background:#E8401C;color:#fff;border:0;box-shadow:0 5px 16px rgba(232,64,28,.32);"
                data-bs-dismiss="modal">
          <i class="bi bi-check2-circle me-1"></i> Terapkan Filter
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Include modal role (daftar) --}}
@include('layouts.modal-role')

{{-- ============================================================
     JAVASCRIPT
     ============================================================ --}}
<script>
(function () {
  const navbar   = document.getElementById('kfNavbar');
  const navSrch  = document.getElementById('navbarSearch');
  const navLinks = document.getElementById('navMenuList');

  const isCariKost = {{ request()->is('cari-kost*') ? 'true' : 'false' }};
  const isHome     = {{ request()->routeIs('home') ? 'true' : 'false' }};
  const heroEl     = document.querySelector('.hero');

  if (isCariKost) {
    // ── Halaman Cari Kost: Tampilkan search
    if (navSrch)  navSrch.classList.remove('d-none');

    const params = new URLSearchParams(window.location.search);
    const inp = document.getElementById('navSearchInput');
    if (inp) {
        if (params.get('q'))    inp.value = params.get('q');
        if (params.get('kota')) inp.value = params.get('kota');
    }
  } else if (isHome && heroEl) {
    // ── Halaman Home: Toggle saat scroll melewati hero
    window.addEventListener('scroll', function () {
      const scrolled   = window.scrollY > 50;
      const heroPassed = heroEl.getBoundingClientRect().bottom < 80;

      navbar.classList.toggle('scrolled', scrolled);
      
      if (navSrch) {
          if (heroPassed) navSrch.classList.remove('d-none');
          else navSrch.classList.add('d-none');
      }
    });
  } else {
    // ── Halaman lain: Nav links tampil, search tersembunyi
    if (navLinks) navLinks.classList.remove('d-none');
    if (navSrch)  navSrch.classList.add('d-none');
  }
})();

/* ── Fungsi Search ── */
function showNavDropdown() {
  document.getElementById('navSearchDropdown').classList.remove('d-none');
}

function setNavSearch(val) {
  document.getElementById('navSearchInput').value = val;
  document.getElementById('navSearchDropdown').classList.add('d-none');
  document.getElementById('navSearchInput').focus();
}

function switchNavTab(tab) {
  const daerah    = document.getElementById('navTabDaerah');
  const kampus    = document.getElementById('navTabKampus');
  const btnDaerah = document.getElementById('tabBtnDaerah');
  const btnKampus = document.getElementById('tabBtnKampus');

  if (tab === 'daerah') {
    daerah.classList.remove('d-none');
    kampus.classList.add('d-none');
    btnDaerah.style.cssText = 'color:#E8401C;border-bottom:2px solid #E8401C;font-size:.82rem;border-radius:0;';
    btnKampus.style.cssText = 'color:#888;font-size:.82rem;border-radius:0;';
  } else {
    kampus.classList.remove('d-none');
    daerah.classList.add('d-none');
    btnKampus.style.cssText = 'color:#E8401C;border-bottom:2px solid #E8401C;font-size:.82rem;border-radius:0;';
    btnDaerah.style.cssText = 'color:#888;font-size:.82rem;border-radius:0;';
  }
}

function doNavSearch() {
  const q = document.getElementById('navSearchInput').value.trim();
  if (!q) {
    window.location.href = '{{ route("carikos") }}';
    return;
  }
  // Cek apakah input adalah nama kota
  const kotaList = ['Surabaya','Malang','Sidoarjo','Gresik','Jember','Kediri','Banyuwangi','Mojokerto','Pasuruan'];
  const isKota = kotaList.find(k => k.toLowerCase() === q.toLowerCase());
  if (isKota) {
    window.location.href = '{{ route("carikos") }}?kota=' + encodeURIComponent(isKota);
  } else {
    window.location.href = '{{ route("carikos") }}?q=' + encodeURIComponent(q);
  }
}

// Tutup dropdown saat klik di luar
document.addEventListener('click', function (e) {
  const dd  = document.getElementById('navSearchDropdown');
  const inp = document.getElementById('navSearchInput');
  if (dd && inp && !dd.contains(e.target) && e.target !== inp) {
    dd.classList.add('d-none');
  }
});

/* ── Fungsi Filter Modal ── */
function showFilterModal() {
  new bootstrap.Modal(document.getElementById('modalFilter')).show();
}

function toggleFilter(btn) {
  btn.classList.toggle('active-filter');
}
</script>