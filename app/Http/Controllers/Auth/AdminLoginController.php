<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/admin/dashboard';
    public function __construct()
    {
        $this->middleware('guest.admin')->except('logout');
    }
    // public function redirectTo()
    // {
    //     $user = auth()->user();
    //     dd($user);
    //     // $role = 
    //     // switch ($role) {
    //     //     case 'admin':
    //     //         return '/admin_dashboard';
    //     //         break;
    //     //     case 'seller':
    //     //         return '/seller_dashboard';
    //     //         break;

    //     //     default:
    //     //         return '/home';
    //     //         break;
    //     // }
    // }
    public function guard()
    {
     return Auth::guard('admin');
    }

    public function showLoginForm()
    {
        return view('admin.loginnew');
    }
    public function logout(Request $request)
    {
        Auth::guard("admin")->logout();
        $request->session()->forget('site_id');
        return redirect('/admin');
    }
}