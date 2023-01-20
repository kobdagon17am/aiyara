<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon\Carbon;
class GiftVoucherController extends Controller
{
    public function __construct()
    {
      $this->middleware('customer');
    }

    public function index()
    {

        return view('frontend/giftvoucher_history');
    }

    public function gift_order_history()
    {

        return view('frontend/gift_order_history');
    }

    public function dt_giftvoucher_history(Request $rs)
    {

        $status = $rs->status;
        $s_date = !empty($rs->s_date) ? date('Y-m-d', strtotime($rs->s_date)) : '';
        $e_date = !empty($rs->e_date) ? date('Y-m-d', strtotime($rs->e_date)) : '';
        $date = date('Y-m-d H:i:s');

        // $gift_voucher = DB::table('gift_voucher')
        //     ->select('*')
        //     ->where('gift_voucher.customer_id', '=', Auth::guard('c_user')->user()->id)
        //     ->whereRaw(("case WHEN '{$status}' = 'expiry_date' THEN gift_voucher.expiry_date < '{$date}' WHEN '{$status}' = 'not_expiry_date' THEN  gift_voucher.expiry_date >= '{$date}'
        // else 1 END"))
        //     ->orderby('id', 'DESC')
        //     ->get();

            $gift_voucher = DB::table('db_giftvoucher_cus')
            ->select('db_giftvoucher_cus.*','db_giftvoucher_code.descriptions'
            ,'db_giftvoucher_code.status as status_cancel_active')
            ->leftjoin('db_giftvoucher_code', 'db_giftvoucher_code.id', '=', 'db_giftvoucher_cus.giftvoucher_code_id_fk')
            ->where('db_giftvoucher_cus.customer_username', '=', Auth::guard('c_user')->user()->user_name)
            ->whereraw('(db_giftvoucher_cus.pro_status = 1 || db_giftvoucher_cus.pro_status = 2)')
            ->whereRaw(("case WHEN '{$status}' = 'expiry_date' THEN db_giftvoucher_cus.pro_edate < '{$date}' WHEN '{$status}' = 'not_expiry_date' THEN  db_giftvoucher_cus.pro_edate >= '{$date}'
        else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_giftvoucher_cus.pro_sdate) = '{$s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_giftvoucher_cus.pro_sdate) >= '{$s_date}' and date(db_giftvoucher_cus.pro_sdate) <= '{$e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' = ''  and '{$e_date}' != ''  THEN  date(db_giftvoucher_cus.pro_edate) = '{$e_date}' else 1 END"))
            ->orderby('db_giftvoucher_cus.pro_edate', 'ASC')
            ->get();


         $sQuery = DataTables::of($gift_voucher);
        return $sQuery

            ->addColumn('pro_sdate', function ($row) {
                $date = '<label class="label label-inverse-info-border"><b>' . date('Y/m/d', strtotime($row->pro_sdate)) . '</b></label>';
                return $date;
            })

            ->addColumn('pro_edate', function ($row) {
                $expiry_date = strtotime($row->pro_edate);
                if (empty($row->pro_edate)) {
                    $expiry_date_html = '';
                } elseif ($expiry_date <= strtotime(date('Y-m-d'))) {
                    $expiry_date_html = '<label class="label label-inverse-danger"><b>' . date('Y/m/d', $expiry_date) . '</b></label>';
                } else {
                    $expiry_date_html = '<label class="label label-inverse-success"><b>' . date('Y/m/d', $expiry_date) . '</b></label>';
                }
                return $expiry_date_html;
            })

            ->addColumn('detail', function ($row) {
              if($row->giftvoucher_type == 'gencode'){
                return $row->descriptions;
              }else{
                return $row->detail;
              }
            })

            ->addColumn('giftvoucher_value', function ($row) {
                $gv = '<b class="text-primary">' . number_format($row->giftvoucher_value) . '</b>';
                return $gv;
            })

            ->addColumn('giftvoucher_banlance', function ($row) {
              $gv = '<b class="text-danger">' . number_format($row->giftvoucher_banlance) . '</b>';
              return $gv;
          })
            ->addColumn('banlance', function ($row) {
                $banlance = '<b class="text-success">' . number_format($row->giftvoucher_banlance) . '</b>';
                return $banlance;
            })

            ->addColumn('expri', function ($row) {

              if(strtotime(date('Y-m-d')) <= strtotime($row->pro_edate)){
               $expri_date  = '<label class="label label-inverse-success"><b>'.Carbon::createFromFormat('Y-m-d',$row->pro_edate)->diffForHumans().'</b></label>';
              //   $expri_date  = '1';
               }else{
                $expri_date  = '<label class="label label-inverse-danger"><b?>Expri</b></label>';
              }

              return $expri_date;
          })

            ->rawColumns(['pro_sdate','pro_edate','expiry_date','giftvoucher_value','banlance','expri'])
            ->make(true);
    }

    public function dt_gift_order_history(Request $rs)
    {
      $s_date = !empty($rs->s_date) ? date('Y-m-d', strtotime($rs->s_date)) : '';
      $e_date = !empty($rs->e_date) ? date('Y-m-d', strtotime($rs->e_date)) : '';
      $date = date('Y-m-d H:i:s');

          $log_gift_voucher = DB::table('log_gift_voucher')
          ->select('log_gift_voucher.*')
          ->where('log_gift_voucher.customer_id_fk', '=', Auth::guard('c_user')->user()->id)
          ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(log_gift_voucher.created_at) = '{$s_date}' else 1 END"))
          ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(log_gift_voucher.created_at) >= '{$s_date}' and date(log_gift_voucher.created_at) <= '{$e_date}'else 1 END"))
          ->whereRaw(("case WHEN '{$s_date}' = ''  and '{$e_date}' != ''  THEN  date(log_gift_voucher.created_at) = '{$e_date}' else 1 END"))
          ->orderby('log_gift_voucher.id', 'DESC')
          ->get();


       $sQuery = DataTables::of($log_gift_voucher);
      return $sQuery

          ->addColumn('date', function ($row) {
              $date = '<label class="label label-inverse-info-border"><b>' . date('Y/m/d H:i:s', strtotime($row->created_at)) . '</b></label>';
              return $date;
          })

          ->addColumn('code_order', function ($row) {
            $order_id = DB::table('db_orders')
            ->select('id')
            ->where('code_order', '=', $row->code_order)
            ->where('customers_id_fk', '=', $row->customer_id_fk)
            ->first();
            if( $row->code_order){
              $code_order = '<label class="label label-inverse-info-border"><a href="'.route('cart-payment-history', ['code_order' => $order_id->id]) .'">'.$row->code_order.'</a></label>';
            }else{
              $code_order = '';
            }

            return $code_order;
        })

          ->addColumn('giftvoucher_value_old', function ($row) {
            $gv = '<b class="text-primary">' . number_format($row->giftvoucher_value_old) . '</b>';
            return $gv;
        })

          ->addColumn('giftvoucher_value_use', function ($row) {
            if( $row->type == 'Add'){
              $gv = '<b class="text-success"> +' . number_format($row->giftvoucher_value_use) . '</b>';

            }else{
              $gv = '<b class="text-danger"> -' . number_format($row->giftvoucher_value_use) . '</b>';
            }

              return $gv;
          })

          ->addColumn('giftvoucher_value_banlance', function ($row) {
            $gv = '<b>' . number_format($row->giftvoucher_value_banlance) . '</b>';
            return $gv;
        })
          ->addColumn('status', function ($row) {
              return $row->status;
          })

          ->addColumn('type', function ($row) {
            return $row->type;
        })



          ->rawColumns(['date','giftvoucher_value_old','giftvoucher_value_use','giftvoucher_value_banlance','code_order'])
          ->make(true);
  }



}
