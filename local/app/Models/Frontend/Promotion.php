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
    ->where('promotion_id_fk', '=', $promotion_id)
    ->where('type_product', '=','promotion')
    ->count();

		$resule = ['status'=>'success','message'=>'success','all_available_purchase'=>$all_available_purchase];
		return $resule;
	}

	public static function count_per_promotion($promotion_id,$customer_id){//เฉพาะต่อรอบโปรโมชั่น

    $count_per_promotion = DB::table('db_order_products_list')
    ->where('promotion_id_fk', '=', $promotion_id)
    ->where('type_product', '=','promotion')
    ->where('customer_id_fk', '=',$customer_id)
    ->count();

		$resule = ['status'=>'success','message'=>'success','count'=>$count_per_promotion];
		return $resule;
	}

	public static function count_per_promotion_day($promotion_id,$customer_id){//ต่อวันภายในรอบโปรโมชั่น
    $date_now = date('Y-m-d');
    $count_per_promotion = DB::table('db_order_products_list')
    ->where('promotion_id_fk', '=', $promotion_id)
    ->where('type_product', '=','promotion')
    ->where('customer_id_fk', '=',$customer_id)
    ->wheredate('created_at', '=',$date_now)
    ->count();

		$resule = ['status'=>'success','message'=>'success','count'=>$count_per_promotion];
		return $resule;
	}




}
