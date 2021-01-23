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

    return view('frontend/aicash',compact('type'));
  }

  public function cart_payment_aicash(Request $request){
    if($request->price == ''){
      return redirect('ai-cash');
    }else{
      $data = ['type'=>7,'price'=>$request->price];
      return view('frontend/product/cart_payment_aicash',compact('data'));
    }
  }


  public function dt_aicash(Request $request){ 

    $columns = array(
      0 => 'order',
      1 => 'create_at',
      2 => 'type',
      3 => 'order_type',
      4 => 'price',
      5 => 'banlance',
      6 => 'detail',
    );



    if(empty($request->input('search.value'))){

      $totalData =  DB::table('ai_cash')
      
      //->leftjoin('dataset_orders_type','ai_pocket.type_id','=','dataset_orders_type.group_id')
      //->where('dataset_orders_type.lang_id','=','1')

      ->where('customer_id','=',Auth::guard('c_user')->user()->id)
      ->count();


      $totalFiltered = $totalData;
      $limit = $request->input('length');
      $start = $request->input('start');
      //$order = $columns[$request->input('order.0.column')];
      ////$dir = $request->input('order.0.dir');

      $ai_cash =  DB::table('ai_cash')
      ->select('ai_cash.*') 
      ->where('customer_id','=',Auth::guard('c_user')->user()->id)
      ->offset($start)
      ->limit($limit)
      ->orderby('update_at','DESC')
      ->get(); 

    }else{

      $search = trim($request->input('search.value'));

      $totalData =  DB::table('ai_cash')
      
      //->leftjoin('dataset_orders_type','ai_pocket.type_id','=','dataset_orders_type.group_id')
      //->where('dataset_orders_type.lang_id','=','1')

      ->where('customer_id','=',Auth::guard('c_user')->user()->id)
      ->count();


      $totalFiltered = $totalData;
      $limit = $request->input('length');
      $start = $request->input('start');
      //$order = $columns[$request->input('order.0.column')];
      ////$dir = $request->input('order.0.dir');

      $ai_cash =  DB::table('ai_cash')
      ->select('ai_cash.*') 
      ->where('customer_id','=',Auth::guard('c_user')->user()->id)
      ->offset($start)
      ->limit($limit)
      ->orderby('update_at','DESC')
      ->get(); 

        //dd($ai_cash);
    }


    $i = 0;
    $data = array();

    foreach ($ai_cash as $value){
      $i++;
      $nestedData['order'] = $i;

      $nestedData['create_at'] = '<span class="label label-inverse-info-border">'.date('d/m/Y H:i:s',strtotime($value->create_at)).'</span>' ;

      $nestedData['type'] = 'เครดิตการ์ด';
      $nestedData['order_type'] = 'เติมเงิน';

      $nestedData['price'] = $value->ai_cash;


      if( $value->status == 'success'){
        if(empty($value->banlance)){
          $banlance = '';
        }else{
          $banlance = number_format($value->banlance);
        }

      }elseif ($value->status == 'panding'){
       $class_css = 'warning';
       $banlance ='<span class="label label-'.$class_css.'"><b style="color: #000">'.$value->status.'</b></span>';
     }else{
       $class_css = 'danger'; 
     }

   // $nestedData['status'] =  '<span class="label label-'.$class_css.'"><b style="color: #000">'.$value->status.'</b></span>';

     $nestedData['banlance'] = $banlance;

     

     $nestedData['detail'] = $value->detail;

     $data[] = $nestedData;
   }

   $json_data = array(
    "draw" => intval($request->input('draw')),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data,
  );

   return json_encode($json_data);
 }




}


