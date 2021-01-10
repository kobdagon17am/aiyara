<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;
use App\Models\Frontend\Product;
use Auth;

class CourseEventController extends Controller
{

  public function __construct()
  {
    $this->middleware('customer');
  }

  public function index(){
    // $data = DB::table('dataset_orders_type')
    // ->where('status','=','1')
    // ->where('lang_id','=','1')
    // ->orderby('order')
    // ->get();

    return view('frontend/course');
  }

  public function dt_course(Request $request){

    $columns = array(
      0 => 'id',
      1 => 'date',
      2 => 'code',
      3 => 'title',
      4 => 'address',
      5 => 'status',
      6 => 'qrcode'
    );

    if(empty($request->input('search.value')) ){

     $totalData =  DB::table('orders')
     ->leftjoin('dataset_order_status','dataset_order_status.orderstatus_id','=','orders.orderstatus_id')
     ->leftjoin('dataset_orders_type','dataset_orders_type.group_id','=','orders.type_id')
     ->leftjoin('dataset_pay_type','dataset_pay_type.pay_type_id','=','orders.pay_type_id')
     ->where('dataset_orders_type.lang_id','=','1')
     ->where('dataset_order_status.lang_id','=','1')

     ->where('orders.customer_id','=',Auth::guard('c_user')->user()->id)
     ->count();
     $totalFiltered = $totalData;

     $limit = $request->input('length');
     $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

     $orders =  DB::table('orders')
     ->select('orders.*','dataset_order_status.detail','dataset_order_status.css_class','dataset_orders_type.orders_type as type','dataset_pay_type.detail as pay_type_name') 
     ->leftjoin('dataset_order_status','dataset_order_status.orderstatus_id','=','orders.orderstatus_id')
     ->leftjoin('dataset_orders_type','dataset_orders_type.group_id','=','orders.type_id')
     ->leftjoin('dataset_pay_type','dataset_pay_type.pay_type_id','=','orders.pay_type_id') 
     ->where('dataset_order_status.lang_id','=','1')
     ->where('dataset_orders_type.lang_id','=','1')
     ->where('orders.customer_id','=',Auth::guard('c_user')->user()->id)
     ->offset($start)
     ->limit($limit)
     ->orderby('orders.updated_at','DESC') 
     ->get(); 
// dd($request->input('order_type'));

   }else{

     $search=$request->input('search.value');
     $order_type=$request->input('order_type');
         //DB::enableQueryLog();
         //dd($search.':'.$order_type);
     $totalData =  DB::table('orders')
     ->leftjoin('dataset_order_status','dataset_order_status.orderstatus_id','=','orders.orderstatus_id')
     ->leftjoin('dataset_orders_type','dataset_orders_type.group_id','=','orders.type_id')
     ->leftjoin('dataset_pay_type','dataset_pay_type.pay_type_id','=','orders.pay_type_id') 
     ->where('dataset_order_status.lang_id','=','1')
     ->where('dataset_orders_type.lang_id','=','1')
     ->where('orders.customer_id','=',Auth::guard('c_user')->user()->id)
     ->whereRaw(("case WHEN '{$order_type}' = '' THEN 1 else dataset_orders_type.group_id = '{$order_type}' END"))
     ->whereRaw( "(orders.code_order LIKE '%{$search}%' or orders.tracking_number LIKE '%{$search}%')" )
     ->count(); 

     $totalFiltered = $totalData;
     $limit = $request->input('length');
     $start = $request->input('start');

        //dd($query);
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

     $orders =  DB::table('orders')
     ->select('orders.*','dataset_order_status.detail','dataset_order_status.css_class','dataset_orders_type.orders_type as type','dataset_pay_type.detail as pay_type_name') 
     ->leftjoin('dataset_order_status','dataset_order_status.orderstatus_id','=','orders.orderstatus_id')
     ->leftjoin('dataset_orders_type','dataset_orders_type.group_id','=','orders.type_id')
     ->leftjoin('dataset_pay_type','dataset_pay_type.pay_type_id','=','orders.pay_type_id') 
     ->where('dataset_order_status.lang_id','=','1')
     ->where('dataset_orders_type.lang_id','=','1')
     ->where('orders.customer_id','=',Auth::guard('c_user')->user()->id)
     ->whereRaw(("case WHEN '{$order_type}' = '' THEN 1 else dataset_orders_type.group_id = '{$order_type}' END"))
     ->whereRaw( "(orders.code_order LIKE '%{$search}%' or orders.tracking_number LIKE '%{$search}%')" )
     ->offset($start) 
     ->limit($limit)
     ->orderby('orders.updated_at','DESC') 
     ->get(); 

   }

   $data = array();
   $i=0;
   foreach ($orders as $value){
    $i++;
    $nestedData['id'] = $i;

    $nestedData['code_order'] = $value->code_order;
    if($value->tracking_number){
     $nestedData['tracking'] = '<label class="label label-inverse-info"><b style="color:#000">'.$value->tracking_number.'</b></label>';

   }else{
     $nestedData['tracking'] = '';
   }

   if($value->type_id == 5){
    $nestedData['price'] = number_format($value->price_remove_gv,2);

  }elseif($value->type_id == 6){
   $nestedData['price'] = number_format($value->price,2);

 }else{
  $nestedData['price'] = number_format($value->price + $value->shipping,2);

}

$nestedData['pv_total'] = '<b class="text-success">'.number_format($value->pv_total).'</b>';
$nestedData['date'] = '<span class="label label-inverse-info-border">'.date('d/m/Y H:i:s',strtotime($value->created_at)).'</span>';
$nestedData['type'] = $value->type;


$nestedData['status'] = '<button class="btn btn-sm btn-'.$value->css_class.' btn-outline-'.$value->css_class.'" ><b style="color: #000">'.$value->detail.'</b></button>'; 

if($value->orderstatus_id == 1 || $value->orderstatus_id == 3){
  $upload = '<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#large-Modal" onclick="upload_slip('.$value->id.')"><i class="fa fa-file-text-o"></i> Upload </button>';
}else{
  $upload = '';
}


$nestedData['action'] = '<a class="btn btn-sm btn-primary" href="'.route('cart-payment-history',['code_order'=>$value->code_order]).'" ><i class="fa fa-file-text-o"></i> View </a> '.$upload;
if($value->banlance){
  $banlance= number_format($value->banlance);
}else{
  $banlance = '';
}
$nestedData['banlance'] = '<b class="text-primary">'.$banlance.'</b>';
$nestedData['pay_type_name'] = '<b class="text-primary">'.$value->pay_type_name.'</b>';
if(empty($value->active_mt_tv_date)){
  $nestedData['date_active'] = '';

}else{

  $date_active= date('d/m/Y',strtotime($value->active_mt_tv_date));
  $nestedData['date_active'] = '<span class="label label-inverse-info-border" data-toggle="tooltip" data-placement="right" data-original-title="'.$date_active.'"><b style="color:#000">'.$date_active.'<b></span>';
}

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


