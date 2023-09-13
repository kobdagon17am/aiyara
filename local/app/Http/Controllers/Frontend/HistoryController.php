<?php

namespace App\Http\Controllers\Frontend;

use PDF;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Random_code;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Frontend\Ksher\KsherController;
use App\Http\Controllers\Frontend\Fc\CancelOrderController;
use App\Http\Controllers\Frontend\Fc\DeleteOrderController;

class HistoryController extends Controller
{


    public function __construct()
    {

        $this->middleware('customer');
    }

    public function index()
    {



      $business_location_id = Auth::guard('c_user')->user()->business_location_id;
      if (empty($business_location_id)) {
          $business_location_id = 1;
      }


        $orders_type = DB::table('dataset_orders_type')
            ->where('status', '=', '1')
            ->where('lang_id', '=', $business_location_id)
            ->orderby('order')
            ->get();

        $pay_type = DB::table('dataset_pay_type')
            ->where('status', '=', '1')
            ->orderby('id')
            ->get();

        $data = ['orders_type' => $orders_type, 'pay_type' => $pay_type];

        return view('frontend/product/product-history', compact('data'));
    }

    // public function export_pdf_history(){
    //   $data = DB::table('dataset_orders_type')
    //   ->where('status','=','1')
    //   ->where('lang_id','=','1')
    //   ->orderby('order')
    //   ->get();

    //   //$pdf = PDF::loadView('frontend.pdf.payment',compact('data'));
    //   return view('frontend.pdf.payment',compact('data'));
    //   // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
    //   //return $pdf->stream('payment.pdf'); // เปิดไฟลฺ์
    // }

    public function export_pdf_history($code_order)
    {
      $business_location_id = Auth::guard('c_user')->user()->business_location_id;
      if (empty($business_location_id)) {
          $business_location_id = 1;
      }


      $order = DB::table('db_orders')
        ->select('db_orders.*', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type',
            'db_invoice_code.order_payment_code',
            'dataset_pay_type.detail as pay_type_name', 'dataset_provinces.name_th as provinces_name', 'dataset_amphures.name_th as amphures_name', 'dataset_districts.name_th as district_name')
        ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
        ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
        ->leftjoin('db_invoice_code', 'db_invoice_code.order_id', '=', 'db_orders.id')
        ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')

        ->leftjoin('dataset_provinces', 'dataset_provinces.id', '=', 'db_orders.province_id_fk')
        ->leftjoin('dataset_amphures', 'dataset_amphures.id', '=', 'db_orders.amphures_id_fk')
        ->leftjoin('dataset_districts', 'dataset_districts.id', '=', 'db_orders.district_id_fk')

        ->where('dataset_order_status.lang_id', '=', $business_location_id)
        ->where('dataset_orders_type.lang_id', '=', $business_location_id)
        ->where('db_orders.code_order', '=', $code_order)
        ->first();

            $branch = DB::table('branchs')
              ->select('b_name')
              ->where('id', $order->branch_id_fk)
              ->first();

        if ($order->delivery_location_frontend == 'sent_address') {
          $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);

        } elseif ($order->delivery_location_frontend == 'sent_address_card') {

            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);

        } elseif ($order->delivery_location_frontend == 'sent_office') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $branch->b_name, '', '', '', '', '', '', '', '');

        } elseif ($order->delivery_location_frontend == 'sent_address_other') {

            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);
        } else {
            $address = '';
        }

        if ($order->purchase_type_id_fk == 6) {
            $order_items = DB::table('db_order_products_list')
                ->select('db_order_products_list.*', 'course_ticket_number.ticket_number')
                ->where('frontstore_id_fk', '=', $order->id)
                ->leftjoin('course_event_regis', 'course_event_regis.order_item_id', '=', 'db_order_products_list.id')
                ->leftjoin('course_ticket_number', 'course_ticket_number.id', '=', 'course_event_regis.ticket_id')
                ->get();
        } else {
            $order_items = DB::table('db_order_products_list')
                ->where('frontstore_id_fk', '=', $order->id)
                ->orderby('id', 'ASC')
                ->get();
        }

        if (!empty($order)) {
            //return view('frontend.pdf.payment', compact('order', 'order_items'));
            $pdf = PDF::loadView('frontend.pdf.payment', compact('order', 'order_items', 'address'));
            //   return view('frontend.pdf.payment',compact('data'));
            //   // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
            return $pdf->stream('payment.pdf'); // เปิดไฟลฺ์
        } else {
            return redirect('product-history')->withError('Payment Data is Null');
        }

    }

    public function modal_qr_recive_product(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $data_order = DB::table('db_orders')
            ->where('id', '=', $id)
            ->first();

        if ($type == 'refresh_time') {

            $type_qr_modal = 'update';
            $random = Random_code::random_code('8');
            $qr = $id . '' . $random;

            $endata = date('Y-m-d H:i:s', strtotime("+30 minutes"));
            $updated_qrcode = DB::table('db_orders')
                ->where('id', $id)
                ->update(['qr_code' => $qr, 'qr_endate' => $endata]);

        } else {
            if ($data_order->qr_code) {
              $type_qr_modal = 'non';

                $qr_endate = strtotime($data_order->qr_endate);
                if ($qr_endate < strtotime(now())) {
                  $type_qr_modal = 'non';
                }else{
                  $type_qr_modal = 'update';
                }
            } else {
                $type_qr_modal = 'update';
                $random = Random_code::random_code('8');
                $qr = $id . '' . $random;

                $endata = date('Y-m-d H:i:s', strtotime("+30 minutes"));
                $updated_qrcode = DB::table('db_orders')
                    ->where('id', $id)
                    ->update(['qr_code' => $qr, 'qr_endate' => $endata]);
            }

        }

        $data_order = DB::table('db_orders')
            ->where('id', '=', $id)
            ->first();

        return view('frontend/modal/modal_qr_recive_product', compact('data_order','type_qr_modal'));
    }

    public function datatable(Request $request)
    {

        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        if(empty($business_location_id)){
          $business_location_id = 1;
        }

        $not_show = DB::table('db_orders')
        ->select('id')
        ->whereRaw('db_orders.distribution_channel_id_fk != 3  and db_orders.order_status_id_fk = 8 ')
        ->where('db_orders.customers_id_fk', '=', Auth::guard('c_user')->user()->id)
        ->get();


        $not_show_arr = array();
        foreach($not_show as $value){
          $not_show_arr[] = $value->id;
        }

        // dd($request->dt_order_type);

            $orders = DB::table('db_orders')
            ->select('db_orders.tracking_no','db_orders.note','db_orders.sum_price','db_orders.shipping_price','db_orders.distribution_channel_id_fk',
            'db_orders.purchase_type_id_fk', 'db_orders.pv_total', 'db_orders.created_at', 'db_orders.delivery_location_frontend','db_orders.order_status_id_fk',
            'db_orders.branch_id_fk' , 'db_orders.code_order', 'db_orders.cancel_expiry_date','db_orders.pay_type_id_fk','db_orders.id',
            'db_orders.pv_banlance','db_orders.active_mt_date','db_orders.active_tv_date',
            'dataset_order_status.detail', 'dataset_order_status.css_class',
                'dataset_orders_type.orders_type as type', 'dataset_orders_type.icon as type_icon',
                'dataset_pay_type.detail as pay_type_name')
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
            ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
            ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
            ->whereNotIn('db_orders.id',$not_show_arr)
            ->where("db_orders.customers_id_fk","=",Auth::guard('c_user')->user()->id)
            ->where('db_orders.order_channel', '!=','VIP')
            ->where('db_orders.deleted_at', '=',null)
            ->where('dataset_order_status.lang_id', '=', $business_location_id)
            ->where('dataset_orders_type.lang_id', '=', $business_location_id)
            // ->whereRaw('(db_orders.distribution_channel_id_fk NOT IN (3) and db_orders.order_status_id_fk NOT IN (8) )')
            ->whereRaw(("case WHEN '{$request->dt_order_type}' = '' THEN 1 else dataset_orders_type.group_id = '{$request->dt_order_type}' END"))
            ->whereRaw(("case WHEN '{$request->dt_pay_type}' = '' THEN 1 else dataset_pay_type.id = '{$request->dt_pay_type}' END"))
            ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))

            //->orwhere("db_orders.customers_sent_id_fk","=",Auth::guard('c_user')->user()->id)

            ->orderby('db_orders.created_at', 'DESC');

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
                    return number_format($row->total_price - $row->gift_voucher_price, 2);
                } elseif ($row->type == 6) {
                    return number_format($row->sum_price, 2);
                } elseif ($row->type == 7) {
                    return number_format($row->sum_price, 2);
                } else {
                    return number_format($row->sum_price + $row->shipping_price, 2);
                }
            })

            ->addColumn('pv_total', function ($row) {
              if($row->purchase_type_id_fk == 5){
                return '<b class="text-success"> 0 </b>';
              }else{
                return '<b class="text-success">' . number_format($row->pv_total) . '</b>';
              }

            })
            ->addColumn('date', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->created_at));
            })

            ->addColumn('status', function ($row) {
                if ($row->delivery_location_frontend == 'sent_office' and $row->order_status_id_fk == 4) {
                   $name = HistoryController::get_name_branchs($row->branch_id_fk);

                    return '<button class="btn btn-sm btn-' . $row->css_class . ' btn-outline-' . $row->css_class . '" onclick="qrcode('.$row->id.')" ><i class="fa fa-qrcode"></i> <b style="color: #000">'.$row->detail.' '.$name->b_name.'</b></button>';
                } else {

                  if($row->order_status_id_fk == 5 and !empty($row->tracking_no)){
                    return '<span class="label label-inverse-success" ><b style="color:#000"> Success <b></span>';
                  }


                  if($row->order_status_id_fk == 1 ){
                    if($row->note){
                      return '<button class="btn btn-sm btn-' . $row->css_class . ' btn-outline-' . $row->css_class . '" data-toggle="modal" data-target="#large-Modal" onclick="upload_slip('.$row->id.',\''.$row->note.'\')" > <b style="color: #000">' . $row->detail . '</b></button>';
                    }else{
                      return '<span class="label label-inverse-'.$row->css_class.'" ><b style="color:#000"> '.$row->detail.' <b></span>';
                      // return '<button class="btn btn-sm btn-' . $row->css_class . ' btn-outline-' . $row->css_class . '"> <b style="color: #000">' . $row->detail . '</b></button>';
                    }


                  }else if($row->order_status_id_fk == 3){
                    return '<button class="btn btn-sm btn-' . $row->css_class . ' btn-outline-' . $row->css_class . '" onclick="modal_logtranfer(' . $row->id . ','.$row->customers_id_fk.')" ><i class="fa fa-search"></i> <b style="color: #000">' . $row->detail . '</b></button>';

                  }else {

                    return '<span class="label label-inverse-'.$row->css_class.'" ><b style="color:#000"> '.$row->detail.' <b></span>';
                  }


                }
            })

            ->addColumn('action', function ($row) {

              if($row->distribution_channel_id_fk == 3 ){
                if ($row->order_status_id_fk == 1 || $row->order_status_id_fk == 3) {
                  // $action = '<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#large-Modal" onclick="upload_slip('.$row->id.',\''.$row->note.'\')"><i class="fa fa-upload"></i> Upload </button>
                  $action = '<a class="btn btn-sm btn-success" href="'.route('cart_payment_transfer',['code_order'=>$row->code_order]).'" ><i class="fa fa-refresh"></i> ชำระเงิน </a>

                  <a class="btn btn-sm btn-danger"  data-toggle="modal" data-target="#delete" onclick="delete_order(' . $row->id . ',\'' . $row->code_order . '\')" ><i class="fa fa-trash"></i></a>';
              } elseif ($row->order_status_id_fk == 2 || $row->order_status_id_fk == 5 || ($row->purchase_type_id_fk == 6 and $row->order_status_id_fk == 7)) {

                  if ($row->cancel_expiry_date == '' || $row->cancel_expiry_date == '00-00-00 00:00:00' || (strtotime('now') > strtotime($row->cancel_expiry_date))) {
                      $action = '';
                  } else {
                      if ($row->pay_type_id_fk == 1 || $row->pay_type_id_fk == 10 || $row->pay_type_id_fk == 11 || $row->pay_type_id_fk == 12) {
                          $action = '';
                      } else {
                          //$action = '<a class="btn btn-sm btn-warning"  data-toggle="modal" data-target="#cancel" onclick="cancel_order(' . $row->id . ',\'' . $row->code_order . '\')" ><i class="fa fa-reply-all"></i> Cancel</a>';
                          $action = '';
                      }

                  }

              }elseif($row->order_status_id_fk == 9){
                $action = '<div class="dropdown-warning btn-sm dropdown open">
                <button class="btn btn-sm btn-warning dropdown-toggle waves-effect waves-light " type="button" id="dropdown-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-refresh"></i></button>
                <div class="dropdown-menu" aria-labelledby="dropdown-5" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                <a class="dropdown-item waves-light waves-effect" data-toggle="modal" data-target="#promptpay" onclick="re_new_payment_promptpay('.$row->id.',\''.$row->code_order.'\')">PromptPay</a>
                <a class="dropdown-item waves-light waves-effect" data-toggle="modal" data-target="#truemoney" onclick="re_new_payment_truemoney('.$row->id.',\''.$row->code_order.'\')">TrueMoney</a>
                <a class="dropdown-item waves-light waves-effect" data-toggle="modal" data-target="#large-Modal" onclick="upload_slip('.$row->id.',\''.$row->note.'\')">Upload File Slip</a>
                </div>
            </div>';
              }
               else {
                  $action = '';
              }
              return '<a class="btn btn-sm btn-primary" href="' . route('cart-payment-history', ['code_order' => $row->id]) . '" ><i class="fa fa-search"></i></a> ' . $action;

              }else{

                if(($row->pay_type_id_fk == 3 || $row->pay_type_id_fk == 6 || $row->pay_type_id_fk == 9 || $row->pay_type_id_fk == 11 || $row->pay_type_id_fk == 14) and $row->order_status_id_fk == 2 and  $row->member_id_aicash == Auth::guard('c_user')->user()->id){

                  $action = '<div class="dropdown-warning btn-sm dropdown open">
                  <button class="btn btn-warning btn-sm dropdown-toggle waves-effect waves-light " type="button" id="dropdown-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-refresh"></i></button>
                  <div class="dropdown-menu" aria-labelledby="dropdown-5" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                  <a class="dropdown-item waves-light waves-effect" data-toggle="modal" data-target="#confirm_aicash" onclick="confirm_aicash('.$row->id.',\''.$row->code_order.'\')">Confirm</a>
                  <a class="dropdown-item waves-light waves-effect" data-toggle="modal" data-target="#cancel_aicash_backend" onclick="cancel_aicash_backend(' . $row->id . ',\'' . $row->code_order . '\')">Cancel</a>

                  </div>
              </div>';

                }else{
                  $action = '';

                }


                return '<a class="btn btn-sm btn-primary" href="' . route('cart-payment-history', ['code_order' => $row->id]) . '" ><i class="fa fa-search"></i></a> '.$action;

              }

            })

            ->addColumn('banlance', function ($row) {
                if ($row->pv_banlance) {
                    $banlance = number_format($row->pv_banlance);
                } else {
                    $banlance = '';
                }
                return '<b class="text-primary">' . $banlance . '</b>';
            })

            ->addColumn('pay_type_name', function ($row) {
                return '<b class="text-primary">' . $row->pay_type_name . '</b>';
            })
            ->addColumn('date_active', function ($row) {
                if(!empty($row->active_mt_date)) {
                  $date_active = date('d/m/Y', strtotime($row->active_mt_date));
                  return '<span class="label label-inverse-info-border" data-toggle="tooltip" data-placement="right" data-original-title="' . $date_active . '"><b style="color:#000">' . $date_active . '<b></span>';
                }elseif(!empty($row->active_tv_date)) {
                    $date_active = date('d/m/Y', strtotime($row->active_tv_date));
                    return '<span class="label label-inverse-info-border" data-toggle="tooltip" data-placement="right" data-original-title="' . $date_active . '"><b style="color:#000">' . $date_active . '<b></span>';
                }else{
                  return '';
                }
            })

            ->addColumn('type', function ($row) {
                return $row->type_icon;
            })

            ->rawColumns(['pv_total', 'status', 'action', 'banlance', 'pay_type_name', 'type','date_active'])

            ->make(true);
    }

    public function datatable_sent_to(Request $request)
    {

        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        if(empty($business_location_id)){
          $business_location_id = 1;
        }

        $not_show = DB::table('db_orders')
        ->select('id')
        ->whereRaw('db_orders.distribution_channel_id_fk != 3  and db_orders.order_status_id_fk = 8 ')
        ->where('db_orders.customers_id_fk', '=', Auth::guard('c_user')->user()->id)
        ->get();


        $not_show_arr = array();
        foreach($not_show as $value){
          $not_show_arr[] = $value->id;
        }

        // dd($request->dt_order_type);

            $orders = DB::table('db_orders')
            ->select('db_orders.tracking_no','db_orders.note','db_orders.sum_price','db_orders.shipping_price','db_orders.distribution_channel_id_fk',
            'db_orders.purchase_type_id_fk', 'db_orders.pv_total', 'db_orders.created_at', 'db_orders.delivery_location_frontend','db_orders.order_status_id_fk',
            'db_orders.branch_id_fk' , 'db_orders.code_order', 'db_orders.cancel_expiry_date','db_orders.pay_type_id_fk','db_orders.id',
            'db_orders.pv_banlance','db_orders.active_mt_date','db_orders.active_tv_date',
            'dataset_order_status.detail', 'dataset_order_status.css_class',
                'dataset_orders_type.orders_type as type', 'dataset_orders_type.icon as type_icon',
                'dataset_pay_type.detail as pay_type_name')
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
            ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
            ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
            //->whereNotIn('db_orders.id',$not_show_arr)
            ->where("db_orders.customers_sent_id_fk","=",Auth::guard('c_user')->user()->id)
            ->where('db_orders.order_channel', '!=','VIP')
            ->where('db_orders.deleted_at', '=',null)
            ->where('dataset_order_status.lang_id', '=', $business_location_id)
            ->where('dataset_orders_type.lang_id', '=', $business_location_id)
            ->whereRaw(("case WHEN '{$request->dt_order_type}' = '' THEN 1 else dataset_orders_type.group_id = '{$request->dt_order_type}' END"))
            ->whereRaw(("case WHEN '{$request->dt_pay_type}' = '' THEN 1 else dataset_pay_type.id = '{$request->dt_pay_type}' END"))
            ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))

            //->orwhere("db_orders.customers_sent_id_fk","=",Auth::guard('c_user')->user()->id)
            ->orderby('db_orders.created_at', 'DESC');

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
                    return number_format($row->total_price - $row->gift_voucher_price, 2);
                } elseif ($row->type == 6) {
                    return number_format($row->sum_price, 2);
                } elseif ($row->type == 7) {
                    return number_format($row->sum_price, 2);
                } else {
                    return number_format($row->sum_price + $row->shipping_price, 2);
                }
            })

            ->addColumn('pv_total', function ($row) {
              if($row->purchase_type_id_fk == 5){
                return '<b class="text-success"> 0 </b>';
              }else{
                return '<b class="text-success">' . number_format($row->pv_total) . '</b>';
              }

            })
            ->addColumn('date', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->created_at));
            })

            ->addColumn('status', function ($row) {
                if ($row->delivery_location_frontend == 'sent_office' and $row->order_status_id_fk == 4) {
                   $name = HistoryController::get_name_branchs($row->branch_id_fk);

                    return '<button class="btn btn-sm btn-' . $row->css_class . ' btn-outline-' . $row->css_class . '" onclick="qrcode('.$row->id.')" ><i class="fa fa-qrcode"></i> <b style="color: #000">'.$row->detail.' '.$name->b_name.'</b></button>';
                } else {
                  if($row->order_status_id_fk == 1 ){
                    if($row->note){
                      return '<button class="btn btn-sm btn-' . $row->css_class . ' btn-outline-' . $row->css_class . '" data-toggle="modal" data-target="#large-Modal" onclick="upload_slip('.$row->id.',\''.$row->note.'\')" > <b style="color: #000">' . $row->detail . '</b></button>';
                    }else{
                      return '<span class="label label-inverse-'.$row->css_class.'" ><b style="color:#000"> '.$row->detail.' <b></span>';
                      // return '<button class="btn btn-sm btn-' . $row->css_class . ' btn-outline-' . $row->css_class . '"> <b style="color: #000">' . $row->detail . '</b></button>';
                    }


                  }else if($row->order_status_id_fk == 3){
                    return '<button class="btn btn-sm btn-' . $row->css_class . ' btn-outline-' . $row->css_class . '" onclick="modal_logtranfer(' . $row->id . ','.$row->customers_id_fk.')" ><i class="fa fa-search"></i> <b style="color: #000">' . $row->detail . '</b></button>';

                  }else {

                    return '<span class="label label-inverse-'.$row->css_class.'" ><b style="color:#000"> '.$row->detail.' <b></span>';
                  }


                }
            })

            ->addColumn('action', function ($row) {

              if($row->distribution_channel_id_fk == 3 ){
                if ($row->order_status_id_fk == 1 || $row->order_status_id_fk == 3) {
                  // $action = '<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#large-Modal" onclick="upload_slip('.$row->id.',\''.$row->note.'\')"><i class="fa fa-upload"></i> Upload </button>
                  $action = '<a class="btn btn-sm btn-success" href="'.route('cart_payment_transfer',['code_order'=>$row->code_order]).'" ><i class="fa fa-refresh"></i> ชำระเงิน </a>

                  <a class="btn btn-sm btn-danger"  data-toggle="modal" data-target="#delete" onclick="delete_order(' . $row->id . ',\'' . $row->code_order . '\')" ><i class="fa fa-trash"></i></a>';
              } elseif ($row->order_status_id_fk == 2 || $row->order_status_id_fk == 5 || ($row->purchase_type_id_fk == 6 and $row->order_status_id_fk == 7)) {

                  if ($row->cancel_expiry_date == '' || $row->cancel_expiry_date == '00-00-00 00:00:00' || (strtotime('now') > strtotime($row->cancel_expiry_date))) {
                      $action = '';
                  } else {
                      if ($row->pay_type_id_fk == 1 || $row->pay_type_id_fk == 10 || $row->pay_type_id_fk == 11 || $row->pay_type_id_fk == 12) {
                          $action = '';
                      } else {
                          //$action = '<a class="btn btn-sm btn-warning"  data-toggle="modal" data-target="#cancel" onclick="cancel_order(' . $row->id . ',\'' . $row->code_order . '\')" ><i class="fa fa-reply-all"></i> Cancel</a>';
                          $action = '';
                      }

                  }

              }elseif($row->order_status_id_fk == 9){
                $action = '<div class="dropdown-warning btn-sm dropdown open">
                <button class="btn btn-sm btn-warning dropdown-toggle waves-effect waves-light " type="button" id="dropdown-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-refresh"></i></button>
                <div class="dropdown-menu" aria-labelledby="dropdown-5" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                <a class="dropdown-item waves-light waves-effect" data-toggle="modal" data-target="#promptpay" onclick="re_new_payment_promptpay('.$row->id.',\''.$row->code_order.'\')">PromptPay</a>
                <a class="dropdown-item waves-light waves-effect" data-toggle="modal" data-target="#truemoney" onclick="re_new_payment_truemoney('.$row->id.',\''.$row->code_order.'\')">TrueMoney</a>
                <a class="dropdown-item waves-light waves-effect" data-toggle="modal" data-target="#large-Modal" onclick="upload_slip('.$row->id.',\''.$row->note.'\')">Upload File Slip</a>
                </div>
            </div>';
              }
               else {
                  $action = '';
              }
              return '<a class="btn btn-sm btn-primary" href="' . route('cart-payment-history', ['code_order' => $row->id]) . '" ><i class="fa fa-search"></i></a> ' . $action;

              }else{

                if(($row->pay_type_id_fk == 3 || $row->pay_type_id_fk == 6 || $row->pay_type_id_fk == 9 || $row->pay_type_id_fk == 11 || $row->pay_type_id_fk == 14) and $row->order_status_id_fk == 2 and  $row->member_id_aicash == Auth::guard('c_user')->user()->id){

                  $action = '<div class="dropdown-warning btn-sm dropdown open">
                  <button class="btn btn-warning btn-sm dropdown-toggle waves-effect waves-light " type="button" id="dropdown-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-refresh"></i></button>
                  <div class="dropdown-menu" aria-labelledby="dropdown-5" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                  <a class="dropdown-item waves-light waves-effect" data-toggle="modal" data-target="#confirm_aicash" onclick="confirm_aicash('.$row->id.',\''.$row->code_order.'\')">Confirm</a>
                  <a class="dropdown-item waves-light waves-effect" data-toggle="modal" data-target="#cancel_aicash_backend" onclick="cancel_aicash_backend(' . $row->id . ',\'' . $row->code_order . '\')">Cancel</a>

                  </div>
              </div>';

                }else{
                  $action = '';

                }


                return '<a class="btn btn-sm btn-primary" href="' . route('cart-payment-history', ['code_order' => $row->id]) . '" ><i class="fa fa-search"></i></a> '.$action;

              }

            })

            ->addColumn('banlance', function ($row) {
                if ($row->pv_banlance) {
                    $banlance = number_format($row->pv_banlance);
                } else {
                    $banlance = '';
                }
                return '<b class="text-primary">' . $banlance . '</b>';
            })

            ->addColumn('pay_type_name', function ($row) {
                return '<b class="text-primary">' . $row->pay_type_name . '</b>';
            })
            ->addColumn('date_active', function ($row) {
                if(!empty($row->active_mt_date)) {
                  $date_active = date('d/m/Y', strtotime($row->active_mt_date));
                  return '<span class="label label-inverse-info-border" data-toggle="tooltip" data-placement="right" data-original-title="' . $date_active . '"><b style="color:#000">' . $date_active . '<b></span>';
                }elseif(!empty($row->active_tv_date)) {
                    $date_active = date('d/m/Y', strtotime($row->active_tv_date));
                    return '<span class="label label-inverse-info-border" data-toggle="tooltip" data-placement="right" data-original-title="' . $date_active . '"><b style="color:#000">' . $date_active . '<b></span>';
                }else{
                  return '';
                }
            })

            ->addColumn('type', function ($row) {
                return $row->type_icon;
            })

            ->rawColumns(['pv_total', 'status', 'action', 'banlance', 'pay_type_name', 'type','date_active'])

            ->make(true);
    }


    public function upload_slip(Request $request)
    {

      $order = DB::table('db_orders')
      ->select('total_price')
      ->where('id','=',$request->order_id)
      ->first();

      if(empty($order)){
        return redirect('product-history')->withError('Upload Slip fail');
      }

        $file_slip = $request->file_slip;
        if (isset($file_slip)) {
            $url = 'local/public/files_slip/' . date('Ym');

            $f_name = date('YmdHis') . '_' . Auth::guard('c_user')->user()->id . '.' . $file_slip->getClientOriginalExtension();
            if ($file_slip->move($url, $f_name)) {
                try {
                    DB::BeginTransaction();
                    DB::table('payment_slip')
                        ->insert(['customer_id' => Auth::guard('c_user')->user()->id, 'url' => $url, 'file' => $f_name,'code_order' => $order->code_order, 'order_id' => $request->order_id]);

                    DB::table('db_orders')
                        ->where('id', $request->order_id)
                        ->update(['order_status_id_fk' => '2','pay_type_id_fk'=>'1','transfer_price'=>$order->total_price]);



                    DB::commit();
                    return redirect('product-history')->withSuccess('Upload Slip Success');
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect('product-history')->withError('Upload Slip fail');
                }

            } else {

                return redirect('product-history')->withError('Upload Slip fail');

            }
        }
    }

    public function delete_order(Request $rs)
    {
        if ($rs->delete_order_id) {
            $rs = DeleteOrderController::delete_order($rs->delete_order_id);
            if ($rs['status'] == 'success') {
                return redirect('product-history')->withSuccess('Delete Oder Success');
            } else {
                return redirect('product-history')->withError('Delete Oder Fail : Data is null');
            }

        } else {
            return redirect('product-history')->withError('Delete Oder Fail : Data is null');
        }

    }

    public function cancel_order(Request $rs)
    {

        if ($rs->cancel_order_id) {
            $customer_id = Auth::guard('c_user')->user()->id;
            $order = DB::table('db_orders')
                ->select('cancel_expiry_date','distribution_channel_id_fk')
                ->where('id', '=', $rs->cancel_order_id)
                ->first();



            if($order->distribution_channel_id_fk == 3){//มาจากออนไลนเท่านั้น

              if ($order->cancel_expiry_date == '' || $order->cancel_expiry_date == '00-00-00 00:00:00' || (strtotime('now') > strtotime($order->cancel_expiry_date))) {
                return redirect('product-history')->withError('Cancel Oder Fail : Cancel Time Out !');
              }
            }



            $resule = CancelOrderController::cancel_order($rs->cancel_order_id, $customer_id, 1, 'customer');
            if ($resule['status'] == 'success') {
                return redirect('product-history')->withSuccess($resule['message']);
            } else {
                return redirect('product-history')->withError($resule['message']);
            }

        } else {
            return redirect('product-history')->withSuccess('Cancel Oder Fail : Data is null');
        }
    }

    public function cart_payment_history($code_order)
    {
      $business_location_id = Auth::guard('c_user')->user()->business_location_id;
      if (empty($business_location_id)) {
          $business_location_id = 1;
      }

      // dd($code_order);

        $order = DB::table('db_orders')
            ->select('db_orders.*', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type',
                'branchs.b_name as office_name',
                'branchs.house_no as office_house_no',
                'branchs.b_name as office_house_name',
                'branchs.moo as office_moo',
                'branchs.soi as office_soi',
                'branchs.amphures_id_fk as office_amphures',
                'branchs.district_id_fk as office_district',
                'branchs.road as office_road',
                'branchs.province_id_fk as office_province',
                'branchs.zipcode as office_zipcode',
                'branchs.tel as office_tel',
                'branchs.email as office_email',
                'db_invoice_code.order_payment_code',
                'dataset_pay_type.detail as pay_type_name', 'dataset_provinces.name_th as provinces_name', 'dataset_amphures.name_th as amphures_name', 'dataset_districts.name_th as district_name')
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
            ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
            ->leftjoin('branchs', 'branchs.business_location_id_fk', '=', 'db_orders.branch_id_fk')
            ->leftjoin('db_invoice_code', 'db_invoice_code.order_id', '=', 'db_orders.id')
            ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')

            ->leftjoin('dataset_provinces', 'dataset_provinces.id', '=', 'db_orders.province_id_fk')
            ->leftjoin('dataset_amphures', 'dataset_amphures.id', '=', 'db_orders.amphures_id_fk')
            ->leftjoin('dataset_districts', 'dataset_districts.id', '=', 'db_orders.district_id_fk')

            ->where('dataset_order_status.lang_id', '=', $business_location_id)
            ->where('dataset_orders_type.lang_id', '=', $business_location_id)
            ->where('db_orders.id', '=', $code_order)
            ->first();

            //  dd($order);

            $branch = DB::table('branchs')
            ->select('b_name')
            ->where('id', $order->branch_id_fk)
            ->first();

            $pm = DB::table('pm')
            ->select('*')
            ->where('order_code','=',$code_order)
            ->where('see_status','=',0)
            ->first();
            if($pm){
              $pm_update = DB::table('pm')
              ->where('order_code','=',$code_order)
              ->update(['see_status' => 1,'last_update'=>date('Y-m-d')]);
            }

        if ($order->delivery_location_frontend == 'sent_address') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);

        } elseif ($order->delivery_location_frontend == 'sent_address_card') {

            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);

        } elseif ($order->delivery_location_frontend == 'sent_office') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $branch->b_name, '', '', '', '', '', '', '', '');

        } elseif ($order->delivery_location_frontend == 'sent_address_other') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);
        } else {
            $address = '';
        }
        // dd($order);

        if ($order->purchase_type_id_fk == 6) {
            $order_items = DB::table('db_order_products_list')
                ->select('db_order_products_list.*', 'course_ticket_number.ticket_number')
                ->where('frontstore_id_fk', '=', $order->id)
                ->leftjoin('course_event_regis', 'course_event_regis.order_item_id', '=', 'db_order_products_list.id')
                ->leftjoin('course_ticket_number', 'course_ticket_number.id', '=', 'course_event_regis.ticket_id')
                ->get();
        } else {
            $order_items = DB::table('db_order_products_list')
                ->where('frontstore_id_fk', '=', $order->id)
                ->orderby('id', 'ASC')
                ->get();
        }

        $customer_confirm =  DB::table('customers')
        ->select('first_name','last_name','user_name','business_name')
        ->where('id','=',$order->member_id_aicash)
        ->first();

        $customer_use =  DB::table('customers')
        ->select('first_name','last_name','user_name','business_name')
        ->where('id','=',$order->customers_id_fk)
        ->first();
        $file_slip =  DB::table('payment_slip')
        ->where('order_id','=',$order->id)
        ->get();


        if (!empty($order)) {
            return view('frontend/product/cart-payment-history', compact('order', 'order_items', 'address','customer_confirm','customer_use','file_slip'));
        } else {
            return redirect('product-history')->withError('Payment Data is Null');
        }

    }

    public function cart_payment_history_vip($code_order)
    {
      $business_location_id = Auth::guard('c_user')->user()->business_location_id;
      if (empty($business_location_id)) {
          $business_location_id = 1;
      }

        $order = DB::table('db_orders')
            ->select('db_orders.*', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type',
                'branchs.b_name as office_name',
                'branchs.house_no as office_house_no',
                'branchs.b_name as office_house_name',
                'branchs.moo as office_moo',
                'branchs.soi as office_soi',
                'branchs.amphures_id_fk as office_amphures',
                'branchs.district_id_fk as office_district',
                'branchs.road as office_road',
                'branchs.province_id_fk as office_province',
                'branchs.zipcode as office_zipcode',
                'branchs.tel as office_tel',
                'branchs.email as office_email',
                'db_invoice_code.order_payment_code',
                'dataset_pay_type.detail as pay_type_name', 'dataset_provinces.name_th as provinces_name', 'dataset_amphures.name_th as amphures_name', 'dataset_districts.name_th as district_name')
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
            ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
            ->leftjoin('branchs', 'branchs.business_location_id_fk', '=', 'db_orders.branch_id_fk')
            ->leftjoin('db_invoice_code', 'db_invoice_code.order_id', '=', 'db_orders.id')
            ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')

            ->leftjoin('dataset_provinces', 'dataset_provinces.id', '=', 'db_orders.province_id_fk')
            ->leftjoin('dataset_amphures', 'dataset_amphures.id', '=', 'db_orders.amphures_id_fk')
            ->leftjoin('dataset_districts', 'dataset_districts.id', '=', 'db_orders.district_id_fk')

            ->where('dataset_order_status.lang_id', '=', $business_location_id)
            ->where('dataset_orders_type.lang_id', '=', $business_location_id)
            ->where('db_orders.code_order', '=', $code_order)
            ->first();

            $branch = DB::table('branchs')
            ->select('b_name')
            ->where('id', $order->branch_id_fk)
            ->first();

        if ($order->delivery_location_frontend == 'sent_address') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);

        } elseif ($order->delivery_location_frontend == 'sent_address_card') {

            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);

        } elseif ($order->delivery_location_frontend == 'sent_office') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $branch->b_name, '', '', '', '', '', '', '', '');

        } elseif ($order->delivery_location_frontend == 'sent_address_other') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);
        } else {
            $address = '';
        }
        // dd($order);

        if ($order->purchase_type_id_fk == 6) {
            $order_items = DB::table('db_order_products_list')
                ->select('db_order_products_list.*', 'course_ticket_number.ticket_number')
                ->where('frontstore_id_fk', '=', $order->id)
                ->leftjoin('course_event_regis', 'course_event_regis.order_item_id', '=', 'db_order_products_list.id')
                ->leftjoin('course_ticket_number', 'course_ticket_number.id', '=', 'course_event_regis.ticket_id')
                ->get();
        } else {
            $order_items = DB::table('db_order_products_list')
                ->where('frontstore_id_fk', '=', $order->id)
                ->orderby('id', 'ASC')
                ->get();
        }

        $customer_confirm =  DB::table('customers')
        ->select('first_name','last_name','user_name','business_name')
        ->where('id','=',$order->member_id_aicash)
        ->first();

        $customer_use =  DB::table('customers')
        ->select('first_name','last_name','user_name','business_name')
        ->where('id','=',$order->customers_id_fk)
        ->first();

        if (!empty($order)) {
            return view('frontend/product/cart-payment-history-vip', compact('order', 'order_items', 'address','customer_confirm','customer_use'));
        } else {
            return redirect('salepage/vip-report')->withError('Payment Data is Null');
        }

    }
    public static function address($name, $tel, $email, $house_no, $moo, $house_name, $soi, $road, $district_name, $amphures_name, $provinces_name, $zipcode)
    {
        $address = ['name' => $name,
            'tel' => $tel,
            'email' => $email,
            'house_no' => $house_no,
            'moo' => $moo,
            'house_name' => $house_name,
            'soi' => $soi,
            'road' => $road,
            'district_name' => $district_name,
            'amphures_name' => $amphures_name,
            'provinces_name' => $provinces_name,
            'zipcode' => $zipcode,
        ];
        return $address;

    }

    public static function re_new_payment(Request $rs){
      if($rs->order_id == "" || $rs->channel_list == ""){
        dd($rs->all());
        return redirect('product-history')->withError('Payment Fail');
      }


      $business_location_id = Auth::guard('c_user')->user()->business_location_id;
      if(empty($business_location_id)){
        $business_location_id = 1;
      }
      $order_data = DB::table('db_orders')
        ->select('db_orders.*','dataset_orders_type.orders_type as type','dataset_pay_type.detail as pay_type_name')
        ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
        ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
        ->where('dataset_orders_type.lang_id', '=', $business_location_id)
        ->where('db_orders.id', '=',$rs->order_id)
        ->first();

        if(empty($order_data)){
          return redirect('product-history')->withError('Data is Null');
        }

        if($order_data->pay_type_id_fk == 15){
          $total_fee = $order_data->prompt_pay_price;
        }elseif($order_data->pay_type_id_fk == 16){
          $total_fee = $order_data->true_money_price;
        }else{
          return redirect('product-history')->withError('Channel payment in Incorrect');
        }

        $gateway_pay_data = array('mch_order_no'=> $order_data->code_order,
        "total_fee" =>  $total_fee,
        "fee_type" => 'THB',
        "channel_list" => $rs->channel_list,
        'mch_code' => $order_data->code_order,
        'product_name' => $order_data->type,
      );

      $data = KsherController::gateway_ksher($gateway_pay_data);
      //targetUrl
          if($data['status'] == 'success'){
            return redirect($data['url']);
          }else{
            return redirect('product-history')->withError('Payment Fail');
          }
    }

    public static function get_detail_order_aicash(Request $rs){

      $order = DB::table('db_orders')
      ->select('db_orders.customers_id_fk','db_orders.aicash_price','db_orders.credit_price','db_orders.transfer_price','db_orders.cash_pay','db_orders.member_id_aicash','dataset_pay_type.detail as pay_type_name')
      ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
      ->where('db_orders.id', '=', $rs->id)
      ->first();



      $customer =  DB::table('customers')
      ->select('first_name','last_name','user_name','business_name')
      ->where('id','=',$order->customers_id_fk)
      ->first();

      if(empty($order)){
        $resule = ['status' => 'fail', 'message' => 'ไม่พบข้อมูลผู้อนุมัต AiCash กรุณาติดต่อเจ้าหน้าที่'];
        return $resule;
      }else{
        //$order['cash_pay'] = number_format($order->cash_pay);
        $resule = ['status' => 'success', 'message' => 'success','order'=>$order,'customer'=>$customer];
        return $resule;
      }

    }

    public function log_tranfer(Request $rs){
      $file_slip =  DB::table('payment_slip')
      ->where('order_id','=',$rs->order_id)
      ->get();

      $customer =  DB::table('customers')
      ->select('first_name','last_name','user_name','business_name')
      ->where('id','=',$rs->customers_id_fk)
      ->first();

      $order =  DB::table('db_orders')
      ->select('transfer_bill_note')
      ->where('id','=',$rs->order_id)
      ->first();

      return view('frontend/modal/modal_tranfer_log', compact('customer','file_slip','order'));
    }

    public function get_name_branchs($branch_id){
      if($branch_id){
        $branch =  DB::table('branchs')
        ->where('id','=',$branch_id)
        ->first();
        if($branch){
          return $branch;
        }else{
          return '';
        }
      }else{
        return '';
      }
    }


}
