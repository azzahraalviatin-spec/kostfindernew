<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - KostFinder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
      --navy: #10233f;
      --navy-deep: #091529;
      --cream: #fffaf2;
      --peach: #ffb26b;
      --gold: #ffd166;
      --ink: #24324a;
      --muted: #6c7a92;
      --line: rgba(16, 35, 63, 0.1);
      --card-shadow: 0 24px 60px rgba(16, 35, 63, 0.14);
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: var(--ink);
      background:
        radial-gradient(circle at top left, rgba(255, 178, 107, 0.22), transparent 28%),
        radial-gradient(circle at bottom right, rgba(16, 35, 63, 0.10), transparent 32%),
        linear-gradient(135deg, #fffdf8 0%, #f7f8fb 45%, #eef3f8 100%);
    }

    .auth-shell {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 32px;
    }

    .auth-card {
      width: min(1180px, 100%);
      min-height: calc(100vh - 64px);
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 480px);
      background: rgba(255, 255, 255, 0.82);
      border: 1px solid rgba(255, 255, 255, 0.7);
      border-radius: 32px;
      overflow: hidden;
      box-shadow: var(--card-shadow);
      backdrop-filter: blur(16px);
    }

    .form-panel {
      display: flex;
      align-items: center;
      padding: 52px;
      background:
        linear-gradient(180deg, rgba(255, 255, 255, 0.96) 0%, rgba(255, 248, 240, 0.94) 100%);
    }

    .form-wrap {
      width: 100%;
      max-width: 390px;
    }

    .brand-link {
      display: inline-flex;
      align-items: center;
      gap: 14px;
      text-decoration: none;
      margin-bottom: 28px;
    }

    .brand-icon {
      width: 58px;
      height: 58px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 18px;
      color: #fff;
      font-size: 1.35rem;
      background: linear-gradient(135deg, var(--navy) 0%, #1f3e68 100%);
      box-shadow: 0 16px 36px rgba(16, 35, 63, 0.22);
    }

    .brand-title {
      margin: 0;
      font-size: 1.3rem;
      font-weight: 800;
      color: var(--navy);
    }

    .brand-subtitle {
      display: block;
      margin-top: 2px;
      font-size: 0.9rem;
      color: var(--muted);
      font-weight: 500;
    }

    .form-title {
      margin: 0;
      font-size: clamp(2rem, 4vw, 2.8rem);
      line-height: 1.05;
      font-weight: 800;
      color: var(--navy-deep);
    }

    .form-lead {
      margin: 14px 0 28px;
      color: var(--muted);
      font-size: 0.98rem;
      line-height: 1.75;
    }

    .highlight-row {
      display: flex;
      gap: 12px;
      margin-bottom: 28px;
      flex-wrap: wrap;
    }

    .highlight-chip {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 14px;
      border-radius: 14px;
      background: rgba(255, 255, 255, 0.85);
      border: 1px solid var(--line);
      color: var(--ink);
      font-size: 0.83rem;
      font-weight: 600;
    }

    .highlight-chip i {
      color: #f59e0b;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-label {
      display: block;
      margin-bottom: 8px;
      font-size: 0.77rem;
      font-weight: 800;
      color: var(--ink);
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    .input-wrap {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1rem;
      color: #90a0b6;
    }

    .form-control {
      width: 100%;
      min-height: 58px;
      padding: 16px 18px 16px 50px;
      border-radius: 18px;
      border: 1px solid rgba(16, 35, 63, 0.12);
      background: rgba(255, 255, 255, 0.84);
      color: var(--ink);
      font-size: 0.96rem;
      font-weight: 600;
      transition: 0.25s ease;
      box-shadow: none;
    }

    .form-control::placeholder {
      color: #a2aec0;
      font-weight: 500;
    }

    .form-control:focus {
      border-color: rgba(16, 35, 63, 0.32);
      background: #fff;
      box-shadow: 0 0 0 5px rgba(16, 35, 63, 0.08);
    }

    .password-toggle {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      width: 38px;
      height: 38px;
      border: 0;
      border-radius: 50%;
      background: transparent;
      color: #94a3b8;
      transition: 0.2s ease;
    }

    .password-toggle:hover {
      color: var(--navy);
      background: rgba(16, 35, 63, 0.06);
    }

    .support-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      margin-top: 6px;
      margin-bottom: 24px;
      flex-wrap: wrap;
    }

    .remember-box {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      color: var(--muted);
      font-size: 0.9rem;
      font-weight: 500;
    }

    .remember-box input {
      width: 18px;
      height: 18px;
      accent-color: var(--navy);
    }

    .forgot-link,
    .switch-link,
    .secondary-link {
      text-decoration: none;
    }

    .forgot-link {
      color: var(--navy);
      font-size: 0.9rem;
      font-weight: 700;
    }

    .forgot-link:hover,
    .switch-link:hover,
    .secondary-link:hover {
      opacity: 0.85;
    }

    .btn-login,
    .btn-google {
      width: 100%;
      border: 0;
      border-radius: 18px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      font-size: 0.96rem;
      font-weight: 800;
      transition: 0.25s ease;
      text-decoration: none;
    }

    .btn-login {
      min-height: 58px;
      color: #fff;
      background: linear-gradient(135deg, var(--navy-deep) 0%, var(--navy) 100%);
      box-shadow: 0 18px 32px rgba(16, 35, 63, 0.22);
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 24px 40px rgba(16, 35, 63, 0.28);
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 16px;
      margin: 22px 0;
      color: #8a98ae;
      font-size: 0.86rem;
      font-weight: 600;
    }

    .divider::before,
    .divider::after {
      content: "";
      flex: 1;
      height: 1px;
      background: rgba(16, 35, 63, 0.12);
    }

    .btn-google {
      min-height: 56px;
      color: var(--ink);
      background: rgba(255, 255, 255, 0.88);
      border: 1px solid rgba(16, 35, 63, 0.12);
    }

    .btn-google:hover {
      transform: translateY(-2px);
      border-color: rgba(16, 35, 63, 0.22);
      box-shadow: 0 14px 30px rgba(16, 35, 63, 0.1);
    }

    .switch-text {
      margin: 22px 0 0;
      text-align: center;
      color: var(--muted);
      font-size: 0.93rem;
      line-height: 1.7;
    }

    .switch-link,
    .secondary-link {
      color: var(--navy);
      font-weight: 800;
    }

    .alert {
      border: 0;
      border-radius: 18px;
      padding: 15px 16px;
      margin-bottom: 18px;
      font-size: 0.9rem;
      line-height: 1.6;
    }

    .alert-success {
      background: rgba(34, 197, 94, 0.14);
      color: #166534;
    }

    .alert-danger {
      background: rgba(239, 68, 68, 0.14);
      color: #991b1b;
    }

    .visual-panel {
      position: relative;
      display: flex;
      align-items: stretch;
      overflow: hidden;
      background: linear-gradient(145deg, var(--navy-deep) 0%, #17355e 55%, #27527f 100%);
    }

    .visual-media {
      position: absolute;
      inset: 0;
      background:
        linear-gradient(180deg, rgba(4, 10, 20, 0.35) 0%, rgba(4, 10, 20, 0.75) 100%),
        url('{{ asset('images/daftar.png') }}') center/cover no-repeat;
      transform: scale(1.1);
    }

    .visual-panel::before,
    .visual-panel::after {
      content: "";
      position: absolute;
      border-radius: 999px;
      filter: blur(8px);
    }

    .visual-panel::before {
      width: 220px;
      height: 220px;
      top: -60px;
      right: -40px;
      background: rgba(255, 209, 102, 0.26);
    }

    .visual-panel::after {
      width: 180px;
      height: 180px;
      bottom: 48px;
      left: -44px;
      background: rgba(255, 178, 107, 0.24);
    }

    .visual-content {
      position: relative;
      z-index: 1;
      width: 100%;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      padding-top: 15%;
      align-items: flex-start;
      text-align: left;
      padding: 46px;
      color: #fff;
    }

    .visual-badge {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 11px 16px;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.14);
      border: 1px solid rgba(255, 255, 255, 0.18);
      backdrop-filter: blur(10px);
      font-size: 0.84rem;
      font-weight: 700;
      letter-spacing: 0.02em;
    }

    .visual-copy {
      max-width: 480px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      text-align: left;
    }

    .visual-title {
      margin: 0 0 16px;
      font-size: clamp(1.65rem, 3.2vw, 2.65rem);
      line-height: 1.15;
      font-weight: 800;
      text-wrap: balance;
    }

    .visual-text {
      margin: 0;
      max-width: 360px;
      font-size: 0.92rem;
      line-height: 1.7;
      color: rgba(255, 255, 255, 0.84);
    }

    .visual-caption {
      margin-top: 20px;
      max-width: 330px;
      padding: 12px 16px;
      border-radius: 16px;
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      font-size: 0.84rem;
      line-height: 1.6;
      color: rgba(255, 255, 255, 0.84);
    }

    @media (max-width: 1199px) {
      .form-panel,
      .visual-content {
        padding: 38px;
      }
    }

    @media (max-width: 991px) {
      .auth-shell {
        padding: 18px;
      }

      .auth-card {
        min-height: auto;
        grid-template-columns: 1fr;
      }

      .form-panel {
        order: 2;
        padding: 30px 24px 34px;
      }

      .visual-panel {
        min-height: 520px;
        order: 1;
      }

      .visual-content {
        padding: 26px 24px 30px;
      }
    }

    @media (max-width: 767px) {
      .auth-card {
        border-radius: 26px;
      }

      .visual-panel {
        min-height: 470px;
      }

      .visual-title {
        font-size: 2rem;
      }

      .support-row {
        align-items: flex-start;
        flex-direction: column;
      }
    }

@media (max-width: 575px) {
  .visual-panel { min-height: 80px; }
  .visual-badge { display: none; }
  .visual-copy { display: none; }
  .form-panel {
    border-radius: 24px 24px 0 0;
    margin-top: -24px;
    position: relative;
    z-index: 2;
  }



      }

      .auth-card {
        border-radius: 0;
        min-height: 100vh;
      }

      .form-panel {
        padding: 28px 18px 34px;
      }

      .visual-panel {
        min-height: 430px;
      }

      .visual-content {
        padding: 22px 18px 24px;
      }

      .brand-link {
        margin-bottom: 22px;
      }

      .highlight-row {
        margin-bottom: 24px;
      }
    }

    @media (max-width: 575px) {
  .auth-shell { padding: 0; }
  .auth-card {
    border-radius: 0;
    min-height: 100vh;
    grid-template-columns: 1fr;
    display: flex;
    flex-direction: column;
  }
  .visual-panel {
    order: 1;
    min-height: 240px;
    flex-shrink: 0;
  }
  .visual-content { padding: 28px 20px; }
  .visual-title { font-size: 1.5rem; }
  .visual-text, .visual-caption { display: none; }
  .visual-badge { font-size: .78rem; padding: 8px 12px; }
  .form-panel {
    order: 2;
    flex: 1;
    padding: 24px 18px 32px;
    border-radius: 24px 24px 0 0;
    margin-top: -20px;
    position: relative;
    z-index: 2;
    background: #fff;
  }
  .brand-link { margin-bottom: 16px; }
  .brand-icon { width: 44px; height: 44px; font-size: 1.1rem; border-radius: 14px; }
  .brand-title { font-size: 1.1rem; }
  .brand-subtitle { font-size: .78rem; }
  .form-title { font-size: 1.4rem; }
  .form-lead { font-size: .85rem; margin-bottom: 16px; }
  .highlight-row { display: none; }
  .form-control { min-height: 50px; font-size: .88rem; }
  .btn-login { min-height: 50px; font-size: .9rem; }
  .btn-google { min-height: 48px; font-size: .88rem; }
}
  </style>
</head>
<body>
  @php
    $selectedRole = request('role', 'user');
    $showGoogleLogin = $selectedRole === 'user';
    $roleLabels = [
      'user' => 'Masuk sebagai pencari kos',
      'owner' => 'Masuk sebagai pemilik kos',
      'admin' => 'Masuk sebagai admin',
    ];
    $roleDescriptions = [
      'user' => 'Cari kos yang nyaman, cek fasilitas, dan booking dengan lebih cepat.',
      'owner' => 'Kelola properti, pantau booking, dan tingkatkan performa bisnis kosmu.',
      'admin' => 'Akses panel pengelolaan untuk memantau seluruh aktivitas platform.',
    ];
  @endphp

  <div class="auth-shell">
    <div class="auth-card">
      <section class="visual-panel">
        <div class="visual-media"></div>
        <div class="visual-content">

          <div class="visual-copy">
            <h3 class="visual-title" style="margin-bottom: 0.5rem;">Temukan Kos Impianmu dengan Mudah</h3>
            <p class="visual-text" style="margin-bottom: 2rem; opacity: 0.9;">
              Daftar sekarang dan mulai cari kos terbaik di sekitar lokasimu.
            </p>

            <div class="visual-features" style="display: flex; flex-direction: column; gap: 1.2rem; width: 100%;">
              <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 42px; height: 42px; border-radius: 50%; background: rgba(232, 64, 28, 0.3); display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(232, 64, 28, 0.5);">
                  <i class="bi bi-search" style="color: #ff6b2b; font-size: 1.1rem;"></i>
                </div>
                <span style="font-weight: 600; font-size: 0.95rem;">Cari kos berdasarkan lokasi & fasilitas</span>
              </div>
              <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 42px; height: 42px; border-radius: 50%; background: rgba(232, 64, 28, 0.3); display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(232, 64, 28, 0.5);">
                  <i class="bi bi-heart" style="color: #ff6b2b; font-size: 1.1rem;"></i>
                </div>
                <span style="font-weight: 600; font-size: 0.95rem;">Simpan kos favorit dengan mudah</span>
              </div>
              <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 42px; height: 42px; border-radius: 50%; background: rgba(232, 64, 28, 0.3); display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(232, 64, 28, 0.5);">
                  <i class="bi bi-chat-dots" style="color: #ff6b2b; font-size: 1.1rem;"></i>
                </div>
                <span style="font-weight: 600; font-size: 0.95rem;">Hubungi pemilik kos langsung</span>
              </div>
              <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 42px; height: 42px; border-radius: 50%; background: rgba(232, 64, 28, 0.3); display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(232, 64, 28, 0.5);">
                  <i class="bi bi-shield-check" style="color: #ff6b2b; font-size: 1.1rem;"></i>
                </div>
                <span style="font-weight: 600; font-size: 0.95rem;">Akun aman & terpercaya</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="form-panel">
        <div class="form-wrap">
          <a href="{{ url('/') }}" class="brand-link">
            <span class="brand-icon">
              <i class="bi bi-house-heart-fill"></i>
            </span>
            <div>
             <h1 class="brand-title">
  <span style="color: #10233f;">Kost</span><span style="color: #f06432;">Finder</span>
</h1>
              <span class="brand-subtitle">Platform pencarian kos yang terasa simpel</span>
            </div>
          </a>
          
          <div class="mb-4">
            <h2 style="font-weight: 800; color: #10233f; font-size: 1.5rem; margin-bottom: 0.4rem;">Selamat datang! 👋</h2>
            <p style="color: #6c7a92; font-size: 0.88rem; margin: 0;">Silakan masuk ke akun kamu untuk melanjutkan.</p>
          </div>          @if(session('status'))
            <div class="alert alert-success">
              <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
            </div>
          @endif

          @if($errors->any())
            <div class="alert alert-danger">
              <i class="bi bi-exclamation-circle-fill me-2"></i>
              @foreach($errors->all() as $error)
                {{ $error }}@if(!$loop->last)<br>@endif
              @endforeach
            </div>
          @endif

          <form method="POST" action="{{ route('login') }}" autocomplete="off">
            @csrf
            <input type="hidden" name="role" value="{{ old('role', $selectedRole) }}">
            <input type="text" name="fake_username" autocomplete="username" class="d-none" tabindex="-1" aria-hidden="true">
            <input type="password" name="fake_password" autocomplete="current-password" class="d-none" tabindex="-1" aria-hidden="true">

            <div class="form-group">
              <label class="form-label" for="email">Email</label>
              <div class="input-wrap">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com" value="" autocomplete="off" autocapitalize="none" spellcheck="false" required autofocus>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" for="passwordInput">Password</label>
              <div class="input-wrap">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Masukkan password" value="" autocomplete="new-password" required>
                <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Tampilkan password">
                  <i class="bi bi-eye" id="toggleIcon"></i>
                </button>
              </div>
            </div>

            <div class="support-row">
              <label class="remember-box" for="remember">
                <input type="checkbox" name="remember" id="remember" @checked(old('remember'))>
                Ingat saya
              </label>
              <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
            </div>

            <button type="submit" class="btn-login">
              <i class="bi bi-box-arrow-in-right"></i>
              Masuk ke akun
            </button>
          </form>

          @if($showGoogleLogin)
            <div class="divider">atau lanjutkan dengan</div>

            <a href="{{ route('auth.google.redirect', ['role' => $selectedRole]) }}" class="btn-google">
              <i class="bi bi-google"></i>
              Login dengan Google
            </a>
          @endif

          @if($selectedRole !== 'admin')
            <p class="switch-text">
              Belum punya akun?
              <a href="{{ route('register') }}" class="switch-link">Daftar sekarang</a>
            </p>
          @else
            <p class="switch-text">
              Butuh kembali ke beranda?
              <a href="{{ url('/') }}" class="secondary-link">Buka halaman utama</a>
            </p>
          @endif
        </div>
      </section>
    </div>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById('passwordInput');
      const icon = document.getElementById('toggleIcon');

      if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
      } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
      }
    }
  </script>
</body>
</html>
