<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DbCustomersDetail;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('customer');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = DB::table('customers')
            ->select('customers.business_name', 'customers.prefix_name', 'customers.first_name', 'customers.last_name', 'customers.user_name', 'customers.created_at', 'customers.date_mt_first', 'customers.pv_mt_active',
                'customers.pv_mt', 'customers.pv_mt', 'customers.bl_a', 'customers.bl_b', 'customers.bl_c', 'customers.pv_a', 'customers.pv_b', 'customers.pv_c',
                'customers.pv', 'dataset_package.dt_package', 'dataset_qualification.code_name', 'q_max.code_name as max_code_name',
                'q_max.business_qualifications as max_q_name', 'dataset_qualification.business_qualifications as q_name', 'customers.team_active_a', 'customers.team_active_b', 'customers.team_active_c')
            ->leftjoin('dataset_package', 'dataset_package.id', '=', 'customers.package_id')
            ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
            ->leftjoin('dataset_qualification as q_max', 'q_max.id', '=', 'customers.qualification_max_id')
            ->where('customers.user_name', '=', Auth::guard('c_user')->user()->user_name)
            ->first();

        return view('frontend/profile', compact('data'));
    }

    // public function edit_profile()
    // {

    //     return view('frontend/edit_profile',compact('provinces'));
    // }

    public function profile_address()
    {
        $customer = DB::table('customers_detail')
            ->select('customers_detail.*', 'dataset_provinces.id as provinces_id', 'dataset_provinces.name_th as provinces_name', 'dataset_amphures.name_th as amphures_name', 'dataset_amphures.id as amphures_id', 'dataset_districts.id as district_id', 'dataset_districts.name_th as district_name')

            ->leftjoin('dataset_provinces', 'dataset_provinces.id', '=', 'customers_detail.province_id_fk')
            ->leftjoin('dataset_amphures', 'dataset_amphures.id', '=', 'customers_detail.amphures_id_fk')
            ->leftjoin('dataset_districts', 'dataset_districts.id', '=', 'customers_detail.district_id_fk')
            ->where('customer_id', '=', Auth::guard('c_user')->user()->id)
            ->first();

        $provinces = DB::table('dataset_provinces')
            ->select('*')
            ->get();
        return view('frontend/profile_address', compact('customer', 'provinces'));
    }

    public function edit_address(Request $request)
    {

        $checkpass = DB::table('customers')
            ->where('id', '=', Auth::guard('c_user')->user()->id)
            ->where('password', '=', md5($request->password))
            ->count();
        if ($checkpass == 1) {
            $data = array('house_no' => trim($request->house_no),
                'house_name' => trim($request->house_name),
                'moo' => trim($request->moo),
                'soi' => trim($request->soi),
                'amphures_id_fk' => trim($request->amphures),
                'district_id_fk' => trim($request->district),
                'road' => trim($request->road),
                'province_id_fk' => trim($request->province),
                'zipcode' => trim($request->zipcode),
            );

            try {
                DbCustomersDetail::updateOrCreate([
                    'customer_id' => Auth::guard('c_user')->user()->id,
                ], $data);

                //  $update = DB::table('customers_detail')
                //  ->where('customer_id','=',Auth::guard('c_user')->user()->id)
                //  ->update($data);

                return redirect('profile_address')->withSuccess('Update Address Success');

            } catch (Exception $e) {
                return redirect('profile_address')->withError('Update Address Error');

            }

        } else {
            return redirect('profile_address')->withError('Wrong password');

        }

    }

    public function profile_img()
    {
        return view('frontend/profile_img');
    }

    public function update_img_profile(Request $request)
    {
        try {
            if ($request->imgBase64 != null) {
                $photoBase64 = $request->imgBase64;
                $imageBase = $photoBase64;
                $image_array_1 = explode(";", $imageBase);
                $image_array_2 = explode(",", $image_array_1[1]);
                $imageBase = base64_decode($image_array_2[1]);

                if (!is_dir('local/public/profile_customer/' . date('Ym'))) {
                    // dir doesn't exist, make it
                    mkdir('local/public/profile_customer/' . date('Ym'));
                }

                $imageName = 'local/public/profile_customer/' . date('Ym') . '/' . date('YmdHis') . '_' . Auth::guard('c_user')->user()->id . '.jpg';
                $name = date('Ym') . '/' . date('YmdHis') . '_' . Auth::guard('c_user')->user()->id . '.jpg';
                file_put_contents($imageName, $imageBase);
            } elseif ($request->imgBase64 == null) {
                $profile->profile_photo = "";
            }
            $update = DB::table('customers')
                ->where('id', '=', Auth::guard('c_user')->user()->id)
                ->update(['profile_img' => $name]);
            return redirect('profile_img')->withSuccess('Upload image Success');
        } catch (Exception $e) {
            return redirect('profile_img')->withError('Upload image Error');

        }

    }

    public function update_profile(Request $request)
    {
        try {
            if ($request->imgBase64 != null) {
                $photoBase64 = $request->imgBase64;
                $imageBase = $photoBase64;
                $image_array_1 = explode(";", $imageBase);
                $image_array_2 = explode(",", $image_array_1[1]);
                $imageBase = base64_decode($image_array_2[1]);
                $randint = rand();
                $randtime = time();
                $name = $randint . $randtime . '.jpg';
                $imageName = 'local/public/profile_customer/' . $randint . $randtime . '.jpg';
                file_put_contents($imageName, $imageBase);
            } elseif ($request->imgBase64 == null) {
                $profile->profile_photo = "";
            }
            return redirect('cart')->withSuccess('Upload image Success');

        } catch (Exception $e) {
            return redirect('cart')->withError($e);
        }

        //$profile->save();

    }
}
