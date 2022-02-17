<?php


namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use App\Models\UserModel;
use App\Models\MenuPermissionModel;

class AdminController extends Controller
{
  public function index(Request $request)
  {
    $sPermission = \Auth::user()->permission;
    // dd($sPermission);
    // return view('backend.admin.index');
    return view('backend.permission.index',['sPermission'=>$sPermission]);

  }

  public function create()
  {
    $sRole_group = \App\Models\Backend\Role::get();
    $sBranchs = \App\Models\Backend\Branchs::get();
    $position_level = DB::select("select * from dataset_position_level");
    // dd($position_level);
    return view('backend.permission.form',['sRole_group'=>$sRole_group,'sBranchs'=>$sBranchs,'position_level'=>$position_level]);
  }

  public function edit($id)
  {

    // dd(\Hash::make('@12345'));

    try {
      $sLocale  = \App\Models\Locale::all();
      $sRow = \App\Models\Backend\Permission\Admin::find($id);
      // dd($sRow);
      $sRole_group = \App\Models\Backend\Role::get();
      $sBranchs = \App\Models\Backend\Branchs::orderBy('b_name', 'asc')->get();
      $position_level = DB::select("select * from dataset_position_level");
      return View('backend.permission.form')->with(array('sRow'=>$sRow, 'sLocale'=>$sLocale,'sRole_group'=>$sRole_group,'sBranchs'=>$sBranchs,'position_level'=>$position_level));
    } catch (\Exception $e) {
      return redirect()->action('backend\AdminController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
    }
  }

  public function store(Request $request)
  {
    return $this->form();
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
          $sRow = \App\Models\Backend\Permission\Admin::find($id);
          $sRow->updated_at = date('Y-m-d H:i:s');
        }else{
          $sRow = new \App\Models\Backend\Permission\Admin;
        }
        $branch_id_fk = \App\Models\Backend\Branchs::find(request('branch_id_fk'));

        if(\Auth::user()->permission==1){

              $sRow->name    = request('name');
              $sRow->email    = request('email');
              $sRow->tel    = request('tel');
              $sRow->branch_id_fk    = request('branch_id_fk');
              $sRow->business_location_id_fk    = $branch_id_fk->business_location_id_fk;
              $sRow->department    = request('department');
              $sRow->position    = request('position');
              $sRow->position_level    = request('position_level');
              $sRow->permission    = request('permission')?request('permission'):'0';
              $sRow->role_group_id_fk    = request('role_group_id_fk');
              $sRow->isActive    = request('active')?request('active'):'N';
              $sRow->can_edit_profile = request('can_edit_profile') ?: 0;

        }else{

              $sRow->tel    = request('tel');

        }

        if( request('password') ){
          $sRow->password    = \Hash::make( request('password') );
        }

        // dd(\Hash::make( request('password') ));

        $sRow->save();
        \DB::commit();

         return redirect()->to(url("backend/permission/".$sRow->id."/edit"));

         // return redirect()->action('backend\AdminController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

    } catch (\Exception $e) {
      echo $e->getMessage();
      \DB::rollback();
      return redirect()->action('backend\AdminController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
    }
  }


  public function roles($id)
  {

    // dd($id);

        // $data = \App\Models\MenuModel::query()->orderBy('sort', 'ASC')->get();
        $data = \App\Models\MenuModel::query()->where('isActive', 'Y')->get();
        return view('backend.permission.roles', compact('data', 'id'));

  }


    public function updateRoles(Request $request, $id)
    {
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
        return redirect(url('/backend/admin'))->with('success', 'จัดการเลือกเมนูสำเร็จ');
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
      ->addColumn('branch', function($row) {
        if(@$row->branch_id_fk!=''){
          $sD = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
           return @$sD[0]->b_name;
        }else{
           return '';
        }
      }) 
      ->addColumn('business_location', function($row) {
        if(@$row->business_location_id_fk!=''){
          $sD = DB::select(" select * from dataset_business_location where id=".$row->business_location_id_fk." ");
           return @$sD[0]->txt_desc;
        }else{
           return '';
        }
      }) 
      ->make(true);
  }
}
