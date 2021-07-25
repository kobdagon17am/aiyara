<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
class Location extends Model
{
	public static function location($zon,$lang_id){
        //te
        $data = DB::table('dataset_business_location')
        ->select('*')
        ->leftjoin('branchs', 'dataset_business_location.id', '=', 'branchs.business_location_id_fk')
        ->where('branchs.lang_id', '=',$lang_id)
        ->where('dataset_business_location.id', '=',$zon)
        ->get();

        return $data;

    }
}
