<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Session;
use App\Helpers\General;

class Po_approveController extends Controller
{

  public function index(Request $request)
  {
    General::gen_id_url();
    // $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
    //     return $query->where('id', auth()->user()->business_location_id_fk);
    // })->get();
    // $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
    //      return $query->where('id', auth()->user()->branch_id);
    //  })->get();
    $sBusiness_location = \App\Models\Backend\Business_location::get();
    $sBranchs = \App\Models\Backend\Branchs::get();


    if (@\Auth::user()->permission == 1) {
      // $code_order = DB::select(" select code_order from db_orders where pay_type_id_fk in (1,8,10,11,12) and  LENGTH(code_order)>3 order by code_order desc limit 10 ");
    } else {

      // $code_order = DB::select(" select code_order from db_orders where pay_type_id_fk in (1,8,10,11,12) and  LENGTH(code_order)>3 AND branch_id_fk=".\Auth::user()->branch_id_fk." order by code_order desc limit 10 ");
    }
    // dd($code_order);
    $sApprover = DB::select(" select * from ck_users_admin where isActive='Y' AND branch_id_fk=" . \Auth::user()->branch_id_fk . " AND id in (select transfer_amount_approver from db_orders) ");

    return View('backend.po_approve.index')->with(
      array(
        'sBusiness_location' => $sBusiness_location,
        'sBranchs' => $sBranchs,
        'sApprover' => $sApprover,
        //  'code_order'=>$code_order,
      )
    );
  }


  public function create()
  {
  }
  public function store(Request $request)
  {
  }

  public function notAprrove(Request $request)
  {
  }

  public function edit($id)
  {
    $sRow = \App\Models\Backend\Orders::find($id);

    // วุฒิสร้าง session
    $menus = DB::table('ck_backend_menu')->select('id')->where('id', 41)->first();
    Session::put('session_menu_id', $menus->id);
    Session::put('menu_id', $menus->id);
    $role_group_id = \Auth::user()->role_group_id_fk;
    $menu_permit = DB::table('role_permit')->where('role_group_id_fk', @$role_group_id)->where('menu_id_fk', @$menus->id)->first();
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

    // dd($can_approve);


    // $slip = DB::table('payment_slip')->where('order_id', '=', $id)->orderby('id', 'asc')->get();
    $slip = DB::table('payment_slip')->where('code_order', '=', $sRow->code_order)->orderby('id', 'asc')->get();
    $slip_approve = DB::table('payment_slip')->where('code_order', '=', $sRow->code_order)->whereIn('status', [2])->orderby('id', 'asc')->get();
    $slip_not_approve = DB::table('payment_slip')->where('code_order', '=', $sRow->code_order)->whereIn('status', [3])->orderby('id', 'asc')->get();

    $price = 0;
    // วุฒิเปลี่ยนเป็นยอดที่ต้องโอน
    // if ($sRow->purchase_type_id_fk == 7) {
    //     $price = number_format($sRow->sum_price, 2);
    // } else if ($sRow->purchase_type_id_fk == 5) {
    //     $total_price = $sRow->total_price - $sRow->gift_voucher_price;
    //     $price = number_format($total_price, 2);
    // } else {
    //     $price = number_format($sRow->sum_price + $sRow->shipping_price, 2);
    // }

    $sum_price = $sRow->transfer_price;
    $p_bill = "บิล : " . $sRow->code_order . ' <br> ยอดชำระ : <label style="color:red;">' . $sRow->transfer_price . ' </label><br>';

    // วุฒิเพิ่มวนเช็คว่ามีบิลไหนจ่ายพร้อมบิลนี้ไหม
    $other_bill = DB::table('db_orders')
      ->select('id','code_order','transfer_price','approve_status')
      ->where('pay_with_other_bill', 1)
      //  ->where('pay_with_other_bill_note','like','%'.$sRow->code_order.'%')
      ->where('pay_with_other_bill_note', '=', $sRow->code_order)
      ->where('approve_status', '!=', 5)
      //  ->where('approve_status',1)
      ->get();

    foreach ($other_bill as $ot) {
      if($ot->approve_status == 1 || $ot->approve_status == 2 ||$ot->approve_status == 6 ||$ot->approve_status == 9){
        $input_data ='<input type="hidden" class="order_id_approve" name="order_id_approve[]" value="'.$ot->code_order.'">';
      }
      $sum_price += $ot->transfer_price;
      $p_bill .= "บิล : " . $ot->code_order . ' '.$input_data.'<br> ยอดชำระ : <label style="color:red;"> ' . $ot->transfer_price . ' </label><br>';
    }

    $other_bill_ai = DB::table('db_add_ai_cash')
    ->select('id','code_order','transfer_price','approve_status')
      ->where('pay_with_other_bill_select', 1)
      ->where('pay_with_other_bill_code', $sRow->code_order)
      ->where('approve_status', '!=', 5)
      //  ->where('approve_status',1)
      ->get();

    foreach ($other_bill_ai as $ot) {
         if($ot->approve_status == 1 || $ot->approve_status == 2 ||$ot->approve_status == 6 ||$ot->approve_status == 9){
        $input_data ='<input type="hidden" class="order_id_approve" name="order_id_approve[]" value="'.$ot->code_order.'">';
      }
      $sum_price += $ot->transfer_price;
      $p_bill .= "บิล : " . $ot->code_order. ' '.$input_data. ' <br> ยอดชำระ : <label style="color:red;"> ' . $ot->transfer_price . ' </label><br>';
    }


    $price =  number_format($sum_price, 2);

    // $TransferBank = \App\Models\Backend\TransferBank::get();
    $sAccount_bank = \App\Models\Backend\Account_bank::get();

    return view('backend.po_approve.form')->with([
      'sRow' => $sRow,
      'p_bill' => $p_bill,
      'id' => $id,
      'slip' => $slip,
      'slip_approve' => $slip_approve,
      'slip_not_approve' => $slip_not_approve,
      'price' => $price,
      'note_fullpayonetime' => $sRow->note_fullpayonetime,
      'approval_amount_transfer' => $sRow->approval_amount_transfer > 0 ? $sRow->approval_amount_transfer : "",
      'TransferBank' => $sAccount_bank,
    ]);
  }

  public function update(Request $request, $id)
  {
    // dd($request->all());
    \DB::beginTransaction();
    try {

      // approval_amount_transfer
      $data_id = DB::table('db_orders')->where('id', $id)->first();
      if ($data_id) {
        $sRow = \App\Models\Backend\Orders::find($data_id->id);
        $sRow->approver = \Auth::user()->id;
        $sRow->updated_at = now();
        if (@request('approved') != null) {
          $sRow->status_slip = 'true';
          $sRow->order_status_id_fk = '5';
          if ($sRow->approve_status != 9) {
            $sRow->approve_status  = 2;
          }
          $sRow->transfer_bill_status  = 2;
          if (!empty($request->slip_ids)) {
            for ($i = 0; $i < count($request->slip_ids); $i++) {
              DB::table('payment_slip')->where('id', $request->slip_ids[$i])->update([
                'note' => $request->slip_note[$i],
                'code_order' => $sRow->code_order,
                'status' => 2,
                'transfer_bill_date' => $request->transfer_bill_date[$i],
              ]);
            }
          }
          // approval_amount_transfer payment_slip transfer_bill_status
          $sRow->approval_amount_transfer = $request->approval_amount_transfer;
          $sRow->approval_amount_transfer_over = $request->approval_amount_transfer_over;
          $sRow->approval_amount_transfer_over_status = $request->approval_amount_transfer_over_status;
          $sRow->account_bank_name_customer = $request->account_bank;
          $sRow->transfer_amount_approver = \Auth::user()->id;
          $sRow->transfer_bill_date  = $request->transfer_bill_date;
          $sRow->transfer_bill_approvedate = date("Y-m-d H:i:s");
          DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");
        }

        if (@request('no_approved') != null) {
          $sRow->status_slip = 'false';
          $sRow->order_status_id_fk = '3';
          $sRow->approve_status  = 1;
          // note
          $sRow->transfer_bill_status = 1;
          $sRow->status_slip = 'true';
          $sRow->approval_amount_transfer = 0;
          $sRow->approval_amount_transfer_over = 0;
          $sRow->approval_amount_transfer_over_status = 0;
          $sRow->account_bank_name_customer = 0;
          $sRow->transfer_amount_approver =  \Auth::user()->id;
          $sRow->transfer_bill_date  = NULL;
          $sRow->transfer_bill_approvedate = NULL;
          $sRow->transfer_bill_note = @request('detail');
          DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");
          $sRow->approve_status = 6;
          $sRow->status_slip = false;

          if ($sRow->order_channel == 'MEMBER') {
            $message = [
              'customers_id_fk' => $sRow->customers_id_fk,
              'topics_question' => 'เอกสารไม่ผ่านการตรวจสอบ',
              'details_question' => 'Order:' . $sRow->code_order,
              'order_code' => $sRow->code_order,
              'orders_type' => $sRow->purchase_type_id_fk,
              'type' => 'system',
              'status' => 0,
              'see_status' => 0,
            ];

            DB::table('pm')->insert($message);
          }
        }


        if (@request('approved') != null) {
          // dd('lk');
          if ($sRow->order_channel == 'VIP') {
            $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme_vip($id, \Auth::user()->id, '1', 'admin');
            if ($data['status'] != 'fail') {
              $sRow->order_status_id_fk = '5';
              // return redirect()->action('backend\Po_approveController@index')->with(['alert' => 'เกิดปัญหาไม่สามารถเติม PV ได้']);
              // return false;
            }
          } else {
            $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme($id, \Auth::user()->id, '1', 'admin');

            if ($data['status'] != 'fail') {
              $sRow->order_status_id_fk = '5';
              // return redirect()->action('backend\Po_approveController@index')->with(['alert' => 'เกิดปัญหาไม่สามารถเติม PV ได้']);
              // return false;
            }
          }
        }

        $sRow->save();

        if ($sRow->approve_status == 2) {
          // $this->fncUpdateDeliveryAddress($sRow->id);
          // $this->fncUpdateDeliveryAddressDefault($sRow->id);
          \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddress($sRow->id);
          \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddressDefault($sRow->id);
        }

        // วุฒิเพิ่มวนเช็คว่ามีบิลไหนจ่ายพร้อมบิลนี้ไหม
        $other_bill = DB::table('db_orders')->where('pay_with_other_bill', 1)->where('pay_with_other_bill_note', '=', $data_id->code_order)
          // ->where('approve_status',1)
          ->whereIn('approve_status', [1, 2, 6, 9])
          ->get();

        foreach ($other_bill as $b) {
          $sRow2 = \App\Models\Backend\Orders::find($b->id);
          $sRow2->approver = \Auth::user()->id;
          $sRow2->updated_at = now();
          if (@request('approved') != null) {
            $sRow2->status_slip = 'true';
            // $sRow2->order_status_id_fk = '5';
            if ($sRow2->approve_status != 9) {
              $sRow2->approve_status  = 2;
            }
            $sRow2->transfer_bill_status = 2;
            // if(!empty($request->slip_ids)){
            //     for ($i=0; $i < count($request->slip_ids) ; $i++) {
            //       DB::table('payment_slip')->where('id',$request->slip_ids[$i])->update([
            //           'note' => $request->slip_note[$i],
            //           'code_order' => $sRow2->code_order,
            //           'status' => 2,
            //           'transfer_bill_date' => $request->transfer_bill_date[$i],
            //       ]);
            //     }
            // }
            // วุฒ็เปลี่ยนเป็น 0
            // $sRow2->approval_amount_transfer = $sRow2->transfer_price;
            $sRow2->approval_amount_transfer = 0;
            $sRow2->approval_amount_transfer_over = 0;
            $sRow2->approval_amount_transfer_over_status = 0;
            $sRow2->account_bank_name_customer = $request->account_bank;
            $sRow2->transfer_amount_approver = \Auth::user()->id;
            $sRow2->transfer_bill_date  = $request->transfer_bill_date;
            $sRow2->transfer_bill_approvedate = date("Y-m-d H:i:s");
            DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");
          }

          if (@request('no_approved') != null) {
            $sRow2->status_slip = 'false';
            $sRow2->order_status_id_fk = '3';
            $sRow2->approve_status  = 1;
            // note
            $sRow2->transfer_bill_status = 1;
            $sRow2->status_slip = 'true';
            $sRow2->approval_amount_transfer = 0;
            $sRow2->approval_amount_transfer_over = 0;
            $sRow2->approval_amount_transfer_over_status = 0;
            $sRow2->account_bank_name_customer = 0;
            $sRow2->transfer_amount_approver =  \Auth::user()->id;
            $sRow2->transfer_bill_date  = NULL;
            $sRow2->transfer_bill_approvedate = NULL;
            $sRow2->transfer_bill_note = @request('detail');
            DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");
            $sRow2->approve_status = 6;
            $sRow2->status_slip = false;
          }


          if (@request('approved') != null) {
            // dd($sRow);
            if ($sRow2->order_channel == 'VIP') {
              $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme_vip($b->id, \Auth::user()->id, '1', 'admin');
              if ($data['status'] != 'fail') {
                $sRow2->order_status_id_fk = '5';
                // return redirect()->action('backend\Po_approveController@index')->with(['alert' => 'เกิดปัญหาไม่สามารถเติม PV ได้']);
                // return false;
              }
            } else {
              $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme($b->id, \Auth::user()->id, '1', 'admin');
              if ($data['status'] != 'fail') {
                $sRow2->order_status_id_fk = '5';
                // return redirect()->action('backend\Po_approveController@index')->with(['alert' => 'เกิดปัญหาไม่สามารถเติม PV ได้']);
                // return false;
              }
            }
          }

          $sRow2->save();
          if ($sRow2->approve_status == 2) {
            // $this->fncUpdateDeliveryAddress($sRow2->id);
            // $this->fncUpdateDeliveryAddressDefault($sRow2->id);
            \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddress($sRow2->id);
            \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddressDefault($sRow2->id);
          }
        }

        $admin_id = \Auth::user()->id;
        $ai_cash = DB::table('db_add_ai_cash')->where('pay_with_other_bill_select', 1)->where('pay_with_other_bill_code', $data_id->code_order)->get();
        foreach ($ai_cash as $ai) {

          if (@request('approved') != null) {

            $add_aicash = \App\Http\Controllers\Frontend\Fc\AicashConfirmeController::aicash_confirme($ai->id, $admin_id, 'admin', $ai->note, $pay_type_id = '1');
            $sRow = \App\Models\Backend\Add_ai_cash::find($ai->id);
            $sRow->approve_status = 2;
            $sRow->order_type_id_fk = 7;
            $sRow->approver = \Auth::user()->id;
            $sRow->approve_date = now();
            $sRow->note = $ai->note;

            if ($sRow->code_order == "") {
              $code_order = RunNumberPayment::run_number_aicash($sRow->business_location_id_fk);
              DB::select(" UPDATE db_add_ai_cash SET code_order='$code_order' WHERE (id='" . $ai->id . "') ");
            }

            $sRow->save();
          }

          if (@request('no_approved') != null) {

            // $add_aicash = \App\Http\Controllers\Frontend\Fc\AicashConfirmeController::aicash_confirme($ai->id, $admin_id, 'admin', $ai->note, $pay_type_id = '1');
            $sRow = \App\Models\Backend\Add_ai_cash::find($ai->id);
            $sRow->approve_status = 6;
            $sRow->order_type_id_fk = 7;
            $sRow->approver = \Auth::user()->id;
            $sRow->approve_date = now();
            $sRow->note = $ai->note;

            if ($sRow->code_order == "") {
              $code_order = RunNumberPayment::run_number_aicash($sRow->business_location_id_fk);
              DB::select(" UPDATE db_add_ai_cash SET code_order='$code_order' WHERE (id='" . $ai->id . "') ");
            }

            $sRow->save();
          }
        }
      } else {
      //   return response()->json([
      //     'message' => 'Id Emty',
      //     'status' => 0,
      // ]);
        return redirect()->action('backend\Po_approveController@index')->with(['alert' => 'Id Emty']);
      }

      \DB::commit();

    //   return response()->json([
    //     'message' => 'success',
    //     'status' => 1,
    // ]);
      return redirect()->action('backend\Po_approveController@index')->with(['alert' => \App\Models\Alert::Msg('success')]);
    } catch (\Exception $e) {
      echo $e->getMessage();
      \DB::rollback();
      // dd($e->getMessage());
    //   return response()->json([
    //     'message' => $e,
    //     'status' => 0,
    // ]);
      return redirect()->action('backend\Po_approveController@index')->with(['alert' => \App\Models\Alert::e($e)]);
    }
  }

  public function po_approve_update_other(Request $request)
  {
    // dd($id);
    $id = $request->id;
    // dd(explode(',',$request->order_id_approve_list));
    // dd($request->all());
    \DB::beginTransaction();
    try {

      // approval_amount_transfer
      $data_id = DB::table('db_orders')->where('id', $id)->first();
      // dd($data_id);
      if ($data_id) {
        $sRow = \App\Models\Backend\Orders::find($data_id->id);
        $sRow->approver = \Auth::user()->id;
        $sRow->updated_at = now();
        if (@request('approved') != null) {
          $sRow->status_slip = 'true';
          $sRow->order_status_id_fk = '5';
          if ($sRow->approve_status != 9) {
            $sRow->approve_status  = 2;
          }
          $sRow->transfer_bill_status  = 2;
          if (!empty($request->slip_ids)) {
            for ($i = 0; $i < count($request->slip_ids); $i++) {
              DB::table('payment_slip')->where('id', $request->slip_ids[$i])->update([
                'note' => $request->slip_note[$i],
                'code_order' => $sRow->code_order,
                'status' => 2,
                'transfer_bill_date' => $request->transfer_bill_date[$i],
              ]);
            }
          }
          // approval_amount_transfer payment_slip transfer_bill_status
          $sRow->approval_amount_transfer = $request->approval_amount_transfer;
          $sRow->approval_amount_transfer_over = $request->approval_amount_transfer_over;
          $sRow->approval_amount_transfer_over_status = $request->approval_amount_transfer_over_status;
          $sRow->account_bank_name_customer = $request->account_bank;
          $sRow->transfer_amount_approver = \Auth::user()->id;
          $sRow->transfer_bill_date  = $request->transfer_bill_date;
          $sRow->transfer_bill_approvedate = date("Y-m-d H:i:s");
          DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");
        }

        if (@request('no_approved') != null) {
          $sRow->status_slip = 'false';
          $sRow->order_status_id_fk = '3';
          $sRow->approve_status  = 1;
          // note
          $sRow->transfer_bill_status = 1;
          $sRow->status_slip = 'true';
          $sRow->approval_amount_transfer = 0;
          $sRow->approval_amount_transfer_over = 0;
          $sRow->approval_amount_transfer_over_status = 0;
          $sRow->account_bank_name_customer = 0;
          $sRow->transfer_amount_approver =  \Auth::user()->id;
          $sRow->transfer_bill_date  = NULL;
          $sRow->transfer_bill_approvedate = NULL;
          $sRow->transfer_bill_note = @request('detail');
          DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");
          $sRow->approve_status = 6;
          $sRow->status_slip = false;

          if ($sRow->order_channel == 'MEMBER') {
            $message = [
              'customers_id_fk' => $sRow->customers_id_fk,
              'topics_question' => 'เอกสารไม่ผ่านการตรวจสอบ',
              'details_question' => 'Order:' . $sRow->code_order,
              'order_code' => $sRow->code_order,
              'orders_type' => $sRow->purchase_type_id_fk,
              'type' => 'system',
              'status' => 0,
              'see_status' => 0,
            ];

            DB::table('pm')->insert($message);
          }
        }


        if (@request('approved') != null) {
          // dd('lk');
          if ($sRow->order_channel == 'VIP') {
            $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme_vip($id, \Auth::user()->id, '1', 'admin');
            if ($data['status'] != 'fail') {
              $sRow->order_status_id_fk = '5';
              // return redirect()->action('backend\Po_approveController@index')->with(['alert' => 'เกิดปัญหาไม่สามารถเติม PV ได้']);
              // return false;
            }
          } else {
            $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme($id, \Auth::user()->id, '1', 'admin');

            if ($data['status'] != 'fail') {
              $sRow->order_status_id_fk = '5';
              // return redirect()->action('backend\Po_approveController@index')->with(['alert' => 'เกิดปัญหาไม่สามารถเติม PV ได้']);
              // return false;
            }
          }
        }

        $sRow->save();

        if ($sRow->approve_status == 2) {
          // $this->fncUpdateDeliveryAddress($sRow->id);
          // $this->fncUpdateDeliveryAddressDefault($sRow->id);
          \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddress($sRow->id);
          \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddressDefault($sRow->id);
        }

        // วุฒิเพิ่มวนเช็คว่ามีบิลไหนจ่ายพร้อมบิลนี้ไหม
        // $other_bill = DB::table('db_orders')->where('pay_with_other_bill', 1)->where('pay_with_other_bill_note', '=', $data_id->code_order)
        //   // ->where('approve_status',1)
        //   ->whereIn('approve_status', [1, 2, 6, 9])
        //   ->get();

        $order_id_approve_list = explode(',',@$request->order_id_approve_list);
        $order_id_approve_list_arr = [];
        foreach($order_id_approve_list as $l){
           if($l!='' || $l!=','){
             array_push($order_id_approve_list_arr,$l);
           }
        }

        $other_bill = DB::table('db_orders')->select('id')->where('pay_with_other_bill', 1)->whereIn('code_order', $order_id_approve_list_arr)
        // ->where('approve_status',1)
        ->whereIn('approve_status', [1, 2, 6, 9])
        ->get();

        foreach ($other_bill as $b) {
          $sRow2 = \App\Models\Backend\Orders::find($b->id);
          $sRow2->approver = \Auth::user()->id;
          $sRow2->updated_at = now();
          if (@request('approved') != null) {
            $sRow2->status_slip = 'true';
            // $sRow2->order_status_id_fk = '5';
            if ($sRow2->approve_status != 9) {
              $sRow2->approve_status  = 2;
            }
            $sRow2->transfer_bill_status = 2;
            $sRow2->approval_amount_transfer = 0;
            $sRow2->approval_amount_transfer_over = 0;
            $sRow2->approval_amount_transfer_over_status = 0;
            $sRow2->account_bank_name_customer = $request->account_bank;
            $sRow2->transfer_amount_approver = \Auth::user()->id;
            $sRow2->transfer_bill_date  = $request->transfer_bill_date;
            $sRow2->transfer_bill_approvedate = date("Y-m-d H:i:s");
            DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");
          }

          if (@request('no_approved') != null) {
            $sRow2->status_slip = 'false';
            $sRow2->order_status_id_fk = '3';
            $sRow2->approve_status  = 1;
            // note
            $sRow2->transfer_bill_status = 1;
            $sRow2->status_slip = 'true';
            $sRow2->approval_amount_transfer = 0;
            $sRow2->approval_amount_transfer_over = 0;
            $sRow2->approval_amount_transfer_over_status = 0;
            $sRow2->account_bank_name_customer = 0;
            $sRow2->transfer_amount_approver =  \Auth::user()->id;
            $sRow2->transfer_bill_date  = NULL;
            $sRow2->transfer_bill_approvedate = NULL;
            $sRow2->transfer_bill_note = @request('detail');
            DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");
            $sRow2->approve_status = 6;
            $sRow2->status_slip = false;
          }

          if (@request('approved') != null) {

            if ($sRow2->order_channel == 'VIP') {
              $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme_vip($b->id, \Auth::user()->id, '1', 'admin');
              if ($data['status'] != 'fail') {
                $sRow2->order_status_id_fk = '5';
                // return redirect()->action('backend\Po_approveController@index')->with(['alert' => 'เกิดปัญหาไม่สามารถเติม PV ได้']);
                // return false;
              }
            } else {
              $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme($b->id, \Auth::user()->id, '1', 'admin');
              if ($data['status'] != 'fail') {
                $sRow2->order_status_id_fk = '5';
                // return redirect()->action('backend\Po_approveController@index')->with(['alert' => 'เกิดปัญหาไม่สามารถเติม PV ได้']);
                // return false;
              }
            }
          }

          $sRow2->save();
          if ($sRow2->approve_status == 2) {
            // $this->fncUpdateDeliveryAddress($sRow2->id);
            // $this->fncUpdateDeliveryAddressDefault($sRow2->id);
            \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddress($sRow2->id);
            \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddressDefault($sRow2->id);
          }
        }

        $admin_id = \Auth::user()->id;
        $ai_cash = DB::table('db_add_ai_cash')->where('pay_with_other_bill_select', 1)->where('pay_with_other_bill_code', $data_id->code_order)->get();
        foreach ($ai_cash as $ai) {

          if (@request('approved') != null) {

            $add_aicash = \App\Http\Controllers\Frontend\Fc\AicashConfirmeController::aicash_confirme($ai->id, $admin_id, 'admin', $ai->note, $pay_type_id = '1');
            $sRow = \App\Models\Backend\Add_ai_cash::find($ai->id);
            $sRow->approve_status = 2;
            $sRow->order_type_id_fk = 7;
            $sRow->approver = \Auth::user()->id;
            $sRow->approve_date = now();
            $sRow->note = $ai->note;

            if ($sRow->code_order == "") {
              $code_order = RunNumberPayment::run_number_aicash($sRow->business_location_id_fk);
              DB::select(" UPDATE db_add_ai_cash SET code_order='$code_order' WHERE (id='" . $ai->id . "') ");
            }

            $sRow->save();
          }

          if (@request('no_approved') != null) {

            // $add_aicash = \App\Http\Controllers\Frontend\Fc\AicashConfirmeController::aicash_confirme($ai->id, $admin_id, 'admin', $ai->note, $pay_type_id = '1');
            $sRow = \App\Models\Backend\Add_ai_cash::find($ai->id);
            $sRow->approve_status = 6;
            $sRow->order_type_id_fk = 7;
            $sRow->approver = \Auth::user()->id;
            $sRow->approve_date = now();
            $sRow->note = $ai->note;

            if ($sRow->code_order == "") {
              $code_order = RunNumberPayment::run_number_aicash($sRow->business_location_id_fk);
              DB::select(" UPDATE db_add_ai_cash SET code_order='$code_order' WHERE (id='" . $ai->id . "') ");
            }

            $sRow->save();
          }
        }
      } else {
        return response()->json([
          'message' => 'Id Emty',
          'status' => 0,
      ]);
        // return redirect()->action('backend\Po_approveController@index')->with(['alert' => 'Id Emty']);
      }

      \DB::commit();

      return response()->json([
        'message' => 'success',
        'status' => 1,
    ]);
      // return redirect()->action('backend\Po_approveController@index')->with(['alert' => \App\Models\Alert::Msg('success')]);
    } catch (\Exception $e) {
      echo $e->getMessage();
      \DB::rollback();
      // dd($e->getMessage());
      return response()->json([
        'message' => $e,
        'status' => 0,
    ]);
      // return redirect()->action('backend\Po_approveController@index')->with(['alert' => \App\Models\Alert::e($e)]);
    }
  }


  public function form(Request $request)
  {
  }

  public function destroy($id)
  {
  }

  public function DatatableSet()
  {
    $sTable = \App\Models\Backend\Orders::search()->orderBy('id', 'asc');

    $sQuery = \DataTables::of($sTable);
    return $sQuery
      ->addColumn('price', function ($row) {
        if ($row->purchase_type_id_fk == 7) {
          return number_format($row->sum_price, 2);
        } else if ($row->purchase_type_id_fk == 5) {
          $total_price =  $row->transfer_price;
          return number_format($total_price, 2);
        } else {
          return number_format($row->sum_price + $row->shipping_price, 2);
        }
      })
      ->addColumn('type', function ($row) {
        $D = DB::table('dataset_orders_type')->where('group_id', '=', $row->purchase_type_id_fk)->get();
        return @$D[0]->orders_type;
      })
      ->addColumn('date', function ($row) {
        return date('d/m/Y H:i:s', strtotime($row->created_at));
      })
      ->make(true);
  }



  public function Datatable(Request $req)
  {
    $sPermission = auth()->user()->permission;
    $User_branch_id = auth()->user()->branch_id_fk;

    if (auth()->user()->permission == 1) {
      if (!empty($req->business_location_id_fk)) {
        $business_location_id_fk = " db_orders.business_location_id_fk = " . $req->business_location_id_fk . " ";
      } else {
        $business_location_id_fk = "";
      }

      if (!empty($req->branch_id_fk)) {
        $branch_id_fk = " db_orders.branch_id_fk = " . $req->branch_id_fk . " ";
      } else {
        $branch_id_fk = "";
      }
      $action_user = "";
    } else {
      $business_location_id_fk = " db_orders.business_location_id_fk = " . auth()->user()->business_location_id_fk . " ";
      $branch_id_fk = " db_orders.branch_id_fk = " . auth()->user()->branch_id_fk . " ";
      $action_user = " db_orders.action_user = " . auth()->user()->id . " ";
    }

    if (!empty($req->doc_id)) {
      $doc_id = " db_orders.code_order = '" . $req->doc_id . "' ";
    } else {
      $doc_id = "";
    }

    if (!empty($req->transfer_amount_approver)) {
      $transfer_amount_approver = " db_orders.transfer_amount_approver = '" . $req->transfer_amount_approver . "' ";
    } else {
      $transfer_amount_approver = "";
    }

    if (!empty($req->transfer_bill_status)) {
      $transfer_bill_status = " db_orders.transfer_bill_status = '" . $req->transfer_bill_status . "' ";
    } else {
      $transfer_bill_status = "";
    }

    if (!empty($req->bill_sdate) && !empty($req->bill_edate)) {
      $created_at = "date(db_orders.created_at) BETWEEN '" . $req->bill_sdate . "' AND '" . $req->bill_edate . "'  ";
    } else {
      $req->bill_sdate = date('Y-m-d');
      $req->bill_edate = date('Y-m-d');
      $created_at = "date(db_orders.created_at) BETWEEN '" . $req->bill_sdate . "' AND '" . $req->bill_edate . "'  ";
    }

    if (!empty($req->transfer_bill_approve_sdate) && !empty($req->transfer_bill_approve_edate)) {
      $transfer_bill_approvedate = " date(db_orders.transfer_bill_approvedate) BETWEEN '" . $req->transfer_bill_approve_sdate . "' AND '" . $req->transfer_bill_approve_edate . "'  ";
    } else {
      $transfer_bill_approvedate = "";
    }

    if (!empty($req->customer_id)) {
      $customer_id = " db_orders.customers_id_fk = '" . $req->customer_id . "' ";
    } else {
      $customer_id = "";
    }

    $branch_id_fk = "";
    $action_user = "";

    $sTable = DB::table('db_orders')
    ->select(
        'db_orders.*',
        'dataset_approve_status.txt_desc',
        'dataset_approve_status.color',
        'db_orders.id as orders_id',
        'dataset_order_status.detail',
        'dataset_order_status.css_class',
        'dataset_orders_type.orders_type as type',
        'dataset_pay_type.detail as pay_type_name',
        DB::raw("'' as sum_approval_amount_transfer"),
        DB::raw("1 as remark"),
        'branchs.b_name'
    )
    ->leftJoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
    ->leftJoin('dataset_orders_type', function($join) {
        $join->on('dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk');
        $join->where(function($query) {
            $query->where('dataset_orders_type.lang_id', '=', 1);
            $query->orWhereNull('dataset_orders_type.lang_id');
        });
    })
    ->leftJoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
    ->leftJoin('branchs', 'branchs.id', '=', 'db_orders.branch_id_fk')
    ->leftJoin('dataset_approve_status', 'dataset_approve_status.id', '=', 'db_orders.approve_status')
    ->where([
        ['pay_type_id_fk', '=', 1],
        ['dataset_order_status.lang_id', '=', 1],
        ['db_orders.id', '!=', 0]
    ])
    ->whereIn('pay_type_id_fk', [1, 8, 10, 11, 12, 20])
    ->orderByRaw("CASE WHEN approve_status = 1 THEN 1 ELSE 99 END ASC, approve_status, code_order DESC")
    ->when($business_location_id_fk, function($query, $business_location_id_fk) {
      return $query->whereRaw($business_location_id_fk);
  })
    ->when($created_at, function($query, $created_at) {
      return $query->whereRaw($created_at);
  })
    ->when($branch_id_fk, function($query, $branch_id_fk) {
        return $query->whereRaw($branch_id_fk);
    })
    ->when($doc_id, function($query, $doc_id) {
        return $query->whereRaw($doc_id);
    })
    ->when($transfer_amount_approver, function($query, $transfer_amount_approver) {
        return $query->whereRaw($transfer_amount_approver);
    })
    ->when($transfer_bill_status, function($query, $transfer_bill_status) {
        return $query->whereRaw($transfer_bill_status);
    })
    ->when($transfer_bill_approvedate, function($query, $transfer_bill_approvedate) {
        return $query->whereRaw($transfer_bill_approvedate);
    })
    ->when($customer_id, function($query, $customer_id) {
        return $query->whereRaw($customer_id);
    })
    ->orderByRaw("CASE WHEN approve_status = 1 THEN 1 ELSE 99 END ASC, approve_status, code_order DESC");

    // or
    // pay_type_id_fk in (1,8,10,11,12,20) and
    // `dataset_order_status`.`lang_id` = 1 and
    // (`dataset_orders_type`.`lang_id` = 1 or `dataset_orders_type`.`lang_id` IS NULL) and
    // `db_orders`.`id` != 0
    // $business_location_id_fk
    // $action_user
    // $doc_id
    // $transfer_amount_approver
    // $transfer_bill_status
    // $created_at
    // $transfer_bill_approvedate
    // $customer_id

    $sQuery = \DataTables::of($sTable);
    return $sQuery
      ->addColumn('created_at', function ($row) {
        return $row->created_at . '<br>' . '(' . $row->b_name . ')';
      })

      ->addColumn('customer_bank', function ($row) {
        $cus = DB::table('customers_detail')->select('bank_name', 'bank_account', 'bank_no')->where('customer_id', $row->customers_id_fk)->first();

        return 'ธนาคาร : ' . @$cus->bank_name . '<br>' . 'ชื่อบัญชี : ' . @$cus->bank_account . '<br>' . 'เลขที่บัญชี : ' . @$cus->bank_no;
        // return 'ธนาคาร : '.' '.'<br>'.'ชื่อบัญชี : '.' '.'<br>'.'เลขที่บัญชี : '.' ';
      })
      ->escapeColumns('customer_bank')

      ->addColumn('approval_amount_transfer_over', function ($row) {
        if ($row->approval_amount_transfer_over > 0) {
          if ($row->approval_amount_transfer_over_status == 0) {
            return '<label style="color:red;">' . number_format(@$row->approval_amount_transfer_over, 2) . '</label>';
          } else {
            return '<label style="color:green;">' . number_format(@$row->approval_amount_transfer_over, 2) . '</label>';
          }
        } else {
          return '<label>' . number_format(@$row->approval_amount_transfer_over, 2) . '</label>';
        }
      })
      ->escapeColumns('approval_amount_transfer_over')

      ->addColumn('price', function ($row) {
        return number_format(@$row->transfer_price, 2);
      })

      ->addColumn('customer_name', function ($row) {
        return $row->user_name . ' : ' . $row->name_customer;
        // if (!empty($row->user_id_fk)) {
        //   $user = DB::table('users')->select(DB::raw('CONCAT(name, " ", last_name) as user_full_name'))->where('id', $row->user_id_fk)->first();
        //   return $user->user_full_name;
        // }
        // if(!empty($row->customers_id_fk)){
        // @$Customer = DB::select(" select * from customers where id=".@$row->customers_id_fk." ");
        // return @$Customer[0]->user_name." : ".@$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
        //     }
      })

      ->addColumn('note_fullpayonetime', function ($row) {
        $n = '';
        $n .= $row->note_fullpayonetime_02 . "<br>";
        $n .= $row->note_fullpayonetime_03 . "<br>";
        return $row->note_fullpayonetime . "<br>" . $n;
      })
      ->escapeColumns('note_fullpayonetime')

      ->addColumn('transfer_money_datetime', function ($row) {
        $n = '';
        $n .= !empty($row->transfer_money_datetime_02) ? $row->transfer_money_datetime_02 . "<br>" : '';
        $n .= !empty($row->transfer_money_datetime_03) ? $row->transfer_money_datetime_03 . "<br>" : '';
        return $row->transfer_money_datetime . "<br>" . $n;
      })
      ->escapeColumns('transfer_money_datetime')

      ->addColumn('approval_amount_transfer', function ($row) {
        if ($row->pay_with_other_bill_note == '') {
          if (@$row->approval_amount_transfer > 0) {
            return number_format($row->approval_amount_transfer, 2);
          } else {
            return "-";
          }
        } else {
          return "-";
        }
      })
      ->escapeColumns('approval_amount_transfer')
      ->addColumn('transfer_amount_approver', function ($row) {
        if (@$row->transfer_amount_approver > 0 && @$row->transfer_amount_approver != "") {

          $sD = DB::select(" select * from ck_users_admin where id=" . $row->transfer_amount_approver . " ");
          return @$sD[0]->name;
        } else {
          return "-";
        }
      })
      ->escapeColumns('transfer_amount_approver')

      ->addColumn('transfer_bill_status', function ($row) {
        $str = "<label style='color:" . $row->color . ";'>" . $row->txt_desc . "</label>";
        if ($row->approve_status == 1 && $row->approve_one_more == 1) {
          $str .= '<br><label style="color:red;">บิลแก้ไขหลังจากอนุมัติ</label>';
        }
        return $str;
      })
      ->escapeColumns('transfer_bill_status')
      ->addColumn('transfer_bill_date', function ($row) {
        // return DB::table('payment_slip')->where('code_order', '=', $row->code_order)->where('status', 2)->orderby('id', 'desc')->value('transfer_bill_date');
        $transfer_bill_date = DB::table('payment_slip')->select('transfer_bill_date')->where('code_order', '=', $row->code_order)->where('status', 2)->orderby('id', 'desc')->first();
        return @$transfer_bill_date->transfer_bill_date;
      })

      ->addColumn('pay_with_other_bill_note', function ($row) {
        $str = '';
        if ($row->pay_with_other_bill_note != '' && $row->pay_with_other_bill_note != 0) {
          $str .= "<label>" . $row->pay_with_other_bill_note . "</label>";
        } else {

          $other_b = DB::table('db_orders')
            ->select('db_orders.code_order')
            ->where('db_orders.approve_status', '!=', 5)
            // ->where('db_orders.pay_with_other_bill_note','like','%'.$row->code_order.'%')
            ->where('db_orders.pay_with_other_bill_note', '=', $row->code_order)
            ->get();

          if (count($other_b) != 0) {
            foreach ($other_b as $b) {
              $str .= "<label>" . $b->code_order . "</label><br>";
            }
          } else {
            $str .= '';
          }
        }
        // approval_amount_transfer_over
        $ai_cash = DB::table('db_add_ai_cash')
          ->select('code_order')
          ->where('pay_with_other_bill_select', 1)
          ->where('pay_with_other_bill_code', $row->code_order)
          ->get();

        foreach ($ai_cash as $a) {
          $str .= '<label>' . $a->code_order . '</label><br>';
        }

        return $str;
      })
      ->escapeColumns('pay_with_other_bill_note')

      ->make(true);
  }




  public function DatatableEdit(Request $req)
  {

    if (!empty($req->id)) {
      $w01 = $req->id;
      $con01 = "=";
    } else {
      $w01 = "";
      $con01 = "!=";
    }

    $sTable = DB::table('db_orders')
      ->select('db_orders.*', 'db_orders.id as orders_id', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type', 'dataset_pay_type.detail as pay_type_name')
      ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
      ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
      ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
      ->where('dataset_order_status.lang_id', '=', '1')
      ->where(function ($query) {
        $query->where('dataset_orders_type.lang_id', '=', '1')
          ->orWhereNull('dataset_orders_type.lang_id');
      })
      // ->where('dataset_orders_type.lang_id', '=', '1')
      // ->where('db_orders.purchase_type_id_fk', '!=', '6')
      // ->where('db_orders.order_status_id_fk', '=', '2')
      ->where('db_orders.id', $con01, $w01)
      ->get();
    // ->toSql();
    $sQuery = \DataTables::of($sTable);
    return $sQuery
      ->addColumn('price', function ($row) {
        // วุฒิเปลี่ยนเป็นยอดที่ต้องโอน
        // if (@$row->purchase_type_id_fk == 7) {
        //     return number_format($row->sum_price, 2);
        // } else if (@$row->purchase_type_id_fk == 5) {
        //     $total_price =  $total_price = $row->transfer_price;
        //     return number_format($total_price, 2);
        // } else {
        //     return number_format(@$row->sum_price + $row->shipping_price, 2);
        // }
        return number_format(@$row->transfer_price, 2);
      })
      // ->addColumn('date', function ($row) {
      //     return date('d/m/Y H:i:s', strtotime($row->created_at));
      // })
      ->addColumn('customer_name', function ($row) {
        if (!empty($row->customers_id_fk)) {
          @$Customer = DB::select(" select * from customers where id=" . @$row->customers_id_fk . " ");
          return @$Customer[0]->user_name . " : " . @$Customer[0]->prefix_name . $Customer[0]->first_name . " " . @$Customer[0]->last_name;
        }
      })

      ->addColumn('note_fullpayonetime', function ($row) {
        $n = '';
        $n .= $row->note_fullpayonetime_02 . "<br>";
        $n .= $row->note_fullpayonetime_03 . "<br>";
        return $row->note_fullpayonetime . "<br>" . $n;
      })
      ->escapeColumns('note_fullpayonetime')

      ->addColumn('transfer_money_datetime', function ($row) {
        $n = '';
        $n .= !empty($row->transfer_money_datetime_02) ? $row->transfer_money_datetime_02 . "<br>" : '';
        $n .= !empty($row->transfer_money_datetime_03) ? $row->transfer_money_datetime_03 . "<br>" : '';
        return $row->transfer_money_datetime . "<br>" . $n;
      })
      ->escapeColumns('transfer_money_datetime')

      ->addColumn('approval_amount_transfer', function ($row) {
        if (@$row->approval_amount_transfer > 0) {
          return number_format($row->approval_amount_transfer, 2);
        } else {
          return "-";
        }
      })
      ->escapeColumns('approval_amount_transfer')
      ->addColumn('transfer_amount_approver', function ($row) {
        if (@$row->transfer_amount_approver > 0 && @$row->transfer_amount_approver != "") {

          $sD = DB::select(" select * from ck_users_admin where id=" . $row->transfer_amount_approver . " ");
          return @$sD[0]->name;
        } else {
          return "-";
        }
      })
      ->escapeColumns('transfer_amount_approver')
      ->addColumn('transfer_bill_status', function ($row) {
        if (!empty($row->transfer_bill_status)) {

          if ($row->transfer_bill_status == 1) {
            return "รออนุมัติ";
          } else if ($row->transfer_bill_status == 2) {
            return "อนุมัติแล้ว";
          } else if ($row->transfer_bill_status == 3) {
            return "ไม่อนุมัติ";
          } else {
            return '-';
          }
        }
        // return    $str = "<label style='color:".$row->color.";'>".$row->txt_desc."</label>";
      })
      ->escapeColumns('transfer_bill_status')

      ->make(true);
  }

  public function DatatableEditOther(Request $req)
  {

    if (!empty($req->id)) {
      $w01 = $req->id;
      $con01 = "=";

      $order_id = DB::table('db_orders')->where('id', $req->id)->first();
    } else {
      $w01 = "";
      $con01 = "!=";
    }

    $sTable = DB::table('db_orders')
      ->select('db_orders.*', 'db_orders.id as orders_id', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type', 'dataset_pay_type.detail as pay_type_name')
      ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
      ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
      ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
      ->where('dataset_order_status.lang_id', '=', '1')
      ->where(function ($query) {
        $query->where('dataset_orders_type.lang_id', '=', '1')
          ->orWhereNull('dataset_orders_type.lang_id');
      })
      // ->where('dataset_orders_type.lang_id', '=', '1')
      // ->where('db_orders.purchase_type_id_fk', '!=', '6')
      // ->where('db_orders.order_status_id_fk', '=', '2')
      // ->where('db_orders.id', $con01, $w01)
      ->where('db_orders.pay_with_other_bill', 1)
      ->where('db_orders.approve_status', '!=', 5)
      // ->where('db_orders.pay_with_other_bill_note','like','%'.@$order_id->code_order.'%')
      ->where('db_orders.pay_with_other_bill_note', '=', @$order_id->code_order)
      ->get();
    // ->toSql();
    $sQuery = \DataTables::of($sTable);
    return $sQuery
      ->addColumn('price', function ($row) {
        // วุฒิเปลี่ยนเป็นยอดที่ต้องโอน
        // if (@$row->purchase_type_id_fk == 7) {
        //     return number_format($row->sum_price, 2);
        // } else if (@$row->purchase_type_id_fk == 5) {
        //     $total_price = $row->transfer_price;
        //     return number_format($total_price, 2);
        // } else {
        //     return number_format(@$row->sum_price + $row->shipping_price, 2);
        // }
        return number_format(@$row->transfer_price, 2);
      })
      // ->addColumn('date', function ($row) {
      //     return date('d/m/Y H:i:s', strtotime($row->created_at));
      // })
      ->addColumn('customer_name', function ($row) {
        if (!empty($row->customers_id_fk)) {
          @$Customer = DB::select(" select * from customers where id=" . @$row->customers_id_fk . " ");
          return @$Customer[0]->user_name . " : " . @$Customer[0]->prefix_name . $Customer[0]->first_name . " " . @$Customer[0]->last_name;
        }
      })

      ->addColumn('note_fullpayonetime', function ($row) {
        $n = '';
        $n .= $row->note_fullpayonetime_02 . "<br>";
        $n .= $row->note_fullpayonetime_03 . "<br>";
        return $row->note_fullpayonetime . "<br>" . $n;
      })
      ->escapeColumns('note_fullpayonetime')

      ->addColumn('transfer_money_datetime', function ($row) {
        $n = '';
        $n .= !empty($row->transfer_money_datetime_02) ? $row->transfer_money_datetime_02 . "<br>" : '';
        $n .= !empty($row->transfer_money_datetime_03) ? $row->transfer_money_datetime_03 . "<br>" : '';
        return $row->transfer_money_datetime . "<br>" . $n;
      })
      ->escapeColumns('transfer_money_datetime')

      ->addColumn('approval_amount_transfer', function ($row) {

        if (@$row->approval_amount_transfer > 0) {
          return number_format($row->approval_amount_transfer, 2);
        } else {
          return "-";
        }
      })
      ->escapeColumns('approval_amount_transfer')
      ->addColumn('transfer_amount_approver', function ($row) {
        if (@$row->transfer_amount_approver > 0 && @$row->transfer_amount_approver != "") {

          $sD = DB::select(" select * from ck_users_admin where id=" . $row->transfer_amount_approver . " ");
          return @$sD[0]->name;
        } else {
          return "-";
        }
      })
      ->escapeColumns('transfer_amount_approver')
      ->addColumn('transfer_bill_status', function ($row) {
        if (!empty($row->transfer_bill_status)) {

          if ($row->approve_status != 5) {
            if ($row->transfer_bill_status == 1) {
              return "รออนุมัติ";
            } else if ($row->transfer_bill_status == 2) {
              return "อนุมัติแล้ว";
            } else if ($row->transfer_bill_status == 3) {
              return "ไม่อนุมัติ";
            } else {
              return '-';
            }
          } else {
            return '<label style="color:red;">บิลยกเลิก</label>';
          }
        }
        // return    $str = "<label style='color:".$row->color.";'>".$row->txt_desc."</label>";
      })
      ->escapeColumns('transfer_bill_status')

      ->make(true);
  }

  public function DatatableEditOther_ai(Request $req)
  {

    $order_id = DB::table('db_orders')->where('id', $req->id)->first();

    //     $sTable = DB::select("
    //     SELECT db_add_ai_cash.*
    //     FROM
    //     db_add_ai_cash
    //     WHERE pay_type_id_fk in (1,8,10,11,12)
    //     AND pay_with_other_bill_select = 1 AND pay_with_other_bill_code = '".$order_id->code_order."'
    //     ORDER BY db_add_ai_cash.created_at DESC
    //  ");


    $sTable = DB::table('db_orders')
      ->select('db_orders.*', 'db_orders.id as orders_id', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type', 'dataset_pay_type.detail as pay_type_name')
      ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
      ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
      ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
      ->where('dataset_order_status.lang_id', '=', '1')
      ->where(function ($query) {
        $query->where('dataset_orders_type.lang_id', '=', '1')
          ->orWhereNull('dataset_orders_type.lang_id');
      })
      // ->where('db_orders.pay_with_other_bill',1)
      // ->where('db_orders.pay_with_other_bill_note','like','%'.@$order_id->code_order.'%')
      ->where('db_orders.id', $req->id)
      ->where('aicash_price', '>', 0)
      ->get();

    $sQuery = \DataTables::of($sTable);
    return $sQuery
      ->addColumn('customer_name', function ($row) {
        if (@$row->customers_id_fk != '') {
          $Customer = DB::select(" select * from customers where id=" . @$row->customers_id_fk . " ");
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
      // ->addColumn('pay_type_id_fk', function ($row) {
      //     if (@$row->pay_type_id_fk != '') {
      //     $sD = DB::select(" select * from dataset_pay_type where id=" . $row->pay_type_id_fk . " ");
      //     return @$sD[0]->detail;
      //     } else {
      //     return '';
      //     }
      // })
      ->addColumn('aicash_remain', function ($row) {
        return $row->aicash_old;
      })
      ->addColumn('aicash_amt', function ($row) {
        return $row->aicash_price;
      })
      ->addColumn('aicash_banlance', function ($row) {
        return $row->aicash_banlance;
      })
      // ->addColumn('status', function ($row) {
      //     // if(!empty($row->bill_status)){
      //     //   if($row->bill_status==1){
      //     //     return 'รอชำระ';
      //     //   }else if($row->bill_status==2){
      //     //     return 'ชำระแล้ว';
      //     //   }else if($row->bill_status==3){
      //     //     return 'ยกเลิก';
      //     //   }
      //     // }else{
      //     // return '';
      //     // }
      //     //  `approve_status` int(11) DEFAULT '0' COMMENT 'ล้อตาม db_orders>approve_status : 1=รออนุมัติ,2=อนุมัติแล้ว,3=รอชำระ,4=รอจัดส่ง,5=ยกเลิก,6=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย > Ref>dataset_approve_status>id',
      //     if (@$row->approve_status != "") {
      //     @$approve_status = DB::select(" select * from `dataset_approve_status` where id=" . @$row->approve_status . " ");
      //     // return $purchase_type[0]->orders_type;
      //     if (@$approve_status[0]->id == 2 || @$approve_status[0]->id == 3 || @$approve_status[0]->id == 4) {
      //         return "<label style='color:green;'>อนุมัติแล้ว";
      //     } else {
      //         // return $approve_status[0]->orders_type;
      //         return "<label style='color:".@$approve_status[0]->color.";'>".@$approve_status[0]->txt_desc."</label>";
      //         // return @$approve_status[0]->txt_desc;
      //     }
      //     // return @$approve_status[0]->txt_desc;
      //     } else {
      //     return "รออนุมัติ";
      //     }
      // })
      ->addColumn('created_at', function ($row) {
        return is_null($row->created_at) ? '-' : date("Y-m-d", strtotime($row->created_at));
      })
      ->addColumn('approve_date', function ($row) {
        return is_null($row->approve_date) ? '-' : date("Y-m-d", strtotime($row->approve_date));
      })
      ->make(true);
  }

  public function DatatableEditOtherSum(Request $req)
  {

    if (!empty($req->id)) {
      $w01 = $req->id;
      $con01 = "=";

      $order_id = DB::table('db_orders')->where('id', $req->id)->first();
    } else {
      $w01 = "";
      $con01 = "!=";
    }

    $sTable = DB::table('db_orders')
      ->select('db_orders.*', 'db_orders.id as orders_id', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type', 'dataset_pay_type.detail as pay_type_name')
      ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
      ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
      ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
      ->where('dataset_order_status.lang_id', '=', '1')
      ->where(function ($query) {
        $query->where('dataset_orders_type.lang_id', '=', '1')
          ->orWhereNull('dataset_orders_type.lang_id');
      })
      ->where('db_orders.pay_with_other_bill', 1)
      // ->where('db_orders.pay_with_other_bill_note','like','%'.@$order_id->code_order.'%')
      ->where('db_orders.pay_with_other_bill_note', '=', @$order_id->code_order)
      ->where('db_orders.approve_status', '!=', 5)
      ->get();

    $sum = 0;
    foreach ($sTable as $row) {

      // if (@$row->purchase_type_id_fk == 7) {
      //     $sum += $row->sum_price;
      // } else if (@$row->purchase_type_id_fk == 5) {
      //     $total_price = $row->transfer_price;
      //     $sum += $total_price;
      // } else {
      //     $sum += @$row->sum_price + $row->shipping_price;
      // }

      $sum += $row->transfer_price;
    }


    //     $sTable = DB::select("
    //     SELECT db_add_ai_cash.*
    //     FROM
    //     db_add_ai_cash
    //     WHERE pay_type_id_fk in (1,8,10,11,12)
    //     AND pay_with_other_bill_select = 1 AND pay_with_other_bill_code = '".@$order_id->code_order."'
    //     ORDER BY db_add_ai_cash.created_at DESC
    //  ");

    //     foreach($sTable as $row){

    //         // if (@$row->purchase_type_id_fk == 7) {
    //         //     $sum += $row->sum_price;
    //         // } else if (@$row->purchase_type_id_fk == 5) {
    //         //     $total_price = $row->transfer_price;
    //         //     $sum += $total_price;
    //         // } else {
    //         //     $sum += @$row->sum_price + $row->shipping_price;
    //         // }

    //         $sum += $row->transfer_price;
    //     }


    // if (@$order_id->purchase_type_id_fk == 7) {
    //     $sum += $order_id->sum_price;
    // } else if (@$order_id->purchase_type_id_fk == 5) {
    //     $total_price = $order_id->transfer_price;
    //     $sum += $total_price;
    // } else {
    //     $sum += @$order_id->sum_price + $order_id->shipping_price;
    // }

    $sum += @$order_id->transfer_price;

    return number_format($sum, 2);
  }
}
