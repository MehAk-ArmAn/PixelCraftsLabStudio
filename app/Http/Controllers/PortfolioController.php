<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Portfolio; // make sure this is your model

class PortfolioController extends Controller
{
    public function index()
    {
        // Fetch all portfolio items
        $projects = Portfolio::all(); // get from DB

        // Send to the view
        return view('pages.portfolio', compact('projects'));
    }
}