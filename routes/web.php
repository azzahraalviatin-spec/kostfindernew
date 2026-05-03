<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KosController;
use App\Http\Controllers\User\KeluhanController as UserKeluhanController;
use App\Http\Controllers\Owner\KeluhanController as OwnerKeluhanController;
use App\Http\Controllers\Owner\KamarController;

// ✅ HAPUS baris ini (salah namespace, tidak ada Controller-nya):
// Route::get('kost/{kost}', 'KostController@show');

Route::get('/carikos', [KosController::class, 'cari'])->name('carikos');

// ── HOMEPAGE ──
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/cari-kost', [App\Http\Controllers\KostDetailController::class, 'index'])->name('kost.cari');
Route::get('/hubungi-kami', [App\Http\Controllers\HubungiController::class, 'index'])->name('hubungi.kami');
Route::post('/hubungi-kami', [App\Http\Controllers\HubungiController::class, 'store'])->name('hubungi.store');

// ── DETAIL KOST PUBLIK ──
Route::get('/kost/{kost}', [App\Http\Controllers\KostDetailController::class, 'show'])->name('kost.show');

// ── LANDING PEMILIK KOST ──
Route::get('/panduan-pemilik-kost', [App\Http\Controllers\OwnerLandingController::class, 'index'])->name('owner.landing');
Route::view('/panduan', 'panduan')->name('panduan');

// ── DASHBOARD USER ──
Route::middleware(['auth', 'role.user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::get('/edit-profil', fn() => view('user.edit-profil'))->name('profil.edit');
    Route::patch('/edit-profil', [App\Http\Controllers\User\ProfilController::class, 'update'])->name('profil.update');
    Route::patch('/pengaturan/privasi', [App\Http\Controllers\User\PengaturanController::class, 'updatePrivasi'])->name('pengaturan.privasi');
    Route::patch('/pengaturan/notifikasi', [App\Http\Controllers\User\PengaturanController::class, 'updateNotifikasi'])->name('pengaturan.notifikasi');
    Route::patch('/verifikasi/email', [App\Http\Controllers\User\VerifikasiController::class, 'updateEmail'])->name('verifikasi.email');
    Route::patch('/verifikasi/hp', [App\Http\Controllers\User\VerifikasiController::class, 'updateHp'])->name('verifikasi.hp');
    Route::patch('/verifikasi/identitas', [App\Http\Controllers\User\VerifikasiController::class, 'updateIdentitas'])->name('verifikasi.identitas');
    Route::get('/favorit', [App\Http\Controllers\User\FavoritController::class, 'index'])->name('favorit');
    Route::delete('/favorit/clear-history', [App\Http\Controllers\User\FavoritController::class, 'clearHistory'])->name('favorit.clearHistory');

    Route::get('/booking', [App\Http\Controllers\User\BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking', [App\Http\Controllers\User\BookingController::class, 'store'])->name('booking.store');
    Route::patch('/booking/{id}/cancel', [App\Http\Controllers\User\BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/booking/{id}/pembayaran', [App\Http\Controllers\User\BookingController::class, 'pembayaran'])->name('booking.pembayaran');
    Route::post('/booking/{id}/bayar', [App\Http\Controllers\User\BookingController::class, 'bayar'])->name('booking.bayar');
    Route::post('/favorit/toggle', [App\Http\Controllers\User\FavoritController::class, 'toggle'])->name('favorit.toggle');
    Route::delete('/favorit/{id}', [App\Http\Controllers\User\FavoritController::class, 'destroy'])->name('favorit.destroy');
    Route::post('/review', [App\Http\Controllers\User\ReviewController::class, 'store'])->name('review.store');
    Route::get('/ulasan', [App\Http\Controllers\User\ReviewController::class, 'index'])->name('ulasan.index');
    Route::get('/verifikasi', [App\Http\Controllers\User\VerifikasiController::class, 'index'])->name('verifikasi.index');
    Route::get('/pengaturan', [App\Http\Controllers\User\PengaturanController::class, 'index'])->name('pengaturan.index');
});

// ── DASHBOARD ADMIN ──
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role.admin'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // USER
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::patch('/users/{user}/verify', [AdminController::class, 'verifyUser'])->name('users.verify');
    Route::patch('/users/{user}/reject', [AdminController::class, 'rejectUser'])->name('users.reject');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // OWNER
    Route::get('/owners', [AdminController::class, 'owners'])->name('owners');
    Route::get('/owners/{owner}', [AdminController::class, 'showOwner'])->name('owners.show');
    Route::patch('/owners/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('owners.toggle-status');
    Route::delete('/owners/{user}', [AdminController::class, 'destroyUser'])->name('owners.destroy');
    Route::patch('/owners/{owner}/verify-identity', [AdminController::class, 'verifyOwner'])->name('owners.verify-identity');
    Route::patch('/owners/{owner}/reject-identity', [AdminController::class, 'rejectOwner'])->name('owners.reject-identity');

    // KOST
    Route::get('/kosts', [AdminController::class, 'kosts'])->name('kosts');
    Route::get('/kosts/{kost}', [AdminController::class, 'showKost'])->name('kosts.show');
    Route::patch('/kosts/{kost}/verify', [AdminController::class, 'verifyKost'])->name('kosts.verify');
    Route::patch('/kosts/{kost}/toggle-status', [AdminController::class, 'toggleKostStatus'])->name('kosts.toggle-status');
    Route::delete('/kosts/{kost}', [AdminController::class, 'destroyKost'])->name('kosts.destroy');

    // BOOKING
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}', [AdminController::class, 'showBooking'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status/{status}', [AdminController::class, 'updateBookingStatus'])->name('bookings.update-status');
    Route::delete('/bookings/{booking}', [AdminController::class, 'destroyBooking'])->name('bookings.destroy');

    // ULASAN
    Route::get('/ulasan', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/ulasan/{id}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/ulasan/{id}/reject', [App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');

  
    // REPORT
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/export/csv', [AdminController::class, 'exportReportsCsv'])->name('reports.export.csv');
    Route::get('/reports/export/pdf', [AdminController::class, 'exportReportsPdf'])->name('reports.export.pdf');
    Route::get('/reports/export/word', [AdminController::class, 'exportReportsWord'])->name('reports.export.word');

    // ACTIVITY
    Route::get('/activities', [AdminController::class, 'activities'])->name('activities');

    // SETTINGS
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

    // NOTIFIKASI
    Route::get('/notifications/read-all', [AdminController::class, 'readAllNotifications'])->name('notifications.readAll');
    Route::get('/notifications/{id}/read', [AdminController::class, 'readNotification'])->name('notifications.read');
});

// ── PROFILE ──
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── OWNER ROUTES ──
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role.owner'])->group(function () {
    Route::patch('kamar/bulk-update', [KamarController::class, 'bulkUpdate'])->name('kamar.bulkUpdate');
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [App\Http\Controllers\Owner\DashboardController::class, 'search'])->name('search');

    // KELUHAN
    Route::prefix('keluhan')->name('keluhan.')->group(function () {
        Route::get('/', [OwnerKeluhanController::class, 'index'])->name('index');
        Route::get('/{id}', [OwnerKeluhanController::class, 'show'])->name('show');
        Route::post('/update-status/{id}', [OwnerKeluhanController::class, 'updateStatus'])->name('updateStatus');
    });

    // ✅ KOST — pakai full namespace, HANYA 1x resource, tambah destroyImage
    Route::resource('kost', App\Http\Controllers\Owner\KostController::class);
    Route::delete('kost/{kost}/image/{image}', [App\Http\Controllers\Owner\KostController::class, 'destroyImage'])
        ->name('kost.image.destroy');
    Route::delete('kost/{kost}/facility/{facility}', [App\Http\Controllers\Owner\KostController::class, 'destroyFacility'])
        ->name('kost.facility.destroy');

    // KAMAR
    Route::get('kamar/bulk-edit-detail', [KamarController::class, 'bulkEditDetail'])->name('kamar.bulkEditDetail');
    Route::post('kamar/bulk-update-detail', [KamarController::class, 'bulkUpdateDetail'])->name('kamar.bulkUpdateDetail');
    Route::resource('kamar', App\Http\Controllers\Owner\KamarController::class);

    // BOOKING
    Route::get('/booking', [App\Http\Controllers\Owner\BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{booking}', [App\Http\Controllers\Owner\BookingController::class, 'show'])->name('booking.show');
    Route::patch('/booking/{booking}/terima', [App\Http\Controllers\Owner\BookingController::class, 'terima'])->name('booking.terima');
    Route::patch('/booking/{booking}/tolak', [App\Http\Controllers\Owner\BookingController::class, 'tolak'])->name('booking.tolak');
    Route::patch('/booking/{booking}/selesai', [App\Http\Controllers\Owner\BookingController::class, 'selesai'])->name('booking.selesai');

    // STATISTIK
    Route::get('/statistik', [App\Http\Controllers\Owner\StatistikController::class, 'index'])->name('statistik');
    Route::get('/export-excel', [App\Http\Controllers\Owner\StatistikController::class, 'exportExcel'])->name('export.excel');

    // PENGATURAN
    Route::get('/pengaturan', [App\Http\Controllers\Owner\PengaturanController::class, 'index'])->name('pengaturan');
    Route::patch('/pengaturan', [App\Http\Controllers\Owner\PengaturanController::class, 'update'])->name('pengaturan.update');
    Route::patch('/pengaturan/password', [App\Http\Controllers\Owner\PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
    Route::patch('/pengaturan/notifikasi', [App\Http\Controllers\Owner\PengaturanController::class, 'updateNotifikasi'])->name('pengaturan.notifikasi');
    Route::post('/pengaturan/bank', [App\Http\Controllers\Owner\PengaturanController::class, 'storeBank'])->name('pengaturan.bank.store');
    Route::delete('/pengaturan/bank/{id}', [App\Http\Controllers\Owner\PengaturanController::class, 'deleteBank'])->name('pengaturan.bank.delete');
    Route::delete('/pengaturan/akun', [App\Http\Controllers\Owner\PengaturanController::class, 'hapusAkun'])->name('akun.hapus');

    // ULASAN
    Route::get('/ulasan', [App\Http\Controllers\Owner\ReviewController::class, 'index'])->name('review.index');
    Route::post('/ulasan', [App\Http\Controllers\Owner\ReviewController::class, 'store'])->name('review.store');
    Route::post('/ulasan/{review}/reply', [App\Http\Controllers\Owner\ReviewController::class, 'reply'])->name('review.reply');
    Route::get('/ulasan/{review}/approve', [App\Http\Controllers\Owner\ReviewController::class, 'approve'])->name('review.approve');
    Route::post('/ulasan/{review}/report', [App\Http\Controllers\Owner\ReviewController::class, 'report'])->name('review.report');
});

// ── USER KELUHAN ──
Route::get('/keluhan', [UserKeluhanController::class, 'index'])->name('keluhan.index');
Route::get('/keluhan/pilih', [UserKeluhanController::class, 'pilih'])->name('keluhan.pilih');
Route::get('/keluhan/create/{id}', [UserKeluhanController::class, 'create'])->name('keluhan.create');
Route::post('/keluhan/store', [UserKeluhanController::class, 'store'])->name('keluhan.store');

// Fallback logout GET
Route::get('/logout', function () {
    return redirect('/');
})->name('logout.get');

require __DIR__.'/auth.php';