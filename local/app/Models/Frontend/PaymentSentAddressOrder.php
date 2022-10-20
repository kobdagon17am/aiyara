<?php

namespace App\Models\Frontend;

use App\Http\Controllers\Frontend\Fc\ShippingCosController;
use App\Models\Db_Orders;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Backend\DatasetOrderHistoryStatus;
use App\Models\Backend\DbOrderHistoryLog;

class PaymentSentAddressOrder extends Model
{

  public static function update_order_and_address($rs, $code_order, $customer_id, $business_location_id, $orderstatus_id, $gv, $price_remove_gv, $quantity)
  {


    try {
      DB::BeginTransaction();
      $insert_db_orders = new Db_Orders();
      $insert_db_orders->quantity = $quantity;
      $insert_db_orders->customers_id_fk = $customer_id;
      $insert_db_orders->customers_sent_id_fk = $rs->address_sent_id_fk;
      $insert_db_orders->business_location_id_fk = $business_location_id;
      $insert_db_orders->delivery_location_frontend = $rs->receive;
      $insert_db_orders->delivery_province_id = $rs->province_id_fk;
      $insert_db_orders->code_order = $code_order;
      $insert_db_orders->distribution_channel_id_fk = 3;
      $insert_db_orders->purchase_type_id_fk = $rs->type;
      $insert_db_orders->branch_id_fk = 12;
      $insert_db_orders->check_press_save = 2;
      $insert_db_orders->created_at = date('Y-m-d H:i:s');
      $insert_db_orders->updated_at = date('Y-m-d H:i:s');

      $data_customer = DB::table('customers')
      ->select('user_name','first_name','last_name','business_name')
      ->where('id','=',$customer_id)
      ->first();

      $insert_db_orders->user_name = $data_customer->user_name;
      $insert_db_orders->name_customer = $data_customer->first_name.' '.$data_customer->last_name;
      $insert_db_orders->name_customer_business = $data_customer->business_name;


      if ($rs->aistockist_id_fk) {
        $insert_db_orders->aistockist = $rs->aistockist_id_fk;
      }

      if ($rs->sent_type_to_customer == 'sent_another_bill') {
        $rs->receive = 'sent_another_bill';
      }


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

        if ($rs->pay_type == '16') { //15 TrueMoney
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
      $insert_db_orders->tax =  $p_vat;
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
        // ที่อยู่ผู้รับ>0=รับสินค้าด้วยตัวเอง
        //|1=ที่อยู่ตามบัตร ปชช.>customers_address_card
        // |2=ที่อยู่จัดส่งไปรษณีย์หรือที่อยู่ตามที่ลงทะเบียนไว้ในระบบ>customers_detail
        // |3=ที่อยู่กำหนดเอง>customers_addr_frontstore|4=จัดส่งพร้อมบิลอื่น|5=ส่งแบบพิเศษ/พรีเมี่ยม
        $insert_db_orders->delivery_location = 2;
        $data_shipping = ShippingCosController::fc_check_shipping_cos($business_location_id, $rs->province, $rs->price, $rs->shipping_premium, $rs->receive);
        $shipping = $data_shipping['data']->shipping_cost;

        if ($data_shipping['data']->shipping_type_id == 1) {
          $insert_db_orders->shipping_free = 1;
        }

        $update_tel = DB::table('customers_detail')
          ->where('customer_id', $customer_id)
          ->update([
            'tel_mobile' => $rs->tel_mobile,
          ]);


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

        $insert_db_orders->delivery_location = 1;
        $data_shipping = ShippingCosController::fc_check_shipping_cos($business_location_id, $rs->card_province, $rs->price, $rs->shipping_premium, $rs->receive);
        $shipping = $data_shipping['data']->shipping_cost;

        if ($data_shipping['data']->shipping_type_id == 1) {
          $insert_db_orders->shipping_free = 1;
        }

        $update_tel_card = DB::table('customers_address_card')
          ->where('customer_id', $customer_id)
          ->update([
            'tel' => $rs->tel_mobile,
          ]);

        $insert_db_orders->shipping_price = $shipping;
        $insert_db_orders->shipping_cost_id_fk = $data_shipping['data']->shipping_type_id;
        $insert_db_orders->shipping_cost_detail = $data_shipping['data']->shipping_name;

        $insert_db_orders->house_no = $rs->card_house_no;
        $insert_db_orders->house_name = $rs->card_house_name;
        $insert_db_orders->moo = $rs->card_moo;
        $insert_db_orders->soi = $rs->card_soi;
        $insert_db_orders->amphures_id_fk = $rs->card_amphures;
        $insert_db_orders->district_id_fk = $rs->card_district;
        $insert_db_orders->province_id_fk = $rs->card_province;
        $insert_db_orders->road = $rs->card_road;
        $insert_db_orders->zipcode = $rs->card_zipcode;
        $insert_db_orders->email = $rs->card_email;
        $insert_db_orders->tel = $rs->card_tel_mobile;
        $insert_db_orders->name = $rs->card_name;
      } elseif ($rs->receive == 'sent_address_other') {


        $insert_db_orders->delivery_location = 3;
        $data_shipping = ShippingCosController::fc_check_shipping_cos($business_location_id, $rs->other_province, $rs->price, $rs->shipping_premium, $rs->receive);
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
        $insert_db_orders->amphures_id_fk = $rs->other_amphures;
        $insert_db_orders->district_id_fk = $rs->other_district;
        $insert_db_orders->province_id_fk = $rs->other_province;
        $insert_db_orders->road = $rs->other_road;
        $insert_db_orders->zipcode = $rs->other_zipcode;
        $insert_db_orders->email = $rs->other_email;
        $insert_db_orders->tel = $rs->other_tel_mobile;
        $insert_db_orders->name = $rs->other_name;
      } elseif ($rs->receive == 'sent_office') {

        // ที่อยู่ผู้รับ>0=รับสินค้าด้วยตัวเอง
        //|1=ที่อยู่ตามบัตร ปชช.>customers_address_card
        // |2=ที่อยู่จัดส่งไปรษณีย์หรือที่อยู่ตามที่ลงทะเบียนไว้ในระบบ>customers_detail
        // |3=ที่อยู่กำหนดเอง>customers_addr_frontstore|4=จัดส่งพร้อมบิลอื่น|5=ส่งแบบพิเศษ/พรีเมี่ยม
        $insert_db_orders->delivery_location = 0;
        $insert_db_orders->shipping_price = 0;
        $insert_db_orders->shipping_free = 1;
        $insert_db_orders->tel = $rs->receive_tel_mobile;
        $insert_db_orders->name = $rs->office_name;
        $insert_db_orders->email = $rs->office_email;
        $insert_db_orders->branch_id_fk = $rs->receive_location;
        $insert_db_orders->sentto_branch_id = $rs->receive_location;
        $insert_db_orders->shipping_cost_detail = 'รับที่สาขา / branch';
        $insert_db_orders->shipping_cost_id_fk = 5;
      } elseif ($rs->receive == 'sent_another_bill') { //ส่งพร้อมบิลอื่น
        // ที่อยู่ผู้รับ>0=รับสินค้าด้วยตัวเอง
        //|1=ที่อยู่ตามบัตร ปชช.>customers_address_card
        // |2=ที่อยู่จัดส่งไปรษณีย์หรือที่อยู่ตามที่ลงทะเบียนไว้ในระบบ>customers_detail
        // |3=ที่อยู่กำหนดเอง>customers_addr_frontstore|4=จัดส่งพร้อมบิลอื่น|5=ส่งแบบพิเศษ/พรีเมี่ยม
        $insert_db_orders->delivery_location = 4;
        $data_shipping = ShippingCosController::fc_check_shipping_cos($business_location_id, $rs->province, $rs->price, $rs->shipping_premium, $rs->receive);
        $shipping = $data_shipping['data']->shipping_cost;

        if ($data_shipping['data']->shipping_type_id == 1) {
          $insert_db_orders->shipping_free = 1;
        }


        $insert_db_orders->shipping_price = $shipping;
        $insert_db_orders->shipping_cost_id_fk = $data_shipping['data']->shipping_type_id;
        $insert_db_orders->shipping_cost_detail = $data_shipping['data']->shipping_name;

        $order_sent_another_bill = DB::table('db_orders')
        ->select('db_orders.*','dataset_provinces.name_th as provinces_name', 'dataset_amphures.name_th as amphures_name', 'dataset_districts.name_th as district_name')
        ->leftjoin('dataset_provinces', 'dataset_provinces.id', '=', 'db_orders.province_id_fk')
        ->leftjoin('dataset_amphures', 'dataset_amphures.id', '=', 'db_orders.amphures_id_fk')
        ->leftjoin('dataset_districts', 'dataset_districts.id', '=', 'db_orders.district_id_fk')
        ->where('db_orders.code_order', '=', $rs->bill_code)
        ->first();

        $insert_db_orders->bill_transfer_other = $rs->bill_code;
        $insert_db_orders->house_no = $order_sent_another_bill->house_no;
        $insert_db_orders->house_name = $order_sent_another_bill->house_name;
        $insert_db_orders->moo = $order_sent_another_bill->moo;
        $insert_db_orders->soi = $order_sent_another_bill->soi;
        $insert_db_orders->amphures_id_fk = $order_sent_another_bill->amphures_id_fk;
        $insert_db_orders->district_id_fk = $order_sent_another_bill->district_id_fk;
        $insert_db_orders->province_id_fk = $order_sent_another_bill->province_id_fk;
        $insert_db_orders->road = $order_sent_another_bill->road;
        $insert_db_orders->zipcode = $order_sent_another_bill->zipcode;
        $insert_db_orders->email = $order_sent_another_bill->email;
        $insert_db_orders->tel = $order_sent_another_bill->tel;
        $insert_db_orders->tel_home = $order_sent_another_bill->tel_home;
        $insert_db_orders->name = $order_sent_another_bill->name;

      } else {


        $resule = ['status' => 'fail', 'message' => 'Orderstatus Not Type'];

        return $resule;
      }

      $insert_db_orders->save();


      if ($rs->receive == 'sent_address_other') {

        // วุฒิเพิ่มมา บันทึกที่อยู่ลงระบุเอง
        DB::insert(" INSERT INTO customers_addr_frontstore (frontstore_id_fk, customer_id, recipient_name, addr_no, province_id_fk , amphur_code, tambon_code, zip_code, tel,tel_home, created_at)
                VALUES
                ('" . @$insert_db_orders->id . "',
                 '" . @$insert_db_orders->customers_id_fk . "',
                 '" . @$rs->other_name . "',
                  '" . @$rs->other_house_no . "',
                   '" . @$rs->other_province . "',
                   '" . @$rs->other_amphures . "',
                   '" . @$rs->other_district . "',
                   '" . @$rs->other_zipcode . "',
                   '" . @$rs->other_tel_mobile . "',
                    '" . @$rs->other_tel_mobile . "',
                   now()
                )
              ");
      }


      if ($insert_db_orders->delivery_location == 1) {
        DB::select(" DELETE FROM customers_addr_sent WHERE receipt_no='" . $insert_db_orders->code_order . "' ");
        $addr = DB::select("SELECT
                                            customers_address_card.id,
                                            customers_address_card.customer_id,
                                            customers_address_card.card_house_no,
                                            customers_address_card.card_house_name,
                                            customers_address_card.card_moo,
                                            customers_address_card.card_zipcode,
                                            customers_address_card.card_soi,
                                            customers_address_card.card_district_id_fk,
                                            customers_address_card.card_amphures_id_fk,
                                            customers_address_card.card_road,
                                            customers_address_card.card_province_id_fk,
                                            customers_address_card.created_at,
                                            customers_address_card.updated_at,
                                            dataset_provinces.name_th AS provname,
                                            dataset_amphures.name_th AS ampname,
                                            dataset_districts.name_th AS tamname,
                                            customers.prefix_name,
                                            customers.first_name,
                                            customers.last_name
                                            FROM
                                            customers_address_card
                                            Left Join dataset_provinces ON customers_address_card.card_province_id_fk= dataset_provinces.id
                                            Left Join dataset_amphures ON customers_address_card.card_amphures_id_fk = dataset_amphures.id
                                            Left Join dataset_districts ON customers_address_card.card_district_id_fk = dataset_districts.id
                                            Left Join customers ON customers_address_card.customer_id = customers.id
                                            where customers_address_card.customer_id = " . ($insert_db_orders->customers_id_fk ? $insert_db_orders->customers_id_fk : 0) . "
                                       ");
        $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (invoice_code,customer_id, recipient_name, house_no, zipcode, amphures_id_fk, district_id_fk, province_id_fk, from_table, from_table_id, receipt_no) VALUES ('" . $insert_db_orders->code_order . "','" . $insert_db_orders->customers_id_fk . "', '" . @$addr[0]->first_name . " " . @$addr[0]->last_name . "','" . @$addr[0]->card_house_no . "','" . @$addr[0]->card_zipcode . "', '" . @$addr[0]->card_amphures_id_fk . "', '" . @$addr[0]->card_district_id_fk . "', '" . @$addr[0]->card_province_id_fk . "', 'customers_address_card', '" . @$addr[0]->id . "','" . $insert_db_orders->code_order . "') ");
        DB::select("UPDATE db_orders SET address_sent_id_fk='" . (DB::getPdo()->lastInsertId()) . "' WHERE (id='" . $insert_db_orders->id . "')");
      }

      if ($insert_db_orders->delivery_location == 2) {

        DB::select(" DELETE FROM customers_addr_sent WHERE receipt_no='" . $insert_db_orders->code_order . "' ");

        $addr = DB::select("SELECT
                                customers_detail.id,
                                customers_detail.customer_id,
                                customers_detail.house_no,
                                customers_detail.house_name,
                                customers_detail.moo,
                                customers_detail.zipcode,
                                customers_detail.soi,
                                customers_detail.amphures_id_fk,
                                customers_detail.district_id_fk,
                                customers_detail.road,
                                customers_detail.province_id_fk,
                                customers.prefix_name,
                                customers.first_name,
                                customers.last_name,
                                dataset_amphures.name_th AS amp_name,
                                            dataset_districts.name_th AS tambon_name,
                                            dataset_provinces.name_th AS province_name
                                FROM
                                customers_detail
                                Left Join customers ON customers_detail.customer_id = customers.id
                                Left Join dataset_amphures ON customers_detail.amphures_id_fk = dataset_amphures.id
                                              Left Join dataset_districts ON customers_detail.district_id_fk = dataset_districts.id
                                              Left Join dataset_provinces ON customers_detail.province_id_fk = dataset_provinces.id
                                WHERE customers_detail.customer_id =
                                 " . $insert_db_orders->customers_id_fk . " ");

        @$recipient_name = @$addr[0]->prefix_name . @$addr[0]->first_name . " " . @$addr[0]->last_name;

        $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (invoice_code,
                              customer_id,
                              recipient_name,
                               house_no,house_name, zipcode,
                               amphures_id_fk, district_id_fk,province_id_fk,
                                from_table, from_table_id, receipt_no) VALUES ( '" . $insert_db_orders->code_order . "',
                                '" . $insert_db_orders->customers_id_fk . "',
                                '" . @$recipient_name . "',
                                '" . @$addr[0]->house_no . "','" . @$addr[0]->house_name . "','" . @$addr[0]->zipcode . "',
                                '" . @$addr[0]->district . "', '" . @$addr[0]->district_sub . "', '" . @$addr[0]->province . "',
                                'customers_detail', '" . @$addr[0]->id . "','" . $insert_db_orders->invoice_code . "') ");

        DB::select("UPDATE db_orders SET address_sent_id_fk='" . (DB::getPdo()->lastInsertId()) . "' WHERE (id='" . $insert_db_orders->id . "')");
      }

      if ($insert_db_orders->delivery_location == 3) {

        DB::select(" DELETE FROM customers_addr_sent WHERE receipt_no='" . $insert_db_orders->invoice_code . "' ");

        $addr = DB::select("select customers_addr_frontstore.* ,dataset_provinces.name_th as provname,
                                    dataset_amphures.name_th as ampname,dataset_districts.name_th as tamname
                                    from customers_addr_frontstore
                                    Left Join dataset_provinces ON customers_addr_frontstore.province_id_fk = dataset_provinces.id
                                    Left Join dataset_amphures ON customers_addr_frontstore.amphur_code = dataset_amphures.id
                                    Left Join dataset_districts ON customers_addr_frontstore.tambon_code = dataset_districts.id
                                    where customers_addr_frontstore.frontstore_id_fk = " . $insert_db_orders->id . " ");

        $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (invoice_code,customer_id, recipient_name, house_no, zipcode,amphures_id_fk,district_id_fk, province_id_fk, from_table, from_table_id, receipt_no) VALUES ('" . $insert_db_orders->code_order . "','" . $insert_db_orders->customers_id_fk . "', '" . @$addr[0]->recipient_name . "','" . @$addr[0]->addr_no . "','" . @$addr[0]->zip_code . "', '" . @$addr[0]->ampname . "', '" . @$addr[0]->tamname . "', '" . @$addr[0]->provname . "', 'customers_addr_frontstore', '" . @$addr[0]->id . "','" . $insert_db_orders->code_order . "') ");

        DB::select("UPDATE db_orders SET address_sent_id_fk='" . (DB::getPdo()->lastInsertId()) . "' WHERE (id='" . $insert_db_orders->id . "')");
      }

      DB::select("UPDATE
              db_delivery_packing_code
              Inner Join db_delivery_packing ON db_delivery_packing_code.id = db_delivery_packing.packing_code
              Inner Join db_delivery ON db_delivery_packing.delivery_id_fk = db_delivery.id
              Inner Join db_orders ON db_delivery.receipt = db_orders.invoice_code
              SET
              db_delivery_packing_code.address_sent_id_fk=db_orders.address_sent_id_fk
              WHERE
              db_orders.invoice_code='" . $insert_db_orders->invoice_code . "' ");

      $r_addr = DB::select("select address_sent_id_fk from db_orders WHERE (id='" . $insert_db_orders->id . "')");

      if ($insert_db_orders->delivery_location != 3) {
        DB::select(" UPDATE
                                        customers_addr_sent
                                        Left Join dataset_amphures ON customers_addr_sent.amphures_id_fk = dataset_amphures.id
                                        left Join dataset_districts ON customers_addr_sent.district_id_fk = dataset_districts.id
                                        LEFT Join dataset_provinces ON customers_addr_sent.province_id_fk = dataset_provinces.id
                                        SET
                                        customers_addr_sent.amphures=dataset_amphures.name_th,
                                        customers_addr_sent.district=dataset_districts.name_th,
                                        customers_addr_sent.province=dataset_provinces.name_th
                                        WHERE
                                        customers_addr_sent.id='" . ($r_addr[0]->address_sent_id_fk) . "' ");
      }




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
