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
		->select('customers.*','dataset_package.dt_package','dataset_qualification.code_name','q_max.code_name as max_code_name',DB::raw(' DATE_ADD(customers.created_at,INTERVAL + 60 DAY) as end_date'))
		->leftjoin('dataset_package','dataset_package.id','=','customers.package_id')
		->leftjoin('dataset_qualification', 'dataset_qualification.id', '=','customers.qualification_id')
		->leftjoin('dataset_qualification as q_max', 'q_max.id', '=','customers.qualification_max_id')
		->where('customers.introduce_id','=',$user_name)
    	//->whereRaw('DATE_ADD(customers.created_at, INTERVAL +60 DAY) >= NOW()')
		->get();

		return $sponser;
	}

}

?>
