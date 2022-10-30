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
                $branch_id_fk = "";
            }

            $billing_employee = '';

        }else{

            $business_location_id = " and db_delivery.business_location_id = ".@\Auth::user()->business_location_id_fk." " ;
            $branch_id_fk = " and db_delivery.branch_id_fk = ".@\Auth::user()->branch_id_fk." " ;
            $billing_employee = " and db_delivery.billing_employee = ".@\Auth::user()->id." " ;

        }

        // วุฒิเอา  $branch_id_fk ออก
        // order by db_delivery_packing_code.updated_at desc
       $sTable = DB::select("

          SELECT db_delivery_packing_code.*,db_orders.action_user as action_user_data,db_orders.distribution_channel_id_fk, db_orders.shipping_special,db_delivery.id as db_delivery_id,db_delivery.status_to_wh,db_delivery.status_to_wh_by , MAX(db_orders.shipping_special) AS 'shipping_special' from db_delivery_packing_code
          LEFT JOIN db_delivery_packing on db_delivery_packing.packing_code_id_fk=db_delivery_packing_code.id
          LEFT JOIN db_delivery on db_delivery.id=db_delivery_packing.delivery_id_fk
          LEFT JOIN db_orders on db_orders.id=db_delivery.orders_id_fk
          WHERE db_delivery.status_pick_pack<>1 AND db_delivery.status_delivery<>1
          $business_location_id

          group by db_delivery_packing_code.id
          order by db_delivery_packing_code.updated_at desc
        ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // แก้ไขที่อยู่
      ->addColumn('status_pick_pack', function($row) {
         $DP = DB::table('db_delivery_packing')->where('packing_code_id_fk',$row->id)->get();
         if(@$DP){
              foreach ($DP as $key => $value) {
                $rs = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
                return $rs[0]->status_pick_pack;
              }
            }
      })
      ->addColumn('action_user_data', function($row) {
        $user = DB::table('ck_users_admin')->where('id',$row->action_user_data)->first();
        if($user){
          $user_name = $user->name;
        }else{
          if($row->distribution_channel_id_fk == 3){
            $user_name ='V3';
          }else{
            $user_name = '';
          }
        }
        return $user_name;
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
                // array_push($array, @$rs[0]->receipt);
                $p = "<a href='".url('backend/frontstore/print_receipt_022/'.@$rs[0]->orders_id_fk)."' target='blank'>".@$rs[0]->receipt."</a>";
                array_push($array, $p);
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
              // dd($DP);
              $array = array();
              if(@$DP){
                foreach ($DP as $key => $value) {
                  $rs = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
                  array_push($array, "'".@$rs[0]->receipt."'");
                }
                $arr = implode(',', $array);
              }
            }

            // foreach($rs as $rr){
              // \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddress($rr->orders_id_fk);
              // \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddressDefault($rr->orders_id_fk);
            // }


            $addr = DB::select(" SELECT
                  db_delivery.set_addr_send_this,
                  db_delivery.recipient_name,
                  db_delivery.addr_send,
                  db_delivery.postcode,
                  db_delivery.mobile,
                  db_delivery.tel_home,
                  db_delivery.total_price,
                  db_delivery_packing_code.id AS db_delivery_packing_code_id,
                  db_delivery.receipt
                  FROM
                  db_delivery_packing_code
                  Inner Join db_delivery_packing ON db_delivery_packing.packing_code_id_fk = db_delivery_packing_code.id
                  Inner Join db_delivery ON db_delivery_packing.delivery_id_fk = db_delivery.id
                  WHERE
                  db_delivery_packing_code.id = ".$row->id." and db_delivery.receipt in ($arr)  ");
                  // AND set_addr_send_this=1

                // $de = "<br>(ที่อยู่จัดส่งกำหนดเอง)";
                $de = "";

            if($addr){
              if($row->status_to_wh==0){
                  return @$addr[0]->recipient_name."<br>".@$addr[0]->addr_send." ".@$addr[0]->postcode."<br>".@$addr[0]->mobile.", ".@$addr[0]->tel_home."<br>"."<span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [แก้ไขที่อยู่] [".@$addr[0]->receipt."] </span> ".$de;
              }else{
                return @$addr[0]->recipient_name."<br>".@$addr[0]->addr_send." ".@$addr[0]->postcode."<br>".@$addr[0]->mobile.", ".@$addr[0]->tel_home."<br>".$de;
                // ."<span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [แก้ไขที่อยู่] </span> ";
              }


            }else{
              return "* กรณีได้สิทธิ์ส่งฟรี กรุณาระบุที่อยู่อีกครั้ง <span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [Click here] </span> ";
            }

      })
      ->escapeColumns('addr_to_send')

      ->addColumn('approve', function($row) {
        if($row->status_to_wh==1){
          $user = DB::table('ck_users_admin')->where('id',$row->status_to_wh_by)->first();
          if($user){
            $user_name = $user->name;
          }else{
            $user_name = '';
          }
          $p = '
          <b class="" style="color:green;">ยืนยันแล้ว</b>
          <br> โดย : '.$user_name.'
          <br> '.$row->updated_at.'
          ';
        }else{
          $p = '
          <a onclick="return confirm(\'ยืนยันการทำรายการ\')" href="'.url('backend/delivery_approve_to_wh/'.$row->db_delivery_id).'" class="btn btn-sm btn-success">ยืนยัน</a>
        ';
        }

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
              db_delivery.tel_home,
              db_delivery.total_price,
              db_delivery_packing_code.id AS db_delivery_packing_code_id,
              db_delivery.receipt
              FROM
              db_delivery_packing_code
              Inner Join db_delivery_packing ON db_delivery_packing.packing_code_id_fk = db_delivery_packing_code.id
              Inner Join db_delivery ON db_delivery_packing.delivery_id_fk = db_delivery.id
              WHERE
              db_delivery_packing_code.id = ".$row->id." and db_delivery.receipt in ($arr)  ");
              // AND set_addr_send_this=1

        if(@$addr[0]->mobile == '' && @$addr[0]->tel_home == ''){
          $p = '<label style="color:red;">กรุณาระบุเบอร์โทรก่อนทำรายการ!</label>';
        }

        return $p;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }

    public function Datatable2(Request $request){

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
       select db_delivery.*, db_orders.shipping_special from db_delivery LEFT JOIN db_orders on db_orders.id=db_delivery.orders_id_fk WHERE db_delivery.status_pack=0 and db_delivery.status_pick_pack=0 and db_delivery.status_pack = 0 and db_delivery.status_to_wh=1 AND db_delivery.orders_id_fk is not NULL $branch_id_fk
       $startDate
       $endDate
       UNION
       select db_delivery.*, db_orders.shipping_special from db_delivery LEFT JOIN db_orders on db_orders.id=db_delivery.orders_id_fk WHERE db_delivery.status_pack=1 and db_delivery.status_pick_pack=0 and db_delivery.status_pack = 0 and db_delivery.status_to_wh=1 AND db_delivery.orders_id_fk is not NULL $branch_id_fk
       $startDate
       $endDate
       GROUP BY packing_code
    ");
     }else{
       $sTable = DB::select("
       select db_delivery.*, db_orders.shipping_special from db_delivery LEFT JOIN db_orders on db_orders.id=db_delivery.orders_id_fk WHERE db_delivery.status_pack=0 and db_delivery.status_pick_pack=0 and db_delivery.status_pack = 0 and db_delivery.status_to_wh=1 AND db_delivery.orders_id_fk is not NULL $branch_id_fk
       UNION
       select db_delivery.*, db_orders.shipping_special from db_delivery LEFT JOIN db_orders on db_orders.id=db_delivery.orders_id_fk WHERE db_delivery.status_pack=1 and db_delivery.status_pick_pack=0 and db_delivery.status_pack = 0 and db_delivery.status_to_wh=1 AND db_delivery.orders_id_fk is not NULL $branch_id_fk GROUP BY db_delivery.packing_code
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
         return '';
       }
     })
     ->addColumn('shipping_special_detail', function($row) {
      $data = "";
      if($row->shipping_special == 1){
        $data = "<span style='color:red;'>ส่งแบบพิเศษ/พรีเมี่ยม</span>";
      }
      return $data;
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

     ->addColumn('packing_code', function($row) { })
     ->addColumn('packing_id', function($row) {
       if(@$row->packing_code!=''){
            return @$row->packing_code;
       }
     })
  ->addColumn('addr_to_send', function($row) {

       if(@$row->set_addr_send_this==1){
              return @$row->recipient_name."<br>".@$row->addr_send."<br>".@$row->postcode." ".@$row->mobile."<br>"."<span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [เปลี่ยนที่อยู่] </span> ";
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
                   db_delivery_packing_code.id = ".$row->packing_code." and db_delivery.receipt in ($arr)  ");
                  //  AND set_addr_send_this=1
             if($addr){
                   return @$addr[0]->recipient_name."<br>".@$addr[0]->addr_send."<br>".@$addr[0]->postcode." ".@$addr[0]->mobile."<br>"."<span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [แก้ไขที่อยู่] </span> ";
             }else{

                   if(@$addr2[0]->delivery_location==1 || @$addr2[0]->delivery_location==2){
                       // return "* ".@$d[0]->txt_desc."<br>"."<span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [แก้ไขที่อยู่] </span> ";
                       return '<font color=red>* ไม่พบข้อมูลที่อยู่ </font> <br>(ระบุในบิล : '.@$d[0]->txt_desc.")<br>"."<span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [แก้ไขที่อยู่] </span> ";
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
                                return @$addr2[0]->recipient_name."<br>".@$addr2[0]->addr_send."<br>".@$addr2[0]->postcode." ".@$addr2[0]->mobile."<br>"."<span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [แก้ไขที่อยู่] </span> ";

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
