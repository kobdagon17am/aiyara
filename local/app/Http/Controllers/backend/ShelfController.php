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

      $dsZone  = \App\Models\Backend\Shelf::get();
      return view('backend.shelf.index')->with(['dsZone'=>$dsZone]);

    }

 public function create()
    {
      $dsZone = \App\Models\Backend\Zone::get();
      return View('backend.shelf.form')->with(array(
        'dsZone'=>$dsZone,
      ) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $dsZone = \App\Models\Backend\Zone::get();
       $sRow = \App\Models\Backend\Shelf::find($id);
       return View('backend.shelf.form')->with(array('sRow'=>$sRow, 'id'=>$id , 'dsZone'=>$dsZone) );
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
          }else{
            $sRow = new \App\Models\Backend\Shelf;
          }

          // dd(request('w_warehouse'));

          $sRow->w_zone_id_fk    = request('w_zone');
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

           return redirect()->action('backend\ShelfController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

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
      ->addColumn('w_warehouse', function($row) {
         $dsZone = \App\Models\Backend\Zone::find($row->w_zone_id_fk);
         $dsSubwarehouse = \App\Models\Backend\Subwarehouse::find($dsZone->w_subwarehouse_id_fk);
         $dsWarehouse = \App\Models\Backend\Warehouse::find($dsSubwarehouse->w_warehouse_id_fk);
         return $dsWarehouse->w_name;
      })      
      ->addColumn('w_subwarehouse', function($row) {
         $dsZone = \App\Models\Backend\Zone::find($row->w_zone_id_fk);
         $dsSubwarehouse = \App\Models\Backend\Subwarehouse::find($dsZone->w_subwarehouse_id_fk);
         return $dsSubwarehouse->w_name;
         // return $row->w_zone_id_fk;
         // return $dsZone->w_subwarehouse_id_fk;
      })
      ->addColumn('w_zone', function($row) {
         $dsZone = \App\Models\Backend\Zone::find($row->w_zone_id_fk);
         return $dsZone->w_name;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
