<?php

namespace App\Http\Controllers\Frontend\Fc;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CancelAicashController extends Controller
{
  //\App\Http\Controllers\Frontend\Fc\CancelAicashController::cancel_aicash($cancel_aicash_id,$customer_or_admin,$type_user_cancel);
    public static function cancel_aicash($cancel_aicash_id,$customer_or_admin,$type_user_cancel){
      DB::BeginTransaction();

      $aicash = DB::table('db_add_ai_cash')
      ->select('*')
      ->where('id','=',$cancel_aicash_id)
      ->first();


        if($aicash->deleted_status == 1){
          $resule = ['status' => 'fail', 'message' => 'Oder Aicash Status Delete'];
          return $resule;

        }

        if($aicash->order_status_id_fk == 8){
          $resule = ['status' => 'fail', 'message' => 'Oder Aicash Status Cancel'];
          return $resule;

        }

        if($aicash->upto_customer_status == 0){

          $resule = ['status' => 'fail', 'message' => 'Oder Aicash นี้ยังไม่ถูก Add Ai-Cash ให้ User ไม่สามารถยกเลิกบิลได้'];
          return $resule;
        }


        $customer_user = DB::table('customers') //อัพ Pv ของตัวเอง
        ->select('*')
        ->where('id', '=',  $aicash->customer_id_fk)
        ->first();

        if($customer_user->ai_cash < $aicash->aicash_amt){
            $resule = ['status' => 'fail', 'message' => 'ยอด Ai-Cash ไม่พอสำหรับการยกเลิก'];

          return $resule;
        }

        try {
          $update_aicash = $customer_user->ai_cash - $aicash->aicash_amt;
          $update_ai_cash = DB::table('customers')
              ->where('id',$customer_user->id)
              ->update(['ai_cash' => $update_aicash]);

          $update = DB::table('db_add_ai_cash') //update บิล
          ->where('id', $cancel_aicash_id)
          ->update([
              'cancel_by_user_id_fk' => $customer_or_admin,
              'approve_status' => 5,
              'order_status_id_fk' => 8,
              'type_user' => $type_user_cancel, //customer || Admin
          ]);
          // DB::rollback();
          // $resule = ['status' => 'success', 'message' => 'Cancle Ai-Cash Success'];
          // return $resule;

              $inseart_aicash_movement = DB::table('db_movement_ai_cash')->insert([
                'customer_id_fk' => $customer_user->id,
                //'order_id_fk' =>$order_id,
                'add_ai_cash_id_fk' =>  $cancel_aicash_id,//กรณีเติม Aicash
                'business_location_id_fk' => $customer_user->business_location_id,
                'price_total' => $aicash->aicash_amt,
                'aicash_old' => $customer_user->ai_cash,
                'aicash_price' => $aicash->aicash_amt,
                'aicash_banlance' => $update_aicash,
                'order_code' => $aicash->code_order,
                'order_type_id_fk' => $aicash->order_type_id_fk,
                'pay_type_id_fk' =>$aicash->pay_type_id_fk,
                'type' => 'cancel',
                'detail'=>'Cancel Oder',
            ]);


          $resule = ['status' => 'success', 'message' => 'Cancle Ai-Cash Success'];
            if ($resule['status'] == 'success') {
                DB::commit();
                return $resule;
            } else {
                DB::rollback();
                return $resule;
            }
        } catch (\Exception $e) {
          $resule = ['status' => 'fail', 'message' => $e['message']];
          return $resule;
        }
    }

    public static function cancel_aicash_backend($order_id, $customer_or_admin, $type_user_cancel, $action_type)
    {
        DB::BeginTransaction();
        $order_data = DB::table('db_orders')
            ->where('id', '=', $order_id)
            ->first();
        if (empty($order_data)) {
            $resule = ['status' => 'fail', 'message' => 'ไม่มีบิลนี้อยู่ในระบบ'];
            return $resule;
        }

        try {
            $update_order = DB::table('db_orders') //update บิล
                ->where('id', $order_id)
                ->update([
                    'cancel_by_user_id_fk' => $customer_or_admin,
                    'order_status_id_fk' => 8, //Status  8 = Cancel
                    'approve_status' => 5, //status = Cancel
                    'type_user_cancel' => $type_user_cancel, //Customer
                    'cancel_action_date' => date('Y-m-d H:i:s'),
                ]);

                $resule = ['status' => 'success', 'message' => 'Cancle Order Ai-Cash Success'];

            if ($resule['status'] == 'success') {
                DB::commit();
                return $resule;
            } else {

                DB::rollback();
                return $resule;
            }
        } catch (Exception $e) {
            DB::rollback();
            $resule = ['status' => 'fail', 'message' => $e['message']];
            return $resule;
        }
    }

}
