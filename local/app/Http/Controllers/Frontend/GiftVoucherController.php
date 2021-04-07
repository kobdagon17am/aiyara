<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use DB;
// use DataTables;
class GiftVoucherController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer');
    }

    public function index()
    {

        return view('frontend/giftvoucher_history');
    }

    public function gift_order_history()
    {

        return view('frontend/gift_order_history');
    }

    public function dt_giftvoucher_history(Request $rs)
    {
      die('dddd');
        $status = $rs->status;
        $s_date = !empty($rs->s_date) ? date('Y-m-d', strtotime($rs->s_date)) : '';
        $e_date = !empty($rs->e_date) ? date('Y-m-d', strtotime($rs->e_date)) : '';
        $date = date('Y-m-d H:i:s');

        $gift_voucher = DB::table('gift_voucher')
            ->select('*')
            ->where('gift_voucher.customer_id', '=', Auth::guard('c_user')->user()->id)
            ->whereRaw(("case WHEN '{$status}' = 'expiry_date' THEN gift_voucher.expiry_date < '{$date}' WHEN '{$status}' = 'not_expiry_date' THEN  gift_voucher.expiry_date >= '{$date}'
        else 1 END"))
            ->orderby('id', 'DESC')
            ->get();

        // $sQuery = DataTables::of($gift_voucher);
        return $sQuery

            ->addColumn('date', function ($row) {
                $date = '<label class="label label-inverse-info-border"><b>' . date('d/m/Y H:i:s', strtotime($row->create_at)) . '</b></label>';
                return $date;
            })

            ->addColumn('expiry_date', function ($row) {
                $expiry_date = strtotime($row->expiry_date);
                if (empty($row->expiry_date)) {
                    $expiry_date_html = '';
                } elseif ($expiry_date <= strtotime(date('Y-m-d H:i:s'))) {
                    $expiry_date_html = '<label class="label label-inverse-danger"><b>' . date('d-m-Y H:i:s', $expiry_date) . '</b></label>';
                } else {
                    $expiry_date_html = '<label class="label label-inverse-success"><b>' . date('d-m-Y H:i:s', $expiry_date) . '</b></label>';
                }
                return $expiry_date_html;
            })

            ->addColumn('code', function ($row) {
                $code = '<label class="label label-inverse-info-border"><b>' . $row->code . '</b></label>';
                return $code;
            })

            ->addColumn('detail', function ($row) {
                return $row->detail;
            })

            ->addColumn('gv', function ($row) {
                $gv = '<b class="text-primary">' . $row->gv . '</b>';
                return $gv;
            })

            ->addColumn('banlance', function ($row) {
                $banlance = '<b class="text-success">' . $row->banlance . '</b>';
                return $banlance;
            })

            ->rawColumns(['date','expiry_date', 'code','gv','banlance'])
            ->make(true);
    }

    public function dt_gift_order_history(Request $request)
    {
      dd('ddfsdf');

        $columns = array(
            0 => 'id',
            1 => 'date',
            2 => 'order',
            3 => 'gv',
            4 => 'status',
            5 => 'actions',
        );

        if (empty($request->input('search.value')) and empty($request->input('status'))) {

            $totalData = DB::table('log_gift_voucher')
                ->leftjoin('db_orders', 'db_orders.id', '=', 'log_gift_voucher.order_id')
                ->where('log_gift_voucher.customer_id', '=', Auth::guard('c_user')->user()->id)
                ->count();
            $totalFiltered = $totalData;
            $limit = $request->input('length');
            $start = $request->input('start');
            //$order = $columns[$request->input('order.0.column')];
            //$dir = $request->input('order.0.dir');

            $gift_voucher = DB::table('log_gift_voucher')
                ->select('log_gift_voucher.*', 'db_orders.code_order')
                ->leftjoin('db_orders', 'db_orders.id', '=', 'log_gift_voucher.order_id')
                ->where('log_gift_voucher.customer_id', '=', Auth::guard('c_user')->user()->id)
                ->offset($start)
                ->limit($limit)
                ->orderby('id', 'DESC')
                ->get();

// dd($request->input('status'));

        } else {
            $status = $request->status;
            $search = $request->input('search.value');
            $date = date('Y-m-d H:i:s');
            //$status=$request->input('status');

            $totalData = DB::table('log_gift_voucher')
                ->leftjoin('db_orders', 'db_orders.id', '=', 'log_gift_voucher.order_id')
                ->where('log_gift_voucher.customer_id', '=', Auth::guard('c_user')->user()->id)
                ->whereRaw(("case WHEN '{$status}' = 'success' THEN log_gift_voucher.status = 'success' || log_gift_voucher.status = 'order'  WHEN '{$status}' = 'cancel' THEN log_gift_voucher.status = 'cancel'
        else 1 END"))
                ->whereRaw("(db_orders.code_order LIKE '%{$search}%')")
                ->count();

            $totalFiltered = $totalData;
            $limit = $request->input('length');
            $start = $request->input('start');

            //dd($query);
            //$order = $columns[$request->input('order.0.column')];
            //$dir = $request->input('order.0.dir');

            $gift_voucher = DB::table('log_gift_voucher')
                ->select('log_gift_voucher.*', 'db_orders.code_order')
                ->leftjoin('db_orders', 'db_orders.id', '=', 'log_gift_voucher.order_id')
                ->where('log_gift_voucher.customer_id', '=', Auth::guard('c_user')->user()->id)
                ->whereRaw(("case WHEN '{$status}' = 'success' THEN log_gift_voucher.status = 'success' || log_gift_voucher.status = 'order'  WHEN '{$status}' = 'cancel' THEN log_gift_voucher.status = 'cancel'
        else 1 END"))
                ->whereRaw("(db_orders.code_order LIKE '%{$search}%')")
                ->limit($limit)
                ->orderby('id', 'DESC')
                ->get();

        }

        $data = array();
        $i = 0;
        foreach ($gift_voucher as $value) {
            $i++;
            $nestedData['id'] = $i;

            $nestedData['date'] = '<label class="label label-inverse-info-border"><b>' . date('d/m/Y H:i:s', strtotime($value->create_at)) . '</b></label>';
            $nestedData['order'] = $value->code_order;
            $nestedData['gv'] = '<b class="text-primary">' . $value->gv . '</b>';
            if ($value->status == 'success' || $value->status == 'order') {
                $nestedData['status'] = '<label class="label label-inverse-success"><b> Success </b></label>';
            } else {
                $nestedData['status'] = '<label class="label label-inverse-danger"><b> Cancel </b></label>';

            }
            $nestedData['action'] = '<a class="btn btn-sm btn-primary" href="' . route('gift-cart-payment-history', ['code_order' => $value->code_order]) . '" ><i class="fa fa-search"></i></a> ';

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

}
