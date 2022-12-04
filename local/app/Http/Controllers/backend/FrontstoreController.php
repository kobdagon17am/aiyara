<?php

namespace App\Http\Controllers\backend;

use DB;
use PDO;
use Auth;
use File;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\Frontend\LineModel;
use App\Models\Frontend\PvPayment;
use App\Models\DbOrderProductsList;
use App\Http\Controllers\Controller;
use App\Models\Backend\DbOrderHistoryLog;
use App\Models\Frontend\CourseCheckRegis;
use App\Models\Frontend\RunNumberPayment;
use App\Models\Backend\DatasetOrderHistoryStatus;
use App\Http\Controllers\Frontend\Fc\GiveawayController;
use App\Http\Controllers\Frontend\Fc\CancelOrderController;
use App\Helpers\General;
use Session;

class FrontstoreController extends Controller
{

  public function upPro(Request $request)
  {
    // $pro = DB::table('cambodia_provinces')->get();
    // foreach($pro as $p){
    //   DB::table('dataset_provinces')->insert([
    //     'business_location_id' => 3,
    //     'code' => $p->id,
    //     'name_th' => $p->name,
    //     'name_en' => $p->name,
    //     'ref_id' => $p->id,
    //     'status' => 1,
    //   ]);
    // }

    // $amp = DB::table('cambodia_districts')->get();
    // foreach($amp as $a){
    //   $pro = DB::table('dataset_provinces')->where('ref_id',$a->province_id)->first();
    //   DB::table('dataset_amphures')->insert([
    //     'code' => $a->id,
    //     'name_th' => $a->name,
    //     'name_en' => $a->name,
    //     'province_id' => $pro->id,
    //     'ref_id' => $a->id,
    //     'status' => 1,
    //   ]);
    // }

    // $tam = DB::table('cambodia_communes')->get();
    // foreach($tam as $t){
    //   $am = DB::table('dataset_amphures')->where('ref_id',$t->amphure_id)->first();
    //   DB::table('dataset_districts')->insert([
    //     'id' => '333'.$t->id,
    //     'name_th' => $t->name,
    //     'name_en' => $t->name,
    //     'amphure_id' => $am->id,
    //     'ref_id' => $t->id,
    //     'status' => 1,
    //   ]);
    // }

    return 'ok';

  }

  public function index(Request $request)
  {

    General::gen_id_url();

      // วุฒิสร้าง session
      // r_invoice_code
        $menus = DB::table('ck_backend_menu')->select('id')->where('id',6)->first();
        Session::put('session_menu_id', $menus->id);
        Session::put('menu_id', $menus->id);
        $role_group_id = \Auth::user()->role_group_id_fk;
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',@$role_group_id)->where('menu_id_fk',@$menus->id)->first();
        $sC = @$menu_permit->c;
        $sU = @$menu_permit->u;
        $sD = @$menu_permit->d;
        Session::put('sC', $sC);
        Session::put('sU', $sU);
        Session::put('sD', $sD);
        $can_cancel_bill = @$menu_permit->can_cancel_bill;
        $can_cancel_bill_across_day = @$menu_permit->can_cancel_bill_across_day;
        $can_approve = @$menu_permit->can_approve;
        Session::put('can_cancel_bill', $can_cancel_bill);
        Session::put('can_cancel_bill_across_day', $can_cancel_bill_across_day);
        Session::put('can_approve', $can_approve);


    // dump($request->all());
    // dd(\Auth::user()->position_level);
    // dd(\Auth::user()->branch_id_fk);
    $branch_id_fk = \Auth::user()->branch_id_fk;
    $user_login_id = \Auth::user()->id;
    $sUser = DB::select(" select * from ck_users_admin ");
    // $sApproveStatus = DB::select(" select * from dataset_approve_status where status=1 and id not in (1,2) "); // 1,2 เหมือนว่าไม่ได้ใช้แล้ว
    // $sApproveStatus = DB::select(" select * from dataset_approve_status where status=1 and id not in (3) "); // 1,2 เหมือนว่าไม่ได้ใช้แล้ว
    $sApproveStatus = DB::select(" select * from dataset_approve_status where status=1 and id not in (3) "); // 1,2 เหมือนว่าไม่ได้ใช้แล้ว

    // $resule = CancelOrderController::cancel_order('1618', @\Auth::user()->id,1,'admin');//goftest
    // dd($resule);

    $sPermission = \Auth::user()->permission;
    if ($sPermission == 1) {
      $w1 = "";
    } else {
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

      if ($value->approve_status == 1) {
        $approve_status_1 += 1;
        $sum_price_1 += $value->sum_price;
        $pv_1 += @$value->pv;
      }


      if ($value->approve_status == 5) {
        $approve_status_5 += 1;
        $sum_price_5 += $value->sum_price;
        $pv_5 += @$value->pv;
      }

      if ($value->approve_status == 9) {
        $approve_status_9 += 1;
        $sum_price_9 += $value->sum_price;
        $pv_9 += @$value->pv;
      }

      if ($value->approve_status != 1 && $value->approve_status != 5 && $value->approve_status != 9) {
        $approve_status_88 += 1;
        $sum_price_88 += @$value->sum_price;
        $pv_88 += @$value->pv;
      }

      $approve_status_total += 1;
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


    // $r_invoice_code = DB::select(" SELECT code_order FROM db_orders where code_order <>'' ");
    // dd($r_invoice_code);

      $sBusiness_location = \App\Models\Backend\Business_location::get();

    return View('backend.frontstore.index')->with(
      array(
        'sBusiness_location' => $sBusiness_location,
        'sUser' => $sUser,
        'sApproveStatus' => $sApproveStatus,
        'sPurchase_type' => $sPurchase_type,
        'sDBFrontstoreApproveStatus' => $sDBFrontstoreApproveStatus,

        'approve_status_2' => ($approve_status_2),
        'sum_price_2' => $sum_price_2,
        'pv_2' => $pv_2,

        'approve_status_4' => ($approve_status_4),
        'sum_price_4' => $sum_price_4,
        'pv_4' => $pv_4,

        'approve_status_5' => ($approve_status_5),
        'sum_price_5' => $sum_price_5,
        'pv_5' => $pv_5,

        'approve_status_9' => ($approve_status_9),
        'sum_price_9' => $sum_price_9,
        'pv_9' => $pv_9,

        'approve_status_88' => ($approve_status_88),
        'sum_price_88' => $sum_price_88,
        'pv_88' => $pv_88,

        'approve_status_total' => ($approve_status_total),
        'sum_price_total' => $sum_price_total,
        'pv_total' => $pv_total,

        'sDBFrontstoreSumCostActionUser' => $sDBFrontstoreSumCostActionUser,
        'sDBFrontstoreUserAddAiCash' => $sDBFrontstoreUserAddAiCash,

        'sDBSentMoneyDaily' => $sDBSentMoneyDaily,
        // 'r_invoice_code' => $r_invoice_code,

      )
    );
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

    // $sPay_type = DB::select(" select * from dataset_pay_type where id > 4 and status=1 ");
    $sPay_type = DB::select(" select * from dataset_pay_type where status=1 ");

    $sDistribution_channel = DB::select(" select * from dataset_distribution_channel where id<>3 AND status=1  ");
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

    $sPermission = \Auth::user()->permission; // Super Admin == 1
    $position_level = \Auth::user()->position_level;
    // dd($sPermission);
    // dd($position_level);
    $ChangePurchaseType = 0; // ปิด / ไม่แสดง
    if ($sPermission == 1) {
      $ChangePurchaseType = 1; // เปิด / แสดง
    } else {
      // dataset_position_level
      // 4 Supervisor/Manager
      // 2 CS แผนกขาย
      if ($position_level == 4) {
        if ($DATE_CREATED >= $DATE_YESTERDAY && $DATE_CREATED <= $DATE_TODAY) $ChangePurchaseType = 1;
      } else {
        if ($DATE_CREATED == $DATE_TODAY) $ChangePurchaseType = 1;
      }
    }

    return View('backend.frontstore.form')->with(
      array(
        'sPurchase_type' => $sPurchase_type,
        'sProductUnit' => $sProductUnit,
        'sDistribution_channel' => $sDistribution_channel,
        'Products' => $Products,
        'sBusiness_location' => $sBusiness_location,
        'sFee' => $sFee,
        'sBranchs' => $sBranchs,
        'User_branch_id' => $User_branch_id,
        // 'aistockist'=>$aistockist,
        // 'agency'=>$agency,
        'sPay_type' => $sPay_type,
        'ChangePurchaseType' => $ChangePurchaseType,

      )
    );
  }
  public function store(Request $request)
  {
// dd('ok');
    $customers = DB::table('customers')->select('user_name','business_location_id')->where('id',@$request->customers_id_fk)->first();
    if($customers){

      if($request->purchase_type_id_fk==4){
        $branchs = DB::table('branchs')->select('business_location_id_fk')->where('id',$request->branch_id_fk)->first();
        if(!$branchs){
          return redirect()->back()->with('error','ไม่พบข้อมูลสาขา');
        }
        // if($customers->business_location_id==3 && $branchs->business_location_id_fk!=3 || $customers->business_location_id==1 && $branchs->business_location_id_fk!=1 || $customers->business_location_id=='' && $branchs->business_location_id_fk!=1){
        //   return redirect()->back()->with('error','ลูกค้าต่างพื้นที่ไม่สามารถทำรายการเติม Ai Stock ได้');
        // }
      }

     $result = \App\Helpers\Frontend::check_kyc($customers->user_name);

    if($result['status']=='fail'){
      return redirect()->back()->with('error',$customers->user_name.' ไม่สามารถทำรายการใดๆได้ หากยังไม่ผ่านการยืนยันตัวตน');
    }
  }

    $sRow = \App\Models\Backend\Frontstore::find($request->id);

    if (!empty($request->upload_file)) {

      $sRow = \App\Models\Backend\Frontstore::find($request->id);

      if ($request->hasFile('image01')) {
        $this->validate($request, [
          'image01' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1000000',
        ]);
        $image = $request->file('image01');
        $name = 'S' . time() . '.' . $image->getClientOriginalExtension();
        $image_path = 'local/public/files_slip/' . date('Ym');
        $image->move($image_path, $name);
        $sRow->file_slip = $image_path . $name;
        DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`,transfer_bill_date,note, `create_at`, `update_at`)
               VALUES
               ('" . $sRow->customers_id_fk . "', '', '" . $sRow->code_order . "', '$image_path', '$name','" . $request->transfer_money_datetime . "','" . $request->note . "', now(), now()) ");
        $lastInsertId_01 = DB::getPdo()->lastInsertId();

        $sRow->save();
      }

      return redirect()->to(url("backend/frontstore/" . $request->id . "/edit"));
    } else {

      return $this->form();

    }
  }

  public function edit($id)
  {
    // sBranchs customer_pv

    // กำลังเบิกสินค้า ไม่ให้แก้บิล
    $ch_Disabled = 0;
    $r_ch_Disabled = DB::select(" SELECT orders_id_fk FROM `db_pick_pack_packing_code` where status<>6 and status_picked=1 ; ");
    if (!empty($r_ch_Disabled))
      foreach ($r_ch_Disabled as $key => $value) {

        $orders_id_fk = explode(',', @$value->orders_id_fk);
        if (in_array($id, @$orders_id_fk)) {
          $ch_Disabled = 1;
        }
      }

    $sRow = \App\Models\Backend\Frontstore::find($id);
    // || $sRow->approve_status == 2
    if ($sRow->approve_status == 9 ) {
      $ch_Disabled = 1;
    }
    $sCustomer = DB::select(" select user_name,prefix_name,first_name,last_name from customers where id=" . $sRow->customers_id_fk . " ");
    // $sCustomer = DB::select(" select * from customers where id=" . $sRow->customers_id_fk . " ");
    @$CusName = (@$sCustomer[0]->user_name . " : " . @$sCustomer[0]->prefix_name . $sCustomer[0]->first_name . " " . @$sCustomer[0]->last_name);
    @$user_name = @$sCustomer[0]->user_name;

    if (@$user_name) {
      $update_package = \App\Http\Controllers\Frontend\Fc\RunPvController::update_package($sCustomer[0]->user_name);
    }

    if (@$sRow->aistockist) {
      $sCusAistockist = DB::select(" select user_name,prefix_name,first_name,last_name from customers where id=" . $sRow->aistockist . " ");
      @$CusAistockistName = @$sCusAistockist[0]->user_name . " : " . @$sCusAistockist[0]->prefix_name . $sCusAistockist[0]->first_name . " " . @$sCusAistockist[0]->last_name;
    } else {
      @$CusAistockistName = '';
    }

    if (@$sRow->agency) {
      $sCusAgency = DB::select(" select user_name,prefix_name,first_name,last_name from customers where id=" . $sRow->agency . " ");
      @$CusAgencyName = @$sCusAgency[0]->user_name . " : " . @$sCusAgency[0]->prefix_name . $sCusAgency[0]->first_name . " " . @$sCusAgency[0]->last_name;
    } else {
      @$CusAgencyName = '';
    }

    if (!empty($sRow->member_id_aicash)) {
      $sAicash = DB::select(" select user_name,prefix_name,first_name,last_name from customers where id=" . $sRow->member_id_aicash . " ");
      $Cus_Aicash = @$sAicash[0]->ai_cash;
      $Customer_id_Aicash = @$sRow->member_id_aicash;
      $Customer_name_Aicash = (@$sAicash[0]->user_name . " : " . @$sAicash[0]->prefix_name . $sAicash[0]->first_name . " " . @$sAicash[0]->last_name);
    } else {
      $sAicash  = NULL;
      $Cus_Aicash = "0.00";
      $Customer_id_Aicash = "";
      $Customer_name_Aicash = "";
    }

    $sBranchs = DB::select(" select * from branchs where id=" . $sRow->branch_id_fk . " ");
    $BranchName = $sBranchs[0]->b_name;

    $Purchase_type = DB::select(" select * from dataset_orders_type where id=" . $sRow->purchase_type_id_fk . " ");
    $PurchaseName = @$Purchase_type[0]->orders_type;

    $CusAddrFrontstore = \App\Models\Backend\CusAddrFrontstore::where('frontstore_id_fk', $id)->get();
    $sUser = \App\Models\Backend\Permission\Admin::get();

    $Delivery_location = DB::select(" select id,txt_desc from dataset_delivery_location  ");

    $shipping_special = DB::select(" select * from dataset_shipping_cost where business_location_id_fk=" . $sRow->business_location_id_fk . " AND shipping_type_id=4 ");

    // วุฒิเพิ่มมาเช็คว่า business location มีราคาสินค้านั้นๆไหม
    $products_cost = DB::table('products_cost')->where('business_location_id',$sBranchs[0]->business_location_id_fk)->pluck('product_id_fk')->toArray();
    $arr_cost = "";
    foreach($products_cost as $key => $cost){
      if($key+1 != count($products_cost)){
        $arr_cost .= $cost.',';
      }else{
        $arr_cost .= $cost;
      }

    }
    // dataset_pay_type
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
          " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 1), ',', -1)  OR
          " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 2), ',', -1) OR
          " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 3), ',', -1) OR
          " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 4), ',', -1) OR
          " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 5), ',', -1)
        )

    AND products.id IN (".$arr_cost.")

    order by products.product_code

  ");

    // $Products = DB::select("

    //     SELECT products.id as product_id,
    //     products.product_code,
    //     (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
    //     FROM
    //     products_details
    //     Left Join products ON products_details.product_id_fk = products.id
    //     WHERE lang_id=1
    //     AND
    //         (
    //           " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 1), ',', -1)  OR
    //           " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 2), ',', -1) OR
    //           " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 3), ',', -1) OR
    //           " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 4), ',', -1) OR
    //           " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 5), ',', -1)
    //         )

    //     order by products.product_code

    //   ");

    // dd($Products);

    /* dataset_orders_type
        1 ทำคุณสมบัติ
        2 รักษาคุณสมบัติรายเดือน
        3 รักษาคุณสมบัติท่องเที่ยว
        4 เติม Ai-Stockist
        5 แลก Gift Voucher
        */

    // วุฒิถาม ไม่แน่ใจล็อคไว้ทำไม
    // if(!empty($sRow->purchase_type_id_fk) && $sRow->purchase_type_id_fk!=5) {
    //   $sPurchase_type = DB::select(" select * from dataset_orders_type where id<>5 and status=1 and lang_id=1 order by id limit 6");
    // }else{
    //   $sPurchase_type = DB::select(" select * from dataset_orders_type where status=1 and lang_id=1 order by id limit 6");
    // }
    $sPurchase_type = DB::select(" select * from dataset_orders_type where status=1 and lang_id=1 order by id limit 6");

    if ($sRow->purchase_type_id_fk == '5') {
      $sPay_type = DB::select(" select * from dataset_pay_type where id in (4,12,13,14,19) and status=1 ");
    } else {
      // $sPay_type = DB::select(" select * from dataset_pay_type where id > 4 and id <=11 and status=1 ");
      $sPay_type = DB::select(" select * from dataset_pay_type where id <=11 and id != 4 and status=1 ");
    }
    // dd($sRow->pay_type_id_fk);
    $sDistribution_channel = DB::select(" select * from dataset_distribution_channel where id<>3 AND status=1  ");
    $sDistribution_channel3 = DB::select(" select * from dataset_distribution_channel where id=3 AND status=1  ");
    $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();

    // วุฒิดักว่าอยู่ประเทศอะไร
    // if($sBranchs[0]->business_location_id_fk == 3){
    //   $sProvince = DB::select(" select *,name as province_name from cambodia_provinces order by name ");
    //   $sAmphures = DB::select(" select *,name as amphur_name from cambodia_districts order by name ");
    //   $sTambons = DB::select(" select *,name as tambon_name from cambodia_communes order by name ");
    // }else{
      $sProvince = DB::select(" select *,name_th as province_name from dataset_provinces where business_location_id = ".$sBranchs[0]->business_location_id_fk." order by name_th ");
      $sAmphures = DB::select(" select *,name_th as amphur_name from dataset_amphures order by name_th ");
      $sTambons = DB::select(" select *,name_th as tambon_name from dataset_districts order by name_th ");
    // }

    $sBusiness_location = \App\Models\Backend\Business_location::get();

    $sFee = \App\Models\Backend\Fee::get();

    $User_branch_id = \Auth::user()->branch_id_fk;
    // $sBranchs = DB::select(" select * from branchs where province_id_fk <> 0  ");
    $sBranchs = DB::select(" select * from branchs where business_location_id_fk = ".$sBranchs[0]->business_location_id_fk."  ");
    // dd($sBranchs);

    $ThisCustomer = DB::select(" select user_name from customers where id=" . $sRow->customers_id_fk . " ");
    // dd($ThisCustomer[0]->user_name);
    $aistockist = DB::select(" select * from customers_aistockist_agency where aistockist=1 AND user_name <> '" . $ThisCustomer[0]->user_name . "' ");
    $agency = DB::select(" select * from customers_aistockist_agency where agency=1 AND user_name <> '" . $ThisCustomer[0]->user_name . "' ");

    // $giftvoucher_this = DB::select(" select sum(banlance) as gift_total from gift_voucher where customer_id=".$sRow->customers_id_fk." AND banlance>0 AND expiry_date>=now() "); //AND expiry_date>=now()

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
            Left Join customers ON db_giftvoucher_cus.customer_username = customers.user_name
            WHERE
            customers.id = " . $sRow->customers_id_fk . "
            AND
            curdate() BETWEEN db_giftvoucher_code.pro_sdate and db_giftvoucher_code.pro_edate
            AND
            db_giftvoucher_code.status = 1

             "); //AND expiry_date>=now()

    $giftvoucher_this = @$giftvoucher_this[0]->giftvoucher_value;

    $rs = DB::select(" SELECT count(*) as cnt FROM db_order_products_list WHERE frontstore_id_fk=$id ");

    $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total from db_order_products_list WHERE frontstore_id_fk=$id GROUP BY frontstore_id_fk ");
    $vat_b = DB::table('dataset_vat')
    ->where('business_location_id_fk', '=', $sBranchs[0]->business_location_id_fk)
    ->first();
    $vat_b = $vat_b->vat;
    if ($sFrontstoreDataTotal) {
      // $vat = floatval(@$sFrontstoreDataTotal[0]->total) - (floatval(@$sFrontstoreDataTotal[0]->total) / 1.07);
      $vat_data = DB::table('dataset_vat')
      ->where('business_location_id_fk', '=', $sBranchs[0]->business_location_id_fk)
      ->first();
      $vat_data = $vat_data->vat;
      //vatใน 7%
      $vat = floatval(@$sFrontstoreDataTotal[0]->total) * ($vat_data / (100 + $vat_data));
      // dd(floatval(@$sFrontstoreDataTotal[0]->total));
      //มูลค่าสินค้า
      // $price_vat = floatval(@$sFrontstoreDataTotal[0]->total) - $p_vat;
      // $price_total = $price + $shipping;

      // dd( $vat);
      $vat = $vat > 0 ? $vat : 0;
      $product_value = str_replace(",", "", floatval(@$sFrontstoreDataTotal[0]->total) - $vat);
      $product_value = $product_value > 0 ? $product_value : 0;
      $total = @$sFrontstoreDataTotal[0]->total > 0 ? @$sFrontstoreDataTotal[0]->total : 0;
      DB::select(" UPDATE db_orders SET product_value=" . ($product_value) . ",tax=" . ($vat). ",vat=" . ($vat_b).",sum_price=" . ($total) . " WHERE id=$id ");
    } else {
      DB::select(" UPDATE db_orders SET product_value=0,tax=0,sum_price=0 WHERE id=$id  ");
    }

    $sAccount_bank = \App\Models\Backend\Account_bank::where('business_location_id_fk', $sBranchs[0]->business_location_id_fk)->get();

    // $type = $sRow->purchase_type_id_fk;
    $pv_total = $sRow->pv_total;
    // $customer_pv = \Auth::user()->pv ? \Auth::user()->pv : 0 ;

    // แถม
    $check_giveaway = GiveawayController::check_giveaway($sRow->purchase_type_id_fk, $ThisCustomer[0]->user_name, $pv_total);

    $sPay_type_purchase_type6 = DB::select(" select * from dataset_pay_type where id > 4 and id <=11 and status=1 ORDER BY id=5 DESC ");

    $DATE_CREATED = date("Y-m-d", strtotime($sRow->created_at));
    $DATE_YESTERDAY = Carbon::yesterday()->format('Y-m-d');
    $DATE_TODAY = Carbon::today()->format('Y-m-d');

    $sPermission = \Auth::user()->permission; // Super Admin == 1
    $position_level = \Auth::user()->position_level;

    $ChangePurchaseType = 0; // ปิด / ไม่แสดง
    if ($sPermission == 1) {
      $ChangePurchaseType = 1; // เปิด / แสดง
    } else {
      // dataset_position_level
      // 4 Supervisor/Manager
      // 2 CS แผนกขาย
      if ($position_level == 4) {
        if ($DATE_CREATED >= $DATE_YESTERDAY && $DATE_CREATED <= $DATE_TODAY) $ChangePurchaseType = 1;
      } else {
        if ($DATE_CREATED == $DATE_TODAY) $ChangePurchaseType = 1;
      }
    }

    $PaymentSlip = DB::select(" select * from payment_slip where code_order='" . $sRow->code_order . "' ");

    $cnt_slip = count($PaymentSlip);

    $data_gv = \App\Helpers\Frontend::get_gitfvoucher(@$ThisCustomer[0]->user_name);
    // $data_gv = \App\Helpers\Frontend::get_gitfvoucher("A101987");
    // $gv = \App\Helpers\Frontend::get_gitfvoucher("A436875");
    // $gv = \App\Helpers\Frontend::get_gitfvoucher("A548815"); CusAddrFrontstore
    $gv = @$data_gv->sum_gv;
    // $gitfvoucher = @$gv!=null?$gv:0; sProvince
    // $gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->user_name);
    $customer_pv = DB::table('customers')
      ->select(
        'customers.business_name',
        'customers.prefix_name',
        'customers.first_name',
        'customers.last_name',
        'customers.user_name',
        'customers.created_at',
        'customers.date_mt_first',
        'customers.pv_mt_active',
        'customers.pv_mt',
        'customers.pv_aistockist',
        'customers.bl_a',
        'customers.bl_b',
        'customers.bl_c',
        'customers.pv_a',
        'customers.pv_b',
        'customers.pv_c',
        'customers.aistockist_status',
        'customers.pv_tv',
        'customers.pv',
        'customers.business_location_id',
        'dataset_package.dt_package',
        'dataset_qualification.code_name',
        'q_max.code_name as max_code_name',
        'q_max.business_qualifications as max_q_name',
        'dataset_qualification.business_qualifications as q_name',
        'customers.team_active_a',
        'customers.team_active_b',
        'customers.team_active_c'
      )
      ->leftjoin('dataset_package', 'dataset_package.id', '=', 'customers.package_id')
      ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
      ->leftjoin('dataset_qualification as q_max', 'q_max.id', '=', 'customers.qualification_max_id')
      ->where('customers.user_name', '=', @$ThisCustomer[0]->user_name)
      ->first();

    return View('backend.frontstore.form')->with(
      array(
        'customer_pv' => $customer_pv,
        'user_name_buy' =>$sCustomer[0]->user_name,
        'sRow' => $sRow,
        'sPurchase_type' => $sPurchase_type,
        'sProductUnit' => $sProductUnit,
        'sDistribution_channel' => $sDistribution_channel,
        'sDistribution_channel3' => $sDistribution_channel3,
        'Products' => $Products,
        'sProvince' => $sProvince,
        'sAmphures' => $sAmphures,
        'sTambons' => $sTambons,
        'Delivery_location' => $Delivery_location,
        'CusAddrFrontstore' => $CusAddrFrontstore,
        'sBusiness_location' => $sBusiness_location,
        'sFee' => $sFee,
        'sBranchs' => $sBranchs, 'User_branch_id' => $User_branch_id,
        'aistockist' => $aistockist,
        'agency' => $agency,
        'CusName' => $CusName,
        'user_name' => $user_name,
        'sAicash' => $sAicash,
        'Cus_Aicash' => $Cus_Aicash,
        'Customer_id_Aicash' => $Customer_id_Aicash,
        'Customer_name_Aicash' => $Customer_name_Aicash,
        'BranchName' => $BranchName,
        'PurchaseName' => $PurchaseName,
        'giftvoucher_this' => $giftvoucher_this,
        'sAccount_bank' => $sAccount_bank,
        'sPay_type' => $sPay_type,
        'shipping_special' => $shipping_special,
        'sFrontstoreDataTotal' => $sFrontstoreDataTotal,
        'check_giveaway' => $check_giveaway,
        'sPay_type_purchase_type6' => $sPay_type_purchase_type6,
        'ChangePurchaseType' => $ChangePurchaseType,
        'CusAistockistName' => @$CusAistockistName,
        'CusAgencyName' => @$CusAgencyName,
        // 'giveaway_desc'=>@$giveaway_desc,
        'check_giveaway' => @$check_giveaway,
        'PaymentSlip' => @$PaymentSlip,
        'cnt_slip' => @$cnt_slip,
        'ch_Disabled' => @$ch_Disabled,
        'gitfvoucher' => @$gv,
        'vat_b' => @$vat_b,
      )
    );
  }


  public function viewdata($id)
  {
    // dd($id);

    $sRow = \App\Models\Backend\Frontstore::find($id);

    if (!$sRow) {
      return redirect()->to(url("backend/frontstore"));
    }
    // dd($sRow->customers_id_fk);
    $sCustomer = DB::select(" select * from customers where id=" . $sRow->customers_id_fk . " ");
    @$CusName = (@$sCustomer[0]->user_name . " : " . @$sCustomer[0]->prefix_name . $sCustomer[0]->first_name . " " . @$sCustomer[0]->last_name);


    // $sAicash = DB::select(" select * from customers where id=".$sRow->member_id_aicash." ");
    // $Cus_Aicash = @$sAicash[0]->ai_cash;
    // $Cus_name_Aicash = (@$sAicash[0]->user_name." : ".@$sAicash[0]->prefix_name.$sAicash[0]->first_name." ".@$sAicash[0]->last_name);


    if (!empty($sRow->member_id_aicash)) {
      $sAicash = DB::select(" select * from customers where id=" . $sRow->member_id_aicash . " ");
      // dd($sAicash);
      $Cus_Aicash = @$sAicash[0]->ai_cash;
      $Cus_Aicash = @$sRow->member_id_aicash;
      $Cus_name_Aicash = (@$sAicash[0]->user_name . " : " . @$sAicash[0]->prefix_name . $sAicash[0]->first_name . " " . @$sAicash[0]->last_name);
      // dd($Customer_name_Aicash);
    } else {
      $sAicash  = NULL;
      $Cus_Aicash = "0.00";
      $Cus_Aicash = "";
      $Cus_name_Aicash = "";
    }

    $sBranchs = DB::select(" select * from branchs where id=" . $sRow->branch_id_fk . " ");
    $BranchName = $sBranchs[0]->b_name;

    $Purchase_type = DB::select(" select * from dataset_orders_type where id=" . $sRow->purchase_type_id_fk . " ");
    $PurchaseName = $Purchase_type[0]->orders_type;

    $CusAddrFrontstore = \App\Models\Backend\CusAddrFrontstore::where('frontstore_id_fk', $id)->get();
    $sUser = \App\Models\Backend\Permission\Admin::get();

    $Delivery_location = DB::select(" select id,txt_desc from dataset_delivery_location  ");

    $shipping_special = DB::select(" select * from dataset_shipping_cost where business_location_id_fk=" . $sRow->purchase_type_id_fk . " AND shipping_type_id=4 ");

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
              " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 1), ',', -1)  OR
              " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 2), ',', -1) OR
              " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 3), ',', -1) OR
              " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 4), ',', -1) OR
              " . $sRow->purchase_type_id_fk . " = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 5), ',', -1)
            )

      ");

    /* dataset_orders_type
        1 ทำคุณสมบัติ
        2 รักษาคุณสมบัติรายเดือน
        3 รักษาคุณสมบัติท่องเที่ยว
        4 เติม Ai-Stockist
        5 แลก Gift Voucher
        */

    if (!empty($sRow->purchase_type_id_fk) && $sRow->purchase_type_id_fk != 5) {
      $sPurchase_type = DB::select(" select * from dataset_orders_type where id<>5 and status=1 and lang_id=1 order by id limit 4");
    } else {
      $sPurchase_type = DB::select(" select * from dataset_orders_type where status=1 and lang_id=1 order by id limit 5");
    }
    $sPay_type = DB::select(" select * from dataset_pay_type where id > 4 and status=1 ");

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

    $ThisCustomer = DB::select(" select * from customers where id=" . $sRow->customers_id_fk . " ");
    // dd($ThisCustomer[0]->user_name);
    $aistockist = DB::select(" select * from customers_aistockist_agency where aistockist=1 AND user_name <> '" . $ThisCustomer[0]->user_name . "' ");
    $agency = DB::select(" select * from customers_aistockist_agency where agency=1 AND user_name <> '" . $ThisCustomer[0]->user_name . "' ");


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
            Left Join customers ON db_giftvoucher_cus.customer_username = customers.user_name
            WHERE
            customers.id = " . $sRow->customers_id_fk . "
            AND
            curdate() BETWEEN db_giftvoucher_code.pro_sdate and db_giftvoucher_code.pro_edate
            AND
            db_giftvoucher_code.status = 1  "); //AND expiry_date>=now()
    // dd($giftvoucher_this);
    $giftvoucher_this = @$giftvoucher_this[0]->giftvoucher_value;

    $rs = DB::select(" SELECT count(*) as cnt FROM db_order_products_list WHERE frontstore_id_fk=$id ");



    $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total from db_order_products_list WHERE frontstore_id_fk=$id GROUP BY frontstore_id_fk ");
    // dd($sFrontstoreDataTotal);
    if ($sFrontstoreDataTotal) {
      $vat = floatval(@$sFrontstoreDataTotal[0]->total) - (floatval(@$sFrontstoreDataTotal[0]->total) / 1.07);

      // $vat_data = DB::table('dataset_vat')
      // ->where('business_location_id_fk', '=', $sBranchs[0]->business_location_id_fk)
      // ->first();
      // $vat_data = $vat_data->vat;
      // //vatใน 7%
      // $vat = floatval(@$sFrontstoreDataTotal[0]->total) * ($vat_data / (100 + $vat_data));
      //มูลค่าสินค้า
      // $price_vat = floatval(@$sFrontstoreDataTotal[0]->total) - $p_vat;
      // $price_total = $price + $shipping;

      // dd( $vat);

      $vat = $vat > 0 ? $vat : 0;
      $product_value = str_replace(",", "", floatval(@$sFrontstoreDataTotal[0]->total) - $vat);
      $product_value = $product_value > 0 ? $product_value : 0;
      $total = @$sFrontstoreDataTotal[0]->total > 0 ? @$sFrontstoreDataTotal[0]->total : 0;
      DB::select(" UPDATE db_orders SET product_value=" . ($product_value) . ",tax=" . ($vat) . ",sum_price=" . ($total) . " WHERE id=$id ");
    } else {
      DB::select(" UPDATE db_orders SET product_value=0,tax=0,sum_price=0 WHERE id=$id  ");
    }

    $sAccount_bank = \App\Models\Backend\Account_bank::get();

    $DATE_CREATED = date("Y-m-d", strtotime($sRow->created_at));
    $DATE_YESTERDAY = Carbon::yesterday()->format('Y-m-d');
    $DATE_TODAY = Carbon::today()->format('Y-m-d');

    $sPermission = \Auth::user()->permission; // Super Admin == 1
    $position_level = \Auth::user()->position_level;
    // dd($sPermission);
    // dd($position_level);
    $ChangePurchaseType = 0; // ปิด / ไม่แสดง
    if ($sPermission == 1) {
      $ChangePurchaseType = 1; // เปิด / แสดง
    } else {
      // dataset_position_level
      // 4 Supervisor/Manager
      // 2 CS แผนกขาย
      if ($position_level == 4) {
        if ($DATE_CREATED >= $DATE_YESTERDAY && $DATE_CREATED <= $DATE_TODAY) $ChangePurchaseType = 1;
      } else {
        if ($DATE_CREATED == $DATE_TODAY) $ChangePurchaseType = 1;
      }
    }

    return View('backend.frontstore.viewdata')->with(
      array(
        'sRow' => $sRow,

        'sPurchase_type' => $sPurchase_type,
        'sProductUnit' => $sProductUnit,
        'sDistribution_channel' => $sDistribution_channel,
        'Products' => $Products,
        'sProvince' => $sProvince,
        'sAmphures' => $sAmphures,
        'sTambons' => $sTambons,
        'Delivery_location' => $Delivery_location,
        'CusAddrFrontstore' => $CusAddrFrontstore,
        'sBusiness_location' => $sBusiness_location,
        'sFee' => $sFee,
        'sBranchs' => $sBranchs, 'User_branch_id' => $User_branch_id,
        'aistockist' => $aistockist,
        'agency' => $agency,
        'CusName' => $CusName,
        'Cus_Aicash' => $Cus_Aicash,
        'Cus_name_Aicash' => $Cus_name_Aicash,
        'BranchName' => $BranchName,
        'PurchaseName' => $PurchaseName,
        'giftvoucher_this' => $giftvoucher_this,
        'sAccount_bank' => $sAccount_bank,
        'sPay_type' => $sPay_type,
        'shipping_special' => $shipping_special,
        'sFrontstoreDataTotal' => $sFrontstoreDataTotal,
        'DATE_CREATED' => $DATE_CREATED,
        'DATE_YESTERDAY' => $DATE_YESTERDAY,
        'DATE_TODAY' => $DATE_TODAY,
        'ChangePurchaseType' => $ChangePurchaseType,
      )
    );
  }

  public function fnManageGiveaway($frontstore_id)
  {

    // แถม
    if (!empty($frontstore_id)) {

      $sFrontstore = \App\Models\Backend\Frontstore::find($frontstore_id);
      // return  $sFrontstore->business_location_id_fk;
      // return  $sFrontstore->pv_total;
      // return  $sFrontstore->customers_id_fk;
      // return  $sFrontstore->purchase_type_id_fk;
      $sCustomer = \App\Models\Backend\Customers::find($sFrontstore->customers_id_fk);
      // return $sCustomer->user_name;
      // ลยก่อน ถ้าเข้าเงื่อนไขค่อยเพิ่มเข้าไปใหม่
      DB::table('db_order_products_list_giveaway')->where('order_id_fk', '=', $sFrontstore->id)->delete();
      DB::table('db_order_products_list')
        ->where('frontstore_id_fk', $sFrontstore->id)
        ->where('code_order', $sFrontstore->code_order)
        ->where('type_product', 'giveaway')
        ->where('add_from', '4')
        ->delete();

      if (!empty($sFrontstore->business_location_id_fk) and !empty($sFrontstore->pv_total)) {
        $check_giveaway = GiveawayController::check_giveaway($sFrontstore->purchase_type_id_fk, $sCustomer->user_name, $sFrontstore->pv_total);
        // return $check_giveaway;

        if (!empty(@$check_giveaway)) {

          foreach (@$check_giveaway as $value) {
            $insert_order_products_list_type_giveaway = new DbOrderProductsList();
            if (@$value['status'] == 'success') {
              if ($value['rs']) {

                $_ch = DB::table('db_order_products_list')
                  ->where('frontstore_id_fk', $sFrontstore->id)
                  ->where('code_order', $sFrontstore->code_order)
                  ->where('customers_id_fk', $sFrontstore->customers_id_fk)
                  ->where('distribution_channel_id_fk', '2')
                  ->where('giveaway_id_fk', $value['rs']['giveaway_id'])
                  ->where('amt', $value['rs']['count_free'])
                  ->where('type_product', 'giveaway')
                  ->where('add_from', '4')
                  ->get();
                if ($_ch->count() == 0) {

                  $insert_order_products_list_type_giveaway->frontstore_id_fk = $sFrontstore->id;
                  $insert_order_products_list_type_giveaway->code_order = $sFrontstore->code_order;
                  $insert_order_products_list_type_giveaway->customers_id_fk = $sFrontstore->customers_id_fk;
                  $insert_order_products_list_type_giveaway->distribution_channel_id_fk = 2;
                  $insert_order_products_list_type_giveaway->giveaway_id_fk = $value['rs']['giveaway_id'];
                  $insert_order_products_list_type_giveaway->product_name = $value['rs']['name'];
                  $insert_order_products_list_type_giveaway->amt = $value['rs']['count_free'];
                  $insert_order_products_list_type_giveaway->type_product = 'giveaway';
                  $insert_order_products_list_type_giveaway->add_from = 4;
                  $insert_order_products_list_type_giveaway->save();
                }

                if ($value['rs']['type'] == 1) { //product แถมเป็นสินค้า

                  $product = DB::table('db_giveaway_products')
                    ->select(
                      'products_details.product_id_fk',
                      'products_details.product_name',
                      'dataset_product_unit.product_unit',
                      'dataset_product_unit.group_id as unit_id',
                      'db_giveaway_products.product_amt'
                    )
                    ->leftJoin('products_details', 'products_details.product_id_fk', '=', 'db_giveaway_products.product_id_fk')
                    ->leftJoin('dataset_product_unit', 'dataset_product_unit.group_id', '=', 'db_giveaway_products.product_unit')
                    ->where('db_giveaway_products.giveaway_id_fk', '=', $value['rs']['giveaway_id'])
                    ->where('products_details.lang_id', '=', $sFrontstore->business_location_id_fk)
                    ->where('dataset_product_unit.lang_id', '=', $sFrontstore->business_location_id_fk)
                    ->get();

                  foreach ($value['rs']['product'] as $giveaway_product) {

                    DB::table('db_order_products_list_giveaway')->insertOrignore([
                      'order_id_fk' => $sFrontstore->id,
                      'product_list_id_fk' => $insert_order_products_list_type_giveaway->id,
                      'giveaway_id_fk' => $value['rs']['giveaway_id'],
                      'code_order' => $sFrontstore->code_order,
                      'product_id_fk' => $giveaway_product->product_id_fk,
                      'product_name' => $giveaway_product->product_name,
                      'product_unit_id_fk' => $giveaway_product->unit_id,
                      'product_amt' => $giveaway_product->product_amt,
                      'product_unit_name' => $giveaway_product->product_unit,
                      'free' => $value['rs']['count_free'],
                      'type_product' => 'giveaway_product',
                    ]);
                  }
                } else { //gv แถมเป้นกิฟวอยเชอ

                  DB::table('db_order_products_list_giveaway')->insert([
                    'order_id_fk' => $sFrontstore->id,
                    'product_list_id_fk' => $insert_order_products_list_type_giveaway->id,
                    'product_name' => 'GiftVoucher',
                    'code_order' => $sFrontstore->code_order,
                    'giveaway_id_fk' => $value['rs']['giveaway_id'],
                    'product_amt' => 1,
                    'gv_free' => $value['rs']['gv'],
                    'free' => $value['rs']['count_free'],
                    'type_product' => 'giveaway_gv',

                  ]);
                }
              }
            }
          }
        }
      }
    }
  }



  public function update(Request $request, $id)
  {
  //  dd($request->all());
    // dd($request->transfer_money_datetime." : AAAA");
    // db_delivery

    if (isset($request->pay_type_transfer_slip) && $request->pay_type_transfer_slip == '1') {
    if(isset($request->note_bill_id)){
      foreach($request->note_bill_id as $key_data => $n_data){
        DB::table('payment_slip')->where('id',$n_data)->update([
          'note' => $request->note_bill[$key_data],
        ]);
      }
    }
  }

  $check_data = \App\Models\Backend\Frontstore::select('id','code_order')->find($request->frontstore_id);
  if($check_data){
    $check = \App\Models\Backend\Frontstore::select('id','code_order')->where('code_order',$check_data->code_order)->where('id','!=',$check_data->id)->get();
    if(count($check )>0){
        return redirect()->back()->with('error','ไม่สามารถทำรายการได้เนื่องจากเลขบิลซ้ำ!! ..กรุณาเยิกเลิกและออกรายการใหม่');
        return false;
    }
  }


    DB::beginTransaction();
    try {
      // dd($request->all());
      // shipping_special
      $orderHistoryLog = new DbOrderHistoryLog;

      $this->fnManageGiveaway(@$request->frontstore_id);
      $sRow = \App\Models\Backend\Frontstore::find($request->frontstore_id);



      if($sRow->approve_status==2 || $sRow->approve_status==6 || $sRow->approve_status==0){
        if($sRow->pay_with_other_bill_note!='' && $sRow->pay_with_other_bill == 1){
          $other_bill1 = DB::table('db_orders')->select('id','code_order')->whereIn('approve_status',[1,2,6])->where('code_order',$sRow->pay_with_other_bill_note)->update([
            'approve_status' => 1,
            'approve_one_more' => 1,
            'gv_before' => 0,
          ]);
          $other_bill2 = DB::table('db_orders')->select('id','code_order')->whereIn('approve_status',[1,2,6])->where('pay_with_other_bill_note',$sRow->pay_with_other_bill_note)->update([
            'approve_status' => 1,
          ]);
        }else{
          $other_bill2 = DB::table('db_orders')->select('id','code_order')->whereIn('approve_status',[1,2,6])->where('pay_with_other_bill_note',$sRow->code_order)->update([
            'approve_status' => 1,
          ]);
        }
      }

      $delivery_location = request('delivery_location');
      $shipping_special = $request->shipping_special;

      if ($delivery_location == 0) { //รับสินค้าด้วยตัวเอง
        $delivery_location_frontend = 'sent_office';
      } elseif ($delivery_location == 1) { //ที่อยู่ตามบัตร ปชช.
        $delivery_location_frontend = 'sent_address_card';
      } elseif ($delivery_location == 2) { //ที่อยู่จัดส่งไปรษณีย์
        $delivery_location_frontend = 'sent_address';
      } elseif ($delivery_location == 3) { //ที่อยู่กำหนดเอง
        $delivery_location_frontend = 'sent_address_other';
      } elseif ($delivery_location == 4) { //จัดส่งพร้อมบิลอื่น
        $delivery_location_frontend = 'sent_another_bill';
      } elseif ($delivery_location == 5) { //ส่งแบบพิเศษ/พรีเมี่ยม
        $delivery_location_frontend = 'shipping_special';
        $shipping_special = 1;
      } else {
        $delivery_location_frontend = '';
        $shipping_special = 0;
      }

      $update_orders = DB::table('db_orders')
        ->where('id', $request->frontstore_id)
        ->update([
          'delivery_location_frontend' => $delivery_location_frontend,
          'shipping_special' => $shipping_special,
          'approve_status' => 1,
          'order_status_id_fk' => 2
        ]);


      if (isset($request->pay_type_transfer_slip) && $request->pay_type_transfer_slip == '1') {

        // dd($request->sentto_branch_id);

        if ($request->hasFile('image01')) {
          @UNLINK(@$sRow->file_slip);
          $this->validate($request, [
            'image01' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);
          $image = $request->file('image01');
          $name = 'S2' . time() . '.' . $image->getClientOriginalExtension();
          $image_path = 'local/public/files_slip/' . date('Ym');
          $image->move($image_path, $name);
          $sRow->file_slip = $image_path . $name;
          DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`, `create_at`, `update_at`)
                   VALUES
                   ('" . request('customers_id_fk') . "', '', '" . $sRow->code_order . "', '$image_path', '$name', now(), now()) ");
          $lastInsertId_01 = DB::getPdo()->lastInsertId();
        }

        if ($request->hasFile('image02')) {
          @UNLINK(@$sRow->file_slip_02);
          $this->validate($request, [
            'image02' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);
          $image = $request->file('image02');
          $name = 'S2' . time() . '.' . $image->getClientOriginalExtension();
          $image_path = 'local/public/files_slip/' . date('Ym');
          $image->move($image_path, $name);
          $sRow->file_slip_02 = $image_path . $name;
          DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`, `create_at`, `update_at`)
                   VALUES
                   ('" . request('customers_id_fk') . "', '', '" . $sRow->code_order . "', '$image_path', '$name', now(), now()) ");
          $lastInsertId_02 = DB::getPdo()->lastInsertId();
        }

        if ($request->hasFile('image03')) {
          @UNLINK(@$sRow->file_slip_03);
          $this->validate($request, [
            'image03' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);
          $image = $request->file('image03');
          $name = 'S3' . time() . '.' . $image->getClientOriginalExtension();
          $image_path = 'local/public/files_slip/' . date('Ym');
          $image->move($image_path, $name);
          $sRow->file_slip_03 = $image_path . $name;
          DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`, `create_at`, `update_at`)
                   VALUES
                   ('" . request('customers_id_fk') . "', '', '" . $sRow->code_order . "', '$image_path', '$name', now(), now()) ");
          $lastInsertId_03 = DB::getPdo()->lastInsertId();
        }

        $sRow->account_bank_id = request('account_bank_id');

        $sRow->transfer_money_datetime = request('transfer_money_datetime') ? request('transfer_money_datetime') : NULL;
        $sRow->transfer_money_datetime_02 = request('transfer_money_datetime_02') ? request('transfer_money_datetime_02') : NULL;
        $sRow->transfer_money_datetime_03 = request('transfer_money_datetime_03') ? request('transfer_money_datetime_03') : NULL;
        $sRow->note_fullpayonetime = request('note_fullpayonetime');
        $sRow->note_fullpayonetime_02 = request('note_fullpayonetime_02');
        $sRow->note_fullpayonetime_03 = request('note_fullpayonetime_03');
        $sRow->pay_with_other_bill = request('pay_with_other_bill');
        $sRow->pay_with_other_bill_note = request('pay_with_other_bill_note');
        $sRow->sentto_branch_id = request('sentto_branch_id');
        $sRow->check_press_save = 2;
        if(request('gv_before')){
          $sRow->gv_before = request('gv_before');
        }
        $sRow->number_bill = request('number_bill');
        if($sRow->pay_with_other_bill==1){
            $ot_bill = DB::table('db_orders')->select('id')->where('pay_with_other_bill_note',$sRow->pay_with_other_bill_note)->whereNotIn('id',[$sRow->id])->get();
            $number_bill_curr = 1;
            foreach($ot_bill as $ot){
              $number_bill_curr++;
            }
            DB::table('db_orders')->where('code_order',$sRow->pay_with_other_bill_note)->update([
              'number_bill_curr' => $number_bill_curr,
            ]);
        }

        if (empty(request('shipping_price'))) {

          $sum_price = str_replace(',', '', request('sum_price'));
          $fee_amt = request('fee_amt') > 0 ? str_replace(',', '', request('fee_amt')) : 0;
          // dd(str_replace(',','',request('fee_amt')));
          // dd($fee_amt);
          $sRow->total_price    =  $sum_price  + $fee_amt;
        } else {

          // wut เพิ่มมา
          $sum_price = str_replace(',', '', request('sum_price'));
          $fee_amt = request('fee_amt') > 0 ? str_replace(',', '', request('fee_amt')) : 0;
          $shipping_price = request('shipping_price') > 0 ? str_replace(',', '', request('shipping_price')) : 0;
          $sRow->total_price    =  $sum_price  + $fee_amt +  $shipping_price;
        }

        // กรณีโอนชำระ
        if (request('pay_type_id_fk') == 1 || request('pay_type_id_fk') == 8 || request('pay_type_id_fk') == 10 || request('pay_type_id_fk') == 11 || request('pay_type_id_fk') == 12 || request('pay_type_id_fk') == 3 || request('pay_type_id_fk') == 6 || request('pay_type_id_fk') == 9 || request('pay_type_id_fk') == 14) {
          $sRow->approve_status = 1;
          $sRow->order_status_id_fk = 2;
        } else {
          $rs = PvPayment::PvPayment_type_confirme($sRow->id, \Auth::user()->id, '1', 'admin');
        }
        $sRow->note    = request('note');

        if ($sRow->purchase_type_id_fk == 5) {

          $rs_log_gift = \App\Models\Frontend\GiftVoucher::log_gift($sRow->total_price, $sRow->customers_id_fk, $sRow->code_order, $sRow->gift_voucher_price);
          DB::commit();
        }

        if(request('number_bill')){
          $sRow->number_bill = request('number_bill');
        }


        $sRow->save();

        $this->fncUpdateDeliveryAddress($sRow->id);
        $orderHistoryLog->store($sRow->id, DatasetOrderHistoryStatus::CREATE_ORDER, \Auth::user()->id);
        // return redirect()->to(url("backend/frontstore/".$request->frontstore_id."/edit")); pay_with_other_bill_note
        return redirect()->to(url("backend/frontstore"));
      } else if (isset($request->receipt_save_list)) {


        // dd($request->transfer_money_datetime." : BBBB");


        // dd($sRow);

        $sRow->date_setting_code = date('ym');

        $sRow->charger_type    = request('charger_type');
        $sRow->credit_price    = str_replace(',', '', request('credit_price'));
        $sRow->sum_credit_price    = str_replace(',', '', request('sum_credit_price'));
        $sRow->pay_type_id_fk    = request('pay_type_id_fk') ? request('pay_type_id_fk') : 0;
        $sRow->gift_voucher_cost    = str_replace(',', '', request('gift_voucher_cost'));
        $sRow->member_id_aicash    = str_replace(',', '', request('member_id_aicash'));
        $sRow->aistockist    = request('aistockist');
        $sRow->agency    = request('agency');
        $sRow->note    = request('note');
        $sRow->delivery_location    = request('delivery_location');
        $sRow->cash_price    = str_replace(',', '', request('cash_price'));
        $sRow->shipping_price    = str_replace(',', '', request('shipping_price'));
        $sRow->fee    =  str_replace(',', '', request('fee'));
        $sRow->fee_amt    =  str_replace(',', '', request('fee_amt'));
        $sRow->sum_price    =  str_replace(',', '', request('sum_price'));
        $sRow->cash_pay    =  str_replace(',', '', request('cash_pay'));
        $sRow->account_bank_id = request('account_bank_id');
        $sRow->transfer_money_datetime = request('transfer_money_datetime') ? request('transfer_money_datetime') : NULL;
        $sRow->transfer_money_datetime_02 = request('transfer_money_datetime_02') ? request('transfer_money_datetime_02') : NULL;
        $sRow->transfer_money_datetime_03 = request('transfer_money_datetime_03') ? request('transfer_money_datetime_03') : NULL;

        $sRow->note_fullpayonetime = request('note_fullpayonetime');
        $sRow->note_fullpayonetime_02 = request('note_fullpayonetime_02');
        $sRow->note_fullpayonetime_03 = request('note_fullpayonetime_03');

        $sRow->pay_with_other_bill = request('pay_with_other_bill');
        $sRow->pay_with_other_bill_note = request('pay_with_other_bill_note');
        if(request('gv_before')){
          $sRow->gv_before = request('gv_before');
        }

        $sRow->number_bill = request('number_bill');
        if($sRow->pay_with_other_bill==1){
            $ot_bill = DB::table('db_orders')->select('id')->where('pay_with_other_bill_note',$sRow->pay_with_other_bill_note)->whereNotIn('id',[$sRow->id])->get();
            $number_bill_curr = 1;
            foreach($ot_bill as $ot){
              $number_bill_curr++;
            }
            DB::table('db_orders')->where('code_order',$sRow->pay_with_other_bill_note)->update([
              'number_bill_curr' => $number_bill_curr,
            ]);
        }


        $sRow->gift_voucher_price = request('gift_voucher_price') ? request('gift_voucher_price') : 0;
        $sRow->bill_transfer_other = request('bill_transfer_other');

        if (empty(request('shipping_price'))) {

          $sum_price = str_replace(',', '', request('sum_price'));
          $fee_amt = request('fee_amt') > 0 ? str_replace(',', '', request('fee_amt')) : 0;
          // dd(str_replace(',','',request('fee_amt')));
          // dd($fee_amt);
          $sRow->total_price    =  $sum_price  + $fee_amt;
        } else {

          // wut เพิ่มมา
          $sum_price = str_replace(',', '', request('sum_price'));
          $fee_amt = request('fee_amt') > 0 ? str_replace(',', '', request('fee_amt')) : 0;
          $shipping_price = request('shipping_price') > 0 ? str_replace(',', '', request('shipping_price')) : 0;
          $sRow->total_price    =  $sum_price  + $fee_amt +  $shipping_price;
        }

        // dd("976");

        $sRow->action_user = \Auth::user()->id;
        $sRow->action_date = date('Y-m-d H:i:s');

        $lastInsertId_01 = 0;
        $lastInsertId_02 = 0;
        $lastInsertId_03 = 0;

        $request = app('request');
        if ($request->hasFile('image01')) {
          @UNLINK(@$sRow->file_slip);
          $this->validate($request, [
            'image01' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);
          $image = $request->file('image01');
          $name = 'S' . time() . '.' . $image->getClientOriginalExtension();
          $image_path = 'local/public/files_slip/' . date('Ym');
          $image->move($image_path, $name);
          $sRow->file_slip = $image_path . $name;

          DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`, `create_at`, `update_at`)
                   VALUES
                   ('" . request('customers_id_fk') . "', '', '" . $sRow->code_order . "', '$image_path', '$name', now(), now()) ");

          $lastInsertId_01 = DB::getPdo()->lastInsertId();
        }

        if ($request->hasFile('image02')) {
          @UNLINK(@$sRow->file_slip_02);
          $this->validate($request, [
            'image02' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);
          $image = $request->file('image02');
          $name = 'S2' . time() . '.' . $image->getClientOriginalExtension();
          $image_path = 'local/public/files_slip/' . date('Ym');
          $image->move($image_path, $name);
          $sRow->file_slip_02 = $image_path . $name;
          DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`, `create_at`, `update_at`)
                   VALUES
                   ('" . request('customers_id_fk') . "', '', '" . $sRow->code_order . "', '$image_path', '$name', now(), now()) ");
          $lastInsertId_02 = DB::getPdo()->lastInsertId();
        }

        if ($request->hasFile('image03')) {
          @UNLINK(@$sRow->file_slip_03);
          $this->validate($request, [
            'image03' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);
          $image = $request->file('image03');
          $name = 'S3' . time() . '.' . $image->getClientOriginalExtension();
          $image_path = 'local/public/files_slip/' . date('Ym');
          $image->move($image_path, $name);
          $sRow->file_slip_03 = $image_path . $name;
          DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`, `create_at`, `update_at`)
                   VALUES
                   ('" . request('customers_id_fk') . "', '', '" . $sRow->code_order . "', '$image_path', '$name', now(), now()) ");
          $lastInsertId_03 = DB::getPdo()->lastInsertId();
        }





        //id_order,id_admin,1 ติดต่อหน้าร้าน 2 ช่องทางการจำหน่ายอื่นๆ  dataset_distribution_channel>id  ,'customer หรือ admin'
        // dd("1233");
        // dd(request('purchase_type_id_fk'));
        if ($sRow->purchase_type_id_fk == 5) {
          // dd($sRow);
          //dd($sRow->total_price, $sRow->customers_id_fk, $sRow->code_order,$sRow->gift_voucher_price);
          $rs_log_gift = \App\Models\Frontend\GiftVoucher::log_gift($sRow->total_price, $sRow->customers_id_fk, $sRow->code_order, $sRow->gift_voucher_price);
          DB::commit();
        }

        // dd(request('sentto_branch_id'));
        // dd(request('branch_id_fk'));
        // dd($request->delivery_location);

        $db_orders = DB::select(" select code_order from db_orders where id=" . $sRow->id . " ");

        // if(@$request->delivery_location  == 0 || @$request->delivery_location  == 4 ){
        // $sentto_branch_id = request('sentto_branch_id')?request('sentto_branch_id'):0;
        // dd($sentto_branch_id);
        // dd(request('sentto_branch_id'));
        // dd($request->frontstore_id);

        $sRow->sentto_branch_id    = request('sentto_branch_id');
        // DB::select("UPDATE db_orders SET sentto_branch_id=".$sentto_branch_id.", address_sent_id_fk='0' WHERE (id='".$request->frontstore_id."')");
        // }

        if (@$request->delivery_location == 1) {

          DB::select(" DELETE FROM customers_addr_sent WHERE receipt_no='" . @$db_orders[0]->code_order . "' ");

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
                                      where customers_address_card.customer_id = " . (@$sRow->customers_id_fk ? @$sRow->customers_id_fk : 0) . "
                                 ");
                                //  gift_voucher_price
          $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (invoice_code,customer_id, recipient_name, house_no, zipcode, amphures_id_fk, district_id_fk, province_id_fk, from_table, from_table_id, receipt_no) VALUES ('" . @$db_orders[0]->code_order . "','" . @$request->customers_id_fk . "', '" . @$addr[0]->first_name . " " . @$addr[0]->last_name . "','" . @$addr[0]->card_house_no . "','" . @$addr[0]->card_zipcode . "', '" . @$addr[0]->card_amphures_id_fk . "', '" . @$addr[0]->card_district_id_fk . "', '" . @$addr[0]->card_province_id_fk . "', 'customers_address_card', '" . @$addr[0]->id . "','" . @$db_orders[0]->code_order . "') ");


          DB::select("UPDATE db_orders SET address_sent_id_fk='" . (DB::getPdo()->lastInsertId()) . "' WHERE (id='" . $request->frontstore_id . "')");
        }

        if (@$request->delivery_location == 2) {

          DB::select(" DELETE FROM customers_addr_sent WHERE receipt_no='" . @$db_orders[0]->code_order . "' ");

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
                           " . @$request->customers_id_fk . " ");

          @$recipient_name = @$addr[0]->prefix_name . @$addr[0]->first_name . " " . @$addr[0]->last_name;

          $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (invoice_code,
                        customer_id,
                        recipient_name,
                         house_no,house_name, zipcode,
                         amphures_id_fk, district_id_fk,province_id_fk,
                          from_table, from_table_id, receipt_no) VALUES ( '" . @$db_orders[0]->code_order . "',
                          '" . @$request->customers_id_fk . "',
                          '" . @$recipient_name . "',
                          '" . @$addr[0]->house_no . "','" . @$addr[0]->house_name . "','" . @$addr[0]->zipcode . "',
                          '" . @$addr[0]->district . "', '" . @$addr[0]->district_sub . "', '" . @$addr[0]->province . "',
                          'customers_detail', '" . @$addr[0]->id . "','" . @$request->invoice_code . "') ");

          DB::select("UPDATE db_orders SET address_sent_id_fk='" . (DB::getPdo()->lastInsertId()) . "' WHERE (id='" . $request->frontstore_id . "')");
        }


        if (@$request->delivery_location == 3) {

          DB::select(" DELETE FROM customers_addr_sent WHERE receipt_no='" . @$request->invoice_code . "' ");

          $addr = DB::select("select customers_addr_frontstore.* ,dataset_provinces.name_th as provname,
                              dataset_amphures.name_th as ampname,dataset_districts.name_th as tamname
                              from customers_addr_frontstore
                              Left Join dataset_provinces ON customers_addr_frontstore.province_id_fk = dataset_provinces.id
                              Left Join dataset_amphures ON customers_addr_frontstore.amphur_code = dataset_amphures.id
                              Left Join dataset_districts ON customers_addr_frontstore.tambon_code = dataset_districts.id
                              where customers_addr_frontstore.frontstore_id_fk = " . @$request->frontstore_id . " ");

          $rs = DB::select(" INSERT IGNORE INTO customers_addr_sent (invoice_code,customer_id, recipient_name, house_no, zipcode,amphures_id_fk,district_id_fk, province_id_fk, from_table, from_table_id, receipt_no) VALUES ('" . @$db_orders[0]->code_order . "','" . @$request->customers_id_fk . "', '" . @$addr[0]->recipient_name . "','" . @$addr[0]->addr_no . "','" . @$addr[0]->zip_code . "', '" . @$addr[0]->ampname . "', '" . @$addr[0]->tamname . "', '" . @$addr[0]->provname . "', 'customers_addr_frontstore', '" . @$addr[0]->id . "','" . @$db_orders[0]->code_order . "') ");

          DB::select("UPDATE db_orders SET address_sent_id_fk='" . (DB::getPdo()->lastInsertId()) . "' WHERE (id='" . $request->frontstore_id . "')");
        }

        DB::select("UPDATE
              db_delivery_packing_code
              Inner Join db_delivery_packing ON db_delivery_packing_code.id = db_delivery_packing.packing_code
              Inner Join db_delivery ON db_delivery_packing.delivery_id_fk = db_delivery.id
              Inner Join db_orders ON db_delivery.receipt = db_orders.invoice_code
              SET
              db_delivery_packing_code.address_sent_id_fk=db_orders.address_sent_id_fk
              WHERE
              db_orders.invoice_code='" . @$request->invoice_code . "' ");

        // dd($request);
        if ($request->frontstore_id) {
          $ch_aicash_02 = DB::select(" select * from db_orders where id=" . $request->frontstore_id . " ");
        } else {
          $ch_aicash_02 = NULL;
        }

        // dd($ch_aicash_02[0]->member_id_aicash);

        // เช็คเรื่องการตัดยอด Ai-Cash
        $ch_aicash_01 = DB::select(" select * from customers where id=" . $ch_aicash_02[0]->member_id_aicash . " ");

        // dd($ch_aicash_02[0]->aicash_price);
        // ถ้าค่าที่ส่งมาตัด รวมกับ ค่าเดิม แล้วเกินค่าเดิม ให้ลบกันได้เลย แต่ถ้า รวมแล้วน่อยกว่า ให้เอาค่าก่อนหน้า มาบวกก่อน ค่อยลบออก
        // if(@$ch_aicash_01[0]->ai_cash>0){
        //    if( ($ch_aicash_02[0]->aicash_price + $ch_aicash_01[0]->ai_cash) >= $ch_aicash_01[0]->ai_cash ){
        //       DB::select(" UPDATE customers SET ai_cash=(ai_cash-".$ch_aicash_02[0]->aicash_price.") where id=".$ch_aicash_02[0]->member_id_aicash." ");
        //    }else{
        //         $x = $ch_aicash_01[0]->ai_cash - $ch_aicash_02[0]->aicash_price ;
        //         DB::select(" UPDATE customers SET ai_cash=($x) where id=".$ch_aicash_02[0]->member_id_aicash." ");
        //    }
        //  }

        $r_addr = DB::select("select address_sent_id_fk from db_orders WHERE (id='" . $request->frontstore_id . "')");

        if (@$request->delivery_location != 3) {
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
                  customers_addr_sent.id='" . ($r_addr[0]->address_sent_id_fk) . "' ");
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



        $sRow->check_press_save = 2;

        $sRow->save();

        DB::select(" UPDATE `payment_slip` SET `order_id`=$sRow->id ,`code_order`='$sRow->code_order' WHERE (`id`=$lastInsertId_01);");
        DB::select(" UPDATE `payment_slip` SET `order_id`=$sRow->id ,`code_order`='$sRow->code_order' WHERE (`id`=$lastInsertId_02);");
        DB::select(" UPDATE `payment_slip` SET `order_id`=$sRow->id ,`code_order`='$sRow->code_order' WHERE (`id`=$lastInsertId_03);");



        DB::select(" UPDATE db_orders SET pv_total=0 WHERE pv_total is null; ");

        if (request('pay_type_id_fk') == 1 || request('pay_type_id_fk') == 8 || request('pay_type_id_fk') == 10 || request('pay_type_id_fk') == 11 || request('pay_type_id_fk') == 12 || request('pay_type_id_fk') == 3 || request('pay_type_id_fk') == 6 || request('pay_type_id_fk') == 9 || request('pay_type_id_fk') == 14) {

          DB::select(" UPDATE `db_orders` SET `approve_status`=1,`order_status_id_fk`=2 WHERE (`id`=" . $sRow->id . ") ");
        } else {
          $rs = PvPayment::PvPayment_type_confirme($sRow->id, \Auth::user()->id, '1', 'admin');
          //DB::select(" UPDATE `db_orders` SET `approve_status`=2 WHERE (`id`=".$sRow->id.") ");
        }

        if ($request->shipping_free == 1) {
          DB::select(" UPDATE `db_orders` SET `shipping_price`=0 WHERE (`id`=" . $sRow->id . ") ");
        }

        // วุฒิปรับให้อนุมัติก่อนค่อยอัพเดทไปโชวรอส่ง
        // if(@$request->pay_type_id_fk != 1 && @$request->pay_type_id_fk != 8 && @$request->pay_type_id_fk != 10 && @$request->pay_type_id_fk != 11 && @$request->pay_type_id_fk != 12){
        $this->fncUpdateDeliveryAddress($sRow->id);
        $this->fncUpdateDeliveryAddressDefault($sRow->id);

        $orderHistoryLog->store($sRow->id, DatasetOrderHistoryStatus::CREATE_ORDER, \Auth::user()->id);
        $orderHistoryLog->store($sRow->id, DatasetOrderHistoryStatus::APPROVE_ORDER, \Auth::user()->id);
        // }

        DB::commit();
        return redirect()->to(url("backend/frontstore"));
        // return redirect()->back()->with('success','success');
      } else {

        DB::commit();
        // dd($request->all());
        return $this->form($id);
      }
    } catch (\Exception $e) {
      DB::rollback();
      return $e->getMessage();
    } catch (\FatalThrowableError $fe) {
      DB::rollback();
      return $e->getMessage();
    }
  }

  public static function fncUpdateDeliveryAddress($id)
  {
    $sRow = \App\Models\Backend\Frontstore::find($id);
    // dd($sRow->delivery_location);
    if (@$sRow->delivery_location == 0) {
      DB::select(" UPDATE `db_orders` SET invoice_code=code_order WHERE (`id`=" . $sRow->id . ") ");
      DB::select(" DELETE FROM `db_delivery` WHERE (`orders_id_fk`=" . $sRow->id . ") ");
    }

    // วุฒิปรับ approve_status > 1
    // if($sRow->check_press_save==2 && $sRow->approve_status>0 && $sRow->id!='' && @$sRow->delivery_location>0 ){
    if ($sRow->check_press_save == 2 && $sRow->approve_status > 1 && $sRow->id != '' && @$sRow->delivery_location > 0 || @$sRow->distribution_channel_id_fk == 3 && @$sRow->delivery_location > 0) {

      // วุฒิเพิ่มมาเช็คว่ามาชาร์ไหม จะได้รู้ว่าต้องบวกค่าธรรมเนียมไหม
      $check_order = DB::table('db_orders')->where('id',$sRow->id)->first();
      if($check_order->charger_type==1){
   // วุฒิเอา (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END) ออกมา
            DB::select("
            INSERT IGNORE INTO db_delivery
            ( orders_id_fk,receipt, customer_id, business_location_id,branch_id_fk , delivery_date, billing_employee, created_at,list_type,shipping_price,total_price)
            SELECT id,code_order,customers_id_fk,business_location_id_fk,branch_id_fk,created_at,action_user,now(),2,shipping_price,
            (SUM(
            (CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) +
            (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
            (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
            (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
            (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END)
            ))
            FROM db_orders WHERE (`id`=" . $sRow->id . ") AND delivery_location <> 0 ;
          ");
      }else{
   // วุฒิเอา (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END) ออกมา
  // วุฒิเอา (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) ออกมา
                DB::select("
                INSERT IGNORE INTO db_delivery
                ( orders_id_fk,receipt, customer_id, business_location_id,branch_id_fk , delivery_date, billing_employee, created_at,list_type,shipping_price,total_price)
                SELECT id,code_order,customers_id_fk,business_location_id_fk,branch_id_fk,created_at,action_user,now(),2,shipping_price,
                (SUM(
                (CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) +
                (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
                (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
                (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END)
                ))
                FROM db_orders WHERE (`id`=" . $sRow->id . ") AND delivery_location <> 0 ;
              ");
      }

      // Clear ก่อน ค่อย อัพเดต ใส่ตามเงื่อนไขทีหลัง
      DB::select(" UPDATE db_delivery
                          SET
                          recipient_name = '',
                          addr_send = '',
                          postcode = '',
                          mobile = '',
                          tel_home = '',
                          province_id_fk = '',
                          province_name = '',
                          shipping_price = '" . $sRow->shipping_price . "',
                          delivery_date = now() ,
                          set_addr_send_this = '0'
                          where orders_id_fk = '" . $sRow->id . "'

                         ");

      //delivery_location = ที่อยู่ผู้รับ>0=รับสินค้าด้วยตัวเอง|1=ที่อยู่ตามบัตร ปชช.>customers_address_card|2=ที่อยู่จัดส่งไปรษณีย์หรือที่อยู่ตามที่ลงทะเบียนไว้ในระบบ>customers_detail|3=ที่อยู่กำหนดเอง>customers_addr_frontstore|4=จัดส่งพร้อมบิลอื่น|5=ส่งแบบพิเศษ/พรีเมี่ยม

      if (@$sRow->delivery_location == 1) {

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
                                      customers_address_card.tel,
                                      customers_address_card.tel_home,
                                      dataset_provinces.name_th AS provname,
                                      dataset_provinces.id AS province_id,
                                      dataset_amphures.id AS amp_id,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_districts.name_th AS tamname,
                                      dataset_districts.id AS tam_id,
                                      customers.prefix_name,
                                      customers.first_name,
                                      customers.last_name
                                      FROM
                                      customers_address_card
                                      Left Join dataset_provinces ON customers_address_card.card_province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_address_card.card_amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_address_card.card_district_id_fk = dataset_districts.id
                                      Left Join customers ON customers_address_card.customer_id = customers.id
                                      where customers_address_card.customer_id = " . (@$sRow->customers_id_fk ? @$sRow->customers_id_fk : 0) . "

                            ");

        if (@$addr) {


          foreach ($addr as $key => $v) {

            @$address = @$v->card_house_no . " " . @$v->card_house_name . " " . @$v->card_moo . "";
            @$address .= @$v->card_soi . " " . @$v->card_road;
            @$address .= ", ต." . @$v->tamname . " ";
            @$address .= ", อ." . @$v->ampname;
            @$address .= ", จ." . @$v->provname;

            @$recipient_name = @$v->prefix_name . @$v->first_name . ' ' . @$v->last_name;

            if (!empty(@$v->tamname) && !empty(@$v->ampname) && !empty(@$v->provname)) {
            } else {
              @$address = null;
            }

            DB::select(" UPDATE db_delivery
                                            SET
                                            recipient_name = '" . @$recipient_name . "',
                                            addr_send = '" . @$address . "',
                                            postcode = '" . @$v->card_zipcode . "',
                                            province_id_fk = '" . @$v->card_province_id_fk . "',
                                            province_name = '" . @$v->province_name . "',
                                            set_addr_send_this = '1'
                                            where orders_id_fk = '" . $sRow->id . "'

                                           ");
          }


          DB::select("

                              UPDATE db_orders SET
                              house_no='" . @$v->card_house_no . "',
                              house_name='" . @$v->card_house_name . "',
                              moo='" . @$v->card_moo . "',
                              soi='" . @$v->card_soi . "',
                              road='" . @$v->card_road . "',
                              amphures_id_fk='" . (@$v->amp_id ? @$v->amp_id : 0) . "',
                              district_id_fk='" . (@$v->tam_id ? @$v->tam_id : 0) . "',
                              province_id_fk='" . (@$v->province_id ? @$v->province_id : 0) . "',
                              zipcode='" . @$v->card_zipcode . "',
                              tel='" . @$v->tel . "',
                              tel_home='" . @$v->tel_home . "',
                              name='" . @$recipient_name . "'
                              WHERE (id='" . $id . "')");
        }
      }



      if (@$sRow->delivery_location == 2) {

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
                                      dataset_provinces.id AS province_id,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_amphures.id AS amp_id,
                                      dataset_districts.name_th AS tamname,
                                      dataset_districts.id AS tam_id
                                      FROM
                                      customers_detail
                                      Left Join customers ON customers_detail.customer_id = customers.id
                                      Left Join dataset_provinces ON customers_detail.province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_detail.amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_detail.district_id_fk = dataset_districts.id
                                      WHERE customers_detail.customer_id = " . (@$sRow->customers_id_fk ? @$sRow->customers_id_fk : 0) . "

                               ");

        if (@$addr) {
          foreach ($addr as $key => $v) {

            @$address = @$v->house_no . " " . @$v->house_name . " " . @$v->moo . " " . @$v->soi . " " . @$v->road . " ";
            @$address .= ", ต." . @$v->tamname . " ";
            @$address .= ", อ." . @$v->ampname;
            @$address .= ", จ." . @$v->provname;

            if (!empty(@$v->tamname) && !empty(@$v->ampname) && !empty(@$v->provname)) {
            } else {
              @$address = null;
            }

            @$recipient_name = @$v->prefix_name . @$v->first_name . ' ' . @$v->last_name;

            DB::select(" UPDATE db_delivery
                                  SET
                                  recipient_name = '" . @$recipient_name . "',
                                  addr_send = '" . @$address . "',
                                  postcode = '" . @$v->zipcode . "',
                                  mobile = '" . (@$v->tel_mobile ? $v->tel_mobile : '') . "',
                                  tel_home = '" . (@$v->tel_home ? $v->tel_home : '') . "',
                                  province_id_fk = '" . @$v->province_id_fk . "',
                                  province_name = '" . @$v->provname . "',
                                  set_addr_send_this = '1'
                                  where orders_id_fk = '" . $sRow->id . "'

                                 ");
          }

          DB::select("

                              UPDATE db_orders SET
                              house_no='" . @$v->house_no . "',
                              house_name='" . @$v->house_name . "',
                              moo='" . @$v->moo . "',
                              soi='" . @$v->soi . "',
                              road='" . @$v->road . "',
                              amphures_id_fk='" . (@$v->amp_id ? @$v->amp_id : 0) . "',
                              district_id_fk='" . (@$v->tam_id ? @$v->tam_id : 0) . "',
                              province_id_fk='" . (@$v->province_id ? @$v->province_id : 0) . "',
                              zipcode='" . @$v->zipcode . "',
                              tel='" . @$v->tel_mobile . "',
                              tel_home='" . @$v->tel_home . "',
                              name='" . @$recipient_name . "'
                              WHERE (id='" . $id . "')");
        }
      }



      if (@$sRow->delivery_location == 3) {

        $addr = DB::select("select customers_addr_frontstore.* ,dataset_provinces.name_th as provname,
                            dataset_amphures.name_th as ampname,dataset_districts.name_th as tamname,dataset_provinces.id as province_id_fk
                            from customers_addr_frontstore
                            Left Join dataset_provinces ON customers_addr_frontstore.province_id_fk = dataset_provinces.id
                            Left Join dataset_amphures ON customers_addr_frontstore.amphur_code = dataset_amphures.id
                            Left Join dataset_districts ON customers_addr_frontstore.tambon_code = dataset_districts.id
                            WHERE
                            frontstore_id_fk in (" . @$sRow->id . ") ;");

        if (@$addr) {
          foreach ($addr as $key => $v) {

            @$address = @$v->addr_no;
            @$address .= ", ต." . @$v->tamname . " ";
            @$address .= ", อ." . @$v->ampname;
            @$address .= ", จ." . @$v->provname;


            DB::select(" UPDATE db_delivery
                                  SET
                                  recipient_name = '" . @$v->recipient_name . "',
                                  addr_send = '" . @$address . "',
                                  postcode = '" . @$v->zip_code . "',
                                  mobile = '" . (@$v->tel ? $v->tel : '') . "',
                                  tel_home = '" . (@$v->tel_home ? $v->tel_home : '') . "',
                                  province_id_fk = '" . @$v->province_id_fk . "',
                                  province_name = '" . @$v->provname . "',
                                  set_addr_send_this = '1'
                                  where orders_id_fk = '" . $sRow->id . "'

                                 ");
          }

          DB::select("

                              UPDATE db_orders SET
                              house_no='" . @$v->addr_no . "',
                              amphures_id_fk='" . (@$v->amphur_code ? @$v->amphur_code : 0) . "',
                              district_id_fk='" . (@$v->tambon_code ? @$v->tambon_code : 0) . "',
                              province_id_fk='" . (@$v->province_id_fk ? @$v->province_id_fk : 0) . "',
                              zipcode='" . @$v->zip_code . "',
                              tel='" . @$v->tel . "',
                              tel_home='" . @$v->tel_home . "',
                              name='" . @$v->recipient_name . "'
                              WHERE (id='" . $id . "')");
        }
      }

      // $this->fncUpdateDeliveryAddressDefault($id);

    }
  }



  // กรณี เลือก จัดส่งพร้อมบิลอื่น หรือ รับสินค้าด้วยตัวเอง ให้เช็คดูว่า มี ที่อยู่จัดส่ง ปณ. หรือไม่ ถ้ามี เซ็ตเป็นดีฟอลท์ ถ้าไม่มี เช็คต่อ ที่อยู่ตามบัตร ปชช. เช็คต่ออีก ที่อยู่กำหนดเอง ถ้าไม่มีทั้ง 3 แจ้งว่า ไม่ได้ลงทะเบียนที่อยู่ไว้

  public static function fncUpdateDeliveryAddressDefault($id)
  {
    // dd($id);

    $ch = DB::select("

                SELECT  * FROM db_orders
                WHERE id=$id and amphures_id_fk is null and district_id_fk is null and province_id_fk is null

              ");


    if (!empty($ch)) {

      //delivery_location = ที่อยู่ผู้รับ>0=รับสินค้าด้วยตัวเอง|1=ที่อยู่ตามบัตร ปชช.>customers_address_card|2=ที่อยู่จัดส่งไปรษณีย์หรือที่อยู่ตามที่ลงทะเบียนไว้ในระบบ>customers_detail|3=ที่อยู่กำหนดเอง>customers_addr_frontstore|4=จัดส่งพร้อมบิลอื่น|5=ส่งแบบพิเศษ/พรีเมี่ยม

      $sRow = \App\Models\Backend\Frontstore::find($id);

      $delivery_location_01 = DB::select(" SELECT
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
                                      customers_address_card.tel,
                                      customers_address_card.tel_home,
                                      dataset_provinces.name_th AS provname,
                                      dataset_provinces.id AS province_id,
                                      dataset_amphures.id AS amp_id,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_districts.name_th AS tamname,
                                      dataset_districts.id AS tam_id,
                                      customers.prefix_name,
                                      customers.first_name,
                                      customers.last_name
                                      FROM
                                      customers_address_card
                                      Left Join dataset_provinces ON customers_address_card.card_province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_address_card.card_amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_address_card.card_district_id_fk = dataset_districts.id
                                      Left Join customers ON customers_address_card.customer_id = customers.id
                                      where customers_address_card.customer_id = " . (@$sRow->customers_id_fk ? @$sRow->customers_id_fk : 0) . "

                            ");

      if (!empty($delivery_location_01)) {


        foreach ($delivery_location_01 as $key => $v) {

          @$address = @$v->card_house_no . " " . @$v->card_house_name . " " . @$v->card_moo . "";
          @$address .= @$v->card_soi . " " . @$v->card_road;
          @$address .= ", ต." . @$v->tamname . " ";
          @$address .= ", อ." . @$v->ampname;
          @$address .= ", จ." . @$v->provname;

          @$recipient_name = @$v->prefix_name . @$v->first_name . ' ' . @$v->last_name;

          if (!empty(@$v->tamname) && !empty(@$v->ampname) && !empty(@$v->provname)) {
          } else {
            @$address = null;
          }

          DB::select(" UPDATE db_delivery
                                          SET
                                          recipient_name = '" . @$recipient_name . "',
                                          addr_send = '" . @$address . "',
                                          postcode = '" . @$v->card_zipcode . "',
                                          province_id_fk = '" . @$v->card_province_id_fk . "',
                                          province_name = '" . @$v->province_name . "',
                                          set_addr_send_this = '1'
                                          where orders_id_fk = '" . $sRow->id . "'

                                         ");


          DB::select("

                                            UPDATE db_orders SET
                                            house_no='" . @$v->card_house_no . "',
                                            house_name='" . @$v->card_house_name . "',
                                            moo='" . @$v->card_moo . "',
                                            soi='" . @$v->card_soi . "',
                                            road='" . @$v->card_road . "',
                                            amphures_id_fk='" . (@$v->amp_id ? @$v->amp_id : 0) . "',
                                            district_id_fk='" . (@$v->tam_id ? @$v->tam_id : 0) . "',
                                            province_id_fk='" . (@$v->province_id ? @$v->province_id : 0) . "',
                                            zipcode='" . @$v->card_zipcode . "',
                                            tel='" . @$v->tel . "',
                                            tel_home='" . @$v->tel_home . "',
                                            name='" . @$recipient_name . "'
                                            WHERE (id='" . $id . "')");
        }
      }




      $delivery_location_02 = DB::select("
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
                                      dataset_provinces.id AS province_id,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_amphures.id AS amp_id,
                                      dataset_districts.name_th AS tamname,
                                      dataset_districts.id AS tam_id
                                      FROM
                                      customers_detail
                                      Left Join customers ON customers_detail.customer_id = customers.id
                                      Left Join dataset_provinces ON customers_detail.province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_detail.amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_detail.district_id_fk = dataset_districts.id
                                      WHERE customers_detail.customer_id = " . (@$sRow->customers_id_fk ? @$sRow->customers_id_fk : 0) . "

                               ");

      if (@$delivery_location_02) {
        foreach ($delivery_location_02 as $key => $v) {

          @$address = @$v->house_no . " " . @$v->house_name . " " . @$v->moo . " " . @$v->soi . " " . @$v->road . " ";
          @$address .= ", ต." . @$v->tamname . " ";
          @$address .= ", อ." . @$v->ampname;
          @$address .= ", จ." . @$v->provname;

          if (!empty(@$v->tamname) && !empty(@$v->ampname) && !empty(@$v->provname)) {
          } else {
            @$address = null;
          }

          @$recipient_name = @$v->prefix_name . @$v->first_name . ' ' . @$v->last_name;

          DB::select(" UPDATE db_delivery
                                  SET
                                  recipient_name = '" . @$recipient_name . "',
                                  addr_send = '" . @$address . "',
                                  postcode = '" . @$v->zipcode . "',
                                  mobile = '" . (@$v->tel_mobile ? $v->tel_mobile : '') . "',
                                  tel_home = '" . (@$v->tel_home ? $v->tel_home : '') . "',
                                  province_id_fk = '" . @$v->province_id_fk . "',
                                  province_name = '" . @$v->provname . "',
                                  set_addr_send_this = '1'
                                  where orders_id_fk = '" . $sRow->id . "'

                                 ");


          DB::select("

                                  UPDATE db_orders SET
                                  house_no='" . @$v->house_no . "',
                                  house_name='" . @$v->house_name . "',
                                  moo='" . @$v->moo . "',
                                  soi='" . @$v->soi . "',
                                  road='" . @$v->road . "',
                                  amphures_id_fk='" . (@$v->amp_id ? @$v->amp_id : 0) . "',
                                  district_id_fk='" . (@$v->tam_id ? @$v->tam_id : 0) . "',
                                  province_id_fk='" . (@$v->province_id ? @$v->province_id : 0) . "',
                                  zipcode='" . @$v->zipcode . "',
                                  tel='" . @$v->tel_mobile . "',
                                  tel_home='" . @$v->tel_home . "',
                                  name='" . @$recipient_name . "'
                                  WHERE (id='" . $id . "')");
        }
      }


      $delivery_location_03 = DB::select("select customers_addr_frontstore.* ,dataset_provinces.name_th as provname,
                            dataset_amphures.name_th as ampname,dataset_districts.name_th as tamname,dataset_provinces.id as province_id_fk
                            from customers_addr_frontstore
                            Left Join dataset_provinces ON customers_addr_frontstore.province_id_fk = dataset_provinces.id
                            Left Join dataset_amphures ON customers_addr_frontstore.amphur_code = dataset_amphures.id
                            Left Join dataset_districts ON customers_addr_frontstore.tambon_code = dataset_districts.id
                            WHERE
                            frontstore_id_fk in (" . @$sRow->id . ") ;");

      if (@$delivery_location_03) {
        foreach ($delivery_location_03 as $key => $v) {

          @$address = @$v->addr_no;
          @$address .= ", ต." . @$v->tamname . " ";
          @$address .= ", อ." . @$v->ampname;
          @$address .= ", จ." . @$v->provname;


          DB::select(" UPDATE db_delivery
                                  SET
                                  recipient_name = '" . @$v->recipient_name . "',
                                  addr_send = '" . @$address . "',
                                  postcode = '" . @$v->zip_code . "',
                                  mobile = '" . (@$v->tel ? $v->tel : '') . "',
                                  tel_home = '" . (@$v->tel_home ? $v->tel_home : '') . "',
                                  province_id_fk = '" . @$v->province_id_fk . "',
                                  province_name = '" . @$v->provname . "',
                                  set_addr_send_this = '1'
                                  where orders_id_fk = '" . $sRow->id . "'

                                 ");

          DB::select("

                                  UPDATE db_orders SET
                                  house_no='" . @$v->addr_no . "',
                                  amphures_id_fk='" . (@$v->amphur_code ? @$v->amphur_code : 0) . "',
                                  district_id_fk='" . (@$v->tambon_code ? @$v->tambon_code : 0) . "',
                                  province_id_fk='" . (@$v->province_id_fk ? @$v->province_id_fk : 0) . "',
                                  zipcode='" . @$v->zip_code . "',
                                  tel='" . @$v->tel . "',
                                  tel_home='" . @$v->tel_home . "',
                                  name='" . @$v->recipient_name . "'
                                  WHERE (id='" . $id . "')");
        }
      }
    }
  }


  public function form($id = NULL)
  {
    \DB::beginTransaction();
    try {
      if ($id) {
        $sRow = \App\Models\Backend\Frontstore::find($id);
        // $invoice_code = $sRow->invoice_code;

        // $sRow->cash_price    = str_replace(',','',request('cash_price')) ;
        // $sRow->fee_amt    = str_replace(',','',request('fee_amt')) ;
        // $sRow->shipping_price    = str_replace(',','',request('shipping_price')) ;


      } else {
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

      $data_customer = DB::table('customers')
      ->select('user_name','first_name','last_name','business_name')
      ->where('id','=',request('customers_id_fk'))
      ->first();
      $sRow->user_name = $data_customer->user_name;
      $sRow->name_customer = $data_customer->first_name.' '.$data_customer->last_name;
      $sRow->name_customer_business = $data_customer->business_name;

      if(request('date_create')){
        if(date('Y-m-d', strtotime(request('date_create'))) == date('Y-m-d') || date('Y-m-d', strtotime(request('date_create'))) > date('Y-m-d')){
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->action_date = date('Y-m-d');
          // $code_order = RunNumberPayment::run_number_order($Branchs->business_location_id_fk);
          $x = 'start';
          $code_order = "";
          while ($x == 'start') {
            $code_order = RunNumberPayment::run_number_order($Branchs->business_location_id_fk);
            $code_order_check = DB::table('db_orders')->where('code_order',$code_order)->first();
            if(!$code_order_check){
              $code_order = $code_order;
              $x = 'end';
            }
          }
        }else{
          $sRow->created_at = request('date_create').' 21:30:00';
          $sRow->action_date = request('date_create');
          // $code_order = RunNumberPayment::run_number_order($Branchs->business_location_id_fk,request('date_create'));
          $x = 'start';
          $code_order = "";
          while ($x == 'start') {
            $code_order = RunNumberPayment::run_number_order($Branchs->business_location_id_fk,request('date_create'));
            $code_order_check = DB::table('db_orders')->where('code_order',$code_order)->first();
            if(!$code_order_check){
              $code_order = $code_order;
              $x = 'end';
            }
          }

        }
      }else{
        $sRow->created_at = date('Y-m-d H:i:s');
        $sRow->action_date = date('Y-m-d');
        // $code_order = RunNumberPayment::run_number_order($Branchs->business_location_id_fk);
        $x = 'start';
        $code_order = "";
        while ($x == 'start') {
          $code_order = RunNumberPayment::run_number_order($Branchs->business_location_id_fk);
          $code_order_check = DB::table('db_orders')->where('code_order',$code_order)->first();
          if(!$code_order_check){
            $code_order = $code_order;
            $x = 'end';
          }
        }

      }


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
      //   if($sRow->check_press_save==2 && $sRow->approve_status>0 && $sRow->code_order==""){

      //    $branchs = DB::select("SELECT * FROM branchs where id=".$request->this_branch_id_fk."");
      //   DB::select(" UPDATE `db_orders` SET date_setting_code='".date('ym')."' WHERE (`id`=".$sRow->id.") ");

      if($id){
        // ไม่ทำอะไร
      }else{
        $sRow->code_order = $code_order;
        $sRow->date_setting_code    = date('ym');
      }

      //   DB::select(" UPDATE `db_orders` SET `code_order`='$code_order' WHERE (`id`=".$sRow->id.") ");

      // }
      $sRow->save();

      DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");
      DB::select(" UPDATE db_orders SET pv_total=0 WHERE pv_total is null; ");
      // return "to this";
      \DB::commit();

      return redirect()->to(url("backend/frontstore/" . $sRow->id . "/edit"));
      // return redirect()->to(url("backend/frontstore"));


    } catch (\Exception $e) {
      echo $e->getMessage();
      \DB::rollback();
      return redirect()->action('backend\FrontstoreController@index')->with(['alert' => \App\Models\Alert::e($e)]);
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

    $user_login_id = \Auth::user()->id;
    $sPermission = \Auth::user()->permission;
    if ($sPermission == 1) {
      $action_user_011 = "";
      $action_user_012 = "";
    } else {

      if (\Auth::user()->position_level == '3' || \Auth::user()->position_level == '4') {
        $action_user_011 = "";
        $action_user_012 = "";
      } else {
        $action_user_011 = " AND db_orders.action_user = $user_login_id ";
        $action_user_012 = " AND db_add_ai_cash.action_user = $user_login_id ";
      }

      $role_check = DB::table('role_group')->select('acc_status')->where('id',\Auth::user()->role_group_id_fk)->first();
      if(@$role_check->acc_status==1){
        $action_user_011 = "";
        $action_user_012 = "";
      }

    }

    if (!empty($req->startDate)) {
      $startDate1 = " AND DATE(db_orders.created_at) >= '" . $req->startDate . "' ";
      $startDate2 = " AND DATE(db_add_ai_cash.created_at) >= '" . $req->startDate . "' ";
      $startDate3 = date("d-m-Y", strtotime($req->startDate));
      $sD3 = $startDate3;
    } else {
      $startDate1 = " AND DATE(db_orders.created_at) >= CURDATE() ";
      $startDate2 = " AND DATE(db_add_ai_cash.created_at) >= CURDATE() ";
      $startDate3 = date("d-m-Y");
      $sD3 = date("d-m-Y");
    }

    if (!empty($req->endDate)) {
      $endDate1 = " AND DATE(db_orders.created_at) <= '" . $req->endDate . "' ";
      $endDate2 = " AND DATE(db_add_ai_cash.created_at) <= '" . $req->endDate . "' ";
      $endDate3 = date("d-m-Y", strtotime($req->endDate));
      $eD3 = " To " . $endDate3;
    } else {
      $endDate1 = "";
      $endDate2 = "";
      $endDate3 = date("Y-m-d");
      $eD3 = "";
    }

    $sD3 = $sD3 . $eD3;


    if (!empty($req->invoice_code)) {
      if(count($req->invoice_code) > 0){
        $or_str = "";
        foreach($req->invoice_code as $key => $or){
          if($key+1 == count($req->invoice_code)){
            $or_str.= "'".$or."'";
          }else{
            $or_str.= "'".$or."'".',';
          }

        }
        $invoice_code = " AND db_orders.code_order IN (".$or_str.") ";
        $invoice_code2 = " AND db_add_ai_cash.code_order IN (".$or_str.") ";
    }

    } else {
      $invoice_code = "";
      $invoice_code2 = "";
    }

    if (!empty($req->purchase_type_id_fk)) {
      $purchase_type_id_fk = " AND db_orders.purchase_type_id_fk = '" . $req->purchase_type_id_fk . "' ";
      if ($req->purchase_type_id_fk == 4) {
        $purchase_type_id_fk_02 = "";
      } else {
        $purchase_type_id_fk_02 = " AND db_add_ai_cash.id=0 ";
      }
    } else {
      $purchase_type_id_fk = "";
      $purchase_type_id_fk_02 = "";
    }

    if (!empty($req->customer_username)) {
      $customer_username = " AND db_orders.customers_id_fk = '" . $req->customer_username . "' ";
      $customer_username_02 = " AND db_add_ai_cash.customer_id_fk = '" . $req->customer_username . "' ";
    } else {
      $customer_username = "";
      $customer_username_02 = "";
    }

    if (!empty($req->customer_name)) {
      $customer_name = " AND db_orders.customers_id_fk = '" . $req->customer_name . "' ";
      $customer_name_02 = " AND db_add_ai_cash.customer_id_fk = '" . $req->customer_name . "' ";
    } else {
      $customer_name = "";
      $customer_name_02 = "";
    }

    if (!empty($req->action_user)) {
      if($req->action_user=='v3'){
        $action_user_02 = " AND db_orders.action_user = '" . 0 . "' ";
        $action_user_022 = " AND db_add_ai_cash.action_user = '" . 0 . "' ";
      }else{
        $action_user_02 = " AND db_orders.action_user = '" . $req->action_user . "' ";
        $action_user_022 = " AND db_add_ai_cash.action_user = '" . $req->action_user . "' ";
      }

    } else {
      $action_user_02 = "";
      $action_user_022 = "";
    }

    if (isset($req->status_sent_money)) {
      $status_sent_money = " AND db_orders.status_sent_money = " . $req->status_sent_money . " ";
      $status_sent_money_02 = " AND db_add_ai_cash.status_sent_money = " . $req->status_sent_money . " ";
    } else {
      $status_sent_money = "";
      $status_sent_money_02 = "";
    }

    if (isset($req->approve_status)) {
      if ($req->approve_status == 7) {
        $approve_status = " AND db_orders.approve_status = 0 ";
        if (!empty($req->startDate)) {
        } else {
          $startDate = '';
          $endDate = '';
          $startDate2 = '';
          $endDate2 = '';
        }
      } else {
        $approve_status = " AND db_orders.approve_status = " . $req->approve_status . " ";
      }
      $approve_status_02 = " AND db_add_ai_cash.approve_status = " . $req->approve_status . " ";
    } else {
      $approve_status = "";
      $approve_status_02 = "";
    }


    if (isset($req->viewcondition)) {
      if (isset($req->viewcondition) && $req->viewcondition == "ViewBuyNormal") {
        $viewcondition_01 = ' and db_orders.purchase_type_id_fk not in (4,5) ';
        $viewcondition_02 = ' and db_add_ai_cash.id=0 ';
      } else if (isset($req->viewcondition) && $req->viewcondition == "ViewBuyVoucher") {
        $viewcondition_01 = ' and db_orders.purchase_type_id_fk in (4,5) ';
        $viewcondition_02 = '';
      } else {
        $viewcondition_01 = '';
        $viewcondition_02 = '';
      }
    } else {
      $viewcondition_01 = '';
      $viewcondition_02 = '';
    }

    if (!empty($req->business_location_id_fk)) {
      $business_location_id_fk = " AND db_orders.business_location_id_fk = " . $req->business_location_id_fk . " ";
      $business_location_id_fk_ai = " AND db_add_ai_cash.business_location_id_fk = " . $req->business_location_id_fk . " ";
    }else{
      $business_location_id_fk = " AND db_orders.business_location_id_fk = '" . (\Auth::user()->business_location_id_fk) . "' ";
      $business_location_id_fk_ai = " AND db_add_ai_cash.business_location_id_fk = '" . (\Auth::user()->business_location_id_fk) . "' ";
    }

    // วุฒิเปลี่ยน db_orders.credit_price เป็น db_orders.sum_credit_price
    $sDBFrontstoreSumCostActionUser = DB::select("
                SELECT
                db_orders.action_user,
                ck_users_admin.`name` as action_user_name,
                db_orders.pay_type_id_fk,
                dataset_pay_type.detail AS pay_type,
                date(db_orders.action_date) AS action_date,

                SUM(CASE WHEN db_orders.sum_credit_price is null THEN 0 ELSE db_orders.sum_credit_price END) AS credit_price,
                SUM(CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) AS transfer_price,
                SUM(CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) AS fee_amt,
                SUM(CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) AS aicash_price,
                SUM(CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) AS cash_pay,
                SUM(CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END) AS gift_voucher_price,
                SUM(CASE WHEN db_orders.true_money_price is null THEN 0 ELSE db_orders.true_money_price END) AS true_money_price,
                SUM(CASE WHEN db_orders.prompt_pay_price is null THEN 0 ELSE db_orders.prompt_pay_price END) AS prompt_pay_price,
                SUM(
                (CASE WHEN db_orders.sum_credit_price is null THEN 0 ELSE db_orders.sum_credit_price END) +
                (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
                -- (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
                (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
                (CASE WHEN db_orders.true_money_price is null THEN 0 ELSE db_orders.true_money_price END) +
                (CASE WHEN db_orders.prompt_pay_price is null THEN 0 ELSE db_orders.prompt_pay_price END) +
                (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END)
                -- (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
                ) as total_price,

                SUM(
                 CASE WHEN db_orders.shipping_free = 1 THEN 0 ELSE db_orders.shipping_price END
                ) AS shipping_price

                FROM
                db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                WHERE db_orders.approve_status not in (5,6,0) AND db_orders.check_press_save=2
                $action_user_011
                $startDate1
                $endDate1
                $invoice_code
                $purchase_type_id_fk
                $customer_username
                $customer_name
                $action_user_02
                $status_sent_money
                $approve_status
                $viewcondition_01
                $business_location_id_fk

                GROUP BY action_user
        ");
    $str_ch = $action_user_011 . $startDate1 . $endDate1 . $invoice_code;
    $show = '';
    $show .=
      '
          <div class="table-responsive">
            <table class="table table-sm m-0">
              <thead>
                <tr style="background-color: #f2f2f2;"><th colspan="8">
                  ' . trans('message.all_payment_list') . ' (' . $sD3 . ') (<font color=red>ไม่รวมบิล * รอดำเนินการต่อ และ ไม่รวมบิลที่ ยกเลิก </font>)
                </th></tr>
                <tr>
                  <th width="10%">' . trans('message.seller') . '</th>
                  <th width="5%" class="text-right">' . trans('message.cash') . '</th>
                  <th width="5%" class="text-right">' . trans('message.ai_cash') . '</th>
                  <th width="5%" class="text-right">' . trans('message.transfer_cash') . '</th>
                  <th width="5%" class="text-right">' . trans('message.credit_card') . '</th>
                  <th width="5%" class="text-right">TrueMoney</th>
                  <th width="5%" class="text-right">PromptPay</th>
                  <th width="10%" class="text-right">' . trans('message.total') . '</th>
                  <th width="5%" class="text-right">(' . trans('message.fee') . ')</th>
                  <th width="5%" class="text-right">(' . trans('message.transportor_fee') . ')</th>
                </tr>
              </thead>
              <tbody>';
    if (@$sDBFrontstoreSumCostActionUser) {
      foreach (@$sDBFrontstoreSumCostActionUser as $r) {
        @$cnt_row1 += 1;
        if($r->action_user_name == ''){
          $r->action_user_name = 'V3';
        }

        $show .= '
                    <tr>
                      <td>' . $r->action_user_name . '</td>
                      <td class="text-right"> ' . number_format($r->cash_pay, 2) . ' </td>
                      <td class="text-right"> ' . number_format($r->aicash_price, 2) . ' </td>
                      <td class="text-right"> ' . number_format($r->transfer_price, 2) . ' </td>
                      <td class="text-right"> ' . number_format($r->credit_price, 2) . ' </td>
                      <td class="text-right"> ' . number_format($r->true_money_price, 2) . ' </td>
                      <td class="text-right"> ' . number_format($r->prompt_pay_price, 2) . ' </td>
                      <th class="text-right"> ' . number_format($r->total_price, 2) . ' </th>
                      <td class="text-right"> ' . number_format($r->fee_amt, 2) . ' </td>
                      <td class="text-right"> ' . number_format($r->shipping_price, 2) . ' </td>
                    </tr>';
      }
    }

// วุฒิบวกค่าธรรมเนียม
// วุฒิเพิ่มค่าธรรมเนียมไว้หักล้าง
// (CASE WHEN db_orders.charger_type = 2 THEN 0 WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END)
      $sDBFrontstoreTOTAL = DB::select("
                SELECT
                SUM(CASE WHEN db_orders.sum_credit_price is null THEN 0 ELSE db_orders.sum_credit_price END) AS sum_credit_price,
                SUM(CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) AS transfer_price,
                SUM(CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) AS fee_amt,
                SUM(CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) AS aicash_price,
                SUM(CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) AS cash_pay,
                SUM(CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END) AS gift_voucher_price,
                SUM(CASE WHEN db_orders.true_money_price is null THEN 0 ELSE db_orders.true_money_price END) AS true_money_price,
                SUM(CASE WHEN db_orders.prompt_pay_price is null THEN 0 ELSE db_orders.prompt_pay_price END) AS prompt_pay_price,
                SUM(CASE WHEN db_orders.charger_type = 2 THEN 0 ELSE db_orders.fee_amt END) AS fee_amt_charger_in,

                db_orders.code_order,

                SUM(
                (CASE WHEN db_orders.sum_credit_price is null THEN 0 ELSE db_orders.sum_credit_price END) +
                (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
                /*  (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +  */
                (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
                (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END)   /* + */ +
                (CASE WHEN db_orders.true_money_price is null THEN 0 ELSE db_orders.true_money_price END)   /* + */ +
                (CASE WHEN db_orders.prompt_pay_price is null THEN 0 ELSE db_orders.prompt_pay_price END)   /* + */

                /* (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END) */
                ) as total_price,

                SUM(
                 CASE WHEN db_orders.shipping_price is null THEN 0 ELSE db_orders.shipping_price END
                ) AS shipping_price

                FROM
                db_orders
                WHERE 1
                AND approve_status <> 5
                AND approve_status <> 6
                AND approve_status <> 0
                $action_user_011
                $startDate1
                $endDate1
                $invoice_code
                $purchase_type_id_fk
                $customer_username
                $customer_name
                $action_user_02
                $status_sent_money
                $approve_status
                $viewcondition_01
                $business_location_id_fk
        ");

  //  วุฒิเพิ่มมา + $sDBFrontstoreTOTAL[0]->fee_amt วุฒิบวกค่าธรรมเนียม
      $show .= '
                    <tr>
                      <th>Total > </th>
                      <th class="text-right"> ' . number_format($sDBFrontstoreTOTAL[0]->cash_pay, 2) . ' </th>
                      <th class="text-right"> ' . number_format($sDBFrontstoreTOTAL[0]->aicash_price, 2) . ' </th>
                      <th class="text-right"> ' . number_format($sDBFrontstoreTOTAL[0]->transfer_price, 2) . ' </th>
                      <th class="text-right"> ' . number_format($sDBFrontstoreTOTAL[0]->sum_credit_price, 2) . ' </th>
                      <td class="text-right"> ' . number_format($sDBFrontstoreTOTAL[0]->true_money_price, 2) . ' </td>
                      <td class="text-right"> ' . number_format($sDBFrontstoreTOTAL[0]->prompt_pay_price, 2) . ' </td>
                      <th class="text-right"> ' . number_format($sDBFrontstoreTOTAL[0]->total_price, 2) . ' </th>
                      <th class="text-right"> ' . number_format($sDBFrontstoreTOTAL[0]->fee_amt, 2) . ' </th>
                      <th class="text-right"> ' . number_format($sDBFrontstoreTOTAL[0]->shipping_price, 2) . ' </th>
                    </tr>';
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
                ' . trans('message.payment_ai_cash_list') . ' (' . $sD3 . ') <a href="' . url('backend/add_ai_cash') . '" class="btn btn-success btn-sm"><i class="bx bx-search align-middle"></i> ดูรายละเอียดเติม Ai-Cash</a>
                </th> </tr>
                <tr>
                  <th width="10%">' . trans('message.seller') . '</th>
                  <th width="5%" class="text-right"> </th>
                  <th width="5%" class="text-right"> </th>
                  <th width="5%" class="text-right"> </th>
                  <th width="5%" class="text-right"> </th>
                  <th width="5%" class="text-right">' . trans('message.list') . '</th>
                  <th width="10%" class="text-right">' . trans('message.total') . '</th>
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
              $customer_username_02
              $customer_name_02
              $action_user_022
              $status_sent_money_02
              $approve_status_02
              $viewcondition_02
              $business_location_id_fk_ai

              GROUP BY action_user

        ");

    if (@$sDBFrontstoreUserAddAiCash) {

      foreach (@$sDBFrontstoreUserAddAiCash as $r) {

        @$cnt_row2 += 1;
        @$cnt_aicash_amt += 1;
        @$sum_cnt += $r->cnt;
        @$sum_sum += $r->sum;

        if($r->name == ''){
          $r->name = 'V3';
        }

        $show .= '
                    <tr>
                      <td>' . $r->name . '</td>
                       <td class="text-right"> </td>
                       <td class="text-right"> </td>
                       <td class="text-right"> </td>
                       <td class="text-right"> </td>
                      <td class="text-right">' . @$r->cnt . '</td>
                      <th class="text-right"> ' . number_format($r->sum, 2) . ' </th>
                      <td class="text-right"> </td>
                    </tr>';
      }
    }

    if (@$cnt_row2 > 1) {

      $show .= '
                    <tr>
                      <th>Total > </th>
                      <th> </th>
                      <th> </th>
                      <th> </th>
                      <th> </th>
                      <th class="text-right"> ' . @$sum_cnt . ' </th>
                      <th class="text-right"> ' . number_format(@$sum_sum, 2) . ' </th>
                      <th class="text-right"> </th>
                    </tr>';
    }

    $show .= '
                     </tbody>
                   </table>
                 </div>
              <br>
           ';

    if ($sPermission == 1) {
      $action_user_0111 = "";
    } else {

      $action_user_0111 = " AND db_orders.action_user = $user_login_id ";
    }

    $ch_user = DB::select("
                SELECT pay_type_id_fk,
                action_user,
                pay_type_id_fk
                FROM
                db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                WHERE db_orders.pay_type_id_fk<>0
                $action_user_0111

        ");

    if ($sPermission == 1 || count($ch_user) > 0) {
      $show_tb_sent_money = "";
      $user_login_id = \Auth::user()->id;
    } else {
      $show_tb_sent_money = "display:none;";
    }
    // test_clear_sent_money
    $show .= '
                <div id="tb_sent_money" class="table-responsive" style=' . $show_tb_sent_money . '>
                  <table class="table table-sm m-0">
                    <thead>
                      <tr style="background-color: #f2f2f2;"><th colspan="8">
                        <span class="" >' . trans('message.daily_cash_list') . ' (' . trans('message.current_day') . ' : ' . date("d-m-Y") . $user_login_id . ') </span>
                        </th></tr>
                        <tr>
                          <th class="text-center">' . trans('message.times') . '</th>
                          <th class="text-center">' . trans('message.send_reciept_list') . '</th>
                          <th class="text-center">' . trans('message.sender') . '</th>
                          <th class="text-center">' . trans('message.send_time') . '</th>
                          <th class="text-center"> Status </th>
                          <th class="text-center"> remark </th>
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

    if (@$sDBSentMoneyDaily) {
      $tt = 1;
      $sDBSentMoneyDaily02 = DB::select("
                                    SELECT
                                    db_sent_money_daily.id,
                                    ck_users_admin.`name` as sender
                                    FROM
                                    db_sent_money_daily
                                    Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
                                    WHERE date(db_sent_money_daily.updated_at)=CURDATE() and sender_id=$user_login_id
                                    AND status_cancel=0
                                    order by db_sent_money_daily.time_sent
                              ");

              foreach (@$sDBSentMoneyDaily as $key1 => $r) {
                $order_ex = $r->orders_ids;
                $sOrders = DB::select("
                SELECT db_orders.code_order ,customers.prefix_name,customers.first_name,customers.last_name
                            FROM
                            db_orders Left Join customers ON db_orders.customers_id_fk = customers.id
                            where db_orders.id in (" . $order_ex . ") AND code_order<>'' AND action_user='$user_login_id' ;
                ");
                $show .= '
                        <tr>
                          <td class="text-center">' . $tt . '</td>';

        if (@$r->status_cancel == 0) {

          $show .= '
                          <td class="text-center">
                            <div class="invoice_code_list" data-toggle="tooltip" data-placement="bottom" title="คลิ้กเพื่อดูใบเสร็จทั้งหมด" >';

          $i = 1;
          foreach ($sOrders as $key => $value) {
            $show .=  $value->code_order . "<br>";
            $i++;
            if ($i == 4) {
              break;
            }
          }
          if ($i > 3) $show .= "...";
          $arr = [];
          foreach ($sOrders as $key => $value) {
            array_push($arr, $value->code_order . ' :' . (@$value->first_name . ' ' . @$value->last_name) . '<br>');
          }
          $arr_inv = implode(",", $arr);

          $show .= '
                            </div>
                            <input type="hidden" class="arr_inv" value="' . $arr_inv . '">
                          </td>';
        } else {

          $show .= '
                          <td class="text-left" style="color:red;">
                            * รายการนี้ได้ทำการยกเลิกการส่งเงิน
                          </td>';
        }

        if(@$r->status_approve == 1){
            $status_approve = '<label style="color:green;">อนุมติ</label>';
        }elseif(@$r->status_approve == 2){
          $status_approve = '<label style="color:red;">ไม่อนุมัติ</label>';
        }else{
          $status_approve = '<label style="color:black;">รอดำเนินการ</label>';
        }

        if(@$r->status_cancel == 1){
          $status_approve = '<label style="color:red;">ยกเลิกรายการ</label>';
        }

          $approver = DB::table('ck_users_admin')->where('id',$r->approver)->first();
          if($approver){
            $approver = $approver->name;
          }else{
            $approver = "";
          }

        $show .= '
                          <td class="text-center">' . @$r->sender . '</td>
                          <td class="text-center">' . @$r->created_at . '<br> วันเวลาที่รับเงิน : '.@$r->approve_date.' <br> ผู้รับเงิน : '.$approver.' </td>
                          <td class="text-center">' . $status_approve . '</td>
                          <td class="text-center">' . @$r->remark . '</td>
                          <td class="text-center">';

        if (@$r->status_approve == 0 || @$r->status_approve == 2) {
          if (@$r->status_cancel == 0 ) {
            $show .= '
                            <a href="javascript: void(0);" class="btn btn-sm btn-danger btnCancelSentMoney " data_type="sale" data-id="' . @$r->id . '" > ยกเลิก </a>';
          }
        } else {
          echo "-";
        }

        $show .= '
                          </td>
                        </tr>';

        $tt++;
      }
    }

    $show .= '<tr><td colspan="7"><b>Ai-cash</b></td></tr>';

    // วุฒิเพิ่ม ai cash
                    $sDBSentMoneyDaily_ai = DB::select("
                    SELECT
                    db_sent_money_daily_ai.*,
                    ck_users_admin.`name` as sender
                    FROM
                    db_sent_money_daily_ai
                    Left Join ck_users_admin ON db_sent_money_daily_ai.sender_id = ck_users_admin.id
                    WHERE date(db_sent_money_daily_ai.updated_at)=CURDATE() and sender_id=$user_login_id
                    order by db_sent_money_daily_ai.time_sent
                ");

                if (@$sDBSentMoneyDaily_ai) {
                $tt = 1;

                $sDBSentMoneyDaily02 = DB::select("
                                          SELECT
                                          db_sent_money_daily_ai.id,
                                          ck_users_admin.`name` as sender
                                          FROM
                                          db_sent_money_daily_ai
                                          Left Join ck_users_admin ON db_sent_money_daily_ai.sender_id = ck_users_admin.id
                                          WHERE date(db_sent_money_daily_ai.updated_at)=CURDATE() and sender_id=$user_login_id
                                          AND status_cancel=0
                                          order by db_sent_money_daily_ai.time_sent
                                    ");

                foreach (@$sDBSentMoneyDaily_ai as $key1 => $r) {
                    $order_ex = $r->add_ai_ids;
                      $sOrders = DB::select("
                      SELECT db_add_ai_cash.code_order ,customers.prefix_name,customers.first_name,customers.last_name
                                FROM
                                db_add_ai_cash Left Join customers ON db_add_ai_cash.customer_id_fk = customers.id
                                where db_add_ai_cash.id in (" . $order_ex . ") AND code_order<>'' AND action_user='$user_login_id' ;
                      ");
                $show .= '
                              <tr>
                                <td class="text-center">' . $tt . '</td>';
                if (@$r->status_cancel == 0) {

                $show .= '
                                <td class="text-center">
                                  <div class="invoice_code_list" data-toggle="tooltip" data-placement="bottom" title="คลิ้กเพื่อดูใบเสร็จทั้งหมด" >';

                $i = 1;
                foreach ($sOrders as $key => $value) {
                  $show .=  $value->code_order . "<br>";
                  $i++;
                  if ($i == 4) {
                    break;
                  }
                }
                if ($i > 3) $show .= "...";
                $arr = [];
                foreach ($sOrders as $key => $value) {
                  array_push($arr, $value->code_order . ' :' . (@$value->first_name . ' ' . @$value->last_name) . '<br>');
                }
                $arr_inv = implode(",", $arr);

                $show .= '
                                  </div>
                                  <input type="hidden" class="arr_inv" value="' . $arr_inv . '">
                                </td>';
                } else {

                $show .= '
                                <td class="text-left" style="color:red;">
                                  * รายการนี้ได้ทำการยกเลิกการส่งเงิน
                                </td>';
                }

                if(@$r->status_approve == 1){
                  $status_approve = '<label style="color:green;">อนุมติ</label>';
                }elseif(@$r->status_approve == 2){
                $status_approve = '<label style="color:red;">ไม่อนุมัติ</label>';
                }else{
                $status_approve = '<label style="color:black;">รอดำเนินการ</label>';
                }

                if(@$r->status_cancel == 1){
                $status_approve = '<label style="color:red;">ยกเลิกรายการ</label>';
                }

                $approver = DB::table('ck_users_admin')->where('id',$r->approver)->first();
                if($approver){
                  $approver = $approver->name;
                }else{
                  $approver = "";
                }

                $show .= '
                                <td class="text-center">' . @$r->sender . '</td>
                                <td class="text-center">' . @$r->created_at . '<br> วันเวลาที่รับเงิน : '.@$r->approve_date.' <br> ผู้รับเงิน : '.$approver.' </td>
                                <td class="text-center">' . $status_approve . '</td>
                                <td class="text-center">' . @$r->remark . '</td>
                                <td class="text-center">';

                if (@$r->status_approve == 0 || @$r->status_approve == 2) {
                if (@$r->status_cancel == 0 ) {
                  $show .= '
                                  <a href="javascript: void(0);" class="btn btn-sm btn-danger btnCancelSentMoney " data_type="aicash" data-id="' . @$r->id . '" > ยกเลิก </a>';
                }
                } else {
                echo "-";
                }

                $show .= '
                                </td>
                              </tr>';

                $tt++;
                }
                }
    $show .= '
                        <tr>
                          <td class="text-center">  </td>
                          <td class="text-left">  </td>
                          <td class="text-center">  </td>
                          <td class="text-center">  </td>
                          <td class="text-center">
                            <a href="javascript: void(0);" class="btn btn-sm btn-primary font-size-18  btnSentMoney " style="" > ' . trans('message.btn_send_money') . ' </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                  </div>

           ';

    // วุฒิเพิ่มมา
    $order_back = DB::table('db_orders')
    ->select('db_orders.code_order','db_orders.created_at','db_orders.cash_pay','ck_users_admin.name')
    ->join('ck_users_admin','ck_users_admin.id','db_orders.action_user')
    ->where('db_orders.created_at','<',date('Y-m-d H:i:s'))
    ->whereIn('db_orders.approve_status',[2,4,9])
    ->whereNotIn('db_orders.approve_status',[5,6])
    ->where('db_orders.status_sent_money',0)
    ->where('db_orders.code_order','!=','')
    ->where('db_orders.action_user',$user_login_id)
    ->orderBy('db_orders.created_at','desc')
    ->get();

  $order_back_ai = DB::table('db_add_ai_cash')
  ->select('db_add_ai_cash.code_order','db_add_ai_cash.created_at','db_add_ai_cash.cash_pay','ck_users_admin.name')
  ->join('ck_users_admin','ck_users_admin.id','db_add_ai_cash.action_user')
  ->where('db_add_ai_cash.created_at','<',date('Y-m-d H:i:s'))
  ->whereIn('db_add_ai_cash.approve_status',[2,4,9])
  ->whereNotIn('db_add_ai_cash.approve_status',[5,6])
  ->where('db_add_ai_cash.status_sent_money',0)
  ->where('db_add_ai_cash.code_order','!=','')
  ->where('db_add_ai_cash.action_user',$user_login_id)
  ->orderBy('db_add_ai_cash.created_at','desc')
  ->get();

  $p = '';
  foreach($order_back as $ord){
    $date=date_create($ord->created_at);
    $date=date_format($date,"d-m-Y");
      $p .= '<tr>
      <td class="text-left">'.$ord->name.'</td>
      <td class="text-left">'.$date.'</td>
      <td class="text-left">'.$ord->code_order.'</td>
      <td class="text-right">'.number_format($ord->cash_pay, 2).'</td>
      <td></td>
      </tr>';
  }
  $p .= '<tr><td><b>Ai-cash</b></td></tr>';
  foreach($order_back_ai as $ord){
    $date=date_create($ord->created_at);
    $date=date_format($date,"d-m-Y");
      $p .= '<tr>
      <td class="text-left">'.$ord->name.'</td>
      <td class="text-left">'.$date.'</td>
      <td class="text-left">'.$ord->code_order.'</td>
      <td class="text-right">'.number_format($ord->cash_pay, 2).'</td>
      <td></td>
      </tr>';
  }

    $show .= '<div id="tb_sent_money_back" class="table-responsive" style=' . $show_tb_sent_money . '>
    <table class="table table-sm m-0">
    <thead>
    <tr style="background-color: #f2f2f2;"><th colspan="5">
    <span class="" > รายการค้างส่งเงินย้อนหลัง (' . trans('message.current_day') . ' : ' . date("d-m-Y") . $user_login_id . ') </span></th>
    </tr>
    <tr>
    <th class="text-left">Seller</th>
    <th class="text-left">Date</th>
    <th class="text-left">Code</th>
    <th class="text-right">Cash</th>
    <th></th>
    </tr>
    </thead>
    <tbody>
    '.$p.'
    </tbody>
    </table>
    ';
    return $show;
  }




  public function getPV_Amount(Request $req)
  {

    $user_login_id = \Auth::user()->id;
    $branch_id_fk = \Auth::user()->branch_id_fk;
    $sPermission = \Auth::user()->permission;
    if ($sPermission == 1) {
      $action_user_011 = "";
      $action_user_012 = "";
    } else {

      if (\Auth::user()->position_level == '3' || \Auth::user()->position_level == '4') {
        $action_user_011 = "";
        $action_user_012 = "";
      } else {
        $action_user_011 = " AND db_orders.action_user = $user_login_id ";
        $action_user_012 = " AND db_add_ai_cash.action_user = $user_login_id ";
      }
    }

     $role_check = DB::table('role_group')->select('acc_status')->where('id',\Auth::user()->role_group_id_fk)->first();
      if(@$role_check->acc_status==1){
        $action_user_011 = "";
        $action_user_012 = "";
      }


    if (!empty($req->startDate)) {
      $startDate1 = " AND DATE(db_orders.created_at) >= '" . $req->startDate . "' ";
      $startDate2 = " AND DATE(db_add_ai_cash.created_at) >= '" . $req->startDate . "' ";
      $startDate3 = date("d-m-Y", strtotime($req->startDate));
      $sD3 = $startDate3;
    } else {
      $startDate1 = " AND DATE(db_orders.created_at) >= CURDATE() ";
      $startDate2 = " AND DATE(db_add_ai_cash.created_at) >= CURDATE() ";
      $startDate3 = date("d-m-Y");
      $sD3 = date("d-m-Y");
    }

    if (!empty($req->endDate)) {
      $endDate1 = " AND DATE(db_orders.created_at) <= '" . $req->endDate . "' ";
      $endDate2 = " AND DATE(db_add_ai_cash.created_at) <= '" . $req->endDate . "' ";
      $endDate3 = date("d-m-Y", strtotime($req->endDate));
      $eD3 = " To " . $endDate3;
    } else {
      $endDate1 = "";
      $endDate2 = "";
      $endDate3 = date("Y-m-d");
      $eD3 = "";
    }

    $sD3 = $sD3 . $eD3;

    if (!empty($req->invoice_code)) {

      if(count($req->invoice_code) > 0){
        $or_str = "";
        foreach($req->invoice_code as $key => $or){
          if($key+1 == count($req->invoice_code)){
            $or_str.= "'".$or."'";
          }else{
            $or_str.= "'".$or."'".',';
          }

        }
        $invoice_code = " AND db_orders.code_order IN (".$or_str.") ";
        $invoice_code2 = " AND db_add_ai_cash.code_order IN (".$or_str.") ";
    }

    } else {
      $invoice_code = "";
      $invoice_code2 = "";
    }

    if (!empty($req->purchase_type_id_fk)) {
      $purchase_type_id_fk = " AND db_orders.purchase_type_id_fk = '" . $req->purchase_type_id_fk . "' ";
      if ($req->purchase_type_id_fk == 4) {
        $purchase_type_id_fk_02 = "";
      } else {
        $purchase_type_id_fk_02 = " AND db_add_ai_cash.id=0 ";
      }
    } else {
      $purchase_type_id_fk = "";
      $purchase_type_id_fk_02 = "";
    }

    if (!empty($req->customer_username)) {
      $customer_username = " AND db_orders.customers_id_fk = '" . $req->customer_username . "' ";
      $customer_username_02 = " AND db_add_ai_cash.customer_id_fk = '" . $req->customer_username . "' ";
    } else {
      $customer_username = "";
      $customer_username_02 = "";
    }

    if (!empty($req->customer_name)) {
      $customer_name = " AND db_orders.customers_id_fk = '" . $req->customer_name . "' ";
      $customer_name_02 = " AND db_add_ai_cash.customer_id_fk = '" . $req->customer_name . "' ";
    } else {
      $customer_name = "";
      $customer_name_02 = "";
    }

    if (!empty($req->action_user)) {
      $action_user_02 = " AND db_orders.action_user = '" . $req->action_user . "' ";
      $action_user_022 = " AND db_add_ai_cash.action_user = '" . $req->action_user . "' ";
    } else {
      $action_user_02 = "";
      $action_user_022 = "";
    }


    if (isset($req->status_sent_money)) {
      $status_sent_money = " AND db_orders.status_sent_money = " . $req->status_sent_money . " ";
      $status_sent_money_02 = " AND db_add_ai_cash.status_sent_money = " . $req->status_sent_money . " ";
    } else {
      $status_sent_money = "";
      $status_sent_money_02 = "";
    }

    if (isset($req->approve_status)) {
      $approve_status = " AND db_orders.approve_status = " . $req->approve_status . " ";
      $approve_status_02 = " AND db_add_ai_cash.approve_status = " . $req->approve_status . " ";
    } else {
      $approve_status = "";
      $approve_status_02 = "";
    }

    if (isset($req->viewcondition)) {
      if (isset($req->viewcondition) && $req->viewcondition == "ViewBuyNormal") {
        $viewcondition_01 = ' and db_orders.purchase_type_id_fk not in (4,5) ';
        $viewcondition_02 = ' and db_add_ai_cash.id=0 ';
      } else if (isset($req->viewcondition) && $req->viewcondition == "ViewBuyVoucher") {
        $viewcondition_01 = ' and db_orders.purchase_type_id_fk in (4,5) ';
        $viewcondition_02 = '';
      } else {
        $viewcondition_01 = '';
        $viewcondition_02 = '';
      }
    } else {
      $viewcondition_01 = '';
      $viewcondition_02 = '';
    }

    if (!empty($req->business_location_id_fk)) {
      $business_location_id_fk = " AND db_orders.business_location_id_fk = " . $req->business_location_id_fk . " ";
    }else{
      $business_location_id_fk = " AND db_orders.business_location_id_fk = '" . (\Auth::user()->business_location_id_fk) . "' ";
    }

    $d1 = DB::select("
                    SELECT count(db_orders.id) as cnt,
                      SUM(
                        (CASE WHEN db_orders.sum_credit_price is null THEN 0 ELSE db_orders.sum_credit_price END) +
                        (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
                        -- (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
                        (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
                        (CASE WHEN db_orders.true_money_price is null THEN 0 ELSE db_orders.true_money_price END) +
                        (CASE WHEN db_orders.prompt_pay_price is null THEN 0 ELSE db_orders.prompt_pay_price END) +
                        (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END)
                        -- (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
                        ) as sum_price,

                    sum(pv_total) as pv_total
                    FROM db_orders
                    Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                    WHERE db_orders.check_press_save=2
                    AND approve_status!='' AND approve_status!=0 AND approve_status!=5
                    and approve_status in (1)
                    $action_user_011
                    $startDate1
                    $endDate1
                    $purchase_type_id_fk
                    $customer_username
                    $customer_name
                    $invoice_code
                    $action_user_02
                    $status_sent_money
                    $approve_status
                    $viewcondition_01
                    $business_location_id_fk
                    ");

                $cnt_01 = $d1[0]->cnt;
                $sum_price_01 = $d1[0]->sum_price;
                $pv_total_01 = $d1[0]->pv_total;
                $d3 = DB::select("
                SELECT count(db_orders.id) as cnt,
                SUM(
                  (CASE WHEN db_orders.sum_credit_price is null THEN 0 ELSE db_orders.sum_credit_price END) +
                  (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
                  -- (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
                  (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
                  (CASE WHEN db_orders.true_money_price is null THEN 0 ELSE db_orders.true_money_price END) +
                  (CASE WHEN db_orders.prompt_pay_price is null THEN 0 ELSE db_orders.prompt_pay_price END) +
                  (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END)
                  -- (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
                  ) as sum_price,
                sum(pv_total) as pv_total
                FROM db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                WHERE db_orders.check_press_save=2
                AND approve_status!='' AND approve_status!=0 AND approve_status!=5
                and approve_status in (2)
                $action_user_011
                $startDate1
                $endDate1
                $purchase_type_id_fk
                $customer_username
                $customer_name
                $invoice_code
                $action_user_02
                $status_sent_money
                $approve_status
                $viewcondition_01
                $business_location_id_fk
");

    $cnt_02 = $d3[0]->cnt;
    $sum_price_02 = $d3[0]->sum_price;
    $pv_total_02 = $d3[0]->pv_total;

    $d5 = DB::select("
                SELECT count(db_orders.id) as cnt,
                SUM(
                  (CASE WHEN db_orders.sum_credit_price is null THEN 0 ELSE db_orders.sum_credit_price END) +
                  (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
                  -- (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
                  (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
                  (CASE WHEN db_orders.true_money_price is null THEN 0 ELSE db_orders.true_money_price END) +
                  (CASE WHEN db_orders.prompt_pay_price is null THEN 0 ELSE db_orders.prompt_pay_price END) +
                  (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END)
                  -- (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
                  ) as sum_price,

                sum(pv_total) as pv_total
                FROM db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                WHERE db_orders.check_press_save=2
                AND approve_status!='' AND approve_status!=0
                and approve_status in (5)
                $action_user_011
                $startDate1
                $endDate1
                $purchase_type_id_fk
                $customer_username
                $customer_name
                $invoice_code
                $action_user_02
                $status_sent_money
                $approve_status
                $viewcondition_01
                $business_location_id_fk

");

    $cnt_03 = $d5[0]->cnt;
    $sum_price_03 = $d5[0]->sum_price;
    $pv_total_03 = $d5[0]->pv_total;

    $d7 = DB::select("

                SELECT count(db_orders.id) as cnt,

                SUM(
                  (CASE WHEN db_orders.sum_credit_price is null THEN 0 ELSE db_orders.sum_credit_price END) +
                  (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
                  -- (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
                  (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
                  (CASE WHEN db_orders.true_money_price is null THEN 0 ELSE db_orders.true_money_price END) +
                  (CASE WHEN db_orders.prompt_pay_price is null THEN 0 ELSE db_orders.prompt_pay_price END) +
                  (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END)
                  -- (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
                  ) as sum_price,

                sum(pv_total) as pv_total
                FROM db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                WHERE db_orders.check_press_save=2
                and approve_status not in (1,2,5)
                $action_user_011
                $startDate1
                $endDate1
                $purchase_type_id_fk
                $customer_username
                $customer_name
                $invoice_code
                $action_user_02
                $status_sent_money
                $approve_status
                $viewcondition_01
                $business_location_id_fk

");

    $cnt_04 = $d7[0]->cnt;
    $sum_price_04 = $d7[0]->sum_price;
    $pv_total_04 = $d7[0]->pv_total;
    $d9 = DB::select("

                SELECT count(db_orders.id) as cnt,

                SUM(
                  (CASE WHEN db_orders.sum_credit_price is null THEN 0 ELSE db_orders.sum_credit_price END) +
                  (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
                  -- (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
                  (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
                  (CASE WHEN db_orders.true_money_price is null THEN 0 ELSE db_orders.true_money_price END) +
                  (CASE WHEN db_orders.prompt_pay_price is null THEN 0 ELSE db_orders.prompt_pay_price END) +
                  (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END)
                  -- (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
                  ) as sum_price,

                sum(pv_total) as pv_total
                FROM db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                WHERE db_orders.check_press_save=2 AND approve_status!=5
                $action_user_011
                $startDate1
                $endDate1
                $purchase_type_id_fk
                $customer_username
                $customer_name
                $invoice_code
                $action_user_02
                $status_sent_money
                $approve_status
                $viewcondition_01
                $business_location_id_fk

");

    $cnt_05 = $d9[0]->cnt;
    $sum_price_05 = $d9[0]->sum_price;
    $pv_total_05 = $d9[0]->pv_total;

    $show = '';

    $show .=
      '
          <div class="table-responsive">
                  <table class="table table-striped mb-0">

                    <thead>
                      <tr style="background-color: #f2f2f2;text-align: right;">
                        <th style="text-align: left !important;" > (' . $sD3 . ') </th>
                        <th>' . trans('message.list') . '</th>
                        <th>PV</th>
                        <th>' . trans('message.amount') . '</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr style="color: red" >
                        <th scope="row"><span style="color:black !important;">' . trans('message.status') . ':</span> ' . trans('message.status_pv_1') . '</th>
                        <td style="text-align: right;">' . @$cnt_01 . ' </td>
                        <td style="text-align: right;">' . number_format(@$pv_total_01, 0) . ' </td>
                        <td style="text-align: right;">' . number_format(@$sum_price_01, 2) . ' </td>
                      </tr>
                      <tr>
                        <th scope="row"><span style="color:black !important;">' . trans('message.status') . ':</span> ' . trans('message.status_pv_2') . '</th>
                        <td style="text-align: right;">' . @$cnt_02 . ' </td>
                        <td style="text-align: right;">' . number_format(@$pv_total_02, 0) . ' </td>
                        <td style="text-align: right;">' . number_format(@$sum_price_02, 2) . ' </td>
                      </tr>
                      <tr>
                        <th scope="row"><span style="color:black !important;">' . trans('message.status') . ':</span> ' . trans('message.status_pv_3') . '</th>
                        <td style="text-align: right;">' . @$cnt_03 . ' </td>
                        <td style="text-align: right;">' . number_format(@$pv_total_03, 0) . ' </td>
                        <td style="text-align: right;">' . number_format(@$sum_price_03, 2) . ' </td>
                      </tr>
                      <tr>
                        <th scope="row"><span style="color:black !important;">' . trans('message.status') . ':</span> ' . trans('message.status_pv_4') . '</th>
                        <td style="text-align: right;">' . @$cnt_04 . ' </td>
                        <td style="text-align: right;">' . number_format(@$pv_total_04, 0) . ' </td>
                        <td style="text-align: right;">' . number_format(@$sum_price_04, 2) . ' </td>
                      </tr>

                      <tr>
                        <th scope="row">' . trans('message.total') . '<br>(<font color=red>ยกเว้นรายการ * รอดำเนินการต่อ ,ยกเลิก</font>)</th>
                        <td style="text-align: right;font-weight:bold;">' . @$cnt_05 . ' </td>
                        <td style="text-align: right;font-weight:bold;">' . number_format(@$pv_total_05, 0) . ' </td>
                        <td style="text-align: right;font-weight:bold;">' . number_format(@$sum_price_05, 2) . ' </td>
                      </tr>

                    </tbody>
                  </table>
                </div>
           ';
    return $show;
  }


  public function Datatable(Request $req)
  {
    $user_login_id = \Auth::user()->id;
    $sPermission = \Auth::user()->permission;
    if (\Auth::user()->position_level == '3' || \Auth::user()->position_level == '4') {
      $action_user_011 = " AND db_orders.branch_id_fk = '" . (\Auth::user()->branch_id_fk) . "' ";
    } else {
      $action_user_011 = " AND action_user = $user_login_id ";
    }

    if ($sPermission == 1) {
      $action_user_01 = "";
      $action_user_011 = "";
    } else {
      $action_user_01 = " AND action_user = $user_login_id ";
    }

    $role_check = DB::table('role_group')->select('acc_status')->where('id',\Auth::user()->role_group_id_fk)->first();
    if(@$role_check->acc_status==1){
      $action_user_01 = "";
      $action_user_011 = "";
    }

    // วุฒิเพิ่มมา
    if (\Auth::user()->position_level == '3' || \Auth::user()->position_level == '4') {
      $action_user_01 = "";
      $action_user_011 = "";
    }

    if (!empty($req->startDate)) {
      $startDate = " AND DATE(db_orders.created_at) >= '" . $req->startDate . "' ";
      $startDate2 = " AND DATE(db_add_ai_cash.created_at) >= '" . $req->startDate . "' ";
    } else {
      $startDate = " AND DATE(db_orders.created_at) >= CURDATE() ";
      $startDate2 = " AND DATE(db_add_ai_cash.created_at) >= CURDATE() ";
    }

    if (!empty($req->endDate)) {
      $endDate = " AND DATE(db_orders.created_at) <= '" . $req->endDate . "' ";
      $endDate2 = " AND DATE(db_add_ai_cash.created_at) <= '" . $req->endDate . "' ";
    } else {
      $endDate = "";
      $endDate2 = "";
    }

    if (!empty($req->business_location_id_fk)) {
      $business_location_id_fk = " AND db_orders.business_location_id_fk = " . $req->business_location_id_fk . " ";
    }else{
      $business_location_id_fk = " AND db_orders.business_location_id_fk = '" . (\Auth::user()->business_location_id_fk) . "' ";
    }

    if (!empty($req->purchase_type_id_fk)) {
      $purchase_type_id_fk = " AND db_orders.purchase_type_id_fk = '" . $req->purchase_type_id_fk . "' ";
      if ($req->purchase_type_id_fk == 4) {
        $purchase_type_id_fk_02 = "";
      } else {
        $purchase_type_id_fk_02 = " AND db_add_ai_cash.id=0 ";
      }
    } else {
      $purchase_type_id_fk = "";
      $purchase_type_id_fk_02 = "";
    }

    if (!empty($req->customer_username)) {
      $customer_username = " AND db_orders.customers_id_fk = '" . $req->customer_username . "' ";
      $customer_username_02 = " AND db_add_ai_cash.customer_id_fk = '" . $req->customer_username . "' ";
    } else {
      $customer_username = "";
      $customer_username_02 = "";
    }

    if (!empty($req->customer_name)) {
      $customer_name = " AND db_orders.customers_id_fk = '" . $req->customer_name . "' ";
      $customer_name_02 = " AND db_add_ai_cash.customer_id_fk = '" . $req->customer_name . "' ";
    } else {
      $customer_name = "";
      $customer_name_02 = "";
    }

    if (!empty($req->invoice_code)) {
      if(count($req->invoice_code) > 0){
          $or_str = "";
          foreach($req->invoice_code as $key => $or){
            if($key+1 == count($req->invoice_code)){
              $or_str.= "'".$or."'";
            }else{
              $or_str.= "'".$or."'".',';
            }
          }
          $invoice_code = " AND code_order IN (".$or_str.") ";
        // wut เพิ่ม
        $action_user_01 = "";
        $action_user_011 = "";
      }
    } else {
      $invoice_code = "";
    }


    if (!empty($req->action_user)) {
      $action_user_02 = " AND db_orders.action_user = '" . $req->action_user . "' ";
      $action_user_022 = " AND db_add_ai_cash.action_user = '" . $req->action_user . "' ";
    } else {
      $action_user_02 = "";
      $action_user_022 = "";
    }


    if (isset($req->status_sent_money)) {
      $status_sent_money = " AND db_orders.status_sent_money = " . $req->status_sent_money . " ";
      $status_sent_money_02 = " AND db_add_ai_cash.status_sent_money = " . $req->status_sent_money . " ";
    } else {
      $status_sent_money = "";
      $status_sent_money_02 = "";
    }

    if (isset($req->approve_status)) {
      if ($req->approve_status == 7) {
        $approve_status = " AND db_orders.approve_status = 0 ";
        if (!empty($req->startDate)) {
        } else {
          $startDate = '';
          $endDate = '';
          $startDate2 = '';
          $endDate2 = '';
        }
      } else {
        $approve_status = " AND db_orders.approve_status = " . $req->approve_status . " ";
      }
      $approve_status_02 = " AND db_add_ai_cash.approve_status = " . $req->approve_status . " ";
    } else {
      $approve_status = "";
      $approve_status_02 = "";
    }

    if (isset($req->viewcondition)) {
      if (isset($req->viewcondition) && $req->viewcondition == "ViewBuyNormal") {
        $viewcondition_01 = ' and db_orders.purchase_type_id_fk not in (4,5) ';
        $viewcondition_02 = ' and db_add_ai_cash.id=0 ';
      } else if (isset($req->viewcondition) && $req->viewcondition == "ViewBuyVoucher") {
        $viewcondition_01 = ' and db_orders.purchase_type_id_fk in (4,5) ';
        $viewcondition_02 = '';
      } else {
        $viewcondition_01 = '';
        $viewcondition_02 = '';
      }
    } else {
      $viewcondition_01 = '';
      $viewcondition_02 = '';
    }

    $sTable = DB::select("
                SELECT gift_voucher_price,code_order,db_orders.id,action_date,purchase_type_id_fk,0 as type,customers_id_fk,sum_price,invoice_code,approve_status,shipping_price,
                db_orders.true_money_price,db_orders.prompt_pay_price, db_orders.user_name, db_orders.name_customer, db_orders.name_customer_business,
                db_orders.updated_at,dataset_pay_type.detail as pay_type,cash_price,db_orders.business_location_id_fk,
                credit_price,fee_amt,transfer_price,aicash_price,total_price,db_orders.created_at,
                status_sent_money,cash_pay,action_user,db_orders.pay_type_id_fk,sum_credit_price,db_orders.charger_type,db_orders.pay_with_other_bill,db_orders.pay_with_other_bill_note,
                db_orders.shipping_free,db_orders.transfer_bill_note
                FROM db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                WHERE 1

                $action_user_011
                $startDate
                $endDate
                $purchase_type_id_fk
                $customer_username
                $customer_name
                $invoice_code
                $status_sent_money
                $approve_status
                $viewcondition_01
                $action_user_02
                $business_location_id_fk
                ORDER BY id DESC
              ");

    $sQuery = \DataTables::of($sTable);

    return $sQuery
      ->addColumn('created_at', function ($row) {
        $d = strtotime(@$row->created_at);
        return date("Y-m-d", $d) . "<br/>" . date("H:i:s", $d);
      })
      ->escapeColumns('created_at')
      ->addColumn('customer_name', function ($row) {
        if($row->user_name=='' || $row->user_name==null){
          if ($row->customers_id_fk) {
            $Customer = DB::select(" select user_name, prefix_name, first_name, last_name from customers where id=" . $row->customers_id_fk . " ");
            return "[" . @$Customer[0]->user_name . '] <br>' . @$Customer[0]->prefix_name . @$Customer[0]->first_name . " " . @$Customer[0]->last_name;
          }
        }else{
          return "[" . $row->user_name . '] <br>' . $row->name_customer;
        }

      })
      ->addColumn('purchase_type', function ($row) {
        if (@$row->purchase_type_id_fk > 0) {
          @$purchase_type = DB::select(" select * from dataset_orders_type where id=" . @$row->purchase_type_id_fk . " ");
          return @$purchase_type[0]->detail;
        }
      })

      ->addColumn('status', function ($row) {
        if (@$row->approve_status != "") {
          @$approve_status = DB::select(" select * from `dataset_approve_status` where id=" . @$row->approve_status . " ");
          if($row->approve_status == 6){
            $transfer_bill_note = '<br><span style="color:black; font-size: 11px;"> - '.$row->transfer_bill_note.'</span>';
          }else{
            $transfer_bill_note = '';
          }
          return @$approve_status[0]->txt_desc.$transfer_bill_note;
        } else {
          return "<font color=red>* รอดำเนินการต่อ</font>";
        }
      })
      ->addColumn('shipping_price', function ($row) {
        if (@$row->shipping_price && @$row->shipping_free != 1) {
          return @number_format(@$row->shipping_price, 0);
        }else{
          return '';
        }
      })
      ->addColumn('tooltip_price', function ($row) {
        $tootip_price = '';
          if($row->cash_pay!=0){
            $tootip_price = ' เงินสด: ' . $row->cash_pay;
          }
        if (@$row->sum_credit_price != 0) {
          if (@$row->charger_type == 1) {
            $tootip_price .= ' เครดิต: ' . $row->sum_credit_price . ' ค่าธรรมเนียม :' . $row->fee_amt;
          } else {
            $tootip_price .= ' เครดิต: ' . $row->sum_credit_price . ' ค่าธรรมเนียม :' . $row->fee_amt;
          }
        }
        if (@$row->transfer_price != 0) {
          $tootip_price .= ' เงินโอน: ' . $row->transfer_price;
        }
        if (@$row->aicash_price != 0) {
          $tootip_price .= ' Ai-Cash: ' . $row->aicash_price;
        }

        if ($row->shipping_price > 0 && @$row->shipping_free != 1) {
          $tootip_price .= ' ค่าขนส่ง: ' . $row->shipping_price;
        }

        if ($row->gift_voucher_price > 0) {
          $tootip_price .= ' Gift Voucher: ' . $row->gift_voucher_price;
        }

        if ($row->true_money_price > 0) {
          $tootip_price .= ' TrueMoney: ' . $row->true_money_price;
        }

        if ($row->prompt_pay_price > 0) {
          $tootip_price .= ' PromptPay: ' . $row->prompt_pay_price;
        }

        return $tootip_price;
      })
      ->addColumn('total_price', function ($row) {
        $total_price = 0;

          $total_price += $row->cash_pay;

        if (@$row->sum_credit_price != 0) {
          $total_price += $row->sum_credit_price;
        }

        if (@$row->transfer_price != 0) {
          $total_price += $row->transfer_price;
        }

        if (@$row->aicash_price != 0) {
          $total_price += $row->aicash_price;
        }

        if (@$row->true_money_price != 0) {
          $total_price += $row->true_money_price;
        }

        if (@$row->prompt_pay_price != 0) {
          $total_price += $row->prompt_pay_price;
        }

        if (@$row->pay_type_id_fk != 10) {
          if ($row->shipping_price > 0 && @$row->shipping_free != 1) {
            $shipping_price  =  $row->shipping_price;
          } else {
            $shipping_price  = 0;
          }
        } else {
          $shipping_price  = 0;
        }

        return @number_format((@$total_price), 2);
      })

      ->addColumn('status_delivery_packing', function ($row) {
        $r = DB::select(" select receipt FROM db_delivery WHERE receipt = '" . $row->code_order . "' AND status_pack=1 ");
        if (@$r) {
          return 1;
        } else {
          return 0;
        }
      })
      ->addColumn('status_pay_product_receipt', function ($row) {
        $r = DB::select(" SELECT id FROM db_pay_product_receipt_001 WHERE invoice_code = '" . $row->code_order . "' AND status_sent<>4 ");
        if (@$r) {
          return 1;
        } else {
          return 0;
        }
      })
      ->addColumn('status_delivery_02', function ($row) {
        $ch = 0;
        $r = DB::select(" SELECT orders_id_fk FROM `db_pick_pack_packing_code` where status<>6 and status_picked=1 ; ");
        foreach ($r as $key => $value) {

          $orders_id_fk = explode(',', @$value->orders_id_fk);
          if (in_array($row->id, @$orders_id_fk)) {
            $ch = 1;
          }
        }
        return @$ch;
      })
      ->addColumn('status_sent_product', function ($row) {
        if (!empty($row->code_order)) {
          $r1 = DB::select(" SELECT receipts FROM `db_pick_pack_requisition_code` where status=1 ; ");
          if (@$r1) {
            $receipts = explode(',', $r1[0]->receipts);
            if (in_array($row->code_order, $receipts)) {
              return $row->code_order;
            } else {
              return '';
            }
          } else {
            return '';
          }
        }
      })
      ->addColumn('status_sent_desc', function ($row) {
        if (!empty($row->code_order)) {

          $r1 = DB::select(" SELECT pick_pack_packing_code_id_fk,receipts FROM `db_pick_pack_requisition_code` order by pick_pack_packing_code_id_fk desc limit 1 ; ");
          if (@$r1) {
            $receipts = explode(',', $r1[0]->receipts);
            if (in_array($row->code_order, $receipts)) {
              $r2 = DB::select(" SELECT status_sent FROM `db_pay_requisition_001` WHERE pick_pack_requisition_code_id_fk = '" . $r1[0]->pick_pack_packing_code_id_fk . "' ");
              $r3 = \App\Models\Backend\Pay_requisition_status::find($r2[0]->status_sent);
              return @$r3->txt_desc;
            }
          }
        }
      })
      ->addColumn('action_user', function ($row) {
        if (@$row->action_user != '') {
          $sD = DB::select(" select name from ck_users_admin where id=" . $row->action_user . " ");
          return @$sD[0]->name;
        } else {
          return 'V3';
        }
      })
      ->addColumn('code_order_select', function ($row) {
        $other = "";
        if($row->pay_with_other_bill == 1){
          $other = "<br><br> ชำระพร้อมบิล ".$row->pay_with_other_bill_note;
        }
        $p = "<a href='javascript:;' class='order_select' code_id='".@$row->code_order."'><span class='badge badge-pill badge-soft-primary font-size-16'>".@$row->code_order."</span></a>";
        return $p.$other;
      })

      ->make(true);
  }

  public function DatatableCourseEvent(Request $req)
  {
    // print_r($req->user_name);
    // $sTable = \App\Models\Backend\Course_event::search()->orderBy('id', 'asc');
    $sTable = DB::select(" SELECT course_event.*,('" . $req->user_name . "') as user_name FROM `course_event` ");
    $sQuery = \DataTables::of($sTable);
    return $sQuery
      ->addColumn('ce_type_desc', function ($row) {
        $ce_type = \App\Models\Backend\Ce_type::find($row->ce_type);
        return @$ce_type->txt_desc;
      })
      ->addColumn('updated_at', function ($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->addColumn('CourseCheckRegis', function ($row) {
        $CourseCheckRegis = \App\Models\Frontend\CourseCheckRegis::cart_check_register($row->id, 1, $row->user_name);
        return $CourseCheckRegis['status'];
      })
      ->addColumn('cuase_cannot_buy', function ($row) {
        $CourseCheckRegis = \App\Models\Frontend\CourseCheckRegis::check_register_all($row->id, $row->user_name);
        if ($CourseCheckRegis) {
          $arr = [];
          for ($i = 0; $i < count(@$CourseCheckRegis); $i++) {
            $c = array_column($CourseCheckRegis, $i);
            foreach ($c as $key => $value) {
              if ($value['status'] == "fail") {
                array_push($arr, $value['message']);
              }
            }
            $im = implode(',', $arr);
          }
          return $im;
        }
      })
      ->escapeColumns('cuase_cannot_buy')
      ->make(true);
  }

  public function getOrderHistoryStatus(Request $request)
  {
    $orderHistoryLogs = (new DbOrderHistoryLog())->queryLogs($request->order_id);
    $rows = '';

    foreach ($orderHistoryLogs as $orderHistoryLog) {

      $formatDate = $orderHistoryLog->created_at->format('d/m/Y H:i:s');

      $rows .= "
          <tr>
            <td>$orderHistoryLog->status_name</td>
            <td>$orderHistoryLog->approve_name</td>
            <td>$formatDate</td>
          </tr>
        ";
    }

    return $rows;
  }
}
