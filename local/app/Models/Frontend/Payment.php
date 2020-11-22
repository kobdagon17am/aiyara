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
		$code_order = date('Ymdhis').''.Auth::guard('c_user')->user()->id;
		try{
			if($rs->receive == 'sent_address'){
				$address = 'บ้านเลขที่.'.$rs->house_no.' หมู่บ้าน/อาคาร.'.$rs->house_name.' หมู่.'.$rs->moo.' ตรอก/ซอย.'.$rs->soi.' เขต/อำเภอ.'.$rs->district.' แขวง/ตำบล.'.$rs->district_sub.' ถนน.'.$rs->road.' จังหวัด.'.$rs->province.' รหัสไปษณีย์.'.$rs->zipcode;

				$cartCollection = Cart::session($rs->type)->getContent();
				$data = $cartCollection->toArray();
				$total = Cart::session($rs->type)->getTotal();

				$id = DB::table('orders')->insertGetId(
					['code_order' => $code_order,
					'customer_id' => Auth::guard('c_user')->user()->id,
					'vat'  => $rs->vat,
					'shipping'  => $rs->shipping,
					'price' => $rs->price,
					'price_vat' => $rs->price_vat,
					'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'type_id'  => $rs->type,
					'pay_type_id'  => $rs->pay_type,
					'orderstatus_id' => '2',
					'address' => $address,
					'type_address' => 0,
					'tel' => $rs->tel_mobile,
					'name' => $rs->name,
				]
			);

				$file_slip = $rs->file_slip;

				if(isset($file_slip)){
					$url='local/public/files_slip/'.date('Ym');

					$f_name = date('YmdHis').'_'.Auth::guard('c_user')->user()->id.'.'.$file_slip->getClientOriginalExtension();
					if($file_slip->move($url,$f_name)){
						DB::table('payment_slip')
						->insert(['customer_id'=>Auth::guard('c_user')->user()->id,'url'=>$url,'file'=>$f_name,'order_id'=>$id]);

					}
				}

				foreach ($data as $value) {
					DB::table('order_items')->insert([
						'order_id'=>$id,
						'product_id'=>$value['id'],
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
					'customer_id' => Auth::guard('c_user')->user()->id,
					'vat'  => $rs->vat,
					'shipping'  => $rs->shipping,
					'price' => $rs->price,
					'price_vat' => $rs->price_vat,
					'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'type_id'  => $rs->type,
					'pay_type_id'  => $rs->pay_type,
					'orderstatus_id' => '2',
					'address' => 'Receive office',
					'type_address' => $rs->receive_location,
					'tel' => $rs->tel_mobile,
					'name' => $rs->name,
				]
			);

				$file_slip = $rs->file_slip;

				if(isset($file_slip)){
					$url='local/public/files_slip/'.date('Ym');

					$f_name = date('YmdHis').'_'.Auth::guard('c_user')->user()->id.'.'.$file_slip->getClientOriginalExtension();
					if($file_slip->move($url,$f_name)){
						DB::table('payment_slip')
						->insert(['customer_id'=>Auth::guard('c_user')->user()->id,'url'=>$url,'file'=>$f_name,'order_id'=>$id]);

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

		$code_order = date('Ymdhis').''.Auth::guard('c_user')->user()->id;
		try{

			if($rs->receive == 'sent_address'){
				$address = 'บ้านเลขที่.'.$rs->house_no.' หมู่บ้าน/อาคาร.'.$rs->house_name.' หมู่.'.$rs->moo.' ตรอก/ซอย.'.$rs->soi.' เขต/อำเภอ.'.$rs->district.' แขวง/ตำบล.'.$rs->district_sub.' ถนน.'.$rs->road.' จังหวัด.'.$rs->province.' รหัสไปษณีย์.'.$rs->zipcode;

				$cartCollection = Cart::session($rs->type)->getContent();
				$data=$cartCollection->toArray();
				$total = Cart::session($rs->type)->getTotal();

				$id = DB::table('orders')->insertGetId(
					['code_order' => $code_order,
					'customer_id' => Auth::guard('c_user')->user()->id,
					'vat'  => $rs->vat,
					'shipping'  => $rs->shipping,
					'price' => $rs->price,
					'price_vat' => $rs->price_vat,
					'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'type_id'  => $rs->type,
					'pay_type_id'  => $rs->pay_type,
					'orderstatus_id' => '1',
					'address' => $address,
					'type_address' => 0,
					'tel' => $rs->tel_mobile,
					'name' => $rs->name,
				]
			);

				foreach ($data as $value) {

					DB::table('order_items')->insert([
						'order_id'=>$id,
						'product_id'=>$value['id'],
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
					'customer_id' => Auth::guard('c_user')->user()->id,
					'vat'  => $rs->vat,
					'shipping'  => $rs->shipping,
					'price' => $rs->price,
					'price_vat' => $rs->price_vat,
					'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'type_id'  => $rs->type,
					'pay_type_id'  => $rs->pay_type,
					'orderstatus_id' => '1',
					'address' => 'Receive office',
					'type_address' => $rs->receive_location,
					'tel' => $rs->tel_mobile,
					'name' => $rs->name,
				]
			);

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
		$code_order = date('Ymdhis').''.Auth::guard('c_user')->user()->id;
		try{
			if($rs->receive == 'sent_address'){
				$address = 'บ้านเลขที่.'.$rs->house_no.' หมู่บ้าน/อาคาร.'.$rs->house_name.' หมู่.'.$rs->moo.' ตรอก/ซอย.'.$rs->soi.' เขต/อำเภอ.'.$rs->district.' แขวง/ตำบล.'.$rs->district_sub.' ถนน.'.$rs->road.' จังหวัด.'.$rs->province.' รหัสไปษณีย์.'.$rs->zipcode;

				$cartCollection = Cart::session($rs->type)->getContent();
				$data=$cartCollection->toArray();
				$total = Cart::session($rs->type)->getTotal();

				$id = DB::table('orders')->insertGetId(
					['code_order' => $code_order,
					'customer_id' => Auth::guard('c_user')->user()->id,
					'vat'  => $rs->vat,
					'shipping'  => $rs->shipping,
					'price' => $rs->price,
					'price_vat' => $rs->price_vat,
					'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'type_id'  => $rs->type,
					'pay_type_id'  => $rs->pay_type,
					'orderstatus_id' => '2',
					'address' => $address,
					'type_address' => 0,
					'tel' => $rs->tel_mobile,
					'name' => $rs->name,
				]
			);

				foreach ($data as $value) {

					DB::table('order_items')->insert([
						'order_id'=>$id,
						'product_id'=>$value['id'],
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
					'customer_id' => Auth::guard('c_user')->user()->id,
					'vat'  => $rs->vat,
					'shipping'  => $rs->shipping,
					'price' => $rs->price,
					'price_vat' => $rs->price_vat,
					'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'type_id'  => $rs->type,
					'pay_type_id'  => $rs->pay_type,
					'orderstatus_id' => '2',
					'address' => 'Receive office',
					'type_address' => $rs->receive_location,
					'tel' => $rs->tel_mobile,
					'name' => $rs->name,
				]
			);

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
		$code_order = date('Ymdhis').''.Auth::guard('c_user')->user()->id;
		try{
			if($rs->receive == 'sent_address'){
				$address = 'บ้านเลขที่.'.$rs->house_no.' หมู่บ้าน/อาคาร.'.$rs->house_name.' หมู่.'.$rs->moo.' ตรอก/ซอย.'.$rs->soi.' เขต/อำเภอ.'.$rs->district.' แขวง/ตำบล.'.$rs->district_sub.' ถนน.'.$rs->road.' จังหวัด.'.$rs->province.' รหัสไปษณีย์.'.$rs->zipcode;

				$cartCollection = Cart::session($rs->type)->getContent();
				$data=$cartCollection->toArray();
				$total = Cart::session($rs->type)->getTotal();

				$id = DB::table('orders')->insertGetId(
					['code_order' => $code_order,
					'customer_id' => Auth::guard('c_user')->user()->id,
					'vat'  => $rs->vat,
					'shipping'  => $rs->shipping,
					'price' => $rs->price,
					'price_vat' => $rs->price_vat,
					'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'type_id'  => $rs->type,
					'pay_type_id'  => $rs->pay_type,
					'orderstatus_id' => '2',
					'address' => $address,
					'type_address' => 0,
					'tel' => $rs->tel_mobile,
					'name' => $rs->name,
				]
			);

				foreach ($data as $value) {

					DB::table('order_items')->insert([
						'order_id'=>$id,
						'product_id'=>$value['id'],
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
					'customer_id' => Auth::guard('c_user')->user()->id,
					'vat'  => $rs->vat,
					'shipping'  => $rs->shipping,
					'price' => $rs->price,
					'price_vat' => $rs->price_vat,
					'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'type_id'  => $rs->type,
					'pay_type_id'  => $rs->pay_type,
					'orderstatus_id' => '1',
					'address' => 'Receive office',
					'type_address' => $rs->receive_location,
					'tel' => $rs->tel_mobile,
					'name' => $rs->name,
				]
			);

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
