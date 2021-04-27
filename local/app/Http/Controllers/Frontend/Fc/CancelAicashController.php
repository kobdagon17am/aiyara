<?php

namespace App\Http\Controllers\Frontend\Fc;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CancelAicashController extends Controller
{

    public static function cancel_aicash($cancel_aicash_id,$customer_or_admin,$type_user_cancel){


      $aicash = DB::table('db_add_ai_cash')
      ->select('*')
      ->where('id','=',$cancel_aicash_id)
      ->first();



        DB::BeginTransaction();
        if($aicash->deleted_status == 1){
          $resule = ['status' => 'fail', 'message' => 'Oder Aicash Status Delete'];
          DB::rollback();
          return $resule;

        }

        if($aicash->order_status_id_fk == 8){
          $resule = ['status' => 'fail', 'message' => 'Oder Aicash Status Cancel'];
          DB::rollback();
          return $resule;

        }

        if($aicash->upto_customer_status == 0){
          $resule = ['status' => 'fail', 'message' => 'Oder Aicash นี้ยังไม่ถูก Add Ai-Cash ให้ User ไม่สามารถยกเลิกบิลได้'];
          DB::rollback();
          return $resule;
        }


        $customer_user = DB::table('customers') //อัพ Pv ของตัวเอง
        ->select('*')
        ->where('id', '=',  $aicash->customer_id_fk)
        ->first();

        if($customer_user->ai_cash < $aicash->aicash_amt){
            $resule = ['status' => 'fail', 'message' => 'ยอด Ai-Cash ไม่พอสำหรับการยกเลิก'];
            DB::rollback();
          return $resule;
        }



        try {

          $update_aicash = $customer_user->ai_cash - $aicash->aicash_amt;

          $update_ai_cash = DB::table('customers')
              ->where('id',  $aicash->customer_id_fk)
              ->update(['ai_cash' => $update_aicash]);

          $update = DB::table('db_add_ai_cash') //update บิล
          ->where('id', $cancel_aicash_id)
          ->update([
              'cancel_by_user_id_fk' => $customer_or_admin,
              'approve_status' => 4,
              'order_status_id_fk' => 8,
              'type_user' => $type_user_cancel, //customer || Admin
          ]);

          $resule = ['status' => 'success', 'message' => 'Cancle Ai-Cash Success'];
            if ($resule['status'] == 'success') {
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
