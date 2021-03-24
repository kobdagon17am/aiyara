<?php
namespace App\Models\Frontend;

use App\Http\Controllers\Frontend\Fc\ShippingCosController;
use App\Models\Db_Orders;
use DB;
use Illuminate\Database\Eloquent\Model;
class PaymentSentAddressOrder extends Model
{

    public static function update_fontstore_and_address($rs, $code_order, $customer_id, $business_location_id, $orderstatus_id, $gv, $price_remove_gv)
    {

        try {

            $insert_db_orders = new Db_Orders();
            $insert_db_orders->code_order = $code_order;
            $insert_db_orders->customers_id_fk = $customer_id;
            $insert_db_orders->vat = $rs->vat;

            $insert_db_orders->sum_price = $rs->price;
            // 'price_vat = $rs->price_vat;
            // 'p_vat'  => $rs->p_vat;
            $insert_db_orders->pv_total = $rs->pv_total;
            $insert_db_orders->gift_voucher = $gv; //gvที่ใช้
            $insert_db_orders->price_remove_gv = $price_remove_gv; //ราคาที่ต้องจ่ายเพิ่ม
            $insert_db_orders->orders_type_id_fk = $rs->type;
            $insert_db_orders->pay_type_id_fk = $rs->pay_type;
            $insert_db_orders->order_status_id_fk = $orderstatus_id;
            $insert_db_orders->date_setting_code = date('ym');

            $insert_db_orders->delivery_location_status = $rs->receive;
            $insert_db_orders->business_location_id_fk = $business_location_id;
            $insert_db_orders->sentto_branch_id = 0;
            //$insert_db_orders->product_value = 0;

            if ($rs->shipping_premium == 'true') {
                $insert_db_orders->shipping_special = 1;
            }

            if ($rs->receive == 'sent_address') {
                $data_shipping = ShippingCosController::fc_check_shipping_cos($business_location_id, $rs->card_province, $rs->price, $rs->shipping_premium);
                $shipping = $data_shipping['data']->shipping_cost;

                $insert_db_orders->shipping_price = $shipping;
                $insert_db_orders->shipping_cost_type_id = $data_shipping['data']->shipping_type_id;
                $insert_db_orders->shipping_cost_detail = $data_shipping['data']->shipping_name;

                $insert_db_orders->house_no = $rs->house_no;
                $insert_db_orders->house_name = $rs->house_name;
                $insert_db_orders->moo = $rs->moo;
                $insert_db_orders->soi = $rs->soi;
                $insert_db_orders->district = $rs->district;
                $insert_db_orders->district_sub = $rs->district_sub;
                $insert_db_orders->road = $rs->road;
                $insert_db_orders->province = $rs->province;
                $insert_db_orders->zipcode = $rs->zipcode;
                $insert_db_orders->tel = $rs->tel_mobile;
                $insert_db_orders->name = $rs->name;
                $insert_db_orders->email = $rs->email;
            } elseif ($rs->receive == 'sent_address_card') {

                $data_shipping = ShippingCosController::fc_check_shipping_cos($business_location_id, $rs->card_province, $rs->price, $rs->shipping_premium);
                $shipping = $data_shipping['data']->shipping_cost;

                $insert_db_orders->shipping_price = $shipping;
                $insert_db_orders->shipping_cost_type_id = $data_shipping['data']->shipping_type_id;
                $insert_db_orders->shipping_cost_detail = $data_shipping['data']->shipping_name;

                $insert_db_orders->house_no = $rs->card_house_no;
                $insert_db_orders->house_name = $rs->card_house_name;
                $insert_db_orders->moo = $rs->card_moo;
                $insert_db_orders->soi = $rs->card_soi;
                $insert_db_orders->district = $rs->card_district;
                $insert_db_orders->district_sub = $rs->card_district_sub;
                $insert_db_orders->road = $rs->card_road;
                $insert_db_orders->province = $rs->card_province;
                $insert_db_orders->zipcode = $rs->card_zipcode;
                $insert_db_orders->tel = $rs->card_tel_mobile;
                $insert_db_orders->name = $rs->card_name;
                $insert_db_orders->email = $rs->card_email;
            } elseif ($rs->receive == 'sent_address_other') {

                $data_shipping = ShippingCosController::fc_check_shipping_cos($business_location_id, $rs->other_province, $rs->price, $rs->shipping_premium);
                $shipping = $data_shipping['data']->shipping_cost;

                $insert_db_orders->shipping_price = $shipping;
                $insert_db_orders->shipping_cost_type_id = $data_shipping['data']->shipping_type_id;
                $insert_db_orders->shipping_cost_detail = $data_shipping['data']->shipping_name;

                $insert_db_orders->house_no = $rs->other_house_no;
                $insert_db_orders->house_name = $rs->other_house_name;
                $insert_db_orders->moo = $rs->other_moo;
                $insert_db_orders->soi = $rs->other_soi;
                $insert_db_orders->district = $rs->other_district;
                $insert_db_orders->district_sub = $rs->other_district_sub;
                $insert_db_orders->road = $rs->other_road;
                $insert_db_orders->province = $rs->other_province;
                $insert_db_orders->zipcode = $rs->other_zipcode;

                $insert_db_orders->tel = $rs->other_tel_mobile;
                $insert_db_orders->name = $rs->other_name;
                $insert_db_orders->email = $rs->other_email;

            } elseif ($rs->receive == 'sent_office') {
                $insert_db_orders->shipping_price = 0;
                $insert_db_orders->tel = $rs->tel_mobile;
                $insert_db_orders->name = $rs->office_name;
                $insert_db_orders->email = $rs->office_email;
                $insert_db_orders->sentto_branch_id = $rs->receive_location;

            } else {
                $resule = ['status' => 'fail', 'message' => 'Orderstatus Not Type'];

                return $resule;
            }

            $insert_db_orders->save();
            $resule = ['status' => 'success', 'message' => 'Order Update Success', 'id' => $insert_db_orders->id];

            return $resule;

        } catch (Exception $e) {

            $resule = ['status' => 'fail', 'message' => 'Order Update Fail', 'id' => $insert_db_orders->id];
            return $resule;
        }
    }

}
