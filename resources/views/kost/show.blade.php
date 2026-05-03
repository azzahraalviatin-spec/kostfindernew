@extends('layouts.app')

@section('title', $kost->nama_kost . ' - KostFinder')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
:root {
  --primary: #e8401c;
  --primary-light: #fff5f2;
  --primary-border: #ffd0c0;
  --dark: #1e2d3d;
  --bg: #f5f6fa;
  --card-border: #e4e9f0;
  --text-muted: #8fa3b8;
  --text-body: #444;
  --radius: .75rem;
}
* { box-sizing: border-box; }
body { background: var(--bg); }

.bc a { color: var(--primary); text-decoration: none; font-size: .78rem; }
.bc .active { font-size: .78rem; color: var(--text-muted); }

/* ══ STICKY NAV ══ */
.sec-nav {
  position: sticky;
  top: 0;
  z-index: 900;
  background: #fff;
  border-top: 1px solid var(--card-border);
  border-bottom: 2px solid var(--card-border);
  border-radius: 0 0 .5rem .5rem;
  box-shadow: 0 3px 10px rgba(0,0,0,.07);
}
.sec-nav-inner {
  display: flex;
  overflow-x: auto;
  scrollbar-width: none;
  -webkit-overflow-scrolling: touch;
}
.sec-nav-inner::-webkit-scrollbar { display: none; }
.sec-nav-btn {
  flex-shrink: 0;
  padding: .65rem .95rem;
  font-size: .77rem;
  font-weight: 600;
  color: #666;
  cursor: pointer;
  border: none;
  border-bottom: 2.5px solid transparent;
  background: none;
  white-space: nowrap;
  transition: all .2s;
  text-decoration: none;
  display: inline-block;
}
.sec-nav-btn:hover { color: var(--primary); }
.sec-nav-btn.active { color: var(--primary); border-bottom-color: var(--primary); font-weight: 700; }

/* GALERI */
.galeri-wrap { display:flex; flex-direction:row; gap:6px; height:400px; width:100%; overflow:hidden; border-radius:.85rem; }
.galeri-main { flex:0 0 62%; position:relative; overflow:hidden; cursor:pointer; border-radius:.75rem; }
.galeri-main img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .35s; }
.galeri-main:hover img { transform:scale(1.04); }
.galeri-side { flex:1; display:flex; flex-direction:column; gap:6px; }
.galeri-slot { flex:1; position:relative; overflow:hidden; cursor:pointer; border-radius:.75rem; min-height:0; }
.galeri-slot img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .35s; }
.galeri-slot:hover img { transform:scale(1.04); }
.galeri-ph { width:100%; height:100%; background:#e9edf2; display:flex; align-items:center; justify-content:center; font-size:3rem; color:#c0ccd8; }
.btn-lihat-semua { position:absolute; bottom:12px; right:12px; background:rgba(255,255,255,.94); color:#222; border:1.5px solid rgba(0,0,0,.08); border-radius:.45rem; padding:.4rem .95rem; font-size:.78rem; font-weight:700; cursor:pointer; box-shadow:0 2px 10px rgba(0,0,0,.15); display:flex; align-items:center; gap:.38rem; z-index:4; transition:all .2s; white-space:nowrap; }
.btn-lihat-semua:hover { background:#fff; transform:translateY(-1px); box-shadow:0 4px 14px rgba(0,0,0,.2); }

/* BADGE */
.badge-tipe { background:var(--primary-light); color:#be3f1d; border:1px solid var(--primary-border); border-radius:999px; padding:.22rem .72rem; font-size:.73rem; font-weight:700; }
.badge-verified { background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; border-radius:999px; padding:.22rem .72rem; font-size:.73rem; font-weight:700; }
.status-tersedia { background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; border-radius:999px; padding:.16rem .58rem; font-size:.69rem; font-weight:700; }
.status-terisi { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; border-radius:999px; padding:.16rem .58rem; font-size:.69rem; font-weight:700; }

/* SECTION */
.sec { background:#fff; border-radius:var(--radius); padding:1.1rem 1.3rem; margin-bottom:.5rem; box-shadow:0 1px 4px rgba(0,0,0,.05); }
.sec-title { font-size:.92rem; font-weight:800; color:var(--dark); margin-bottom:.75rem; }
.sec-div { border:none; border-top:1px solid #f0f3f8; margin:.8rem 0; }
.info-row { display:flex; flex-wrap:wrap; gap:.5rem 1rem; font-size:.79rem; color:#555; margin:.4rem 0; }
.btn-action { display:flex; align-items:center; gap:.35rem; padding:.35rem .85rem; border:1.5px solid var(--card-border); border-radius:999px; font-size:.74rem; font-weight:600; color:#555; background:#fff; cursor:pointer; transition:all .2s; text-decoration:none; }
.btn-action:hover { border-color:var(--primary); color:var(--primary); }
.spek-list { display:flex; flex-direction:column; gap:.38rem; }
.spek-item { display:flex; align-items:center; gap:.6rem; font-size:.81rem; color:var(--text-body); }
.spek-item i { color:var(--primary); width:17px; flex-shrink:0; font-size:.88rem; }
.fas-grid { display:grid; grid-template-columns:1fr 1fr; gap:.2rem .9rem; }
.fas-item { display:flex; align-items:center; gap:.6rem; font-size:.8rem; color:var(--text-body); padding:.28rem 0; }
.fas-item i { color:var(--primary); width:17px; flex-shrink:0; font-size:.88rem; }
.fas-km-item { display:flex; align-items:center; gap:.6rem; font-size:.8rem; color:#0369a1; padding:.28rem 0; }
.fas-km-item i { color:#0369a1; width:17px; flex-shrink:0; }
.fas-tag { background:#f0f4f8; color:#555; border-radius:.32rem; padding:.16rem .48rem; font-size:.7rem; display:inline-block; margin:.08rem; }
.aturan-list { display:flex; flex-direction:column; gap:.4rem; }
.aturan-item { display:flex; align-items:flex-start; gap:.6rem; font-size:.8rem; color:var(--text-body); }
.aturan-item i { color:#f59e0b; flex-shrink:0; margin-top:.12rem; }
.aturan-item.larangan i { color:#dc2626; }
.cerita-box { font-size:.81rem; color:#555; line-height:1.7; }
.parkir-item { display:flex; align-items:center; gap:.6rem; font-size:.8rem; color:#15803d; padding:.28rem 0; }
.parkir-item i { color:#15803d; width:17px; flex-shrink:0; }

/* MAP & TABS */
#kostMap { width:100%; height:260px; border-radius:.7rem; border:1px solid var(--card-border); }
.tab-tempat-wrap { display:flex; gap:.35rem; margin:.7rem 0; overflow-x:auto; scrollbar-width:none; flex-wrap:nowrap; }
.tab-tempat-wrap::-webkit-scrollbar { display:none; }
.tab-tempat { padding:.28rem .8rem; border:1.5px solid var(--card-border); border-radius:999px; font-size:.74rem; font-weight:700; background:#fff; cursor:pointer; transition:all .2s; color:#555; white-space:nowrap; flex-shrink:0; }
.tab-tempat.active { border-color:var(--primary); background:var(--primary-light); color:var(--primary); }
.tempat-panel { display:none; }
.tempat-panel.active { display:block; }
.tempat-row { display:flex; justify-content:space-between; align-items:center; padding:.52rem 0; border-bottom:1px solid #f5f7fa; font-size:.8rem; }
.tempat-row:last-child { border-bottom:none; }
.tempat-row-left { display:flex; align-items:center; gap:.55rem; color:#333; }
.tempat-dist { font-size:.74rem; font-weight:700; color:var(--text-muted); background:#f0f4f8; border-radius:999px; padding:.16rem .58rem; }

.ketentuan-grid { display:grid; grid-template-columns:1fr 1fr; gap:.5rem; }
.ketentuan-item { display:flex; align-items:flex-start; gap:.55rem; font-size:.78rem; color:#444; padding:.65rem .8rem; background:#f8fafd; border-radius:.55rem; border:1px solid #edf0f5; }
.ketentuan-item i { color:#3b82f6; flex-shrink:0; margin-top:.08rem; }

/* REVIEW */
.rating-summary { display:flex; gap:1.5rem; align-items:center; background:linear-gradient(135deg,#fffbf0,#fff8e0); border:1px solid #fde68a; border-radius:.8rem; padding:1rem 1.2rem; margin-bottom:.9rem; }
.rating-big { font-size:2.5rem; font-weight:900; color:#f59e0b; line-height:1; }
.rating-bar-bg { background:#f0f3f8; border-radius:999px; height:6px; flex:1; overflow:hidden; }
.rating-bar-fill { background:linear-gradient(90deg,#f59e0b,#fbbf24); border-radius:999px; height:6px; }
.review-card { border-bottom:1px solid #f0f3f8; padding:.85rem 0; }
.review-card:last-child { border-bottom:none; }
.review-ava { width:36px; height:36px; border-radius:50%; background:var(--primary); color:#fff; font-weight:700; font-size:.8rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden; }

/* PEMILIK */
.pemilik-wrap { display:flex; align-items:center; gap:.9rem; }
.pemilik-ava { width:52px; height:52px; border-radius:50%; background:var(--primary); color:#fff; font-size:1.2rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden; border:3px solid var(--primary-light); }
.online-dot { width:7px; height:7px; background:#22c55e; border-radius:50%; display:inline-block; margin-right:.28rem; }
.btn-hubungi { border:2px solid var(--primary); color:var(--primary); background:#fff; border-radius:999px; padding:.36rem 1rem; font-size:.78rem; font-weight:700; cursor:pointer; transition:all .2s; }
.btn-hubungi:hover { background:var(--primary-light); }

/* ══ STICKY BOTTOM BAR (MOBILE) ══ */
.sticky-bottom-bar {
  position: fixed;
  bottom: 0; left: 0; right: 0;
  background: #fff;
  border-top: 1px solid var(--card-border);
  padding: .75rem 1.1rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: .8rem;
  z-index: 1000;
  box-shadow: 0 -4px 18px rgba(0,0,0,.1);
  padding-bottom: calc(.75rem + env(safe-area-inset-bottom));
}
.sbb-price {
  flex: 1;
  min-width: 0;
}
.sbb-price .val {
  font-size: 1.15rem;
  font-weight: 800;
  color: var(--primary);
  display: block;
}
.sbb-price .lbl {
  font-size: .68rem;
  color: var(--text-muted);
}
.sbb-btn-tanya {
  height: 44px;
  width: 44px;
  border-radius: .65rem;
  border: 1.5px solid var(--primary);
  background: #fff;
  color: var(--primary);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  cursor: pointer;
  flex-shrink: 0;
}
.sbb-btn-sewa {
  height: 44px;
  padding: 0 1.5rem;
  background: var(--primary);
  color: #fff;
  border: none;
  border-radius: .65rem;
  font-weight: 700;
  font-size: .88rem;
  flex: 1.4;
  cursor: pointer;
  white-space: nowrap;
}

@media (min-width: 992px) {
  .sticky-bottom-bar { display: none !important; }
}
@media (max-width: 991px) {
  .u-bottom-nav, .kf-footer { display: none !important; } /* Sembunyikan global nav & footer */
}

/* ══ REVIEW IMPROVEMENTS ══ */
.aspect-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: .6rem 1.2rem;
  flex: 1;
}
.aspect-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: .8rem;
}
.aspect-lbl { font-size: .75rem; color: #555; font-weight: 600; white-space: nowrap; }
.aspect-bar-bg { background: #eef2f6; height: 6px; border-radius: 10px; flex: 1; position: relative; }
.aspect-bar-fill { position: absolute; left: 0; top: 0; height: 100%; border-radius: 10px; background: #f59e0b; }
.aspect-val { font-size: .72rem; color: #888; width: 22px; text-align: right; font-weight: 700; }

.review-user-name { font-weight: 800; font-size: .88rem; color: var(--dark); }
.review-date { font-size: .7rem; color: var(--text-muted); }
.review-text { font-size: .84rem; color: #444; line-height: 1.6; margin-top: .4rem; }
.review-photos { display: flex; gap: .5rem; margin-top: .6rem; }
.review-photo-item { width: 70px; height: 70px; border-radius: .5rem; overflow: hidden; border: 1px solid #eee; cursor: pointer; }
.review-photo-item img { width: 100%; height: 100%; object-fit: cover; }

/* REKOM */
.rekom-track { display:flex; gap:.75rem; overflow-x:auto; padding-bottom:.3rem; scrollbar-width:none; }
.rekom-track::-webkit-scrollbar { display:none; }
.rekom-card { flex:0 0 185px; border:1px solid var(--card-border); border-radius:.8rem; overflow:hidden; background:#fff; text-decoration:none; color:inherit; transition:box-shadow .2s,transform .2s; }
.rekom-card:hover { box-shadow:0 6px 18px rgba(0,0,0,.1); transform:translateY(-2px); }
.rekom-thumb { width:100%; height:110px; object-fit:cover; }
.rekom-thumb-ph { width:100%; height:110px; background:#f0f4f8; display:flex; align-items:center; justify-content:center; font-size:1.8rem; color:#c0ccd8; }
.rekom-nav-btn { width:30px; height:30px; border:1px solid var(--card-border); background:#fff; border-radius:50%; font-size:.82rem; color:#444; cursor:pointer; display:flex; align-items:center; justify-content:center; }

/* KAMAR CARD */
.kamar-card { border:1px solid var(--card-border); border-radius:.8rem; overflow:hidden; background:#fff; transition:box-shadow .2s,transform .2s; }
.kamar-card:hover { box-shadow:0 6px 18px rgba(0,0,0,.1); transform:translateY(-2px); }
.kamar-thumb { width:100%; height:148px; object-fit:cover; }
.kamar-thumb-ph { width:100%; height:148px; background:#f0f4f8; display:flex; align-items:center; justify-content:center; font-size:2.5rem; color:#c0ccd8; }

/* SIDEBAR */
.sidebar-sticky { position:sticky; top:56px; }
.price-card { background:#fff; border:1px solid var(--card-border); border-radius:var(--radius); padding:1.25rem; box-shadow:0 4px 18px rgba(0,0,0,.08); }
.price-main { font-size:1.6rem; font-weight:900; color:var(--primary); line-height:1.1; }
.price-period { font-size:.76rem; color:#888; font-weight:400; }
.btn-sewa { background:var(--primary); color:#fff; border:0; border-radius:.65rem; padding:.8rem; font-weight:700; font-size:.92rem; width:100%; display:block; text-align:center; text-decoration:none; transition:background .2s; cursor:pointer; margin-bottom:.45rem; }
.btn-sewa:hover { background:#cb3518; color:#fff; }
.btn-sewa:disabled { background:#ccc; cursor:not-allowed; opacity:.6; }
.btn-tanya { background:#fff; color:var(--primary); border:2px solid var(--primary); border-radius:.65rem; padding:.62rem; font-weight:700; font-size:.84rem; width:100%; transition:all .2s; cursor:pointer; display:block; text-align:center; }
.btn-tanya:hover { background:var(--primary-light); }
.kamar-select-item { border:1.5px solid var(--card-border); border-radius:.58rem; padding:.52rem .72rem; cursor:pointer; transition:all .2s; margin-bottom:.38rem; }
.kamar-select-item:hover,.kamar-select-item.selected { border-color:var(--primary); background:var(--primary-light); }
.kamar-select-item.terisi { opacity:.5; cursor:not-allowed; pointer-events:none; }
.tipe-durasi-btn { flex:1; border:2px solid var(--card-border); border-radius:.58rem; padding:.42rem .55rem; cursor:pointer; text-align:center; background:#fff; transition:all .2s; }
.tipe-durasi-btn.active { border-color:var(--primary); background:var(--primary-light); }
.tipe-durasi-btn.disabled { opacity:.4; pointer-events:none; }
.tipe-durasi-btn .tl { font-size:.71rem; font-weight:700; color:#555; }
.tipe-durasi-btn.active .tl { color:var(--primary); }
.tipe-durasi-btn .th { font-size:.66rem; color:#888; margin-top:.1rem; }
.jumlah-btn { width:29px; height:29px; border:1px solid var(--card-border); border-radius:.4rem; background:#f8fafd; font-size:1rem; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.jumlah-btn:hover { background:#e4e9f0; }
.booking-input { border:1px solid var(--card-border); border-radius:.42rem; padding:.42rem .65rem; font-size:.78rem; color:#333; width:100%; outline:none; transition:border .2s; }
.booking-input:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(232,64,28,.1); }

/* LIGHTBOX */
#lbOverlay { display:none; position:fixed; inset:0; background:#fff; z-index:99999; flex-direction:column; animation:lbFadeIn .2s ease; }
@keyframes lbFadeIn { from{opacity:0} to{opacity:1} }
.lb-header { display:flex; align-items:center; justify-content:space-between; padding:.85rem 1.25rem; border-bottom:1px solid #f0f0f0; flex-shrink:0; background:#fff; }
.lb-header-left { display:flex; align-items:center; gap:.75rem; }
.lb-header-title { font-size:.95rem; font-weight:700; color:#333; }
.lb-header-count { font-size:.82rem; color:#888; font-weight:500; }
.lb-btn-close { width:36px; height:36px; border-radius:50%; border:none; background:#f5f5f5; color:#555; font-size:1.1rem; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; }
.lb-btn-close:hover { background:#eee; color:#222; }
.lb-main { flex:1; display:flex; align-items:center; justify-content:center; position:relative; min-height:0; padding:1rem 0; background:#fff; touch-action:pan-y; user-select:none; -webkit-user-drag:none; }
.lb-img-wrap { max-width:85%; max-height:65vh; display:flex; align-items:center; justify-content:center; }
#lbImg { max-width:100%; max-height:65vh; object-fit:contain; border-radius:.5rem; transition:transform .3s ease,opacity .2s ease; display:block; box-shadow:0 4px 20px rgba(0,0,0,.08); }
.lb-nav { position:absolute; top:50%; transform:translateY(-50%); width:44px; height:44px; border-radius:50%; border:1px solid #e0e0e0; background:rgba(255,255,255,.95); color:#444; font-size:1.3rem; cursor:pointer; display:flex; align-items:center; justify-content:center; z-index:10; transition:all .2s; box-shadow:0 2px 8px rgba(0,0,0,.1); }
.lb-nav:hover { background:#fff; border-color:var(--primary); color:var(--primary); transform:translateY(-50%) scale(1.05); }
.lb-nav-prev { left:16px; }
.lb-nav-next { right:16px; }
.lb-thumbs-wrap { padding:.75rem 1rem 1rem; flex-shrink:0; background:#fff; border-top:1px solid #f0f0f0; }
.lb-thumbs-label { font-size:.72rem; color:#888; font-weight:600; margin-bottom:.55rem; text-transform:uppercase; letter-spacing:.02em; }
#lbThumbs { display:flex; gap:.5rem; overflow-x:auto; scrollbar-width:none; padding:.2rem 0; }
#lbThumbs::-webkit-scrollbar { display:none; }
.lb-thumb { flex-shrink:0; cursor:pointer; border-radius:.4rem; overflow:hidden; border:2px solid transparent; transition:all .2s; opacity:.5; }
.lb-thumb img { width:64px; height:48px; object-fit:cover; display:block; pointer-events:none; }
.lb-thumb.active { opacity:1; border-color:var(--primary); transform:scale(1.05); box-shadow:0 2px 8px rgba(232,64,28,.25); }
.lb-thumb:hover { opacity:.8; }
.lb-swipe-hint { display:none; text-align:center; padding:.3rem; font-size:.65rem; color:#aaa; }

@media(max-width:768px){
  .lb-nav{width:38px;height:38px;font-size:1.1rem;}
  .lb-nav-prev{left:8px;} .lb-nav-next{right:8px;}
  .lb-img-wrap{max-width:92%;} #lbImg{max-height:55vh;}
  .lb-swipe-hint{display:block;} .lb-header{padding:.65rem 1rem;}
}
@media(max-width:991px){.galeri-wrap{height:290px;} .galeri-main{flex:0 0 58%;}}
@media(max-width:600px){
  .galeri-wrap{flex-direction:column;height:auto;}
  .galeri-main{flex:0 0 210px;height:210px;}
  .galeri-side{flex-direction:row;flex:0 0 120px;height:120px;}
  .galeri-slot{flex:1;}
  .ketentuan-grid{grid-template-columns:1fr;}
  .fas-grid{grid-template-columns:1fr;}
}
</style>
@endsection

@section('content')

@if(session('error'))
<div style="position:fixed;top:75px;right:20px;z-index:99999;max-width:360px;background:#fef2f2;border:1px solid #fecaca;border-radius:.7rem;padding:.85rem 1rem;box-shadow:0 8px 24px rgba(0,0,0,.12);display:flex;align-items:flex-start;gap:.65rem;">
  <span>❌</span>
  <div>
    <div style="font-weight:700;font-size:.81rem;color:#b91c1c;margin-bottom:.12rem;">Tidak Bisa Booking</div>
    <div style="font-size:.78rem;color:#dc2626;line-height:1.5;">{{ session('error') }}</div>
    <a href="{{ route('user.profil') }}" style="display:inline-block;margin-top:.45rem;background:#dc2626;color:#fff;font-size:.71rem;font-weight:700;padding:.25rem .7rem;border-radius:.32rem;text-decoration:none;">Lengkapi Profil →</a>
  </div>
  <button onclick="this.parentElement.remove()" style="background:none;border:none;color:#aaa;font-size:.9rem;cursor:pointer;margin-left:auto;">✕</button>
</div>
@endif
@if(session('success'))
<div style="position:fixed;top:75px;right:20px;z-index:99999;max-width:360px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.7rem;padding:.85rem 1rem;box-shadow:0 8px 24px rgba(0,0,0,.12);display:flex;gap:.65rem;">
  <span>✅</span><div style="font-size:.78rem;color:#16a34a;font-weight:600;">{{ session('success') }}</div>
</div>
@endif

@php
  $fotoUtama = $kost->foto_utama ? ltrim($kost->foto_utama, '/') : null;
  $semuaFoto = collect();
  if ($fotoUtama) $semuaFoto->push(['path' => $fotoUtama, 'label' => 'Foto Utama']);
  
  foreach ($kost->images as $img) {
      $semuaFoto->push(['path' => ltrim($img->image_path, '/'), 'label' => $img->kategori ?: 'Foto Properti']);
  }
  
  foreach ($kost->rooms as $room) {
      foreach ($room->images as $img) {
          $semuaFoto->push(['path' => ltrim($img->foto_path, '/'), 'label' => $img->judul ?: ('Kamar ' . $room->nomor_kamar)]);
      }
  }
  
  foreach ($kost->generalFacilities as $fac) {
      $semuaFoto->push(['path' => ltrim($fac->foto, '/'), 'label' => $fac->nama]);
  }

  // Filter agar foto yang sama hanya muncul satu kali saja
  $semuaFoto = $semuaFoto->unique('path')->values();
  $owner = $kost->owner ?? null;
  $foto1 = $semuaFoto->get(0);
  $foto2 = $semuaFoto->get(1) ?? $semuaFoto->get(0);
  $foto3 = $semuaFoto->get(2) ?? $semuaFoto->get(1) ?? $semuaFoto->get(0);
  $ikonFas = ['kasur'=>'bi-moon-stars','ac'=>'bi-thermometer-snow','kipas'=>'bi-wind','tv'=>'bi-tv','kamar mandi dalam'=>'bi-droplet','kamar mandi luar'=>'bi-droplet-half','air panas'=>'bi-fire','meja'=>'bi-easel','lemari'=>'bi-archive','wifi'=>'bi-wifi','motor'=>'bi-scooter','mobil'=>'bi-car-front','dapur'=>'bi-cup-hot','laundry'=>'bi-bag','mushola'=>'bi-building','cctv'=>'bi-camera-video','kulkas'=>'bi-snow','tempat tidur'=>'bi-moon-stars'];
@endphp

<div class="content-wrapper-detail" style="min-height:100vh; padding-bottom:4rem;">
<style>
  @media (max-width: 991px) {
    .content-wrapper-detail { padding-bottom: 160px !important; }
  }
</style>

  {{-- BREADCRUMB --}}
  <div style="background:#fff;border-bottom:1px solid var(--card-border);padding:.5rem 0;margin-bottom:0;">
    <div class="container">
      <nav><ol class="breadcrumb bc mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Kost {{ $kost->tipe_kost }}</a></li>
        <li class="breadcrumb-item active">{{ Str::limit($kost->nama_kost, 45) }}</li>
      </ol></nav>
    </div>
  </div>

  <div class="container" style="margin-top:.85rem;">

    {{-- GALERI FULL WIDTH --}}
    <div id="sec-foto" class="galeri-wrap mb-2">
      @if($foto1)
        <div class="galeri-main" onclick="bukaLb(0)">
          <img src="{{ asset('storage/' . $foto1['path']) }}" alt="{{ $kost->nama_kost }}">
        </div>
      @else
        <div class="galeri-main"><div class="galeri-ph">🏠</div></div>
      @endif
      <div class="galeri-side">
        <div class="galeri-slot" onclick="bukaLb({{ $semuaFoto->count() > 1 ? 1 : 0 }})">
          @if($foto2)<img src="{{ asset('storage/' . $foto2['path']) }}" alt="">
          @else<div class="galeri-ph">🏠</div>@endif
        </div>
        <div class="galeri-slot" onclick="bukaLb({{ $semuaFoto->count() > 2 ? 2 : ($semuaFoto->count() > 1 ? 1 : 0) }})">
          @if($foto3)<img src="{{ asset('storage/' . $foto3['path']) }}" alt="">
          @else<div class="galeri-ph">🏠</div>@endif
          <button class="btn-lihat-semua" onclick="event.stopPropagation(); bukaLb(0)">
            <i class="bi bi-grid-3x3-gap-fill"></i> Lihat semua foto
          </button>
        </div>
      </div>
    </div>

    <div class="row g-3">

      {{-- KOLOM KIRI --}}
      <div class="col-12 col-lg-8">

        {{-- NAMA KOS --}}
        <div class="sec mb-1">
          <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
              <div class="d-flex flex-wrap gap-2 mb-2">
                <span class="badge-tipe">🏠 {{ $kost->tipe_kost }}</span>
                <span class="badge-verified">✅ Terverifikasi</span>
              </div>
              <h1 style="font-size:1.35rem;font-weight:800;color:var(--dark);margin-bottom:.2rem;">{{ $kost->nama_kost }}</h1>
            </div>
            <div class="d-flex gap-2" style="flex-shrink:0;">
              <button class="btn-action" onclick="navigator.clipboard.writeText(window.location.href).then(()=>alert('Link disalin!'))"><i class="bi bi-share"></i> Bagikan</button>
              <button class="btn-action"><i class="bi bi-heart"></i> Simpan</button>
            </div>
          </div>
          <div class="info-row">
            <span><i class="bi bi-geo-alt-fill text-danger"></i> {{ $kost->kota }}</span>
            @if($kost->reviews->count() > 0)
              @php $avg = round($kost->reviews->avg('rating'), 1); @endphp
              <span style="color:#f59e0b;font-weight:700;">⭐ {{ $avg }} <span style="color:#888;font-weight:400;">({{ $kost->reviews->count() }} review)</span></span>
            @endif
            @php $tersedia = $kost->rooms->where('status_kamar','tersedia')->count(); @endphp
            @if($tersedia > 0)
              <span style="color:var(--primary);font-weight:700;"><i class="bi bi-key-fill"></i> Tersisa {{ $tersedia }} kamar</span>
            @else
              <span style="color:#dc2626;font-weight:700;">❌ Penuh</span>
            @endif
          </div>
          <div style="font-size:.78rem;color:#666;margin-top:.3rem;"><i class="bi bi-geo-alt text-danger"></i> {{ $kost->alamat }}</div>
        </div>

        {{-- DISEWAKAN OLEH --}}
        <div class="sec mb-1">
          <div style="font-size:.72rem;color:var(--text-muted);margin-bottom:.4rem;">Kos disewakan oleh</div>
          <div style="display:flex;align-items:center;gap:.8rem;">
            <div style="width:42px;height:42px;border-radius:50%;background:var(--primary);color:#fff;font-size:.95rem;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;border:2px solid var(--primary-light);">
              @if($owner && $owner->foto_profil)<img src="{{ asset('storage/' . $owner->foto_profil) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
              @else{{ strtoupper(substr($owner->name ?? 'P', 0, 1)) }}@endif
            </div>
            <div>
              <div style="font-weight:800;font-size:.88rem;color:var(--dark);">{{ $owner->name ?? 'Pemilik Kost' }}</div>
              @if($owner && $owner->created_at)<div style="font-size:.72rem;color:var(--text-muted);">Aktif sejak {{ $owner->created_at->translatedFormat('M Y') }}</div>@endif
              <div style="font-size:.71rem;color:#22c55e;font-weight:600;margin-top:.08rem;"><span class="online-dot"></span>Online baru-baru ini</div>
            </div>
            <div class="ms-auto"><i class="bi bi-shield-check-fill text-success" style="font-size:1.3rem;"></i></div>
          </div>
        </div>

        {{-- ══ STICKY NAV — taruh setelah "Disewakan Oleh" ══ --}}
        <div class="sec-nav" id="secNav" style="margin-left:-1.3rem;margin-right:-1.3rem;margin-bottom:.5rem;">
          <div class="sec-nav-inner">
            <button class="sec-nav-btn active" onclick="navScroll('sec-foto',this)">📷 Foto Properti</button>
            <button class="sec-nav-btn" onclick="navScroll('sec-fasilitas',this)">🛏️ Fasilitas Kamar</button>
            <button class="sec-nav-btn" onclick="navScroll('sec-fasilitas-umum',this)">🏢 Fasilitas Umum</button>
            <button class="sec-nav-btn" onclick="navScroll('sec-lokasi',this)">📍 Lokasi</button>
            <button class="sec-nav-btn" onclick="navScroll('sec-review',this)">⭐ Review</button>
            <button class="sec-nav-btn" onclick="navScroll('sec-pemilik',this)">👤 Pemilik Kos</button>
          </div>
        </div>

        {{-- LOOP KAMAR — group by HARGA --}}
        @php
          $roomGroups = [];
          foreach($kost->rooms as $room) {
            $fasArr2 = is_array($room->fasilitas) ? $room->fasilitas : (json_decode($room->fasilitas, true) ?: []);
            sort($fasArr2);
            // Key: harga bulanan + harga harian (beda harga = section beda)
            $key = ($room->harga_per_bulan ?? 0) . '||' . ($room->harga_harian ?? 0);
            if (!isset($roomGroups[$key])) {
              $roomGroups[$key] = ['rooms' => [], 'sample' => $room, 'fasArr' => $fasArr2];
            }
            $roomGroups[$key]['rooms'][] = $room;
          }
        @endphp

        @foreach($roomGroups as $gKey => $group)
        @php
          $room     = $group['sample'];
          $gRooms   = $group['rooms'];
          $fasArr   = $group['fasArr'];
          $hasListrik = collect($fasArr)->contains(fn($f) => str_contains(strtolower($f), 'listrik'));
          $hasKmDalam = collect($fasArr)->contains(fn($f) => str_contains(strtolower($f), 'dalam'));
          $hasKmLuar  = !$hasKmDalam;
          $jmlTersedia = collect($gRooms)->where('status_kamar','tersedia')->count();
          $jmlTotal    = count($gRooms);
          $adaTersedia = $jmlTersedia > 0;
        @endphp

        <div class="sec mb-1">

          {{-- ── JUDUL: Tipe X ── --}}
          <div style="margin-bottom:.85rem;">
            <div style="font-size:.7rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.25rem;">Tipe Kamar</div>
            <div style="font-size:1.15rem;font-weight:800;color:var(--dark);margin-bottom:.3rem;">
              @if($room->tipe_kamar)
                Tipe {{ $room->tipe_kamar }}
              @else
                Kamar Standar
              @endif
            </div>
            {{-- Nomor kamar --}}
            <div style="font-size:.78rem;color:#666;margin-bottom:.35rem;">
              @if($jmlTotal === 1)
                No. {{ $room->nomor_kamar }}
              @else
                No. {{ collect($gRooms)->pluck('nomor_kamar')->join(', ') }}
                <span style="color:#888;"> &middot; {{ $jmlTotal }} kamar</span>
              @endif
            </div>
            {{-- Status --}}
            @if($adaTersedia)
              <span class="status-tersedia">✅ Tersedia {{ $jmlTersedia }} kamar</span>
            @else
              <span class="status-terisi">❌ Semua Terisi</span>
            @endif
          </div>

          {{-- ── FOTO KAMAR (ambil dari kamar pertama yang punya foto) ── --}}
          @php
            $fotoKamar = null;
            foreach($gRooms as $gr) {
              if($gr->images->isNotEmpty()) { $fotoKamar = $gr->images->first(); break; }
            }
          @endphp
          @if($fotoKamar)
            <div style="margin-bottom:.85rem;border-radius:.65rem;overflow:hidden;height:160px;max-width:320px;">
              <img src="{{ asset('storage/'.ltrim($fotoKamar->foto_path,'/')) }}"
                style="width:100%;height:100%;object-fit:cover;" alt="Foto kamar">
            </div>
          @endif

          {{-- ── HARGA ── --}}
          <div style="display:flex;gap:1.2rem;flex-wrap:wrap;margin-bottom:.85rem;">
            @if($room->aktif_bulanan && $room->harga_per_bulan)
              <div>
                <div style="font-size:1.25rem;font-weight:900;color:var(--primary);">
                  Rp {{ number_format($room->harga_per_bulan,0,',','.') }}
                </div>
                <div style="font-size:.72rem;color:#888;">per bulan</div>
              </div>
            @endif
            @if($room->aktif_harian && $room->harga_harian)
              <div>
                <div style="font-size:1.1rem;font-weight:800;color:#d97706;">
                  Rp {{ number_format($room->harga_harian,0,',','.') }}
                </div>
                <div style="font-size:.72rem;color:#888;">per hari</div>
              </div>
            @endif
          </div>

          {{-- ── SPESIFIKASI ── --}}
          <div class="sec-title" style="font-size:.86rem;">📐 Spesifikasi</div>
          <div class="spek-list mb-3">
            @if($room->ukuran)
              <div class="spek-item"><i class="bi bi-bounding-box"></i> {{ $room->ukuran }}</div>
            @endif
            <div class="spek-item">
              <i class="bi bi-lightning-charge{{ $hasListrik ? '-fill' : '' }}"></i>
              {{ $hasListrik ? 'Termasuk listrik' : 'Tidak termasuk listrik (bayar sendiri)' }}
            </div>
            <div class="spek-item">
              <i class="bi bi-droplet{{ $hasKmDalam ? '-fill' : '' }}"></i>
              {{ $hasKmDalam ? 'Kamar mandi dalam' : 'Kamar mandi di luar' }}
            </div>
          </div>

          {{-- ── FASILITAS KAMAR ── --}}
          <hr class="sec-div">
          <div class="sec-title" style="font-size:.86rem;">🛏️ Fasilitas kamar</div>
          @if(count($fasArr) > 0)
            <div class="fas-grid">
              @foreach($fasArr as $f)
                @php
                  $ico = 'bi-check-circle-fill';
                  foreach($ikonFas as $k => $v) {
                    if(str_contains(strtolower($f), $k)) { $ico = $v; break; }
                  }
                @endphp
                <div class="fas-item"><i class="bi {{ $ico }}"></i>{{ $f }}</div>
              @endforeach
            </div>
          @else
            <div style="font-size:.78rem;color:var(--text-muted);">Belum ada fasilitas</div>
          @endif

          {{-- ── FASILITAS KAMAR MANDI ── --}}
          @if($hasKmLuar)
          <hr class="sec-div">
          <div class="sec-title" style="font-size:.86rem;">🚿 Kamar mandi</div>
          <div class="fas-grid">
            <div class="fas-km-item"><i class="bi bi-droplet"></i>Kamar Mandi Bersama</div>
            <div class="fas-km-item"><i class="bi bi-door-open"></i>Pintu Kamar Mandi</div>
            <div class="fas-km-item"><i class="bi bi-thermometer-snow"></i>Air Dingin</div>
            <div class="fas-km-item"><i class="bi bi-brightness-high"></i>Lampu</div>
          </div>
          @endif

          {{-- ── PERATURAN KOS — dari database $kost->aturan ── --}}
          @if($kost->aturan || !$hasListrik)
          <hr class="sec-div">
          <div class="sec-title" style="font-size:.86rem;">📋 Peraturan kamar ini</div>
          <div class="aturan-list">
            @if(!$hasListrik)
              <div class="aturan-item">
                <i class="bi bi-info-circle-fill" style="color:#3b82f6;margin-top:.12rem;"></i>
                <span>Listrik menggunakan token, biaya ditanggung penyewa sendiri</span>
              </div>
            @endif
            @if($kost->aturan)
              @foreach(explode("\n", $kost->aturan) as $a)
                @if(trim($a))
                  @php
                    $aLower = strtolower(trim($a));
                    $isLarangan = str_contains($aLower,'dilarang') || str_contains($aLower,'tidak boleh') || str_contains($aLower,'tidak untuk') || str_contains($aLower,'tidak diperbolehkan');
                  @endphp
                  <div class="aturan-item {{ $isLarangan ? 'larangan' : '' }}">
                    @if($isLarangan)
                      <i class="bi bi-x-circle-fill"></i>
                    @else
                      <i class="bi bi-exclamation-circle-fill"></i>
                    @endif
                    <span>{{ trim($a) }}</span>
                  </div>
                @endif
              @endforeach
            @endif
          </div>
          @endif

      {{-- ── DESKRIPSI KAMAR ── --}}
@if($room->deskripsi)
  <hr class="sec-div">
  <div class="sec-title" style="font-size:.86rem;">📝 Deskripsi Kamar</div>
  <div class="cerita-box">{{ $room->deskripsi }}</div>
@endif

          {{-- ── PILIH KAMAR TERSEDIA ── --}}
          @if($adaTersedia)
          <hr class="sec-div">
          <div style="font-size:.78rem;font-weight:700;color:var(--dark);margin-bottom:.42rem;">
            🔑 Pilih kamar tersedia:
          </div>
          <div style="display:flex;flex-wrap:wrap;gap:.38rem;">
            @foreach($gRooms as $gr)
              @if($gr->status_kamar === 'tersedia')
                @auth
                  @if(auth()->user()->role === 'user')
                    <button onclick="pilihKamarDanScroll({{ $gr->id_room }})"
                      style="background:var(--primary);color:#fff;border:none;border-radius:.5rem;padding:.38rem .9rem;font-size:.76rem;font-weight:700;cursor:pointer;transition:background .2s;"
                      onmouseover="this.style.background='#cb3518'" onmouseout="this.style.background='var(--primary)'">
                      No. {{ $gr->nomor_kamar }}
                    </button>
                  @endif
                @else
                  <a href="{{ route('login') }}"
                    style="background:var(--primary);color:#fff;border-radius:.5rem;padding:.38rem .9rem;font-size:.76rem;font-weight:700;text-decoration:none;display:inline-block;">
                    No. {{ $gr->nomor_kamar }}
                  </a>
                @endauth
              @else
                <span style="background:#f0f4f8;color:#bbb;border-radius:.5rem;padding:.38rem .9rem;font-size:.76rem;font-weight:600;text-decoration:line-through;">
                  No. {{ $gr->nomor_kamar }}
                </span>
              @endif
            @endforeach
          </div>
          @endif

        </div>
        @endforeach

    {{-- DESKRIPSI KOS --}}
@if($kost->deskripsi)
<div class="sec mb-1">
  <div class="sec-title">📖 Deskripsi Kos</div>
  <div class="cerita-box">{{ $kost->deskripsi }}</div>
</div>
@endif

        @if($kost->fasilitas || $kost->generalFacilities->count() > 0)
        <div id="sec-fasilitas-umum" class="sec mb-1">
          <div class="sec-title">🏢 Fasilitas umum</div>
          
          @if($kost->fasilitas)
          @php
            $fasArr = is_array($kost->fasilitas)
              ? $kost->fasilitas
              : (json_decode($kost->fasilitas, true) ?: array_map('trim', explode(',', $kost->fasilitas)));
          @endphp
          <div class="fas-grid mb-3">
            @foreach($fasArr as $f)
              <div class="fas-item"><i class="bi bi-check-circle-fill" style="color:#22c55e;"></i>{{ trim($f) }}</div>
            @endforeach
          </div>
          @endif

          {{-- Galeri Fasilitas Berfoto --}}
          @if($kost->generalFacilities->count() > 0)
          <hr class="sec-div">
          <div style="font-size:.82rem; font-weight:800; color:var(--dark); margin-bottom:.75rem; display:flex; align-items:center; gap:.45rem;">
            <i class="bi bi-camera" style="color:var(--primary);"></i> Foto Fasilitas Umum
          </div>
          <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(150px, 1fr)); gap:12px; margin-bottom:1rem;">
            @foreach($kost->generalFacilities as $fac)
              @php
                $facIdx = $semuaFoto->search(fn($item) => str_contains($item['path'], basename($fac->foto)));
                $facIdx = ($facIdx !== false) ? $facIdx : 0;
              @endphp
              <div style="border-radius:.75rem; overflow:hidden; border:1px solid var(--card-border); background:#fff; box-shadow:0 2px 10px rgba(0,0,0,.06); cursor:pointer; transition:transform .2s, box-shadow .2s;"
                   onclick="bukaLb({{ $facIdx }})"
                   onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.12)'"
                   onmouseout="this.style.transform='none';this.style.boxShadow='0 2px 10px rgba(0,0,0,.06)'">
                <div style="position:relative; overflow:hidden; height:110px;">
                  <img src="{{ asset('storage/'.$fac->foto) }}"
                       alt="{{ $fac->nama }}"
                       style="width:100%; height:100%; object-fit:cover; display:block; transition:transform .3s ease;"
                       onmouseover="this.style.transform='scale(1.05)'"
                       onmouseout="this.style.transform='none'">
                  <div style="position:absolute;top:8px;right:8px;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);border-radius:999px;padding:.15rem .5rem;">
                    <i class="bi bi-zoom-in" style="color:#fff;font-size:.6rem;"></i>
                  </div>
                </div>
                <div style="padding:.55rem .65rem; border-top:1px solid #f0f3f8;">
                  <div style="font-size:.75rem; font-weight:700; color:var(--dark); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:flex; align-items:center; gap:.35rem;">
                    <i class="bi bi-tag-fill" style="color:var(--primary);font-size:.65rem;flex-shrink:0;"></i>
                    {{ $fac->nama }}
                  </div>
                </div>
              </div>
            @endforeach
          </div>
          @endif

          @php $fasStr=strtolower($kost->fasilitas??''); $hasMotor=str_contains($fasStr,'motor')||str_contains($fasStr,'parkir'); $hasMobil=str_contains($fasStr,'mobil'); @endphp
          @if($hasMotor || $hasMobil)
          <hr class="sec-div">
          <div class="sec-title" style="font-size:.86rem;">🅿️ Fasilitas parkir</div>
          @if($hasMotor)<div class="parkir-item"><i class="bi bi-scooter"></i>Parkir Motor</div>@endif
          @if($hasMobil)<div class="parkir-item"><i class="bi bi-car-front-fill"></i>Parkir Mobil</div>@endif
          @endif
        </div>
        @endif

        {{-- PERATURAN KOS --}}
        @if($kost->aturan)
        <div class="sec mb-1">
          <div class="sec-title">📋 Peraturan di kos ini</div>
          <div class="aturan-list">
            @foreach(explode("\n", $kost->aturan) as $a)
              @if(trim($a))
                @php $aL=strtolower(trim($a)); $isL=str_contains($aL,'dilarang')||str_contains($aL,'tidak boleh')||str_contains($aL,'tidak untuk')||str_contains($aL,'tidak diperbolehkan'); @endphp
                <div class="aturan-item {{ $isL ? 'larangan' : '' }}">
                  @if($isL)<i class="bi bi-x-circle-fill"></i>@else<i class="bi bi-exclamation-circle-fill"></i>@endif
                  <span>{{ trim($a) }}</span>
                </div>
              @endif
            @endforeach
          </div>
        </div>
        @endif

        {{-- LOKASI --}}
        <div id="sec-lokasi" class="sec mb-1">
          <div class="sec-title">📍 Lokasi dan lingkungan sekitar</div>
          <div style="font-size:.78rem;color:#555;margin-bottom:.7rem;"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $kost->alamat }}, {{ $kost->kota }}</div>
          <div id="kostMap"></div>

          {{-- 4 TAB --}}
          <div class="tab-tempat-wrap">
            <div class="tab-tempat active" onclick="gantiTab(this,'tp-tempat')">🏪 Terdekat</div>
            <div class="tab-tempat" onclick="gantiTab(this,'tp-kuliner')">🍽️ Kuliner</div>
            <div class="tab-tempat" onclick="gantiTab(this,'tp-wisata')">🛍️ Mall & Wisata</div>
            <div class="tab-tempat" onclick="gantiTab(this,'tp-transport')">🚌 Transportasi</div>
          </div>
          <div id="tp-tempat" class="tempat-panel active">
            <div id="tp-loading" style="text-align:center;padding:.7rem;font-size:.78rem;color:var(--text-muted);"><div class="spinner-border spinner-border-sm text-danger me-1" style="width:.9rem;height:.9rem;"></div> Mencari tempat terdekat...</div>
            <div id="tp-hasil"></div>
          </div>
          <div id="tp-kuliner" class="tempat-panel">
            <div id="tp-kuliner-loading" style="text-align:center;padding:.7rem;font-size:.78rem;color:var(--text-muted);"><div class="spinner-border spinner-border-sm text-danger me-1" style="width:.9rem;height:.9rem;"></div> Mencari kuliner...</div>
            <div id="tp-kuliner-hasil"></div>
          </div>
          <div id="tp-wisata" class="tempat-panel">
            <div id="tp-wisata-loading" style="text-align:center;padding:.7rem;font-size:.78rem;color:var(--text-muted);"><div class="spinner-border spinner-border-sm text-danger me-1" style="width:.9rem;height:.9rem;"></div> Mencari mall & wisata...</div>
            <div id="tp-wisata-hasil"></div>
          </div>
          <div id="tp-transport" class="tempat-panel">
            <div id="tr-loading" style="text-align:center;padding:.7rem;font-size:.78rem;color:var(--text-muted);"><div class="spinner-border spinner-border-sm text-danger me-1" style="width:.9rem;height:.9rem;"></div> Mencari transportasi...</div>
            <div id="tr-hasil"></div>
          </div>
        </div>

        {{-- KETENTUAN --}}
        <div class="sec mb-1">
          <div class="sec-title">📄 Ketentuan pengajuan sewa</div>
          <div class="ketentuan-grid">
            <div class="ketentuan-item"><i class="bi bi-person-check-fill"></i><span>Lengkapi data diri sebelum mengajukan sewa</span></div>
            <div class="ketentuan-item"><i class="bi bi-clock-fill"></i><span>Konfirmasi booking dalam 1×24 jam oleh owner</span></div>
            <div class="ketentuan-item"><i class="bi bi-credit-card-fill"></i><span>Pembayaran di muka sesuai tipe sewa dipilih</span></div>
            <div class="ketentuan-item"><i class="bi bi-shield-fill-check"></i><span>Dana aman hingga check-in dikonfirmasi</span></div>
            <div class="ketentuan-item"><i class="bi bi-x-circle-fill" style="color:#dc2626;"></i><span>Pembatalan gratis hingga 24 jam sebelum check-in</span></div>
            <div class="ketentuan-item"><i class="bi bi-file-earmark-text-fill"></i><span>Wajib menyetujui peraturan kos sebelum menempati</span></div>
          </div>
        </div>

        {{-- REVIEW --}}
        <div id="sec-review" class="sec mb-1" style="padding-bottom:1.5rem;">
          <div class="sec-title">⭐ Review penghuni ({{ $kost->reviews->count() }})</div>
          @if($kost->reviews->count() > 0)
            @php
              $avg = round($kost->reviews->avg('rating'), 1);
              $total = $kost->reviews->count();
              $stars = [5=>0,4=>0,3=>0,2=>0,1=>0];
              foreach($kost->reviews as $r) $stars[(int)$r->rating]++;
            @endphp
            <div class="rating-summary flex-wrap gap-4">
              <div class="text-center">
                <div class="rating-big">{{ $avg }}</div>
                <div style="font-size:.75rem;color:#f59e0b;margin-top:.1rem;">
                  @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$avg?'-fill':'' }}"></i>@endfor
                </div>
                <div style="font-size:.68rem;color:#888;margin-top:.2rem;">{{ $total }} Review</div>
              </div>
              <div class="aspect-grid">
                @foreach([5,4,3,2,1] as $s)
                <div class="aspect-item">
                  <span class="aspect-lbl">{{ $s }} <i class="bi bi-star-fill text-warning"></i></span>
                  <div class="aspect-bar-bg"><div class="aspect-bar-fill" style="width:{{ ($stars[$s]/$total)*100 }}%"></div></div>
                  <span class="aspect-val">{{ $stars[$s] }}</span>
                </div>
                @endforeach
              </div>
            </div>

            @foreach($kost->reviews as $review)
              <div class="review-card">
                <div class="d-flex gap-3">
                  <div class="review-ava">
                    @if($review->user->foto_profil)<img src="{{ asset('storage/'.$review->user->foto_profil) }}" style="width:100%;height:100%;object-fit:cover;">
                    @else{{ strtoupper(substr($review->user->name,0,1)) }}@endif
                  </div>
                  <div class="flex-1">
                    <div class="d-flex justify-content-between align-items-center">
                      <div class="review-user-name">{{ $review->user->name }}</div>
                      <div class="review-date">{{ $review->created_at->translatedFormat('d M Y') }}</div>
                    </div>
                    <div style="font-size:.72rem;color:#f59e0b;margin-top:.1rem;">
                      @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$review->rating?'-fill':'' }}"></i>@endfor
                    </div>
                    <div class="review-text">{{ $review->review }}</div>
                  </div>
                </div>
              </div>
            @endforeach
          @else
            <div class="text-center py-4 text-muted" style="font-size:.82rem;">
              <i class="bi bi-chat-left-dots fs-3 d-block mb-2 opacity-25"></i>
              Belum ada review untuk kos ini
            </div>
          @endif
        </div>

        {{-- PEMILIK KOS --}}
        <div id="sec-pemilik" class="sec mb-1">
          <div class="sec-title">👤 Informasi Pemilik</div>
          <div class="pemilik-wrap">
            <div class="pemilik-ava">
              @if($owner && $owner->foto_profil)<img src="{{ asset('storage/'.$owner->foto_profil) }}" style="width:100%;height:100%;object-fit:cover;">
              @else{{ strtoupper(substr($owner->name ?? 'P', 0, 1)) }}@endif
            </div>
            <div class="flex-1">
              <div style="font-weight:800;font-size:.9rem;color:var(--dark);">{{ $owner->name ?? 'Pemilik Kost' }}</div>
              <div style="font-size:.7rem;color:#22c55e;font-weight:600;"><span class="online-dot"></span>Aktif baru saja</div>
            </div>
            <button class="btn-hubungi" onclick="alert('Fitur chat segera hadir!')">Tanya Pemilik</button>
          </div>
        </div>

      </div>

      {{-- KOLOM KANAN --}}
      <div class="col-12 col-lg-4">
        <div class="sidebar-sticky">

          {{-- BOOKING CARD --}}
          <div class="price-card mb-3" id="frmBooking">
            <div class="detail-label mb-2">Harga Mulai</div>
            <div class="price-main">Rp {{ number_format($kost->harga_mulai,0,',','.') }}<span class="price-period"> / bulan</span></div>
            <hr class="sec-div">

            @auth
              @if(auth()->user()->role === 'user')
                <form action="{{ route('user.booking.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="id_room" id="sbRoomId">
                  <input type="hidden" name="tipe_sewa" id="sbTipe" value="bulanan">
                  <input type="hidden" name="jumlah_sewa" id="sbJml" value="1">

                  <div class="mb-3">
                    <label class="detail-label">Pilih Kamar</label>
                    @if($tersedia > 0)
                      <div style="max-height:160px;overflow-y:auto;padding-right:4px;">
                        @foreach($kost->rooms as $r)
                          <div class="kamar-select-item {{ $r->status_kamar!=='tersedia'?'terisi':'' }}"
                            id="ki{{ $r->id_room }}"
                            data-hb="{{ $r->harga_per_bulan }}"
                            data-hh="{{ $r->harga_harian }}"
                            data-ab="{{ $r->aktif_bulanan?'1':'0' }}"
                            data-ah="{{ $r->aktif_harian?'1':'0' }}"
                            onclick="pilihKamar({{ $r->id_room }}, this)">
                            <div class="d-flex justify-content-between align-items-center">
                              <div style="font-size:.8rem;font-weight:700;">No. {{ $r->nomor_kamar }} ({{ $r->tipe_kamar }})</div>
                              @if($r->status_kamar==='tersedia')<i class="bi bi-circle text-muted" style="font-size:.75rem;"></i>@else<span class="badge bg-danger" style="font-size:.62rem;">Terisi</span>@endif
                            </div>
                            <div style="font-size:.72rem;color:#888;margin-top:.15rem;">Ukuran {{ $r->ukuran }} &middot; {{ $r->aktif_bulanan?'Bulanan':'' }} {{ $r->aktif_harian?'& Harian':'' }}</div>
                          </div>
                        @endforeach
                      </div>
                    @else
                      <div class="alert alert-danger py-2 px-3 m-0" style="font-size:.78rem;">Kos ini sudah penuh.</div>
                    @endif
                  </div>

                  <div id="wTipe" style="display:none;" class="mb-3">
                    <label class="detail-label">Tipe Sewa</label>
                    <div class="d-flex gap-2">
                      <div class="tipe-durasi-btn" id="btnBln" onclick="pilihTipe('bulanan')">
                        <div class="tl">Bulanan</div>
                        <div class="th" id="lhBln">-</div>
                      </div>
                      <div class="tipe-durasi-btn" id="btnHar" onclick="pilihTipe('harian')">
                        <div class="tl">Harian</div>
                        <div class="th" id="lhHar">-</div>
                      </div>
                    </div>
                  </div>

                  <div id="wJml" style="display:none;" class="mb-3">
                    <label class="detail-label" id="lblJml">Jumlah</label>
                    <div class="d-flex align-items-center gap-3">
                      <div class="d-flex align-items-center gap-2" style="background:#f8fafd;padding:.3rem .5rem;border-radius:.6rem;border:1px solid #edf0f5;">
                        <button type="button" class="jumlah-btn" onclick="ubahJml(-1)">-</button>
                        <input type="text" id="jmlDisp" value="1" readonly style="width:35px;text-align:center;border:none;background:transparent;font-weight:800;color:var(--dark);font-size:.88rem;">
                        <button type="button" class="jumlah-btn" onclick="ubahJml(1)">+</button>
                      </div>
                    </div>
                  </div>

                  <div id="wTgl" style="display:none;" class="row g-2 mb-3">
                    <div class="col-7">
                      <label class="detail-label">Tanggal Masuk</label>
                      <input type="date" name="tanggal_masuk" id="tglMasuk" class="booking-input">
                    </div>
                    <div class="col-5">
                      <label class="detail-label">Jam (Estimasi)</label>
                      <input type="time" name="jam_masuk" id="jamMasuk" class="booking-input" value="12:00">
                    </div>
                  </div>

                  <div id="wCO" style="display:none;" class="mb-3">
                    <div style="background:#f0f7ff;border:1px solid #cce3ff;border-radius:.65rem;padding:.65rem .85rem;display:flex;align-items:center;gap:.7rem;">
                      <i class="bi bi-calendar-event text-primary" style="font-size:1.1rem;"></i>
                      <div>
                        <div style="font-size:.68rem;color:#5c728a;font-weight:600;text-transform:uppercase;">Estimasi Selesai</div>
                        <div style="font-size:.82rem;font-weight:800;color:#0369a1;" id="coDisp">Otomatis dihitung</div>
                      </div>
                    </div>
                  </div>

                  <div id="wRing" style="display:none;background:#f8fafc;border:1px solid #edf2f7;border-radius:.8rem;padding:.9rem;margin-bottom:1.1rem;">
                    <div class="d-flex justify-content-between mb-2" style="font-size:.8rem;color:#666;">
                      <span id="rLabel">Sewa</span><span id="rHarga">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size:.8rem;color:#666;">
                      <span>Biaya Layanan</span><span id="rLayanan">Rp 0</span>
                    </div>
                    <hr style="margin:.7rem 0;border-top:1px dashed #cbd5e0;">
                    <div class="d-flex justify-content-between" style="font-size:.9rem;font-weight:800;color:var(--dark);">
                      <span>Total Pembayaran</span><span id="rTotal" class="text-primary">Rp 0</span>
                    </div>
                  </div>

                  <button type="submit" id="btnBayar" class="btn-sewa" disabled style="opacity:.6;">Ajukan Sewa</button>
                  <a href="#" class="btn-tanya" onclick="alert('Fitur chat segera hadir!')">Tanya Pemilik</a>

                </form>
              @else
                <div class="alert alert-warning text-center py-3" style="font-size:.8rem;border-radius:.8rem;">
                  Hanya akun <strong>Pencari Kost</strong> yang bisa melakukan booking.
                </div>
              @endif
            @else
              <a href="{{ route('login') }}" class="btn-sewa">Login untuk Booking</a>
              <div class="text-center mt-2">
                <span class="text-muted" style="font-size:.75rem;">Belum punya akun? <a href="{{ route('register') }}" style="color:var(--primary);font-weight:700;text-decoration:none;">Daftar Sekarang</a></span>
              </div>
            @endauth
          </div>

          {{-- REKOM --}}
          <div class="price-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="sec-title mb-0" style="font-size:.85rem;">🔥 Kos Serupa</div>
              <div class="d-flex gap-1">
                <button class="rekom-nav-btn" id="rkPrev">‹</button>
                <button class="rekom-nav-btn" id="rkNext">›</button>
              </div>
            </div>
            <div class="rekom-track" id="rkTrack">
              @foreach(\App\Models\Kost::where('id_kost','!=',$kost->id_kost)->where('kota',$kost->kota)->take(5)->get() as $rk)
              <a href="{{ route('kost.show', $rk->id_kost) }}" class="rekom-card">
                @if($rk->foto_utama)<img src="{{ asset('storage/'.$rk->foto_utama) }}" class="rekom-thumb">@else<div class="rekom-thumb-ph">🏠</div>@endif
                <div style="padding:.6rem .75rem;">
                  <div style="font-size:.65rem;font-weight:700;color:var(--primary);text-transform:uppercase;">{{ $rk->tipe_kost }}</div>
                  <div style="font-size:.78rem;font-weight:800;color:var(--dark);margin-top:.1rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $rk->nama_kost }}</div>
                  <div style="font-size:.8rem;font-weight:800;color:var(--primary);margin-top:.2rem;">Rp {{ number_format($rk->harga_mulai,0,',','.') }}</div>
                </div>
              </a>
              @endforeach
            </div>
          </div>

          <div style="background:#fff;border:1px solid var(--card-border);border-radius:var(--radius);padding:.7rem;box-shadow:0 1px 4px rgba(0,0,0,.05);margin-top:1rem;">
            <div class="row g-0 text-center">
              <div class="col-6" style="border-right:1px solid #f0f3f8;padding:.45rem 0;">
                <div style="font-size:1.3rem;font-weight:800;color:var(--primary);">{{ $tersedia }}</div>
                <div style="font-size:.68rem;color:#888;">🔑 Tersedia</div>
              </div>
              <div class="col-6" style="padding:.45rem 0;">
                <div style="font-size:1.3rem;font-weight:800;color:var(--dark);">{{ $kost->rooms->count() }}</div>
                <div style="font-size:.68rem;color:#888;">🏠 Total Kamar</div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- LIGHTBOX --}}
<div id="lbOverlay" onclick="lbBg(event)">
  <div class="lb-header">
    <div class="lb-header-left">
      <div class="lb-header-title" id="lbHeaderTitle">Foto Properti</div>
      <div class="lb-header-count" id="lbTitle">1 / {{ $semuaFoto->count() }}</div>
    </div>
    <button class="lb-btn-close" onclick="lbTutup()">✕</button>
  </div>
  <div class="lb-main" id="lbMain">
    <button class="lb-nav lb-nav-prev" onclick="lbNav(-1)">‹</button>
    <div class="lb-img-wrap">
      <img id="lbImg" src="" alt="" draggable="false">
      <div id="lbCaption" style="position:absolute;bottom:20px;background:rgba(0,0,0,0.6);color:#fff;padding:6px 18px;border-radius:999px;font-size:0.85rem;font-weight:700;backdrop-filter:blur(4px);z-index:5;"></div>
    </div>
    <button class="lb-nav lb-nav-next" onclick="lbNav(1)">›</button>
  </div>
  <div class="lb-swipe-hint">← Geser untuk navigasi →</div>
  <div class="lb-thumbs-wrap">
    <div class="lb-thumbs-label">Galeri Foto</div>
    <div id="lbThumbs">
      @foreach($semuaFoto as $i => $foto)
      <div class="lb-thumb {{ $i===0?'active':'' }}" id="lbThumb{{ $i }}" onclick="lbGoto({{ $i }})">
        <img src="{{ asset('storage/'.$foto['path']) }}" alt="{{ $foto['label'] }}">
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- ══ STICKY BOTTOM BAR (MOBILE) ══ --}}
<div class="sticky-bottom-bar" style="flex-direction:column; align-items:stretch; gap:6px; padding:10px 16px;">
  <!-- Owner Info Line -->
  <div class="d-flex align-items-center justify-content-between mb-1">
    <div class="d-flex align-items-center gap-2">
       <div style="width:28px; height:28px; border-radius:50%; overflow:hidden; background:var(--primary); color:#fff; display:flex; align-items:center; justify-content:center; font-size:.7rem; font-weight:800; border:1px solid #eee;">
          @if($owner && $owner->foto_profil)<img src="{{ asset('storage/'.$owner->foto_profil) }}" style="width:100%;height:100%;object-fit:cover;">
          @else{{ strtoupper(substr($owner->name ?? 'P', 0, 1)) }}@endif
       </div>
       <span style="font-weight:700; font-size:.8rem; color:#333;">{{ $owner->name ?? 'Pemilik' }}</span>
       <i class="bi bi-patch-check-fill text-primary" style="font-size:.7rem;"></i>
    </div>
    <div style="font-size:.68rem; color:var(--text-muted);">Tanya pemilik via Chat</div>
  </div>

  <div style="background:linear-gradient(90deg, #fff3e0, #fff); color:#e65100; font-size:.68rem; font-weight:700; padding:8px 12px; border-radius:8px; margin-bottom:4px; border:1px solid #ffe0b2; display:flex; align-items:center; gap:8px;">
    <i class="bi bi-fire" style="font-size:1rem;"></i>
    <span>Sedang populer! {{ rand(8, 25) }} orang mengajukan survei hari ini</span>
  </div>
  
  <div class="d-flex align-items-center justify-content-between mt-1">
     <div class="sbb-price">
        <span class="val" style="font-size:1.2rem;">Rp {{ number_format($kost->harga_mulai,0,',','.') }}<span style="font-size:.7rem; font-weight:400; color:#888;"> /bulan</span></span>
        <span class="lbl" style="color:var(--primary); font-weight:700; font-size:.68rem; cursor:pointer;" onclick="scrollToBooking()">Estimasi pembayaran <i class="bi bi-chevron-right" style="font-size:.6rem;"></i></span>
     </div>
     <div class="d-flex gap-2">
        <button class="sbb-btn-tanya" onclick="alert('Fitur chat segera hadir!')" style="width:48px; height:46px; border-radius:10px;">
           <i class="bi bi-chat-dots" style="font-size:1.3rem;"></i>
        </button>
        <button class="sbb-btn-sewa" onclick="scrollToBooking()" style="height:46px; border-radius:10px; padding:0 1.5rem; font-size:.9rem; font-weight:800;">Ajukan Sewa</button>
     </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
function scrollToBooking() {
  const el = document.getElementById('frmBooking') || document.getElementById('sec-review');
  el.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function bukaLbCustom(url, label) {
  const img = document.getElementById('lbImg');
  const caption = document.getElementById('lbCaption');
  const title = document.getElementById('lbHeaderTitle');
  
  document.getElementById('lbOverlay').style.display = 'flex';
  document.body.style.overflow = 'hidden';
  
  img.src = url;
  caption.textContent = label;
  title.textContent = label;
  
  // Sembunyikan thumb dan nav internal lightbox
  document.querySelector('.lb-thumbs-wrap').style.display = 'none';
  document.querySelectorAll('.lb-nav').forEach(n => n.style.display = 'none');
  document.getElementById('lbTitle').style.display = 'none';
}

function lbTutup() {
  document.getElementById('lbOverlay').style.display = 'none';
  document.body.style.overflow = '';
  // Restore
  document.querySelector('.lb-thumbs-wrap').style.display = 'block';
  document.querySelectorAll('.lb-nav').forEach(n => n.style.display = 'flex');
  document.getElementById('lbTitle').style.display = 'block';
}

/* ═══ LIGHTBOX ═══ */
const LBF = @json($semuaFoto->values());
let lbI=0, lbTouchStartX=0;
function bukaLb(i){lbI=i;document.getElementById('lbOverlay').style.display='flex';document.body.style.overflow='hidden';lbR();}
function lbTutup(){document.getElementById('lbOverlay').style.display='none';document.body.style.overflow='';}
function lbBg(e){if(e.target===document.getElementById('lbOverlay'))lbTutup();}
function lbNav(d){lbI=(lbI+d+LBF.length)%LBF.length;lbR();}
function lbGoto(i){lbI=i;lbR();}
function lbR(){
  if(!LBF.length)return;
  const f=LBF[lbI],img=document.getElementById('lbImg');
  img.style.opacity='0';img.style.transform='scale(0.98)';
  setTimeout(()=>{img.src='{{ asset("storage") }}/'+f.path;img.style.opacity='1';img.style.transform='scale(1)';},120);
  document.getElementById('lbTitle').textContent=(lbI+1)+' / '+LBF.length;
  document.getElementById('lbHeaderTitle').textContent = f.label;
  document.getElementById('lbCaption').textContent = f.label;
  document.querySelectorAll('.lb-thumb').forEach((el,i)=>{el.classList.toggle('active',i===lbI);if(i===lbI)el.scrollIntoView({behavior:'smooth',inline:'center',block:'nearest'});});
}
document.addEventListener('keydown',e=>{const ov=document.getElementById('lbOverlay');if(!ov||ov.style.display==='none')return;if(e.key==='ArrowLeft')lbNav(-1);if(e.key==='ArrowRight')lbNav(1);if(e.key==='Escape')lbTutup();});
const lbMain=document.getElementById('lbMain');
if(lbMain){
  let isDragging=false,startX=0;
  lbMain.addEventListener('touchstart',e=>lbTouchStartX=e.changedTouches[0].screenX,{passive:true});
  lbMain.addEventListener('touchend',e=>{const d=lbTouchStartX-e.changedTouches[0].screenX;if(Math.abs(d)>50)lbNav(d>0?1:-1);},{passive:true});
  lbMain.addEventListener('mousedown',e=>{isDragging=true;startX=e.clientX;lbMain.style.cursor='grabbing';});
  lbMain.addEventListener('mouseup',e=>{if(!isDragging)return;isDragging=false;lbMain.style.cursor='default';const d=startX-e.clientX;if(Math.abs(d)>80)lbNav(d>0?1:-1);});
  lbMain.addEventListener('mouseleave',()=>{isDragging=false;lbMain.style.cursor='default';});
}

/* ═══ STICKY NAV SCROLL ═══ */
function navScroll(id, btn) {
  const el = document.getElementById(id);
  if (!el) return;
  const navH = document.getElementById('secNav')?.offsetHeight || 48;
  const top = el.getBoundingClientRect().top + window.scrollY - navH - 10;
  window.scrollTo({ top, behavior: 'smooth' });
  document.querySelectorAll('.sec-nav-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}

// Auto-highlight nav saat scroll
window.addEventListener('scroll', () => {
  const sections = [
    { id: 'sec-foto',         btn: 0 },
    { id: 'sec-fasilitas',    btn: 1 },
    { id: 'sec-fasilitas-umum', btn: 2 },
    { id: 'sec-lokasi',       btn: 3 },
    { id: 'sec-review',       btn: 4 },
    { id: 'sec-pemilik',      btn: 5 },
  ];
  const navH = document.getElementById('secNav')?.offsetHeight || 48;
  const btns = document.querySelectorAll('.sec-nav-btn');
  let active = 0;
  sections.forEach((s, i) => {
    const el = document.getElementById(s.id);
    if (el && el.getBoundingClientRect().top <= navH + 30) active = i;
  });
  btns.forEach((b, i) => b.classList.toggle('active', i === active));
}, { passive: true });

/* ═══ BOOKING ═══ */
const E=id=>document.getElementById(id);
let sRId='',sHB=0,sHH=0,sAH=false,sAB=false,sT='bulanan',sJ=1,sHr=0;
window.pilihKamarDanScroll=function(r){const t=E('ki'+r);if(t)pilihKamar(r,t);E('frmBooking')?.scrollIntoView({behavior:'smooth',block:'center'});};
window.pilihKamar=function(r,el){
  document.querySelectorAll('.kamar-select-item').forEach(x=>x.classList.remove('selected'));
  if(!el)return;el.classList.add('selected');
  sRId=r;sHB=parseInt(el.dataset.hb||0);sHH=parseInt(el.dataset.hh||0);sAH=el.dataset.ah==='1';sAB=el.dataset.ab==='1';
  if(E('sbRoomId'))E('sbRoomId').value=r;
  if(E('lhBln'))E('lhBln').textContent=(sAB&&sHB>0)?'Rp '+sHB.toLocaleString('id-ID')+'/bln':'Tidak tersedia';
  if(E('lhHar'))E('lhHar').textContent=(sAH&&sHH>0)?'Rp '+sHH.toLocaleString('id-ID')+'/hari':'Tidak tersedia';
  E('btnBln')?.classList.toggle('disabled',!(sAB&&sHB>0));
  E('btnHar')?.classList.toggle('disabled',!(sAH&&sHH>0));
  sT=(sAB&&sHB>0)?'bulanan':'harian';
  ['wTipe','wJml','wTgl','wCO'].forEach(id=>{if(E(id))E(id).style.display='block';});
  pilihTipe(sT);
};
window.pilihTipe=function(t){
  if(t==='bulanan'&&(!sAB||sHB<=0))return;if(t==='harian'&&(!sAH||sHH<=0))return;
  sT=t;if(E('sbTipe'))E('sbTipe').value=t;
  E('btnBln')?.classList.remove('active');E('btnHar')?.classList.remove('active');
  if(t==='bulanan'){E('btnBln')?.classList.add('active');sHr=sHB;if(E('lblJml'))E('lblJml').textContent='📆 Jumlah Bulan';}
  else{E('btnHar')?.classList.add('active');sHr=sHH;if(E('lblJml'))E('lblJml').textContent='📅 Jumlah Hari';}
  sJ=1;if(E('jmlDisp'))E('jmlDisp').value=1;if(E('sbJml'))E('sbJml').value=1;hitungCO();
};
window.ubahJml=function(d){const mx=sT==='bulanan'?24:30;sJ=Math.min(mx,Math.max(1,sJ+d));if(E('jmlDisp'))E('jmlDisp').value=sJ;if(E('sbJml'))E('sbJml').value=sJ;hitungCO();};
window.hitungCO=function(){
  const tgl=E('tglMasuk')?.value,jam=E('jamMasuk')?.value||'12:00';
  if(E('coDisp')){E('coDisp').textContent='Otomatis dihitung';E('coDisp').style.color='#aaa';}
  if(tgl){const ci=new Date(tgl+'T'+jam+':00'),co=new Date(ci);if(sT==='bulanan')co.setMonth(co.getMonth()+sJ);else co.setDate(co.getDate()+sJ);const s=co.toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'}),j=String(co.getHours()).padStart(2,'0')+':'+String(co.getMinutes()).padStart(2,'0');if(E('coDisp')){E('coDisp').textContent=`${s}, ${j} WIB`;E('coDisp').style.color='#333';}}
  if(sHr>0){const sub=sHr*sJ,lyr={{ $komisiAdmin }},tot=sub+lyr,sat=sT==='bulanan'?'bulan':'hari';if(E('wRing'))E('wRing').style.display='block';if(E('rLabel'))E('rLabel').textContent=`Harga kamar × ${sJ} ${sat}`;if(E('rHarga'))E('rHarga').textContent='Rp '+sub.toLocaleString('id-ID');if(E('rLayanan'))E('rLayanan').textContent='Rp '+lyr.toLocaleString('id-ID');if(E('rTotal'))E('rTotal').textContent='Rp '+tot.toLocaleString('id-ID');}else{if(E('wRing'))E('wRing').style.display='none';}
  const ok=!!(E('tglMasuk')?.value&&E('sbRoomId')?.value&&sHr>0);const b=E('btnBayar');if(b){b.disabled=!ok;b.style.opacity=ok?'1':'.6';}
};
(function(){const it=E('tglMasuk'),ij=E('jamMasuk');if(!it)return;const now=new Date(),utc=now.getTime()+(now.getTimezoneOffset()*60000),jkt=new Date(utc+(7*3600000));const y=jkt.getFullYear(),m=String(jkt.getMonth()+1).padStart(2,'0'),d=String(jkt.getDate()).padStart(2,'0'),h=String(jkt.getHours()).padStart(2,'0'),mn=String(jkt.getMinutes()).padStart(2,'0');it.value=`${y}-${m}-${d}`;it.min=`${y}-${m}-${d}`;if(ij)ij.value=`${h}:${mn}`;})();
E('tglMasuk')?.addEventListener('change',hitungCO);
document.getElementById('rkPrev')?.addEventListener('click',()=>document.getElementById('rkTrack')?.scrollBy({left:-205,behavior:'smooth'}));
document.getElementById('rkNext')?.addEventListener('click',()=>document.getElementById('rkTrack')?.scrollBy({left:205,behavior:'smooth'}));

window.gantiTab=function(el,id){
  document.querySelectorAll('.tab-tempat').forEach(t=>t.classList.remove('active'));
  document.querySelectorAll('.tempat-panel').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  document.getElementById(id)?.classList.add('active');
};

/* ═══ MAP & OVERPASS ═══ */
document.addEventListener('DOMContentLoaded',function(){
  @php $lat=$kost->latitude??-6.2088; $lng=$kost->longitude??106.8456; @endphp
  const KL={{ $lat }},KG={{ $lng }};
  const map=L.map('kostMap').setView([KL,KG],15);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap'}).addTo(map);
  const pin=L.divIcon({html:`<div style="background:#e8401c;color:#fff;border-radius:50% 50% 50% 0;width:32px;height:32px;display:flex;align-items:center;justify-content:center;font-size:.95rem;transform:rotate(-45deg);box-shadow:0 3px 10px rgba(232,64,28,.4);border:2px solid #fff;"><span style="transform:rotate(45deg)">🏠</span></div>`,iconSize:[32,32],iconAnchor:[16,32],popupAnchor:[0,-32],className:''});
  L.marker([KL,KG],{icon:pin}).addTo(map).bindPopup(`<strong>{{ $kost->nama_kost }}</strong><br><small>{{ $kost->alamat }}</small>`).openPopup();

  function jrk(a,b,c,d){const R=6371e3,dL=(c-a)*Math.PI/180,dG=(d-b)*Math.PI/180;const x=Math.sin(dL/2)**2+Math.cos(a*Math.PI/180)*Math.cos(c*Math.PI/180)*Math.sin(dG/2)**2;return R*2*Math.atan2(Math.sqrt(x),Math.sqrt(1-x));}
  function fj(m){return m>=1000?(m/1000).toFixed(1)+' km':Math.round(m)+' m';}

  const iT={bank:'🏦',atm:'🏧',hospital:'🏥',clinic:'🏥',pharmacy:'💊',school:'🏫',university:'🎓',mosque:'🕌',church:'⛪',hotel:'🏨',supermarket:'🛒',convenience:'🏪',default:'📍'};
  const iK={restaurant:'🍽️',fast_food:'🍔',cafe:'☕',food_court:'🍜',bar:'🍺',bakery:'🥐',ice_cream:'🍦',default:'🍽️'};
  const iW={mall:'🏬',department_store:'🏬',cinema:'🎬',theatre:'🎭',theme_park:'🎡',zoo:'🦁',museum:'🏛️',park:'🌳',default:'🛍️'};
  const iR={bus_stop:'🚌',station:'🚉',halt:'🚉',subway_entrance:'🚇',taxi:'🚕',default:'🚌'};

  function gi(tags,m){
    const mp=m==='t'?iT:m==='k'?iK:m==='w'?iW:iR;
    for(const[k,v]of Object.entries(mp)){
      if((tags.amenity&&tags.amenity.includes(k))||(tags.shop&&tags.shop.includes(k))||(tags.railway&&tags.railway.includes(k))||(tags.highway&&tags.highway.includes(k))||(tags.tourism&&tags.tourism.includes(k))||(tags.leisure&&tags.leisure.includes(k)))return v;
    }
    return mp.default;
  }

  function cari(q,eh,el,m,maxD){
    fetch(`https://overpass-api.de/api/interpreter?data=[out:json][timeout:15];(${q});out body;`)
      .then(r=>r.json()).then(data=>{
        el.style.display='none';
        const it=data.elements.filter(e=>e.lat&&e.lon)
          .map(e=>({nama:e.tags.name||e.tags['name:id']||e.tags.amenity||e.tags.shop||e.tags.tourism||'Tempat',jarak:jrk(KL,KG,e.lat,e.lon),tags:e.tags}))
          .filter(e=>e.jarak<=(maxD||3500)).sort((a,b)=>a.jarak-b.jarak).slice(0,10);
        if(!it.length){eh.innerHTML='<div style="font-size:.78rem;color:#aaa;padding:.7rem 0;text-align:center;">Tidak ditemukan</div>';return;}
        eh.innerHTML=it.map(x=>`<div class="tempat-row"><div class="tempat-row-left"><i>${gi(x.tags,m)}</i><span>${x.nama}</span></div><span class="tempat-dist">${fj(x.jarak)}</span></div>`).join('');
      }).catch(()=>{el.style.display='none';eh.innerHTML='<div style="font-size:.78rem;color:#aaa;padding:.7rem 0;text-align:center;">Gagal memuat data.</div>';});
  }

  const R=3000,R2=6000;
  cari(`node["amenity"~"bank|atm|hospital|clinic|pharmacy|school|university|mosque|church|hotel|supermarket|convenience"](around:${R},${KL},${KG});node["shop"~"supermarket|convenience|minimarket"](around:${R},${KL},${KG});`,document.getElementById('tp-hasil'),document.getElementById('tp-loading'),'t',R);
  cari(`node["amenity"~"restaurant|fast_food|cafe|food_court|bar|bakery|ice_cream"](around:${R},${KL},${KG});node["shop"~"bakery|pastry|coffee"](around:${R},${KL},${KG});`,document.getElementById('tp-kuliner-hasil'),document.getElementById('tp-kuliner-loading'),'k',R);
  cari(`way["shop"="mall"](around:${R2},${KL},${KG});way["shop"="department_store"](around:${R2},${KL},${KG});way["shop"="supermarket"](around:${R2},${KL},${KG});node["shop"~"mall|department_store"](around:${R2},${KL},${KG});node["amenity"~"cinema|theatre"](around:${R2},${KL},${KG});way["amenity"~"cinema|theatre"](around:${R2},${KL},${KG});node["tourism"~"attraction|museum|theme_park|zoo|hotel"](around:${R2},${KL},${KG});node["leisure"~"park|stadium|sports_centre"](around:${R2},${KL},${KG});`,document.getElementById('tp-wisata-hasil'),document.getElementById('tp-wisata-loading'),'w',R2);
  cari(`node["highway"="bus_stop"](around:${R},${KL},${KG});node["railway"~"station|halt|subway_entrance"](around:${R},${KL},${KG});node["public_transport"~"stop_position|station"](around:${R},${KL},${KG});node["amenity"="taxi"](around:${R},${KL},${KG});`,document.getElementById('tr-hasil'),document.getElementById('tr-loading'),'r',R);
});
</script>
@endsection