<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDO;

class FrontstoreController extends Controller
{

    public function index(Request $request)
    {

      $Frontstore = \App\Models\Backend\Frontstore::get();
      // dd($Frontstore);
      $sUser = DB::select(" select * from ck_users_admin ");
      $sApproveStatus = DB::select(" select * from dataset_approve_status where status=1 ");


      $Customer = DB::select(" select * from customers ");
      return View('backend.frontstore.index')->with(
        array(
           'Customer'=>$Customer,
           'sUser'=>$sUser,
           'sApproveStatus'=>$sApproveStatus,
        ) );
      
    }

 public function create()
    {
      $sUser = \App\Models\Backend\Permission\Admin::get();

      $Products = DB::select("SELECT products.id as product_id,
      products.product_code,
      (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
      FROM
      products_details
      Left Join products ON products_details.product_id_fk = products.id
      WHERE lang_id=1");

      $Customer = DB::select(" select * from customers ");
      $sPurchase_type = DB::select(" select * from dataset_orders_type where status=1 and lang_id=1 order by id limit 5");

      $sPay_type01 = DB::select(" select * from dataset_pay_type where id in(1,2,3,4) and status=1 ");
      $sPay_type02 = DB::select(" select * from dataset_pay_type where id in(1,3,5) and status=1 ORDER BY detail ");

      $sDistribution_channel = DB::select(" select * from dataset_distribution_channel where status=1  ");
      $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();

      $User_branch_id = \Auth::user()->branch_id_fk;

      $sBranchs = \App\Models\Backend\Branchs::get();
      
      $sBusiness_location = \App\Models\Backend\Business_location::get();

      $sFee = \App\Models\Backend\Fee::get();

      $aistockist = DB::select(" select * from customers_aistockist_agency where aistockist=1 ");
      $agency = DB::select(" select * from customers_aistockist_agency where agency=1 ");

      return View('backend.frontstore.form')->with(
        array(
           'Customer'=>$Customer,
           'sPurchase_type'=>$sPurchase_type,
           'sPay_type01'=>$sPay_type01,
           'sPay_type02'=>$sPay_type02,
           'sProductUnit'=>$sProductUnit,
           'sDistribution_channel'=>$sDistribution_channel,
           'Products'=>$Products,
           'sBusiness_location'=>$sBusiness_location,
           'sFee'=>$sFee,
           'sBranchs'=>$sBranchs,
           'User_branch_id'=>$User_branch_id,
           'aistockist'=>$aistockist,
           'agency'=>$agency,
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
      // dd($request->all());

      $sRow = \App\Models\Backend\Frontstore::find($id);
      // dd($sRow->customers_id_fk);
      $sCustomer = DB::select(" select * from customers where id=".$sRow->customers_id_fk." ");
      $CusName = ($sCustomer[0]->user_name." : ".$sCustomer[0]->prefix_name.$sCustomer[0]->first_name." ".$sCustomer[0]->last_name);

      $sBranchs = DB::select(" select * from branchs where id=".$sRow->branch_id_fk." ");
      $BranchName = $sBranchs[0]->b_name;

      $Purchase_type = DB::select(" select * from dataset_orders_type where id=".$sRow->purchase_type_id_fk." ");
      $PurchaseName = $Purchase_type[0]->orders_type;

      $CusAddrFrontstore = \App\Models\Backend\CusAddrFrontstore::where('frontstore_id_fk',$id)->get();
      $sUser = \App\Models\Backend\Permission\Admin::get();

      $Delivery_location = DB::select(" select id,txt_desc from dataset_delivery_location  ");

      // dd($sRow->purchase_type_id_fk);

      $Products = DB::select("

        SELECT products.id as product_id,
        products.product_code,
        (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
        FROM
        products_details
        Left Join products ON products_details.product_id_fk = products.id
        WHERE lang_id=1
        AND 
            (
              ".$sRow->purchase_type_id_fk." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 1), ',', -1)  OR 
              ".$sRow->purchase_type_id_fk." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 2), ',', -1) OR 
              ".$sRow->purchase_type_id_fk." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 3), ',', -1) OR 
              ".$sRow->purchase_type_id_fk." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 4), ',', -1) OR 
              ".$sRow->purchase_type_id_fk." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 5), ',', -1) 
            )        

      ");

      $Customer = DB::select(" select * from customers ");
        /* dataset_orders_type
        1 ทำคุณสมบัติ
        2 รักษาคุณสมบัติรายเดือน
        3 รักษาคุณสมบัติท่องเที่ยว
        4 เติม Ai-Stockist
        5 แลก Gift Voucher
        */
      if(!empty($sRow->purchase_type_id_fk) && $sRow->purchase_type_id_fk!=5) {
        $sPurchase_type = DB::select(" select * from dataset_orders_type where id<>5 and status=1 and lang_id=1 order by id limit 4");
        $sPay_type01 = DB::select(" select * from dataset_pay_type where id in(1,2,3,4) and status=1 ");
      }else{
        $sPurchase_type = DB::select(" select * from dataset_orders_type where status=1 and lang_id=1 order by id limit 5");
        $sPay_type01 = DB::select(" select * from dataset_pay_type where id=4 and status=1 ");
      }
		/* dataset_pay_type
		1	โอนชำระ
		2	บัตรเครดิต
		3	Ai-Cash
		4	Gift Voucher
		5	เงินสด
		*/
      $sPay_type02 = DB::select(" select * from dataset_pay_type where id in(1,3,5) and status=1 ORDER BY detail ");
      $sDistribution_channel = DB::select(" select * from dataset_distribution_channel where status=1  ");
      $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();

      $sProvince = DB::select(" select *,name_th as province_name from dataset_provinces order by name_th ");
      $sAmphures = DB::select(" select *,name_th as amphur_name from dataset_amphures order by name_th ");
      $sTambons = DB::select(" select *,name_th as tambon_name from dataset_districts order by name_th ");
      $sBusiness_location = \App\Models\Backend\Business_location::get();

      $sFee = \App\Models\Backend\Fee::get();

      $User_branch_id = \Auth::user()->branch_id_fk;
      // dd($User_branch_id);
      $sBranchs = DB::select(" select * from branchs where province_id_fk <> 0  ");
      // dd($sBranchs);

      $ThisCustomer = DB::select(" select * from customers where id=".$sRow->customers_id_fk." ");
      // dd($ThisCustomer[0]->user_name);
      $aistockist = DB::select(" select * from customers_aistockist_agency where aistockist=1 AND user_name <> '".$ThisCustomer[0]->user_name."' ");
      $agency = DB::select(" select * from customers_aistockist_agency where agency=1 AND user_name <> '".$ThisCustomer[0]->user_name."' ");


      $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total from db_frontstore_products_list WHERE frontstore_id_fk=$id GROUP BY frontstore_id_fk ");
      if($sFrontstoreDataTotal){
          $vat = floatval(@$sFrontstoreDataTotal[0]->total) - (floatval(@$sFrontstoreDataTotal[0]->total)/1.07) ;
          $product_value = str_replace(",","",floatval(@$sFrontstoreDataTotal[0]->total) - @$vat) ;
          DB::select(" UPDATE db_frontstore SET product_value=".(@$product_value).",tax=".(@$vat).",sum_price=".@$sFrontstoreDataTotal[0]->total." WHERE id=$id ");
      }else{
          DB::select(" UPDATE db_frontstore SET product_value=0,tax=0,sum_price=0 WHERE id=$id ");
      }


      return View('backend.frontstore.form')->with(
        array(
           'sRow'=>$sRow,
           'Customer'=>$Customer,
           'sPurchase_type'=>$sPurchase_type,
           'sPay_type01'=>$sPay_type01,
           'sPay_type02'=>$sPay_type02,
           'sProductUnit'=>$sProductUnit,
           'sDistribution_channel'=>$sDistribution_channel,
           'Products'=>$Products,
           'sProvince'=>$sProvince,
           'sAmphures'=>$sAmphures,
           'sTambons'=>$sTambons,
           'Delivery_location'=>$Delivery_location,
           'CusAddrFrontstore'=>$CusAddrFrontstore,
           'sBusiness_location'=>$sBusiness_location,
           'sFee'=>$sFee,
           'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id,
           'aistockist'=>$aistockist,
           'agency'=>$agency,           
           'CusName'=>$CusName,           
           'BranchName'=>$BranchName,           
           'PurchaseName'=>$PurchaseName,           
        ) );
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
         if(isset($request->receipt_save_list)){

          // dd($request->all());

            $sRow = \App\Models\Backend\Frontstore::find($request->frontstore_id);

/*
P2102100001
P=Product
2102=year-month
1=business location
00001=running no.*/

              $branchs = DB::select("SELECT * FROM branchs where id=".$request->this_branch_id_fk."");
              // dd($branchs[0]->business_location_id_fk);

              $inv = DB::select(" select invoice_code,SUBSTR(invoice_code,2,2)as y,SUBSTR(invoice_code,4,2)as m,DATE_FORMAT(now(), '%y') as this_y,DATE_FORMAT(now(), '%m') as this_m from db_frontstore 
                WHERE SUBSTR(invoice_code,2,2)=DATE_FORMAT(now(), '%y') AND SUBSTR(invoice_code,4,2)=DATE_FORMAT(now(), '%m')
                order by invoice_code desc limit 1 ");
              if($inv){
                  $invoice_code = 'P'.date("ym").$branchs[0]->business_location_id_fk.sprintf("%05d",intval(substr($inv[0]->invoice_code,-5))+1);
              }else{
                  $invoice_code = 'P'.date("ym").$branchs[0]->business_location_id_fk.sprintf("%05d",1);
              }
              // dd($invoice_code);

              if($sRow->invoice_code==''){
                $sRow->invoice_code = $invoice_code;
              }

              
              $sFee = \App\Models\Backend\Fee::find($sRow->fee);

              if( $sRow->pay_type_id_fk==2 || $sRow->pay_type_id_fk_2 ==2 ){ 

                $fee_amt    = (str_replace(',','',request('sum_price')) - str_replace(',','',request('cash_price')))*(@$sFee->txt_value/100);

              }else{
                $fee_amt    = 0 ;
              }



              $sRow->pay_type_id_fk    = request('pay_type_id_fk');
              $sRow->pay_type_id_fk_2    = request('pay_type_id_fk_2');

              $sRow->aistockist    = request('aistockist');
              $sRow->agency    = request('agency');
              $sRow->note    = request('note');

              $sRow->delivery_location    = request('delivery_location');
              $sRow->cash_price    = str_replace(',','',request('cash_price'));
              $sRow->transfer_price    = str_replace(',','',request('transfer_price'));
              $sRow->shipping_price    = str_replace(',','',request('shipping_price'));
              $sRow->fee_amt    =  $fee_amt ;
              $sRow->total_price    =  str_replace(',','',request('sum_price'))+str_replace(',','',request('shipping_price'));

              $sRow->action_user = \Auth::user()->id;
              $sRow->action_date = date('Y-m-d H:i:s');
              $sRow->updated_at = date('Y-m-d H:i:s');
              $sRow->save();    


             if(@$request->delivery_location  == 0 || @$request->delivery_location  == 4 ){
                  $sRow->sentto_branch_id    = request('sentto_branch_id');
                   DB::select("UPDATE `db_frontstore` SET `address_sent_id_fk`='0' WHERE (`id`='".$request->frontstore_id."')");
              }
  
             if(@$request->delivery_location==1){

                          DB::select(" DELETE FROM customers_addr_sent WHERE receipt_no='".@$request->invoice_code."' ");  

                          $addr = DB::select("SELECT
                                      customers_address_card.id,
                                      customers_address_card.customer_id,
                                      customers_address_card.card_house_no,
                                      customers_address_card.card_house_name,
                                      customers_address_card.card_moo,
                                      customers_address_card.card_zipcode,
                                      customers_address_card.card_soi,
                                      customers_address_card.card_district,
                                      customers_address_card.card_district_sub,
                                      customers_address_card.card_road,
                                      customers_address_card.card_province,
                                      customers_address_card.created_at,
                                      customers_address_card.updated_at,
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
                                      where customers_address_card.customer_id = ".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)."
                                 ");


                            $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (customer_id, first_name, house_no, zipcode, district, district_sub, province, from_table, from_table_id, receipt_no) VALUES ('".@$request->customers_id_fk."', '".@$addr[0]->first_name."','".@$addr[0]->card_house_no."','".@$addr[0]->card_zipcode."', '".@$addr[0]->card_district."', '".@$addr[0]->card_district_sub."', '".@$addr[0]->card_province."', 'customers_address_card', '".@$addr[0]->id."','".@$request->invoice_code."') "); 


                            DB::select("UPDATE `db_frontstore` SET `address_sent_id_fk`='".(DB::getPdo()->lastInsertId())."' WHERE (`id`='".$request->frontstore_id."')");

              

                      }

              
              if(@$request->delivery_location==2){

                    DB::select(" DELETE FROM customers_addr_sent WHERE receipt_no='".@$request->invoice_code."' ");  

                    $addr = DB::select("SELECT
                          customers_detail.id,
                          customers_detail.customer_id,
                          customers_detail.house_no,
                          customers_detail.house_name,
                          customers_detail.moo,
                          customers_detail.zipcode,
                          customers_detail.soi,
                          customers_detail.district,
                          customers_detail.district_sub,
                          customers_detail.road,
                          customers_detail.province,
                          customers.prefix_name,
                          customers.first_name,
                          customers.last_name
                          FROM
                          customers_detail
                          Left Join customers ON customers_detail.customer_id = customers.id
                          WHERE customers_detail.customer_id = 
                           ".@$request->customers_id_fk." ");

                      @$recipient_name = @$addr[0]->prefix_name.@$addr[0]->first_name." ".@$addr[0]->last_name;

                      $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (
                        customer_id, 
                        recipient_name,
                         house_no,house_name, zipcode,
                          district_id, district_sub_id, province_id, 
                          from_table, from_table_id, receipt_no) VALUES (
                          '".@$request->customers_id_fk."',
                          '".@$recipient_name."',
                          '".@$addr[0]->house_no."','".@$addr[0]->house_name."','".@$addr[0]->zipcode."',
                          '".@$addr[0]->district."', '".@$addr[0]->district_sub."', '".@$addr[0]->province."', 
                          'customers_detail', '".@$addr[0]->id."','".@$request->invoice_code."') "); 

                      DB::select("UPDATE `db_frontstore` SET `address_sent_id_fk`='".(DB::getPdo()->lastInsertId())."' WHERE (`id`='".$request->frontstore_id."')");


              }


              if(@$request->delivery_location==3){

                   DB::select(" DELETE FROM customers_addr_sent WHERE receipt_no='".@$request->invoice_code."' ");  

                        $addr = DB::select("select customers_addr_frontstore.* ,dataset_provinces.name_th as provname,
                              dataset_amphures.name_th as ampname,dataset_districts.name_th as tamname 
                              from customers_addr_frontstore
                              Left Join dataset_provinces ON customers_addr_frontstore.province_id_fk = dataset_provinces.id
                              Left Join dataset_amphures ON customers_addr_frontstore.amphur_code = dataset_amphures.id
                              Left Join dataset_districts ON customers_addr_frontstore.tambon_code = dataset_districts.id
                              where customers_addr_frontstore.frontstore_id_fk = ".@$request->frontstore_id." ");

                        $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (customer_id, recipient_name, house_no, zipcode, district, district_sub, province, from_table, from_table_id, receipt_no) VALUES ('".@$request->customers_id_fk."', '".@$addr[0]->recipient_name."','".@$addr[0]->addr_no."','".@$addr[0]->zip_code."', '".@$addr[0]->ampname."', '".@$addr[0]->tamname."', '".@$addr[0]->provname."', 'customers_addr_frontstore', '".@$addr[0]->id."','".@$request->invoice_code."') "); 

                       DB::select("UPDATE `db_frontstore` SET `address_sent_id_fk`='".(DB::getPdo()->lastInsertId())."' WHERE (`id`='".$request->frontstore_id."')");


             }

                DB::select("UPDATE
                  db_delivery_packing_code
                  Inner Join db_delivery_packing ON db_delivery_packing_code.id = db_delivery_packing.packing_code
                  Inner Join db_delivery ON db_delivery_packing.delivery_id_fk = db_delivery.id
                  Inner Join db_frontstore ON db_delivery.receipt = db_frontstore.invoice_code
                  SET
                  db_delivery_packing_code.address_sent_id_fk=db_frontstore.address_sent_id_fk
                  WHERE
                  db_frontstore.invoice_code='".@$request->invoice_code."' ");

             return redirect()->to(url("backend/frontstore/".$request->frontstore_id."/edit"));

        }else{
          return $this->form($id);
        }

      
    }

   public function form($id=NULL)
    {
      // dd($request->all());
      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow = \App\Models\Backend\Frontstore::find($id);
            $invoice_code = $sRow->invoice_code;
          }else{
            $sRow = new \App\Models\Backend\Frontstore;
            /*
            P2102100001
            P=Product
            2102=year-month
            1=business location
            00001=running no.
            P2102100001
            */
            // $inv = DB::select(" select invoice_code from db_frontstore order by invoice_code desc limit 1 ");
            // $invoice_code = substr($inv[0]->invoice_code,0,6).sprintf("%05d",intval(substr($inv[0]->invoice_code,-5))+1);

          }
          // 5=เงินสด,2=บัตรเครดิต
          if( request('pay_type_id_fk')!=2 && request('pay_type_id_fk_2')!=2 ){
            $fee = 0;
          }else{
            $fee = request('fee');
          }

          // clear ออกก่อน แล้วค่อยคำนวณใหม่
          // $sRow->invoice_code    = $invoice_code ;
          $sRow->cash_price    = 0 ;
          $sRow->transfer_price    = 0 ;
          $sRow->fee_amt    = 0 ;
          $sRow->fee_value   = 0 ;
          $sRow->shipping_price    = 0 ;
          $sRow->branch_id_fk    = request('branch_id_fk');
          $Branchs = \App\Models\Backend\Branchs::find($sRow->branch_id_fk);
          $sRow->business_location_id_fk    = $Branchs->business_location_id_fk;
          $sRow->customers_id_fk    = request('customers_id_fk');
          $sRow->distribution_channel_id_fk    = request('distribution_channel_id_fk');
          $sRow->purchase_type_id_fk    = request('purchase_type_id_fk');
          $sRow->pay_type_id_fk    = request('pay_type_id_fk');
          $sRow->pay_type_id_fk_2    = request('pay_type_id_fk_2');
          $sRow->fee    = $fee;
          $sRow->aistockist    = request('aistockist');
          $sRow->agency    = request('agency');
          $sRow->note    = request('note');
          $sRow->action_user = \Auth::user()->id;
          $sRow->action_date = date('Y-m-d H:i:s');
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/frontstore/".$sRow->id."/edit"));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\FrontstoreController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // dd($id);
      DB::select(" DELETE FROM db_frontstore_products_list where frontstore_id_fk=$id ");
      $sRow = \App\Models\Backend\Frontstore::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Frontstore::search();
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('action_date', function($row) {
        $d = strtotime($row->action_date); 
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('customer_name', function($row) {
        $Customer = DB::select(" select * from customers where id=".$row->customers_id_fk." ");
        return $Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name;
      })
      ->addColumn('purchase_type', function($row) {
          $purchase_type = DB::select(" select * from dataset_orders_type where id=".$row->purchase_type_id_fk." ");
          return $purchase_type[0]->orders_type;
      }) 
      ->addColumn('total_price', function($row) {
          $total_price = DB::select(" select sum(total_price) as tt from db_frontstore_products_list where frontstore_id_fk=".$row->id." ");
          return $total_price[0]->tt;
      })        
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
