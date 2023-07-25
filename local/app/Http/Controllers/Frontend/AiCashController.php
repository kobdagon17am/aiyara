<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\Fc\CancelAicashController;
use App\Http\Controllers\Frontend\Fc\DeleteAicashController;
use App\Http\Controllers\Frontend\Ksher\KsherController;
use App\Models\Frontend\RunNumberPayment;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AiCashController extends Controller
{

  public function __construct()
  {
    $this->middleware('customer');
  }

  public function index()
  {
    // $rs = \App\Models\Frontend\CourseCheckRegis::check_register_all('1','A0000008');
    // dd($rs);

    // $rs= AicashConfirmeController::aicash_confirme($aicash_id='41',$customer_or_admin_id='99',$type_user_confirme='admin');//$type_user_confirme = "'customer','admin'"
    // dd($rs);
    $business_location_id = Auth::guard('c_user')->user()->business_location_id;
    if (empty($business_location_id)) {
        $business_location_id = 1;
    }

    $type = DB::table('dataset_orders_type')
      ->where('status', '=', 1)
      ->where('lang_id', '=', $business_location_id)
      ->whereRaw('(group_id = 1 || group_id = 2 || group_id = 3)')
      ->orderby('order')
      ->get();

    $orders_type = DB::table('dataset_orders_type')
      ->where('status', '=', '1')
      ->where('lang_id', '=',  $business_location_id)
      ->orderby('order')
      ->get();

    $pay_type = DB::table('dataset_pay_type')
      ->where('status', '=', '1')
      ->orderby('id')
      ->get();

    $data = ['orders_type' => $orders_type, 'pay_type' => $pay_type];

    return view('frontend/aicash', compact('type', 'data'));
  }

  public function cart_payment_aicash_submit(Request $rs)
  {

    if ($rs->price == '') {
      return redirect('ai-cash')->withError('Price is null');
    } else {
      //$data = ['type' => 7, 'price' => $request->price];

      DB::BeginTransaction();
      $price = str_replace(',', '', $rs->price);
      $business_location_id = Auth::guard('c_user')->user()->business_location_id;
      if (empty($business_location_id)) {
        $business_location_id = 1;
      }
      $customer_id = Auth::guard('c_user')->user()->id;
      $code_order = RunNumberPayment::run_number_aicash($business_location_id);
      try {
        $id = DB::table('db_add_ai_cash')->insertGetId(
          [
            'customer_id_fk' => $customer_id,
            'business_location_id_fk' => $business_location_id,
            'aicash_amt' => $price,
            'action_user' => $customer_id,
            'order_type_id_fk' => 7,
            'pay_type_id_fk' => $rs->pay_type,
            'code_order' => $code_order,
            'date_setting_code' => date('ym'),
            'transfer_price' => $price,
            'credit_price' => 0,
            'total_amt' => $price,
            'account_bank_id' => 1,
            'approve_status' => 0,
            'order_status_id_fk' => 1,
            'upto_customer_status' => 0,
            // 'note' => 'Add Ai-Cash',
          ]
        );

        DB::commit();
        return redirect('cart_payment_transfer_aicash/' . $code_order);
      } catch (Exception $e) {
        DB::rollback();
        return redirect('product-history')->withError('ทำรายการไม่สำเร็จกรุณาตรวจสอบรายการสั่งซื้อ');
      }
    }
  }

  public function datatable_add_aicash(Request $request)
  {
    $customer_id = Auth::guard('c_user')->user()->id;
    $business_location_id = Auth::guard('c_user')->user()->business_location_id;
    if (empty($business_location_id)) {
      $business_location_id = 1;
    }

    $ai_cash = DB::table('db_add_ai_cash')
      ->select('db_add_ai_cash.*', 'dataset_pay_type.detail as pay_type', 'dataset_order_status.detail as order_status', 'dataset_order_status.css_class')
      ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_add_ai_cash.pay_type_id_fk')
      ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_add_ai_cash.order_status_id_fk')

      ->where('db_add_ai_cash.deleted_status', '!=', 1)
      ->where('db_add_ai_cash.code_order', '!=', null)
      ->where('dataset_order_status.lang_id', '=', $business_location_id)
      ->where('db_add_ai_cash.customer_id_fk', '=', $customer_id)
      // ->whereRaw("(db_add_ai_cash.type_create != 'admin' and db_add_ai_cash.order_status_id_fk != '8')")

      ->orderby('db_add_ai_cash.created_at', 'desc')
      ->get();


    $sQuery = Datatables::of($ai_cash);
    return $sQuery
      ->addColumn('created_at', function ($row) {
        return '<span style="font-size: 13px">' . date('Y/m/d H:i:s', strtotime($row->created_at)) . '</span>';
      })

      ->addColumn('order_status', function ($row) {
        return '<span class="label label-inverse-' . $row->css_class . '"><b style="color: #000">' . $row->order_status . '</b></span>';
      })

      ->addColumn('code_order', function ($row) {
        if ($row->code_order) {
          return '<label class="label label-inverse-info-border" onclick="view_aicash(' . $row->id . ')"><a href="#!">' . $row->code_order . '</a></label>';
        }
      })

      ->addColumn('total_amt', function ($row) {
        // if ($row->order_status_id_fk == 8) {
        //     return '<b class="text-danger"> -' . number_format($row->total_amt, 2) . '</b>';
        // } else {
        //     return '<b class="text-success"> ' . number_format($row->total_amt, 2) . '</b>';
        // }
        return '<b>' . number_format($row->aicash_amt, 2) . '</b>';
      })

      ->addColumn('aicash_banlance', function ($row) {
        return '<b>' . number_format($row->aicash_banlance, 2) . '</b>';
      })

      ->addColumn('action', function ($row) {
        $button = '';

        if ($row->type_create == 'admin') {
          return $button;
        }


        if ($row->order_status_id_fk == 1 || $row->order_status_id_fk == 3) {
          //           $button .= '<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#upload_slip_aicash"
          //  onclick="upload_slip_aicash(' . $row->id . ',\'' . $row->code_order . '\')"><i class="fa fa-upload"></i> Upload </button>';

          $button .= '<a class="btn btn-sm btn-success" href="' . route('cart_payment_transfer_aicash', ['code_order' => $row->code_order]) . '" ><i class="fa fa-refresh"></i> ชำระเงิน </a> ';

          $button .= '<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete_aicash" onclick="delete_aicash(' . $row->id . ',\'' . $row->code_order . '\')"><i class="fa fa-trash"></i></button>';

          // } elseif ($row->order_status_id_fk == 7) {
          //       if (strtotime('now') < strtotime($row->cancel_expiry_date)) {
          //           $button .= '<button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#cancel_aicash"
          // onclick="cancel_aicash(' . $row->id . ',\'' . $row->code_order . '\')"><i class="fa fa-reply-all"></i> Cancel</button>';
          //       }

        } else {
        }
        return $button;
      })
      ->rawColumns(['created_at', 'order_status', 'total_amt', 'aicash_banlance', 'code_order', 'action'])
      ->make(true);
  }

  public function delete_aicash(Request $rs)
  {
    $delete_aicash_id = $rs->delete_aicash_id;
    $customer_id = Auth::guard('c_user')->user()->id;

    if ($delete_aicash_id) {
      $rs = DeleteAicashController::delete_aicash($delete_aicash_id, $customer_id, 'customer');

      if ($rs['status'] == 'success') {
        return redirect('ai-cash')->withSuccess('Delete Ai-Cash Success');
      } else {
        return redirect('ai-cash')->withError('Delete Ai-Cash  Fail : Data is null');
      }
    } else {
      return redirect('ai-cash')->withError('Delete Ai-Cash  Fail : Data is null');
    }
  }

  public function datatable_order_aicash(Request $request)
  {

    $customer_id = Auth::guard('c_user')->user()->id;
    $business_location_id = Auth::guard('c_user')->user()->business_location_id;
    if (empty($business_location_id)) {
      $business_location_id = 1;
    }

    $movement_ai_cash = DB::table('db_movement_ai_cash')
      ->select(
        'db_movement_ai_cash.*',
        'dataset_pay_type.detail as pay_type',
        'dataset_orders_type.orders_type as orders_type',
        'dataset_orders_type.icon as type_icon'
      )
      ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_movement_ai_cash.pay_type_id_fk')
      ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_movement_ai_cash.order_type_id_fk')
      ->leftjoin('db_add_ai_cash', 'db_add_ai_cash.id', '=', 'db_movement_ai_cash.add_ai_cash_id_fk')
      ->where('dataset_orders_type.lang_id', '=', $business_location_id)
      ->where('db_movement_ai_cash.customer_id_fk', '=', $customer_id)
      // ->whereRaw("(db_add_ai_cash.type_create != 'admin' and db_add_ai_cash.order_status_id_fk != '8')")
      ->orderby('db_movement_ai_cash.created_at', 'desc')
      ->get();

    $sQuery = Datatables::of($movement_ai_cash);
    return $sQuery

      ->addColumn('created_at', function ($row) {
        return '<span style="font-size: 13px">' . date('Y/m/d H:i:s', strtotime($row->created_at)) . '</span>';
      })

      ->addColumn('created_at', function ($row) {
        return '<span style="font-size: 13px">' . date('Y/m/d H:i:s', strtotime($row->created_at)) . '</span>';
      })

      ->addColumn('code_order', function ($row) {
        if ($row->order_id_fk) {

          return '<label class="label label-inverse-info-border" ><a href="' . route('cart-payment-history', ['code_order' =>$row->order_code]) . '">' . $row->order_code . '</a></label>';
        } else {
          return '<label class="label label-inverse-info-border" onclick="view_aicash(' . $row->add_ai_cash_id_fk . ')"><a href="#!">' . $row->order_code . '</a></label>';
        }
      })

      ->addColumn('price_total', function ($row) {
        //return '<b class="text-primary">' . number_format($row->price_total, 2) . '</b>';
        return '<b>' . number_format($row->price_total, 2) . '</b>';
      })
      ->addColumn('aicash_old', function ($row) {
        return '<b>' . number_format($row->aicash_old, 2) . '</b>';
      })

      ->addColumn('aicash_price', function ($row) {
        if ($row->type == 'buy_product' || $row->type == 'buy_course' || $row->type == 'cancel') {
          return '<b class="text-danger"> -' . number_format($row->aicash_price, 2) . '</b>';
        } else {
          return '<b class="text-success"> +' . number_format($row->aicash_price, 2) . '</b>';
        }
      })
      ->addColumn('aicash_banlance', function ($row) {
        return '<b>' . number_format($row->aicash_banlance, 2) . '</b>';
      })

      ->rawColumns(['created_at', 'price_total', 'aicash_price', 'aicash_banlance', 'code_order'])
      ->make(true);
  }

  public function cancel_aicash(Request $rs)
  {

    $cancel_aicash_id = $rs->cancel_aicash_id;
    if ($cancel_aicash_id) {
      $customer_id = Auth::guard('c_user')->user()->id;
      $aicash = DB::table('db_add_ai_cash')
        ->select('cancel_expiry_date')
        ->where('id', '=', $cancel_aicash_id)
        ->first();

      if ($aicash->cancel_expiry_date == '' || $aicash->cancel_expiry_date == '00-00-00 00:00:00' || (strtotime('now') > strtotime($aicash->cancel_expiry_date))) {
        return redirect('ai-cash')->withError('Cancel Ai-Cash Fail : Cancel Time Out !');
      }

      $resule = CancelAicashController::cancel_aicash($cancel_aicash_id, $customer_id, 'customer');

      if ($resule['status'] == 'success') {
        return redirect('ai-cash')->withSuccess($resule['message']);
      } else {
        return redirect('ai-cash')->withError($resule['message']);
      }
    } else {
      return redirect('ai-cash')->withError('Cancel Ai-Cash Fail : Data is null');
    }
  }

  public function cancel_aicash_backend(Request $rs)
  {
    $order_id = $rs->cancel_aicash_backend_order_id;
    if ($order_id) {
      $customer_id = Auth::guard('c_user')->user()->id;
      $resule = CancelAicashController::cancel_aicash_backend($order_id, $customer_id, '2', 'customer');

      if ($resule['status'] == 'success') {
        return redirect('product-history')->withSuccess($resule['message']);
      } else {
        return redirect('product-history')->withError($resule['message']);
      }
    } else {
      return redirect('product-history')->withError('Cancel Ai-Cash Fail : Data is null');
    }
  }

  public function confirm_aicash(Request $rs)
  {

    $order_id = $rs->confirm_aicash_order_id;
    if ($order_id) {

      $customer_id = Auth::guard('c_user')->user()->id;
      $resule = \App\Models\Frontend\PvPayment::PvPayment_type_confirme($order_id, $customer_id, '2', 'customer');

      if ($resule['status'] == 'success') {
        return redirect('product-history')->withSuccess($resule['message']);
      } else {
        return redirect('product-history')->withError($resule['message']);
      }
    } else {
      return redirect('product-history')->withError('Cancel Ai-Cash Fail : Data is null');
    }
  }

  public function upload_slip_aicash(Request $request)
  {
    //ไม่ใช้เเล้ว
    $file_slip = $request->file_slip_aicash;
    if (isset($file_slip)) {
      $url = 'local/public/files_slip/' . date('Ym');

      $f_name = date('YmdHis') . '_' . Auth::guard('c_user')->user()->id . '.' . $file_slip->getClientOriginalExtension();
      if ($file_slip->move($url, $f_name)) {
        try {
          DB::BeginTransaction();
          DB::table('payment_slip')
            ->insert(['customer_id' => Auth::guard('c_user')->user()->id, 'url' => $url, 'file' => $f_name, 'order_id' => $request->aicash_id, 'type' => 'ai-cash']);

          $update = DB::table('db_add_ai_cash') //update บิล
            ->where('id', $request->aicash_id)
            ->update([
              'order_status_id_fk' => 2,
              'file_slip' => $url . $f_name,
            ]);

          DB::commit();
          return redirect('ai-cash')->withSuccess('Upload Slip Success');
        } catch (Exception $e) {
          DB::rollback();
          return redirect('ai-cash')->withError('Upload Slip fail');
        }
      } else {

        return redirect('product-history')->withError('Upload Slip fail');
      }
    }
  }

  public function view_aicash(Request $request)
  {
    $data = DB::table('db_add_ai_cash')
      ->select('db_add_ai_cash.*', 'dataset_pay_type.detail as pay_type', 'dataset_order_status.detail as order_status', 'dataset_order_status.css_class')
      ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_add_ai_cash.pay_type_id_fk')
      ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_add_ai_cash.order_status_id_fk')
      ->where('db_add_ai_cash.id', '=', $request->id)
      ->orderby('db_add_ai_cash.created_at', 'desc')
      ->first();

    $date = date('d/m/Y H:i:s', strtotime($data->created_at));
    $data = ['status' => 'success', 'data' => $data, 'price' => number_format($data->aicash_amt, 2), 'date_aicash' => $date];

    // dd($data);

    return $data;
  }

  public function cart_payment_transfer_aicash($code_order)
  {

    $ai_cash = DB::table('db_add_ai_cash')
      ->select('db_add_ai_cash.*', 'dataset_pay_type.detail as pay_type', 'dataset_order_status.detail as order_status', 'dataset_order_status.css_class')
      ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_add_ai_cash.pay_type_id_fk')
      ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_add_ai_cash.order_status_id_fk')
      ->where('db_add_ai_cash.order_status_id_fk', '=', '1')
      ->where('db_add_ai_cash.code_order', '=', $code_order)
      ->orderby('db_add_ai_cash.created_at', 'desc')
      ->first();

    if (empty($ai_cash)) {
      return redirect('product-history')->withError('ไม่พบบิลเลขที่ ' . $code_order . ' อยู่ในระบบเติมเงิน Ai Cash');
    } else {
      return view('frontend/product/cart_payment_transfer_aicash', compact('ai_cash'));
    }
  }

  public function cart_payment_transfer_aicash_submit(Request $request)
  {

    $business_location_id = Auth::guard('c_user')->user()->business_location_id;
    if (empty($business_location_id)) {
      $business_location_id = 1;
    }

    $ai_cash = DB::table('db_add_ai_cash')
      ->select('db_add_ai_cash.*', 'dataset_pay_type.detail as pay_type', 'dataset_order_status.detail as order_status', 'dataset_order_status.css_class')
      ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_add_ai_cash.pay_type_id_fk')
      ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_add_ai_cash.order_status_id_fk')
      ->where('db_add_ai_cash.order_status_id_fk', '=', '1')

      ->where('db_add_ai_cash.code_order', '=', $request->code_order)
      ->orderby('db_add_ai_cash.created_at', 'desc')
      ->first();

    if (empty($ai_cash)) {
      return redirect('ai-cash')->withError('ไม่พบบิลเลขที่ ' . $request->code_order . ' อยู่ในระบบรอชำระเงิน');
    }

    if ($request->submit == 'upload') {

      $file_slip = $request->file('files');
      if (count($file_slip) > 0) {
        $url = 'local/public/files_slip/' . date('Ym');
        $i = 0;
        foreach ($file_slip as $value) {
          $i++;
          $f_name = date('YmdHis_') . '' . $i . '_' . $ai_cash->customer_id_fk . '.' . $value->getClientOriginalExtension();
          if ($value->move($url, $f_name)) {
            DB::table('payment_slip')
              ->insert(['customer_id' => $ai_cash->customer_id_fk, 'url' => $url, 'file' => $f_name, 'order_id' => $ai_cash->id, 'code_order' => $request->code_order, 'type' => 'ai-cash']);
            $update_aicash = DB::table('db_add_ai_cash')
              ->where('id', $ai_cash->id)
              ->update([
                'pay_type_id_fk' => 1,
                'transfer_price' => $ai_cash->total_amt,
                'order_status_id_fk' => 2,
                'file_slip' => $url . '/' . $f_name,
              ]);
            $resule = ['status' => 'success', 'message' => 'Add Ai-Cash Success'];
          }
        }
      }
      if ($resule['status'] == 'success') {
        DB::commit();
        return redirect('ai-cash')->withSuccess($resule['message']);
      } else {
        DB::rollback();
        return redirect('ai-cash')->withErrors('การโอนชำระไม่สำเร็จ กรุณาลองใหม่อีกครั้งคะ');
      }
    } elseif ($request->submit == 'not_upload') {
      return redirect('ai-cash');
    } elseif ($request->submit == 'PromptPay') {
      $request['pay_type'] = 15;

      $gateway_pay_data = array(
        'mch_order_no' => $ai_cash->code_order,
        "total_fee" => $ai_cash->total_amt,
        "fee_type" => 'THB',
        "channel_list" => 'promptpay',
        'mch_code' => $ai_cash->code_order,
        'product_name' => 'Add Ai Cash',
      );

      $data = KsherController::gateway_ksher($gateway_pay_data);
      //targetUrl
      if ($data['status'] == 'success') {


        $update_order = DB::table('db_add_ai_cash')
          ->where('id', $ai_cash->id)
          ->update(['pay_type_id_fk' => '17']);


        return redirect($data['url']);
      } else {
        return redirect('ai-cash')->withError('Payment Fail');
      }
    } elseif ($request->submit == 'TrueMoney') {


      $gateway_pay_data = array(
        'mch_order_no' => $ai_cash->code_order,
        "total_fee" => $ai_cash->total_amt,
        "fee_type" => 'THB',
        "channel_list" => 'truemoney',
        'mch_code' => $ai_cash->code_order,
        'product_name' => 'Add Ai Cash',
      );

      $data = KsherController::gateway_ksher($gateway_pay_data);
      //targetUrl
      if ($data['status'] == 'success') {

        $update_order = DB::table('db_add_ai_cash')
          ->where('id', $ai_cash->id)
          ->update(['pay_type_id_fk' => '17']);

        return redirect($data['url']);
      } else {
        return redirect('ai-cash')->withError('Payment Fail');
      }
    } elseif ($request->submit == 'Credit') {

      $check_cradit = \App\Helpers\Frontend::check_cradit(Auth::guard('c_user')->user()->business_location_id);
      if( $check_cradit['type'] == 1){
        $fee_rate = $ai_cash->total_amt * ($check_cradit['fee_rate']/100);
      }else{
        $fee_rate = $check_cradit['fee_rate'];
      }
      $credit_price = $ai_cash->total_amt;
      $total_price = $ai_cash->total_amt+$fee_rate;
      $gateway_pay_data = array(
        'mch_order_no' => $ai_cash->code_order,
        "total_fee" =>  $total_price,
        "fee_type" => 'THB',
        "channel_list" => 'ktbcard',
        'mch_code' => $ai_cash->code_order,
        'product_name' => 'Add Ai Cash',
      );

      $data = KsherController::gateway_ksher($gateway_pay_data);
      //targetUrl
      if ($data['status'] == 'success') {

        $update_order = DB::table('db_add_ai_cash')
          ->where('id', $ai_cash->id)
          ->update(['pay_type_id_fk' => '2','fee'=>$check_cradit['dataset_fee_id_fk'],'charger_type'=>'1','credit_price'=>$credit_price,'sum_credit_price'=> $total_price,'fee_amt'=>$fee_rate,'approve_status'=>1]);


        return redirect($data['url']);
      } else {
        return redirect('ai-cash')->withError('Payment Fail');
      }
    } else {
      return redirect('ai-cash')->withError('Payment submit Fail');
    }
  }
}
