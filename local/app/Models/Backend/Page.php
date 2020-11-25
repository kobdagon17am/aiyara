<?php

namespace App\Models\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Page extends Model {

   public static function insertData($data){

      // $value=DB::table('users')->where('username', $data['username'])->get();
      // $value=DB::table('pm_broadcast')->where('customers_id_fk', $data['customers_id_fk'])->get();
      $value=DB::table('pm_broadcast')->get();
      // if($value->count() == 0){
         // DB::table('users')->insert($data);
         DB::table('pm_broadcast')->insert($data);
      // }
   }

}

