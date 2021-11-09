<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;
use App\Models\Backend\AddressSent;

class Pick_warehouse_fifo_noController extends Controller
{

    public function index(Request $request)
    {
      return View('backend.pick_warehouse_fifo_no.index');
    }


    public function create()
    {
    }

    public function store(Request $request)
    {
      // dd($request->all());
      if(isset($request->save_to_packing)){

        $arr = implode(',', $request->row_id);

        $receipt = '';
        $arr_receipt = [];
        // No packing code
        $packing_code = DB::select(" SELECT id,packing_code,receipt FROM db_delivery WHERE id in ($arr) and packing_code=0 ");
        if($packing_code){
            foreach ($packing_code as $key => $value) {
                array_push($arr_receipt,$value->receipt);
            }
            $receipt = implode(',',$arr_receipt);
        }

        // Packing code
        $packing_code = DB::select(" SELECT id,packing_code,receipt FROM db_delivery WHERE id in ($arr) and packing_code<>0 ");
        if($packing_code){

            $packing_code = DB::select(" SELECT id,packing_code,receipt FROM db_delivery WHERE packing_code in (".$packing_code[0]->packing_code.") ");
            // dd($packing_code);
            foreach ($packing_code as $key => $value) {
                array_push($arr_receipt,$value->receipt);
            }
            $receipt = implode(',',$arr_receipt);
        }

        // dd($receipt);

        DB::update(" UPDATE db_delivery SET status_pick_pack='1' WHERE id in ($arr)  ");

        $rsDelivery = DB::select(" SELECT * FROM db_delivery WHERE id in ($arr)  ");

          $DeliveryPackingCode = new \App\Models\Backend\Pick_packPackingCode;
          if( $DeliveryPackingCode ){
            $DeliveryPackingCode->created_at = date('Y-m-d H:i:s');
            $DeliveryPackingCode->action_user = (\Auth::user()->id);
            $DeliveryPackingCode->receipt = $receipt ;
            $DeliveryPackingCode->save();
          }

         foreach ($rsDelivery as $key => $value) {
            $DeliveryPacking = new \App\Models\Backend\Pick_packPacking;
            $DeliveryPacking->packing_code = $DeliveryPackingCode->id;
            $DeliveryPacking->delivery_id_fk = @$value->id;
            $DeliveryPacking->created_at = date('Y-m-d H:i:s');
            $DeliveryPacking->save();

            DB::update(" UPDATE db_delivery SET status_pick_pack='1' WHERE packing_code<>0 and packing_code in (".@$value->packing_code.")  ");

         }

        return redirect()->to(url("backend/pick_pack"));


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
        return redirect()->action('backend\Pick_warehouse_fifo_noController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
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
      $sTable = \App\Models\Backend\Pick_warehouse_fifo_no::search()->orderBy('id', 'asc');
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

          ->addColumn('date_in_stock', function($row) {
           
              if(!empty($row->pickup_firstdate)){
                return $row->pickup_firstdate;
              }

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
