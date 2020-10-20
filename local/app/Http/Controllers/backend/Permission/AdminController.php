<?php

namespace App\Http\Controllers\Backend\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
  public function index(Request $request)
  {
    $sPermission = \Auth::user()->permission;
    // dd($sPermission);
    // return view('backend.permission.admin.index');
    return view('backend.permission.admin.index',['sPermission'=>$sPermission]);

  }

  public function create()
  {
    $sLocale  = \App\Models\Locale::all();
    return view('backend.permission.admin.form',['sLocale'=>$sLocale]);
  }

  public function store(Request $request)
  {
    return $this->form();
  }

  public function update(Request $request, $id)
  {
    // dd($request);
    return $this->form($id);
  }

  public function form($id=NULL)
  {
    \DB::beginTransaction();
    try {
        if( $id ){
          $sRow = \App\Models\Backend\Permission\Admin::find($id);
          $sRow->updated_at = date('Y-m-d H:i:s');
        }else{
          $sRow = new \App\Models\Backend\Permission\Admin;
        }
        $sRow->name    = request('name');
        $sRow->email    = request('email');
        $sRow->tel    = request('tel');
        $sRow->department    = request('department');
        $sRow->position    = request('position');
        
        $sRow->permission    = request('permission')?request('permission'):'0';

        $sRow->isActive    = request('active')?request('active'):'N';

        if( request('password') ){
          $sRow->password    = \Hash::make( request('password') );
        }

        $sRow->save();
        \DB::commit();

      return redirect()->action('Backend\Permission\AdminController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
    } catch (\Exception $e) {
      echo $e->getMessage();
      \DB::rollback();
      return redirect()->action('Backend\Permission\AdminController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
    }
  }


  public function edit($id)
  {
    try {
      $sLocale  = \App\Models\Locale::all();
      $sRow = \App\Models\Backend\Permission\Admin::find($id);
      return View('backend.permission.admin.form')->with(array('sRow'=>$sRow, 'sLocale'=>$sLocale) );
    } catch (\Exception $e) {
      return redirect()->action('Backend\Permission\AdminController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
    }
  }


  public function destroy($id)
  {
    $sRow = \App\Models\Backend\Permission\Admin::find($id);
    if( $sRow ){
      $sRow->forceDelete();
    }
    return response()->json(\App\Models\Alert::Msg('success'));
  }

  public function Datatable(){
      // $sTable = \App\Models\Backend\Permission\Admin::with(['locale:locale,name'])->search()->where('id','>','2')->orderBy('id', 'asc');
      // $sTable = \App\Models\Backend\Permission\Admin::search()->orderBy('id', 'asc');
    if(\Auth::user()->permission==1){
      // $sTable = \App\Models\Backend\Permission\Admin::search()->where('id','>','1')->orderBy('id', 'asc');
      $sTable = \App\Models\Backend\Permission\Admin::search()->orderBy('id', 'asc');
    }else{
      $sTable = \App\Models\Backend\Permission\Admin::search()->where('id', \Auth::user()->id);
    }
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->make(true);
  }
}
