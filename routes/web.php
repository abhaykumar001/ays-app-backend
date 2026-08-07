<?php

use App\Http\Controllers\Dashboard\AccommodationsController;
use App\Http\Controllers\Dashboard\AgentsController;
use App\Http\Controllers\Dashboard\AmenitiesController;
use App\Http\Controllers\Dashboard\AnnouncementsController;
use App\Http\Controllers\Dashboard\AuditLogsController;
use App\Http\Controllers\Dashboard\BlogsController;
use App\Http\Controllers\Dashboard\NewsController;
use App\Http\Controllers\Dashboard\BookingCancellationsController;
use App\Http\Controllers\Dashboard\BookingPaymentsController;
use App\Http\Controllers\Dashboard\BookingsController;
use App\Http\Controllers\Dashboard\BuyersController;
use App\Http\Controllers\Dashboard\CommunitiesController;
use App\Http\Controllers\Dashboard\ConstructionUpdatesController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EnquiriesController;
use App\Http\Controllers\Dashboard\EventRegistrationsController;
use App\Http\Controllers\Dashboard\EventRequestsController;
use App\Http\Controllers\Dashboard\EventsController;
use App\Http\Controllers\Dashboard\HighlightsController;
use App\Http\Controllers\Dashboard\MaintananceController;
use App\Http\Controllers\Dashboard\MaintananceRequestsController;
use App\Http\Controllers\Dashboard\MarketInsightsController;
use App\Http\Controllers\Dashboard\NearbyPlacesController;
use App\Http\Controllers\Dashboard\NotificationsController;
use App\Http\Controllers\Dashboard\NotificationSettingsController;
use App\Http\Controllers\Dashboard\OffersController;
use App\Http\Controllers\Dashboard\OwnersController;
use App\Http\Controllers\Dashboard\PaymentPlansController;
use App\Http\Controllers\Dashboard\PaymentSchedulesController;
use App\Http\Controllers\Dashboard\PaymentsController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\PhasesController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\ProjectOffersController;
use App\Http\Controllers\Dashboard\ProjectsController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\SeoDataController;
use App\Http\Controllers\Dashboard\TagController;
use App\Http\Controllers\Dashboard\TeamMembersController;
use App\Http\Controllers\Dashboard\TeamMemberCategoriesController;
use App\Http\Controllers\Dashboard\UnitMediaController;
use App\Http\Controllers\Dashboard\UnitsController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\ViewingsController;
use App\Http\Controllers\Dashboard\VirtualToursController;
use App\Http\Controllers\Dashboard\DesignPhilosophyController;
use App\Http\Controllers\Dashboard\DesignPhilosophySectionController;
use App\Http\Controllers\Dashboard\KioskSlidesController;
use App\Http\Controllers\Dashboard\WebsiteSettingController;
use App\Http\Controllers\LegalController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// Public legal pages — no auth required, so Play Store / App Store reviewers
// and the mobile app can link to them without logging into the dashboard.
Route::get('/privacy-policy', [LegalController::class, 'privacyPolicy'])->name('legal.privacy');
Route::get('/terms-and-conditions', [LegalController::class, 'terms'])->name('legal.terms');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->prefix('dashboard')->group(function () {

    // Design Philosophy
    Route::get('design-philosophy', [DesignPhilosophyController::class, 'edit'])->name('design-philosophy.edit');
    Route::put('design-philosophy', [DesignPhilosophyController::class, 'update'])->name('design-philosophy.update');
    Route::resource('design-philosophy/sections', DesignPhilosophySectionController::class)
        ->names('design-philosophy.sections')
        ->parameters(['sections' => 'section']);
    Route::delete('design-philosophy/sections/{section}/media/{mediaId}', [DesignPhilosophySectionController::class, 'destroyMedia'])
        ->name('design-philosophy.sections.media.destroy');

    // Projects
    Route::resource('projects', ProjectsController::class);
    Route::delete('projects/{project}/media/{media}', [ProjectsController::class, 'destroyMedia'])->name('projects.media.destroy');
    Route::prefix('projects/{project}')->name('projects.')->group(function () { Route::resource('phases', PhasesController::class); });
    Route::prefix('projects/{project}')->name('projects.')->group(function () { Route::resource('highlights', HighlightsController::class); });
    Route::prefix('projects/{project}')->name('projects.')->group(function () { Route::resource('virtualTours', VirtualToursController::class); });
    Route::prefix('projects/{project}')->name('projects.')->group(function () { Route::resource('paymentPlans', PaymentPlansController::class); });
    Route::prefix('projects/{project}')->name('projects.')->group(function () { Route::resource('units', UnitsController::class); });
    Route::prefix('projects/{project}')->name('projects.')->group(function () {
        Route::resource('constructionUpdates', ConstructionUpdatesController::class);
        Route::delete('constructionUpdates/{constructionUpdate}/media/{media}', [ConstructionUpdatesController::class, 'destroyMedia'])->name('constructionUpdates.media.destroy');
    });
    Route::prefix('projects/{project}')->name('projects.')->group(function () { Route::resource('projectOffers', ProjectOffersController::class); });
    Route::resource('amenities', AmenitiesController::class);
    Route::resource('communities', CommunitiesController::class);
    Route::prefix('communities/{community}')->name('communities.')->group(function () { Route::resource('nearbyPlaces', NearbyPlacesController::class); });
    Route::resource('accommodations', AccommodationsController::class);

    // Units
    Route::prefix('units/{unit}')->name('units.')->group(function () { Route::resource('unitMedia', UnitMediaController::class); });

    // Bookings
    Route::resource('bookings/reservations', BookingsController::class);
    Route::put('bookings/payments', [BookingPaymentsController::class, 'index'])->name('bookings.payments');

    Route::resource('bookings/cancellations', BookingCancellationsController::class);

    // Events
    Route::resource('events', EventsController::class);
    Route::resource('events/requests', EventRequestsController::class);
    Route::resource('events/registrations', EventRegistrationsController::class);

    // Leads / Enquiries
    Route::resource('enquiries', EnquiriesController::class)->except(['create', 'store', 'show']);
    Route::resource('viewings', ViewingsController::class)->except(['create', 'store', 'show']);

    // Agents / Buyers / Updates
    Route::resource('agents', AgentsController::class);
    Route::resource('buyers', BuyersController::class);

    // Content Management
    Route::resource('blogs', BlogsController::class);
    Route::resource('news', NewsController::class);
    Route::resource('kioskSlides', KioskSlidesController::class);
    Route::resource('teamMembers', TeamMembersController::class);
    Route::get('teamMemberCategories', [TeamMemberCategoriesController::class, 'index'])->name('teamMemberCategories.index');
    Route::post('teamMemberCategories', [TeamMemberCategoriesController::class, 'store'])->name('teamMemberCategories.store');
    Route::put('teamMemberCategories/{teamMemberCategory}', [TeamMemberCategoriesController::class, 'update'])->name('teamMemberCategories.update');
    Route::delete('teamMemberCategories/{teamMemberCategory}', [TeamMemberCategoriesController::class, 'destroy'])->name('teamMemberCategories.destroy');
    Route::post('teamMemberCategory/addNew', [TeamMemberCategoriesController::class, 'addNewCategory'])->name('teamMemberCategory.addNew');
    Route::resource('tags', TagController::class);
    Route::post('tag/addNewTag', [TagController::class, 'addNewTag'])->name('tag.addNewTag');
    Route::resource('marketInsights', MarketInsightsController::class);
    Route::resource('announcements', AnnouncementsController::class);
    Route::resource('offers', OffersController::class);

    // Notifications
    Route::resource('notifications', NotificationsController::class);
    Route::resource('notifications/settings', NotificationSettingsController::class);

    // Property Management
    Route::resource('maintanance', MaintananceController::class);
    Route::resource('owners', OwnersController::class);
    Route::resource('payments', PaymentsController::class);
    Route::resource('paymentSchedules', PaymentSchedulesController::class);
    Route::resource('maintananceRequests', MaintananceRequestsController::class);


    // Audit Logs / SEO / Profile / Settings
    Route::resource('auditLogs', AuditLogsController::class);
    Route::resource('seoData', SeoDataController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // website setting Routes 
    Route::get('websiteSettings', [WebsiteSettingController::class, 'index'])->name('website.settings');
    Route::put('website-info-update', [WebsiteSettingController::class, 'websiteInfoUpdate'])->name('website.info.update');
    Route::put('personal-info-update', [WebsiteSettingController::class, 'personalInfoUpdate'])->name('personal.info.update');
    Route::resource('user', UserController::class);
    Route::patch('user/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('user.toggleStatus');
    Route::resource('seoData', SeoDataController::class);
    Route::resource('permission', PermissionController::class);
    Route::resource('roles', RoleController::class);
});

require __DIR__ . '/auth.php';
