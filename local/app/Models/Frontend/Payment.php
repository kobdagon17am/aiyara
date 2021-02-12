<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
use Cart;
use App\Models\Frontend\GiftVoucher;
use App\Models\Frontend\Pvpayment;
use App\Models\Frontend\PaymentSentAddressOrder;
use App\Models\Frontend\PaymentAddProduct;

class Payment extends Model
{
	public static function payment_uploadfile($rs){
		$business_location_id = '1'; 

		DB::BeginTransaction();
		$customer_id = Auth::guard('c_user')->user()->id;
		// เลขใบเสร็จ
		// ปีเดือน[รันเลข]
		// 2020110 00001

		$id = DB::table('db_orders')
		->select('id')
		->orderby('id','desc')
		->first();
		if ($id) {
			$maxId  = $id->id +1;
		}else{
			$maxId = 1;
		}
		
		
		$maxId = substr("0000000".$maxId, -7);
		$code_order = date('ymd').''.$maxId;

		try{
			$cartCollection = Cart::session($rs->type)->getContent();
			$data = $cartCollection->toArray();
			$total = Cart::session($rs->type)->getTotal();

			if($rs->type == 5){
				$data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
				$gv_customer = $data_gv->sum_gv;

				$gv_total = $gv_customer - ($rs->price + $rs->shipping);

				if($gv_total < 0){
					$gv = $gv_customer;
					$price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));

				}else{
					$gv = $rs->price + $rs->shipping;
					$price_remove_gv = 0;
				}
			}else{
				$gv = null;
				$price_remove_gv = null;

			}

			$orderstatus_id = 2;
			$rs_update_rontstore_and_address = PaymentSentAddressOrder::update_rontstore_and_address($rs,$code_order,$customer_id,$business_location_id,$orderstatus_id,$gv,$price_remove_gv);

			if($rs_update_rontstore_and_address['status'] == 'fail'){
				DB::rollback(); 
				return $rs_update_rontstore_and_address;
			}else{
				$id= $rs_update_rontstore_and_address['id'];
			}

				if($rs->type == 4){//เติม Ai-Stockist
					$ai_pocket = DB::table('ai_stockist')->insert(
						['customer_id'=>$customer_id,
						'to_customer_id'=>$customer_id,
						'order_id_fk'=>$rs_update_rontstore_and_address['id'],
						'pv'=>$rs->pv_total,
						'type_id'  => $rs->type,
						'status' => 'panding',
						'detail' => 'Payment Add Ai-Stockist',
					]);
				}

				if($rs->type == 5){
					$price_total = ($rs->price + $rs->shipping);
					$rs_log_gift = GiftVoucher::log_gift($price_total,$customer_id,$id);

					if($rs_log_gift['status'] != 'success'){
						DB::rollback(); 
						$resule = ['status'=>'fail','message'=>'rs_log_gift fail'];
						return $resule;
					}

				}

				$file_slip = $rs->file_slip;
				if(isset($file_slip)){
					$url='local/public/files_slip/'.date('Ym');

					$f_name = date('YmdHis').'_'.$customer_id.'.'.$file_slip->getClientOriginalExtension();
					if($file_slip->move($url,$f_name)){
						DB::table('payment_slip')
						->insert(['customer_id'=>$customer_id,'url'=>$url,'file'=>$f_name,'order_id'=>$id]);
					}
				}

				$resule = PaymentAddProduct::payment_add_product($id,$customer_id,$rs->type);

				if($resule['status'] == 'success'){
					DB::commit();
					return $resule;

				}else{
					DB::rollback();
					return $resule;
				}
			}catch(Exception $e) { 
				DB::rollback();
				return $e;

			}
		}

		public static function payment_not_uploadfile($rs){
			
			$business_location_id = '1';
			DB::BeginTransaction();
			$customer_id = Auth::guard('c_user')->user()->id;
			$id = DB::table('db_orders')
			->select('id')
			->orderby('id','desc')
			->first();

			if ($id) {
				$maxId  = $id->id +1;
			}else{
				$maxId = 1;
			}
			$maxId = substr("0000000".$maxId, -7);
			$code_order = date('ymd').''.$maxId;

			try{
				$cartCollection = Cart::session($rs->type)->getContent();
				$data=$cartCollection->toArray();
				$total = Cart::session($rs->type)->getTotal();

				if($rs->type == 5){
					$data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
					$gv_customer = $data_gv->sum_gv;

					$gv_total = $gv_customer - ($rs->price + $rs->shipping);

					if($gv_total < 0){
						$gv = $gv_customer;
						$price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));

					}else{
						$gv = $rs->price + $rs->shipping;
						$price_remove_gv = 0;
					}
				}else{
					$gv = null;
					$price_remove_gv = null;

				}

				$orderstatus_id = 1;
				$rs_update_rontstore_and_address = PaymentSentAddressOrder::update_rontstore_and_address($rs,$code_order,$customer_id,$business_location_id,$orderstatus_id,$gv,$price_remove_gv);

				if($rs_update_rontstore_and_address['status'] == 'fail'){
					DB::rollback(); 
					return $rs_update_rontstore_and_address;
				}else{
					$id= $rs_update_rontstore_and_address['id'];
				}


			if($rs->type == 4){//เติม Ai-Stockist
				$ai_pocket = DB::table('ai_stockist')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id_fk'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-Stockist',
				]);
			}

			if($rs->type == 5){
				$price_total = ($rs->price + $rs->shipping);
				$rs_log_gift = GiftVoucher::log_gift($price_total,$customer_id,$id);

				if($rs_log_gift['status'] != 'success'){
					DB::rollback(); 
					$resule = ['status'=>'fail','message'=>'rs_log_gift fail'];
					return $resule;
				}

			}

			$resule = PaymentAddProduct::payment_add_product($id,$customer_id,$rs->type);

			//$resule = ['status'=>'success','message'=>'สั่งซื้อสินค้าเรียบร้อย','order_id'=>$id];
					//return $resule;
			if($resule['status'] == 'success'){
				DB::commit();
				return $resule;
			}else{

				DB::rollback();
				return $resule;
			}
		}catch(Exception $e) {
			DB::rollback();
			return $e;

		}
	}

	public static function credit_card($rs){

		$business_location_id = '1';
		DB::BeginTransaction();
		$customer_id = Auth::guard('c_user')->user()->id;
		$id = DB::table('db_orders')
		->select('id')
		->orderby('id','desc')
		->first();

		if ($id) {
			$maxId  = $id->id +1;
		}else{
			$maxId = 1;
		}
		$maxId = substr("0000000".$maxId, -7);
		$code_order = date('ymd').''.$maxId;
		try{

			$cartCollection = Cart::session($rs->type)->getContent();
			$data=$cartCollection->toArray();
			$total = Cart::session($rs->type)->getTotal();

			if($rs->type == 5){
				$data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
				$gv_customer = $data_gv->sum_gv;

				$gv_total = $gv_customer - ($rs->price + $rs->shipping);

				if($gv_total < 0){
					$gv = $gv_customer;
					$price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));

				}else{
					$gv = $rs->price + $rs->shipping;
					$price_remove_gv = 0;
				}
			}else{
				$gv = null;
				$price_remove_gv = null;

			}

			$orderstatus_id = 5;
			$rs_update_rontstore_and_address = PaymentSentAddressOrder::update_rontstore_and_address($rs,$code_order,$customer_id,$business_location_id,$orderstatus_id,$gv,$price_remove_gv);
			

			if($rs_update_rontstore_and_address['status'] == 'fail'){
				DB::rollback(); 
				return $rs_update_rontstore_and_address;
			}else{
				$id= $rs_update_rontstore_and_address['id'];
			}

			if($rs->type == 4){//เติม Ai-Stockist
				$ai_pocket = DB::table('ai_stockist')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id_fk'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-Stockist',
				]);

			}
			if($rs->type == 5){
				$price_total = ($rs->price + $rs->shipping);
				$rs_log_gift = GiftVoucher::log_gift($price_total,$customer_id,$id);

				if($rs_log_gift['status'] != 'success'){
					DB::rollback(); 
					$resule = ['status'=>'fail','message'=>'rs_log_gift fail'];
					return $resule;
				}

			}

			$resule = PaymentAddProduct::payment_add_product($id,$customer_id,$rs->type);
					 
			if($resule['status'] == 'success'){
				$resulePv = Pvpayment::PvPayment_type_confirme($id,'99');
				if($resulePv['status'] == 'success'){
					DB::commit();
					return $resulePv;

				}else{
					DB::rollback();
					return $resulePv;
				}

			}else{
				DB::rollback();
				return $resule;

			}
		}catch(Exception $e) {
			DB::rollback();
			return $e;

		}
	}

	public static function ai_cash($rs){
		$business_location_id = '1';
		DB::BeginTransaction();
		$customer_id = Auth::guard('c_user')->user()->id;
		$id = DB::table('db_orders')
		->select('id')
		->orderby('id','desc')
		->first();

		if ($id) {
			$maxId  = $id->id +1;
		}else{
			$maxId = 1;
		}
		$maxId = substr("0000000".$maxId, -7);
		$code_order = date('ymd').''.$maxId;
		try{


			$cartCollection = Cart::session($rs->type)->getContent();
			$data=$cartCollection->toArray();
			$total = Cart::session($rs->type)->getTotal();

			if($rs->type == 5){
				$data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
				$gv_customer = $data_gv->sum_gv;

				$gv_total = $gv_customer - ($rs->price + $rs->shipping);

				if($gv_total < 0){
					$gv = $gv_customer;
					$price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));

				}else{
					$gv = $rs->price + $rs->shipping;
					$price_remove_gv = 0;
				}
			}else{
				$gv = null;
				$price_remove_gv = null;

			}

			$orderstatus_id = 5;
			$rs_update_rontstore_and_address = PaymentSentAddressOrder::update_rontstore_and_address($rs,$code_order,$customer_id,$business_location_id,$orderstatus_id,$gv,$price_remove_gv);

			if($rs_update_rontstore_and_address['status'] == 'fail'){
				DB::rollback(); 
				return $rs_update_rontstore_and_address;
			}else{
				$id= $rs_update_rontstore_and_address['id'];
			}

			if($rs->type == 4){//เติม Ai-Stockist
				$ai_pocket = DB::table('ai_stockist')->insert(
					['customer_id'=>$customer_id,
					'to_customer_id'=>$customer_id,
					'order_id_fk'=>$id,
					'pv'=>$rs->pv_total,
					'type_id'  => $rs->type,
					'status' => 'panding',
					'detail' => 'Payment Add Ai-Stockist',
				]);

			}

			if($rs->type == 5){
				$price_total = ($rs->price + $rs->shipping);
				$rs_log_gift = GiftVoucher::log_gift($price_total,$customer_id,$id);

				if($rs_log_gift['status'] != 'success'){
					DB::rollback(); 
					$resule = ['status'=>'fail','message'=>'rs_log_gift fail'];
					return $resule;
				}

			}

			$resule = PaymentAddProduct::payment_add_product($id,$customer_id,$rs->type);

			if($resule['status'] == 'success'){
				$resulePv = Pvpayment::PvPayment_type_confirme($id,'99');
				if($resulePv['status'] == 'success'){
					DB::commit();
					return $resulePv;

				}else{
					DB::rollback();
					return $resulePv;
				}

			}else{
				DB::rollback();
				return $resule;

			}

		}catch(Exception $e) {
			DB::rollback();
			return $e ;

		}
	}

	public static function gift_voucher($rs){
		$business_location_id = '1';
		DB::BeginTransaction();
		$customer_id = Auth::guard('c_user')->user()->id;
		$id = DB::table('db_orders')
		->select('id')
		->orderby('id','desc')
		->first();

		if ($id) {
			$maxId  = $id->id +1;
		}else{
			$maxId = 1;
		}
		$maxId = substr("0000000".$maxId, -7);
		$code_order = date('ymd').''.$maxId;

		$data_gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
		$gv_customer = $data_gv->sum_gv;

		try{

			$cartCollection = Cart::session($rs->type)->getContent();
			$data=$cartCollection->toArray();
			$total = Cart::session($rs->type)->getTotal();

			$gv_total = $gv_customer - ($rs->price + $rs->shipping);

			if($gv_total < 0){
				$gv = $gv_customer;
				$price_remove_gv = abs($gv_customer - ($rs->price + $rs->shipping));

			}else{
				$gv = $rs->price + $rs->shipping;
				$price_remove_gv = 0;
			}
			
			$orderstatus_id = 2;
			$rs_update_rontstore_and_address = PaymentSentAddressOrder::update_rontstore_and_address($rs,$code_order,$customer_id,$business_location_id,$orderstatus_id,$gv,$price_remove_gv);

			if($rs_update_rontstore_and_address['status'] == 'fail'){
				DB::rollback(); 
				return $rs_update_rontstore_and_address;
			}else{
				$id= $rs_update_rontstore_and_address['id'];
			}

			$price_total = ($rs->price + $rs->shipping);
			$rs_log_gift = GiftVoucher::log_gift($price_total,$customer_id,$id);

			if($rs_log_gift['status'] != 'success'){
				DB::rollback(); 
				$resule = ['status'=>'fail','message'=>'rs_log_gift fail'];
				return $resule;
			}


			$resule = PaymentAddProduct::payment_add_product($id,$customer_id,$rs->type);

			if($resule['status'] == 'success'){
				$resulePv = Pvpayment::PvPayment_type_confirme($id,'99');
				if($resulePv['status'] == 'success'){
					DB::commit();
					return $resulePv;

				}else{
					DB::rollback();
					return $resulePv;
				}

			}else{
				DB::rollback();
				return $resule;

			}

		}catch(Exception $e) {
			DB::rollback();
			return $e;

		}
	}


}
