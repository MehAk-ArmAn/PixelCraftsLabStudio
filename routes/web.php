<?php

use App\Http\Controllers\ContactSubmissionController;
use App\Http\Controllers\PixelCraftsLabSiteController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site
|--------------------------------------------------------------------------
| The locked Claude Design document is served raw by the controller; the CMS
| payload is injected into <head> by SiteRenderer.
*/

Route::get('/', PixelCraftsLabSiteController::class)->name('home');
Route::get('/work', PixelCraftsLabSiteController::class)->defaults('siteRoute', 'work')->name('work.index');
Route::get('/services', PixelCraftsLabSiteController::class)->defaults('siteRoute', 'services')->name('services.index');
Route::get('/marketing', PixelCraftsLabSiteController::class)->defaults('siteRoute', 'marketing')->name('marketing.index');
Route::get('/pricing', PixelCraftsLabSiteController::class)->defaults('siteRoute', 'pricing')->name('pricing.index');
Route::get('/studio', PixelCraftsLabSiteController::class)->defaults('siteRoute', 'studio')->name('studio');
Route::get('/lab', PixelCraftsLabSiteController::class)->defaults('siteRoute', 'lab')->name('lab');
Route::get('/contact', PixelCraftsLabSiteController::class)->defaults('siteRoute', 'contact')->name('contact');
Route::get('/work/{project:slug}', PixelCraftsLabSiteController::class)
    ->defaults('siteRoute', 'project')
    ->name('projects.show');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::post('/contact', [ContactSubmissionController::class, 'store'])
    ->middleware('throttle:pcl-contact')
    ->name('contact.store');

require __DIR__.'/admin.php';
