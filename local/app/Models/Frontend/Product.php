<?php
namespace App\Models\Frontend;

use App\Models\Frontend\ProductList;
use App\Models\Frontend\Promotion;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public static function product_list($type)
    {

        $data_type = DB::table('dataset_orders_type')
            ->where('lang_id', '=', 1)
            ->where('group_id', '=', $type)

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
            ->where('products.orders_type_id', 'LIKE', '%' . $type . '%')
            ->where('products_images.image_default', '=', 1)
            ->where('products_details.lang_id', '=', 1)
            ->where('products_cost.business_location_id', '=', 1)
            ->orderby('products.id')
            ->get();
        //->Paginate(4);
        //dd($product);

        $data = array(
            'type' => $data_type,
            'product' => $product);
        return $data;

    }

    public static function product_list_select($c_id, $type)
    {
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
            ->where('products.orders_type_id', 'LIKE', '%' . $type . '%')
            ->whereRaw(('case WHEN ' . $c_id . ' != 1 THEN products.category_id = ' . $c_id . ' else products.category_id != ' . $c_id . ' END'))
        // ->where('products.category_id', '=',$c_id)
            ->where('products_images.image_default', '=', 1)
            ->where('products_details.lang_id', '=', 1)
            ->where('products_cost.business_location_id', '=', 1)
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

    public static function product_list_select_promotion($type, $customer_username)
    { //เช่นทำคุณสมบัติ (dataset_orders_type->id),customer_username

        $data_customers = DB::table('customers')
            ->where('user_name', '=', $customer_username)
            ->first();

        if (empty($data_customers)) {
            $resule = ['status' => 'fail', 'message' => 'Customers is Null'];
            return $resule;
        }

        $business_location_id = $data_customers->business_location_id;

        $promotions = DB::table('promotions')
            ->select('promotions.*', 'promotions_images.img_url', 'promotions_images.promotion_img', 'promotions_cost.selling_price', 'promotions_cost.pv')
            ->leftjoin('promotions_images', 'promotions_images.promotion_id_fk', '=', 'promotions.id')
            ->leftjoin('promotions_cost', 'promotions_cost.promotion_id_fk', '=', 'promotions.id')
            ->where('promotions_images.image_default', '=', 1)
            ->where('promotions.orders_type_id', 'LIKE', '%' . $type . '%')
            ->where('promotions.business_location', '=', $business_location_id)
            ->where('promotions_cost.business_location_id', '=', $business_location_id)
            ->where('promotions_cost.status', '=', 1)
            ->wheredate('promotions.show_startdate', '<=', date('Y-m-d'))
            ->wheredate('promotions.show_enddate', '>=', date('Y-m-d'))
            ->where('promotions.status', '=', 1)
            ->orderby('id', 'DESC')
            ->get();



        if (count($promotions) <= 0) {
            $resule = ['status' => 'fail', 'message' => 'ตอนนี้ไม่มี Promotions'];

        } else {

            $customer_id = $data_customers->id;
            $package_id = $data_customers->package_id;
            $qualification_id = $data_customers->qualification_id;
            $aistockist_status = $data_customers->aistockist_status;
            $agency_status = $data_customers->agency_status;

            $mt_active = \App\Helpers\Frontend::check_mt_active($customer_id);
            $tv_active = \App\Helpers\Frontend::check_tv_active($customer_id);

            $html = '';

            foreach ($promotions as $value) {

                $check_all_available_purchase = Promotion::all_available_purchase($value->id);
                $count_per_promotion = Promotion::count_per_promotion($value->id,$customer_id);
                $count_per_promotion_day = Promotion::count_per_promotion_day($value->id,$customer_id);

                //1 เช็คก่อนว่าใช้โปรโมชั่นเต็มหรือยัง
                if ($value->all_available_purchase <= $check_all_available_purchase['all_available_purchase'] and $value->all_available_purchase != "" and $value->all_available_purchase != 0) {
                    $resule = ['status' => 'fail', 'message' => 'การสั่งซื้อของโปรโมชั่นนี้ครบ '.$value->all_available_purchase.' ชิ้นแล้ว'];
                } elseif ($package_id < $value->minimum_package_purchased and !empty($value->minimum_package_purchased)) {
                    $resule = ['status' => 'fail', 'message' => 'ไม่ผ่าน Package ขั้นต่ำที่ซื้อได้'];

                } elseif ($qualification_id < $value->reward_qualify_purchased and !empty($value->reward_qualify_purchased)) {

                    $resule = ['status' => 'fail', 'message' => 'ไม่ผ่านคุณวุฒิ Reward ขั้นต่ำที่ซื้อได้', 'code' => 'e003'];

                } elseif ($value->keep_personal_quality == 1 and !empty($value->keep_personal_quality) and $mt_active['status'] == 'fail') { ///ต้องรักษาคุณสมบัตรรายเดือน
                    $resule = ['status' => 'fail', 'message' => $mt_active['message']];
                } elseif ($value->keep_personal_quality == 1 and !empty($value->keep_personal_quality) and $mt_active['type'] == 'N') { //ต้องรักษาคุณสมบัติรายเดือน
                    $resule = ['status' => 'fail', 'message' => 'ต้องมีการรักษาคุณสมบัติรายเดือน'];

                } elseif ($value->keep_personal_quality == 0 and !empty($value->keep_personal_quality) and $mt_active['status'] == 'fail') { //ไม่่ต้องรักษาคุณสมบัติรายเดือน
                    $resule = ['status' => 'fail', 'message' => $mt_active['message']];

                } elseif ($value->keep_personal_quality == 0 and !empty($value->keep_personal_quality) and $mt_active['type'] == 'Y') { //ไม่่ต้องรักษาคุณสมบัติรายเดือน
                    $resule = ['status' => 'fail', 'message' => 'ต้องไม่มีการรักษาคุณสมบัติรายเดือน'];

                } elseif ($value->maintain_travel_feature == 1 and !empty($value->maintain_travel_feature) and $mt_active['status'] == 'fail') {
                    ///ต้องมีการรักษาคุณสมบัติท่องเที่ยว
                    $resule = ['status' => 'fail', 'message' => $tv_active['message']];

                } elseif ($value->maintain_travel_feature == 1 and !empty($value->maintain_travel_feature) and $tv_active['type'] == 'N') {

                    $resule = ['status' => 'fail', 'message' => 'ต้องมีการรักษาคุณสมบัติท่องเที่ยว', 'code' => 'e009'];

                } elseif ($value->maintain_travel_feature == 0 and !empty($value->maintain_travel_feature) and $tv_active['status'] == 'fail') {
                    ///ต้องมีการรักษาคุณสมบัติท่องเที่ยว
                    $resule = ['status' => 'fail', 'message' => $tv_active['message']];

                } elseif ($value->maintain_travel_feature == 0 and !empty($value->maintain_travel_feature) and $tv_active['type'] == 'Y') {
                    $resule = ['status' => 'fail', 'message' => 'ต้องไม่มีการรักษาคุณสมบัติท่องเที่ยว', 'code' => 'e011'];

                } elseif ($value->aistockist == 1 and !empty($value->aistockist) and $aistockist_status == 0) { //เป็น aistockist
                    $resule = ['status' => 'fail', 'message' => 'ต้องเป็น Ai-Stockist '];

                } elseif ($value->aistockist == 0 and !empty($value->aistockist) and $aistockist_status == 1) { //ต้องไม่เป็น aistockist

                    $resule = ['status' => 'fail', 'message' => 'ต้องไม่เป็น Ai-Stockist'];

                } elseif ($value->agency == 1 and !empty($value->agency) and $agency_status == 0) { //เป็น aistockist
                    $resule = ['status' => 'fail', 'message' => 'ต้องเป็น Agency'];

                } elseif ($value->agency == 0 and !empty($value->agency) and $agency_status == 1) { //ต้องไม่เป็น aistockist
                    $resule = ['status' => 'fail', 'message' => 'ต้องไม่เป็น Agency'];

                } elseif ($value->limited_amt_type == 1) {
                    //เฉพาะต่อรอบโปรโมชั่น(1),ต่อวันภายในรอบโปรโมชั่น(2),(null)ไม่จำกัด,ไม่จำกัด(3)
                    if ($value->limited_amt_person <= $count_per_promotion['count']) {
                        $resule = ['status' => 'fail', 'message' => 'การซื้อเฉพาะต่อรอบโปรโมชั่นครบ '.$value->limited_amt_person.' ชิ้นแล้ว'];
                    } else {
                        $resule = ['status' => 'success', 'message' => 'สามารถซื้อได้'];

                        $html .= ProductList::product_list_html($value->id, $type, $value->img_url, $value->promotion_img, $value->name_thai, $value->detail_thai, $icon = '', $value->selling_price, $value->pv, $category_id);
                    }

                } elseif ($value->limited_amt_type == 2) {
                    //เฉพาะต่อรอบโปรโมชั่น(1),ต่อวันภายในรอบโปรโมชั่น(2),(null)ไม่จำกัด,ไม่จำกัด(3)

                    if ($value->limited_amt_person <= $count_per_promotion_day['count']) {
                        $resule = ['status' => 'fail', 'message' => 'การซื้อโปรโมชั่นต่อวันครบ '.$value->limited_amt_person.' ชิ้นแล้ว'];
                    } else {
                        $resule = ['status' => 'success', 'message' => 'สามารถซื้อได้'];
                        $html .= ProductList::product_list_html($value->id, $type, $value->img_url, $value->promotion_img, $value->name_thai, $value->detail_thai, $icon = '', $value->selling_price, $value->pv, 8);
                    }

                } else {
                    $resule = ['status' => 'success', 'message' => 'สามารถซื้อได้'];

                    $html .= ProductList::product_list_html($value->id, $type, $value->img_url, $value->promotion_img, $value->name_thai, $value->detail_thai, $icon = '', $value->selling_price, $value->pv, 8);
                }

            }


            $data = ['html' => $html, 'rs' => $resule];
            return $data;
        }
    }

    public static function product_list_select_promotion_all($type, $customer_username)
    { //เช่นทำคุณสมบัติ (dataset_orders_type->id),customer_username


        $arr = array();
        $data_customers = DB::table('customers')
            ->where('user_name', '=', $customer_username)
            ->first();

        if (empty($data_customers)) {
            $resule = ['status' => 'fail', 'message' => 'Customers is Null'];
            return $resule;
        }

        $business_location_id = $data_customers->business_location_id;

        $promotions = DB::table('promotions')
            ->select('promotions.*', 'promotions_images.img_url', 'promotions_images.promotion_img', 'promotions_cost.selling_price', 'promotions_cost.pv')
            ->leftjoin('promotions_images', 'promotions_images.promotion_id_fk', '=', 'promotions.id')
            ->leftjoin('promotions_cost', 'promotions_cost.promotion_id_fk', '=', 'promotions.id')
            ->where('promotions_images.image_default', '=', 1)
            ->where('promotions.orders_type_id', 'LIKE', '%' . $type . '%')
            ->where('promotions.business_location', '=', $business_location_id)
            ->where('promotions_cost.business_location_id', '=', $business_location_id)
            ->where('promotions_cost.status', '=', 1)
            ->wheredate('promotions.show_startdate', '<=', date('Y-m-d'))
            ->wheredate('promotions.show_enddate', '>=', date('Y-m-d'))
            ->where('promotions.status', '=', 1)
            ->orderby('id', 'DESC')
            ->get();

        if (count($promotions) <= 0) {
          $arr[] = ['status' => 'fail', 'message' => 'ตอนนี้ไม่มี Promotions'];

        } else {

            $customer_id = $data_customers->id;
            $package_id = $data_customers->package_id;
            $qualification_id = $data_customers->qualification_id;
            $aistockist_status = $data_customers->aistockist_status;
            $agency_status = $data_customers->agency_status;

            $mt_active = \App\Helpers\Frontend::check_mt_active($customer_id);
            $tv_active = \App\Helpers\Frontend::check_tv_active($customer_id);

            $html = '';

            foreach ($promotions as $value) {

                $check_all_available_purchase = Promotion::all_available_purchase($value->id);
                $count_per_promotion = Promotion::count_per_promotion($value->id,$customer_id);
                $count_per_promotion_day = Promotion::count_per_promotion_day($value->id,$customer_id);

                //1 เช็คก่อนว่าใช้โปรโมชั่นเต็มหรือยัง
                if ($value->all_available_purchase <= $check_all_available_purchase['all_available_purchase'] and $value->all_available_purchase != "" and $value->all_available_purchase != 0) {
                    $arr[] = ['status' => 'fail', 'message' => 'การสั่งซื้อของโปรโมชั่นนี้ครบ '.$value->all_available_purchase.' ชิ้นการแล้ว'];
                }

                if ($package_id < $value->minimum_package_purchased and !empty($value->minimum_package_purchased)) {
                    $arr[] = ['status' => 'fail', 'message' => 'ไม่ผ่าน Package ขั้นต่ำที่ซื้อได้'];

                }

                if ($qualification_id < $value->reward_qualify_purchased and !empty($value->reward_qualify_purchased)) {

                    $arr[] = ['status' => 'fail', 'message' => 'ไม่ผ่านคุณวุฒิ Reward ขั้นต่ำที่ซื้อได้', 'code' => 'e003'];
                }

                if ($value->keep_personal_quality == 1 and !empty($value->keep_personal_quality) and $mt_active['status'] == 'fail') { ///ต้องรักษาคุณสมบัตรรายเดือน
                    $arr[] = ['status' => 'fail', 'message' => $mt_active['message']];
                }

                if ($value->keep_personal_quality == 1 and !empty($value->keep_personal_quality) and $mt_active['type'] == 'N') { //ต้องรักษาคุณสมบัติรายเดือน
                    $arr[] = ['status' => 'fail', 'message' => 'ต้องมีการรักษาคุณสมบัติรายเดือน'];

                }

                if ($value->keep_personal_quality == 0 and !empty($value->keep_personal_quality) and $mt_active['status'] == 'fail') { //ไม่่ต้องรักษาคุณสมบัติรายเดือน
                    $arr[] = ['status' => 'fail', 'message' => $mt_active['message']];

                }

                if ($value->keep_personal_quality == 0 and !empty($value->keep_personal_quality) and $mt_active['type'] == 'Y') { //ไม่่ต้องรักษาคุณสมบัติรายเดือน
                    $arr[] = ['status' => 'fail', 'message' => 'ต้องไม่มีการรักษาคุณสมบัติรายเดือน'];

                }

                if ($value->maintain_travel_feature == 1 and !empty($value->maintain_travel_feature) and $mt_active['status'] == 'fail') {
                    ///ต้องมีการรักษาคุณสมบัติท่องเที่ยว
                    $arr[] = ['status' => 'fail', 'message' => $tv_active['message']];

                }

                if ($value->maintain_travel_feature == 1 and !empty($value->maintain_travel_feature) and $tv_active['type'] == 'N') {

                    $arr[] = ['status' => 'fail', 'message' => 'ต้องมีการรักษาคุณสมบัติท่องเที่ยว', 'code' => 'e009'];
                }

                if ($value->maintain_travel_feature == 0 and !empty($value->maintain_travel_feature) and $tv_active['status'] == 'fail') {
                    ///ต้องมีการรักษาคุณสมบัติท่องเที่ยว
                    $arr[] = ['status' => 'fail', 'message' => $tv_active['message']];
                }

                if ($value->maintain_travel_feature == 0 and !empty($value->maintain_travel_feature) and $tv_active['type'] == 'Y') {
                    $arr[] = ['status' => 'fail', 'message' => 'ต้องไม่มีการรักษาคุณสมบัติท่องเที่ยว', 'code' => 'e011'];

                }

                if ($value->aistockist == 1 and !empty($value->aistockist) and $aistockist_status == 0) { //เป็น aistockist
                    $arr[] = ['status' => 'fail', 'message' => 'ต้องเป็น Ai-Stockist '];

                }

                if ($value->aistockist == 0 and !empty($value->aistockist) and $aistockist_status == 1) { //ต้องไม่เป็น aistockist

                    $arr[] = ['status' => 'fail', 'message' => 'ต้องไม่เป็น Ai-Stockist'];

                }

                if ($value->agency == 1 and !empty($value->agency) and $agency_status == 0) { //เป็น aistockist
                    $arr[] = ['status' => 'fail', 'message' => 'ต้องเป็น Agency'];

                }

                if ($value->agency == 0 and !empty($value->agency) and $agency_status == 1) { //ต้องไม่เป็น aistockist
                    $arr[] = ['status' => 'fail', 'message' => 'ต้องไม่เป็น Agency'];

                }

                if ($value->limited_amt_type == 1) {
                    //เฉพาะต่อรอบโปรโมชั่น(1),ต่อวันภายในรอบโปรโมชั่น(2),(null)ไม่จำกัด,ไม่จำกัด(3)
                    if ($value->limited_amt_person <= $count_per_promotion['count']) {
                        $arr[] =  ['status' => 'fail', 'message' => 'การซื้อเฉพาะต่อรอบโปรโมชั่นครบ '.$value->limited_amt_person.' ชิ้นแล้ว'];
                    }
                }

                if ($value->limited_amt_type == 2) {
                    //เฉพาะต่อรอบโปรโมชั่น(1),ต่อวันภายในรอบโปรโมชั่น(2),(null)ไม่จำกัด,ไม่จำกัด(3)

                    if ($value->limited_amt_person <= $count_per_promotion_day['count']) {
                        $arr[] = ['status' => 'fail', 'message' => 'การซื้อโปรโมชั่นต่อวันครบ '.$value->limited_amt_person.' ชิ้นแล้ว'];
                    }

                }

            }

            if($arr){
              $data = ['status'=>'fail','rs'=>$arr];
            }else{
              $data = ['status'=>'success','rs'=>'สามารถสั่งซื้อ Promotion ได้ '];
            }

            return $data;

        }

    }

    public static function product_list_coupon($promotion_id, $type, $category_id,$coupon_code)
    { //$promotion_id,$types

        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        if(empty($business_location_id)){
          $business_location_id = 1;
        }
        $promotions = DB::table('promotions')
            ->select('promotions.*', 'promotions_images.img_url', 'promotions_images.promotion_img', 'promotions_cost.selling_price', 'promotions_cost.pv')
            ->leftjoin('promotions_images', 'promotions_images.promotion_id_fk', '=', 'promotions.id')
            ->leftjoin('promotions_cost', 'promotions_cost.promotion_id_fk', '=', 'promotions.id')
            ->where('promotions_images.image_default', '=', 1)
            ->where('promotions.orders_type_id','LIKE','%'.$type.'%')
            ->where('promotions.business_location', '=', $business_location_id)
            ->where('promotions_cost.business_location_id', '=', $business_location_id)
            // ->where('promotions.promotion_coupon_status', '=', 0)// 0 ทั่วไป 1 ผู้มีคูปองเท่านั้น
            // ->where('promotions.status', '=', 0)// 0 ใช้ได้
            ->where('promotions.id', '=', $promotion_id)
            ->orderby('id', 'DESC')
            ->first();

            //dd($promotions);

            if($promotions){
              $html = ProductList::product_list_html($promotions->id, $type, $promotions->img_url, $promotions->promotion_img, $promotions->name_thai,
              $promotions->detail_thai, $icon = '', $promotions->selling_price, $promotions->pv, $category_id,$coupon_code);

              $resule = ['status'=>'success','message'=>'สามารถซื้อได้','html'=>$html];

            }else{
              //สินค้าไม่มีรูปภาพ
              $resule = ['status'=>'fail','message'=>'ข้อมูลรายการสินค้าไม่พร้อมใช้งาน'];

            }
        return  $resule;
    }

}
