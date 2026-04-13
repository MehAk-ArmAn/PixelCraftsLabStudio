<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Contact::latest()->paginate(15); // list
        return view('admin.messages.index', compact('messages'));
    }

    public function show(Contact $message)
    {
        if (!$message->is_read) {
            $message->update(['is_read' => true]); // mark read
        }

        return view('admin.messages.show', compact('message'));
    }

    public function destroy(Contact $message)
    {
        $message->delete(); // delete
        return back()->with('success', 'Deleted.');
    }
}