<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Pick_warehouse_fifoController extends Controller
{

    public function index(Request $request)
    {

      $Products = DB::select("SELECT products.id as product_id,
      products.product_code,
      (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
      FROM
      products_details
      Left Join products ON products_details.product_id_fk = products.id
      WHERE lang_id=1");

      $Check_stock = \App\Models\Backend\Pick_warehouse_fifo::get();

      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBranchs = \App\Models\Backend\Branchs::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

      return View('backend.pick_warehouse_fifo.index')->with(
        array(
           'Products'=>$Products,'Check_stock'=>$Check_stock,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id
        ) );

    }

    public function calFifo(Request $request)
    {
      // dd($request->all());
      // return $request;
      // dd();
      // $row_id = explode(',', $request->picking_id);

      DB::select('TRUNCATE db_orders_tmp');
      DB::select('TRUNCATE db_order_products_list_tmp');
      DB::select('TRUNCATE db_pick_warehouse_fifo');

      DB::select('TRUNCATE db_pick_warehouse_fifo_topicked');
      DB::select('TRUNCATE db_pick_warehouse_fifo_no');


      DB::select(' UPDATE db_pick_pack_packing_code SET status=1 WHERE id in ('.$request->picking_id.') ');

      if(!empty($request->picking_id)){

              DB::select(' 
                  insert ignore into db_orders_tmp select * from db_orders where invoice_code in(
                  SELECT
                  db_delivery.receipt
                  FROM
                  db_pick_pack_packing
                  Left Join db_delivery ON db_pick_pack_packing.delivery_id_fk = db_delivery.id
                  WHERE db_pick_pack_packing.packing_code in ('.$request->picking_id.') and db_delivery.packing_code=0
                  );
              ');


              DB::select(' 
                 insert ignore into db_orders_tmp select * from db_orders where invoice_code in
                  (
                  SELECT receipt FROM db_delivery WHERE packing_code in 
                  (
                  SELECT
                  db_delivery.packing_code
                  FROM
                  db_pick_pack_packing
                  Left Join db_delivery ON db_pick_pack_packing.delivery_id_fk = db_delivery.id
                  WHERE db_pick_pack_packing.packing_code in ('.$request->picking_id.') and  db_delivery.packing_code<>0
                  )
                  );
              ');


              DB::select(' 
                insert ignore into db_order_products_list_tmp select * from db_order_products_list where product_id_fk in 
                (
                SELECT
                db_order_products_list.product_id_fk
                FROM
                db_order_products_list
                INNER Join db_orders_tmp ON db_order_products_list.frontstore_id_fk = db_orders_tmp.id
                GROUP BY db_order_products_list.product_id_fk
                )
              ');

// รวมจำนวนสินค้าแต่ละรายการ
              // DB::select(' 
        
              // ');

        //  ได้รหัสสินค้ามาแล้ว 

              $product = DB::select('select product_id_fk from db_order_products_list_tmp');
              $product_id = [];
              foreach ($product as $key => $value) {
                 array_push($product_id,$value->product_id_fk);
                 DB::select('insert ignore into db_pick_warehouse_fifo (product_id_fk,pick_pack_packing_code_id_fk) values ('.$value->product_id_fk.','.$request->picking_id.')');
              }

              DB::select(" UPDATE db_pick_pack_packing_code SET status_picked='1' WHERE (id in(select pick_pack_packing_code_id_fk from db_pick_warehouse_fifo)) ");

              // return $product_id;
              // หา FIFO 

              DB::select("

                    UPDATE
                    db_pick_warehouse_fifo
                    Inner Join 
                    (SELECT * FROM db_stocks GROUP BY db_stocks.branch_id_fk,db_stocks.product_id_fk,db_stocks.lot_number ORDER BY db_stocks.lot_expired_date ASC) as db_stocks
                     ON db_pick_warehouse_fifo.product_id_fk = db_stocks.product_id_fk
                    SET
                    db_pick_warehouse_fifo.branch_id_fk=
                    db_stocks.branch_id_fk,
                    db_pick_warehouse_fifo.lot_number=
                    db_stocks.lot_number,
                    db_pick_warehouse_fifo.lot_expired_date=
                    db_stocks.lot_expired_date,
                    db_pick_warehouse_fifo.amt=
                    db_stocks.amt,
                    db_pick_warehouse_fifo.warehouse_id_fk=
                    db_stocks.warehouse_id_fk,
                    db_pick_warehouse_fifo.zone_id_fk=
                    db_stocks.zone_id_fk,
                    db_pick_warehouse_fifo.shelf_id_fk=
                    db_stocks.shelf_id_fk,
                    db_pick_warehouse_fifo.shelf_floor=
                    db_stocks.shelf_floor

                ");


              DB::select("

                    UPDATE
                    db_pick_warehouse_fifo
                    Left Join db_order_products_list_tmp ON db_pick_warehouse_fifo.product_id_fk = db_order_products_list_tmp.product_id_fk
                    SET
                    db_pick_warehouse_fifo.amt_get=
                    db_order_products_list_tmp.amt,
                    db_pick_warehouse_fifo.product_unit_id_fk=
                    db_order_products_list_tmp.product_unit_id_fk

                ");


        }


    }

// ไม่ได้ใช้แล้ว 
    public function calFifoForPayReceipt(Request $request)
    {
      // dd($request->all());
      
      // dd();
      DB::select("
        CREATE TEMPORARY TABLE TEMP_db_orders (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  customers_id_fk int(11) DEFAULT '0' COMMENT 'Ref>customers>id',
  address_sent_id_fk int(11) DEFAULT '0' COMMENT 'Ref>customers_addr_sent>id',
  business_location_id_fk int(11) DEFAULT NULL,
  branch_id_fk int(11) DEFAULT '0' COMMENT 'Ref>branchs>id สาขาร้าน',
  sentto_branch_id int(11) DEFAULT '0' COMMENT 'Ref>branchs>id : รับสินค้าที่สาขา',
  delivery_location int(1) DEFAULT '0' COMMENT 'ที่อยู่ผู้รับ >1=ที่อยู่ตามบัตร ปชช.>customers_address_card, 2=ที่อยู่ตามที่ลงทะเบียนในระบบ>customers_detail, 3=ที่อยู่กำหนดเอง>customers_addr_frontstore, 4=จัดส่งพร้อมบิลอื่น, 5=ส่งแบบพิเศษ/พรีเมี่ยม',
  delivery_location_frontend enum('sent_address','sent_address_card','sent_office','sent_address_other') DEFAULT NULL COMMENT 'ประเภทที่อยู่การส่งที่มาจากหน้าบ้าน sent_address ที่อยู่ตามลงทะเบียนระบบ sent_address_card ที่อยู่ตามบัตรประชาชน sent_office ที่อยู่ของออฟฟิส sent_address_other ที่อยู่อื่นๆ',
  delivery_province_id int(11) DEFAULT '0' COMMENT 'รหัสจังหวัด เอาไว้เช็คค่าขนส่ง',
  invoice_code varchar(255) DEFAULT NULL COMMENT 'เลขที่ใบเสร็จที่ชำระสำเร็จเเล้ว',
  invoice_code_id_fk int(11) DEFAULT NULL,
  code_order varchar(100) NOT NULL DEFAULT '' COMMENT 'เลขที่ Order ',
  distribution_channel_id_fk int(11) DEFAULT '0' COMMENT 'Ref>dataset_distribution_channel>id',
  purchase_type_id_fk int(11) DEFAULT '0' COMMENT 'Ref>dataset_orders_type>id  ประเภทการสั่งซื้อ',
  pay_type_id int(11) DEFAULT '0' COMMENT 'รหัสการชำระ (ทำใหม่) Ref>dataset_pay_type_02>id',
  pay_type_id_fk int(11) DEFAULT '0' COMMENT 'รหัสการชำระ (ทำใหม่) Ref>dataset_pay_type>id',
  member_id_aicash int(11) DEFAULT '0' COMMENT 'รหัสสมาชิกที่เป็นคนจ่าย ai-cash',
  transfer_price decimal(10,2) DEFAULT NULL COMMENT 'ยอดเงินโอน',
  credit_price decimal(10,2) DEFAULT NULL COMMENT 'ยอดบัตรเครดิต',
  aicash_price decimal(10,2) DEFAULT NULL COMMENT 'ยอด Aicash ที่ชำระออกไป',
  cash_pay decimal(10,2) DEFAULT NULL COMMENT 'ยอดเงินสด+ค่าขนส่ง+ค่าธรรมเนียม ที่จะต้องจ่ายเงินสด',
  account_bank_id int(11) DEFAULT '0' COMMENT 'Ref>dataset_account_bank จ่ายด้วยธนาคารอะไร',
  transfer_money_datetime datetime DEFAULT NULL COMMENT 'เวลาการโอน',
  file_slip varchar(255) DEFAULT NULL COMMENT 'ไฟล์สลิปกรณีโอนเงิน (ถ้ามี)',
  note text COMMENT 'หมายเหตุ',
  tracking_type varchar(255) DEFAULT NULL,
  tracking_no varchar(255) DEFAULT NULL COMMENT 'เลขรันการส่งสินค้า',
  payment_notification text,
  product_value decimal(10,2) DEFAULT NULL COMMENT 'มูลค่าสินค้า',
  tax decimal(10,2) DEFAULT NULL COMMENT 'ภาษี',
  fee int(1) DEFAULT '0' COMMENT 'Ref>dataset_fee>id',
  fee_amt decimal(10,2) DEFAULT NULL COMMENT 'ค่าธรรมเนียมตัดบัตรเครดิต > ค่าที่ผ่านการคำนวณแล้ว',
  charger_type int(1) DEFAULT '1' COMMENT '1=ชาร์ทในบัตร ,2=แยกชำระเป็นเงินสด',
  sum_credit_price decimal(10,2) DEFAULT NULL COMMENT 'หักจากบัตรเครดิต (รวมค่าธรรมเนียม ถ้ามี)',
  cash_price decimal(10,2) DEFAULT NULL COMMENT 'ยอดเงินสด หน้าบเานไม่ได้ใช้',
  gift_voucher_cost decimal(10,2) DEFAULT NULL COMMENT 'ยอด gift_voucher ที่มี Customer ตอนนี้มีเท่าไหร่',
  gift_voucher_price decimal(10,2) DEFAULT NULL COMMENT 'ยอด gift_voucher ที่หักจ่ายบิลนี้ ไปเท่าไหร่',
  shipping_price decimal(10,2) DEFAULT NULL COMMENT 'ค่าขนส่ง',
  shipping_free int(1) DEFAULT '0' COMMENT '1=ค่าจัดส่งฟรี',
  shipping_special int(1) DEFAULT '0' COMMENT '1=ส่งแบบพิเศษ/premium',
  shipping_cost_id_fk int(1) DEFAULT NULL COMMENT 'dataset_shipping_cost Typeของการส่งสินค้า',
  shipping_cost_detail varchar(100) DEFAULT NULL COMMENT 'dataset_shipping_cost ชื่อของการส่งสินค้า',
  sum_price decimal(10,2) DEFAULT NULL COMMENT 'รวมราคาสินค้า  ยังไม่รวมค่าขนส่ง',
  total_price decimal(10,2) DEFAULT NULL COMMENT 'รวมราคาสินค้า รวมทั้งหมด (รวมค่าขนส่งด้วยแล้ว)',
  pv_total decimal(11,2) DEFAULT NULL COMMENT 'รวม PV ที่ได้รับ',
  pv_banlance decimal(11,2) DEFAULT NULL COMMENT 'คะแนน PV ส่วนตัวขณะที่มีการชำระขณะนั้นว่ามีการเพิ่มขึ้นหรือลดลงของ PV เท่าไหร่ จะเกิดขึ้นหลังจากใบเสร็จนี้มีการอนุมัติเเล้วเท่านั้น',
  pv_old int(11) DEFAULT NULL COMMENT 'pv ก่อนจะมีการเปลี่ยนแปลง',
  pv_mt_old int(11) DEFAULT NULL COMMENT 'pv_mt ก่อนจะมีการเปลี่ยนแปลง',
  pv_tv_old int(11) DEFAULT NULL COMMENT 'pv_tv ก่อนจะมีการเปลี่ยนแปลง',
  active_mt_old_date date DEFAULT NULL COMMENT 'วันที่ Active  รักษาคุณสมบัติรายเดือน ของเดิม',
  active_tv_old_date date DEFAULT NULL COMMENT 'วันที่ Active สำหรับการซื้อเพื่อการท่องเที่ยว  ของเดิม',
  active_mt_date date DEFAULT NULL COMMENT 'วันที่ Active  รักษาคุณสมบัติรายเดือน',
  active_tv_date date DEFAULT NULL COMMENT 'วันที่ Active สำหรับการซื้อเพื่อการท่องเที่ยว  ',
  status_pv_mt_old varchar(100) DEFAULT NULL COMMENT 'Status Active  รักษาคุณสมบัติรายเดือนเดิม',
  aistockist varchar(100) DEFAULT NULL,
  agency varchar(100) DEFAULT NULL,
  house_no varchar(100) DEFAULT NULL COMMENT 'บ้านเลขที่',
  house_name varchar(50) DEFAULT NULL COMMENT 'ชื่อหมู่บ้าน',
  moo varchar(50) DEFAULT NULL COMMENT 'หมู่ที่',
  soi varchar(200) DEFAULT NULL COMMENT 'ซอย',
  amphures_id_fk int(11) DEFAULT NULL COMMENT 'อำเภอ',
  district_id_fk int(11) DEFAULT NULL COMMENT 'ตำบล',
  province_id_fk int(11) DEFAULT NULL COMMENT 'จังหวัด',
  road varchar(150) DEFAULT NULL,
  zipcode varchar(100) DEFAULT NULL COMMENT 'รหัสไปรษณี',
  email varchar(100) DEFAULT NULL,
  tel varchar(50) DEFAULT NULL,
  name varchar(200) DEFAULT NULL COMMENT 'ชื่อผู้รับ',
  qr_code varchar(255) DEFAULT NULL COMMENT 'รหัสสำหรับ Scan QR ในกรณีรับสินค้าที่สาขา',
  qr_endate datetime DEFAULT NULL COMMENT 'วันที่หมดอายุของ QR ',
  status_slip enum('true','false') DEFAULT NULL COMMENT 'Status ของ Slip ที่แนนมาสำหรับตรวจสอบว่าสลิปนั้น ผ่าน(true) หรือ ไม่ผ่าน(false)',
  status_payment_sent_other int(2) DEFAULT '0' COMMENT '0 สั่งซื้อให้ตัวเอง 1 สั่งซื้อให้คนอื่น',
  status_delivery int(1) DEFAULT '0' COMMENT '1=อยู่ระหว่างดำเนินการจัดส่งสินค้า',
  action_date date DEFAULT NULL COMMENT 'วันดำเนินการ',
  action_user int(11) DEFAULT '0' COMMENT 'ผู้ทำการโอน Ref>ck_users_admin>id',
  approve_status int(11) DEFAULT '0' COMMENT ' 0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย)''',
  order_status_id_fk int(11) DEFAULT NULL COMMENT '(ยึดตาม* dataset_order_status )',
  approver int(11) DEFAULT '0' COMMENT 'ผู้อนุมัติ',
  approve_date date DEFAULT NULL COMMENT 'วันที่อนุมัติ',
  cancel_by_user_id_fk int(11) DEFAULT NULL COMMENT 'user id ที่ทำ Cancel Oder สามารเป็น Admin หรือ User ก็ได้ โดยมี type_user_cancel เป็นตัวกำหนด',
  type_user_cancel int(11) DEFAULT NULL COMMENT '0 คือพนักงานหลังบ้าน 1 คือ Customrs มีผลในการดึงเอา cancel_by_user_id_fk ไปใช้จะได้รู้ว่าใช้ตารางใหน',
  date_setting_code varchar(50) DEFAULT NULL COMMENT 'ไว้สำหรับคำนวนเลขการ Run Code เก็บปีและเดือน',
  cancel_action_date datetime DEFAULT NULL COMMENT 'วันที่และเวลาในการยกเลิกบิล',
  cancel_expiry_date datetime DEFAULT NULL COMMENT 'เวลาหมดอายุ + 30 นาทีหลังจากมีการอนุมัติบิล และต้องไม่เกิน ห้าทุ่มของวันที่ทำรายการ',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8
");


      DB::select("
CREATE TEMPORARY TABLE TEMP_db_order_products_list (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  frontstore_id_fk int(11) DEFAULT '0' COMMENT 'Ref>db_orders>id',
  customers_id_fk int(11) DEFAULT '0' COMMENT 'Ref>customers>id',
  distribution_channel_id_fk int(11) DEFAULT '0' COMMENT 'Ref>dataset_distribution_channel>id',
  purchase_type_id_fk int(11) DEFAULT '0' COMMENT 'Ref>dataset_purchase_type>id',
  pay_type_id_fk int(11) DEFAULT '0' COMMENT 'Ref>dataset_pay_type>id',
  selling_price decimal(10,2) DEFAULT NULL COMMENT 'ราคาขาย',
  product_id_fk int(11) DEFAULT NULL,
  product_name varchar(150) DEFAULT NULL COMMENT 'ชื่อสินค้า',
  amt int(11) DEFAULT '0',
  product_unit_id_fk int(11) DEFAULT NULL,
  pv int(11) DEFAULT '0' COMMENT 'pv',
  total_pv int(11) DEFAULT '0',
  total_price decimal(10,2) DEFAULT NULL,
  currency varchar(255) DEFAULT NULL,
  add_from int(11) DEFAULT '1' COMMENT '1=เพิ่มเข้าจากรายการซื้อปกติ,2=เพิ่มเข้าจากโปรแกรมโมชั่นหรือคูปอง 3 = course 4 สินค้าแถม',
  type_product enum('promotion','course','giveaway','product') DEFAULT NULL COMMENT '''promotion'',''course'',''giveaway'',''product'' ประเภทสินค้าของหน้าบ้าน',
  promotion_id_fk int(11) DEFAULT '0' COMMENT 'Ref>promotions>id',
  promotion_code varchar(255) DEFAULT NULL COMMENT 'รหัสคูปอง',
  giveaway_id_fk int(11) DEFAULT NULL COMMENT 'กรณีเป้นสินค้าแถม = db_giveaway',
  course_id_fk int(11) DEFAULT NULL COMMENT 'กรรีซื้อ Course course ',
  action_date date DEFAULT NULL COMMENT 'วันดำเนินการ',
  action_user int(11) DEFAULT '0' COMMENT 'ผู้ทำการโอน Ref>ck_users_admin>id',
  approve_status int(11) DEFAULT '0' COMMENT '0=รออนุมัติ,1=อนุมัติ,2=ยกเลิก,3=ไม่อนุมัติ',
  approver int(11) DEFAULT '0' COMMENT 'ผู้อนุมัติ',
  approve_date date DEFAULT NULL COMMENT 'วันที่อนุมัติ',
  qr_code text COMMENT 'คิวอาร์สินค้ารายชิ้น เป็น อาเรย์ เนื่องจากมีหลายชิ้น',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id) USING BTREE,
  UNIQUE KEY frontstore_id_fk (frontstore_id_fk,product_id_fk) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8
        ");


      DB::select("
CREATE TEMPORARY TABLE TEMP_db_pick_warehouse_fifo (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  branch_id_fk int(11) DEFAULT '0' COMMENT 'Ref>branchs>id',
  product_id_fk int(11) DEFAULT '0' COMMENT 'Ref>products>id',
  lot_number varchar(255) DEFAULT NULL,
  lot_expired_date date DEFAULT NULL COMMENT 'วันหมดอายุของ lot นี้',
  amt int(11) DEFAULT '0' COMMENT 'จำนวนที่รับเข้า',
  product_unit_id_fk int(11) DEFAULT '0' COMMENT 'Ref>dataset_product_unit>id',
  date_in_stock date DEFAULT NULL COMMENT 'วันที่เช็คเข้าสต๊อค',
  warehouse_id_fk int(11) DEFAULT '0' COMMENT 'Ref>warehouse>id',
  zone_id_fk int(11) DEFAULT '0' COMMENT 'Ref>zone>id',
  shelf_id_fk int(11) DEFAULT '0' COMMENT 'Ref>shelf>id',
  shelf_floor int(11) DEFAULT '1' COMMENT 'ชั้นของ shelf',
  amt_get int(11) DEFAULT '0' COMMENT 'จำนวนที่เบิกตามใบเสร็จ',
  status int(1) DEFAULT '0' COMMENT '1=ถูกเลือกรายการนี้แล้ว',
  pick_pack_packing_code_id_fk int(11) DEFAULT '0' COMMENT 'Ref>db_pick_pack_packing_code>id',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
        ");


      DB::select("
CREATE TEMPORARY TABLE TEMP_db_pick_warehouse_fifo_topicked (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  branch_id_fk int(11) DEFAULT '0' COMMENT 'Ref>branchs>id',
  product_id_fk int(11) DEFAULT '0' COMMENT 'Ref>products>id',
  lot_number varchar(255) DEFAULT NULL,
  lot_expired_date date DEFAULT NULL COMMENT 'วันหมดอายุของ lot นี้',
  amt int(11) DEFAULT '0' COMMENT 'จำนวนที่รับเข้า',
  product_unit_id_fk int(11) DEFAULT '0' COMMENT 'Ref>dataset_product_unit>id',
  date_in_stock date DEFAULT NULL COMMENT 'วันที่เช็คเข้าสต๊อค',
  warehouse_id_fk int(11) DEFAULT '0' COMMENT 'Ref>warehouse>id',
  zone_id_fk int(11) DEFAULT '0' COMMENT 'Ref>zone>id',
  shelf_id_fk int(11) DEFAULT '0' COMMENT 'Ref>shelf>id',
  shelf_floor int(11) DEFAULT '1' COMMENT 'ชั้นของ shelf',
  amt_get int(11) DEFAULT '0' COMMENT 'จำนวนที่เบิกตามใบเสร็จ',
  status int(1) DEFAULT '0' COMMENT '1=อนุมัติแล้ว',
  pick_pack_packing_code_id_fk int(11) DEFAULT '0' COMMENT 'Ref>db_pick_pack_packing_code>id',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

        ");

 
      DB::select("
CREATE TEMPORARY TABLE TEMP_db_pick_warehouse_fifo_no (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  branch_id_fk int(11) DEFAULT '0' COMMENT 'Ref>branchs>id',
  product_id_fk int(11) DEFAULT '0' COMMENT 'Ref>products>id',
  lot_number varchar(255) DEFAULT NULL,
  lot_expired_date date DEFAULT NULL COMMENT 'วันหมดอายุของ lot นี้',
  amt int(11) DEFAULT '0' COMMENT 'จำนวนที่รับเข้า',
  product_unit_id_fk int(11) DEFAULT '0' COMMENT 'Ref>dataset_product_unit>id',
  date_in_stock date DEFAULT NULL COMMENT 'วันที่เช็คเข้าสต๊อค',
  warehouse_id_fk int(11) DEFAULT '0' COMMENT 'Ref>warehouse>id',
  zone_id_fk int(11) DEFAULT '0' COMMENT 'Ref>zone>id',
  shelf_id_fk int(11) DEFAULT '0' COMMENT 'Ref>shelf>id',
  shelf_floor int(11) DEFAULT '1' COMMENT 'ชั้นของ shelf',
  amt_get int(11) DEFAULT '0' COMMENT 'จำนวนที่เบิกตามใบเสร็จ',
  status int(1) DEFAULT '0' COMMENT '1=อนุมัติแล้ว',
  pick_pack_packing_code_id_fk int(11) DEFAULT '0' COMMENT 'Ref>db_pick_pack_packing_code>id',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

        ");


         // return $request;
      DB::select(" TRUNCATE TEMP_db_orders; ");
      DB::select(" INSERT INTO TEMP_db_orders select * from db_orders WHERE invoice_code='".$request->txtSearch."' ; ");
      $rs_TEMP_db_orders =  DB::select(" select * from TEMP_db_orders WHERE invoice_code='".$request->txtSearch."' ; ");
      // return $rs_TEMP_db_orders[0]->id;

      DB::select(" TRUNCATE TEMP_db_order_products_list; ");
      DB::select(" INSERT INTO TEMP_db_order_products_list select * from db_order_products_list WHERE frontstore_id_fk='".$rs_TEMP_db_orders[0]->id."' ; ");
      $rs_TEMP_db_order_products_list =  DB::select(" select * from TEMP_db_order_products_list WHERE frontstore_id_fk='".$rs_TEMP_db_orders[0]->id."' ; ");
      // return $rs_TEMP_db_order_products_list;

      //  ได้รหัสสินค้ามาแล้ว 
      $product = DB::select('select product_id_fk from TEMP_db_order_products_list');
      $product_id = [];
      foreach ($product as $key => $value) {
         array_push($product_id,$value->product_id_fk);
         DB::select('insert ignore into TEMP_db_pick_warehouse_fifo (product_id_fk) values ('.$value->product_id_fk.')');
      }
      
      $rs_TEMP_db_pick_warehouse_fifo =  DB::select(" select * from TEMP_db_pick_warehouse_fifo ; ");
      // return $rs_TEMP_db_pick_warehouse_fifo;
// &&&&&&&&&&&&&&&&

          DB::select("

                    UPDATE
                    TEMP_db_pick_warehouse_fifo
                    Inner Join 
                    (SELECT * FROM db_stocks GROUP BY db_stocks.branch_id_fk,db_stocks.product_id_fk,db_stocks.lot_number ORDER BY db_stocks.lot_expired_date ASC) as db_stocks
                     ON TEMP_db_pick_warehouse_fifo.product_id_fk = db_stocks.product_id_fk
                    SET
                    TEMP_db_pick_warehouse_fifo.branch_id_fk=
                    db_stocks.branch_id_fk,
                    TEMP_db_pick_warehouse_fifo.lot_number=
                    db_stocks.lot_number,
                    TEMP_db_pick_warehouse_fifo.lot_expired_date=
                    db_stocks.lot_expired_date,
                    TEMP_db_pick_warehouse_fifo.amt=
                    db_stocks.amt,
                    TEMP_db_pick_warehouse_fifo.warehouse_id_fk=
                    db_stocks.warehouse_id_fk,
                    TEMP_db_pick_warehouse_fifo.zone_id_fk=
                    db_stocks.zone_id_fk,
                    TEMP_db_pick_warehouse_fifo.shelf_id_fk=
                    db_stocks.shelf_id_fk,
                    TEMP_db_pick_warehouse_fifo.shelf_floor=
                    db_stocks.shelf_floor

                ");


              DB::select("

                    UPDATE
                    TEMP_db_pick_warehouse_fifo
                    Left Join db_order_products_list_tmp ON TEMP_db_pick_warehouse_fifo.product_id_fk = db_order_products_list_tmp.product_id_fk
                    SET
                    TEMP_db_pick_warehouse_fifo.amt_get=
                    db_order_products_list_tmp.amt,
                    TEMP_db_pick_warehouse_fifo.product_unit_id_fk=
                    db_order_products_list_tmp.product_unit_id_fk

                ");

      $rs_TEMP_db_pick_warehouse_fifo =  DB::select(" select * from TEMP_db_pick_warehouse_fifo ; ");
      return $rs_TEMP_db_pick_warehouse_fifo;

    }

    public function create()
    {
    }
    public function store(Request $request)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

   public function form($id=NULL)
    {
    }

    public function destroy($id)
    {
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Pick_warehouse_fifo::search()->orderBy('lot_expired_date', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {
        
          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".($row->product_id_fk)." AND lang_id=1");

          if(@$Products[0]->product_code!=@$Products[0]->product_name){
             return @$Products[0]->product_code." : ".@$Products[0]->product_name;
          }else{
             return @$Products[0]->product_name;
          }

      })
      ->addColumn('lot_expired_date', function($row) {
        $d = strtotime($row->lot_expired_date); 
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('date_in_stock', function($row) {
        $d = strtotime($row->pickup_firstdate); 
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('warehouses', function($row) {
        $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
        return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$row->shelf_floor;
      }) 
      ->addColumn('product_unit', function($row) {
        $p = DB::select("  SELECT product_unit
              FROM
              dataset_product_unit
              WHERE id = ".$row->product_unit_id_fk." AND  lang_id=1  ");
          return @$p[0]->product_unit;
      }) 
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


}
