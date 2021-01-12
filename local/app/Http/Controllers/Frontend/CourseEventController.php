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
      1 => 'sdate',
      2 => 'edate',
      3 => 'type',
      4 => 'title',
      5 => 'price',
      6 => 'pv',
      7 => 'status',
      8 => 'qrcode',
    );

    if(empty($request->input('search.value')) ){

      $totalData =  DB::table('course_event_regis')
      ->leftjoin('course_event', 'course_event.id', '=','course_event_regis.ce_id_fk') 
      ->leftjoin('dataset_ce_type', 'course_event.ce_type','=','dataset_ce_type.id') 
      ->where('course_event_regis.customers_id_fk','=',Auth::guard('c_user')->user()->id)
      ->count();
      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

      $course_event_regis = DB::table('course_event_regis')
      ->select('course_event_regis.*','course_event.ce_name','course_event.ce_name','course_event.ce_sdate','course_event.ce_edate','course_event.ce_ticket_price','course_event.pv','dataset_ce_type.txt_desc') 
      ->leftjoin('course_event','course_event.id', '=','course_event_regis.ce_id_fk')
      ->leftjoin('dataset_ce_type','course_event.ce_type','=','dataset_ce_type.id') 
      ->where('course_event_regis.customers_id_fk','=',Auth::guard('c_user')->user()->id)
      ->orderby('course_event_regis.updated_at','DESC') 
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
   foreach($course_event_regis as $value){

    $i++;
    $nestedData['id'] = $i;


    // <label class="label label-inverse-danger"><b>31-12-2020 14:49:50</b></label>
    // <label class="label label-inverse-primary"><b>31-12-2020 14:49:50</b></label>
    if(strtotime($value->ce_sdate) > strtotime(now()) ){
      $date_css = 'primary';
    }else{
      $date_css = 'danger';
    }

    if(strtotime($value->ce_edate) > strtotime(now()) ){
      $date_css = 'primary';
    }else{
      $date_css = 'danger';
    }

    $nestedData['sdate'] = '<span class="label label-inverse-'.$date_css.'"><b>'
    .date('d/m/Y',strtotime($value->ce_sdate)).'</b></label>';


    $nestedData['edate'] = '<span class="label label-inverse-'.$date_css.'"><b>'
    .date('d/m/Y',strtotime($value->ce_edate)).'</b></label>';

    $nestedData['title'] = '<b>'.$value->ce_name.'</b>';


    
    if($value->txt_desc == 'Course'){
      $nestedData['type'] = '<label class="label label-inverse-primary"><b style="color:#000">'.$value->txt_desc.'</b></label>';

    }else{
      $nestedData['type'] = '<label class="label label-inverse-warning"><b style="color:#000">'.$value->txt_desc.'</b></label>';

    }


    $nestedData['price'] = '<b class="text-primary">'.number_format($value->ce_ticket_price).'</b>';
    $nestedData['pv'] =  '<b class="text-success">'.number_format($value->pv).'</b>';

  // $nestedData['status'] = '<button class="btn btn-sm btn-'.$value->css_class.' btn-outline-'.$value->css_class.'" ><b style="color: #000">'.$value->detail.'</b></button>'; 
    $nestedData['status'] = ''; 
    $nestedData['qrcode'] = '';


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


