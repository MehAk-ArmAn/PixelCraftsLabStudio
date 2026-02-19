<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        // TODO: Return the home page
        return view('pages.home');
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
