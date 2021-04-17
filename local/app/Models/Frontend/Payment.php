<?php
namespace App\Models\Frontend;

use App\Models\Frontend\GiftVoucher;
use App\Models\Frontend\PaymentAddProduct;
use App\Models\Frontend\PaymentSentAddressOrder;
use App\Models\Frontend\Pvpayment;
use App\Models\Frontend\RunNumberPayment;
use Auth;
use Cart;
use DB;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public static function payment_uploadfile($rs)
    {
        DB::BeginTransaction();
        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        $customer_id = Auth::guard('c_user')->user()->id;
        // เลขใบเสร็จ
        // ปีเดือน[รันเลข]
        // 2020110 00001

        $code_order = RunNumberPayment::run_number_order($business_location_id);
        try {
            $cartCollection = Cart::session($rs->type)->getContent();
            $data = $cartCollection->toArray();
            $total = Cart::session($rs->type)->getTotal();

            if ($rs->type == 5) {
                $data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->user_name);
                $gv_customer = $data_gv->sum_gv;
                $gv = $gv_customer;
                $gv_total = $gv_customer - ($rs->price + $rs->shipping);

                if ($gv_total < 0) {
                    $price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));
                } else {
                    $price_remove_gv = 0;
                }
            } else {
                $gv = null;
                $price_remove_gv = null;
            }

            $orderstatus_id = 2;
            $rs_update_rontstore_and_address = PaymentSentAddressOrder::update_order_and_address($rs, $code_order, $customer_id, $business_location_id, $orderstatus_id, $gv, $price_remove_gv);

            if ($rs_update_rontstore_and_address['status'] == 'fail') {
                DB::rollback();
                return $rs_update_rontstore_and_address;
            } else {
                $id = $rs_update_rontstore_and_address['id'];
            }

            if ($rs->type == 4) { //เติม Ai-Stockist
              $transection_code = RunNumberPayment::run_number_aistockis();
                $ai_stockist = DB::table('ai_stockist')->insert(
                    ['customer_id' => $customer_id,
                        'to_customer_id' => $customer_id,
                        'transection_code' => $transection_code,
                        'set_transection_code' => date('ym'),
                        'order_id_fk' => $rs_update_rontstore_and_address['id'],
                        'pv' => $rs->pv_total,
                        'type_id' => $rs->type,
                        'status' => 'panding',
                        'detail' => 'Payment Add Ai-Stockist',
                    ]);
            }

            if ($rs->type == 5) {

                $price_total = ($rs->price + $rs->shipping);
                $rs_log_gift = GiftVoucher::log_gift($price_total, $customer_id, $id);

                if ($rs_log_gift['status'] != 'success') {
                    DB::rollback();
                    $resule = ['status' => 'fail', 'message' => 'rs_log_gift fail'];
                    return $resule;
                }

            }

            $file_slip = $rs->file_slip;
            if (isset($file_slip)) {
                $url = 'local/public/files_slip/' . date('Ym');

                $f_name = date('YmdHis') . '_' . $customer_id . '.' . $file_slip->getClientOriginalExtension();
                if ($file_slip->move($url, $f_name)) {
                    DB::table('payment_slip')
                        ->insert(['customer_id' => $customer_id, 'url' => $url, 'file' => $f_name, 'order_id' => $id]);
                }
            }

            $resule = PaymentAddProduct::payment_add_product($id, $customer_id, $rs->type, $business_location_id, $rs->pv_total);

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

    public static function payment_not_uploadfile($rs)
    {
        DB::BeginTransaction();
        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        $customer_id = Auth::guard('c_user')->user()->id;

        $code_order = RunNumberPayment::run_number_order($business_location_id);


        try {
            $cartCollection = Cart::session($rs->type)->getContent();
            $data = $cartCollection->toArray();
            $total = Cart::session($rs->type)->getTotal();

            if ($rs->type == 5) {
                $data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->user_name);
                $gv_customer = $data_gv->sum_gv;

                $gv_total = $gv_customer - ($rs->price + $rs->shipping);
                $gv = $gv_customer;
                if ($gv_total < 0) {
                    $price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));
                } else {
                    $price_remove_gv = 0;
                }

            } else {
                $gv = null;
                $price_remove_gv = null;
            }

            $orderstatus_id = 1;
            $rs_update_rontstore_and_address = PaymentSentAddressOrder::update_order_and_address($rs, $code_order, $customer_id, $business_location_id, $orderstatus_id, $gv, $price_remove_gv);
            if ($rs_update_rontstore_and_address['status'] == 'fail') {
                DB::rollback();
                return $rs_update_rontstore_and_address;
            } else {
                $id = $rs_update_rontstore_and_address['id'];
            }

            if ($rs->type == 4) { //เติม Ai-Stockist
              $transection_code = RunNumberPayment::run_number_aistockis();
                $ai_stockist = DB::table('ai_stockist')->insert(
                    ['customer_id' => $customer_id,
                        'to_customer_id' => $customer_id,
                        'transection_code' => $transection_code,
                        'set_transection_code' => date('ym'),
                        'order_id_fk' => $id,
                        'pv' => $rs->pv_total,
                        'type_id' => $rs->type,
                        'status' => 'panding',
                        'detail' => 'Payment Add Ai-Stockist',
                    ]);
            }

            if ($rs->type == 5) {
                $price_total = ($rs->price + $rs->shipping);
                $rs_log_gift = GiftVoucher::log_gift($price_total, $customer_id, $id);

                if ($rs_log_gift['status'] != 'success') {
                    DB::rollback();
                    $resule = ['status' => 'fail', 'message' => 'rs_log_gift fail'];
                    return $resule;
                }

            }

            $resule = PaymentAddProduct::payment_add_product($id, $customer_id, $rs->type, $business_location_id, $rs->pv_total);


            //$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย','order_id'=>$id];
            //return $resule;
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

    public static function credit_card($rs)
    {


        DB::BeginTransaction();
        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        $customer_id = Auth::guard('c_user')->user()->id;

        $code_order = RunNumberPayment::run_number_order($business_location_id);

        try {
            $cartCollection = Cart::session($rs->type)->getContent();
            $data = $cartCollection->toArray();
            $total = Cart::session($rs->type)->getTotal();

            if ($rs->type == 5) {
                $data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->user_name);
                $gv_customer = $data_gv->sum_gv;
                $gv = $gv_customer;

                $gv_total = $gv_customer - ($rs->price + $rs->shipping);
                if ($gv_total < 0) {
                    $price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));
                } else {
                    $price_remove_gv = 0;
                }
            } else {
                $gv = null;
                $price_remove_gv = null;

            }

            $orderstatus_id = 5;
            $rs_update_rontstore_and_address = PaymentSentAddressOrder::update_order_and_address($rs, $code_order, $customer_id, $business_location_id, $orderstatus_id, $gv, $price_remove_gv);

            if ($rs_update_rontstore_and_address['status'] == 'fail') {
                DB::rollback();
                return $rs_update_rontstore_and_address;
            } else {
                $id = $rs_update_rontstore_and_address['id'];
            }

            if ($rs->type == 4) { //เติม Ai-Stockist
              $transection_code = RunNumberPayment::run_number_aistockis();
              $ai_stockist = DB::table('ai_stockist')->insert(
                ['customer_id' => $customer_id,
                    'to_customer_id' => $customer_id,
                    'transection_code' => $transection_code,
                    'set_transection_code' => date('ym'),
                    'order_id_fk' => $id,
                    'pv' => $rs->pv_total,
                    'type_id' => $rs->type,
                    'status' => 'panding',
                    'detail' => 'Payment Add Ai-Stockist',
                ]);
            }

            if ($rs->type == 5) {
                $price_total = ($rs->price + $rs->shipping);
                $rs_log_gift = GiftVoucher::log_gift($price_total, $customer_id, $id);

                if ($rs_log_gift['status'] != 'success') {
                    DB::rollback();
                    $resule = ['status' => 'fail', 'message' => 'rs_log_gift fail'];
                    return $resule;
                }

            }

            $resule = PaymentAddProduct::payment_add_product($id, $customer_id, $rs->type, $business_location_id,$rs->pv_total);

            if ($resule['status'] == 'success') {
                $resulePv = Pvpayment::PvPayment_type_confirme($id,$customer_id,'2','customer');

                if ($resulePv['status'] == 'success') {
                    DB::commit();
                    return $resulePv;

                } else {
                    DB::rollback();
                    return $resulePv;
                }

            } else {
                DB::rollback();
                return $resule;

            }
        } catch (Exception $e) {
            DB::rollback();
            return $e;

        }
    }

    public static function ai_cash($rs)
    {
        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        $customer_id = Auth::guard('c_user')->user()->id;
        $code_order = RunNumberPayment::run_number_order($business_location_id);

        try {

            $cartCollection = Cart::session($rs->type)->getContent();
            $data = $cartCollection->toArray();
            $total = Cart::session($rs->type)->getTotal();

            if ($rs->type == 5) {
                $data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
                $gv_customer = $data_gv->sum_gv;
                $gv = $gv_customer;

                $gv_total = $gv_customer - ($rs->price + $rs->shipping);

                if ($gv_total < 0) {
                    $price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));
                } else {
                    $price_remove_gv = 0;
                }
            } else {
                $gv = null;
                $price_remove_gv = null;

            }

            $orderstatus_id = 5;
            $rs_update_rontstore_and_address = PaymentSentAddressOrder::update_order_and_address($rs, $code_order, $customer_id, $business_location_id, $orderstatus_id, $gv, $price_remove_gv);

            if ($rs_update_rontstore_and_address['status'] == 'fail') {
                DB::rollback();
                return $rs_update_rontstore_and_address;
            } else {
                $id = $rs_update_rontstore_and_address['id'];
            }

            if ($rs->type == 4) { //เติม Ai-Stockist
              $transection_code = RunNumberPayment::run_number_aistockis();
                $ai_stockist = DB::table('ai_stockist')->insert(
                    ['customer_id' => $customer_id,
                        'to_customer_id' => $customer_id,
                        'transection_code' => $transection_code,
                        'set_transection_code' => date('ym'),
                        'order_id_fk' => $id,
                        'pv' => $rs->pv_total,
                        'type_id' => $rs->type,
                        'status' => 'panding',
                        'detail' => 'Payment Add Ai-Stockist',
                    ]);

            }

            if ($rs->type == 5) {
                $price_total = ($rs->price + $rs->shipping);
                $rs_log_gift = GiftVoucher::log_gift($price_total, $customer_id, $id);

                if ($rs_log_gift['status'] != 'success') {
                    DB::rollback();
                    $resule = ['status' => 'fail', 'message' => 'rs_log_gift fail'];
                    return $resule;
                }

            }

            $resule = PaymentAddProduct::payment_add_product($id, $customer_id, $rs->type, $business_location_id, $rs->pv_total);

            if ($resule['status'] == 'success') {
                $resulePv = Pvpayment::PvPayment_type_confirme($id,$customer_id,'2','customer');
                if ($resulePv['status'] == 'success') {
                    DB::commit();
                    return $resulePv;

                } else {
                    DB::rollback();
                    return $resulePv;
                }

            } else {
                DB::rollback();
                return $resule;

            }

        } catch (Exception $e) {
            DB::rollback();
            return $e;

        }
    }

    public static function gift_voucher($rs)
    {
        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        $customer_id = Auth::guard('c_user')->user()->id;
        $code_order = RunNumberPayment::run_number_order($business_location_id);

        $data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->user_name);
        $gv_customer = $data_gv->sum_gv;

        DB::BeginTransaction();
        try {

            $cartCollection = Cart::session($rs->type)->getContent();
            $data = $cartCollection->toArray();
            $total = Cart::session($rs->type)->getTotal();

            $gv_total = $gv_customer - ($rs->price + $rs->shipping);
            $gv = $gv_customer;

            if ($gv_total < 0) {
                $price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));

            } else {
                $price_remove_gv = 0;
            }

            $orderstatus_id = 2;
            $rs_update_rontstore_and_address = PaymentSentAddressOrder::update_order_and_address($rs, $code_order, $customer_id, $business_location_id, $orderstatus_id, $gv, $price_remove_gv);

            if ($rs_update_rontstore_and_address['status'] == 'fail') {
                DB::rollback();
                return $rs_update_rontstore_and_address;
            } else {
                $id = $rs_update_rontstore_and_address['id'];
            }

            $price_total = ($rs->price + $rs->shipping);
            $rs_log_gift = GiftVoucher::log_gift($price_total, $customer_id, $id);

            if ($rs_log_gift['status'] != 'success') {
                DB::rollback();
                $resule = ['status' => 'fail', 'message' => 'rs_log_gift fail'];
                return $resule;
            }

            $resule = PaymentAddProduct::payment_add_product($id, $customer_id, $rs->type, $business_location_id, $rs->pv_total);

            if ($resule['status'] == 'success') {
                $resulePv = Pvpayment::PvPayment_type_confirme($id,$customer_id,'2','customer');
                if ($resulePv['status'] == 'success') {
                    DB::commit();
                    return $resulePv;

                } else {
                    DB::rollback();
                    return $resulePv;
                }

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
