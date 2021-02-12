<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;
use App\Models\Frontend\Product;
use App\Models\Frontend\ProductList;
use App\Models\Frontend\Couse_Event;
use Auth;
class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }

    public function product_list($type){

       $categories = DB::table('categories')
       ->where('lang_id', '=', 1)
       ->where('status', '=',1)
       ->orderby('order')
       ->get();

       if($type == '6'){
           $data = Couse_Event::couse_event($type);
           return view('frontend/product/product-list-course',compact('data','type'));

       }else{

        $coupon =  DB::table('db_promotion_cus')
        ->select('db_promotion_code.id','db_promotion_code.pro_sdate','db_promotion_code.pro_edate','db_promotion_cus.promotion_code','promotions.name_thai','promotions.name_eng','promotions.name_laos','promotions.name_burma','promotions.name_cambodia','db_promotion_cus.pro_status') 
        ->leftjoin('db_promotion_code', 'db_promotion_code.id', '=', 'db_promotion_cus.promotion_code_id_fk')
        ->leftjoin('promotions', 'promotions.id', '=', 'db_promotion_code.promotion_id_fk')
        ->where('db_promotion_code.approve_status','=',1)
        ->where('db_promotion_cus.customer_id_fk','=',Auth::guard('c_user')->user()->id)
        ->where('db_promotion_cus.pro_status','=',1)
        ->orderby('db_promotion_cus.pro_status','ASC')
        ->get(); 

        $data = Product::product_list($type);
    }

    return view('frontend/product/product-list',compact('data','type','coupon','categories'));
}

public function product_list_select(Request $request){
    $type = $request->type;
    if(empty($request->category_id)){
        $c_id = 1;
    }else{
        $c_id = $request->category_id;

        if($c_id == 8 ){
            $html = Product::product_list_select_promotion($c_id,$type);
            return $html;
        }else{
           $data = Product::product_list_select($c_id,$type);

       }
   }

   $html='';
   if($data['product']){
    foreach ($data['product'] as $value){

        $html .= ProductList::product_list_html($value->products_id,$type,$value->img_url,$value->product_img,$value->product_name,$value->title,$value->icon,$value->member_price,$value->pv,$c_id);


    }

}else{
    $html ='';
}

return $html;
}



public function product_detail($type,$id,$category_id='')
{

    if (empty($id) || empty($type)) {
        return redirect('product-list/1')->withError('No Product');
    }else{
        if($type == 6){

            $couse_event = DB::table('course_event')
            ->select('course_event.*')
            //->where('course_event_images.img_default', '=', 1) 
            ->where('course_event.id','=',$id)
            ->orderby('course_event.ce_edate')
            ->first();

            $img = DB::table('course_event_images')
            ->select('*')
            ->where('course_event_id_fk','=',$id)
            ->get();

            $data = ['product_data'=>$couse_event,'img'=>$img,'type'=>$type,'category_id'=>$category_id];

            return view('frontend/product/product-detail', compact('data'));

        }else{
            if($category_id == 8 || $category_id == 9){//โปรโมชั่น || cpupon

                $product = DB::table('promotions')
                ->select('promotions.id as products_id','name_thai as product_name',
                    'selling_price as member_price','promotions_cost.pv','title_thai as descriptions'
                    ,'detail_thai as products_details')
                   // ->leftjoin('promotions_images','promotions_images.promotion_id_fk','=','promotions.id')
                ->leftjoin('promotions_cost','promotions_cost.promotion_id_fk','=','promotions.id')
                  // ->where('promotions_images.image_default','=',1) 
                    // ->where('promotions.orders_type_id','LIKE','%'.$type.'%')
                   // ->where('promotions.business_location','=',$business_location_id)
                   // ->where('promotions_cost.business_location_id','=',$business_location_id)
                   // ->where('promotions_cost.status','=',1) 
                   // ->wheredate('promotions.show_startdate','<=',date('Y-m-d'))
                   // ->wheredate('promotions.show_enddate','>=',date('Y-m-d'))
                   // ->where('promotions.status','=',1)
                   // ->orderby('id','DESC')
                ->where('promotions.id','=',$id)
                ->first();

                if(empty($product)){
                    return redirect('product-list/'.$type)->withError('No Product');
                }else{
                    $img = DB::table('promotions_images')
                    ->select('promotion_img as product_img','img_url' )
                    ->where('promotion_id_fk', '=',$id)
                    ->orderby('image_default','DESC')
                    ->get();
                }

                $data = ['product_data'=>$product,'img'=>$img,'type'=>$type,'category_id'=>$category_id];

            }else{
             $product = DB::table('products')
             ->select(
                'products.id as products_id',
                'products_details.*',
                'products_cost.*',
                'dataset_currency.*')
             ->leftjoin('products_details', 'products.id', '=', 'products_details.product_id_fk')
                  // ->leftjoin('products_images', 'products.id', '=', 'products_images.product_id_fk')
             ->leftjoin('products_cost', 'products.id', '=', 'products_cost.product_id_fk')
             ->leftjoin('dataset_currency', 'dataset_currency.id', '=', 'products_cost.currency_id')
             ->where('products.id', '=',$id)
                  // ->where('products_images.image_default', '=', 1)
             ->where('products_details.lang_id', '=', 1)
             ->where('products_cost.business_location_id','=', 1)
             ->first();

             if(empty($product)){
                return redirect('product-list/'.$type)->withError('No Product');
            }else{
                $img = DB::table('products_images')
                ->where('product_id_fk', '=',$id)
                ->orderby('image_default','DESC')
                ->get();
            }

            $data = ['product_data'=>$product,'img'=>$img,'type'=>$type,'category_id'=>$category_id];
        }




        return view('frontend/product/product-detail', compact('data'));

    }
}
}



public function add_cart(Request $request){

     if($request->category_id == 8){//promotion
        $id ='P'.$request->id;


        $location_id = Auth::guard('c_user')->user()->business_location_id;
        $data = \App\Helpers\Frontend::get_promotion_detail($request->id,$location_id);

        
    }else{
        $id = $request->id;
        $data = ''; 
    }

    Cart::session($request->type)->add(array(
            'id' => $id, // inique row ID
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'attributes' => array(
                'pv'=>$request->pv,
                'img'=>$request->img,
                'promotion'=>$request->promotion,
                'promotion_id'=>$request->id, 
                'promotion_detail'=>$data,
                'category_id'=>$request->category_id,
            )
        ));
    $getTotalQuantity = Cart::session($request->type)->getTotalQuantity();

        // $item = Cart::session($request->type)->getContent();
    return $getTotalQuantity; 

}
}
