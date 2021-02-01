<?php
namespace App\Models\Frontend;
use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
class MonthPv extends Model
{
	public static function get_month_pv($customer_id){
		$data_customer = DB::table('customers')
		->select('month_pv_a','month_pv_b','month_pv_c')
		->where('id','=',$customer_id)
		->first();

		if(empty($data_customer->month_pv_a)){
			$a = 0;
		}else{
			$a = $data_customer->month_pv_a;
		}

		if(empty($data_customer->month_pv_b)){
			$b = 0;
		}else{
			$b = $data_customer->month_pv_b;
		}

		if(empty($data_customer->month_pv_c)){
			$c = 0;
		}else{
			$c = $data_customer->month_pv_c;
		}

		$array_mount_pv = ['A'=>$a,'B'=>$b,'C'=>$c];
		asort($array_mount_pv);
 
		$i = 0;
		foreach($array_mount_pv as $key => $value) {
			$i++;
			if($i == 2){
				$data = ['type'=>$key,'pv_month'=>$value]; 
			}
			
		}

		$resule = array('data_all'=>$data_customer,'data_avg_pv'=>$data);
		return $resule;
	}

}


?> 