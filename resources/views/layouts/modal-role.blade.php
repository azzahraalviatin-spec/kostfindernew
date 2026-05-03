<style>
  .kf-modal-shell {
    border-radius: 22px !important;
    overflow: hidden !important;
    border: 0 !important;
  }

  .kf-modal-header-dark {
    background: #1c1008;
    padding: 26px 28px 24px;
    position: relative;
  }

  .kf-brand-row {
    display: flex; align-items: center;
    gap: 10px; margin-bottom: 18px;
  }
  .kf-modal-brand-icon {
    width: 34px; height: 34px;
    background: #E8401C; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .kf-brand-name { font-size: 15px; font-weight: 600; color: #fff; line-height: 1; }
  .kf-brand-name span { color: #ff7a52; }
  .kf-brand-sub { font-size: 9px; letter-spacing: 2px; color: #a07850; display: block; margin-top: 2px; }

  .kf-modal-title-text { font-size: 20px; font-weight: 600; color: #fff; margin-bottom: 3px; }
  .kf-modal-sub-text { font-size: 13px; color: #c8a880; }

  .kf-close-dark {
    position: absolute; top: 16px; right: 16px;
    width: 30px; height: 30px; border-radius: 50%;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.15);
    color: #c8a880; font-size: 13px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.18s, color 0.18s;
  }
  .kf-close-dark:hover { background: rgba(255,255,255,0.22); color: #fff; }

  .kf-section-label {
    font-size: 10.5px; font-weight: 700;
    letter-spacing: 0.1em; text-transform: uppercase;
    color: #888070; margin-bottom: 13px;
  }

  .kf-role-card {
    display: flex; align-items: center;
    gap: 14px; padding: 15px 16px;
    border-radius: 13px; border: 1.5px solid #e0dbd3;
    margin-bottom: 10px; cursor: pointer;
    text-decoration: none;
    transition: background 0.22s, border-color 0.22s;
    background: #fff;
  }

  .kf-role-icon {
    width: 46px; height: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; transition: background 0.22s;
  }
  .kf-role-icon svg { width: 22px; height: 22px; transition: stroke 0.22s; }

  .icon-pencari { background: #e0f2ea; }
  .icon-pencari svg { stroke: #0a5c45; }
  .icon-pemilik { background: #fdeede; }
  .icon-pemilik svg { stroke: #a84008; }

  .kf-role-text { flex: 1; min-width: 0; }
  .kf-role-title {
    font-size: 14px; font-weight: 700; color: #1c1008;
    margin-bottom: 3px; transition: color 0.22s;
    display: flex; align-items: center; gap: 7px;
  }
  .kf-role-desc { font-size: 12px; color: #6b5e54; line-height: 1.45; transition: color 0.22s; }

  .kf-free-badge {
    font-size: 10px; padding: 2px 8px; border-radius: 20px;
    background: #d8f0e6; color: #0a5c45; font-weight: 700;
    transition: background 0.22s, color 0.22s;
  }

  .kf-arrow-circle {
    width: 26px; height: 26px; border-radius: 50%;
    background: #f0ebe3;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 15px; color: #a84008;
    transition: background 0.22s, color 0.22s, transform 0.22s;
  }

  /* Hover Pencari */
  .kf-role-card.pencari:hover { background: #0a5c45; border-color: #0a5c45; }
  .kf-role-card.pencari:hover .kf-role-icon { background: rgba(255,255,255,0.18); }
  .kf-role-card.pencari:hover .kf-role-icon svg { stroke: #fff; }
  .kf-role-card.pencari:hover .kf-role-title { color: #fff; }
  .kf-role-card.pencari:hover .kf-role-desc { color: rgba(255,255,255,0.8); }
  .kf-role-card.pencari:hover .kf-free-badge { background: rgba(255,255,255,0.22); color: #fff; }
  .kf-role-card.pencari:hover .kf-arrow-circle { background: rgba(255,255,255,0.22); color: #fff; transform: translateX(3px); }

  /* Hover Pemilik */
  .kf-role-card.pemilik:hover { background: #a84008; border-color: #a84008; }
  .kf-role-card.pemilik:hover .kf-role-icon { background: rgba(255,255,255,0.18); }
  .kf-role-card.pemilik:hover .kf-role-icon svg { stroke: #fff; }
  .kf-role-card.pemilik:hover .kf-role-title { color: #fff; }
  .kf-role-card.pemilik:hover .kf-role-desc { color: rgba(255,255,255,0.8); }
  .kf-role-card.pemilik:hover .kf-arrow-circle { background: rgba(255,255,255,0.22); color: #fff; transform: translateX(3px); }

  .kf-modal-footer-custom {
    border-top: 1px solid #ede8e0;
    padding: 14px 24px; text-align: center;
    font-size: 13px; color: #6b5e54;
  }
  .kf-modal-footer-custom a { color: #E8401C; font-weight: 700; text-decoration: none; }
  .kf-modal-footer-custom a:hover { text-decoration: underline; }
</style>

<div class="modal fade" id="modalRole" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 kf-modal-shell">

      {{-- Header Gelap --}}
      <div class="kf-modal-header-dark">
        <button type="button" class="kf-close-dark" data-bs-dismiss="modal">✕</button>
        <div class="kf-brand-row">
          <div class="kf-modal-brand-icon">
            <svg width="18" height="18" viewBox="0 0 34 34" fill="none">
              <path d="M17 5L5 14.2V29H13V22H21V29H29V14.2L17 5Z" fill="white" opacity=".95"/>
              <rect x="14" y="22" width="6" height="7" rx="1" fill="#E8401C"/>
            </svg>
          </div>
          <div>
            <div class="kf-brand-name">Kost<span>Finder</span></div>
            <span class="kf-brand-sub">JAWA TIMUR</span>
          </div>
        </div>
        <div class="kf-modal-title-text">Daftar ke KostFinder</div>
        <div class="kf-modal-sub-text">Pilih peranmu untuk memulai</div>
      </div>

      {{-- Body --}}
      <div class="modal-body px-4 py-4">
        <div class="kf-section-label">Saya ingin mendaftar sebagai</div>

        <a href="{{ route('register.user') }}" class="kf-role-card pencari">
          <div class="kf-role-icon icon-pencari">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/>
              <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </div>
          <div class="kf-role-text">
            <div class="kf-role-title">
              Pencari Kost
              <span class="kf-free-badge">Gratis</span>
            </div>
            <div class="kf-role-desc">Cari, simpan favorit, dan booking kos impian kamu</div>
          </div>
          <div class="kf-arrow-circle">›</div>
        </a>

        <a href="{{ route('register.owner') }}" class="kf-role-card pemilik">
          <div class="kf-role-icon icon-pemilik">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
              <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
          </div>
          <div class="kf-role-text">
            <div class="kf-role-title">Pemilik Kost</div>
            <div class="kf-role-desc">Kelola properti dan pasang iklan kost kamu</div>
          </div>
          <div class="kf-arrow-circle">›</div>
        </a>
      </div>

      {{-- Footer --}}
      <div class="kf-modal-footer-custom">
        Sudah punya akun?
        <a href="{{ route('login') }}">Masuk sekarang</a>
      </div>

    </div>
  </div>
</div>