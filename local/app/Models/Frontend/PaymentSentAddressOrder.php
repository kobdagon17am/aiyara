<?php
namespace App\Models\Frontend;

use App\Http\Controllers\Frontend\Fc\ShippingCosController;
use App\Models\Db_Orders;
use DB;
use Illuminate\Database\Eloquent\Model;

class PaymentSentAddressOrder extends Model
{

    public static function update_order_and_address($rs, $code_order, $customer_id, $business_location_id, $orderstatus_id, $gv, $price_remove_gv,$quantity)
    {



        try {
            DB::BeginTransaction();
            $insert_db_orders = new Db_Orders();
            $insert_db_orders->quantity = $quantity;
            $insert_db_orders->customers_id_fk = $customer_id;
            $insert_db_orders->address_sent_id_fk = $rs->address_sent_id_fk;
            $insert_db_orders->business_location_id_fk = $business_location_id;
            $insert_db_orders->delivery_location_frontend = $rs->receive;
            $insert_db_orders->delivery_province_id = $rs->province_id_fk;
            $insert_db_orders->code_order = $code_order;
            $insert_db_orders->distribution_channel_id_fk = 2;
            $insert_db_orders->purchase_type_id_fk = $rs->type;

            if ($rs->type == '5') { //โอนชำระแบบกิฟวอยเชอ
              $insert_db_orders->gift_voucher_cost = $gv;
              $insert_db_orders->gift_voucher_price = $rs->gift_voucher_price;

            } else {
                $insert_db_orders->pay_type_id_fk = $rs->pay_type;
                if ($rs->pay_type == '1') { //1 โอนเงิน
                    $insert_db_orders->transfer_price = $rs->price_total;
                }

                if ($rs->pay_type == '2') { //2 บัตรเครดิต
                    $insert_db_orders->credit_price = $rs->price_total;

                }

                if ($rs->pay_type == '3') { //3 Ai-Cash
                    $insert_db_orders->aicash_price = $rs->price_total;
                }

                if ($rs->pay_type == '15') { //15 PromptPay
                  $insert_db_orders->prompt_pay_price = $rs->price_total;
                }

               if($rs->pay_type == '16') { //15 TrueMoney
                $insert_db_orders->true_money_price = $rs->price_total;
              }

            }
            // dd($rs->all());
            // //gift_voucher_price

            if ($rs->sent_type_to_customer == 'sent_type_other') {
                $insert_db_orders->status_payment_sent_other = 1;
            }

            $vat = DB::table('dataset_vat')
            ->where('business_location_id_fk', '=', $business_location_id)
            ->first();

            $vat = $vat->vat;

            //$shipping = $data_shipping['data']->shipping_cost;

            //vatใน 7%

            $p_vat = $rs->price * ($vat / (100 + $vat));
            //มูลค่าสินค้า
            $price_vat = $rs->price - $p_vat;


            $insert_db_orders->product_value = $price_vat;
            $insert_db_orders->tax = $rs->vat;
            $insert_db_orders->sum_price = $rs->price;
            $insert_db_orders->total_price = $rs->price_total;
            $insert_db_orders->pv_total = $rs->pv_total;
            $insert_db_orders->order_status_id_fk = $orderstatus_id;
            $insert_db_orders->date_setting_code = date('ym');
            $insert_db_orders->action_date = date('Y-m-d');

            // $insert_db_orders->gift_voucher = $gv; //gvที่ใช้
            // $insert_db_orders->price_remove_gv = $price_remove_gv; //ราคาที่ต้องจ่ายเพิ่ม

            if ($rs->shipping_premium == 'true') {
                $insert_db_orders->shipping_special = 1;
            }

            if ($rs->receive == 'sent_address') {
                $data_shipping = ShippingCosController::fc_check_shipping_cos($business_location_id,$rs->province,$rs->price,$rs->shipping_premium,$rs->receive);
                $shipping = $data_shipping['data']->shipping_cost;

                if ($data_shipping['data']->shipping_type_id == 1) {
                    $insert_db_orders->shipping_free = 1;
                }

                $insert_db_orders->shipping_price = $shipping;
                $insert_db_orders->shipping_cost_id_fk = $data_shipping['data']->shipping_type_id;
                $insert_db_orders->shipping_cost_detail = $data_shipping['data']->shipping_name;

                $insert_db_orders->house_no = $rs->house_no;
                $insert_db_orders->house_name = $rs->house_name;
                $insert_db_orders->moo = $rs->moo;
                $insert_db_orders->soi = $rs->soi;
                $insert_db_orders->amphures_id_fk = $rs->amphures;
                $insert_db_orders->district_id_fk = $rs->district;
                $insert_db_orders->province_id_fk = $rs->province;
                $insert_db_orders->road = $rs->road;
                $insert_db_orders->zipcode = $rs->zipcode;
                $insert_db_orders->email = $rs->email;
                $insert_db_orders->tel = $rs->tel_mobile;
                $insert_db_orders->name = $rs->name;

            } elseif ($rs->receive == 'sent_address_card') {

                $data_shipping = ShippingCosController::fc_check_shipping_cos($business_location_id,$rs->card_province,$rs->price,$rs->shipping_premium,$rs->receive);
                $shipping = $data_shipping['data']->shipping_cost;

                if ($data_shipping['data']->shipping_type_id == 1) {
                    $insert_db_orders->shipping_free = 1;
                }

                $insert_db_orders->shipping_price = $shipping;
                $insert_db_orders->shipping_cost_id_fk = $data_shipping['data']->shipping_type_id;
                $insert_db_orders->shipping_cost_detail = $data_shipping['data']->shipping_name;

                $insert_db_orders->house_no = $rs->card_house_no;
                $insert_db_orders->house_name = $rs->card_house_name;
                $insert_db_orders->moo = $rs->card_moo;
                $insert_db_orders->soi = $rs->card_soi;
                $insert_db_orders->amphures_id_fk = $rs->card_amphures_id_fk;
                $insert_db_orders->district_id_fk = $rs->card_district_id_fk;
                $insert_db_orders->province_id_fk = $rs->card_province_id_fk;
                $insert_db_orders->road = $rs->card_road;
                $insert_db_orders->zipcode = $rs->card_zipcode;
                $insert_db_orders->email = $rs->card_email;
                $insert_db_orders->tel = $rs->card_tel_mobile;
                $insert_db_orders->name = $rs->card_name;

            } elseif ($rs->receive == 'sent_address_other') {

                $data_shipping = ShippingCosController::fc_check_shipping_cos($business_location_id,$rs->other_province,$rs->price,$rs->shipping_premium,$rs->receive);
                $shipping = $data_shipping['data']->shipping_cost;



                if ($data_shipping['data']->shipping_type_id == 1) {
                    $insert_db_orders->shipping_free = 1;
                }
                $insert_db_orders->shipping_price = $shipping;
                $insert_db_orders->shipping_cost_id_fk = $data_shipping['data']->shipping_type_id;
                $insert_db_orders->shipping_cost_detail = $data_shipping['data']->shipping_name;

                $insert_db_orders->house_no = $rs->other_house_no;
                $insert_db_orders->house_name = $rs->other_house_name;
                $insert_db_orders->moo = $rs->other_moo;
                $insert_db_orders->soi = $rs->other_soi;
                $insert_db_orders->amphures_id_fk = $rs->other_amphures_id_fk;
                $insert_db_orders->district_id_fk = $rs->other_district_id_fk;
                $insert_db_orders->province_id_fk = $rs->other_province_id_fk;
                $insert_db_orders->road = $rs->other_road;
                $insert_db_orders->zipcode = $rs->other_zipcode;
                $insert_db_orders->email = $rs->other_email;
                $insert_db_orders->tel = $rs->other_tel_mobile;
                $insert_db_orders->name = $rs->other_name;

            } elseif ($rs->receive == 'sent_office') {
                $insert_db_orders->shipping_price = 0;
                $insert_db_orders->shipping_free = 1;
                $insert_db_orders->tel = $rs->tel_mobile;
                $insert_db_orders->name = $rs->office_name;
                $insert_db_orders->email = $rs->office_email;
                $insert_db_orders->branch_id_fk = $rs->receive_location;
                $insert_db_orders->sentto_branch_id = $rs->receive_location;
                $insert_db_orders->shipping_cost_detail = 'รับที่สาขา / branch';
                $insert_db_orders->shipping_cost_id_fk = 5;

            } else {
                $resule = ['status' => 'fail', 'message' => 'Orderstatus Not Type'];

                return $resule;
            }

            $insert_db_orders->save();
            DB::commit();
            $resule = ['status' => 'success', 'message' => 'Order Update Success', 'id' => $insert_db_orders->id];
            return $resule;

        } catch (Exception $e) {
            DB::rollback();
            $resule = ['status' => 'fail', 'message' => 'Order Update Fail', 'id' => $insert_db_orders->id];
            return $resule;
        }
    }

}
