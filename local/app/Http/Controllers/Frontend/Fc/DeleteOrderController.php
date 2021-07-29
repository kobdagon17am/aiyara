<?php

namespace App\Http\Controllers\Frontend\Fc;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Backend\Giveaway;

class DeleteOrderController extends Controller
{

  public static function delete_order($delete_order_id){
    if($delete_order_id){
      DB::BeginTransaction();
      try{
        $order_id  = $delete_order_id;

        $order_data = DB::table('db_orders')
        ->select('code_order','purchase_type_id_fk')
        ->where('id','=',$order_id)
        ->first();

        if($order_data->purchase_type_id_fk == 5){
        $giv_log = DB::table('log_gift_voucher')
        ->where('order_id_fk','=',$order_id)
        ->get();

        foreach($giv_log as $value){
          $update_giftvoucher = DB::table('db_giftvoucher_cus')
          ->where('id',$value->giftvoucher_cus_id_fk)
          ->update(['giftvoucher_banlance' => $value->giftvoucher_value_use]);
         }

          DB::table('log_gift_voucher')->where('order_id_fk', '=', $order_id)->delete();
          DB::table('db_orders')->where('id', '=', $order_id)->delete();
          DB::table('db_order_products_list')->where('frontstore_id_fk', '=', $order_id)->delete();
          DB::table('db_order_products_list_giveaway')->where('order_id_fk', '=', $order_id)->delete();

        }elseif($order_data->purchase_type_id_fk == 5){

          DB::table('course_event_regis')->where('order_id_fk', '=', $order_id)->delete();
          DB::table('db_orders')->where('id', '=', $order_id)->delete();
          DB::table('db_order_products_list')->where('frontstore_id_fk', '=', $order_id)->delete();
          DB::table('db_order_products_list_giveaway')->where('order_id_fk', '=', $order_id)->delete();

        }else{

          DB::table('db_orders')->where('id', '=', $order_id)->delete();
          DB::table('db_order_products_list')->where('frontstore_id_fk', '=', $order_id)->delete();
          DB::table('db_order_products_list_giveaway')->where('order_id_fk', '=', $order_id)->delete();

        }

        DB::commit();
        $resule = ['status' => 'success', 'message' => 'Delete Oder Success'];
        return  $resule;
      }catch(Exception $e){
        DB::rollback();
        $resule = ['status' => 'fail', 'message' => $e];
        return  $resule;
      }

    }else{
      DB::rollback();
        $resule = ['status' => 'fail', 'message' => 'Delete Oder Fail : Data is null'];
        return  $resule;
    }

  }


}
