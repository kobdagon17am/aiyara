<?php

namespace App\Http\Controllers\Frontend\Fc;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Backend\Giveaway;
class GiveawayController extends Controller
{
  public static function check_giveaway($business_location_id,$type,$customer_pv,$pv_total){//check ของแถม
    $data = Giveaway::where('business_location_id_fk','=',$business_location_id)
                    ->where('status','=',1)
                    ->wheredate('start_date','<=',now())
                    ->wheredate('end_date','>=',now())
                    ->get();
                    //dd($data);
    if(count($data)>0){
      $i=0;
      $s_data = array();
      $f_data = array();
      foreach($data as $key => $value){
        $i++;
        if(!empty($value->purchase_type_id_fk) and $value->purchase_type_id_fk != $type ){
          $f_data[$i]['giveaway_id'] = $value->id;
          $f_data[$i]['message'] = 'ประเภทการซื้อไม่ถูกต้อง';

        }elseif($value->giveaway_member_type_id_fk == 1 and $customer_pv > 0 ){
          $f_data[$i]['giveaway_id'] = $value->id;
          $f_data[$i]['message'] = 'รูปแบบสมาชิกไม่ถูกต้อง ( User Pv > 0)';

        }elseif($value->giveaway_member_type_id_fk == 2 and $customer_pv == 0 ){
          $f_data[$i]['giveaway_id'] = $value->id;
          $f_data[$i]['message'] = 'รูปแบบสมาชิกไม่ถูกต้อง ( User Pv = 0 )';

        }elseif($pv_total < $value->pv_minimum_purchase){
          $f_data[$i]['giveaway_id'] = $value->id;
          $f_data[$i]['message'] = 'PV ขั้นต่ำไม่ถึงค่าที่กำหนด';

        }else{
          if($value->giveaway_in_bill_id_fk == 2){//กรณีแถมสินค้าได้หลายครั้งต่อบิล
             $count_free = floor($pv_total/$value->pv_minimum_purchase);
          }else{
            $count_free = 1;
          }

          $s_data[$i]['giveaway_id'] = $value->id;
          $s_data[$i]['name'] = $value->giveaway_name;
          $s_data[$i]['type'] = $value->giveaway_option_id_fk;//1 product 2 gv

          if($value->giveaway_option_id_fk == 1){

            $product =  DB::table('db_giveaway_products')
            ->select('products_details.product_id_fk','products_details.product_name','dataset_product_unit.product_unit','dataset_product_unit.group_id as unit_id','db_giveaway_products.product_amt')
            ->leftJoin('products_details','products_details.product_id_fk','=','db_giveaway_products.product_id_fk')
            ->leftJoin('dataset_product_unit','dataset_product_unit.group_id','=','db_giveaway_products.product_unit')
            ->where('db_giveaway_products.giveaway_id_fk','=',$value->id)
            ->where('products_details.lang_id','=',$business_location_id)
            ->where('dataset_product_unit.lang_id','=',$business_location_id)
            ->get();
            //dd($product);
            $s_data[$i]['product'] = $product;
            $s_data[$i]['gv'] = '';

          }else{
            $s_data[$i]['product'] = '';
            $s_data[$i]['gv'] = $value->giveaway_voucher;
          }

          $s_data[$i]['count_free'] = $count_free;
          $resule = ['status'=>'success'];
        }
      }

      $resule = ['status'=>'success','f_data'=>$f_data,'s_data'=>$s_data];

    }else{
      $resule = ['status'=>'fail','message'=>'ไม่มีของแถม'];
    }
    //dd($resule);

    return $resule;
  }

}
