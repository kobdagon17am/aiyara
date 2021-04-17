<?php
namespace App\Helpers;
use DB;
use Auth;
class Frontend{
	public static function get_gitfvoucher($user_name){

		$data_gv = DB::table('db_giftvoucher_cus')
		->selectRaw('sum(giftvoucher_banlance) as sum_gv')
		->where('customer_code','=',$user_name)
		->where('pro_sdate','<=',date('Y-m-d'))
    ->where('pro_edate','>=',date('Y-m-d'))
    ->whereraw('(pro_status = 1 || pro_status = 2)')

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

	public static function notifications($customer_id){

		$data = DB::table('pm')
		->select('*')
		->where('customers_id_fk','=',$customer_id)
		->where('see_status','=',0)
		->orderby('created_at','DESC')
		->get();

		$count_data = count($data);

		if($count_data <= 0){
			$rs = ['status'=>'fail','count'=>$count_data];

		}else{

			$rs = ['status'=>'success','data'=>$data,'count'=>$count_data];

		}

		return $rs;

	}

	// public static function get_customer_all_detail($id){
	// 	$customer = DB::table('customers_detail')
	// 	->select('customers_detail.*','dataset_provinces.id as provinces_id','dataset_provinces.name_th as provinces_name','dataset_amphures.name_th as amphures_name','dataset_amphures.id as amphures_id','dataset_districts.id as district_id','dataset_districts.name_th as district_name')
	// 	->where('customer_id','=',$id)
	// 	->leftjoin('dataset_provinces','dataset_provinces.id','=','customers_detail.province_id_fk')
	// 	->leftjoin('dataset_amphures','dataset_amphures.province_id','=','dataset_provinces.id')
	// 	->leftjoin('dataset_districts','dataset_districts.amphure_id','=','dataset_amphures.id')
	// 	->first();
	// 	return $customer;
	// }

	public static function get_ce_register($ce_id){

		$orders = DB::table('db_order_products_list')
		->where('course_id_fk','=',$ce_id)
		->where('approve_status','=',0)
		->count();

		$course_event_regis = DB::table('course_event_regis')
		->where('ce_id_fk',$ce_id)
		->count();

		$sum = $orders + $course_event_regis;
		return $sum;

	}

	public static function check_reward_history($customer_id,$reward_id){
		$data = DB::table('db_logs_qualification')
		->where('customer_id_fk','=',$customer_id)
		->where('qualification_id_fk','=',$reward_id)
		->first();

		return $data;

	}

	public static function get_promotion_detail($promotion_id,$location_id){

		$promotion_detail = DB::table('promotions_products')
		->select('promotions_products.promotion_id_fk','promotions_products.product_id_fk','promotions_products.product_amt','products_details.product_name','products_cost.member_price'
			,'dataset_product_unit.group_id as unit_id','dataset_product_unit.product_unit as unit_name')
		->leftjoin('products','products.id','=','promotions_products.product_id_fk')
		->leftjoin('products_details', 'products.id', '=', 'products_details.product_id_fk')
		->leftjoin('products_cost', 'products.id', '=', 'products_cost.product_id_fk')

		->leftjoin('dataset_product_unit', 'dataset_product_unit.group_id', '=', 'promotions_products.product_unit')
		->where('promotions_products.promotion_id_fk','=',$promotion_id)
		->where('products_details.lang_id', '=',1)
		->where('dataset_product_unit.lang_id', '=',1)
		->where('products_cost.business_location_id','=',$location_id)
		->get();
		//dd($promotion_detail);

		return $promotion_detail;

	}

  public static function get_giveaway_detail($product_list_id_fk){

    $giveaway =  DB::table('db_order_products_list_giveaway')
    ->select('db_order_products_list_giveaway.*')
    ->where('db_order_products_list_giveaway.product_list_id_fk','=',$product_list_id_fk)
    ->get();

		return $giveaway;

	}



	public static function get_ce_register_per_customer_perday($ce_id,$customer_id){//ต่อวัน

		$date_now = date('Y-m-d');
		//$date_now = '2021-01-13';


		$orders = DB::table('db_order_products_list')
		->leftjoin('db_orders','db_orders.id','=','db_order_products_list.frontstore_id_fk')
		->where('db_order_products_list.course_id_fk','=',$ce_id)
		->where('db_orders.customers_id_fk','=',$customer_id)
		->whereDate('db_orders.created_at','=',$date_now)
		->where('db_orders.approve_status','=',0)
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

		$orders = DB::table('db_order_products_list')
		->leftjoin('db_orders','db_orders.id','=','db_order_products_list.frontstore_id_fk')
		->where('db_order_products_list.course_id_fk','=',$ce_id)
		->where('db_orders.customers_id_fk','=',$customer_id)
		->where('db_orders.approve_status','=',0)
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
