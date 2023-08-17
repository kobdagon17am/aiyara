<?php

namespace App\Http\Middleware;

use Closure;

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
        \View::share('sLocale', $request->segment(1));
        if($request->segment(1)=='en'){
          \Config::set('land', 'en');
          \Config::set('locale', 'en');
          return $next($request);
        }

        if($request->segment(1)=='th'){
          \Config::set('land', 'th');
          \Config::set('locale', 'th');
          return $next($request);
        }
        if($request->segment(1)=='th-en'){
          \Config::set('land', 'en');
          \Config::set('locale', 'th');
          return $next($request);
        }
        return redirect('en');
    }
}



