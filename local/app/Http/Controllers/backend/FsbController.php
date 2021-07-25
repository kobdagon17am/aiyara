<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class FsbController extends Controller
{

    public function index(Request $request)
    {

      $dsPackage  = \App\Models\Backend\Fsb::get();
      return view('backend.fsb.index')->with(['dsPackage'=>$dsPackage]);

    }

 public function create()
    {
      $sLocale  = \App\Models\Locale::all();
      return view('backend.fsb.form');
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Fsb::find($id);
       
       $dsPackage = \App\Models\Backend\Package::find($sRow->package_id_fk);
       // dd($dsPackage->dt_package);
       // dd($id);
       return View('backend.fsb.form')->with(array('sRow'=>$sRow, 'id'=>$id,'dsPackage'=>$dsPackage) );
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
            $sRow = \App\Models\Backend\Fsb::find($id);
          }else{
            $sRow = new \App\Models\Backend\Fsb;
          }

          // $sRow->package_id_fk    = request('package_id_fk');
          $sRow->g1    = request('g1');
          $sRow->g2    = request('g2');
          $sRow->g3    = request('g3');
          $sRow->g4    = request('g4');
          $sRow->g5    = request('g5');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

         return redirect()->action('backend\FsbController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\FsbController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Fsb::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Fsb::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('package_name', function($row) {
        $dsPackage = \App\Models\Backend\Package::find($row->package_id_fk);
        return @$dsPackage->dt_package;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
