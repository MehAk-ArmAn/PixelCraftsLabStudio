<?php

namespace App\Http\Controllers;
use App\Models\Particle;
use App\Models\Service;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {

        $services = Service::all(); // get services

        $images = Particle::where('active', true)->get(); // Get full model, not just pluck

            // TODO: Return the home page
        return view('pages.home', compact('services', 'images'));

    }

    public function about()
    {
        // TODO: Return full about page
        return view('pages.about');
    }

    public function contact()
    {
        // TODO: Return contact page with form
        return view('pages.contact');
    }
}
