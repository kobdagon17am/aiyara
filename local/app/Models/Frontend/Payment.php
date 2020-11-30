<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
use Cart;
class Payment extends Model
{
	public static function payment_uploadfile($rs){

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

				$id = DB::table('orders')->insertGetId(
					['code_order' => $code_order,
					'customer_id' => $customer_id,
					'vat'  => $rs->vat,
					'shipping'  => $rs->shipping,
					'price' => $rs->price,
					'price_vat' => $rs->price_vat,
					'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
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
				if($rs->type == 4){//เติม Ai-pocket
					$ai_pocket = DB::table('ai_pocket')->insert(
						['customer_id'=>$customer_id,
						'to_customer_id'=>$customer_id,
						'order_id'=>$id,
						'pv'=>$rs->pv_total,
						'type_id'  => $rs->type,
						'status' => 'panding',
						'detail' => 'Payment Add Ai-pocket',
					]);

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

				$id = DB::table('orders')->insertGetId(
					['code_order' => $code_order,
					'customer_id' => $customer_id,
					'vat'  => $rs->vat,
					'shipping'  => $rs->shipping,
					'price' => $rs->price,
					'price_vat' => $rs->price_vat,
					'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'type_id'  => $rs->type,
					'pay_type_id'  => $rs->pay_type,
					'orderstatus_id' => '2',
					'type_address' => $rs->receive_location,
					'tel' => $rs->tel_mobile,
					'name' => $rs->name,
					'email' => $rs->email,
				]
			);

			if($rs->type == 4){//เติม Ai-pocket
				$ai_pocket = DB::table('ai_pocket')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-pocket',
				]);

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

			$id = DB::table('orders')->insertGetId(
				['code_order' => $code_order,
				'customer_id' => $customer_id,
				'vat'  => $rs->vat,
				'shipping'  => $rs->shipping,
				'price' => $rs->price,
				'price_vat' => $rs->price_vat,
				'p_vat'  => $rs->p_vat,
				'pv_total'  => $rs->pv_total,
				'type_id'  => $rs->type,
				'pay_type_id'  => $rs->pay_type,
				'orderstatus_id' => '1',
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

			if($rs->type == 4){//เติม Ai-pocket
				$ai_pocket = DB::table('ai_pocket')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-pocket',
				]);

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

			$id = DB::table('orders')->insertGetId(
				['code_order' => $code_order,
				'customer_id' => $customer_id,
				'vat'  => $rs->vat,
				'shipping'  => $rs->shipping,
				'price' => $rs->price,
				'price_vat' => $rs->price_vat,
				'p_vat'  => $rs->p_vat,
				'pv_total'  => $rs->pv_total,
				'type_id'  => $rs->type,
				'pay_type_id'  => $rs->pay_type,
				'orderstatus_id' => '1',
				'type_address' => $rs->receive_location,
				'tel' => $rs->tel_mobile,
				'name' => $rs->name,
				'email' => $rs->email,
			]
		);
			if($rs->type == 4){//เติม Ai-pocket
				$ai_pocket = DB::table('ai_pocket')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-pocket',
				]);

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

			$id = DB::table('orders')->insertGetId(
				['code_order' => $code_order,
				'customer_id' => $customer_id,
				'vat'  => $rs->vat,
				'shipping'  => $rs->shipping,
				'price' => $rs->price,
				'price_vat' => $rs->price_vat,
				'p_vat'  => $rs->p_vat,
				'pv_total'  => $rs->pv_total,
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
			if($rs->type == 4){//เติม Ai-pocket
				$ai_pocket = DB::table('ai_pocket')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-pocket',
				]);

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

			$id = DB::table('orders')->insertGetId(
				['code_order' => $code_order,
				'customer_id' => $customer_id,
				'vat'  => $rs->vat,
				'shipping'  => $rs->shipping,
				'price' => $rs->price,
				'price_vat' => $rs->price_vat,
				'p_vat'  => $rs->p_vat,
				'pv_total'  => $rs->pv_total,
				'type_id'  => $rs->type,
				'pay_type_id'  => $rs->pay_type,
				'orderstatus_id' => '2',
				'type_address' => $rs->receive_location,
				'tel' => $rs->tel_mobile,
				'name' => $rs->name,
				'email' => $rs->email,
			]
		);
			if($rs->type == 4){//เติม Ai-pocket
				$ai_pocket = DB::table('ai_pocket')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-pocket',
				]);

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

			$id = DB::table('orders')->insertGetId(
				['code_order' => $code_order,
				'customer_id' => $customer_id,
				'vat'  => $rs->vat,
				'shipping'  => $rs->shipping,
				'price' => $rs->price,
				'price_vat' => $rs->price_vat,
				'p_vat'  => $rs->p_vat,
				'pv_total'  => $rs->pv_total,
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
			if($rs->type == 4){//เติม Ai-pocket
				$ai_pocket = DB::table('ai_pocket')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-pocket',
				]);

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

			$id = DB::table('orders')->insertGetId(
				['code_order' => $code_order,
				'customer_id' => $customer_id,
				'vat'  => $rs->vat,
				'shipping'  => $rs->shipping,
				'price' => $rs->price,
				'price_vat' => $rs->price_vat,
				'p_vat'  => $rs->p_vat,
				'pv_total'  => $rs->pv_total,
				'type_id'  => $rs->type,
				'pay_type_id'  => $rs->pay_type,
				'orderstatus_id' => '1',
				'type_address' => $rs->receive_location,
				'tel' => $rs->tel_mobile,
				'name' => $rs->name,
				'email' => $rs->email,
			]
		);
			if($rs->type == 4){//เติม Ai-pocket
				$ai_pocket = DB::table('ai_pocket')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-pocket',
				]);

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
 