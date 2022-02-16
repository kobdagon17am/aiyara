<?php
namespace App\Models\Frontend;

use App\Http\Controllers\Frontend\Fc\RunPvController;
use App\Models\Db_Movement_ai_cash;
use App\Models\Frontend\Customer;
use App\Models\Frontend\Order;
use App\Models\Frontend\RunNumberPayment;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Backend\GiftvoucherCus;
use App\Models\Frontend\Runpv_AiStockis;
class PvPayment extends Model
{

    public static function PvPayment_type_confirme($order_id, $admin_id, $distribution_channel, $action_type)
    {
        DB::BeginTransaction();
        $order_data = DB::table('db_orders')
            ->where('id', '=', $order_id)
            ->first();

        $order_update = Order::find($order_id);
        $movement_ai_cash = new Db_Movement_ai_cash;
        $GiftvoucherCus = new GiftvoucherCus;
        $customer_update_ai_cash = Customer::find($order_data->member_id_aicash);

        // dd($movement_ai_cash);

        if (empty($order_data)) {
            $resule = ['status' => 'fail', 'message' => 'ไม่มีบิลนี้อยู่ในระบบ'];
            return $resule;
        }

        if ($order_data->order_status_id_fk == 4 || $order_data->order_status_id_fk == 5 || $order_data->order_status_id_fk == 6 || $order_data->order_status_id_fk == 7) {
            $resule = ['status' => 'fail', 'message' => 'บิลนี้ถูกอนุมัติไปแล้ว ไม่สามารถอนุมัติซ้ำได้'];
            return $resule;
        }

        $pv = $order_data->pv_total;
        if ($order_data->status_payment_sent_other == 1) {

            $customer_id = $order_data->customers_sent_id_fk;
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

                if ($order_data->pay_type_id_fk == 3 || $order_data->pay_type_id_fk == 6 || $order_data->pay_type_id_fk == 9
                    || $order_data->pay_type_id_fk == 11 || $order_data->pay_type_id_fk == 14) { //Aicash




                    $check_aicash = DB::table('customers') //อัพ Pv ของตัวเอง
                        ->select('ai_cash','id','user_name')
                        ->where('id', '=', $order_data->member_id_aicash)
                        ->first();

                        if(empty($check_aicash)){
                          $resule = ['status' => 'fail', 'message' => 'ไม่มี Customers_id ให้ตัด Aicash'];
                          DB::rollback();
                          return $resule;
                        }

                    $update_icash = $check_aicash->ai_cash - $order_data->aicash_price;
                    //$check_aicash->ai_cash;//Ai-Cash เดิม
                    //$update_icash; //Bicash_banlance
                    if ($update_icash >= 0) {

                        $order_update->aicash_old = $check_aicash->ai_cash;
                        $order_update->aicash_banlance = $update_icash;

                        if ($type_id == 6) { //course
                            $movement_type = 'buy_course';
                            $movement_detail = 'Buy Course';
                        } else {
                            $movement_type = 'buy_product';
                            $movement_detail = 'Buy Product';

                        }

                        $movement_ai_cash->customer_id_fk = $check_aicash->id;
                        $movement_ai_cash->order_id_fk = $order_id;
                        //'add_ai_cash_id_fk' => '';//กรณีเติม Aicash
                        $movement_ai_cash->business_location_id_fk = $order_data->business_location_id_fk;
                        $movement_ai_cash->price_total = $order_data->total_price;
                        $movement_ai_cash->aicash_old = $check_aicash->ai_cash;
                        $movement_ai_cash->aicash_price = $order_data->aicash_price;
                        $movement_ai_cash->aicash_banlance = $update_icash;
                        $movement_ai_cash->order_code = $order_data->code_order;
                        $movement_ai_cash->order_type_id_fk = $order_data->purchase_type_id_fk;
                        $movement_ai_cash->pay_type_id_fk = $order_data->pay_type_id_fk;
                        $movement_ai_cash->type = $movement_type;
                        $movement_ai_cash->detail = $movement_detail;

                        $customer_update_ai_cash->ai_cash = $update_icash;

                    } else {

                        $resule = ['status' => 'fail', 'message' => 'Ai Cash ไม่พอสำหรับการการชำระ'];
                        DB::rollback();
                        return $resule;

                    }

                }


                // if ($order_data->pay_type_id_fk == 4 || $order_data->pay_type_id_fk == 12 || $order_data->pay_type_id_fk == 13 || $order_data->pay_type_id_fk == 14
                // || $order_data->pay_type_id_fk == 17 || $order_data->pay_type_id_fk == 18 || $order_data->pay_type_id_fk == 19) { //กิฟวอยเชอ


                // }

                if ($type_id == 6) { //course

                    $orderstatus_id = 7;
                    $opc_type_order = 'course_event';

                    $run_pament_code = RunNumberPayment::run_payment_code($business_location_id, $opc_type_order);

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

                if ($strtotime_date_now_30 > $strtotime_date_now_23) {
                    //$x= 'มากกว่า : '.date('Y-m-d H:i:s',$strtotime_date_now_30).'||'.date('Y-m-d H:i:s',$strtotime_date_now_23);
                    $cancel_expiry_date = date('Y-m-d H:i:s', $strtotime_date_now_23);
                } else {
                    //$x= 'น้อยกว่า : '.date('Y-m-d H:i:s',$strtotime_date_now_30).'||'.date('Y-m-d H:i:s',$strtotime_date_now_23);
                    $cancel_expiry_date = date('Y-m-d H:i:s', $strtotime_date_now_30);
                }

                $order_update->approver = $admin_id;
                $order_update->order_status_id_fk = $orderstatus_id;
                $order_update->cancel_expiry_date = $cancel_expiry_date;
                $order_update->approve_status = 2;
                $order_update->approve_date = date('Y-m-d H:i:s');

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

                                $gv = \App\Helpers\Frontend::get_gitfvoucher($customer_update->user_name);
                                $gv_banlance = $gv->sum_gv + $giveaway_value->gv_free;

                                $GiftvoucherCus->customer_username = $customer_update->user_name;
                                $GiftvoucherCus->giftvoucher_value = $giveaway_value->gv_free;
                                $GiftvoucherCus->giftvoucher_banlance = $giveaway_value->gv_free;
                                $GiftvoucherCus->pro_status = 1;
                                $GiftvoucherCus->detail = 'ได้รับจากการซื้อสินค้า';
                                $GiftvoucherCus->order_id_fk = $order_id;
                                $GiftvoucherCus->code_order = $order_data->code_order;
                                $GiftvoucherCus->giftvoucher_type = 'promotion';
                                $GiftvoucherCus->pro_sdate = now();
                                $GiftvoucherCus->pro_edate = $expiry_date;
                                $GiftvoucherCus->save();

                                $insert_log_gift_voucher = DB::table('log_gift_voucher')->insert([
                                    'customer_id_fk' => $customer_id,
                                    'order_id_fk' => $order_id,
                                    'code_order' => $order_data->code_order,
                                    'giftvoucher_cus_id_fk' => $GiftvoucherCus->id,
                                    'type_action_giftvoucher'=>0,
                                    'giftvoucher_value_old' => $gv->sum_gv,
                                    'giftvoucher_value_use' => $giveaway_value->gv_free,
                                    'giftvoucher_value_banlance' =>  $giveaway_value->gv_free+$gv->sum_gv,
                                    'detail' => 'ได้จากการซื้อสินค้า',
                                    'status' => 'success',
                                    'type' => 'Add',

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
                $order_update->invoice_code_id_fk = $code_order;
                $order_update->invoice_code = $order_data->code_order;
                //check รายการสินค้าแถม

                if ($type_id == 1) { //ทำคุณสมบติ

                    $add_pv = $customer_update->pv + $pv;

                    $order_update->pv_old = $customer_update->pv;
                    $order_update->pv_banlance = $add_pv;
                    $order_update->approve_date = date('Y-m-d H:i:s');

                    // dd($customer_update->user_name);
                    // dd($pv);
                    if($order_update->status_run_pv == 'not_run_pv' || $order_update->status_run_pv == 'cancel' ){

                      $resule = RunPvController::Runpv($customer_update->user_name, $pv, $type_id,$order_data->code_order);
                      if($resule['status'] == 'success'){
                        $order_update->status_run_pv = 'success';
                      }
                    }else{
                      $resule =  ['status' => 'success','message' => 'บิลนี้ถูก Run PV ไปเเล้วไม่สามารถทำซ้ำได้'];
                    }


                    // dd($resule);

                } elseif ($type_id == 2) { //รักษาคุณสมบัติรายเดือน

                    $active_mt_old_date = $customer_update->pv_mt_active;
                    $status_pv_mt_old = $customer_update->status_pv_mt;

                    $order_update->pv_mt_old = $customer_update->pv_mt;
                    $order_update->active_mt_old_date = $active_mt_old_date;
                    $order_update->status_pv_mt_old = $status_pv_mt_old;
                    $order_update->approve_date = date('Y-m-d H:i:s');

                    $strtime_user = strtotime($customer_update->pv_mt_active);
                    $strtime = strtotime(date("Y-m-d"));

                    if ($customer_update->status_pv_mt == 'first') {

                        // if($strtime_user < $strtime){
                        //     //$contract_date = strtotime(date('Y-m',$strtime_user));
                        //     //$caltime = strtotime("+1 Month",$contract_date);
                        //     //$start_month = date("Y-m", $caltime);
                        //     $start_month = date("Y-m");

                        // }else{

                        //     $start_month = date("Y-m");
                        // }

                        $start_month = date('Y-m');

                        $promotion_mt = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                            ->select('*')
                            ->where('code', '=', 'pv_mt')
                            ->first();

                        $pro_mt = $promotion_mt->pv;
                        $pv_mt = $customer_update->pv_mt;
                        $pv_mt_all = $pv + $pv_mt;


                        if ($pv_mt_all >= $pro_mt) {
                            //dd('หักลบค่อยอัพเดท');
                            //หักลบค่อยอัพเดท
                            $mt_mount = $pv_mt_all / $pro_mt;
                            $mt_mount = floor($mt_mount); //จำนวนเต์มเดือนที่นำไปบวกเพิ่ม

                            $pv_mt_total = $pv_mt_all % $pro_mt; //ค่า pv ที่ต้องเอาไปอัพเดท DB

                            $mt_mount_new = $mt_mount+2;//กำหนดให้เป็นวันที่ 1 ของสองเดือนหน้า

                            $mt_active = strtotime("+$mt_mount_new Month", strtotime($start_month));

                            $mt_active = date('Y-m-1', $mt_active); //วันที่ mt_active


                            $customer_update->pv_mt = $pv_mt_total;
                            $customer_update->pv_mt_active = $mt_active;
                            $customer_update->status_pv_mt = 'not';

                            $customer_update->date_mt_first = date('Y-m-d h:i:s');

                            $order_update->pv_banlance = $pv_mt_total;

                            $order_update->active_mt_date =  $mt_active;

                        } else {
                            //dd('อัพเดท');

                            $customer_update->pv_mt = $pv_mt_all;
                            $mt_mount_new = strtotime("+2 Month", strtotime($start_month));

                            //dd(date('Y-m-1',$mt_mount_new));
                            $customer_update->pv_mt_active = date('Y-m-1',$mt_mount_new);
                            $customer_update->status_pv_mt = 'not';
                            $customer_update->date_mt_first = date('Y-m-d h:i:s');

                            $order_update->pv_banlance = $pv_mt_all;
                            $order_update->active_mt_date = date('Y-m-1',$mt_mount_new);
                        }



                        if($order_update->status_run_pv == 'not_run_pv' || $order_update->status_run_pv == 'cancel' ){
                          $resule = RunPvController::Runpv($customer_update->user_name, $pv, $type_id,$order_data->code_order);
                          if($resule['status'] == 'success'){
                            $order_update->status_run_pv = 'success';
                          }
                        }else{
                          $resule =  ['status' => 'success','message' => 'บิลนี้ถูก Run PV ไปเเล้วไม่สามารถทำซ้ำได้'];
                        }

                    } else {
                        $promotion_mt = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                            ->select('*')
                            ->where('code', '=', 'pv_mt')
                            ->first();

                        $pro_mt = $promotion_mt->pv;
                        $pv_mt = $customer_update->pv_mt;
                        $pv_mt_all = $pv + $pv_mt;

                        if ($strtime_user > $strtime) {

                            // $contract_date = strtotime(date('Y-m',$strtime_user));

                            // $caltime = strtotime("+1 Month",$contract_date);
                            // $start_month = date("Y-m", $caltime);
                            $strtime_user = strtotime("-1 Month", $strtime_user);
                            $start_month = date('Y-m', $strtime_user);


                        } else {

                          $strtime_user = strtotime("-1 Month");
                          $start_month = date('Y-m', $strtime_user);

                        }

                        if ($pv_mt_all >= $pro_mt) {

                            //หักลบค่อยอัพเดท
                            $mt_mount = $pv_mt_all / $pro_mt;
                            $mt_mount = floor($mt_mount); //จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
                            $pv_mt_total = $pv_mt_all % $pro_mt; //ค่า pv ที่ต้องเอาไปอัพเดท DB

                            $strtime = strtotime($start_month);
                            $mt_mount_new = $mt_mount+1;

                            $mt_active = strtotime("+$mt_mount_new Month", $strtime);
                            $mt_active = date('Y-m-1', $mt_active); //วันที่ mt_active

                            $customer_update->pv_mt = $pv_mt_total;
                            $customer_update->pv_mt_active = $mt_active;

                            $order_update->pv_banlance = $pv_mt_total;
                            $order_update->active_mt_date =  date('Y-m-1',strtotime($mt_active));

                        } else {

                            $customer_update->pv_mt = $pv_mt_all;
                            $order_update->pv_banlance = $pv_mt_all;
                            $order_update->active_mt_date = $customer_update->pv_mt_active;

                        }


                        if($order_update->status_run_pv == 'not_run_pv' || $order_update->status_run_pv == 'cancel' ){
                          $resule = RunPvController::Runpv($customer_update->user_name, $pv, $type_id,$order_data->code_order);
                          if($resule['status'] == 'success'){
                            $order_update->status_run_pv = 'success';
                          }
                        }else{
                          $resule =  ['status' => 'success','message' => 'บิลนี้ถูก Run PV ไปเเล้วไม่สามารถทำซ้ำได้'];
                        }

                    }

                } elseif ($type_id == 3) { //รักษาคุณสมบัติท่องเที่ยง

                    $active_tv_old_date = $customer_update->pv_tv_active;
                    $strtime_user = strtotime($customer_update->pv_tv_active);
                    $strtime = strtotime(date("Y-m-d"));

                    $promotion_tv = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                        ->select('*')
                        ->where('code', '=', 'pv_tv')
                        ->first();
                    $pro_tv = $promotion_tv->pv;
                    $pv_tv = $customer_update->pv_tv;
                    $pv_tv_all = $pv + $pv_tv;

                    if ($strtime_user > $strtime) {

                      $strtime_user = strtotime("-1 Month", $strtime_user);
                      $start_month = date('Y-m', $strtime_user);

                  } else {
                    $strtime_user = strtotime("-1 Month");
                    $start_month = date('Y-m', $strtime_user);
                  }


                    if ($pv_tv_all >= $pro_tv) {
                        //หักลบค่อยอัพเดท
                        $tv_mount = $pv_tv_all / $pro_tv;
                        $tv_mount = floor($tv_mount); //จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
                        $pv_tv_total = $pv_tv_all % $pro_tv; //ค่า pv ที่ต้องเอาไปอัพเดท DB

                        $add_mount = $tv_mount+1;
                        $strtime = strtotime($start_month);
                        $tv_active = strtotime("+$add_mount Month", $strtime);
                        $tv_active = date('Y-m-1', $tv_active); //วันที่ tv_active

                        $customer_update->pv_tv = $pv_tv_total;
                        $customer_update->pv_tv_active = $tv_active;
                        $order_update->pv_tv_old = $customer_update->pv_tv;
                        $order_update->pv_banlance = $pv_tv_total;
                        $order_update->active_tv_old_date = $active_tv_old_date;
                        $order_update->active_tv_date = $tv_active;

                    } else {

                        $customer_update->pv_tv = $pv_tv_all;
                        $order_update->pv_tv_old = $customer_update->pv_tv;
                        $order_update->pv_banlance = $pv_tv_all;
                        $order_update->active_tv_old_date = $active_tv_old_date;
                        $order_update->active_tv_date = $customer_update->pv_tv_active;

                    }



                    if($order_update->status_run_pv == 'not_run_pv' || $order_update->status_run_pv == 'cancel' ){
                      $resule = RunPvController::Runpv($customer_update->user_name, $pv, $type_id,$order_data->code_order);
                      if($resule['status'] == 'success'){
                        $order_update->status_run_pv = 'success';
                      }
                    }else{
                      $resule =  ['status' => 'success','message' => 'บิลนี้ถูก Run PV ไปเเล้วไม่สามารถทำซ้ำได้'];
                    }

                } elseif ($type_id == 4) { //เติม Ai Stockist

                  $resule = Runpv_AiStockis::add_pv_aistockist($type_id, $pv,$customer_update->user_name,$customer_update->user_name,$order_data->code_order,$order_data->id);

                  if ($resule['status'] == 'fail') {

                    $log =  ['customer_user_name' => $customer_update->user_name,
                    'admin_user_name' => '',
                    'code_order' => $order_data->code_order,
                    'error_detail' => 'เติม Ai Stockist',
                    'function' => 'App\Http\Controllers\Frontend\Fc\RunPvController',
                    'type' =>'',//'admin','customer'
                    ];
                    $rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);
                  }

                    $add_pv_aipocket = $customer_update->pv_aistockist + $pv;
                    $customer_update->pv_aistockist = $add_pv_aipocket;

                    $update_ai_stockist = DB::table('ai_stockist')
                        ->where('order_id_fk', $order_id)
                        ->update(['status' => 'success', 'pv_aistockist' => $add_pv_aipocket]);



                     if($order_update->status_run_pv == 'not_run_pv' || $order_update->status_run_pv == 'cancel' ){
                      $resule = RunPvController::Runpv($customer_update->user_name, $pv, $type_id,$order_data->code_order);
                      if($resule['status'] == 'success'){
                        $order_update->status_run_pv = 'success';
                      }
                    }else{
                      $resule =  ['status' => 'success','message' => 'บิลนี้ถูก Run PV ไปเเล้วไม่สามารถทำซ้ำได้'];
                    }

                      //ถ้าบิลนี้มียอดเกิน 10000 บาทให้เปลี่ยนสถานะเป็น Aistockis
                        if ($order_data->pv_total >= 10000) {

                          $customer_update->aistockist_status = '1';
                          $customer_update->aistockist_date = date('Y-m-d h:i:s');
                      }

                } elseif ($type_id == 5) { // Ai Voucher

                    $pv_banlance = DB::table('customers')
                        ->select('pv')
                        ->where('id', $customer_id)
                        ->first();

                    $order_update->pv_banlance = $pv_banlance->pv;
                    //ไม่เข้าสถานะต้อง Approve


                    if($order_update->status_run_pv == 'not_run_pv' || $order_update->status_run_pv == 'cancel' ){
                      $resule = RunPvController::Runpv($customer_update->user_name, $pv, $type_id,$order_data->code_order);
                      if($resule['status'] == 'success'){
                        $order_update->status_run_pv = 'success';
                      }
                    }else{
                      $resule =  ['status' => 'success','message' => 'บิลนี้ถูก Run PV ไปเเล้วไม่สามารถทำซ้ำได้'];
                    }

                } elseif ($type_id == 6) { //couse อบรม

                    $update_couse = DB::table('course_event_regis')
                        ->where('order_id_fk', $order_id)
                        ->update(['status_register' => '2']);

                    $add_pv = $customer_update->pv + $pv;

                    $order_update->pv_old = $customer_update->pv;
                    $order_update->pv_banlance = $add_pv;
                    $order_update->action_date = date('Y-m-d H:i:s');


                    if($order_update->status_run_pv == 'not_run_pv' || $order_update->status_run_pv == 'cancel' ){
                      $resule = RunPvController::Runpv($customer_update->user_name, $pv, $type_id,$order_data->code_order);
                      if($resule['status'] == 'success'){
                        $order_update->status_run_pv = 'success';
                      }
                    }else{
                      $resule =  ['status' => 'success','message' => 'บิลนี้ถูก Run PV ไปเเล้วไม่สามารถทำซ้ำได้'];
                    }

                } else { //ไม่เข้าเงื่อนไขได้เลย
                    $resule = ['status' => 'fail', 'message' => 'ไม่มีเงื่อนไขที่ตรงตามความต้องการ'];
                    DB::rollback();
                    return $resule;
                }



                if ($resule['status'] == 'success') {
                    $movement_ai_cash->save();
                    $customer_update->save();
                    $order_update->save();

                    if ($order_data->pay_type_id_fk == 3 || $order_data->pay_type_id_fk == 6 || $order_data->pay_type_id_fk == 9
                    || $order_data->pay_type_id_fk == 11 || $order_data->pay_type_id_fk == 14) { //Aicash
                    $customer_update_ai_cash->save();
                    }

                    \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddress($order_id);
                    \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddressDefault($order_id);

                    $update_package = \App\Http\Controllers\Frontend\Fc\RunPvController::update_package($customer_update->user_name);
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

    public static function PvPayment_type_confirme_vip($order_id, $admin_id, $distribution_channel, $action_type)
    {

        DB::BeginTransaction();
        $order_data = DB::table('db_orders')
            ->where('id', '=', $order_id)
            ->first();

        if (empty($order_data)) {
            $resule = ['status' => 'fail', 'message' => 'ไม่มีบิลนี้อยู่ในระบบ'];
            return $resule;
        }

        if ($order_data->order_channel != 'VIP') {
            $resule = ['status' => 'fail', 'message' => 'ประเภทการสังซื้อไม่ถูกต้อง ( Order VIP เท่านั้น)'];
            return $resule;
        }

        if ($order_data->order_status_id_fk == 4 || $order_data->order_status_id_fk == 5 || $order_data->order_status_id_fk == 6 || $order_data->order_status_id_fk == 7) {
            $resule = ['status' => 'fail', 'message' => 'บิลนี้ถูกอนุมัติไปแล้ว ไม่สามารถอนุมัติซ้ำได้'];
            return $resule;
        }

        $opc_type_order = 'product';
        $run_pament_code = RunNumberPayment::run_payment_code($order_data->business_location_id_fk, $opc_type_order);
        if ($run_pament_code['status'] == 'fail') {
            $resule = ['status' => 'fail', 'message' => $run_pament_code['message']];
            DB::rollback();
            return $resule;
        }

        $invoice_code = $run_pament_code['code_order'];
        $transection_code = RunNumberPayment::run_number_aistockis();

        //dd($order_data->pv_total,$order_data->drop_ship_bonus);

        try {

            $customer_update = Customer::find($order_data->customers_id_fk);
            $add_pv_aipocket = $customer_update->pv_aistockist + $order_data->pv_total;
            $drop_ship_bonus = $customer_update->drop_ship_bonus + $order_data->drop_ship_bonus;

            $customer_update->pv_aistockist = $add_pv_aipocket;
            $customer_update->drop_ship_bonus = $drop_ship_bonus;

            $ai_stockist = DB::table('ai_stockist')->insert(
                [
                    'user_id_fk' => $order_data->user_id_fk,
                    'customer_id' => $order_data->customers_id_fk,
                    'to_customer_id' => $order_data->customers_id_fk,
                    'order_id_fk' => $order_data->id,
                    'transection_code' => $transection_code,
                    'set_transection_code' => date('ym'),
                    'code_order' => $order_data->code_order,
                    'pv' => $order_data->pv_total,
                    'type_id' => '8',
                    'status' => 'success',
                    'pv_aistockist' => $add_pv_aipocket,
                    'banlance' => $add_pv_aipocket,
                    'order_channel' => 'VIP',
                    'status_transfer' => '1',
                    //'detail' => 'Payment Add Ai-Stockist',
                ]
            );

            $order_update = Order::find($order_id);

            if ($order_data->delivery_location_frontend == 'sent_office') {
                $orderstatus_id = 4;
                # code...
            } else {
                $orderstatus_id = 5;

            }
            $order_update->pv_mt_old = $customer_update->pv_mt;
            $order_update->approver = $admin_id;
            $order_update->order_status_id_fk = $orderstatus_id;
            $order_update->approve_status = 2;
            $order_update->approve_date = date('Y-m-d H:i:s');
            $customer_update->save();
            $order_update->save();

            \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddress($order_id);
            \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddressDefault($order_id);

            DB::commit();

            $resule = ['status' => 'success', 'message' => 'Success'];
            return $resule;

        } catch (Exception $e) {
            DB::rollback();
            $resule = ['status' => 'fail', 'message' => 'Update PvPayment Fail'];
            return $resule;

        }

    }

}
