<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\websiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class WebsiteSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_website_settings')->only(['index']);
        $this->middleware('permission:edit_website-info-update')->only(['websiteInfoUpdate']);
        $this->middleware('permission:edit_personal-info-update')->only(['personalInfoUpdate']);
    }
    /**
     * Display a listing of the website settings.
     */
    public function index()
    {
        // convert setting to array and pass including images  to view

        $settingsArr = WebsiteSetting::toarrayWithMedia();
        return view('dashboard.settings.edit', compact('settingsArr'));
    }
    /**
     * Update the website information.
     */
    public function websiteInfoUpdate(Request $request)
    {
        $request->validate([
            'website_name' => 'nullable|string|max:255',
            'website_slogan' => 'nullable|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'footer_text' => 'nullable|string|max:255',
            'website_description' => 'nullable|string',
            'website_keywords' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,webp|max:1024',
            'login_banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'partnership_hero_title' => 'nullable|string|max:255',
            'partnership_hero_subtitle' => 'nullable|string|max:255',
            'partnership_hero_video' => 'nullable|mimes:mp4,mov,avi,mkv|max:51200',
            'welcome_video' => 'nullable|mimes:mp4,mov,avi,mkv|max:51200',
        ]);

        $fields = [
            'website_name',
            'website_slogan',
            'website_url',
            'footer_text',
            'website_description',
            'website_keywords',
            'partnership_hero_title',
            'partnership_hero_subtitle',
        ];

        foreach ($fields as $field) {
            try {
            WebsiteSetting::setSetting($field, $request->input($field));
            } catch (\Exception $e) {
                // Log the error or handle it as needed
                Log::error("Failed to update setting {$field}: " . $e->getMessage());
            }
        }
        $settings = WebsiteSetting::firstOrCreate([], []);
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $settings->clearMediaCollection('logo');
            $settings->addMedia($request->file('logo'))
                ->toMediaCollection('logos');
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $settings->clearMediaCollection('favicon');
            $settings->addMedia($request->file('favicon'))
                ->toMediaCollection('favicons');
        }

        // Handle login banner upload
        if ($request->hasFile('login_banner')) {
            $settings->clearMediaCollection('login_banners');
            $settings->addMedia($request->file('login_banner'))
                ->toMediaCollection('login_banners');
        }

        // Handle partnership hero video upload (AYS screen)
        if ($request->hasFile('partnership_hero_video')) {
            $settings->clearMediaCollection('partnership_hero_videos');
            $settings->addMedia($request->file('partnership_hero_video'))
                ->toMediaCollection('partnership_hero_videos');
        }

        // Handle welcome screen background video upload
        if ($request->hasFile('welcome_video')) {
            $settings->clearMediaCollection('welcome_videos');
            $settings->addMedia($request->file('welcome_video'))
                ->toMediaCollection('welcome_videos');
        }

        return redirect()->back()->with('status', 'success')->with('message', 'Updated Data Successfully.');;
    }

    /**
     * update personal information.
     */
    public function personalInfoUpdate(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'phone_number' => ['nullable', 'regex:/^[0-9+ ]+$/'], // only numbers, +, and spaces
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'youtube' => 'nullable|url',
            'twitter' => 'nullable|url',
            'pinterest' => 'nullable|url',
            'tiktok' => 'nullable|url',
        ], [
            'phone_number.regex' => 'Phone number can only contain numbers, spaces, and + sign.',
        ]);

        $fields = [
            'email',
            'phone_number',
            'facebook',
            'instagram',
            'linkedin',
            'youtube',
            'twitter',
            'pinterest',
            'tiktok',
        ];

        foreach ($fields as $field) {
            $value = $request->input($field);
            WebsiteSetting::setSetting($field, $value); // will use logged in user if user_id not passed
        }

        return redirect()->back()->with('status', 'success')->with('message', 'Updated Data Successfully.');
    }
}
