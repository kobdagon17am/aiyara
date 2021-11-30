<?php

namespace App\Http\Controllers\Frontend\Fc;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\Fc\RunPvController;
use Illuminate\Support\Facades\DB;

class CancelOrderController extends Controller
{

    public static function cancel_order($order_id, $customer_or_admin, $type_user_cancel, $action_type)
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

            $type_id = $order_data->purchase_type_id_fk;
//1=รออนุมัติ,2=อนุมัติแล้ว,3=รอชำระ,4=รอจัดส่ง,5=ยกเลิก,6=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย) > Ref>dataset_approve_status>id
            if ($order_data->approve_status == 0 || $order_data->approve_status == 3) { //กรณีสั่งซื้อเเล้วยังไม่มีการอนุมัติค่า PV ยังไม่ถูกดำเนินการ  0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย)'

                if ($type_id == 1 || $type_id == 2 || $type_id == 3 || $type_id == 4 || $type_id == 5) { //1 ทำคุณสมบัติ , 2 รักษาคุณสมบัติ ,3 รักษาคุณสมบัตรท่องเที่ยว ,4 เติม Aistockist
                    $update_order = DB::table('db_orders') //update บิล
                        ->where('id', $order_id)
                        ->update([
                            'cancel_by_user_id_fk' => $customer_or_admin,
                            'order_status_id_fk' => 8, //Status  8 = Cancel
                            'approve_status' => 5, //status = Cancel
                            'type_user_cancel' => $type_user_cancel, //Customer
                            'cancel_action_date' => date('Y-m-d H:i:s'),
                        ]);

                    $update_products_lis = DB::table('db_order_products_list')
                        ->where('frontstore_id_fk', $order_id)
                        ->update([
                            'approve_status' => 2,
                            'approve_date' => date('Y-m-d H:i:s'),
                        ]);

                    $update_products_lis = DB::table('db_order_products_list_giveaway')
                        ->where('order_id_fk', $order_id)
                        ->update([
                            'approve_status' => 4,
                            'approve_date' => date('Y-m-d H:i:s'),
                        ]);

                    if ($type_id == 5) { //GivtVoucher
                        $giv_log = DB::table('log_gift_voucher')
                            ->where('code_order', '=', $order_data->code_order)
                            ->get();

                        foreach ($giv_log as $value) {
                            $update_giftvoucher = DB::table('db_giftvoucher_cus')
                                ->where('id', $value->giftvoucher_cus_id_fk)
                                ->update(['giftvoucher_banlance' => $value->giftvoucher_value_old]);
                        }

                        $update_log_gift_voucher = DB::table('log_gift_voucher')
                            ->where('code_order', $order_data->code_order)
                            ->update([
                                'status' => 'cancel',
                                'action_user_id_fk' => $customer_or_admin,
                                'action_type' => $action_type,
                                'cancel_date' => date('Y-m-d H:i:s'),
                            ]);

                        $resule = ['status' => 'success', 'message' => 'Cancel Oder GiftVoucher Success'];
                    } else {
                        $resule = ['status' => 'success', 'message' => 'Cancel Oder Success'];
                    }
                } elseif ($type_id == 6) { //Course

                } else {
                    $resule = ['status' => 'fail', 'message' => 'type_id นี้ไม่มีในระบบ'];
                    DB::rollback();
                    return $resule;
                }
            } elseif ($order_data->approve_status == 2 || $order_data->approve_status == 4 ){ //กรณีบัตรเครดิตร หรือ Admin มีการอนุมัติเรียบร้อยเเล้ว

                $update_order = DB::table('db_orders') //update บิล
                    ->where('id', $order_id)
                    ->update([
                        'cancel_by_user_id_fk' => $customer_or_admin,
                        'order_status_id_fk' => 8, //Status  8 = Cancel
                        'approve_status' => 5, //status = Cancel
                        'type_user_cancel' => $type_user_cancel, //Customer
                        'cancel_action_date' => date('Y-m-d H:i:s'),
                    ]);

                $update_products_lis = DB::table('db_order_products_list')
                    ->where('frontstore_id_fk', $order_id)
                    ->update([
                        'approve_status' => 2,
                        'approve_date' => date('Y-m-d H:i:s'),
                    ]);

                $update_products_lis = DB::table('db_order_products_list_giveaway')
                    ->where('order_id_fk', $order_id)
                    ->update([
                        'approve_status' => 2,
                        'approve_date' => date('Y-m-d H:i:s'),
                    ]);
                DB::rollback();

                if ($type_id == 5) { //GivtVoucher

                    $giv_log = DB::table('log_gift_voucher')
                        ->where('order_id_fk', '=', $order_id)
                        ->get();

                    foreach ($giv_log as $value) {
                        $update_giftvoucher = DB::table('db_giftvoucher_cus')
                            ->where('id', $value->giftvoucher_cus_id_fk)
                            ->update(['giftvoucher_banlance' => $value->giftvoucher_value_old]);
                    }

                    $update_log_gift_voucher = DB::table('log_gift_voucher')
                        ->where('order_id_fk', $order_id)
                        ->update([
                            'status' => 'cancel',
                            'action_user_id_fk' => $customer_or_admin,
                            'action_type' => $action_type,
                            'cancel_date' => date('Y-m-d H:i:s'),
                        ]);
                }

                //เริ่มลบค่า pv

                $pv_total = $order_data->pv_total;

                if ($pv_total > 0) {

                    if ($order_data->status_payment_sent_other == 1) {
                        $customer_id = $order_data->address_sent_id_fk;
                    } else {
                        $customer_id = $order_data->customers_id_fk;
                    }

                    $customer_user = DB::table('customers') //อัพ Pv ของตัวเอง
                        ->select('*')
                        ->where('id', '=', $customer_id)
                        ->first();

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

                        $update_icash = $customer_user->ai_cash + $order_data->aicash_price;
                        //$customer_user->ai_cash;//Ai-Cash เดิม
                        //$update_icash; //Bicash_banlance

                        $update_aicash = DB::table('db_orders') //update บิล
                            ->where('id', $order_id)
                            ->update(['aicash_old' => $customer_user->ai_cash, 'aicash_banlance' => $update_icash]);

                        $inseart_aicash_movement = DB::table('db_movement_ai_cash')->insert([
                            'customer_id_fk' => $customer_id,
                            'order_id_fk' => $order_id,
                            //'add_ai_cash_id_fk' => '';//กรณีเติม Aicash
                            'business_location_id_fk' => $order_data->business_location_id_fk,
                            'price_total' => $order_data->total_price,
                            'aicash_old' => $customer_user->ai_cash,
                            'aicash_price' => $order_data->aicash_price,
                            'aicash_banlance' => $update_icash,
                            'order_code' => $order_data->code_order,
                            'order_type_id_fk' => $order_data->purchase_type_id_fk,
                            'pay_type_id_fk' => $order_data->pay_type_id_fk,
                            'type' => 'cancel',
                            'detail' => 'Cancel Oder',
                        ]);

                        $update_aicash = DB::table('customers')
                            ->where('id', $customer_id)
                            ->update(['ai_cash' => $update_icash]);

                    }

                    if ($type_id == 1) {

                        $resule = RunPvController::Cancle_pv($customer_user->user_name, $pv_total, $type_id);

                    } elseif ($type_id == 2) { //รักษาคุณสมบัติรายเดือน
                        $update_tv = DB::table('customers')
                            ->where('id', $customer_user->id)
                            ->update(['pv_mt' => $order_data->pv_mt_old, 'pv_mt_active' => $order_data->active_mt_old_date, 'status_pv_mt' => $order_data->status_pv_mt_old]);

                        $resule = RunPvController::Cancle_pv($customer_user->user_name, $pv_total, $type_id);

                    } elseif ($type_id == 3) { //รักษาคุณสมบัตรการท่องเที่ยว

                        $update_tv = DB::table('customers')
                            ->where('id', $customer_user->id)
                            ->update(['pv_tv' => $order_data->pv_tv_old, 'pv_tv_active' => $order_data->active_tv_old_date]);

                        $resule = RunPvController::Cancle_pv($customer_user->user_name, $pv_total, $type_id);

                    } elseif ($type_id == 4) { //เติม Aistockis
                        $add_pv_aistockist = $customer_user->pv_aistockist - $pv_total;

                        $update_pv = DB::table('customers')
                            ->where('id', $customer_user->id)
                            ->update(['pv_aistockist' => $add_pv_aistockist]);

                        $update_ai_stockist = DB::table('ai_stockist')
                            ->where('order_id_fk', $order_id)
                            ->update(['status' => 'cancel']);

                        $resule = RunPvController::Cancle_pv($customer_user->user_name, $pv_total, $type_id);
                    } elseif ($type_id == 6) { // Course
                        $update_couse = DB::table('course_event_regis')
                            ->where('order_id_fk', $order_id)
                            ->update(['status_register' => '3']);

                        $resule = RunPvController::Cancle_pv($customer_user->user_name, $pv_total, $type_id);

                    } else {
                        $resule = ['status' => 'fail', 'message' => 'Type Id is null'];
                        DB::rollback();
                        return $resule;

                    }

                } else {
                    $resule = ['status' => 'success', 'message' => 'pv ของบิลนี้มีค่าเป็น 0 ปรับสถานะบิลเป็น Cancel อย่างเดียว'];
                }
            } else {
                $resule = ['status' => 'fail', 'message' => 'บิลนี้ไม่สามารถยกเลิกได้เนื่องจากไม่อยู่ในสถานะที่ถูกต้อง'];
            }

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
