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
        ->where('course_event_images.img_default', '=', 1)
        ->whereDate('course_event.ce_edate', '>=',date('Y-m-d'))
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

    public static function couse_register($order_id,$admin_id=''){

        $order_data = DB::table('db_orders')
        ->where('id','=',$order_id)
        ->first();

        if($order_data->purchase_type_id_fk != '6'){
         $resule = ['status'=>'fail','message'=>'Type ID not course or event'];
         return $resule;
     }

     $order_items = DB::table('db_order_products_list')
     ->select('db_order_products_list.*','dataset_ce_type.txt_desc','dataset_ce_type.id as type_ce_id')
     ->leftjoin('course_event', 'course_event.id', '=','db_order_products_list.course_id_fk')
     ->leftjoin('dataset_ce_type', 'course_event.ce_type','=','dataset_ce_type.id')
     ->where('db_order_products_list.frontstore_id_fk','=',$order_id)
     // ->where('order_items.status','=',null)
     // ->orwhere('order_items.status','=','order')
     ->get();


     if(count($order_items) <= 0){
        $resule = ['status'=>'fail','message'=>'Items not course or event || Items Empty'];
        return $resule;
    }
    try {
       DB::BeginTransaction();
       foreach ($order_items as $value) {

        if($value->type_ce_id == 1) {//course

            $last_code = DB::table('course_ticket_number')
            ->where('business_location_id','=',$order_data->business_location_id_fk)
            ->where('ce_id_fk','=',$value->course_id_fk)
            ->orderby('id','desc')
            ->first();

            if($last_code){
                $last_code = $last_code->ticket_number;
                $code = substr($last_code,-3);
                $last_code = $code + 1;

                $num_code = substr("000".$last_code, -3);
                $code_order = 'C'.$value->course_id_fk.''.date('ymd').''.$num_code;

            }else{
                $last_code = 1;
                $maxId = substr("000".$last_code, -3);
                $code_order = 'C'.$value->course_id_fk.''.date('ymd').''.$maxId;
            }

            $course_ticket_id = DB::table('course_ticket_number')->insertGetId([
                'ce_id_fk'=>$value->course_id_fk,
                'ticket_number'=>$code_order,
                'business_location_id'=>$order_data->business_location_id_fk
            ]);


        }elseif ($value->type_ce_id == 2) {//event
            $last_code = DB::table('course_ticket_number')
            ->where('business_location_id','=',$order_data->business_location_id_fk)
            ->where('ce_id_fk','=',$value->course_id_fk)
            ->orderby('id','desc')
            ->first();

            if($last_code){
                $last_code = $last_code->ticket_number;
                $code = substr($last_code,-3);
                $last_code = $code + 1;

                $num_code = substr("000".$last_code, -3);
                $code_order = 'E'.$value->course_id_fk.''.date('ymd').''.$num_code;

            }else{
                $last_code = 1;
                $maxId = substr("000".$last_code, -3);
                $code_order = 'E'.$value->course_id_fk.''.date('ymd').''.$maxId;
            }

            $course_ticket_id = DB::table('course_ticket_number')->insertGetId([
                'ce_id_fk'=>$value->course_id_fk,
                'ticket_number'=>$code_order,
                'business_location_id'=>$order_data->business_location_id_fk
            ]);

        }else{

            DB::rollback();
            $resule = ['status'=>'fail','message'=>'Items not course or event'];
            return $resule;
        }


        $update_items = DB::table('db_order_products_list')
        ->where('id',$value->id)
        ->update(['approve_status' => '1']);

        DB::table('course_event_regis')->insert([

            'ce_id_fk'=>$value->course_id_fk,
            'customers_id_fk'=>$order_data->customers_id_fk,
            'ticket_id'=>$course_ticket_id,
            'subject_recipient'=>1,
            'order_id_fk'=>$order_id,
            'order_item_id'=>$value->id,
            'regis_date'=>date('Y-m-d'),
        ]);

        $resule = ['status'=>'success','message'=>'Register Course Success'];
    }

    if($resule['status'] == 'success'){
        DB::commit();
        return $resule;

    }else{
        DB::rollback();
        return $resule;

    }

} catch (Exception $e) {
 $resule = ['status'=>'fail','message'=> $e];
 return $resule;
}


}

}
