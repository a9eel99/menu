<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    public function switch(Request $request, $locale)
    {
        // Debug: Log the request
        Log::info('=== Language Switch Debug ===');
        Log::info('Requested locale: ' . $locale);
        Log::info('Session ID: ' . Session::getId());
        Log::info('Session locale BEFORE: ' . Session::get('locale', 'NOT SET'));
        
        // Validate locale
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }
        
        // Store in session
        Session::put('locale', $locale);
        Session::save(); // Force save
        
        // Set app locale immediately
        App::setLocale($locale);
        
        // Debug: Log after setting
        Log::info('Session locale AFTER: ' . Session::get('locale', 'NOT SET'));
        Log::info('App locale: ' . App::getLocale());
        Log::info('=== End Debug ===');
        
        // Get the previous URL
        $previousUrl = url()->previous();
        
        if (!$previousUrl || $previousUrl == url()->current()) {
            $previousUrl = route('admin.dashboard');
        }
        
        return redirect($previousUrl);
    }
}