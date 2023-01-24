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
  //\App\Models\Frontend\RunNumberPayment::run_number_order(1,'');
  public static function run_number_order($business_location_id_fk, $date_order = "")
  {
    $date_order = date('ymd');
    $code_order =  IdGenerator::generate([
      'table' => 'db_code_order',
      'field' => 'code_order',
      'length' => 13,
      'prefix' => 'O' . $business_location_id_fk . '' . $date_order,
      'reset_on_prefix_change' => true
    ]);

    $rs_code_order = DB::table('db_code_order')
      ->Insert(['code_order' => $code_order]);
    if ($rs_code_order == true) {
      return  $code_order;
    } else {
      \App\Models\Frontend\RunNumberPayment::run_number_order($business_location_id_fk, '');
    }
  }



  public static function run_number_aicash($business_location_id_fk)
  {

    $date_order = date('ymd');
    $code_order =  IdGenerator::generate([
      'table' => 'db_code_order',
      'field' => 'code_order',
      'length' => 13,
      'prefix' => 'W' . $business_location_id_fk . '' . $date_order,
      'reset_on_prefix_change' => true
    ]);

    $rs_code_order = DB::table('db_code_order')
      ->Insert(['code_order' => $code_order]);
    if ($rs_code_order == true) {
      return  $code_order;
    } else {
      \App\Models\Frontend\RunNumberPayment::run_number_aicash($business_location_id_fk);
    }
  }



  public static function run_number_aistockis()
  {
    $date_order = date('ymd');
    $code_order =  IdGenerator::generate([
      'table' => 'db_code_order',
      'field' => 'code_order',
      'length' => 13,
      'prefix' => 'T' . $date_order,
      'reset_on_prefix_change' => true
    ]);
    $rs_code_order = DB::table('db_code_order')
      ->Insert(['code_order' => $code_order]);
    if ($rs_code_order == true) {
      return  $code_order;
    } else {
      \App\Models\Frontend\RunNumberPayment::run_number_aistockis();
    }
  }


  public static function run_payment_code($business_location_id, $type_order)
  {
    //R = Payment
    //E = Course/Event
    $date_order = date('ymd');
    if ($type_order == 'product') {

      $code_order =  IdGenerator::generate([
        'table' => 'db_code_order',
        'field' => 'code_order',
        'length' => 13,
        'prefix' => 'R' . $business_location_id . '' . $date_order,
        'reset_on_prefix_change' => true
      ]);

      $rs_code_order = DB::table('db_code_order')
        ->Insert(['code_order' => $code_order]);
      if ($rs_code_order == true) {
        return  $code_order;
      } else {
        \App\Models\Frontend\RunNumberPayment::run_payment_code($business_location_id, $type_order);
      }
    } elseif ($type_order == 'course_event') {
      $code_order =  IdGenerator::generate([
        'table' => 'db_code_order',
        'field' => 'code_order',
        'length' => 13,
        'prefix' => 'E' . $business_location_id . '' . $date_order,
        'reset_on_prefix_change' => true
      ]);

      $rs_code_order = DB::table('db_code_order')
        ->Insert(['code_order' => $code_order]);
      if ($rs_code_order == true) {
        return  $code_order;
      } else {
        \App\Models\Frontend\RunNumberPayment::run_payment_code($business_location_id, $type_order);
      }
    } else {
      $resule = ['status' => 'fail', 'message' => 'Is not product/course_event'];
      return  $resule;
    }
  }
}
