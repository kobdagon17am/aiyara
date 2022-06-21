<?php

namespace App\Http\Controllers\Frontend\Fc;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingCosController extends Controller
{
  public function check_shipping_cos(Request $request){//check ราคาค่าส่ง


  	$shipping_type_id_1 = DB::table('dataset_shipping_cost')
  	->where('business_location_id_fk','=',$request->location_id)
  	->where('shipping_type_id','=',1)
  	->first();

  	$shipping_type_id_2 = DB::table('dataset_shipping_vicinity')
  	->where('province_id_fk','=',$request->provinces_id)
  	->first();

    if($request->shipping_premium == 'true'){
      $data = DB::table('dataset_shipping_cost')
  		->where('business_location_id_fk','=',$request->location_id)
  		->where('shipping_type_id','=',4)
  		->first();

    }elseif($request->price >= $shipping_type_id_1->purchase_amt){
  		//0
  		$data = DB::table('dataset_shipping_cost')
  		->where('business_location_id_fk','=',$request->location_id)
  		->where('shipping_type_id','=',1)
  		->first();

  	}elseif($request->type_sent == 'sent_office'){
  		$data = DB::table('dataset_shipping_cost')
  		->where('business_location_id_fk','=',$request->location_id)
  		->where('shipping_type_id','=',5)
  		->first();

    }elseif($shipping_type_id_2){
  		$data = DB::table('dataset_shipping_cost')
  		->where('business_location_id_fk','=',$request->location_id)
  		->where('shipping_type_id','=',2)
  		->first();

  	}else{
  		$data = DB::table('dataset_shipping_cost')
  		->where('business_location_id_fk','=',$request->location_id)
  		->where('shipping_type_id','=',3)
  		->first();
  	}

    $shipping_cost = $data->shipping_cost;

    if($request->type == 6){
      $price_total = $request->price;
    }else{
      $price_total = $request->price + $data->shipping_cost;
    }


    if($request->type == 5){

			$gv = $request->sum_gv;
      $gv_total = $gv - $price_total;

			if($gv_total < 0){
				$price_total_type5 = abs($gv - $price_total);
        $gift_voucher_price =  $gv;
        $gv_remove_price = 0;
			}else{
				$price_total_type5 = 0;
        $gift_voucher_price = $price_total;
        $gv_remove_price = $gv_total;
			}

		}else{
      $gv_remove_price = null;
			$price_total_type5 = null;
      $gift_voucher_price = null;
    }


  	$rs = ['status'=>'success','data'=>$data,'price_total'=>$price_total,'shipping_cost'=>$shipping_cost,
    'price_total_type5'=>$price_total_type5,'gift_voucher_price'=>$gift_voucher_price,'gv_remove_price'=>$gv_remove_price];
  	return $rs;

  }


  public static function fc_check_shipping_cos($location_id,$provinces_id,$price_total,$shipping_premium='',$type_sent=''){//check ราคาค่าส่ง
    $shipping_type_id_1 = DB::table('dataset_shipping_cost')
  	->where('business_location_id_fk','=',$location_id)
  	->where('shipping_type_id','=',1)
  	->first();

  	$shipping_type_id_2 = DB::table('dataset_shipping_vicinity')
  	->where('province_id_fk','=',$provinces_id)
  	->first();

    if($shipping_premium == 'true'){
      $data = DB::table('dataset_shipping_cost')
  		->where('business_location_id_fk','=',$location_id)
  		->where('shipping_type_id','=',4)
  		->first();

    }elseif($price_total >= $shipping_type_id_1->purchase_amt){
  		//0
  		$data = DB::table('dataset_shipping_cost')
  		->where('business_location_id_fk','=',$location_id)
  		->where('shipping_type_id','=',1)
  		->first();

  	}elseif($type_sent == 'sent_office'){
  		$data = DB::table('dataset_shipping_cost')
  		->where('business_location_id_fk','=',$location_id)
  		->where('shipping_type_id','=',5)
  		->first();

    }elseif($shipping_type_id_2){
  		$data = DB::table('dataset_shipping_cost')
  		->where('business_location_id_fk','=',$location_id)
  		->where('shipping_type_id','=',2)
  		->first();

  	}else{
  		$data = DB::table('dataset_shipping_cost')
  		->where('business_location_id_fk','=',$location_id)
  		->where('shipping_type_id','=',3)
  		->first();
  	}

    $rs = ['status'=>'success','data'=>$data];
    return $rs;

  }

}
