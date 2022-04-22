<?php

namespace App\Http\Controllers\backend;

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

      $delivery_id_fk = DB::select(" SELECT * FROM db_pick_pack_packing WHERE packing_code_id_fk=$id ");
      $arr_delivery_id_fk = [];
      foreach ($delivery_id_fk as $key => $value) {
          array_push($arr_delivery_id_fk,$value->delivery_id_fk);
      }
      $arr_delivery_id_fk = implode(',', $arr_delivery_id_fk);

      DB::update(" UPDATE db_delivery SET status_pick_pack='0' WHERE id in (".$arr_delivery_id_fk.")");
      DB::update(" DELETE FROM db_pick_pack_packing WHERE packing_code = $id  ");

      $sRow = \App\Models\Backend\Pick_packPackingCode::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      
      return response()->json(\App\Models\Alert::Msg('success'));

    }

    public function packing_list(Request $request){     

      // $sTable = \App\Models\Backend\Pick_packPackingCode::where('status_picked','1')->search()->orderBy('id', 'asc');
      if(!empty($request->id)){
        $sTable = DB::select(" select * from db_pick_pack_packing_code where id=$request->id ");

      }else{
        $sTable = DB::select(" select * from db_pick_pack_packing_code where status_picked=1 AND status_delivery<>1 AND `status`=1 order by updated_at desc ");
      }
      
      $sQuery = \DataTables::of($sTable);

      return $sQuery
      ->addColumn('packing_code_02', function($row) {
        // return "P2".sprintf("%05d",$row->id);
            $DP = DB::table('db_pick_pack_packing')->where('packing_code_id_fk',$row->id)->first();
            return @$DP->packing_code;
      })      
      ->addColumn('customer_name', function($row) {
          $DP = DB::select(" select * from db_orders where id in (".$row->orders_id_fk.") ");
          $array = array();
          if(@$DP){
            foreach ($DP as $key => $value) {
              $Customer = DB::select(" select * from customers where id=".@$value->customers_id_fk." ");
              array_push($array, $Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name);
            }
            $arr = implode('<br>', $array);
            return $arr;
            // return $array[0];
          }
      })
      ->escapeColumns('customer_name')
      ->addColumn('action_user_name', function($row) {
        if(@$row->action_user!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->action_user." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })

      ->addColumn('receipt02', function($row) {
        if(@$row->receipt!=''){
            $r = explode(',',$row->receipt);
            $t = ' <div class="invoice_code_list" data-toggle="tooltip" data-placement="top" title="คลิ้กเพื่อดูใบเสร็จทั้งหมด" style="cursor:pointer;" >';
            if(count($r)>3){
              for ($i=0; $i < 3 ; $i++) { 
                 $t .= $r[$i]."<br>";
              }
              $t .= "...";
              $arr = [];
              foreach ($r as $key => $value) {
              array_push($arr,$value.'');
              }
              $arr_inv = implode(",",$arr);
              return $t."</div>" .' <input type="hidden" class="arr_inv" value="'.$arr_inv.'"> ';
            }else{
              $arr_inv = implode("<br>",$r);
              return @$arr_inv;
            }
        }
      })
      ->escapeColumns('receipt02')

      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->addColumn('new_bill', function($row) {
        $ref_bill_id = DB::table('db_pick_pack_packing')->select('packing_code')->where('packing_code_id_fk',$row->ref_bill_id)->first();
        $p = '';
        if($ref_bill_id){
          $p = $ref_bill_id->packing_code;
        }
        return $p;
      })
      ->make(true);
    }


 public function packing_list_for_fifo(Request $req){     

       $sPermission = \Auth::user()->permission ;
       $User_branch_id = \Auth::user()->branch_id_fk;

        if(@\Auth::user()->permission==1){

            if(!empty( $req->business_location_id_fk) ){
                $business_location_id = " and business_location_id = ".$req->business_location_id_fk." " ;
            }else{
                $business_location_id = "";
            }
            if(!empty( $req->branch_id_fk) ){
                $branch_id_fk = " and branch_id_fk = ".$req->branch_id_fk." " ;
            }else{
                $branch_id_fk = "";
            }

        }else{

            $business_location_id = " and business_location_id = ".@\Auth::user()->business_location_id_fk." " ;
            $branch_id_fk = " and branch_id_fk = ".@\Auth::user()->branch_id_fk." " ;

        }

   
        if(!empty($req->action_user)){
           $w02 = " and action_user = '".$req->action_user."' " ;
        }else{
           $w02 = "";
        }


        if(isset($req->status_sent) && $req->status_sent!=0 ){
           $w03 = " and status ='".$req->status_sent."' " ;
        }else{
           $w03 = "";
        }

        if(!empty($req->startDate) && !empty($req->endDate)){
           $w04 = " and date(created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
        }else{
           $w04 = "";
        }

        if(!empty($req->startPayDate) && !empty($req->endPayDate)){
           $w05 = " and date(sent_date) BETWEEN '".$req->startPayDate."' AND '".$req->endPayDate."'  " ;
        }else{
           $w05 = "";
        }

        // วุฒิปรับให้ WH ดูได้ทุกสาขา
        $branch_id_fk = "";

        // วุฒิเพิ่มมา
        if(isset($req->remain)){
          $re = " and bill_remain_status = 1";
        }else{
          $re = " and bill_remain_status <> 1";
        }

      // $sTable = \App\Models\Backend\Pick_packPackingCode::where('status_picked','1')->search()->orderBy('id', 'asc');
      $sTable = DB::select(" 

        SELECT * FROM db_pick_pack_packing_code where status_picked=1 $business_location_id $branch_id_fk

        $w02
        $w03
        $w04
        $w05
        $re
        ORDER BY id DESC

        ");
      $sQuery = \DataTables::of($sTable);

      return $sQuery
      ->addColumn('packing_code_02', function($row) {
        // dd($row);
        // return "P2".sprintf("%05d",$row->id);
           $DP = DB::table('db_pick_pack_packing')->where('packing_code_id_fk',$row->id)->first();
           return @$DP->packing_code;
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

      ->addColumn('approver', function($row) {
        $d1 = '';
        if(@$row->approver!=''){
           $sD = DB::select(" select * from ck_users_admin where id=".$row->approver." ");
            $d1 = @$sD[0]->name;
            $d = date("Y-m-d",strtotime($row->created_at))." : ".date("h:i:s",strtotime($row->created_at));
            return "<span data-toggle='tooltip' data-placement='right' title='".$d."' >".$d1."</span>";
        }else{
            return "-";
        }

      })
      ->escapeColumns('approver')

      ->addColumn('amt_receipt', function($row) {
          $amt = explode(',', $row->orders_id_fk);
          return sizeof($amt);
      })

      ->addColumn('action_user', function($row) {
        $d1 = '';
        if(@$row->action_user!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->action_user." ");
           $d1 = @$sD[0]->name;
            // $d = date("Y-m-d",strtotime($row->action_date))." : ".date("h:i:s",strtotime($row->action_date));
            $d = date("Y-m-d",strtotime($row->created_at))." : ".date("h:i:s",strtotime($row->created_at));
           return "<span data-toggle='tooltip' data-placement='right' title='".$d."' >".$d1."</span>";
        }else{
          return "-";
        }
      })
      ->escapeColumns('action_user')


      ->addColumn('sender', function($row) {
        $d1 = '';
        if(@$row->sender!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->sender." ");
           $d1 = @$sD[0]->name;
            $d = date("Y-m-d",strtotime($row->sent_date))." : ".date("h:i:s",strtotime($row->sent_date));
           return "<span data-toggle='tooltip' data-placement='right' title='".$d."' >".$d1."</span>";
        }else{
          return "-";
        }
      })
      ->escapeColumns('action_user')


      ->addColumn('action_date', function($row) {
        // return is_null($row->updated_at) ? '-' : $row->updated_at;
        // $d = date("Y-m-d",strtotime($row->action_date))." : ".date("h:i:s",strtotime($row->action_date));
        $d = date("Y-m-d",strtotime($row->created_at))." : ".date("h:i:s",strtotime($row->created_at));
        return "<span data-toggle='tooltip' data-placement='right' title='".$d."' >".date("Y-m-d",strtotime($row->created_at))."</span>";
      })
      ->escapeColumns('action_date')

      ->addColumn('status_desc', function($row) {
          $sD = DB::select(" select * from dataset_pay_requisition_status_02 where id=".$row->status." ");
          // return @$sD[0]->txt_desc;
          $t = '';
          if($row->status==6){
            if(@$row->who_cancel){
              $w = DB::select(" select * from ck_users_admin where id=".$row->who_cancel." ");
              $t = "ผู้ทำการยกเลิก ".$w[0]->name." : ".@$row->cancel_date;

              }

              return "<span data-toggle='tooltip' data-placement='right' title='".$t."' >".@$sD[0]->txt_desc."</span>";

            
          }else{
            return @$sD[0]->txt_desc;
          }
      })
      ->escapeColumns('status_desc')

      ->addColumn('status_amt_remain', function($row) {
        $DP = DB::table('db_pick_pack_packing')->where('packing_code_id_fk',$row->ref_bill_id)->first();
        // หา max time_pay ก่อน 
           $r_ch01 = DB::select("SELECT time_pay FROM `db_pay_requisition_002_pay_history` where pick_pack_packing_code_id_fk=".$row->id." order by time_pay desc limit 1  ");
        // Check ว่ามี status=2 ? (ค้างจ่าย)
           if(@$r_ch01){
           $r_ch02 = DB::select("SELECT * FROM `db_pay_requisition_002_pay_history` where pick_pack_packing_code_id_fk=".$row->id." and time_pay=".$r_ch01[0]->time_pay." and status=2 ");
           if(count($r_ch02)>0){
               return '<font color=red> มีค้างจ่าย <br> '.@$DP->packing_code.' </font>';
           }else{
             return '';
           }
         }
     
      })
      ->escapeColumns('status_amt_remain')

      ->make(true);
    }

    public function packing_list_for_fifo_report(Request $req){    

      if(isset($req->startDate) && isset($req->endDate)){
        $startDate = $req->startDate;
        $endDate = $req->endDate;
      }else{
        $startDate = date('Y-m-d');
      }

      $pick_code = DB::table('db_pick_pack_packing_code')->whereIn('status',[4,5])
      // ->where('sent_date','>=',$startDate)
      ->pluck('id')->toArray();
      $sTable = DB::table('db_consignments')->whereIn('pick_pack_requisition_code_id_fk',$pick_code)->groupBy('pick_pack_requisition_code_id_fk')->orderBy('recipient_code','asc')->get();
      // dd($sTable);
      // $sTable = DB::select(" SELECT * FROM `db_consignments` where pick_pack_requisition_code_id_fk='$reg->packing_id' group by pick_pack_requisition_code_id_fk order by recipient_code asc");
      $sQuery = \DataTables::of($sTable);

      return $sQuery

      ->addColumn('column_001', function($row) {
        
         $d = DB::select(" SELECT * FROM `db_consignments` where pick_pack_requisition_code_id_fk = $row->pick_pack_requisition_code_id_fk order by delivery_id_fk asc");

          $f = [] ;
          foreach ($d as $key => $v) {
             array_push($f,@$v->recipient_code);
          }
          // $f = implode('<br><br><br>',$f);
            $web_all = "";
          foreach($f as $value){
            $web = "<div class='col-md-12' style='height: 70px;'>";
            $web .=$value;
            $web .= "</div>";
            $web_all .=$web.'<br>';
          }
     
          // return $f;
          return $web_all;
      })
      ->escapeColumns('column_001') 


      ->addColumn('column_002', function($row) {
        
         $d = DB::select(" SELECT * FROM `db_consignments` where pick_pack_requisition_code_id_fk = $row->pick_pack_requisition_code_id_fk order by delivery_id_fk asc");

          $f = [] ;
          foreach ($d as $key => $v) {
             array_push($f,@$v->recipient_name." > ".@$v->address ." ".@$v->postcode.(@$v->mobile?" Tel.".@$v->mobile:"").(@$v->phone_no?", ".@$v->phone_no:"") );
          }

          $web_all = "";
          foreach($f as $value){
            $web = "<div class='col-md-12'style='height: 70px;'>";
            $web .=$value;
            $web .= "</div>";
            $web_all .=$web.'<br>';
          }

          return $web_all;
          // $f = implode('<br><br>',$f);
          // return $f;


      })
      ->escapeColumns('column_002')  

      ->addColumn('column_003', function($row) {
          return "<input type='button' class=\"btn btn-primary btnExportElsx \" data-id='".$row->pick_pack_requisition_code_id_fk."' value='Export Excel' >";
      })
      ->escapeColumns('column_003')  

      ->addColumn('column_004', function($row) {
          
          $d = DB::select(" SELECT * FROM `db_consignments` where pick_pack_requisition_code_id_fk = $row->pick_pack_requisition_code_id_fk order by delivery_id_fk asc");
      
          $f = [] ;
          $ff = [] ;
          foreach ($d as $key => $v) {
             array_push($f,@$v->consignment_no);
             array_push($ff,@$v->con_arr);
          }
          // $f = implode('<br><br><br>',$f);
          // return $f;

          $web_all = "";
          foreach($f as $key => $value){
            $web = "<div class='col-md-12'style='height: 70px;'><a href='javascript:;' class='con_arr_data_show' con_arr=' ".$ff[$key]."' >";
            $web .=$value;
            $web .= "</a></div>";
            $web_all .=$web.'<br>';
          }

          return $web_all;

      })
      ->escapeColumns('column_004')  

      ->addColumn('column_005', function($row) {
          
          $d = DB::select(" SELECT * FROM `db_consignments` where pick_pack_requisition_code_id_fk = $row->pick_pack_requisition_code_id_fk order by delivery_id_fk asc");
    
          $f = [] ;
          $tx = '';
          foreach ($d as $key => $v) {

              $tx = '<center> 
                 
                <a href="backend/pick_warehouse/print_envelope/'.$v->recipient_code.'" target=_blank ><i class="bx bx-printer grow " data-toggle="tooltip" data-placement="left" title="ใบปะหน้ากล่อง" style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a> 

                 </center>' ;

             array_push($f,@$tx);
          }
          // $f = implode('<br><br>',$f);
          // return $f;

          $web_all = "";
          foreach($f as $value){
            $web = "<div class='col-md-12'style='height: 70px;'>";
            $web .=$value;
            $web .= "</div>";
            $web_all .=$web.'<br>';
          }

          return $web_all;

      })
      ->escapeColumns('column_005')

      ->addColumn('column_008', function($row) {
          
        $d = DB::select(" SELECT * FROM `db_consignments` where pick_pack_requisition_code_id_fk = $row->pick_pack_requisition_code_id_fk order by delivery_id_fk asc");

        $f = [] ;
        $tx = '';
        foreach ($d as $key => $v) {

            $tx = '<center> 

              <a href="javascript: void(0);" target=_blank data-id="'.$v->recipient_code.'" class="print02" data-toggle="tooltip" data-placement="bottom" title="ใบเสร็จ"  > <i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#476b6b;"></i></a>

               </center>' ;

           array_push($f,@$tx);
        }
        // $f = implode('<br><br>',$f);
        // return $f;
        $web_all = "";
        foreach($f as $value){
          $web = "<div class='col-md-12'style='height: 70px;'>";
          $web .=$value;
          $web .= "</div>";
          $web_all .=$web.'<br>';
        }

        return $web_all;

    })
    ->escapeColumns('column_008')

      ->addColumn('column_006', function($row) {
          // return '<center> <a href="backend/pick_warehouse/print_requisition/'.$row->pick_pack_requisition_code_id_fk.'" target=_blank title="พิมพ์ใบเบิก"> <i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#660000;"></i></a> </center>';

          $db_pick_pack_packing_code = DB::table('db_pick_pack_packing_code')
          ->where('id',$row->pick_pack_requisition_code_id_fk)->first();

          // วุฒิแก้อีกรอบ
          if($db_pick_pack_packing_code->bill_remain_status==1){
            $DP = DB::table('db_pick_pack_packing')
            ->where('packing_code_id_fk',$row->pick_pack_requisition_code_id_fk)
            ->where('no_product',0)
            ->orderBy('db_pick_pack_packing.delivery_id_fk','asc')
            ->get();
          }else{
            $DP = DB::table('db_pick_pack_packing')
            ->where('packing_code_id_fk',$row->pick_pack_requisition_code_id_fk)
            ->orderBy('db_pick_pack_packing.delivery_id_fk','asc')
            ->get();
          }
     
         
          $pn = '';
          $f = [] ;
          $tx = '';

          if(!empty($DP)){
            foreach ($DP as $key => $value) {
              if($value->p_amt_box==null){
                DB::table('db_pick_pack_packing')->where('id',$value->id)->update([
                  'p_amt_box' => 1,
                ]);
                $value->p_amt_box = 1;
              }
              // $pn = '<input type="number" class="p_amt_box form-control" data-id="'.@$value->id.'" box-id="0"  type="text" value="'.@$value->p_amt_box.'">';
              $pn = @$value->p_amt_box;
              array_push($f,@$pn);
            }
          }

          $web_all = "";
          foreach($f as $value){
            $web = "<div class='col-md-12'style='height: 70px;'>";
            $web .=$value;
            $web .= "</div>";
            $web_all .=$web.'<br>';
          }

          return $web_all;
      
        })

      ->escapeColumns('column_006')  

      ->addColumn('column_007', function($row) {
        $p_code = DB::table('db_pick_pack_packing_code')->where('id',$row->pick_pack_requisition_code_id_fk)->first();
        if($p_code){
            $DP = DB::table('db_pick_pack_packing')
            ->select('db_pick_pack_packing.*')
            // ->join('db_consignments','db_consignments.delivery_id_fk','db_pick_pack_packing.delivery_id_fk')
            ->where('db_pick_pack_packing.packing_code_id_fk',$p_code->id)
            // ->orderBy('db_consignments.recipient_code','asc')
            ->where('no_product',0)
            ->orderBy('db_pick_pack_packing.delivery_id_fk','asc')
            ->get();
            if(!empty($DP)){
              $pn = '';
              $arr = [];
              foreach ($DP as $key => $value) {
                  $pn .=     
                    '<div class="col-md-12" style="height: 70px;">
                    <center> 
                    <a href="backend/pick_warehouse/print_requisition_detail/'.$value->delivery_id_fk.'/'.$p_code->id.'" title="รายละเอียดลูกค้า" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a>
                    </center>
                    </div><br>';
              } 
               return $pn;
            }else{
              return '-';
            }
        }else{
          return '-';
        }
        })

      ->make(true);
   }

 public function packing_list_for_fifo_02(Request $request){     
      // if(!empty($request->packing_id)){
          $sTable = \App\Models\Backend\Pick_packPackingCode::where('id',$request->id)->search();
      // }else{
      //     $sTable = \App\Models\Backend\Pick_packPackingCode::where('status_picked','1')->search()->orderBy('id', 'asc');
      // }
      $sQuery = \DataTables::of($sTable);

      return $sQuery
      ->addColumn('packing_code_02', function($row) {
        // return "P2".sprintf("%05d",$row->id);
           $DP = DB::table('db_pick_pack_packing')->where('packing_code_id_fk',$row->id)->first();
           return $DP->packing_code;
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

    //   ->addColumn('print', function($row) {

    //     $arr_order = explode(',',$row->orders_id_fk);
    //     $f = [] ;
    //     $tx = '';

    //     $order = DB::table('db_orders')->whereIn('id',$arr_order)->orderBy('id','asc')->get();

    //     foreach ($order as $key => $v) {

    //         $tx = '<center> 

    //           <a href="javascript: void(0);" target=_blank data-id="'.$v->id.'" class="print02" data-toggle="tooltip" data-placement="bottom" title="ใบเสร็จ"  > '.$v->code_order.' <i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#476b6b;"></i></a>

    //            </center>' ;

    //        array_push($f,@$tx);
    //     }
        
    //     $web_all = "";
    //     foreach($f as $value){
    //       $web_all .=$value.'<br>';
    //     }

    //     return $web_all;

    // })
    // ->escapeColumns('print')
      ->make(true);
    }



}
