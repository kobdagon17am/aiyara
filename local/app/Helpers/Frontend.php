<?php
namespace App\Helpers;
use DB;
use Auth;
class Frontend{
	public static function get_gitfvoucher($id){
		$data_gv = DB::table('gift_voucher')
		->selectRaw('sum(banlance) as sum_gv')
		->where('customer_id','=',$id)
		->where('expiry_date','>',date('Y-m-d H:i:s'))
		->first();
		return $data_gv;

	}

	public static function get_ce_register($ce_id){

		$orders = DB::table('orders')
		->where('course_id','=',$ce_id)
		->count();

		$course_event_regis = DB::table('course_event_regis') 
		->where('ce_id_fk',$ce_id)
		->count();

		$sum = $orders + $course_event_regis;

		return $sum;

	}
}
?>