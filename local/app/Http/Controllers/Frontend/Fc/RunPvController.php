<?php

namespace App\Http\Controllers\Frontend\Fc;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Customer;
use Illuminate\Support\Facades\DB;

class RunPvController extends Controller
{

    public static function Runpv($username, $pv, $type, $order_code = null)
    {
        
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
                                'message' => 'Pv upline Type 1 Success',
                            ];
                            $j = 0;
                        } elseif ($last_upline_type == 'B') {
                            $add_pv = $data_user->pv_b + $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_b' => $add_pv]);

                            $resule = [
                                'status' => 'success',
                                'message' => 'Pv upline Type 1 Success',
                            ];
                            $j = 0;
                        } elseif ($last_upline_type == 'C') {
                            $add_pv = $data_user->pv_c + $pv;
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
                    'message' => 'Pv add Type AA Success',
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

        // dd($type);
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
                            $add_pv = $data_user->pv_a + $pv;
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
                    'message' => 'Pv add Type AA Success',
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
            if ($user->pv == 0) {
                $update_package = DB::table('customers')
                    ->where('user_name', $username)
                    ->update(['package_id' => 1]);

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
}
