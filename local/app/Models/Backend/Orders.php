<?php

namespace App\Models\Backend;

use App\Models\InitModel;
use DB;
use Illuminate\Support\Collection;

class Orders extends InitModel
{
    protected $table = 'db_orders';

    static function getAllProduct($arr_order){
        // product
        $Products_old = DB::table('db_order_products_list')
        // ->select('db_order_products_list.amt as amt_sum','db_order_products_list.product_id_fk as product_id_fk','products_details.product_name as product_name','products.product_code as product_code','dataset_product_unit.product_unit as product_unit')
        ->select('db_order_products_list.amt as amt_sum','db_order_products_list.product_id_fk as product_id_fk')
        // ->join('products','products.id','db_order_products_list.product_id_fk')
        // ->join('products_details','products_details.product_id_fk','db_order_products_list.product_id_fk')
        // ->join('products_units','products_units.product_id_fk','db_order_products_list.product_id_fk')
        // ->join('dataset_product_unit','dataset_product_unit.id','products_units.product_unit_id_fk')
        ->whereIn('db_order_products_list.frontstore_id_fk',$arr_order)
        ->where('db_order_products_list.type_product','product')
        // ->groupBy('db_order_products_list.product_id_fk')
        // ->whereIn('db_order_products_list.product_id_fk',[17,23])
        ->get();

        $Products = new Collection();
        $arr_check = [];
        foreach($Products_old as $p){

            $products_data = DB::table('products')->select('products.product_code as product_code')->where('id',$p->product_id_fk)->first();
            $products_details = DB::table('products_details')->select('products_details.product_name as product_name')->where('product_id_fk',$p->product_id_fk)->first();
            $products_units = DB::table('products_units')->select('product_unit_id_fk')->where('product_id_fk',$p->product_id_fk)->first();
            $dataset_product_unit = DB::table('dataset_product_unit')->select('dataset_product_unit.product_unit as product_unit')->where('id',$products_units->product_unit_id_fk)->first();
          
            if(isset($arr_check[$p->product_id_fk])){
                $filtered = $Products->filter(function ($value, $key) use($p) {
                    return $value->product_id_fk == $p->product_id_fk;
                });
                $filtered = $filtered->first();
    
                $Products = $Products->reject(function ($value, $key) use($p) {
                    return $value->product_id_fk == $p->product_id_fk;
                });
    
                $Products->push((object)[
                    'amt_sum'=> $p->amt_sum+$filtered->amt_sum,
                    'product_id_fk'=> $p->product_id_fk,
                    'product_name'=> $products_details->product_name,
                    'product_code'=> $products_data->product_code,
                    'product_unit'=> $dataset_product_unit->product_unit,
                     ]);
            }else{
              
                $Products->push((object)[
                    'amt_sum'=> $p->amt_sum,
                    'product_id_fk'=> $p->product_id_fk,
                    'product_name'=> $products_details->product_name,
                    'product_code'=> $products_data->product_code,
                    'product_unit'=> $dataset_product_unit->product_unit,
                     ]);
                     $arr_check[$p->product_id_fk] = true;
             }

          
        }
     

        // promotion
        // $Products = DB::table('db_order_products_list')
        // ->select(DB::raw('promotions_products.product_amt * db_order_products_list.amt AS amt_sum'),'promotions_products.product_id_fk','products_details.product_name','products.product_code','dataset_product_unit.product_unit')
        // ->rightJoin('promotions_products','promotions_products.promotion_id_fk','db_order_products_list.promotion_id_fk')
        // ->leftJoin('products','products.id','promotions_products.product_id_fk')
        // ->leftJoin('products_details','products_details.product_id_fk','promotions_products.product_id_fk')
        // ->leftJoin('dataset_product_unit','dataset_product_unit.id','promotions_products.product_unit')
        // ->whereIn('db_order_products_list.frontstore_id_fk',$arr_order)
        // ->where('db_order_products_list.type_product','promotion')
        // ->groupBy('promotions_products.product_id_fk')
        // ->union($Products)
        // ->get();

        // promotion
         $Pro_amt = DB::table('db_order_products_list')
         ->select('*',DB::raw('SUM(db_order_products_list.amt) AS sum_amt'))
         ->whereIn('db_order_products_list.frontstore_id_fk',$arr_order)
         ->where('db_order_products_list.type_product','promotion')
         ->groupBy('db_order_products_list.promotion_id_fk')
         ->get();

         foreach($Pro_amt as $key => $pro){
            $Products2 = DB::table('promotions_products')
            ->select(DB::raw('promotions_products.product_amt * '.$pro->sum_amt.' AS amt_sum'),'promotions_products.product_id_fk as product_id_fk','products_details.product_name as product_name','products.product_code as product_code','dataset_product_unit.product_unit as product_unit')
            ->leftJoin('products','products.id','promotions_products.product_id_fk')
            ->leftJoin('products_details','products_details.product_id_fk','promotions_products.product_id_fk')
            ->leftJoin('dataset_product_unit','dataset_product_unit.id','promotions_products.product_unit')
            ->where('promotions_products.promotion_id_fk',$pro->promotion_id_fk)
            ->groupBy('promotions_products.product_id_fk')
            ->get();

            if($key+1==1){
                $Products = $Products->merge($Products2);
            }else{
                $Products = $Products->merge($Products2);
            }
         }  

         $collection = new Collection();
         foreach($Products as $p){
             $collection->push((object)[
                 'amt_sum'=> $p->amt_sum,
                 'product_id_fk'=> $p->product_id_fk,
                 'product_name'=> $p->product_name,
                 'product_code'=> $p->product_code,
                 'product_unit'=> $p->product_unit,
                  ]);
         }
         $arr_check = [];
         $collection_new = new Collection();
         foreach($collection as $c){
             if(isset($arr_check[$c->product_id_fk])){

                $filtered = $collection_new->filter(function ($value, $key) use($c) {
                    return $value->product_id_fk == $c->product_id_fk;
                });
                $filtered = $filtered->first();

                $collection_new = $collection_new->reject(function ($value, $key) use($c) {
                    return $value->product_id_fk == $c->product_id_fk;
                });

                $collection_new->push((object)[
                    'amt_sum'=> $c->amt_sum+$filtered->amt_sum,
                    'product_id_fk'=> $c->product_id_fk,
                    'product_name'=> $c->product_name,
                    'product_code'=> $c->product_code,
                    'product_unit'=> $c->product_unit,
                     ]);

             }else{
                $collection_new->push((object)[
                    'amt_sum'=> $c->amt_sum,
                    'product_id_fk'=> $c->product_id_fk,
                    'product_name'=> $c->product_name,
                    'product_code'=> $c->product_code,
                    'product_unit'=> $c->product_unit,
                     ]);
                     $arr_check[$c->product_id_fk] = true;
             }
         }
         $Products = $collection_new->sortBy('product_code');

        return $Products;
       
    }

}
