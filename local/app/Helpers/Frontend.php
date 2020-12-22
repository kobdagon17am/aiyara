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
}
?>