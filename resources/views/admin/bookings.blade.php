@extends('admin.layout')

@section('title', 'Monitoring Booking')
@section('page_title', 'Monitoring Data Booking')
@section('page_subtitle', 'Pantau semua transaksi dan status pemesanan kamar real-time')

@push('styles')
<style>
  /* Fix giant pagination icons */
  .pagination svg {
    width: 20px !important;
    height: 20px !important;
    display: inline-block !important;
  }
  .pagination .flex.justify-between.flex-1 {
    display: none !important;
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

{{-- SUMMARY CARDS (Identical to Monitoring Owner) --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div style="background:#fff;border:1px solid #f0f3f8;border-radius:.9rem;padding:.9rem 1rem;display:flex;align-items:center;gap:.8rem;">
      <div style="width:42px;height:42px;background:#f5f3ff;border-radius:.65rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
      </div>
      <div>
        <div style="font-size:1.25rem;font-weight:800;color:#1e2d3d;line-height:1;">{{ $allBookings->count() }}</div>
        <div style="font-size:.72rem;color:#8fa3b8;margin-top:2px;">Total Booking</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div style="background:#fff;border:1px solid #f0f3f8;border-radius:.9rem;padding:.9rem 1rem;display:flex;align-items:center;gap:.8rem;">
      <div style="width:42px;height:42px;background:#fffbeb;border-radius:.65rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <div>
        <div style="font-size:1.25rem;font-weight:800;color:#1e2d3d;line-height:1;">{{ $allBookings->where('status_booking','pending')->count() }}</div>
        <div style="font-size:.72rem;color:#8fa3b8;margin-top:2px;">Pending</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div style="background:#fff;border:1px solid #f0f3f8;border-radius:.9rem;padding:.9rem 1rem;display:flex;align-items:center;gap:.8rem;">
      <div style="width:42px;height:42px;background:#f0fdf4;border-radius:.65rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
      <div>
        <div style="font-size:1.25rem;font-weight:800;color:#1e2d3d;line-height:1;">{{ $allBookings->where('status_booking','diterima')->count() }}</div>
        <div style="font-size:.72rem;color:#8fa3b8;margin-top:2px;">Diterima</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div style="background:#fff;border:1px solid #f0f3f8;border-radius:.9rem;padding:.9rem 1rem;display:flex;align-items:center;gap:.8rem;">
      <div style="width:42px;height:42px;background:#fff5f2;border-radius:.65rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#e8401c" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      </div>
      <div>
        <div style="font-size:1rem;font-weight:800;color:#1e2d3d;line-height:1;">Rp{{ number_format($allBookings->where('status_booking','selesai')->sum('komisi_admin'),0,',','.') }}</div>
        <div style="font-size:.72rem;color:#8fa3b8;margin-top:2px;">Komisi Admin</div>
      </div>
    </div>
  </div>
</div>

{{-- MAIN PANEL --}}
<div style="background:#fff;border:1px solid #f0f3f8;border-radius:1rem;overflow:hidden;">

  {{-- FILTER --}}
  <div style="padding:1.1rem 1.25rem;border-bottom:1px solid #f0f3f8;">
    <form method="GET" action="{{ route('admin.bookings') }}">
      <div class="row g-2">
        <div class="col-md-5">
          <div style="position:relative;">
            <svg width="14" height="14" fill="none" stroke="#8fa3b8" stroke-width="2" viewBox="0 0 24 24" style="position:absolute;left:.8rem;top:50%;transform:translateY(-50%);">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari penyewa, kost, atau kamar..." style="padding-left:2.2rem;border-radius:.6rem;border-color:#e4e9f0;font-size:.83rem;">
          </div>
        </div>
        <div class="col-md-4">
          <select name="status" class="form-select" style="border-radius:.6rem;border-color:#e4e9f0;font-size:.83rem;">
            <option value="semua">Semua Status Booking</option>
            <option value="pending"  @selected($activeStatus === 'pending')>⏳ Pending</option>
            <option value="diterima" @selected($activeStatus === 'diterima')>✅ Diterima</option>
            <option value="ditolak"  @selected($activeStatus === 'ditolak')>❌ Ditolak</option>
            <option value="selesai"  @selected($activeStatus === 'selesai')>🏁 Selesai</option>
          </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button type="submit" style="flex:1;background:linear-gradient(135deg,#e8401c,#ff7043);border:none;color:#fff;font-size:.83rem;font-weight:600;padding:.5rem .8rem;border-radius:.6rem;cursor:pointer;">
            <i class="bi bi-filter me-1"></i>Filter
          </button>
          <a href="{{ route('admin.bookings') }}" style="flex:1;background:#fff;border:1.5px solid #e4e9f0;color:#666;font-size:.83rem;font-weight:600;padding:.5rem .8rem;border-radius:.6rem;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:4px;">
            <i class="bi bi-arrow-counterclockwise"></i>Reset
          </a>
        </div>
      </div>
    </form>
  </div>

  {{-- TABLE --}}
  <div class="table-responsive">
    <table class="table mb-0 align-middle">
      <thead>
        <tr style="background:#f8fafd;">
          <th style="font-size:.68rem;font-weight:700;color:#8fa3b8;padding:.75rem 1.1rem;border:0;">PENYEWA</th>
          <th style="font-size:.68rem;font-weight:700;color:#8fa3b8;padding:.75rem 1rem;border:0;">KOST & KAMAR</th>
          <th style="font-size:.68rem;font-weight:700;color:#8fa3b8;padding:.75rem 1rem;border:0;">DURASI</th>
          <th style="font-size:.68rem;font-weight:700;color:#8fa3b8;padding:.75rem 1rem;border:0;">TOTAL</th>
          <th style="font-size:.68rem;font-weight:700;color:#8fa3b8;padding:.75rem 1rem;border:0;">STATUS</th>
          <th style="font-size:.68rem;font-weight:700;color:#8fa3b8;padding:.75rem 1rem;border:0;">AKSI</th>
        </tr>
      </thead>
      <tbody>
        @forelse($bookings as $booking)
        <tr style="border-color:#f5f7fa;transition:background .12s;" onmouseover="this.style.background='#fafbfd'" onmouseout="this.style.background=''">
          <td>
            <div style="display:flex;align-items:center;gap:.7rem;padding-left:.5rem;">
              <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg, #7c3aed, #9f67ff);color:#fff;font-weight:800;font-size:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                {{ strtoupper(substr($booking->user->name ?? 'U',0,1)) }}
              </div>
              <div>
                <div style="font-size:.82rem;font-weight:700;color:#1e2d3d;">{{ $booking->user->name ?? '-' }}</div>
                <div style="font-size:.7rem;color:#8fa3b8;">{{ $booking->created_at->format('d M Y') }}</div>
              </div>
            </div>
          </td>
          <td>
            <div style="font-size:.82rem;font-weight:600;color:#4b5563;">{{ $booking->room->kost->nama_kost ?? '-' }}</div>
            <div style="font-size:.72rem;color:#e8401c;font-weight:700;">Kamar {{ $booking->room->nomor_kamar ?? '-' }}</div>
          </td>
          <td>
            <div style="font-size:.82rem;font-weight:700;color:#1e2d3d;">{{ $booking->jumlah_durasi ?? $booking->durasi_sewa ?? '-' }} {{ ucfirst($booking->tipe_durasi ?? 'Bulan') }}</div>
            <div style="font-size:.7rem;color:#8fa3b8;">Mulai {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d M Y') }}</div>
          </td>
          <td>
            <div style="font-size:.85rem;font-weight:800;color:#1e2d3d;">Rp{{ number_format($booking->total_bayar,0,',','.') }}</div>
            <div style="font-size:.65rem;color:#16a34a;font-weight:700;">Adm: Rp{{ number_format($booking->komisi_admin,0,',','.') }}</div>
          </td>
          <td>
            @php
              $s = $booking->status_booking;
              $cfg = match($s) {
                'pending' => ['bg'=>'#fffbeb','color'=>'#b45309','dot'=>'#f59e0b'],
                'diterima' => ['bg'=>'#f0fdf4','color'=>'#15803d','dot'=>'#16a34a'],
                'ditolak' => ['bg'=>'#fef2f2','color'=>'#b91c1c','dot'=>'#dc2626'],
                default => ['bg'=>'#f1f5f9','color'=>'#475569','dot'=>'#94a3b8'],
              };
            @endphp
            <span style="display:inline-flex;align-items:center;gap:.35rem;background:{{ $cfg['bg'] }};color:{{ $cfg['color'] }};border:1px solid transparent;font-size:.7rem;font-weight:700;padding:.25rem .7rem;border-radius:999px;">
              <span style="width:6px;height:6px;border-radius:50%;background:{{ $cfg['dot'] }};display:inline-block;"></span>
              {{ ucfirst($s) }}
            </span>
          </td>
          <td>
            <a href="{{ route('admin.bookings.show', $booking->id_booking) }}" 
               style="display:inline-flex;align-items:center;gap:.3rem;background:#fff5f2;color:#e8401c;border:1px solid #ffd0c0;font-size:.72rem;font-weight:700;padding:.3rem .75rem;border-radius:.45rem;text-decoration:none;">
               <i class="bi bi-eye-fill"></i> Detail
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;padding:3rem;color:#8fa3b8;">
            <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:.5rem;opacity:.5;"></i>
            <div style="font-size:.85rem;font-weight:600;">Belum ada data booking</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- PAGINATION --}}
  @if($bookings->hasPages())
  <div style="padding:.9rem 1.25rem;border-top:1px solid #f0f3f8;display:flex;justify-content:flex-end;">
    {{ $bookings->withQueryString()->links() }}
  </div>
  @endif

</div>

@endsection
