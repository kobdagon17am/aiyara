<?php
namespace App\Models\Frontend;
use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
class GiftVoucher extends Model
{

	public static function add_gift($user_name,$gv,$code,$expri_date,$admin_id){

		$data_user = DB::table('customers') 
		->where('user_name','=',$user_name)
		->first();
		try {
			DB::BeginTransaction();
			if(empty($data_user)){
				$resule = ['status'=>'fail','message'=>'Empty UserName in system'];
			}else{

				DB::table('gift_voucher')
				->insert(['customer_id'=>$data_user->id,'code'=>$code,'gv'=>$gv,'banlance'=>$gv,'admin_id'=>$admin_id,'expiry_date'=>$expri_date ]);
				$resule = ['status'=>'success','message'=>'Add GiftVoucher Success'];
			}

			if($resule['status'] == 'success'){
				DB::commit();
				//DB::rollback();
				return $resule;
			}else{
				DB::rollback();
				return $resule;
			}

		} catch (Exception $e) {
			DB::rollback();
			$resule = ['status'=>'fail','message'=>$e];
			return $resule;

		}

	}

	public static function log_gift($price_total,$customer_id,$order_id){
		// $admin_id = 99;
		// $price_total = 900;
		// $order_id = 99;
		$id = Auth::guard('c_user')->user()->id;  
		$gv_customer = \App\Helpers\Frontend::get_gitfvoucher($id);
		$gv_sum=$gv_customer->sum_gv;

		$data_gv = DB::table('gift_voucher')
		->where('customer_id','=',$id)
		->where('banlance','>',0)
		->where('expiry_date','>',date('Y-m-d H:i:s'))
		->orderby('expiry_date')
		->get();

		try {
			DB::BeginTransaction();

			if($price_total >= $gv_sum){
				
				foreach ($data_gv as $value){
				$update_giftvoucher = DB::table('gift_voucher')//update บิล
				->where('id',$value->id)
				->update(['banlance' => 0 ]);

				$insert_log_gift_voucher = DB::table('log_gift_voucher')->insert([
					'customer_id'=>$id,
					'gift_voucher_id'=>$value->id,
					'order_id'=>$order_id,
					'gv'=>$value->banlance,
					'status'=>'order',
				]);
			}
			DB::commit();
			$resule = ['status'=>'success','message'=>'Use GiftVoucher Success'];
			return $resule;
		}else{
			foreach ($data_gv as $value){
				$gv_rs = $price_total - $value->banlance;
				
				if($gv_rs > 0){

					$update_giftvoucher = DB::table('gift_voucher')//update บิล
					->where('id',$value->id)
					->update(['banlance' => 0 ]);

					$insert_log_gift_voucher = DB::table('log_gift_voucher')->insert([
						'customer_id'=>$id,
						'gift_voucher_id'=>$value->id,
						'order_id'=>$order_id,
						'gv'=>$value->banlance,
						'status'=>'order',
					]);

					$price_total = $gv_rs;

				}else{


					$update_giftvoucher = DB::table('gift_voucher')//update บิล
					->where('id',$value->id)
					->update(['banlance' => abs($gv_rs) ]);

					$insert_log_gift_voucher = DB::table('log_gift_voucher')->insert([
						'customer_id'=>$id,
						'gift_voucher_id'=>$value->id,
						'order_id'=>$order_id,
						'gv'=>$price_total,
						'status'=>'order',
					]);

					DB::commit();
					$resule = ['status'=>'success','message'=>'Use GiftVoucher Success'];
					return $resule;
				}
			}	
		}

	}catch(Exception $e) {
		DB::rollback();
		$resule = ['status'=>'fail','message'=>$e]; 
		return $resule;

	}


}



}
?>