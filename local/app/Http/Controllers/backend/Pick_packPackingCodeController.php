<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Pick_packPackingCodeController extends Controller
{

    public function index(Request $request)
    {

      // return view('backend.delivery_packing.index');
      
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

      // return $id;
      // dd($id);

      $DP = DB::table('db_pick_pack_packing')->where('packing_code',$id)->get();
      
      $arr = [];
      foreach ($DP as $key => $value) {
          DB::update(" UPDATE db_delivery SET status_pick_pack='0' WHERE id = ".$value->delivery_id_fk."  ");
          $delivery = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
          array_push($arr,$delivery[0]->packing_code);
      }
      $arr = implode(',', $arr);

      DB::update(" UPDATE db_delivery SET status_pick_pack='0' WHERE packing_code in (".$arr.")");

      DB::update(" DELETE FROM db_pick_pack_packing WHERE packing_code = $id  ");

      $sRow = \App\Models\Backend\Pick_packPackingCode::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      
      return response()->json(\App\Models\Alert::Msg('success'));
      // return redirect()->to(url("backend/delivery"));
    }

    public function Datatable(){      
      $sTable = \App\Models\Backend\Pick_packPackingCode::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('packing_code_02', function($row) {
        return "P2".sprintf("%05d",$row->id);
      })      
      ->addColumn('receipt', function($row) {
         // กรณีไม่เป็น packing
        if($row->packing_type==1){
            $DP = DB::table('db_pick_pack_packing')->where('packing_code',$row->id)->get();
            $array = array();
            if(@$DP){
              foreach ($DP as $key => $value) {
                $rs = DB::table('db_delivery')->where('status_pack',"<>","1")->where('id',$value->delivery_id_fk)->get();
                if(!empty(@$rs[0]->receipt)){
                  array_push($array, @$rs[0]->receipt);
                }
              }
              $array1 = implode(',', $array);
              return $array1;
             }
            }

      })
      ->addColumn('customer_name', function($row) {
          $DP = DB::table('db_pick_pack_packing')->where('packing_code',$row->id)->orderBy('id', 'asc')->get();
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
      })
      ->addColumn('action_user_name', function($row) {
        if(@$row->action_user!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->action_user." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
