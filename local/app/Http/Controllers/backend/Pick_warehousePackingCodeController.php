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
    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
    }


    public function destroy($id)
    {

      $DP = DB::table('db_pick_warehouse_packing')->where('packing_code',$id)->get();
      foreach ($DP as $key => $value) {
          DB::update(" UPDATE db_pick_warehouse SET status_pack='0' WHERE id = ".$value->pick_warehouse_id_fk."  ");
      }

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
                $rs = DB::table('db_pick_warehouse')->where('id',$value->pick_warehouse_id_fk)->get();
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
              $rs = DB::table('db_pick_warehouse')->where('id',$value->pick_warehouse_id_fk)->get();
              $Customer = DB::select(" select * from customers where id=".@$rs[0]->customer_id." ");
              array_push($array, $Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name);
            }
            $arr = implode(',', $array);
            return $arr;
          }

          }
      })
      ->addColumn('packing_code_desc', function($row) {
        return sprintf("%05d",$row->id);
      })
      ->addColumn('addr_to_send', function($row) { 
        if(@$row->addr_id!=""){
            // $rs = DB::table('customers_detail')->where('id',$row->addr_id)->get();
            $rs = DB::table('customers_addr_sent')->where('id',$row->addr_id)->where('id_choose',1)->get();
            $addr = @$rs[0]->house_no?@$rs[0]->house_no.", ":'';
            $addr .= @$rs[0]->house_name;
            $addr .= @$rs[0]->moo?", หมู่ ".@$rs[0]->moo:'';
            $addr .= @$rs[0]->soi?", ซอย".@$rs[0]->soi:'';
            $addr .= @$rs[0]->road?", ถนน".@$rs[0]->road:'';
            $addr .= @$rs[0]->district_sub?", ต.".@$rs[0]->district_sub:'';
            $addr .= @$rs[0]->district?", อ.".@$rs[0]->district:'';
            $addr .= @$rs[0]->province?", จ.".@$rs[0]->province:'';
            $addr .= @$rs[0]->zipcode?", ".@$rs[0]->zipcode:'';
            return $addr;
        }else{
            return '-ไม่ระบุ-';
        }

      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
