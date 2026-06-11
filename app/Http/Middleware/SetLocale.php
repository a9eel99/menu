<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Debug
        Log::info('--- SetLocale Middleware ---');
        Log::info('Session ID: ' . Session::getId());
        Log::info('Session locale: ' . Session::get('locale', 'NOT SET'));
        
        // Get locale from session
        $locale = Session::get('locale', config('app.locale', 'ar'));
        
        // Validate locale
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }
        
        // Set application locale
        App::setLocale($locale);
        
        Log::info('Final locale set to: ' . App::getLocale());
        Log::info('--- End SetLocale ---');
        
        return $next($request);
    }
}