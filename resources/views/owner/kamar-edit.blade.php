@extends('layouts.owner')

@section('title', 'Edit Kamar')

@push('styles')
<style>
    .form-card { background:#fff; border-radius:1rem; border:1px solid var(--line); box-shadow:0 6px 20px rgba(0,0,0,.04); padding:1.5rem; margin-bottom:1.5rem; }
    .form-card h6 { font-weight:800; color:var(--dark); font-size:.95rem; margin-bottom:1.2rem; padding-bottom:.8rem; border-bottom:1px solid #f0f3f8; display:flex; align-items:center; gap:.5rem; }
    .form-label { font-size:.82rem; font-weight:700; color:#344054; margin-bottom:.4rem; }
    .hover-opacity-100:hover { opacity: 1 !important; transition: .2s; }
    .form-control, .form-select { font-size:.85rem; border-color:var(--line); border-radius:.75rem; padding:.65rem .9rem; min-height:46px; }
    .form-control:focus, .form-select:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(232,64,28,.1); }
    
    .preview-grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:1rem; margin-top:1.2rem; }
    .preview-card { border-radius:.8rem; overflow:hidden; border:1px solid var(--line); background:#fff; position:relative; }
    .preview-img { height:120px; width:100%; object-fit:cover; }
    .btn-remove { position:absolute; top:5px; right:5px; background:rgba(220,38,38,.9); color:#fff; border:none; border-radius:50%; width:24px; height:24px; display:flex; align-items:center; justify-content:center; font-size:.7rem; cursor:pointer; }

    .btn-submit { background:linear-gradient(135deg,#e8401c,#ff7043); color:#fff; font-weight:700; border:0; border-radius:.75rem; padding:.8rem 2rem; font-size:.9rem; cursor:pointer; box-shadow:0 6px 16px rgba(232,64,28,.2); transition:.2s; display:inline-flex; align-items:center; gap:.5rem; }
    .btn-submit:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(232,64,28,.3); }
</style>
@endpush

@section('content')
      <div class="d-flex align-items-center gap-2 mb-3" style="font-size:.82rem; color:var(--muted);">
        <a href="{{ route('owner.kamar.index') }}" style="color:var(--muted); text-decoration:none;">Kelola Kamar</a>
        <i class="bi bi-chevron-right" style="font-size:.7rem;"></i>
        <span style="color:var(--dark); font-weight:700;">Edit Kamar #{{ $kamar->nomor_kamar }}</span>
      </div>

      <form action="{{ route('owner.kamar.update', $kamar->id_room) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="row">
          <div class="col-lg-8">
            {{-- PILIH KOST --}}
            <div class="form-card">
              <h6><i class="bi bi-house" style="color:var(--primary)"></i> Properti Kost</h6>
              <div class="mb-3">
                <label class="form-label">Kost <span class="text-danger">*</span></label>
                <select name="kost_id" class="form-select @error('kost_id') is-invalid @enderror" required>
                  @foreach($kosts as $k)
                    <option value="{{ $k->id_kost }}" {{ $kamar->kost_id == $k->id_kost ? 'selected' : '' }}>{{ $k->nama_kost }}</option>
                  @endforeach
                </select>
                @error('kost_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- INFORMASI DASAR --}}
            <div class="form-card">
              <h6><i class="bi bi-info-circle" style="color:var(--primary)"></i> Informasi Kamar</h6>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Nomor/Nama Kamar <span class="text-danger">*</span></label>
                  <input type="text" name="nomor_kamar" class="form-control" value="{{ old('nomor_kamar', $kamar->nomor_kamar) }}" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Tipe Kamar</label>
                  @php $currentTipe = old('tipe_kamar', $kamar->tipe_kamar); @endphp
                  <select name="tipe_kamar" class="form-select">
                    <option value="">-- Pilih Tipe Kamar --</option>
                    <option value="Standard" {{ $currentTipe == 'Standard' ? 'selected' : '' }}>🏠 Standard — Paling dasar, harga terjangkau</option>
                    <option value="Menengah" {{ $currentTipe == 'Menengah' ? 'selected' : '' }}>🏡 Menengah — Fasilitas lebih lengkap</option>
                    <option value="Superior" {{ $currentTipe == 'Superior' ? 'selected' : '' }}>✨ Superior — Nyaman & premium</option>
                    <option value="Deluxe"   {{ $currentTipe == 'Deluxe'   ? 'selected' : '' }}>💎 Deluxe — Mewah & luas</option>
                    <option value="VIP"      {{ $currentTipe == 'VIP'      ? 'selected' : '' }}>👑 VIP — Paling eksklusif</option>
                    {{-- Fallback kalau data lama tidak ada di list --}}
                    @if($currentTipe && !in_array($currentTipe, ['Standard','Menengah','Superior','Deluxe','VIP']))
                      <option value="{{ $currentTipe }}" selected>{{ $currentTipe }}</option>
                    @endif
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Ukuran Kamar (PxL)</label>
                  <input type="text" name="ukuran" class="form-control" value="{{ old('ukuran', $kamar->ukuran) }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Info Listrik</label>
                  <select name="listrik" class="form-select">
                    <option value="Termasuk" {{ $kamar->listrik == 'Termasuk' ? 'selected' : '' }}>Termasuk Biaya Sewa</option>
                    <option value="Token Sendiri" {{ $kamar->listrik == 'Token Sendiri' ? 'selected' : '' }}>Token Sendiri (Bayar Terpisah)</option>
                    <option value="Tagihan Bulanan" {{ $kamar->listrik == 'Tagihan Bulanan' ? 'selected' : '' }}>Tagihan Bulanan Terpisah</option>
                  </select>
                </div>
              </div>
              <div class="mt-3">
                <label class="form-label">Deskripsi Kamar</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $kamar->deskripsi) }}</textarea>
              </div>
              <div class="mt-3">
                <label class="form-label"><i class="bi bi-clipboard-check me-1" style="color:var(--primary)"></i>Peraturan Kamar</label>
                <textarea name="aturan_kamar" class="form-control" rows="3" placeholder="Contoh: Tidak boleh merokok, jam malam 22.00, tamu dilarang menginap...">{{ old('aturan_kamar', $kamar->aturan_kamar) }}</textarea>
                <div style="font-size:.72rem;color:var(--muted);margin-top:.3rem;"><i class="bi bi-info-circle me-1"></i>Aturan khusus yang berlaku di kamar ini</div>
              </div>
            </div>

            {{-- FASILITAS KAMAR --}}
            <div class="form-card">
              <h6><i class="bi bi-check2-square" style="color:var(--primary)"></i> Fasilitas Kamar</h6>
              @php $fasilitasList = is_array($kamar->fasilitas) ? $kamar->fasilitas : []; @endphp
              @php
                $standardRoomFac = [
                  'Kasur/Springbed','Bantal','Guling',
                  'AC','Kipas Angin',
                  'Kamar Mandi Dalam','Water Heater',
                  'Lemari Baju','Meja & Kursi Belajar',
                  'TV','WiFi Kamar','Stop Kontak',
                  'Jendela','Cermin','Gantungan Baju',
                  'Sapu & Pel','Tempat Sampah',
                  'Rak Sepatu','Lampu Belajar',
                ];
                $fasilitasKustomKamar = array_diff($fasilitasList, $standardRoomFac);
              @endphp
              <div class="row g-2" id="roomFacilityList">
                @foreach($standardRoomFac as $f)
                  <div class="col-6 col-md-4" id="rf-item-{{ $loop->index }}">
                    <div class="form-check p-2 border rounded-3 d-flex align-items-center justify-content-between pe-2" style="font-size:.8rem; background:#f8fafc;">
                      <div class="d-flex align-items-center">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="fasilitas[]" value="{{ $f }}"
                               id="f_{{ $loop->index }}"
                               {{ in_array($f, $fasilitasList) ? 'checked' : '' }}>
                        <label class="form-check-label" for="f_{{ $loop->index }}">{{ $f }}</label>
                      </div>
                      <button type="button" class="btn btn-link text-danger p-0 ms-2 opacity-50 hover-opacity-100" onclick="document.getElementById('rf-item-{{ $loop->index }}').remove()" title="Hapus dari daftar">
                        <i class="bi bi-x-circle"></i>
                      </button>
                    </div>
                  </div>
                @endforeach
              </div>

              {{-- Section untuk input fasilitas kustom --}}
              <div class="mt-4 pt-3 border-top">
                <label class="form-label" style="font-size:.78rem;color:var(--muted);">Gak nemu fasilitas kamar? Tambah di sini:</label>
                <div class="input-group input-group-sm mb-3">
                  <input type="text" id="customFacInput" class="form-control" placeholder="Contoh: Kulkas Mini, Balkon..." style="border-radius:.6rem 0 0 .6rem;">
                  <button type="button" class="btn btn-primary px-3" onclick="addCustomFacility()" style="border-radius:0 .6rem .6rem 0;background:var(--primary);border:none;">
                    <i class="bi bi-plus-lg"></i> Tambah
                  </button>
                </div>
                
                <div id="customFacContainer" class="row g-2 mt-2">
                  @foreach($fasilitasKustomKamar as $fk)
                    <div class="col-6 col-md-4" id="custom-fac-old-{{ $loop->index }}">
                      <div class="form-check p-2 border rounded-3 d-flex align-items-center justify-content-between pe-2" style="font-size:.8rem; background:#fff7f5; border-color:#ffd2c7;">
                        <div class="d-flex align-items-center">
                          <input class="form-check-input ms-0 me-2" type="checkbox" name="fasilitas[]" value="{{ $fk }}" id="cf_old_{{ $loop->index }}" checked>
                          <label class="form-check-label" for="cf_old_{{ $loop->index }}">{{ $fk }}</label>
                        </div>
                        <button type="button" class="btn btn-link text-danger p-0 ms-2" onclick="document.getElementById('custom-fac-old-{{ $loop->index }}').remove()">
                          <i class="bi bi-x-circle-fill"></i>
                        </button>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>

            {{-- FOTO KAMAR --}}
            <div class="form-card">
              <h6><i class="bi bi-camera" style="color:var(--primary)"></i> Foto Kamar</h6>
              
              {{-- Foto Saat Ini --}}
              <div class="mb-3">
                <label class="form-label d-block small text-muted">Foto Saat Ini (Klik sampah untuk menghapus)</label>
                <div class="preview-grid">
                  @foreach($fotoKamar as $img)
                    <div class="preview-card">
                      <div class="preview-img-wrap">
                        <img src="{{ asset('storage/' . $img->foto_path) }}">
                        @if($img->is_utama)
                          <div class="badge-cover"><i class="bi bi-star-fill"></i> Utama</div>
                        @endif
                     <button type="button" class="btn-remove"
  onclick="hapusFotoExisting(this, {{ $img->id }})">
  <i class="bi bi-trash"></i>
</button>
<input type="checkbox" name="hapus_foto_ids[]" 
  value="{{ $img->id }}" 
  id="del_img_{{ $img->id }}"
  style="display:none;">
                      </div>
                      <div class="preview-info">
                        <input type="text" name="existing_foto_judul[{{ $img->id }}]" 
                               class="preview-label-input" 
                               placeholder="Nama foto, contoh: Ruang Kamar"
                               value="{{ $img->judul }}">
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label d-block small text-muted">Upload Foto Baru</label>
                <input type="file" name="foto_kamar[]" id="foto_kamar" class="form-control" multiple accept="image/*" onchange="previewImages(this, 'previewGridNew')">
                <div id="judulFotoInputs"></div>
                <div id="previewGridNew" class="preview-grid"></div>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            {{-- STATUS etc --}}
            <div class="form-card">
              <h6><i class="bi bi-cash-stack" style="color:var(--primary)"></i> Harga Sewa</h6>
              <div class="mb-3">
                <div class="form-check form-switch mb-2">
                  <input class="form-check-input" type="checkbox" name="aktif_bulanan" id="aktif_bulanan" {{ $kamar->aktif_bulanan ? 'checked' : '' }}>
                  <label class="form-check-label fw-bold" for="aktif_bulanan">Bisa Sewa Bulanan</label>
                </div>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input type="number" name="harga_per_bulan" class="form-control" value="{{ $kamar->harga_per_bulan }}">
                </div>
              </div>
              <div class="mb-3">
                <div class="form-check form-switch mb-2">
                  <input class="form-check-input" type="checkbox" name="aktif_harian" id="aktif_harian" {{ $kamar->aktif_harian ? 'checked' : '' }}>
                  <label class="form-check-label fw-bold" for="aktif_harian">Bisa Sewa Harian</label>
                </div>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input type="number" name="harga_harian" class="form-control" value="{{ $kamar->harga_harian }}">
                </div>
              </div>
            </div>

            <div class="form-card">
              <h6><i class="bi bi-toggle-on" style="color:var(--primary)"></i> Status Kamar</h6>
              <select name="status_kamar" class="form-select">
                <option value="tersedia" {{ $kamar->status_kamar == 'tersedia' ? 'selected' : '' }}>Tersedia (Kosong)</option>
                <option value="terisi" {{ $kamar->status_kamar == 'terisi' ? 'selected' : '' }}>Terisi (Penuh)</option>
              </select>
            </div>

            <button type="submit" class="btn-submit w-100 mb-4">
              <i class="bi bi-check-lg"></i> Simpan Perubahan
            </button>
            <a href="{{ route('owner.kamar.index') }}" class="btn btn-outline-secondary w-100 rounded-3 py-2 fw-bold">Batal</a>
          </div>
        </div>
      </form>
@endsection

@push('scripts')
<script>
    window._judulFotoValues = {};

    function previewImages(input, gridId) {
      const grid = document.getElementById(gridId);
      const judulContainer = document.getElementById('judulFotoInputs');
      grid.innerHTML = '';
      judulContainer.innerHTML = '';
      
      if (input.files) {
        Array.from(input.files).forEach((file, i) => {
          const reader = new FileReader();
          reader.onload = function(e) {
            const card = document.createElement('div');
            card.className = 'preview-card';
            card.innerHTML = `
              <div class="preview-img-wrap">
                <img src="${e.target.result}">
              </div>
              <div class="preview-info">
                <input type="text" 
                       placeholder="Nama foto, contoh: Ruang Kamar" 
                       class="preview-label-input" 
                       value="${window._judulFotoValues[i] || ''}"
                       oninput="syncJudulFoto(${i}, this.value)">
              </div>
            `;
            grid.appendChild(card);

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'foto_kamar_judul[]';
            hiddenInput.id = `hidden_judul_${i}`;
            hiddenInput.value = window._judulFotoValues[i] || '';
            judulContainer.appendChild(hiddenInput);
          }
          reader.readAsDataURL(file);
        });
      }
    }

    function syncJudulFoto(index, value) {
      window._judulFotoValues[index] = value;
      const hidden = document.getElementById(`hidden_judul_${index}`);
      if (hidden) hidden.value = value;
    }
    function hapusFotoExisting(btn, id) {
  // Centang checkbox hidden
  const cb = document.getElementById('del_img_' + id);
  if (cb) cb.checked = true;
  
  // Sembunyikan card foto
  const card = btn.closest('.preview-card');
  if (card) {
    card.style.opacity = '0.3';
    card.style.pointerEvents = 'none';
    
    // Tambah label "Akan dihapus"
    const label = document.createElement('div');
    label.style.cssText = 'position:absolute;inset:0;background:rgba(220,38,38,.15);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:#dc2626;border-radius:.8rem;';
    label.innerHTML = '<i class="bi bi-trash me-1"></i> Akan Dihapus';
    card.style.position = 'relative';
    card.appendChild(label);
  }
}

function addCustomFacility() {
  const input = document.getElementById('customFacInput');
  const val = input.value.trim();
  if (!val) return;

  // Cek apakah sudah ada di list checkbox standar
  const existingLabels = Array.from(document.querySelectorAll('.form-check-label')).map(l => l.textContent.trim().toLowerCase());
  if (existingLabels.includes(val.toLowerCase())) {
    alert('Fasilitas ini sudah ada di daftar!');
    input.value = '';
    return;
  }

  const container = document.getElementById('customFacContainer');
  const idx = Date.now();

  const col = document.createElement('div');
  col.className = 'col-6 col-md-4';
  col.id = 'custom-fac-' + idx;
  col.innerHTML = `
    <div class="form-check p-2 border rounded-3 d-flex align-items-center justify-content-between pe-2" style="font-size:.8rem; background:#fff7f5; border-color:#ffd2c7;">
      <div class="d-flex align-items-center">
        <input class="form-check-input ms-0 me-2" type="checkbox" name="fasilitas[]" value="${val}" id="cf_${idx}" checked>
        <label class="form-check-label" for="cf_${idx}">${val}</label>
      </div>
      <button type="button" class="btn btn-link text-danger p-0 ms-2" onclick="document.getElementById('custom-fac-${idx}').remove()">
        <i class="bi bi-x-circle-fill"></i>
      </button>
    </div>
  `;
  container.appendChild(col);
  input.value = '';
  input.focus();
}
</script>
@endpush
