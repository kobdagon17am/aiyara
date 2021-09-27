<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;

class Pick_warehouseController extends Controller
{

    public function index(Request $request)
    {

      $Pick_warehouse_fifo_topicked = \App\Models\Backend\Pick_warehouse_fifo_topicked::get();
     // dd($Pick_warehouse_fifo_topicked);
      $Pick_warehouse_fifo = \App\Models\Backend\Pick_warehouse_fifo::search()->orderBy('lot_expired_date', 'desc');
      // dd($Pick_warehouse_fifo);

      $sDelivery = \App\Models\Backend\Delivery::where('approver','NULL')->get();
      $sPacking = \App\Models\Backend\DeliveryPackingCode::where('status_delivery','<>','2')->get();
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $Customer = DB::select(" SELECT
          db_delivery.customer_id as id,
          customers.prefix_name,
          customers.first_name,
          customers.last_name,
          customers.user_name
          FROM
          db_delivery
          Left Join customers ON db_delivery.customer_id = customers.id GROUP BY db_delivery.customer_id 
           ");

      return View('backend.pick_warehouse.index')->with(
        array(
           'sDelivery'=>$sDelivery,
           'Customer'=>$Customer,
           'sBusiness_location'=>$sBusiness_location,
           'sPacking'=>$sPacking,
           'Pick_warehouse_fifo_topicked'=>$Pick_warehouse_fifo_topicked,
        ) );

      
    }


 public function create()
    {

      $Province = DB::select(" select * from dataset_provinces ");

      $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.pick_warehouse.form')->with(
        array(
           'Customer'=>$Customer,'Province'=>$Province
        ) );
    }


    public function store(Request $request)
    {
        // dd($request->all());
        return $this->form();
      
    }

    public function edit($id)
    {

      // dd($id);

      $r = DB::select("SELECT * FROM db_pay_requisition_001 where id in($id) ");
      $sRow = \App\Models\Backend\Pick_packPackingCode::find($r[0]->pick_pack_requisition_code_id_fk);
      // dd($sRow);

        $addr_sent =  DB::select(" 
            SELECT customer_id,from_table FROM `customers_addr_sent` WHERE packing_code=".$r[0]->pick_pack_requisition_code_id_fk."
        ");

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

        @$address = "";
        @$address .= "". @$addr[0]->prefix_name.@$addr[0]->first_name." ".@$addr[0]->last_name;
        @$address .= "". @$addr[0]->house_no. " ". @$addr[0]->house_name. " ";
        @$address .= " ต. ". @$addr[0]->tamname;
        @$address .= " อ. ". @$addr[0]->ampname;
        @$address .= " จ. ". @$addr[0]->provname;
        @$address .= " รหัส ปณ. ". @$addr[0]->zipcode ;

      }else{
        @$address = '-ไม่พบข้อมูล-';
      }
      
        $sUser =  DB::select(" 
            SELECT
            db_pay_requisition_001.id,
            db_pay_requisition_001.business_location_id_fk,
            db_pay_requisition_001.branch_id_fk,
            db_pay_requisition_001.pick_pack_requisition_code_id_fk,
            (select name from ck_users_admin where id=db_pay_requisition_001.action_user)  AS user_action,
            db_pay_requisition_001.action_date,
            (select name from ck_users_admin where id=db_pay_requisition_001.pay_user)  AS pay_user,
            db_pay_requisition_001.pay_date,
            db_pay_requisition_001.status_sent,
            db_pay_requisition_001.customer_id_fk,
            db_pay_requisition_001.address_send AS user_address,
            db_pay_requisition_001.address_send_type,
            customers.user_name AS user_code,
            CONCAT(customers.prefix_name,customers.first_name,' ',customers.last_name) AS user_name,
            dataset_pay_product_status.txt_desc as bill_status
            FROM
            db_pay_requisition_001
            Left Join customers ON $customer_id = customers.id
            Left Join dataset_pay_product_status ON db_pay_requisition_001.status_sent = dataset_pay_product_status.id
            where db_pay_requisition_001.id in ($id)
        ");
      return View('backend.pick_warehouse.form')->with(
        array(
           'pick_pack_requisition_code_id_fk'=>$r[0]->pick_pack_requisition_code_id_fk,'sUser'=>$sUser,'user_address'=>@$address,'id'=>$id,
        ) );
    }


    public function qr($id)
    {
          // return $id;

      $r = DB::select("SELECT db_pay_requisition_001.*,db_pick_pack_requisition_code.requisition_code FROM db_pay_requisition_001 LEFT Join db_pick_pack_requisition_code ON db_pay_requisition_001.pick_pack_requisition_code_id_fk = db_pick_pack_requisition_code.id
where db_pay_requisition_001.id =$id ");
      $sRow = \App\Models\Backend\Pick_packPackingCode::find($r[0]->pick_pack_requisition_code_id_fk);
      // dd($sRow);
      $requisition_code = $r[0]->requisition_code;
      // dd($requisition_code);

        $addr_sent =  DB::select(" 
            SELECT customer_id,from_table FROM `customers_addr_sent` WHERE packing_code=".$r[0]->pick_pack_requisition_code_id_fk."
        ");
        if($addr_sent){
            $customer_id = $addr_sent[0]->customer_id;
            $from_table = $addr_sent[0]->from_table;

            $addr =  DB::select(" 
                SELECT * FROM $from_table WHERE customer_id=$customer_id
            ");
        }else{
          $customer_id = 0 ;
          $addr =  [0];
        }


      if(sizeof(@$addr)>0){

        @$address = "";
        @$address .= "". @$addr[0]->prefix_name.@$addr[0]->first_name." ".@$addr[0]->last_name;
        @$address .= "". @$addr[0]->house_no. " ". @$addr[0]->house_name. " ";
        @$address .= " ต. ". @$addr[0]->tamname;
        @$address .= " อ. ". @$addr[0]->ampname;
        @$address .= " จ. ". @$addr[0]->provname;
        @$address .= " รหัส ปณ. ". @$addr[0]->zipcode ;

      }else{
        @$address = '-ไม่พบข้อมูล-';
      }
      
        $sUser =  DB::select(" 
            SELECT
            db_pay_requisition_001.id,
            db_pay_requisition_001.business_location_id_fk,
            db_pay_requisition_001.branch_id_fk,
            db_pay_requisition_001.pick_pack_requisition_code_id_fk,
            (select name from ck_users_admin where id=db_pay_requisition_001.action_user)  AS user_action,
            db_pay_requisition_001.action_date,
            (select name from ck_users_admin where id=db_pay_requisition_001.pay_user)  AS pay_user,
            db_pay_requisition_001.pay_date,
            db_pay_requisition_001.status_sent,
            db_pay_requisition_001.customer_id_fk,
            db_pay_requisition_001.address_send AS user_address,
            db_pay_requisition_001.address_send_type,
            customers.user_name AS user_code,
            CONCAT(customers.prefix_name,customers.first_name,' ',customers.last_name) AS user_name,
            dataset_pay_product_status.txt_desc as bill_status
            FROM
            db_pay_requisition_001
            Left Join customers ON $customer_id = customers.id
            Left Join dataset_pay_product_status ON db_pay_requisition_001.status_sent = dataset_pay_product_status.id
            where db_pay_requisition_001.id = '$id'
        ");
        // dd($sUser);

      $user_amdin = DB::select(" select * from ck_users_admin where id=".(\Auth::user()->id)."");
      $role_group = DB::select(" SELECT can_cancel_packing_sent FROM `role_permit` where can_cancel_packing_sent=1 and role_group_id_fk=".$user_amdin[0]->role_group_id_fk."");
      // dd($user_amdin[0]->role_group_id_fk);
      // dd($role_group[0]->can_cancel_packing_sent);
      if(@$role_group[0]->can_cancel_packing_sent==1){
          $can_cancel_packing_sent = 1;
      }else{
          $can_cancel_packing_sent = 0;
      }

      return View('backend.pick_warehouse.qr')->with(
        array(
           'packing_id'=>@$sRow->id,'sUser'=>$sUser,'user_address'=>@$address,'id'=>@$id,'requisition_code'=>@$requisition_code,
           'can_cancel_packing_sent'=>@$can_cancel_packing_sent,
        ) );
      // return View('backend.pick_warehouse.qr');

    }


    public function update(Request $request, $id)
    {
      // dd($request->all());
      return $this->form($id);
    }

   public function form($id=NULL)
    {

      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow = \App\Models\Backend\Pick_warehouse::find($id);
          }else{
            $sRow = new \App\Models\Backend\Pick_warehouse;
          }

          $sRow->receipt    = request('receipt');
          $sRow->customer_id    = request('customer_id');
          $sRow->province_id_fk    = request('province_id_fk');
          $sRow->pick_warehouse_date    = request('pick_warehouse_date');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

          return redirect()->to(url("backend/pick_warehouse"));

           // return redirect()->to(url("backend/pick_warehouse/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Pick_warehouseController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {

        DB::update(" UPDATE db_pick_warehouse SET status_pick_warehouse='0',updated_at=now() WHERE id in ($id)  ");

        $rs = DB::select(" select * from db_pick_warehouse WHERE id in ($id)  ");

        foreach ($rs as $key => $value) {
        	DB::update(" UPDATE db_pick_warehouse_packing_code SET status_pick_warehouse='0',updated_at=now() WHERE id = ".$value->packing_code."  ");
        }

        DB::update(" 
	        UPDATE
			db_pick_warehouse_packing_code
			Inner Join db_pick_warehouse ON db_pick_warehouse_packing_code.id = db_pick_warehouse.packing_code
			SET
			db_pick_warehouse.status_pick_warehouse=db_pick_warehouse_packing_code.status_pick_warehouse
			WHERE
			db_pick_warehouse_packing_code.status_pick_warehouse=0
		 ");
      
      return response()->json(\App\Models\Alert::Msg('success'));

    }

    public function Datatable(){
      $sTable = DB::select(" 
      	select * from db_delivery WHERE status_pack=0 and status_pick_pack=0 
    		UNION
    		select * from db_delivery WHERE status_pack=1 and status_pick_pack=0 GROUP BY packing_code
    		ORDER BY delivery_date DESC
		 ");
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
      ->addColumn('business_location', function($row) {
        if(@$row->business_location_id!=''){
             $P = DB::select(" select * from dataset_business_location where id=".@$row->business_location_id." ");
             return @$P[0]->txt_desc;
        }else{
             return '-';
        }
      })
      ->addColumn('packing_code', function($row) {
        if(@$row->packing_code!=''){
             return "P".sprintf("%05d",@$row->packing_code);
        }
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



    public function Datatable0002(Request $req){

      $sTable = DB::select(" SELECT * FROM db_pay_requisition_001  WHERE  pick_pack_requisition_code_id_fk='".$req->packing_id."' 
        group by time_pay order By time_pay ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) { 

            $rs = DB::select(" SELECT
                  db_pay_requisition_002_pay_history.id,
                  db_pay_requisition_002_pay_history.time_pay,
                  db_pay_requisition_002_pay_history.pick_pack_requisition_code_id_fk,
                  DATE(db_pay_requisition_002_pay_history.pay_date) as pay_date,
                  db_pay_requisition_002_pay_history.`status`,
                  ck_users_admin.`name` as pay_user
                  FROM
                  db_pay_requisition_002_pay_history
                  Left Join ck_users_admin ON db_pay_requisition_002_pay_history.pay_user = ck_users_admin.id  WHERE pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."' and time_pay=".$row->time_pay." group by time_pay order By time_pay ");

                        $pn = '<div class="divTable"><div class="divTableBody">';
                        $pn .=     
                        '<div class="divTableRow">
                        <div class="divTableCell" style="width:50px;font-weight:bold;">ครั้งที่</div>
                        <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;">วันที่จ่าย</div>
                        <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;"> พนักงาน </div>
                        </div>
                        ';
                  foreach ($rs as $v) {                        
                        $pn .=     
                        '<div class="divTableRow">
                        <div class="divTableCell" style="text-align:center;font-weight:bold;">'.$v->time_pay.'</div>
                        <div class="divTableCell" style="text-align:center;font-weight:bold;">'.$v->pay_date.'</div>
                        <div class="divTableCell" style="text-align:center;font-weight:bold;">'.$v->pay_user.'</div>
                        </div>
                        ';
                  }

            return $pn;

        })
        ->escapeColumns('column_001')
        ->addColumn('column_002', function($row) {

          $Products = DB::select(" SELECT
            db_pay_requisition_002.*,sum(amt_get) as sum_amt_get
            FROM
            db_pay_requisition_002 
            WHERE pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."' and time_pay=".$row->time_pay." group by time_pay,product_id_fk ORDER BY product_name ");

          $pn = '<div class="divTable"><div class="divTableBody">';
          $pn .=     
          '<div class="divTableRow">
          <div class="divTableCell" style="width:240px;font-weight:bold;">ชื่อสินค้า</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จ่ายครั้งนี้</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">ค้างจ่าย</div>
          <div class="divTableCell" style="width:450px;text-align:center;font-weight:bold;">

                      <div class="divTableRow">
                      <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">หยิบสินค้าจากคลัง</div>
                      <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;"> Lot number [Expired]</div>
                      <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
                      </div>

          </div>
          </div>
          ';

          $amt_get = 0;
         
          foreach ($Products as $key => $v) {

                   $css_font = $v->amt_remain>0?"color:red;font-weight:bold;":"";

                     $pn .=     
                    '<div class="divTableRow">
                    <div class="divTableCell" style="font-weight:bold;padding-bottom:15px;"> '.$v->product_name.'</div>
                    <div class="divTableCell" style="text-align:center;color:blue;font-weight:bold;"> '.$v->sum_amt_get.'</div>
                    <div class="divTableCell" style="text-align:center;'.$css_font.'"> '.$v->amt_remain.'</div>
                    <div class="divTableCell" style="text-align:center;width:400px;padding-bottom:15px !important;"> 
                    ';

                      $WH = DB::select("
                         SELECT * from db_pay_requisition_002
                         where db_pay_requisition_002.pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."' AND time_pay = ".$v->time_pay." AND product_id_fk=".$v->product_id_fk."
                      ");

                      foreach ($WH AS $v_wh) {

                          $zone = DB::select(" select * from zone where id=".$v_wh->zone_id_fk." ");
                          $shelf = DB::select(" select * from shelf where id=".$v_wh->shelf_id_fk." ");
                          if($v_wh->zone_id_fk!=''){
                            $sWarehouse = @$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.$v_wh->shelf_floor;
                            $lot_number = $v_wh->lot_number.' ['.$v_wh->lot_expired_date.']';
                          }else{
                            $sWarehouse = '<span style="width:200px;text-align:center;color:red;">*** ไม่มีสินค้าในคลัง ***</span>';
                            $lot_number = '';
                          }

                          $pn .=     
                              '<div class="divTableRow">
                              <div class="divTableCell" style="width:200px;text-align:center;">'.$sWarehouse.'</div>
                              <div class="divTableCell" style="width:200px;text-align:center;">'.$lot_number.'</div>
                              <div class="divTableCell" style="width:80px;text-align:center;color:blue;font-weight:bold;">'.($v_wh->amt_get>0?$v_wh->amt_get:'').'</div>
                              </div>
                              ';

                     }


            $pn .= '</div>';  
            $pn .= '</div>';  
          }

          $pn .= '</div>';  
          return $pn;
      })
      ->escapeColumns('column_002')  
      ->addColumn('ch_amt_remain', function($row) { 
        // ดูว่าไม่มีสินค้าค้างจ่ายแล้วใช่หรือไม่ 
        // Case ที่มีการบันทึกข้อมูลแล้ว
        // '3=สินค้าพอต่อการจ่ายครั้งนี้ 2=สินค้าไม่พอ มีบางรายการค้างจ่าย',
           @$rs_pay_history = DB::select(" SELECT id FROM `db_pay_requisition_002_pay_history` WHERE pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."' AND status in (2) ");

           if(count(@$rs_pay_history)>0){
               return 2;
           }else{
               return 3; // เบิกจากคลังมาไว้เตรียมแพ็คกล่องส่ง > จัดเตรียมสินค้าแล้ว
           }

       })
      ->addColumn('ch_amt_lot_wh', function($row) { 
// ดูว่าไม่มีสินค้าคลังเลย

          $Products = DB::select("
            SELECT * from db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."' 
            AND amt_remain > 0 GROUP BY product_id_fk ORDER BY time_pay DESC limit 1 ;
          ");
          // Case ที่มีการบันทึกข้อมูลแล้ว
              if(@$Products){

                    if(count($Products)>0){
                        $arr = [];
                        foreach ($Products as $key => $value) {
                          array_push($arr,$value->product_id_fk);
                        }
                        $arr_im = implode(',',$arr);
                        $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
                        $r = DB::select(" SELECT sum(amt) as sum FROM $temp_db_stocks WHERE product_id_fk in ($arr_im) ");
                        return @$r[0]->sum?@$r[0]->sum:0; 
                    }else{
                        // Case ที่ยังไม่มีการบันทึกข้อมูล ก็ให้ส่งค่า >0 ไปก่อน
                        return 1;
                    }

                }else{
                  // Case ที่ยังไม่มีการบันทึกข้อมูล ก็ให้ส่งค่า >0 ไปก่อน
                  return 1;
              }

       })      
       ->addColumn('column_003', function($row) { 

        if(!empty($row->time_pay)){

                $pn = '<div class="divTable">';
                $pn .= '<div class="divTableBody">';

                $pn .=     
                '<div class="divTableRow">
                 <div class="divTableCell" style="background-color:white !important;color:white !important;">ยกเลิก</div>
                 </div>
               ';

                $pn .=     
                '<div class="divTableRow">
                 <div class="divTableCell" >

                  <a href="javascript: void(0);" class="btn btn-sm btn-danger cDelete2 " data-toggle="tooltip" data-placement="top" title="ยกเลิกรายการจ่ายสินค้าครั้งล่าสุด" data-time_pay='.$row->time_pay.' ><i class="bx bx-trash font-size-16 align-middle"></i></a>

                 </div>
                 </div>
               ';

              $pn .= '</div>';
              $pn .= '</div>';

             return $pn;

           }

        })
        ->escapeColumns('column_003') 
        ->addColumn('column_004', function($row) { 
             $r = DB::select("SELECT time_pay from db_pay_requisition_002 where status_cancel<>1 ORDER BY id DESC LIMIT 1");
             if($r) return @$r[0]->time_pay;
        })
        ->addColumn('status_cancel', function($row) { 

          if($row->pick_pack_requisition_code_id_fk){

            $Products = DB::select(" SELECT status_cancel
            FROM
            db_pay_requisition_002 
            WHERE pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."' and time_pay=".$row->time_pay."  group by time_pay ");
            
            return @$Products[0]->status_cancel;
          }

        })        
      ->make(true);
    }



  public function warehouse_qr_0001(Request $reg){

      $sTable = DB::select(" SELECT * FROM `db_pay_requisition_001` WHERE `id`=".$reg->id." ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
          $d = DB::select(" SELECT * FROM `db_pick_pack_requisition_code` WHERE `id`=".$row->pick_pack_requisition_code_id_fk." ");
          return "<b>".$d[0]->requisition_code."</b>";
      })
      ->addColumn('column_002', function($row) {
          $d = DB::select(" SELECT * FROM `db_pick_pack_requisition_code` WHERE `id`=".$row->pick_pack_requisition_code_id_fk." ");
          return "<b>".$d[0]->pick_pack_packing_code."</b>";
      })
      ->escapeColumns('column_002')  
      ->addColumn('column_003', function($row) {
          $d = DB::select(" SELECT * FROM `db_pick_pack_requisition_code` WHERE `id`=".$row->pick_pack_requisition_code_id_fk." ");
          return "<b>".$d[0]->receipts."</b>";      })
      ->escapeColumns('column_003')        
      ->make(true);
    }


  public function warehouse_qr_0002(Request $reg){

      $sTable1 = DB::select(" SELECT * FROM `db_pick_pack_requisition_code` WHERE `id`=".$reg->id." ");
      $sTable = DB::select(" 
        SELECT db_pick_pack_packing_code.*,db_pick_pack_packing.packing_code FROM db_pick_pack_packing_code 
        JOIN db_pick_pack_packing  on db_pick_pack_packing.packing_code_id_fk=db_pick_pack_packing_code.id
        WHERE db_pick_pack_packing_code.id in (".$sTable1[0]->pick_pack_packing_code_id_fk.")
        GROUP BY db_pick_pack_packing_code.id 
      ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
         return '<b>'.$row->packing_code.'</b>';
      })
      ->escapeColumns('column_001')  
      ->addColumn('column_002', function($row) {

            $pn = '<div class="divTable"><div class="divTableBody">';
            $pn .=     
            '<div class="divTableRow">
            <div class="divTableCell" style="width:200px;font-weight:bold;">ชื่อสินค้า (เลขที่ใบเสร็จ)</div>
            <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวนที่สั่งซื้อ</div>
            <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วยนับ </div>
            <div class="divTableCell" style="width:300px;text-align:center;font-weight:bold;"> Scan Qr-code </div>
            </div>
            ';

                $Products = DB::select("
                SELECT 
                db_order_products_list.product_id_fk,
                db_order_products_list.product_name,
                db_order_products_list.product_unit_id_fk,
                SUM(db_order_products_list.amt) AS amt ,
                dataset_product_unit.product_unit
                from db_order_products_list
                LEFT Join dataset_product_unit ON db_order_products_list.product_unit_id_fk = dataset_product_unit.id 
                LEFT Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id 
                WHERE db_orders.id in  (".$row->orders_id_fk.")
                AND db_order_products_list.product_id_fk<>''
                GROUP BY db_order_products_list.product_id_fk
                ORDER BY db_order_products_list.product_id_fk ");

              $sum_amt = 0 ;

              if(@$Products){
            
              foreach ($Products as $key => $value) {

                $arr_inv = [];
                $arr_inv2 = [];
                if(@$row->orders_id_fk){
                $r_invoice_code = DB::select(" select db_orders.invoice_code,db_order_products_list.* FROM db_order_products_list
                LEFT JOIN db_orders on db_orders.id = db_order_products_list.frontstore_id_fk
                WHERE frontstore_id_fk in (".@$row->orders_id_fk.") AND db_order_products_list.product_id_fk in (".@$value->product_id_fk.") ");
                if(@$r_invoice_code){
                  foreach (@$r_invoice_code as $inv) {
                      array_push($arr_inv,@$inv->invoice_code);
                      array_push($arr_inv2,'"'.@$inv->invoice_code.'"');
                  }
                }

                $invoice_code = implode(",",$arr_inv);
                $invoice_code2 = implode(",",$arr_inv2);
                $sum_amt += $value->amt;
                $pn .=     
                '<div class="divTableRow">
                <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                '.@$value->product_name.'</b><br>('.@$invoice_code.')
                </div>
                <div class="divTableCell" style="text-align:center;">'.@$value->amt.'</div> 
                <div class="divTableCell" style="text-align:center;">'.@$value->product_unit.'</div> 
                <div class="divTableCell" style="text-align:left;"> ';

                $item_id = 1;
                $amt_scan = @$value->amt;

                for ($i=0; $i < $amt_scan ; $i++) { 

                $qr = DB::select(" select qr_code,updated_at from db_pick_warehouse_qrcode where item_id='".@$item_id."' and packing_code= ('".@$row->packing_code."') AND product_id_fk='".@$value->product_id_fk."' ");
                
                            if( (@$qr[0]->updated_at < date("Y-m-d") && !empty(@$qr[0]->qr_code)) ){

                              $pn .= 
                               '
                                <input type="text" style="width:122px;" value="'.@$qr[0]->qr_code.'" readonly > 
                                <i class="fa fa-times-circle fa-2 " aria-hidden="true" style="color:grey;" ></i> 
                               ';

                            }else{

                               $pn .= 
                               '
                                <input type="text" class="in-tx qr_scan " data-item_id="'.@$item_id.'" data-packing_code="'.@$row->packing_code.'" data-product_id_fk="'.$value->product_id_fk.'" placeholder="scan qr" style="width:122px;'.(empty(@$qr[0]->qr_code)?"background-color:blanchedalmond;":"").'" value="'.@$qr[0]->qr_code.'" > 
                                <i class="fa fa-times-circle fa-2 btnDeleteQrcodeProduct " aria-hidden="true" style="color:red;cursor:pointer;" data-item_id="'.@$item_id.'" data-packing_code="'.@$row->packing_code.'" data-product_id_fk="'.@$value->product_id_fk.'" ></i> 
                               ';

                            }

                              @$item_id++;
                              
                          }  

                $pn .= '</div>';  
                $pn .= '</div>';  
              
              }
              }
              }

              $pn .=     
              '<div class="divTableRow">
              <div class="divTableCell" style="text-align:right;font-weight:bold;"> รวม </div>
              <div class="divTableCell" style="text-align:center;font-weight:bold;">'.@$sum_amt.'</div>
              <div class="divTableCell" style="text-align:center;"> </div>
              <div class="divTableCell" style="text-align:center;"> </div>
              </div>
              ';

          $pn .= '</div>';  
          return $pn;
      })
      ->escapeColumns('column_002')  
      ->make(true);
    }


  public function warehouse_tb_000(Request $reg){

      $sTable = DB::select(" SELECT * FROM `db_pay_requisition_001` WHERE `id`=".$reg->id." ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
          $d = DB::select(" SELECT * FROM `db_pick_pack_requisition_code` WHERE `id`=".$row->pick_pack_requisition_code_id_fk." ");
          return "<b>".$d[0]->requisition_code."</b>";
      })
      ->addColumn('column_002', function($row) {
          $d = DB::select(" SELECT * FROM `db_pick_pack_requisition_code` WHERE `id`=".$row->pick_pack_requisition_code_id_fk." ");
          return "<b>".$d[0]->pick_pack_packing_code."</b>";
      })
      ->escapeColumns('column_002')  
      ->addColumn('column_003', function($row) {
          $d = DB::select(" SELECT * FROM `db_pick_pack_requisition_code` WHERE `id`=".$row->pick_pack_requisition_code_id_fk." ");
          return "<b>".$d[0]->receipts."</b>";      })
      ->escapeColumns('column_003')        
      ->make(true);
    }


  public function warehouse_tb_001(Request $reg){

      $sTable = DB::select(" SELECT * FROM `db_pick_pack_requisition_code` WHERE `id`=".$reg->id." ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_002', function($row) {

            $pn = '<div class="divTable"><div class="divTableBody">';
            $pn .=     
            '<div class="divTableRow">
            <div class="divTableCell" style="width:200px;font-weight:bold;">รายการสินค้า </div>
            <div class="divTableCell" style="width:90px;text-align:center;font-weight:bold;">จำนวนที่สั่งซื้อ</div>
            <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วยนับ </div>
            <div class="divTableCell" style="width:250px;text-align:left;font-weight:bold;"> รหัสใบเสร็จ </div>
            </div>
            ';

            $arr_pick_pack_packing_code_id_fk = explode(',',$row->pick_pack_packing_code_id_fk);

            $sTable1 = DB::select(" SELECT * FROM `db_pick_pack_packing_code` WHERE `id` in(".$row->pick_pack_packing_code_id_fk.") ");
            $arr1 = [];
            foreach ($sTable1 as $key => $v) {
              array_push($arr1,$v->orders_id_fk);
            }
            $orders_id_fk = implode(',',$arr1);
            // สินค้า รวมกัน ทั้งหมด ได้แก่  ซื้อปกติ ซื้อโปรโมชั่น ซื้อคูปอง เป็นต้น 
            $Products = DB::select("

                (
                SELECT 
                db_order_products_list.product_id_fk,
                db_order_products_list.product_name,
                SUM(db_order_products_list.amt) AS amt ,
                dataset_product_unit.product_unit,
                db_orders.code_order
                from db_order_products_list
                LEFT Join dataset_product_unit ON db_order_products_list.product_unit_id_fk = dataset_product_unit.id 
                LEFT Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id 
                WHERE db_orders.id in  ($orders_id_fk) AND add_from=1
                GROUP BY db_order_products_list.product_id_fk,db_order_products_list.type_product
                )
                UNION
                (
                SELECT
                promotions_products.product_id_fk,
                CONCAT(
                (SELECT product_code FROM products LEFT JOIN products_details on products.id=products_details.product_id_fk WHERE products.id=promotions_products.product_id_fk and lang_id=1 limit 1),' : ',
                (SELECT product_name FROM products_details WHERE product_id_fk=promotions_products.product_id_fk and lang_id=1 limit 1)) as product_name,
                sum(db_order_products_list.amt*promotions_products.product_amt) as amt ,
                dataset_product_unit.product_unit,
                db_orders.code_order
                FROM
                db_order_products_list
                Left Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
                Left Join promotions ON db_order_products_list.promotion_id_fk = promotions.id
                Inner Join promotions_products ON promotions.id = promotions_products.promotion_id_fk
                Left Join dataset_product_unit ON promotions_products.product_unit = dataset_product_unit.id
                WHERE db_orders.id in ($orders_id_fk) AND add_from=2
                GROUP BY promotions_products.product_id_fk,db_order_products_list.type_product
                )

                ORDER BY product_name

             ");

              $sum_amt = 0 ;

              foreach ($Products as $key => $value) {

                $sum_amt += $value->amt;
        
                $pn .=     
                '<div class="divTableRow">
                <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                '.$value->product_name.'</b>
                </div>
                <div class="divTableCell" style="text-align:center;">'.$value->amt.'</div> 
                <div class="divTableCell" style="text-align:center;">'.$value->product_unit.'</div> 
                <div class="divTableCell" style="text-align:left;">'.$value->code_order.'</div> 
                ';

                $pn .= '</div>';  
              
              }

              $pn .=     
              '<div class="divTableRow">
              <div class="divTableCell" style="text-align:right;font-weight:bold;"> รวม </div>
              <div class="divTableCell" style="text-align:center;font-weight:bold;">'.$sum_amt.'</div>
              <div class="divTableCell" style="text-align:center;"> </div>
              <div class="divTableCell" style="text-align:center;"> </div>
              </div>
              ';

          $pn .= '</div>';  
          return $pn;


      })
      ->escapeColumns('column_002')  
      ->make(true);
    }



  public function warehouse_address_sent(Request $reg){

    //  $sTable = DB::select(" SELECT * FROM `db_consignments` WHERE `packing_code`=".$reg->packing_id." ");
      $sTable = DB::select(" SELECT * FROM `db_consignments`  ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // ->addColumn('column_001', function($row) {

      //   return "P2".sprintf("%05d",$row->packing_code);
      // })
      ->addColumn('column_002', function($row) {
        
          return $row->recipient_name." > ".$row->address;

        // return "ชื่อและที่อยู่ลูกค้า";
           // @$addr = DB::select(" SELECT
           //                            customers_detail.customer_id,
           //                            customers_detail.house_no,
           //                            customers_detail.house_name,
           //                            customers_detail.moo,
           //                            customers_detail.zipcode,
           //                            customers_detail.soi,
           //                            customers_detail.amphures_id_fk,
           //                            customers_detail.district_id_fk,
           //                            customers_detail.road,
           //                            customers_detail.province_id_fk,
           //                            customers.prefix_name,
           //                            customers.first_name,
           //                            customers.last_name,
           //                            dataset_provinces.name_th AS provname,
           //                            dataset_amphures.name_th AS ampname,
           //                            dataset_districts.name_th AS tamname
           //                            FROM
           //                            customers_detail
           //                            Left Join customers ON customers_detail.customer_id = customers.id
           //                            Left Join dataset_provinces ON customers_detail.province_id_fk = dataset_provinces.id
           //                            Left Join dataset_amphures ON customers_detail.amphures_id_fk = dataset_amphures.id
           //                            Left Join dataset_districts ON customers_detail.district_id_fk = dataset_districts.id
           //                            WHERE customers_detail.customer_id =8");

           //      if(sizeof(@$addr)>0){

           //                      @$address = "";
           //                      @$address .= "ชื่อผู้รับ : ". @$addr[0]->prefix_name.@$addr[0]->first_name." ".@$addr[0]->last_name;
           //                      @$address .= "<br>ที่อยู่ : ". @$addr[0]->house_no. " ". @$addr[0]->house_name. " ";
           //                      @$address .= " ต. ". @$addr[0]->tamname;
           //                      @$address .= " อ. ". @$addr[0]->ampname;
           //                      @$address .= " จ. ". @$addr[0]->provname;
           //                      @$address .= " รหัส ปณ. ". @$addr[0]->zipcode ;

           //                    }else{
           //                      @$address = '-ไม่พบข้อมูล-';
           //                    }
           //  return @$address; 

      })
      ->escapeColumns('column_002')  
      ->addColumn('column_003', function($row) {
          // $d = DB::select(" SELECT * FROM `db_orders` WHERE `invoice_code`='".$row->recipient_code."' ");
          // return $d[0]->id?$d[0]->id:1;      
        })
      ->escapeColumns('column_003')        
      ->make(true);
    }



}
