<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;
use App\Models\Frontend\Product;
use App\Models\Frontend\LineModel;
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
            0 => 'id',
            1 => 'code_order',
            2 => 'price',
            3 => 'pv_total',
            4 => 'type',
            5 => 'date',
            6 => 'status',
            7 => 'action',
        );

        $totalData =  DB::table('orders')
        ->leftjoin('dataset_order_status','dataset_order_status.orderstatus_id','=','orders.orderstatus_id')
        ->leftjoin('dataset_orders_type','dataset_orders_type.group_id','=','orders.type_id')
        ->where('dataset_orders_type.lang_id','=','1')
        ->where('dataset_order_status.lang_id','=','1')
        ->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');

//1 รอส่งเอกสารการชำระเงิน warning
//2 ตรวจสอบการชำระเงิน warning
//3 เอกสารการชำระเงินไม่ผ่าน danger
//4 จัดเตรียมสินค้า primary
//5 จัดส่งสินค้า primary
//6 ได้รับสินค้าแล้ว Success 

        $orders =  DB::table('orders')
        ->select('orders.*','dataset_order_status.detail','dataset_order_status.css_class','dataset_orders_type.orders_type as type') 
        ->leftjoin('dataset_order_status','dataset_order_status.orderstatus_id','=','orders.orderstatus_id')
        ->leftjoin('dataset_orders_type','dataset_orders_type.group_id','=','orders.type_id') 
        ->where('dataset_order_status.lang_id','=','1')
        ->where('dataset_orders_type.lang_id','=','1')
        ->offset($start)
        ->limit($limit)
        ->orderby('id','DESC')
        ->get(); 
        $i = 0;
        foreach ($orders as $value){
            $i++;
            $nestedData['id'] = $i;
            $nestedData['code_order'] = $value->code_order;
            $nestedData['price'] = number_format($value->price + $value->shipping,2);
            $nestedData['pv_total'] = '<b class="text-success">'.$value->pv_total.'</b>';
            $nestedData['date'] = date('d/m/Y H:i:s',strtotime($value->create_at));
            $nestedData['type'] = $value->type;
            $nestedData['status'] = '<span style="font-size: 14px;"class="pcoded-badge label label-'.$value->css_class.'"><font style="color:#000">'.$value->detail.'</font></span>';
            if($value->orderstatus_id == 1 || $value->orderstatus_id == 3){
                $upload = '<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#large-Modal" onclick="upload_slip('.$value->id.')"><i class="fa fa-file-text-o"></i> Upload </button>';
            }else{
                $upload = '';
            }


            $nestedData['action'] = '<a class="btn btn-sm btn-primary" href="'.route('cart-payment-history',['code_order'=>$value->code_order]).'" ><i class="fa fa-file-text-o"></i> View </a> '.$upload;

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



}


