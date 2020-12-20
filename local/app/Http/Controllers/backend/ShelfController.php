<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class ShelfController extends Controller
{

    public function index(Request $request)
    {
    }

    public function create($id)
    {
      $sZone = \App\Models\Backend\Zone::find($id);
      $sWarehouse = \App\Models\Backend\Warehouse::find($sZone->warehouse_id_fk);
      $sBranchs = \App\Models\Backend\Branchs::find($sWarehouse->branch_id_fk);
      return View('backend.shelf.form')->with(array('sZone'=>$sZone,'sWarehouse'=>$sWarehouse,'sBranchs'=>$sBranchs) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Shelf::find($id);
       $sZone = \App\Models\Backend\Zone::find($sRow->zone_id_fk);
       $sWarehouse = \App\Models\Backend\Warehouse::find($sZone->warehouse_id_fk);
       $sBranchs = \App\Models\Backend\Branchs::find($sWarehouse->branch_id_fk);
       $sMaker_name = DB::select(" select * from ck_users_admin where id=".$sRow->s_maker." ");
       return view('backend.shelf.form')->with(['sRow'=>$sRow, 'id'=>$id ,'sZone'=>$sZone ,'sWarehouse'=>$sWarehouse,'sBranchs'=>$sBranchs,'sMaker_name'=>$sMaker_name  ]);
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
            $sRow = \App\Models\Backend\Shelf::find($id);
            $sRow->updated_at = date('Y-m-d H:i:s');
          }else{
            $sRow = new \App\Models\Backend\Shelf;
          }

          $sRow->zone_id_fk    = request('zone_id_fk');
          $sRow->s_code    = request('s_code');
          $sRow->s_name    = request('s_name');
          $sRow->s_details    = request('s_details');
          $sRow->s_maker    = request('s_maker');

          $sRow->status    = request('status')?request('status'):0;
          $sRow->save();


          \DB::commit();
 
           return redirect()->to(url("backend/shelf/".($id?$id:$sRow->id)."/edit"));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\ShelfController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Shelf::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Shelf::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('s_maker', function($row) {
        if(@$row->s_maker!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->s_maker." ");
           return @$sD[0]->name;
        }else{
           return '';
        }
      })        
      // ->addColumn('w_warehouse', function($row) {
      //    $dsZone = \App\Models\Backend\Zone::find($row->w_zone_id_fk);
      //    $dsWarehouse = \App\Models\Backend\Warehouse::find($dsZone->warehouse_id_fk);
      //    return $dsWarehouse->w_name;
      // })      
      // ->addColumn('w_zone', function($row) {
      //    $dsZone = \App\Models\Backend\Zone::find($row->w_zone_id_fk);
      //    return $dsZone->w_name;
      // })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
