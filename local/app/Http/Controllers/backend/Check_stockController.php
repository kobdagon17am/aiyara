<?php

namespace App\Http\Controllers\backend;

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
      WHERE lang_id=1 
      order by products.product_code

      ");

      $Check_stock = \App\Models\Backend\Check_stock::get();

      $lot_number = DB::select(" select lot_number from db_stocks where business_location_id_fk=".@\Auth::user()->business_location_id_fk." GROUP BY lot_number  ");


      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
        return $query->where('id', auth()->user()->business_location_id_fk);
      })->get();
      $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
        return $query->where('id', auth()->user()->branch_id_fk);
      })->get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      // dd($Warehouse);
      // dd(\Auth::user()->branch_id_fk);
      if(@\Auth::user()->permission==1){
        $Warehouse = \App\Models\Backend\Warehouse::get();
      }else{
        $Warehouse = \App\Models\Backend\Warehouse::where('branch_id_fk',\Auth::user()->branch_id_fk)->get();
      }

      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

      return View('backend.check_stock.index')->with(
        array(
           'Products'=>$Products,'Check_stock'=>$Check_stock,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id,'sBusiness_location'=>$sBusiness_location,'lot_number'=>$lot_number
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
            $total = @$request->total? str_replace(',','',$request->total):0 ;
            $d_ch = explode(":",$request->date);

         $sBalance = DB::select(" SELECT sum(amt) as amt FROM db_stocks WHERE product_id_fk='".$id."' AND lot_number='".$request->lot_number."' AND lot_expired_date <= '".$d_ch[1]."' ");

         // dd($sBalance);

        $sRow = \App\Models\Backend\Check_stock::where('product_id_fk',$id)->get();
        // dd($sRow);

        $b = DB::select(" select * from branchs where id=".$sRow[0]->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$sRow[0]->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$sRow[0]->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$sRow[0]->shelf_id_fk." ");
        // $wh = @$b[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$sRow[0]->shelf_floor;
        $wh = @$b[0]->b_name;

         return View('backend.check_stock.stock_card')->with(
         array(
           'sRow'=>$sRow,
           'Products'=>$Products,
           'lot_number'=>$lot_number,
           'sBalance'=>$sBalance,
           'date_s_e'=>$request->date,
           'wh'=>$wh,
           'total'=>$total,
         ));

    }


    public function stock_card_01(Request $request,$id)
    {
         // dd($request->lot_number);
        // dd($request->date);
        // dd($id);
        
          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$id." AND lang_id=1");

            $lot_number = $request->lot_number;
            $total = @$request->total? str_replace(',','',$request->total):0 ;
            $d_ch = explode(":",$request->date);

         $sBalance = DB::select(" SELECT sum(amt) as amt FROM db_stocks WHERE product_id_fk='".$id."' AND lot_number='".$request->lot_number."' AND lot_expired_date <= '".$d_ch[1]."' ");

         // dd($sBalance);

        $sRow = \App\Models\Backend\Check_stock::where('product_id_fk',$id)->get();
        // dd($sRow);

        $w_wh = explode(":",$request->wh);

        $b = DB::select(" select * from branchs where id=".$sRow[0]->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$w_wh[0]." ");
        $zone = DB::select(" select * from zone where id=".$w_wh[1]." ");
        $shelf = DB::select(" select * from shelf where id=".$w_wh[2]." ");
        $wh = @$b[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$w_wh[3];
        // $wh = @$b[0]->b_name;

         return View('backend.check_stock.stock_card_01')->with(
         array(
           'sRow'=>$sRow,
           'Products'=>$Products,
           'lot_number'=>$lot_number,
           'sBalance'=>$sBalance,
           'date_s_e'=>$request->date,
           'wh'=>$wh,
           'total'=>$total,
           'warehouse_id_fk'=>$w_wh[0],
           'zone_id_fk'=>$w_wh[1],
           'shelf_id_fk'=>$w_wh[2],
           'shelf_floor'=>$w_wh[3],
         ));

    }

    public function Datatable(Request $req){

        if(!empty($req->business_location_id_fk)){
           $w01 = "  AND business_location_id_fk=".$req->business_location_id_fk ;
        }else{
           $w01 = "";
        }

        if(!empty($req->branch_id_fk)){
           $w02 = " AND branch_id_fk = ".$req->branch_id_fk." " ;
        }else{
           $w02 = "" ;
        }
        if(!empty($req->warehouse_id_fk)){
           $w03 = " AND warehouse_id_fk = ".$req->warehouse_id_fk." " ;
        }else{
           $w03 = "" ;
        }
        if(!empty($req->zone_id_fk)){
           $w04 = " AND zone_id_fk = ".$req->zone_id_fk." " ;
        }else{
           $w04 = "" ;
        }

        if(!empty($req->shelf_id_fk)){
           $w05 = " AND shelf_id_fk = ".$req->shelf_id_fk." " ;
        }else{
           $w05 = "" ;
        }

        if(!empty($req->shelf_floor)){
           $w06 = " AND shelf_floor = ".$req->shelf_floor." " ;
        }else{
           $w06 = "" ;
        }

        if(!empty($req->lot_number)){
           $w07 = " AND lot_number = '".$req->lot_number."' " ;
        }else{
           $w07 = "" ;
        }

        if(!empty($req->product)){
           $w08 = " AND product_id_fk = '".$req->product."' " ;
        }else{
           $w08 = "" ;
        }

        if(!empty($req->start_date) && !empty($req->end_date)){
           $w09 = " and date(lot_expired_date) BETWEEN '".$req->start_date."' AND '".$req->end_date."'  " ;
        }else{
           // $w09 = " and date(lot_expired_date) >= CURDATE() ";
           $w09 = "";
        }

    // start_date:start_date,
    // end_date:end_date,

      // $sTable = \App\Models\Backend\Check_stock::search()->orderBy('updated_at', 'desc');
      $sTable = DB::select("
            SELECT concat(product_id_fk,lot_number) as gr,db_stocks.*
            FROM
            db_stocks
            WHERE 1
            $w01
            $w02
            $w03
            $w04
            $w05
            $w06
            $w07
            $w08
            $w09

         ");
  
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
      // ->addColumn('lot_expired_date', function($row) {
      //   $d = strtotime($row->lot_expired_date);
      //   return date("d/m/", $d).(date("Y", $d)+543);
      // })
      ->addColumn('warehouses', function($row) {
        $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
        return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$row->shelf_floor;
      })
      ->addColumn('stock_card', function($row) {
        // return "<a class='btn btn-outline-success waves-effect waves-light' style='padding: initial;padding-left: 2px;padding-right: 2px;'  > STOCK CARD </a> ";
      })
      ->escapeColumns('stock_card')
      ->make(true);
    }


}
