<?php

namespace App\Models\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Ce_regis_add extends Model {

   public static function insertData($data){

      $value=DB::table('course_event_regis')->where('ce_id_fk', $data['ce_id_fk'])->where('customers_id_fk', $data['customers_id_fk'])->get();
      if($value->count() == 0){
         DB::table('course_event_regis')->insert($data);
      }
   }

}
