<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;
use App\Models\Frontend\Product;
use App\Models\DeleteAicashController\LineModel;
use App\Models\Frontend\Runpv;
use App\Http\Controllers\Frontend\Fc\DeleteAicashController;
use App\Http\Controllers\Frontend\Fc\CancelAicashController;
use Auth;
use DataTables;

class AiCashController extends Controller
{

  public function __construct()
  {
    $this->middleware('customer');
  }

  public function index(){

    $type = DB::table('dataset_orders_type')
    ->where('status','=',1)
    ->where('lang_id','=',1)
    ->whereRaw('(group_id = 1 || group_id = 2 || group_id = 3)')
    ->orderby('order')
    ->get();

    $ai_cash = DB::table('db_add_ai_cash')
    ->select('db_add_ai_cash.*','dataset_pay_type.detail as pay_type','dataset_order_status.detail as order_status','dataset_order_status.css_class')
    ->leftjoin('dataset_pay_type','dataset_pay_type.id','=','db_add_ai_cash.pay_type_id')
    ->leftjoin('dataset_order_status','dataset_order_status.orderstatus_id','=','db_add_ai_cash.order_status_id_fk')
    ->where('db_add_ai_cash.deleted_status','!=',1,)
    ->orderby('db_add_ai_cash.created_at','desc')
    ->get();


    $orders_type = DB::table('dataset_orders_type')
    ->where('status', '=', '1')
    ->where('lang_id', '=', '1')
    ->orderby('order')
    ->get();

  $pay_type = DB::table('dataset_pay_type')
    ->where('status', '=', '1')
    ->orderby('id')
    ->get();

    $data = ['orders_type'=> $orders_type,'pay_type'=>$pay_type];

    return view('frontend/aicash',compact('type','ai_cash','data'));
  }


  public function delete_aicash(Request $rs){
     $delete_aicash_id = $rs->delete_aicash_id;
     $customer_id = Auth::guard('c_user')->user()->id;

    if($delete_aicash_id){
      $rs = DeleteAicashController::delete_aicash($delete_aicash_id,$customer_id,'customer');

      if($rs['status'] == 'success'){
        return redirect('ai-cash')->withSuccess('Delete Ai-Cash Success');
      }else{
        return redirect('ai-cash')->withError('Delete Ai-Cash  Fail : Data is null');
      }

    }else{
      return redirect('ai-cash')->withError('Delete Ai-Cash  Fail : Data is null');
    }

  }

  public function datatable_order_aicash(Request $request)
  {
    $business_location_id = Auth::guard('c_user')->user()->business_location_id;
      $orders = DB::table('db_orders')
          ->select('db_orders.*', 'dataset_order_status.detail', 'dataset_order_status.css_class',
          'dataset_orders_type.orders_type as type', 'dataset_orders_type.icon as type_icon',
          'dataset_pay_type.detail as pay_type_name')
          ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
          ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
          ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
          ->where('dataset_order_status.lang_id', '=', $business_location_id)
          ->where('dataset_orders_type.lang_id', '=', $business_location_id)
          ->whereRaw(("case WHEN '{$request->dt_order_type}' = '' THEN 1 else dataset_orders_type.group_id = '{$request->dt_order_type}' END"))
          ->whereRaw(("case WHEN '{$request->dt_pay_type}' = '' THEN 1 else dataset_pay_type.id = '{$request->dt_pay_type}' END"))
          ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
          ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
          ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))
          ->where('db_orders.customers_id_fk', '=', Auth::guard('c_user')->user()->id)
          ->where('db_orders.pay_type_id_fk', '=',3)
          ->orwhere('db_orders.address_sent_id_fk', '=', Auth::guard('c_user')->user()->id)
          ->orderby('db_orders.updated_at', 'DESC')
          ->get();
          //dd($orders);

      $sQuery = Datatables::of($orders);
      return $sQuery
          ->addColumn('tracking', function ($row) {
              if ($row->tracking_no) {
                  return $row->tracking_no;
              } else {
                  return '';
              }

          })
          ->addColumn('price', function ($row) {
              if ($row->type == 5) {
                  return number_format($row->price_remove_gv, 2);
              } elseif ($row->type == 6) {
                  return number_format($row->sum_price, 2);
              } elseif ($row->type == 7) {
                  return number_format($row->sum_price, 2);
              } else {
                  return number_format($row->sum_price + $row->shipping_price, 2);
              }
          })

          ->addColumn('pv_total', function ($row) {
              return '<b class="text-success">' . number_format($row->pv_total) . '</b>';
          })
          ->addColumn('date', function ($row) {
              return date('Y/m/d H:i:s', strtotime($row->created_at));
          })

          ->addColumn('status', function ($row) {
              if ($row->delivery_location_frontend == 'sent_office' and $row->type == 4) {
                  return '<button class="btn btn-sm btn-' . $row->css_class . ' btn-outline-' . $row->css_class . '" onclick="qrcode(' . $row->id . ')" ><i class="fa fa-qrcode"></i> <b style="color: #000">' . $row->detail . '</b></button>';
              } else {
                  return '<button class="btn btn-sm btn-' . $row->css_class . ' btn-outline-' . $row->css_class . '" > <b style="color: #000">' . $row->detail . '</b></button>';

              }
          })

          ->addColumn('action', function ($row) {
              if ($row->order_status_id_fk == 1 || $row->order_status_id_fk == 3) {
                  $action = '<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#large-Modal" onclick="upload_slip(' . $row->id . ')"><i class="fa fa-upload"></i> Upload </button>
                  <a class="btn btn-sm btn-danger"  data-toggle="modal" data-target="#delete" onclick="delete_order('.$row->id.',\''.$row->code_order.'\')" ><i class="fa fa-trash"></i></a>';
              } elseif($row->order_status_id_fk == 2 || $row->order_status_id_fk == 5 || ($row->purchase_type_id_fk == 6 and $row->order_status_id_fk == 7)) {

                if($row->cancel_expiry_date == '' || $row->cancel_expiry_date == '00-00-00 00:00:00' || (strtotime('now') > strtotime($row->cancel_expiry_date)) ){
                  $action = '';
                }else{
                  if($row->pay_type_id_fk == 1 || $row->pay_type_id_fk == 10 || $row->pay_type_id_fk == 11 || $row->pay_type_id_fk == 12 )
                  {
                    $action = '';
                  }else{
                    $action = '<a class="btn btn-sm btn-warning"  data-toggle="modal" data-target="#cancel" onclick="cancel_order('.$row->id.',\''.$row->code_order.'\')" ><i class="fa fa-reply-all"></i> Cancel</a>';
                  }

                }

              }else{
                  $action ='';
              }
              return '<a class="btn btn-sm btn-primary" href="' . route('cart-payment-history', ['code_order' => $row->code_order]) . '" ><i class="fa fa-search"></i></a> ' . $action;
          })

          // ->addColumn('banlance', function ($row) {
          //     if ($row->pv_banlance) {
          //         $banlance = number_format($row->pv_banlance);
          //     } else {
          //         $banlance = '';
          //     }
          //     return '<b class="text-primary">' . $banlance . '</b>';
          // })

          ->addColumn('pay_type_name', function ($row) {
              return '<b class="text-primary">' . $row->pay_type_name . '</b>';
          })
          // ->addColumn('date_active', function ($row) {
          //     if (empty($row->active_mt_tv_date)) {
          //         return '';
          //     } else {
          //         $date_active = date('d/m/Y', strtotime($row->active_mt_tv_date));
          //         return '<span class="label label-inverse-info-border" data-toggle="tooltip" data-placement="right" data-original-title="' . $date_active . '"><b style="color:#000">' . $date_active . '<b></span>';
          //     }
          // })

          ->addColumn('type', function ($row) {
          return $row->type_icon;
        })

          ->rawColumns(['pv_total', 'status', 'action', 'pay_type_name','type'])

          ->make(true);
  }





  public function cancel_aicash(Request $rs){

     $cancel_aicash_id = $rs->cancel_aicash_id;
    if($cancel_aicash_id){
      $customer_id = Auth::guard('c_user')->user()->id;
      $aicash = DB::table('db_add_ai_cash')
      ->select('cancel_expiry_date')
      ->where('id','=',$cancel_aicash_id)
      ->first();

      if($aicash->cancel_expiry_date == '' || $aicash->cancel_expiry_date == '00-00-00 00:00:00' || (strtotime('now') > strtotime($aicash->cancel_expiry_date)) ){
        return redirect('ai-cash')->withError('Cancel Ai-Cash Fail : Cancel Time Out !');
      }

      $resule = CancelAicashController::cancel_aicash($cancel_aicash_id,$customer_id,'customer');

      if($resule['status']== 'success'){
        return redirect('ai-cash')->withSuccess($resule['message']);
      }else{
        return redirect('ai-cash')->withError($resule['message']);
      }

    }else{
      return redirect('ai-cash')->withSuccess('Cancel Ai-Cash Fail : Data is null');
    }
  }

  public function upload_slip_aicash(Request $request)
  {
      $file_slip = $request->file_slip_aicash;
      if (isset($file_slip)) {
          $url = 'local/public/files_slip/' . date('Ym');

          $f_name = date('YmdHis') . '_' . Auth::guard('c_user')->user()->id . '.' . $file_slip->getClientOriginalExtension();
          if ($file_slip->move($url, $f_name)) {
              try {
                  DB::BeginTransaction();
                  DB::table('payment_slip')
                      ->insert(['customer_id' => Auth::guard('c_user')->user()->id, 'url' => $url, 'file' => $f_name, 'order_id' => $request->aicash_id,'type' => 'ai-cash']);

                      $update = DB::table('db_add_ai_cash') //update บิล
                      ->where('id', $request->aicash_id)
                      ->update([
                          'order_status_id_fk' => 2, //customer || Admin
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


  public function cart_payment_aicash(Request $request){
    if($request->price == ''){
      return redirect('ai-cash');
    }else{
      $data = ['type'=>7,'price'=>$request->price];
      return view('frontend/product/cart_payment_aicash',compact('data'));
    }
  }
}


