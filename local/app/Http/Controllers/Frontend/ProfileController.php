<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        return view('frontend/profile');
    }

     public function edit_profile()
    {
        return view('frontend/edit_profile');
    }

    public function update_profile(Request $request){
        try {
            if ($request->imgBase64 != null) {
                $photoBase64 = $request->imgBase64;
                $imageBase = $photoBase64;
                $image_array_1 = explode(";", $imageBase);
                $image_array_2 = explode(",", $image_array_1[1]);
                $imageBase = base64_decode($image_array_2[1]);
                $randint = rand();
                $randtime = time();
                $name = $randint.$randtime.'.jpg';
                $imageName = 'local/public/profile_customer/'.$randint.$randtime.'.jpg';
                file_put_contents($imageName, $imageBase);
            } elseif ($request->imgBase64 == null) {
                $profile->profile_photo = "";
            }
            dd('success');
            
        } catch (Exception $e) {
             dd($e);

        }


       
            //$profile->save();

    }
}
