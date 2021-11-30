<?php

namespace App\Http\Controllers\Frontend\Fc;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Customer;
use Illuminate\Support\Facades\DB;

class RunPvController extends Controller
{

    public static function Runpv($username,$pv,$type) // RunPv ทำคุณสมบัติ

    {

        // dd($type);
        // dd($username);
        // dd($pv);

        $user = DB::table('customers') //อัพ Pv ของตัวเอง
            ->select('id','pv')
            ->where('user_name', '=', $username)
            ->first();

        if (empty($user)) {
            $resule = ['status' => 'fail', 'message' => 'ไม่มี User นี้ในระบบ'];
            return $resule;
        }

        $update_use = Customer::find($user->id);
        if($type == 1 || $type == 6){
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



            if ($customer_id != 'AA') { //run pv

                // dd($customer_id);

                $j = 2;
                for ($i = 1; $i <= $j; $i++) {

                    $data_user = DB::table('customers')
                        ->where('user_name', '=', $customer_id)
                        ->first();
                        if(empty($data_user)){
                          $resule = ['status' => 'success', 'message' => 'Not Upline'];
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

                            $resule = ['status' => 'success', 'message' => 'Pv upline Type 1 Success'];
                            $j = 0;

                        } elseif ($last_upline_type == 'B') {
                            $add_pv = $data_user->pv_b + $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_b' => $add_pv]);

                            $resule = ['status' => 'success', 'message' => 'Pv upline Type 1 Success'];
                            $j = 0;

                        } elseif ($last_upline_type == 'C') {
                            $add_pv = $data_user->pv_c + $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_c' => $add_pv]);

                            $resule = ['status' => 'success', 'message' => 'Pv upline Type 1 Success'];
                            $j = 0;

                        } else {
                            $resule = ['status' => 'fail', 'message' => 'Data Null Not Upline ID Type AA'];
                            $j = 0;
                        }

                        // dd();

                    } else {



                        if ($last_upline_type == 'A') {

                            $add_pv = $data_user->pv_a + $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_a' => $add_pv]);

                            $customer_id = $upline_id;
                            $last_upline_type = $upline_type;
                            $j = $j + 1;

                        } elseif ($last_upline_type == 'B') {
                            $add_pv = $data_user->pv_b + $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_b' => $add_pv]);

                            $customer_id = $upline_id;
                            $last_upline_type = $upline_type;
                            $j = $j + 1;

                        } elseif ($last_upline_type == 'C') {
                            $add_pv = $data_user->pv_c + $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_c' => $add_pv]);

                            $customer_id = $upline_id;
                            $last_upline_type = $upline_type;
                            $j = $j + 1;

                        } else {
                            $resule = ['status' => 'fail', 'message' => 'Data Null Not Upline ID'];
                            $j = 0;
                        }

                    }

                }

            } else {

                $resule = ['status' => 'success', 'message' => 'Pv add Type AA Success'];

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
            $resule = ['status' => 'fail', 'message' => 'Update PvPayment Fail'];
            return $resule;

        }

    }

    public static function Cancle_pv($username,$pv,$type) // RunPv ทำคุณสมบัติ

    {

        // dd($type);
        // dd($username);
        // dd($pv);

        $user = DB::table('customers') //อัพ Pv ของตัวเอง
            ->select('id','pv')
            ->where('user_name', '=', $username)
            ->first();

        if (empty($user)) {
            $resule = ['status' => 'fail', 'message' => 'ไม่มี User นี้ในระบบ'];
            return $resule;
        }

        $update_use = Customer::find($user->id);
        if($type == 1 || $type == 6){
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



            if ($customer_id != 'AA') { //run pv

                // dd($customer_id);

                $j = 2;
                for ($i = 1; $i <= $j; $i++) {

                    $data_user = DB::table('customers')
                        ->where('user_name', '=', $customer_id)
                        ->first();

                      if(empty($data_user)){
                          $resule = ['status' => 'success', 'message' => 'Not Upline'];
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

                            $resule = ['status' => 'success', 'message' => 'Pv upline Type 1 Success'];
                            $j = 0;

                        } elseif ($last_upline_type == 'B') {
                            $add_pv = $data_user->pv_b - $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_b' => $add_pv]);

                            $resule = ['status' => 'success', 'message' => 'Pv upline Type 1 Success'];
                            $j = 0;

                        } elseif ($last_upline_type == 'C') {
                            $add_pv = $data_user->pv_c - $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_c' => $add_pv]);

                            $resule = ['status' => 'success', 'message' => 'Pv upline Type 1 Success'];
                            $j = 0;

                        } else {
                            $resule = ['status' => 'fail', 'message' => 'Data Null Not Upline ID Type AA'];
                            $j = 0;
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
                            $last_upline_type = $upline_type;
                            $j = $j + 1;

                        } elseif ($last_upline_type == 'B') {
                            $add_pv = $data_user->pv_b - $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_b' => $add_pv]);

                            $customer_id = $upline_id;
                            $last_upline_type = $upline_type;
                            $j = $j + 1;

                        } elseif ($last_upline_type == 'C') {
                            $add_pv = $data_user->pv_c - $pv;
                            $update_pv = DB::table('customers')
                                ->where('user_name', $customer_id)
                                ->update(['pv_c' => $add_pv]);

                            $customer_id = $upline_id;
                            $last_upline_type = $upline_type;
                            $j = $j + 1;

                        } else {
                            $resule = ['status' => 'fail', 'message' => 'Data Null Not Upline ID'];
                            $j = 0;
                        }

                    }

                }

            } else {

                $resule = ['status' => 'success', 'message' => 'Pv add Type AA Success'];

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
            $resule = ['status' => 'fail', 'message' => 'Update PvPayment Fail'];
            return $resule;

        }

    }

}
