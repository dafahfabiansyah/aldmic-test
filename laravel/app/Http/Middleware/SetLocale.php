<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check for locale in request (query param, header, or session)
        $locale = $request->input('lang', 
                    $request->header('Accept-Language', 
                        session('locale', config('app.locale'))));
        
        // Sanitize locale (only accept 'en' or 'id')
        if (in_array($locale, ['en', 'id'])) {
            App::setLocale($locale);
            session(['locale' => $locale]);
        }

        return $next($request);
    }
}
