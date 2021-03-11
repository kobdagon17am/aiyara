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

    $shipping_cost = number_format($data->shipping_cost,2);
    $price_total = $request->price + $data->shipping_cost;

  	$rs = ['status'=>'success','data'=>$data,'price_total'=>number_format($price_total,2),'shipping_cost'=>$shipping_cost];
  	return $rs;

  }


  public static function fc_check_shipping_cos($location_id,$provinces_id,$price_total,$shipping_premium=''){//check ราคาค่าส่ง

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
