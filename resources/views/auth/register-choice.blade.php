<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pilih Tipe Daftar - KostFinder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,800&display=swap');

    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #1e293b;
    }

    .register-choice-shell {
      width: 100%;
      max-width: 520px;
      padding: 1.5rem;
    }

    .register-choice-card {
      background: #fff;
      border-radius: 2rem;
      box-shadow: 0 32px 64px rgba(15, 23, 42, .12);
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, .8);
    }

    .register-choice-header {
      background: #1a1108; /* Dark Brown/Black */
      padding: 2.5rem 2rem;
      color: #fff;
      position: relative;
    }

    .close-btn {
      position: absolute;
      top: 1.5rem;
      right: 1.5rem;
      width: 32px;
      height: 32px;
      background: rgba(255, 255, 255, .15);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      text-decoration: none;
      transition: all .2s;
      z-index: 10;
    }

    .close-btn:hover {
      background: rgba(255, 255, 255, .25);
      color: #fff;
      transform: rotate(90deg);
    }

    .brand-logo-area {
      display: flex;
      align-items: center;
      gap: .8rem;
      margin-bottom: 2rem;
    }

    .brand-icon {
      width: 44px;
      height: 44px;
      background: #e8401c;
      border-radius: .8rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.3rem;
      color: #fff;
      box-shadow: 0 8px 16px rgba(232, 64, 28, .3);
    }

    .brand-text {
      line-height: 1.2;
    }

    .brand-name {
      font-family: 'Fraunces', serif;
      font-size: 1.15rem;
      font-weight: 800;
      letter-spacing: -.02em;
    }

    .brand-name span {
      color: #ff7a52;
    }

    .brand-sub {
      font-size: .6rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .15em;
      color: #c8a880;
      display: block;
      margin-top: 2px;
    }

    .header-title {
      font-size: 1.75rem;
      font-weight: 800;
      margin-bottom: .4rem;
      letter-spacing: -.01em;
    }

    .header-sub {
      font-size: .95rem;
      color: #c8a880;
      font-weight: 500;
    }

    .register-choice-body {
      padding: 2.2rem 2rem 1rem;
    }

    .section-label {
      font-size: .7rem;
      font-weight: 800;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: .12em;
      margin-bottom: 1.2rem;
    }

    .register-option {
      display: flex;
      align-items: center;
      gap: 1.2rem;
      padding: 1.2rem;
      border: 1.5px solid #f1f5f9;
      border-radius: 1.25rem;
      text-decoration: none;
      color: #1e293b;
      background: #fff;
      transition: all .25s cubic-bezier(0.4, 0, 0.2, 1);
      margin-bottom: 1rem;
      position: relative;
      overflow: hidden;
    }

    .register-option::after {
      content: '';
      position: absolute;
      inset: 0;
      background: currentColor;
      opacity: 0;
      transition: opacity .25s;
      z-index: 0;
    }

    .option-icon-box {
      width: 56px;
      height: 56px;
      border-radius: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      flex-shrink: 0;
      transition: all .25s;
      z-index: 1;
    }

    .option-icon-box.user {
      background: #ecfdf5;
      color: #10b981;
    }

    .option-icon-box.owner {
      background: #fff7ed;
      color: #f59e0b;
    }

    .option-content {
      flex: 1;
      z-index: 1;
      transition: all .25s;
    }

    .option-title-wrap {
      display: flex;
      align-items: center;
      gap: .6rem;
      margin-bottom: .2rem;
    }

    .option-title {
      font-size: 1.05rem;
      font-weight: 800;
      color: #0f172a;
      transition: color .25s;
    }

    .badge-gratis {
      background: #d1fae5;
      color: #065f46;
      font-size: .65rem;
      font-weight: 800;
      padding: .2rem .5rem;
      border-radius: 99px;
      text-transform: uppercase;
      letter-spacing: .02em;
      transition: all .25s;
    }

    .option-desc {
      font-size: .88rem;
      color: #64748b;
      line-height: 1.5;
      font-weight: 500;
      transition: color .25s;
    }

    .option-arrow {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #94a3b8;
      font-size: .8rem;
      transition: all .25s;
      z-index: 1;
    }

    /* Hover States - Pencari Kost (Green Theme) */
    .register-option.pencari:hover {
      background: #059669;
      border-color: #059669;
      transform: translateY(-3px);
      box-shadow: 0 12px 24px rgba(16, 185, 129, 0.2);
    }
    .register-option.pencari:hover .option-title,
    .register-option.pencari:hover .option-desc { color: #fff; }
    .register-option.pencari:hover .option-icon-box { background: rgba(255,255,255,0.15); color: #fff; }
    .register-option.pencari:hover .badge-gratis { background: rgba(255,255,255,0.2); color: #fff; }
    .register-option.pencari:hover .option-arrow { background: rgba(255,255,255,0.2); color: #fff; transform: translateX(3px); }

    /* Hover States - Pemilik Kost (Orange Theme) */
    .register-option.pemilik:hover {
      background: #e8401c;
      border-color: #e8401c;
      transform: translateY(-3px);
      box-shadow: 0 12px 24px rgba(232, 64, 28, 0.2);
    }
    .register-option.pemilik:hover .option-title,
    .register-option.pemilik:hover .option-desc { color: #fff; }
    .register-option.pemilik:hover .option-icon-box { background: rgba(255,255,255,0.15); color: #fff; }
    .register-option.pemilik:hover .option-arrow { background: rgba(255,255,255,0.2); color: #fff; transform: translateX(3px); }

    .register-choice-footer {
      text-align: center;
      padding: 1.5rem 2rem;
      border-top: 1px solid #f1f5f9;
      color: #64748b;
      font-size: .92rem;
      font-weight: 600;
    }

    .register-choice-footer a {
      color: #e8401c;
      font-weight: 800;
      text-decoration: none;
      margin-left: 4px;
      transition: color .2s;
    }

    .register-choice-footer a:hover {
      color: #c03414;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="register-choice-shell">
    <div class="register-choice-card">
      <div class="register-choice-header">
        <a href="{{ url('/') }}" class="close-btn"><i class="bi bi-x-lg"></i></a>
        
        <div class="brand-logo-area">
          <div class="brand-icon">
            <i class="bi bi-house-fill"></i>
          </div>
          <div class="brand-text">
            <div class="brand-name">Kost<span>Finder</span></div>
            <div class="brand-sub">JAWA TIMUR</div>
          </div>
        </div>

        <h1 class="header-title">Daftar ke KostFinder</h1>
        <p class="header-sub">Pilih peranmu untuk memulai</p>
      </div>

      <div class="register-choice-body">
        <div class="section-label">Saya ingin mendaftar sebagai</div>

        <a href="{{ route('register.user') }}" class="register-option pencari">
          <div class="option-icon-box user">
            <i class="bi bi-search"></i>
          </div>
          <div class="option-content">
            <div class="option-title-wrap">
              <span class="option-title">Pencari Kost</span>
              <span class="badge-gratis">Gratis</span>
            </div>
            <div class="option-desc">Cari, simpan favorit, dan booking kos impian kamu</div>
          </div>
          <div class="option-arrow">
            <i class="bi bi-chevron-right"></i>
          </div>
        </a>

        <a href="{{ route('register.owner') }}" class="register-option pemilik">
          <div class="option-icon-box owner">
            <i class="bi bi-house-door"></i>
          </div>
          <div class="option-content">
            <div class="option-title-wrap">
              <span class="option-title">Pemilik Kost</span>
            </div>
            <div class="option-desc">Kelola properti dan pasang iklan kost kamu</div>
          </div>
          <div class="option-arrow">
            <i class="bi bi-chevron-right"></i>
          </div>
        </a>
      </div>

      <div class="register-choice-footer">
        Sudah punya akun?
        <a href="{{ route('login') }}">Masuk sekarang</a>
      </div>
    </div>
  </div>
</body>
</html>
