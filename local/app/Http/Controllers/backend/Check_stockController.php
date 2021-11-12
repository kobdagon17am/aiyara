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
        // dd($id);
        // dd($request->business_location_id_fk);
        $business_location_id_fk = $request->business_location_id_fk;
        $branch_id_fk = $request->branch_id_fk;
        // dd($request->branch_id_fk);
        if(!empty($business_location_id_fk)){
            $wh_01 = " AND business_location_id_fk = ".$business_location_id_fk." ";
        }else{
            $wh_01 = "" ; 
        }

        if(!empty($branch_id_fk)){
            $wh_02 = " AND branch_id_fk = ".$branch_id_fk." ";
        }else{
            $wh_02 = "" ; 
        }

// date(lot_expired_date) >= CURDATE() 
        $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$id." AND lang_id=1");

         $sBalance = DB::select(" SELECT sum(amt) as amt
         FROM `db_stocks`
         WHERE 1
         AND product_id_fk=$id 

         $wh_01
         $wh_02

         GROUP BY product_id_fk
         ");
         // dd($Products);
         // dd($sBalance);
        $p_name = @$Products[0]->product_code." : ".@$Products[0]->product_name;
         return View('backend.check_stock.stock_card')->with(
         array(
           'Products'=>$Products,
           'p_name'=>$p_name,
           'sBalance'=>$sBalance,
           'business_location_id_fk'=>$business_location_id_fk,
           'branch_id_fk'=>$branch_id_fk,
         ));

    }


    public function stock_card_01($id)
    {
        // dd($id);

         $Stock = \App\Models\Backend\Check_stock::where('id',$id)->get();
        // dd($Stock[0]->product_id_fk);
        
          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$Stock[0]->product_id_fk." AND lang_id=1");

           $p_name = @$Products[0]->product_code." : ".@$Products[0]->product_name;

// date(lot_expired_date) >= CURDATE()
          $sBalance= DB::select(" SELECT (case when amt>0 then sum(amt) else 0 end) as amt FROM `db_stocks` where 1 AND product_id_fk=".$Stock[0]->product_id_fk." 
          AND lot_number='".$Stock[0]->lot_number."' 
          AND lot_expired_date='".$Stock[0]->lot_expired_date."' 
          AND warehouse_id_fk='".$Stock[0]->warehouse_id_fk."' 
          AND zone_id_fk='".$Stock[0]->zone_id_fk."' 
          AND shelf_id_fk='".$Stock[0]->shelf_id_fk."' 
          AND shelf_floor='".$Stock[0]->shelf_floor."' 
          GROUP BY product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor ORDER BY lot_number ");

          $lot_number = $Stock[0]->lot_number;
          $lot_expired_date = $Stock[0]->lot_expired_date;


        $b = DB::select(" select * from branchs where id=".($Stock[0]->branch_id_fk?$Stock[0]->branch_id_fk:0)." ");
        $warehouse = DB::select(" select * from warehouse where id=".($Stock[0]->warehouse_id_fk?$Stock[0]->warehouse_id_fk:0)." ");
        $zone = DB::select(" select * from zone where id=".($Stock[0]->zone_id_fk?$Stock[0]->zone_id_fk:0)." ");
        $shelf = DB::select(" select * from shelf where id=".($Stock[0]->shelf_id_fk?$Stock[0]->shelf_id_fk:0)." ");
        $wh = 'สาขา '.(@$b[0]->b_name?@$b[0]->b_name:'').'/'.(@$warehouse[0]->w_name?@$warehouse[0]->w_name:'').'/'.(@$zone[0]->z_name?@$zone[0]->z_name:'').'/'.(@$shelf[0]->s_name?@$shelf[0]->s_name:'').'/ชั้น>'.($Stock[0]->shelf_floor?$Stock[0]->shelf_floor:'');

         return View('backend.check_stock.stock_card_01')->with(
         array(
            'id'=>$id,
            'Stock'=>$Stock,
            'p_name'=>$p_name,
           'lot_number'=>$lot_number,
           'lot_expired_date'=>$lot_expired_date,
           'sBalance'=>$sBalance,
           'wh'=>$wh,
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
           $w07 = ' AND lot_number = "' .$req->lot_number.'" ' ;
        }else{
           $w07 = "" ;
        }

        if(!empty($req->product)){
           $w08 = " AND product_id_fk = ".$req->product." " ;
        }else{
           $w08 = "" ;
        }

        if(!empty($req->start_date) && !empty($req->end_date)){
           $w09 = ' and date(lot_expired_date) BETWEEN "'.$req->start_date.'" AND "'.$req->end_date.'"  ';
        }else{
           // $w09 = " and date(lot_expired_date) >= CURDATE() ";
           $w09 = "";
        }

       $sTable = DB::select("

        SELECT id,product_id_fk,sum(amt) as amt,
        '$w01' as w01,
        '$w02' as w02,
        '$w03' as w03,
        '$w04' as w04,
        '$w05' as w05,
        '$w06' as w06,
        '$w07' as w07,
        '$w08' as w08,
        '$w09' as w09
         FROM `db_stocks`
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
        GROUP BY product_id_fk
        ORDER BY db_stocks.id

        ");
// date(lot_expired_date) >= CURDATE()
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
// date(lot_expired_date) >= CURDATE()
      ->addColumn('lot_number', function($row) {
            $d = DB::select(" SELECT lot_number FROM `db_stocks` where 1 AND product_id_fk=".$row->product_id_fk." 

          ".$row->w01."
          ".$row->w02."
          ".$row->w03."
          ".$row->w04."
          ".$row->w05."
          ".$row->w06."
          ".$row->w07."
          ".$row->w08."
          ".$row->w09."

          GROUP BY product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor ORDER BY lot_number ");
            $f = [] ;
            foreach ($d as $key => $v) {
               array_push($f,$v->lot_number);
            }
            $f = implode('<br>',$f);
            return $f;

      })
      ->addColumn('lot_expired_date', function($row) {
        // $d = strtotime($row->lot_expired_date);
            $d = DB::select(" SELECT lot_expired_date FROM `db_stocks` where 1 AND product_id_fk=".$row->product_id_fk." 

          ".$row->w01."
          ".$row->w02."
          ".$row->w03."
          ".$row->w04."
          ".$row->w05."
          ".$row->w06."
          ".$row->w07."
          ".$row->w08."
          ".$row->w09."

            GROUP BY product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor ORDER BY lot_number ");
            $f = [] ;
            foreach ($d as $key => $v) {
               array_push($f,$v->lot_expired_date);
            }
            $f = implode('<br>',$f);
            return $f;
      })
       ->addColumn('amt_desc', function($row) {
        // $d = strtotime($row->lot_expired_date);
            $d = DB::select(" SELECT (case when amt>0 then sum(amt) else 0 end) as amt FROM `db_stocks` where 1 AND product_id_fk=".$row->product_id_fk." 
          
          ".$row->w01."
          ".$row->w02."
          ".$row->w03."
          ".$row->w04."
          ".$row->w05."
          ".$row->w06."
          ".$row->w07."
          ".$row->w08."
          ".$row->w09."

          GROUP BY product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor ORDER BY lot_number ");
            $f = [] ;
            foreach ($d as $key => $v) {
               array_push($f,$v->amt);
            }
            $f = implode('<br>',$f);
            return $f;
      })
      ->addColumn('warehouses', function($row) {
        // $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
        // $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
        // $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
        // $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
        // return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$row->shelf_floor;
        // date(lot_expired_date) >= CURDATE()

            $d = DB::select(" SELECT * FROM `db_stocks` where 1 AND product_id_fk=".$row->product_id_fk."

          ".$row->w01."
          ".$row->w02."
          ".$row->w03."
          ".$row->w04."
          ".$row->w05."
          ".$row->w06."
          ".$row->w07."
          ".$row->w08."
          ".$row->w09."

           GROUP BY product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor ORDER BY lot_number ");
            $f = [] ;
            foreach ($d as $key => $v) {
                    
                    $sBranchs = DB::select(" select * from branchs where id=".$v->branch_id_fk." ");
                    $warehouse = DB::select(" select * from warehouse where id=".($v->warehouse_id_fk?$v->warehouse_id_fk:0)." ");
                    $zone = DB::select(" select * from zone where id=".($v->zone_id_fk?$v->zone_id_fk:0)." ");
                    $shelf = DB::select(" select * from shelf where id=".($v->shelf_id_fk?$v->shelf_id_fk:0)." ");
                    $t = @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$v->shelf_floor;

               array_push($f,$t);
            }
            $f = implode('<br>',$f);
            return $f;


      })
      ->addColumn('stock_card', function($row) {
        // return "<a class='btn btn-outline-success waves-effect waves-light' style='padding: initial;padding-left: 2px;padding-right: 2px;'  > STOCK CARD </a> ";
// date(lot_expired_date) >= CURDATE()
            $d = DB::select(" SELECT id FROM `db_stocks` where 1 AND product_id_fk=".$row->product_id_fk." 
          
          ".$row->w01."
          ".$row->w02."
          ".$row->w03."
          ".$row->w04."
          ".$row->w05."
          ".$row->w06."
          ".$row->w07."
          ".$row->w08."
          ".$row->w09."

          GROUP BY product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor ORDER BY lot_number ");
            $f = [] ;
            foreach ($d as $key => $v) {
               array_push($f,'<a class="btn btn-outline-success waves-effect waves-light" href="backend/check_stock/stock_card_01/'.$v->id.'" style="padding: initial;padding-left: 2px;padding-right: 2px;color:black;" target=_blank > STOCK CARD </a>');
            }
            $f = implode('<br>',$f);

           return $f;
           // return '* อยู่ระหว่างปรับปรุง';

      })
      ->escapeColumns('stock_card')
      ->make(true);
    }


        public function DatatableBorrow(Request $req){


              $sTable = \App\Models\Backend\Check_stock::where('branch_id_fk',$req->branch_id_fk)->where('product_id_fk',$req->product_id_fk);
              
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
              ->addColumn('warehouses', function($row) {
                $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
                $warehouse = DB::select(" select * from warehouse where id=".($row->warehouse_id_fk?$row->warehouse_id_fk:0)." ");
                $zone = DB::select(" select * from zone where id=".($row->zone_id_fk?$row->zone_id_fk:0)." ");
                $shelf = DB::select(" select * from shelf where id=".($row->shelf_id_fk?$row->shelf_id_fk:0)." ");
                return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$row->shelf_floor;
              })      
              ->addColumn('stock_card', function($row) {
                // return "<a class='btn btn-outline-success waves-effect waves-light' style='padding: initial;padding-left: 2px;padding-right: 2px;'  > STOCK CARD </a> ";
              })
              ->escapeColumns('stock_card')
              ->make(true);
            }





        public function DatatableTransfer_warehouses(Request $req){


              $sTable = \App\Models\Backend\Check_stock::where('branch_id_fk',$req->branch_id_fk)->where('product_id_fk',$req->product_id_fk);
              
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
              ->addColumn('warehouses', function($row) {
                $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
                $warehouse = DB::select(" select * from warehouse where id=".($row->warehouse_id_fk?$row->warehouse_id_fk:0)." ");
                $zone = DB::select(" select * from zone where id=".($row->zone_id_fk?$row->zone_id_fk:0)." ");
                $shelf = DB::select(" select * from shelf where id=".($row->shelf_id_fk?$row->shelf_id_fk:0)." ");
                return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$row->shelf_floor;
              })      
              ->addColumn('stock_card', function($row) {
                // return "<a class='btn btn-outline-success waves-effect waves-light' style='padding: initial;padding-left: 2px;padding-right: 2px;'  > STOCK CARD </a> ";
              })
              ->escapeColumns('stock_card')
              ->addColumn('amt_now', function($row) {
                    $d1 = DB::select("

                        SELECT
sum(amt) AS amt
FROM
db_transfer_warehouses_details
Inner Join db_transfer_warehouses_code ON db_transfer_warehouses_details.transfer_warehouses_code_id = db_transfer_warehouses_code.id
                        where db_transfer_warehouses_details.stocks_id_fk=".$row->id." and db_transfer_warehouses_details.remark=1 and db_transfer_warehouses_details.approve_status=0
                        and db_transfer_warehouses_code.approve_status=0 ");
                    $d2 = DB::select("SELECT sum(amt) as amt FROM `db_transfer_choose` where stocks_id_fk=".$row->id." ");
                    $amt1 = @$d1[0]->amt?$d1[0]->amt:0;
                    $amt2 = @$d2[0]->amt?$d2[0]->amt:0;
                    $r = @$row->amt - $amt1 - $amt2 ;
                    return $r>0?$r:0;
              })
              ->make(true);
            }



        public function DatatableTransfer_branch(Request $req){


              $sTable = \App\Models\Backend\Check_stock::where('branch_id_fk',$req->branch_id_fk)->where('product_id_fk',$req->product_id_fk);
              
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
              ->addColumn('warehouses', function($row) {
                $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
                if(@$row->warehouse_id_fk){
                    $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
                    $warehouse= @$warehouse[0]->w_name;
                }else{
                    $warehouse = '-';
                }
                if(@$row->zone_id_fk){
                    $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
                    $zone = @$zone[0]->z_name;
                }else{
                    $zone = '-';
                }
                if(@$row->shelf_id_fk){
                    $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
                    $shelf = @$shelf[0]->s_name;
                }else{
                    $shelf = '-';
                }

                return @$sBranchs[0]->b_name.'/'.@$warehouse.'/'.@$zone.'/'.@$shelf.'/ชั้น>'.(@$row->shelf_floor?@$row->shelf_floor:"-");
                // return @$sBranchs[0]->b_name.'/'.@$warehouse;
                // return $row->warehouse_id_fk;
              })      
              ->addColumn('stock_card', function($row) {
                // return "<a class='btn btn-outline-success waves-effect waves-light' style='padding: initial;padding-left: 2px;padding-right: 2px;'  > STOCK CARD </a> ";
              })
              ->escapeColumns('stock_card')
              ->make(true);
            }


}
