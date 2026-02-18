<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Validate form input
        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'contact_number' => 'required',
            'email' => 'required|email',
            'description' => 'required'
        ]);

        // Store data in DB
        Contact::create($validated);

        return back()->with('success', 'Message sent successfully!');
    }
}

