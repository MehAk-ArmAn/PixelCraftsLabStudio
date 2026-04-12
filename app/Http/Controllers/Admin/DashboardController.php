<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\TeamMember;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index', [
            'contactCount' => Contact::count(),
            'unreadCount' => Contact::where('is_read', false)->count(),
            'serviceCount' => Service::count(),
            'portfolioCount' => Portfolio::count(),
            'testimonialCount' => Testimonial::count(),
            'teamCount' => TeamMember::count(),
            'latestContacts' => Contact::latest()->take(5)->get(),
        ]);
    }
}
