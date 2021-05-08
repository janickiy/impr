<?php

namespace App\Helpers;

class SettingsHelper
{
    public static function getKey($key = '')
    {
        $setting = \App\Models\Settings::where('name', $key)->remember(60 * 60)->first();

        if ($setting) {
            return $setting->value;
        } else {
            return '';
        }
    }
}

