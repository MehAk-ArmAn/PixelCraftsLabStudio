<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'service' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:3'],
        ], [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'message.required' => 'Please write a message before submitting.',
            'message.min' => 'Your message is too short.',
        ]);

        Contact::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'service' => $validated['service'],
            'message' => trim($validated['message']),
            'is_read' => false,
        ]);

        return back()->with('success', 'Message sent successfully!');
    }
}
