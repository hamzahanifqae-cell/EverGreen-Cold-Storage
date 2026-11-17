<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Language switch for the marketing pages (landing + how it works).
 *
 * `?lang=en|ur` stores the choice in a cookie for a year and redirects to the
 * same URL without the parameter. Later visits read the cookie.
 */
class SetMarketingLocale
{
    public const SUPPORTED = ['en', 'ur'];

    public const COOKIE = 'site_lang';

    public function handle(Request $request, Closure $next): Response
    {
        $requested = $request->query('lang');

        if (is_string($requested) && in_array($requested, self::SUPPORTED, true)) {
            $query = $request->except('lang');
            $url = $request->url().($query ? '?'.http_build_query($query) : '');

            return redirect($url)->withCookie(Cookie::make(self::COOKIE, $requested, 60 * 24 * 365));
        }

        $locale = $request->cookie(self::COOKIE);
        App::setLocale(in_array($locale, self::SUPPORTED, true) ? $locale : 'en');

        return $next($request);
    }
}
