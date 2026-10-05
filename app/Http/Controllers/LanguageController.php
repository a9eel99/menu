<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request, $locale)
    {
        // Validate locale
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }
        
        // Store in session
        Session::put('locale', $locale);
        Session::save(); // Force save
        
        // Set app locale immediately
        App::setLocale($locale);
        
        // Get the previous URL
        $previousUrl = url()->previous();
        
        if (!$previousUrl || $previousUrl == url()->current()) {
            $previousUrl = route('admin.dashboard');
        }
        
        return redirect($previousUrl);
    }
}