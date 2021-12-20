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

class Pay_product_receipt_001Controller extends Controller
{

    public function index(Request $request)
    {
      // dd($request->all());
       // $sPermission = \Auth::user()->permission ;
       // dd($sPermission);

      // $menu_permit = DB::select(" select * from role_permit where role_group_id_fk=2 AND menu_id_fk=34 ");
      // dd($menu_permit);

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
      $sPay_product_status = \App\Models\Backend\Pay_product_status::get();

      $sAdmin = DB::select(" select * from ck_users_admin where isActive='Y' AND branch_id_fk=".\Auth::user()->branch_id_fk." ");

        return View('backend.pay_product_receipt_001.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,
           'sBranchs'=>$sBranchs,
           'customer'=>$customer,
           'sPay_product_status'=>$sPay_product_status,
           'sAdmin'=>$sAdmin,
        ) );
    }


    public function create()
    {
      return View('backend.pay_product_receipt_001.form');
    }


    public function store(Request $request)
    {
        // dd($request->all());
        return $this->form();
    }

    public function edit($id)
    {
      return View('backend.pay_product_receipt_001.form');
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

      DB::beginTransaction();
      try
      {
      // return $request->id;
      // return $request->invoice_code;
      // สถานะกลับไปเป็นรอจ่าย 1 ถ้าเป็นกรณียกเลิกใบเสร็จ 4 ให้เกิดจากการยกเลิกจากคลัง
      $r01 = DB::select(" SELECT * FROM db_pay_product_receipt_001 WHERE invoice_code='".$request->invoice_code."'; ");
      DB::update(" UPDATE `db_orders` SET `approve_status`='2' WHERE (`id`='".$r01[0]->orders_id_fk."') ");

      DB::select("
        UPDATE db_pay_product_receipt_001
        SET
        action_user=".(\Auth::user()->id)." ,
        action_date=now() ,
        status_sent=1
        WHERE invoice_code='".$request->invoice_code."' ");
        // dd($request->invoice_code);

      DB::update(" UPDATE db_pay_product_receipt_002 SET status_cancel=1 WHERE invoice_code='".$request->invoice_code."' ");
      DB::update(" DELETE FROM db_pick_warehouse_qrcode WHERE invoice_code='".$request->invoice_code."' ");

      // คืนสินค้ากลับ คลัง เพราะไม่ได้จ่ายแล้ว
      // DB::select(" TRUNCATE db_stocks_return; ");
      // $r = DB::table('db_pay_product_receipt_002')->where('invoice_code',$request->invoice_code)->orderBy('time_pay','desc')->limit(1)->get();
// dd($r);
      // foreach ($r as $key => $v) {
            //  $_choose=DB::table("db_stocks_return")
            //     ->where('time_pay', $v->time_pay)
            //     ->where('business_location_id_fk', $v->business_location_id_fk)
            //     ->where('branch_id_fk', $v->branch_id_fk)
            //     ->where('product_id_fk', $v->product_id_fk)
            //     ->where('lot_number', $v->lot_number)
            //     ->where('lot_expired_date', $v->lot_expired_date)
            //     ->where('amt', $v->amt_get)
            //     ->where('product_unit_id_fk', $v->product_unit_id_fk)
            //     ->where('warehouse_id_fk', $v->warehouse_id_fk)
            //     ->where('zone_id_fk', $v->zone_id_fk)
            //     ->where('shelf_id_fk', $v->shelf_id_fk)
            //     ->where('shelf_floor', $v->shelf_floor)
            //     ->where('invoice_code', $request->invoice_code)
            //     ->get();

                // if($_choose->count() == 0){

                    DB::select(" INSERT INTO db_stocks_return (time_pay, business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk,shelf_floor, invoice_code, created_at, updated_at,status_cancel)
                    SELECT time_pay,
                    business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt_get, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk, shelf_floor,invoice_code, created_at,now(),0
                    FROM db_pay_product_receipt_002 WHERE time_pay not in(select time_pay from db_stocks_return where time_pay=db_pay_product_receipt_002.time_pay and business_location_id_fk=db_pay_product_receipt_002.business_location_id_fk AND branch_id_fk=db_pay_product_receipt_002.branch_id_fk AND product_id_fk=db_pay_product_receipt_002.product_id_fk AND lot_number=db_pay_product_receipt_002.lot_number AND lot_expired_date=db_pay_product_receipt_002.lot_expired_date AND amt=db_pay_product_receipt_002.amt_get AND product_unit_id_fk=db_pay_product_receipt_002.product_unit_id_fk AND warehouse_id_fk=db_pay_product_receipt_002.warehouse_id_fk AND zone_id_fk=db_pay_product_receipt_002.zone_id_fk AND shelf_id_fk=db_pay_product_receipt_002.shelf_id_fk AND shelf_floor=db_pay_product_receipt_002.shelf_floor AND invoice_code=db_pay_product_receipt_002.invoice_code) ; ");




                            $insertStockMovement = new  AjaxController();

                              // รับคืนจากการยกเลิกใบสั่งซื้อ db_stocks_return
                              // วุฒิเพิ่ม  AND db_stocks_return.invoice_code
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
                                      AND db_stocks_return.invoice_code = '".$request->invoice_code."'

                                ");
                                    // wut แก้
                              // $Data = DB::table('db_stocks_return')->select(
                              //   'db_stocks_return.business_location_id_fk',
                              //   'db_stocks_return.invoice_code as doc_no',
                              //   'db_stocks_return.updated_at as doc_date',
                              //   'db_stocks_return.branch_id_fk',
                              //   'product_id_fk',
                              //   'lot_number',
                              //   'lot_expired_date',
                              //   'amt',
                              //   'product_unit_id_fk',
                              //   'warehouse_id_fk',
                              //   'zone_id_fk',
                              //   'shelf_id_fk',
                              //   'shelf_floor',
                              //   'warehouse_id_fk',
                              //   'db_stocks_return.status_cancel as status',
                              //   )
                              //   ->where('invoice_code',$request->invoice_code)
                              //   ->where('status_cancel',1)
                              //   ->get();

                              if(!$Data){
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
                                WHERE db_stocks_return.invoice_code = '".$request->invoice_code."'
                          ");
                              // dd($Data);
                         }
                            // dd($Data);

                              if(@$Data){
                                DB::table('db_stock_movement_tmp')->truncate();
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
                                          // "in_out" =>  @$value->in_out?$value->in_out:0,
                                          "in_out" =>  1,
                                          "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                                          "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                                          "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                                          "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                                          "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                                          "status" =>  @$value->status?$value->status:0,
                                          "note" =>  @$value->note?$value->note:NULL,
                                          "action_user" =>  NULL,
                                          // "action_user" =>  @$value->action_user?$value->action_user:NULL,
                                          "action_date" =>  @$value->action_date?$value->action_date:NULL,
                                          "approver" =>  @$value->approver?$value->approver:NULL,
                                          "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                                          "created_at" =>@$value->dd?$value->dd:NULL
                                      );

                                        $insertStockMovement->insertStockMovement($insertData);

                                    }
                                    $tmp = DB::table('db_stock_movement_tmp')->where('doc_no',$request->invoice_code)->orderBy('updated_at','desc')->groupBy('product_id_fk')->get();
                                    foreach($tmp as $t){
                                      DB::table('db_stock_movement')->insertOrignore(array(
                                        // ไม่เหมือน tmp
                                        "stock_type_id_fk" =>  0,
                                        "stock_id_fk" =>  0,
                                        "ref_table" =>  0,
                                        "ref_table_id" =>  0,
                                        "ref_doc" =>  $t->doc_no,
                                        //
                                        'doc_no' => $t->doc_no,
                                        "doc_date" =>  $t->doc_date,
                                        "business_location_id_fk" =>  $t->business_location_id_fk,
                                        "branch_id_fk" =>  $t->branch_id_fk,
                                        "product_id_fk" =>  $t->product_id_fk,
                                        "lot_number" =>  $t->lot_number,
                                        "lot_expired_date" =>  $t->lot_expired_date,
                                        "amt" => $t->amt,
                                        "in_out" =>  $t->in_out,
                                        "product_unit_id_fk" => $t->product_unit_id_fk,
                                        "warehouse_id_fk" =>  $t->warehouse_id_fk,
                                        "zone_id_fk" =>  $t->zone_id_fk,
                                        "shelf_id_fk" => $t->shelf_id_fk,
                                        "shelf_floor" =>  $t->shelf_floor,
                                        "status" => $t->status,
                                        "note" =>  $t->note,
                                        "note2" => $t->note2,
                                        "action_user" => \Auth::user()->id,
                                        "action_date" =>  $t->updated_at,
                                        "approver" =>  \Auth::user()->id,
                                        "approve_date" =>  $t->updated_at,
                                        "created_at" =>$t->created_at,
                                        'sender' => $t->sender,
                                        'sent_date' => $t->sent_date,
                                        'sender' => $t->who_cancel,
                                        'cancel_date' => $t->cancel_date,
                                    ));
                                    }
                                    // DB::select(" INSERT IGNORE INTO db_stock_movement SELECT * FROM db_stock_movement_tmp ORDER BY doc_date asc ");

                               }


                // }

      //  }

      // สร้างตาราง temp group ตามรายการข้างล่าง
      $temp_db_stocks_from_return = "temp_db_stocks_from_return".\Auth::user()->id;
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_from_return ; ");
      DB::select(" CREATE TABLE $temp_db_stocks_from_return Like db_stocks_return ; ");
      // DB::select(" INSERT INTO $temp_db_stocks_from_return (business_location_id_fk,branch_id_fk,product_id_fk,lot_number,lot_expired_date,amt,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,invoice_code)

      //     select
      //     business_location_id_fk,branch_id_fk,product_id_fk,lot_number,lot_expired_date,sum(amt) as amt,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,invoice_code
      //     FROM db_stocks_return
      //     WHERE
      //     status_cancel=0

      //     GROUP BY
      //     business_location_id_fk,branch_id_fk,product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor

      //    ; ");
      // วุฒิแก้ ไม่ sum(amt) as amt
      DB::select(" INSERT INTO $temp_db_stocks_from_return (business_location_id_fk,branch_id_fk,product_id_fk,lot_number,lot_expired_date,amt,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,invoice_code)

      select
      business_location_id_fk,branch_id_fk,product_id_fk,lot_number,lot_expired_date,amt,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,invoice_code
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
               DB::commit();
              // DB::rollback();
              }
              catch (\Exception $e) {
                  DB::rollback();
              return $e->getMessage();
              }
              catch(\FatalThrowableError $fe)
              {
                  DB::rollback();
              return $e->getMessage();
              }
    }

    public function destroy_some(Request $request)
    {

      DB::update(" UPDATE db_pay_product_receipt_002 SET status_cancel=1 WHERE invoice_code='".$request->invoice_code."' AND time_pay='".$request->time_pay."' ");
      DB::update(" UPDATE `db_orders` SET `approve_status`='2' , `order_status_id_fk`='6' WHERE (`code_order`='".$request->invoice_code."') ");
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

                          DB::select(" INSERT IGNORE INTO `db_pay_product_receipt_002_cancel_log` (`time_pay`, `business_location_id_fk`, `branch_id_fk`, `orders_id_fk`, `customers_id_fk`, `invoice_code`, `product_id_fk`, `product_name`, `amt_need`, `amt_get`, `amt_lot`, `amt_remain`, `product_unit_id_fk`, `product_unit`, `lot_number`, `lot_expired_date`, `warehouse_id_fk`, `zone_id_fk`, `shelf_id_fk`, `shelf_floor`, `status_cancel`, `created_at`)
                            VALUES
                           (".$v->time_pay.", ".$v->business_location_id_fk.", ".$v->branch_id_fk.", ".$v->orders_id_fk.", ".$v->customers_id_fk.", '".$v->invoice_code."', ".$v->product_id_fk.", '".$v->product_name."', ".$v->amt_get.", 0 , 0, ".$v->amt_get.", ".$v->product_unit_id_fk.", '".$v->product_unit."', '".$v->lot_number."', '".$v->lot_expired_date."', ".$v->warehouse_id_fk.", ".$v->zone_id_fk.", ".$v->shelf_id_fk.", ".$v->shelf_floor.", ".$v->status_cancel.", '".$v->created_at."') ");

                   }


           }


             $ch_status_cancel = DB::select(" SELECT * FROM `db_pay_product_receipt_002` WHERE invoice_code='".$request->invoice_code."' AND status_cancel in (0) ");
             if(count(@$ch_status_cancel)==0 || empty(@$ch_status_cancel)){
                DB::select(" UPDATE `db_pay_product_receipt_001` SET status_sent=1 WHERE invoice_code='".$request->invoice_code."' ");
             }



    }


    public function ajaxCHECKPay_product_receipt(Request $request)
    {
      // temp_db_stocks
         $r =  DB::select(" select * from db_pay_product_receipt_001 WHERE invoice_code='".$request->txtSearch."' ");
         if($r){
            return  $r[0]->id;
         }else{
            return 0;
         }
    }

    public function ajaxSavePay_product_receipt(Request $request)
    {

          $temp_ppr_003 = "temp_ppr_003".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
          $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
          $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
          $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
          // $temp_ppr_005 = "temp_ppr_005".\Auth::user()->id;
          $invoice_code = $request->txtSearch;

          DB::beginTransaction();
          try
          {

        // หา time+pay ครั้งที่จ่าย
          $rs_time_pay = DB::select(" SELECT * FROM `db_pay_product_receipt_001` WHERE invoice_code='$invoice_code' order by time_pay DESC limit 1 ");
          if(count($rs_time_pay)>0){
            $time_pay = $rs_time_pay[0]->time_pay + 1;
          }else{
            $time_pay = 1;
          }

        // เก็บลงตารางจริง
          $db_temp_ppr_003 = DB::select(" select * from $temp_ppr_003 ;");

          $data_db_pay_product_receipt_001 = [];
          foreach ($db_temp_ppr_003 as $key => $value) {
                $data_db_pay_product_receipt_001 = array(
                    "time_pay" =>  @$time_pay,
                    "orders_id_fk" =>  @$value->orders_id_fk,
                    "business_location_id_fk" =>  @$value->business_location_id_fk,
                    "branch_id_fk" =>  @$value->branch_id_fk,
                    "branch_id_fk_tosent" =>  @$value->branch_id_fk_tosent,
                    "invoice_code" =>  @$value->invoice_code,
                    "bill_date" =>  @$value->bill_date,
                    "action_user" =>  @$value->action_user,
                    "action_date" =>  @$value->action_date,
                    "pay_user" =>  @$value->pay_user,
                    "pay_date" =>  @$value->pay_date,
                    "status_sent" =>  @$value->status_sent,
                    "customer_id_fk" =>  @$value->customer_id_fk,
                    "address_send" =>  @$value->address_send,
                    "address_send_type" =>  @$value->address_send_type,
                    "created_at" =>  @$value->created_at,
                    );
          }

          if(@$data_db_pay_product_receipt_001){
              DB::table('db_pay_product_receipt_001')->insertOrIgnore($data_db_pay_product_receipt_001);
          }

          $lastInsertId = DB::getPdo()->lastInsertId();

          if($lastInsertId){

               // เก็บรายการสินค้าที่จ่าย
                $db_temp_ppr_004 = DB::select(" select * from $temp_ppr_004 ;");
                // dd($db_temp_ppr_004);
                $data_db_pay_product_receipt_002 = [];
                foreach ($db_temp_ppr_004 as $key => $value) {
                      $data_db_pay_product_receipt_002 = array(
                            "time_pay" =>  @$time_pay,
                            "business_location_id_fk" =>  @$value->business_location_id_fk,
                            "branch_id_fk" =>  @$value->branch_id_fk,
                            "orders_id_fk" =>  @$value->orders_id_fk,
                            "customers_id_fk" =>  @$value->customers_id_fk,
                            "invoice_code" =>  @$value->invoice_code,
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

                    if(@$data_db_pay_product_receipt_002){
                        DB::table('db_pay_product_receipt_002')->insertOrIgnore($data_db_pay_product_receipt_002);
                    }

                }


                DB::select(" INSERT IGNORE INTO  db_pay_product_receipt_002_pay_history (time_pay,invoice_code,product_id_fk,pay_date,pay_user,amt_need,amt_get,amt_remain) select $time_pay,invoice_code,product_id_fk,now(),".\Auth::user()->id.",amt_need,amt_get,amt_remain FROM  $temp_ppr_004 ");


                DB::select(" UPDATE `db_pay_product_receipt_002_pay_history` SET status=2 WHERE amt_remain>0 AND invoice_code='$invoice_code' ");

                $rs_pay_history = DB::select(" SELECT * FROM `db_pay_product_receipt_002_pay_history` WHERE invoice_code='$invoice_code' AND status in(2) ");
                if(count($rs_pay_history) > 0){

                      DB::table('db_pay_product_receipt_001')
                      ->where('invoice_code', $invoice_code)
                      ->update(array(
                        'status_sent' => 2 ,
                      ));

                }else{
                  DB::table('db_pay_product_receipt_001')
                      ->where('invoice_code', $invoice_code)
                      ->update(array(
                        'status_sent' => 3 ,
                      ));
                }


              DB::select(" TRUNCATE $temp_db_stocks_compare ;");

              DB::select(" UPDATE `db_pay_product_receipt_001` SET pay_date=now(),pay_user=".\Auth::user()->id." WHERE (`id`='$lastInsertId') ");


              // ตัด Stock
              $db_select = DB::select("
                SELECT * FROM  `db_pay_product_receipt_002`  WHERE invoice_code='$invoice_code' AND time_pay=$time_pay
                 ");


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
                        $stock_type_id_fk = 2 ;
                        $stock_id_fk = $lastID ;
                        $ref_table = 'db_pay_product_receipt_002' ;
                        $ref_table_id = $sRow->id ;
                        // $ref_doc = $sRow->ref_doc;
                        // $ref_doc = DB::select(" select * from `db_pay_requisition_002` WHERE id=".$sRow->id." ");
                        // dd($ref_doc[0]->ref_doc);
                        // $ref_doc = @$ref_doc[0]->ref_doc;
                        $ref_doc = @$sRow->invoice_code;
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
                                  "note" =>  'จ่ายสินค้าตามใบเสร็จ ',
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

             // อัพเดต สถานะ ด้วย ว่าจ่ายครบแล้ว หรือ ยังค้างอยู่  db_pay_product_receipt_001


             $ch_status_cancel = DB::select(" SELECT * FROM `db_pay_product_receipt_002` WHERE invoice_code='$invoice_code' AND status_cancel in (0) ");
             if(count($ch_status_cancel)==0){
                DB::select(" UPDATE `db_pay_product_receipt_001` SET status_sent=1 WHERE invoice_code='$invoice_code' ");
             }else{

                    $rs_pay_history = DB::select(" SELECT status FROM `db_pay_product_receipt_002_pay_history` WHERE time_pay=$time_pay AND invoice_code='$invoice_code' AND amt_remain>0  ");

                    if(count($rs_pay_history)>0){
                      DB::select(" UPDATE `db_pay_product_receipt_001` SET status_sent=2 WHERE invoice_code='$invoice_code' ");
                    }else{
                      DB::select(" UPDATE `db_pay_product_receipt_001` SET status_sent=3 WHERE invoice_code='$invoice_code' ");
                      DB::select(" UPDATE `db_orders` SET approve_status=9 ,order_status_id_fk=7 WHERE code_order='$invoice_code' ");
                    }

             }


          }

          DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
        return $e->getMessage();
        }
        catch(\FatalThrowableError $fe)
        {
            DB::rollback();
        return $e->getMessage();
        }


          return $lastInsertId ;


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

       $w01 = "";
       $w02 = "";
       $w03 = "";
       $w04 = "";
       $w05 = "";
       $w06 = "";
       $w07 = "";
       $w08 = "";

        if(!empty($req->business_location_id_fk)){
           $w01 = " AND db_pay_product_receipt_001.business_location_id_fk=".$req->business_location_id_fk ;
        }else{
           $w01 = "";
        }

        if(!empty($req->branch_id_fk)){
           // $w02 = "  AND
           //              (
           //              db_pay_product_receipt_001.branch_id_fk=".(\Auth::user()->branch_id_fk)." OR
           //              db_pay_product_receipt_001.branch_id_fk_tosent=".(\Auth::user()->branch_id_fk)."
           //              ) " ;
           $w02 = " AND db_pay_product_receipt_001.branch_id_fk = ".$req->branch_id_fk." " ;
        }else{
           // $w02 = " AND db_pay_product_receipt_001.branch_id_fk_tosent = ".(\Auth::user()->branch_id_fk)." " ;
           $w02 = "" ;
        }

        if(!empty($req->customer_id_fk)){
           $w03 = " AND db_pay_product_receipt_001.customer_id_fk LIKE '%".$req->customer_id_fk."%'  " ;
        }else{
           $w03 = "";
        }


        if(isset($req->status_sent) && $req->status_sent!=0 ){
            if(!empty($req->btnSearch03) && $req->btnSearch03==1 ){
                $w08 = " and (db_pay_product_receipt_001.status_sent='".$req->status_sent."' OR db_pay_product_receipt_001.status_sent=2) " ;
             }else{
                $w08 = " and db_pay_product_receipt_001.status_sent='".$req->status_sent."' " ;
             }
        }else{
           // $w08 = " and db_pay_product_receipt_001.status_sent=3 AND date(db_pay_product_receipt_001.pay_date)=CURDATE() ";
           $w08 = " AND ( date(db_pay_product_receipt_001.pay_date)=CURDATE() OR date(db_pay_product_receipt_001.action_date)=CURDATE() ) ";
        }

        if(!empty($req->startDate) && !empty($req->endDate)){
           $w04 = " and date(db_pay_product_receipt_001.bill_date) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
           $w05 = "";
        }else{
           $w04 = "";
        }

        if(!empty($req->btnSearch03) && $req->btnSearch03==1 ){
           $w04 = " and date(db_pay_product_receipt_001.bill_date) BETWEEN '".$req->startDate."' AND '".$req->endDate."' AND db_pay_product_receipt_001.status_sent!=3 " ;
           $w05 = "";
        }


       if(isset($req->txtSearch_001)){
           $w06 = " AND LOWER(db_pay_product_receipt_001.invoice_code) LIKE '%".strtolower($req->txtSearch_001)."%' ";
        }else{
           $w06 = "";
        }

        if(!empty($req->startPayDate) && !empty($req->endPayDate)){
           $w07 = " and date(db_pay_product_receipt_001.pay_date) BETWEEN '".$req->startPayDate."' AND '".$req->endPayDate."'  " ;
        }else{
           $w07 = "";
        }

        if(!empty($req->action_user)){
           $w09 = " and ( db_pay_product_receipt_001.pay_user = '".$req->action_user."' OR db_pay_product_receipt_001.action_user = '".$req->action_user."')  " ;
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
                    db_pay_product_receipt_001.id,
                    db_pay_product_receipt_001.status_sent,
                    db_pay_product_receipt_001.action_user,
                    db_pay_product_receipt_001.pay_user,
                    db_pay_product_receipt_001.action_date,
                    db_pay_product_receipt_001.pay_date,
                    db_pay_product_receipt_001.invoice_code,
                    db_pay_product_receipt_001.bill_date,
                    db_pay_product_receipt_001.customer_id_fk,  
                    db_pay_product_receipt_001.branch_id_fk,
                    db_pay_product_receipt_001.branch_id_fk_tosent,
                    db_pay_product_receipt_001.business_location_id_fk,
                    db_pay_product_receipt_001.address_send_type
                    FROM
                    db_pay_product_receipt_001
                    WHERE db_pay_product_receipt_001.address_send_type in (1,2)
                   ".$w01."
                   ".$w02."
                   ".$w03."
                   ".$w04."
                   ".$w05."
                   ".$w06."
                   ".$w07."
                   ".$w08."
                   ".$w09."
                   GROUP BY invoice_code
                   ORDER BY db_pay_product_receipt_001.pay_date DESC
                 ");
        }else{
              $sTable = DB::select("
                        SELECT
                        db_pay_product_receipt_001.id,
                        db_pay_product_receipt_001.status_sent,
                        db_pay_product_receipt_001.action_user,
                        db_pay_product_receipt_001.pay_user,
                        db_pay_product_receipt_001.action_date,
                        db_pay_product_receipt_001.pay_date,
                        db_pay_product_receipt_001.invoice_code,
                        db_pay_product_receipt_001.bill_date,
                        db_pay_product_receipt_001.customer_id_fk,
                        db_pay_product_receipt_001.branch_id_fk,
                        db_pay_product_receipt_001.branch_id_fk_tosent,
                        db_pay_product_receipt_001.business_location_id_fk,
                        db_pay_product_receipt_001.address_send_type
                        FROM
                        db_pay_product_receipt_001
                        WHERE db_pay_product_receipt_001.address_send_type in (1,2)
                       AND (db_pay_product_receipt_001.branch_id_fk_tosent = ".(\Auth::user()->branch_id_fk)." OR db_pay_product_receipt_001.branch_id_fk = ".(\Auth::user()->branch_id_fk)." AND db_pay_product_receipt_001.branch_id_fk_tosent = ".(\Auth::user()->branch_id_fk)." )
                       ".$w01." 
                       ".$w02." 
                       ".$w03." 
                       ".$w04." 
                       ".$w05." 
                       ".$w06." 
                       ".$w07." 
                       ".$w08." 
                       ".$w09." 
                       GROUP BY invoice_code
                       ORDER BY db_pay_product_receipt_001.pay_date DESC
                     ");

        }
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('invoice_code_2', function($row) {
            return $row->invoice_code;
      })
      ->addColumn('invoice_code', function($row) {
            // return "<span style='cursor:pointer;' class='invoice_code' data-toggle='tooltip' data-placement='top' title='คลิ้กเพื่อดูรายละเอียด' data-invoice_code='".$row->invoice_code."' >".$row->invoice_code."</span>";
        return @$row->invoice_code;
      })
      ->escapeColumns('invoice_code')
      ->addColumn('customer', function($row) {
            $rs = DB::select(" select * from customers where id=".@$row->customer_id_fk." ");
            return @$rs[0]->user_name." : ".@$rs[0]->prefix_name.@$rs[0]->first_name." ".@$rs[0]->last_name;
      })
      ->addColumn('status_sent', function($row) {
            $rs = DB::select(" select * from dataset_pay_product_status where id=".@$row->status_sent." ");
            if(@$row->status_sent==3){
              return '<span class="badge badge-pill badge-success font-size-16">'.@$rs[0]->txt_desc.'</span>';
            }elseif(@$row->status_sent==1||@$row->status_sent==2||@$row->status_sent==4){
              return '<span class="badge badge-pill badge-warning font-size-16" style="color:black;">'.@$rs[0]->txt_desc.'</span>';
            }else{
              return '<span class="badge badge-pill badge-info font-size-16">'.@$rs[0]->txt_desc.'</span>';
            }

      })
      ->addColumn('status_sent_2', function($row) {
            return $row->status_sent;
      })
      ->addColumn('status_cancel_all', function($row) {
            $P = DB::select(" select status_cancel from db_pay_product_receipt_002 where invoice_code='".@$row->invoice_code."' AND status_cancel=0 ");
            if(count($P)>0){
              return 1;
            }else{
              return 0;
            }
      })
      ->addColumn('status_cancel_some', function($row) {
            $P = DB::select(" select status_cancel from db_pay_product_receipt_002 where invoice_code='".@$row->invoice_code."' AND status_cancel=1 ");
            if(count($P)>0){
              return 1;
            }else{
              return 0;
            }
      })
      ->addColumn('action_user', function($row) {
        if(@$row->action_user){
          $P = DB::select(" select * from ck_users_admin where id=".@$row->action_user." ");
            return @$P[0]->name." <br> ".@$row->action_date;
        }else{
          return '-';
        }
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
                 return 'รับสินค้าด้วยตนเอง';
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

    public function wait_orders(Request $req){

      $user_login_id = \Auth::user()->id;
      $sPermission = \Auth::user()->permission ;
      $action_user_011_1 = "";
      $action_user_011_2 = "";
          // \Auth::user()->position_level==4 => Supervisor
          if(\Auth::user()->position_level=='3' || \Auth::user()->position_level=='4'){
          $action_user_011 = " AND db_orders.branch_id_fk = '".(\Auth::user()->branch_id_fk)."' " ;
          $action_user_011_1 = " AND db_orders.sentto_branch_id = '".(\Auth::user()->branch_id_fk)."' " ;
          $action_user_011_2 = " AND db_orders.sentto_branch_id = 0";
          }else{
          // $action_user_011 = " AND action_user = $user_login_id ";
          $action_user_011 = " AND db_orders.branch_id_fk = '".(\Auth::user()->branch_id_fk)."' " ;
          $action_user_011_1 = " AND db_orders.sentto_branch_id = '".(\Auth::user()->branch_id_fk)."' " ;
          $action_user_011_2 = " AND db_orders.sentto_branch_id = 0";
          }

          if($sPermission==1){
              $action_user_01 = "";
              $action_user_011 = "";
              $action_user_011_1 = "";
              $action_user_011_2 = "";
          }else{
          $action_user_01 = " AND action_user = $user_login_id ";
          }

          // วุฒิเพิ่มมา
          if(\Auth::user()->position_level=='3' || \Auth::user()->position_level=='4'){
          $action_user_01 = "";
          $action_user_011 = "";
          $action_user_011_1 = "";
          $action_user_011_2 = "";
          }

          // รับแล้ว
          $sTable_re = DB::table('db_pay_product_receipt_001')->whereIn('address_send_type',[1,2])->select('invoice_code')->pluck('invoice_code')->toArray();
      //     $sTable_re = DB::select("
      //     SELECT
      //     db_pay_product_receipt_001.invoice_code,
      //     FROM
      //     db_pay_product_receipt_001
      //     WHERE db_pay_product_receipt_001.address_send_type in (1,2)
      //    GROUP BY invoice_code
      //    ORDER BY db_pay_product_receipt_001.pay_date DESC
      //  ");

  $sw = '';
  foreach($sTable_re as $key => $sr){
    if($key+1==count($sTable_re)){
      $sw.="'".$sr."'";
    }else{
      $sw.="'".$sr."'".',';
    }
    // $sw.=$sr.',';
  }

  $startDate = "";
  if(isset($req->startDate)){
    $startDate = " AND DATE(db_orders.approve_date) >= '".$req->startDate."' " ;
  }

  $endDate = "";
  if(isset($req->endDate)){
    $endDate = " AND DATE(db_orders.approve_date) <= '".$req->endDate."' " ;
  }
  // sentto_branch_id
if(count($sTable_re)!=0){
            $sTable = DB::select("
            SELECT gift_voucher_price,code_order,db_orders.id,action_date,purchase_type_id_fk,0 as type,customers_id_fk,sum_price,invoice_code,approve_status,shipping_price,db_orders.updated_at,dataset_pay_type.detail as pay_type,cash_price,credit_price,fee_amt,transfer_price,aicash_price,total_price,db_orders.created_at,status_sent_money,cash_pay,action_user,db_orders.pay_type_id_fk
            FROM db_orders
            Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
            WHERE 1
            $action_user_011
            $action_user_011_2
            $startDate
            $endDate
            AND db_orders.approve_status = 2
            AND delivery_location = 0
            AND db_orders.code_order not in ($sw)

            OR 1
            $action_user_011_1
            $startDate
            $endDate
            AND db_orders.approve_status = 2
            AND delivery_location = 0
            AND db_orders.code_order not in ($sw)

            ORDER BY created_at DESC

          ");
}else{
  $sTable = DB::select("
            SELECT gift_voucher_price,code_order,db_orders.id,action_date,purchase_type_id_fk,0 as type,customers_id_fk,sum_price,invoice_code,approve_status,shipping_price,db_orders.updated_at,dataset_pay_type.detail as pay_type,cash_price,credit_price,fee_amt,transfer_price,aicash_price,total_price,db_orders.created_at,status_sent_money,cash_pay,action_user,db_orders.pay_type_id_fk
            FROM db_orders
            Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
            WHERE 1
            $action_user_011
            AND db_orders.approve_status = 2
            AND delivery_location = 0
            UNION ALL
            SELECT
            gift_voucher_price,
            code_order,
            db_add_ai_cash.id,
            db_add_ai_cash.created_at as d2,
            0 as purchase_type_id_fk,
            'เติม Ai-Cash' AS type,
            db_add_ai_cash.customer_id_fk as c2,
            db_add_ai_cash.aicash_amt,
            db_add_ai_cash.id as inv_no,approve_status
            ,'',
            db_add_ai_cash.updated_at as ud2,
            'ai_cash' as pay_type,cash_price,
            credit_price,fee_amt,transfer_price,
            0 as aicash_price,total_amt as total_price,db_add_ai_cash.created_at ,status_sent_money,'',action_user,''
            FROM db_add_ai_cash
            WHERE 1 AND db_add_ai_cash.approve_status<>4
            $action_user_01
            ORDER BY created_at DESC

          ");
}


/*
SELECT code_order,db_orders.id,action_date,purchase_type_id_fk,0 as type,customers_id_fk,sum_price,invoice_code,approve_status,shipping_price,db_orders.updated_at,dataset_pay_type.detail as pay_type,cash_price,credit_price,fee_amt,transfer_price,aicash_price,total_price,db_orders.created_at,status_sent_money,cash_pay,action_user  FROM db_orders  Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id  WHERE 1
AND db_orders.branch_id_fk = '1'
AND DATE(db_orders.created_at) >= CURDATE()
UNION ALL
SELECT  '' as code_order,  db_add_ai_cash.id,  db_add_ai_cash.created_at as d2,  0 as purchase_type_id_fk,  '\u0e40\u0e15\u0e34\u0e21 Ai-Cash' AS type,  db_add_ai_cash.customer_id_fk as c2,  db_add_ai_cash.aicash_amt,  db_add_ai_cash.id as inv_no,approve_status  ,'',  db_add_ai_cash.updated_at as ud2,  'ai_cash' as pay_type,cash_price,  credit_price,fee_amt,transfer_price,  0 as aicash_price,total_amt as total_price,db_add_ai_cash.created_at ,status_sent_money,'',action_user  FROM db_add_ai_cash  WHERE 1
AND db_add_ai_cash.approve_status<>4
AND DATE(db_add_ai_cash.created_at) >= CURDATE()

ORDER BY created_at DESC
*/
      //  dd($sTable);
     $sQuery = \DataTables::of($sTable);
     return $sQuery
     ->addColumn('invoice_code_2', function($row) {
           return $row->code_order;
     })
     ->addColumn('invoice_code', function($row) {
           // return "<span style='cursor:pointer;' class='invoice_code' data-toggle='tooltip' data-placement='top' title='คลิ้กเพื่อดูรายละเอียด' data-invoice_code='".$row->invoice_code."' >".$row->invoice_code."</span>";
      $url = url('backend/pay_product_receipt') . '?invoice_code=' . $row->code_order;
       return "<a href='$url'>$row->code_order</a>";
     })
     ->escapeColumns('invoice_code')
     ->addColumn('action_user', function($row) {
      if(@$row->action_user!=''){
        $sD = DB::select(" select * from ck_users_admin where id=".$row->action_user." ");
         return @$sD[0]->name;
      }else{
        return '';
      }
    })
     ->addColumn('status', function($row) {

      // `approve_status` int(11) DEFAULT '0' COMMENT ' 0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ,9=Finished (ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย)''',
      // ของเดิม
      // `approve_status` int(11) DEFAULT '0' COMMENT '0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ',
      // แก้ใหม่
      // `approve_status` int(11) DEFAULT '0' COMMENT ' 1=รออนุมัติ,2=อนุมัติแล้ว,3=รอชำระ,4=รอจัดส่ง,5=ยกเลิก,6=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย',

              if(@$row->approve_status!=""){
                @$approve_status = DB::select(" select * from `dataset_approve_status` where id=".@$row->approve_status." ");
                // return $purchase_type[0]->orders_type;
                return @$approve_status[0]->txt_desc;
              }else{
                // return "No completed";
                return "<font color=red>* รอดำเนินการต่อ</font>";
              }

            })
     ->addColumn('created_at', function($row) {
      $d = strtotime(@$row->created_at);
      return date("Y-m-d",$d)."<br/>".date("H:i:s",$d);
    })
    ->escapeColumns('created_at')
     ->addColumn('customer', function($row) {
           $rs = DB::select(" select * from customers where id=".@$row->customers_id_fk." ");
           return @$rs[0]->user_name." : ".@$rs[0]->prefix_name.@$rs[0]->first_name." ".@$rs[0]->last_name;
     })
    //  ->addColumn('status_sent', function($row) {
    //        $rs = DB::select(" select * from dataset_pay_product_status where id=".@$row->approve_status." ");
    //        if(@$row->status_sent==3){
    //          return '<span class="badge badge-pill badge-success font-size-16">'.@$rs[0]->txt_desc.'</span>';
    //        }elseif(@$row->status_sent==1||@$row->status_sent==2||@$row->status_sent==4){
    //          return '<span class="badge badge-pill badge-warning font-size-16" style="color:black;">'.@$rs[0]->txt_desc.'</span>';
    //        }else{
    //          return '<span class="badge badge-pill badge-info font-size-16">'.@$rs[0]->txt_desc.'</span>';
    //        }

    //  })
    //  ->addColumn('status_sent_2', function($row) {
    //        return $row->status_sent;
    //  })
    //  ->addColumn('status_cancel_all', function($row) {
    //        $P = DB::select(" select status_cancel from db_pay_product_receipt_002 where invoice_code='".@$row->invoice_code."' AND status_cancel=0 ");
    //        if(count($P)>0){
    //          return 1;
    //        }else{
    //          return 0;
    //        }
    //  })
    //  ->addColumn('status_cancel_some', function($row) {
    //        $P = DB::select(" select status_cancel from db_pay_product_receipt_002 where invoice_code='".@$row->invoice_code."' AND status_cancel=1 ");
    //        if(count($P)>0){
    //          return 1;
    //        }else{
    //          return 0;
    //        }
    //  })
    //  ->addColumn('action_user', function($row) {
    //    if(@$row->action_user){
    //      $P = DB::select(" select * from ck_users_admin where id=".@$row->action_user." ");
    //        return @$P[0]->name." <br> ".@$row->action_date;
    //    }else{
    //      return '-';
    //    }
    //  })
    //  ->escapeColumns('action_user')
    //  ->addColumn('pay_user', function($row) {
    //    if(@$row->pay_user){
    //       $P = DB::select(" select * from ck_users_admin where id=".@$row->pay_user." ");
    //      return @$P[0]->name." <br> ".@$row->pay_date;
    //    }else{
    //      return '-';
    //    }
    //  })
    //  ->escapeColumns('pay_user')
    //   ->addColumn('branch', function($row) {
    //         $P = DB::select(" select * from branchs where id=".@$row->branch_id_fk." ");
    //         return @$P[0]->b_name;
    //  })
    //   ->addColumn('address_send_type', function($row) {
    //        if(@$row->address_send_type==1){
    //             return 'รับสินค้าด้วยตนเอง';
    //        }else if(@$row->address_send_type==2){
    //          if(@$row->branch_id_fk_tosent==(\Auth::user()->branch_id_fk)){
    //            return 'รับที่สาขานี้';
    //          }else{
    //            return 'รับที่สาขาอื่น';
    //          }
    //        }else if(@$row->address_send_type==3){
    //            return 'จัดส่งพัสดุ';
    //        }
    //  })
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


        if(isset($req->status_sent)){
           $w05 = " and db_pay_product_receipt_001.status_sent='".$req->status_sent."' " ;
        }else{
           $w05 = "";
        }

      if(isset($req->txtSearch_003)){
           // $w06 = " and db_pick_warehouse_tmp.invoice_code ='".$req->txtSearch."'  " ;
           $w06 = " AND LOWER(db_pay_product_receipt_002.invoice_code) LIKE '%".strtolower($req->txtSearch_003)."%' ";
        }else{
           $w06 = "";
        }

      $sTable = DB::select("
              SELECT
              db_pay_product_receipt_002.id,
              db_pay_product_receipt_002.invoice_code,
              db_pay_product_receipt_002.business_location_id_fk,
              db_pay_product_receipt_002.warehouse_id_fk,
              db_pay_product_receipt_002.branch_id_fk,
              db_pay_product_receipt_002.zone_id_fk,
              db_pay_product_receipt_002.shelf_id_fk,
              db_pay_product_receipt_002.shelf_floor,
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
              group by db_pay_product_receipt_002.invoice_code

              ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
          return "<b>".@$row->invoice_code."</b>";
      })
      ->escapeColumns('column_001')

      ->addColumn('column_002', function($row) {
             $Products = DB::select("
                SELECT x.*,
                  (
                  CASE WHEN (SELECT product_id_fk FROM db_pay_product_receipt_002 WHERE id=(x.id)-1 AND invoice_code=x.invoice_code ORDER BY id)=x.product_id_fk THEN 1 ELSE 0 END
                  ) as dup FROM db_pay_product_receipt_002 as x  WHERE x.invoice_code = '".$row->invoice_code."'
                  ORDER BY x.invoice_code
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
      // temp_db_stocks
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
    			// $Products = DB::select("
      	// 	    	SELECT $temp_ppr_002.product_id_fk,$temp_ppr_002.amt,$temp_ppr_002.product_name,$temp_ppr_002.product_name,dataset_product_unit.product_unit from $temp_ppr_002 LEFT Join dataset_product_unit ON $temp_ppr_002.product_unit_id_fk = dataset_product_unit.id where $temp_ppr_002.frontstore_id_fk=".$row->id."
      	// 		");

DB::select(" DROP TABLE IF EXISTS temp_product_fifo; ");
// สินค้าปกติ
DB::select(" CREATE TEMPORARY TABLE temp_product_fifo
SELECT $temp_ppr_002.product_id_fk,sum($temp_ppr_002.amt) as amt,$temp_ppr_002.product_name,dataset_product_unit.product_unit from $temp_ppr_002 LEFT Join dataset_product_unit ON $temp_ppr_002.product_unit_id_fk = dataset_product_unit.id where $temp_ppr_002.frontstore_id_fk=".$row->id." and $temp_ppr_002.type_product='product'
group by $temp_ppr_002.product_id_fk
");


// สินค้าโปรโมชั่น
// วุฒิเพิ่ม
$temp_ppr_0021_data = DB::table($temp_ppr_002)->get();
// dd($temp_ppr_0021_data);
foreach($temp_ppr_0021_data as $tmp){
  $p_unit = 'ชิ้น';
  $p_code = '0000:';
  $p_name = '';
  $p_id = 0;
  $p_amt = 0;
  if($tmp->type_product=='promotion'){
  $data_promos = DB::table('promotions_products')->where('promotion_id_fk',$tmp->promotion_id_fk)->get();
    foreach($data_promos as $data_promo){
      $p_id = $data_promo->product_id_fk;
      $data_unit  = DB::table('dataset_product_unit')->where('id',$data_promo->product_unit)->first();
      if($data_unit ){
        $p_unit = $data_unit->product_unit;
      }
      $data_code = DB::table('products')->where('id',$data_promo->product_id_fk)->first();
      if($data_code){
        $p_code = $data_code->product_code.' : ';
      }
      $data_product = DB::table('products_details')->where('product_id_fk',$data_promo->product_id_fk)->where('lang_id',1)->first();
      if($data_product){
        $p_name = $data_product->product_name;
      }
      $p_amt = $tmp->amt*$data_promo->product_amt;
        $id = DB::table('temp_product_fifo')->insertOrIgnore([
          'product_id_fk' =>$p_id,
          'amt' => $p_amt,
          'product_name' => $p_code.$p_name,
          'product_unit' => $p_unit,
        ]);
    }
}

}

// สินค้าโปรโมชั่น
// DB::select(" INSERT IGNORE INTO temp_product_fifo
// SELECT
// promotions_products.product_id_fk,sum(promotions_products.product_amt) as amt,
// CONCAT(
// (SELECT product_code FROM products WHERE id=promotions_products.product_id_fk limit 1),':',
// (SELECT product_name FROM products_details WHERE product_id_fk=promotions_products.product_id_fk and lang_id=1 limit 1)) as product_name,
// dataset_product_unit.product_unit
// FROM `promotions_products`
// LEFT Join dataset_product_unit ON promotions_products.product_unit = dataset_product_unit.id
// where promotion_id_fk in (
// SELECT $temp_ppr_002.promotion_id_fk from $temp_ppr_002 WHERE
// $temp_ppr_002.frontstore_id_fk=".$row->id." and $temp_ppr_002.type_product='promotion')
// group by promotions_products.product_id_fk
// ");

        $Products = DB::select("
               SELECT product_id_fk,sum(amt) as amt,product_name,product_unit from temp_product_fifo GROUP BY product_id_fk ORDER BY product_name
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
// dd($Products);
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
      ->addColumn('column_003', function($row) {
          // return "<b>".@$row->invoice_code."</b>";
          return '<a href="javascript: void(0);" target=_blank data-id="'.$row->orders_id_fk.'" class="print02" > <i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#669999;"></i></a>';
      })
      ->escapeColumns('column_003')
      ->make(true);
    }



    public function Datatable008(Request $req){

      $sTable = DB::select(" SELECT * FROM db_pay_product_receipt_001  WHERE  db_pay_product_receipt_001.invoice_code='".$req->txtSearch."' group by time_pay order By time_pay ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {

            $rs = DB::select(" SELECT
                  db_pay_product_receipt_002_pay_history.id,
                  db_pay_product_receipt_002_pay_history.time_pay,
                  db_pay_product_receipt_002_pay_history.invoice_code,
                  DATE(db_pay_product_receipt_002_pay_history.pay_date) as pay_date,
                  db_pay_product_receipt_002_pay_history.`status`,
                  ck_users_admin.`name` as pay_user
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
        //  dd($Products);
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
          // '3=สินค้าพอต่อการจ่ายครั้งนี้ 2=สินค้าไม่พอ มีบางรายการค้างจ่าย'
           $rs_pay_history = DB::select(" SELECT id FROM `db_pay_product_receipt_002_pay_history` WHERE invoice_code='".$row->invoice_code."' AND status in (2) ");

           if(count($rs_pay_history)>0){
               return 2; // ค้างจ่าย
           }else{
               return 3; // จ่ายครบแล้ว
           }

       })
      ->addColumn('ch_amt_lot_wh', function($row) {
          // ดูว่าไม่มีสินค้าคลังเลย
          // ดูในใบซื้อว่ามีรยการสินค้าใดบ้างที่ยังค้างส่งอยู่
       
          $Products = DB::select("
            SELECT * from db_pay_product_receipt_002 WHERE invoice_code='".$row->invoice_code."'
            AND amt_remain > 0 GROUP BY product_id_fk ORDER BY time_pay,amt_get DESC limit 1 ;
          ");
             // วุฒิแก้ ปรับเอา AND amt_remain > 0 ออก เพื่อให้มันบันทึกได้ เอา limit 1 ออก
        //   $Products = DB::select("
        //   SELECT * from db_pay_product_receipt_002 WHERE invoice_code='".$row->invoice_code."'
        //   GROUP BY product_id_fk ORDER BY time_pay,amt_get DESC;
        // ");
          // Case ที่มีการบันทึกข้อมูลแล้ว
              if(@$Products){

                    if(count($Products)>0){
                        $arr = [];
                        foreach ($Products as $key => $value) {
                          array_push($arr,$value->product_id_fk);
                        }
                        $arr_im = implode(',',$arr);
                        $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
                        $r = DB::select(" SELECT sum(amt) as sum FROM $temp_db_stocks WHERE product_id_fk in ($arr_im) 	ORDER BY amt DESC");
                        // dd($r[0]->sum);
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

                  <a href="javascript: void(0);" class="btn btn-sm btn-danger cDelete2 " data-toggle="tooltip" data-placement="left" title="ยกเลิกรายการจ่ายสินค้าครั้งล่าสุด" data-time_pay='.$row->time_pay.' style="display:none;" ><i class="bx bx-trash font-size-16 align-middle"></i></a>

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




}
