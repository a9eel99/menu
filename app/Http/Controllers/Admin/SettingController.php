<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * عرض صفحة الإعدادات
     */
    public function index()
    {
        $general = Setting::getGroup('general');
        $appearance = Setting::getGroup('appearance');
        
        return view('admin.settings.index', compact('general', 'appearance'));
    }

    /**
     * حفظ الإعدادات العامة
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_name_ar' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_description_ar' => 'nullable|string|max:500',
            'default_language' => 'required|in:ar,en',
        ]);

        Setting::set('site_name', $request->site_name);
        Setting::set('site_name_ar', $request->site_name_ar);
        Setting::set('site_description', $request->site_description);
        Setting::set('site_description_ar', $request->site_description_ar);
        Setting::set('default_language', $request->default_language);
        Setting::set('allow_registration', $request->boolean('allow_registration') ? '1' : '0');

        return back()->with('success', 'تم حفظ الإعدادات العامة بنجاح');
    }

    /**
     * حفظ إعدادات المظهر
     */
    public function updateAppearance(Request $request)
    {
        $request->validate([
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'site_favicon' => 'nullable|image|mimes:png,ico,jpg,jpeg|max:512',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
        ]);

        // رفع الشعار
        if ($request->hasFile('site_logo')) {
            // حذف القديم
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            $path = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $path);
        }

        // حذف الشعار
        if ($request->boolean('remove_logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::set('site_logo', null);
        }

        // رفع الأيقونة
        if ($request->hasFile('site_favicon')) {
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }
            
            $path = $request->file('site_favicon')->store('settings', 'public');
            Setting::set('site_favicon', $path);
        }

        // حذف الأيقونة
        if ($request->boolean('remove_favicon')) {
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }
            Setting::set('site_favicon', null);
        }

        Setting::set('primary_color', $request->primary_color);
        Setting::set('secondary_color', $request->secondary_color);
        
        // مسح الكاش
        Setting::clearCache();

        return back()->with('success', 'تم حفظ إعدادات المظهر بنجاح');
    }
}