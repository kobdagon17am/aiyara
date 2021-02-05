<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;
use App\Models\Frontend\Product;
use App\Models\Frontend\Couse_Event;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }

    public function product_list($type){
        if($type == '6'){
           $data = Couse_Event::couse_event($type);
           return view('frontend/product/product-list-course',compact('data','type'));

       }else{
        $data = Product::product_list($type);
    }

    return view('frontend/product/product-list',compact('data','type'));
}

public function product_list_select(Request $request){
    $type = $request->type;
    if(empty($request->category_id)){
        $c_id = 1;
    }else{
        $c_id = $request->category_id;

        if($c_id == 8 ){
            $data = Product::product_list_select_promotion($c_id,$type);

        }else{
           $data = Product::product_list_select($c_id,$type);

       }
   }

   
   $html='';
   if($data['product']){
    foreach ($data['product'] as $value){
        $html .= '<div class="col-xl-3 col-md-3 col-sm-6 col-xs-6" >
        <input type="hidden" id="item_id" value="'.$value->products_id.'">
        <div class="card prod-view">
        <div class="prod-item text-center">
        <div class="prod-img">
        <div class="option-hover">
        <a href="'.route("product-detail",['type'=>$type,'id'=>$value->products_id]).'" type="button" 
        class="btn btn-success btn-icon waves-effect waves-light m-r-15 hvr-bounce-in option-icon"> <i class="icofont icofont-cart-alt f-20"></i></a>
        <a href="'.route("product-detail",['type'=>$type,'id'=>$value->products_id]).'"
        class="btn btn-primary btn-icon waves-effect waves-light m-r-15 hvr-bounce-in option-icon">
        <i class="icofont icofont-eye-alt f-20"></i>
        </a>
        <!-- <button type="button" class="btn btn-danger btn-icon waves-effect waves-light hvr-bounce-in option-icon">
        <i class="icofont icofont-heart-alt f-20"></i>
        </button> -->
        </div>
        <a href="#!" class="hvr-shrink">
        <img src="'.asset($value->img_url.''.$value->product_img).'" class="img-fluid o-hidden" alt="">
        </a>
        <!-- <div class="p-new"><a href=""> New </a></div> -->
        </div>
        <div class="prod-info">
        <a href="'.route('product-detail',['type'=>$type,'id'=>$value->products_id]).'" class="txt-muted">
        <h5 style="font-size: 15px">'.$value->product_name.'</h5>
        <p class="text-left p-2 m-b-0" style="font-size: 12px">'.$value->title.'</p>
        </a>
        <!--<div class="m-b-10">
        <label class="label label-success">3.5 <i class="fa fa-star"></i></label><a class="text-muted f-w-600">14 Ratings &amp;  3 Reviews</a>
        </div> -->
        <span class="prod-price" style="font-size: 20px">'.$value->icon.' '.number_format($value->member_price,2).'<b
        style="color:#00c454">['.$value->pv.' PV]</b></span>
        </div>
        </div>
        </div>
        </div>';
    }

}else{
    $html ='';
}
return $html;
}



public function product_detail($type,$id)
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

            $data = ['data'=>$couse_event,'img'=>$img,'type'=>$type];

            return view('frontend/product/product-detail', compact('couse_event','img','type'));

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

        return view('frontend/product/product-detail', compact('product','img','type'));

    }
}
}



public function add_cart(Request $request){
     //dd($request->all());
    Cart::session($request->type)->add(array(
            'id' => $request->id, // inique row ID
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'attributes' => array(
                'pv'=>$request->pv,
                'img'=>$request->img,
                'promotion'=>$request->promotion
            )
        ));
    $getTotalQuantity = Cart::session($request->type)->getTotalQuantity();

        // $item = Cart::session($request->type)->getContent();
    return $getTotalQuantity; 

}
}
