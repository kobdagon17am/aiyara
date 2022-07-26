<?php

namespace App\Http\Controllers\Frontend\Fc;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AicashConfirmeController extends Controller
{
  // \App\Http\Controllers\Frontend\Fc\AicashConfirmeController\aicash_confirme($aicash_id,$customer_or_admin_id,$type_user_confirme,$comment='',$pay_type_id);
    public static function aicash_confirme($aicash_id,$customer_or_admin_id,$type_user_confirme,$comment='',$pay_type_id)//$type_user_confirme = "'customer','admin'"
    {

      DB::BeginTransaction();
      $db_add_ai_cash = DB::table('db_add_ai_cash')
              ->where('id', '=', $aicash_id)
              ->first();



      if(empty($db_add_ai_cash)){
        $resule = ['status' => 'fail', 'message' => 'ไม่พบบิลการเติม Ai-Cash นี้'];
        return $resule;
      }

      if($db_add_ai_cash->upto_customer_status == 1){
        $resule = ['status' => 'fail', 'message' => 'บิลนี้ถูกเพิ่ม Ai-Cash ไปแล้ว'];
        return $resule;
      }

      try {

          $customer_data = DB::table('customers')
              ->where('id', '=',$db_add_ai_cash->customer_id_fk)
              ->first();

          $banlance = $customer_data->ai_cash +  $db_add_ai_cash->total_amt;

          $strtotime_date_now_30 = strtotime("+30 minutes");
              $strtotime_date_now_23 = strtotime(date('Y-m-d 23:00:00'));

              if($strtotime_date_now_30 > $strtotime_date_now_23 ){
                //$x= 'มากกว่า : '.date('Y-m-d H:i:s',$strtotime_date_now_30).'||'.date('Y-m-d H:i:s',$strtotime_date_now_23);
                $cancel_expiry_date = date('Y-m-d H:i:s',$strtotime_date_now_23);
              }else{
                //$x= 'น้อยกว่า : '.date('Y-m-d H:i:s',$strtotime_date_now_30).'||'.date('Y-m-d H:i:s',$strtotime_date_now_23);
                $cancel_expiry_date =date('Y-m-d H:i:s',$strtotime_date_now_30);

              }

              $id_add_ai_cash = DB::table('db_add_ai_cash')
              ->where('id', $aicash_id)
              ->update(  [
                'aicash_old' =>$customer_data->ai_cash,
                'aicash_banlance' =>$banlance,
                // 'action_user' => $customer_or_admin_id,
                'date_setting_code' => date('ym'),
                'approve_status' => 2,
                'order_type_id_fk' => 7,
                'order_status_id_fk' => 7,
                'upto_customer_status' => 1,
                'cancel_expiry_date'=>$cancel_expiry_date,
                //  'note' => $comment,
                'type_user' => $type_user_confirme,
            ]);


        $inseart_aicash_movement = DB::table('db_movement_ai_cash')->insert([
          'customer_id_fk' => $db_add_ai_cash->customer_id_fk,
          //'order_id_fk' =>$order_id,
          'add_ai_cash_id_fk' => $aicash_id, //กรณีเติม Aicash
          'business_location_id_fk' => $customer_data->business_location_id,
          'price_total' => $db_add_ai_cash->total_amt,
          'aicash_old' => $customer_data->ai_cash,
          'aicash_price' => $db_add_ai_cash->total_amt,
          'aicash_banlance' => $banlance,
          'order_code' =>  $db_add_ai_cash->code_order,
          'order_type_id_fk' => 7,

          'pay_type_id_fk' => $pay_type_id,
          'type' => 'add_aicash',
          // 'detail'=>'Add Ai-Cash ',
      ]);

          $customers_update = DB::table('customers')
              ->where('id',$db_add_ai_cash->customer_id_fk)
              ->update(['ai_cash' => $banlance]);

          $resule = ['status' => 'success', 'message' => 'Add Ai-Cash Success'];
          DB::commit();
          return $resule;
      } catch (Exception $e) {
          DB::rollback();
          $resule = ['status' => 'fail', 'message' => $e];
          return $resule;
      }
  }
}
