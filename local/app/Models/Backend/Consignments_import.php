<?php

namespace App\Models\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Consignments_import extends Model {

   public static function insertData($data){

      $value=DB::table('db_consignments_import')
      ->where('recipient_code', $data['recipient_code'])
      ->get();
      if($value->count() == 0){
         DB::table('db_consignments_import')->insert($data);
      }else{
		        DB::table('db_consignments_import')
            ->where('recipient_code', $data['recipient_code'])
            ->update(array(
            	'recipient_name' => $data['recipient_name'],
              'address' => $data['address'],
              'consignment_no' => $data['consignment_no'],
            	'customer_ref_no' => $data['customer_ref_no'],
            	'sender_code' => $data['sender_code'],
               'postcode' => $data['postcode'],
               'mobile' => $data['mobile'],
               'pay_requisition_001_id_fk' => $data['pay_requisition_001_id_fk'],
               'pick_pack_requisition_code_id_fk' => $data['service_code'],
               'delivery_id_fk' => $data['delivery_id_fk'],
            ));
      }

   }


   public static function insertDataAddr($data){

      $value=DB::table('db_consignments')
      ->where('recipient_code', $data['recipient_code'])
      ->get();
      if($value->count() == 0){
         DB::table('db_consignments')->insertOrIgnore($data);
      }else{
            // DB::table('db_consignments')
            // ->where('recipient_code', $data['recipient_code'])
            // ->update(array(
            //   'sender_code' => $data['sender_code'],
            // ));
      }

   }

}

  // `consignment_no` varchar(255) DEFAULT NULL,
  // `customer_ref_no` varchar(255) DEFAULT NULL,
  // `sender_code` varchar(255) DEFAULT NULL,
  // `recipient_code` varchar(255) DEFAULT NULL,
  // `recipient_name` varchar(255) DEFAULT NULL,
  // `address` varchar(255) DEFAULT NULL,
  // `postcode` varchar(255) DEFAULT NULL,
  // `mobile` varchar(255) DEFAULT NULL,
  // `contact_person` varchar(255) DEFAULT NULL,
  // `phone_no` varchar(255) DEFAULT NULL,
  // `email` varchar(255) DEFAULT NULL,
  // `declare_value` varchar(255) DEFAULT NULL,
  // `cod_amount` varchar(255) DEFAULT NULL,
  // `remark` varchar(255) DEFAULT NULL,
  // `total_box` varchar(255) DEFAULT NULL,
  // `sat_del` varchar(255) DEFAULT NULL,
  // `hrc` varchar(255) DEFAULT NULL,
  // `invr` varchar(255) DEFAULT NULL,