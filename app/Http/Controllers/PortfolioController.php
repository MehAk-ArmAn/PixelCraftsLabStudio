<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        // TODO: Fetch and show portfolio items
        $items = Portfolio::all();
        return view('pages.portfolio', compact('items'));
    }
}
