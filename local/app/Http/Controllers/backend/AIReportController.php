<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use App\Models\Frontend\RunNumberPayment;
use Auth;
use Session;
class AIReportController extends Controller
{

  public function ai_report()
  {
    $sBusiness_location = \App\Models\Backend\Business_location::get();
    $sBranchs = \App\Models\Backend\Branchs::get();
    return View('backend.ai_report.ai_report')->with(
      array(
        'sBusiness_location' => $sBusiness_location,
        'sBranchs' => $sBranchs,
      )
    );
  }

  public function ai_report_datatable(Request $req)
  {
    if(isset($req->startDate)){
      $report_type = $req->report_type;
      $date_start = $req->startDate;
      $date_end = $req->endDate;

      if($report_type == 1){
        $sTable = DB::table('ai_stockist')
        ->select('ai_stockist.created_at as action_date','ai_stockist.customer_id','ai_stockist.code_order')
        ->where(DB::raw('DATE(ai_stockist.created_at)'),$date_start)
        ->where('ai_stockist.status','success')
        ->orderBy('ai_stockist.created_at','asc')
        ->groupBy('ai_stockist.customer_id',DB::raw('DATE(ai_stockist.created_at)'));
      }

      if($report_type == 2){
        $sTable = DB::table('ai_stockist')
        ->select('ai_stockist.created_at as action_date','ai_stockist.customer_id','ai_stockist.code_order')
        ->whereBetween('ai_stockist.created_at', [$date_start,$date_end])
        ->where('ai_stockist.status','success')
        ->orderBy('ai_stockist.created_at','asc')
        ->groupBy('ai_stockist.customer_id',DB::raw('DATE(ai_stockist.created_at)'));
      }

      if($req->customer_id != ''){
        $sTable->where('ai_stockist.customer_id',$req->customer_id);
      }
      if($req->customer_name != ''){
        $sTable->where('ai_stockist.customer_id',$req->customer_name);
      }

    }else{
      $report_type = 1;
      // $date_start = date('Y-m-d');
      $date_start = '2023-11-04';

      $sTable = DB::table('ai_stockist')
      ->select('ai_stockist.created_at as action_date','ai_stockist.customer_id','ai_stockist.code_order')
      ->where(DB::raw('DATE(ai_stockist.created_at)'),$date_start)
      ->where('ai_stockist.status','success')
      ->orderBy('ai_stockist.created_at','asc')
      ->groupBy('ai_stockist.customer_id',DB::raw('DATE(ai_stockist.created_at)'));
    }

    $sQuery = \DataTables::of($sTable);
    return $sQuery
      ->addColumn('action_date', function ($row) {
       return date('d/m/Y', strtotime($row->action_date));
      })
      ->escapeColumns('action_date')
      ->addColumn('member_id', function ($row) {
        $cus = DB::table('customers')->select('user_name')->where('id',$row->customer_id)->first();
        return @$cus->user_name;
       })
       ->escapeColumns('member_id')
       ->addColumn('member_name', function ($row) {
        $cus = DB::table('customers')->select('prefix_name','first_name','last_name')->where('id',$row->customer_id)->first();
        return @$cus->prefix_name.' '.@$cus->first_name.' '.@$cus->last_name;
       })
       ->escapeColumns('member_name')

       ->addColumn('detail', function ($row) {

        $members_before_add = DB::table('ai_stockist')
        ->select('ai_stockist.pv','ai_stockist.status_add_remove')
        ->where(DB::raw('DATE(ai_stockist.created_at)'),'<',date('Y-m-d'))
        ->where('ai_stockist.customer_id',$row->customer_id)
        ->where('status_add_remove','add')
        ->where('status','success')
        ->orderBy('ai_stockist.code_order','asc')
        ->sum('pv');

        $members_before_remove = DB::table('ai_stockist')
        ->select('ai_stockist.pv','ai_stockist.status_add_remove')
        ->where(DB::raw('DATE(ai_stockist.created_at)'),'<',date('Y-m-d'))
        ->where('ai_stockist.customer_id',$row->customer_id)
        ->where('status_add_remove','remove')
        ->where('status','success')
        ->orderBy('ai_stockist.code_order','asc')
        ->sum('pv');

        $before_total = $members_before_add-$members_before_remove;

        $members = DB::table('ai_stockist')
        ->select('pv','status_add_remove','order_id_fk')
        ->where(DB::raw('DATE(ai_stockist.created_at)'),date('Y-m-d', strtotime($row->action_date)))
        ->where('ai_stockist.customer_id',$row->customer_id)
        ->orderBy('ai_stockist.code_order','asc')
        ->get();

        $p = '';
        $balance = 0;
        foreach( $members as $key => $m){
          if($key == 0){
            $balance += $before_total;
          }
          $code_order = '';
          $total_price = '0';
          if($m->order_id_fk != '' && $m->order_id_fk != 0){
            $order = DB::table('db_orders')->select('total_price','invoice_code')->where('id',$m->order_id_fk)->first();
            if($order){
              $code_order = $order->invoice_code;
              $total_price = $order->total_price;
            }
          }

          $p .= '<tr>'.
          '<td style="text-align: left;">'. $code_order.'</td>'.
          '<td style="text-align: right;">'.$before_total.'</td>'.
          '<td style="text-align: right;">'. $total_price.'</td>';
          if($m->status_add_remove == 'add'){
            $balance += $m->pv;
            $p .= '<td style="text-align: right;">'. $m->pv.'</td>'.
            '<td style="text-align: right;">'. '0'.'</td>';
          }else{
            $balance -= $m->pv;
            $p .= '<td style="text-align: right;">'. '0'.'</td>'.
            '<td style="text-align: right;">'. $m->pv.'</td>';
          }
          $p .= '<td style="text-align: right;">'. $balance.'</td>'.
          '</tr>';
        }
        return
        '<table width="100%">
        <th width="10%" style="text-align: center;">เลขที่บิล</th>
        <th width="10%" style="text-align: center;">ยอดยกมา</th>
        <th width="10%" style="text-align: center;">ยอดเงิน</th>
        <th width="10%" style="text-align: center;">คะแนน</th>
        <th width="10%" style="text-align: center;">แจงคะแนน</th>
        <th width="10%" style="text-align: center;">คะแนนคงเหลือ</th>
        <tbody>
        '.
        $p
        .'
        </tbody>
        </table>';
       })
       ->escapeColumns('detail')
      ->make(true);
  }


  public function ai_report_datatable_ai_cash(Request $req)
  {
    // DB::table('db_movement_ai_cash')->where('customer_id_fk',0)->delete();
    // if(isset($req->startDate)){
    //   $report_type = $req->report_type;
    //   $date_start = $req->startDate;
    //   $date_end = $req->endDate;

      // if($report_type == 1){
      //   $sTable = DB::table('ai_stockist')
      //   ->select('ai_stockist.created_at as action_date','ai_stockist.customer_id','ai_stockist.code_order')
      //   ->where(DB::raw('DATE(ai_stockist.created_at)'),$date_start)
      //   ->where('ai_stockist.status','success')
      //   ->orderBy('ai_stockist.created_at','asc')
      //   ->groupBy('ai_stockist.customer_id',DB::raw('DATE(ai_stockist.created_at)'));
      // }

      // if($report_type == 2){
      //   $sTable = DB::table('ai_stockist')
      //   ->select('ai_stockist.created_at as action_date','ai_stockist.customer_id','ai_stockist.code_order')
      //   ->whereBetween('ai_stockist.created_at', [$date_start,$date_end])
      //   ->where('ai_stockist.status','success')
      //   ->orderBy('ai_stockist.created_at','asc')
      //   ->groupBy('ai_stockist.customer_id',DB::raw('DATE(ai_stockist.created_at)'));
      // }

    //   if($req->customer_id != ''){
    //     $sTable->where('ai_stockist.customer_id',$req->customer_id);
    //   }
    //   if($req->customer_name != ''){
    //     $sTable->where('ai_stockist.customer_id',$req->customer_name);
    //   }

    // }else{
      $report_type = 1;
      $date_start = '2023-12-04';

      $sTable = DB::table('db_movement_ai_cash')
      ->select('customer_id_fk','created_at as action_date','pay_type_id_fk')
      ->where(DB::raw('DATE(created_at)'),$date_start)
      ->orderBy('created_at','asc')
      ->groupBy('customer_id_fk',DB::raw('DATE(created_at)'));
    // }

    $sQuery = \DataTables::of($sTable);
    return $sQuery
      ->addColumn('action_date', function ($row) {
       return date('d/m/Y', strtotime($row->action_date));
      })
      ->escapeColumns('action_date')

      ->addColumn('pay_type', function ($row) {
        $cus = DB::table('dataset_pay_type')->select('detail')->where('id',$row->pay_type_id_fk)->first();
        return @$cus->detail;
       })
       ->escapeColumns('pay_type')

       ->addColumn('pay_slip', function ($row) {
        // $cus = DB::table('dataset_pay_type')->select('detail')->where('id',$row->pay_type_id_fk)->first();
        // return @$cus->detail;
        return '';
       })
       ->escapeColumns('pay_slip')

      ->addColumn('member_id', function ($row) {
        $cus = DB::table('customers')->select('user_name')->where('id',$row->customer_id_fk)->first();
        return @$cus->user_name;
       })
       ->escapeColumns('member_id')

       ->addColumn('member_name', function ($row) {
        $cus = DB::table('customers')->select('prefix_name','first_name','last_name')->where('id',$row->customer_id_fk)->first();
        return @$cus->prefix_name.' '.@$cus->first_name.' '.@$cus->last_name;
       })
       ->escapeColumns('member_name')

       ->addColumn('detail', function ($row) {

        $members_before_add = DB::table('db_movement_ai_cash')
        ->select('aicash_price')
        ->where(DB::raw('DATE(created_at)'),'<',date('Y-m-d'))
        ->where('customer_id_fk',$row->customer_id_fk)
        ->where('type','add_aicash')
        ->orderBy('order_code','asc')
        ->sum('aicash_price');

        $members_before_remove = DB::table('db_movement_ai_cash')
        ->select('aicash_price')
        ->where(DB::raw('DATE(created_at)'),'<',date('Y-m-d'))
        ->where('customer_id_fk',$row->customer_id_fk)
        ->where('type','buy_product')
        ->orderBy('order_code','asc')
        ->sum('aicash_price');

        $before_total = $members_before_add-$members_before_remove;

        $members = DB::table('db_movement_ai_cash')
        ->select('customer_id_fk','created_at','pay_type_id_fk','order_code','aicash_price','type','order_id_fk')
        ->where(DB::raw('DATE(created_at)'),date('Y-m-d', strtotime($row->action_date)))
        ->where('customer_id_fk',$row->customer_id_fk)
        ->orderBy('order_code','asc')
        ->get();

        $p = '';
        $balance = 0;
        foreach( $members as $key => $m){
          if($key == 0){
            $balance += $before_total;
          }
          $code_order = '';
          $total_price = '0';
          if($m->order_id_fk != '' && $m->order_id_fk != 0){
            $order = DB::table('db_orders')->select('total_price','invoice_code')->where('id',$m->order_id_fk)->first();
            if($order){
              $code_order = $order->invoice_code;
              $total_price = $order->total_price;
            }
          }

          if($key==0){
            $p .= '<tr>'.
            '<td style="text-align: right;">'.$before_total.'</td>';
          }else{
            $p .= '<tr>'.
            '<td style="text-align: right;">'.$balance.'</td>';
          }

          // '<td style="text-align: right;">'. $m->aicash_price.'</td>'.


          if($m->type == 'add_aicash'){
            $balance += $m->aicash_price;
            $p .= '<td style="text-align: right;">'. $m->aicash_price.'</td>'.
            '<td style="text-align: right;">'. '0'.'</td>'.
            '<td style="text-align: right;">'. '0'.'</td>';
          }else{
            $balance -= $m->aicash_price;
            $p .= '<td style="text-align: right;">'. '0'.'</td>'.
            '<td style="text-align: right;">'. '0'.'</td>'.
            '<td style="text-align: right;">'. $m->aicash_price.'</td>';
          }

          $p .= '<td style="text-align: right;">'. $m->order_code.'</td>'.
          '<td style="text-align: right;">'. '0'.'</td>'.
          '<td style="text-align: right;">'. '-'.'</td>'.
          // '<td style="text-align: right;">'. $balance.'</td>'.
          '</tr>';
        }
        return
        '<table width="100%">
        <th width="10%" style="text-align: center;">ยอดยกมา</th>
        <th width="10%" style="text-align: center;">ยอดเงินนำฝาก</th>
        <th width="10%" style="text-align: center;">ค่าธรรมเนียม</th>
        <th width="10%" style="text-align: center;">ยอดเงินใช้ไป</th>
        <th width="10%" style="text-align: center;">เลขที่บิล</th>
        <th width="10%" style="text-align: center;">ขอเงินคืน</th>
        <th width="10%" style="text-align: center;">วันที่ขอเงินคืน</th>
        <tbody>
        '.
        $p
        .'
        </tbody>
        </table>';
       })
       ->escapeColumns('detail')
      ->make(true);
  }

  public function ai_report_datatable_gv(Request $req)
  {
    // DB::table('db_movement_ai_cash')->where('customer_id_fk',0)->delete();
    // if(isset($req->startDate)){
    //   $report_type = $req->report_type;
    //   $date_start = $req->startDate;
    //   $date_end = $req->endDate;

      // if($report_type == 1){
      //   $sTable = DB::table('ai_stockist')
      //   ->select('ai_stockist.created_at as action_date','ai_stockist.customer_id','ai_stockist.code_order')
      //   ->where(DB::raw('DATE(ai_stockist.created_at)'),$date_start)
      //   ->where('ai_stockist.status','success')
      //   ->orderBy('ai_stockist.created_at','asc')
      //   ->groupBy('ai_stockist.customer_id',DB::raw('DATE(ai_stockist.created_at)'));
      // }

      // if($report_type == 2){
      //   $sTable = DB::table('ai_stockist')
      //   ->select('ai_stockist.created_at as action_date','ai_stockist.customer_id','ai_stockist.code_order')
      //   ->whereBetween('ai_stockist.created_at', [$date_start,$date_end])
      //   ->where('ai_stockist.status','success')
      //   ->orderBy('ai_stockist.created_at','asc')
      //   ->groupBy('ai_stockist.customer_id',DB::raw('DATE(ai_stockist.created_at)'));
      // }

      // if($req->customer_id != ''){
      //   $sTable->where('ai_stockist.customer_id',$req->customer_id);
      // }
      // if($req->customer_name != ''){
      //   $sTable->where('ai_stockist.customer_id',$req->customer_name);
      // }

    // }else{
      $report_type = 1;
      $date_start = '2023-11-01';
      $date_end = '2023-11-31';

      $sTable = DB::table('log_gift_voucher')
      ->select('customer_id_fk','created_at as action_date')
      // ->where(DB::raw('DATE(created_at)'),$date_start)
      ->whereBetween('created_at', [$date_start,$date_end])
      ->orderBy('created_at','asc')
      ->groupBy('customer_id_fk',DB::raw('DATE(created_at)'));
    // }

    $sQuery = \DataTables::of($sTable);
    return $sQuery
      ->addColumn('action_date', function ($row) {
       return date('d/m/Y', strtotime($row->action_date));
      })
      ->escapeColumns('action_date')


      ->addColumn('member_id', function ($row) {
        $cus = DB::table('customers')->select('user_name')->where('id',$row->customer_id_fk)->first();
        return @$cus->user_name;
       })
       ->escapeColumns('member_id')

       ->addColumn('member_name', function ($row) {
        $cus = DB::table('customers')->select('prefix_name','first_name','last_name')->where('id',$row->customer_id_fk)->first();
        return @$cus->prefix_name.' '.@$cus->first_name.' '.@$cus->last_name;
       })
       ->escapeColumns('member_name')

       ->addColumn('detail', function ($row) {

        $members_before_add = DB::table('log_gift_voucher')
        ->select('giftvoucher_value_use')
        ->where(DB::raw('DATE(created_at)'),'<',date('Y-m-d'))
        ->where('customer_id_fk',$row->customer_id_fk)
        ->where('type','Add')
        ->orderBy('code_order','asc')
        ->sum('giftvoucher_value_use');

        $members_before_remove = DB::table('log_gift_voucher')
        ->select('giftvoucher_value_use')
        ->where(DB::raw('DATE(created_at)'),'<',date('Y-m-d'))
        ->where('customer_id_fk',$row->customer_id_fk)
        ->where('type','Remove')
        ->orderBy('code_order','asc')
        ->sum('giftvoucher_value_use');

        $before_total = $members_before_add-$members_before_remove;

        $members = DB::table('log_gift_voucher')
        ->select('customer_id_fk','created_at','code_order','giftvoucher_cus_id_fk','giftvoucher_value_use','type','order_id_fk','giftvoucher_value_old','giftvoucher_value_banlance')
        ->where(DB::raw('DATE(created_at)'),date('Y-m-d', strtotime($row->action_date)))
        ->where('customer_id_fk',$row->customer_id_fk)
        ->orderBy('code_order','asc')
        ->get();

        $p = '';
        $balance = 0;
        foreach( $members as $key => $m){
          if($key == 0){
            $balance += $before_total;
          }
          $code_order = '';
          $total_price = '0';
          if($m->order_id_fk != '' && $m->order_id_fk != 0){
            $order = DB::table('db_orders')->select('total_price','invoice_code')->where('id',$m->order_id_fk)->first();
            if($order){
              $code_order = $order->invoice_code;
              $total_price = $order->total_price;
            }
          }

          $db_giftvoucher_cus = DB::table('db_giftvoucher_cus')->select('pro_sdate','pro_edate')->where('id',$m->giftvoucher_cus_id_fk)->first();


          // if($key==0){ code_order
            $p .= '<tr>'.
            '<td style="text-align: right;">'.$before_total.'</td>'.
            '<td style="text-align: right;">'. $m->code_order.'</td>'.
            '<td style="text-align: right;">'. $total_price.'</td>'.
            '<td style="text-align: right;">'. $m->giftvoucher_value_old.'</td>'
            ;
          // }else{
          //   $p .= '<tr>'.
          //   '<td style="text-align: right;">'.$balance.'</td>';
          // }

          // '<td style="text-align: right;">'. $m->aicash_price.'</td>'.


          if($m->type == 'Add'){
            $balance += $m->giftvoucher_value_use;
            $p .= '<td style="text-align: right;">'. $m->giftvoucher_value_use.'</td>'.
            '<td style="text-align: right;">'. '0'.'</td>';
          }else{
            $balance -= $m->giftvoucher_value_use;
            $p .= '<td style="text-align: right;">'. '0'.'</td>'.
            '<td style="text-align: right;">'. $m->giftvoucher_value_use.'</td>';
          }

          $p .=
          '<td style="text-align: left;">'. date('d/m/Y', strtotime($db_giftvoucher_cus->pro_sdate)).' - '.date('d/m/Y', strtotime($db_giftvoucher_cus->pro_edate)).'</td>'.
          '<td style="text-align: right;">'. $balance.'</td>'.
          '</tr>';
        }
        return
        '<table width="100%">
        <th width="10%" style="text-align: center;">ยอดยกมา</th>
        <th width="10%" style="text-align: center;">เลขที่บิล</th>
        <th width="10%" style="text-align: center;">ยอดเงิน</th>
        <th width="10%" style="text-align: center;">Voucher เก่า</th>
        <th width="10%" style="text-align: center;">Voucher ใหม่</th>
        <th width="10%" style="text-align: center;">Voucher ใช้ไปแล้ว</th>
        <th width="10%" style="text-align: center;">วันหมดอายุ</th>
        <th width="10%" style="text-align: center;">ยอดเงินคงเหลือ</th>
        <tbody>
        '.
        $p
        .'
        </tbody>
        </table>';
        // return '';
       })
       ->escapeColumns('detail')
      ->make(true);
  }

}
