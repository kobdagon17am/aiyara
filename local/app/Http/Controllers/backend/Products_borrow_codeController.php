<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Session;

class Products_borrow_codeController extends Controller
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
            WHERE lang_id=1 AND products.id in (SELECT product_id_fk FROM db_stocks WHERE branch_id_fk='$User_branch_id')
            ORDER BY products.product_code");
      
      $sBranchs = \App\Models\Backend\Branchs::get();

      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

        return View('backend.products_borrow.index')->with(
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
      return View('backend.products_borrow_code.form')->with(
        array(
           'receive_id'=>$receive_id,'Products'=>$Products,'ProductsUnit'=>$ProductsUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf
        ) );
    }
    public function store(Request $request)
    {

      // dd($request->all());

/*
        var d = $(this).val();
        var t = d.substring(d.length - 5);
        var d = d.substring(0, 10);
        var d = d.split("/").reverse().join("-");
*/

        $return_datetime = request('return_datetime');
        if(!empty($return_datetime)){
        $t = substr($return_datetime, -5);
        // echo $t;
        $d = substr($return_datetime,0,10);
        // echo $d;
        $d = explode("/", $d); 
        $dt = $d[2]."-".$d[1]."-".$d[0]." ".$t; 
        // echo $dt;
        // dd();
        }else{
           $dt = null;
        }


        if(isset($request->products_borrow_choose_id)){
                // dd($request->all());
                $Products_borrow_code = new \App\Models\Backend\Products_borrow_code;
                $Products_borrow_code->branch_id_fk = request('branch_id_fk');
                $Products_borrow_code->borrow_cause_id_fk = request('borrow_cause_id_fk');
                $Products_borrow_code->return_datetime = $dt;
                $Products_borrow_code->note = request('note');
                $Products_borrow_code->action_date = date("Y-m-d");
                $Products_borrow_code->action_user = \Auth::user()->id;
                $Products_borrow_code->created_at = date("Y-m-d H:i:s");
                $Products_borrow_code->save();

                DB::update(" update db_products_borrow_code set business_location_id_fk=?, borrow_number=? where id=? ",[
                  $request->business_location_id_fk[0],
                  "BR".sprintf("%05d",$Products_borrow_code->id),
                  $Products_borrow_code->id
                ]);

                for ($i=0; $i < count($request->products_borrow_choose_id) ; $i++) { 
                    $Products_borrow_choose = \App\Models\Backend\Products_borrow_choose::find($request->products_borrow_choose_id[$i]);
                    DB::insert("  
                       insert into db_products_borrow_details set  
                       products_borrow_code_id=? 
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
                         $Products_borrow_code->id
                        ,$Products_borrow_choose->stocks_id_fk
                        ,$Products_borrow_choose->product_id_fk
                        ,$Products_borrow_choose->lot_number
                        ,$Products_borrow_choose->lot_expired_date
                        ,$Products_borrow_choose->amt
                        ,$Products_borrow_choose->product_unit_id_fk
                        ,$Products_borrow_choose->branch_id_fk
                        ,$Products_borrow_choose->warehouse_id_fk
                        ,$Products_borrow_choose->zone_id_fk
                        ,$Products_borrow_choose->shelf_id_fk
                        ,$Products_borrow_choose->shelf_floor
                        ,$Products_borrow_choose->action_user
                        ,$Products_borrow_choose->action_date
                        ,$Products_borrow_choose->created_at
                      ]);
                }

               DB::update(" DELETE FROM db_products_borrow_choose where action_user=? ",[\Auth::user()->id]);

                return redirect()->to(url("backend/products_borrow"));

        }else{
              return $this->form();
        }


      
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Products_borrow_code::find($id);
        $receive_id = \App\Models\Backend\General_receive::get();
        $Products = \App\Models\Backend\Products::get();
        $ProductsUnit = \App\Models\Backend\Product_unit::get();
        $Warehouse = \App\Models\Backend\Warehouse::get();
        $Zone = \App\Models\Backend\Zone::get();
        $Shelf = \App\Models\Backend\Shelf::get();
       $Recipient  = DB::select(" select * from ck_users_admin where id=".$sRow->recipient." ");
      return View('backend.products_borrow_code.form')->with(
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
            $sRow = \App\Models\Backend\Products_borrow_code::find($id);
          }else{
            $sRow = new \App\Models\Backend\Products_borrow_code;
          }

          $sRow->general_receive_id_fk    = request('general_receive_id_fk');
          $sRow->product_id_fk    = request('product_id_fk');
          $sRow->amt    = request('amt');
          $sRow->product_unit_id_fk    = request('product_unit_id_fk');
          $sRow->warehouse_id_fk    = request('warehouse_id_fk');
          $sRow->zone_id_fk    = request('zone_id_fk');
          $sRow->shelf_id_fk    = request('shelf_id_fk');
          $sRow->recipient    = request('recipient');
          $sRow->approver    = request('approver');
          $sRow->approve_status    = request('approve_status');
          $sRow->getin_date = date('Y-m-d H:i:s');

          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/.products_borrow_code./".$sRow->id."/edit"));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Products_borrow_codeController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // DB::select(" DELETE FROM db_products_borrow_details WHERE products_borrow_code_id=$id ; ");
      // $sRow = \App\Models\Backend\Products_borrow_code::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // ไม่ได้ลบจริง 
      DB::update(" UPDATE db_products_borrow_code SET approve_status=2 WHERE id=$id ; ");
      // เอาไว้เปิดดูใบโอนได้
      // DB::update(" UPDATE db_products_borrow_details SET deleted_at=now() WHERE products_borrow_code_id=$id ; ");
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){
      /*
	    Super Admin   => see all 
		1) ถ้าทำ เห็น  
		2) ระบุ สาขา จะเห็น ในสาขา นั้น    =>  1 Or 2
		3) ถ้ามีสิทธิ์อนุมัติ แสดงปุ่มอนุมัติ  (0=รออนุมัติ,1=อนุมัติ,2=ยกเลิก,3=ไม่อนุมัติ)
      */
      if($req->id>0){
        $id = " AND id = ".$req->id."  ";
      }else{
        $id = "";
      }

       $sPermission = \Auth::user()->permission ;
       $User_branch_id = \Auth::user()->branch_id_fk;


        $menu_id = '31';

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

            if(!empty( $req->business_location_id_fk) ){
                $business_location_id = " and db_products_borrow_code.business_location_id_fk = ".$req->business_location_id_fk." " ;
            }else{
                $business_location_id = "";
            }

            if(!empty( $req->branch_id_fk) ){
                $branch_id_fk = " and db_products_borrow_code.branch_id_fk = ".$req->branch_id_fk." " ;
            }else{
                $branch_id_fk = "";
            }

            $action_user = '';

        }else{

          if($can_approve==1){

            $business_location_id = " and db_products_borrow_code.business_location_id_fk = ".@\Auth::user()->business_location_id_fk." " ;
            $branch_id_fk = " and db_products_borrow_code.branch_id_fk = ".@\Auth::user()->branch_id_fk." " ;

            $action_user = '';
            
          }else{

            $business_location_id = " and db_products_borrow_code.business_location_id_fk = ".@\Auth::user()->business_location_id_fk." " ;
            $branch_id_fk = " and db_products_borrow_code.branch_id_fk = ".@\Auth::user()->branch_id_fk." " ;

            $action_user = " AND action_user=".@\Auth::user()->id."  ";
          }


        }

      // '0=รออนุมัติ,1=อนุมัติ,2=ยกเลิก,3=ไม่อนุมัติ'
      switch ($req->approve_status) {
        case '':
          $approve_status = "";
          break;
        case '0':
          $approve_status = " approve_status = 0 AND ";
          break;    
        case '1' :
        case '2' :
        case '3' :
          $approve_status = " approve_status = ".$req->approve_status." AND ";
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
      
      $sTable = DB::select(" SELECT * FROM db_products_borrow_code 
          where 1 
          $id
          $business_location_id
          $branch_id_fk
          $action_user
          ORDER BY updated_at DESC ");
          		
      $sQuery = \DataTables::of($sTable);
       return $sQuery
      ->addColumn('borrow_cause', function($row) {
        if(@$row->borrow_cause_id_fk!=''){
          $sD = DB::select(" select * from dataset_borrow_cause where id=".$row->borrow_cause_id_fk." ");
           return @$sD[0]->txt_desc;
        }else{
          return '';
        }
      })         
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
          return '-';
        }
      })                         
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


}
