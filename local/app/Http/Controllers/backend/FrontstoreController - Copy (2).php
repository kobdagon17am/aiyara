<?php

namespace App\Http\Controllers\backend;

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
use App\Models\Frontend\CourseCheckRegis;
use App\Http\Controllers\Frontend\Fc\CancelOrderController;


class FrontstoreController extends Controller
{

    public function index(Request $request)
    {

      // $r = RunNumberPayment::run_number_order(1);
      // dd($r);

      // dd($request);
      // dd(\Auth::user()->position_level);
      // dd(\Auth::user()->branch_id_fk);
      $branch_id_fk = \Auth::user()->branch_id_fk;
      $user_login_id = \Auth::user()->id;
      $sUser = DB::select(" select * from ck_users_admin ");
      // $sApproveStatus = DB::select(" select * from dataset_approve_status where status=1 and id not in (1,2) "); // 1,2 เหมือนว่าไม่ได้ใช้แล้ว
      // $sApproveStatus = DB::select(" select * from dataset_approve_status where status=1 and id not in (3) "); // 1,2 เหมือนว่าไม่ได้ใช้แล้ว
      $sApproveStatus = DB::select(" select * from dataset_approve_status where status=1 and id not in (3) "); // 1,2 เหมือนว่าไม่ได้ใช้แล้ว

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
              WHERE db_orders.branch_id_fk=$branch_id_fk
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
              WHERE db_add_ai_cash.approve_status<>4
              $w1

           ");

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

            if($value->approve_status==1){
              $approve_status_1 += 1;
              $sum_price_1 += $value->sum_price;
              $pv_1 += @$value->pv;
            }


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


      $r_invoice_code = DB::select(" SELECT code_order FROM db_orders where code_order <>'' ");
      // dd($r_invoice_code);


      return View('backend.frontstore.index')->with(
        array(
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

      $sPurchase_type = DB::select(" select * from dataset_orders_type where status=1 and lang_id=1 order by id limit 6");

      $sPay_type = DB::select(" select * from dataset_pay_type where id > 4 ");

      $sDistribution_channel = DB::select(" select * from dataset_distribution_channel where status=1  ");
      $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();

      $User_branch_id = \Auth::user()->branch_id_fk;

      $sBranchs = \App\Models\Backend\Branchs::get();

      $sBusiness_location = \App\Models\Backend\Business_location::get();

      $sFee = \App\Models\Backend\Fee::get();

      // $aistockist = DB::select(" select * from customers_aistockist_agency where aistockist=1 ");
      // $agency = DB::select(" select * from customers_aistockist_agency where agency=1 ");

      // $aistockist = DB::select(" select * from customers where aistockist_status=1 ");
      // $agency = DB::select(" select * from customers where agency_status=1 ");


      $DATE_CREATED = Carbon::today()->format('Y-m-d');
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
        // 4 Supervisor/Manager
        // 2 CS แผนกขาย
        if($position_level==4){
          if( $DATE_CREATED>=$DATE_YESTERDAY && $DATE_CREATED<=$DATE_TODAY  ) $ChangePurchaseType = 1;
        }else{
          if($DATE_CREATED==$DATE_TODAY) $ChangePurchaseType = 1;
        }

      }

      return View('backend.frontstore.form')->with(
        array(
           'sPurchase_type'=>$sPurchase_type,
           'sProductUnit'=>$sProductUnit,
           'sDistribution_channel'=>$sDistribution_channel,
           'Products'=>$Products,
           'sBusiness_location'=>$sBusiness_location,
           'sFee'=>$sFee,
           'sBranchs'=>$sBranchs,
           'User_branch_id'=>$User_branch_id,
           // 'aistockist'=>$aistockist,
           // 'agency'=>$agency,
           'sPay_type'=>$sPay_type,
           'ChangePurchaseType'=>$ChangePurchaseType,

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


              // $data =  CancelOrderController::cancel_order('204',\Auth::user()->id,'1','admin');
              // dd($data);

      // $Check = \App\Models\Frontend\Product::product_list_select_promotion_all('1','A56');
      // dd($Check);

      // $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme('9','1','1','admin');
      // dd($data);


//       $CourseCheckRegis = \App\Models\Frontend\CourseCheckRegis::check_register_all(1,'A0000014');
//        if($CourseCheckRegis){
//         echo "<pre>";
//         // foreach (@$CourseCheckRegis as $key => $value) {
//         //   print_r(@$value);
//         // }

//          $arr = [];
//           for ($i=0; $i < count(@$CourseCheckRegis) ; $i++) {

//                $c = array_column($CourseCheckRegis,$i);
//                foreach ($c as $key => $value) {
//                 if($value['status'] == "fail"){
//                    // echo ($value['message'])."<br>";
//                    array_push($arr,$value['message']);
//                 }
//                }

//                $im = implode(',',$arr);

//           }
//           echo $im;

//       }
// dd();

      $sRow = \App\Models\Backend\Frontstore::find($id);
      // dd($sRow);
      // dd($sRow->business_location_id_fk);

      // dd($sRow->customers_id_fk);
      $sCustomer = DB::select(" select * from customers where id=".$sRow->customers_id_fk." ");
      @$CusName = (@$sCustomer[0]->user_name." : ".@$sCustomer[0]->prefix_name.$sCustomer[0]->first_name." ".@$sCustomer[0]->last_name);
      @$user_name = @$sCustomer[0]->user_name;
      // dd($CusName);
      // dd($user_name);
        if(@$sRow->aistockist){
              $sCusAistockist = DB::select(" select * from customers where id=".$sRow->aistockist." ");
              @$CusAistockistName = @$sCusAistockist[0]->user_name." : ".@$sCusAistockist[0]->prefix_name.$sCusAistockist[0]->first_name." ".@$sCusAistockist[0]->last_name;
        }else{
             @$CusAistockistName = '';
        }

        // dd(@$CusAistockistName);

        if(@$sRow->agency){
                   $sCusAgency = DB::select(" select * from customers where id=".$sRow->agency." ");
                @$CusAgencyName = @$sCusAgency[0]->user_name." : ".@$sCusAgency[0]->prefix_name.$sCusAgency[0]->first_name." ".@$sCusAgency[0]->last_name;
        }else{
             @$CusAgencyName = '';
        }

                // dd(@$CusAgencyName);


      if(!empty($sRow->member_id_aicash)){
          $sAicash = DB::select(" select * from customers where id=".$sRow->member_id_aicash." ");
          // dd($sAicash);
          $Cus_Aicash = @$sAicash[0]->ai_cash;
          $Customer_id_Aicash = @$sRow->member_id_aicash;
          $Customer_name_Aicash = (@$sAicash[0]->user_name." : ".@$sAicash[0]->prefix_name.$sAicash[0]->first_name." ".@$sAicash[0]->last_name);
          // dd($Customer_name_Aicash);
      }else{
          $sAicash  = NULL;
          $Cus_Aicash = "0.00";
          $Customer_id_Aicash = "";
          $Customer_name_Aicash = "";
      }


      $sBranchs = DB::select(" select * from branchs where id=".$sRow->branch_id_fk." ");
      $BranchName = $sBranchs[0]->b_name;

      $Purchase_type = DB::select(" select * from dataset_orders_type where id=".$sRow->purchase_type_id_fk." ");
      $PurchaseName = @$Purchase_type[0]->orders_type;


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

        /* dataset_orders_type
        1 ทำคุณสมบัติ
        2 รักษาคุณสมบัติรายเดือน
        3 รักษาคุณสมบัติท่องเที่ยว
        4 เติม Ai-Stockist
        5 แลก Gift Voucher
        */

      if(!empty($sRow->purchase_type_id_fk) && $sRow->purchase_type_id_fk!=5) {
        $sPurchase_type = DB::select(" select * from dataset_orders_type where id<>5 and status=1 and lang_id=1 order by id limit 6");
      }else{
        $sPurchase_type = DB::select(" select * from dataset_orders_type where status=1 and lang_id=1 order by id limit 6");
      }
      $sPay_type = DB::select(" select * from dataset_pay_type where id > 4 and id <=11 ");

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
      // dd($sRow->customers_id_fk);
      $giftvoucher_this = DB::select("

        SELECT
            db_giftvoucher_cus.id,
            db_giftvoucher_cus.giftvoucher_code_id_fk,
            db_giftvoucher_cus.customer_username,
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
            db_giftvoucher_code.status = 1

             "); //AND expiry_date>=now()

      // dd($giftvoucher_this);

      $giftvoucher_this = @$giftvoucher_this[0]->giftvoucher_value;

      $rs = DB::select(" SELECT count(*) as cnt FROM db_order_products_list WHERE frontstore_id_fk=$id ");



       $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total from db_order_products_list WHERE frontstore_id_fk=$id GROUP BY frontstore_id_fk ");
       // dd($sFrontstoreDataTotal);
       if($sFrontstoreDataTotal){
          $vat = floatval(@$sFrontstoreDataTotal[0]->total) - (floatval(@$sFrontstoreDataTotal[0]->total)/1.07) ;
          $vat = $vat > 0 ? $vat : 0 ;
          $product_value = str_replace(",","",floatval(@$sFrontstoreDataTotal[0]->total) - $vat) ;
          $product_value = $product_value > 0 ? $product_value : 0 ;
          $total = @$sFrontstoreDataTotal[0]->total>0 ? @$sFrontstoreDataTotal[0]->total : 0 ;
          DB::select(" UPDATE db_orders SET product_value=".($product_value).",tax=".($vat).",sum_price=".($total)." WHERE id=$id ");
        }else{
          DB::select(" UPDATE db_orders SET product_value=0,tax=0,sum_price=0 WHERE id=$id  ");
        }

        $sAccount_bank = \App\Models\Backend\Account_bank::get();
        // dd($sAccount_bank);

      // $type = $sRow->purchase_type_id_fk;
      $pv_total = $sRow->pv_total;
      $customer_pv = \Auth::user()->pv ? \Auth::user()->pv : 0 ;
      $check_giveaway = GiveawayController::check_giveaway($sRow->business_location_id_fk,$sRow->purchase_type_id_fk,$customer_pv,$pv_total);
      // dd($sRow->business_location_id_fk);
      // dd($sRow->purchase_type_id_fk);
      // dd($customer_pv);
      // dd($pv_total);
      if($check_giveaway){
              $arr = [];
              for ($i=0; $i < count($check_giveaway) ; $i++) {
                   $c = array_column($check_giveaway,$i);
                   foreach ($c as $key => $value) {
                   //  // if($value['status'] == "fail"){
                       // array_push($arr,$value->status);
                   //  // }
                   }
                   // $im = implode(',',$arr);
              }
              // print_r($im);
      }

      // dd($check_giveaway);


      $sPay_type_purchase_type6 = DB::select(" select * from dataset_pay_type where id > 4 and id <=11 ORDER BY id=5 DESC ");

      // CourseCheckRegis::cart_check_register($value['id'], $value['quantity'],user_name);
      // $chek_course = CourseCheckRegis::cart_check_register('1', '1',@$user_name);
      // dd($sRow->customers_id_fk);
      // dd($chek_course);

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
        // 4 Supervisor/Manager
        // 2 CS แผนกขาย
        if($position_level==4){
          if( $DATE_CREATED>=$DATE_YESTERDAY && $DATE_CREATED<=$DATE_TODAY  ) $ChangePurchaseType = 1;
        }else{
          if($DATE_CREATED==$DATE_TODAY) $ChangePurchaseType = 1;
        }

      }

      return View('backend.frontstore.form')->with(
        array(
           'sRow'=>$sRow,
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
           'user_name'=>$user_name,
           'sAicash'=>$sAicash,
           'Cus_Aicash'=>$Cus_Aicash,
           'Customer_id_Aicash'=>$Customer_id_Aicash,
           'Customer_name_Aicash'=>$Customer_name_Aicash,
           'BranchName'=>$BranchName,
           'PurchaseName'=>$PurchaseName,
           'giftvoucher_this'=>$giftvoucher_this,
           'sAccount_bank'=>$sAccount_bank,
           'sPay_type'=>$sPay_type,
           'shipping_special'=>$shipping_special,
           'sFrontstoreDataTotal'=>$sFrontstoreDataTotal,
           'check_giveaway'=>$check_giveaway,
           'sPay_type_purchase_type6'=>$sPay_type_purchase_type6,
           'ChangePurchaseType'=>$ChangePurchaseType,
           'CusAistockistName'=>@$CusAistockistName,
           'CusAgencyName'=>@$CusAgencyName,
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


      // $sAicash = DB::select(" select * from customers where id=".$sRow->member_id_aicash." ");
      // $Cus_Aicash = @$sAicash[0]->ai_cash;
      // $Cus_name_Aicash = (@$sAicash[0]->user_name." : ".@$sAicash[0]->prefix_name.$sAicash[0]->first_name." ".@$sAicash[0]->last_name);


      if(!empty($sRow->member_id_aicash)){
          $sAicash = DB::select(" select * from customers where id=".$sRow->member_id_aicash." ");
          // dd($sAicash);
          $Cus_Aicash = @$sAicash[0]->ai_cash;
          $Cus_Aicash = @$sRow->member_id_aicash;
          $Cus_name_Aicash = (@$sAicash[0]->user_name." : ".@$sAicash[0]->prefix_name.$sAicash[0]->first_name." ".@$sAicash[0]->last_name);
          // dd($Customer_name_Aicash);
      }else{
          $sAicash  = NULL;
          $Cus_Aicash = "0.00";
          $Cus_Aicash = "";
          $Cus_name_Aicash = "";
      }

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
            db_giftvoucher_cus.customer_username,
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
          $vat = $vat > 0 ? $vat : 0 ;
          $product_value = str_replace(",","",floatval(@$sFrontstoreDataTotal[0]->total) - $vat) ;
          $product_value = $product_value > 0 ? $product_value : 0 ;
          $total = @$sFrontstoreDataTotal[0]->total>0 ? @$sFrontstoreDataTotal[0]->total : 0 ;
          DB::select(" UPDATE db_orders SET product_value=".($product_value).",tax=".($vat).",sum_price=".($total)." WHERE id=$id ");
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
        // 4 Supervisor/Manager
        // 2 CS แผนกขาย
        if($position_level==4){
          if( $DATE_CREATED>=$DATE_YESTERDAY && $DATE_CREATED<=$DATE_TODAY  ) $ChangePurchaseType = 1;
        }else{
          if($DATE_CREATED==$DATE_TODAY) $ChangePurchaseType = 1;
        }

      }

      return View('backend.frontstore.viewdata')->with(
        array(
           'sRow'=>$sRow,
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
           'Cus_name_Aicash'=>$Cus_name_Aicash,
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
      // dd($request->transfer_money_datetime." : AAAA");

      // dd($request->all());

         if(isset($request->pay_type_transfer_slip) && $request->pay_type_transfer_slip=='1'){

          // dd($request->all());

           // dd($request->transfer_money_datetime." : CCCC ");

             $sRow = \App\Models\Backend\Frontstore::find($request->frontstore_id);

             if ($request->hasFile('image01')) {
                  @UNLINK(@$sRow->file_slip);
                  $this->validate($request, [
                    'image01' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                  ]);
                  $image = $request->file('image01');
                  $name = 'S2'.time() . '.' . $image->getClientOriginalExtension();
                  $image_path = 'local/public/files_slip/'.date('Ym').'/';
                  $image->move($image_path, $name);
                  $sRow->file_slip = $image_path.$name;
                  DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`, `create_at`, `update_at`)
                   VALUES
                   ('".request('customers_id_fk')."', '', '".$sRow->code_order."', '$image_path', '$name', now(), now()) ");
                  $lastInsertId_01 = DB::getPdo()->lastInsertId();
                }

             if ($request->hasFile('image02')) {
                  @UNLINK(@$sRow->file_slip_02);
                  $this->validate($request, [
                    'image02' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                  ]);
                  $image = $request->file('image02');
                  $name = 'S2'.time() . '.' . $image->getClientOriginalExtension();
                  $image_path = 'local/public/files_slip/'.date('Ym').'/';
                  $image->move($image_path, $name);
                  $sRow->file_slip_02 = $image_path.$name;
                  DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`, `create_at`, `update_at`)
                   VALUES
                   ('".request('customers_id_fk')."', '', '".$sRow->code_order."', '$image_path', '$name', now(), now()) ");
                  $lastInsertId_02 = DB::getPdo()->lastInsertId();
                }

               if ($request->hasFile('image03')) {
                  @UNLINK(@$sRow->file_slip_03);
                  $this->validate($request, [
                    'image03' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                  ]);
                  $image = $request->file('image03');
                  $name = 'S3'.time() . '.' . $image->getClientOriginalExtension();
                  $image_path = 'local/public/files_slip/'.date('Ym').'/';
                  $image->move($image_path, $name);
                  $sRow->file_slip_03 = $image_path.$name;
                  DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`, `create_at`, `update_at`)
                   VALUES
                   ('".request('customers_id_fk')."', '', '".$sRow->code_order."', '$image_path', '$name', now(), now()) ");
                  $lastInsertId_03 = DB::getPdo()->lastInsertId();
                }

              $sRow->account_bank_id = request('account_bank_id');

              $sRow->transfer_money_datetime = request('transfer_money_datetime')?request('transfer_money_datetime'):NULL;
              $sRow->transfer_money_datetime_02 = request('transfer_money_datetime_02')?request('transfer_money_datetime_02'):NULL;
              $sRow->transfer_money_datetime_03 = request('transfer_money_datetime_03')?request('transfer_money_datetime_03'):NULL;
              $sRow->note_fullpayonetime = request('note_fullpayonetime');
              $sRow->note_fullpayonetime_02 = request('note_fullpayonetime_02');
              $sRow->note_fullpayonetime_03 = request('note_fullpayonetime_03');
              $sRow->pay_with_other_bill = request('pay_with_other_bill');
              $sRow->pay_with_other_bill_note = request('pay_with_other_bill_note');

              $sRow->check_press_save = 2 ;

             // กรณีโอนชำระ
              if(request('pay_type_id_fk')==8 || request('pay_type_id_fk')==10 || request('pay_type_id_fk')==11){
                   $sRow->approve_status = 1 ;
                   $sRow->order_status_id_fk = 2  ;
              }else{
                  $sRow->approve_status = 2 ;
                  $sRow->order_status_id_fk = 5  ;
              }

              $sRow->save();

                // return redirect()->to(url("backend/frontstore/".$request->frontstore_id."/edit"));
             return redirect()->to(url("backend/frontstore"));


         }else if(isset($request->receipt_save_list)){

          // dd($request->all());
          // dd($request->transfer_money_datetime." : BBBB");

              $sRow = \App\Models\Backend\Frontstore::find($request->frontstore_id);
              // dd($sRow);

              $sRow->date_setting_code = date('ym');

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
              $sRow->transfer_money_datetime = request('transfer_money_datetime')?request('transfer_money_datetime'):NULL;
              $sRow->transfer_money_datetime_02 = request('transfer_money_datetime_02')?request('transfer_money_datetime_02'):NULL;
              $sRow->transfer_money_datetime_03 = request('transfer_money_datetime_03')?request('transfer_money_datetime_03'):NULL;

              $sRow->note_fullpayonetime = request('note_fullpayonetime');
              $sRow->note_fullpayonetime_02 = request('note_fullpayonetime_02');
              $sRow->note_fullpayonetime_03 = request('note_fullpayonetime_03');

              $sRow->pay_with_other_bill = request('pay_with_other_bill');
              $sRow->pay_with_other_bill_note = request('pay_with_other_bill_note');


              if(empty(request('shipping_price'))){

                $sum_price = str_replace(',','',request('sum_price'));
                $fee_amt = request('fee_amt')>0 ? str_replace(',','',request('fee_amt')) : 0 ;
                // dd(str_replace(',','',request('fee_amt')));
                // dd($fee_amt);
                $sRow->total_price    =  $sum_price  + $fee_amt ;

              }

              // dd("976");

              $sRow->action_user = \Auth::user()->id;
              $sRow->action_date = date('Y-m-d H:i:s');

              $lastInsertId_01 = 0 ;
              $lastInsertId_02 = 0 ;
              $lastInsertId_03 = 0 ;

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

                  DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`, `create_at`, `update_at`)
                   VALUES
                   ('".request('customers_id_fk')."', '', '".$sRow->code_order."', '$image_path', '$name', now(), now()) ");

                  $lastInsertId_01 = DB::getPdo()->lastInsertId();

                }

               if ($request->hasFile('image02')) {
                  @UNLINK(@$sRow->file_slip_02);
                  $this->validate($request, [
                    'image02' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                  ]);
                  $image = $request->file('image02');
                  $name = 'S2'.time() . '.' . $image->getClientOriginalExtension();
                  $image_path = 'local/public/files_slip/'.date('Ym').'/';
                  $image->move($image_path, $name);
                  $sRow->file_slip_02 = $image_path.$name;
                  DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`, `create_at`, `update_at`)
                   VALUES
                   ('".request('customers_id_fk')."', '', '".$sRow->code_order."', '$image_path', '$name', now(), now()) ");
                  $lastInsertId_02 = DB::getPdo()->lastInsertId();
                }

               if ($request->hasFile('image03')) {
                  @UNLINK(@$sRow->file_slip_03);
                  $this->validate($request, [
                    'image03' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                  ]);
                  $image = $request->file('image03');
                  $name = 'S3'.time() . '.' . $image->getClientOriginalExtension();
                  $image_path = 'local/public/files_slip/'.date('Ym').'/';
                  $image->move($image_path, $name);
                  $sRow->file_slip_03 = $image_path.$name;
                  DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`, `create_at`, `update_at`)
                   VALUES
                   ('".request('customers_id_fk')."', '', '".$sRow->code_order."', '$image_path', '$name', now(), now()) ");
                  $lastInsertId_03 = DB::getPdo()->lastInsertId();
                }
              // PvPayment::PvPayment_type_confirme($sRow->id,\Auth::user()->id,'1','admin');
              //id_order,id_admin,1 ติดต่อหน้าร้าน 2 ช่องทางการจำหน่ายอื่นๆ  dataset_distribution_channel>id  ,'customer หรือ admin'

// dd(request('sentto_branch_id'));
// dd(request('branch_id_fk'));
// dd($request->delivery_location);

              $db_orders = DB::select(" select invoice_code from db_orders where id=".$sRow->id." ");

             if(@$request->delivery_location  == 0 || @$request->delivery_location  == 4 ){
                   $sentto_branch_id = request('sentto_branch_id')?request('sentto_branch_id'):0;
                   $sRow->sentto_branch_id    = request('sentto_branch_id');
                   DB::select("UPDATE db_orders SET sentto_branch_id=".$sentto_branch_id.", address_sent_id_fk='0' WHERE (id='".$request->frontstore_id."')");
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


// dd('1215');
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

`approve_status` int(11) DEFAULT '0' COMMENT 'Ref>dataset_approve_status>id',
1 รออนุมัติ
2 อนุมัติแล้ว
3 รอชำระ
4 รอจัดส่ง
5 ยกเลิก
6 ไม่อนุมัติ
9 Finished

`order_status_id_fk` int(11) DEFAULT NULL COMMENT '(ยึดตาม* dataset_order_status )',
1 รอส่งเอกสารการชำระ
2 รอตรวจสอบการชำระ
3 เอกสารไม่ผ่านการตรวจสอบ
5 กำลังจัดเตรียมสินค้า
6 กำลังจัดส่งสินค้า
7 ได้รับสินค้าแล้ว
4 รับสินค้าที่สาขา
8 ยกเลิก(Cancel)

*/

 // ประเภทการโอนเงินต้องรอ อนุมัติก่อน  approve_status
// dd(request('pay_type_id_fk'));

/*
===================================
1 เงินโอน
8 เครดิต + เงินโอน
10  เงินโอน + เงินสด
11  เงินโอน + Ai-Cash
12  Gift Voucher + เงินโอน
===================================
3 Ai-Cash
6 เงินสด + Ai-Cash
9 เครดิต + Ai-Cash
===================================
*/
// กลุ่มนี้ต้องรออนุมัติก่อน
// dd('1274');

              if(request('pay_type_id_fk')==1 || request('pay_type_id_fk')==8 || request('pay_type_id_fk')==10 || request('pay_type_id_fk')==11 || request('pay_type_id_fk')==12 || request('pay_type_id_fk')==3 || request('pay_type_id_fk')==6 || request('pay_type_id_fk')==9){
                  $sRow->approve_status = 1  ;
                  $sRow->order_status_id_fk = 2  ;
              }else{
                // dd('1280');
                  $sRow->approve_status = 2 ;
                  $sRow->order_status_id_fk = 5  ;
              }

              $sRow->check_press_save = 2;

              $sRow->save();

              DB::select(" UPDATE `payment_slip` SET `order_id`=$sRow->id ,`code_order`='$sRow->code_order' WHERE (`id`=$lastInsertId_01);");
              DB::select(" UPDATE `payment_slip` SET `order_id`=$sRow->id ,`code_order`='$sRow->code_order' WHERE (`id`=$lastInsertId_02);");
              DB::select(" UPDATE `payment_slip` SET `order_id`=$sRow->id ,`code_order`='$sRow->code_order' WHERE (`id`=$lastInsertId_03);");

              // $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme($sRow->id,\Auth::user()->id,'1','admin');

              DB::select(" UPDATE db_orders SET pv_total=0 WHERE pv_total is null; ");

              if(request('pay_type_id_fk')==1 || request('pay_type_id_fk')==8 || request('pay_type_id_fk')==10 || request('pay_type_id_fk')==11 || request('pay_type_id_fk')==12 || request('pay_type_id_fk')==3 || request('pay_type_id_fk')==6 || request('pay_type_id_fk')==9){
                   DB::select(" UPDATE `db_orders` SET `approve_status`=1 WHERE (`id`=".$sRow->id.") ");
              }else{
                   DB::select(" UPDATE `db_orders` SET `approve_status`=2 WHERE (`id`=".$sRow->id.") ");
              }

//   `check_press_save` int(1) DEFAULT '0' COMMENT '1=มีการเลือกสินค้าแล้ว 2=มีการกดปุ่ม save แล้ว (เอาไว้เช็คกรณีซื้อที่หลังบ้าน เพื่อไม่ให้การคำนวณเงินผิดเพี้ยนไปจากเดิม)',
//   `approve_status` int(11) DEFAULT '0' COMMENT '1=รออนุมัติ,2=อนุมัติแล้ว,3=รอชำระ,4=รอจัดส่ง,5=ยกเลิก,6=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย) > Ref>dataset_approve_status>id',
// เช็คดูก่อน ว่า check_press_save = 2 + approve_status <> 0 และดูด้วยว่า มีรหัส code_order แล้วหรือยัง

              if($sRow->check_press_save==2 && $sRow->approve_status>0 && $sRow->code_order==""){

                 $branchs = DB::select("SELECT * FROM branchs where id=".$request->this_branch_id_fk."");
                 DB::select(" UPDATE `db_orders` SET date_setting_code='".date('ym')."' WHERE (`id`=".$sRow->id.") ");
                 $code_order = RunNumberPayment::run_number_order($branchs[0]->business_location_id_fk);
                 DB::select(" UPDATE `db_orders` SET `code_order`='$code_order' WHERE (`id`=".$sRow->id.") ");

              }


              if($sRow->check_press_save==2 && $sRow->approve_status>0 ){

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
                        FROM db_orders WHERE (`id`=".$sRow->id.") ;
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

                      if(@$request->delivery_location==1){

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



                      if(@$request->delivery_location==2){

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



                      if(@$request->delivery_location==3){

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

             return redirect()->to(url("backend/frontstore"));


        }else{

          // dd($request->all());
          return $this->form($id);
        }


    }

   public function form($id=NULL)
    {
      // dd($id);
      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow = \App\Models\Backend\Frontstore::find($id);
            // $invoice_code = $sRow->invoice_code;

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

            //   dd($sRow);
            // // กรณีโอนชำระ
            //   if(request('pay_type_id_fk')==8 || request('pay_type_id_fk')==10 || request('pay_type_id_fk')==11){
            //        $sRow->approve_status = 1 ;
            //        $sRow->order_status_id_fk = 2  ;
            //   }else{
            //       $sRow->approve_status = 2 ;
            //       $sRow->order_status_id_fk = 5  ;
            //   }

                  // $sRow->approve_status = 2 ;

          $sRow->save();

          DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");
          DB::select(" UPDATE db_orders SET pv_total=0 WHERE pv_total is null; ");


            // return "to this";
          // dd($sRow->id);

          \DB::commit();

           return redirect()->to(url("backend/frontstore/".$sRow->id."/edit"));
           // return redirect()->to(url("backend/frontstore"));


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





   public function getSumCostActionUser(Request $req)
    {

         // return $request;
          $user_login_id = \Auth::user()->id;
          $sPermission = \Auth::user()->permission ;
          if($sPermission==1){
              $action_user_011 = "";
              $action_user_012 = "";
          }else{

                if(\Auth::user()->position_level=='3' || \Auth::user()->position_level=='4'){
                    $action_user_011 = " AND db_orders.branch_id_fk = '".(\Auth::user()->branch_id_fk)."' " ;
                    $action_user_012 = " AND db_add_ai_cash.branch_id_fk = '".(\Auth::user()->branch_id_fk)."' " ;
                }else{
                    $action_user_011 = " AND db_orders.action_user = $user_login_id ";
                    $action_user_012 = " AND db_add_ai_cash.action_user = $user_login_id ";
                }
          }


         if(!empty($req->startDate)){
               $startDate1 = " AND DATE(db_orders.created_at) >= '".$req->startDate."' " ;
               $startDate2 = " AND DATE(db_add_ai_cash.created_at) >= '".$req->startDate."' " ;
               $startDate3 = date("d-m-Y",strtotime($req->startDate)) ;
               $sD3 = $startDate3;
            }else{
               $startDate1 = " AND DATE(db_orders.created_at) >= CURDATE() " ;
               $startDate2 = " AND DATE(db_add_ai_cash.created_at) >= CURDATE() " ;
               $startDate3 = date("d-m-Y") ;
               $sD3 = date("d-m-Y");
            }

            if(!empty($req->endDate)){
               $endDate1 = " AND DATE(db_orders.created_at) <= '".$req->endDate."' " ;
               $endDate2 = " AND DATE(db_add_ai_cash.created_at) <= '".$req->endDate."' " ;
               $endDate3 = date("d-m-Y",strtotime($req->endDate)) ;
               $eD3 = " To ".$endDate3 ;
            }else{
               $endDate1 = "";
               $endDate2 = "";
               $endDate3 = date("Y-m-d") ;
               $eD3 = "";
            }

            $sD3 = $sD3.$eD3;


            if(!empty($req->invoice_code)){
               $invoice_code = " AND db_orders.code_order = '".$req->invoice_code."' " ;
               $invoice_code2 = " AND db_add_ai_cash.code_order = '".$req->invoice_code."' " ;
            }else{
               $invoice_code = "";
               $invoice_code2 = "";
            }

 // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

          if(!empty($req->purchase_type_id_fk)){
             $purchase_type_id_fk = " AND db_orders.purchase_type_id_fk = '".$req->purchase_type_id_fk."' " ;
             if($req->purchase_type_id_fk==4){
               $purchase_type_id_fk_02 = "";
             }else{
               $purchase_type_id_fk_02 = " AND db_add_ai_cash.id=0 ";
             }
          }else{
             $purchase_type_id_fk = "";
             $purchase_type_id_fk_02 = "";
          }

          if(!empty($req->customer_code)){
             $customer_code = " AND db_orders.customers_id_fk = '".$req->customer_code."' " ;
             $customer_code_02 = " AND db_add_ai_cash.customer_id_fk = '".$req->customer_code."' " ;
          }else{
             $customer_code = "";
             $customer_code_02 = "";
          }

          if(!empty($req->customer_name)){
             $customer_name = " AND db_orders.customers_id_fk = '".$req->customer_name."' " ;
             $customer_name_02 = " AND db_add_ai_cash.customer_id_fk = '".$req->customer_name."' " ;
          }else{
             $customer_name = "";
             $customer_name_02 = "";
          }

          if(!empty($req->action_user)){
             $action_user_02 = " AND db_orders.action_user = '".$req->action_user."' " ;
             $action_user_022 = " AND db_add_ai_cash.action_user = '".$req->action_user."' " ;
          }else{
             $action_user_02 = "";
             $action_user_022 = "";
          }

          if(isset($req->status_sent_money)){
             $status_sent_money = " AND db_orders.status_sent_money = ".$req->status_sent_money." " ;
             $status_sent_money_02 = " AND db_add_ai_cash.status_sent_money = ".$req->status_sent_money." " ;
          }else{
             $status_sent_money = "";
             $status_sent_money_02 = "";
          }

          if(isset($req->approve_status)){
             $approve_status = " AND db_orders.approve_status = ".$req->approve_status." " ;
             $approve_status_02 = " AND db_add_ai_cash.approve_status = ".$req->approve_status." " ;
          }else{
             $approve_status = "";
             $approve_status_02 = "";
          }

        if(isset($req->viewcondition)){
          if(isset($req->viewcondition) && $req->viewcondition=="ViewBuyNormal"){
            $viewcondition_01 = ' and db_orders.purchase_type_id_fk not in (4,5) ';
            $viewcondition_02 = ' and db_add_ai_cash.id=0 ';
          }else if(isset($req->viewcondition) && $req->viewcondition=="ViewBuyVoucher"){
            $viewcondition_01 = ' and db_orders.purchase_type_id_fk in (4,5) ';
            $viewcondition_02 = '';
          }else{
            $viewcondition_01 = '';
            $viewcondition_02 = '';
          }
        }else{
          $viewcondition_01 = '';
          $viewcondition_02 = '';
        }

 // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

           $sDBFrontstoreSumCostActionUser = DB::select("
                SELECT
                db_orders.action_user,
                ck_users_admin.`name` as action_user_name,
                db_orders.pay_type_id_fk,
                dataset_pay_type.detail AS pay_type,
                date(db_orders.action_date) AS action_date,

                SUM(CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) AS credit_price,
                SUM(CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) AS transfer_price,
                SUM(CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) AS fee_amt,
                SUM(CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) AS aicash_price,
                SUM(CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) AS cash_pay,
                SUM(CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END) AS gift_voucher_price,


                SUM(
                (CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) +
                (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
                (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
                (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
                (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) +
                (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
                ) as total_price,

                SUM(
                (CASE WHEN db_orders.shipping_price is null THEN 0 ELSE db_orders.shipping_price END) +
                (CASE WHEN db_orders.shipping_special is null THEN 0 ELSE db_orders.shipping_special END)
                ) AS shipping_price

                FROM
                db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                WHERE db_orders.pay_type_id_fk<>0
              --  AND approve_status!='' AND approve_status!=0 AND approve_status!=5
                $action_user_011
                $startDate1
                $endDate1
                $invoice_code
                $purchase_type_id_fk
                $customer_code
                $customer_name
                $action_user_02
                $status_sent_money
                $approve_status
                $viewcondition_01

                GROUP BY action_user
        ");


        $str_ch = $action_user_011 . $startDate1 . $endDate1 . $invoice_code ;



        $show = '';

        $show .=
         '

          <div class="table-responsive">
            <table class="table table-sm m-0">
              <thead>
                <tr style="background-color: #f2f2f2;"><th colspan="8">
                  '.trans('message.all_payment_list').' ('.$sD3.')
                </th></tr>
                <tr>
                  <th width="10%">'.trans('message.seller').'</th>
                  <th width="5%" class="text-right">'.trans('message.cash').'</th>
                  <th width="5%" class="text-right">'.trans('message.ai_cash').'</th>
                  <th width="5%" class="text-right">'.trans('message.transfer_cash').'</th>
                  <th width="5%" class="text-right">'.trans('message.credit_card').'</th>
                  <th width="5%" class="text-right">'.trans('message.fee').'</th>
                  <th width="10%" class="text-right">'.trans('message.total').'</th>
                  <th width="5%" class="text-right">('.trans('message.transportor_fee').')</th>
                </tr>
              </thead>
              <tbody>';

                    if(@$sDBFrontstoreSumCostActionUser){

                     foreach(@$sDBFrontstoreSumCostActionUser AS $r){
                  @$cnt_row1 += 1;

               $show .= '
                    <tr>
                      <td>'.$r->action_user_name.'</td>
                      <td class="text-right"> '.number_format($r->cash_pay,2).' </td>
                      <td class="text-right"> '.number_format($r->aicash_price,2).' </td>
                      <td class="text-right"> '.number_format($r->transfer_price,2).' </td>
                      <td class="text-right"> '.number_format($r->credit_price,2).' </td>
                      <td class="text-right"> '.number_format($r->fee_amt,2).' </td>
                      <th class="text-right"> '.number_format($r->total_price,2).' </th>
                      <td class="text-right"> '.number_format($r->shipping_price,2).' </td>

                    </tr>';

                   }
                  }

                if(@$cnt_row1>1){


           $sDBFrontstoreTOTAL = DB::select("
                SELECT
                SUM(CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) AS credit_price,
                SUM(CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) AS transfer_price,
                SUM(CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) AS fee_amt,
                SUM(CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) AS aicash_price,
                SUM(CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) AS cash_pay,
                SUM(CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END) AS gift_voucher_price,

                SUM(
                (CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) +
                (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
                (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
                (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
                (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) +
                (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
                ) as total_price,

                SUM(
                (CASE WHEN db_orders.shipping_price is null THEN 0 ELSE db_orders.shipping_price END) +
                (CASE WHEN db_orders.shipping_special is null THEN 0 ELSE db_orders.shipping_special END)
                ) AS shipping_price

                FROM
                db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                WHERE db_orders.pay_type_id_fk<>0
              --  AND approve_status!='' AND approve_status!=0 AND approve_status!=5
                $action_user_011
                $startDate1
                $endDate1
                $invoice_code
                $purchase_type_id_fk
                $customer_code
                $customer_name
                $action_user_02
                $status_sent_money
                $approve_status
                $viewcondition_01

        ");

                $show .= '
                    <tr>
                      <th>Total > </th>
                      <th class="text-right"> '.number_format($sDBFrontstoreTOTAL[0]->cash_pay,2).' </th>
                      <th class="text-right"> '.number_format($sDBFrontstoreTOTAL[0]->aicash_price,2).' </th>
                      <th class="text-right"> '.number_format($sDBFrontstoreTOTAL[0]->transfer_price,2).' </th>
                      <th class="text-right"> '.number_format($sDBFrontstoreTOTAL[0]->credit_price,2).' </th>
                      <th class="text-right"> '.number_format($sDBFrontstoreTOTAL[0]->fee_amt,2).' </th>
                      <th class="text-right"> '.number_format($sDBFrontstoreTOTAL[0]->total_price,2).' </th>
                      <th class="text-right"> '.number_format($sDBFrontstoreTOTAL[0]->shipping_price,2).' </th>
                    </tr>';

                   }

                 $show .= '
                     </tbody>
                   </table>
                 </div>
              <br>
           ';

           $show .= '

           <div class="table-responsive">
            <table class="table table-sm m-0">
              <thead>
                <tr style="background-color: #f2f2f2;"><th colspan="8">
                '.trans('message.payment_ai_cash_list').' ('.$sD3.')
                </th></tr>
                <tr>
                  <th width="10%">'.trans('message.seller').'</th>
                  <th width="5%" class="text-right"> </th>
                  <th width="5%" class="text-right"> </th>
                  <th width="5%" class="text-right"> </th>
                  <th width="5%" class="text-right"> </th>
                  <th width="5%" class="text-right">'.trans('message.list').'</th>
                  <th width="10%" class="text-right">'.trans('message.total').'</th>
                  <th width="5%" class="text-right"> </th>
                </tr>
              </thead>
              <tbody>';

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
              AND approve_status!='' AND approve_status!=0 AND approve_status!=5
              $action_user_012
              $startDate2
              $endDate2
              $invoice_code2
              $purchase_type_id_fk_02
              $customer_code_02
              $customer_name_02
              $action_user_022
              $status_sent_money_02
              $approve_status_02
              $viewcondition_02

              GROUP BY action_user

        ");

                    if(@$sDBFrontstoreUserAddAiCash){

                      foreach(@$sDBFrontstoreUserAddAiCash AS $r){

                      @$cnt_row2 += 1;
                      @$cnt_aicash_amt += 1;
                      @$sum_cnt += $r->cnt;
                      @$sum_sum += $r->sum;


                  $show .= '
                    <tr>
                      <td>'.$r->name.'</td>
                       <td class="text-right"> </td>
                       <td class="text-right"> </td>
                       <td class="text-right"> </td>
                       <td class="text-right"> </td>
                      <td class="text-right">'.@$r->cnt.'</td>
                      <th class="text-right"> '.number_format($r->sum,2).' </th>
                      <td class="text-right"> </td>
                    </tr>';

                  }
                }

                  if(@$cnt_row2>1){

                     $show .= '
                    <tr>
                      <th>Total > </th>
                      <th> </th>
                      <th> </th>
                      <th> </th>
                      <th> </th>
                      <th class="text-right"> '.@$sum_cnt.' </th>
                      <th class="text-right"> '.number_format(@$sum_sum,2).' </th>
                      <th class="text-right"> </th>
                    </tr>';
                  }

                 $show .= '
                     </tbody>
                   </table>
                 </div>
              <br>
           ';

          if($sPermission==1){
              $action_user_0111 = "";
          }else{

              $action_user_0111 = " AND db_orders.action_user = $user_login_id ";
          }

            $ch_user = DB::select("
                SELECT *
                FROM
                db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                WHERE db_orders.pay_type_id_fk<>0
                $action_user_0111

        ");

          if($sPermission==1 || count($ch_user)>0 ){
               $show_tb_sent_money = "";
               $user_login_id = \Auth::user()->id;
          }else{
               $show_tb_sent_money = "display:none;";
          }
// test_clear_sent_money
           $show .= '
                <div id="tb_sent_money" class="table-responsive" style='.$show_tb_sent_money.'>
                  <table class="table table-sm m-0">
                    <thead>
                      <tr style="background-color: #f2f2f2;"><th colspan="8">
                        <span class="" >'.trans('message.daily_cash_list').' ('.trans('message.current_day').' : '.date("d-m-Y").$user_login_id.') </span>
                        </th></tr>
                        <tr>
                          <th class="text-center">'.trans('message.times').'</th>
                          <th class="text-center">'.trans('message.send_reciept_list').'</th>
                          <th class="text-center">'.trans('message.sender').'</th>
                          <th class="text-center">'.trans('message.send_time').'</th>
                          <th class="text-center">Tool</th>
                        </tr>
                      </thead>
                      <tbody>';

        $sDBSentMoneyDaily = DB::select("
              SELECT
              db_sent_money_daily.*,
              ck_users_admin.`name` as sender
              FROM
              db_sent_money_daily
              Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
              WHERE date(db_sent_money_daily.updated_at)=CURDATE() and sender_id=$user_login_id
              order by db_sent_money_daily.time_sent
        ");

                        if(@$sDBSentMoneyDaily){
                         $tt = 1;

                        foreach(@$sDBSentMoneyDaily AS $r){

                        $sOrders = DB::select("

                          SELECT db_orders.code_order ,customers.prefix_name,customers.first_name,customers.last_name
                                      FROM
                                      db_orders Left Join customers ON db_orders.customers_id_fk = customers.id
                                      where db_orders.id in (".$sDBSentMoneyDaily[0]->orders_ids.") AND code_order<>'' AND action_user='$user_login_id' ;

                                      ");
                        // AND code_order<>'' AND action_user='$user_login_id'
                    $show .= '
                        <tr>
                          <td class="text-center">'.$tt.'</td>';

                          if(@$r->status_cancel==0){

                    $show .= '
                          <td class="text-center">
                            <div class="invoice_code_list" data-toggle="tooltip" data-placement="bottom" title="คลิ้กเพื่อดูใบเสร็จทั้งหมด" >';

                              $i = 1;
                              foreach ($sOrders as $key => $value) {
                              $show .=  $value->code_order."<br>";
                              $i++;
                              if($i==4){
                              break;
                              }
                              }
                              if($i>3) $show .= "...";
                              $arr = [];
                              foreach ($sOrders as $key => $value) {
                              array_push($arr,$value->code_order.' :'.(@$value->first_name.' '.@$value->last_name).'<br>');
                              }
                              $arr_inv = implode(",",$arr);

                   $show .= '
                            </div>
                            <input type="hidden" class="arr_inv" value="'.$arr_inv.'">
                          </td>';

                           }else{

                   $show .= '
                          <td class="text-left" style="color:red;">
                            * รายการนี้ได้ทำการยกเลิกการส่งเงิน
                          </td>';

                           }

                  $show .= '
                          <td class="text-center">'.@$r->sender.'</td>
                          <td class="text-center">'.@$r->updated_at.'</td>
                          <td class="text-center">';

                             if(@$r->status_approve==0){
                             if(@$r->status_cancel==0){
                   $show .= '
                            <a href="javascript: void(0);" class="btn btn-sm btn-danger btnCancelSentMoney " data-id="'.@$r->id.'" > ยกเลิก </a>';
                            }

                         }else{echo"-";}

                  $show .= '
                          </td>
                        </tr>';

                         $tt++ ;

                        }
                      }

                     $show .= '
                        <tr>
                          <td class="text-center">  </td>
                          <td class="text-left">  </td>
                          <td class="text-center">  </td>
                          <td class="text-center">  </td>
                          <td class="text-center">
                            <a href="javascript: void(0);" class="btn btn-sm btn-primary font-size-18  btnSentMoney " style="" > '.trans('message.btn_send_money').' </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                  </div>

           ';
// btnSentMoney
         return $show;


    }




   public function getPV_Amount(Request $req)
    {

          $user_login_id = \Auth::user()->id;
          $branch_id_fk = \Auth::user()->branch_id_fk;
          $sPermission = \Auth::user()->permission ;
          if($sPermission==1){
              $action_user_011 = "";
              $action_user_012 = "";
          }else{

                if(\Auth::user()->position_level=='3' || \Auth::user()->position_level=='4'){
                    $action_user_011 = " AND db_orders.branch_id_fk = '".(\Auth::user()->branch_id_fk)."' " ;
                    $action_user_012 = " AND db_add_ai_cash.branch_id_fk = '".(\Auth::user()->branch_id_fk)."' " ;
                }else{
                    $action_user_011 = " AND db_orders.action_user = $user_login_id ";
                    $action_user_012 = " AND db_add_ai_cash.action_user = $user_login_id ";
                }
          }


         if(!empty($req->startDate)){
               $startDate1 = " AND DATE(db_orders.created_at) >= '".$req->startDate."' " ;
               $startDate2 = " AND DATE(db_add_ai_cash.created_at) >= '".$req->startDate."' " ;
               $startDate3 = date("d-m-Y",strtotime($req->startDate)) ;
               $sD3 = $startDate3;
            }else{
               $startDate1 = " AND DATE(db_orders.created_at) >= CURDATE() " ;
               $startDate2 = " AND DATE(db_add_ai_cash.created_at) >= CURDATE() " ;
               $startDate3 = date("d-m-Y") ;
               $sD3 = date("d-m-Y");
            }

            if(!empty($req->endDate)){
               $endDate1 = " AND DATE(db_orders.created_at) <= '".$req->endDate."' " ;
               $endDate2 = " AND DATE(db_add_ai_cash.created_at) <= '".$req->endDate."' " ;
               $endDate3 = date("d-m-Y",strtotime($req->endDate)) ;
               $eD3 = " To ".$endDate3 ;
            }else{
               $endDate1 = "";
               $endDate2 = "";
               $endDate3 = date("Y-m-d") ;
               $eD3 = "";
            }

            $sD3 = $sD3.$eD3;


            if(!empty($req->invoice_code)){
               $invoice_code = " AND db_orders.code_order = '".$req->invoice_code."' " ;
               $invoice_code2 = " AND db_add_ai_cash.code_order = '".$req->invoice_code."' " ;
            }else{
               $invoice_code = "";
               $invoice_code2 = "";
            }

 // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

          if(!empty($req->purchase_type_id_fk)){
             $purchase_type_id_fk = " AND db_orders.purchase_type_id_fk = '".$req->purchase_type_id_fk."' " ;
             if($req->purchase_type_id_fk==4){
               $purchase_type_id_fk_02 = "";
             }else{
               $purchase_type_id_fk_02 = " AND db_add_ai_cash.id=0 ";
             }
          }else{
             $purchase_type_id_fk = "";
             $purchase_type_id_fk_02 = "";
          }

          if(!empty($req->customer_code)){
             $customer_code = " AND db_orders.customers_id_fk = '".$req->customer_code."' " ;
             $customer_code_02 = " AND db_add_ai_cash.customer_id_fk = '".$req->customer_code."' " ;
          }else{
             $customer_code = "";
             $customer_code_02 = "";
          }

          if(!empty($req->customer_name)){
             $customer_name = " AND db_orders.customers_id_fk = '".$req->customer_name."' " ;
             $customer_name_02 = " AND db_add_ai_cash.customer_id_fk = '".$req->customer_name."' " ;
          }else{
             $customer_name = "";
             $customer_name_02 = "";
          }

          if(!empty($req->action_user)){
             $action_user_02 = " AND db_orders.action_user = '".$req->action_user."' " ;
             $action_user_022 = " AND db_add_ai_cash.action_user = '".$req->action_user."' " ;
          }else{
             $action_user_02 = "";
             $action_user_022 = "";
          }


          if(isset($req->status_sent_money)){
             $status_sent_money = " AND db_orders.status_sent_money = ".$req->status_sent_money." " ;
             $status_sent_money_02 = " AND db_add_ai_cash.status_sent_money = ".$req->status_sent_money." " ;
          }else{
             $status_sent_money = "";
             $status_sent_money_02 = "";
          }

          if(isset($req->approve_status)){
             $approve_status = " AND db_orders.approve_status = ".$req->approve_status." " ;
             $approve_status_02 = " AND db_add_ai_cash.approve_status = ".$req->approve_status." " ;
          }else{
             $approve_status = "";
             $approve_status_02 = "";
          }

        if(isset($req->viewcondition)){
          if(isset($req->viewcondition) && $req->viewcondition=="ViewBuyNormal"){
            $viewcondition_01 = ' and db_orders.purchase_type_id_fk not in (4,5) ';
            $viewcondition_02 = ' and db_add_ai_cash.id=0 ';
          }else if(isset($req->viewcondition) && $req->viewcondition=="ViewBuyVoucher"){
            $viewcondition_01 = ' and db_orders.purchase_type_id_fk in (4,5) ';
            $viewcondition_02 = '';
          }else{
            $viewcondition_01 = '';
            $viewcondition_02 = '';
          }
        }else{
          $viewcondition_01 = '';
          $viewcondition_02 = '';
        }

// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

$d1 = DB::select("

SELECT count(db_orders.id) as cnt,
SUM(
(CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) +
(CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
(CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
(CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
(CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) +
(CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
) as sum_price,
sum(pv_total) as pv_total
FROM db_orders
Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
WHERE db_orders.branch_id_fk=$branch_id_fk AND approve_status!='' AND approve_status!=0 AND approve_status!=5
and approve_status in (1)
$action_user_011
$startDate1
$endDate1
                $purchase_type_id_fk
                $customer_code
                $customer_name
                $invoice_code
                $action_user_02
                $status_sent_money
                $approve_status
                $viewcondition_01

");

$cnt_01 = $d1[0]->cnt;
$sum_price_01 = $d1[0]->sum_price;
$pv_total_01 = $d1[0]->pv_total;


$d2 = DB::select("

SELECT count(db_add_ai_cash.id) as cnt,sum(db_add_ai_cash.aicash_amt) as sum_price
FROM db_add_ai_cash
WHERE db_add_ai_cash.approve_status<>4 AND db_add_ai_cash.branch_id_fk=$branch_id_fk AND approve_status!='' AND approve_status!=0 AND approve_status!=5
and approve_status in (1)
$action_user_012
$startDate2
$endDate2
$purchase_type_id_fk_02
              $customer_code_02
              $customer_name_02
              $action_user_022
              $status_sent_money_02
              $approve_status_02
              $viewcondition_02
");

$cnt_01 = $d2[0]->cnt + $cnt_01 ;
$sum_price_01 = $d2[0]->sum_price + $sum_price_01 ;

// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@


$d3 = DB::select("

SELECT count(db_orders.id) as cnt,
SUM(
(CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) +
(CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
(CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
(CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
(CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) +
(CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
) as sum_price,
sum(pv_total) as pv_total
FROM db_orders
Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
WHERE db_orders.branch_id_fk=$branch_id_fk AND approve_status!='' AND approve_status!=0 AND approve_status!=5
and approve_status in (2)
$action_user_011
$startDate1
$endDate1
                $purchase_type_id_fk
                $customer_code
                $customer_name
                $invoice_code
                $action_user_02
                $status_sent_money
                $approve_status
                $viewcondition_01
");

$cnt_02 = $d3[0]->cnt;
$sum_price_02 = $d3[0]->sum_price;
$pv_total_02 = $d3[0]->pv_total;


$d4 = DB::select("

SELECT count(db_add_ai_cash.id) as cnt,sum(db_add_ai_cash.aicash_amt) as sum_price
FROM db_add_ai_cash
WHERE db_add_ai_cash.approve_status<>4 AND db_add_ai_cash.branch_id_fk=$branch_id_fk AND approve_status!='' AND approve_status!=0 AND approve_status!=5
and approve_status in (2)
$action_user_012
$startDate2
$endDate2
$purchase_type_id_fk_02
              $customer_code_02
              $customer_name_02
              $action_user_022
              $status_sent_money_02
              $approve_status_02
              $viewcondition_02
");

$cnt_02 = $d4[0]->cnt + $cnt_02 ;
$sum_price_02 = $d4[0]->sum_price + $sum_price_02 ;


// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@


$d5 = DB::select("

SELECT count(db_orders.id) as cnt,
SUM(
(CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) +
(CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
(CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
(CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
(CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) +
(CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
) as sum_price,
sum(pv_total) as pv_total
FROM db_orders
Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
WHERE db_orders.branch_id_fk=$branch_id_fk AND approve_status!='' AND approve_status!=0
and approve_status in (5)
$action_user_011
$startDate1
$endDate1
                $purchase_type_id_fk
                $customer_code
                $customer_name
                $invoice_code
                $action_user_02
                $status_sent_money
                $approve_status
                $viewcondition_01
");

$cnt_03 = $d5[0]->cnt;
$sum_price_03 = $d5[0]->sum_price;
$pv_total_03 = $d5[0]->pv_total;


$d6 = DB::select("

SELECT count(db_add_ai_cash.id) as cnt,sum(db_add_ai_cash.aicash_amt) as sum_price
FROM db_add_ai_cash
WHERE db_add_ai_cash.approve_status<>4 AND db_add_ai_cash.branch_id_fk=$branch_id_fk AND approve_status!='' AND approve_status!=0
and approve_status in (5)
$action_user_012
$startDate2
$endDate2
$purchase_type_id_fk_02
              $customer_code_02
              $customer_name_02
              $action_user_022
              $status_sent_money_02
              $approve_status_02
              $viewcondition_02
");

$cnt_03 = $d6[0]->cnt + $cnt_03 ;
$sum_price_03 = $d6[0]->sum_price + $sum_price_03 ;

// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@


$d7 = DB::select("

SELECT count(db_orders.id) as cnt,
SUM(
(CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) +
(CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
(CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
(CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
(CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) +
(CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
) as sum_price,
sum(pv_total) as pv_total
FROM db_orders
Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
WHERE db_orders.branch_id_fk=$branch_id_fk
and approve_status not in (1,2,5)
$action_user_011
$startDate1
$endDate1
                $purchase_type_id_fk
                $customer_code
                $customer_name
                $invoice_code
                $action_user_02
                $status_sent_money
                $approve_status
                $viewcondition_01
");

$cnt_04 = $d7[0]->cnt;
$sum_price_04 = $d7[0]->sum_price;
$pv_total_04 = $d7[0]->pv_total;


$d8 = DB::select("

SELECT count(db_add_ai_cash.id) as cnt,sum(db_add_ai_cash.aicash_amt) as sum_price
FROM db_add_ai_cash
WHERE db_add_ai_cash.branch_id_fk=$branch_id_fk
and approve_status not in (1,2,5)
$action_user_012
$startDate2
$endDate2
$purchase_type_id_fk_02
              $customer_code_02
              $customer_name_02
              $action_user_022
              $status_sent_money_02
              $approve_status_02
              $viewcondition_02
");

$cnt_04 = $d8[0]->cnt + $cnt_04 ;
$sum_price_04 = $d8[0]->sum_price + $sum_price_04 ;


// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@



$d9 = DB::select("

SELECT count(db_orders.id) as cnt,
SUM(
(CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) +
(CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
(CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
(CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
(CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) +
(CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
) as sum_price,
sum(pv_total) as pv_total
FROM db_orders
Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
WHERE db_orders.branch_id_fk=$branch_id_fk
$action_user_011
$startDate1
$endDate1
                $purchase_type_id_fk
                $customer_code
                $customer_name
                $invoice_code
                $action_user_02
                $status_sent_money
                $approve_status
                $viewcondition_01
");

$cnt_05 = $d9[0]->cnt;
$sum_price_05 = $d9[0]->sum_price;
$pv_total_05 = $d9[0]->pv_total;


$d10 = DB::select("

SELECT count(db_add_ai_cash.id) as cnt,sum(db_add_ai_cash.aicash_amt) as sum_price
FROM db_add_ai_cash
WHERE db_add_ai_cash.branch_id_fk=$branch_id_fk
$action_user_012
$startDate2
$endDate2
$purchase_type_id_fk_02
              $customer_code_02
              $customer_name_02
              $action_user_022
              $status_sent_money_02
              $approve_status_02
              $viewcondition_02
");

$cnt_05 = $d10[0]->cnt + $cnt_05 ;
$sum_price_05 = $d10[0]->sum_price + $sum_price_05 ;


// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@



        $show = '';

        $show .=
           '
          <div class="table-responsive">
                  <table class="table table-striped mb-0">

                    <thead>
                      <tr style="background-color: #f2f2f2;text-align: right;">
                        <th style="text-align: left !important;" > ('.$sD3.') </th>
                        <th>'.trans('message.list').'</th>
                        <th>PV</th>
                        <th>'.trans('message.amount').'</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr style="color: red" >
                        <th scope="row"><span style="color:black !important;">'.trans('message.status').':</span> '.trans('message.status_pv_1').'</th>
                        <td style="text-align: right;">'.@$cnt_01.' </td>
                        <td style="text-align: right;">'.number_format(@$pv_total_01,0).' </td>
                        <td style="text-align: right;">'.number_format(@$sum_price_01,2).' </td>
                      </tr>
                      <tr>
                        <th scope="row"><span style="color:black !important;">'.trans('message.status').':</span> '.trans('message.status_pv_2').'</th>
                        <td style="text-align: right;">'.@$cnt_02.' </td>
                        <td style="text-align: right;">'.number_format(@$pv_total_02,0).' </td>
                        <td style="text-align: right;">'.number_format(@$sum_price_02,2).' </td>
                      </tr>
                      <tr>
                        <th scope="row"><span style="color:black !important;">'.trans('message.status').':</span> '.trans('message.status_pv_3').'</th>
                        <td style="text-align: right;">'.@$cnt_03.' </td>
                        <td style="text-align: right;">'.number_format(@$pv_total_03,0).' </td>
                        <td style="text-align: right;">'.number_format(@$sum_price_03,2).' </td>
                      </tr>
                      <tr>
                        <th scope="row"><span style="color:black !important;">'.trans('message.status').':</span> '.trans('message.status_pv_4').'</th>
                        <td style="text-align: right;">'.@$cnt_04.' </td>
                        <td style="text-align: right;">'.number_format(@$pv_total_04,0).' </td>
                        <td style="text-align: right;">'.number_format(@$sum_price_04,2).' </td>
                      </tr>

                      <tr>
                        <th scope="row">'.trans('message.total').'</th>
                        <td style="text-align: right;font-weight:bold;">'.@$cnt_05.' </td>
                        <td style="text-align: right;font-weight:bold;">'.number_format(@$pv_total_05,0).' </td>
                        <td style="text-align: right;font-weight:bold;">'.number_format(@$sum_price_05,2).' </td>
                      </tr>

                    </tbody>
                  </table>
                </div>


           ';
         return $show;


    }


    public function Datatable(Request $req){

          $user_login_id = \Auth::user()->id;
          $sPermission = \Auth::user()->permission ;
          // dd($sPermission);
          /*
          1 Super Admin
2 Director
3 Manager
4 Supervisor
5 User

*/
// \Auth::user()->position_level==4 => Supervisor
        if(\Auth::user()->position_level=='3' || \Auth::user()->position_level=='4'){
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
           $startDate = " AND DATE(db_orders.created_at) >= CURDATE() " ;
           $startDate2 = " AND DATE(db_add_ai_cash.created_at) >= CURDATE() " ;
        }

        if(!empty($req->endDate)){
           $endDate = " AND DATE(db_orders.created_at) <= '".$req->endDate."' " ;
           $endDate2 = " AND DATE(db_add_ai_cash.created_at) <= '".$req->endDate."' " ;
        }else{
           $endDate = "";
           $endDate2 = "";
        }

        // $startDate = "";
        // $endDate = "";

        if(!empty($req->purchase_type_id_fk)){
           $purchase_type_id_fk = " AND db_orders.purchase_type_id_fk = '".$req->purchase_type_id_fk."' " ;
           if($req->purchase_type_id_fk==4){
             $purchase_type_id_fk_02 = "";
           }else{
             $purchase_type_id_fk_02 = " AND db_add_ai_cash.id=0 ";
           }
        }else{
           $purchase_type_id_fk = "";
           $purchase_type_id_fk_02 = "";
        }

          if(!empty($req->customer_code)){
             $customer_code = " AND db_orders.customers_id_fk = '".$req->customer_code."' " ;
             $customer_code_02 = " AND db_add_ai_cash.customer_id_fk = '".$req->customer_code."' " ;
          }else{
             $customer_code = "";
             $customer_code_02 = "";
          }

          if(!empty($req->customer_name)){
             $customer_name = " AND db_orders.customers_id_fk = '".$req->customer_name."' " ;
             $customer_name_02 = " AND db_add_ai_cash.customer_id_fk = '".$req->customer_name."' " ;
          }else{
             $customer_name = "";
             $customer_name_02 = "";
          }

        if(!empty($req->invoice_code)){
           $invoice_code = " AND db_orders.code_order = '".$req->invoice_code."' " ;
        }else{
           $invoice_code = "";
        }

          if(!empty($req->action_user)){
             $action_user_02 = " AND db_orders.action_user = '".$req->action_user."' " ;
             $action_user_022 = " AND db_add_ai_cash.action_user = '".$req->action_user."' " ;
          }else{
             $action_user_02 = "";
             $action_user_022 = "";
          }


          if(isset($req->status_sent_money)){
             $status_sent_money = " AND db_orders.status_sent_money = ".$req->status_sent_money." " ;
             $status_sent_money_02 = " AND db_add_ai_cash.status_sent_money = ".$req->status_sent_money." " ;
          }else{
             $status_sent_money = "";
             $status_sent_money_02 = "";
          }

          if(isset($req->approve_status)){
             if($req->approve_status==7){
                $approve_status = " AND db_orders.approve_status = 0 " ;
                if(!empty($req->startDate)){

                }else{
                    $startDate = '';
                    $endDate = '';
                    $startDate2 = '';
                    $endDate2 = '';
                }

             }else{
                $approve_status = " AND db_orders.approve_status = ".$req->approve_status." " ;
             }
             $approve_status_02 = " AND db_add_ai_cash.approve_status = ".$req->approve_status." " ;
          }else{
             $approve_status = "";
             $approve_status_02 = "";
          }

        if(isset($req->viewcondition)){
          if(isset($req->viewcondition) && $req->viewcondition=="ViewBuyNormal"){
            $viewcondition_01 = ' and db_orders.purchase_type_id_fk not in (4,5) ';
            $viewcondition_02 = ' and db_add_ai_cash.id=0 ';
          }else if(isset($req->viewcondition) && $req->viewcondition=="ViewBuyVoucher"){
            $viewcondition_01 = ' and db_orders.purchase_type_id_fk in (4,5) ';
            $viewcondition_02 = '';
          }else{
            $viewcondition_01 = '';
            $viewcondition_02 = '';
          }
        }else{
          $viewcondition_01 = '';
          $viewcondition_02 = '';
        }

   // dd($action_user_011);

    $sTable = DB::select("

                SELECT code_order,db_orders.id,action_date,purchase_type_id_fk,0 as type,customers_id_fk,sum_price,invoice_code,approve_status,shipping_price,db_orders.updated_at,dataset_pay_type.detail as pay_type,cash_price,credit_price,fee_amt,transfer_price,aicash_price,total_price,db_orders.created_at,status_sent_money,cash_pay,action_user
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
                $viewcondition_01

                UNION ALL

                SELECT
                '' as code_order,
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
                0 as aicash_price,total_amt as total_price,db_add_ai_cash.created_at ,status_sent_money,'',action_user
                FROM db_add_ai_cash
                WHERE 1 AND db_add_ai_cash.approve_status<>4
                $action_user_01
                $startDate2
                $endDate2
                $purchase_type_id_fk_02
                $customer_code_02
                $customer_name_02
                $action_user_022
                $status_sent_money_02
                $approve_status_02
                $viewcondition_02

                ORDER BY created_at DESC

              ");
/*
SELECT code_order,db_orders.id,action_date,purchase_type_id_fk,0 as type,customers_id_fk,sum_price,invoice_code,approve_status,shipping_price,db_orders.updated_at,dataset_pay_type.detail as pay_type,cash_price,credit_price,fee_amt,transfer_price,aicash_price,total_price,db_orders.created_at,status_sent_money,cash_pay,action_user  FROM db_orders  Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id  WHERE 1
AND db_orders.branch_id_fk = '1'
AND DATE(db_orders.created_at) >= CURDATE()
UNION ALL
SELECT  '' as code_order,  db_add_ai_cash.id,  db_add_ai_cash.created_at as d2,  0 as purchase_type_id_fk,  '\u0e40\u0e15\u0e34\u0e21 Ai-Cash' AS type,  db_add_ai_cash.customer_id_fk as c2,  db_add_ai_cash.aicash_amt,  db_add_ai_cash.id as inv_no,approve_status  ,'',  db_add_ai_cash.updated_at as ud2,  'ai_cash' as pay_type,cash_price,  credit_price,fee_amt,transfer_price,  0 as aicash_price,total_amt as total_price,db_add_ai_cash.created_at ,status_sent_money,'',action_user  FROM db_add_ai_cash  WHERE 1
AND db_add_ai_cash.approve_status<>4
AND DATE(db_add_ai_cash.created_at) >= CURDATE()

ORDER BY created_at DESC
*/

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('created_at', function($row) {
        $d = strtotime(@$row->created_at);
        return date("Y-m-d",$d)."<br/>".date("H:i:s",$d);
      })
      ->escapeColumns('created_at')
      ->addColumn('customer_name', function($row) {
        if($row->customers_id_fk){
           $Customer = DB::select(" select * from customers where id=".$row->customers_id_fk." ");
           return "[".@$Customer[0]->user_name.'] <br>'.@$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
        }
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
             $tootip_price = ' เงินสด: '.$row->cash_price;
          }elseif(@$row->cash_pay!=0){
             $tootip_price = ' เงินสด: '.$row->cash_pay;
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
          $total_price = $row->shipping_price>0?$row->shipping_price:0;
          // if(@$row->cash_price!=0){
          //    $total_price += $row->cash_price;
          // }
          // if(@$row->credit_price!=0){
          //    $total_price += $row->credit_price+$row->fee_amt;
          // }
          // if(@$row->transfer_price!=0){
          //    $total_price += $row->transfer_price;
          // }
          // if(@$row->aicash_price!=0){
          //    $total_price += $row->aicash_price;
          // }
          $total_price += $row->sum_price;
          return @number_format(@$total_price,2);
          // return sprintf("%0.3f", ciel(@$total_price));
          // return number_format(@$total_price,2,",",".");
          // return number_format((float)$total_price, 2, '.', '');
          // return substr($total_price,0,strpos($total_price,'.')+3); ;

      })
      ->addColumn('status_delivery', function($row) {
          $r = DB::select(" select status_delivery FROM db_orders WHERE id = ".$row->id." ");
          if($r)
          return $r[0]->status_delivery;

      })
      ->addColumn('status_sent_product', function($row) {
        if(!empty($row->code_order)){
          $r1 = DB::select(" SELECT * FROM `db_pick_pack_requisition_code` WHERE receipts = '".$row->code_order."' ");
          if(@$r1){
             $r2 = DB::select(" SELECT status_sent FROM `db_pay_requisition_001` WHERE pick_pack_requisition_code_id_fk = '".$r1[0]->pick_pack_packing_code_id_fk."' ");
             return @$r2[0]->status_sent;
          }
        }
      })
      ->addColumn('status_sent_desc', function($row) {
        if(!empty($row->code_order)){
          $r1 = DB::select(" SELECT * FROM `db_pick_pack_requisition_code` WHERE receipts = '".$row->code_order."' ");
          if(@$r1){
            $r2 = DB::select(" SELECT status_sent FROM `db_pay_requisition_001` WHERE pick_pack_requisition_code_id_fk = '".$r1[0]->pick_pack_packing_code_id_fk."' ");
            $r3 = \App\Models\Backend\Pay_requisition_status::find($r2[0]->status_sent);
            return @$r3->txt_desc;
          }
        }
      })
      ->addColumn('action_user', function($row) {
        if(@$row->action_user!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->action_user." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })
      ->make(true);
    }

    public function DatatableCourseEvent(Request $req){
      // print_r($req->user_name);
      // $sTable = \App\Models\Backend\Course_event::search()->orderBy('id', 'asc');
      $sTable = DB::select(" SELECT course_event.*,('".$req->user_name."') as user_name FROM `course_event` ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('ce_type_desc', function($row) {
        $ce_type = \App\Models\Backend\Ce_type::find($row->ce_type);
        return @$ce_type->txt_desc;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->addColumn('CourseCheckRegis', function($row) {
        $CourseCheckRegis = \App\Models\Frontend\CourseCheckRegis::cart_check_register($row->id, 1 ,$row->user_name);
        return $CourseCheckRegis['status'];
      })
      ->addColumn('cuase_cannot_buy', function($row) {
           $CourseCheckRegis = \App\Models\Frontend\CourseCheckRegis::check_register_all($row->id,$row->user_name);
           if($CourseCheckRegis){
              $arr = [];
              for ($i=0; $i < count(@$CourseCheckRegis) ; $i++) {
                   $c = array_column($CourseCheckRegis,$i);
                   foreach ($c as $key => $value) {
                    if($value['status'] == "fail"){
                       array_push($arr,$value['message']);
                    }
                   }
                   $im = implode(',',$arr);
              }
              return $im;
          }
      })
      ->escapeColumns('cuase_cannot_buy')
      ->make(true);
    }


}
