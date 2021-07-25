<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TemplateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
      $path = str_replace('backend/template/','',$request->path());
      if($path == 'backend/template'){
        $path = 'index';
      }
      if(view()->exists('backend.template.'.$path)){
          return view('backend.template.'.$path);
      }
      return view('backend.template.pages-404');
    }
}
