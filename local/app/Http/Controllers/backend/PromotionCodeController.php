<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class PromotionCodeController extends Controller
{

    public function index(Request $request)
    {
        return view("backend.promotion_cus.index");
    }

    public function create(Request $request)
    {

    }

    public function store(Request $request)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
    }

   public function form($id=NULL)
    {

    }

    public function destroy($id)
    {
    }

    public function Datatable(Request $req){
      $sTable = \App\Models\Backend\PromotionCode::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('promotion_name', function($row) {
        if($row->promotion_id_fk){
          $d = \App\Models\Backend\Promotions::find($row->promotion_id_fk);
          return $d->name_thai;
        }
      })
      ->addColumn('pro_sdate', function($row) {
        $d = strtotime($row->pro_sdate); return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('pro_edate', function($row) {
        $d = strtotime($row->pro_edate); return date("d/m/", $d).(date("Y", $d)+543);
      })      
      ->make(true);
    }



}
