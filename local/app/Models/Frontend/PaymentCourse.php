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
		$business_location_id = '1';

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

		$maxId = substr("0000000".$maxId, -7);
		$code_order = date('ymd').''.$maxId;

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
					'business_location_id' => $business_location_id,
					'orderstatus_id' => '2'
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

			$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย','order_id'=>$id];

				//return redirect('product-history')->withSuccess('สั่งซื้อสินค้าเรียบร้อย');

			DB::commit();

			return $resule;
		}catch(Exception $e) { 
			DB::rollback();
			return redirect('product-history')->withError('Payment submit Fail');

		}
	}

	public static function payment_not_uploadfile($rs){
		$business_location_id = '1';
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

		$maxId = substr("0000000".$maxId, -7);
		$code_order = date('ymd').''.$maxId;
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
					'business_location_id' => $business_location_id,
					'orderstatus_id' => '1'
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

			$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย','order_id'=>$id];
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
		$business_location_id = '1';

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

		$maxId = substr("0000000".$maxId, -7);
		$code_order = date('ymd').''.$maxId;
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
					'business_location_id' => $business_location_id,
					'orderstatus_id' => '7'
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

			$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย','order_id'=>$id];
				//return $resule;
				//return redirect('product-history')->withSuccess('สั่งซื้อสินค้าเรียบร้อย');

			if($resule['status'] = 'success'){
				$resulePv = Pvpayment::PvPayment_type_confirme($id,'99');
				$resuleRegisCourse = Couse_Event::couse_register($id,'99');

				if($resulePv['status'] == 'success' and $resuleRegisCourse['status'] == 'success'){

					DB::commit();
					return $resulePv;

				}else{
					DB::rollback();
					return redirect('product-history')->withError($resulePv);
				}
				
			}else{
				DB::rollback();
				return redirect('product-history')->withError('Payment submit Fail');

			}
		}catch(Exception $e){
			DB::rollback();
			return redirect('product-history')->withError('Payment submit Fail');

		}
	}

	public static function ai_cash($rs){
		$business_location_id = '1'; 

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

		$maxId = substr("0000000".$maxId, -7);
		$code_order = date('ymd').''.$maxId;
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
					'business_location_id' => $business_location_id,
					'orderstatus_id' => '7'
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

			$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย','order_id'=>$id];
				//return $resule;
				//return redirect('product-history')->withSuccess('สั่งซื้อสินค้าเรียบร้อย');

			if($resule['status'] = 'success'){
				$resulePv = Pvpayment::PvPayment_type_confirme($id,'99');
				$resuleRegisCourse = Couse_Event::couse_register($id,'99');

				if($resulePv['status'] == 'success' and $resuleRegisCourse['status'] == 'success'){

					DB::commit();
					return $resulePv;

				}else{
					DB::rollback();
					return redirect('product-history')->withError($resulePv);
				}
				
			}else{
				DB::rollback();
				return redirect('product-history')->withError('Payment submit Fail');

			}
		}catch(Exception $e){
			DB::rollback();
			return redirect('product-history')->withError('Payment submit Fail');

		}
	}


}
