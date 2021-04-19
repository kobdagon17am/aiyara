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

      $user_login_id = \Auth::user()->id;
      $Frontstore = \App\Models\Backend\Frontstore::get();
      $sUser = DB::select(" select * from ck_users_admin ");
      $sApproveStatus = DB::select(" select * from dataset_approve_status where status=1 ");

      $sPermission = \Auth::user()->permission ;
      if($sPermission==1){
          $w1 = "";
      }else{
          $w1 = " AND action_user = $user_login_id ";
      }


          $sDBFrontstoreApproveStatus = DB::select("

              SELECT db_orders.id,action_date,purchase_type_id_fk,0 as type,customers_id_fk,sum_price,invoice_code,approve_status,shipping_price,db_orders.updated_at,dataset_pay_type.detail as pay_type,pay_type_id,action_user
              FROM db_orders
              Left Join dataset_pay_type ON db_orders.pay_type_id = dataset_pay_type.id
              WHERE 1
              $w1

              UNION

              SELECT
              db_add_ai_cash.id,
              db_add_ai_cash.created_at as d2,
              0 as purchase_type_id_fk,
              'เติม Ai-Cash' AS type,
              db_add_ai_cash.customer_id_fk as c2,
              db_add_ai_cash.aicash_amt,
              db_add_ai_cash.id as inv_no,approve_status
              ,'',
              db_add_ai_cash.updated_at as ud2,
              'ai_cash' as pay_type,3 as pay_type_id,action_user
              FROM db_add_ai_cash
              WHERE 1 AND db_add_ai_cash.approve_status<>4
              $w1

           ");

          // dd($sDBFrontstoreApproveStatus);

      // 0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย)'
          $approve_status_2 = 0;
          $sum_price_2 = 0;
          $pv_2 = 0;

          $approve_status_4 = 0;
          $sum_price_4 = 0;
          $pv_4 = 0;

          $approve_status_9 = 0;
          $sum_price_9 = 0;
          $pv_9 = 0;

// อื่นๆ ไม่รวม 0,2,3,4,9
          $approve_status_88 = 0;
          $sum_price_88 = 0;
          $pv_88 = 0;

// Total
          $approve_status_total = 0;
          $sum_price_total = 0;
          $pv_total = 0;


          foreach ($sDBFrontstoreApproveStatus as $key => $value) {

            if($value->approve_status==0 || $value->approve_status==2 || $value->approve_status==3){
              $approve_status_2 += 1;
              $sum_price_2 += $value->sum_price;
              $pv_2 += @$value->pv;
            }

            if($value->approve_status==4){
              $approve_status_4 += 1;
              $sum_price_4 += $value->sum_price;
              $pv_4 += @$value->pv;
            }

            if($value->approve_status==9){
              $approve_status_9 += 1;
              $sum_price_9 += $value->sum_price;
              $pv_9 += @$value->pv;
            }

            if($value->approve_status==1 || $value->approve_status==5 || $value->approve_status==6  || $value->approve_status==7  || $value->approve_status==8 ){
                $approve_status_88 += 1;
                $sum_price_88 += @$value->sum_price;
                $pv_88 += @$value->pv;
            }

              $approve_status_total += 1 ;
              $sum_price_total += @$value->sum_price;
              $pv_total += @$value->pv;


           }


        $sDBFrontstoreSumCostActionUser = DB::select("
                SELECT
                db_orders.action_user,
                ck_users_admin.`name` as action_user_name,
                db_orders.pay_type_id,
                dataset_pay_type.detail AS pay_type,
                date(db_orders.action_date) AS action_date,
                sum(db_orders.cash_pay) as cash_pay,
                sum(db_orders.credit_price) as credit_price,
                sum(db_orders.transfer_price) as transfer_price,
                sum(db_orders.aicash_price) as aicash_price,
                sum(db_orders.shipping_price) as shipping_price,
                sum(db_orders.fee_amt) as fee_amt,
                sum(db_orders.total_price) as total_price
                FROM
                db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id = dataset_pay_type.id
                Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                WHERE db_orders.pay_type_id<>0 $w1
                GROUP BY action_user
        ");

        // dd($sDBFrontstoreSumCostActionUser);

        $sDBFrontstoreUserAddAiCash = DB::select("
              SELECT
              db_add_ai_cash.action_user,ck_users_admin.`name`,
              sum(db_add_ai_cash.aicash_amt) as sum,
              count(*) as cnt,
              db_add_ai_cash.created_at
              FROM
              db_add_ai_cash
              Left Join ck_users_admin ON db_add_ai_cash.action_user = ck_users_admin.id
              WHERE approve_status<>4
              GROUP BY action_user

        ");


      $sPurchase_type = DB::select(" select * from dataset_orders_type where status=1 and lang_id=1 order by id limit 5");
      $Customer = DB::select(" select * from customers ");

      return View('backend.frontstore.index')->with(
        array(
           'Customer'=>$Customer,
           'sUser'=>$sUser,
           'sApproveStatus'=>$sApproveStatus,
           'sPurchase_type'=>$sPurchase_type,
           'sDBFrontstoreApproveStatus'=>$sDBFrontstoreApproveStatus,

           'approve_status_2'=>($approve_status_2),
           'sum_price_2'=>$sum_price_2,
           'pv_2'=>$pv_2,

           'approve_status_4'=>($approve_status_4),
           'sum_price_4'=>$sum_price_4,
           'pv_4'=>$pv_4,

           'approve_status_9'=>($approve_status_9),
           'sum_price_9'=>$sum_price_9,
           'pv_9'=>$pv_9,

           'approve_status_88'=>($approve_status_88),
           'sum_price_88'=>$sum_price_88,
           'pv_88'=>$pv_88,


           'approve_status_total'=>($approve_status_total),
           'sum_price_total'=>$sum_price_total,
           'pv_total'=>$pv_total,

           'sDBFrontstoreSumCostActionUser'=>$sDBFrontstoreSumCostActionUser,
           'sDBFrontstoreUserAddAiCash'=>$sDBFrontstoreUserAddAiCash,

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

      $sPay_type = DB::select(" select * from dataset_pay_type where id > 4 ");

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
           'sProductUnit'=>$sProductUnit,
           'sDistribution_channel'=>$sDistribution_channel,
           'Products'=>$Products,
           'sBusiness_location'=>$sBusiness_location,
           'sFee'=>$sFee,
           'sBranchs'=>$sBranchs,
           'User_branch_id'=>$User_branch_id,
           'aistockist'=>$aistockist,
           'agency'=>$agency,
           'sPay_type'=>$sPay_type,
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

      $sRow = \App\Models\Backend\Frontstore::find($id);
      if(!$sRow){
        return redirect()->to(url("backend/frontstore"));
      }
      // dd($sRow->customers_id_fk);
      $sCustomer = DB::select(" select * from customers where id=".$sRow->customers_id_fk." ");
      @$CusName = (@$sCustomer[0]->user_name." : ".@$sCustomer[0]->prefix_name.$sCustomer[0]->first_name." ".@$sCustomer[0]->last_name);

      $Cus_Aicash = DB::select(" select * from customers where id=".$sRow->member_id_aicash." ");
      $Cus_Aicash = @$Cus_Aicash[0]->ai_cash;

      $sBranchs = DB::select(" select * from branchs where id=".$sRow->branch_id_fk." ");
      $BranchName = $sBranchs[0]->b_name;

      $Purchase_type = DB::select(" select * from dataset_orders_type where id=".$sRow->purchase_type_id_fk." ");
      $PurchaseName = $Purchase_type[0]->orders_type;

      $CusAddrFrontstore = \App\Models\Backend\CusAddrFrontstore::where('frontstore_id_fk',$id)->get();
      $sUser = \App\Models\Backend\Permission\Admin::get();

      $Delivery_location = DB::select(" select id,txt_desc from dataset_delivery_location  ");

      $shipping_special = DB::select(" select * from dataset_shipping_cost where business_location_id_fk=".$sRow->purchase_type_id_fk." AND shipping_type_id=4 ");

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
      }else{
        $sPurchase_type = DB::select(" select * from dataset_orders_type where status=1 and lang_id=1 order by id limit 5");
      }
      $sPay_type = DB::select(" select * from dataset_pay_type where id > 4 ");

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


      // $giftvoucher_this = DB::select(" select sum(banlance) as gift_total from gift_voucher where customer_id=".$sRow->customers_id_fk." AND banlance>0 AND expiry_date>=now() "); //AND expiry_date>=now()
      $giftvoucher_this = DB::select(" SELECT
            db_giftvoucher_cus.id,
            db_giftvoucher_cus.giftvoucher_code_id_fk,
            db_giftvoucher_cus.customer_code,
            db_giftvoucher_cus.giftvoucher_value,
            db_giftvoucher_cus.pro_status,
            db_giftvoucher_cus.created_at,
            db_giftvoucher_cus.updated_at,
            db_giftvoucher_cus.deleted_at,
            db_giftvoucher_code.descriptions,
            db_giftvoucher_code.pro_sdate,
            db_giftvoucher_code.pro_edate,
            db_giftvoucher_code.`status`,
            customers.id as customers_id
            FROM
            db_giftvoucher_cus
            Left Join db_giftvoucher_code ON db_giftvoucher_cus.giftvoucher_code_id_fk = db_giftvoucher_code.id
            Left Join customers ON db_giftvoucher_cus.customer_code = customers.user_name
            WHERE
            customers.id = ".$sRow->customers_id_fk."
            AND
            curdate() BETWEEN db_giftvoucher_code.pro_sdate and db_giftvoucher_code.pro_edate
            AND
            db_giftvoucher_code.status = 1  "); //AND expiry_date>=now()
      // dd($giftvoucher_this);
      $giftvoucher_this = @$giftvoucher_this[0]->giftvoucher_value;

      $rs = DB::select(" SELECT count(*) as cnt FROM db_order_products_list WHERE frontstore_id_fk=$id ");
      // if($rs[0]->cnt==0){
      //   DB::select(" UPDATE db_orders SET pay_type_id_fk_1='0', pay_type_id_fk_2='0' WHERE (id=$id) ");
      // }


       $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total from db_order_products_list WHERE frontstore_id_fk=$id GROUP BY frontstore_id_fk ");
       // dd($sFrontstoreDataTotal);
       if($sFrontstoreDataTotal){
          $vat = floatval(@$sFrontstoreDataTotal[0]->total) - (floatval(@$sFrontstoreDataTotal[0]->total)/1.07) ;
          $product_value = str_replace(",","",floatval(@$sFrontstoreDataTotal[0]->total) - $vat) ;
          DB::select(" UPDATE db_orders SET product_value=".($product_value).",tax=".($vat).",sum_price=".@$sFrontstoreDataTotal[0]->total." WHERE id=$id ");
        }else{
          DB::select(" UPDATE db_orders SET product_value=0,tax=0,sum_price=0 WHERE id=$id  ");
        }

        $sAccount_bank = \App\Models\Backend\Account_bank::get();

      return View('backend.frontstore.form')->with(
        array(
           'sRow'=>$sRow,
           'Customer'=>$Customer,
           'sPurchase_type'=>$sPurchase_type,
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
           'Cus_Aicash'=>$Cus_Aicash,
           'BranchName'=>$BranchName,
           'PurchaseName'=>$PurchaseName,
           'giftvoucher_this'=>$giftvoucher_this,
           'sAccount_bank'=>$sAccount_bank,
           'sPay_type'=>$sPay_type,
           'shipping_special'=>$shipping_special,
           'sFrontstoreDataTotal'=>$sFrontstoreDataTotal,
        ) );
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
         if(isset($request->receipt_save_list)){
          // dd($request->all());

              $sRow = \App\Models\Backend\Frontstore::find($request->frontstore_id);

              // return request('pay_type_id');
              // dd();

              // ประเภทการโอนเงินต้องรอ อนุมัติก่อน  approve_status
              if(request('pay_type_id')==8 || request('pay_type_id')==10 || request('pay_type_id')==11){

                 $sRow->approve_status = 0  ;
                 $sRow->invoice_code = '' ;

              }else if(request('pay_type_id')==5 || request('pay_type_id')==6 || request('pay_type_id')==7 || request('pay_type_id')==9){
                $sRow->approve_status = 2  ;
              }else{

                  $table = 'db_orders';
                  $branchs = DB::select("SELECT * FROM branchs where id=".$request->this_branch_id_fk."");
                  $inv = DB::select(" select invoice_code,SUBSTR(invoice_code,3,2)as y,SUBSTR(invoice_code,5,2)as m,DATE_FORMAT(now(), '%y') as this_y,DATE_FORMAT(now(), '%m') as this_m from $table
                    WHERE SUBSTR(invoice_code,3,2)=DATE_FORMAT(now(), '%y') AND SUBSTR(invoice_code,5,2)=DATE_FORMAT(now(), '%m')
                    order by invoice_code desc limit 1 ");
                  if($inv){
                      $invoice_code = 'P'.$branchs[0]->business_location_id_fk.date("ym").sprintf("%05d",intval(substr($inv[0]->invoice_code,-5))+1);
                  }else{
                      $invoice_code = 'P'.$branchs[0]->business_location_id_fk.date("ym").sprintf("%05d",1);
                  }
                  // dd($invoice_code);
                  if($sRow->invoice_code==''||$sRow->invoice_code==0){
                    $sRow->invoice_code = $invoice_code;
                  }

                    $sRow->approve_status = 1 ;

              }


              $sRow->charger_type    = request('charger_type');
              $sRow->credit_price    = str_replace(',','',request('credit_price'));
              $sRow->sum_credit_price    = str_replace(',','',request('sum_credit_price'));
              $sRow->pay_type_id    = request('pay_type_id')?request('pay_type_id'):0;
              $sRow->gift_voucher_cost    = str_replace(',','',request('gift_voucher_cost'));

              $sRow->member_id_aicash    = str_replace(',','',request('member_id_aicash'));

              $sRow->aistockist    = request('aistockist');
              $sRow->agency    = request('agency');
              $sRow->note    = request('note');

              $sRow->delivery_location    = request('delivery_location');
              $sRow->cash_price    = str_replace(',','',request('cash_price'));

              // dd(str_replace(',','',request('cash_price')));

              $sRow->shipping_price    = str_replace(',','',request('shipping_price'));
              $sRow->fee    =  str_replace(',','',request('fee'));
              $sRow->fee_amt    =  str_replace(',','',request('fee_amt'));
              $sRow->sum_price    =  str_replace(',','',request('sum_price'));
              $sRow->cash_pay    =  str_replace(',','',request('cash_pay'));

              $sRow->account_bank_id = request('account_bank_id');
              $sRow->transfer_money_datetime = request('transfer_money_datetime');
              // dd(request('shipping_price'));
              if(empty(request('shipping_price'))){
                $sRow->total_price    =  str_replace(',','',request('sum_price'))+str_replace(',','',request('fee_amt'));
              }else{
                if(request('sum_price')>0){
                $sRow->total_price    =  str_replace(',','',request('sum_price'))+str_replace(',','',request('shipping_price'))+str_replace(',','',request('fee_amt'));
                }
              }


              $sRow->action_user = \Auth::user()->id;
              $sRow->action_date = date('Y-m-d H:i:s');


              $request = app('request');
              if ($request->hasFile('image01')) {
                  @UNLINK(@$sRow->file_slip);
                  $this->validate($request, [
                    'image01' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                  ]);
                  $image = $request->file('image01');
                  $name = 'S'.time() . '.' . $image->getClientOriginalExtension();
                  $image_path = 'local/public/files_slip/'.date('Ym').'/';
                  $image->move($image_path, $name);
                  $sRow->file_slip = $image_path.$name;
                }

              $sRow->save();


             if(@$request->delivery_location  == 0 || @$request->delivery_location  == 4 ){
                  $sRow->sentto_branch_id    = request('sentto_branch_id');
                   DB::select("UPDATE db_orders SET address_sent_id_fk='0' WHERE (id='".$request->frontstore_id."')");
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
                                      customers_address_card.card_district_id_fk,
                                      customers_address_card.card_amphures_id_fk,
                                      customers_address_card.card_road,
                                      customers_address_card.card_province_id_fk,
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
                                      Left Join dataset_provinces ON customers_address_card.card_province_id_fk= dataset_provinces.id
                                      Left Join dataset_amphures ON customers_address_card.card_amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_address_card.card_district_id_fk = dataset_districts.id
                                      Left Join customers ON customers_address_card.customer_id = customers.id
                                      where customers_address_card.customer_id = ".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)."
                                 ");


                            $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (customer_id, first_name, house_no, zipcode, amphures_id_fk, district_id_fk, province_id_fk, from_table, from_table_id, receipt_no) VALUES ('".@$request->customers_id_fk."', '".@$addr[0]->first_name."','".@$addr[0]->card_house_no."','".@$addr[0]->card_zipcode."', '".@$addr[0]->card_amphures_id_fk."', '".@$addr[0]->card_district_id_fk."', '".@$addr[0]->card_province_id_fk."', 'customers_address_card', '".@$addr[0]->id."','".@$request->invoice_code."') ");


                            DB::select("UPDATE db_orders SET address_sent_id_fk='".(DB::getPdo()->lastInsertId())."' WHERE (id='".$request->frontstore_id."')");



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
                          customers_detail.amphures_id_fk,
                          customers_detail.district_id_fk,
                          customers_detail.road,
                          customers_detail.province_id_fk,
                          customers.prefix_name,
                          customers.first_name,
                          customers.last_name,
                          dataset_amphures.name_th AS amp_name,
						dataset_districts.name_th AS tambon_name,
						dataset_provinces.name_th AS province_name
                          FROM
                          customers_detail
                          Left Join customers ON customers_detail.customer_id = customers.id
                          Left Join dataset_amphures ON customers_detail.amphures_id_fk = dataset_amphures.id
							Left Join dataset_districts ON customers_detail.district_id_fk = dataset_districts.id
							Left Join dataset_provinces ON customers_detail.province_id_fk = dataset_provinces.id
                          WHERE customers_detail.customer_id =
                           ".@$request->customers_id_fk." ");

                      @$recipient_name = @$addr[0]->prefix_name.@$addr[0]->first_name." ".@$addr[0]->last_name;

                      $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (
                        customer_id,
                        recipient_name,
                         house_no,house_name, zipcode,
                         amphures_id_fk, district_id_fk,province_id_fk,
                          from_table, from_table_id, receipt_no) VALUES (
                          '".@$request->customers_id_fk."',
                          '".@$recipient_name."',
                          '".@$addr[0]->house_no."','".@$addr[0]->house_name."','".@$addr[0]->zipcode."',
                          '".@$addr[0]->district."', '".@$addr[0]->district_sub."', '".@$addr[0]->province."',
                          'customers_detail', '".@$addr[0]->id."','".@$request->invoice_code."') ");

                      DB::select("UPDATE db_orders SET address_sent_id_fk='".(DB::getPdo()->lastInsertId())."' WHERE (id='".$request->frontstore_id."')");


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

                        $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (customer_id, recipient_name, house_no, zipcode,amphures_id_fk,district_id_fk, province_id_fk, from_table, from_table_id, receipt_no) VALUES ('".@$request->customers_id_fk."', '".@$addr[0]->recipient_name."','".@$addr[0]->addr_no."','".@$addr[0]->zip_code."', '".@$addr[0]->ampname."', '".@$addr[0]->tamname."', '".@$addr[0]->provname."', 'customers_addr_frontstore', '".@$addr[0]->id."','".@$request->invoice_code."') ");

                       DB::select("UPDATE db_orders SET address_sent_id_fk='".(DB::getPdo()->lastInsertId())."' WHERE (id='".$request->frontstore_id."')");


             }

                DB::select("UPDATE
                  db_delivery_packing_code
                  Inner Join db_delivery_packing ON db_delivery_packing_code.id = db_delivery_packing.packing_code
                  Inner Join db_delivery ON db_delivery_packing.delivery_id_fk = db_delivery.id
                  Inner Join db_orders ON db_delivery.receipt = db_orders.invoice_code
                  SET
                  db_delivery_packing_code.address_sent_id_fk=db_orders.address_sent_id_fk
                  WHERE
                  db_orders.invoice_code='".@$request->invoice_code."' ");

                // dd($request);
               $ch_aicash_02 = DB::select(" select * from db_orders where id=".$request->frontstore_id." ");

               // dd($ch_aicash_02[0]->member_id_aicash);

            // เช็คเรื่องการตัดยอด Ai-Cash
               $ch_aicash_01 = DB::select(" select * from customers where id=".$ch_aicash_02[0]->member_id_aicash." ");

               // dd($ch_aicash_02[0]->aicash_price);
               // ถ้าค่าที่ส่งมาตัด รวมกับ ค่าเดิม แล้วเกินค่าเดิม ให้ลบกันได้เลย แต่ถ้า รวมแล้วน่อยกว่า ให้เอาค่าก่อนหน้า มาบวกก่อน ค่อยลบออก
              if(@$ch_aicash_01[0]->ai_cash>0){
                 if( ($ch_aicash_02[0]->aicash_price + $ch_aicash_01[0]->ai_cash) >= $ch_aicash_01[0]->ai_cash ){
                    DB::select(" UPDATE customers SET ai_cash=(ai_cash-".$ch_aicash_02[0]->aicash_price.") where id=".$ch_aicash_02[0]->member_id_aicash." ");
                 }else{
                      $x = $ch_aicash_01[0]->ai_cash - $ch_aicash_02[0]->aicash_price ;
                      DB::select(" UPDATE customers SET ai_cash=($x) where id=".$ch_aicash_02[0]->member_id_aicash." ");
                 }
               }


               $r_addr = DB::select("select address_sent_id_fk from db_orders WHERE (id='".$request->frontstore_id."')");

           if( @$request->delivery_location!=3){
              DB::select(" UPDATE
                  customers_addr_sent
                  Left Join dataset_amphures ON customers_addr_sent.district_id = dataset_amphures.id
                  left Join dataset_districts ON customers_addr_sent.district_sub_id = dataset_districts.id
                  LEFT Join dataset_provinces ON customers_addr_sent.province_id = dataset_provinces.id
                  SET
                  customers_addr_sent.district=dataset_amphures.name_th,
                  customers_addr_sent.district_sub=dataset_districts.name_th,
                  customers_addr_sent.province=dataset_provinces.name_th
                  WHERE
                  customers_addr_sent.id='".($r_addr[0]->address_sent_id_fk)."' ");
            }
        

             // return redirect()->to(url("backend/frontstore/".$request->frontstore_id."/edit"));
             return redirect()->to(url("backend/frontstore"));

        }else{

          // dd($request->all());
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

              // $sRow->cash_price    = str_replace(',','',request('cash_price')) ;
              // $sRow->fee_amt    = str_replace(',','',request('fee_amt')) ;
              // $sRow->shipping_price    = str_replace(',','',request('shipping_price')) ;


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
            // $inv = DB::select(" select invoice_code from db_orders order by invoice_code desc limit 1 ");
            // $invoice_code = substr($inv[0]->invoice_code,0,6).sprintf("%05d",intval(substr($inv[0]->invoice_code,-5))+1);

            // $sRow->cash_price    = 0 ;
            // $sRow->fee_amt    = 0 ;
            // $sRow->shipping_price    = 0 ;


          }
          // 5=เงินสด,2=บัตรเครดิต
          // if( request('pay_type_id_fk_1')!=2 && request('pay_type_id_fk_2')!=2 ){
          //   $fee = 0;
          // }else{
            $fee = request('fee');
          // }

          // clear ออกก่อน แล้วค่อยคำนวณใหม่
          // $sRow->invoice_code    = $invoice_code ;


          $sRow->branch_id_fk    = request('branch_id_fk');
          $Branchs = \App\Models\Backend\Branchs::find($sRow->branch_id_fk);
          $sRow->business_location_id_fk    = $Branchs->business_location_id_fk;
          $sRow->customers_id_fk    = request('customers_id_fk');
          $sRow->distribution_channel_id_fk    = request('distribution_channel_id_fk');
          $sRow->purchase_type_id_fk    = request('purchase_type_id_fk');
          // $sRow->pay_type_id_fk_1    = request('pay_type_id_fk_1');
          // $sRow->pay_type_id_fk_2    = request('pay_type_id_fk_2');
          // $sRow->gift_voucher_cost    = request('gift_voucher_cost');
          // $sRow->gift_voucher_id    = request('gift_voucher_id');
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

      $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total from db_order_products_list WHERE frontstore_id_fk=$id GROUP BY frontstore_id_fk ");
       // dd($sFrontstoreDataTotal);
       if($sFrontstoreDataTotal){
          $vat = floatval(@$sFrontstoreDataTotal[0]->total) - (floatval(@$sFrontstoreDataTotal[0]->total)/1.07) ;
          $product_value = str_replace(",","",floatval(@$sFrontstoreDataTotal[0]->total) - $vat) ;
          DB::select(" UPDATE db_orders SET product_value=".($product_value).",tax=".($vat).",sum_price=".@$sFrontstoreDataTotal[0]->total." WHERE id=$id ");
        }else{
          DB::select(" UPDATE db_orders SET product_value=0,tax=0,sum_price=0,cash_pay='0', cash_price='0', shipping_price='0', total_price='0' WHERE id=$id  ");
        }

      DB::select(" DELETE FROM db_order_products_list where frontstore_id_fk=$id ");

      $r = DB::select(" SELECT * FROM db_orders where id=$id ");

      DB::select(" UPDATE customers SET ai_cash=(ai_cash + ".$r[0]->aicash_price.") WHERE (id='".$r[0]->member_id_aicash."') ");

      DB::select(" UPDATE db_orders SET approve_status=4 where id=$id ");

      // $sRow = \App\Models\Backend\Frontstore::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      return response()->json(\App\Models\Alert::Msg('success'));
    }



    public function Datatable(){

          $user_login_id = \Auth::user()->id;
          $sPermission = \Auth::user()->permission ;
          // dd($sPermission);
          if($sPermission==1){
              $w1 = "";
          }else{
              $w1 = " AND action_user = $user_login_id ";
          }

            $sTable = DB::select("

                SELECT db_orders.id,action_date,purchase_type_id_fk,0 as type,customers_id_fk,sum_price,invoice_code,approve_status,shipping_price,db_orders.updated_at,dataset_pay_type.detail as pay_type,cash_price,credit_price,fee_amt,transfer_price,aicash_price,total_price
                FROM db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id = dataset_pay_type.id
                WHERE 1
                $w1

                UNION

                SELECT
                db_add_ai_cash.id,
                db_add_ai_cash.created_at as d2,
                0 as purchase_type_id_fk,
                'เติม Ai-Cash' AS type,
                db_add_ai_cash.customer_id_fk as c2,
                db_add_ai_cash.aicash_amt,
                db_add_ai_cash.id as inv_no,approve_status
                ,'',
                db_add_ai_cash.updated_at as ud2,
                'ai_cash' as pay_type,cash_price,credit_price,fee_amt,transfer_price,0 as aicash_price,total_amt as total_price
                FROM db_add_ai_cash
                WHERE 1 AND db_add_ai_cash.approve_status<>4
                $w1

                ORDER BY updated_at DESC

              ");

      // $sTable = \App\Models\Backend\Frontstore::search();
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('action_date', function($row) {
        $d = strtotime(@$row->action_date);
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('customer_name', function($row) {
        $Customer = DB::select(" select * from customers where id=".@$row->customers_id_fk." ");
        return @$Customer[0]->user_name.' : '.@$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
      })
      ->addColumn('purchase_type', function($row) {
        if(@$row->purchase_type_id_fk>0){
          @$purchase_type = DB::select(" select * from dataset_orders_type where id=".@$row->purchase_type_id_fk." ");
          // return $purchase_type[0]->orders_type;
          return @$purchase_type[0]->detail;
        }
      })
      ->addColumn('status', function($row) {
        // 0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ
          if($row->approve_status==1){
            return 'อนุมัติ';
          }else if($row->approve_status==2){
            return 'รอชำระ';
          }else if($row->approve_status==3){
            return 'รอจัดส่ง';
          }else if($row->approve_status==4){
            return 'ยกเลิก';
          }else if($row->approve_status==5){
            return 'ไม่อนุมัติ';
          }else if($row->approve_status==9){
            return 'สำเร็จ';
          }else{
            return 'รออนุมัติ';
          }
      })
      ->addColumn('shipping_price', function($row) {
          if(@$row->shipping_price){
            return number_format($row->shipping_price,0);
          }
      })
      ->addColumn('tooltip_price', function($row) {
        // cash_pay,credit_price,fee_amt,transfer_price,aicash_price
          $tootip_price = '';
          if(@$row->cash_price!=0){
             $tootip_price .= ' เงินสด: '.$row->cash_price;
          }
          if(@$row->credit_price!=0){
             $tootip_price .= ' เครดิต: '.$row->credit_price.' ค่าธรรมเนียม :'.$row->fee_amt;
          }
          if(@$row->transfer_price!=0){
             $tootip_price .= ' เงินโอน: '.$row->transfer_price;
          }
          if(@$row->aicash_price!=0){
             $tootip_price .= ' Ai-Cash: '.$row->aicash_price;
          }
          return $tootip_price;

      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
