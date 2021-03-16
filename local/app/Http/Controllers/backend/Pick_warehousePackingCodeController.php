<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Pick_warehousePackingCodeController extends Controller
{

    public function index(Request $request)
    {

      // return view('backend.pick_warehouse_packing.index');
      
    }

    public function create()
    {

    }
    public function store(Request $request)
    {
      dd($request->all());

        if(isset($request->save_to_packing)){

            $arr = implode(',', $request->row_id);

            $rsDelivery = DB::select(" SELECT * FROM db_delivery WHERE id in ($arr)  ");
            // dd($rsDelivery);

            foreach ($rsDelivery as $key => $value) {
                DB::update(" UPDATE db_delivery SET status_pick_pack=1 WHERE packing_code = ".$value->packing_code." ");
            }

            $PackingCode = new \App\Models\Backend\Pick_warehousePackingCode;
            if( $PackingCode ){
              $PackingCode->status_pick_warehouse = 1;
              $PackingCode->created_at = date('Y-m-d H:i:s');
              $PackingCode->save();
            }

           foreach ($rsDelivery as $key => $value) {
              $Packing = new \App\Models\Backend\Pick_warehousePacking;
              $Packing->packing_code = $PackingCode->id;
              $Packing->delivery_id_fk = @$rsDelivery[0]->id;
              $Packing->created_at = date('Y-m-d H:i:s');
              $Packing->save();
           }              

        }

        return redirect()->to(url("backend/pick_warehouse"));


    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
    }


    public function destroy($id)
    {

      // $DP = DB::table('db_pick_warehouse_packing')->where('packing_code',$id)->get();
      // foreach ($DP as $key => $value) {
      //     DB::update(" UPDATE db_pick_warehouse SET status_pack='0' WHERE id = ".$value->pick_warehouse_id_fk."  ");
      // }

      DB::update(" DELETE FROM db_pick_warehouse_packing WHERE packing_code = $id  ");

      $sRow = \App\Models\Backend\Pick_warehousePackingCode::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      
      return response()->json(\App\Models\Alert::Msg('success'));
      // return redirect()->to(url("backend/pick_warehouse"));
    }

    public function Datatable(){      
      $sTable = \App\Models\Backend\Pick_warehousePackingCode::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('receipt', function($row) {
        if($row->id!==""){
            $DP = DB::table('db_pick_warehouse_packing')->where('packing_code',$row->id)->get();
            $array = array();
            if(@$DP){
              foreach ($DP as $key => $value) {
                $rs = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
                array_push($array, @$rs[0]->receipt);
              }
              $arr = implode(',', $array);
              return $arr;
            }
          }
      })
      ->addColumn('customer_name', function($row) {

        if($row->id!==""){

            $DP = DB::table('db_pick_warehouse_packing')->where('packing_code',$row->id)->get();
            $array = array();
            if(@$DP){
              foreach ($DP as $key => $value) {
                $rs = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
                $Customer = DB::select(" select * from customers where id=".@$rs[0]->customer_id." ");
                array_push($array, $Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name);
              }
              $arr = implode(',', $array);
              return $arr;
            }

          }
      })
      ->addColumn('packing_code_desc', function($row) {
        return "S".sprintf("%05d",$row->id);
      })
      ->make(true);
    }



}
