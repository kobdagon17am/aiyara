<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
class Product extends Model
{
	public static function product_list($type){ 
		// $ev_objective = DB::table('categories')
  //       ->where('lang_id', '=', 1)
  //       ->orderby('order')
  //       ->get();

        $categories = DB::table('categories')
        ->where('lang_id', '=', 1)
        ->orderby('order')
        ->get();

        $data_type = DB::table('dataset_orders_type')
        ->where('lang_id', '=', 1)
        ->where('group_id', '=',$type)
        //->orderby('order')
        ->first();


        $product = DB::table('products')
        ->select(
            'products.id as products_id',
            'products_details.*',
            'products_images.*',
            'products_cost.*',
            'dataset_currency.*',
        )
        ->leftjoin('products_details', 'products.id', '=', 'products_details.product_id_fk')
        ->leftjoin('products_images', 'products.id', '=', 'products_images.product_id_fk')
        ->leftjoin('products_cost', 'products.id', '=', 'products_cost.product_id_fk')
        ->leftjoin('dataset_currency', 'dataset_currency.id', '=', 'products_cost.currency_id')
        ->where('products.orders_type_id','LIKE','%'.$type.'%')
        ->where('products_images.image_default', '=', 1)
        ->where('products_details.lang_id', '=', 1)
        ->where('products_cost.business_location_id','=', 1)
        ->orderby('products.id')
        ->get();
        //->Paginate(4);
        //dd($product);

        $data = array(
            'category' => $categories,
            'type'=>$data_type,
            'product' => $product);
        return $data;

    }

    public static function product_list_select($c_id,$type){
        // $ev_objective = DB::table('categories')
  //       ->where('lang_id', '=', 1)
  //       ->orderby('order')
  //       ->get();

        $categories = DB::table('categories')
        ->where('lang_id', '=', 1)
        ->orderby('order')
        ->get();

//DB::enableQueryLog();
        $product = DB::table('products')
        ->select(
            'products.id as products_id',
            'products_details.*',
            'products_images.*',
            'products_cost.*',
            'dataset_currency.*',
        )
        ->leftjoin('products_details', 'products.id', '=', 'products_details.product_id_fk')
        ->leftjoin('products_images', 'products.id', '=', 'products_images.product_id_fk')
        ->leftjoin('products_cost', 'products.id', '=', 'products_cost.product_id_fk')
        ->leftjoin('dataset_currency', 'dataset_currency.id', '=', 'products_cost.currency_id')
        ->where('products.orders_type_id','LIKE','%'.$type.'%')
        ->whereRaw(('case WHEN '.$c_id.' != 1 THEN products.category_id = '.$c_id.' else products.category_id != '.$c_id.' END'))
        // ->where('products.category_id', '=',$c_id)
        ->where('products_images.image_default', '=', 1)
        ->where('products_details.lang_id', '=', 1)
        ->where('products_cost.business_location_id','=', 1)
        ->orderby('products.id')
        ->get();
        //->Paginate(4);
        //dd($product);
        //$query = DB::getQueryLog();
        //dd($query);

        $data = array(
            'category' => $categories,
            'product' => $product);
        return $data;

    }

    public static function product_list_select_promotion(){

         $promotions = DB::table('promotions')
        ->select('promotions.*','promotions_images.img_url','promotions_images.promotion_img')
        ->leftjoin('promotions_images','promotions_images.id','=','promotions.id')
        ->leftjoin('promotions_cost','promotions_cost.promotion_id_fk','=','promotions.id')
        ->where('promotions_images.image_default','=',1)
        ->where('promotions.business_location','=',Auth::guard('c_user')->user()->business_location_id)
        ->wheredate('promotions.show_startdate','<=',date('Y-m-d'))
        ->wheredate('promotions.show_enddate','>=',date('Y-m-d'))
        ->where('promotions.status','=',1)
        ->orderby('id','DESC')
        ->get();

        $customer_id = Auth::guard('c_user')->user()->id;
        $package_id = Auth::guard('c_user')->user()->package_id;
        $qualification_id = Auth::guard('c_user')->user()->qualification_id;
        $aistockist_id = Auth::guard('c_user')->user()->aistockist_id;

        $data = array();
        foreach ($promotions as $value){
            all_available_purchase($promotions->id)

        }

        //dd($data_ce);
        //1 เช็คบัตรก่อนว่าเต็มไหม
        //$check_all_available_purchase = \App\Helpers\Frontend::check_all_available_purchase($id);//[โปรโมชั่นนี้ทั้งหมด]
         
        // $ce_register_per_customer_perday = \App\Helpers\Frontend::get_ce_register_per_customer_perday($course_id,$customer_id);//จำนวนที่จองคอสนี้ ต่อวัน
        // $ce_register_per_customer_course = \App\Helpers\Frontend::get_ce_register_per_customer_percourse($course_id,$customer_id);//จำนวนที่จองคอสนี้ต่อ Course
        // $mt_active = \App\Helpers\Frontend::check_mt_active($customer_id);
        // $tv_active = \App\Helpers\Frontend::check_tv_active($customer_id);


 
        if($package_id < $data_ce->minimum_package_purchased and !empty($data_ce->minimum_package_purchased)){//Package ขั้นต่ำที่ซื้อได้ :(DB:dataset_package) package_id 
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
        $resule = ['status'=>'fail','message'=>$mt_active['message'],'code'=>'e010'];
        return $resule;exit();

    }elseif($data_ce->maintain_travel_feature == 2 and !empty($data_ce->maintain_travel_feature) and $tv_active['type']=='Y'){
        $resule = ['status'=>'fail','message'=>'ต้องไม่รักษาคุณสมบัติท่องเที่ยว','code'=>'e011'];
        return $resule;exit();

    }elseif($data_ce->aistockist == 1 and !empty($data_ce->aistockist) and ($aistockist_id == 2 || $aistockist_id == null)){//เป็น aistockist

        $resule = ['status'=>'fail','message'=>'ต้องเป็น  Ai-Stockist','code'=>'e012'];
        return $resule;exit();

    }elseif($data_ce->aistockist == 2 and !empty($data_ce->aistockist) and $aistockist_id == 1){//ต้องไม่เป็น aistockist  

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

}
