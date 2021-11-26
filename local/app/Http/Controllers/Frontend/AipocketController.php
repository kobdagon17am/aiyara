<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Frontend\LineModel;
use App\Models\Frontend\Runpv_AiStockis;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AipocketController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer');
    }

    public function index()
    {
        $type = DB::table('dataset_orders_type')
            ->where('status', '=', 1)
            ->where('lang_id', '=', 1)
            ->whereRaw('(group_id = 1 || group_id = 2 || group_id = 3)')
            ->orderby('order')
            ->get();

        return view('frontend/aistockist', compact('type'));
    }

    public function dt_aipocket(Request $request)
    {
        //$date = date('Y-m-d');

        $sTable = DB::table('ai_stockist')
            ->select('ai_stockist.*', 'c_use.business_name as business_name_use', 'c_to.business_name as business_name_to',
             'c_use.user_name as c_use', 'c_to.user_name as c_to', 'dataset_orders_type.orders_type',
             'users.name as vip_name','users.last_name as vip_last_name')
            ->leftjoin('customers as c_use', 'ai_stockist.customer_id', '=', 'c_use.id')
            ->leftjoin('customers as c_to', 'ai_stockist.to_customer_id', '=', 'c_to.id')
            ->leftjoin('users', 'users.id', '=', 'ai_stockist.user_id_fk')
            ->leftjoin('dataset_orders_type', 'ai_stockist.type_id', '=', 'dataset_orders_type.group_id')
            ->where('dataset_orders_type.lang_id', '=', '1')
            ->where('ai_stockist.status', '=', 'success')
            ->whereRaw('(ai_stockist.customer_id = ' . Auth::guard('c_user')->user()->id . ' or  ai_stockist.to_customer_id =' . Auth::guard('c_user')->user()->id . ')')
            ->get();


        $sQuery = DataTables::of($sTable);

        return $sQuery

            ->addColumn('created_at', function ($row) {
                $data = '<label class="label label-inverse-info-border"><b>' . date('Y/m/d H:i:s', strtotime($row->created_at)) . '</b></label>';

                return $data;

            })

            ->addColumn('order_code', function ($row) {
                return $row->transection_code;
            })

            ->addColumn('customer_id', function ($row) {

              if($row->order_channel != 'VIP'){
                if (Auth::guard('c_user')->user()->id == $row->customer_id) {
                  $data = '<span class="label label-success"><b style="color: #000"><i class="fa fa-user"></i> You </b></span>';
                } else {
                  $data = $row->business_name_use . ' <b>( ' . $row->c_use . ' )</b>';
                }
              }else{

                $data = $row->vip_name.' '.$row->vip_last_name. ' <b>(VIP Shop)</b>';

              }

                return $data;
            })

            ->addColumn('to_customer_id', function ($row) {

                if (Auth::guard('c_user')->user()->id == $row->to_customer_id) {
                    $data = '<span class="label label-success"><b style="color: #000"><i class="fa fa-user"></i> You </b></span>';

                } else {
                    $data = $row->business_name_to . ' <b>( ' . $row->c_to . ' )</b>';

                }

                return $data;
            })

            ->addColumn('type', function ($row) {
                return $row->orders_type;
            })



            ->addColumn('pv', function ($row) {

                if ($row->type_id == 4 || $row->type_id == 8) {
                    $pv = '<b class="text-success">' . $row->pv . '</b>';
                } else {
                    $pv = '<b class="text-danger"> -' . $row->pv . '</b>';

                }

                return $pv;
            })

            ->addColumn('banlance', function ($row) {

                if ($row->status == 'success') {
                    if (empty($row->banlance)) {
                        $banlance = '';
                    } else {
                        $banlance = number_format($row->banlance);
                    }

                } elseif ($row->status == 'panding') {
                    $class_css = 'warning';
                    $banlance = '<span class="label label-' . $class_css . '"><b style="color: #000">' . $row->status . '</b></span>';
                } else {
                    $class_css = 'danger';
                    $class_css = 'danger';
                    $banlance = '<span class="label label-' . $class_css . '"><b style="color: #000">' . $row->status . '</b></span>';
                }

                return $banlance;
            })

            ->addColumn('detail', function ($row) {

                if ($row->detail == 'Sent Ai-Stockist') {
                    $detail = '';
                } else {
                    $detail = $row->detail;

                }

                return $detail;
            })

            ->rawColumns(['created_at', 'customer_id', 'to_customer_id', 'type', 'pv', 'banlance', 'detail'])
            ->make(true);
    }

    public function dt_aistockist_panding(Request $request)
    {
        //$date = date('Y-m-d');

        $sTable = DB::table('ai_stockist')
            ->select('ai_stockist.*', 'c_use.business_name as business_name_use', 'c_to.business_name as business_name_to',
             'c_use.user_name as c_use', 'c_to.user_name as c_to', 'dataset_orders_type.orders_type',
             'users.name as vip_name','users.last_name as vip_last_name')
            ->leftjoin('customers as c_use', 'ai_stockist.customer_id', '=', 'c_use.id')
            ->leftjoin('customers as c_to', 'ai_stockist.to_customer_id', '=', 'c_to.id')
            ->leftjoin('users', 'users.id', '=', 'ai_stockist.user_id_fk')
            ->leftjoin('dataset_orders_type', 'ai_stockist.type_id', '=', 'dataset_orders_type.group_id')
            ->where('dataset_orders_type.lang_id', '=', '1')
            ->whereRaw('(ai_stockist.status = "panding" or ai_stockist.status ="fail") and (ai_stockist.customer_id = ' . Auth::guard('c_user')->user()->id . ' or  ai_stockist.to_customer_id =' . Auth::guard('c_user')->user()->id . ')')
            ->get();

        $sQuery = DataTables::of($sTable);
        return $sQuery

            ->addColumn('created_at', function ($row) {
                $data = '<label class="label label-inverse-info-border"><b>' . date('Y/m/d H:i:s', strtotime($row->created_at)) . '</b></label>';

                return $data;

            })

            ->addColumn('order_code', function ($row) {
                return $row->transection_code;
            })

            ->addColumn('customer_id', function ($row) {

              if($row->order_channel != 'VIP'){
                if (Auth::guard('c_user')->user()->id == $row->customer_id) {
                  $data = '<span class="label label-success"><b style="color: #000"><i class="fa fa-user"></i> You </b></span>';
                } else {
                  $data = $row->business_name_use . ' <b>( ' . $row->c_use . ' )</b>';
                }
              }else{

                $data = $row->vip_name.' '.$row->vip_last_name. ' <b>(VIP Shop)</b>';

              }

                return $data;
            })

            ->addColumn('to_customer_id', function ($row) {

                if (Auth::guard('c_user')->user()->id == $row->to_customer_id) {
                    $data = '<span class="label label-success"><b style="color: #000"><i class="fa fa-user"></i> You </b></span>';

                } else {
                    $data = $row->business_name_to . ' <b>( ' . $row->c_to . ' )</b>';

                }

                return $data;
            })

            ->addColumn('type', function ($row) {
                return $row->orders_type;
            })



            ->addColumn('pv', function ($row) {

                if ($row->type_id == 4 || $row->type_id == 8) {
                    $pv = '<b class="text-success">' . $row->pv . '</b>';
                } else {
                    $pv = '<b class="text-danger"> -' . $row->pv . '</b>';

                }

                return $pv;
            })

            ->addColumn('banlance', function ($row) {

                if ($row->status == 'success') {
                    if (empty($row->banlance)) {
                        $banlance = '';
                    } else {
                        $banlance = number_format($row->banlance);
                    }

                } elseif ($row->status == 'panding') {
                    $class_css = 'warning';
                    $banlance = '<span class="label label-' . $class_css . '"><b style="color: #000">' . $row->status . '</b></span>';
                } else {
                    $class_css = 'danger';
                    $banlance = '<span class="label label-' . $class_css . '"><b style="color: #000">' . $row->status . '</b></span>';
                }

                return $banlance;
            })

            ->addColumn('detail', function ($row) {

                if ($row->detail == 'Sent Ai-Stockist') {
                    $detail = '';
                } else {
                    $detail = $row->detail;

                }

                return $detail;
            })

            ->rawColumns(['created_at', 'customer_id', 'to_customer_id', 'type', 'pv', 'banlance', 'detail'])
            ->make(true);
    }


    public function check_customer_id(Request $request)
    {

      $user_name = Auth::guard('c_user')->user()->user_name;
        $resule = LineModel::check_line_backend($user_name,$request->user_name);

        if ($resule['status'] == 'success') {

            if (empty($resule['data']->pv_mt_active) || (strtotime($resule['data']->pv_mt_active) < strtotime(date('Ymd')))) {
                $pv_mt_active = '<span class="label label-danger"  data-toggle="tooltip" data-placement="right" data-original-title="' . date('d/m/Y', strtotime($resule['data']->pv_mt_active)) . '"  style="font-size: 14px">Not Active </span>  ';

            } else {
                $pv_mt_active = '<span class="label label-info" style="font-size: 12px">Active ถึง ' . date('d/m/Y', strtotime($resule['data']->pv_mt_active)) . '</span>';
            }

            if (empty($resule['data']->pv_tv_active) || (strtotime($resule['data']->pv_tv_active) < strtotime(date('Ymd')))) {
                $pv_tv_active = '<span class="label label-danger"  data-toggle="tooltip" data-placement="right" data-original-title="' . date('d/m/Y', strtotime($resule['data']->pv_tv_active)) . '"  style="font-size: 14px">Not Active </span>  ';

            } else {
                $pv_tv_active = '<span class="label label-info" style="font-size: 12px">Active ถึง ' . date('d/m/Y', strtotime($resule['data']->pv_tv_active)) . '</span>';
            }
            $data = array('status' => 'success', 'data' => $resule, 'pv_tv_active' => $pv_tv_active, 'pv_mt_active' => $pv_mt_active);

        } else {
            $data = array('status' => 'fail', 'data' => $resule);
        }

        //$data = ['status'=>'fail'];
        return $data;
    }

    public function use_aipocket(Request $request)
    {
        $type = $request->type;
        $pv = str_replace(',', '', $request->pv);
        $to_customer_user = $request->username;

        if ($pv > Auth::guard('c_user')->user()->pv_aistockist) {
            return redirect('ai-stockist')->withError('PV Ai-Stockist ของคุณมีไม่เพียงพอ ');

        } else {
            if ($type == 1) {
                $resule = Runpv_AiStockis::run_pv($type, $pv, $to_customer_user, Auth::guard('c_user')->user()->user_name);

                //dd($resule);
                if ($resule['status'] == 'success') {
                    return redirect('ai-stockist')->withSuccess('Sent Ai-Stockist Success');
                } else {
                    return redirect('ai-stockist')->withError('Sent Ai-Stockist Fail');
                }

            } elseif ($type == 2) {
                $resule = Runpv_AiStockis::run_pv($type, $pv, $to_customer_user, Auth::guard('c_user')->user()->user_name);
                //dd($resule);
                if ($resule['status'] == 'success') {
                    return redirect('ai-stockist')->withSuccess('Sent Ai-Stockist Success');
                } else {
                    return redirect('ai-stockist')->withError('Sent Ai-Stockist Fail');
                }

            } elseif ($type == 3) {
                $resule = Runpv_AiStockis::run_pv($type, $pv, $to_customer_user, Auth::guard('c_user')->user()->user_name);
                //dd($resule);
                if ($resule['status'] == 'success') {
                    return redirect('ai-stockist')->withSuccess('Sent Ai-Stockist Success');
                } else {
                    return redirect('ai-stockist')->withError('Sent Ai-Stockist Fail');
                }
            } else {
                return redirect('ai-stockist')->withError('ไม่มีคุณสมบัติที่เลือก');
            }

        }

    }

}
