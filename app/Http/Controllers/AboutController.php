<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Section;
use App\Models\Setting;
use App\Models\TeamMember;

class AboutController extends Controller
{
    public function index()
    {
        return view('about', [
            'setting' => Setting::first(),
            'page' => Page::where('page_key', 'about')->first(),
            'story' => Section::where('page_key', 'about')->where('section_key', 'story')->first(),
            'mission' => Section::where('page_key', 'about')->where('section_key', 'mission')->first(),
            'vision' => Section::where('page_key', 'about')->where('section_key', 'vision')->first(),
            'teamMembers' => TeamMember::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }
}
