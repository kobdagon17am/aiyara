<?php

namespace App\Http\Controllers\Frontend\Fc;

use App\Http\Controllers\Controller;
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
                    'approve_status' => 4, //status = Cancel
                    'type_user_cancel' => $type_user_cancel, //Customer
                    'cancel_action_date' => date('Y-m-d H:i:s'),
                ]);

            $type_id = $order_data->purchase_type_id_fk;

            if ($order_data->approve_status == 0 || $order_data->approve_status == 2) { //กรณีสั่งซื้อเเล้วยังไม่มีการอนุมัติค่า PV ยังไม่ถูกดำเนินการ  0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย)'

                if ($type_id == 1 || $type_id == 2 || $type_id == 3 || $type_id == 4 || $type_id == 5) { //1 ทำคุณสมบัติ , 2 รักษาคุณสมบัติ ,3 รักษาคุณสมบัตรท่องเที่ยว ,4 เติม Aistockist
                    $update_order = DB::table('db_orders') //update บิล
                        ->where('id', $order_id)
                        ->update([
                            'cancel_by_user_id_fk' => $customer_or_admin,
                            'order_status_id_fk' => 8, //Status  8 = Cancel
                            'approve_status' => 4, //status = Cancel
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
            } elseif ($order_data->approve_status == 1) { //กรณีบัตรเครดิตร หรือ Admin มีการอนุมัติเรียบร้อยเเล้ว

                $update_order = DB::table('db_orders') //update บิล
                    ->where('id', $order_id)
                    ->update([
                        'cancel_by_user_id_fk' => $customer_or_admin,
                        'order_status_id_fk' => 8, //Status  8 = Cancel
                        'approve_status' => 4, //status = Cancel
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
                    // 4 Gift Voucher
                    // 5 เงินสด
                    // 6 เงินสด + Ai-Cash
                    // 7 เครดิต + เงินสด
                    // 8 เครดิต + เงินโอน
                    // 9 เครดิต + Ai-Cash
                    // 10 เงินโอน + เงินสด
                    // 11 เงินโอน + Ai-Cash
                    // 12 Gift Voucher + เงินโอน
                    // 13 Gift Voucher + บัตรเครดิต
                    // 14 Gift Voucher + Ai-Cash

                    // if ($pay_type == 3 || $pay_type == 6 || $pay_type == 9 || $pay_type == 11) {
                    //     dd('1');

                    // }

                    // if ($pay_type == 14) {
                    //     dd('2');

                    // }

                    if ($type_id == 1) {

                        $add_pv = $customer_user->pv - $pv_total;

                        $update_pv = DB::table('customers')
                            ->where('id', $customer_user->id)
                            ->update(['pv' => $add_pv]);

                        $upline_type = $customer_user->line_type;
                        $upline_id = $customer_user->upline_id;
                        $customer_id = $upline_id;
                        $last_upline_type = $upline_type;
                    } elseif ($type_id == 2) { //รักษาคุณสมบัติรายเดือน
                        $update_tv = DB::table('customers')
                            ->where('id', $customer_user->id)
                            ->update(['pv_mt' => $order_data->pv_mt_old, 'pv_mt_active' => $order_data->active_mt_old_date, 'status_pv_mt' => $order_data->status_pv_mt_old]);

                        $upline_type = $customer_user->line_type;
                        $upline_id = $customer_user->upline_id;
                        $customer_id = $upline_id;
                        $last_upline_type = $upline_type;

                    } elseif ($type_id == 3) { //รักษาคุณสมบัตรการท่องเที่ยว

                        $update_tv = DB::table('customers')
                            ->where('id', $customer_user->id)
                            ->update(['pv_tv' => $order_data->pv_tv_old, 'pv_tv_active' => $order_data->active_tv_old_date]);

                        $upline_type = $customer_user->line_type;
                        $upline_id = $customer_user->upline_id;
                        $customer_id = $upline_id;
                        $last_upline_type = $upline_type;

                    } elseif ($type_id == 4) { //เติม Aistockis
                        $add_pv_aistockist = $customer_user->pv_aistockist - $pv_total;

                        $update_pv = DB::table('customers')
                            ->where('id', $customer_user->id)
                            ->update(['pv_aistockist' => $add_pv_aistockist]);

                        $update_ai_stockist = DB::table('ai_stockist')
                            ->where('order_id_fk', $order_id)
                            ->update(['status' => 'cancel']);

                        $upline_type = $customer_user->line_type;
                        $upline_id = $customer_user->upline_id;
                        $customer_id = $upline_id;
                        $last_upline_type = $upline_type;
                    } elseif ($type_id == 6) { // Course
                        $update_ai_stockist = DB::table('course_event_regis')
                            ->where('order_id_fk', $order_id)
                            ->update(['status_register' => 'cancel']);

                        $upline_type = $customer_user->line_type;
                        $upline_id = $customer_user->upline_id;
                        $customer_id = $upline_id;
                        $last_upline_type = $upline_type;

                    } else {
                        $resule = ['status' => 'fail', 'message' => 'Type Id is null'];
                        DB::rollback();
                        return $resule;

                    }

                    if ($customer_id != 'AA') {
                        $j = 2;
                        for ($i = 1; $i <= $j; $i++) {

                            $data_user = DB::table('Customers')
                                ->where('id', '=', $customer_id)
                                ->first();

                            $upline_type = $data_user->line_type;
                            $upline_id = $data_user->upline_id;

                            if ($upline_id == 'AA') {

                                if ($last_upline_type == 'A') {

                                    $add_pv = $data_user->pv_a - $pv_total;
                                    $update_pv = DB::table('Customers')
                                        ->where('id', $customer_id)
                                        ->update(['pv_a' => $add_pv]);

                                    $resule = ['status' => 'success', 'message' => 'Pv Remove upline Success'];
                                    $j = 0;
                                } elseif ($last_upline_type == 'B') {
                                    $add_pv = $data_user->pv_b - $pv_total;
                                    $update_pv = DB::table('Customers')
                                        ->where('id', $customer_id)
                                        ->update(['pv_b' => $add_pv]);

                                    $resule = ['status' => 'success', 'message' => 'Pv Remove upline Success'];
                                    $j = 0;
                                } elseif ($last_upline_type == 'C') {
                                    $add_pv = $data_user->pv_c - $pv_total;
                                    $update_pv = DB::table('Customers')
                                        ->where('id', $customer_id)
                                        ->update(['pv_c' => $add_pv]);

                                    $resule = ['status' => 'success', 'message' => 'Pv Remove upline Success'];
                                    $j = 0;
                                } else {
                                    $resule = ['status' => 'fail', 'message' => 'Data Null Not Upline ID Type AA'];
                                    $j = 0;
                                }
                            } else {

                                if ($last_upline_type == 'A') {

                                    $add_pv = $data_user->pv_a - $pv_total;
                                    $update_pv = DB::table('Customers')
                                        ->where('id', $customer_id)
                                        ->update(['pv_a' => $add_pv]);

                                    $customer_id = $upline_id;
                                    $last_upline_type = $upline_type;
                                    $j = $j + 1;
                                } elseif ($last_upline_type == 'B') {
                                    $add_pv = $data_user->pv_b - $pv_total;
                                    $update_pv = DB::table('Customers')
                                        ->where('id', $customer_id)
                                        ->update(['pv_b' => $add_pv]);

                                    $customer_id = $upline_id;
                                    $last_upline_type = $upline_type;
                                    $j = $j + 1;
                                } elseif ($last_upline_type == 'C') {
                                    $add_pv = $data_user->pv_c - $pv_total;
                                    $update_pv = DB::table('Customers')
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
                } else {
                    $resule = ['status' => 'success', 'message' => 'Cancel Oder Success'];
                }
            } else {
                $resule = ['status' => 'fail', 'message' => 'บิลนี้ไม่สามารถยกเลิกได้เนื่องจากไม่อยู่ในสถานะที่ถูกต้อง'];
            }

            if ($resule['status'] == 'success') {
                DB::rollback();
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

    public static function cancel_oder_run_pv($order_id) //ตีกลับ PV กรณียกเลิกบิล

    {
        if (!empty($type) || !empty($pv) || !empty($username)) {
            try {
                DB::BeginTransaction();
                $id = Auth::guard('c_user')->user()->id;

                $user = DB::table('customers')
                    ->where('id', '=', $id)
                    ->limit(1)
                    ->get();

                if (empty($user)) {
                    $resule = ['status' => 'fail', 'message' => 'ไม่มี User นี้ในระบบ'];
                } else {
                    $pv_total = $user[0]->pv_aistockist - $pv;
                    if ($pv_total < 0) {
                        $resule = ['status' => 'fail', 'message' => 'ค่า Pv ไม่พอสำหรับการใช้งาน'];
                    } else {
                        $update_pv = DB::table('customers') //update PV
                            ->where('id', $id)
                            ->update(['pv_aistockist' => $pv_total]);

                        //run slot
                        if ($type == 1) { //ทำคุณสมบติ

                            $to_customer_id = DB::table('customers')
                                ->where('user_name', '=', $username)
                                ->first();

                            $transection_code = RunNumberPayment::run_number_aistockis();
                            DB::table('ai_stockist')
                                ->insert([
                                    'customer_id' => $id,
                                    'to_customer_id' => $to_customer_id->id,
                                    'transection_code' => $transection_code,
                                    'set_transection_code' => date('ym'),
                                    'pv' => $pv,
                                    'status' => 'success',
                                    'type_id' => $type,
                                    'detail' => 'Sent Ai-Stockis',
                                    'banlance' => $pv_total,
                                ]);

                            $customer_user = DB::table('customers') //อัพ Pv ของตัวเอง
                                ->select('*')
                                ->where('user_name', '=', $username)
                                ->first();
                            //dd($customer_user);

                            $add_pv = $customer_user->pv + $pv;
                            $update_pv = DB::table('customers')
                                ->where('id', $customer_user->id)
                                ->update(['pv' => $add_pv]);

                            //ทำคุณสมบัติตัวเอง
                            $upline_type = $customer_user->line_type;
                            $upline_id = $customer_user->upline_id;
                            $customer_id = $upline_id;
                            $last_upline_type = $upline_type;
                        } elseif ($type == 2) { //รักษาคุณสมบัติรายเดือน
                            $to_customer_id = DB::table('customers')
                                ->where('user_name', '=', $username)
                                ->first();

                            $transection_code = RunNumberPayment::run_number_aistockis();
                            DB::table('ai_stockist')
                                ->insert([
                                    'customer_id' => $id,
                                    'to_customer_id' => $to_customer_id->id,
                                    'transection_code' => $transection_code,
                                    'set_transection_code' => date('ym'),
                                    'pv' => $pv,
                                    'status' => 'success',
                                    'type_id' => $type,
                                    'detail' => 'Sent Ai-Stockis',
                                    'banlance' => $pv_total,
                                ]);

                            $strtime_user = strtotime($to_customer_id->pv_mt_active);
                            $strtime = strtotime(date("Y-m-d"));

                            if ($to_customer_id->status_pv_mt == 'first') {

                                $start_month = date("Y-m");

                                $promotion_mt = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                                    ->select('*')
                                    ->where('code', '=', 'pv_mt')
                                    ->first();

                                $pro_mt = $promotion_mt->pv;
                                $pv_mt = $to_customer_id->pv_mt;
                                $pv_mt_all = $pv + $pv_mt;

                                if ($pv_mt_all >= $pro_mt) {
                                    //dd('หักลบค่อยอัพเดท');
                                    //หักลบค่อยอัพเดท
                                    $mt_mount = $pv_mt_all / $pro_mt;
                                    $mt_mount = floor($mt_mount); //จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
                                    $pv_mt_total = $pv_mt_all - ($mt_mount * $pro_mt); //ค่า pv ที่ต้องเอาไปอัพเดท DB

                                    $mt_active = strtotime("+$mt_mount Month", strtotime($start_month));
                                    $mt_active = date('Y-m-t', $mt_active); //วันที่ mt_active

                                    $update_mt = DB::table('customers')
                                        ->where('id', $to_customer_id->id)
                                        ->update([
                                            'pv_mt' => $pv_mt_total, 'pv_mt_active' => $mt_active,
                                            'status_pv_mt' => 'not', 'date_mt_first' => date('Y-m-d h:i:s'),
                                        ]);
                                } else {
                                    //dd('อัพเดท');
                                    $update_mt = DB::table('customers')
                                        ->where('id', $to_customer_id->id)
                                        ->update([
                                            'pv_mt' => $pv_mt_all,
                                            'pv_mt_active' => date('Y-m-t', strtotime($start_month)),
                                            'status_pv_mt' => 'not', 'date_mt_first' => date('Y-m-d h:i:s'),
                                        ]);
                                }

                                $upline_type = $to_customer_id->line_type;
                                $upline_id = $to_customer_id->upline_id;
                                $customer_id = $upline_id;
                                $last_upline_type = $upline_type;
                            } else {
                                $promotion_mt = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                                    ->select('*')
                                    ->where('code', '=', 'pv_mt')
                                    ->first();

                                $pro_mt = $to_customer_id->pv;
                                $pv_mt = $to_customer_id->pv_mt;
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

                                    $update_mt = DB::table('customers')
                                        ->where('id', $to_customer_id->id)
                                        ->update(['pv_mt' => $pv_mt_total, 'pv_mt_active' => $mt_active]);
                                } else {
                                    //dd('อัพเดท');
                                    $update_mt = DB::table('customers')
                                        ->where('id', $to_customer_id->id)
                                        ->update(['pv_mt' => $pv_mt_all]);

                                    $upline_type = $to_customer_id->line_type;
                                    $upline_id = $to_customer_id->upline_id;
                                    $customer_id = $upline_id;
                                    $last_upline_type = $upline_type;
                                }
                            }
                        } elseif ($type == 3) { //รักษาคุณสมบัติท่องเที่ยง

                            $to_customer_id = DB::table('customers')
                                ->where('user_name', '=', $username)
                                ->first();

                            $transection_code = RunNumberPayment::run_number_aistockis();
                            DB::table('ai_stockist')
                                ->insert([
                                    'customer_id' => $id,
                                    'to_customer_id' => $to_customer_id->id,
                                    'transection_code' => $transection_code,
                                    'set_transection_code' => date('ym'),
                                    'pv' => $pv,
                                    'status' => 'success',
                                    'type_id' => $type,
                                    'detail' => 'Sent Ai-Stockis',
                                    'banlance' => $pv_total,
                                ]);

                            $strtime_user = strtotime($to_customer_id->pv_tv_active);
                            $strtime = strtotime(date("Y-m-d"));

                            $promotion_tv = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                                ->select('*')
                                ->where('code', '=', 'pv_tv')
                                ->first();

                            $pro_tv = $promotion_tv->pv;
                            $pv_tv = $to_customer_id->pv_tv;
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

                                $update_mt = DB::table('customers')
                                    ->where('id', $to_customer_id->id)
                                    ->update(['pv_tv' => $pv_tv_total, 'pv_tv_active' => $tv_active]);
                                //dd($tv_active);

                            } else {
                                //dd('อัพเดท');
                                $update_mt = DB::table('customers')
                                    ->where('id', $to_customer_id->id)
                                    ->update(['pv_tv' => $pv_tv_all]);
                            }
                            $upline_type = $to_customer_id->line_type;
                            $upline_id = $to_customer_id->upline_id;
                            $customer_id = $upline_id;
                            $last_upline_type = $upline_type;
                        } else {
                            $resule = ['status' => 'fail', 'message' => 'ไม่มี Type ที่เลือก'];
                        }

                        if ($customer_id != 'AA') {
                            $j = 2;
                            for ($i = 1; $i <= $j; $i++) {

                                $data_user = DB::table('Customers')
                                    ->where('id', '=', $customer_id)
                                    ->first();

                                $upline_type = $data_user->line_type;
                                $upline_id = $data_user->upline_id;

                                if ($upline_id == 'AA') {

                                    if ($last_upline_type == 'A') {

                                        $add_pv = $data_user->pv_a + $pv;
                                        $update_pv = DB::table('Customers')
                                            ->where('id', $customer_id)
                                            ->update(['pv_a' => $add_pv]);

                                        $resule = ['status' => 'success', 'message' => 'Pv upline Type 1 Success'];
                                        $j = 0;
                                    } elseif ($last_upline_type == 'B') {
                                        $add_pv = $data_user->pv_b + $pv;
                                        $update_pv = DB::table('Customers')
                                            ->where('id', $customer_id)
                                            ->update(['pv_b' => $add_pv]);

                                        $resule = ['status' => 'success', 'message' => 'Pv upline Type 1 Success'];
                                        $j = 0;
                                    } elseif ($last_upline_type == 'C') {
                                        $add_pv = $data_user->pv_c + $pv;
                                        $update_pv = DB::table('Customers')
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
                                        $update_pv = DB::table('Customers')
                                            ->where('id', $customer_id)
                                            ->update(['pv_a' => $add_pv]);

                                        $customer_id = $upline_id;
                                        $last_upline_type = $upline_type;
                                        $j = $j + 1;
                                    } elseif ($last_upline_type == 'B') {
                                        $add_pv = $data_user->pv_b + $pv;
                                        $update_pv = DB::table('Customers')
                                            ->where('id', $customer_id)
                                            ->update(['pv_b' => $add_pv]);

                                        $customer_id = $upline_id;
                                        $last_upline_type = $upline_type;
                                        $j = $j + 1;
                                    } elseif ($last_upline_type == 'C') {
                                        $add_pv = $data_user->pv_c + $pv;
                                        $update_pv = DB::table('Customers')
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
                    }
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
                $resule = ['status' => 'fail', 'message' => 'Update PvPayment Fail'];
                return $resule;
            }

            $resule = ['status' => 'success', 'message' => 'Yess'];
        } else {
            $resule = ['status' => 'fail', 'message' => 'Data in Null'];
        }

        return $resule;
    }
}
