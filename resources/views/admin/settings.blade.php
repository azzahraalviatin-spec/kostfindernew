@php use Illuminate\Support\Facades\Storage; @endphp
@extends('admin.layout')

@section('title', 'Pengaturan Platform')
@section('page_title', 'Pengaturan Platform')
@section('page_subtitle', 'Kelola identitas website, sosial media, dan pengaturan bisnis platform')

@section('content')
<div class="container-fluid px-0">

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            {{-- 🔹 BAGIAN KIRI: PROFIL & BISNIS --}}
            <div class="col-lg-8">
                
                {{-- 1. PENGATURAN AKUN --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                            <i class="bi bi-person-circle text-primary"></i> Profil Administrator
                        </h5>

                        <div class="d-flex align-items-center gap-4 mb-4 pb-4 border-bottom">
                            <div class="position-relative" style="width:100px; height:100px;">
                                <img id="preview-foto"
                                     src="{{ auth()->user()->photo
                                         ? Storage::url(auth()->user()->photo)
                                         : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=4f46e5&color=fff&size=100&bold=true' }}"
                                     class="rounded-circle object-fit-cover w-100 h-100 border border-3 border-white shadow-sm"
                                     style="cursor:pointer;"
                                     onclick="document.getElementById('input-foto').click()">
                                <button type="button" class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle shadow-sm" 
                                        onclick="document.getElementById('input-foto').click()" style="width: 32px; height: 32px;">
                                    <i class="bi bi-camera"></i>
                                </button>
                                <input type="file" id="input-foto" name="photo" accept="image/*" class="d-none">
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 fs-5">{{ auth()->user()->name }}</h6>
                                <p class="text-muted small mb-2">{{ auth()->user()->email }}</p>
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3">Super Admin</span>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control rounded-3" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Email Utama</label>
                                <input type="email" class="form-control rounded-3 bg-light" value="{{ auth()->user()->email }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. IDENTITAS PLATFORM (SOSMED & KONTAK) --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                            <i class="bi bi-globe text-success"></i> Kontak & Sosial Media
                        </h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">WhatsApp Customer Service</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp text-success"></i></span>
                                    <input type="text" name="whatsapp_cs" class="form-control rounded-end-3" placeholder="Contoh: 628123456789" value="{{ $settings->whatsapp_cs ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Email Support</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-primary"></i></span>
                                    <input type="email" name="email_support" class="form-control rounded-end-3" placeholder="support@kostfinder.id" value="{{ $settings->email_support ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Link Instagram</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-instagram text-danger"></i></span>
                                    <input type="url" name="instagram_link" class="form-control rounded-end-3" placeholder="https://instagram.com/..." value="{{ $settings->instagram_link ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Link TikTok</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-tiktok text-dark"></i></span>
                                    <input type="url" name="tiktok_link" class="form-control rounded-end-3" placeholder="https://tiktok.com/@..." value="{{ $settings->tiktok_link ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Link Facebook</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-facebook text-primary"></i></span>
                                    <input type="url" name="facebook_link" class="form-control rounded-end-3" placeholder="https://facebook.com/..." value="{{ $settings->facebook_link ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-muted small fw-bold">Alamat Kantor (Footer)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt text-danger"></i></span>
                                    <textarea name="alamat_kantor" class="form-control rounded-end-3" rows="3" placeholder="Masukkan alamat lengkap untuk footer...">{{ $settings->alamat_kantor ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. GANTI PASSWORD --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                            <i class="bi bi-shield-lock text-danger"></i> Keamanan Akun
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label text-muted small fw-bold">Password Lama</label>
                                <input type="password" name="old_password" class="form-control rounded-3" placeholder="Kosongkan jika tidak ingin ganti">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Password Baru</label>
                                <input type="password" name="password" class="form-control rounded-3" placeholder="Min. 8 karakter">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{--  BAGIAN KANAN: BISNIS & NOTIFIKASI --}}
            <div class="col-lg-4">
                
                {{-- 4. PENGATURAN BISNIS --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-primary text-white p-3 border-0">
                        <h6 class="fw-bold mb-0"><i class="bi bi-cash-coin me-2"></i>Pengaturan Bisnis</h6>
                    </div>
                    <div class="card-body p-4">
                        <label class="form-label text-muted small fw-bold">Biaya Layanan Platform (Rp)</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-primary text-white border-0 fw-bold">Rp</span>
                            <input type="text" name="komisi_admin" class="form-control fs-4 fw-bold text-primary" 
                                   value="{{ number_format($settings->komisi_admin ?? 0, 0, ',', '.') }}"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                        </div>
                        <p class="text-muted small">Biaya tetap yang kaka dapet dari setiap transaksi booking (Flat Fee).</p>
                    </div>
                </div>

                {{-- 5. NOTIFIKASI SISTEM --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                            <i class="bi bi-bell text-warning"></i> Notifikasi
                        </h5>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="notif_booking" id="notif_booking" {{ ($settings->notif_booking ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="notif_booking">Booking Masuk</label>
                            <div class="text-muted small">Dapatkan notifikasi saat ada penyewa baru.</div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="notif_user" id="notif_user" {{ ($settings->notif_user ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="notif_user">User Baru</label>
                            <div class="text-muted small">Dapatkan notifikasi saat ada pendaftaran user/owner.</div>
                        </div>
                    </div>
                </div>

                {{-- 🚀 TOMBOL SIMPAN --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary py-3 rounded-4 fw-bold shadow-sm">
                        <i class="bi bi-save me-2"></i> Simpan Semua Perubahan
                    </button>
                    <p class="text-center text-muted small mt-3">Terakhir diperbarui: <br> {{ $settings?->updated_at?->translatedFormat('d F Y, H:i') ?? '-' }}</p>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    // Preview foto
    document.getElementById('input-foto').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('preview-foto').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection