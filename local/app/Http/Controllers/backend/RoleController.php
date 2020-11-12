<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class RoleController extends Controller
{

    public function index(Request $request)
    {
        $sPermission = \Auth::user()->permission;
        return view('backend.role.index',['sPermission'=>$sPermission]);
    }

   public function create()
    {
      return View('backend.role.form');
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sMenu_All = \App\Models\MenuModel::query()->where('isActive', 'Y')->get();
       $sRow = \App\Models\Backend\Role::find($id);
       return View('backend.role.form')->with(array('sRow'=>$sRow, 'id'=>$id, 'sMenu_All'=>$sMenu_All));
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
            $sRow = \App\Models\Backend\Role::find($id);
          }else{
            $sRow = new \App\Models\Backend\Role;
          }
          $sRow->role_name    = request('role_name');
          $sRow->status    = request('status')?request('status'):0;    
          $sRow->created_at = date('Y-m-d H:i:s');
          $id_user = request('id_user');
          $nameMenu = request('nameMenu');
          // dd($id_user);
          // dd($nameMenu);
          DB::delete(" DELETE FROM role_permit where role_group_id_fk=$id;");
          DB::select(" ALTER TABLE role_permit AUTO_INCREMENT=1; ");
          if(!empty($nameMenu)){
              foreach ($nameMenu AS $row) {
                    $dnow = date("Y-m-d H:i:s");
                    DB::table('role_permit')->insert(
                        [
                            'role_group_id_fk' => $id_user,
                            'menu_id_fk' => $row,
                            'created_at' => $dnow
                        ]
                    );
              }

          }

          $sRow->save();

          \DB::commit();

         return redirect()->action('backend\RoleController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\RoleController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Role::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Role::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('access_menu', function($row) {
          $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$row->id)->get();
          $array = array();
          foreach ($menu_permit as $key => $value) {
            $menu_name = DB::table('ck_backend_menu')->where('id',$value->menu_id_fk)->first();
            array_push($array, $menu_name->name);
          }
          $arr = implode(',', $array);
          if($row->id==1){
            return 'เข้าถึงทุกเมนู';
          }else{
            return $arr;
          }
      })
      ->addColumn('member_ingroup', function($row) {
        $sRow = \App\Models\Backend\Permission\Admin::where('role_group_id_fk', $row->id )->get();
        $array = array();
        foreach ($sRow as $key => $value) {
            array_push($array, $value->name);
        }
        $arr = implode(',', $array);
        return $arr;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
