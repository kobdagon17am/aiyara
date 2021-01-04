<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
use Cart;
class PaymentCourse extends Model
{
	public static function payment_uploadfile($rs){
		dd('ffffff');
		DB::BeginTransaction();
		$customer_id = Auth::guard('c_user')->user()->id;
		// เลขใบเสร็จ
		// ปีเดือน[รันเลข]
		// 2020110 00001

		$id = DB::table('orders')
		->select('id')
		->orderby('id','desc')
		->first();
		
		$maxId  = $id->id +1;
		$maxId = substr("00000".$maxId, -5);
		$code_order = date('Ymd').''.$maxId;

		try{
			if($rs->receive == 'sent_address'){

				$cartCollection = Cart::session($rs->type)->getContent();
				$data = $cartCollection->toArray();
				$total = Cart::session($rs->type)->getTotal();

				if($rs->type == 5){
					$data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
					$gv_customer = $data_gv->sum_gv;

					$gv_total = $gv_customer - ($rs->price + $rs->shipping);

					if($gv_total < 0){
						$gv = $gv_customer;
						$price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));

					}else{
						$gv = $rs->price + $rs->shipping;
						$price_remove_gv = 0;
					}
				}else{
					$gv = null;
					$price_remove_gv = null;

				}

				$id = DB::table('orders')->insertGetId(
					['code_order' => $code_order,
					'customer_id' => $customer_id,
					'vat'  => $rs->vat,
					'shipping'  => $rs->shipping,
					'price' => $rs->price,
					'price_vat' => $rs->price_vat,
					'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'gv' => $gv,//gvที่ใช้
					'price_remove_gv'=>$price_remove_gv,//ราคาที่ต้องจ่ายเพิ่ม
					'type_id'  => $rs->type,
					'pay_type_id'  => $rs->pay_type,
					'orderstatus_id' => '2',
					'house_no'=> $rs->house_no,
					'house_name'=> $rs->house_name,
					'moo'=> $rs->moo,
					'soi'=> $rs->soi,
					'district'=> $rs->district,
					'district_sub'=> $rs->district_sub,
					'road'=> $rs->road,
					'province'=> $rs->province,
					'zipcode'=> $rs->zipcode,
					'type_address' => 0,
					'tel' => $rs->tel_mobile,
					'name' => $rs->name,
					'email' => $rs->email,
				]
			);
				if($rs->type == 4){//เติม Ai-Stockist
					$ai_pocket = DB::table('ai_pocket')->insert(
						['customer_id'=>$customer_id,
						'to_customer_id'=>$customer_id,
						'order_id'=>$id,
						'pv'=>$rs->pv_total,
						'type_id'  => $rs->type,
						'status' => 'panding',
						'detail' => 'Payment Add Ai-Stockist',
					]);

				}
				if($rs->type == 5){
					$price_total = ($rs->price + $rs->shipping);
					$rs_log_gift = GiftVoucher::log_gift($price_total,$customer_id,$id);

					if($rs_log_gift['status'] != 'success'){
						DB::rollback(); 
						$resule = ['status'=>'fail','message'=>'rs_log_gift fail'];
						return $resule;
					}

				}


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
					DB::table('order_items')->insert([
						'order_id'=>$id,
						'product_id'=>$value['id'],
						'product_name'=>$value['name'],
						'quantity'=>$value['quantity'],
						'list_price'=>$value['price'],
						'pv'=>$value['attributes']['pv'],
					]);

					Cart::session($rs->type)->remove($value['id']);
				}
				$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย'];

				//return redirect('product-history')->withSuccess('สั่งซื้อสินค้าเรียบร้อย');
			}elseif($rs->receive == 'receive_office') {

				$cartCollection = Cart::session($rs->type)->getContent();
				$data=$cartCollection->toArray();
				$total = Cart::session($rs->type)->getTotal();

				if($rs->type == 5){
					$data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
					$gv_customer = $data_gv->sum_gv;
					
					$gv_total = $gv_customer - ($rs->price + $rs->shipping);

					if($gv_total < 0){
						$gv = $gv_customer;
						$price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));

					}else{
						$gv = $rs->price + $rs->shipping;
						$price_remove_gv = 0;
					}
				}else{
					$gv = null;
					$price_remove_gv = null;

				}

				$id = DB::table('orders')->insertGetId(
					['code_order' => $code_order,
					'customer_id' => $customer_id,
					'vat'  => $rs->vat,
					'shipping'  => $rs->shipping,
					'price' => $rs->price,
					'price_vat' => $rs->price_vat,
					'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'gv' => $gv,//gvที่ใช้
					'price_remove_gv'=>$price_remove_gv,//ราคาที่ต้องจ่ายเพิ่ม
					'type_id'  => $rs->type,
					'pay_type_id'  => $rs->pay_type,
					'orderstatus_id' => '2',
					'type_address' => $rs->receive_location,
					'tel' => $rs->tel_mobile,
					'name' => $rs->name,
					'email' => $rs->email,
				]
			);

			if($rs->type == 4){//เติม Ai-Stockist
				$ai_pocket = DB::table('ai_pocket')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-Stockist',
				]);

			}
			if($rs->type == 5){
				$price_total = ($rs->price + $rs->shipping);
				$rs_log_gift = GiftVoucher::log_gift($price_total,$customer_id,$id);

				if($rs_log_gift['status'] != 'success'){
					DB::rollback(); 
					$resule = ['status'=>'fail','message'=>'rs_log_gift fail'];
					return $resule;
				}

			}

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
				DB::table('order_items')->insert([
					'order_id'=>$id,
					'product_id'=>$value['id'],
					'product_name'=>$value['name'],
					'quantity'=>$value['quantity'],
					'list_price'=>$value['price'],
					'pv'=>$value['attributes']['pv'],
				]);
				Cart::session($rs->type)->remove($value['id']);
			}
			$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย'];

				//return redirect('product-history')->withSuccess('สั่งซื้อสินค้าเรียบร้อย');
		}else{
			$resule = ['status'=>'fail','message'=>'Payment submit Fail Address'];

				//return redirect('cart_payment')->withError('Payment submit Fail ( Address )');
		}
		DB::commit();

		return $resule;
	}catch(Exception $e) { 
		DB::rollback();
		return redirect('product-history')->withError('Payment submit Fail');

	}
}

public static function payment_not_uploadfile($rs){
	DB::BeginTransaction();
	$customer_id = Auth::guard('c_user')->user()->id;

	$id = DB::table('orders')
	->select('id')
	->orderby('id','desc')
	->first();

	if(empty($id)){
		$maxId  = 0; 
	}else{
		$maxId  = $id->id +1; 
	}
	
	$maxId = substr("00000".$maxId, -5);
	$code_order = date('Ymd').''.$maxId;
	try{
		$cartCollection = Cart::session($rs->type)->getContent();
		$data=$cartCollection->toArray();

		$total = Cart::session($rs->type)->getTotal();

		$id = DB::table('orders')->insertGetId(
			[
				'code_order' => $code_order,
				'customer_id' => $customer_id,
				'vat'  => $rs->vat,
				'price' => $rs->price,
				'price_vat' => $rs->price_vat,
				'p_vat'  => $rs->p_vat,
				'pv_total'  => $rs->pv_total,
				'type_id'  => $rs->type,
				'pay_type_id'  => $rs->pay_type,
				'orderstatus_id' => '2'
			] 
		);

		foreach ($data as $value) {
			DB::table('order_items')->insert([
				'order_id'=>$id,
				'course_id'=>$value['id'],
				'product_name'=>$value['name'],
				'quantity'=>$value['quantity'],
				'list_price'=>$value['price'],
				'pv'=>$value['attributes']['pv'],
			]);
 
			Cart::session($rs->type)->remove($value['id']);
		}

		$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย'];
				//return $resule;
				//return redirect('product-history')->withSuccess('สั่งซื้อสินค้าเรียบร้อย');

		DB::commit();
		return $resule;
	}catch(Exception $e){
		DB::rollback();
		return redirect('product-history')->withError('Payment submit Fail');

	}
}

public static function credit_card($rs){
	DB::BeginTransaction();
	$customer_id = Auth::guard('c_user')->user()->id;
	$id = DB::table('orders')
	->select('id')
	->orderby('id','desc')
	->first();

	$maxId  = $id->id +1;
	$maxId = substr("00000".$maxId, -5);
	$code_order = date('Ymd').''.$maxId;
	try{
		if($rs->receive == 'sent_address'){

			$cartCollection = Cart::session($rs->type)->getContent();
			$data=$cartCollection->toArray();
			$total = Cart::session($rs->type)->getTotal();

			if($rs->type == 5){
				$data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
				$gv_customer = $data_gv->sum_gv;

				$gv_total = $gv_customer - ($rs->price + $rs->shipping);

				if($gv_total < 0){
					$gv = $gv_customer;
					$price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));

				}else{
					$gv = $rs->price + $rs->shipping;
					$price_remove_gv = 0;
				}
			}else{
				$gv = null;
				$price_remove_gv = null;

			}

			$id = DB::table('orders')->insertGetId(
				['code_order' => $code_order,
				'customer_id' => $customer_id,
				'vat'  => $rs->vat,
				'shipping'  => $rs->shipping,
				'price' => $rs->price,
				'price_vat' => $rs->price_vat,
				'p_vat'  => $rs->p_vat,
				'pv_total'  => $rs->pv_total,
				'gv' => $gv,//gvที่ใช้
				'price_remove_gv'=>$price_remove_gv,//ราคาที่ต้องจ่ายเพิ่ม
				'type_id'  => $rs->type,
				'pay_type_id'  => $rs->pay_type,
				'orderstatus_id' => '2',
				'house_no'=> $rs->house_no,
				'house_name'=> $rs->house_name,
				'moo'=> $rs->moo,
				'soi'=> $rs->soi,
				'district'=> $rs->district,
				'district_sub'=> $rs->district_sub,
				'road'=> $rs->road,
				'province'=> $rs->province,
				'zipcode'=> $rs->zipcode,
				'type_address' => 0,
				'tel' => $rs->tel_mobile,
				'name' => $rs->name,
				'email' => $rs->email,
			]
		);
			if($rs->type == 4){//เติม Ai-Stockist
				$ai_pocket = DB::table('ai_pocket')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-Stockist',
				]);

			}
			if($rs->type == 5){
				$price_total = ($rs->price + $rs->shipping);
				$rs_log_gift = GiftVoucher::log_gift($price_total,$customer_id,$id);

				if($rs_log_gift['status'] != 'success'){
					DB::rollback(); 
					$resule = ['status'=>'fail','message'=>'rs_log_gift fail'];
					return $resule;
				}

			}

			foreach ($data as $value) {

				DB::table('order_items')->insert([
					'order_id'=>$id,
					'product_id'=>$value['id'],
					'product_name'=>$value['name'],
					'quantity'=>$value['quantity'],
					'list_price'=>$value['price'],
					'pv'=>$value['attributes']['pv'],
				]);

				Cart::session($rs->type)->remove($value['id']);
			}
			$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย'];
					//return $resule;

				//return redirect('product-history')->withSuccess('สั่งซื้อสินค้าเรียบร้อย');
		}elseif($rs->receive == 'receive_office') {

			$cartCollection = Cart::session($rs->type)->getContent();
			$data=$cartCollection->toArray();
			$total = Cart::session($rs->type)->getTotal();
			if($rs->type == 5){
				$data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
				$gv_customer = $data_gv->sum_gv;

				$gv_total = $gv_customer - ($rs->price + $rs->shipping);

				if($gv_total < 0){
					$gv = $gv_customer;
					$price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));

				}else{
					$gv = $rs->price + $rs->shipping;
					$price_remove_gv = 0;
				}
			}else{
				$gv = null;
				$price_remove_gv = null;

			}

			$id = DB::table('orders')->insertGetId(
				['code_order' => $code_order,
				'customer_id' => $customer_id,
				'vat'  => $rs->vat,
				'shipping'  => $rs->shipping,
				'price' => $rs->price,
				'price_vat' => $rs->price_vat,
				'p_vat'  => $rs->p_vat,
				'pv_total'  => $rs->pv_total,
				'gv' => $gv,//gvที่ใช้
				'price_remove_gv'=>$price_remove_gv,//ราคาที่ต้องจ่ายเพิ่ม
				'type_id'  => $rs->type,
				'pay_type_id'  => $rs->pay_type,
				'orderstatus_id' => '2',
				'type_address' => $rs->receive_location,
				'tel' => $rs->tel_mobile,
				'name' => $rs->name,
				'email' => $rs->email,
			]
		);
			if($rs->type == 4){//เติม Ai-Stockist
				$ai_pocket = DB::table('ai_pocket')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-Stockist',
				]);

			}
			if($rs->type == 5){
				$price_total = ($rs->price + $rs->shipping);
				$rs_log_gift = GiftVoucher::log_gift($price_total,$customer_id,$id);

				if($rs_log_gift['status'] != 'success'){
					DB::rollback(); 
					$resule = ['status'=>'fail','message'=>'rs_log_gift fail'];
					return $resule;
				}

			}

			foreach ($data as $value) {
				DB::table('order_items')->insert([
					'order_id'=>$id,
					'product_id'=>$value['id'],
					'product_name'=>$value['name'],
					'quantity'=>$value['quantity'],
					'list_price'=>$value['price'],
					'pv'=>$value['attributes']['pv'],
				]);
				Cart::session($rs->type)->remove($value['id']);
			}
			$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย'];
					//return $resule;
				//return redirect('product-history')->withSuccess('สั่งซื้อสินค้าเรียบร้อย');
		}else{
			$resule = ['status'=>'fail','message'=>'Payment submit Fail Address'];
					//return $resule;
				//return redirect('cart_payment')->withError('Payment submit Fail ( Address )');
		}
		DB::commit();
		return $resule;
	}catch(Exception $e) {
		DB::rollback();
		return redirect('product-history')->withError('Payment submit Fail');

	}
}

public static function ai_cash($rs){
	DB::BeginTransaction();
	$customer_id = Auth::guard('c_user')->user()->id;
	$id = DB::table('orders')
	->select('id')
	->orderby('id','desc')
	->first();

	$maxId  = $id->id +1;
	$maxId = substr("00000".$maxId, -5);
	$code_order = date('Ymd').''.$maxId;
	try{
		if($rs->receive == 'sent_address'){

			$cartCollection = Cart::session($rs->type)->getContent();
			$data=$cartCollection->toArray();
			$total = Cart::session($rs->type)->getTotal();

			if($rs->type == 5){
				$data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
				$gv_customer = $data_gv->sum_gv;

				$gv_total = $gv_customer - ($rs->price + $rs->shipping);

				if($gv_total < 0){
					$gv = $gv_customer;
					$price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));

				}else{
					$gv = $rs->price + $rs->shipping;
					$price_remove_gv = 0;
				}
			}else{
				$gv = null;
				$price_remove_gv = null;

			}

			$id = DB::table('orders')->insertGetId(
				['code_order' => $code_order,
				'customer_id' => $customer_id,
				'vat'  => $rs->vat,
				'shipping'  => $rs->shipping,
				'price' => $rs->price,
				'price_vat' => $rs->price_vat,
				'p_vat'  => $rs->p_vat,
				'pv_total'  => $rs->pv_total,
				'gv' => $gv,//gvที่ใช้
				'price_remove_gv'=>$price_remove_gv,//ราคาที่ต้องจ่ายเพิ่ม
				'type_id'  => $rs->type,
				'pay_type_id'  => $rs->pay_type,
				'orderstatus_id' => '2',
				'house_no'=> $rs->house_no,
				'house_name'=> $rs->house_name,
				'moo'=> $rs->moo,
				'soi'=> $rs->soi,
				'district'=> $rs->district,
				'district_sub'=> $rs->district_sub,
				'road'=> $rs->road,
				'province'=> $rs->province,
				'zipcode'=> $rs->zipcode,
				'type_address' => 0,
				'tel' => $rs->tel_mobile,
				'name' => $rs->name,
				'email' => $rs->email,
			]
		);
			if($rs->type == 4){//เติม Ai-Stockist
				$ai_pocket = DB::table('ai_pocket')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-Stockist',
				]);

			}
			if($rs->type == 5){
				$price_total = ($rs->price + $rs->shipping);
				$rs_log_gift = GiftVoucher::log_gift($price_total,$customer_id,$id);

				if($rs_log_gift['status'] != 'success'){
					DB::rollback(); 
					$resule = ['status'=>'fail','message'=>'rs_log_gift fail'];
					return $resule;
				}

			}

			foreach ($data as $value) {

				DB::table('order_items')->insert([
					'order_id'=>$id,
					'product_id'=>$value['id'],
					'product_name'=>$value['name'],
					'quantity'=>$value['quantity'],
					'list_price'=>$value['price'],
					'pv'=>$value['attributes']['pv'],
				]);

				Cart::session($rs->type)->remove($value['id']);
			}
			$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย'];
					//return $resule;

				//return redirect('product-history')->withSuccess('สั่งซื้อสินค้าเรียบร้อย');
		}elseif($rs->receive == 'receive_office') {

			$cartCollection = Cart::session($rs->type)->getContent();
			$data=$cartCollection->toArray();
			$total = Cart::session($rs->type)->getTotal();

			if($rs->type == 5){
				$data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
				$gv_customer = $data_gv->sum_gv;

				$gv_total = $gv_customer - ($rs->price + $rs->shipping);

				if($gv_total < 0){
					$gv = $gv_customer;
					$price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));

				}else{
					$gv = $rs->price + $rs->shipping;
					$price_remove_gv = 0;
				}
			}else{
				$gv = null;
				$price_remove_gv = null;

			}

			$id = DB::table('orders')->insertGetId(
				['code_order' => $code_order,
				'customer_id' => $customer_id,
				'vat'  => $rs->vat,
				'shipping'  => $rs->shipping,
				'price' => $rs->price,
				'price_vat' => $rs->price_vat,
				'p_vat'  => $rs->p_vat,
				'pv_total'  => $rs->pv_total,
				'gv' => $gv,//gvที่ใช้
				'price_remove_gv'=>$price_remove_gv,//ราคาที่ต้องจ่ายเพิ่ม
				'type_id'  => $rs->type,
				'pay_type_id'  => $rs->pay_type,
				'orderstatus_id' => '2',
				'type_address' => $rs->receive_location,
				'tel' => $rs->tel_mobile,
				'name' => $rs->name,
				'email' => $rs->email,
			]
		);
			if($rs->type == 4){//เติม Ai-Stockist
				$ai_pocket = DB::table('ai_pocket')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-Stockist',
				]);

			}

			if($rs->type == 5){
				$price_total = ($rs->price + $rs->shipping);
				$rs_log_gift = GiftVoucher::log_gift($price_total,$customer_id,$id);

				if($rs_log_gift['status'] != 'success'){
					DB::rollback(); 
					$resule = ['status'=>'fail','message'=>'rs_log_gift fail'];
					return $resule;
				}

			}

			foreach ($data as $value) {
				DB::table('order_items')->insert([
					'order_id'=>$id,
					'product_id'=>$value['id'],
					'product_name'=>$value['name'],
					'quantity'=>$value['quantity'],
					'list_price'=>$value['price'],
					'pv'=>$value['attributes']['pv'],
				]);
				Cart::session($rs->type)->remove($value['id']);
			}
			$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย'];
					//return $resule;
				//return redirect('product-history')->withSuccess('สั่งซื้อสินค้าเรียบร้อย');
		}else{
			$resule = ['status'=>'fail','message'=>'Payment submit Fail Address'];
					//return $resule;
				//return redirect('cart_payment')->withError('Payment submit Fail ( Address )');
		}
		DB::commit();
		return $resule;

	}catch(Exception $e) {
		DB::rollback();
		return redirect('product-history')->withError('Payment submit Fail');

	}
}


}
