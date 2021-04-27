<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class DeliveryPackingCodeController extends Controller
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

      $DP = DB::table('db_delivery_packing')->where('packing_code_id_fk',$id)->get();
      foreach ($DP as $key => $value) {
          DB::update(" UPDATE db_delivery SET status_pack='0' WHERE id = ".$value->delivery_id_fk."  ");
      }

      DB::update(" DELETE FROM db_delivery_packing WHERE packing_code_id_fk = $id  ");

      $sRow = \App\Models\Backend\DeliveryPackingCode::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      
      return response()->json(\App\Models\Alert::Msg('success'));
      // return redirect()->to(url("backend/delivery"));
    }

    public function Datatable(){      
      $sTable = \App\Models\Backend\DeliveryPackingCode::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('status_pick_pack', function($row) {
         $DP = DB::table('db_delivery_packing')->where('packing_code_id_fk',$row->id)->get();
         if(@$DP){
              foreach ($DP as $key => $value) {
                $rs = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
                return $rs[0]->status_pick_pack;
              }
            }
      })  
      ->addColumn('packing_code_desc', function($row) {
        return "P1".sprintf("%05d",$row->id);
      })      
      ->addColumn('receipt', function($row) {
        if($row->id!==""){
            $DP = DB::table('db_delivery_packing')->where('packing_code_id_fk',$row->id)->get();
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
          $DP = DB::table('db_delivery_packing')->where('packing_code_id_fk',$row->id)->get();
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
      ->addColumn('addr_to_send', function($row) { 

          $rs = DB::select(" SELECT
                customers_addr_sent.recipient_name,
                customers_addr_sent.house_no,
                customers_addr_sent.house_name,
                customers_addr_sent.moo,
                customers_addr_sent.road,
                customers_addr_sent.soi,
                dataset_districts.name_th as tambon,
                dataset_amphures.name_th as amphur,
                dataset_provinces.name_th as province,
                customers_addr_sent.zipcode,
                customers_addr_sent.tel,
                customers_addr_sent.tel_home
                FROM
                customers_addr_sent
                Left Join dataset_provinces ON customers_addr_sent.province_id_fk = dataset_provinces.id
                Left Join dataset_amphures ON customers_addr_sent.amphures_id_fk = dataset_amphures.id
                Left Join dataset_districts ON customers_addr_sent.district_id_fk = dataset_districts.id
                WHERE customers_addr_sent.id=".$row->address_sent_id_fk."
            ");
                $addr = @$rs[0]->recipient_name?"ชื่อผู้รับ : ". @$rs[0]->recipient_name.", ":'';
                $addr .= @$rs[0]->house_no?@$rs[0]->house_no.", ":'';
                $addr .= @$rs[0]->house_name?@$rs[0]->house_name.", ":'';
                $addr .= @$rs[0]->moo?" หมู่ ".@$rs[0]->moo.", ":'';
                $addr .= @$rs[0]->soi?" ซอย".@$rs[0]->soi.", ":'';
                $addr .= @$rs[0]->road?" ถนน".@$rs[0]->road.", ":'';
                $addr .= @$rs[0]->tambon?" ต.".@$rs[0]->tambon.", ":'';
                $addr .= @$rs[0]->amphur?" อ.".@$rs[0]->amphur.", ":'';
                $addr .= @$rs[0]->province?" จ.".@$rs[0]->province.", ":'';
                $addr .= @$rs[0]->zipcode?" ".@$rs[0]->zipcode:'';
                return $addr;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
