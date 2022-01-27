<?php

namespace App\Http\Controllers\Frontend\Fc;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Db_Ai_stockist;

class Cancel_mt_tv extends Controller
{

  public static function cancel_mt($customer_id,$pv)
   {

    $customer =  DB::table('customers')
    ->select('user_name', 'pv_aistockist','pv_mt_active','pv_mt')
    ->where('id', '=', $customer_id)
    ->first();

    if (empty($customer)) {
      $resule = ['status' => 'fail', 'message' => 'ไม่มี User นี้ในระบบ'];
      return $resule;
    }



    $promotion_mt = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
    ->select('*')
    ->where('code', '=', 'pv_mt')
    ->first();

    $pro_mt = $promotion_mt->pv;

    if($pv >= $pro_mt){

      //หารเอาเศษ ค่า Y คือ pv_mt
      $pv_mt_new = $pv % $pro_mt;

      if($pv_mt_new == 0){//หาจำนวนเต็มของเดือน
        $mt_active =  $pv / $pro_mt;
      }else{
        $mt_active =  floor($pv / $pro_mt);

      }

      $pv = $customer->pv_mt - $pv_mt_new;
      if($pv<0){
        $pv = $pv*-1;
        $mt_active = $mt_active+1;
      }
    }else{
      $pv = $customer->pv_mt - $pv;

      if($pv<0){
        $pv = $pv*-1;
        $mt_active = 1;
      }else{
        $mt_active = 0;
      }

    }

    $resule = ['status' => 'success', 'message' => 'Cancle Ai-Stockis Success','pv'=>$pv,'mt_active'=>$mt_active];
    //จำนวนเต็มคือเดือนนี้ต้องไปไปย้อนหลัง
    return $resule;
   }

   public static function cancel_tv($customer_id,$pv)
   {

    $customer =  DB::table('customers')
    ->select('user_name', 'pv_aistockist','pv_tv_active','pv_tv')
    ->where('id', '=', $customer_id)
    ->first();

    if (empty($customer)) {
      $resule = ['status' => 'fail', 'message' => 'ไม่มี User นี้ในระบบ'];
      return $resule;
    }



    $promotion_mt = DB::table('dataset_mt_tv') //อัพ Pv ของตัวเอง
    ->select('*')
    ->where('code', '=', 'pv_tv')
    ->first();


    $pro_tv = $promotion_mt->pv;

    if($pv >= $pro_tv){

      //หารเอาเศษ ค่า Y คือ pv_mt
      $pv_tv_new = $pv % $pro_tv;

      if($pv_tv_new == 0){//หาจำนวนเต็มของเดือน
        $tv_active =  $pv / $pro_tv;
      }else{
        $tv_active =  floor($pv / $pro_tv);

      }

      $pv = $customer->pv_tv - $pv_tv_new;
      if($pv<0){
        $pv = $pv*-1;
        $tv_active = $tv_active+1;
      }
    }else{
      $pv = $customer->pv_tv - $pv;

      if($pv<0){
        $pv = $pv*-1;
        $tv_active = 1;
      }else{
        $tv_active = 0;
      }

    }

    $resule = ['status' => 'success', 'message' => 'Cancle Ai-Stockis Success','pv'=>$pv,'tv_active'=>$tv_active];
    //จำนวนเต็มคือเดือนนี้ต้องไปไปย้อนหลัง
    return $resule;
   }
}
