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



}
?>