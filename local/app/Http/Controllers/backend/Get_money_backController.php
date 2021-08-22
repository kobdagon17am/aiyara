<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Get_money_backController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.get_money_back.index');

    }

    public function create()
    {
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $sGet_money_back_type = \App\Models\Backend\Get_money_back_type::get();
      $sCurrency = \App\Models\Backend\Currency::get();
      return View('backend.get_money_back.form')->with(array(
        'sBusiness_location'=>$sBusiness_location,
        'sBranchs'=>$sBranchs,
        'sGet_money_back_type'=>$sGet_money_back_type,
        'sCurrency'=>$sCurrency,
        ));
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
      $sRow = \App\Models\Backend\Get_money_back::find($id);
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $sGet_money_back_type = \App\Models\Backend\Get_money_back_type::get();
      $sCurrency = \App\Models\Backend\Currency::get();
       return View('backend.get_money_back.form')->with(array('sRow'=>$sRow , 'id'=>$id,
        'sBusiness_location'=>$sBusiness_location,
        'sBranchs'=>$sBranchs,
        'sGet_money_back_type'=>$sGet_money_back_type,
        'sCurrency'=>$sCurrency,
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
            $sRow = \App\Models\Backend\Get_money_back::find($id);
          }else{
            $sRow = new \App\Models\Backend\Get_money_back;
          }

          $sRow->business_location_id_fk    = request('business_location_id_fk');
          $sRow->branch_id_fk    = request('branch_id_fk');
          $sRow->get_back_type_id_fk    = request('get_back_type_id_fk');
          $sRow->amt    = request('amt');
          $sRow->currency_type_id_fk    = request('currency_type_id_fk');
          $sRow->note    = request('note');

          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         return redirect()->action('backend\Get_money_backController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Get_money_backController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Get_money_back::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Get_money_back::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('business_location', function($row) {
          $sP = \App\Models\Backend\Business_location::find($row->business_location_id_fk);
          return $sP->txt_desc;
      })
      ->addColumn('branch', function($row) {
          $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
          return @$sBranchs[0]->b_name;
      })
      ->addColumn('get_back_type', function($row) {
          $sP = \App\Models\Backend\Get_money_back_type::find($row->get_back_type_id_fk);
          return $sP->txt_desc;
      })
      ->addColumn('currency_type', function($row) {
          $D = DB::select(" select * from dataset_currency where id=".$row->currency_type_id_fk." ");
          return @$D[0]->txt_desc;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
