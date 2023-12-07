<?php

namespace App\Http\Controllers\Frontend\Fc;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Customer;
use Illuminate\Support\Facades\DB;

use App\Models\Frontend\RunNumberPayment;
use Illuminate\Database\Eloquent\Model;
use App\Models\Db_Ai_stockist;


use App\Models\Db_Movement_ai_cash;
use App\Models\Frontend\Order;
use App\Models\Backend\GiftvoucherCus;
use App\Models\Frontend\Runpv_AiStockis;
use App\Http\Controllers\Frontend\Fc\RunPvController;

class RunErrorController extends Controller
{
  public static function index(){

    // $product_list =  DB::table('db_order_products_list') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
    // ->selectRaw('count(id) as id')
    // ->havingRaw('count(id) > 1 ')
    // // ->wheredate('date_active', '=', $date)

    // ->groupby('id')
    // ->get();


//     $customers =  DB::table('customers') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
//     ->selectRaw('user_name as username,count(user_name) as user_name_count')
//     ->havingRaw('count(user_name) > 1 ')
//     // ->wheredate('date_active', '=', $date)

//     ->groupby('user_name')
//     ->get();


// dd($customers);

    // ////////////////////////////

    //     $data = DB::table('customers') //อัพ Pv ของตัวเอง
    //       ->select('id','user_name')
    //       ->wherein('user_name',['A796135',
    //       'A729954',
    //       'A728234',
    //       'A739426',
    //       'A840653',
    //       'A871162',
    //       'A872831',
    //       'A762514',
    //       'A137206',
    //       'A594725',
    //       'A583817',
    //       'A550456',
    //       'A869076',
    //       'A871453',
    //      ])
    //       ->get();
    //       $arr = array();
    // $i = 0;
    // foreach($data as $value){

    //   $gv = \App\Helpers\Frontend::get_gitfvoucher($value->user_name);
    //   if($gv){
    //     $gv_value = 0;
    //   }else{
    //     $gv_value = $gv;
    //   }

    //   if($gv_value>0){
    //     dd($value->user_name,'fail');
    //   }else{
    //      $arr[] = $value->id;
    //   }

    // $i++;

    // $customers = DB::table('customers')
    // ->wherein('id',$arr)
    // ->update(['business_location_id' => 3]); //ลงข้อมูลบิลชำระเงิน

    // $file = DB::table('register_files')
    // ->wherein('customer_id',$arr)
    // ->update(['business_location_id_fk' => 3]); //ลงข้อมูลบิลชำระเงิน

    // }
    // dd($i,'success');


    /////////////////////////////////
    // $data = \App\Models\Frontend\Promotion::all_available_purchase_2(350);
    // dd($data);


    // $run_pament_code = RunNumberPayment::run_number_order(1);

    // dd($run_pament_code);
    // $data = RunErrorController::hidden_order();
    // dd($data);
//dd('1');
//   $promotions = DB::table('db_promotion_cus')
//   ->select('db_promotion_cus.id')
//   ->leftjoin('db_promotion_code', 'db_promotion_code.id', '=', 'db_promotion_cus.promotion_code_id_fk')
//            ->where('db_promotion_code.pro_edate', '<=', '2023-11-20 00:59:59')
//            //  ->where('pro_status', 3)
//            ->orderByDesc('pro_status')
//            //->limit(50000)
//           ->delete();

// 	dd('ok');

// $promotions = DB::table('db_code_order')
// ->select('	db_code_order.id')

//          ->where('db_code_order.created_at', '<=', '2023-09-19 00:59:59')
//          //  ->where('pro_status', 3)
//          //->limit(50000)
//         ->count();
//  dd($promotions);
//   dd('ok');


    // $data = DB::table('db_salepage_setting')
    // ->get();
    // $i = 0;
    // foreach($data as $value){

    //   $c = DB::table('customers') //อัพ Pv ของตัวเอง
    //   ->select('user_name')
    //   ->where('id', '=', $value->customers_id_fk)
    //   ->first();

    //   $salepage_setting = DB::table('db_salepage_setting')
    //   ->where('id', $value->id)
    //   ->update(['customers_username' => $c->user_name]); //ลงข้อมูลบิลชำระเงิน
    // $i++;
    // }
    // dd($i);

  //   $user = DB::table('customers')
  //   ->select('id', 'user_name','upline_id','introduce_id')
  //   // ->where('id', '>=',1298067)
  //   // ->where('id', '<=',1311311)
  //   ->where('user_name','A1307989')
  //   ->get();
  //   // dd($user);
  //   //max1311311
  //   $i= 0;
  // foreach($user as $value){
  //   // $data =  \App\Models\Frontend\LineModel::check_type_introduce($value->introduce_id,$value->upline_id);
  //   $data= RunErrorController::check_type_introduce($value->introduce_id,$value->upline_id,'A1307989');
  //   // dd($data);

  //   if( $data['status'] == 'success'){
  //     $i++;
  //     $introduce_type = $data['data']->line_type;
  //   }else{
  //     $introduce_type = '';
  //   }



  //   $update = DB::table('customers')
  //   ->where('id', $value->id)
  //   ->update(['introduce_type' =>$introduce_type]);
  // }

  // dd('success รวม'.count($user).' Run'.$i);



    // $rs= RunErrorController::check_type_introduce('A787338','A872520');


//     $rs = \App\Http\Controllers\Frontend\Fc\RunErrorController::run_invoice_code();
//     dd($rs);

//     dd('qqq');
//     $x = 1000;
//     dd('222');
//     // $rs = \App\Http\Controllers\Frontend\Fc\RunErrorController::Runpv('A873120',$x,1, $order_code = null);
//     $rs = \App\Http\Controllers\Frontend\Fc\RunErrorController::Runpv('A516540',$x,2, $order_code = null);
//     dd($rs);
//     Cancle_pv
//     Runpv

//    $rs = \App\Models\Frontend\RunNumberPayment::run_payment_code(1,'product');
//    dd($rs);

//   $order_data = DB::table('db_orders')
//   ->where('code_order', '=', 'O123113000419')
//   ->first();
// //   dd($order_data);

//   $user = DB::table('customers')
//   ->select('id', 'pv_aistockist', 'user_name')
//   ->where('id', '=',$order_data->customers_id_fk)
//   ->first();

//   $rs = \App\Http\Controllers\Frontend\Fc\RunErrorController::add_pv_aistockist('4',$order_data->pv_total,$user->user_name,$user->user_name,$order_data->code_order,$order_data->id);
//   dd($rs,$user->user_name);



//  $order_data = DB::table('db_orders')
//   ->wherein('code_order',
//   ['O123082100036'])
//   ->where('status_run_pv','=','not_run_pv')
//   //->where('invoice_code_id_fk','=',null)
//   ->get();
// // dd($order_data);
//   if(count($order_data)>1){
//     dd('มากกว่า 1');

//   }
// //  dd($order_data);
//   $i=0;

//   foreach($order_data as $value){
//     $i++;
//     //$rs = \App\Http\Controllers\Frontend\Fc\RunErrorController::PvPayment_type_confirme($value->id,$value->approver,$value->distribution_channel_id_fk,'customer');
//     $rs = \App\Http\Controllers\Frontend\Fc\RunErrorController::PvPayment_type_confirme($value->id,$value->approver,2,'customer');


//     $data[][$i]=$rs;
//   }


// dd($data,'O123082100036');
  }

  public static function run_invoice_code(){
    $order = DB::table('db_orders') //อัพ Pv ของตัวเอง
    ->where('order_status_id_fk', '>=', '4')
    // ->orwhere('invoice_code_id_fk', '!=', null)
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

        //   dd($user);

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



    public static function PvPayment_type_confirme($order_id, $admin_id, $distribution_channel, $action_type)
    {
        DB::BeginTransaction();
        $order_data = DB::table('db_orders')
            ->where('id', '=', $order_id)
            ->first();
// dd('ok');
            // dd($order_data);

        $order_update = Order::find($order_id);
        $movement_ai_cash = new Db_Movement_ai_cash;
        $GiftvoucherCus = new GiftvoucherCus;
        $customer_update_ai_cash = Customer::find($order_data->member_id_aicash);

        // dd($movement_ai_cash);

        if (empty($order_data)) {
            $resule = ['status' => 'fail', 'message' => 'ไม่มีบิลนี้อยู่ในระบบ'];
            return $resule;
        }

        if ($order_data->order_status_id_fk == 4 || $order_data->order_status_id_fk == 5 || $order_data->order_status_id_fk == 6 || $order_data->order_status_id_fk == 7) {
            $resule = ['status' => 'fail', 'message' => 'บิลนี้ถูกอนุมัติไปแล้ว ไม่สามารถอนุมัติซ้ำได้'];
            return $resule;
        }

        if($order_update->approve_status){
          if($order_update->status_run_pv == 'success'){
            if($order_update->pay_type_id_fk==1||$order_update->pay_type_id_fk==8||$order_update->pay_type_id_fk==10||$order_update->pay_type_id_fk==11||$order_update->pay_type_id_fk==12){
              $resule = ['status' => 'fail', 'message' => 'บิลนี้ถูกอนุมัติไปแล้ว ไม่สามารถอนุมัติซ้ำได้'];
              return $resule;
            }
          }

        }

        $pv = $order_data->pv_total;
        if ($order_data->status_payment_sent_other == 1) {

            $customer_id = $order_data->customers_sent_id_fk;
        } else {
            $customer_id = $order_data->customers_id_fk;

        }

        $customer_update = Customer::find($customer_id);


        $type_id = $order_data->purchase_type_id_fk;
        $business_location_id = $order_data->business_location_id_fk;

        if (empty($order_id)) {
            $resule = ['status' => 'fail', 'message' => 'Data is Null'];
            DB::rollback();
            return $resule;

        } else {

            try {

                if ($order_data->delivery_location_frontend == 'sent_office') {

                    $orderstatus_id = 4;
                    # code...
                } else {

                    $orderstatus_id = 5;

                }

                if ($order_data->pay_type_id_fk == 3 || $order_data->pay_type_id_fk == 6 || $order_data->pay_type_id_fk == 9
                    || $order_data->pay_type_id_fk == 11 || $order_data->pay_type_id_fk == 14) { //Aicash

                    $check_aicash = DB::table('customers') //อัพ Pv ของตัวเอง
                        ->select('ai_cash','id','user_name')
                        ->where('id', '=', $order_data->member_id_aicash)
                        ->first();

                        if(empty($check_aicash)){
                          $resule = ['status' => 'fail', 'message' => 'ไม่มี Customers_id ให้ตัด Aicash'];
                          DB::rollback();
                          return $resule;
                        }

                    $update_icash = $check_aicash->ai_cash - $order_data->aicash_price;
                    // dd($update_icash);
                    //$check_aicash->ai_cash;//Ai-Cash เดิม
                    //$update_icash; //Bicash_banlance
                    if ($update_icash >= 0) {

                        $order_update->aicash_old = $check_aicash->ai_cash;
                        $order_update->aicash_banlance = $update_icash;

                        if ($type_id == 6) { //course
                            $movement_type = 'buy_course';
                            $movement_detail = 'Buy Course';
                        } else {
                            $movement_type = 'buy_product';
                            $movement_detail = 'Buy Product';

                        }

                        $movement_ai_cash->customer_id_fk = $check_aicash->id;
                        $movement_ai_cash->order_id_fk = $order_id;
                        //'add_ai_cash_id_fk' => '';//กรณีเติม Aicash
                        $movement_ai_cash->business_location_id_fk = $order_data->business_location_id_fk;
                        $movement_ai_cash->price_total = $order_data->total_price;
                        $movement_ai_cash->aicash_old = $check_aicash->ai_cash;
                        $movement_ai_cash->aicash_price = $order_data->aicash_price;
                        $movement_ai_cash->aicash_banlance = $update_icash;
                        $movement_ai_cash->order_code = $order_data->code_order;
                        $movement_ai_cash->order_type_id_fk = $order_data->purchase_type_id_fk;
                        $movement_ai_cash->pay_type_id_fk = $order_data->pay_type_id_fk;
                        $movement_ai_cash->type = $movement_type;
                        $movement_ai_cash->detail = $movement_detail;

                        $customer_update_ai_cash->ai_cash = $update_icash;

                    } else {

                        $resule = ['status' => 'fail', 'message' => 'Ai Cash ไม่พอสำหรับการการชำระ'];
                        DB::rollback();
                        return $resule;

                    }

                }


                // if ($order_data->pay_type_id_fk == 4 || $order_data->pay_type_id_fk == 12 || $order_data->pay_type_id_fk == 13 || $order_data->pay_type_id_fk == 14
                // || $order_data->pay_type_id_fk == 17 || $order_data->pay_type_id_fk == 18 || $order_data->pay_type_id_fk == 19) { //กิฟวอยเชอ


                // }

                if ($type_id == 6) { //course

                    $orderstatus_id = 7;
                    $opc_type_order = 'course_event';

                    $run_pament_code = RunNumberPayment::run_payment_code($business_location_id, $opc_type_order);

                    if ($run_pament_code['status'] == 'fail') {
                        $resule = ['status' => 'fail', 'message' => $run_pament_code['message']];
                        DB::rollback();
                        return $resule;

                    }
                    $code_order = $run_pament_code['code_order'];

                } else { //product ทั่วไป

                    $opc_type_order = 'product';
                    $run_pament_code = RunNumberPayment::run_payment_code($business_location_id, $opc_type_order);
                    if ($run_pament_code['status'] == 'fail') {
                        $resule = ['status' => 'fail', 'message' => $run_pament_code['message']];
                        DB::rollback();
                        return $resule;
                    }
                    $code_order = $run_pament_code['code_order'];
                }

                $strtotime_date_now_30 = strtotime("+30 minutes");
                $strtotime_date_now_23 = strtotime(date('Y-m-d 23:00:00'));

                if ($strtotime_date_now_30 > $strtotime_date_now_23) {
                    //$x= 'มากกว่า : '.date('Y-m-d H:i:s',$strtotime_date_now_30).'||'.date('Y-m-d H:i:s',$strtotime_date_now_23);
                    $cancel_expiry_date = date('Y-m-d H:i:s', $strtotime_date_now_23);
                } else {
                    //$x= 'น้อยกว่า : '.date('Y-m-d H:i:s',$strtotime_date_now_30).'||'.date('Y-m-d H:i:s',$strtotime_date_now_23);
                    $cancel_expiry_date = date('Y-m-d H:i:s', $strtotime_date_now_30);
                }

                $order_update->approver = $admin_id;
                $order_update->order_status_id_fk = $orderstatus_id;
                $order_update->cancel_expiry_date = $cancel_expiry_date;
                $order_update->approve_status = 2;
                $order_update->approve_date = date('Y-m-d H:i:s');

                $product_list = DB::table('db_order_products_list')
                    ->where('frontstore_id_fk', '=', $order_id)
                    ->get();

                foreach ($product_list as $product_list_value) {

                    $update_products_lis = DB::table('db_order_products_list')
                        ->where('id', $product_list_value->id)
                        ->update(['approver' => $admin_id,
                            'approve_status' => 1,
                            'approve_date' => date('Y-m-d H:i:s')]);

                    if ($product_list_value->type_product == 'giveaway') {
                        $giveaway = DB::table('db_order_products_list_giveaway')
                            ->where('db_order_products_list_giveaway.product_list_id_fk', '=', $product_list_value->id)
                            ->get();




                        foreach ($giveaway as $giveaway_value) {

                            if ($giveaway_value->type_product == 'giveaway_product') {

                                $update_products_list_giveaway = DB::table('db_order_products_list_giveaway')
                                    ->where('id', $giveaway_value->id)
                                    ->update(['approver' => $admin_id,
                                        'approve_status' => 1,
                                        'approve_date' => date('Y-m-d H:i:s')]);

                            } else {


                                $update_products_list_giveaway = DB::table('db_order_products_list_giveaway')
                                    ->where('id', $giveaway_value->id)
                                    ->update(['approver' => $admin_id,
                                        'approve_status' => 1,
                                        'approve_date' => date('Y-m-d H:i:s')]);

                                $expiry_date = date("Y-m-d", strtotime("+30 day"));

                                $inseart_gift_voucher = DB::table('gift_voucher')->insert([

                                    'customer_id' => $customer_id,
                                    'gv' => $giveaway_value->gv_free,
                                    'banlance' => $giveaway_value->gv_free,
                                    'detail' => $product_list_value->product_name,
                                    'order_id' => $order_id,
                                    'admin_id' => $admin_id,
                                    'code' => $order_data->code_order,
                                    'status' => 'promotion',
                                    'code_order' => $order_data->code_order,
                                    'create_at' => now(),
                                    'expiry_date' => $expiry_date,
                                    'admin_id' => $admin_id,

                                ]);

                                $gv = \App\Helpers\Frontend::get_gitfvoucher($customer_update->user_name);
                                $gv_banlance = $gv->sum_gv + $giveaway_value->gv_free;

                                $GiftvoucherCus->customer_username = $customer_update->user_name;
                                $GiftvoucherCus->giftvoucher_value = $giveaway_value->gv_free;
                                $GiftvoucherCus->giftvoucher_banlance = $giveaway_value->gv_free;
                                $GiftvoucherCus->pro_status = 1;
                                $GiftvoucherCus->detail = 'ได้รับจากการซื้อสินค้า';
                                $GiftvoucherCus->order_id_fk = $order_id;
                                $GiftvoucherCus->code_order = $order_data->code_order;
                                $GiftvoucherCus->giftvoucher_type = 'promotion';
                                $GiftvoucherCus->pro_sdate = now();
                                $GiftvoucherCus->pro_edate = $expiry_date;
                                $GiftvoucherCus->save();

                                $insert_log_gift_voucher = DB::table('log_gift_voucher')->insert([
                                    'customer_id_fk' => $customer_id,
                                    'order_id_fk' => $order_id,
                                    'code_order' => $order_data->code_order,
                                    'giftvoucher_cus_id_fk' => $GiftvoucherCus->id,
                                    'type_action_giftvoucher'=>0,
                                    'giftvoucher_value_old' => $gv->sum_gv,
                                    'giftvoucher_value_use' => $giveaway_value->gv_free,
                                    'giftvoucher_value_banlance' =>  $giveaway_value->gv_free+$gv->sum_gv,
                                    'detail' => 'ได้จากการซื้อสินค้า',
                                    'status' => 'success',
                                    'type' => 'Add',

                                ]);

                            }

                        }

                    }

                }
                //end check รายการของแถม

                $check_payment_code = DB::table('db_invoice_code') //เจนเลข payment order
                    ->where('order_id', '=', $order_id)
                    ->first();

                if ($check_payment_code) {
                    $update_order_payment_code = DB::table('db_invoice_code')
                        ->where('order_id', $order_id)
                        ->update(['order_payment_status' => 'Success',
                            'order_payment_code' => $code_order,
                            'business_location_id' => $business_location_id,
                            'date_setting_code' => date('ym'),
                            'type_order' => $opc_type_order]); //ลงข้อมูลบิลชำระเงิน

                } else {

                    $inseart_order_payment_code = DB::table('db_invoice_code')->insert([
                        'order_id' => $order_id,
                        'order_payment_code' => $code_order,
                        'order_payment_status' => 'Success',
                        'business_location_id' => $business_location_id,
                        'date_setting_code' => date('ym'),
                        'type_order' => $opc_type_order]); //ลงข้อมูลบิลชำระเงิน

                }
                $order_update->invoice_code_id_fk = $code_order;
                $order_update->invoice_code = $order_data->code_order;
                //check รายการสินค้าแถม

                if ($type_id == 1) { //ทำคุณสมบติ

                  if($customer_update->date_order_first == null || $customer_update->date_order_first == '' || $customer_update->date_order_first == '0000-00-00 00:00:00'){
                    $customer_update->date_order_first = date('Y-m-d h:i:s');
                  }

                    $add_pv = $customer_update->pv + $pv;

                    $order_update->pv_old = $customer_update->pv;
                    $order_update->pv_banlance = $add_pv;
                    $order_update->approve_date = date('Y-m-d H:i:s');

                    // dd($customer_update->user_name);
                    // dd($pv);
                    if($order_update->status_run_pv == 'not_run_pv' || $order_update->status_run_pv == 'cancel' ){

                      $resule = RunPvController::Runpv($customer_update->user_name, $pv, $type_id,$order_data->code_order);
                      if($resule['status'] == 'success'){
                        $order_update->status_run_pv = 'success';
                      }
                    }else{
                      $resule =  ['status' => 'success','message' => 'บิลนี้ถูก Run PV ไปเเล้วไม่สามารถทำซ้ำได้'];
                    }


                    if ($customer_update->status_pv_mt == 'first') {//คุณไก่ขอเพิ่มทุกเงื่อนไข
                      $start_month = date('Y-m');
                      $mt_mount_new = strtotime("+2 Month", strtotime($start_month));
                      //dd(date('Y-m-1',$mt_mount_new));
                      $customer_update->pv_mt_active = date('Y-m-1',$mt_mount_new);
                      $customer_update->status_pv_mt = 'not';
                      $customer_update->date_mt_first = date('Y-m-d h:i:s');
                      $order_update->active_mt_date = date('Y-m-1',$mt_mount_new);
                  }

                } elseif ($type_id == 2) { //รักษาคุณสมบัติรายเดือน

                  if($customer_update->date_order_first == null || $customer_update->date_order_first == '' || $customer_update->date_order_first == '0000-00-00 00:00:00'){
                    $customer_update->date_order_first = date('Y-m-d h:i:s');
                  }

                    $active_mt_old_date = $customer_update->pv_mt_active;
                    $status_pv_mt_old = $customer_update->status_pv_mt;

                    $order_update->pv_mt_old = $customer_update->pv_mt;
                    $order_update->active_mt_old_date = $active_mt_old_date;
                    $order_update->status_pv_mt_old = $status_pv_mt_old;
                    $order_update->approve_date = date('Y-m-d H:i:s');

                    $strtime_user = strtotime($customer_update->pv_mt_active);
                    $strtime = strtotime(date("Y-m-d"));

                    if ($customer_update->status_pv_mt == 'first') {

                        // if($strtime_user < $strtime){
                        //     //$contract_date = strtotime(date('Y-m',$strtime_user));
                        //     //$caltime = strtotime("+1 Month",$contract_date);
                        //     //$start_month = date("Y-m", $caltime);
                        //     $start_month = date("Y-m");

                        // }else{

                        //     $start_month = date("Y-m");
                        // }

                        $start_month = date('Y-m');

                        $promotion_mt = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                            ->select('*')
                            ->where('code', '=', 'pv_mt')
                            ->first();

                        $pro_mt = $promotion_mt->pv;
                        $pv_mt = $customer_update->pv_mt;
                        $pv_mt_all = $pv + $pv_mt;


                        if ($pv_mt_all >= $pro_mt) {
                            //dd('หักลบค่อยอัพเดท');
                            //หักลบค่อยอัพเดท
                            $mt_mount = $pv_mt_all / $pro_mt;
                            $mt_mount = floor($mt_mount); //จำนวนเต์มเดือนที่นำไปบวกเพิ่ม

                            $pv_mt_total = $pv_mt_all % $pro_mt; //ค่า pv ที่ต้องเอาไปอัพเดท DB

                            $mt_mount_new = $mt_mount+2;//กำหนดให้เป็นวันที่ 1 ของสองเดือนหน้า

                            $mt_active = strtotime("+$mt_mount_new Month", strtotime($start_month));

                            $mt_active = date('Y-m-1', $mt_active); //วันที่ mt_active


                            $customer_update->pv_mt = $pv_mt_total;
                            $customer_update->pv_mt_active = $mt_active;
                            $customer_update->status_pv_mt = 'not';

                            $customer_update->date_mt_first = date('Y-m-d h:i:s');

                            $order_update->pv_banlance = $pv_mt_total;

                            $order_update->active_mt_date =  $mt_active;

                        } else {
                            //dd('อัพเดท');

                            $customer_update->pv_mt = $pv_mt_all;
                            $mt_mount_new = strtotime("+2 Month", strtotime($start_month));

                            //dd(date('Y-m-1',$mt_mount_new));
                            $customer_update->pv_mt_active = date('Y-m-1',$mt_mount_new);
                            $customer_update->status_pv_mt = 'not';
                            $customer_update->date_mt_first = date('Y-m-d h:i:s');

                            $order_update->pv_banlance = $pv_mt_all;
                            $order_update->active_mt_date = date('Y-m-1',$mt_mount_new);
                        }



                        if($order_update->status_run_pv == 'not_run_pv' || $order_update->status_run_pv == 'cancel' ){
                          $resule = RunPvController::Runpv($customer_update->user_name, $pv, $type_id,$order_data->code_order);
                          if($resule['status'] == 'success'){
                            $order_update->status_run_pv = 'success';
                          }
                        }else{
                          $resule =  ['status' => 'success','message' => 'บิลนี้ถูก Run PV ไปเเล้วไม่สามารถทำซ้ำได้'];
                        }

                    } else {
                        $promotion_mt = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                            ->select('*')
                            ->where('code', '=', 'pv_mt')
                            ->first();

                        $pro_mt = $promotion_mt->pv;
                        $pv_mt = $customer_update->pv_mt;
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

                            $strtime = strtotime($start_month);
                            $mt_mount_new = $mt_mount+1;

                            $mt_active = strtotime("+$mt_mount_new Month", $strtime);
                            $mt_active = date('Y-m-1', $mt_active); //วันที่ mt_active

                            $customer_update->pv_mt = $pv_mt_total;
                            $customer_update->pv_mt_active = $mt_active;

                            $order_update->pv_banlance = $pv_mt_total;
                            $order_update->active_mt_date =  date('Y-m-1',strtotime($mt_active));

                        } else {

                            $customer_update->pv_mt = $pv_mt_all;
                            $order_update->pv_banlance = $pv_mt_all;
                            $order_update->active_mt_date = $customer_update->pv_mt_active;

                        }


                        if($order_update->status_run_pv == 'not_run_pv' || $order_update->status_run_pv == 'cancel' ){
                          $resule = RunPvController::Runpv($customer_update->user_name, $pv, $type_id,$order_data->code_order);
                          if($resule['status'] == 'success'){
                            $order_update->status_run_pv = 'success';
                          }
                        }else{
                          $resule =  ['status' => 'success','message' => 'บิลนี้ถูก Run PV ไปเเล้วไม่สามารถทำซ้ำได้'];
                        }

                    }

                } elseif ($type_id == 3) { //รักษาคุณสมบัติท่องเที่ยง

                  if($customer_update->date_order_first == null || $customer_update->date_order_first == '' || $customer_update->date_order_first == '0000-00-00 00:00:00'){
                    $customer_update->date_order_first = date('Y-m-d h:i:s');
                  }

                    $active_tv_old_date = $customer_update->pv_tv_active;
                    $strtime_user = strtotime($customer_update->pv_tv_active);
                    $strtime = strtotime(date("Y-m-d"));

                    $promotion_tv = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
                        ->select('*')
                        ->where('code', '=', 'pv_tv')
                        ->first();
                    $pro_tv = $promotion_tv->pv;
                    $pv_tv = $customer_update->pv_tv;
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

                        $customer_update->pv_tv = $pv_tv_total;
                        $customer_update->pv_tv_active = $tv_active;
                        $order_update->pv_tv_old = $customer_update->pv_tv;
                        $order_update->pv_banlance = $pv_tv_total;
                        $order_update->active_tv_old_date = $active_tv_old_date;
                        $order_update->active_tv_date = $tv_active;

                    } else {

                        $customer_update->pv_tv = $pv_tv_all;
                        $order_update->pv_tv_old = $customer_update->pv_tv;
                        $order_update->pv_banlance = $pv_tv_all;
                        $order_update->active_tv_old_date = $active_tv_old_date;
                        $order_update->active_tv_date = $customer_update->pv_tv_active;

                    }



                    if($order_update->status_run_pv == 'not_run_pv' || $order_update->status_run_pv == 'cancel' ){
                      $resule = RunPvController::Runpv($customer_update->user_name, $pv, $type_id,$order_data->code_order);
                      if($resule['status'] == 'success'){
                        $order_update->status_run_pv = 'success';
                      }
                    }else{
                      $resule =  ['status' => 'success','message' => 'บิลนี้ถูก Run PV ไปเเล้วไม่สามารถทำซ้ำได้'];
                    }


                    if ($customer_update->status_pv_mt == 'first') {//คุณไก่ขอเพิ่มทุกเงื่อนไข
                      $start_month = date('Y-m');
                      $mt_mount_new = strtotime("+2 Month", strtotime($start_month));
                      //dd(date('Y-m-1',$mt_mount_new));
                      $customer_update->pv_mt_active = date('Y-m-1',$mt_mount_new);
                      $customer_update->status_pv_mt = 'not';
                      $customer_update->date_mt_first = date('Y-m-d h:i:s');
                      $order_update->active_mt_date = date('Y-m-1',$mt_mount_new);
                  }

                } elseif ($type_id == 4) { //เติม Ai Stockist

                  if($customer_update->date_order_first == null || $customer_update->date_order_first == '' || $customer_update->date_order_first == '0000-00-00 00:00:00'){
                    $customer_update->date_order_first = date('Y-m-d h:i:s');
                  }

                  $resule = Runpv_AiStockis::add_pv_aistockist($type_id, $pv,$customer_update->user_name,$customer_update->user_name,$order_data->code_order,$order_data->id);

                  if ($resule['status'] == 'fail') {

                    $log =  ['customer_user_name' => $customer_update->user_name,
                    'admin_user_name' => '',
                    'code_order' => $order_data->code_order,
                    'error_detail' => 'เติม Ai Stockist',
                    'function' => 'App\Http\Controllers\Frontend\Fc\RunPvController',
                    'type' =>'',//'admin','customer'
                    ];
                    $rs = \App\Http\Controllers\Frontend\Fc\LogErrorController::log_error($log);
                  }

                    $add_pv_aipocket = $customer_update->pv_aistockist + $pv;
                    $customer_update->pv_aistockist = $add_pv_aipocket;

                    $update_ai_stockist = DB::table('ai_stockist')
                        ->where('order_id_fk', $order_id)
                        ->update(['status' => 'success', 'pv_aistockist' => $add_pv_aipocket]);



                    //  if($order_update->status_run_pv == 'not_run_pv' || $order_update->status_run_pv == 'cancel' ){
                    //   $resule = RunPvController::Runpv($customer_update->user_name, $pv, $type_id,$order_data->code_order);
                    //   if($resule['status'] == 'success'){
                    //     $order_update->status_run_pv = 'success';
                    //   }
                    // }else{
                    //   $resule =  ['status' => 'success','message' => 'บิลนี้ถูก Run PV ไปเเล้วไม่สามารถทำซ้ำได้'];
                    // }

                      //ถ้าบิลนี้มียอดเกิน 10000 บาทให้เปลี่ยนสถานะเป็น Aistockis
                        if ($order_data->pv_total >= 10000) {

                          $customer_update->aistockist_status = '1';
                          $customer_update->aistockist_date = date('Y-m-d h:i:s');
                      }


                      if ($customer_update->status_pv_mt == 'first') {//คุณไก่ขอเพิ่มทุกเงื่อนไข
                            $start_month = date('Y-m');
                            $mt_mount_new = strtotime("+2 Month", strtotime($start_month));
                            //dd(date('Y-m-1',$mt_mount_new));
                            $customer_update->pv_mt_active = date('Y-m-1',$mt_mount_new);
                            $customer_update->status_pv_mt = 'not';
                            $customer_update->date_mt_first = date('Y-m-d h:i:s');
                            $order_update->active_mt_date = date('Y-m-1',$mt_mount_new);
                        }
                        $order_update->status_run_pv = 'success';




                } elseif ($type_id == 5) { // Ai Voucher

                  if($customer_update->date_order_first == null || $customer_update->date_order_first == '' || $customer_update->date_order_first == '0000-00-00 00:00:00'){
                    $customer_update->date_order_first = date('Y-m-d h:i:s');
                  }



                    $pv_banlance = DB::table('customers')
                        ->select('pv')
                        ->where('id', $customer_id)
                        ->first();

                    $order_update->pv_banlance = $pv_banlance->pv;
                    //ไม่เข้าสถานะต้อง Approve


                    if($order_update->status_run_pv == 'not_run_pv' || $order_update->status_run_pv == 'cancel' ){
                      $resule = RunPvController::Runpv($customer_update->user_name, $pv, $type_id,$order_data->code_order);
                      if($resule['status'] == 'success'){
                        $order_update->status_run_pv = 'success';
                      }
                    }else{
                      $resule =  ['status' => 'success','message' => 'บิลนี้ถูก Run PV ไปเเล้วไม่สามารถทำซ้ำได้'];
                    }

                } elseif ($type_id == 6) { //couse อบรม

                    $update_couse = DB::table('course_event_regis')
                        ->where('order_id_fk', $order_id)
                        ->update(['status_register' => '2']);

                    $add_pv = $customer_update->pv + $pv;

                    $order_update->pv_old = $customer_update->pv;
                    $order_update->pv_banlance = $add_pv;
                    $order_update->action_date = date('Y-m-d H:i:s');


                    if($order_update->status_run_pv == 'not_run_pv' || $order_update->status_run_pv == 'cancel' ){
                      $resule = RunPvController::Runpv($customer_update->user_name, $pv, $type_id,$order_data->code_order);
                      if($resule['status'] == 'success'){
                        $order_update->status_run_pv = 'success';
                      }
                    }else{
                      $resule =  ['status' => 'success','message' => 'บิลนี้ถูก Run PV ไปเเล้วไม่สามารถทำซ้ำได้'];
                    }

                } else { //ไม่เข้าเงื่อนไขได้เลย
                    $resule = ['status' => 'fail', 'message' => 'ไม่มีเงื่อนไขที่ตรงตามความต้องการ'];
                    DB::rollback();
                    return $resule;
                }



                if ($resule['status'] == 'success') {

                    $customer_update->save();
                    $order_update->save();

                    if ($order_data->pay_type_id_fk == 3 || $order_data->pay_type_id_fk == 6 || $order_data->pay_type_id_fk == 9
                    || $order_data->pay_type_id_fk == 11 || $order_data->pay_type_id_fk == 14) { //Aicash
                    $customer_update_ai_cash->save();
                    $movement_ai_cash->save();
                    }


                    \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddress($order_id);
                    \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddressDefault($order_id);


                    $update_package = \App\Http\Controllers\Frontend\Fc\RunPvController::update_package($customer_update->user_name);
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


    public static function check_type_introduce($introduce_id,$under_line_id,$user_name){//คนแนะนำ//สร้างภายใต้ id


        if($introduce_id == $under_line_id){
            $data = DB::table('customers')
            ->select('user_name','upline_id','line_type')
            ->where('user_name','=',$user_name)
          //->where('upline_id','=',$use_id)
            ->first();
            $resule = ['status'=>'success','message'=>'Upline ID ','data'=>$data];
            return $resule;
        }
      $data_user = DB::table('customers')
      ->select('upline_id','user_name','upline_id','line_type')
      ->where('user_name','=',$under_line_id)
      ->first();
    //   dd($data_user);

      if(!empty($data_user)){
        $upline_id = $data_user->upline_id;
        if($upline_id == $introduce_id){
          $resule = ['status'=>'success','message'=>'My Account','data'=>$data_user];
          return $resule;
        }

        //group 1 หาภายใต้ตัวเอง

        $username = $data_user->upline_id;
        $j = 2;
        for ($i=1; $i <= $j ; $i++){
          if($i == 1){
            $data = DB::table('customers')
            ->select('user_name','upline_id','line_type')
            ->where('user_name','=',$username)
          //->where('upline_id','=',$use_id)
            ->first();


          }

          if($data){
            if($data->user_name == $introduce_id || $data->upline_id == $introduce_id ){
              $resule = ['status'=>'success','message'=>'Under line','data'=>$data];
              $j =0;
              return $resule;

            }elseif($data->upline_id == 'AA'){

              $resule = ['status'=>'fail','message'=>'ไม่พบรหัสสมาชิกดังกล่าวหรือไม่ได้อยู่ในสายงานเดียวกัน'];
              $j =0;
            }else{

              $data = DB::table('customers')
              ->select('user_name','upline_id','line_type')
              ->where('id','=',$data->upline_id)
              ->first();

              $j = $j+1;
            }

          }else{
            $resule = ['status'=>'fail','message'=>'ไม่พบรหัสสมาชิกดังกล่าวหรือไม่ได้อยู่ในสายงานเดียวกัน'];
            $j =0;
          }
        }

        //end group 1 หาภายใต้ตัวเอง
        /////////////////////////////////////////////////////////////////////////////////////

        //หาด้านบนตัวเอง
        $upline_id_arr = array();

        if($resule['status'] == 'fail'){

          $data_account = DB::table('customers')
          ->select('user_name','upline_id','line_type')
          ->where('user_name','=',$introduce_id)
          ->first();

          if(empty($data_account)){
              $username = null;
            $j = 0;
          }else{
              $username = $data_account->upline_id;
            $j = 2;
          }

          for ($i=1; $i <= $j ; $i++){
            if($username == 'AA'){
              $upline_id_arr[] = $data_account->user_name;
              $j =0;
            }else{
              $data_account = DB::table('customers')
              ->select('user_name','upline_id','line_type')
              ->where('user_name','=',$username)
              ->first();

              if(empty($data_account)){
                $j = 0;
              }else{
                $upline_id_arr[] = $data_account->user_name;
                $username = $data_account->upline_id;
                $j = $j+1;
              }


            }
          }

          //return $resule;
        }

        if (in_array($introduce_id, $upline_id_arr)) {
          $resule = ['status'=>'success','message'=>'Upline ID ','data'=>$data_account];
        }else{
          $resule = ['status'=>'fail','message'=>'ไม่พบรหัสสมาชิกดังกล่าวหรือไม่ได้อยู่ในสายงานเดียวกัน','data'=>null];

        }
        return $resule;

      }else{
        $resule = ['status'=>'fail','message'=>'ไม่พบข้อมูลผู้ใช้งานรหัสนี้'];
        return $resule;

      }
    }

    public static function hidden_order(){
       $adate= now();
       $date = date("Y-m-d 00:59:59", strtotime("-1 day",strtotime($adate)));

        $data = DB::table('db_orders')
        ->where('db_orders.approve_status', '=',0)
        ->where('db_orders.order_status_id_fk', '=',1)
        ->where('db_orders.created_at', '<=', $date)
        ->where('db_orders.deleted_at', '=',null)
        ->get();

        $i = 0;
        foreach( $data as $value){
            $i++;
            $order_update = DB::table('db_orders')
            ->where('id', $value->id)
            ->update(['deleted_at' => now()]); //ลงข้อมูลบิลชำระเงิน
        }


        $date_delete = date("Y-m-d 00:59:59", strtotime("-5 day",strtotime($adate)));
        $db_orders = DB::table('db_orders')
        ->select('id')
        ->where('db_orders.approve_status', '=',0)
        ->where('db_orders.order_status_id_fk', '=',1)
        ->where('db_orders.created_at', '<=', $date_delete)
        ->where('db_orders.deleted_at', '!=',null)
        ->get();



        $arr_id_order = array();
        foreach( $db_orders as $value){
            $arr_id_order[] = $value->id;
        }
        if(count($arr_id_order)>0){

            $db_orders_d = DB::table('db_orders')
            ->wherein('id',$arr_id_order)
            ->delete();


            $db_list_d = DB::table('db_order_products_list')
            ->wherein('frontstore_id_fk',$arr_id_order)
            ->delete();

        }




    }

}
