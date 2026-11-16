<?php

namespace App\Http\Middleware;

use App\Support\DomainConfig;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictLandingDomain
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! DomainConfig::splitEnabled()) {
            return $next($request);
        }

        if (! DomainConfig::isLandingHost($request->getHost())) {
            return $next($request);
        }

        if ($this->isAllowedOnLanding($request)) {
            return $next($request);
        }

        $target = DomainConfig::appUrl().$request->getRequestUri();

        return redirect()->away($target);
    }

    private function isAllowedOnLanding(Request $request): bool
    {
        // Path checks (not route names): this middleware is global and may run before routing.
        if ($request->is('/') || $request->is('how-it-works')) {
            return true;
        }

        if ($request->is('up')) {
            return true;
        }

        // Public marketing / built assets must resolve on the apex host.
        return $request->is([
            'build/*',
            'images/*',
            'fonts/*',
            'favicon.ico',
            'favicon.svg',
            'robots.txt',
        ]);
    }
}
