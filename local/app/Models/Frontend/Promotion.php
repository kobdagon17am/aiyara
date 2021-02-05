<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
 
class Promotion extends Model
{
	public static function all_available_purchase($promotion_id){

		$resule = ['status'=>'success','message'=>'success','all_available_purchase'=>90];
		return $resule;
	}
	

}
