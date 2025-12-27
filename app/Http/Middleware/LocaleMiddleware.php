<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['en', 'bn', 'hi'];
        $locale = $request->route('locale');
        
        if ($locale && in_array($locale, $supportedLocales)) {
            session(['locale' => $locale]);
        }
        app()->setLocale(session('locale', 'en'));

        return $next($request);
    }
}