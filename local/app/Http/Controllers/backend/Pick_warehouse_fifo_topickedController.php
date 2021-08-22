<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;
use App\Models\Backend\AddressSent;

class Pick_warehouse_fifo_topickedController extends Controller
{

    public function index(Request $request)
    {
      return View('backend.pick_warehouse_fifo_topicked.index');
    }


    public function create()
    {
    }

    public function store(Request $request)
    {
      // dd($request->all());
      if(isset($request->pick_warehouse_fifo_topicked)){

          DB::select('TRUNCATE db_pick_warehouse_fifo_topicked');
          DB::select('TRUNCATE db_pick_warehouse_fifo_no');

          DB::select(' 
              INSERT IGNORE INTO db_pick_warehouse_fifo_topicked 
              SELECT * FROM db_pick_warehouse_fifo WHERE db_pick_warehouse_fifo.amt>=db_pick_warehouse_fifo.amt_get;
          ');


          DB::select(' 
             INSERT IGNORE INTO db_pick_warehouse_fifo_no 
             SELECT * FROM db_pick_warehouse_fifo WHERE db_pick_warehouse_fifo.amt<db_pick_warehouse_fifo.amt_get;
          ');


        return redirect()->to(url("backend/pick_warehouse"));


      }

    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      // return $this->form($id);
    }

   public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {
          // if( $id ){
          //   $sRow = \App\Models\Backend\Pick_pack::find($id);
          // }else{
          //   $sRow = new \App\Models\Backend\Pick_pack;
          // }

          // $sRow->receipt    = request('receipt');
          // $sRow->customer_id    = request('customer_id');
          // $sRow->tel    = request('tel');
          // $sRow->province_id_fk    = request('province_id_fk');
          // $sRow->delivery_date    = request('delivery_date');
                    
          // $sRow->created_at = date('Y-m-d H:i:s');
          // $sRow->save();

          // \DB::commit();

          //  return redirect()->to(url("backend/delivery/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Pick_warehouse_fifo_topickedController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // $sRow = \App\Models\Backend\Pick_pack::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // return response()->json(\App\Models\Alert::Msg('success'));
    }

    
    public function Datatable(){
      $sTable = \App\Models\Backend\Pick_warehouse_fifo_topicked::search()->orderBy('id', 'asc');
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
          ->addColumn('status_desc', function($row) {
            if(!empty($row->status)){
               return $row->status;
            }else{
              return '0';
            }
          })          
          ->addColumn('updated_at', function($row) {
            return is_null($row->updated_at) ? '-' : $row->updated_at;
          })
          ->make(true);
        }



}
