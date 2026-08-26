<?php

use App\Http\Controllers\Api\AppConfigController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DesignPhilosophyController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\Api\KioskController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\EnquiryController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\Api\ViewingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes  (prefix: /api/v1/...)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ── App Config (public) ────────────────────────────────────────────────
    Route::get('app-config', [AppConfigController::class, 'index']);

    // ── Design Philosophy (public) ─────────────────────────────────────────
    Route::get('design-philosophy', [DesignPhilosophyController::class, 'index']);

    // ── Auth (public) ──────────────────────────────────────────────────────
    Route::post('auth/register',   [AuthController::class, 'register']);
    Route::post('auth/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('auth/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('auth/login',      [AuthController::class, 'login']);
    Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('auth/reset-password',  [AuthController::class, 'resetPassword']);

    // ── Projects (public — guest users can browse) ─────────────────────────
    Route::get('projects',              [ProjectController::class, 'index']);
    Route::get('projects/{slug}',       [ProjectController::class, 'show']);
    Route::get('projects/{slug}/units',                [ProjectController::class, 'units']);
    Route::get('projects/{slug}/construction-updates', [ProjectController::class, 'constructionUpdates']);

    // ── Offers (public — guest users can browse) ───────────────────────────
    Route::get('offers',      [OfferController::class, 'index']);
    Route::get('offers/{id}', [OfferController::class, 'show']);

    // ── Communities (public) ───────────────────────────────────────────────
    Route::get('communities',           [CommunityController::class, 'index']);
    Route::get('communities/{slug}',    [CommunityController::class, 'show']);

    // ── Events (public) ────────────────────────────────────────────────────
    Route::get('events',                [EventController::class, 'index']);
    Route::get('events/{slug}',         [EventController::class, 'show']);
    Route::post('events/{slug}/register', [EventController::class, 'register']);

    // ── Content (public) ───────────────────────────────────────────────────
    Route::get('blogs',                         [ContentController::class, 'blogs']);
    Route::get('blogs/{slug}',                  [ContentController::class, 'showBlog']);
    Route::get('market-insights',               [ContentController::class, 'marketInsights']);
    Route::get('market-insights/{slug}',        [ContentController::class, 'showMarketInsight']);
    Route::get('announcements',                 [ContentController::class, 'announcements']);

    // ── News (public) ──────────────────────────────────────────────────────
    Route::get('news',        [NewsController::class, 'index']);
    Route::get('news/{slug}', [NewsController::class, 'show']);

    // ── Kiosk Slides (public) ─────────────────────────────────────────────────
    Route::get('kiosk-slides', [KioskController::class, 'index']);

    // ── Team Members (public) ──────────────────────────────────────────────
    Route::get('team-members', [TeamMemberController::class, 'index']);
    Route::get('team-members/{slug}', [TeamMemberController::class, 'show']);

    // ── Enquiries & Viewings (public — guests may submit, optional auth) ───
    Route::post('enquiries', [EnquiryController::class, 'store']);
    Route::post('viewings',  [ViewingController::class, 'store']);

    // ── Device tokens (public — a token can be registered before login) ────
    Route::post('device-tokens', [DeviceTokenController::class, 'store']);

    // ── Protected (requires Sanctum token) ────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout',   [AuthController::class, 'logout']);
        Route::get('auth/me',        [AuthController::class, 'me']);
        Route::put('auth/profile',   [AuthController::class, 'updateProfile']);
        Route::delete('auth/account', [AuthController::class, 'deleteAccount']);
        Route::post('auth/broker-documents', [AuthController::class, 'uploadBrokerDocuments']);

        // Announcements for logged-in users (includes user-targeted ones)
        Route::get('announcements/user', [ContentController::class, 'announcements']);

        // Push notifications (bell icon list) + per-category preferences
        Route::get('notifications',                 [NotificationController::class, 'index']);
        Route::get('notifications/unread-count',    [NotificationController::class, 'unreadCount']);
        Route::post('notifications/read-all',       [NotificationController::class, 'markAllRead']);
        Route::post('notifications/{notification}/read', [NotificationController::class, 'markRead']);
        Route::get('notification-settings',         [NotificationController::class, 'settings']);
        Route::put('notification-settings',         [NotificationController::class, 'updateSettings']);
    });
});
