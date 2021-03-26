<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Pick_warehouse_fifoController extends Controller
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

      $Check_stock = \App\Models\Backend\Pick_warehouse_fifo::get();

      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBranchs = \App\Models\Backend\Branchs::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

      return View('backend.pick_warehouse_fifo.index')->with(
        array(
           'Products'=>$Products,'Check_stock'=>$Check_stock,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id
        ) );

    }

    public function calFifo(Request $request)
    {
      // dd($request->all());
      // return $request;
      // dd();
      // $row_id = explode(',', $request->picking_id);

      DB::select('TRUNCATE db_frontstore_tmp');
      DB::select('TRUNCATE db_frontstore_products_list_tmp');
      DB::select('TRUNCATE db_pick_warehouse_fifo');

        if(!empty($request->picking_id)){

             

              DB::select(' 
                  insert ignore into db_frontstore_tmp select * from db_frontstore where invoice_code in(
                  SELECT
                  db_delivery.receipt
                  FROM
                  db_pick_pack_packing
                  Left Join db_delivery ON db_pick_pack_packing.delivery_id_fk = db_delivery.id
                  WHERE db_pick_pack_packing.packing_code in ('.$request->picking_id.') and db_delivery.packing_code=0
                  );
              ');


              DB::select(' 
                 insert ignore into db_frontstore_tmp select * from db_frontstore where invoice_code in
                  (
                  SELECT receipt FROM db_delivery WHERE packing_code in 
                  (
                  SELECT
                  db_delivery.packing_code
                  FROM
                  db_pick_pack_packing
                  Left Join db_delivery ON db_pick_pack_packing.delivery_id_fk = db_delivery.id
                  WHERE db_pick_pack_packing.packing_code in ('.$request->picking_id.') and  db_delivery.packing_code<>0
                  )
                  );
              ');


              DB::select(' 
                insert ignore into db_frontstore_products_list_tmp select * from db_frontstore_products_list where product_id_fk in 
                (
                SELECT
                db_frontstore_products_list.product_id_fk
                FROM
                db_frontstore_products_list
                INNER Join db_frontstore_tmp ON db_frontstore_products_list.frontstore_id_fk = db_frontstore_tmp.id
                GROUP BY db_frontstore_products_list.product_id_fk
                )
              ');

// รวมจำนวนสินค้าแต่ละรายการ
              // DB::select(' 
        
              // ');

        //  ได้รหัสสินค้ามาแล้ว 

              $product = DB::select('select product_id_fk from db_frontstore_products_list_tmp');
              $product_id = [];
              foreach ($product as $key => $value) {
                 array_push($product_id,$value->product_id_fk);
                 DB::select('insert ignore into db_pick_warehouse_fifo (product_id_fk) values ('.$value->product_id_fk.')');
              }

              // return $product_id;
              // หา FIFO 

              DB::select("

                    UPDATE
                    db_pick_warehouse_fifo
                    Inner Join 
                    (SELECT * FROM `db_stocks` GROUP BY db_stocks.branch_id_fk,db_stocks.product_id_fk,db_stocks.lot_number ORDER BY db_stocks.lot_expired_date ASC) as db_stocks
                     ON db_pick_warehouse_fifo.product_id_fk = db_stocks.product_id_fk
                    SET
                    db_pick_warehouse_fifo.branch_id_fk=
                    db_stocks.branch_id_fk,
                    db_pick_warehouse_fifo.lot_number=
                    db_stocks.lot_number,
                    db_pick_warehouse_fifo.lot_expired_date=
                    db_stocks.lot_expired_date,
                    db_pick_warehouse_fifo.amt=
                    db_stocks.amt,
                    db_pick_warehouse_fifo.warehouse_id_fk=
                    db_stocks.warehouse_id_fk,
                    db_pick_warehouse_fifo.zone_id_fk=
                    db_stocks.zone_id_fk,
                    db_pick_warehouse_fifo.shelf_id_fk=
                    db_stocks.shelf_id_fk,
                    db_pick_warehouse_fifo.shelf_floor=
                    db_stocks.shelf_floor

                ");


              DB::select("

                    UPDATE
                    db_pick_warehouse_fifo
                    Left Join db_frontstore_products_list_tmp ON db_pick_warehouse_fifo.product_id_fk = db_frontstore_products_list_tmp.product_id_fk
                    SET
                    db_pick_warehouse_fifo.amt_get=
                    db_frontstore_products_list_tmp.amt,
                    db_pick_warehouse_fifo.product_unit_id_fk=
                    db_frontstore_products_list_tmp.product_unit_id_fk

                ");


        }


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

    public function Datatable(){
      $sTable = \App\Models\Backend\Pick_warehouse_fifo::search()->orderBy('lot_expired_date', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {
        
          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".($row->product_id_fk)." AND lang_id=1");

          if(@$Products[0]->product_code!=@$Products[0]->product_name){
             return @$Products[0]->product_code." : ".@$Products[0]->product_name;
          }else{
             return @$Products[0]->product_name;
          }

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
      ->addColumn('product_unit', function($row) {
        $p = DB::select("  SELECT product_unit
              FROM
              dataset_product_unit
              WHERE id = ".$row->product_unit_id_fk." AND  lang_id=1  ");
          return @$p[0]->product_unit;
      }) 
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


}
