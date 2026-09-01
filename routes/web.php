<?php

use App\Http\Controllers\PixelCraftsLabSiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', PixelCraftsLabSiteController::class)->name('home');
