<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Frontend;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
class DirectSponsorController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer');
    }

    public function index()
    {

      // $user_name = Auth::guard('c_user')->user()->user_name;
      // $id = Auth::guard('c_user')->user()->id;

      // $sTable = DB::table('customers')
      //     ->select('customers.id', 'customers.user_name', 'customers.introduce_id', 'customers.upline_id',
      //      'customers.pv_mt_active', 'customers.introduce_type', 'customers.business_name',
      //         'customers.reward_max_id', 'customers.line_type','customers.package_id',
      //         'dataset_package.dt_package', 'dataset_qualification.code_name', 'q_max.code_name as max_code_name')
      //     ->leftjoin('dataset_package', 'dataset_package.id', '=', 'customers.package_id')
      //     ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
      //     ->leftjoin('dataset_qualification as q_max', 'q_max.id', '=', 'customers.qualification_max_id')
      //     ->where('customers.introduce_id', '=', $user_name)
      //     ->orwhere('customers.user_name', '=', $user_name)
      //     ->orderbyraw('(customers.id = ' . $id . ') DESC')
      //     ->get();

      //     dd($sTable);

        // $user_name = Auth::guard('c_user')->user()->user_name;
        // $id = Auth::guard('c_user')->user()->id;

        // $customers = DB::table('customers')
        //     ->select('customers.id', 'customers.user_name', 'customers.introduce_id', 'customers.upline_id', 'customers.pv_mt_active', 'customers.introduce_type', 'customers.business_name',
        //         'customers.reward_max_id', 'customers.line_type',
        //         'dataset_package.dt_package', 'dataset_qualification.code_name', 'q_max.code_name as max_code_name')
        //     ->leftjoin('dataset_package', 'dataset_package.id', '=', 'customers.package_id')
        //     ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
        //     ->leftjoin('dataset_qualification as q_max', 'q_max.id', '=', 'customers.qualification_max_id')
        //     ->where('customers.introduce_id', '=', $user_name)
        //     ->orwhere('customers.user_name', '=', $user_name)
        //     ->orderbyraw('(customers.id = ' . $id . ') DESC')
        //     ->get();

        // $a = DB::table('customers')
        //     ->where('customers.introduce_id', '=', 'AF888')
        //     ->where('introduce_type', '=', 'A')
        //     ->whereDate('pv_mt_active', '>=', now())
        //     ->where('package_id', '!=', '')
        //     ->where('package_id', '!=', null)
        //     ->count();

        // dd($customers);

        // $customers_sponser = DB::table('customers')
        //     ->select('customers.*', 'dataset_package.dt_package', 'dataset_qualification.code_name', 'q_max.code_name as max_code_name', DB::raw(' DATE_ADD(customers.created_at,INTERVAL + 60 DAY) as end_date'))
        //     ->leftjoin('dataset_package', 'dataset_package.id', '=', 'customers.package_id')
        //     ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
        //     ->leftjoin('dataset_qualification as q_max', 'q_max.id', '=', 'customers.qualification_max_id')
        //     ->where('customers.introduce_id', '=', $user_name)
        //     ->whereRaw('DATE_ADD(customers.created_at, INTERVAL +60 DAY) >= NOW()')
        //     ->get();

        return view('frontend/direct_sponsor');
    }

    public function dt_sponsor(Request $rs)
    {

        $user_name = Auth::guard('c_user')->user()->user_name;
        $id = Auth::guard('c_user')->user()->id;

        $sTable = DB::table('customers')
            ->select('customers.id', 'customers.user_name', 'customers.introduce_id', 'customers.upline_id', 'customers.pv_mt_active', 'customers.introduce_type', 'customers.business_name',
                'customers.reward_max_id', 'customers.line_type',
                'dataset_package.dt_package', 'dataset_qualification.code_name', 'q_max.code_name as max_code_name')
            ->leftjoin('dataset_package', 'dataset_package.id', '=', 'customers.package_id')
            ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
            ->leftjoin('dataset_qualification as q_max', 'q_max.id', '=', 'customers.qualification_max_id')
            ->where('customers.introduce_id', '=', $user_name)
            ->orwhere('customers.user_name', '=', $user_name)
            ->orderbyraw('(customers.id = ' . $id . ') DESC')
            ->get();

        $sQuery = DataTables::of($sTable);
        return $sQuery

            ->addColumn('id', function ($row) {
                return $row->id;
            })
            ->addColumn('introduce_type', function ($row) {
                return $row->introduce_type;
            })
            ->addColumn('user_name', function ($row) {
                return $row->user_name;
            })
            ->addColumn('business_name', function ($row) {
                return $row->business_name;
            })
            ->addColumn('dt_package', function ($row) {
                return $row->dt_package;
            })

            ->addColumn('upline', function ($row) {
                return $row->user_name . '/' . $row->line_type;
            })

            ->addColumn('pv_mt_active', function ($row) {
                $check_active_mt = Frontend::check_mt_active($row->pv_mt_active);
                if ($check_active_mt['status'] == 'success') {
                    if ($check_active_mt['type'] == 'Y') {
                        $active_mt = "<span class='label label-inverse-success'><b>"
                            . $check_active_mt['date'] . "</b></span>";
                    } else {
                        $active_mt = "<span class='label label-inverse-info-border'><b>"
                            . $check_active_mt['date'] . "</b></span>";
                    }
                } else {
                    $active_mt = "<span class='label label-inverse-info-border'><b> Not Active </b></span>";
                }
                return $active_mt;
            })

            ->addColumn('count_directsponsor_a', function ($row) {

              // $a = DB::table('customers')
              // ->select('id')
              // ->where('customers.introduce_id', '=',$row->user_name)
              // ->where('introduce_type', '=', 'A')
              // ->whereDate('pv_mt_active', '>=', now())
              // ->where('package_id', '!=', '')
              // ->where('package_id', '!=', null)
              // ->count();
                return $a = 0;
            })
            ->addColumn('count_directsponsor_b', function ($row) {
              // $b = DB::table('customers')
              // ->select('id')
              // ->where('customers.introduce_id', '=',$row->user_name)
              // ->where('introduce_type', '=', 'B')
              // ->whereDate('pv_mt_active', '>=', now())
              // ->where('package_id', '!=', '')
              // ->where('package_id', '!=', null)
              // ->count();
                return $b = 0;
            })
            ->addColumn('count_directsponsor_c', function ($row) {
              // $c = DB::table('customers')
              // ->select('id')
              // ->where('customers.introduce_id', '=',$row->user_name)
              // ->where('introduce_type', '=', 'C')
              // ->whereDate('pv_mt_active', '>=', now())
              // ->where('package_id', '!=', '')
              // ->where('package_id', '!=', null)
              // ->count();
                return $c = 0;
            })

            ->addColumn('reward_bonus', function ($row) {
                return '';
            })

            ->addColumn('reward_max_id', function ($row) {
                return $row->reward_max_id;
            })

            ->addColumn('code_name', function ($row) {
              return $row->code_name;
          })
          ->addColumn('max_code_name', function ($row) {
            return $row->max_code_name;
          })

            ->rawColumns(['upline','pv_mt_active'])
            ->make(true);
    }

}
