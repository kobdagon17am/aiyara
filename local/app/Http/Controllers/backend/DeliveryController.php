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

 // ไม่ได้ทำตรงนี้แล้ว ไปทำที่ D:\wamp64\www\aiyara\local\app\Http\Controllers\backend\FrontstoreController.php
      /*
      DB::select("
          INSERT IGNORE INTO db_delivery
          ( orders_id_fk,receipt, customer_id, business_location_id,branch_id_fk , delivery_date, billing_employee, created_at,list_type,shipping_price)
          SELECT id,code_order,customers_id_fk,business_location_id_fk,branch_id_fk,created_at,action_user,now(),2,shipping_price 
          FROM db_orders where code_order<>'' AND delivery_location<>0 AND approve_status in(2,4) AND check_press_save=2 ; 
        ");
        */


   // Start => นำเข้า manaul  
/* 
  DB::select("
          INSERT IGNORE INTO db_delivery
          ( orders_id_fk,receipt, customer_id, business_location_id,branch_id_fk , delivery_date, billing_employee, created_at,list_type,shipping_price,total_price)
          SELECT id,code_order,customers_id_fk,business_location_id_fk,branch_id_fk,created_at,action_user,now(),2,shipping_price,
            (SUM(
              (CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) +
              (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
              (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
              (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
              (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) +
              (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END) 
              ))
          FROM db_orders 
          WHERE code_order<>'' AND delivery_location<>0 AND approve_status in(2,4) AND check_press_save=2

          GROUP BY db_orders.code_order

        ");

         DB::select("
              UPDATE
              db_delivery
              SET
              db_delivery.total_price=
              (
              SELECT
              (SUM(
              (CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) +
              (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
              (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
              (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
              (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) +
              (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END) 
              ))

              from db_orders  WHERE db_orders.code_order=db_delivery.receipt
              GROUP BY db_delivery.receipt
              ) 

        ");

         $sDelivery = DB::select("
                    SELECT id,delivery_location
                    FROM db_orders 
                    WHERE code_order<>'' AND delivery_location<>0 AND approve_status in(2,4) AND check_press_save=2
                    GROUP BY db_orders.code_order
                  ");


         foreach ($sDelivery as $key => $vd) {
              $this->fncUpdateDeliveryAddress($vd->id);
         }

         // สิ้นสุดการนำเข้าแบบ manual 
  */



      // รายที่ยังไม่อนุมัติ และ รอจัดส่ง และ ไม่ได้รอส่งไปสาขาอื่น
      // $receipt = \App\Models\Backend\Delivery::where('approver','NULL')->get();
        $receipt = DB::select(" select receipt from `db_delivery` where approver is null ; ");
        // dd($sDelivery);
        $sPacking = \App\Models\Backend\DeliveryPackingCode::where('status_delivery','<>','2')->get();

        $User_branch_id = \Auth::user()->branch_id_fk;
        $sBranchs = \App\Models\Backend\Branchs::get();
        $Warehouse = \App\Models\Backend\Warehouse::get();
        $Zone = \App\Models\Backend\Zone::get();
        $Shelf = \App\Models\Backend\Shelf::get();

        $sBusiness_location = \App\Models\Backend\Business_location::get();

        $shipping_cost = DB::select(" SELECT purchase_amt FROM `dataset_shipping_cost` WHERE shipping_type_id=1 ; ");
        $shipping_cost= $shipping_cost[0]->purchase_amt;
        // dd($shipping_cost);

      $sProvince = DB::select(" select *,name_th as province_name from dataset_provinces order by name_th ");
      $sAmphures = DB::select(" select *,name_th as amphur_name from dataset_amphures order by name_th ");
      $sTambons = DB::select(" select *,name_th as tambon_name from dataset_districts order by name_th ");
      

        return View('backend.delivery.index')->with(
          array(
             'receipt'=>$receipt,
             'sBranchs'=>$sBranchs,
             'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,
             'User_branch_id'=>$User_branch_id,
             'sBusiness_location'=>$sBusiness_location,
             'sPacking'=>$sPacking,
             'shipping_cost'=>$shipping_cost,
             'sProvince'=>$sProvince,
             'sAmphures'=>$sAmphures,
             'sTambons'=>$sTambons,
          ) );


    }



   public function fncUpdateDeliveryAddress($id)
    {
              
              $sRow = \App\Models\Backend\Frontstore::find($id);
              // dd($sRow->delivery_location);
              if(@$sRow->delivery_location==0){
                DB::select(" UPDATE `db_orders` SET invoice_code=code_order WHERE (`id`=".$sRow->id.") ");
                DB::select(" DELETE FROM `db_delivery` WHERE (`orders_id_fk`=".$sRow->id.") ");
              }


              if($sRow->check_press_save==2 && $sRow->approve_status>0  && $sRow->id!='' && @$sRow->delivery_location>0 ){

                       DB::select("
                        INSERT IGNORE INTO db_delivery
                        ( orders_id_fk,receipt, customer_id, business_location_id,branch_id_fk , delivery_date, billing_employee, created_at,list_type,shipping_price,total_price)
                        SELECT id,code_order,customers_id_fk,business_location_id_fk,branch_id_fk,created_at,action_user,now(),2,shipping_price,
                        (SUM(
                        (CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) +
                        (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
                        (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
                        (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
                        (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) +
                        (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
                        ))
                        FROM db_orders WHERE (`id`=".$sRow->id.") AND delivery_location <> 0 ;
                      ");


// Clear ก่อน ค่อย อัพเดต ใส่ตามเงื่อนไขทีหลัง
                      DB::select(" UPDATE db_delivery
                          SET
                          recipient_name = '',
                          addr_send = '',
                          postcode = '',
                          mobile = '',
                          province_id_fk = '',
                          province_name = '',
                          shipping_price = '".$sRow->shipping_price."',
                          delivery_date = now() ,
                          set_addr_send_this = '0'
                          where orders_id_fk = '".$sRow->id."'

                         ");

                      //delivery_location = ที่อยู่ผู้รับ>0=รับสินค้าด้วยตัวเอง|1=ที่อยู่ตามบัตร ปชช.>customers_address_card|2=ที่อยู่จัดส่งไปรษณีย์หรือที่อยู่ตามที่ลงทะเบียนไว้ในระบบ>customers_detail|3=ที่อยู่กำหนดเอง>customers_addr_frontstore|4=จัดส่งพร้อมบิลอื่น|5=ส่งแบบพิเศษ/พรีเมี่ยม

                      if(@$sRow->delivery_location==1){

                          $addr = DB::select(" SELECT
                                      customers_address_card.id,
                                      customers_address_card.customer_id,
                                      customers_address_card.card_house_no,
                                      customers_address_card.card_house_name,
                                      customers_address_card.card_moo,
                                      customers_address_card.card_zipcode,
                                      customers_address_card.card_soi,
                                      customers_address_card.created_at,
                                      customers_address_card.updated_at,
                                      customers_address_card.card_province_id_fk,
                                      dataset_provinces.name_th AS provname,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_districts.name_th AS tamname,
                                      customers.prefix_name,
                                      customers.first_name,
                                      customers.last_name
                                      FROM
                                      customers_address_card
                                      Left Join dataset_provinces ON customers_address_card.card_province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_address_card.card_amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_address_card.card_district_id_fk = dataset_districts.id
                                      Left Join customers ON customers_address_card.customer_id = customers.id
                                      where customers_address_card.customer_id = ".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)."

                            ");

                            if(@$addr){


                                        foreach ($addr as $key => $v) {

                                          @$address = @$v->card_house_no." ". @$v->card_house_name." ". @$v->card_moo."";
                                          @$address .= @$v->card_soi." ". @$v->card_road;
                                          @$address .= ", ต.". @$v->tamname. " ";
                                          @$address .= ", อ.". @$v->ampname;
                                          @$address .= ", จ.". @$v->provname;

                                          @$recipient_name = @$v->prefix_name.@$v->first_name.' '.@$v->last_name;

                                          if(!empty(@$v->tamname) && !empty(@$v->ampname) && !empty(@$v->provname)){
                                          }else{
                                              @$address = null;
                                          }

                                            DB::select(" UPDATE db_delivery
                                            SET
                                            recipient_name = '".@$recipient_name."',
                                            addr_send = '".@$address."',
                                            postcode = '".@$v->card_zipcode."',
                                            province_id_fk = '".@$v->card_province_id_fk."',
                                            province_name = '".@$v->province_name."',
                                            set_addr_send_this = '1'
                                            where orders_id_fk = '".$sRow->id."'

                                           ");
                                        }

                            }

                      }



                      if(@$sRow->delivery_location==2){

                          $addr = DB::select("
                            SELECT
                                      customers_detail.customer_id,
                                      customers_detail.house_no,
                                      customers_detail.house_name,
                                      customers_detail.moo,
                                      customers_detail.zipcode,
                                      customers_detail.soi,
                                      customers_detail.road,
                                      customers_detail.province_id_fk,
                                      customers_detail.tel_mobile,
                                      customers_detail.tel_home,
                                      customers.prefix_name,
                                      customers.first_name,
                                      customers.last_name,
                                      dataset_provinces.name_th AS provname,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_districts.name_th AS tamname
                                      FROM
                                      customers_detail
                                      Left Join customers ON customers_detail.customer_id = customers.id
                                      Left Join dataset_provinces ON customers_detail.province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_detail.amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_detail.district_id_fk = dataset_districts.id
                                      WHERE customers_detail.customer_id = ".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)."

                               ");

                           if(@$addr){
                              foreach ($addr as $key => $v) {

                                  @$address = @$v->house_no." ". @$v->house_name." ". @$v->moo." ". @$v->soi." ". @$v->road." ";
                                  @$address .= ", ต.". @$v->tamname. " ";
                                  @$address .= ", อ.". @$v->ampname;
                                  @$address .= ", จ.". @$v->provname;

                                  if(!empty(@$v->tamname) && !empty(@$v->ampname) && !empty(@$v->provname)){
                                  }else{
                                      @$address = null;
                                  }

                                  if(!empty(@$v->tel_mobile)){
                                      $tel = 'Tel. '. @$v->tel_mobile . (@$v->tel_home?', '.@$v->tel_home:'') ;
                                  }else{
                                      $tel = '';
                                  }

                                  @$recipient_name = @$v->prefix_name.@$v->first_name.' '.@$v->last_name;

                                  DB::select(" UPDATE db_delivery
                                  SET
                                  recipient_name = '".@$recipient_name."',
                                  addr_send = '".@$address."',
                                  postcode = '".@$v->zipcode."',
                                  mobile = '".@$tel."',
                                  province_id_fk = '".@$v->province_id_fk."',
                                  province_name = '".@$v->provname."',
                                  set_addr_send_this = '1'
                                  where orders_id_fk = '".$sRow->id."'

                                 ");
                              }

                            }
                      }



                      if(@$sRow->delivery_location==3){

                          $addr = DB::select("select customers_addr_frontstore.* ,dataset_provinces.name_th as provname,
                            dataset_amphures.name_th as ampname,dataset_districts.name_th as tamname,dataset_provinces.id as province_id_fk
                            from customers_addr_frontstore
                            Left Join dataset_provinces ON customers_addr_frontstore.province_id_fk = dataset_provinces.id
                            Left Join dataset_amphures ON customers_addr_frontstore.amphur_code = dataset_amphures.id
                            Left Join dataset_districts ON customers_addr_frontstore.tambon_code = dataset_districts.id
                            WHERE
                            frontstore_id_fk in (".@$sRow->id.") ;");

                           if(@$addr){
                              foreach ($addr as $key => $v) {

                                  @$address = @$v->addr_no;
                                  @$address .= ", ต.". @$v->tamname. " ";
                                  @$address .= ", อ.". @$v->ampname;
                                  @$address .= ", จ.". @$v->provname;

                                  if(!empty(@$v->tamname) && !empty(@$v->ampname) && !empty(@$v->provname)){
                                  }else{
                                      @$address = null;
                                  }

                                  if(!empty(@$v->tel)){
                                      $tel = 'Tel. '. @$v->tel . (@$v->tel_home?', '.@$v->tel_home:'') ;
                                  }else{
                                      $tel = '';
                                  }

                                  DB::select(" UPDATE db_delivery
                                  SET
                                  recipient_name = '".@$v->recipient_name."',
                                  addr_send = '".@$address."',
                                  postcode = '".@$v->zip_code."',
                                  mobile = '".@$tel."',
                                  province_id_fk = '".@$v->province_id_fk."',
                                  province_name = '".@$v->provname."',
                                  set_addr_send_this = '1'
                                  where orders_id_fk = '".$sRow->id."'

                                 ");
                              }

                            }
                      }


              }

    }




 public function create()
    {

      // $Province = DB::select(" select * from dataset_provinces ");

      // $Customer = DB::select(" select * from customers limit 100 ");
      // return View('backend.delivery.form')->with(
      //   array(
      //      'Customer'=>$Customer,'Province'=>$Province
      //   ) );
    }


    public function store(Request $request)
    {

        // dd($request->all());

        if(isset($request->update_delivery_custom)){

            $ch = DB::select("select * from customers_addr_frontstore where frontstore_id_fk=".($request->customers_addr_frontstore_id?$request->customers_addr_frontstore_id:0)." ");
            // dd(count($ch));
            if(count($ch)==0){

              DB::insert(" INSERT INTO customers_addr_frontstore (frontstore_id_fk, customer_id,customers_id_fk, recipient_name, addr_no, province_id_fk , amphur_code, tambon_code, zip_code, tel,tel_home, created_at)
                  VALUES
                  ('".$request->customers_addr_frontstore_id."',
                   '".$request->customer_id."',
                   '".$request->customer_id."',
                   '".$request->delivery_cusname."',
                    '".$request->delivery_addr."',
                     '".$request->delivery_province."',
                     '".$request->delivery_amphur."',
                     '".$request->delivery_tambon."',
                     '".$request->delivery_zipcode."',
                     '".$request->delivery_tel."',
                     '".$request->delivery_tel_home."',
                     now()
                  )
                ");

            }else{

               // dd($request->all());

               DB::select(" UPDATE customers_addr_frontstore
                SET recipient_name = '".$request->delivery_cusname."',
                addr_no = '".$request->delivery_addr."',
                province_id_fk  = '".$request->delivery_province."',
                amphur_code = '".$request->delivery_amphur."',
                tambon_code = '".$request->delivery_tambon."',
                zip_code = '".$request->delivery_zipcode."',
                tel = '".$request->delivery_tel."',
                tel_home = '".$request->delivery_tel_home."',
                updated_at = now() where frontstore_id_fk=".($request->customers_addr_frontstore_id?$request->customers_addr_frontstore_id:0)."
              ");

            }


               $addr = DB::select("select customers_addr_frontstore.* ,dataset_provinces.name_th as provname,
                            dataset_amphures.name_th as ampname,dataset_districts.name_th as tamname,dataset_provinces.id as province_id_fk
                            from customers_addr_frontstore
                            Left Join dataset_provinces ON customers_addr_frontstore.province_id_fk = dataset_provinces.id
                            Left Join dataset_amphures ON customers_addr_frontstore.amphur_code = dataset_amphures.id
                            Left Join dataset_districts ON customers_addr_frontstore.tambon_code = dataset_districts.id
                            WHERE
                            frontstore_id_fk in (".($request->customers_addr_frontstore_id?$request->customers_addr_frontstore_id:0).") ;");

                           if(@$addr){
                              foreach ($addr as $key => $v) {

                                  @$address = @$v->addr_no;
                                  @$address .= ", ต.". @$v->tamname. " ";
                                  @$address .= ", อ.". @$v->ampname;
                                  @$address .= ", จ.". @$v->provname;

                                  if(!empty(@$v->tel)){
                                      $tel = 'Tel. '. @$v->tel . (@$v->tel_home?', '.@$v->tel_home:'') ;
                                  }else{
                                      $tel = '';
                                  }

                                  DB::select(" UPDATE db_delivery  
                                  SET 
                                  recipient_name = '".@$v->recipient_name."',
                                  addr_send = '".@$address."',
                                  postcode = '".@$v->zip_code."',
                                  mobile = '".@$tel."',
                                  province_id_fk = '".@$v->province_id_fk."',
                                  province_name = '".@$v->provname."',
                                  set_addr_send_this = '1'
                                  where orders_id_fk = '".($request->customers_addr_frontstore_id?$request->customers_addr_frontstore_id:0)."'

                                 ");
                              }

                            }


            return redirect()->to(url("backend/delivery"));


       }else if(isset($request->update_delivery_custom_from_pick_pack)){


          $ch = DB::select("select * from customers_addr_frontstore where frontstore_id_fk=".($request->customers_addr_frontstore_id?$request->customers_addr_frontstore_id:0)." ");
                    // dd(count($ch));
                    if(count($ch)==0){

                      DB::insert(" INSERT INTO customers_addr_frontstore (frontstore_id_fk, customer_id,customers_id_fk, recipient_name, addr_no, province_id_fk , amphur_code, tambon_code, zip_code, tel,tel_home, created_at)
                          VALUES
                          ('".$request->customers_addr_frontstore_id."',
                           '".$request->customer_id."',
                           '".$request->customer_id."',
                           '".$request->delivery_cusname."',
                            '".$request->delivery_addr."',
                             '".$request->delivery_province."',
                             '".$request->delivery_amphur."',
                             '".$request->delivery_tambon."',
                             '".$request->delivery_zipcode."',
                             '".$request->delivery_tel."',
                             '".$request->delivery_tel_home."',
                             now()
                          )
                        ");

                    }else{

                       // dd($request->all());

                       DB::select(" UPDATE customers_addr_frontstore
                        SET recipient_name = '".$request->delivery_cusname."',
                        addr_no = '".$request->delivery_addr."',
                        province_id_fk  = '".$request->delivery_province."',
                        amphur_code = '".$request->delivery_amphur."',
                        tambon_code = '".$request->delivery_tambon."',
                        zip_code = '".$request->delivery_zipcode."',
                        tel = '".$request->delivery_tel."',
                        tel_home = '".$request->delivery_tel_home."',
                        updated_at = now() where frontstore_id_fk=".($request->customers_addr_frontstore_id?$request->customers_addr_frontstore_id:0)."
                      ");

                    }


                       $addr = DB::select("select customers_addr_frontstore.* ,dataset_provinces.name_th as provname,
                                    dataset_amphures.name_th as ampname,dataset_districts.name_th as tamname,dataset_provinces.id as province_id_fk
                                    from customers_addr_frontstore
                                    Left Join dataset_provinces ON customers_addr_frontstore.province_id_fk = dataset_provinces.id
                                    Left Join dataset_amphures ON customers_addr_frontstore.amphur_code = dataset_amphures.id
                                    Left Join dataset_districts ON customers_addr_frontstore.tambon_code = dataset_districts.id
                                    WHERE
                                    frontstore_id_fk in (".($request->customers_addr_frontstore_id?$request->customers_addr_frontstore_id:0).") ;");

                                   if(@$addr){
                                      foreach ($addr as $key => $v) {

                                          @$address = @$v->addr_no;
                                          @$address .= ", ต.". @$v->tamname. " ";
                                          @$address .= ", อ.". @$v->ampname;
                                          @$address .= ", จ.". @$v->provname;

                                          if(!empty(@$v->tel)){
                                              $tel = 'Tel. '. @$v->tel . (@$v->tel_home?', '.@$v->tel_home:'') ;
                                          }else{
                                              $tel = '';
                                          }

                                          DB::select(" UPDATE db_delivery  
                                          SET 
                                          recipient_name = '".@$v->recipient_name."',
                                          addr_send = '".@$address."',
                                          postcode = '".@$v->zip_code."',
                                          mobile = '".@$tel."',
                                          province_id_fk = '".@$v->province_id_fk."',
                                          province_name = '".@$v->provname."',
                                          set_addr_send_this = '1'
                                          where orders_id_fk = '".($request->customers_addr_frontstore_id?$request->customers_addr_frontstore_id:0)."'

                                         ");
                                      }

                                    }


                    return redirect()->to(url("backend/pick_pack"));



       }else if(isset($request->save_to_packing)){

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
  	      	$DeliveryPackingCode->address_sent_id_fk = @$rsDeliveryAddr[0]->addr;
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
            db_delivery.packing_code=db_delivery_packing.packing_code_id_fk ");

         // รหัสนี้สร้างใหม่เพื่อเอาไว้อ้างอิงให้มันชัดเจนยิ่งขึ้น
         $rsDelivery = DB::select(" SELECT * FROM db_delivery WHERE id in ($arr)  ");
         foreach ($rsDelivery as $key => $value) {

              $pc = "P1".sprintf("%05d",$value->packing_code) ;
              DB::select(" UPDATE db_delivery SET packing_code_desc='$pc' WHERE id=$value->id  ");
            
         }

        return redirect()->to(url("backend/delivery"));

      }elseif(isset($request->save_select_addr)){

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
      //  $sRow = \App\Models\Backend\Delivery::find($id);
      //  $Province = DB::select(" select * from dataset_provinces ");

      //  $Customer = DB::select(" select * from customers limit 100 ");
      // return View('backend.delivery.form')->with(
      //   array(
      //      'sRow'=>$sRow, 'id'=>$id, 'Province'=>$Province,'Customer'=>$Customer,
      //   ) );
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
        WHERE status_pack=0 AND approver=0 AND status_delivery<>1 AND status_pick_pack<>1

        $business_location_id
        $branch_id_fk
        $receipt
        $customer_id_fk
        $delivery_date

        order by updated_at desc


        ");


      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // ->addColumn('delivery_date', function($row) {
      //     $d = strtotime($row->delivery_date);
      //     return date("d/m/", $d).(date("Y", $d)+543);
      // })
      ->addColumn('customer_name', function($row) {
      	if(@$row->customer_id!=''){
         	$Customer = DB::select(" select user_name,prefix_name,first_name,last_name from customers where id=".@$row->customer_id." ");
        	return @$Customer[0]->user_name.' : '.@$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
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

      ->addColumn('id2', function($row) {
             $total_price = @$row->total_price?@$row->total_price:0;
             $shipping_price = @$row->shipping_price?@$row->shipping_price:0;
             return $row->id.':'.$total_price.':'.$shipping_price;
      })

      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }




}
