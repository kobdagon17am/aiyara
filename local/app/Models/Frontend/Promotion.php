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
    ->where('db_orders.order_status_id_fk', '!=', 8)
    ->where('db_order_products_list.promotion_id_fk', '=', $promotion_id)
    ->where('type_product', '=','promotion')
    ->first();

    // $all_available_purchase = DB::table('db_order_products_list')
    // ->select(DB::raw('sum(db_order_products_list.amt) as amt_all'))
    // ->join('db_orders', 'db_orders.id', '=', 'db_order_products_list.frontstore_id_fk')
    // ->where('db_orders.order_status_id_fk', '!=', 8)
    // ->where('db_order_products_list.promotion_id_fk', '=', $promotion_id)
    // ->where('type_product', '=','promotion')
    // ->groupBy('db_order_products_list.promotion_id_fk')
    // ->first();

		$resule = ['status'=>'success','message'=>'success','all_available_purchase'=>$all_available_purchase->amt_all];
		return $resule;
	}

	public static function count_per_promotion($promotion_id,$customer_id){//เฉพาะต่อรอบโปรโมชั่น


    $count_per_promotion = DB::table('db_order_products_list')
    ->select(db::raw('sum(db_order_products_list.amt) as amt_all'))
    ->leftjoin('db_orders', 'db_orders.id', '=', 'db_order_products_list.frontstore_id_fk')
    ->where('db_order_products_list.type_product', '=','promotion')
    ->where('db_orders.order_status_id_fk', '!=', 8)
    ->where('db_order_products_list.promotion_id_fk', '=', $promotion_id)
    ->where('db_orders.customers_id_fk', '=',$customer_id)
    ->first();

    // $count_per_promotion = DB::table('db_order_products_list')
    // ->select(db::raw('sum(db_order_products_list.amt) as amt_all'))
    // ->leftjoin('db_orders', 'db_orders.id', '=', 'db_order_products_list.frontstore_id_fk')
    // ->where('db_order_products_list.promotion_id_fk', $promotion_id)
    // ->where('db_orders.customers_id_fk', $customer_id)
    // ->where('db_order_products_list.type_product', 'promotion')
    // ->whereIn('db_orders.order_status_id_fk', [1, 2, 3, 4, 5, 6, 7])
    // ->first();

//     Explanation:

// Remove the "!=" operator in the where clause for order status, and use whereIn() instead to check for multiple order statuses that are not equal to 8.
// Move the promotion_id_fk and customers_id_fk conditions to the beginning of the where clause for better performance.

		$resule = ['status'=>'success','message'=>'success','count'=>$count_per_promotion->amt_all];
		return $resule;
	}

	public static function count_per_promotion_day($promotion_id,$customer_id){//ต่อวันภายในรอบโปรโมชั่น
    $date_now = date('Y-m-d');
    $count_per_promotion = DB::table('db_order_products_list')
    ->select(db::raw('sum(db_order_products_list.amt) as amt_all'))
    ->leftjoin('db_orders', 'db_orders.id', '=', 'db_order_products_list.frontstore_id_fk')
    ->where('db_order_products_list.type_product', '=','promotion')
    ->where('db_orders.order_status_id_fk', '!=', 8)
    ->where('db_order_products_list.promotion_id_fk', '=', $promotion_id)
    ->where('db_orders.customers_id_fk', '=',$customer_id)
    ->wheredate('db_order_products_list.created_at', '=',$date_now)
    ->first();

    //     $count_per_promotion = DB::table('db_order_products_list')
    // ->select(db::raw('sum(db_order_products_list.amt) as amt_all'))
    // ->leftjoin('db_orders', 'db_orders.id', '=', 'db_order_products_list.frontstore_id_fk')
    // ->where('db_order_products_list.promotion_id_fk', $promotion_id)
    // ->where('db_orders.customers_id_fk', $customer_id)
    // ->where('db_order_products_list.type_product', 'promotion')
    // ->whereIn('db_orders.order_status_id_fk', [1, 2, 3, 4, 5, 6, 7])
    // ->whereDate('db_order_products_list.created_at', $date_now)
    // ->first();

//     Explanation:

// Remove the "!=" operator in the where clause for order status, and use whereIn() instead to check for multiple order statuses that are not equal to 8.
// Move the promotion_id_fk and customers_id_fk conditions to the beginning of the where clause for better performance.
// Change whereDate to wheredate for better performance



		$resule = ['status'=>'success','message'=>'success','count'=>$count_per_promotion->amt_all];
		return $resule;
	}




}
