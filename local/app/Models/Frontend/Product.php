<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
class Product extends Model
{
	public static function product_list($c_id=''){
		// $ev_objective = DB::table('categories')
  //       ->where('lang_id', '=', 1)
  //       ->orderby('order')
  //       ->get();

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
        ->where('products.category_id', '=',$c_id)
        ->where('product_detail.lang_id', '=', 1)
        ->orderby('products.id')
        ->get();
        // ->Paginate(4);

        $data = array(
            'category' => $categories,
            'product' => $product);
        return $data;

    }
}