<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
class CourseCheckRegis extends Model
{
    public static function check_register($course_id,$customer_username){
        $data_ce = DB::table('course_event')
        ->where('id',$course_id)
        ->first();


        $customer = DB::table('customers')
        ->where('user_name',$customer_username)
        ->first();

        if(empty($customer)){
          $resule = ['status'=>'fail','message'=>'ไม่มีข้อมูล Coustomer is null'];
          return $resule;
        }

        //dd($data_ce->aistockist);

        $customer_id =  $customer->id;
        $package_id =  $customer->package_id;
        $qualification_id =  $customer->qualification_id;
        $aistockist_status =  $customer->aistockist_status;


        //1 เช็คบัตรก่อนว่าเต็มไหม
        $count_ce = \App\Helpers\Frontend::get_ce_register($course_id);//[บัตรทั้งหมด]

        $ce_register_per_customer_perday = \App\Helpers\Frontend::get_ce_register_per_customer_perday($course_id,$customer_id);//จำนวนที่จองคอสนี้ ต่อวัน
        $ce_register_per_customer_course = \App\Helpers\Frontend::get_ce_register_per_customer_percourse($course_id,$customer_id);//จำนวนที่จองคอสนี้ต่อ Course
        $mt_active = \App\Helpers\Frontend::check_mt_active($customer_id);
        $tv_active = \App\Helpers\Frontend::check_tv_active($customer_id);


        if($count_ce >=  $data_ce->ce_max_ticket){
            $resule = ['status'=>'fail','message'=>'Ticket Full','code'=>'e001'];
            return $resule;exit();
        }elseif($package_id < $data_ce->minimum_package_purchased and !empty($data_ce->minimum_package_purchased)){//Package ขั้นต่ำที่ซื้อได้ :(DB:dataset_package) package_id
            $resule = ['status'=>'fail','message'=>'ไม่ผ่าน Package ขั้นต่ำที่ซื้อได้','code'=>'e002'];
            return $resule;exit();
        }elseif($qualification_id < $data_ce->reward_qualify_purchased and !empty($data_ce->reward_qualify_purchased)){
            $resule = ['status'=>'fail','message'=>'ไม่ผ่านคุณวุฒิ Reward ขั้นต่ำที่ซื้อได้','code'=>'e003'];
            return $resule;exit();

        }elseif($data_ce->keep_personal_quality == 1 and !empty($data_ce->keep_personal_quality) and $mt_active['status'] == 'fail'){
            ///ต้องรักษาคุณสมบัตรรายเดือน
         $resule = ['status'=>'fail','message'=>$mt_active['message'],'code'=>'e004'];
         return $resule;exit();

     }elseif($data_ce->keep_personal_quality == 1 and !empty($data_ce->keep_personal_quality) and $mt_active['type']=='N'){

        $resule = ['status'=>'fail','message'=>'ต้องรักษาคุณสมบัติรายเดือน','code'=>'e005'];
        return $resule;

    }elseif($data_ce->keep_personal_quality == 2 and !empty($data_ce->keep_personal_quality) and $mt_active['status'] == 'fail'){
                ///ไม่ต้องรักษาคุณสมบัตรรายเดือน
        $resule = ['status'=>'fail','message'=>$mt_active['message'],'code'=>'e006'];
        return $resule;exit();

    }elseif($data_ce->keep_personal_quality == 2 and !empty($data_ce->keep_personal_quality) and $mt_active['type']=='Y'){
        $resule = ['status'=>'fail','message'=>'ต้องไม่รักษาคุณสมบัติรายเดือน','code'=>'e007'];
        return $resule;exit();


    }elseif($data_ce->maintain_travel_feature == 1 and !empty($data_ce->maintain_travel_feature) and $mt_active['status'] == 'fail'){
                ///ต้องรักษาคุณสมบัตรรายเดือน
        $resule = ['status'=>'fail','message'=>$tv_active['message'],'code'=>'e008'];
        return $resule;exit();

    }elseif($data_ce->maintain_travel_feature == 1 and !empty($data_ce->maintain_travel_feature) and $tv_active['type']=='N'){

        $resule = ['status'=>'fail','message'=>'ต้องรักษาคุณสมบัติท่องเที่ยว','code'=>'e009'];
        return $resule;exit();

    }elseif($data_ce->maintain_travel_feature == 2 and !empty($data_ce->maintain_travel_feature) and $tv_active['status'] == 'fail'){
                            ///ไม่ต้องรักษาคุณสมบัตรรายเดือน
        $resule = ['status'=>'fail','message'=>$tv_active['message'],'code'=>'e010'];
        return $resule;exit();

    }elseif($data_ce->maintain_travel_feature == 2 and !empty($data_ce->maintain_travel_feature) and $tv_active['type']=='Y'){
        $resule = ['status'=>'fail','message'=>'ต้องไม่รักษาคุณสมบัติท่องเที่ยว','code'=>'e011'];
        return $resule;exit();

    }elseif($data_ce->aistockist == 1 and !empty($data_ce->aistockist) and ($aistockist_status == 0 || $aistockist_status == null)){//เป็น aistockist

        $resule = ['status'=>'fail','message'=>'ต้องเป็น  Ai-Stockist','code'=>'e012'];
        return $resule;exit();

    }elseif($data_ce->aistockist == 2 and !empty($data_ce->aistockist) and $aistockist_status == 1){//ต้องไม่เป็น aistockist

        $resule = ['status'=>'fail','message'=>'ต้องไม่เป็น Ai-Stockist','code'=>'e013'];
        return $resule;exit();

    }elseif(!empty($data_ce->ce_can_reserve) and $data_ce->ce_can_reserve != 6 ){

        //จำนวนคนที่สามารถจองได้ต่อกิจกรรม (1)ต่อวัน (2)ต่อกิจกรรม (null)ไม่จำกัด
            if($data_ce->ce_limit == '1' and $ce_register_per_customer_perday >= $data_ce->ce_can_reserve){//ต่อวัน
                $resule = ['status'=>'fail','message'=>'การสมัครต่อวันครบแล้ว','code'=>'e01'];
                return $resule;exit();
            }elseif($data_ce->ce_limit == '2' and $ce_register_per_customer_course >= $data_ce->ce_can_reserve){//ต่อกิจกรรม
                $resule = ['status'=>'fail','message'=>'การสมัครต่อกิจกรรมครบแล้ว','code'=>'e02'];
                return $resule;exit();

            }elseif($data_ce->ce_limit == Null and $ce_register_per_customer_course >= $data_ce->ce_can_reserve){//ต่อกิจกรรม
                $resule = ['status'=>'fail','message'=>'คุณใช้สิทธิ์การจองครบแล้ว','code'=>'e03'];
                return $resule;exit();
            }else{

                $resule = ['status'=>'success','message'=>'Register open','code'=>'0'];
                return $resule;exit();
            }

        }else{


            $resule = ['status'=>'success','message'=>'Register open','code'=>'0'];
            return $resule;exit();
        }

    }

    public static function cart_check_register($course_id,$quantity,$customer_username){

        $data_ce = DB::table('course_event')
        ->where('id',$course_id)
        ->first();

        //dd($data_ce->aistockist);

        $customer = DB::table('customers')
        ->where('user_name',$customer_username)
        ->first();

        if(empty($customer)){
          $resule = ['status'=>'fail','message'=>'ไม่มีข้อมูล Coustomer is null'];
          return $resule;
        }

        //dd($data_ce->aistockist);

        $customer_id =  $customer->id;
        $package_id = $customer->package_id;
        $qualification_id = $customer->qualification_id;
        $aistockist_id = $customer->aistockist_id;
        //dd($data_ce);

        //1 เช็คบัตรก่อนว่าเต็มไหม
        $count_ce = \App\Helpers\Frontend::get_ce_register($course_id);//[บัตรทั้งหมด]
        $ce_register_per_customer_perday = \App\Helpers\Frontend::get_ce_register_per_customer_perday($course_id,$customer_id);//จำนวนที่จองคอสนี้ ต่อวัน
        $ce_register_per_customer_perday = $ce_register_per_customer_perday + $quantity;


        $ce_register_per_customer_course = \App\Helpers\Frontend::get_ce_register_per_customer_percourse($course_id,$customer_id);//จำนวนที่จองคอสนี้ต่อ Course
        $ce_register_per_customer_course = $ce_register_per_customer_course + $quantity;
        $mt_active = \App\Helpers\Frontend::check_mt_active($customer_id);
        $tv_active = \App\Helpers\Frontend::check_tv_active($customer_id);


         if(!empty($data_ce->ce_can_reserve) and $data_ce->ce_can_reserve != 6 ){

        //จำนวนคนที่สามารถจองได้ต่อกิจกรรม (1)ต่อวัน (2)ต่อกิจกรรม (null)ไม่จำกัด
            if($data_ce->ce_limit == '1' and $ce_register_per_customer_perday > $data_ce->ce_can_reserve){//ต่อวัน
                $resule = ['status'=>'fail','message'=>'สิทธิ์การสมัครต่อวันเกินกำหนด','code'=>'e01'];
                return $resule;exit();
            }elseif($data_ce->ce_limit == '2' and $ce_register_per_customer_course > $data_ce->ce_can_reserve){//ต่อกิจกรรม
                $resule = ['status'=>'fail','message'=>'สิทธิ์การสมัครต่อกิจกรรมเกินกำหนด','code'=>'e02'];
                return $resule;exit();

            }elseif($data_ce->ce_limit == Null and $ce_register_per_customer_course > $data_ce->ce_can_reserve){//ต่อกิจกรรม
                $resule = ['status'=>'fail','message'=>'สิทธิ์การจองเกินกำหนด','code'=>'e03'];
                return $resule;exit();
            }else{

                $resule = ['status'=>'success','message'=>'Register open','code'=>'0'];
                return $resule;exit();
            }

        }else{


            $resule = ['status'=>'success','message'=>'Register open','code'=>'0'];
            return $resule;exit();
        }

    }
}
