<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;
use App\Models\Frontend\Product;
use Auth;

class HistoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer');
    }

    public function index(){

        return view('frontend/product/product-history');
    }

    public function dt_history(Request $request){

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
        ->where('orders.customer_id','=',Auth::guard('c_user')->user()->id)
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
        ->where('orders.customer_id','=',Auth::guard('c_user')->user()->id)
        ->offset($start)
        ->limit($limit)
        ->orderby('id','DESC')
        ->get(); 

        $data = array();

        $i = 0;
        foreach ($orders as $value){
            $i++;
            $nestedData['id'] = $i;
            $nestedData['code_order'] = $value->code_order;
            $nestedData['price'] = number_format($value->price + $value->shipping,2);
            $nestedData['pv_total'] = '<b class="text-success">'.$value->pv_total.'</b>';
            $nestedData['date'] = date('d/m/Y H:i:s',strtotime($value->create_at));
            $nestedData['type'] = $value->type;


            $nestedData['status'] = '<button class="btn btn-sm btn-'.$value->css_class.' btn-outline-'.$value->css_class.'" ><b style="color: #000">'.$value->detail.'</b></button>'; 
 
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


    public function upload_slip(Request $request){
        $file_slip = $request->file_slip;
        if(isset($file_slip)){
            $url='local/public/files_slip/'.date('Ym');

            $f_name = date('YmdHis').'_'.Auth::guard('c_user')->user()->id.'.'.$file_slip->getClientOriginalExtension();
            if($file_slip->move($url,$f_name)){
                try {
                    DB::BeginTransaction();
                    DB::table('payment_slip')
                    ->insert(['customer_id'=>Auth::guard('c_user')->user()->id,'url'=>$url,'file'=>$f_name,'order_id'=>$request->order_id]);

                    DB::table('orders')
                    ->where('id',$request->order_id)
                    ->update(['orderstatus_id' => '2']);

                    DB::commit();
                    return redirect('product-history')->withSuccess('Upload Slip Success');
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect('product-history')->withError('Upload Slip fail');

                }


            }else{


                return redirect('product-history')->withError('Upload Slip fail');

            }
        }
    }

    public function cart_payment_history($code_order){

        $order =  DB::table('orders')
        ->select('orders.*','dataset_order_status.detail','dataset_order_status.css_class','dataset_orders_type.orders_type as type',
            'dataset_business_major.name as office_name',
            'dataset_business_major.house_no as office_house_no',
            'dataset_business_major.name as office_house_name',
            'dataset_business_major.moo as office_moo',
            'dataset_business_major.soi as office_soi',
            'dataset_business_major.district as office_district',
            'dataset_business_major.district_sub as office_district_sub',
            'dataset_business_major.road as office_road',
            'dataset_business_major.province as office_province',
            'dataset_business_major.zipcode as office_zipcode',
            'dataset_business_major.tel as office_tel',
            'dataset_business_major.email as office_email',
            'order_payment_code.order_payment_code') 
        ->leftjoin('dataset_order_status','dataset_order_status.orderstatus_id','=','orders.orderstatus_id')
        ->leftjoin('dataset_orders_type','dataset_orders_type.group_id','=','orders.type_id') 
        ->leftjoin('dataset_business_major','dataset_business_major.location_id','=','orders.type_address') 
        ->leftjoin('order_payment_code','order_payment_code.order_id','=','orders.id') 
        ->where('dataset_order_status.lang_id','=','1')
        ->where('dataset_orders_type.lang_id','=','1')
        ->where('orders.code_order','=',$code_order)
        ->first();

        $order_items =  DB::table('order_items')
        ->where('order_id','=',$order->id)
        ->get();

        //dd($order_items);

        if(!empty($order)){ 
         return view('frontend/product/cart-payment-history',compact('order','order_items'));
     }else{
        return redirect('product-history')->withError('Payment Data is Null');
    }


}

}


