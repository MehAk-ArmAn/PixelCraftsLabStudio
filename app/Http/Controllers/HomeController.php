<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Particle;
use App\Models\Portfolio;
use App\Models\Section;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'setting' => Setting::first(),
            'page' => Page::where('page_key', 'home')->first(),
            'heroSection' => Section::where('page_key', 'home')->where('section_key', 'hero')->first(),
            'aboutPreview' => Section::where('page_key', 'home')->where('section_key', 'about_preview')->first(),
            'ctaSection' => Section::where('page_key', 'home')->where('section_key', 'cta')->first(),
            'services' => Service::latest()->get(),
            'projects' => Portfolio::latest()->get(),
            'testimonials' => Testimonial::where('is_active', true)->orderBy('sort_order')->get(),
            'images' => Particle::where('active', true)->pluck('image_path')->values(),
        ]);
    }
}
