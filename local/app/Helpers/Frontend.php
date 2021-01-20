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

		$orders = DB::table('order_items')
		->where('course_id','=',$ce_id)
		->where('status','=','order')
		->count();

		$course_event_regis = DB::table('course_event_regis') 
		->where('ce_id_fk',$ce_id)
		->count();

		$sum = $orders + $course_event_regis;
		return $sum;

	}

	public static function get_ce_register_per_customer_perday($ce_id,$customer_id){//ต่อวัน

		$date_now = date('Y-m-d');
		//$date_now = '2021-01-13';

		$orders = DB::table('order_items')
		->leftjoin('orders','orders.id','=','order_items.order_id') 
		->where('order_items.course_id','=',$ce_id)
		->where('orders.customer_id','=',$customer_id)
		->whereDate('order_items.create_at','=',$date_now)
		->where('status','=','order')
		->count();
		 
		$course_event_regis = DB::table('course_event_regis') 
		->where('ce_id_fk',$ce_id)
		->where('customers_id_fk','=',$customer_id)
		->whereDate('created_at','=',$date_now)
		->count();

		$sum = $orders + $course_event_regis;
		return $sum;

	}

	public static function get_ce_register_per_customer_percourse($ce_id,$customer_id){//ต่อวัน

		$orders = DB::table('order_items')
		->leftjoin('orders','orders.id','=','order_items.order_id') 
		->where('order_items.course_id','=',$ce_id)
		->where('orders.customer_id','=',$customer_id)
		->where('status','=','order')
		->count();
		 
		$course_event_regis = DB::table('course_event_regis') 
		->where('ce_id_fk',$ce_id)
		->where('customers_id_fk','=',$customer_id)
		->count();

		$sum = $orders + $course_event_regis;
		return $sum;

	}

	public static function check_mt_active($customer_id){
		$data = DB::table('customers') 
		->where('id','=',$customer_id)
		->first();

		if(empty($data)){
			$resule = ['status'=>'fail','message'=>'User is Null'];
			return $resule;

		}else{
			if(empty($data->pv_mt_active) || (strtotime($data->pv_mt_active) < strtotime(date('Ymd')))){
				
				$date_mt_active= date('d/m/Y',strtotime($data->pv_mt_active));
				$resule = ['status'=>'success','type'=>'N','message'=>'Not Active','date'=>$date_mt_active];

				return $resule;
			}else{
				$date_mt_active= date('d/m/Y',strtotime($data->pv_mt_active));
				$resule = ['status'=>'success','type'=>'Y','message'=>'Active Success','date'=>$date_mt_active];
				return $resule;

			}

		}

	}

	public static function check_tv_active($customer_id){
		$data = DB::table('customers') 
		->where('id','=',$customer_id)
		->first();

		if(empty($data)){
			$resule = ['status'=>'fail','message'=>'User is Null'];
			return $resule;

		}else{
			if(empty($data->pv_tv_active) || (strtotime($data->pv_tv_active) < strtotime(date('Ymd')))){
				
				$date_tv_active= date('d/m/Y',strtotime($data->pv_tv_active));
				$resule = ['status'=>'success','type'=>'N','message'=>'Not Active','date'=>$date_tv_active];

				return $resule;
			}else{
				$date_tv_active= date('d/m/Y',strtotime($data->pv_tv_active));
				$resule = ['status'=>'success','type'=>'Y','message'=>'Active Success','date'=>$date_tv_active];
				return $resule;

			}

		}

	} 

}
?>