<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\SeoDataController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\WebsiteSettingController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;



Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // website setting Routes 
    Route::get('websiteSettings', [WebsiteSettingController::class, 'index'])->name('website.settings');
    Route::put('website-info-update', [WebsiteSettingController::class, 'websiteInfoUpdate'])->name('website.info.update');
    Route::put('personal-info-update', [WebsiteSettingController::class, 'personalInfoUpdate'])->name('personal.info.update');
    Route::resource('user', UserController::class);
    Route::resource('seoData', SeoDataController::class);
    Route::resource('permission', PermissionController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('projects', RoleController::class);
    Route::resource('developers', RoleController::class);
    Route::resource('amenities', RoleController::class);
    Route::resource('units', RoleController::class);
    Route::resource('blogs', RoleController::class);
    Route::resource('blogCategories', RoleController::class);
    Route::resource('marketInsights', RoleController::class);
    Route::resource('announcements', RoleController::class);
    Route::resource('offers', RoleController::class);
    Route::resource('constructionUpdates', RoleController::class);
    Route::resource('agents', RoleController::class);
    Route::resource('buyers', RoleController::class);
    Route::resource('owners', RoleController::class);
    Route::resource('maintanance', RoleController::class);
    Route::resource('maintananceRequests', RoleController::class);
    Route::resource('enquiries', RoleController::class);
    Route::resource('viewings', RoleController::class);
    Route::resource('favourites', RoleController::class);
    Route::resource('payments', RoleController::class);
    Route::resource('paymentSchedules', RoleController::class);
});

require __DIR__.'/auth.php';
