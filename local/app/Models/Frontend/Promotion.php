<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;

class Promotion extends Model
{
	public static function all_available_purchase($promotion_id){


    $all_available_purchase = DB::table('db_order_products_list')
    ->select(db::raw('sum(db_order_products_list.amt) as amt_all'))
    ->leftjoin('db_orders', 'db_orders.id', '=', 'db_order_products_list.frontstore_id_fk')
    ->where('db_order_products_list.promotion_id_fk', '=', $promotion_id)
    ->where('type_product', '=','promotion')
    ->first();

		$resule = ['status'=>'success','message'=>'success','all_available_purchase'=>$all_available_purchase->amt_all];
		return $resule;
	}

	public static function count_per_promotion($promotion_id,$customer_id){//เฉพาะต่อรอบโปรโมชั่น


    $count_per_promotion = DB::table('db_order_products_list')
    ->select(db::raw('sum(db_order_products_list.amt) as amt_all'))
    ->leftjoin('db_orders', 'db_orders.id', '=', 'db_order_products_list.frontstore_id_fk')
    ->where('db_order_products_list.type_product', '=','promotion')
    ->where('db_order_products_list.promotion_id_fk', '=', $promotion_id)
    ->where('db_orders.customers_id_fk', '=',$customer_id)
    ->first();

		$resule = ['status'=>'success','message'=>'success','count'=>$count_per_promotion->amt_all];
		return $resule;
	}

	public static function count_per_promotion_day($promotion_id,$customer_id){//ต่อวันภายในรอบโปรโมชั่น
    $date_now = date('Y-m-d');
    $count_per_promotion = DB::table('db_order_products_list')
    ->select(db::raw('sum(db_order_products_list.amt) as amt_all'))
    ->leftjoin('db_orders', 'db_orders.id', '=', 'db_order_products_list.frontstore_id_fk')
    ->where('db_order_products_list.type_product', '=','promotion')
    ->where('db_order_products_list.promotion_id_fk', '=', $promotion_id)
    ->where('db_orders.customers_id_fk', '=',$customer_id)
    ->wheredate('db_order_products_list.created_at', '=',$date_now)
    ->first();



		$resule = ['status'=>'success','message'=>'success','count'=>$count_per_promotion->amt_all];
		return $resule;
	}




}
