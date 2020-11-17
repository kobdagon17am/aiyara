<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class DepartmentController extends Controller
{

    public function index(Request $request)
    {
        return view('backend.department.index');
    }

   public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }
  
    public function destroy($id)
    {
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Department::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('access_menu', function($row) {
          $menu_permit = DB::table('Department_permit')->where('Department_group_id_fk',$row->id)->get();
          $array = array();
          foreach ($menu_permit as $key => $value) {
            $menu_name = DB::table('ck_backend_menu')->where('id',$value->menu_id_fk)->get();
            array_push($array, @$menu_name[0]->name);
          }
          $arr = implode(',', $array);
          if($row->id==1){
            return 'เข้าถึงทุกเมนู';
          }else{
            return $arr;
          }
      })
      ->addColumn('member_ingroup', function($row) {
        // $sRow2 = \App\Models\Backend\Permission\Admin::where('Department_group_id_fk', $row->id )->get();
        $sRows = DB::table('ck_users_admin')->where('Department_group_id_fk',$row->id)->get();
        $array = array();
        foreach (@$sRows as $k => $v) {
            array_push($array, @$v->name);
        }
        $arr = implode(',', @$array);
        return $arr;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
