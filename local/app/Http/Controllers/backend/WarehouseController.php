<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class WarehouseController extends Controller
{

    public function index(Request $request)
    {
    }

    public function create($id)
    {
      $sBranchs = \App\Models\Backend\Branchs::find($id);
      return View('backend.warehouse.form')->with(array('sBranchs'=>$sBranchs) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Warehouse::find($id);
       $sBranchs = \App\Models\Backend\Branchs::find($sRow->branch_id_fk);
       $sMaker_name = DB::select(" select * from ck_users_admin where id=".$sRow->w_maker." ");
       return View('backend.warehouse.form')->with(array('sRow'=>$sRow, 'id'=>$id,'sBranchs'=>$sBranchs, 'sMaker_name'=>$sMaker_name) );
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
            $sRow = \App\Models\Backend\Warehouse::find($id);
            $sRow->updated_at = date('Y-m-d H:i:s');
          }else{
            $sRow = new \App\Models\Backend\Warehouse;
          }

          $sRow->branch_id_fk    = request('branch_id_fk');
          $sRow->w_code    = request('w_code');
          $sRow->w_name    = request('w_name');
          $sRow->w_details    = request('w_details');
          $sRow->w_maker    = request('w_maker');

          $sRow->status    = request('status')?request('status'):0;
          $sRow->save();

          \DB::commit();
          
          // if( $id ){
            return redirect()->to(url("backend/warehouse/".($id?$id:$sRow->id)."/edit"));
          // }else{
          //   return redirect()->to(url("backend/branchs/".request('branch_id_fk')."/edit"));
          // }  

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\WarehouseController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Warehouse::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Warehouse::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('w_maker', function($row) {
        if(@$row->w_maker!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->w_maker." ");
           return @$sD[0]->name;
        }else{
           return '';
        }
      })  
      ->addColumn('created_at', function($row) {
        if(!is_null($row->created_at)){
            return $row->created_at;
        }else{
            return '';
        }
      })
      ->addColumn('updated_at', function($row) {
        if(!is_null($row->updated_at)){
            return $row->updated_at;
        }else{
            return '';
        }
      })
      ->make(true);
    }



}
