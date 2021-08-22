<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use App\Http\Controllers\backend\AjaxController;

class Transfer_branchController extends Controller
{

    public function index(Request $request)
    {
// dd();
        $sBusiness_location = \App\Models\Backend\Business_location::get();

        $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1");

        $sPermission  = \Auth::user()->permission ;
        $User_branch_id = \Auth::user()->branch_id_fk;
        $sBranchs = \App\Models\Backend\Branchs::get();
        $Warehouse = \App\Models\Backend\Warehouse::get();
        $Zone = \App\Models\Backend\Zone::get();
        $Shelf = \App\Models\Backend\Shelf::get();

        $Transfer_branch_status = \App\Models\Backend\Transfer_branch_status::get();

        $sTransfer_chooseAll = \App\Models\Backend\Transfer_choose_branch::where('action_user','=',(\Auth::user()->id))->get();
        // dd(count($sTransfer_chooseAll));

        $sTransfer_choose = \App\Models\Backend\Transfer_choose_branch::where('branch_id_fk_to','0')->where('action_user','=',\Auth::user()->id)->get();
        // dd(count($sTransfer_choose));
        // dd($User_branch_id);
        // dd($sTransfer_chooseAll);
        $b_l = \App\Models\Backend\Branchs::where('id',$User_branch_id)->get();
        // dd($b_l[0]->business_location_id_fk);
        $business_location_id_fk = $b_l[0]->business_location_id_fk;

        $tr_number = DB::select(" SELECT tr_number FROM `db_transfer_branch_code` where branch_id_fk=".(\Auth::user()->branch_id_fk)." ");

        // dd($tr_number);

        $sAction_user = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");

        return View('backend.transfer_branch.index')->with(
        array(
           'Products'=>$Products,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sTransfer_choose'=>$sTransfer_choose,'sTransfer_chooseAll'=>$sTransfer_chooseAll,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id,
           'business_location_id_fk'=>$business_location_id_fk,
           'sBusiness_location'=>$sBusiness_location,
           'Transfer_branch_status'=>$Transfer_branch_status,
           'tr_number'=>$tr_number,
           'sAction_user'=>$sAction_user,
           'sPermission'=>$sPermission,

        ) );
      
    }

 public function create()
    {
      $receive_id = \App\Models\Backend\General_receive::get();
      $Products = \App\Models\Backend\Products::get();
      $ProductsUnit = \App\Models\Backend\Product_unit::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();
      $sPermission  = \Auth::user()->permission ;
      return View('backend.transfer_branch.form')->with(
        array(
           'receive_id'=>$receive_id,'Products'=>$Products,'ProductsUnit'=>$ProductsUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,
           'Shelf'=>$Shelf,
           'sPermission'=>$sPermission,
        ) );
    }
    public function store(Request $request)
    {

      if(isset($request->save_select_to_transfer)){
        // dd($request->all());

        for ($i=0; $i < count($request->id) ; $i++) { 
            $Check_stock = \App\Models\Backend\Check_stock::find($request->id[$i]);
            // echo $Check_stock->product_id_fk;
            $sRow = new \App\Models\Backend\Transfer_choose_branch;
            $sRow->branch_id_fk    = request('branch_id_select_to_transfer');
            $sRow->branch_id_fk_to    = request('branch_id_select_to_transfer_to');
            $sRow->stocks_id_fk    = $Check_stock->id;
            $sRow->product_id_fk    = $Check_stock->product_id_fk;
            $sRow->lot_number    = $Check_stock->lot_number;
            $sRow->lot_expired_date    = $Check_stock->lot_expired_date;
            $sRow->amt    = request('amt_transfer')[$i];
            $sRow->product_unit_id_fk    = $Check_stock->product_unit_id_fk;
            $sRow->date_in_stock    = $Check_stock->date_in_stock;
            $sRow->warehouse_id_fk    = $Check_stock->warehouse_id_fk;
            $sRow->zone_id_fk    = $Check_stock->zone_id_fk;
            $sRow->shelf_id_fk    = $Check_stock->shelf_id_fk;
            $sRow->shelf_floor    = $Check_stock->shelf_floor;
            $sRow->action_user = \Auth::user()->id;
            $sRow->action_date = date('Y-m-d H:i:s');
            $sRow->created_at = date('Y-m-d H:i:s');
            if(!empty(request('amt_transfer')[$i])){
              $sRow->save();
            }
          }

          DB::update(" update db_transfer_choose_branch set branch_id_fk_to=".request('branch_id_select_to_transfer_to')." ");

          return redirect()->to(url("backend/transfer_branch"));

      }else if(isset($request->save_set_to_warehouse)){

        // dd($request->all());
              if($request->save_set_to_warehouse==1){
                // DB::update(" 
                //     UPDATE db_transfer_choose_branch 
                //     SET branch_id_fk = ".$request->branch_id_set_to_warehouse.",
                //      warehouse_id_fk = ".$request->warehouse_id_fk_c."  ,
                //      zone_id_fk = ".$request->zone_id_fk_c."  ,
                //      shelf_id_fk = ".$request->shelf_id_fk_c."  
                //     where id=".$request->id_set_to_warehouse." 
                // ");
                DB::update(" 
                    UPDATE db_transfer_choose_branch 
                    SET branch_id_fk_to = ".$request->branch_id_fk_c_to."
                    where action_user=".(\Auth::user()->id)." 
                ");                
              }

              return redirect()->to(url("backend/transfer_branch"));

      }else if(isset($request->save_set_to_warehouse_e)){

        // dd($request->all());
              if($request->save_set_to_warehouse_e==1){
                // DB::update(" 
                //     UPDATE db_transfer_choose_branch 
                //     SET branch_id_fk = ".$request->branch_id_set_to_warehouse_e.",
                //      warehouse_id_fk = ".$request->warehouse_id_fk_c_e."  ,
                //      zone_id_fk = ".$request->zone_id_fk_c_e."  ,
                //      shelf_id_fk = ".$request->shelf_id_fk_c_e."  
                //     where id=".$request->id_set_to_warehouse_e." 
                // ");
                 DB::update(" 
                    UPDATE db_transfer_choose_branch 
                    SET branch_id_fk_to = ".$request->branch_id_fk_c_e_to."
                    where action_user=".(\Auth::user()->id)." 
                ");
              }

              return redirect()->to(url("backend/transfer_branch"));
      
      }else if(isset($request->save_select_to_cancel)){     

          DB::update(" UPDATE db_transfer_branch_code 
            SET 
            approve_status=2 , note = '".$request->note_to_cancel."'
            WHERE id=".$request->id_to_cancel." ; ");
          
          return redirect()->to(url("backend/transfer_branch"));

      }else{
         // dd($request->all());
        return $this->form();
      }
      
    }

    public function edit($id)
    {

        // dd($id);
        $sRow = \App\Models\Backend\Transfer_branch_code::find($id);
        $sPermission  = \Auth::user()->permission ;
        // dd($sRow);
        return View('backend.transfer_branch.form')->with(
        array(
           'sRow'=>$sRow,
            'id'=>$id,
            'sPermission'=>$sPermission,

        ) );
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      if(!empty($request->approve_transfer_branch_code)){
            return $this->form($id);

      }else
      if(!empty($request->approve_status_cutstock) && $request->approve_status==1){
          // dd($request->approve_status);

        $rsBranch_details = DB::select("  
           select * from db_transfer_branch_details where transfer_branch_code_id = ".$request->id." ");
        $arr1 = [];
        foreach ($rsBranch_details as $key => $value) {
          array_push($arr1, $value->stocks_id_fk);
        }

        $arr1 = implode(",", $arr1);

        $rsStock = DB::select(" select * from  db_stocks  where id in (".$arr1.")  ");
              // dd($rsStock);
        foreach ($rsStock as $key => $value) {
            DB::update(" UPDATE db_transfer_branch_details SET stock_amt_before_up =".$value->amt." , stock_date_before_up = '".$value->date_in_stock."'  where transfer_branch_code_id = ".$request->id." and stocks_id_fk = ".$value->id."  ");
        }

         $rsBranch_details = DB::select("  
           select * from db_transfer_branch_details where transfer_branch_code_id = ".$request->id." ");
         foreach ($rsBranch_details as $key => $value) {
              DB::update(" UPDATE db_stocks SET amt = (amt - ".$value->amt.") where id =".$value->stocks_id_fk."  ");
         }


         DB::select(" UPDATE `db_transfer_branch_code` SET `tr_status_from`='2' WHERE (`id`='".$rsBranch_details[0]->transfer_branch_code_id."') ");


      }else{

        return $this->form($id);

      }



    }

   public function form($id=NULL)
    {

      $id=request('id');
      // dd($id);
      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow = \App\Models\Backend\Transfer_branch_code::find($id);
          }else{
            $sRow = new \App\Models\Backend\Transfer_branch_code;
          }


// Check Stock อีกครั้งก่อน เพื่อดูว่าสินค้ายังมีพอให้ตัดหรือไม่
          $db_select = DB::select(" 
            SELECT * FROM `db_transfer_branch_details` WHERE transfer_branch_code_id=".$id."
             ");
          foreach ($db_select as $key => $v) {

                 $fnCheckStock = new  AjaxController();
                 $r_check_stcok = $fnCheckStock->fnCheckStock(
                  $sRow->branch_id_fk,
                  $v->product_id_fk,
                  $v->amt,
                  $v->lot_number,
                  $v->lot_expired_date,
                  $v->warehouse_id_fk,
                  $v->zone_id_fk,
                  $v->shelf_id_fk,
                  $v->shelf_floor);
                // return $r_check_stcok;
                if($r_check_stcok==0){
                  return redirect()->to(url("backend/transfer_branch/".$id."/edit"))->with(['alert'=>\App\Models\Alert::myTxt("สินค้าในคลังไม่เพียงพอ")]);
                }

          }



          $sRow->approver    = \Auth::user()->id;
          $sRow->approve_status    = request('approve_status');
          $sRow->approve_note    = request('approve_note');
          $sRow->tr_status_from    = 2 ;
          $sRow->approve_date = date('Y-m-d H:i:s');

          $sRow->save();



/*

CREATE TABLE `db_transfer_branch_get` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_location_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>dataset_business_location>id',
  `branch_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>branchs>id',
  `get_from_branch_id_fk` int(11) DEFAULT '0' COMMENT 'รับจากสาขาใด',
  `tr_number` varchar(255) DEFAULT NULL COMMENT 'รหัสการโอนย้าย',
  `action_user` int(11) DEFAULT NULL,
  `approver` int(11) DEFAULT '0' COMMENT 'รหัสผู้อนุมัติ Ref>ck_users_admin>id',
  `approve_date` datetime DEFAULT NULL COMMENT 'วันที่อนุมัติ',
  `approve_status` int(1) DEFAULT '0' COMMENT '1=อนุมัติ 5=ไม่อนุมัติ',
  `note1` text COMMENT 'หมายเหตุ',
  `note2` text COMMENT 'หมายเหตุของส่วนการอนุมัติ',
  `note3` text COMMENT 'หมายเหตุของส่วนการอนุมัติ กรณีรับคืน',
  `tr_status_get` int(1) DEFAULT '1' COMMENT 'Ref_dataset_transfer_branch_status>id (ฝั่งรับ)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8
*/
          $Transfer_branch_get = new \App\Models\Backend\Transfer_branch_get;
          $Transfer_branch_get->business_location_id_fk    = $sRow->business_location_id_fk;
          $Transfer_branch_get->branch_id_fk    = $sRow->to_branch_id_fk;
          $Transfer_branch_get->get_from_branch_id_fk    = $sRow->branch_id_fk;
          $Transfer_branch_get->tr_number    = $sRow->tr_number;
          $Transfer_branch_get->action_user    = \Auth::user()->id ;
          $Transfer_branch_get->note1    = $sRow->note;
          $Transfer_branch_get->created_at    = $sRow->created_at;

// อนุมัติ เท่านั้น
          if($sRow->approve_status==1){

    // สาขาปลายทางที่รับ 
              
              $Transfer_branch_get->save();


              DB::select("  UPDATE `db_transfer_branch_details_log` SET remark=1 WHERE transfer_branch_code_id=".$sRow->id."  ");

              // เมื่อมีการรับเข้าแล้ว นำเข้า Stock ด้วย 


            /*

            CREATE TABLE `db_transfer_branch_details` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `transfer_branch_code_id` int(11) DEFAULT '0' COMMENT 'Ref>db_transfer_warehouses_code >id',
            `stocks_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>db_stocks>id',
            `product_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>products>id',
            `lot_number` varchar(255) DEFAULT NULL,
            `lot_expired_date` date DEFAULT NULL COMMENT 'วันหมดอายุของ lot นี้',
            `amt` int(11) DEFAULT '0' COMMENT 'จำนวนโอน',
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
            `stock_amt_before_up` int(11) DEFAULT '0' COMMENT 'จำนวนคงคลังก่อนอัพเดต',
            `stock_date_before_up` date DEFAULT NULL COMMENT 'วันที่ในสต๊อดก่อนอัพเดต',
            `created_at` timestamp NULL DEFAULT NULL,


            CREATE TABLE `db_transfer_branch_get_products` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `transfer_branch_get_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>db_transfer_branch_get>id',
              `product_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>products>id',
              `product_amt` int(11) DEFAULT '0' COMMENT 'จำนวน',
              `product_amt_receive` int(11) DEFAULT '0' COMMENT 'จำนวนที่รับมาแล้ว',
              `product_unit` int(11) DEFAULT '0' COMMENT 'หน่วยนับหลัก',
              `get_status` int(1) DEFAULT '0' COMMENT '1=ได้รับสินค้าครบแล้ว 2=ยังค้างรับสินค้า 3=ยกเลิกรายการสินค้านี้',
              `created_at` timestamp NULL DEFAULT NULL,
              */
            // ส่วนของสินค้า ก็รับเข้ามาจากใบโอนสินค้าจากสาชาต้นทางเลย
              $r_product = DB::select("  SELECT * FROM `db_transfer_branch_details` WHERE transfer_branch_code_id=".$sRow->id."  ");

// ต้องลูปเข้าตามรหัส transfer_branch_code_id กรณีถ้ามีสินค้าหลายรายการ

              if($r_product){

                foreach ($r_product as $key => $value) {

                       $Transfer_branch_get_products = new \App\Models\Backend\Transfer_branch_get_products;
                       $Transfer_branch_get_products->transfer_branch_get_id_fk = $Transfer_branch_get->id;
                       // สินค้ามาจากตาราง db_transfer_branch_details
                       $Transfer_branch_get_products->product_id_fk = $value->product_id_fk;
                       $Transfer_branch_get_products->product_amt = $value->amt;
                       $Transfer_branch_get_products->product_unit = $value->product_unit_id_fk;
                       $Transfer_branch_get_products->created_at    = date("Y-m-d H:i:s");
                       $Transfer_branch_get_products->save();

                }

              }


          // เมื่อมีการอนุมัติแล้ว ต้องตัดออกจาก Stock 

           if($sRow->approve_status==1){

               // ตัด Stock 
              $db_select = DB::select(" 
                SELECT * FROM `db_transfer_branch_details` WHERE transfer_branch_code_id=".$sRow->id."
                 ");

              foreach ($db_select as $key => $v) {
              
                       $_choose=DB::table('db_stocks')
                      ->where('branch_id_fk', $sRow->branch_id_fk)
                      ->where('product_id_fk', $v->product_id_fk)
                      ->where('lot_number', $v->lot_number)
                      ->where('lot_expired_date', $v->lot_expired_date)
                      ->where('warehouse_id_fk', $v->warehouse_id_fk)
                      ->where('zone_id_fk', $v->zone_id_fk)
                      ->where('shelf_id_fk', $v->shelf_id_fk)
                      ->where('shelf_floor', $v->shelf_floor)
                      ->get();
                      if($_choose->count() > 0){

                          // DB::select(" UPDATE db_stocks SET amt=amt-(".$v->amt.") WHERE branch_id_fk='".$sRow->branch_id_fk."' and product_id_fk='".$v->product_id_fk."' and lot_number='".$v->lot_number."' and lot_expired_date='".$v->lot_expired_date."' ");

                           DB::table('db_stocks')
                                  ->where('branch_id_fk', $sRow->branch_id_fk)
                                  ->where('product_id_fk', $v->product_id_fk)
                                  ->where('lot_number', $v->lot_number)
                                  ->where('lot_expired_date', $v->lot_expired_date)
                                  ->where('warehouse_id_fk', $v->warehouse_id_fk)
                                  ->where('zone_id_fk', $v->zone_id_fk)
                                  ->where('shelf_id_fk', $v->shelf_id_fk)
                                  ->where('shelf_floor', $v->shelf_floor)
                                ->update(array(
                                  'amt' => DB::raw( ' amt - '.$v->amt )
                                ));


                       
                      }
       
              }

              
           }



            
          }

          \DB::commit();

           // return redirect()->to(url("backend/transfer_branch/".$id."/edit?list_id=".$id.""));
           return redirect()->to(url("backend/transfer_branch"));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Transfer_branchController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Transfer_branch::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){

      if(isset($req->id)){
            $sTable = \App\Models\Backend\Transfer_branch::where('id',$req->id)->search()->orderBy('id', 'asc');
      }else{
           $sTable = \App\Models\Backend\Transfer_branch::search()->orderBy('id', 'asc');
      }
    

      $sQuery = \DataTables::of($sTable);
      return $sQuery
     ->addColumn('product_name', function($row) {
        
          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$row->product_id_fk." AND lang_id=1");

        return @$Products[0]->product_code." <br> ".@$Products[0]->product_name;

      })
      // ->addColumn('lot_expired_date', function($row) {
      //   $d = strtotime($row->lot_expired_date); 
      //   return date("d/m/", $d).(date("Y", $d)+543);
      // })
      // ->addColumn('action_date', function($row) {
      //   $d = strtotime($row->action_date); 
      //   return date("d/m/", $d).(date("Y", $d)+543);
      // })
       ->escapeColumns('product_name')
      ->addColumn('warehouses', function($row) {
        $Check_stock = \App\Models\Backend\Check_stock::where('product_id_fk',$row->product_id_fk)->where('lot_number',$row->lot_number)->first();
        // return $Check_stock->branch_id_fk;
        $sBranchs = DB::select(" select * from branchs where id=".$Check_stock->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$Check_stock->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$Check_stock->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$Check_stock->shelf_id_fk." ");
        return str_replace(">","/",@$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น '.@$Check_stock->shelf_floor);
      })  
      ->escapeColumns('warehouses')
      ->addColumn('amt_in_warehouse', function($row) {
        if($row->stocks_id_fk!=''){
          $Check_stock = DB::select(" select * from db_stocks where id=".$row->stocks_id_fk." ");
          return @$Check_stock[0]->amt;
        }
      }) 
      ->addColumn('to_branch', function($row) {
          $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
          return @$sBranchs[0]->b_name;
      })      
      // ->addColumn('updated_at', function($row) {
      //   return is_null($row->updated_at) ? '-' : $row->updated_at;
      // })
      ->make(true);
    }



    public function DatatableProduct(Request $req){

      $sTable = DB::select(" select * from db_transfer_branch_details where transfer_branch_code_id=".$req->transfer_branch_code_id."  ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
     ->addColumn('product_name', function($row) {
        
          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$row->product_id_fk." AND lang_id=1");

        return @$Products[0]->product_code." <br> ".@$Products[0]->product_name;

      })
      // ->addColumn('lot_expired_date', function($row) {
      //   $d = strtotime($row->lot_expired_date); 
      //   return date("d/m/", $d).(date("Y", $d)+543);
      // })
      // ->addColumn('action_date', function($row) {
      //   $d = strtotime($row->action_date); 
      //   return date("d/m/", $d).(date("Y", $d)+543);
      // })
       ->escapeColumns('product_name')
      ->addColumn('warehouses', function($row) {
        $Check_stock = \App\Models\Backend\Check_stock::where('product_id_fk',$row->product_id_fk)->where('lot_number',$row->lot_number)->first();
        // return $Check_stock->branch_id_fk;
        $sBranchs = DB::select(" select * from branchs where id=".$Check_stock->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$Check_stock->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$Check_stock->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$Check_stock->shelf_id_fk." ");
        return str_replace(">","/",@$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น '.@$Check_stock->shelf_floor);
      })  
      ->escapeColumns('warehouses')
      ->addColumn('amt_in_warehouse', function($row) {
        if($row->stocks_id_fk!=''){
          $Check_stock = DB::select(" select * from db_stocks where id=".$row->stocks_id_fk." ");
          return @$Check_stock[0]->amt;
        }
      }) 
      ->addColumn('to_branch', function($row) {
          $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
          return @$sBranchs[0]->b_name;
      })      
      // ->addColumn('updated_at', function($row) {
      //   return is_null($row->updated_at) ? '-' : $row->updated_at;
      // })
      ->make(true);
    }


}
