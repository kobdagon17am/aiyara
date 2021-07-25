<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
use App\Models\Frontend\Promotion;
use App\Models\Frontend\ProductList;

class Product extends Model
{
	public static function product_list($type){

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
            'type'=>$data_type,
            'product' => $product);
        return $data;

    }

    public static function product_list_select($c_id,$type){
        // $ev_objective = DB::table('categories')
  //       ->where('lang_id', '=', 1)
  //       ->orderby('order')
  //       ->get();



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
            'product' => $product);
        return $data;

    }

    public static function product_list_select_promotion($category_id,$type){//$promotion_id,$types
     $business_location_id = Auth::guard('c_user')->user()->business_location_id;
     $promotions = DB::table('promotions')
     ->select('promotions.*','promotions_images.img_url','promotions_images.promotion_img','promotions_cost.selling_price','promotions_cost.pv')
     ->leftjoin('promotions_images','promotions_images.promotion_id_fk','=','promotions.id')
     ->leftjoin('promotions_cost','promotions_cost.promotion_id_fk','=','promotions.id')
     ->where('promotions_images.image_default','=',1)
     ->where('promotions.orders_type_id','LIKE','%'.$type.'%')
     ->where('promotions.business_location','=',$business_location_id)
     ->where('promotions_cost.business_location_id','=',$business_location_id)
     ->where('promotions_cost.status','=',1)
     ->wheredate('promotions.show_startdate','<=',date('Y-m-d'))
     ->wheredate('promotions.show_enddate','>=',date('Y-m-d'))
     ->where('promotions.status','=',1)
     ->orderby('id','DESC')
     ->get();

       //dd($promotions);

     if(count($promotions) <= 0 ){
         $resule = ['status'=>'fail','message'=>'NUll Product'];
     }else{

        $customer_id = Auth::guard('c_user')->user()->id;
        $package_id = Auth::guard('c_user')->user()->package_id;
        $qualification_id = Auth::guard('c_user')->user()->qualification_id;
        $aistockist_status = Auth::guard('c_user')->user()->aistockist_status;
        $agency_status = Auth::guard('c_user')->user()->agency_status;

        $mt_active = \App\Helpers\Frontend::check_mt_active($customer_id);
        $tv_active = \App\Helpers\Frontend::check_tv_active($customer_id);

        $html='';

        foreach ($promotions as $value){

            $check_all_available_purchase = Promotion::all_available_purchase($value->id);
            $count_per_promotion = Promotion::count_per_promotion($value->id);
            $count_per_promotion_day = Promotion::count_per_promotion_day($value->id);


             //1 เช็คก่อนว่าใช้โปรโมชั่นเต็มหรือยัง
            if($value->all_available_purchase <= $check_all_available_purchase['all_available_purchase']){
                $resule = ['status'=>'fail','message'=>'การสั่งซื้อครบแล้ว'];
            }elseif($package_id < $value->minimum_package_purchased and !empty($data_ce->minimum_package_purchased)) {
                $resule = ['status'=>'fail','message'=>'ไม่ผ่าน Package ขั้นต่ำที่ซื้อได้'];

            }elseif($qualification_id < $value->reward_qualify_purchased and !empty($data_ce->reward_qualify_purchased)) {

                $resule = ['status'=>'fail','message'=>'ไม่ผ่านคุณวุฒิ Reward ขั้นต่ำที่ซื้อได้','code'=>'e003'];

            }elseif($value->keep_personal_quality == 1 and !empty($value->keep_personal_quality) and $mt_active['status'] == 'fail'){///ต้องรักษาคุณสมบัตรรายเดือน
                $resule = ['status'=>'fail','message'=>$mt_active['message']];
            }elseif($value->keep_personal_quality == 1 and !empty($value->keep_personal_quality) and $mt_active['type'] == 'N'){//ต้องรักษาคุณสมบัติรายเดือน
                $resule = ['status'=>'fail','message'=>'ต้องมีการรักษาคุณสมบัติรายเดือน'];

            }elseif ($value->keep_personal_quality == 0 and !empty($value->keep_personal_quality) and $mt_active['status'] == 'fail') {//ไม่่ต้องรักษาคุณสมบัติรายเดือน
                $resule = ['status'=>'fail','message'=>$mt_active['message']];

            }elseif ($value->keep_personal_quality == 0 and !empty($value->keep_personal_quality) and $mt_active['type'] == 'Y') {//ไม่่ต้องรักษาคุณสมบัติรายเดือน
                $resule = ['status'=>'fail','message'=>'ต้องไม่มีการรักษาคุณสมบัติรายเดือน'];

            }elseif($value->maintain_travel_feature == 1 and !empty($value->maintain_travel_feature) and $mt_active['status'] == 'fail'){
                ///ต้องมีการรักษาคุณสมบัติท่องเที่ยว
               $resule = ['status'=>'fail','message'=>$tv_active['message']];


           }elseif($value->maintain_travel_feature == 1 and !empty($value->maintain_travel_feature) and $tv_active['type']=='N'){

            $resule = ['status'=>'fail','message'=>'ต้องมีการรักษาคุณสมบัติท่องเที่ยว','code'=>'e009'];


        }elseif($value->maintain_travel_feature == 0 and !empty($value->maintain_travel_feature) and $tv_active['status'] == 'fail'){
                            ///ต้องมีการรักษาคุณสมบัติท่องเที่ยว
           $resule = ['status'=>'fail','message'=>$tv_active['message']];

       }elseif($value->maintain_travel_feature == 0 and !empty($value->maintain_travel_feature) and $tv_active['type']=='Y'){
        $resule = ['status'=>'fail','message'=>'ต้องไม่มีการรักษาคุณสมบัติท่องเที่ยว','code'=>'e011'];

            }elseif($value->aistockist == 1 and !empty($value->aistockist) and $aistockist_status == 0 ){//เป็น aistockist
                $resule = ['status'=>'fail','message'=>'ต้องเป็น Ai-Stockist '];

            }elseif($value->aistockist == 0 and !empty($value->aistockist) and $aistockist_status == 1){//ต้องไม่เป็น aistockist

                $resule = ['status'=>'fail','message'=>'ต้องไม่เป็น Ai-Stockist'];

            }elseif($value->agency == 1 and !empty($value->agency) and $agency_status == 0 ){//เป็น aistockist
                $resule = ['status'=>'fail','message'=>'ต้องเป็น Agency'];

            }elseif($value->agency == 0 and !empty($value->agency) and $agency_status == 1){//ต้องไม่เป็น aistockist
                $resule = ['status'=>'fail','message'=>'ต้องไม่เป็น Agency'];

            }elseif($value->limited_amt_type == 1 ){
        //เฉพาะต่อรอบโปรโมชั่น(1),ต่อวันภายในรอบโปรโมชั่น(2),(null)ไม่จำกัด,ไม่จำกัด(3)
                if($value->limited_amt_person <= $count_per_promotion['count']){
                    $resule = ['status'=>'fail','message'=>'การซื้อเฉพาะต่อรอบโปรโมชั่นครบแล้ว'];
                }else{
                    $resule = ['status'=>'success','message'=>'สามารถซื้อได้'];

                    $html .= ProductList::product_list_html($value->id,$type,$value->img_url,$value->promotion_img,$value->name_thai,$value->detail_thai,$icon='',$value->selling_price,$value->pv,$category_id);
                }

            }elseif($value->limited_amt_type == 2 ){
                //เฉพาะต่อรอบโปรโมชั่น(1),ต่อวันภายในรอบโปรโมชั่น(2),(null)ไม่จำกัด,ไม่จำกัด(3)

                if($value->limited_amt_person <= $count_per_promotion_day['count']){
                    $resule = ['status'=>'fail','message'=>'การซื้อโปรโมชั่นต่อวันครบแล้ว'];
                }else{
                    $resule = ['status'=>'success','message'=>'สามารถซื้อได้'];
                    $html .= ProductList::product_list_html($value->id,$type,$value->img_url,$value->promotion_img,$value->name_thai,$value->detail_thai,$icon='',$value->selling_price,$value->pv,$category_id);
                }

            }else{
                $resule = ['status'=>'success','message'=>'สามารถซื้อได้'];

                $html .= ProductList::product_list_html($value->id,$type,$value->img_url,$value->promotion_img,$value->name_thai,$value->detail_thai,$icon='',$value->selling_price,$value->pv,$category_id);
            }


        }

        return $html;
    }
}


 public static function product_list_coupon($promotion_id,$type,$category_id){//$promotion_id,$types
     $business_location_id = Auth::guard('c_user')->user()->business_location_id;
     $promotions = DB::table('promotions')
     ->select('promotions.*','promotions_images.img_url','promotions_images.promotion_img','promotions_cost.selling_price','promotions_cost.pv')
     ->leftjoin('promotions_images','promotions_images.promotion_id_fk','=','promotions.id')
     ->leftjoin('promotions_cost','promotions_cost.promotion_id_fk','=','promotions.id')
     ->where('promotions_images.image_default','=',1)
     // ->where('promotions.orders_type_id','LIKE','%'.$type.'%')
     ->where('promotions.business_location','=',$business_location_id)
     ->where('promotions_cost.business_location_id','=',$business_location_id)
     ->where('promotions.promotion_coupon_status','=',1)
     ->where('promotions.id','=',$promotion_id)
     ->orderby('id','DESC')
     ->first();

     //$resule = ['status'=>'success','message'=>'สามารถซื้อได้'];
     $html = ProductList::product_list_html($promotions->id,$type,$promotions->img_url,$promotions->promotion_img,$promotions->name_thai,$promotions->detail_thai,$icon='',$promotions->selling_price,$promotions->pv,$category_id);
     return $html;
 }

}
