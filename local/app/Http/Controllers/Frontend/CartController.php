<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;
use App\Models\Frontend\Pvpayment;

class CartController extends Controller
{
	public function cart($type){
		$cartCollection = Cart::session($type)->getContent();
		$data=$cartCollection->toArray();

		$quantity = Cart::session($type)->getTotalQuantity();
		if($data){
			foreach ($data as $value) {
				$pv[] = $value['quantity'] *  $value['attributes']['pv'];
			}
			$pv_total = array_sum($pv);
		}else{
			$pv_total = 0;
		}

		$price = Cart::session($type)->getTotal();
		$price_total = number_format($price,2);

		$bill = array('price_total'=>$price_total,
			'pv_total'=>$pv_total,
			'data'=>$data,
			'quantity'=>$quantity,
			'status'=>'success'
		);
		return view('frontend/product/cart',compact('bill','type'));
	}


	public function cart_delete(Request $request){
		//dd($request->all());
		Cart::session($request->type)->remove($request->data_id);
		return redirect('cart/'.$request->type)->withSuccess('Deleted Success');
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

			}else{
				$pv_total = 0;
			}

			$price = Cart::session($type)->getTotal();
			$price_total = number_format($price,2);

			$bill = array('price_total'=>$price_total,
				'pv_total'=>$pv_total,
				'count_item'=>$pv_total,
				'data'=>$data,
				'quantity'=>$quantity,
				'status'=>'success'
			);

			return $bill;

		}else{
			$bill = array('status'=>'fail');
			return $bill;

		}

	}

	public function payment_test_type_1(){
		$order_id = '52';//order_id
		$admin_id = '99';//admin_id 
		$resule = Pvpayment::PvPayment_type_1_success($order_id,$admin_id);
		dd($resule);
	}
}