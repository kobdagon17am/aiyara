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

      $DP = DB::table('db_delivery_packing')->where('packing_code',$id)->get();
      foreach ($DP as $key => $value) {
          DB::update(" UPDATE db_delivery SET status_pack='0' WHERE id = ".$value->delivery_id_fk."  ");
      }

      DB::update(" DELETE FROM db_delivery_packing WHERE packing_code = $id  ");

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
      ->addColumn('receipt', function($row) {
        if($row->id!==""){
            $DP = DB::table('db_delivery_packing')->where('packing_code',$row->id)->orderBy('id', 'asc')->get();
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
        // return $row->id;
        // if($row->id!==""){

          $DP = DB::table('db_delivery_packing')->where('packing_code',$row->id)->orderBy('id', 'asc')->get();
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

        //   }
      })
      ->addColumn('packing_code_desc', function($row) {
        return "P".sprintf("%05d",$row->id);
      })
      ->addColumn('addr_to_send', function($row) { 
        if(@$row->id!=""){
            $rs1 = DB::select("select * from db_delivery_packing where packing_code=".$row->id." ");
            $arr1 = [];
            foreach ($rs1 as $key => $value) {
              array_push($arr1,$value->delivery_id_fk);
            }
            $id = implode(',', $arr1);
            $rs2 = DB::select("select * from db_delivery where id in ($id) ");
            $arr2 = [];
            foreach ($rs2 as $key => $value) {
              array_push($arr2,"'".$value->receipt."'");
            }
            $receipt = implode(',', $arr2);

            if($receipt){
                $rs = DB::select("select * from customers_addr_sent where receipt_no in($receipt) AND id_choose=1 ");

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
                return '0';
            }
            
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
