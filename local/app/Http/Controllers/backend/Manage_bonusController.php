<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Manage_bonusController extends Controller
{

    public function index(Request $request)
    {

      $dsManageBonus  = \App\Models\Backend\Manage_bonus::get();
      return view('backend.manage_bonus.index')->with(['dsManageBonus'=>$dsManageBonus]);

    }

 public function create()
    {
      $sLocale  = \App\Models\Locale::all();
      return view('backend.manage_bonus.form');
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Manage_bonus::find($id);

       $dsPackage = \App\Models\Backend\Package::find($sRow->package_id_fk);
       // dd($dsPackage->dt_package);
       // dd($id);
       return View('backend.manage_bonus.form')->with(array('sRow'=>$sRow, 'id'=>$id,'dsPackage'=>$dsPackage) );
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
            $sRow = \App\Models\Backend\Manage_bonus::find($id);
          }else{
            $sRow = new \App\Models\Backend\Manage_bonus;
          }

          $sRow->bonus_perday    = request('bonus_perday');
          $sRow->bonus_perround    = request('bonus_perround');
          $sRow->bonus_permonth    = request('bonus_permonth');
          $sRow->benefit    = request('benefit');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         return redirect()->action('backend\Manage_bonusController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Manage_bonusController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Manage_bonus::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Manage_bonus::search()->orderBy('id', 'asc');
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
