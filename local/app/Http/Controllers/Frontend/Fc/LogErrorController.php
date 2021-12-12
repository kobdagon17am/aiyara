<?php

namespace App\Http\Controllers\Frontend\Fc;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Customer;
use Illuminate\Support\Facades\DB;

class LogErrorController extends Controller
{

    public static function log_error($log) // RunPv ทำคุณสมบัติ
    {
      if($log){
        $insert_log = DB::table('log_error')->insert([
          'customer_user_name' => $log['customer_user_name'],
          'admin_user_name' => $log['admin_user_name'],
          'code_order' => $log['code_order'],
          'error_detail' => $log['error_detail'],
          'function' => $log['function'],
          'type' => $log['type'],//'admin','customer'
        ]);
      }

      if($insert_log){
        $resule = ['status' => 'success', 'message' => 'log Success'];
        return $resule;
      }else{
        $resule = ['status' => 'fail', 'message' => 'log Fail'];
        return $resule;

      }

    //   $log =  ['customer_user_name' => '12',
    //   'admin_user_name' => '13',
    //   'code_order' => 'O12344011',
    //   'error_detail' => 'error detail ',
    //   'function' => 'App\Http\Controllers\Frontend\Fc\LogErrorController::log_error()',
    //   'type' =>'admin',//'admin','customer'
    // ];
    // $rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);

    }


}
