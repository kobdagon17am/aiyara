<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class SubwarehouseController extends Controller
{

    public function index(Request $request)
    {

      $dsSubwarehouse  = \App\Models\Backend\Subwarehouse::get();
      return view('backend.subwarehouse.index')->with(['dsSubwarehouse'=>$dsSubwarehouse]);

    }

    public function create($id)
    {
      $sWarehouse = \App\Models\Backend\Warehouse::find($id);
      return View('backend.subwarehouse.form')->with(array('sWarehouse'=>$sWarehouse) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

      public function edit($id)
    {
       $sRow = \App\Models\Backend\Subwarehouse::find($id);
       $sWarehouse = \App\Models\Backend\Warehouse::find($sRow->w_warehouse_id_fk);
       return view('backend.subwarehouse.form')->with(['sRow'=>$sRow, 'id'=>$id ,'sSubwarehouse'=>$sRow,'sWarehouse'=>$sWarehouse ]);
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
            $sRow = \App\Models\Backend\Subwarehouse::find($id);
          }else{
            $sRow = new \App\Models\Backend\Subwarehouse;
          }

          // dd(request('w_warehouse'));

          $sRow->w_warehouse_id_fk    = request('w_warehouse_id_fk');
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

           // return redirect()->action('backend\SubwarehouseController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

          if( $id ){
            return redirect()->to(url("backend/subwarehouse/".$id."/edit"));
          }else{
            // return redirect()->action('backend\ZoneController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
            return redirect()->to(url("backend/warehouse/".request('w_warehouse_id_fk')."/edit"));
          } 


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\SubwarehouseController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Subwarehouse::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Subwarehouse::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('w_warehouse', function($row) {
        // return $row->fname.' '.$row->surname;
         $dsWarehouse = \App\Models\Backend\Warehouse::find($row->w_warehouse_id_fk);
         return $dsWarehouse->w_name;

      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
