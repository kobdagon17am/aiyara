<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;
use App\Models\Backend\AddressSent;

class StatusDeliveryController extends Controller
{

    public function index(Request $request)
    {
      // รายที่ยังไม่อนุมัติ และ รอจัดส่ง และ ไม่ได้รอส่งไปสาขาอื่น
      // $receipt = \App\Models\Backend\Delivery::where('approver','NULL')->get();
        // $receipt = DB::select(" select receipt from `db_delivery` where approver is null ; ");
        // $receipt = DB::select(" SELECT code_order FROM db_orders where code_order <>'' ");
        // dd($sDelivery);
        // $sPacking = \App\Models\Backend\DeliveryPackingCode::where('status_delivery','<>','2')->get();

        $User_branch_id = \Auth::user()->branch_id_fk;
        $sBranchs = \App\Models\Backend\Branchs::get();
        $Warehouse = \App\Models\Backend\Warehouse::get();
        $Zone = \App\Models\Backend\Zone::get();
        $Shelf = \App\Models\Backend\Shelf::get();

        $sBusiness_location = \App\Models\Backend\Business_location::get();

        $shipping_cost = DB::select(" SELECT purchase_amt FROM `dataset_shipping_cost` WHERE shipping_type_id=1 ; ");
        $shipping_cost= $shipping_cost[0]->purchase_amt;

      $sProvince = DB::select(" select *,name_th as province_name from dataset_provinces order by name_th ");
      $sAmphures = DB::select(" select *,name_th as amphur_name from dataset_amphures order by name_th ");
      $sTambons = DB::select(" select *,name_th as tambon_name from dataset_districts order by name_th ");


        return View('backend.status_delivery.index')->with(
          array(
            //  'receipt'=>$receipt,
             'sBranchs'=>$sBranchs,
             'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,
             'User_branch_id'=>$User_branch_id,
             'sBusiness_location'=>$sBusiness_location,
            //  'sPacking'=>$sPacking,
             'shipping_cost'=>$shipping_cost,
             'sProvince'=>$sProvince,
             'sAmphures'=>$sAmphures,
             'sTambons'=>$sTambons,
          ) );


    }



    public function Datatable(Request $req)
    {
      $sPermission = \Auth::user()->permission ;
      $User_branch_id = \Auth::user()->branch_id_fk;
     //  วุฒิเพิ่มมาเช็คว่าใช่ WH ไหม
      $role_group = DB::table('role_group')->select('wh_status')->where('id',@\Auth::user()->role_group_id_fk)->first();
       if(@\Auth::user()->permission==1 || @$role_group->wh_status==1){
           $billing_employee = '';
       }else{
           $business_location_id = " and db_delivery.business_location_id = ".@\Auth::user()->business_location_id_fk." " ;
           $branch_id_fk = " and db_delivery.branch_id_fk = ".@\Auth::user()->branch_id_fk." " ;
           $billing_employee = " and db_delivery.billing_employee = ".@\Auth::user()->id." " ;
       }


       if (!empty($req->receipt)) {
        if(count($req->receipt) > 0){
            $or_str = "";
            foreach($req->receipt as $key => $or){
              if($key+1 == count($req->receipt)){
                $or_str.= "'".$or."'";
              }else{
                $or_str.= "'".$or."'".',';
              }

            }
            $receipt = " AND db_delivery.receipt IN (".$or_str.") ";
          // $receipt = " AND code_order = '" . $req->receipt . "' ";
          // wut เพิ่ม
          $action_user_01 = "";
          $action_user_011 = "";
        }
      } else {
        $receipt = "";
      }

        //  if(!empty($req->receipt)){
        // $receipt = " and db_delivery.receipt =  '".$req->receipt."'" ;
        // }else{
        //     $receipt = "";
        // }

        if(!empty($req->customer_id_fk)){
          $customer_id_fk = " and db_delivery.customer_id =  '".$req->customer_id_fk."'" ;
      }else{
          $customer_id_fk = "";
      }

      if(!empty($req->bill_sdate) && !empty($req->bill_edate)){
         $delivery_date = " and date(db_delivery_packing_code.updated_at) BETWEEN '".$req->bill_sdate."' AND '".$req->bill_edate."'  " ;
      }else{
         $delivery_date = " and date(db_delivery_packing_code.updated_at) BETWEEN '".date('Y-m-d')."' AND '".date('Y-m-d')."' ";
      }

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

       // วุฒิเอา  $branch_id_fk ออก
       // order by db_delivery_packing_code.updated_at desc
      //  dd($receipt);
      $sTable = DB::select("
         SELECT db_delivery_packing_code.*,db_delivery_packing.packing_code,db_orders.action_user as action_user_data,db_delivery.status_to_wh_date,db_delivery.updated_at as d_updated_at,db_orders.distribution_channel_id_fk, db_orders.shipping_special,db_orders.id as db_orders_id,db_delivery.id as db_delivery_id,db_delivery.status_to_wh,db_delivery.status_to_wh_by,db_delivery.status_tracking , MAX(db_orders.shipping_special) AS 'shipping_special' from db_delivery_packing_code
         LEFT JOIN db_delivery_packing on db_delivery_packing.packing_code_id_fk=db_delivery_packing_code.id
         LEFT JOIN db_delivery on db_delivery.id=db_delivery_packing.delivery_id_fk
         LEFT JOIN db_orders on db_orders.id=db_delivery.orders_id_fk
         where db_delivery_packing_code.id <> 0
         $business_location_id
         $receipt
         $customer_id_fk
         $delivery_date
         group by db_delivery_packing_code.id
         order by db_delivery_packing_code.updated_at desc
       ");
      //  WHERE db_delivery.status_pick_pack<>1 AND db_delivery.status_delivery<>1

      // packing_code
     $sQuery = \DataTables::of($sTable);
     return $sQuery
     ->addColumn('status_pick_pack', function($row) {

      //  dd($row);
        $DP = DB::table('db_delivery_packing')->select('delivery_id_fk')->where('packing_code_id_fk',$row->id)->get();
        if(@$DP){
             foreach ($DP as $key => $value) {
               $rs = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
               return $rs[0]->status_pick_pack;
             }
           }else{
            return $row->id;
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
       $DP = DB::table('db_delivery_packing')->select('packing_code')->where('packing_code_id_fk',$row->id)->first();
       if($DP){
        return $DP->packing_code;
       }
       else{
        return '-';
       }

     })
     ->addColumn('receipt', function($row) {
       if($row->id!==""){
           $DP = DB::table('db_delivery_packing')->select('delivery_id_fk')->where('packing_code_id_fk',$row->id)->get();
           $array = array();
           if(@$DP){
             foreach ($DP as $key => $value) {
               $rs = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
               // array_push($array, @$rs[0]->receipt);
               $p = "<a href='".url('backend/frontstore/print_receipt_022/'.@$rs[0]->orders_id_fk)."' target='blank'>".@$rs[0]->receipt."</a>";
               array_push($array, ($key+1).')'.$p);
             }
             $arr = array_filter($array);
             $arr = implode('<br>', $arr);
             return $arr;
           }
         }
     })
     ->escapeColumns('receipt')
     ->addColumn('customer_name', function($row) {
         $DP = DB::table('db_delivery_packing')->select('delivery_id_fk')->where('packing_code_id_fk',$row->id)->get();
         $array = array();
         if(@$DP){
           foreach ($DP as $key => $value) {
             $rs = DB::table('db_delivery')->select('customer_id')->where('id',$value->delivery_id_fk)->get();
             if(!empty(@$rs[0]->customer_id)){
               $Customer = DB::select(" select prefix_name,first_name,last_name from customers where id=".@$rs[0]->customer_id." ");
               array_push($array, $Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name);
             }
           }
           $arr = implode(',', $array);
           return $arr;
         }
     })
     ->addColumn('shipping_special_detail', function($row) {
       $data = "ส่งแบบธรรมดา";
       if($row->shipping_special == 1){
         $data = "<span style='color:red;'>ส่งแบบพิเศษ/พรีเมี่ยม</span>";
       }
       return $data;
     })
     ->addColumn('addr_to_send', function($row) {

          if($row->id!==""){
             $DP = DB::table('db_delivery_packing')->select('delivery_id_fk')->where('packing_code_id_fk',$row->id)->get();
             $array = array();
             if(@$DP){
               foreach ($DP as $key => $value) {
                 $rs = DB::table('db_delivery')->select('receipt')->where('id',$value->delivery_id_fk)->get();
                 array_push($array, "'".@$rs[0]->receipt."'");
               }
               $arr = implode(',', $array);
             }
           }

           if(count($array)>0){
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
            db_delivery_packing_code.id = ".$row->id." and db_delivery.receipt in ($arr)  ");
           //  AND set_addr_send_this=1
      if($addr){
        if($row->status_to_wh==0){
          return @$addr[0]->recipient_name."<br>".@$addr[0]->addr_send." ".@$addr[0]->postcode."<br>".@$addr[0]->mobile."<br>";
        }else{
          return @$addr[0]->recipient_name."<br>".@$addr[0]->addr_send." ".@$addr[0]->postcode."<br>".@$addr[0]->mobile."<br>";
          // ."<span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [แก้ไขที่อยู่] </span> ";
        }


      }else{
        return "* กรณีได้สิทธิ์ส่งฟรี กรุณาระบุที่อยู่อีกครั้ง <span class='class_add_address' data-id=".$row->id." style='cursor:pointer;color:blue;'> [Click here] </span> ";
      }
           }else{
            return '-';
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

         if($row->status_to_wh_date==''){
          $p = '
          <b class="" style="color:green;">ยืนยันแล้ว</b>
          <br> โดย : '.$user_name.'
          <br> '.$row->d_updated_at.'
          ';
         }else{
          $p = '
          <b class="" style="color:green;">ยืนยันแล้ว</b>
          <br> โดย : '.$user_name.'
          <br> '.$row->status_to_wh_date.'
          ';
         }


       }else{
      //    $p = '
      //    <a onclick="return confirm(\'ยืนยันการทำรายการ\')" href="'.url('backend/delivery_approve_to_wh/'.$row->db_delivery_id).'" class="btn btn-sm btn-success">ยืนยัน</a>
      //  ';
      $p = '-';
       }

       return $p;
     })

     ->addColumn('status_tracking', function($row) {
       $p = "";
       if($row->status_tracking==0){
        $p = '<label>รอเบิก</label>';
       }elseif($row->status_tracking==1){
        $p = '<label style="color:green;">เบิกสินค้า</label>';
       }elseif($row->status_tracking==2){
        $p = '<label style="color:green;">แพ็คสินค้า</label>';
       }elseif($row->status_tracking==3){
        $p = '<label style="color:green;">ส่งออกสินค้า</label>';
       }

       if($row->status_tracking==1 || $row->status_tracking==2 || $row->status_tracking==3){
        $DP = DB::table('db_delivery_packing')->select('delivery_id_fk')->where('packing_code_id_fk',$row->id)->get();
        if(@$DP){
          foreach ($DP as $key => $value) {
            $rs = DB::table('db_delivery')->select('id')->where('id',$value->delivery_id_fk)->first();
            if($rs){
                 $db_pick_pack_packing = DB::table('db_pick_pack_packing')->select('packing_code_id_fk')->where('delivery_id_fk',$rs->id)->orderBy('packing_code_id_fk','desc')->first();
                 if($db_pick_pack_packing){

                  if($row->status_tracking==1){
                    $db_pick_pack_packing_code = DB::table('db_pick_pack_packing_code')->select('action_user','updated_at')->where('id',$db_pick_pack_packing->packing_code_id_fk)->first();

                    if($db_pick_pack_packing_code){
                      $action_user = DB::table('ck_users_admin')->select('name')->where('id',$db_pick_pack_packing_code->action_user)->first();
                      $p .= '<br> โดย : '.@$action_user->name;
                      $p .= '<br> '.@$db_pick_pack_packing_code->updated_at;
                      break;
                    }
                  }elseif($row->status_tracking==2){
                    $db_pick_pack_packing_code = DB::table('db_pick_pack_packing_code')->select('approver','aprove_date')->where('id',$db_pick_pack_packing->packing_code_id_fk)->first();
                    if($db_pick_pack_packing_code){
                      $approver = DB::table('ck_users_admin')->select('name')->where('id',$db_pick_pack_packing_code->approver)->first();
                      $p .= '<br> โดย : '.@$approver->name;
                      $p .= '<br> '.@$db_pick_pack_packing_code->aprove_date;
                      break;
                    }
                  }elseif($row->status_tracking==3){
                    $db_pick_pack_packing_code = DB::table('db_pick_pack_packing_code')->select('sender','sent_date')->where('id',$db_pick_pack_packing->packing_code_id_fk)->first();
        //                    if($row->packing_code=='P100007'){
        // // dd($row->db_delivery_id);
        // dd($db_pick_pack_packing);
        //       }
                    if($db_pick_pack_packing_code){
                      $sender = DB::table('ck_users_admin')->select('name')->where('id',$db_pick_pack_packing_code->sender)->first();
                      $p .= '<br> โดย : '.@$sender->name;
                      $p .= '<br> '.@$db_pick_pack_packing_code->sent_date;
                      break;
                    }
                  }
                 }
            }
          }
        }

       }

      return $p;
    })

    ->addColumn('tracking_no', function($row) {
      $p = "";
      // if($row->packing_code=='P100008'){
      //   // dd($row->db_delivery_id);
      //   dd($row);
      //         }
      if($row->status_tracking==3 || $row->status_tracking==2){
        $pcode = DB::table('db_pick_pack_packing')->select('packing_code_id_fk')->where('delivery_id_fk',$row->db_delivery_id)->orderBy('packing_code_id_fk','desc')->first();
              // if($row->packing_code=='P100008'){
              //     dd($pcode);
              // }
          if($pcode){
            $data = DB::table('db_consignments')->select('con_arr')->where('pick_pack_requisition_code_id_fk',$pcode->packing_code_id_fk)->where('recipient_code',$row->packing_code)->get();
          //   if($row->packing_code=='P100008'){
          //     dd($pcode->packing_code_id_fk);
          // }
            foreach($data as $d){
              $arr = explode(',',$d->con_arr);
              foreach($arr as $ar){
                $p .= @$ar.'<br>';
              }
            }
          }
       }

      //  $DP = DB::table('db_delivery_packing')->select('packing_code')->where('packing_code_id_fk',$row->id)->first();
      //  return $DP->packing_code;

     return $p;
   })

   ->addColumn('tranfer_status', function($row) {
    $p = "";
    if($row->status_tracking==3){
      $pcode = DB::table('db_pick_pack_packing')->select('packing_code_id_fk')->where('delivery_id_fk',$row->db_delivery_id)->orderBy('packing_code_id_fk','desc')->first();

        if($pcode){
          $data = DB::table('db_consignments')->select('tracking_status','tracking_remark')->where('pick_pack_requisition_code_id_fk',$pcode->packing_code_id_fk)->where('recipient_code',$row->packing_code)->get();

          foreach($data as $d){
            // $arr = explode(',',$d->con_arr);
            // foreach($arr as $ar){
            //   $p .= @$ar.'<br>';
            // }
            if($d->tracking_status==0){
              $p .= 'รอยืนยัน<br>หมายเหตุ: '.$d->tracking_remark;
            }
            if($d->tracking_status==1){
              $p .= 'จัดส่งสำเร็จ<br>หมายเหตุ: '.$d->tracking_remark;
            }
            if($d->tracking_status==2){
              $p .= 'จัดส่งไม่สำเร็จ<br>หมายเหตุ: '.$d->tracking_remark;
            }

          }
        }
     }
   return $p;
 })


     ->addColumn('updated_at', function($row) {
       return is_null($row->updated_at) ? '-' : $row->updated_at;
     })
     ->make(true);
    }


    public function delivery_approve_to_wh($id)
    {
      DB::table('db_delivery')->where('id',$id)->update([
        'status_to_wh' => 1,
        'status_to_wh_by' => @\Auth::user()->id,
      ]);
      return redirect()->back();
    }


}
