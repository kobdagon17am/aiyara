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
use App\Http\Controllers\Frontend\Fc\RunPvController;
use App\Models\Db_Ai_stockist;
use App\Models\Frontend\Customer;

class DropshipPointController extends Controller
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

    return view('frontend/dropship-point', compact('type'));
  }

  public function dt_dropship(Request $request)
  {
    //$date = date('Y-m-d');

    $sTable = DB::table('ai_stockist')
      ->select(
        'ai_stockist.*',
        'c_use.business_name as business_name_use',
        'c_to.business_name as business_name_to',
        'c_use.user_name as c_use',
        'c_to.user_name as c_to',
        'dataset_orders_type.orders_type',
        'users.name as vip_name',
        'users.last_name as vip_last_name'
      )
      ->leftjoin('customers as c_use', 'ai_stockist.customer_id', '=', 'c_use.id')
      ->leftjoin('customers as c_to', 'ai_stockist.to_customer_id', '=', 'c_to.id')
      ->leftjoin('users', 'users.id', '=', 'ai_stockist.user_id_fk')
      ->leftjoin('dataset_orders_type', 'ai_stockist.type_id', '=', 'dataset_orders_type.group_id')
      ->where('dataset_orders_type.lang_id', '=', '1')
      ->where('ai_stockist.order_channel', '=', 'VIP')
      ->where('ai_stockist.status', '!=', 'panding')
      ->whereRaw('(ai_stockist.customer_id = ' . Auth::guard('c_user')->user()->id . ' or  ai_stockist.to_customer_id =' . Auth::guard('c_user')->user()->id . ')')
      ->get();


    $sQuery = DataTables::of($sTable);

    return $sQuery

      ->addColumn('created_at', function ($row) {
        $data = '<label class="label label-inverse-info-border"><b>' . date('Y/m/d H:i:s', strtotime($row->created_at)) . '</b></label>';

        return $data;
      })

      ->addColumn('code_order', function ($row) {
        return $row->code_order;
      })

      ->addColumn('customer_id', function ($row) {

        if ($row->order_channel != 'VIP') {
          if (Auth::guard('c_user')->user()->id == $row->customer_id) {
            $data = '<span class="label label-success"><b style="color: #000"><i class="fa fa-user"></i> You </b></span>';
          } else {
            $data = $row->business_name_use . ' <b>( ' . $row->c_use . ' )</b>';
          }
        } else {

          $data = $row->vip_name . ' ' . $row->vip_last_name . ' <b>(VIP Shop)</b>';
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
        if (Auth::guard('c_user')->user()->id == $row->customer_id) {
          if ($row->status_add_remove == 'add') {
            $pv = '<b class="text-success">' . $row->pv . '</b>';
          } else {
            $pv = '<b class="text-danger"> -' . $row->pv . '</b>';
          }
        } else {
          if ($row->status_add_remove == 'add') {
            $pv = '<b class="text-danger"> -' . $row->pv . '</b>';
          } else {
            $pv = '<b class="text-success">' . $row->pv . '</b>';
          }
        }
        return $pv;
      })

      ->addColumn('banlance', function ($row) {

        if (Auth::guard('c_user')->user()->id == $row->customer_id) {
          if ($row->status == 'success') {
            if (empty($row->banlance)) {
              $banlance = '0';
            } else {
              $banlance = number_format($row->banlance);
            }
          } else {
            if (empty($row->banlance)) {
              $banlance = '0';
            } else {
              $banlance = number_format($row->banlance);
            }
          }
        } else {

          $banlance = '';
        }

        return $banlance;
      })

      ->addColumn('status', function ($row) {


        if ($row->status == 'success') {
          $status = '<span class="label label-success"><b style="color: #000">' . $row->status . '</b></span>';
        } elseif ($row->status == 'cancel') {
          $status = '<span class="label label-warning"><b style="color: #000">' . $row->status . '</b></span>';
        } elseif ($row->status == 'panding') {
          $status = '<span class="label label-warning"><b style="color: #000">' . $row->status . '</b></span>';
        } else { //fail
          $status = '<span class="label label-danger"><b style="color: #000">' . $row->status . '</b></span>';
        }

        return $status;
      })

      ->addColumn('detail', function ($row) {

        if ($row->detail == 'Sent Ai-Stockist') {
          $detail = '';
        } else {
          $detail = $row->detail;
        }

        return $detail;
      })


      ->addColumn('action', function ($row) {

        if ($row->status == 'success' and Auth::guard('c_user')->user()->id == $row->customer_id) {

          if ($row->cancel_expiry_date == '' || $row->cancel_expiry_date == '00-00-00 00:00:00' || (strtotime('now') > strtotime($row->cancel_expiry_date))) {
            $action = '';
          } else {
            if ($row->code_order) {
              $action = '';
            } else {
              $action = '<a class="btn btn-sm btn-warning"  data-toggle="modal" data-target="#cancel_aistockist" onclick="cancel_aistockist(' . $row->id . ',\'' . $row->transection_code . '\')" ><i class="fa fa-reply-all"></i> Cancel</a>';
            }
          }
        } else {

          $action = '';
        }

        return $action;
      })

      ->rawColumns(['created_at', 'customer_id', 'to_customer_id', 'type', 'pv', 'banlance', 'detail', 'action', 'status'])
      ->make(true);
  }

  public function dt_aistockist_panding(Request $request)
  {
    //$date = date('Y-m-d');

    $sTable = DB::table('ai_stockist')
      ->select(
        'ai_stockist.*',
        'c_use.business_name as business_name_use',
        'c_to.business_name as business_name_to',
        'c_use.user_name as c_use',
        'c_to.user_name as c_to',
        'dataset_orders_type.orders_type',
        'users.name as vip_name',
        'users.last_name as vip_last_name'
      )
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

        if ($row->order_channel != 'VIP') {
          if (Auth::guard('c_user')->user()->id == $row->customer_id) {
            $data = '<span class="label label-success"><b style="color: #000"><i class="fa fa-user"></i> You </b></span>';
          } else {
            $data = $row->business_name_use . ' <b>( ' . $row->c_use . ' )</b>';
          }
        } else {

          $data = $row->vip_name . ' ' . $row->vip_last_name . ' <b>(VIP Shop)</b>';
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


      ->addColumn('action', function ($row) {

        if ($row->status == 'success' and Auth::guard('c_user')->user()->id == $row->customer_id) {

          if ($row->cancel_expiry_date == '' || $row->cancel_expiry_date == '00-00-00 00:00:00' || (strtotime('now') > strtotime($row->cancel_expiry_date))) {
            $action = '';
          } else {
            if ($row->order_id_fk and $row->code_order) {
              $action = '<a class="btn btn-sm btn-warning"  data-toggle="modal" data-target="#cancel" onclick="cancel_order(' . $row->order_id_fk . ',\'' . $row->code_order . '\')" ><i class="fa fa-reply-all"></i> Cancel</a>';
            } else {
              $action = '2';
            }
          }
        } else {

          $action = '';
        }

        return $action;
      })

      ->rawColumns(['created_at', 'customer_id', 'to_customer_id', 'type', 'pv', 'banlance', 'action'])
      ->make(true);
  }


  public function check_customer_id(Request $request)
  {

    $user_name = Auth::guard('c_user')->user()->user_name;
    $kyc = \App\Helpers\Frontend::check_kyc($request->user_name);
    if($kyc['status'] == 'fail'){
      return $kyc;
    }
    $resule = LineModel::check_line_backend($user_name, $request->user_name);
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

  public function check_customer_aistockis(Request $request)
  {

    $user_name = Auth::guard('c_user')->user()->user_name;
    // if($user_name == $request->user_name || strtolower($user_name) ==  strtolower($request->user_name)){
    //   $resule['message'] = 'ไม่สามารถใช้ User ตัวเองในการซื้อผ่าน Aistockist ได้';
    //   $resule['status'] = 'fail';
    //   $data = array('status' => 'fail', 'data' => $resule);
    //   return $data;
    // }
    $resule = LineModel::check_line_backend($user_name, $request->user_name);
    if ($resule['status'] == 'success') {

     if( $resule['data']->aistockist_status== 0){
      $resule['message'] = 'User นี้ไม่เป็น Aistockist';
      $resule['status'] = 'fail';
      $data = array('status' => 'fail', 'data' => $resule);
      return $data;
     }
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

  public function use_dropship(Request $request)
  {
    $type = $request->type;
    $pv = str_replace(',', '', $request->pv);
    $to_customer_user = $request->username;
    $check_register = DB::table('customers')
    ->where('user_name', '=', $to_customer_user)
    ->first();

    if($check_register){
      $check_register =  DB::table('customers')
      ->where('user_name','=',$to_customer_user)
      ->where('regis_doc1_status','=','1')
      ->where('regis_doc2_status','=','1')
      ->where('regis_doc3_status','=','1')
      // ->where('regis_doc4_status','=','1')
      ->where('regis_date_doc','!=',null)
      ->count();

      if($check_register == 0 ){
        return redirect('dropship-point')->withError($to_customer_user.' ยังไม่ผ่านการตรวจเอกสาร ไม่สามารถโอนคะแนนได้');
      }

    }else{
      return redirect('dropship-point')->withError('ไม่พบข้อมูลผู้ในระบบ กรุณาตรวจสอบข้อมูล UserName');
    }

    if ($pv > Auth::guard('c_user')->user()->drop_ship_bonus) {
      return redirect('dropship-point')->withError('Dropship point ของคุณมีไม่เพียงพอ ');
    } else {
      if ($type == 1) {
        $resule = Runpv_AiStockis::run_pv_dropship($type, $pv, $to_customer_user, Auth::guard('c_user')->user()->user_name);

        //dd($resule);
        if ($resule['status'] == 'success') {
           $update_package = \App\Http\Controllers\Frontend\Fc\RunPvController::update_package($to_customer_user);

          return redirect('dropship-point')->withSuccess('Sent Dropship point Success');
        } else {
          return redirect('dropship-point')->withError('Sent Dropship point Fail');
        }
      } elseif ($type == 2) {
        $resule = Runpv_AiStockis::run_pv_dropship($type, $pv, $to_customer_user, Auth::guard('c_user')->user()->user_name);
        //dd($resule);
        if ($resule['status'] == 'success') {
          $update_package = \App\Http\Controllers\Frontend\Fc\RunPvController::update_package($to_customer_user);
          return redirect('dropship-point')->withSuccess('Sent Dropship point Success');
        } else {
          return redirect('dropship-point')->withError('Sent Dropship point Fail');
        }
      } elseif ($type == 3) {
        $resule = Runpv_AiStockis::run_pv_dropship($type, $pv, $to_customer_user, Auth::guard('c_user')->user()->user_name);
        //dd($resule);
        if ($resule['status'] == 'success') {
          $update_package = \App\Http\Controllers\Frontend\Fc\RunPvController::update_package($to_customer_user);
          return redirect('dropship-point')->withSuccess('Sent Dropship point Success');
        } else {
          return redirect('dropship-point')->withError('Sent Dropship point Fail');
        }
      } else {
        return redirect('dropship-point')->withError('ไม่มีคุณสมบัติที่เลือก');
      }
    }
  }


  public static function cancel_dropship(Request $rs)
  {

    if (empty($rs->cancel_code)) {
      return redirect('ai-stockist')->withError('ไม่พบข้อมูลเลขบิล กรุณาติดต่อเจ้าหน้าที่');
    }

    $ai_stockist = DB::table('ai_stockist')
      ->select(
        'ai_stockist.*',
        'c_use.business_name as business_name_use',
        'c_to.business_name as business_name_to',
        'c_use.user_name as c_use',
        'c_to.user_name as c_to',
        'dataset_orders_type.orders_type',

      )
      ->leftjoin('customers as c_use', 'ai_stockist.customer_id', '=', 'c_use.id')
      ->leftjoin('customers as c_to', 'ai_stockist.to_customer_id', '=', 'c_to.id')
      ->leftjoin('dataset_orders_type', 'ai_stockist.type_id', '=', 'dataset_orders_type.group_id')
      ->where('dataset_orders_type.lang_id', '=', '1')
      ->where('ai_stockist.transection_code', '=', $rs->cancel_code)
      ->where('ai_stockist.status', '=', 'success')
      ->where('ai_stockist.customer_id', '=', Auth::guard('c_user')->user()->id)
      ->first();



    if (empty($ai_stockist)) {
      return redirect('ai-stockist')->withError('ไม่พบข้อมูลเลขบิล กรุณาติดต่อเจ้าหน้าที่');
    }

    $update_ai_stockist = new Db_Ai_stockist();
    $update_ai_stockist->customer_id = $ai_stockist->customer_id;
    $update_ai_stockist->to_customer_id = $ai_stockist->to_customer_id;
    $update_ai_stockist->transection_code = $ai_stockist->transection_code;
    $update_ai_stockist->set_transection_code = date('ym');
    $update_ai_stockist->pv = $ai_stockist->pv;
    $update_ai_stockist->status = 'cancel';
    $update_ai_stockist->type_id = $ai_stockist->type_id;
    $update_ai_stockist->code_order = $ai_stockist->code_order;
    $update_ai_stockist->order_id_fk = $ai_stockist->order_id_fk;
    $update_ai_stockist->order_channel = $ai_stockist->order_channel;
    $update_ai_stockist->status_transfer = 2;
    $update_ai_stockist->status_add_remove = 'add';
    $customer = Customer::find($ai_stockist->customer_id);
    $add_pv_aistockist = $customer->drop_ship_bonus + $ai_stockist->pv;
    $update_ai_stockist->banlance = $add_pv_aistockist;
    $update_ai_stockist->pv_aistockist = $add_pv_aistockist;

    $customer->drop_ship_bonus = $add_pv_aistockist;

    if ($ai_stockist->type_id == 1) { //ทำคุณสมบัติ


      $rs = RunPvController::Cancle_pv($ai_stockist->c_to, $ai_stockist->pv, $ai_stockist->type_id, $ai_stockist->transection_code);
    } elseif ($ai_stockist->type_id == 2) { //รักษาคุณสมบัติรายเดือน

      $rs =  \App\Http\Controllers\Frontend\Fc\Cancel_mt_tv::cancel_mt($ai_stockist->customer_id, $ai_stockist->pv);

      $customer->pv_mt = $rs['pv'];
      if ($rs['mt_active'] > 0) {

        $customer->pv_mt = $rs['pv'];
        $m = $rs['mt_active'];

        $mt_active = strtotime("-$m Month", strtotime($customer->pv_mt_active));

        $mt_active = date('Y-m-1', $mt_active); //วันที่ mt_active
        $customer->pv_mt_active = $mt_active;
      }

      $rs = RunPvController::Cancle_pv($ai_stockist->c_to, $ai_stockist->pv, $ai_stockist->type_id, $ai_stockist->transection_code);
    } elseif ($ai_stockist->type_id == 3) { //รักษาคุณสมบัติท่องเที่ยว
      $rs =  \App\Http\Controllers\Frontend\Fc\Cancel_mt_tv::cancel_tv($ai_stockist->customer_id, $ai_stockist->pv);

      $customer->pv_tv = $rs['pv'];
      if ($rs['tv_active'] > 0) {

        $customer->pv_tv = $rs['pv'];
        $m = $rs['tv_active'];

        $tv_active = strtotime("-$m Month", strtotime($customer->pv_tv_active));

        $tv_active = date('Y-m-1', $tv_active); //วันที่ mt_active
        $customer->pv_tv_active = $tv_active;
      }

      $rs = RunPvController::Cancle_pv($ai_stockist->c_to, $ai_stockist->pv, $ai_stockist->type_id, $ai_stockist->transection_code);

    } else {
      return redirect('dropship-point')->withError('ไม่สามารถยกเลิกบิลได้ กรุณาติดต่อเจ้าหน้าที่');
    }

    if ($rs['status'] == 'success') {

      $ai_stockist_update_not_cancel = DB::table('ai_stockist')
        ->where('transection_code',$ai_stockist->transection_code)
        ->update(['cancel_expiry_date' => null]);

      $update_ai_stockist->save();
      $customer->save();
      return redirect('dropship-point')->withSuccess($rs['message']);
    } else {
      return redirect('dropship-point')->withError($rs['message']);
    }
  }

}
