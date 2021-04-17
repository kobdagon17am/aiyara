<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Random_code;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Http\Controllers\Frontend\Fc\DeleteOrderController;
use App\Http\Controllers\Frontend\Fc\CancelOrderController;

class HistoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer');
    }

    public function index()
    {
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

      ->where('dataset_order_status.lang_id', '=', '1')
      ->where('dataset_orders_type.lang_id', '=', '1')
      ->where('db_orders.code_order', '=', $code_order)
      ->first();

        if ($order->delivery_location_frontend == 'sent_address') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->province_id_fk, $order->amphures_id_fk, $order->district_id_fk, $order->zipcode);

        } elseif ($order->delivery_location_frontend == 'sent_address_card') {

            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->province_id_fk, $order->amphures_id_fk, $order->district_id_fk, $order->zipcode);

        } elseif ($order->delivery_location_frontend == 'sent_office') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->office_house_no, $order->office_moo, $order->office_name, $order->office_soi, $order->office_road, $order->office_province, $order->office_amphures, $order->office_district, $order->office_zipcode);

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

        $data_order = DB::table('db_orders')
            ->where('id', '=', $id)
            ->first();

        if ($data_order->qr_code) {
            $qr_endate = strtotime($data_order->qr_endate);
            if ($qr_endate < strtotime(now())) {

                $random = Random_code::random_code('8');
                $qr = $id . '' . $random;

                $endata = date('Y-m-d H:i:s', strtotime("+30 minutes"));
                $updated_qrcode = DB::table('db_orders')
                    ->where('id', $id)
                    ->update(['qr_code' => $qr, 'qr_endate' => $endata]);

            }

        } else {

            $random = Random_code::random_code('8');
            $qr = $id . '' . $random;

            $endata = date('Y-m-d H:i:s', strtotime("+30 minutes"));
            $updated_qrcode = DB::table('db_orders')
                ->where('id', $id)
                ->update(['qr_code' => $qr, 'qr_endate' => $endata]);
        }

        $data = DB::table('orders')
            ->where('id', '=', $id)
            ->first();

        return view('frontend/modal/modal_qr_recive_product', compact('data'));
    }

    public function datatable(Request $request)
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
                    $upload = '<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#large-Modal" onclick="upload_slip(' . $row->id . ')"><i class="fa fa-upload"></i> Upload </button>
                    <a class="btn btn-sm btn-danger"  data-toggle="modal" data-target="#delete" onclick="delete_order('.$row->id.',\''.$row->code_order.'\')" ><i class="fa fa-trash"></i></a>';
                } elseif($row->order_status_id_fk == 2 || $row->order_status_id_fk == 5) {
                    $upload = '<a class="btn btn-sm btn-warning"  data-toggle="modal" data-target="#cancel" onclick="cancel_order('.$row->id.',\''.$row->code_order.'\')" ><i class="fa fa-reply-all"></i> Cancel</a>';
                }else{
                    $upload ='';
                }
                return '<a class="btn btn-sm btn-primary" href="' . route('cart-payment-history', ['code_order' => $row->code_order]) . '" ><i class="fa fa-search"></i></a> ' . $upload;
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
                if (empty($row->active_mt_tv_date)) {
                    return '';
                } else {
                    $date_active = date('d/m/Y', strtotime($row->active_mt_tv_date));
                    return '<span class="label label-inverse-info-border" data-toggle="tooltip" data-placement="right" data-original-title="' . $date_active . '"><b style="color:#000">' . $date_active . '<b></span>';
                }
            })

            ->addColumn('type', function ($row) {
            return $row->type_icon;
          })

            ->rawColumns(['pv_total', 'status', 'action', 'banlance', 'pay_type_name','type'])

            ->make(true);
    }


    public function upload_slip(Request $request)
    {
        $file_slip = $request->file_slip;
        if (isset($file_slip)) {
            $url = 'local/public/files_slip/' . date('Ym');

            $f_name = date('YmdHis') . '_' . Auth::guard('c_user')->user()->id . '.' . $file_slip->getClientOriginalExtension();
            if ($file_slip->move($url, $f_name)) {
                try {
                    DB::BeginTransaction();
                    DB::table('payment_slip')
                        ->insert(['customer_id' => Auth::guard('c_user')->user()->id, 'url' => $url, 'file' => $f_name, 'order_id' => $request->order_id]);

                    DB::table('db_orders')
                        ->where('id', $request->order_id)
                        ->update(['order_status_id_fk' => '2']);

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

    public function delete_order(Request $rs){
      if($rs->delete_order_id){
        $rs = DeleteOrderController::delete_order($rs->delete_order_id);
        if($rs['status'] == 'success'){
          return redirect('product-history')->withSuccess('Delete Oder Success');
        }else{
          return redirect('product-history')->withError('Delete Oder Fail : Data is null');
        }

      }else{
        return redirect('product-history')->withError('Delete Oder Fail : Data is null');
      }

    }

    public function cancel_order(Request $rs){

      if($rs->cancel_order_id){
        $customer_id = Auth::guard('c_user')->user()->id;
        $resule = CancelOrderController::cancel_order($rs->cancel_order_id,$customer_id,1,'customer');
        if($resule['status']== 'success'){
          return redirect('product-history')->withSuccess($resule['message']);
        }else{
          return redirect('product-history')->withError($resule['message']);
        }

      }else{
        return redirect('product-history')->withSuccess('Cancel Oder Fail : Data is null');
      }
    }


    public function cart_payment_history($code_order)
    {

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

            ->where('dataset_order_status.lang_id', '=', '1')
            ->where('dataset_orders_type.lang_id', '=', '1')
            ->where('db_orders.code_order', '=', $code_order)
            ->first();



        if ($order->delivery_location_frontend == 'sent_address') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);

        } elseif ($order->delivery_location_frontend == 'sent_address_card') {

            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);

        } elseif ($order->delivery_location_frontend == 'sent_office') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->office_house_no, $order->office_moo, $order->office_name, $order->office_soi, $order->office_road, $order->office_province, $order->office_amphures, $order->office_district, $order->office_zipcode);

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

        if (!empty($order)) {
            return view('frontend/product/cart-payment-history', compact('order', 'order_items', 'address'));
        } else {
            return redirect('product-history')->withError('Payment Data is Null');
        }

    }
    public function address($name, $tel, $email, $house_no, $moo, $house_name, $soi, $road, $district_name, $amphures_name, $provinces_name, $zipcode)
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

}
