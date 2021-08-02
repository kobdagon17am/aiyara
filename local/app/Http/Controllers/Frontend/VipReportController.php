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
}
