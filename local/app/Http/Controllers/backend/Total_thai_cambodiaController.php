<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class Total_thai_cambodiaController extends Controller
{

    public function index(Request $request)
    {
        $data = DB::table('dataset_business_location')
            ->get();

        return view('backend.total_thai_cambodia.index')->with(array('business_location' => $data));
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
            ->select('db_total_thai_cambodia.*', 'dataset_business_location.txt_desc', 'branchs.id', 'branchs.b_name', 'branchs.b_details')
            ->leftjoin('dataset_business_location', 'dataset_business_location.country_id_fk', '=', 'db_total_thai_cambodia.business_location_id_fk')
            ->leftjoin('branchs', 'branchs.id', '=', 'db_total_thai_cambodia.branchs_id_fk')
            ->whereRaw(("case WHEN '{$rs->business_location}' = '' THEN 1 else db_total_thai_cambodia.business_location_id_fk = '{$rs->business_location}' END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_total_thai_cambodia.action_date) = '{$s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_total_thai_cambodia.action_date) >= '{$s_date}' and date(db_total_thai_cambodia.action_date) <= '{$e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(db_total_thai_cambodia.action_date) = '{$e_date}' else 1 END"))
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

            ->addColumn('action_date', function ($row) {
                return is_null($row->action_date) ? '-' : date('Y/m/d', strtotime($row->action_date));
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
