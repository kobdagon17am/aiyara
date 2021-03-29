<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class Commission_transferController extends Controller
{

    public function index(Request $request)
    {
        //$sBranchs = \App\Models\Backend\Branchs::get();

        $data = DB::table('dataset_business_location')
                ->get();

        return view('backend.commission_transfer.index')->with(array('business_location' =>$data));
    }

    public function create()
    {
        $sRowGroup = \App\Models\Backend\Commission_transfer::orderBy('group_id', 'desc')->limit(1)->get();
        $groupMaxID = $sRowGroup[0]->group_id + 1;
        // dd($groupMaxID);
        $sLanguage = \App\Models\Backend\Language::get();

        $sBranchs = \App\Models\Backend\Branchs::get();

        $Warehouse = \App\Models\Backend\Warehouse::get();

        return View('backend.commission_transfer.form')->with(array('sLanguage' => $sLanguage, 'groupMaxID' => $groupMaxID));
    }

    public function store(Request $request)
    {
        return $this->form();
    }

    public function edit($id)
    {
        $sRowGroup = \App\Models\Backend\Commission_transfer::find($id);
        $sRow = \App\Models\Backend\Commission_transfer::where('group_id', $sRowGroup->group_id)->get();
        // dd($sRow[0]->status);
        $sLanguage = \App\Models\Backend\Language::get();
        return View('backend.commission_transfer.form')->with(array('sRow' => $sRow, 'id' => $id, 'sLanguage' => $sLanguage));
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
                $sRow = \App\Models\Backend\Commission_transfer::find($id);
                $langCnt = count(request('lang'));
                for ($i = 0; $i < $langCnt; $i++) {

                    \App\Models\Backend\Commission_transfer::where('id', request('id')[$i])->update(
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

                $sRowGroup = \App\Models\Backend\Commission_transfer::orderBy('group_id', 'desc')->limit(1)->get();
                $groupMaxID = $sRowGroup[0]->group_id + 1;

                $langCnt = count(request('lang'));
                for ($i = 0; $i < $langCnt; $i++) {

                    \App\Models\Backend\Commission_transfer::insert(
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

            return redirect()->action('backend\Commission_transferController@index')->with(['alert' => \App\Models\Alert::Msg('success')]);

        } catch (\Exception $e) {
            echo $e->getMessage();
            \DB::rollback();
            return redirect()->action('backend\Commission_transferController@index')->with(['alert' => \App\Models\Alert::e($e)]);
        }
    }

    public function destroy($id)
    {
        $sRow = \App\Models\Backend\Commission_transfer::find($id);
        if ($sRow) {
            $sRow->forceDelete();
        }
        return response()->json(\App\Models\Alert::Msg('success'));
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

        //$sTable = \App\Models\Backend\Commission_transfer::search()->orderBy('id', 'asc');
        $sTable = DB::table('db_report_bonus_transfer')
            ->select('db_report_bonus_transfer.*', 'customers.user_name', 'customers.prefix_name', 'customers.first_name', 'customers.last_name')
            ->leftjoin('customers', 'db_report_bonus_transfer.customer_id_fk', '=', 'customers.id')
            ->whereRaw(("case WHEN '{$rs->business_location}' = '' THEN 1 else customers.business_location_id = '{$rs->business_location}' END"))
            ->whereRaw(("case WHEN '{$rs->status_search}' = '' THEN 1 else db_report_bonus_transfer.status_transfer = '{$rs->status_search}' END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_report_bonus_transfer.bonus_transfer_date) = '{$s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_report_bonus_transfer.bonus_transfer_date) >= '{$s_date}' and date(db_report_bonus_transfer.bonus_transfer_date) <= '{$e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(db_report_bonus_transfer.bonus_transfer_date) = '{$e_date}' else 1 END"))
            ->orderby('bonus_transfer_date', 'DESC')
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
              $date=  strtotime($row->bonus_transfer_date);
              $customer_id = $row->customer_id_fk;
             return '<button type="button" class="btn btn-success btn-sm waves-effect btnSearchInList " style="font-size: 14px !important;"  onclick="modal_commission_transfer('.$customer_id.','.$date.')">
              <i class="bx bx-search font-size-16 align-middle"></i>';
            })
            ->rawColumns(['view'])
            ->make(true);
    }

    public function modal_commission_transfer(Request $rs){


      $date = (date('Y-m-d',$rs->date));
      $customer_id = $rs->customer_id;
      $data = DB::table('db_report_bonus_per_day')
           ->where('customer_id_fk', '=', $customer_id)
           ->where('status_payment', '=',1)
           ->wheredate('transfer_date','=',$date)
           ->orderby('action_date','DESC')
           ->get();


       $total = DB::table('db_report_bonus_per_day')
       ->select(db::raw('sum(faststart) as faststart_total ,sum(tmb) as tmb_total,sum(booster) as booster_total
       ,sum(reward) as reward_total,sum(team_maker) as team_maker_total,sum(pro) as pro_total,sum(bonus_total) as sum_bonus_total'))
       ->where('customer_id_fk', '=',$customer_id)
       ->where('status_payment', '=',1)
       ->wheredate('transfer_date','=',$date)
       ->orderby('action_date','DESC')
       ->first();

     return view('backend.commission_transfer.modal_commission_transfer',compact('data','date','total'));
   }

}
