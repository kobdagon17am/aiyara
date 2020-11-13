<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;

class CartController extends Controller
{
	public function cart_1(){

		$cartCollection = Cart::session(1)->getContent();
		$data=$cartCollection->toArray();

		$quantity = Cart::session(1)->getTotalQuantity();
		if($data){
			foreach ($data as $value) {
				$pv[] = $value['quantity'] *  $value['attributes']['pv'];
			}
			$pv_total = array_sum($pv);
			$sent = 100;//ค่าส่ง

		}else{
			$pv_total = 0;
			$sent = 0;//ค่าส่ง
		}

		$price = Cart::session(1)->getTotal();
		$price_total = number_format($price,2);
		$price_total_sent = $price + $sent;

		$bill = array('price_total'=>$price_total,
			'pv_total'=>$pv_total,
			'data'=>$data,
			'quantity'=>$quantity,
			'price_total_sent'=>number_format($price_total_sent,2),
			'sent'=>number_format($sent,2),
			'status'=>'success'
		);

		return view('frontend/product/cart-1',$bill);

	}

	public function cart_2(){

		$cartCollection = Cart::session(2)->getContent();
		$data=$cartCollection->toArray();

		$quantity = Cart::session(2)->getTotalQuantity();
		if($data){
			foreach ($data as $value) {
				$pv[] = $value['quantity'] *  $value['attributes']['pv'];
			}
			$pv_total = array_sum($pv);
			$sent = 100;//ค่าส่ง

		}else{
			$pv_total = 0;
			$sent = 0;//ค่าส่ง
		}

		$price = Cart::session(2)->getTotal();
		$price_total = number_format($price,2);
		$price_total_sent = $price + $sent;

		$bill = array('price_total'=>$price_total,
			'pv_total'=>$pv_total,
			'data'=>$data,
			'quantity'=>$quantity,
			'price_total_sent'=>number_format($price_total_sent,2),
			'sent'=>number_format($sent,2),
			'status'=>'success'
		);

		return view('frontend/product/cart-2',$bill);

	}

	public function cart_3(){

		$cartCollection = Cart::session(3)->getContent();
		$data=$cartCollection->toArray();

		$quantity = Cart::session(3)->getTotalQuantity();
		if($data){
			foreach ($data as $value) {
				$pv[] = $value['quantity'] *  $value['attributes']['pv'];
			}
			$pv_total = array_sum($pv);
			$sent = 100;//ค่าส่ง

		}else{
			$pv_total = 0;
			$sent = 0;//ค่าส่ง
		}

		$price = Cart::session(3)->getTotal();
		$price_total = number_format($price,2);
		$price_total_sent = $price + $sent;

		$bill = array('price_total'=>$price_total,
			'pv_total'=>$pv_total,
			'data'=>$data,
			'quantity'=>$quantity,
			'price_total_sent'=>number_format($price_total_sent,2),
			'sent'=>number_format($sent,2),
			'status'=>'success'
		);

		return view('frontend/product/cart-3',$bill);

	}
	public function cart_4(){

		$cartCollection = Cart::session(4)->getContent();
		$data=$cartCollection->toArray();

		$quantity = Cart::session(4)->getTotalQuantity();
		if($data){
			foreach ($data as $value) {
				$pv[] = $value['quantity'] *  $value['attributes']['pv'];
			}
			$pv_total = array_sum($pv);
			$sent = 100;//ค่าส่ง

		}else{
			$pv_total = 0;
			$sent = 0;//ค่าส่ง
		}

		$price = Cart::session(4)->getTotal();
		$price_total = number_format($price,2);
		$price_total_sent = $price + $sent;

		$bill = array('price_total'=>$price_total,
			'pv_total'=>$pv_total,
			'data'=>$data,
			'quantity'=>$quantity,
			'price_total_sent'=>number_format($price_total_sent,2),
			'sent'=>number_format($sent,2),
			'status'=>'success'
		);

		return view('frontend/product/cart-4',$bill);

	}

	public function cart_5(){

		$cartCollection = Cart::session(5)->getContent();
		$data=$cartCollection->toArray();

		$quantity = Cart::session(5)->getTotalQuantity();
		if($data){
			foreach ($data as $value) {
				$pv[] = $value['quantity'] *  $value['attributes']['pv'];
			}
			$pv_total = array_sum($pv);
			$sent = 100;//ค่าส่ง

		}else{
			$pv_total = 0;
			$sent = 0;//ค่าส่ง
		}

		$price = Cart::session(5)->getTotal();
		$price_total = number_format($price,2);
		$price_total_sent = $price + $sent;

		$bill = array('price_total'=>$price_total,
			'pv_total'=>$pv_total,
			'data'=>$data,
			'quantity'=>$quantity,
			'price_total_sent'=>number_format($price_total_sent,2),
			'sent'=>number_format($sent,2),
			'status'=>'success'
		);

		return view('frontend/product/cart-5',$bill);

	}

	public function cart_6(){

		$cartCollection = Cart::session(6)->getContent();
		$data=$cartCollection->toArray();

		$quantity = Cart::session(6)->getTotalQuantity();
		if($data){
			foreach ($data as $value) {
				$pv[] = $value['quantity'] *  $value['attributes']['pv'];
			}
			$pv_total = array_sum($pv);
			$sent = 100;//ค่าส่ง

		}else{
			$pv_total = 0;
			$sent = 0;//ค่าส่ง
		}

		$price = Cart::getTotal();
		$price_total = number_format($price,2);
		$price_total_sent = $price + $sent;

		$bill = array('price_total'=>$price_total,
			'pv_total'=>$pv_total,
			'data'=>$data,
			'quantity'=>$quantity,
			'price_total_sent'=>number_format($price_total_sent,2),
			'sent'=>number_format($sent,2),
			'status'=>'success'
		);

		return view('frontend/product/cart-6',$bill);

	}

	public function cart_delete(Request $request){
		//dd($request->all());
		Cart::session($request->type)->remove($request->data_id);
		return redirect('cart-'.$request->type)->withSuccess('Deleted Success');
	}

	public function edit_item(Request $request){
		$type = $request->type;

		if($request->item_id){
			Cart::session($type)->update($request->item_id,array(
				'quantity' => array (
					'relative' => false ,
					'value' => $request->qty
				),
			));
			$cartCollection = Cart::session($type)->getContent();
			$data=$cartCollection->toArray();

			$quantity = Cart::session($type)->getTotalQuantity();
			if($data){
				foreach ($data as $value) {
					$pv[] = $value['quantity'] *  $value['attributes']['pv'];
				}
				$pv_total = array_sum($pv);
			$sent = 100;//ค่าส่ง

		}else{
			$pv_total = 0;
			$sent = 0;//ค่าส่ง
		}

		$price = Cart::session($type)->getTotal();
		$price_total = number_format($price,2);
		$price_total_sent = $price + $sent;

		$bill = array('price_total'=>$price_total,
			'pv_total'=>$pv_total,
			'count_item'=>$pv_total,
			'data'=>$data,
			'quantity'=>$quantity,
			'price_total_sent'=>number_format($price_total_sent,2),
			'sent'=>number_format($sent,2),
			'status'=>'success'
		);

		return $bill;

	}else{
		$bill = array('status'=>'fail');
		return $bill;

	}

}
}