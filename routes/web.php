<?php

use App\Http\Controllers\ContactSubmissionController;
use App\Http\Controllers\PixelCraftsLabSiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site
|--------------------------------------------------------------------------
| The locked Claude Design document is served raw by the controller; the CMS
| payload is injected into <head> by SiteRenderer.
*/

Route::get('/', PixelCraftsLabSiteController::class)->name('home');

Route::post('/contact', [ContactSubmissionController::class, 'store'])
    ->middleware('throttle:pcl-contact')
    ->name('contact.store');

require __DIR__.'/admin.php';
