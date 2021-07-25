<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App;

class LocaleController extends Controller
{
    public function lang($locale)
    {

    	// dd($locale);
    	if($locale==""){
    		$locale = 'en';
    	}
        App::setLocale($locale);
        session()->put('locale', $locale);
        // dd(Session::get('locale'));
        return redirect()->back();
    }
}