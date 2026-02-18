<?php

namespace App\Http\Controllers;

use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch all services from database
        $services = Service::all();

        // Send services to home view
        return view('home', compact('services'));
    }
}

