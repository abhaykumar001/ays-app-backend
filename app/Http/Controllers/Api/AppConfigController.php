<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\websiteSetting;

class AppConfigController extends Controller
{
    public function index()
    {
        $settingsArr = websiteSetting::toArrayWithMedia();

        return response()->json([
            'data' => [
                'login_banner_url' => $settingsArr['login_banner'] ?? null,
                'partnership_hero_video_url' => $settingsArr['partnership_hero_video'] ?? null,
                'partnership_hero_title' => $settingsArr['partnership_hero_title'] ?? null,
                'partnership_hero_subtitle' => $settingsArr['partnership_hero_subtitle'] ?? null,
                'social_facebook'  => $settingsArr['facebook'] ?? null,
                'social_instagram' => $settingsArr['instagram'] ?? null,
                'social_linkedin'  => $settingsArr['linkedin'] ?? null,
                'social_youtube'   => $settingsArr['youtube'] ?? null,
                'social_twitter'   => $settingsArr['twitter'] ?? null,
                'social_pinterest' => $settingsArr['pinterest'] ?? null,
                'social_tiktok'    => $settingsArr['tiktok'] ?? null,
            ],
        ]);
    }
}
