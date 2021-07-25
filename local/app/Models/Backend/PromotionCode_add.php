<?php

namespace App\Models\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class PromotionCode_add extends Model {

   public static function insertData($data){

      $value=DB::table('db_promotion_cus')->where('promotion_code', $data['promotion_code'])->get();
      if($value->count() == 0){
         DB::table('db_promotion_cus')->insert($data);
      }
   }

}

