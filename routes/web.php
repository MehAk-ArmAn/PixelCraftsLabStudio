<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ContactController;
 use App\Http\Controllers\TestimonialController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Public site and admin panel routes.
|
*/

// ================== PUBLIC ROUTES ==================
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Form submission route
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


// ================== ADMIN ROUTES ==================
// Prefix with /admin and protect later with auth middleware
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Services CRUD
    Route::resource('services', AdminServiceController::class);

    // Portfolio CRUD
    Route::resource('portfolio', AdminPortfolioController::class);

    // Messages CRUD
    Route::resource('messages', AdminMessageController::class);

Route::post('/testimonial', [TestimonialController::class, 'store']);
});

