<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class Po_approveController extends Controller
{

    public function index(Request $request)
    {
        $sBusiness_location = \App\Models\Backend\Business_location::get();
        $sBranchs = \App\Models\Backend\Branchs::get();
      // $customer = DB::select(" SELECT
      //         customers.user_name AS cus_code,
      //         customers.prefix_name,
      //         customers.first_name,
      //         customers.last_name,
      //         db_add_ai_cash.customer_id_fk
      //         FROM
      //         db_add_ai_cash
      //         left Join customers ON db_add_ai_cash.customer_id_fk = customers.id
      //         GROUP BY db_add_ai_cash.customer_id_fk
      //         ");
      // // $sPay_product_status = \App\Models\Backend\Pay_product_status::get();
      // $sInvoice_code = DB::select(" SELECT
      //   db_add_ai_cash.invoice_code
      //   FROM
      //   db_add_ai_cash where invoice_code is not null
      //   ");

      // $sAdmin = DB::select(" select * from ck_users_admin where isActive='Y' AND branch_id_fk=".\Auth::user()->branch_id_fk." ");
   //   $sApprover = DB::select(" select * from ck_users_admin where isActive='Y' AND branch_id_fk=".\Auth::user()->branch_id_fk." AND id in (select approver from db_add_ai_cash) ");

      // $sApprover = DB::select(" select * from ck_users_admin where isActive='Y' AND branch_id_fk=".\Auth::user()->branch_id_fk." ");
        $sDoc_id = DB::select(" select id from db_add_ai_cash WHERE pay_type_id_fk in (1,8,10,11,12) ");
        $sApprover = DB::select(" select * from ck_users_admin where isActive='Y' AND branch_id_fk=".\Auth::user()->branch_id_fk." ");

        return View('backend.po_approve.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,
           'sBranchs'=>$sBranchs,
           'sDoc_id'=>$sDoc_id,
           'sApprover'=>$sApprover,
        ) );

    }


    public function create()
    {
    }
    public function store(Request $request)
    {
    }

    public function edit($id)
    {
        $sRow = \App\Models\Backend\Orders::find($id);
        $slip = DB::table('payment_slip')->where('order_id', '=', $id)->orderby('id', 'asc')->get();
        // dd($slip);
        return view('backend.po_approve.form')->with([
            'sRow' => $sRow,
            'id' => $id,
            'slip' => $slip,
        ]);
    }

    public function update(Request $request, $id)
    {
        \DB::beginTransaction();
        try {

            if ($id) {
                $sRow = \App\Models\Backend\Orders::find($id);
            } else {
                return redirect()->action('backend\Po_approveController@index')->with(['alert' => 'Id Emty']);
            }

            $sRow->approver = \Auth::user()->id;
            $sRow->updated_at = now();

            if (@request('approved') != null) {
                $sRow->status_slip = 'true';
                $sRow->order_status_id_fk = '5';
                $date = str_replace("T", " ", $request->slip_date);
                $sRow->account_bank_name_customer = $request->bank_name;
                $sRow->transfer_money_datetime = $date;
                //$sRow->date_action_pv  = now();
            }

            if (@request('no_approved') != null) {
                $sRow->note = $request->detail;
                $sRow->status_slip = 'false';
                $sRow->order_status_id_fk = '3';
            }

            if (@request('approved') != null) {
                $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme($id, \Auth::user()->id, '1', 'admin');

            }

            $sRow->save();
            \DB::commit();

            return redirect()->action('backend\Po_approveController@index')->with(['alert' => \App\Models\Alert::Msg('success')]);

        } catch (\Exception $e) {
            echo $e->getMessage();
            \DB::rollback();
            return redirect()->action('backend\Po_approveController@index')->with(['alert' => \App\Models\Alert::e($e)]);
        }
    }

    public function form(Request $request)
    {

    }

    public function destroy($id)
    {

    }

    public function DatatableSet()
    {
        $sTable = \App\Models\Backend\Orders::search()->orderBy('id', 'asc');

        $sQuery = \DataTables::of($sTable);
        return $sQuery
            ->addColumn('price', function ($row) {
              if ($row->purchase_type_id_fk == 7) {
                return number_format($row->sum_price, 2);
            } else if ($row->purchase_type_id_fk == 5) {
                $total_price = $row->total_price - $row->gift_voucher_price;
                return number_format($total_price, 2);
            } else {
                return number_format($row->sum_price + $row->shipping_price, 2);
            }
            })
            ->addColumn('type', function ($row) {
                $D = DB::table('dataset_orders_type')->where('group_id', '=', $row->purchase_type_id_fk)->get();
                return @$D[0]->orders_type;
            })
            ->addColumn('date', function ($row) {
                return date('d/m/Y H:i:s', strtotime($row->created_at));
            })
            ->make(true);
    }



    public function Datatable(Request $req)
    {

        if(!empty($req->id)){
            $w01 = $req->id;
            $con01 = "=";
        }else{
            $w01 = "";
            $con01 = "!=";
        }

        $sTable = DB::table('db_orders')
            ->select('db_orders.*', 'db_orders.id as orders_id', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type', 'dataset_pay_type.detail as pay_type_name')
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
            ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
            ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
            ->where('dataset_order_status.lang_id', '=', '1')
            ->where('dataset_orders_type.lang_id', '=', '1')
           // ->where('db_orders.purchase_type_id_fk', '!=', '6')
            ->where('db_orders.order_status_id_fk', '=', '2')
            ->where('db_orders.id', $con01, $w01)
            ->orderby('id', 'ASC')
            ->get();

        $sQuery = \DataTables::of($sTable);
        return $sQuery
            ->addColumn('price', function ($row) {
                if ($row->purchase_type_id_fk == 7) {
                    return number_format($row->sum_price, 2);
                } else if ($row->purchase_type_id_fk == 5) {
                    $total_price = $row->total_price - $row->gift_voucher_price;
                    return number_format($total_price, 2);
                } else {
                    return number_format($row->sum_price + $row->shipping_price, 2);
                }

            })
            // ->addColumn('date', function ($row) {
            //     return date('d/m/Y H:i:s', strtotime($row->created_at));
            // })
             ->addColumn('customer_name', function($row) {
                $Customer = DB::select(" select * from customers where id=".$row->customers_id_fk." ");
                return $Customer[0]->user_name." : ".$Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name;
              })

             ->addColumn('note_fullpayonetime', function($row) {
                $n = '';
                $n .= $row->note_fullpayonetime_02."<br>";
                $n .= $row->note_fullpayonetime_03."<br>";
                return $row->note_fullpayonetime."<br>".$n;
              })
             ->escapeColumns('note_fullpayonetime')

             ->addColumn('transfer_money_datetime', function($row) {
                $n = '';
                $n .= !empty($row->transfer_money_datetime_02)?$row->transfer_money_datetime_02."<br>":'';
                $n .= !empty($row->transfer_money_datetime_03)?$row->transfer_money_datetime_03."<br>":'';
                return $row->transfer_money_datetime."<br>".$n;
              })
             ->escapeColumns('transfer_money_datetime')

             // ->addColumn('pay_with_other_bill_note', function($row) {
             //    return $row->pay_with_other_bill_note;
             //  })
             // ->escapeColumns('pay_with_other_bill_note')

            ->addColumn('status', function ($row) {
                return $row->detail;
            })
            ->make(true);
    }


}


