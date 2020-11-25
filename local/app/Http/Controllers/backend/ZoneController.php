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

      $dsZone  = \App\Models\Backend\Zone::get();
      return view('backend.zone.index')->with(['dsZone'=>$dsZone]);

    }

    public function create($id)
    {
      $sSubwarehouse = \App\Models\Backend\Subwarehouse::find($id);
      $sWarehouse = \App\Models\Backend\Warehouse::find($sSubwarehouse->w_warehouse_id_fk);
      return View('backend.zone.form')->with(array('sSubwarehouse'=>$sSubwarehouse,'sWarehouse'=>$sWarehouse) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Zone::find($id);
       $sSubwarehouse = \App\Models\Backend\Subwarehouse::find($sRow->w_subwarehouse_id_fk);
       $sWarehouse = \App\Models\Backend\Warehouse::find($sSubwarehouse->w_warehouse_id_fk);
       return view('backend.zone.form')->with(['sRow'=>$sRow, 'id'=>$id ,'sSubwarehouse'=>$sSubwarehouse,'sWarehouse'=>$sWarehouse ]);
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
          }else{
            $sRow = new \App\Models\Backend\Zone;
          }

          // dd(request('w_warehouse'));

          $sRow->w_subwarehouse_id_fk    = request('w_subwarehouse_id_fk');
          $sRow->w_code    = request('w_code');
          $sRow->w_name    = request('w_name');
          $sRow->w_date_created    = request('w_date_created');
          $sRow->w_date_updated    = request('w_date_updated');
          $sRow->w_details    = request('w_details');
          $sRow->w_maker    = request('w_maker');

          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();
          
          if( $id ){
            return redirect()->to(url("backend/zone/".$id."/edit"));
          }else{
            // return redirect()->action('backend\ZoneController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
            return redirect()->to(url("backend/subwarehouse/".request('w_subwarehouse_id_fk')."/edit"));
          }          

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
      ->addColumn('w_warehouse', function($row) {
         // $dsSubwarehouse = \App\Models\Backend\Subwarehouse::find($row->w_subwarehouse_id_fk);
         // $dsWarehouse = \App\Models\Backend\Warehouse::find($dsSubwarehouse->w_warehouse_id_fk);
         // return $dsWarehouse->w_name;
        $dsSubwarehouse = \App\Models\Backend\Subwarehouse::find($row->w_subwarehouse_id_fk);
        $dsWarehouse = \App\Models\Backend\Warehouse::find($dsSubwarehouse->w_warehouse_id_fk);
        return $dsWarehouse->w_name;

      })
      ->addColumn('w_subwarehouse', function($row) {
         $dsSubwarehouse = \App\Models\Backend\Subwarehouse::find($row->w_subwarehouse_id_fk);
         return $dsSubwarehouse->w_name;

      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
