<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;
use App\Models\Backend\AddressSent;
use App\Models\Backend\Consignments_import;
use Session;
use App\Helpers\General;

class Pick_packController extends Controller
{

    public function index(Request $request)
    {
      General::gen_id_url();

      $sProvince = DB::select(" select *,name_th as province_name from dataset_provinces order by name_th ");
      $sAmphures = DB::select(" select *,name_th as amphur_name from dataset_amphures order by name_th ");
      $sTambons = DB::select(" select *,name_th as tambon_name from dataset_districts order by name_th ");
    
        return View('backend.pick_pack.index')->with(
          array(
             'sProvince'=>$sProvince,
             'sAmphures'=>$sAmphures,
             'sTambons'=>$sTambons,
          ) );

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

              $DeliveryPackingCode->business_location_id = $rsDelivery[0]->business_location_id;
              $DeliveryPackingCode->branch_id_fk =  $rsDelivery[0]->branch_id_fk;
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

     public function Datatable(Request $request){

       $sPermission = \Auth::user()->permission ;
       $User_branch_id = \Auth::user()->branch_id_fk;
      //  วุฒิเพิ่มมาเช็คว่าใช่ WH ไหม
      $role_group = DB::table('role_group')->select('wh_status')->where('id',@\Auth::user()->role_group_id_fk)->first();

        if(@\Auth::user()->permission==1 || @$role_group->wh_status==1){

            if(!empty( $req->business_location_id_fk) ){
                $business_location_id = " and db_delivery.business_location_id = ".$req->business_location_id_fk." " ;
            }else{
                $business_location_id = "";
            }

            if(!empty( $req->branch_id_fk) ){
                $branch_id_fk = " and db_delivery.branch_id_fk = ".$req->branch_id_fk." " ;
            }else{
                $branch_id_fk = " ";
            }

            $billing_employee = '';

        }else{

            $business_location_id = " and db_delivery.business_location_id = ".@\Auth::user()->business_location_id_fk." " ;
            $branch_id_fk = " and db_delivery.branch_id_fk = ".@\Auth::user()->branch_id_fk." " ;
            // $branch_id_fk = " and db_delivery.branch_id_fk in (1,6) ";
            $billing_employee = " and db_delivery.billing_employee = ".@\Auth::user()->id." " ;

            // วุฒิแก้ใหม่ ให้ WH เห็นทุกสาขา 
            // $branch_id_fk = "";

        }

      if(isset($request->startDate)){
       if($request->startTime == null){
        $startTime = '00:00:00';
        $endTime = '23:59:59';
       }else{
        $startTime = $request->startTime;
        $endTime = $request->endTime;
       }
        // $startDate = " AND DATE(delivery_date) >= '".$request->startDate."' " ;
        // $endDate = " AND DATE(delivery_date) <= '".$request->endDate."' " ;
        $startDate = " AND delivery_date >= '".$request->startDate." ".$startTime."' " ;
        $endDate = " AND delivery_date <= '".$request->endDate." ".$endTime."' " ;

// วุฒิเพิ่ม and status_to_wh=1
        $sTable = DB::select(" 
        select * from db_delivery WHERE status_pack=0 and status_pick_pack=0 and status_to_wh=1 AND orders_id_fk is not NULL $branch_id_fk 
        $startDate
        $endDate
        UNION
        select * from db_delivery WHERE status_pack=1 and status_pick_pack=0 and status_to_wh=1 AND orders_id_fk is not NULL $branch_id_fk 
        $startDate
        $endDate
        GROUP BY packing_code
     ");
      }else{
        $sTable = DB::select(" 
        select * from db_delivery WHERE status_pack=0 and status_pick_pack=0 and status_to_wh=1 AND orders_id_fk is not NULL $branch_id_fk
        UNION
        select * from db_delivery WHERE status_pack=1 and status_pick_pack=0 and status_to_wh=1 AND orders_id_fk is not NULL $branch_id_fk GROUP BY packing_code
     ");
      }
      
      $sQuery = \DataTables::of($sTable);
      return $sQuery     
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
          // if($row->distribution_channel_id_fk == 3){
          //   return 'V3';
          // }else{
            return 'V3';
          // }
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
      
      ->addColumn('receipt', function($row) {
        if(!empty($row->packing_code)){
            $d = DB::select(" select * from db_delivery WHERE packing_code=".$row->packing_code." ");
            // return "P1".sprintf("%05d",@$row->packing_code);
                $array1 = array();
                $rs = DB::table('db_delivery')->where('packing_code',@$row->packing_code)->get();
                foreach ($rs as $key => $value) {
                    if(!empty(@$value->receipt)){
                        array_push($array1, @$value->receipt);
                    }
                }
                $arr = implode('<br>', $array1);

                return "<b>(".@$row->packing_code_desc.")</b><br>".$arr;

        }else{
          // return "<span class='order_list' data-id=".@$row->receipt." style='cursor:pointer;color:blue;'> ".@$row->receipt." </span> ";
          $data = DB::table('db_orders')->where('code_order',$row->receipt)->first();
          if($data){
            return "<a href='".url('backend/frontstore/print_receipt_022/'.$data->id)."' target='blank' class='order_list'>".@$row->receipt."</a>";
          }else{
            return $row->receipt;
          }
           
        }

      })
      ->escapeColumns('receipt')
      
      ->addColumn('packing_code', function($row) {

        // $d = DB::select(" select * from db_delivery WHERE packing_code=".$row->packing_code." ");

        // return @$row->packing_code;

        // $array = '';

        // if(@$row->packing_code!=''){
        //      $array = "P1".sprintf("%05d",@$row->packing_code);
        // }

        // $array1 = array();
        // $rs = DB::table('db_delivery')->where('packing_code',$row->packing_code)->get();
        // foreach ($rs as $key => $value) {
        //     if(!empty(@$value->receipt)){
        //         array_push($array1, @$value->receipt);
        //     }
        // }
          
        //   $array2 = implode(',', $array1);
        //   return "(".$array."),".$array2;

      })
      ->addColumn('packing_id', function($row) {
        if(@$row->packing_code!=''){
             return @$row->packing_code;
        }
      })
   ->addColumn('addr_to_send', function($row) { 

        if(@$row->set_addr_send_this==1){
               return @$row->recipient_name."<br>".@$row->addr_send."<br>".@$row->postcode." ".@$row->mobile."<br>";
              //  ."<span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [เปลี่ยนที่อยู่] </span> ";
        }else{

            $d = DB::select(" 
                SELECT
                db_delivery.orders_id_fk,
                db_orders.code_order,
                db_orders.delivery_location,
                dataset_delivery_location.txt_desc
                FROM
                db_delivery
                Left Join db_orders ON db_delivery.orders_id_fk = db_orders.id
                Left Join dataset_delivery_location ON db_orders.delivery_location = dataset_delivery_location.de_lo_code
                WHERE
                db_delivery.orders_id_fk=".$row->orders_id_fk." ");

           

                   $addr2 = DB::select(" 
                          SELECT
                          db_delivery.set_addr_send_this,
                          db_delivery.recipient_name,
                          db_delivery.addr_send,
                          db_delivery.postcode,
                          db_delivery.mobile,
                          db_delivery.total_price,
                          db_delivery.receipt,
                          db_orders.delivery_location
                          FROM
                          db_delivery
                          left Join db_orders ON db_delivery.orders_id_fk = db_orders.id
                          WHERE 
                          db_delivery.id =".$row->id." ");
                    
          
            $array = array(0);
             if($row->id!==""){
                $DP = DB::table('db_delivery_packing')->where('packing_code_id_fk',$row->packing_code)->get();
                
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
                    db_delivery_packing_code.id = ".$row->packing_code." and db_delivery.receipt in ($arr) AND set_addr_send_this=1 ");
              if($addr){
                    // return @$addr[0]->recipient_name."<br>".@$addr[0]->addr_send."<br>".@$addr[0]->postcode." ".@$addr[0]->mobile."<br>"."<span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [แก้ไขที่อยู่] </span> ";
                    return @$addr[0]->recipient_name."<br>".@$addr[0]->addr_send."<br>".@$addr[0]->postcode." ".@$addr[0]->mobile."<br>"."";
              }else{

                    if(@$addr2[0]->delivery_location==1 || @$addr2[0]->delivery_location==2){
                        // return "* ".@$d[0]->txt_desc."<br>"."<span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [แก้ไขที่อยู่] </span> ";
                        // return '<font color=red>* ไม่พบข้อมูลที่อยู่ </font> <br>(ระบุในบิล : '.@$d[0]->txt_desc.")<br>"."<span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [แก้ไขที่อยู่] </span> ";
                        return '<font color=red>* ไม่พบข้อมูลที่อยู่ </font> <br>(ระบุในบิล : '.@$d[0]->txt_desc.")<br>"." ";
                      }else{
                        if(@$addr2[0]->delivery_location==4){
                          return "<b>* ".@$d[0]->txt_desc."</b>";
                        }else{

                            // เช็คกรณี ส่งฟรี
                              $shipping_cost = DB::select(" SELECT purchase_amt FROM `dataset_shipping_cost` WHERE shipping_type_id=1 ; ");
                              $shipping_cost= $shipping_cost[0]->purchase_amt;

                              $d1 = DB::select(" SELECT * from db_delivery where id=$row->id ");

                              if($d1[0]->packing_code>0){

                              $total_p = DB::select(" 
                                  SELECT sum(db_delivery.total_price) as total_price
                                  FROM
                                  db_delivery
                                  WHERE 
                                  shipping_price=0 and packing_code=".$d1[0]->packing_code." GROUP BY packing_code ");

                               if(@$total_p[0]->total_price>0){

                                 if(@$total_p[0]->total_price>=$shipping_cost){
                                    return "* กรณีได้สิทธิ์ส่งฟรี กรุณาระบุที่อยู่อีกครั้ง <span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [Click here] </span> ";
                                  }else{
                                    return "<b>* จัดส่งพร้อมบิลอื่น</b>";
                                  }

                                }

                               }else{

                                  // return "<b>* ".@$d[0]->txt_desc."</b>";
                                //  return @$addr2[0]->recipient_name."<br>".@$addr2[0]->addr_send."<br>".@$addr2[0]->postcode." ".@$addr2[0]->mobile."<br>"."<span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [แก้ไขที่อยู่] </span> ";
                                return @$addr2[0]->recipient_name."<br>".@$addr2[0]->addr_send."<br>".@$addr2[0]->postcode." ".@$addr2[0]->mobile."<br>"." ";

                               }

                          // return "* กรณีได้สิทธิ์ส่งฟรี กรุณาระบุที่อยู่อีกครั้ง <span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [Click here] </span> ";
                        }
                        
                      }

                  }
              }


      })
      ->escapeColumns('addr_to_send')

       ->addColumn('delivery_location', function($row) { 

             $addr2 = DB::select(" 
                    SELECT
                    db_delivery.set_addr_send_this,
                    db_delivery.recipient_name,
                    db_delivery.addr_send,
                    db_delivery.postcode,
                    db_delivery.mobile,
                    db_delivery.total_price,
                    db_delivery.receipt,
                    db_orders.delivery_location
                    FROM
                    db_delivery
                    left Join db_orders ON db_delivery.orders_id_fk = db_orders.id
                    WHERE 
                    db_delivery.id =".$row->id." ");
              
              if(@$addr2){
                  return @$addr2[0]->delivery_location;
              }


      })
      ->escapeColumns('delivery_location')

      ->addColumn('check_case_sent_free', function($row) { 
        // เช็คกรณี ส่งฟรี
            $shipping_cost = DB::select(" SELECT purchase_amt FROM `dataset_shipping_cost` WHERE shipping_type_id=1 ; ");
            $shipping_cost= $shipping_cost[0]->purchase_amt;

            $d1 = DB::select(" SELECT * from db_delivery where id=$row->id ");

            if($d1[0]->packing_code>0){

            $total_p = DB::select(" 
                SELECT sum(db_delivery.total_price) as total_price
                FROM
                db_delivery
                WHERE 
                shipping_price=0 and packing_code=".$d1[0]->packing_code." GROUP BY packing_code ");

             if(@$total_p[0]->total_price>0){

               if(@$total_p[0]->total_price>=$shipping_cost){
                  return "sent_free";
                }else{
                  return "";
                }

              }else{
                return "";
              }
          }
      })
      ->escapeColumns('check_case_sent_free')
      ->addColumn('delivery_date', function ($row) {
          return [
            'display' => date('Y-m-d H:i:s', strtotime($row->delivery_date)),
            'timestamp' => \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->delivery_date)->timestamp,
          ];
      })
      ->make(true);
    }





}
