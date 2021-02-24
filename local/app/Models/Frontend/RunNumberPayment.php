<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use App\Models\Db_orders;
use App\Models\DbInvoiceCode;

class RunNumberPayment extends Model
{

//patern R121011100001

//O = Oder
//R = Payment
//E = Course/Event

	public static function run_number_order($business_location_id_fk){

		$id = Db_orders::where('business_location_id_fk','=',$business_location_id_fk)
		->where('date_setting_code','=',date('ym'))
		->orderby('id','desc')
		->first();


		if(@$id->code_order){
			$last_code = $id->code_order;
			$code = substr($last_code,-5);
			$last_code = $code + 1;

			$num_code = substr("00000".$last_code, -5);
			$code_order = 'O'.$business_location_id_fk.date('ymd').''.$num_code;

		}else{
			$last_code = 1;
			$num_code = substr("00000".$last_code, -5);
			$code_order = 'O'.$business_location_id_fk.date('ymd').''.$num_code;
		}

		return  $code_order;
	}


	public static function run_payment_code($business_location_id,$type_order){
		//R = Payment
		//E = Course/Event

		$data = DbInvoiceCode::where('business_location_id','=',$business_location_id)
		->where('date_setting_code','=',date('ym'))
		->where('type_order','=',$type_order)
		->orderby('id','desc')
		->first();


		if($type_order == 'product'){
			$type_code = 'R';
		}elseif ($type_order == 'course_event') {
			$type_code = 'E';
		}else{
			$resule = ['status'=>'fail','message'=>'Is not product/course_event'];
			return  $resule;
		}

		if(@$data->order_payment_code){
			$last_code = $data->order_payment_code;
			$code = substr($last_code,-5);
			$last_code = $code + 1;

			$num_code = substr("00000".$last_code, -5);
			$code_order = $type_code.$business_location_id.date('ymd').''.$num_code;

		}else{
			$last_code = 1;
			$num_code = substr("00000".$last_code, -5);
			$code_order = $type_code.$business_location_id.date('ymd').''.$num_code;
		}

		$resule = ['status'=>'success','message'=>'success','code_order'=>$code_order];
		return  $resule;
 
	}


}