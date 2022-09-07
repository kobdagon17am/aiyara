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
      // $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
      //   return $query->where('id', auth()->user()->business_location_id_fk);
      // })->get();

      if(auth()->user()->permission==1){
        $sBusiness_location = \App\Models\Backend\Business_location::get();
      }else{
        $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
          return $query->where('id', auth()->user()->business_location_id_fk);
        })->get();
      }

      if(auth()->user()->permission==1){
        $sBranchs = \App\Models\Backend\Branchs::get();
      }elseif(auth()->user()->position_level !== 5 && auth()->user()->position_level !== 0){
        $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
          return $query->where('business_location_id_fk', auth()->user()->business_location_id_fk);
        })->get();
      }else{
        $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
          return $query->where('id', auth()->user()->branch_id_fk);
        })->get();
      }

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



    public function stock_card(Request $request,$product_id_fk)
    {
        // dd($product_id_fk);
        // dd($request->business_location_id_fk);
        // dd($request->branch_id_fk);
             // return View('backend.stock_card_01.index');
          $business_location_id_fk = $request->business_location_id_fk;
          $branch_id_fk = $request->branch_id_fk;
          $lot_number = $request->lot_number;

          // dd($business_location_id_fk);

          if(!empty($request->business_location_id_fk) && $request->business_location_id_fk != 'null'){
            $w_business_location_id_fk_01 = "business_location_id_fk" ;
            $w_business_location_id_fk_02 = $request->business_location_id_fk ;
            $w_business_location_id_fk = " AND business_location_id_fk=".$request->business_location_id_fk."" ;
          }else{
            $w_business_location_id_fk_01 = "1" ;
            $w_business_location_id_fk_02 = "1" ;
            $w_business_location_id_fk = '';
          }
          // dd($w_business_location_id_fk_01);

          if(!empty($request->branch_id_fk) && $request->branch_id_fk != 'null'){
            $w_branch_id_fk_01 = "branch_id_fk" ;
            $w_branch_id_fk_02 = $request->branch_id_fk ;
            $w_branch_id_fk = " AND branch_id_fk=".$request->branch_id_fk."" ;
          }else{
            $w_branch_id_fk_01 = "1" ;
            $w_branch_id_fk_02 = "1" ;
            $w_branch_id_fk = '';
          }

          if(!empty($request->warehouse_id_fk) && $request->warehouse_id_fk != 'null'){
            $w_warehouse_id_fk_01 = "warehouse_id_fk" ;
            $w_warehouse_id_fk_02 = $request->warehouse_id_fk ;
            $w_warehouse_id_fk = " AND warehouse_id_fk=".$request->warehouse_id_fk."" ;
          }else{
            $w_warehouse_id_fk_01 = "1" ;
            $w_warehouse_id_fk_02 = "1" ;
            $w_warehouse_id_fk = '';
          }

          if(!empty($request->zone_id_fk) && $request->zone_id_fk != 'null'){
            $w_zone_id_fk_01 = "zone_id_fk" ;
            $w_zone_id_fk_02 = $request->zone_id_fk ;
            $w_zone_id_fk = " AND zone_id_fk=".$request->zone_id_fk."" ;
          }else{
            $w_zone_id_fk_01 = "1" ;
            $w_zone_id_fk_02 = "1" ;
            $w_zone_id_fk = '';
          }

          if(!empty($request->shelf_id_fk) && $request->shelf_id_fk != 'null'){
            $w_shelf_id_fk_01 = "shelf_id_fk" ;
            $w_shelf_id_fk_02 = $request->shelf_id_fk ;
            $w_shelf_id_fk = " AND shelf_id_fk=".$request->shelf_id_fk."" ;
          }else{
            $w_shelf_id_fk_01 = "1" ;
            $w_shelf_id_fk_02 = "1" ;
            $w_shelf_id_fk = '';
          }

          if(!empty($request->shelf_floor) && $request->shelf_floor != 'null'){
            $w_shelf_floor_01 = "shelf_floor" ;
            $w_shelf_floor_02 = $request->shelf_floor ;
            $w_shelf_floor = " AND shelf_floor=".$request->shelf_floor."" ;
          }else{
            $w_shelf_floor_01 = "1" ;
            $w_shelf_floor_02 = "1" ;
            $w_shelf_floor = '';
          }

          if(!empty($request->lot_number) && $request->lot_number != 'null'){
            $w_lot_number_01 = "lot_number" ;
            $w_lot_number_02 = $request->lot_number ;
            $w_lot_number = " AND lot_number=".$request->lot_number."" ;
          }else{
            $w_lot_number_01 = "1" ;
            $w_lot_number_02 = "1" ;
            $w_lot_number = '';
          }

// dd($w_business_location_id_fk_01);

           $Stock = \App\Models\Backend\Check_stock::where('product_id_fk',$product_id_fk)
           ->where(DB::raw($w_business_location_id_fk_01), "=", $w_business_location_id_fk_02)
           ->where(DB::raw($w_branch_id_fk_01), "=", $w_branch_id_fk_02)
           ->where(DB::raw($w_warehouse_id_fk_01), "=", $w_warehouse_id_fk_02)
           ->where(DB::raw($w_zone_id_fk_01), "=", $w_zone_id_fk_02)
           ->where(DB::raw($w_shelf_id_fk_01), "=", $w_shelf_id_fk_02)
           ->where(DB::raw($w_shelf_floor_01), "=", $w_shelf_floor_02)
           ->where(DB::raw($w_lot_number_01), "=", $w_lot_number_02)
           ->get();
           // dd($Stock);

        if(!empty($Stock[0]->product_id_fk)){

          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$Stock[0]->product_id_fk." AND lang_id=1");

           $p_name = @$Products[0]->product_code." : ".@$Products[0]->product_name;

           $Amt= DB::select(" SELECT amt
            FROM `db_stocks`
            where 1

            AND product_id_fk=".@$Stock[0]->product_id_fk."

            $w_business_location_id_fk
            $w_branch_id_fk
            $w_warehouse_id_fk
            $w_zone_id_fk
            $w_shelf_id_fk
            $w_shelf_floor
            $w_lot_number

            GROUP BY branch_id_fk,product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor

            ");

        }

        $sBalance = 0;
        if(@$Amt){
            foreach ($Amt as $key => $value) {
                $sBalance+=$value->amt;
            }
        }
        // dd($w_business_location_id_fk);
        // dd($w_branch_id_fk);
        // dd($sBalance);
        // dd($sBalance);

         return View('backend.check_stock.stock_card')->with(
         array(
            'product_id_fk'=>$product_id_fk,
            'business_location_id_fk'=>$business_location_id_fk,
            'branch_id_fk'=>$branch_id_fk,
            'lot_number'=>@$lot_number?$lot_number:'',
            'p_name'=>@$p_name?@$p_name:'',
            'sBalance'=>@$sBalance?@$sBalance:0,
         ));

    }


    public function show()
    {
        return redirect()->to(url("backend/check_stock"));
    }


    public function Datatable(Request $req){

      if(isset($req->Where['business_location_id_fk'])){
        if($req->Where['business_location_id_fk']!=''){
          $req->business_location_id_fk = $req->Where['business_location_id_fk'];
        }
      }

      if(isset($req->Where['branch_id_fk'])){
        if($req->Where['branch_id_fk']!=''){
          $req->branch_id_fk = $req->Where['branch_id_fk'];
        }
      }
      if(isset($req->Where['warehouse_id_fk'])){
        if($req->Where['warehouse_id_fk']!=''){
          $req->warehouse_id_fk = $req->Where['warehouse_id_fk'];
        }
      }
      if(isset($req->Where['zone_id_fk'])){
        if($req->Where['zone_id_fk']!=''){
          $req->zone_id_fk = $req->Where['zone_id_fk'];
        }
      }
      if(isset($req->Where['shelf_id_fk'])){
        if($req->Where['shelf_id_fk']!=''){
          $req->shelf_id_fk = $req->Where['shelf_id_fk'];
        }
      }
      if(isset($req->Where['shelf_floor'])){
        if($req->Where['shelf_floor']!=''){
          $req->shelf_floor = $req->Where['shelf_floor'];
        }
      }
      if(isset($req->Where['lot_number'])){
        if($req->Where['lot_number']!=''){
          $req->lot_number = $req->Where['lot_number'];
        }
      }
      if(isset($req->Where['product'])){
        if($req->Where['product']!=''){
          $req->product = $req->Where['product'];
        }
      }

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

        SELECT db_stocks.id,db_stocks.product_id_fk,sum(db_stocks.amt) as amt,
        '$w01' as w01,
        '$w02' as w02,
        '$w03' as w03,
        '$w04' as w04,
        '$w05' as w05,
        '$w06' as w06,
        '$w07' as w07,
        '$w08' as w08,
        '$w09' as w09
         FROM `db_stocks` LEFT JOIN products ON db_stocks.product_id_fk=products.id
         WHERE 1
         AND db_stocks.amt <> 0
          $w01
          $w02
          $w03
          $w04
          $w05
          $w06
          $w07
          $w08
          $w09

        GROUP BY db_stocks.product_id_fk
        ORDER BY products.product_code

        ");




      // $sTable = DB::select("

      // SELECT db_stocks.id,db_stocks.product_id_fk,sum(db_stocks.amt) as amt,
      // '$w01' as w01,
      // '$w02' as w02,
      // '$w03' as w03,
      // '$w04' as w04,
      // '$w05' as w05,
      // '$w06' as w06,
      // '$w07' as w07,
      // '$w08' as w08,
      // '$w09' as w09
      //  FROM `db_stocks` LEFT JOIN products ON db_stocks.product_id_fk=products.id
      //  WHERE 1
      //   $w01
      //   $w02
      //   $w03
      //   $w04
      //   $w05
      //   $w06
      //   $w07
      //   $w08
      //   $w09

      // GROUP BY db_stocks.product_id_fk
      // ORDER BY products.product_code

      // ");








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
          AND amt <> 0
          GROUP BY branch_id_fk,product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor ORDER BY lot_number ");
            $f = [] ;
            foreach ($d as $key => $v) {
               array_push($f,$v->lot_number);
            }
            $f = implode('<br><br>',$f);
            return $f;
            // AND amt <> 0
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
          AND amt <> 0
            GROUP BY branch_id_fk,product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor ORDER BY lot_number ");
            $f = [] ;
            foreach ($d as $key => $v) {
               array_push($f,$v->lot_expired_date);
            }
            $f = implode('<br><br>',$f);
            return $f.'';
            // AND amt <> 0
      })
       ->addColumn('amt_desc', function($row) {
        // $d = strtotime($row->lot_expired_date);
            $d = DB::select(" SELECT amt FROM `db_stocks` where 1 AND product_id_fk=".$row->product_id_fk."

          ".$row->w01."
          ".$row->w02."
          ".$row->w03."
          ".$row->w04."
          ".$row->w05."
          ".$row->w06."
          ".$row->w07."
          ".$row->w08."
          ".$row->w09."
          AND amt <> 0

          GROUP BY branch_id_fk,product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor ORDER BY lot_number ");
            $f = [] ;
            foreach ($d as $key => $v) {
               array_push($f,$v->amt);
            }
            $f = implode('<br><br>',$f);
            // dd($f);
            return $f;
            // AND amt <> 0
      })
      ->addColumn('amt', function($row) {
        // $d = strtotime($row->lot_expired_date);
            $d = DB::select(" SELECT amt FROM `db_stocks` where 1 AND product_id_fk=".$row->product_id_fk."

          ".$row->w01."
          ".$row->w02."
          ".$row->w03."
          ".$row->w04."
          ".$row->w05."
          ".$row->w06."
          ".$row->w07."
          ".$row->w08."
          ".$row->w09."
          AND amt <> 0
          GROUP BY branch_id_fk,product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor ORDER BY lot_number ");
            $f = 0 ;
            foreach ($d as $key => $v) {
               $f+=$v->amt;
            }
            return $f;
            // AND amt <> 0
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
          AND amt <> 0
           GROUP BY branch_id_fk,product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor ORDER BY lot_number ");
            $f = [] ;
            foreach ($d as $key => $v) {

                    $sBranchs = DB::select(" select * from branchs where id=".$v->branch_id_fk." ");
                    $warehouse = DB::select(" select * from warehouse where id=".($v->warehouse_id_fk?$v->warehouse_id_fk:0)." ");
                    $zone = DB::select(" select * from zone where id=".($v->zone_id_fk?$v->zone_id_fk:0)." ");
                    $shelf = DB::select(" select * from shelf where id=".($v->shelf_id_fk?$v->shelf_id_fk:0)." ");
                    $t = @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$v->shelf_floor;

               array_push($f,$t);
            }
            $f = implode('<br><br>',$f);
            return $f;
            // AND amt <> 0

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
          AND amt <> 0
          GROUP BY branch_id_fk,product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor ORDER BY lot_number ");
            $f = [] ;
            foreach ($d as $key => $v) {
               array_push($f,'<a class="btn btn-outline-success waves-effect waves-light" href="backend/stock_card_01?stock_id='.$v->id.'" style="padding: initial;padding-left: 2px;padding-right: 2px;color:black;" target=_blank > STOCK CARD </a>');
            }
            $f = implode('<br><br>',$f);

           return $f;
           // return '* อยู่ระหว่างปรับปรุง';
          //  AND amt <> 0

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


              $sTable = \App\Models\Backend\Check_stock::where('branch_id_fk',$req->branch_id_fk)->where('amt','>',0)->where('product_id_fk',$req->product_id_fk);

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

              $sTable = \App\Models\Backend\Check_stock::where('branch_id_fk',$req->branch_id_fk)->where('product_id_fk',$req->product_id_fk)->where('amt','!=',0);

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
