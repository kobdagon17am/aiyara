<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Add_ai_cashController extends Controller
{

    public function index(Request $request)
    {
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $customer = DB::select(" SELECT
              customers.user_name AS cus_code,
              customers.prefix_name,
              customers.first_name,
              customers.last_name,
              db_add_ai_cash.customer_id_fk
              FROM
              db_add_ai_cash
              left Join customers ON db_add_ai_cash.customer_id_fk = customers.id
              GROUP BY db_add_ai_cash.customer_id_fk
              ");
      // $sPay_product_status = \App\Models\Backend\Pay_product_status::get();
      $sInvoice_code = DB::select(" SELECT
        db_add_ai_cash.invoice_code
        FROM
        db_add_ai_cash where invoice_code is not null
        ");

      // $sAdmin = DB::select(" select * from ck_users_admin where isActive='Y' AND branch_id_fk=".\Auth::user()->branch_id_fk." ");
      $sApprover = DB::select(" select * from ck_users_admin where isActive='Y' AND branch_id_fk=".\Auth::user()->branch_id_fk." AND id in (select approver from db_add_ai_cash) ");

        return View('backend.add_ai_cash.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,
           'sBranchs'=>$sBranchs,
           'customer'=>$customer,
           'sInvoice_code'=>$sInvoice_code,
           'sApprover'=>$sApprover,
        ) );

    }

   public function create()
    {
      $Customer = DB::select(" select * from customers ");
      $sPay_type = DB::select(" select * from dataset_pay_type where id in(5,7,8,10); ");
      $sAccount_bank = \App\Models\Backend\Account_bank::get();
      $sFee = \App\Models\Backend\Fee::get();

      return View('backend.add_ai_cash.form')->with(
      array(
         'Customer'=>$Customer,'sPay_type'=>$sPay_type,'sAccount_bank'=>$sAccount_bank,'sFee'=>$sFee,
      ));
    }

    public function store(Request $request)
    {
      // dd($request->all());
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Add_ai_cash::find($id);
       $Customer = DB::select(" select * from customers ");
       $sPay_type = DB::select(" select * from dataset_pay_type where id in(5,7,8,10); ");

       if($sRow){
         $action_user = \App\Models\Backend\Permission\Admin::where('id', $sRow->action_user)->get();
         $action_user = @$action_user[0]->name;

        $CustomerAicash = DB::select(" select * from customers where id=".$sRow->customer_id_fk." ");

       }else{
         $action_user = NULL ;
         $CustomerAicash = NULL ;
       }


       $sAccount_bank = \App\Models\Backend\Account_bank::get();
       $sFee = \App\Models\Backend\Fee::get();



       return View('backend.add_ai_cash.form')->with(array(
          'sRow'=>$sRow,
          'id'=>$id,
          'Customer'=>$Customer,
          'action_user'=>$action_user,
          'sPay_type'=>$sPay_type,
          'sAccount_bank'=>$sAccount_bank,
          'sFee'=>$sFee,
          'CustomerAicash'=>$CustomerAicash,
      ));

    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      if(isset($request->approved)){
          // dd($request->all());
            $sRow = \App\Models\Backend\Add_ai_cash::find($request->id);
            $sRow->approver = \Auth::user()->id;
            $sRow->approve_status = $request->approve_status ;
            $sRow->approve_date = date('Y-m-d');
            $sRow->note = $request->note;
            $sRow->save();

            return redirect()->to(url("backend/add_ai_cash/".$request->id."/edit"));

      }else{
           return $this->form($id);
      }

    }

   public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow = \App\Models\Backend\Add_ai_cash::find($id);

          }else{
            $sRow = new \App\Models\Backend\Add_ai_cash;
          }
/*
1 เงินโอน
2 บัตรเครดิต
3 Ai-Cash
4  Ai Voucher
5 เงินสด
6 เงินสด + Ai-Cash
7 เครดิต + เงินสด
8 เครดิต + เงินโอน
9 เครดิต + Ai-Cash
10  เงินโอน + เงินสด
11  เงินโอน + Ai-Cash
*/
            // ประเภทการโอนเงินต้องรอ อนุมัติก่อน  approve_status
            if(request('pay_type_id_fk')==8 || request('pay_type_id_fk')==10 || request('pay_type_id_fk')==11){
               $sRow->approve_status = 0  ;
            }else if(request('pay_type_id_fk')==5 || request('pay_type_id_fk')==6 || request('pay_type_id_fk')==7 || request('pay_type_id_fk')==9){
              $sRow->approve_status = 2  ;
            }else{
              $sRow->approve_status = 1 ;
            }

           if(request('pay_type_id_fk')!=''){
              if(request('save_update')==1){

                if(request('pay_type_id_fk')==8 || request('pay_type_id_fk')==10 || request('pay_type_id_fk')==11){
                  // เป็นการโอนจะยังไม่ทำต่อจนกว่าจะผ่านการอนุมัติก่อน
                }else{
                   DB::select("
                    UPDATE
                    customers
                    Inner Join db_add_ai_cash ON customers.id = db_add_ai_cash.customer_id_fk
                    SET customers.ai_cash=(customers.ai_cash + ".str_replace(',','',request('aicash_amt')).")
                    WHERE customers.id='".request('customer_id_fk')."' AND db_add_ai_cash.upto_customer_status=0 ;
                 ");

                  DB::select(" UPDATE db_add_ai_cash SET upto_customer_status=1 WHERE db_add_ai_cash.customer_id_fk='".request('customer_id_fk')."' ");
                }


              }
           }
          // dd(str_replace(',','',request('fee_amt')));

          $sRow->customer_id_fk    = request('customer_id_fk');
          $sRow->aicash_amt    = str_replace(',','',request('aicash_amt'));
          $sRow->action_user    = request('action_user');
          $sRow->pay_type_id_fk    = request('pay_type_id_fk');
          $sRow->fee_amt    = request('fee_amt')?str_replace(',','',request('fee_amt')):0;
          $sRow->total_amt    = str_replace(',','',request('aicash_amt'))+request('fee_amt')?str_replace(',','',request('fee_amt')):0 ;

          if(!empty(request('account_bank_id'))){
            $sRow->account_bank_id = request('account_bank_id');
          }
          if(!empty(request('account_bank_id'))){
            $sRow->transfer_money_datetime = request('transfer_money_datetime');
          }

          $request = app('request');
              if ($request->hasFile('image01')) {
                @UNLINK(@$sRow->file_slip);
                $this->validate($request, [
                  'image01' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
                $image = $request->file('image01');
                $name = 'G'.time() . '.' . $image->getClientOriginalExtension();
                $image_path = 'local/public/files_slip/'.date('Ym').'/';
                $image->move($image_path, $name);
                $sRow->file_slip = $image_path.$name;
              }


          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          DB::select(" UPDATE db_add_ai_cash SET total_amt=(cash_pay + transfer_price + credit_price + fee_amt ) WHERE (id='".$sRow->id."') ");

          \DB::commit();

          if(request('fromAddAiCash')==1){
            return redirect()->to(url("backend/add_ai_cash/".$sRow->id."/edit?customer_id=".request('customer_id_fk')."&frontstore_id_fk=".request('frontstore_id_fk')."&fromAddAiCash=1"));
          }else{
            return redirect()->to(url("backend/add_ai_cash/".$sRow->id."/edit"));
          }

          // return redirect()->to(url("backend/add_ai_cash"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        // return redirect()->action('backend\Add_ai_cashController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // dd($id);
      $sRow = \App\Models\Backend\Add_ai_cash::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }

      // เช็คเรื่องการตัดยอด Ai-Cash
       $ch_aicash_01 = DB::select(" select * from customers where id=".$sRow->customer_id_fk." ");
       // return($ch_aicash_01[0]->ai_cash);
       // $ch_aicash_02 = DB::select(" select * from db_add_ai_cash where id=$id ");
       // return($ch_aicash_02[0]->aicash_amt);

       if( $ch_aicash_01[0]->ai_cash < $sRow->aicash_amt ){

       }else{
            DB::select(" UPDATE customers SET ai_cash=(ai_cash-".$sRow->aicash_amt.") where id=".$sRow->customer_id_fk." ");
            DB::select(" UPDATE db_add_ai_cash SET approve_status=5 where id=$id ");
       }

       return response()->json(\App\Models\Alert::Msg('success'));


    }

    public function Datatable(Request $req){

      // if(!empty($req->id)){

      //   $sTable = \App\Models\Backend\Add_ai_cash::where('id',$req->id);

      // }else{

      //   $sTable = \App\Models\Backend\Add_ai_cash::search()->orderBy('id', 'asc');

      // }
       $w01 = "";
       $w02 = "";
       $w03 = "";
       $w04 = "";
       $w05 = "";
       $w06 = "";
       $w07 = "";

      if(!empty($req->business_location_id_fk)){
         $w01 = " AND db_add_ai_cash.business_location_id_fk=".$req->business_location_id_fk ;
      }else{
         $w01 = "";
      }

      // if(!empty($req->branch_id_fk)){
      //    $w02 = " AND db_add_ai_cash.branch_id_fk = ".$req->branch_id_fk." " ;
      // }else{
      //    $w02 = "" ;
      // }

        if(!empty($req->customer_id_fk)){
           $w03 = " AND db_add_ai_cash.customer_id_fk LIKE '%".$req->customer_id_fk."%'  " ;
        }else{
           $w03 = "";
        }

        // if(!empty($req->bill_status)){
        //    $w04 = " AND db_add_ai_cash.bill_status = ".$req->bill_status."  " ;
        // }else{
        //    $w04 = "";
        // }

        if(!empty($req->startDate) && !empty($req->endDate)){
           $w05 = " and date(db_add_ai_cash.updated_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
        }else{
           $w05 = "";
        }

        if(!empty($req->invoice_code)){
           $w06 = " AND db_add_ai_cash.invoice_code = '".$req->invoice_code."'  " ;
        }else{
           $w06 = "";
        }
        if(!empty($req->approver)){
           $w07 = " AND db_add_ai_cash.approver = ".$req->approver." " ;
        }else{
           $w07 = "";
        }
      // $sTable = \App\Models\Backend\Add_ai_cash::search()->orderBy('updated_at', 'desc');
       $sTable = DB::select("
            SELECT db_add_ai_cash.*
            FROM
            db_add_ai_cash
            WHERE 1
            ".$w01."
            ".$w02."
            ".$w03."
            ".$w04."
            ".$w05."
            ".$w06."
            ".$w07."
            ORDER BY db_add_ai_cash.updated_at DESC
         ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('customer_name', function($row) {
        if(@$row->customer_id_fk!=''){
          $Customer = DB::select(" select * from customers where id=".@$row->customer_id_fk." ");
          return @$Customer[0]->user_name." <br> ".@$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
        }else{
          return '';
        }
      })
      ->escapeColumns('customer_name')
      ->addColumn('action_user', function($row) {
        if(@$row->action_user!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->action_user." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })
       ->addColumn('approver', function($row) {
        if(@$row->approver!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->approver." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })
      ->addColumn('pay_type_id_fk', function($row) {
        if(@$row->pay_type_id_fk!=''){
          $sD = DB::select(" select * from dataset_pay_type where id=".$row->pay_type_id_fk." ");
           return @$sD[0]->detail;
        }else{
          return '';
        }
      })
      ->addColumn('aicash_remain', function($row) {
        if(@$row->customer_id_fk!=''){
          $Customer = DB::select(" select * from customers where id=".@$row->customer_id_fk." ");
          return @$Customer[0]->ai_cash;
        }else{
          return '';
        }
      })
      ->addColumn('status', function($row) {
        // if(!empty($row->bill_status)){
        //   if($row->bill_status==1){
        //     return 'รอชำระ';
        //   }else if($row->bill_status==2){
        //     return 'ชำระแล้ว';
        //   }else if($row->bill_status==3){
        //     return 'ยกเลิก';
        //   }
        // }else{
          // return '';
        // }
//  `approve_status` int(11) DEFAULT '0' COMMENT 'ล้อตาม db_orders>approve_status : 1=รออนุมัติ,2=อนุมัติแล้ว,3=รอชำระ,4=รอจัดส่ง,5=ยกเลิก,6=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย > Ref>dataset_approve_status>id',
        if(@$row->approve_status!=""){
          @$approve_status = DB::select(" select * from `dataset_approve_status` where id=".@$row->approve_status." ");
          // return $purchase_type[0]->orders_type;
          if(@$approve_status[0]->id==2||@$approve_status[0]->id==3||@$approve_status[0]->id==4){
              return "อนุมัติแล้ว";
          }else{
              // return $approve_status[0]->orders_type;
              return @$approve_status[0]->txt_desc;
          }
          // return @$approve_status[0]->txt_desc;
        }else{
          return "No completed";
        }

      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
