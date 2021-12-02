<?php
namespace App\Models\Frontend;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

class GiftVoucher extends Model
{

    public static function add_gift($user_name, $gv, $code, $expri_date, $admin_id)
    {

        $data_user = DB::table('customers')
            ->where('user_name', '=', $user_name)
            ->first();
        try {
            DB::BeginTransaction();
            if (empty($data_user)) {
                $resule = ['status' => 'fail', 'message' => 'Empty UserName in system'];
            } else {

                DB::table('gift_voucher')
                    ->insert(['customer_id' => $data_user->id, 'code' => $code, 'gv' => $gv, 'banlance' => $gv, 'admin_id' => $admin_id, 'expiry_date' => $expri_date]);
                $resule = ['status' => 'success', 'message' => 'Add GiftVoucher Success'];
            }

            if ($resule['status'] == 'success') {
                DB::commit();
                //DB::rollback();
                return $resule;
            } else {
                DB::rollback();
                return $resule;
            }

        } catch (Exception $e) {
            DB::rollback();
            $resule = ['status' => 'fail', 'message' => $e];
            return $resule;

        }

    }


    public static function log_gift($price_total, $customer_id, $code_order,$gv_price)
    {
        //type_action_giftvoucher = 0 เกิดจากการซื้อสินค้าหรือชำระสินค้า 1 เกิดจากหลังบ้าน Add ให้
        //$type = Add เพิ่มแต้ม หรือ ลดแต้ม Remove
        // $admin_id = 99;
        // $price_total = 900;
        // $order_id = 99;



        $id = $customer_id;

        $data_user = DB::table('customers')
            ->where('id', '=', $customer_id)
            ->first();

            if (empty($data_user)) {
              $resule = ['status' => 'fail', 'message' => 'Empty UserName in system'];
              return  $resule;
            }


            $user_name = $data_user->user_name;
            $gv_customer = \App\Helpers\Frontend::get_gitfvoucher($user_name);
            $gv_sum = $gv_customer->sum_gv;

            if($gv_price>$gv_sum){
              $resule = ['status' => 'fail', 'message' => 'Giftvoucher ปัจจุบันมีไม่พอสำหรับการใช้งาน'];
              return  $resule;
            }




        $data_gv = DB::table('db_giftvoucher_cus')
            ->select('db_giftvoucher_cus.*', 'db_giftvoucher_code.descriptions')
            ->leftjoin('db_giftvoucher_code', 'db_giftvoucher_code.id', '=', 'db_giftvoucher_cus.giftvoucher_code_id_fk')
            ->where('db_giftvoucher_cus.giftvoucher_banlance', '>', 0)
            ->where('db_giftvoucher_cus.customer_username', '=', $user_name)
            ->where('db_giftvoucher_cus.pro_sdate', '<=', date('Y-m-d'))
            ->where('db_giftvoucher_cus.pro_edate', '>=', date('Y-m-d'))
            ->whereraw('(db_giftvoucher_cus.pro_status = 1 || db_giftvoucher_cus.pro_status = 2)')
            ->get();

        try {
            DB::BeginTransaction();
            $price_log = $price_total-$gv_price;

            if ($price_log >= $gv_sum) {


                foreach ($data_gv as $value) {

                  $gv_customer = \App\Helpers\Frontend::get_gitfvoucher($user_name);
                  $gv_sum = $gv_customer->sum_gv;

                  $banlance = $gv_customer->sum_gv - $gv_price;

                  if ($banlance < 0) {
                      $banlance = 0;
                  }

                  $insert_log_gift_voucher = DB::table('log_gift_voucher')->insert([
                      'customer_id_fk' => $id,
                      'giftvoucher_cus_id_fk' => $value->id,
                      //'order_id_fk'=>$order_id,
                      'giftvoucher_value_old' => $gv_sum,
                      'giftvoucher_value_use' => $price_log,
                      'giftvoucher_value_banlance' => $banlance,
                      'code_order' => $code_order,
                      'detail' => 'ซื้อสินค้าด้วย Ai Voucher',
                      'status' => 'success',
                      'type' => 'Remove',
                      'type_action_giftvoucher' => 0,
                  ]);
                  $price_log =  $banlance;

                    $update_giftvoucher = DB::table('db_giftvoucher_cus') //update บิล
                        ->where('id', $value->id)
                        ->update(['giftvoucher_banlance' => $banlance, 'pro_status' => 2]);


                }

                DB::commit();
                $resule = ['status' => 'success', 'message' => 'Use Ai Voucher Success'];
                return $resule;


            } else {

                foreach ($data_gv as $value) {

                  $gv_customer = \App\Helpers\Frontend::get_gitfvoucher($user_name);
                  $gv_sum = $gv_customer->sum_gv;

                  $banlance = $gv_customer->sum_gv - $gv_price;

                  $gv_rs = $price_log - $value->giftvoucher_banlance;
                  if ($banlance < 0) {
                      $banlance = 0;
                  }

                    if ($gv_rs > 0) {

                      $insert_log_gift_voucher = DB::table('log_gift_voucher')->insert([
                        'customer_id_fk' => $id,
                        'giftvoucher_cus_id_fk' => $value->id,
                        //'order_id_fk'=>$order_id,
                        'giftvoucher_value_old' => $gv_sum,
                        'giftvoucher_value_use' => $value->giftvoucher_banlance,
                        'giftvoucher_value_banlance' => $banlance,
                        'code_order' => $code_order,
                        'detail' => 'ซื้อสินค้าด้วย Ai Voucher',
                        'status' => 'success',
                        'type' => 'Remove',
                        'type_action_giftvoucher' => 0,
                    ]);

                        $update_giftvoucher = DB::table('db_giftvoucher_cus') //update บิล
                            ->where('id', $value->id)
                            ->update(['giftvoucher_banlance' => $banlance, 'pro_status' => 2]);

                        $price_total = $gv_rs;

                    } else {

                      $insert_log_gift_voucher = DB::table('log_gift_voucher')->insert([
                        'customer_id_fk' => $id,
                        'giftvoucher_cus_id_fk' => $value->id,
                        //'order_id_fk'=>$order_id,
                        'giftvoucher_value_old' => $gv_sum,
                        'giftvoucher_value_use' => $price_total,
                        'giftvoucher_value_banlance' => $banlance,
                        'code_order' => $code_order,
                        'detail' => 'ซื้อสินค้าด้วย Ai Voucher',
                        'status' => 'success',
                        'type' => 'Remove',
                        'type_action_giftvoucher' => 0,
                    ]);

                        $update_giftvoucher = DB::table('db_giftvoucher_cus') //update บิล
                            ->where('id', $value->id)
                            ->update(['giftvoucher_banlance' => abs($gv_rs), 'pro_status' => 2]);
                            DB::commit();
                            $resule = ['status' => 'success', 'message' => 'Use GiftVoucher Success'];
                            return $resule;
                    }

                }


            }

        } catch (Exception $e) {
            DB::rollback();
            $resule = ['status' => 'fail', 'message' => $e];
            return $resule;
        }
    }

}
