<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use App\Models\Db_Orders;
use App\Models\Db_Add_ai_cash;
use App\Models\DbInvoiceCode;

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
      // วุฒิเพิ่มมา เพื่อไว้เลือกวันที่เฉยๆ
      $id = Db_Orders::where('business_location_id_fk', '=', $business_location_id_fk)
        ->where('date_setting_code', '=', date('ym', strtotime($date_order)))
        ->where('order_channel', '=', 'MEMBER')
        ->where('code_order', '!=', '')
        ->orwhere('code_order', '!=', null)
        ->orderby('id', 'desc')
        ->first();

      if (@$id->code_order) {
        $last_code = $id->code_order;
        $code = substr($last_code, -5);
        $last_code = $code + 1;

        $num_code = substr("00000" . $last_code, -5);
        $code_order = 'O' . $business_location_id_fk . date('ymd', strtotime($date_order)) . '' . $num_code;
      } else {
        $last_code = 1;
        $num_code = substr("00000" . $last_code, -5);
        $code_order = 'O' . $business_location_id_fk . date('ymd', strtotime($date_order)) . '' . $num_code;
      }


      $x = 'start';
      $rs = RunNumberPayment::check_number_order($code_order);
      while ($x == 'end') {
        if ($rs['status'] == 'fail') {
          $code_order = 'O' . $business_location_id_fk . date('ymd', strtotime($date_order)) . '' . $rs['code_order'];
          $rs = RunNumberPayment::check_number_order($code_order);
        } else {
          $x = 'end';
          $code_order = 'O' . $business_location_id_fk . date('ymd', strtotime($date_order)) . '' . $rs['code_order'];
        }
      }

      return  $code_order;
    } else {
      // ของพี่กอล์ฟ
      $id = Db_Orders::where('business_location_id_fk', '=', $business_location_id_fk)
        ->where('date_setting_code', '=', date('ym'))
        ->where('order_channel', '=', 'MEMBER')
        ->where('code_order', '!=', '')
        ->orwhere('code_order', '!=', null)
        ->orderby('id', 'desc')
        ->first();

      if (@$id->code_order) {
        $last_code = $id->code_order;
        $code = substr($last_code, -5);
        $last_code = $code + 1;

        $num_code = substr("00000" . $last_code, -5);
        $code_order = 'O' . $business_location_id_fk . date('ymd') . '' . $num_code;
      } else {
        $last_code = 1;
        $num_code = substr("00000" . $last_code, -5);
        $code_order = 'O' . $business_location_id_fk . date('ymd') . '' . $num_code;
      }
      $x = 'start';
      $rs = RunNumberPayment::check_number_order($code_order);
      while ($x == 'end') {
        if ($rs['status'] == 'fail') {
          $code_order = 'O' . $business_location_id_fk . date('ymd', strtotime($date_order)) . '' . $rs['code_order'];
          $rs = RunNumberPayment::check_number_order($code_order);
        } else {
          $x = 'end';
          $code_order = 'O' . $business_location_id_fk . date('ymd', strtotime($date_order)) . '' . $rs['code_order'];
        }
      }

      return  $code_order;
    }
  }

  public static function check_number_order($code_order)
  {
    $check_number_order = Db_Orders::where('code_order', '=', $code_order)
      ->count();

    if ($check_number_order > 0) {
      $last_code = $code_order;
      $code = substr($last_code, -5);
      $last_code = $code + 1;
      $data = ['status' => 'fail', 'code_order' => $last_code];
    } else {
      $last_code = $code_order;
      $code = substr($last_code, -5);
      $last_code = $code + 1;
      $data = ['status' => 'success', 'code_order' => $code_order];
    }
    return $data;
  }

  public static function run_number_aicash($business_location_id_fk)
  {

    $id = Db_Add_ai_cash::where('business_location_id_fk', '=', $business_location_id_fk)
      ->where('date_setting_code', '=', date('ym'))
      ->where('code_order', '!=', '')
      ->orwhere('code_order', '!=', null)
      ->orderby('id', 'desc')
      ->first();

    if (@$id->code_order) {

      $last_code = $id->code_order;
      $code = substr($last_code, -5);
      $last_code = $code + 1;
      $num_code = substr("00000" . $last_code, -5);

      $code_order = 'W' . $business_location_id_fk . date('ymd') . '' . $num_code;
    } else {

      $last_code = 1;
      $num_code = substr("00000" . $last_code, -5);
      $code_order = 'W' . $business_location_id_fk . date('ymd') . '' . $num_code;
    }

    //new
    $x = 'start';
    $rs = RunNumberPayment::check_number_aicash($code_order);
    while ($x == 'end') {
      if ($rs['status'] == 'fail') {
        $code_order = $code_order = 'W' . $business_location_id_fk . date('ymd') .''.$rs['code_order'];
        $rs = RunNumberPayment::check_number_aicash($code_order);
      } else {
        $x = 'end';
        $code_order = $code_order = 'W' . $business_location_id_fk . date('ymd') .''.$rs['code_order'];
      }
    }

    return  $code_order;
  }

  public static function check_number_aicash($code_order)
  {
    $check_number_aicash = Db_Add_ai_cash::where('code_order', '=', $code_order)
      ->count();
      if ($check_number_aicash > 0) {
        $last_code = $code_order;
        $code = substr($last_code, -5);
        $last_code = $code + 1;
        $data = ['status' => 'fail', 'code_order' => $last_code];
      } else {
        $last_code = $code_order;
        $code = substr($last_code, -5);
        $last_code = $code + 1;
        $data = ['status' => 'success', 'code_order' => $code_order];
      }
      return $data;
  }

  public static function run_number_aistockis()
  {

    $id = DB::table('ai_stockist')
      ->orderby('id', 'desc')
      ->first();
      // dd($id);


    if (@$id->id) {
      $last_code = $id->id;
      // $code = substr($last_code, -5);
      $last_code = $last_code + 1;

      $num_code = substr("00000" . $last_code, -5);
      $code_order = 'T' . date('ymd') . '' . $num_code;
    } else {

      $last_code = 1;
      $num_code = substr("00000" . $last_code, -5);
      $code_order = 'T' . date('ymd') . '' . $num_code;
    }

    return  $code_order;
  }


  public static function run_payment_code($business_location_id,$type_order)
  {
    //R = Payment
    //E = Course/Event

    $data = DbInvoiceCode::where('business_location_id', '=', $business_location_id)
      ->where('date_setting_code', '=', date('ym'))
      ->where('order_payment_code', '!=', '')
      ->orwhere('order_payment_code', '!=', null)
      ->where('type_order', '=', $type_order)
      ->orderby('id', 'desc')
      ->first();




    if ($type_order == 'product') {
      $type_code = 'R';
    } elseif ($type_order == 'course_event') {
      $type_code = 'E';
    } else {
      $resule = ['status' => 'fail', 'message' => 'Is not product/course_event'];
      return  $resule;
    }

    if (@$data->order_payment_code) {

      $last_code = $data->order_payment_code;
      $code = substr($last_code, -5);
      $last_code = $code + 1;

      $num_code = substr("00000" . $last_code, -5);
      $code_order = $type_code . $business_location_id . date('ymd') . '' . $num_code;
    } else {
      $last_code = 1;
      $num_code = substr("00000" . $last_code, -5);
      $code_order = $type_code . $business_location_id . date('ymd') . '' . $num_code;
    }

    $x = 'start';
    $rs = RunNumberPayment::check_invoice($code_order);
    while ($x == 'end') {
      if ($rs['status'] == 'fail') {
        $code_order = $type_code . $business_location_id . date('ymd') . '' .$rs['code_order'];
        $rs = RunNumberPayment::check_invoice($code_order);
      } else {
        $x = 'end';
        $code_order = $type_code . $business_location_id . date('ymd') . '' .$rs['code_order'];
      }
    }

    $resule = ['status' => 'success', 'message' => 'success', 'code_order' => $code_order];
    return  $resule;
  }

  public static function check_invoice($code_order)
  {
    $check_number_order = DbInvoiceCode::where('order_payment_code', '=', $code_order)
      ->count();

    if ($check_number_order > 0) {
      $last_code = $code_order;
      $code = substr($last_code, -5);
      $last_code = $code + 1;
      $data = ['status' => 'fail', 'code_order' => $last_code];
    } else {
      $last_code = $code_order;
      $code = substr($last_code, -5);
      $last_code = $code + 1;
      $data = ['status' => 'success', 'code_order' => $code_order];
    }
    return $data;
  }
}
