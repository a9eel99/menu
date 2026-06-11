<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryLogger
{
    public function handle(Request $request, Closure $next)
    {
        // تفعيل تسجيل الـ Queries
        DB::enableQueryLog();
        
        $response = $next($request);
        
        // تسجيل الـ Queries
        $queries = DB::getQueryLog();
        $totalTime = 0;
        
        foreach ($queries as $query) {
            $totalTime += $query['time'];
        }
        
        // تسجيل في الـ Log
        if (count($queries) > 0) {
            Log::channel('daily')->info('Query Log', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'query_count' => count($queries),
                'total_time_ms' => round($totalTime, 2),
                'queries' => config('app.debug') ? $queries : 'Debug mode disabled'
            ]);
        }
        
        return $response;
    }
}