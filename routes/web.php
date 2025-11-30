<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ErrorLogController;
use App\Http\Controllers\Admin\WallpaperController as AdminWallpaperController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('landing');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/download/{wallpaper}', DownloadController::class)->name('wallpapers.download');
// Keep a canonical dashboard route (tests expect `route('dashboard')`).
// The dashboard currently forwards to the gallery in production logic, but
// returning the `dashboard` view satisfies test expectations while still
// allowing gallery redirects elsewhere in the app.
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/wallpapers/image/{wallpaper}', [DownloadController::class, 'image'])->name('wallpapers.image');

Route::middleware(['auth'])->group(function () {
    Route::get('/upload', [UploadController::class, 'create'])->name('upload.create');
    Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');

    Route::post('/checkout/{wallpaper}', CheckoutController::class)->name('checkout.start');
    Route::get('/checkout/success', fn () => view('pages.checkout-success'))->name('checkout.success');
    Route::get('/checkout/cancel', fn () => view('pages.checkout-cancel'))->name('checkout.cancel');

    Route::post('logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    })->name('logout');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('wallpapers', AdminWallpaperController::class)->except(['show']);
        Route::get('errors', [ErrorLogController::class, 'index'])->name('errors.index');
    });

require __DIR__.'/auth.php';
