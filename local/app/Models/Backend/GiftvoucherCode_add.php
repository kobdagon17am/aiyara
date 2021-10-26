<?php

namespace App\Models\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class GiftvoucherCode_add extends Model {

   public static function insertData($data){

      // $value=DB::table('db_giftvoucher_cus')
      // ->where('giftvoucher_code_id_fk', $data['giftvoucher_code_id_fk'])
      // ->where('customer_username', $data['customer_username'])
      // ->get();
      // if($value->count() == 0){
         DB::table('db_giftvoucher_cus')->insert($data);
      // }
   }

}

