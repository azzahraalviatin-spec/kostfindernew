@extends('layouts.app')

@section('title', 'Temukan Kost Impian di Jawa Timur')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Fraunces:ital,opsz,wght@0,9..144,700;1,9..144,400&display=swap" rel="stylesheet">

<style>
  :root {
    --kf-primary: #e4572e;
    --kf-primary-dark: #c03e1c;
    --kf-primary-light: #ff7a52;
    --kf-dark: #0f1923;
    --kf-muted: #6c768a;
    --kf-soft: #f6f8fc;
    --kf-border: #e7ebf3;
    --kf-accent: #ffb347;
  }

  *, *::before, *::after { box-sizing: border-box; }
  html { scroll-behavior: smooth; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; }
  .welcome-page { background: #fff; overflow-x: hidden; }

  /* ══ HERO ══ */
  .hero {
    position: relative; min-height: 100vh;
    display: flex; align-items: flex-start;
    overflow-x: hidden; overflow-y: visible;
  }
  .hero-bg {
    position: absolute; inset: 0;
    background-image:
      linear-gradient(135deg, rgba(10,18,32,.92) 0%, rgba(15,25,40,.75) 45%, rgba(10,18,32,.55) 100%),
      url('{{ asset("images/hero-kost1.jpg") }}');
    background-size: cover; background-position: center;
    transform: scale(1.04); transition: transform 8s ease-out; overflow: hidden;
  }
  .hero-bg.loaded { transform: scale(1); }
  .hero-mesh { position:absolute;inset:0;background:radial-gradient(ellipse 60% 55% at 80% 30%,rgba(228,87,46,.22) 0%,transparent 65%),radial-gradient(ellipse 40% 60% at 10% 80%,rgba(255,179,71,.12) 0%,transparent 55%);pointer-events:none;z-index:1; }
  .hero-grain { position:absolute;inset:0;opacity:.04;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");pointer-events:none;z-index:1; }
  .hero-content {
    position:relative; z-index:10;
    padding-top:2.5rem; padding-bottom:5rem;
    animation:heroIn .9s cubic-bezier(.22,1,.36,1) both;
  }
  @keyframes heroIn { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }
  .hero-badge { display:inline-flex;align-items:center;gap:.4rem;padding:.38rem .9rem;border-radius:999px;border:1px solid rgba(255,255,255,.22);background:rgba(255,255,255,.08);backdrop-filter:blur(8px);color:#e8d5c8;font-size:.8rem;font-weight:600;letter-spacing:.03em;margin-bottom:1.4rem;animation:heroIn .9s .1s cubic-bezier(.22,1,.36,1) both; }
  .hero-badge span.dot { width:7px;height:7px;border-radius:50%;background:#4ade80;box-shadow:0 0 0 3px rgba(74,222,128,.3);animation:pulse 2s infinite; }
  @keyframes pulse { 0%,100%{box-shadow:0 0 0 3px rgba(74,222,128,.3)} 50%{box-shadow:0 0 0 6px rgba(74,222,128,.15)} }
  .hero-title { font-family:'Fraunces',serif;font-weight:700;font-size:clamp(2.4rem,5.5vw,4.8rem);line-height:1.06;letter-spacing:-.025em;color:#fff;margin-bottom:1rem;animation:heroIn .9s .18s cubic-bezier(.22,1,.36,1) both; }
  .hero-title .accent { color:var(--kf-primary-light);font-style:italic;position:relative; }
  .hero-title .accent::after { content:'';position:absolute;bottom:-4px;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--kf-primary-light),transparent);border-radius:99px; }
  .hero-sub { color:#bdc9e0;font-size:1rem;max-width:580px;line-height:1.75;margin-bottom:2rem;animation:heroIn .9s .26s cubic-bezier(.22,1,.36,1) both; }

  /* ══ SEARCH ══ */
  .search-wrapper { position:relative;max-width:760px;z-index:9999;animation:heroIn .9s .34s cubic-bezier(.22,1,.36,1) both; }
  .search-card { background:rgba(255,255,255,.99);backdrop-filter:blur(16px);border-radius:1.5rem;padding:.95rem 1rem;box-shadow:0 24px 48px rgba(5,10,20,.22),0 0 0 1px rgba(255,255,255,.2); }
  .search-card .form-control { border:0;height:50px;font-size:.95rem;padding-left:0;color:var(--kf-dark);background:transparent; }
  .search-card .form-control:focus { box-shadow:none; }
  .search-card .form-control::placeholder { color:#a0a9bc; }
  .search-divider { width:1px;height:28px;background:#e0e5ef;margin:0 .4rem; }
  .btn-filter { display:flex;align-items:center;gap:.38rem;padding:.58rem .95rem;border-radius:.95rem;border:1px solid #f1d7cb;background:linear-gradient(135deg,#fff8f4 0%,#ffece3 100%);color:#f06432;font-size:.84rem;font-weight:700;white-space:nowrap;cursor:pointer;transition:all .18s; }
  .btn-filter:hover { background:linear-gradient(135deg,#fff2eb 0%,#ffe1d4 100%);border-color:#efb7a2; }
  .btn-cari { height:50px;padding:0 1.6rem;border-radius:1rem;border:0;background:linear-gradient(135deg,#ffd7c7 0%,#ff9b75 100%);color:#31170d;font-weight:800;font-size:.95rem;letter-spacing:.01em;white-space:nowrap;box-shadow:0 10px 24px rgba(228,87,46,.22);transition:all .2s;cursor:pointer; }
  .btn-cari:hover { transform:translateY(-1px);box-shadow:0 14px 28px rgba(228,87,46,.28); }

  /* ══ DROPDOWN dark glassmorphism ══ */
  .hero-dropdown { position:absolute;top:calc(100% + 8px);left:0;right:0;z-index:99999;background:rgba(12,20,35,.88);backdrop-filter:blur(28px) saturate(1.3);-webkit-backdrop-filter:blur(28px) saturate(1.3);border-radius:1.35rem;border:1px solid rgba(255,255,255,.1);box-shadow:0 22px 54px rgba(0,0,0,.5),inset 0 1px 0 rgba(255,255,255,.07);padding:1rem 1rem .95rem;animation:dropIn .2s ease; }
  @keyframes dropIn { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:none} }
  .hero-drop-head { display:flex;align-items:flex-start;gap:.75rem;margin-bottom:1rem; }
  .hero-drop-icon { width:34px;height:34px;border-radius:50%;border:1px solid rgba(228,87,46,.35);background:rgba(228,87,46,.18);display:flex;align-items:center;justify-content:center;color:#ff7a52;flex-shrink:0; }
  .hero-tab-bar { display:flex;gap:3px;border-bottom:1px solid rgba(255,255,255,.12);margin-bottom:.7rem; }
  .hero-chip { display:inline-flex;align-items:center;justify-content:center;padding:.45rem .9rem;border-radius:999px;border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.07);color:#c8d8f0;cursor:pointer;font-size:.76rem;font-weight:600;transition:all .18s ease; }
  .hero-chip:hover { border-color:rgba(228,87,46,.5);color:#fff;background:rgba(228,87,46,.22); }
  .hero-kota-thumb { width:88px;height:56px;border-radius:.75rem;overflow:hidden;background:rgba(255,255,255,.08);margin-bottom:.38rem;border:1.5px solid rgba(255,255,255,.12); }
  .hero-kota-thumb img { width:100%;height:100%;object-fit:cover; }
  .quick-tags { display:flex;flex-wrap:wrap;gap:.45rem;margin-top:.9rem;animation:heroIn .9s .42s cubic-bezier(.22,1,.36,1) both; }
  .qtag { display:inline-flex;align-items:center;gap:.3rem;padding:.3rem .75rem;border-radius:999px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);color:#e0e8f5;font-size:.78rem;font-weight:600;cursor:pointer;backdrop-filter:blur(6px);transition:all .2s; }
  .qtag:hover { background:rgba(255,255,255,.2);color:#fff; }

  /* ══ STATS — HITAM + ORANGE NERAWANG ══ */
  .stats-row { position:relative;z-index:4;margin-top:-3rem; }
  .stats-card {
    /* hitam pekat campur orange gelap, transparan/nerawang */
    background: linear-gradient(135deg,
      rgba(8, 3, 0, 0.78) 0%,
      rgba(35, 12, 2, 0.74) 45%,
      rgba(50, 18, 4, 0.76) 80%,
      rgba(80, 25, 5, 0.72) 100%
    );
    backdrop-filter: blur(28px) saturate(1.6);
    -webkit-backdrop-filter: blur(28px) saturate(1.6);
    border-radius: 1.2rem;
    /* border orange tipis */
    border: 1px solid rgba(228, 87, 46, 0.22);
    box-shadow:
      0 8px 32px rgba(0, 0, 0, 0.50),
      0 0 0 1px rgba(228, 87, 46, 0.08),
      inset 0 1px 0 rgba(255, 140, 80, 0.12),
      inset 0 -1px 0 rgba(0, 0, 0, 0.25);
    padding: 1.5rem 2rem;
  }
  .stat-item { text-align:center; }
  .stat-number {
    font-family:'Fraunces',serif;
    font-size:clamp(1.6rem,2.8vw,2.2rem);
    font-weight:700; color:#ffffff; line-height:1;
    /* glow orange tipis di angka */
    text-shadow: 0 0 22px rgba(228,87,46,.28), 0 2px 8px rgba(0,0,0,.55);
  }
  .stat-number .stat-accent { color: #ff7a52; }
  .stat-label { margin-top:.3rem; color:rgba(255,190,160,.65); font-size:.82rem; font-weight:500; }
  /* divider orange tipis vertikal */
  .stat-divider {
    width: 1px;
    background: linear-gradient(to bottom, transparent, rgba(228,87,46,.30), transparent);
    align-self: stretch;
  }

  /* ══ SECTION UMUM ══ */
  .section-space { padding:5rem 0; }
  .section-title { font-family:'Fraunces',serif;font-weight:700;color:var(--kf-dark);letter-spacing:-.02em;line-height:1.15;margin-bottom:.4rem; }
  .section-sub { color:var(--kf-muted);font-size:.95rem;margin:0; }

  /* ══ PROMO ══ */
  .promo-track { display:flex;gap:1rem;overflow-x:auto;scroll-behavior:smooth;padding-bottom:.5rem;scrollbar-width:none; }
  .promo-track::-webkit-scrollbar { display:none; }
  .promo-card { flex:0 0 clamp(300px,72vw,820px);height:240px;border-radius:1rem;overflow:hidden;background:linear-gradient(135deg,#f0e8e0,#e8d8ce);border:1px solid #ddd5ce;position:relative; }
  .promo-card img { width:100%;height:100%;object-fit:cover; }
  .promo-empty { width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:.9rem;color:#a09080;font-weight:600; }
  .promo-controls { display:flex;align-items:center;gap:.8rem;margin-top:1rem; }
  .promo-nav-btn { width:40px;height:40px;border-radius:50%;border:1.5px solid #ede8e5;background:#fff;color:var(--kf-dark);display:flex;align-items:center;justify-content:center;font-size:1rem;box-shadow:0 4px 12px rgba(228,87,46,.07);transition:all .2s;cursor:pointer; }
  .promo-nav-btn:hover { background:var(--kf-primary);border-color:var(--kf-primary);color:#fff; }
  .promo-see-all { color:var(--kf-dark);font-weight:700;font-size:.9rem;text-decoration:none; }
  .promo-see-all:hover { color:var(--kf-primary); }

  /* ══ KOTA POPULER ══ */
  .kota-section { background:#fff;position:relative;overflow:hidden; }
  .city-card { position:relative;border-radius:1.1rem;overflow:hidden;display:block;height:210px;box-shadow:0 8px 24px rgba(228,87,46,.04);border:1px solid #ede8e5;transition:transform .28s ease,box-shadow .28s ease;text-decoration:none; }
  .city-card:hover { transform:translateY(-5px);box-shadow:0 18px 40px rgba(228,87,46,.15); }
  .city-card img { width:100%;height:100%;object-fit:cover;transition:transform .5s ease; }
  .city-card:hover img { transform:scale(1.06); }
  .city-overlay { position:absolute;inset:0;background:linear-gradient(to top,rgba(8,14,26,.78) 0%,rgba(8,14,26,.42) 55%,rgba(8,14,26,.18) 100%);display:flex;align-items:center;justify-content:center;flex-direction:column;text-align:center;padding:1rem;color:#fff; }
  .city-overlay h6 { margin:0;font-weight:800;font-size:1.05rem;text-shadow:0 2px 8px rgba(0,0,0,.5); }
  .city-overlay p { margin:.3rem 0 0;font-size:.75rem;color:#c8d8ee;background:rgba(255,255,255,.12);backdrop-filter:blur(4px);padding:.18rem .6rem;border-radius:999px; }

  /* ══ REKOMENDASI — 4 KOLOM GRID ══ */
  .reco-section { background:#f7f4f1;padding:3rem 0; }
.reco-track {
  display: flex;        /* ubah ke flex */
  overflow-x: auto;     /* biar bisa geser */
  gap: 1rem;
    gap: 1rem;
    overflow-x: auto;
    scrollbar-width: none;
    padding-bottom: .5rem;
  }
  .reco-track::-webkit-scrollbar { display:none; }
.reco-item {
  flex: 0 0 25%;   /* bikin 4 card muat 1 layar */
}

  .kost-card {
    background:#fff; border-radius:.9rem; overflow:hidden;
    border:1px solid #ebe6e2;
    box-shadow:0 2px 12px rgba(0,0,0,.07);
    transition:transform .22s,box-shadow .22s;
    display:flex; flex-direction:column; height:100%;
    text-decoration:none; color:inherit;
  }
  .kost-card:hover { transform:translateY(-4px);box-shadow:0 12px 30px rgba(0,0,0,.12); }

  /* Gambar rasio 3:2 */
  .kost-img-wrap {
    position:relative; width:100%; padding-top:66%;
    overflow:hidden; background:#f0ebe6; flex-shrink:0;
  }
  .kost-img-wrap img {
    position:absolute;top:0;left:0;
    width:100%;height:100%;object-fit:cover;object-position:center;
    transition:transform .4s;
  }
  .kost-card:hover .kost-img-wrap img { transform:scale(1.04); }

  /* Badge overlay di atas gambar (pojok kiri atas) */
  .kost-img-overlay-badges {
    position:absolute; top:.5rem; left:.5rem;
    display:flex; gap:.3rem; flex-wrap:wrap; z-index:2;
  }
  .kost-badge-overlay {
    background:rgba(15,25,40,.72); backdrop-filter:blur(4px);
    color:#fff; font-size:.63rem; font-weight:700;
    padding:.16rem .45rem; border-radius:.3rem;
  }

  /* Tombol favorit */
  .btn-fav { position:absolute;top:.5rem;right:.5rem;width:30px;height:30px;border-radius:50%;background:rgba(255,255,255,.92);backdrop-filter:blur(6px);border:0;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,.18);transition:transform .2s,box-shadow .2s;z-index:5; }
  .btn-fav:hover { transform:scale(1.18);box-shadow:0 4px 14px rgba(232,64,28,.3); }
  .btn-fav.liked { background:#fff0ee; }
  .btn-fav i { font-size:.82rem;transition:transform .25s cubic-bezier(.34,1.56,.64,1),color .15s; }
  .btn-fav.pop i { transform:scale(1.5); }

  /* Body card */
  .kost-body { padding:.8rem .85rem .9rem;display:flex;flex-direction:column;gap:.22rem;flex:1; }

  /* FIX: Baris tipe + rating langsung di bawah gambar */
  .kost-meta-row {
    display:flex; align-items:center; gap:.4rem;
    flex-wrap:wrap; margin-bottom:.15rem;
  }
  .kost-badge-tipe {
    display:inline-flex; align-items:center;
    background:rgba(228,87,46,.1); color:var(--kf-primary);
    font-size:.65rem; font-weight:800;
    padding:.15rem .5rem; border-radius:.35rem; white-space:nowrap;
  }
  .kost-badge-rating {
    display:inline-flex; align-items:center; gap:.2rem;
    font-size:.68rem; font-weight:700; color:#f59e0b;
    margin-left:auto;
  }

  .kost-name {
    font-weight:700; font-size:.88rem; color:#1a1a1a;
    line-height:1.35;
    overflow:hidden; display:-webkit-box;
    -webkit-line-clamp:2; -webkit-box-orient:vertical;
  }

  /* FIX: Alamat lengkap — tampil 2 baris */
  .kost-alamat {
    display:flex; align-items:flex-start; gap:.22rem;
    font-size:.72rem; color:#6c768a; line-height:1.45;
    overflow:hidden; display:-webkit-box;
    -webkit-line-clamp:2; -webkit-box-orient:vertical;
  }
  .kost-alamat-icon { color:var(--kf-primary); flex-shrink:0; margin-top:.06rem; font-size:.66rem; }

  /* Fasilitas 1 baris */
  .kost-fas-row {
    font-size:.7rem; color:#8a9ab0; line-height:1.5;
    overflow:hidden; display:-webkit-box;
    -webkit-line-clamp:1; -webkit-box-orient:vertical;
  }

  /* Harga */
  .kost-price-row { margin-top:auto; padding-top:.5rem; border-top:1px solid #f2ede8; }
  .kost-price-main { font-size:.95rem; font-weight:800; color:var(--kf-primary); }
  .kost-price-harian { font-size:.85rem; font-weight:700; color:#f59e0b; }
  .kost-price-unit { font-size:.68rem; color:#9ca3af; font-weight:400; }

  .reco-nav-btn { width:40px;height:40px;border-radius:50%;border:1.5px solid #ede8e5;background:#fff;color:var(--kf-dark);display:flex;align-items:center;justify-content:center;font-size:1rem;box-shadow:0 4px 12px rgba(228,87,46,.07);transition:all .2s;cursor:pointer; }
  .reco-nav-btn:hover { background:var(--kf-primary);border-color:var(--kf-primary);color:#fff; }

  /* Responsive grid rekomendasi */
  @media(max-width:991px){ .reco-track { grid-template-columns:repeat(2,1fr); } }
  @media(max-width:575px){
    .reco-track { display:flex; }
    .reco-item { flex:0 0 185px; }
  }

  /* ══ OWNER BANNER ══ */
  .owner-section { background:#fff; }
  .owner-banner { border-radius:1.6rem;overflow:hidden;position:relative;padding:4rem 3.5rem;background:linear-gradient(125deg,#1c0800 0%,#4a1500 30%,#8b2f00 62%,#e4572e 100%);box-shadow:0 24px 64px rgba(228,87,46,.3); }
  .owner-banner::before { content:'';position:absolute;top:-100px;right:-100px;width:450px;height:450px;border-radius:50%;background:radial-gradient(circle,rgba(255,120,50,.3) 0%,transparent 65%);pointer-events:none; }
  .owner-banner::after { content:'';position:absolute;bottom:-80px;left:25%;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(255,180,70,.12) 0%,transparent 65%);pointer-events:none; }
  .owner-banner-has-img { background-image:linear-gradient(120deg,rgba(20,5,0,.97) 0%,rgba(55,15,0,.93) 38%,rgba(110,35,0,.80) 62%,rgba(200,65,20,.5) 85%,transparent 100%),url('{{ asset("images/banner/owner-kost-banner.jpg") }}');background-size:cover;background-position:center right; }
  .owner-content { position:relative;z-index:2;max-width:560px; }
  .owner-chip { display:inline-flex;align-items:center;gap:.4rem;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.22);color:#ffd4bf;font-size:.75rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;padding:.32rem .85rem;border-radius:999px;margin-bottom:1.1rem;backdrop-filter:blur(8px); }
  .owner-title { font-family:'Fraunces',serif;font-weight:700;color:#ffffff;font-size:clamp(1.7rem,3.5vw,2.8rem);line-height:1.12;letter-spacing:-.02em;margin-bottom:.8rem;text-shadow:0 2px 16px rgba(0,0,0,.5); }
  .owner-sub { color:rgba(255,220,200,.82);font-size:.95rem;line-height:1.7;margin-bottom:1.8rem; }
  .btn-owner { display:inline-flex;align-items:center;gap:.5rem;background:#ffffff;color:var(--kf-primary-dark);font-weight:800;font-size:.95rem;padding:.9rem 1.8rem;border-radius:.9rem;text-decoration:none;box-shadow:0 8px 28px rgba(0,0,0,.28);transition:all .2s; }
  .btn-owner:hover { transform:translateY(-2px);color:var(--kf-primary);background:#fff8f4;box-shadow:0 14px 36px rgba(0,0,0,.32); }

  /* ══ FITUR ══ */
  .fitur-section { background:linear-gradient(160deg,#fdf6f2 0%,#fef3ee 50%,#fdf6f2 100%);position:relative;overflow:hidden; }
  .fitur-section::before { content:'';position:absolute;top:-120px;right:-80px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(228,87,46,.06) 0%,transparent 65%);pointer-events:none; }
  .fitur-section::after { content:'';position:absolute;bottom:-80px;left:-60px;width:320px;height:320px;border-radius:50%;background:radial-gradient(circle,rgba(255,179,71,.05) 0%,transparent 65%);pointer-events:none; }
  .fitur-inner { position:relative;z-index:2; }
  .fitur-desc-box { padding-right:2.5rem; }
  .fitur-platform-badge { display:inline-flex;align-items:center;gap:.4rem;padding:.32rem .85rem;border-radius:999px;background:rgba(228,87,46,.08);border:1px solid rgba(228,87,46,.2);color:#c03e1c;font-size:.75rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;margin-bottom:1.1rem; }
  .fitur-main-title { font-family:'Fraunces',serif;font-weight:700;color:#0f1923;font-size:clamp(1.7rem,3vw,2.5rem);line-height:1.15;letter-spacing:-.02em;margin-bottom:.9rem; }
  .fitur-main-title span { color:var(--kf-primary);font-style:italic; }
  .fitur-main-desc { color:#6c768a;font-size:.92rem;line-height:1.85;margin-bottom:1.8rem; }

  .fitur-cards-wrap { max-height:0;overflow:hidden;transition:max-height .55s cubic-bezier(.4,0,.2,1),opacity .4s ease;opacity:0; }
  .fitur-cards-wrap.open { max-height:1800px;opacity:1; }
  .fitur-cards-grid { display:grid;grid-template-columns:1fr 1fr;gap:1rem;padding-top:1.5rem; }
  @media(max-width:600px){.fitur-cards-grid{grid-template-columns:1fr;}}
  .fitur-card { border-radius:1rem;padding:1.15rem 1.25rem;display:flex;gap:.9rem;align-items:flex-start;transition:transform .22s,box-shadow .22s; }
  .fc-orange{background:linear-gradient(135deg,#fff4ef,#ffe8dc);border:1px solid rgba(228,87,46,.18);}
  .fc-teal{background:linear-gradient(135deg,#f0fdf8,#dcfdf2);border:1px solid rgba(16,185,129,.18);}
  .fc-amber{background:linear-gradient(135deg,#fffbeb,#fef3c7);border:1px solid rgba(245,158,11,.2);}
  .fc-rose{background:linear-gradient(135deg,#fff5f5,#ffe4e4);border:1px solid rgba(239,68,68,.15);}
  .fc-blue{background:linear-gradient(135deg,#eff8ff,#dbeafe);border:1px solid rgba(59,130,246,.15);}
  .fc-purple{background:linear-gradient(135deg,#faf5ff,#ede9fe);border:1px solid rgba(139,92,246,.15);}
  .fitur-card:hover { transform:translateY(-3px);box-shadow:0 10px 28px rgba(0,0,0,.09); }
  .fitur-card-icon { width:42px;height:42px;border-radius:.85rem;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.05rem;color:#fff; }
  .fci1{background:linear-gradient(135deg,#ff7a52,#e4572e);}
  .fci2{background:linear-gradient(135deg,#34d399,#059669);}
  .fci3{background:linear-gradient(135deg,#fbbf24,#d97706);}
  .fci4{background:linear-gradient(135deg,#f87171,#ef4444);}
  .fci5{background:linear-gradient(135deg,#60a5fa,#3b82f6);}
  .fci6{background:linear-gradient(135deg,#a78bfa,#7c3aed);}
  .fitur-card-title { font-size:.88rem;font-weight:800;color:#0f1923;margin-bottom:.25rem; }
  .fitur-card-desc { font-size:.8rem;color:#6c768a;line-height:1.7;margin:0; }
  .fitur-layout { display:grid;grid-template-columns:1fr 1.6fr;gap:3rem;align-items:start; }
  @media(max-width:991px){.fitur-layout{grid-template-columns:1fr;gap:2rem;}.fitur-desc-box{padding-right:0;}}

  /* ══ FINAL CTA ══ */
  .final-cta-section { background:#f8f5f2;padding:5rem 0; }
  .final-cta-box { position:relative;overflow:hidden;border-radius:1.6rem;padding:3.5rem 2rem;text-align:center;background:linear-gradient(145deg,#1c0600 0%,#5a1800 35%,#b84020 65%,#e4572e 100%);box-shadow:0 20px 60px rgba(228,87,46,.35); }
  .final-cta-box::before { content:'';position:absolute;top:-80px;right:-80px;width:350px;height:350px;border-radius:50%;background:radial-gradient(circle,rgba(255,140,80,.25) 0%,transparent 65%);pointer-events:none; }
  .final-cta-box::after { content:'';position:absolute;bottom:-60px;left:-40px;width:260px;height:260px;border-radius:50%;background:radial-gradient(circle,rgba(255,200,100,.12) 0%,transparent 65%);pointer-events:none; }
  .final-cta-title { font-family:'Fraunces',serif;font-size:clamp(1.8rem,3.8vw,3rem);color:#ffffff;font-weight:700;letter-spacing:-.02em;margin-bottom:.8rem;position:relative;text-shadow:0 2px 16px rgba(0,0,0,.4); }
  .final-cta-sub { max-width:620px;margin:0 auto 2rem;color:rgba(255,220,200,.85);font-size:.96rem;line-height:1.8;position:relative; }
  .btn-final-cta { display:inline-flex;align-items:center;gap:.6rem;background:#ffffff;color:var(--kf-primary-dark);font-weight:800;font-size:.98rem;padding:.95rem 2rem;border-radius:.9rem;text-decoration:none;box-shadow:0 10px 30px rgba(0,0,0,.25);transition:all .22s ease;position:relative; }
  .btn-final-cta:hover { transform:translateY(-2px);color:var(--kf-primary);background:#fff8f4;box-shadow:0 16px 38px rgba(0,0,0,.3); }

  /* ══ FILTER CHIP ══ */
  .f-chip { display:inline-flex;align-items:center;gap:.3rem;padding:.4rem .9rem;border-radius:999px;border:1.5px solid #ede8e5;background:#fff;font-size:.8rem;font-weight:600;color:#555;cursor:pointer;transition:all .18s;user-select:none; }
  .f-chip:hover { border-color:#ffbeaa;color:#e4572e;background:#fff8f4; }
  .f-chip.aktif { background:#fff5f2;border-color:#e4572e;color:#e4572e; }

  /* ══ MODAL FILTER ══ */
  .modal-filter .modal-content { border:0;border-radius:1.4rem;box-shadow:0 32px 80px rgba(10,20,50,.22);overflow:hidden; }
  .modal-filter-header { padding:1.3rem 1.5rem 1rem;border-bottom:1px solid #f5ede8;display:flex;align-items:center;justify-content:space-between; }
  .modal-filter-title { font-family:'Fraunces',serif;font-weight:700;font-size:1.1rem;color:var(--kf-dark);margin:0; }
  .modal-filter-body { padding:1.3rem 1.5rem;max-height:68vh;overflow-y:auto; }
  .modal-filter-body::-webkit-scrollbar { width:5px; }
  .modal-filter-body::-webkit-scrollbar-thumb { background:#e8d5cb;border-radius:99px; }
  .filter-group { margin-bottom:1.4rem; }
  .filter-group-label { font-size:.7rem;font-weight:800;color:var(--kf-dark);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.7rem;display:flex;align-items:center;gap:.4rem; }
  .filter-group-label::after { content:'';flex:1;height:1px;background:#f0e8e3; }
  .modal-filter-footer { padding:1rem 1.5rem;border-top:1px solid #f5ede8;display:flex;gap:.75rem; }
  .btn-reset-filter { flex:1;padding:.7rem;border-radius:.9rem;border:1.5px solid #ede8e5;background:#fff;font-size:.85rem;font-weight:700;color:#555;cursor:pointer;transition:all .18s; }
  .btn-reset-filter:hover { border-color:#d4cdc9;color:#333; }
  .btn-apply-filter { flex:2.2;padding:.7rem;border-radius:.9rem;border:0;background:linear-gradient(135deg,var(--kf-primary),var(--kf-primary-dark));color:#fff;font-size:.88rem;font-weight:800;cursor:pointer;box-shadow:0 8px 22px rgba(228,87,46,.28);transition:all .2s; }
  .btn-apply-filter:hover { transform:translateY(-1px);box-shadow:0 12px 28px rgba(228,87,46,.36); }

  /* ══ REVEAL ══ */
  .reveal { opacity:0;transform:translateY(24px);transition:opacity .65s ease,transform .65s ease; }
  .reveal.visible { opacity:1;transform:none; }
  .reveal-delay-1{transition-delay:.1s}.reveal-delay-2{transition-delay:.2s}.reveal-delay-3{transition-delay:.3s}

  /* ══ BOTTOM NAV ══ */
  .bottom-nav { display:none;position:fixed;bottom:0;left:0;right:0;height:62px;background:#fff;border-top:1px solid #ede8e5;box-shadow:0 -4px 20px rgba(0,0,0,.08);z-index:9999;align-items:center;justify-content:space-around; }
  .bottom-nav-item { display:flex;flex-direction:column;align-items:center;gap:3px;cursor:pointer;color:#9ca3af;font-size:.65rem;font-weight:600;text-decoration:none;transition:color .18s;flex:1;position:relative;padding:6px 0; }
  .bottom-nav-item i { font-size:1.35rem;transition:transform .2s; }
  .bottom-nav-item.active { color:var(--kf-primary); }
  .bottom-nav-item.active i { transform:translateY(-2px); }
  .bnav-dot { position:absolute;top:4px;right:calc(50% - 14px);width:8px;height:8px;border-radius:50%;background:var(--kf-primary);border:2px solid #fff; }

  /* ══ RESPONSIVE ══ */
  @media(max-width:991px){
    .hero-content{padding-top:2rem;padding-bottom:4rem;}
    .owner-banner{padding:2.5rem 2rem;}
  }
  @media(max-width:767px){
    .bottom-nav{display:flex;}
    body{padding-bottom:62px;}
    .hero{min-height:100svh;}
    .hero-content{padding-top:2rem;padding-bottom:3rem;}
    .hero-title{font-size:1.8rem !important;}
    .hero-sub{font-size:.85rem;}
    .search-card{border-radius:1rem;padding:.7rem .8rem;}
    .btn-cari{width:100%;height:46px;margin-top:.4rem;border-radius:.75rem !important;}
    .stats-row{margin-top:-1.5rem;}
    .stats-card{padding:.8rem;}
    .stat-number{font-size:1.2rem;}
    .stat-label{font-size:.72rem;}
    .section-space{padding:2.5rem 0;}
    .city-card{height:140px;}
    .owner-banner{padding:1.8rem 1.1rem;}
    .owner-title{font-size:1.35rem;}
    .final-cta-box{padding:2.2rem 1rem;}
    .fitur-cards-grid{grid-template-columns:1fr;}
    .fitur-layout{grid-template-columns:1fr;gap:2rem;}
    .fitur-desc-box{padding-right:0;}
  }
</style>
@endsection

@section('content')
<div class="welcome-page">

{{-- ══ HERO ══ --}}
<section class="hero">
  <div class="hero-bg" id="heroBg"></div>
  <div class="hero-mesh"></div>
  <div class="hero-grain"></div>
  <div class="container hero-content">
    <div class="row">
      <div class="col-lg-9 col-xl-8">
        <div class="hero-badge"><span class="dot"></span> Platform Kost Fokus Jawa Timur</div>
        <h1 class="hero-title">Temukan Kost yang<br><span class="accent">Nyaman &amp; Cocok</span> untukmu</h1>
        <p class="hero-sub">Cari kost putra, putri, atau campur dengan lokasi strategis, harga transparan, dan informasi yang lebih jelas — biar kamu nggak bingung sebelum memilih.</p>

        <div class="search-wrapper" id="searchWrapper">
          <div class="search-card">
            <div class="d-flex align-items-center gap-2 flex-wrap flex-md-nowrap">
              <i class="bi bi-search" style="color:#a0a9bc;font-size:1.1rem;flex-shrink:0;margin-left:.3rem;"></i>
              <input type="text" id="heroSearchInput" class="form-control flex-grow-1"
                placeholder="Cari kota, kampus, atau nama jalan..."
                autocomplete="off" onfocus="showHeroDropdown()">
              <div class="search-divider d-none d-md-block"></div>
              <button class="btn-filter d-none d-md-flex" data-bs-toggle="modal" data-bs-target="#modalFilter">
                <i class="bi bi-sliders"></i> Filter Kost
              </button>
              <button class="btn-cari" onclick="doSearch()">
                <i class="bi bi-search me-1"></i> Cari Kost
              </button>
            </div>
          </div>

          <div id="heroDropdown" class="hero-dropdown d-none">
            <div class="hero-drop-head">
              <div class="hero-drop-icon"><i class="bi bi-house-door"></i></div>
              <div>
                <div style="font-size:.82rem;font-weight:800;color:#ffffff;">Lokasi</div>
                <div style="font-size:.8rem;color:#9db4cc;line-height:1.5;">Cari nama properti / alamat / daerah / kota</div>
              </div>
            </div>
            <div class="d-flex gap-2 overflow-auto pb-2 mb-3" style="scrollbar-width:none;">
              @foreach(['Surabaya'=>'photo-1555899434-94d1368aa7af','Malang'=>'photo-1558618666-fcd25c85cd64','Sidoarjo'=>'photo-1486406146926-c627a92ad1ab','Gresik'=>'photo-1504280390367-361c6d9f38f4','Jember'=>'photo-1500534314209-a25ddb2bd429'] as $c=>$photo)
              <div class="text-center flex-shrink-0" style="cursor:pointer;" onclick="setHeroSearch('{{ $c }}')">
                <div class="hero-kota-thumb">
                  <img src="{{ asset('images/kota/'.strtolower($c).'.jpg') }}"
                       onerror="this.src='https://images.unsplash.com/{{ $photo }}?w=88&h=56&fit=crop'"
                       alt="{{ $c }}" loading="lazy">
                </div>
                <div style="font-size:.74rem;font-weight:700;color:#e0e8f5;">{{ $c }}</div>
              </div>
              @endforeach
            </div>
            <div class="hero-tab-bar">
              <button id="tabBtnDaerah" class="btn btn-link p-0 pb-2 fw-bold text-decoration-none"
                style="color:var(--kf-primary-light);border-bottom:2px solid var(--kf-primary-light);font-size:.84rem;background:transparent;"
                onclick="switchHeroTab('daerah',this)">Daerah</button>
              <button id="tabBtnKampus" class="btn btn-link p-0 pb-2 fw-medium text-decoration-none"
                style="color:rgba(255,255,255,.45);font-size:.84rem;background:transparent;"
                onclick="switchHeroTab('kampus',this)">Kampus</button>
            </div>
            <div id="heroTabDaerah" class="d-flex flex-wrap gap-2">
              @foreach(['Surabaya','Malang','Sidoarjo','Gresik','Kediri','Jember','Banyuwangi','Mojokerto','Pasuruan'] as $d)
              <span class="hero-chip" onclick="setHeroSearch('{{ $d }}')">{{ $d }}</span>
              @endforeach
            </div>
            <div id="heroTabKampus" class="d-none flex-wrap gap-2">
              @foreach(['ITS Surabaya','UNAIR','UB Malang','UIN Malang','UNEJ Jember','UNESA','UNITOMO','UPN Veteran'] as $k)
              <span class="hero-chip" onclick="setHeroSearch('{{ $k }}')">{{ $k }}</span>
              @endforeach
            </div>
          </div>
        </div>

        <div class="quick-tags">
          <span style="color:#8090a8;font-size:.78rem;font-weight:600;align-self:center;">Populer:</span>
          @foreach(['Kos Malang','Kos ITS','Kos Surabaya','Kos Jember'] as $qt)
          <div class="qtag" onclick="setHeroSearch('{{ $qt }}')">{{ $qt }}</div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ══ STATS hitam + orange nerawang ══ --}}
<div class="container stats-row reveal">
  <div class="stats-card">
    <div class="d-flex justify-content-around align-items-center flex-wrap gap-3">
      <div class="stat-item">
        <div class="stat-number">{{ number_format($stats['total_kost']) }}<span class="stat-accent">+</span></div>
        <div class="stat-label">Unit Kost Aktif</div>
      </div>
      <div class="stat-divider d-none d-md-block"></div>
      <div class="stat-item">
        <div class="stat-number">{{ number_format($stats['total_kamar']) }}<span class="stat-accent">+</span></div>
        <div class="stat-label">Total Kamar Tersedia</div>
      </div>
      <div class="stat-divider d-none d-md-block"></div>
      <div class="stat-item">
        <div class="stat-number">{{ $stats['total_kota'] }}</div>
        <div class="stat-label">Kota Besar Jatim</div>
      </div>
      <div class="stat-divider d-none d-md-block"></div>
      <div class="stat-item">
        <div class="stat-number">{{ number_format($stats['avg_rating'],1) }}<span class="stat-accent">/5</span></div>
        <div class="stat-label">Rating Pengguna</div>
      </div>
    </div>
  </div>
</div>

{{-- ══ PROMO ══ --}}
<section class="section-space pb-3" style="background:#fff;">
  <div class="container reveal">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
      <div>
        <h3 class="section-title" style="font-size:clamp(1.5rem,2.8vw,2rem);">Promo &amp; Info Menarik</h3>
        <p class="section-sub">Penawaran eksklusif dan info kost yang lagi banyak dicari.</p>
      </div>
      <div class="promo-controls">
        <button type="button" id="promoPrev" class="promo-nav-btn"><i class="bi bi-chevron-left"></i></button>
        <a href="#" class="promo-see-all">Lihat semua</a>
        <button type="button" id="promoNext" class="promo-nav-btn"><i class="bi bi-chevron-right"></i></button>
      </div>
    </div>
    <div class="promo-track" id="promoTrack" style="cursor:grab;">
      <article class="promo-card" onclick="window.location.href='{{ route('carikos') }}'">
        <img src="{{ asset('images/promo/promo-1.png') }}" alt="Promo 1">
      </article>
      <article class="promo-card" onclick="window.location.href='{{ route('carikos') }}'">
        <img src="{{ asset('images/promo/promo-2.png') }}" alt="Promo 2">
      </article>
      <article class="promo-card" onclick="window.location.href='{{ route('carikos') }}'">
        <img src="{{ asset('images/promo/promo-3.png') }}" alt="Promo 3">
      </article>
      <article class="promo-card" onclick="window.location.href='{{ route('carikos') }}'">
        <img src="{{ asset('images/promo/Temukan kost nyaman di Jawa Timur.png') }}" alt="Promo 4">
      </article>
    </div>
  </div>
</section>

{{-- ══ KOTA POPULER ══ --}}
<section class="section-space kota-section" id="kota-populer">
  <div class="container">
    <div class="section-head reveal mb-4">
      <h3 class="section-title" style="font-size:clamp(1.5rem,2.8vw,2rem);">Kota Populer di Jawa Timur</h3>
      <p class="section-sub">Area kost favorit dekat kampus, kantor, dan pusat aktivitas.</p>
    </div>
    <div class="row g-3">
      @foreach($kotaList as $kota => $info)
        @php $jumlah = $jumlahPerKota[$kota] ?? 0; @endphp
        <div class="col-12 col-sm-6 col-md-4 reveal reveal-delay-{{ ($loop->index % 3) + 1 }}">
          <a href="{{ route('carikos', ['kota' => $kota]) }}" class="city-card">
            <img src="{{ $info['img'] }}" loading="lazy"
                 onerror="this.onerror=null;this.src='{{ $info['fallback'] }}';"
                 alt="{{ $info['label'] }}">
            <div class="city-overlay">
              <div>
                <h6>{{ $info['label'] }}</h6>
                <p>{{ $jumlah > 0 ? $jumlah.' kost tersedia' : 'Segera hadir' }}</p>
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══ REKOMENDASI — 4 kolom, alamat lengkap, tipe + rating ══ --}}
<section class="reco-section">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3 reveal">
      <div>
        <h3 class="section-title" style="font-size:clamp(1.5rem,2.8vw,2rem);">Rekomendasi Kamar Tersedia</h3>
        <p class="section-sub">Kamar-kamar terbaik dengan fasilitas lengkap dan harga transparan.</p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="{{ route('carikos') }}" style="color:var(--kf-dark);font-weight:700;font-size:.88rem;text-decoration:none;">Lihat semua</a>
        <button type="button" id="recoPrev" class="reco-nav-btn"><i class="bi bi-chevron-left"></i></button>
        <button type="button" id="recoNext" class="reco-nav-btn"><i class="bi bi-chevron-right"></i></button>
      </div>
    </div>

    <div class="reco-track" id="recoTrack">
      @forelse($rooms as $room)
      @php
        $kost      = $room->kost;
        $fotoKamar = $room->mainImage?->foto_path;
        $fasilitas = collect($room->fasilitas ?? [])->take(4);
        $isFav     = in_array($kost->id_kost, $favoritIds);
        $rating    = $room->kost->reviews_avg_rating ?? 0;
        /* Alamat lengkap: gabung alamat + kecamatan + kota jika ada */
        $alamatLengkap = collect([
          $kost->alamat,
          $kost->kecamatan ?? null,
          $kost->kota ?? null,
        ])->filter()->implode(', ');
        if(!$alamatLengkap) $alamatLengkap = $kost->kota ?? '-';
      @endphp
      <div class="reco-item">
        <a href="{{ route('kost.show', $kost->id_kost) }}" class="kost-card">

          {{-- Gambar --}}
          <div class="kost-img-wrap">
            @if($fotoKamar)
              <img src="{{ asset('storage/'.$fotoKamar) }}" alt="{{ $kost->nama_kost }}" loading="lazy">
            @elseif($kost->foto_utama)
              <img src="{{ asset('storage/'.$kost->foto_utama) }}" alt="{{ $kost->nama_kost }}" loading="lazy">
            @else
              <div style="position:absolute;inset:0;background:linear-gradient(135deg,#fff0eb,#ffddd0);display:flex;align-items:center;justify-content:center;font-size:2.5rem;">🛏️</div>
            @endif

            {{-- Favorit --}}
            <button class="btn-fav {{ $isFav ? 'liked' : '' }}" data-kost="{{ $kost->id_kost }}"
              onclick="event.preventDefault();toggleFav(this)"
              title="{{ $isFav ? 'Hapus dari favorit' : 'Tambah ke favorit' }}">
              <i class="bi bi-heart{{ $isFav ? '-fill' : '' }}" style="color:{{ $isFav ? '#e8401c' : '#bbb' }};"></i>
            </button>
          </div>

          {{-- Body --}}
          <div class="kost-body">

            {{-- FIX: Baris TIPE + RATING langsung di bawah gambar --}}
            <div class="kost-meta-row">
              <span class="kost-badge-tipe">{{ $kost->tipe_kost ?? 'Campur' }}</span>
              @if($rating > 0)
              <span class="kost-badge-rating">
                <i class="bi bi-star-fill" style="font-size:.6rem;"></i>
                {{ number_format($rating,1) }}
              </span>
              @endif
            </div>

            {{-- Nama kost --}}
            <div class="kost-name">{{ $kost->nama_kost }}</div>

            {{-- FIX: Alamat LENGKAP — tampil 2 baris --}}
            <div style="display:flex;align-items:flex-start;gap:.22rem;font-size:.72rem;color:#6c768a;line-height:1.45;overflow:hidden;">
              <i class="bi bi-geo-alt-fill" style="color:var(--kf-primary);flex-shrink:0;margin-top:.1rem;font-size:.66rem;"></i>
              <span style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $alamatLengkap }}</span>
            </div>

            {{-- Fasilitas --}}
            @if($fasilitas->count() > 0)
            <div class="kost-fas-row">{{ $fasilitas->implode(' · ') }}</div>
            @endif

            {{-- Harga --}}
            <div class="kost-price-row">
              @if($room->aktif_bulanan && $room->harga_per_bulan > 0)
              <div>
                <span class="kost-price-main">Rp {{ number_format($room->harga_per_bulan,0,',','.') }}</span>
                <span class="kost-price-unit">/bulan</span>
              </div>
              @endif
              @if($room->aktif_harian && $room->harga_harian > 0)
              <div>
                <span class="kost-price-harian">Rp {{ number_format($room->harga_harian,0,',','.') }}</span>
                <span class="kost-price-unit">/hari</span>
              </div>
              @endif
            </div>

          </div>
        </a>
      </div>
      @empty
      <div class="p-4 text-muted" style="font-size:.88rem;grid-column:span 4;">Belum ada kamar tersedia saat ini.</div>
      @endforelse
    </div>
  </div>
</section>

{{-- ══ OWNER BANNER ══ --}}
<section class="owner-section section-space">
  <div class="container reveal">
    <div class="owner-banner owner-banner-has-img">
      <div class="owner-content">
        <div class="owner-chip"><i class="bi bi-building"></i> Untuk Pemilik Kost</div>
        <h3 class="owner-title">Daftarkan Kost Anda &amp; Jangkau Lebih Banyak Calon Penghuni</h3>
        <p class="owner-sub">Tampilkan properti Anda secara lebih profesional dan bantu pencari kost menemukan tempat tinggal yang tepat.</p>
        <a href="{{ route('owner.landing') }}" class="btn-owner">
          Pelajari Lebih Lanjut <i class="bi bi-arrow-right-short" style="font-size:1.2rem;"></i>
        </a>
      </div>
    </div>
  </div>
</section>

{{-- ══ FITUR ══ --}}
<section class="fitur-section section-space">
  <div class="container fitur-inner">
    <div class="fitur-layout reveal">
      <div class="fitur-desc-box">
        <div class="fitur-platform-badge"><i class="bi bi-stars"></i> Keunggulan Platform</div>
        <h3 class="fitur-main-title">Kenapa Cari Kost <span>Lebih Gampang</span> di Sini?</h3>
        <p class="fitur-main-desc">KostFinder dirancang khusus untuk pencari kost di Jawa Timur. Semua informasi tersaji lengkap — harga, fasilitas, foto, dan lokasi — di satu tempat, tanpa perlu tanya sana-sini.</p>
      </div>
      <div>
        <div class="fitur-cards-wrap open" id="fiturList">
          <div class="fitur-cards-grid">
            @foreach([
              ['icon'=>'bi bi-search',      'cls'=>'fci1','fc'=>'fc-orange','title'=>'Pencarian Kost',          'desc'=>'Cari kost berdasarkan nama daerah, kampus, atau nama jalan di seluruh Jawa Timur dengan cepat.'],
              ['icon'=>'bi bi-sliders',     'cls'=>'fci2','fc'=>'fc-teal',  'title'=>'Filter Canggih',           'desc'=>'Filter berdasarkan fasilitas, tipe kost, harga, dan durasi sewa harian atau bulanan.'],
              ['icon'=>'bi bi-card-list',   'cls'=>'fci3','fc'=>'fc-amber', 'title'=>'Info Lengkap & Transparan','desc'=>'Harga, foto, fasilitas, lokasi, dan ketersediaan kamar tampil real-time di setiap listing.'],
              ['icon'=>'bi bi-heart',       'cls'=>'fci4','fc'=>'fc-rose',  'title'=>'Simpan Favorit',           'desc'=>'Simpan kost ke daftar favorit dan bandingkan kapan saja sebelum kamu memutuskan.'],
              ['icon'=>'bi bi-telephone',   'cls'=>'fci5','fc'=>'fc-blue',  'title'=>'Hubungi Pemilik Langsung', 'desc'=>'Kontak pemilik kost tersedia di halaman detail — survey tanpa perantara.'],
              ['icon'=>'bi bi-building-add','cls'=>'fci6','fc'=>'fc-purple','title'=>'Daftarkan Kost Milikmu',  'desc'=>'Pemilik kost daftar gratis dan tampilkan listing secara profesional.'],
            ] as $item)
            <div class="fitur-card {{ $item['fc'] }}">
              <div class="fitur-card-icon {{ $item['cls'] }}"><i class="{{ $item['icon'] }}"></i></div>
              <div>
                <div class="fitur-card-title">{{ $item['title'] }}</div>
                <p class="fitur-card-desc">{{ $item['desc'] }}</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ══ FINAL CTA ══ --}}
<section class="final-cta-section">
  <div class="container reveal">
    <div class="final-cta-box">
      <h3 class="final-cta-title">Siap Menemukan Kost yang Cocok?</h3>
      <p class="final-cta-sub">Jelajahi berbagai pilihan kost dengan informasi yang lebih jelas, tampilan yang nyaman, dan proses pencarian yang lebih praktis.</p>
      <a href="{{ route('carikos') }}" class="btn-final-cta">
        <i class="bi bi-search"></i> Cari Kost Sekarang
      </a>
    </div>
  </div>
</section>

{{-- ══ MODAL FILTER ══ --}}
<div class="modal fade modal-filter" id="modalFilter" tabindex="-1" aria-labelledby="modalFilterLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
    <div class="modal-content">
      <div class="modal-filter-header">
        <div style="display:flex;align-items:center;gap:.5rem;">
          <i class="bi bi-sliders" style="color:var(--kf-primary);font-size:1rem;"></i>
          <p class="modal-filter-title" id="modalFilterLabel">Filter Kost</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-filter-body">
        <div class="filter-group">
          <div class="filter-group-label">Jenis Sewa</div>
          <div class="d-flex gap-2 flex-wrap">
            @foreach(['Semua'=>'','Bulanan'=>'bulanan','Tahunan'=>'tahunan','Harian'=>'harian'] as $label=>$val)
            <div class="f-chip {{ $label==='Semua'?'aktif':'' }}" data-group="sewa" data-val="{{ $val }}" onclick="pilihChip(this,'sewa')">{{ $label }}</div>
            @endforeach
          </div>
        </div>
        <div class="filter-group">
          <div class="filter-group-label">Tipe Penghuni</div>
          <div class="d-flex gap-2 flex-wrap">
            @foreach(['Semua'=>'','Putra'=>'Putra','Putri'=>'Putri','Campur'=>'Campur'] as $label=>$val)
            <div class="f-chip {{ $label==='Semua'?'aktif':'' }}" data-group="tipe" data-val="{{ $val }}" onclick="pilihChip(this,'tipe')">{{ $label }}</div>
            @endforeach
          </div>
        </div>
        <div class="filter-group">
          <div class="filter-group-label">Urutkan Berdasarkan</div>
          <div class="d-flex flex-column gap-2">
            @foreach(['rekomendasi'=>'Paling Rekomendasi','termurah'=>'Harga Termurah','tertinggi'=>'Harga Tertinggi','terbaru'=>'Update Terbaru'] as $val=>$label)
            <label style="display:flex;align-items:center;gap:.6rem;font-size:.85rem;cursor:pointer;">
              <input type="radio" name="urutan" value="{{ $val }}" style="accent-color:var(--kf-primary);" {{ $val==='rekomendasi'?'checked':'' }}> {{ $label }}
            </label>
            @endforeach
          </div>
        </div>
        <div class="filter-group">
          <div class="filter-group-label">Lokasi</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;">
            @foreach(['Surabaya','Malang','Sidoarjo','Gresik','Kediri','Jember','Banyuwangi','Mojokerto','Madiun','Pasuruan'] as $kota)
            <label style="display:flex;align-items:center;gap:.5rem;font-size:.84rem;cursor:pointer;">
              <input type="checkbox" name="lokasi[]" value="{{ $kota }}" style="accent-color:var(--kf-primary);"> {{ $kota }}
            </label>
            @endforeach
          </div>
        </div>
        <div class="filter-group" style="margin-bottom:0;">
          <div class="filter-group-label">Rentang Harga</div>
          <div class="d-flex gap-3 align-items-center mb-2">
            <div style="flex:1;">
              <div style="font-size:.75rem;color:var(--kf-muted);margin-bottom:.3rem;">Minimum</div>
              <div id="filterMinLabel" style="border:1px solid #ede8e5;border-radius:.7rem;padding:.4rem .8rem;font-size:.84rem;background:#fdf8f5;">Rp 0</div>
            </div>
            <span style="color:var(--kf-muted);padding-top:1.2rem;">–</span>
            <div style="flex:1;">
              <div style="font-size:.75rem;color:var(--kf-muted);margin-bottom:.3rem;">Maksimum</div>
              <div id="filterMaxLabel" style="border:1px solid #ede8e5;border-radius:.7rem;padding:.4rem .8rem;font-size:.84rem;background:#fdf8f5;">Rp 15.000.000</div>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;gap:.6rem;">
            <div style="display:flex;align-items:center;gap:.6rem;">
              <span style="font-size:.75rem;color:var(--kf-muted);width:28px;">Min</span>
              <input type="range" id="filterMinRange" min="0" max="15000000" step="500000" value="0" style="flex:1;accent-color:var(--kf-primary);" oninput="updateFilterRange()">
            </div>
            <div style="display:flex;align-items:center;gap:.6rem;">
              <span style="font-size:.75rem;color:var(--kf-muted);width:28px;">Maks</span>
              <input type="range" id="filterMaxRange" min="0" max="15000000" step="500000" value="15000000" style="flex:1;accent-color:var(--kf-primary);" oninput="updateFilterRange()">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-filter-footer">
        <button type="button" class="btn-reset-filter" onclick="resetFilter()">Reset</button>
        <button type="button" class="btn-apply-filter" onclick="terapkanFilter()">Terapkan</button>
      </div>
    </div>
  </div>
</div>

{{-- ══ BOTTOM NAV ══ --}}
<nav class="bottom-nav">
  <a href="{{ route('home') }}" class="bottom-nav-item active" id="bnav-cari">
    <i class="bi bi-search"></i><span>Cari</span>
  </a>
  <a href="{{ route('carikos') }}" class="bottom-nav-item" id="bnav-kost">
    <i class="bi bi-house-door"></i><span>Kost</span>
  </a>
  @auth
  <a href="{{ route('user.favorit') }}" class="bottom-nav-item" id="bnav-favorit">
    <i class="bi bi-heart"></i><span>Favorit</span>
  </a>
  <a href="#" class="bottom-nav-item" id="bnav-notif" style="position:relative;">
    <i class="bi bi-bell"></i><span>Notifikasi</span>
    <span class="bnav-dot"></span>
  </a>
  <a href="{{ route('user.profil') }}" class="bottom-nav-item {{ request()->routeIs('user.profil*','user.verifikasi*','user.pengaturan*')?'active':'' }}" id="bnav-profil">
    <i class="bi bi-person-circle"></i><span>Profil</span>
  </a>
  @else
  <a href="{{ route('login') }}" class="bottom-nav-item" id="bnav-favorit">
    <i class="bi bi-heart"></i><span>Favorit</span>
  </a>
  <a href="{{ route('login') }}" class="bottom-nav-item" id="bnav-notif">
    <i class="bi bi-bell"></i><span>Notifikasi</span>
  </a>
  <a href="{{ route('login') }}" class="bottom-nav-item" id="bnav-profil">
    <i class="bi bi-person-circle"></i><span>Masuk</span>
  </a>
  @endauth
</nav>

</div>
@endsection

@section('scripts')
<script>
(function(){
  setTimeout(()=>document.getElementById('heroBg')?.classList.add('loaded'),200);
  const obs=new IntersectionObserver(entries=>{
    entries.forEach(e=>{ if(e.isIntersecting){e.target.classList.add('visible');obs.unobserve(e.target);} });
  },{threshold:0.1});
  document.querySelectorAll('.reveal').forEach(el=>obs.observe(el));

  const promoTrack=document.getElementById('promoTrack');
  if(promoTrack){
    const slide=()=>Math.max(300,promoTrack.clientWidth*.72);
    document.getElementById('promoPrev')?.addEventListener('click',()=>promoTrack.scrollBy({left:-slide(),behavior:'smooth'}));
    document.getElementById('promoNext')?.addEventListener('click',()=>promoTrack.scrollBy({left:slide(),behavior:'smooth'}));
    let down=false,sx,sl;
    promoTrack.addEventListener('mousedown',e=>{down=true;sx=e.pageX-promoTrack.offsetLeft;sl=promoTrack.scrollLeft;promoTrack.style.cursor='grabbing';});
    promoTrack.addEventListener('mouseleave',()=>{down=false;promoTrack.style.cursor='grab';});
    promoTrack.addEventListener('mouseup',()=>{down=false;promoTrack.style.cursor='grab';});
    promoTrack.addEventListener('mousemove',e=>{if(!down)return;e.preventDefault();promoTrack.scrollLeft=sl-(e.pageX-promoTrack.offsetLeft-sx);});
  }
  const recoTrack=document.getElementById('recoTrack');
  if(recoTrack){
    const slide=()=>{const item=recoTrack.querySelector('.reco-item');return item?(item.offsetWidth+16)*4:1000;};
    document.getElementById('recoPrev')?.addEventListener('click',()=>recoTrack.scrollBy({left:-slide(),behavior:'smooth'}));
    document.getElementById('recoNext')?.addEventListener('click',()=>recoTrack.scrollBy({left:slide(),behavior:'smooth'}));
  }
})();

function showHeroDropdown(){ document.getElementById('heroDropdown').classList.remove('d-none'); }
function setHeroSearch(val){ document.getElementById('heroSearchInput').value=val;document.getElementById('heroDropdown').classList.add('d-none');document.getElementById('heroSearchInput').focus(); }
function switchHeroTab(tab,el){
  const d=document.getElementById('heroTabDaerah'),k=document.getElementById('heroTabKampus');
  if(tab==='daerah'){d.classList.remove('d-none');d.classList.add('d-flex');k.classList.add('d-none');k.classList.remove('d-flex');}
  else{k.classList.remove('d-none');k.classList.add('d-flex');d.classList.add('d-none');d.classList.remove('d-flex');}
  document.querySelectorAll('.hero-tab-bar .btn-link').forEach(b=>{b.style.color='rgba(255,255,255,.45)';b.style.borderBottom='none';b.classList.remove('fw-bold');b.classList.add('fw-medium');});
  el.style.color='var(--kf-primary-light)';el.style.borderBottom='2px solid var(--kf-primary-light)';el.classList.remove('fw-medium');el.classList.add('fw-bold');
}
document.addEventListener('click',e=>{
  const w=document.getElementById('searchWrapper');
  if(w&&!w.contains(e.target)) document.getElementById('heroDropdown')?.classList.add('d-none');
});
function doSearch(){
  const q=document.getElementById('heroSearchInput').value.trim();
  window.location.href='{{ route("carikos") }}'+(q?'?q='+encodeURIComponent(q):'');
}
document.getElementById('heroSearchInput')?.addEventListener('keydown',e=>{ if(e.key==='Enter') doSearch(); });

function pilihChip(el,grup){
  document.querySelectorAll(`.f-chip[data-group="${grup}"]`).forEach(c=>c.classList.remove('aktif'));
  el.classList.add('aktif');
}
function updateFilterRange(){
  let mn=parseInt(document.getElementById('filterMinRange').value);
  let mx=parseInt(document.getElementById('filterMaxRange').value);
  if(mn>mx){mn=mx;document.getElementById('filterMinRange').value=mn;}
  document.getElementById('filterMinLabel').textContent='Rp '+mn.toLocaleString('id-ID');
  document.getElementById('filterMaxLabel').textContent='Rp '+mx.toLocaleString('id-ID');
}
function resetFilter(){
  ['tipe','harga','durasi','sewa'].forEach(g=>{document.querySelectorAll(`.f-chip[data-group="${g}"]`).forEach((c,i)=>c.classList.toggle('aktif',i===0));});
  document.querySelectorAll('input[name="lokasi[]"]').forEach(c=>c.checked=false);
  const u=document.querySelector('input[name="urutan"][value="rekomendasi"]'); if(u) u.checked=true;
  const mn=document.getElementById('filterMinRange'),mx=document.getElementById('filterMaxRange');
  if(mn) mn.value=0; if(mx) mx.value=15000000; updateFilterRange();
}
function terapkanFilter(){
  const params=new URLSearchParams();
  const q=document.getElementById('heroSearchInput')?.value.trim(); if(q) params.set('q',q);
  const tipe=document.querySelector('.f-chip[data-group="tipe"].aktif')?.dataset.val; if(tipe) params.set('type',tipe);
  const sewa=document.querySelector('.f-chip[data-group="sewa"].aktif')?.dataset.val; if(sewa) params.set('sewa',sewa);
  const urutan=document.querySelector('input[name="urutan"]:checked')?.value; if(urutan) params.set('urutan',urutan);
  const minR=document.getElementById('filterMinRange'),maxR=document.getElementById('filterMaxRange');
  if(minR&&parseInt(minR.value)>0) params.set('harga_min',minR.value);
  if(maxR&&parseInt(maxR.value)<15000000) params.set('harga_max',maxR.value);
  document.querySelectorAll('input[name="lokasi[]"]:checked').forEach(c=>params.append('lokasi',c.value));
  bootstrap.Modal.getInstance(document.getElementById('modalFilter'))?.hide();
  window.location.href='{{ route("carikos") }}?'+params.toString();
}

function toggleFav(btn){
  const kostId=btn.dataset.kost,icon=btn.querySelector('i');
  btn.classList.add('pop'); setTimeout(()=>btn.classList.remove('pop'),300);
  @auth
  fetch('{{ route("user.favorit.toggle") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({kost_id:kostId})})
  .then(r=>r.json()).then(data=>{
    if(data.status==='added'){icon.className='bi bi-heart-fill';icon.style.color='#e8401c';btn.classList.add('liked');btn.title='Hapus dari favorit';}
    else if(data.status==='removed'){icon.className='bi bi-heart';icon.style.color='#bbb';btn.classList.remove('liked');btn.title='Tambah ke favorit';}
  }).catch(err=>console.error(err));
  @else
  window.location.href='{{ route("login") }}';
  @endauth
}
(function(){
  const path=window.location.pathname;
  document.querySelectorAll('.bottom-nav-item').forEach(el=>{
    el.classList.remove('active');
    if(el.getAttribute('href')&&path===new URL(el.href,location.origin).pathname) el.classList.add('active');
  });
  if(path==='/'||path==='/home') document.getElementById('bnav-cari')?.classList.add('active');
})();
</script>
@endsection