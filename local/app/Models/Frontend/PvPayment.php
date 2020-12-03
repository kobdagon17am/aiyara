<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
class PvPayment extends Model
{

	public static function PvPayment_type_1_success($order_id,$admin_id){//ทำคุณสมบัติ

		$order_data = DB::table('orders')
		->where('id','=',$order_id)
		->first();

		if(empty($order_data)){
			$resule = ['status'=>'fail','message'=>'ไม่มีบิลนี้อยู่ในระบบ'];
			return $resule;
		}

		$pv = $order_data->pv_total;
		$customer_id = $order_data->customer_id;

		if(empty($order_id) || empty($admin_id)){
			$resule = ['status'=>'fail','message'=>'Data is Null'];

		}else{

			try {
				DB::BeginTransaction();
				if($order_data->type_address == 0){
					$orderstatus_id = 4;
					# code...
				}else{
					$orderstatus_id = 5;

				}

				 

				$update_order = DB::table('orders')//update บิล
				->where('id',$order_id)
				->update(['id_admin_check' => $admin_id,
					'orderstatus_id'=> $orderstatus_id,
					'date_payment'=> date('Y-m-d H:i:s')]);

				$last_id = DB::table('order_payment_code')//เจนเลข payment order
				->select('id')
				->orderby('id','desc')
				->first();


				if($last_id){
					$last_id = $last_id->id +1;
				}else{
					$last_id = 0;
				}


				$maxId = substr("00000".$last_id, -5);

				$code_order = 'R'.date('Ymd').''.$maxId;

				$check_payment_code = DB::table('order_payment_code')//เจนเลข payment order
				->where('order_id','=',$order_id)
				->first(); 
				if($check_payment_code){

					$update_order_payment_code = DB::table('order_payment_code') 
					->where('order_id',$order_id)
					->update(['order_payment_status' => 'Success']);//ลงข้อมูลบิลชำระเงิน

				}else{

					$inseart_order_payment_code = DB::table('order_payment_code')->insert([
						'order_id'=>$order_id,
						'order_payment_code'=>$code_order,
						'order_payment_status'=>'Success'
				]);//ลงข้อมูลบิลชำระเงิน

				}


				$data_user = DB::table('Customers')//อัพ Pv ของตัวเอง
				->select('*')
				->where('id','=',$customer_id)
				->first();
				//dd($data_user);

				$add_pv = $data_user->pv + $pv;
				$update_pv = DB::table('Customers') 
				->where('id',$customer_id)
				->update(['pv' => $add_pv]);

				//ทำคุณสมบัติตัวเอง
				$upline_type = $data_user->line_type;
				$upline_id = $data_user->upline_id;
				$customer_id = $upline_id;
				$last_upline_type = $upline_type;

				if($customer_id != 'AA'){
					$j = 2;
					for ($i=1; $i <= $j ; $i++){ 

						$data_user = DB::table('Customers')
						->where('id','=',$customer_id)
						->first();

						$upline_type = $data_user->line_type;
						$upline_id = $data_user->upline_id;

						if($upline_id == 'AA'){

							if($last_upline_type == 'A'){

								$add_pv = $data_user->pv_a + $pv;
								$update_pv = DB::table('Customers') 
								->where('id',$customer_id)
								->update(['pv_a' => $add_pv]);

								$resule = ['status'=>'success','message'=>'Pv upline Type 1 Success'];
								$j = 0;

							}elseif($last_upline_type =='B'){
								$add_pv = $data_user->pv_b + $pv;
								$update_pv = DB::table('Customers') 
								->where('id',$customer_id)
								->update(['pv_b' => $add_pv]);

								$resule = ['status'=>'success','message'=>'Pv upline Type 1 Success'];
								$j = 0;

							}elseif($last_upline_type == 'C'){
								$add_pv = $data_user->pv_c + $pv; 
								$update_pv = DB::table('Customers') 
								->where('id',$customer_id)
								->update(['pv_c' => $add_pv]);

								$resule = ['status'=>'success','message'=>'Pv upline Type 1 Success'];
								$j = 0;

							}else{
								$resule = ['status'=>'fail','message'=>'Data Null Not Upline ID Type AA'];
								$j = 0;
							}

						}else{

							if($last_upline_type == 'A'){

								$add_pv = $data_user->pv_a + $pv;
								$update_pv = DB::table('Customers') 
								->where('id',$customer_id)
								->update(['pv_a' => $add_pv]);

								$customer_id = $upline_id;
								$last_upline_type = $upline_type;
								$j = $j+1;


							}elseif($last_upline_type =='B'){
								$add_pv = $data_user->pv_b + $pv;
								$update_pv = DB::table('Customers') 
								->where('id',$customer_id)
								->update(['pv_b' => $add_pv]);

								$customer_id = $upline_id;
								$last_upline_type = $upline_type;
								$j = $j+1;

							}elseif($last_upline_type == 'C'){
								$add_pv = $data_user->pv_c + $pv; 
								$update_pv = DB::table('Customers') 
								->where('id',$customer_id)
								->update(['pv_c' => $add_pv]);

								$customer_id = $upline_id;
								$last_upline_type = $upline_type;
								$j = $j+1;

							}else{
								$resule = ['status'=>'fail','message'=>'Data Null Not Upline ID'];
								$j = 0;
							}


						}


					}

				}else{
					$resule = ['status'=>'success','message'=>'Pv add Type AA Success'];

				}
				

				if($resule['status'] == 'success'){
					DB::commit();
					return $resule;
				}else{
					DB::rollback();
					return $resule;
				}

			} catch (Exception $e) {
				DB::rollback();
				$resule = ['status'=>'fail','message'=>'Update PvPayment Fail'];
				return $resule;

			}

		}


	}


}


?>