<?php

namespace App\Http\Controllers\Backend\Auth;

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

    protected function attemptLogin(Request $request)
    {
        if (\Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'isActive' => 'Y'], $request->get('remember'))) {
            \Cache::forget('Menu-'.\Auth::guard('admin')->user()->id);
            return redirect()->intended('backend/index');
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('backend/login');
    }
}
