<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;
use App\Models\Frontend\Product;
use App\Models\Frontend\LineModel;
use App\Models\Frontend\Runpv;
use Auth;

class AiCashController extends Controller
{

  public function __construct()
  {
    $this->middleware('customer');
  }

  public function index(){
    $type = DB::table('dataset_orders_type')
    ->where('status','=',1)
    ->where('lang_id','=',1)
    ->whereRaw('(group_id = 1 || group_id = 2 || group_id = 3)')
    ->orderby('order')
    ->get();

    $ai_cash = DB::table('ai_cash')
    ->select('ai_cash.*','dataset_pay_type.detail as pay_type','dataset_order_status.detail as order_status')
    ->leftjoin('dataset_pay_type','dataset_pay_type.id','=','ai_cash.pay_type_id_fk')
    ->leftjoin('dataset_order_status','dataset_order_status.orderstatus_id','=','ai_cash.order_status_id_fk')
    ->orderby('ai_cash.created_at','desc')
    ->get();

    return view('frontend/aicash',compact('type','ai_cash'));
  }

  public function cart_payment_aicash(Request $request){
    if($request->price == ''){
      return redirect('ai-cash');
    }else{
      $data = ['type'=>7,'price'=>$request->price];
      return view('frontend/product/cart_payment_aicash',compact('data'));
    }
  }
}


