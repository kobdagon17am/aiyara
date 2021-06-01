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
      // return $request->picking_id;
      // dd();
      // $row_id = explode(',', $request->picking_id);
      if(isset($request->picking_id)){

        // return $request->picking_id;
        // dd();


          $r_db_pick_pack_packing_code = DB::select(" SELECT * FROM db_pick_pack_packing_code WHERE id=".$request->picking_id." ");
          // return $r_db_pick_pack_packing_code[0]->receipt ;
          $receipt = explode(",",$r_db_pick_pack_packing_code[0]->receipt);

          $arr_01 = [];
          foreach ($receipt as $key => $value) {
            array_push($arr_01,'"'.$value.'"');
          }

          $receipt = implode(",",$arr_01);

          $r_db_orders = DB::select(" SELECT * FROM db_orders WHERE invoice_code in ($receipt) ");
          // return $r_db_orders;
          // dd();

          $arr_02 = [];
          foreach ($r_db_orders as $key => $value) {
            array_push($arr_02,$value->id);
          }
          $id = implode(",",$arr_02);
          // return $id;
          // dd();
          $r_db_order_products_list = DB::select(" SELECT * FROM db_order_products_list WHERE frontstore_id_fk in ($id) ");
          // return $r_db_order_products_list;
          // dd();
    //  ดึง product_id_fk ออกมารวมกัน 
          $arr_03 = [];
          foreach ($r_db_order_products_list as $key => $value) {
            array_push($arr_03,$value->product_id_fk);
          }
          $arr_product_id_fk = implode(",",$arr_03);
          // return $arr_product_id_fk;
          // dd();

          $business_location_id_fk = \Auth::user()->business_location_id_fk;
          $branch_id_fk = \Auth::user()->branch_id_fk;
          $temp_ppp_001 = "temp_ppp_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
          $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list
          $temp_ppp_003 = "temp_ppp_003".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง 
          $temp_db_stocks_compare002 = "temp_db_stocks_compare002".\Auth::user()->id; 
          $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // เก็บรายการที่หยิบสินค้ามาจากชั้นต่างๆ ตอน FIFO 
          $temp_db_stocks = "temp_db_stocks".\Auth::user()->id; 
          $temp_db_stocks_check002 = "temp_db_stocks_check002".\Auth::user()->id; 

      
          $temp_db_stocks = "temp_db_stocks".\Auth::user()->id; 

          $TABLES = DB::select(" SHOW TABLES ");
          // return $TABLES;
          $array_TABLES = [];
          foreach($TABLES as $t){
            // print_r($t->Tables_in_aiyara_db);
            array_push($array_TABLES, $t->Tables_in_aiyara_db);
          }

          if(in_array($temp_db_stocks,$array_TABLES)){
            // return "IN";
            DB::select(" TRUNCATE TABLE $temp_db_stocks  ");
          }else{
            // return "Not";
            DB::select(" CREATE TABLE $temp_db_stocks LIKE db_stocks ");
          }

          DB::select(" INSERT IGNORE INTO $temp_db_stocks SELECT * FROM db_stocks 
          WHERE db_stocks.business_location_id_fk='$business_location_id_fk' AND db_stocks.branch_id_fk='$branch_id_fk' AND db_stocks.lot_expired_date>=now() AND db_stocks.warehouse_id_fk=(SELECT warehouse_id_fk FROM branchs WHERE id=db_stocks.branch_id_fk) AND db_stocks.product_id_fk in ($arr_product_id_fk) ORDER BY db_stocks.lot_number ASC, db_stocks.lot_expired_date ASC ");


      $TABLES = DB::select(" SHOW TABLES ");
      // return $TABLES;
      $array_TABLES = [];
      foreach($TABLES as $t){
        // print_r($t->Tables_in_aiyara_db);
        array_push($array_TABLES, $t->Tables_in_aiyara_db);
      }

      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check002 ");
      DB::select(" CREATE TABLE $temp_db_stocks_check002 LIKE temp_db_stocks_check002_template ");

      // dd();

    // return $request;
    // หาในตาราง db_orders ว่ามีมั๊ย
    // คิวรีจาก db_orders ที่ branch_id_fk = sentto_branch_id & delivery_location = 0 
    // กรณีที่ เป็น invoice_code (เพราะมี 2 กรณี คือ invoice_code กับ QR_CODE)
    $invoice_code = $request->txtSearch;
    $r01 = DB::select(" SELECT invoice_code FROM db_orders where invoice_code in ($receipt) AND branch_id_fk <> sentto_branch_id ");

    // return $r01[0]->invoice_code;

    if($r01){

        DB::select(" DROP TABLE IF EXISTS $temp_ppp_001; ");
        DB::select(" CREATE TABLE $temp_ppp_001 LIKE db_orders ");
        DB::select(" INSERT $temp_ppp_001 SELECT * FROM db_orders WHERE invoice_code in ($receipt) ");

        DB::select(" ALTER TABLE $temp_ppp_001
ADD COLUMN `pick_pack_packing_code_id_fk`  int NULL DEFAULT 0 COMMENT 'Ref>db_pick_pack_packing_code>id' AFTER `id` ");

        DB::select(" UPDATE $temp_ppp_001 SET pick_pack_packing_code_id_fk=".$request->picking_id." WHERE invoice_code in ($receipt)  ");


   // Qry > product_id_fk from $temp_ppp_002
        $product = DB::select(" select product_id_fk from $temp_ppp_002 ");
        // return $product;

        $product_id_fk = [];
        foreach ($product as $key => $value) {
           array_push($product_id_fk,$value->product_id_fk);
        }
        // return $product_id_fk;
        $arr_product_id_fk = implode(',',$product_id_fk);
        // return $product_id_fk;

        DB::select(" DROP TABLE IF EXISTS $temp_ppp_002; ");
        DB::select(" CREATE TABLE $temp_ppp_002 LIKE db_order_products_list ");
        DB::select(" INSERT $temp_ppp_002 
        SELECT db_order_products_list.* FROM db_order_products_list INNER Join $temp_ppp_001 ON db_order_products_list.frontstore_id_fk = $temp_ppp_001.id ");

          DB::select(" ALTER TABLE $temp_ppp_002
ADD COLUMN `pick_pack_packing_code_id_fk`  int(11) NULL DEFAULT 0 COMMENT 'Ref>db_pick_pack_packing_code>id' AFTER `id` ");

          DB::select(" UPDATE $temp_ppp_002 SET pick_pack_packing_code_id_fk=".$request->picking_id." WHERE product_id_fk in ($arr_product_id_fk) ");

        $temp_ppp_0022 = "temp_ppp_0022".\Auth::user()->id; 
        DB::select(" DROP TABLE IF EXISTS $temp_ppp_0022; ");
        DB::select(" CREATE TABLE $temp_ppp_0022 LIKE temp_ppp_002_template ");
        DB::select(" INSERT IGNORE INTO $temp_ppp_0022 (`pick_pack_packing_code_id_fk`, `product_id_fk`, `product_name`, `amt`, `product_unit_id_fk`) 
         SELECT `pick_pack_packing_code_id_fk`, `product_id_fk`, `product_name`, sum(`amt`), `product_unit_id_fk` FROM $temp_ppp_002 WHERE product_id_fk in ($arr_product_id_fk) GROUP BY product_id_fk");


             // ต้องมีอีกตารางนึง เก็บ สถานะการอนุมัติ และ ที่อยู่การจัดส่งสินค้า > $temp_ppp_003
                DB::select(" DROP TABLE IF EXISTS $temp_ppp_003; ");
                DB::select(" CREATE TABLE $temp_ppp_003 LIKE temp_ppp_003_template ");
                DB::select(" 
                  INSERT IGNORE INTO $temp_ppp_003 (orders_id_fk, business_location_id_fk, branch_id_fk, branch_id_fk_tosent, invoice_code, bill_date, action_user,  customer_id_fk,  address_send_type, created_at) 
                  SELECT id,business_location_id_fk,branch_id_fk,sentto_branch_id,invoice_code,action_date,action_user , customers_id_fk ,'1', now() FROM db_orders where invoice_code in ($receipt) ;
                ");

              // $Data = DB::select(" SELECT * FROM $temp_ppp_003; ");
              // return $Data;
      // FIFO 

                  if(in_array($temp_db_stocks,$array_TABLES)){
                    // return "IN";
                    DB::select(" TRUNCATE TABLE $temp_db_stocks  ");
                  }else{
                    // return "Not";
                    DB::select(" CREATE TABLE $temp_db_stocks LIKE db_stocks ");
                  }

                DB::select(" INSERT IGNORE INTO $temp_db_stocks SELECT * FROM db_stocks 
                WHERE db_stocks.business_location_id_fk='$business_location_id_fk' AND db_stocks.branch_id_fk='$branch_id_fk' AND db_stocks.lot_expired_date>=now() AND db_stocks.warehouse_id_fk=(SELECT warehouse_id_fk FROM branchs WHERE id=db_stocks.branch_id_fk) AND db_stocks.product_id_fk in ($arr_product_id_fk) ORDER BY db_stocks.lot_number ASC, db_stocks.lot_expired_date ASC ");

       
                if(in_array($temp_db_stocks_compare002,$array_TABLES)){
                  // return "IN";
                }else{
                  // return "Not";
                  DB::select(" CREATE TABLE $temp_db_stocks_compare002 LIKE temp_db_stocks_compare002_template ");
                }



          // $lastInsertId = DB::getPdo()->lastInsertId();

            return 1;
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

          DB::select(" UPDATE db_pay_requisition_002 SET status_cancel=1 WHERE pick_pack_packing_code_id_fk='".$request->pick_pack_packing_code_id_fk."' AND time_pay='".$request->time_pay."' ");
          // เอาสินค้าคืนคลัง
          // return "OK";

          $r = DB::select(" 
            SELECT 
            time_pay,business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt_get, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk, shelf_floor,pick_pack_packing_code_id_fk, created_at,now()
             FROM db_pay_requisition_002 WHERE pick_pack_packing_code_id_fk='".$request->pick_pack_packing_code_id_fk."' AND time_pay='".$request->time_pay."' ; ");

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
                      ->where('pick_pack_packing_code_id_fk', $request->pick_pack_packing_code_id_fk)
                      ->get();
                      if($_choose->count() == 0){

                             DB::select(" INSERT INTO db_stocks_return (time_pay,business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk,shelf_floor, pick_pack_packing_code_id_fk, created_at, updated_at,status_cancel) 
                            SELECT 
                            time_pay,business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt_get, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk, shelf_floor,pick_pack_packing_code_id_fk, created_at,now(),1
                             FROM db_pay_requisition_002 WHERE pick_pack_packing_code_id_fk='".$request->pick_pack_packing_code_id_fk."' AND time_pay='".$v->time_pay."' ; ");

                       }

                         DB::select(" UPDATE db_pay_requisition_001 SET status_sent=2 WHERE pick_pack_packing_code_id_fk='".$request->pick_pack_packing_code_id_fk."' ; ");

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
            SELECT * FROM db_pay_requisition_002 WHERE pick_pack_packing_code_id_fk='".$request->pick_pack_packing_code_id_fk."' AND time_pay='".$request->time_pay."' AND status_cancel=1 ; ");

             foreach ($r2 as $key => $v) {
                   $_choose=DB::table("db_pay_requisition_002_cancel_log")
                      ->where('time_pay', $v->time_pay)
                      ->where('business_location_id_fk', $v->business_location_id_fk)
                      ->where('branch_id_fk', $v->branch_id_fk)
                      ->where('customers_id_fk', $v->customers_id_fk)
                      ->where('pick_pack_packing_code_id_fk', $v->pick_pack_packing_code_id_fk)
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

                              DB::select(" INSERT IGNORE INTO `db_pay_requisition_002_cancel_log` (`time_pay`, `business_location_id_fk`, `branch_id_fk`, `customers_id_fk`, `pick_pack_packing_code_id_fk`, `product_id_fk`, `product_name`, `amt_need`, `amt_get`, `amt_lot`, `amt_remain`, `product_unit_id_fk`, `product_unit`, `lot_number`, `lot_expired_date`, `warehouse_id_fk`, `zone_id_fk`, `shelf_id_fk`, `shelf_floor`, `status_cancel`, `created_at`)  
                                VALUES
                               (".$v->time_pay.", ".$v->business_location_id_fk.", ".$v->branch_id_fk.", ".$v->customers_id_fk.", '".$v->pick_pack_packing_code_id_fk."', ".$v->product_id_fk.", '".$v->product_name."', ".$v->amt_get.", 0 , 0, ".$v->amt_get.", ".$v->product_unit_id_fk.", '".$v->product_unit."', '".$v->lot_number."', '".$v->lot_expired_date."', ".$v->warehouse_id_fk.", ".$v->zone_id_fk.", ".$v->shelf_id_fk.", ".$v->shelf_floor.", ".$v->status_cancel.", '".$v->created_at."') ");

                       }


               }


                 $ch_status_cancel = DB::select(" SELECT * FROM `db_pay_requisition_002` WHERE pick_pack_packing_code_id_fk='".$request->pick_pack_packing_code_id_fk."' AND status_cancel in (0) ");
                 if(count(@$ch_status_cancel)==0 || empty(@$ch_status_cancel)){
                    DB::select(" UPDATE `db_pay_requisition_001` SET status_sent=1 WHERE pick_pack_packing_code_id_fk='".$request->pick_pack_packing_code_id_fk."' ");
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


  public function Datatable0001(Request $reg){

      $sTable = \App\Models\Backend\Pick_packPackingCode::where('id',$reg->picking_id);
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
          return "<b>"."P2".sprintf("%05d",$row->id)."</b>";
      })
      ->addColumn('column_002', function($row) {

          $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list
          $Products = DB::select("
            SELECT $temp_ppp_002.*,dataset_product_unit.product_unit,db_orders.invoice_code  from $temp_ppp_002 
            LEFT Join dataset_product_unit ON $temp_ppp_002.product_unit_id_fk = dataset_product_unit.id 
            LEFT Join db_orders ON $temp_ppp_002.frontstore_id_fk = db_orders.id 
            order by db_orders.id 

            ");

            $pn = '<div class="divTable"><div class="divTableBody">';
            $pn .=     
            '<div class="divTableRow">
            <div class="divTableCell" style="width:250px;font-weight:bold;">ชื่อสินค้า (เลขที่ใบเสร็จ)</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;">จำนวนที่สั่งซื้อ</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;"> หน่วยนับ </div>
            <div class="divTableCell" style="width:300px;text-align:center;font-weight:bold;"> Scan Qr-code </div>
            </div>
            ';

            $sum_amt = 0 ;
              
              foreach ($Products as $key => $value) {
              $sum_amt += $value->amt;
              $pn .=     
              '<div class="divTableRow">
              <div class="divTableCell" style="font-weight:bold;padding-bottom:15px;">
              '.$value->product_name.'<br>('.$value->invoice_code.')
              </div>
              <div class="divTableCell" style="text-align:center;">'.$value->amt.'</div> 
              <div class="divTableCell" style="text-align:center;">'.$value->product_unit.'</div> 
              <div class="divTableCell" style="text-align:left;"> ';

              $item_id = 1;
              $amt_scan = $value->amt;

               for ($i=0; $i < $amt_scan ; $i++) { 

                $qr = DB::select(" select qr_code,updated_at from db_pick_warehouse_qrcode where item_id='".$item_id."' and invoice_code='".$value->invoice_code."' AND product_id_fk='".$value->product_id_fk."' ");
                
                            if( (@$qr[0]->updated_at < date("Y-m-d") && !empty(@$qr[0]->qr_code)) ){

                              $pn .= 
                               '
                                <input type="text" style="width:122px;" value="'.@$qr[0]->qr_code.'" readonly > 
                                <i class="fa fa-times-circle fa-2 " aria-hidden="true" style="color:grey;" ></i> 
                               ';

                            }else{

                               $pn .= 
                               '
                                <input type="text" class="in-tx qr_scan " data-item_id="'.$item_id.'" data-invoice_code="'.$value->invoice_code.'" data-product_id_fk="'.$value->product_id_fk.'" placeholder="scan qr" style="width:122px;'.(empty(@$qr[0]->qr_code)?"background-color:blanchedalmond;":"").'" value="'.@$qr[0]->qr_code.'" > 
                                <i class="fa fa-times-circle fa-2 btnDeleteQrcodeProduct " aria-hidden="true" style="color:red;cursor:pointer;" data-item_id="'.$item_id.'" data-invoice_code="'.$value->invoice_code.'" data-product_id_fk="'.$value->product_id_fk.'" ></i> 
                               ';
                            }

                              $item_id++;
                              
                          }  

              $pn .= '</div>';  
              $pn .= '</div>';  
            
            }

              $pn .=     
              '<div class="divTableRow">
              <div class="divTableCell" style="text-align:right;font-weight:bold;"> รวม </div>
              <div class="divTableCell" style="text-align:center;font-weight:bold;">'.$sum_amt.'</div>
              <div class="divTableCell" style="text-align:center;"> </div>
              <div class="divTableCell" style="text-align:center;"> </div>
              </div>
              ';

              $pn .= '</div>';  

          return $pn;
      })
      ->escapeColumns('column_002')  
      ->make(true);
    }



// http://localhost/aiyara.host/backend/pay_product_receipt
// %%%%%%%%%%%%%%%%%%%%%%%
   public function Datatable0002FIFO(Request $request){


      $temp_ppp_001 = "temp_ppp_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_ppp_0022 = "temp_ppp_0022".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_db_stocks_check002 = "temp_db_stocks_check002".\Auth::user()->id;
      $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
      $temp_db_stocks_compare002 = "temp_db_stocks_compare002".\Auth::user()->id;

      $TABLES = DB::select(" SHOW TABLES ");
      // return $TABLES;
      $array_TABLES = [];
      foreach($TABLES as $t){
        // print_r($t->Tables_in_aiyara_db);
        array_push($array_TABLES, $t->Tables_in_aiyara_db);
      }
      // เก็บรายการสินค้าที่จ่าย ตอน FIFO 

      if(in_array($temp_ppp_004,$array_TABLES)){
        // return "IN";
        DB::select(" TRUNCATE TABLE $temp_ppp_004  ");
      }else{
        // return "Not";
        DB::select(" CREATE TABLE $temp_ppp_004 LIKE temp_ppp_004_template ");
      }

      $picking_id = implode(",",$request->picking_id);

      $r_db_pick_pack_packing_code = DB::select(" SELECT * FROM db_pick_pack_packing_code WHERE id in($picking_id) ");
      // return $r_db_pick_pack_packing_code[0]->receipt ;
      $receipt = explode(",",$r_db_pick_pack_packing_code[0]->receipt);

      $arr_01 = [];
      foreach ($receipt as $key => $value) {
        array_push($arr_01,'"'.$value.'"');
      }

      $receipt = implode(",",$arr_01);

      $sTable = DB::select("
        SELECT * from $temp_ppp_0022 WHERE pick_pack_packing_code_id_fk in($picking_id)  GROUP BY pick_pack_packing_code_id_fk
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
          $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // เก็บรายการที่หยิบสินค้ามาจากชั้นต่างๆ ตอน FIFO 
          $temp_db_stocks = "temp_db_stocks".\Auth::user()->id; 
          $temp_db_stocks_check002 = "temp_db_stocks_check002".\Auth::user()->id; 
          $temp_db_stocks_compare002 = "temp_db_stocks_compare002".\Auth::user()->id; 

           $Products = DB::select("
            SELECT * from $temp_ppp_0022
            where $temp_ppp_0022.pick_pack_packing_code_id_fk=".$row->pick_pack_packing_code_id_fk."

          ");

          $pn = '<div class="divTable"><div class="divTableBody">';
          $pn .=     
          '<div class="divTableRow">
          <div class="divTableCell" style="width:240px;font-weight:bold;">ชื่อสินค้า</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จ่ายครั้งนี้</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">ค้างจ่าย</div>
          <div class="divTableCell" style="width:450px;text-align:center;font-weight:bold;">
                     
                      <div class="divTableRow">
                      <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">หยิบสินค้าจากคลัง</div>
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


          foreach ($Products as $key => $value) {

            // return $value->amt;

               $temp_db_stocks = "temp_db_stocks".\Auth::user()->id; 
               $amt_pay_this = $value->amt; 
                // จำนวนที่จะ Hint ให้ไปหยิบจากแต่ละชั้นมา ตามจำนวนที่สั่งซื้อ โดยการเช็คไปทีละชั้น fifo จนกว่าจะพอ
                // เอาจำนวนที่เบิก เป็นเช็ค กับ สต๊อก ว่ามีพอหรือไม่ โดยเอาทุกชั้นที่มีมาคิดรวมกันก่อนว่าพอหรือไม่ 
                $temp_db_stocks_01 = DB::select(" SELECT sum(amt) as amt,count(*) as amt_floor from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk."  ");
                $amt_floor = $temp_db_stocks_01[0]->amt_floor;


                // Case 1 > มีสินค้าพอ (รวมจากทุกชั้น) และ ในคลังมีมากกว่า ที่ต้องการซื้อ
                if($temp_db_stocks_01[0]->amt>0 && $temp_db_stocks_01[0]->amt>=$amt_pay_this ){ 

                  $pay_this = $value->amt ;
                  $amt_pay_remain = 0;
                  
                    $pn .=     
                    '<div class="divTableRow">
                    <div class="divTableCell" style="font-weight:bold;padding-bottom:15px;">'.$value->product_name.'</div>
                    <div class="divTableCell" style="text-align:center;font-weight:bold;">'. $pay_this .'</div> 
                    <div class="divTableCell" style="text-align:center;"> '.$amt_pay_remain.' </div>  
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
                          $pay_this = @$rs_[0]->amt_remain?@$rs_[0]->amt_remain:$value->amt ;

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
                                      ->where('pick_pack_packing_code_id_fk', $row->pick_pack_packing_code_id_fk)
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
                                              pick_pack_packing_code_id_fk,
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
                                                '".$value->pick_pack_packing_code_id_fk."',
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
                    <div class="divTableCell" style="font-weight:bold;padding-bottom:15px;">'.$value->product_name.'</div>
                    <div class="divTableCell" style="text-align:center;font-weight:bold;">'. $pay_this .'</div> 
                    <div class="divTableCell" style="text-align:center;'.$css_red.'">'. $amt_pay_remain .'</div>  
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
                                    <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;color:blue;"> '.$pay_this.' </div>
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
                                      ->where('pick_pack_packing_code_id_fk', $row->pick_pack_packing_code_id_fk)
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
                                              pick_pack_packing_code_id_fk,
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
                                                '".$value->pick_pack_packing_code_id_fk."',
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
                                      ->where('pick_pack_packing_code_id_fk', $row->pick_pack_packing_code_id_fk)
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
                                              pick_pack_packing_code_id_fk,
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
                                                '".$value->pick_pack_packing_code_id_fk."',
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

                     $amt_pay_remain = $value->amt ;
                     $pay_this = 0 ;
                     $css_red = $amt_pay_remain>0?'color:red;font-weight:bold;':'';

                    $pn .=     
                    '<div class="divTableRow">
                    <div class="divTableCell" style="font-weight:bold;padding-bottom:15px;">'.$value->product_name.'</div>
                    <div class="divTableCell" style="text-align:center;font-weight:bold;">'. $pay_this .'</div> 
                    <div class="divTableCell" style="text-align:center;'.$css_red.'">'. $amt_pay_remain .'</div>  
                    <div class="divTableCell" style="width:450px;text-align:center;"> ';

                      $pn .=     
                            '<div class="divTableRow">
                            <div class="divTableCell" style="width:200px;text-align:center;color:red;"> *** ไม่มีสินค้าในคลัง *** </div>
                            <div class="divTableCell" style="width:200px;text-align:center;">  </div>
                            <div class="divTableCell" style="width:100px;text-align:center;">  </div>
                            </div>
                            ';

                              $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt=0 and product_id_fk=".$value->product_id_fk." ");
                    
                     $i = 1;
                     foreach ($temp_db_stocks_02 as $v_02) {

                             // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                     $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                     $_choose=DB::table("$temp_ppp_004")
                                      ->where('pick_pack_packing_code_id_fk', $row->pick_pack_packing_code_id_fk)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->get();
                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppp_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              pick_pack_packing_code_id_fk,
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
                                                '".$value->pick_pack_packing_code_id_fk."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt."',
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

            $rs_temp_ppp_004 = DB::select(" select product_id_fk,lot_number,lot_expired_date,sum(amt_get) as amt_get, sum(amt_lot) as amt_lot, amt_remain  from $temp_ppp_004 group by pick_pack_packing_code_id_fk,product_id_fk,lot_number  ");

          foreach ($rs_temp_ppp_004 as $key => $v) {

              $_choose=DB::table("$temp_db_stocks_check002")
                ->where('pick_pack_packing_code_id_fk', $row->pick_pack_packing_code_id_fk)
                ->where('product_id_fk', $v->product_id_fk)
                ->where('lot_number', $v->lot_number)
                // ->where('lot_expired_date', $v->lot_expired_date)
                // ->where('amt_get', $v->amt_get)
                // ->where('amt_lot', $v->amt_lot)
                // ->where('amt_remain', $v->amt_remain)
                ->get();
                if($_choose->count() == 0){
                  DB::select(" INSERT IGNORE INTO $temp_db_stocks_check002 (pick_pack_packing_code_id_fk,product_id_fk,lot_number,lot_expired_date,amt_get,amt_lot,amt_remain) 
                    VALUES (
                    '".$row->pick_pack_packing_code_id_fk."', ".$v->product_id_fk.", '".$v->lot_number."', '".$v->lot_expired_date."', ".$v->amt_lot.", '".$v->amt_lot."', '".$v->amt_remain."'
                    )
                  ");
                }

          }

        
          $rs_temp_db_stocks_check002 = DB::select(" select pick_pack_packing_code_id_fk, sum(amt_get) as amt_get, sum(amt_lot) as amt_lot, sum(amt_remain) as amt_remain from $temp_db_stocks_check002 group by pick_pack_packing_code_id_fk ");

          foreach ($rs_temp_db_stocks_check002 as $key => $v) {

              $_choose=DB::table("$temp_db_stocks_compare002")
                // ->where('invoice_code', $row->invoice_code)
                // ->where('amt_get', $v->amt_get)
                // ->where('amt_lot', $v->amt_lot)
                // ->where('amt_remain', $v->amt_remain)
                ->get();
                // if($_choose->count() == 0){
                  DB::select(" INSERT IGNORE INTO $temp_db_stocks_compare002 (`pick_pack_packing_code_id_fk`, `amt_get`, `amt_lot`, `amt_remain`)
                    VALUES (
                    '".$row->pick_pack_packing_code_id_fk."', ".$v->amt_get.", '".$v->amt_lot."', '".$v->amt_remain."'
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
          <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">หยิบสินค้าจากคลัง</div>
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
            SELECT * from $temp_ppp_004 WHERE pick_pack_packing_code_id_fk='".$row->pick_pack_packing_code_id_fk."' 
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
      ->make(true);
    }


    public function ajaxSearch_requisition_db_orders002(Request $request)
    {

            $temp_db_stocks_compare002 = "temp_db_stocks_compare002".\Auth::user()->id;

            $r_compare_1 =  DB::select(" 
              SELECT amt_get+amt_lot+amt_remain as sum from $temp_db_stocks_compare002 ORDER BY id DESC LIMIT 1,1;
            ");

            $x = @$r_compare_1[0]->sum?@$r_compare_1[0]->sum:0;

            $r_compare_2 =  DB::select(" 
              SELECT amt_get+amt_lot+amt_remain as sum from $temp_db_stocks_compare002 ORDER BY id DESC LIMIT 0,1;
            ");
            $y = @$r_compare_2[0]->sum?@$r_compare_2[0]->sum:0;

            if($x == $y){
                  return "No_changed";
            }else{
                  return "changed";
            }
       
      
    }// Close > function



}// Close > class
