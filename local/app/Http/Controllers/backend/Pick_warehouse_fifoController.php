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
      // return $request->picking_id;
      // dd();
      DB::select('TRUNCATE db_frontstore_tmp');
      DB::select('TRUNCATE db_frontstore_products_list_tmp');
      DB::select('TRUNCATE db_pick_warehouse_fifo');

      $row_id = explode(',', $request->picking_id);

      $d1 = DB::table('db_pick_pack_packing')->whereIn('packing_code',$row_id)->get();
      // return $d1;
      // dd();
      $arr = [];
      foreach ($d1 as $key => $value) {
          $delivery = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
          array_push($arr,$delivery[0]->receipt);
      }
      // return $arr;
      // dd();

      $frontstore = DB::table('db_frontstore')->whereIn('invoice_code',$arr)->get();
      foreach ($frontstore as $key => $value) {
          DB::select('insert ignore into db_frontstore_tmp select * from db_frontstore where invoice_code="'.$value->invoice_code.'" ');
      }
      foreach ($frontstore as $key => $value) {
          DB::select('insert ignore into db_frontstore_products_list_tmp select * from db_frontstore_products_list where frontstore_id_fk='.$value->id.' ');
      }

      $product = DB::select('select product_id_fk from db_frontstore_products_list_tmp');
      $product_id = [];
      foreach ($product as $key => $value) {
         array_push($product_id,$value->product_id_fk);
         DB::select('insert ignore into db_pick_warehouse_fifo (product_id_fk) values ('.$value->product_id_fk.') 
            ');
      }
      return $product_id;
      // dd();

      // เอาลงตาราง db_pick_warehouse_fifo นี้ก่อน เพราะเป็นรายการสินค้าทั้งหมดจากใบเสร็จ ค่อยเช็คหาจำนวนในคลัง ถ้ามีก็ใส่เลข ถ้าไม่มี ก็ติดลบ 

 
      // เอา product_id ไป scan หาในคลังต่างๆ ก็คือ db_stocks  => แล้วยิงลงตาราง db_pick_warehouse_fifo
      $fifo_01 =  DB::table('db_stocks')->whereIn('product_id_fk',$product_id)->get();
      foreach ($fifo_01 as $key => $value) {
          DB::select('insert ignore into db_pick_warehouse_fifo select * from db_stocks where product_id_fk='.$value->product_id_fk.' 
            order by lot_expired_date desc 
            ');
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
      $sTable = \App\Models\Backend\Pick_warehouse_fifo::search()->orderBy('lot_expired_date', 'desc');
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
        return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name;
      })      
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


}
