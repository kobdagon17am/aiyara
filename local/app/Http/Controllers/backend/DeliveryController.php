<?php

namespace App\Http\Controllers\Backend;

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

      $sDelivery = \App\Models\Backend\Delivery::where('approver','NULL')->get();
      // dd($sDelivery);

      $arr = [];
      foreach ($sDelivery as $key => $value) {
        array_push($arr,$value->id);
      }
      $sDeliveryPacking = \App\Models\Backend\DeliveryPacking::where('approver','NULL');

      // ขาย Frontend ของน้องกอล์ฟ
      DB::select(" 
          INSERT IGNORE INTO db_delivery 
          ( receipt, customer_id, business_location_id , delivery_date, billing_employee, created_at,list_type)
          SELECT
          orders.code_order,orders.customer_id,orders.business_location_id,orders.created_at,orders.id_admin_check,now(),1
          FROM orders
          WHERE
          orders.orderstatus_id in (4,5); ");

      // ขายหน้าร้าน (หลังบ้าน)
      DB::select(" 
          INSERT IGNORE INTO db_delivery 
          ( receipt, customer_id, business_location_id , delivery_date, billing_employee, created_at,list_type)
          SELECT invoice_code,customers_id_fk,business_location_id_fk,created_at,action_user,now(),2 FROM db_frontstore ; ");

      // นำเข้าที่อยู่ในการจัดส่ง
      // `addr_type` int(1) DEFAULT '0' COMMENT 'ที่อยู่ผู้รับ>0=รัยสินค้าที่สาขาด้วยตนเอง,
      // 1=ที่อยู่ตามบัตร ปชช.>customers_address_card,
      // 2=ที่อยู่ตามที่ลงทะเบียนในระบบ>customers_detail,
      // 3>ที่อยู่กำหนดเอง>customers_addr_frontstore',

        // DB::select(" TRUNCATE customers_addr_sent; ");

        // 1=ที่อยู่ตามบัตร ปชช.>customers_address_card
        $address_card = DB::select(" 

                SELECT
                customers_address_card.*,
                dataset_provinces.name_th AS provname,
                dataset_amphures.name_th AS ampname,
                dataset_districts.name_th AS tamname,
                customers.prefix_name,
                customers.first_name,
                customers.last_name
                FROM
                customers_address_card
                Left Join dataset_provinces ON customers_address_card.card_province = dataset_provinces.id
                Left Join dataset_amphures ON customers_address_card.card_district = dataset_amphures.id
                Left Join dataset_districts ON customers_address_card.card_district_sub = dataset_districts.id
                Left Join customers ON customers_address_card.customer_id = customers.id

               ");

        foreach ($address_card as $key => $value) {

              $d = array(
                 "customer_id"=>@$value->customer_id,
                 "recipient_name"=>@$value->prefix_name.@$value->first_name.' '.@$value->last_name,
                 "house_no"=>@$value->card_house_no,
                 "house_name"=>@$value->card_house_name,
                 "moo"=>@$value->moo,
                 "road"=>@$value->road,
                 "soi"=>@$value->soi,
                 "district_id"=>@$value->card_district,
                 "district"=>@$value->ampname,
                 "district_sub_id"=>@$value->card_district_sub,
                 "district_sub"=>@$value->tamname,
                 "province_id"=>@$value->card_province,
                 "province"=>@$value->provname,
                 "zipcode"=>@$value->card_zipcode,
                 "from_table"=>'customers_address_card',
                 "from_table_id"=>@$value->id,
                 "addr_type"=>1,
                 "created_at"=>now());
               AddressSent::insertData($d);

        }

        // 2=ที่อยู่ตามที่ลงทะเบียนในระบบ>customers_detail,
        $customers_detail = DB::select("
                SELECT
                  customers_detail.*,
                  dataset_provinces.name_th AS provname,
                  dataset_amphures.name_th AS ampname,
                  dataset_districts.name_th AS tamname,                  
                  customers.prefix_name,
                  customers.first_name,
                  customers.last_name
                  FROM
                  customers_detail
                  Left Join dataset_provinces ON customers_detail.province = dataset_provinces.id
                  Left Join dataset_amphures ON customers_detail.district = dataset_amphures.id
                  Left Join dataset_districts ON customers_detail.district_sub = dataset_districts.id                  
                  Left Join customers ON customers_detail.customer_id = customers.id
                   ");

        foreach ($customers_detail as $key => $value) {

              $d = array(
                 "customer_id"=>@$value->customer_id,
                 "recipient_name"=>@$value->prefix_name.@$value->first_name.' '.@$value->last_name,
                 "house_no"=>@$value->house_no,
                 "house_name"=>@$value->house_name,
                 "moo"=>@$value->moo,
                 "road"=>@$value->road,
                 "soi"=>@$value->soi,
                 "district_id"=>@$value->district,
                 "district"=>@$value->ampname,
                 "district_sub_id"=>@$value->district_sub,
                 "district_sub"=>@$value->tamname,
                 "province_id"=>@$value->province,
                 "province"=>@$value->provname,
                 "zipcode"=>@$value->zipcode,
                 "from_table"=>'customers_detail',
                 "from_table_id"=>@$value->id,
                 "addr_type"=>2,
                 "created_at"=>now());
               AddressSent::insertData($d);

        }


        // 3>ที่อยู่กำหนดเอง>customers_addr_frontstore',
        $addr_frontstore = DB::select(" SELECT customers_addr_frontstore.*,db_frontstore.invoice_code from customers_addr_frontstore Left Join db_frontstore ON customers_addr_frontstore.frontstore_id_fk = db_frontstore.id ");

        foreach ($addr_frontstore as $key => $value) {

              $district = DB::select(" SELECT * from dataset_amphures where id=".@$value->amphur_code." ");
              $district_sub = DB::select(" SELECT * from dataset_districts where id=".@$value->tambon_code." ");
              $province = DB::select(" SELECT * from dataset_provinces where id=".@$value->province_code." ");

              $d = array(
                 "customer_id"=>@$value->customers_id_fk,
                 "recipient_name"=>@$value->recipient_name,
                 "house_no"=>@$value->addr_no,
                 "district_id"=>@$value->amphur_code,
                 "district"=>@$district[0]->name_th,
                 "district_sub_id"=>@$value->tambon_code,
                 "district_sub"=>@$district_sub[0]->name_th,
                 "province_id"=>@$value->province_code,
                 "province"=>@$province[0]->name_th,
                 "zipcode"=>@$value->zip_code,
                 "tel"=>@$value->tel,
                 "from_table"=>'customers_addr_frontstore',
                 "from_table_id"=>@$value->id,
                 "addr_type"=>3,
                 "receipt_no"=>@$value->invoice_code,
                 "created_at"=>now());
               AddressSent::insertData($d);
        }

     // DB::select(" UPDATE db_delivery SET billing_employee='1' WHERE  billing_employee is null ; ");

      return view('backend.delivery.index');
      
    }


 public function create()
    {

      $Province = DB::select(" select * from dataset_provinces ");

      $Customer = DB::select(" select * from customers ");
      return View('backend.delivery.form')->with(
        array(
           'Customer'=>$Customer,'Province'=>$Province
        ) );
    }


    public function store(Request $request)
    {
      // dd($request->all());
      
      if(isset($request->save_to_packing)){

      	$arr = implode(',', $request->row_id);

        DB::update(" UPDATE db_delivery SET status_pack='1',updated_at=now() WHERE id in ($arr)  ");

        $rsDelivery = DB::select(" SELECT * FROM db_delivery WHERE id in ($arr)  ");

        $rsDeliveryAddr = DB::select(" 
        	SELECT
    			customers_detail.id as addr
    			FROM
    			db_delivery
    			Left Join customers_detail ON db_delivery.customer_id = customers_detail.customer_id
    			WHERE db_delivery.id in ($arr) 
    			ORDER BY customers_detail.id limit 1  ");

  	      $DeliveryPackingCode = new \App\Models\Backend\DeliveryPackingCode;
  	      if( $DeliveryPackingCode ){
  	      	$DeliveryPackingCode->addr_id = $rsDeliveryAddr[0]->addr;
  	      	$DeliveryPackingCode->created_at = date('Y-m-d H:i:s');
  	        $DeliveryPackingCode->save();
  	      }

  	     foreach ($rsDelivery as $key => $value) {
    	     	$DeliveryPacking = new \App\Models\Backend\DeliveryPacking;
    	     	$DeliveryPacking->packing_code = $DeliveryPackingCode->id;
    	     	$DeliveryPacking->delivery_id_fk = @$value->id;
    	     	$DeliveryPacking->created_at = date('Y-m-d H:i:s');
  	        $DeliveryPacking->save();

            DB::select(" UPDATE customers_addr_sent SET packing_code=".$DeliveryPackingCode->id." WHERE (receipt_no='".$value->receipt."'); ");

  	     }



         // เพื่อเอาไว้ไปทำตารางเบิก
         DB::update(" UPDATE
            db_delivery
            Inner Join db_delivery_packing ON db_delivery.id = db_delivery_packing.delivery_id_fk
            SET
            db_delivery.packing_code=
            db_delivery_packing.packing_code ");



        return redirect()->to(url("backend/delivery?select_addr=".$DeliveryPackingCode->id."&"));

        // return redirect()->to(url("backend/delivery/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));

      }elseif(isset($request->save_select_addr)){

      		$DeliveryPackingCode = \App\Models\Backend\DeliveryPackingCode::find($request->id);
      		$DeliveryPackingCode->addr_id = $request->addr;
      		$DeliveryPackingCode->save();

          // DB::select(" INSERT INTO `customers_addr_sent` (`customer_id`, `prefix_name`, `first_name`, `last_name`, `house_no`, `house_name`, `moo`, `zipcode`, `soi`, `district`, `district_sub`, `road`, `province`, `from_table`, `from_table_id`, `receipt_no`) VALUES ('1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1') ");

          $rs = DB::select(" SELECT packing_code FROM customers_addr_sent WHERE id=".$request->id." ");

          if(@$request->addr!="" &&  @$rs[0]->packing_code!=""){
            DB::select(" UPDATE customers_addr_sent SET addr_id2=".$request->addr." WHERE (packing_code='".@$rs[0]->packing_code."'); ");
          }

      		return redirect()->to(url("backend/delivery"));
      
      }elseif(isset($request->save_select_addr_edit)){     

          // dd($request->all());
          // dd($request->id);
          $rs = DB::select(" SELECT * FROM customers_addr_sent WHERE receipt_no='".$request->receipt_no."' ");
          // dd($rs);

      		$DeliveryPackingCode = \App\Models\Backend\DeliveryPackingCode::find(@$rs[0]->packing_code);
      		$DeliveryPackingCode->addr_id = @$rs[0]->id;
      		$DeliveryPackingCode->save();

          // Clear ก่อน
          DB::select(" UPDATE customers_addr_sent SET id_choose=0 WHERE (packing_code='".@$rs[0]->packing_code."'); ");


          if(@$request->receipt_no!="" ){
            DB::select(" UPDATE customers_addr_sent SET id_choose=1 WHERE receipt_no='".$request->receipt_no."' ");
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

       $Customer = DB::select(" select * from customers ");
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

          $sRow->delivery_slip    = request('delivery_slip');
          $sRow->receipt    = request('receipt');
          $sRow->customer_id    = request('customer_id');
          $sRow->tel    = request('tel');
          $sRow->province_code    = request('province_code');
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

    public function Datatable(){
      $sTable = \App\Models\Backend\Delivery::search()->where('status_pack','0')->where('approver','NULL')->orderBy('id', 'asc');
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
