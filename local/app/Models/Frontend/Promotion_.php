<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;

class Promotion extends Model
{
	public static function all_available_purchase($promotion_id){

    // $promotions = DB::table('promotions')
    // ->select('show_startdate','show_enddate')
    // ->where('id','=',$promotion_id)
    // ->first();

    // $all_available_purchase = DB::table('db_order_products_list')
    // ->select(DB::raw('sum(db_order_products_list.amt) as amt_all'))
    // // ->leftjoin('db_orders', 'db_orders.id', '=', 'db_order_products_list.frontstore_id_fk')
    // // ->where('db_orders.order_status_id_fk', '!=', 8)
    // ->where('db_order_products_list.promotion_id_fk', '=', $promotion_id)
    // ->where('type_product', '=','promotion')
    // ->where('approve_status', '=','1')

    // ->wheredate('created_at', '<=',$promotions->show_enddate)
    // ->wheredate('created_at', '>=',$promotions->show_startdate)
    // ->groupBy('db_order_products_list.promotion_id_fk')
    // ->first();

    $all_available_purchase = null;

    if($all_available_purchase){

      if($all_available_purchase->amt_all){ 
        $amt_all = $all_available_purchase->amt_all;
      }else{
        $amt_all = 0;
      }
    }else{
      $amt_all = 0;
    }

		$resule = ['status'=>'success','message'=>'success','all_available_purchase'=>$amt_all];
		return $resule;
	}


 


	public static function count_per_promotion($promotion_id,$customer_id){//เฉพาะต่อรอบโปรโมชั่น


    // $promotions = DB::table('promotions')
    // ->select('show_startdate','show_enddate')
    // ->where('id','=',$promotion_id)
    // ->first();
    // $count_per_promotion = DB::table('db_order_products_list')
    // ->select(db::raw('sum(db_order_products_list.amt) as amt_all'))
    // // ->leftjoin('db_orders', 'db_orders.id', '=', 'db_order_products_list.frontstore_id_fk')
    // ->where('db_order_products_list.promotion_id_fk', $promotion_id)
    // ->where('db_order_products_list.customers_id_fk', $customer_id)
    // ->where('db_order_products_list.type_product', 'promotion')
    // ->where('db_order_products_list.approve_status', '=','1')
    // ->wheredate('created_at', '<=',$promotions->show_enddate)
    // ->wheredate('created_at', '>=',$promotions->show_startdate)
    // ->first();

     $count_per_promotion = null;

    if($count_per_promotion){

      if($count_per_promotion->amt_all){
        $amt_all = $count_per_promotion->amt_all;
      }else{
        $amt_all = 0;
      }
    }else{
      $amt_all = 0;
    }

 
		$resule = ['status'=>'success','message'=>'success','count'=>$amt_all];
		return $resule;
	}

	public static function count_per_promotion_day($promotion_id,$customer_id){//ต่อวันภายในรอบโปรโมชั่น
    $date_now = date('Y-m-d');

    // $promotions = DB::table('promotions')
    // ->select('show_startdate','show_enddate')
    // ->where('id','=',$promotion_id)
    // ->first();
 
    //     $count_per_promotion = DB::table('db_order_products_list')
    // ->select(db::raw('sum(db_order_products_list.amt) as amt_all'))
    // //->leftjoin('db_orders', 'db_orders.id', '=', 'db_order_products_list.frontstore_id_fk')
    // ->where('db_order_products_list.promotion_id_fk', $promotion_id)
    // ->where('db_order_products_list.customers_id_fk', $customer_id)
    // ->where('db_order_products_list.type_product', 'promotion')
    // ->where('db_order_products_list.approve_status', '=','1')
    // ->whereDate('db_order_products_list.created_at', $date_now)
    // ->first(); 
  $count_per_promotion = null;
 


if($count_per_promotion){

  if($count_per_promotion->amt_all){
    $amt_all = $count_per_promotion->amt_all;
  }else{
    $amt_all = 0;
  }
}else{
  $amt_all = 0;
}



		$resule = ['status'=>'success','message'=>'success','count'=>$amt_all];
		return $resule;
	}




}
