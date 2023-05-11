<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Session;
use App\Http\Controllers\backend\AjaxController;
use Auth;

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
      // return($request->all());
      // return $request->picking_id;
      // return "aaa";
      // dd();

      // $arr_picking_id = $request->picking_id;
      // $picking = explode(",", $request->picking_id);

      // return ($request->picking_id);
      // return ($picking[0]);
      // $picking = explode(",", $picking[0]);

      // return gettype($picking);
      // if(gettype($request->picking_id)=="array"){
      //   $picking = implode(",", $request->picking_id);
      // }else{
        $picking = $request->picking_id;
      // }
      // return ($picking[0]);
      // return gettype($arr_picking_id);
      // return $picking;
      // return count($picking);

      // $arr_picking = [];
      // for ($i=0; $i < count($picking) ; $i++) {
      //   $a = "P2".sprintf("%05d",$picking[$i]);
      //   array_push($arr_picking,$a);
      // }
      // $arr_picking = array_unique($arr_picking);
      // $pickings = implode(',', $arr_picking);
      // return $pickings;

      // $pick_pack_packing_code = implode(',', $arr_picking);

      // return gettype($picking);
      // $picking = array_unique($picking);
      // $row_id = implode(',', $picking);

      // return gettype($row_id);
      // return $arr_picking_id;
      // dd(); db_pick_pack_requisition_code

      if(!empty($picking)){

          $r_db_pick_pack_packing_code = DB::select(" SELECT orders_id_fk,receipt FROM db_pick_pack_packing_code WHERE id in ($picking) ; ");

          // return $r_db_pick_pack_packing_code;
          // dd();

          $arr_00 = [];
          $arr_receipt = [];
          foreach ($r_db_pick_pack_packing_code as $key => $value) {
            if($value->orders_id_fk!="") array_push($arr_00,$value->orders_id_fk);
            array_push($arr_receipt,$value->receipt);
          }
          $receipts = implode(",",$arr_receipt);

          $orders_id_fk = array_filter($arr_00);
          $orders_id_fk = implode(",",$orders_id_fk);
          // $orders_id_fk = explode(",",$orders_id_fk);
          // dd($orders_id_fk);
          // $orders_id_fk = implode(",",$orders_id_fk);

          // return $orders_id_fk;

          $r_db_order_products_list = DB::select(" SELECT product_id_fk,promotion_id_fk FROM db_order_products_list WHERE frontstore_id_fk in ($orders_id_fk) ");
          // return $r_db_order_products_list;
          // return ($id);

          // check กรณีสินค้าที่เกิดขจากการซื้อโปร ด้วย ถ้าซื้อโปร รหัสสินค้าจะไม่มีในฃตาราง db_order_products_list ต้อง join หาอีกที

    //  ดึง product_id_fk ออกมารวมกัน
          $arr_03 = [];
          $arr_04 = [];
          foreach ($r_db_order_products_list as $key => $value) {
            array_push($arr_03,$value->product_id_fk);
            array_push($arr_04,$value->promotion_id_fk);
          }

          $arr_promotion = array_unique($arr_04);
          $arr_promotion = array_filter($arr_promotion);
          $arr_promotion = implode(",",$arr_promotion);
          $arr_promotion = $arr_promotion?$arr_promotion:0;

          // return $orders_id_fk." : ".$arr_promotion;
          // dd();

          if(@$arr_promotion){
            $r_promo_product = DB::select(" SELECT product_id_fk FROM `promotions_products` where promotion_id_fk in ($arr_promotion)  ");
            if(@$r_promo_product){
                foreach ($r_promo_product as $key => $v) {
                  array_push($arr_03,$v->product_id_fk);
                }
            }
          }

          $arr_product_id_fk = array_unique($arr_03);
          $arr_product_id_fk = array_filter($arr_product_id_fk);
          $arr_product_id_fk = implode(",",$arr_product_id_fk);
          $arr_product_id_fk = $arr_product_id_fk?$arr_product_id_fk:0;

          // return $arr_product_id_fk;

          $business_location_id_fk = \Auth::user()->business_location_id_fk;
          $branch_id_fk = \Auth::user()->branch_id_fk;
          $temp_ppp_001 = "temp_ppp_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
          $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list
          $temp_ppp_003 = "temp_ppp_003".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
          $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
          $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // เก็บรายการที่หยิบสินค้ามาจากชั้นต่างๆ ตอน FIFO
          $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
          $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
          $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id;


          $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;

          $TABLES = DB::select(" SHOW TABLES ");
          // return $TABLES;
          // dd(); temp_ppp_0022

          // return $temp_db_stocks;

          $array_TABLES = [];
          foreach($TABLES as $t){
            // print_r($t->Tables_in_aiyaraco_v3);
            array_push($array_TABLES, $t->Tables_in_aiyaraco_v3);
          }

          if(in_array($temp_db_stocks,$array_TABLES)){
            // return "IN";
            DB::select(" TRUNCATE TABLE $temp_db_stocks  ");
          }else{
            // return "Not";
            DB::select(" CREATE TABLE $temp_db_stocks LIKE db_stocks ");
          }


          // return $temp_db_stocks;
          // return $arr_product_id_fk;
          // return $business_location_id_fk;
          // return $branch_id_fk;
          // return $temp_db_stocks;
          // dd();
          // if($branch_id_fk==1){ // รวม 6 = WAREHOUSE คลังสินค้า
          //   $wh_branch_id_fk = " AND db_stocks.branch_id_fk in (1,6) ";
          // }else{
          //   $wh_branch_id_fk = " AND db_stocks.branch_id_fk='$branch_id_fk' ";
          // }

          // return $arr_product_id_fk;

          DB::select(" INSERT IGNORE INTO $temp_db_stocks SELECT * FROM db_stocks
          WHERE db_stocks.business_location_id_fk='$business_location_id_fk' AND db_stocks.branch_id_fk='$branch_id_fk' AND db_stocks.lot_expired_date>=now() AND db_stocks.warehouse_id_fk=(SELECT warehouse_id_fk FROM branchs WHERE id=db_stocks.branch_id_fk) AND db_stocks.product_id_fk in ($arr_product_id_fk) ORDER BY db_stocks.lot_number ASC, db_stocks.lot_expired_date ASC ");


      //    $r_test = DB::select(" SELECT * FROM $temp_db_stocks ");
      //    return ($r_test);
      // return $business_location_id_fk ." : ".$branch_id_fk;

      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ");
      DB::select(" CREATE TABLE $temp_db_stocks_check LIKE temp_db_stocks_check_template ");

      // return $temp_db_stocks_check;
      // dd();

    // return $request;
    // หาในตาราง db_orders ว่ามีมั๊ย
    // คิวรีจาก db_orders ที่ branch_id_fk = sentto_branch_id & delivery_location = 0
    // กรณีที่ เป็น invoice_code (เพราะมี 2 กรณี คือ invoice_code กับ QR_CODE)
    // $invoice_code = $request->txtSearch;

      $r_db_orders = DB::select(" SELECT * FROM db_orders WHERE id in ($orders_id_fk) ");
          // return $r_db_orders;

      if(@$r_db_orders){

              DB::select(" DROP TABLE IF EXISTS $temp_ppp_001; ");
              DB::select(" CREATE TABLE $temp_ppp_001 LIKE db_orders ");
              DB::select(" INSERT $temp_ppp_001 SELECT * FROM db_orders WHERE id in ($orders_id_fk) ");
              DB::select(" ALTER TABLE $temp_ppp_001
              ADD COLUMN pick_pack_packing_code_id_fk  int NULL DEFAULT 0 COMMENT 'Ref>db_pick_pack_packing_code>id' AFTER id ");

              DB::select(" ALTER TABLE $temp_ppp_001
              ADD COLUMN pick_pack_requisition_code_id_fk  int NULL DEFAULT 0 COMMENT 'Ref>db_pick_pack_requisition_code>id' AFTER id ");

              DB::select(" DROP TABLE IF EXISTS $temp_db_pick_pack_requisition_code ; ");
              DB::select(" CREATE TABLE $temp_db_pick_pack_requisition_code LIKE db_pick_pack_requisition_code ");
              DB::select(" INSERT INTO $temp_db_pick_pack_requisition_code select * from db_pick_pack_requisition_code ");

              DB::select(" INSERT IGNORE INTO $temp_db_pick_pack_requisition_code(pick_pack_packing_code_id_fk,pick_pack_packing_code,action_user,receipts) VALUES ('$picking','$picking',".(\Auth::user()->id).",'".$receipts."') ");
              $lastInsertId01 = DB::getPdo()->lastInsertId();

              $requisition_code = "P3".sprintf("%05d",$lastInsertId01);
              DB::select(" UPDATE $temp_db_pick_pack_requisition_code SET requisition_code='$requisition_code' WHERE id in ($lastInsertId01) ");
              //     DB::select(" UPDATE $temp_ppp_001 SET pick_pack_requisition_code_id_fk=$lastInsertId01  ");
              DB::select(" UPDATE $temp_ppp_001 SET pick_pack_requisition_code_id_fk=$picking  ");

              DB::select(" DROP TABLE IF EXISTS $temp_ppp_002; ");
              DB::select(" CREATE TABLE $temp_ppp_002 LIKE db_order_products_list ");
              // กรณี product
              DB::select(" INSERT IGNORE INTO $temp_ppp_002
              SELECT db_order_products_list.* FROM db_order_products_list INNER Join $temp_ppp_001 ON db_order_products_list.frontstore_id_fk = $temp_ppp_001.id ");


              DB::select(" ALTER TABLE $temp_ppp_002
              ADD COLUMN pick_pack_packing_code_id_fk  int(11) NULL DEFAULT 0 COMMENT 'Ref>db_pick_pack_packing_code>id' AFTER id ");

              DB::select(" ALTER TABLE $temp_ppp_002
              ADD COLUMN pick_pack_requisition_code_id_fk  int NULL DEFAULT 0 COMMENT 'Ref>db_pick_pack_requisition_code>id' AFTER id ");
              DB::select(" UPDATE $temp_ppp_002 SET pick_pack_requisition_code_id_fk=$lastInsertId01  ");


              $temp_ppp_0022 = "temp_ppp_0022".\Auth::user()->id;
              DB::select(" DROP TABLE IF EXISTS $temp_ppp_0022; ");
              DB::select(" CREATE TABLE $temp_ppp_0022 LIKE temp_ppp_002_template ");
              // sleep(3);
              DB::select(" INSERT IGNORE INTO $temp_ppp_0022 (pick_pack_requisition_code_id_fk, product_id_fk, product_name, amt, product_unit_id_fk)
              SELECT $lastInsertId01, product_id_fk, product_name, sum(amt), product_unit_id_fk FROM $temp_ppp_002 WHERE product_id_fk in ($arr_product_id_fk) GROUP BY pick_pack_packing_code_id_fk,product_id_fk");

              // กรณี promotion

              if(@$arr_promotion){

                  // $r_promo_product = DB::select(" SELECT product_id_fk,sum(product_amt) as product_amt,product_unit FROM `promotions_products` where promotion_id_fk in ($arr_promotion) GROUP BY product_id_fk  ");
                   // if(@$r_promo_product){
                  //     foreach ($r_promo_product as $key => $v) {

                  //         $Products = DB::select("
                  //             SELECT products.id as product_id,
                  //               products.product_code,
                  //               (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name ,
                  //               products_cost.member_price,
                  //               products_cost.pv
                  //               FROM
                  //               products_details
                  //               Left Join products ON products_details.product_id_fk = products.id
                  //               LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                  //               WHERE lang_id=1 AND products.id= ".$v->product_id_fk."

                  //        ");

                  //         $pn = @$Products[0]->product_code." : ".@$Products[0]->product_name;

                  //         DB::select(" INSERT IGNORE INTO $temp_ppp_0022 (pick_pack_requisition_code_id_fk, product_id_fk, product_name, amt, product_unit_id_fk) values ($lastInsertId01, $v->product_id_fk,'$pn', $v->product_amt, $v->product_unit)
                  //          ");

                  //     }
                  // }

                  $new_arr_promotion = explode(',',$arr_promotion);

                  // วุฒิเพิ่มมานับจำนวนโปร
                  $Pro_amt = DB::table($temp_ppp_002)->select('*',DB::raw('SUM(amt) AS sum_amt'))->whereIn('promotion_id_fk',$new_arr_promotion)->groupBy('promotion_id_fk')->get();

                  foreach($Pro_amt as $pro){
                      $r_pro = DB::table('promotions_products')->where('promotion_id_fk',$pro->promotion_id_fk)->get();
                      foreach($r_pro as $r){
                        $sum_amt = 0;
                        $sum_amt = $pro->sum_amt*$r->product_amt;
                        $Products = DB::select("
                        SELECT products.id as product_id,
                          products.product_code,
                          (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name ,
                          products_cost.member_price,
                          products_cost.pv
                          FROM
                          products_details
                          Left Join products ON products_details.product_id_fk = products.id
                          LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                          WHERE lang_id=1 AND products.id= ".$r->product_id_fk."

                   ");

                    $pn = @$Products[0]->product_code." : ".@$Products[0]->product_name;

                        DB::select(" INSERT IGNORE INTO $temp_ppp_0022 (pick_pack_requisition_code_id_fk, product_id_fk, product_name, amt, product_unit_id_fk) values ($lastInsertId01, $r->product_id_fk,'$pn', $sum_amt, $r->product_unit) ");
                      }
                  }

                }

             // ต้องมีอีกตารางนึง เก็บ สถานะการอนุมัติ และ ที่อยู่การจัดส่งสินค้า > $temp_ppp_003
                DB::select(" DROP TABLE IF EXISTS $temp_ppp_003; ");
                DB::select(" CREATE TABLE $temp_ppp_003 LIKE temp_ppp_003_template ");
                DB::select("
                  INSERT IGNORE INTO $temp_ppp_003 (orders_id_fk, business_location_id_fk, branch_id_fk, branch_id_fk_tosent, invoice_code, bill_date, action_user,  customer_id_fk,  address_send_type, created_at)
                  SELECT id,business_location_id_fk,branch_id_fk,sentto_branch_id,invoice_code,action_date,action_user , customers_id_fk ,'3', now() FROM db_orders WHERE id in ($orders_id_fk) ;
                ");

              // $Data = DB::select(" SELECT * FROM $temp_ppp_003; ");
              // return $Data;
              // FIFO

                  // if(in_array($temp_db_stocks,$array_TABLES)){
                  //   // return "IN";
                  //   DB::select(" TRUNCATE TABLE $temp_db_stocks  ");
                  // }else{
                  //   // return "Not";
                  //   DB::select(" CREATE TABLE $temp_db_stocks LIKE db_stocks ");
                  // }

                // DB::select(" INSERT IGNORE INTO $temp_db_stocks SELECT * FROM db_stocks
                // WHERE db_stocks.business_location_id_fk='$business_location_id_fk' AND db_stocks.branch_id_fk='$branch_id_fk' AND db_stocks.lot_expired_date>=now() AND db_stocks.warehouse_id_fk=(SELECT warehouse_id_fk FROM branchs WHERE id=db_stocks.branch_id_fk) AND db_stocks.product_id_fk in ($arr_product_id_fk) ORDER BY db_stocks.lot_number ASC, db_stocks.lot_expired_date ASC ");

                 DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_compare; ");

                if(in_array($temp_db_stocks_compare,$array_TABLES)){
                  DB::select(" CREATE TABLE $temp_db_stocks_compare LIKE temp_db_stocks_compare_template ");
                  // return "IN";
                }else{
                  // return "Not";
                  DB::select(" CREATE TABLE $temp_db_stocks_compare LIKE temp_db_stocks_compare_template ");
                }


                // วุฒิเพิ่มมา เบิกจ่ายสินค้าใบเบิกใหม่ 2
                $DeliveryPackingCode = \App\Models\Backend\Pick_packPackingCode::where('id',$picking)->first();
                if($DeliveryPackingCode->bill_remain_status==1){
                  $arr_item = explode(',',$DeliveryPackingCode->pay_requisition_002_item);
                  $item_pro = DB::table('db_pay_requisition_002_item')->whereIn('id',$arr_item)->pluck('product_id_fk')->toArray();
                  DB::table($temp_ppp_0022)->whereNotIn('product_id_fk',$item_pro)->delete();
                }

          // $lastInsertId = DB::getPdo()->lastInsertId();

            return $lastInsertId01 ;
            // dd();
// Close > if($r01)
            // กรณีหาแล้วไม่เจอ
          }else{
            return 0;
          }




      } // END if(isset($request->picking_id)){



    }


    public function calFifo_edit(Request $request)
    {
      // return($request->all());
      // dd();
      if(isset($request->requisition_code)){
          // return gettype($request->requisition_code);
          $requisition_code = $request->requisition_code;

          // return $requisition_code;
          // dd();

          $business_location_id_fk = \Auth::user()->business_location_id_fk;
          $branch_id_fk = \Auth::user()->branch_id_fk;
          $temp_ppp_001 = "temp_ppp_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
          $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list
          $temp_ppp_003 = "temp_ppp_003".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
          $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // เก็บรายการที่หยิบสินค้ามาจากชั้นต่างๆ ตอน FIFO
          $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
          $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
          $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
          $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id;

     // หา arr_product_id_fk
          $r_db_pay_requisition_002 = DB::select(" SELECT product_id_fk FROM `db_pay_requisition_002` WHERE pick_pack_requisition_code_id_fk in($requisition_code) ");

    //  ดึง product_id_fk ออกมารวมกัน
          $arr_01 = [];
          foreach ($r_db_pay_requisition_002 as $key => $value) {
            array_push($arr_01,$value->product_id_fk);
          }

          $arr_product_id_fk = array_unique($arr_01);
          $arr_product_id_fk = implode(",",$arr_product_id_fk);
          // return $arr_product_id_fk;

          DB::select(" DROP TABLE IF EXISTS $temp_db_stocks ");
          DB::select(" CREATE TABLE $temp_db_stocks LIKE db_stocks  ");


          DB::select(" INSERT IGNORE INTO $temp_db_stocks SELECT * FROM db_stocks
          WHERE db_stocks.business_location_id_fk='$business_location_id_fk' AND db_stocks.branch_id_fk='$branch_id_fk' AND db_stocks.lot_expired_date>=now() AND db_stocks.warehouse_id_fk=(SELECT warehouse_id_fk FROM branchs WHERE id=db_stocks.branch_id_fk) AND db_stocks.product_id_fk in ($arr_product_id_fk) ORDER BY db_stocks.lot_number ASC, db_stocks.lot_expired_date ASC ");


          DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ");
          DB::select(" CREATE TABLE $temp_db_stocks_check LIKE temp_db_stocks_check_template ");


     if($r_db_pay_requisition_002){


        // return "OK";
        // dd();


   // Qry > product_id_fk from $temp_ppp_002
        $product = DB::select(" select product_id_fk from $temp_ppp_002 ");
        // return $product;

        $product_id_fk = [];
        foreach ($product as $key => $value) {
           array_push($product_id_fk,$value->product_id_fk);
        }
        // return $product_id_fk;
        $arr_product_id_fk = implode(',',$product_id_fk);
        // return $arr_product_id_fk;
        // dd();

        // DB::select(" DROP TABLE IF EXISTS $temp_ppp_002; ");
        // DB::select(" CREATE TABLE $temp_ppp_002 LIKE db_order_products_list ");
        // DB::select(" INSERT IGNORE INTO $temp_ppp_002
        // SELECT db_order_products_list.* FROM db_order_products_list INNER Join $temp_ppp_001 ON db_order_products_list.frontstore_id_fk = $temp_ppp_001.id ");

        //   DB::select(" ALTER TABLE $temp_ppp_002
        //   ADD COLUMN pick_pack_packing_code_id_fk  int(11) NULL DEFAULT 0 COMMENT 'Ref>db_pick_pack_packing_code>id' AFTER id ");

        // DB::select(" ALTER TABLE $temp_ppp_002
        // ADD COLUMN pick_pack_requisition_code_id_fk  int NULL DEFAULT 0 COMMENT 'Ref>db_pick_pack_requisition_code>id' AFTER id ");
        //   // DB::select(" UPDATE $temp_ppp_002 SET pick_pack_packing_code_id_fk=".$request->picking_id." WHERE product_id_fk in ($arr_product_id_fk) ");
        // DB::select(" UPDATE $temp_ppp_002 SET pick_pack_requisition_code_id_fk=$lastInsertId01  ");

        //   // return $arr_product_id_fk;

        // $temp_ppp_0022 = "temp_ppp_0022".\Auth::user()->id;
        // DB::select(" DROP TABLE IF EXISTS $temp_ppp_0022; ");
        // DB::select(" CREATE TABLE $temp_ppp_0022 LIKE temp_ppp_002_template ");
        // // sleep(3);
        // DB::select(" INSERT IGNORE INTO $temp_ppp_0022 (pick_pack_requisition_code_id_fk, product_id_fk, product_name, amt, product_unit_id_fk)
        //  SELECT $lastInsertId01, product_id_fk, product_name, sum(amt), product_unit_id_fk FROM $temp_ppp_002 WHERE product_id_fk in ($arr_product_id_fk) GROUP BY pick_pack_packing_code_id_fk,product_id_fk");


        //      // ต้องมีอีกตารางนึง เก็บ สถานะการอนุมัติ และ ที่อยู่การจัดส่งสินค้า > $temp_ppp_003
        //         DB::select(" DROP TABLE IF EXISTS $temp_ppp_003; ");
        //         DB::select(" CREATE TABLE $temp_ppp_003 LIKE temp_ppp_003_template ");
        //         DB::select("
        //           INSERT IGNORE INTO $temp_ppp_003 (orders_id_fk, business_location_id_fk, branch_id_fk, branch_id_fk_tosent, invoice_code, bill_date, action_user,  customer_id_fk,  address_send_type, created_at)
        //           SELECT id,business_location_id_fk,branch_id_fk,sentto_branch_id,invoice_code,action_date,action_user , customers_id_fk ,'3', now() FROM db_orders WHERE id in ($id) ;
        //         ");

              // $Data = DB::select(" SELECT * FROM $temp_ppp_003; ");
              // return $Data;
      // FIFO

                //   if(in_array($temp_db_stocks,$array_TABLES)){
                //     // return "IN";
                //     DB::select(" TRUNCATE TABLE $temp_db_stocks  ");
                //   }else{
                //     // return "Not";
                //     DB::select(" CREATE TABLE $temp_db_stocks LIKE db_stocks ");
                //   }

                // DB::select(" INSERT IGNORE INTO $temp_db_stocks SELECT * FROM db_stocks
                // WHERE db_stocks.business_location_id_fk='$business_location_id_fk' AND db_stocks.branch_id_fk='$branch_id_fk' AND db_stocks.lot_expired_date>=now() AND db_stocks.warehouse_id_fk=(SELECT warehouse_id_fk FROM branchs WHERE id=db_stocks.branch_id_fk) AND db_stocks.product_id_fk in ($arr_product_id_fk) ORDER BY db_stocks.lot_number ASC, db_stocks.lot_expired_date ASC ");

                //  DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_compare; ");

                // if(in_array($temp_db_stocks_compare,$array_TABLES)){
                //   DB::select(" CREATE TABLE $temp_db_stocks_compare LIKE temp_db_stocks_compare_template ");
                //   // return "IN";
                // }else{
                //   // return "Not";
                //   DB::select(" CREATE TABLE $temp_db_stocks_compare LIKE temp_db_stocks_compare_template ");
                // }



          // $lastInsertId = DB::getPdo()->lastInsertId();

            return $requisition_code;
// Close > if($r01)
            // กรณีหาแล้วไม่เจอ
          }else{
            return 0;
          }




      } // END if(isset($request->picking_id)){



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

     public function destroy_some(Request $request)
        {

          DB::select(" UPDATE db_pay_requisition_002 SET status_cancel=1 WHERE pick_pack_requisition_code_id_fk='".$request->pick_pack_requisition_code_id_fk."' AND time_pay='".$request->time_pay."' ");
          // เอาสินค้าคืนคลัง
          // return "OK";

          DB::select(" DELETE FROM db_consignments WHERE pick_pack_requisition_code_id_fk='".$request->pick_pack_requisition_code_id_fk."' ");

          $r = DB::select("
            SELECT
            time_pay,business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt_get, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk, shelf_floor,pick_pack_requisition_code_id_fk, created_at,now()
             FROM db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk='".$request->pick_pack_requisition_code_id_fk."' AND time_pay='".$request->time_pay."' ; ");

            foreach ($r as $key => $v) {
                   $_choose=DB::table("db_stocks_return")
                      ->where('time_pay', $v->time_pay)
                      ->where('business_location_id_fk', $v->business_location_id_fk)
                      ->where('branch_id_fk', $v->branch_id_fk)
                      ->where('product_id_fk', $v->product_id_fk)
                      ->where('lot_number', $v->lot_number)
                      ->where('lot_expired_date', $v->lot_expired_date)
                      ->where('amt', $v->amt_get)
                      ->where('product_unit_id_fk', $v->product_unit_id_fk)
                      ->where('warehouse_id_fk', $v->warehouse_id_fk)
                      ->where('zone_id_fk', $v->zone_id_fk)
                      ->where('shelf_id_fk', $v->shelf_id_fk)
                      ->where('shelf_floor', $v->shelf_floor)
                      ->where('pick_pack_requisition_code_id_fk', $request->pick_pack_requisition_code_id_fk)
                      ->get();
                      if($_choose->count() == 0){

                             DB::select(" INSERT INTO db_stocks_return (time_pay,business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk,shelf_floor, pick_pack_requisition_code_id_fk, created_at, updated_at,status_cancel)
                            SELECT
                            time_pay,business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt_get, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk, shelf_floor,pick_pack_requisition_code_id_fk, created_at,now(),1
                             FROM db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk='".$request->pick_pack_requisition_code_id_fk."' AND time_pay='".$v->time_pay."' ; ");


                            $insertStockMovement = new  AjaxController();

                              // รับคืนจากการยกเลิกใบเบิก db_stocks_return
                              $Data = DB::select("
                                      SELECT
                                      db_stocks_return.business_location_id_fk,
                                      db_stocks_return.invoice_code as doc_no,
                                      db_stocks_return.updated_at as doc_date,
                                      db_stocks_return.branch_id_fk,
                                      product_id_fk,
                                      lot_number,
                                      lot_expired_date,
                                      amt,
                                      1 as 'in_out',
                                      product_unit_id_fk,
                                      warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,db_stocks_return.status_cancel as status,
                                      'รับคืนจากการยกเลิกใบเบิก' as note,
                                      db_stocks_return.updated_at as dd,
                                      db_pick_pack_requisition_code.action_user as action_user,'' as approver,'' as approve_date

                                      FROM db_stocks_return
                                      LEFT JOIN db_pick_pack_requisition_code on db_pick_pack_requisition_code.id=db_stocks_return.pick_pack_requisition_code_id_fk
                                      WHERE
                                      db_stocks_return.status_cancel=1

                                ");

                              if(@$Data){

                                  foreach ($Data as $key => $value) {

                                       $insertData = array(
                                          "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                                          "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                                          "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                                          "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                                          "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                                          "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                                          "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                                          "amt" =>  @$value->amt?$value->amt:0,
                                          "in_out" =>  @$value->in_out?$value->in_out:0,
                                          "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                                          "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                                          "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                                          "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                                          "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                                          "status" =>  @$value->status?$value->status:0,
                                          "note" =>  @$value->note?$value->note:NULL,

                                          "action_user" =>  @$value->action_user?$value->action_user:NULL,
                                          "action_date" =>  @$value->action_date?$value->action_date:NULL,
                                          "approver" =>  @$value->approver?$value->approver:NULL,
                                          "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                                          "created_at" =>@$value->dd?$value->dd:NULL
                                      );

                                        $insertStockMovement->insertStockMovement($insertData);

                                    }

                                    DB::select(" INSERT IGNORE INTO db_stock_movement SELECT * FROM db_stock_movement_tmp ORDER BY doc_date asc ");

                               }


                          }



                         DB::select(" UPDATE db_pay_requisition_001 SET status_sent=2 WHERE pick_pack_requisition_code_id_fk='".$request->pick_pack_requisition_code_id_fk."' ; ");

                         DB::select(" UPDATE db_stocks SET db_stocks.amt=db_stocks.amt+(".$v->amt_get.")
                                  WHERE
                                  business_location_id_fk= ".$v->business_location_id_fk." AND
                                  branch_id_fk= ".$v->branch_id_fk." AND
                                  product_id_fk= ".$v->product_id_fk." AND
                                  lot_number= '".$v->lot_number."' AND
                                  lot_expired_date= '".$v->lot_expired_date."' AND
                                  warehouse_id_fk= ".$v->warehouse_id_fk." AND
                                  zone_id_fk= ".$v->zone_id_fk." AND
                                  shelf_id_fk= ".$v->shelf_id_fk." AND
                                  shelf_floor= ".$v->shelf_floor."
                              ");


               }

          $r2 = DB::select("
            SELECT * FROM db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk='".$request->pick_pack_requisition_code_id_fk."' AND time_pay='".$request->time_pay."' AND status_cancel=1 ; ");

             foreach ($r2 as $key => $v) {
                   $_choose=DB::table("db_pay_requisition_002_cancel_log")
                      ->where('time_pay', $v->time_pay)
                      ->where('business_location_id_fk', $v->business_location_id_fk)
                      ->where('branch_id_fk', $v->branch_id_fk)
                      ->where('customers_id_fk', $v->customers_id_fk)
                      ->where('pick_pack_requisition_code_id_fk', $v->pick_pack_requisition_code_id_fk)
                      ->where('product_id_fk', $v->product_id_fk)
                      ->where('amt_need', $v->amt_need)
                      ->where('amt_get', $v->amt_get)
                      ->where('amt_lot', $v->amt_lot)
                      ->where('amt_remain', $v->amt_remain)
                      ->where('lot_number', $v->lot_number)
                      ->where('lot_expired_date', $v->lot_expired_date)
                      ->where('product_unit_id_fk', $v->product_unit_id_fk)
                      ->where('warehouse_id_fk', $v->warehouse_id_fk)
                      ->where('zone_id_fk', $v->zone_id_fk)
                      ->where('shelf_id_fk', $v->shelf_id_fk)
                      ->where('shelf_floor', $v->shelf_floor)
                      ->get();
                      if($_choose->count() == 0){

                              DB::select(" INSERT IGNORE INTO db_pay_requisition_002_cancel_log (time_pay, business_location_id_fk, branch_id_fk, customers_id_fk, pick_pack_requisition_code_id_fk, product_id_fk, product_name, amt_need, amt_get, amt_lot, amt_remain, product_unit_id_fk, product_unit, lot_number, lot_expired_date, warehouse_id_fk, zone_id_fk, shelf_id_fk, shelf_floor, status_cancel, created_at)
                                VALUES
                               (".$v->time_pay.", ".$v->business_location_id_fk.", ".$v->branch_id_fk.", ".$v->customers_id_fk.", '".$v->pick_pack_requisition_code_id_fk."', ".$v->product_id_fk.", '".$v->product_name."', ".$v->amt_get.", 0 , 0, ".$v->amt_get.", ".$v->product_unit_id_fk.", '".$v->product_unit."', '".$v->lot_number."', '".$v->lot_expired_date."', ".$v->warehouse_id_fk.", ".$v->zone_id_fk.", ".$v->shelf_id_fk.", ".$v->shelf_floor.", ".$v->status_cancel.", '".$v->created_at."') ");

                       }


               }


                 $ch_status_cancel = DB::select(" SELECT * FROM db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk='".$request->pick_pack_requisition_code_id_fk."' AND status_cancel in (0) ");
                 if(count(@$ch_status_cancel)==0 || empty(@$ch_status_cancel)){
                    DB::select(" UPDATE db_pay_requisition_001 SET status_sent=1 WHERE pick_pack_requisition_code_id_fk='".$request->pick_pack_requisition_code_id_fk."' ");
                 }

          // มีการยกเลิกตั้งแต่การจ่ายครั้งแรกเลย
           if($request->time_pay==1){
              DB::select(" UPDATE db_pay_requisition_001 SET status_sent=6 WHERE pick_pack_requisition_code_id_fk='".$request->pick_pack_requisition_code_id_fk."' ");
              DB::select(" UPDATE `db_pick_pack_packing_code` SET `status`=6,who_cancel=".@\Auth::user()->id.",cancel_date=now() WHERE (`id` in (".$request->pick_pack_requisition_code_id_fk.")  ) ");

               $r01 =  DB::select(" SELECT * FROM `db_pick_pack_packing_code` WHERE (`id` in (".$request->pick_pack_requisition_code_id_fk.")  ) ");
               $order_id = explode(',', $r01[0]->orders_id_fk);
               if(@$order_id){
                  foreach ($order_id as $key => $v) {
                     DB::select(" UPDATE `db_delivery` SET `status_pick_pack`='0' WHERE orders_id_fk in ($v) ");
                  }
               }

            }else{

               // เช็คว่ามีสินค้าค้างจ่ายหรือไม่
                  $ch01 =  DB::select(" SELECT * FROM db_pay_requisition_002_pay_history WHERE pick_pack_requisition_code_id_fk='".$request->pick_pack_requisition_code_id_fk."' ORDER BY time_pay DESC LIMIT 1 ");

                  // $ch02 =  DB::select(" SELECT * FROM db_pay_requisition_002_cancel_log WHERE pick_pack_requisition_code_id_fk='".$pick_pack_requisition_code_id_fk."' and status_cancel=1 GROUP BY time_pay ORDER BY time_pay DESC LIMIT 1");

                // return count($ch);
                if(@$ch01[0]->amt_remain>0){
                  // 2=สินค้าไม่พอ มีบางรายการค้างจ่าย,3=สินค้าพอต่อการจ่ายครั้งนี้
                  DB::select(" UPDATE db_pay_requisition_001 SET status_sent=2 WHERE pick_pack_requisition_code_id_fk='".$request->pick_pack_requisition_code_id_fk."' ");
                  // 1=รอเบิก, 2=อนุมัติแล้วรอจัดกล่อง (มีค้างจ่ายบางรายการ), 3=อนุมัติแล้วรอจัดกล่อง (ไม่มีค้างจ่าย), 4=Packing กล่องแล้ว, 5=บ.ขนส่งเข้ามารับสินค้าแล้ว, 6=ยกเลิกใบเบิก
                  DB::select(" UPDATE `db_pick_pack_packing_code` SET `status`=2 WHERE (`id` in (".$request->pick_pack_requisition_code_id_fk.")  ) ");
                }else{


                   if(!empty($ch01[0]->time_pay)){

                        $ch03 =  DB::select(" SELECT * FROM `db_pay_requisition_002_pay_history` WHERE pick_pack_requisition_code_id_fk= ".$pick_pack_requisition_code_id_fk." AND time_pay in (".$ch01[0]->time_pay.") AND amt_remain > 0 ");

                        if(count($ch03)>0){
                           DB::select(" UPDATE db_pay_requisition_001 SET status_sent=2 WHERE pick_pack_requisition_code_id_fk='".$request->pick_pack_requisition_code_id_fk."' ");
                           DB::select(" UPDATE `db_pick_pack_packing_code` SET `status`=2 WHERE (`id` in (".$request->pick_pack_requisition_code_id_fk.")  ) ");
                        }else{
                           DB::select(" UPDATE db_pay_requisition_001 SET status_sent=3 WHERE pick_pack_requisition_code_id_fk='".$request->pick_pack_requisition_code_id_fk."' ");
                           DB::select(" UPDATE `db_pick_pack_packing_code` SET `status`=3 WHERE (`id` in (".$request->pick_pack_requisition_code_id_fk.")  ) ");
                        }
                   }

                }



            }





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

      ->addColumn('date_in_stock', function($row) {

        if(!empty($row->pickup_firstdate)){
          return $row->pickup_firstdate;
        }

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


  public function Datatable0001(Request $reg){

      // $sTable = \App\Models\Backend\Pick_packPackingCode::where('id',$reg->picking_id);
      $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id;

      $sTable = DB::select(" select * from $temp_db_pick_pack_requisition_code where id =".$reg->picking_id."  ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
           // $D = DB::select(" ");
          // return "<b>"."P3".sprintf("%05d",$row->pick_pack_requisition_code_id_fk)."</b>";
          return "<b>"."P3".sprintf("%05d",$row->id)."</b>";
      })
      ->addColumn('column_002', function($row) {

           $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list

           $Products = DB::select("
            select $temp_ppp_002.*, (SELECT invoice_code FROM temp_ppp_0011 where id=temp_ppp_0021.frontstore_id_fk LIMIT 1) as invoice_code
            from
            $temp_ppp_002
            where $temp_ppp_002.pick_pack_requisition_code_id_fk=".$row->id."");

            $pn = '<div class="divTable"><div class="divTableBody">';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="width:250px;font-weight:bold;">ชื่อสินค้า (เลขที่ใบเสร็จ)</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;">จำนวนที่สั่งซื้อ</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;"> หน่วยนับ </div>
            </div>
            ';

            $sum_amt = 0 ;

              foreach ($Products as $key => $value) {

                $p_unit  = DB::select("  SELECT product_unit
                FROM
                dataset_product_unit
                WHERE id = ".$value->product_unit_id_fk." AND  lang_id=1  ");
               $p_unit_name = @$p_unit[0]->product_unit;

              $sum_amt += $value->amt;
              $pn .=
              '<div class="divTableRow">
              <div class="divTableCell" style="font-weight:bold;padding-bottom:15px;">
              '.$value->product_name.'<br>('.$value->invoice_code.')
              </div>
              <div class="divTableCell" style="text-align:center;">'.$value->amt.'</div>
              <div class="divTableCell" style="text-align:center;">'.$p_unit_name.'</div>
              ';

              $pn .= '</div>';

            }

              $pn .=
              '<div class="divTableRow">
              <div class="divTableCell" style="text-align:right;font-weight:bold;"> รวม </div>
              <div class="divTableCell" style="text-align:center;font-weight:bold;">'.$sum_amt.'</div>
              <div class="divTableCell" style="text-align:center;"> </div>
              </div>
              ';

              $pn .= '</div>';

          return $pn;
      })
      ->escapeColumns('column_002')
      ->make(true);
    }

// /backend/pay_product_receipt
// http://localhost/aiyara/backend/pick_warehouse
// %%%%%%%%%%%%%%%%%%%%%%% ไม่มีสินค้าในคลัง
   public function Datatable0002FIFO(Request $request){
    // return $request->picking_id;
      $temp_ppp_001 = "temp_ppp_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_ppp_0022 = "temp_ppp_0022".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      // $temp_ppp_0023 = "temp_ppp_0023".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
      $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
      $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id;
      $temp_db_pick_pack_packing_code = "db_pick_pack_packing_code".\Auth::user()->id;
       // วุฒิเพิ่มมา
      $db_pick_pack_packing_code_data = DB::table('db_pick_pack_packing_code')->where('id',$request->picking_id)->first();
      if($db_pick_pack_packing_code_data){
        $arr_order = explode(',',$db_pick_pack_packing_code_data->receipt);
        $all_orders = DB::table('db_orders')->whereIn('invoice_code',$arr_order)->pluck('id')->toArray();
        $all_orders_lists = DB::table('db_order_products_list')->whereIn('frontstore_id_fk',$all_orders)->get();
      }
      $TABLES = DB::select(" SHOW TABLES ");
      // return $TABLES;
      $array_TABLES = [];
      foreach($TABLES as $t){
        // print_r($t->Tables_in_aiyaraco_v3);
        array_push($array_TABLES, $t->Tables_in_aiyaraco_v3);
      }
      // เก็บรายการสินค้าที่จ่าย ตอน FIFO

      if(in_array($temp_ppp_004,$array_TABLES)){
        // return "IN";
        DB::select(" TRUNCATE TABLE $temp_ppp_004  ");
      }else{
        // return "Not";
        DB::select(" CREATE TABLE $temp_ppp_004 LIKE temp_ppp_004_template ");
      }

      // $picking_id = implode(",",$request->picking_id);
      $picking_id = $request->picking_id;

      $r_db_pick_pack_packing_code = DB::select(" SELECT * FROM db_pick_pack_packing_code WHERE id in($picking_id) ");
      // return $r_db_pick_pack_packing_code[0]->receipt ;
      $receipt = explode(",",$r_db_pick_pack_packing_code[0]->receipt);

      $arr_01 = [];
      foreach ($receipt as $key => $value) {
        array_push($arr_01,'"'.$value.'"');
      }

      $receipt = implode(",",$arr_01);

      // return $picking_id;
//  SELECT * from $temp_ppp_0022 WHERE pick_pack_requisition_code_id_fk in($picking_id)  GROUP BY pick_pack_requisition_code_id_fk
      $sTable = DB::select("
            SELECT * from $temp_ppp_0022 GROUP BY pick_pack_requisition_code_id_fk
        ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('column_001', function($row) {

            $pn = '<div class="divTable"><div class="divTableBody">';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="width:50px;font-weight:bold;">ครั้งที่</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;">วันที่จ่าย</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;"> พนักงาน </div>
            </div>
            ';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            </div>
            ';

            return $pn;

        })
       ->escapeColumns('column_001')
       ->addColumn('column_002', function($row) {

          $temp_ppp_001 = "temp_ppp_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
          $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list
          $temp_ppp_0022 = "temp_ppp_0022".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list
          $temp_ppp_003 = "temp_ppp_003".\Auth::user()->id; // เก็บรายการที่หยิบสินค้ามาจากชั้นต่างๆ ตอน FIFO
          $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // เก็บรายการที่หยิบสินค้ามาจากชั้นต่างๆ ตอน FIFO
          $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
          $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
          $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;



           $Products = DB::select("
           SELECT
            id,
            pick_pack_requisition_code_id_fk,
            pick_pack_packing_code_id_fk,
            product_id_fk,
            product_name,
            sum(amt) as amt,
            product_unit_id_fk from $temp_ppp_0022 GROUP BY product_id_fk

          ");

            $arr_pick_pack_packing_code_id_fk = explode(',',$row->pick_pack_packing_code_id_fk);

            $sTable1 = DB::select(" SELECT * FROM $temp_ppp_001 WHERE `pick_pack_packing_code_id_fk` in(".$row->pick_pack_packing_code_id_fk.") ");
            $arr1 = [];
            foreach ($sTable1 as $key => $v) {
              array_push($arr1,$v->id);
            }
            $orders_id_fk = implode(',',$arr1);


          $pn = '<div class="divTable"><div class="divTableBody">';
          $pn .=
          '<div class="divTableRow">
          <div class="divTableCell" style="width:240px;font-weight:bold;">ชื่อสินค้า</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จ่ายครั้งนี้</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">ค้างจ่าย</div>
          <div class="divTableCell" style="width:450px;text-align:center;font-weight:bold;">

                      <div class="divTableRow">
                      <div class="divTableCell" style="width:220px;text-align:center;font-weight:bold;">หยิบสินค้าจากคลัง.</div>
                      <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">Lot number [Expired]</div>
                      <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;">จำนวน</div>
                      </div>

          </div>
          </div>
          ';

          $amt_pay_remain = 0;
          $amt_lot = 0;
          $amt_to_take = 0;
          $pay_this = 0;

          DB::select(" DROP TABLE IF EXISTS temp_001; ");
          // TEMPORARY
          DB::select(" CREATE TEMPORARY TABLE temp_001 LIKE temp_db_stocks_amt_template_02 ");


            $Products_02 = DB::select("
            SELECT
            db_order_products_list.product_id_fk,
            db_order_products_list.product_name,
            db_order_products_list.product_unit_id_fk,
            SUM(db_order_products_list.amt) AS amt ,
            dataset_product_unit.product_unit
            from db_order_products_list
            LEFT Join dataset_product_unit ON db_order_products_list.product_unit_id_fk = dataset_product_unit.id
            LEFT Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
            WHERE db_orders.id in  (".$orders_id_fk.")
            GROUP BY db_order_products_list.product_id_fk
            ORDER BY db_order_products_list.product_id_fk ");

            // dd($Products);

          $z = 1;
          // เช็คหาสินค้าค่าขนส่ง
          $shipping = DB::table('products')->select('id')->where('shipping_status',1)->pluck('id')->toArray();
          foreach ($Products as $key => $value) {


            $ship = 0;
            foreach($shipping as $sh){
              if($sh==$value->product_id_fk){
                $ship++;
              }
            }
            if($ship>0){
              $amt_pay_this = 0;
            }else{
              $amt_pay_this = $value->amt;
            }

            // if($value->product_id_fk == 18){
            //   dd($temp_db_stocks_01[0]->amt); ไม่มีสินค้าในคลัง
            // }

            // dd($value);
               // บิลปกติ
                $arr_inv = [];
                $p1 = DB::select(" select db_orders.code_order FROM db_order_products_list
                LEFT JOIN db_orders on db_orders.id = db_order_products_list.frontstore_id_fk
                WHERE db_orders.id in ($orders_id_fk) AND db_order_products_list.product_id_fk in ($value->product_id_fk)  AND type_product='product' ");
                if(@$p1){
                  foreach (@$p1 as $inv) {
                      array_push($arr_inv,@$inv->code_order);
                  }
                }

                // บิลโปร
                $p2 = DB::select("
                select db_orders.code_order
                FROM `promotions_products`
                LEFT JOIN db_order_products_list on db_order_products_list.promotion_id_fk=promotions_products.promotion_id_fk
                LEFT Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
                WHERE db_orders.id in  ($orders_id_fk) AND promotions_products.product_id_fk in ($value->product_id_fk) AND db_order_products_list.type_product='promotion' ");
                if(@$p2){
                  foreach (@$p2 as $inv) {
                      array_push($arr_inv,@$inv->code_order);
                  }
                }

                // วุฒิเพิ่มมาดักยาวเกิน
                // $invoice_code = implode(",",$arr_inv);
                $data_alert = "";
                foreach($arr_inv as $index =>$ai){
                  $data_alert.=$ai.",";
                }

                $invoice_code = '<a href="javascript:;" class="alert_data" data_alert="'.$data_alert.'">';
                foreach($arr_inv as $index =>$ai){
                  $invoice_code .= $ai;
                  if($index+1 != count($arr_inv)){
                    $invoice_code .= ',';
                  }
                  if($index+1 == 3){
                    if(count($arr_inv)> 3){
                      $invoice_code .= $ai.',...';
                    }
                    break;
                  }
                }

                $invoice_code .= '</a>';



               $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
              //  dd( $temp_db_stocks );

                   // วุฒิเพิ่มมาเช็คคลัง ว่าอย่าเอาคลังเก็บมา
                   $w_arr2 = DB::table('warehouse')->select('id')->where('w_code','WH02')->pluck('id')->toArray();
                   $w_str2 = '';
                   foreach($w_arr2 as $key => $w){
                     if($key+1==count($w_arr2)){
                       $w_str2.=$w;
                     }else{
                       $w_str2.=$w.',';
                     }

                   }

                   $zone_arr2 = DB::table('zone')->select('id')->where('z_code','Zone-1')->pluck('id')->toArray();
                   $zone_str2 = '';
                   foreach($zone_arr2 as $key => $w){
                     if($key+1==count($zone_arr2)){
                       $zone_str2.=$w;
                     }else{
                       $zone_str2.=$w.',';
                     }

                   }
                  //  * ไม่มีสินค้าในคลัง
// dd(Auth::user());
                // จำนวนที่จะ Hint ให้ไปหยิบจากแต่ละชั้นมา ตามจำนวนที่สั่งซื้อ โดยการเช็คไปทีละชั้น fifo จนกว่าจะพอ
                // เอาจำนวนที่เบิก เป็นเช็ค กับ สต๊อก ว่ามีพอหรือไม่ โดยเอาทุกชั้นที่มีมาคิดรวมกันก่อนว่าพอหรือไม่
                // $temp_db_stocks_01 = DB::select(" SELECT sum(amt) as amt,count(*) as amt_floor from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk."  ");

                if(Auth::user()->branch_id_fk == 6){

                    // ให้ฝ่ายคลังดึงชั้น 1 เท่านั้น
                    $temp_db_stocks_01 = DB::select(" SELECT sum(amt) as amt,count(*) as amt_floor from $temp_db_stocks WHERE amt>0 AND shelf_floor = 1 AND warehouse_id_fk in (".$w_str2.") AND zone_id_fk in (".$zone_str2.") AND product_id_fk=".$value->product_id_fk."  ");
                  }else{
                  $temp_db_stocks_01 = DB::select(" SELECT sum(amt) as amt,count(*) as amt_floor from $temp_db_stocks WHERE amt>0 AND warehouse_id_fk in (".$w_str2.") AND zone_id_fk in (".$zone_str2.") AND product_id_fk=".$value->product_id_fk."  ");
                }

                $amt_floor = $temp_db_stocks_01[0]->amt_floor;
                // วุฒิเพิ่มมา
                // dd($invoice_code);
                // if($db_pick_pack_packing_code_data){
                // $all_orders_lists = DB::table('db_order_products_list')->whereIn('frontstore_id_fk',$all_orders)->where('')->get();
                // $data_remark = '';
                // }



                // Case 1 > มีสินค้าพอ (รวมจากทุกชั้น) และ ในคลังมีมากกว่า ที่ต้องการซื้อ
                if($temp_db_stocks_01[0]->amt>0 && $temp_db_stocks_01[0]->amt>=$amt_pay_this ){

                  $pay_this = $value->amt ;
                  $amt_pay_remain = 0;

                    $pn .=
                    '<div class="divTableRow">
                    <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                    '.@$value->product_name.'</b><br>
                        ('.@$invoice_code.')
                    </div>
                    <div class="divTableCell" style="text-align:center;font-weight:bold;">'. $pay_this .'</div>
                    <div class="divTableCell" style="text-align:center;"> '.$amt_pay_remain.' </div>
                    <div class="divTableCell" style="width:450px;text-align:center;"> ';

                    // Case 1.1 > ไล่หาแต่ละชั้น ตาม FIFO ชั้นที่จะหมดอายุก่อน เอาออกมาก่อน

                    $w_arr = DB::table('warehouse')->where('w_code','WH02')->pluck('id')->toArray();
                    $w_str = '';
                    foreach($w_arr as $key => $w){
                      if($key+1==count($w_arr)){
                        $w_str.=$w;
                      }else{
                        $w_str.=$w.',';
                      }

                    }

                    $zone_arr2 = DB::table('zone')->select('id')->where('z_code','Zone-1')->pluck('id')->toArray();
                    $zone_str2 = '';
                    foreach($zone_arr2 as $key => $w){
                      if($key+1==count($zone_arr2)){
                        $zone_str2.=$w;
                      }else{
                        $zone_str2.=$w.',';
                      }

                    }

                    // wut อันนี้แก้ จ่ายผิดคลัง
                    if(Auth::user()->branch_id_fk == 6){
                      $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." AND shelf_floor = 1 AND warehouse_id_fk in (".$w_str.") AND zone_id_fk in (".$zone_str2.") ORDER BY lot_expired_date ASC  ");
                    }else{
                      $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." AND warehouse_id_fk in (".$w_str.") AND zone_id_fk in (".$zone_str2.") ORDER BY lot_expired_date ASC  ");
                   }



                    //  $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC  ");

                          DB::select(" DROP TABLE IF EXISTS temp_001; ");
                          // TEMPORARY
                          DB::select(" CREATE TEMPORARY TABLE temp_001 LIKE temp_db_stocks_amt_template_02 ");


                     $i = 1;
                     foreach ($temp_db_stocks_02 as $v_02) {

                          $rs_ = DB::select(" SELECT amt_remain FROM temp_001 where id>0 order by id desc limit 1 ");
                          $amt_remain = @$rs_[0]->amt_remain?@$rs_[0]->amt_remain:0;
                          $pay_this = @$rs_[0]->amt_remain?@$rs_[0]->amt_remain:$value->amt ;

                          $branch = DB::select(" select * from branchs where id=".$v_02->branch_id_fk." ");
                          $warehouse = DB::select(" select * from warehouse where id=".$v_02->warehouse_id_fk." ");
                          $zone = DB::select(" select * from zone where id=".$v_02->zone_id_fk." ");
                          $shelf = DB::select(" select * from shelf where id=".$v_02->shelf_id_fk." ");
                          $sWarehouse = @$branch[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$v_02->shelf_floor;

                          // Check ว่า ชั้นแรกๆ มีพอมั๊ย ถ้ามีพอแล้ว ก็หยุดค้น
                          // ถ้าจำนวนในคลังมีพอ เอาค่าจาก ที่ต้องการ
                            $amt_in_wh = @$v_02->amt?@$v_02->amt:0;
                            $amt_to_take = $pay_this;


                            if($amt_to_take>=$amt_in_wh){
                              DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_in_wh,$amt_to_take-$amt_in_wh) ");
                              $rs_ = DB::select(" SELECT amt_get FROM temp_001 order by id desc limit 1 ");
                              $amt_to_take = $rs_[0]->amt_get;
                            }else{
                              // $r_before =  DB::select(" select * from temp_001 where amt_remain<>0 order by id desc limit 1 ");
                              // $amt_remain = $r_before[0]->amt_remain;
                              DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_to_take,0) ");
                              $rs_ = DB::select(" SELECT amt_get FROM temp_001 order by id desc limit 1 ");
                              $amt_to_take = $rs_[0]->amt_get;
                            }


                                $pn .=
                                '<div class="divTableRow">
                                <div class="divTableCell" style="width:220px;text-align:center;"> '.$sWarehouse.' </div>
                                <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;color:blue;"> '.$amt_to_take.' </div>
                                </div>
                                ';

                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                     $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                     $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id;

                                     $_choose=DB::table("$temp_ppp_004")
                                      ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      ->where('lot_number', $v_02->lot_number)
                                      ->where('lot_expired_date', $v_02->lot_expired_date)
                                      ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                      ->where('zone_id_fk', $v_02->zone_id_fk)
                                      ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                      ->where('shelf_floor', $v_02->shelf_floor)
                                      ->get();
                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppp_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              pick_pack_requisition_code_id_fk,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              lot_number,
                                              lot_expired_date,
                                              warehouse_id_fk,
                                              zone_id_fk,
                                              shelf_id_fk,
                                              shelf_floor,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$value->pick_pack_requisition_code_id_fk."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt."',
                                                '".$amt_to_take."',
                                                '".$amt_to_take."',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->lot_number."',
                                                '".$v_02->lot_expired_date."',
                                                '".$v_02->warehouse_id_fk."',
                                                '".$v_02->zone_id_fk."',
                                                '".$v_02->shelf_id_fk."',
                                                '".$v_02->shelf_floor."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                                     $r_temp_001 = DB::select(" SELECT amt_remain FROM temp_001 order by id desc limit 1 ; ");
                                     // return $r_temp_001[0]->amt_remain;
                                     if($r_temp_001[0]->amt_remain==0) break;

                          $i++;

                     }


                // Case 2 > กรณีมีบ้างเป็นบางรายการ (รวมจากทุกชั้น) และ ในคลังมี น้อยกว่า ที่ต้องการซื้อ
                }else if($temp_db_stocks_01[0]->amt>0 && $temp_db_stocks_01[0]->amt<$amt_pay_this){ // กรณีมีบ้างเป็นบางรายการ

                     $pay_this = $temp_db_stocks_01[0]->amt ;
                     $amt_pay_remain = $value->amt - $temp_db_stocks_01[0]->amt ;
                     $css_red = $amt_pay_remain>0?'color:red;font-weight:bold;':'';

                    $pn .=
                    '<div class="divTableRow">
                     <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                    '.@$value->product_name.'</b><br>
                        ('.@$invoice_code.')
                    </div>
                    <div class="divTableCell" style="text-align:center;font-weight:bold;">'. $pay_this .'</div>
                    <div class="divTableCell" style="text-align:center;'.$css_red.'">'. $amt_pay_remain .'</div>
                    <div class="divTableCell" style="width:450px;text-align:center;"> ';

                     // Case 2.1 > ไล่หาแต่ละชั้น ตาม FIFO ชั้นที่จะหมดอายุก่อน เอาออกมาก่อน
                     $w_arr = DB::table('warehouse')->where('w_code','WH02')->pluck('id')->toArray();
                     $w_str = '';
                     foreach($w_arr as $key => $w){
                       if($key+1==count($w_arr)){
                         $w_str.=$w;
                       }else{
                         $w_str.=$w.',';
                       }

                     }

                     $zone_arr2 = DB::table('zone')->select('id')->where('z_code','Zone-1')->pluck('id')->toArray();
                     $zone_str2 = '';
                     foreach($zone_arr2 as $key => $w){
                       if($key+1==count($zone_arr2)){
                         $zone_str2.=$w;
                       }else{
                         $zone_str2.=$w.',';
                       }

                     }


                     // wut อันนี้แก้ จ่ายผิดคลัง
                     if(Auth::user()->branch_id_fk == 6){
                      $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." AND shelf_floor = 1 AND warehouse_id_fk in (".$w_str.") AND zone_id_fk in (".$zone_str2.") ORDER BY lot_expired_date ASC  ");
                    }else{
                      $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." AND warehouse_id_fk in (".$w_str.") AND zone_id_fk in (".$zone_str2.") ORDER BY lot_expired_date ASC  ");
                    }

                    //  $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC  ");

                     $i = 1;
                     foreach ($temp_db_stocks_02 as $v_02) {
                          $zone = DB::select(" select * from zone where id=".$v_02->zone_id_fk." ");
                          $shelf = DB::select(" select * from shelf where id=".$v_02->shelf_id_fk." ");
                          $sWarehouse = @$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$v_02->shelf_floor;

                          // Check ว่า ชั้นแรกๆ มีพอมั๊ย ถ้ามีพอแล้ว ก็หยุดค้น
                          // ถ้าจำนวนในคลังมีพอ เอาค่าจาก ที่ต้องการ
                          $amt_to_take = $pay_this;
                          $amt_in_wh = @$v_02->amt?@$v_02->amt:0;

                            // ในคลัง พิจารณารายชั้น ถ้าชั้นนั้นๆ น้อยกว่า ที่ต้องการ

                               if($i==1){
                                    DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_in_wh,".($amt_to_take-$amt_in_wh).") ");
                                    $rs_ = DB::select(" SELECT amt_get FROM temp_001 WHERE id='$i' ");
                                    $amt_to_take = $rs_[0]->amt_get;

                                   $pn .=
                                    '<div class="divTableRow">
                                    <div class="divTableCell" style="width:200px;text-align:center;">'.$sWarehouse.'</div>
                                    <div class="divTableCell" style="width:200px;text-align:center;">'.$v_02->lot_number.' ['.$v_02->lot_expired_date.']</div>
                                    <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;color:blue;">'.$pay_this.'</div>
                                    </div>
                                    ';

                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                     $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                     $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id;

                                     $_choose=DB::table("$temp_ppp_004")
                                      ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      ->where('lot_number', $v_02->lot_number)
                                      ->where('lot_expired_date', $v_02->lot_expired_date)
                                      ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                      ->where('zone_id_fk', $v_02->zone_id_fk)
                                      ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                      ->where('shelf_floor', $v_02->shelf_floor)
                                      ->get();
                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppp_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              pick_pack_requisition_code_id_fk,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              lot_number,
                                              lot_expired_date,
                                              warehouse_id_fk,
                                              zone_id_fk,
                                              shelf_id_fk,
                                              shelf_floor,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$value->pick_pack_requisition_code_id_fk."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt."',
                                                '".$pay_this."',
                                                '".$pay_this."',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->lot_number."',
                                                '".$v_02->lot_expired_date."',
                                                '".$v_02->warehouse_id_fk."',
                                                '".$v_02->zone_id_fk."',
                                                '".$v_02->shelf_id_fk."',
                                                '".$v_02->shelf_floor."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }

                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                      $r_temp_001 = DB::select(" SELECT amt_remain FROM temp_001 order by id desc limit 1 ; ");
                                     // return $r_temp_001[0]->amt_remain;
                                     if($r_temp_001[0]->amt_remain==0) break;

                                }else{


                                    $r_before =  DB::select(" select * from temp_001 where amt_remain<>0 order by id desc limit 1 ");
                                    $amt_remain = @$r_before[0]->amt_remain?@$r_before[0]->amt_remain:0;

                                    DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_remain,".($r_before[0]->amt_remain-$amt_in_wh).") ");
                                    $rs_ = DB::select(" SELECT * FROM temp_001 WHERE id='$i' ");
                                    $amt_to_take = $amt_in_wh;

                                    $pn .=
                                    '<div class="divTableRow">
                                    <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                    <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                    <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;color:blue;"> '.$amt_to_take.' </div>
                                    </div>
                                    ';
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                                    $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                     $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id;

                                     $_choose=DB::table("$temp_ppp_004")
                                      ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      ->where('lot_number', $v_02->lot_number)
                                      ->where('lot_expired_date', $v_02->lot_expired_date)
                                      ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                      ->where('zone_id_fk', $v_02->zone_id_fk)
                                      ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                      ->where('shelf_floor', $v_02->shelf_floor)
                                      ->get();
                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppp_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              pick_pack_requisition_code_id_fk,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              lot_number,
                                              lot_expired_date,
                                              warehouse_id_fk,
                                              zone_id_fk,
                                              shelf_id_fk,
                                              shelf_floor,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$value->pick_pack_requisition_code_id_fk."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt."',
                                                '".$amt_to_take."',
                                                '".$amt_to_take."',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->lot_number."',
                                                '".$v_02->lot_expired_date."',
                                                '".$v_02->warehouse_id_fk."',
                                                '".$v_02->zone_id_fk."',
                                                '".$v_02->shelf_id_fk."',
                                                '".$v_02->shelf_floor."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }

                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                                 $r_temp_001 = DB::select(" SELECT amt_remain FROM temp_001 order by id desc limit 1 ; ");
                                     // return $r_temp_001[0]->amt_remain;
                                     if($r_temp_001[0]->amt_remain==0) break;
                                }


                          $i++;

                     }



                }else{ // กรณีไม่มีสินค้าในคลังเลย
                    if($amt_pay_this > 0){
                      $amt_pay_remain = $value->amt ;
                      $pay_this = 0 ;
                      $css_red = $amt_pay_remain>0?'color:red;font-weight:bold;':'';

                     $pn .=
                     '<div class="divTableRow">
                      <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                     '.@$value->product_name.'</b><br>
                         ('.@$invoice_code.')
                     </div>
                     <div class="divTableCell" style="text-align:center;font-weight:bold;">'. $pay_this .'</div>
                     <div class="divTableCell" style="text-align:center;'.$css_red.'">'. $amt_pay_remain .'</div>
                     <div class="divTableCell" style="width:400px;text-align:center;"> ';


                       $pn .=
                             '<div class="divTableRow" style="text-align:center;">
                             <div class="divTableCell" style="width:200px;text-align:center;color:red;"><center>* ไม่มีสินค้าในคลัง </div>
                             </div>
                             ';

                             @$_SESSION['check_product_instock'] = 0;

                               // $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt=0 and product_id_fk=".$value->product_id_fk." ");
                               $temp_db_stocks_02 = DB::select(" SELECT * from $temp_ppp_002 WHERE  product_id_fk=".$value->product_id_fk." ");

                               // วุฒิเพิ่มมาสำหรับตรวจโปรโมชั่น
                               if(count($temp_db_stocks_02)==0){
                                 $temp_db_stocks_02 = DB::table($temp_ppp_002)
                                 ->select(
                                   $temp_ppp_002.'.created_at',
                                   'promotions_products.product_unit as product_unit_id_fk',
                                   'promotions_products.product_id_fk as product_id_fk',
                                   DB::raw('CONCAT(products.product_code," : ", products_details.product_name) AS product_name'),
                                   'promotions_products.product_amt as amt',
                                 )
                                 ->join('promotions_products','promotions_products.promotion_id_fk',$temp_ppp_002.'.promotion_id_fk')
                                 ->join('products_details','products_details.product_id_fk','promotions_products.product_id_fk')
                                 ->join('products','products.id','promotions_products.product_id_fk')
                                 ->where($temp_ppp_002.'.type_product','promotion')
                                 ->where('promotions_products.product_id_fk',$value->product_id_fk)
                                 ->groupBy('promotions_products.product_id_fk')
                                 ->get();
                               }

                      $i = 1;
                      foreach ($temp_db_stocks_02 as $v_02) {

                              // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                      $p_unit  = DB::select("  SELECT product_unit
                                       FROM
                                       dataset_product_unit
                                       WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                      $p_unit_name = @$p_unit[0]->product_unit;

                                      $_choose=DB::table("$temp_ppp_004")
                                       ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                                       ->where('product_id_fk', $v_02->product_id_fk)
                                       ->where('branch_id_fk', @\Auth::user()->branch_id_fk )
                                       ->get();
                                         if($_choose->count() == 0){
                                               DB::select(" INSERT IGNORE INTO $temp_ppp_004 (
                                               business_location_id_fk,
                                               branch_id_fk,
                                               pick_pack_requisition_code_id_fk,
                                               product_id_fk,
                                               product_name,
                                               amt_need,
                                               amt_get,
                                               amt_lot,
                                               amt_remain,
                                               product_unit_id_fk,
                                               product_unit,
                                               created_at
                                               )
                                               VALUES (
                                                 '".@\Auth::user()->business_location_id_fk."',
                                                 '".@\Auth::user()->branch_id_fk."',
                                                 '".$row->pick_pack_requisition_code_id_fk."',
                                                 '".$v_02->product_id_fk."',
                                                 '".$v_02->product_name."',
                                                 '".$v_02->amt."',
                                                 '0',
                                                 '0',
                                                 '".$amt_pay_remain."',
                                                 '".$v_02->product_unit_id_fk."',
                                                 '".$p_unit_name."',
                                                 '".$v_02->created_at."'
                                               )
                                             ");
                                         }
                                 // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                               $i++;

                         }
                    }


                }

            $pn .= '</div>';
            $pn .= '</div>';

            $z++;
          }

          $pn .= '</div>';

            $rs_temp_ppp_004 = DB::select(" select product_id_fk,lot_number,lot_expired_date,sum(amt_get) as amt_get, sum(amt_lot) as amt_lot, amt_remain  from $temp_ppp_004 group by pick_pack_requisition_code_id_fk,product_id_fk,lot_number  ");

          foreach ($rs_temp_ppp_004 as $key => $v) {

              $_choose=DB::table("$temp_db_stocks_check")
                // ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                ->where('product_id_fk', $v->product_id_fk)
                ->where('lot_number', $v->lot_number)
                // ->where('lot_expired_date', $v->lot_expired_date)
                // ->where('amt_get', $v->amt_get)
                // ->where('amt_lot', $v->amt_lot)
                // ->where('amt_remain', $v->amt_remain)
                ->get();
                if($_choose->count() == 0){
                  DB::select(" INSERT IGNORE INTO $temp_db_stocks_check (pick_pack_requisition_code_id_fk,product_id_fk,lot_number,lot_expired_date,amt_get,amt_lot,amt_remain)
                    VALUES (
                    '".$row->pick_pack_requisition_code_id_fk."', ".$v->product_id_fk.", '".$v->lot_number."', '".$v->lot_expired_date."', ".$v->amt_lot.", '".$v->amt_lot."', '".$v->amt_remain."'
                    )
                  ");
                }

          }


          $rs_temp_db_stocks_check = DB::select(" select pick_pack_requisition_code_id_fk, sum(amt_get) as amt_get, sum(amt_lot) as amt_lot, sum(amt_remain) as amt_remain from $temp_db_stocks_check group by pick_pack_requisition_code_id_fk ");

          foreach ($rs_temp_db_stocks_check as $key => $v) {

              $_choose=DB::table("$temp_db_stocks_compare")
                // ->where('invoice_code', $row->invoice_code)
                // ->where('amt_get', $v->amt_get)
                // ->where('amt_lot', $v->amt_lot)
                // ->where('amt_remain', $v->amt_remain)
                ->get();
                // if($_choose->count() == 0){
                  DB::select(" INSERT IGNORE INTO $temp_db_stocks_compare (pick_pack_requisition_code_id_fk, amt_get, amt_lot, amt_remain)
                    VALUES (
                    '".$row->pick_pack_requisition_code_id_fk."', ".$v->amt_get.", '".$v->amt_lot."', '".$v->amt_remain."'
                    )
                  ");
                // }

          }

          return $pn;
      })
      ->escapeColumns('column_002')
      ->addColumn('column_003', function($row) {

          $pn = '<div class="divTable"><div class="divTableBody">';
          $pn .=
          '<div class="divTableRow">
          <div class="divTableCell" style="width:250px;text-align:center;font-weight:bold;">หยิบสินค้าจากคลัง</div>
          <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">Lot number [Expired]</div>
          <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;">จำนวน</div>
          </div>
          ';
          $pn .= '</div>';
          return $pn;

       })
      ->escapeColumns('column_003')
      ->addColumn('ch_amt_lot_wh', function($row) {
// ดูว่าไม่มีสินค้าคลังเลย

          $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id;

          $Products = DB::select("
            SELECT * from $temp_ppp_004 WHERE pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."'
          ");

          if(count($Products)>0){
              $arr = [];
              foreach ($Products as $key => $value) {
                array_push($arr,$value->product_id_fk);
              }
              $arr_im = implode(',',$arr);
              $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
              $r = DB::select(" SELECT sum(amt) as sum FROM $temp_db_stocks WHERE product_id_fk in ($arr_im) ");
              return @$r[0]->sum?@$r[0]->sum:0;
          }else{
              return 0;
          }

       })

      ->addColumn('check_product_instock', function($row) {
      // ดูว่าไม่มีสินค้าในคลังบางรายการ
          if(@$_SESSION['check_product_instock']=="0"){
            return "N";
          }else{
            return "Y";
          }

       })
      ->make(true);
    }



    public function ajaxSearch_requisition_db_orders(Request $request)
    {

      $business_location_id_fk = \Auth::user()->business_location_id_fk;
      $branch_id_fk = \Auth::user()->branch_id_fk;
      $temp_ppp_001 = "temp_ppp_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list
      $temp_ppp_003 = "temp_ppp_003".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
      $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // เก็บรายการที่หยิบสินค้ามาจากชั้นต่างๆ ตอน FIFO
      $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
      $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;

      $TABLES = DB::select(" SHOW TABLES ");
      // return $TABLES;
      $array_TABLES = [];
      foreach($TABLES as $t){
        // print_r($t->Tables_in_aiyaraco_v3);
        array_push($array_TABLES, $t->Tables_in_aiyaraco_v3);
      }

      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ");
      DB::select(" CREATE TABLE $temp_db_stocks_check LIKE temp_db_stocks_check_template ");

      // dd();

    // return $request;
    // หาในตาราง db_orders ว่ามีมั๊ย
    // คิวรีจาก db_orders ที่ branch_id_fk = sentto_branch_id & delivery_location = 0
    // กรณีที่ เป็น invoice_code (เพราะมี 2 กรณี คือ invoice_code กับ QR_CODE)
    $tb0 = DB::select(" SELECT * FROM `db_pick_pack_requisition_code".\Auth::user()->id."` WHERE `pick_pack_packing_code_id_fk` in(".$request->packing_id.") ");

    if(@$tb0){

    $packing_id = $tb0[0]->id;

          $tb1 = DB::select(" SELECT * FROM `db_pick_pack_requisition_code".\Auth::user()->id."` WHERE `id` in(".$packing_id.") ");
          // return $tb1;
          // dd();


          $tb2 = DB::select(" SELECT * FROM `db_pick_pack_packing_code` WHERE `id` in(".$tb1[0]->pick_pack_packing_code_id_fk.") ");
          $arr1 = [];
          foreach ($tb2 as $key => $v) {
            array_push($arr1,$v->orders_id_fk);
          }
          $orders_id_fk = array_filter($arr1);

          // dd($orders_id_fk);

          $orders_id_fk = implode(',',$orders_id_fk);
          if($orders_id_fk==""){
            $orders_id_fk = "0";
          }
  // dd($orders_id_fk);
          // return $orders_id_fk;
          $r01 = DB::select(" SELECT invoice_code FROM db_orders where id in ($orders_id_fk) ");
    }

    // return $r01[0]->invoice_code;
    if(@$r01){

        DB::select(" DROP TABLE IF EXISTS $temp_ppp_001; ");
        DB::select(" CREATE TABLE $temp_ppp_001 LIKE db_orders ");
        DB::select(" INSERT $temp_ppp_001 SELECT * FROM db_orders WHERE id in ($orders_id_fk) ");

        DB::select(" ALTER TABLE $temp_ppp_001
        ADD COLUMN pick_pack_packing_code_id_fk  int NULL DEFAULT 0 COMMENT 'Ref>db_pick_pack_packing_code>id' AFTER id ");

        DB::select(" ALTER TABLE $temp_ppp_001
        ADD COLUMN pick_pack_requisition_code_id_fk  int NULL DEFAULT 0 COMMENT 'Ref>db_pick_pack_requisition_code>id' AFTER id ");

        DB::select(" UPDATE $temp_ppp_001 SET pick_pack_requisition_code_id_fk=$packing_id ");

        DB::select(" DROP TABLE IF EXISTS $temp_ppp_002; ");
        DB::select(" CREATE TABLE $temp_ppp_002 LIKE db_order_products_list ");
        DB::select(" INSERT $temp_ppp_002
        SELECT db_order_products_list.* FROM db_order_products_list INNER Join $temp_ppp_001 ON db_order_products_list.frontstore_id_fk = $temp_ppp_001.id ");
        $Data = DB::select(" SELECT * FROM $temp_ppp_002; ");
        return $Data;

        // เอาไปแสดงชั่วคราวไว้ในตารางที่ค้นก่อน หน้า จ่ายสินค้าตามใบเสร็จ /backend/pay_product_receipt
        // ตารางส่วนบน
        // ดึงจาก $temp_ppp_001 & $temp_ppp_002 ลง temp ก่อน แค่แสดง แต่หลังจากกดปุ่ม save แล้ว ค่อยเก็บลงตารางจริง

       // ต้องมีอีกตารางนึง เก็บ สถานะการอนุมัติ และ ที่อยู่การจัดส่งสินค้า > $temp_ppp_003
          DB::select(" DROP TABLE IF EXISTS $temp_ppp_003; ");
          DB::select(" CREATE TABLE $temp_ppp_003 LIKE temp_ppp_003_template ");
          DB::select("
            INSERT IGNORE INTO $temp_ppp_003 (orders_id_fk, business_location_id_fk, branch_id_fk, branch_id_fk_tosent, invoice_code, bill_date, action_user,  customer_id_fk,  address_send_type, created_at)
            SELECT id,business_location_id_fk,branch_id_fk,sentto_branch_id,invoice_code,action_date,action_user , customers_id_fk ,'1', now() FROM db_orders where db_orders.id in ($orders_id_fk) ;
          ");

        // $Data = DB::select(" SELECT * FROM $temp_ppp_003; ");
        // return $Data;
// FIFO
        // Qry > product_id_fk from $temp_ppp_002
            $product = DB::select(" select product_id_fk from $temp_ppp_002 ");
            // return $product;

            $product_id_fk = [];
            foreach ($product as $key => $value) {
               array_push($product_id_fk,$value->product_id_fk);
               array_push($product_id_fk,0);
            }
            // return $product_id_fk;
            $arr_product_id_fk = array_unique($product_id_fk);
            $arr_product_id_fk = array_filter($arr_product_id_fk);
            $arr_product_id_fk = implode(',',$arr_product_id_fk);
            // return $arr_product_id_fk;

            if(in_array($temp_db_stocks,$array_TABLES)){
              // return "IN";
              DB::select(" TRUNCATE TABLE $temp_db_stocks  ");
            }else{
              // return "Not";
              DB::select(" CREATE TABLE $temp_db_stocks LIKE db_stocks ");
            }

          DB::select(" INSERT IGNORE INTO $temp_db_stocks SELECT * FROM db_stocks
          WHERE db_stocks.business_location_id_fk='$business_location_id_fk' AND db_stocks.branch_id_fk='$branch_id_fk' AND db_stocks.lot_expired_date>=now() AND db_stocks.warehouse_id_fk=(SELECT warehouse_id_fk FROM branchs WHERE id=db_stocks.branch_id_fk) AND db_stocks.product_id_fk in ($arr_product_id_fk) ORDER BY db_stocks.lot_number ASC, db_stocks.lot_expired_date ASC ");


          if(in_array($temp_db_stocks_compare,$array_TABLES)){
            // return "IN";
          }else{
            // return "Not";
            DB::select(" CREATE TABLE $temp_db_stocks_compare LIKE temp_db_stocks_compare_template ");
          }



          // $lastInsertId = DB::getPdo()->lastInsertId();

            return 1;
// Close > if($r01)
            // กรณีหาแล้วไม่เจอ
          }else{
            return 0;
          }


    }// Close > function

    public function ajaxSearch_requisition_db_orders002(Request $request)
    {

            $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;

            $r_compare_1 =  DB::select("
              SELECT amt_get+amt_lot+amt_remain as sum from $temp_db_stocks_compare ORDER BY id DESC LIMIT 1,1;
            ");

            $x = @$r_compare_1[0]->sum?@$r_compare_1[0]->sum:0;

            $r_compare_2 =  DB::select("
              SELECT amt_get+amt_lot+amt_remain as sum from $temp_db_stocks_compare ORDER BY id DESC LIMIT 0,1;
            ");
            $y = @$r_compare_2[0]->sum?@$r_compare_2[0]->sum:0;

            if($x == $y){
                  return "No_changed";
            }else{
                  return "changed";
            }


    }// Close > function



// /backend/pick_warehouse/1/edit (กรณีที่ยังไม่มีการยกเลิกการจ่าย status_cancel==0)
    public function Datatable0003(Request $req){

      $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
      $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
      // วุฒิเพิ่มมา
      $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list

      $TABLES = DB::select(" SHOW TABLES ");
      // return $TABLES;
      $array_TABLES = [];
      foreach($TABLES as $t){
        // print_r($t->Tables_in_aiyaraco_v3);
        array_push($array_TABLES, $t->Tables_in_aiyaraco_v3);
      }
      // เก็บรายการสินค้าที่จ่าย ตอน FIFO

      if(in_array($temp_ppp_004,$array_TABLES)){
        // return "IN";
        DB::select(" TRUNCATE TABLE $temp_ppp_004  ");
      }else{
        // return "Not";
        DB::select(" CREATE TABLE $temp_ppp_004 LIKE temp_ppp_004_template ");
      }


      $pick_pack_requisition_code_id_fk = $req->packing_id;


      $sTable = DB::select("
        SELECT * from db_pay_requisition_001 WHERE pick_pack_requisition_code_id_fk in ($pick_pack_requisition_code_id_fk) GROUP BY pick_pack_requisition_code_id_fk
        ");
        // temp_ppp_002
      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('column_001', function($row) {

            $pn = '<div class="divTable"><div class="divTableBody">';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="width:50px;font-weight:bold;">ครั้งที่</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;">วันที่จ่าย</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;"> พนักงาน </div>
            </div>
            ';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            </div>
            ';
            return $pn;
        })
       ->escapeColumns('column_001')
       ->addColumn('column_002', function($row) {

          $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // เก็บรายการที่หยิบสินค้ามาจากชั้นต่างๆ ตอน FIFO
          $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
          $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
          $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
          $max_time_pay = DB::table('db_pay_requisition_002')
          ->select('time_pay')
          ->where('pick_pack_requisition_code_id_fk',$row->pick_pack_requisition_code_id_fk)
          ->where('amt_remain','!=',0)
          ->where('status_cancel',0)
          ->orderby('time_pay','desc')
          ->first();

          $Products = DB::select("
            SELECT db_pay_requisition_002.* from db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."' and amt_remain <> 0 AND time_pay='".@$max_time_pay->time_pay."' and status_cancel=0  GROUP BY product_id_fk ORDER BY time_pay
          ");
          // status_sent
          $pn = '<div class="divTable"><div class="divTableBody">';
          $pn .=
          '<div class="divTableRow">
          <div class="divTableCell" style="width:240px;font-weight:bold;">ชื่อสินค้า</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จ่ายครั้งนี้</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">ค้างจ่าย</div>
          <div class="divTableCell" style="width:450px;text-align:center;font-weight:bold;">

                      <div class="divTableRow">
                      <div class="divTableCell" style="width:250px;text-align:center;font-weight:bold;">หยิบสินค้าจากคลัง</div>
                      <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">Lot number [Expired]</div>
                      <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
                      </div>

          </div>
          </div>
          ';

          $amt_pay_remain = 0;
          $amt_lot = 0;
          $amt_to_take = 0;
          $pay_this = 0;

         DB::select(" DROP TABLE IF EXISTS temp_001; ");
         // TEMPORARY
         DB::select(" CREATE TEMPORARY TABLE temp_001 LIKE temp_db_stocks_amt_template_02 ");

          foreach ($Products as $key => $value) {

               $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
               $amt_pay_this = $value->amt_remain;

                // จำนวนที่จะ Hint ให้ไปหยิบจากแต่ละชั้นมา ตามจำนวนที่สั่งซื้อ โดยการเช็คไปทีละชั้น fifo จนกว่าจะพอ
               // เอาจำนวนที่เบิก เป็นเช็ค กับ สต๊อก ว่ามีพอหรือไม่ โดยเอาทุกชั้นที่มีมาคิดรวมกันก่อนว่าพอหรือไม่
              // วุฒิเพิ่มมาเช็คคลัง ว่าอย่าเอาคลังเก็บมา
            $w_arr2 = DB::table('warehouse')->where('w_code','WH02')->pluck('id')->toArray();
            $w_str2 = '';
            foreach($w_arr2 as $key => $w){
              if($key+1==count($w_arr2)){
                $w_str2.=$w;
              }else{
                $w_str2.=$w.',';
              }

            }
            // time_pay
                // $temp_db_stocks_01 = DB::select(" SELECT sum(amt) as amt,count(*) as amt_floor from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk."  ");
                if(Auth::user()->branch_id_fk == 6){
                  $temp_db_stocks_01 = DB::select(" SELECT sum(amt) as amt,count(*) as amt_floor from db_stocks WHERE amt>0 AND branch_id_fk=".\Auth::user()->branch_id_fk." AND shelf_floor = 1 AND  warehouse_id_fk in (".$w_str2.") AND product_id_fk=".$value->product_id_fk."  ");
                }else{
                  $temp_db_stocks_01 = DB::select(" SELECT sum(amt) as amt,count(*) as amt_floor from db_stocks WHERE amt>0 AND branch_id_fk=".\Auth::user()->branch_id_fk." AND  warehouse_id_fk in (".$w_str2.") AND product_id_fk=".$value->product_id_fk."  ");
              }


                $amt_floor = $temp_db_stocks_01[0]->amt_floor;

                // Case 1 > มีสินค้าพอ (รวมจากทุกชั้น) และ ในคลังมีมากกว่า ที่ต้องการซื้อ
                if($temp_db_stocks_01[0]->amt>0 && $temp_db_stocks_01[0]->amt>=$amt_pay_this ){

                  $pay_this = $value->amt_remain ;

                  $amt_pay_remain = 0;

                    $pn .=
                    '<div class="divTableRow">
                     <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                    '.@$value->product_name.'</b><br>
                    </div>
                    <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">'. $pay_this .'</div>
                    <div class="divTableCell" style="width:80px;text-align:center;">'. $amt_pay_remain .'</div>
                    <div class="divTableCell" style="width:450px;text-align:center;"> ';

                    // Case 1.1 > ไล่หาแต่ละชั้น ตาม FIFO ชั้นที่จะหมดอายุก่อน เอาออกมาก่อน
                    //  $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC  ");
                    $temp_db_stocks_02 = DB::select(" SELECT * from db_stocks WHERE amt>0 AND branch_id_fk=".\Auth::user()->branch_id_fk." AND  warehouse_id_fk in (".$w_str2.") AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC ");

                      DB::select(" DROP TABLE IF EXISTS temp_001; ");
                      // TEMPORARY
                      DB::select(" CREATE TEMPORARY TABLE temp_001 LIKE temp_db_stocks_amt_template_02 ");


                     $i = 1;
                     foreach ($temp_db_stocks_02 as $v_02) {

                          $rs_ = DB::select(" SELECT amt_remain FROM temp_001 where id>0 order by id desc limit 1 ");
                          $amt_remain = @$rs_[0]->amt_remain?@$rs_[0]->amt_remain:0;
                          $pay_this = @$rs_[0]->amt_remain?@$rs_[0]->amt_remain:$value->amt_remain ;

                          $zone = DB::select(" select * from zone where id=".$v_02->zone_id_fk." ");
                          $shelf = DB::select(" select * from shelf where id=".$v_02->shelf_id_fk." ");
                          $sWarehouse = @$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$v_02->shelf_floor;

                          // Check ว่า ชั้นแรกๆ มีพอมั๊ย ถ้ามีพอแล้ว ก็หยุดค้น
                          // ถ้าจำนวนในคลังมีพอ เอาค่าจาก ที่ต้องการ
                            $amt_in_wh = @$v_02->amt?@$v_02->amt:0;
                            $amt_to_take = $pay_this;


                            if($amt_to_take>=$amt_in_wh){
                              DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_in_wh,$amt_to_take-$amt_in_wh) ");
                              $rs_ = DB::select(" SELECT amt_get FROM temp_001 order by id desc limit 1 ");
                              $amt_to_take = $rs_[0]->amt_get;
                            }else{
                              // $r_before =  DB::select(" select * from temp_001 where amt_remain<>0 order by id desc limit 1 ");
                              // $amt_remain = $r_before[0]->amt_remain;
                                     DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_to_take,0) ");
                                      $rs_ = DB::select(" SELECT amt_get FROM temp_001 order by id desc limit 1 ");
                                      $amt_to_take = $rs_[0]->amt_get;
                            }


                                $pn .=
                                '<div class="divTableRow">
                                <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;color:blue;"> '.$amt_to_take.' </div>
                                </div>
                                ';

                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                     $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                     $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id;

                                     $_choose=DB::table("$temp_ppp_004")
                                      ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      ->where('lot_number', $v_02->lot_number)
                                      ->where('lot_expired_date', $v_02->lot_expired_date)
                                      ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                      ->where('zone_id_fk', $v_02->zone_id_fk)
                                      ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                      ->where('shelf_floor', $v_02->shelf_floor)
                                      ->get();
                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppp_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              pick_pack_requisition_code_id_fk,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              lot_number,
                                              lot_expired_date,
                                              warehouse_id_fk,
                                              zone_id_fk,
                                              shelf_id_fk,
                                              shelf_floor,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$value->pick_pack_requisition_code_id_fk."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt_need."',
                                                '".$amt_to_take."',
                                                '".$amt_to_take."',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->lot_number."',
                                                '".$v_02->lot_expired_date."',
                                                '".$v_02->warehouse_id_fk."',
                                                '".$v_02->zone_id_fk."',
                                                '".$v_02->shelf_id_fk."',
                                                '".$v_02->shelf_floor."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                                     $r_temp_001 = DB::select(" SELECT amt_remain FROM temp_001 order by id desc limit 1 ; ");
                                     // return $r_temp_001[0]->amt_remain;
                                     if($r_temp_001[0]->amt_remain==0) break;

                          $i++;

                     }


                // Case 2 > กรณีมีบ้างเป็นบางรายการ (รวมจากทุกชั้น) และ ในคลังมี น้อยกว่า ที่ต้องการซื้อ
                }else if($temp_db_stocks_01[0]->amt>0 && $temp_db_stocks_01[0]->amt<$amt_pay_this){ // กรณีมีบ้างเป็นบางรายการ

                     $pay_this = $temp_db_stocks_01[0]->amt ;
                     $amt_pay_remain = $value->amt_remain - $temp_db_stocks_01[0]->amt ;
                     $css_red = $amt_pay_remain>0?'color:red;font-weight:bold;':'';

                    $pn .=
                    '<div class="divTableRow">
                     <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                    '.@$value->product_name.'</b><br>
                    </div>
                    <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">'. $pay_this .'</div>
                    <div class="divTableCell" style="width:80px;text-align:center;'.$css_red.'">'. $amt_pay_remain .'</div>
                    <div class="divTableCell" style="width:450px;text-align:center;"> ';

                     // Case 2.1 > ไล่หาแต่ละชั้น ตาม FIFO ชั้นที่จะหมดอายุก่อน เอาออกมาก่อน
                    //  $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC  ");

                    if(Auth::user()->branch_id_fk == 6){
                      // ให้ฝ่ายคลังดึงชั้น 1 เท่านั้น
                      $temp_db_stocks_02 = DB::select(" SELECT * from db_stocks WHERE amt>0 AND branch_id_fk=".\Auth::user()->branch_id_fk."  AND shelf_floor = 1 AND  warehouse_id_fk in (".$w_str2.") AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC ");

                    }else{
                    $temp_db_stocks_02 = DB::select(" SELECT * from db_stocks WHERE amt>0 AND branch_id_fk=".\Auth::user()->branch_id_fk." AND  warehouse_id_fk in (".$w_str2.") AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC ");
                  }

                     $i = 1;
                     foreach ($temp_db_stocks_02 as $v_02) {
                          $zone = DB::select(" select * from zone where id=".$v_02->zone_id_fk." ");
                          $shelf = DB::select(" select * from shelf where id=".$v_02->shelf_id_fk." ");
                          $sWarehouse = @$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$v_02->shelf_floor;

                          // Check ว่า ชั้นแรกๆ มีพอมั๊ย ถ้ามีพอแล้ว ก็หยุดค้น
                          // ถ้าจำนวนในคลังมีพอ เอาค่าจาก ที่ต้องการ
                          $amt_to_take = $pay_this;
                          $amt_in_wh = @$v_02->amt?@$v_02->amt:0;


                            // ในคลัง พิจารณารายชั้น ถ้าชั้นนั้นๆ น้อยกว่า ที่ต้องการ

                               if($i==1){
                                    DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_in_wh,".($amt_to_take-$amt_in_wh).") ");
                                    $rs_ = DB::select(" SELECT amt_get FROM temp_001 WHERE id='$i' ");
                                    $amt_to_take = $rs_[0]->amt_get;

                                   $pn .=
                                    '<div class="divTableRow">
                                    <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                    <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                    <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;color:blue;"> '.$amt_to_take.' </div>
                                    </div>
                                    ';

                                  // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                     $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                     $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id;

                                     $_choose=DB::table("$temp_ppp_004")
                                      ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      ->where('lot_number', $v_02->lot_number)
                                      ->where('lot_expired_date', $v_02->lot_expired_date)
                                      ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                      ->where('zone_id_fk', $v_02->zone_id_fk)
                                      ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                      ->where('shelf_floor', $v_02->shelf_floor)
                                      ->get();
                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppp_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              pick_pack_requisition_code_id_fk,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              lot_number,
                                              lot_expired_date,
                                              warehouse_id_fk,
                                              zone_id_fk,
                                              shelf_id_fk,
                                              shelf_floor,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$value->pick_pack_requisition_code_id_fk."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt_need."',
                                                '".$amt_to_take."',
                                                '".$amt_to_take."',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->lot_number."',
                                                '".$v_02->lot_expired_date."',
                                                '".$v_02->warehouse_id_fk."',
                                                '".$v_02->zone_id_fk."',
                                                '".$v_02->shelf_id_fk."',
                                                '".$v_02->shelf_floor."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }

                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒


                                }else{

                                    $r_before =  DB::select(" select * from temp_001 where amt_remain<>0 order by id desc limit 1 ");
                                    $amt_remain = @$r_before[0]->amt_remain?@$r_before[0]->amt_remain:0;

                                    DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_remain,".($r_before[0]->amt_remain-$amt_in_wh).") ");
                                    $rs_ = DB::select(" SELECT * FROM temp_001 WHERE id='$i' ");
                                    $amt_to_take = $amt_in_wh;

                                    $pn .=
                                    '<div class="divTableRow">
                                    <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                    <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                    <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;color:blue;"> '.$amt_to_take.' </div>
                                    </div>
                                    ';
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                                    $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                     $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id;

                                     $_choose=DB::table("$temp_ppp_004")
                                      ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      ->where('lot_number', $v_02->lot_number)
                                      ->where('lot_expired_date', $v_02->lot_expired_date)
                                      ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                      ->where('zone_id_fk', $v_02->zone_id_fk)
                                      ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                      ->where('shelf_floor', $v_02->shelf_floor)
                                      ->get();
                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppp_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              pick_pack_requisition_code_id_fk,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              lot_number,
                                              lot_expired_date,
                                              warehouse_id_fk,
                                              zone_id_fk,
                                              shelf_id_fk,
                                              shelf_floor,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$value->pick_pack_requisition_code_id_fk."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt_need."',
                                                '".$amt_to_take."',
                                                '".$amt_to_take."',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->lot_number."',
                                                '".$v_02->lot_expired_date."',
                                                '".$v_02->warehouse_id_fk."',
                                                '".$v_02->zone_id_fk."',
                                                '".$v_02->shelf_id_fk."',
                                                '".$v_02->shelf_floor."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }

                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒


                                }


                          $i++;

                     }



                }else{ // กรณีไม่มีสินค้าในคลังเลย

                     $amt_pay_remain = $value->amt_remain ;
                     $pay_this = 0 ;
                     $css_red = $amt_pay_remain>0?'color:red;font-weight:bold;':'';

                    $pn .=
                    '<div class="divTableRow">
                     <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                    '.@$value->product_name.'</b><br>

                    </div>
                    <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">'. $pay_this .'</div>
                    <div class="divTableCell" style="width:80px;text-align:center;'.$css_red.'">'. $amt_pay_remain .' </div>
                    <div class="divTableCell" style="width:450px;text-align:center;"> ';

                      $pn .=
                            '<div class="divTableRow">
                            <div class="divTableCell" style="width:200px;text-align:center;color:red;"> *** ไม่มีสินค้าในคลัง *** </div>
                            <div class="divTableCell" style="width:200px;text-align:center;">  </div>
                            <div class="divTableCell" style="width:80px;text-align:center;">  </div>
                            </div>
                            ';

                      @$_SESSION['check_product_instock'] = 0;

                      // $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt=0 and product_id_fk=".$value->product_id_fk." ");
                      if(Auth::user()->branch_id_fk == 6){
                        $temp_db_stocks_02 = DB::select(" SELECT * from db_stocks WHERE amt=0 AND branch_id_fk=".\Auth::user()->branch_id_fk." AND shelf_floor = 1 AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC ");
                      }else{
                        $temp_db_stocks_02 = DB::select(" SELECT * from db_stocks WHERE amt=0 AND branch_id_fk=".\Auth::user()->branch_id_fk."  AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC ");
                     }



                   // วุฒิเพิ่มมาสำหรับตรวจโปรโมชั่น
                  //  $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list
                  //  if(count($temp_db_stocks_02)==0){
                  //   $temp_db_stocks_02 = DB::table($temp_ppp_002)
                  //   ->select(
                  //     $temp_ppp_002.'.created_at',
                  //     'promotions_products.product_unit as product_unit_id_fk',
                  //     'promotions_products.product_id_fk as product_id_fk',
                  //     DB::raw('CONCAT(products.product_code," : ", products_details.product_name) AS product_name'),
                  //     'promotions_products.product_amt as amt',
                  //   )
                  //   ->join('promotions_products','promotions_products.promotion_id_fk',$temp_ppp_002.'.promotion_id_fk')
                  //   ->join('products_details','products_details.product_id_fk','promotions_products.product_id_fk')
                  //   ->join('products','products.id','promotions_products.product_id_fk')
                  //   ->where($temp_ppp_002.'.type_product','promotion')
                  //   ->where('promotions_products.product_id_fk',$value->product_id_fk)
                  //   ->groupBy('promotions_products.product_id_fk')
                  //   ->get();
                  // }
                      $i = 1;
                     foreach ($temp_db_stocks_02 as $v_02) {

                              // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                     $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                     $_choose=DB::table("$temp_ppp_004")
                                      ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      // ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('branch_id_fk', @\Auth::user()->branch_id_fk)
                                      ->get();
                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppp_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              pick_pack_requisition_code_id_fk,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$value->pick_pack_requisition_code_id_fk."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt_need."',
                                                '0',
                                                '0',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                        }

                }

            $pn .= '</div>';
            $pn .= '</div>';
          }

          $pn .= '</div>';

          $rs_temp_ppp_004 = DB::select(" select product_id_fk,lot_number,lot_expired_date,sum(amt_get) as amt_get, sum(amt_lot) as amt_lot, amt_remain  from $temp_ppp_004 group by pick_pack_requisition_code_id_fk,product_id_fk,lot_number  ");

          foreach ($rs_temp_ppp_004 as $key => $v) {

              $_choose=DB::table("$temp_db_stocks_check")
                ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                // ->where('product_id_fk', $v->product_id_fk)
                // ->where('lot_number', $v->lot_number)
                // ->where('lot_expired_date', $v->lot_expired_date)
                // ->where('amt_get', $v->amt_get)
                // ->where('amt_lot', $v->amt_lot)
                // ->where('amt_remain', $v->amt_remain)
                ->get();
                if($_choose->count() == 0){
                  DB::select(" INSERT IGNORE INTO $temp_db_stocks_check (pick_pack_requisition_code_id_fk,product_id_fk,lot_number,lot_expired_date,amt_get,amt_lot,amt_remain)
                    VALUES (
                    '".$row->pick_pack_requisition_code_id_fk."', ".$v->product_id_fk.", '".$v->lot_number."', '".$v->lot_expired_date."', ".$v->amt_lot.", '".$v->amt_lot."', '".$v->amt_remain."'
                    )
                  ");
                }

          }


          $rs_temp_db_stocks_check = DB::select(" select pick_pack_requisition_code_id_fk, sum(amt_get) as amt_get, sum(amt_lot) as amt_lot, sum(amt_remain) as amt_remain from $temp_db_stocks_check group by pick_pack_requisition_code_id_fk ");

          foreach ($rs_temp_db_stocks_check as $key => $v) {

              $_choose=DB::table("$temp_db_stocks_compare")
                // ->where('invoice_code', $row->invoice_code)
                // ->where('amt_get', $v->amt_get)
                // ->where('amt_lot', $v->amt_lot)
                // ->where('amt_remain', $v->amt_remain)
                ->get();
                // if($_choose->count() == 0){
                  DB::select(" INSERT IGNORE INTO $temp_db_stocks_compare (pick_pack_requisition_code_id_fk, amt_get, amt_lot, amt_remain)
                    VALUES (
                    '".$row->pick_pack_requisition_code_id_fk."', ".$v->amt_get.", '".$v->amt_lot."', '".$v->amt_remain."'
                    )
                  ");
                // }

          }

          return $pn;
      })
      ->escapeColumns('column_002')
       ->addColumn('column_003', function($row) {

                $pn = '<div class="divTable" ><div class="divTableBody" style="background-color:white !important;">';
                $pn .=
                '<div class="divTableRow" style="background-color:white !important;">
                <div class="divTableCell" style="text-align:center;font-weight:bold;">

                      <div class="divTableRow" style="background-color:white !important;">
                      <div class="divTableCell" style="text-align:center;font-weight:bold;color:white;">ยกเลิก</div>
                      </div>

              </div>
              </div>
              ';
            return $pn;

        })
        ->escapeColumns('column_003')
        ->addColumn('ch_amt_remain', function($row) {
        // ดูว่าไม่มีสินค้าค้างจ่ายแล้วใช่หรือไม่
        // Case ที่มีการบันทึกข้อมูลแล้ว
        // '3=สินค้าพอต่อการจ่ายครั้งนี้ 2=สินค้าไม่พอ มีบางรายการค้างจ่าย',
           $rs_pay_history = DB::select(" SELECT id FROM db_pay_requisition_002_pay_history WHERE pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."' AND status in (2) ");

           if(count($rs_pay_history)>0){
               return 2;
           }else{
               return 3;
           }

       })
      ->addColumn('ch_amt_lot_wh', function($row) {
// ดูว่าไม่มีสินค้าคลังเลย
          $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง

          $Products = DB::select("
            SELECT * from $temp_ppp_004 WHERE pick_pack_requisition_code_id_fk in (".$row->pick_pack_requisition_code_id_fk.") AND amt_remain>0 GROUP BY product_id_fk
          ");
          // Case ที่มีการบันทึกข้อมูลแล้ว
          // return count($Products);
          if(count($Products)>0){
              $arr = [];
              foreach ($Products as $key => $value) {
                array_push($arr,$value->product_id_fk);
              }
              $arr_im = implode(',',$arr);
              $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
              $r = DB::select(" SELECT sum(amt) as sum FROM $temp_db_stocks WHERE product_id_fk in ($arr_im) ");
              return @$r[0]->sum?@$r[0]->sum:0;
          }else{
              // Case ที่ยังไม่มีการบันทึกข้อมูล ก็ให้ส่งค่า >0 ไปก่อน
              return 1;
          }

       })
     ->addColumn('status_sent', function($row) {

          $status_sent = DB::select(" SELECT status_sent FROM db_pay_requisition_001 WHERE pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."'  ");
          if($status_sent){
              return $status_sent[0]->status_sent;
          }else{
              return 0;
          }

       })
      ->addColumn('status_cancel_all', function($row) {
            $P = DB::select(" select status_cancel from db_pay_requisition_002 where pick_pack_requisition_code_id_fk='".@$row->pick_pack_requisition_code_id_fk."' AND status_cancel=1 ");
            if(count($P)>0){
              return 1; // มีการยกเลิก
            }else{
              return 0; // ไม่มีการยกเลิก
            }
      })
      ->make(true);
    }





// /backend/pick_warehouse/1/edit (กรณีที่มีการยกเลิกการจ่าย status_cancel==1 บ้างบางรายการหรือทั้งหมด)
// %%%%%%%%%%%%%%%%%%%%%%%
    public function Datatable0004(Request $req){

      $temp_ppp_001 = "temp_ppp_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
      $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;


      $TABLES = DB::select(" SHOW TABLES ");
      // return $TABLES;
      $array_TABLES = [];
      foreach($TABLES as $t){
        // print_r($t->Tables_in_aiyaraco_v3);
        array_push($array_TABLES, $t->Tables_in_aiyaraco_v3);
      }
      // เก็บรายการสินค้าที่จ่าย ตอน FIFO

      if(in_array($temp_ppp_004,$array_TABLES)){
        // return "IN";
        DB::select(" TRUNCATE TABLE $temp_ppp_004  ");
      }else{
        // return "Not";
        DB::select(" CREATE TABLE $temp_ppp_004 LIKE temp_ppp_004_template ");
      }


      $pick_pack_requisition_code_id_fk = $req->packing_id;

      $sTable = DB::select("
        SELECT * from $temp_ppp_001 WHERE pick_pack_requisition_code_id_fk in ($pick_pack_requisition_code_id_fk) GROUP BY pick_pack_requisition_code_id_fk
        ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('column_001', function($row) {
            $pn = '<div class="divTable"><div class="divTableBody">';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="width:50px;font-weight:bold;">ครั้งที่</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;">วันที่จ่าย</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;"> พนักงาน </div>
            </div>
            ';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            </div>
            ';
            return $pn;
        })
       ->escapeColumns('column_001')
       ->addColumn('column_002', function($row) {

          $temp_ppp_001 = "temp_ppp_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
          $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list
          $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // เก็บรายการที่หยิบสินค้ามาจากชั้นต่างๆ ตอน FIFO
          $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
          $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
          $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;


           $ch_status_cancel = DB::select(" SELECT * FROM `db_pay_requisition_002` WHERE pick_pack_requisition_code_id_fk in (".$row->pick_pack_requisition_code_id_fk.") AND status_cancel in (0) ");
           if(count($ch_status_cancel)>0){

                 $Products = DB::select("
                  SELECT time_pay,customers_id_fk,`pick_pack_requisition_code_id_fk`, `product_id_fk`, `product_name`,`amt_remain` from db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk in (".$row->pick_pack_requisition_code_id_fk.") AND amt_remain > 0 AND time_pay=(SELECT time_pay FROM db_pay_requisition_002 WHERE `status_cancel`=0 ORDER BY time_pay DESC limit 1)  ;
                ");

           }else{

                $Products = DB::select("
                SELECT time_pay,customers_id_fk,`pick_pack_requisition_code_id_fk`, `product_id_fk`, `product_name`,`amt_need` as amt_remain from db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk in (".$row->pick_pack_requisition_code_id_fk.") AND time_pay=1;
              ");

           }


          $pn = '<div class="divTable"><div class="divTableBody">';
          $pn .=
          '<div class="divTableRow">
          <div class="divTableCell" style="width:240px;font-weight:bold;">ชื่อสินค้า</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จ่ายครั้งนี้</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">ค้างจ่าย</div>
          <div class="divTableCell" style="width:450px;text-align:center;font-weight:bold;">

                      <div class="divTableRow">
                      <div class="divTableCell" style="width:250px;text-align:center;font-weight:bold;">หยิบสินค้าจากคลัง</div>
                      <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">Lot number [Expired]</div>
                      <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
                      </div>

          </div>
          </div>
          ';

          $amt_pay_remain = 0;
          $amt_lot = 0;
          $amt_to_take = 0;
          $pay_this = 0;

          DB::select(" DROP TABLE IF EXISTS temp_001; ");
           // TEMPORARY
          DB::select(" CREATE TEMPORARY TABLE temp_001 LIKE temp_db_stocks_amt_template_02 ");

          if($Products){

                  foreach ($Products as $key => $value) {

                       $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
                       $amt_pay_this = $value->amt_remain;
                        // จำนวนที่จะ Hint ให้ไปหยิบจากแต่ละชั้นมา ตามจำนวนที่สั่งซื้อ โดยการเช็คไปทีละชั้น fifo จนกว่าจะพอ
                        // เอาจำนวนที่เบิก เป็นเช็ค กับ สต๊อก ว่ามีพอหรือไม่ โดยเอาทุกชั้นที่มีมาคิดรวมกันก่อนว่าพอหรือไม่
                        $temp_db_stocks_01 = DB::select(" SELECT sum(amt) as amt,count(*) as amt_floor from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk."  ");
                        $amt_floor = $temp_db_stocks_01[0]->amt_floor;

                        // return $amt_pay_this ;

                        // Case 1 > มีสินค้าพอ (รวมจากทุกชั้น) และ ในคลังมีมากกว่า ที่ต้องการซื้อ
                        if($temp_db_stocks_01[0]->amt>0 && $temp_db_stocks_01[0]->amt>=$amt_pay_this ){

                          $pay_this = $value->amt_remain ;
                          $amt_pay_remain = 0;

                            $pn .=
                            '<div class="divTableRow">
                             <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                            '.@$value->product_name.'</b><br>
                            </div>
                            <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">'. $pay_this .'</div>
                            <div class="divTableCell" style="width:80px;text-align:center;">'. $amt_pay_remain .'</div>
                            <div class="divTableCell" style="width:450px;text-align:center;"> ';

                            // Case 1.1 > ไล่หาแต่ละชั้น ตาม FIFO ชั้นที่จะหมดอายุก่อน เอาออกมาก่อน
                             $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC  ");

                              DB::select(" DROP TABLE IF EXISTS temp_001; ");
                              // TEMPORARY
                              DB::select(" CREATE TEMPORARY TABLE temp_001 LIKE temp_db_stocks_amt_template_02 ");


                             $i = 1;
                             foreach ($temp_db_stocks_02 as $v_02) {

                                  $rs_ = DB::select(" SELECT amt_remain FROM temp_001 where id>0 order by id desc limit 1 ");
                                  $amt_remain = @$rs_[0]->amt_remain?@$rs_[0]->amt_remain:0;
                                  $pay_this = @$rs_[0]->amt_remain?@$rs_[0]->amt_remain:$value->amt_remain ;

                                  $zone = DB::select(" select * from zone where id=".$v_02->zone_id_fk." ");
                                  $shelf = DB::select(" select * from shelf where id=".$v_02->shelf_id_fk." ");
                                  $sWarehouse = @$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$v_02->shelf_floor;

                                  // Check ว่า ชั้นแรกๆ มีพอมั๊ย ถ้ามีพอแล้ว ก็หยุดค้น
                                  // ถ้าจำนวนในคลังมีพอ เอาค่าจาก ที่ต้องการ
                                    $amt_in_wh = @$v_02->amt?@$v_02->amt:0;
                                    $amt_to_take = $pay_this;


                                    if($amt_to_take>=$amt_in_wh){
                                      DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_in_wh,$amt_to_take-$amt_in_wh) ");
                                      $rs_ = DB::select(" SELECT amt_get FROM temp_001 order by id desc limit 1 ");
                                      $amt_to_take = $rs_[0]->amt_get;
                                    }else{
                                      // $r_before =  DB::select(" select * from temp_001 where amt_remain<>0 order by id desc limit 1 ");
                                      // $amt_remain = $r_before[0]->amt_remain;
                                             DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_to_take,0) ");
                                              $rs_ = DB::select(" SELECT amt_get FROM temp_001 order by id desc limit 1 ");
                                              $amt_to_take = $rs_[0]->amt_get;
                                    }


                                        $pn .=
                                        '<div class="divTableRow">
                                        <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                        <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                        <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;color:blue;"> '.$amt_to_take.' </div>
                                        </div>
                                        ';

                                        // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                          $p_unit  = DB::select("  SELECT product_unit
                                          FROM
                                          dataset_product_unit
                                          WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                         $p_unit_name = @$p_unit[0]->product_unit;

                                         $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id;

                                         $_choose=DB::table("$temp_ppp_004")
                                          ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                                          ->where('product_id_fk', $v_02->product_id_fk)
                                          ->where('lot_number', $v_02->lot_number)
                                          ->where('lot_expired_date', $v_02->lot_expired_date)
                                          ->where('branch_id_fk', $v_02->branch_id_fk)
                                          ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                          ->where('zone_id_fk', $v_02->zone_id_fk)
                                          ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                          ->where('shelf_floor', $v_02->shelf_floor)
                                          ->get();
                                            if($_choose->count() == 0){
                                                  DB::select(" INSERT IGNORE INTO $temp_ppp_004 (
                                                  business_location_id_fk,
                                                  branch_id_fk,
                                                  pick_pack_requisition_code_id_fk,
                                                  product_id_fk,
                                                  product_name,
                                                  amt_need,
                                                  amt_get,
                                                  amt_lot,
                                                  amt_remain,
                                                  product_unit_id_fk,
                                                  product_unit,
                                                  lot_number,
                                                  lot_expired_date,
                                                  warehouse_id_fk,
                                                  zone_id_fk,
                                                  shelf_id_fk,
                                                  shelf_floor,
                                                  created_at
                                                  )
                                                  VALUES (
                                                    '".$v_02->business_location_id_fk."',
                                                    '".$v_02->branch_id_fk."',
                                                    '".$value->pick_pack_requisition_code_id_fk."',
                                                    '".$v_02->product_id_fk."',
                                                    '".$value->product_name."',
                                                    '".$value->amt_remain."',
                                                    '".$amt_to_take."',
                                                    '".$amt_to_take."',
                                                    '".$amt_pay_remain."',
                                                    '".$v_02->product_unit_id_fk."',
                                                    '".$p_unit_name."',
                                                    '".$v_02->lot_number."',
                                                    '".$v_02->lot_expired_date."',
                                                    '".$v_02->warehouse_id_fk."',
                                                    '".$v_02->zone_id_fk."',
                                                    '".$v_02->shelf_id_fk."',
                                                    '".$v_02->shelf_floor."',
                                                    '".$v_02->created_at."'
                                                  )
                                                ");
                                            }
                                        // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                                             $r_temp_001 = DB::select(" SELECT amt_remain FROM temp_001 order by id desc limit 1 ; ");
                                             // return $r_temp_001[0]->amt_remain;
                                             if($r_temp_001[0]->amt_remain==0) break;

                                  $i++;

                             }


                        // Case 2 > กรณีมีบ้างเป็นบางรายการ (รวมจากทุกชั้น) และ ในคลังมี น้อยกว่า ที่ต้องการซื้อ
                        }else if($temp_db_stocks_01[0]->amt>0 && $temp_db_stocks_01[0]->amt<$amt_pay_this){ // กรณีมีบ้างเป็นบางรายการ

                             $pay_this = $temp_db_stocks_01[0]->amt ;
                             $amt_pay_remain = $value->amt_remain - $temp_db_stocks_01[0]->amt ;
                             $css_red = $amt_pay_remain>0?'color:red;font-weight:bold;':'';

                            $pn .=
                            '<div class="divTableRow">
                             <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                            '.@$value->product_name.'</b><br>
                            </div>
                            <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">'. $pay_this .'</div>
                            <div class="divTableCell" style="width:80px;text-align:center;'.$css_red.'">'. $amt_pay_remain .'</div>
                            <div class="divTableCell" style="width:450px;text-align:center;"> ';

                             // Case 2.1 > ไล่หาแต่ละชั้น ตาม FIFO ชั้นที่จะหมดอายุก่อน เอาออกมาก่อน
                             $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC  ");

                             $i = 1;
                             foreach ($temp_db_stocks_02 as $v_02) {
                                  $zone = DB::select(" select * from zone where id=".$v_02->zone_id_fk." ");
                                  $shelf = DB::select(" select * from shelf where id=".$v_02->shelf_id_fk." ");
                                  $sWarehouse = @$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$v_02->shelf_floor;

                                  // Check ว่า ชั้นแรกๆ มีพอมั๊ย ถ้ามีพอแล้ว ก็หยุดค้น
                                  // ถ้าจำนวนในคลังมีพอ เอาค่าจาก ที่ต้องการ
                                  $amt_to_take = $amt_pay_this;
                                  $amt_in_wh = @$v_02->amt?@$v_02->amt:0;


                                    // ในคลัง พิจารณารายชั้น ถ้าชั้นนั้นๆ น้อยกว่า ที่ต้องการ

                                       if($i==1){
                                            DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_in_wh,".($amt_to_take-$amt_in_wh).") ");
                                            $rs_ = DB::select(" SELECT amt_get FROM temp_001 WHERE id='$i' ");
                                            $amt_to_take = $rs_[0]->amt_get;

                                           $pn .=
                                            '<div class="divTableRow">
                                            <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                            <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                            <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;color:blue;"> '.$pay_this.' </div>
                                            </div>
                                            ';

                                        // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                             $p_unit  = DB::select("  SELECT product_unit
                                                  FROM
                                                  dataset_product_unit
                                                  WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                                 $p_unit_name = @$p_unit[0]->product_unit;

                                                 $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id;

                                                 $_choose=DB::table("$temp_ppp_004")
                                                  ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                                                  ->where('product_id_fk', $v_02->product_id_fk)
                                                  ->where('lot_number', $v_02->lot_number)
                                                  ->where('lot_expired_date', $v_02->lot_expired_date)
                                                  ->where('branch_id_fk', $v_02->branch_id_fk)
                                                  ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                                  ->where('zone_id_fk', $v_02->zone_id_fk)
                                                  ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                                  ->where('shelf_floor', $v_02->shelf_floor)
                                                  ->get();
                                                    if($_choose->count() == 0){
                                                          DB::select(" INSERT IGNORE INTO $temp_ppp_004 (
                                                          business_location_id_fk,
                                                          branch_id_fk,
                                                          pick_pack_requisition_code_id_fk,
                                                          product_id_fk,
                                                          product_name,
                                                          amt_need,
                                                          amt_get,
                                                          amt_lot,
                                                          amt_remain,
                                                          product_unit_id_fk,
                                                          product_unit,
                                                          lot_number,
                                                          lot_expired_date,
                                                          warehouse_id_fk,
                                                          zone_id_fk,
                                                          shelf_id_fk,
                                                          shelf_floor,
                                                          created_at
                                                          )
                                                          VALUES (
                                                            '".$v_02->business_location_id_fk."',
                                                            '".$v_02->branch_id_fk."',
                                                            '".$value->pick_pack_requisition_code_id_fk."',
                                                            '".$v_02->product_id_fk."',
                                                            '".$value->product_name."',
                                                            '".$value->amt_remain."',
                                                            '".$pay_this."',
                                                            '".$pay_this."',
                                                            '".$amt_pay_remain."',
                                                            '".$v_02->product_unit_id_fk."',
                                                            '".$p_unit_name."',
                                                            '".$v_02->lot_number."',
                                                            '".$v_02->lot_expired_date."',
                                                            '".$v_02->warehouse_id_fk."',
                                                            '".$v_02->zone_id_fk."',
                                                            '".$v_02->shelf_id_fk."',
                                                            '".$v_02->shelf_floor."',
                                                            '".$v_02->created_at."'
                                                          )
                                                        ");
                                                    }
                                        // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒


                                        }else{

                                            $r_before =  DB::select(" select * from temp_001 where amt_remain<>0 order by id desc limit 1 ");
                                            $amt_remain = @$r_before[0]->amt_remain?@$r_before[0]->amt_remain:0;

                                            DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_remain,".($r_before[0]->amt_remain-$amt_in_wh).") ");
                                            $rs_ = DB::select(" SELECT * FROM temp_001 WHERE id='$i' ");
                                            $amt_to_take = $amt_in_wh;

                                            $pn .=
                                            '<div class="divTableRow">
                                            <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                            <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                            <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;color:blue;"> '.$amt_to_take.' </div>
                                            </div>
                                            ';
                                         // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                           $p_unit  = DB::select("  SELECT product_unit
                                              FROM
                                              dataset_product_unit
                                              WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                             $p_unit_name = @$p_unit[0]->product_unit;

                                             $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id;

                                             $_choose=DB::table("$temp_ppp_004")
                                              ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                                              ->where('product_id_fk', $v_02->product_id_fk)
                                              ->where('lot_number', $v_02->lot_number)
                                              ->where('lot_expired_date', $v_02->lot_expired_date)
                                              ->where('branch_id_fk', $v_02->branch_id_fk)
                                              ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                              ->where('zone_id_fk', $v_02->zone_id_fk)
                                              ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                              ->where('shelf_floor', $v_02->shelf_floor)
                                              ->get();
                                                if($_choose->count() == 0){
                                                      DB::select(" INSERT IGNORE INTO $temp_ppp_004 (
                                                      business_location_id_fk,
                                                      branch_id_fk,
                                                      pick_pack_requisition_code_id_fk,
                                                      product_id_fk,
                                                      product_name,
                                                      amt_need,
                                                      amt_get,
                                                      amt_lot,
                                                      amt_remain,
                                                      product_unit_id_fk,
                                                      product_unit,
                                                      lot_number,
                                                      lot_expired_date,
                                                      warehouse_id_fk,
                                                      zone_id_fk,
                                                      shelf_id_fk,
                                                      shelf_floor,
                                                      created_at
                                                      )
                                                      VALUES (
                                                        '".$v_02->business_location_id_fk."',
                                                        '".$v_02->branch_id_fk."',
                                                        '".$value->pick_pack_requisition_code_id_fk."',
                                                        '".$v_02->product_id_fk."',
                                                        '".$value->product_name."',
                                                        '".$value->amt_remain."',
                                                        '".$amt_to_take."',
                                                        '".$amt_to_take."',
                                                        '".$amt_pay_remain."',
                                                        '".$v_02->product_unit_id_fk."',
                                                        '".$p_unit_name."',
                                                        '".$v_02->lot_number."',
                                                        '".$v_02->lot_expired_date."',
                                                        '".$v_02->warehouse_id_fk."',
                                                        '".$v_02->zone_id_fk."',
                                                        '".$v_02->shelf_id_fk."',
                                                        '".$v_02->shelf_floor."',
                                                        '".$v_02->created_at."'
                                                      )
                                                    ");
                                                }

                                        // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒


                                        }


                                  $i++;

                             }



                        }else{ // กรณีไม่มีสินค้าในคลังเลย


                             $amt_pay_remain = $value->amt_remain ;
                             $pay_this = 0 ;
                             $css_red = $amt_pay_remain>0?'color:red;font-weight:bold;':'';

                            $pn .=
                            '<div class="divTableRow">
                             <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                              '.@$value->product_name.'</b><br>
                              </div>
                            <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">'. $pay_this .'</div>
                            <div class="divTableCell" style="width:80px;text-align:center;'.$css_red.'">'. $amt_pay_remain .'</div>
                            <div class="divTableCell" style="width:450px;text-align:center;"> ';

                              $pn .=
                                    '<div class="divTableRow">
                                    <div class="divTableCell" style="width:200px;text-align:center;color:red;"> *** ไม่มีสินค้าในคลัง *** </div>
                                    <div class="divTableCell" style="width:200px;text-align:center;">  </div>
                                    <div class="divTableCell" style="width:80px;text-align:center;">  </div>
                                    </div>
                                    ';

                             @$_SESSION['check_product_instock'] = 0;


                              $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt=0 and product_id_fk=".$value->product_id_fk." ");

                               // วุฒิเพิ่มมาสำหรับตรวจโปรโมชั่น
                               if(count($temp_db_stocks_02)==0){
                                $temp_db_stocks_02 = DB::table($temp_ppp_002)
                                ->select(
                                  $temp_ppp_002.'.created_at',
                                  'promotions_products.product_unit as product_unit_id_fk',
                                  'promotions_products.product_id_fk as product_id_fk',
                                  DB::raw('CONCAT(products.product_code," : ", products_details.product_name) AS product_name'),
                                  'promotions_products.product_amt as amt',
                                )
                                ->join('promotions_products','promotions_products.promotion_id_fk',$temp_ppp_002.'.promotion_id_fk')
                                ->join('products_details','products_details.product_id_fk','promotions_products.product_id_fk')
                                ->join('products','products.id','promotions_products.product_id_fk')
                                ->where($temp_ppp_002.'.type_product','promotion')
                                ->where('promotions_products.product_id_fk',$value->product_id_fk)
                                ->groupBy('promotions_products.product_id_fk')
                                ->get();
                              }


                             $i = 1;
                             foreach ($temp_db_stocks_02 as $v_02) {

                                     // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                          $p_unit  = DB::select("  SELECT product_unit
                                            FROM
                                            dataset_product_unit
                                            WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                           $p_unit_name = @$p_unit[0]->product_unit;

                                           $_choose=DB::table("$temp_ppp_004")
                                            ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                                            ->where('product_id_fk', $v_02->product_id_fk)
                                            // ->where('branch_id_fk', $v_02->branch_id_fk)
                                            ->where('branch_id_fk', @\Auth::user()->branch_id_fk)
                                            ->get();
                                              if($_choose->count() == 0){
                                                    DB::select(" INSERT IGNORE INTO $temp_ppp_004 (
                                                    business_location_id_fk,
                                                    branch_id_fk,
                                                    pick_pack_requisition_code_id_fk,
                                                    product_id_fk,
                                                    product_name,
                                                    amt_need,
                                                    amt_get,
                                                    amt_lot,
                                                    amt_remain,
                                                    product_unit_id_fk,
                                                    product_unit,
                                                    created_at
                                                    )
                                                    VALUES (
                                                      '".$v_02->business_location_id_fk."',
                                                      '".$v_02->branch_id_fk."',
                                                      '".$value->pick_pack_requisition_code_id_fk."',
                                                      '".$v_02->product_id_fk."',
                                                      '".$value->product_name."',
                                                      '".$value->amt_remain."',
                                                      '0',
                                                      '0',
                                                      '".$amt_pay_remain."',
                                                      '".$v_02->product_unit_id_fk."',
                                                      '".$p_unit_name."',
                                                      '".$v_02->created_at."'
                                                    )
                                                  ");
                                              }
                                        // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                                }

                        }

                    $pn .= '</div>';
                    $pn .= '</div>';
                  }

                  $pn .= '</div>';

          }

            $rs_temp_ppp_004 = DB::select(" select product_id_fk,lot_number,lot_expired_date,sum(amt_get) as amt_get, sum(amt_lot) as amt_lot, amt_remain  from $temp_ppp_004 group by pick_pack_requisition_code_id_fk,product_id_fk  ");

            foreach ($rs_temp_ppp_004 as $key => $v) {

                $_choose=DB::table("$temp_db_stocks_check")
                  ->where('pick_pack_requisition_code_id_fk', $row->pick_pack_requisition_code_id_fk)
                  // ->where('product_id_fk', $v->product_id_fk)
                  // ->where('lot_number', $v->lot_number)
                  // ->where('lot_expired_date', $v->lot_expired_date)
                  // ->where('amt_get', $v->amt_get)
                  // ->where('amt_lot', $v->amt_lot)
                  // ->where('amt_remain', $v->amt_remain)
                  ->get();
                  if($_choose->count() == 0){
                    DB::select(" INSERT IGNORE INTO $temp_db_stocks_check (pick_pack_requisition_code_id_fk,product_id_fk,lot_number,lot_expired_date,amt_get,amt_lot,amt_remain)
                      VALUES (
                      '".$row->pick_pack_requisition_code_id_fk."', ".$v->product_id_fk.", '".$v->lot_number."', '".$v->lot_expired_date."', ".$v->amt_lot.", '".$v->amt_lot."', '".$v->amt_remain."'
                      )
                    ");
                  }

            }


          $rs_temp_db_stocks_check = DB::select(" select pick_pack_requisition_code_id_fk, sum(amt_get) as amt_get, sum(amt_lot) as amt_lot, sum(amt_remain) as amt_remain from $temp_db_stocks_check group by pick_pack_requisition_code_id_fk ");

          foreach ($rs_temp_db_stocks_check as $key => $v) {

              $_choose=DB::table("$temp_db_stocks_compare")
                ->get();
                  DB::select(" INSERT IGNORE INTO $temp_db_stocks_compare (`pick_pack_requisition_code_id_fk`, `amt_get`, `amt_lot`, `amt_remain`)
                    VALUES (
                    '".$row->pick_pack_requisition_code_id_fk."', ".$v->amt_get.", '".$v->amt_lot."', '".$v->amt_remain."'
                    )
                  ");
          }

          return $pn;
      })
      ->escapeColumns('column_002')
       ->addColumn('column_003', function($row) {

                $pn = '<div class="divTable" ><div class="divTableBody" style="background-color:white !important;">';
                $pn .=
                '<div class="divTableRow" style="background-color:white !important;">
                <div class="divTableCell" style="text-align:center;font-weight:bold;">

                      <div class="divTableRow" style="background-color:white !important;">
                      <div class="divTableCell" style="text-align:center;font-weight:bold;color:white;">ยกเลิก</div>
                      </div>

              </div>
              </div>
              ';
            return $pn;

        })
         ->escapeColumns('column_003')
         ->addColumn('ch_amt_remain', function($row) {
            // ดูว่าไม่มีสินค้าค้างจ่ายแล้วใช่หรือไม่
            // Case ที่มีการบันทึกข้อมูลแล้ว
            // '3=สินค้าพอต่อการจ่ายครั้งนี้ 2=สินค้าไม่พอ มีบางรายการค้างจ่าย',
             $rs_pay_history = DB::select(" SELECT id FROM `db_pay_requisition_002_pay_history` WHERE pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."' AND status in (2) ");

             if(count($rs_pay_history)>0){
                 return 2;
             }else{
                 return 3;
             }


           })
          ->addColumn('ch_amt_lot_wh', function($row) {
         // ดูว่าไม่มีสินค้าคลังเลย

            $Products = DB::select("
              SELECT * from db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."' AND amt_remain > 0 GROUP BY product_id_fk ORDER BY time_pay DESC limit 1 ;
            ");
            // Case ที่มีการบันทึกข้อมูลแล้ว
            if(count($Products)>0){
                $arr = [];
                foreach ($Products as $key => $value) {
                  array_push($arr,$value->product_id_fk);
                }
                $arr_im = implode(',',$arr);
                $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
                $r = DB::select(" SELECT sum(amt) as sum FROM $temp_db_stocks WHERE product_id_fk in ($arr_im) ");
                return @$r[0]->sum?@$r[0]->sum:0;
            }else{
                // Case ที่ยังไม่มีการบันทึกข้อมูล ก็ให้ส่งค่า >0 ไปก่อน
                return 1;
            }

       })
      ->addColumn('status_cancel_some', function($row) {
            $P = DB::select(" select status_cancel from db_pay_requisition_002 where pick_pack_requisition_code_id_fk='".@$row->pick_pack_requisition_code_id_fk."' AND status_cancel=1 ");
            if(count($P)>0){
              return 1;
            }else{
              return 0;
            }
      })
       ->addColumn('status_sent', function($row) {

            $status_sent = DB::select(" SELECT status_sent FROM `db_pay_requisition_001` WHERE pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."'  ");
            if($status_sent){
                return $status_sent[0]->status_sent;
            }else{
                return 0;
            }

         })
      ->addColumn('check_product_instock', function($row) {
      // ดูว่าไม่มีสินค้าในคลังบางรายการ
          if(@$_SESSION['check_product_instock']=="0"){
            return "N";
          }else{
            return "Y";
          }

       })
        ->make(true);
      }



}// Close > class
