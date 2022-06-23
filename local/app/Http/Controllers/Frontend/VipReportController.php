<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use DB;
use Carbon\Carbon;

class VipReportController extends Controller
{
  public function __construct()
  {
    $this->middleware('customer');
  }
    public function index()
    {
        return view('frontend.salepage.vip-report');
    }

    public function vipDatatable(Request $request)
    {
        $users = DB::table('users')
            ->select('*')
            ->where('user_recommend', auth('c_user')->user()->user_name)
            ->get();

        return Datatables::of($users)
            ->editColumn('name', function ($user) {
                return $user->name.' '.$user->last_name;
            })
            ->editColumn('created_at', function ($user) {
                $dateFormat = date('d-M-Y', strtotime($user->created_at));
                return "<label class='label label-inverse-info-border'>{$dateFormat}</label>";
            })
            ->rawColumns(['created_at'])
            ->make(true);
    }

    public function ordersDatatable(Request $request)
    {
        $orders = DB::table('db_orders')
            ->select('db_orders.*', 'users.name', 'dataset_pay_type.detail as pay_type', 'dataset_order_status.detail as pay_status', 'dataset_order_status.css_class')
            ->leftJoin('users', 'users.id', 'db_orders.user_id_fk')
            ->leftJoin('dataset_pay_type', 'dataset_pay_type.id', 'pay_type_id_fk')
            ->leftJoin('dataset_order_status', 'dataset_order_status.orderstatus_id', 'db_orders.order_status_id_fk')
            ->where('dataset_order_status.lang_id', '=', 1)
            ->where('users.user_recommend', auth('c_user')->user()->user_name)
            ->orderBy('db_orders.created_at', 'desc')
            ->get();

        return Datatables::of($orders)
            ->editColumn('created_at', function ($order) {
                return date('d/m/Y H:i:s', strtotime($order->created_at));
            })
            ->editColumn('pay_type', function ($order) {
                return '<b class="text-primary">' . $order->pay_type . '</b>';
            })
            ->editColumn('pay_status', function ($order) {
                return "<span class='badge badge-{$order->css_class} rounded px-2 py-1'>$order->pay_status</span>";
            })
            ->editColumn('action', function ($order) {
                $route = route('cart-payment-history-vip', $order->code_order);
                return "<a href='{$route}' class='btn btn-sm btn-success'><i class='fa fa-search'></i></a>";
            })

            ->addColumn('total_price', function ($row) {
              if ($row->drop_ship_bonus) {
                  return number_format($row->total_price, 2);
              } else {
                  return '-';
              }
          })

            ->addColumn('drop_ship_bonus', function ($row) {
              if ($row->drop_ship_bonus) {
                  return number_format($row->drop_ship_bonus, 2);
              } else {
                  return '-';
              }
          })

            ->rawColumns(['pay_type', 'pay_status', 'action'])
            ->make(true);
    }
}
