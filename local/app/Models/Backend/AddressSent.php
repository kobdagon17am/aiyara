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
      ->where('amphures_id_fk', $data['amphures_id_fk'])
      ->where('district_id_fk', $data['district_id_fk'])
      ->where('province_id_fk', $data['province_id_fk'])
      ->where('zipcode', $data['zipcode'])
      ->get();
      
      if($value->count() == 0){
         DB::table('customers_addr_sent')->insert($data);
      }

   }

}

