<?php
namespace App\Models\Frontend;

use App\Models\Frontend\RunNumberPayment;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Frontend\Customer;
use App\Models\Db_Ai_stockist;
use App\Http\Controllers\Frontend\Fc\RunPvController;

class Runpv_AiStockis extends Model
{
    public static function run_pv($type,$pv,$to_customer_user,$username,$code_order='',$order_id_fk='')
    {
            try {
                DB::BeginTransaction();

                $user = DB::table('customers')
                ->select('id','pv_aistockist','user_name')
                ->where('user_name', '=', $username)
                ->first();


                $to_customer= DB::table('customers')
                ->select('id','pv_aistockist','user_name','pv_mt_active','pv_tv_active','status_pv_mt','pv_mt','pv_tv','pv')
                ->where('user_name', '=', $to_customer_user)
                ->first();

                if (empty($user) || empty($to_customer)) {
                    $resule = ['status' => 'fail', 'message' => 'ไม่มี User นี้ในระบบ'];
                    return $resule;
                } else {

                  $update_use = Customer::find($user->id);
                  $update_to_customer = Customer::find($to_customer->id);

                  $pv_total = $user->pv_aistockist - $pv;

                  if ($pv_total < 0) {
                        $resule = ['status' => 'fail', 'message' => 'ค่า Pv ไม่พอสำหรับการใช้งาน'];
                    } else {
                      $update_use->pv_aistockist = $pv_total;

                      $transection_code = RunNumberPayment::run_number_aistockis();
                      $update_ai_stockist = new Db_Ai_stockist();
                      $update_ai_stockist->customer_id = $user->id;
                      $update_ai_stockist->to_customer_id =$to_customer->id;
                      $update_ai_stockist->transection_code = $transection_code;
                      $update_ai_stockist->set_transection_code = date('ym');
                      $update_ai_stockist->pv = $pv;
                      $update_ai_stockist->status = 'success';
                      $update_ai_stockist->status_add_remove = 'remove';
                      $update_ai_stockist->type_id =$type;
                      $update_ai_stockist->banlance = $pv_total;
                      $update_ai_stockist->code_order = $code_order;
                      $update_ai_stockist->order_id_fk = $order_id_fk;

                        if ($type == 1) { //ทำคุณสมบติ

                          $resule =  RunPvController::Runpv($to_customer_user,$pv,$type,$transection_code);


                        } elseif ($type == 2) { //รักษาคุณสมบัติรายเดือน


                            $strtime_user = strtotime($to_customer->pv_mt_active);
                            $strtime = strtotime(date("Y-m-d"));

                            $promotion_mt = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                                    ->select('*')
                                    ->where('code', '=', 'pv_mt')
                                    ->first();

                            if ($to_customer->status_pv_mt == 'first') {

                                $start_month = date("Y-m");
                                $pro_mt = $promotion_mt->pv;
                                $pv_mt = $to_customer->pv_mt;
                                $pv_mt_all = $pv + $pv_mt;

                                if ($pv_mt_all >= $pro_mt) {
                                    //dd('หักลบค่อยอัพเดท');
                                    //หักลบค่อยอัพเดท
                                    $mt_mount = $pv_mt_all / $pro_mt;
                                    $mt_mount = floor($mt_mount); //จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
                                    $pv_mt_total = $pv_mt_all % $pro_mt; //ค่า pv ที่ต้องเอาไปอัพเดท DB
                                    $mt_mount_new = $mt_mount+2;
                                    $mt_active = strtotime("+$mt_mount_new Month", strtotime($start_month));
                                    $mt_active = date('Y-m-1', $mt_active); //วันที่ mt_active

                                    $update_to_customer->pv_mt = $pv_mt_total;
                                    $update_to_customer->pv_mt_active = $mt_active;
                                    $update_to_customer->status_pv_mt = 'not';
                                    $update_to_customer->date_mt_first = date('Y-m-d h:i:s');


                                } else {
                                    $mt_mount_new = strtotime("+2 Month", strtotime($start_month));
                                    $update_to_customer->pv_mt = $pv_mt_all;
                                    $update_to_customer->pv_mt_active = date('Y-m-1', $mt_mount_new);
                                    $update_to_customer->status_pv_mt = 'not';
                                    $update_to_customer->date_mt_first = date('Y-m-d h:i:s');
                                }

                                $resule = RunPvController::Runpv($to_customer_user,$pv,$type,$transection_code);


                            } else {

                                $pro_mt =  $promotion_mt->pv;
                                $pv_mt = $to_customer->pv_mt;
                                $pv_mt_all = $pv + $pv_mt;




                                if ($strtime_user > $strtime) {

                                    // $contract_date = strtotime(date('Y-m',$strtime_user));

                                    // $caltime = strtotime("+1 Month",$contract_date);
                                    // $start_month = date("Y-m", $caltime);
                                    $strtime_user = strtotime("-1 Month", $strtime_user);
                                    $start_month = date('Y-m', $strtime_user);


                                } else {

                                  $strtime_user = strtotime("-1 Month");
                                  $start_month = date('Y-m', $strtime_user);

                                }



                                if ($pv_mt_all >= $pro_mt) {


                                    //หักลบค่อยอัพเดท
                                    $mt_mount = $pv_mt_all / $pro_mt;
                                    $mt_mount = floor($mt_mount); //จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
                                    $pv_mt_total = $pv_mt_all % $pro_mt; //ค่า pv ที่ต้องเอาไปอัพเดท DB
                                    $mt_mount_new = $mt_mount+1;
                                    $strtime = strtotime($start_month);
                                    $mt_active = strtotime("+$mt_mount_new Month", $strtime);
                                    $mt_active = date('Y-m-1', $mt_active); //วันที่ mt_active

                                    $update_to_customer->pv_mt = $pv_mt_total;
                                    $update_to_customer->pv_mt_active =  $mt_active;

                                } else {

                                    $update_to_customer->pv_mt = $pv_mt_all;

                                }

                                $resule =  RunPvController::Runpv($to_customer_user,$pv,$type,$transection_code);
                            }

                        } elseif ($type == 3) { //รักษาคุณสมบัติท่องเที่ยง



                            $strtime_user = strtotime($to_customer->pv_tv_active);
                            $strtime = strtotime(date("Y-m-d"));

                            $promotion_tv = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                                ->select('*')
                                ->where('code', '=', 'pv_tv')
                                ->first();
                            $pro_tv = $promotion_tv->pv;
                            $pv_tv = $to_customer->pv_tv;
                            $pv_tv_all = $pv + $pv_tv;

                            if ($strtime_user > $strtime) {

                                $strtime_user = strtotime("-1 Month", $strtime_user);
                                $start_month = date('Y-m', $strtime_user);

                            } else {

                              $strtime_user = strtotime("-1 Month");
                              $start_month = date('Y-m', $strtime_user);

                            }


                            if ($pv_tv_all >= $pro_tv) {

                                //หักลบค่อยอัพเดท
                                $tv_mount = $pv_tv_all / $pro_tv;
                                $tv_mount = floor($tv_mount); //จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
                                $pv_tv_total = $pv_tv_all % $pro_tv; //ค่า pv ที่ต้องเอาไปอัพเดท DB

                                $add_mount = $tv_mount+1;
                                $strtime = strtotime($start_month);
                                $tv_active = strtotime("+$add_mount Month", $strtime);
                                $tv_active = date('Y-m-1', $tv_active); //วันที่ tv_active

                                $update_tv = DB::table('customers')
                                    ->where('id', $to_customer->id)
                                    ->update(['pv_tv' => $pv_tv_total, 'pv_tv_active' => $tv_active]);
                                //dd($tv_active);

                            } else {
                                //dd('อัพเดท');
                                $update_tv = DB::table('customers')
                                    ->where('id', $to_customer->id)
                                    ->update(['pv_tv' => $pv_tv_all]);
                            }

                            $resule =  RunPvController::Runpv($to_customer_user,$pv,$type,$transection_code);
                        }elseif ($type == 4) {//ซื้อเพื่อเติม เก็บ log เฉยๆ
                          $resule = ['status' => '', 'message' => 'success'];
                        }else {
                            $resule = ['status' => 'fail', 'message' => 'ไม่มี Type ที่เลือก'];
                            return  $resule;
                        }


                    }

                }
                if ($resule['status'] == 'success') {
                  $update_to_customer->date_order_first = date('Y-m-d h:i:s');
                  $strtotime_date_now_30 = strtotime("+30 minutes");
                  $strtotime_date_now_23 = strtotime(date('Y-m-d 23:00:00'));

                  if ($strtotime_date_now_30 > $strtotime_date_now_23) {
                      //$x= 'มากกว่า : '.date('Y-m-d H:i:s',$strtotime_date_now_30).'||'.date('Y-m-d H:i:s',$strtotime_date_now_23);
                      $cancel_expiry_date = date('Y-m-d H:i:s', $strtotime_date_now_23);
                  } else {
                      //$x= 'น้อยกว่า : '.date('Y-m-d H:i:s',$strtotime_date_now_30).'||'.date('Y-m-d H:i:s',$strtotime_date_now_23);
                      $cancel_expiry_date = date('Y-m-d H:i:s', $strtotime_date_now_30);
                  }
                  $update_ai_stockist->cancel_expiry_date = $cancel_expiry_date;
                  $update_ai_stockist->save();

                  $update_to_customer->save();
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


    public static function add_pv_aistockist($type,$pv,$to_customer_user,$username,$code_order='',$order_id_fk='')
    {
            try {
                DB::BeginTransaction();

                $user = DB::table('customers')
                ->select('id','pv_aistockist','user_name')
                ->where('user_name', '=', $username)
                ->first();

                $to_customer= DB::table('customers')
                ->select('id','pv_aistockist','user_name','pv_mt_active','status_pv_mt','pv_mt','pv_tv','pv')
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
                      $update_ai_stockist->to_customer_id =$to_customer->id;
                      $update_ai_stockist->transection_code = $transection_code;
                      $update_ai_stockist->set_transection_code = date('ym');
                      $update_ai_stockist->pv = $pv;
                      $update_ai_stockist->status_add_remove = 'add';
                      $update_ai_stockist->status = 'success';
                      $update_ai_stockist->type_id =$type;
                      $update_ai_stockist->banlance = $pv_total;
                      $update_ai_stockist->code_order = $code_order;
                      $update_ai_stockist->order_id_fk = $order_id_fk;
                      $resule = ['status' => 'success', 'message' => 'ซื้อเพื่อเติม เก็บ log + PV AiStockist '];

                }

                if ($resule['status'] == 'success') {
                  $update_ai_stockist->save();
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
