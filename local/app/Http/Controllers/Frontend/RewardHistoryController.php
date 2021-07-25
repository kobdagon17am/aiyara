<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class RewardHistoryController extends Controller
{

  public function __construct()
  {
    $this->middleware('customer');
  }

  public function index(){
  
    $data = DB::table('dataset_qualification')
            ->select('*')
            ->orderby('id','DESC')
            ->get();

    return view('frontend/reward-history',compact('data'));
  }

   
}


