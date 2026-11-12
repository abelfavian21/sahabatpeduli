<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kampanye', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/kampanye/{campaign:slug}', [CampaignController::class, 'show'])->name('campaigns.show');
Route::post('/midtrans/notification', \App\Http\Controllers\MidtransController::class)
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
    ->name('midtrans.notification');
Route::get('/donasi/{orderId}/sinkron', [DonationController::class, 'sync'])->middleware('throttle:40,1')->name('donations.sync');
Route::post('/kampanye/{campaign:slug}/donasi', [DonationController::class, 'store'])->name('donations.store');

// Admin
Route::get('/admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'show'])->name('login');
Route::post('/admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->middleware('throttle:6,1')->name('admin.login.post');
Route::post('/admin/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware(\App\Http\Middleware\AdminOnly::class)->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('campaigns', \App\Http\Controllers\Admin\CampaignController::class)->except('show');
    Route::get('donations', [\App\Http\Controllers\Admin\DonationController::class, 'index'])->name('donations.index');
    Route::patch('donations/{donation}', [\App\Http\Controllers\Admin\DonationController::class, 'update'])->name('donations.update');
});

// Berita
Route::get('/berita', [\App\Http\Controllers\ArticleController::class, 'index'])->name('articles.index');
Route::get('/berita/{article:slug}', [\App\Http\Controllers\ArticleController::class, 'show'])->name('articles.show');
Route::prefix('admin')->name('admin.')->middleware(\App\Http\Middleware\AdminOnly::class)->group(function () {
    Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class)->except('show');
});

Route::prefix('admin')->name('admin.')->middleware(\App\Http\Middleware\AdminOnly::class)->group(function () {
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->only(['index', 'update']);
});

// Pengajuan kampanye (publik membuat tiket, admin yang memproses)
Route::get('/ajukan-kampanye', [\App\Http\Controllers\CampaignRequestController::class, 'create'])->name('requests.create');
Route::post('/ajukan-kampanye', [\App\Http\Controllers\CampaignRequestController::class, 'store'])->middleware('throttle:5,60')->name('requests.store');
Route::get('/ajukan-kampanye/status', [\App\Http\Controllers\CampaignRequestController::class, 'statusForm'])->name('requests.status');
Route::post('/ajukan-kampanye/status', [\App\Http\Controllers\CampaignRequestController::class, 'statusCheck'])->middleware('throttle:10,1')->name('requests.check');

Route::prefix('admin')->name('admin.')->middleware(\App\Http\Middleware\AdminOnly::class)->group(function () {
    $c = \App\Http\Controllers\Admin\CampaignRequestController::class;
    Route::get('requests', [$c, 'index'])->name('requests.index');
    Route::get('requests/{campaignRequest}', [$c, 'show'])->name('requests.show');
    Route::patch('requests/{campaignRequest}', [$c, 'update'])->name('requests.update');
    Route::post('requests/{campaignRequest}/convert', [$c, 'convert'])->name('requests.convert');
    Route::get('requests/{campaignRequest}/file/{type}/{i?}', [$c, 'file'])->name('requests.file');
    Route::delete('requests/{campaignRequest}', [$c, 'destroy'])->name('requests.destroy');
});
