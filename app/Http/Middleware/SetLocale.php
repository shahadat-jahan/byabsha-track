<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const SUPPORTED_LOCALES = ['en', 'bn'];

    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('app.available_locales', self::SUPPORTED_LOCALES);
        $locale    = Session::get('locale', config('app.locale', 'en'));

        if (! in_array($locale, $supported)) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
