<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use App\Models\Frontend\RunNumberPayment;
use Auth;
use Session;
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
    //   $sApprover = DB::select(" select * from ck_users_admin where isActive='Y' AND branch_id_fk=".\Auth::user()->branch_id_fk." AND id in (select approver from db_add_ai_cash) ");

    $sApprover = DB::select(" select * from ck_users_admin where isActive='Y' AND branch_id_fk=" . \Auth::user()->branch_id_fk . " ");

    return View('backend.add_ai_cash.index')->with(
      array(
        'sBusiness_location' => $sBusiness_location,
        'sBranchs' => $sBranchs,
        'customer' => $customer,
        'sInvoice_code' => $sInvoice_code,
        'sApprover' => $sApprover,
      )
    );
  }

  public function create()
  {
    // dd(\Auth::user()->business_location_id_fk);
    // dd(\Auth::user()->branch_id_fk);
    $sPay_type = DB::select(" select * from dataset_pay_type where id in(5,7,8,10); ");
    // dd($sPay_type);
    $sAccount_bank = \App\Models\Backend\Account_bank::get();
    $sFee = \App\Models\Backend\Fee::get();

    return View('backend.add_ai_cash.form')->with(
      array(
        // 'Customer'=>$Customer,
        'sPay_type' => $sPay_type, 'sAccount_bank' => $sAccount_bank, 'sFee' => $sFee,
      )
    );
  }

  public function store(Request $request)
  {

    return $this->form();
  }

  public function approve($id)
  {
    // dd($id);
    $sRow = \App\Models\Backend\Add_ai_cash::find($id);


    $sPay_type = DB::select(" select * from dataset_pay_type where id in(1,5,7,8,10); ");

    if ($sRow) {
      $action_user = \App\Models\Backend\Permission\Admin::where('id', $sRow->action_user)->get();
      $action_user = @$action_user[0]->name;

      $CustomerAicash = DB::select(" select * from customers where id=" . $sRow->customer_id_fk . " ");
      if ($CustomerAicash) {
        $CustomerAicashName = $CustomerAicash[0]->user_name . " : " . $CustomerAicash[0]->prefix_name . $CustomerAicash[0]->first_name . ' ' . $CustomerAicash[0]->last_name;
      } else {
        $CustomerAicashName = 'ไม่ระบุชื่อ';
      }

      if($sRow->approver!=0){
        $approve_user = \App\Models\Backend\Permission\Admin::where('id', $sRow->approver)->first();
        $approve_user = $approve_user->name;
      }else{
        $approve_user = '';
      }


    } else {
      $action_user = NULL;
      $CustomerAicash = NULL;
      $CustomerAicashName = 'ไม่ระบุชื่อ';
    }


    $sAccount_bank = \App\Models\Backend\Account_bank::get();
    $sFee = \App\Models\Backend\Fee::get();



    return View('backend.po_approve.form_aicash')->with(array(
      'sRow' => $sRow,
      'id' => $id,
      // 'Customer'=>$Customer,
      'action_user' => $action_user,
      'sPay_type' => $sPay_type,
      'sAccount_bank' => $sAccount_bank,
      'sFee' => $sFee,
      'CustomerAicash' => $CustomerAicash,
      'CustomerAicash' => $CustomerAicash,
      'CustomerAicashName' => $CustomerAicashName,
      'approve_user' => $approve_user,
    ));
  }

  public function edit(Request $request, $id)
  {

    // dd($id);
    // dd($request->all());
    $sRow = \App\Models\Backend\Add_ai_cash::find($id);
    //dd($sRow);

    $sPay_type = DB::select(" select * from dataset_pay_type where id in(1,5,7,8,10); ");

    if ($sRow) {
      $action_user = \App\Models\Backend\Permission\Admin::where('id', $sRow->action_user)->get();
      $action_user = @$action_user[0]->name;

      $CustomerAicash = DB::select(" select * from customers where id=" . $sRow->customer_id_fk . " ");
      if ($CustomerAicash) {
        $CustomerAicashName = $CustomerAicash[0]->user_name . " : " . $CustomerAicash[0]->prefix_name . $CustomerAicash[0]->first_name . ' ' . $CustomerAicash[0]->last_name;
      } else {
        $CustomerAicashName = 'ไม่ระบุชื่อ';
      }
    } else {
      $action_user = NULL;
      $CustomerAicash = NULL;
      $CustomerAicashName = 'ไม่ระบุชื่อ';
    }


    $sAccount_bank = \App\Models\Backend\Account_bank::get();
    $sFee = \App\Models\Backend\Fee::get();



    return View('backend.add_ai_cash.form')->with(array(
      'sRow' => $sRow,
      'id' => $id,
      // 'Customer'=>$Customer,
      'action_user' => $action_user,
      'sPay_type' => $sPay_type,
      'sAccount_bank' => $sAccount_bank,
      'sFee' => $sFee,
      'CustomerAicash' => $CustomerAicash,
      'CustomerAicash' => $CustomerAicash,
      'CustomerAicashName' => $CustomerAicashName,
    ));
  }

  public function update(Request $request, $id)
  {

    // dd($id);
    if (isset($request->approved)) {

      $admin_id = \Auth::user()->id;
      $add_aicash = \App\Http\Controllers\Frontend\Fc\AicashConfirmeController::aicash_confirme($request->id, $admin_id, 'admin', $request->note, $pay_type_id = '1');

      $sRow = \App\Models\Backend\Add_ai_cash::find($id);
      $sRow->approve_status = 2;
      $sRow->order_type_id_fk = 7;
      $sRow->approver = \Auth::user()->id;
      $sRow->approve_date = now();
      $sRow->note = $request->note;



      if ($sRow->code_order == "") {
        $code_order = RunNumberPayment::run_number_aicash($sRow->business_location_id_fk);
        DB::select(" UPDATE db_add_ai_cash SET code_order='$code_order' WHERE (id='" . $id . "') ");
      }

      $sRow->save();
      return redirect()->to(url("backend/po_approve"));
    } else {
      $sRow = \App\Models\Backend\Add_ai_cash::find($id);
      if ($sRow->code_order == "") {
        $code_order = RunNumberPayment::run_number_aicash($sRow->business_location_id_fk);
        DB::select(" UPDATE db_add_ai_cash SET code_order='$code_order' WHERE (id='" . $id . "') ");
      }
      $sRow->note = $request->note;

      $sRow->save();
      return $this->form($id);
    }
  }

  public function form($id = NULL)
  {

    \DB::beginTransaction();
    try {
      if ($id) {
        $sRow = \App\Models\Backend\Add_ai_cash::find($id);
      } else {
        $sRow = new \App\Models\Backend\Add_ai_cash;
      }

      $sRow->type_create = 'admin';
// approved
      // ประเภทการโอนเงินต้องรอ อนุมัติก่อน  approve_status 
      if (request('pay_type_id_fk') == 1 || request('pay_type_id_fk') == 8 || request('pay_type_id_fk') == 10 || request('pay_type_id_fk') == 11) {
        $sRow->approve_status = 1;
        $sRow->order_status_id_fk = 1;
        $sRow->approver = 0;

      } else if (request('pay_type_id_fk') == 5 || request('pay_type_id_fk') == 6 || request('pay_type_id_fk') == 7 || request('pay_type_id_fk') == 9) {
        $sRow->approve_status = 2;
        $sRow->order_status_id_fk = 7;
        $sRow->approver = \Auth::user()->id;

        if ($sRow->code_order == "") {
          $code_order = RunNumberPayment::run_number_aicash($sRow->business_location_id_fk);
          $date_setting_code = date('ym');

          $delete = DB::table('db_add_ai_cash') //update บิล
            ->where('id', $sRow->id)
            ->update([
              'code_order' => $code_order,
              'date_setting_code' => $date_setting_code,
            ]);
        }
      } else {
        $sRow->approve_status = 1;
        $sRow->order_status_id_fk = 1;
        $sRow->approver = \Auth::user()->id;

      }


      if (request('pay_type_id_fk') != '') {
        if (request('save_update') == 1) {

          if (request('pay_type_id_fk') == 1 || request('pay_type_id_fk') == 8 || request('pay_type_id_fk') == 10 || request('pay_type_id_fk') == 11) {
            // เป็นการโอนจะยังไม่ทำต่อจนกว่าจะผ่านการอนุมัติก่อน
          } else {

            $rs =  \App\Http\Controllers\Frontend\Fc\AicashConfirmeController::aicash_confirme($sRow->id, Auth::user()->id,'admin',$comment = '', request('pay_type_id_fk'));
            if ($rs['status'] == 'fail') {
              return redirect()->to(url("backend/add_ai_cash/" . $sRow->id . "/edit"))->with(['alert' => \App\Models\Alert::Msg($rs['message'])]);;
            }
            // DB::select(" UPDATE db_add_ai_cash SET upto_customer_status=1 WHERE db_add_ai_cash.customer_id_fk='".request('customer_id_fk')."' ");
          }
        }
      }

      // dd(str_replace(',','',request('aicash_amt')));
      // dd('sssss');

      $sRow->business_location_id_fk    = @\Auth::user()->business_location_id_fk;
      $sRow->branch_id_fk    = @\Auth::user()->branch_id_fk;
      $sRow->customer_id_fk    = request('customer_id_fk');
      $sRow->aicash_amt    = str_replace(',', '', request('aicash_amt'));
      $sRow->action_user    = @\Auth::user()->id;
      $sRow->pay_type_id_fk    = request('pay_type_id_fk');
      $sRow->fee_amt    = request('fee_amt') ? str_replace(',', '', request('fee_amt')) : 0;
      $sRow->total_amt    = str_replace(',', '', request('aicash_amt')) + request('fee_amt') ? str_replace(',', '', request('fee_amt')) : 0;

      if (!empty(request('account_bank_id'))) {
        $sRow->account_bank_id = request('account_bank_id');
      }
      if (!empty(request('account_bank_id'))) {
        $sRow->transfer_money_datetime = request('transfer_money_datetime');
      }


      $request = app('request');
      if ($request->hasFile('image01') == true) {
        @UNLINK(@$sRow->file_slip);
        $this->validate($request, [
          'image01' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $image = $request->file('image01');
        $name = 'G' . time() . '.' . $image->getClientOriginalExtension();
        $image_path = 'local/public/files_slip/' . date('Ym') . '/';
        $image->move($image_path, $name);
        $sRow->file_slip = $image_path . $name;
      }

      if($sRow->approve_status==0){
        $sRow->action_user    = @\Auth::user()->id;
        $sRow->created_at = date('Y-m-d H:i:s');
      }
      $sRow->save();

      DB::select(" UPDATE db_add_ai_cash SET total_amt=(cash_pay + transfer_price + credit_price + fee_amt ) WHERE (id='".$sRow->id."') ");
      \DB::commit();

      if (request('fromAddAiCash') == 1) {
        return redirect()->to(url("backend/add_ai_cash/" . $sRow->id . "/edit?customer_id=" . request('customer_id_fk') . "&frontstore_id_fk=" . request('frontstore_id_fk') . "&fromAddAiCash=1"));
      } else {
        // return redirect()->to(url("backend/add_ai_cash/".$sRow->id."/edit"));
        if (request('save_update') == 1) {
          return redirect()->to(url("backend/add_ai_cash"));
        } else {
          return redirect()->to(url("backend/add_ai_cash/" . $sRow->id . "/edit"));
        }
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

    $sRow = \App\Models\Backend\Add_ai_cash::find($id);
    // if( $sRow ){
    //   $sRow->forceDelete();
    // }
    // เช็คเรื่องการตัดยอด Ai-Cash
    $ch_aicash_01 = DB::select(" select * from customers where id=" . $sRow->customer_id_fk . " ");
    // return($ch_aicash_01[0]->ai_cash);
    // $ch_aicash_02 = DB::select(" select * from db_add_ai_cash where id=$id ");
    // return($ch_aicash_02[0]->aicash_amt);
    if ($sRow->approve_status == 1) {
      DB::select(" UPDATE db_add_ai_cash SET approve_status=5 where id=$id ");
    } else {

      if ($ch_aicash_01[0]->ai_cash < $sRow->aicash_amt) {
      } else {
        DB::select(" UPDATE customers SET ai_cash=(ai_cash-" . $sRow->aicash_amt . ") where id=" . $sRow->customer_id_fk . " ");
        DB::select(" UPDATE db_add_ai_cash SET approve_status=5 where id=$id ");
      }
    }



    return response()->json(\App\Models\Alert::Msg('success'));
  }

  public function Datatable(Request $req)
  {

    $w01 = "";
    $w02 = "";
    $w03 = "";
    $w04 = "";
    $w05 = "";
    $w06 = "";
    $w07 = "";

    $sPermission = \Auth::user()->permission;
    $User_branch_id = \Auth::user()->branch_id_fk;

    if (@\Auth::user()->permission == 1) {

      if (!empty($req->business_location_id_fk)) {
        $w01 = " and db_add_ai_cash.business_location_id_fk = " . $req->business_location_id_fk . " ";
      } else {
        $w02 = "";
      }

      if (!empty($req->branch_id_fk)) {
        $w02 = " and db_add_ai_cash.branch_id_fk = " . $req->branch_id_fk . " ";
      } else {
        $w02 = "";
      }
    } else {

      $w01 = " and db_add_ai_cash.business_location_id_fk = " . @\Auth::user()->business_location_id_fk . " ";
      $w02 = " and db_add_ai_cash.branch_id_fk = " . @\Auth::user()->branch_id_fk . " and action_user = ".Auth::user()->id."";
    }


    if (!empty($req->customer_id_fk)) {
      $w03 = " AND db_add_ai_cash.customer_id_fk LIKE '%" . $req->customer_id_fk . "%'  ";
    } else {
      $w03 = "";
    }

    if (!empty($req->approve_status)) {
      $w04 = " AND db_add_ai_cash.approve_status = " . $req->approve_status . "  ";
    } else {
      $w04 = "";
    }

    if (!empty($req->startDate) && !empty($req->endDate)) {
      $w05 = " and date(db_add_ai_cash.updated_at) BETWEEN '" . $req->startDate . "' AND '" . $req->endDate . "'  ";
    } else {
      $startDate = date('Y-m-d');
      $endDate =date('Y-m-d');
      $w05 = " and date(db_add_ai_cash.updated_at) BETWEEN '" . $startDate . "' AND '" . $endDate . "'  ";
    }

    // if(!empty($req->invoice_code)){
    //    $w06 = " AND db_add_ai_cash.invoice_code = '".$req->invoice_code."'  " ;
    // }else{
    //    $w06 = "";
    // }

    if (!empty($req->approver)) {
      $w07 = " AND db_add_ai_cash.approver = " . $req->approver . " ";
    } else {
      $w07 = "";
    }
    // $sTable = \App\Models\Backend\Add_ai_cash::search()->orderBy('updated_at', 'desc');

    //WHERE pay_type_id_fk not in (1,8,10,11,12)
    $sTable = DB::select("
         SELECT db_add_ai_cash.*
         FROM
         db_add_ai_cash where 1
         " . $w01 . "
         " . $w02 . "
         " . $w03 . "
         " . $w04 . "
         " . $w05 . "
         " . $w07 . "
         ORDER BY db_add_ai_cash.updated_at DESC
      ");

    $sQuery = \DataTables::of($sTable);
    return $sQuery
      ->addColumn('customer_name', function ($row) {
        if (@$row->customer_id_fk != '') {
          $Customer = DB::select(" select * from customers where id=" . @$row->customer_id_fk . " ");
          return @$Customer[0]->user_name . " <br> " . @$Customer[0]->prefix_name . @$Customer[0]->first_name . " " . @$Customer[0]->last_name;
        } else {
          return '';
        }
      })
      ->escapeColumns('customer_name')
      ->addColumn('action_user', function ($row) {
        if (@$row->action_user != '') {
          $sD = DB::select(" select * from ck_users_admin where id=" . $row->action_user . " ");
          return @$sD[0]->name;
        } else {
          return '';
        }
      })
      ->addColumn('approver', function ($row) {
        if (@$row->approver != '') {
          $sD = DB::select(" select * from ck_users_admin where id=" . $row->approver . " ");
          return @$sD[0]->name;
        } else {
          return '';
        }
      })
      ->addColumn('pay_type_id_fk', function ($row) {
        if (@$row->pay_type_id_fk != '') {
          $sD = DB::select(" select * from dataset_pay_type where id=" . $row->pay_type_id_fk . " ");
          return @$sD[0]->detail;
        } else {
          return '';
        }
      })
      ->addColumn('aicash_remain', function ($row) {
        if (@$row->customer_id_fk != '') {
          $Customer = DB::select(" select * from customers where id=" . @$row->customer_id_fk . " ");
          return @$Customer[0]->ai_cash;
        } else {
          return '';
        }
      })

      ->addColumn('code_order', function ($row) {
        return $row->code_order;

      })
      ->addColumn('status', function ($row) {
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
        if (@$row->approve_status != "") {
          @$approve_status = DB::select(" select * from `dataset_approve_status` where id=" . @$row->approve_status . " ");
          // return $purchase_type[0]->orders_type;
          if (@$approve_status[0]->id == 2 || @$approve_status[0]->id == 3 || @$approve_status[0]->id == 4) {
            return "อนุมัติแล้ว";
          } else {
            // return $approve_status[0]->orders_type;
            return @$approve_status[0]->txt_desc;
          }
          // return @$approve_status[0]->txt_desc;
        } else {
          return "รออนุมัติ";
        }
      })
      ->addColumn('updated_at', function ($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
  }




  public function Datatable02(Request $req)
  {

    $w01 = "";
    $w02 = "";
    $w03 = "";
    $w04 = "";
    $w05 = "";
    $w06 = "";
    $w07 = "";

    // if(!empty($req->business_location_id_fk)){
    //    $w01 = " AND db_add_ai_cash.business_location_id_fk=".$req->business_location_id_fk ;
    // }else{
    //    $w01 = "";
    // }

    // if(!empty($req->branch_id_fk)){
    //    $w02 = " AND db_add_ai_cash.branch_id_fk = ".$req->branch_id_fk." " ;
    // }else{
    //    $w02 = "" ;
    // }

    if (@\Auth::user()->permission == 1) {

      if (!empty($req->business_location_id_fk)) {
        $w01 = " and db_add_ai_cash.business_location_id_fk = " . $req->business_location_id_fk . " ";
      } else {
        $w02 = "";
      }

      if (!empty($req->branch_id_fk)) {
        $w02 = " and db_add_ai_cash.branch_id_fk = " . $req->branch_id_fk . " ";
      } else {
        $w02 = "";
      }
    } else {

      // $w01 = " and db_add_ai_cash.business_location_id_fk = " . @\Auth::user()->business_location_id_fk . " ";
      // $w02 = " and db_add_ai_cash.branch_id_fk = " . @\Auth::user()->branch_id_fk . " and action_user = ".Auth::user()->id."";
      // ทดสอบ
      $w01 = " ";
      $w02 = "";
    }

    if (!empty($req->doc_id)) {
      $w03 = " AND db_add_ai_cash.id ='" . $req->doc_id . "'  ";
    } else {
      $w03 = "";
    }

    if (!empty($req->approve_status)) {
      $w04 = " AND db_add_ai_cash.approve_status = " . $req->approve_status . "  ";
    } else {
      $w04 = "";
    }

    if (!empty($req->bill_sdate) && !empty($req->bill_edate)) {
      $w05 = " and date(db_add_ai_cash.created_at) BETWEEN '" . $req->bill_sdate . "' AND '" . $req->bill_edate . "'  ";
    } else {
      $w05 = "";
    }

    if (!empty($req->approve_sdate) && !empty($req->approve_edate)) {
      $w06 = " and date(db_add_ai_cash.approve_date) BETWEEN '" . $req->approve_sdate . "' AND '" . $req->approve_edate . "'  ";
    } else {
      $w06 = "";
    }

    if (!empty($req->approver)) {
      $w07 = " AND db_add_ai_cash.approver = " . $req->approver . " ";
    } else {
      $w07 = "";
    }

    $sTable = DB::select("
            SELECT db_add_ai_cash.*
            FROM
            db_add_ai_cash
            WHERE pay_type_id_fk in (1,8,10,11,12)
            " . $w01 . "
            " . $w02 . "
            " . $w03 . "
            " . $w04 . "
            " . $w05 . "
            " . $w06 . "
            " . $w07 . "
            ORDER BY db_add_ai_cash.updated_at DESC
         ");

    $sQuery = \DataTables::of($sTable);
    return $sQuery
      ->addColumn('customer_name', function ($row) {
        if (@$row->customer_id_fk != '') {
          $Customer = DB::select(" select * from customers where id=" . @$row->customer_id_fk . " ");
          return @$Customer[0]->user_name . " <br> " . @$Customer[0]->prefix_name . @$Customer[0]->first_name . " " . @$Customer[0]->last_name;
        } else {
          return '';
        }
      })
      ->escapeColumns('customer_name')
      ->addColumn('action_user', function ($row) {
        if (@$row->action_user != '') {
          $sD = DB::select(" select * from ck_users_admin where id=" . $row->action_user . " ");
          return @$sD[0]->name;
        } else {
          return '';
        }
      })

      ->addColumn('code_order', function ($row) {
        return $row->code_order;

      })

      ->addColumn('approver', function ($row) {
        if (@$row->approver != '') {
          $sD = DB::select(" select * from ck_users_admin where id=" . $row->approver . " ");
          return @$sD[0]->name;
        } else {
          return '-';
        }
      })
      ->addColumn('pay_type_id_fk', function ($row) {
        if (@$row->pay_type_id_fk != '') {
          $sD = DB::select(" select * from dataset_pay_type where id=" . $row->pay_type_id_fk . " ");
          return @$sD[0]->detail;
        } else {
          return '';
        }
      })
      ->addColumn('aicash_remain', function ($row) {
        if (@$row->customer_id_fk != '') {
          $Customer = DB::select(" select * from customers where id=" . @$row->customer_id_fk . " ");
          return @$Customer[0]->ai_cash;
        } else {
          return '';
        }
      })
      ->addColumn('status', function ($row) {
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
        if (@$row->approve_status != "") {
          @$approve_status = DB::select(" select * from `dataset_approve_status` where id=" . @$row->approve_status . " ");
          // return $purchase_type[0]->orders_type;
          if (@$approve_status[0]->id == 2 || @$approve_status[0]->id == 3 || @$approve_status[0]->id == 4) {
            return "อนุมัติแล้ว";
          } else {
            // return $approve_status[0]->orders_type;
            return @$approve_status[0]->txt_desc;
          }
          // return @$approve_status[0]->txt_desc;
        } else {
          return "รออนุมัติ";
        }
      })
      ->addColumn('created_at', function ($row) {
        return is_null($row->created_at) ? '-' : date("Y-m-d", strtotime($row->created_at));
      })
      ->addColumn('approve_date', function ($row) {
        return is_null($row->approve_date) ? '-' : date("Y-m-d", strtotime($row->approve_date));
      })
      ->make(true);
  }
}
