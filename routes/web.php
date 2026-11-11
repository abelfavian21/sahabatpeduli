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
