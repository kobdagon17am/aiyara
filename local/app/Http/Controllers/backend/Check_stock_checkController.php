<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Check_stock_checkController extends Controller
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

      $Check_stock_check = \App\Models\Backend\Check_stock_check::get();

      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

      return View('backend.check_stock_check.index')->with(
        array(
           'Products'=>$Products,'Check_stock_check'=>$Check_stock_check,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id,'sBusiness_location'=>$sBusiness_location,
        ) );

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
    }

    public function Datatable(Request $req){

      // if(isset($req->id)){
        $sTable = \App\Models\Backend\Check_stock_check::where('stocks_account_code_id_fk',$req->id);
      // }else{
      //   $sTable = \App\Models\Backend\Check_stock_check::search()->orderBy('id', 'asc');
      // }
      
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // ->addColumn('ref_code', function($row) {
        /*
        A + business location + ymd + 00001
        */
      //   $d = strtotime($row->lot_expired_date); 
      //   return date("d/m/", $d).(date("Y", $d)+543);
      // })

      ->addColumn('action_user_id', function($row) {
        $sTable = DB::select("SELECT action_user FROM db_stocks_account_code  WHERE id=$row->stocks_account_code_id_fk ");
        return @$sTable[0]->action_user;
      }) 

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
      ->addColumn('lot_number', function($row) {
        $d = strtotime($row->lot_expired_date); 
        return $row->lot_number." : ".date("d/m/", $d).(date("Y", $d)+543);
      })
      // ->addColumn('date_in_stock', function($row) {
      //   $d = strtotime($row->pickup_firstdate); 
      //   return date("d/m/", $d).(date("Y", $d)+543);
      // })
      ->addColumn('warehouses', function($row) {
        $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
        return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$row->shelf_floor;
      })      
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


    public function Datatable02(Request $req){

      $sTable = \App\Models\Backend\Check_stock_check::where('id',$req->id);
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
      ->addColumn('lot_number', function($row) {
        $d = strtotime($row->lot_expired_date); 
        return $row->lot_number." : ".date("d/m/", $d).(date("Y", $d)+543);
      })
      // ->addColumn('date_in_stock', function($row) {
      //   $d = strtotime($row->pickup_firstdate); 
      //   return date("d/m/", $d).(date("Y", $d)+543);
      // })
      ->addColumn('warehouses', function($row) {
        $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
        return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$row->shelf_floor;
      })      
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


}
