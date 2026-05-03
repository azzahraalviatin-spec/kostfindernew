<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KamarController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['kost', 'mainImage'])
            ->whereHas('kost', fn ($q) => $q->where('owner_id', auth()->id()))
            ->latest()
            ->get();

        return view('owner.kamar', compact('rooms'));
    }

    public function create()
    {
        $kosts = Kost::where('owner_id', auth()->id())
            ->orderBy('nama_kost')
            ->get();

        return view('owner.kamar-create', compact('kosts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kost_id'                   => 'required|exists:kosts,id_kost',
            'nomor_kamar'               => 'nullable|string|max:50',
            'nomor_kamar_prefix'        => 'nullable|string|max:30',
            'jumlah_kamar'              => 'nullable|integer|min:1|max:50',
            'mode_massal'               => 'nullable|in:0,1',
            'harga_per_bulan'           => 'nullable|integer|min:0',
            'harga_harian'              => 'nullable|integer|min:0',
            'status_kamar'              => 'required|in:tersedia,terisi',
            'tipe_kamar'                => 'nullable|string',
            'ukuran'                    => 'nullable|string|max:50',
            'deskripsi'                 => 'nullable|string',
            'aturan_kamar'              => 'nullable|string',
            'listrik'                   => 'nullable|string|max:100',
            'fasilitas'                 => 'nullable|array',
            'fasilitas.*'               => 'string',
            'foto_kamar'                => 'nullable|array',
            'foto_kamar.*'              => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto_fasilitas'            => 'nullable|array',
            'foto_fasilitas.*.file'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto_fasilitas.*.judul'    => 'nullable|string|max:100',
        ]);

        $kost = Kost::where('id_kost', $validated['kost_id'])
            ->where('owner_id', auth()->id())
            ->firstOrFail();

        $isMassal = ($request->input('mode_massal') == '1');

        if ($isMassal) {
            // ── MODE MASSAL: buat banyak kamar sekaligus ──
            $prefix  = $request->input('nomor_kamar_prefix', '');
            $jumlah  = (int) ($request->input('jumlah_kamar', 1));
            $jumlah  = max(1, min(50, $jumlah));

            $baseData = [
                'kost_id'         => $kost->id_kost,
                'harga_per_bulan' => $validated['harga_per_bulan'] ?? 0,
                'harga_harian'    => $validated['harga_harian'] ?? null,
                'aktif_bulanan'   => $request->has('aktif_bulanan') ? 1 : 0,
                'aktif_harian'    => $request->has('aktif_harian') ? 1 : 0,
                'status_kamar'    => $validated['status_kamar'],
                'tipe_kamar'      => $validated['tipe_kamar'] ?? null,
                'ukuran'          => $validated['ukuran'] ?? null,
                'deskripsi'       => $validated['deskripsi'] ?? null,
                'aturan_kamar'    => $validated['aturan_kamar'] ?? null,
                'listrik'         => $validated['listrik'] ?? null,
                'fasilitas'       => $validated['fasilitas'] ?? null,
            ];

            // Upload foto sekali untuk kamar pertama, lalu copy path-nya ke kamar lain
            $fotoPaths = [];
            if ($request->hasFile('foto_kamar')) {
                foreach ($request->file('foto_kamar') as $file) {
                    $fotoPaths[] = $file->store('kamar', 'public');
                }
            }

            for ($i = 1; $i <= $jumlah; $i++) {
                $room = Room::create(array_merge($baseData, [
                    'nomor_kamar' => $prefix . $i,
                ]));

                // Attach foto (shared path) ke setiap kamar
                foreach ($fotoPaths as $idx => $path) {
                    RoomImage::create([
                        'room_id'   => $room->id_room,
                        'foto_path' => $path,
                        'judul'     => $juduls[$idx] ?? null,
                        'tipe_foto' => 'kamar',
                        'is_utama'  => ($idx === 0),
                    ]);
                }

                $this->syncFasilitasImages($room, $request);
            }

            return redirect()->route('owner.kamar.index')
                ->with('success', "✅ {$jumlah} kamar berhasil dibuat sekaligus!");
        }

        // ── MODE NORMAL: buat 1 kamar ──
        $room = Room::create([
            'kost_id'         => $kost->id_kost,
            'nomor_kamar'     => $validated['nomor_kamar'] ?? $validated['tipe_kamar'],
            'harga_per_bulan' => $validated['harga_per_bulan'] ?? 0,
            'harga_harian'    => $validated['harga_harian'] ?? null,
            'aktif_bulanan'   => $request->has('aktif_bulanan') ? 1 : 0,
            'aktif_harian'    => $request->has('aktif_harian') ? 1 : 0,
            'status_kamar'    => $validated['status_kamar'],
            'tipe_kamar'      => $validated['tipe_kamar'] ?? null,
            'ukuran'          => $validated['ukuran'] ?? null,
            'deskripsi'       => $validated['deskripsi'] ?? null,
            'aturan_kamar'    => $validated['aturan_kamar'] ?? null,
            'listrik'         => $validated['listrik'] ?? null,
            'fasilitas'       => $validated['fasilitas'] ?? null,
        ]);

        $this->syncRoomImages($room, $request, false);
        $this->syncFasilitasImages($room, $request);

        return redirect()->route('owner.kamar.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function show(Room $kamar)
    {
        $kamar->load(['images', 'bookings.user', 'kost']);
        $kost = $kamar->kost;

        if (is_string($kost->fasilitas)) {
            $decoded = json_decode($kost->fasilitas, true);
            $kost->fasilitas = json_last_error() === JSON_ERROR_NONE
                ? $decoded
                : explode(',', $kost->fasilitas);
        }

        $fotoKamar = $kamar->images->where('tipe_foto', 'kamar');

        return view('owner.kamar-show', compact('kamar', 'kost', 'fotoKamar'));
    }

    public function edit(Room $kamar)
    {
        $kamar->load(['kost', 'mainImage', 'images']);
        $this->authorizeOwner($kamar);

        $kosts = Kost::where('owner_id', auth()->id())
            ->orderBy('nama_kost')
            ->get();

        // Pisahkan foto kamar dan foto fasilitas
        $fotoKamar     = $kamar->images->where('tipe_foto', 'kamar');
        $fotoFasilitas = $kamar->images->where('tipe_foto', 'fasilitas');

        return view('owner.kamar-edit', compact('kamar', 'kosts', 'fotoKamar', 'fotoFasilitas'));
    }

    public function update(Request $request, Room $kamar)
    {
        $kamar->load(['kost', 'mainImage', 'images']);
        $this->authorizeOwner($kamar);

        $validated = $request->validate([
            'kost_id'                   => 'required|exists:kosts,id_kost',
            'nomor_kamar'               => 'nullable|string|max:50',
            'harga_per_bulan'           => 'nullable|integer|min:0',
            'harga_harian'              => 'nullable|integer|min:0',
            'status_kamar'              => 'required|in:tersedia,terisi',
            'tipe_kamar'                => 'nullable|string',
            'ukuran'                    => 'nullable|string|max:50',
            'deskripsi'                 => 'nullable|string',
            'aturan_kamar'              => 'nullable|string',
            'listrik'                   => 'nullable|string|max:100',
            'fasilitas'                 => 'nullable|array',
            'fasilitas.*'               => 'string',
            'foto_kamar'                => 'nullable|array',
            'foto_kamar.*'              => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            // Foto fasilitas kamar
            'foto_fasilitas'            => 'nullable|array',
            'foto_fasilitas.*.file'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto_fasilitas.*.judul'    => 'nullable|string|max:100',
            // Hapus foto fasilitas tertentu
            'hapus_fasilitas_ids'       => 'nullable|array',
            'hapus_fasilitas_ids.*'     => 'integer',
        ]);

        $kost = Kost::where('id_kost', $validated['kost_id'])
            ->where('owner_id', auth()->id())
            ->firstOrFail();

        $kamar->update([
            'kost_id'         => $kost->id_kost,
            'nomor_kamar'     => $validated['nomor_kamar'] ?? $validated['tipe_kamar'],
            'harga_per_bulan' => $validated['harga_per_bulan'] ?? 0,
            'harga_harian'    => $validated['harga_harian'] ?? null,
            'aktif_bulanan'   => $request->has('aktif_bulanan') ? 1 : 0,
            'aktif_harian'    => $request->has('aktif_harian') ? 1 : 0,
            'status_kamar'    => $validated['status_kamar'],
            'tipe_kamar'      => $validated['tipe_kamar'] ?? null,
            'ukuran'          => $validated['ukuran'] ?? null,
            'deskripsi'       => $validated['deskripsi'] ?? null,
            'aturan_kamar'    => $validated['aturan_kamar'] ?? null,
            'listrik'         => $validated['listrik'] ?? null,
            'fasilitas'       => $validated['fasilitas'] ?? null,
        ]);

        // Upload foto kamar utama
        $this->syncRoomImages($kamar, $request, true);

        // Update judul foto kamar yang sudah ada
        if ($request->filled('existing_foto_judul')) {
            foreach ($request->existing_foto_judul as $id => $judul) {
                RoomImage::where('id', $id)
                    ->where('room_id', $kamar->id_room)
                    ->update(['judul' => $judul]);
            }
        }

        // Hapus foto fasilitas yang dipilih
        if (!empty($validated['hapus_fasilitas_ids'])) {
            $toDelete = RoomImage::whereIn('id', $validated['hapus_fasilitas_ids'])
                ->where('room_id', $kamar->id_room)
                ->where('tipe_foto', 'fasilitas')
                ->get();
            foreach ($toDelete as $img) {
                Storage::disk('public')->delete($img->foto_path);
                $img->delete();
            }
        }

        // Upload foto fasilitas baru
        $this->syncFasilitasImages($kamar, $request);

        return redirect()->route('owner.kamar.index')->with('success', 'Kamar berhasil diupdate.');
    }

    public function destroy(Room $kamar)
    {
        $kamar->load(['kost', 'images']);
        $this->authorizeOwner($kamar);

        foreach ($kamar->images as $image) {
            Storage::disk('public')->delete($image->foto_path);
        }

        $kamar->delete();

        return redirect()->route('owner.kamar.index')->with('success', 'Kamar berhasil dihapus.');
    }

    // ===================== PRIVATE HELPERS =====================

    /**
     * Sync foto utama kamar (tipe: kamar)
     */
    private function syncRoomImages(Room $room, Request $request, bool $replaceExisting): void
    {
        if (!$request->hasFile('foto_kamar')) {
            return;
        }

        if ($request->boolean('hapus_semua_foto')) {
            $existing = $room->images()->where('tipe_foto', 'kamar')->get();
            foreach ($existing as $image) {
                Storage::disk('public')->delete($image->foto_path);
            }
            $room->images()->where('tipe_foto', 'kamar')->delete();
        }

        $existingCount = $room->images()->where('tipe_foto', 'kamar')->count();
        $sisaSlot      = 6 - $existingCount;

        if ($sisaSlot <= 0) return;

        $files         = array_slice($request->file('foto_kamar'), 0, $sisaSlot);
        $isFirstExisting = $existingCount === 0;

        $files         = array_slice($request->file('foto_kamar'), 0, $sisaSlot);
        $juduls        = $request->input('foto_kamar_judul', []);
        $isFirstExisting = $existingCount === 0;

        foreach ($files as $index => $file) {
            $path = $file->store('kamar', 'public');
            RoomImage::create([
                'room_id'   => $room->id_room,
                'foto_path' => $path,
                'judul'     => $juduls[$index] ?? null,
                'tipe_foto' => 'kamar',
                'is_utama'  => $isFirstExisting && $index === 0,
            ]);
        }
    }

    /**
     * Sync foto fasilitas kamar (tipe: fasilitas)
     * Input: foto_fasilitas[0][file], foto_fasilitas[0][judul], dst.
     */
    private function syncFasilitasImages(Room $room, Request $request): void
    {
        if (!$request->has('foto_fasilitas')) {
            return;
        }

        $existingCount = $room->images()->where('tipe_foto', 'fasilitas')->count();
        $sisaSlot      = 10 - $existingCount; // max 10 foto fasilitas

        if ($sisaSlot <= 0) return;

        $items   = $request->input('foto_fasilitas', []);
        $files   = $request->file('foto_fasilitas', []);
        $count   = 0;

        foreach ($files as $index => $item) {
            if ($count >= $sisaSlot) break;

            $file  = $item['file'] ?? null;
            $judul = $items[$index]['judul'] ?? null;

            if (!$file || !$file->isValid()) continue;

            $path = $file->store('kamar/fasilitas', 'public');
            RoomImage::create([
                'room_id'   => $room->id_room,
                'foto_path' => $path,
                'judul'     => $judul,
                'tipe_foto' => 'fasilitas',
                'is_utama'  => false,
            ]);
            $count++;
        }
    }

    private function authorizeOwner(Room $room): void
    {
        abort_if($room->kost->owner_id !== auth()->id(), 403);
    }
    public function bulkUpdate(Request $request)
    {
        foreach ($request->rooms as $id => $data) {
            Room::where('id_room', $id)
                ->whereHas('kost', fn($q) => $q->where('owner_id', auth()->id()))
                ->update([
                    'nomor_kamar'    => $data['nomor_kamar'],
                    'status_kamar'   => $data['status_kamar'],
                    'harga_per_bulan'=> $data['harga_per_bulan'],
                ]);
        }
        return back()->with('success', 'Semua kamar berhasil diperbarui!');
    }

    public function bulkEditDetail(Request $request)
    {
        $ids = explode(',', $request->query('ids'));
        $rooms = Room::whereIn('id_room', $ids)
            ->whereHas('kost', fn($q) => $q->where('owner_id', auth()->id()))
            ->get();

        if ($rooms->isEmpty()) return redirect()->route('owner.kamar.index');

        // Gunakan kamar pertama sebagai template data awal
        $kamar = $rooms->first();
        $kosts = Kost::where('owner_id', auth()->id())->get();
        
        return view('owner.kamar-bulk-edit', compact('rooms', 'kamar', 'kosts'));
    }

    public function bulkUpdateDetail(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        $validated = $request->validate([
            'tipe_kamar'                => 'nullable|string',
            'harga_per_bulan'           => 'nullable|numeric|min:0',
            'harga_harian'              => 'nullable|numeric|min:0',
            'ukuran'                    => 'nullable|string|max:50',
            'deskripsi'                 => 'nullable|string',
            'aturan_kamar'              => 'nullable|string',
            'listrik'                   => 'nullable|string|max:100',
            'fasilitas'                 => 'nullable|array',
            'fasilitas.*'               => 'string',
            'foto_kamar'                => 'nullable|array',
            'foto_kamar.*'              => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $rooms = Room::whereIn('id_room', $ids)
            ->whereHas('kost', fn($q) => $q->where('owner_id', auth()->id()))
            ->get();

        foreach ($rooms as $room) {
            $room->update([
                'tipe_kamar'      => $validated['tipe_kamar'],
                'harga_per_bulan' => $validated['harga_per_bulan'] ?? $room->harga_per_bulan,
                'harga_harian'    => $validated['harga_harian'] ?? $room->harga_harian,
                'ukuran'          => $validated['ukuran'],
                'deskripsi'       => $validated['deskripsi'],
                'aturan_kamar'    => $validated['aturan_kamar'],
                'listrik'         => $request->input('listrik'),
                'fasilitas'       => $validated['fasilitas'],
            ]);

            // Hapus Foto Masal (berdasarkan path)
            if ($request->filled('hapus_foto_paths')) {
                foreach ($request->hapus_foto_paths as $path) {
                    $img = RoomImage::where('room_id', $room->id_room)
                                   ->where('foto_path', $path)
                                   ->first();
                    if ($img) {
                        // Hapus file fisik hanya sekali saja nanti (atau biarkan di handle sync)
                        // Untuk amannya, kita hapus record database di setiap room
                        $img->delete();
                    }
                }
            }

            // Sync Foto Kamar Baru
            if ($request->hasFile('foto_kamar')) {
                $this->syncRoomImages($room, $request, false);
            }

            // Sync Foto Fasilitas Baru
            if ($request->has('foto_fasilitas')) {
                $this->syncFasilitasImages($room, $request);
            }
        }

        return redirect()->route('owner.kamar.index')->with('success', count($ids) . ' kamar berhasil diperbarui secara masal dengan foto baru!');
    }
}