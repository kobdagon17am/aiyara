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

      // dd(\Auth::user()->permission .":". \Auth::user()->branch_id_fk.":". \Auth::user()->id);

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
        $b_l = \App\Models\Backend\Branchs::where('id',$User_branch_id)->get();

        $business_location_id_fk = $b_l[0]->business_location_id_fk;
      
     
      // dd($sTransfer_chooseAll);
      $sTransfer_choose = \App\Models\Backend\Transfer_choose::where('warehouse_id_fk','=','0')->where('action_user','=',(\Auth::user()->id))->get();
      // dd($sTransfer_choose);

        return View('backend.transfer_warehouses.index')->with(
        array(
           'Products'=>$Products,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sTransfer_choose'=>$sTransfer_choose,'sTransfer_chooseAll'=>$sTransfer_chooseAll,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id,
           'business_location_id_fk'=>$business_location_id_fk
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

      // dd($request->all());

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
                     shelf_id_fk = ".$request->shelf_id_fk_c."  ,
                     shelf_floor = ".$request->shelf_floor_c."  
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
                     shelf_id_fk = ".$request->shelf_id_fk_c_e.",  
                     shelf_floor = ".$request->shelf_floor_c_e."  
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
      if(!empty($request->approve_status) && $request->approve_status==1){
          // dd($request->approve_status);

        $rsWarehouses_details = DB::select("  
           select * from db_transfer_warehouses_details where transfer_warehouses_code_id = ".$request->id." ");
        $arr1 = [];
        foreach ($rsWarehouses_details as $key => $value) {
          array_push($arr1, $value->stocks_id_fk);
        }

        $arr1 = implode(",", $arr1);

        $rsStock = DB::select(" select * from  db_stocks  where id in (".$arr1.")  ");
              // dd($rsStock);
        foreach ($rsStock as $key => $value) {
            DB::update(" UPDATE db_transfer_warehouses_details SET stock_amt_before_up =".$value->amt." , stock_date_before_up = '".$value->date_in_stock."'  where transfer_warehouses_code_id = ".$request->id." and stocks_id_fk = ".$value->id."  ");
        }

         $rsWarehouses_details = DB::select("  
           select * from db_transfer_warehouses_details where transfer_warehouses_code_id = ".$request->id." ");
         foreach ($rsWarehouses_details as $key => $value) {
              DB::update(" UPDATE db_stocks SET amt = (amt - ".$value->amt.") where id =".$value->stocks_id_fk."  ");
         }

      }

      // dd();

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

           // return redirect()->to(url("backend/transfer_warehouses/".$id."/edit?list_id=".$id.""));
           return redirect()->to(url("backend/transfer_warehouses"));
           

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
        return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.$row->shelf_floor;
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
