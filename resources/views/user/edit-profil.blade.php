@extends('layouts.user-sidebar')

@section('title', 'Edit Profil')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
  .info-banner { background:#e8f4fd; border:1px solid #bee3f8; border-radius:.65rem; padding:.75rem 1rem; display:flex; align-items:center; gap:.6rem; font-size:.82rem; color:#2b6cb0; margin-bottom:1.5rem; }
  .info-banner i { font-size:1rem; flex-shrink:0; }
  .foto-wrap { display:flex; flex-direction:column; align-items:center; margin-bottom:1.8rem; }
  .foto-avatar { width:88px; height:88px; border-radius:50%; background:#e8401c; color:#fff; font-weight:800; font-size:2rem; display:flex; align-items:center; justify-content:center; overflow:hidden; margin-bottom:.5rem; border:3px solid #fff; box-shadow:0 4px 16px rgba(232,64,28,.2); cursor:pointer; position:relative; }
  .foto-avatar img { width:88px; height:88px; object-fit:cover; border-radius:50%; }
  .foto-avatar-overlay { position:absolute; inset:0; background:rgba(0,0,0,.35); border-radius:50%; display:flex; align-items:center; justify-content:center; opacity:0; transition:opacity .2s; }
  .foto-avatar:hover .foto-avatar-overlay { opacity:1; }
  .foto-avatar-overlay i { color:#fff; font-size:1.2rem; }
  .foto-label { font-size:.8rem; color:#e8401c; font-weight:600; cursor:pointer; }
  .form-section { background:#fff; border-radius:.85rem; border:1px solid #e4e9f0; overflow:hidden; margin-bottom:1rem; }
  .form-section-title { font-size:.72rem; font-weight:700; color:#8fa3b8; letter-spacing:.08em; padding:.75rem 1.2rem .4rem; background:#f8fafd; border-bottom:1px solid #f0f3f8; }
  .form-row-item { display:flex; align-items:center; padding:.85rem 1.2rem; border-bottom:1px solid #f8fafd; gap:1rem; }
  .form-row-item:last-child { border-bottom:0; }
  .form-row-label { width:180px; flex-shrink:0; font-size:.84rem; font-weight:600; color:#374151; }
  .form-row-label .wajib { font-size:.68rem; color:#ea580c; font-weight:400; display:block; margin-top:.1rem; }
  .form-row-input { flex:1; }
  .form-row-input input { width:100%; border:1px solid #e4e9f0; border-radius:.5rem; padding:.5rem .75rem; font-size:.84rem; color:#374151; background:#f8fafd; font-family:'Plus Jakarta Sans',sans-serif; outline:none; transition:border .2s; }
  .form-row-input input:focus { border-color:#e8401c; background:#fff; }
  .form-row-input input[type="date"] { color:#374151; }
  .btn-simpan { background:#e8401c; color:#fff; font-weight:700; border:0; border-radius:.6rem; padding:.65rem 2rem; font-size:.9rem; cursor:pointer; transition:background .2s; }
  .btn-simpan:hover { background:#cb3518; }
  .btn-batal { background:#fff; color:#374151; font-weight:600; border:1px solid #e4e9f0; border-radius:.6rem; padding:.65rem 1.5rem; font-size:.9rem; cursor:pointer; text-decoration:none; display:inline-block; }
  .btn-batal:hover { background:#f8fafd; color:#1a2332; }

  /* SELECT2 CUSTOM */
  .select2-container .select2-selection--single { height:38px !important; border:1px solid #e4e9f0 !important; border-radius:.5rem !important; background:#f8fafd !important; }
  .select2-container--default .select2-selection--single .select2-selection__rendered { line-height:38px !important; font-size:.84rem; color:#374151; padding-left:.75rem; }
  .select2-container--default .select2-selection--single .select2-selection__arrow { height:36px !important; }
  .select2-container--default.select2-container--focus .select2-selection--single { border-color:#e8401c !important; background:#fff !important; }
  .select2-dropdown { border:1px solid #e4e9f0 !important; border-radius:.5rem !important; font-size:.84rem; }
  .select2-container--default .select2-results__option--highlighted { background:#e8401c !important; }
  .select2-search--dropdown .select2-search__field { border:1px solid #e4e9f0 !important; border-radius:.4rem !important; padding:.4rem .6rem; font-size:.82rem; }
</style>
@endsection

@section('content')
<div style="max-width: 680px; margin: 0 auto;">

  <div class="info-banner">
    <i class="bi bi-info-circle-fill"></i>
    Pemilik kos lebih menyukai calon penyewa dengan profil yang jelas dan lengkap.
  </div>

  <form method="POST" action="{{ route('user.profil.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    {{-- FOTO --}}
    <div class="foto-wrap">
      <div class="foto-avatar" onclick="toggleFotoMenu(event)">
        @if(auth()->user()->foto_profil)
          <img src="{{ asset('storage/'.auth()->user()->foto_profil) }}" alt="foto" id="fotoPreview">
        @else
          <span id="fotoInitial">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span>
          <img src="" alt="" id="fotoPreview" style="display:none;">
        @endif
        <div class="foto-avatar-overlay"><i class="bi bi-camera"></i></div>
      </div>
      <label class="foto-label" onclick="toggleFotoMenu(event)">Ubah Foto</label>
      <input type="file" id="fotoInput" name="foto_profil" accept="image/*" style="display:none;" onchange="previewFoto(this)">

      {{-- POPUP MENU --}}
      <div id="fotoMenu" style="display:none;position:absolute;background:#fff;border:1px solid #e4e9f0;border-radius:.75rem;box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:999;overflow:hidden;min-width:180px;margin-top:.3rem;">
        <button type="button" onclick="bukaPilihFoto()" style="width:100%;padding:.7rem 1rem;border:0;background:#fff;text-align:left;font-size:.83rem;font-weight:600;color:#374151;cursor:pointer;display:flex;align-items:center;gap:.6rem;transition:background .15s;" onmouseover="this.style.background='#f8fafd'" onmouseout="this.style.background='#fff'">
          <i class="bi bi-camera" style="color:#e8401c;"></i> Ubah Foto
        </button>
        @if(auth()->user()->foto_profil)
        <button type="button" onclick="hapusFotoProfil()" style="width:100%;padding:.7rem 1rem;border:0;border-top:1px solid #f0f3f8;background:#fff;text-align:left;font-size:.83rem;font-weight:600;color:#dc2626;cursor:pointer;display:flex;align-items:center;gap:.6rem;transition:background .15s;" onmouseover="this.style.background='#fff5f5'" onmouseout="this.style.background='#fff'">
          <i class="bi bi-trash"></i> Hapus Foto
        </button>
        @endif
      </div>
    </div>

    {{-- INFORMASI PRIBADI --}}
    <div class="form-section">
      <div class="form-section-title">INFORMASI PRIBADI</div>
      <div class="form-row-item">
        <div class="form-row-label">Nama Lengkap</div>
        <div class="form-row-input"><input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" placeholder="Masukkan nama lengkap"></div>
      </div>
      <div class="form-row-item">
        <div class="form-row-label">Jenis Kelamin<span class="wajib">Wajib diisi</span></div>
        <div class="form-row-input">
          <select name="jenis_kelamin" id="selJK">
            <option value="">Pilih jenis kelamin</option>
            <option value="laki-laki" {{ auth()->user()->jenis_kelamin=='laki-laki'?'selected':'' }}>Laki-laki</option>
            <option value="perempuan" {{ auth()->user()->jenis_kelamin=='perempuan'?'selected':'' }}>Perempuan</option>
          </select>
        </div>
      </div>
      <div class="form-row-item">
        <div class="form-row-label">Tanggal Lahir<span class="wajib">Wajib diisi</span></div>
        <div class="form-row-input"><input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', auth()->user()->tanggal_lahir) }}"></div>
      </div>
      <div class="form-row-item">
        <div class="form-row-label">Nomor HP</div>
        <div class="form-row-input">
          <input type="text" name="no_hp" value="{{ old('no_hp', auth()->user()->no_hp) }}" placeholder="+62 xxxx xxxx">
          @error('no_hp')
            <div style="color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="form-row-item">
        <div class="form-row-label">Nomor Kontak Darurat</div>
        <div class="form-row-input">
          <input type="text" name="kontak_darurat" value="{{ old('kontak_darurat', auth()->user()->kontak_darurat) }}" placeholder="+62 xxxx xxxx">
          @error('kontak_darurat')
            <div style="color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>

    {{-- PEKERJAAN & PENDIDIKAN --}}
    <div class="form-section">
      <div class="form-section-title">PEKERJAAN & PENDIDIKAN</div>
      <div class="form-row-item">
        <div class="form-row-label">Pekerjaan<span class="wajib">Wajib diisi</span></div>
        <div class="form-row-input">
          <select name="pekerjaan" id="selPekerjaan">
            <option value="">Pilih pekerjaan</option>
            <option value="mahasiswa" {{ auth()->user()->pekerjaan=='mahasiswa'?'selected':'' }}>Mahasiswa</option>
            <option value="pelajar" {{ auth()->user()->pekerjaan=='pelajar'?'selected':'' }}>Pelajar</option>
            <option value="karyawan" {{ auth()->user()->pekerjaan=='karyawan'?'selected':'' }}>Karyawan Swasta</option>
            <option value="pns" {{ auth()->user()->pekerjaan=='pns'?'selected':'' }}>PNS</option>
            <option value="wirausaha" {{ auth()->user()->pekerjaan=='wirausaha'?'selected':'' }}>Wirausaha</option>
            <option value="freelancer" {{ auth()->user()->pekerjaan=='freelancer'?'selected':'' }}>Freelancer</option>
            <option value="lainnya" {{ auth()->user()->pekerjaan=='lainnya'?'selected':'' }}>Lainnya</option>
          </select>
          <input type="text" name="pekerjaan_lainnya" id="inputPekerjaanLainnya" placeholder="Tulis pekerjaan kamu..." style="display:none;margin-top:.5rem;">
        </div>
      </div>
      <div class="form-row-item" id="rowInstansi" style="{{ in_array(auth()->user()->pekerjaan, ['mahasiswa','pelajar']) ? '' : 'display:none;' }}">
        <div class="form-row-label">Nama Instansi/Kampus/Sekolah</div>
        <div class="form-row-input">
          <select name="instansi" id="selInstansi">
            <option value="">Pilih nama instansi</option>
            <option value="ITS" {{ auth()->user()->instansi=='ITS'?'selected':'' }}>ITS Surabaya</option>
            <option value="UNAIR" {{ auth()->user()->instansi=='UNAIR'?'selected':'' }}>UNAIR</option>
            <option value="lainnya" {{ auth()->user()->instansi=='lainnya'?'selected':'' }}>Lainnya</option>
          </select>
          <input type="text" name="instansi_lainnya" id="inputInstansiLainnya" placeholder="Tulis nama instansi..." style="display:none;margin-top:.5rem;">
        </div>
      </div>
      <div class="form-row-item">
        <div class="form-row-label">Status</div>
        <div class="form-row-input">
          <select name="status_pernikahan" id="selStatus">
            <option value="">Pilih status</option>
            <option value="lajang" {{ auth()->user()->status_pernikahan=='lajang'?'selected':'' }}>Lajang</option>
            <option value="menikah" {{ auth()->user()->status_pernikahan=='menikah'?'selected':'' }}>Menikah</option>
          </select>
        </div>
      </div>
    </div>

    {{-- LOKASI --}}
    <div class="form-section">
      <div class="form-section-title">LOKASI</div>
      <div class="form-row-item">
        <div class="form-row-label">Kota Asal</div>
        <div class="form-row-input">
          <select name="kota" id="selKota">
            <option value="">Pilih kota asal</option>
            <option value="Surabaya" {{ auth()->user()->kota=='Surabaya'?'selected':'' }}>Surabaya</option>
            <option value="Sidoarjo" {{ auth()->user()->kota=='Sidoarjo'?'selected':'' }}>Sidoarjo</option>
            <option value="Kediri" {{ auth()->user()->kota=='Kediri'?'selected':'' }}>Kediri</option>
          </select>
          <input type="text" name="kota_lainnya" id="inputKotaLainnya" placeholder="Tulis kota..." style="display:none;margin-top:.5rem;">
        </div>
      </div>
    </div>

    <div class="d-flex gap-2 justify-content-end mt-3">
      <a href="{{ route('user.profil') }}" class="btn-batal">Batal</a>
      <button type="submit" class="btn-simpan">Simpan</button>
    </div>

  </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
  const tagsOpt = { width: '100%', allowClear: true, tags: true, createTag: function(p){ return { id: p.term, text: p.term }; } };
  $('#selKota').select2({ ...tagsOpt, placeholder: 'Pilih atau ketik kota asal' });
  $('#selInstansi').select2({ ...tagsOpt, placeholder: 'Pilih atau ketik kampus/sekolah' });
  $('#selPekerjaan').select2({ ...tagsOpt, placeholder: 'Pilih atau ketik pekerjaan' });
  $('#selJK').select2({ ...tagsOpt, placeholder: 'Pilih jenis kelamin' });
  $('#selStatus').select2({ placeholder: 'Pilih status', width: '100%', allowClear: true });
});

function previewFoto(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('fotoPreview').src = e.target.result;
      document.getElementById('fotoPreview').style.display = 'block';
      if(document.getElementById('fotoInitial')) document.getElementById('fotoInitial').style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function handleLainnya(selectId, inputId) {
  $('#' + selectId).on('change', function() {
    if($(this).val() === 'lainnya' || $(this).val() === 'Lainnya') $('#' + inputId).show().focus();
    else $('#' + inputId).hide().val('');
  });
}
handleLainnya('selKota', 'inputKotaLainnya');
handleLainnya('selInstansi', 'inputInstansiLainnya');
handleLainnya('selPekerjaan', 'inputPekerjaanLainnya');

$('#selPekerjaan').on('change', function() {
  if (['mahasiswa', 'pelajar'].includes($(this).val())) $('#rowInstansi').show();
  else { $('#rowInstansi').hide(); $('#selInstansi').val(null).trigger('change'); }
});

function toggleFotoMenu(e) { e.stopPropagation(); e.preventDefault(); $('#fotoMenu').toggle(); }
$(document).on('click', function(e) { if(!$(e.target).closest('#fotoMenu, .foto-avatar, .foto-label').length) $('#fotoMenu').hide(); });

function bukaPilihFoto() { $('#fotoMenu').hide(); setTimeout(() => $('#fotoInput').click(), 100); }

function hapusFotoProfil() {
  $('#fotoMenu').hide();
  fetch('{{ route("user.profil.update") }}', {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
    body: JSON.stringify({ _method: 'PATCH', hapus_foto: 1 })
  }).then(() => location.reload());
}
</script>
@endsection