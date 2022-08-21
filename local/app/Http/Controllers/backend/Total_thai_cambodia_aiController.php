<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class Total_thai_cambodia_aiController extends Controller
{

    public function index(Request $request)
    {
        $data = DB::table('dataset_business_location')
            ->get();
        $sUser = DB::select(" select * from ck_users_admin ");
        return view('backend.total_thai_cambodia_ai.index')->with([
            'business_location' => $data,
            'sUser' => $sUser
        ]);
    }

    public function create()
    {
        $sRowGroup = \App\Models\Backend\Total_thai_cambodia::orderBy('group_id', 'desc')->limit(1)->get();
        $groupMaxID = $sRowGroup[0]->group_id + 1;
        // dd($groupMaxID);
        $sLanguage = \App\Models\Backend\Language::get();

        $sBranchs = \App\Models\Backend\Branchs::get();

        $Warehouse = \App\Models\Backend\Warehouse::get();

        return View('backend.total_thai_cambodia.form')->with(array('sLanguage' => $sLanguage, 'groupMaxID' => $groupMaxID));
    }

    public function store(Request $request)
    {
        return $this->form();
    }

    public function edit($id)
    {
        $sRowGroup = \App\Models\Backend\Total_thai_cambodia::find($id);
        $sRow = \App\Models\Backend\Total_thai_cambodia::where('group_id', $sRowGroup->group_id)->get();
        // dd($sRow[0]->status);
        $sLanguage = \App\Models\Backend\Language::get();
        return View('backend.total_thai_cambodia.form')->with(array('sRow' => $sRow, 'id' => $id, 'sLanguage' => $sLanguage));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        return $this->form($id);
    }

    public function form($id = null)
    {
        \DB::beginTransaction();
        try {
            if ($id) {
                $sRow = \App\Models\Backend\Total_thai_cambodia::find($id);
                $langCnt = count(request('lang'));
                for ($i = 0; $i < $langCnt; $i++) {

                    \App\Models\Backend\Total_thai_cambodia::where('id', request('id')[$i])->update(
                        [
                            'product_unit' => request('product_unit')[$i],
                            'detail' => request('detail')[$i],

                            'date_added' => request('date_added'),

                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => request('status') ? request('status') : 0,
                        ]
                    );
                }

            } else {

                $sRowGroup = \App\Models\Backend\Total_thai_cambodia::orderBy('group_id', 'desc')->limit(1)->get();
                $groupMaxID = $sRowGroup[0]->group_id + 1;

                $langCnt = count(request('lang'));
                for ($i = 0; $i < $langCnt; $i++) {

                    \App\Models\Backend\Total_thai_cambodia::insert(
                        [
                            'lang_id' => request('lang')[$i],
                            'group_id' => $groupMaxID,

                            'product_unit' => request('product_unit')[$i],
                            'detail' => request('detail')[$i],

                            'date_added' => request('date_added'),
                            'created_at' => date('Y-m-d H:i:s'),
                            'status' => 1,
                        ]
                    );
                }

            }

            \DB::commit();

            return redirect()->action('backend\Total_thai_cambodiaController@index')->with(['alert' => \App\Models\Alert::Msg('success')]);

        } catch (\Exception $e) {
            echo $e->getMessage();
            \DB::rollback();
            return redirect()->action('backend\Total_thai_cambodiaController@index')->with(['alert' => \App\Models\Alert::e($e)]);
        }
    }

    public function destroy($id)
    {
        $sRow = \App\Models\Backend\Total_thai_cambodia::find($id);
        if ($sRow) {
            $sRow->forceDelete();
        }
        return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $rs)
    {
        if ($rs->startDate) {
            $date = str_replace('/', '-', $rs->startDate);
            $s_date = date('Y-m-d', strtotime($date));
            $startDate = " AND DATE(db_orders.created_at) >= '" . $s_date . "' ";
        } else {
            $startDate = '';
        }

        if ($rs->endDate) {
            $date = str_replace('/', '-', $rs->endDate);
            $e_date = date('Y-m-d', strtotime($date));
            $endDate = " AND DATE(db_orders.created_at) <= '" . $e_date . "' ";
        } else {
            $endDate = '';
        }

        if ($rs->action_user) {
            $action_user = " AND db_orders.action_user = $rs->action_user ";

        } else {
            $action_user = '';
        }

        if ($rs->business_location) {
            $business_location_id_fk = " AND db_orders.business_location_id_fk = " . $rs->business_location . " ";
        } else {
            $business_location_id_fk = "";
        }

        if ($rs->report_type) {
            $report_type = $rs->report_type;
        } else {
            $report_type = "day";
        }

   // แบบไม่ sum
        if($report_type == 'day'){
            $sTable =DB::select("
            SELECT
            db_orders.action_user,
            ck_users_admin.`name` as action_user_name,
            ck_users_admin.`first_name` as action_first_name,
            ck_users_admin.`last_name` as action_last_name,
            db_orders.pay_type_id_fk,
            dataset_pay_type.detail AS pay_type,
            date(db_orders.action_date) AS action_date,
            db_orders.branch_id_fk,
            branchs.b_name as branchs_name,
            dataset_business_location.txt_desc as business_location_name,

            db_orders.sum_credit_price,
            db_orders.transfer_price,
            db_orders.fee_amt,
            db_orders.aicash_price,
            db_orders.cash_pay,
            db_orders.gift_voucher_price,
            db_orders.shipping_price,
            db_orders.product_value,
            db_orders.tax,
            db_orders.sum_price,
            db_orders.pv_total,

            db_orders.code_order

            FROM
            db_orders
            Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
            Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
            Left Join branchs ON branchs.id = db_orders.branch_id_fk
            Left Join dataset_business_location ON dataset_business_location.id = db_orders.business_location_id_fk
            WHERE db_orders.approve_status not in (5) AND db_orders.check_press_save=2
            $startDate
            $endDate
            $action_user
            $business_location_id_fk
            ORDER BY action_date ASC
        ");
        }else{
            $sTable =DB::select("
            SELECT
            db_orders.action_user,
            ck_users_admin.`name` as action_user_name,
            ck_users_admin.`first_name` as action_first_name,
            ck_users_admin.`last_name` as action_last_name,
            db_orders.pay_type_id_fk,
            dataset_pay_type.detail AS pay_type,
            date_format(db_orders.action_date, '%M') AS action_date,
            db_orders.branch_id_fk,
            branchs.b_name as branchs_name,
            dataset_business_location.txt_desc as business_location_name,

            SUM(CASE WHEN db_orders.sum_credit_price is null THEN 0 ELSE db_orders.sum_credit_price END) AS sum_credit_price,
            SUM(CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) AS transfer_price,
            SUM(CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) AS fee_amt,
            SUM(CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) AS aicash_price,
            SUM(CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) AS cash_pay,
            SUM(CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END) AS gift_voucher_price,
            SUM(CASE WHEN db_orders.shipping_price is null THEN 0 ELSE db_orders.shipping_price END) AS shipping_price,
            SUM(CASE WHEN db_orders.product_value is null THEN 0 ELSE db_orders.product_value END) AS product_value,
            SUM(CASE WHEN db_orders.tax is null THEN 0 ELSE db_orders.tax END) AS tax,
            SUM(CASE WHEN db_orders.sum_price is null THEN 0 ELSE db_orders.sum_price END) AS sum_price,
            SUM(CASE WHEN db_orders.pv_total is null THEN 0 ELSE db_orders.pv_total END) AS pv_total,

            db_orders.code_order

            FROM
            db_orders
            Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
            Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
            Left Join branchs ON branchs.id = db_orders.branch_id_fk
            Left Join dataset_business_location ON dataset_business_location.id = db_orders.business_location_id_fk
            WHERE db_orders.approve_status not in (5) AND db_orders.check_press_save=2
            $startDate
            $endDate
            $action_user
            $business_location_id_fk
            GROUP BY action_user , date_format(db_orders.action_date, '%M')
            ORDER BY action_date ASC
        ");
        }

    $sQuery = \DataTables::of($sTable);
    return $sQuery
        ->addColumn('branchs_name', function ($row) {
            return $row->branchs_name;
        })
        ->addColumn('business_location_name', function ($row) {
            return $row->business_location_name;
        })
        ->addColumn('action_date', function ($row) use($report_type) {

            if($report_type == 'day'){
                $action_date = date('d/m/Y', strtotime($row->action_date));
              }else{
                $action_date = date('m/Y', strtotime($row->action_date));
              }

            return $action_date;
        })

        ->addColumn('invoice' , function ($row) use($startDate, $endDate,  $action_user,$business_location_id_fk,$report_type) {

            if($report_type == 'day'){
                return $row->code_order;
            }else{
                return '-';
            }
        })
        ->escapeColumns('invoice')

        ->addColumn('invoice_total', function ($row) use($startDate, $endDate,  $action_user,$business_location_id_fk) {

            return number_format($row->sum_price, 2);
        })


        ->addColumn('total_price', function ($row) {
            return number_format($row->cash_pay, 2);
        })
        ->addColumn('total_transfer', function ($row) {
            return number_format($row->transfer_price, 2);
        })

        ->addColumn('total_credit_card', function ($row) {
            return number_format($row->sum_credit_price, 2);
        })
        ->addColumn('total_aicash', function ($row) {
            return number_format($row->aicash_price, 2);
        })

        ->addColumn('pv_total', function ($row) {
            return number_format($row->pv_total, 2);
        })

        ->addColumn('total_balance', function ($row) {
            return number_format($row->cash_pay+
            $row->transfer_price+
            $row->sum_credit_price+
            $row->aicash_price, 2);
        })
        ->addColumn('total_add_aicash', function ($row) {
            return number_format(0, 2);
        })

        ->addColumn('action_user', function ($row) {
        if($row->action_user_name == ''){
            $row->action_user_name = 'V3';
        }
            return $row->action_user_name;
        })
        ->make(true);
    }

    public function Datatable_aicash_full(Request $rs)
    {
        if ($rs->startDate) {
            $date = str_replace('/', '-', $rs->startDate);
            $s_date = date('Y-m-d', strtotime($date));
            $startDate = " AND DATE(db_movement_ai_cash.created_at) >= '" . $s_date . "' ";
        } else {
            $startDate = '';
        }

        if ($rs->endDate) {
            $date = str_replace('/', '-', $rs->endDate);
            $e_date = date('Y-m-d', strtotime($date));
            $endDate = " AND DATE(db_movement_ai_cash.created_at) <= '" . $e_date . "' ";
        } else {
            $endDate = '';
        }

        if ($rs->action_user) {
            // $action_user = " AND db_add_ai_cash.action_user = $rs->action_user ";
            $action_user = '';

        } else {
            $action_user = '';
        }

        if ($rs->business_location) {
            // $business_location_id_fk = " AND db_add_ai_cash.business_location_id_fk = " . $rs->business_location . " ";
            $business_location_id_fk = "";
        } else {
            $business_location_id_fk = "";
        }

        if ($rs->report_type) {
            $report_type = $rs->report_type;
        } else {
            $report_type = "day";
        }

        // แบบไม่ sum
        if($report_type == 'day'){
            $sTable =DB::select("
            SELECT
            db_movement_ai_cash.*,
            customers.upline_id,
            customers.first_name,
            customers.prefix_name,
            customers.last_name

            FROM
            db_movement_ai_cash
            Left Join customers ON customers.id = db_movement_ai_cash.customer_id_fk
            WHERE db_movement_ai_cash.order_code IS NOT NULL
            AND db_movement_ai_cash.type IN ('buy_product','add_aicash')
            $startDate
            $endDate
            ORDER BY created_at ASC
        ");

        }else{

          $sTable =DB::select("
          SELECT
          db_movement_ai_cash.created_at,
          SUM(CASE WHEN db_movement_ai_cash.aicash_banlance is null THEN 0 ELSE db_movement_ai_cash.aicash_banlance END) AS aicash_banlance,
          SUM(CASE WHEN db_movement_ai_cash.aicash_price is null THEN 0 ELSE db_movement_ai_cash.aicash_price END) AS aicash_price,
          db_movement_ai_cash.type,
          db_movement_ai_cash.order_id_fk,
          db_movement_ai_cash.add_ai_cash_id_fk,
          customers.upline_id,
          customers.first_name,
          customers.prefix_name,
          customers.last_name

          FROM
          db_movement_ai_cash
          Left Join customers ON customers.id = db_movement_ai_cash.customer_id_fk
          WHERE db_movement_ai_cash.order_code IS NOT NULL
          AND db_movement_ai_cash.type IN ('buy_product','add_aicash')
          $startDate
          $endDate
          GROUP BY db_movement_ai_cash.customer_id_fk , date_format(db_movement_ai_cash.created_at, '%M') , db_movement_ai_cash.type
          ORDER BY created_at ASC
      ");

        //     $sTable =DB::select("
        //     SELECT
        //     db_movement_ai_cash.*

        //     SUM(CASE WHEN db_add_ai_cash.sum_credit_price is null THEN 0 ELSE db_add_ai_cash.sum_credit_price END) AS sum_credit_price,
        //     SUM(CASE WHEN db_add_ai_cash.transfer_price is null THEN 0 ELSE db_add_ai_cash.transfer_price END) AS transfer_price,
        //     SUM(CASE WHEN db_add_ai_cash.fee_amt is null THEN 0 ELSE db_add_ai_cash.fee_amt END) AS fee_amt,
        //     SUM(CASE WHEN db_add_ai_cash.cash_pay is null THEN 0 ELSE db_add_ai_cash.cash_pay END) AS cash_pay,
        //     SUM(CASE WHEN db_add_ai_cash.gift_voucher_price is null THEN 0 ELSE db_add_ai_cash.gift_voucher_price END) AS gift_voucher_price,

        //     db_add_ai_cash.code_order

        //     FROM
        //     db_add_ai_cash
        //     Left Join dataset_pay_type ON db_add_ai_cash.pay_type_id_fk = dataset_pay_type.id
        //     Left Join ck_users_admin ON db_add_ai_cash.action_user = ck_users_admin.id
        //     Left Join branchs ON branchs.id = db_add_ai_cash.branch_id_fk
        //     Left Join dataset_business_location ON dataset_business_location.id = db_add_ai_cash.business_location_id_fk
        //     Left Join customers ON customers.id = db_add_ai_cash.customer_id_fk
        //     WHERE db_add_ai_cash.approve_status in (2,4,9)
        //     $startDate
        //     $endDate
        //     $action_user
        //     $business_location_id_fk
        //     GROUP BY action_user , date_format(db_add_ai_cash.created_at, '%M')
        //     ORDER BY action_date ASC
        // ");

        }
    $sQuery = \DataTables::of($sTable);
    // dd($sTable);
    return $sQuery
        // ->addColumn('branchs_name', function ($row) {
        //     return $row->branchs_name;
        // })
        // ->addColumn('business_location_name', function ($row) {
        //     return $row->business_location_name;
        // })
        ->addColumn('action_date', function ($row) use($report_type) {

            if($report_type == 'day'){
                $action_date = date('d/m/Y', strtotime($row->created_at));
              }else{
                $action_date = date('m/Y', strtotime($row->created_at));
              }

            return $action_date;
        })

        ->addColumn('invoice' , function ($row) use($startDate, $endDate,  $action_user,$business_location_id_fk,$report_type) {
            if($report_type == 'day'){
              $a = "";
              if($row->type=='add_aicash'){
                $a = '<a href="'.url('backend/add_ai_cash/'.$row->add_ai_cash_id_fk.'/edit').'" target="bank">'.$row->order_code.'</a>';
              }
              if($row->type=='buy_product'){
                $a = '<a href="'.url('backend/frontstore/'.$row->order_id_fk.'/edit').'" target="bank">'.$row->order_code.'</a>';
              }
                return $a;
            }else{
                return '-';
            }
        })
        ->escapeColumns('invoice')
        // ->addColumn('total_price', function ($row) {
        //     return number_format($row->cash_pay, 2);
        // })
        // ->addColumn('total_transfer', function ($row) {
        //     return number_format($row->transfer_price, 2);
        // })

        // ->addColumn('total_credit_card', function ($row) {
        //     return number_format($row->sum_credit_price, 2);
        // })

        ->addColumn('total_balance', function ($row) {
            return number_format($row->aicash_banlance, 2);
        })

        ->addColumn('got', function ($row) {
          if($row->type=='add_aicash'){
            return number_format($row->aicash_price, 2);
          }else{
            return number_format(0, 2);
          }

      })
      ->addColumn('lost', function ($row) {
        if($row->type=='buy_product'){
          return number_format($row->aicash_price, 2);
        }else{
          return number_format(0, 2);
        }
    })

        ->addColumn('customer_name', function ($row) {

            return '['.$row->upline_id.'] '.$row->prefix_name.' '.$row->first_name.' '.$row->last_name;
        })
        ->make(true);
    }

    public function DatatableTotalThai(Request $rs)
    {

        if ($rs->startDate) {
            $date = str_replace('/', '-', $rs->startDate);
            $s_date = date('Y-m-d', strtotime($date));

        } else {
            $s_date = '';
        }

        if ($rs->endDate) {
            $date = str_replace('/', '-', $rs->endDate);
            $e_date = date('Y-m-d', strtotime($date));
        } else {
            $e_date = '';
        }

        $sTable = DB::table('db_total_thai_cambodia')
            ->select(DB::raw('dataset_business_location.txt_desc , SUM(db_total_thai_cambodia.total_balance) as total_balance , SUM(db_total_thai_cambodia.total_price) as total_price
          ,SUM(db_total_thai_cambodia.total_transfer) as total_transfer,SUM(db_total_thai_cambodia.total_credit_card) as total_credit_card,SUM(db_total_thai_cambodia.total_aicash) as total_aicash
          ,SUM(db_total_thai_cambodia.total_add_aicash) as total_add_aicash'), 'branchs.id', 'branchs.b_name', 'branchs.b_details')
            ->leftjoin('dataset_business_location', 'dataset_business_location.country_id_fk', '=', 'db_total_thai_cambodia.business_location_id_fk')
            ->leftjoin('branchs', 'branchs.id', '=', 'db_total_thai_cambodia.branchs_id_fk')
            // ->whereRaw(("case WHEN '{$rs->business_location}' = '' THEN 1 else db_total_thai_cambodia.business_location_id_fk = '{$rs->business_location}' END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_total_thai_cambodia.action_date) = '{$s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_total_thai_cambodia.action_date) >= '{$s_date}' and date(db_total_thai_cambodia.action_date) <= '{$e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(db_total_thai_cambodia.action_date) = '{$e_date}' else 1 END"))
            ->where('db_total_thai_cambodia.business_location_id_fk', 1)
            ->groupby('db_total_thai_cambodia.branchs_id_fk')
            ->get();

        $sQuery = \DataTables::of($sTable);
        return $sQuery
            ->addColumn('branchs', function ($row) {
                return "{$row->b_name} ({$row->b_details})";
            })
            ->addColumn('total_balance', function ($row) {
                return number_format($row->total_balance, 2);
            })
            ->addColumn('total_price', function ($row) {
                return number_format($row->total_price, 2);
            })
            ->addColumn('total_transfer', function ($row) {
                return number_format($row->total_transfer, 2);
            })

            ->addColumn('total_credit_card', function ($row) {
                return number_format($row->total_credit_card, 2);
            })
            ->addColumn('total_aicash', function ($row) {
                return number_format($row->total_aicash, 2);
            })

            ->addColumn('total_add_aicash', function ($row) {
                return number_format($row->total_add_aicash, 2);
            })

            ->make(true);
    }

    public function DatatableTotalCambodia(Request $rs)
    {
        if ($rs->startDate) {
            $date = str_replace('/', '-', $rs->startDate);
            $s_date = date('Y-m-d', strtotime($date));

        } else {
            $s_date = '';
        }

        if ($rs->endDate) {
            $date = str_replace('/', '-', $rs->endDate);
            $e_date = date('Y-m-d', strtotime($date));
        } else {
            $e_date = '';
        }

        $sTable = DB::table('db_total_thai_cambodia')
            ->select(DB::raw('dataset_business_location.txt_desc , SUM(db_total_thai_cambodia.total_balance) as total_balance , SUM(db_total_thai_cambodia.total_price) as total_price
          ,SUM(db_total_thai_cambodia.total_transfer) as total_transfer,SUM(db_total_thai_cambodia.total_credit_card) as total_credit_card,SUM(db_total_thai_cambodia.total_aicash) as total_aicash
          ,SUM(db_total_thai_cambodia.total_add_aicash) as total_add_aicash'), 'branchs.id', 'branchs.b_name', 'branchs.b_details')
            ->leftjoin('dataset_business_location', 'dataset_business_location.country_id_fk', '=', 'db_total_thai_cambodia.business_location_id_fk')
            ->leftjoin('branchs', 'branchs.id', '=', 'db_total_thai_cambodia.branchs_id_fk')
            // ->whereRaw(("case WHEN '{$rs->business_location}' = '' THEN 1 else db_total_thai_cambodia.business_location_id_fk = '{$rs->business_location}' END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_total_thai_cambodia.action_date) = '{$s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_total_thai_cambodia.action_date) >= '{$s_date}' and date(db_total_thai_cambodia.action_date) <= '{$e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(db_total_thai_cambodia.action_date) = '{$e_date}' else 1 END"))
            ->where('db_total_thai_cambodia.business_location_id_fk', 5)
            ->groupby('db_total_thai_cambodia.branchs_id_fk')
            ->get();

        $sQuery = \DataTables::of($sTable);
        return $sQuery
            ->addColumn('branchs', function ($row) {
                return "{$row->b_name} ({$row->b_details})";
            })
            ->addColumn('total_balance', function ($row) {
                return number_format($row->total_balance, 2);
            })
            ->addColumn('total_price', function ($row) {
                return number_format($row->total_price, 2);
            })
            ->addColumn('total_transfer', function ($row) {
                return number_format($row->total_transfer, 2);
            })

            ->addColumn('total_credit_card', function ($row) {
                return number_format($row->total_credit_card, 2);
            })
            ->addColumn('total_aicash', function ($row) {
                return number_format($row->total_aicash, 2);
            })

            ->addColumn('total_add_aicash', function ($row) {
                return number_format($row->total_add_aicash, 2);
            })

            ->make(true);
    }

}
