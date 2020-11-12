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
        // ->select(
        //     'products.*',
        //     'product_detail.product_name',
        //     'product_detail.title',
        //     'product_detail.product_detail'
        // )
        ->leftjoin('products_details', 'products.id', '=', 'products_details.product_id_fk')
        ->leftjoin('products_images', 'products.id', '=', 'products_images.product_id_fk')
        ->leftjoin('products_cost', 'products.id', '=', 'products_cost.product_id_fk')
        ->leftjoin('dataset_currency', 'dataset_currency.id', '=', 'products_cost.currency_id')
        ->where('products.category_id', '=',$c_id)
        ->where('products_images.image_default', '=', 1)
        ->where('products_details.lang_id', '=', 1)
        ->where('products_cost.business_location_id','=', 1)
        ->orderby('products.id')
        ->get();
        //->Paginate(4);
        //dd($product);

        $data = array(
            'category' => $categories,
            'product' => $product);
        return $data;

    }
}
