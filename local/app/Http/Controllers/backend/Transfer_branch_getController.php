<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use App\Http\Controllers\backend\AjaxController;
use Session;


class Transfer_branch_getController extends Controller
{

    public function index(Request $request)
    {

        // วุฒิสร้าง session
        $menus = DB::table('ck_backend_menu')->select('id')->where('id',82)->first();
        Session::put('session_menu_id', $menus->id);
        Session::put('menu_id', $menus->id);
        $role_group_id = \Auth::user()->role_group_id_fk;
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',@$role_group_id)->where('menu_id_fk',@$menus->id)->first();
        $sC = @$menu_permit->c;
        $sU = @$menu_permit->u;
        $sD = @$menu_permit->d;
        Session::put('sC', $sC);
        Session::put('sU', $sU);
        Session::put('sD', $sD);
        $can_cancel_bill = @$menu_permit->can_cancel_bill;
        $can_cancel_bill_across_day = @$menu_permit->can_cancel_bill_across_day;
        $can_approve = @$menu_permit->can_approve;
        Session::put('can_cancel_bill', $can_cancel_bill);
        Session::put('can_cancel_bill_across_day', $can_cancel_bill_across_day);
        Session::put('can_approve', $can_approve);

       $sAction_user = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");
       $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
         return $query->where('id', auth()->user()->business_location_id_fk);
       })->get();
       $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1 && auth()->user()->position_level == 5, function ($query) {
         return $query->where('id', auth()->user()->branch_id_fk);
       })->get();
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


      // วุฒิสร้าง session
      $menus = DB::table('ck_backend_menu')->select('id')->where('id',82)->first();
      Session::put('session_menu_id', $menus->id);
      Session::put('menu_id', $menus->id);
      $role_group_id = \Auth::user()->role_group_id_fk;
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',@$role_group_id)->where('menu_id_fk',@$menus->id)->first();
      $sC = @$menu_permit->c;
      $sU = @$menu_permit->u;
      $sD = @$menu_permit->d;
      Session::put('sC', $sC);
      Session::put('sU', $sU);
      Session::put('sD', $sD);
      $can_cancel_bill = @$menu_permit->can_cancel_bill;
      $can_cancel_bill_across_day = @$menu_permit->can_cancel_bill_across_day;
      $can_approve = @$menu_permit->can_approve;
      Session::put('can_cancel_bill', $can_cancel_bill);
      Session::put('can_cancel_bill_across_day', $can_cancel_bill_across_day);
      Session::put('can_approve', $can_approve);


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

      }elseif(isset($request->save_from_firstform)){

            // dd($id);
            $sRow = \App\Models\Backend\Transfer_branch_get::find($id);
            $sRow->note    = request('note');
            $sRow->created_at = date('Y-m-d H:i:s');
            $sRow->save();
            return redirect()->to(url("backend/transfer_branch_get/".$id."/edit"));

            // ถ้ายืนยันอนุมัติจะไม่เข้าเงื่อนไขนี้ $request->approve_getback
      }elseif(isset($request->approve_getback)){
            // $sRow = \App\Models\Backend\Transfer_branch_get::find($request->id);
            // $sRow->approve_status    = $request->approve_status;
            // $sRow->approver    = $request->approver;
            // $sRow->approve_date    = date('Y-m-d H:i:s');
            // $sRow->note3    = $request->note3;
            // $sRow->tr_status_get    = 6;
            // $sRow->created_at = date('Y-m-d H:i:s');
            // $sRow->save();
            // DB::select(" UPDATE `db_transfer_branch_code` SET tr_status_from='6' where tr_number='".$sRow->tr_number."' ");
            // เมื่ออนุมัติ == 1 ตัดเข้าคลังอีกครั้ง
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
                    WHERE transfer_branch_get_id_fk in ($request->id)
                    GROUP BY transfer_branch_get_id_fk,branch_id_fk,warehouse_id_fk,zone_id_fk,shelf_floor
                    ");

              // check ก่อนว่ามีใน ชั้นนั้นๆ หรือยัง ถ้ามี update ถ้ายังไม่มี add
                 foreach ($products as $key => $p) {
                          $branch_data = DB::table('branchs')->where('id',$p->branch_id_fk)->first();
                          if($branch_data){
                            $business_location_id_fk = $branch_data->business_location_id_fk;
                          }else{
                            $business_location_id_fk = 0;
                          }

                          $_check=DB::table('db_stocks')
                          ->where('business_location_id_fk', $business_location_id_fk)
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
                              $stock->business_location_id_fk = $business_location_id_fk ;
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
                                ->where('business_location_id_fk', $business_location_id_fk)
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

               // ดึงเข้าคลัง สาขาที่รับโอนมา กรณี อนุมัติเท่านั้น (ชำรุด)
               if(request('approve_status')==1){
                // นำเข้า Stock
                                 $products = DB::select("

                                    SELECT
                                    sum(amt_get) AS sum_amt,
                                    db_transfer_branch_get_products_receive_defective.id,
                                    db_transfer_branch_get_products_receive_defective.transfer_branch_get_id_fk,
                                    db_transfer_branch_get_products_receive_defective.transfer_branch_get_products,
                                    db_transfer_branch_get_products_receive_defective.product_id_fk,
                                    db_transfer_branch_get_products_receive_defective.lot_number,
                                    db_transfer_branch_get_products_receive_defective.lot_expired_date,
                                    db_transfer_branch_get_products_receive_defective.amt_get,
                                    db_transfer_branch_get_products_receive_defective.product_unit_id_fk,
                                    db_transfer_branch_get_products_receive_defective.branch_id_fk,
                                    db_transfer_branch_get_products_receive_defective.warehouse_id_fk,
                                    db_transfer_branch_get_products_receive_defective.zone_id_fk,
                                    db_transfer_branch_get_products_receive_defective.shelf_id_fk,
                                    db_transfer_branch_get_products_receive_defective.shelf_floor,
                                    db_transfer_branch_get_products_receive_defective.action_user,
                                    db_transfer_branch_get_products_receive_defective.action_date,
                                    db_transfer_branch_get_products_receive_defective.approver,
                                    db_transfer_branch_get_products_receive_defective.approve_status,
                                    db_transfer_branch_get_products_receive_defective.approve_date,
                                    db_transfer_branch_get_products_receive_defective.created_at,
                                    db_transfer_branch_get_products_receive_defective.updated_at,
                                    db_transfer_branch_get_products_receive_defective.deleted_at
                                    from db_transfer_branch_get_products_receive_defective
                                    WHERE transfer_branch_get_id_fk in ($request->id)
                                    GROUP BY transfer_branch_get_id_fk,branch_id_fk,warehouse_id_fk,zone_id_fk,shelf_floor

                                    ");

                // check ก่อนว่ามีใน ชั้นนั้นๆ หรือยัง ถ้ามี update ถ้ายังไม่มี add

                                 foreach ($products as $key => $p) {
                                          $branch_data = DB::table('branchs')->where('id',$p->branch_id_fk)->first();
                                          if($branch_data){
                                            $business_location_id_fk = $branch_data->business_location_id_fk;
                                          }else{
                                            $business_location_id_fk = 0;
                                          }

                                          $_check=DB::table('db_stocks')
                                          ->where('business_location_id_fk', $business_location_id_fk)
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
                                              $stock->business_location_id_fk = $business_location_id_fk ;
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
                                                ->where('business_location_id_fk', $business_location_id_fk)
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
      // dd($id);
      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow = \App\Models\Backend\Transfer_branch_get::find($id);
          }else{
            $sRow = new \App\Models\Backend\Transfer_branch_get;
          }


            if(request('approve_status')==5){
                $sRow->tr_status_get    = 5 ; // ปฏิเสธการรับ
            }

            $sRow->approver = \Auth::user()->id;
            $sRow->approve_status = request('approve_status') ;
            $sRow->approve_date = date('Y-m-d H:i:s');
            $sRow->note2 = request('note2') ;
            $sRow->save();

            DB::select(" update db_transfer_branch_code set tr_status_from=".$sRow->tr_status_get.",tr_status_to=".$sRow->tr_status_get." where tr_number='".$sRow->tr_number."'");


// ดึงเข้าคลัง สาขาที่รับโอนมา กรณี อนุมัติเท่านั้น
            if(request('approve_status')==1){

// นำเข้า Stock
              $products = DB::select("

              SELECT

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


              ");
              // วุฒิเปลี่ยนเป็นแบบไม่ sum และไม่กรุ๊ป
              // sum(amt_get) AS sum_amt,
              // GROUP BY transfer_branch_get_id_fk,branch_id_fk,warehouse_id_fk,zone_id_fk,shelf_floor

                 $sRow = \App\Models\Backend\Transfer_branch_get::find($id);

          // check ก่อนว่ามีใน ชั้นนั้นๆ หรือยัง ถ้ามี update ถ้ายังไม่มี add
                 foreach ($products as $key => $p) {
                  $branch_data = DB::table('branchs')->where('id',$p->branch_id_fk)->first();
                  if($branch_data){
                    $business_location_id_fk = $branch_data->business_location_id_fk;
                  }else{
                    $business_location_id_fk = 0;
                  }
                          $_check=DB::table('db_stocks')
                          ->where('business_location_id_fk', $business_location_id_fk)
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
                              $stock->business_location_id_fk = $business_location_id_fk ;
                              $stock->product_id_fk = $p->product_id_fk ;
                              $stock->lot_number = $p->lot_number ;
                              $stock->lot_expired_date = $p->lot_expired_date ;
                              // วุฒิปรับจากเพิ่ม amt แบบรวมเปลี่ยนเป็นแบบรายตัวแทน
                              $stock->amt = $p->amt_get ;
                              // $stock->amt = $p->sum_amt ;
                              $stock->product_unit_id_fk = $p->product_unit_id_fk ;
                              $stock->branch_id_fk = $p->branch_id_fk ;
                              $stock->warehouse_id_fk = $p->warehouse_id_fk ;
                              $stock->zone_id_fk = $p->zone_id_fk ;
                              $stock->shelf_id_fk = $p->shelf_id_fk ;
                              $stock->shelf_floor = $p->shelf_floor ;
                              $stock->date_in_stock = date("Y-m-d");
                              $stock->created_at = date("Y-m-d H:i:s");
                              $stock->save();

                              // $lastID = DB::getPdo()->lastInsertId();
                              $lastID = $stock->id;


                          }else{

                              DB::table('db_stocks')
                                  ->where('business_location_id_fk', $business_location_id_fk)
                                  ->where('branch_id_fk', $p->branch_id_fk)
                                  ->where('product_id_fk', $p->product_id_fk)
                                  ->where('lot_number', $p->lot_number)
                                  ->where('lot_expired_date', $p->lot_expired_date)
                                  ->where('warehouse_id_fk', $p->warehouse_id_fk)
                                  ->where('zone_id_fk', $p->zone_id_fk)
                                  ->where('shelf_id_fk', $p->shelf_id_fk)
                                  ->where('shelf_floor', $p->shelf_floor)
                                ->update(array(
                                // วุฒิปรับจากเพิ่ม amt แบบรวมเปลี่ยนเป็นแบบรายตัวแทน
                                'amt' => DB::raw( ' amt + '.$p->amt_get )
                                  // 'amt' => DB::raw( ' amt + '.$p->sum_amt )
                                ));

                                $lastID = DB::table('db_stocks')
                                ->select('id')
                                ->where('business_location_id_fk', $business_location_id_fk)
                                ->where('branch_id_fk', $p->branch_id_fk)
                                ->where('product_id_fk', $p->product_id_fk)
                                ->where('lot_number', $p->lot_number)
                                ->where('lot_expired_date', $p->lot_expired_date)
                                ->where('warehouse_id_fk', $p->warehouse_id_fk)
                                ->where('zone_id_fk', $p->zone_id_fk)
                                ->where('shelf_id_fk', $p->shelf_id_fk)
                                ->where('shelf_floor', $p->shelf_floor)
                                ->first();
                               $lastID = $lastID->id;

                          }


                          // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@2

                              /*
                              เปลี่ยนใหม่ นำเข้าเฉพาะรายการที่มีการอนุมัติอันล่าสุดเท่านั้น
                              นำเข้า Stock movement => กรองตาม 4 ฟิลด์ที่สร้างใหม่ stock_type_id_fk,stock_id_fk,ref_table_id,ref_doc
                              [dataset_stock_type]
                              1 จ่ายสินค้าตามใบเบิก 26
                              2 จ่ายสินค้าตามใบเสร็จ  27
                              3 รับสินค้าเข้าทั่วไป 28
                              4 รับสินค้าเข้าตาม PO 29
                              5 นำสินค้าออก 30
                              6 สินค้าเบิก-ยืม  31
                              7 โอนภายในสาขา  32
                              8 โอนระหว่างสาขา  33
                              9 รับสินค้าจากการโอนระหว่างสาขา  82
                              */
                              $stock_type_id_fk = 9 ;
                              // $stock_id_fk =  @$lastID?$lastID:0 ;
                              $ref_table = 'db_transfer_branch_get_products_receive' ;
                              $ref_table_id = $p->id ;
                              // $ref_doc = $p->ref_doc;
                              $ref_doc = DB::select(" select * from `db_transfer_branch_get` WHERE id=".$id." ");
                              // dd($ref_doc[0]->ref_doc);
                              $ref_doc = @$ref_doc[0]->tr_number;

                              $value=DB::table('db_stock_movement')
                              ->where('stock_type_id_fk', @$stock_type_id_fk?$stock_type_id_fk:0 )
                              ->where('stock_id_fk', $lastID )
                              ->where('ref_table_id', @$ref_table_id?$ref_table_id:0 )
                              ->where('ref_doc', @$ref_doc?$ref_doc:NULL )
                              ->get();

                              if($value->count() == 0){

                                    DB::table('db_stock_movement')->insertOrignore(array(
                                        "stock_type_id_fk" =>  @$stock_type_id_fk?$stock_type_id_fk:0,
                                        "stock_id_fk" =>  $lastID,
                                        "ref_table" =>  @$ref_table?$ref_table:0,
                                        "ref_table_id" =>  @$ref_table_id?$ref_table_id:0,
                                        "ref_doc" =>  @$ref_doc?$ref_doc:NULL,
                                        "doc_date" =>  $p->updated_at,
                                        "business_location_id_fk" =>  $business_location_id_fk?$business_location_id_fk:0,
                                        "branch_id_fk" =>  @$p->branch_id_fk?$p->branch_id_fk:0,
                                        "product_id_fk" =>  @$p->product_id_fk?$p->product_id_fk:0,
                                        "lot_number" =>  @$p->lot_number?$p->lot_number:NULL,
                                        "lot_expired_date" =>  @$p->lot_expired_date?$p->lot_expired_date:NULL,
                                        "amt" =>  @$p->amt_get?$p->amt_get:0,
                                        "in_out" =>  '1',
                                        "product_unit_id_fk" =>  @$p->product_unit_id_fk?$p->product_unit_id_fk:0,

                                        "warehouse_id_fk" =>  @$p->warehouse_id_fk?$p->warehouse_id_fk:0,
                                        "zone_id_fk" =>  @$p->zone_id_fk?$p->zone_id_fk:0,
                                        "shelf_id_fk" =>  @$p->shelf_id_fk?$p->shelf_id_fk:0,
                                        "shelf_floor" =>  @$p->shelf_floor?$p->shelf_floor:0,

                                        "status" => '1',
                                        "note" =>  'รับสินค้าจากการโอนระหว่างสาขา ',
                                        "note2" =>  @$p->description?$p->description:NULL,

                                        "action_user" =>  @$p->action_user?$p->action_user:NULL,
                                        "action_date" =>  @$p->action_date?$p->action_date:NULL,
                                        "approver" =>  @\Auth::user()->id,
                                        "approve_date" =>  @$p->updated_at?$p->updated_at:NULL,

                                        "created_at" =>@$p->created_at,
                                    ));

                              }
                              // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@




                 }

            }

            // ดึงเข้าคลัง สาขาที่รับโอนมา กรณี อนุมัติเท่านั้น ชำรุด
            if(request('approve_status')==1){

              // นำเข้า Stock
                            $products = DB::select("

                            SELECT

                            db_transfer_branch_get_products_receive_defective.id,
                            db_transfer_branch_get_products_receive_defective.transfer_branch_get_id_fk,
                            db_transfer_branch_get_products_receive_defective.transfer_branch_get_products,
                            db_transfer_branch_get_products_receive_defective.product_id_fk,
                            db_transfer_branch_get_products_receive_defective.lot_number,
                            db_transfer_branch_get_products_receive_defective.lot_expired_date,
                            db_transfer_branch_get_products_receive_defective.amt_get,
                            db_transfer_branch_get_products_receive_defective.product_unit_id_fk,
                            db_transfer_branch_get_products_receive_defective.branch_id_fk,
                            db_transfer_branch_get_products_receive_defective.warehouse_id_fk,
                            db_transfer_branch_get_products_receive_defective.zone_id_fk,
                            db_transfer_branch_get_products_receive_defective.shelf_id_fk,
                            db_transfer_branch_get_products_receive_defective.shelf_floor,
                            db_transfer_branch_get_products_receive_defective.action_user,
                            db_transfer_branch_get_products_receive_defective.action_date,
                            db_transfer_branch_get_products_receive_defective.approver,
                            db_transfer_branch_get_products_receive_defective.approve_status,
                            db_transfer_branch_get_products_receive_defective.approve_date,
                            db_transfer_branch_get_products_receive_defective.created_at,
                            db_transfer_branch_get_products_receive_defective.updated_at,
                            db_transfer_branch_get_products_receive_defective.deleted_at
                            from db_transfer_branch_get_products_receive_defective
                            WHERE transfer_branch_get_id_fk in ($id)
                            ");
                            // วุฒิเปลี่ยนเป็นแบบไม่ sum และไม่กรุ๊ป
                            // sum(amt_get) AS sum_amt,
                            // GROUP BY transfer_branch_get_id_fk,branch_id_fk,warehouse_id_fk,zone_id_fk,shelf_floor

                               $sRow = \App\Models\Backend\Transfer_branch_get::find($id);

                        // check ก่อนว่ามีใน ชั้นนั้นๆ หรือยัง ถ้ามี update ถ้ายังไม่มี add
                               foreach ($products as $key => $p) {
                                $branch_data = DB::table('branchs')->where('id',$p->branch_id_fk)->first();
                                if($branch_data){
                                  $business_location_id_fk = $branch_data->business_location_id_fk;
                                }else{
                                  $business_location_id_fk = 0;
                                }
                                        $_check=DB::table('db_stocks')
                                        ->where('business_location_id_fk', $business_location_id_fk)
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
                                            $stock->business_location_id_fk = $business_location_id_fk ;
                                            $stock->product_id_fk = $p->product_id_fk ;
                                            $stock->lot_number = $p->lot_number ;
                                            $stock->lot_expired_date = $p->lot_expired_date ;
                                            // วุฒิปรับจากเพิ่ม amt แบบรวมเปลี่ยนเป็นแบบรายตัวแทน
                                            $stock->amt = $p->amt_get ;
                                            // $stock->amt = $p->sum_amt ;
                                            $stock->product_unit_id_fk = $p->product_unit_id_fk ;
                                            $stock->branch_id_fk = $p->branch_id_fk ;
                                            $stock->warehouse_id_fk = $p->warehouse_id_fk ;
                                            $stock->zone_id_fk = $p->zone_id_fk ;
                                            $stock->shelf_id_fk = $p->shelf_id_fk ;
                                            $stock->shelf_floor = $p->shelf_floor ;
                                            $stock->date_in_stock = date("Y-m-d");
                                            $stock->created_at = date("Y-m-d H:i:s");
                                            $stock->save();

                                            // $lastID = DB::getPdo()->lastInsertId();
                                            $lastID = $stock->id;

                                        }else{
                                            DB::table('db_stocks')
                                                ->where('business_location_id_fk', $business_location_id_fk)
                                                ->where('branch_id_fk', $p->branch_id_fk)
                                                ->where('product_id_fk', $p->product_id_fk)
                                                ->where('lot_number', $p->lot_number)
                                                ->where('lot_expired_date', $p->lot_expired_date)
                                                ->where('warehouse_id_fk', $p->warehouse_id_fk)
                                                ->where('zone_id_fk', $p->zone_id_fk)
                                                ->where('shelf_id_fk', $p->shelf_id_fk)
                                                ->where('shelf_floor', $p->shelf_floor)
                                              ->update(array(
                                              // วุฒิปรับจากเพิ่ม amt แบบรวมเปลี่ยนเป็นแบบรายตัวแทน
                                              'amt' => DB::raw( ' amt + '.$p->amt_get )
                                                // 'amt' => DB::raw( ' amt + '.$p->sum_amt )
                                              ));

                                              $lastID = DB::table('db_stocks')
                                              ->select('id')
                                              ->where('business_location_id_fk', $business_location_id_fk)
                                              ->where('branch_id_fk', $p->branch_id_fk)
                                              ->where('product_id_fk', $p->product_id_fk)
                                              ->where('lot_number', $p->lot_number)
                                              ->where('lot_expired_date', $p->lot_expired_date)
                                              ->where('warehouse_id_fk', $p->warehouse_id_fk)
                                              ->where('zone_id_fk', $p->zone_id_fk)
                                              ->where('shelf_id_fk', $p->shelf_id_fk)
                                              ->where('shelf_floor', $p->shelf_floor)
                                              ->first();
                                             $lastID = $lastID->id;

                                        }


                                        // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@2

                                            /*
                                            เปลี่ยนใหม่ นำเข้าเฉพาะรายการที่มีการอนุมัติอันล่าสุดเท่านั้น
                                            นำเข้า Stock movement => กรองตาม 4 ฟิลด์ที่สร้างใหม่ stock_type_id_fk,stock_id_fk,ref_table_id,ref_doc
                                            [dataset_stock_type]
                                            1 จ่ายสินค้าตามใบเบิก 26
                                            2 จ่ายสินค้าตามใบเสร็จ  27
                                            3 รับสินค้าเข้าทั่วไป 28
                                            4 รับสินค้าเข้าตาม PO 29
                                            5 นำสินค้าออก 30
                                            6 สินค้าเบิก-ยืม  31
                                            7 โอนภายในสาขา  32
                                            8 โอนระหว่างสาขา  33
                                            9 รับสินค้าจากการโอนระหว่างสาขา  82
                                            */
                                            $stock_type_id_fk = 9 ;
                                            // $stock_id_fk =  @$lastID?$lastID:0 ;
                                            $ref_table = 'db_transfer_branch_get_products_receive_defective' ;
                                            $ref_table_id = $p->id ;
                                            // $ref_doc = $p->ref_doc;
                                            $ref_doc = DB::select(" select * from `db_transfer_branch_get` WHERE id=".$id." ");
                                            // dd($ref_doc[0]->ref_doc);
                                            $ref_doc = @$ref_doc[0]->tr_number;

                                            $value=DB::table('db_stock_movement')
                                            ->where('stock_type_id_fk', @$stock_type_id_fk?$stock_type_id_fk:0 )
                                            ->where('stock_id_fk', $lastID )
                                            ->where('ref_table_id', @$ref_table_id?$ref_table_id:0 )
                                            ->where('ref_doc', @$ref_doc?$ref_doc:NULL )
                                            ->get();

                                            if($value->count() == 0){

                                                  DB::table('db_stock_movement')->insertOrignore(array(
                                                      "stock_type_id_fk" =>  @$stock_type_id_fk?$stock_type_id_fk:0,
                                                      "stock_id_fk" =>  $lastID,
                                                      "ref_table" =>  @$ref_table?$ref_table:0,
                                                      "ref_table_id" =>  @$ref_table_id?$ref_table_id:0,
                                                      "ref_doc" =>  @$ref_doc?$ref_doc:NULL,
                                                      "doc_date" =>  $p->updated_at,
                                                      "business_location_id_fk" =>  $business_location_id_fk?$business_location_id_fk:0,
                                                      "branch_id_fk" =>  @$p->branch_id_fk?$p->branch_id_fk:0,
                                                      "product_id_fk" =>  @$p->product_id_fk?$p->product_id_fk:0,
                                                      "lot_number" =>  @$p->lot_number?$p->lot_number:NULL,
                                                      "lot_expired_date" =>  @$p->lot_expired_date?$p->lot_expired_date:NULL,
                                                      "amt" =>  @$p->amt_get?$p->amt_get:0,
                                                      "in_out" =>  '1',
                                                      "product_unit_id_fk" =>  @$p->product_unit_id_fk?$p->product_unit_id_fk:0,

                                                      "warehouse_id_fk" =>  @$p->warehouse_id_fk?$p->warehouse_id_fk:0,
                                                      "zone_id_fk" =>  @$p->zone_id_fk?$p->zone_id_fk:0,
                                                      "shelf_id_fk" =>  @$p->shelf_id_fk?$p->shelf_id_fk:0,
                                                      "shelf_floor" =>  @$p->shelf_floor?$p->shelf_floor:0,

                                                      "status" => '1',
                                                      "note" =>  'รับสินค้าจากการโอนระหว่างสาขา ',
                                                      "note2" =>  @$p->description?$p->description:NULL,

                                                      "action_user" =>  @$p->action_user?$p->action_user:NULL,
                                                      "action_date" =>  @$p->action_date?$p->action_date:NULL,
                                                      "approver" =>  @\Auth::user()->id,
                                                      "approve_date" =>  @$p->updated_at?$p->updated_at:NULL,

                                                      "created_at" =>@$p->created_at,
                                                  ));

                                            }
                                            // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

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

              // ตอนรับคืนมันต้อง ตัดเข้า stock ตามเดิมด้วยนะ และต้องลึกถึง ชั้น ด้วย




      }

      // $sRow = \App\Models\Backend\Transfer_branch_get::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){

        if(!empty($req->business_location_id_fk)){
          //  $w01 = " AND db_transfer_branch_get.business_location_id_fk=".$req->business_location_id_fk ;
          $w01 = "";
        }else{
           $w01 = "";
        }
// dd($w01);
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
           $w05 = "and date(db_transfer_branch_get.created_at) BETWEEN '".date('Y-m-d')."' AND '".date('Y-m-d')."'";
        }
// dd($w05);
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

      // ดึงต้นทางยกเลิกมาเช็ค
      $db_transfer_branch_code = DB::table('db_transfer_branch_code')->select('tr_number')->where('approve_status',2)->pluck('tr_number')->toArray();

        if(count($db_transfer_branch_code)!=0){
          $cancel_arr = implode(",",$db_transfer_branch_code);
          $text = "";
          $dot = ",";
          foreach($db_transfer_branch_code as $key => $c){
            if(($key+1)==count($db_transfer_branch_code)){
              $text .= '"'.$c.'"';
            }else{
              $text .= '"'.$c.'"'.$dot;
            }
          }
          $cancel_txt = " AND tr_number NOT IN(".$text.")";
        }else{
          $cancel_txt = "";
        }

        if(\Auth::user()->id==1){
          $w01 = "";
          $w02 = "";
          $w02 = "";
          $w03 = "";
          $w04 = "";
          $w05 = "";
          $w06 = "";
          $cancel_txt = "";
        }else{
          $w05 = "";
        }

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
            $cancel_txt
            ORDER BY updated_at DESC
         ");
// dd($sTable);
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
