<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
class Couse_Event extends Model
{
	public static function couse_event($type){

        $couse_event = DB::table('course_event')
        ->select('course_event.*','course_event_images.img_url','course_event_images.img_name')
        ->leftjoin('course_event_images', 'course_event_images.course_event_id_fk', '=','course_event.id') 
        // ->leftjoin('products_images', 'products.id', '=', 'products_images.product_id_fk')
        // ->leftjoin('products_cost', 'products.id', '=', 'products_cost.product_id_fk')
        // ->leftjoin('dataset_currency', 'dataset_currency.id', '=', 'products_cost.currency_id')
        ->where('course_event_images.img_default', '=', 1) 
        // ->where('products_cost.business_location_id','=', 1)
        ->orderby('course_event.ce_edate')
        ->get();

        $data_type = DB::table('dataset_orders_type')
        ->where('lang_id', '=', 1)
        ->where('group_id', '=',$type)
        //->orderby('order')
        ->first();    

        $data = array(
            'couse_event' => $couse_event,
            'type'=> $data_type
        );
        return $data;
    }
}
