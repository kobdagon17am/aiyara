<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;
use Auth;
use App\Models\Frontend\Location;
use App\Models\Frontend\Payment;
use App\Models\Frontend\PaymentCourse;

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

		$price_total = $price + $shipping;

		if($type == 5){
			$data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
			$gv = $data_gv->sum_gv; 
			$gv_total = $gv - $price_total;

			if($gv_total < 0){
				$gv_total = 0;
				$price_total_type5 = abs($gv - $price_total);

			}else{
				$gv_total = $gv_total;
				$price_total_type5 = 0;
			}
			

		}else{
			$gv_total = null;
			$price_total_type5 = null;
		}
		

		$bill = array('vat'=>$vat,
			'shipping'=>$shipping,
			'price'=>$price,
			'p_vat'=>$p_vat,
			'price_vat'=>$price_vat,
			'price_total'=>$price_total,
			'pv_total'=>$pv_total,
			'data'=>$data,
			'quantity'=>$quantity,
			'gv_total'=>$gv_total,
			'price_total_type5'=>$price_total_type5,
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
		//dd($request->all());

		if($request->submit == 'upload'){
			if($request->type == '6'){
				$resule = PaymentCourse::payment_uploadfile($request);

			}else{
				$resule = Payment::payment_uploadfile($request);
			}
			
			if($resule['status'] == 'success'){
				return redirect('product-history')->withSuccess($resule['message']);
			}elseif($resule['status'] == 'fail') {
				return redirect('cart_payment/'.$request->type)->withError($resule['message']);
			}else{
				return redirect('cart_payment/'.$request->type)->withError('Data is Null');
			} 
		}elseif($request->submit == 'not_upload'){

			if($request->type == '6'){
				$resule = PaymentCourse::payment_not_uploadfile($request);

			}else{
				$resule = Payment::payment_not_uploadfile($request);
			}

			
			if($resule['status'] == 'success'){
				return redirect('product-history')->withSuccess($resule['message']);
			}elseif($resule['status'] == 'fail') {
				return redirect('cart_payment/'.$request->type)->withError($resule['message']);
			}else{
				return redirect('cart_payment/'.$request->type)->withError('Data is Null');
			} 
		}elseif($request->submit == 'credit_card'){

			if($request->type == '6'){ 
				$resule = PaymentCourse::credit_card($request);
				
			}else{
				$resule = Payment::credit_card($request);
			}

			
			 
			if($resule['status'] == 'success'){ 
				return redirect('product-history')->withSuccess($resule['message']);
			}elseif($resule['status'] == 'fail') {
				return redirect('cart_payment/'.$request->type)->withError($resule['message']);
			}else{
				return redirect('cart_payment/'.$request->type)->withError('Data is Null');
			} 
		}elseif($request->submit == 'ai_cash'){

			if($request->type == '6'){
				$resule = PaymentCourse::ai_cash($request);
			}else{
				$resule = Payment::ai_cash($request);
			}
			
			if($resule['status'] == 'success'){
				return redirect('product-history')->withSuccess($resule['message']);
			}elseif($resule['status'] == 'fail') {
				return redirect('cart_payment/'.$request->type)->withError($resule['message']);
			}else{
				return redirect('cart_payment/'.$request->type)->withError('Data is Null');
			} 
		}elseif($request->submit == 'gift_voucher'){
			$resule = Payment::gift_voucher($request);
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