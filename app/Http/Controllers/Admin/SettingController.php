<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::firstOrCreate(['id' => 1]);
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::firstOrCreate(['id' => 1]);

        $data = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'brand_tagline' => 'nullable|string|max:255',
            'logo_path' => 'nullable|string|max:255',
            'favicon_path' => 'nullable|string|max:255',
            'admin_email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linked_in' => 'nullable|string|max:255',
            'x' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'pinterest' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'footer_text' => 'nullable|string',
        ]);

        $setting->update($data);

        return back()->with('success', 'Settings updated.');
    }
}
