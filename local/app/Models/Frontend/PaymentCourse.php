<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
use Cart;
use App\Models\Frontend\RunNumberPayment;
class PaymentCourse extends Model
{
	public static function payment_uploadfile($rs){

		DB::BeginTransaction();
		$business_location_id = Auth::guard('c_user')->user()->business_location_id;
		$customer_id = Auth::guard('c_user')->user()->id;
		$code_order = RunNumberPayment::run_number_order($business_location_id);

		try{
			$cartCollection = Cart::session($rs->type)->getContent();

			$data=$cartCollection->toArray();

			$total = Cart::session($rs->type)->getTotal();


			$id = DB::table('db_orders')->insertGetId(
				[
					'code_order' => $code_order,
					'customers_id_fk' => $customer_id,
					'tax'  => $rs->vat,
					'sum_price' => $rs->price,
          'total_price'=> $rs->price,
          'product_value'=>$rs->price,
          'date_setting_code' => date('ym'),
          'action_date' => date('Y-m-d'),
					'pv_total'  => $rs->pv_total,
					'purchase_type_id_fk'  => $rs->type,
					'pay_type_id_fk'  => $rs->pay_type,
					'business_location_id_fk' => $business_location_id,
          'distribution_channel_id_fk'=>2,
					'order_status_id_fk' => '2'
				]
			);



			$file_slip = $rs->file_slip;
			if(isset($file_slip)){
				$url='local/public/files_slip/'.date('Ym');

				$f_name = date('YmdHis').'_'.$customer_id.'.'.$file_slip->getClientOriginalExtension();
				if($file_slip->move($url,$f_name)){
					DB::table('payment_slip')
					->insert(['customer_id'=>$customer_id,'url'=>$url,'file'=>$f_name,'order_id'=>$id]);
				}
			}

			foreach ($data as $value) {
				$j = $value['quantity'];
				for ($i=1; $i <= $j ; $i++){

					DB::table('db_order_products_list')->insert([
						'frontstore_id_fk'=>$id,
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

			$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย','order_id'=>$id];

				//return redirect('product-history')->withSuccess('สั่งซื้อสินค้าเรียบร้อย');

			DB::commit();
			return $resule;

		}catch(Exception $e){
			DB::rollback();
			$resule = ['status'=>'fail','message'=>$e];
			return $resule;
		}
	}

	public static function payment_not_uploadfile($rs){

		DB::BeginTransaction();
		$business_location_id = Auth::guard('c_user')->user()->business_location_id;
		$customer_id = Auth::guard('c_user')->user()->id;
		$code_order = RunNumberPayment::run_number_order($business_location_id);

		try{
			$cartCollection = Cart::session($rs->type)->getContent();

			$data=$cartCollection->toArray();

			$total = Cart::session($rs->type)->getTotal();

			$id = DB::table('db_orders')->insertGetId(
				[
					'code_order' => $code_order,
					'customers_id_fk' => $customer_id,
					'tax'  => $rs->vat,
					'sum_price' => $rs->price,
          'total_price' => $rs->price,
          'product_value'=>$rs->price,
          'date_setting_code' => date('ym'),
          'action_date' => date('Y-m-d'),
					'pv_total'  => $rs->pv_total,
					'purchase_type_id_fk'  => $rs->type,
					'pay_type_id_fk'  => $rs->pay_type,
					'business_location_id_fk' => $business_location_id,
          'distribution_channel_id_fk'=>2,
					'order_status_id_fk' => '1'
				]
			);

			foreach ($data as $value) {
				$j = $value['quantity'];
				for ($i=1; $i <= $j ; $i++){
					DB::table('db_order_products_list')->insert([
						'frontstore_id_fk'=>$id,
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

			$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย','order_id'=>$id];
				//return $resule;
				//return redirect('product-history')->withSuccess('สั่งซื้อสินค้าเรียบร้อย');

			DB::commit();
			return $resule;
		}catch(Exception $e){
			DB::rollback();
			$resule = ['status'=>'fail','message'=>$e];
			return $resule;

		}
	}

	public static function credit_card($rs){

		DB::BeginTransaction();
		$customer_id = Auth::guard('c_user')->user()->id;

		$business_location_id = Auth::guard('c_user')->user()->business_location_id;
		$customer_id = Auth::guard('c_user')->user()->id;
		$code_order = RunNumberPayment::run_number_order($business_location_id);

		try{
			$cartCollection = Cart::session($rs->type)->getContent();
			$data=$cartCollection->toArray();

			$total = Cart::session($rs->type)->getTotal();

			$id = DB::table('db_orders')->insertGetId(
				[
					'code_order' => $code_order,
					'customers_id_fk' => $customer_id,
					'tax'  => $rs->vat,
					'sum_price' => $rs->price,
          'total_price' => $rs->price,
          'product_value'=>$rs->price,
          'date_setting_code' => date('ym'),
          'action_date' => date('Y-m-d'),
					'pv_total'  => $rs->pv_total,
					'purchase_type_id_fk'  => $rs->type,
					'pay_type_id_fk'  => $rs->pay_type,
					'business_location_id_fk' => $business_location_id,
          'distribution_channel_id_fk'=>2,
					'order_status_id_fk' => '2'
				]
			);

			foreach ($data as $value) {
				$j = $value['quantity'];
				for ($i=1; $i <= $j ; $i++){
					DB::table('db_order_products_list')->insert([
						'frontstore_id_fk'=>$id,
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

		$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย','order_id'=>$id];



		if($resule['status'] == 'success'){

			$resulePv = Pvpayment::PvPayment_type_confirme($id,$customer_id,'2','customer');

			if($resulePv['status'] == 'fail'){

				DB::rollback();
				return $resulePv;
			}else{
				DB::commit();
				return $resule;
			}
		}else{
			DB::rollback();
			return $resule;
		}
	}catch(Exception $e){
		DB::rollback();
		$resule = ['status'=>'fail','message'=>$e];
		return $resule;
	}
}

public static function ai_cash($rs){


	DB::BeginTransaction();

	$business_location_id = Auth::guard('c_user')->user()->business_location_id;
	$customer_id = Auth::guard('c_user')->user()->id;
	$code_order = RunNumberPayment::run_number_order($business_location_id);

	try{
		$cartCollection = Cart::session($rs->type)->getContent();
		$data=$cartCollection->toArray();

		$total = Cart::session($rs->type)->getTotal();

		$id = DB::table('db_orders')->insertGetId(
			[
				'code_order' => $code_order,
				'customers_id_fk' => $customer_id,
				'tax'  => $rs->vat,
				'sum_price' => $rs->price,
        'total_price' => $rs->price,
        'product_value'=>$rs->price,
        'aicash_price' => $rs->price,
        'date_setting_code' => date('ym'),
        'action_date' => date('Y-m-d'),
				'pv_total'  => $rs->pv_total,
				'purchase_type_id_fk'  => $rs->type,
				'pay_type_id_fk'  => $rs->pay_type,
				'business_location_id_fk' => $business_location_id,
        'distribution_channel_id_fk'=>2,
				'order_status_id_fk' => '2'
			]
		);

		foreach ($data as $value) {
			$j = $value['quantity'];
			for ($i=1; $i <= $j ; $i++){
				DB::table('db_order_products_list')->insert([
					'frontstore_id_fk'=>$id,
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

		$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย','order_id'=>$id];


		if($resule['status'] == 'success'){
			$resulePv = Pvpayment::PvPayment_type_confirme($id,$customer_id,'2','customer');
			if($resulePv['status'] == 'fail'){

				DB::rollback();
				return $resulePv;
			}else{
				DB::commit();
				return $resule;
			}
		}else{
			DB::rollback();
			return $resule;
		}
	}catch(Exception $e){
		DB::rollback();
		$resule = ['status'=>'fail','message'=>$e];
		return $resule;
	}
}


}
