<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Session;

class Transfer_warehouses_codeController extends Controller
{

    public function index(Request $request)
    {

      $User_branch_id = \Auth::user()->branch_id_fk;
      // dd($User_branch_id);

      $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1");

      $sBranchs = \App\Models\Backend\Branchs::get();

      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

        return View('backend.transfer_warehouses.index')->with(
        array(
           'Products'=>$Products,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id
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
      return View('backend.transfer_warehouses_code.form')->with(
        array(
           'receive_id'=>$receive_id,'Products'=>$Products,'ProductsUnit'=>$ProductsUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf
        ) );
    }
    public function store(Request $request)
    {
      // dd($request->all());

        if(isset($request->transfer_choose_id)){
                // dd($request->all());
                $Transfer_warehouses_code = new \App\Models\Backend\Transfer_warehouses_code;
                $Transfer_warehouses_code->business_location_id_fk = request('business_location_id_fk');
                $Transfer_warehouses_code->branch_id_fk = request('branch_id_fk');
                $Transfer_warehouses_code->note = request('note');
                $Transfer_warehouses_code->action_date = date("Y-m-d");
                $Transfer_warehouses_code->action_user = \Auth::user()->id;
                $Transfer_warehouses_code->created_at = date("Y-m-d H:i:s");
                $Transfer_warehouses_code->save();

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
                $stock_type_id_fk = 7;
                $ref_table_id = $Transfer_warehouses_code->id ;
                $ref_doc = 'TR'.$Transfer_warehouses_code->business_location_id_fk.$Transfer_warehouses_code->branch_id_fk.$stock_type_id_fk.date('ymd').$ref_table_id;
                // DB::update(" update db_transfer_warehouses_code set tr_number=? where id=? ",["TR".sprintf("%05d",$Transfer_warehouses_code->id),$Transfer_warehouses_code->id]);
                DB::update(" update db_transfer_warehouses_code set tr_number=? where id=? ",[$ref_doc,$Transfer_warehouses_code->id]);
// ฝั่งรับโอน
                for ($i=0; $i < count($request->transfer_choose_id) ; $i++) { 
                    $Transfer_choose = \App\Models\Backend\Transfer_choose::find($request->transfer_choose_id[$i]);
                    DB::insert("  
                       insert into db_transfer_warehouses_details set  
                       transfer_warehouses_code_id=? 
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
                       ,remark=? 
                       ,created_at=? 
                       ",
                      [
                        $Transfer_warehouses_code->id
                        ,$Transfer_choose->stocks_id_fk
                        ,$Transfer_choose->product_id_fk
                        ,$Transfer_choose->lot_number
                        ,$Transfer_choose->lot_expired_date
                        ,$Transfer_choose->amt
                        ,$Transfer_choose->product_unit_id_fk
                        ,$Transfer_choose->branch_id_fk
                        ,$Transfer_choose->warehouse_id_fk
                        ,$Transfer_choose->zone_id_fk
                        ,$Transfer_choose->shelf_id_fk
                        ,$Transfer_choose->shelf_floor
                        ,$Transfer_choose->action_user
                        ,$Transfer_choose->action_date
                        ,'2'
                        ,date("Y-m-d H:i:s")
                      ]);
                }

 // 1=ฝั่งโอนให้ , 2=ฝั่งรับโอน 
// ฝั่งโอนให้ ใส่ไว้ก่อน แล้วค่อยไปหักลบยอดออกตอน อนุมัติ อีกที  warehouse_id_fk ต้องเป็นอันเดิมตาม db_stocks

                for ($i=0; $i < count($request->transfer_choose_id) ; $i++) { 
                    $Transfer_choose = \App\Models\Backend\Transfer_choose::find($request->transfer_choose_id[$i]);

                    $Stock = DB::table('db_stocks')
                            ->where('id', $Transfer_choose->stocks_id_fk)
                            ->get();

                    DB::insert("  
                       insert into db_transfer_warehouses_details set  
                       transfer_warehouses_code_id=? 
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
                       ,remark=? 
                       ,created_at=? 
                       ",
                      [
                        $Transfer_warehouses_code->id
                        ,$Transfer_choose->stocks_id_fk
                        ,$Transfer_choose->product_id_fk
                        ,$Transfer_choose->lot_number
                        ,$Transfer_choose->lot_expired_date
                        ,$Transfer_choose->amt
                        ,$Transfer_choose->product_unit_id_fk
                        ,$Stock[0]->branch_id_fk
                        ,$Stock[0]->warehouse_id_fk
                        ,$Stock[0]->zone_id_fk
                        ,$Stock[0]->shelf_id_fk
                        ,$Stock[0]->shelf_floor
                        ,$Transfer_choose->action_user
                        ,$Transfer_choose->action_date
                        ,'1'
                        ,date("Y-m-d H:i:s")
                      ]);

                      DB::update(" DELETE FROM db_transfer_choose where id=? ",[$Transfer_choose->id]);

                }

                // DB::update(" DELETE FROM db_transfer_choose where action_user=? ",[\Auth::user()->id]);

                return redirect()->to(url("backend/transfer_warehouses"));

        }else{
              return $this->form();
        }


      
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Transfer_warehouses_code::find($id);
       // dd($sRow);
        $receive_id = \App\Models\Backend\General_receive::get();
        $Products = \App\Models\Backend\Products::get();
        $ProductsUnit = \App\Models\Backend\Product_unit::get();
        $Warehouse = \App\Models\Backend\Warehouse::get();
        $Zone = \App\Models\Backend\Zone::get();
        $Shelf = \App\Models\Backend\Shelf::get();
       $Recipient  = DB::select(" select * from ck_users_admin where id=".$sRow->recipient." ");
      return View('backend.transfer_warehouses_code.form')->with(
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
            $sRow = \App\Models\Backend\Transfer_warehouses_code::find($id);
          }else{
            $sRow = new \App\Models\Backend\Transfer_warehouses_code;
          }

          $sRow->general_receive_id_fk    = request('general_receive_id_fk');
          $sRow->product_id_fk    = request('product_id_fk');
          $sRow->amt    = request('amt');
          $sRow->product_unit_id_fk    = request('product_unit_id_fk');
          $sRow->warehouse_id_fk    = request('warehouse_id_fk');
          $sRow->zone_id_fk    = request('zone_id_fk');
          $sRow->shelf_id_fk    = request('shelf_id_fk');
          $sRow->shelf_floor    = request('shelf_floor');
          $sRow->recipient    = request('recipient');
          $sRow->approver    = request('approver');
          $sRow->approve_status    = request('approve_status');
          $sRow->getin_date = date('Y-m-d H:i:s');

          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/.transfer_warehouses_code./".$sRow->id."/edit"));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Transfer_warehouses_codeController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // DB::select(" DELETE FROM db_transfer_warehouses_details WHERE transfer_warehouses_code_id=$id ; ");
      // $sRow = \App\Models\Backend\Transfer_warehouses_code::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // ไม่ได้ลบจริง 
      DB::update(" UPDATE db_transfer_warehouses_code SET approve_status=2 WHERE id=$id ; ");
      // เอาไว้เปิดดูใบโอนได้
      // DB::update(" UPDATE db_transfer_warehouses_details SET deleted_at=now() WHERE transfer_warehouses_code_id=$id ; ");
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){
   
      /*
	    Super Admin   => see all 
		1) ถ้าทำ เห็น  
		2) ระบุ สาขา จะเห็น ในสาขา นั้น    =>  1 Or 2
		3) ถ้ามีสิทธิ์อนุมัติ แสดงปุ่มอนุมัติ  (0=รออนุมัติ,1=อนุมัติ,2=ยกเลิก,3=ไม่อนุมัติ)
      */
      // if(\Auth::user()->permission==1){
      //   // 1=Super Admin > เห็นทั้งหมด
      //   $sTable = \App\Models\Backend\Transfer_warehouses_code::search()->orderBy('action_date', 'desc');
      // }else{
      // 	// กรณีระบุสาขา เห็นเฉพาะสาขาตัวเอง 
      // 	if(\Auth::user()->branch_id_fk!=0  && Session::get('roleApprove')==0){
      // 		$sTable = \App\Models\Backend\Transfer_warehouses_code::where('action_user',\Auth::user()->id)->orderBy('action_date', 'desc');
      // 	}else if(\Auth::user()->branch_id_fk!=0  && Session::get('roleApprove')==1){
      // 		$sTable = \App\Models\Backend\Transfer_warehouses_code::where('branch_id_fk',\Auth::user()->branch_id_fk)->orderBy('action_date', 'desc');
      // 	}else{
      // 		// กรณีไม่ระบุสาขา 
      //       $sTable = \App\Models\Backend\Transfer_warehouses_code::search()->orderBy('action_date', 'desc');
      // 	}
      // }
      if(@$req->id>0){
        $id = " AND id = ".$req->id."  ";
      }else{
        $id = "";
      }

       $sPermission = @\Auth::user()->permission ;
       $User_branch_id = @\Auth::user()->branch_id_fk;

        // if(@\Auth::user()->permission==1){

        //     $sTable = \App\Models\Backend\Transfer_warehouses::where('remark','1')->search()->orderBy('id', 'asc'); // remark '1=ฝั่งโอนให้ , 2=ฝั่งรับโอน'

        // }else{

        //    // $sTable = \App\Models\Backend\Products_borrow::where('branch_id_fk',$User_branch_id)->orderBy('id', 'asc');
        //     $sTable = \App\Models\Backend\Transfer_warehouses::where('branch_id_fk',$User_branch_id)->where('remark','1')->search()->orderBy('id', 'asc'); // remark '1=ฝั่งโอนให้ , 2=ฝั่งรับโอน'


        // }

        $menu_id = '32';

        if($sPermission==1){
            $role_group_id = '%';
            $can_approve = 1;
        }else{
            $role_group_id = \Auth::user()->role_group_id_fk;
            $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
            $can_approve = @$menu_permit->can_approve;
            // dd($can_approve);
        }



        if(@\Auth::user()->permission==1){

            // if(!empty( $req->business_location_id_fk) ){
            //     $business_location_id = " and db_delivery.business_location_id = ".$req->business_location_id_fk." " ;
            // }else{
            //     $business_location_id = "";
            // }

            if(!empty( $req->branch_id_fk) ){
                $branch_id_fk = " AND branch_id_fk = ".$req->branch_id_fk." " ;
            }else{
                $branch_id_fk = "";
            }
            // เพื่ออะไร
            // $action_user = '1';
            $action_user = '';

        }else{

            // $business_location_id = " and db_delivery.business_location_id = ".@\Auth::user()->business_location_id_fk." " ;
            $branch_id_fk = " AND branch_id_fk = ".@\Auth::user()->branch_id_fk." " ;

            $action_user = " action_user=".@\Auth::user()->id." ";

          if($can_approve==1){
            $action_user = "";

          }else{
            $action_user = " and action_user=".@\Auth::user()->id." ";

          }

        }

      // '0=รออนุมัติ,1=อนุมัติ,2=ยกเลิก,3=ไม่อนุมัติ'
      switch ($req->approve_status) {
        case '':
          $approve_status = "";
          break;
        case '0':
          $approve_status = " AND approve_status = 0  ";
          break;    
        case '1' :
        case '2' :
        case '3' :
          $approve_status = " AND approve_status = ".$req->approve_status."  ";
          break;                 
        default:
          $approve_status = "";
          break;
      }
      
      if(!empty($req->startDated)){
          $action_date = "  AND action_date between '".($req->startDated)."' AND  '".($req->endDate)."' ";
      }else{
          $action_date = "";
      }
      
      // $sTable = DB::select(" SELECT * FROM db_transfer_warehouses_code 
      //     WHERE 
      //     $id
      //     ".$branch_id2." 
      //     ".$approve_status." 
      //     (action_user LIKE ".(\Auth::user()->id)." OR 
      //     (CASE WHEN ".(\Auth::user()->id)." IS NULL OR ".(\Auth::user()->branch_id_fk)." = '' THEN TRUE ELSE branch_id_fk = ".($branch_id)." END))
      //     ".$action_date." 
      //     ORDER BY updated_at DESC ");
    // dd($action_user);
      $sTable = DB::select(" SELECT * FROM db_transfer_warehouses_code 
          WHERE 1 $action_user
          $id
          $branch_id_fk
          $approve_status
          $action_date
          ORDER BY updated_at DESC ");
          		
      $sQuery = \DataTables::of($sTable);
       return $sQuery
      ->addColumn('action_user', function($row) {
        if(@$row->action_user!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->action_user." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })  
      ->addColumn('action_date', function($row) {
        if(!empty($row->action_date)){
          return $row->action_date;
        }else{
          return '';
        }
      })
      ->addColumn('approver', function($row) {
        if(@$row->approver!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->approver." ");
           return @$sD[0]->name;
        }else{
          return '-';
        }
      })  
      ->addColumn('approve_date', function($row) {
        if(!empty($row->approve_date)){
          return $row->approve_date;
        }else{
          return '';
        }
      })                         
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


}
