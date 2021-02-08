<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;
use App\Models\Frontend\Product;
use App\Models\Frontend\ProductList;
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
            $html = Product::product_list_select_promotion($c_id,$type);
            return $html;
        }else{
           $data = Product::product_list_select($c_id,$type);

       }
   }

   $html='';
   if($data['product']){
    foreach ($data['product'] as $value){

        $html .= ProductList::product_list_html($value->products_id,$type,$value->img_url,$value->product_img,$value->product_name,$value->title,$value->icon,$value->member_price,$value->pv);
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
