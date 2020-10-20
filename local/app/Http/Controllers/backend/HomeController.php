<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    public function index(Request $request)
    {

      // return view('backend.index');
      // return view('backend.banner.index');
      return view('backend.index');

    }
}
