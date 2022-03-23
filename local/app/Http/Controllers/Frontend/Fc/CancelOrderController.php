<?php

namespace App\Http\Controllers\Frontend\Fc;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\Fc\RunPvController;
use Illuminate\Support\Facades\DB;
use App\Models\Db_Ai_stockist;
use App\Models\Frontend\Customer;
use App\Models\Frontend\Order;

class CancelOrderController extends Controller
{

  public static function cancel_order($order_id, $customer_or_admin, $type_user_cancel, $action_type)
  {


    DB::BeginTransaction();

    $order_data  = Order::find($order_id);



    if ($order_data->status_payment_sent_other == 1) {
      $customer_id = $order_data->customers_sent_id_fk;
    } else {
      $customer_id = $order_data->customers_id_fk;
    }
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

        if ($pv_total > 0) {



          $pay_type = $order_data->pay_type_id_fk;
          // 1 เงินโอน
          // 2 บัตรเครดิต
          // 3 Ai-Cash
          // 4  Ai Voucher
          // 5 เงินสด
          // 6 เงินสด + Ai-Cash
          // 7 เครดิต + เงินสด
          // 8 เครดิต + เงินโอน
          // 9 เครดิต + Ai-Cash
          // 10 เงินโอน + เงินสด
          // 11 เงินโอน + Ai-Cash
          // 12  Ai Voucher + เงินโอน
          // 13  Ai Voucher + บัตรเครดิต
          // 14  Ai Voucher + Ai-Cash

          if ($pay_type == 3 || $pay_type == 6 || $pay_type == 9 || $pay_type == 11 || $pay_type == 14) { //Aicash

            $customer_user_aicash = DB::table('customers') //อัพ Pv ของตัวเอง
              ->select('*')
              ->where('id', '=', $order_data->member_id_aicash)
              ->first();

            if (empty($customer_user_aicash)) {
              DB::rollback();
              $log = [
                'customer_user_name' => $customer_user->user_name,
                'admin_user_name' => '',
                'code_order' => $order_data->code_order,
                'error_detail' => 'ไม่มี Customers_id ให้ตัด Aicash =' . $type_id,
                'function' => 'App\Http\Controllers\Frontend\Fc\CancelOrderController::cancel_order()',
                'type' => '', //'admin','customer'
                'order_type' => $type_id,
              ];

              $rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);


              $resule = ['status' => 'fail', 'message' => 'ไม่มี Customers_id ให้ตัด Aicash'];
              DB::commit();
              return $resule;
            }

            $update_icash = $customer_user_aicash->ai_cash + $order_data->aicash_price;
            //$customer_user->ai_cash;//Ai-Cash เดิม
            //$update_icash; //Bicash_banlance

            $order_data->aicash_old = $customer_user_aicash->ai_cash;
            $order_data->aicash_banlance =  $update_icash;

            $inseart_aicash_movement = DB::table('db_movement_ai_cash')->insert([
              'customer_id_fk' => $customer_user_aicash->id,
              'order_id_fk' => $order_id,
              //'add_ai_cash_id_fk' => '';//กรณีเติม Aicash
              'business_location_id_fk' => $order_data->business_location_id_fk,
              'price_total' => $order_data->total_price,
              'aicash_old' => $customer_user_aicash->ai_cash,
              'aicash_price' => $order_data->aicash_price,
              'aicash_banlance' => $update_icash,
              'order_code' => $order_data->code_order,
              'order_type_id_fk' => $order_data->purchase_type_id_fk,
              'pay_type_id_fk' => $order_data->pay_type_id_fk,
              'type' => 'cancel',
              'detail' => 'Cancel Oder',
            ]);

            $update_aicash = DB::table('customers')
              ->where('id', $customer_user_aicash->id)
              ->update(['ai_cash' => $update_icash]);
          }

          if ($type_id == 1) {

            $resule = RunPvController::Cancle_pv($customer_user->user_name, $pv_total, $type_id, $order_data->code_order);
          } elseif ($type_id == 2) { //รักษาคุณสมบัติรายเดือน

            $rs =  \App\Http\Controllers\Frontend\Fc\Cancel_mt_tv::cancel_mt($customer_user->id, $pv_total);

            $customer_user->pv_mt = $rs['pv'];
            if ($rs['mt_active'] > 0) {
              $customer_user->pv_mt = $rs['pv'];
              $m = $rs['mt_active'];

              $mt_active = strtotime("-$m Month", strtotime($customer_user->pv_mt_active));

              $mt_active = date('Y-m-1', $mt_active); //วันที่ mt_active
              $customer_user->pv_mt_active = $mt_active;
            }

            // $update_tv = DB::table('customers')
            //   ->where('id', $customer_user->id)
            //   ->update(['pv_mt' => $order_data->pv_mt_old, 'pv_mt_active' => $order_data->active_mt_old_date, 'status_pv_mt' => $order_data->status_pv_mt_old]);

            $resule = RunPvController::Cancle_pv($customer_user->user_name, $pv_total, $type_id, $order_data->code_order);
          } elseif ($type_id == 3) { //รักษาคุณสมบัตรการท่องเที่ยว

            $rs =  \App\Http\Controllers\Frontend\Fc\Cancel_mt_tv::cancel_tv($customer_user->id, $pv_total);

            $customer_user->pv_tv = $rs['pv'];
            if ($rs['tv_active'] > 0) {

              $customer_user->pv_tv = $rs['pv'];
              $m = $rs['tv_active'];

              $tv_active = strtotime("-$m Month", strtotime($customer_user->pv_tv_active));

              $tv_active = date('Y-m-1', $tv_active); //วันที่ mt_active
              $customer_user->pv_tv_active = $tv_active;
            }


            // $update_tv = DB::table('customers')
            //   ->where('id', $customer_user->id)
            //   ->update(['pv_tv' => $order_data->pv_tv_old, 'pv_tv_active' => $order_data->active_tv_old_date]);

            $resule = RunPvController::Cancle_pv($customer_user->user_name, $pv_total, $type_id, $order_data->code_order);
          } elseif ($type_id == 4) { //เติม Aistockis
            $add_pv_aistockist = $customer_user->pv_aistockist - $pv_total;

            // $update_pv = DB::table('customers')
            //   ->where('id', $customer_user->id)
            //   ->update(['pv_aistockist' => $add_pv_aistockist]);

            $customer_user->pv_aistockist = $add_pv_aistockist;

            $ai_stockist = DB::table('ai_stockist')
              ->select('transection_code')
              ->where('ai_stockist.code_order', '=', $order_data->code_order)
              ->first();


            $update_ai_stockist->customer_id = $order_data->customers_id_fk;
            $update_ai_stockist->to_customer_id = $order_data->customers_id_fk;
            $update_ai_stockist->transection_code = $ai_stockist->transection_code;
            // $update_ai_stockist->set_transection_code = date('ym');
            $update_ai_stockist->pv = $order_data->pv_total;
            $update_ai_stockist->status = 'cancel';
            $update_ai_stockist->type_id = 4;
            $update_ai_stockist->code_order = $order_data->code_order;
            $update_ai_stockist->order_id_fk = $order_data->id;
            $update_ai_stockist->order_channel = 'MEMBER';
            $update_ai_stockist->status_transfer = 2;

            $update_ai_stockist->status_add_remove = 'remove';

            //$update_ai_stockist->status_add_remove = 'add';
            $update_ai_stockist->banlance = $add_pv_aistockist;
            $update_ai_stockist->pv_aistockist = $add_pv_aistockist;

            if ($order_data->pv_total >= 10000) {
              $cancel_status_aistockist = DB::table('db_orders')
                ->where('customers_id_fk', '=',  $customer_user->id)
                ->where('purchase_type_id_fk', '=', 4)
                ->where('pv_total', '>=', 10000)
                ->wherein('approve_status', [2, 4, 9])
                ->where('id', '!=', $order_id)
                ->count();

              //dd($cancel_status_aistockist);

              if ($cancel_status_aistockist == 0) {
                $customer_user->aistockist_status = 0;
                $customer_user->aistockist_date = null;
              }
            }

            $resule = RunPvController::Cancle_pv($customer_user->user_name, $pv_total, $type_id, $order_data->code_order);
          } elseif ($type_id == 6) { // Course
            $update_couse = DB::table('course_event_regis')
              ->where('order_id_fk', $order_id)
              ->update(['status_register' => '3']);

            $resule = RunPvController::Cancle_pv($customer_user->user_name, $pv_total, $type_id, $order_data->code_order);
          } else {
            DB::rollback();
            $log = [
              'customer_user_name' => $customer_user->user_name,
              'admin_user_name' => '',
              'code_order' => $order_data->code_order,
              'error_detail' => 'Type Id is null Type=' . $type_id,
              'function' => 'App\Http\Controllers\Frontend\Fc\CancelOrderController::cancel_order()',
              'type' => '', //'admin','customer'
              'order_type' => $type_id,
            ];

            $rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);


            $resule = ['status' => 'fail', 'message' => 'Type Id is null'];
            DB::commit();
            return $resule;
          }
        } else {
          $resule = ['status' => 'success', 'message' => 'pv ของบิลนี้มีค่าเป็น 0 ปรับสถานะบิลเป็น Cancel อย่างเดียว'];
        }
      } else {
        DB::rollback();
        $log = [
          'customer_user_name' => $customer_user->user_name,
          'admin_user_name' => '',
          'code_order' => $order_data->code_order,
          'error_detail' => 'บิลนี้ไม่สามารถยกเลิกได้เนื่องจากไม่อยู่ในสถานะที่ถูกต้อง approve_status=' . $order_data->approve_status,
          'function' => 'App\Http\Controllers\Frontend\Fc\CancelOrderController::cancel_order()',
          'type' => '', //'admin','customer'
          'order_type' => $type_id,
        ];

        $rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);
        $resule = ['status' => 'fail', 'message' => 'บิลนี้ไม่สามารถยกเลิกได้เนื่องจากไม่อยู่ในสถานะที่ถูกต้อง'];
        DB::commit();
        return $resule;
      }
      // วุฒิเพิ่มมาแก้ error 
      if(!isset($resule)){
        $resule = ['status' => 'success', 'message' => 'ทำรายการสำเร็จ'];
      }
      // 
      if ($resule['status'] == 'success') {

        if ($type_id == 4) {
          $update_ai_stockist->save();
        }


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



        $giv_log = DB::table('log_gift_voucher')
          ->where('code_order', '=', $order_data->code_order)
          ->get();

        if ($giv_log) { //GivtVoucher

          if ($type_id == 5) {

            foreach ($giv_log as $value) {

              $gv = \App\Helpers\Frontend::get_gitfvoucher($customer_order->user_name);

              $gv_banlance = $gv->sum_gv + $value->giftvoucher_value_use;

              $insert_log_gift_voucher = DB::table('log_gift_voucher')->insert([
                'customer_id_fk' =>  $order_data->customers_id_fk,
                'order_id_fk' => $order_id,
                'code_order' => $order_data->code_order,
                'giftvoucher_cus_code_order' => $value->giftvoucher_cus_code_order,
                'giftvoucher_cus_id_fk' => $value->giftvoucher_cus_id_fk,
                'type_action_giftvoucher' => 1, //เกิดจากกาสั่งซื้อ
                'giftvoucher_value_old' => $gv->sum_gv,
                'giftvoucher_value_use' => $value->giftvoucher_value_use,
                'giftvoucher_value_banlance' => $gv_banlance,
                'detail' => 'ยกเลิกรายการ' . $value->detail,
                'status' => 'cancle',
                'type' => 'Add',
                'status' => 'cancel',
                'action_user_id_fk' => $customer_or_admin,
                'action_type' => $action_type,
                'cancel_date' => date('Y-m-d H:i:s'),
              ]);

              $db_giftvoucher_cus = DB::table('db_giftvoucher_cus')
                ->select('giftvoucher_banlance')
                ->where('id', $value->giftvoucher_cus_id_fk)
                ->first();

              if (empty($db_giftvoucher_cus)) {
                $resule = ['status' => 'fail', 'message' => 'ข้อมูลไม่ถูกต้อง'];
                //DB::commit();
                DB::rollback();
                return $resule;
              }


              $giftvoucher_banlance = $db_giftvoucher_cus->giftvoucher_banlance + $value->giftvoucher_value_use;

              $update_giftvoucher = DB::table('db_giftvoucher_cus')
                ->where('id', $value->giftvoucher_cus_id_fk)
                ->update(['giftvoucher_banlance' => $giftvoucher_banlance]);
            }
            $resule = ['status' => 'success', 'message' => 'Cancel Oder GiftVoucher Success'];
          } else { //ได้จากการแถมสินค้า

            foreach ($giv_log as $value) {

              $gv = \App\Helpers\Frontend::get_gitfvoucher($customer_order);
              $gv_banlance = $gv->sum_gv - $value->giftvoucher_value_use;

              $insert_log_gift_voucher = DB::table('log_gift_voucher')->insert([
                'customer_id_fk' =>  $order_data->customers_id_fk,
                'order_id_fk' => $order_id,
                'code_order' => $order_data->code_order,
                'giftvoucher_cus_code_order' => $value->giftvoucher_cus_code_order,
                'giftvoucher_cus_id_fk' => $value->giftvoucher_cus_id_fk,
                'type_action_giftvoucher' => 1, //เกิดจากกาสั่งซื้อ
                'giftvoucher_value_old' => $gv->sum_gv,
                'giftvoucher_value_use' => $value->giftvoucher_value_use,
                'giftvoucher_value_banlance' => $gv_banlance,
                'detail' => 'ยกเลิกรายการ' . $value->detail,
                'status' => 'cancle',
                'type' => 'Remove',
                'status' => 'cancel',
                'action_user_id_fk' => $customer_or_admin,
                'action_type' => $action_type,
                'cancel_date' => date('Y-m-d H:i:s'),
              ]);

              $db_giftvoucher_cus = DB::table('db_giftvoucher_cus')
                ->select('giftvoucher_banlance')
                ->where('id', $value->giftvoucher_cus_id_fk)
                ->first();

              if (empty($db_giftvoucher_cus)) {
                $resule = ['status' => 'fail', 'message' => 'ข้อมูลไม่ถูกต้อง'];
                //DB::commit();
                DB::rollback();
                return $resule;
              }

              $giftvoucher_banlance = $db_giftvoucher_cus->giftvoucher_banlance - $value->giftvoucher_value_use;

              $update_giftvoucher = DB::table('db_giftvoucher_cus')
                ->where('id', $value->giftvoucher_cus_id_fk)
                ->update(['giftvoucher_banlance' => $giftvoucher_banlance]);
            }
            $resule = ['status' => 'success', 'message' => 'Cancel Oder GiftVoucher Success'];
          }
        }

        $order_data->save();
        $customer_user->save();

 
        DB::commit();
        return $resule;
      } else {

        DB::rollback();
        return $resule;
      }
    } catch (Exception $e) {
      DB::rollback();
      return $e;
    }
  }
}
