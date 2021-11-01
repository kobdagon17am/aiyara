<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;

class Stock_cardController extends Controller
{

    public function index(Request $request)
    {
       // dd($request);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
      // dd($request->all());
    }

    public function edit($id)
    {
       // dd($id);
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
    }

   public function form($id=NULL)
    {
  
    }

    public function destroy($id)
    {
      
    }

    
    public function Datatable(){
      // $sTable = \App\Models\Backend\Stock_card::search()->orderBy('action_date', 'asc');
      // ดึงข้อมูลเข้าตารางสรุป
      $temp_db_stock_card = "temp_db_stock_card".\Auth::user()->id;
      // return $temp_db_stock_card ;

      DB::select(" SET @csum := 0; ");
      // $sTable = DB::select(" 
      //     SELECT db_stock_card.*,
      //     CASE WHEN @csum + ( CASE WHEN amt_out>0 THEN -(amt_out) ELSE amt_in END ) > 0 THEN 
      //     (@csum := @csum + ( CASE WHEN amt_out>0 THEN -(amt_out) ELSE amt_in END )) ELSE 0 END
      //      as remain FROM db_stock_card ORDER BY action_date;
      //     ");
      $sTable = DB::select(" 
          SELECT $temp_db_stock_card.*,(@csum := @csum + ( CASE WHEN amt_out>0 THEN -(amt_out) ELSE amt_in END )) as remain FROM $temp_db_stock_card ORDER BY action_date;
          ");     
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
      ->addColumn('approver', function($row) {
        if(@$row->approver!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->approver." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      }) 
      ->addColumn('warehouses', function($row) {
        $sBranchs = DB::select(" select * from branchs where id=".(@$row->branch_id_fk>0?@$row->branch_id_fk:0)." ");
        $warehouse = DB::select(" select * from warehouse where id=".(@$row->warehouse_id_fk>0?@$row->warehouse_id_fk:0)." ");
        $zone = DB::select(" select * from zone where id=".(@$row->zone_id_fk>0?@$row->zone_id_fk:0)." ");
        $shelf = DB::select(" select * from shelf where id=".(@$row->shelf_id_fk>0?@$row->shelf_id_fk:0)." ");
        return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$row->shelf_floor;
      })   

      ->make(true);
    }


 
    public function Datatable01(Request $request){
  // $sTable = \App\Models\Backend\Stock_card::search()->orderBy('action_date', 'asc');
      // ดึงข้อมูลเข้าตารางสรุป
      $temp_db_stock_card = "temp_db_stock_card_01".\Auth::user()->id;
      // return $temp_db_stock_card ;

      DB::select(" SET @csum := 0; ");
      // $sTable = DB::select(" 
      //     SELECT db_stock_card.*,
      //     CASE WHEN @csum + ( CASE WHEN amt_out>0 THEN -(amt_out) ELSE amt_in END ) > 0 THEN 
      //     (@csum := @csum + ( CASE WHEN amt_out>0 THEN -(amt_out) ELSE amt_in END )) ELSE 0 END
      //      as remain FROM db_stock_card ORDER BY action_date;
      //     ");
      $sTable = DB::select(" 
          SELECT $temp_db_stock_card.*,(@csum := @csum + ( CASE WHEN amt_out>0 THEN -(amt_out) ELSE amt_in END )) as remain 
          FROM $temp_db_stock_card 
          WHERE 1 
          ORDER BY action_date;
          ");     
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
      ->addColumn('approver', function($row) {
        if(@$row->approver!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->approver." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })   

      ->make(true);
    }





}
