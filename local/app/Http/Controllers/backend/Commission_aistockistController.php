<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class Commission_aistockistController extends Controller
{

    public function index(Request $request)
    {
        //$sBranchs = \App\Models\Backend\Branchs::get();
        $data = DB::table('dataset_business_location')
            ->get();
        return view('backend.commission_aistockist.index')->with(array('business_location' => $data));
    }

    public function create()
    {
        $sRowGroup = \App\Models\Backend\Commission_aistockist::orderBy('group_id', 'desc')->limit(1)->get();
        $groupMaxID = $sRowGroup[0]->group_id + 1;
        // dd($groupMaxID);
        $sLanguage = \App\Models\Backend\Language::get();

        $sBranchs = \App\Models\Backend\Branchs::get();

        $Warehouse = \App\Models\Backend\Warehouse::get();

        return View('backend.commission_aistockist.form')->with(array('sLanguage' => $sLanguage, 'groupMaxID' => $groupMaxID));
    }

    public function store(Request $request)
    {
        return $this->form();
    }

    public function edit($id)
    {
        $sRowGroup = \App\Models\Backend\Commission_aistockist::find($id);
        $sRow = \App\Models\Backend\Commission_aistockist::where('group_id', $sRowGroup->group_id)->get();
        // dd($sRow[0]->status);
        $sLanguage = \App\Models\Backend\Language::get();
        return View('backend.commission_aistockist.form')->with(array('sRow' => $sRow, 'id' => $id, 'sLanguage' => $sLanguage));
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
                $sRow = \App\Models\Backend\Commission_aistockist::find($id);
                $langCnt = count(request('lang'));
                for ($i = 0; $i < $langCnt; $i++) {

                    \App\Models\Backend\Commission_aistockist::where('id', request('id')[$i])->update(
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

                $sRowGroup = \App\Models\Backend\Commission_aistockist::orderBy('group_id', 'desc')->limit(1)->get();
                $groupMaxID = $sRowGroup[0]->group_id + 1;

                $langCnt = count(request('lang'));
                for ($i = 0; $i < $langCnt; $i++) {

                    \App\Models\Backend\Commission_aistockist::insert(
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

            return redirect()->action('backend\Commission_aistockistController@index')->with(['alert' => \App\Models\Alert::Msg('success')]);

        } catch (\Exception $e) {
            echo $e->getMessage();
            \DB::rollback();
            return redirect()->action('backend\Commission_aistockistController@index')->with(['alert' => \App\Models\Alert::e($e)]);
        }
    }

    public function Datatable(Request $rs)
    {
      if ($rs->startDate) {
        $date = str_replace('/','-',$rs->startDate);
        $s_date = date('Y-m-d', strtotime($date));

      } else {
          $s_date = '';
      }

      if ($rs->endDate) {
          $date = str_replace('/','-',$rs->endDate);
          $e_date = date('Y-m-d', strtotime($date));
      } else {
          $e_date = '';
      }

        $sTable = DB::table('db_report_bonus_transfer_aistockist')
            ->select('db_report_bonus_transfer_aistockist.*', 'customers.user_name', 'customers.prefix_name', 'customers.first_name', 'customers.last_name')
            ->leftjoin('customers', 'db_report_bonus_transfer_aistockist.customer_id_fk', '=', 'customers.id')
            ->whereRaw(("case WHEN '{$rs->business_location}' = '' THEN 1 else customers.business_location_id = '{$rs->business_location}' END"))
            ->whereRaw(("case WHEN '{$rs->status_search}' = '' THEN 1 else db_report_bonus_transfer_aistockist.status_transfer = '{$rs->status_search}' END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_report_bonus_transfer_aistockist.bonus_transfer_date) = '{$s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_report_bonus_transfer_aistockist.bonus_transfer_date) >= '{$s_date}' and date(db_report_bonus_transfer_aistockist.bonus_transfer_date) <= '{$e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(db_report_bonus_transfer_aistockist.bonus_transfer_date) = '{$e_date}' else 1 END"))
            ->orderby('db_report_bonus_transfer_aistockist.bonus_transfer_date', 'DESC')
            ->get();

        $sQuery = \DataTables::of($sTable);
        return $sQuery

            ->addColumn('id', function ($row) {
                return $row->user_name;
            })
            ->addColumn('cus_name', function ($row) {
                return $row->first_name . ' ' . $row->last_name;
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
                // value="0" รออนุมัติ
                // value="1" อนุมัติ
                // value="3" ไม่อนุมัติ
                // value="2" ยกเลิก
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
                return is_null($row->bonus_transfer_date) ? '-' : date('d/m/Y', strtotime($row->bonus_transfer_date));
            })
            ->addColumn('view', function ($row) {
                $date = strtotime($row->bonus_transfer_date);
                $customer_id = $row->customer_id_fk;
                return '<button type="button" class="btn btn-success btn-sm waves-effect btnSearchInList " style="font-size: 14px !important;"  onclick="modal_commission_transfer(' . $customer_id . ',' . $date . ')">
              <i class="bx bx-search font-size-16 align-middle"></i>';
            })
            ->rawColumns(['view'])
            ->make(true);
    }

    public function modal_commission_transfer_aistockist(Request $rs)
    {
        $date = (date('Y-m-d', $rs->date));

        $customer_id = $rs->customer_id;

        $data_customer = DB::table('customers')
        ->where('customers.id', '=', $customer_id)
        ->first();

        $data = DB::table('ai_stockist')
            ->select('ai_stockist.*','customers.user_name', 'customers.prefix_name', 'customers.first_name', 'customers.last_name','dataset_orders_type.orders_type')
            ->leftjoin('customers', 'ai_stockist.to_customer_id', '=', 'customers.id')
            ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'ai_stockist.type_id')
            ->where('dataset_orders_type.lang_id', '=', $data_customer->business_location_id)
            ->where('ai_stockist.customer_id', '=', $customer_id)
            ->where('ai_stockist.status_transfer', '=', 1)
            ->wheredate('ai_stockist.bonus_transfer_date', '=', $date)
            ->get();

        $vat_tax = DB::table('dataset_vat')
            ->where('business_location_id_fk', '=', $data_customer->business_location_id)
            ->first();

        $total =  DB::table('ai_stockist')
        ->select(db::raw('sum(pv) as pv_total'))
        ->where('ai_stockist.customer_id', '=', $customer_id)
        ->where('ai_stockist.status_transfer', '=', 1)
        ->wheredate('ai_stockist.bonus_transfer_date', '=', $date)
        ->first();

        $vat = $vat_tax->vat;
        $tax = $vat_tax->tax;
        $pv_total = $total->pv_total;
        $data_total = ['vat'=>$vat,'tax'=>$tax,'pv_total'=>$pv_total];

        return view('backend.commission_aistockist.modal_commission_transfer_aistockist', compact('data','date','data_total'));
    }

}
