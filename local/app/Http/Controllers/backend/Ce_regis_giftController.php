<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Ce_regis_giftController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.ce_regis_gift.index');

    }

    public function create()
    {
      return View('backend.ce_regis_gift.form');
    }

    public function store(Request $request)
    {
      // dd($request->all());
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Ce_regis_gift::find($id);
       return View('backend.ce_regis_gift.form')->with(array('sRow'=>$sRow , 'id'=>$id,
        ));
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      return $this->form($id);
    }


    public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow = \App\Models\Backend\Ce_regis_gift::find($id);
          }else{
            $sRow = new \App\Models\Backend\Ce_regis_gift;
          }

          $sRow->txt_desc    = request('txt_desc');
          $sRow->status    = request('status')?request('status'):0;

          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         return redirect()->action('backend\Ce_regis_giftController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Ce_regis_giftController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Ce_regis_gift::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Ce_regis_gift::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // ->addColumn('business_location', function($row) {
      //     $sP = \App\Models\Backend\Business_location::find($row->business_location_id_fk);
      //     return $sP->txt_desc;
      // })
      // ->addColumn('branch', function($row) {
      //     $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
      //     return @$sBranchs[0]->b_name;
      // })
      // ->addColumn('get_back_type', function($row) {
      //     $sP = \App\Models\Backend\Ce_regis_gift_type::find($row->get_back_type_id_fk);
      //     return $sP->txt_desc;
      // })
      // ->addColumn('currency_type', function($row) {
      //     $D = DB::select(" select * from dataset_currency where id=".$row->currency_type_id_fk." ");
      //     return @$D[0]->txt_desc;
      // })
      // ->addColumn('updated_at', function($row) {
      //   return is_null($row->updated_at) ? '-' : $row->updated_at;
      // })
      ->make(true);
    }



}
