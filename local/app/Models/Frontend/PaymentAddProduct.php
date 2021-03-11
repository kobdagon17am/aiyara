<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Cart;
use Auth;
use App\Http\Controllers\Frontend\Fc\GiveawayController;
class PaymentAddProduct extends Model
{
	public static function payment_add_product($order_id,$customer_id,$type,$business_location_id='',$pv_total=''){

    DB::BeginTransaction();
		try {
			$cartCollection = Cart::session($type)->getContent();
			$data = $cartCollection->toArray();

			foreach ($data as $value) {
				$total_pv = $value['attributes']['pv'] * $value['quantity'];
				$total_price = $value['price'] * $value['quantity'];

				if($value['attributes']['category_id'] == 8){//ซื้อด้วยโปรโมชั่น
					$type_product = 'promotion';

					DB::table('db_order_products_list')->insert([
					'order_id_fk'=>$order_id,
					'promotion_id_fk'=>$value['attributes']['promotion_id'],
					'customers_id_fk'=>$customer_id,
					'product_name'=>$value['name'],
					'amt'=>$value['quantity'],
					'type_product'=>$type_product,
					'selling_price'=>$value['price'],
					'pv'=>$value['attributes']['pv'],
					'total_pv'=>$total_pv,
					'total_price'=>$total_price,
					'add_from'=>1,
				]);

				}else{

					$type_product = 'product';
					DB::table('db_order_products_list')->insert([
					'order_id_fk'=>$order_id,
					'customers_id_fk'=>$customer_id,
					'product_id_fk'=>$value['id'],
					'product_name'=>$value['name'],
					'amt'=>$value['quantity'],
					'type_product'=>$type_product,
					'selling_price'=>$value['price'],
					'pv'=>$value['attributes']['pv'],
					'total_pv'=>$total_pv,
					'total_price'=>$total_price,
					'add_from'=>1,
				]);
 

				}

				Cart::session($type)->remove($value['id']);

			}
      if(!empty($business_location_id) and !empty($pv_total)){
        $customer_pv = Auth::guard('c_user')->user()->pv;// ของแถม
        $check_giveaway = GiveawayController::check_giveaway($business_location_id,$type,$customer_pv,$pv_total);

        if($check_giveaway['status']== 'success'){
          if($check_giveaway['s_data']){
            foreach($check_giveaway['s_data'] as $giveaway_value){


                  $products_list_id = DB::table('db_order_products_list')->insertGetId([
                    'order_id_fk'=>$order_id,
                    'giveaway_id_fk'=>$giveaway_value['giveaway_id'],
                    'customers_id_fk'=>$customer_id,
                    'product_name'=>$giveaway_value['name'],
                    'amt'=>$giveaway_value['count_free'],
                    'type_product'=>'giveaway',
                    'add_from'=>1,
                  ]);

                  if($giveaway_value['type'] == 1){//product

                    $product =  DB::table('db_giveaway_products')
                    ->select('products_details.product_id_fk','products_details.product_name','dataset_product_unit.product_unit'
                    ,'dataset_product_unit.group_id as unit_id','db_giveaway_products.product_amt')
                    ->leftJoin('products_details','products_details.product_id_fk','=','db_giveaway_products.product_id_fk')
                    ->leftJoin('dataset_product_unit','dataset_product_unit.group_id','=','db_giveaway_products.product_unit')
                    ->where('db_giveaway_products.giveaway_id_fk','=',$giveaway_value['giveaway_id'])
                    ->where('products_details.lang_id','=',$business_location_id)
                    ->where('dataset_product_unit.lang_id','=',$business_location_id)
                    ->get();

                    foreach($product as $giveaway_product){

                      DB::table('db_order_products_list_giveaway')->insert([
                        'order_id_fk'=>$order_id,
                        'product_list_id_fk'=>$products_list_id,
                        'giveaway_id_fk'=>$giveaway_value['giveaway_id'],
                        'product_id_fk'=>$giveaway_product->product_id_fk,
                        'product_name'=>$giveaway_product->product_name,
                        'product_unit_id_fk'=>$giveaway_product->unit_id,
                        'product_amt'=>$giveaway_product->product_amt,
                        'product_unit_name'=> $giveaway_product->product_unit,
                        'free'=>$giveaway_value['count_free'],
                        'type_product'=>'giveaway_product',
                        ]);
                    }

                  }else{//gv

                    DB::table('db_order_products_list_giveaway')->insert([
                      'order_id_fk'=>$order_id,
                      'product_list_id_fk'=>$products_list_id,
                      'product_name'=>'GiftVoucher',
                      'giveaway_id_fk'=>$giveaway_value['giveaway_id'],
                      'product_amt'=>1,
                      'gv_free'=>$giveaway_value['gv'],
                      'free'=>$giveaway_value['count_free'],
                      'type_product'=>'giveaway_gv',

                      ]);
                  }
          }
        }
      }
    }

			$resule = ['status'=>'success','message'=>'Product insert Success'];
      DB::commit();
			return $resule;

		}catch(Exception $e){

			$resule = ['status'=>'fail','message'=>'Product insert Fail'];
			DB::rollback();
			return $resule;
		}
	}

}
