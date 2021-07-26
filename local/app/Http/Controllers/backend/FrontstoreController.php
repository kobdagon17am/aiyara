<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDO;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Http\Controllers\Frontend\Fc\GiveawayController;
use Auth;
use App\Models\Frontend\RunNumberPayment;
use App\Models\Frontend\PvPayment;

class FrontstoreController extends Controller
{

    public function index(Request $request)
    {
      // dd(\Auth::user()->position_level);
      // dd(\Auth::user()->branch_id_fk);
      $user_login_id = \Auth::user()->id;
      $Frontstore = \App\Models\Backend\Frontstore::get();
      $sUser = DB::select(" select * from ck_users_admin ");
      $sApproveStatus = DB::select(" select * from dataset_approve_status where status=1 and id not in (1,2) "); // 1,2 เหมือนว่าไม่ได้ใช้แล้ว

      $sPermission = \Auth::user()->permission ;
      if($sPermission==1){
          $w1 = "";
      }else{
          $w1 = " AND action_user = $user_login_id ";
      }


          $sDBFrontstoreApproveStatus = DB::select("

              SELECT db_orders.id,action_date,purchase_type_id_fk,0 as type,customers_id_fk,sum_price,invoice_code,approve_status,shipping_price,db_orders.updated_at,dataset_pay_type.detail as pay_type,pay_type_id_fk,action_user
              FROM db_orders
              Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
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
              'ai_cash' as pay_type,3 as pay_type_id_fk,action_user
              FROM db_add_ai_cash
              WHERE 1 AND db_add_ai_cash.approve_status<>4
              $w1

           ");

          // dd($sDBFrontstoreApproveStatus);

      // 0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย)'

          //   `approve_status` int(11) DEFAULT '0' COMMENT '1=รออนุมัติ,2=อนุมัติแล้ว,3=รอชำระ,4=รอจัดส่ง,5=ยกเลิก,6=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย) > Ref>dataset_approve_status>id',

          // รออนุมัติ
          $approve_status_1 = 0;
          $sum_price_1 = 0;
          $pv_1 = 0;

          $approve_status_2 = 0;
          $sum_price_2 = 0;
          $pv_2 = 0;

          $approve_status_4 = 0;
          $sum_price_4 = 0;
          $pv_4 = 0;

          // 5=ยกเลิก
          $approve_status_5 = 0;
          $sum_price_5 = 0;
          $pv_5 = 0;

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

            // if($value->approve_status==0 || $value->approve_status==2 || $value->approve_status==3){
            //   $approve_status_2 += 1;
            //   $sum_price_2 += $value->sum_price;
            //   $pv_2 += @$value->pv;
            // }

            if($value->approve_status==1){
              $approve_status_1 += 1;
              $sum_price_1 += $value->sum_price;
              $pv_1 += @$value->pv;
            }

            // if($value->approve_status==4){
            //   $approve_status_4 += 1;
            //   $sum_price_4 += $value->sum_price;
            //   $pv_4 += @$value->pv;
            // }

            if($value->approve_status==5){
              $approve_status_5 += 1;
              $sum_price_5 += $value->sum_price;
              $pv_5 += @$value->pv;
            }

            if($value->approve_status==9){
              $approve_status_9 += 1;
              $sum_price_9 += $value->sum_price;
              $pv_9 += @$value->pv;
            }

            // if($value->approve_status==1 || $value->approve_status==5 || $value->approve_status==6  || $value->approve_status==7  || $value->approve_status==8 ){
            //     $approve_status_88 += 1;
            //     $sum_price_88 += @$value->sum_price;
            //     $pv_88 += @$value->pv;
            // }


            if($value->approve_status!=1 && $value->approve_status!=5 && $value->approve_status!=9 ){
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
                db_orders.pay_type_id_fk,
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
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                WHERE db_orders.pay_type_id_fk<>0 $w1
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
       $Customer = DB::select(" select * from customers where id in(select customers_id_fk from db_orders) ");

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


        $sDBSentMoneyDaily = DB::select("
              SELECT
              db_sent_money_daily.*,
              ck_users_admin.`name` as sender
              FROM
              db_sent_money_daily
              Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
              WHERE date(db_sent_money_daily.updated_at)=CURDATE()
              order by db_sent_money_daily.time_sent
        ");


      $r_invoice_code = DB::select(" SELECT invoice_code FROM db_orders ");
      // dd($r_invoice_code);


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

           'approve_status_5'=>($approve_status_5),
           'sum_price_5'=>$sum_price_5,
           'pv_5'=>$pv_5,

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

           'sDBSentMoneyDaily'=>$sDBSentMoneyDaily,
           'r_invoice_code'=>$r_invoice_code,

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

      $Customer = DB::select(" select * from customers limit 10000");
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
      // dd($sRow);
      // dd($sRow->pv_total);

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

      // dd($Products);

      $Customer = DB::select(" select * from customers where id in(select customers_id_fk from db_orders)  ");
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

      $business_location_id = $sRow->business_location_id;
      $type = $sRow->purchase_type_id_fk;
      $pv_total = $sRow->pv_total;
      $customer_pv = \Auth::user()->pv;
      $check_giveaway = GiveawayController::check_giveaway($business_location_id,$type,$customer_pv,$pv_total);
      // dd($check_giveaway);

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
           'check_giveaway'=>$check_giveaway,
        ) );
    }


    public function viewdata($id)
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

      $DATE_CREATED = date("Y-m-d",strtotime($sRow->created_at));
      $DATE_YESTERDAY = Carbon::yesterday()->format('Y-m-d');
      $DATE_TODAY = Carbon::today()->format('Y-m-d');

      $sPermission = \Auth::user()->permission ; // Super Admin == 1
      $position_level = \Auth::user()->position_level;
      // dd($sPermission);
      // dd($position_level);
      $ChangePurchaseType = 0; // ปิด / ไม่แสดง
      if($sPermission==1){
        $ChangePurchaseType = 1; // เปิด / แสดง
      }else{
        // dataset_position_level
        // 1 Supervisor/Manager
        // 2 CS แผนกขาย
        if($position_level==1){
          if( $DATE_CREATED>=$DATE_YESTERDAY && $DATE_CREATED<=$DATE_TODAY  ) $ChangePurchaseType = 1;
        }else{
          if($DATE_CREATED==$DATE_TODAY) $ChangePurchaseType = 1;
        }

      }

      return View('backend.frontstore.viewdata')->with(
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
           'DATE_CREATED'=>$DATE_CREATED,
           'DATE_YESTERDAY'=>$DATE_YESTERDAY,
           'DATE_TODAY'=>$DATE_TODAY,
           'ChangePurchaseType'=>$ChangePurchaseType,
        ) );
    }


    public function update(Request $request, $id)
    {
      // dd($request->all());
         if(isset($request->receipt_save_list)){
          // dd($request->all());

              $sRow = \App\Models\Backend\Frontstore::find($request->frontstore_id);

              // dd($sRow);

              if($sRow->invoice_code==""){

                if(request('pay_type_id_fk')==8 || request('pay_type_id_fk')==10 || request('pay_type_id_fk')==11){
                  $sRow->invoice_code = '' ;
                }else{
                  $table = 'db_orders';
                  $branchs = DB::select("SELECT * FROM branchs where id=".$request->this_branch_id_fk."");
                  $sRow->invoice_code = RunNumberPayment::run_number_order($branchs[0]->business_location_id_fk);
                }

              }

              // dd("706");

              $sRow->charger_type    = request('charger_type');
              $sRow->credit_price    = str_replace(',','',request('credit_price'));
              $sRow->sum_credit_price    = str_replace(',','',request('sum_credit_price'));
              $sRow->pay_type_id_fk    = request('pay_type_id_fk')?request('pay_type_id_fk'):0;
              $sRow->gift_voucher_cost    = str_replace(',','',request('gift_voucher_cost'));
              $sRow->member_id_aicash    = str_replace(',','',request('member_id_aicash'));
              $sRow->aistockist    = request('aistockist');
              $sRow->agency    = request('agency');
              $sRow->note    = request('note');
              $sRow->delivery_location    = request('delivery_location');
              $sRow->cash_price    = str_replace(',','',request('cash_price'));
              $sRow->shipping_price    = str_replace(',','',request('shipping_price'));
              $sRow->fee    =  str_replace(',','',request('fee'));
              $sRow->fee_amt    =  str_replace(',','',request('fee_amt'));
              $sRow->sum_price    =  str_replace(',','',request('sum_price'));
              $sRow->cash_pay    =  str_replace(',','',request('cash_pay'));
              $sRow->account_bank_id = request('account_bank_id');
              $sRow->transfer_money_datetime = request('transfer_money_datetime');
              if(empty(request('shipping_price'))){
                $sRow->total_price    =  str_replace(',','',request('sum_price'))+str_replace(',','',request('fee_amt'));
              // }else{
              //   if(request('sum_price')>0){
              //   // $sRow->total_price    =  str_replace(',','',request('sum_price'))+str_replace(',','',request('shipping_price'))+str_replace(',','',request('fee_amt'));
              //   }
              }

              // dd("734");

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

    

              // PvPayment::PvPayment_type_confirme($sRow->id,\Auth::user()->id,'1','admin');
              //id_order,id_admin,1 ติดต่อหน้าร้าน 2 ช่องทางการจำหน่ายอื่นๆ  dataset_distribution_channel>id  ,'customer หรือ admin'

// dd(request('sentto_branch_id'));
// dd(request('branch_id_fk'));
// dd($request->delivery_location);

              $db_orders = DB::select(" select invoice_code from db_orders where id=".$sRow->id." ");

             if(@$request->delivery_location  == 0 || @$request->delivery_location  == 4 ){
                   $sRow->sentto_branch_id    = request('branch_id_fk');
                   DB::select("UPDATE db_orders SET sentto_branch_id=".request('branch_id_fk').", address_sent_id_fk='0' WHERE (id='".$request->frontstore_id."')");
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


                            $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (invoice_code,customer_id, recipient_name, house_no, zipcode, amphures_id_fk, district_id_fk, province_id_fk, from_table, from_table_id, receipt_no) VALUES ('".$db_orders[0]->invoice_code."','".@$request->customers_id_fk."', '".@$addr[0]->first_name." ".@$addr[0]->last_name."','".@$addr[0]->card_house_no."','".@$addr[0]->card_zipcode."', '".@$addr[0]->card_amphures_id_fk."', '".@$addr[0]->card_district_id_fk."', '".@$addr[0]->card_province_id_fk."', 'customers_address_card', '".@$addr[0]->id."','".@$request->invoice_code."') ");


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

                      $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (invoice_code,
                        customer_id,
                        recipient_name,
                         house_no,house_name, zipcode,
                         amphures_id_fk, district_id_fk,province_id_fk,
                          from_table, from_table_id, receipt_no) VALUES ( '".$db_orders[0]->invoice_code."',
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

                        $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (invoice_code,customer_id, recipient_name, house_no, zipcode,amphures_id_fk,district_id_fk, province_id_fk, from_table, from_table_id, receipt_no) VALUES ('".$db_orders[0]->invoice_code."','".@$request->customers_id_fk."', '".@$addr[0]->recipient_name."','".@$addr[0]->addr_no."','".@$addr[0]->zip_code."', '".@$addr[0]->ampname."', '".@$addr[0]->tamname."', '".@$addr[0]->provname."', 'customers_addr_frontstore', '".@$addr[0]->id."','".@$request->invoice_code."') ");

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
                if($request->frontstore_id){
                  $ch_aicash_02 = DB::select(" select * from db_orders where id=".$request->frontstore_id." ");
                }else{
                  $ch_aicash_02 = NULL;
                }

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
                  Left Join dataset_amphures ON customers_addr_sent.amphures_id_fk = dataset_amphures.id
                  left Join dataset_districts ON customers_addr_sent.district_id_fk = dataset_districts.id
                  LEFT Join dataset_provinces ON customers_addr_sent.province_id_fk = dataset_provinces.id
                  SET
                  customers_addr_sent.amphures=dataset_amphures.name_th,
                  customers_addr_sent.district=dataset_districts.name_th,
                  customers_addr_sent.province=dataset_provinces.name_th
                  WHERE
                  customers_addr_sent.id='".($r_addr[0]->address_sent_id_fk)."' ");
            }
        

 /*
1 เงินโอน
2 บัตรเครดิต
3 Ai-Cash
4 Gift Voucher
5 เงินสด
6 เงินสด + Ai-Cash
7 เครดิต + เงินสด
8 เครดิต + เงินโอน
9 เครดิต + Ai-Cash
10  เงินโอน + เงินสด
11  เงินโอน + Ai-Cash
12  Gift Voucher + เงินโอน
13  Gift Voucher + บัตรเครดิต
14  Gift Voucher + Ai-Cash
15  PromptPay
16  TrueMoney
17  Gift Voucher + PromptPay
18  Gift Voucher + TrueMoney
*/
// `approve_status` int(11) DEFAULT '0' COMMENT '1=รออนุมัติ,2=อนุมัติแล้ว,3=รอชำระ,4=รอจัดส่ง,5=ยกเลิก,6=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย) > Ref>dataset_approve_status>id',

    // ประเภทการโอนเงินต้องรอ อนุมัติก่อน  approve_status
// dd(request('pay_type_id_fk'));
              if(request('pay_type_id_fk')==8 || request('pay_type_id_fk')==10 || request('pay_type_id_fk')==11){
                  $sRow->approve_status = 1  ;
              // }else if(request('pay_type_id_fk')==5 || request('pay_type_id_fk')==6 || request('pay_type_id_fk')==7 || request('pay_type_id_fk')==9){
                // dd(request('pay_type_id_fk'));
                  // $sRow->approve_status = 2  ;
              }else{
                  $sRow->approve_status = 4 ;
              }

              // $sRow->approve_status = 9 ;

              $sRow->save();

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
 
            $fee = request('fee');

          // clear ออกก่อน แล้วค่อยคำนวณใหม่
          // $sRow->invoice_code    = $invoice_code ;

          $sRow->branch_id_fk    = request('branch_id_fk');
          $Branchs = \App\Models\Backend\Branchs::find($sRow->branch_id_fk);
          $sRow->business_location_id_fk    = $Branchs->business_location_id_fk;
          $sRow->customers_id_fk    = request('customers_id_fk');
          $sRow->distribution_channel_id_fk    = request('distribution_channel_id_fk');
          $sRow->purchase_type_id_fk    = request('purchase_type_id_fk');
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

// สคริปต์นี้มีปัญหากับ V3 ไปใช้ ajaxCancelOrderBackend แทน
    public function destroy($id)
    {
      // DB::select(" UPDATE db_orders SET approve_status=5 where id=$id ");
      // return response()->json(\App\Models\Alert::Msg('success'));
       // return redirect()->to(url("backend/frontstore"));
    }
   

    public function Datatable(Request $req){

          $user_login_id = \Auth::user()->id;
          $sPermission = \Auth::user()->permission ;
          // dd($sPermission);
// \Auth::user()->position_level==1 => Supervisor
        if(\Auth::user()->position_level=='1'){
            $action_user_011 = " AND db_orders.branch_id_fk = '".(\Auth::user()->branch_id_fk)."' " ;
        }else{
            $action_user_011 = " AND action_user = $user_login_id ";
        }
        
        if($sPermission==1){
            $action_user_01 = "";
            $action_user_011 =  " AND db_orders.branch_id_fk = '".(\Auth::user()->branch_id_fk)."' " ;
        }else{
            $action_user_01 = " AND action_user = $user_login_id ";
        }

        if(!empty($req->startDate)){
           $startDate = " AND DATE(db_orders.created_at) >= '".$req->startDate."' " ;
           $startDate2 = " AND DATE(db_add_ai_cash.created_at) >= '".$req->startDate."' " ;
        }else{
           $startDate = "";
           $startDate2 = "";
        }

        if(!empty($req->endDate)){
           $endDate = " AND DATE(db_orders.created_at) <= '".$req->endDate."' " ;
           $endDate2 = " AND DATE(db_add_ai_cash.created_at) <= '".$req->endDate."' " ;
        }else{
           $endDate = "";
           $endDate2 = "";
        }

        if(!empty($req->purchase_type_id_fk)){
           $purchase_type_id_fk = " AND db_orders.purchase_type_id_fk = '".$req->purchase_type_id_fk."' " ;
        }else{
           $purchase_type_id_fk = "";
        }

        if(!empty($req->customer_code)){
           $customer_code = " AND db_orders.customers_id_fk = '".$req->customer_code."' " ;
        }else{
           $customer_code = "";
        }

        if(!empty($req->customer_name)){
           $customer_name = " AND db_orders.customers_id_fk = '".$req->customer_name."' " ;
        }else{
           $customer_name = "";
        }

        if(!empty($req->invoice_code)){
           $invoice_code = " AND db_orders.invoice_code = '".$req->invoice_code."' " ;
        }else{
           $invoice_code = "";
        }

        if(!empty($req->action_user)){
           $action_user_02 = " AND db_orders.action_user = '".$req->action_user."' " ;
        }else{
           $action_user_02 = "";
        }

        if(isset($req->status_sent_money)){
           $status_sent_money = " AND db_orders.status_sent_money = ".$req->status_sent_money." " ;
        }else{
           $status_sent_money = "";
        }

        if(isset($req->approve_status)){
           $approve_status = " AND db_orders.approve_status = ".$req->approve_status." " ;
        }else{
           $approve_status = "";
        }


        if(isset($req->ViewCondition) && $req->ViewCondition=="ViewAll"){

          $sTable = DB::select("

                SELECT db_orders.id,action_date,purchase_type_id_fk,0 as type,customers_id_fk,sum_price,invoice_code,approve_status,shipping_price,db_orders.updated_at,dataset_pay_type.detail as pay_type,cash_price,credit_price,fee_amt,transfer_price,aicash_price,total_price,db_orders.created_at,status_sent_money
                FROM db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id

                UNION ALL 

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
                'ai_cash' as pay_type,cash_price,
                credit_price,fee_amt,transfer_price,
                0 as aicash_price,total_amt as total_price,'',status_sent_money
                FROM db_add_ai_cash
                ORDER BY created_at DESC

              ");

        }else if(isset($req->ViewCondition) && $req->ViewCondition=="ViewBuyNormal"){

/*
1 1 ทำคุณสมบัติ
2 2 รักษาคุณสมบัติรายเดือน
3 3 รักษาคุณสมบัติท่องเที่ยว
4 4 เติม Ai-Stockist
5 5 Gift Voucher
6 6 คอร์สอบรม
*/
              $sTable = DB::select("

                SELECT db_orders.id,action_date,purchase_type_id_fk,0 as type,customers_id_fk,sum_price,invoice_code,approve_status,shipping_price,db_orders.updated_at,dataset_pay_type.detail as pay_type,cash_price,credit_price,fee_amt,transfer_price,aicash_price,total_price,db_orders.created_at,status_sent_money
                FROM db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                WHERE db_orders.purchase_type_id_fk in (1,2,3)

                UNION ALL 

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
                'ai_cash' as pay_type,cash_price,
                credit_price,fee_amt,transfer_price,
                0 as aicash_price,total_amt as total_price,'' ,status_sent_money
                FROM db_add_ai_cash
                ORDER BY created_at DESC

              ");


             }else if(isset($req->ViewCondition) && $req->ViewCondition=="ViewBuyVoucher"){

/*
1 1 ทำคุณสมบัติ
2 2 รักษาคุณสมบัติรายเดือน
3 3 รักษาคุณสมบัติท่องเที่ยว
4 4 เติม Ai-Stockist
5 5 Gift Voucher
6 6 คอร์สอบรม
*/
              $sTable = DB::select("

                SELECT db_orders.id,action_date,purchase_type_id_fk,0 as type,customers_id_fk,sum_price,invoice_code,approve_status,shipping_price,db_orders.updated_at,dataset_pay_type.detail as pay_type,cash_price,credit_price,fee_amt,transfer_price,aicash_price,total_price,db_orders.created_at,status_sent_money
                FROM db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                WHERE db_orders.purchase_type_id_fk in (4,5)

                UNION ALL 

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
                'ai_cash' as pay_type,cash_price,
                credit_price,fee_amt,transfer_price,
                0 as aicash_price,total_amt as total_price,'' ,status_sent_money
                FROM db_add_ai_cash
                ORDER BY created_at DESC

              ");

        }else{
       
            $sTable = DB::select("

                SELECT db_orders.id,action_date,purchase_type_id_fk,0 as type,customers_id_fk,sum_price,invoice_code,approve_status,shipping_price,db_orders.updated_at,dataset_pay_type.detail as pay_type,cash_price,credit_price,fee_amt,transfer_price,aicash_price,total_price,db_orders.created_at,status_sent_money
                FROM db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                WHERE 1
                $action_user_011
                $startDate
                $endDate
                $purchase_type_id_fk
                $customer_code
                $customer_name
                $invoice_code
                $action_user_02
                $status_sent_money
                $approve_status

                UNION ALL 

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
                'ai_cash' as pay_type,cash_price,
                credit_price,fee_amt,transfer_price,
                0 as aicash_price,total_amt as total_price,'' ,status_sent_money
                FROM db_add_ai_cash
                WHERE 1 AND db_add_ai_cash.approve_status<>4
                $action_user_01
                $startDate2
                $endDate2

                ORDER BY created_at DESC
                

              ");
       
       }

      // $sTable = \App\Models\Backend\Frontstore::search();
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('created_at', function($row) {
        $d = strtotime(@$row->created_at);
        return date("Y-m-d",$d)."<br/>".date("H:i:s",$d);
      })
      ->escapeColumns('created_at')
      ->addColumn('customer_name', function($row) {
        $Customer = DB::select(" select * from customers where id=".@$row->customers_id_fk." ");
        return "[".@$Customer[0]->user_name.'] <br>'.@$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
      })
      ->addColumn('purchase_type', function($row) {
        if(@$row->purchase_type_id_fk>0){
          @$purchase_type = DB::select(" select * from dataset_orders_type where id=".@$row->purchase_type_id_fk." ");
          return @$purchase_type[0]->detail;
        }
      })
      ->addColumn('status', function($row) {

// `approve_status` int(11) DEFAULT '0' COMMENT ' 0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ,9=Finished (ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย)''',
// ของเดิม
// `approve_status` int(11) DEFAULT '0' COMMENT '0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ',
// แก้ใหม่
// `approve_status` int(11) DEFAULT '0' COMMENT ' 1=รออนุมัติ,2=อนุมัติแล้ว,3=รอชำระ,4=รอจัดส่ง,5=ยกเลิก,6=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย',

        if(@$row->approve_status!=""){
          @$approve_status = DB::select(" select * from `dataset_approve_status` where id=".@$row->approve_status." ");
          // return $purchase_type[0]->orders_type;
          return @$approve_status[0]->txt_desc;
        }else{
          return "No completed";
        }
     
      })
      ->addColumn('shipping_price', function($row) {
          if(@$row->shipping_price){
            return @number_format(@$row->shipping_price,0);
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
      ->addColumn('total_price', function($row) {
        // cash_pay,credit_price,fee_amt,transfer_price,aicash_price
          $total_price = '';
          if(@$row->cash_price!=0){
             $total_price .= $row->cash_price;
          }
          if(@$row->credit_price!=0){
             $total_price .= $row->credit_price+$row->fee_amt;
          }
          if(@$row->transfer_price!=0){
             $total_price .= $row->transfer_price;
          }
          if(@$row->aicash_price!=0){
             $total_price .= $row->aicash_price;
          }
          return @number_format(@$total_price,2);

      })
      ->addColumn('status_delivery', function($row) {
          $r = DB::select(" select status_delivery FROM db_orders WHERE id = ".$row->id." ");
          if($r)
          return $r[0]->status_delivery;

      })   
      // ->addColumn('status_sent_money', function($row) {
      //     $r = DB::select(" select status_sent_money FROM db_orders WHERE id = ".$row->id." ");
      //     if($r)
      //     return $r[0]->status_sent_money;

      // })             
      ->make(true);
    }



}
