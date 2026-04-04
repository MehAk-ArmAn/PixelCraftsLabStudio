<?php

namespace App\Http\Controllers;

use App\Models\Particle;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $services = Service::all();
        $images = Particle::where('active', true)->get();
        $testimonials = Testimonial::latest()->get();

        return view('pages.home', compact('services', 'images', 'testimonials'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}