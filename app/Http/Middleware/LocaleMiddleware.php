<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1);
        // dd($locale);
        $supportedLocales = ['en', 'bn', 'hi'];
        $defaultLocale = 'en';
        
        if ($locale && in_array($locale, $supportedLocales)) {
            app()->setLocale($locale);  
            Session::put('locale', $locale);
            // session(['locale' => $locale]);
        } else {
            // dd($defaultLocale);
            // Session::put('locale', $defaultLocale);
            app()->setLocale($defaultLocale);
        }

        return $next($request);
    }
}