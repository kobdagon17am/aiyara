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

    public static function couse_register($order_id,$admin_id){

        $order_data = DB::table('orders')
        ->where('id','=',$order_id)
        ->first();

        if($order_data->type_id != '6'){
           $resule = ['status'=>'fail','message'=>'Type ID not course or event'];
           return $resule;
       }

       $order_items = DB::table('order_items')
       ->where('order_id','=',$order_id)
       ->where('status','=',null)
       ->orwhere('status','=','order')
       ->get();

       if(count($order_items) <= 0){ 
        $resule = ['status'=>'fail','message'=>'Items not course or event '];
        return $resule;
    }

    foreach ($order_items as $value) {
       try {

         DB::BeginTransaction();
        if($value->course_id == 1) {//course

            $last_code = DB::table('course_ticket_number')
            ->where('business_location_id','=',$order_data->business_location_id)
            ->where('ce_id_fk','=',$value->course_id)
            ->orderby('id','desc')
            ->first();

            if($last_code){
                $last_code = $last_code->ticket_number;
                $code = substr($last_code,-3);
                $last_code = $code + 1;

                $num_code = substr("000".$last_code, -3);
                $code_order = 'C'.$value->course_id.''.date('ymd').''.$num_code;

            }else{
                $last_code = 1;
                $maxId = substr("000".$last_code, -3);
                $code_order = 'C'.$value->course_id.''.date('ymd').''.$maxId;
            }

            $course_ticket_id = DB::table('course_ticket_number')->insertGetId([
                'ce_id_fk'=>$value->course_id,
                'ticket_number'=>$code_order,
                'business_location_id'=>$order_data->business_location_id
            ]);


        }elseif ($value->course_id == 2) {//event
            $last_code = DB::table('event_ticket_number')
            ->where('business_location_id','=',$order_data->business_location_id)
            ->where('ce_id_fk','=',$value->course_id)
            ->orderby('id','desc')
            ->first();

            if($last_code){
                $last_code = $last_code->ticket_number;
                $code = substr($last_code,-3);
                $last_code = $code + 1;

                $num_code = substr("000".$last_code, -3);
                $code_order = 'C'.$value->course_id.''.date('ymd').''.$num_code;

            }else{
                $last_code = 1;
                $maxId = substr("000".$last_code, -3);
                $code_order = 'C'.$value->course_id.''.date('ymd').''.$maxId;
            }

            $course_ticket_id = DB::table('course_ticket_number')->insertGetId([
                'ce_id_fk'=>$value->course_id,
                'ticket_number'=>$code_order,
                'business_location_id'=>$order_data->business_location_id
            ]);

        }else{
            DB::rollback();
            $resule = ['status'=>'fail','message'=>'Items not course or event'];
            return $resule;
        }


        $update_items = DB::table('order_items') 
        ->where('id',$value->id)
        ->update(['status' => 'success']);

        DB::table('course_event_regis')->insert([
            'ce_id_fk'=>$value->course_id,
            'customers_id_fk'=>$order_data->customer_id,
            'ticket_id'=>$course_ticket_id,
            'subject_recipient'=>1,
            'order_item_id'=>$value->course_id,
            'regis_date'=>date('Y-m-d'),
        ]);

        
        $resule = ['status'=>'success','message'=>'Register Course Success'];
        

    } catch (Exception $e) {
        DB::rollback();
        $resule = ['status'=>'fail','message'=>$e];
        return $resule;

    }
} 

if($resule['status'] == 'success'){
    DB::commit();
    return $resule;

}else{
    DB::rollback();
    return $resule;

}

}

}
