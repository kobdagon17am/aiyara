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
use App\Models\Frontend\PaymentAiCash;
use App\Models\Frontend\CourseCheckRegis;
use App\Http\Controllers\Frontend\Fc\GiveawayController;

class CartPaymentController extends Controller
{
	public function index($type){
    $business_location_id = Auth::guard('c_user')->user()->business_location_id;

      $location = Location::location($business_location_id,1);
      $cartCollection = Cart::session($type)->getContent();
      $data=$cartCollection->toArray();
      $quantity = Cart::session($type)->getTotalQuantity();

		if($data){
			foreach ($data as $value) {
				$pv[] = $value['quantity'] *  $value['attributes']['pv'];
				if($type == '6'){
					$chek_course = CourseCheckRegis::cart_check_register($value['id'],$value['quantity']);
					if($chek_course['status'] == 'fail'){
						return redirect('cart/'.$type)->withError($chek_course['message']);
					}
				}
			}
			$pv_total = array_sum($pv);

		}else{
			$pv_total = 0;
      return redirect('product-list/'.$type)->withError('กรุณาเลือกสินค้าที่ต้องการ');

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
		$p_vat = $price*($vat/(100+$vat));

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

    $customer_pv = Auth::guard('c_user')->user()->pv;
    $check_giveaway = GiveawayController::check_giveaway($business_location_id,$type,$customer_pv,$pv_total);

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
		->where('id','=',Auth::guard('c_user')->user()->id)
		->first();

		$address = DB::table('customers_detail')
		->select('customers_detail.*','dataset_provinces.id as provinces_id','dataset_provinces.name_th as provinces_name','dataset_amphures.name_th as amphures_name','dataset_amphures.id as amphures_id','dataset_districts.id as district_id','dataset_districts.name_th as district_name')
		->leftjoin('dataset_provinces','dataset_provinces.id','=','customers_detail.province')
		->leftjoin('dataset_amphures','dataset_amphures.id','=','customers_detail.district')
		->leftjoin('dataset_districts','dataset_districts.id','=','customers_detail.district_sub')
		->where('customer_id','=',Auth::guard('c_user')->user()->id)
		->first();

		$address_card = DB::table('customers_address_card')
		->select('customers_address_card.*','dataset_provinces.id as provinces_id','dataset_provinces.name_th as card_provinces_name','dataset_amphures.name_th as card_amphures_name','dataset_amphures.id as card_amphures_id','dataset_districts.id as card_district_id','dataset_districts.name_th as card_district_name')
		->leftjoin('dataset_provinces','dataset_provinces.id','=','customers_address_card.card_province')
		->leftjoin('dataset_amphures','dataset_amphures.id','=','customers_address_card.card_district')
		->leftjoin('dataset_districts','dataset_districts.id','=','customers_address_card.card_district_sub')
		->where('customer_id','=',Auth::guard('c_user')->user()->id)
		->first();

		$provinces = DB::table('dataset_provinces')
		->select('*')
		->get();


		return view('frontend/product/cart_payment',compact('check_giveaway','customer','address','address_card','location','provinces','bill'));

	}

	public function payment_submit(Request $request){

		if($request->submit == 'upload'){
			if($request->type == '6'){
				$resule = PaymentCourse::payment_uploadfile($request);
			}elseif($request->type == '7') {
				$resule = PaymentAiCash::payment_uploadfile($request);

				if($resule['status'] == 'success'){
					return redirect('ai-cash')->withSuccess($resule['message']);
				}elseif($resule['status'] == 'fail') {
					return redirect('ai-cash')->withError($resule['message']);
				}else{
					return redirect('ai-cash'.$request->type)->withError('Data is Null');
				}

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
			}elseif($request->type == '7') {

				$resule = PaymentAiCash::payment_not_uploadfile($request);

				if($resule['status'] == 'success'){
					return redirect('ai-cash')->withSuccess($resule['message']);
				}elseif($resule['status'] == 'fail') {
					return redirect('ai-cash')->withError($resule['message']);
				}else{
					return redirect('ai-cash'.$request->type)->withError('Data is Null');
				}
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

			}elseif($request->type == '7') {
				$resule = PaymentAiCash::credit_card($request);

				if($resule['status'] == 'success'){
					return redirect('ai-cash')->withSuccess($resule['message']);
				}elseif($resule['status'] == 'fail') {
					return redirect('ai-cash')->withError($resule['message']);
				}else{
					return redirect('ai-cash'.$request->type)->withError('Data is Null');
				}
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
