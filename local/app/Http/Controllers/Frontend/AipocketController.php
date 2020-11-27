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

class AipocketController extends Controller
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

        return view('frontend/aipocket',compact('type'));
    }

    public function dt_aipocket(Request $request){

        $columns = array(
            0 => 'order',
            1 => 'customer_id',
            2 => 'to_customer_id',
            3 => 'create_at',
            4 => 'type',
            5 => 'pv',
            6 => 'status',
            7 => 'detail',
        );

        //1 รอส่งเอกสารการชำระเงิน warning
        //2 ตรวจสอบการชำระเงิน warning
        //3 เอกสารการชำระเงินไม่ผ่าน danger
        //4 จัดเตรียมสินค้า primary
        //5 จัดส่งสินค้า primary
        //6 ได้รับสินค้าแล้ว Success 

        if(empty($request->input('search.value'))){

          $totalData =  DB::table('ai_pocket')
          ->leftjoin('customers as c_use','ai_pocket.customer_id','=','c_use.id')
          ->leftjoin('customers as c_to','ai_pocket.to_customer_id','=','c_to.id')
          ->leftjoin('dataset_orders_type','ai_pocket.type_id','=','dataset_orders_type.group_id')
          ->where('dataset_orders_type.lang_id','=','1')
          ->count();

          $totalFiltered = $totalData;

          $limit = $request->input('length');
          $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');


          $ai_pocket =  DB::table('ai_pocket')
          ->select('ai_pocket.*','c_use.business_name as business_name_use','c_to.business_name as business_name_to','c_use.user_name as c_use','c_to.user_name as c_to','dataset_orders_type.orders_type') 
          ->leftjoin('customers as c_use','ai_pocket.customer_id','=','c_use.id')
          ->leftjoin('customers as c_to','ai_pocket.to_customer_id','=','c_to.id')
          ->leftjoin('dataset_orders_type','ai_pocket.type_id','=','dataset_orders_type.group_id')
          ->where('dataset_orders_type.lang_id','=','1')
          ->offset($start)
          ->limit($limit)
          ->orderby('ai_pocket.create_at','DESC')
          ->get(); 

      }else{

        $search = trim($request->input('search.value'));

          $totalData =  DB::table('ai_pocket')
          ->leftjoin('customers as c_use','ai_pocket.customer_id','=','c_use.id')
          ->leftjoin('customers as c_to','ai_pocket.to_customer_id','=','c_to.id')
          ->leftjoin('dataset_orders_type','ai_pocket.type_id','=','dataset_orders_type.group_id')
          ->where('dataset_orders_type.lang_id','=','1')
          ->whereRaw('(c_use.user_name LIKE "%'.$search.'%" or c_to.user_name LIKE "%'.$search.'%")' )
          ->count();

          $totalFiltered = $totalData;

          $limit = $request->input('length');
          $start = $request->input('start'); 
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

           
          $ai_pocket =  DB::table('ai_pocket')
          ->select('ai_pocket.*','c_use.business_name as business_name_use','c_to.business_name as business_name_to','c_use.user_name as c_use','c_to.user_name as c_to','dataset_orders_type.orders_type') 
          ->leftjoin('customers as c_use','ai_pocket.customer_id','=','c_use.id')
          ->leftjoin('customers as c_to','ai_pocket.to_customer_id','=','c_to.id')
          ->leftjoin('dataset_orders_type','ai_pocket.type_id','=','dataset_orders_type.group_id')
          ->where('dataset_orders_type.lang_id','=','1')
          ->whereRaw('(c_use.user_name LIKE "%'.$search.'%" or c_to.user_name LIKE "%'.$search.'%" or c_use.business_name LIKE "%'.$search.'%" or c_to.business_name LIKE "%'.$search.'%")' )
          ->offset($start)
          ->limit($limit)
          ->orderby('ai_pocket.create_at','DESC')
          ->get(); 

          //dd($ai_pocket);

      }


      $i = 0;
      $data = array();

      foreach ($ai_pocket as $value){
        $i++;
        $columns = array(
            0 => 'order',
            1 => 'customer_id',
            2 => 'to_customer_id',
            3 => 'create_at',
            4 => 'type',
            5 => 'pv',
            6 => 'status',
            7 => 'detail',
        );

        $nestedData['order'] = $i;
        if(Auth::guard('c_user')->user()->id == $value->customer_id){
         $nestedData['customer_id'] = '<span class="label label-success"><b style="color: #000"><i class="fa fa-user"></i> You </b></span>';

     }else{
         $nestedData['customer_id'] = $value->business_name_use.' <b>( '.$value->c_use.' )</b>';

     }

     if(Auth::guard('c_user')->user()->id == $value->to_customer_id){
         $nestedData['to_customer_id'] = '<span class="label label-success"><b style="color: #000"><i class="fa fa-user"></i> You </b></span>';

     }else{
         $nestedData['to_customer_id'] = $value->business_name_to.' <b>( '.$value->c_to.' )</b>';

     }

     $nestedData['create_at'] = date('d/m/Y H:i:s',strtotime($value->create_at));
     $nestedData['type'] = $value->detail;
     $nestedData['pv'] = '<b class="text-success">'.$value->pv.'</b>';

     if( $value->status == 'success'){
        $class_css = 'success';
    }elseif ($value->status == 'panding'){
     $class_css = 'warning';
 }else{
   $class_css = 'danger'; 
}

$nestedData['status'] =  '<span class="label label-'.$class_css.'"><b style="color: #000">'.$value->status.'</b></span>';
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




public function check_customer_id(Request $request){

    $resule =LineModel::check_line($request->user_name);
    if($resule['status'] == 'success'){
        $data = array('status'=>'success','data'=>$resule);
    }else{
       $data = array('status'=>'fail','data'=>$resule);
   }
        //$data = ['status'=>'fail'];
   return $data;
}

public function use_aipocket(Request $request){
    $type = $request->type;
    $pv = str_replace(',', '', $request->pv);
    $username = $request->username;

    if($pv>Auth::guard('c_user')->user()->pv_aipocket){
        return redirect('ai-pocket')->withError('PV Ai-Pocket ของคุณมีไม่เพียงพอ ');

    }else{
        $resule = Runpv::run_pv($type,$pv,$username);
        if($resule['status'] == 'success'){
            $to_customer_id = DB::table('customers')
            ->where('user_name','=',$username)
            ->first();
            DB::table('ai_pocket')
            ->insert(['customer_id'=>Auth::guard('c_user')->user()->id,'to_customer_id'=>$to_customer_id->id,'pv'=>$pv,'status'=>'success','type_id'=>$type,'detail'=>'Sent Ai-Pocket']);

            return redirect('ai-pocket')->withSuccess('Sent Ai-Pocket Success');

        }else{
           return redirect('ai-pocket')->withError('Sent Ai-Pocket Fail');

       }

   }

}

}


