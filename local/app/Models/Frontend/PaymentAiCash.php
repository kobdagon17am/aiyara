<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
use App\Models\Frontend\RunNumberPayment;

class PaymentAiCash extends Model
{
	public static function payment_uploadfile($rs){
		DB::BeginTransaction();
		$price = str_replace(',','',$rs->price);
		$business_location_id = Auth::guard('c_user')->user()->business_location_id;
		$customer_id = Auth::guard('c_user')->user()->id;
		$code_order = RunNumberPayment::run_number_order($business_location_id);

		try{
			$id = DB::table('ai_cash')->insertGetId(
				[
					'code_order' => $code_order,
					'customer_id_fk' => $customer_id,
					'ai_cash' => $price,
					'order_type_id_fk'  => $rs->type,
					'pay_type_id_fk'  => $rs->pay_type,
					'business_location_id_fk' => $business_location_id,
					'order_status_id_fk' => 2,
					'status' => 'panding',
					'detail' => 'Add Ai-Cash', 
				] 
			);


			$file_slip = $rs->file_slip;
			if(isset($file_slip)){
				$url='local/public/files_slip/'.date('Ym');

				$f_name = date('YmdHis').'_'.$customer_id.'.'.$file_slip->getClientOriginalExtension();
				if($file_slip->move($url,$f_name)){
					DB::table('payment_slip')
					->insert(['customer_id'=>$customer_id,'url'=>$url,'file'=>$f_name,'order_id'=>$id,'type'=>'ai-cash']);
				}
			}

			$resule = ['status'=>'success','message'=>'Add Ai-Cash Success'];
			DB::commit();
			return $resule;
		}catch(Exception $e){ 
			DB::rollback();
			$resule = ['status'=>'fail','message'=>$e];
			return $resule; 
		}
	}

	public static function payment_not_uploadfile($rs){
		DB::BeginTransaction();

		$price = str_replace(',','',$rs->price);
		$business_location_id = Auth::guard('c_user')->user()->business_location_id;;
		$customer_id = Auth::guard('c_user')->user()->id;
		$code_order = RunNumberPayment::run_number_order($business_location_id);

		try{
			$id = DB::table('ai_cash')->insertGetId(
				[
					'code_order' => $code_order,
					'customer_id_fk' => $customer_id,
					'ai_cash' => $price,
					'order_type_id_fk'  => $rs->type,
					'pay_type_id_fk'  => $rs->pay_type,
					'business_location_id_fk' => $business_location_id,
					'order_status_id_fk' => 1,
					'status' => 'panding',
					'detail' => 'Add Ai-Cash', 
				] 
			);


			$file_slip = $rs->file_slip;
			if(isset($file_slip)){
				$url='local/public/files_slip/'.date('Ym');

				$f_name = date('YmdHis').'_'.$customer_id.'.'.$file_slip->getClientOriginalExtension();
				if($file_slip->move($url,$f_name)){
					DB::table('payment_slip')
					->insert(['customer_id'=>$customer_id,'url'=>$url,'file'=>$f_name,'order_id'=>$id,'type'=>'ai-cash']);
				}
			}

			$resule = ['status'=>'success','message'=>'Add Ai-Cash Success'];
			DB::commit();
			return $resule;
		}catch(Exception $e){ 
			DB::rollback();
			$resule = ['status'=>'fail','message'=>$e];
			return $resule; 
		}
	}

	public static function credit_card($rs){

		DB::BeginTransaction();
		$price = str_replace(',','',$rs->price);
		
		$business_location_id = Auth::guard('c_user')->user()->business_location_id;;
		$customer_id = Auth::guard('c_user')->user()->id;
		$code_order = RunNumberPayment::run_number_order($business_location_id);

		try{

			$customer_data = DB::table('customers')
							->select('ai_cash')
							->where('id','=',$customer_id,)
							->first();
			$banlance = $customer_data->ai_cash + $price;

			$id = DB::table('ai_cash')->insertGetId(
				[
					'code_order' => $code_order,
					'customer_id_fk' => $customer_id,
					'ai_cash' => $price,
					'banlance' => $banlance,
					'order_type_id_fk'  => $rs->type,
					'pay_type_id_fk'  => $rs->pay_type,
					'business_location_id_fk' => $business_location_id,
					'order_status_id_fk' => 7,
					'status' => 'success',
					'detail' => 'Add Ai-Cash', 
				] 
			);

			 $customers_update = DB::table('customers')
			->where('id',$customer_id)
			->update(['ai_cash' => $banlance]);

			$resule = ['status'=>'success','message'=>'Add Ai-Cash Success'];
			DB::commit();
			return $resule;
		}catch(Exception $e){ 
			DB::rollback();
			$resule = ['status'=>'fail','message'=>$e];
			return $resule; 
		}
	}



}
