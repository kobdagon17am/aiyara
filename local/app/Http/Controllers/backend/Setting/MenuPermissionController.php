<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Models\MenuModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Validator;
use DB;
use Session;
use Storage;
use App\Models\UserModel;
use App\Models\MenuPermissionModel;

class MenuPermissionController extends Controller
{
    public function index()
    {
        // return view('backend.MenuPermission.index');
        // $last_created   = "
        //     SELECT created_at as d FROM menu_admin ORDER BY created_at DESC LIMIT 1
        //     ";
        // $last_created = DB::select($last_created);

        // return view('backend.MenuPermission.index', [
        //     'last_created'     => $last_created,
        // ]);     

        // $sTable = \App\Models\UserModel::get();   
        return view('backend.MenuPermission.index');

        // dd($sTable);

    }

    public function create()
    {
        return view('backend.MenuPermission.create');

    }

    public function store(Request $request)
    {


    }

    public function show($id)
    {
        $data = UserModel::query()->where('id', $id)->first();
        $getRowData = DB::table('menu_admin')
            ->where('admin_id', $id)->get();
        return view('backend.MenuPermission.show', compact('data', 'getRowData'));


    }

    public function edit($id)
    {
        $data = MenuModel::query()->orderBy('sort', 'ASC')->get();
        return view('backend.MenuPermission.edit', compact('data', 'id'));
        // return view('backend.MenuPermission.edit');
    }


    public function update(Request $request, $id)
    {

        // dd($request);

        $id_user = $request->id_user;
        $nameMenu = $request->nameMenu;
        $subMenu = $request->subMenu;
        MenuPermissionModel::where('admin_id', $id)->delete();;
        foreach ($nameMenu AS $row) {
            if (@count($subMenu[$row]) > 0) {
                $sub = '';
                $i = 1;
                foreach ($subMenu[$row] AS $rowSubMenu) {
                    if ($i != count($subMenu[$row])) {
                        $comma = ',';
                        } else {
                        $comma = '';
                    }
                    $sub .= $rowSubMenu.$comma;
                    $i++;
                }
                DB::table('menu_admin')->insert(
                    [
                        'admin_id' => $id_user,
                        'main_menu_id' => $row,
                        'submenu_id' => $sub
                    ]
                );

            } else {
                $dnow = date("Y-m-d H:i:s");
                DB::table('menu_admin')->insert(
                    [
                        'admin_id' => $id_user,
                        'main_menu_id' => $row,
                        'created_at' => $dnow
                    ]
                );
            }
        }
        return redirect(url('/backend/permission/admin'))->with('success', 'จัดการเลือกเมนูสำเร็จ');
    }

    public function destroy($id)
    {

    }

    public function queryDatatable()
    {

      $sTable = \App\Models\UserModel::get();   
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('name', function($row) {
        // return $row->Prename_desc;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }

}
