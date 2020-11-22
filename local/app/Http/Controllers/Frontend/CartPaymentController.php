<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;
use Auth;
use App\Models\Frontend\Location;
use App\Models\Frontend\Payment;

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
		


        //ราคาสินค้า
		$price = Cart::session($type)->getTotal();

		//vatใน 7% 
		$p_vat = number_format($price*($vat/(100+$vat)),2);

		 //มูลค่าสินค้า
		$price_vat =  $price - $p_vat;

		$price_total = number_format($price + $shipping,2);


		$bill = array('vat'=>$vat,
			'shipping'=>$shipping,
			'price'=>$price,
			'p_vat'=>$p_vat,
			'price_vat'=>$price_vat,
			'price_total'=>$price_total,
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

		return view('frontend/product/cart_payment',compact('customer','location','bill'));

	}
	
	public function payment_submit(Request $request){

		if($request->submit == 'upload'){
			$resule = Payment::payment_uploadfile($request);
			if($resule['status'] == 'success'){
				return redirect('product-history')->withSuccess($resule['message']);
			}elseif($resule['status'] == 'fail') {
				return redirect('cart_payment/'.$request->type)->withError($resule['message']);
			}else{
				return redirect('cart_payment/'.$request->type)->withError('Data is Null');
			} 
		}elseif($request->submit == 'not_upload'){
			$resule = Payment::payment_not_uploadfile($request);
			if($resule['status'] == 'success'){
				return redirect('product-history')->withSuccess($resule['message']);
			}elseif($resule['status'] == 'fail') {
				return redirect('cart_payment/'.$request->type)->withError($resule['message']);
			}else{
				return redirect('cart_payment/'.$request->type)->withError('Data is Null');
			} 
		}elseif($request->submit == 'credit_card'){
			$resule = Payment::credit_card($request);
			if($resule['status'] == 'success'){
				return redirect('product-history')->withSuccess($resule['message']);
			}elseif($resule['status'] == 'fail') {
				return redirect('cart_payment/'.$request->type)->withError($resule['message']);
			}else{
				return redirect('cart_payment/'.$request->type)->withError('Data is Null');
			} 
		}elseif($request->submit == 'ai_cash'){
			$resule = Payment::ai_cash($request);
			if($resule['status'] == 'success'){
				return redirect('product-history')->withSuccess($resule['message']);
			}elseif($resule['status'] == 'fail') {
				return redirect('cart_payment/'.$request->type)->withError($resule['message']);
			}else{
				return redirect('cart_payment/'.$request->type)->withError('Data is Null');
			} 
		}else{
			return redirect('product-history')->withError('Payment submit Fail');
		}

	}

}