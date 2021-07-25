<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Transfer_choose_branchController extends Controller
{

    public function index(Request $request)
    {
      return view('backend.transfer_choose_branch.index');
    }

    public function create()
    {
    }
    public function store(Request $request)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

   public function form($id=NULL)
    {
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Transfer_choose_branch::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      // ข้อมูลตาม user login ไม่งั้นจะทำพร้อมกันไม่ได้ 
      $sTable = \App\Models\Backend\Transfer_choose_branch::where('action_user',\Auth::user()->id)->search()->orderBy('id', 'asc');
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
      ->addColumn('date_in_stock', function($row) {
        $d = strtotime($row->pickup_firstdate); 
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('amt_in_warehouse', function($row) {
        // $Check_stock = \App\Models\Backend\Check_stock::where('id',$row->stock_id_fk);
        if($row->stocks_id_fk!=''){
          $Check_stock = DB::select(" select * from db_stocks where id=".$row->stocks_id_fk." ");
          return @$Check_stock[0]->amt;
        }
        
      })      
      ->addColumn('branch_to', function($row) {
        $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
        $sBranchsTo = DB::select(" select * from branchs where id=".$row->branch_id_fk_to." ");
        $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
        if($row->branch_id_fk_to!=""){
          return @$sBranchsTo[0]->b_name;
        }else{
          return 0;
        }
      })      
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


}
