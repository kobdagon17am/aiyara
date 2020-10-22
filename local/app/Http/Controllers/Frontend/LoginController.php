<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use App\Models\Frontend\CUser;
//use App\Http\Controllers\Session;
class LoginController extends Controller
{
    public function login(Request $req){

        // $get_users = DB::table('customers')
        // ->where('user_name','=',$req->username)
        // ->where('password','=',md5($req->password))
        // ->first();

      $get_users = CUser::where('user_name','=',$req->username)
        ->where('password','=',md5($req->password))
        ->first();
// dd($get_users);
        // if(count($get_users)>0){
 if($get_users){
          Auth::guard('c_user')->login($get_users);
            // session(['id' => $get_users[0]->id,
            //     'line_type' => $get_users[0]->line_type]);
          // dd('ok');
           // dd(Auth::user()->id);
            return redirect('home'); 
        }else{
           return redirect('/')->withError('Pless check username and password !.');

       }
   }

}
