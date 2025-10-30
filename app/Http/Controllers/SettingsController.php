<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class SettingsController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'show_completed_tasks' => 'boolean',
            'notifications' => 'boolean',
            'dark_mode' => 'boolean',
        ]);

        $settings = [
            'show_completed_tasks' => $request->boolean('show_completed_tasks'),
            'notifications' => $request->boolean('notifications'),
            'dark_mode' => $request->boolean('dark_mode'),
        ];

        // Store settings in cookies (expires in 1 year)
        // httpOnly: false allows JavaScript to read the cookie
        $cookie = Cookie::make('user_settings', json_encode($settings), 525600, '/', null, false, false); // 1 year in minutes

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully'
        ])->cookie($cookie);
    }

    public function get(Request $request)
    {
        // Get settings from cookies
        $settingsJson = $request->cookie('user_settings');
        
        if ($settingsJson) {
            $settings = json_decode($settingsJson, true);
        } else {
            // Default settings
            $settings = [
                'show_completed_tasks' => false,
                'notifications' => false,
                'dark_mode' => true, // Default to dark mode
            ];
        }

        return response()->json($settings);
    }
}
