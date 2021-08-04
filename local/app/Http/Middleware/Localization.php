<?php

namespace App\Http\Middleware;

use Closure;

class Localization
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

        // dd(session()->get('locale'));

        if(session()->has('locale') && in_array(session()->get('locale'),['th','en','lo']))
        {
            app()->setLocale(session()->get('locale'));
        }
        else
        {
            session()->put('locale','th');
        }
        return $next($request);
    }


}
