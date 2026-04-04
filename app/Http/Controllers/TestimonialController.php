<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function store(Request $request)
    {
        // ✅ validation
        $request->validate([
            'name' => 'required|max:50',
            'message' => 'required|max:500'
        ]);

        // ✅ save
        Testimonial::create([
            'name' => $request->name,
            'message' => $request->message
        ]);

        return back()->with('success', 'Review submitted!');
    }
}