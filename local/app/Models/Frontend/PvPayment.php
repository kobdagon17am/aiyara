<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
class PvPayment extends Model
{

	public static function PvPayment_type_1_success($order_id,$admin_id){//ทำคุณสมบัติ
		//dd('ssss');die;

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


				$data_user = DB::table('customers')//อัพ Pv ของตัวเอง
				->select('*')
				->where('id','=',$customer_id)
				->first();
				//dd($data_user);

				$add_pv = $data_user->pv + $pv;
				$update_pv = DB::table('customers') 
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

						$data_user = DB::table('customers')
						->where('id','=',$customer_id)
						->first();

						$upline_type = $data_user->line_type;
						$upline_id = $data_user->upline_id;

						if($upline_id == 'AA'){

							if($last_upline_type == 'A'){

								$add_pv = $data_user->pv_a + $pv;
								$update_pv = DB::table('customers') 
								->where('id',$customer_id)
								->update(['pv_a' => $add_pv]);

								$resule = ['status'=>'success','message'=>'Pv upline Type 1 Success'];
								$j = 0;

							}elseif($last_upline_type =='B'){
								$add_pv = $data_user->pv_b + $pv;
								$update_pv = DB::table('customers') 
								->where('id',$customer_id)
								->update(['pv_b' => $add_pv]);

								$resule = ['status'=>'success','message'=>'Pv upline Type 1 Success'];
								$j = 0;

							}elseif($last_upline_type == 'C'){
								$add_pv = $data_user->pv_c + $pv; 
								$update_pv = DB::table('customers') 
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
								$update_pv = DB::table('customers') 
								->where('id',$customer_id)
								->update(['pv_a' => $add_pv]);

								$customer_id = $upline_id;
								$last_upline_type = $upline_type;
								$j = $j+1;


							}elseif($last_upline_type =='B'){
								$add_pv = $data_user->pv_b + $pv;
								$update_pv = DB::table('customers') 
								->where('id',$customer_id)
								->update(['pv_b' => $add_pv]);

								$customer_id = $upline_id;
								$last_upline_type = $upline_type;
								$j = $j+1;

							}elseif($last_upline_type == 'C'){
								$add_pv = $data_user->pv_c + $pv; 
								$update_pv = DB::table('customers') 
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


	public static function PvPayment_type_confirme($order_id,$admin_id){//1 ทำคุณสมบัติ //2 รักษาคุณสมบัตรรายเดือน

		$order_data = DB::table('orders')
		->where('id','=',$order_id)
		->first();

		if(empty($order_data)){
			$resule = ['status'=>'fail','message'=>'ไม่มีบิลนี้อยู่ในระบบ'];
			return $resule;
		}

		$pv = $order_data->pv_total;
		$customer_id = $order_data->customer_id;
		$type_id = $order_data->type_id;

		if(empty($order_id) || empty($admin_id)){
			$resule = ['status'=>'fail','message'=>'Data is Null'];

		}else{

			try {
				DB::BeginTransaction();
				if($order_data->type_address == 0){
					$orderstatus_id = 5;
					# code...
				}else{
					$orderstatus_id = 4;

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

				if($type_id == 1){//ทำคุณสมบติ
						$data_user = DB::table('customers')//อัพ Pv ของตัวเอง
						->select('*')
						->where('id','=',$customer_id)
						->first();
				//dd($data_user);

						$add_pv = $data_user->pv + $pv;
						$update_pv = DB::table('customers') 
						->where('id',$customer_id)
						->update(['pv' => $add_pv]);

				//ทำคุณสมบัติตัวเอง
						$upline_type = $data_user->line_type;
						$upline_id = $data_user->upline_id;
						$customer_id = $upline_id;
						$last_upline_type = $upline_type;

					}elseif ($type_id == 2) {//รักษาคุณสมบัติรายเดือน

					     $data_user = DB::table('customers')//อัพ Pv ของตัวเอง
					     ->select('*')
					     ->where('id','=',$customer_id)
					     ->first();

					     $strtime_user = strtotime($data_user->pv_mt_active);
					     $strtime = strtotime(date("Y-m-d"));

					     if($data_user->status_pv_mt == 'first'){

					     	if($strtime_user > $strtime){

					     		$contract_date = strtotime(date('Y-m',$strtime_user));

					     		$caltime = strtotime("+1 Month",$contract_date);
					     		$start_month = date("Y-m", $caltime);

					     	}else{

					     		$start_month = date("Y-m");
					     	}

				        $promotion_mt = DB::table('dataset_mt_tv')//อัพ Pv ของตัวเอง
				        ->select('*')
				        ->where('code','=','pv_mt')
				        ->first();

				        $pro_mt = $promotion_mt->pv;
				        $pv_mt = $data_user->pv_mt;
				        $pv_mt_all = $pv+$pv_mt;

				        if($pv_mt_all >= $pro_mt){
					        	//dd('หักลบค่อยอัพเดท');
					          //หักลบค่อยอัพเดท
				        	$mt_mount= $pv_mt_all/$pro_mt;
					          $mt_mount = floor($mt_mount);//จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
					          $pv_mt_total = $pv_mt_all-($mt_mount*$pro_mt);//ค่า pv ที่ต้องเอาไปอัพเดท DB

					          $mt_active = strtotime("+$mt_mount Month",strtotime($start_month));
					          $mt_active = date('Y-m-t',$mt_active);//วันที่ mt_active 

					          $update_mt = DB::table('customers') 
					          ->where('id',$customer_id)
					          ->update(['pv_mt' => $pv_mt_total,'pv_mt_active' => $mt_active,
					          	'status_pv_mt'=>'not']);


					      }else{
					      	//dd('อัพเดท');
					      	$update_mt = DB::table('customers') 
					      	->where('id',$customer_id)
					      	->update(['pv_mt' => $pv_mt_all,
					      		'pv_mt_active' => date('Y-m-t',strtotime($start_month)),
					      		'status_pv_mt'=>'not']);
					      }

					      $upline_type = $data_user->line_type;
					      $upline_id = $data_user->upline_id;
					      $customer_id = $upline_id;
					      $last_upline_type = $upline_type;

					  }else{
					       $promotion_mt = DB::table('dataset_mt_tv')//อัพ Pv ของตัวเอง
					       ->select('*')
					       ->where('code','=','pv_mt')
					       ->first();

					       $pro_mt = $promotion_mt->pv;
					       $pv_mt = $data_user->pv_mt;
					       $pv_mt_all = $pv+$pv_mt;

					       if($strtime_user > $strtime){

					       	// $contract_date = strtotime(date('Y-m',$strtime_user));

					       	// $caltime = strtotime("+1 Month",$contract_date);
					       	// $start_month = date("Y-m", $caltime);
					       	$start_month = date('Y-m',$strtime_user);

					       }else{
					       	$start_month = date("Y-m");

					       }

					       if($pv_mt_all >= $pro_mt){

				          //หักลบค่อยอัพเดท
					       	$mt_mount= $pv_mt_all/$pro_mt;
				          $mt_mount = floor($mt_mount);//จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
				          $pv_mt_total = $pv_mt_all-($mt_mount*$pro_mt);//ค่า pv ที่ต้องเอาไปอัพเดท DB

				          $strtime = strtotime($start_month);
				          $mt_active = strtotime("+$mt_mount Month",$strtime);
				          $mt_active = date('Y-m-t',$mt_active);//วันที่ mt_active 

				          $update_mt = DB::table('customers') 
				          ->where('id',$customer_id)
				          ->update(['pv_mt' => $pv_mt_total,'pv_mt_active' => $mt_active]);
				          //dd($mt_active);

				      }else{
				      	//dd('อัพเดท');
				      	$update_mt = DB::table('customers') 
				      	->where('id',$customer_id)
				      	->update(['pv_mt' => $pv_mt_all]);
				      }
				      $upline_type = $data_user->line_type;
				      $upline_id = $data_user->upline_id;
				      $customer_id = $upline_id;
				      $last_upline_type = $upline_type;

				  }


					}elseif($type_id == 3){//รักษาคุณสมบัติท่องเที่ยง

						  $data_user = DB::table('customers')//อัพ Pv ของตัวเอง
						  ->select('*')
						  ->where('id','=',$customer_id)
						  ->first();

						  $strtime_user = strtotime($data_user->pv_tv_active);
						  $strtime = strtotime(date("Y-m-d"));


						 $promotion_tv = DB::table('dataset_mt_tv')//อัพ Pv ของตัวเอง
						 ->select('*')
						 ->where('code','=','pv_tv')
						 ->first();

						 $pro_tv = $promotion_tv->pv;
						 $pv_tv = $data_user->pv_tv;
						 $pv_tv_all = $pv+$pv_tv;

						 if($strtime_user > $strtime){

						 	$start_month = date('Y-m',$strtime_user);

						 }else{
						 	$start_month = date("Y-m");

						 }



						 if($pv_tv_all >= $pro_tv){

				          //หักลบค่อยอัพเดท
						 	$tv_mount= $pv_tv_all/$pro_tv;
				          $tv_mount = floor($tv_mount);//จำนวนเต์มเดือนที่นำไปบวกเพิ่ม
				          $pv_tv_total = $pv_tv_all-($tv_mount*$pro_tv);//ค่า pv ที่ต้องเอาไปอัพเดท DB

				          $add_mount = $tv_mount-1;
				          $strtime = strtotime($start_month);
				          $tv_active = strtotime("+$add_mount Month",$strtime);
				          $tv_active = date('Y-m-t',$tv_active);//วันที่ tv_active

				          $update_mt = DB::table('customers') 
				          ->where('id',$customer_id)
				          ->update(['pv_tv' => $pv_tv_total,'pv_tv_active' => $tv_active]);
				          //dd($tv_active);

				      }else{
				      	//dd('อัพเดท');
				      	$update_mt = DB::table('customers') 
				      	->where('id',$customer_id)
				      	->update(['pv_tv' => $pv_tv_all]);
				      }
				      $upline_type = $data_user->line_type;
				      $upline_id = $data_user->upline_id;
				      $customer_id = $upline_id;
				      $last_upline_type = $upline_type;

					}elseif($type_id == 4){//เติม Aipocket

						$data_user = DB::table('customers')//อัพ Pv ของตัวเอง
						->select('*')
						->where('id','=',$customer_id)
						->first();
						//dd($data_user);

						$add_pv_aipocket = $data_user->pv_aipocket + $pv;

						$update_pv = DB::table('customers') 
						->where('id',$customer_id)
						->update(['pv_aipocket' => $add_pv_aipocket]);

						$update_ai_pocket = DB::table('ai_pocket') 
						->where('order_id',$order_id)
						->update(['status' => 'success','pv_aipocket' => $add_pv_aipocket]);

						$upline_type = $data_user->line_type;
						$upline_id = $data_user->upline_id;
						$customer_id = $upline_id;
						$last_upline_type = $upline_type;

					}elseif($type_id == 5){//Gift Voucher

					}elseif($type_id == 6){//couse อบรม

					}else{//ไม่เข้าเงื่อนไขได้เลย
						$resule = ['status'=>'fail','message'=>'ไม่มีเงื่อนไขที่ตรงตามความต้องการ'];
						return $resule;
					}
					if($customer_id != 'AA'){
						$j = 2;
						for ($i=1; $i <= $j ; $i++){ 

							$data_user = DB::table('customers')
							->where('id','=',$customer_id)
							->first();

							$upline_type = $data_user->line_type;
							$upline_id = $data_user->upline_id;

							if($upline_id == 'AA'){

								if($last_upline_type == 'A'){

									$add_pv = $data_user->pv_a + $pv;
									$update_pv = DB::table('customers') 
									->where('id',$customer_id)
									->update(['pv_a' => $add_pv]);

									$resule = ['status'=>'success','message'=>'Pv upline Type 1 Success'];
									$j = 0;

								}elseif($last_upline_type =='B'){
									$add_pv = $data_user->pv_b + $pv;
									$update_pv = DB::table('customers') 
									->where('id',$customer_id)
									->update(['pv_b' => $add_pv]);

									$resule = ['status'=>'success','message'=>'Pv upline Type 1 Success'];
									$j = 0;

								}elseif($last_upline_type == 'C'){
									$add_pv = $data_user->pv_c + $pv; 
									$update_pv = DB::table('customers') 
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
									$update_pv = DB::table('customers') 
									->where('id',$customer_id)
									->update(['pv_a' => $add_pv]);

									$customer_id = $upline_id;
									$last_upline_type = $upline_type;
									$j = $j+1;


								}elseif($last_upline_type =='B'){
									$add_pv = $data_user->pv_b + $pv;
									$update_pv = DB::table('customers') 
									->where('id',$customer_id)
									->update(['pv_b' => $add_pv]);

									$customer_id = $upline_id;
									$last_upline_type = $upline_type;
									$j = $j+1;

								}elseif($last_upline_type == 'C'){
									$add_pv = $data_user->pv_c + $pv; 
									$update_pv = DB::table('customers') 
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
						//DB::rollback();
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