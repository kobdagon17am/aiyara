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

      $dsWarehouse  = \App\Models\Backend\Warehouse::get();
      return view('backend.warehouse.index')->with(['dsWarehouse'=>$dsWarehouse]);

    }

 public function create()
    {
      $sLocale  = \App\Models\Locale::all();
      return view('backend.warehouse.form');
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Warehouse::find($id);
       return View('backend.warehouse.form')->with(array('sRow'=>$sRow, 'id'=>$id) );
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
          }else{
            $sRow = new \App\Models\Backend\Warehouse;
          }

          $sRow->w_code    = request('w_code');
          $sRow->w_name    = request('w_name');
          $sRow->w_date_created    = request('w_date_created');
          $sRow->w_date_updated    = request('w_date_updated');
          $sRow->w_location    = request('w_location');
          $sRow->w_details    = request('w_details');
          $sRow->w_maker    = request('w_maker');

          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         // return redirect()->action('backend\WarehouseController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
          return redirect()->to(url("backend/warehouse/".($id?$id:0)."/edit"));

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
      ->addColumn('name', function($row) {
        // return $row->fname.' '.$row->surname;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
