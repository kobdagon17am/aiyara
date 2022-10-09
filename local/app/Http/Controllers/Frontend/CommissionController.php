<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Frontend;
use App\Http\Controllers\Controller;
use Auth;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CommissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer');
    }

    public function commission_bonus_transfer()
    {
        return view('frontend/commission_bonus_transfer');

    }
    public function dt_commission_bonus_transfer(Request $rs)
    {
        $s_date = !empty($rs->s_date) ? date('Y-m-d', strtotime($rs->s_date)) : date('Y-m-01');
        $e_date = !empty($rs->e_date) ? date('Y-m-d', strtotime($rs->e_date)) : date('Y-m-t');

        $date_between = [$s_date, $e_date];

        $db_commission_bonus_transfer = DB::table('db_report_bonus_transfer')
            ->where('customer_username', '=', Auth::guard('c_user')->user()->user_name)
            ->when($date_between, function ($query, $date_between) {
                return $query->whereBetween('bonus_transfer_date', $date_between);
            })
            ->orderby('bonus_transfer_date', 'DESC')
            ->get();
        //$customer_data = Frontend::get_customer('8');
        //dd($db_commission_faststart);
        $sQuery = Datatables::of($db_commission_bonus_transfer);
        return $sQuery

            ->addColumn('bonus_transfer_date', function ($row) {
                return date('Y/m/d', strtotime($row->bonus_transfer_date));
            })
            ->addColumn('tax_percent', function ($row) {
              return number_format($row->tax_percent).'%';
          })

            ->addColumn('bonus_total', function ($row) {
                if ($row->bonus_total) {
                    return number_format($row->bonus_total, 2);
                } else {
                    return '-';
                }
            })
            ->addColumn('tax', function ($row) {
                if ($row->tax) {
                    return number_format($row->tax, 2);
                } else {
                    return '-';
                }
            })
            ->addColumn('fee', function ($row) {
                if ($row->fee) {
                    return number_format($row->fee, 2);
                } else {
                    return '-';
                }
            })
            ->addColumn('price_transfer_total', function ($row) {
                if ($row->price_transfer_total) {
                    return number_format($row->price_transfer_total, 2);
                } else {
                    return '-';
                }
            })

            ->addColumn('status_transfer', function ($row) {

              if ($row->status_transfer == '0') {
                  $status = 'รออนุมัติ';
              } elseif ($row->status_transfer == '1') {
                  $status = 'โอนสำเร็จ';
              } elseif ($row->status_transfer == '2') {
                  $status = 'ยกเลิก';
              } elseif ($row->status_transfer == '3') {
                  $status = 'ไม่อนุมัติ';
              } else {
                  $status = '-';
              }
              return $status;
          })

            ->addColumn('action', function ($row) {
                $date = strtotime($row->bonus_transfer_date);
                return "<button class='btn btn-sm btn-success btn-outline-success' onclick='modal_commission_transfer($date)'><i class='fa fa-search'></i></button>";
            })
            ->rawColumns(['action','transfer_result'])
            ->make(true);
    }

    public function modal_commission_transfer(Request $rs)
    {

        $date = (date('Y-m-d', $rs->date));
        $data = DB::table('db_report_bonus_per_day')
            ->where('customer_username', '=', Auth::guard('c_user')->user()->user_name)
            ->where('status_payment', '=', 1)
            ->wheredate('transfer_date', '=', $date)
            ->orderby('action_date', 'DESC')
            ->get();

        $total = DB::table('db_report_bonus_per_day')
            ->select(db::raw('sum(faststart) as faststart_total ,sum(tmb) as tmb_total,sum(booster) as booster_total
        ,sum(reward) as reward_total,sum(team_maker) as team_maker_total,sum(pro) as pro_total,sum(bonus_total) as sum_bonus_total'))
            ->where('customer_username', '=', Auth::guard('c_user')->user()->user_name)
            ->where('status_payment', '=', 1)
            ->wheredate('transfer_date', '=', $date)
            ->orderby('action_date', 'DESC')
            ->first();

        return view('frontend.modal.modal_commission_transfer', compact('data', 'date', 'total'));
    }

    public function commission_per_day()
    {

        return view('frontend/commission_per_day');
    }

    public function dt_commission_perday(Request $rs)
    {
        $s_date = !empty($rs->s_date) ? date('Y-m-d 00:00:00', strtotime($rs->s_date)) : date('Y-m-01 00:00:00');
        $e_date = !empty($rs->e_date) ? date('Y-m-d 23:59:59', strtotime($rs->e_date)) : date('Y-m-t 23:59:59');

        $date_between = [$s_date, $e_date];

        $db_report_bonus_per_day = DB::table('db_report_bonus_per_day')
            ->where('customer_username', '=', Auth::guard('c_user')->user()->user_name)
            ->when($date_between, function ($query, $date_between) {
                return $query->whereBetween('action_date', $date_between);
            })
            ->orderby('action_date', 'DESC')
            ->get();

        $sQuery = Datatables::of($db_report_bonus_per_day);
        return $sQuery
            ->addColumn('action_date', function ($row) {
                return date('Y/m/d', strtotime($row->action_date));
            })
            ->addColumn('pv', function ($row) {
                if ($row->pv) {
                    return number_format($row->pv);
                } else {
                    return '-';
                }

            })
            ->addColumn('pv_pay_add', function ($row) {
                if ($row->pv_pay_add) {
                    return number_format($row->pv_pay_add);
                } else {
                    return '-';
                }
            })
            ->addColumn('pv_pay_active', function ($row) {
                if ($row->pv_pay_active) {
                    return number_format($row->pv_pay_active);
                } else {
                    return '-';
                }
            })
            ->addColumn('new_pv_a', function ($row) {
                if ($row->new_pv_a) {
                    return number_format($row->new_pv_a);
                } else {
                    return '-';
                }
            })
            ->addColumn('new_pv_b', function ($row) {
                if ($row->new_pv_b) {
                    return number_format($row->new_pv_b);
                } else {
                    return '-';
                }
            })
            ->addColumn('new_pv_c', function ($row) {
                if ($row->new_pv_c) {
                    return number_format($row->new_pv_c);
                } else {
                    return '-';
                }
            })
            ->addColumn('old_pv_a', function ($row) {
                if ($row->old_pv_a) {
                    return number_format($row->old_pv_a);
                } else {
                    return '-';
                }
            })
            ->addColumn('old_pv_b', function ($row) {
                if ($row->old_pv_b) {
                    return number_format($row->old_pv_b);
                } else {
                    return '-';
                }
            })
            ->addColumn('old_pv_c', function ($row) {
                if ($row->old_pv_c) {
                    return number_format($row->old_pv_c);
                } else {
                    return '-';
                }
            })
            ->addColumn('faststart', function ($row) {
                if ($row->faststart and $row->faststart > 0 ) {
                    // $row->action_date
                    $url = route('commission_faststart', ['user_name' => $row->customer_username, 'date' => $row->action_date]);
                    return "<a href='" . $url . "' class='text-primary'>" . number_format($row->faststart) . "</a>";
                } else {
                    return '-';
                };
            })
            ->addColumn('matching', function ($row) {
                if ($row->matching and $row->matching > 0) {
                    $url = route('commission_matching', ['user_name' => $row->customer_username, 'date' => $row->action_date]);
                    return "<a href='" . $url . "' class='text-primary'>" . number_format($row->matching) . "</a>";

                } else {
                    return '-';
                }
            })

            ->addColumn('outtime', function ($row) {
                if ($row->outtime) {
                    return date('Y/m/d', strtotime($row->outtime));
                } else {
                    return '-';
                }
            })
            ->addColumn('tmb', function ($row) {
                if ($row->tmb) {
                    return number_format($row->tmb, 2);
                } else {
                    return '-';
                }
            })

            ->addColumn('tm', function ($row) {
              if ($row->team_maker) {
                  return number_format($row->team_maker, 2);
              } else {
                  return '-';
              }
          })
            ->addColumn('bonus_total', function ($row) {
                if ($row->bonus_total) {
                    return number_format($row->bonus_total, 2);
                } else {
                    return '-';
                }
            })
            ->rawColumns(['faststart', 'matching'])
            // {data: 'faststart'},
            // {data: 'pro'},
            // {data: 'tmb'},
            // {data: 'booster'},
            // {data: 'matching'},
            // {data: 'pool'},
            // {data: 'outtime'},
            // {data: 'bonus_total'},
            // //->rawColumns(['pv'])
            ->make(true);
    }

    public function commission_faststart($customer_username, $date)
    {
        if ($customer_username == '' || $date == '') {
            return redirect('commission_per_day')->withError('Data Is Null');
        } else {
            $date = date('Y-m-d', strtotime($date));
            $data = ['customer_username' => $customer_username, 'date' => $date];
        }

        return view('frontend/commission_faststart', compact('data'));
    }

    public function dt_commission_faststart(Request $rs)
    {

        $db_commission_faststart = DB::table('db_report_bonus_faststart')
            ->where('customer_username', '=', $rs->customer_username)
            ->wheredate('action_date', '=', $rs->date)
            ->orderby('action_date', 'DESC')
            ->get();

        //$customer_data = Frontend::get_customer('8');
        //dd($db_commission_faststart);
        $sQuery = Datatables::of($db_commission_faststart);
        return $sQuery
            ->addColumn('username', function ($row) {
                return $row->customer_pay_username;
            })
            ->addColumn('name', function ($row) {
                if ($row->customer_pay_username) {
                    $customer_data = Frontend::get_customer($row->customer_pay_username);
                    return $customer_data->prefix_name . ' ' . $customer_data->first_name . ' ' . $customer_data->last_name;
                } else {
                    return '-';
                }

            })
            ->addColumn('new_pv', function ($row) {
                if ($row->new_pv) {
                    return number_format($row->new_pv);
                } else {
                    return '-';
                }
            })
            ->addColumn('benefit', function ($row) {
                if ($row->benefit) {
                    return $row->benefit.'%';
                } else {
                    return '-';
                }
            })
            ->addColumn('faststart_bonus', function ($row) {
                if ($row->faststart_bonus) {
                    return number_format($row->faststart_bonus,2);
                } else {
                    return '0';
                }
            })
            ->addColumn('invtype', function ($row) {
                if ($row->invtype) {
                    return number_format($row->invtype);
                } else {
                    return '-';
                }
            })
            ->make(true);
    }

    public function commission_matching($user_name, $date)
    {
        if ($user_name == '' || $date == '') {
            return redirect('commission_per_day')->withError('Data Is Null');
        } else {
            $date = date('Y-m-d', strtotime($date));
            $data = ['user_name' => $user_name, 'date' => $date];
        }

        return view('frontend/commission_matching', compact('data'));
    }

    public function dt_commission_matching(Request $rs)
    {
        $db_commission_matching = DB::table('db_report_bonus_matching')
            ->where('customer_username', '=', $rs->user_name)
            ->wheredate('action_date', '=', $rs->date)
            ->orderby('deep')
            ->get();

        //$customer_data = Frontend::get_customer('8');
        $sQuery = Datatables::of($db_commission_matching);
        return $sQuery
            ->addColumn('username', function ($row) {

                return $row->customer_matching_username;

            })

            ->addColumn('name', function ($row) {

              $user = DB::table('customers')
              ->select('business_name','prefix_name','first_name','last_name')
              ->where('user_name', '=', $row->customer_matching_username)
              ->first();

              if( $user->business_name ||  $user->business_name  != '-'){
                return $user->business_name;
              }else{
                $name = $user->prefix_name.' '. $user->first_name.' '. $user->last_name;
                return $name;
              }

            })

            ->addColumn('deep', function ($row) {
                if ($row->deep) {
                    return  number_format($row->deep);
                } else {
                    return '-';
                }
            })
            ->addColumn('gen', function ($row) {
              if ($row->gen) {
                  return 'G-'.$row->gen;
              } else {
                  return '-';
              }
          })
            ->addColumn('benefit', function ($row) {
                if ($row->benefit) {
                    return $row->benefit.'%';
                } else {
                    return '-';
                }
            })

            ->addColumn('bns_strong_leg', function ($row) {
                if ($row->bns_strong_leg) {
                    return number_format($row->bns_strong_leg, 2);
                } else {
                    return '-';
                }
            })
            ->addColumn('reward_bonus', function ($row) {
                if ($row->reward_bonus) {
                    return number_format($row->reward_bonus, 2);
                } else {
                    return '0';
                }
            })
            ->make(true);
    }

    public function commission_bonus_transfer_aistockist()
    {
        return view('frontend/commission_bonus_transfer_aistockist');

    }
    public function dt_commission_bonus_transfer_aistockist(Request $rs)
    {
        if ($rs->startDate) {
            $s_date = date('Y-m-d', strtotime($rs->startDate));
        } else {
            $s_date = '';
        }
        if ($rs->endDate) {
            $e_date = date('Y-m-d', strtotime($rs->endDate));
        } else {
            $e_date = '';
        }
        $customer_username = Auth::guard('c_user')->user()->user_name;

        $sTable = DB::table('db_report_bonus_transfer_aistockist')
            ->select('db_report_bonus_transfer_aistockist.*', 'customers.user_name', 'customers.prefix_name', 'customers.first_name', 'customers.last_name')
            ->leftjoin('customers', 'db_report_bonus_transfer_aistockist.customer_username', '=', 'customers.user_name')
            ->where('db_report_bonus_transfer_aistockist.customer_username', '=', $customer_username)
            ->whereRaw(("case WHEN '{$rs->business_location}' = '' THEN 1 else customers.business_location_id = '{$rs->business_location}' END"))
            ->whereRaw(("case WHEN '{$rs->status_search}' = '' THEN 1 else db_report_bonus_transfer_aistockist.status_transfer = '{$rs->status_search}' END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_report_bonus_transfer_aistockist.bonus_transfer_date) = '{$s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_report_bonus_transfer_aistockist.bonus_transfer_date) >= '{$s_date}' and date(db_report_bonus_transfer_aistockist.bonus_transfer_date) <= '{$e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(db_report_bonus_transfer_aistockist.bonus_transfer_date) = '{$e_date}' else 1 END"))
            ->orderby('db_report_bonus_transfer_aistockist.bonus_transfer_date', 'DESC')
            ->get();

        $sQuery = DataTables::of($sTable);
        return $sQuery

            ->addColumn('id', function ($row) {
                return $row->user_name;
            })
            ->addColumn('cus_name', function ($row) {
                return $row->first_name . ' ' . $row->last_name;
            })
            ->addColumn('commission', function ($row) {
                return is_null($row->bonus_total) ? '-' : number_format($row->bonus_total, 2);
            })
            ->addColumn('tax_percent', function ($row) {
                return number_format($row->tax_percent).'%';
            })
            ->addColumn('tax', function ($row) {
                return is_null($row->tax) ? '-' : number_format($row->tax, 2);
            })
            ->addColumn('fee', function ($row) {
                return is_null($row->fee) ? '-' : number_format($row->fee, 2);
            })
            ->addColumn('destination_bank', function ($row) {
                return is_null($row->bank_name) ? '-' : $row->bank_name;
            })

            ->addColumn('transferee_bank_no', function ($row) {
                return is_null($row->bank_account) ? '-' : $row->bank_account;
            })
            ->addColumn('amount', function ($row) {
                return is_null($row->price_transfer_total) ? '-' : number_format($row->price_transfer_total, 2);
            })

            ->addColumn('transfer_result', function ($row) {

                if ($row->status_transfer == '0') {
                    $status = 'รออนุมัติ';
                } elseif ($row->status_transfer == '1') {
                    $status = 'โอนสำเร็จ';
                } elseif ($row->status_transfer == '2') {
                    $status = 'ยกเลิก';
                } elseif ($row->status_transfer == '3') {
                    $status = 'ไม่อนุมัติ';
                } else {
                    $status = '-';
                }
                return $status;
            })

            ->addColumn('note', function ($row) {
                return is_null($row->remark_transfer) ? '-' : $row->remark_transfer;
            })
            ->addColumn('action_date', function ($row) {
                return is_null($row->bonus_transfer_date) ? '-' : date('Y/m/d', strtotime($row->bonus_transfer_date));
            })
            ->addColumn('view', function ($row) {
                $date = strtotime($row->bonus_transfer_date);
                return '<button class="btn btn-sm btn-success btn-outline-success" onclick="modal_commission_transfer_aistockist(' . $date . ')"><i class="fa fa-search"></i></button>';
            })
            ->rawColumns(['view'])
            ->make(true);
    }

    public function modal_commission_bonus_transfer_aistockist(Request $rs)
    {

        $date = (date('Y-m-d', $rs->date));
        $customer_username = Auth::guard('c_user')->user()->user_name;

        $data_customer = DB::table('customers')
            ->where('customers.user_name', '=', $customer_username)
            ->first();

        $data = DB::table('ai_stockist')
            ->select('ai_stockist.*', 'customers.user_name', 'customers.prefix_name', 'customers.first_name', 'customers.last_name', 'dataset_orders_type.orders_type')
            ->leftjoin('customers', 'ai_stockist.to_customer_id', '=', 'customers.id')
            ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'ai_stockist.type_id')
            ->where('dataset_orders_type.lang_id', '=', $data_customer->business_location_id)
            ->where('ai_stockist.customer_id', '=', $data_customer->id)
            ->where('ai_stockist.status_transfer', '=', 1)
            ->wheredate('ai_stockist.bonus_transfer_date', '=', $date)
            ->get();


        $vat_tax = DB::table('dataset_vat')
            ->where('business_location_id_fk', '=', $data_customer->business_location_id)
            ->first();

        $total = DB::table('ai_stockist')
            ->select(db::raw('sum(pv) as pv_total'))
            ->where('ai_stockist.customer_id', '=', $data_customer->id)
            ->where('ai_stockist.status_transfer', '=', 1)
            ->wheredate('ai_stockist.bonus_transfer_date', '=', $date)
            ->first();

        $vat = $vat_tax->vat;
        $tax = $vat_tax->tax;
        $pv_total = $total->pv_total;
        $data_total = ['vat' => $vat, 'tax' => $tax, 'pv_total' => $pv_total];

        return view('frontend.modal.modal_commission_transfer_aistockist', compact('data', 'date', 'data_total'));

    }

    public function commission_bonus_transfer_af()
    {
        return view('frontend/commission_bonus_transfer_af');

    }

    public function dt_commission_bonus_transfer_af(Request $rs)
    {
        if ($rs->startDate) {
            $s_date = date('Y-m-d', strtotime($rs->startDate));
        } else {
            $s_date = '';
        }
        if ($rs->endDate) {
            $e_date = date('Y-m-d', strtotime($rs->endDate));
        } else {
            $e_date = '';
        }
        $customer_username = Auth::guard('c_user')->user()->user_name;

        $sTable = DB::table('db_report_bonus_transfer_af')
            ->select('db_report_bonus_transfer_af.*', 'customers.user_name', 'customers.prefix_name', 'customers.first_name', 'customers.last_name')
            ->leftjoin('customers', 'db_report_bonus_transfer_af.customer_username', '=', 'customers.user_name')
            ->where('db_report_bonus_transfer_af.customer_username', '=', $customer_username)
            ->whereRaw(("case WHEN '{$rs->business_location}' = '' THEN 1 else customers.business_location_id = '{$rs->business_location}' END"))
            ->whereRaw(("case WHEN '{$rs->status_search}' = '' THEN 1 else db_report_bonus_transfer_af.status_transfer = '{$rs->status_search}' END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_report_bonus_transfer_af.bonus_transfer_date) = '{$s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_report_bonus_transfer_af.bonus_transfer_date) >= '{$s_date}' and date(db_report_bonus_transfer_af.bonus_transfer_date) <= '{$e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(db_report_bonus_transfer_af.bonus_transfer_date) = '{$e_date}' else 1 END"))
            ->orderby('db_report_bonus_transfer_af.bonus_transfer_date', 'DESC')
            ->get();

        $sQuery = DataTables::of($sTable);
        return $sQuery

            ->addColumn('id', function ($row) {
                return $row->user_name;
            })
            ->addColumn('cus_name', function ($row) {
                return $row->first_name . ' ' . $row->last_name;
            })
            ->addColumn('commission', function ($row) {
                return is_null($row->bonus_total) ? '-' : number_format($row->bonus_total, 2);
            })
            ->addColumn('tax_percent', function ($row) {
                return number_format($row->tax_percent).'%';
            })
            ->addColumn('tax', function ($row) {
                return is_null($row->tax) ? '-' : number_format($row->tax, 2);
            })
            ->addColumn('fee', function ($row) {
                return is_null($row->fee) ? '-' : number_format($row->fee, 2);
            })
            ->addColumn('destination_bank', function ($row) {
                return is_null($row->bank_name) ? '-' : $row->bank_name;
            })
            ->addColumn('transferee_bank_no', function ($row) {
                return is_null($row->bank_account) ? '-' : $row->bank_account;
            })
            ->addColumn('amount', function ($row) {
                return is_null($row->price_transfer_total) ? '-' : number_format($row->price_transfer_total, 2);
            })

            ->addColumn('transfer_result', function ($row) {

                if ($row->status_transfer == '0') {
                    $status = 'รออนุมัติ';
                } elseif ($row->status_transfer == '1') {
                    $status = 'โอนสำเร็จ';
                } elseif ($row->status_transfer == '2') {
                    $status = 'ยกเลิก';
                } elseif ($row->status_transfer == '3') {
                    $status = 'ไม่อนุมัติ';
                } else {
                    $status = '-';
                }
                return $status;
            })

            ->addColumn('note', function ($row) {
                return is_null($row->remark_transfer) ? '-' : $row->remark_transfer;
            })
            ->addColumn('action_date', function ($row) {
                return is_null($row->bonus_transfer_date) ? '-' : date('Y/m/d', strtotime($row->bonus_transfer_date));
            })

            ->make(true);
    }

    public function commission_bonus_transfer_member()
    {
        return view('frontend/commission_bonus_transfer_member');

    }

    public function dt_commission_bonus_transfer_member(Request $rs)
    {
        if ($rs->startDate) {
            $s_date = date('Y-m-d', strtotime($rs->startDate));
        } else {
            $s_date = '';
        }
        if ($rs->endDate) {
            $e_date = date('Y-m-d', strtotime($rs->endDate));
        } else {
            $e_date = '';
        }
        $customer_username = Auth::guard('c_user')->user()->user_name;

        $sTable = DB::table('db_report_bonus_transfer_corp')
            ->select('db_report_bonus_transfer_corp.*', 'customers.user_name', 'customers.prefix_name', 'customers.first_name', 'customers.last_name')
            ->leftjoin('customers', 'db_report_bonus_transfer_corp.customer_username', '=', 'customers.user_name')
            ->where('db_report_bonus_transfer_corp.customer_username', '=', $customer_username)
            ->whereRaw(("case WHEN '{$rs->business_location}' = '' THEN 1 else customers.business_location_id = '{$rs->business_location}' END"))
            ->whereRaw(("case WHEN '{$rs->status_search}' = '' THEN 1 else db_report_bonus_transfer_corp.status_transfer = '{$rs->status_search}' END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_report_bonus_transfer_corp.bonus_transfer_date) = '{$s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_report_bonus_transfer_corp.bonus_transfer_date) >= '{$s_date}' and date(db_report_bonus_transfer_corp.bonus_transfer_date) <= '{$e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(db_report_bonus_transfer_corp.bonus_transfer_date) = '{$e_date}' else 1 END"))
            ->orderby('db_report_bonus_transfer_corp.bonus_transfer_date', 'DESC')
            ->get();

        $sQuery = DataTables::of($sTable);
        return $sQuery

            ->addColumn('id', function ($row) {
                return $row->user_name;
            })
            ->addColumn('cus_name', function ($row) {
                return $row->first_name . ' ' . $row->last_name;
            })
            ->addColumn('commission', function ($row) {
                return is_null($row->bonus_total) ? '-' : number_format($row->bonus_total, 2);
            })
            ->addColumn('tax_percent', function ($row) {
                return number_format($row->tax_percent).'%';
            })
            ->addColumn('tax', function ($row) {
                return is_null($row->tax) ? '-' : number_format($row->tax, 2);
            })
            ->addColumn('fee', function ($row) {
                return is_null($row->fee) ? '-' : number_format($row->fee, 2);
            })
            ->addColumn('destination_bank', function ($row) {
                return is_null($row->bank_name) ? '-' : $row->bank_name;
            })

            ->addColumn('transferee_bank_no', function ($row) {
                return is_null($row->bank_account) ? '-' : $row->bank_account;
            })
            ->addColumn('amount', function ($row) {
                return is_null($row->price_transfer_total) ? '-' : number_format($row->price_transfer_total, 2);
            })

            ->addColumn('transfer_result', function ($row) {

                if ($row->status_transfer == '0') {
                    $status = 'รออนุมัติ';
                } elseif ($row->status_transfer == '1') {
                    $status = 'โอนสำเร็จ';
                } elseif ($row->status_transfer == '2') {
                    $status = 'ยกเลิก';
                } elseif ($row->status_transfer == '3') {
                    $status = 'ไม่อนุมัติ';
                } else {
                    $status = '-';
                }
                return $status;
            })

            ->addColumn('note', function ($row) {
                return is_null($row->remark_transfer) ? '-' : $row->remark_transfer;
            })
            ->addColumn('action_date', function ($row) {
                return is_null($row->bonus_transfer_date) ? '-' : date('Y/m/d', strtotime($row->bonus_transfer_date));
            })

            ->make(true);
    }

}
