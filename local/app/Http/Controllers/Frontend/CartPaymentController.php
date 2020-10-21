<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;

class CartPaymentController extends Controller
{
	public function index(){
		$cartCollection = Cart::getContent();
		$data=$cartCollection->toArray();

		$quantity = Cart::getTotalQuantity();
		if($data){
			foreach ($data as $value) {
				$pv[] = $value['quantity'] *  $value['attributes']['pv'];
			}
			$pv_total = array_sum($pv);
			$sent = 60;//ค่าส่ง

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

		return view('frontend/product/cart_payment',$bill);

	}

	 
}