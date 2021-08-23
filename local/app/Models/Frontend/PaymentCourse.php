<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
use Cart;
use App\Models\Frontend\RunNumberPayment;
use App\Models\Db_Orders;
class PaymentCourse extends Model
{
  public static function transfer_course($rs){
	DB::BeginTransaction();
	$business_location_id = Auth::guard('c_user')->user()->business_location_id;
  if(empty($business_location_id)){
    $business_location_id = 1;
  }
  if(empty($business_location_id)){
    $business_location_id = 1;
  }
	$customer_id = Auth::guard('c_user')->user()->id;
	$code_order = RunNumberPayment::run_number_order($business_location_id);

	try{
		$cartCollection = Cart::session($rs->type)->getContent();
		$data=$cartCollection->toArray();

		$total = Cart::session($rs->type)->getTotal();

    $insert_db_orders = new Db_Orders();
    $insert_db_orders->code_order = $code_order;
    $insert_db_orders->customers_id_fk = $customer_id;
    $insert_db_orders->tax  = $rs->vat;
    $insert_db_orders->sum_price = $rs->price;
    $insert_db_orders->total_price = $rs->price;
    $insert_db_orders->product_value=$rs->price;
    $insert_db_orders->aicash_price = $rs->price;
    $insert_db_orders->date_setting_code =  date('ym');
    $insert_db_orders->action_date =  date('Y-m-d');
    $insert_db_orders->pv_total  =  $rs->pv_total;
    $insert_db_orders->purchase_type_id_fk =  $rs->type;
    $insert_db_orders->pay_type_id_fk  =  $rs->pay_type;
    $insert_db_orders->business_location_id_fk =  $business_location_id;
    $insert_db_orders->distribution_channel_id_fk = '2';
    $insert_db_orders->order_status_id_fk =  '2';

		foreach ($data as $value) {
			$j = $value['quantity'];
			for ($i=1; $i <= $j ; $i++){
				DB::table('db_order_products_list')->insert([
					'code_order'=>$code_order,
					'course_id_fk'=>$value['id'],
					'product_name'=>$value['name'],
					'amt'=>'1',
					'type_product'=>'course',
					'selling_price'=>$value['price'],
					'pv'=>$value['attributes']['pv'],
					'total_pv'=>$value['attributes']['pv'],
					'total_price'=>$value['price'],
				]);

			}

			Cart::session($rs->type)->remove($value['id']);
		}

		$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย'];
    $insert_db_orders->save();
    DB::commit();
    return $resule;

	}catch(Exception $e){
		DB::rollback();
		$resule = ['status'=>'fail','message'=>$e];
		return $resule;
	}
}


}
