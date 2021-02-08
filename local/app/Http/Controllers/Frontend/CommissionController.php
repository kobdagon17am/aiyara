<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Helpers\Frontend; 
use App\Models\Frontend\DirectSponsor;


class CommissionController extends Controller
{

  public function __construct()
  {
    $this->middleware('customer'); 
}

public function commission_per_day(){
  
    return view('frontend/commission_per_day');
}

public function commission_faststart(){ 

    return view('frontend/commission_faststart');

}

public function commission_bonus_report(){ 

    return view('frontend/commission_bonus_report');

}

public function commission_matching(){ 

    return view('frontend/commission_matching'); 

}





}


