<?php

namespace App\Http\Controllers\backend\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{

    use AuthenticatesUsers;
    protected $guard = 'admin';
    protected $redirectTo = 'backend/index';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm() {
       return view('backend.auth.login');
    }

    public function showLoginFormADM() {
       return view('backend.auth.login-adm');
    }

    protected function attemptLogin(Request $request)
    {
        if (\Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'isActive' => 'Y'], $request->get('remember'))) {
            \Cache::forget('Menu-'.\Auth::guard('admin')->user()->id);
            setcookie('username', $request->email, time()+60*60*24*365);
            setcookie('password', $request->password, time()+60*60*24*365);
            return redirect()->intended('backend/index');
        }

        // $r = DB::select(" select * from ck_users_admin where email=".$request->email." ");

        // if (\Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $r[0]->password, 'isActive' => 'Y'], $request->get('remember'))) {
        //     \Cache::forget('Menu-'.\Auth::guard('admin')->user()->id);
        //     setcookie('username', $request->email, time()+60*60*24*365);
        //     setcookie('password', $request->password, time()+60*60*24*365);
        //     return redirect()->intended('backend/index');
        // }

    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('backend/login');
    }
}
