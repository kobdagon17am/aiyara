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
    //ไม่ได้ใช้
      DB::BeginTransaction();
      $price = str_replace(',', '', $rs->price);
      $business_location_id = Auth::guard('c_user')->user()->business_location_id;

      if(empty($business_location_id)){
        $business_location_id = 1;
      }
      $customer_id = Auth::guard('c_user')->user()->id;
      $code_order = RunNumberPayment::run_number_aicash($business_location_id);

      try {

        $file_slip = $rs->file_slip;
        if (isset($file_slip)) {
            $url = 'local/public/files_slip/' . date('Ym');

            $f_name = date('YmdHis') . '_' . $customer_id . '.' . $file_slip->getClientOriginalExtension();
            if ($file_slip->move($url, $f_name)) {
                DB::table('payment_slip')
                    ->insert(['customer_id' => $customer_id, 'url' => $url, 'file' => $f_name, 'order_id' => $id,'code_order' =>$code_order, 'type' => 'ai-cash']);
            }

            $url = $url.'/'.$f_name;

        }


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
                'file_slip' =>$url,
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
      dd('Soon');
    }

}
