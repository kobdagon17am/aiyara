<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class General_receiveController extends Controller
{

    public function index(Request $request)
    {

      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBranchs = \App\Models\Backend\Branchs::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

        return View('backend.general_receive.index')->with(
        array(
           'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id
        ) );

    }

 public function create()
    {
      $Product_in_cause = \App\Models\Backend\Product_in_cause::get();
      $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1");

      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBranchs = \App\Models\Backend\Branchs::get();

      // dd($sBranchs);

      // dd($Products);
      $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();
      $sSupplier = \App\Models\Backend\Supplier::get();


      return View('backend.general_receive.form')->with(
        array(
           'Product_in_cause'=>$Product_in_cause,
           'Products'=>$Products,
           'sProductUnit'=>$sProductUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,
           'sBranchs'=>$sBranchs,
           'User_branch_id'=>$User_branch_id,
           'sSupplier'=>$sSupplier,
        ) );
    }
    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\General_receive::find($id);
       $Product_in_cause = \App\Models\Backend\Product_in_cause::get();
       $Recipient  = DB::select(" select * from ck_users_admin where id=".$sRow->recipient." ");
       $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1");
      $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();       
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();      

      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBranchs = \App\Models\Backend\Branchs::get();

      $sSupplier = \App\Models\Backend\Supplier::get();

      // dd($sBranchs);

      return View('backend.general_receive.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id,
           'Product_in_cause'=>$Product_in_cause,
           'Recipient'=>$Recipient,
           'Products'=>$Products,
           'sProductUnit'=>$sProductUnit,
           'Warehouse'=>$Warehouse,
           'Zone'=>$Zone,
           'Shelf'=>$Shelf,
           'sBranchs'=>$sBranchs,
           'User_branch_id'=>$User_branch_id,
           'sSupplier'=>$sSupplier,
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
            $sRow = \App\Models\Backend\General_receive::find($id);
          }else{
            $sRow = new \App\Models\Backend\General_receive;
          }

          $sRow->product_in_cause_id_fk    = request('product_in_cause_id_fk');
          $sRow->product_id_fk    = request('product_id_fk');
          $sRow->lot_number    = request('lot_number');
          $sRow->lot_expired_date    = request('lot_expired_date');
          $sRow->amt    = request('amt');
          $sRow->product_unit_id_fk    = request('product_unit_id_fk');
          $sRow->branch_id_fk    = request('branch_id_fk');
          $sRow->warehouse_id_fk    = request('warehouse_id_fk');
          $sRow->zone_id_fk    = request('zone_id_fk');
          $sRow->shelf_id_fk    = request('shelf_id_fk');
          $sRow->shelf_floor    = request('shelf_floor');
          $sRow->delivery_person    = request('delivery_person');
          
          $sRow->pickup_firstdate    = date('Y-m-d H:i:s');
          $sRow->recipient    = request('recipient');
          $sRow->approver    = request('approver');
          $sRow->approve_status    = request('approve_status');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/general_receive/".$sRow->id."/edit"));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\General_receiveController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\General_receive::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\General_receive::search()->orderBy('id', 'asc');
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

        return @$Products[0]->product_code." : ".@$Products[0]->product_name;

      })
      ->addColumn('recipient_name', function($row) {
        $sD = DB::select(" select * from ck_users_admin where id=".$row->recipient." ");
        return $sD[0]->name;
      })
      ->addColumn('product_in_cause', function($row) {
        if($row->product_in_cause_id_fk!=''){
          $d = \App\Models\Backend\Product_in_cause::find($row->product_in_cause_id_fk);
          return @$d->txt_desc;
        }else{
          return '';
        }
        
      })
      ->addColumn('lot_expired_date', function($row) {
        $d = strtotime($row->lot_expired_date); 
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('pickup_date', function($row) {
        $d = strtotime($row->pickup_firstdate); 
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


}
