<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * عرض صفحة Landing للمطعم
     */
    public function landing($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'socialLinks' => function ($query) {
                    $query->where('is_active', true)->orderBy('sort_order');
                },
                'parent'
            ])
            ->firstOrFail();

        // جلب اللغة الخاصة بهذا المطعم
        $localeKey = 'locale_' . $restaurant->id;
        $locale = session($localeKey, 'ar');
        session(['current_menu_locale' => $locale]);
        app()->setLocale($locale);

        return view('menu.landing', compact('restaurant', 'locale'));
    }

    /**
     * عرض منيو المطعم (توجيه تلقائي حسب النوع)
     */
    public function show($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // إذا كان نوع المنيو PDF وموجود ملف
        if ($restaurant->menu_type === 'pdf' && $restaurant->menu_pdf) {
            return redirect()->route('menu.pdf', $slug);
        }

        // وإلا توجيه للمنيو الإلكتروني
        return redirect()->route('menu.digital', $slug);
    }

    /**
     * عرض منيو PDF
     */
    public function showPdf($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->with(['parent'])
            ->firstOrFail();

        // تأكد من أن النوع PDF وموجود ملف
        if ($restaurant->menu_type !== 'pdf' || !$restaurant->menu_pdf) {
            return redirect()->route('menu.digital', $slug);
        }

        return view('menu.pdf', compact('restaurant'));
    }

    /**
     * عرض المنيو الإلكتروني
     */
    public function showDigital($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'socialLinks' => function ($query) {
                    $query->where('is_active', true);
                },
                'parent'
            ])
            ->firstOrFail();

        // جلب اللغة الخاصة بهذا المطعم
        $localeKey = 'locale_' . $restaurant->id;
        $locale = session($localeKey, 'ar');
        session(['current_menu_locale' => $locale]);
        app()->setLocale($locale);

        // Get categories with items and tags
        $categories = $restaurant->categories()
            ->where('is_active', true)
            ->with(['menuItems' => function ($query) {
                $query->where('is_available', true)
                    ->with('tags')
                    ->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        // Get featured items with tags
        $featuredItems = $restaurant->menuItems()
            ->where('is_featured', true)
            ->where('is_available', true)
            ->with('tags')
            ->take(6)
            ->get();

        return view('menu.show', compact('restaurant', 'categories', 'featuredItems', 'locale'));
    }

    /**
     * تبديل اللغة - خاص بكل مطعم
     */
    public function switchLanguage(Request $request, $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->first();
        
        if ($restaurant) {
            $locale = $request->input('locale', 'ar');
            if (in_array($locale, ['ar', 'en'])) {
                // حفظ اللغة الخاصة بهذا المطعم
                $localeKey = 'locale_' . $restaurant->id;
                session([$localeKey => $locale]);
                session(['current_menu_locale' => $locale]);
                app()->setLocale($locale);
            }
        }
        
        return redirect()->back();
    }
}