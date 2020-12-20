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

      $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1");

      $User_branch_id = \Auth::user()->branch_id_fk;
      // dd($User_branch_id);
      $sBranchs = \App\Models\Backend\Branchs::get();

      // $Warehouse = \App\Models\Backend\Warehouse::where('id','1')->get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      // dd($Warehouse[0]->w_name);
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

      $sTransfer_chooseAll = \App\Models\Backend\Transfer_choose::where('action_user','=',(\Auth::user()->id))->get();
      // dd(\Auth::user()->id);
      // dd(count($sTransfer_chooseAll));
      // dd($sTransfer_chooseAll);
      $sTransfer_choose = \App\Models\Backend\Transfer_choose::where('warehouse_id_fk','=','0')->where('action_user','=',(\Auth::user()->id))->get();
      // dd(count($sTransfer_choose));

        return View('backend.transfer_warehouses.index')->with(
        array(
           'Products'=>$Products,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sTransfer_choose'=>$sTransfer_choose,'sTransfer_chooseAll'=>$sTransfer_chooseAll,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id
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
      return View('backend.transfer_warehouses.form')->with(
        array(
           'receive_id'=>$receive_id,'Products'=>$Products,'ProductsUnit'=>$ProductsUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf
        ) );
    }
    public function store(Request $request)
    {

      if(isset($request->save_select_to_transfer)){
        // dd($request->all());

        for ($i=0; $i < count($request->id) ; $i++) { 
            $Check_stock = \App\Models\Backend\Check_stock::find($request->id[$i]);
            // echo $Check_stock->product_id_fk;
            $sRow = new \App\Models\Backend\Transfer_choose;
            $sRow->branch_id_fk    = request('branch_id_select_to_transfer');
            $sRow->stocks_id_fk    = $Check_stock->id;
            $sRow->product_id_fk    = $Check_stock->product_id_fk;
            $sRow->lot_number    = $Check_stock->lot_number;
            $sRow->lot_expired_date    = $Check_stock->lot_expired_date;
            $sRow->amt    = request('amt_transfer')[$i];
            $sRow->product_unit_id_fk    = $Check_stock->product_unit_id_fk;
            $sRow->date_in_stock    = $Check_stock->date_in_stock;
            $sRow->action_user = \Auth::user()->id;
            $sRow->action_date = date('Y-m-d H:i:s');
            $sRow->created_at = date('Y-m-d H:i:s');
            if(!empty(request('amt_transfer')[$i])){
              $sRow->save();
            }
          }

          return redirect()->to(url("backend/transfer_warehouses"));

      }else if(isset($request->save_set_to_warehouse)){

        // dd($request->all());
              if($request->save_set_to_warehouse==1){
                DB::update(" 
                    UPDATE db_transfer_choose 
                    SET branch_id_fk = ".$request->branch_id_set_to_warehouse.",
                     warehouse_id_fk = ".$request->warehouse_id_fk_c."  ,
                     zone_id_fk = ".$request->zone_id_fk_c."  ,
                     shelf_id_fk = ".$request->shelf_id_fk_c."  
                    where id=".$request->id_set_to_warehouse." 
                ");
              }

              return redirect()->to(url("backend/transfer_warehouses"));

      }else if(isset($request->save_set_to_warehouse_e)){

        // dd($request->all());
              if($request->save_set_to_warehouse_e==1){
                DB::update(" 
                    UPDATE db_transfer_choose 
                    SET branch_id_fk = ".$request->branch_id_set_to_warehouse_e.",
                     warehouse_id_fk = ".$request->warehouse_id_fk_c_e."  ,
                     zone_id_fk = ".$request->zone_id_fk_c_e."  ,
                     shelf_id_fk = ".$request->shelf_id_fk_c_e."  
                    where id=".$request->id_set_to_warehouse_e." 
                ");
              }

              return redirect()->to(url("backend/transfer_warehouses"));
      
      }else if(isset($request->save_select_to_cancel)){     

          DB::update(" UPDATE db_transfer_warehouses_code 
            SET 
            approve_status=2 , note = '".$request->note_to_cancel."'
            WHERE id=".$request->id_to_cancel." ; ");
          
          return redirect()->to(url("backend/transfer_warehouses"));

      }else{
         // dd($request->all());
        return $this->form();
      }
      
    }

    public function edit($id)
    {

        // dd($id);
        $sRow = \App\Models\Backend\Transfer_warehouses_code::find($id);
        // dd($sRow);
        return View('backend.transfer_warehouses.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id
        ) );
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      return $this->form($id);
    }

   public function form($id=NULL)
    {

      // dd(request('id'));

      $id=request('id');

      // dd($id);
      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow = \App\Models\Backend\Transfer_warehouses_code::find($id);
          }else{
            $sRow = new \App\Models\Backend\Transfer_warehouses_code;
          }

          $sRow->approver    = \Auth::user()->id;
          $sRow->approve_status    = request('approve_status');
          $sRow->note    = request('note');
          $sRow->approve_date = date('Y-m-d H:i:s');

          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/transfer_warehouses/".$id."/edit?list_id=".$id.""));
           

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
      ->addColumn('lot_expired_date', function($row) {
        $d = strtotime($row->lot_expired_date); 
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('action_date', function($row) {
        $d = strtotime($row->action_date); 
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('warehouses', function($row) {
        $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
        return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name;
      })  
      ->addColumn('amt_in_warehouse', function($row) {
        // $Check_stock = \App\Models\Backend\Check_stock::where('id',$row->stock_id_fk);
        if($row->stocks_id_fk!=''){
          $Check_stock = DB::select(" select * from db_stocks where id=".$row->stocks_id_fk." ");
          return @$Check_stock[0]->amt;
        }
        
      })     
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


}
