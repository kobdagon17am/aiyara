<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Session;

class Transfer_branch_codeController extends Controller
{

    public function index(Request $request)
    {

       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sBranchs = \App\Models\Backend\Branchs::get();

        $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1");

        $Warehouse = \App\Models\Backend\Warehouse::get();
        $Zone = \App\Models\Backend\Zone::get();
        $Shelf = \App\Models\Backend\Shelf::get();

        $User_branch_id = \Auth::user()->branch_id_fk;

        $Transfer_branch_status = \App\Models\Backend\Transfer_branch_status::get();


        return View('backend.transfer_branch_code.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,
           'Products'=>$Products,
           'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,
           'User_branch_id'=>$User_branch_id,
           'Transfer_branch_status'=>$Transfer_branch_status,
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
      return View('backend.transfer_branch_code.form')->with(
        array(
           'receive_id'=>$receive_id,'Products'=>$Products,'ProductsUnit'=>$ProductsUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf
        ) );
    }
    public function store(Request $request)
    {
      // dd($request->all());

              // return $this->form();
              if($request->save_set_to_warehouse_c_e==1){


                      $Transfer_branch_code = new \App\Models\Backend\Transfer_branch_code;
                      $Transfer_branch_code->business_location_id_fk = request('business_location_id_fk');
                      $Transfer_branch_code->branch_id_fk = request('branch_id_fk');
                      $Transfer_branch_code->to_branch_id_fk = request('branch_id_fk_to');
                      $Transfer_branch_code->note = request('note');
                      $Transfer_branch_code->action_date = date("Y-m-d");
                      $Transfer_branch_code->action_user = \Auth::user()->id;
                      $Transfer_branch_code->created_at = date("Y-m-d H:i:s");
                      $Transfer_branch_code->save();

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
                      // รหัสอ้างอิงเอกสาร (แสดงรหัสอ้างอิงในตารางที่เชื่อมโยงกันทุกตาราง) REF+BL+branch+stock_type_id_fk+(yy+mm+dd)+ref_table_id
                      $stock_type_id_fk = 8;
                      $ref_table_id = $Transfer_branch_code->id ;
                      $ref_doc = 'TR'.$Transfer_branch_code->business_location_id_fk.$Transfer_branch_code->branch_id_fk.$stock_type_id_fk.date('ymd').$ref_table_id;
                      // DB::update(" update db_transfer_branch_code set tr_number=?,to_branch_id_fk=? where id=? ",["TR".sprintf("%05d",$Transfer_branch_code->id),request('branch_id_fk_to'),$Transfer_branch_code->id]);
                      DB::update(" update db_transfer_branch_code set tr_number=? where id=? ",[$ref_doc,$Transfer_branch_code->id]);


                      for ($i=0; $i < count($request->transfer_choose_id) ; $i++) {
                          $Transfer_choose = \App\Models\Backend\Transfer_choose_branch::find($request->transfer_choose_id[$i]);
                          DB::insert("
                             insert into db_transfer_branch_details set
                             transfer_branch_code_id=?
                             ,stocks_id_fk=?
                             ,product_id_fk=?
                             ,lot_number=?
                             ,lot_expired_date=?
                             ,amt=?
                             ,product_unit_id_fk=?
                             ,branch_id_fk=?
                             ,warehouse_id_fk=?
                             ,zone_id_fk=?
                             ,shelf_id_fk=?
                             ,shelf_floor=?
                             ,action_user=?
                             ,action_date=?
                             ,created_at=?
                             ",
                            [
                              $Transfer_branch_code->id
                              ,$Transfer_choose->stocks_id_fk
                              ,$Transfer_choose->product_id_fk
                              ,$Transfer_choose->lot_number
                              ,$Transfer_choose->lot_expired_date
                              ,$Transfer_choose->amt
                              ,$Transfer_choose->product_unit_id_fk
                              ,request('branch_id_fk_to')
                              ,$Transfer_choose->warehouse_id_fk
                              ,$Transfer_choose->zone_id_fk
                              ,$Transfer_choose->shelf_id_fk
                              ,$Transfer_choose->shelf_floor
                              ,$Transfer_choose->action_user
                              ,$Transfer_choose->action_date
                              ,date("Y-m-d H:i:s")
                            ]);


                           DB::insert("
                             insert into db_transfer_branch_details_log set
                             transfer_branch_code_id=?
                             ,stocks_id_fk=?
                             ,product_id_fk=?
                             ,lot_number=?
                             ,lot_expired_date=?
                             ,amt=?
                             ,product_unit_id_fk=?
                             ,branch_id_fk=?
                             ,warehouse_id_fk=?
                             ,zone_id_fk=?
                             ,shelf_id_fk=?
                             ,shelf_floor=?
                             ,action_user=?
                             ,action_date=?
                             ,created_at=?
                             ",
                            [
                              $Transfer_branch_code->id
                              ,$Transfer_choose->stocks_id_fk
                              ,$Transfer_choose->product_id_fk
                              ,$Transfer_choose->lot_number
                              ,$Transfer_choose->lot_expired_date
                              ,$Transfer_choose->amt
                              ,$Transfer_choose->product_unit_id_fk
                              ,request('branch_id_fk_to')
                              ,$Transfer_choose->warehouse_id_fk
                              ,$Transfer_choose->zone_id_fk
                              ,$Transfer_choose->shelf_id_fk
                              ,$Transfer_choose->shelf_floor
                              ,$Transfer_choose->action_user
                              ,$Transfer_choose->action_date
                              ,date("Y-m-d H:i:s")
                            ]);

                            DB::update(" DELETE FROM db_transfer_choose_branch where id=? ",[$Transfer_choose->id]);


                      }

                     // DB::update(" DELETE FROM db_transfer_choose_branch where action_user=? ",[\Auth::user()->id]);

              }

              return redirect()->to(url("backend/transfer_branch"));



    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Transfer_branch_code::find($id);

        $receive_id = \App\Models\Backend\General_receive::get();
        $Products = \App\Models\Backend\Products::get();
        $ProductsUnit = \App\Models\Backend\Product_unit::get();
        $Warehouse = \App\Models\Backend\Warehouse::get();
        $Zone = \App\Models\Backend\Zone::get();
        $Shelf = \App\Models\Backend\Shelf::get();
       $Recipient  = DB::select(" select * from ck_users_admin where id=".$sRow->recipient." ");
      return View('backend.transfer_branch_code.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id,'Recipient'=>$Recipient,'receive_id'=>$receive_id,'Products'=>$Products,'ProductsUnit'=>$ProductsUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf
        ) );
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
            $sRow = \App\Models\Backend\Transfer_branch_code::find($id);
          }else{
            $sRow = new \App\Models\Backend\Transfer_branch_code;
          }

          // $sRow->general_receive_id_fk    = request('general_receive_id_fk');
          // $sRow->product_id_fk    = request('product_id_fk');
          // $sRow->amt    = request('amt');
          // $sRow->product_unit_id_fk    = request('product_unit_id_fk');
          // $sRow->warehouse_id_fk    = request('warehouse_id_fk');
          // $sRow->zone_id_fk    = request('zone_id_fk');
          // $sRow->shelf_id_fk    = request('shelf_id_fk');
          // $sRow->recipient    = request('recipient');
          // $sRow->approver    = request('approver');
          // $sRow->approve_status    = request('approve_status');
          // $sRow->getin_date = date('Y-m-d H:i:s');

          // $sRow->created_at = date('Y-m-d H:i:s');
          // $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/.transfer_branch_code./".$sRow->id."/edit"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Transfer_branch_codeController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // DB::select(" DELETE FROM db_transfer_warehouses_details WHERE transfer_branch_code_id=$id ; ");
      // $sRow = \App\Models\Backend\Transfer_branch_code::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // ไม่ได้ลบจริง
      DB::update(" UPDATE db_transfer_branch_code SET approve_status=2 WHERE id=$id ; ");
      // เอาไว้เปิดดูใบโอนได้
      // DB::update(" UPDATE db_transfer_warehouses_details SET deleted_at=now() WHERE transfer_branch_code_id=$id ; ");
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){

      $sPermission  = \Auth::user()->permission;
       if(!empty($req->id)){
           $w00 = " AND db_transfer_branch_code.id=".$req->id ;
        }else{
           $w00 = "";
        }


       if(!empty($req->business_location_id_fk)){
          //  $w01 = " AND db_transfer_branch_code.business_location_id_fk=".$req->business_location_id_fk ;
          $w01 = "";
        }else{
           $w01 = "";
        }

// สาขาที่ดำเนินการ
        if(!empty($req->branch_id_fk)){
           $w02 = " AND db_transfer_branch_code.branch_id_fk = ".$req->branch_id_fk." " ;
          //  dd($w01);
        }else{
           if($sPermission==1 || auth()->user()->position_level != 5){
              $w02 = "";
          }else{
             //  $w02 = "AND (db_transfer_branch_code.branch_id_fk = ".\Auth::user()->branch_id_fk.")" ;
             $userBranch = \Auth::user()->branch_id_fk;
            //  $w02 = "AND (db_transfer_branch_code.branch_id_fk = $userBranch OR db_transfer_branch_code.to_branch_id_fk = $userBranch AND db_transfer_branch_code.approve_status = 1)";
            $w02 = "AND (db_transfer_branch_code.branch_id_fk = $userBranch)";
          }
        }

 // โอนไปให้สาขา
        if(!empty($req->to_branch)){
           $w03 = " AND db_transfer_branch_code.to_branch_id_fk = ".$req->to_branch." " ;

        }else{
           $w03 = "" ;
        }

        if(!empty($req->tr_status_from)){
           $w04 = " AND db_transfer_branch_code.tr_status_from = ".$req->tr_status_from." " ;
        }else{
           $w04 = "" ;
        }

      if(!empty($req->startDate)){
          $w05 = "  AND db_transfer_branch_code.action_date between '".($req->startDate)."' AND  '".($req->endDate)."' ";
      }else{
          $w05 = "";
      }

      if(!empty($req->tr_number)){
          $w06 =  " AND db_transfer_branch_code.tr_number = '".$req->tr_number."' " ;
      }else{
          $w06 = "";
      }

      if(!empty($req->action_user)){
          $w07 =  " AND db_transfer_branch_code.action_user = '".$req->action_user."' " ;
      }else{
          $w07 = "";
      }

      // dd( $w02);

      $sTable = DB::select(" SELECT * FROM db_transfer_branch_code
          WHERE 1
          ".$w00."
          ".$w01."
          ".$w02."
          ".$w03."
          ".$w04."
          ".$w05."
          ".$w06."
          ".$w07."
          ORDER BY CASE
          WHEN approve_status = 0 THEN 0
          WHEN approve_status = 1 THEN 1
          WHEN approve_status = 3 THEN 1
          WHEN approve_status = 2 THEN 4
          ELSE 99 END ASC,id DESC
          ");
          // ORDER BY approve_status ASC, tr_number DESC;

      $sQuery = \DataTables::of($sTable);
       return $sQuery

      // ->addColumn('created_at', function($row) {
      //   if(!is_null(@$row->created_at)){
      //       return date("Y-m-d",strtotime(@$row->created_at));
      //   }else{
      //     return '-';
      //   }
      // })
      // ->escapeColumns('created_at')

      ->addColumn('approve_date', function($row) {
        if(!is_null(@$row->approve_date)){
            return date("Y-m-d",strtotime(@$row->approve_date));
        }else{
          return '-';
        }
      })
      ->escapeColumns('approve_date')


      ->addColumn('action_user', function($row) {
        if(@$row->action_user!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->action_user." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })

      ->addColumn('approver', function($row) {
        if(@$row->approver!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".@$row->approver." ");
           return @$sD[0]->name;
        }else{
          return '-';
        }
      })

      ->addColumn('tr_status_from', function($row) {
         $sD = \App\Models\Backend\Transfer_branch_status::where('id',$row->tr_status_from)->get();
         return @$sD[0]->txt_from;
      })
      ->escapeColumns('tr_status_from')

      ->addColumn('tr_status_to', function($row) {
        if(@$row->tr_status_to){
             $sD = \App\Models\Backend\Transfer_branch_status::where('id',$row->tr_status_to)->get();
             if($sD){
                return @$sD[0]->txt_to;
              }
         }else{
           return '-';
         }
      })
      ->escapeColumns('tr_status_to')

      ->addColumn('to_branch_id', function($row) {
          $b = DB::table('branchs')->where('id',$row->to_branch_id_fk)->first();
          $data = "";
          if($b){
            $data = $b->b_name;
          }
          return $data;
      })

      ->make(true);
    }


}
