<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\HistoryController;
use App\Http\Controllers\Frontend\Fc\GiveawayController;
use App\Http\Controllers\Frontend\Fc\ShippingCosController;
use App\Http\Controllers\Frontend\Ksher\KsherController;
use App\Models\Frontend\CourseCheckRegis;
use App\Models\Frontend\GiftVoucher;
use App\Models\Frontend\Location;
use App\Models\Frontend\Payment;
use App\Models\Frontend\PaymentAddProduct;
use App\Models\Frontend\PaymentAiCash;
use App\Models\Frontend\PaymentCourse;
use App\Models\Frontend\PaymentSentAddressOrder;
use App\Models\Frontend\RunNumberPayment;

use Auth;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartPaymentController extends Controller
{
    public function index($type)
    {
        $business_location_id = Auth::guard('c_user')->user()->business_location_id;

        $location = Location::location($business_location_id, $business_location_id);
        $cartCollection = Cart::session($type)->getContent();
        $data = $cartCollection->toArray();
        $quantity = Cart::session($type)->getTotalQuantity();
        $customer_id = Auth::guard('c_user')->user()->id;
        $user_name = Auth::guard('c_user')->user()->user_name;

        if ($data) {
            foreach ($data as $value) {
                $pv[] = $value['quantity'] * $value['attributes']['pv'];
                if ($type == '6') {
                    $chek_course = CourseCheckRegis::cart_check_register($value['id'], $value['quantity']);
                    if ($chek_course['status'] == 'fail') {
                        return redirect('cart/' . $type)->withError($chek_course['message']);
                    }
                }
            }
            $pv_total = array_sum($pv);
        } else {
            $pv_total = 0;
            return redirect('product-list/' . $type)->withError('กรุณาเลือกสินค้าที่ต้องการ');
        }

        //ราคาสินค้า
        $price = Cart::session($type)->getTotal();

        $province_data = DB::table('customers_detail')
            ->select('province_id_fk')
            ->where('customer_id', '=', $customer_id)
            ->first();

        $data_shipping = ShippingCosController::fc_check_shipping_cos($business_location_id, $province_data->province_id_fk, $price);

        $vat = DB::table('dataset_vat')
            ->where('business_location_id_fk', '=', $business_location_id)
            ->first();

        $vat = $vat->vat;
        $shipping = $data_shipping['data']->shipping_cost;

        //vatใน 7%
        $p_vat = $price * ($vat / (100 + $vat));

        //มูลค่าสินค้า
        $price_vat = $price - $p_vat;

        $price_total = $price + $shipping;

        if ($type == 5) {
            $data_gv = \App\Helpers\Frontend::get_gitfvoucher($user_name);
            $gv = $data_gv->sum_gv;
            $gv_total = $gv - $price_total;

            if ($gv_total < 0) {
                $gv_total = 0;
                $price_total_type5 = abs($gv - $price_total);
                $gift_voucher_price = $gv;
            } else {
                $gift_voucher_price = $price_total;
                $gv_total = $gv_total;
                $price_total_type5 = 0;
            }
        } else {
            $gift_voucher_price = null;
            $gv_total = null;
            $price_total_type5 = null;
        }

        $customer_pv = Auth::guard('c_user')->user()->pv;
        $check_giveaway = GiveawayController::check_giveaway($business_location_id, $type, $customer_pv, $pv_total);

        $bill = array(
            'vat' => $vat,
            'shipping' => $shipping,
            'price' => $price,
            'p_vat' => $p_vat,
            'price_vat' => $price_vat,
            'price_total' => $price_total,
            'pv_total' => $pv_total,
            'data' => $data,
            'quantity' => $quantity,
            'gv_total' => $gv_total,
            'price_total_type5' => $price_total_type5,
            'gift_voucher_price' => $gift_voucher_price,
            'type' => $type,
            'location_id' => $business_location_id,
            'status' => 'success',
        );

        $customer = DB::table('customers')
            ->where('id', '=', Auth::guard('c_user')->user()->id)
            ->first();

        $address = DB::table('customers_detail')
            ->select('customers_detail.*', 'dataset_provinces.id as provinces_id', 'dataset_provinces.name_th as provinces_name', 'dataset_amphures.name_th as amphures_name', 'dataset_amphures.id as amphures_id', 'dataset_districts.id as district_id', 'dataset_districts.name_th as district_name')
            ->leftjoin('dataset_provinces', 'dataset_provinces.id', '=', 'customers_detail.province_id_fk')
            ->leftjoin('dataset_amphures', 'dataset_amphures.id', '=', 'customers_detail.amphures_id_fk')
            ->leftjoin('dataset_districts', 'dataset_districts.id', '=', 'customers_detail.district_id_fk')
            ->where('customer_id', '=', $customer_id)
            ->first();

        $address_card = DB::table('customers_address_card')
            ->select('customers_address_card.*', 'dataset_provinces.id as provinces_id', 'dataset_provinces.name_th as card_provinces_name', 'dataset_amphures.name_th as card_amphures_name', 'dataset_amphures.id as card_amphures_id', 'dataset_districts.id as card_district_id', 'dataset_districts.name_th as card_district_name')
            ->leftjoin('dataset_provinces', 'dataset_provinces.id', '=', 'customers_address_card.card_province_id_fk')
            ->leftjoin('dataset_amphures', 'dataset_amphures.id', '=', 'customers_address_card.card_amphures_id_fk')
            ->leftjoin('dataset_districts', 'dataset_districts.id', '=', 'customers_address_card.card_district_id_fk')
            ->where('customer_id', '=', $customer_id)
            ->first();

        $provinces = DB::table('dataset_provinces')
            ->select('*')
            ->get();

        return view('frontend/product/cart_payment', compact('check_giveaway', 'customer', 'address', 'address_card', 'location', 'provinces', 'bill'));
    }

    public function payment_submit(Request $request)
    {
        if ($request->submit == 'upload') {
          $resule = Payment::payment_uploadfile($request);
            if ($resule['status'] == 'success') {
                return redirect('product-history')->withSuccess($resule['message']);
            } elseif ($resule['status'] == 'fail') {
                return redirect('cart_payment_transfer/' . $request->code_order)->withError($resule['message']);
            } else {
                return redirect('cart_payment_transfer/' . $request->code_order)->withError('Data is Null');
            }
        } elseif ($request->submit == 'not_upload') {
                return redirect('product-history');

        } elseif ($request->submit == 'PromptPay') {
            $request['pay_type'] = 15;

                $business_location_id = Auth::guard('c_user')->user()->business_location_id;

                $order_data = DB::table('db_orders')
                    ->select('db_orders.*', 'dataset_orders_type.orders_type as type', 'dataset_pay_type.detail as pay_type_name')
                    ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
                    ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
                    ->where('dataset_orders_type.lang_id', '=', $business_location_id)
                    ->where('db_orders.id', '=', $request->id)
                    ->first();

                $gateway_pay_data = array(
                    'mch_order_no' => $order_data->code_order,
                    "total_fee" => $order_data->total_price,
                    "fee_type" => 'THB',
                    "channel_list" => 'promptpay',
                    'mch_code' => $order_data->code_order,
                    'product_name' => $order_data->type,
                );

                $data = KsherController::gateway_ksher($gateway_pay_data);
                //targetUrl
                if ($data['status'] == 'success') {
                    return redirect($data['url']);
                } else {
                    return redirect('product-history')->withError('Payment Fail');
                }

        } elseif ($request->submit == 'TrueMoney') {
            $request['pay_type'] = 16;
                $business_location_id = Auth::guard('c_user')->user()->business_location_id;
                $order_data = DB::table('db_orders')
                    ->select('db_orders.*', 'dataset_orders_type.orders_type as type', 'dataset_pay_type.detail as pay_type_name')
                    ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
                    ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
                    ->where('dataset_orders_type.lang_id', '=', $business_location_id)
                    ->where('db_orders.id', '=', $request->id)
                    ->first();

                $gateway_pay_data = array(
                    'mch_order_no' => $order_data->code_order,
                    "total_fee" => $order_data->total_price,
                    "fee_type" => 'THB',
                    "channel_list" => 'truemoney',
                    'mch_code' => $order_data->code_order,
                    'product_name' => $order_data->type,
                );

                $data = KsherController::gateway_ksher($gateway_pay_data);
                //targetUrl
                if ($data['status'] == 'success') {
                    return redirect($data['url']);
                } else {
                    return redirect('product-history')->withError('Payment Fail');
                }

        } elseif ($request->submit == 'credit_card') {
          dd('coming soon');
        } elseif ($request->submit == 'ai_cash') {

          $resule = Payment::ai_cash($request);

            if ($resule['status'] == 'success') {
                return redirect('product-history')->withSuccess($resule['message']);
            } elseif ($resule['status'] == 'fail') {
              return redirect('cart_payment_transfer/'. $request->code_order)->withError($resule['message']);
            } else {
              return redirect('cart_payment_transfer/'. $request->code_order)->withError('Data is Null');
            }

        } elseif ($request->submit == 'gift_voucher') {
            $resule = Payment::gift_voucher($request);
            if ($resule['status'] == 'success') {
                return redirect('product-history')->withSuccess($resule['message']);
            } elseif ($resule['status'] == 'fail') {
                return redirect('cart_payment_transfer/' . $request->code_order)->withError($resule['message']);
            } else {
                return redirect('cart_payment_transfer/' . $request->code_order)->withError('Data is Null');
            }
        } else {
            return redirect('product-history')->withError('Payment submit Fail');
        }
    }

    public function payment_address(Request $rs)
    {

        DB::BeginTransaction();
        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        $customer_id = Auth::guard('c_user')->user()->id;

        $code_order = RunNumberPayment::run_number_order($business_location_id);

        try {
            $cartCollection = Cart::session($rs->type)->getContent();
            $data = $cartCollection->toArray();
            $total = Cart::session($rs->type)->getTotal();
            $quantity = Cart::session($rs->type)->getTotalQuantity();

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
            $rs_update_rontstore_and_address = PaymentSentAddressOrder::update_order_and_address($rs, $code_order, $customer_id, $business_location_id, $orderstatus_id, $gv, $price_remove_gv, $quantity);
            if ($rs_update_rontstore_and_address['status'] == 'fail') {
                DB::rollback();
                return redirect('product-history')->withError($rs_update_rontstore_and_address['message']);
            } else {
                $id = $rs_update_rontstore_and_address['id'];
            }

            if ($rs->type == 4) { //เติม Ai-Stockist
                $transection_code = RunNumberPayment::run_number_aistockis();
                $ai_stockist = DB::table('ai_stockist')->insert(
                    [
                        'customer_id' => $customer_id,
                        'to_customer_id' => $customer_id,
                        'transection_code' => $transection_code,
                        'set_transection_code' => date('ym'),
                        'order_id_fk' => $id,
                        'pv' => $rs->pv_total,
                        'type_id' => $rs->type,
                        'status' => 'panding',
                        'detail' => 'Payment Add Ai-Stockist',
                    ]
                );
            }

            if ($rs->type == 5) {
                $price_total = ($rs->price + $rs->shipping);
                $rs_log_gift = GiftVoucher::log_gift($price_total, $customer_id, $id);

                if ($rs_log_gift['status'] != 'success') {
                    DB::rollback();
                    $resule = ['status' => 'fail', 'message' => 'rs_log_gift fail'];
                    return redirect('product-history')->withError($rs_log_gift['message']);
                }
            }

            $resule = PaymentAddProduct::payment_add_product($id, $customer_id, $rs->type, $business_location_id, $rs->pv_total);

            if ($resule['status'] == 'success') {

                DB::commit();
                Cart::session($rs->type)->clear();

                return redirect('cart_payment_transfer/'.$code_order);
                return $resule;
            } else {

                DB::rollback();
                return redirect('product-history')->withError($resule['message']);
            }
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function cart_payment_transfer($code_order)
    {
        if ($code_order) {

            $data = DB::table('db_orders')
            ->select('db_orders.*', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type',
                'branchs.b_name as office_name',
                'branchs.house_no as office_house_no',
                'branchs.b_name as office_house_name',
                'branchs.moo as office_moo',
                'branchs.soi as office_soi',
                'branchs.amphures_id_fk as office_amphures',
                'branchs.district_id_fk as office_district',
                'branchs.road as office_road',
                'branchs.province_id_fk as office_province',
                'branchs.zipcode as office_zipcode',
                'branchs.tel as office_tel',
                'branchs.email as office_email',
                'db_invoice_code.order_payment_code',
                'dataset_pay_type.detail as pay_type_name', 'dataset_provinces.name_th as provinces_name', 'dataset_amphures.name_th as amphures_name', 'dataset_districts.name_th as district_name')
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
            ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
            ->leftjoin('branchs', 'branchs.business_location_id_fk', '=', 'db_orders.branch_id_fk')
            ->leftjoin('db_invoice_code', 'db_invoice_code.order_id', '=', 'db_orders.id')
            ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')

            ->leftjoin('dataset_provinces', 'dataset_provinces.id', '=', 'db_orders.province_id_fk')
            ->leftjoin('dataset_amphures', 'dataset_amphures.id', '=', 'db_orders.amphures_id_fk')
            ->leftjoin('dataset_districts', 'dataset_districts.id', '=', 'db_orders.district_id_fk')

            ->where('dataset_order_status.lang_id', '=', '1')
            ->where('dataset_orders_type.lang_id', '=', '1')
            ->where('db_orders.code_order', '=', $code_order)
            ->first();

            if(empty($data)){
              return redirect('product-history')->withError('ไม่พบบิลเลขที่ ' . $code_order . ' อยู่ในระบบรอชำระเงิน');
            }

        if ($data->delivery_location_frontend == 'sent_address') {
            $address = HistoryController::address($data->name, $data->tel, $data->email, $data->house_no, $data->moo, $data->house_name, $data->soi, $data->road, $data->district_name, $data->amphures_name, $data->provinces_name, $data->zipcode);

        } elseif ($data->delivery_location_frontend == 'sent_address_card') {

            $address = HistoryController::address($data->name, $data->tel, $data->email, $data->house_no, $data->moo, $data->house_name, $data->soi, $data->road, $data->district_name, $data->amphures_name, $data->provinces_name, $data->zipcode);

        } elseif ($data->delivery_location_frontend == 'sent_office') {
            $address = HistoryController::address($data->name, $data->tel, $data->email, $data->office_house_no, $data->office_moo, $data->office_name, $data->office_soi, $data->office_road, $data->office_province, $data->office_amphures, $data->office_district, $data->office_zipcode);

        } elseif ($data->delivery_location_frontend == 'sent_address_other') {
            $address = HistoryController::address($data->name, $data->tel, $data->email, $data->house_no, $data->moo, $data->house_name, $data->soi, $data->road, $data->district_name, $data->amphures_name, $data->provinces_name, $data->zipcode);
        } else {
            $address = '';
        }
        // dd($data);

        if ($data->purchase_type_id_fk == 6) {
            $order_items = DB::table('db_order_products_list')
                ->select('db_order_products_list.*', 'course_ticket_number.ticket_number')
                ->where('frontstore_id_fk', '=',$data->id)
                ->leftjoin('course_event_regis', 'course_event_regis.order_item_id', '=', 'db_order_products_list.id')
                ->leftjoin('course_ticket_number','course_ticket_number.id', '=', 'course_event_regis.ticket_id')
                ->get();
        } else {
            $order_items = DB::table('db_order_products_list')
                ->where('frontstore_id_fk', '=', $data->id)
                ->orderby('id', 'ASC')
                ->get();
        }


                return view('frontend/product/cart_payment_transfer',compact('data','order_items','address'));



        } else {
            return redirect('product-history')->withError('ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่');
        }
    }
}
