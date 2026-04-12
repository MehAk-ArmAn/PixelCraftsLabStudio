<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Service;
use App\Models\Setting;

class ServicesController extends Controller
{
    public function index()
    {
        return view('services', [
            'setting' => Setting::first(),
            'page' => Page::where('page_key', 'services')->first(),
            'services' => Service::latest()->get(),
        ]);
    }
}
