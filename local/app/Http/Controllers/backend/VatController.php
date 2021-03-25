<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class VatController extends Controller
{

    public function index(Request $request)
    {
      return view('backend.vat.index');
    }

   public function create()
    {
      $sBusiness_location = \App\Models\Backend\Business_location::get();
       return View('backend.vat.form')->with(array('sBusiness_location'=>$sBusiness_location) );

    }
    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Vat::find($id);
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       return View('backend.vat.form')->with(array('sRow'=>$sRow, 'id'=>$id,'sBusiness_location'=>$sBusiness_location) );
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
            $sRow = \App\Models\Backend\Vat::find($id);
          }else{
            $sRow = new \App\Models\Backend\Vat;
          }

          $sRow->business_location_id_fk    = request('business_location_id_fk');
          $sRow->vat    = request('vat');
          $sRow->tax    = request('tax');
          $sRow->juristic_person    = request('juristic_person');

          $sRow->action_user    = \Auth::user()->id;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

          return redirect()->action('backend\VatController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\VatController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Vat::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Vat::search()->orderBy('id', 'asc');
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
       ->addColumn('action_user', function($row) {
        if(@$row->action_user!=''){
             $P = DB::select(" select * from ck_users_admin where id=".@$row->action_user." ");
             return @$P[0]->name;
        }else{
             return '-';
        }
      })        
      // ->addColumn('updated_at', function($row) {
      //   return is_null($row->updated_at) ? '-' : $row->updated_at;
      // })
      ->make(true);
    }



}
