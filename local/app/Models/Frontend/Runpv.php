<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
class Runpv extends Model
{
	public static function run_pv($type,$pv,$username){
		if(!empty($type) || !empty($pv) || !empty($username) ){
			try {
				DB::BeginTransaction();

				$id = Auth::guard('c_user')->user()->id;

				$user = DB::table('customers')
				->where('id','=',$id)
				->limit(1)
				->get();

				if(empty($user)){
					$resule = ['status'=>'fail','message'=>'ไม่มี User นี้ในระบบ']; 
				}else{
					$pv_total = $user[0]->pv_aipocket - $pv;
					if($pv_total < 0){
						$resule = ['status'=>'fail','message'=>'ค่า Pv ไม่พอสำหรับการใช้งาน']; 
					}else{
						$update_pv = DB::table('customers')//update PV
						->where('id',$id)
						->update(['pv_aipocket' => $pv_total ]);

						//run slot
						if($type == 1){//ทำคุณสมบติ

						$to_customer_id = DB::table('customers')
						->where('user_name','=',$username)
						->first();

						DB::table('ai_pocket')
						->insert(['customer_id'=>$id,'to_customer_id'=>$to_customer_id->id,'pv'=>$pv,'status'=>'success','type_id'=>$type,'detail'=>'Sent Ai-Pocket','banlance'=>$pv_total]);

						$customer_user = DB::table('Customers')//อัพ Pv ของตัวเอง
						->select('*')
						->where('user_name','=',$username)
						->first();
				//dd($customer_user);

						$add_pv = $customer_user->pv + $pv;
						$update_pv = DB::table('Customers') 
						->where('id',$customer_user->id)
						->update(['pv' => $add_pv]);

				//ทำคุณสมบัติตัวเอง
						$upline_type = $customer_user->line_type;
						$upline_id = $customer_user->upline_id;
						$customer_id = $upline_id;
						$last_upline_type = $upline_type;

					}elseif ($type == 2) {//รักษาคุณสมบัติรายเดือน
						# code...
					}elseif($type == 3){//รักษาคุณสมบัติท่องเที่ยง

					}else{
						$resule = ['status'=>'fail','message'=>'ไม่มี Type ที่เลือก']; 

					}

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

				}

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

		$resule = ['status'=>'success','message'=>'Yess']; 

	}else{
		$resule = ['status'=>'fail','message'=>'Data in Null']; 
	}

	return $resule;

}

}
