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

	public static function get_customer($id){
		$customer =  DB::table('customers')
		->select('customers.*','dataset_package.dt_package','dataset_qualification.code_name','dataset_qualification.business_qualifications as qualification_name') 
		->leftjoin('dataset_package','dataset_package.id','=','customers.package_id') 
		->leftjoin('dataset_qualification', 'dataset_qualification.id', '=','customers.qualification_id')
		->where('customers.id','=',$id)
		->first(); 
		return $customer;
	}

	// public static function get_customer_all_detail($id){
	// 	$customer = DB::table('customers_detail')
	// 	->select('customers_detail.*','dataset_provinces.id as provinces_id','dataset_provinces.name_th as provinces_name','dataset_amphures.name_th as amphures_name','dataset_amphures.id as amphures_id','dataset_districts.id as district_id','dataset_districts.name_th as district_name')
	// 	->where('customer_id','=',$id)
	// 	->leftjoin('dataset_provinces','dataset_provinces.id','=','customers_detail.province') 
	// 	->leftjoin('dataset_amphures','dataset_amphures.province_id','=','dataset_provinces.id') 
	// 	->leftjoin('dataset_districts','dataset_districts.amphure_id','=','dataset_amphures.id') 
	// 	->first(); 
	// 	return $customer;
	// }

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

				if(empty($data->pv_mt_active)){
					$date_mt_active= 'Not Active';
				}else{
					$date_mt_active= date('d/m/Y',strtotime($data->pv_mt_active));
				}
				
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

	public static function check_customer_directsponsor($customer_id){//เช็ค ลูกทีมที่แนะนำตรง แยกสาย A B C 

		$a =  DB::table('customers')
		->where('customers.introduce_id','=',$customer_id) 
		->where('introduce_type','=','A')
		->whereDate('pv_mt_active','>=',now())
		->where('package_id','!=','')
		->where('package_id','!=',null)
		->count();

		$b =  DB::table('customers')
		->where('customers.introduce_id','=',$customer_id) 
		->where('introduce_type','=','B')
		->whereDate('pv_mt_active','>=',now())
		->where('package_id','!=','')
		->where('package_id','!=',null)
		->count(); 

		$c =  DB::table('customers')
		->where('customers.introduce_id','=',$customer_id) 
		->where('introduce_type','=','C')
		->whereDate('pv_mt_active','>=',now())
		->where('package_id','!=','')
		->where('package_id','!=',null)
		->count();

		if($a>=5 and $b>=5 ){
			$reward_bonus =	'Five Star';
		}elseif($a>=5 and $c>=5 ){
			$reward_bonus =	'Five Star';
		}elseif($b>=5 and $c>=5 ){
			$reward_bonus =	'Five Star';
		}if($a>=3 and $b>=3 ){
			$reward_bonus =	'Triple Star';
		}elseif($a>=3 and $c>=3 ){
			$reward_bonus =	'Triple Star';
		}elseif($b>=3 and $c>=3 ){
			$reward_bonus =	'Triple Star';
		}if($a>=1 and $b>=1 ){
			$reward_bonus =	'Star';
		}elseif($a>=1 and $c>=1 ){
			$reward_bonus =	'Star';
		}elseif($b>=1 and $c>=1 ){
			$reward_bonus =	'Star';
		}else{
			$reward_bonus = ' - ';
		}

		//dd($c);
		$data = ['A'=>$a,'B'=>$b,'C'=>$c,'reward_bonus'=>$reward_bonus];
		return $data;  

	} 

}
?>