<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use Illuminate\Http\Request;

class KostDetailController extends Controller
{
    public function index(Request $request)
    {
        $query = Kost::query()
            ->where('status', 'aktif')
            ->whereHas('owner', function ($q) {
                $q->where('status_verifikasi_identitas', 'disetujui');
            });

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_kost', 'like', '%'.$request->q.'%')
                  ->orWhere('alamat', 'like', '%'.$request->q.'%')
                  ->orWhere('kota', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->filled('tipe')) {
            $query->where('tipe_kost', $request->tipe);
        }

        if ($request->filled('harga_min')) {
            $query->where('harga_mulai', '>=', $request->harga_min);
        }

        if ($request->filled('harga_max')) {
            $query->where('harga_mulai', '<=', $request->harga_max);
        }

        if ($request->filled('fasilitas')) {
            foreach ($request->fasilitas as $f) {
                $query->where(function ($q) use ($f) {
                    $q->whereJsonContains('fasilitas', $f)
                      ->orWhereHas('rooms', function ($qRoom) use ($f) {
                          $qRoom->whereJsonContains('fasilitas', $f);
                      });
                });
            }
        }

        if ($request->filled('aturan')) {
            $query->where(function ($q) use ($request) {
                foreach ($request->aturan as $a) {
                    $q->where('aturan', 'like', '%' . $a . '%');
                }
            });
        }

        if ($request->filled('q_aturan')) {
            $query->where('aturan', 'like', '%' . $request->q_aturan . '%');
        }

        match ($request->get('sort', 'terbaru')) {
            'termurah' => $query->orderBy('harga_mulai', 'asc'),
            'termahal' => $query->orderBy('harga_mulai', 'desc'),
            default    => $query->orderBy('created_at', 'desc'),
        };

        $kosts = $query->with(['images', 'rooms'])
            ->withCount(['rooms as kamar_tersedia' => function($q) {
                $q->where('status_kamar', 'tersedia');
            }])
            ->withCount(['rooms as kamar_total'])
            ->paginate(12)
            ->withQueryString();

        // Untuk maps: ambil semua (max 100) tanpa pagination
$kostsMapRaw = (clone $query)->limit(100)
->get(['id_kost','nama_kost','alamat','kota','harga_mulai','tipe_kost','latitude','longitude', 'foto_utama']);

$kostsMap = $kostsMapRaw->map(fn($k) => [
'id'    => $k->id_kost,
'nama'  => $k->nama_kost,
'alamat'=> $k->alamat,
'kota'  => $k->kota,
'harga' => $k->harga_mulai,
'tipe'  => $k->tipe_kost,
'lat'   => $k->latitude,
'lng'   => $k->longitude,
'url'   => route('kost.show', $k->id_kost),
'foto'  => $k->foto_utama ? asset('storage/' . $k->foto_utama) : null,
]);

return view('cari-kost', compact('kosts', 'kostsMap'));
    }

    public function show(Kost $kost)
    {
        abort_if($kost->status != 'aktif', 404);
        abort_if($kost->owner->status_verifikasi_identitas !== 'disetujui', 404);

        $kost->load(['rooms.images', 'owner', 'bookings', 'generalFacilities', 'reviews' => function($q) {
            $q->where('status', 'approved')->with(['user', 'reply']);
        }]);

        if (auth()->check()) {
            $viewedKost = \App\Models\RecentlyViewedKost::firstOrCreate(
                ['user_id' => auth()->id(), 'kost_id' => $kost->id_kost]
            );
            $viewedKost->touch();
        }

        $viewed = session('recently_viewed', []);
        $viewed = array_filter($viewed, fn($id) => $id != $kost->id_kost);
        array_unshift($viewed, $kost->id_kost);
        $viewed = array_slice($viewed, 0, 10);
        session(['recently_viewed' => array_values($viewed)]);

        $rekomendasi = Kost::where('status', 'aktif')
            ->whereHas('owner', function ($q) {
                $q->where('status_verifikasi_identitas', 'disetujui');
            })
            ->where('id_kost', '!=', $kost->id_kost)
            ->where('kota', $kost->kota)
            ->with(['rooms'])
            ->take(6)
            ->get();

        $settings = \App\Models\Setting::first();
        $komisiAdmin = (int) ($settings->komisi_admin ?? 10000);
        return view('kost.show', compact('kost', 'rekomendasi', 'komisiAdmin'));
    }
}