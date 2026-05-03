@extends('layouts.owner')

@section('title', 'Edit Detail Masal')

@push('styles')
<style>
    :root {
        --glass: rgba(255, 255, 255, 0.95);
        --primary-gradient: linear-gradient(135deg, #e8401c, #ff7043);
    }
    .form-card { 
        background: var(--glass); 
        border-radius: 1.25rem; 
        border: 1px solid rgba(228, 233, 240, 0.8); 
        box-shadow: 0 8px 32px rgba(0,0,0,0.03); 
        padding: 1.75rem; 
        margin-bottom: 2rem;
        backdrop-filter: blur(10px);
    }
    .form-card h6 { 
        font-weight: 800; 
        color: var(--dark); 
        font-size: 1rem; 
        margin-bottom: 1.5rem; 
        padding-bottom: 1rem; 
        border-bottom: 1px solid #f0f3f8; 
        display: flex; 
        align-items: center; 
        gap: .75rem; 
    }
    .form-card h6 i {
        width: 32px;
        height: 32px;
        background: #fff5f2;
        color: #e8401c;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    .form-label { font-size: .85rem; font-weight: 700; color: #344054; margin-bottom: .5rem; }
    .form-control, .form-select { 
        font-size: .88rem; 
        border-color: #e2e8f0; 
        border-radius: .85rem; 
        padding: .75rem 1rem; 
        min-height: 48px;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus { 
        border-color: #e8401c; 
        box-shadow: 0 0 0 4px rgba(232, 64, 28, 0.1); 
    }
    .btn-submit { 
        background: var(--primary-gradient); 
        color: #fff; 
        font-weight: 700; 
        border: 0; 
        border-radius: 1rem; 
        padding: 1rem 2.5rem; 
        font-size: .95rem; 
        cursor: pointer; 
        box-shadow: 0 10px 25px rgba(232, 64, 28, 0.25); 
        transition: all 0.3s; 
        display: inline-flex; 
        align-items: center; 
        gap: .75rem; 
    }
    .btn-submit:hover { 
        transform: translateY(-3px); 
        box-shadow: 0 15px 30px rgba(232, 64, 28, 0.35); 
    }
    .room-tag { 
        background: #fff; 
        color: #e8401c; 
        padding: .35rem .85rem; 
        border-radius: .75rem; 
        font-size: .75rem; 
        font-weight: 800; 
        margin-right: .5rem;
        border: 1px solid #ff704333;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    
    /* Image Preview */
    .preview-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 1rem; margin-top: 1rem; }
    .preview-card { border-radius: 1rem; overflow: hidden; border: 1px solid #e2e8f0; background: #fff; position: relative; transition: .2s; }
    .preview-card:hover { transform: scale(1.02); }
    .preview-img-wrap { position: relative; height: 110px; width: 100%; overflow: hidden; }
    .preview-img { height: 100%; width: 100%; object-fit: cover; }
    .preview-info { padding: .6rem; }
    .preview-label-input { width: 100%; border: none; font-size: .7rem; font-weight: 600; outline: none; color: #475569; }

    .btn-remove { 
        position: absolute; top: 5px; right: 5px; 
        background: rgba(220, 38, 38, 0.9); color: #fff; 
        border: none; border-radius: 50%; 
        width: 22px; height: 22px; 
        display: flex; align-items: center; justify-content: center; 
        font-size: .7rem; cursor: pointer; z-index: 5;
    }

    .fac-item {
        border: 1px solid #eef2f6;
        border-radius: .85rem;
        padding: .75rem;
        transition: all 0.2s;
        background: #fff;
    }
    .fac-item:hover { border-color: #e8401c33; background: #fffaf9; }
</style>
@endpush

@section('content')
      <div class="d-flex align-items-center gap-2 mb-4" style="font-size: .88rem; color: #64748b;">
        <a href="{{ route('owner.kamar.index') }}" class="text-decoration-none fw-600" style="color: #64748b;">Kelola Kamar</a>
        <i class="bi bi-chevron-right" style="font-size: .75rem;"></i>
        <span style="color: #1e293b; font-weight: 800;">Edit Detail Masal ({{ $rooms->count() }} Kamar)</span>
      </div>

      <div class="form-card mb-4" style="background: linear-gradient(135deg, #fffbeb, #fff7ed); border-color: #fde68a;">
        <div class="d-flex gap-3">
            <div style="width:48px; height:48px; background:#fef3c7; color:#d97706; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink:0;">
                <i class="bi bi-magic"></i>
            </div>
            <div>
                <h6 class="border-0 mb-1 pb-0" style="background:none;">Update Detail Masal</h6>
                <p class="text-muted mb-3" style="font-size: .82rem;">
                    Informasi di bawah ini akan diterapkan ke semua kamar berikut:
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($rooms as $r)
                        <span class="room-tag">#{{ $r->nomor_kamar }}</span>
                    @endforeach
                </div>
            </div>
        </div>
      </div>

      <form action="{{ route('owner.kamar.bulkUpdateDetail') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="ids" value="{{ $rooms->pluck('id_room')->join(',') }}">

        <div class="row">
          <div class="col-lg-8">
            
            {{-- INFORMASI DASAR --}}
            <div class="form-card">
              <h6><i class="bi bi-info-circle"></i> Informasi Kamar</h6>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Tipe Kamar</label>
                  <select name="tipe_kamar" class="form-select">
                    <option value="Standard" {{ $kamar->tipe_kamar == 'Standard' ? 'selected' : '' }}>🏠 Standard</option>
                    <option value="Menengah" {{ $kamar->tipe_kamar == 'Menengah' ? 'selected' : '' }}>🏡 Menengah</option>
                    <option value="Superior" {{ $kamar->tipe_kamar == 'Superior' ? 'selected' : '' }}>✨ Superior</option>
                    <option value="Deluxe"   {{ $kamar->tipe_kamar == 'Deluxe'   ? 'selected' : '' }}>💎 Deluxe</option>
                    <option value="VIP"      {{ $kamar->tipe_kamar == 'VIP'      ? 'selected' : '' }}>👑 VIP</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Ukuran Kamar (PxL)</label>
                  <input type="text" name="ukuran" class="form-control" value="{{ $kamar->ukuran }}" placeholder="Contoh: 3x4">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Harga per Bulan (Rp)</label>
                  <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: .85rem 0 0 .85rem; font-weight: 700; color: #94a3b8;">Rp</span>
                    <input type="number" name="harga_per_bulan" class="form-control border-start-0" value="{{ $kamar->harga_per_bulan }}" placeholder="0" style="padding-left: 0;">
                  </div>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Harga per Hari (Rp)</label>
                  <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: .85rem 0 0 .85rem; font-weight: 700; color: #3b82f6;">Rp</span>
                    <input type="number" name="harga_harian" class="form-control border-start-0" value="{{ $kamar->harga_harian }}" placeholder="0" style="padding-left: 0;">
                  </div>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Info Listrik</label>
                  <select name="listrik" class="form-select">
                    <option value="Termasuk" {{ $kamar->listrik == 'Termasuk' ? 'selected' : '' }}>⚡ Termasuk Biaya Sewa</option>
                    <option value="Token Sendiri" {{ $kamar->listrik == 'Token Sendiri' ? 'selected' : '' }}>🎟️ Token Sendiri</option>
                    <option value="Tagihan Bulanan" {{ $kamar->listrik == 'Tagihan Bulanan' ? 'selected' : '' }}>🧾 Tagihan Bulanan</option>
                  </select>
                </div>
              </div>
              <div class="mt-4">
                <label class="form-label">Deskripsi Kamar</label>
                <textarea name="deskripsi" class="form-control" rows="4">{{ $kamar->deskripsi }}</textarea>
              </div>
              <div class="mt-4">
                <label class="form-label">Peraturan Kamar</label>
                <textarea name="aturan_kamar" class="form-control" rows="3">{{ $kamar->aturan_kamar }}</textarea>
              </div>
            </div>

            {{-- FOTO KAMAR --}}
            <div class="form-card">
              <h6><i class="bi bi-camera"></i> Foto Kamar (Masal)</h6>
              
              {{-- Foto Kamar Saat Ini --}}
              @php $fotoKamar = $kamar->images->where('tipe_foto', 'kamar'); @endphp
              @if($fotoKamar->count() > 0)
              <div class="mb-4">
                <label class="form-label d-block small text-muted">Foto Saat Ini (Terapkan ke semua room)</label>
                <div class="preview-grid">
                  @foreach($fotoKamar as $img)
                    <div class="preview-card" id="existing_img_{{ $img->id }}">
                      <div class="preview-img-wrap">
                        <img src="{{ asset('storage/' . $img->foto_path) }}" class="preview-img">
                        <button type="button" class="btn-remove" onclick="markForDelete(this, '{{ $img->foto_path }}')">
                          <i class="bi bi-trash"></i>
                        </button>
                        <input type="checkbox" name="hapus_foto_paths[]" value="{{ $img->foto_path }}" style="display:none;">
                      </div>
                      <div class="preview-info">
                        <div class="small fw-700 text-muted" style="font-size:.65rem;">{{ $img->judul ?: 'Tanpa Label' }}</div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
              @endif

              {{-- Upload Baru --}}
              <div class="upload-zone p-4 text-center border-2 border-dashed rounded-4" style="border-color: #e2e8f0; background: #f8fafc;">
                <input type="file" name="foto_kamar[]" id="foto_kamar" class="form-control d-none" multiple accept="image/*" onchange="previewImages(this, 'previewGridNew')">
                <label for="foto_kamar" style="cursor: pointer;">
                    <div style="width: 50px; height: 50px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto .75rem; color: #e8401c; font-size: 1.3rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                        <i class="bi bi-plus-lg"></i>
                    </div>
                    <div class="fw-800" style="font-size: .9rem; color: #1e293b;">Tambah Foto Kamar Baru</div>
                </label>
              </div>
              <div id="judulFotoInputs"></div>
              <div id="previewGridNew" class="preview-grid mt-3"></div>
            </div>
          </div>

          <div class="col-lg-4">
            {{-- FASILITAS KAMAR --}}
            <div class="form-card">
              <h6><i class="bi bi-check2-square"></i> Fasilitas</h6>
              @php $fasilitasList = is_array($kamar->fasilitas) ? $kamar->fasilitas : []; @endphp
              @php
                $standardRoomFac = [
                  'Kasur','AC','WiFi','KM Dalam','Water Heater','TV','Lemari','Meja','Bantal','Guling'
                ];
              @endphp
              <div class="row g-2">
                @foreach($standardRoomFac as $f)
                  <div class="col-6">
                    <div class="form-check fac-item">
                      <input class="form-check-input ms-0 me-2" type="checkbox" name="fasilitas[]" value="{{ $f }}"
                             id="f_{{ $loop->index }}"
                             {{ in_array($f, $fasilitasList) ? 'checked' : '' }}>
                      <label class="form-check-label fw-600" for="f_{{ $loop->index }}" style="font-size: .78rem;">{{ $f }}</label>
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="mt-4 pt-3 border-top">
                <p class="small text-muted fw-700 mb-2">Fasilitas Tambahan:</p>
                <div class="input-group input-group-sm">
                    <input type="text" id="customFacInput" class="form-control" placeholder="Contoh: Kulkas...">
                    <button type="button" class="btn btn-dark px-3" onclick="addCustomFacility()"><i class="bi bi-plus"></i></button>
                </div>
                <div id="customFacContainer" class="mt-2 d-flex flex-wrap gap-2"></div>
              </div>
            </div>

            <div class="sticky-top" style="top: 2rem; z-index: 5;">
                <button type="submit" class="btn-submit w-100 mb-3">
                    <i class="bi bi-cloud-check-fill"></i> Terapkan ke Semua Kamar
                </button>
                <a href="{{ route('owner.kamar.index') }}" class="btn btn-light w-100 rounded-4 py-3 fw-800 text-muted" style="border: 1px solid #e2e8f0; font-size: .9rem;">
                    Batal
                </a>
            </div>
          </div>
        </div>
      </form>
@endsection

@push('scripts')
<script>
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
              <div class="preview-img-wrap"><img src="${e.target.result}" class="preview-img"></div>
              <div class="preview-info">
                <input type="text" placeholder="Label foto..." class="preview-label-input" oninput="syncJudulFoto(${i}, this.value)">
              </div>
            `;
            grid.appendChild(card);

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'foto_kamar_judul[]';
            hiddenInput.id = `hidden_judul_${i}`;
            judulContainer.appendChild(hiddenInput);
          }
          reader.readAsDataURL(file);
        });
      }
    }

    function syncJudulFoto(index, value) {
      const hidden = document.getElementById(`hidden_judul_${index}`);
      if (hidden) hidden.value = value;
    }

    function markForDelete(btn, path) {
        const card = btn.closest('.preview-card');
        const checkbox = card.querySelector('input[type="checkbox"]');
        
        if (checkbox.checked) {
            checkbox.checked = false;
            card.style.opacity = '1';
            btn.innerHTML = '<i class="bi bi-trash"></i>';
            btn.classList.remove('bg-dark');
        } else {
            checkbox.checked = true;
            card.style.opacity = '0.3';
            btn.innerHTML = '<i class="bi bi-arrow-counterclockwise"></i>';
            btn.classList.add('bg-dark');
        }
    }

    function addCustomFacility() {
        const input = document.getElementById('customFacInput');
        const val = input.value.trim();
        if (!val) return;
        
        const container = document.getElementById('customFacContainer');
        const idx = Date.now();
        const div = document.createElement('div');
        div.className = 'badge bg-light text-dark border d-flex align-items-center gap-2 p-2';
        div.style.borderRadius = '10px';
        div.innerHTML = `
            <input type="hidden" name="fasilitas[]" value="${val}">
            <span style="font-size:.75rem;">${val}</span>
            <i class="bi bi-x-circle-fill text-danger" style="cursor:pointer;" onclick="this.parentElement.remove()"></i>
        `;
        container.appendChild(div);
        input.value = '';
    }
</script>
@endpush
