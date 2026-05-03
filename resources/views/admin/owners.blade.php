@extends('admin.layout')

@section('title', 'Monitoring Owner')
@section('page_title', 'Monitoring Data Owner')
@section('page_subtitle', 'Kelola akun pemilik kos: verifikasi, status, dan properti')

@push('styles')
<style>
  /* Fix giant pagination icons */
  .pagination svg {
    width: 20px !important;
    height: 20px !important;
    display: inline-block !important;
  }
  .pagination .flex.justify-between.flex-1 {
    display: none !important; /* Hide tailwind mobile pagination */
  }
</style>
@endpush

@section('content')

@if(session('status'))
  <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.75rem;padding:.85rem 1.1rem;margin-bottom:1rem;font-size:.83rem;color:#15803d;display:flex;align-items:center;gap:.6rem;">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    {{ session('status') }}
  </div>
@endif

{{-- SUMMARY CARDS --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div style="background:#fff;border:1px solid #f0f3f8;border-radius:.9rem;padding:.9rem 1rem;display:flex;align-items:center;gap:.8rem;">
      <div style="width:42px;height:42px;background:#fff5f2;border-radius:.65rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#e8401c" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </div>
      <div>
        <div style="font-size:1.25rem;font-weight:800;color:#1e2d3d;line-height:1;">{{ $owners->total() }}</div>
        <div style="font-size:.72rem;color:#8fa3b8;margin-top:2px;">Total Owner</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div style="background:#fff;border:1px solid #f0f3f8;border-radius:.9rem;padding:.9rem 1rem;display:flex;align-items:center;gap:.8rem;">
      <div style="width:42px;height:42px;background:#f0fdf4;border-radius:.65rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
      <div>
        <div style="font-size:1.25rem;font-weight:800;color:#1e2d3d;line-height:1;">{{ $owners->where('status_akun','aktif')->count() }}</div>
        <div style="font-size:.72rem;color:#8fa3b8;margin-top:2px;">Owner Aktif</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div style="background:#fff;border:1px solid #f0f3f8;border-radius:.9rem;padding:.9rem 1rem;display:flex;align-items:center;gap:.8rem;">
      <div style="width:42px;height:42px;background:#fffbeb;border-radius:.65rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      </div>
      <div>
        <div style="font-size:1.25rem;font-weight:800;color:#1e2d3d;line-height:1;">{{ $owners->where('status_verifikasi_identitas','pending')->count() }}</div>
        <div style="font-size:.72rem;color:#8fa3b8;margin-top:2px;">Pending Verifikasi</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div style="background:#fff;border:1px solid #f0f3f8;border-radius:.9rem;padding:.9rem 1rem;display:flex;align-items:center;gap:.8rem;">
      <div style="width:42px;height:42px;background:#f0f9ff;border-radius:.65rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#0284c7" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      </div>
      <div>
        <div style="font-size:1.25rem;font-weight:800;color:#1e2d3d;line-height:1;">{{ collect($kostCountByOwner ?? [])->sum() }}</div>
        <div style="font-size:.72rem;color:#8fa3b8;margin-top:2px;">Total Kos Terdaftar</div>
      </div>
    </div>
  </div>
</div>

{{-- PANEL UTAMA --}}
<div style="background:#fff;border:1px solid #f0f3f8;border-radius:1rem;overflow:hidden;">

  {{-- FILTER --}}
  <div style="padding:1.1rem 1.25rem;border-bottom:1px solid #f0f3f8;">
    <form method="GET" action="{{ route('admin.owners') }}">
      <div class="row g-2">
        <div class="col-md-4">
          <div style="position:relative;">
            <svg width="14" height="14" fill="none" stroke="#8fa3b8" stroke-width="2" viewBox="0 0 24 24"
              style="position:absolute;left:.8rem;top:50%;transform:translateY(-50%);">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control"
              placeholder="Cari nama, email, no hp..."
              style="padding-left:2.2rem;border-radius:.6rem;border-color:#e4e9f0;font-size:.83rem;">
          </div>
        </div>
        <div class="col-md-3">
          <select name="status" class="form-select" style="border-radius:.6rem;border-color:#e4e9f0;font-size:.83rem;">
            <option value="">Semua status akun</option>
            <option value="aktif" @selected(request('status')==='aktif')>Aktif</option>
            <option value="nonaktif" @selected(request('status')==='nonaktif')>Nonaktif</option>
          </select>
        </div>
        <div class="col-md-3">
          <select name="verifikasi" class="form-select" style="border-radius:.6rem;border-color:#e4e9f0;font-size:.83rem;">
            <option value="">Semua verifikasi</option>
            <option value="belum"     @selected(request('verifikasi')==='belum')>Belum Upload</option>
            <option value="pending"   @selected(request('verifikasi')==='pending')>Menunggu Review</option>
            <option value="disetujui" @selected(request('verifikasi')==='disetujui')>Terverifikasi</option>
            <option value="ditolak"   @selected(request('verifikasi')==='ditolak')>Ditolak</option>
          </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
          <button type="submit"
            style="flex:1;background:linear-gradient(135deg,#e8401c,#ff7043);border:none;color:#fff;font-size:.83rem;font-weight:600;padding:.5rem .8rem;border-radius:.6rem;cursor:pointer;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="margin-right:4px;vertical-align:-1px;">
              <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>Filter
          </button>
          <a href="{{ route('admin.owners') }}"
            style="flex:1;background:#fff;border:1.5px solid #e4e9f0;color:#666;font-size:.83rem;font-weight:600;padding:.5rem .8rem;border-radius:.6rem;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:4px;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/>
            </svg>Reset
          </a>
        </div>
      </div>
    </form>
  </div>

  {{-- TABEL --}}
  <div class="table-responsive">
    <table class="table mb-0 align-middle" style="border-collapse:separate;border-spacing:0;">
      <thead>
        <tr style="background:#f8fafd;">
          <th style="font-size:.68rem;font-weight:700;color:#8fa3b8;padding:.75rem 1.1rem;border:0;white-space:nowrap;">OWNER</th>
          <th style="font-size:.68rem;font-weight:700;color:#8fa3b8;padding:.75rem 1rem;border:0;">KONTAK</th>
          <th style="font-size:.68rem;font-weight:700;color:#8fa3b8;padding:.75rem 1rem;border:0;">STATUS</th>
          <th style="font-size:.68rem;font-weight:700;color:#8fa3b8;padding:.75rem 1rem;border:0;">VERIFIKASI</th>
          <th style="font-size:.68rem;font-weight:700;color:#8fa3b8;padding:.75rem 1rem;border:0;text-align:center;">KOS</th>
          <th style="font-size:.68rem;font-weight:700;color:#8fa3b8;padding:.75rem 1rem;border:0;">TERDAFTAR</th>
          <th style="font-size:.68rem;font-weight:700;color:#8fa3b8;padding:.75rem 1rem;border:0;">AKSI</th>
        </tr>
      </thead>
      <tbody>
        @forelse($owners as $owner)
        @php
          $jumlahKos = $kostCountByOwner[$owner->id] ?? 0;
          $v = $owner->status_verifikasi_identitas ?? 'belum';
          $vCfg = match($v) {
            'disetujui' => ['bg'=>'#f0fdf4','color'=>'#15803d','border'=>'#bbf7d0','dot'=>'#16a34a','label'=>'Terverifikasi'],
            'pending'   => ['bg'=>'#fffbeb','color'=>'#b45309','border'=>'#fde68a','dot'=>'#f59e0b','label'=>'Menunggu Review'],
            'ditolak'   => ['bg'=>'#fef2f2','color'=>'#b91c1c','border'=>'#fecaca','dot'=>'#dc2626','label'=>'Ditolak'],
            default     => ['bg'=>'#f8fafd','color'=>'#64748b','border'=>'#e4e9f0','dot'=>'#94a3b8','label'=>'Belum Upload'],
          };
          $colors = ['#e8401c','#0284c7','#7c3aed','#0f766e','#d97706','#be185d'];
          $avatarColor = $colors[crc32($owner->name) % count($colors)];
          if ($avatarColor < 0) $avatarColor = $colors[abs(crc32($owner->name)) % count($colors)];
        @endphp
        <tr style="border-color:#f5f7fa;transition:background .12s;" onmouseover="this.style.background='#fafbfd'" onmouseout="this.style.background=''">

          {{-- OWNER --}}
          <td style="padding:.8rem 1.1rem;">
            <div style="display:flex;align-items:center;gap:.8rem;">
              @if($owner->foto_profil)
                <img src="{{ asset('storage/'.$owner->foto_profil) }}"
                  style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid #ffd0c0;flex-shrink:0;">
              @else
                <div style="width:40px;height:40px;border-radius:50%;background:{{ $avatarColor }};color:#fff;font-weight:800;font-size:.9rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;letter-spacing:0;">
                  {{ strtoupper(substr($owner->name,0,1)) }}
                </div>
              @endif
              <div>
                <div style="font-size:.85rem;font-weight:700;color:#1e2d3d;">{{ $owner->name }}</div>
                <div style="font-size:.72rem;color:#8fa3b8;margin-top:1px;">{{ $owner->email }}</div>
              </div>
            </div>
          </td>

          {{-- KONTAK --}}
          <td style="padding:.8rem 1rem;">
            <div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem;color:#4b5563;">
              <svg width="13" height="13" fill="none" stroke="#8fa3b8" stroke-width="2" viewBox="0 0 24 24">
                <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>
              </svg>
              {{ $owner->no_hp ?? '-' }}
            </div>
          </td>

          {{-- STATUS --}}
          <td style="padding:.8rem 1rem;">
            @if($owner->status_akun === 'aktif')
              <span style="display:inline-flex;align-items:center;gap:.35rem;background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;font-size:.72rem;font-weight:700;padding:.28rem .75rem;border-radius:999px;">
                <span style="width:6px;height:6px;border-radius:50%;background:#16a34a;display:inline-block;"></span>Aktif
              </span>
            @else
              <span style="display:inline-flex;align-items:center;gap:.35rem;background:#f8fafd;color:#64748b;border:1px solid #e4e9f0;font-size:.72rem;font-weight:700;padding:.28rem .75rem;border-radius:999px;">
                <span style="width:6px;height:6px;border-radius:50%;background:#94a3b8;display:inline-block;"></span>Nonaktif
              </span>
            @endif
          </td>

          {{-- VERIFIKASI --}}
          <td style="padding:.8rem 1rem;">
            <span style="display:inline-flex;align-items:center;gap:.35rem;background:{{ $vCfg['bg'] }};color:{{ $vCfg['color'] }};border:1px solid {{ $vCfg['border'] }};font-size:.72rem;font-weight:700;padding:.28rem .75rem;border-radius:999px;white-space:nowrap;">
              <span style="width:6px;height:6px;border-radius:50%;background:{{ $vCfg['dot'] }};display:inline-block;"></span>
              {{ $vCfg['label'] }}
            </span>
          </td>

          {{-- JUMLAH KOS --}}
          <td style="padding:.8rem 1rem;text-align:center;">
            <div style="display:inline-flex;flex-direction:column;align-items:center;gap:1px;">
              <span style="font-size:1rem;font-weight:800;color:#1e2d3d;">{{ $jumlahKos }}</span>
              <span style="font-size:.65rem;color:#8fa3b8;">unit</span>
            </div>
          </td>

          {{-- TERDAFTAR --}}
          <td style="padding:.8rem 1rem;">
            <div style="font-size:.82rem;color:#4b5563;font-weight:500;">{{ $owner->created_at?->format('d M Y') }}</div>
            <div style="font-size:.72rem;color:#8fa3b8;">{{ $owner->created_at?->format('H:i') }}</div>
          </td>

          {{-- AKSI --}}
          <td style="padding:.8rem 1rem;">
            <div style="display:flex;gap:.4rem;flex-wrap:wrap;align-items:center;">

              {{-- Detail --}}
              <a href="{{ route('admin.owners.show', $owner) }}"
                style="display:inline-flex;align-items:center;gap:.3rem;background:#fff5f2;color:#e8401c;border:1px solid #ffd0c0;font-size:.73rem;font-weight:600;padding:.3rem .75rem;border-radius:.45rem;text-decoration:none;white-space:nowrap;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Detail
              </a>

              {{-- Toggle Status --}}
              <form method="POST" action="{{ route('admin.owners.toggle-status', $owner) }}" id="formToggle-{{ $owner->id }}" style="display:none;">
                @csrf @method('PATCH')
              </form>
              <button type="button"
                onclick="bukaModalToggle({{ $owner->id }}, '{{ addslashes($owner->name) }}', '{{ $owner->status_akun }}')"
                style="display:inline-flex;align-items:center;gap:.3rem;background:#fffbeb;color:#b45309;border:1px solid #fde68a;font-size:.73rem;font-weight:600;padding:.3rem .75rem;border-radius:.45rem;cursor:pointer;white-space:nowrap;">
                @if($owner->status_akun === 'aktif')
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                  Nonaktifkan
                @else
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                  Aktifkan
                @endif
              </button>

              {{-- Hapus --}}
              <form method="POST" action="{{ route('admin.owners.destroy', $owner) }}" id="formHapus-{{ $owner->id }}" style="display:none;">
                @csrf @method('DELETE')
              </form>
              <button type="button"
                onclick="bukaModalHapus({{ $owner->id }}, '{{ addslashes($owner->name) }}')"
                style="display:inline-flex;align-items:center;gap:.3rem;background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;font-size:.73rem;font-weight:600;padding:.3rem .75rem;border-radius:.45rem;cursor:pointer;white-space:nowrap;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                Hapus
              </button>

            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:3rem;color:#8fa3b8;">
            <svg width="40" height="40" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24" style="display:block;margin:0 auto .75rem;">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <div style="font-size:.85rem;font-weight:600;color:#94a3b8;">Tidak ada owner ditemukan</div>
            <div style="font-size:.75rem;color:#b0bec5;margin-top:.3rem;">Coba ubah filter pencarian</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- PAGINATION --}}
  @if($owners->hasPages())
  <div style="padding:.9rem 1.25rem;border-top:1px solid #f0f3f8;">
    {{ $owners->links() }}
  </div>
  @endif

</div>


{{-- ===== MODAL TOGGLE STATUS ===== --}}
<div class="modal fade" id="modalToggleStatus" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
    <div class="modal-content" style="border-radius:20px;border:none;box-shadow:0 24px 64px rgba(0,0,0,.18);overflow:hidden;">
      <div style="padding:32px 28px 20px;text-align:center;">
        <div id="mtIconRing" style="width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
          <svg id="mtIconSvg" width="28" height="28" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"></svg>
        </div>
        <h5 id="mtJudul" style="font-size:1rem;font-weight:700;color:#1e2d3d;margin:0 0 8px;"></h5>
        <p id="mtDesc" style="font-size:.83rem;color:#6b7a8d;line-height:1.65;margin:0;"></p>
        <div style="display:inline-flex;align-items:center;gap:8px;background:#f8fafd;border:1px solid #e4e9f0;border-radius:999px;padding:6px 14px 6px 8px;margin-top:14px;">
          <div id="mtAvatar" style="width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;"></div>
          <span id="mtNama" style="font-size:.83rem;font-weight:600;color:#1e2d3d;"></span>
        </div>
      </div>
      <hr style="margin:0;border-color:#f0f3f8;">
      <div style="padding:18px 28px;display:flex;gap:10px;">
        <button type="button" data-bs-dismiss="modal"
          style="flex:1;padding:11px;border-radius:10px;border:1.5px solid #e4e9f0;background:#fff;color:#555;font-size:.85rem;font-weight:600;cursor:pointer;">
          Batal
        </button>
        <button type="button" id="mtBtnKonfirm"
          style="flex:1.4;padding:11px;border-radius:10px;border:none;color:#fff;font-size:.85rem;font-weight:600;cursor:pointer;">
          Konfirmasi
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ===== MODAL HAPUS ===== --}}
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
    <div class="modal-content" style="border-radius:20px;border:none;box-shadow:0 24px 64px rgba(0,0,0,.18);overflow:hidden;">
      <div style="padding:32px 28px 20px;text-align:center;">
        <div style="width:64px;height:64px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
          <svg width="28" height="28" fill="none" stroke="#b91c1c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
          </svg>
        </div>
        <h5 style="font-size:1rem;font-weight:700;color:#1e2d3d;margin:0 0 8px;">Hapus owner ini?</h5>
        <p style="font-size:.83rem;color:#6b7a8d;line-height:1.65;margin:0;">Aksi ini <strong style="color:#b91c1c;">tidak dapat dibatalkan</strong>. Semua data kos milik owner ini akan ikut terhapus permanen.</p>
        <div style="display:inline-flex;align-items:center;gap:8px;background:#f8fafd;border:1px solid #e4e9f0;border-radius:999px;padding:6px 14px 6px 8px;margin-top:14px;">
          <div id="mhAvatar" style="width:28px;height:28px;border-radius:50%;background:#b91c1c;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;"></div>
          <span id="mhNama" style="font-size:.83rem;font-weight:600;color:#1e2d3d;"></span>
        </div>
      </div>
      <hr style="margin:0;border-color:#f0f3f8;">
      <div style="padding:18px 28px;display:flex;gap:10px;">
        <button type="button" data-bs-dismiss="modal"
          style="flex:1;padding:11px;border-radius:10px;border:1.5px solid #e4e9f0;background:#fff;color:#555;font-size:.85rem;font-weight:600;cursor:pointer;">
          Batal
        </button>
        <button type="button" id="mhBtnHapus"
          style="flex:1.4;padding:11px;border-radius:10px;border:none;background:#b91c1c;color:#fff;font-size:.85rem;font-weight:600;cursor:pointer;">
          Ya, hapus permanen
        </button>
      </div>
    </div>
  </div>
</div>

<script>
let _toggleFormId = null;
let _hapusFormId  = null;

function bukaModalToggle(id, nama, status) {
  _toggleFormId = 'formToggle-' + id;
  const iconRing   = document.getElementById('mtIconRing');
  const iconSvg    = document.getElementById('mtIconSvg');
  const judul      = document.getElementById('mtJudul');
  const desc       = document.getElementById('mtDesc');
  const avatar     = document.getElementById('mtAvatar');
  const namaEl     = document.getElementById('mtNama');
  const btnKonfirm = document.getElementById('mtBtnKonfirm');

  namaEl.textContent = nama;
  avatar.textContent = nama.charAt(0).toUpperCase();

  if (status === 'aktif') {
    iconRing.style.background   = '#FCEBEB';
    iconSvg.setAttribute('stroke', '#A32D2D');
    iconSvg.innerHTML           = '<circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>';
    judul.textContent           = 'Nonaktifkan owner ini?';
    desc.textContent            = 'Owner tidak akan bisa login dan mengelola kos selama akun dinonaktifkan.';
    avatar.style.background     = '#e8401c';
    btnKonfirm.textContent      = 'Ya, nonaktifkan';
    btnKonfirm.style.background = '#A32D2D';
  } else {
    iconRing.style.background   = '#EAF3DE';
    iconSvg.setAttribute('stroke', '#3B6D11');
    iconSvg.innerHTML           = '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>';
    judul.textContent           = 'Aktifkan owner ini?';
    desc.textContent            = 'Owner akan bisa login dan mengelola kos kembali setelah diaktifkan.';
    avatar.style.background     = '#16a34a';
    btnKonfirm.textContent      = 'Ya, aktifkan';
    btnKonfirm.style.background = '#3B6D11';
  }
  new bootstrap.Modal(document.getElementById('modalToggleStatus')).show();
}

function bukaModalHapus(id, nama) {
  _hapusFormId = 'formHapus-' + id;
  document.getElementById('mhNama').textContent    = nama;
  document.getElementById('mhAvatar').textContent  = nama.charAt(0).toUpperCase();
  new bootstrap.Modal(document.getElementById('modalHapus')).show();
}

document.getElementById('mtBtnKonfirm').addEventListener('click', () => {
  if (_toggleFormId) document.getElementById(_toggleFormId).submit();
});

document.getElementById('mhBtnHapus').addEventListener('click', () => {
  if (_hapusFormId) document.getElementById(_hapusFormId).submit();
});
</script>

@endsection