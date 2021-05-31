<?php

namespace App\Http\Controllers\Frontend\Ksher;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class KsherNotifyController extends Controller
{
    public static function index()
    {
      return view('frontend/ksher');
    }

}
