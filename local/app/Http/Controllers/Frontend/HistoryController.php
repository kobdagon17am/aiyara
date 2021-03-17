<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Random_code;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Datatables;

class HistoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer');
    }

    public function index()
    {
        $data = DB::table('dataset_orders_type')
            ->where('status', '=', '1')
            ->where('lang_id', '=', '1')
            ->orderby('order')
            ->get();

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
                'dataset_business_major.name as office_name',
                'dataset_business_major.house_no as office_house_no',
                'dataset_business_major.name as office_house_name',
                'dataset_business_major.moo as office_moo',
                'dataset_business_major.soi as office_soi',
                'dataset_business_major.district as office_district',
                'dataset_business_major.district_sub as office_district_sub',
                'dataset_business_major.road as office_road',
                'dataset_business_major.province as office_province',
                'dataset_business_major.zipcode as office_zipcode',
                'dataset_business_major.tel as office_tel',
                'dataset_business_major.email as office_email',
                'db_invoice_code.order_payment_code',
                'dataset_pay_type.detail as pay_type_name', 'dataset_provinces.name_th as provinces_name', 'dataset_amphures.name_th as amphures_name', 'dataset_districts.name_th as district_name')
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
            ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.orders_type_id_fk')
            ->leftjoin('dataset_business_major', 'dataset_business_major.location_id', '=', 'db_orders.sentto_branch_id')
            ->leftjoin('db_invoice_code', 'db_invoice_code.order_id', '=', 'db_orders.id')
            ->leftjoin('dataset_pay_type', 'dataset_pay_type.pay_type_id', '=', 'db_orders.pay_type_id_fk')

            ->leftjoin('dataset_provinces', 'dataset_provinces.id', '=', 'db_orders.province')
            ->leftjoin('dataset_amphures', 'dataset_amphures.id', '=', 'db_orders.district')
            ->leftjoin('dataset_districts', 'dataset_districts.id', '=', 'db_orders.district_sub')

            ->where('dataset_order_status.lang_id', '=', '1')
            ->where('dataset_orders_type.lang_id', '=', '1')
            ->where('db_orders.code_order', '=', $code_order)
            ->first();

        if ($order->delivery_location_status == 'sent_address') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->province, $order->district, $order->district_sub, $order->zipcode);

        } elseif ($order->delivery_location_status == 'sent_address_card') {

            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->province, $order->district, $order->district_sub, $order->zipcode);

        } elseif ($order->delivery_location_status == 'sent_office') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->office_house_no, $order->office_moo, $order->office_name, $order->office_soi, $order->office_road, $order->office_province, $order->office_district, $order->office_district_sub, $order->office_zipcode);

        } elseif ($order->delivery_location_status == 'sent_address_other') {

            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);
        } else {
            $address = '';
        }

        if ($order->orders_type_id_fk == 6) {
            $order_items = DB::table('db_order_products_list')
                ->select('db_order_products_list.*', 'course_ticket_number.ticket_number')
                ->where('order_id_fk', '=', $order->id)
                ->leftjoin('course_event_regis', 'course_event_regis.order_item_id', '=', 'db_order_products_list.id')
                ->leftjoin('course_ticket_number', 'course_ticket_number.id', '=', 'course_event_regis.ticket_id')
                ->get();
        } else {
            $order_items = DB::table('db_order_products_list')
                ->where('order_id_fk', '=', $order->id)
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

    public function datatable()
    {
      $orders = DB::table('db_orders')
      ->select('db_orders.*', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type', 'dataset_pay_type.detail as pay_type_name')
      ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
      ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.orders_type_id_fk')
      ->leftjoin('dataset_pay_type', 'dataset_pay_type.pay_type_id', '=', 'db_orders.pay_type_id_fk')
      ->where('dataset_order_status.lang_id', '=', '1')
      ->where('dataset_orders_type.lang_id', '=', '1')
      ->where('db_orders.customers_id_fk', '=', Auth::guard('c_user')->user()->id)
      ->orderby('db_orders.updated_at', 'DESC')
      ->get();

      $sQuery = DataTables::of($orders);
      return $sQuery;
    }

    public function dt_history(Request $request)
    {

        $columns = array(
            0 => 'id',
            1 => 'date',
            2 => 'code_order',
            3 => 'tracking',
            4 => 'price',
            5 => 'pv_total',
            6 => 'banlance',
            7 => 'date_active',
            8 => 'type',
            9 => 'pay_type_name',
            10 => 'status',
            11 => 'action',
        );

        if (empty($request->input('search.value')) and empty($request->input('order_type'))) {

            $totalData = DB::table('db_orders')
                ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
                ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.orders_type_id_fk')
                ->leftjoin('dataset_pay_type', 'dataset_pay_type.pay_type_id', '=', 'db_orders.pay_type_id_fk')
                ->where('dataset_orders_type.lang_id', '=', '1')
                ->where('dataset_order_status.lang_id', '=', '1')
                ->where('db_orders.customers_id_fk', '=', Auth::guard('c_user')->user()->id)
                ->count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            //$order = $columns[$request->input('order.0.column')];
            //$dir = $request->input('order.0.dir');

            $orders = DB::table('db_orders')
                ->select('db_orders.*', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type', 'dataset_pay_type.detail as pay_type_name')
                ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
                ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.orders_type_id_fk')
                ->leftjoin('dataset_pay_type', 'dataset_pay_type.pay_type_id', '=', 'db_orders.pay_type_id_fk')
                ->where('dataset_order_status.lang_id', '=', '1')
                ->where('dataset_orders_type.lang_id', '=', '1')
                ->where('db_orders.customers_id_fk', '=', Auth::guard('c_user')->user()->id)
                ->offset($start)
                ->limit($limit)
                ->orderby('db_orders.updated_at', 'DESC')
                ->get();

        } else {

            $search = $request->input('search.value');
            $order_type = $request->input('order_type');
            //DB::enableQueryLog();
            //dd($search.':'.$order_type);
            $totalData = DB::table('db_orders')
                ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
                ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.orders_type_id_fk')
                ->leftjoin('dataset_pay_type', 'dataset_pay_type.pay_type_id', '=', 'db_orders.pay_type_id_fk')
                ->where('dataset_order_status.lang_id', '=', '1')
                ->where('dataset_orders_type.lang_id', '=', '1')
                ->where('db_orders.customers_id_fk', '=', Auth::guard('c_user')->user()->id)
                ->whereRaw(("case WHEN '{$order_type}' = '' THEN 1 else dataset_orders_type.group_id = '{$order_type}' END"))
                ->whereRaw("(db_orders.code_order LIKE '%{$search}%' or db_orders.tracking_no LIKE '%{$search}%')")
                ->count();

            $totalFiltered = $totalData;
            $limit = $request->input('length');
            $start = $request->input('start');

            //dd($query);
            //$order = $columns[$request->input('order.0.column')];
            //$dir = $request->input('order.0.dir');

            $orders = DB::table('db_orders')
                ->select('db_orders.*', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type', 'dataset_pay_type.detail as pay_type_name')
                ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
                ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.orders_type_id_fk')
                ->leftjoin('dataset_pay_type', 'dataset_pay_type.pay_type_id', '=', 'db_orders.pay_type_id_fk')
                ->where('dataset_order_status.lang_id', '=', '1')
                ->where('dataset_orders_type.lang_id', '=', '1')
                ->where('db_orders.customers_id_fk', '=', Auth::guard('c_user')->user()->id)
                ->whereRaw(("case WHEN '{$order_type}' = '' THEN 1 else dataset_orders_type.group_id = '{$order_type}' END"))
                ->whereRaw("(db_orders.code_order LIKE '%{$search}%' or db_orders.tracking_no LIKE '%{$search}%')")
                ->offset($start)
                ->limit($limit)
                ->orderby('db_orders.updated_at', 'DESC')
                ->get();

        }

        $data = array();
        $i = $start;
        foreach ($orders as $value) {
            $i++;
            $nestedData['id'] = $i;

            $nestedData['code_order'] = $value->code_order;
            if ($value->tracking_no) {
                $nestedData['tracking'] = '<label class="label label-inverse-info"><b style="color:#000">' . $value->tracking_no . '</b></label>';

            } else {
                $nestedData['tracking'] = '';
            }

            if ($value->type == 5) {
                $nestedData['price'] = number_format($value->price_remove_gv, 2);

            } elseif ($value->type == 6) {
                $nestedData['price'] = number_format($value->sum_price, 2);

            } elseif ($value->type == 7) {
                $nestedData['price'] = number_format($value->sum_price, 2);

            } else {
                $nestedData['price'] = number_format($value->sum_price + $value->shipping_price, 2);

            }

            $nestedData['pv_total'] = '<b class="text-success">' . number_format($value->pv_total) . '</b>';
            $nestedData['date'] = '<span class="label label-inverse-info-border">' . date('d/m/Y H:i:s', strtotime($value->created_at)) . '</span>';
            $nestedData['type'] = $value->type;

            if ($value->delivery_location_status == 'sent_office' and $value->type == 4) {
                $nestedData['status'] = '<button class="btn btn-sm btn-' . $value->css_class . ' btn-outline-' . $value->css_class . '" onclick="qrcode(' . $value->id . ')" ><i class="fa fa-qrcode"></i> <b style="color: #000">' . $value->detail . '</b></button>';
            } else {
                $nestedData['status'] = '<button class="btn btn-sm btn-' . $value->css_class . ' btn-outline-' . $value->css_class . '" > <b style="color: #000">' . $value->detail . '</b></button>';

            }

            if ($value->order_status_id_fk == 1 || $value->order_status_id_fk == 3) {
                $upload = '<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#large-Modal" onclick="upload_slip(' . $value->id . ')"><i class="fa fa-file-text-o"></i> Upload </button>';
            } else {
                $upload = '';
            }

            $nestedData['action'] = '<a class="btn btn-sm btn-primary" href="' . route('cart-payment-history', ['code_order' => $value->code_order]) . '" ><i class="fa fa-file-text-o"></i> View </a> ' . $upload;
            if ($value->pv_banlance) {
                $banlance = number_format($value->pv_banlance);
            } else {
                $banlance = '';
            }
            $nestedData['banlance'] = '<b class="text-primary">' . $banlance . '</b>';
            $nestedData['pay_type_name'] = '<b class="text-primary">' . $value->pay_type_name . '</b>';
            if (empty($value->active_mt_tv_date)) {
                $nestedData['date_active'] = '';

            } else {

                $date_active = date('d/m/Y', strtotime($value->active_mt_tv_date));
                $nestedData['date_active'] = '<span class="label label-inverse-info-border" data-toggle="tooltip" data-placement="right" data-original-title="' . $date_active . '"><b style="color:#000">' . $date_active . '<b></span>';
            }

            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        return json_encode($json_data);
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

    public function cart_payment_history($code_order)
    {

        $order = DB::table('db_orders')
            ->select('db_orders.*', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type',
                'dataset_business_major.name as office_name',
                'dataset_business_major.house_no as office_house_no',
                'dataset_business_major.name as office_house_name',
                'dataset_business_major.moo as office_moo',
                'dataset_business_major.soi as office_soi',
                'dataset_business_major.district as office_district',
                'dataset_business_major.district_sub as office_district_sub',
                'dataset_business_major.road as office_road',
                'dataset_business_major.province as office_province',
                'dataset_business_major.zipcode as office_zipcode',
                'dataset_business_major.tel as office_tel',
                'dataset_business_major.email as office_email',
                'db_invoice_code.order_payment_code',
                'dataset_pay_type.detail as pay_type_name', 'dataset_provinces.name_th as provinces_name', 'dataset_amphures.name_th as amphures_name', 'dataset_districts.name_th as district_name')
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
            ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.orders_type_id_fk')
            ->leftjoin('dataset_business_major', 'dataset_business_major.location_id', '=', 'db_orders.sentto_branch_id')
            ->leftjoin('db_invoice_code', 'db_invoice_code.order_id', '=', 'db_orders.id')
            ->leftjoin('dataset_pay_type', 'dataset_pay_type.pay_type_id', '=', 'db_orders.pay_type_id_fk')

            ->leftjoin('dataset_provinces', 'dataset_provinces.id', '=', 'db_orders.province')
            ->leftjoin('dataset_amphures', 'dataset_amphures.id', '=', 'db_orders.district')
            ->leftjoin('dataset_districts', 'dataset_districts.id', '=', 'db_orders.district_sub')

            ->where('dataset_order_status.lang_id', '=', '1')
            ->where('dataset_orders_type.lang_id', '=', '1')
            ->where('db_orders.code_order', '=', $code_order)
            ->first();

        if ($order->delivery_location_status == 'sent_address') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);

        } elseif ($order->delivery_location_status == 'sent_address_card') {

            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);

        } elseif ($order->delivery_location_status == 'sent_office') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->office_house_no, $order->office_moo, $order->office_name, $order->office_soi, $order->office_road, $order->office_province, $order->office_district, $order->office_district_sub, $order->office_zipcode);

        } elseif ($order->delivery_location_status == 'sent_address_other') {
            $address = HistoryController::address($order->name, $order->tel, $order->email, $order->house_no, $order->moo, $order->house_name, $order->soi, $order->road, $order->district_name, $order->amphures_name, $order->provinces_name, $order->zipcode);
        } else {
            $address = '';
        }
       // dd($order);

        if ($order->orders_type_id_fk == 6) {
            $order_items = DB::table('db_order_products_list')
                ->select('db_order_products_list.*', 'course_ticket_number.ticket_number')
                ->where('order_id_fk', '=', $order->id)
                ->leftjoin('course_event_regis', 'course_event_regis.order_item_id', '=', 'db_order_products_list.id')
                ->leftjoin('course_ticket_number', 'course_ticket_number.id', '=', 'course_event_regis.ticket_id')
                ->get();
        } else {
            $order_items = DB::table('db_order_products_list')
                ->where('order_id_fk', '=', $order->id)
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
