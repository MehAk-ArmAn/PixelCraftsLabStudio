<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Setting;
use App\Models\Portfolio;
use App\Models\Testimonial;
use App\Models\Section;
use App\Models\Page;
use App\Models\TeamMember;

class PageController extends Controller
{
    public function home()
    {
        $setting = Setting::first();
        $page = Page::where('page_key', 'home')->first();

        $services = Service::all(); // services for home sections / forms
        $projects = Portfolio::latest()->get(); // portfolio preview if needed
        $testimonials = \App\Models\Testimonial::where('is_active', true)
        ->orderBy('sort_order')
        ->get();
        $heroSection = Section::where('page_key', 'home')->where('section_key', 'hero')->first();
        $aboutPreview = Section::where('page_key', 'home')->where('section_key', 'about_preview')->first();
        $ctaSection = Section::where('page_key', 'home')->where('section_key', 'cta')->first();

        return view('pages.home', compact(
            'setting',
            'page',
            'services',
            'projects',
            'testimonials',
            'heroSection',
            'aboutPreview',
            'ctaSection'
        ));
    }

    public function about()
    {
        $setting = Setting::first();

        // 🔥 THIS is what you forgot
        $team = TeamMember::where('is_active', 1)
            ->orderBy('sort_order')
            ->get();

        return view('pages.about', compact('setting', 'team'));
    }

    public function contact()
    {
        $setting = Setting::first();
        $page = Page::where('page_key', 'contact')->first();

        $services = Service::orderBy('title')->get();

        return view('pages.contact', compact('services', 'setting', 'page'));
    }
}
