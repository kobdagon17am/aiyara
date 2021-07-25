<?php
namespace App\Models\Frontend;

use App\Models\Frontend\RunNumberPayment;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

class PaymentAiCash extends Model
{
    public static function payment_uploadfile($rs)
    {
        DB::BeginTransaction();
        $price = str_replace(',', '', $rs->price);
        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        $customer_id = Auth::guard('c_user')->user()->id;
        $code_order = RunNumberPayment::run_number_aicash($business_location_id);

        try {

            $id = DB::table('db_add_ai_cash')->insertGetId(
              [
                  'customer_id_fk' => $customer_id,
                  'business_location_id_fk' => $business_location_id,
                  'aicash_amt' => $price,
                  'action_user' => $customer_id,
                  'order_type_id_fk' => 7,
                  'pay_type_id' => $rs->pay_type,
                  'code_order' => $code_order,
                  'date_setting_code' => date('ym'),
                  'transfer_price' => $price,
                  'credit_price' => 0,
                  'total_amt' => $price,
                  'approve_status' => 0,
                  'order_status_id_fk' => 2,
                  'upto_customer_status' => 0,

                  'note' => 'Add Ai-Cash',
              ]
          );

            $file_slip = $rs->file_slip;
            if (isset($file_slip)) {
                $url = 'local/public/files_slip/' . date('Ym');

                $f_name = date('YmdHis') . '_' . $customer_id . '.' . $file_slip->getClientOriginalExtension();
                if ($file_slip->move($url, $f_name)) {
                    DB::table('payment_slip')
                        ->insert(['customer_id' => $customer_id, 'url' => $url, 'file' => $f_name, 'order_id' => $id, 'type' => 'ai-cash']);
                }
            }

            $resule = ['status' => 'success', 'message' => 'Add Ai-Cash Success'];
            DB::commit();
            return $resule;
        } catch (Exception $e) {
            DB::rollback();
            $resule = ['status' => 'fail', 'message' => $e];
            return $resule;
        }
    }

    public static function payment_not_uploadfile($rs)
    {
        DB::BeginTransaction();

        $price = str_replace(',', '', $rs->price);
        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        $customer_id = Auth::guard('c_user')->user()->id;
        $code_order = RunNumberPayment::run_number_aicash($business_location_id);

        try {
            $id = DB::table('db_add_ai_cash')->insertGetId(
                [
                    'customer_id_fk' => $customer_id,
                    'business_location_id_fk' => $business_location_id,
                    'aicash_amt' => $price,
                    'action_user' => $customer_id,
                    'order_type_id_fk' => 7,
                    'pay_type_id' => $rs->pay_type,
                    'code_order' => $code_order,
                    'date_setting_code' => date('ym'),
                    'transfer_price' => $price,
                    'credit_price' => 0,
                    'total_amt' => $price,
                    'approve_status' => 0,
                    'order_status_id_fk' => 1,
                    'upto_customer_status' => 0,
                    'note' => 'Add Ai-Cash',
                ]
            );


            $resule = ['status' => 'success', 'message' => 'Add Ai-Cash Success'];
            DB::commit();
            return $resule;
        } catch (Exception $e) {
            DB::rollback();
            $resule = ['status' => 'fail', 'message' => $e];
            return $resule;
        }
    }

    public static function credit_card($rs)
    {

        DB::BeginTransaction();
        $price = str_replace(',','', $rs->price);

        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        $customer_id = Auth::guard('c_user')->user()->id;
        $code_order = RunNumberPayment::run_number_aicash($business_location_id);

        try {

            $customer_data = DB::table('customers')
                ->select('ai_cash')
                ->where('id', '=', $customer_id, )
                ->first();
            $banlance = $customer_data->ai_cash + $price;

            $strtotime_date_now_30 = strtotime("+30 minutes");
                $strtotime_date_now_23 = strtotime(date('Y-m-d 23:00:00'));

                if($strtotime_date_now_30 > $strtotime_date_now_23 ){
                  //$x= 'มากกว่า : '.date('Y-m-d H:i:s',$strtotime_date_now_30).'||'.date('Y-m-d H:i:s',$strtotime_date_now_23);
                  $cancel_expiry_date = date('Y-m-d H:i:s',$strtotime_date_now_23);
                }else{
                  //$x= 'น้อยกว่า : '.date('Y-m-d H:i:s',$strtotime_date_now_30).'||'.date('Y-m-d H:i:s',$strtotime_date_now_23);
                  $cancel_expiry_date =date('Y-m-d H:i:s',$strtotime_date_now_30);

                }

            $id_add_ai_cash = DB::table('db_add_ai_cash')->insertGetId(
              [
                  'customer_id_fk' => $customer_id,
                  'business_location_id_fk' => $business_location_id,
                  'aicash_amt' => $price,
                  'aicash_old' =>$customer_data->ai_cash,
                  'aicash_banlance' =>$banlance,
                  'action_user' => $customer_id,
                  'pay_type_id' => $rs->pay_type,
                  'code_order' => $code_order,
                  'date_setting_code' => date('ym'),
                  'credit_price' => $price,
                  'total_amt' => $price,
                  'approve_status' => 1,
                  'order_type_id_fk' => 7,
                  'order_status_id_fk' => 7,
                  'upto_customer_status' => 1,
                  'cancel_expiry_date'=>$cancel_expiry_date,
                  'note' => 'Add Ai-Cash',
              ]
          );

          $inseart_aicash_movement = DB::table('db_movement_ai_cash')->insert([
            'customer_id_fk' => $customer_id,
            //'order_id_fk' =>$order_id,
            'add_ai_cash_id_fk' => $id_add_ai_cash, //กรณีเติม Aicash
            'business_location_id_fk' => $business_location_id,
            'price_total' => $price,
            'aicash_old' => $customer_data->ai_cash,
            'aicash_price' => $price,
            'aicash_banlance' => $banlance,
            'order_code' =>  $code_order,
            'order_type_id_fk' => 7,
            'pay_type_id_fk' => $rs->pay_type,
            'type' => 'add_aicash',
            'detail'=>'Add Ai-Cash',
        ]);

            $customers_update = DB::table('customers')
                ->where('id', $customer_id)
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
