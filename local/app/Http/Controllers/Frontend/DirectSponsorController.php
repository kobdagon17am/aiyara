<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Helpers\Frontend; 
use App\Models\Frontend\DirectSponsor;

class DirectSponsorController extends Controller
{

  public function __construct()
  {
    $this->middleware('customer'); 
  }

  public function index(){

    $user_id = Auth::guard('c_user')->user()->id;
    $customers =  DB::table('customers')
    ->select('customers.*','dataset_package.dt_package','dataset_qualification.code_name','q_max.code_name as max_code_name') 
    ->leftjoin('dataset_package','dataset_package.id','=','customers.package_id') 
    ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=','customers.qualification_id')
    ->leftjoin('dataset_qualification as q_max', 'q_max.id', '=','customers.qualification_max_id')
    ->where('customers.introduce_id','=',$user_id )
    ->orwhere('customers.id','=',$user_id ) 
    ->orderbyraw('(customers.id = '.$user_id.') DESC') 
    ->get(); 


    $customers_sponser =  DB::table('customers')
    ->select('customers.*','dataset_package.dt_package','dataset_qualification.code_name','q_max.code_name as max_code_name',DB::raw(' DATE_ADD(customers.created_at,INTERVAL + 60 DAY) as end_date')) 
    ->leftjoin('dataset_package','dataset_package.id','=','customers.package_id') 
    ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=','customers.qualification_id')
    ->leftjoin('dataset_qualification as q_max', 'q_max.id', '=','customers.qualification_max_id')
    ->where('customers.introduce_id','=',$user_id )
    ->whereRaw('DATE_ADD(customers.created_at, INTERVAL +60 DAY) >= NOW()')
    ->get(); 
    return view('frontend/direct_sponsor',compact('customers','customers_sponser'));
  }


}


