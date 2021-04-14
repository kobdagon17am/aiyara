<?php

namespace App\Http\Controllers\Frontend\Fc;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LogOrderController extends Controller
{

    public static function log_order($data)
    {
      dd($data);
      $insert_log_gift_voucher = DB::table('log_orders')->insert([
        'customer_id_fk'=> $customer_id,
        'order_id_fk'=>$order_id,
        'code_order'=>$order_data->code_order,
        'fee'=>$order_data->fee,
        'fee_amt'=>$order_data->fee_amt,
        'product_value'=>$order_data->product_value,
        'shipping_price'=>$order_data->shipping_price,
        'vat'=>$order_data->vat,
        'sum_price'=>$order_data->sum_price,
        'total_price'=>$order_data->total_price,
        'pv_total'=>$order_data->pv_total,
        'gift_voucher'=>$order_data->gift_voucher,
        'price_remove_gv'=>$order_data->price_remove_gv,
        'active_mt_tv_date_old'=>'',
        'active_mt_tv_date'=>'',
        'status_pv_mt_old'=>'',
        'status_pv_mt'=>'',
        'business_location_id_fk'=>'',
        'distribution_channel_id_fk'=>$distribution_channel,
        'purchase_type_id_fk'=>'',
        'pay_type_id_fk'=>'',
        'action_user_id_fk'=>'',//id คนยกเลิกบิล หรือ ยืนยันบิล สามารถเป็นได้ทั้ง Admin id และ Customer id ขึ้นอยู่กับ action_type
        'action_type'=>'',//Type ของผู้ดำเนินการ 'admin','customer'
        'status'=>'success',
      ]);
    }


}
