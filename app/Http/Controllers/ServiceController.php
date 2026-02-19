<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        // TODO: Fetch and show services on public site
        $services = Service::all();
        return view('pages.services', compact('services'));
    }
}
