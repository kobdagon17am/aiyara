<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;
use App\Models\Backend\AddressSent;

class DeliveryController extends Controller
{

    public function index(Request $request)
    {

      // ขายหน้าร้าน (หลังบ้าน)
      // DB::select(" TRUNCATE `db_delivery` ; ");
//   `approve_status` int(11) DEFAULT '0' COMMENT '1=รออนุมัติ,2=อนุมัติแล้ว,3=รอชำระ,4=รอจัดส่ง,5=ยกเลิก,6=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย) > Ref>dataset_approve_status>id',
      DB::select("
          INSERT IGNORE INTO db_delivery
          ( orders_id_fk,receipt, customer_id, business_location_id,branch_id_fk , delivery_date, billing_employee, created_at,list_type,shipping_price)
          SELECT id,code_order,customers_id_fk,business_location_id_fk,branch_id_fk,created_at,action_user,now(),2,shipping_price 
          FROM db_orders where code_order<>'' AND delivery_location<>0 AND approve_status in(2,4) ; 
        ");

      // รายที่ยังไม่อนุมัติ และ รอจัดส่ง และ ไม่ได้รอส่งไปสาขาอื่น
      $sDelivery = \App\Models\Backend\Delivery::where('approver','NULL')->get();
      // dd($sDelivery);
      $sPacking = \App\Models\Backend\DeliveryPackingCode::where('status_delivery','<>','2')->get();


      // นำเข้าที่อยู่ในการจัดส่ง
      // `addr_type` int(1) DEFAULT '0' COMMENT 'ที่อยู่ผู้รับ>0=รัยสินค้าที่สาขาด้วยตนเอง,
      // 1=ที่อยู่ตามบัตร ปชช.>customers_address_card,
      // 2=ที่อยู่ตามที่ลงทะเบียนในระบบ>customers_detail,
      // 3>ที่อยู่กำหนดเอง>customers_addr_frontstore',

        // 1=ที่อยู่ตามบัตร ปชช.>customers_address_card
        // $address_card = DB::select("

        //         SELECT
        //         customers_address_card.*,
        //         dataset_provinces.name_th AS provname,
        //         dataset_amphures.name_th AS ampname,
        //         dataset_districts.name_th AS tamname,
        //         customers.prefix_name,
        //         customers.first_name,
        //         customers.last_name
        //         FROM
        //         customers_address_card
        //         Left Join dataset_provinces ON customers_address_card.card_province_id_fk = dataset_provinces.id
        //         Left Join dataset_amphures ON customers_address_card.card_amphures_id_fk = dataset_amphures.id
        //         Left Join dataset_districts ON customers_address_card.card_district_id_fk = dataset_districts.id
        //         Left Join customers ON customers_address_card.customer_id = customers.id

        //        ");

        // foreach ($address_card as $key => $value) {

        //       $d = array(
        //          "customer_id"=>@$value->customer_id,
        //          "recipient_name"=>@$value->prefix_name.@$value->first_name.' '.@$value->last_name,
        //          "house_no"=>@$value->card_house_no,
        //          "house_name"=>@$value->card_house_name,
        //          "moo"=>@$value->moo,
        //          "road"=>@$value->road,
        //          "soi"=>@$value->soi,
        //          "amphures_id_fk"=>@$value->card_amphures_id_fk,
        //          "amphures"=>@$value->ampname,
        //          "district_id_fk"=>@$value->card_district_id_fk,
        //          "district"=>@$value->tamname,
        //          "province_id_fk"=>@$value->card_province_id_fk,
        //          "province"=>@$value->provname,
        //          "zipcode"=>@$value->card_zipcode,
        //          "from_table"=>'customers_address_card',
        //          "from_table_id"=>@$value->id,
        //          "addr_type"=>1,
        //          "created_at"=>now());
        //        AddressSent::insertData($d);

        // }

        // // 2=ที่อยู่ตามที่ลงทะเบียนในระบบ>customers_detail,
        // $customers_detail = DB::select("
        //         SELECT
        //           customers_detail.*,
        //           dataset_provinces.name_th AS provname,
        //           dataset_amphures.name_th AS ampname,
        //           dataset_districts.name_th AS tamname,
        //           customers.prefix_name,
        //           customers.first_name,
        //           customers.last_name
        //           FROM
        //           customers_detail
        //           Left Join dataset_provinces ON customers_detail.province_id_fk = dataset_provinces.id
        //           Left Join dataset_amphures ON customers_detail.amphures_id_fk = dataset_amphures.id
        //           Left Join dataset_districts ON customers_detail.district_id_fk = dataset_districts.id
        //           Left Join customers ON customers_detail.customer_id = customers.id
        //            ");

        // foreach ($customers_detail as $key => $value) {

        //       $d = array(
        //          "customer_id"=>@$value->customer_id,
        //          "recipient_name"=>@$value->prefix_name.@$value->first_name.' '.@$value->last_name,
        //          "house_no"=>@$value->house_no,
        //          "house_name"=>@$value->house_name,
        //          "moo"=>@$value->moo,
        //          "road"=>@$value->road,
        //          "soi"=>@$value->soi,
        //          "amphures_id_fk"=>@$value->card_amphures_id_fk,
        //          "amphures"=>@$value->ampname,
        //          "district_id_fk"=>@$value->card_district_id_fk,
        //          "district"=>@$value->tamname,
        //          "province_id_fk"=>@$value->card_province_id_fk,
        //          "province"=>@$value->provname,
        //          "zipcode"=>@$value->zipcode,
        //          "from_table"=>'customers_detail',
        //          "from_table_id"=>@$value->id,
        //          "addr_type"=>2,
        //          "created_at"=>now());
        //        AddressSent::insertData($d);

        // }


        // // 3>ที่อยู่กำหนดเอง>customers_addr_frontstore',
        // $addr_frontstore = DB::select(" SELECT customers_addr_frontstore.*,db_orders.invoice_code from customers_addr_frontstore Left Join db_orders ON customers_addr_frontstore.frontstore_id_fk = db_orders.id ");

        // foreach ($addr_frontstore as $key => $value) {

        //       $district = DB::select(" SELECT * from dataset_amphures where id=".@$value->amphur_code." ");
        //       $district_sub = DB::select(" SELECT * from dataset_districts where id=".@$value->tambon_code." ");
        //       $province = DB::select(" SELECT * from dataset_provinces where id=".@$value->province_id_fk." ");

        //       $d = array(
        //          "customer_id"=>@$value->customers_id_fk,
        //          "recipient_name"=>@$value->recipient_name,
        //          "house_no"=>@$value->addr_no,
        //          "amphures_id_fk"=>@$value->card_amphures_id_fk,
        //          "amphures"=>@$value->ampname,
        //          "district_id_fk"=>@$value->card_district_id_fk,
        //          "district"=>@$value->tamname,
        //          "province_id_fk"=>@$value->card_province_id_fk,
        //          "province"=>@$value->provname,
        //          "zipcode"=>@$value->zip_code,
        //          "tel"=>@$value->tel,
        //          "from_table"=>'customers_addr_frontstore',
        //          "from_table_id"=>@$value->id,
        //          "addr_type"=>3,
        //          "receipt_no"=>@$value->invoice_code,
        //          "created_at"=>now());
        //        AddressSent::insertData($d);
        // }

     // DB::select(" UPDATE db_delivery SET billing_employee='1' WHERE  billing_employee is null ; ");
        $User_branch_id = \Auth::user()->branch_id_fk;
        $sBranchs = \App\Models\Backend\Branchs::get();
        $Warehouse = \App\Models\Backend\Warehouse::get();
        $Zone = \App\Models\Backend\Zone::get();
        $Shelf = \App\Models\Backend\Shelf::get();

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

      return View('backend.delivery.index')->with(
        array(
           'sDelivery'=>$sDelivery,
           'sBranchs'=>$sBranchs,
           'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,
           'User_branch_id'=>$User_branch_id,
           'Customer'=>$Customer,
           'sBusiness_location'=>$sBusiness_location,
           'sPacking'=>$sPacking,
        ) );


    }


 public function create()
    {

      $Province = DB::select(" select * from dataset_provinces ");

      $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.delivery.form')->with(
        array(
           'Customer'=>$Customer,'Province'=>$Province
        ) );
    }


    public function store(Request $request)
    {
      // dd($request->all());

      if(isset($request->save_to_packing)){

      	if(empty($request->row_id)){
      		return redirect()->to(url("backend/delivery"));
      	}

      	$arr = implode(',', $request->row_id);

        DB::update(" UPDATE db_delivery SET status_pack='1',updated_at=now() WHERE id in ($arr)  ");

        $rsDelivery = DB::select(" SELECT * FROM db_delivery WHERE id in ($arr)  ");

        $rsDeliveryAddr = DB::select("

          SELECT
          db_orders.address_sent_id_fk as addr
          FROM
          db_delivery
          Inner Join db_orders ON db_delivery.receipt = db_orders.invoice_code
          WHERE db_delivery.id in ($arr) limit 1  ");

  	      $DeliveryPackingCode = new \App\Models\Backend\DeliveryPackingCode;
  	      if( $DeliveryPackingCode ){
  	      	$DeliveryPackingCode->address_sent_id_fk = $rsDeliveryAddr[0]->addr;
  	      	$DeliveryPackingCode->created_at = date('Y-m-d H:i:s');
  	        $DeliveryPackingCode->save();
  	      }

  	     foreach ($rsDelivery as $key => $value) {
    	     	$DeliveryPacking = new \App\Models\Backend\DeliveryPacking;
    	     	$DeliveryPacking->packing_code_id_fk = $DeliveryPackingCode->id;
            $DeliveryPacking->packing_code = "P1".sprintf("%05d",$DeliveryPackingCode->id) ;
    	     	$DeliveryPacking->delivery_id_fk = @$value->id;
    	     	$DeliveryPacking->created_at = date('Y-m-d H:i:s');
  	        $DeliveryPacking->save();

  	     }



         // เพื่อเอาไว้ไปทำตารางเบิก
         DB::update(" UPDATE
            db_delivery
            Inner Join db_delivery_packing ON db_delivery.id = db_delivery_packing.delivery_id_fk
            SET
            db_delivery.packing_code=
            db_delivery_packing.packing_code_id_fk ");



        return redirect()->to(url("backend/delivery?select_addr=".$DeliveryPackingCode->id."&"));

        // return redirect()->to(url("backend/delivery/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));

      }elseif(isset($request->save_select_addr)){

      		// $DeliveryPackingCode = \App\Models\Backend\DeliveryPackingCode::find($request->id);
      		// $DeliveryPackingCode->addr_id = $request->addr;
      		// $DeliveryPackingCode->save();

          // DB::select(" INSERT INTO `customers_addr_sent` (`customer_id`, `prefix_name`, `first_name`, `last_name`, `house_no`, `house_name`, `moo`, `zipcode`, `soi`, `amphures_id_fk`, `district_id_fk`, `road`, `province_id_fk`, `from_table`, `from_table_id`, `receipt_no`) VALUES ('1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1') ");

          // $rs = DB::select(" SELECT packing_code FROM customers_addr_sent WHERE id=".$request->id." ");

          // if(@$request->addr!="" &&  @$rs[0]->packing_code!=""){
          //   DB::select(" UPDATE customers_addr_sent SET addr_id2=".$request->addr." WHERE (packing_code='".@$rs[0]->packing_code."'); ");
          // }

          $receipt_no = explode(",",$request->receipt_no);
          $arr = [];
          for ($i=0; $i < sizeof($receipt_no); $i++) {
              array_push($arr, "'".$receipt_no[$i]."'");
          }
          $arr = implode(",",$arr);

          $rs = DB::select(" SELECT * FROM customers_addr_sent WHERE id='".$request->id."' ");
          // dd($rs);

          // $DeliveryPackingCode = \App\Models\Backend\DeliveryPackingCode::find(@$rs[0]->packing_code);
          // $DeliveryPackingCode->addr_id = @$rs[0]->id;
          // $DeliveryPackingCode->save();

          // Clear ก่อน
          DB::select(" UPDATE customers_addr_sent SET id_choose=0 WHERE receipt_no in ($arr)  ");


          if(@$request->id!="" ){
            DB::select(" UPDATE customers_addr_sent SET id_choose=1 WHERE id='".$request->id."' ");
          }

      		return redirect()->to(url("backend/delivery"));

      }elseif(isset($request->save_select_addr_edit)){

          // dd($request->all());
          // dd($request->id);

          $receipt_no = explode(",",$request->receipt_no);
          $arr = [];
          for ($i=0; $i < sizeof($receipt_no); $i++) {
              array_push($arr, "'".$receipt_no[$i]."'");
          }
          $arr = implode(",",$arr);

          $rs = DB::select(" SELECT * FROM customers_addr_sent WHERE id='".$request->id."' ");
          // dd($rs);

      		// $DeliveryPackingCode = \App\Models\Backend\DeliveryPackingCode::find(@$rs[0]->packing_code);
      		// $DeliveryPackingCode->addr_id = @$rs[0]->id;
      		// $DeliveryPackingCode->save();

          // Clear ก่อน
          DB::select(" UPDATE customers_addr_sent SET id_choose=0 WHERE receipt_no in ($arr)  ");


          if(@$request->id!="" ){
            DB::select(" UPDATE customers_addr_sent SET id_choose=1 WHERE id='".$request->id."' ");
          }

      		return redirect()->to(url("backend/delivery"));

      }else{
        return $this->form();
      }

    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Delivery::find($id);
       $Province = DB::select(" select * from dataset_provinces ");

       $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.delivery.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id, 'Province'=>$Province,'Customer'=>$Customer,
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
            $sRow = \App\Models\Backend\Delivery::find($id);
          }else{
            $sRow = new \App\Models\Backend\Delivery;
          }

          $sRow->receipt    = request('receipt');
          $sRow->customer_id    = request('customer_id');
          $sRow->tel    = request('tel');
          $sRow->province_id_fk    = request('province_id_fk');
          $sRow->delivery_date    = request('delivery_date');

          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/delivery/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\DeliveryController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // $sRow = \App\Models\Backend\Delivery::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req)
    {

        if(!empty($req->business_location_id_fk) && $req->business_location_id_fk > 0 ){
            $business_location_id = " and db_delivery.business_location_id =  ".$req->business_location_id_fk ;
        }else{
            $business_location_id = "";
        }

        if(!empty($req->branch_id_fk) && $req->branch_id_fk > 0 ){
            $branch_id_fk = " and db_delivery.branch_id_fk =  ".$req->branch_id_fk ;
        }else{
            $branch_id_fk = "";
        }

        if(!empty($req->receipt)){
            $receipt = " and db_delivery.receipt =  '".$req->receipt."'" ;
        }else{
            $receipt = "";
        }

        if(!empty($req->customer_id_fk)){
            $customer_id_fk = " and db_delivery.customer_id =  '".$req->customer_id_fk."'" ;
        }else{
            $customer_id_fk = "";
        }

        if(!empty($req->bill_sdate) && !empty($req->bill_edate)){
           $delivery_date = " and date(db_delivery.delivery_date) BETWEEN '".$req->bill_sdate."' AND '".$req->bill_edate."'  " ;
        }else{
           $delivery_date = "";
        }

        // return $receipt;

      // $sTable = \App\Models\Backend\Delivery::search()->where('status_pack','0')->where('approver','NULL')->orderBy('id', 'asc');
      $sTable = DB::select(" 

        SELECT * from db_delivery  
        WHERE status_pack=0 AND approver=0 

        $business_location_id
        $branch_id_fk
        $receipt
        $customer_id_fk
        $delivery_date


        ");


      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('delivery_date', function($row) {
          $d = strtotime($row->delivery_date);
          return date("d/m/", $d).(date("Y", $d)+543);
      })
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
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }




}
