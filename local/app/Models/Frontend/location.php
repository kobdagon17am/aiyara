<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
class Location extends Model
{
	public static function location($zon,$lang_id){

        $data = DB::table('dataset_business_location')
        ->select('*')
        ->leftjoin('dataset_business_major', 'dataset_business_location.id', '=', 'dataset_business_major.location_id')
        ->where('dataset_business_major.lang_id', '=',$lang_id)
        ->where('dataset_business_location.id', '=',$zon)
        ->orderby('order')
        ->get();

        return $data;

    }
}
