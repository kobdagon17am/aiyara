<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;
use Redirect;
use Session;
use PDO;
use App\Http\Controllers\backend\AjaxController;

class Pay_requisition_001Controller extends Controller
{

    public function index(Request $request)
    {
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $customer = DB::select(" SELECT
              customers.user_name AS cus_code,
              customers.prefix_name,
              customers.first_name,
              customers.last_name,
              db_pay_product_receipt_001.customer_id_fk
              FROM
              db_pay_product_receipt_001
              left Join customers ON db_pay_product_receipt_001.customer_id_fk = customers.id
              GROUP BY db_pay_product_receipt_001.customer_id_fk
              ");
      // $sPay_requisition_status = \App\Models\Backend\Pay_requisition_status::get();
      $sPay_requisition_status = DB::select(" select * from dataset_pay_requisition_status_02 ");

      $sAdmin = DB::select(" select * from ck_users_admin where isActive='Y' AND branch_id_fk=".\Auth::user()->branch_id_fk." ");

        return View('backend.pay_requisition_001.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,
           'sBranchs'=>$sBranchs,
           'customer'=>$customer,
           'sPay_requisition_status'=>$sPay_requisition_status,
           'sAdmin'=>$sAdmin,
        ) );
    }

    public function pay_requisition_001_report(Request $request)
    {
      $sAdmin = DB::select(" select * from ck_users_admin where isActive='Y' AND branch_id_fk=".\Auth::user()->branch_id_fk." ");
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
        return View('backend.pay_requisition_001.pay_requisition_001_report')->with(
          array(
             'sBusiness_location'=>$sBusiness_location,
             'sBranchs'=>$sBranchs,
             'sAdmin'=>$sAdmin,
          ) );
    }

    public function pay_requisition_001_remain(Request $request)
    {
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $customer = DB::select(" SELECT
              customers.user_name AS cus_code,
              customers.prefix_name,
              customers.first_name,
              customers.last_name,
              db_pay_product_receipt_001.customer_id_fk
              FROM
              db_pay_product_receipt_001
              left Join customers ON db_pay_product_receipt_001.customer_id_fk = customers.id
              GROUP BY db_pay_product_receipt_001.customer_id_fk
              ");
      // $sPay_requisition_status = \App\Models\Backend\Pay_requisition_status::get();
      $sPay_requisition_status = DB::select(" select * from dataset_pay_requisition_status_02 ");

      $sAdmin = DB::select(" select * from ck_users_admin where isActive='Y' AND branch_id_fk=".\Auth::user()->branch_id_fk." ");

        return View('backend.pay_requisition_001.pay_requisition_001_remain')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,
           'sBranchs'=>$sBranchs,
           'customer'=>$customer,
           'sPay_requisition_status'=>$sPay_requisition_status,
           'sAdmin'=>$sAdmin,
        ) );
    }


    public function create()
    {
      return View('backend.pay_requisition_001.form');
    }


    public function store(Request $request)
    {
        // dd($request->all());
        return $this->form();
    }

    public function edit($id)
    {
      return View('backend.pay_requisition_001.form');
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      return $this->form($id);
    }

   public function form($id=NULL)
    {

      \DB::beginTransaction();
      try {
          if( $id ){
          //   // $sRow = \App\Models\Backend\Pay_product_receipt::find($id);
          //   $sRow = \App\Models\Backend\Consignments::find(request('consignments_id'));
          //   // $db_pick_warehouse_tmp = DB::select(" select * from db_pick_warehouse_tmp where id = '$id' ");
          //   // $sRow = \App\Models\Backend\Consignments::where('recipient_code',$db_pick_warehouse_tmp[0]->invoice_code);

          // $sRow->mobile = request('mobile');
          // $sRow->sent_date = request('sent_date');
          // $sRow->status_sent = request('status_sent')==1?1:0;
          // $sRow->approver = \Auth::user()->id;
          // $sRow->created_at = date('Y-m-d H:i:s');
          // $sRow->save();


        }

          \DB::commit();

          return redirect()->to(url("backend/pay_product_receipt_001"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
      }
    }

    public function destroy(Request $request)
    {
      // return $request->id;
      // return $request->invoice_code;
      // สถานะกลับไปเป็นรอจ่าย 1 ถ้าเป็นกรณียกเลิกใบเสร็จ 4 ให้เกิดจากการยกเลิกจากคลัง
      DB::select("
        UPDATE db_pay_product_receipt_001
        SET
        action_user=".(\Auth::user()->id)." ,
        action_date=now() ,
        status_sent=1
        WHERE invoice_code='".$request->invoice_code."' ");

      DB::update(" UPDATE db_pay_product_receipt_002 SET status_cancel=1 WHERE invoice_code='".$request->invoice_code."' ");
      DB::update(" DELETE FROM db_pick_warehouse_qrcode WHERE invoice_code='".$request->invoice_code."' ");

      // คืนสินค้ากลับ คลัง เพราะไม่ได้จ่ายแล้ว
      // DB::select(" TRUNCATE db_stocks_return; ");
      // $r = DB::select(" SELECT * FROM db_pay_product_receipt_002 WHERE invoice_code='".$request->invoice_code."'; ");

      // foreach ($r as $key => $v) {
             // $_choose=DB::table("db_stocks_return")
                // ->where('time_pay', $v->time_pay)
                // ->where('business_location_id_fk', $v->business_location_id_fk)
                // ->where('branch_id_fk', $v->branch_id_fk)
                // ->where('product_id_fk', $v->product_id_fk)
                // ->where('lot_number', $v->lot_number)
                // ->where('lot_expired_date', $v->lot_expired_date)
                // ->where('amt', $v->amt_get)
                // ->where('product_unit_id_fk', $v->product_unit_id_fk)
                // ->where('warehouse_id_fk', $v->warehouse_id_fk)
                // ->where('zone_id_fk', $v->zone_id_fk)
                // ->where('shelf_id_fk', $v->shelf_id_fk)
                // ->where('shelf_floor', $v->shelf_floor)
                // ->where('invoice_code', $request->invoice_code)
                // ->get();

                // if($_choose->count() == 0){

                    DB::select(" INSERT INTO db_stocks_return (time_pay, business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk,shelf_floor, invoice_code, created_at, updated_at,status_cancel)
                    SELECT time_pay,
                    business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt_get, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk, shelf_floor,invoice_code, created_at,now(),0
                    FROM db_pay_product_receipt_002 WHERE time_pay not in(select time_pay from db_stocks_return where time_pay=db_pay_product_receipt_002.time_pay and business_location_id_fk=db_pay_product_receipt_002.business_location_id_fk AND branch_id_fk=db_pay_product_receipt_002.branch_id_fk AND product_id_fk=db_pay_product_receipt_002.product_id_fk AND lot_number=db_pay_product_receipt_002.lot_number AND lot_expired_date=db_pay_product_receipt_002.lot_expired_date AND amt=db_pay_product_receipt_002.amt_get AND product_unit_id_fk=db_pay_product_receipt_002.product_unit_id_fk AND warehouse_id_fk=db_pay_product_receipt_002.warehouse_id_fk AND zone_id_fk=db_pay_product_receipt_002.zone_id_fk AND shelf_id_fk=db_pay_product_receipt_002.shelf_id_fk AND shelf_floor=db_pay_product_receipt_002.shelf_floor AND invoice_code=db_pay_product_receipt_002.invoice_code) ; ");


                        $insertStockMovement = new  AjaxController();

                              // รับคืนจากการยกเลิกใบสั่งซื้อ db_stocks_return
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
                                      'รับคืนจากการยกเลิกใบสั่งซื้อ' as note,
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


                // }

       // }

      // สร้างตาราง temp group ตามรายการข้างล่าง
      $temp_db_stocks_from_return = "temp_db_stocks_from_return".\Auth::user()->id;
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_from_return ; ");
      DB::select(" CREATE TABLE $temp_db_stocks_from_return Like db_stocks_return ; ");
      DB::select(" INSERT INTO $temp_db_stocks_from_return (business_location_id_fk,branch_id_fk,product_id_fk,lot_number,lot_expired_date,amt,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,invoice_code)

          select
          business_location_id_fk,branch_id_fk,product_id_fk,lot_number,lot_expired_date,sum(amt) as amt,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,invoice_code
          FROM db_stocks_return
          WHERE
          status_cancel=0
          GROUP BY
          business_location_id_fk,branch_id_fk,product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor

         ; ");


      DB::select(" UPDATE db_stocks
               Join $temp_db_stocks_from_return ON db_stocks.business_location_id_fk = $temp_db_stocks_from_return.business_location_id_fk
               AND db_stocks.branch_id_fk = $temp_db_stocks_from_return.branch_id_fk
               AND db_stocks.product_id_fk = $temp_db_stocks_from_return.product_id_fk
               AND db_stocks.lot_number = $temp_db_stocks_from_return.lot_number
               AND db_stocks.lot_expired_date = $temp_db_stocks_from_return.lot_expired_date
               AND db_stocks.warehouse_id_fk = $temp_db_stocks_from_return.warehouse_id_fk
               AND db_stocks.zone_id_fk = $temp_db_stocks_from_return.zone_id_fk
               AND db_stocks.shelf_id_fk = $temp_db_stocks_from_return.shelf_id_fk
               AND db_stocks.shelf_floor = $temp_db_stocks_from_return.shelf_floor
               AND $temp_db_stocks_from_return.invoice_code='".$request->invoice_code."'
               SET db_stocks.amt=db_stocks.amt+($temp_db_stocks_from_return.amt) ");

      DB::select(" UPDATE db_stocks_return
               Join $temp_db_stocks_from_return ON db_stocks_return.business_location_id_fk = $temp_db_stocks_from_return.business_location_id_fk
               AND db_stocks_return.branch_id_fk = $temp_db_stocks_from_return.branch_id_fk
               AND db_stocks_return.product_id_fk = $temp_db_stocks_from_return.product_id_fk
               AND db_stocks_return.lot_number = $temp_db_stocks_from_return.lot_number
               AND db_stocks_return.lot_expired_date = $temp_db_stocks_from_return.lot_expired_date
               AND db_stocks_return.warehouse_id_fk = $temp_db_stocks_from_return.warehouse_id_fk
               AND db_stocks_return.zone_id_fk = $temp_db_stocks_from_return.zone_id_fk
               AND db_stocks_return.shelf_id_fk = $temp_db_stocks_from_return.shelf_id_fk
               AND db_stocks_return.shelf_floor = $temp_db_stocks_from_return.shelf_floor
               AND $temp_db_stocks_from_return.invoice_code='".$request->invoice_code."'
               SET db_stocks_return.status_cancel=1 ");

    }

    public function destroy_some(Request $request)
    {

      DB::update(" UPDATE db_pay_product_receipt_002 SET status_cancel=1 WHERE invoice_code='".$request->invoice_code."' AND time_pay='".$request->time_pay."' ");
      // เอาสินค้าคืนคลัง

      $r = DB::select("
        SELECT
        time_pay,business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt_get, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk, shelf_floor,invoice_code, created_at,now()
         FROM db_pay_product_receipt_002 WHERE invoice_code='".$request->invoice_code."' AND time_pay='".$request->time_pay."' ; ");

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
                  ->where('invoice_code', $request->invoice_code)
                  ->get();
                  if($_choose->count() == 0){

                         DB::select(" INSERT INTO db_stocks_return (time_pay,business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk,shelf_floor, invoice_code, created_at, updated_at,status_cancel)
                        SELECT
                        time_pay,business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt_get, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk, shelf_floor,invoice_code, created_at,now(),1
                         FROM db_pay_product_receipt_002 WHERE invoice_code='".$request->invoice_code."' AND time_pay='".$v->time_pay."' ; ");



                     }


                     DB::select(" UPDATE db_pay_product_receipt_001 SET status_sent=2 WHERE invoice_code='".$request->invoice_code."' ; ");

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



                             $insertStockMovement = new  AjaxController();

                              // รับคืนจากการยกเลิกใบสั่งซื้อ db_stocks_return
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
                                      'รับคืนจากการยกเลิกใบสั่งซื้อ' as note,
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


      $r2 = DB::select("
        SELECT * FROM db_pay_product_receipt_002 WHERE invoice_code='".$request->invoice_code."' AND time_pay='".$request->time_pay."' AND status_cancel=1 ; ");

         foreach ($r2 as $key => $v) {
               $_choose=DB::table("db_pay_product_receipt_002_cancel_log")
                  ->where('time_pay', $v->time_pay)
                  ->where('business_location_id_fk', $v->business_location_id_fk)
                  ->where('branch_id_fk', $v->branch_id_fk)
                  ->where('orders_id_fk', $v->orders_id_fk)
                  ->where('customers_id_fk', $v->customers_id_fk)
                  ->where('invoice_code', $v->invoice_code)
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

                          DB::select(" INSERT IGNORE INTO db_pay_product_receipt_002_cancel_log (time_pay, business_location_id_fk, branch_id_fk, orders_id_fk, customers_id_fk, invoice_code, product_id_fk, product_name, amt_need, amt_get, amt_lot, amt_remain, product_unit_id_fk, product_unit, lot_number, lot_expired_date, warehouse_id_fk, zone_id_fk, shelf_id_fk, shelf_floor, status_cancel, created_at)
                            VALUES
                           (".$v->time_pay.", ".$v->business_location_id_fk.", ".$v->branch_id_fk.", ".$v->orders_id_fk.", ".$v->customers_id_fk.", '".$v->invoice_code."', ".$v->product_id_fk.", '".$v->product_name."', ".$v->amt_get.", 0 , 0, ".$v->amt_get.", ".$v->product_unit_id_fk.", '".$v->product_unit."', '".$v->lot_number."', '".$v->lot_expired_date."', ".$v->warehouse_id_fk.", ".$v->zone_id_fk.", ".$v->shelf_id_fk.", ".$v->shelf_floor.", ".$v->status_cancel.", '".$v->created_at."') ");

                   }


           }


             $ch_status_cancel = DB::select(" SELECT * FROM db_pay_product_receipt_002 WHERE invoice_code='".$request->invoice_code."' AND status_cancel in (0) ");
             if(count(@$ch_status_cancel)==0 || empty(@$ch_status_cancel)){
                DB::select(" UPDATE db_pay_product_receipt_001 SET status_sent=1 WHERE invoice_code='".$request->invoice_code."' ");
             }



    }




    public function ajaxApproveProductSent(Request $request)
    {
      // return $request->id;
      DB::select("UPDATE db_pay_product_receipt_001
        SET
        pay_user=".(\Auth::user()->id)." ,
        pay_date=now() ,
        action_user=0 ,
        action_date=NULL ,
        status_sent=3
        WHERE id=$request->id ");

    }

    public function Datatable001(Request $req){


       $w04 = "";
       $w05 = "";
       $w06 = "";
       $w07 = "";
       $w08 = "";

        if(!empty($req->business_location_id_fk)){
           $w01 = " AND db_pay_requisition_001.business_location_id_fk=".$req->business_location_id_fk ;
        }else{
           $w01 = "";
        }

        if(!empty($req->branch_id_fk)){
           // $w02 = "  AND
           //              (
           //              db_pay_product_receipt_001.branch_id_fk=".(\Auth::user()->branch_id_fk)." OR
           //              db_pay_product_receipt_001.branch_id_fk_tosent=".(\Auth::user()->branch_id_fk)."
           //              ) " ;
           $w02 = " AND db_pay_requisition_001.branch_id_fk = ".$req->branch_id_fk." " ;
        }else{
           // $w02 = " AND db_pay_product_receipt_001.branch_id_fk_tosent = ".(\Auth::user()->branch_id_fk)." " ;
           $w02 = "" ;
        }

        if(!empty($req->customer_id_fk)){
           $w03 = " AND db_pay_requisition_001.customer_id_fk LIKE '%".$req->customer_id_fk."%'  " ;
        }else{
           $w03 = "";
        }


        if(isset($req->status_sent) && $req->status_sent!=0 ){
            if(!empty($req->btnSearch03) && $req->btnSearch03==1 ){
                $w08 = " and (db_pay_requisition_001.status_sent='".$req->status_sent."' OR db_pay_requisition_001.status_sent=2) " ;
             }else{
                $w08 = " and db_pay_requisition_001.status_sent='".$req->status_sent."' " ;
             }
        }else{
           // $w08 = " and db_pay_product_receipt_001.status_sent=3 AND date(db_pay_product_receipt_001.pay_date)=CURDATE() ";
           $w08 = " AND date(db_pay_requisition_001.pay_date)=CURDATE() OR date(db_pay_requisition_001.action_date)=CURDATE() ";
        }

        if(!empty($req->startDate) && !empty($req->endDate)){
           $w04 = " and date(db_pay_requisition_001.pay_date) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
           $w05 = "";
        }else{
           $w04 = "";
        }

        if(!empty($req->btnSearch03) && $req->btnSearch03==1 ){
           $w04 = " and date(db_pay_requisition_001.pay_date) BETWEEN '".$req->startDate."' AND '".$req->endDate."' AND db_pay_requisition_001.status_sent!=3 " ;
           $w05 = "";
        }


       if(isset($req->txtSearch_001)){
           $w06 = " AND LOWER(db_pay_requisition_001.invoice_code) LIKE '%".strtolower($req->txtSearch_001)."%' ";
        }else{
           $w06 = "";
        }

        if(!empty($req->startPayDate) && !empty($req->endPayDate)){
           $w07 = " and date(db_pay_requisition_001.pay_date) BETWEEN '".$req->startPayDate."' AND '".$req->endPayDate."'  " ;
        }else{
           $w07 = "";
        }

        if(!empty($req->action_user)){
           $w09 = " and ( db_pay_requisition_001.pay_user = '".$req->action_user."' OR db_pay_requisition_001.action_user = '".$req->action_user."')  " ;
        }else{
           $w09 = "";
        }

        $User_branch_id = \Auth::user()->branch_id_fk;
        $sPermission = \Auth::user()->permission ;
        $menu_id = Session::get('session_menu_id');
        $role_group_id = \Auth::user()->role_group_id_fk;
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
        $can_approve = @$menu_permit->can_approve==1?'1':'0';

        if($sPermission==1){
                    $sTable = DB::select("
                    SELECT
                    db_pay_requisition_001.id,
                    db_pay_requisition_001.status_sent,
                    db_pay_requisition_001.action_user,
                    db_pay_requisition_001.pay_user,
                    db_pay_requisition_001.action_date,
                    db_pay_requisition_001.pay_date,
                    db_pay_requisition_001.pick_pack_requisition_code_id_fk,
                    DATE(db_pay_requisition_001.packing_date) as packing_date,
                    db_pay_requisition_001.customer_id_fk,
                    db_pay_requisition_001.branch_id_fk,
                    db_pay_requisition_001.business_location_id_fk,
                    db_pay_requisition_001.address_send_type
                    FROM
                    db_pay_requisition_001
                    WHERE db_pay_requisition_001.address_send_type in (3)
                   ".$w01."
                   ".$w02."
                   ".$w03."
                   ".$w04."
                   ".$w05."
                   ".$w06."
                   ".$w07."
                   ".$w08."
                   ".$w09."
                   GROUP BY pick_pack_requisition_code_id_fk
                   ORDER BY db_pay_requisition_001.pay_date DESC
                 ");
        }else{

              $sTable = DB::select("
                        SELECT
                        db_pay_requisition_001.id,
                        db_pay_requisition_001.status_sent,
                        db_pay_requisition_001.action_user,
                        db_pay_requisition_001.pay_user,
                        db_pay_requisition_001.action_date,
                        db_pay_requisition_001.pay_date,
                        db_pay_requisition_001.pick_pack_requisition_code_id_fk,
                        DATE(db_pay_requisition_001.packing_date) as packing_date,
                        db_pay_requisition_001.customer_id_fk,
                        db_pay_requisition_001.branch_id_fk,
                        db_pay_requisition_001.business_location_id_fk,
                        db_pay_requisition_001.address_send_type
                        FROM
                        db_pay_requisition_001
                        WHERE db_pay_requisition_001.address_send_type in (3)
                       ".$w01."
                       ".$w02."
                       ".$w03."
                       ".$w04."
                       ".$w05."
                       ".$w06."
                       ".$w07."
                       ".$w08."
                       ".$w09."
                       GROUP BY pick_pack_requisition_code_id_fk
                       ORDER BY db_pay_requisition_001.pay_date DESC
                     ");

        }
                       // AND db_pay_requisition_001.branch_id_fk_tosent = ".(\Auth::user()->branch_id_fk)."

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('pick_pack_requisition_code_id_fk_2', function($row) {
            return $row->pick_pack_requisition_code_id_fk;
      })
      ->addColumn('pick_pack_requisition_code_id_fk', function($row) {
            return "<span style='cursor:pointer;' class='pick_pack_requisition_code_id_fk' data-toggle='tooltip' data-placement='top' title='คลิ้กเพื่อดูรายละเอียด' data-pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."' >P3".sprintf("%05d",$row->pick_pack_requisition_code_id_fk)."</span>";
      })
      ->escapeColumns('pick_pack_requisition_code_id_fk')
      ->addColumn('customer', function($row) {

        $addr_sent =  DB::select("
            SELECT customer_id,from_table FROM `customers_addr_sent` WHERE packing_code=".$row->pick_pack_requisition_code_id_fk."
        ");
        $customer_id = @$addr_sent[0]->customer_id?$addr_sent[0]->customer_id:0;

            $rs = DB::select(" select * from customers where id=".@$customer_id." ");
            if($rs){
                return @$rs[0]->user_name." <br> ".@$rs[0]->prefix_name.@$rs[0]->first_name." ".@$rs[0]->last_name;
            }

      })
      ->addColumn('status_sent', function($row) {
            // $rs = DB::select(" select * from dataset_pay_product_status where id=".@$row->status_sent." ");
        $rs = \App\Models\Backend\Pay_requisition_status::find($row->status_sent);
            if(@$row->status_sent==3||@$row->status_sent==4||@$row->status_sent==5){
              return '<span class="badge badge-pill badge-success font-size-16">'.@$rs->txt_desc.'</span>';
            }elseif(@$row->status_sent==1||@$row->status_sent==2){
              return '<span class="badge badge-pill badge-warning font-size-16" style="color:black;">'.@$rs->txt_desc.'</span>';
            }else{
              return '<span class="badge badge-pill badge-info font-size-16">'.@$rs->txt_desc.'</span>';
            }

      })
      ->addColumn('status_sent_2', function($row) {
            return $row->status_sent;
      })
      ->addColumn('status_cancel_all', function($row) {
            $P = DB::select(" select status_cancel from db_pay_requisition_002 where pick_pack_requisition_code_id_fk='".@$row->pick_pack_requisition_code_id_fk."' AND status_cancel=0 ");
            if(count($P)>0){
              return 1;
            }else{
              return 0;
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
      ->addColumn('action_user', function($row) {
        // if(@$row->action_user){
        //   $P = DB::select(" select * from ck_users_admin where id=".@$row->action_user." ");
        //     return @$P[0]->name." <br> ".@$row->action_date;
        // }else{
          return '-';
        // }
      })
      ->escapeColumns('action_user')
      ->addColumn('pay_user', function($row) {
        if(@$row->pay_user){
           $P = DB::select(" select * from ck_users_admin where id=".@$row->pay_user." ");
          return @$P[0]->name." <br> ".@$row->pay_date;
        }else{
          return '-';
        }
      })
      ->escapeColumns('pay_user')
       ->addColumn('branch', function($row) {
             $P = DB::select(" select * from branchs where id=".@$row->branch_id_fk." ");
             return @$P[0]->b_name;
      })
       ->addColumn('address_send_type', function($row) {
            if(@$row->address_send_type==1){
                 return 'มารับด้วยตนเองที่สาขา';
            }else if(@$row->address_send_type==2){
              if(@$row->branch_id_fk_tosent==(\Auth::user()->branch_id_fk)){
                return 'รับที่สาขานี้';
              }else{
                return 'รับที่สาขาอื่น';
              }
            }else if(@$row->address_send_type==3){
                return 'จัดส่งพัสดุ';
            }
      })
      ->make(true);
    }


    public function Datatable002(Request $req){

        if(!empty($req->business_location_id_fk)){
           $w01 = " AND db_pay_product_receipt_002.business_location_id_fk=".$req->business_location_id_fk ;
        }else{
           $w01 = "";
        }

        if(!empty($req->branch_id_fk)){
           $w02 = " AND db_pay_product_receipt_002.branch_id_fk = ".$req->branch_id_fk." " ;
        }else{
           $w02 = "";
        }

        if(!empty($req->customer_id_fk)){
           $w03 = " AND db_pay_product_receipt_001.customer_id_fk = ".$req->customer_id_fk." " ;
        }else{
           $w03 = "";
        }

        if(!empty($req->startDate) && !empty($req->endDate)){
           $w04 = " and date(db_pay_product_receipt_001.action_date) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
        }else{
           $w04 = "";
        }


        if(isset($req->txtSearch_002)){
          $w05 = " AND (LOWER(db_pay_product_receipt_002.product_id_fk) LIKE '%".strtolower(ltrim($req->txtSearch_002, '0'))."%' ";
          $w05 .= " OR LOWER(db_pay_product_receipt_002.product_name) LIKE '%".strtolower(ltrim($req->txtSearch_002, '0'))."%') ";
        }else{
          $w05 = "";
        }

        if(isset($req->status_sent)){
           $w06 = " and db_pay_product_receipt_001.status_sent='".$req->status_sent."' " ;
        }else{
           $w06 = "";
        }

      $sTable = DB::select("

              SELECT
              db_pay_product_receipt_002.id,
              db_pay_product_receipt_002.invoice_code,
              db_pay_product_receipt_002.product_id_fk,
              db_pay_product_receipt_002.product_name,
              db_pay_product_receipt_002.lot_number,
              db_pay_product_receipt_002.amt_lot,
              db_pay_product_receipt_002.product_unit,
              db_pay_product_receipt_002.lot_expired_date,
              db_pay_product_receipt_002.business_location_id_fk,
              db_pay_product_receipt_002.branch_id_fk,
              ( CASE WHEN db_pay_product_receipt_001.customer_id_fk<>'' THEN db_pay_product_receipt_001.customer_id_fk ELSE 0 END ) as customer_id_fk,
              ( CASE WHEN db_pay_product_receipt_001.pay_user<>'' THEN db_pay_product_receipt_001.pay_user ELSE 0 END ) as pay_user,
              db_pay_product_receipt_001.action_date,
              ( CASE WHEN db_pay_product_receipt_001.status_sent<>'' THEN db_pay_product_receipt_001.status_sent ELSE 0 END) AS status_sent
              FROM
              db_pay_product_receipt_002
              Join db_pay_product_receipt_001 ON db_pay_product_receipt_002.invoice_code = db_pay_product_receipt_001.invoice_code
              WHERE 1
             ".$w01."
             ".$w02."
             ".$w03."
             ".$w04."
             ".$w05."
             ".$w06."
              group by db_pay_product_receipt_002.product_id_fk
              ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
          return "<b>".sprintf("%04d",@$row->product_id_fk)." <br> ".@$row->product_name."</b>";
      })
      ->escapeColumns('column_001')
      ->addColumn('column_002', function($row) {
          $Products = DB::select("
              SELECT * FROM db_pay_product_test_002
              WHERE product_id_fk = '".$row->product_id_fk."' AND invoice_code='".$row->invoice_code."'
              ");

            $pn = '<div class="divTable"><div class="divTableBody">';
            // if($Products[0]->id==1){
            $pn .=
                  '<div class="divTableRow">
                  <div class="divTableCell" style="width:100px;font-weight:bold;">Lot number</div>
                  <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> จำนวน </div>
                  <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วย </div>
                  </div>
                  ';
            // }
            $sum_amt = 0 ;
            foreach ($Products as $key => $value) {
              $sum_amt += $value->amt_lot;
              $pn .=
                  '<div class="divTableRow">
                  <div class="divTableCell" style="width:100px;">'.$value->lot_number.'</div>
                  <div class="divTableCell" style="width:50px;text-align:center;">'.$value->amt_lot.'</div>
                  <div class="divTableCell" style="width:50px;text-align:center;">'.$value->product_unit.'</div>
                  </div>
                  ';
             }
              $pn .=
                  '<div class="divTableRow">
                  <div class="divTableCell" style="width:100px;font-weight:bold;"> รวม </div>
                  <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;">'.$sum_amt.'</div>
                  <div class="divTableCell" style="width:50px;text-align:center;"> </div>
                  </div>
                  ';

              $pn .= '</div></div>';

              return $pn;

      })
      ->escapeColumns('column_002')
      ->addColumn('column_003', function($row) {
           $Products = DB::select("
              SELECT db_pay_product_test_002.* ,
              ck_users_admin.name as action_user_name
              FROM
              db_pay_product_test_002
              Left Join db_pay_product_test_001 ON db_pay_product_test_002.invoice_code = db_pay_product_test_001.invoice_code
              Left Join ck_users_admin ON db_pay_product_test_001.action_user = ck_users_admin.id
              WHERE db_pay_product_test_002.product_id_fk = '".$row->product_id_fk."' AND db_pay_product_test_002.invoice_code='".$row->invoice_code."'
              ");
           $arr = [];
           foreach ($Products AS $k => $v){
            $x = "<span style='cursor:pointer;' class='invoice_code' data-toggle='tooltip' data-placement='top' title='คลิ้กเพื่อดูรายละเอียด'  data-invoice_code='".$v->invoice_code."' >".$v->invoice_code.' : '.$v->action_user_name."</span>";
              array_push($arr,$x);
           }
           $invoice_code = implode(",",$arr);
           return "<br/>".str_replace(",","<br/>",$invoice_code);
      })
      ->escapeColumns('column_003')
      ->addColumn('column_004', function($row) {
           $Branch = DB::select("
              SELECT *
              FROM
              branchs
              WHERE id = ".$row->branch_id_fk."
              ");
           return "<br/>".$Branch[0]->b_name;
      })
      ->escapeColumns('column_004')
      ->addColumn('column_005', function($row) {
              return "<br/>".$row->action_date;
      })
      ->escapeColumns('column_005')
      ->make(true);
    }




    public function Datatable003(Request $req){


        if(!empty($req->business_location_id_fk)){
           $w01 = " AND db_pay_requisition_002.business_location_id_fk=".$req->business_location_id_fk ;
        }else{
           $w01 = "";
        }

        if(!empty($req->branch_id_fk)){
           $w02 = " AND db_pay_requisition_002.branch_id_fk = ".$req->branch_id_fk." " ;
        }else{
           $w02 = "";
        }

        if(!empty($req->customer_id_fk)){
           $w03 = " AND db_pay_requisition_002.customer_id_fk = ".$req->customer_id_fk." " ;
        }else{
           $w03 = "";
        }

        if(!empty($req->startDate) && !empty($req->endDate)){
           $w04 = " and date(db_pay_requisition_001.action_date) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
        }else{
           $w04 = "";
        }


        if(isset($req->status_sent)){
           $w05 = " and db_pay_requisition_001.status_sent='".$req->status_sent."' " ;
        }else{
           $w05 = "";
        }

      if(isset($req->txtSearch_003)){
           // $w06 = " and db_pick_warehouse_tmp.invoice_code ='".$req->txtSearch."'  " ;
           $w06 = " AND LOWER(db_pay_requisition_002.pick_pack_requisition_code_id_fk) LIKE '%".strtolower($req->txtSearch_003)."%' ";
        }else{
           $w06 = "";
        }

      $sTable = DB::select("
              SELECT
              db_pay_requisition_002.id,
              db_pay_requisition_002.pick_pack_requisition_code_id_fk,
              db_pay_requisition_002.business_location_id_fk,
              db_pay_requisition_002.warehouse_id_fk,
              db_pay_requisition_002.branch_id_fk,
              db_pay_requisition_002.zone_id_fk,
              db_pay_requisition_002.shelf_id_fk,
              db_pay_requisition_002.shelf_floor,
              ( CASE WHEN db_pay_requisition_001.customer_id_fk<>'' THEN db_pay_requisition_001.customer_id_fk ELSE 0 END ) as customer_id_fk,
              ( CASE WHEN db_pay_requisition_001.pay_user<>'' THEN db_pay_requisition_001.pay_user ELSE 0 END ) as pay_user,
              db_pay_requisition_001.action_date,
              ( CASE WHEN db_pay_requisition_001.status_sent<>'' THEN db_pay_requisition_001.status_sent ELSE 0 END) AS status_sent
              FROM
              db_pay_requisition_002
              Join db_pay_requisition_001 ON db_pay_requisition_002.pick_pack_requisition_code_id_fk = db_pay_requisition_001.pick_pack_requisition_code_id_fk

              WHERE 1
              ".$w01."
              ".$w02."
              ".$w03."
              ".$w04."
              ".$w05."
              ".$w06."
              group by db_pay_requisition_002.pick_pack_requisition_code_id_fk

              ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
          return "<b>".@$row->pick_pack_requisition_code_id_fk."</b>";
      })
      ->escapeColumns('column_001')

      ->addColumn('column_002', function($row) {
             $Products = DB::select("
                SELECT x.*,
                  (
                  CASE WHEN (SELECT product_id_fk FROM db_pay_requisition_002 WHERE id=(x.id)-1 AND pick_pack_requisition_code_id_fk=x.pick_pack_requisition_code_id_fk ORDER BY id)=x.product_id_fk THEN 1 ELSE 0 END
                  ) as dup FROM db_pay_requisition_002 as x  WHERE x.pick_pack_requisition_code_id_fk = '".$row->pick_pack_requisition_code_id_fk."'
                  ORDER BY x.pick_pack_requisition_code_id_fk
              ");

              // $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
              $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
              $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
              $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
              // $sWarehouse = @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$row->shelf_floor;
              $sWarehouse = @$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$row->shelf_floor;

            $pn = '<div class="divTable"><div class="divTableBody">';
            // if($Products[0]->id==1){
            $pn .=
                  '<div class="divTableRow">
                  <div class="divTableCell" style="width:200px;font-weight:bold;">Product</div>
                  <div class="divTableCell" style="width:100px;font-weight:bold;">Lot number</div>
                  <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> จำนวน </div>
                  <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วย </div>
                  <div class="divTableCell" style="width:150px;text-align:center;font-weight:bold;"> หยิบจากที่ไหน </div>
                  </div>
                  ';
            // }
            $sum_amt = 0 ;
            foreach ($Products as $key => $value) {
              $sum_amt += $value->amt_lot;
              if($value->dup==0){
                $p_name = sprintf("%04d",@$value->product_id_fk)." <br> ".@$value->product_name;
              }else{
                $p_name = "<span style='opacity: 0;'>".sprintf("%04d",@$value->product_id_fk)." <br> ".@$value->product_name."</span>";
              }
              $pn .=
                  '<div class="divTableRow">
                  <div class="divTableCell" style="width:200px;font-weight:bold;padding-bottom:15px;">'.$p_name.'</div>
                  <div class="divTableCell" style="">'.$value->lot_number.'</div>
                  <div class="divTableCell" style="text-align:center;">'.$value->amt_lot.'</div>
                  <div class="divTableCell" style="text-align:center;">'.$value->product_unit.'</div>
                  <div class="divTableCell" style="text-align:center;">'.$sWarehouse.'</div>
                  </div>
                  ';
             }
              $pn .=
                  '<div class="divTableRow">
                  <div class="divTableCell">  </div>
                  <div class="divTableCell" style="font-weight:bold;"> รวม </div>
                  <div class="divTableCell" style="text-align:center;font-weight:bold;">'.$sum_amt.'</div>
                  <div class="divTableCell" style="text-align:center;"> </div>
                  <div class="divTableCell" style="text-align:center;"> </div>
                  </div>
                  ';

              $pn .= '</div></div>';

              return $pn;

      })
      ->escapeColumns('column_002')

      ->addColumn('column_003', function($row) {
            $rs = DB::select(" select * from customers where id=".@$row->customer_id_fk." ");
            return "<br/>".@$rs[0]->user_name." <br/> ".@$rs[0]->prefix_name.@$rs[0]->first_name." ".@$rs[0]->last_name;
      })
      ->escapeColumns('column_003')
      ->addColumn('column_004', function($row) {
           $Branch = DB::select("
              SELECT *
              FROM
              branchs
              WHERE id = ".$row->branch_id_fk."
              ");
           return "<br/>".$Branch[0]->b_name;
      })
      ->escapeColumns('column_004')
      ->make(true);
    }


    public function Datatable004(Request $req){

      $temp_ppr_001 = "temp_ppr_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders

      if(isset($req->txtSearch)){
         $w001 = " AND $temp_ppr_001.invoice_code='".$req->txtSearch."' ";
      }else{
         $w001 = "";
      }
      $sTable = DB::select(" SELECT * FROM $temp_ppr_001  WHERE 1 ".$w001."  ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
          return "<b>".@$row->invoice_code."</b>";
      })
      ->escapeColumns('column_001')
      ->addColumn('column_002', function($row) {

          $temp_ppr_002 = "temp_ppr_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list
    			$Products = DB::select("
      			SELECT $temp_ppr_002.*,dataset_product_unit.product_unit from $temp_ppr_002 LEFT Join dataset_product_unit ON $temp_ppr_002.product_unit_id_fk = dataset_product_unit.id where $temp_ppr_002.frontstore_id_fk=".$row->id."
      			");

      			$pn = '<div class="divTable"><div class="divTableBody">';
      			$pn .=
      			'<div class="divTableRow">
          <div class="divTableCell" style="width:240px;font-weight:bold;">ชื่อสินค้า</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จ่ายครั้งนี้</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">ค้างจ่าย</div>
          <div class="divTableCell" style="width:450px;text-align:center;font-weight:bold;"> Scan Qr-code </div>
      			</div>
      			';

      			$sum_amt = 0 ;

      	    	foreach ($Products as $key => $value) {
      				$sum_amt += $value->amt;
      				$pn .=
      				'<div class="divTableRow">
      				<div class="divTableCell" style="font-weight:bold;padding-bottom:15px;">'.$value->product_name.'</div>
      				<div class="divTableCell" style="text-align:center;">'.$value->amt.'</div>
      				<div class="divTableCell" style="text-align:center;">'.$value->product_unit.'</div>
      				<div class="divTableCell" style="text-align:left;"> ';

      				$item_id = 1;
      				$amt_scan = $value->amt;

      				 for ($i=0; $i < $amt_scan ; $i++) {

      					$qr = DB::select(" select qr_code,updated_at from db_pick_warehouse_qrcode where item_id='".$item_id."' and invoice_code='".$row->invoice_code."' AND product_id_fk='".$value->product_id_fk."' ");

                            if( (@$qr[0]->updated_at < date("Y-m-d") && !empty(@$qr[0]->qr_code)) ){

                              $pn .=
                               '
                                <input type="text" style="width:122px;" value="'.@$qr[0]->qr_code.'" readonly >
                                <i class="fa fa-times-circle fa-2 " aria-hidden="true" style="color:grey;" ></i>
                               ';

                            }else{

                               $pn .=
                               '
                                <input type="text" class="in-tx qr_scan " data-item_id="'.$item_id.'" data-invoice_code="'.$row->invoice_code.'" data-product_id_fk="'.$value->product_id_fk.'" placeholder="scan qr" style="width:122px;'.(empty(@$qr[0]->qr_code)?"background-color:blanchedalmond;":"").'" value="'.@$qr[0]->qr_code.'" >
                                <i class="fa fa-times-circle fa-2 btnDeleteQrcodeProduct " aria-hidden="true" style="color:red;cursor:pointer;" data-item_id="'.$item_id.'" data-invoice_code="'.$row->invoice_code.'" data-product_id_fk="'.$value->product_id_fk.'" ></i>
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


  public function Datatable006(Request $req){

      $sTable = DB::select(" SELECT * FROM db_pay_product_receipt_001  ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
          return "<b>".@$row->invoice_code."</b>";
      })
      ->escapeColumns('column_001')
      ->addColumn('column_002', function($row) {

            $pn = '<div class="divTable"><div class="divTableBody">';
            $pn .=
            '<div class="divTableRow">
          <div class="divTableCell" style="width:240px;font-weight:bold;">ชื่อสินค้า</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จ่ายครั้งนี้</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">ค้างจ่าย</div>
          <div class="divTableCell" style="width:450px;text-align:center;font-weight:bold;"> Scan Qr-code </div>
            </div>
            ';


              $pn .=
              '<div class="divTableRow">
              <div class="divTableCell" style="font-weight:bold;padding-bottom:15px;"></div>
              <div class="divTableCell" style="text-align:center;"></div>
              <div class="divTableCell" style="text-align:center;"></div>
              <div class="divTableCell" style="text-align:left;"> ';


              $pn .= '</div>';
              $pn .= '</div>';


              $pn .=
              '<div class="divTableRow">
              <div class="divTableCell" style="text-align:right;font-weight:bold;"> รวม </div>
              <div class="divTableCell" style="text-align:center;font-weight:bold;"></div>
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

// เก็บประวัติการเลือก
    public function Datatable007(Request $req){

      if(isset($req->txtSearch)){
         $w001 = " AND db_pay_product_receipt_001.invoice_code='".$req->txtSearch."' ";
      }else{
         $w001 = "";
      }
      $sTable = DB::select(" SELECT * FROM db_pay_product_receipt_001  WHERE 1 ".$w001." group by invoice_code ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
          return "<b>".@$row->invoice_code."</b>";
      })
      ->escapeColumns('column_001')
      ->addColumn('column_002', function($row) {

          $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;

          $Products = DB::select("
            SELECT
              db_pay_product_receipt_002.invoice_code,
              db_pay_product_receipt_002.product_id_fk,
              db_pay_product_receipt_002.product_name,
              db_pay_product_receipt_002.amt_need,
              db_pay_product_receipt_002.amt_get,
              db_pay_product_receipt_002.amt_lot,
              db_pay_product_receipt_002.product_unit FROM db_pay_product_receipt_002
              where db_pay_product_receipt_002.invoice_code='".$row->invoice_code."' group by product_id_fk
            ");

            $pn = '<div class="divTable"><div class="divTableBody">';
            $pn .=
            '<div class="divTableRow">
          <div class="divTableCell" style="width:240px;font-weight:bold;">ชื่อสินค้า</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จ่ายครั้งนี้</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">ค้างจ่าย</div>
          <div class="divTableCell" style="width:450px;text-align:center;font-weight:bold;"> Scan Qr-code </div>
            </div>
            ';

            $sum_amt = 0 ;

              foreach ($Products as $key => $value) {
              $sum_amt += $value->amt_need;
              $pn .=
              '<div class="divTableRow">
              <div class="divTableCell" style="font-weight:bold;padding-bottom:15px;">'.$value->product_name.'</div>
              <div class="divTableCell" style="text-align:center;">'.$value->amt_need.'</div>
              <div class="divTableCell" style="text-align:center;">'.$value->product_unit.'</div>
              <div class="divTableCell" style="text-align:left;"> ';

              $item_id = 1;
              $amt_scan = $value->amt_need;

              if($row->status_sent!=4){

               for ($i=0; $i < $amt_scan ; $i++) {

                $qr = DB::select(" select qr_code,updated_at from db_pick_warehouse_qrcode where item_id='".$item_id."' and invoice_code='".$row->invoice_code."' AND product_id_fk='".$value->product_id_fk."' ");

                            if( (@$qr[0]->updated_at < date("Y-m-d") && !empty(@$qr[0]->qr_code)) ){

                              $pn .=
                               '
                                <input type="text" style="width:122px;" value="'.@$qr[0]->qr_code.'" readonly >
                                <i class="fa fa-times-circle fa-2 " aria-hidden="true" style="color:grey;" ></i>
                               ';

                            }else{

                               $pn .=
                               '
                                <input type="text" class="in-tx qr_scan " data-item_id="'.$item_id.'" data-invoice_code="'.$row->invoice_code.'" data-product_id_fk="'.$value->product_id_fk.'" placeholder="scan qr" style="width:122px;'.(empty(@$qr[0]->qr_code)?"background-color:blanchedalmond;":"").'" value="'.@$qr[0]->qr_code.'" >
                                <i class="fa fa-times-circle fa-2 btnDeleteQrcodeProduct " aria-hidden="true" style="color:red;cursor:pointer;" data-item_id="'.$item_id.'" data-invoice_code="'.$row->invoice_code.'" data-product_id_fk="'.$value->product_id_fk.'" ></i>
                               ';
                            }

                              $item_id++;

                          }

              }else{
                 $pn .= '<center> - ';
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



    public function Datatable008(Request $req){

      $sTable = DB::select(" SELECT * FROM db_pay_product_receipt_001  WHERE  db_pay_product_receipt_001.invoice_code='".$req->txtSearch."' group by time_pay order By time_pay ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
        // group by time_pay order by time_pay asc

            $rs = DB::select(" SELECT
                  db_pay_product_receipt_002_pay_history.id,
                  db_pay_product_receipt_002_pay_history.time_pay,
                  db_pay_product_receipt_002_pay_history.invoice_code,
                  DATE(db_pay_product_receipt_002_pay_history.pay_date) as pay_date,
                  db_pay_product_receipt_002_pay_history.status,
                  ck_users_admin.name as pay_user
                  FROM
                  db_pay_product_receipt_002_pay_history
                  Left Join ck_users_admin ON db_pay_product_receipt_002_pay_history.pay_user = ck_users_admin.id  WHERE invoice_code='".$row->invoice_code."' and time_pay=".$row->time_pay." group by time_pay order By time_pay ");

                        $pn = '<div class="divTable"><div class="divTableBody">';
                        $pn .=
                        '<div class="divTableRow">
                        <div class="divTableCell" style="width:50px;font-weight:bold;">ครั้งที่</div>
                        <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;">วันที่จ่าย</div>
                        <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;"> พนักงาน </div>
                        </div>
                        ';
                  foreach ($rs as $v) {
                        $pn .=
                        '<div class="divTableRow">
                        <div class="divTableCell" style="text-align:center;font-weight:bold;">'.$v->time_pay.'</div>
                        <div class="divTableCell" style="text-align:center;font-weight:bold;">'.$v->pay_date.'</div>
                        <div class="divTableCell" style="text-align:center;font-weight:bold;">'.$v->pay_user.'</div>
                        </div>
                        ';
                  }

            return $pn;

        })
        ->escapeColumns('column_001')
        ->addColumn('column_002', function($row) {

          // $Products = DB::select("
          //    SELECT *,sum(amt_get) as sum_amt_get  from db_pay_product_receipt_002
          //    where db_pay_product_receipt_002.invoice_code='".$row->invoice_code."' and time_pay=1 group by time_pay,product_id_fk order By time_pay
          // ");

          $Products = DB::select(" SELECT
            db_pay_product_receipt_002.*,sum(amt_get) as sum_amt_get
            FROM
            db_pay_product_receipt_002
            WHERE invoice_code='".$row->invoice_code."' and time_pay=".$row->time_pay." group by time_pay,product_id_fk ");

          $pn = '<div class="divTable"><div class="divTableBody">';
          $pn .=
          '<div class="divTableRow">
          <div class="divTableCell" style="width:240px;font-weight:bold;">ชื่อสินค้า</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จ่ายครั้งนี้</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">ค้างจ่าย</div>
          <div class="divTableCell" style="width:450px;text-align:center;font-weight:bold;">

                      <div class="divTableRow">
                      <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">หยิบสินค้าจากคลัง</div>
                      <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;"> Lot number [Expired]</div>
                      <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
                      </div>

          </div>
          </div>
          ';

          $amt_get = 0;

          foreach ($Products as $key => $v) {

                   $css_font = $v->amt_remain>0?"color:red;font-weight:bold;":"";

                     $pn .=
                    '<div class="divTableRow">
                    <div class="divTableCell" style="font-weight:bold;padding-bottom:15px;"> '.$v->product_name.'</div>
                    <div class="divTableCell" style="text-align:center;color:blue;font-weight:bold;"> '.$v->sum_amt_get.'</div>
                    <div class="divTableCell" style="text-align:center;'.$css_font.'"> '.$v->amt_remain.'</div>
                    <div class="divTableCell" style="text-align:center;width:400px;padding-bottom:15px !important;">
                    ';

                      $WH = DB::select("
                         SELECT * from db_pay_product_receipt_002
                         where db_pay_product_receipt_002.invoice_code='".$row->invoice_code."' AND time_pay = ".$v->time_pay." AND product_id_fk=".$v->product_id_fk."
                      ");

                      foreach ($WH AS $v_wh) {

                          $zone = DB::select(" select * from zone where id=".$v_wh->zone_id_fk." ");
                          $shelf = DB::select(" select * from shelf where id=".$v_wh->shelf_id_fk." ");
                          if($v_wh->zone_id_fk!=''){
                            $sWarehouse = @$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.$v_wh->shelf_floor;
                            $lot_number = $v_wh->lot_number.' ['.$v_wh->lot_expired_date.']';
                          }else{
                            $sWarehouse = '<span style="width:200px;text-align:center;color:red;">*** ไม่มีสินค้าในคลัง ***</span>';
                            $lot_number = '';
                          }

                          $pn .=
                              '<div class="divTableRow">
                              <div class="divTableCell" style="width:200px;text-align:center;">'.$sWarehouse.'</div>
                              <div class="divTableCell" style="width:200px;text-align:center;">'.$lot_number.'</div>
                              <div class="divTableCell" style="width:80px;text-align:center;color:blue;font-weight:bold;">'.($v_wh->amt_get>0?$v_wh->amt_get:'').'</div>
                              </div>
                              ';

                     }


            $pn .= '</div>';
            $pn .= '</div>';
          }

          $pn .= '</div>';
          return $pn;
      })
      ->escapeColumns('column_002')
      ->addColumn('ch_amt_remain', function($row) {
        // ดูว่าไม่มีสินค้าค้างจ่ายแล้วใช่หรือไม่
        // Case ที่มีการบันทึกข้อมูลแล้ว
        // '3=สินค้าพอต่อการจ่ายครั้งนี้ 2=สินค้าไม่พอ มีบางรายการค้างจ่าย',

           $rs_pay_history = DB::select(" SELECT id FROM db_pay_product_receipt_002_pay_history WHERE invoice_code='".$row->invoice_code."' AND status in (2) ");

           if(count($rs_pay_history)>0){
               return 2;
           }else{
               return 3;
           }

          // $r_check_remain = DB::select(" SELECT status FROM db_pay_product_receipt_002_pay_history WHERE invoice_code='".$row->invoice_code."' ORDER BY time_pay DESC LIMIT 1  ");
          // if($r_check_remain){
          //     return $r_check_remain[0]->status;
          // }else{
          // // Case ที่ยังไม่มีการบันทึกข้อมูล ก็ให้ส่งค่า 3 ไปก่อน
          //     return 3;
          // }

       })
      ->addColumn('ch_amt_lot_wh', function($row) {
          // ดูว่าไม่มีสินค้าคลังเลย
          // ดูในใบซื้อว่ามีรยการสินค้าใดบ้างที่ยังค้างส่งอยู่
          $Products = DB::select("
            SELECT * from db_pay_product_receipt_002 WHERE invoice_code='".$row->invoice_code."' AND amt_remain > 0 GROUP BY product_id_fk ORDER BY time_pay DESC limit 1 ;
          ");

          // Case ที่มีการบันทึกข้อมูลแล้ว
              if(@$Products){

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

                }else{
                  // Case ที่ยังไม่มีการบันทึกข้อมูล ก็ให้ส่งค่า >0 ไปก่อน
                  return 1;
              }

       })
       ->addColumn('column_003', function($row) {

                $pn = '<div class="divTable">';
                $pn .= '<div class="divTableBody">';

                $pn .=
                '<div class="divTableRow">
                 <div class="divTableCell" style="background-color:white !important;color:white !important;">ยกเลิก</div>
                 </div>
               ';

                $pn .=
                '<div class="divTableRow">
                 <div class="divTableCell" >

                  <a href="javascript: void(0);" class="btn btn-sm btn-danger cDelete2 " data-toggle="tooltip" data-placement="top" title="ยกเลิกรายการจ่ายสินค้าครั้งล่าสุด" data-time_pay='.$row->time_pay.' style="display:none;" ><i class="bx bx-trash font-size-16 align-middle"></i></a>

                 </div>
                 </div>
               ';

              $pn .= '</div>';
              $pn .= '</div>';

             return $pn;

        })
        ->escapeColumns('column_003')
        ->addColumn('column_004', function($row) {
             $r = DB::select("SELECT time_pay from db_pay_product_receipt_002 where status_cancel<>1 ORDER BY id DESC LIMIT 1");
             return @$r[0]->time_pay;
        })
        ->addColumn('status_cancel', function($row) {
            $Products = DB::select(" SELECT status_cancel
            FROM
            db_pay_product_receipt_002
            WHERE invoice_code='".$row->invoice_code."' and time_pay=".$row->time_pay."  group by time_pay ");

            return @$Products[0]->status_cancel;

        })
      ->make(true);
    }


 public function ajaxSavePay_requisition(Request $request)
    {
      // db_delivery db_pick_pack_requisition_code

          $temp_ppp_001 = "temp_ppp_001".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
          $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
          $temp_ppp_003 = "temp_ppp_003".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
          $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
          $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
          $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
          $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id;
// db_pay_requisition_002
          // if(gettype($request->picking_id)=='array'){
          //   $db_pick_pack_packing_code_id = implode(',',$request->picking_id);
          // }else{
          //   $db_pick_pack_packing_code_id = $request->picking_id;
          // }
          $db_pick_pack_packing_code_id = $request->picking_id;

          // return $db_pick_pack_packing_code_id;
          // return $temp_db_pick_pack_requisition_code;

          $r_db_pick_pack_packing = DB::select(" SELECT * FROM $temp_db_pick_pack_requisition_code WHERE id in($db_pick_pack_packing_code_id) ");
          // return $r_db_pick_pack_packing;
          DB::select(" UPDATE db_pick_pack_packing_code SET approver=".(\Auth::user()->id).",aprove_date=now(),status=2 WHERE id in($db_pick_pack_packing_code_id) ");
          DB::select(" UPDATE db_pick_pack_requisition_code SET status=1 WHERE id in($db_pick_pack_packing_code_id) ");
          DB::select(" UPDATE $temp_db_pick_pack_requisition_code SET status=1 WHERE id in($db_pick_pack_packing_code_id) ");
          // return ($db_pick_pack_packing_code_id);
          // if(gettype($request->picking_id)=='array'){
          //     $picking_id = implode(',',$request->picking_id);
          // }else{
          //     $picking_id = explode(' ',$request->picking_id);
          // }

          // $picking_id = explode(", ", $request->picking_id);
          // return $picking_id;

        // หา time+pay ครั้งที่จ่าย
          $rs_time_pay = DB::select(" SELECT * FROM db_pay_requisition_001 WHERE pick_pack_requisition_code_id_fk in ($db_pick_pack_packing_code_id)  order by time_pay DESC limit 1 ");
          // return $rs_time_pay;

          if(count($rs_time_pay)>0){
            $time_pay = $rs_time_pay[0]->time_pay + 1;
          }else{
            $time_pay = 1;
          }
          // return $time_pay;

          $pick_pack_requisition_code_id_fk = DB::select(" SELECT
            $temp_ppp_001.pick_pack_requisition_code_id_fk
            FROM
            $temp_ppp_001
            Inner Join $temp_ppp_002 ON $temp_ppp_002.frontstore_id_fk = $temp_ppp_001.id
            limit 1;");
         @$pick_pack_requisition_code_id_fk = @$pick_pack_requisition_code_id_fk[0]->pick_pack_requisition_code_id_fk;

          // เก็บลงตารางจริง
          // DB::select(" TRUNCATE db_pay_requisition_001 ;");
          // DB::select(" TRUNCATE db_pay_requisition_002 ;");
          // db_pay_requisition_002
          $db_temp_ppp_003 = DB::select(" select * from $temp_ppp_003 ;");

          $data_db_pay_requisition_001 = [];
          foreach ($db_temp_ppp_003 as $key => $value) {
                $data_db_pay_requisition_001 = array(
                    "time_pay" =>  @$time_pay,
                    "business_location_id_fk" =>  @$value->business_location_id_fk,
                    "packing_date" =>  date("Y-m-d",strtotime(@$value->updated_at)),
                    "action_user" =>  @$value->action_user,
                    "branch_id_fk" =>  @$value->branch_id_fk,
                    "pick_pack_requisition_code_id_fk" =>  @$pick_pack_requisition_code_id_fk,
                    "pick_pack_packing_code_id_fk" =>  @$db_pick_pack_packing_code_id,
                    "action_date" =>  @$value->action_date,
                    "pay_user" =>  @$value->pay_user,
                    "pay_date" =>  @$value->pay_date,
                    "status_sent" =>  @$value->status_sent,
                    "address_send" =>  @$value->address_send,
                    "address_send_type" =>  @$value->address_send_type,
                    "created_at" =>  @$value->created_at,
                    );
          }
          if(@$data_db_pay_requisition_001){
              DB::table('db_pay_requisition_001')->insertOrIgnore($data_db_pay_requisition_001);
          }

          $lastInsertId = DB::getPdo()->lastInsertId();
          // return $lastInsertId;

          if($lastInsertId){

               DB::select("
                  INSERT IGNORE INTO db_pick_pack_requisition_code(requisition_code,pick_pack_packing_code_id_fk,pick_pack_packing_code,action_user,receipts,status,status_picked,created_at,updated_at)
                  select requisition_code,pick_pack_packing_code_id_fk,pick_pack_packing_code,action_user,receipts,status,status_picked,created_at,updated_at
                  from $temp_db_pick_pack_requisition_code WHERE id in($db_pick_pack_packing_code_id)

                ");

               // เก็บรายการสินค้าที่จ่าย
                $db_temp_ppp_004 = DB::select(" select * from $temp_ppp_004 ;");

                $data_db_pay_requisition_002 = [];
                // return $db_temp_ppp_004;
                foreach ($db_temp_ppp_004 as $key => $value) {

                      $data_db_pay_requisition_002 = array(
                            "time_pay" =>  @$time_pay,
                            "business_location_id_fk" =>  @$value->business_location_id_fk,
                            "branch_id_fk" =>  @$value->branch_id_fk,
                            "pick_pack_requisition_code_id_fk" => @$pick_pack_requisition_code_id_fk,
                            "product_id_fk" =>  @$value->product_id_fk,
                            "product_name" =>  @$value->product_name,
                            "amt_need" =>  @$value->amt_need,
                            "amt_get" =>  @$value->amt_get,
                            "amt_lot" =>  @$value->amt_lot,
                            "amt_remain" =>  @$value->amt_remain,
                            "product_unit_id_fk" =>  @$value->product_unit_id_fk,
                            "product_unit" =>  @$value->product_unit,
                            "lot_number" =>  @$value->lot_number,
                            "lot_expired_date" =>  @$value->lot_expired_date,
                            "warehouse_id_fk" =>  @$value->warehouse_id_fk,
                            "zone_id_fk" =>  @$value->zone_id_fk,
                            "shelf_id_fk" =>  @$value->shelf_id_fk,
                            "shelf_floor" =>  @$value->shelf_floor,
                            "created_at" =>  @$value->created_at,
                            "updated_at" =>  @$value->updated_at,
                          );

                    if(@$data_db_pay_requisition_002){
                        DB::table('db_pay_requisition_002')->insertOrIgnore($data_db_pay_requisition_002);
                    }
                }



                // วุฒิเพิ่มมา เอาไว้ตรวจว่าสินค้าไหนจ่ายแล้วของบิลไหน
                \App\Helpers\General::create_pay_requisition_002_item(@$pick_pack_requisition_code_id_fk);
    // $db_pay_requisition_002_datas = DB::table('db_pay_requisition_002')
    // ->where('pick_pack_requisition_code_id_fk', @$pick_pack_requisition_code_id_fk)->get();
    // // dd($db_pay_requisition_002_datas);
    // foreach($db_pay_requisition_002_datas as $data_002){
    //               $product_amt_get = $data_002->amt_get;
    //               $db_pick_pack_packing_code_data = DB::table('db_pick_pack_packing_code')->select('id','orders_id_fk')->where('id', @$pick_pack_requisition_code_id_fk)->first();
    //               $arr_order = explode(',',$db_pick_pack_packing_code_data->orders_id_fk);
    //               $promotions_products_arr = DB::table('promotions_products')->select('promotion_id_fk')
    //               ->where('product_id_fk',$data_002->product_id_fk)->pluck('promotion_id_fk')->toArray();
    //               $db_order_products_list_data1 = DB::table('db_order_products_list')
    //               ->select('frontstore_id_fk')
    //               ->whereIn('frontstore_id_fk',$arr_order)
    //               ->where('type_product','product')
    //               ->where('product_id_fk',$data_002->product_id_fk);
    //               $db_order_products_list_data2 = DB::table('db_order_products_list')
    //               ->select('frontstore_id_fk')
    //               ->whereIn('frontstore_id_fk',$arr_order)
    //               ->where('type_product','promotion')
    //               ->whereIn('promotion_id_fk',$promotions_products_arr)
    //               ->union($db_order_products_list_data1)
    //               ->get();
    //               foreach($db_order_products_list_data2 as $data2){
    //                     $order_product1 = DB::table('db_order_products_list')
    //                     ->where('frontstore_id_fk',$data2->frontstore_id_fk)
    //                     ->where('product_id_fk',$data_002->product_id_fk)
    //                     ->where('type_product','product')
    //                     ->get();
    //                     $order_product2 = DB::table('db_order_products_list')
    //                     ->where('frontstore_id_fk',$data2->frontstore_id_fk)
    //                     ->whereIn('promotion_id_fk',$promotions_products_arr)
    //                     ->where('type_product','promotion')
    //                     ->get();
    //                     $sum_want_amt = 0;
    //                     foreach($order_product1 as $order_product1_data){
    //                       $sum_want_amt+=$order_product1_data->amt;
    //                     }

    //                     foreach($order_product2 as $order_product2_data){
    //                       $pro = DB::table('promotions_products')->where('promotion_id_fk',$order_product2_data->promotion_id_fk)->get();
    //                       foreach($pro as $p){
    //                             if($p->product_id_fk == $data_002->product_id_fk){
    //                               $sum_want_amt+=($order_product2_data->amt*$p->product_amt);
    //                             }
    //                       }

    //                     }

    //                     // if($data_002->product_id_fk == 16){
    //                     //   dd($order_product2);
    //                     // }


    //                      $amt_get_data = 0;
    //                      $amt_remain_data = 0;
    //                     // จำนวนที่ต้องการหักจากที่ได้รับ


    //                     if($sum_want_amt <= $product_amt_get){
    //                       $product_amt_get = $product_amt_get - $sum_want_amt;
    //                       $amt_get_data = $sum_want_amt;
    //                       $amt_remain_data = 0;
    //                     }else{
    //                        $amt_get_data = $product_amt_get;
    //                        $amt_remain_data = $sum_want_amt - $product_amt_get;
    //                        $product_amt_get = 0;
    //                     }

    //                         DB::table('db_pay_requisition_002_item')->insert([
    //                           'requisition_002_id' => $data_002->id,
    //                           'order_id' => $data2->frontstore_id_fk,
    //                           'product_id_fk' => $data_002->product_id_fk,
    //                           'amt_need' => $sum_want_amt,
    //                           'amt_get' => $amt_get_data,
    //                           'amt_remain' => $amt_remain_data,
    //                           'created_at' => date('Y:m:d H:i:s'),
    //                           'updated_at' => date('Y:m:d H:i:s'),
    //                           'delete_status' => 2,
    //                         ]);

    //                     //  }

    //               }
    // }

    // // xxxxxxxxxxxx

                DB::select(" INSERT IGNORE INTO  db_pay_requisition_002_pay_history (time_pay,pick_pack_requisition_code_id_fk,pick_pack_packing_code_id_fk,product_id_fk,pay_date,pay_user,amt_need,amt_get,amt_remain) select $time_pay,pick_pack_requisition_code_id_fk,$db_pick_pack_packing_code_id,product_id_fk,now(),".\Auth::user()->id.",amt_need,amt_get,amt_remain FROM  $temp_ppp_004 ");

                // db_pick_pack_packing

              // DB::select(" UPDATE db_pay_requisition_002_pay_history SET status=2 WHERE amt_remain>0 AND pick_pack_requisition_code_id_fk=$pick_pack_requisition_code_id_fk ");
              DB::select(" UPDATE db_pay_requisition_002_pay_history SET status=2 WHERE amt_remain>0 AND pick_pack_packing_code_id_fk=$pick_pack_requisition_code_id_fk ");

              DB::select(" TRUNCATE $temp_db_stocks_compare ;");
              DB::select(" UPDATE db_pay_requisition_001 SET pay_date=now(),pay_user=".\Auth::user()->id." WHERE (id='$lastInsertId') ");

              // เช็คว่ามีสินค้าค้างจ่ายหรือไม่
                  $ch01 =  DB::select(" SELECT * FROM db_pay_requisition_002_pay_history WHERE pick_pack_packing_code_id_fk='".$pick_pack_requisition_code_id_fk."' ORDER BY time_pay DESC, amt_remain DESC LIMIT 1 ");
                  // return $ch01;
                  // db_pay_requisition_001
                  // $ch02 =  DB::select(" SELECT * FROM db_pay_requisition_002_cancel_log WHERE pick_pack_requisition_code_id_fk='".$pick_pack_requisition_code_id_fk."' and status_cancel=1 GROUP BY time_pay ORDER BY time_pay DESC LIMIT 1");
                // return count($ch);
                if(@$ch01[0]->amt_remain>0){

                  // 2=สินค้าไม่พอ มีบางรายการค้างจ่าย,3=สินค้าพอต่อการจ่ายครั้งนี้
                  DB::select(" UPDATE db_pay_requisition_001 SET status_sent=2 WHERE pick_pack_packing_code_id_fk='".$pick_pack_requisition_code_id_fk."' ");
                  // 1=รอเบิก, 2=อนุมัติแล้วรอจัดกล่อง (มีค้างจ่ายบางรายการ), 3=อนุมัติแล้วรอจัดกล่อง (ไม่มีค้างจ่าย), 4=Packing กล่องแล้ว, 5=บ.ขนส่งเข้ามารับสินค้าแล้ว, 6=ยกเลิกใบเบิก
                  DB::select(" UPDATE `db_pick_pack_packing_code` SET `status`=2 WHERE (`id` in (".$pick_pack_requisition_code_id_fk.")  ) ");
                }else{

                  if(!empty($ch01[0]->time_pay)){

                    $ch03 =  DB::select(" SELECT * FROM `db_pay_requisition_002_pay_history` WHERE pick_pack_packing_code_id_fk= ".$pick_pack_requisition_code_id_fk."  AND time_pay in (".$ch01[0]->time_pay.") AND amt_remain > 0 ");

                    if(count($ch03)>0){
                       DB::select(" UPDATE db_pay_requisition_001 SET status_sent=2 WHERE pick_pack_packing_code_id_fk='".$pick_pack_requisition_code_id_fk."' ");
                       DB::select(" UPDATE `db_pick_pack_packing_code` SET `status`=2 WHERE (`id` in (".$pick_pack_requisition_code_id_fk.")  ) ");
                    }else{
                       DB::select(" UPDATE db_pay_requisition_001 SET status_sent=3 WHERE pick_pack_packing_code_id_fk='".$pick_pack_requisition_code_id_fk."' ");
                       DB::select(" UPDATE `db_pick_pack_packing_code` SET `status`=3 WHERE (`id` in (".$pick_pack_requisition_code_id_fk.")  ) ");
                    }

                  }else{
                       DB::select(" UPDATE db_pay_requisition_001 SET status_sent=3 WHERE pick_pack_packing_code_id_fk='".$pick_pack_requisition_code_id_fk."' ");
                       DB::select(" UPDATE `db_pick_pack_packing_code` SET `status`=3 WHERE (`id` in (".$pick_pack_requisition_code_id_fk.")  ) ");
                  }

                }


              // ตัด Stock
              $db_select = DB::select("
                SELECT db_pay_requisition_002.*,db_pick_pack_requisition_code.action_user
                FROM
                db_pay_requisition_002
                left Join db_pick_pack_requisition_code ON db_pay_requisition_002.pick_pack_requisition_code_id_fk = db_pick_pack_requisition_code.id
                WHERE db_pay_requisition_002.pick_pack_requisition_code_id_fk=$pick_pack_requisition_code_id_fk AND db_pay_requisition_002.time_pay=$time_pay
                 ");
              // return $db_select;
              // dd();

              foreach ($db_select as $key => $sRow) {

                     // Check Stock อีกครั้งก่อน เพื่อดูว่าสินค้ายังมีพอให้ตัดหรือไม่
                      // $fnCheckStock = new  AjaxController();
                      //  $r_check_stcok = $fnCheckStock->fnCheckStock(
                      //   $v->branch_id_fk,
                      //   $v->product_id_fk,
                      //   $v->amt_get,
                      //   $v->lot_number,
                      //   $v->lot_expired_date,
                      //   $v->warehouse_id_fk,
                      //   $v->zone_id_fk,
                      //   $v->shelf_id_fk,
                      //   $v->shelf_floor);

                      // // return $r_check_stcok;

                      // if($r_check_stcok==0){
                      //   return redirect()->to(url("backend/pay_product_receipt_001"))->with(['alert'=>\App\Models\Alert::myTxt("สินค้าในคลังไม่เพียงพอ")]);
                      // }
                      $lastID = 0;

                       $_choose=DB::table('db_stocks')
                      ->where('branch_id_fk', $sRow->branch_id_fk)
                      ->where('product_id_fk', $sRow->product_id_fk)
                      ->where('lot_number', $sRow->lot_number)
                      ->where('lot_expired_date', $sRow->lot_expired_date)
                      ->where('warehouse_id_fk', $sRow->warehouse_id_fk)
                      ->where('zone_id_fk', $sRow->zone_id_fk)
                      ->where('shelf_id_fk', $sRow->shelf_id_fk)
                      ->where('shelf_floor', $sRow->shelf_floor)
                      ->get();
                      if($_choose->count() > 0){

                        DB::table('db_stocks')
                            ->where('branch_id_fk', $sRow->branch_id_fk)
                            ->where('product_id_fk', $sRow->product_id_fk)
                            ->where('lot_number', $sRow->lot_number)
                            ->where('lot_expired_date', $sRow->lot_expired_date)
                            ->where('warehouse_id_fk', $sRow->warehouse_id_fk)
                            ->where('zone_id_fk', $sRow->zone_id_fk)
                            ->where('shelf_id_fk', $sRow->shelf_id_fk)
                            ->where('shelf_floor', $sRow->shelf_floor)
                          ->update(array(
                            'amt' => DB::raw( ' amt - '.$sRow->amt_get )
                          ));

                          $lastID = DB::table('db_stocks')->latest()->first();
                          $lastID = $lastID->id;

                      }

                        // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@2

                        /*
                        เปลี่ยนใหม่ นำเข้าเฉพาะรายการที่มีการอนุมัติอันล่าสุดเท่านั้น
                        นำเข้า Stock movement => กรองตาม 4 ฟิลด์ที่สร้างใหม่ stock_type_id_fk,stock_id_fk,ref_table_id,ref_doc
                        1 จ่ายสินค้าตามใบเบิก 26
                        2 จ่ายสินค้าตามใบเสร็จ  27
                        3 รับสินค้าเข้าทั่วไป 28
                        4 รับสินค้าเข้าตาม PO 29
                        5 นำสินค้าออก 30
                        6 สินค้าเบิก-ยืม  31
                        7 โอนภายในสาขา  32
                        8 โอนระหว่างสาขา  33
                        */
                        $stock_type_id_fk = 1 ;
                        $stock_id_fk = $lastID ;
                        $ref_table = 'db_pay_requisition_002' ;
                        $ref_table_id = $sRow->id ;
                        // $ref_doc = $sRow->ref_doc;
                        // $ref_doc = DB::select(" select * from `db_pay_requisition_002` WHERE id=".$sRow->id." ");
                        // dd($ref_doc[0]->ref_doc);
                        // $ref_doc = @$ref_doc[0]->ref_doc;
                        $ref_doc = @$request->packing_code;
                        // $General_takeout = \App\Models\Backend\General_takeout::find($sRow->id);
                        // @$ref_doc = @$General_takeout[0]->ref_doc;

                        $value=DB::table('db_stock_movement')
                        ->where('stock_type_id_fk', @$stock_type_id_fk?$stock_type_id_fk:0 )
                        ->where('stock_id_fk', @$stock_id_fk?$stock_id_fk:0 )
                        ->where('ref_table_id', @$ref_table_id?$ref_table_id:0 )
                        ->where('ref_doc', @$ref_doc?$ref_doc:NULL )
                        ->get();

                        if($value->count() == 0){

                              DB::table('db_stock_movement')->insert(array(
                                  "stock_type_id_fk" =>  @$stock_type_id_fk?$stock_type_id_fk:0,
                                  "stock_id_fk" =>  @$stock_id_fk?$stock_id_fk:0,
                                  "ref_table" =>  @$ref_table?$ref_table:0,
                                  "ref_table_id" =>  @$ref_table_id?$ref_table_id:0,
                                  "ref_doc" =>  @$ref_doc?$ref_doc:NULL,
                                  // "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                                  "doc_date" =>  $sRow->created_at,
                                  "business_location_id_fk" =>  @$sRow->business_location_id_fk?$sRow->business_location_id_fk:0,
                                  "branch_id_fk" =>  @$sRow->branch_id_fk?$sRow->branch_id_fk:0,
                                  "product_id_fk" =>  @$sRow->product_id_fk?$sRow->product_id_fk:0,
                                  "lot_number" =>  @$sRow->lot_number?$sRow->lot_number:NULL,
                                  "lot_expired_date" =>  @$sRow->lot_expired_date?$sRow->lot_expired_date:NULL,
                                  "amt" =>  @$sRow->amt_get?$sRow->amt_get:0,
                                  "in_out" =>  2,
                                  "product_unit_id_fk" =>  @$sRow->product_unit_id_fk?$sRow->product_unit_id_fk:0,

                                  "warehouse_id_fk" =>  @$sRow->warehouse_id_fk?$sRow->warehouse_id_fk:0,
                                  "zone_id_fk" =>  @$sRow->zone_id_fk?$sRow->zone_id_fk:0,
                                  "shelf_id_fk" =>  @$sRow->shelf_id_fk?$sRow->shelf_id_fk:0,
                                  "shelf_floor" =>  @$sRow->shelf_floor?$sRow->shelf_floor:0,

                                  "status" =>  '1',
                                  "note" =>  'จ่ายสินค้าตามใบเบิก ',
                                  "note2" =>  @$sRow->description?$sRow->description:NULL,

                                  "action_user" =>  @$sRow->action_user?$sRow->action_user:NULL,
                                  "action_date" =>  @$sRow->created_at?$sRow->created_at:NULL,
                                  "approver" =>  @\Auth::user()->id,
                                  "approve_date" =>  @$sRow->updated_at?$sRow->updated_at:NULL,

                                  "created_at" =>@$sRow->created_at?$sRow->created_at:NULL
                              ));

                        }
                        // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@


              }




             // อัพเดต สถานะ ด้วย ว่าจ่ายครบแล้ว หรือ ยังค้างอยู่  db_pay_requisition_001
// return "1563";

             $ch_status_cancel = DB::select(" SELECT * FROM db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk=$pick_pack_requisition_code_id_fk AND status_cancel in (0) ");

               // return $ch_status_cancel;

             if(count($ch_status_cancel)==0 || $ch_status_cancel==""){
                DB::select(" UPDATE db_pay_requisition_001 SET status_sent=1 WHERE pick_pack_requisition_code_id_fk=$pick_pack_requisition_code_id_fk ");
             }else{

                    // วุฒิแก้ pick_pack_requisition_code_id_fk เป็น pick_pack_packing_code_id_fk
                    // $rs_pay_history = DB::select(" SELECT status FROM db_pay_requisition_002_pay_history WHERE pick_pack_requisition_code_id_fk=$pick_pack_requisition_code_id_fk AND time_pay=$time_pay AND amt_remain>0  ");
                    $rs_pay_history = DB::select(" SELECT status FROM db_pay_requisition_002_pay_history WHERE pick_pack_packing_code_id_fk=$pick_pack_requisition_code_id_fk AND time_pay=$time_pay AND amt_remain>0  ");
                    if(count($rs_pay_history)>0){
                      // DB::select(" UPDATE db_pay_requisition_001 SET status_sent=2 WHERE pick_pack_requisition_code_id_fk=$pick_pack_requisition_code_id_fk  ");
                      DB::select(" UPDATE db_pay_requisition_001 SET status_sent=2 WHERE pick_pack_packing_code_id_fk=$pick_pack_requisition_code_id_fk  ");
                    }else{
                      // DB::select(" UPDATE db_pay_requisition_001 SET status_sent=3 WHERE pick_pack_requisition_code_id_fk=$pick_pack_requisition_code_id_fk  ");
                      DB::select(" UPDATE db_pay_requisition_001 SET status_sent=3 WHERE pick_pack_packing_code_id_fk=$pick_pack_requisition_code_id_fk  ");
                    }
             }
          }

          // วุฒิพิ่มมาอัพเดทสถานะบิล
          $arr1 = explode(',',$pick_pack_requisition_code_id_fk);
          $db_pick_pack_packing_code = DB::table('db_pick_pack_packing_code')->whereIn('id',$arr1)->get();
          foreach($db_pick_pack_packing_code as $data){
              $arr = explode(',',$data->orders_id_fk);
              // $oder = DB::table('db_orders')->select('db_delivery.id')
              DB::table('db_delivery')->whereIn('orders_id_fk',$arr)->update([
                'status_tracking' => 2,
              ]);
          }

          return $lastInsertId ;
    }



 public function ajaxSavePay_requisition_edit(Request $request)
    {
      // return $request->txtSearch; db_pay_requisition_002
          $temp_ppp_001 = "temp_ppp_001".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
          $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
          $temp_ppp_003 = "temp_ppp_003".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
          $temp_ppp_004 = "temp_ppp_004".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
          $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
          $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
          $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id;

          $requisition_code = $request->requisition_code;
          $pick_pack_packing_code_id_fk = $request->pick_pack_packing_code_id_fk;

        // หา time+pay ครั้งที่จ่าย
          $rs_time_pay = DB::select(" SELECT * FROM db_pay_requisition_001 WHERE pick_pack_requisition_code_id_fk in ($requisition_code)  order by time_pay DESC limit 1 ");
          // return $rs_time_pay;
          // dd();

          if(count($rs_time_pay)>0){
            $time_pay = $rs_time_pay[0]->time_pay + 1;
          }else{
            $time_pay = 1;
          }

          // return $time_pay;
          // dd();

         //  $pick_pack_requisition_code_id_fk = DB::select(" SELECT
         //    $temp_ppp_001.pick_pack_requisition_code_id_fk
         //    FROM
         //    $temp_ppp_001
         //    Inner Join $temp_ppp_002 ON $temp_ppp_002.frontstore_id_fk = $temp_ppp_001.id
         //    limit 1;");

         // @$pick_pack_requisition_code_id_fk = @$pick_pack_requisition_code_id_fk[0]->pick_pack_requisition_code_id_fk;

// เก็บลงตารางจริง

          // DB::select(" TRUNCATE db_pay_requisition_001 ;");
          // DB::select(" TRUNCATE db_pay_requisition_002 ;");

          $db_temp_ppp_003 = DB::select(" select * from $temp_ppp_003 ;");
          $data_db_pay_requisition_001 = [];
          foreach ($db_temp_ppp_003 as $key => $value) {
                $data_db_pay_requisition_001 = array(
                    "time_pay" =>  @$time_pay,
                    "business_location_id_fk" =>  @$value->business_location_id_fk,
                    "packing_date" =>  date("Y-m-d",strtotime(@$value->updated_at)),
                    "action_user" =>  @$value->action_user,
                    "branch_id_fk" =>  @$value->branch_id_fk,
                    "pick_pack_requisition_code_id_fk" =>  @$requisition_code,
                    "pick_pack_packing_code_id_fk" =>  @$pick_pack_packing_code_id_fk,
                    "action_date" =>  @$value->action_date,
                    "pay_user" =>  @$value->pay_user,
                    "pay_date" =>  @$value->pay_date,
                    "status_sent" =>  @$value->status_sent,
                    "address_send" =>  @$value->address_send,
                    "address_send_type" =>  @$value->address_send_type,
                    "created_at" =>  @$value->created_at,
                    );
          }
          if(@$data_db_pay_requisition_001){
              DB::table('db_pay_requisition_001')->insertOrIgnore($data_db_pay_requisition_001);
          }

          $lastInsertId = DB::getPdo()->lastInsertId();
          // return $lastInsertId;
          // dd();

          if($lastInsertId){

               DB::select("
                  INSERT IGNORE INTO db_pick_pack_requisition_code(requisition_code,pick_pack_packing_code_id_fk,pick_pack_packing_code,action_user,receipts,status,status_picked,created_at,updated_at)
                  select requisition_code,pick_pack_packing_code_id_fk,@$pick_pack_packing_code_id_fk,action_user,receipts,status,status_picked,created_at,updated_at
                  from $temp_db_pick_pack_requisition_code

                ");

               // เก็บรายการสินค้าที่จ่าย
                $db_temp_ppp_004 = DB::select(" select * from $temp_ppp_004 ;");
                $data_db_pay_requisition_002 = [];


                foreach ($db_temp_ppp_004 as $key => $value) {

                      $data_db_pay_requisition_002 = array(
                            "time_pay" =>  @$time_pay,
                            "business_location_id_fk" =>  @$value->business_location_id_fk,
                            "branch_id_fk" =>  @$value->branch_id_fk,
                            "pick_pack_requisition_code_id_fk" => @$requisition_code,
                            "product_id_fk" =>  @$value->product_id_fk,
                            "product_name" =>  @$value->product_name,
                            "amt_need" =>  @$value->amt_need,
                            "amt_get" =>  @$value->amt_get,
                            "amt_lot" =>  @$value->amt_lot,
                            "amt_remain" =>  @$value->amt_remain,
                            "product_unit_id_fk" =>  @$value->product_unit_id_fk,
                            "product_unit" =>  @$value->product_unit,
                            "lot_number" =>  @$value->lot_number,
                            "lot_expired_date" =>  @$value->lot_expired_date,
                            "warehouse_id_fk" =>  @$value->warehouse_id_fk,
                            "zone_id_fk" =>  @$value->zone_id_fk,
                            "shelf_id_fk" =>  @$value->shelf_id_fk,
                            "shelf_floor" =>  @$value->shelf_floor,
                            "created_at" =>  @$value->created_at,
                            "updated_at" =>  @$value->updated_at,
                          );

                    if(@$data_db_pay_requisition_002){
                        DB::table('db_pay_requisition_002')->insertOrIgnore($data_db_pay_requisition_002);
                    }

                }

                 // วุฒิเพิ่มมา เอาไว้ตรวจว่าสินค้าไหนจ่ายแล้วของบิลไหน
                 \App\Helpers\General::create_pay_requisition_002_item(@$requisition_code);

                DB::select(" INSERT IGNORE INTO  db_pay_requisition_002_pay_history (time_pay,pick_pack_requisition_code_id_fk,pick_pack_packing_code_id_fk,product_id_fk,pay_date,pay_user,amt_need,amt_get,amt_remain) select $time_pay,pick_pack_requisition_code_id_fk,$pick_pack_packing_code_id_fk,product_id_fk,now(),".\Auth::user()->id.",amt_need,amt_get,amt_remain FROM  $temp_ppp_004 where pick_pack_requisition_code_id_fk=$requisition_code ");


                DB::select(" UPDATE db_pay_requisition_002_pay_history SET status=2 WHERE amt_remain>0 AND pick_pack_requisition_code_id_fk=$requisition_code ");


                $rs_pay_history = DB::select(" SELECT * FROM db_pay_requisition_002_pay_history WHERE pick_pack_requisition_code_id_fk=$requisition_code AND status in(2) ");
                if(count($rs_pay_history) > 0){

                      DB::table('db_pay_requisition_001')
                      ->where('pick_pack_requisition_code_id_fk', $requisition_code)
                      ->update(array(
                        'status_sent' => 2 ,
                      ));

                }else{
                  DB::table('db_pay_requisition_001')
                      ->where('pick_pack_requisition_code_id_fk', $requisition_code)
                      ->update(array(
                        'status_sent' => 3 ,
                      ));
                }



              DB::select(" TRUNCATE $temp_db_stocks_compare ;");

              DB::select(" UPDATE db_pay_requisition_001 SET pay_date=now(),pay_user=".\Auth::user()->id." WHERE (id='$lastInsertId') ");


               // เช็คว่ามีสินค้าค้างจ่ายหรือไม่
              // วุฒิเปลี่ยน pick_pack_requisition_code_id_fk เป็น pick_pack_packing_code_id_fk
                  // $ch01 =  DB::select(" SELECT * FROM db_pay_requisition_002_pay_history WHERE pick_pack_requisition_code_id_fk='".$requisition_code."' ORDER BY time_pay DESC LIMIT 1 ");
                  $ch01 =  DB::select(" SELECT * FROM db_pay_requisition_002_pay_history WHERE pick_pack_packing_code_id_fk='".$requisition_code."' ORDER BY time_pay DESC LIMIT 1 ");
                  // $ch02 =  DB::select(" SELECT * FROM db_pay_requisition_002_cancel_log WHERE pick_pack_requisition_code_id_fk='".$pick_pack_requisition_code_id_fk."' and status_cancel=1 GROUP BY time_pay ORDER BY time_pay DESC LIMIT 1");

                // return count($ch);
                if(@$ch01[0]->amt_remain>0){
                  // 2=สินค้าไม่พอ มีบางรายการค้างจ่าย,3=สินค้าพอต่อการจ่ายครั้งนี้
                  DB::select(" UPDATE db_pay_requisition_001 SET status_sent=2 WHERE pick_pack_requisition_code_id_fk='".$requisition_code."' ");
                  // 1=รอเบิก, 2=อนุมัติแล้วรอจัดกล่อง (มีค้างจ่ายบางรายการ), 3=อนุมัติแล้วรอจัดกล่อง (ไม่มีค้างจ่าย), 4=Packing กล่องแล้ว, 5=บ.ขนส่งเข้ามารับสินค้าแล้ว, 6=ยกเลิกใบเบิก
                  DB::select(" UPDATE `db_pick_pack_packing_code` SET `status`=2 WHERE (`id` in (".$requisition_code.")  ) ");
                }else{


                   if(!empty($ch01[0]->time_pay)){

                    // วุฒิเปลี่ยน pick_pack_requisition_code_id_fk เป็น pick_pack_packing_code_id_fk
                        // $ch03 =  DB::select(" SELECT * FROM `db_pay_requisition_002_pay_history` WHERE pick_pack_requisition_code_id_fk= ".$requisition_code." AND time_pay in (".$ch01[0]->time_pay.") AND amt_remain > 0 ");
                        $ch03 =  DB::select(" SELECT * FROM `db_pay_requisition_002_pay_history` WHERE pick_pack_packing_code_id_fk= ".$requisition_code." AND time_pay in (".$ch01[0]->time_pay.") AND amt_remain > 0 ");

                        if(count($ch03)>0){
                           DB::select(" UPDATE db_pay_requisition_001 SET status_sent=2 WHERE pick_pack_requisition_code_id_fk='".$requisition_code."' ");
                           DB::select(" UPDATE `db_pick_pack_packing_code` SET `status`=2 WHERE (`id` in (".$requisition_code.")  ) ");
                        }else{
                           DB::select(" UPDATE db_pay_requisition_001 SET status_sent=3 WHERE pick_pack_requisition_code_id_fk='".$requisition_code."' ");
                           DB::select(" UPDATE `db_pick_pack_packing_code` SET `status`=3 WHERE (`id` in (".$requisition_code.")  ) ");
                        }
                   }

                }


              // ตัด Stock
              $db_select = DB::select("
                SELECT * FROM  db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk=$requisition_code AND time_pay=$time_pay
                 ");



              foreach ($db_select as $key => $v) {

                      $wh_arr = DB::table('warehouse')->where('w_code','WH02')->select('id')->where('status',1)->pluck('id')->toArray();

                       $_choose=DB::table('db_stocks')
                      ->where('product_id_fk', $v->product_id_fk)
                      ->where('lot_number', $v->lot_number)
                      ->where('lot_expired_date', $v->lot_expired_date)
                      ->whereIn('warehouse_id_fk',$wh_arr)
                      ->get();
                      if($_choose->count() > 0){
                        if($v->amt_get<=$_choose[0]->amt){
                          DB::select(" UPDATE db_stocks SET amt=amt-(".$v->amt_get.") WHERE product_id_fk='".$v->product_id_fk."' and lot_number='".$v->lot_number."' and lot_expired_date='".$v->lot_expired_date."'

                            and business_location_id_fk='".$v->business_location_id_fk."'
                            and branch_id_fk='".$v->branch_id_fk."'
                            and warehouse_id_fk='".$v->warehouse_id_fk."'
                            and zone_id_fk='".$v->zone_id_fk."'
                            and shelf_id_fk='".$v->shelf_id_fk."'
                            and shelf_floor='".$v->shelf_floor."'

                            ");

                        }else{
                          DB::select(" UPDATE db_stocks SET amt=0 WHERE product_id_fk='".$v->product_id_fk."' and lot_number='".$v->lot_number."' and lot_expired_date='".$v->lot_expired_date."'

                            and business_location_id_fk='".$v->business_location_id_fk."'
                            and branch_id_fk='".$v->branch_id_fk."'
                            and warehouse_id_fk='".$v->warehouse_id_fk."'
                            and zone_id_fk='".$v->zone_id_fk."'
                            and shelf_id_fk='".$v->shelf_id_fk."'
                            and shelf_floor='".$v->shelf_floor."'

                            ");

                        }
                      }

              }

             // อัพเดต สถานะ ด้วย ว่าจ่ายครบแล้ว หรือ ยังค้างอยู่  db_pay_requisition_001


             $ch_status_cancel = DB::select(" SELECT * FROM db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk=$requisition_code AND status_cancel in (0) ");
// return "OK0";
                // dd();

               // return $ch_status_cancel;

             if(count($ch_status_cancel)==0 || $ch_status_cancel==""){
                DB::select(" UPDATE db_pay_requisition_001 SET status_sent=1 WHERE pick_pack_requisition_code_id_fk=$requisition_code ");

                // return "OK1";
                // dd();

             }else{

                 // return "OK2";
                // dd();
                  // วุฒิเปลี่ยน pick_pack_requisition_code_id_fk เป็น pick_pack_packing_code_id_fk
                    $rs_pay_history = DB::select(" SELECT status FROM db_pay_requisition_002_pay_history WHERE pick_pack_packing_code_id_fk=$requisition_code AND time_pay=$time_pay AND amt_remain>0  ");

                      // return "OK3";
                      // dd();


                    if(count($rs_pay_history)>0){
                      DB::select(" UPDATE db_pay_requisition_001 SET status_sent=2 WHERE pick_pack_requisition_code_id_fk=$requisition_code  ");

                       // return "OK4";
                      // dd();


                    }else{

                      DB::select(" UPDATE db_pay_requisition_001 SET status_sent=3 WHERE pick_pack_requisition_code_id_fk=$requisition_code  ");

                       // return "OK5";
                      // dd();


                    }



             }


          }

          // return "check_product_instock= ".@$_SESSION['check_product_instock'] ;
          return $lastInsertId ;


    }


}
