<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;
use Auth;
use App\Models\Frontend\Location;

class CartPaymentController extends Controller
{
	public function index($type){
		$location = Location::location(1,1);
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

		$shipping = DB::table('dataset_shipping')
		->where('location_id','=','1')
		->first();
		
		$vat = DB::table('dataset_vat')
		->where('location_id','=','1')
		->first();


		$vat = $vat->txt_value;
		$shipping = $shipping->price_shipping;
		
		$price = Cart::session($type)->getTotal();
		$price_vat = $price*($vat/100);
		$price_vat = number_format($price_vat,2);

		$price_vat_shipping = $price + $price_vat + $shipping;
		$price_vat_shipping = number_format($price_vat_shipping,2);

		$bill = array('vat'=>$vat,
			'shipping'=>$shipping,
			'price'=>$price,
			'price_vat'=>$price_vat,
			'price_vat_shipping'=>$price_vat_shipping,
			'pv_total'=>$pv_total,
			'data'=>$data,
			'quantity'=>$quantity,
			'type'=>$type,
			'status'=>'success'
		);


		$customer = DB::table('customers')
		->leftjoin('customers_detail','customers.id','=','customers_detail.customer_id')
		->where('customer_id','=',Auth::guard('c_user')->user()->id)
		->first();
		//dd($customer);

		return view('frontend/product/cart_payment',compact('customer','location'),$bill);

	}
	
	public function payment_submit(Request $request){
		try {
			$code_order = date('Ymdhis').''.Auth::guard('c_user')->user()->id;

			if($request->receive == 'sent_address'){
				$address = 'บ้านเลขที่.'.$request->house_no.' หมู่บ้าน/อาคาร.'.$request->house_name.' หมู่.'.$request->moo.' ตรอก/ซอย.'.$request->soi.' เขต/อำเภอ.'.$request->district.' แขวง/ตำบล.'.$request->district_sub.' ถนน.'.$request->road.' จังหวัด.'.$request->province.' รหัสไปษณีย์.'.$request->zipcode;

				$cartCollection = Cart::session($request->type)->getContent();
				$data=$cartCollection->toArray();
				$total = Cart::session($request->type)->getTotal();
				


				$id = DB::table('orders')->insertGetId(
					['code_order' => $code_order,
					'customer_id' => Auth::guard('c_user')->user()->id,
					'vat'  => $request->vat,
					'shipping'  => $request->shipping,
					'price' => $request->price,
					'price_vat' => $request->price_vat,
					'price_vat_shipping'  => $request->price_vat_shipping,
					'pv_total'  => $request->pv_total,
					'type'  => $request->type,
					'pay_type'  => $request->pay_type,
					'order_status' => '1',
					'address' => $address,
					'type_address' => 0,
					'tel' => $request->tel_mobile,
					'name' => $request->name,
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
					
					Cart::session($request->type)->remove($value['id']);
				}

				return redirect('product-history')->withSuccess('สั่งซื้อสินค้าเรียบร้อย');
			}elseif($request->receive == 'receive_office') {

				$cartCollection = Cart::session($request->type)->getContent();
				$data=$cartCollection->toArray();
				$total = Cart::session($request->type)->getTotal();

				$id = DB::table('orders')->insertGetId(
					['code_order' => $code_order,
					'customer_id' => Auth::guard('c_user')->user()->id,
					'vat'  => $request->vat,
					'shipping'  => $request->shipping,
					'price' => $request->price,
					'price_vat' => $request->price_vat,
					'price_vat_shipping'  => $request->price_vat_shipping,
					'pv_total'  => $request->pv_total,
					'type'  => $request->type,
					'pay_type'  => $request->pay_type,
					'order_status' => '1',
					'address' => 'Receive office',
					'type_address' => $request->receive_location,
					'tel' => $request->tel_mobile,
					'name' => $request->name,
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
					Cart::session($request->type)->remove($value['id']);
				}
				return redirect('product-history')->withSuccess('สั่งซื้อสินค้าเรียบร้อย');
			}else{
				return redirect('cart_payment')->withError('Payment submit Fail ( Address )');
			}
			return redirect('cart_payment')->withSuccess('Success');
		}catch(Exception $e) {
			return redirect('cart_payment')->withError('Payment submit Fail');

		}

	}

}