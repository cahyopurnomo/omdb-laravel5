<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

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
        // Ambil dari query ?lang=xx kalau ada, kalau tidak pakai yang di session
        $locale = $request->get('lang') ?: Session::get('locale', config('app.locale'));

        // Batasi ke opsi yang kita dukung
        if (in_array($locale, ['en', 'id'])) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        } else {
            App::setLocale(Session::get('locale', config('app.locale')));
        }

        return $next($request);
    }
}
