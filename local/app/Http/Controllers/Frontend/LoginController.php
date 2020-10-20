<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Illuminate\Support\Facades\Hash;
//use App\Http\Controllers\Session;
class LoginController extends Controller
{
    public function login(Request $req){

        $get_users = DB::table('customers')
        ->where('user_name','=',$req->username)
        ->where('password','=',md5($req->password))
        ->limit(1)
        ->get();

        if(count($get_users)>0){
            session(['id' => $get_users[0]->id,
                'line_type' => $get_users[0]->line_type]);

            return redirect('home'); 

        }else{
           return redirect('/')->withError('Pless check username and password !.');

       }
   }

}
