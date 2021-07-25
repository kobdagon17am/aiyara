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

        if($request->segment(1)=='cn'){
          \Config::set('land', 'cn');
          \Config::set('locale', 'cn');
          return $next($request);
        }
        if($request->segment(1)=='cn-en'){
          \Config::set('land', 'en');
          \Config::set('locale', 'cn');
          return $next($request);
        }

        if($request->segment(1)=='jp'){
          \Config::set('land', 'jp');
          \Config::set('locale', 'jp');
          return $next($request);
        }
        if($request->segment(1)=='jp-en'){
          \Config::set('land', 'en');
          \Config::set('locale', 'jp');
          return $next($request);
        }

        if($request->segment(1)=='kr'){
          \Config::set('land', 'kr');
          \Config::set('locale', 'kr');
          return $next($request);
        }
        if($request->segment(1)=='kr-en'){
          \Config::set('land', 'en');
          \Config::set('locale', 'kr');
          return $next($request);
        }

        if($request->segment(1)=='fr'){
          \Config::set('land', 'fr');
          \Config::set('locale', 'fr');
          return $next($request);
        }
        if($request->segment(1)=='fr-en'){
          \Config::set('land', 'en');
          \Config::set('locale', 'fr');
          return $next($request);
        }
        return redirect('en');
        //return $next($request);
    }
}



