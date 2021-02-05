<?php

namespace App\Models\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class AddressSent extends Model {

   public static function insertData($data){
   	  // Check duplicate data
      $value=DB::table('customers_addr_sent')
      ->where('customer_id', $data['customer_id'])
      ->where('house_no', $data['house_no'])
      ->where('district_id', $data['district_id'])
      ->where('district_sub_id', $data['district_sub_id'])
      ->where('province_id', $data['province_id'])
      ->where('zipcode', $data['zipcode'])
      ->get();
      
      if($value->count() == 0){
         DB::table('customers_addr_sent')->insert($data);
      }

   }

}

