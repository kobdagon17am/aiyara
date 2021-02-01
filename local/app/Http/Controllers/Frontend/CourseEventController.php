<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;
use App\Models\Frontend\Product;
use App\Models\Frontend\Random_code;
use App\Models\Frontend\CourseCheckRegis;
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

  public function modal_qr_ce(Request $request){
    $course_event_regis_id = $request->id;

    $data_course_event_regis = DB::table('course_event_regis') 
    ->where('id',$course_event_regis_id)
    ->first();

    if($data_course_event_regis->qr_code){
      $qr_endate = strtotime($data_course_event_regis->qr_endate);
      if( $qr_endate < strtotime(now()) ){

       $random = Random_code::random_code('8');
       $qr = $course_event_regis_id.''.$random;

       $endata = date('Y-m-d H:i:s',strtotime("+1 day"));
       $updated_qrcode = DB::table('course_event_regis') 
       ->where('id',$course_event_regis_id)
       ->update(['qr_code' => $qr,'qr_endate' => $endata ]);

     }

   }else{

    $random = Random_code::random_code('8');
    $qr = $course_event_regis_id.''.$random;

    $edata = date('Y-m-d H:i:s',strtotime("+1 day"));
    $updated_qrcode = DB::table('course_event_regis') 
    ->where('id',$course_event_regis_id)
    ->update(['qr_code' => $qr,'qr_endate' => $edata ]);
  }

  $data =  DB::table('course_event_regis')
  ->select('course_event_regis.*','course_ticket_number.ticket_number')
  ->leftjoin('course_ticket_number', 'course_ticket_number.id', '=','course_event_regis.ticket_id') 
  ->where('course_event_regis.id','=',$course_event_regis_id)
  ->first();

  return view('frontend/modal/modal_qr_ce',compact('data')); 
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
    ->offset($start) 
    ->limit($limit)
    ->orderby('course_event_regis.updated_at','DESC') 
    ->get(); 

// dd($request->input('order_type'));

  }else{

   $search=$request->input('search.value');
   $order_type=$request->input('order_type');

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
   ->offset($start) 
   ->limit($limit)
   ->orderby('course_event_regis.updated_at','DESC') 
   ->get();  
 }

 $data = array();
 $i= $start;
 foreach($course_event_regis as $value){

  $i++;
  $nestedData['id'] = $i;

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
  .date('d/m/Y',strtotime($value->ce_sdate)).'</b></span>';


  $nestedData['edate'] = '<span class="label label-inverse-'.$date_css.'"><b>'
  .date('d/m/Y',strtotime($value->ce_edate)).'</b></span>';

  $nestedData['title'] = '<b>'.$value->ce_name.'</b>';



  if($value->txt_desc == 'Course'){
    $nestedData['type'] = '<label class="label label-inverse-primary"><b style="color:#000">'.$value->txt_desc.'</b></label>';

  }else{
    $nestedData['type'] = '<label class="label label-inverse-warning"><b style="color:#000">'.$value->txt_desc.'</b></label>';

  }


  $nestedData['price'] = '<b class="text-primary">'.number_format($value->ce_ticket_price).'</b>';
  $nestedData['pv'] =  '<b class="text-success">'.number_format($value->pv).'</b>';

  // $nestedData['status'] = '<button class="btn btn-sm btn-'.$value->css_class.' btn-outline-'.$value->css_class.'" ><b style="color: #000">'.$value->detail.'</b></button>'; 
  if($value->status_ce == 'Y' ){

   $status = '<button class="btn btn-sm btn-success btn-outline-success"><b style="color: #000">ผ่านกิจกรรม</b></button>';

 }else{
  $status = '<button class="btn btn-sm btn-warning btn-outline-warning"><b style="color: #000">ยังไม่ผ่านกิจกรรม</b></button>';
} 

$nestedData['status'] = $status;  

$nestedData['qrcode'] = '<button class="btn btn-sm btn-primary" onclick="qrcode('.$value->id.')" ><i class="fa fa-qrcode"></i> QR </button>';

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


