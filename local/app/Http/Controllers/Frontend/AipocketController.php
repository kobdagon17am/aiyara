<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Frontend\LineModel;
use App\Models\Frontend\Runpv;
use Auth;
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

        $columns = array(
            0 => 'order',
            1 => 'order_code',
            2 => 'created_at',
            3 => 'customer_id',
            4 => 'to_customer_id',
            5 => 'type',
            6 => 'pv',
            7 => 'banlance',
            8 => 'detail',
            //status
        );

        //1 รอส่งเอกสารการชำระเงิน warning
        //2 ตรวจสอบการชำระเงิน warning
        //3 เอกสารการชำระเงินไม่ผ่าน danger
        //4 จัดเตรียมสินค้า primary
        //5 จัดส่งสินค้า primary
        //6 ได้รับสินค้าแล้ว Success

        if (empty($request->input('search.value'))) {

            $totalData = DB::table('ai_stockist')
                ->leftjoin('customers as c_use', 'ai_stockist.customer_id', '=', 'c_use.id')
                ->leftjoin('customers as c_to', 'ai_stockist.to_customer_id', '=', 'c_to.id')
                ->leftjoin('dataset_orders_type', 'ai_stockist.type_id', '=', 'dataset_orders_type.group_id')
                ->where('dataset_orders_type.lang_id', '=', '1')

                ->whereRaw('(ai_stockist.customer_id = ' . Auth::guard('c_user')->user()->id . ' or  ai_stockist.to_customer_id =' . Auth::guard('c_user')->user()->id . ')')
                ->count();

            $totalFiltered = $totalData;
            $limit = $request->input('length');
            $start = $request->input('start');
            //$order = $columns[$request->input('order.0.column')];
            ////$dir = $request->input('order.0.dir');

            $ai_stockist = DB::table('ai_stockist')
                ->select('ai_stockist.*', 'c_use.business_name as business_name_use', 'c_to.business_name as business_name_to', 'c_use.user_name as c_use', 'c_to.user_name as c_to', 'dataset_orders_type.orders_type')
                ->leftjoin('customers as c_use', 'ai_stockist.customer_id', '=', 'c_use.id')
                ->leftjoin('customers as c_to', 'ai_stockist.to_customer_id', '=', 'c_to.id')
                ->leftjoin('dataset_orders_type', 'ai_stockist.type_id', '=', 'dataset_orders_type.group_id')
                ->where('dataset_orders_type.lang_id', '=', '1')
                ->whereRaw('(ai_stockist.customer_id = ' . Auth::guard('c_user')->user()->id . ' or  ai_stockist.to_customer_id =' . Auth::guard('c_user')->user()->id . ')')
                ->offset($start)
                ->limit($limit)
                ->orderby('ai_stockist.updated_at', 'DESC')
                ->get();

        } else {

            $search = trim($request->input('search.value'));

            $totalData = DB::table('ai_stockist')
                ->leftjoin('customers as c_use', 'ai_stockist.customer_id', '=', 'c_use.id')
                ->leftjoin('customers as c_to', 'ai_stockist.to_customer_id', '=', 'c_to.id')
                ->leftjoin('dataset_orders_type', 'ai_stockist.type_id', '=', 'dataset_orders_type.group_id')
                ->where('dataset_orders_type.lang_id', '=', '1')
                ->whereRaw('(ai_stockist.customer_id = ' . Auth::guard('c_user')->user()->id . ' or  ai_stockist.to_customer_id =' . Auth::guard('c_user')->user()->id . ')')
                ->whereRaw('(c_use.user_name LIKE "%' . $search . '%" or c_to.user_name LIKE "%' . $search . '%")')
                ->count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            //$order = $columns[$request->input('order.0.column')];
            //$dir = $request->input('order.0.dir');

            $ai_stockist = DB::table('ai_stockist')
                ->select('ai_stockist.*', 'c_use.business_name as business_name_use', 'c_to.business_name as business_name_to', 'c_use.user_name as c_use', 'c_to.user_name as c_to', 'dataset_orders_type.orders_type')
                ->leftjoin('customers as c_use', 'ai_stockist.customer_id', '=', 'c_use.id')
                ->leftjoin('customers as c_to', 'ai_stockist.to_customer_id', '=', 'c_to.id')
                ->leftjoin('dataset_orders_type', 'ai_stockist.type_id', '=', 'dataset_orders_type.group_id')
                ->where('dataset_orders_type.lang_id', '=', '1')
                ->whereRaw('(ai_stockist.customer_id = ' . Auth::guard('c_user')->user()->id . ' or  ai_stockist.to_customer_id =' . Auth::guard('c_user')->user()->id . ')')
                ->whereRaw('(c_use.user_name LIKE "%' . $search . '%" or c_to.user_name LIKE "%' . $search . '%" or c_use.business_name LIKE "%' . $search . '%" or c_to.business_name LIKE "%' . $search . '%")')
                ->offset($start)
                ->limit($limit)
                ->orderby('ai_stockist.updated_at', 'DESC')
                ->get();

            //dd($ai_stockist);

        }

        $i = $start;
        $data = array();

        foreach ($ai_stockist as $value) {
            $i++;

            $nestedData['order'] = $i;
            if (Auth::guard('c_user')->user()->id == $value->customer_id) {
                $nestedData['customer_id'] = '<span class="label label-success"><b style="color: #000"><i class="fa fa-user"></i> You </b></span>';

            } else {
                $nestedData['customer_id'] = $value->business_name_use . ' <b>( ' . $value->c_use . ' )</b>';

            }

            if (Auth::guard('c_user')->user()->id == $value->to_customer_id) {
                $nestedData['to_customer_id'] = '<span class="label label-success"><b style="color: #000"><i class="fa fa-user"></i> You </b></span>';

            } else {
                $nestedData['to_customer_id'] = $value->business_name_to . ' <b>( ' . $value->c_to . ' )</b>';

            }

            $nestedData['created_at'] = date('d/m/Y H:i:s', strtotime($value->created_at));
            $nestedData['type'] = $value->orders_type;
            $nestedData['order_code'] = $value->transection_code;

            if ($value->type_id == 4) {
                $pv = '<b class="text-success">' . $value->pv . '</b>';
            } else {
                $pv = '<b class="text-danger"> -' . $value->pv . '</b>';

            }

            $nestedData['pv'] = $pv;

            if ($value->status == 'success') {
                if (empty($value->banlance)) {
                    $banlance = '';
                } else {
                    $banlance = number_format($value->banlance);
                }

            } elseif ($value->status == 'panding') {
                $class_css = 'warning';
                $banlance = '<span class="label label-' . $class_css . '"><b style="color: #000">' . $value->status . '</b></span>';
            } else {
                $class_css = 'danger';
            }

            // $nestedData['status'] =  '<span class="label label-'.$class_css.'"><b style="color: #000">'.$value->status.'</b></span>';

            $nestedData['banlance'] = $banlance;

            if ($value->detail == 'Sent Ai-Stockist') {
                $detail = '';
            } else {
                $detail = $value->detail;

            }

            $nestedData['detail'] = $detail;

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

    public function check_customer_id(Request $request)
    {

        $resule = LineModel::check_line_aipocket($request->user_name);

        if ($resule['status'] == 'success') {

            if (empty($resule['data']->pv_mt_active) || (strtotime($resule['data']->pv_mt_active) < strtotime(date('Ymd')))) {
                $pv_mt_active = '<span class="label label-danger"  data-toggle="tooltip" data-placement="right" data-original-title="' . date('d/m/Y', strtotime($resule['data']->pv_mt_active)) . '"  style="font-size: 14px">Not Active </span>  ';

            } else {
                $pv_mt_active = '<span class="label label-info" style="font-size: 14px">Active ถึง ' . date('d/m/Y', strtotime($resule['data']->pv_mt_active)) . '</span>';
            }

            if (empty($resule['data']->pv_tv_active) || (strtotime($resule['data']->pv_tv_active) < strtotime(date('Ymd')))) {
                $pv_tv_active = '<span class="label label-danger"  data-toggle="tooltip" data-placement="right" data-original-title="' . date('d/m/Y', strtotime($resule['data']->pv_tv_active)) . '"  style="font-size: 14px">Not Active </span>  ';

            } else {
                $pv_tv_active = '<span class="label label-info" style="font-size: 14px">Active ถึง ' . date('d/m/Y', strtotime($resule['data']->pv_tv_active)) . '</span>';
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
        $username = $request->username;

        if ($pv > Auth::guard('c_user')->user()->pv_aipocket) {
            return redirect('ai-stockist')->withError('PV Ai-Stockist ของคุณมีไม่เพียงพอ ');

        } else {
            if ($type == 1) {
                $resule = Runpv::run_pv($type, $pv, $username);
                //dd($resule);
                if ($resule['status'] == 'success') {
                    return redirect('ai-stockist')->withSuccess('Sent Ai-Stockist Success');
                } else {
                    return redirect('ai-stockist')->withError('Sent Ai-Stockist Fail');
                }

            } elseif ($type == 2) {
                $resule = Runpv::run_pv($type, $pv, $username);
                //dd($resule);
                if ($resule['status'] == 'success') {
                    return redirect('ai-stockist')->withSuccess('Sent Ai-Stockist Success');
                } else {
                    return redirect('ai-stockist')->withError('Sent Ai-Stockist Fail');
                }

            } elseif ($type == 3) {
                $resule = Runpv::run_pv($type, $pv, $username);
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
