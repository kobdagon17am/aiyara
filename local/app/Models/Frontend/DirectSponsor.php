<?php
namespace App\Models\Frontend;
use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
class DirectSponsor extends Model
{
	public static function sponsor_get_directsponsor($user_name){

		$sponser =  DB::table('customers')
		->select('customers.id','customers.created_at','customers.pv','customers.first_name','customers.last_name','customers.user_name', 'customers.introduce_id', 'customers.upline_id',
    'customers.pv_mt_active', 'customers.introduce_type', 'customers.business_name','dataset_package.dt_package','dataset_qualification.code_name')
		->leftjoin('dataset_package','dataset_package.id','=','customers.package_id')
		->leftjoin('dataset_qualification', 'dataset_qualification.id', '=','customers.qualification_id')
		->where('customers.introduce_id','=',$user_name)
    	//->whereRaw('DATE_ADD(customers.created_at, INTERVAL +60 DAY) >= NOW()')
		->get();

		return $sponser;
	}

}

?>
