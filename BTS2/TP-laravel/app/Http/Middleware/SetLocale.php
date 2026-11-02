<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use LogicException;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /** @param Closure(Request): Response $next */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = config('app.supported_locales');
        $fallbackLocale = config('app.fallback_locale');

        if (! is_array($supportedLocales) || ! is_string($fallbackLocale)) {
            throw new LogicException('Locale configuration must contain supported locales and a fallback locale.');
        }

        $locale = $request->session()->get('locale', config('app.locale'));

        if (! is_string($locale) || ! array_key_exists($locale, $supportedLocales)) {
            $locale = $fallbackLocale;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
