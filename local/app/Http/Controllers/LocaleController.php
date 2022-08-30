<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App;

class LocaleController extends Controller
{
    public function lang($locale)
    {

    	//
    	if($locale==""){
    		$locale = 'th';
    	}

        App::setLocale($locale);
        session()->put('locale', $locale);
        // dd(Session::get('locale'));
        return redirect()->back();
    }
}
