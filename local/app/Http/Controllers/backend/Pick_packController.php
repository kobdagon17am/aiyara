<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;
use App\Models\Backend\AddressSent;
use App\Models\Backend\Consignments_import;

class Pick_packController extends Controller
{

    public function index(Request $request)
    {
      return View('backend.pick_pack.index');
    }


    public function create()
    {
    }

    public function store(Request $request)
    {
      // dd($request->all());
      if(isset($request->save_to_packing)){

        $arr = implode(',', $request->row_id);
        // dd($arr);

        $receipt = '';
        $packing_code = '';
        $arr_receipt = [];
        $arr_orders_id_fk = [];
        $arr_packing_code = [];
        $arr_delivery_id = [];
        // No packing code
        $no_packing_code = DB::select(" SELECT id,packing_code,orders_id_fk,receipt FROM db_delivery WHERE id in ($arr) and packing_code in (0,5) ");
        // dd($no_packing_code);
        if($no_packing_code){
            foreach ($no_packing_code as $key => $value) {
                array_push($arr_receipt,$value->receipt);
                array_push($arr_orders_id_fk,$value->orders_id_fk);
            }
            $receipt = implode(',',$arr_receipt);
            $orders_id_fk = implode(',',$arr_orders_id_fk);
        }
        // dd($receipt);


        // Packing code
        $packing = DB::select(" SELECT packing_code FROM db_delivery WHERE id in ($arr) and packing_code not in (0,5) ");
        if($packing){
            foreach ($packing as $key => $value) {
                array_push($arr_packing_code,$value->packing_code);
            }
            $packing_code = implode(',',$arr_packing_code);
        }

        // dd($packing_code);

        if($packing_code){

            $packing_code = DB::select(" SELECT id,packing_code,orders_id_fk,receipt FROM db_delivery WHERE packing_code in ($packing_code) ");
            // dd($packing_code);
            foreach ($packing_code as $key => $value) {
                array_push($arr_orders_id_fk,$value->orders_id_fk);
                array_push($arr_receipt,$value->receipt);
            }
            $orders_id_fk = array_filter($arr_orders_id_fk);
            // dd($orders_id_fk);
            $orders_id_fk = implode(',',$orders_id_fk);
            $receipt = implode(',',$arr_receipt);
        }

        // dd($orders_id_fk);
        // dd($receipt);

        DB::update(" UPDATE db_delivery SET status_pick_pack='1' WHERE id in ($arr)  ");

          $rsDelivery = DB::select(" SELECT * FROM db_delivery WHERE id in ($arr)  ");

          $DeliveryPackingCode = new \App\Models\Backend\Pick_packPackingCode;

          if( $DeliveryPackingCode ){

              $DeliveryPackingCode->created_at = date('Y-m-d H:i:s');
              $DeliveryPackingCode->action_user = (\Auth::user()->id);
              $DeliveryPackingCode->orders_id_fk = $orders_id_fk ;
              $DeliveryPackingCode->receipt = $receipt ;
              $DeliveryPackingCode->status_picked = 1 ;
              $DeliveryPackingCode->save();

               DB::select(" UPDATE customers_addr_sent SET packing_code=".$DeliveryPackingCode->id.",receipt_no='".$rsDelivery[0]->receipt."' WHERE (invoice_code='".$rsDelivery[0]->receipt."'); ");


          }

         foreach ($rsDelivery as $key => $value) {
            $DeliveryPacking = new \App\Models\Backend\Pick_packPacking;
            $DeliveryPacking->packing_code_id_fk = $DeliveryPackingCode->id;
            $DeliveryPacking->packing_code = "P2".sprintf("%05d",$DeliveryPackingCode->id) ;
            $DeliveryPacking->delivery_id_fk = @$value->id;
            $DeliveryPacking->created_at = date('Y-m-d H:i:s');
            $DeliveryPacking->save();

            DB::update(" UPDATE db_delivery SET status_pick_pack='1' WHERE packing_code<>0 and packing_code in (".@$value->packing_code.")  ");
            DB::select(" UPDATE customers_addr_sent SET packing_code=".$DeliveryPackingCode->id." WHERE (receipt_no='".$value->receipt."'); ");
            DB::select(" UPDATE db_orders SET status_delivery=1 WHERE (invoice_code='".$value->receipt."'); ");

         }

            // // เก็บข้อมูลที่อยู่ในการจัดส่งลูกค้า  db_consignments
          $Pick_packPacking = \App\Models\Backend\Pick_packPackingCode::get();
          $recipient_code = "P2".sprintf("%05d",$Pick_packPacking[0]->id);

          $r1 =  DB::select(" SELECT id FROM `db_pick_pack_packing_code` WHERE LOCATE(',',orders_id_fk)>0 ");
          // return $r1;
          if($r1){

           
                // ที่อยู่จัดส่ง
                  $addr_sent =  DB::select(" 
                      SELECT customer_id,from_table,recipient_name FROM `customers_addr_sent` WHERE packing_code=".$r1[0]->id."
                  ");

                  @$recipient_name = @$addr_sent[0]->recipient_name;

                  if($addr_sent){
                      $customer_id = @$addr_sent[0]->customer_id?@$addr_sent[0]->customer_id:0;
                      $from_table = @$addr_sent[0]->from_table;
                      $addr =  DB::select(" 
                          SELECT * FROM $from_table WHERE customer_id=$customer_id
                      ");
                  }else{
                    $customer_id = 0;
                    @$addr = [0];
                  }

                if(sizeof(@$addr)>0){

                  // @$recipient_name = @$addr[0]->prefix_name.@$addr[0]->first_name." ".@$addr[0]->last_name;

                  @$address = "";
                  // @$address .= "". @$addr[0]->prefix_name.@$addr[0]->first_name." ".@$addr[0]->last_name;
                  @$address .= "". @$addr[0]->house_no. " ". @$addr[0]->house_name. " ";
                  @$address .= " ต. ". @$addr[0]->tamname;
                  @$address .= " อ. ". @$addr[0]->ampname;
                  @$address .= " จ. ". @$addr[0]->provname;
                  @$address .= " รหัส ปณ. ". @$addr[0]->zipcode ;

                }else{
                  @$address = '-ไม่พบข้อมูล-';
                  @$recipient_name = '';
                }


              // $recipient_code = "P2".sprintf("%05d",$r1[0]->id);
              // DB::select(" INSERT IGNORE INTO `db_consignments` (`recipient_code`,pick_pack_packing_code_id_fk,recipient_name,address) VALUES ('$recipient_code',".$r1[0]->id.",'".@$recipient_name."','".@$address."'); ");


          }


          $r2 =  DB::select(" SELECT id,receipt FROM `db_pick_pack_packing_code` WHERE LOCATE(',',orders_id_fk)=0 ");
          
            if($r2){

                // ที่อยู่จัดส่ง
                  $addr_sent =  DB::select(" 
                      SELECT customer_id,from_table,recipient_name FROM `customers_addr_sent` WHERE packing_code=".$r2[0]->id."
                  ");
                  @$recipient_name  = @$addr_sent[0]->recipient_name;

                  if($addr_sent){
                      $customer_id = @$addr_sent[0]->customer_id?@$addr_sent[0]->customer_id:0;
                      $from_table = @$addr_sent[0]->from_table;
                      $addr =  DB::select(" 
                          SELECT * FROM $from_table WHERE customer_id=$customer_id
                      ");
                  }else{
                    $customer_id = 0;
                    @$addr = [0];
                  }

                if(sizeof(@$addr)>0){

                  // @$recipient_name = @$addr[0]->prefix_name.@$addr[0]->first_name." ".@$addr[0]->last_name;

                  @$address = "";
                  // @$address .= "". @$addr[0]->prefix_name.@$addr[0]->first_name." ".@$addr[0]->last_name;
                  @$address .= "". @$addr[0]->house_no. " ". @$addr[0]->house_name. " ";
                  @$address .= " ต. ". @$addr[0]->tamname;
                  @$address .= " อ. ". @$addr[0]->ampname;
                  @$address .= " จ. ". @$addr[0]->provname;
                  @$address .= " รหัส ปณ. ". @$addr[0]->zipcode ;

                }else{
                  @$address = '-ไม่พบข้อมูล-';
                  @$recipient_name = '';
                }

             // DB::select(" INSERT IGNORE INTO `db_consignments` (`recipient_code`,pick_pack_packing_code_id_fk,recipient_name,address) VALUES ('".$r2[0]->receipt."',".$r2[0]->id.",'".@$recipient_name."','".@$address."'); ");

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
        return redirect()->action('backend\Pick_packController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
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
      $sTable = DB::select(" 
        select * from db_delivery WHERE status_pack=0 and status_pick_pack=0 AND orders_id_fk is not NULL
        UNION
        select * from db_delivery WHERE status_pack=1 and status_pick_pack=0  AND orders_id_fk is not NULL GROUP BY packing_code
        ORDER BY updated_at DESC
     ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // ->addColumn('delivery_date', function($row) {
      //     $d = strtotime($row->delivery_date);
      //     return date("d/m/", $d).(date("Y", $d)+543);
      // })      
      ->addColumn('customer_name', function($row) {
        if(@$row->customer_id!=''){
          $Customer = DB::select(" select * from customers where id=".@$row->customer_id." ");
          return @$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
        }else{
          return '';
        }
      })
      ->addColumn('billing_employee', function($row) {
        if(@$row->billing_employee!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->billing_employee." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })
      ->addColumn('business_location', function($row) {
        if(@$row->business_location_id!=''){
             $P = DB::select(" select * from dataset_business_location where id=".@$row->business_location_id." ");
             return @$P[0]->txt_desc;
        }else{
             return '-';
        }
      })
      ->addColumn('packing_code', function($row) {

        $array = '';

        if(@$row->packing_code!=''){
             $array = "P1".sprintf("%05d",@$row->packing_code);
        }

        $array1 = array();
        $rs = DB::table('db_delivery')->where('packing_code',$row->packing_code)->get();
        foreach ($rs as $key => $value) {
            if(!empty(@$value->receipt)){
                array_push($array1, @$value->receipt);
            }
        }
          
          $array2 = implode(',', $array1);
          return "(".$array."),".$array2;

      })
      ->addColumn('packing_id', function($row) {
        if(@$row->packing_code!=''){
             return @$row->packing_code;
        }
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }





}
