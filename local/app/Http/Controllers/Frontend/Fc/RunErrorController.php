<?php

namespace App\Http\Controllers\Frontend\Fc;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Customer;
use Illuminate\Support\Facades\DB;

use App\Models\Frontend\RunNumberPayment;
use Illuminate\Database\Eloquent\Model;
use App\Models\Db_Ai_stockist;
use App\Http\Controllers\Frontend\Fc\RunPvController;

class RunErrorController extends Controller
{
  public static function index(){

    // $rs = \App\Http\Controllers\Frontend\Fc\RunErrorController::run_invoice_code();
    // dd($rs);

    // dd('qqq');
    // $x = 1350*4;
    // $rs = \App\Http\Controllers\Frontend\Fc\RunErrorController::Runpv('A684135',$x,1, $order_code = null);
    // dd($rs);

  //  $rs = \App\Models\Frontend\RunNumberPayment::run_payment_code(1,'product');
  //  dd($rs);


  // $rs = \App\Http\Controllers\Frontend\Fc\RunErrorController::add_pv_aistockist(4,2438,'A223356','A223356','O122083000964',1089);
  // dd($rs);
  }

  public static function run_invoice_code(){
    $order = DB::table('db_orders') //อัพ Pv ของตัวเอง
    ->where('invoice_code_id_fk', '!=', '')
    ->orwhere('invoice_code_id_fk', '!=', null)
    ->orderBy('id')
    ->get();

    $i = 0;
    foreach($order as $value){
      $i++;
      $check_payment_code = DB::table('db_invoice_code') //เจนเลข payment order
      ->where('order_id', '=', $value->id)
      ->first();


      if ($check_payment_code) {

        $update_order_payment_code = DB::table('db_invoice_code')
            ->where('order_id', $value->id)
            ->update(['order_payment_status' => 'Success',
                'order_payment_code' => $value->invoice_code_id_fk,
                'business_location_id' => $value->business_location_id_fk,
                'date_setting_code' => $value->date_setting_code,
                'type_order' => 'product']); //ลงข้อมูลบิลชำระเงิน

              $order_update = DB::table('db_orders')
              ->where('id', $value->id)
              ->update(['invoice_code_id_fk' => $check_payment_code->order_payment_code]); //ลงข้อมูลบิลชำระเงิน



    } else {


      $rs = \App\Models\Frontend\RunNumberPayment::run_payment_code($value->business_location_id_fk,'product');


        $inseart_order_payment_code = DB::table('db_invoice_code')->insert([
            'order_id' => $value->id,
            'order_payment_code' => $rs['code_order'],
            'order_payment_status' => 'Success',
            'business_location_id' => $value->business_location_id_fk,
            'date_setting_code' =>$value->date_setting_code,
            'type_order' => 'product']); //ลงข้อมูลบิลชำระเงิน

            $order_update = DB::table('db_orders')
            ->where('id', $value->id)
            ->update(['invoice_code_id_fk' => $rs['code_order']]); //ลงข้อมูลบิลชำระเงิน

    }
  }

  return 'success'.' '.$i;

  }

    public static function Runpv($username, $pv, $type, $order_code = null)
    {
      // $rs = \App\Http\Controllers\Frontend\Fc\RunErrorController::Runpv($username, $pv, $type, $order_code = null)


        // RunPv ทำคุณสมบัติ

        // dd($type);
        // dd(' username '.$username.'/ '.' pv '.$pv.'/ '.' type '.$type.'/ '.' order_code '.$order_code);
        // dd($pv);
        // dd($order_code);
        $user = DB::table('customers') //อัพ Pv ของตัวเอง
            ->select('id', 'pv')
            ->where('user_name', '=', $username)
            ->first();

        $log_user_name = $username;

        if (empty($user)) {
            $log = [
                'customer_user_name' => $username,
                'admin_user_name' => '',
                'code_order' => $order_code,
                'error_detail' =>
                    'Run PV Eror ไม่มีข้อมูล User :' . $log_user_name,
                'function' =>
                    'App\Http\Controllers\Frontend\Fc\RunPvController::Runpv()',
                'type' => '', //'admin','customer',
                'order_type' => $type,
            ];
            $rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);
            DB::commit();

            $resule = ['status' => 'fail', 'message' => 'ไม่มี User นี้ในระบบ'];
            return $resule;
        }

        $update_use = Customer::find($user->id);
        if ($type == 1 || $type == 6) {
            $add_pv = $user->pv + $pv;
            $update_use->pv = $add_pv;
        }

        //ทำคุณสมบัติตัวเอง
        $upline_type = $update_use->line_type;
        $upline_id = $update_use->upline_id;
        $customer_id = $upline_id;

        $last_upline_type = $upline_type;

        // dd($last_upline_type);

        try {
            DB::BeginTransaction();

            if ($customer_id != 'AA') {
                //run pv

                // dd($customer_id);

                $j = 2;
                for ($i = 1; $i <= $j; $i++) {
                    $data_user = DB::table('customers')
                        ->where('user_name', '=', $customer_id)
                        ->first();

                    if (empty($data_user)) {
                        $log = [
                            'customer_user_name' => $username,
                            'admin_user_name' => '',
                            'code_order' => $order_code,
                            'error_detail' =>
                                'Run PV Eror ไม่มีข้อมูล User :' .
                                $customer_id .
                                ' ที่เป็น Upline ของ ' .
                                $log_user_name,
                            'function' =>
                                'App\Http\Controllers\Frontend\Fc\RunPvController::Runpv()',
                            'type' => '', //'admin','customer',
                            'order_type' => $type,
                        ];
                        $rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);
                        $resule = [
                            'status' => 'success',
                            'message' => 'Not Upline',
                        ];
                        $update_use->save();
                        DB::commit();
                        //DB::rollback();
                        return $resule;
                    }

                    $upline_type = $data_user->line_type;
                    $upline_id = $data_user->upline_id;

                    if ($upline_id == 'AA') {
                        if ($last_upline_type == 'A') {
                            $add_pv = $data_user->pv_a + $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_a' => $add_pv]);

                            $resule = [
                                'status' => 'success',
                                'message' => 'Add PV Success',
                            ];
                            $j = 0;
                        } elseif ($last_upline_type == 'B') {
                            $add_pv = $data_user->pv_b + $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_b' => $add_pv]);

                            $resule = [
                                'status' => 'success',
                                'message' => 'Add PV Success',
                            ];
                            $j = 0;
                        } elseif ($last_upline_type == 'C') {
                            $add_pv = $data_user->pv_c + $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_c' => $add_pv]);

                            $resule = [
                                'status' => 'success',
                                'message' => 'Add PV Success',
                            ];
                            $j = 0;
                        } else {
                            $log = [
                                'customer_user_name' => $username,
                                'admin_user_name' => '',
                                'code_order' => $order_code,
                                'error_detail' =>
                                    'Run PV Eror ไม่มีข้อมูล User :' .
                                    $customer_id .
                                    ' ที่เป็น Upline ของ ' .
                                    $log_user_name,
                                'function' =>
                                    'App\Http\Controllers\Frontend\Fc\RunPvController::Runpv()',
                                'type' => '', //'admin','customer'
                                'order_type' => $type,
                            ];
                            $rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);
                            $resule = [
                                'status' => 'fail',
                                'message' => 'Data Null Not Upline ID Type AA',
                            ];
                            $j = 0;
                            DB::commit();
                            //DB::rollback();
                            return $resule;
                        }

                        // dd();
                    } else {
                        if ($last_upline_type == 'A') {
                            $add_pv = $data_user->pv_a + $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_a' => $add_pv]);

                            $customer_id = $upline_id;
                            $log_user_name = $data_user->user_name;
                            $last_upline_type = $upline_type;
                            $j = $j + 1;
                        } elseif ($last_upline_type == 'B') {
                            $add_pv = $data_user->pv_b + $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_b' => $add_pv]);

                            $customer_id = $upline_id;
                            $log_user_name = $data_user->user_name;
                            $last_upline_type = $upline_type;
                            $j = $j + 1;
                        } elseif ($last_upline_type == 'C') {
                            $add_pv = $data_user->pv_c + $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_c' => $add_pv]);

                            $customer_id = $upline_id;
                            $log_user_name = $data_user->user_name;
                            $last_upline_type = $upline_type;
                            $j = $j + 1;
                        } else {
                            $log = [
                                'customer_user_name' => $username,
                                'admin_user_name' => '',
                                'code_order' => $order_code,
                                'error_detail' =>
                                    'Run PV Eror UserName:' .
                                    $log_user_name .
                                    ' ไม่มีข้อมูลว่าสายใหน A B C',
                                'function' =>
                                    'App\Http\Controllers\Frontend\Fc\RunPvController::Runpv()',
                                'type' => '', //'admin','customer'
                                'order_type' => $type,
                            ];$rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);
                            $resule = [
                                'status' => 'fail',
                                'message' => 'Data Null Not Upline ID',
                            ];
                            $j = 0;
                            DB::commit();
                            //DB::rollback();
                            return $resule;
                        }
                    }
                }
            } else {
                $resule = [
                    'status' => 'success',
                    'message' => '',
                ];
                // dd($resule);
            }

            if ($resule['status'] == 'success') {
                $update_use->save();
                DB::commit();
                //DB::rollback();
                return $resule;
            } else {
                DB::rollback();
                return $resule;
            }
        } catch (Exception $e) {
            DB::rollback();
            $resule = [
                'status' => 'fail',
                'message' => 'Update PvPayment Fail',
            ];
            return $resule;
        }
    }

    public static function Cancle_pv($username, $pv, $type, $order_code)
    {
        // RunPv ทำคุณสมบัติ
        // dd($username);
        // dd($pv);

        $user = DB::table('customers') //อัพ Pv ของตัวเอง
            ->select('id', 'pv')
            ->where('user_name', '=', $username)
            ->first();

        $log_user_name = $username;

        if (empty($user)) {
            $log = [
                'customer_user_name' => $username,
                'admin_user_name' => '',
                'code_order' => $order_code,
                'error_detail' => 'ไม่มี User นี้ในระบบ UserName :' . $username,
                'function' =>
                    'App\Http\Controllers\Frontend\Fc\RunPvController::Cancle_pv()',
                'type' => '', //'admin','customer'
                'order_type' => $type,
            ];
            $rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);

            $resule = ['status' => 'fail', 'message' => 'ไม่มี User นี้ในระบบ'];
            return $resule;
        }

        $update_use = Customer::find($user->id);
        if ($type == 1 || $type == 6) {
            $add_pv = $user->pv - $pv;
            $update_use->pv = $add_pv;
        }

        //ทำคุณสมบัติตัวเอง
        $upline_type = $update_use->line_type;
        $upline_id = $update_use->upline_id;
        $customer_id = $upline_id;
        $last_upline_type = $upline_type;

        // dd($last_upline_type);

        try {
            DB::BeginTransaction();

            if ($customer_id != 'AA') {
                //run pv

                // dd($customer_id);

                $j = 2;
                for ($i = 1; $i <= $j; $i++) {
                    $data_user = DB::table('customers')
                        ->where('user_name', '=', $customer_id)
                        ->first();

                    if (empty($data_user)) {
                        $log = [
                            'customer_user_name' => $username,
                            'admin_user_name' => '',
                            'code_order' => $order_code,
                            'error_detail' =>
                                'Cancle PV Eror ไม่มีข้อมูล User :' .
                                $customer_id .
                                ' ที่เป็น Upline ของ ' .
                                $log_user_name,
                            'function' =>
                                'App\Http\Controllers\Frontend\Fc\RunPvController::Cancle_pv()',
                            'type' => '', //'admin','customer'
                            'order_type' => $type,
                        ];
                        $rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);
                        $resule = [
                            'status' => 'success',
                            'message' => 'Not Upline',
                        ];
                        $update_use->save();
                        DB::commit();
                        //DB::rollback();
                        return $resule;
                    }

                    $upline_type = $data_user->line_type;
                    $upline_id = $data_user->upline_id;

                    if ($upline_id == 'AA') {
                        if ($last_upline_type == 'A') {
                            $add_pv = $data_user->pv_a - $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_a' => $add_pv]);

                            $resule = [
                                'status' => 'success',
                                'message' => 'Pv upline Type 1 Success',
                            ];
                            $j = 0;
                        } elseif ($last_upline_type == 'B') {
                            $add_pv = $data_user->pv_b - $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_b' => $add_pv]);

                            $resule = [
                                'status' => 'success',
                                'message' => 'Pv upline Type 1 Success',
                            ];
                            $j = 0;
                        } elseif ($last_upline_type == 'C') {
                            $add_pv = $data_user->pv_c - $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_c' => $add_pv]);

                            $resule = [
                                'status' => 'success',
                                'message' => 'Pv upline Type 1 Success',
                            ];
                            $j = 0;
                        } else {

                          $log = [
                            'customer_user_name' => $username,
                            'admin_user_name' => '',
                            'code_order' => $order_code,
                            'error_detail' =>
                                'Run PV Eror ไม่มีข้อมูล User :' .
                                $customer_id .
                                ' ที่เป็น Upline ของ ' .
                                $log_user_name,
                            'function' =>
                                'App\Http\Controllers\Frontend\Fc\RunPvController::Cancle_pv()',
                            'type' => '', //'admin','customer'
                            'order_type' => $type,
                        ];
                        $rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);
                        $resule = [
                            'status' => 'fail',
                            'message' => 'Data Null Not Upline ID Type AA',
                        ];
                        $j = 0;
                        DB::commit();
                        //DB::rollback();
                        return $resule;

                      }

                        // dd();
                    } else {
                        // dd();

                        if ($last_upline_type == 'A') {
                            $add_pv = $data_user->pv_a - $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_a' => $add_pv]);

                            $customer_id = $upline_id;
                            $log_user_name = $customer_id;
                            $last_upline_type = $upline_type;
                            $j = $j + 1;
                        } elseif ($last_upline_type == 'B') {
                            $add_pv = $data_user->pv_b - $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_b' => $add_pv]);

                            $customer_id = $upline_id;
                            $last_upline_type = $upline_type;
                            $log_user_name = $customer_id;
                            $j = $j + 1;
                        } elseif ($last_upline_type == 'C') {
                            $add_pv = $data_user->pv_c - $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_c' => $add_pv]);

                            $customer_id = $upline_id;
                            $last_upline_type = $upline_type;
                            $log_user_name = $customer_id;
                            $j = $j + 1;
                        } else {
                          $log = [
                            'customer_user_name' => $username,
                            'admin_user_name' => '',
                            'code_order' => $order_code,
                            'error_detail' =>
                            'Run PV Eror UserName:'.$log_user_name .'ไม่มีข้อมูลว่าสายใหน A B C',
                            'function' =>'App\Http\Controllers\Frontend\Fc\RunPvController::Cancle_pv()',
                            'type' => '', //'admin','customer'
                            'order_type' => $type,
                        ];$rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);
                        $resule = [
                            'status' => 'fail',
                            'message' => 'Data Null Not Upline ID',
                        ];
                        $j = 0;
                        DB::commit();
                        //DB::rollback();
                        return $resule;

                            $resule = [
                                'status' => 'fail',
                                'message' => 'Data Null Not Upline ID',
                            ];
                            $j = 0;
                        }
                    }
                }
            } else {
                $resule = [
                    'status' => 'success',
                    'message' => '',
                ];

                // dd($resule);
            }

            if ($resule['status'] == 'success') {
                $update_use->save();
                DB::commit();
                //DB::rollback();
                return $resule;
            } else {
                DB::rollback();
                return $resule;
            }
        } catch (Exception $e) {
            DB::rollback();
            $resule = [
                'status' => 'fail',
                'message' => 'Update PvPayment Fail',
            ];
            return $resule;
        }
    }

    public static function update_package($username)
    {
        $user = DB::table('customers') //อัพ Pv ของตัวเอง
            ->select('id', 'pv', 'qualification_id', 'package_id')
            ->where('user_name', '=', $username)
            ->first();

        if ($user) {
            if ($user->pv < 500) {
                $update_package = DB::table('customers')
                    ->where('user_name', $username)
                    ->update(['package_id' => null]);

                $resule = [
                    'status' => 'success',
                    'message' => 'Update Package Success',
                ];
                return $resule;
            }

            $package = DB::table('dataset_package') //อัพ Pv ของตัวเอง
                ->where('dt_pv', '<=', $user->pv)
                ->orderBy('dt_pv', 'DESC')
                ->first();

            if (@$user->package_id == @$package->id) {
                $resule = [
                    'status' => 'success',
                    'message' => 'Not Update Package',
                ];
                return $resule;
            } else {

              if(@$package->id){
                $update_package = DB::table('customers')
                    ->where('user_name', $username)
                    ->update(['package_id' => $package->id]);
              }else{
                $update_package = DB::table('customers')
                    ->where('user_name', $username)
                    ->update(['package_id' => 1]);
              }

                $resule = [
                    'status' => 'success',
                    'message' => 'Update Package Success',
                ];
                return $resule;
            }
        } else {
            $log = [
                'customer_user_name' => $username,
                'admin_user_name' => '',
                'code_order' => '',
                'error_detail' => 'Update Package Fail ' . $username,
                'function' =>
                    'App\Http\Controllers\Frontend\Fc\RunPvController::update_package()',
                'type' => '', //'admin','customer'
                'order_type' => '',
            ];
            $rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);
            $resule = [
                'status' => 'fail',
                'message' => 'Data Null Not Upline ID',
            ];
            $j = 0;
            DB::commit();
            //DB::rollback();
            return $resule;
        }
    }




    public static function add_pv_aistockist($type, $pv, $to_customer_user, $username, $code_order = '', $order_id_fk = '')
    {
      try {
        DB::BeginTransaction();

        $user = DB::table('customers')
          ->select('id', 'pv_aistockist', 'user_name')
          ->where('user_name', '=', $username)
          ->first();

        $to_customer = DB::table('customers')
          ->select('id', 'pv_aistockist', 'user_name', 'pv_mt_active', 'status_pv_mt', 'pv_mt', 'pv_tv', 'pv')
          ->where('user_name', '=', $to_customer_user)
          ->first();

        if (empty($user) || empty($to_customer)) {
          $resule = ['status' => 'fail', 'message' => 'ไม่มี User นี้ในระบบ'];
          return $resule;
        } else {

          $update_use = Customer::find($user->id);
          $update_to_customer = Customer::find($to_customer->id);

          $pv_total = $user->pv_aistockist + $pv;

          $update_use->pv_aistockist = $pv_total;
          $transection_code = RunNumberPayment::run_number_aistockis();
          $update_ai_stockist = new Db_Ai_stockist();
          $update_ai_stockist->customer_id = $user->id;
          $update_ai_stockist->to_customer_id = $to_customer->id;
          $update_ai_stockist->transection_code = $transection_code;
          $update_ai_stockist->set_transection_code = date('ym');
          $update_ai_stockist->pv = $pv;
          $update_ai_stockist->status_add_remove = 'add';
          $update_ai_stockist->status = 'success';
          $update_ai_stockist->type_id = $type;
          $update_ai_stockist->banlance = $pv_total;
          $update_ai_stockist->code_order = $code_order;
          $update_ai_stockist->order_id_fk = $order_id_fk;
          $resule = ['status' => 'success', 'message' => 'ซื้อเพื่อเติม เก็บ log + PV AiStockist '];
        }

        if ($resule['status'] == 'success') {
          $update_ai_stockist->save();
          $update_use->save();
          DB::commit();
          //DB::rollback();
          return $resule;
        } else {
          DB::rollback();
          return $resule;
        }
      } catch (Exception $e) {
        DB::rollback();
        $resule = ['status' => 'fail', 'message' => 'Update PvPayment Fail'];
        return $resule;
      }
      $resule = ['status' => 'success', 'message' => 'Yess'];
      return $resule;
    }

}
