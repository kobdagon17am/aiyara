<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AdminModel;
use App\Model\MenuModel;
use App\Model\UserModel;
use DB;
use Session;
use Storage;
//use Datatables;
use Yajra\Datatables\Datatables;



class ProfileController extends Controller
{
    public function index()
    {
        $id = Session::get("id_admin");
        $data = AdminModel::where('relation_admin', $id)->first();

        return view('backoffice.profile.index', compact('data'));
    }


    public function create()
    {
    }


    public function store(Request $request)
    {
        //img profile
        $id = Session::get('id_admin');
        $getImg = UserModel::where('id_admin', $id)->first();
        $getImgBackground = AdminModel::where('relation_admin', $id)->first();
        if ($request->pofile != '') {
            if ($getImg) {
                Storage::delete('admin/' . $getImg->img_profile);
            }
            $Profile = insertSingleImage($request->pofile, 'admin');
            $updateSystem = UserModel::where('id_admin', $id)->update(['img_profile' => $Profile]);
            session(['img_admin' => $Profile]);
            return redirect(url('/backoffice/profile'))->with('success', 'อัพเดทรูปโปรไฟล์สำเร็จ');

        }
        if ($request->backgroundFile != '') {
            if ($getImgBackground) {
                Storage::delete('admin/' . $getImgBackground->background_profile);

            }
            $background = insertSingleImage($request->backgroundFile, 'admin');
            $update = AdminModel::updateOrCreate(
                [
                    'relation_admin' => $id
                ],
                [
                    'background_profile' => $background
                ])->save();

            DB::insert("INSERT INTO history_log (log_desc, log_member, created_at) VALUES 
                ('เพิ่ม Profile > id_admin=$id ', '".Session::get('name_admin')."', now())");

            return redirect(url('/backoffice/profile'))->with('error', 'อัพเดทข้อมูลไม่สำเร็จ');
        }


    }

    public function show($id)
    {
    }


    public function edit($id)
    {

    }


    public function update(Request $request, $id)
    {
//        dd($request);
        $full_name = $request->full_name;
        $sex = $request->sex;
        $birthdate = $request->birthdate;
        $single = $request->single;
        $phone = $request->phone;
        $twitter = $request->twitter;
        $skype = $request->skype;
        $website = $request->website;
        $address = $request->address;
//        dd($id);
//        full_name	gender_admin	birth_date	marital_status	location_admin	mobile_number	twitter_admin skype_admin	website	background_profile	relation_admin
        $update = AdminModel::updateOrCreate(
            [
                'relation_admin' => $id
            ],
            [
                'full_name' => $full_name,
                'gender_admin' => $sex,
                'birth_date' => $birthdate,
                'marital_status' => $single,
                'location_admin' => $address,
                'mobile_number' => $phone,
                'twitter_admin' => $twitter,
                'skype_admin' => $skype,
                'website' => $website
            ])->save();
        if ($update) {

            DB::insert("INSERT INTO history_log (log_desc, log_member, created_at) VALUES 
                ('แก้ไข Profile > id_admin=$id ', '".Session::get('name_admin')."', now())");

            return redirect(url('/backoffice/profile'))->with('success', 'อัพเดทข้อมูลสำเร็จ');

        }
    }

    public function destroy($id)
    {

    }





}
