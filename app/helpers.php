<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * الحصول على قيمة إعداد
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('settings')) {
    /**
     * الحصول على جميع إعدادات مجموعة
     */
    function settings(string $group): array
    {
        return Setting::getGroup($group);
    }
}