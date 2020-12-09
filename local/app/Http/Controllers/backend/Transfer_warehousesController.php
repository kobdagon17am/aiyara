<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Transfer_warehousesController extends Controller
{

    public function index(Request $request)
    {

        $sProduct = \App\Models\Backend\Products_details::where('lang_id', 1)->get();
          // return @$sProduct[0]->product_name;
        return View('backend.transfer_warehouses.index')->with(
        array(
           'sProduct'=>$sProduct
        ) );
      
    }

 public function create()
    {
      $receive_id = \App\Models\Backend\General_receive::get();
      $Products = \App\Models\Backend\Products::get();
      $ProductsUnit = \App\Models\Backend\Product_unit::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Subwarehouse = \App\Models\Backend\Subwarehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();
      return View('backend.transfer_warehouses.form')->with(
        array(
           'receive_id'=>$receive_id,'Products'=>$Products,'ProductsUnit'=>$ProductsUnit,'Warehouse'=>$Warehouse,'Subwarehouse'=>$Subwarehouse,'Zone'=>$Zone,'Shelf'=>$Shelf
        ) );
    }
    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Transfer_warehouses::find($id);
        $receive_id = \App\Models\Backend\General_receive::get();
        $Products = \App\Models\Backend\Products::get();
        $ProductsUnit = \App\Models\Backend\Product_unit::get();
        $Warehouse = \App\Models\Backend\Warehouse::get();
        $Subwarehouse = \App\Models\Backend\Subwarehouse::get();
        $Zone = \App\Models\Backend\Zone::get();
        $Shelf = \App\Models\Backend\Shelf::get();
       $Recipient  = DB::select(" select * from ck_users_admin where id=".$sRow->recipient." ");
      return View('backend.transfer_warehouses.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id,'Recipient'=>$Recipient,'receive_id'=>$receive_id,'Products'=>$Products,'ProductsUnit'=>$ProductsUnit,'Warehouse'=>$Warehouse,'Subwarehouse'=>$Subwarehouse,'Zone'=>$Zone,'Shelf'=>$Shelf
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
            $sRow = \App\Models\Backend\Transfer_warehouses::find($id);
          }else{
            $sRow = new \App\Models\Backend\Transfer_warehouses;
          }

          $sRow->general_receive_id_fk    = request('general_receive_id_fk');
          $sRow->product_id_fk    = request('product_id_fk');
          $sRow->amt    = request('amt');
          $sRow->product_unit_id_fk    = request('product_unit_id_fk');
          $sRow->warehouse_id_fk    = request('warehouse_id_fk');
          $sRow->subwarehouse_id_fk    = request('subwarehouse_id_fk');
          $sRow->zone_id_fk    = request('zone_id_fk');
          $sRow->shelf_id_fk    = request('shelf_id_fk');
          $sRow->recipient    = request('recipient');
          $sRow->approver    = request('approver');
          $sRow->approve_status    = request('approve_status');
          $sRow->getin_date = date('Y-m-d H:i:s');

          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/transfer_warehouses/".$sRow->id."/edit"));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Transfer_warehousesController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Transfer_warehouses::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Transfer_warehouses::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('recipient_name', function($row) {
        $sD = DB::select(" select * from ck_users_admin where id=".$row->recipient." ");
        return $sD[0]->name;
      })
      ->addColumn('product_code', function($row) {
        $P = \App\Models\Backend\Products::find($row->product_id_fk);
        return $P->product_code;
      })
      ->addColumn('getin_date', function($row) {
        $d = strtotime($row->getin_date); 
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('getin_code', function($row) {
        return sprintf("%05d",$row->general_receive_id_fk);
      })
      ->addColumn('product_unit', function($row) {
        $sD = DB::select(" select * from dataset_product_unit where id=".$row->product_unit_id_fk." ");
        return $sD[0]->product_unit;
      })
      ->addColumn('warehouse', function($row) {
        $sD = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
        return $sD[0]->w_name;
      })  
      ->addColumn('subwarehouse', function($row) {
        $sD = DB::select(" select * from subwarehouse where id=".$row->subwarehouse_id_fk." ");
        return $sD[0]->w_name;
      })  
      ->addColumn('zone', function($row) {
        $sD = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
        return $sD[0]->w_name;
      })  
      ->addColumn('shelf', function($row) {
        $sD = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
        return $sD[0]->w_name;
      })                       
      ->make(true);
    }



}
