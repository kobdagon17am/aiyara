<?php

namespace App\Http\Controllers\Frontend\Fc;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\Fc\RunPvController;
use Illuminate\Support\Facades\DB;
use App\Models\Db_Ai_stockist;
use App\Models\Frontend\Customer;
use App\Models\Frontend\Order;

class CancelOrderVipShopController extends Controller
{

  public static function cancel_order_vip($order_id, $customer_or_admin, $type_user_cancel, $action_type)
  {

    DB::BeginTransaction();

    $order_data  = Order::find($order_id);


    $delivery = DB::table('db_delivery')
      ->where('orders_id_fk', '=', $order_id)
      ->first();

    if ($delivery) {
      if ($delivery->status_to_wh != 0) {
        $resule = ['status' => 'fail', 'message' => 'ไม่สามารถยกเลิกบิลได้เนื่องจากสินค้าเข้าสู่ขั้นตอนแพคสินค้าแล้ว'];
        return $resule;
      }
    }


    $customer_id = $order_data->customers_id_fk;



    $customer_order = Customer::find($order_data->customers_id_fk);
    $customer_user = Customer::find($customer_id);

    //dd($order_data->approve_status);
    if (empty($order_data)) {
      DB::rollback();
      $log = [
        'customer_user_name' => $customer_user->user_name,
        'admin_user_name' => '',
        'code_order' =>  '',
        'error_detail' => 'ไม่มีบิลนี้อยู่ในระบบ Order ID :' . $order_id,
        'function' =>
        'App\Http\Controllers\Frontend\Fc\CancelOrderController::cancel_order()',
        'type' => $action_type, //'admin','customer'
        'order_type' => '5',
      ];
      $rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);
      $resule = ['status' => 'fail', 'message' => 'ไม่มีบิลนี้อยู่ในระบบ'];
      DB::commit();
      return $resule;
    }

    $update_ai_stockist = new Db_Ai_stockist();

    try {
      $type_id = $order_data->purchase_type_id_fk;

      //1=รออนุมัติ,2=อนุมัติแล้ว,3=รอชำระ,4=รอจัดส่ง,5=ยกเลิก,6=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย) > Ref>dataset_approve_status>id
      if ($order_data->approve_status == 0 || $order_data->approve_status == 1 || $order_data->approve_status == 3) { //กรณีสั่งซื้อเเล้วยังไม่มีการอนุมัติค่า PV ยังไม่ถูกดำเนินการ  0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย)'

        $order_data->cancel_by_user_id_fk = $customer_or_admin;
        $order_data->order_status_id_fk = 8; //Status  8 = Cancel
        $order_data->approve_status = 5; //status = Cancel
        $order_data->type_user_cancel = $type_user_cancel; //Customer
        $order_data->cancel_action_date = date('Y-m-d H:i:s');
      } elseif ($order_data->approve_status == 2 || $order_data->approve_status == 4 || $order_data->approve_status == 9) { //กรณีบัตรเครดิตร หรือ Admin มีการอนุมัติเรียบร้อยเเล้ว

        $order_data->cancel_by_user_id_fk = $customer_or_admin;
        $order_data->order_status_id_fk = 8; //Status  8 = Cancel
        $order_data->approve_status = 5; //status = Cancel
        $order_data->type_user_cancel = $type_user_cancel; //Customer
        $order_data->cancel_action_date = date('Y-m-d H:i:s');
        $pv_total = $order_data->pv_total;


          $pay_type = $order_data->pay_type_id_fk;
          $add_pv_aistockist = $customer_user->pv_aistockist - $pv_total;
          $customer_user->pv_aistockist = $add_pv_aistockist;
          $ai_stockist = DB::table('ai_stockist')
            ->select('transection_code')
            ->where('ai_stockist.code_order', '=', $order_data->code_order)
            ->first();

          $update_ai_stockist->user_id_fk = $order_data->user_id_fk;
          $update_ai_stockist->customer_id = $order_data->customers_id_fk;
          $update_ai_stockist->to_customer_id = $order_data->customers_id_fk;
          $update_ai_stockist->transection_code = $ai_stockist->transection_code;
          // $update_ai_stockist->set_transection_code = date('ym');
          $update_ai_stockist->pv = $order_data->pv_total;
          $update_ai_stockist->status = 'cancel';
          $update_ai_stockist->type_id = 4;
          $update_ai_stockist->code_order = $order_data->code_order;
          $update_ai_stockist->order_id_fk = $order_data->id;
          $update_ai_stockist->order_channel = 'VIP';
          $update_ai_stockist->status_transfer = 2;

          $update_ai_stockist->status_add_remove = 'remove';

          //$update_ai_stockist->status_add_remove = 'add';
          $update_ai_stockist->banlance = $add_pv_aistockist;
          $update_ai_stockist->pv_aistockist = $add_pv_aistockist;




        $update_products_lis = DB::table('db_order_products_list')
          ->where('frontstore_id_fk', $order_id)
          ->update([
            'approve_status' => 2,
            'approve_date' => date('Y-m-d H:i:s'),
          ]);

        $update_products_list_giveaway = DB::table('db_order_products_list_giveaway')
          ->where('order_id_fk', $order_id)
          ->update([
            'approve_status' => 2,
            'approve_date' => date('Y-m-d H:i:s'),
          ]);

        $update_ai_stockist->save();
        $order_data->save();
        $customer_user->save();


        DB::commit();
        $resule = ['status' => 'success', 'message' => 'success'];
        return $resule;
      } else {

        DB::rollback();
        $resule = ['status' => 'success', 'message' => 'fail'];
        return $resule;
      }
    } catch (Exception $e) {
      DB::rollback();
      $resule = ['status' => 'success', 'message' => $e];
      return $resule;

    }
  }
}
