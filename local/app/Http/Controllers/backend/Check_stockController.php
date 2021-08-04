<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Check_stockController extends Controller
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

      $Check_stock = \App\Models\Backend\Check_stock::get();
   

      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

      return View('backend.check_stock.index')->with(
        array(
           'Products'=>$Products,'Check_stock'=>$Check_stock,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id,'sBusiness_location'=>$sBusiness_location,
        ) );

    }

    public function stock_card(Request $request,$id)
    {
        // dd($request->lot_number);
        // dd($request->date);
        // dd($id);
        // dd($request);
          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$id." AND lang_id=1");

            $lot_number = $request->lot_number;
            $d_ch = explode(":",$request->date);

         $sBalance = DB::select(" SELECT sum(amt) as amt FROM db_stocks WHERE product_id_fk='".$id."' AND lot_number='".$request->lot_number."' AND lot_expired_date <= '".$d_ch[1]."' ");

         // dd($sBalance);

        $sRow = \App\Models\Backend\Check_stock::where('id',$id)->get();
        // dd($sRow);

        $b = DB::select(" select * from branchs where id=".$sRow[0]->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$sRow[0]->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$sRow[0]->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$sRow[0]->shelf_id_fk." ");
        $wh = @$b[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$sRow[0]->shelf_floor;

         return View('backend.check_stock.stock_card')->with(
         array(
           'Products'=>$Products,
           'lot_number'=>$lot_number,
           'sBalance'=>$sBalance,
           'date_s_e'=>$request->date,
           'wh'=>$wh,
         ));

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

      

      if(isset($req->id)){
        $sTable = \App\Models\Backend\Check_stock::where('id',$req->id)->orderBy('product_id_fk', 'asc')->orderBy('lot_number', 'asc');
      }else{
        $sTable = \App\Models\Backend\Check_stock::where('lot_expired_date',">=",date("Y-m-d"))->search()->orderBy('product_id_fk', 'asc')->orderBy('lot_number', 'asc');
      }
// TEST
      // $sTable = \App\Models\Backend\Check_stock::where('id',0)->orderBy('product_id_fk', 'asc')->orderBy('lot_number', 'asc');
      
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
