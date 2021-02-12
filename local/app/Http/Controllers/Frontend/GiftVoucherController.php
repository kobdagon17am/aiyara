<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
class GiftVoucherController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer');
    }

    public function index(){
        return view('frontend/giftvoucher_history');
    }

    public function gift_order_history(){
        return view('frontend/gift_order_history');
    }


    public function dt_giftvoucher_history(Request $request){

        $columns = array(
            0 => 'id',
            1 => 'date',
            2 => 'expiry_date',
            3 => 'code',
            4 => 'detail',
            5 => 'bill',
            6 => 'gv',
            7 => 'banlance',
        );

        if( empty($request->input('search.value')) and empty($request->input('status')) ){

           $totalData =  DB::table('gift_voucher') 
           ->where('gift_voucher.customer_id','=',Auth::guard('c_user')->user()->id)
           ->count();
           $totalFiltered = $totalData;
           $limit = $request->input('length');
           $start = $request->input('start');
            //$order = $columns[$request->input('order.0.column')];
            //$dir = $request->input('order.0.dir');

           $gift_voucher =  DB::table('gift_voucher')
           ->select('*') 
           ->where('gift_voucher.customer_id','=',Auth::guard('c_user')->user()->id)
           ->offset($start)
           ->limit($limit)
           ->orderby('id','DESC')
           ->get(); 

// dd($request->input('status'));

       }else{
        $status = $request->status;
        $search=$request->input('search.value');
        $date = date('Y-m-d H:i:s');
        //$status=$request->input('status');

        $totalData =  DB::table('gift_voucher')
        ->where('gift_voucher.customer_id','=',Auth::guard('c_user')->user()->id)
        ->whereRaw(("case WHEN '{$status}' = 'not_expiry_date' THEN gift_voucher.expiry_date > '{$date}' WHEN '{$status}' = 'expiry_date' THEN  gift_voucher.expiry_date < '{$date}'
            else 1 END"))
        ->whereRaw("(gift_voucher.code LIKE '%{$search}%')" )
        ->count(); 

        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');

        //dd($query); 
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

        $gift_voucher =  DB::table('gift_voucher') 
        ->select('*') 
        ->where('gift_voucher.customer_id','=',Auth::guard('c_user')->user()->id)
        ->whereRaw(("case WHEN '{$status}' = 'expiry_date' THEN gift_voucher.expiry_date > '{$date}' WHEN '{$status}' = 'not_expiry_date' THEN  gift_voucher.expiry_date < '{$date}'
            else 1 END"))
        ->whereRaw("(gift_voucher.code LIKE '%{$search}%')" )
        ->limit($limit)
        ->orderby('id','DESC')
        ->get(); 

    }

    $data = array();
    $i= $start;
    foreach ($gift_voucher as $value){
        $i++;
        $nestedData['id'] = $i;

        $nestedData['date'] = '<label class="label label-inverse-info-border"><b>'.date('d/m/Y H:i:s',strtotime($value->create_at)).'</b></label>';

        $expiry_date = strtotime($value->expiry_date);

        if (empty($value->expiry_date)){
         $nestedData['expiry_date'] = '';
     }elseif( $expiry_date > strtotime(date('Y-m-d H:i:s')) ) {
       $nestedData['expiry_date'] = '<label class="label label-inverse-success"><b>'.date('d-m-Y H:i:s',$expiry_date).'</b></label>';

   }else{
    $nestedData['expiry_date'] = '<label class="label label-inverse-danger"><b>'.date('d-m-Y H:i:s',$expiry_date).'</b></label>';
}

$nestedData['code'] = $value->code;
$nestedData['detail'] = $value->detail;
$nestedData['bill'] = $value->order_id;

$nestedData['gv'] = '<b class="text-primary">'.$value->gv.'</b>';
$nestedData['banlance'] = '<b class="text-success">'.$value->banlance.'</b>';
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

public function dt_gift_order_history(Request $request){


    $columns = array(
        0 => 'id',
        1 => 'date',
        2 => 'order',
        3 => 'gv',
        4 => 'status'
    );

    if( empty($request->input('search.value')) and empty($request->input('status')) ){

       $totalData =  DB::table('log_gift_voucher') 
       ->leftjoin('db_orders', 'db_orders.id', '=', 'log_gift_voucher.order_id')
       ->where('log_gift_voucher.customer_id','=',Auth::guard('c_user')->user()->id)
       ->count();
       $totalFiltered = $totalData;
       $limit = $request->input('length');
       $start = $request->input('start');
            //$order = $columns[$request->input('order.0.column')];
            //$dir = $request->input('order.0.dir');

       $gift_voucher =  DB::table('log_gift_voucher')
       ->select('log_gift_voucher.*','db_orders.code_order') 
       ->leftjoin('db_orders','db_orders.id','=', 'log_gift_voucher.order_id')
       ->where('log_gift_voucher.customer_id','=',Auth::guard('c_user')->user()->id)
       ->offset($start)
       ->limit($limit)
       ->orderby('id','DESC')
       ->get(); 

// dd($request->input('status'));

   }else{
    $status = $request->status;
    $search=$request->input('search.value');
    $date = date('Y-m-d H:i:s');
        //$status=$request->input('status');

    $totalData =  DB::table('log_gift_voucher')
    ->leftjoin('db_orders', 'db_orders.id', '=', 'log_gift_voucher.order_id')
    ->where('log_gift_voucher.customer_id','=',Auth::guard('c_user')->user()->id)
    ->whereRaw(("case WHEN '{$status}' = 'success' THEN log_gift_voucher.status = 'success' || log_gift_voucher.status = 'order'  WHEN '{$status}' = 'cancel' THEN log_gift_voucher.status = 'cancel'
        else 1 END"))
    ->whereRaw("(db_orders.code_order LIKE '%{$search}%')" )
    ->count(); 

    $totalFiltered = $totalData;
    $limit = $request->input('length'); 
    $start = $request->input('start');

        //dd($query); 
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

    $gift_voucher =  DB::table('log_gift_voucher') 
    ->select('log_gift_voucher.*','db_orders.code_order') 
    ->leftjoin('db_orders', 'db_orders.id', '=', 'log_gift_voucher.order_id')
    ->where('log_gift_voucher.customer_id','=',Auth::guard('c_user')->user()->id)
    ->whereRaw(("case WHEN '{$status}' = 'success' THEN log_gift_voucher.status = 'success' || log_gift_voucher.status = 'order'  WHEN '{$status}' = 'cancel' THEN log_gift_voucher.status = 'cancel'
        else 1 END"))
    ->whereRaw("(db_orders.code_order LIKE '%{$search}%')" )
    ->limit($limit)
    ->orderby('id','DESC')
    ->get(); 

}

$data = array();
$i=0;
foreach ($gift_voucher as $value){
    $i++;
    $nestedData['id'] = $i;

    $nestedData['date'] = '<label class="label label-inverse-info-border"><b>'.date('d/m/Y H:i:s',strtotime($value->create_at)).'</b></label>';
    $nestedData['order'] = $value->code_order;
    $nestedData['gv'] = '<b class="text-primary">'.$value->gv.'</b>';
    if($value->status == 'success' || $value->status == 'order'){
     $nestedData['status'] = '<label class="label label-inverse-success"><b> Success </b></label>';
 }else{
     $nestedData['status'] = '<label class="label label-inverse-danger"><b> Cancel </b></label>';

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


