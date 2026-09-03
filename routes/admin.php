<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactOptionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\GrowthPlanController;
use App\Http\Controllers\Admin\HomepageFeaturedProjectController;
use App\Http\Controllers\Admin\InteractiveExperienceController;
use App\Http\Controllers\Admin\MarketingCampaignController;
use App\Http\Controllers\Admin\MarketingChannelController;
use App\Http\Controllers\Admin\MarketingOverviewController;
use App\Http\Controllers\Admin\MarketingServiceController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\NavigationItemController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PreviewController;
use App\Http\Controllers\Admin\ProcessStageController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectMetricController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/**
 * Every schema-driven CMS resource exposes the same seven endpoints.
 * A closure, not a named function: this file is re-included on every app boot.
 */
$resourceRoutes = function (string $base, string $controller): void {
    Route::get($base, [$controller, 'index'])->name($base.'.index');
    Route::get($base.'/create', [$controller, 'create'])->name($base.'.create');
    Route::post($base, [$controller, 'store'])->name($base.'.store');
    Route::post($base.'/reorder', [$controller, 'reorder'])->name($base.'.reorder');
    Route::get($base.'/{record}/edit', [$controller, 'edit'])->name($base.'.edit');
    Route::put($base.'/{record}', [$controller, 'update'])->name($base.'.update');
    Route::delete($base.'/{record}', [$controller, 'destroy'])->name($base.'.destroy');
};

Route::prefix('admin')->name('admin.')->group(function () use ($resourceRoutes) {
    /*
     * Guest routes. There is deliberately no registration route — accounts are
     * created with `php artisan pcl:admin` or by a super admin in the panel.
     */
    Route::middleware('guest.admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:pcl-login')
            ->name('login.attempt');
    });

    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () use ($resourceRoutes) {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('preview', PreviewController::class)->name('preview');
        Route::get('home/featured-projects', [HomepageFeaturedProjectController::class, 'index'])->name('home.featured-projects.index');
        Route::put('home/featured-projects', [HomepageFeaturedProjectController::class, 'update'])->name('home.featured-projects.update');

        // ---------------------------------------------------------- content
        $resourceRoutes('projects', ProjectController::class);
        Route::post('projects/{project}/duplicate', [ProjectController::class, 'duplicate'])->name('projects.duplicate');
        Route::post('projects/{project}/toggle-publish', [ProjectController::class, 'togglePublish'])->name('projects.toggle-publish');
        Route::post('projects/{project}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
        Route::post('projects/{project}/metrics', [ProjectMetricController::class, 'store'])->name('projects.metrics.store');
        Route::put('projects/{project}/metrics/{metric}', [ProjectMetricController::class, 'update'])->name('projects.metrics.update');
        Route::delete('projects/{project}/metrics/{metric}', [ProjectMetricController::class, 'destroy'])->name('projects.metrics.destroy');

        $resourceRoutes('services', ServiceController::class);
        $resourceRoutes('process', ProcessStageController::class);
        $resourceRoutes('team', TeamMemberController::class);
        $resourceRoutes('socials', SocialLinkController::class);
        $resourceRoutes('testimonials', TestimonialController::class);
        $resourceRoutes('experiences', InteractiveExperienceController::class);
        $resourceRoutes('navigation', NavigationItemController::class);
        // -------------------------------------------------------- marketing
        Route::get('marketing', MarketingOverviewController::class)->name('marketing.overview');
        $resourceRoutes('marketing-services', MarketingServiceController::class);
        $resourceRoutes('channels', MarketingChannelController::class);
        $resourceRoutes('campaigns', MarketingCampaignController::class);
        $resourceRoutes('growth-plans', GrowthPlanController::class);
        $resourceRoutes('packages', PackageController::class);
        Route::post('growth-plans/{growthPlan}/items', [GrowthPlanController::class, 'storeItem'])->name('growth-plans.items.store');
        Route::put('growth-plans/{growthPlan}/items/{item}', [GrowthPlanController::class, 'updateItem'])->name('growth-plans.items.update');
        Route::delete('growth-plans/{growthPlan}/items/{item}', [GrowthPlanController::class, 'destroyItem'])->name('growth-plans.items.destroy');
        Route::post('packages/{package}/items', [PackageController::class, 'storeItem'])->name('packages.items.store');
        Route::put('packages/{package}/items/{item}', [PackageController::class, 'updateItem'])->name('packages.items.update');
        Route::delete('packages/{package}/items/{item}', [PackageController::class, 'destroyItem'])->name('packages.items.destroy');

        // ------------------------------------------------------------ pages
        Route::get('pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('pages/{page}', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('pages/{page}', [PageController::class, 'update'])->name('pages.update');
        Route::post('pages/{page}/restore', [PageController::class, 'restore'])->name('pages.restore');
        Route::put('pages/{page}/sections/{section}', [PageController::class, 'updateSection'])->name('pages.sections.update');
        Route::post('pages/{page}/sections/{section}/restore', [PageController::class, 'restoreSection'])->name('pages.sections.restore');
        Route::post('pages/{page}/sections/{section}/toggle', [PageController::class, 'toggleSection'])->name('pages.sections.toggle');

        // ------------------------------------------------------------ media
        Route::get('media', [MediaController::class, 'index'])->name('media.index');
        Route::get('media/browse', [MediaController::class, 'browse'])->name('media.browse');
        Route::post('media', [MediaController::class, 'store'])->name('media.store');
        Route::post('media/import-legacy', [MediaController::class, 'importLegacy'])->name('media.import-legacy');
        Route::put('media/{medium}', [MediaController::class, 'update'])->name('media.update');
        Route::post('media/{medium}/replace', [MediaController::class, 'replace'])->name('media.replace');
        Route::delete('media/{medium}', [MediaController::class, 'destroy'])->name('media.destroy');

        Route::middleware('can:manage-administration')->group(function () use ($resourceRoutes) {
            // ---------------------------------------------------- enquiries
            Route::get('enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
            Route::get('enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
            Route::put('enquiries/{enquiry}', [EnquiryController::class, 'update'])->name('enquiries.update');
            Route::post('enquiries/{enquiry}/toggle-read', [EnquiryController::class, 'toggleRead'])->name('enquiries.toggle-read');
            Route::delete('enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');

            // ----------------------------------------------------- settings
            $resourceRoutes('contact-options', ContactOptionController::class);
            Route::get('settings/{group?}', [SettingsController::class, 'edit'])->name('settings.edit');
            Route::put('settings/{group}', [SettingsController::class, 'update'])->name('settings.update');
            Route::post('settings/restore/{setting}', [SettingsController::class, 'restore'])->name('settings.restore');
        });

        // --------------------------------------------------------- security
        Route::middleware('can:manage-security')->group(function () {
            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::get('users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('users', [UserController::class, 'store'])->name('users.store');
            Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::get('activity', [ActivityController::class, 'index'])->name('activity.index');
        });
    });
});
