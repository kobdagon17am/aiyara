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

    public function index($user_name = '')
    {
      if(empty($user_name)){
        $user_name = Auth::guard('c_user')->user()->user_name;
      }

      $data = DB::table('customers')
      ->select('customers.*', 'dataset_package.dt_package', 'dataset_qualification.code_name', 'q_max.code_name as max_code_name',
          'q_max.business_qualifications as max_q_name', 'dataset_qualification.business_qualifications as q_name',
           'customers.team_active_a', 'customers.team_active_b', 'customers.team_active_c','dataset_business_location.txt_desc')
      ->leftjoin('dataset_package', 'dataset_package.id', '=', 'customers.package_id')
      ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
      ->leftjoin('dataset_qualification as q_max', 'q_max.id', '=', 'customers.qualification_max_id')
      ->leftjoin('dataset_business_location','dataset_business_location.id', '=', 'customers.business_location_id')
      ->where('customers.user_name', '=', $user_name)
      ->first();



      $customers_sponser =  DB::table('customers')
      ->select('customers.id','customers.pv','customers.created_at','customers.first_name','customers.last_name','customers.user_name', 'customers.introduce_id', 'customers.upline_id',
      'customers.pv_mt_active', 'customers.introduce_type', 'customers.business_name',
      'dataset_package.dt_package','dataset_qualification.code_name','q_max.code_name as max_code_name',DB::raw(' DATE_ADD(customers.created_at,INTERVAL + 60 DAY) as end_date'))
      ->leftjoin('dataset_package','dataset_package.id','=','customers.package_id')
      ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=','customers.qualification_id')
      ->leftjoin('dataset_qualification as q_max', 'q_max.id', '=','customers.qualification_max_id')
      ->where('customers.introduce_id','=',$user_name)
        ->whereRaw('DATE_ADD(customers.created_at, INTERVAL +60 DAY) >= NOW()')
        ->orderbyraw('customers.introduce_type,customers.id ASC')
      ->get();


        return view('frontend/direct_sponsor',compact('customers_sponser','data'));
    }

    public function dt_sponsor(Request $rs)
    {

      if($rs->user_name){
        $user_name = $rs->user_name;
      }else{
        $user_name = Auth::guard('c_user')->user()->user_name;

      }

        $sTable = DB::table('customers')
            ->select('customers.id','customers.pv','customers.first_name','customers.last_name','customers.user_name', 'customers.introduce_id', 'customers.upline_id',
            'customers.pv_mt_active', 'customers.introduce_type', 'customers.business_name',
                'customers.reward_max_id', 'customers.line_type','customers.team_active_a','customers.team_active_b','customers.team_active_c',
                'dataset_package.dt_package', 'dataset_qualification.code_name', 'q_max.code_name as max_code_name')
            ->leftjoin('dataset_package', 'dataset_package.id', '=', 'customers.package_id')
            ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
            ->leftjoin('dataset_qualification as q_max', 'q_max.id', '=', 'customers.qualification_max_id')
            ->where('customers.introduce_id', '=', $user_name)
            // ->orwhere('customers.user_name', '=', $user_name)
            // ->where('customers.user_name', '=', 'A10263')

            ->orderbyraw('customers.introduce_type,customers.id ASC')
            // ->orderbyraw('customers.introduce_type ASC')
            ->get();

        $sQuery = DataTables::of($sTable);
        return $sQuery
           ->addIndexColumn()
            // ->addColumn('id', function ($row) {
            //     return $row->id;
            // })
            ->addColumn('introduce_type', function ($row) {
                return $row->introduce_type;
            })
            ->addColumn('user_name', function ($row) {
                return $row->user_name;
            })
            ->addColumn('business_name', function ($row) {
              $html = '<button class="btn btn-sm btn-primary" onclick="search_tree(\''.$row->user_name.'\')"><i class="fa fa-sitemap "></i></button>';
                if( !empty($row->business_name) and  $row->business_name  != '-'){
                  return $html.'  <a href="'.route('direct-sponsor',['user_name'=>$row->user_name]).'" target="_blank">'.$row->business_name.' <b>('.$row->user_name.')</b></a>';
                }else{
                  $name = $html.'  <a href="'.route('direct-sponsor',['user_name'=>$row->user_name]).'" target="_blank">'.@$row->first_name.' '. @$row->last_name.' <b>('.$row->user_name.')</b></a>';
                  return $name;
                }
            })
            ->addColumn('dt_package', function ($row) {
                return $row->dt_package;
            })



            ->addColumn('upline', function ($row) {
              $user = DB::table('customers')
              ->select('line_type')
              ->where('user_name', '=', $row->upline_id)
              ->first();
              if($user){
                return $row->upline_id . '/' . $user->line_type;
              }else{
                return '-';
              }


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

              if(empty($row->team_active_a)){
                $a = 0;
              }else{
                $a = $row->team_active_a;
              }
                return $a;
            })
            ->addColumn('count_directsponsor_b', function ($row) {
              if(empty($row->team_active_b)){
                $b = 0;
              }else{
                $b = $row->team_active_b;
              }
                return $b;
            })
            ->addColumn('count_directsponsor_c', function ($row) {
              if(empty($row->team_active_c)){
                $c = 0;
              }else{
                $c = $row->team_active_c;
              }
                return $c ;
            })

            ->addColumn('reward_bonus', function ($row) {

              $count_directsponsor = Frontend::check_customer_directsponsor($row->team_active_a,$row->team_active_b,$row->team_active_c);
                return $count_directsponsor['reward_bonus'];
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

            ->rawColumns(['upline','pv_mt_active','business_name'])
            ->make(true);
    }

}
