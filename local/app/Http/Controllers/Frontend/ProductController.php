<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;
use App\Models\Frontend\Product;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer');
    }

    public function product_list_type_1(Request $request)

    {
        if(empty($request->c_id)){
            $c_id = 2;
        }else{
            $c_id = $request->c_id;
        }

        $data = Product::product_list($c_id);
        return view('frontend/product/product-list-1', $data);
    }

    public function product_list_type_2(Request $request)

    {
        if(empty($request->c_id)){
            $c_id = 2;
        }else{
            $c_id = $request->c_id;
        }

        $data = Product::product_list($c_id);
        return view('frontend/product/product-list-2', $data);
    }

    public function product_list_type_3(Request $request)

    {
        if(empty($request->c_id)){
            $c_id = 3;
        }else{
            $c_id = $request->c_id;
        }

        $data = Product::product_list($c_id);
        return view('frontend/product/product-list-3', $data);
    }

    public function product_list_type_4(Request $request)

    {
        if(empty($request->c_id)){
            $c_id = 4;
        }else{
            $c_id = $request->c_id;
        }

        $data = Product::product_list($c_id);
        return view('frontend/product/product-list-4', $data);
    }

    public function product_list_type_5(Request $request)

    {
        if(empty($request->c_id)){
            $c_id = 5;
        }else{
            $c_id = $request->c_id;
        }

        $data = Product::product_list($c_id);
        return view('frontend/product/product-list-5', $data);
    }

    public function product_list_type_6(Request $request)
    {
        if(empty($request->c_id)){
            $c_id = 6;
        }else{
            $c_id = $request->c_id;
        }

        $data = Product::product_list($c_id);
        return view('frontend/product/product-list-6', $data);
    }

    public function product_list_select(Request $request)
    {
        $type = $request->type;

        if(empty($request->category_id)){
            $c_id = 1;
        }else{
            $c_id = $request->category_id;
        }

        $data = Product::product_list($c_id);
        $html='';
        if($data['product']){
            foreach ($data['product'] as $value) {
                if($value->image_default == 1){
                    $img = '<img src="'.asset($value->img_url.''.$value->image01).'" class="img-fluid o-hidden" alt="">';
                }elseif($value->image_default == 2){
                    $img =  '<img src="'.asset($value->img_url.''.$value->image02).'" class="img-fluid o-hidden" alt="">';
                }elseif($value->image_default == 3){
                    $img = '<img src="'.asset($value->img_url.''.$value->image03).'" class="img-fluid o-hidden" alt="">';
                }else{
                    $img = '<img src="'.asset($value->img_url.''.$value->image01).'" class="img-fluid o-hidden" alt="">';
                }

                $html .= '<div class="col-xl-3 col-md-3 col-sm-6 col-xs-6" >
                <input type="hidden" id="item_id" value="'.$value->id.'">
                <div class="card prod-view">
                <div class="prod-item text-center">
                <div class="prod-img">
                <div class="option-hover">
                <a href="'.route("product-detail-".$type,["id"=>$value->id]).'" type="button" 
                class="btn btn-success btn-icon waves-effect waves-light m-r-15 hvr-bounce-in option-icon"> <i class="icofont icofont-cart-alt f-20"></i></a>
                <a href="'.route("product-detail-".$type,["id"=>$value->id]).'"
                class="btn btn-primary btn-icon waves-effect waves-light m-r-15 hvr-bounce-in option-icon">
                <i class="icofont icofont-eye-alt f-20"></i>
                </a>
                <!-- <button type="button" class="btn btn-danger btn-icon waves-effect waves-light hvr-bounce-in option-icon">
                <i class="icofont icofont-heart-alt f-20"></i>
                </button> -->
                </div>
                <a href="#!" class="hvr-shrink">
                '.$img.'
                </a>
                <!-- <div class="p-new"><a href=""> New </a></div> -->
                </div>
                <div class="prod-info">
                <a href="'.route('product-detail-'.$type,['id'=>$value->id]).'" class="txt-muted">
                <h5 style="font-size: 15px">'.$value->product_name.'</h5>
                <p style="margin-top: 0px;">'.$value->title.'</p>
                </a>
                <!--           <div class="m-b-10">
                <label class="label label-success">3.5 <i class="fa fa-star"></i></label><a class="text-muted f-w-600">14 Ratings &amp;  3 Reviews</a>
                </div> -->
                <span class="prod-price"><i
                class="icofont icofont-cur-dollar"></i>฿'.number_format($value->price,2).'<b
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



    public function product_detail_1($id)
    {
        if (empty($id)) {
            return redirect('product-list');
        } else {
            $product = DB::table('products')
            ->select(
                'products.*',
                'product_detail.product_name',
                'product_detail.title',
                'product_detail.product_detail'
            )
                //->select('*')
            ->leftjoin('product_detail', 'products.id', '=', 'product_detail.product_id')
            ->where('products.category_id', '=', 1)
            ->where('product_detail.lang_id', '=', 1)
            ->where('products.id', '=', $id)
            ->orderby('products.id')
            ->first();


            return view('frontend/product/product-detail-1', compact('product'));
        }
    }

    public function product_detail_2($id)
    {
        if (empty($id)) {
            return redirect('product-list');
        } else {
            $product = DB::table('products')
            ->select(
                'products.*',
                'product_detail.product_name',
                'product_detail.title',
                'product_detail.product_detail'
            )
                //->select('*')
            ->leftjoin('product_detail', 'products.id', '=', 'product_detail.product_id')
            // ->where('products.category_id', '=', 1)
            ->where('product_detail.lang_id', '=', 1)
            ->where('products.id', '=', $id)
            ->orderby('products.id')
            ->first();


            return view('frontend/product/product-detail-2', compact('product'));
        }
    }

    public function product_detail_3($id)
    {
        if (empty($id)) {
            return redirect('product-list');
        } else {
            $product = DB::table('products')
            ->select(
                'products.*',
                'product_detail.product_name',
                'product_detail.title',
                'product_detail.product_detail'
            )
                //->select('*')
            ->leftjoin('product_detail', 'products.id', '=', 'product_detail.product_id')
            // ->where('products.category_id', '=', 1)
            ->where('product_detail.lang_id', '=', 1)
            ->where('products.id', '=', $id)
            ->orderby('products.id')
            ->first();


            return view('frontend/product/product-detail-3', compact('product'));
        }
    }

    public function product_detail_4($id)
    {
        if (empty($id)) {
            return redirect('product-list');
        } else {
            $product = DB::table('products')
            ->select(
                'products.*',
                'product_detail.product_name',
                'product_detail.title',
                'product_detail.product_detail'
            )
                //->select('*')
            ->leftjoin('product_detail', 'products.id', '=', 'product_detail.product_id')
            // ->where('products.category_id', '=', 1)
            ->where('product_detail.lang_id', '=', 1)
            ->where('products.id', '=', $id)
            ->orderby('products.id')
            ->first();


            return view('frontend/product/product-detail-4', compact('product'));
        }
    }
    public function product_detail_5($id)
    {
        if (empty($id)) {
            return redirect('product-list');
        } else {
            $product = DB::table('products')
            ->select(
                'products.*',
                'product_detail.product_name',
                'product_detail.title',
                'product_detail.product_detail'
            )
                //->select('*')
            ->leftjoin('product_detail', 'products.id', '=', 'product_detail.product_id')
            // ->where('products.category_id', '=', 1)
            ->where('product_detail.lang_id', '=', 1)
            ->where('products.id', '=', $id)
            ->orderby('products.id')
            ->first();


            return view('frontend/product/product-detail-5', compact('product'));
        }
    }
    public function product_detail_6($id)
    {
        if (empty($id)) {
            return redirect('product-list');
        } else {
            $product = DB::table('products')
            ->select(
                'products.*',
                'product_detail.product_name',
                'product_detail.title',
                'product_detail.product_detail'
            )
                //->select('*')
            ->leftjoin('product_detail', 'products.id', '=', 'product_detail.product_id')
            // ->where('products.category_id', '=', 1)
            ->where('product_detail.lang_id', '=', 1)
            ->where('products.id', '=', $id)
            ->orderby('products.id')
            ->first();


            return view('frontend/product/product-detail-6', compact('product'));
        }
    }

    public function add_cart(Request $request){
     // dd($request->type);
        Cart::session($request->type)->add(array(
            'id' => $request->id, // inique row ID
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'attributes' => array(
                'pv'=>$request->pv,
                'img'=>$request->img,
                'title'=>$request->title,
                'promotion'=>$request->promotion
            )
        ));
        $getTotalQuantity = Cart::session($request->type)->getTotalQuantity();

        // $item = Cart::session($request->type)->getContent();
        return $getTotalQuantity; 

    }
}
