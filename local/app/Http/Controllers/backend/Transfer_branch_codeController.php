<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Session;

class Transfer_branch_codeController extends Controller
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

        $Warehouse = \App\Models\Backend\Warehouse::get();
        $Zone = \App\Models\Backend\Zone::get();
        $Shelf = \App\Models\Backend\Shelf::get();

        $User_branch_id = \Auth::user()->branch_id_fk;
        $sBranchs = \App\Models\Backend\Branchs::get();      

        return View('backend.transfer_branch_code.index')->with(
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
                      $Transfer_branch_code->branch_id_fk = request('branch_id_fk');
                      $Transfer_branch_code->note = request('note');
                      $Transfer_branch_code->action_date = date("Y-m-d");
                      $Transfer_branch_code->action_user = \Auth::user()->id;
                      $Transfer_branch_code->created_at = date("Y-m-d H:i:s");
                      $Transfer_branch_code->save();

                      DB::update(" update db_transfer_branch_code set tr_number=? where id=? ",["TR".sprintf("%05d",$Transfer_branch_code->id),$Transfer_branch_code->id]);

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
                             ,action_user=? 
                             ,action_date=? 
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
                              ,$Transfer_choose->action_user
                              ,$Transfer_choose->action_date
                            ]);
                      }

                     DB::update(" DELETE FROM db_transfer_choose_branch where action_user=? ",[\Auth::user()->id]);

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

    public function Datatable(){

      /*
      user 4 levels
      1=Super Admin
      2=User > branch = 0
      3=User > branch != 0 > approver
      4=User > branch != 0 > no approver
      */
      if(\Auth::user()->permission==1){
        // 1=Super Admin > see all
        $sTable = \App\Models\Backend\Transfer_branch_code::search()->orderBy('action_date', 'desc');

      }else if(\Auth::user()->branch_id_fk==0){
        // 2=User > branch = 0 > ตามสาขาที่เลือก ไป where ที่หน้า View อีกที 
        $sTable = \App\Models\Backend\Transfer_branch_code::search()->orderBy('action_date', 'desc');

      }else if(\Auth::user()->branch_id_fk!=0 && Session::get('roleApprove')==1){
         // 3=User > branch != 0 > approver
        $sTable = \App\Models\Backend\Transfer_branch_code::where('branch_id_fk',\Auth::user()->branch_id_fk)->orderBy('action_date', 'desc');
      }else{
        // 4=User > branch != 0 > no approver
        $sTable = \App\Models\Backend\Transfer_branch_code::where('action_user',\Auth::user()->id)->orderBy('action_date', 'desc');
      }
      
      // if(\Auth::user()->branch_id_fk==0){
      //   $sTable = \App\Models\Backend\Transfer_branch_code::search()->orderBy('action_date', 'desc');
      // }else{
      //   $sTable = \App\Models\Backend\Transfer_branch_code::where('action_user',\Auth::user()->id)->orderBy('action_date', 'desc');
      // }
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
        if(@$row->action_date!=''){
          $d = strtotime($row->action_date); return date("d/m/", $d).(date("Y", $d)+543);
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
        if(@$row->approve_date!=''){
          $d = strtotime($row->approve_date); return date("d/m/", $d).(date("Y", $d)+543);
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
