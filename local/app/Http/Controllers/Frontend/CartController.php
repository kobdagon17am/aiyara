<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;

class CartController extends Controller
{
	public function index(){

		$sent = 60;//ค่าส่ง

		$cartCollection = Cart::getContent();
		$data=$cartCollection->toArray();
		$price = Cart::getTotal();
		$price_total = number_format($price,2);
		$price_total_sent = $price + $sent;

		$quantity = Cart::getTotalQuantity();
		if($data){
			foreach ($data as $value) {
				$pv[] = $value['quantity'] *  $value['attributes']['pv'];
			}
			$pv_total = array_sum($pv);

		}else{
			$pv_total = 0;
		}


		$bill = array('price_total'=>$price_total ,
			'pv_total'=>$pv_total,
			'data'=>$data ,
			'quantity'=>$quantity,
			'price_total_sent'=>$price_total_sent,
			'sent'=>$sent
		);


		return view('frontend/product/cart',$bill);

	}

	public function cart_delete(Request $request){
		//dd($request->all());
		Cart::remove($request->data_id);
		return redirect('cart')->withSuccess('Deleted Success');
	}
}