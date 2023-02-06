<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;
use Auth;
use Session;

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

    public function show(Request $request)
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

    public function store(Request $request)
    {
        // dd($request->all());
        return $this->form();

    }

    public function edit($id)
    {
      $r = DB::select("SELECT * FROM db_pay_requisition_001 where id in($id) ");
      if(@$r[0]->status_sent==6){
        return redirect()->to(url("backend/pick_warehouse/".$id."/cancel"));
      }
      // $sRow = \App\Models\Backend\Pick_packPackingCode::find($r[0]->pick_pack_requisition_code_id_fk);
      $sRow = \App\Models\Backend\Pick_packPackingCode::find($id);
//   SELECT customer_id,from_table FROM `customers_addr_sent` WHERE packing_code=".$r[0]->pick_pack_requisition_code_id_fk."
        $addr_sent =  DB::select("

            SELECT customer_id,from_table FROM `customers_addr_sent` WHERE packing_code=".$id."
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
           // 'pick_pack_requisition_code_id_fk'=>$r[0]->pick_pack_requisition_code_id_fk,'sUser'=>$sUser,'user_address'=>@$address,'id'=>$id,
           'pick_pack_requisition_code_id_fk'=>$id,'sUser'=>$sUser,'user_address'=>@$address,'id'=>$id,'sRow'=> $sRow,
        ) );
    }

    public function edit_product($id)
    {
      $r = DB::select("SELECT * FROM db_pay_requisition_001 where id in($id) ");
      if(@$r[0]->status_sent==6){
        return redirect()->to(url("backend/pick_warehouse/".$id."/cancel"));
      }
      // $sRow = \App\Models\Backend\Pick_packPackingCode::find($r[0]->pick_pack_requisition_code_id_fk);
      $sRow = \App\Models\Backend\Pick_packPackingCode::find($id);
//   SELECT customer_id,from_table FROM `customers_addr_sent` WHERE packing_code=".$r[0]->pick_pack_requisition_code_id_fk."
        $addr_sent =  DB::select("

            SELECT customer_id,from_table FROM `customers_addr_sent` WHERE packing_code=".$id."
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

        $Products = DB::select("
        SELECT products.id as product_id,
        products.product_code,
        (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
        FROM
        products_details
        Left Join products ON products_details.product_id_fk = products.id
        WHERE lang_id=1 AND status = 1
        order by products.product_code
      ");

      return View('backend.pick_warehouse.form_edit_product')->with(
        array(
           // 'pick_pack_requisition_code_id_fk'=>$r[0]->pick_pack_requisition_code_id_fk,'sUser'=>$sUser,'user_address'=>@$address,'id'=>$id,
           'pick_pack_requisition_code_id_fk'=>$id,'sUser'=>$sUser,'user_address'=>@$address,'id'=>$id,'sRow'=> $sRow, 'Products' => $Products,
        ) );
    }


    public function delete_test($id)
    {
          $db_pay_requisition_002 = DB::table('db_pay_requisition_002')->select('id')->where('pick_pack_requisition_code_id_fk',15)->get();
          foreach( $db_pay_requisition_002 as $data ){
              $item_data = DB::table('db_pay_requisition_002_item')->select('id')->where('requisition_002_id',$data->id)->where('pick_pack_requisition_code_id_fk',15)->first();
          }
    }


    public function qr($id)
    {
      // return $id;
      // $r1 = DB::select(" SELECT * FROM db_pick_pack_packing_code where id=$id ");
      // dd($r1);

      $r = DB::select("SELECT db_pay_requisition_001.*,db_pick_pack_requisition_code.requisition_code FROM db_pay_requisition_001 LEFT Join db_pick_pack_requisition_code ON db_pay_requisition_001.pick_pack_requisition_code_id_fk = db_pick_pack_requisition_code.id
      where db_pay_requisition_001.pick_pack_requisition_code_id_fk =$id ");
      $sRow = \App\Models\Backend\Pick_packPackingCode::find($r[0]->pick_pack_requisition_code_id_fk);
      // dd($sRow);

      	 // วุฒิเพิ่มมา เอาไว้ตรวจว่าสินค้าไหนจ่ายแล้วของบิลไหน
		//  $db_pay_requisition_002_datas = DB::table('db_pay_requisition_002')
		//  ->where('pick_pack_requisition_code_id_fk',$r[0]->pick_pack_requisition_code_id_fk)->get();
		//  foreach($db_pay_requisition_002_datas as $data_002){
		// 			   $product_amt_get = $data_002->amt_get;
		// 			   $db_pick_pack_packing_code_data = DB::table('db_pick_pack_packing_code')->select('id','orders_id_fk')->where('id',$r[0]->pick_pack_requisition_code_id_fk)->first();
		// 			   $arr_order = explode(',',$db_pick_pack_packing_code_data->orders_id_fk);

		// 			   $promotions_products_arr = DB::table('promotions_products')->select('promotion_id_fk')
		// 			   ->where('product_id_fk',$data_002->product_id_fk)->pluck('promotion_id_fk')->toArray();
		// 			   $db_order_products_list_data1 = DB::table('db_order_products_list')
		// 			   ->select('frontstore_id_fk')
		// 			   ->whereIn('frontstore_id_fk',$arr_order)
		// 			   ->where('type_product','product')
		// 			   ->where('product_id_fk',$data_002->product_id_fk);


		// 			   $db_order_products_list_data2 = DB::table('db_order_products_list')
		// 			   ->select('frontstore_id_fk')
		// 			   ->whereIn('frontstore_id_fk',$arr_order)
		// 			   ->where('type_product','promotion')
		// 			   ->whereIn('promotion_id_fk',$promotions_products_arr)
		// 			   ->union($db_order_products_list_data1)
		// 			   ->orderBy('frontstore_id_fk','asc')
		// 			   ->get();

		// 			   foreach($db_order_products_list_data2 as $data2){

		// 					 $order_product1 = DB::table('db_order_products_list')
		// 					 ->where('frontstore_id_fk',$data2->frontstore_id_fk)
		// 					 ->where('product_id_fk',$data_002->product_id_fk)
		// 					 ->where('type_product','product')
		// 					 ->get();
		// 					 $order_product2 = DB::table('db_order_products_list')
		// 					 ->where('frontstore_id_fk',$data2->frontstore_id_fk)
		// 					 ->whereIn('promotion_id_fk',$promotions_products_arr)
		// 					 ->where('type_product','promotion')
		// 					 ->get();
		// 					 $sum_want_amt = 0;
		// 					 foreach($order_product1 as $order_product1_data){
		// 					   $sum_want_amt+=$order_product1_data->amt;
		// 					 }

		// 					 foreach($order_product2 as $order_product2_data){
		// 					   $pro = DB::table('promotions_products')->where('promotion_id_fk',$order_product2_data->promotion_id_fk)->get();
		// 					   foreach($pro as $p){
		// 							 if($p->product_id_fk == $data_002->product_id_fk){
		// 							   $sum_want_amt+=($order_product2_data->amt*$p->product_amt);
		// 							 }
		// 					   }

		// 					 }

		// 					 $amt_get_data = 0;
		// 					 $amt_remain_data = 0;
		// 					 // จำนวนที่ต้องการหักจากที่ได้รับ

    //           //  if($data2->frontstore_id_fk == 523){
    //           //   // dd($amt_get_data);
    //           //   // dd($data_002);
    //           //   // dd($data_002->amt_get);
    //           //   dd($sum_want_amt.' < '.$product_amt_get);
    //           //                  }

    //           // if($data_002->product_id_fk==42){
    //           //   dd($sum_want_amt.' < '.$product_amt_get);
    //           //   dd($product_amt_get);
    //           // }

              // $check = DB::table('db_pay_requisition_002_item')
              // ->where('pick_pack_requisition_code_id_fk',$data_002->pick_pack_requisition_code_id_fk)
              // ->where('product_id_fk',$data_002->product_id_fk)
              // ->where('order_id',$data2->frontstore_id_fk)
              // ->orderBy('id','desc')->first();

              // if($check){

              //   $sum_want_amt = $check->amt_remain;

              //   if($sum_want_amt <= $product_amt_get){
              //     $product_amt_get = $product_amt_get - $sum_want_amt;
              //     $amt_get_data = $sum_want_amt;
              //     $amt_remain_data = 0;
              //   }else{
              //     $amt_get_data = $product_amt_get;
              //     $amt_remain_data = $sum_want_amt - $product_amt_get;
              //     $product_amt_get = 0;
              //   }

              // }else{
              //   if($sum_want_amt <= $product_amt_get){
              //     $product_amt_get = $product_amt_get - $sum_want_amt;
              //     $amt_get_data = $sum_want_amt;
              //     $amt_remain_data = 0;
              //   }else{
              //     $amt_get_data = $product_amt_get;
              //     $amt_remain_data = $sum_want_amt - $product_amt_get;
              //     $product_amt_get = 0;
              //   }

              // }

		// 						 DB::table('db_pay_requisition_002_item')->insert([
		// 						   'requisition_002_id' => $data_002->id,
    //                'pick_pack_requisition_code_id_fk' => $data_002->pick_pack_requisition_code_id_fk,
		// 						   'order_id' => $data2->frontstore_id_fk,
		// 						   'product_id_fk' => $data_002->product_id_fk,
		// 						   'amt_need' => $sum_want_amt,
		// 						   'amt_get' => $amt_get_data,
		// 						   'amt_remain' => $amt_remain_data,
		// 						   'created_at' => date('Y:m:d H:i:s'),
		// 						   'updated_at' => date('Y:m:d H:i:s'),
		// 						   'delete_status' => 3,
		// 						 ]);

		// 			   }
		//  }


      $requisition_code = $r[0]->requisition_code;

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
            -- customers.user_name AS user_code,
            -- CONCAT(customers.prefix_name,customers.first_name,' ',customers.last_name) AS user_name,
            dataset_pay_product_status.txt_desc as bill_status
            FROM
            db_pay_requisition_001
            Left Join dataset_pay_product_status ON db_pay_requisition_001.status_sent = dataset_pay_product_status.id
            where db_pay_requisition_001.pick_pack_requisition_code_id_fk = '$id'
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
           'sRow' => @$sRow,
        ) );
      // return View('backend.pick_warehouse.qr');

    }

    public function qr1($id)
    {
          // return $id;

      // $r1 = DB::select(" SELECT * FROM db_pick_pack_packing_code where id=$id ");
      // dd($r1);


      $r = DB::select("SELECT db_pay_requisition_001.*,db_pick_pack_requisition_code.requisition_code FROM db_pay_requisition_001 LEFT Join db_pick_pack_requisition_code ON db_pay_requisition_001.pick_pack_requisition_code_id_fk = db_pick_pack_requisition_code.id
      where db_pay_requisition_001.pick_pack_requisition_code_id_fk =$id ");
      $sRow = \App\Models\Backend\Pick_packPackingCode::find($r[0]->pick_pack_requisition_code_id_fk);

      // dd($sRow);
      $requisition_code = $r[0]->requisition_code;

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
            -- customers.user_name AS user_code,
            -- CONCAT(customers.prefix_name,customers.first_name,' ',customers.last_name) AS user_name,
            dataset_pay_product_status.txt_desc as bill_status
            FROM
            db_pay_requisition_001
            Left Join dataset_pay_product_status ON db_pay_requisition_001.status_sent = dataset_pay_product_status.id
            where db_pay_requisition_001.pick_pack_requisition_code_id_fk = '$id'
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

      return View('backend.pick_warehouse.qr1')->with(
        array(
           'packing_id'=>@$sRow->id,'sUser'=>$sUser,'user_address'=>@$address,'id'=>@$id,'requisition_code'=>@$requisition_code,
           'can_cancel_packing_sent'=>@$can_cancel_packing_sent,
        ) );
      // return View('backend.pick_warehouse.qr');

    }


    public function cancel($id)
    {
          // return $id;
      $r = DB::select("SELECT * FROM db_pay_requisition_001 where id in($id) ");

      $sRow = \App\Models\Backend\Pick_packPackingCode::find($id);

//   SELECT customer_id,from_table FROM `customers_addr_sent` WHERE packing_code=".$r[0]->pick_pack_requisition_code_id_fk."
        $addr_sent =  DB::select("

            SELECT customer_id,from_table FROM `customers_addr_sent` WHERE packing_code=".$id."
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
            Left Join customers ON 8 = customers.id
            Left Join dataset_pay_product_status ON db_pay_requisition_001.status_sent = dataset_pay_product_status.id
            where db_pay_requisition_001.pick_pack_requisition_code_id_fk in ($id)
        ");

      // วุฒิเพิ่มมา เบิกจ่ายสินค้าใบเบิกใหม่
      $remain_status = DB::table('db_pay_requisition_002')->select('id','amt_remain','product_id_fk')->where('pick_pack_requisition_code_id_fk',$id)->orderBy('time_pay','desc')->get();
      $arr_remain = [];
      foreach($remain_status as $rem){
          if(!isset($arr_remain[$rem->product_id_fk])){
            $arr_remain[$rem->product_id_fk] = $rem->amt_remain;
          }
      }
      $remain_check = 0;
      foreach($arr_remain as $in => $rem){
        $remain_check += $rem;
      }
      if($remain_check>0){
        $remain_status = 1;
      }else{
        $remain_status = 0;
      }
      // ---

      return View('backend.pick_warehouse.cancel')->with(
        array(
           // 'pick_pack_requisition_code_id_fk'=>$r[0]->pick_pack_requisition_code_id_fk,'sUser'=>$sUser,'user_address'=>@$address,'id'=>$id,
           'pick_pack_requisition_code_id_fk'=>$id,'sUser'=>$sUser,'user_address'=>@$address,'id'=>$id, 'sRow' => $sRow, 'remain_status' => $remain_status,
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

      $sTable = DB::select(" SELECT * FROM db_pay_requisition_001  WHERE  pick_pack_requisition_code_id_fk='".$req->packing_id."'
        group by time_pay order By time_pay ");
      $sQuery = \DataTables::of($sTable);

      return $sQuery
      ->addColumn('column_001', function($row) {

            // วุฒิเปลี่ยน pick_pack_requisition_code_id_fk เป็น pick_pack_packing_code_id_fk
            $rs = DB::select(" SELECT
                  db_pay_requisition_002_pay_history.id,
                  db_pay_requisition_002_pay_history.time_pay,
                  db_pay_requisition_002_pay_history.pick_pack_requisition_code_id_fk,
                  DATE(db_pay_requisition_002_pay_history.pay_date) as pay_date,
                  db_pay_requisition_002_pay_history.`status`,
                  ck_users_admin.`name` as pay_user
                  FROM
                  db_pay_requisition_002_pay_history
                  Left Join ck_users_admin ON db_pay_requisition_002_pay_history.pay_user = ck_users_admin.id  WHERE pick_pack_packing_code_id_fk='".$row->pick_pack_requisition_code_id_fk."' and time_pay=".$row->time_pay." group by time_pay order By time_pay ");

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
        //  dd($Products );
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

          // วุฒืแก้เป็น pick_pack_packing_code_id_fk
          //  @$rs_pay_history = DB::select(" SELECT id FROM `db_pay_requisition_002_pay_history` WHERE pick_pack_requisition_code_id_fk='".$row->pick_pack_requisition_code_id_fk."' AND status in (2) ");
          @$rs_pay_history = DB::select(" SELECT id FROM `db_pay_requisition_002_pay_history` WHERE pick_pack_packing_code_id_fk='".$row->pick_pack_requisition_code_id_fk."' AND status in (2) ");
// dd($rs_pay_history);
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

                  <a href="javascript: void(0);" class="btn btn-sm btn-danger cDelete2 " data-toggle="tooltip" data-placement="left" title="ยกเลิกรายการจ่ายสินค้าครั้งล่าสุด" data-time_pay='.$row->time_pay.' style="display:none;"  ><i class="bx bx-trash font-size-16 align-middle"></i></a>

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

      // $sTable = DB::select(" SELECT * FROM `db_pick_pack_packing` WHERE packing_code_id_fk =".$reg->id." GROUP BY packing_code  ");
      // $sTable = DB::select(" SELECT * FROM db_pick_pack_packing_code where id=".$reg->id."  ");
      $sTable = DB::table('db_pick_pack_packing_code')
      // ->join('db_orders','db_pick_pack_packing_code.receipt','=','db_orders.code_order')
      // ->select('db_pick_pack_packing_code.*','db_orders.id as order_id')
      ->where('db_pick_pack_packing_code.id',$reg->id)->get();

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
           $DP = DB::table('db_pick_pack_packing')->select('packing_code')->where('packing_code_id_fk',$row->id)->first();
           return '<b>'.@$DP->packing_code.'</b>';
      })
      ->escapeColumns('column_001')
      ->addColumn('column_002', function($row) {
        if($row->bill_remain_status==1){
          $DP = DB::table('db_pick_pack_packing')->select('id','delivery_id_fk')->where('packing_code_id_fk',$row->id)->where('no_product',0)->get();
        }else{
          $DP = DB::table('db_pick_pack_packing')->select('id','delivery_id_fk')->where('packing_code_id_fk',$row->id)->get();
        }

        $order_code_arr = explode(',',$row->receipt);
        if(!empty($DP)){

          $pn = '<div class="divTable"><div class="divTableBody">';
          $arr = [];

          foreach ($DP as $key => $value) {
             $delivery = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();

              if($delivery[0]->status_scan_wh==1){
                $color = 'style="color:green;"';
              }else{
                $color = '';
              }
             if($delivery[0]->status_pack==1){
              $d1 = DB::select(" SELECT delivery_id_fk FROM `db_delivery_packing` where packing_code_id_fk in (".$delivery[0]->packing_code.")  ");
              $arr1 = [];
              foreach ($d1 as $key => $v1) {
                array_push( $arr1 ,$v1->delivery_id_fk);
              }
              $rs1 = implode(',',$arr1);
              $d2 = DB::select(" SELECT receipt FROM `db_delivery` where id in (".$rs1.")  ");
              $arr2 = [];
              foreach ($d2 as $key => $v2) {

                    array_push( $arr2 ,$v2->receipt);

              }
              $rs2 = implode(',',$arr2);
              $pn .=
                '<div class="divTableRow">
                 <div class="divTableCell" style="text-align:left;font-weight:bold;"> - <a href="javaScript:;" delivery_id="'.$delivery[0]->id.'" '.$color.' class="pack_modal" data_id="'.$value->id.'">'.@$delivery[0]->packing_code_desc.' (Packing list) : '.$rs2.' </a> </div>
                 </div>
              ';
             }else{

                $pn .=
                '<div class="divTableRow">
                 <div class="divTableCell" style="text-align:left;font-weight:bold;"> - <a href="javaScript:;" delivery_id="'.$delivery[0]->id.'" '.$color.' class="single_modal" data_id="'.$value->id.'">'.@$delivery[0]->receipt.' </a> </div>
                 </div>
              ';

             }


          }
             $pn .= '</div>';
           return $pn;
        }else{
          return '-';
        }

      })
      ->escapeColumns('column_002')
      ->addColumn('column_003', function($row) {

        $DP = DB::table('db_pick_pack_packing')->select('delivery_id_fk')->where('packing_code_id_fk',$row->id)->get();
        if(!empty($DP)){

          $pn = '<div class="divTable"><div class="divTableBody">';
          $arr = [];
          foreach ($DP as $key => $value) {
      // dd($row);
              $pn .=
                '<center> <a href="backend/pick_warehouse/print_requisition_detail/'.$value->delivery_id_fk.'/'.$row->id.'" title="พิมพ์ใบเบิก" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a>


                ';
                // <a href="javascript: void(0);" target=_blank data-id="'+$row->order_id+'" class="print02" > <i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#669999;"></i></a>
          }
          // <a href="javascript: void(0);" target=_blank data-id="'.$row->order_id.'" title="พิมพ์ใบเสร็จ" class="print_slip" > <i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#669999;"></i></a>
             $pn .= '</div>';
           return $pn;
        }else{
          return '-';
        }

        })

        // ->addColumn('p_weight',function($row){
          // $DP = DB::table('db_pick_pack_packing')->where('packing_code_id_fk',$row->id)->get();
          // $pn = '';
          // if(!empty($DP)){
          //   foreach ($DP as $key => $value) {
          //     $pn .= '<input type="number" class="p_amt_box" data-id="'.@$value->id.'" box-id="0"  type="text" value="'.@$value->p_amt_box.'"><br>';
          //   }

          //   return $pn;
          // }else{
          //   return '-';
          // }
        // })


      ->escapeColumns('column_003')
      ->make(true);
    }


  public function warehouse_qr_0002(Request $reg){
// pick_pack_packing_code_id_fk
    // Scan Qr-code
$sTable = DB::select("

SELECT
db_pick_pack_packing.id,
db_pick_pack_packing.p_size,
db_pick_pack_packing.p_weight,
db_pick_pack_packing.p_amt_box,
db_pick_pack_packing.packing_code_id_fk as packing_code_id_fk,
db_pick_pack_packing.packing_code as packing_code,
CASE WHEN db_delivery_packing.packing_code is not null THEN concat(db_delivery_packing.packing_code,' (packing)') ELSE db_delivery.receipt END as lists ,
CASE WHEN db_delivery_packing.packing_code is not null THEN 'packing' ELSE 'no_packing' END as remark,
db_delivery.id as db_delivery_id,
db_delivery.packing_code as db_delivery_packing_code
FROM `db_pick_pack_packing`
LEFT JOIN db_delivery on db_delivery.id=db_pick_pack_packing.delivery_id_fk
LEFT JOIN db_delivery_packing on db_delivery_packing.delivery_id_fk=db_delivery.id
WHERE

db_pick_pack_packing.packing_code_id_fk =".$reg->id."

AND db_delivery_packing.packing_code is not null

ORDER BY db_pick_pack_packing.id

");
// dd($sTable);
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
        if(@$row->lists){
          return '<b>'.$row->lists.'</b>' ;
        }else{
          return "-";
        }
      })
      ->escapeColumns('column_001')
      ->addColumn('column_002', function($row) {

            $pn = '<div class="divTable"><div class="divTableBody">';
            // $pn .=
            // '<div class="divTableRow">
            // <div class="divTableCell" style="width:200px;font-weight:bold;">รหัส : ชื่อสินค้า (เลขที่ใบเสร็จ)</div>
            // <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
            // <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วย </div>
            // <div class="divTableCell" style="width:300px;text-align:center;font-weight:bold;"> Scan Qr-code </div>
            // </div>
            // ';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="width:200px;font-weight:bold;">รหัส : ชื่อสินค้า (เลขที่ใบเสร็จ)</div>
            <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
            <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วย </div>
            </div>
            ';


              // ต้องรวมสินค้า โปรโมชั่น ด้วย

          $Products = DB::select("

(SELECT

db_delivery.orders_id_fk,
db_order_products_list.product_id_fk,
db_order_products_list.product_name,
db_order_products_list.product_unit_id_fk,
SUM(db_order_products_list.amt) AS amt ,
dataset_product_unit.product_unit,
db_orders.code_order,
db_orders.id as orders_id_fk

FROM `db_order_products_list`
left Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
left Join db_delivery ON db_delivery.orders_id_fk = db_orders.id
left Join db_delivery_packing ON db_delivery_packing.delivery_id_fk = db_delivery.id
LEFT Join dataset_product_unit ON db_order_products_list.product_unit_id_fk = dataset_product_unit.id

WHERE type_product='product' AND db_delivery_packing.packing_code_id_fk = '".$row->db_delivery_packing_code."'

GROUP BY db_order_products_list.product_id_fk
)

UNION

(
SELECT

db_delivery.orders_id_fk,
db_order_products_list.product_id_fk,
db_order_products_list.product_name,
db_order_products_list.product_unit_id_fk,
SUM(db_order_products_list.amt) AS amt ,
dataset_product_unit.product_unit,
db_orders.code_order,
db_orders.id as orders_id_fk

FROM `db_order_products_list`
left Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
left Join db_delivery ON db_delivery.orders_id_fk = db_orders.id
left Join db_delivery_packing ON db_delivery_packing.delivery_id_fk = db_delivery.id
LEFT Join dataset_product_unit ON db_order_products_list.product_unit_id_fk = dataset_product_unit.id

WHERE type_product='promotion' AND db_delivery_packing.packing_code_id_fk = '".$row->db_delivery_packing_code."'

GROUP BY db_order_products_list.product_id_fk
)


                 ");

              $sum_amt = 0 ;
              $r_ch_t = '';

         if(@$Products){
              foreach ($Products as $key => $value) {
                // วุฒิเพิ่มมาเช็คยอดที่โดนตัดแล้ว
                $pro_check = DB::table('db_pay_requisition_002')->where('pick_pack_requisition_code_id_fk',$row->packing_code_id_fk)->where('product_id_fk',$value->product_id_fk)->first();
                $c_amt_get = 0;
                if($pro_check){
                  $c_amt_get = $pro_check->amt_get;
                }
              // วุฒิเพิ่ม if มาเช็ค
                if($value->product_id_fk!=null){

                // หา max time_pay ก่อน
                 $r_ch01 = DB::select("SELECT time_pay FROM `db_pay_requisition_002_pay_history` where product_id_fk in(".$value->product_id_fk.") AND  pick_pack_packing_code_id_fk=".$row->packing_code_id_fk." order by time_pay desc limit 1  ");

                 // Check ว่ามี status=2 ? (ค้างจ่าย)
              // .$value->product_id_fk.
                 $r_ch02 = DB::select("SELECT * FROM `db_pay_requisition_002_pay_history` where product_id_fk in(".$value->product_id_fk.") AND  pick_pack_packing_code_id_fk=".$row->packing_code_id_fk." and time_pay=".$r_ch01[0]->time_pay." and status=2 ");

                 if(count($r_ch02)>0){
                    $r_ch_t = '(รายการนี้ค้างจ่ายในรอบนี้ สินค้าในคลังมีไม่เพียงพอ)';
                 }else{
                   $r_ch_t = '';
                 }

                  // บิลปกติ
                $arr_inv = [];
                $p1 = DB::select(" select db_orders.code_order FROM db_order_products_list
                LEFT JOIN db_orders on db_orders.id = db_order_products_list.frontstore_id_fk
                left Join db_delivery ON db_delivery.orders_id_fk = db_orders.id
                left Join db_delivery_packing ON db_delivery_packing.delivery_id_fk = db_delivery.id
                WHERE db_order_products_list.product_id_fk in (".($value->product_id_fk?$value->product_id_fk:0).")  AND type_product='product'  AND db_delivery_packing.packing_code_id_fk = '".$row->db_delivery_packing_code."' ");
                if(@$p1){
                  foreach (@$p1 as $inv) {
                      array_push($arr_inv,@$inv->code_order);
                  }
                }

                // บิลโปร
                $p2 = DB::select("
                select db_orders.code_order
                FROM `promotions_products`
                LEFT JOIN db_order_products_list on db_order_products_list.promotion_id_fk=promotions_products.promotion_id_fk
                LEFT Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
                left Join db_delivery ON db_delivery.orders_id_fk = db_orders.id
                left Join db_delivery_packing ON db_delivery_packing.delivery_id_fk = db_delivery.id
                WHERE db_order_products_list.product_id_fk in (".($value->product_id_fk?$value->product_id_fk:0).")  AND type_product='promotion'  AND db_delivery_packing.packing_code_id_fk = '".$row->db_delivery_packing_code."' ");
                if(@$p2){
                  foreach (@$p2 as $inv) {
                      array_push($arr_inv,@$inv->code_order);
                  }
                }

                $invoice_code = implode(",",$arr_inv);

                // วุฒิแก้
                // $sum_amt += $value->amt;
                $sum_amt += $c_amt_get;
                $pn .=
                '<div class="divTableRow">
                <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                '.@$value->product_name.'</b><br>
                ('.@$invoice_code.')<br>
                <font color=red>'.$r_ch_t.'</font>
                </div>
                <div class="divTableCell" style="text-align:center;">'.@$c_amt_get.'</div>
                <div class="divTableCell" style="text-align:center;">'.@$value->product_unit.'</div>
                <div class="divTableCell" style="text-align:left;"> ';

                $item_id = 1;
                //  $amt_scan = @$value->amt;
                $amt_scan = @$c_amt_get;

                // for ($i=0; $i < $amt_scan ; $i++) {

                // $qr = DB::select(" select qr_code,updated_at from db_pick_warehouse_qrcode where item_id='".@$item_id."' and packing_code= ('".@$row->packing_code."') AND product_id_fk='".@$value->product_id_fk."' ");

                //             if( (@$qr[0]->updated_at < date("Y-m-d") && !empty(@$qr[0]->qr_code)) ){

                //               $pn .=
                //                '
                //                 <input type="text" style="width:122px;" value="'.@$qr[0]->qr_code.'" readonly >
                //                 <i class="fa fa-times-circle fa-2 " aria-hidden="true" style="color:grey;" ></i>
                //                ';

                //             }else{

                //                $pn .=
                //                '
                //                 <input type="text" class="in-tx qr_scan " data-item_id="'.@$item_id.'" data-packing_code="'.@$row->packing_code.'" data-product_id_fk="'.$value->product_id_fk.'" placeholder="scan qr" style="width:122px;'.(empty(@$qr[0]->qr_code)?"background-color:blanchedalmond;":"").'" value="'.@$qr[0]->qr_code.'" >
                //                 <i class="fa fa-times-circle fa-2 btnDeleteQrcodeProduct " aria-hidden="true" style="color:red;cursor:pointer;" data-item_id="'.@$item_id.'" data-packing_code="'.@$row->packing_code.'" data-product_id_fk="'.@$value->product_id_fk.'" ></i>
                //                ';

                //             }

                //               @$item_id++;

                //           }

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
      ->addColumn('column_003', function($row) {

        $sBox = DB::table('db_pick_pack_boxsize')->where('pick_pack_packing_id_fk',@$row->id)->where('deleted_at',null)->get();
          $pn = '<div class="divTable"><div class="divTableBody">';
          $pn .=
          '<div class="divTableRow">
          <div class="divTableCell" style="width:90px;text-align:center;font-weight:bold;"> ข้อมูลอื่นๆ </div>
          </div>
          ';
          if($sBox->count()==0){
              DB::table('db_pick_pack_boxsize')->insert([
                  'pick_pack_packing_id_fk' => @$row->id,
                  'created_at' => date('Y-m-d H:i:s'),
              ]);
          }
          $sBox = DB::table('db_pick_pack_boxsize')->where('pick_pack_packing_id_fk',@$row->id)->where('deleted_at',null)->get();
          foreach($sBox as $key1 => $box){
            $pn .= '<div class="divTableRow row_0002" data-bill-type="warehouse_qr_0002">
            <div class="divTableCell" style="text-align:left;">
             <b>ขนาด <br>
             <input class="p_size" data-id="'.@$row->id.'" box-id="'.@$box->id.'" type="text" value="'.@$box->p_size.'"><br>
             น้ำหนัก <br>
             <input class="p_weight" data-id="'.@$row->id.'" box-id="'.@$box->id.'"  type="text" value="'.@$box->p_weight.'"><br>

             <button type="button" class="btn btn-primary btn-sm btn_0002_add" data-bill-type="warehouse_qr_0002">+ เพิ่มกล่อง</button>
             <button type="button" class="btn btn-danger btn-sm btn_0002_remove" data-bill-type="warehouse_qr_0002">- ลบกล่อง</button>
            </div>
            </div>';
          }
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="text-align:center;"> </div>
            <div class="divTableCell" style="text-align:center;"> </div>
            </div>
            ';

        $pn .= '</div>';
        return $pn;
    })
      ->escapeColumns('column_003')

      ->make(true);
    }


    // วุฒิเพิ่มมา สำหรับสแกนราย order
    public function warehouse_qr_0002_pack_scan(Request $reg){

      // Scan Qr-code
  $sTable = DB::select("

  SELECT
  db_pick_pack_packing.id,
  db_pick_pack_packing.p_size,
  db_pick_pack_packing.p_weight,
  db_pick_pack_packing.p_amt_box,
  db_pick_pack_packing.packing_code_id_fk as packing_code_id_fk,
  db_pick_pack_packing.packing_code as packing_code,
  db_delivery.user_scan,
  db_delivery.status_scan_wh,
  CASE WHEN db_delivery_packing.packing_code is not null THEN concat(db_delivery_packing.packing_code,' (packing)') ELSE db_delivery.receipt END as lists ,
  CASE WHEN db_delivery_packing.packing_code is not null THEN 'packing' ELSE 'no_packing' END as remark,
  db_delivery.id as db_delivery_id,
  db_delivery.packing_code as db_delivery_packing_code
  FROM `db_pick_pack_packing`
  LEFT JOIN db_delivery on db_delivery.id=db_pick_pack_packing.delivery_id_fk
  LEFT JOIN db_delivery_packing on db_delivery_packing.delivery_id_fk=db_delivery.id
  WHERE
  db_pick_pack_packing.delivery_id_fk =".$reg->delivery_id."
  AND
  db_pick_pack_packing.packing_code_id_fk =".$reg->id."

  AND db_delivery_packing.packing_code is not null

  ORDER BY db_pick_pack_packing.id

  ");

        $r_delivery_id = $reg->delivery_id;
        // dd($sTable);
        $sQuery = \DataTables::of($sTable);
        return $sQuery
        ->addColumn('column_001', function($row) use($r_delivery_id){

          $customer = DB::table('db_delivery')->select('recipient_name')->where('id',$row->db_delivery_id)->first();
          $user_name = "<br> บันทึกสแกน : ";
          if($row->user_scan!=0 && $row->user_scan!=''){
            $user_admin = DB::table('ck_users_admin')->where('id',$row->user_scan)->first();
            if($user_admin){
              $user_name .= 'คุณ '.$user_admin->name;
            }
          }
                           $sum_amt = 0 ;
                           $r_ch_t = '';

                          //  วุฒิเพิ่มมา
                          $d1 = DB::select(" SELECT * from db_delivery WHERE id=".$r_delivery_id."");
                          $d2 = DB::select(" SELECT * from db_delivery WHERE packing_code=".$d1[0]->packing_code."");
                           $receipt = "";
                           foreach ($d2 as $key => $d) {
                            $receipt .= $d->receipt.',';
                          }
                          $fid_arr = explode(',',$receipt);
                          $order_arr = DB::table('db_orders')->select('id')->whereIn('code_order',$fid_arr)->pluck('id')->toArray();
                          $Products = DB::table('db_pay_requisition_002')
                          ->select('db_pay_requisition_002.product_name','db_pay_requisition_002.product_unit','db_pay_requisition_002_item.*','db_orders.code_order')
                          ->join('db_pay_requisition_002_item','db_pay_requisition_002_item.requisition_002_id','db_pay_requisition_002.id')
                          ->join('db_orders','db_orders.id','db_pay_requisition_002_item.order_id')
                          ->where('db_pay_requisition_002.pick_pack_requisition_code_id_fk',$row->packing_code_id_fk)
                          ->whereIn('db_pay_requisition_002_item.order_id',$order_arr)
                          // เพิ่มมา
                          ->groupBy('db_orders.code_order')
                          ->get();

                   if(@$Products){
                        foreach ($Products as $key => $value) {
                            $r_ch_t .= ($key+1).')  '.$value->code_order.'<br>';
                      }}

        if(@$row->lists){
          return '<b>'.$row->lists.'</b><br><b>ผู้รับ : '.$customer->recipient_name.$user_name .'</b><br><br>'.$r_ch_t;
        }else{
          return "-";
        }

        })
        ->escapeColumns('column_001')
        ->addColumn('column_002', function($row)  use($r_delivery_id){

              $pn = '<div class="divTable"><div class="divTableBody">';
              $pn .=
              '<div class="divTableRow">
              <div class="divTableCell" style="width:200px;font-weight:bold;">รหัส : ชื่อสินค้า (เลขที่ใบเสร็จ)</div>
              <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
              <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วย </div>
              <div class="divTableCell" style="width:300px;text-align:center;font-weight:bold;"> Scan Qr-code </div>
              </div>
              ';

                // ต้องรวมสินค้า โปรโมชั่น ด้วย

                $sum_amt = 0 ;
                $r_ch_t = '';

                  //  วุฒิเพิ่มมา
                  $d1 = DB::select(" SELECT * from db_delivery WHERE id=".$r_delivery_id."");
                  $d2 = DB::select(" SELECT * from db_delivery WHERE packing_code=".$d1[0]->packing_code."");
                   $receipt = "";
                   foreach ($d2 as $key => $d) {
                    $receipt .= $d->receipt.',';
                  }
                 $fid_arr = explode(',',$receipt);
                 $order_arr = DB::table('db_orders')->select('id')->whereIn('code_order',$fid_arr)->pluck('id')->toArray();
                //  dd( $order_arr);
                 $Products = DB::table('db_pay_requisition_002')
                 ->select('db_pay_requisition_002.product_name',
                 'db_pay_requisition_002.product_unit',
                 'db_pay_requisition_002_item.*',
                 'db_orders.code_order',
                 DB::raw('SUM(db_pay_requisition_002_item.amt_get) AS amt_get')
                 )
                 ->join('db_pay_requisition_002_item','db_pay_requisition_002_item.requisition_002_id','db_pay_requisition_002.id')
                 ->join('db_orders','db_orders.id','db_pay_requisition_002_item.order_id')
                 ->where('db_pay_requisition_002.pick_pack_requisition_code_id_fk',$row->packing_code_id_fk)
                 ->whereIn('db_pay_requisition_002_item.order_id',$order_arr)
                  // เพิ่มมา
                  ->groupBy('db_pay_requisition_002_item.product_id_fk')
                 ->get();
                  // dd(@$Products);
           if(@$Products){
                foreach ($Products as $key => $value) {

                  // วุฒิเพิ่มมาเช็ค if
                  if($value->product_id_fk!=null){

                  // หา max time_pay ก่อน
                   $r_ch01 = DB::select("SELECT time_pay FROM `db_pay_requisition_002_pay_history` where product_id_fk in(".$value->product_id_fk.") AND  pick_pack_packing_code_id_fk=".$row->packing_code_id_fk." order by time_pay desc limit 1  ");
                // Check ว่ามี status=2 ? (ค้างจ่าย)
                   $r_ch02 = DB::select("SELECT * FROM `db_pay_requisition_002_pay_history` where product_id_fk in(".$value->product_id_fk.") AND  pick_pack_packing_code_id_fk=".$row->packing_code_id_fk." and time_pay=".$r_ch01[0]->time_pay." and status=2 ");
                  //  if(count($r_ch02)>0){
                  //     $r_ch_t = '(รายการนี้ค้างจ่ายในรอบนี้ สินค้าในคลังมีไม่เพียงพอ)';
                  //  }else{
                  //    $r_ch_t = '';
                  //  }

                  //  วุฒิเพิ่มมา
                   $db_pay_requisition_002 = DB::table('db_pay_requisition_002')
                   ->where('product_id_fk',$value->product_id_fk)
                   ->where('pick_pack_requisition_code_id_fk',$row->packing_code_id_fk)
                   ->orderBy('time_pay', 'desc')
                   ->first();

                  $db_pay_requisition_002_item = DB::table('db_pay_requisition_002_item')
                  ->select(DB::raw('SUM(amt_get) AS amt_get'),'amt_need',DB::raw('SUM(amt_remain) AS amt_remain'))
                  ->where('product_id_fk',$value->product_id_fk)
                  // ->where('order_id',$value->order_id)
                  ->whereIn('order_id',$order_arr)
                  // ->where('requisition_002_id',@$db_pay_requisition_002->id)
                  ->where('pick_pack_requisition_code_id_fk',@$db_pay_requisition_002->pick_pack_requisition_code_id_fk)
                  ->groupBy('product_id_fk')
                  ->first();

                    // if($db_pay_requisition_002_item->amt_remain > 0){
                    //   $r_ch_t = '&nbsp;<span style="font:15px;color:red;">(รายการนี้ค้างจ่าย จำนวน '.$db_pay_requisition_002_item->amt_remain.' )</span>';
                    // }else{
                    //   $r_ch_t = '';
                    // }

                    if(($db_pay_requisition_002_item->amt_need - $db_pay_requisition_002_item->amt_get) > 0 ){
                      $r_ch_t = '&nbsp;<span style="font:15px;color:red;">(รายการนี้ค้างจ่าย จำนวน '.($db_pay_requisition_002_item->amt_need - $db_pay_requisition_002_item->amt_get).' )</span>';
                    }else{
                      $r_ch_t = '';
                    }



                  // $sum_amt += $value->amt_get;
                  $sum_amt += $db_pay_requisition_002_item->amt_get;
                  $amt_get = $db_pay_requisition_002_item->amt_get;
                  $pn .=
                  '<div class="divTableRow">
                  <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                  '.@$value->product_name.'</b><br>
                  <font color=red>'.$r_ch_t.'</font>
                    </div>
                  <div class="divTableCell" style="text-align:center;">'.@$amt_get.'</div>
                  <div class="divTableCell" style="text-align:center;">'.@$value->product_unit.'</div>
                  <div class="divTableCell" style="text-align:left;"> ';

                  $item_id = 1;
                  $amt_scan = @$value->amt_get;

                  if($key==0){
                                // for ($i=0; $i < $amt_scan ; $i++) { บันทึกสแกน

                                  $qr = DB::select(" select qr_code,updated_at from db_pick_warehouse_qrcode where item_id='".@$item_id."' and invoice_code='".$value->code_order."' and packing_code= ('".@$row->packing_code."') AND product_id_fk='".@$value->product_id_fk."' ");

                                  // if( (@$qr[0]->updated_at < date("Y-m-d") && !empty(@$qr[0]->qr_code)) ){

                                  //   $pn .=
                                  //    '
                                  //     <input type="text" style="width:122px;" value="'.@$qr[0]->qr_code.'" readonly >
                                  //     <i class="fa fa-times-circle fa-2 " aria-hidden="true" style="color:grey;" ></i>
                                  //    ';

                                  // }else{

                                    $pn .=
                                    '
                                      <input type="text" class="in-tx qr_scan " data-item_id="'.@$item_id.'" packing_list="'.$row->lists.'" invoice_code="'.$value->code_order.'" data-packing_code="'.@$row->packing_code.'" data-product_id_fk="'.$value->product_id_fk.'" placeholder="scan qr" style="width:122px;" value="" >

                                    ';
                                    // <i class="fa fa-times-circle fa-2 btnDeleteQrcodeProduct " aria-hidden="true" style="color:red;cursor:pointer;" data-item_id="'.@$item_id.'" data-packing_code="'.@$row->packing_code.'" data-product_id_fk="'.@$value->product_id_fk.'" ></i>

                                    //  วุฒิเพิ่มมา
                                    $pn .=
                                    '  <a href="'.url('backend/qr_show/'.$value->code_order.'/'.$row->packing_code.'/'.$value->product_id_fk.'/'.$row->lists).'" target="bank" class="qr_scan_show" data-item_id="'.@$item_id.'" invoice_code="'.$value->code_order.'" data-packing_code="'.@$row->packing_code.'" data-product_id_fk="'.$value->product_id_fk.'"><u>แสดง</u></a>
                                    <br>
                                    ';
                                    $pn .= '<label class="last_scan" style="color:red;"></label>';
                                  // }

                                //     @$item_id++;

                                // }
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
        ->addColumn('column_003', function($row) {

          $sBox = DB::table('db_pick_pack_boxsize')->where('pick_pack_packing_id_fk',@$row->id)->where('deleted_at',null)->get();
            $pn = '<div class="divTable"><div class="divTableBody">';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="width:90px;text-align:center;font-weight:bold;"> ข้อมูลอื่นๆ </div>
            </div>
            ';
            if($sBox->count()==0){
                DB::table('db_pick_pack_boxsize')->insert([
                    'pick_pack_packing_id_fk' => @$row->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            if($row->status_scan_wh == 1){
              $readonly = "readonly";
            }else{
              $readonly = "";
            }

            $sBox = DB::table('db_pick_pack_boxsize')->where('pick_pack_packing_id_fk',@$row->id)->where('deleted_at',null)->get();
            foreach($sBox as $key1 => $box){
              $pn .= '<div class="divTableRow row_0002" data-bill-type="warehouse_qr_0002">
              <div class="divTableCell" style="text-align:left;">
               <b>ขนาด <br>
               <input class="p_size" data-id="'.@$row->id.'" '.$readonly.' box-id="'.@$box->id.'" type="text" value="'.@$box->p_size.'"><br>
               น้ำหนัก <br>
                <input class="p_weight" data-id="'.@$row->id.'" '.$readonly.' box-id="'.@$box->id.'"  type="text" value="'.@$box->p_weight.'"><br>

               <button type="button" class="btn btn-primary btn-sm btn_0002_add" data-bill-type="warehouse_qr_0002">+ เพิ่มกล่อง</button>
               <button type="button" class="btn btn-danger btn-sm btn_0002_remove" data-bill-type="warehouse_qr_0002">- ลบกล่อง</button>
              </div>
              </div>';
            }
              $pn .=
              '<div class="divTableRow">
              <div class="divTableCell" style="text-align:center;"> </div>
              <div class="divTableCell" style="text-align:center;"> </div>
              </div>
              ';

          $pn .= '</div>';
          return $pn;
      })
        ->escapeColumns('column_003')

        ->make(true);
      }



  public function warehouse_qr_00022(Request $reg){



$sTable = DB::select("

SELECT
db_pick_pack_packing.id,
db_pick_pack_packing.p_size,
db_pick_pack_packing.p_weight,
db_pick_pack_packing.p_amt_box,
db_pick_pack_packing.packing_code_id_fk as packing_code_id_fk,
db_pick_pack_packing.packing_code as packing_code,
CASE WHEN db_delivery_packing.packing_code is not null THEN concat(db_delivery_packing.packing_code,' (packing)') ELSE db_delivery.receipt END as lists ,
CASE WHEN db_delivery_packing.packing_code is not null THEN 'packing' ELSE 'no_packing' END as remark,
db_delivery.id as db_delivery_id,
db_delivery.packing_code as db_delivery_packing_code
FROM `db_pick_pack_packing`
LEFT JOIN db_delivery on db_delivery.id=db_pick_pack_packing.delivery_id_fk
LEFT JOIN db_delivery_packing on db_delivery_packing.delivery_id_fk=db_delivery.id
WHERE

db_pick_pack_packing.packing_code_id_fk =".$reg->id."

AND db_delivery_packing.packing_code is null

ORDER BY db_pick_pack_packing.id

");




      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
        if(@$row->lists){
          return '<b>'.$row->lists.'</b>' ;
        }else{
          return "-";
        }
      })
      ->escapeColumns('column_001')
      ->addColumn('column_002', function($row) {



            $pn = '<div class="divTable"><div class="divTableBody">';
            // $pn .=
            // '<div class="divTableRow">
            // <div class="divTableCell" style="width:200px;font-weight:bold;">รหัส : ชื่อสินค้า</div>
            // <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
            // <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วย </div>
            // <div class="divTableCell" style="width:300px;text-align:center;font-weight:bold;"> Scan Qr-code </div>
            // </div>
            // ';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="width:200px;font-weight:bold;">รหัส : ชื่อสินค้า</div>
            <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
            <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วย </div>
            </div>
            ';


              // ต้องรวมสินค้า โปรโมชั่น ด้วย

           $Products = DB::select("

                SELECT
                db_pay_requisition_002.id,
                db_pay_requisition_002.time_pay,
                db_pay_requisition_002.business_location_id_fk,
                db_pay_requisition_002.branch_id_fk,
                db_pay_requisition_002.pick_pack_requisition_code_id_fk,
                db_pay_requisition_002.customers_id_fk,
                db_pay_requisition_002.product_id_fk,
                db_pay_requisition_002.product_name,
                db_pay_requisition_002.amt_need,
                db_pay_requisition_002.amt_get as amt,
                db_pay_requisition_002.amt_lot,
                db_pay_requisition_002.amt_remain,
                db_pay_requisition_002.product_unit_id_fk,
                db_pay_requisition_002.product_unit,
                db_pay_requisition_002.lot_number,
                db_pay_requisition_002.lot_expired_date,
                db_pay_requisition_002.warehouse_id_fk,
                db_pay_requisition_002.zone_id_fk,
                db_pay_requisition_002.shelf_id_fk,
                db_pay_requisition_002.shelf_floor,
                db_pay_requisition_002.status_cancel,
                db_pay_requisition_002.created_at,
                db_pay_requisition_002.updated_at,
                db_pay_requisition_002.deleted_at
                FROM `db_pay_requisition_002`
                WHERE pick_pack_requisition_code_id_fk='".@$row->packing_code_id_fk."'



                 ");

              $sum_amt = 0 ;
              $r_ch_t = '';

         if(@$Products){

              foreach ($Products as $key => $value) {

                if(!empty($value->product_id_fk)){

                // หา max time_pay ก่อน
                 $r_ch01 = DB::select("SELECT time_pay FROM `db_pay_requisition_002_pay_history` where product_id_fk in(".$value->product_id_fk.") AND  pick_pack_packing_code_id_fk=".$row->packing_code_id_fk." order by time_pay desc limit 1  ");
              // Check ว่ามี status=2 ? (ค้างจ่าย)
                 $r_ch02 = DB::select("SELECT * FROM `db_pay_requisition_002_pay_history` where product_id_fk in(".$value->product_id_fk.") AND  pick_pack_packing_code_id_fk=".$row->packing_code_id_fk." and time_pay=".$r_ch01[0]->time_pay." and status=2 ");
                 if(count($r_ch02)>0){
                    $r_ch_t = '(รายการนี้ค้างจ่ายในรอบนี้ สินค้าในคลังมีไม่เพียงพอ)';
                 }else{
                   $r_ch_t = '';
                 }


                $sum_amt += $value->amt;
                $pn .=
                '<div class="divTableRow">
                <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                '.@$value->product_name.'</b><br>
                <font color=red>'.$r_ch_t.'</font>
                </div>
                <div class="divTableCell" style="text-align:center;">'.@$value->amt.'</div>
                <div class="divTableCell" style="text-align:center;">'.@$value->product_unit.'</div>
                <div class="divTableCell" style="text-align:left;"> ';

                $item_id = 1;
                $amt_scan = @$value->amt;

                // for ($i=0; $i < $amt_scan ; $i++) {

                // $qr = DB::select(" select qr_code,updated_at from db_pick_warehouse_qrcode where item_id='".@$item_id."' and packing_code= ('".@$row->packing_code."') AND product_id_fk='".@$value->product_id_fk."' ");

                //             if( (@$qr[0]->updated_at < date("Y-m-d") && !empty(@$qr[0]->qr_code)) ){

                //               $pn .=
                //                '
                //                 <input type="text" style="width:122px;" value="'.@$qr[0]->qr_code.'" readonly >
                //                 <i class="fa fa-times-circle fa-2 " aria-hidden="true" style="color:grey;" ></i>
                //                ';

                //             }else{

                //                $pn .=
                //                '
                //                 <input type="text" class="in-tx qr_scan " data-item_id="'.@$item_id.'" data-packing_code="'.@$row->packing_code.'" data-product_id_fk="'.$value->product_id_fk.'" placeholder="scan qr" style="width:122px;'.(empty(@$qr[0]->qr_code)?"background-color:blanchedalmond;":"").'" value="'.@$qr[0]->qr_code.'" >
                //                 <i class="fa fa-times-circle fa-2 btnDeleteQrcodeProduct " aria-hidden="true" style="color:red;cursor:pointer;" data-item_id="'.@$item_id.'" data-packing_code="'.@$row->packing_code.'" data-product_id_fk="'.@$value->product_id_fk.'" ></i>
                //                ';

                //             }

                //               @$item_id++;

                //           }

                $pn .= '</div>';
                $pn .= '</div>';

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
        }
      })
      ->escapeColumns('column_002')
      ->addColumn('column_003', function($row) {

        $sBox = DB::table('db_pick_pack_boxsize')->where('pick_pack_packing_id_fk',@$row->id)->where('deleted_at',null)->get();
        $pn = '<div class="divTable"><div class="divTableBody">';
        $pn .=
        '<div class="divTableRow">
        <div class="divTableCell" style="width:90px;text-align:center;font-weight:bold;"> ข้อมูลอื่นๆ </div>
        </div>
        ';
        if($sBox->count()==0){
            DB::table('db_pick_pack_boxsize')->insert([
                'pick_pack_packing_id_fk' => @$row->id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        $sBox = DB::table('db_pick_pack_boxsize')->where('pick_pack_packing_id_fk',@$row->id)->where('deleted_at',null)->get();
        foreach($sBox as $key1 => $box){
          $pn .= '<div class="divTableRow row_0002" data-bill-type="warehouse_qr_0002">
          <div class="divTableCell" style="text-align:left;">
           <b>ขนาด <br>
           <input class="p_size" data-id="'.@$row->id.'" box-id="'.@$box->id.'" type="text" value="'.@$box->p_size.'"><br>
           น้ำหนัก <br>
            <input class="p_weight" data-id="'.@$row->id.'" box-id="'.@$box->id.'"  type="text" value="'.@$box->p_weight.'"><br>

           <button type="button" class="btn btn-primary btn-sm btn_0002_add" data-bill-type="warehouse_qr_0002">+ เพิ่มกล่อง</button>
           <button type="button" class="btn btn-danger btn-sm btn_0002_remove" data-bill-type="warehouse_qr_0002">- ลบกล่อง</button>
          </div>
          </div>';
        }

              $pn .=
              '<div class="divTableRow">
              <div class="divTableCell" style="text-align:center;"> </div>
              <div class="divTableCell" style="text-align:center;"> </div>
              </div>
              ';

          $pn .= '</div>';
          return $pn;
      })


      ->escapeColumns('column_003')

      ->make(true);
    }

    public function warehouse_qr_00022_single_scan(Request $reg){
      // Scan Qr-code
      $sTable = DB::select("

      SELECT
      db_pick_pack_packing.id,
      db_pick_pack_packing.p_size,
      db_pick_pack_packing.p_weight,
      db_pick_pack_packing.p_amt_box,
      db_pick_pack_packing.packing_code_id_fk as packing_code_id_fk,
      db_pick_pack_packing.packing_code as packing_code,
      db_delivery.user_scan,
      CASE WHEN db_delivery_packing.packing_code is not null THEN concat(db_delivery_packing.packing_code,' (packing)') ELSE db_delivery.receipt END as lists ,
      CASE WHEN db_delivery_packing.packing_code is not null THEN 'packing' ELSE 'no_packing' END as remark,
      db_delivery.id as db_delivery_id,
      db_delivery.packing_code as db_delivery_packing_code
      FROM `db_pick_pack_packing`
      LEFT JOIN db_delivery on db_delivery.id=db_pick_pack_packing.delivery_id_fk
      LEFT JOIN db_delivery_packing on db_delivery_packing.delivery_id_fk=db_delivery.id
      WHERE

      db_pick_pack_packing.packing_code_id_fk =".$reg->id."

      AND db_delivery_packing.packing_code is null

      ORDER BY db_pick_pack_packing.id

      ");




            $sQuery = \DataTables::of($sTable);
            return $sQuery
            ->addColumn('column_001', function($row) {
              $customer = DB::table('db_delivery')->select('recipient_name')->where('id',$row->db_delivery_id)->first();
              $user_name = "<br> บันทึกสแกน : ";
              if($row->user_scan!=0 && $row->user_scan!=''){
                $user_admin = DB::table('ck_users_admin')->where('id',$row->user_scan)->first();
                if($user_admin){
                  $user_name .= 'คุณ '.$user_admin->name;
                }
              }

              if(@$row->lists){
                return '<b>'.$row->lists.'</b><br><b>ผู้รับ : '.$customer->recipient_name.$user_name ;
              }else{
                return "-";
              }
            })
            ->escapeColumns('column_001')
            ->addColumn('column_002', function($row) {



                  $pn = '<div class="divTable"><div class="divTableBody">';
                  // $pn .=
                  // '<div class="divTableRow">
                  // <div class="divTableCell" style="width:200px;font-weight:bold;">รหัส : ชื่อสินค้า</div>
                  // <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
                  // <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วย </div>
                  // <div class="divTableCell" style="width:300px;text-align:center;font-weight:bold;"> Scan Qr-code </div>
                  // </div>
                  // ';
                  $pn .=
                  '<div class="divTableRow">
                  <div class="divTableCell" style="width:200px;font-weight:bold;">รหัส : ชื่อสินค้า</div>
                  <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
                  <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วย </div>
                  <div class="divTableCell" style="width:300px;text-align:center;font-weight:bold;"> Scan Qr-code </div>
                  </div>
                  ';


                    // ต้องรวมสินค้า โปรโมชั่น ด้วย

                 $Products = DB::select("

                      SELECT
                      db_pay_requisition_002.id,
                      db_pay_requisition_002.time_pay,
                      db_pay_requisition_002.business_location_id_fk,
                      db_pay_requisition_002.branch_id_fk,
                      db_pay_requisition_002.pick_pack_requisition_code_id_fk,
                      db_pay_requisition_002.customers_id_fk,
                      db_pay_requisition_002.product_id_fk,
                      db_pay_requisition_002.product_name,
                      db_pay_requisition_002.amt_need,
                      db_pay_requisition_002.amt_get as amt,
                      db_pay_requisition_002.amt_lot,
                      db_pay_requisition_002.amt_remain,
                      db_pay_requisition_002.product_unit_id_fk,
                      db_pay_requisition_002.product_unit,
                      db_pay_requisition_002.lot_number,
                      db_pay_requisition_002.lot_expired_date,
                      db_pay_requisition_002.warehouse_id_fk,
                      db_pay_requisition_002.zone_id_fk,
                      db_pay_requisition_002.shelf_id_fk,
                      db_pay_requisition_002.shelf_floor,
                      db_pay_requisition_002.status_cancel,
                      db_pay_requisition_002.created_at,
                      db_pay_requisition_002.updated_at,
                      db_pay_requisition_002.deleted_at
                      FROM `db_pay_requisition_002`
                      WHERE pick_pack_requisition_code_id_fk='".@$row->packing_code_id_fk."'



                       ");

                    $sum_amt = 0 ;
                    $r_ch_t = '';

               if(@$Products){

                    foreach ($Products as $key => $value) {

                      if(!empty($value->product_id_fk)){

                      // หา max time_pay ก่อน
                       $r_ch01 = DB::select("SELECT time_pay FROM `db_pay_requisition_002_pay_history` where product_id_fk in(".$value->product_id_fk.") AND  pick_pack_packing_code_id_fk=".$row->packing_code_id_fk." order by time_pay desc limit 1  ");
                    // Check ว่ามี status=2 ? (ค้างจ่าย)
                       $r_ch02 = DB::select("SELECT * FROM `db_pay_requisition_002_pay_history` where product_id_fk in(".$value->product_id_fk.") AND  pick_pack_packing_code_id_fk=".$row->packing_code_id_fk." and time_pay=".$r_ch01[0]->time_pay." and status=2 ");
                       if(count($r_ch02)>0){
                          $r_ch_t = '(รายการนี้ค้างจ่ายในรอบนี้ สินค้าในคลังมีไม่เพียงพอ)';
                       }else{
                         $r_ch_t = '';
                       }


                      $sum_amt += $value->amt;
                      $pn .=
                      '<div class="divTableRow">
                      <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                      '.@$value->product_name.'</b><br>
                      <font color=red>'.$r_ch_t.'</font>
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
              }
            })
            ->escapeColumns('column_002')
            ->addColumn('column_003', function($row) {

              $sBox = DB::table('db_pick_pack_boxsize')->where('pick_pack_packing_id_fk',@$row->id)->where('deleted_at',null)->get();
              $pn = '<div class="divTable"><div class="divTableBody">';
              $pn .=
              '<div class="divTableRow">
              <div class="divTableCell" style="width:90px;text-align:center;font-weight:bold;"> ข้อมูลอื่นๆ </div>
              </div>
              ';
              if($sBox->count()==0){
                  DB::table('db_pick_pack_boxsize')->insert([
                      'pick_pack_packing_id_fk' => @$row->id,
                      'created_at' => date('Y-m-d H:i:s'),
                  ]);
              }
              $sBox = DB::table('db_pick_pack_boxsize')->where('pick_pack_packing_id_fk',@$row->id)->where('deleted_at',null)->get();
              foreach($sBox as $key1 => $box){
                $pn .= '<div class="divTableRow row_0002" data-bill-type="warehouse_qr_0002">
                <div class="divTableCell" style="text-align:left;">
                 <b>ขนาด <br>
                 <input class="p_size" data-id="'.@$row->id.'" box-id="'.@$box->id.'" type="text" value="'.@$box->p_size.'"><br>
                 น้ำหนัก <br>
                  <input class="p_weight" data-id="'.@$row->id.'" box-id="'.@$box->id.'"  type="text" value="'.@$box->p_weight.'"><br>

                 <button type="button" class="btn btn-primary btn-sm btn_0002_add" data-bill-type="warehouse_qr_0002">+ เพิ่มกล่อง</button>
                 <button type="button" class="btn btn-danger btn-sm btn_0002_remove" data-bill-type="warehouse_qr_0002">- ลบกล่อง</button>
                </div>
                </div>';
              }

                    $pn .=
                    '<div class="divTableRow">
                    <div class="divTableCell" style="text-align:center;"> </div>
                    <div class="divTableCell" style="text-align:center;"> </div>
                    </div>
                    ';

                $pn .= '</div>';
                return $pn;
            })
            ->escapeColumns('column_003')

            ->make(true);
          }

  public function warehouse_tb_000(Request $reg){

      $sTable = DB::select(" SELECT * FROM `db_pay_requisition_001` WHERE `pick_pack_requisition_code_id_fk` in (".$reg->id.") group by pick_pack_requisition_code_id_fk ");
       $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {
          $d = DB::select(" SELECT * FROM `db_pick_pack_requisition_code` WHERE `id`=".$row->pick_pack_requisition_code_id_fk." ");
          return "<b>".@$d[0]->requisition_code."</b>";
      })
      ->addColumn('column_002', function($row) {
          $d = DB::select(" SELECT * FROM `db_pick_pack_requisition_code` WHERE `id`=".$row->pick_pack_requisition_code_id_fk." ");
          return "<b>".@$d[0]->pick_pack_packing_code."</b>";
      })
      ->escapeColumns('column_002')
      ->addColumn('column_003', function($row) {
          $d = DB::select(" SELECT * FROM `db_pick_pack_requisition_code` WHERE `id`=".$row->pick_pack_requisition_code_id_fk." ");
          return "<b>".@$d[0]->receipts."</b>";      })
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
    // return $reg->id;
    if(!empty($reg->packing_id)){

      // วุฒิเพิ่มมา
      $remain_id = DB::table('db_pick_pack_packing_code')->where('id',$reg->packing_id)->first();
      // if($remain_id){
      //   if($remain_id->bill_remain_status==1){
      //     $reg->packing_id = $remain_id->ref_bill_id;
      //   }
      // }

        $d1 = DB::select(" SELECT pick_pack_requisition_code_id_fk FROM `db_pay_requisition_001` WHERE `pick_pack_requisition_code_id_fk`=".$reg->packing_id." ");


        if($d1){

          // วุฒิลองแก้เป็นฐานตัวเอง
          $db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id;
          // $db_pick_pack_requisition_code = "db_pick_pack_requisition_code";
          $d2 = DB::select(" SELECT receipts FROM $db_pick_pack_requisition_code WHERE `pick_pack_packing_code_id_fk`=".$d1[0]->pick_pack_requisition_code_id_fk." ");
            // $d2 = DB::select(" SELECT * FROM `db_pick_pack_requisition_code` WHERE `id`=".$d1[0]->pick_pack_requisition_code_id_fk." ");

            $arr1 = [];
            if(!$d2){
     // วุฒิเพิ่มกันที่อยู่หาย
              $r_db_pick_pack_packing_code = DB::select(" SELECT * FROM db_pick_pack_packing_code WHERE id in (".$d1[0]->pick_pack_requisition_code_id_fk.") ; ");
              $arr_00 = [];
              $arr_receipt = [];
              foreach ($r_db_pick_pack_packing_code as $key => $value) {
                if($value->orders_id_fk!="") array_push($arr_00,$value->orders_id_fk);
                array_push($arr_receipt,$value->receipt);
              }
              $receipts = implode(",",$arr_receipt);

            $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id;
            DB::select(" DROP TABLE IF EXISTS $temp_db_pick_pack_requisition_code ; ");
            DB::select(" CREATE TABLE $temp_db_pick_pack_requisition_code LIKE db_pick_pack_requisition_code ");
            DB::select(" INSERT INTO $temp_db_pick_pack_requisition_code select * from db_pick_pack_requisition_code ");

            DB::select(" INSERT IGNORE INTO $temp_db_pick_pack_requisition_code(pick_pack_packing_code_id_fk,pick_pack_packing_code,action_user,receipts) VALUES ('".$d1[0]->pick_pack_requisition_code_id_fk."','".$d1[0]->pick_pack_requisition_code_id_fk."',".(\Auth::user()->id).",'".$receipts."') ");
            $lastInsertId01 = DB::getPdo()->lastInsertId();

            $requisition_code = "P3".sprintf("%05d",$lastInsertId01);
            DB::select(" UPDATE $temp_db_pick_pack_requisition_code SET requisition_code='$requisition_code' WHERE id in ($lastInsertId01) ");

            $d2 = DB::select(" SELECT receipts FROM $db_pick_pack_requisition_code WHERE `pick_pack_packing_code_id_fk`=".$d1[0]->pick_pack_requisition_code_id_fk." ");
            }


// dd($db_pick_pack_requisition_code);
            if($d2){
              foreach ($d2 as $key => $v) {
                 $str = explode(',',$v->receipts);
                 foreach ($str as $key => $v2) {
                   array_push($arr1,"'".$v2."'");
                 }
              }
              $arr2 = implode(',', $arr1);

              $d3 = DB::select("SELECT set_addr_send_this,orders_id_fk,id FROM `db_delivery` WHERE receipt in ($arr2) and set_addr_send_this=1 ;");
              $d0 = DB::select("SELECT set_addr_send_this,orders_id_fk,id FROM `db_delivery` WHERE receipt in ($arr2) and set_addr_send_this=0 ;");

              // วุฒิเพิ่มมาสำหรับพวกบิลส่งกับบิลอื่น
              if(!empty($d0)){

                foreach($d0 as $d0_data){
                  $pack_check = DB::table('db_delivery_packing')
                  ->select('packing_code')
                  ->where('delivery_id_fk', $d0_data->id)
                  ->first();
              if ($pack_check) {
                  $pack_check_arr = DB::table('db_delivery_packing')
                      ->select('delivery_id_fk')
                      ->where('packing_code', $pack_check->packing_code)
                      ->pluck('delivery_id_fk')
                      ->toArray();
                  if (count($pack_check_arr) > 0) {

                    $delivery_data = DB::table('db_delivery')
                    ->select('set_addr_send_this', 'recipient_name', 'addr_send', 'postcode', 'mobile', 'tel_home', 'status_pack', 'receipt', 'id as delivery_id_fk', 'orders_id_fk')
                    ->whereIn('id', $pack_check_arr)
                    ->where('set_addr_send_this', 1)
                    ->first();

                    // dd($d0_data->id);

                    DB::table('db_delivery')->where('id',$d0_data->id)->update([
                      'recipient_name' => $delivery_data->recipient_name,
                      'addr_send' => $delivery_data->addr_send,
                      'postcode' => $delivery_data->postcode,
                      'mobile' => $delivery_data->mobile,
                      'tel_home' => $delivery_data->tel_home,
                    ]);


                  }
              }
                }

                // $d3_arr = [];
                // $data_d3 = DB::select("SELECT set_addr_send_this,orders_id_fk,id FROM `db_delivery` WHERE receipt in ($arr2);");
                // foreach($data_d3 as $data){
                //   if($data->set_addr_send_this==0){
                //     // dd($data->orders_id_fk);
                //     $order_this = DB::table('db_orders')->where('id',$data->orders_id_fk)->first();
                //     if($order_this){
                //       $order_send = DB::table('db_orders')->where('code_order','like','%'.$order_this->bill_transfer_other.'%')->first();
                //       if($order_send){
                //           // $d3_arr[$data->id] = $order_send->code_order;
                //           $d_send = DB::table('db_delivery')->where('orders_id_fk',$order_send->id)->first();
                //           if($d_send){
                //             DB::table('db_delivery')->where('id',$data->id)->update([
                //               'recipient_name' => $d_send->recipient_name,
                //               'addr_send' => $d_send->addr_send,
                //               'postcode' => $d_send->postcode,
                //               'mobile' => $d_send->mobile,
                //               'tel_home' => $d_send->tel_home,
                //             ]);
                //           }

                //       }
                //     }
                //   }
                // }


              }

              // $d3 = DB::select("SELECT * FROM `db_delivery` WHERE receipt in ($arr2) and set_addr_send_this=0 ;");
              $d3 = DB::select("SELECT * FROM `db_delivery` WHERE receipt in ($arr2);");

              // dd($arr2);

              if(!empty($d3)){
                // dd('ok');
                     foreach ($d3 as $key => $v3) {

                       $recipient_code = $v3->packing_code!=0?"P1".sprintf("%05d",$v3->packing_code):$v3->receipt;

                      //  if($remain_id->bill_remain_status==1){
                      //   $recipient_code_real = $recipient_code;
                      //   $recipient_code = 'C'.$recipient_code;
                      //  }else{
                      //   $recipient_code_real = null;
                      //  }

                      $check = DB::table('db_consignments')->select('id')->where('recipient_code',$recipient_code)->where('pick_pack_requisition_code_id_fk',$reg->packing_id)->first();

                        // if($recipient_code=='P100107' && $v3->status_to_wh_by!=0){
                        //   // dd($check);
                        //   dd($v3);
                        // }

                      if(!$check && $v3->status_to_wh_by!=0){
                        DB::select(" INSERT IGNORE INTO `db_consignments`
                        SET
                        `pick_pack_requisition_code_id_fk`='$reg->packing_id' ,
                        `recipient_code`='$recipient_code' ,
                        `recipient_name`='$v3->recipient_name' ,
                        `address`='$v3->addr_send' ,
                        `postcode`='$v3->postcode' ,
                        `mobile`='$v3->mobile' ,
                        `phone_no`='$v3->tel_home' ,
                        `delivery_id_fk`='$v3->id' ,
                        `created_at`=now()
                        ");
                      }

                    }
              }
              // dd('no');

            }
        }

      }

      $sTable = DB::select(" SELECT * FROM `db_consignments` where pick_pack_requisition_code_id_fk='$reg->packing_id' group by pick_pack_requisition_code_id_fk order by recipient_code asc");

      $sQuery = \DataTables::of($sTable);

      return $sQuery

      ->addColumn('column_del', function($row) use($remain_id) {

        $d = DB::select(" SELECT recipient_code FROM `db_consignments` where pick_pack_requisition_code_id_fk = $row->pick_pack_requisition_code_id_fk order by delivery_id_fk asc");

        $f = [] ;
        $tx = '';
        foreach ($d as $key => $v) {

            $tx = '<center>

              <a href="backend/pick_warehouse_del_packing/'.$v->recipient_code.'"  onclick="return confirm(\'คุณต้องการยกเลิกรายการ '.$v->recipient_code.' ? (หลังจากนั้นจะไม่สามารถแก้ไขได้อีก)\')" ><i class="bx bx-trash grow " data-toggle="tooltip" data-placement="left" title="ยกเลิกรายการ '.$v->recipient_code.'" style="font-size:24px;cursor:pointer;color:red;"></i></a>

               </center>' ;

           array_push($f,@$tx);
        }
        // $f = implode('<br><br>',$f);
        // return $f;

        $web_all = "";
        if($remain_id->status==1 || $remain_id->status==2 || $remain_id->status==3 || $remain_id->status==4){
          foreach($f as $value){
            $web = "<div class='col-md-12'style='height: 70px;'>";
            $web .=$value;
            $web .= "</div>";
            $web_all .=$web.'<br>';
          }

        }

        return $web_all;

     })
     ->escapeColumns('column_del')

      ->addColumn('column_001', function($row) {

         $d = DB::select(" SELECT recipient_code FROM `db_consignments` where pick_pack_requisition_code_id_fk = $row->pick_pack_requisition_code_id_fk order by delivery_id_fk asc");

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

         $d = DB::select(" SELECT recipient_name,address,postcode,mobile,phone_no FROM `db_consignments` where pick_pack_requisition_code_id_fk = $row->pick_pack_requisition_code_id_fk order by delivery_id_fk asc");

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

          $d = DB::select(" SELECT consignment_no,con_arr FROM `db_consignments` where pick_pack_requisition_code_id_fk = $row->pick_pack_requisition_code_id_fk order by delivery_id_fk asc");

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

          $d = DB::select(" SELECT recipient_code FROM `db_consignments` where pick_pack_requisition_code_id_fk = $row->pick_pack_requisition_code_id_fk order by delivery_id_fk asc");

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

        $d = DB::select(" SELECT recipient_code FROM `db_consignments` where pick_pack_requisition_code_id_fk = $row->pick_pack_requisition_code_id_fk order by delivery_id_fk asc");

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
          ->select('bill_remain_status')
          ->where('id',$row->pick_pack_requisition_code_id_fk)->first();

          // วุฒิแก้อีกรอบ
          if($db_pick_pack_packing_code->bill_remain_status==1){
            $DP = DB::table('db_pick_pack_packing')
            ->select('id','p_amt_box')
            ->where('packing_code_id_fk',$row->pick_pack_requisition_code_id_fk)
            ->where('no_product',0)
            ->orderBy('db_pick_pack_packing.delivery_id_fk','asc')
            ->get();
          }else{
            $DP = DB::table('db_pick_pack_packing')
            ->select('id','p_amt_box')
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
              $pn = '<input type="number" class="p_amt_box form-control" data-id="'.@$value->id.'" box-id="0"  type="text" value="'.@$value->p_amt_box.'">';
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
        $p_code = DB::table('db_pick_pack_packing_code')->select('id')->where('id',$row->pick_pack_requisition_code_id_fk)->first();
        if($p_code){
            $DP = DB::table('db_pick_pack_packing')
            ->select('db_pick_pack_packing.delivery_id_fk')
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

    function pick_warehouse_del_packing($p_id){
      \DB::beginTransaction();
      try {
      $db_consignments = DB::table('db_consignments')->where('recipient_code',$p_id)->get();
      foreach($db_consignments as $con){
        $db_delivery_data = DB::table('db_delivery')->select('id')->where('packing_code_desc',$con->recipient_code)->first();
        if($db_delivery_data){
          $db_delivery = DB::table('db_delivery')->where('packing_code_desc',$con->recipient_code)->get();
        }else{
          $db_delivery = DB::table('db_delivery')->where('receipt',$con->recipient_code)->get();
        }
        // dd($db_delivery);
        foreach($db_delivery as $de){
          $db_pick_pack_packing_code = DB::table('db_pick_pack_packing_code')->where('id',$con->pick_pack_requisition_code_id_fk)->first();
          // dd($db_pick_pack_packing_code); db_delivery_packing
          DB::table('db_delivery')->where('id',$de->id)->update([
            'status_pick_pack' => 0,
            'status_delivery' => 0,
            'status_tracking' => 0,
            'status_scan_wh' => 0,
            'user_scan' => 0,
          ]);
          if($db_pick_pack_packing_code){
            $or_ids = explode(",", $db_pick_pack_packing_code->orders_id_fk);
            $or_codes = explode(",", $db_pick_pack_packing_code->receipt);
            $or_ids_str = "";
            $or_codes_str = "";

            foreach($or_ids as $key => $or_id){
                if($or_id!=$de->orders_id_fk){
                  if($key+1==count($or_ids)){
                    $or_ids_str .= $or_id;
                  }else{
                    $or_ids_str .= $or_id.',';
                  }
                }
            }
            $or_ids_str2 = explode(",", $or_ids_str);
            $or_ids_str_arr = [];
            foreach($or_ids_str2 as $key => $or_id){
            if($or_id!=''){
              array_push($or_ids_str_arr,$or_id);
            }
          }
          $or_ids_str = '';
          foreach($or_ids_str_arr as $key => $or_id){
              if($key+1==count($or_ids_str_arr)){
                $or_ids_str .= $or_id;
              }else{
                $or_ids_str .= $or_id.',';
              }
        }

            foreach($or_codes as $key => $or_code){
              if($or_code!=$de->receipt){
                if($key+1==count($or_codes)){
                  $or_codes_str .= $or_code;
                }else{
                  $or_codes_str .= $or_code.',';
                }
              }
          }
          $or_codes_str2 = explode(",", $or_codes_str);
          $or_codes_str_arr = [];
          foreach($or_codes_str2 as $key => $or_code){
           if($or_code!=''){
            array_push($or_codes_str_arr,$or_code);
           }
        }
        $or_codes_str = '';
        foreach($or_codes_str_arr as $key => $or_code){
            if($key+1==count($or_codes_str_arr)){
              $or_codes_str .= $or_code;
            }else{
              $or_codes_str .= $or_code.',';
            }
      }

          // db_pick_pack_packing_code
            DB::table('db_pick_pack_packing_code')->where('id',$db_pick_pack_packing_code->id)->update([
              'orders_id_fk' => $or_ids_str,
              'receipt' => $or_codes_str,
            ]);

            $db_pay_requisition_002_item = DB::table('db_pay_requisition_002_item')->where('order_id',$de->orders_id_fk)->get();
            foreach($db_pay_requisition_002_item as $req_item){
              $db_pay_requisition_002 = DB::table('db_pay_requisition_002')->where('id',$req_item->requisition_002_id)->first();
              if($db_pay_requisition_002){
                DB::table('db_pay_requisition_002')->where('id',$req_item->requisition_002_id)->update([
                  'amt_get' => ($db_pay_requisition_002->amt_get - $req_item->amt_get),
                  'amt_need' => ($db_pay_requisition_002->amt_need - $req_item->amt_need),
                  'amt_remain' => ($db_pay_requisition_002->amt_get - $req_item->amt_get) - ($db_pay_requisition_002->amt_need - $req_item->amt_need),
                ]);
              }
              $db_pay_requisition_002 = DB::table('db_pay_requisition_002')->where('id',$req_item->requisition_002_id)->first();
              if($db_pay_requisition_002){
                $db_stocks = DB::table('db_stocks')
                ->where('product_id_fk',$db_pay_requisition_002->product_id_fk)
                ->where('warehouse_id_fk',$db_pay_requisition_002->warehouse_id_fk)
                ->where('business_location_id_fk',$db_pay_requisition_002->business_location_id_fk)
                ->where('branch_id_fk',$db_pay_requisition_002->branch_id_fk)
                ->where('zone_id_fk',$db_pay_requisition_002->zone_id_fk)
                ->where('shelf_id_fk',$db_pay_requisition_002->shelf_id_fk)
                ->where('shelf_floor',$db_pay_requisition_002->shelf_floor)
                ->where('lot_number',$db_pay_requisition_002->lot_number)
                ->first();
                if($db_stocks){
                    DB::table('db_stocks')
                  ->where('id',$db_stocks->id)
                  ->update([
                    'amt' => $db_stocks->amt + $req_item->amt_get,
                  ]);
                  $db_pick_pack_packing = DB::table('db_pick_pack_packing')->select('packing_code','created_at')->where('packing_code_id_fk',$con->pick_pack_requisition_code_id_fk)->first();
                  if($db_pick_pack_packing){
                    $ref_doc = $db_pick_pack_packing->packing_code;
                    $doc_date = $db_pick_pack_packing->created_at;
                  }else{
                    $ref_doc = "";
                    $doc_date = "";
                  }
                  DB::table('db_stock_movement')->insert([
                    'stock_type_id_fk' => 1,
                    'stock_id_fk' => $db_stocks->id,
                    'ref_table' => 'db_pay_requisition_002',
                    'ref_table_id' => $req_item->requisition_002_id,
                    'ref_doc' => $ref_doc,
                    'doc_date' => $doc_date,
                    'business_location_id_fk' => $db_stocks->business_location_id_fk,
                    'branch_id_fk' => $db_stocks->branch_id_fk,
                    'product_id_fk' => $db_stocks->product_id_fk,
                    'lot_number' => $db_stocks->lot_number,
                    'lot_expired_date' => $db_stocks->lot_expired_date,
                    'amt' => $req_item->amt_get,
                    'in_out' => 1,
                    'product_unit_id_fk' => $db_stocks->product_unit_id_fk,
                    'warehouse_id_fk' => $db_stocks->warehouse_id_fk,
                    'zone_id_fk' => $db_stocks->zone_id_fk,
                    'shelf_id_fk' => $db_stocks->shelf_id_fk,
                    'shelf_floor' => $db_stocks->shelf_floor,
                    'status' => 1,
                    'note' => 'ยกเลิกจ่ายสินค้าตามใบเบิก',
                    'action_user' => \Auth::user()->id,
                    'action_date' => date('Y-m-d H:i:s'),
                    'approver' => \Auth::user()->id,
                    'approve_date' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                  ]);
                }
              }

              DB::table('db_pick_pack_requisition_code')->where('pick_pack_packing_code_id_fk',$db_pick_pack_packing_code->id)->update([
                'receipts' => $or_codes_str,
              ]);

              $db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id;

              DB::table($db_pick_pack_requisition_code)->where('pick_pack_packing_code_id_fk',$db_pick_pack_packing_code->id)->update([
                'receipts' => $or_codes_str,
              ]);

              //  db_pick_pack_requisition_code
              $db_pay_requisition_002 = DB::table('db_pay_requisition_002')->where('id',$req_item->requisition_002_id)->first();
              if($db_pay_requisition_002->amt_need<=0){
               DB::table('db_pay_requisition_002')->where('id',$req_item->requisition_002_id)->delete();
              }

              DB::table('db_pay_requisition_002_item')->where('id',$req_item->id)->delete();
              DB::table('db_pick_pack_packing')->where('delivery_id_fk',$de->id)->where('packing_code_id_fk',$con->pick_pack_requisition_code_id_fk)->delete();
            }

          }

        }
      }

      DB::table('db_consignments')->where('recipient_code',$p_id)->delete();

      \DB::commit();
    } catch (\Exception $e) {
          echo $e->getMessage();
          \DB::rollback();
          return redirect()->back()->with(['alert'=>\App\Models\Alert::e($e)]);
        }

      return redirect()->back()->with('success','Delete success');
    }


    function pick_warehouse_scan_save(Request $r){
      DB::table('db_delivery')->where('id',$r->delivery_id)->update([
        'status_scan_wh' => 1,
        'user_scan' => \Auth::user()->id,
      ]);
        return redirect()->back();
    }

    function qr_show($oid,$pid,$proid,$p_list){
      // dd($proid);
        $qrs = DB::table('db_pick_warehouse_qrcode')
        // ->where('invoice_code',$oid)
        // ->where('product_id_fk',$proid)
        ->where('packing_code',$pid)
        ->where('packing_list',$p_list)
        ->orderBy('item_id','asc')
        ->get();
        // $product = DB::table('products')
        // ->join('products_details','products_details.product_id_fk','products_details.id')
        // ->where('products.id',$proid)
        // ->first();
      return view('backend.pick_warehouse.qr_show',[
        'qrs' => $qrs,
        'oid' => $oid,
        'pid' => $pid,
        'p_list' => $p_list,
      ]);
    }

    public function qr_show_import(Request $request){
      // dd($request->all());
    if ($request->file('excel_data')){
      $file = $request->file('excel_data');
      // File Details
      $filename = $file->getClientOriginalName();
      $extension = $file->getClientOriginalExtension();
      $tempPath = $file->getRealPath();
      $fileSize = $file->getSize();
      $mimeType = $file->getMimeType();
      // Valid File Extensions
      $valid_extension = array("xlsx");
      // 2MB in Bytes
      // $maxFileSize = 2097152;
      // 5MB in Bytes
      $maxFileSize = 5242880;
      // Check file extension
      if(in_array(strtolower($extension),$valid_extension)){
        // Check file size
        if($fileSize <= $maxFileSize){
          // File upload location
          $location = 'uploads_excel_wh/';

          $destinationPath = public_path($location);
          $file->move($destinationPath, $filename);

          $filepath = public_path("uploads_excel_wh/".$filename);

          $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
          $spreadsheet = $reader->load($filepath);

          $worksheet = $spreadsheet->getActiveSheet();
          $highestRow = $worksheet->getHighestRow(); // total number of rows
          $highestColumn = $worksheet->getHighestColumn(); // total number of columns
          $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

          $lines = $highestRow - 1;
          if ($lines <= 0) {
                   // Exit ('There is no data in the Excel table');
              Session::flash('message','There is no data in the Excel table');

          }else{
              // dd($highestRow);
              $i = 0;
              $item_id = 0;

              $qr_old = DB::table('db_pick_warehouse_qrcode')
              ->select('item_id')
              ->where('packing_code',$request->pid)
              ->where('packing_list',$request->p_list)
              ->orderBy('item_id','desc')
              ->first();
              if($qr_old){
                $item_id = $qr_old->item_id;
              }

              $arr_con = [];

              for ($row = 1; $row <= $highestRow; ++$row) {
                   $qr_code = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                   $remark = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    // Skip first row (Remove below comment if you want to skip the first row)
                   if($i == 0){
                      $i++;
                      $item_id++;
                      continue;
                   }
                   if($qr_code!=''){
                    DB::table('db_pick_warehouse_qrcode')->insert([
                      'item_id'=>$item_id,
                      'invoice_code'=>$request->oid,
                      'packing_code'=>$request->pid,
                      'packing_list'=>$request->p_list,
                      'product_id_fk'=>0,
                      'qr_code'=>$qr_code,
                      'remark' => $remark,
                      'created_at' => date('Y-m-d H:i:s'),
                      'updated_at' => date('Y-m-d H:i:s'),
                     ]);
                     $item_id++;
                   }
                   $i++;

              }
              Session::flash('message','Import Successful.');
          }
        }else{
          Session::flash('message','File too large. File must be less than 5MB.');
        }
      }else{
         Session::flash('message','Invalid File Extension.');
      }

    }
    return redirect()->back();

  }

    // db_pick_pack_packing_code
    function pick_warehouse_save_new_bill(Request $r){

      $DeliveryPackingCode = \App\Models\Backend\Pick_packPackingCode::where('id',$r->pick_pack_packing_code_id_fk)->first();
      if($DeliveryPackingCode){

        $orders_id_fk = "";
        $receipt = "";
        $arr_order = [];
        $pay_item_arr = [];
        $pay_requisition_002_item = "";
      // วุฒิเพิ่มมา เบิกจ่ายสินค้าใบเบิกใหม่ 1
      $remain_status = DB::table('db_pay_requisition_002')->select('id','amt_remain','product_id_fk')->where('pick_pack_requisition_code_id_fk',$DeliveryPackingCode->id)->orderBy('time_pay','desc')->get();
      $arr_remain = [];
      foreach($remain_status as $rem){
          if(!isset($arr_remain[$rem->product_id_fk])){
            $arr_remain[$rem->product_id_fk] = $rem;
          }
      }

      foreach($arr_remain as $rem){
        $db_pay_requisition_002_item = DB::table('db_pay_requisition_002_item')
        ->where('requisition_002_id',$rem->id)
        ->where('product_id_fk',$rem->product_id_fk)
        ->where('amt_remain', '>', 0)
        ->get();

        foreach( $db_pay_requisition_002_item as $item){
          $order = DB::table('db_orders')->where('id',$item->order_id)->first();
          if($order){
            if(!isset($arr_order[$item->order_id])){
              $arr_order[$item->order_id] = $item;
            }
          }
          if(!isset($pay_item_arr[$item->id])){
            $pay_item_arr[$item->id] = $item;
          }
        }
      }

      $i = 0;

      foreach($arr_order as $key => $rem){
        $order = DB::table('db_orders')->where('id',$rem->order_id)->first();
        $i++;
        if($i==count($arr_order)){
          $orders_id_fk .= $order->id;
          $receipt .= $order->code_order;
        }else{
          $orders_id_fk .= $order->id.',';
          $receipt .= $order->code_order.',';
        }

      }

      $i = 0;
      foreach($pay_item_arr as $key => $rem){
        $i++;
        if($i==count($pay_item_arr)){
          $pay_requisition_002_item .= $rem->id;
        }else{
          $pay_requisition_002_item .= $rem->id.',';
        }

      }

          $DeliveryPackingCode2 = new \App\Models\Backend\Pick_packPackingCode;
          $DeliveryPackingCode2->business_location_id = $DeliveryPackingCode->business_location_id;
          $DeliveryPackingCode2->branch_id_fk =  $DeliveryPackingCode->branch_id_fk;
          $DeliveryPackingCode2->orders_id_fk =  $orders_id_fk;
          $DeliveryPackingCode2->receipt = $receipt;
          $DeliveryPackingCode2->status_picked = 1 ;
          $DeliveryPackingCode2->action_user = (\Auth::user()->id);
          $DeliveryPackingCode2->created_at = date('Y-m-d H:i:s');
          $DeliveryPackingCode2->updated_at = date('Y-m-d H:i:s');
          $DeliveryPackingCode2->bill_remain_status = 1;
          $DeliveryPackingCode2->ref_bill_id = $DeliveryPackingCode->id;
          $DeliveryPackingCode2->pay_requisition_002_item = $pay_requisition_002_item;
          $DeliveryPackingCode2->save();
          // receipt
          // DB::select(" UPDATE customers_addr_sent SET packing_code=".$DeliveryPackingCode->id.",receipt_no='".$rsDelivery[0]->receipt."' WHERE (invoice_code='".$rsDelivery[0]->receipt."'); ");
          $DeliveryPackingCode->bill_remain_status = 2;
          $DeliveryPackingCode->ref_bill_id = $DeliveryPackingCode2->id;
          $DeliveryPackingCode->save();
    }

    $DeliveryPacking = \App\Models\Backend\Pick_packPacking::where('packing_code_id_fk',$r->pick_pack_packing_code_id_fk)->get();

    foreach ($DeliveryPacking as $key => $value) {
      $DeliveryPacking = new \App\Models\Backend\Pick_packPacking;
      $DeliveryPacking->packing_code_id_fk = $DeliveryPackingCode2->id;
      $DeliveryPacking->packing_code = "P2".sprintf("%05d",$DeliveryPackingCode2->id) ;
      $DeliveryPacking->delivery_id_fk = $value->delivery_id_fk;
      $DeliveryPacking->created_at = date('Y-m-d H:i:s');
      $DeliveryPacking->save();
      // DB::update(" UPDATE db_delivery SET status_pick_pack='1' , status_tracking='1' WHERE packing_code<>0 and packing_code in (".@$value->packing_code.")  ");
      // DB::select(" UPDATE customers_addr_sent SET packing_code=".$DeliveryPackingCode->id." WHERE (receipt_no='".$value->receipt."'); ");
      // DB::select(" UPDATE db_orders SET status_delivery=1 WHERE (invoice_code='".$value->receipt."'); ");

   }

    return true;

  }

  function pick_warehouse_edit_product_store(Request $r){
    // dd($r->all());
    \DB::beginTransaction();
    try {
    foreach($r->product_id_fk as $key => $product_id_fk){
      if($product_id_fk!=null && $product_id_fk!=''){
        // dd($product_id_fk);
          $db_stocks = DB::table('db_stocks')->where('id',$r->wh[$key])->first();
          // dd($db_stocks);
          if($db_stocks){
              if($db_stocks->amt >= $r->amt_need[$key]){
                 $db_pay_requisition_002_old = DB::table('db_pay_requisition_002')->where('pick_pack_requisition_code_id_fk',$r->pick_pack_packing_code_id)->orderBy('id','Desc')->first();
                //  dd($db_pay_requisition_002_old);
                  if($db_pay_requisition_002_old){
// dd($db_pay_requisition_002_old);
                    $products = DB::table('products')->select('id','product_code')->where('id',$product_id_fk)->first();
                    if($products){
                      $products_details = DB::table('products_details')->select('product_name')
                      ->where('product_id_fk',$products->id)->where('lang_id',1)->first();

                      $r_id = DB::table('db_pay_requisition_002')->insertGetId([
                        'time_pay' => $db_pay_requisition_002_old->time_pay,
                        'business_location_id_fk' => $db_pay_requisition_002_old->business_location_id_fk,
                        'branch_id_fk' => $db_pay_requisition_002_old->branch_id_fk,
                        'pick_pack_requisition_code_id_fk' => $db_pay_requisition_002_old->pick_pack_requisition_code_id_fk,
                        'customers_id_fk' => $db_pay_requisition_002_old->customers_id_fk,
                        'product_id_fk' => $product_id_fk,
                        'product_name' => $products->product_code.' : '.$products_details->product_name,
                        'amt_need' => $r->amt_need[$key],
                        'amt_get' => $r->amt_need[$key],
                        'amt_lot' => $db_stocks->amt,
                        'amt_remain' => 0,
                        'product_unit_id_fk' => 4,
                        'product_unit' => 'ชิ้น',
                        'lot_number' => $db_stocks->lot_number,
                        'lot_expired_date' => $db_stocks->lot_expired_date,
                        'warehouse_id_fk' => $db_stocks->warehouse_id_fk,
                        'zone_id_fk' => $db_stocks->zone_id_fk,
                        'shelf_id_fk' => $db_stocks->shelf_id_fk,
                        'shelf_floor' => $db_stocks->shelf_floor,
                        'status_cancel'=>0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                       ]);

                       $db_pick_pack_packing = DB::table('db_pick_pack_packing')
                       ->select('packing_code','created_at')
                       ->where('packing_code_id_fk',$db_pay_requisition_002_old->pick_pack_requisition_code_id_fk)
                       ->first();

                       DB::table('db_pay_requisition_002_pay_history')->insert([
                        'time_pay' => $db_pay_requisition_002_old->time_pay,
                        'pick_pack_requisition_code_id_fk' => $db_pay_requisition_002_old->pick_pack_requisition_code_id_fk,
                        'pick_pack_packing_code_id_fk' => $db_pay_requisition_002_old->pick_pack_requisition_code_id_fk,
                        'product_id_fk' => $product_id_fk,
                        'pay_date' => date('Y-m-d H:i:s'),
                        'pay_user' =>  Auth::user()->id,
                        'amt_need' => $r->amt_need[$key],
                        'amt_get' => $r->amt_need[$key],
                        'amt_remain' => 0,
                        'status' => 3,
                       ]);

                       DB::table('db_stock_movement')->insert([
                        'stock_type_id_fk' =>  1,
                        'stock_id_fk' => $db_stocks->id,
                        'ref_table' => 'db_pay_requisition_002',
                        'ref_table_id' => $r_id,
                        'ref_doc' => $db_pick_pack_packing->packing_code,
                        'doc_no' => $db_pick_pack_packing->packing_code,
                        'doc_date' => $db_pick_pack_packing->created_at,
                        'business_location_id_fk' => $db_pay_requisition_002_old->business_location_id_fk,
                        'branch_id_fk' => $db_pay_requisition_002_old->branch_id_fk,
                        'product_id_fk' => $product_id_fk,
                        'lot_number' => $db_stocks->lot_number,
                        'lot_expired_date' => $db_stocks->lot_expired_date,
                        'amt' => $r->amt_need[$key],
                        'in_out' => 2,
                        'product_unit_id_fk' => 4,
                        'warehouse_id_fk' => $db_stocks->warehouse_id_fk,
                        'zone_id_fk' => $db_stocks->zone_id_fk,
                        'shelf_id_fk' => $db_stocks->shelf_id_fk,
                        'shelf_floor' => $db_stocks->shelf_floor,
                        'status' => 1,
                        'note' => 'จ่ายสินค้าตามใบเบิก',
                        'action_user' => Auth::user()->id,
                        'action_date' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                       ]);

                       DB::table('db_stocks')->where('id',$r->wh[$key])->update([
                        'amt' => $db_stocks->amt-$r->amt_need[$key],
                       ]);

                       \App\Helpers\General::create_pay_requisition_002_item($db_pay_requisition_002_old->pick_pack_requisition_code_id_fk);

                    }
                  }else{
                    // ถ้าไม่เคยมีสินค้าเลย
                    $products = DB::table('products')->select('id','product_code')->where('id',$product_id_fk)->first();
                    if($products){
                      $products_details = DB::table('products_details')->select('product_name')
                      ->where('product_id_fk',$products->id)->where('lang_id',1)->first();

                      $r_id = DB::table('db_pay_requisition_002')->insertGetId([
                        'time_pay' => 1,
                        'business_location_id_fk' => 1,
                        'branch_id_fk' => 6,
                        'pick_pack_requisition_code_id_fk' => $r->pick_pack_packing_code_id,
                        'customers_id_fk' => 0,
                        'product_id_fk' => $product_id_fk,
                        'product_name' => $products->product_code.' : '.$products_details->product_name,
                        'amt_need' => $r->amt_need[$key],
                        'amt_get' => $r->amt_need[$key],
                        'amt_lot' => $db_stocks->amt,
                        'amt_remain' => 0,
                        'product_unit_id_fk' => 4,
                        'product_unit' => 'ชิ้น',
                        'lot_number' => $db_stocks->lot_number,
                        'lot_expired_date' => $db_stocks->lot_expired_date,
                        'warehouse_id_fk' => $db_stocks->warehouse_id_fk,
                        'zone_id_fk' => $db_stocks->zone_id_fk,
                        'shelf_id_fk' => $db_stocks->shelf_id_fk,
                        'shelf_floor' => $db_stocks->shelf_floor,
                        'status_cancel'=>0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                       ]);

                       $db_pick_pack_packing = DB::table('db_pick_pack_packing')
                       ->select('packing_code','created_at')
                       ->where('packing_code_id_fk',$r->pick_pack_packing_code_id)
                       ->first();

                       DB::table('db_pay_requisition_002_pay_history')->insert([
                        'time_pay' => 1,
                        'pick_pack_requisition_code_id_fk' => $r->pick_pack_packing_code_id,
                        'pick_pack_packing_code_id_fk' => $r->pick_pack_packing_code_id,
                        'product_id_fk' => $product_id_fk,
                        'pay_date' => date('Y-m-d H:i:s'),
                        'pay_user' =>  Auth::user()->id,
                        'amt_need' => $r->amt_need[$key],
                        'amt_get' => $r->amt_need[$key],
                        'amt_remain' => 0,
                        'status' => 3,
                       ]);

                       DB::table('db_stock_movement')->insert([
                        'stock_type_id_fk' =>  1,
                        'stock_id_fk' => $db_stocks->id,
                        'ref_table' => 'db_pay_requisition_002',
                        'ref_table_id' => $r_id,
                        'ref_doc' => $db_pick_pack_packing->packing_code,
                        'doc_no' => $db_pick_pack_packing->packing_code,
                        'doc_date' => $db_pick_pack_packing->created_at,
                        'business_location_id_fk' => 1,
                        'branch_id_fk' => 6,
                        'product_id_fk' => $product_id_fk,
                        'lot_number' => $db_stocks->lot_number,
                        'lot_expired_date' => $db_stocks->lot_expired_date,
                        'amt' => $r->amt_need[$key],
                        'in_out' => 2,
                        'product_unit_id_fk' => 4,
                        'warehouse_id_fk' => $db_stocks->warehouse_id_fk,
                        'zone_id_fk' => $db_stocks->zone_id_fk,
                        'shelf_id_fk' => $db_stocks->shelf_id_fk,
                        'shelf_floor' => $db_stocks->shelf_floor,
                        'status' => 1,
                        'note' => 'จ่ายสินค้าตามใบเบิก',
                        'action_user' => Auth::user()->id,
                        'action_date' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                       ]);

                       DB::table('db_stocks')->where('id',$r->wh[$key])->update([
                        'amt' => $db_stocks->amt-$r->amt_need[$key],
                       ]);

                       \App\Helpers\General::create_pay_requisition_002_item($r->pick_pack_packing_code_id);

                    }


                  }

              }
          }
      }
    }

    \DB::commit();

    return redirect()->to('backend/pay_requisition_001')->with('success','ทำรายการสำเร็จ');

  } catch (\Exception $e) {
    echo $e->getMessage();
    \DB::rollback();
  }


  }


}
