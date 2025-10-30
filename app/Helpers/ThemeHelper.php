<?php

namespace App\Helpers;

class ThemeHelper
{
    /**
     * Get theme colors based on theme mode
     * 
     * @param bool $isLightTheme
     * @return array
     */
    public static function getThemeColors($isLightTheme = false)
    {
        if ($isLightTheme) {
            return self::getLightTheme();
        }
        
        return self::getDarkTheme();
    }

    /**
     * Get dark theme colors
     * 
     * @return array
     */
    protected static function getDarkTheme()
    {
        return [
            // Background colors (3 levels)
            '--bg-primary' => '#121212',      // Main background
            '--bg-secondary' => '#1e1e1e',    // Sidebar, cards
            '--bg-tertiary' => '#2c2c2c',     // Hover states, active items
            
            // Text colors (3 levels)
            '--text-primary' => '#fff',       // Main text
            '--text-secondary' => '#ccc',     // Secondary text
            '--text-muted' => '#888',         // Muted/disabled text
            
            // Component colors
            '--accent-color' => '#6a1b9a',    // Primary accent (purple)
            '--border-color' => '#333',       // Borders, dividers
            
            // Special backgrounds
            '--hover-bg' => '#333',           // Hover background
            '--card-bg' => '#1e1e1e',         // Card background
            '--input-bg' => '#1e1e1e',        // Input background
        ];
    }

    /**
     * Get light theme colors
     * 
     * @return array
     */
    protected static function getLightTheme()
    {
        return [
            // Background colors (3 levels)
            '--bg-primary' => '#ffffff',       // Main background
            '--bg-secondary' => '#f8f9fa',     // Sidebar, cards
            '--bg-tertiary' => '#e9ecef',      // Hover states, active items
            
            // Text colors (3 levels)
            '--text-primary' => '#212529',     // Main text
            '--text-secondary' => '#495057',   // Secondary text
            '--text-muted' => '#6c757d',       // Muted/disabled text
            
            // Component colors
            '--accent-color' => '#6a1b9a',     // Primary accent (purple)
            '--border-color' => '#dee2e6',     // Borders, dividers
            
            // Special backgrounds
            '--hover-bg' => '#e9ecef',         // Hover background
            '--card-bg' => '#ffffff',          // Card background
            '--input-bg' => '#ffffff',         // Input background
        ];
    }

    /**
     * Get auth theme colors (for auth pages)
     * 
     * @param bool $isLightTheme
     * @return array
     */
    public static function getAuthThemeColors($isLightTheme = false)
    {
        if ($isLightTheme) {
            return [
                '--auth-bg' => '#ffffff',
                '--auth-text' => '#212529',
                '--auth-text-muted' => '#6c757d',
                '--auth-input-bg' => '#f8f9fa',
                '--auth-border' => '#dee2e6',
                '--auth-accent' => '#7f00ff',
                '--auth-secondary' => '#000',
            ];
        }
        
        return [
            '--auth-bg' => '#000',
            '--auth-text' => '#fff',
            '--auth-text-muted' => '#888',
            '--auth-input-bg' => '#1e1e1e',
            '--auth-border' => '#333',
            '--auth-accent' => '#7f00ff',
            '--auth-secondary' => '#fff',
        ];
    }

    /**
     * Check if user has light theme enabled
     * 
     * @return bool
     */
    public static function isLightTheme()
    {
        if (!isset($_COOKIE['user_settings'])) {
            return false; // Default to dark theme
        }
        
        try {
            $settings = json_decode($_COOKIE['user_settings'], true);
            return isset($settings['dark_mode']) && $settings['dark_mode'] === false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get theme colors formatted for CSS
     * 
     * @param bool $isLightTheme
     * @return string CSS variable declarations
     */
    public static function getCssVariables($isLightTheme = false)
    {
        $colors = self::getThemeColors($isLightTheme);
        $css = '';
        
        foreach ($colors as $key => $value) {
            $css .= sprintf('%s: %s;%s', $key, $value, "\n            ");
        }
        
        return trim($css);
    }
}

