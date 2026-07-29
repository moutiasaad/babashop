<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Reads the desired locale from the request and sets it on the app.
 * Priority: ?lang= query param → Accept-Language header → default ('fr').
 * Only 'fr' and 'ar' are honored; anything else falls back to 'fr'.
 *
 * Model accessors (e.g. Product::localized_name) consult app()->getLocale()
 * to pick between the base column and its *_ar sibling.
 */
class SetLocaleFromRequest
{
    private const SUPPORTED = ['fr', 'ar'];
    private const DEFAULT   = 'fr';

    public function handle(Request $request, Closure $next)
    {
        $requested = $request->query('lang')
            ?: substr((string) $request->header('Accept-Language', self::DEFAULT), 0, 2);

        $locale = in_array($requested, self::SUPPORTED, true) ? $requested : self::DEFAULT;
        app()->setLocale($locale);

        return $next($request);
    }
}
