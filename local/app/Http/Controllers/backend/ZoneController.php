<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class ZoneController extends Controller
{

    public function index(Request $request)
    {
    }

    public function create($id)
    {
      $sWarehouse = \App\Models\Backend\Warehouse::find($id);
      $sBranchs = \App\Models\Backend\Branchs::find($sWarehouse->branch_id_fk);
      return View('backend.zone.form')->with(array('sWarehouse'=>$sWarehouse,'sBranchs'=>$sBranchs) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       // dd($id);
       $sRow = \App\Models\Backend\Zone::find($id);
       // dd($sRow);
       $sWarehouse = \App\Models\Backend\Warehouse::find($sRow->warehouse_id_fk);
       $sBranchs = \App\Models\Backend\Branchs::find($sWarehouse->branch_id_fk);
       $sMaker_name = DB::select(" select * from ck_users_admin where id=".$sRow->z_maker." ");
       return view('backend.zone.form')->with(['sRow'=>$sRow, 'id'=>$id ,'sWarehouse'=>$sWarehouse,'sBranchs'=>$sBranchs , 'sMaker_name'=>$sMaker_name ]);
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
            $sRow = \App\Models\Backend\Zone::find($id);
            $sRow->updated_at = date('Y-m-d H:i:s');
          }else{
            $sRow = new \App\Models\Backend\Zone;
          }

          $sRow->warehouse_id_fk    = request('warehouse_id_fk');
          $sRow->z_code    = request('z_code');
          $sRow->z_name    = request('z_name');
          $sRow->z_details    = request('z_details');
          $sRow->z_maker    = request('z_maker');

          $sRow->status    = request('status')?request('status'):0;
          $sRow->save();

          \DB::commit();
          
          // if( $id ){
            return redirect()->to(url("backend/zone/".($id?$id:$sRow->id)."/edit"));
          // }else{
          //   return redirect()->to(url("backend/warehouse/".request('warehouse_id_fk')."/edit"));
          // }          

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\ZoneController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Zone::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Zone::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('z_maker', function($row) {
        if(@$row->z_maker!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->z_maker." ");
           return @$sD[0]->name;
        }else{
           return '';
        }
      })  
      ->addColumn('w_warehouse', function($row) {
        $dsWarehouse = \App\Models\Backend\Warehouse::find($row->warehouse_id_fk);
        return $dsWarehouse->w_name;

      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
