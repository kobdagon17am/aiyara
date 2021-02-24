<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Cart;
class PaymentAddProduct extends Model
{
	public static function payment_add_product($order_id,$customer_id,$type){

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

			$resule = ['status'=>'success','message'=>'Product insert Success'];
			return $resule;

		}catch(Exception $e){

			$resule = ['status'=>'fail','message'=>'Product insert Fail'];
			//DB::rollback();
			return $resule;
		}
	}

}
