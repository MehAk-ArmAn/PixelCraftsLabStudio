<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Portfolio;
use App\Models\Setting;

class PortfolioController extends Controller
{
    public function index()
    {
        return view('portfolio', [
            'setting' => Setting::first(),
            'page' => Page::where('page_key', 'portfolio')->first(),
            'projects' => Portfolio::latest()->get(),
        ]);
    }
}
