<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Session;
use App\Helpers\General;

class Check_money_dailyController extends Controller
{

    public function index(Request $request)
    {
         // วุฒิสร้าง session
         $menus = DB::table('ck_backend_menu')->select('id')->where('id',43)->first();
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

      //   $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
      //       return $query->where('id', auth()->user()->business_location_id_fk);
      //   })->get();

      $sBusiness_location = \App\Models\Backend\Business_location::get();
        $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
            return $query->where('id', auth()->user()->branch_id);
        })->get();
        $sSeller = DB::select(" select id,name as seller_name from  ck_users_admin ");
       return View('backend.check_money_daily.index')->with(array(
        'sBusiness_location'=>$sBusiness_location,
        'sBranchs'=>$sBranchs,
           'sSeller'=>$sSeller,

      ) );
    }

    public function check_money_daily_report(Request $request)
    {
      //   $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
      //       return $query->where('id', auth()->user()->business_location_id_fk);
      //   })->get();
      $sBusiness_location = \App\Models\Backend\Business_location::get();
        $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
            return $query->where('id', auth()->user()->branch_id);
        })->get();
        $sSeller = DB::select(" select id,name as seller_name from  ck_users_admin ");
       return View('backend.check_money_daily.check_money_daily_report')->with(array(
        'sBusiness_location'=>$sBusiness_location,
        'sBranchs'=>$sBranchs,
           'sSeller'=>$sSeller,
      ) );
    }

   public function create()
    {
      return View('backend.check_money_daily.form');
    }


    public function edit($id)
    {

      // วุฒิสร้าง session
      $menus = DB::table('ck_backend_menu')->select('id')->where('id',43)->first();
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


       $sRow = \App\Models\Backend\Sent_money_daily::find($id);
       // dd($sRow);
       // $sSent_money_daily = DB::select(" select * from  db_sent_money_daily where id=$id ");
       // dd($sSent_money_daily);

       // $sRowCheck_money_daily = \App\Models\Backend\Check_money_daily::find($id);
       // dd($sRowCheck_money_daily);
       // $sRowCheck_money_daily_AiCash = \App\Models\Backend\Check_money_daily::where('id',$id)->get();
       // dd($sRowCheck_money_daily_AiCash);

       $sSeller = DB::select(" select * from  ck_users_admin ");

       return View('backend.check_money_daily.form')->with(
        array(
           'sRow'=>$sRow,
           // 'sRowAdd_ai_cash'=>$sRowAdd_ai_cash,
           // 'sRowCheck_money_daily'=>$sRowCheck_money_daily,
           // 'sRowCheck_money_daily_AiCash'=>$sRowCheck_money_daily_AiCash,
           // 'sSent_money_daily'=>$sSent_money_daily,
           'sSeller'=>$sSeller,
        ) );
    }

    public function check_money_daily_ai_edit($id)
    {

      // วุฒิสร้าง session
      $menus = DB::table('ck_backend_menu')->select('id')->where('id',43)->first();
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


       $sRow = \App\Models\Backend\Sent_money_daily_ai::find($id);
       // dd($sRow);
       // $sSent_money_daily = DB::select(" select * from  db_sent_money_daily where id=$id ");
       // dd($sSent_money_daily);

       // $sRowCheck_money_daily = \App\Models\Backend\Check_money_daily::find($id);
       // dd($sRowCheck_money_daily);
       // $sRowCheck_money_daily_AiCash = \App\Models\Backend\Check_money_daily::where('id',$id)->get();
       // dd($sRowCheck_money_daily_AiCash);

       $sSeller = DB::select(" select * from  ck_users_admin ");

       return View('backend.check_money_daily.form_ai')->with(
        array(
           'sRow'=>$sRow,
           // 'sRowAdd_ai_cash'=>$sRowAdd_ai_cash,
           // 'sRowCheck_money_daily'=>$sRowCheck_money_daily,
           // 'sRowCheck_money_daily_AiCash'=>$sRowCheck_money_daily_AiCash,
           // 'sSent_money_daily'=>$sSent_money_daily,
           'sSeller'=>$sSeller,
        ) );
    }

    public function update(Request $request, $id)
    {
       // dd($request->all());
       // dd($request);

      if(!empty($request->id)){

              if(isset($request->approved) && $request->approved==1){
                // dd($request->all());
                  if(request('sRowCheck_money_daily_id')){

                      // $sRow = \App\Models\Backend\Check_money_daily::find($request->id);
                      // $sRow->approver = \Auth::user()->id;
                      // $sRow->approve_status = request('approve_status') ;
                      // $sRow->approve_date = date('Y-m-d');
                      // $sRow->note = request('note');
                      // $sRow->save();

                      // $Frontstore = \App\Models\Backend\Frontstore::find(request('fronstore_id_fk'));
                      // $Frontstore->approver = \Auth::user()->id;
                      // $Frontstore->approve_status = request('approve_status')  ;
                      // $Frontstore->approve_date = date('Y-m-d');
                      // $Frontstore->save();

                     DB::select(" UPDATE `db_sent_money_daily` SET `status_approve`='1', `approver`='".(\Auth::user()->id).")', `approve_date`=now() ,total_money=".$request->sum_total_price2." WHERE (`id`='".$request->id."') ");

                     DB::select(" UPDATE `db_orders` SET `status_sent_money`='2' WHERE (`sent_money_daily_id_fk` in ('".$request->id."') ) ");


                  }

                  return redirect()->to(url("backend/check_money_daily"));

              // }else{
              //   return $this->form($request->sRowCheck_money_daily_id);
              }

      }


      if(!empty($request->add_ai_cash_id_fk)){

         // dd($request->approved);

              if(isset($request->approved) && $request->approved==1){
                // dd($request->all());

                  if(request('sRowCheck_money_daily_AiCash_id')){
                      $sRow = \App\Models\Backend\Check_money_daily::find(request('sRowCheck_money_daily_AiCash_id'));
                      $sRow->approver = \Auth::user()->id;
                      $sRow->approve_status = request('approve_status') ;
                      $sRow->approve_date = date('Y-m-d');
                      $sRow->note = request('note');
                      $sRow->save();

                      $Add_ai_cash = \App\Models\Backend\Add_ai_cash::find(request('add_ai_cash_id_fk'));
                      $Add_ai_cash->approver = \Auth::user()->id;
                      $Add_ai_cash->approve_status = request('approve_status') ;
                      $Add_ai_cash->approve_date = date('Y-m-d');
                      $Add_ai_cash->save();
                  }

                  return redirect()->to(url("backend/check_money_daily/".request('add_ai_cash_id_fk')."/edit?fromAicash"));
              }else{
                return $this->form($request->sRowCheck_money_daily_AiCash_id);
              }

      }



    }

    public function check_money_daily_ai_update(Request $request, $id)
    {
       // dd($request->all());
       // dd($request);

      if(!empty($request->id)){

              if(isset($request->approved) && $request->approved==1){
                // dd($request->all());
                  if(request('sRowCheck_money_daily_id')){

                      // $sRow = \App\Models\Backend\Check_money_daily::find($request->id);
                      // $sRow->approver = \Auth::user()->id;
                      // $sRow->approve_status = request('approve_status') ;
                      // $sRow->approve_date = date('Y-m-d');
                      // $sRow->note = request('note');
                      // $sRow->save();

                      // $Frontstore = \App\Models\Backend\Frontstore::find(request('fronstore_id_fk'));
                      // $Frontstore->approver = \Auth::user()->id;
                      // $Frontstore->approve_status = request('approve_status')  ;
                      // $Frontstore->approve_date = date('Y-m-d');
                      // $Frontstore->save();

                     DB::select(" UPDATE `db_sent_money_daily_ai` SET `status_approve`='1', `approver`='".(\Auth::user()->id).")', `approve_date`=now() ,total_money=".$request->sum_total_price2." WHERE (`id`='".$request->id."') ");

                     DB::select(" UPDATE `db_add_ai_cash` SET `status_sent_money`='2' WHERE (`sent_money_daily_id_fk` in ('".$request->id."') ) ");


                  }

                  return redirect()->to(url("backend/check_money_daily"));

              // }else{
              //   return $this->form($request->sRowCheck_money_daily_id);
              }

      }


      // if(!empty($request->add_ai_cash_id_fk)){

      //    // dd($request->approved);

      //         if(isset($request->approved) && $request->approved==1){
      //           // dd($request->all());

      //             if(request('sRowCheck_money_daily_AiCash_id')){
      //                 $sRow = \App\Models\Backend\Check_money_daily::find(request('sRowCheck_money_daily_AiCash_id'));
      //                 $sRow->approver = \Auth::user()->id;
      //                 $sRow->approve_status = request('approve_status') ;
      //                 $sRow->approve_date = date('Y-m-d');
      //                 $sRow->note = request('note');
      //                 $sRow->save();

      //                 $Add_ai_cash = \App\Models\Backend\Add_ai_cash::find(request('add_ai_cash_id_fk'));
      //                 $Add_ai_cash->approver = \Auth::user()->id;
      //                 $Add_ai_cash->approve_status = request('approve_status') ;
      //                 $Add_ai_cash->approve_date = date('Y-m-d');
      //                 $Add_ai_cash->save();
      //             }

      //             return redirect()->to(url("backend/check_money_daily/".request('add_ai_cash_id_fk')."/edit?fromAicash"));
      //         }else{
      //           return $this->form($request->sRowCheck_money_daily_AiCash_id);
      //         }

      // }



    }



    public function store(Request $request)
    {
      // dd($request->all());
      return $this->form();
    }

    public function check_money_daily_ai_store(Request $request)
    {
      // dd($request->all());
      return $this->form_ai();
    }

    public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {

      //   dd(request('id'));
        if(@request('id')){

            $sRow = \App\Models\Backend\Sent_money_daily::find(request('id'));
            // dd($sRow);
            $sRow->total_money = request('total_money');
            // $sRow->status_approve = 1 ;
            $sRow->status_approve = request('select_approve_status');
            $sRow->remark = request('remark');
            $sRow->approver = \Auth::user()->id;
            $sRow->approve_date = date('Y-m-d h:i:s');
            $sRow->save();
//  `approve_status` int(11) DEFAULT '0' COMMENT ' 0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย)''',
// `status_sent_money` int(1) DEFAULT '0' COMMENT '0= - (รอดำเนินการ) , 1=In process (cs กดปุ่มส่งเงินแล้ว)  , 2=Success (พ.เงิน กดรับเงินแล้ว)',
            DB::update(" UPDATE db_orders SET approve_status=9,status_sent_money=2 WHERE id in($sRow->orders_ids) ");

            // // wut ai cash
            // $ai = DB::table('db_add_ai_cash')->where('sent_money_daily_id_fk',request('id'))->first();
            // if($ai){
            //    $sRow = DB::table('db_sent_money_daily_ai')->where('id',$ai->sent_money_daily_id_fk_ai)->update([
            //       'total_money' => request('total_money'),
            //       'status_approve' => 1,
            //       'approver' => \Auth::user()->id,
            //       'approve_date' => date('Y-m-d h:i:s'),
            //    ]);
            //    $sRow =  DB::table('db_sent_money_daily_ai')->where('id',$ai->sent_money_daily_id_fk_ai)->first();
            //    DB::update(" UPDATE db_add_ai_cash SET approve_status=9,status_sent_money=2 WHERE id in($sRow->add_ai_ids) ");
            // }

        }

        return redirect()->to(url("backend/check_money_daily"));

       \DB::commit();

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Check_money_dailyController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function form_ai($id=NULL)
    {
      \DB::beginTransaction();
      try {

      //   dd(request('id'));
        if(@request('id')){

            $sRow = \App\Models\Backend\Sent_money_daily_ai::find(request('id'));
            // dd($sRow);
            $sRow->total_money = request('total_money');
            // $sRow->status_approve = 1 ;
            $sRow->status_approve = request('select_approve_status');
            $sRow->remark = request('remark');
            $sRow->approver = \Auth::user()->id;
            $sRow->approve_date = date('Y-m-d h:i:s');
            $sRow->save();
//  `approve_status` int(11) DEFAULT '0' COMMENT ' 0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย)''',
// `status_sent_money` int(1) DEFAULT '0' COMMENT '0= - (รอดำเนินการ) , 1=In process (cs กดปุ่มส่งเงินแล้ว)  , 2=Success (พ.เงิน กดรับเงินแล้ว)',
            DB::update(" UPDATE db_add_ai_cash SET approve_status=9,status_sent_money=2 WHERE id in($sRow->add_ai_ids) ");

            // // wut ai cash
            // $ai = DB::table('db_add_ai_cash')->where('sent_money_daily_id_fk',request('id'))->first();
            // if($ai){
            //    $sRow = DB::table('db_sent_money_daily_ai')->where('id',$ai->sent_money_daily_id_fk_ai)->update([
            //       'total_money' => request('total_money'),
            //       'status_approve' => 1,
            //       'approver' => \Auth::user()->id,
            //       'approve_date' => date('Y-m-d h:i:s'),
            //    ]);
            //    $sRow =  DB::table('db_sent_money_daily_ai')->where('id',$ai->sent_money_daily_id_fk_ai)->first();
            //    DB::update(" UPDATE db_add_ai_cash SET approve_status=9,status_sent_money=2 WHERE id in($sRow->add_ai_ids) ");
            // }

        }

        return redirect()->to(url("backend/check_money_daily"));

       \DB::commit();

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Check_money_dailyController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }


    public function destroy($id)
    {
      // $sRow = \App\Models\Backend\Check_money_daily::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){

      if(!empty($req->id)){

          $sTable = DB::select("
                    SELECT
                    db_orders.id ,
                    db_orders.action_user,
                    ck_users_admin.`name` as action_user_name,
                    db_orders.pay_type_id_fk,
                    dataset_pay_type.detail AS pay_type,
                    date(db_orders.created_at) AS action_date,
                    db_orders.cash_pay,
                    db_orders.credit_price,
                    db_orders.transfer_price,
                    db_orders.aicash_price,
                    db_orders.shipping_price,
                    db_orders.fee_amt,
                    db_orders.total_price,
                    db_orders.approve_status
                    FROM
                    db_orders
                    Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                    Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                    WHERE db_orders.pay_type_id_fk<>0 AND db_orders.id=".$req->id."
            ");
      }else{

          $sTable = DB::select("
                    SELECT
                    db_orders.id ,
                    db_orders.action_user,
                    ck_users_admin.`name` as action_user_name,
                    db_orders.pay_type_id_fk,
                    dataset_pay_type.detail AS pay_type,
                    date(db_orders.created_at) AS action_date,
                    db_orders.cash_pay,
                    db_orders.credit_price,
                    db_orders.transfer_price,
                    db_orders.aicash_price,
                    db_orders.shipping_price,
                    db_orders.fee_amt,
                    db_orders.total_price,
                    db_orders.approve_status
                    FROM
                    db_orders
                    Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                    Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                    WHERE db_orders.pay_type_id_fk<>0
            ");

      }

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('approve_status', function($row) {
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
      ->addColumn('action_date', function($row) {
          if(!empty($row->action_date)){
            return $row->action_date;
          }
      })
      ->make(true);
    }

    public function DatatableSentMoney(Request $req){

      // วุฒิทำค้นหา บิลวันที่ทำรายการก่อน
      if(!empty($req->business_location_id_fk)){
         $w012 = " AND db_orders.business_location_id_fk=".$req->business_location_id_fk ;
      }else{
         // $w01 = "";
         $w012 = " AND db_orders.business_location_id_fk=".\Auth::user()->business_location_id_fk;
      }
      if(!empty($req->branch_id_fk)){
         $w022 = " AND db_orders.branch_id_fk=".$req->branch_id_fk ;
      }else{
         $w022 = "";
      }

      if(!empty($req->seller)){
         $w032 = " AND db_orders.action_user=".$req->seller ;
      }else{
         $w032 = "";
      }

      if(!empty($req->status_search)){
         $s = $req->status_search==1?"2":"1";
         $w042 = " AND db_orders.status_sent_money=".$s."" ;
      }else{
         $w042 = "";
      }

      if(!empty($req->startDate) && !empty($req->endDate)){
         $w052 = " AND date(db_orders.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
      }else{
         $w052 = " AND date(db_orders.created_at)=CURDATE() ";
      }

      // วุฒิแก้ รวมข้อมูล groupby
      $sTable2 = DB::select("
      SELECT
      db_orders.id,
      db_orders.sent_money_daily_id_fk
      FROM
      db_orders
      WHERE
      db_orders.invoice_code!=''
      $w012
      $w022
      $w032
      $w042
      $w052
      AND sent_money_daily_id_fk != 0
    ");

      $arr_sent = "";
      foreach($sTable2 as $key => $s2){
         if($s2!=''){
            if($key+1 == count($sTable2)){
               $arr_sent .= $s2->sent_money_daily_id_fk;
            }else{
               $arr_sent .= $s2->sent_money_daily_id_fk.',';
            }
         }
      }
      if($arr_sent!=""){
         $w_arr = "AND db_sent_money_daily.id IN ($arr_sent)";
      }else{
         $w_arr = "AND db_sent_money_daily.id IN (0)";
      }

      if(!empty($req->business_location_id_fk)){
         $w01 = " HAVING business_location=".$req->business_location_id_fk;
      }else{
         // $w01 = "";
         $w01 = " HAVING business_location=".\Auth::user()->business_location_id_fk;
      }

      if(!empty($req->branch_id_fk)){
         $w02 = " AND branch=".$req->branch_id_fk ;
      }else{
         $w02 = "";
      }

      if(!empty($req->seller)){
         $w03 = " AND db_sent_money_daily.sender_id=".$req->seller ;
      }else{
         $w03 = "";
      }

      if(!empty($req->status_search)){
         $w04 = " AND db_sent_money_daily.status_approve=".$req->status_search ;
      }else{
         $w04 = "";
      }

      $date_check = 0;
      if(!empty($req->startDate) && !empty($req->endDate)){
         // $w05 = " AND date(db_sent_money_daily.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
         $date_check = 1;
         $w05_order = " AND date(db_orders.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
      }
      else{
         // $w05 = " AND date(db_sent_money_daily.created_at)=CURDATE() ";
         $w05_order = " AND date(db_orders.created_at)<=CURDATE() ";
      }
      $w05 = "";

      // if(!empty($req->startDate) && !empty($req->endDate)){
      //    $w05 = " AND date(db_sent_money_daily.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
      //    $date_check = 1;
      //    $w05_order = " AND date(db_orders.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
      // }else{
      //    $w05 = " AND date(db_sent_money_daily.created_at)=CURDATE() ";
      //    $w05_order = " AND date(db_orders.created_at)<=CURDATE() ";
      // }



         $has_id = 0;
      if(isset($req->id)){

        $sTable = DB::select("  SELECT db_sent_money_daily.*,remark as detail,1 as remark,(SELECT business_location_id_fk FROM db_orders WHERE id in(db_sent_money_daily.orders_ids) limit 1) as business_location FROM db_sent_money_daily WHERE  id=".$req->id." AND db_sent_money_daily.status_cancel=0 ");
               $has_id = 1;
               $w05 = " ";
               $w05_order = " ";
               $w052 = " ";
               $w_arr= " ";
      }else{

         $sTable = DB::select("
         SELECT db_sent_money_daily.*,1 as remark,remark as detail
         ,(SELECT business_location_id_fk FROM db_orders WHERE id in(db_sent_money_daily.orders_ids) limit 1) as business_location
         ,(SELECT branch_id_fk FROM db_orders WHERE id in(db_sent_money_daily.orders_ids) limit 1) as branch
         FROM db_sent_money_daily WHERE db_sent_money_daily.status_cancel=0

         $w02
         $w03
         $w04
         $w05
         $w_arr
     ");
    //  $w01
      }

      $sQuery = \DataTables::of($sTable);

      return $sQuery
      ->addColumn('column_001', function($row) {

              if(@$row->sender_id!='' && $row->remark==1){
                $sD = DB::select(" select * from ck_users_admin where id=".$row->sender_id." ");
                 return @$sD[0]->name;
              }else{
                 return '';
              }
      })
      ->escapeColumns('column_001')

      ->addColumn('column_002', function($row) {
          if($row->remark==1){
            return @$row->time_sent;
          }else{
             return '';
          }
      })
      ->escapeColumns('column_002')
      ->addColumn('date_order', function($row) use($w05,$w052) {
         if($row->remark==1){
      // วุฒิแก้ให้มันเลือกวันที่ได้
      $sDBSentMoneyDaily = DB::select("
            SELECT
            db_sent_money_daily.*,
            ck_users_admin.`name` as sender
            FROM
            db_sent_money_daily
            Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
            WHERE db_sent_money_daily.id=".$row->id."
            $w05
            order by db_sent_money_daily.time_sent
      ");
           $pn = '';
            foreach(@$sDBSentMoneyDaily AS $r){
                      $sOrders = DB::select("
                            SELECT db_orders.invoice_code ,customers.prefix_name,customers.first_name,customers.last_name,date(db_orders.created_at) as date_order
                            FROM
                            db_orders Left Join customers ON db_orders.customers_id_fk = customers.id
                            where sent_money_daily_id_fk in (".$r->id.")
                            $w052
                            GROUP BY date(db_orders.created_at);
                        ");

                        $i = 1;
                        foreach ($sOrders as $key => $value) {
                            $pn .=
                              '
                              <div class="divTableRow invoice_code_list ">
                              <div class="divTableCell" style="text-align:center;">'.$value->date_order.' </div>
                              </div>
                              ';
                            $i++;
                           //  if($i==4){
                           //   break;
                           //  }

                      }

                    }
                    return $pn;
          }else{
             return '';
          }

      })
      ->escapeColumns('date_order')

      ->addColumn('column_003', function($row) use($w05,$w052) {

         if($row->remark==1){
            //       $sDBSentMoneyDaily = DB::select("
            //       SELECT
            //       db_sent_money_daily.*,
            //       ck_users_admin.`name` as sender
            //       FROM
            //       db_sent_money_daily
            //       Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
            //       WHERE date(db_sent_money_daily.updated_at)=CURDATE() AND db_sent_money_daily.id=".$row->id."
            //       order by db_sent_money_daily.time_sent
            // ");
      // วุฒิแก้ให้มันเลือกวันที่ได้
      $sDBSentMoneyDaily = DB::select("
            SELECT
            db_sent_money_daily.*,
            ck_users_admin.`name` as sender
            FROM
            db_sent_money_daily
            Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
            WHERE db_sent_money_daily.id=".$row->id."
            $w05
            order by db_sent_money_daily.time_sent
      ");

           $pn = '';
            foreach(@$sDBSentMoneyDaily AS $r){
              // dd("SELECT db_orders.invoice_code ,customers.prefix_name,customers.first_name,customers.last_name,db_orders.id
              // FROM
              // db_orders Left Join customers ON db_orders.customers_id_fk = customers.id
              // where sent_money_daily_id_fk in (".$r->id.") $w052;");
                      $sOrders = DB::select("
                            SELECT db_orders.invoice_code ,customers.prefix_name,customers.first_name,customers.last_name,db_orders.id
                            FROM
                            db_orders Left Join customers ON db_orders.customers_id_fk = customers.id
                            where sent_money_daily_id_fk in (".$r->id.") $w052;
                        ");

                        // dd($sOrders);

                        $i = 1;
                        foreach ($sOrders as $key => $value) {

                            $pn .=
                              '
                              <div class="divTableRow invoice_code_list ">
                              <div class="divTableCell" style="text-align:center;"><a href="'.url('backend/frontstore/print_receipt_022/'.$value->id).'" target="blank">'. $value->invoice_code.'</a></div>
                              </div>
                              ';
                            $i++;
                           //  if($i==4){
                           //   break;
                           //  }

                      }

                                 $arr = [];
                          foreach ($sOrders as $key => $value) {
                              array_push($arr,$value->invoice_code.' :'.(@$value->first_name.' '.@$value->last_name).'<br>');
                            }
                        $arr_inv = implode(",",$arr);



                        //  if($i>3){
                        //   $pn .=
                        //   '<div class="divTableRow  ">
                        //   <div class="divTableCell" style="text-align:center;">...</div>
                        //   </div>
                        //   ';
                        // }

                         $pn .=
                          '<input type="hidden" class="arr_inv" value="'.$arr_inv.'">
                          ';
                    }

                  if($row->status_cancel==1){
                     return " - ";
                  }else{
                     return $pn;
                  }

          }else{
             return '';
          }

      })
      ->escapeColumns('column_003')

      ->addColumn('column_004', function($row) {
        if($row->remark==1){
           return $row->created_at;
          }else{
             return '';
          }
      })
      ->addColumn('approver', function($row) {
         $name = '';
        if($row->approver!=0){
            $app_name = DB::table('ck_users_admin')->select('name')->where('id',$row->approver)->first();
            $name = $app_name->name;
        }
        return $name;
       })
       ->addColumn('approver_time', function($row) {
         $date_data = '';
        if($row->approve_date!=''){
            $date_data = $row->approve_date;
        }
        return $date_data;
       })
      ->escapeColumns('column_004')

      ->addColumn('column_005', function($row) use($w05,$w05_order,$w052) {

        if($row)

         if($row->remark==1){

         //  $sDBSentMoneyDaily = DB::select("
         //        SELECT
         //        db_sent_money_daily.*,
         //        ck_users_admin.`name` as sender
         //        FROM
         //        db_sent_money_daily
         //        Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
         //        WHERE date(db_sent_money_daily.updated_at)=CURDATE() AND db_sent_money_daily.id=".$row->id."
         //        order by db_sent_money_daily.time_sent
         //  ");

         $sDBSentMoneyDaily = DB::select("
         SELECT
         db_sent_money_daily.*,
         ck_users_admin.`name` as sender
         FROM
         db_sent_money_daily
         Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
         WHERE  db_sent_money_daily.id=".$row->id."
         $w05
         order by db_sent_money_daily.time_sent
   ");



          $arr = [];

             foreach(@$sDBSentMoneyDaily AS $r){
                      $sOrders = DB::select("
                            SELECT *
                            FROM
                            db_orders where sent_money_daily_id_fk in (".$r->id.") $w052;
                        ");

                        foreach ($sOrders as $key => $value) {
                           array_push($arr,$value->id);
                      }
                      }
                      $id = implode(',',$arr);

                       $pn =  '';

          if($id){
            $sDBFrontstoreSumCostActionUser = DB::select("
                SELECT
                db_orders.action_user,
                ck_users_admin.`name` as action_user_name,
                db_orders.pay_type_id_fk,
                dataset_pay_type.detail AS pay_type,
                date(db_orders.created_at) AS action_date,
                sum(db_orders.cash_pay) as cash_pay,
                sum(db_orders.credit_price) as credit_price,
                sum(db_orders.transfer_price) as transfer_price,
                sum(db_orders.aicash_price) as aicash_price,
                sum(db_orders.shipping_price) as shipping_price,
                sum(db_orders.fee_amt) as fee_amt,
                sum(db_orders.cash_pay+db_orders.credit_price+db_orders.transfer_price+db_orders.aicash_price) as total_price
                FROM
                db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                WHERE db_orders.pay_type_id_fk<>0 AND db_orders.id in ($id)

                AND db_orders.business_location_id_fk in(".$row->business_location.")
                GROUP BY action_user
           ");

           $pn .=   ' <div class="table-responsive">
                  <table class="table table-sm m-0">
                    <thead>
                      <tr>
                        <th>พนักงานขาย</th>
                        <th class="text-right">เงินสด</th>
                        <th class="text-right">เงินโอน</th>
                        <th class="text-right">เครดิต</th>
                        <th class="text-right">Ai-cash</th>
                      </tr>
                    </thead>

                        <tbody>';

                  $cash_pay_total = 0;
                  // if($row->id == 18){
                  //    dd($id);
                  // }
                foreach(@$sDBFrontstoreSumCostActionUser AS $r){
                  $cash_pay_total+=$r->cash_pay + $r->transfer_price+ $r->credit_price+ $r->aicash_price;
                     @$cnt_row1 += 1;
                              @$sum_cash_pay += $r->cash_pay;
                              @$sum_aicash_price += $r->aicash_price;
                              @$sum_transfer_price += $r->transfer_price;
                              @$sum_credit_price += $r->credit_price;
                              @$sum_shipping_price += $r->shipping_price;
                              @$sum_fee_amt += $r->fee_amt;
                              @$sum_total_price += $r->total_price;

                              $pn .=
                                '<tr>
                                <td>'.$r->action_user_name.'</td>
                                <td class="text-right"> '.number_format($r->cash_pay,2).' </td>
                                <td class="text-right"> '.number_format($r->transfer_price,2).' </td>
                                <td class="text-right"> '.number_format($r->credit_price,2).' </td>
                                <td class="text-right"> '.number_format($r->aicash_price,2).' </td>
                               </tr>';
                }

                $pn .= '<tr>
                <td class="text-right" colspan="4"><b>รวมทั้งสิ้น</b></td>
                <td class="text-right">'.number_format($cash_pay_total,2).'</td>
                </tr>';

                $pn .=   '
                  </tbody>
                  </table>
                </div>';
              }

              if($row->status_cancel==1){
                 return "<span style='color:red;'>* รายการนี้ได้ทำการยกเลิกการส่งเงิน</span>";
              }else{
                 return $pn;
              }

         }else{


         //    if($row){


         //         $pn =  '';

         //          $sDBFrontstoreSumCostActionUser = DB::select("
         //              SELECT
         //              sum(db_orders.cash_pay+db_orders.credit_price+db_orders.transfer_price+db_orders.aicash_price) as total_price
         //              FROM
         //              db_orders
         //              Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
         //              Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
         //              WHERE db_orders.pay_type_id_fk<>0 AND date(db_orders.created_at)=CURDATE()
         //              AND db_orders.business_location_id_fk in(".$row->business_location.")
         //              AND db_orders.id in(".$row->orders_ids.")
         //              GROUP BY action_user
         //         ");


         //         $pn .=   ' <div class="table-responsive">
         //                <table class="table table-sm m-0">
         //                      <tbody>';
         //              $sum = 0;
         //              foreach(@$sDBFrontstoreSumCostActionUser AS $r){

         //                      $sum += $r->total_price;
         //                      $pn .=   '
         //                         <tr>
         //                        <td colspan="6">  </td>
         //                        <td class="text-right" style="width:35%;color:black;font-size:16px;font-weight:bold;"> รวมทั้งสิ้น </td>
         //                        <td class="text-right" style="width:25%;color:black;font-size:16px;font-weight:bold;" > '.number_format($sum,2).' </td>
         //                         </tr>';

         //               }

         //              $pn .=   '
         //                </tbody>
         //                </table>
         //              </div>';

         //     return $pn;
         //   }

         }

      })
      ->escapeColumns('column_005')

      ->addColumn('column_006', function($row) {
          if($row->remark==1){
              if($row->status_cancel==1){
                 return "display:none;";
              }else{
                 return  $row->id ;
              }
          }else{
             return  "display:none;" ;
          }
      })
      ->escapeColumns('column_006')
      ->addColumn('column_007', function($row) {

        if($row->remark==1){

                if($row->status_approve==1){
                   return "<span style='color:green;'>รับเงินแล้ว</span>";
                }elseif($row->status_approve==2){
                  return "<span style='color:red;'>ไม่อนุมัติ</span>";
               }else{
                   return  "-" ;
                }

          }else{
             return  "" ;
          }

      })
      ->addColumn('detail', function($row) {
         return @$row->detail;
       })
      ->escapeColumns('column_007')
      ->addColumn('sum_total_price', function($row) use($w05,$w05_order) {

       if($row->remark==1){

                $sDBSentMoneyDaily = DB::select("
                      SELECT
                      db_sent_money_daily.*,
                      ck_users_admin.`name` as sender
                      FROM
                      db_sent_money_daily
                      Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
                      WHERE db_sent_money_daily.id=".$row->id."
                      $w05
                      order by db_sent_money_daily.time_sent
                ");
               //  WHERE date(db_sent_money_daily.updated_at)=CURDATE() AND db_sent_money_daily.id=".$row->id."
                $arr = [];

                   foreach(@$sDBSentMoneyDaily AS $r){
                            $sOrders = DB::select("
                                  SELECT *
                                  FROM
                                  db_orders where sent_money_daily_id_fk in (".$r->id.");
                              ");

                              foreach ($sOrders as $key => $value) {
                                 array_push($arr,$value->id);
                            }
                            }
                            $id = implode(',',$arr);

                            if($id){

                              $sDBFrontstoreSumCostActionUser = DB::select("
                              SELECT
                              db_orders.action_user,
                              ck_users_admin.`name` as action_user_name,
                              db_orders.pay_type_id_fk,
                              dataset_pay_type.detail AS pay_type,
                              date(db_orders.created_at) AS action_date,
                              sum(db_orders.cash_pay) as cash_pay,
                              sum(db_orders.cash_pay) as total_price
                              FROM
                              db_orders
                              Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                              Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                              WHERE db_orders.pay_type_id_fk<>0 AND db_orders.id in ($id)
                              $w05_order
                              AND db_orders.business_location_id_fk in(".$row->business_location.")
                              GROUP BY action_user
                         ");

                        //  if($row->id == 12){
                        //    dd($id);
                        //  }
                        //  $w05_order
                        //  WHERE db_orders.pay_type_id_fk<>0 AND db_orders.id in ($id) AND date(db_orders.created_at)=CURDATE()

               //    $sDBFrontstoreSumCostActionUser = DB::select("
               //        SELECT
               //        db_orders.action_user,
               //        ck_users_admin.`name` as action_user_name,
               //        db_orders.pay_type_id_fk,
               //        dataset_pay_type.detail AS pay_type,
               //        date(db_orders.created_at) AS action_date,
               //        sum(db_orders.cash_pay) as cash_pay,
               //        sum(db_orders.credit_price) as credit_price,
               //        sum(db_orders.transfer_price) as transfer_price,
               //        sum(db_orders.aicash_price) as aicash_price,
               //        sum(db_orders.shipping_price) as shipping_price,
               //        sum(db_orders.fee_amt) as fee_amt,
               //        sum(db_orders.cash_pay+db_orders.credit_price+db_orders.transfer_price+db_orders.aicash_price) as total_price
               //        FROM
               //        db_orders
               //        Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
               //        Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
               //        WHERE db_orders.pay_type_id_fk<>0 AND db_orders.id in ($id) AND date(db_orders.created_at)=CURDATE()
               //        AND db_orders.business_location_id_fk in(".$row->business_location.")
               //        GROUP BY action_user
               //   ");
               // if(!isset($sDBFrontstoreSumCostActionUser[0])){
               //    return $id.' -';
               // }else{
                  return number_format(@$sDBFrontstoreSumCostActionUser[0]->total_price,2);
               // }

                    }

          }else{
             return  '' ;
          }

      })
      ->escapeColumns('sum_total_price')
      ->make(true);
    }

    public function DatatableSentMoney_ai(Request $req){

      // วุฒิทำค้นหา บิลวันที่ทำรายการก่อน
      if(!empty($req->business_location_id_fk)){
         $w012 = " AND db_add_ai_cash.business_location_id_fk=".$req->business_location_id_fk ;
      }else{
         // $w01 = "";
         $w012 = " AND db_add_ai_cash.business_location_id_fk=".\Auth::user()->business_location_id_fk;
      }
      if(!empty($req->branch_id_fk)){
         $w022 = " AND db_add_ai_cash.branch_id_fk=".$req->branch_id_fk ;
      }else{
         $w022 = "";
      }

      if(!empty($req->seller)){
         $w032 = " AND db_add_ai_cash.action_user=".$req->seller ;
      }else{
         $w032 = "";
      }

      if(!empty($req->status_search)){
         $s = $req->status_search==1?"2":"1";
         $w042 = " AND db_add_ai_cash.status_sent_money=".$s."" ;
      }else{
         $w042 = "";
      }

      if(!empty($req->startDate) && !empty($req->endDate)){
         $w052 = " AND date(db_add_ai_cash.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
      }else{
         $w052 = " AND date(db_add_ai_cash.created_at)=CURDATE() ";
      }

      // วุฒิแก้ รวมข้อมูล groupby
      $sTable2 = DB::select("
      SELECT
      db_add_ai_cash.id,
      db_add_ai_cash.sent_money_daily_id_fk
      FROM
      db_add_ai_cash
      WHERE
      db_add_ai_cash.code_order!=''
      $w012
      $w022
      $w032
      $w042
      $w052
      AND sent_money_daily_id_fk != 0
    ");

      $arr_sent = "";
      foreach($sTable2 as $key => $s2){
         if($s2!=''){
            if($key+1 == count($sTable2)){
               $arr_sent .= $s2->sent_money_daily_id_fk;
            }else{
               $arr_sent .= $s2->sent_money_daily_id_fk.',';
            }
         }
      }
      if($arr_sent!=""){
         $w_arr = "AND db_sent_money_daily_ai.id IN ($arr_sent)";
      }else{
         $w_arr = "AND db_sent_money_daily_ai.id IN (0)";
      }

      if(!empty($req->business_location_id_fk)){
         $w01 = " HAVING business_location=".$req->business_location_id_fk;
      }else{
         // $w01 = "";
         $w01 = " HAVING business_location=".\Auth::user()->business_location_id_fk;
      }

      if(!empty($req->branch_id_fk)){
         $w02 = " AND branch=".$req->branch_id_fk ;
      }else{
         $w02 = "";
      }

      if(!empty($req->seller)){
         $w03 = " AND db_sent_money_daily_ai.sender_id=".$req->seller ;
      }else{
         $w03 = "";
      }

      if(!empty($req->status_search)){
         $w04 = " AND db_sent_money_daily_ai.status_approve=".$req->status_search ;
      }else{
         $w04 = "";
      }

      $date_check = 0;
      if(!empty($req->startDate) && !empty($req->endDate)){
         // $w05 = " AND date(db_sent_money_daily.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
         $date_check = 1;
         $w05_order = " AND date(db_add_ai_cash.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
      }
      else{
         // $w05 = " AND date(db_sent_money_daily.created_at)=CURDATE() ";
         $w05_order = " AND date(db_add_ai_cash.created_at)<=CURDATE() ";
      }
      $w05 = "";

         $has_id = 0;
      if(isset($req->id)){

        $sTable = DB::select("  SELECT db_sent_money_daily_ai.*,remark as detail,1 as remark,(SELECT business_location_id_fk FROM db_add_ai_cash WHERE id in(db_sent_money_daily_ai.add_ai_ids) limit 1) as business_location FROM db_sent_money_daily_ai WHERE  id=".$req->id." AND db_sent_money_daily_ai.status_cancel=0 ");
               $has_id = 1;
               $w05 = " ";
               $w05_order = " ";
               $w052 = " ";
               $w_arr= " ";
      }else{

         $sTable = DB::select("
         SELECT db_sent_money_daily_ai.*,1 as remark,remark as detail
         ,(SELECT business_location_id_fk FROM db_add_ai_cash WHERE id in(db_sent_money_daily_ai.add_ai_ids) limit 1) as business_location
         ,(SELECT branch_id_fk FROM db_add_ai_cash WHERE id in(db_sent_money_daily_ai.add_ai_ids) limit 1) as branch
         FROM db_sent_money_daily_ai WHERE db_sent_money_daily_ai.status_cancel=0

         $w02
         $w03
         $w04
         $w05
         $w_arr
     ");
    //  $w01
      }
// dd($sTable); sum_total_price
      $sQuery = \DataTables::of($sTable);

      return $sQuery
      ->addColumn('column_001', function($row) {

              if(@$row->sender_id!='' && $row->remark==1){
                $sD = DB::select(" select * from ck_users_admin where id=".$row->sender_id." ");
                 return @$sD[0]->name;
              }else{
                 return '';
              }
      })
      ->escapeColumns('column_001')

      ->addColumn('column_002', function($row) {
          if($row->remark==1){
            return @$row->time_sent;
          }else{
             return '';
          }
      })
      ->escapeColumns('column_002')
      ->addColumn('date_order', function($row) use($w05,$w052) {
         if($row->remark==1){
      // วุฒิแก้ให้มันเลือกวันที่ได้
      $sDBSentMoneyDaily = DB::select("
            SELECT
            db_sent_money_daily_ai.*,
            ck_users_admin.`name` as sender
            FROM
            db_sent_money_daily_ai
            Left Join ck_users_admin ON db_sent_money_daily_ai.sender_id = ck_users_admin.id
            WHERE db_sent_money_daily_ai.id=".$row->id."
            $w05
            order by db_sent_money_daily_ai.time_sent
      ");
           $pn = '';
            foreach(@$sDBSentMoneyDaily AS $r){
                      $sOrders = DB::select("
                            SELECT db_add_ai_cash.code_order ,customers.prefix_name,customers.first_name,customers.last_name,date(db_add_ai_cash.created_at) as date_order
                            FROM
                            db_add_ai_cash Left Join customers ON db_add_ai_cash.customer_id_fk = customers.id
                            where sent_money_daily_id_fk in (".$r->id.")
                            $w052
                            GROUP BY date(db_add_ai_cash.created_at);
                        ");

                        $i = 1;
                        foreach ($sOrders as $key => $value) {
                            $pn .=
                              '
                              <div class="divTableRow invoice_code_list ">
                              <div class="divTableCell" style="text-align:center;">'.$value->date_order.' </div>
                              </div>
                              ';
                            $i++;
                      }

                    }
                    return $pn;
          }else{
             return '';
          }

      })
      ->escapeColumns('date_order')

      ->addColumn('column_003', function($row) use($w05,$w052) {

         if($row->remark==1){
      // วุฒิแก้ให้มันเลือกวันที่ได้
      $sDBSentMoneyDaily = DB::select("
            SELECT
            db_sent_money_daily_ai.*,
            ck_users_admin.`name` as sender
            FROM
            db_sent_money_daily_ai
            Left Join ck_users_admin ON db_sent_money_daily_ai.sender_id = ck_users_admin.id
            WHERE db_sent_money_daily_ai.id=".$row->id."
            $w05
            order by db_sent_money_daily_ai.time_sent
      ");

           $pn = '';
            foreach(@$sDBSentMoneyDaily AS $r){
                      $sOrders = DB::select("
                            SELECT db_add_ai_cash.code_order ,customers.prefix_name,customers.first_name,customers.last_name,db_add_ai_cash.id
                            FROM
                            db_add_ai_cash Left Join customers ON db_add_ai_cash.customer_id_fk = customers.id
                            where sent_money_daily_id_fk in (".$r->id.") $w052;
                        ");

                        $i = 1;
                        foreach ($sOrders as $key => $value) {

                            $pn .=
                              '
                              <div class="divTableRow invoice_code_list ">
                              <div class="divTableCell" style="text-align:center;">'. $value->code_order.'</div>
                              </div>
                              ';
                            $i++;

                      }

                                 $arr = [];
                          foreach ($sOrders as $key => $value) {
                              array_push($arr,$value->code_order.' :'.(@$value->first_name.' '.@$value->last_name).'<br>');
                            }
                        $arr_inv = implode(",",$arr);

                         $pn .=
                          '<input type="hidden" class="arr_inv" value="'.$arr_inv.'">
                          ';
                    }

                  if($row->status_cancel==1){
                     return " - ";
                  }else{
                     return $pn;
                  }

          }else{
             return '';
          }

      })
      ->escapeColumns('column_003')

      ->addColumn('column_004', function($row) {
        if($row->remark==1){
           return $row->created_at;
          }else{
             return '';
          }
      })
      ->addColumn('approver', function($row) {
         $name = '';
        if($row->approver!=0){
            $app_name = DB::table('ck_users_admin')->select('name')->where('id',$row->approver)->first();
            $name = $app_name->name;
        }
        return $name;
       })
       ->addColumn('approver_time', function($row) {
         $date_data = '';
        if($row->approve_date!=''){
            $date_data = $row->approve_date;
        }
        return $date_data;
       })
      ->escapeColumns('column_004')

      ->addColumn('column_005', function($row) use($w05,$w05_order,$w052) {

        if($row)

         if($row->remark==1){

         $sDBSentMoneyDaily = DB::select("
         SELECT
         db_sent_money_daily_ai.*,
         ck_users_admin.`name` as sender
         FROM
         db_sent_money_daily_ai
         Left Join ck_users_admin ON db_sent_money_daily_ai.sender_id = ck_users_admin.id
         WHERE  db_sent_money_daily_ai.id=".$row->id."
         $w05
         order by db_sent_money_daily_ai.time_sent
   ");

          $arr = [];

             foreach(@$sDBSentMoneyDaily AS $r){
                      $sOrders = DB::select("
                            SELECT *
                            FROM
                            db_add_ai_cash where sent_money_daily_id_fk in (".$r->id.") $w052;
                        ");

                        foreach ($sOrders as $key => $value) {
                           array_push($arr,$value->id);
                      }
                      }
                      $id = implode(',',$arr);

                       $pn =  '';

          if($id){
            $sDBFrontstoreSumCostActionUser = DB::select("
                SELECT
                db_add_ai_cash.action_user,
                ck_users_admin.`name` as action_user_name,
                db_add_ai_cash.pay_type_id_fk,
                dataset_pay_type.detail AS pay_type,
                date(db_add_ai_cash.created_at) AS action_date,
                sum(db_add_ai_cash.cash_pay) as cash_pay,
                sum(db_add_ai_cash.credit_price) as credit_price,
                sum(db_add_ai_cash.transfer_price) as transfer_price,
                sum(db_add_ai_cash.fee_amt) as fee_amt,
                sum(db_add_ai_cash.cash_pay+db_add_ai_cash.credit_price+db_add_ai_cash.transfer_price) as total_price
                FROM
                db_add_ai_cash
                Left Join dataset_pay_type ON db_add_ai_cash.pay_type_id_fk = dataset_pay_type.id
                Left Join ck_users_admin ON db_add_ai_cash.action_user = ck_users_admin.id
                WHERE db_add_ai_cash.pay_type_id_fk<>0 AND db_add_ai_cash.id in ($id)

                AND db_add_ai_cash.business_location_id_fk in(".$row->business_location.")
                GROUP BY action_user
           ");

           $pn .=   ' <div class="table-responsive">
                  <table class="table table-sm m-0">
                    <thead>
                      <tr>
                        <th>พนักงานขาย</th>
                        <th class="text-right">เงินสด</th>
                      </tr>
                    </thead>

                        <tbody>';

                  $cash_pay_total = 0;

                foreach(@$sDBFrontstoreSumCostActionUser AS $r){
                  $cash_pay_total+=$r->cash_pay;
                     @$cnt_row1 += 1;
                              @$sum_cash_pay += $r->cash_pay;
                              @$sum_transfer_price += $r->transfer_price;
                              @$sum_credit_price += $r->credit_price;
                              @$sum_fee_amt += $r->fee_amt;
                              @$sum_total_price += $r->total_price;

                              $pn .=
                                '<tr>
                                <td>'.$r->action_user_name.'</td>
                                <td class="text-right"> '.number_format($r->cash_pay,2).' </td>
                               </tr>';
                }

                $pn .= '<tr>
                <td class="text-right"><b>รวมทั้งสิ้น</b></td>
                <td class="text-right">'.number_format($cash_pay_total,2).'</td>
                </tr>';

                $pn .=   '
                  </tbody>
                  </table>
                </div>';
              }

              if($row->status_cancel==1){
                 return "<span style='color:red;'>* รายการนี้ได้ทำการยกเลิกการส่งเงิน</span>";
              }else{
                 return $pn;
              }

         }else{

         }

      })
      ->escapeColumns('column_005')

      ->addColumn('column_006', function($row) {
          if($row->remark==1){
              if($row->status_cancel==1){
                 return "display:none;";
              }else{
                 return  $row->id ;
              }
          }else{
             return  "display:none;" ;
          }
      })
      ->escapeColumns('column_006')
      ->addColumn('column_007', function($row) {

        if($row->remark==1){

                if($row->status_approve==1){
                   return "<span style='color:green;'>รับเงินแล้ว</span>";
                }elseif($row->status_approve==2){
                  return "<span style='color:red;'>ไม่อนุมัติ</span>";
               }else{
                   return  "-" ;
                }

          }else{
             return  "" ;
          }

      })
      ->addColumn('detail', function($row) {
         return @$row->detail;
       })
      ->escapeColumns('column_007')
      ->addColumn('sum_total_price', function($row) use($w05,$w05_order) {

       if($row->remark==1){

                $sDBSentMoneyDaily = DB::select("
                      SELECT
                      db_sent_money_daily_ai.*,
                      ck_users_admin.`name` as sender
                      FROM
                      db_sent_money_daily_ai
                      Left Join ck_users_admin ON db_sent_money_daily_ai.sender_id = ck_users_admin.id
                      WHERE db_sent_money_daily_ai.id=".$row->id."
                      $w05
                      order by db_sent_money_daily_ai.time_sent
                ");
               //  WHERE date(db_sent_money_daily.updated_at)=CURDATE() AND db_sent_money_daily.id=".$row->id."
                $arr = [];

                   foreach(@$sDBSentMoneyDaily AS $r){
                            $sOrders = DB::select("
                                  SELECT *
                                  FROM
                                  db_add_ai_cash where sent_money_daily_id_fk in (".$r->id.");
                              ");

                              foreach ($sOrders as $key => $value) {
                                 array_push($arr,$value->id);
                            }
                            }
                            $id = implode(',',$arr);

                            if($id){

                              $sDBFrontstoreSumCostActionUser = DB::select("
                              SELECT
                              db_add_ai_cash.action_user,
                              ck_users_admin.`name` as action_user_name,
                              db_add_ai_cash.pay_type_id_fk,
                              dataset_pay_type.detail AS pay_type,
                              date(db_add_ai_cash.created_at) AS action_date,
                              sum(db_add_ai_cash.cash_pay) as cash_pay,
                              sum(db_add_ai_cash.cash_pay) as total_price
                              FROM
                              db_add_ai_cash
                              Left Join dataset_pay_type ON db_add_ai_cash.pay_type_id_fk = dataset_pay_type.id
                              Left Join ck_users_admin ON db_add_ai_cash.action_user = ck_users_admin.id
                              WHERE db_add_ai_cash.pay_type_id_fk<>0 AND db_add_ai_cash.id in ($id)
                              $w05_order
                              AND db_add_ai_cash.business_location_id_fk in(".$row->business_location.")
                              GROUP BY action_user
                         ");


                  return number_format(@$sDBFrontstoreSumCostActionUser[0]->total_price,2);

                    }

          }else{
             return  '' ;
          }

      })
      ->escapeColumns('sum_total_price')
      ->make(true);
    }

    public function DatatableTotal(Request $req){


      // if(isset($req->business_location)){
      //    $w_b =  " AND db_orders.business_location_id_fk=".$req->business_location;
      // }else{
      //    $w_b = "";
      // }

      if(!empty($req->business_location_id_fk)){
         $w01 = " AND db_orders.business_location_id_fk=".$req->business_location_id_fk ;
      }else{
         // $w01 = "";
         $w01 = " AND db_orders.business_location_id_fk=".\Auth::user()->business_location_id_fk;
      }
      if(!empty($req->branch_id_fk)){
         $w02 = " AND db_orders.branch_id_fk=".$req->branch_id_fk ;
      }else{
         $w02 = "";
      }

      if(!empty($req->seller)){
         $w03 = " AND db_orders.action_user=".$req->seller ;
      }else{
         $w03 = "";
      }

      if(!empty($req->status_search)){
         $s = $req->status_search==1?"2":"1";
         $w04 = " AND db_orders.status_sent_money=".$s."" ;
      }else{
         $w04 = "";
      }

      if(!empty($req->startDate) && !empty($req->endDate)){
         $w05 = " AND date(db_orders.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
      }else{
         $w05 = " AND date(db_orders.created_at)=CURDATE() ";
      }


      // วุฒิแก้ รวมข้อมูล groupby
      $sTable = DB::select("

      SELECT
      db_orders.*,
      db_orders.action_user AS order_action_user,
      DATE(db_orders.created_at) AS created_date,
      dataset_business_location.txt_desc AS business_location,
      branchs.b_name AS branch_name,
      ck_users_admin.`name` as action_user,
      '' as ttp,
      1 as remark
      FROM
      db_orders
      Left Join dataset_business_location ON db_orders.business_location_id_fk = dataset_business_location.id
      Left Join branchs ON db_orders.branch_id_fk = branchs.id
      Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
      WHERE
      db_orders.invoice_code!=''
      $w01
      $w02
      $w03
      $w04
      $w05

      AND cash_pay > 0

      GROUP BY DATE(db_orders.created_at) , db_orders.action_user

      UNION ALL
      SELECT
      db_orders.*,
      db_orders.action_user AS order_action_user,
      DATE(db_orders.created_at) AS created_date,
      dataset_business_location.txt_desc AS business_location,
      branchs.b_name AS branch_name,
      ck_users_admin.`name` as action_user ,
      sum(cash_pay) as ttp,
      2 as remark
      FROM
      db_orders
      Left Join dataset_business_location ON db_orders.business_location_id_fk = dataset_business_location.id
      Left Join branchs ON db_orders.branch_id_fk = branchs.id
      Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
      WHERE
      db_orders.invoice_code!=''
      $w01
      $w02
      $w03
      $w04
      $w05

      AND cash_pay > 0

    ");

//     $text = "

//     SELECT
//     db_orders.*,
//     db_orders.action_user AS order_action_user,
//     DATE(db_orders.created_at) AS created_date,
//     dataset_business_location.txt_desc AS business_location,
//     branchs.b_name AS branch_name,
//     ck_users_admin.`name` as action_user,
//     '' as ttp,
//     1 as remark
//     FROM
//     db_orders
//     Left Join dataset_business_location ON db_orders.business_location_id_fk = dataset_business_location.id
//     Left Join branchs ON db_orders.branch_id_fk = branchs.id
//     Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
//     WHERE
//     db_orders.invoice_code!=''
//     $w01
//     $w02
//     $w03
//     $w04
//     $w05

//     AND cash_pay > 0

//     GROUP BY DATE(db_orders.created_at) , db_orders.action_user

//     UNION ALL
//     SELECT
//     db_orders.*,
//     db_orders.action_user AS order_action_user,
//     DATE(db_orders.created_at) AS created_date,
//     dataset_business_location.txt_desc AS business_location,
//     branchs.b_name AS branch_name,
//     ck_users_admin.`name` as action_user ,
//     sum(cash_pay) as ttp,
//     2 as remark
//     FROM
//     db_orders
//     Left Join dataset_business_location ON db_orders.business_location_id_fk = dataset_business_location.id
//     Left Join branchs ON db_orders.branch_id_fk = branchs.id
//     Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
//     WHERE
//     db_orders.invoice_code!=''
//     $w01
//     $w02
//     $w03
//     $w04
//     $w05

//     AND cash_pay > 0

//   ";
//   dd($text);

   //  DATE(db_orders.created_at)=CURDATE() AND db_orders.invoice_code!=''

   //  sum(sum_price) as ttp,

      // $sTable = DB::select("

      //       SELECT
      //       db_orders.*,
      //       dataset_business_location.txt_desc AS business_location,
      //       branchs.b_name AS branch_name,
      //       ck_users_admin.`name` as action_user,
      //       '' as ttp,
      //       1 as remark
      //       FROM
      //       db_orders
      //       Left Join dataset_business_location ON db_orders.business_location_id_fk = dataset_business_location.id
      //       Left Join branchs ON db_orders.branch_id_fk = branchs.id
      //       Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
      //       WHERE
      //       DATE(db_orders.created_at)=CURDATE() AND db_orders.invoice_code!=''
      //       $w01
      //       $w02
      //       $w03
      //       $w04
      //       $w05
      //       $w_b
      //       UNION ALL
      //       SELECT
      //       db_orders.*,
      //       dataset_business_location.txt_desc AS business_location,
      //       branchs.b_name AS branch_name,
      //       ck_users_admin.`name` as action_user ,
      //       sum(sum_price) as ttp,
      //       2 as remark
      //       FROM
      //       db_orders
      //       Left Join dataset_business_location ON db_orders.business_location_id_fk = dataset_business_location.id
      //       Left Join branchs ON db_orders.branch_id_fk = branchs.id
      //       Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
      //       WHERE
      //       DATE(db_orders.created_at)=CURDATE() AND db_orders.invoice_code!=''
      //       $w01
      //       $w02
      //       $w03
      //       $w04
      //       $w05
      //       $w_b
      //     ");

      $sQuery = \DataTables::of($sTable);

      return $sQuery
      ->addColumn('total_money', function($row) use($w01,$w02,$w03,$w04,$w05) {

        if($row->id){
         $date=date_create($row->created_at);
         $created_at = date_format($date,"Y-m-d");

         $data = DB::select("
         SELECT
         db_orders.id
         FROM
         db_orders
         WHERE
         db_orders.invoice_code!=''
         $w01
         $w02
         $w03
         $w04
         $w05
         AND db_orders.action_user = ".$row->order_action_user."
         AND date(db_orders.created_at)='".$created_at."'
       ");
//        $Text = "
//        SELECT
//        db_orders.id
//        FROM
//        db_orders
//        WHERE
//        db_orders.invoice_code!=''
//        $w01
//        $w02
//        $w03
//        $w04
//        $w05
//        AND db_orders.action_user = ".$row->order_action_user."
//        AND date(db_orders.created_at)='".$created_at."'
//      ";
// dd($Text);
       $arr = "";
       foreach($data as $key=>$d){
         if($key+1 == count($data)){
            $arr.=$d->id;
         }else{
            $arr.=$d->id.',';
         }

       }
         // $r = DB::select(" SELECT sum(total_price) as total_money FROM db_order_products_list WHERE frontstore_id_fk in (".$row->id.") GROUP BY frontstore_id_fk ");
         //  $r = DB::select(" SELECT sum(total_price) as total_money FROM db_order_products_list WHERE frontstore_id_fk in (".$arr.") ");

         $r = DB::select(" SELECT sum(cash_pay) as total_money FROM db_orders WHERE id in (".$arr.") ");

             if($r){
             if($row->remark==1){

                if($r[0]->total_money)
                return "<b>".number_format($r[0]->total_money,2)."</b>";

             }else{
               if($row->ttp)
               return "<b>".number_format($row->ttp,2)."</b>";

             }
           }else{
            return '';
           }
         }

      })
      ->escapeColumns('total_money')

      ->addColumn('total_money_all', function($row) use($w01,$w02,$w03,$w04,$w05) {

        if($row->id){
         $date=date_create($row->created_at);
         $created_at = date_format($date,"Y-m-d");

         $data = DB::select("
         SELECT
         db_orders.id
         FROM
         db_orders
         WHERE
         db_orders.invoice_code!=''
         $w01
         $w02
         $w03
         $w04
         $w05
         AND db_orders.action_user = ".$row->order_action_user."
         AND date(db_orders.created_at)='".$created_at."'
       ");
//        $Text = "
//        SELECT
//        db_orders.id
//        FROM
//        db_orders
//        WHERE
//        db_orders.invoice_code!=''
//        $w01
//        $w02
//        $w03
//        $w04
//        $w05
//        AND db_orders.action_user = ".$row->order_action_user."
//        AND date(db_orders.created_at)='".$created_at."'
//      ";
// dd($Text);
       $arr = "";
       foreach($data as $key=>$d){
         if($key+1 == count($data)){
            $arr.=$d->id;
         }else{
            $arr.=$d->id.',';
         }

       }
         // $r = DB::select(" SELECT sum(total_price) as total_money FROM db_order_products_list WHERE frontstore_id_fk in (".$row->id.") GROUP BY frontstore_id_fk ");
         //  $r = DB::select(" SELECT sum(total_price) as total_money FROM db_order_products_list WHERE frontstore_id_fk in (".$arr.") ");

         $r = DB::select(" SELECT sum(cash_pay) as total_money , sum(total_price) as total_price FROM db_orders WHERE id in (".$arr.") ");

             if($r){
             if($row->remark==1){

                if($r[0]->total_price)
                return "<b>".number_format($r[0]->total_price,2)."</b>";

             }else{
               if($row->ttp)
               return "<b>".number_format($row->ttp,2)."</b>";

             }
           }else{
            return '';
           }
         }

      })
      ->escapeColumns('total_money_all')

      ->addColumn('total_money_sent', function($row) use($w01,$w02,$w03,$w04,$w05) {

         if($row->id){
            $date=date_create($row->created_at);
           $created_at = date_format($date,"Y-m-d");

          $data = DB::select("
          SELECT
          db_orders.id
          FROM
          db_orders
          WHERE
          db_orders.invoice_code!=''
          $w01
          $w02
          $w03
          $w04
          $w05
          AND db_orders.action_user = ".$row->order_action_user."
          AND date(db_orders.created_at)='".$created_at."'
        ");

        $arr = "";
        foreach($data as $key=>$d){
          if($key+1 == count($data)){
             $arr.=$d->id;
          }else{
             $arr.=$d->id.',';
          }

        }


        $data2 = DB::select("
        SELECT
        db_orders.id
        FROM
        db_orders
        WHERE
        db_orders.invoice_code!=''
        $w01
        $w02
        $w03
        $w04
        $w05
      ");

      $arr2 = "";
      foreach($data2 as $key=>$d){
        if($key+1 == count($data2)){
           $arr2.=$d->id;
        }else{
           $arr2.=$d->id.',';
        }

      }
          // $r = DB::select(" SELECT sum(total_price) as total_money FROM db_order_products_list WHERE frontstore_id_fk in (".$row->id.") GROUP BY frontstore_id_fk ");
          //  $r = DB::select(" SELECT sum(total_price) as total_money FROM db_order_products_list WHERE frontstore_id_fk in (".$arr.") ");

          $r = DB::select(" SELECT sum(cash_pay) as total_money FROM db_orders WHERE id in (".$arr.") AND status_sent_money = 2");
          $r2 = DB::select(" SELECT sum(cash_pay) as total_money FROM db_orders WHERE id in (".$arr2.") AND status_sent_money = 2");

              if($r){
              if($row->remark==1){

                 if($r[0]->total_money)
                 return "<b>".number_format($r[0]->total_money,2)."</b>";
                 else return '0.00';

              }else{
                if($row->ttp)
                return "<b>".number_format($r2[0]->total_money,2)."</b>";

              }
            }else{
             return '';
            }
          }

       })
       ->escapeColumns('total_money_sent')

       ->addColumn('total_money_sent_inprocess', function($row) use($w01,$w02,$w03,$w04,$w05) {

         if($row->id){
            $date=date_create($row->created_at);
            $created_at = date_format($date,"Y-m-d");

          $data = DB::select("
          SELECT
          db_orders.id
          FROM
          db_orders
          WHERE
          db_orders.invoice_code!=''
          $w01
          $w02
          $w03
          $w04
          $w05
          AND db_orders.action_user = ".$row->order_action_user."
          AND date(db_orders.created_at)='".$created_at."'
        ");

        $arr = "";
        foreach($data as $key=>$d){
          if($key+1 == count($data)){
             $arr.=$d->id;
          }else{
             $arr.=$d->id.',';
          }

        }


        $data2 = DB::select("
        SELECT
        db_orders.id
        FROM
        db_orders
        WHERE
        db_orders.invoice_code!=''
        $w01
        $w02
        $w03
        $w04
        $w05
      ");

      $arr2 = "";
      foreach($data2 as $key=>$d){
        if($key+1 == count($data2)){
           $arr2.=$d->id;
        }else{
           $arr2.=$d->id.',';
        }

      }
          // $r = DB::select(" SELECT sum(total_price) as total_money FROM db_order_products_list WHERE frontstore_id_fk in (".$row->id.") GROUP BY frontstore_id_fk ");
          //  $r = DB::select(" SELECT sum(total_price) as total_money FROM db_order_products_list WHERE frontstore_id_fk in (".$arr.") ");

          $r = DB::select(" SELECT sum(cash_pay) as total_money FROM db_orders WHERE id in (".$arr.") AND status_sent_money in (1,2)");
          $r2 = DB::select(" SELECT sum(cash_pay) as total_money FROM db_orders WHERE id in (".$arr2.") AND status_sent_money in (1,2)");

              if($r){
              if($row->remark==1){

                 if($r[0]->total_money)
                 return "<b>".number_format($r[0]->total_money,2)."</b>";
                 else return '0.00';

              }else{
                if($row->ttp)
                return "<b>".number_format($r2[0]->total_money,2)."</b>";

              }
            }else{
             return '';
            }
          }

       })
       ->escapeColumns('total_money_sent_inprocess')

      ->make(true);
    }

    public function DatatableTotal_ai(Request $req){

      if(!empty($req->business_location_id_fk)){
         $w01 = " AND db_add_ai_cash.business_location_id_fk=".$req->business_location_id_fk ;
      }else{
         // $w01 = "";
         $w01 = " AND db_add_ai_cash.business_location_id_fk=".\Auth::user()->business_location_id_fk;
      }
      if(!empty($req->branch_id_fk)){
         $w02 = " AND db_add_ai_cash.branch_id_fk=".$req->branch_id_fk ;
      }else{
         $w02 = "";
      }

      if(!empty($req->seller)){
         $w03 = " AND db_add_ai_cash.action_user=".$req->seller ;
      }else{
         $w03 = "";
      }

      if(!empty($req->status_search)){
         $s = $req->status_search==1?"2":"1";
         $w04 = " AND db_add_ai_cash.status_sent_money=".$s."" ;
      }else{
         $w04 = "";
      }

      if(!empty($req->startDate) && !empty($req->endDate)){
         $w05 = " AND date(db_add_ai_cash.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
      }else{
         $w05 = " AND date(db_add_ai_cash.created_at)=CURDATE() ";
      }

      // วุฒิแก้ รวมข้อมูล groupby
      $sTable = DB::select("

      SELECT
      db_add_ai_cash.*,
      db_add_ai_cash.action_user AS order_action_user,
      DATE(db_add_ai_cash.created_at) AS created_date,
      dataset_business_location.txt_desc AS business_location,
      branchs.b_name AS branch_name,
      ck_users_admin.`name` as action_user,
      '' as ttp,
      1 as remark
      FROM
      db_add_ai_cash
      Left Join dataset_business_location ON db_add_ai_cash.business_location_id_fk = dataset_business_location.id
      Left Join branchs ON db_add_ai_cash.branch_id_fk = branchs.id
      Left Join ck_users_admin ON db_add_ai_cash.action_user = ck_users_admin.id
      WHERE
      db_add_ai_cash.code_order!=''
      $w01
      $w02
      $w03
      $w04
      $w05

      AND cash_pay > 0

      GROUP BY DATE(db_add_ai_cash.created_at) , db_add_ai_cash.action_user

      UNION ALL
      SELECT
      db_add_ai_cash.*,
      db_add_ai_cash.action_user AS order_action_user,
      DATE(db_add_ai_cash.created_at) AS created_date,
      dataset_business_location.txt_desc AS business_location,
      branchs.b_name AS branch_name,
      ck_users_admin.`name` as action_user ,
      sum(cash_pay) as ttp,
      2 as remark
      FROM
      db_add_ai_cash
      Left Join dataset_business_location ON db_add_ai_cash.business_location_id_fk = dataset_business_location.id
      Left Join branchs ON db_add_ai_cash.branch_id_fk = branchs.id
      Left Join ck_users_admin ON db_add_ai_cash.action_user = ck_users_admin.id
      WHERE
      db_add_ai_cash.code_order!=''
      $w01
      $w02
      $w03
      $w04
      $w05

      AND cash_pay > 0

    ");

      $sQuery = \DataTables::of($sTable);

      return $sQuery
      ->addColumn('total_money', function($row) use($w01,$w02,$w03,$w04,$w05) {

        if($row->id){
         $date=date_create($row->created_at);
         $created_at = date_format($date,"Y-m-d");

         $data = DB::select("
         SELECT
         db_add_ai_cash.id
         FROM
         db_add_ai_cash
         WHERE
         db_add_ai_cash.code_order!=''
         $w01
         $w02
         $w03
         $w04
         $w05
         AND db_add_ai_cash.action_user = ".$row->order_action_user."
         AND date(db_add_ai_cash.created_at)='".$created_at."'
       ");

       $arr = "";
       foreach($data as $key=>$d){
         if($key+1 == count($data)){
            $arr.=$d->id;
         }else{
            $arr.=$d->id.',';
         }

       }

         $r = DB::select(" SELECT sum(cash_pay) as total_money FROM db_add_ai_cash WHERE id in (".$arr.") ");

             if($r){
             if($row->remark==1){

                if($r[0]->total_money)
                return "<b>".number_format($r[0]->total_money,2)."</b>";

             }else{
               if($row->ttp)
               return "<b>".number_format($row->ttp,2)."</b>";

             }
           }else{
            return '';
           }
         }

      })
      ->escapeColumns('total_money')

      ->addColumn('total_money_sent', function($row) use($w01,$w02,$w03,$w04,$w05) {

         if($row->id){
            $date=date_create($row->created_at);
           $created_at = date_format($date,"Y-m-d");

          $data = DB::select("
          SELECT
          db_add_ai_cash.id
          FROM
          db_add_ai_cash
          WHERE
          db_add_ai_cash.code_order!=''
          $w01
          $w02
          $w03
          $w04
          $w05
          AND db_add_ai_cash.action_user = ".$row->order_action_user."
          AND date(db_add_ai_cash.created_at)='".$created_at."'
        ");

        $arr = "";
        foreach($data as $key=>$d){
          if($key+1 == count($data)){
             $arr.=$d->id;
          }else{
             $arr.=$d->id.',';
          }

        }

        $data2 = DB::select("
        SELECT
        db_add_ai_cash.id
        FROM
        db_add_ai_cash
        WHERE
        db_add_ai_cash.code_order!=''
        $w01
        $w02
        $w03
        $w04
        $w05
      ");

      $arr2 = "";
      foreach($data2 as $key=>$d){
        if($key+1 == count($data2)){
           $arr2.=$d->id;
        }else{
           $arr2.=$d->id.',';
        }

      }
          // $r = DB::select(" SELECT sum(total_price) as total_money FROM db_order_products_list WHERE frontstore_id_fk in (".$row->id.") GROUP BY frontstore_id_fk ");
          //  $r = DB::select(" SELECT sum(total_price) as total_money FROM db_order_products_list WHERE frontstore_id_fk in (".$arr.") ");

          $r = DB::select(" SELECT sum(cash_pay) as total_money FROM db_add_ai_cash WHERE id in (".$arr.") AND status_sent_money = 2");
          $r2 = DB::select(" SELECT sum(cash_pay) as total_money FROM db_add_ai_cash WHERE id in (".$arr2.") AND status_sent_money = 2");

              if($r){
              if($row->remark==1){

                 if($r[0]->total_money)
                 return "<b>".number_format($r[0]->total_money,2)."</b>";
                 else return '0.00';

              }else{
                if($row->ttp)
                return "<b>".number_format($r2[0]->total_money,2)."</b>";

              }
            }else{
             return '';
            }
          }

       })
       ->escapeColumns('total_money_sent')

       ->addColumn('total_money_sent_inprocess', function($row) use($w01,$w02,$w03,$w04,$w05) {

         if($row->id){
            $date=date_create($row->created_at);
            $created_at = date_format($date,"Y-m-d");

          $data = DB::select("
          SELECT
          db_add_ai_cash.id
          FROM
          db_add_ai_cash
          WHERE
          db_add_ai_cash.code_order!=''
          $w01
          $w02
          $w03
          $w04
          $w05
          AND db_add_ai_cash.action_user = ".$row->order_action_user."
          AND date(db_add_ai_cash.created_at)='".$created_at."'
        ");

        $arr = "";
        foreach($data as $key=>$d){
          if($key+1 == count($data)){
             $arr.=$d->id;
          }else{
             $arr.=$d->id.',';
          }

        }


        $data2 = DB::select("
        SELECT
        db_add_ai_cash.id
        FROM
        db_add_ai_cash
        WHERE
        db_add_ai_cash.code_order!=''
        $w01
        $w02
        $w03
        $w04
        $w05
      ");

      $arr2 = "";
      foreach($data2 as $key=>$d){
        if($key+1 == count($data2)){
           $arr2.=$d->id;
        }else{
           $arr2.=$d->id.',';
        }

      }
          // $r = DB::select(" SELECT sum(total_price) as total_money FROM db_order_products_list WHERE frontstore_id_fk in (".$row->id.") GROUP BY frontstore_id_fk ");
          //  $r = DB::select(" SELECT sum(total_price) as total_money FROM db_order_products_list WHERE frontstore_id_fk in (".$arr.") ");

          $r = DB::select(" SELECT sum(cash_pay) as total_money FROM db_add_ai_cash WHERE id in (".$arr.") AND status_sent_money in (1,2)");
          $r2 = DB::select(" SELECT sum(cash_pay) as total_money FROM db_add_ai_cash WHERE id in (".$arr2.") AND status_sent_money in (1,2)");

              if($r){
              if($row->remark==1){

                 if($r[0]->total_money)
                 return "<b>".number_format($r[0]->total_money,2)."</b>";
                 else return '0.00';

              }else{
                if($row->ttp)
                return "<b>".number_format($r2[0]->total_money,2)."</b>";

              }
            }else{
             return '';
            }
          }

       })
       ->escapeColumns('total_money_sent_inprocess')

      ->make(true);
    }

    public function DatatableTotal_report(Request $req){

      // if(!empty($req->business_location_id_fk)){
      //    $w01 = " AND db_orders.business_location_id_fk=".$req->business_location_id_fk ;
      // }else{
      //    $w01 = "";
      // }
      // if(!empty($req->branch_id_fk)){
      //    $w02 = " AND db_orders.branch_id_fk=".$req->branch_id_fk ;
      // }else{
      //    $w02 = "";
      // }

      if(!empty($req->seller)){
         $w03 = " AND db_sent_money_daily.approver=".$req->seller ;
      }else{
         $w03 = "";
      }

      // if(!empty($req->status_search)){
      //    $s = $req->status_search==1?"2":"1";
      //    $w04 = " AND db_orders.status_sent_money=".$s."" ;
      // }else{
      //    $w04 = "";
      // }

      if(!empty($req->startDate) && !empty($req->endDate)){
         $w05 = " AND date(db_sent_money_daily.updated_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
      }else{
         $w05 = " AND date(db_sent_money_daily.updated_at)=CURDATE() ";
      }

      // if(isset($req->business_location)){
      //    $w_b =  " AND business_location=".$req->business_location;
      // }else{
         // $w_b = "";
      // }

      // $sTable = DB::table('db_sent_money_daily')->where('status_cancel',0)->where('status_approve',1)->orderBy('created_at','desc')->get();

      $sTable = DB::select("
              SELECT db_sent_money_daily.*,1 as remark,remark as detail
              ,(SELECT business_location_id_fk FROM db_orders WHERE id in(db_sent_money_daily.orders_ids) limit 1) as business_location
              ,(SELECT branch_id_fk FROM db_orders WHERE id in(db_sent_money_daily.orders_ids) limit 1) as branch_name
              ,'' as ttp
              FROM db_sent_money_daily WHERE db_sent_money_daily.status_cancel=0 AND db_sent_money_daily.status_approve=1
              $w03
              $w05
              UNION ALL
              SELECT db_sent_money_daily.*,2 as remark,remark as detail
              ,(SELECT business_location_id_fk FROM db_orders WHERE id in(db_sent_money_daily.orders_ids) limit 1) as business_location
              ,(SELECT branch_id_fk FROM db_orders WHERE id in(db_sent_money_daily.orders_ids) limit 1) as branch_name
              ,sum(db_sent_money_daily.total_money) as ttp
              FROM db_sent_money_daily WHERE db_sent_money_daily.status_cancel=0 AND db_sent_money_daily.status_approve=1
              $w03
              $w05
          ");

           //   $w01
            //   $w02
            //   $w03
            //   $w04
            //   $w05

      // $sTable = DB::select("

      //       SELECT
      //       db_orders.*,
      //       dataset_business_location.txt_desc AS business_location,
      //       branchs.b_name AS branch_name,
      //       ck_users_admin.`name` as action_user,
      //       '' as ttp,
      //       1 as remark
      //       FROM
      //       db_orders
      //       Left Join dataset_business_location ON db_orders.business_location_id_fk = dataset_business_location.id
      //       Left Join branchs ON db_orders.branch_id_fk = branchs.id
      //       Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
      //       WHERE
      //       DATE(db_orders.created_at)=CURDATE() AND db_orders.invoice_code!=''
      //       $w01
      //       $w02
      //       $w03
      //       $w04
      //       $w05
      //       $w_b
      //       UNION ALL
      //       SELECT
      //       db_orders.*,
      //       dataset_business_location.txt_desc AS business_location,
      //       branchs.b_name AS branch_name,
      //       ck_users_admin.`name` as action_user ,
      //       sum(sum_price) as ttp,
      //       2 as remark
      //       FROM
      //       db_orders
      //       Left Join dataset_business_location ON db_orders.business_location_id_fk = dataset_business_location.id
      //       Left Join branchs ON db_orders.branch_id_fk = branchs.id
      //       Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
      //       WHERE
      //       DATE(db_orders.created_at)=CURDATE() AND db_orders.invoice_code!=''
      //       $w01
      //       $w02
      //       $w03
      //       $w04
      //       $w05
      //       $w_b
      //     ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery


      ->addColumn('business_location', function($row) {
         $data = DB::table('dataset_business_location')->where('id',$row->business_location)->first();
         return @$data->txt_desc;
      })
      ->escapeColumns('business_location')

      ->addColumn('branch_name', function($row) {
         $data = DB::table('branchs')->where('id',$row->branch_name)->first();
         return @$data->b_name;
      })
      ->escapeColumns('branch_name')

      ->addColumn('action_user', function($row) {
         $data = DB::table('ck_users_admin')->where('id',$row->sender_id)->first();
         return @$data->name;
      })
      ->escapeColumns('action_user')

      ->addColumn('approve_user', function($row) {
         $data = DB::table('ck_users_admin')->where('id',$row->approver)->first();
         return @$data->name;
      })
      ->escapeColumns('approve_user')

      ->addColumn('total_money', function($row) {

         if($row->remark==1){

            if($row->total_money)
            return "<b>".number_format($row->total_money,2)."</b>";

         }else{
           if($row->ttp)
           return "<b>".number_format($row->ttp,2)."</b>";

         }
      })
      ->escapeColumns('total_money')

      ->make(true);
    }


}
