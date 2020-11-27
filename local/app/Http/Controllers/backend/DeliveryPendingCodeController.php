<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class DeliveryPendingCodeController extends Controller
{

    public function index(Request $request)
    {

      // return view('backend.delivery_pending.index');
      
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

      $DP = DB::table('db_delivery_pending')->where('pending_code',$id)->get();
      foreach ($DP as $key => $value) {
          DB::update(" UPDATE db_delivery SET status_delivery='0' WHERE id = ".$value->delivery_id_fk."  ");
      }

      DB::update(" DELETE FROM db_delivery_pending WHERE pending_code = $id  ");

      $sRow = \App\Models\Backend\DeliveryPendingCode::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      
      return response()->json(\App\Models\Alert::Msg('success'));
      // return redirect()->to(url("backend/delivery"));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\DeliveryPendingCode::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('receipt', function($row) {
          $DP = DB::table('db_delivery_pending')->where('pending_code',$row->id)->get();
          $array = array();
          foreach ($DP as $key => $value) {
            $rs = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
            array_push($array, @$rs[0]->receipt);
          }
          $arr = implode(',', $array);
          return $arr;
      })
      ->addColumn('customer_name', function($row) {
          $DP = DB::table('db_delivery_pending')->where('pending_code',$row->id)->get();
          $array = array();
          foreach ($DP as $key => $value) {
            $rs = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
            $Customer = DB::select(" select * from customers where id=".@$rs[0]->customer_id." ");
            array_push($array, $Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name);
          }
          $arr = implode(',', $array);
          return $arr;
      })
      ->addColumn('pending_code_desc', function($row) {
        return sprintf("%05d",$row->id);
      })
      ->addColumn('addr_to_send', function($row) {
        if($row->addr_id){
            $rs = DB::table('customers_detail')->where('id',$row->addr_id)->get();
            $addr = $rs[0]->house_no?$rs[0]->house_no.", ":'';
            $addr .= $rs[0]->house_name;
            $addr .= $rs[0]->moo?", หมู่ ".$rs[0]->moo:'';
            $addr .= $rs[0]->soi?", ซอย".$rs[0]->soi:'';
            $addr .= $rs[0]->road?", ถนน".$rs[0]->road:'';
            $addr .= $rs[0]->district_sub?", ต.".$rs[0]->district_sub:'';
            $addr .= $rs[0]->district?", อ.".$rs[0]->district:'';
            $addr .= $rs[0]->province?", จ.".$rs[0]->province:'';
            $addr .= $rs[0]->zipcode?", ".$rs[0]->zipcode:'';
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
