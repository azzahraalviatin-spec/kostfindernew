<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Kost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Notifications\BookingBaruNotification;
use App\Notifications\BookingDibatalkanNotification;
use App\Notifications\OwnerBaruNotification;
use App\Notifications\ReviewBaruNotification;

class AdminController extends Controller
{
    // ════════════════════════════════════════════════════════════
    // HELPER: ambil nominal biaya layanan (Flat Fee) dari settings
    // Dipakai di mana saja yang butuh hitung komisi_admin
    // ════════════════════════════════════════════════════════════
    private function getKomisiFlat(): float
    {
        $settings = Setting::first();
        return (float) ($settings->komisi_admin ?? 10000); // default Rp 10.000 kalau belum diset
    }

    // ════════════════════════════════════════════════════════════
    // DASHBOARD
    // ════════════════════════════════════════════════════════════
    public function dashboard()
    {
        $summary = [
            'total_users'    => User::where('role', 'user')->count(),
            'total_owners'   => User::where('role', 'owner')->count(),
            'total_kosts'    => Kost::count(),
            'total_bookings' => Booking::count(),
        ];

        $start = now()->subMonths(5)->startOfMonth();
        $end   = now()->endOfMonth();

        $bookingsPerMonth = Booking::whereBetween('created_at', [$start, $end])
            ->get(['created_at'])
            ->groupBy(fn($b) => Carbon::parse($b->created_at)->format('Y-m'))
            ->map->count();

        $monthlyLabels = [];
        $monthlyTotals = [];
        for ($cursor = $start->copy(); $cursor <= $end; $cursor->addMonth()) {
            $key             = $cursor->format('Y-m');
            $monthlyLabels[] = $cursor->translatedFormat('M Y');
            $monthlyTotals[] = (int) ($bookingsPerMonth[$key] ?? 0);
        }

        $topKosts = DB::table('bookings')
            ->join('rooms', 'rooms.id_room', '=', 'bookings.room_id')
            ->join('kosts', 'kosts.id_kost', '=', 'rooms.kost_id')
            ->select('kosts.nama_kost', DB::raw('COUNT(bookings.id_booking) as total_booking'))
            ->groupBy('kosts.id_kost', 'kosts.nama_kost')
            ->orderByDesc('total_booking')
            ->limit(5)
            ->get();

        $notifications = [
            'unverified_kosts' => $this->hasKostVerificationColumn() ? Kost::where('is_verified', false)->count() : 0,
            'inactive_users'   => $this->hasUserStatusColumn() ? User::where('status_akun', 'nonaktif')->count() : 0,
        ];

        // Dashboard hanya tampilkan 3 booking terbaru
        $recentBookings = Booking::with(['user:id,name,email', 'room.kost'])
            ->latest()
            ->limit(3)
            ->get();

        $recentActivities = $this->hasActivityLogTable()
            ? ActivityLog::with('actor:id,name')->latest()->limit(8)->get()
            : collect();

        return view('admin.dashboard', compact(
            'summary',
            'recentBookings',
            'monthlyLabels',
            'monthlyTotals',
            'topKosts',
            'notifications',
            'recentActivities'
        ))->with([
            'total_kost'           => $summary['total_kosts'],
            'total_booking'        => $summary['total_bookings'],
            'total_users'          => $summary['total_users'],
            'total_owners'         => $summary['total_owners'],
            'booking_pending'      => Booking::where('status_booking', 'pending')->count(),
            'booking_selesai'      => Booking::where('status_booking', 'selesai')->count(),
            'kosts'                => collect(),
            'chartLabels'          => $monthlyLabels,
            'chartData'            => $monthlyTotals,
            'pendapatan_bulan_ini' => 0,
            'selisih_pendapatan'   => 0,
        ]);
    }

    // ════════════════════════════════════════════════════════════
    // USERS
    // ════════════════════════════════════════════════════════════
    public function users(Request $request)
    {
        $q      = trim((string) $request->query('q', ''));
        $status = $request->query('status');

        $users = User::where('role', 'user')
            ->when($q !== '', fn($query) => $query->where(fn($sub) =>
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('no_hp', 'like', "%{$q}%")
            ))
            ->when(
                $this->hasUserStatusColumn() && in_array($status, ['aktif', 'nonaktif'], true),
                fn($query) => $query->where('status_akun', $status)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function showUser(User $user)
    {
        abort_unless($user->role === 'user', 404);

        $bookings     = Booking::with('room.kost')->where('user_id', $user->id)->latest()->get();
        $totalBooking = $bookings->count();

        return view('admin.user-show', compact('user', 'bookings', 'totalBooking'));
    }

    public function verifyUser(Request $request, User $user)
    {
        abort_unless($user->role === 'user', 404);
        $user->update(['status_verifikasi_identitas' => 'disetujui']);
        return back()->with('status', '✅ Identitas user berhasil diverifikasi!');
    }

    public function rejectUser(Request $request, User $user)
    {
        abort_unless($user->role === 'user', 404);
        $request->validate(['catatan' => 'required|string|max:255']);
        $user->update([
            'status_verifikasi_identitas' => 'ditolak',
            'catatan_verifikasi'          => $request->catatan,
        ]);
        return back()->with('status', '❌ Identitas user ditolak.');
    }

    public function toggleUserStatus(Request $request, User $user)
    {
        abort_unless(in_array($user->role, ['user', 'owner'], true), 404);

        if (!$this->hasUserStatusColumn()) {
            return back()->withErrors(['status' => 'Kolom status_akun belum tersedia. Jalankan migrate.']);
        }

        $newStatus = $user->status_akun === 'aktif' ? 'nonaktif' : 'aktif';
        $user->update(['status_akun' => $newStatus]);

        $this->logActivity($request, 'toggle_user_status', 'user', $user->id, $user->id, [
            'new_status' => $newStatus,
            'role'       => $user->role,
        ]);

        return back()->with('status', 'Status akun berhasil diperbarui.');
    }

    public function destroyUser(Request $request, User $user)
    {
        abort_unless(in_array($user->role, ['user', 'owner'], true), 404);
        abort_if($request->user()->id === $user->id, 422, 'Tidak bisa menghapus akun sendiri.');

        $this->logActivity($request, 'delete_user', 'user', $user->id, null, [
            'name' => $user->name,
            'role' => $user->role,
        ]);

        $user->delete();
        return back()->with('status', 'Akun berhasil dihapus.');
    }

    // ════════════════════════════════════════════════════════════
    // OWNERS
    // ════════════════════════════════════════════════════════════
    public function owners(Request $request)
    {
        $q          = trim((string) $request->query('q', ''));
        $status     = $request->query('status');
        $verifikasi = $request->query('verifikasi');

        $owners = User::where('role', 'owner')
            ->when($q !== '', fn($query) => $query->where(fn($sub) =>
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('no_hp', 'like', "%{$q}%")
            ))
            ->when(
                $this->hasUserStatusColumn() && in_array($status, ['aktif', 'nonaktif'], true),
                fn($query) => $query->where('status_akun', $status)
            )
            ->when(
                in_array($verifikasi, ['belum', 'pending', 'disetujui', 'ditolak'], true),
                fn($query) => $query->where('status_verifikasi_identitas', $verifikasi)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kostCountByOwner = Kost::selectRaw('owner_id, COUNT(*) as total_kost')
            ->groupBy('owner_id')
            ->pluck('total_kost', 'owner_id');

        return view('admin.owners', compact('owners', 'kostCountByOwner'));
    }

    public function showOwner(User $owner)
    {
        abort_unless($owner->role === 'owner', 404);
        $ownerKosts = Kost::where('owner_id', $owner->id)->latest()->get();
        return view('admin.owner-show', compact('owner', 'ownerKosts'));
    }

    public function verifyOwner(Request $request, User $owner)
    {
        abort_unless($owner->role === 'owner', 404);

        $owner->update([
            'status_verifikasi_identitas' => 'disetujui',
            'catatan_verifikasi'          => null,
        ]);

        auth()->user()->notifications()
            ->where('type', \App\Notifications\OwnerBaruNotification::class)
            ->get()
            ->filter(fn($n) => $n->data['owner_id'] === $owner->id)
            ->each->delete();

        $this->logActivity($request, 'verify_owner_identity', 'user', $owner->id, $owner->id, [
            'name' => $owner->name,
        ]);

        return back()->with('status', '✅ Identitas owner berhasil diverifikasi!');
    }

    public function rejectOwner(Request $request, User $owner)
    {
        abort_unless($owner->role === 'owner', 404);
        $request->validate(['catatan' => 'required|string|max:255']);

        $owner->update([
            'status_verifikasi_identitas' => 'ditolak',
            'catatan_verifikasi'          => $request->catatan,
        ]);

        $this->logActivity($request, 'reject_owner_identity', 'user', $owner->id, $owner->id, [
            'name'    => $owner->name,
            'catatan' => $request->catatan,
        ]);

        return back()->with('status', '❌ Identitas owner ditolak.');
    }

    // ════════════════════════════════════════════════════════════
    // KOSTS
    // ════════════════════════════════════════════════════════════
    public function kosts(Request $request)
    {
        $q        = trim((string) $request->query('q', ''));
        $status   = $request->query('status');
        $verified = $request->query('verified');

        $kosts = Kost::with('owner:id,name,email')
            ->withCount('rooms')
            ->when($q !== '', fn($query) => $query->where(fn($sub) =>
                $sub->where('nama_kost', 'like', "%{$q}%")
                    ->orWhere('kota', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%")
            ))
            ->when(in_array($status, ['aktif', 'nonaktif'], true), fn($query) => $query->where('status', $status))
            ->when(
                $this->hasKostVerificationColumn() && in_array($verified, ['ya', 'tidak'], true),
                fn($query) => $query->where('is_verified', $verified === 'ya')
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.kosts', compact('kosts'));
    }

    public function showKost(Kost $kost)
    {
        $kost->load(['owner:id,name,email,no_hp', 'rooms']);

        $bookingCount = Booking::whereHas('room', fn($q) => $q->where('kost_id', $kost->id_kost))->count();

        return view('admin.kost-show', compact('kost', 'bookingCount'));
    }

    public function verifyKost(Request $request, Kost $kost)
    {
        if (!$this->hasKostVerificationColumn()) {
            return back()->withErrors(['status' => 'Kolom is_verified belum tersedia. Jalankan migrate.']);
        }

        $kost->update(['is_verified' => true]);

        $this->logActivity($request, 'verify_kost', 'kost', $kost->id_kost, $kost->owner_id, [
            'nama_kost' => $kost->nama_kost,
        ]);

        return back()->with('status', 'Kos berhasil diverifikasi.');
    }

    public function toggleKostStatus(Request $request, Kost $kost)
    {
        $newStatus = $kost->status === 'aktif' ? 'nonaktif' : 'aktif';
        $kost->update(['status' => $newStatus]);

        $this->logActivity($request, 'toggle_kost_status', 'kost', $kost->id_kost, $kost->owner_id, [
            'new_status' => $newStatus,
            'nama_kost'  => $kost->nama_kost,
        ]);

        return back()->with('status', 'Status kos berhasil diperbarui.');
    }

    public function destroyKost(Request $request, Kost $kost)
    {
        $this->logActivity($request, 'delete_kost', 'kost', $kost->id_kost, null, [
            'nama_kost' => $kost->nama_kost,
        ]);

        $kost->delete();
        return back()->with('status', 'Kos berhasil dihapus.');
    }

    // ════════════════════════════════════════════════════════════
    // BOOKINGS — Monitoring
    // ════════════════════════════════════════════════════════════
    public function bookings(Request $request)
    {
        $status    = $request->query('status');
        $startDate = $request->query('start_date');
        $endDate   = $request->query('end_date');

        // Paginated — untuk tabel
        $bookings = Booking::with(['user:id,name,email', 'room.kost'])
            ->when(in_array($status, ['pending', 'diterima', 'ditolak', 'selesai'], true), fn($q) => $q->where('status_booking', $status))
            ->when($startDate, fn($q) => $q->whereDate('tanggal_masuk', '>=', $startDate))
            ->when($endDate,   fn($q) => $q->whereDate('tanggal_masuk', '<=', $endDate))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Semua booking tanpa filter — untuk stat cards
        // (selalu ambil semua, agar card total tidak terpengaruh filter)
        $allBookings = Booking::with(['user:id,name', 'room.kost'])->get();

        $activeStatus = in_array($status, ['pending', 'diterima', 'ditolak', 'selesai'])
            ? $status
            : 'semua';

        return view('admin.bookings', compact('bookings', 'allBookings', 'activeStatus'));
    }

    // ════════════════════════════════════════════════════════════
    // REPORTS — Laporan & Statistik
    // ════════════════════════════════════════════════════════════
    public function reports()
    {
        $totalUsers    = User::where('role', 'user')->count();
        $totalOwners   = User::where('role', 'owner')->count();
        $totalKosts    = Kost::count();
        $totalBookings = Booking::count();

        $bookingThisMonth = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ✅ FIX: Pendapatan Admin = SUM(komisi_admin) booking SELESAI
        // Sinkron dengan card Pendapatan Admin di halaman Monitoring Booking
        $totalPendapatanAdmin = Booking::where('status_booking', 'selesai')
            ->sum('komisi_admin');

        $statusStats = Booking::selectRaw('status_booking, COUNT(*) as total')
            ->groupBy('status_booking')
            ->pluck('total', 'status_booking');

        $bookingByStatus = [
            'pending'  => (int) ($statusStats['pending']  ?? 0),
            'diterima' => (int) ($statusStats['diterima'] ?? 0),
            'ditolak'  => (int) ($statusStats['ditolak']  ?? 0),
            'selesai'  => (int) ($statusStats['selesai']  ?? 0),
        ];

        // Grafik 6 bulan terakhir
        $start = now()->subMonths(5)->startOfMonth();
        $end   = now()->endOfMonth();

        $grouped = Booking::whereBetween('created_at', [$start, $end])
            ->get(['created_at'])
            ->groupBy(fn($b) => Carbon::parse($b->created_at)->format('Y-m'))
            ->map->count();

        $monthlyLabels = [];
        $monthlyTotals = [];
        for ($cursor = $start->copy(); $cursor <= $end; $cursor->addMonth()) {
            $key             = $cursor->format('Y-m');
            $monthlyLabels[] = $cursor->translatedFormat('M Y');
            $monthlyTotals[] = (int) ($grouped[$key] ?? 0);
        }

        // ✅ FIX: Booking terbaru hanya 3 data
        $recentBookings = DB::table('bookings')
            ->join('users as penyewa', 'penyewa.id', '=', 'bookings.user_id')
            ->join('rooms', 'rooms.id_room', '=', 'bookings.room_id')
            ->join('kosts', 'kosts.id_kost', '=', 'rooms.kost_id')
            ->join('users as owner', 'owner.id', '=', 'kosts.owner_id')
            ->select(
                'bookings.id_booking',
                'penyewa.name as nama_penyewa',
                'owner.name as nama_owner',
                'kosts.nama_kost',
                DB::raw("COALESCE(kosts.alamat, '-') as daerah_kos"),
                'rooms.nomor_kamar as nama_kamar',
                'bookings.status_booking',
                'bookings.status_pembayaran',
                'bookings.total_bayar',
                'bookings.komisi_admin',
                'bookings.created_at'
            )
            ->latest('bookings.created_at')
            ->limit(3) // ✅ 3 terbaru
            ->get();

        $topKostsThisMonth = DB::table('bookings')
            ->join('rooms', 'rooms.id_room', '=', 'bookings.room_id')
            ->join('kosts', 'kosts.id_kost', '=', 'rooms.kost_id')
            ->join('users as owner', 'owner.id', '=', 'kosts.owner_id')
            ->whereMonth('bookings.created_at', now()->month)
            ->whereYear('bookings.created_at', now()->year)
            ->where('bookings.status_pembayaran', '!=', 'belum')
            ->select(
                'kosts.nama_kost',
                DB::raw("COALESCE(kosts.alamat, '-') as daerah_kos"),
                'owner.name as nama_owner',
                DB::raw('COUNT(bookings.id_booking) as total_booking'),
                DB::raw('SUM(bookings.total_bayar) as total_pemasukan')
            )
            ->groupBy('kosts.id_kost', 'kosts.nama_kost', 'kosts.alamat', 'owner.name')
            ->orderByDesc('total_booking')
            ->limit(5)
            ->get();

        return view('admin.reports', compact(
            'totalUsers',
            'totalOwners',
            'totalKosts',
            'totalBookings',
            'bookingThisMonth',
            'totalPendapatanAdmin',
            'bookingByStatus',
            'monthlyLabels',
            'monthlyTotals',
            'recentBookings',
            'topKostsThisMonth'
        ));
    }

    // ════════════════════════════════════════════════════════════
    // EXPORT PDF
    // ════════════════════════════════════════════════════════════
    public function exportReportsPdf(Request $request)
    {
        $data = $this->getReportExportData();

        $this->logActivity($request, 'export_reports_pdf', 'report', null, null, [
            'exported_at' => now()->toDateTimeString(),
        ]);

        $pdf = Pdf::loadView('admin.exports.reports-pdf', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->download('laporan-admin-' . now()->format('Ymd-His') . '.pdf');
    }

    // ════════════════════════════════════════════════════════════
    // EXPORT WORD
    // ════════════════════════════════════════════════════════════
    public function exportReportsWord(Request $request)
    {
        $data = $this->getReportExportData();

        $this->logActivity($request, 'export_reports_word', 'report', null, null, [
            'exported_at' => now()->toDateTimeString(),
        ]);

        $phpWord  = new PhpWord();
        $section  = $phpWord->addSection();

        $section->addText('LAPORAN ADMIN KOSTFINDER', ['bold' => true, 'size' => 16]);
        $section->addText('Tanggal Export: ' . now()->translatedFormat('d F Y H:i'));
        $section->addTextBreak(1);

        $section->addText('RINGKASAN', ['bold' => true, 'size' => 13]);
        $section->addText("Total User: " . $data['totalUsers']);
        $section->addText("Total Owner: " . $data['totalOwners']);
        $section->addText("Total Kost: " . $data['totalKosts']);
        $section->addText("Total Booking: " . $data['totalBookings']);
        $section->addText("Booking Bulan Ini: " . $data['bookingThisMonth']);
        $section->addText("Pendapatan Admin: Rp " . number_format($data['totalPendapatanAdmin'], 0, ',', '.'));
        $section->addTextBreak(1);

        $section->addText('STATISTIK STATUS BOOKING', ['bold' => true, 'size' => 13]);
        $section->addText("Pending: "  . ($data['bookingByStatus']['pending']  ?? 0));
        $section->addText("Diterima: " . ($data['bookingByStatus']['diterima'] ?? 0));
        $section->addText("Ditolak: "  . ($data['bookingByStatus']['ditolak']  ?? 0));
        $section->addText("Selesai: "  . ($data['bookingByStatus']['selesai']  ?? 0));
        $section->addTextBreak(1);

        $section->addText('BOOKING TERBARU', ['bold' => true, 'size' => 13]);
        foreach ($data['recentBookings'] as $booking) {
            $section->addText(
                "• {$booking->nama_penyewa} | {$booking->nama_kost} | Kamar {$booking->nama_kamar} | " .
                "{$booking->status_booking} | Rp " . number_format($booking->total_bayar, 0, ',', '.')
            );
        }

        $section->addTextBreak(1);
        $section->addText('KOS TERLARIS BULAN INI', ['bold' => true, 'size' => 13]);
        foreach ($data['topKostsThisMonth'] as $kost) {
            $section->addText(
                "• {$kost->nama_kost} - {$kost->nama_owner} | Booking: {$kost->total_booking} | " .
                "Pemasukan: Rp " . number_format($kost->total_pemasukan, 0, ',', '.')
            );
        }

        $filename = storage_path('app/public/laporan-admin-' . now()->format('Ymd-His') . '.docx');
        $writer   = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($filename);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

    // ════════════════════════════════════════════════════════════
    // ACTIVITIES
    // ════════════════════════════════════════════════════════════
    public function activities(Request $request)
    {
        if (!$this->hasActivityLogTable()) {
            $activities = new LengthAwarePaginator([], 0, 15, 1, [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]);
            return view('admin.activities', compact('activities'));
        }

        $q = trim((string) $request->query('q', ''));

        $activities = ActivityLog::with(['actor:id,name,role'])
            ->whereHas('actor', fn($q) => $q->whereIn('role', ['user', 'owner']))
            ->when($q !== '', fn($query) => $query->whereHas('actor', fn($sub) =>
                $sub->where('name', 'like', "%{$q}%")->orWhere('role', 'like', "%{$q}%")
            ))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.activities', compact('activities'));
    }

    // ════════════════════════════════════════════════════════════
    // SETTINGS
    // ════════════════════════════════════════════════════════════
    public function settings()
    {
        $settings = Setting::first();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name'         => 'required|string|max:255',
            'old_password' => 'nullable|string',
            'password'     => 'nullable|confirmed|min:6',
            // ✅ Validasi komisi: angka 0-100
            'komisi_admin' => 'nullable|numeric|min:0',
        ]);

        // Update profil admin
        $user->name = $request->name;

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $path       = $request->file('photo')->store('profile-photos', 'public');
            $user->photo = $path;
        }

        if ($request->filled('password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Password lama salah!']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // ✅ Simpan pengaturan platform termasuk komisi_admin
        $settings = Setting::first() ?? new Setting();
        $settings->notif_booking  = $request->has('notif_booking');
        $settings->notif_user     = $request->has('notif_user');
        $settings->whatsapp_cs    = $request->whatsapp_cs;
        $settings->email_support  = $request->email_support;
        $settings->instagram_link = $request->instagram_link;
        $settings->tiktok_link    = $request->tiktok_link;
        $settings->facebook_link  = $request->facebook_link;
        $settings->alamat_kantor  = $request->alamat_kantor;

        // ✅ komisi_admin tersimpan sebagai nominal Rupiah tetap (Flat Fee)
        $komisiRaw = str_replace('.', '', $request->komisi_admin);
        $settings->komisi_admin   = ($komisiRaw !== null && $komisiRaw !== '') ? $komisiRaw : ($settings->komisi_admin ?? 10000);
        $settings->save();

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }

    // ════════════════════════════════════════════════════════════
    // NOTIFICATIONS
    // ════════════════════════════════════════════════════════════
    public function readNotification($id)
    {
        $notif = auth()->user()->notifications->find($id);
        if ($notif) {
            $notif->markAsRead();
            return redirect($notif->data['url']);
        }
        return redirect()->route('admin.dashboard');
    }

    public function readAllNotifications()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('status', 'Semua notifikasi sudah dibaca.');
    }

    // ════════════════════════════════════════════════════════════
    // BOOKING DETAIL & STATUS
    // ════════════════════════════════════════════════════════════
    public function showBooking(Booking $booking)
    {
        $booking->load(['user', 'room.kost.owner']);
        return view('admin.booking-show', compact('booking'));
    }

    public function updateBookingStatus(Request $request, Booking $booking, $status)
    {
        abort_unless(in_array($status, ['diterima', 'ditolak'], true), 400);

        $booking->update(['status_booking' => $status]);

        $statusText = $status === 'diterima' ? 'disetujui' : 'ditolak';

        $this->logActivity($request, 'update_booking_status', 'booking', $booking->id_booking, $booking->user_id, [
            'status'     => $status,
            'booking_id' => $booking->id_booking,
        ]);

        return back()->with('status', "✅ Pesanan berhasil {$statusText}!");
    }

    public function destroyBooking(Request $request, Booking $booking)
    {
        $this->logActivity($request, 'delete_booking', 'booking', $booking->id_booking, $booking->user_id, [
            'booking_id' => $booking->id_booking,
        ]);

        $booking->delete();
        return back()->with('status', '✅ Data booking berhasil dihapus.');
    }

    // ════════════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ════════════════════════════════════════════════════════════
    private function getReportExportData(): array
    {
        $totalUsers    = User::where('role', 'user')->count();
        $totalOwners   = User::where('role', 'owner')->count();
        $totalKosts    = Kost::count();
        $totalBookings = Booking::count();

        $bookingThisMonth = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ✅ Sinkron: pakai status_booking = selesai
        $totalPendapatanAdmin = Booking::where('status_booking', 'selesai')
            ->sum('komisi_admin');

        $statusStats = Booking::selectRaw('status_booking, COUNT(*) as total')
            ->groupBy('status_booking')
            ->pluck('total', 'status_booking');

        $bookingByStatus = [
            'pending'  => (int) ($statusStats['pending']  ?? 0),
            'diterima' => (int) ($statusStats['diterima'] ?? 0),
            'ditolak'  => (int) ($statusStats['ditolak']  ?? 0),
            'selesai'  => (int) ($statusStats['selesai']  ?? 0),
        ];

        $recentBookings = DB::table('bookings')
            ->join('users as penyewa', 'penyewa.id', '=', 'bookings.user_id')
            ->join('rooms', 'rooms.id_room', '=', 'bookings.room_id')
            ->join('kosts', 'kosts.id_kost', '=', 'rooms.kost_id')
            ->join('users as owner', 'owner.id', '=', 'kosts.owner_id')
            ->select(
                'bookings.id_booking',
                'penyewa.name as nama_penyewa',
                'owner.name as nama_owner',
                'kosts.nama_kost',
                'rooms.nomor_kamar as nama_kamar',
                'bookings.status_booking',
                'bookings.status_pembayaran',
                'bookings.total_bayar',
                'bookings.komisi_admin',
                'bookings.created_at'
            )
            ->latest('bookings.created_at')
            ->limit(20)
            ->get();

        $topKostsThisMonth = DB::table('bookings')
            ->join('rooms', 'rooms.id_room', '=', 'bookings.room_id')
            ->join('kosts', 'kosts.id_kost', '=', 'rooms.kost_id')
            ->join('users as owner', 'owner.id', '=', 'kosts.owner_id')
            ->whereMonth('bookings.created_at', now()->month)
            ->whereYear('bookings.created_at', now()->year)
            ->where('bookings.status_pembayaran', '!=', 'belum')
            ->select(
                'kosts.nama_kost',
                'owner.name as nama_owner',
                DB::raw('COUNT(bookings.id_booking) as total_booking'),
                DB::raw('SUM(bookings.total_bayar) as total_pemasukan')
            )
            ->groupBy('kosts.id_kost', 'kosts.nama_kost', 'owner.name')
            ->orderByDesc('total_booking')
            ->limit(10)
            ->get();

        return compact(
            'totalUsers', 'totalOwners', 'totalKosts', 'totalBookings',
            'bookingThisMonth', 'totalPendapatanAdmin', 'bookingByStatus',
            'recentBookings', 'topKostsThisMonth'
        );
    }

    private function logActivity(
        Request $request,
        string  $action,
        ?string $targetType   = null,
        ?int    $targetId     = null,
        ?int    $targetUserId = null,
        array   $meta         = []
    ): void {
        if (!$this->hasActivityLogTable()) return;

        ActivityLog::create([
            'actor_id'       => $request->user()->id,
            'target_user_id' => $targetUserId,
            'action'         => $action,
            'target_type'    => $targetType,
            'target_id'      => $targetId,
            'ip_address'     => $request->ip(),
            'user_agent'     => (string) $request->userAgent(),
            'meta'           => $meta,
        ]);
    }

    private function hasKostVerificationColumn(): bool
    {
        return Schema::hasColumn('kosts', 'is_verified');
    }

    private function hasUserStatusColumn(): bool
    {
        return Schema::hasColumn('users', 'status_akun');
    }

    private function hasActivityLogTable(): bool
    {
        return Schema::hasTable('activity_logs');
    }
}