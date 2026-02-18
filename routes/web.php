<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing Page Route
|--------------------------------------------------------------------------
| This route loads the main landing page containing the big form.
*/
Route::get('/', function () {
    return view('pages.landing');
});


