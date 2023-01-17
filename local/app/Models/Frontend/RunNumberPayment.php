<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use App\Models\Db_Orders;
use App\Models\Db_Add_ai_cash;
use App\Models\DbInvoiceCode;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class RunNumberPayment extends Model
{

  //patern R121011100001

  //O = Oder
  //R = Payment
  //E = Course/Event
 //\App\Models\Frontend\RunNumberPayment::run_number_order($business_location_id_fk, $date_order);
  public static function run_number_order($business_location_id_fk, $date_order = "")
  {

    if ($date_order != '') {
      $date_order = date('ymd', strtotime($date_order)) ;
    }else{
      $date_order = date('ymd');
    }

    $code_order =  IdGenerator::generate([
        'table' => 'db_orders',
        'field' => 'code_order', 
        'length' => 13,
        'prefix' => 'O'.$business_location_id_fk.''.$date_order,
        'reset_on_prefix_change' => true
    ]);
    // $code_order = $code_order.''.date('s');

      return  $code_order;

  }



  public static function run_number_aicash($business_location_id_fk)
  {

      $date_order = date('ymd');
      $code_order =  IdGenerator::generate([
          'table' => 'db_add_ai_cash',
          'field' => 'code_order',
          'length' => 13,
          'prefix' => 'W'.$business_location_id_fk.''.$date_order,
          'reset_on_prefix_change' => true
      ]);

    return  $code_order;
  }



  public static function run_number_aistockis()
  {
      $date_order = date('ymd');
      $code_order =  IdGenerator::generate([
          'table' => 'ai_stockist',
          'field' => 'transection_code',
          'length' => 13,
          'prefix' => 'T'.$date_order,
          'reset_on_prefix_change' => true
      ]);
    return  $code_order;
  }


  public static function run_payment_code($business_location_id,$type_order)
  {
    //R = Payment
    //E = Course/Event
    $date_order = date('ymd');
    if ($type_order == 'product') {

      $code_order =  IdGenerator::generate([
        'table' => 'db_orders',
        'field' => 'invoice_code_id_fk',
        'length' => 13,
        'prefix' => 'R'.$business_location_id.''.$date_order,
        'reset_on_prefix_change' => true
    ]);
    } elseif ($type_order == 'course_event') {
      $code_order =  IdGenerator::generate([
        'table' => 'db_orders',
        'field' => 'invoice_code_id_fk',
        'length' => 13,
        'prefix' => 'E'.$business_location_id.''.$date_order,
        'reset_on_prefix_change' => true
    ]);
    } else {
      $resule = ['status' => 'fail', 'message' => 'Is not product/course_event'];
      return  $resule;
    }

    $resule = ['status' => 'success', 'message' => 'success', 'code_order' => $code_order];
    return  $resule;
  }


}
