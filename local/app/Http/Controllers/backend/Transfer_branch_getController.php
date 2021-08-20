<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Transfer_branch_getController extends Controller
{

    public function index(Request $request)
    {

       $sAction_user = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sBranchs = \App\Models\Backend\Branchs::get();
       $Supplier = DB::select(" select * from dataset_supplier ");
       $tr_number = DB::select(" SELECT tr_number FROM `db_transfer_branch_get` where branch_id_fk=".(\Auth::user()->branch_id_fk)." ");

       $Transfer_branch_status = \App\Models\Backend\Transfer_branch_status::whereIn('id',[3,4,5])->get();

      return View('backend.transfer_branch_get.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs,'Supplier'=>$Supplier,
           'sAction_user'=>$sAction_user,
           'tr_number'=>$tr_number,
           'Transfer_branch_status'=>$Transfer_branch_status,
           'sPermission'=>\Auth::user()->permission,
        ) );
      // return view('backend.transfer_branch_get.index');
      
    }

 public function create()
    {
       $po_supplier = DB::select(" SELECT CONVERT(SUBSTRING(tr_number, -3),UNSIGNED INTEGER) AS cnt FROM db_transfer_branch_get WHERE tr_number is not null  order by tr_number desc limit 1  ");
       // dd($po_supplier[0]->cnt);
       $po_runno = "PO".date("ymd").sprintf("%03d", $po_supplier[0]->cnt + 1);
       // dd($po_runno);

       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sBranchs = \App\Models\Backend\Branchs::get();
       $Supplier = DB::select(" select * from dataset_supplier ");
      return View('backend.transfer_branch_get.form')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs
           ,'Supplier'=>$Supplier
           ,'po_runno'=>$po_runno,
           'sPermission'=>\Auth::user()->permission,
        ) );
    }
    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Transfer_branch_get::find($id);
       // dd($sRow);
        $sBusiness_location = \App\Models\Backend\Business_location::get();
        $sBranchs = \App\Models\Backend\Branchs::get();
        $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();
        $sUserAdmin = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");
        $sUserAdmin_ALL = DB::select(" select * from ck_users_admin  ");

        // check ว่ารับครบแล้วหรือยัง สินค้าอาจมีหลายรายการ

       return View('backend.transfer_branch_get.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id,
           'sPermission'=>\Auth::user()->permission,
           'sBusiness_location'=>$sBusiness_location,
           'sBranchs'=>$sBranchs,
           'sProductUnit'=>$sProductUnit,
           'sUserAdmin'=>$sUserAdmin,
           'sUserAdmin_ALL'=>$sUserAdmin_ALL,
        ) );
    }

    public function update(Request $request, $id)
    {
     // dd($request->all());
      if(isset($request->approved_getproduct)){

        return $this->form($id);

        //     $sRow = \App\Models\Backend\Transfer_branch_get::find($id);
        //     $sRow->approver    = \Auth::user()->id;
        //     $sRow->approve_date = date('Y-m-d H:i:s');
        //     $sRow->approve_status = request('approve_status');
        //     if(request('approve_status')==5)
        //     $sRow->tr_status_get    = request('tr_status_get');
        //     $sRow->note2    = request('note2');
        //     $sRow->created_at = date('Y-m-d H:i:s');
        //     $sRow->save();

        // return redirect()->to(url("backend/transfer_branch_get/".$id."/edit"));

      }elseif(isset($request->save_from_firstform)){
            // dd($request->all());
            // dd($id);
            $sRow = \App\Models\Backend\Transfer_branch_get::find($id);
            $sRow->note    = request('note');
            $sRow->created_at = date('Y-m-d H:i:s');
            $sRow->save();
            return redirect()->to(url("backend/transfer_branch_get/".$id."/edit"));

      }elseif(isset($request->approve_getback)){
         // dd($request->all());

          /*
            array:7 [▼
              "_method" => "PUT"
              "id" => "2"
              "approve_getback" => "1"
              "_token" => "PwCUnAUrZ6PVfYAg65G8AshYDAASwfRkHelFmuOY"
              "approver" => "1"
              "approve_status_getback" => "1"
              "note3" => "zzzzzzzzzz"
            ]
            */

            $sRow = \App\Models\Backend\Transfer_branch_get::find($request->id);
            $sRow->approve_status    = $request->approve_status;
            $sRow->approver    = $request->approver;
            $sRow->approve_date    = date('Y-m-d H:i:s');
            $sRow->note3    = $request->note3;
            $sRow->tr_status_get    = 6;
            $sRow->created_at = date('Y-m-d H:i:s');
            $sRow->save();


            DB::select(" UPDATE `db_transfer_branch_code` SET tr_status_from='6' where tr_number='".$sRow->tr_number."' ");



            // เมื่ออนุมัติ == 1 ตัดเข้าคลังอีกครั้ง
          // ดึงเข้าคลัง สาขาที่รับโอนมา กรณี อนุมัติเท่านั้น
            if(request('approve_status')==1){
                /*

                CREATE TABLE `db_stocks_in` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `business_location_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>dataset_business_location>id',
                  `branch_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>branchs>id',
                  `product_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>products>id',
                  `lot_number` varchar(255) DEFAULT NULL,
                  `lot_expired_date` date DEFAULT NULL COMMENT 'วันหมดอายุของ lot นี้',
                  `action_date` datetime DEFAULT NULL COMMENT 'วันที่ดำเนินการ',
                  `action_type_id_fk` int(1) DEFAULT '0' COMMENT 'Ref>dataset_stocks_action_type>id',
                  `amt` int(11) DEFAULT '0' COMMENT 'ยอดเข้า',
                  `created_at` timestamp NULL DEFAULT NULL,
                  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  `deleted_at` timestamp NULL DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='ข้อมูล Stocks เข้า'


                CREATE TABLE `db_stocks` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `business_location_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>dataset_business_location>id',
                  `branch_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>branchs>id',
                  `product_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>products>id',
                  `lot_number` varchar(255) DEFAULT NULL,
                  `lot_expired_date` date DEFAULT NULL COMMENT 'วันหมดอายุของ lot นี้',
                  `amt` int(11) DEFAULT '0' COMMENT 'จำนวนที่รับเข้า',
                  `product_unit_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>dataset_product_unit>id',
                  `date_in_stock` date DEFAULT NULL COMMENT 'วันที่เช็คเข้าสต๊อค',
                  `warehouse_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>warehouse>id',
                  `zone_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>zone>id',
                  `shelf_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>shelf>id',
                  `shelf_floor` int(11) DEFAULT '1' COMMENT 'ชั้นของ shelf',
                  `created_at` timestamp NULL DEFAULT NULL,
                  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  `deleted_at` timestamp NULL DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='ข้อมูล Stocks'


                CREATE TABLE `db_transfer_branch_get_products_receive` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `transfer_branch_get_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>db_transfer_branch_get>id',
                  `transfer_branch_get_products` int(11) DEFAULT '0' COMMENT 'Ref>db_transfer_branch_get_products>id',
                  `product_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>products>id',
                  `lot_number` varchar(255) DEFAULT NULL,
                  `lot_expired_date` date DEFAULT NULL COMMENT 'วันหมดอายุของ lot นี้',
                  `amt_get` int(11) DEFAULT '0' COMMENT 'จำนวนที่ได้รับ',
                  `product_unit_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>dataset_product_unit>id',
                  `branch_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>branchs>id',
                  `warehouse_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>warehouse>id',
                  `zone_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>zone>id',
                  `shelf_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>shelf>id',
                  `shelf_floor` int(11) DEFAULT '0' COMMENT 'ชั้นของ shelf',
                  `action_user` int(11) DEFAULT '0' COMMENT 'ผู้ทำการโอน Ref>ck_users_admin>id',
                  `action_date` date DEFAULT NULL COMMENT 'วันดำเนินการ',
                  `approver` int(11) DEFAULT '0' COMMENT 'ผู้อนุมัติ',
                  `approve_status` int(11) DEFAULT '0' COMMENT '1=อนุมัติแล้ว',
                  `approve_date` date DEFAULT NULL COMMENT 'วันอนุมัติ',
                  `created_at` timestamp NULL DEFAULT NULL,
                  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  `deleted_at` timestamp NULL DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='ตารางข้อมูลนี้ > ใช้สำหรับ เมนู ตั้งค่าการแถมสินค้า > backend/giveaway'


                */

// นำเข้า Stock
                 $products = DB::select("

                    SELECT
                    sum(amt_get) AS sum_amt,
                    db_transfer_branch_get_products_receive.id,
                    db_transfer_branch_get_products_receive.transfer_branch_get_id_fk,
                    db_transfer_branch_get_products_receive.transfer_branch_get_products,
                    db_transfer_branch_get_products_receive.product_id_fk,
                    db_transfer_branch_get_products_receive.lot_number,
                    db_transfer_branch_get_products_receive.lot_expired_date,
                    db_transfer_branch_get_products_receive.amt_get,
                    db_transfer_branch_get_products_receive.product_unit_id_fk,
                    db_transfer_branch_get_products_receive.branch_id_fk,
                    db_transfer_branch_get_products_receive.warehouse_id_fk,
                    db_transfer_branch_get_products_receive.zone_id_fk,
                    db_transfer_branch_get_products_receive.shelf_id_fk,
                    db_transfer_branch_get_products_receive.shelf_floor,
                    db_transfer_branch_get_products_receive.action_user,
                    db_transfer_branch_get_products_receive.action_date,
                    db_transfer_branch_get_products_receive.approver,
                    db_transfer_branch_get_products_receive.approve_status,
                    db_transfer_branch_get_products_receive.approve_date,
                    db_transfer_branch_get_products_receive.created_at,
                    db_transfer_branch_get_products_receive.updated_at,
                    db_transfer_branch_get_products_receive.deleted_at
                    from db_transfer_branch_get_products_receive
                    WHERE transfer_branch_get_id_fk in ($request->id)
                    GROUP BY transfer_branch_get_id_fk,branch_id_fk,warehouse_id_fk,zone_id_fk,shelf_floor

                    ");

/*
CREATE TABLE `db_stocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_location_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>dataset_business_location>id',
  `branch_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>branchs>id',
  `product_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>products>id',
  `lot_number` varchar(255) DEFAULT NULL,
  `lot_expired_date` date DEFAULT NULL COMMENT 'วันหมดอายุของ lot นี้',
  `amt` int(11) DEFAULT '0' COMMENT 'จำนวนที่รับเข้า',
  `product_unit_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>dataset_product_unit>id',
  `date_in_stock` date DEFAULT NULL COMMENT 'วันที่เช็คเข้าสต๊อค',
  `warehouse_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>warehouse>id',
  `zone_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>zone>id',
  `shelf_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>shelf>id',
  `shelf_floor` int(11) DEFAULT '1' COMMENT 'ชั้นของ shelf',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='ข้อมูล Stocks'
*/

// check ก่อนว่ามีใน ชั้นนั้นๆ หรือยัง ถ้ามี update ถ้ายังไม่มี add 

                 foreach ($products as $key => $p) {

                          $_check=DB::table('db_stocks')
                          ->where('business_location_id_fk', $sRow->business_location_id_fk)
                          ->where('branch_id_fk', $p->branch_id_fk)
                          ->where('product_id_fk', $p->product_id_fk)
                          ->where('lot_number', $p->lot_number)
                          ->where('lot_expired_date', $p->lot_expired_date)
                          ->where('warehouse_id_fk', $p->warehouse_id_fk)
                          ->where('zone_id_fk', $p->zone_id_fk)
                          ->where('shelf_id_fk', $p->shelf_id_fk)
                          ->where('shelf_floor', $p->shelf_floor)
                          ->get();
                          if($_check->count() == 0){

                              $stock = new  \App\Models\Backend\Check_stock;
                              $stock->business_location_id_fk = $sRow->business_location_id_fk ;
                              $stock->product_id_fk = $p->product_id_fk ;
                              $stock->lot_number = $p->lot_number ;
                              $stock->lot_expired_date = $p->lot_expired_date ;
                              $stock->amt = $p->sum_amt ;
                              $stock->product_unit_id_fk = $p->product_unit_id_fk ;
                              $stock->branch_id_fk = $p->branch_id_fk ;
                              $stock->warehouse_id_fk = $p->warehouse_id_fk ;
                              $stock->zone_id_fk = $p->zone_id_fk ;
                              $stock->shelf_id_fk = $p->shelf_id_fk ;
                              $stock->shelf_floor = $p->shelf_floor ;
                              $stock->date_in_stock = date("Y-m-d");
                              $stock->created_at = date("Y-m-d H:i:s");
                              $stock->save();


                          }else{

                                DB::table('db_stocks')
                                ->where('business_location_id_fk', $sRow->business_location_id_fk)
                                  ->where('branch_id_fk', $p->branch_id_fk)
                                  ->where('product_id_fk', $p->product_id_fk)
                                  ->where('lot_number', $p->lot_number)
                                  ->where('lot_expired_date', $p->lot_expired_date)
                                  ->where('warehouse_id_fk', $p->warehouse_id_fk)
                                  ->where('zone_id_fk', $p->zone_id_fk)
                                  ->where('shelf_id_fk', $p->shelf_id_fk)
                                  ->where('shelf_floor', $p->shelf_floor)
                                ->update(array(
                                  'amt' => DB::raw( ' amt + '.$p->sum_amt )
                                ));


                          }
                          
                      

                 }

         
               
            }

            return redirect()->to(url("backend/transfer_branch_get/noget/".$request->id));

      }else{
        return $this->form($id);
      }
      
    }

   public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow = \App\Models\Backend\Transfer_branch_get::find($id);
          }else{
            $sRow = new \App\Models\Backend\Transfer_branch_get;
            // รหัสหลักๆ จะถูกสร้างตั้งแต่เลือกรหัสใบโอนมาจากสาขาที่ส่งสินค้ามาให้แล้ว  หลังการอนุมัติเสร็จ 
            // $sRow->business_location_id_fk    = request('business_location_id_fk');
            // $sRow->branch_id_fk    =  \Auth::user()->branch_id_fk;
            // $sRow->tr_number    = request('tr_number');
          }
          // $sRow->action_user    = request('action_user');
          // $sRow->tr_status_get    = request('tr_status_get');
          // $sRow->note    = request('note');
          // $sRow->created_at    = request('created_at');
          // $sRow->created_at = date('Y-m-d H:i:s');
          // dd($sRow);

/*
CREATE TABLE `db_transfer_branch_get` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>branchs>id',
  `get_from_branch_id_fk` int(11) DEFAULT '0' COMMENT 'รับจากสาขาใด',
  `business_location_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>dataset_business_location>id',
  `tr_number` varchar(255) DEFAULT NULL COMMENT 'รหัสการโอนย้าย',
  `po_code_other` varchar(255) DEFAULT NULL COMMENT 'รหัสอื่นๆ ถ้ามี',
  `supplier_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>dataset_supplier>id',
  `action_user` int(11) DEFAULT NULL,
  `note` text COMMENT 'หมายเหตุ',
  `approver` int(11) DEFAULT '0' COMMENT 'รหัสผู้อนุมัติ Ref>ck_users_admin>id',
  `approve_date` datetime DEFAULT NULL COMMENT 'วันที่อนุมัติ',
  `approve_status` int(1) DEFAULT '0' COMMENT '1=อนุมัติ 5=ไม่อนุมัติ',
  `note2` text COMMENT 'หมายเหตุของส่วนการอนุมัติ',
  `tr_status_get` int(1) DEFAULT '0' COMMENT '1=ได้รับสินค้าครบแล้ว 2=ยังค้างรับสินค้า  3=ใบโอน ที่ถูกยกเลิก',
  `buy_status` int(1) DEFAULT '0' COMMENT '1=สั่งซื้อแล้ว',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  */

  
            if(request('approve_status')==5){
                $sRow->tr_status_get    = 5 ; // ปฏิเสธการรับ
            }

            $sRow->approver = \Auth::user()->id;
            $sRow->approve_status = request('approve_status') ;
            $sRow->approve_date = date('Y-m-d H:i:s');
            $sRow->note2 = request('note2') ;
            $sRow->save();

            // dd($sRow->tr_number);
            // dd($sRow->tr_status_get);

            // $Transfer_branch_code = \App\Models\Backend\Transfer_branch_code::where('tr_number',$sRow->tr_number);
            // $Transfer_branch_code->tr_status_from = $sRow->tr_status_get;
            // $Transfer_branch_code->tr_status_to = $sRow->tr_status_get;
            // $Transfer_branch_code->save();

            DB::select(" update db_transfer_branch_code set tr_status_from=".$sRow->tr_status_get.",tr_status_to=".$sRow->tr_status_get." where tr_number='".$sRow->tr_number."'");

            // dd($Transfer_branch_code->id);

// ดึงเข้าคลัง สาขาที่รับโอนมา กรณี อนุมัติเท่านั้น
            if(request('approve_status')==1){

// นำเข้า Stock
                 $products = DB::select("

                    SELECT
                    sum(amt_get) AS sum_amt,
                    db_transfer_branch_get_products_receive.id,
                    db_transfer_branch_get_products_receive.transfer_branch_get_id_fk,
                    db_transfer_branch_get_products_receive.transfer_branch_get_products,
                    db_transfer_branch_get_products_receive.product_id_fk,
                    db_transfer_branch_get_products_receive.lot_number,
                    db_transfer_branch_get_products_receive.lot_expired_date,
                    db_transfer_branch_get_products_receive.amt_get,
                    db_transfer_branch_get_products_receive.product_unit_id_fk,
                    db_transfer_branch_get_products_receive.branch_id_fk,
                    db_transfer_branch_get_products_receive.warehouse_id_fk,
                    db_transfer_branch_get_products_receive.zone_id_fk,
                    db_transfer_branch_get_products_receive.shelf_id_fk,
                    db_transfer_branch_get_products_receive.shelf_floor,
                    db_transfer_branch_get_products_receive.action_user,
                    db_transfer_branch_get_products_receive.action_date,
                    db_transfer_branch_get_products_receive.approver,
                    db_transfer_branch_get_products_receive.approve_status,
                    db_transfer_branch_get_products_receive.approve_date,
                    db_transfer_branch_get_products_receive.created_at,
                    db_transfer_branch_get_products_receive.updated_at,
                    db_transfer_branch_get_products_receive.deleted_at
                    from db_transfer_branch_get_products_receive
                    WHERE transfer_branch_get_id_fk in ($id)
                    GROUP BY transfer_branch_get_id_fk,branch_id_fk,warehouse_id_fk,zone_id_fk,shelf_floor

                    ");

          // check ก่อนว่ามีใน ชั้นนั้นๆ หรือยัง ถ้ามี update ถ้ายังไม่มี add 
                 foreach ($products as $key => $p) {

                          $_check=DB::table('db_stocks')
                          ->where('business_location_id_fk', $sRow->business_location_id_fk)
                          ->where('branch_id_fk', $p->branch_id_fk)
                          ->where('product_id_fk', $p->product_id_fk)
                          ->where('lot_number', $p->lot_number)
                          ->where('lot_expired_date', $p->lot_expired_date)
                          ->where('warehouse_id_fk', $p->warehouse_id_fk)
                          ->where('zone_id_fk', $p->zone_id_fk)
                          ->where('shelf_id_fk', $p->shelf_id_fk)
                          ->where('shelf_floor', $p->shelf_floor)
                          ->get();
                          if($_check->count() == 0){

                              $stock = new  \App\Models\Backend\Check_stock;
                              $stock->business_location_id_fk = $sRow->business_location_id_fk ;
                              $stock->product_id_fk = $p->product_id_fk ;
                              $stock->lot_number = $p->lot_number ;
                              $stock->lot_expired_date = $p->lot_expired_date ;
                              $stock->amt = $p->sum_amt ;
                              $stock->product_unit_id_fk = $p->product_unit_id_fk ;
                              $stock->branch_id_fk = $p->branch_id_fk ;
                              $stock->warehouse_id_fk = $p->warehouse_id_fk ;
                              $stock->zone_id_fk = $p->zone_id_fk ;
                              $stock->shelf_id_fk = $p->shelf_id_fk ;
                              $stock->shelf_floor = $p->shelf_floor ;
                              $stock->date_in_stock = date("Y-m-d");
                              $stock->created_at = date("Y-m-d H:i:s");
                              $stock->save();


                          }else{

                              DB::table('db_stocks')
                                  ->where('branch_id_fk', $p->branch_id_fk)
                                  ->where('product_id_fk', $p->product_id_fk)
                                  ->where('lot_number', $p->lot_number)
                                  ->where('lot_expired_date', $p->lot_expired_date)
                                  ->where('warehouse_id_fk', $p->warehouse_id_fk)
                                  ->where('zone_id_fk', $p->zone_id_fk)
                                  ->where('shelf_id_fk', $p->shelf_id_fk)
                                  ->where('shelf_floor', $p->shelf_floor)
                                ->update(array(
                                  'amt' => DB::raw( ' amt + '.$p->sum_amt )
                                ));

                          }
                          
                      

                 }

         
               
            }


          \DB::commit();

           return redirect()->to(url("backend/transfer_branch_get/".$sRow->id."/edit"));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Transfer_branch_getController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {

       if($id){
        
        DB::select(" UPDATE `db_transfer_branch_get_products` SET `product_amt_receive`='0' WHERE `product_amt_receive` is null ; ");

        $r = DB::select("SELECT *  FROM `db_transfer_branch_get_products_receive` WHERE (`id`='$id')");

        DB::select("DELETE FROM `db_transfer_branch_get_products_receive` WHERE (`id`='$id')");

        DB::select("
            UPDATE db_transfer_branch_get_products SET product_amt_receive=(SELECT sum(amt_get) as sum_amt FROM `db_transfer_branch_get_products_receive` WHERE transfer_branch_get_id_fk=".$r[0]->transfer_branch_get_id_fk." AND product_id_fk=".$r[0]->product_id_fk.")
            WHERE id=".$r[0]->transfer_branch_get_products." ;
        ");

        // DB::select(" UPDATE `db_transfer_branch_get_products` SET `get_status`='1' where id=".$r[0]->transfer_branch_get_products." AND product_amt=product_amt_receive; ");

        // DB::select(" UPDATE `db_transfer_branch_get_products` SET `get_status`='2' where id=".$r[0]->transfer_branch_get_products." AND product_amt<product_amt_receive ; ");

        // DB::select(" UPDATE `db_transfer_branch_get` SET `tr_status_get`='1' where id=".$r[0]->transfer_branch_get_id_fk." ; ");

        // DB::select(" UPDATE
        //     db_transfer_branch_get
        //     Inner Join db_transfer_branch_get_products ON db_transfer_branch_get.id = db_transfer_branch_get_products.transfer_branch_get_id_fk
        //     SET
        //     db_transfer_branch_get.tr_status_get=db_transfer_branch_get_products.get_status
        //     WHERE db_transfer_branch_get.id=".$r[0]->transfer_branch_get_id_fk." AND db_transfer_branch_get_products.get_status=1 ");

           // $ch = DB::select("SELECT count(*) as cnt FROM `db_transfer_branch_get_products` WHERE product_amt=product_amt_receive AND transfer_branch_get_id_fk=".$r[0]->transfer_branch_get_id_fk." ");

           //    if($ch[0]->cnt>0){
           //       DB::select(" UPDATE `db_transfer_branch_get` SET tr_status_get=3 where id=".$r[0]->transfer_branch_get_id_fk." ");
           //    }else{
           //      DB::select(" UPDATE `db_transfer_branch_get_products` SET `get_status`='2' WHERE transfer_branch_get_id_fk=".$r[0]->transfer_branch_get_id_fk." ");

           //      $r2 = DB::select(" select tr_number from `db_transfer_branch_get` WHERE id =".$r[0]->transfer_branch_get_id_fk." ");

           //      // 5  รอรับสินค้าคืน
           //      DB::select(" UPDATE `db_transfer_branch_code` SET `tr_status_from`='5' WHERE (`tr_number`='".$r2[0]->tr_number."') ");

           //    }


              DB::select(" UPDATE `db_transfer_branch_get_products` SET `product_amt_receive`='0' WHERE `product_amt_receive` is null ; ");
              DB::select(" UPDATE `db_transfer_branch_get_products` SET `product_amt`='0' WHERE `product_amt` is null ; ");

              $ch1 = DB::select("SELECT sum(product_amt) as r FROM `db_transfer_branch_get_products`  WHERE transfer_branch_get_id_fk=".$r[0]->transfer_branch_get_id_fk." GROUP BY transfer_branch_get_id_fk ; ");

              $ch2 = DB::select("SELECT sum(product_amt_receive) as r FROM `db_transfer_branch_get_products`  WHERE transfer_branch_get_id_fk=".$r[0]->transfer_branch_get_id_fk." GROUP BY transfer_branch_get_id_fk ; ");


              if($ch1[0]->r == $ch2[0]->r){
              
                $r2 =  DB::select(" SELECT tr_number from `db_transfer_branch_get`  where id=".$r[0]->transfer_branch_get_id_fk." ");
                DB::select(" UPDATE `db_transfer_branch_code` SET tr_status_from='6' where tr_number='".$r2[0]->tr_number."' ");

                DB::select(" UPDATE `db_transfer_branch_get_products` SET `get_status`='1' WHERE transfer_branch_get_id_fk=".$r[0]->transfer_branch_get_id_fk." ");
              
              }else{
                
                $r2 =  DB::select(" SELECT tr_number from `db_transfer_branch_get`  where id=".$r[0]->transfer_branch_get_id_fk." ");
                DB::select(" UPDATE `db_transfer_branch_code` SET tr_status_from='5' where tr_number='".$r2[0]->tr_number."' ");

                DB::select(" UPDATE `db_transfer_branch_get_products` SET `get_status`='2' WHERE transfer_branch_get_id_fk=".$r[0]->transfer_branch_get_id_fk." ");
              }



      }

      // $sRow = \App\Models\Backend\Transfer_branch_get::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){


        if(!empty($req->business_location_id_fk)){
           $w01 = " AND db_transfer_branch_get.business_location_id_fk=".$req->business_location_id_fk ;
        }else{
           $w01 = "";
        }

        if(!empty($req->branch_id_fk)){
           $w02 = " AND db_transfer_branch_get.branch_id_fk = ".$req->branch_id_fk." " ;
        }else{
           $w02 = " AND (db_transfer_branch_get.branch_id_fk = ".\Auth::user()->branch_id_fk.")  " ;
        }

        if(!empty($req->get_from_branch_id_fk)){
           $w022 = " AND db_transfer_branch_get.get_from_branch_id_fk = ".$req->get_from_branch_id_fk." " ;
        }else{
           $w022 = "" ;
        }

        if(!empty($req->tr_number)){
           $w03 = " AND db_transfer_branch_get.tr_number LIKE '%".$req->tr_number."%'  " ;
        }else{
           $w03 = "";
        }

        if(isset($req->tr_status_get)){
           $w04 = " AND db_transfer_branch_get.tr_status_get = ".$req->tr_status_get."  " ;
        }else{
           $w04 = "";
        }

        if(!empty($req->startDate) && !empty($req->endDate)){
           $w05 = " and date(db_transfer_branch_get.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
        }else{
           $w05 = "";
        }

        if(!empty($req->action_user)){
           $w06 = " AND db_transfer_branch_get.action_user = ".$req->action_user." " ;
        }else{
           $w06 = "";
        }

        // if(!empty($req->supplier_id_fk)){
        //    $w07 = " AND db_transfer_branch_get.supplier_id_fk = ".$req->supplier_id_fk." " ;
        // }else{
        //    $w07 = "";
        // }

// ต้องรวมข้อมูลที่เกิดจากการปฏิเสธการับด้วย ที่สาขาตัวเองส่งไป

      // $sTable = \App\Models\Backend\Transfer_branch_get::search()->orderBy('id', 'asc');
      $sTable = DB::select("
            SELECT db_transfer_branch_get.*
            FROM
            db_transfer_branch_get
            WHERE 1 
            ".$w01."
            ".$w02."
            ".$w022."
            ".$w03."
            ".$w04."
            ".$w05."
            ".$w06."
            ORDER BY updated_at DESC 
         ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('get_from_branch', function($row) {
        $sD = DB::select(" select * from branchs where id=".$row->get_from_branch_id_fk." ");
        return $sD[0]->b_name;
      })
      ->addColumn('action_user', function($row) {
        $c = DB::select(" select * from ck_users_admin where id=".$row->action_user." ");
        return $c[0]->name;
      })
      ->addColumn('approver', function($row) {
          if($row->approver){
                $c = DB::select(" select * from ck_users_admin where id=".$row->approver." ");
            return $c[0]->name;
          }else{
            return '-';
          }
      })
      ->addColumn('tr_status_get', function($row) {
       if($row->tr_status_get){
             $s = \App\Models\Backend\Transfer_branch_status::where('id',$row->tr_status_get)->get();
             return $s[0]->txt_to?$s[0]->txt_to:$s[0]->txt_from;
          }
       })
      ->escapeColumns('tr_status_get')

      ->addColumn('tr_status_code', function($row) {
       if($row->tr_status_get){
             return $row->tr_status_get;
          }
       })
      ->escapeColumns('tr_status_code')
      
     ->addColumn('approve_date', function($row) {
        if($row->approve_date){
        return date("Y-m-d",strtotime($row->approve_date));
        }else{
            return '-';
        }
      })
      ->addColumn('created_at', function($row) {
        return date("Y-m-d",strtotime($row->created_at));
      })
      ->make(true);
    }



    public function noget($id)
    {
      // dd($id);

        // return View('backend.transfer_branch_get.noget');

      $sRow = \App\Models\Backend\Transfer_branch_get::find($id);
       // dd($sRow);
        $sBusiness_location = \App\Models\Backend\Business_location::get();
        $sBranchs = \App\Models\Backend\Branchs::get();
        $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();
        $sUserAdmin = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");
        $sUserAdmin_ALL = DB::select(" select * from ck_users_admin  ");

        $ch1 = DB::select("SELECT sum(product_amt) as r FROM `db_transfer_branch_get_products`  WHERE transfer_branch_get_id_fk=".$id." GROUP BY transfer_branch_get_id_fk ; ");

        $ch2 = DB::select("SELECT sum(product_amt_receive) as r FROM `db_transfer_branch_get_products`  WHERE transfer_branch_get_id_fk=".$id." GROUP BY transfer_branch_get_id_fk ; ");

        if($ch1[0]->r == $ch2[0]->r){
          $ch_get_products = 1;
        }else{
          $ch_get_products = 0;
        }

       return View('backend.transfer_branch_get.noget')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id,
           'sBusiness_location'=>$sBusiness_location,
           'sBranchs'=>$sBranchs,
           'sProductUnit'=>$sProductUnit,
           'sUserAdmin'=>$sUserAdmin,
           'sUserAdmin_ALL'=>$sUserAdmin_ALL,
           'ch_get_products'=>$ch_get_products,
        ) );


    }


}
