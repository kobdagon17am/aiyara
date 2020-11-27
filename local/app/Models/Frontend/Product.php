<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
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
        ->where('products.category_id', '=',$c_id)
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

}
