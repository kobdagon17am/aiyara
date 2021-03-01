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

                  DB::table('db_order_products_list')->insert([
                    'order_id_fk'=>$order_id,
                    'giveaway_id_fk'=>$giveaway_value['giveaway_id'],
                    'customers_id_fk'=>$customer_id,
                    'product_name'=>$giveaway_value['name'],
                    'amt'=>$giveaway_value['count_free'],
                    'type_product'=>'giveaway',
                    'add_from'=>1,
                  ]);
          }
        }
      }
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
