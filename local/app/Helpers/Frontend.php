<?php
namespace App\Helpers;
use DB;
use Auth;
class Frontend{
	public static function get_gitfvoucher($user_name){

		$data_gv = DB::table('db_giftvoucher_cus')
		->selectRaw('sum(giftvoucher_banlance) as sum_gv')
		->where('customer_username','=',$user_name)
		->where('pro_sdate','<=',date('Y-m-d'))
    ->where('pro_edate','>=',date('Y-m-d'))
    ->whereraw('(pro_status = 1 || pro_status = 2)')

		->first();

		return $data_gv;

	}

	public static function get_customer($user_name){
		$customer =  DB::table('customers')
		->select('customers.*','dataset_package.dt_package','dataset_qualification.code_name','dataset_qualification.business_qualifications as qualification_name')
		->leftjoin('dataset_package','dataset_package.id','=','customers.package_id')
		->leftjoin('dataset_qualification', 'dataset_qualification.id', '=','customers.qualification_id')
		->where('customers.user_name','=',$user_name)
		->first();


		return $customer;
	}

  public static function get_customer_id($id){
		$customer =  DB::table('customers')
		->select('customers.*','dataset_package.dt_package','dataset_qualification.code_name','dataset_qualification.business_qualifications as qualification_name')
		->leftjoin('dataset_package','dataset_package.id','=','customers.package_id')
		->leftjoin('dataset_qualification', 'dataset_qualification.id', '=','customers.qualification_id')
		->where('customers.id','=',$id)
		->first();


		return $customer;
	}

  public static function check_kyc($user_customer){
		$customer =  DB::table('customers')
		->select('*')
		->where('user_name','=',$user_customer)
    ->where('regis_doc1_status','=','1')
    ->where('regis_doc2_status','=','1')
    ->where('regis_doc3_status','=','1')
    ->where('regis_doc4_status','=','1')
    ->where('regis_date_doc','!=',null)
		->first();

    if($customer){
      $html = '<div class="alert alert-success background-success">
        <i class="fa fa-check text-white"></i>
      </button>
      <strong> SUCCESS </strong> <code> เอกสารผ่านการตรวจสอบเรียบร้อย </code>
      </div>';

      $rs = ['status'=>'success','message'=>'เอกสารผ่านเเล้ว','html'=>$html];
    }else{
      $html = '<div class="alert alert-warning icons-alert">
       <p><strong>Warning!</strong> <code>คุณไม่สามารถทำรายการใดๆได้ หากยังไม่ผ่านการยืนยันตัวตน</code>  <a href="'.route('docs').'" class="pcoded-badge label label-warning ">ตรวจสอบ</a></p>
      </div>';

    $rs = ['status'=>'fail','message'=>'คุณไม่สามารถทำรายการใดๆได้ หากยังไม่ผ่านการยืนยันตัวตน',
    'html'=>$html];
    }
    return $rs;


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

	public static function check_mt_active($pv_mt_active){

			if(empty($pv_mt_active) || (strtotime($pv_mt_active) < strtotime(date('Ymd')))){

				if(empty($pv_mt_active)){
					$date_mt_active= 'Not Active';
				}else{
					$date_mt_active= date('d/m/Y',strtotime($pv_mt_active));
				}

				$resule = ['status'=>'success','type'=>'N','message'=>'Not Active','date'=>$date_mt_active];

				return $resule;
			}else{
				$date_mt_active= date('d/m/Y',strtotime($pv_mt_active));
				$resule = ['status'=>'success','type'=>'Y','message'=>'Active Success','date'=>$date_mt_active];
				return $resule;

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

	public static function check_customer_directsponsor($team_active_a ='',$team_active_b = '',$team_active_c = ''){//เช็ค ลูกทีมที่แนะนำตรง แยกสาย A B C

		if($team_active_a>=5 and $team_active_b>=5 ){
			$reward_bonus =	'Five Star';
		}elseif($team_active_a>=5 and $team_active_c>=5 ){
			$reward_bonus =	'Five Star';
		}elseif($team_active_b>=5 and $team_active_c>=5 ){
			$reward_bonus =	'Five Star';
		}elseif($team_active_a>=3 and $team_active_b>=3 ){
			$reward_bonus =	'Triple Star';
		}elseif($team_active_a>=3 and $team_active_c>=3 ){
			$reward_bonus =	'Triple Star';
		}elseif($team_active_b>=3 and $team_active_c>=3 ){
			$reward_bonus =	'Triple Star';
		}elseif($team_active_a>=1 and $team_active_b>=1 ){
			$reward_bonus =	'Star';
		}elseif($team_active_a>=1 and $team_active_c>=1 ){
			$reward_bonus =	'Star';
		}elseif($team_active_b>=1 and $team_active_c>=1 ){
			$reward_bonus =	'Star';
		}else{
			$reward_bonus = ' - ';
		}

		//dd($c);
		$data = ['A'=>$team_active_a,'B'=>$team_active_b,'C'=>$team_active_c,'reward_bonus'=>$reward_bonus];


		return $data;

	}

  public static function check_count_expri_giv($customer_user){

// SELECT DATEDIFF(pro_edate,'2021-04-21') AS DateDiff FROM `db_giftvoucher_cus`
// where DATEDIFF(pro_edate,'2021-04-21') = 8

		$data = DB::table('db_giftvoucher_cus')
    ->select('*')
		->where('customer_username','=',$customer_user)
    ->wheredate('pro_edate','>=',now())
    ->whereraw("DATEDIFF(pro_edate,now()) <= 10 ")
		->count();
    return $data;
  }

}
?>
