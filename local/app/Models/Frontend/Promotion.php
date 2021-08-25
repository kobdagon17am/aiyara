<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;

class Promotion extends Model
{
	public static function all_available_purchase($promotion_id){
		$resule = ['status'=>'success','message'=>'success','all_available_purchase'=>2];
		return $resule;
	}

	public static function count_per_promotion($promotion_id){//เฉพาะต่อรอบโปรโมชั่น
		$resule = ['status'=>'success','message'=>'success','count'=>3];
		return $resule;
	}

	public static function count_per_promotion_day($promotion_id){//ต่อวันภายในรอบโปรโมชั่น
		$resule = ['status'=>'success','message'=>'success','count'=>2];
		return $resule;
	}




}
