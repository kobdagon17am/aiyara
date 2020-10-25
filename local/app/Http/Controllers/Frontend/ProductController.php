<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer');
    }

    public function index()
    {

        $ev_objective = DB::table('ev_objective')
        ->where('lang_id', '=', 1)
        ->orderby('order')
        ->get();

        $categories = DB::table('categories')
        ->where('lang_id', '=', 1)
        ->orderby('order')
        ->get();

        $product = DB::table('products')
        ->select(
            'products.*',
            'product_detail.product_name',
            'product_detail.title',
            'product_detail.product_detail'
        )
        ->leftjoin('product_detail', 'products.id', '=', 'product_detail.product_id')
        ->where('products.category_id', '=', 1)
        ->where('product_detail.lang_id', '=', 1)
        ->orderby('products.id')
        ->Paginate(4);

        $data = array(
            'objective' => $ev_objective,
            'categories' => $categories,
            'product' => $product);
        return view('frontend/product/product-list', $data);
    }

    public function product_detail($id)
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
           

            return view('frontend/product/product-detail', compact('product'));
        }
    }

    public function add_cart(Request $request){

     //dd(Cart::getContent());
     $data = Cart::add(array(
    'id' => $request->id, // inique row ID
    'name' => $request->name,
    'price' => $request->price,
    'pv'=>$request->pv,
    'quantity' => $request->quantity,
    'attributes' => array('pv'=>$request->pv,
        'img'=>$request->img,
        'title'=>$request->title,
        'promotion'=>$request->promotion)
 
));
     $getTotalQuantity = Cart::getTotalQuantity();
     return $getTotalQuantity; 

 }
}
