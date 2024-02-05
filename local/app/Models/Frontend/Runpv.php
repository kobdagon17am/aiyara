<?php
namespace App\Models\Frontend;

use App\Models\Frontend\RunNumberPayment;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

class Runpv extends Model
{
    public static function run_pv($type, $pv, $username)
    {
        if (!empty($type) || !empty($pv) || !empty($username)) {
     
                $id = Auth::guard('c_user')->user()->id;

                $user = DB::table('customers')
                    ->where('id', '=', $id)
                    ->limit(1)
                    ->get();

                if (empty($user)) {
                    $resule = ['status' => 'fail', 'message' => 'ไม่มี User นี้ในระบบ'];
                } else {
                    $pv_total = $user[0]->pv_aistockist - $pv;
                    if ($pv_total < 0) {
                        $resule = ['status' => 'fail', 'message' => 'ค่า Pv ไม่พอสำหรับการใช้งาน'];
                    } else {
                        $update_pv = DB::table('customers') //update PV
                            ->where('id', $id)
                            ->update(['pv_aistockist' => $pv_total]);

                        //run slot
                        if ($type == 1) { //ทำคุณสมบติ

                            $to_customer_id = DB::table('customers')
                                ->where('user_name', '=', $username)
                                ->first();

                            $transection_code = RunNumberPayment::run_number_aistockis();
                            DB::table('ai_stockist')
                                ->insert(['customer_id' => $id,
                                    'to_customer_id' => $to_customer_id->id,
                                    'transection_code' => $transection_code,
                                    'set_transection_code' => date('ym'),
                                    'pv' => $pv,
                                    'status' => 'success',
                                    'type_id' => $type,
                                    //'detail' => 'Sent Ai-Stockis',
                                    'banlance' => $pv_total,
                                ]);

                            $customer_user = DB::table('customers') //อัพ Pv ของตัวเอง
                                ->select('*')
                                ->where('user_name', '=', $username)
                                ->first();
                            //dd($customer_user);

                            $add_pv = $customer_user->pv + $pv;
                            $update_pv = DB::table('customers')
                                ->where('id', $customer_user->id)
                                ->update(['pv' => $add_pv]);

                            //ทำคุณสมบัติตัวเอง
                            $upline_type = $customer_user->line_type;
                            $upline_id = $customer_user->upline_id;
                            $customer_id = $upline_id;
                            $last_upline_type = $upline_type;

                        } elseif ($type == 2) { //รักษาคุณสมบัติรายเดือน
                            $to_customer_id = DB::table('customers')
                                ->where('user_name', '=', $username)
                                ->first();

                            $transection_code = RunNumberPayment::run_number_aistockis();
                            DB::table('ai_stockist')
                                ->insert(['customer_id' => $id,
                                    'to_customer_id' => $to_customer_id->id,
                                    'transection_code' => $transection_code,
                                    'set_transection_code' => date('ym'),
                                    'pv' => $pv,
                                    'status' => 'success',
                                    'type_id' => $type,
                                    //'detail' => 'Sent Ai-Stockis',
                                    'banlance' => $pv_total,
                                ]);

                            $strtime_user = strtotime($to_customer_id->pv_mt_active);
                            $strtime = strtotime(date("Y-m-d"));

                            if ($to_customer_id->status_pv_mt == 'first') {

                                $start_month = date("Y-m");

                                $promotion_mt = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                                    ->select('*')
                                    ->where('code', '=', 'pv_mt')
                                    ->first();

                                $pro_mt = $promotion_mt->pv;
                                $pv_mt = $to_customer_id->pv_mt;
                                $pv_mt_all = $pv + $pv_mt;

                                if ($pv_mt_all >= $pro_mt) {
                                    //dd('หักลบค่อยอัพเดท');
                                    //หักลบค่อยอัพเดท
                                    $mt_mount = $pv_mt_all / $pro_mt;
                                    $mt_mount = floor($mt_mount); //จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
                                    $pv_mt_total = $pv_mt_all - ($mt_mount * $pro_mt); //ค่า pv ที่ต้องเอาไปอัพเดท DB

                                    $mt_active = strtotime("+$mt_mount Month", strtotime($start_month));
                                    $mt_active = date('Y-m-t', $mt_active); //วันที่ mt_active

                                    $update_mt = DB::table('customers')
                                        ->where('id', $to_customer_id->id)
                                        ->update(['pv_mt' => $pv_mt_total, 'pv_mt_active' => $mt_active,
                                            'status_pv_mt' => 'not', 'date_mt_first' => date('Y-m-d h:i:s')]);

                                } else {
                                    //dd('อัพเดท');
                                    $update_mt = DB::table('customers')
                                        ->where('id', $to_customer_id->id)
                                        ->update(['pv_mt' => $pv_mt_all,
                                            'pv_mt_active' => date('Y-m-t', strtotime($start_month)),
                                            'status_pv_mt' => 'not', 'date_mt_first' => date('Y-m-d h:i:s')]);
                                }

                                $upline_type = $to_customer_id->line_type;
                                $upline_id = $to_customer_id->upline_id;
                                $customer_id = $upline_id;
                                $last_upline_type = $upline_type;

                            } else {
                                $promotion_mt = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                                    ->select('*')
                                    ->where('code', '=', 'pv_mt')
                                    ->first();

                                $pro_mt = $to_customer_id->pv;
                                $pv_mt = $to_customer_id->pv_mt;
                                $pv_mt_all = $pv + $pv_mt;

                                if ($strtime_user > $strtime) {

                                    // $contract_date = strtotime(date('Y-m',$strtime_user));

                                    // $caltime = strtotime("+1 Month",$contract_date);
                                    // $start_month = date("Y-m", $caltime);
                                    $start_month = date('Y-m', $strtime_user);

                                } else {
                                    $start_month = date("Y-m");

                                }

                                if ($pv_mt_all >= $pro_mt) {

                                    //หักลบค่อยอัพเดท
                                    $mt_mount = $pv_mt_all / $pro_mt;
                                    $mt_mount = floor($mt_mount); //จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
                                    $pv_mt_total = $pv_mt_all - ($mt_mount * $pro_mt); //ค่า pv ที่ต้องเอาไปอัพเดท DB

                                    $strtime = strtotime($start_month);
                                    $mt_active = strtotime("+$mt_mount Month", $strtime);
                                    $mt_active = date('Y-m-t', $mt_active); //วันที่ mt_active

                                    $update_mt = DB::table('customers')
                                        ->where('id', $to_customer_id->id)
                                        ->update(['pv_mt' => $pv_mt_total, 'pv_mt_active' => $mt_active]);

                                } else {
                                    //dd('อัพเดท');
                                    $update_mt = DB::table('customers')
                                        ->where('id', $to_customer_id->id)
                                        ->update(['pv_mt' => $pv_mt_all]);

                                    $upline_type = $to_customer_id->line_type;
                                    $upline_id = $to_customer_id->upline_id;
                                    $customer_id = $upline_id;
                                    $last_upline_type = $upline_type;

                                }
                            }

                        } elseif ($type == 3) { //รักษาคุณสมบัติท่องเที่ยง

                          $to_customer_id = DB::table('customers')
                          ->where('user_name', '=', $username)
                          ->first();

                          $transection_code = RunNumberPayment::run_number_aistockis();
                          DB::table('ai_stockist')
                              ->insert(['customer_id' => $id,
                                  'to_customer_id' => $to_customer_id->id,
                                  'transection_code' => $transection_code,
                                  'set_transection_code' => date('ym'),
                                  'pv' => $pv,
                                  'status' => 'success',
                                  'type_id' => $type,
                                  //'detail' => 'Sent Ai-Stockis',
                                  'banlance' => $pv_total,
                              ]);


                            $strtime_user = strtotime($to_customer_id->pv_tv_active);
                            $strtime = strtotime(date("Y-m-d"));

                            $promotion_tv = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                                ->select('*')
                                ->where('code', '=', 'pv_tv')
                                ->first();

                            $pro_tv = $promotion_tv->pv;
                            $pv_tv = $to_customer_id->pv_tv;
                            $pv_tv_all = $pv + $pv_tv;

                            if ($strtime_user > $strtime) {

                                $start_month = date('Y-m', $strtime_user);

                            } else {
                                $start_month = date("Y-m");
                            }
                            if ($pv_tv_all >= $pro_tv) {

                                //หักลบค่อยอัพเดท
                                $tv_mount = $pv_tv_all / $pro_tv;
                                $tv_mount = floor($tv_mount); //จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
                                $pv_tv_total = $pv_tv_all - ($tv_mount * $pro_tv); //ค่า pv ที่ต้องเอาไปอัพเดท DB

                                $add_mount = $tv_mount - 1;
                                $strtime = strtotime($start_month);
                                $tv_active = strtotime("+$add_mount Month", $strtime);
                                $tv_active = date('Y-m-t', $tv_active); //วันที่ tv_active

                                $update_mt = DB::table('customers')
                                    ->where('id', $to_customer_id->id)
                                    ->update(['pv_tv' => $pv_tv_total, 'pv_tv_active' => $tv_active]);
                                //dd($tv_active);

                            } else {
                                //dd('อัพเดท');
                                $update_mt = DB::table('customers')
                                    ->where('id', $to_customer_id->id)
                                    ->update(['pv_tv' => $pv_tv_all]);
                            }
                            $upline_type = $to_customer_id->line_type;
                            $upline_id = $to_customer_id->upline_id;
                            $customer_id = $upline_id;
                            $last_upline_type = $upline_type;

                        } else {
                            $resule = ['status' => 'fail', 'message' => 'ไม่มี Type ที่เลือก'];

                        }

                        if ($customer_id != 'AA') {
                            $j = 2;
                            for ($i = 1; $i <= $j; $i++) {

                                $data_user = DB::table('customers')
                                    ->where('id', '=', $customer_id)
                                    ->first();

                                $upline_type = $data_user->line_type;
                                $upline_id = $data_user->upline_id;

                                if ($upline_id == 'AA') {

                                    if ($last_upline_type == 'A') {

                                        $add_pv = $data_user->pv_a + $pv;
                                        $update_pv = DB::table('customers')
                                            ->where('id', $customer_id)
                                            ->update(['pv_a' => $add_pv]);

                                        $resule = ['status' => 'success', 'message' => 'Pv upline Type 1 Success'];
                                        $j = 0;

                                    } elseif ($last_upline_type == 'B') {
                                        $add_pv = $data_user->pv_b + $pv;
                                        $update_pv = DB::table('customers')
                                            ->where('id', $customer_id)
                                            ->update(['pv_b' => $add_pv]);

                                        $resule = ['status' => 'success', 'message' => 'Pv upline Type 1 Success'];
                                        $j = 0;

                                    } elseif ($last_upline_type == 'C') {
                                        $add_pv = $data_user->pv_c + $pv;
                                        $update_pv = DB::table('customers')
                                            ->where('id', $customer_id)
                                            ->update(['pv_c' => $add_pv]);
                                            

                                        $resule = ['status' => 'success', 'message' => 'Pv upline Type 1 Success'];
                                        $j = 0;

                                    } else {
                                        $resule = ['status' => 'fail', 'message' => 'Data Null Not Upline ID Type AA'];
                                        $j = 0;
                                    }

                                } else {

                                    if ($last_upline_type == 'A') {

                                        $add_pv = $data_user->pv_a + $pv;
                                        $update_pv = DB::table('customers')
                                            ->where('id', $customer_id)
                                            ->update(['pv_a' => $add_pv]);

                                        $customer_id = $upline_id;
                                        $last_upline_type = $upline_type;
                                        $j = $j + 1;

                                    } elseif ($last_upline_type == 'B') {
                                        $add_pv = $data_user->pv_b + $pv;
                                        $update_pv = DB::table('customers')
                                            ->where('id', $customer_id)
                                            ->update(['pv_b' => $add_pv]);

                                        $customer_id = $upline_id;
                                        $last_upline_type = $upline_type;
                                        $j = $j + 1;

                                    } elseif ($last_upline_type == 'C') {
                                        $add_pv = $data_user->pv_c + $pv;
                                        $update_pv = DB::table('customers')
                                            ->where('id', $customer_id)
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

                        }

                    }

                }
                if ($resule['status'] == 'success') {
                    
                    //DB::rollback();
                    return $resule;
                } else {
                  
                    return $resule;
                }

       

            $resule = ['status' => 'success', 'message' => 'Yess'];

        } else {
            $resule = ['status' => 'fail', 'message' => 'Data in Null'];
        }

        return $resule;

    }

}
