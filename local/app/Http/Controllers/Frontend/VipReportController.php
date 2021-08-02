<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use DB;
use Carbon\Carbon;

class VipReportController extends Controller
{
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
            ->select('db_orders.*', 'users.name')
            ->leftJoin('users', 'users.id', 'db_orders.user_id_fk')
            ->where('users.user_recommend', auth('c_user')->user()->user_name)
            ->orderBy('db_orders.created_at', 'desc')
            ->get();
        
        return Datatables::of($orders)
            ->editColumn('action', function ($order) {
                $route = route('cart-payment-history', $order->code_order);
                return "<a href='{$route}' class='btn btn-sm btn-success'><i class='fa fa-search'></i></a>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
