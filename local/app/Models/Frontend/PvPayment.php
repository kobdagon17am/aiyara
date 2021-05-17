<?php
namespace App\Models\Frontend;

use App\Models\Frontend\RunNumberPayment;
use DB;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Frontend\Customer;
class PvPayment extends Model
{

    public static function PvPayment_type_confirme($order_id,$admin_id,$distribution_channel,$action_type)
    {


      DB::BeginTransaction();

        $order_data = DB::table('db_orders')
            ->where('id', '=', $order_id)
            ->first();

        if (empty($order_data)) {
            $resule = ['status' => 'fail', 'message' => 'ไม่มีบิลนี้อยู่ในระบบ'];
            return $resule;
        }

        $pv = $order_data->pv_total;
        if ($order_data->status_payment_sent_other == 1) {
            $customer_id = $order_data->address_sent_id_fk;
        } else {
            $customer_id = $order_data->customers_id_fk;
        }

        $customer_update = Customer::find($customer_id);

        $type_id = $order_data->purchase_type_id_fk;
        $business_location_id = $order_data->business_location_id_fk;

        if (empty($order_id) || empty($admin_id)) {
            $resule = ['status' => 'fail', 'message' => 'Data is Null'];
            DB::rollback();
            return $resule;

        } else {

            try {

                if ($order_data->delivery_location_frontend == 'sent_office') {
                    $orderstatus_id = 4;
                    # code...
                } else {
                    $orderstatus_id = 5;

                }

                if($order_data->pay_type_id_fk == 3 || $order_data->pay_type_id_fk  == 6 || $order_data->pay_type_id_fk == 9
                 || $order_data->pay_type_id_fk ==  11 || $order_data->pay_type_id_fk ==  14){//Aicash


                  $check_aicash = DB::table('customers') //อัพ Pv ของตัวเอง
                  ->select('ai_cash')
                  ->where('id', '=', $customer_id)
                  ->first();

                  $update_icash =  $check_aicash->ai_cash - $order_data->aicash_price;
                  //$check_aicash->ai_cash;//Ai-Cash เดิม
                  //$update_icash; //Bicash_banlance
                  if($update_icash >= 0){

                    $update_aicash = DB::table('db_orders') //update บิล
                    ->where('id', $order_id)
                    ->update(['aicash_old'=>$check_aicash->ai_cash,'aicash_banlance' => $update_icash]);

                    if ($type_id == 6) { //course
                      $movement_type = 'buy_course';
                      $movement_detail = 'Buy Course';
                    }else{
                      $movement_type = 'buy_product';
                      $movement_detail = 'Buy Product';

                    }

                    $inseart_aicash_movement = DB::table('db_movement_ai_cash')->insert([
                      'customer_id_fk' => $customer_id,
                      'order_id_fk' =>$order_id,
                      //'add_ai_cash_id_fk' => '';//กรณีเติม Aicash
                      'business_location_id_fk' => $order_data->business_location_id_fk,
                      'price_total' => $order_data->total_price,
                      'aicash_old' => $check_aicash->ai_cash,
                      'aicash_price' => $order_data->aicash_price,
                      'aicash_banlance' => $update_icash,
                      'order_code' => $order_data->code_order,
                      'order_type_id_fk' => $order_data->purchase_type_id_fk,
                      'pay_type_id_fk' =>$order_data->pay_type_id_fk,
                      'type' => $movement_type,
                      'detail'=> $movement_detail,
                  ]);

                  $customer_update->ai_cash = $update_icash;


                  }else{

                    $resule = ['status' => 'fail', 'message' => 'Ai Cash ไม่พอสำหรับการการชำระ'];
                    DB::rollback();
                    return $resule;

                  }

                }


                if ($type_id == 6) { //course

                    $orderstatus_id = 7;
                    $opc_type_order = 'course_event';

                    $run_pament_code = RunNumberPayment::run_payment_code($business_location_id,$opc_type_order);

                    if ($run_pament_code['status'] == 'fail') {
                        $resule = ['status' => 'fail', 'message' => $run_pament_code['message']];
                        DB::rollback();
                        return $resule;

                    }
                    $code_order = $run_pament_code['code_order'];

                } else { //product ทั่วไป

                    $opc_type_order = 'product';
                    $run_pament_code = RunNumberPayment::run_payment_code($business_location_id, $opc_type_order);
                    if ($run_pament_code['status'] == 'fail') {
                        $resule = ['status' => 'fail', 'message' => $run_pament_code['message']];
                        DB::rollback();
                        return $resule;
                    }
                    $code_order = $run_pament_code['code_order'];
                }

                $strtotime_date_now_30 = strtotime("+30 minutes");
                $strtotime_date_now_23 = strtotime(date('Y-m-d 23:00:00'));

                if($strtotime_date_now_30 > $strtotime_date_now_23 ){
                  //$x= 'มากกว่า : '.date('Y-m-d H:i:s',$strtotime_date_now_30).'||'.date('Y-m-d H:i:s',$strtotime_date_now_23);
                  $cancel_expiry_date = date('Y-m-d H:i:s',$strtotime_date_now_23);
                }else{
                  //$x= 'น้อยกว่า : '.date('Y-m-d H:i:s',$strtotime_date_now_30).'||'.date('Y-m-d H:i:s',$strtotime_date_now_23);
                  $cancel_expiry_date =date('Y-m-d H:i:s',$strtotime_date_now_30);

                }

                $update_order = DB::table('db_orders') //update บิล
                    ->where('id', $order_id)
                    ->update(['approver' => $admin_id,
                        'order_status_id_fk' => $orderstatus_id,
                        'cancel_expiry_date' => $cancel_expiry_date,
                        'approve_status' => 1,
                        'approve_date' => date('Y-m-d H:i:s')]);


                $product_list = DB::table('db_order_products_list')
                    ->where('frontstore_id_fk', '=', $order_id)
                    ->get();

                foreach ($product_list as $product_list_value) {

                    $update_products_lis = DB::table('db_order_products_list')
                        ->where('id', $product_list_value->id)
                        ->update(['approver' => $admin_id,
                            'approve_status' => 1,
                            'approve_date' => date('Y-m-d H:i:s')]);

                    if ($product_list_value->type_product == 'giveaway') {
                        $giveaway = DB::table('db_order_products_list_giveaway')
                            ->where('db_order_products_list_giveaway.product_list_id_fk', '=', $product_list_value->id)
                            ->get();

                        foreach ($giveaway as $giveaway_value) {
                            if ($giveaway_value->type_product == 'giveaway_product') {

                                $update_products_list_giveaway = DB::table('db_order_products_list_giveaway')
                                    ->where('id', $giveaway_value->id)
                                    ->update(['approver' => $admin_id,
                                        'approve_status' => 1,
                                        'approve_date' => date('Y-m-d H:i:s')]);

                            } else {
                                $update_products_list_giveaway = DB::table('db_order_products_list_giveaway')
                                    ->where('id', $giveaway_value->id)
                                    ->update(['approver' => $admin_id,
                                        'approve_status' => 1,
                                        'approve_date' => date('Y-m-d H:i:s')]);

                                $expiry_date = date("Y-m-d", strtotime("+1 month", strtotime(now())));

                                $inseart_gift_voucher = DB::table('gift_voucher')->insert([
                                    'order_id' => $order_id,
                                    'customer_id' => $customer_id,
                                    'gv' => $giveaway_value->gv_free,
                                    'banlance' => $giveaway_value->gv_free,
                                    'detail' => $product_list_value->product_name,
                                    'order_id' => $order_id,
                                    'admin_id' => $admin_id,
                                    'code' => $order_data->code_order,
                                    'status' => 'promotion',
                                    'code_order' => $order_data->code_order,
                                    'create_at' => now(),
                                    'expiry_date' => $expiry_date,
                                    'admin_id' => $admin_id,

                                ]);
                            }

                        }

                    }

                }
                //end check รายการของแถม

                $check_payment_code = DB::table('db_invoice_code') //เจนเลข payment order
                    ->where('order_id', '=', $order_id)
                    ->first();

                if ($check_payment_code) {
                    $update_order_payment_code = DB::table('db_invoice_code')
                        ->where('order_id', $order_id)
                        ->update(['order_payment_status' => 'Success',
                            'order_payment_code' => $code_order,
                            'business_location_id' => $business_location_id,
                            'date_setting_code' => date('ym'),
                            'type_order' => $opc_type_order]); //ลงข้อมูลบิลชำระเงิน

                } else {

                    $inseart_order_payment_code = DB::table('db_invoice_code')->insert([
                        'order_id' => $order_id,
                        'order_payment_code' => $code_order,
                        'order_payment_status' => 'Success',
                        'business_location_id' => $business_location_id,
                        'date_setting_code' => date('ym'),
                        'type_order' => $opc_type_order]); //ลงข้อมูลบิลชำระเงิน

                }
                //check รายการสินค้าแถม

                if ($type_id == 1) { //ทำคุณสมบติ
                    $data_user = DB::table('customers') //อัพ Pv ของตัวเอง
                        ->select('*')
                        ->where('id', '=', $customer_id)
                        ->first();


                    $add_pv = $data_user->pv + $pv;
                    $customer_update->pv = $add_pv;

                    $update_order_type_1 = DB::table('db_orders') //update บิล
                        ->where('id', $order_id)
                        ->update(['pv_old'=>$data_user->pv,'pv_banlance' => $add_pv,'approve_date' => date('Y-m-d H:i:s')]);

                    //ทำคุณสมบัติตัวเอง
                    $upline_type = $data_user->line_type;
                    $upline_id = $data_user->upline_id;
                    $customer_id = $upline_id;
                    $last_upline_type = $upline_type;

                } elseif ($type_id == 2) { //รักษาคุณสมบัติรายเดือน

                    $data_user = DB::table('customers') //อัพ Pv ของตัวเอง
                        ->select('*')
                        ->where('id', '=', $customer_id)
                        ->first();

                    $active_mt_old_date = $data_user->pv_mt_active;
                    $status_pv_mt_old = $data_user->status_pv_mt;

                    DB::table('db_orders') //update บิล
                    ->where('id', $order_id)
                    ->update(['pv_mt_old'=>$data_user->pv_mt,'active_mt_old_date' => $active_mt_old_date,'status_pv_mt_old'=>$status_pv_mt_old
                    ,'approve_date' => date('Y-m-d H:i:s')]);

                    $strtime_user = strtotime($data_user->pv_mt_active);
                    $strtime = strtotime(date("Y-m-d"));

                    if ($data_user->status_pv_mt == 'first') {

                        // if($strtime_user < $strtime){
                        //     //$contract_date = strtotime(date('Y-m',$strtime_user));
                        //     //$caltime = strtotime("+1 Month",$contract_date);
                        //     //$start_month = date("Y-m", $caltime);
                        //     $start_month = date("Y-m");

                        // }else{

                        //     $start_month = date("Y-m");
                        // }

                        $start_month = date("Y-m");

                        $promotion_mt = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                            ->select('*')
                            ->where('code', '=', 'pv_mt')
                            ->first();

                        $pro_mt = $promotion_mt->pv;
                        $pv_mt = $data_user->pv_mt;
                        $pv_mt_all = $pv + $pv_mt;

                        if ($pv_mt_all >= $pro_mt) {
                            //dd('หักลบค่อยอัพเดท');
                            //หักลบค่อยอัพเดท
                            $mt_mount = $pv_mt_all / $pro_mt;
                            $mt_mount = floor($mt_mount); //จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
                            $pv_mt_total = $pv_mt_all - ($mt_mount * $pro_mt); //ค่า pv ที่ต้องเอาไปอัพเดท DB

                            $mt_active = strtotime("+$mt_mount Month", strtotime($start_month));
                            $mt_active = date('Y-m-t', $mt_active); //วันที่ mt_active

                            $customer_update->pv_mt = $pv_mt_total;
                            $customer_update->pv_mt_active = $mt_active;
                            $customer_update->status_pv_mt = 'not';
                            $customer_update->date_mt_first =  date('Y-m-d h:i:s');


                            $update_order_type_2 = DB::table('db_orders') //update บิล
                                ->where('id', $order_id)
                                ->update(['pv_banlance' => $pv_mt_total, 'active_mt_date' => date('Y-m-t', strtotime($mt_active))]);

                        } else {
                            //dd('อัพเดท');

                            $customer_update->pv_mt = $pv_mt_all;
                            $customer_update->pv_mt_active =date('Y-m-t', strtotime($start_month));
                            $customer_update->status_pv_mt = 'not';
                            $customer_update->date_mt_first =  date('Y-m-d h:i:s');

                            $update_order_type_2 = DB::table('db_orders') //update บิล
                                ->where('id', $order_id)
                                ->update(['pv_banlance' => $pv_mt_all, 'active_mt_date' => date('Y-m-t', strtotime($start_month))]);
                        }

                        $upline_type = $data_user->line_type;
                        $upline_id = $data_user->upline_id;
                        $customer_id = $upline_id;
                        $last_upline_type = $upline_type;

                    } else {
                        $promotion_mt = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                            ->select('*')
                            ->where('code', '=', 'pv_mt')
                            ->first();

                        $pro_mt = $promotion_mt->pv;
                        $pv_mt = $data_user->pv_mt;
                        $pv_mt_all = $pv + $pv_mt;

                        if ($strtime_user > $strtime) {

                            // $contract_date = strtotime(date('Y-m',$strtime_user));

                            // $caltime = strtotime("+1 Month",$contract_date);
                            // $start_month = date("Y-m", $caltime);
                            $start_month = date('Y-m', $strtime_user);

                        } else {
                            $start_month = date("Y-m");

                        }

                        if ($pv_mt_all >= $pro_mt) {

                            //หักลบค่อยอัพเดท
                            $mt_mount = $pv_mt_all / $pro_mt;
                            $mt_mount = floor($mt_mount); //จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
                            $pv_mt_total = $pv_mt_all - ($mt_mount * $pro_mt); //ค่า pv ที่ต้องเอาไปอัพเดท DB

                            $strtime = strtotime($start_month);
                            $mt_active = strtotime("+$mt_mount Month", $strtime);
                            $mt_active = date('Y-m-t', $mt_active); //วันที่ mt_active


                            $customer_update->pv_mt = $pv_mt_total;
                            $customer_update->pv_mt_active = $mt_active;
                            //dd($mt_active);

                            $update_order_type_2 = DB::table('db_orders') //update บิล
                                ->where('id', $order_id)
                                ->update(['pv_banlance' => $pv_mt_total, 'active_mt_date' => $mt_active]);

                        } else {
                            //dd('อัพเดท');
                            $customer_update->pv_mt = $pv_mt_all;

                            $update_order_type_2 = DB::table('db_orders') //update บิล
                                ->where('id', $order_id)
                                ->update(['pv_banlance' => $pv_mt_all, 'active_mt_date' => $data_user->pv_mt_active]);
                        }

                        $upline_type = $data_user->line_type;
                        $upline_id = $data_user->upline_id;
                        $customer_id = $upline_id;
                        $last_upline_type = $upline_type;

                    }

                } elseif ($type_id == 3) { //รักษาคุณสมบัติท่องเที่ยง

                    $data_user = DB::table('customers') //อัพ Pv ของตัวเอง
                        ->select('*')
                        ->where('id', '=', $customer_id)
                        ->first();

                    $active_tv_old_date = $data_user->pv_tv_active;

                    $strtime_user = strtotime($data_user->pv_tv_active);
                    $strtime = strtotime(date("Y-m-d"));

                    $promotion_tv = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                        ->select('*')
                        ->where('code', '=', 'pv_tv')
                        ->first();

                    $pro_tv = $promotion_tv->pv;
                    $pv_tv = $data_user->pv_tv;
                    $pv_tv_all = $pv + $pv_tv;

                    if ($strtime_user > $strtime) {

                        $start_month = date('Y-m', $strtime_user);

                    } else {
                        $start_month = date("Y-m");
                    }
                    if ($pv_tv_all >= $pro_tv) {

                        //หักลบค่อยอัพเดท
                        $tv_mount = $pv_tv_all / $pro_tv;
                        $tv_mount = floor($tv_mount); //จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
                        $pv_tv_total = $pv_tv_all - ($tv_mount * $pro_tv); //ค่า pv ที่ต้องเอาไปอัพเดท DB

                        $add_mount = $tv_mount - 1;
                        $strtime = strtotime($start_month);
                        $tv_active = strtotime("+$add_mount Month", $strtime);
                        $tv_active = date('Y-m-t', $tv_active); //วันที่ tv_active

                        $customer_update->pv_tv = $pv_tv_total;
                        $customer_update->pv_tv_active = $tv_active;

                        //dd($tv_active);

                        $update_order_type_3 = DB::table('db_orders') //update บิล
                            ->where('id', $order_id)
                            ->update(['pv_tv_old'=>$data_user->pv_tv,'pv_banlance' => $pv_tv_total,'active_tv_old_date'=>$active_tv_old_date, 'active_tv_date' => $tv_active]);

                    } else {
                        //dd('อัพเดท');

                          $customer_update->pv_tv = $pv_tv_all;

                        $update_order_type_3 = DB::table('db_orders') //update บิล
                            ->where('id', $order_id)
                            ->update(['pv_tv_old'=>$data_user->pv_tv,'pv_banlance' => $pv_tv_all,'active_tv_old_date'=>$active_tv_old_date, 'active_tv_date' => $data_user->pv_tv_active]);
                    }
                    $upline_type = $data_user->line_type;
                    $upline_id = $data_user->upline_id;
                    $customer_id = $upline_id;
                    $last_upline_type = $upline_type;

                } elseif ($type_id == 4) { //เติม Aipocket

                    $data_user = DB::table('customers') //อัพ Pv ของตัวเอง
                        ->select('*')
                        ->where('id', '=', $customer_id)
                        ->first();
                    //dd($data_user);

                    $add_pv_aipocket = $data_user->pv_aistockist + $pv;
                    $customer_update->pv_aistockist = $add_pv_aipocket;

                    $update_ai_stockist = DB::table('ai_stockist')
                        ->where('order_id_fk', $order_id)
                        ->update(['status' => 'success', 'pv_aistockist' => $add_pv_aipocket]);

                    $upline_type = $data_user->line_type;
                    $upline_id = $data_user->upline_id;
                    $customer_id = $upline_id;
                    $last_upline_type = $upline_type;

                } elseif ($type_id == 5) { //Gift Voucher

                    $pv_banlance = DB::table('customers')
                        ->select('pv')
                        ->where('id', $customer_id)
                        ->first();

                    $update_order_type_5 = DB::table('db_orders') //update บิล
                        ->where('id', $order_id)
                        ->update(['pv_banlance' => $pv_banlance->pv]);

                    //ไม่เข้าสถานะต้อง Approve
                } elseif ($type_id == 6) { //couse อบรม

                    $resuleRegisCourse = Couse_Event::couse_register($order_id, $admin_id);

                    if ($resuleRegisCourse['status'] != 'success') {
                        DB::rollback();
                        return $resuleRegisCourse;
                    }

                    $data_user = DB::table('customers') //อัพ Pv ของตัวเอง
                        ->select('*')
                        ->where('id', '=', $customer_id)
                        ->first();

                    $add_pv = $data_user->pv + $pv;;
                    $customer_update->pv = $add_pv;

                    $update_order_type_6 = DB::table('db_orders') //update บิล
                        ->where('id', $order_id)
                        ->update(['pv_old'=>$data_user->pv,'pv_banlance' => $add_pv, 'action_date' => date('Y-m-d H:i:s')]);

                    $upline_type = $data_user->line_type;
                    $upline_id = $data_user->upline_id;
                    $customer_id = $upline_id;

                    $last_upline_type = $upline_type;

                } else { //ไม่เข้าเงื่อนไขได้เลย
                    $resule = ['status' => 'fail', 'message' => 'ไม่มีเงื่อนไขที่ตรงตามความต้องการ'];
                    DB::rollback();
                    return $resule;
                }

                //ถ้าบิลนี้มียอดเกิน 10000 บาทให้เปลี่ยนสถานะเป็น Aistockis
                if($order_data->total_price > 10000){

                  $customer_update->aistockist_status = 1;
                  $customer_update->aistockist_date =  date('Y-m-d h:i:s');
                }

                if ($customer_id != 'AA' and $pv > 0) {
                    $j = 2;
                    for ($i = 1; $i <= $j; $i++) {

                        $data_user = DB::table('customers')
                            ->where('id', '=', $customer_id)
                            ->first();

                        $upline_type = $data_user->line_type;
                        $upline_id = $data_user->upline_id;

                        if ($upline_id == 'AA') {

                            if ($last_upline_type == 'A') {

                                $add_pv = $data_user->pv_a + $pv;
                                $update_pv = DB::table('customers')
                                    ->where('id', $customer_id)
                                    ->update(['pv_a' => $add_pv]);

                                $resule = ['status' => 'success', 'message' => 'Pv upline Type 1 Success'];
                                $j = 0;

                            } elseif ($last_upline_type == 'B') {
                                $add_pv = $data_user->pv_b + $pv;
                                $update_pv = DB::table('customers')
                                    ->where('id', $customer_id)
                                    ->update(['pv_b' => $add_pv]);

                                $resule = ['status' => 'success', 'message' => 'Pv upline Type 1 Success'];
                                $j = 0;

                            } elseif ($last_upline_type == 'C') {
                                $add_pv = $data_user->pv_c + $pv;
                                $update_pv = DB::table('customers')
                                    ->where('id', $customer_id)
                                    ->update(['pv_c' => $add_pv]);

                                $resule = ['status' => 'success', 'message' => 'Pv upline Type 1 Success'];
                                $j = 0;

                            } else {
                                $resule = ['status' => 'fail', 'message' => 'Data Null Not Upline ID Type AA'];
                                $j = 0;
                            }

                        } else {

                            if ($last_upline_type == 'A') {

                                $add_pv = $data_user->pv_a + $pv;
                                $update_pv = DB::table('customers')
                                    ->where('id', $customer_id)
                                    ->update(['pv_a' => $add_pv]);

                                $customer_id = $upline_id;
                                $last_upline_type = $upline_type;
                                $j = $j + 1;

                            } elseif ($last_upline_type == 'B') {
                                $add_pv = $data_user->pv_b + $pv;
                                $update_pv = DB::table('customers')
                                    ->where('id', $customer_id)
                                    ->update(['pv_b' => $add_pv]);

                                $customer_id = $upline_id;
                                $last_upline_type = $upline_type;
                                $j = $j + 1;

                            } elseif ($last_upline_type == 'C') {
                                $add_pv = $data_user->pv_c + $pv;
                                $update_pv = DB::table('customers')
                                    ->where('id', $customer_id)
                                    ->update(['pv_c' => $add_pv]);

                                $customer_id = $upline_id;
                                $last_upline_type = $upline_type;
                                $j = $j + 1;

                            } else {
                                $resule = ['status' => 'fail', 'message' => 'Data Null Not Upline ID'];
                                $j = 0;
                            }

                        }

                    }

                } else {
                    $resule = ['status' => 'success', 'message' => 'Pv add Type AA Success'];

                }

                if ($resule['status'] == 'success') {
                  $customer_update->save();

                    DB::commit();
                    //DB::rollback();
                    return $resule;
                } else {
                    DB::rollback();
                    return $resule;
                }

            } catch (Exception $e) {
                DB::rollback();
                $resule = ['status' => 'fail', 'message' => 'Update PvPayment Fail'];
                return $resule;

            }

        }

    }



}
