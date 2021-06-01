<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;

class Pick_warehouseController extends Controller
{

    public function index(Request $request)
    {
      // dd($request);
      // $sTable = \App\Models\Backend\Pick_warehouse_fifo::get();
    //  DB::select(" DROP TABLE IF EXISTS TEMP_db_products_fifo_bill; ");
      // DB::select(" TRUNCATE TEMP_db_products_fifo_bill; ");

     //  DB::select("
     //    CREATE TEMPORARY TABLE `TEMP_db_products_fifo_bill` (
     //      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
     //      `consignment_no` varchar(255) DEFAULT NULL,
     //      `customer_ref_no` varchar(255) DEFAULT NULL,
     //      `sender_code` varchar(255) DEFAULT NULL,
     //      `recipient_code` varchar(255) DEFAULT NULL,
     //      `recipient_name` varchar(255) DEFAULT NULL,
     //      `address` varchar(255) DEFAULT NULL,
     //      `postcode` varchar(255) DEFAULT NULL,
     //      `mobile` varchar(255) DEFAULT NULL,
     //      `contact_person` varchar(255) DEFAULT NULL,
     //      `phone_no` varchar(255) DEFAULT NULL,
     //      `email` varchar(255) DEFAULT NULL,
     //      `declare_value` varchar(255) DEFAULT NULL,
     //      `cod_amount` varchar(255) DEFAULT NULL,
     //      `remark` varchar(255) DEFAULT NULL,
     //      `total_box` varchar(255) DEFAULT NULL,
     //      `sat_del` varchar(255) DEFAULT NULL,
     //      `hrc` varchar(255) DEFAULT NULL,
     //      `invr` varchar(255) DEFAULT NULL,
     //      `service_code` varchar(255) DEFAULT NULL,
     //      `created_at` timestamp NULL DEFAULT NULL,
     //      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     //      `deleted_at` timestamp NULL DEFAULT NULL,
     //      PRIMARY KEY (`id`)
     //    ) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8
     // ");

     // DB::select(" INSERT INTO `temp_db_products_fifo_bill` (`consignment_no`) VALUES ('tttttttttt'); ");
     // $TEMP_db_products_fifo_bill = DB::select(" SELECT * FROM TEMP_db_products_fifo_bill; ");
     // dd($TEMP_db_products_fifo_bill);

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

      $Customer = DB::select(" select * from customers ");
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

      $r = DB::select("SELECT * FROM db_pay_requisition_001 where id =$id ");
      $sRow = \App\Models\Backend\Pick_packPackingCode::find($r[0]->pick_pack_packing_code_id_fk);
      // dd($sRow);

      $sBusiness_location = \App\Models\Backend\Business_location::get();

       $Customer = DB::select(" select * from customers ");
      return View('backend.pick_warehouse.form')->with(
        array(
           'packing_id'=>$sRow->id,
           'Customer'=>$Customer,
           'sBusiness_location'=>$sBusiness_location,
        ) );
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

      $sTable = DB::select(" SELECT * FROM db_pay_requisition_001  WHERE  pick_pack_packing_code_id_fk='".$req->packing_id."' group by time_pay order By time_pay ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) { 
        // group by time_pay order by time_pay asc 

            $rs = DB::select(" SELECT
                  db_pay_requisition_002_pay_history.id,
                  db_pay_requisition_002_pay_history.time_pay,
                  db_pay_requisition_002_pay_history.pick_pack_packing_code_id_fk,
                  DATE(db_pay_requisition_002_pay_history.pay_date) as pay_date,
                  db_pay_requisition_002_pay_history.`status`,
                  ck_users_admin.`name` as pay_user
                  FROM
                  db_pay_requisition_002_pay_history
                  Left Join ck_users_admin ON db_pay_requisition_002_pay_history.pay_user = ck_users_admin.id  WHERE pick_pack_packing_code_id_fk='".$row->pick_pack_packing_code_id_fk."' and time_pay=".$row->time_pay." group by time_pay order By time_pay ");

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
            WHERE pick_pack_packing_code_id_fk='".$row->pick_pack_packing_code_id_fk."' and time_pay=".$row->time_pay." group by time_pay,product_id_fk ");

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
                         where db_pay_requisition_002.pick_pack_packing_code_id_fk='".$row->pick_pack_packing_code_id_fk."' AND time_pay = ".$v->time_pay." AND product_id_fk=".$v->product_id_fk."
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

           $rs_pay_history = DB::select(" SELECT id FROM `db_pay_requisition_002_pay_history` WHERE pick_pack_packing_code_id_fk='".$row->pick_pack_packing_code_id_fk."' AND status in (2) ");

           if(count($rs_pay_history)>0){
               return 2;
           }else{
               return 3;
           }

          // $r_check_remain = DB::select(" SELECT status FROM `db_pay_requisition_002_pay_history` WHERE pick_pack_packing_code_id_fk='".$row->pick_pack_packing_code_id_fk."' ORDER BY time_pay DESC LIMIT 1  ");
          // if($r_check_remain){
          //     return $r_check_remain[0]->status; 
          // }else{
          // // Case ที่ยังไม่มีการบันทึกข้อมูล ก็ให้ส่งค่า 3 ไปก่อน
          //     return 3;
          // }

       })
      ->addColumn('ch_amt_lot_wh', function($row) { 
// ดูว่าไม่มีสินค้าคลังเลย

          $Products = DB::select("
            SELECT * from db_pay_requisition_002 WHERE pick_pack_packing_code_id_fk='".$row->pick_pack_packing_code_id_fk."' AND amt_remain > 0 GROUP BY product_id_fk 
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

        })
        ->escapeColumns('column_003') 
        ->addColumn('column_004', function($row) { 
             $r = DB::select("SELECT time_pay from db_pay_requisition_002 where status_cancel<>1 ORDER BY id DESC LIMIT 1");
             return @$r[0]->time_pay;
        })
        ->addColumn('status_cancel', function($row) { 
            $Products = DB::select(" SELECT status_cancel
            FROM
            db_pay_requisition_002 
            WHERE pick_pack_packing_code_id_fk='".$row->pick_pack_packing_code_id_fk."' and time_pay=".$row->time_pay."  group by time_pay ");
            
            return @$Products[0]->status_cancel;

        })        
      ->make(true);
    }




}
