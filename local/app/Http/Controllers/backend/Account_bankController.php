<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Account_bankController extends Controller
{

    public function index(Request $request)
    {

       return View('backend.account_bank.index');

    }

   public function create()
    {
      $sBusiness_location = \App\Models\Backend\Business_location::get();
       return View('backend.account_bank.form')->with(array('sBusiness_location'=>$sBusiness_location) );

    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Account_bank::find($id);
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       return View('backend.account_bank.form')->with(array('sRow'=>$sRow, 'id'=>$id,'sBusiness_location'=>$sBusiness_location) );
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
            $sRow = \App\Models\Backend\Account_bank::find($id);
          }else{
            $sRow = new \App\Models\Backend\Account_bank;
          }

          $sRow->business_location_id_fk    = request('business_location_id_fk');
          $sRow->txt_account_name    = request('txt_account_name');
          $sRow->txt_bank_name    = request('txt_bank_name');
          $sRow->txt_bank_number    = request('txt_bank_number');
          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

          return redirect()->action('backend\Account_bankController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Account_bankController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }
    public function show($id)
    {
      $sRow = \App\Models\Backend\Account_bank::find($id);
      if( $sRow ){
        $sRow->deleted_at = date('Y-m-d H:i:s');
        $sRow->save();
        // $sRow->forceDelete();
      }
       // return response()->json(\App\Models\Alert::Msg('success'));
       return back()->with(['success' => 'ทำการลบข้อมูลเรียบร้อย']);
    }
    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Account_bank::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Account_bank::search()->orderBy('id', 'asc');
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
