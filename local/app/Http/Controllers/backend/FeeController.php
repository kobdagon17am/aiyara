<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class FeeController extends Controller
{

    public function index(Request $request)
    {

       return View('backend.fee.index');

    }

   public function create()
    {
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       return View('backend.fee.form')->with(array('sBusiness_location'=>$sBusiness_location) );

    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Fee::find($id);
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       return View('backend.fee.form')->with(array('sRow'=>$sRow, 'id'=>$id,'sBusiness_location'=>$sBusiness_location) );
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
            $sRow = \App\Models\Backend\Fee::find($id);
          }else{
            $sRow = new \App\Models\Backend\Fee;
          }
           
          $sRow->business_location_id_fk    = request('business_location_id_fk');
          $sRow->txt_desc    = request('txt_desc');
          $sRow->txt_value    = request('txt_value');
          $sRow->txt_fixed_rate    = request('txt_fixed_rate');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

          return redirect()->action('backend\FeeController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\FeeController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Fee::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Fee::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('business_location', function($row) {
        if(@$row->business_location_id_fk!=''){
             $P = DB::select(" select * from dataset_business_location where id=".@$row->business_location_id_fk." ");
             return @$P[0]->txt_desc;
        }else{
             return '-';
        }
      }) 
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
