<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
class PaymentSentAddressOrder extends Model
{
	public static function update_rontstore_and_address($rs,$code_order,$customer_id,$business_location_id,$orderstatus_id,$gv,$price_remove_gv){
		try { 
			//DB::BeginTransaction();
			if($rs->receive == 'sent_address'){
				$id = DB::table('db_orders')->insertGetId(
					['code_order' => $code_order,
					'customers_id_fk' => $customer_id,
					'vat'  => $rs->vat,
					'shipping_price'  => $rs->shipping,
					'sum_price' => $rs->price,
					// 'price_vat' => $rs->price_vat,
					// 'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'gift_voucher' => $gv,//gvที่ใช้
					'price_remove_gv'=>$price_remove_gv,//ราคาที่ต้องจ่ายเพิ่ม
					'orders_type_id_fk'  => $rs->type,
					'pay_type_id_fk'  => $rs->pay_type,
					'order_status_id_fk' => $orderstatus_id,
					'house_no'=> $rs->house_no,
					'house_name'=> $rs->house_name,
					'moo'=> $rs->moo,
					'soi'=> $rs->soi, 
					'district'=> $rs->district,
					'district_sub'=> $rs->district_sub,
					'road'=> $rs->road,
					'province'=> $rs->province,
					'zipcode'=> $rs->zipcode,
					//'type_address' => 0,
					'tel' => $rs->tel_mobile,
					'name' => $rs->name,
					'email' => $rs->email,
					'delivery_location_status' => $rs->receive,
					'business_location_id_fk' => $business_location_id,
					'sentto_branch_id' => 0,

				]
			);

			}elseif ($rs->receive == 'sent_address_card') {

				$id = DB::table('db_orders')->insertGetId(
					['code_order' => $code_order,
					'customers_id_fk' => $customer_id,
					'vat'  => $rs->vat,
					'shipping_price'  => $rs->shipping,
					'sum_price' => $rs->price,
					//'price_vat' => $rs->price_vat,
					//'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'gift_voucher' => $gv,//gvที่ใช้
					'price_remove_gv'=>$price_remove_gv,//ราคาที่ต้องจ่ายเพิ่ม
					'orders_type_id_fk'  => $rs->type,
					'pay_type_id_fk'  => $rs->pay_type,
					'order_status_id_fk' => $orderstatus_id,
					'house_no'=> $rs->card_house_no,
					'house_name'=> $rs->card_house_name,
					'moo'=> $rs->card_moo,
					'soi'=> $rs->card_soi,
					'district'=> $rs->card_district,
					'district_sub'=> $rs->card_district_sub,
					'road'=> $rs->card_road,
					'province'=> $rs->card_province,
					'zipcode'=> $rs->card_zipcode,
					//'type_address' => $rs->receive
					'tel' => $rs->card_tel_mobile,
					'name' => $rs->card_name,
					'email' => $rs->card_email,
					//'receive_type' => $rs->receive,
					'delivery_location_status' => $rs->receive,
					'business_location_id_fk' => $business_location_id,
					'sentto_branch_id' => 0,

				]
			);

			}elseif ($rs->receive == 'sent_other') {
					$id = DB::table('db_orders')->insertGetId(
					['code_order' => $code_order,
					'customers_id_fk' => $customer_id,
					'vat'  => $rs->vat,
					'shipping_price'  => $rs->shipping,
					'sum_price' => $rs->price,
					// 'price_vat' => $rs->price_vat,
					// 'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'gift_voucher' => $gv,//gvที่ใช้
					'price_remove_gv'=>$price_remove_gv,//ราคาที่ต้องจ่ายเพิ่ม
					'orders_type_id_fk'  => $rs->type,
					'pay_type_id_fk'  => $rs->pay_type,
					'order_status_id_fk' => $orderstatus_id,
					'house_no'=> $rs->other_house_no,
					'house_name'=> $rs->other_house_name,
					'moo'=> $rs->other_moo,
					'soi'=> $rs->other_soi,
					'district'=> $rs->other_district,
					'district_sub'=> $rs->other_district_sub,
					'road'=> $rs->other_road,
					'province'=> $rs->other_province,
					'zipcode'=> $rs->other_zipcode,
					//'type_address' => 0,//0 ส่งตามที่อยู้ ไม่ใช่ 1,2,3, ส่งตาม location office
					'tel' => $rs->other_tel_mobile,
					'name' => $rs->other_name,
					'email' => $rs->other_email,
					'delivery_location_status' => $rs->receive,
					'business_location_id_fk' => $business_location_id,
					'sentto_branch_id' => 0,

				]
			);

			}elseif($rs->receive == 'sent_office') {

				$id = DB::table('db_orders')->insertGetId(
					['code_order' => $code_order,
					'customers_id_fk' => $customer_id,
					'vat'  => $rs->vat,
					'shipping_price'  => $rs->shipping,
					'sum_price' => $rs->price,
					// 'price_vat' => $rs->price_vat,
					// 'p_vat'  => $rs->p_vat,
					'pv_total'  => $rs->pv_total,
					'gift_voucher' => $gv,//gvที่ใช้
					'price_remove_gv'=>$price_remove_gv,//ราคาที่ต้องจ่ายเพิ่ม
					'orders_type_id_fk'  => $rs->type,
					'pay_type_id_fk'  => $rs->pay_type,
					'order_status_id_fk' => $orderstatus_id,
					//'type_address' => $rs->receive_location,
					'tel' => $rs->tel_mobile, 
					'name' => $rs->name,
					'email' => $rs->email,

					'delivery_location_status' => $rs->receive,
					'business_location_id_fk' => $business_location_id,
					'sentto_branch_id' => $rs->receive_location,
			]
		);

			}else{
				$resule = ['status'=>'fail','message'=>'Orderstatus Not Type'];
				//DB::rollback();
				return $resule;
			}

			//DB::commit();
			$resule = ['status'=>'success','message'=>'Order Update Success','id'=>$id];
			return $resule;

		}catch(Exception $e){

			$resule = ['status'=>'success','message'=>'Order Update Fail','id'=>$id];
			//DB::rollback();
			return $resule;
		}
	}

}
