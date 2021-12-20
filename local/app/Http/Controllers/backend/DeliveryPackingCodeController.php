<?php

namespace App\Http\Controllers\backend;

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
      // $sTable = \App\Models\Backend\DeliveryPackingCode::search()->orderBy('id', 'asc');

       $sTable = DB::select(" 

          SELECT db_delivery_packing_code.*,db_orders.shipping_special,db_delivery.id as db_delivery_id from db_delivery_packing_code  
          LEFT JOIN db_delivery_packing on db_delivery_packing.packing_code_id_fk=db_delivery_packing_code.id
          LEFT JOIN db_delivery on db_delivery.id=db_delivery_packing.delivery_id_fk
          LEFT JOIN db_orders on db_orders.id=db_delivery.orders_id_fk
          WHERE db_delivery.status_pick_pack<>1 AND db_delivery.status_delivery<>1
          group by db_delivery_packing_code.id
          order by db_delivery_packing_code.updated_at desc

        ");

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
        $DP = DB::table('db_delivery_packing')->where('packing_code_id_fk',$row->id)->first();
        return $DP->packing_code;
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
              $arr = array_filter($array);
              $arr = implode('<br>', $arr);
              return $arr;
            }
          }
      })
      ->escapeColumns('receipt')
      ->addColumn('customer_name', function($row) {
          $DP = DB::table('db_delivery_packing')->where('packing_code_id_fk',$row->id)->get();
          $array = array();
          if(@$DP){
            foreach ($DP as $key => $value) {
              $rs = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
              if(!empty(@$rs[0]->customer_id)){
                $Customer = DB::select(" select * from customers where id=".@$rs[0]->customer_id." ");
                array_push($array, $Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name);
              }
            }
            $arr = implode(',', $array);
            return $arr;
          }
      })
      ->addColumn('shipping_special_detail', function($row) {
        $data = "";
        if($row->shipping_special == 1){
          $data = "<span style='color:red;'>ส่งแบบพิเศษ/พรีเมี่ยม</span>";
        }
        return $data;
      })     
      ->addColumn('addr_to_send', function($row) { 

           if($row->id!==""){
              $DP = DB::table('db_delivery_packing')->where('packing_code_id_fk',$row->id)->get();
              $array = array();
              if(@$DP){
                foreach ($DP as $key => $value) {
                  $rs = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
                  array_push($array, "'".@$rs[0]->receipt."'");
                }
                $arr = implode(',', $array);
              }
            }

            $addr = DB::select(" SELECT
                  db_delivery.set_addr_send_this,
                  db_delivery.recipient_name,
                  db_delivery.addr_send,
                  db_delivery.postcode,
                  db_delivery.mobile,
                  db_delivery.total_price,
                  db_delivery_packing_code.id AS db_delivery_packing_code_id,
                  db_delivery.receipt
                  FROM
                  db_delivery_packing_code
                  Inner Join db_delivery_packing ON db_delivery_packing.packing_code_id_fk = db_delivery_packing_code.id
                  Inner Join db_delivery ON db_delivery_packing.delivery_id_fk = db_delivery.id
                  WHERE 
                  db_delivery_packing_code.id = ".$row->id." and db_delivery.receipt in ($arr) AND set_addr_send_this=1 ");
            if($addr){
              return @$addr[0]->recipient_name."<br>".@$addr[0]->addr_send."<br>".@$addr[0]->postcode." ".@$addr[0]->mobile."<br>"."<span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [แก้ไขที่อยู่] </span> ";
            }else{
              return "* กรณีได้สิทธิ์ส่งฟรี กรุณาระบุที่อยู่อีกครั้ง <span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [Click here] </span> ";
            }

      })
      ->escapeColumns('addr_to_send')
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
