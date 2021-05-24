<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class BranchsController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.branchs.index');

    }

    public function create()
    {
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $Province = DB::select(" select * from dataset_provinces ");
      $sWarehouse = \App\Models\Backend\Warehouse::get();
      return View('backend.branchs.form')->with(array('sBusiness_location'=>$sBusiness_location,'Province'=>$Province,'sWarehouse'=>$sWarehouse,) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Branchs::find($id);
       // dd($sRow->id);
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sMaker_name = DB::select(" select * from ck_users_admin where id=".$sRow->b_maker." ");
       $Province = DB::select(" select * from dataset_provinces ");
       $sWarehouse = \App\Models\Backend\Warehouse::where('branch_id_fk',$sRow->id)->get();
       return View('backend.branchs.form')->with(array('sRow'=>$sRow, 'id'=>$id, 'sMaker_name'=>$sMaker_name,'sBusiness_location'=>$sBusiness_location,'Province'=>$Province,'sWarehouse'=>$sWarehouse,) );
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
            $sRow = \App\Models\Backend\Branchs::find($id);
            $sRow->updated_at = date('Y-m-d H:i:s');
            $sRow->warehouse_id_fk    = request('warehouse_id_fk');
            $sRow->warehouse_pack_id_fk    = request('warehouse_pack_id_fk');
          }else{
            $sRow = new \App\Models\Backend\Branchs;
          }

          $sRow->business_location_id_fk    = request('business_location_id_fk');
          $sRow->b_code    = request('b_code');
          $sRow->b_name    = request('b_name');
          $sRow->province_id_fk    = request('province_id_fk');
          $sRow->b_details    = request('b_details');
          $sRow->b_maker    = request('b_maker');

          $sRow->status    = request('status')?request('status'):0;
          $sRow->save();

          \DB::commit();

          return redirect()->to(url("backend/branchs/".($id?$id:$sRow->id)."/edit"));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\BranchsController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Branchs::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Branchs::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('b_maker', function($row) {
        if(@$row->b_maker!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->b_maker." ");
           return @$sD[0]->name;
        }else{
           return '';
        }
      })  
       ->addColumn('business_location', function($row) {
        if(@$row->business_location_id_fk!=''){
             $P = DB::select(" select * from dataset_business_location where id=".@$row->business_location_id_fk." ");
             return @$P[0]->txt_desc;
        }else{
             return '-';
        }
      })   
      ->addColumn('created_at', function($row) {
        if(!is_null($row->created_at)){
            $d = strtotime($row->created_at); return date("d/m/", $d).(date("Y", $d)+543).' '.date("H:i:s", $d);
        }else{
            return '';
        }
      })
      ->addColumn('updated_at', function($row) {
        if(!is_null($row->updated_at)){
            $d = strtotime($row->updated_at); return date("d/m/", $d).(date("Y", $d)+543).' '.date("H:i:s", $d);
        }else{
            return '';
        }
      })
      ->make(true);
    }



}
