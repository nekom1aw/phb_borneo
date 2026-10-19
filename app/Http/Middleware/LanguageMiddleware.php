<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Bahasa diambil dari segmen pertama URL: /en/... atau /id/...
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1, 'en');
        if (! in_array($locale, ['en', 'id'])) $locale = config('app.locale');

        App::setLocale($locale);

        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
