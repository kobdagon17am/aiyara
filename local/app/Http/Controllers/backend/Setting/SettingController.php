<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AdminModel;
use App\Model\MenuModel;
use App\Model\SubMenuModel;
use App\Model\UserModel;
use Session;
use Storage;
use DB;
//use Datatables;
use Yajra\Datatables\Datatables;


class SettingController extends Controller
{
    public function index()
    {
        return view('backoffice.menu.menu_view');

    }

    #form insert
    public function create()
    {
        $sort = MenuModel::query()->orderBy('sort_menu', 'DESC')->limit(1)->first();
        $no = $sort->sort_menu + 1;
        $head = DB::table('type_head_menu')->get();
        return view('backoffice.menu.menu_edit', compact('no','head'));
    }

    #action insert
    public function store(Request $request)
    {
        $head_menu = $request->head_menu;

        $name = $request->name;
        $link = $request->link;
        $component = $request->component;
        $icon_menu = $request->icon_menu;
        $shout_menu = $request->shout_menu;
        $shout_css = $request->shout_css;
        $model = $request->model;
        $sort_menu = $request->sort_menu;
        $type_menu = $request->type_menu;

        $message = $request->message;
        $create = new MenuModel;
        $create->type_menu_id = $head_menu;
        $create->name_menu  = $name;
        $create->link_menu  = $link;
        $create->icon_menu  = $icon_menu;
        $create->shout_menu = $shout_menu;
        $create->shout_css  = $shout_css;
        $create->component_menu = $component;
        $create->sort_menu = $sort_menu;
        $create->type_menu = $type_menu;
        $create->note_menu = $message;
        $create->save();


        DB::insert("INSERT INTO history_log (log_desc, log_member, created_at) VALUES 
                ('เพิ่มเมนูใช้งาน > type_menu_id=$head_menu > ชื่อเมนู=$name', '".Session::get('name_admin')."', now())");


        if ($component != '') {
            $controller     = '' . $component . 'Controller';
            $modelStructure = '' . $component . 'Model';

            $contentsClass  = contentController($controller, $component);
            $contentsModel  = contentsModel($modelStructure);
            $index          = contentIndex($component, $link);
            $create         = contentCreate($component, $link);
            $update         = contentUpdate($component, $link);
            $show           = contentShow();
            Storage::disk('www')->put('local/resources/views/backoffice/' . $component . '/show.blade.php', $show);
            Storage::disk('www')->put('local/resources/views/backoffice/' . $component . '/index.blade.php', $index);
            Storage::disk('www')->put('local/resources/views/backoffice/' . $component . '/create.blade.php', $create);
            Storage::disk('www')->put('local/resources/views/backoffice/' . $component . '/edit.blade.php', $update);
            Storage::disk('www')->put('local/app/Http/Controllers/backend/' . $controller . '.php', $contentsClass);
            if ($model == 'open') {
                Storage::disk('www')->put('local/app/Model/' . $modelStructure . '.php', $contentsModel);
            }
        }
        return redirect(url('/backoffice/setting'))->with('success', 'เพิ่มเมนูสำเร็จ');
    }


    public function show($id)
    {
        $data = MenuModel::where('id_menu', $id)->first();
        return view('backoffice.menu.show', compact('data'));
    }

    #form update
    public function edit($id)
    {
        $data = MenuModel::where('id_menu', $id)->first();
        $head = DB::table('type_head_menu')->get();
        return view('backoffice.menu.edit', compact('data', 'id','head'));
    }

    #action update
    public function update(Request $request, $id)
    {
//        dd($id);
        $head_menu = $request->head_menu;
        $name_menu = $request->name_menu;
        $icon_menu = $request->icon_menu;
        $shout_menu = $request->shout_menu;
        $shout_css = $request->shout_css;
        $message = $request->message;
        $update = MenuModel::where('id_menu', $id)->update(
            [
                'type_menu_id' => $head_menu,
                'name_menu' => $name_menu,
                'icon_menu' => $icon_menu,
                'shout_menu' => $shout_menu,
                'shout_css' => $shout_css,
                'note_menu' => $message

            ]
        );
        if ($update) {
            return redirect(url('/backoffice/setting'))->with('success', 'อัพเดทข้อมูลสำเร็จ');

        }

    }

    public function destroy($id)
    {

        DB::insert("INSERT INTO history_log (log_desc, log_member, created_at) VALUES 
                ('ลบข้อมูล Setting > ID=$id ', '".Session::get('name_admin')."', now())");

        MenuModel::destroy($id);

    }

    public function sortMenu(Request $request)
    {
        $id = $request->id_menu;
        $sort = $request->sort_menu;
        $getTotal = MenuModel::query()->get();
        $Max = $getTotal->count();
        $checkData = MenuModel::where('id_menu', $id)->first();
        $thisNo = $checkData->sort_menu;
        if ($sort == 'up') {
            $nowNo = $thisNo - 1;
            if ($thisNo != '1') {
                $checkUp = MenuModel::where('sort_menu', $nowNo)->first();
                MenuModel::where('id_menu', $checkUp->id_menu)->update(
                    [
                        'sort_menu' => $thisNo
                    ]
                );

                MenuModel::where('id_menu', $id)->update(
                    [
                        'sort_menu' => $thisNo - 1
                    ]
                );
                return messageSuccess('Update sort menu success');
            } else {
                return messageInfo('Max sort menu');

            }

        } else {
            $nowNo = $thisNo + 1;
            if ($Max != $thisNo) {
                $checkUp = MenuModel::where('sort_menu', $nowNo)->first();
                MenuModel::where('id_menu', $checkUp->id_menu)->update(
                    [
                        'sort_menu' => $thisNo
                    ]
                );
                MenuModel::where('id_menu', $id)->update(
                    [
                        'sort_menu' => $thisNo + 1
                    ]
                );
                return messageSuccess('PUpdate sort menu success');
            } else {
                return messageInfo('Min sort menu');

            }
        }

    }

    public function sortMenuSub(Request $request)
    {
        $id = $request->id_menu;
        $sort = $request->sort_menu;
        $relation_id = $request->relation_id;
        $getTotal = SubMenuModel::query()->where('relation_menu', $relation_id)->get();
        $Max = $getTotal->count();
        $checkData = SubMenuModel::where('id_menu_sub', $id)->where('relation_menu', $relation_id)->first();
        $thisNo = $checkData->sort_menu;
        if ($sort == 'up') {
            $nowNo = $thisNo - 1;
            if ($thisNo != '1') {
                $checkUp = SubMenuModel::where('sort_menu', $nowNo)->where('relation_menu', $relation_id)->first();
                SubMenuModel::where('id_menu_sub', $checkUp->id_menu_sub)->where('relation_menu', $relation_id)->update(
                    [
                        'sort_menu' => $thisNo
                    ]
                );

                SubMenuModel::where('id_menu_sub', $id)->where('relation_menu', $relation_id)->update(
                    [
                        'sort_menu' => $thisNo - 1
                    ]
                );
                return messageSuccess('Update sort menu success');
            } else {
                return messageInfo('Max sort menu');

            }

        } else {
            $nowNo = $thisNo + 1;
            if ($Max != $thisNo) {
                $checkUp = SubMenuModel::where('sort_menu', $nowNo)->where('relation_menu', $relation_id)->first();
                SubMenuModel::where('id_menu_sub', $checkUp->id_menu_sub)->where('relation_menu', $relation_id)->update(
                    [
                        'sort_menu' => $thisNo
                    ]
                );
                SubMenuModel::where('id_menu_sub', $id)->where('relation_menu', $relation_id)->update(
                    [
                        'sort_menu' => $thisNo + 1
                    ]
                );
                return messageSuccess('PUpdate sort menu success');
            } else {
                return messageInfo('Min sort menu');

            }
        }

    }

    public function queryDatatable()
    {
        $data = MenuModel::query()->orderBy('sort_menu', 'DESC')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('sort', function ($data) {
                $up = 'up';
                $down = 'down';
                if ($data->sort_menu != '') {
                    $sort = '<center><h5>' . $data->sort_menu . '</h5>
               <button type="button" onclick="sortByMenu(' . $data->id_menu . ',\'' . $up . '\')"  class="btn btn-success btn-outline-success btn-icon"><i  class="icofont icofont-arrow-up "></i></button>
               <button type="button" onclick="sortByMenu(' . $data->id_menu . ',\'' . $down . '\')"  class="btn btn-success btn-outline-success btn-icon"><i  class="icofont icofont-arrow-down "></i></button></center>';
                } else {
                    $sort = '';
                }
                return $sort;
            })
            ->addColumn('Link', function ($data) {
                if ($data->link_menu != '') {
                    $link = '' . $data->link_menu . '<br>
                <a class="btn btn-primary btn-outline-primary" href="' . url('backoffice/' . $data->link_menu) . '" target="_blank" title="link">
                <i class="icofont icofont-link-alt"></a>';
                } else {
                    $link = '';
                }
                return $link;
            })
            ->addColumn('component', function ($data) {
                if ($data->component_menu == '') {
                    $component = 'Add Submenu <button onclick="window.location=\'' . url('backoffice/setting/' . $data->id_menu . '/submenu') . '\'" style="color:white;" class="btn btn-info btn-icon"><i  class="icofont icofont-plus"></i></button>';
                } else {
                    $component = $data->component_menu;
                }
                return $component;
            })
            ->addColumn('Icon', function ($data) {
                $Icon = '<i class="' . $data->icon_menu . '"> </i> ' . $data->icon_menu;
                return $Icon;
            })
            ->addColumn('Manage', function ($data) {
                $Manage = buttonManageData($data->id_menu, true, true, true, 'backoffice/setting');
                return $Manage;
            })
            ->rawColumns(['sort', 'Link', 'component', 'Icon', 'Manage'])
            ->make(true);
    }

    function viewSubmenu($id)
    {
        $dataSub = SubMenuModel::query()->where('relation_menu', $id)->orderBy('sort_menu', 'ASC')->get();
        $count = $dataSub->count() + 1;
        $data = MenuModel::query()->where('id_menu', $id)->first();
        return view('backoffice.menu.view_submenu', compact('id', 'data', 'dataSub', 'count'));
    }

    function updateSubmenu(Request $request)
    {
        $name = $request->name;
        $link = $request->link;
        $component = $request->component;
        $shout_menu = $request->shout_menu;
        $shout_css = $request->shout_css;
        $model = $request->model;
        $sort_menu = $request->sort_menu;
        $message = $request->message;
        $submenu_id = $request->submenu_id;
        $main = $request->main;
        if ($submenu_id != '') {
            $data = SubMenuModel::where('id_menu_sub', $submenu_id)->update(
                [
                    'name_menu' => $name,
                    'link_menu' => $link,
                    'component_menu' => $component,
                    'shout_menu' => $shout_menu,
                    'shout_css' => $shout_css,
                    'note_menu' => $message,
                ]
            );
        } else {
            $data = new SubMenuModel;
            $data->name_menu = $name;
            $data->link_menu = $link;
            $data->component_menu = $component;
            $data->shout_menu = $shout_menu;
            $data->shout_css = $shout_css;
            $data->sort_menu = $sort_menu;
            $data->note_menu = $message;
            $data->relation_menu = $main;
            $data->save();

            DB::insert("INSERT INTO history_log (log_desc, log_member, created_at) VALUES 
                ('แก้ไขเมนูใช้งาน > id_menu_sub=$submenu_id > ชื่อเมนู=$name', '".Session::get('name_admin')."', now())");


            if ($component != '') {
                $controller = '' . $component . 'Controller';
                $modelStructure = '' . $component . 'Model';
                $contentsClass = contentController($controller, $component);
                $contentsModel = contentsModel($modelStructure);
                $index = contentIndex($component, $link);
                $create = contentCreate($component, $link);
                $update = contentUpdate($component, $link);
                $show = contentShow();
                Storage::disk('www')->put('local/resources/views/backoffice/' . $component . '/show.blade.php', $show);
                Storage::disk('www')->put('local/resources/views/backoffice/' . $component . '/index.blade.php', $index);
                Storage::disk('www')->put('local/resources/views/backoffice/' . $component . '/create.blade.php', $create);
                Storage::disk('www')->put('local/resources/views/backoffice/' . $component . '/edit.blade.php', $update);
                Storage::disk('www')->put('local/app/Http/Controllers/backend/' . $controller . '.php', $contentsClass);
                if ($model == 'open') {
                    Storage::disk('www')->put('local/app/Model/' . $modelStructure . '.php', $contentsModel);
                }
            }
        }

        return redirect(url('backoffice/setting/' . $main . '/submenu'))->with('success', 'อัพเดทเมนูย่อยเรียบร้อย');
    }

    function deleteSub($main, $id)
    {
        SubMenuModel::destroy($id);
        return messageSuccess('Delete sub menu success');

    }

    public function viewHead()
    {
//        dd("s");
        $data = DB::table('type_head_menu')->orderBy('sort_header_menu', 'ASC')->get();
        return view('backoffice.menu.manage_head', compact('data'));

    }

    public function addHead(Request $request)
    {
        $check = DB::table('type_head_menu')->get();
        $count = count($check) + 1 ;
        if ($request->idUpdate != '') {
            DB::table('type_head_menu')->where('id_type_menu',$request->idUpdate)->update([
                'name_type_menu' => $request->name_type_menu,
            ]);
        } else {
            DB::table('type_head_menu')->insert([
                'name_type_menu' => $request->name_type_menu,
                'sort_header_menu' =>$count
            ]);

        }
        return redirect(url('backoffice/setting'))->with('success', 'อัพเดทหัวข้อเมนูสำเร็จ');

    }

    public function postHead()
    {
        $text ='';
        $data = DB::table('type_head_menu')->orderBy('sort_header_menu', 'ASC')->get();
        foreach ($data AS $row) {
        $text .=  '<tr>
                 <th scope = "row" >' . $row->sort_header_menu . '</th >
                   <td>' . $row->name_type_menu . '</td >
                   <td>
                     <button type = "button" onclick = "sortHead(\'' . $row->id_type_menu . '\',\'up\')" class="btn btn-danger" ><i class=" ti-arrow-up" ></i ></button >
                     <button type = "button" onclick = "sortHead(\'' . $row->id_type_menu . '\',\'down\')" class="btn btn-danger" ><i class=" ti-arrow-down" ></i ></button >
                   </td >
                   <td ><button type = "button" onclick = "editHead(\'' . $row->id_type_menu . '\',\'' . $row->name_type_menu . '\')" class="btn btn-warning" ><u ><i class="icofont icofont-pencil" ></i ></u ></button ></td >
                 </tr >';
        }
        return $text;
    }

    public function sortHead(Request $request)
    {
        $id = $request->id_head;
        $sort = $request->sort_head;
        $getTotal = DB::table('type_head_menu')->get();
        $Max = $getTotal->count();
        $checkData = DB::table('type_head_menu')->where('id_type_menu', $id)->first();
        $thisNo = $checkData->sort_header_menu;
        if ($sort == 'up') {
            $nowNo = $thisNo - 1;
            if ($thisNo != '1') {
                $checkUp = DB::table('type_head_menu')->where('sort_header_menu', $nowNo)->first();
                DB::table('type_head_menu')->where('id_type_menu', $checkUp->id_type_menu)->update(
                    [
                        'sort_header_menu' => $thisNo
                    ]
                );

                DB::table('type_head_menu')->where('id_type_menu', $id)->update(
                    [
                        'sort_header_menu' => $thisNo - 1
                    ]
                );
                return messageSuccess('Update sort menu success');
            } else {
                return messageInfo('Max sort menu');

            }

        } else {
            $nowNo = $thisNo + 1;
            if ($Max != $thisNo) {
                $checkUp = MenuModel::where('sort_menu', $nowNo)->first();
                MenuModel::where('id_menu', $checkUp->id_menu)->update(
                    [
                        'sort_menu' => $thisNo
                    ]
                );
                MenuModel::where('id_menu', $id)->update(
                    [
                        'sort_menu' => $thisNo + 1
                    ]
                );
                return messageSuccess('PUpdate sort menu success');
            } else {
                return messageInfo('Min sort menu');

            }
        }
    }
}
